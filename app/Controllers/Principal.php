<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Curps;
use App\Libraries\Fechas;
use App\Libraries\Funciones;
use App\Models\Mglobal;
use DateTime;



use stdClass;
use CodeIgniter\API\ResponseTrait;
require_once FCPATH . "qr_code/autoload.php";
require_once FCPATH . "mpdf/autoload.php";
require 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Principal extends BaseController
{

    use ResponseTrait;
    private $defaultData = array(
        'title' => 'Turnos 2.0',
        'layout' => 'plantilla/lytDefault',
        'contentView' => 'vUndefined',
        'stylecss' => '',
    );
    public function __construct()
    {
        setlocale(LC_TIME, 'es_ES.utf8', 'es_MX.UTF-8', 'es_MX', 'esp_esp', 'Spanish'); // usar solo LC_TIME para evitar que los decimales los separe con coma en lugar de punto y fallen los inserts de peso y talla
        date_default_timezone_set('America/Mexico_City');
        $session = \Config\Services::session();
        if ($session->get('logueado') != 1) {
            header('Location:' . base_url() . 'index.php/Login/cerrar?inactividad=1');
            die();
        }
    }

    private function _renderView($data = array())
    {
        $data = array_merge($this->defaultData, $data);
        echo view($data['layout'], $data);
    }

    private function normalizeUtf8Value($value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->normalizeUtf8Value($item);
            }
            return $value;
        }

        if (is_object($value)) {
            foreach ($value as $key => $item) {
                $value->$key = $this->normalizeUtf8Value($item);
            }
            return $value;
        }

        if (!is_string($value) || $value === '') {
            return $value;
        }

        if (mb_check_encoding($value, 'UTF-8')) {
            if (function_exists('mb_scrub')) {
                return mb_scrub($value, 'UTF-8');
            }
            return $value;
        }

        $converted = @mb_convert_encoding($value, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
        if ($converted !== false && $converted !== null) {
            if (function_exists('mb_scrub')) {
                $converted = mb_scrub($converted, 'UTF-8');
            }
            return $converted;
        }

        $converted = @iconv('Windows-1252', 'UTF-8//IGNORE', $value);
        if ($converted !== false && $converted !== null) {
            if (function_exists('mb_scrub')) {
                $converted = mb_scrub($converted, 'UTF-8');
            }
            return $converted;
        }

        $converted = utf8_encode($value);
        if (function_exists('mb_scrub')) {
            $converted = mb_scrub($converted, 'UTF-8');
        }

        return $converted;
    }

    private function cleanMpdfHtml(string $html): string
    {
        if ($html === '') {
            return $html;
        }

        $html = preg_replace('/^\xEF\xBB\xBF/', '', $html);

        $encoding = mb_detect_encoding($html, ['UTF-8', 'Windows-1252', 'ISO-8859-1', 'ASCII'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $converted = @mb_convert_encoding($html, 'UTF-8', $encoding);
            if ($converted !== false && $converted !== null) {
                $html = $converted;
            }
        }

        if (function_exists('mb_scrub')) {
            $html = mb_scrub($html, 'UTF-8');
        }

        $iconvHtml = @iconv('UTF-8', 'UTF-8//IGNORE', $html);
        if ($iconvHtml !== false && $iconvHtml !== null) {
            $html = $iconvHtml;
        }

        $html = preg_replace('/[^\x09\x0A\x0D\x20-\x7E\x{00A0}-\x{D7FF}\x{E000}-\x{FFFD}]/u', '', $html);

        return $html ?? '';
    }


    private function uploadFileToS3Storage(string $sourceFile, string $module, string $subfolder, string $fileName): ?string
    {
        try {
            $s3 = new \App\Libraries\S3Service();
            $baseFolder = trim($module, '/');
            $targetFolder = $baseFolder;

            if (!$s3->folderExists($baseFolder)) {
                $s3->createFolder($baseFolder);
            }

            if ($subfolder !== '') {
                $targetFolder .= '/' . trim($subfolder, '/');
                if (!$s3->folderExists($targetFolder)) {
                    $s3->createFolder($targetFolder);
                }
            }

            $s3Key = $targetFolder . '/' . $fileName;
            $uploaded = $s3->uploadFile($sourceFile, $s3Key);

            return $uploaded ? $s3Key : null;
        } catch (\Throwable $e) {
            log_message('error', 'Error al subir archivo de ' . $module . ' a S3: ' . $e->getMessage());
            return null;
        }
    }

    private function resolveStoredFileUrl(?string $storedPath, string $localPrefix = ''): ?string
    {
        if (empty($storedPath)) {
            return null;
        }

        if (preg_match('#^https?://#i', $storedPath)) {
            return $storedPath;
        }

        if (strpos($storedPath, 'assets/') === 0) {
            return base_url($storedPath);
        }

        if ($localPrefix !== '' && strpos($storedPath, '/') === false) {
            return base_url(trim($localPrefix, '/') . '/' . $storedPath);
        }

        try {
            $s3 = new \App\Libraries\S3Service();
            $presignedUrl = $s3->getPresignedUrl($storedPath, '+20 minutes');
            if (!empty($presignedUrl)) {
                return $presignedUrl;
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error al resolver URL de archivo almacenado: ' . $e->getMessage());
        }

        return base_url('index.php/Principal/verArchivoS3?key=' . rawurlencode($storedPath));
    }

    private function resolveStoredFilePreviewUrl(?string $storedPath, string $localPrefix = ''): ?string
    {
        if (empty($storedPath)) {
            return null;
        }

        if (preg_match('#^https?://#i', $storedPath)) {
            return $storedPath;
        }

        if (strpos($storedPath, 'assets/') === 0) {
            return base_url($storedPath);
        }

        if ($localPrefix !== '' && strpos($storedPath, '/') === false) {
            return base_url(trim($localPrefix, '/') . '/' . $storedPath);
        }

        return base_url('index.php/Principal/verArchivoS3?key=' . rawurlencode($storedPath));
    }

    private function resolveStoredFileDownloadUrl(?string $storedPath): ?string
    {
        if (empty($storedPath)) {
            return null;
        }

        if (preg_match('#^https?://#i', $storedPath)) {
            return $storedPath;
        }

        return base_url('index.php/Principal/descargarArchivoAlmacenado?path=' . rawurlencode($storedPath));
    }

    private function mapInstrumentoUrls($instrumentoRaw, bool $forzarDescarga = false): array
    {
        if (empty($instrumentoRaw)) {
            return [];
        }

        if (is_array($instrumentoRaw)) {
            $instrumentos = $instrumentoRaw;
        } elseif (is_string($instrumentoRaw)) {
            $instrumentos = json_decode($instrumentoRaw, true);
            if (!is_array($instrumentos)) {
                $instrumentos = [$instrumentoRaw];
            }
        } else {
            $instrumentos = [(string) $instrumentoRaw];
        }

        $resultado = [];
        foreach ($instrumentos as $ruta) {
            $url = $forzarDescarga ? $this->resolveStoredFileDownloadUrl($ruta) : $this->resolveStoredFilePreviewUrl($ruta);
            if ($url) {
                $resultado[] = [
                    'ruta' => $ruta,
                    'url' => $url,
                ];
            }
        }

        return $resultado;
    }

    private function obtenerCorreosRevisionJuridica(): array
    {
        return [
            'lvelaga@guanajuato.gob.mx',
            'al.hernandezma@guanajuato.gob.mx',
        ];
    }

    private function obtenerConfiguracionModuloArchivos(string $modulo): ?array
    {
        $configuraciones = [
            'contrato' => [
                'tabla' => 'solicitud_contrato_archivos',
                'id_campo' => 'id_solicitud_contrato_archivo',
                'id_solicitud_campo' => 'id_solicitud_contrato',
                'storage_modulo' => 'contratos',
                'ruta_listado' => 'index.php/Principal/ListaSolicitudContrato',
                'ruta_modulo' => 'solicitud_contrato',
            ],
            'convenio' => [
                'tabla' => 'solicitud_convenio_archivos',
                'id_campo' => 'id_archivo',
                'id_solicitud_campo' => 'id_solicitud_convenio',
                'storage_modulo' => 'convenios',
                'ruta_listado' => 'index.php/Principal/ListaSolicitudConvenio',
                'ruta_modulo' => 'solicitud_convenio',
            ],
            'honorarios' => [
                'tabla' => 'solicitud_honorario_archivos',
                'id_campo' => 'id_solicitud_honorario_archivos',
                'id_solicitud_campo' => 'id_solicitud_honorario',
                'storage_modulo' => 'honorarios',
                'ruta_listado' => 'index.php/Principal/listadoHonorarios',
                'ruta_modulo' => 'solicitud_honorario',
            ],
            'adquisiciones' => [
                'tabla' => 'solicitud_adquisiciones_archivos',
                'id_campo' => 'id_solicitud_adquisiciones_archivo',
                'id_campos_posibles' => [
                    'id_solicitud_adquisiciones_archivo',
                    'id_solicitud_adquisiciones_archivos',
                    'id_solicitud_adquisicion_archivo',
                    'id_solicitud_adquisicion_archivos',
                    'id_archivo',
                ],
                'id_solicitud_campo' => 'id_solicitud_adquisiciones',
                'storage_modulo' => 'adquisiciones',
                'ruta_listado' => 'index.php/Principal/ListaSolicitudAdquisiciones',
                'ruta_modulo' => 'solicitud_adquisiciones',
            ],
        ];

        return $configuraciones[$modulo] ?? null;
    }

    private function obtenerCampoIdArchivoModulo(Mglobal $globals, array $configModulo): string
    {
        $camposPosibles = array_values(array_unique(array_filter(array_merge(
            [$configModulo['id_campo'] ?? null],
            $configModulo['id_campos_posibles'] ?? []
        ))));

        $columnasArchivo = $this->obtenerColumnasTablaServicio($globals, $configModulo['tabla']);
        if (empty($columnasArchivo)) {
            return (string) ($camposPosibles[0] ?? ($configModulo['id_campo'] ?? 'id_archivo'));
        }

        foreach ($camposPosibles as $campo) {
            if (in_array($campo, $columnasArchivo, true)) {
                return (string) $campo;
            }
        }

        return (string) ($configModulo['id_campo'] ?? 'id_archivo');
    }

    private function actualizarEstatusSolicitudArchivo(Mglobal $globals, array $configModulo, int $idSolicitud, int $idEstatus, string $script): void
    {
        if ($idSolicitud <= 0 || empty($configModulo['ruta_modulo']) || empty($configModulo['id_solicitud_campo'])) {
            return;
        }

        $session = \Config\Services::session();
        $dataUpdate = ['id_estatus' => $idEstatus];
        $columnasSolicitud = $this->obtenerColumnasTablaServicio($globals, $configModulo['ruta_modulo']);

        if (in_array('usu_act', $columnasSolicitud, true)) {
            $dataUpdate['usu_act'] = $session->id_usuario ?? 0;
        }

        if (in_array('fec_act', $columnasSolicitud, true)) {
            $dataUpdate['fec_act'] = date('Y-m-d H:i:s');
        }

        $globals->saveTabla(
            $dataUpdate,
            ["tabla" => $configModulo['ruta_modulo'], "editar" => true, "idEditar" => [$configModulo['id_solicitud_campo'] => $idSolicitud]],
            ['id_user' => $session->id_usuario ?? 0, 'script' => $script]
        );
    }

    public function verArchivoS3()
    {
        $storedPath = (string) $this->request->getGet('key');
        if ($storedPath === '') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Archivo no especificado.');
        }

        $s3 = new \App\Libraries\S3Service();
        $tempFile = $s3->downloadToTempFile($storedPath, 's3_view_');

        if ($tempFile === false || !is_file($tempFile)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('No fue posible recuperar el archivo.');
        }

        $mimeType = function_exists('mime_content_type') ? mime_content_type($tempFile) : 'application/octet-stream';
        if (empty($mimeType)) {
            $mimeType = 'application/octet-stream';
        }

        $fileName = basename($storedPath);
        $contents = file_get_contents($tempFile);
        @unlink($tempFile);

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $fileName . '"')
            ->setBody($contents === false ? '' : $contents);
    }

    public function descargarArchivoAlmacenado()
    {
        $storedPath = trim((string) $this->request->getGet('path'));
        if ($storedPath === '') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Archivo no especificado.');
        }

        $fileName = basename($storedPath);
        $tempFile = null;

        if (strpos($storedPath, 'assets/') === 0) {
            $fullPath = realpath(FCPATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $storedPath));
            $assetsPath = realpath(FCPATH . 'assets');

            if ($fullPath === false || $assetsPath === false || strpos($fullPath, $assetsPath) !== 0 || !is_file($fullPath)) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('No fue posible recuperar el archivo.');
            }

            $filePath = $fullPath;
        } else {
            $s3 = new \App\Libraries\S3Service();
            $tempFile = $s3->downloadToTempFile($storedPath, 's3_download_');

            if ($tempFile === false || !is_file($tempFile)) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('No fue posible recuperar el archivo.');
            }

            $filePath = $tempFile;
        }

        $mimeType = function_exists('mime_content_type') ? mime_content_type($filePath) : 'application/octet-stream';
        if (empty($mimeType)) {
            $mimeType = 'application/octet-stream';
        }

        $contents = file_get_contents($filePath);
        if ($tempFile !== null) {
            @unlink($tempFile);
        }

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->setBody($contents === false ? '' : $contents);
    }

    private function obtenerNombreConPuesto(Mglobal $globals, $idUsuario, bool $usarDireccion = false): string
    {
        if (empty($idUsuario)) {
            return '';
        }

        $tabla = $usarDireccion ? 'vw_direccion' : 'vw_usuario';
        $consulta = $globals->getTabla([
            'tabla' => $tabla,
            'where' => ['id_usuario' => $idUsuario, 'visible' => 1]
        ]);

        if (empty($consulta->data)) {
            return '';
        }

        $registro = $consulta->data[0];
        $nombre = trim((string) ($registro->nombre_completo ?? ''));
        $puesto = trim((string) ($registro->dsc_puesto ?? ''));

        if ($nombre !== '' && $puesto !== '') {
            return $nombre . ' - ' . $puesto;
        }

        return $nombre !== '' ? $nombre : $puesto;
    }

    private function obtenerDatosFirmaUsuario(Mglobal $globals, $idUsuario): ?object
    {
        if (empty($idUsuario)) {
            return null;
        }

        $registro = null;
        foreach (['vw_direccion', 'vw_usuario'] as $tabla) {
            $consulta = $globals->getTabla([
                'tabla' => $tabla,
                'where' => ['id_usuario' => $idUsuario, 'visible' => 1]
            ]);

            if (!empty($consulta->data)) {
                $registro = $consulta->data[0];
                break;
            }
        }

        if ($registro === null) {
            return null;
        }

        return (object) [
            'nombre' => trim((string) ($registro->nombre_completo ?? '')),
            'cargo' => trim((string) ($registro->dsc_puesto ?? '')),
        ];
    }

    private function obtenerFichaTecnicaData($result): array
    {
        return [
            'em_domicilio' => $result->em_domicilio,
            'fecha_realizacion' => $result->fecha_realizacion,
            'nombre_evento' => $result->nombre_evento,
            'persona_solicitud' => $result->persona_solicitud,
            'municipio_sede' => $result->municipio_sede,
            'periodicidad_radio' => $result->periodicidad_radio,
            'antecedentes' => $result->antecedentes,
            'objetivo_general' => $result->objetivo_general,
            'justificacion' => $result->justificacion,
            'cadena_valor' => $result->cadena_valor,
            'nivel_habilidades' => '',
            'estrato' => !$result->estrato ? $result->estrato : '',
            'asistentes_totales' => $result->asistentes_totales,
            'asistentes_local' => $result->asistentes_local,
            'asistentes_regional' => $result->asistentes_regional,
            'asistentes_nacional' => $result->asistentes_nacional,
            'asistentes_internacional' => $result->asistentes_internacional,
            'alcance' => $result->alcance,
            'derrama_total' => $result->derrama_total,
            'derrama_local' => $result->derrama_local,
            'derrama_foraneo' => $result->derrama_foraneo,
            'empleos_mujeres' => $result->empleos_mujeres,
            'empleos_hombres' => $result->empleos_hombres,
            'empleos_discapacidad' => $result->empleos_discapacidad,
            'cuota_acceso' => $result->cuota_acceso,
            'cuantas_cuotas' => (isset($result->cuantas_cuotas) && !empty($result->cuantas_cuotas)) ? $result->cuantas_cuotas : 'N/A',
            'costo_total' => (isset($result->costo_total) && !empty($result->costo_total)) ? $result->costo_total : 'N/A',
            'desglose_costo' => $result->desglose_costo,
            'cantidades_desglose' => (isset($result->cantidades_desglose) && !empty($result->cantidades_desglose)) ? $result->cantidades_desglose : 'N/A',
            'montos_desglose' => $result->montos_desglose,
            'antecedentes_evento' => $result->antecedentes_evento,
            'propuesta_valor' => $result->propuesta_valor,
            'inclusion_mujeres' => $result->inclusion_mujeres,
            'programa_preliminar' => $result->programa_preliminar,
            'otras_actividades' => $result->otras_actividades,
            'link_web' => $result->link_web,
            'facebook' => $result->facebook,
            'fb_seguidores' => $result->fb_seguidores,
            'twitter' => $result->twitter,
            'tw_seguidores' => $result->tw_seguidores,
            'instagram' => $result->instagram,
            'ig_seguidores' => $result->ig_seguidores,
            'youtube' => $result->youtube,
            'yt_seguidores' => $result->yt_seguidores,
            'tiktok' => $result->tiktok,
            'tk_seguidores' => $result->tk_seguidores,
            'co_nombre' => $result->co_nombre,
            'co_telefono' => $result->co_telefono,
            'co_razon_social' => $result->co_razon_social,
            'co_cargo' => $result->co_cargo,
            'co_celular' => $result->co_celular,
            'co_domicilio' => $result->co_domicilio,
            'co_ciudad_estado' => $result->co_ciudad_estado,
            'co_email' => $result->co_email,
            'em_nombre' => $result->em_nombre,
            'em_cargo' => $result->em_cargo,
            'em_celular' => $result->em_celular,
            'em_telefono_fijo' => $result->em_telefono_fijo,
            'em_ciudad_estado' => $result->em_ciudad_estado,
            'em_email' => $result->em_email,
            'apoyo_federal' => $result->apoyo_federal,
            'apoyo_municipal' => $result->apoyo_municipal,
            'apoyo_estatal' => $result->apoyo_estatal,
            'descripcion_apoyos' => $result->descripcion_apoyos,
        ];
    }

    private function generarPdfFichaTemporal($id, array $data): string
    {
        $mpdfConfig = [
            'mode' => 'utf-8',
            'format' => 'Legal',
            'orientation' => 'P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ];

        $mpdf = new \Mpdf\Mpdf($mpdfConfig);
        $html = view("pdfs/vpdfFicha.php", $data);
        $html = $this->normalizeUtf8Value($html);
        $html = $this->cleanMpdfHtml($html);
        $mpdf->WriteHTML($html);

        $pdfPath = WRITEPATH . 'uploads/Ficha_Tecnica_' . $id . '_' . date('Ymd_His') . '.pdf';
        $mpdf->Output($pdfPath, 'F');

        return $pdfPath;
    }

    public function index()
    {

        $session = \Config\Services::session();
        $data = array();
        $data['scripts'] = array('principal');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vVacio';
        $this->_renderView($data);

    }

    public function GeneradorQr()
    {
        $data = [];
        $data['scripts'] = [];
        $data['contentView'] = 'personal/vGeneradorQr';
        $this->_renderView($data);
    }

    public function generarQrLink()
    {
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'No fue posible generar el codigo QR';

        $link = trim((string) $this->request->getPost('link'));
        if ($link === '') {
            $response->respuesta = 'El link es requerido';
            return $this->respond($response);
        }

        if (!filter_var($link, FILTER_VALIDATE_URL)) {
            $response->respuesta = 'El link no tiene un formato valido';
            return $this->respond($response);
        }

        try {
            $qr = Builder::create()
                ->writer(new PngWriter())
                ->data($link)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(420)
                ->margin(12)
                ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
                ->labelText('')
                ->labelFont(new NotoSans(16))
                ->labelAlignment(new LabelAlignmentCenter())
                ->build();

            $response->error = false;
            $response->respuesta = 'Codigo QR generado correctamente';
            $response->dataUri = $qr->getDataUri();
            $response->link = $link;
        } catch (\Throwable $e) {
            log_message('error', 'Error al generar QR: ' . $e->getMessage());
        }

        return $this->respond($response);
    }

    public function uploadCSV()
    {
        $response = new \stdClass();
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response->error = true;
        $response->respuesta = 'No se subió ningún archivo válido';

        $file = $this->request->getFile('fileParticipantes');

        if (!$file->isValid()) {
            return $this->respond($response);
        }

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            $registrosProcesados = 0;
            $errores = [];



            foreach ($rows as $index => $row) {
                if (empty($row[0]) || empty($row[1])) {
                    continue;
                }

                try {
                    $noEmpleado = $row[0];
                    $fechaHora = $row[1];

                    // Convertir fecha
                    $fechaHoraObj = DateTime::createFromFormat('d/m/Y H:i', $fechaHora);
                    if (!$fechaHoraObj) {
                        $errores[] = "Fila " . ($index + 1) . ": Formato de fecha inválido";
                        continue;
                    }

                    $fecha = $fechaHoraObj->format('Y-m-d');
                    $hora = $fechaHoraObj->format('H:i:s');

                    // Obtener ID usuario
                    $dataDB = ['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'no_empleado' => $noEmpleado]];
                    $userResponse = $globals->getTabla($dataDB);

                    if (empty($userResponse->data)) {
                        $errores[] = "Fila " . ($index + 1) . ": Usuario $noEmpleado no encontrado";
                        continue;
                    }

                    $idUsuario = $userResponse->data[0]->id_usuario;

                    // Verificar si ya existe registro para esta fecha
                    $asistenciaDB = [
                        'tabla' => 'asistencia',
                        'where' => [
                            'visible' => 1,
                            'id_usuario' => $idUsuario,
                            'fecha' => $fecha
                        ]
                    ];

                    $asistenciaExistente = $globals->getTabla($asistenciaDB);

                    // Determinar si es entrada o salida (lógica mejorada)
                    $esEntrada = ($hora <= '12:00:00'); // Antes del mediodía = entrada
                    $esSalida = ($hora >= '12:00:00');  // Después del mediodía = salida

                    if (!empty($asistenciaExistente->data)) {
                        // ACTUALIZAR registro existente
                        $registro = $asistenciaExistente->data[0];
                        $datosActualizar = [];

                        if ($esEntrada && (empty($registro->entrada) || $registro->entrada == '00:00:00')) {
                            $datosActualizar['entrada'] = $hora;
                        }

                        if ($esSalida && (empty($registro->salida) || $registro->salida == '00:00:00')) {
                            $datosActualizar['salida'] = $hora;
                        }

                        if (!empty($datosActualizar)) {
                            $dataConfig = [
                                "tabla" => "asistencia",
                                "editar" => true,
                                "idEditar" => ['id_asistencia' => $registro->id_asistencia]
                            ];

                            $globals->saveTabla($datosActualizar, $dataConfig, ["script" => "Principal.asistenciaExcel"]);
                        }

                    } else {
                        // CREAR nuevo registro
                        $datosNuevos = [
                            'id_usuario' => $idUsuario,
                            'fecha' => $fecha,
                            'entrada' => $esEntrada ? $hora : '00:00:00',
                            'salida' => $esSalida ? $hora : '00:00:00',
                            'visible' => 1
                        ];

                        $dataConfig = [
                            "tabla" => "asistencia",
                            "editar" => false
                        ];

                        $globals->saveTabla($datosNuevos, $dataConfig, ["script" => "Principal.asistenciaExcel"]);
                    }

                    $registrosProcesados++;

                } catch (Exception $e) {
                    $errores[] = "Fila " . ($index + 1) . ": " . $e->getMessage();
                }
            }

            $response->error = false;
            $response->respuesta = "Procesados $registrosProcesados registros. Errores: " . count($errores);
            $response->errores = $errores;

        } catch (Exception $e) {
            $response->respuesta = "Error al procesar archivo: " . $e->getMessage();
        }

        return $this->respond($response);
    }
    public function uploadCSV2()
    {
        $response = new \stdClass();
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response->error = true;
        $response->respuesta = 'No se subió ningún archivo válido';

        if (isset($_FILES['fileParticipantes']) && $_FILES['fileParticipantes']['error'] == 0) {
            $filePath = $_FILES['fileParticipantes']['tmp_name'];
            $data = [];
            $header = [];
            $startProcessing = false;
            $currentName = null;

            if (($handle = fopen($filePath, "r")) !== false) {
                while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                    $encodedRow = array_map('utf8_encode', $row);
                    $cleanRow = array_map('trim', $encodedRow);

                    // Buscar línea con nombre (ahora verificando la columna C)
                    if (isset($cleanRow[0])) {
                        // Versión más robusta para detectar nombres
                        if (strtolower($cleanRow[0]) === 'nombre' && !empty($cleanRow[2])) {
                            $currentName = $cleanRow[2]; // Columna C (índice 2) contiene el nombre
                            continue;
                        }

                        // Detectar encabezado real
                        if (!$startProcessing && strtolower($cleanRow[0]) === 'id') {
                            $header = array_map('strtolower', $cleanRow);
                            $startProcessing = true;
                            continue;
                        }

                        // Procesar filas de datos
                        if ($startProcessing && is_numeric($cleanRow[0])) {
                            $rowAssoc = array_combine($header, $cleanRow);
                            $rowAssoc['nombre_empleado'] = $currentName;
                            $data[] = $rowAssoc;
                        }
                    }
                }
                fclose($handle);
            }

            // Validación de columnas
            $columnasRequeridas = ['id', 'fecha', 'turno', 'entrada', 'salida', 'trabajado', 'tarde / temprano'];
            $columnasFaltantes = array_diff($columnasRequeridas, $header);

            if (!empty($columnasFaltantes)) {
                $response->error = true;
                $response->respuesta = 'Faltan columnas: ' . implode(', ', $columnasFaltantes);
                return $this->respond($response);
            }

            foreach ($data as $row) {
                if (empty($row['fecha']))
                    continue;

                // Extraer tarde / temprano
                $tarde = null;
                $temprano = null;
                if (isset($row['tarde / temprano']) && strpos($row['tarde / temprano'], '/') !== false) {
                    [$tarde, $temprano] = explode('/', $row['tarde / temprano']);
                    $tarde = trim($tarde);
                    $temprano = trim($temprano);
                }


                $nombreUser = $row['nombre_empleado'];
                $like = [
                    'nombre_completo' => "%$nombreUser%",
                ];
                $proveedor = $globals->getTabla([
                    'tabla' => 'vw_usuario',
                    'where' => ['visible' => 1],
                    'orlike' => $like,
                    'limit' => 1
                ]);
                if (isset($proveedor->data) && !empty($proveedor->data)) {
                    $registro = [
                        'id_usuario' => $proveedor->data[0]->id_usuario,
                        'fecha' => DateTime::createFromFormat('d/m/Y', $row['fecha'])->format('Y-m-d'),
                        'turno' => $row['turno'] ?? null,
                        'entrada' => $row['entrada'] ?? null,
                        'salida' => $row['salida'] ?? null,
                        'trabajado' => $row['trabajado'] ?? null,
                        'tarde' => $tarde,
                        'temprano' => $temprano,
                    ];
                    $dataConfig = [
                        "tabla" => "asistencia",
                        "editar" => false
                    ];
                    $response = $globals->saveTabla($registro, $dataConfig, ["script" => "Usuario.tiket"]);
                    $response->error = false;
                    $response->respuesta = 'Carga se guardo correctamente';

                }

            }

        }


        return $this->respond($response);
    }
    public function reporteIncidenciaUsuario($fechaInicio = null, $fechaFin = null, $idUsuario = null, $folio = null)
    {
        $Mglobal = new Mglobal;
        if ($fechaInicio != 0) {
            $usuario = $Mglobal->getTabla([
                'tabla' => 'vw_usuario',
                'where' => ['visible' => 1, 'id_usuario' => $idUsuario]
            ])->data[0]->nombre_completo;
            $html = '
                <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                    <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                        <!-- Encabezado con logotipo -->
                        <div style="background-color: #004080; padding: 20px; text-align: center; color:white">
                        <h2> SUSI</h2>
                        </div>

                        <!-- Contenido principal -->
                        <div style="padding: 30px; color: #333;">
                            <h2 style="color: #004080;">Reporte de Incidencias del Usuario</h2>
                            <p style="font-size: 16px;">A continuación se presenta el reporte con datos verificados correspondientes al usuario:</p>
                            
                            <p style="font-size: 16px;"><strong>Nombre:</strong> ' . $usuario . '</p>
                            <p style="font-size: 16px;"><strong>Periodo:</strong> del ' . date("d-m-Y", strtotime($fechaInicio)) . ' al ' . date("d-m-Y", strtotime($fechaFin)) . '</p>
                            <p style="font-size: 16px;"><strong>Folio de seguimiento:</strong> <span style="color: red;">' . $folio . '</span></p>
                            
                            <p style="font-size: 14px; color: #888;">Este reporte ha sido generado con fines administrativos para su debido seguimiento.</p>
                        </div>

                        <!-- Pie de página -->
                        <div style="background-color: #e0e0e0; text-align: center; padding: 15px; font-size: 13px; color: #666;">
                            © ' . date('Y') . ' Sistema de Atención SUSI. Todos los derechos reservados - SECTURI.
                        </div>
                    </div>
                </div>
                ';
        } else {
            $html = '
            <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    
                    <!-- Encabezado con logotipo o título -->
                    <div style="background-color: #004080; padding: 20px; text-align: center; color: white;">
                        <h2>SUSI</h2>
                    </div>

                    <!-- Contenido principal -->
                    <div style="padding: 30px; color: #333;">
                        <h2 style="color: #004080;">Reporte de Incidencias</h2>
                        <p style="font-size: 16px;">
                            Este documento ha sido generado por el Sistema Unificado de SECTURI (SUSI) y contiene información verificada.
                        </p>
                        <p style="font-size: 16px;">
                            El número de folio <strong>' . $folio . '</strong> debe coincidir con el asignado en el reporte de incidencias correspondiente.
                        </p>
                        <p style="font-size: 14px; color: #888;">
                            Este reporte es de carácter administrativo y está destinado exclusivamente para fines de control y seguimiento interno.
                        </p>
                    </div>

                    <!-- Pie de página -->
                    <div style="background-color: #e0e0e0; text-align: center; padding: 15px; font-size: 13px; color: #666;">
                        © ' . date('Y') . ' Sistema de Atención SUSI. Todos los derechos reservados – SECTURI.
                    </div>
                </div>
            </div>
            ';

        }


        echo $html;
        die();
    }



    /*    public function uploadCSV()
       {
           $response = new \stdClass();
           $session = \Config\Services::session();

           if (isset($_FILES['fileParticipantes']) && $_FILES['fileParticipantes']['error'] == 0) {
               $filePath = $_FILES['fileParticipantes']['tmp_name'];

               // Lee el archivo CSV y convierte sus datos en un array
               $data = [];

               if (($handle = fopen($filePath, "r")) !== false) {
                   $header = fgetcsv($handle, 1000, ","); // Lee la primera fila como encabezado

                   while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                       $encodedRow = array_map('utf8_encode', $row); // Codifica los valores a UTF-8
                       $courseData = array_combine($header, $encodedRow); // Combina encabezado y valores

                       $data[] = $courseData;
                   }
                   fclose($handle);
               }
               $columnasRequeridas = [
                   'nombre', 'primer_apellido', 'segundo_apellido', 'curp', 'correo',
                   'denominacion_funcional', 'nivel', 'municipio',
                    'area', 'jefe_inmediato', 'centro_gestor'
               ];

               // Compara las columnas requeridas con el encabezado del archivo CSV
               $columnasFaltantes = array_diff($columnasRequeridas, $header);

               if (!empty($columnasFaltantes)) {
                   // Si faltan columnas, devolver error con los nombres de las columnas faltantes
                   $response->error = true; 
                   $response->respuesta = 'faltan columnas'; 
                   return $this->respond($response);
               }
               $processResponse = $this->procesarDatos($data);
               if($processResponse->error){
                   $response->error = true;
                   $response->respuesta = $processResponse->respuesta;
                   return $this->respond($response);
               }
           }
           $response->error = false; 
           return $this->respond($response);
       } */
    function encode_img_base64($img_path = false, $img_type = 'png')
    {
        if ($img_path) {
            //convert image into Binary data
            $img_data = fopen($img_path, 'rb');
            $img_size = filesize($img_path);
            $binary_image = fread($img_data, $img_size);
            fclose($img_data);
            //Build the src string to place inside your img tag
            $img_src = "data:image/" . $img_type . ";base64," . str_replace("\n", "", base64_encode($binary_image));
            return $img_src;
        }
        return false;
    }

    public function imprimer_qr($noEmpleado)
    {
        // Ruta del QR
        $session = \Config\Services::session();
        $response = new \stdClass();
        // $response->error = true;
        $this->globals = new Mglobal();
        $data = array();
        $tempQrPath = FCPATH . 'assets/images/qr_final.png';
        $usuario = $this->globals->getTabla(["tabla" => "vw_usuario", "where" => ["no_empleado" => $noEmpleado, "visible" => 1]])->data;

        if (empty($usuario)) {
            echo "<center>EL USUARIO NO EXISTE, FAVOR DE LLAMAR AL ADMINISTRADOR DE SUSI</center>";
            die();
        }
        $dataInsert = [
            'id_usuario' => $usuario[0]->id_usuario,
            'fec_reg' => date('Y-m-d H:i:s'),
            'usu_reg' => $session->id_usuario,

        ];
        $dataConfig = [
            "tabla" => "descarga_qr",
            "editar" => false
        ];
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaDescargaQR'];
        $result = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        $data['usuario'] = $usuario[0];
        // Generar el QR
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data(base_url() . 'index.php/Login?no_empleado=' . $noEmpleado)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(400)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->labelText('')
            ->labelFont(new NotoSans(16))
            ->labelAlignment(new LabelAlignmentCenter())
            ->build();

        $result->saveToFile($tempQrPath);
        $dataImagen = $this->encode_img_base64(FCPATH . 'assets/images/qr_final.png', 'png');

        $data['dataImagen'] = $dataImagen;
        // die( var_dump( $data ) );
        $html = view('secciones/vFormato.php', $data);

        // Configuración mPDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [208, 268],
            'mirrorMargins' => false,
        ]);


        $mpdf->WriteHTML($html);
        $mpdf->Output('test.pdf', 'I');
        exit();
    }
    public function im_qr($id)
    {
        // Ruta del QR
        $session = \Config\Services::session();
        $response = new \stdClass();
        // $response->error = true;
        $this->globals = new Mglobal();
        $data = array();
     
        $usuario = $this->globals->getTabla(["tabla" => "posada", "where" => ["id" => $id ]])->data;

        if (empty($usuario)) {
            echo "<center>EL USUARIO NO EXISTE, FAVOR DE LLAMAR AL ADMINISTRADOR DE SUSI</center>";
            die();
        }

        $data['nombre'] = $usuario[0]->nombre;
        $data['correo'] = $usuario[0]->correo;
        $data['dataImagen'] = base_url().'assets/qrposadas/'.$usuario[0]->valor.'.png';
      
     
        $html = view('secciones/vFormatoPosada.php', $data);
        // $html = view($formato, $data);
        // Crear instancia de mPDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

       // die( var_dump(  $data['dataImagen'] ) );
         $doc = 'assets/pdf/plantillas/posada.pdf';
        $pagecount = $mpdf->SetSourceFile(FCPATH . $doc);


            $mpdf->AddPage();
            $tplId = $mpdf->ImportPage(1);
            $mpdf->UseTemplate($tplId);
           $mpdf->WriteHTML($html);
           $mpdf->Output('Formato_pt.pdf', 'I');
        exit();
          
          
    }


     public function im_qr2($id)
    {
        $email = \Config\Services::email();
        $this->globals = new Mglobal();

        // =============================
        // 1️⃣ OBTENER USUARIO
        // =============================
        $usuario = $this->globals
            ->getTabla(["tabla" => "posada", "where" => ["id" => $id]])
            ->data;

        if (empty($usuario)) {
            echo "USUARIO NO EXISTE";
            return;
        }

        // =============================
        // 2️⃣ DATOS PARA EL PDF
        // =============================
        $data = [
            'nombre'     => $usuario[0]->nombre,
            'correo'     => $usuario[0]->correo,
            'dataImagen' => base_url('assets/qrposadas/' . $usuario[0]->valor . '.png')
        ];

        $html = view('secciones/vFormatoPosada.php', $data);

        // =============================
        // 3️⃣ GENERAR PDF
        // =============================
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
        ]);

        $plantilla = FCPATH . 'assets/pdf/plantillas/posada.pdf';
        $mpdf->SetSourceFile($plantilla);
        $tplId = $mpdf->ImportPage(1);
        $mpdf->AddPage();
        $mpdf->UseTemplate($tplId);
        $mpdf->WriteHTML($html);

        // ✅ Guardar PDF temporal
        $pdfPath = WRITEPATH . 'uploads/Invitacion_Posada_' . $id . '.pdf';
        $mpdf->Output($pdfPath, 'F');

        // =============================
        // 4️⃣ CONFIGURAR CORREO
        // =============================
        $email->setFrom('noreply@susi.gob.mx', 'SUSI - SECTURI');
        $email->setTo($usuario[0]->correo);
        $email->setSubject('Invitación a Reunión de Cierre 2025');
        $email->setMailType('html');

        $email->setMessage("
            <p>Buen día, <strong>{$usuario[0]->nombre}</strong>:</p>

            <p>
                Por este medio se te envía la <strong>invitación a nuestra reunión de cierre 2025</strong>.
                El formato adjunto deberá presentarse de manera <strong>digital el día del evento</strong>,
                ya que será requerido para su acceso.
            </p>

            <p>
                Agradecemos confirmar tu asistencia a más tardar el
                <strong>miércoles 10 de diciembre a las 13:00 horas</strong>,
                a través del siguiente enlace:
            </p>

            <p>
                <a href='https://forms.gle/ST1RqTzmkMoYAuXr8' target='_blank'>
                    https://forms.gle/ST1RqTzmkMoYAuXr8
                </a>
            </p>

            <p>Sin otro particular, recibe un cordial saludo.</p>

            <p>
                <strong>Atentamente</strong><br>
                Sistema Unificado SECTURI (SUSI)
            </p>
        ");

        // 📎 Adjuntar PDF
        $email->attach($pdfPath);

        // =============================
        // 5️⃣ ENVIAR Y LIMPIAR
        // =============================
        if ($email->send()) {
            unlink($pdfPath);
            echo "✅ Correo enviado correctamente";
        } else {
            echo "❌ Error al enviar correo";
            echo $email->printDebugger(['headers']);
        }
    }



    public function procesarDatos($data)
    {
        $response = new \stdClass();
        $session = \Config\Services::session();
        $this->globals = new Mglobal();
        $dataClean = [];
        $dataTrash = [];
        $emailsSeen = []; // Lista para verificar correos duplicados en el CSV
        $curpSeen = [];

        foreach ($data as $d) {
            if (isset($d['curp']) && !empty($d['curp'])) {

                // Validación de duplicados de correo en el archivo CSV
                if (in_array($d['correo'], $emailsSeen)) {
                    $response->respuesta = "Existen correos duplicados en el CSV";
                    $response->error = true;
                    return $response;
                } else {
                    $emailsSeen[] = $d['correo']; // Guardar correo para evitar duplicados en el CSV
                }
                if (in_array($d['curp'], $curpSeen)) {
                    $response->respuesta = "Existen CURP duplicados en el CSV";
                    $response->error = true;
                    return $response;
                } else {
                    $curpSeen[] = $d['curp']; // Guardar correo para evitar duplicados en el CSV
                }

                $curpDB = $this->globals->getTabla([
                    'tabla' => 'participantes',
                    'where' => [
                        'visible' => 1,
                        'id_dependencia' => $session->get('id_dependencia'),
                        'curp' => $d['curp']
                    ]
                ]);
                $correoDB = $this->globals->getTabla([
                    'tabla' => 'participantes',
                    'where' => [
                        'visible' => 1,
                        'id_dependencia' => $session->get('id_dependencia'),
                        'correo' => $d['correo']
                    ]
                ]);

                if (!empty($curpDB->data)) {
                    $d['observaciones'] = 'Curp ya existe en la base de datos';
                    $dataTrash[] = $d;
                    continue;
                }
                if (!empty($correoDB->data)) {
                    $d['observaciones'] = 'Correo ya existe en la base de datos';
                    $dataTrash[] = $d;
                    continue;
                }
                if (!preg_match('/^[^@]+@[^@]+$/', $d['correo'])) {
                    $d['observaciones'] = 'Correo debe contener exactamente un "@" y tener estructura válida';
                    $dataTrash[] = $d;
                    continue;
                }


                // Validar la CURP en formato y datos
                $result = $this->validarCURP($d['curp']);
                if (is_object($result) && !$result->error) {
                    // Si es válido, añadir la fecha de nacimiento, edad y sexo al registro
                    $d['fecha_nacimiento'] = $result->fecha_nacimiento;
                    $d['edad'] = $result->edad;
                    $d['sexo'] = $result->sexo;
                    $dataClean[] = $d;
                } else {
                    $d['observaciones'] = is_object($result) ? $result->respuesta : 'Error al procesar la CURP';
                    $dataTrash[] = $d;
                }
            } else {
                // CURP vacía
                $d['observaciones'] = 'CURP vacía';
                $dataTrash[] = $d;
            }
        }

        // Procesar y guardar los datos limpios y descartados en la base de datos
        $this->guardarEnBaseDeDatos($dataClean, $dataTrash);

        // Respuesta final
        $response->error = false;
        return $response;
    }
    private function guardarEnBaseDeDatos($dataClean, $dataTrash)
    {
        $session = \Config\Services::session();

        if (!empty($dataTrash)) {
            foreach ($dataTrash as $c) {
                $dataInsert = [
                    'nombre' => $c['nombre'],
                    'primer_apellido' => $c['primer_apellido'],
                    'segundo_apellido' => $c['segundo_apellido'],
                    'curp' => $c['curp'],
                    'correo' => $c['correo'],
                    // 'fec_nac'            => date("Y-m-d H:i:s", strtotime($c['fec_nac'])),
                    'centro_gestor' => $c['centro_gestor'],
                    'jefe_inmediato' => $c['jefe_inmediato'],
                    'area' => $c['area'],
                    'rfc' => substr($c['curp'], 0, 10),
                    'observaciones' => $c['observaciones'],
                    //'id_sexo'            => ($c['sexo'] == 'HOMBRE') ? 1 : 2,
                    'id_municipio' => 15,
                    'id_dependencia' => (int) $session->get('id_dependencia'),
                    'id_dep_padre' => (int) $session->get('id_padre'),
                    'id_nivel' => (int) $c['nivel'],
                    'fec_reg' => date("Y-m-d H:i:s"),
                    'usu_reg' => $session->get('id_usuario')
                ];
                $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarDetenido'];
                $dataConfig = ["tabla" => "detenidos", "editar" => false];
                $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
            }
        }

        if (!empty($dataClean)) {
            foreach ($dataClean as $c) {
                $dataInsert = [
                    'nombre' => $c['nombre'],
                    'primer_apellido' => $c['primer_apellido'],
                    'segundo_apellido' => $c['segundo_apellido'],
                    'curp' => $c['curp'],
                    'correo' => $c['correo'],
                    'fec_nac' => $c['fecha_nacimiento'],
                    'centro_gestor' => $c['centro_gestor'],
                    'jefe_inmediato' => $c['jefe_inmediato'],
                    'area' => $c['area'],
                    'rfc' => substr($c['curp'], 0, 10),
                    'edad' => $c['edad'],
                    'id_sexo' => ($c['sexo'] == 'H') ? 1 : 2,
                    'id_municipio' => 15,
                    'id_dependencia' => (int) $session->get('id_dependencia'),
                    'id_dep_padre' => (int) $session->get('id_padre'),
                    'id_nivel' => (int) $c['nivel'],
                    'fec_reg' => date("Y-m-d H:i:s"),
                    'usu_reg' => $session->get('id_usuario')
                ];
                $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarParticipantes'];
                $dataConfig = ["tabla" => "participantes", "editar" => false];
                $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
            }
        }
    }
    function validarCURP($curp)
    {
        // Lista de códigos de entidades válidos en México
        $response = new \stdClass();
        $response->error = true;
        $entidadesValidas = [
            'AS',
            'BC',
            'BS',
            'CC',
            'CL',
            'CM',
            'CS',
            'CH',
            'DF',
            'DG',
            'GT',
            'GR',
            'HG',
            'JC',
            'MC',
            'MN',
            'MS',
            'NT',
            'NL',
            'OC',
            'PL',
            'QT',
            'QR',
            'SP',
            'SL',
            'SR',
            'TC',
            'TL',
            'TS',
            'VZ',
            'YN',
            'ZS'
        ];

        // Validación de longitud de 18 caracteres y el formato general
        if (strlen($curp) !== 18) {
            $response->respuesta = "CURP no válida por formato general";
            return false; // CURP no válida por formato general
        }

        // Validación de fecha de nacimiento en CURP
        $anio = intval(substr($curp, 4, 2));
        $mes = intval(substr($curp, 6, 2));
        $dia = intval(substr($curp, 8, 2));

        // Ajustar año para fechas de 1900 a 2099
        $anioCompleto = ($anio < 50) ? 2000 + $anio : 1900 + $anio;

        // Verificar si el año de nacimiento es en el futuro
        $anioActual = intval(date('Y'));
        if ($anioCompleto > $anioActual) {
            $anioCompleto -= 100; // Ajustar el año si es en el futuro
        }

        if (!checkdate($mes, $dia, $anioCompleto)) {
            $response->respuesta = "CURP no válida por fecha de nacimiento incorrecta";
            return $response; // CURP no válida por fecha de nacimiento incorrecta
        }

        // Validación de sexo (posición 11)
        $sexo = $curp[10];
        if ($sexo !== 'H' && $sexo !== 'M') {
            $response->respuesta = "Validación de sexo solo es valido H o M";
            return $response; // CURP no válida por sexo incorrecto
        }

        // Validación de entidad de nacimiento (posiciones 12 y 13)
        $entidad = substr($curp, 11, 2);
        if (!in_array($entidad, $entidadesValidas)) {
            $response->respuesta = "CURP no válida por entidad de nacimiento ejemplo GT";
            return $response;// CURP no válida por entidad incorrecta
        }

        // Validación de primeras consonantes internas en apellidos y nombre (posiciones 14, 15 y 16)
        $consonantesInternas = substr($curp, 13, 3);
        if (!preg_match("/^[B-DF-HJ-NP-TV-Z]{3}$/", $consonantesInternas)) {
            $response->respuesta = "CURP no válida por consonantes internas incorrectas del apellidos y nombre";
            return $response; // CURP no válida por consonantes internas incorrectas
        }

        $ultimosDos = substr($curp, -1);
        if (!ctype_digit($ultimosDos)) {
            $response->respuesta = "los ultimos 1 digitos tiene que ser números entero";
            return $response;
            ; // CURP no válida por consonantes internas incorrectas
        }

        // CURP válida - calcular fecha de nacimiento y edad
        $fechaNacimiento = "$anioCompleto-$mes-$dia";
        $timestampNacimiento = strtotime($fechaNacimiento);
        $timestampHoy = time();
        $edad = (int) date('Y', $timestampHoy) - (int) date('Y', $timestampNacimiento);

        // Ajuste en caso de que el cumpleaños aún no haya ocurrido en el año actual
        if (date('md', $timestampHoy) < date('md', $timestampNacimiento)) {
            $edad--;
        }

        $response->error = false;
        $response->respuesta = "CURP válida";
        $response->fecha_nacimiento = $fechaNacimiento;
        $response->edad = $edad;
        $response->sexo = $sexo;
        return $response;
    }



    public function guardarReservaEditarGo()
    {

        $session = \Config\Services::session();
        $email = \Config\Services::email();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al Guardar los datos';
        $data = $this->request->getPost();

        $hoy = date("Y-m-d H:i:s");
        $dataInsert = [
            "total_importe" => $data['total_importe'],
            "id_estatus" => 3,
            "usu_act" => $session->get('id_usuario')
        ];

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/EditarReserva'];
        $dataConfig = [
            "tabla" => "reserva_go",
            "editar" => true,
            "idEditar" => ['id_reserva_go' => $data['id_reserva_go']]
        ];
        $response = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        if (!$response->error) {
            $i = 0;
            foreach ($data['id_presupuesto'] as $d) {
                $dataInsert = [
                    "id_proyecto" => $data['proyecto'][$i],
                    "id_partida" => $data['partida'][$i],
                    "importe" => $data['importe'][$i],
                    "propina" => $data['propina'][$i],
                    "usu_act" => $session->get('id_usuario')

                ];
                $dataConfig = [
                    "tabla" => "presupuesto_go",
                    "editar" => true,
                    "idEditar" => ['id_presupuesto_go' => (int) $d]
                ];

                $res = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
                if (!$res->error) {
                    $response->error = $res->error;
                    $response->respuesta = $res->respuesta;

                }
                $i++;
            }
        }

    /*       $email->setTo([
              'alopez@guanajuato.gob.mx',
              'negonzalez@guanajuato.gob.mx',
              'dhernandezq@guanajuato.gob.mx'
          ]); 
          $email->setSubject('Carga de Reserva');
          $email->setMessage('
              <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                  <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                      <!-- Encabezado con logotipo -->
                      <div style="background-color: #004080; padding: 20px; text-align: center;">
                          <img src="' . base_url('assets/images/logo.png') . '" alt="Logo" style="height: 60px;">
                      </div>
                      <!-- Contenido principal -->
                      <div style="padding: 30px; color: #333;">
                          <h1 style="color: #004080;">El usuario <strong>' . $session->get('nombre_completo') . '</strong></h1>
                          <p style="font-size: 16px;">ha actualizado la <strong>RESERVA</strong> en el sistema SUSI.</p>
                          <p style="font-size: 15px;">Para los labores correspondientes.</p>
                          <p style="font-size: 15px; color: #888;">Este correo ha sido generado automáticamente por el sistema SUSI. No es necesario responder a este mensaje.</p>
                          <p style="font-size: 15px; color: #888;">Link: ' .  base_url() . 'index.php/Principal/listaReservaPT</p>
                      </div>
                      <!-- Pie de página -->
                      <div style="background-color: #e0e0e0; text-align: center; padding: 15px; font-size: 13px; color: #666;">
                          © ' . date('Y') . ' Sistema de Atención SUSI. Todos los derechos reservados - SECTURI.
                      </div>
                  </div>
              </div>
          ');                      // Intentar enviar el correo
         if ($email->send()) {
            $response->error = false;
            $response->respuesta = "Correo enviado correctamente.";
          } else {
            $response->respuesta = 'Error al enviar: ' . $email->printDebugger();
          }  */

        return $this->respond($response);


    }
    public function guardarReservaEditar()
    {

        $session = \Config\Services::session();
        $email = \Config\Services::email();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al Guardar los datos';
        $data = $this->request->getPost();
        $ruta_absoluta = "";
        $ruta_relativa = "";
        $file = $this->request->getFile('instrumento');


        if ($file && $file->isValid() && !$file->hasMoved()) {

            $maxSize = 1 * 1024 * 1024; // 1 MB

            if ($file->getSize() > $maxSize) {
                $response->respuesta = "El archivo no debe exceder 1 MB.";
                return $this->respond($response);
            }

            $timestamp = date('Ymd_His');
            $extension = $file->getClientExtension();
            $originalName = pathinfo($file->getName(), PATHINFO_FILENAME);
            $archivo = $originalName . '_' . $timestamp . '.' . $extension;

            // Ruta absoluta
            $ruta_destino = FCPATH . 'assets/pdf/';
            $file->move($ruta_destino, $archivo);

            // Rutas públicas
            $ruta_absoluta = base_url('assets/pdf/' . $archivo);
            $ruta_relativa = 'assets/pdf/' . $archivo;
        }


        if (isset($data['no_convenio']) && empty($data['no_convenio'])) {
            $response->error = true;
            $response->respuesta = "El campo No. Convenio es requerido";
            return $this->respond($response);
        }
        $hoy = date("Y-m-d H:i:s");

        $dataInsert = [
            "total_importe" => $data['total_importe'],
            "no_convenio" => $data['no_convenio'],
            "id_estatus" => 1,
            "usu_act" => $session->get('id_usuario')
        ];

        if (!empty($ruta_relativa)) {
            $dataInsert['instrumento'] = $ruta_relativa;
            $dataInsert['ruta_absoluta'] = $ruta_absoluta;
        }

        if (isset($data['comentarios_instrumento_editar'])) {
            $dataInsert['comentarios_instrumento'] = $data['comentarios_instrumento_editar'];
        }

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/EditarReserva'];
        $dataConfig = [
            "tabla" => "reserva",
            "editar" => true,
            "idEditar" => ['id_reserva' => $data['id_reserva']]
        ];
        $response = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        if (!$response->error) {
            $i = 0;
            foreach ($data['id_presupuesto'] as $d) {
                $dataInsert = [
                    "id_proyecto" => $data['proyecto'][$i],
                    "id_partida" => $data['partida'][$i],
                    "importe" => $data['importe'][$i],
                    "usu_act" => $session->get('id_usuario')

                ];
                $dataConfig = [
                    "tabla" => "presupuesto",
                    "editar" => true,
                    "idEditar" => ['id_presupuesto' => $d]
                ];

                $res = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
                if (!$res->error) {
                    $response->error = $res->error;
                    $response->respuesta = $res->respuesta;

                }
                $i++;
            }

        }

        /*  $email->setTo([
              'alopez@guanajuato.gob.mx',
              'negonzalez@guanajuato.gob.mx',
              'dhernandezq@guanajuato.gob.mx'
          ]); 

          $email->setSubject('Carga de Reserva');
          $email->setMessage('
              <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                  <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                      <!-- Encabezado con logotipo -->
                      <div style="background-color: #004080; padding: 20px; text-align: center;">
                          <img src="' . base_url('assets/images/logo.png') . '" alt="Logo" style="height: 60px;">
                      </div>
                      <!-- Contenido principal -->
                      <div style="padding: 30px; color: #333;">
                          <h1 style="color: #004080;">El usuario <strong>' . $session->get('nombre_completo') . '</strong></h1>
                          <p style="font-size: 16px;">ha actualizado la <strong>RESERVA</strong> en el sistema SUSI.</p>
                          <p style="font-size: 15px;">Para los labores correspondientes.</p>
                          <p style="font-size: 15px; color: #888;">Este correo ha sido generado automáticamente por el sistema SUSI. No es necesario responder a este mensaje.</p>
                          <p style="font-size: 15px; color: #888;">Link: ' .  base_url() . 'index.php/Principal/listaReservaPT</p>
                      </div>
                      <!-- Pie de página -->
                      <div style="background-color: #e0e0e0; text-align: center; padding: 15px; font-size: 13px; color: #666;">
                          © ' . date('Y') . ' Sistema de Atención SUSI. Todos los derechos reservados - SECTURI.
                      </div>
                  </div>
              </div>
          ');                      // Intentar enviar el correo
         if ($email->send()) {
            $response->error = false;
            $response->respuesta = "Correo enviado correctamente.";
          } else {
            $response->respuesta = 'Error al enviar: ' . $email->printDebugger();
          } */
        return $this->respond($response);


    }
    public function guardarFoto()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al Guardar los datos';
        
        $foto = $this->request->getFile('foto');

        if (!$foto->isValid()) {
             $response->respuesta = $foto->getErrorString() . '(' . $foto->getError() . ')';
             return $this->respond($response);
        }

        // Validar tamaño (Max 2MB)
        if ($foto->getSize() > 2048 * 1024) {
             $response->respuesta = "El archivo excede el tamaño máximo permitido de 2MB.";
             return $this->respond($response);
        }

        // Validar tipo de archivo
        $mimeType = $foto->getMimeType();
        $allowedMimes = ['image/jpg', 'image/jpeg', 'image/gif', 'image/png', 'image/webp'];
        
        if (!in_array($mimeType, $allowedMimes)) {
             $response->respuesta = "Formato de archivo no válido. Solo se permiten imágenes (JPG, PNG, GIF, WEBP).";
             return $this->respond($response);
        }

        if ($foto->hasMoved()) {
             $response->respuesta = "El archivo ya ha sido procesado.";
             return $this->respond($response);
        }

        $timestamp = date('Ymd_His');
        $extension = $foto->getExtension(); // Obtener extensión basada en el tipo MIME real
        $archivo = $session->usuario . '_' . $timestamp . '.' . $extension;

        $ruta_destino = FCPATH . 'assets/images/fotos/';

        try {
            $foto->move($ruta_destino, $archivo);
        } catch (\Exception $e) {
            $response->respuesta = "Error al mover el archivo: " . $e->getMessage();
            return $this->respond($response);
        }

        $ruta_absoluta = base_url('assets/images/fotos/' . $archivo);
        $ruta_relativa = 'assets/images/fotos/' . $archivo;

        $dataInsert = [
            "ruta_foto_absoluta" => $ruta_absoluta,
            "ruta_foto_relativa" => $ruta_relativa,
            "usu_act" => $session->get('id_usuario')
        ];
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Principal.php/guardaFoto'];
        $dataConfig = [
            "tabla" => "usuario",
            "editar" => true,
            "idEditar" => ['id_usuario' => $session->get('id_usuario')]
        ];
        
        $res = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
        
        if (!$res->error) {
            $session->set('foto', $ruta_relativa);
            $response->error = $res->error;
            $response->respuesta = $res->respuesta;
            $response->nueva_foto = $ruta_relativa;
        } else {
             $response->respuesta = $res->respuesta;
        }
        
        return $this->respond($response);
    }
    public function guardarReservaGO()
    {

        $session = \Config\Services::session();
        $email = \Config\Services::email();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al Guardar los datos';
        $data = $this->request->getPost();

        $hoy = date("Y-m-d H:i:s");
        $folio = 'GO-' . date('YmdHis'); // Ejemplo: FOL-20250725133045

        $dataInsert = [
            "id_proveedor" => 1,
            "total_importe" => 0,
            "fec_reg" => $hoy,
            "usu_reg" => $session->get('id_usuario'),
            "id_estatus" => 3,
            "folio" => $folio
        ];

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Principal.php/guardaReservaGO'];
        $dataConfig = [
            "tabla" => "reserva_go",
            "editar" => false,
            // "idEditar"=>['id_usuario'=>$data['id_usuario']]
        ];
        $response = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        if (!$response->error) {
            $id_reserva = $response->idRegistro;
            $datosCombinados = [];

            // Verificar que todos los arrays tengan la misma longitud
            if (count($data['proyecto']) === count($data['partida']) && count($data['partida']) ) {
                foreach ($data['proyecto'] as $index => $proyecto) {
                    // Solo agregar si todos los valores existen
                    if (!empty($data['proyecto']) && !empty($data['partida'][$index])) {
                        $datosCombinados[] = [
                            'proyecto' => $proyecto,
                            'partida' => $data['partida'][$index],
                          //  'importe' => str_replace(',', '', $data['importe'][$index]),
                           // 'propina' => str_replace(',', '', $data['propina'][$index]) // Elimina comas del formato numérico
                        ];
                    }
                }
            }
            $dataConfig = [
                "tabla" => "presupuesto_go",
                "editar" => false,
                // "idEditar"=>['id_usuario'=>$data['id_usuario']]
            ];
            foreach ($datosCombinados as $d) {
                $dataInsert = [
                    "id_reserva" => $id_reserva,
                    "id_proyecto" => $d['proyecto'],
                    "id_partida" => $d['partida'],
                    "importe" => 0,
                    "propina" => 0,
                    "fec_reg" => $hoy,
                    "usu_reg" => $session->get('id_usuario')

                ];

                $res = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
                if (!$res->error) {
                    $response->error = $res->error;
                    $response->respuesta = $res->respuesta;
                    $response->idRegistro = $id_reserva;

                }
            }
        }
      //$res = $this->enviarEmail(1);
      

        return $this->respond($response);
    }
    public function guardarReserva()
    {

        $session = \Config\Services::session();
        $email = \Config\Services::email();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al Guardar los datos';
        $data = $this->request->getPost();
        $ruta_absoluta = "";
        $ruta_relativa = "";
        $file = $this->request->getFile('instrumento');
         if (isset($data['no_convenio']) && empty($data['no_convenio'])) {
            $response->error = true;
            $response->respuesta = "El campo No. Convenio es requerido";
            return $this->respond($response);
        }

        // Validación de unicidad para no_convenio
        if (isset($data['no_convenio']) && strtoupper($data['no_convenio']) !== 'NO APLICA') {
            $dataCheck = [
                'tabla' => 'reserva',
                'where' => ['no_convenio' => $data['no_convenio'] , 'visible'=> 1]
            ];
            $exists = $globals->getTabla($dataCheck);
            
            // Si existe algún registros y no es error
            if (!$exists->error && !empty($exists->data)) {
                $response->error = true;
                $response->respuesta = "El No. Convenio '" . $data['no_convenio'] . "' ya existe.";
                return $this->respond($response);
            }
        }

        if ($file && $file->isValid() && !$file->hasMoved()) {

            $maxSize = 100 * 1024 * 1024; // 100 MB

            if ($file->getSize() > $maxSize) {
                $response->respuesta = "El archivo no debe exceder 100 MB.";
                return $this->respond($response);
            }
            $timestamp = date('Ymd_His');
            $extension = $file->getClientExtension();
            $originalName = pathinfo($file->getName(), PATHINFO_FILENAME);
            $archivo = 'instrumento_' . $timestamp . '.' . $extension;

            // Ruta absoluta
            $ruta_destino = FCPATH . 'assets/pdf/';
            $file->move($ruta_destino, $archivo);

            // Rutas públicas
            $ruta_absoluta = base_url('assets/pdf/' . $archivo);
            $ruta_relativa = 'assets/pdf/' . $archivo;
        }


       
        $hoy = date("Y-m-d H:i:s");
        $folio = 'PT-' . date('YmdHis'); // Ejemplo: FOL-20250725133045

        $reviucionInterna = (in_array($session->get('id_usuario'), [80,17,14,59,38,11])) ? true : false;

        $dataInsert = [
            "id_proveedor" => (int) $data['id_proveedor'],
            "total_importe" => $data['total_importe'],
            "id_proveedor_banco" => (int) $data['banco'],
            "fec_reg" => $hoy,
            "usu_reg" => $session->get('id_usuario'),
            "folio" => $folio
        ];

        if($reviucionInterna){
            $dataInsert['id_estatus'] = 5;
            $dataInsert['promo'] = 1;
                $email->setTo([
                'mamedinaher@guanajuato.gob.mx',
                 $session->get('correo')
                ]);  

        //     $email->setTo('dasedetur@guanajuato.gob.mx'); // destinatario principal
                // $email->setCC(['palafox.marin@hotmail.com', 'dasedetur@guanajuato.gob.mx']); // copia visible
                //$email->setCC(['negonzalez@guanajuato.gob.mx ', 'dhernandezq@guanajuato.gob.mx']); // copia visible
                //   $email->setBCC(['a.palafoxm@guanajuato.gob.com']); // copia oculta
                $email->setSubject('Carga de Reserva');

                $email->setMessage('
                    <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                        <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                            <!-- Encabezado con logotipo -->
                            <div style="background-color: #004080; padding: 20px; text-align: center;">
                                <img src="' . base_url('assets/images/logo.png') . '" alt="Logo" style="height: 60px;">
                            </div>
                            <!-- Contenido principal -->
                            <div style="padding: 30px; color: #333;">
                                <h1 style="color: #004080;">El usuario <strong>' . $session->get('nombre_completo') . '</strong></h1>
                                <p style="font-size: 16px;">ha registrado una <strong>RESERVA</strong> en el sistema SUSI.</p>
                                <p style="font-size: 15px;">Para los labores correspondientes.</p>
                                <p style="font-size: 15px; color: #888;">Este correo ha sido generado automáticamente por el sistema SUSI. No es necesario responder a este mensaje.</p>
                                <p style="font-size: 15px; color: #888;">Link: ' . base_url() . 'index.php/Principal/listaReservaPT</p>
                            </div>
                            <!-- Pie de página -->
                            <div style="background-color: #e0e0e0; text-align: center; padding: 15px; font-size: 13px; color: #666;">
                                © ' . date('Y') . ' Sistema de Atención SUSI. Todos los derechos reservados - SECTURI.
                            </div>
                        </div>
                    </div>
                ');                      // Intentar enviar el correo
                if ($email->send()) {
                    $response->error = false;
                    $response->respuesta = "Correo enviado correctamente.";
                } else {
                    $response->respuesta = 'Error al enviar: ' . $email->printDebugger();
                }  
        }
        if (!empty($ruta_relativa)) {
            $dataInsert['instrumento'] = $ruta_relativa;
            $dataInsert['ruta_absoluta'] = $ruta_absoluta;
            $dataInsert['no_convenio'] = ($data['no_convenio'] == 'NO APLICA') ? 'NO APLICA' : $data['no_convenio'];
        }

        if (isset($data['comentarios_instrumento'])) {
            $dataInsert['comentarios_instrumento'] = $data['comentarios_instrumento'];
        }

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaReserva'];
        $dataConfig = [
            "tabla" => "reserva",
            "editar" => false,
            // "idEditar"=>['id_usuario'=>$data['id_usuario']]
        ];
        $response = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        if (!$response->error) {
            $id_reserva = $response->idRegistro;
            $datosCombinados = [];

            // Verificar que todos los arrays tengan la misma longitud
            if (count($data['proyecto']) === count($data['partida']) && count($data['partida']) === count($data['importe'])) {
                foreach ($data['proyecto'] as $index => $proyecto) {
                    // Solo agregar si todos los valores existen
                    if (!empty($data['proyecto']) && !empty($data['partida'][$index]) && !empty($data['importe'][$index])) {
                        $datosCombinados[] = [
                            'proyecto' => $proyecto,
                            'partida' => $data['partida'][$index],
                            'importe' => str_replace(',', '', $data['importe'][$index]) // Elimina comas del formato numérico
                        ];
                    }
                }
            }
            $dataConfig = [
                "tabla" => "presupuesto",
                "editar" => false,
                // "idEditar"=>['id_usuario'=>$data['id_usuario']]
            ];
            foreach ($datosCombinados as $d) {
                $dataInsert = [
                    "id_reserva" => $id_reserva,
                    "id_proyecto" => $d['proyecto'],
                    "id_partida" => $d['partida'],
                    "importe" => $d['importe'],
                    "fec_reg" => $hoy,
                    "usu_reg" => $session->get('id_usuario')

                ];

                $res = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
                if (!$res->error) {
                    $response->error = $res->error;
                    $response->respuesta = $res->respuesta;

                }
            }
        }
        if($session->get('id_perfil')!=1 && !$reviucionInterna){
            $this->enviarEmail(0);
        }
       

        return $this->respond($response);
    }

    private function enviarEmail($id = null)
    {
        $session = \Config\Services::session();
        $email = \Config\Services::email();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al enviar el correo';

        


          $email->setTo([
           'alopez@guanajuato.gob.mx',
           'negonzalez@guanajuato.gob.mx',
           'dhernandezq@guanajuato.gob.mx'
         ]);  

   //     $email->setTo('dasedetur@guanajuato.gob.mx'); // destinatario principal
        // $email->setCC(['palafox.marin@hotmail.com', 'dasedetur@guanajuato.gob.mx']); // copia visible
        //$email->setCC(['negonzalez@guanajuato.gob.mx ', 'dhernandezq@guanajuato.gob.mx']); // copia visible
        //   $email->setBCC(['a.palafoxm@guanajuato.gob.com']); // copia oculta
        $email->setSubject('Carga de Reserva');
        $tipo = ($id==1)?'PT':'GO';
        $email->setMessage('
            <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    <!-- Encabezado con logotipo -->
                    <div style="background-color: #004080; padding: 20px; text-align: center;">
                        <img src="' . base_url('assets/images/logo.png') . '" alt="Logo" style="height: 60px;">
                    </div>
                    <!-- Contenido principal -->
                    <div style="padding: 30px; color: #333;">
                        <h1 style="color: #004080;">El usuario <strong>' . $session->get('nombre_completo') . '</strong></h1>
                        <p style="font-size: 16px;">ha registrado una <strong>RESERVA</strong> en el sistema SUSI.</p>
                        <p style="font-size: 15px;">Para los labores correspondientes.</p>
                        <p style="font-size: 15px; color: #888;">Este correo ha sido generado automáticamente por el sistema SUSI. No es necesario responder a este mensaje.</p>
                        <p style="font-size: 15px; color: #888;">Link: ' . base_url() . 'index.php/Principal/listaReserva'.$tipo.'</p>
                    </div>
                    <!-- Pie de página -->
                    <div style="background-color: #e0e0e0; text-align: center; padding: 15px; font-size: 13px; color: #666;">
                        © ' . date('Y') . ' Sistema de Atención SUSI. Todos los derechos reservados - SECTURI.
                    </div>
                </div>
            </div>
        ');                      // Intentar enviar el correo
        if ($email->send()) {
            $response->error = false;
            $response->respuesta = "Correo enviado correctamente.";
        } else {
            $response->respuesta = 'Error al enviar: ' . $email->printDebugger();
        } 

         return $this->respond($response);
    }
    private function envioCorreoJefeInmediato()
    {
        $session = \Config\Services::session();
        $email = \Config\Services::email();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;

        $id_jefe_inmediato = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['id_usuario' => $session->get('id_usuario'), 'visible' => 1]])->data[0]->id_jefe_inmediato;
        $correoJefe = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['id_usuario' => $id_jefe_inmediato, 'visible' => 1]])->data[0]->correo;

        $email->setTo($correoJefe);

        $email->setSubject('Solicitud de Autorización de Incidencia');

        $email->setMessage('
            <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    <!-- Encabezado con logotipo -->
                    <div style="background-color: #004080; padding: 20px; text-align: center;">
                        <img src="' . base_url('assets/images/logo-sm.png') . '" alt="Logo" style="height: 60px;">
                    </div>
                    <!-- Contenido principal -->
                    <div style="padding: 30px; color: #333;">
                        <h2 style="color: #004080;">Solicitud de autorización de incidencia</h2>
                        <p style="font-size: 16px;">El usuario <strong>' . $session->get('nombre_completo') . '</strong> ha registrado una incidencia en el sistema <strong>SUSI</strong>.</p>
                        <p style="font-size: 16px;">Le solicitamos amablemente su <strong>revisión y autorización</strong> para proceder con el trámite correspondiente.</p>
                        <p style="font-size: 15px;">Puede consultar y validar esta incidencia ingresando al sistema o siguiendo su flujo de aprobación.</p>
                        <p style="font-size: 15px; color: #888;">Este mensaje fue generado automáticamente por el sistema SUSI. No es necesario responder a este correo.</p>
                        <p style="font-size: 15px; color: #888;">Link: <a href="' . base_url() . 'index.php/Principal/incidenciaSubordinado" target="_blank" >Ver incidencia</a></p>
                    </div>
                    <!-- Pie de página -->
                    <div style="background-color: #e0e0e0; text-align: center; padding: 15px; font-size: 13px; color: #666;">
                        © ' . date('Y') . ' Sistema de Atención SUSI. Todos los derechos reservados - SECTURI.
                    </div>
                </div>
            </div>
        ');
        // Intentar enviar el correo
        if ($email->send()) {
            $response->error = false;
            $response->respuesta = "Correo enviado correctamente.";
        } else {
            $response->respuesta = 'Error al enviar: ' . $email->printDebugger();
        }
        return $this->respond($response);

    }
    public function guardarSemana()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $data = $this->request->getPost();
        list($iniStr, $finStr) = array_map('trim', explode('-', $data['datetimes']));

        $ini = date('Y-m-d', strtotime($iniStr));
        $fin = date('Y-m-d', strtotime($finStr));
        $dataInsert = [
            "id_usuario" => (int) $session->get('id_usuario'),
            "id_estatus" => 1,
            "cat_id_incidencia" => (int) $data['tipo_incidencia'],
            "hora_inicio" => '8:30:00',
            "hora_fin" => '16:00:00',
            "fecha" => $data['datetimes'],
            "tipo" => 2,
            "fecha_inicio" => $ini,
            "fecha_fin" => $fin,
            "comentario" => $data['comentario'],
            "detalles" => $data['detalles'],
            "usu_reg" => (int) $session->get('id_usuario'),
            "fec_reg" => date('Y-m-d H:i:s')
        ];
        $dataConfig = [
            "tabla" => "incidencia",
            "editar" => false,
        ];
        $result = $globals->saveTabla($dataInsert, $dataConfig, ["script" => "guardar.incidencia"]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
        }
        $this->envioCorreoJefeInmediato();
        return $this->respond($response);
    }
    public function guardarMes()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $data = $this->request->getPost();
        $dataInsert = [
            "id_usuario" => (int) $session->get('id_usuario'),
            "id_estatus" => 1,
            "cat_id_incidencia" => (int) $data['tipo_incidencia'],
            "hora_inicio" => '8:30:00',
            "hora_fin" => '16:00:00',
            "fecha" => date('d/m/Y', strtotime($data['fecha_inicio'])) . ' - ' . date('d/m/Y', strtotime($data['fecha_fin'])),
            "tipo" => 3,
            "fecha_inicio" => $data['fecha_inicio'],
            "fecha_fin" => $data['fecha_fin'],
            "comentario" => $data['comentario'],
            "detalles" => $data['detalles'],
            "usu_reg" => (int) $session->get('id_usuario'),
            "fec_reg" => date('Y-m-d H:i:s')
        ];
        $dataConfig = [
            "tabla" => "incidencia",
            "editar" => false,
        ];
        $result = $globals->saveTabla($dataInsert, $dataConfig, ["script" => "guardar.incidencia"]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
        }
        $this->envioCorreoJefeInmediato();
        return $this->respond($response);
    }
    public function guardarIncidencia()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $data = $this->request->getPost();

        if (empty($data['tipo_incidencia']) || $data['tipo_incidencia'] == 0) {
            $response->error = true;
            $response->respuesta = 'Es requerido el tipo de incidencia';
            return $this->respond($response);

        }
        $fecha = $data['fecha'];
        $diaSemana = date('N', strtotime($fecha));

        if ($data['tipo_incidencia'] == 9) {
            if ($diaSemana == 1 || $diaSemana == 5) {
                $response->error = true;
                $response->respuesta = 'La fecha no puede ser lunes ni viernes';
                return $this->respond($response);
            }
        }
        if ($data['tipo_incidencia'] == 1) {
            $hora_fin = $data['hora_fin']; // usa el índice correcto
            if (strtotime($hora_fin) >= strtotime('16:00:00')) {
                $hora_fin = "16:00:00";
            }
        }
        if ($data['tipo_incidencia'] == 11) {
            $hora_inicio = $data['hora_inicio'];
            $hora_fin = $data['hora_fin'];

            // Crear objetos DateTime
            $inicio = new DateTime($hora_inicio);
            $fin = new DateTime($hora_fin);

            // Calcular diferencia
            $diff = $inicio->diff($fin);

            // Pasar todo a horas decimales
            $horas = $diff->h + ($diff->days * 24);
            $minutos = $diff->i;
            $totalHoras = $horas + ($minutos / 60);

            if ($totalHoras > 5) {
                // Aquí ya superó las 5 horas
                $response->error = true;
                $response->respuesta = 'Un permiso personal  no puede superar las 5 horas';
                return $this->respond($response);
            }
        }
        /*      if ((int)$data['tipo_incidencia'] === 10) {
                 $horaInicio = $data['hora_inicio'] ?? null; // "HH:MM" o "HH:MM:SS"
                 $horaFin    = $data['hora_fin']    ?? null;

                 // Normaliza a HH:MM:SS si vienen en HH:MM
                 $horaInicio = $horaInicio && strlen($horaInicio) === 5 ? $horaInicio . ':00' : $horaInicio;
                 $horaFin    = $horaFin    && strlen($horaFin) === 5    ? $horaFin    . ':00' : $horaFin;

                 // Reglas
                 $permiteEntradaManana = $horaInicio && strtotime($horaInicio) <= strtotime('09:30:00');
                 $permiteSalidaTres    = $horaFin    && strtotime($horaFin)    === strtotime('15:00:00');

                 if (!($permiteEntradaManana || $permiteSalidaTres)) {
                     $response->error  = true;
                     $response->respuesta = 'Para esta incidencia sólo se permite entrada antes o a las 09:30, o salida exactamente a las 15:00.';
                      return $this->respond($response);
                 }

             } */



        $dataInsert = [
            "hora_inicio" => $data['hora_inicio'],
            "hora_fin" => (isset($hora_fin) && !empty($hora_fin)) ? $hora_fin : $data['hora_fin'],
            "hora_fin_real" => $data['hora_fin'],
            "cat_id_incidencia" => (int) $data['tipo_incidencia'],
            "fecha" => $data['fecha'],
            "tipo" => 1,
            "fecha_inicio" => $data['fecha'],
            "fecha_fin" => $data['fecha'],
            "comentario" => $data['comentario'],
            "detalles" => $data['detalles'],
            "id_usuario" => $session->get('id_usuario'),
            "usu_reg" => $session->get('id_usuario'),
            "fec_reg" => date('Y-m-d H:i:s'),
        ];
        $dataConfig = [
            "tabla" => "incidencia",
            "editar" => false,
        ];
        $result = $globals->saveTabla($dataInsert, $dataConfig, ["script" => "guardar.incidencia"]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
        }
        $this->envioCorreoJefeInmediato();
        return $this->respond($response);
    }
    public function bitacora()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $this->globals = new Mglobal();
        $dataDB = array('tabla' => 'vw_bitacora', 'where' => ['visible' => 1,], 'orlike' => ['fec_act' => date('Y-m-d')]);
        $usuarioBD = ['tabla' => 'vw_asistencia_incidencia', 'where' => ['visible' => 1], 'orlike' => ['fecha' => date('Y-m-d')]];
        $usuario = $this->globals->getTabla($usuarioBD);
        $data['periodo'] = (isset($periodo->data) && !empty($periodo->data)) ? $periodo->data : [];
        $data['usuario'] = (isset($usuario->data) && !empty($usuario->data)) ? $usuario->data : [];
        // Enviar datos a la vista

        $response = $this->globals->getTabla($dataDB)->data;
      
        $data['bitacora'] = $response;
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vBitacora';
        $this->_renderView($data);
    }
    public function Ombudsperson()
    {
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vOmbudsperson';
        $this->_renderView($data);
    }
    public function ControlInterno()
    {
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vControlInterno';
        $this->_renderView($data);
    }
    public function ControlInterno2024()
    {
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vControlInterno2024';
        $this->_renderView($data);
    }
    public function actualizarBanco()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $data = $this->request->getPost();
        $dataInsert = [
            "banco" => $data['banco'],
            "no_cuenta" => $data['no_cuenta'],
            "clabe" => $data['clabe'],
        ];
        $dataConfig = [
            "tabla" => "proveedor_banco",
            "editar" => true,
            "idEditar" => ["id_proveedor_banco" => $data['id_proveedor_banco']]
        ];
        $result = $globals->saveTabla($dataInsert, $dataConfig, ["script" => "proveedor_banco.editarBanco"]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = "Datos guardados correctamente";
        }
        return $this->respond($response);
    }
    public function eliminarBanco()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $id_proveedor_banco = $this->request->getPost('id_proveedor_banco');

        $dataConfig = [
            "tabla" => "proveedor_banco",
            "editar" => true,
            "idEditar" => ["id_proveedor_banco" => $id_proveedor_banco]
        ];
        $result = $globals->saveTabla(['visible' => 0], $dataConfig, ["script" => "proveedor_banco.eliminarBanco"]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = "Datos guardados correctamente";
        }
        return $this->respond($response);
    }
    public function eliminarProveedor()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $id_proveedor = $this->request->getPost('id_proveedor');
        $dataConfig = [
            "tabla" => 'proveedor',
            "editar" => true,
            "idEditar" => ['id_proveedor' => $id_proveedor]
        ];

        $result = $globals->saveTabla(['visible' => 0], $dataConfig, ["script" => "proveedo.eliminarProveedor"]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
        }
        return $this->respond($response);
    }
    public function agregarProveedor()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $data = $this->request->getPost();

        $dataConfig = [
            "tabla" => 'proveedor',
            "editar" => false
        ];
        $dataInsert = [
            "id_tipo_proveedor" => 1,
            "razon_social" => $data['razon_social'],
            "no_proveedor" => $data['no_proveedor'],
            "rfc" => $data['rfc'],
        ];
        $result = $globals->saveTabla($dataInsert, $dataConfig, ["script" => "proveedo.agregarProveedo"]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
        }
        return $this->respond($response);
    }
    public function agregarBanco()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $data = $this->request->getPost();
        $dataConfig = [
            "tabla" => 'proveedor_banco',
            "editar" => false
        ];
        $dataInsert = [
            "idproveedor" => $data['id_proveedor'],
            "banco" => $data['banco'],
            "no_cuenta" => $data['no_cuenta'],
            "clabe" => $data['clabe'],
            "fic" => $data['fic'],
        ];
        $result = $globals->saveTabla($dataInsert, $dataConfig, ["script" => "proveedor_banco.agregarBanco"]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
        }
        return $this->respond($response);
    }

    public function getProveedor()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $id_proveedor = $this->request->getPost('id_proveedor');
        $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1, 'id_proveedor' => $id_proveedor]]);
        $proveedor_banco = $globals->getTabla(['tabla' => 'proveedor_banco', 'where' => ['visible' => 1, 'idproveedor' => $id_proveedor]]);
        if (!$proveedor->error) {
            $response->error = false;
            $response->respuesta = $proveedor->respuesta;
            $response->data['proveedor'] = (isset($proveedor->data) && !empty($proveedor->data)) ? $proveedor->data[0] : [];
            $response->data['proveedor_banco'] = (isset($proveedor_banco->data) && !empty($proveedor_banco->data)) ? $proveedor_banco->data : [];
        }
        return $this->respond($response);
    }

    public function buscarProveedorSelect2()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $request = \Config\Services::request();
        
        $term = $request->getGet('term') ?? ''; // Select2 sends 'term'


        $like = ['razon_social' => "%$term%", "no_proveedor" => "%$term%", "rfc"=>"%$term%"];
        $dataDB = array('tabla' => 'proveedor', 'where' => ['visible' => 1], 'orlike' => $like, );
        $response = $globals->getTabla($dataDB);
        
        
        return $this->respond($response->data);
    }
    public function listadoProveedores()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit' => 50]);
        $data['proveedor'] = (!empty($proveedor->data)) ? $proveedor->data : [];
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vListadoProveedor';
        $this->_renderView($data);
    }
    public function formContrasenia()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();
        if ($data['contrasenia'] != $data['new_contrasenia']) {
            $response->respuesta = "Las contraseñas no coinciden";
            return $this->respond($response);
        }
        if ($data['contrasenia'] == '' || $data['new_contrasenia'] == '') {
            $response->respuesta = "Los campos son requeridos";
            return $this->respond($response);
        }
        $dataInsert = [
            "contrasenia" => $this->hashContrasenia($data['contrasenia']),
            "usu_act" => $session->id_usuario
        ];
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Principal.php/cambioContrasenia'];
        $dataConfig = [
            "tabla" => "usuario",
            "editar" => true,
            "idEditar" => ['id_usuario' => $session->id_usuario]
        ];
        $response = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
        return $this->respond($response);

    }
    public function formComentario()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();

        if (empty($data['comentario'])) {
            $response->respuesta = "Es requerido el comentario";
            return $this->respond($response);
        }

        $dataInsert = [
            "comentario" => $data['comentario'],
            "id_usuario" => $session->id_usuario,
            "fec_reg" => date('Y-m-d'),
            "usu_act" => $session->id_usuario
        ];
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Principal.php/guardarComentario'];
        $dataConfig = [
            "tabla" => "comentarios",
            "editar" => false,
            //"idEditar" => ['id_area' => (int)$data['id_area']]
        ];
        $response = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
        return $this->respond($response);

    }
    public function deleteActividad()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $id_actividad = $this->request->getPost('id_actividad');
        $response = new \stdClass();
        $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Principal.php/eliminarActividad'];
        $dataConfig = [
            "tabla" => "actividad",
            "editar" => true,
            "idEditar" => ['id_actividad' => $id_actividad]
        ];

        $sala = $globals->saveTabla(['visible' => 0], $dataConfig, $dataBitacora);
        if (!$sala->error) {
            $response->error = $sala->error;
            $response->respuesta = $sala->respuesta;
        }

        return $this->respond($response);

    }
    public function Personal()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $personal = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['id_jefe_inmediato' => $session->id_usuario, 'visible' => 1]]);
        $actividad = $globals->getTabla(['tabla' => 'vw_actividad', 'where' => ['usu_reg' => $session->id_usuario, 'visible' => 1]]);

        $data['personal'] = (!empty($personal->data)) ? $personal->data : [];
        $data['actividad'] = (!empty($actividad->data)) ? $actividad->data : [];
        $data['scripts'] = array('inicio');

        $data['contentView'] = 'personal/vPersonal';
        $this->_renderView($data);
    }
    public function Postulacion($idSexo = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $personal = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['id_sexo' => $idSexo, 'visible' => 1, 'honestidad' => 1]]);

        $data['personal'] = (!empty($personal->data)) ? $personal->data : [];

        $data['scripts'] = array('inicio');
        $data['idSexo'] = $idSexo;
        $data['contentView'] = 'personal/vPostulacion';
        $this->_renderView($data);
    }
    public function guardarHonestidad()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error! Error al guardar en la base de datos';
        $data = $this->request->getPost();

        $dataConfig = [
            "tabla" => "registro_honestidad",
            "editar" => false,

        ];
        $dataInsert = [
            "id_usuario_seleciono" => $data['id_usuario'],
            "id_principio" => $data['principio'],
            "id_valor_principio" => $data['valor'],
            "usu_reg" => $session->get('id_usuario'),
            "fec_reg" => date('Y-m-d H:i:s')
        ];

        $result = $principal->saveTabla($dataInsert, $dataConfig, ['id_user' => $session->get('id_usuario'), "script" => "registro.Honestidad"]);

        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;

        }
        return $this->respond($response);

    }
    public function incidenciaSubordinado()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $incidencia = $globals->getTabla([
            'tabla' => 'vw_incidenica',
            'where' => [
                'visible' => 1,
                'id_jefe_inmediato' => $session->get('id_usuario')
            ],
        ]);

        $Periodo = ['tabla' => 'cat_periodo', 'where' => ['visible' => 1]];
        $cat_incidencia = ['tabla' => 'cat_incidencia', 'where' => ['visible' => 1]];
        $usuario = [
            'tabla' => 'vw_incidenica',
            'select' => ['id_usuario', 'nombre_completo'],
            'where' => ['visible' => 1],
            'groupBy' => ['id_usuario']
        ];
        $periodo = $globals->getTabla($Periodo);
        $usuario = $globals->getTabla($usuario);
        $data['periodo'] = (isset($periodo->data) && !empty($periodo->data)) ? $periodo->data : [];
        $data['usuario'] = (isset($usuario->data) && !empty($usuario->data)) ? $usuario->data : [];
        $data['cat_incidencia'] = (isset($cat_incidencia->data) && !empty($cat_incidencia->data)) ? $cat_incidencia->data : [];

        $data['incidencia'] = [];

        if (isset($incidencia->data) && !empty($incidencia->data)) {
            foreach ($incidencia->data as $item) {
                $fecha = $item->fecha;

                // Detectar si es rango tipo "08/18/2025 - 08/22/2025"
                if (preg_match('/\d{2}\/\d{2}\/\d{4}\s*-\s*\d{2}\/\d{2}\/\d{4}/', $fecha)) {
                    list($iniStr, $finStr) = array_map('trim', explode('-', $fecha));

                    $ini = DateTime::createFromFormat('m/d/Y', $iniStr);
                    $fin = DateTime::createFromFormat('m/d/Y', $finStr);

                    if ($ini && $fin) {
                        // FullCalendar → end es exclusivo: sumamos 1 día
                        $fin->modify('+1 day');

                        $item->start = $ini->format('Y-m-d');
                        $item->end = $fin->format('Y-m-d');
                        $item->tipo = 'semana';
                    }
                } else {
                    // Fecha simple: intentamos varios formatos
                    $d = DateTime::createFromFormat('Y-m-d', $fecha) ?:
                        DateTime::createFromFormat('d/m/Y', $fecha) ?:
                        DateTime::createFromFormat('m/d/Y', $fecha);

                    if ($d) {
                        $fin = clone $d;
                        $fin->modify('+1 day');

                        $item->start = $d->format('Y-m-d');
                        $item->end = $fin->format('Y-m-d');
                        $item->tipo = 'dia';
                    }
                }

                $data['incidencia'][] = $item;
            }
        }
        //var_dump(  $data['incidencia'] );
        //die();
        $data['scripts'] = array('inicio', 'principal');
        $data['contentView'] = 'secciones/vListaIncidencia';
        $this->_renderView($data);
    }
    public function EnviarCorreoIncidencias()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error en la base de datos';
        $globals = new Mglobal;
        $email = \Config\Services::email();
        $result = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_tipo_empleado' => 1]])->data;
        $email->setTo('rsalbap@guanajuato.gob.mx');

        $email->setTo([
            'ccampos@guanajuato.gob.mx',
            'ztorrest@guanajuato.gob.mx',
            'ajassome@guanajuato.gob.mx',
            'apenriquez@guanajuato.gob.mx',
            'hramirezd@guanajuato.gob.mx',
            'ialvarezp@guanajuato.gob.mx',
            'jescobarl@guanajuato.gob.mx',
            'jpachecocan@guanajuato.gob.mx',
            'mvallejo@guanajuato.gob.mx',
            'rgonzalezgu@guanajuato.gob.mx',
            'yjimenez@guanajuato.gob.mx',
            'mamoralesg@guanajuato.gob.mx',
            'jrojas@guanajuato.gob.mx',
        ]); 
 
          /*$email->setTo([
                    'alopez@guanajuato.gob.mx',
                    'cchernandezp@guanajuato.gob.mx',
                    'csoto@guanajuato.gob.mx',
                    'emartinezes@guanajuato.gob.mx',
                    'evazquezro@guanajuato.gob.mx',
                    'itzramos@guanajuato.gob.mx',
                    'jmelgar@guanajuato.gob.mx',
                    'jordaz@guanajuato.gob.mx',
                    'kporrasp@guanajuato.gob.mx',
                    'lorozcot@guanajuato.gob.mx',
                    'lsantibanezr@guanajuato.gob.mx',
                    'lvelaga@guanajuato.gob.mx',
                    'mgmena@guanajuato.gob.mx',
                    'rantonio@guanajuato.gob.mx',
                    'sbeltranl@guanajuato.gob.mx',
                    'solvera@guanajuato.gob.mx',
                    'tavaresg@guanajuato.gob.mx',
                    'ygutierrezh@guanajuato.gob.mx',
                    'amendozat@guanajuato.gob.mx',
                    'bmartinez@guanajuato.gob.mx',
                    'crismon@guanajuato.gob.mx',
                    'david.gonzalez@guanajuato.gob.mx',
                    'dhernandezq@guanajuato.gob.mx',
                    'dmontiello@guanajuato.gob.mx',
                    'jacostap@guanajuato.gob.mx',
                    'jrojas@guanajuato.gob.mx',
                    'miguel.salazarc@guanajuato.gob.mx',
                    'mrcarballo@guanajuato.gob.mx',
                    'murrutiac@guanajuato.gob.mx',
                    'negonzalez@guanajuato.gob.mx',
                    'nlandin@guanajuato.gob.mx',
                    'orosas@guanajuato.gob.mx',
                    'pcortesvi@guanajuato.gob.mx',
                    'ilianacord@guanajuato.gob.mx',
                    'luis.perez@guanajuato.gob.mx',
                    'mascencio@guanajuato.gob.mx',
                    'jmazavala@guanajuato.gob.mx',
                    'rantonio@guanajuato.gob.mx',
                    'jrodriguezgo@guanajuato.gob.mx',
                ]);*/
        $email->setSubject('Recordatorio: Revisión de Asistencias - Sistema SUSI');
        $email->setMessage('
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <style>
                    body { font-family: "Segoe UI", Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px; }
                    .container { max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
                    .header { background: linear-gradient(135deg, #004080, #0066cc); padding: 25px; text-align: center; }
                    .content { padding: 35px; color: #333; }
                    .footer { background-color: #e9ecef; text-align: center; padding: 20px; font-size: 13px; color: #6c757d; line-height: 1.5; }
                    .btn { display: inline-block; padding: 12px 28px; background: linear-gradient(135deg, #004080, #0066cc); color: white; text-decoration: none; border-radius: 6px; margin: 10px 8px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0,64,128,0.3); }
                    .btn:hover { background: linear-gradient(135deg, #003366, #0052a3); transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,64,128,0.4); }
                    .highlight-box { background-color: #f0f7ff; border-left: 4px solid #004080; padding: 15px; margin: 20px 0; border-radius: 0 8px 8px 0; }
                </style>
            </head>
            <body>
                <div class="container">
                    <!-- Encabezado -->
                    <div class="header">
                        <img src="cid:logo_susi" alt="SUSI - Sistema Unificado SECTURI" style="height: 65px;">
                    </div>
                    
                    <!-- Contenido principal -->
                    <div class="content">
                        <h1 style="color: #004080; margin-bottom: 10px; text-align: center;">Recordatorio de Asistencias</h1>
                        <p style="text-align: center; color: #666; margin-bottom: 25px; font-size: 16px;">Sistema Unificado SECTURI</p>
                        
                        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px;">Estimado personal,</p>
                        
                        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                            En caso de que aún no hayas realizado las <strong>justificaciones de tu personal a tu cargo correspondientes a la quincena 09/2026</strong>, 
                            la cual comprende el periodo del <strong>01 al 16 de mayo de 2026</strong>, 
                            tienes hasta el día <strong>lunes 25 de mayo hasta las 16:00 hrs</strong> para realizarlas.
                        </p>

                        <div class="highlight-box">
                            <p style="font-size: 15px; line-height: 1.6; margin: 0;">
                                Para cualquier duda o aclaración, favor de comunicarse a la 
                                <strong>Coordinación de Recursos Humanos</strong> o 
                                <strong>Coordinación de Tecnologías de la Información</strong>.
                            </p>
                        </div>

                        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px;">
                            Le invitamos a revisar las incidencias en el sistema SUSI.
                        </p>

                        <div style="text-align: center; margin: 30px 0;">
                            <a href="https://secturnet.guanajuato.gob.mx/susi/index.php/Principal/incidenciaSubordinado" class="btn" style="color: white; text-decoration: none;">
                                📋 Revisar Incidencias
                            </a>
                        </div>

                        <p style="font-size: 14px; color: #666; border-top: 1px solid #dee2e6; padding-top: 20px; margin-top: 25px;">
                            <strong>Nota:</strong> Este es un mensaje automático generado por el Sistema Unificado SECTURI (SUSI). 
                            Por favor, no responda a este correo.
                        </p>
                    </div>
                    
                    <!-- Pie de página -->
                    <div class="footer">
                        <strong>© ' . date('Y') . ' Sistema Unificado SECTURI (SUSI)</strong><br>
                        Todos los derechos reservados - Secretaría de Turismo e Identidad
                    </div>
                </div>
            </body>
            </html>
        ');


        /*    // ✅ SOLUCIÓN PARA LA IMAGEN - AGREGAR COMO ADJUNTO EMBEBIDO
           $logoPath = FCPATH . 'assets/pdf/plantillas/ManualPersonaSuperior.pdf';
           if (file_exists($logoPath)) {
               $email->attach($logoPath);
               $email->setHeader('Content-ID', '<logo_susi>');
           }

           // Configuraciones adicionales recomendadas */
        $email->setMailType('html');


        // Intentar enviar el correo
        if ($email->send()) {
            $response->error = false;
            $response->respuesta = "✅ Notificación enviada correctamente a los destinatarios.";
        } else {
            $response->error = true;
            $response->respuesta = '❌ Error al enviar la notificación: ' . $email->printDebugger(['headers']);
        }
        return $this->respond($response);
    }

    public function getVehiculo()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $id_vehiculo = $this->request->getPost('id_vehiculo');
        $response->error = true;
        $response->respuesta = 'Error en la base de datos';
        $principal = new Mglobal;


        $result = $principal->getTabla(['tabla' => 'vehiculo', 'where' => ['visible' => 1, 'id_vehiculo' => $id_vehiculo]]);

        if (!$result->error) {
            $response->error = $result->error;
            $response->respuesta = $result->respuesta;
            $response->data = $result->data[0];
        }
        return $this->respond($response);
    }
    public function existeIncidencia()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $fecha = $this->request->getPost('fecha');
        $response->error = false;
        $response->respuesta = 'Error al guardar en la base de datos';
        $principal = new Mglobal;
        $result = $principal->getTabla(['tabla' => 'incidencia', 'where' => ['visible' => 1, 'fecha' => $fecha, 'id_usuario' => $session->get('id_usuario')]]);

        if (isset($result->data) && !empty($result->data)) {
            $response->error = true;

        }
        return $this->respond($response);
    }
    public function editarFic()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $data = $this->request->getPost();
        $response->error = true;
        $response->respuesta = 'Error al guardar en la base de datos';
        $principal = new Mglobal;
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Principal.php/guardaVehiculo'];
        // die( var_dump($data) );
        $dataConfig = [
            'tabla' => 'proveedor',
            'editar' => true,
            'idEditar' => ['id_proveedor' => $data['id_proveedor']]
        ];
        $result = $principal->saveTabla(['fic' => 1], $dataConfig, $dataBitacora);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;

        }
        return $this->respond($response);

    }
    public function guardarVehiculo()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $data = $this->request->getPost();
        $response->error = true;
        $response->respuesta = 'Error al guardar en la base de datos';
        $principal = new Mglobal;
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Principal.php/guardaVehiculo'];
        $dataInsert = [
            'no_control' => $data['no_control'],
            'marca' => $data['marca'],
            'tipo' => $data['tipo'],
            'modelo' => $data['modelo'],
            'no_activo_fijo' => $data['no_activo_fijo'],
            'no_tarjeta' => $data['no_tarjeta'],
            'dotacion' => $data['dotacion'],
            'placa' => $data['placa'],
            'no_serie' => $data['no_serie'],
            'id_usuario' => $data['id_usuario']

        ];
        $dataConfig = [
            'tabla' => 'vehiculo',
            'editar' => true,
            'idEditar' => ['id_vehiculo' => $data['id_vehiculo']]
        ];
        $result = $principal->saveTabla($dataInsert, $dataConfig, $dataBitacora);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;

        }
        return $this->respond($response);

    }
    public function listaReservaPT()
    {

        $session = \Config\Services::session();
        $globals = new Mglobal;
        if (in_array($session->get('id_perfil'), [1, 2])) {
            $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva', 'where' => ['visible' => 1, "id_estatus"=> 1]]);
        } else {
            if($session->get('id_usuario')==80){
                $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva', 'where' => ['visible' => 1, 'promo' => 1, "id_estatus"=> 5]]);
            }else{
                 if(in_array($session->get('id_usuario'), [14,80,17, 59, 11, 38])){
                $resultado = $globals->getTabla(['tabla' => 'vw_lista_reserva', 'where' => ['visible' => 1, 'promo' => 1]]);
                  $datosFiltrados = [];
                    foreach($resultado->data as $r) {
                        if(in_array($r->id_estatus, [1,5])) {
                            $datosFiltrados[] = $r;
                        }
                    }
                    
                    $reserva = new \stdClass();
                    $reserva->data = $datosFiltrados;
                                }else{
                    $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva', 'where' => ['usu_reg' => $session->get('id_usuario'), 'visible' => 1, "id_estatus"=> 1 ]]);
                
                }
            }
        }
       // die( var_dump($reserva ) );
        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit' => 100]);
        $reservas = (!empty($reserva->data)) ? $reserva->data : [];
        $forzarDescargaInstrumento = (int) ($session->get('id_perfil') ?? 0) === 2;
        foreach ($reservas as $itemReserva) {
            $itemReserva->instrumento_urls = $this->mapInstrumentoUrls($itemReserva->instrumento ?? null, $forzarDescargaInstrumento);
        }

        $data['reserva'] = $reservas;
        $data['scripts'] = array('inicio');
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['contentView'] = 'secciones/vListadoReservaPT';
        $this->_renderView($data);

    }
    public function concluidosAceptados()
    {

        $session = \Config\Services::session();
        $globals = new Mglobal;
        if (in_array($session->get('id_perfil'), [1, 2])) {
            $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva', 'where' => ['visible' => 1, "id_estatus"=> 3]]);
        } else {
            if(in_array($session->get('id_usuario'), [14,80, 59, 11,38,17])){
                $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva', 'where' => ['visible' => 1, "promo"=> 1, 'id_estatus' => 3]]);
            }else{
                $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva', 'where' => ['usu_reg' => $session->get('id_usuario'), 'visible' => 1, "id_estatus"=> 3]]);
            }
        }
       // die( var_dump($reserva ) );
        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit' => 100]);
        $data['reserva'] = (!empty($reserva->data)) ? $reserva->data : [];
        $data['scripts'] = array('inicio');
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['contentView'] = 'secciones/vListadoReservaPT';
        $this->_renderView($data);

    }

    public function concluidosDeclinados()
    {

        $session = \Config\Services::session();
        $globals = new Mglobal;
        if (in_array($session->get('id_perfil'), [1, 2])) {
            $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva', 'where' => ['visible' => 1, "id_estatus"=> 2]]);
        } else {
             $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva', 'where' => ['visible' => 1, "id_estatus"=> 2, 'usu_reg' => $session->get('id_usuario')]]);
        }
       // die( var_dump($reserva ) );
        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit' => 100]);
        $data['reserva'] = (!empty($reserva->data)) ? $reserva->data : [];
        $data['scripts'] = array('inicio');
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['contentView'] = 'secciones/vListadoReservaPT';
        $data['es_declinado'] = true;
        $this->_renderView($data);

    }

    public function concluidosAceptadosGO()
    {

        $session = \Config\Services::session();
        $globals = new Mglobal;
        if (in_array($session->get('id_perfil'), [1, 2])) {
            $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva_go', 'where' => ['visible' => 1, "id_estatus"=> 3]]);
        } else {
             // Redireccionar o mostrar error si no tiene permiso, aunque el menú lo oculta.
                $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva_go', 'where' => ['visible' => 1, "id_estatus"=> 3, 'usu_reg' => $session->get('id_usuario')]]);
        }
       // die( var_dump($reserva ) );
        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit' => 100]);
        $data['reserva'] = (!empty($reserva->data)) ? $reserva->data : [];
        $data['scripts'] = array('inicio');
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['contentView'] = 'secciones/vListadoReservaGO';
        $this->_renderView($data);

    }

    public function concluidosDeclinadosGO()
    {

        $session = \Config\Services::session();
        $globals = new Mglobal;
        if (in_array($session->get('id_perfil'), [1, 2])) {
            $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva_go', 'where' => ['visible' => 1, "id_estatus"=> 2]]);
        } else {
             return redirect()->to(base_url() . 'index.php/Inicio');
        }
       // die( var_dump($reserva ) );
        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit' => 100]);
        $data['reserva'] = (!empty($reserva->data)) ? $reserva->data : [];
        $data['scripts'] = array('inicio');
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['contentView'] = 'secciones/vListadoReservaGO';
        $data['es_declinado'] = true;
        $this->_renderView($data);

    }

    public function listaReservaGO()
    {

        $session = \Config\Services::session();
        $globals = new Mglobal;
        if (in_array($session->get('id_perfil'), [1, 2])) {
            $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva_go', 'where' => ['visible' => 1, 'borrador' => 2]]);
        } else {
            $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva_go', 'where' => ['usu_reg' => $session->get('id_usuario'), 'visible' => 1,  'borrador' => 2]]);
        }

        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit' => 100]);
        $data['reserva'] = (!empty($reserva->data)) ? $reserva->data : [];

        $data['scripts'] = array('inicio');
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['contentView'] = 'secciones/vListadoReservaGO';
        $this->_renderView($data);

    }
    public function listadoPT()
    {

        $session = \Config\Services::session();
        $globals = new Mglobal;
        $cat_perfil = $globals->getTabla(['tabla' => 'perfil', 'where' => ['visible' => 1]]);
        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit' => 10]);
        //die( var_dump( $proveedor ) );
        $data['cat_perfil'] = (!empty($cat_perfil->data)) ? $cat_perfil->data : [];
        $data['proveedor'] = (!empty($proveedor->data)) ? $proveedor->data : [];
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];

        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['PT'] = 1;
        $data['contentView'] = 'secciones/vListadoPT';
        $this->_renderView($data);

    }

    public function listadoGo($id_registro_pt = null, $id_reserva = null)
    {

        $session = \Config\Services::session();
        $globals = new Mglobal;
        $cat_area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        if ($id_reserva != 0) {
            $reserva = $globals->getTabla(['tabla' => 'vw_reserva', 'where' => ['id_reserva' => $id_reserva]]);
            $presupuesto = $globals->getTabla(['tabla' => 'presupuesto', 'where' => ['id_reserva' => $id_reserva]]);
            $data['reserva'] = (!empty($reserva->data)) ? $reserva->data[0] : [];
            $data['presupuesto'] = (!empty($presupuesto->data)) ? $presupuesto->data : [];
        }
        if (!empty($id_registro_pt)) {
            $registro_pt = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]]);
            $data['registro_pt'] = (!empty($registro_pt->data)) ? $registro_pt->data[0] : [];
        }


        $cat_perfil = $globals->getTabla(['tabla' => 'perfil', 'where' => ['visible' => 1]]);
        $cat_director_general = $globals->getTabla(['tabla' => 'cat_director_general', 'where' => ['visible' => 1]]);
        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_usuario = $globals->getTabla(['tabla' => 'cat_usuario', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $cat_area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        $cat_tipo = $globals->getTabla(['tabla' => 'cat_tipo', 'where' => ['visible' => 1]]);
        $secretario = $globals->getTabla(['tabla' => 'cat_secretario', 'where' => ['visible' => 1]]);
        $cat_opcion = $globals->getTabla(['tabla' => 'cat_opcion', 'where' => ['visible' => 1]]);
        $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit' => 10]);
        $usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->get('id_usuario')]]);
        $data['dsc_director_general'] = (!empty($cat_director_general->data)) ? $cat_director_general->data[0]->dsc_director_general : [];
        $data['cat_tipo'] = (!empty($cat_tipo->data)) ? $cat_tipo->data : [];
        $data['cat_opcion'] = (!empty($cat_opcion->data)) ? $cat_opcion->data : [];
        $data['cat_perfil'] = (!empty($cat_perfil->data)) ? $cat_perfil->data : [];
        $data['proveedor'] = (!empty($proveedor->data)) ? $proveedor->data : [];
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['secretario'] = (!empty($secretario->data)) ? $secretario->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['cat_area'] = (!empty($cat_area->data)) ? $cat_area->data : [];
        $data['usuario'] = (!empty($usuario->data)) ? $usuario->data[0] : [];
        $data['cat_usuario'] = (!empty($cat_usuario->data)) ? $cat_usuario->data : [];
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vFormularioGo';
        $this->_renderView($data);

    }
    public function listadoGrc($id_registro_pt = null, $id_reserva = null)
    {

        $session = \Config\Services::session();
        $globals = new Mglobal;

        $cat_perfil = $globals->getTabla(['tabla' => 'perfil', 'where' => ['visible' => 1]]);
        $cat_director_general = $globals->getTabla(['tabla' => 'cat_director_general', 'where' => ['visible' => 1]]);
        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
    
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $cat_area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['id_pago' => 1]]);
        $cat_tipo = $globals->getTabla(['tabla' => 'cat_tipo', 'where' => ['visible' => 1]]);
        $secretario = $globals->getTabla(['tabla' => 'cat_secretario', 'where' => ['visible' => 1]]);
        $cat_opcion = $globals->getTabla(['tabla' => 'cat_opcion', 'where' => ['visible' => 1]]);
        $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit' => 10]);
        $usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
       
        $data['dsc_director_general'] = (!empty($cat_director_general->data)) ? $cat_director_general->data[0]->dsc_director_general : [];
        $data['cat_tipo'] = (!empty($cat_tipo->data)) ? $cat_tipo->data : [];
        $data['cat_opcion'] = (!empty($cat_opcion->data)) ? $cat_opcion->data : [];
        $data['cat_perfil'] = (!empty($cat_perfil->data)) ? $cat_perfil->data : [];
        $data['proveedor'] = (!empty($proveedor->data)) ? $proveedor->data : [];
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['secretario'] = (!empty($secretario->data)) ? $secretario->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['cat_area'] = (!empty($cat_area->data)) ? $cat_area->data : [];
        $data['usuario'] = (!empty($usuario->data)) ? $usuario->data : [];
        $data['idArea'] = (!empty($idArea)) ? $idArea : '';
    
        // --- Generar Folio GRC ---
        $area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['id_pago' => 1, 'titular' => $session->get('id_usuario')]]);
       
        if(empty($area->data)){
            $idArea = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->get('id_usuario')]])->data[0]->id_area;
            $area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['id_pago' => 1, 'id_area' => $idArea]]);
            $data['prefijo'] = $area->data[0]->prefijo;
        }else{
           $data['prefijo'] = $area->data[0]->prefijo;
        }
       // die( $data['prefijo'] );
        $solicitudes_grc = $globals->getTabla(["tabla" => "solicitud_grc", "where" => ["visible" => 1, 'usu_reg' => $session->get('id_usuario')]]);
        $no_consecutivo_num = count($solicitudes_grc->data) + 1;
        $no_consecutivo_str = str_pad($no_consecutivo_num, 3, "0", STR_PAD_LEFT);
        $data['no_consecutivo'] = $no_consecutivo_str;
       

        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vSolicitudGrc';
        $this->_renderView($data);

    }
    public function editarSolicitudGrc($id_solicitud = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;

        // Validar ID
        if (!$id_solicitud) {
             return redirect()->to(base_url('index.php/Inicio/ListadoSolicitudes'));
        }

        // Obtener datos de la solicitud
        $solicitud = $globals->getTabla(['tabla' => 'solicitud_grc', 'where' => ['id_solicitud_grc' => $id_solicitud, 'visible' => 1]]);
        
        if (empty($solicitud->data)) {
            return redirect()->to(base_url('index.php/Inicio/ListadoSolicitudes'));
        }

        // Obtener detalles
        $detalles = $globals->getTabla(['tabla' => 'solicitud_grc_detalle', 'where' => ['id_solicitud_grc' => $id_solicitud, 'visible' => 1]]);

        // Cargar catálogos (mismos que en listadoGrc/Crear)
        $cat_perfil = $globals->getTabla(['tabla' => 'perfil', 'where' => ['visible' => 1]]);
        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $cat_area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        $usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);

        $data['cat_perfil'] = (!empty($cat_perfil->data)) ? $cat_perfil->data : [];
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['cat_area'] = (!empty($cat_area->data)) ? $cat_area->data : [];
        $data['usuario'] = (!empty($usuario->data)) ? $usuario->data : [];
        
        // Datos para edición
        $data['solicitud'] = $solicitud->data[0];
        $data['detalles'] = (!empty($detalles->data)) ? $detalles->data : [];
        $data['editar'] = true;

        $folioGuardado = isset($data['solicitud']->no_consecutivo) ? trim((string) $data['solicitud']->no_consecutivo) : '';
        $prefijo = '';
        $noConsecutivo = '';

        if ($folioGuardado !== '') {
            $folioSinAnio = preg_replace('/\/\d{4}$/', '', $folioGuardado);
            if (preg_match('/^(.*?)(\d+)$/', $folioSinAnio, $matches)) {
                $prefijo = $matches[1];
                $noConsecutivo = str_pad($matches[2], 3, '0', STR_PAD_LEFT);
            }
        }

        if ($prefijo === '') {
            $areaUsuario = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1, 'titular' => $session->get('id_usuario')]]);
            if (empty($areaUsuario->data)) {
                $usuarioActual = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->get('id_usuario')]]);
                $idAreaUsuario = (!empty($usuarioActual->data) && isset($usuarioActual->data[0]->id_area)) ? $usuarioActual->data[0]->id_area : null;
                if (!empty($idAreaUsuario)) {
                    $areaUsuario = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1, 'id_area' => $idAreaUsuario]]);
                }
            }

            $prefijo = (!empty($areaUsuario->data) && isset($areaUsuario->data[0]->prefijo)) ? $areaUsuario->data[0]->prefijo : '';
        }

        if ($noConsecutivo === '') {
            $noConsecutivo = '001';
        }

        $data['prefijo'] = $prefijo;
        $data['no_consecutivo'] = $noConsecutivo;

        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vSolicitudGrc';
        $this->_renderView($data);
    }
    public function ArchivoGRC($id_solicitud = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;

        if (!$id_solicitud) {
            echo "ID no válido";
            return;
        }

        // Obtener datos de la solicitud (usando vista para nombres)
        $solicitud = $globals->getTabla(['tabla' => 'vw_solicitud_grc', 'where' => ['id_solicitud_grc' => $id_solicitud, 'visible' => 1]]);
        
        if (empty($solicitud->data)) {
            echo "Solicitud no encontrada";
            return;
        }

        // Obtener detalles
        $detalles = $globals->getTabla(['tabla' => 'vw_solicitud_grc_detalle', 'where' => ['id_solicitud_grc' => $id_solicitud, 'visible' => 1]]);

        $data['solicitud'] = $solicitud->data[0];
        $data['detalles'] = (!empty($detalles->data)) ? $detalles->data : [];
        
        // Convertir cantidad a letras
        $data['solicitud']->cantidad_letra = $this->numeroEnLetras($data['solicitud']->cantidad);
        $data['cantidad_letra'] = $data['solicitud']->cantidad_letra;

        // Fecha creación texto
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = strtotime($data['solicitud']->fec_reg);
        $data['fecha_texto'] = "Silao, Gto., a " . date('d', $fecha) . " de " . $meses[date('n', $fecha)-1] . " de " . date('Y', $fecha);

        $html = view('personal/vFormatoGRC', $data);
        $doc = 'public/assets/images/archivo_grc.pdf'; // Reference only, not used anymore
     
        // Crear PDF
      
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 15,
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_bottom' => 15,
            'format' => 'Letter'
        ]);

         $pagecount = $mpdf->SetSourceFile(FCPATH . $doc);
        $templateId = $mpdf->ImportPage($pagecount);
        $mpdf->UseTemplate($templateId);
        $mpdf->WriteHTML($html);
        $mpdf->Output('Solicitud_GRC_' . $id_solicitud . '.pdf', 'I');
        exit();
    }
    public function ArchivoComprobacion($id_solicitud = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;

        if (!$id_solicitud) {
            echo "ID no válido";
            return;
        }

        // Obtener datos de la solicitud
        $solicitud = $globals->getTabla(['tabla' => 'vw_solicitud_grc', 'where' => ['id_solicitud_grc' => $id_solicitud, 'visible' => 1]]);
       // var_dump($solicitud->data[0]); die();
        
        if (empty($solicitud->data)) {
            echo "Solicitud no encontrada";
            return;
        }

        // Obtener comprobaciones
        // NOTA: Asumimos que la tabla de comprobación se llama solicitud_grc_comprobacion
        $comprobaciones = $globals->getTabla(['tabla' => 'solicitud_grc_comprobacion', 'where' => ['id_solicitud_grc' => $id_solicitud, 'visible' => 1]]);
        $usuariosQuery = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        $usuariosPorRfc = [];
        $usuariosPorNombre = [];

        if (!empty($usuariosQuery->data)) {
            foreach ($usuariosQuery->data as $usuario) {
                $nombreCompleto = isset($usuario->nombre_completo) && !empty($usuario->nombre_completo)
                    ? $usuario->nombre_completo
                    : (isset($usuario->nombre) ? $usuario->nombre : '');

                if (!empty($usuario->rfc)) {
                    $usuariosPorRfc[strtoupper(trim($usuario->rfc))] = $nombreCompleto;
                }

                if (!empty($usuario->nombre)) {
                    $usuariosPorNombre[mb_strtoupper(trim($usuario->nombre), 'UTF-8')] = $nombreCompleto;
                }

                if (!empty($usuario->nombre_completo)) {
                    $usuariosPorNombre[mb_strtoupper(trim($usuario->nombre_completo), 'UTF-8')] = $nombreCompleto;
                }
            }
        }

        $data['solicitud'] = $solicitud->data[0];
        $data['comprobaciones'] = (!empty($comprobaciones->data)) ? $comprobaciones->data : [];

        if (!empty($data['comprobaciones'])) {
            foreach ($data['comprobaciones'] as $comp) {
                $nombreCompleto = isset($comp->nombre_emisor) ? $comp->nombre_emisor : '';
                $rfcComp = isset($comp->rfc) ? strtoupper(trim($comp->rfc)) : '';
                $nombreComp = isset($comp->nombre_emisor) ? mb_strtoupper(trim($comp->nombre_emisor), 'UTF-8') : '';

                if ($rfcComp !== '' && isset($usuariosPorRfc[$rfcComp])) {
                    $nombreCompleto = $usuariosPorRfc[$rfcComp];
                } elseif ($nombreComp !== '' && isset($usuariosPorNombre[$nombreComp])) {
                    $nombreCompleto = $usuariosPorNombre[$nombreComp];
                }

                $comp->nombre_emisor_completo = $nombreCompleto;
            }
        }
        
        $html = view('personal/vFormatoComprobacion', $data);
        $doc = 'assets/documentos/SOLICITUD_GRC.pdf'; // Template base if needed, or just plain HTML
        
        // Crear PDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'format' => 'Letter'
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Comprobacion_GRC_' . $id_solicitud . '.pdf', 'I');
        exit();
    }
    public function SolicitudHonorarios()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = array();
        
        $vw_usuario = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1]]);
        $data['direccion'] = (!empty($vw_usuario->data)) ? $vw_usuario->data : [];
        $vw_usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        $data['usuario'] = (!empty($vw_usuario->data)) ? $vw_usuario->data : [];

        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];

        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $cat_area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        $data['cat_area'] = (!empty($cat_area->data)) ? $cat_area->data : [];
        $cat_puesto = $globals->getTabla(['tabla' => 'cat_puesto', 'where' => ['visible' => 1]]);
        $data['cat_puesto'] = (!empty($cat_puesto->data)) ? $cat_puesto->data : [];
        $data['catalogo_firmantes'] = $this->construirCatalogoFirmantes($data['direccion'], $data['usuario']);
        $data['firmas_seleccionadas'] = [];
        
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vSolicitudHonorarios';
        $this->_renderView($data);
    }
    public function listadoHonorarios()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = array();

        if (in_array((int) ($session->id_perfil ?? 0), [1, 7], true)) {
            $solicitudes = $globals->getTabla(['tabla' => 'solicitud_honorario', 'where' => ['visible' => 1]]);
        } else {
            $solicitudes = $globals->getTabla(['tabla' => 'solicitud_honorario', 'where' => ['visible' => 1, 'usu_reg' => $session->id_usuario ?? 0]]);
        }

        $responsables = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1]]);
        $responsablesMap = [];
        if (!empty($responsables->data)) {
            foreach ($responsables->data as $responsable) {
                $responsablesMap[(string) ($responsable->id_usuario ?? '')] = trim(($responsable->nombre_completo ?? '') . ' - ' . ($responsable->dsc_puesto ?? ''));
            }
        }

        if (!empty($solicitudes->data)) {
            foreach ($solicitudes->data as $solicitud) {
                $solicitud->responsable_proyecto_nombre = $responsablesMap[(string) ($solicitud->responsable_proyecto ?? '')] ?? ($solicitud->responsable_proyecto ?? '');
                $archivosSolicitud = $globals->getTabla([
                    'tabla' => 'solicitud_honorario_archivos',
                    'where' => ['visible' => 1, 'id_solicitud_honorario' => $solicitud->id_solicitud_honorario]
                ]);
                $solicitud->tienen_archivos = !empty($archivosSolicitud->data);
                $instrumentos = [];
                if (!empty($archivosSolicitud->data)) {
                    foreach ($archivosSolicitud->data as $archivoSolicitud) {
                        if (($archivoSolicitud->clave_documento ?? '') === 'instrumento_juridico') {
                            $instrumentos[] = $archivoSolicitud->nombre_archivo ?? '';
                        }
                    }
                }
                $solicitud->instrumento_urls = $this->mapInstrumentoUrls($instrumentos);
            }
        }

        $data['solicitudes'] = !empty($solicitudes->data) ? $solicitudes->data : [];
        $data['scripts'] = ['inicio'];
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vListaHonorarios';
        $this->_renderView($data);
    }

    private function obtenerSolicitudHonorariosDetalle($idSolicitudHonorario)
    {
        $globals = new Mglobal;
        $detalle = [
            'solicitud' => null,
            'actividades' => [],
        ];

        $solicitudQuery = $globals->getTabla([
            'tabla' => 'solicitud_honorario',
            'where' => ['id_solicitud_honorario' => $idSolicitudHonorario, 'visible' => 1]
        ]);

        if (empty($solicitudQuery->data)) {
            return $detalle;
        }

        $solicitud = $solicitudQuery->data[0];

        $responsables = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1]]);
        if (!empty($responsables->data)) {
            foreach ($responsables->data as $responsable) {
                if ((string) ($responsable->id_usuario ?? '') === (string) ($solicitud->responsable_proyecto ?? '')) {
                    $solicitud->responsable_proyecto_nombre = trim(($responsable->nombre_completo ?? '') . ' - ' . ($responsable->dsc_puesto ?? ''));
                    break;
                }
            }
        }

        $areas = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        if (!empty($areas->data)) {
            foreach ($areas->data as $area) {
                if ((string) ($area->id_area ?? '') === (string) ($solicitud->area ?? '')) {
                    $solicitud->area_nombre = $area->dsc_area ?? '';
                    break;
                }
            }
        }

        $proyectos = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        if (!empty($proyectos->data)) {
            foreach ($proyectos->data as $proyecto) {
                if ((string) ($proyecto->id_proyecto ?? '') === (string) ($solicitud->clave_presupuestal ?? '')) {
                    $solicitud->clave_presupuestal_nombre = $proyecto->proyecto ?? '';
                    break;
                }
            }
        }

        $partidas = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        if (!empty($partidas->data)) {
            foreach ($partidas->data as $partida) {
                if ((string) ($partida->id_partida ?? '') === (string) ($solicitud->partida ?? '')) {
                    $solicitud->partida_nombre = trim((string) (($partida->cuenta_cable ?? '') . ' - ' . ($partida->partida ?? $partida->nombre_fondo ?? '')));
                    break;
                }
            }
        }

        $puestos = $globals->getTabla(['tabla' => 'cat_puesto', 'where' => ['visible' => 1]]);
        if (!empty($puestos->data)) {
            foreach ($puestos->data as $puesto) {
                foreach (['prestacion_servicios', 'puesto_prestador', 'id_puesto'] as $campoPuesto) {
                    $valorSolicitudPuesto = trim((string) ($solicitud->{$campoPuesto} ?? ''));
                    if ($valorSolicitudPuesto === '') {
                        continue;
                    }

                    $descripcionPuesto = trim((string) ($puesto->dsc_puesto ?? $puesto->puesto ?? $puesto->nombre_puesto ?? ''));

                    if ((string) ($puesto->id_puesto ?? '') === $valorSolicitudPuesto || $descripcionPuesto === $valorSolicitudPuesto) {
                        $solicitud->puesto_prestador_nombre = $descripcionPuesto;
                        break 2;
                    }
                }
            }
        }

        if (empty($solicitud->puesto_prestador_nombre)) {
            foreach (['prestacion_servicios', 'puesto_prestador', 'id_puesto'] as $campoPuesto) {
                $valorSolicitudPuesto = trim((string) ($solicitud->{$campoPuesto} ?? ''));
                if ($valorSolicitudPuesto !== '') {
                    $solicitud->puesto_prestador_nombre = $valorSolicitudPuesto;
                    break;
                }
            }
        }

        $actividadesQuery = $globals->getTabla([
            'tabla' => 'actividades_honorario',
            'where' => ['id_solicitud_honorario' => $idSolicitudHonorario, 'visible' => 1]
        ]);

        $detalle['solicitud'] = $solicitud;
        $detalle['actividades'] = !empty($actividadesQuery->data) ? $actividadesQuery->data : [];

        return $detalle;
    }

    public function pdfSolicitudHonorarios($id_solicitud_honorario = null)
    {
        if (empty($id_solicitud_honorario)) {
            echo 'Solicitud no válida';
            return;
        }

        $detalle = $this->obtenerSolicitudHonorariosDetalle($id_solicitud_honorario);
        if (empty($detalle['solicitud'])) {
            echo 'Solicitud no encontrada';
            return;
        }

        $data = [
            'solicitud' => $detalle['solicitud'],
            'actividades' => $detalle['actividades'],
            'firmas_pdf' => $this->obtenerFirmasSolicitudDetalle(new Mglobal, $detalle['solicitud']),
        ];

        $html = view('pdfs/vPdfSolicitudHonorarios', $data);

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 12,
            'margin_left' => 12,
            'margin_right' => 12,
            'margin_bottom' => 12,
            'format' => 'Letter'
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Solicitud_Honorarios_' . $id_solicitud_honorario . '.pdf', 'I');
        exit();
    }

    private function documentosSolicitudHonorarios(): array
    {
        return [
            1 => 'Oficio de solicitud',
            2 => 'Formato de solicitud de Contrato',
            3 => 'Validacion Proceso Ingreso de SFIA',
            4 => 'RFC / Cedula de Identificacion Fiscal',
            5 => 'Identificacion Oficial',
            6 => 'Autorizacion de Tratamiento de Datos Personales en Posesion de Sujetos Obligados',
            7 => 'Comprobante de Domicilio',
        ];
    }

    public function editarSolicitudHonorarios($id_solicitud_honorario = null)
    {
        if (empty($id_solicitud_honorario)) {
            return redirect()->to(base_url('index.php/Principal/listadoHonorarios'));
        }

        $globals = new Mglobal;
        $detalle = $this->obtenerSolicitudHonorariosDetalle($id_solicitud_honorario);
        if (empty($detalle['solicitud'])) {
            return redirect()->to(base_url('index.php/Principal/listadoHonorarios'));
        }

        $vw_usuario = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1]]);
        $data['direccion'] = (!empty($vw_usuario->data)) ? $vw_usuario->data : [];
        $vw_usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        $data['usuario'] = (!empty($vw_usuario->data)) ? $vw_usuario->data : [];

        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];

        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $cat_area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        $data['cat_area'] = (!empty($cat_area->data)) ? $cat_area->data : [];
        $cat_puesto = $globals->getTabla(['tabla' => 'cat_puesto', 'where' => ['visible' => 1]]);
        $data['cat_puesto'] = (!empty($cat_puesto->data)) ? $cat_puesto->data : [];

        $data['solicitud'] = $detalle['solicitud'];
        $data['actividades'] = $detalle['actividades'];
        $data['catalogo_firmantes'] = $this->construirCatalogoFirmantes($data['direccion'], $data['usuario']);
        $data['firmas_seleccionadas'] = $this->obtenerFirmasSolicitud($detalle['solicitud']);
        $data['scripts'] = array('inicio');
        $data['edita'] = 1;
        $data['contentView'] = 'personal/vSolicitudHonorarios';
        $this->_renderView($data);
    }

    public function guardarSolicitudHonorarios()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al guardar la solicitud';

        $post = $this->request->getPost();
        $id_solicitud_honorario = $post['id_solicitud_honorario'] ?? ($post['id_solicitud_honorarios'] ?? null);

        $dataInsert = [
            'responsable_proyecto' => $post['responsable_proyecto'] ?? null,
            'area' => $post['area'] ?? null,
            'informes_rendir' => $post['informes_rendir'] ?? null,
            'vigencia_inicio' => $post['vigencia_inicio'] ?? null,
            'vigencia_fin' => $post['vigencia_fin'] ?? null,
            'clave_presupuestal' => $post['clave_presupuestal'] ?? null,
            'partida' => $post['partida'] ?? null,
            'monto_total_contrato' => $post['monto_total_contrato'] ?? null,
            'nombre_prestador' => $post['nombre_prestador'] ?? null,
            'rfc_prestador' => $post['rfc_prestador'] ?? null,
            'domicilio_prestador' => $post['domicilio_prestador'] ?? null,
            'autorizacion_sfia' => !empty($post['autorizacion_sfia']) ? 1 : 0,
            'justificacion_oficial' => !empty($post['justificacion_oficial']) ? 1 : 0,
            'cedula_rfc' => !empty($post['cedula_rfc']) ? 1 : 0,
            'comprobante_domicilio' => !empty($post['comprobante_domicilio']) ? 1 : 0,
            'autorizacion_datos' => !empty($post['autorizacion_datos']) ? 1 : 0,
        ];

        $columnasSolicitudHonorario = $this->obtenerColumnasTablaServicio($globals, 'solicitud_honorario');
        foreach (['prestacion_servicios', 'puesto_prestador', 'id_puesto'] as $campoPuesto) {
            if (in_array($campoPuesto, $columnasSolicitudHonorario, true)) {
                $dataInsert[$campoPuesto] = $post['prestacion_servicios'] ?? null;
                break;
            }
        }

        $dataConfig = ["tabla" => "solicitud_honorario", "editar" => false];
        $script = 'Principal.php/guardarSolicitudHonorarios';

        if ($id_solicitud_honorario) {
            $dataConfig = [
                "tabla" => "solicitud_honorario",
                "editar" => true,
                "idEditar" => ['id_solicitud_honorario' => $id_solicitud_honorario]
            ];
            $dataInsert['id_estatus'] = 1;
            $dataInsert['usu_act'] = $session->id_usuario ?? 0;
            $dataInsert['fec_act'] = date('Y-m-d H:i:s');
        } else {
            $dataInsert['id_estatus'] = 1;
            $dataInsert['usu_reg'] = $session->id_usuario ?? 0;
            $dataInsert['fec_reg'] = date('Y-m-d H:i:s');
        }

        $dataBitacora = ['id_user' => $session->id_usuario ?? 0, 'script' => $script];
        $res = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        if ($res->error) {
            $response->respuesta = $res->respuesta;
            return $this->respond($response);
        }

        $id_solicitud = $id_solicitud_honorario ? $id_solicitud_honorario : ($res->idRegistro ?? null);

        if (!$id_solicitud) {
            $response->respuesta = 'No fue posible identificar la solicitud guardada';
            return $this->respond($response);
        }

        $this->guardarFirmasSolicitud(
            $globals,
            'solicitud_honorario',
            'id_solicitud_honorario',
            (int) $id_solicitud,
            $post['firmas'] ?? [],
            (int) ($session->id_usuario ?? 0),
            'Principal.php/guardarSolicitudHonorariosFirmas'
        );

        if ($id_solicitud_honorario) {
            $globals->saveTabla(
                ['visible' => 0],
                [
                    "tabla" => "actividades_honorario",
                    "editar" => true,
                    "idEditar" => ['id_solicitud_honorario' => $id_solicitud_honorario]
                ],
                ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/eliminarActividadesHonorario']
            );
        }

        $actividades = $post['actividades'] ?? [];
        foreach ($actividades as $actividad) {
            $actividad = trim((string) $actividad);
            if ($actividad === '') {
                continue;
            }

            $dataActividad = [
                'id_solicitud_honorario' => $id_solicitud,
                'actividad' => $actividad,
                'visible' => 1,
                'usu_reg' => $session->id_usuario ?? 0,
                'fec_reg' => date('Y-m-d H:i:s')
            ];

            $resActividad = $globals->saveTabla(
                $dataActividad,
                ["tabla" => "actividades_honorario", "editar" => false],
                ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/guardarSolicitudHonorarios']
            );

            if ($resActividad->error) {
                $response->respuesta = $resActividad->respuesta;
                return $this->respond($response);
            }
        }

        $response->error = false;
        $response->respuesta = 'Solicitud guardada correctamente';
        $response->id_solicitud_honorario = $id_solicitud;
        $response->url_listado = base_url('index.php/Principal/listadoHonorarios');
        $response->url_pdf = base_url('index.php/Principal/pdfSolicitudHonorarios/' . $id_solicitud);

        return $this->respond($response);
    }

    public function subirArchivosSolicitudHonorarios()
    {
        $id_solicitud = $this->request->getPost('id_solicitud');
        $documentos = $this->request->getPost('documentos');

        if (!$id_solicitud || empty($documentos)) {
            return redirect()->to(base_url('index.php/Principal/listadoHonorarios'));
        }

        $data['id_solicitud'] = $id_solicitud;
        $data['documentos'] = $documentos;
        $data['contentView'] = 'secciones/vSubirArchivosSolicitudHonorarios';
        $this->_renderView($data);
    }

    public function guardarArchivosSolicitudHonorarios()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;

      

        $id_solicitud = $this->request->getPost('id_solicitud');
        if (!$id_solicitud) {
            $response->respuesta = 'ID de solicitud no valido.';
            return $this->respond($response);
        }

        $count = 0;
        $errores = 0;

        if (isset($_FILES['archivos']) && is_array($_FILES['archivos']['name'])) {
            foreach ($_FILES['archivos']['name'] as $key => $originalName) {
                if (empty($originalName)) {
                    continue;
                }

                if ($_FILES['archivos']['error'][$key] !== UPLOAD_ERR_OK) {
                    $errores++;
                    continue;
                }

                $tmpName = $_FILES['archivos']['tmp_name'][$key];
                $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                $newName = 'honorario_' . $id_solicitud . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) $key) . '_' . time() . ($ext ? '.' . strtolower($ext) : '');
                $s3Key = $this->uploadFileToS3Storage($tmpName, 'honorarios', 'documentos', $newName);

                if (!$s3Key) {
                    $errores++;
                    continue;
                }

                $dataInsert = [
                    'id_solicitud_honorario' => $id_solicitud,
                    'clave_documento' => $key,
                    'nombre_documento' => $this->documentosSolicitudHonorarios()[$key] ?? ('Documento ' . $key),
                    'nombre_archivo' => $s3Key,
                    'visible' => 1,
                    'usu_reg' => $session->id_usuario ?? 0,
                    'fec_reg' => date('Y-m-d H:i:s'),
                ];

                $res = $globals->saveTabla(
                    $dataInsert,
                    ["tabla" => "solicitud_honorario_archivos", "editar" => false],
                    ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/guardarArchivosSolicitudHonorarios']
                );

                if ($res->error) {
                    $errores++;
                    continue;
                }

                $count++;
            }
        }

        if ($count > 0) {
            $globals->saveTabla(
                ['id_estatus' => 4],
                ["tabla" => "solicitud_honorario", "editar" => true, "idEditar" => ["id_solicitud_honorario" => $id_solicitud]],
                ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/guardarArchivosSolicitudHonorarios']
            );
        }

        if ($count > 0) {
            $response->error = false;
            $response->respuesta = $errores > 0
                ? 'Se guardaron ' . $count . ' archivo(s), pero algunos no pudieron cargarse.'
                : 'Archivos guardados correctamente.';
        } else {
            $response->respuesta = 'No se pudo guardar ningun archivo.';
        }

        if ($count > 0) {
            // Enviar correo a lvelaga@guanajuato.gob.mx
            $emailService = \Config\Services::email();
            $usuarioQuery = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["id_usuario" => ($session->id_usuario ?? 0)]]);
            $nombreUsuario = (isset($usuarioQuery->data) && !empty($usuarioQuery->data)) ? $usuarioQuery->data[0]->nombre_completo : 'Usuario Desconocido';
            $enlace = base_url('index.php/Principal/listadoHonorarios');
            $emailService->setFrom('noreply@susi.gob.mx', 'SUSI - SECTURI');
            $emailService->setTo($this->obtenerCorreosRevisionJuridica());
            $emailService->setSubject('Nueva Solicitud de Honorarios - Archivos Adjuntados');
            $emailService->setMailType('html');
            $emailService->setMessage("
                <p>Buen día,</p>
                <p>Se le notifica que se han subido documentos para la solicitud de honorarios con ID <strong>{$id_solicitud}</strong>.</p>
                <p>Los archivos fueron agregados por el usuario: <strong>{$nombreUsuario}</strong>.</p>
                <p>Puede consultar los detalles ingresando al siguiente enlace: <a href='{$enlace}'>{$enlace}</a></p>
                <br>
                <p>Saludos cordiales,</p>
                <p><strong>Sistema Unificado SECTURI (SUSI)</strong></p>
                <a href='https://secturnet.guanajuato.gob.mx/susi/index.php/Principal/listadoHonorarios'>Ir al sistema</a>
            ");
            $emailService->send();

            $response->error = false;
            $msg = "Se guardaron $count archivos correctamente.";
            if ($errores > 0) $msg .= " Hubo problemas con $errores archivos.";
            $response->respuesta = $msg;
        } else {
            $response->respuesta = "No se guardó ningún archivo. " . ($errores > 0 ? "Hubo errores al procesar." : "No se seleccionaron archivos.");
        } 

        return $this->respond($response);
    }

    public function declinarSolicitudHonorarios()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $emailService = \Config\Services::email();
        $response = new \stdClass();
        $response->error = true;

        $id = $this->request->getPost('id_solicitud');
        $motivo = trim((string) $this->request->getPost('motivo'));

        if (!$id) {
            $response->respuesta = 'ID de solicitud no valido.';
            return $this->respond($response);
        }

        $res = $globals->saveTabla([
            'id_estatus' => 2,
            'motivo' => $motivo,
            'usu_act' => $session->id_usuario ?? 0,
            'fec_act' => date('Y-m-d H:i:s')
        ], [
            'tabla' => 'solicitud_honorario',
            'editar' => true,
            'idEditar' => ['id_solicitud_honorario' => $id]
        ], [
            'id_user' => $session->id_usuario ?? 0,
            'script' => 'Principal.php/declinarSolicitudHonorarios'
        ]);

        if ($res->error) {
            $response->respuesta = 'No se pudo declinar la solicitud.';
            return $this->respond($response);
        }

        $solicitudQuery = $globals->getTabla(['tabla' => 'solicitud_honorario', 'where' => ['id_solicitud_honorario' => $id]]);
        if (!empty($solicitudQuery->data)) {
            $usu_reg = $solicitudQuery->data[0]->usu_reg ?? 0;
            $usuarioQuery = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['id_usuario' => $usu_reg]]);
            if (!empty($usuarioQuery->data) && !empty($usuarioQuery->data[0]->correo)) {
                $correoDestino = $usuarioQuery->data[0]->correo;
                $nombreUsuario = $usuarioQuery->data[0]->nombre_completo ?? 'Usuario';
                $emailService->setFrom('noreply@susi.gob.mx', 'SUSI - SECTURI');
                $emailService->setTo($correoDestino);
                $emailService->setSubject('Solicitud de Honorarios Declinada');
                $emailService->setMailType('html');
                $emailService->setMessage("
                    <p>Buen día, <strong>{$nombreUsuario}</strong>:</p>
                    <p>Se le notifica que su solicitud de elaboración de honorarios con ID <strong>{$id}</strong> ha sido <strong>declinada</strong>.</p>
                    <p><strong>Motivo:</strong> {$motivo}</p>
                    <br>
                    <p>Saludos cordiales,</p>
                ");
                $emailService->send();
            }
        }

        $response->error = false;
        $response->respuesta = 'Solicitud declinada correctamente.';
        return $this->respond($response);
    }

    public function subirInstrumentoJuridicoHonorarios()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $emailService = \Config\Services::email();
        $response = new \stdClass();
        $response->error = true;

        $id = $this->request->getPost('id_solicitud');
        $archivos = $this->request->getFileMultiple('archivos');

        if (!$id || empty($archivos)) {
            $response->respuesta = 'Archivos o ID de solicitud no valido.';
            return $this->respond($response);
        }

        if (count($archivos) > 4) {
            $response->respuesta = "Solo se permiten hasta 4 instrumentos juridicos.";
            return $this->respond($response);
        }

        $rutasGuardadas = [];
        foreach ($archivos as $archivo) {
            if (!$archivo->isValid() || $archivo->hasMoved() || strtolower((string) $archivo->getExtension()) !== 'pdf') {
                continue;
            }

            $newName = $archivo->getRandomName();
            $s3Key = $this->uploadFileToS3Storage($archivo->getTempName(), 'honorarios', 'instrumentos', $newName);
            if (!$s3Key) {
                continue;
            }

            $rutasGuardadas[] = $s3Key;
            $globals->saveTabla([
                'id_solicitud_honorario' => $id,
                'clave_documento' => 'instrumento_juridico',
                'nombre_documento' => 'Instrumento Juridico',
                'nombre_archivo' => $s3Key,
                'visible' => 1,
                'usu_reg' => $session->id_usuario ?? 0,
                'fec_reg' => date('Y-m-d H:i:s'),
            ], [
                'tabla' => 'solicitud_honorario_archivos',
                'editar' => false
            ], [
                'id_user' => $session->id_usuario ?? 0,
                'script' => 'Principal.php/subirInstrumentoJuridicoHonorariosArchivo'
            ]);
        }

        if (empty($rutasGuardadas)) {
            $response->respuesta = 'No se pudieron guardar los archivos o no son PDF validos.';
            return $this->respond($response);
        }

        $columnasSolicitudHonorario = $this->obtenerColumnasTablaServicio($globals, 'solicitud_honorario');
        $dataUpdate = [
            'id_estatus' => 3,
            'instrumento_juridico' => json_encode(array_values($rutasGuardadas)),
            'usu_act' => $session->id_usuario ?? 0,
            'fec_act' => date('Y-m-d H:i:s')
        ];
        if (in_array('instrumento_juridico', $columnasSolicitudHonorario, true)) {
            $dataUpdate['instrumento_juridico'] = json_encode($rutasGuardadas);
        }

        $res = $globals->saveTabla($dataUpdate, [
            'tabla' => 'solicitud_honorario',
            'editar' => true,
            'idEditar' => ['id_solicitud_honorario' => $id]
        ], [
            'id_user' => $session->id_usuario ?? 0,
            'script' => 'Principal.php/subirInstrumentoJuridicoHonorarios'
        ]);

        if ($res->error) {
            $response->respuesta = 'No se pudo actualizar la solicitud.';
            return $this->respond($response);
        }

        $solicitudQuery = $globals->getTabla(['tabla' => 'solicitud_honorario', 'where' => ['id_solicitud_honorario' => $id]]);
        if (!empty($solicitudQuery->data)) {
            $usu_reg = $solicitudQuery->data[0]->usu_reg ?? 0;
            $usuarioQuery = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['id_usuario' => $usu_reg]]);
            if (!empty($usuarioQuery->data) && !empty($usuarioQuery->data[0]->correo)) {
                $correoDestino = $usuarioQuery->data[0]->correo;
                $nombreUsuario = $usuarioQuery->data[0]->nombre_completo ?? 'Usuario';
                $enlaceListado = 'https://secturnet.guanajuato.gob.mx/susi/index.php/Principal/listadoHonorarios';

                $emailService->setFrom('noreply@susi.gob.mx', 'SUSI - SECTURI');
                $emailService->setTo($correoDestino);
                $emailService->setSubject('Solicitud de Honorarios Aprobada - Instrumento Disponible');
                $emailService->setMailType('html');
                $emailService->setMessage("
                    <p>Buen dia, <strong>{$nombreUsuario}</strong>:</p>
                    <p>El area Juridica ha autorizado y adjuntado el instrumento juridico correspondiente a su solicitud de honorarios con ID <strong>{$id}</strong>.</p>
                    <p>Puede consultarlo ingresando al siguiente enlace:</p>
                    <p><a href='{$enlaceListado}' target='_blank'>{$enlaceListado}</a></p>
                    <br>
                    <p>Saludos cordiales,</p>
                    <p><strong>Sistema Unificado SECTURI (SUSI)</strong></p>
                ");
                $emailService->send();
            }
        }

        $response->error = false;
        $response->respuesta = 'Instrumento juridico subido y solicitud aprobada.';
        return $this->respond($response);
    }

    public function verArchivosSolicitudHonorarios($id_solicitud)
    {
        $globals = new Mglobal;
       

        $archivos = $globals->getTabla([
            'tabla' => 'solicitud_honorario_archivos',
            'where' => ['id_solicitud_honorario' => $id_solicitud, 'visible' => 1]
        ]);
        //die( var_dump($archivos) );
        if (!empty($archivos->data)) {
            $archivosFiltrados = [];
            foreach ($archivos->data as &$archivo) {
                if (isset($archivo->clave_documento) && $archivo->clave_documento === 'instrumento_juridico') {
                    continue;
                }
                $archivo->url_descarga = $this->resolveStoredFilePreviewUrl($archivo->nombre_archivo ?? null, 'assets/uploads/honorarios');
                $archivosFiltrados[] = $archivo;
            }
            $archivos->data = $archivosFiltrados;
        }

        $data['id_solicitud'] = $id_solicitud;
        $data['archivos'] = !empty($archivos->data) ? $archivos->data : [];
        $data['modulo_archivos'] = 'honorarios';
        $data['scripts'] = [];
        $data['contentView'] = 'secciones/vVerArchivosSolicitud';
        $this->_renderView($data);
    }

    private function construirCatalogoFirmantes(array $direccion = [], array $usuarios = []): array
    {
        $firmantes = [];
        $agregados = [];

        foreach ([$direccion, $usuarios] as $coleccion) {
            foreach ($coleccion as $usuario) {
                $idUsuario = (int) ($usuario->id_usuario ?? 0);
                if ($idUsuario <= 0) {
                    continue;
                }

                $nombre = trim((string) ($usuario->nombre_completo ?? ''));
                $puesto = trim((string) ($usuario->dsc_puesto ?? ''));

                if (isset($agregados[$idUsuario])) {
                    if ($puesto !== '' && $firmantes[$agregados[$idUsuario]]->dsc_puesto === '') {
                        $firmantes[$agregados[$idUsuario]]->dsc_puesto = $puesto;
                    }
                    continue;
                }

                $firmantes[] = (object) [
                    'id_usuario' => $idUsuario,
                    'nombre_completo' => $nombre,
                    'dsc_puesto' => $puesto,
                ];
                $agregados[$idUsuario] = count($firmantes) - 1;
            }
        }

        return $firmantes;
    }

    private function obtenerFirmasSolicitud($solicitud): array
    {
        if (empty($solicitud) || !is_object($solicitud)) {
            return [];
        }

        $firmas = [];
        foreach (['firmas_json', 'firmantes_json', 'firmas'] as $campoJson) {
            if (empty($solicitud->{$campoJson}) || !is_string($solicitud->{$campoJson})) {
                continue;
            }

            $decodificado = json_decode($solicitud->{$campoJson}, true);
            if (!is_array($decodificado)) {
                continue;
            }

            foreach ($decodificado as $firma) {
                $idUsuario = is_array($firma) ? ($firma['id_usuario'] ?? null) : $firma;
                $idUsuario = (int) $idUsuario;
                if ($idUsuario > 0) {
                    $firmas[] = $idUsuario;
                }
            }

            if (!empty($firmas)) {
                break;
            }
        }

        foreach (['firma_1', 'firma_2', 'firma_3'] as $campoFirma) {
            $idUsuario = (int) ($solicitud->{$campoFirma} ?? 0);
            if ($idUsuario > 0) {
                $firmas[] = $idUsuario;
            }
        }

        $firmas = array_values(array_filter($firmas));
        return array_slice($firmas, 0, 3);
    }

    private function obtenerColumnasTablaServicio(Mglobal $globals, string $tabla): array
    {
        static $cacheColumnas = [];

        if (!preg_match('/^[A-Za-z0-9_]+$/', $tabla)) {
            return [];
        }

        if (isset($cacheColumnas[$tabla])) {
            return $cacheColumnas[$tabla];
        }

        $consulta = $globals->getTabla(['query' => "SHOW COLUMNS FROM {$tabla}"]);
        $columnas = [];

        if (!$consulta->error && !empty($consulta->data)) {
            foreach ($consulta->data as $columna) {
                if (is_object($columna) && !empty($columna->Field)) {
                    $columnas[] = (string) $columna->Field;
                    continue;
                }

                if (is_array($columna) && !empty($columna['Field'])) {
                    $columnas[] = (string) $columna['Field'];
                }
            }
        }

        $cacheColumnas[$tabla] = $columnas;
        return $columnas;
    }

    private function guardarFirmasSolicitud(Mglobal $globals, string $tabla, string $idCampo, int $idRegistro, array $firmas, int $idUsuario, string $script): ?bool
    {
        if ($idRegistro <= 0) {
            return null;
        }

        $firmasNormalizadas = [];
        foreach ($firmas as $firma) {
            $idFirma = (int) $firma;
            if ($idFirma > 0) {
                $firmasNormalizadas[] = $idFirma;
            }
        }

        $firmasNormalizadas = array_slice(array_values($firmasNormalizadas), 0, 3);
        $columnas = $this->obtenerColumnasTablaServicio($globals, $tabla);
        if (empty($columnas)) {
            return null;
        }

        $dataUpdate = [];
        if (in_array('firmas_json', $columnas, true)) {
            $dataUpdate['firmas_json'] = json_encode($firmasNormalizadas);
        } elseif (in_array('firmantes_json', $columnas, true)) {
            $dataUpdate['firmantes_json'] = json_encode($firmasNormalizadas);
        } elseif (in_array('firmas', $columnas, true)) {
            $dataUpdate['firmas'] = json_encode($firmasNormalizadas);
        } else {
            foreach ([1, 2, 3] as $indice) {
                $nombreColumna = 'firma_' . $indice;
                if (in_array($nombreColumna, $columnas, true)) {
                    $dataUpdate[$nombreColumna] = $firmasNormalizadas[$indice - 1] ?? null;
                }
            }
        }

        if (empty($dataUpdate)) {
            return null;
        }

        $resultado = $globals->saveTabla(
            $dataUpdate,
            ['tabla' => $tabla, 'editar' => true, 'idEditar' => [$idCampo => $idRegistro]],
            ['id_user' => $idUsuario, 'script' => $script]
        );

        return !$resultado->error;
    }

    private function obtenerFirmasSolicitudDetalle(Mglobal $globals, $solicitud): array
    {
        $firmas = [];
        foreach ($this->obtenerFirmasSolicitud($solicitud) as $idUsuario) {
            $firma = $this->obtenerDatosFirmaUsuario($globals, $idUsuario);
            if ($firma !== null && ($firma->nombre !== '' || $firma->cargo !== '')) {
                $firmas[] = $firma;
            }
        }

        return array_slice($firmas, 0, 3);
    }

    public function SolicitudContrato()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = array();
        
        $vw_usuario = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1]]);
        $data['direccion'] = $vw_usuario->data;
        $vw_usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        $data['usuario'] = $vw_usuario->data;

        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];

        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['partidas_extra'] = [];
        $data['catalogo_firmantes'] = $this->construirCatalogoFirmantes($data['direccion'], $data['usuario']);
        $data['firmas_seleccionadas'] = [];
       //die( var_dump( $data['cat_partida']  ) );
        // Cargar catalogos si es necesario, similar a otras vistas
        // Por ahora solo cargamos la vista básica
        $data['scripts'] = array('inicio'); // Asumiendo scripts estandar
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vSolicitudContrato';
        $this->_renderView($data);
    }

    private function documentosSolicitudAdquisiciones(): array
    {
        return [
            1 => "Anexo TÃ©cnico (TÃ©rminos de referencia)",
            2 => "InvestigaciÃ³n de Mercado (Cotizaciones y consulta PEI)",
            3 => "ValidaciÃ³n de partida restringida (SF)\nVerificaciÃ³n de AlineaciÃ³n de InformaciÃ³n EstratÃ©gica (DGIT)\nSuficiencia presupuestal (R3)\nValidaciÃ³n DGTIT/CGCS u otra",
            4 => "JustificaciÃ³n",
            5 => "Propuesta TÃ©cnico EconÃ³mica (Anexo)",
            6 => "Aviso de privacidad integral",
            7 => "CÃ©dula de Registro en el PadrÃ³n de Proveedores (Refrendo vigente)",
            8 => "Escritura Constitutiva/Documento que acredite la legal constituciÃ³n de la persona moral (Modificaciones sustanciales e inscripciÃ³n en el Registro PÃºblico)",
            9 => "Documento que acredite la representaciÃ³n de la persona moral (Poder)",
            10 => "IdentificaciÃ³n oficial vigente (Personas morales Representante y Responsable de seguimiento)",
            11 => "Constancia de SituaciÃ³n Fiscal (RFC)",
            12 => "Comprobante de domicilio (SÃ³lo cuando sea diferente al domicilio fiscal)",
            13 => "OpiniÃ³n de cumplimiento de Obligaciones Fiscales\nManifiesto bajo protesta de cumplimiento de Obligaciones Fiscales",
            14 => "Manifiesto de no encontrarse impedido para Contratar",
            15 => "Carta de DeclaraciÃ³n de intereses",
            16 => "Manifiesto de contar con infraestructura",
            17 => "Carta compromiso entrega de bienes (ExcepciÃ³n de GarantÃ­a)",
        ];
    }

    public function SolicitudAdquisiciones()
    {
        $globals = new Mglobal;
        $data = [];

        $vwDireccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1]]);
        $data['direccion'] = !empty($vwDireccion->data) ? $vwDireccion->data : [];

        $vwUsuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        $data['usuario'] = !empty($vwUsuario->data) ? $vwUsuario->data : [];

        $catPartida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $data['cat_partida'] = !empty($catPartida->data) ? $catPartida->data : [];
        $data['catalogo_firmantes'] = $this->construirCatalogoFirmantes($data['direccion'], $data['usuario']);
        $data['firmas_seleccionadas'] = [];

        $data['scripts'] = ['inicio'];
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vSolicitudAdquisiciones';
        $this->_renderView($data);
    }

    public function editarSolicitudAdquisiciones($id_solicitud = null)
    {
        $globals = new Mglobal;

        if (!$id_solicitud) {
            return redirect()->to(base_url('index.php/Principal/ListaSolicitudAdquisiciones'));
        }

        $solicitud = $globals->getTabla([
            'tabla' => 'solicitud_adquisiciones',
            'where' => ['id_solicitud_adquisiciones' => $id_solicitud, 'visible' => 1]
        ]);

        if (empty($solicitud->data)) {
            return redirect()->to(base_url('index.php/Principal/ListaSolicitudAdquisiciones'));
        }

        $pagos = $globals->getTabla([
            'tabla' => 'solicitud_adquisiciones_pagos',
            'where' => ['id_solicitud_adquisiciones' => $id_solicitud, 'visible' => 1]
        ]);

        $vwDireccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1]]);
        $vwUsuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);

        $data['direccion'] = !empty($vwDireccion->data) ? $vwDireccion->data : [];
        $data['usuario'] = !empty($vwUsuario->data) ? $vwUsuario->data : [];
        $data['solicitud'] = $solicitud->data[0];
        $data['pagos'] = !empty($pagos->data) ? $pagos->data : [];
        $data['catalogo_firmantes'] = $this->construirCatalogoFirmantes($data['direccion'], $data['usuario']);
        $data['firmas_seleccionadas'] = $this->obtenerFirmasSolicitud($data['solicitud']);
        $data['scripts'] = ['inicio'];
        $data['edita'] = 1;
        $data['contentView'] = 'personal/vSolicitudAdquisiciones';
        $this->_renderView($data);
    }

    public function guardarSolicitudAdquisiciones()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al guardar la solicitud';

        $post = $this->request->getPost();
        $idSolicitud = $post['id_solicitud_adquisiciones'] ?? null;

        $dataInsert = [
            'responsable_proyecto' => $post['responsable_proyecto'] ?? null,
            'fecha_solicitud' => $post['fecha_solicitud'] ?? null,
            'responsable_seguimiento' => $post['responsable_seguimiento'] ?? null,
            'vigencia' => $post['vigencia'] ?? null,
            'objeto_adquisicion' => $post['objeto_adquisicion'] ?? null,
            'tipo_proceso' => $post['tipo_proceso'] ?? null,
            'no_invitacion' => $post['no_invitacion'] ?? null,
            'fecha_invitacion' => $post['fecha_invitacion'] ?? null,
            'codigo_programatico' => $post['codigo_programatico'] ?? null,
            'fondo' => $post['fondo'] ?? null,
            'numero_partida' => $post['numero_partida'] ?? null,
            'nombre_partida' => $post['nombre_partida'] ?? null,
            'garantia' => $post['garantia'] ?? null,
            'monto_garantia' => $post['monto_garantia'] ?? null,
            'texto_monto_garantia' => $post['texto_monto_garantia'] ?? null,
            'descripcion_bienes' => 'N/A',
            'fecha_inicio' => null,
            'lugar_entrega' => 'N/A',
            'proveedor_nombre' => $post['proveedor_nombre'] ?? null,
            'proveedor_comercial' => $post['proveedor_comercial'] ?? null,
            'proveedor_cedula' => $post['proveedor_cedula'] ?? null,
            'proveedor_domicilio' => $post['proveedor_domicilio'] ?? null,
            'proveedor_rfc' => $post['proveedor_rfc'] ?? null,
            'proveedor_representante' => $post['proveedor_representante'] ?? null,
            'proveedor_seguimiento' => $post['proveedor_seguimiento'] ?? null,
        ];

        $dataConfig = ['tabla' => 'solicitud_adquisiciones', 'editar' => false];
        $script = 'Principal.php/guardarSolicitudAdquisiciones';

        if (!empty($idSolicitud)) {
            $dataConfig = [
                'tabla' => 'solicitud_adquisiciones',
                'editar' => true,
                'idEditar' => ['id_solicitud_adquisiciones' => $idSolicitud]
            ];
            $dataInsert['id_estatus'] = 1;
            $dataInsert['usu_act'] = $session->id_usuario ?? 0;
            $dataInsert['fec_act'] = date('Y-m-d H:i:s');
        } else {
            $dataInsert['id_estatus'] = 1;
            $dataInsert['usu_reg'] = $session->id_usuario ?? 0;
            $dataInsert['fec_reg'] = date('Y-m-d H:i:s');
        }

        $res = $globals->saveTabla($dataInsert, $dataConfig, [
            'id_user' => $session->id_usuario ?? 0,
            'script' => $script
        ]);

        if ($res->error) {
            $response->respuesta = $res->respuesta;
            return $this->respond($response);
        }

        $idGuardado = !empty($idSolicitud) ? $idSolicitud : ($res->idRegistro ?? null);
        if (empty($idGuardado)) {
            $response->respuesta = 'No fue posible identificar la solicitud guardada';
            return $this->respond($response);
        }

        $this->guardarFirmasSolicitud(
            $globals,
            'solicitud_adquisiciones',
            'id_solicitud_adquisiciones',
            (int) $idGuardado,
            $post['firmas'] ?? [],
            (int) ($session->id_usuario ?? 0),
            'Principal.php/guardarSolicitudAdquisicionesFirmas'
        );

        if (!empty($idSolicitud)) {
            $globals->saveTabla(
                ['visible' => 0],
                [
                    'tabla' => 'solicitud_adquisiciones_pagos',
                    'editar' => true,
                    'idEditar' => ['id_solicitud_adquisiciones' => $idSolicitud]
                ],
                ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/eliminarPagosAdquisiciones']
            );
        }

        if (isset($post['pagos']) && is_array($post['pagos'])) {
            foreach ($post['pagos'] as $pago) {
                $numeroPago = trim((string) ($pago['numero'] ?? ''));
                $montoPago = trim((string) ($pago['monto'] ?? ''));

                if ($numeroPago === '' && $montoPago === '') {
                    continue;
                }

                $resPago = $globals->saveTabla([
                    'id_solicitud_adquisiciones' => $idGuardado,
                    'numero_pago' => $numeroPago,
                    'monto' => $montoPago,
                    'visible' => 1,
                    'usu_reg' => $session->id_usuario ?? 0,
                    'fec_reg' => date('Y-m-d H:i:s')
                ], [
                    'tabla' => 'solicitud_adquisiciones_pagos',
                    'editar' => false
                ], [
                    'id_user' => $session->id_usuario ?? 0,
                    'script' => 'Principal.php/guardarSolicitudAdquisiciones'
                ]);

                if ($resPago->error) {
                    $response->respuesta = $resPago->respuesta;
                    return $this->respond($response);
                }
            }
        }

        $response->error = false;
        $response->respuesta = 'Solicitud guardada correctamente';
        $response->id_solicitud_adquisiciones = $idGuardado;
        return $this->respond($response);
    }

    public function ListaSolicitudAdquisiciones()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = [];

        $where = ['visible' => 1];
        if (!in_array($session->id_perfil, [1, 7])) {
            $where['usu_reg'] = $session->id_usuario;
        }

        $solicitudes = $globals->getTabla([
            'tabla' => 'solicitud_adquisiciones',
            'where' => $where
        ]);

        if (!empty($solicitudes->data)) {
            $responsables = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1]]);
            $usuariosRegistro = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
            $responsablesMap = [];
            $usuariosMap = [];

            if (!empty($responsables->data)) {
                foreach ($responsables->data as $responsable) {
                    $responsablesMap[(string) ($responsable->id_usuario ?? '')] = trim(($responsable->nombre_completo ?? ''));
                }
            }

            if (!empty($usuariosRegistro->data)) {
                foreach ($usuariosRegistro->data as $usuarioRegistro) {
                    $usuariosMap[(string) ($usuarioRegistro->id_usuario ?? '')] = trim((string) ($usuarioRegistro->nombre_completo ?? ''));
                }
            }

            foreach ($solicitudes->data as &$sol) {
                $archivos = $globals->getTabla([
                    'tabla' => 'solicitud_adquisiciones_archivos',
                    'where' => ['visible' => 1, 'id_solicitud_adquisiciones' => $sol->id_solicitud_adquisiciones]
                ]);
                $sol->tienen_archivos = !empty($archivos->data);
                $sol->nombre_proyecto = $responsablesMap[(string) ($sol->responsable_proyecto ?? '')] ?? $usuariosMap[(string) ($sol->responsable_proyecto ?? '')] ?? ($sol->responsable_proyecto ?? '');
                $sol->nombre_registra = $usuariosMap[(string) ($sol->usu_reg ?? '')] ?? ($sol->usu_reg ?? '');

                $instrumentos = [];
                if (!empty($sol->instrumento_juridico)) {
                    $decoded = json_decode((string) $sol->instrumento_juridico, true);
                    $instrumentos = is_array($decoded) ? $decoded : [$sol->instrumento_juridico];
                } elseif (!empty($sol->instrumento)) {
                    $decoded = json_decode((string) $sol->instrumento, true);
                    $instrumentos = is_array($decoded) ? $decoded : [$sol->instrumento];
                }

                if (!empty($archivos->data)) {
                    foreach ($archivos->data as $archivoSolicitud) {
                        if (($archivoSolicitud->clave_documento ?? '') === 'instrumento_juridico' && !empty($archivoSolicitud->nombre_archivo)) {
                            $instrumentos[] = $archivoSolicitud->nombre_archivo;
                        }
                    }
                }

                $instrumentos = array_values(array_unique(array_filter($instrumentos)));
                $sol->instrumento_urls = $this->mapInstrumentoUrls(array_slice($instrumentos, 0, 4));
            }
        }

        $data['solicitudes'] = !empty($solicitudes->data) ? $solicitudes->data : [];
        $data['scripts'] = ['inicio'];
        $data['contentView'] = 'personal/vListaSolicitudAdquisiciones';
        $this->_renderView($data);
    }

    public function verSolicitudAdquisicionesPDF($id = null)
    {
        if (empty($id)) {
            echo 'ID no vÃ¡lido';
            return;
        }

        $globals = new Mglobal;
        $solicitudQuery = $globals->getTabla([
            'tabla' => 'solicitud_adquisiciones',
            'where' => ['id_solicitud_adquisiciones' => $id, 'visible' => 1]
        ]);

        if (empty($solicitudQuery->data)) {
            echo 'Solicitud no encontrada';
            return;
        }

        $pagosQuery = $globals->getTabla([
            'tabla' => 'solicitud_adquisiciones_pagos',
            'where' => ['id_solicitud_adquisiciones' => $id, 'visible' => 1]
        ]);

        $solicitud = $solicitudQuery->data[0];
        $pagos = !empty($pagosQuery->data) ? $pagosQuery->data : [];

        $direcciones = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1]]);
        if (!empty($direcciones->data)) {
            foreach ($direcciones->data as $direccion) {
                if ((string) ($direccion->id_usuario ?? '') === (string) ($solicitud->responsable_proyecto ?? '')) {
                    $solicitud->responsable_proyecto_nombre = trim(($direccion->nombre_completo ?? '') . ' - ' . ($direccion->dsc_puesto ?? ''));
                    break;
                }
            }
        }

        $usuarios = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        if (!empty($usuarios->data)) {
            foreach ($usuarios->data as $usuario) {
                if ((string) ($usuario->id_usuario ?? '') === (string) ($solicitud->responsable_seguimiento ?? '')) {
                    $solicitud->responsable_seguimiento_nombre = trim(($usuario->nombre_completo ?? '') . ' - ' . ($usuario->dsc_puesto ?? ''));
                    break;
                }
            }
        }

        foreach ($pagos as $pago) {
            $pago->monto_letra = $this->numeroEnLetras((float) str_replace([',', '$', ' '], '', (string) ($pago->monto ?? 0)));
        }

        $data['solicitud'] = $solicitud;
        $data['pagos'] = $pagos;
        $data['firmas_pdf'] = $this->obtenerFirmasSolicitudDetalle($globals, $solicitud);
        $html = view('personal/vPdfSolicitudAdquisiciones', $data);

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 5,
            'margin_left' => 6,
            'margin_right' => 6,
            'margin_bottom' => 5,
            'format' => 'Letter'
        ]);
        $mpdf->shrink_tables_to_fit = 1;

        $mpdf->WriteHTML($html);
        $mpdf->Output('Solicitud_Adquisicion_' . $id . '.pdf', 'I');
        exit();
    }

    public function subirArchivosSolicitudAdquisiciones()
    {
        $id_solicitud = $this->request->getPost('id_solicitud');
        $documentos = $this->request->getPost('documentos');

        if (!$id_solicitud || empty($documentos)) {
            return redirect()->to(base_url('index.php/Principal/ListaSolicitudAdquisiciones'));
        }

        $data['id_solicitud'] = $id_solicitud;
        $data['documentos'] = $documentos;
        $data['contentView'] = 'secciones/vSubirArchivosSolicitudAdquisiciones';
        $this->_renderView($data);
    }

    public function verArchivosSolicitudAdquisiciones($id_solicitud)
    {
        $globals = new Mglobal;

        $archivos = $globals->getTabla([
            'tabla' => 'solicitud_adquisiciones_archivos',
            'where' => ['id_solicitud_adquisiciones' => $id_solicitud, 'visible' => 1]
        ]);

        if (!empty($archivos->data)) {
            foreach ($archivos->data as &$archivo) {
                $archivo->url_descarga = $this->resolveStoredFilePreviewUrl($archivo->nombre_archivo ?? null, 'assets/uploads/adquisiciones');
            }
        }

        $data['id_solicitud'] = $id_solicitud;
        $data['archivos'] = !empty($archivos->data) ? $archivos->data : [];
        $data['modulo_archivos'] = 'adquisiciones';
        $data['scripts'] = [];
        $data['contentView'] = 'secciones/vVerArchivosSolicitud';
        $this->_renderView($data);
    }

    public function guardarArchivosSolicitudAdquisiciones()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;

        $id_solicitud = $this->request->getPost('id_solicitud');
        if (!$id_solicitud) {
            $response->respuesta = 'ID de solicitud no vÃ¡lido.';
            return $this->respond($response);
        }

        $count = 0;
        $errores = 0;

        if (isset($_FILES['archivos']) && is_array($_FILES['archivos']['name'])) {
            foreach ($_FILES['archivos']['name'] as $key => $originalName) {
                if (empty($originalName)) {
                    continue;
                }

                if ($_FILES['archivos']['error'][$key] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['archivos']['tmp_name'][$key];
                    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                    $newName = $id_solicitud . '_' . $key . '_' . time() . '.' . $ext;
                    $s3Key = $this->uploadFileToS3Storage($tmpName, 'adquisiciones', 'documentos', $newName);
                    if ($s3Key) {
                        $res = $globals->saveTabla([
                            'id_solicitud_adquisiciones' => $id_solicitud,
                            'clave_documento' => $key,
                            'nombre_archivo' => $s3Key,
                            'tipo' => $ext,
                            'usu_reg' => $session->id_usuario ?? 0,
                            'fec_reg' => date('Y-m-d H:i:s'),
                            'visible' => 1
                        ], [
                            'tabla' => 'solicitud_adquisiciones_archivos',
                            'editar' => false
                        ], [
                            'id_user' => $session->id_usuario ?? 0,
                            'script' => 'Principal.php/guardarArchivosSolicitudAdquisiciones'
                        ]);

                        $globals->saveTabla([
                            'id_estatus' => 4
                        ], [
                            'tabla' => 'solicitud_adquisiciones',
                            'editar' => true,
                            'idEditar' => ['id_solicitud_adquisiciones' => $id_solicitud]
                        ], [
                            'id_user' => $session->id_usuario ?? 0,
                            'script' => 'Principal.php/guardarArchivosSolicitudAdquisiciones'
                        ]);

                        if (!$res->error) {
                            $count++;
                        } else {
                            $errores++;
                        }
                    } else {
                        $errores++;
                    }
                } else {
                    $errores++;
                }
            }
        }

        if ($count > 0) {
            $response->error = false;
            $response->respuesta = "Se guardaron $count archivos correctamente." . ($errores > 0 ? " Hubo problemas con $errores archivos." : '');
        } else {
            $response->respuesta = "No se guardÃ³ ningÃºn archivo." . ($errores > 0 ? " Hubo errores al procesar." : '');
        }

        if ($count > 0 && !in_array($session->get('id_usuario'), [1, 149])) {
            // Enviar correo a lvelaga@guanajuato.gob.mx
            $emailService = \Config\Services::email();
            $usuarioQuery = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["id_usuario" => ($session->id_usuario ?? 0)]]);
            $nombreUsuario = (isset($usuarioQuery->data) && !empty($usuarioQuery->data)) ? $usuarioQuery->data[0]->nombre_completo : 'Usuario Desconocido';
            $enlace = base_url('index.php/Principal/ListaSolicitudContrato');
            $emailService->setFrom('noreply@susi.gob.mx', 'SUSI - SECTURI');
            $emailService->setTo($this->obtenerCorreosRevisionJuridica());
            $emailService->setSubject('Nueva Solicitud de Contrato - Archivos Adjuntados');
            $emailService->setMailType('html');
            $emailService->setMessage("
                <p>Buen día,</p>
                <p>Se le notifica que se han subido documentos para la solicitud de contrato con ID <strong>{$id_solicitud}</strong>.</p>
                <p>Los archivos fueron agregados por el usuario: <strong>{$nombreUsuario}</strong>.</p>
                <p>Puede consultar los detalles ingresando al siguiente enlace: <a href='{$enlace}'>{$enlace}</a></p>
                <br>
                <p>Saludos cordiales,</p>
                <p><strong>Sistema Unificado SECTURI (SUSI)</strong></p>
                <a href='https://secturnet.guanajuato.gob.mx/susi/index.php/Principal/ListaSolicitudContrato'>Ir al sistema</a>
            ");
            $emailService->send();

            $response->error = false;
            $msg = "Se guardaron $count archivos correctamente.";
            if ($errores > 0) $msg .= " Hubo problemas con $errores archivos.";
            $response->respuesta = $msg;
        } else {
            $response->respuesta = "No se guardó ningún archivo. " . ($errores > 0 ? "Hubo errores al procesar." : "No se seleccionaron archivos.");
        } 

        return $this->respond($response);
    }

    public function declinarSolicitudAdquisiciones()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $emailService = \Config\Services::email();
        $response = new \stdClass();
        $response->error = true;

        $id = $this->request->getPost('id_solicitud');
        $motivo = $this->request->getPost('motivo');

        if (!$id) {
            $response->respuesta = "ID de solicitud no válido.";
            return $this->respond($response);
        }

        $dataUpdate = [
            'id_estatus' => 2,
            'usu_act' => $session->id_usuario ?? 0,
            'fec_act' => date('Y-m-d H:i:s')
        ];


        $res = $globals->saveTabla($dataUpdate, [
            'tabla' => 'solicitud_adquisiciones',
            'editar' => true,
            'idEditar' => ['id_solicitud_adquisiciones' => $id]
        ], [
            'id_user' => $session->id_usuario ?? 0,
            'script' => 'Principal.php/declinarSolicitudAdquisiciones'
        ]);

        if (!$res->error) {
            $solicitudQuery = $globals->getTabla(["tabla" => "solicitud_adquisiciones", "where" => ["id_solicitud_adquisiciones" => $id]]);
            if (isset($solicitudQuery->data) && !empty($solicitudQuery->data)) {
                $usu_reg = $solicitudQuery->data[0]->usu_reg ?? 0;
                $usuarioQuery = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["id_usuario" => $usu_reg]]);
                if (isset($usuarioQuery->data) && !empty($usuarioQuery->data) && !empty($usuarioQuery->data[0]->correo)) {
                    $correoDestino = $usuarioQuery->data[0]->correo;
                    $nombreUsuario = $usuarioQuery->data[0]->nombre_completo ?? 'Usuario';
                    $enlaceListado = 'https://secturnet.guanajuato.gob.mx/susi/index.php/Principal/ListaSolicitudAdquisiciones';

                    $emailService->setFrom('noreply@susi.gob.mx', 'SUSI - SECTURI');
                    $emailService->setTo($correoDestino);
                    $emailService->setSubject('Solicitud de Adquisiciones Declinada');
                    $emailService->setMailType('html');
                    $emailService->setMessage("
                        <p>Buen día, <strong>{$nombreUsuario}</strong>:</p>
                        <p>Se le notifica que su solicitud de adquisiciones con ID <strong>{$id}</strong> ha sido <strong>declinada</strong>.</p>
                        <p><strong>Motivo:</strong> {$motivo}</p>
                        <p>Puede ingresar al sistema SUSI para volver a subir la documentación correspondiente.</p>
                        <br>
                        <p>Saludos cordiales,</p>
                        <p><strong>Sistema Unificado SECTURI (SUSI)</strong></p>
                    ");
                    $emailService->setMessage("
                        <p>Buen dÃ­a, <strong>{$nombreUsuario}</strong>:</p>
                        <p>Se le notifica que su solicitud de adquisiciones con ID <strong>{$id}</strong> ha sido <strong>declinada</strong>.</p>
                        <p><strong>Motivo:</strong> {$motivo}</p>
                        <p>Puede consultar mayores detalles ingresando al siguiente enlace:</p>
                        <p><a href='{$enlaceListado}' target='_blank'>{$enlaceListado}</a></p>
                        <br>
                        <p>Saludos cordiales,</p>
                        <p><strong>Sistema Unificado SECTURI (SUSI)</strong></p>
                    ");
                    $emailService->send();
                }
            }

            $response->error = false;
            $response->respuesta = "Solicitud declinada correctamente.";
        } else {
            $response->respuesta = "No se pudo declinar la solicitud.";
        }

        return $this->respond($response);
    }

    public function subirInstrumentoJuridicoAdquisiciones()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $emailService = \Config\Services::email();
        $response = new \stdClass();
        $response->error = true;

        $id = $this->request->getPost('id_solicitud');
        $archivos = $this->request->getFileMultiple('archivos');

        if (!$id || empty($archivos)) {
            $response->respuesta = "Archivos o ID de solicitud no válido.";
            return $this->respond($response);
        }

        $rutasGuardadas = [];


        $solicitudBd = $globals->getTabla(['tabla' => 'solicitud_adquisiciones', 'where' => ['id_solicitud_adquisiciones' => $id]]);
        if (isset($solicitudBd->data) && !empty($solicitudBd->data)) {
            $instrumentosActuales = $solicitudBd->data[0]->instrumento_juridico ?? '';
            if (!empty($instrumentosActuales)) {
                $decoded = json_decode($instrumentosActuales, true);
                if (is_array($decoded)) {
                    $rutasGuardadas = $decoded;
                    } else {
                        $rutasGuardadas[] = $instrumentosActuales;
                    }
                }
            }
        

        foreach ($archivos as $archivo) {
            if ($archivo->isValid() && !$archivo->hasMoved() && strtolower((string) $archivo->getExtension()) === 'pdf') {
                $newName = $archivo->getRandomName();
                $s3Key = $this->uploadFileToS3Storage($archivo->getTempName(), 'adquisiciones', 'instrumentos', $newName);
                if ($s3Key) {
                    $rutasGuardadas[] = $s3Key;
                }
            }
        }

        if (empty($rutasGuardadas)) {
            $response->respuesta = "No se pudieron guardar los archivos o no son PDF válidos.";
            return $this->respond($response);
        }

        $instrumentoJson = json_encode(array_values($rutasGuardadas));
        $dataUpdate = [
            'id_estatus' => 3,
            'instrumento_juridico' => $instrumentoJson,
            'usu_act' => $session->id_usuario ?? 0,
            'fec_act' => date('Y-m-d H:i:s')
        ];


        $res = $globals->saveTabla($dataUpdate, [
            'tabla' => 'solicitud_adquisiciones',
            'editar' => true,
            'idEditar' => ['id_solicitud_adquisiciones' => $id]
        ], [
            'id_user' => $session->id_usuario ?? 0,
            'script' => 'Principal.php/subirInstrumentoJuridicoAdquisiciones'
        ]);

        if (!$res->error) {
            $solicitudQuery = $globals->getTabla(["tabla" => "solicitud_adquisiciones", "where" => ["id_solicitud_adquisiciones" => $id]]);
            if (isset($solicitudQuery->data) && !empty($solicitudQuery->data)) {
                $usu_reg = $solicitudQuery->data[0]->usu_reg ?? 0;
                $usuarioQuery = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["id_usuario" => $usu_reg]]);
                if (isset($usuarioQuery->data) && !empty($usuarioQuery->data) && !empty($usuarioQuery->data[0]->correo)) {
                    $correoDestino = $usuarioQuery->data[0]->correo;
                    $nombreUsuario = $usuarioQuery->data[0]->nombre_completo ?? 'Usuario';
                    $enlaceListado = 'https://secturnet.guanajuato.gob.mx/susi/index.php/Principal/ListaSolicitudAdquisiciones';

                    $emailService->setFrom('noreply@susi.gob.mx', 'SUSI - SECTURI');
                    $emailService->setTo($correoDestino);
                    $emailService->setSubject('Solicitud de Adquisiciones Aprobada - Instrumento Disponible');
                    $emailService->setMailType('html');
                    $emailService->setMessage("
                        <p>Buen día, <strong>{$nombreUsuario}</strong>:</p>
                        <p>El área Jurídica ha autorizado y adjuntado el instrumento jurídico correspondiente a su solicitud de adquisiciones con ID <strong>{$id}</strong>.</p>
                        <p>Puede consultar los documentos ingresando al sistema SUSI.</p>
                        <br>
                        <p>Saludos cordiales,</p>
                        <p><strong>Sistema Unificado SECTURI (SUSI)</strong></p>
                    ");
                    $emailService->setMessage("
                        <p>Buen dÃ­a, <strong>{$nombreUsuario}</strong>:</p>
                        <p>El Ã¡rea JurÃ­dica ha autorizado y adjuntado el instrumento jurÃ­dico correspondiente a su solicitud de adquisiciones con ID <strong>{$id}</strong>.</p>
                        <p>Puede consultar los documentos ingresando al siguiente enlace:</p>
                        <p><a href='{$enlaceListado}' target='_blank'>{$enlaceListado}</a></p>
                        <br>
                        <p>Saludos cordiales,</p>
                        <p><strong>Sistema Unificado SECTURI (SUSI)</strong></p>
                    ");
                    $emailService->send();
                }
            }

            $response->error = false;
            $response->respuesta = "Instrumento jurídico subido y solicitud aprobada.";
        } else {
            $response->respuesta = "No se pudo actualizar la solicitud.";
        }

        return $this->respond($response);
    }

    public function reemplazarInstrumentoAdquisiciones()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'No fue posible reemplazar el instrumento.';

        if (!in_array((int) ($session->id_perfil ?? 0), [1, 7], true)) {
            $response->respuesta = 'No tiene permisos para editar instrumentos.';
            return $this->respond($response);
        }

        $idSolicitud = (int) ($this->request->getPost('id_solicitud') ?? 0);
        $indice = (int) ($this->request->getPost('indice') ?? -1);
        $archivos = $this->request->getFileMultiple('archivos');

        if ($idSolicitud <= 0 || $indice < 0 || empty($archivos)) {
            $response->respuesta = 'Solicitud o instrumento no valido.';
            return $this->respond($response);
        }

        $solicitudQuery = $globals->getTabla([
            'tabla' => 'solicitud_adquisiciones',
            'where' => ['id_solicitud_adquisiciones' => $idSolicitud, 'visible' => 1],
        ]);
        if (empty($solicitudQuery->data)) {
            $response->respuesta = 'No se encontro la solicitud.';
            return $this->respond($response);
        }

        $instrumentoRaw = $solicitudQuery->data[0]->instrumento_juridico ?? '';
        $instrumentos = [];
        if (is_string($instrumentoRaw) && $instrumentoRaw !== '') {
            $decoded = json_decode($instrumentoRaw, true);
            $instrumentos = is_array($decoded) ? $decoded : [$instrumentoRaw];
        } elseif (is_array($instrumentoRaw)) {
            $instrumentos = $instrumentoRaw;
        }

        if (!isset($instrumentos[$indice])) {
            $response->respuesta = 'El instrumento seleccionado no existe.';
            return $this->respond($response);
        }

        if (($indice + count($archivos)) > 4) {
            $response->respuesta = 'La edicion no puede superar 4 instrumentos.';
            return $this->respond($response);
        }

        $rutasNuevas = [];
        foreach ($archivos as $archivo) {
            if (!$archivo->isValid() || $archivo->hasMoved() || strtolower((string) $archivo->getExtension()) !== 'pdf') {
                continue;
            }

            $newName = $archivo->getRandomName();
            $s3Key = $this->uploadFileToS3Storage($archivo->getTempName(), 'adquisiciones', 'instrumentos', $newName);
            if ($s3Key) {
                $rutasNuevas[] = $s3Key;
            }
        }

        if (empty($rutasNuevas)) {
            $response->respuesta = 'No se pudo guardar el nuevo instrumento.';
            return $this->respond($response);
        }

        array_splice($instrumentos, $indice, count($rutasNuevas), $rutasNuevas);
        $instrumentos = array_slice(array_values($instrumentos), 0, 4);

        $res = $globals->saveTabla(
            [
                'instrumento_juridico' => json_encode(array_values($instrumentos)),
                'usu_act' => $session->id_usuario ?? 0,
                'fec_act' => date('Y-m-d H:i:s'),
            ],
            [
                'tabla' => 'solicitud_adquisiciones',
                'editar' => true,
                'idEditar' => ['id_solicitud_adquisiciones' => $idSolicitud],
            ],
            [
                'id_user' => $session->id_usuario ?? 0,
                'script' => 'Principal.php/reemplazarInstrumentoAdquisiciones',
            ]
        );

        if (!$res->error) {
            $response->error = false;
            $response->respuesta = 'Instrumento reemplazado correctamente.';
        } else {
            $response->respuesta = $res->respuesta ?? $response->respuesta;
        }

        return $this->respond($response);
    }

    public function editarSolicitudContrato($id_solicitud = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        
        if (!$id_solicitud) {
             return redirect()->to(base_url('index.php/Principal/ListaSolicitudContrato'));
        }


        // Obtener solicitud
        $solicitud = $globals->getTabla(['tabla' => 'solicitud_contrato', 'where' => ['id_solicitud_contrato' => $id_solicitud, 'visible' => 1]]);
        if (empty($solicitud->data)) {
            return redirect()->to(base_url('index.php/Principal/ListaSolicitudContrato'));
        }

        // Obtener pagos
        $pagos = $globals->getTabla(['tabla' => 'solicitud_contrato_pagos', 'where' => ['id_solicitud_contrato' => $id_solicitud, 'visible' => 1]]);

        $vw_usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        $data['usuario'] = $vw_usuario->data;
        $vw_direccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1]]);
        $data['direccion'] = $vw_direccion->data;

        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];

        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];

        $data['solicitud'] = $solicitud->data[0];
        $solicitudVista = $globals->getTabla(['tabla' => 'vw_solicitud_contrato', 'where' => ['id_solicitud_contrato' => $id_solicitud]]);
        if (!empty($solicitudVista->data)) {
            foreach ((array) $solicitudVista->data[0] as $key => $value) {
                if ((!isset($data['solicitud']->$key) || $data['solicitud']->$key === null || $data['solicitud']->$key === '') && $value !== null && $value !== '') {
                    $data['solicitud']->$key = $value;
                }
            }
        }
        $data['solicitud']->archivo_suficiencia_url = $this->resolveStoredFileUrl($data['solicitud']->archivo_suficiencia ?? null, 'assets/uploads/contratos');
        $archivosSoporte = $globals->getTabla([
            'tabla' => 'solicitud_contrato_archivos',
            'where' => ['id_solicitud_contrato' => $id_solicitud, 'visible' => 1]
        ]);
        $data['archivos_soporte'] = !empty($archivosSoporte->data) ? $archivosSoporte->data : [];
        $data['pagos'] = (!empty($pagos->data)) ? $pagos->data : [];
        $partidasExtra = $globals->getTabla([
            'tabla' => 'solicitud_contrato_partida',
            'where' => ['id_solicitud_contrato' => $id_solicitud, 'visible' => 1]
        ]);
        $data['partidas_extra'] = (!empty($partidasExtra->data)) ? $partidasExtra->data : [];
        $montoTotal = (float) str_replace([',', '$', ' '], '', (string) ($data['solicitud']->monto_total ?? 0));
        if (empty($data['solicitud']->monto_total_texto)) {
            $data['solicitud']->monto_total_texto = strtoupper($this->numeroEnLetras($montoTotal));
        }
        $data['catalogo_firmantes'] = $this->construirCatalogoFirmantes($data['direccion'], $data['usuario']);
        $data['firmas_seleccionadas'] = $this->obtenerFirmasSolicitud($data['solicitud']);

        
        $data['scripts'] = array('inicio');
        $data['edita'] = 1; // Indicador de edicion
        $data['contentView'] = 'personal/vSolicitudContrato';
        $this->_renderView($data);
    }

    public function guardarSolicitudContrato()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al guardar la solicitud';

        $post = $this->request->getPost();
        $id_solicitud_contrato = isset($post['id_solicitud_contrato']) ? $post['id_solicitud_contrato'] : null;
        
        // DEBUG: Uncomment to see data in response
        // $response->post_received = $post;
        // return $this->respond($response); 

        // Manejo de archivo
        $archivo_suficiencia = '';
        if($file = $this->request->getFile('archivo_suficiencia')) {
             if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $uploadedKey = $this->uploadFileToS3Storage($file->getTempName(), 'contratos', 'suficiencia', $newName);
                if ($uploadedKey) {
                    $archivo_suficiencia = $uploadedKey;
                } else {
                    $response->respuesta = 'No fue posible guardar el archivo de suficiencia en AWS S3.';
                    return $this->respond($response);
                }
             }
        }

        if(empty($post['monto_total'])){
            $response->respuesta = 'El monto total es requerido.';
            return $this->respond($response);
        }
        if(empty($post['proyecto'])){
            $response->respuesta = 'El proyecto es requerido.';
            return $this->respond($response);
        }
        if(empty($post['partida'])){
            $response->respuesta = 'La partida es requerida.';
            return $this->respond($response);
        }
        if(empty($post['clave_estandarizada'])){
            $response->respuesta = 'La clave estandarizada es requerida.';
            return $this->respond($response);
        }
        if(empty($post['garantia'])){
            $response->respuesta = 'La garantia es requerida.';
            return $this->respond($response);
        }
        if(empty($post['monto_garantia'])){
            $response->respuesta = 'El monto de la garantia es requerido.';
            return $this->respond($response);
        }
        if(empty($post['responsable_proyecto'])){
            $response->respuesta = 'El responsable del proyecto es requerido.';
            return $this->respond($response);
        }
        if(empty($post['responsable_seguimiento'])){
            $response->respuesta = 'El responsable del seguimiento es requerido.';
            return $this->respond($response);
        }
        if(empty($post['enlace_comunicaciones'])){
            $response->respuesta = 'El enlace de comunicaciones es requerido.';
            return $this->respond($response);
        }
        $tienePartidasExtra = isset($post['partidas_extra'])
            && is_array($post['partidas_extra'])
            && count(array_slice($post['partidas_extra'], 0, 3)) > 0;
        $montoPartidaInicial = (float) str_replace([',', '$', ' '], '', (string) ($post['monto'] ?? 0));

        if ($tienePartidasExtra && $montoPartidaInicial <= 0) {
            $response->respuesta = 'Completa el monto de la partida inicial.';
            return $this->respond($response);
        }

        if ($tienePartidasExtra) {
            foreach (array_slice($post['partidas_extra'], 0, 3) as $partidaExtra) {
                $idProyectoExtra = trim((string) ($partidaExtra['id_proyecto'] ?? ''));
                $idPartidaExtra = trim((string) ($partidaExtra['id_partida'] ?? ''));
                $claveExtra = trim((string) ($partidaExtra['clave'] ?? ''));
                $montoExtra = (float) str_replace([',', '$', ' '], '', (string) ($partidaExtra['monto'] ?? 0));

                if ($idProyectoExtra === '' && $idPartidaExtra === '' && $claveExtra === '' && $montoExtra <= 0) {
                    continue;
                }
                if ($idProyectoExtra === '' || $idPartidaExtra === '' || $claveExtra === '' || $montoExtra <= 0) {
                    $response->respuesta = 'Completa proyecto, partida, clave y monto en las partidas adicionales.';
                    return $this->respond($response);
                }
            }
        }
        $montoTotal = (float) str_replace([',', '$', ' '], '', (string) ($post['monto_total'] ?? 0));
        if ($montoTotal <= 0) {
            $response->respuesta = 'El monto total es requerido.';
            return $this->respond($response);
        }

        $montoSinImpuesto = round($montoTotal / 1.16, 2);
        $montoTotalTexto = trim((string) ($post['monto_total_texto'] ?? ''));
        if ($montoTotalTexto === '') {
            $montoTotalTexto = strtoupper($this->numeroEnLetras($montoTotal));
        }
        $montoSinImpuestoTexto = strtoupper($this->numeroEnLetras($montoSinImpuesto));

        $montoGarantia = (float) str_replace([',', '$', ' '], '', (string) ($post['monto_garantia'] ?? 0));
        $montoGarantiaTexto = trim((string) ($post['monto_garantia_texto'] ?? ''));
        if ($montoGarantiaTexto === '' && $montoGarantia > 0) {
            $montoGarantiaTexto = strtoupper($this->numeroEnLetras($montoGarantia));
        }
        $proveedorCorreo = trim((string) ($post['proveedor_correo'] ?? ''));
        $proveedorSeguimiento = trim((string) ($post['proveedor_seguimiento'] ?? ''));

        // Datos principales
        $dataInsert = [
            'responsable_proyecto' => $post['responsable_proyecto'],
            'responsable_seguimiento' => $post['responsable_seguimiento'],
            'enlace_comunicaciones' => $post['enlace_comunicaciones'],
            'proyecto' => $post['proyecto'],
            'partida' => $post['partida'],
            'clave_estandarizada' => $post['clave_estandarizada'],
            'monto' => $tienePartidasExtra ? $montoPartidaInicial : null,
            'monto_sin_impuesto' => $montoSinImpuesto,
            'monto_sin_impuesto_texto' => $montoSinImpuestoTexto,
            'monto_total' => $post['monto_total'],
            'monto_total_texto' => $montoTotalTexto,
            'garantia' => $post['garantia'],
            'monto_garantia' => $post['monto_garantia'] ?? null,
            'monto_garantia_texto' => $montoGarantiaTexto,
            'proveedor_seguimiento' => $proveedorSeguimiento,
            'objeto_contrato' => $post['objeto_contrato'],
            'fecha_inicio' => $post['fecha_inicio'],
            'fecha_termino' => $post['fecha_termino'],
            'proveedor_nombre' => $post['proveedor_nombre'],
            'proveedor_domicilio' => $post['proveedor_domicilio'],
            'proveedor_rfc' => $post['proveedor_rfc'],
            'proveedor_cedula' => $post['proveedor_cedula'],
            'proveedor_representante' => $post['proveedor_representante'],
            'proveedor_correo' => $proveedorCorreo,
            'no_delegatorio_1' => null,
            'no_delegatorio_2' => null,
            'no_delegatorio_3' => null,
           // 'correo_responsable' => $post['correo_responsable'],
        ];

        $delegatorios = isset($post['no_delegatorio']) && is_array($post['no_delegatorio']) ? $post['no_delegatorio'] : [];
        $delegatoriosActivos = isset($post['usar_no_delegatorio']) && is_array($post['usar_no_delegatorio']) ? $post['usar_no_delegatorio'] : [];
        foreach ([1, 2, 3] as $indice) {
            $key = (string) ($indice - 1);
            if (!empty($delegatoriosActivos[$key])) {
                $valorDelegatorio = trim((string) ($delegatorios[$key] ?? ''));
                $dataInsert['no_delegatorio_' . $indice] = $valorDelegatorio !== '' ? $valorDelegatorio : null;
            }
        }

        if (!empty($archivo_suficiencia)) {
            $dataInsert['archivo_suficiencia'] = $archivo_suficiencia;
        }

        // Configuración para guardar
        $dataConfig = ["tabla" => "solicitud_contrato", "editar" => false];
        $script = 'Agregar.php/guardaSolicitudContrato';

        if ($id_solicitud_contrato) {
             $dataInsert['id_estatus'] = 1;
            $dataConfig = [
                "tabla" => "solicitud_contrato", 
                "editar" => true, 
                "idEditar" => ['id_solicitud_contrato' => $id_solicitud_contrato]
            ];
            $script = 'Principal.php/editarSolicitudContrato';
            
            // Campos de auditoría para edición
             //$dataInsert['usu_act'] = $session->id_usuario;
             //$dataInsert['fec_act'] = date('Y-m-d H:i:s');
        } else {
             // Campos de auditoría para creación
             $dataInsert['usu_reg'] = $session->id_usuario;
             $dataInsert['fec_reg'] = date('Y-m-d H:i:s');
        }

        $dataBitacora = ['id_user' => $session->id_usuario, 'script' => $script];
        $res = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        if (!$res->error) {
            $id_solicitud = $id_solicitud_contrato ? $id_solicitud_contrato : $res->idRegistro;
            if (isset($post['partidas_extra']) && is_array($post['partidas_extra'])) {
                foreach (array_slice($post['partidas_extra'], 0, 3) as $partidaExtra) {
                    $idProyectoExtra = trim((string) ($partidaExtra['id_proyecto'] ?? ''));
                    $idPartidaExtra = trim((string) ($partidaExtra['id_partida'] ?? ''));
                    $claveExtra = trim((string) ($partidaExtra['clave'] ?? ''));
                    $montoExtra = (float) str_replace([',', '$', ' '], '', (string) ($partidaExtra['monto'] ?? 0));
                    $idPartidaContrato = trim((string) ($partidaExtra['id_solicitud_contrato_partida'] ?? ''));

                    if ($idProyectoExtra === '' && $idPartidaExtra === '' && $claveExtra === '' && $montoExtra <= 0) {
                        continue;
                    }

                    $dataPartidaExtra = [
                        'id_solicitud_contrato' => $id_solicitud,
                        'id_proyecto' => $idProyectoExtra !== '' ? $idProyectoExtra : null,
                        'id_partida' => $idPartidaExtra !== '' ? $idPartidaExtra : null,
                        'clave' => $claveExtra,
                        'monto' => $montoExtra,
                        'visible' => 1
                    ];
                    $dataConfigPartida = [
                        'tabla' => 'solicitud_contrato_partida',
                        'editar' => false
                    ];

                    if ($idPartidaContrato !== '') {
                        $dataConfigPartida = [
                            'tabla' => 'solicitud_contrato_partida',
                            'editar' => true,
                            'idEditar' => ['id_solicitud_contrato_partida' => $idPartidaContrato]
                        ];
                    }

                    $resPartida = $globals->saveTabla($dataPartidaExtra, $dataConfigPartida, [
                        'id_user' => $session->id_usuario,
                        'script' => 'Principal.php/guardarSolicitudContratoPartida'
                    ]);
                    if ($resPartida->error) {
                        $response->respuesta = $resPartida->respuesta;
                        return $this->respond($response);
                    }
                }
            }
            $this->guardarFirmasSolicitud(
                $globals,
                'solicitud_contrato',
                'id_solicitud_contrato',
                (int) $id_solicitud,
                $post['firmas'] ?? [],
                (int) ($session->id_usuario ?? 0),
                'Principal.php/guardarSolicitudContratoFirmas'
            );
            
            // Si es edición, desactivar pagos anteriores
            if ($id_solicitud_contrato) {
                $globals->saveTabla(
                    ['visible' => 0], 
                    [
                        "tabla" => "solicitud_contrato_pagos", 
                        "editar" => true, 
                        "idEditar" => ['id_solicitud_contrato' => $id_solicitud_contrato]
                    ], 
                    ['id_user' => $session->id_usuario, 'script' => 'Principal.php/eliminarPagosAntiguos']
                );
            }

            // Guardar Pagos Nuevos
           
            if(isset($post['pagos']) && is_array($post['pagos'])){
                foreach($post['pagos'] as $pago){
                    $dataPago = [
                        'id_solicitud_contrato' => $id_solicitud,
                        'numero_pago' => $pago['numero'],
                        'monto' => $pago['monto'],
                        'fecha' => $pago['fecha'],
                        'entregable' => $pago['entregable'],
                        'visible' => 1
                    ];
                    $res = $globals->saveTabla($dataPago, ["tabla" => "solicitud_contrato_pagos", "editar" => false], ["id_user" => $session->id_usuario, 'script' => 'Principal.php/guardarSolicitudContrato']);
                }
            }
          
            $response->error = false;
            $response->respuesta = 'Solicitud guardada correctamente';
        } else {
            $response->respuesta = $res->respuesta;
        }

        return $this->respond($response);
    }

    public function eliminarSolicitudContrato()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        
        $id_solicitud = $this->request->getPost('id_solicitud');
        
        if($id_solicitud){
            $dataUpdate = ['visible' => 0];
            $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Principal.php/eliminarSolicitudContrato'];
            $res = $globals->saveTabla($dataUpdate, ["tabla" => "solicitud_contrato", "editar" => true, "idEditar" => ['id_solicitud_contrato' => $id_solicitud]], $dataBitacora);
            
            if(!$res->error){
                $response->error = false;
                $response->respuesta = 'Solicitud eliminada correctamente';
            } else {
                $response->respuesta = $res->respuesta;
            }
        } else {
            $response->respuesta = 'ID no válido';
        }
        
        return $this->respond($response);
    }

    public function enviarSolicitudContrato()
    {
         $session = \Config\Services::session();
         $globals = new Mglobal;
         $response = new \stdClass();
         $response->error = true;
         
         $id_solicitud = $this->request->getPost('id_solicitud');
         // Logica de envío de correo (Placeholder)

         
         if($id_solicitud){
             $response->error = false;
             $response->respuesta = 'Correo enviado (Simulado)';
         } else {
             $response->respuesta = 'ID no válido';
         }
         
        return $this->respond($response);
    }

    public function buscarProveedorContrato()
    {
        $globals = new Mglobal;
        $term = trim((string) $this->request->getGet('q'));
        $results = [];

        if ($term !== '') {
            $proveedores = $globals->getTabla([
                'tabla' => 'proveedor',
                'where' => ['visible' => 1],
                'orlike' => [
                    'razon_social' => $term,
                    'rfc' => $term,
                    'no_proveedor' => $term,
                ],
                'limit' => 20,
            ]);

            if (!empty($proveedores->data)) {
                foreach ($proveedores->data as $proveedor) {
                    $razonSocial = (string) ($proveedor->razon_social ?? '');
                    $rfc = (string) ($proveedor->rfc ?? '');
                    $noProveedor = (string) ($proveedor->no_proveedor ?? '');
                    $results[] = [
                        'id' => $proveedor->id_proveedor,
                        'text' => trim($razonSocial . ' - ' . $rfc . ' - ' . $noProveedor),
                        'razon_social' => $razonSocial,
                        'rfc' => $rfc,
                        'no_proveedor' => $noProveedor,
                    ];
                }
            }
        }

        return $this->response->setJSON(['results' => $results]);
    }
    
    public function verSolicitudContratoPDF($id = null)
    {
        if(!$id){
            echo "ID no válido"; return;
        }

        $session = \Config\Services::session();
        $globals = new Mglobal;
        
        // Cargar datos
        $solicitud = $globals->getTabla(['tabla' => 'vw_solicitud_contrato', 'where' => ['id_solicitud_contrato' => $id]]);
        $solicitudBase = $globals->getTabla(['tabla' => 'solicitud_contrato', 'where' => ['id_solicitud_contrato' => $id, 'visible' => 1]]);
        $pagos = $globals->getTabla(['tabla' => 'solicitud_contrato_pagos', 'where' => ['id_solicitud_contrato' => $id, 'visible' => 1]]);
        $partidasExtra = $globals->getTabla(['tabla' => 'solicitud_contrato_partida', 'where' => ['id_solicitud_contrato' => $id, 'visible' => 1]]);
        
        if(empty($solicitud->data)){
            echo "Solicitud no encontrada"; return;
        }
       
        $data['solicitud'] = $this->normalizeUtf8Value($solicitud->data[0]);
       // var_dump($data['solicitud']); die();
        if (!empty($solicitudBase->data)) {
            foreach ((array) $solicitudBase->data[0] as $key => $value) {
                if (!isset($data['solicitud']->$key) || $data['solicitud']->$key === null || $data['solicitud']->$key === '') {
                    $data['solicitud']->$key = $this->normalizeUtf8Value($value);
                }
            }
        }
        $nombreProyectoConPuesto = $this->obtenerNombreConPuesto($globals, $data['solicitud']->responsable_proyecto ?? null, true);
        $nombreSeguimientoConPuesto = $this->obtenerNombreConPuesto($globals, $data['solicitud']->responsable_seguimiento ?? null, false);
        $nombreEnlaceConPuesto = $this->obtenerNombreConPuesto($globals, $data['solicitud']->enlace_comunicaciones ?? null, false);
        $data['solicitud']->nombre_proyecto_puesto = $nombreProyectoConPuesto !== '' ? $nombreProyectoConPuesto : ($data['solicitud']->nombre_proyecto ?? '');
        $data['solicitud']->nombre_seguimiento_puesto = $nombreSeguimientoConPuesto !== '' ? $nombreSeguimientoConPuesto : ($data['solicitud']->nombre_seguimiento ?? '');
        $data['solicitud']->nombre_enlace_puesto = $nombreEnlaceConPuesto !== '' ? $nombreEnlaceConPuesto : ($data['solicitud']->nombre_enlace ?? '');
        $data['firmas_pdf'] = $this->obtenerFirmasSolicitudDetalle($globals, $data['solicitud']);
        foreach ($data['firmas_pdf'] as $indice => $firma) {
            $firma->no_delegatorio = $data['solicitud']->{'no_delegatorio_' . ($indice + 1)} ?? '';
        }
        $data['pagos'] = $this->normalizeUtf8Value((!empty($pagos->data)) ? $pagos->data : []);
        $data['partidas_extra'] = $this->normalizeUtf8Value((!empty($partidasExtra->data)) ? $partidasExtra->data : []);
        if (!empty($data['partidas_extra'])) {
            $catProyectoPdf = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
            $catPartidaPdf = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
            $proyectosPdf = [];
            $partidasPdf = [];
            foreach ((!empty($catProyectoPdf->data) ? $catProyectoPdf->data : []) as $proyectoPdf) {
                $proyectosPdf[(string) $proyectoPdf->id_proyecto] = $proyectoPdf->proyecto;
            }
            foreach ((!empty($catPartidaPdf->data) ? $catPartidaPdf->data : []) as $partidaPdf) {
                $partidasPdf[(string) $partidaPdf->id_partida] = $partidaPdf->cuenta_cable;
            }
            foreach ($data['partidas_extra'] as $partidaExtraPdf) {
                $partidaExtraPdf->dsc_proyecto = $proyectosPdf[(string) ($partidaExtraPdf->id_proyecto ?? '')] ?? '';
                $partidaExtraPdf->cuenta_cable = $partidasPdf[(string) ($partidaExtraPdf->id_partida ?? '')] ?? '';
                $montoPartidaExtra = (float) str_replace([',', '$', ' '], '', (string) ($partidaExtraPdf->monto ?? 0));
                $partidaExtraPdf->monto_formateado = '$' . number_format($montoPartidaExtra, 2, '.', ',');
            }
        }
        $montoPartidaInicial = (float) str_replace([',', '$', ' '], '', (string) ($data['solicitud']->monto ?? 0));
        if ($montoPartidaInicial > 0) {
            $data['solicitud']->monto_partida_formateado = '$' . number_format($montoPartidaInicial, 2, '.', ',');
        }
        $montoTotal = (float) str_replace([',', '$', ' '], '', (string) ($data['solicitud']->monto_total ?? 0));
        $data['solicitud']->monto_total_formateado = '$' . number_format($montoTotal, 2, '.', ',');
        if (empty($data['solicitud']->monto_total_texto)) {
            $data['solicitud']->monto_total_texto = strtoupper($this->numeroEnLetras($montoTotal));
        }
        $montoSinImpuesto = (float) str_replace([',', '$', ' '], '', (string) ($data['solicitud']->monto_sin_impuesto ?? 0));
        if ($montoSinImpuesto > 0) {
            $data['solicitud']->monto_sin_impuesto_formateado = '$' . number_format($montoSinImpuesto, 2, '.', ',');
            if (empty($data['solicitud']->monto_sin_impuesto_texto)) {
                $data['solicitud']->monto_sin_impuesto_texto = strtoupper($this->numeroEnLetras($montoSinImpuesto));
            }
        }
        $montoGarantia = (float) str_replace([',', '$', ' '], '', (string) ($data['solicitud']->monto_garantia ?? 0));
        $data['solicitud']->monto_garantia_formateado = '$' . number_format($montoGarantia, 2, '.', ',');
        if (empty($data['solicitud']->monto_garantia_texto) && $montoGarantia > 0) {
            $data['solicitud']->monto_garantia_texto = strtoupper($this->numeroEnLetras($montoGarantia));
        }
        if (!empty($data['pagos'])) {
            foreach ($data['pagos'] as $pago) {
                $montoPago = (float) str_replace([',', '$', ' '], '', (string) ($pago->monto ?? 0));
                $pago->monto_formateado = '$' . number_format($montoPago, 2, '.', ',');
            }
        }
        
        // Reutilizamos la vista de formulario pero en modo lectura o creamos una vista optimizada para impresión
        // Por ahora usaré una vista simple para PDF
        $html = view('personal/vPdfSolicitudContrato', $data);
        $html = $this->normalizeUtf8Value($html);
        $html = $this->cleanMpdfHtml($html);
        
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'format' => 'Letter'
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Solicitud_Contrato_' . $id . '.pdf', 'I');
        exit();
    }

    public function subirArchivosSolicitud()
    {
        $session = \Config\Services::session();
        $id_solicitud = $this->request->getPost('id_solicitud');
        $documentos_seleccionados = $this->request->getPost('documentos');

        if (!$id_solicitud || empty($documentos_seleccionados)) {
            return redirect()->to(base_url('index.php/Principal/ListaSolicitudContrato'));
        }

        $data['id_solicitud'] = $id_solicitud;
        $data['documentos'] = $documentos_seleccionados;
        
        $data['contentView'] = 'secciones/vSubirArchivosSolicitud';
        $this->_renderView($data);
    }

    private function obtenerDocumentosContratoSolicitud(): array
    {
        return [
            1 => 'Anexo Técnico (Términos de referencia)',
            '2a' => 'Investigación de mercado (Cotizaciones y consulta PEI)',
            '2b' => 'Análisis de ofertas turísticas',
            '2c' => 'Argumentación técnica',
            '3a' => 'Validación de partida restringida (SF)',
            '3b' => 'Verificación de alineación de información estratégica (DGIT)',
            '3c' => 'Suficiencia presupuestal (R3)',
            '3d' => 'Validación DGTIT/CGCS u otra',
            4 => 'Justificación',
            5 => 'Propuesta técnico-económica (Anexo)',
            6 => 'Aviso de privacidad integral',
            7 => 'Cédula de registro en el Padrón de Proveedores',
            8 => 'Escritura Constitutiva',
            9 => 'Documento de representación legal (Poder)',
            10 => 'Identificación oficial vigente',
            11 => 'Constancia de situación fiscal',
            12 => 'Comprobante de domicilio',
            '13a' => 'Opinión de cumplimiento de obligaciones fiscales',
            '13b' => 'Manifiesto bajo protesta de cumplimiento fiscal',
            14 => 'Manifiesto de no impedimento para contratar',
            15 => 'Carta de declaración de intereses',
            16 => 'Manifiesto de contar con infraestructura',
        ];
    }

    public function descargarChecklistSolicitud($id_solicitud)
    {
        $globals = new Mglobal;
        $documentosContrato = $this->obtenerDocumentosContratoSolicitud();

        $archivos = $globals->getTabla([
            'tabla' => 'solicitud_contrato_archivos',
            'where' => ['id_solicitud_contrato' => $id_solicitud, 'visible' => 1]
        ]);

        $archivosAceptadosOcultos = $globals->getTabla([
            'tabla' => 'solicitud_contrato_archivos',
            'where' => ['id_solicitud_contrato' => $id_solicitud, 'visible' => 0, 'id_estatus' => 3]
        ]);

        $archivosChecklist = (!empty($archivos->data)) ? $archivos->data : [];
        if (!empty($archivosAceptadosOcultos->data)) {
            foreach ($archivosAceptadosOcultos->data as $archivoAceptado) {
                $archivosChecklist[] = $archivoAceptado;
            }
        }

        $documentosCapturados = [];
        foreach ($archivosChecklist as $archivo) {
            $claveDocumento = (string) ($archivo->clave_documento ?? '');
            if ($claveDocumento === '') {
                continue;
            }

            $documentosCapturados[$claveDocumento] = $documentosContrato[$claveDocumento]
                ?? $documentosContrato[(int) $claveDocumento]
                ?? ($archivo->nombre_documento ?? ('Documento ' . $claveDocumento));
        }

        $filas = '';
        foreach ($documentosCapturados as $nombreDocumento) {
            $filas .= '<tr>
                <td class="check">X</td>
                <td>' . htmlspecialchars((string) $nombreDocumento, ENT_QUOTES, 'UTF-8') . '</td>
            </tr>';
        }

        if ($filas === '') {
            $filas = '<tr><td colspan="2" class="empty">No se encontraron documentos capturados.</td></tr>';
        }

        $html = '
            <style>
                body { font-family: sans-serif; font-size: 11pt; color: #222; }
                h2 { text-align: center; margin-bottom: 4px; }
                .subtitle { text-align: center; margin-bottom: 20px; color: #555; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #555; padding: 8px; }
                th { background: #efefef; text-align: left; }
                .check { width: 45px; text-align: center; font-weight: bold; }
                .empty { text-align: center; color: #777; }
            </style>
            <h2>Check List de Documentación Capturada</h2>
            <div class="subtitle">Solicitud de contrato #' . htmlspecialchars((string) $id_solicitud, ENT_QUOTES, 'UTF-8') . '</div>
            <table>
                <thead>
                    <tr>
                        <th class="check">Check</th>
                        <th>Documento</th>
                    </tr>
                </thead>
                <tbody>' . $filas . '</tbody>
            </table>';

        $html = $this->cleanMpdfHtml($html);
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 12,
            'margin_left' => 12,
            'margin_right' => 12,
            'margin_bottom' => 12,
            'format' => 'Letter'
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('CheckList_Solicitud_Contrato_' . $id_solicitud . '.pdf', 'D');
        exit();
    }

    public function verArchivosSolicitud($id_solicitud)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        
        $archivos = $globals->getTabla([
            'tabla' => 'solicitud_contrato_archivos',
            'where' => ['id_solicitud_contrato' => $id_solicitud, 'visible' => 1]
        ]);

        $archivosAceptadosOcultos = $globals->getTabla([
            'tabla' => 'solicitud_contrato_archivos',
            'where' => ['id_solicitud_contrato' => $id_solicitud, 'visible' => 0, 'id_estatus' => 3]
        ]);
        
        if (!empty($archivosAceptadosOcultos->data)) {
            $archivos->data = $archivos->data ?? [];
            $idsArchivosVisibles = [];
            foreach ($archivos->data as $archivoVisible) {
                $idsArchivosVisibles[(int) ($archivoVisible->id_solicitud_contrato_archivo ?? 0)] = true;
            }

            foreach ($archivosAceptadosOcultos->data as $archivoAceptado) {
                $idArchivoAceptado = (int) ($archivoAceptado->id_solicitud_contrato_archivo ?? 0);
                if ($idArchivoAceptado > 0 && empty($idsArchivosVisibles[$idArchivoAceptado])) {
                    $archivoAceptado->visible = 1;
                    $archivos->data[] = $archivoAceptado;
                }
            }
        }
       
        if (!empty($archivos->data)) {
         foreach ($archivos->data as &$archivo) {
                $archivo->url_descarga = $this->resolveStoredFilePreviewUrl($archivo->nombre_archivo ?? null, 'assets/uploads/contratos');
              // var_dump($archivo->url_descarga);            
            }
        }
       //  die();
        $data['id_solicitud'] = $id_solicitud;
        $data['archivos'] = (!empty($archivos->data)) ? $archivos->data : [];
        $data['modulo_archivos'] = 'contrato';
        $data['scripts'] = array(); // No scripts needed for basic list
        $data['contentView'] = 'secciones/vVerArchivosSolicitud';
        
        $this->_renderView($data);
    }
    public function EditarArchivo()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;

        $modulo = (string) ($this->request->getPost('modulo') ?? 'contrato');
        $configModulo = $this->obtenerConfiguracionModuloArchivos($modulo);
        $id_solicitud_contrato_archivo = $this->request->getPost('id_archivo') ?? $this->request->getPost('id_solicitud_contrato_archivo');
        $archivoNuevo = $this->request->getFile('archivo');
        
        if ((int) ($session->get('id_perfil') ?? 0) === 7) {
            $response->respuesta = "No tiene permisos para editar archivos.";
            return $this->respond($response);
        }

        if ($configModulo === null || !$id_solicitud_contrato_archivo) {
            $response->respuesta = "ID de archivo no válido.";
            return $this->respond($response);
        }

        if (!$archivoNuevo || !$archivoNuevo->isValid() || $archivoNuevo->hasMoved()) {
            $response->respuesta = "Debe seleccionar un archivo válido.";
            return $this->respond($response);
        }

        if (strtolower((string) $archivoNuevo->getExtension()) !== 'pdf') {
            $response->respuesta = "Solo se permiten archivos PDF.";
            return $this->respond($response);
        }

        $campoIdArchivo = $this->obtenerCampoIdArchivoModulo($globals, $configModulo);
        $archivoActualQuery = $globals->getTabla([
            'tabla' => $configModulo['tabla'],
            'where' => [$campoIdArchivo => $id_solicitud_contrato_archivo, 'visible' => 1]
        ]);

        if (empty($archivoActualQuery->data)) {
            $response->respuesta = "No se encontró el archivo a editar.";
            return $this->respond($response);
        }

        $archivoActual = $archivoActualQuery->data[0];
        $idSolicitudPadre = $archivoActual->{$configModulo['id_solicitud_campo']} ?? null;

        if ((int) ($archivoActual->id_estatus ?? 0) !== 2) {
            $response->respuesta = "Solo se pueden editar archivos declinados.";
            return $this->respond($response);
        }

        $extension = strtolower((string) $archivoNuevo->getExtension());
        $nombreOriginal = (string) $archivoNuevo->getClientName();
        $nombreGuardado = 'edicion_' . $id_solicitud_contrato_archivo . '_' . time() . '_' . substr(md5(uniqid((string) $id_solicitud_contrato_archivo, true)), 0, 8) . '.' . $extension;
        $s3Key = $this->uploadFileToS3Storage($archivoNuevo->getTempName(), $configModulo['storage_modulo'], 'documentos', $nombreGuardado);

        if (empty($s3Key)) {
            $response->respuesta = "No fue posible guardar el archivo editado en AWS S3.";
            return $this->respond($response);
        }

        $columnasArchivo = $this->obtenerColumnasTablaServicio($globals, $configModulo['tabla']);
        $dataUpdate = [
            'id_estatus' => 4,
            'nombre_archivo' => $s3Key,
        ];

        if (in_array('tipo', $columnasArchivo, true)) {
            $dataUpdate['tipo'] = $extension;
        }

        foreach (['ruta_s3', 'ruta_archivo', 'archivo_ruta', 'ruta_relativa'] as $columnaRuta) {
            if (in_array($columnaRuta, $columnasArchivo, true)) {
                $dataUpdate[$columnaRuta] = $s3Key;
            }
        }

        foreach (['nombre_original', 'nombre_real', 'archivo_nombre', 'nombre_archivo_original'] as $columnaNombre) {
            if (in_array($columnaNombre, $columnasArchivo, true)) {
                $dataUpdate[$columnaNombre] = $nombreOriginal;
            }
        }

        foreach (['usu_act', 'id_usuario_act'] as $columnaUsuarioAct) {
            if (in_array($columnaUsuarioAct, $columnasArchivo, true)) {
                $dataUpdate[$columnaUsuarioAct] = $session->id_usuario ?? 0;
            }
        }

        foreach (['fec_act', 'fecha_actualizacion'] as $columnaFechaAct) {
            if (in_array($columnaFechaAct, $columnasArchivo, true)) {
                $dataUpdate[$columnaFechaAct] = date('Y-m-d H:i:s');
            }
        }

        $res = $globals->saveTabla(
            $dataUpdate,
            ["tabla" => $configModulo['tabla'], "editar" => true, "idEditar" => [$campoIdArchivo => $id_solicitud_contrato_archivo]],
            ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/EditarArchivo']
        );

        if (!$res->error && !empty($idSolicitudPadre)) {
            $this->actualizarEstatusSolicitudArchivo($globals, $configModulo, (int) $idSolicitudPadre, 4, 'Principal.php/EditarArchivo');
        }

        if ($res->error) {
            $response->respuesta = $res->respuesta ?? "No fue posible actualizar el archivo editado.";
            return $this->respond($response);
        }

        $response->error = false;
        $response->respuesta = "Archivo editado correctamente.";
        return $this->respond($response);
    }
    public function DeclinarArchivo()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;

        $modulo = (string) ($this->request->getPost('modulo') ?? 'contrato');
        $configModulo = $this->obtenerConfiguracionModuloArchivos($modulo);
        $id_solicitud_contrato_archivo = $this->request->getPost('id_archivo') ?? $this->request->getPost('id_solicitud_contrato_archivo');

        if (!in_array((int) ($session->get('id_perfil') ?? 0), [1, 7], true)) {
            $response->respuesta = "No tiene permisos para declinar archivos.";
            return $this->respond($response);
        }

        if ($configModulo === null || !$id_solicitud_contrato_archivo) {
            $response->respuesta = "ID de archivo no valido.";
            return $this->respond($response);
        }

        $campoIdArchivo = $this->obtenerCampoIdArchivoModulo($globals, $configModulo);
        $archivoActualQuery = $globals->getTabla([
            'tabla' => $configModulo['tabla'],
            'where' => [$campoIdArchivo => $id_solicitud_contrato_archivo, 'visible' => 1]
        ]);

        if (empty($archivoActualQuery->data)) {
            $response->respuesta = "No se encontro el archivo a declinar.";
            return $this->respond($response);
        }

        $archivoActual = $archivoActualQuery->data[0];
        $idSolicitudPadre = (int) ($archivoActual->{$configModulo['id_solicitud_campo']} ?? 0);

        $res = $globals->saveTabla(
            ['id_estatus' => 2],
            ["tabla" => $configModulo['tabla'], "editar" => true, "idEditar" => [$campoIdArchivo => $id_solicitud_contrato_archivo]],
            ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/DeclinarArchivo']
        );

        if ($res->error) {
            $response->respuesta = $res->respuesta ?? "No fue posible declinar el archivo.";
            return $this->respond($response);
        }

        $response->error = false;
        $response->respuesta = "Archivo declinado correctamente.";
        return $this->respond($response);
    }

    public function AceptarArchivo()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;

        $modulo = (string) ($this->request->getPost('modulo') ?? 'contrato');
        $configModulo = $this->obtenerConfiguracionModuloArchivos($modulo);
        $id_solicitud_contrato_archivo = $this->request->getPost('id_archivo') ?? $this->request->getPost('id_solicitud_contrato_archivo');

        if (!in_array((int) ($session->get('id_perfil') ?? 0), [1, 7], true)) {
            $response->respuesta = "No tiene permisos para aceptar archivos.";
            return $this->respond($response);
        }

        if ($configModulo === null || !$id_solicitud_contrato_archivo) {
            $response->respuesta = "ID de archivo no valido.";
            return $this->respond($response);
        }

        $campoIdArchivo = $this->obtenerCampoIdArchivoModulo($globals, $configModulo);
        $archivoActualQuery = $globals->getTabla([
            'tabla' => $configModulo['tabla'],
            'where' => [$campoIdArchivo => $id_solicitud_contrato_archivo, 'visible' => 1]
        ]);

        if (empty($archivoActualQuery->data)) {
            $response->respuesta = "No se encontro el archivo a aceptar.";
            return $this->respond($response);
        }

        $archivoActual = $archivoActualQuery->data[0];
        $idSolicitudPadre = (int) ($archivoActual->{$configModulo['id_solicitud_campo']} ?? 0);

        $res = $globals->saveTabla(
            ["id_estatus" => 3],
            ["tabla" => $configModulo['tabla'], "editar" => true, "idEditar" => [$campoIdArchivo => $id_solicitud_contrato_archivo]],
            ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/AceptarArchivo']
        );

        if ($res->error) {
            $response->respuesta = $res->respuesta ?? "No fue posible aceptar el archivo.";
            return $this->respond($response);
        }

        $response->error = false;
        $response->respuesta = "Archivo aceptado correctamente.";
        return $this->respond($response);
    }
    public function guardarArchivosSolicitud()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;

        $id_solicitud = $this->request->getPost('id_solicitud');
        
        if (!$id_solicitud) {
            $response->respuesta = "ID de solicitud no válido.";
            return $this->respond($response);
        }

        $count = 0;
        $errores = 0;
        
        // Verificar si hay archivos enviados
        if (isset($_FILES['archivos']) && is_array($_FILES['archivos']['name'])) {
            $guardarArchivoContrato = function ($key, $originalName, $tmpName, $error, $indice = 0) use ($globals, $session, $id_solicitud, &$response, &$count, &$errores) {
                if (empty($originalName)) {
                    return;
                }

                if ($error !== UPLOAD_ERR_OK) {
                    $errores++;
                    return;
                }

                $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                $newName = $id_solicitud . '_' . $key . '_' . time() . '_' . $indice . '.' . $ext;
                $s3Key = $this->uploadFileToS3Storage($tmpName, 'contratos', 'documentos', $newName);

                if ($s3Key) {
                    $dataInsert = [
                        'id_solicitud_contrato' => $id_solicitud,
                        'clave_documento' => $key,
                        'nombre_archivo' => $s3Key,
                        'tipo' => $ext,
                        'usu_reg' => $session->id_usuario ?? 0,
                        'fec_reg' => date('Y-m-d H:i:s'),
                        'visible' => 1
                    ];

                    $res = $globals->saveTabla($dataInsert, ["tabla" => "solicitud_contrato_archivos", "editar" => false], ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/guardarArchivosSolicitud']);
                    $response->respuesta = $res->respuesta;
                    $response->error = $res->error;
                    $globals->saveTabla(['id_estatus' => 4], ["tabla" => "solicitud_contrato", "editar" => true, "idEditar" => ["id_solicitud_contrato" => $id_solicitud]], ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/guardarArchivosSolicitud']);
                    if (!$res->error) {
                        $count++;
                    } else {
                        $errores++;
                    }
                } else {
                    $errores++;
                }
            };

            foreach ($_FILES['archivos']['name'] as $key => $originalName) {
                if (is_array($originalName)) {
                    $limite = in_array((string) $key, ['3a', '3d'], true) ? 3 : 1;
                    foreach (array_slice($originalName, 0, $limite) as $indice => $nombreArchivo) {
                        $guardarArchivoContrato(
                            $key,
                            $nombreArchivo,
                            $_FILES['archivos']['tmp_name'][$key][$indice] ?? '',
                            $_FILES['archivos']['error'][$key][$indice] ?? UPLOAD_ERR_NO_FILE,
                            $indice
                        );
                    }
                    continue;
                }

                $guardarArchivoContrato(
                    $key,
                    $originalName,
                    $_FILES['archivos']['tmp_name'][$key] ?? '',
                    $_FILES['archivos']['error'][$key] ?? UPLOAD_ERR_NO_FILE
                );
            }
        }
        
         if ($count > 0) {
            // Enviar correo a lvelaga@guanajuato.gob.mx
            $emailService = \Config\Services::email();
            $usuarioQuery = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["id_usuario" => ($session->id_usuario ?? 0)]]);
            $nombreUsuario = (isset($usuarioQuery->data) && !empty($usuarioQuery->data)) ? $usuarioQuery->data[0]->nombre_completo : 'Usuario Desconocido';
            $enlace = base_url('index.php/Principal/ListaSolicitudContrato');
            $emailService->setFrom('noreply@susi.gob.mx', 'SUSI - SECTURI');
            $emailService->setTo($this->obtenerCorreosRevisionJuridica());
            $emailService->setSubject('Nueva Solicitud de Contrato - Archivos Adjuntados');
            $emailService->setMailType('html');
            $emailService->setMessage("
                <p>Buen día,</p>
                <p>Se le notifica que se han subido documentos para la solicitud de contrato con ID <strong>{$id_solicitud}</strong>.</p>
                <p>Los archivos fueron agregados por el usuario: <strong>{$nombreUsuario}</strong>.</p>
                <p>Puede consultar los detalles ingresando al siguiente enlace: <a href='{$enlace}'>{$enlace}</a></p>
                <br>
                <p>Saludos cordiales,</p>
                <p><strong>Sistema Unificado SECTURI (SUSI)</strong></p>
                <a href='https://secturnet.guanajuato.gob.mx/susi/index.php/Principal/ListaSolicitudContrato'>Ir al sistema</a>
            ");
            $emailService->send();

            $response->error = false;
            $msg = "Se guardaron $count archivos correctamente.";
            if ($errores > 0) $msg .= " Hubo problemas con $errores archivos.";
            $response->respuesta = $msg;
        } else {
            $response->respuesta = "No se guardó ningún archivo. " . ($errores > 0 ? "Hubo errores al procesar." : "No se seleccionaron archivos.");
        } 

        return $this->respond($response);
    }

    public function aprobarSolicitudContrato()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;

        $id = $this->request->getPost('id_solicitud');

        if (!$id) {
            $response->respuesta = "ID de solicitud no válido.";
            return $this->respond($response);
        }

        $dataConfig = [
            "tabla" => "solicitud_contrato",
            "editar" => true,
            "idEditar" => ["id_solicitud_contrato" => $id]
        ];

        $dataUpdate = [
            "id_estatus" => 3,
            "usu_act" => $session->id_usuario ?? 0,
            "fec_act" => date('Y-m-d H:i:s')
        ];

        $res = $globals->saveTabla($dataUpdate, $dataConfig, ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/aprobarSolicitudContrato']);

        if (!$res->error) {
            $response->error = false;
            $response->respuesta = "Solicitud aprobada correctamente.";
        } else {
            $response->respuesta = "No se pudo aprobar la solicitud.";
        }

        return $this->respond($response);
    }
    public function declinarSolicitudContrato()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $emailService = \Config\Services::email();
        $response = new \stdClass();
        $response->error = true;

        $id = $this->request->getPost('id_solicitud');
        $motivo = $this->request->getPost('motivo');

        if (!$id) {
            $response->respuesta = "ID de solicitud no válido.";
            return $this->respond($response);
        }

        $dataConfig = [
            "tabla" => "solicitud_contrato",
            "editar" => true,
            "idEditar" => ["id_solicitud_contrato" => $id]
        ];

        $dataUpdate = [
            "id_estatus" => 2,
            "motivo" => $motivo,
            "usu_act" => $session->id_usuario ?? 0,
            "fec_act" => date('Y-m-d H:i:s')
        ];

        $res = $globals->saveTabla($dataUpdate, $dataConfig, ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/declinarSolicitudContrato']);

        if (!$res->error) {
            // Mandar correo
            $solicitudQuery = $globals->getTabla(["tabla" => "solicitud_contrato", "where" => ["id_solicitud_contrato" => $id]]);
            if(isset($solicitudQuery->data) && !empty($solicitudQuery->data)){
               $usu_reg = $solicitudQuery->data[0]->usu_reg;
               $usuarioQuery = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["id_usuario" => $usu_reg]]);
               if(isset($usuarioQuery->data) && !empty($usuarioQuery->data) && !empty($usuarioQuery->data[0]->correo)){
                   $correoDestino = $usuarioQuery->data[0]->correo;
                   $nombreUsuario = $usuarioQuery->data[0]->nombre_completo ?? 'Usuario';
                   $enlaceListado = 'https://secturnet.guanajuato.gob.mx/susi/index.php/Principal/ListaSolicitudContrato';
                   //$enlaceListado = 'https://secturnet.guanajuato.gob.mx/susi/index.php/Principal/ListaSolicitudContrato';
                   
                   $emailService->setFrom('noreply@susi.gob.mx', 'SUSI - SECTURI');
                   $emailService->setTo($correoDestino);
                   $emailService->setSubject('Solicitud de Contrato Declinada');
                   $emailService->setMailType('html');
                   $emailService->setMessage("
                       <p>Buen día, <strong>{$nombreUsuario}</strong>:</p>
                       <p>Se le notifica que su solicitud de elaboración de contrato con ID <strong>{$id}</strong> ha sido <strong>declinada</strong>.</p>
                       <p><strong>Motivo:</strong> {$motivo}</p>
                        <p>Puede consultar mayores detalles ingresando al siguiente enlace:</p>
                        <p><a href='{$enlaceListado}' target='_blank'>{$enlaceListado}</a></p>
                       <br>
                       <p>Saludos cordiales,</p>
                       <p><strong>Sistema Unificado SECTURI (SUSI)</strong></p>
                   ");
                   $emailService->send();
               }
            }

            $response->error = false;
            $response->respuesta = "Solicitud declinada correctamente.";
        } else {
            $response->respuesta = "No se pudo declinar la solicitud.";
        }

        return $this->respond($response);
    }

    public function subirInstrumentoJuridico()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $emailService = \Config\Services::email();
        $response = new \stdClass();
        $response->error = true;

        $id = $this->request->getPost('id_solicitud');
        $archivos = $this->request->getFileMultiple('archivos');

        if (!$id || empty($archivos)) {
            $response->respuesta = "Archivos o ID de solicitud no válido.";
            return $this->respond($response);
        }

        if (count($archivos) > 4) {
            $response->respuesta = "Solo se permiten hasta 4 instrumentos juridicos.";
            return $this->respond($response);
        }

        $rutasGuardadas = [];
        $uploadPath = FCPATH . 'assets/instrumentos_juridicos/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        foreach ($archivos as $archivo) {
            if ($archivo->isValid() && !$archivo->hasMoved() && $archivo->getExtension() === 'pdf') {
                $newName = $archivo->getRandomName();
                $s3Key = $this->uploadFileToS3Storage($archivo->getTempName(), 'contratos', 'instrumentos', $newName);
                if ($s3Key) {
                    $rutasGuardadas[] = $s3Key;
                }
            }
        }

        if (empty($rutasGuardadas)) {
             $response->respuesta = "No se pudieron guardar los archivos o no son PDF válidos.";
             return $this->respond($response);
        }

        // Obtener posibles rutas existentes si desea acumular, o sobreecribir todo.
        // Aquí se sobrescribirá con el arreglo nuevo en formato JSON
        $jsonRutas = json_encode($rutasGuardadas);

        // Update DB
        $dataConfig = [
            "tabla" => "solicitud_contrato",
            "editar" => true,
            "idEditar" => ["id_solicitud_contrato" => $id]
        ];

        $dataUpdate = [
            "id_estatus" => 3,
            "instrumento_juridico" => $jsonRutas,
            "usu_act" => $session->id_usuario ?? 0,
            "fec_act" => date('Y-m-d H:i:s')
        ];

        $res = $globals->saveTabla($dataUpdate, $dataConfig, ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/subirInstrumentoJuridico']);

        if (!$res->error) {
            // Mandar correo
            $solicitudQuery = $globals->getTabla(["tabla" => "solicitud_contrato", "where" => ["id_solicitud_contrato" => $id]]);
            if(isset($solicitudQuery->data) && !empty($solicitudQuery->data)){
               $usu_reg = $solicitudQuery->data[0]->usu_reg;
               $usuarioQuery = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["id_usuario" => $usu_reg]]);
                if(isset($usuarioQuery->data) && !empty($usuarioQuery->data) && !empty($usuarioQuery->data[0]->correo)){
                    $correoDestino = $usuarioQuery->data[0]->correo;
                    $nombreUsuario = $usuarioQuery->data[0]->nombre_completo ?? 'Usuario';
                    $enlaceListado = 'https://secturnet.guanajuato.gob.mx/susi/index.php/Principal/ListaSolicitudContrato';
                    
                    $emailService->setFrom('noreply@susi.gob.mx', 'SUSI - SECTURI');
                   $emailService->setTo($correoDestino);
                   $emailService->setSubject('Instrumento Jurídico Cargado');
                   $emailService->setMailType('html');
                   $emailService->setMessage("
                       <p>Buen día, <strong>{$nombreUsuario}</strong>:</p>
                       <p>Se le notifica que se ha subido correctamente el instrumento jurídico en formato PDF para la solicitud de contrato con ID <strong>{$id}</strong>.</p>
                        <p>Puede visualizarlo ingresando al siguiente enlace:</p>
                        <p><a href='{$enlaceListado}' target='_blank'>{$enlaceListado}</a></p>
                       <br>
                       <p>Saludos cordiales,</p>
                       <p><strong>Sistema Unificado SECTURI (SUSI)</strong></p>
                   ");
                   $emailService->send();
               }
            }
            
            $response->error = false;
            $response->respuesta = "Instrumento jurídico subido y guardado correctamente.";
        } else {
            $response->respuesta = "No se pudo actualizar la solicitud.";
        }

        return $this->respond($response);
    }

    public function reemplazarInstrumentoContrato()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'No fue posible reemplazar el instrumento.';

        if (!in_array((int) ($session->id_perfil ?? 0), [1, 7], true)) {
            $response->respuesta = 'No tiene permisos para editar instrumentos.';
            return $this->respond($response);
        }

        $id = (int) ($this->request->getPost('id_solicitud') ?? 0);
        $indice = (int) ($this->request->getPost('indice') ?? -1);
        $archivos = $this->request->getFileMultiple('archivos');
        if (empty($archivos)) {
            $archivoUnico = $this->request->getFile('archivo');
            $archivos = $archivoUnico ? [$archivoUnico] : [];
        }

        if ($id <= 0 || $indice < 0) {
            $response->respuesta = 'Solicitud o instrumento no valido.';
            return $this->respond($response);
        }

        if (empty($archivos)) {
            $response->respuesta = 'Debe seleccionar al menos un archivo PDF.';
            return $this->respond($response);
        }

        if (count($archivos) > 4) {
            $response->respuesta = 'Solo se permiten hasta 4 instrumentos.';
            return $this->respond($response);
        }

        $solicitudQuery = $globals->getTabla([
            'tabla' => 'solicitud_contrato',
            'where' => ['id_solicitud_contrato' => $id, 'visible' => 1]
        ]);

        if (empty($solicitudQuery->data)) {
            $response->respuesta = 'Solicitud no encontrada.';
            return $this->respond($response);
        }

        $instrumentoRaw = $solicitudQuery->data[0]->instrumento_juridico ?? '';
        $instrumentos = [];
        if (is_string($instrumentoRaw) && $instrumentoRaw !== '') {
            $decoded = json_decode($instrumentoRaw, true);
            $instrumentos = is_array($decoded) ? $decoded : [$instrumentoRaw];
        } elseif (is_array($instrumentoRaw)) {
            $instrumentos = $instrumentoRaw;
        }

        if (!isset($instrumentos[$indice])) {
            $response->respuesta = 'El instrumento seleccionado no existe.';
            return $this->respond($response);
        }

        if (($indice + count($archivos)) > 4) {
            $response->respuesta = 'La edicion no puede superar 4 instrumentos.';
            return $this->respond($response);
        }

        $rutasNuevas = [];
        foreach ($archivos as $offset => $archivo) {
            if (!$archivo || !$archivo->isValid() || $archivo->hasMoved()) {
                $response->respuesta = 'Debe seleccionar archivos PDF validos.';
                return $this->respond($response);
            }

            if (strtolower((string) $archivo->getExtension()) !== 'pdf') {
                $response->respuesta = 'Solo se permiten archivos PDF.';
                return $this->respond($response);
            }

            $newName = 'Inst_Contrato_' . $id . '_' . ($indice + $offset + 1) . '_' . substr(md5(uniqid((string) $id, true)), 0, 8) . '.pdf';
            $s3Key = $this->uploadFileToS3Storage($archivo->getTempName(), 'contratos', 'instrumentos', $newName);

            if (empty($s3Key)) {
                $response->respuesta = 'No fue posible guardar el instrumento en AWS S3.';
                return $this->respond($response);
            }

            $rutasNuevas[] = $s3Key;
        }

        array_splice($instrumentos, $indice, count($rutasNuevas), $rutasNuevas);
        $instrumentos = array_slice(array_values($instrumentos), 0, 4);

        $res = $globals->saveTabla(
            [
                'instrumento_juridico' => json_encode(array_values($instrumentos)),
                'usu_act' => $session->id_usuario ?? 0,
                'fec_act' => date('Y-m-d H:i:s')
            ],
            [
                'tabla' => 'solicitud_contrato',
                'editar' => true,
                'idEditar' => ['id_solicitud_contrato' => $id]
            ],
            [
                'id_user' => $session->id_usuario ?? 0,
                'script' => 'Principal.php/reemplazarInstrumentoContrato'
            ]
        );

        if (!$res->error) {
            $response->error = false;
            $response->respuesta = 'Instrumento reemplazado correctamente.';
        } else {
            $response->respuesta = $res->respuesta ?? $response->respuesta;
        }

        return $this->respond($response);
    }

    public function reemplazarInstrumentoConvenio()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'No fue posible reemplazar el instrumento.';

        if (!in_array((int) ($session->id_perfil ?? 0), [1, 7], true)) {
            $response->respuesta = 'No tiene permisos para editar instrumentos.';
            return $this->respond($response);
        }

        $id = (int) ($this->request->getPost('id_solicitud') ?? 0);
        $indice = (int) ($this->request->getPost('indice') ?? -1);
        $archivos = $this->request->getFileMultiple('archivos');
        if (empty($archivos)) {
            $archivoUnico = $this->request->getFile('archivo');
            $archivos = $archivoUnico ? [$archivoUnico] : [];
        }

        if ($id <= 0 || $indice < 0) {
            $response->respuesta = 'Solicitud o instrumento no valido.';
            return $this->respond($response);
        }

        if (empty($archivos)) {
            $response->respuesta = 'Debe seleccionar al menos un archivo PDF.';
            return $this->respond($response);
        }

        if (count($archivos) > 4) {
            $response->respuesta = 'Solo se permiten hasta 4 instrumentos.';
            return $this->respond($response);
        }

        $solicitudQuery = $globals->getTabla([
            'tabla' => 'solicitud_convenio',
            'where' => ['id_solicitud_convenio' => $id, 'visible' => 1]
        ]);

        if (empty($solicitudQuery->data)) {
            $response->respuesta = 'Solicitud no encontrada.';
            return $this->respond($response);
        }

        $instrumentoRaw = $solicitudQuery->data[0]->instrumento_juridico ?? '';
        $instrumentos = [];
        if (is_string($instrumentoRaw) && $instrumentoRaw !== '') {
            $decoded = json_decode($instrumentoRaw, true);
            $instrumentos = is_array($decoded) ? $decoded : [$instrumentoRaw];
        } elseif (is_array($instrumentoRaw)) {
            $instrumentos = $instrumentoRaw;
        }

        if (!isset($instrumentos[$indice])) {
            $response->respuesta = 'El instrumento seleccionado no existe.';
            return $this->respond($response);
        }

        if (($indice + count($archivos)) > 4) {
            $response->respuesta = 'La edicion no puede superar 4 instrumentos.';
            return $this->respond($response);
        }

        $rutasNuevas = [];
        foreach ($archivos as $offset => $archivo) {
            if (!$archivo || !$archivo->isValid() || $archivo->hasMoved()) {
                $response->respuesta = 'Debe seleccionar archivos PDF validos.';
                return $this->respond($response);
            }

            if (strtolower((string) $archivo->getExtension()) !== 'pdf') {
                $response->respuesta = 'Solo se permiten archivos PDF.';
                return $this->respond($response);
            }

            $newName = 'Inst_Convenio_' . $id . '_' . ($indice + $offset + 1) . '_' . substr(md5(uniqid((string) $id, true)), 0, 8) . '.pdf';
            $s3Key = $this->uploadFileToS3Storage($archivo->getTempName(), 'convenios', 'instrumentos', $newName);

            if (empty($s3Key)) {
                $response->respuesta = 'No fue posible guardar el instrumento en AWS S3.';
                return $this->respond($response);
            }

            $rutasNuevas[] = $s3Key;
        }

        array_splice($instrumentos, $indice, count($rutasNuevas), $rutasNuevas);
        $instrumentos = array_slice(array_values($instrumentos), 0, 4);

        $res = $globals->saveTabla(
            [
                'instrumento_juridico' => json_encode(array_values($instrumentos)),
                'usu_act' => $session->id_usuario ?? 0,
                'fec_act' => date('Y-m-d H:i:s')
            ],
            [
                'tabla' => 'solicitud_convenio',
                'editar' => true,
                'idEditar' => ['id_solicitud_convenio' => $id]
            ],
            [
                'id_user' => $session->id_usuario ?? 0,
                'script' => 'Principal.php/reemplazarInstrumentoConvenio'
            ]
        );

        if (!$res->error) {
            $response->error = false;
            $response->respuesta = 'Instrumento reemplazado correctamente.';
        } else {
            $response->respuesta = $res->respuesta ?? $response->respuesta;
        }

        return $this->respond($response);
    }

    public function ListaSolicitudContrato()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = array();
        if(in_array($session->id_perfil, [1,7])) {
            $solicitudes = $globals->getTabla(["tabla" => "vw_solicitud_contrato", "where" => ["visible" => 1]]);
        } else {
            $solicitudes = $globals->getTabla(["tabla" => "vw_solicitud_contrato", "where" => ["visible" => 1, "usu_reg" => $session->id_usuario]]);
        }
        if (!empty($solicitudes->data)) {
            foreach ($solicitudes->data as &$sol) {
                 $archivos = $globals->getTabla([
                     "tabla" => "solicitud_contrato_archivos", 
                     "where" => ["visible" => 1, 'id_solicitud_contrato' => $sol->id_solicitud_contrato]
                 ]);
                 $sol->tienen_archivos = (!empty($archivos->data));
                 $solicitudBase = $globals->getTabla([
                     "tabla" => "solicitud_contrato",
                     "where" => ["visible" => 1, "id_solicitud_contrato" => $sol->id_solicitud_contrato],
                     "limit" => 1
                 ]);
                 if (!empty($solicitudBase->data)) {
                     $sol->no_contrato = $solicitudBase->data[0]->no_contrato ?? '';
                 }
            }
        }
        $data['solicitudes'] = (!empty($solicitudes->data)) ? $solicitudes->data : [];
        if (!empty($data['solicitudes'])) {
            foreach ($data['solicitudes'] as &$sol) {
                $instrumentos = $sol->instrumento_juridico ?? null;

                if (empty($instrumentos) && !empty($sol->id_solicitud_convenio)) {
                    $solicitudBase = $globals->getTabla([
                        'tabla' => 'solicitud_convenio',
                        'where' => [
                            'id_solicitud_convenio' => $sol->id_solicitud_convenio,
                            'visible' => 1
                        ]
                    ]);

                    if (!empty($solicitudBase->data)) {
                        $instrumentos = $solicitudBase->data[0]->instrumento_juridico ?? null;
                        $sol->instrumento_juridico = $instrumentos;
                    }
                }

                $sol->instrumento_urls = $this->mapInstrumentoUrls($instrumentos);
            }
        }
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vListaSolicitudContrato';
        $this->_renderView($data);
    }

    public function guardarNoContratoSolicitudContrato()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'No fue posible guardar el No. contrato.';

        if (!in_array((int) ($session->id_perfil ?? 0), [1, 7], true)) {
            $response->respuesta = 'No tiene permisos para guardar el No. contrato.';
            return $this->respond($response);
        }

        $idSolicitud = (int) ($this->request->getPost('id_solicitud_contrato') ?? 0);
        $noContrato = trim((string) ($this->request->getPost('no_contrato') ?? ''));

        if ($idSolicitud <= 0) {
            $response->respuesta = 'Solicitud no valida.';
            return $this->respond($response);
        }

        if ($noContrato === '') {
            $response->respuesta = 'El No. contrato es requerido.';
            return $this->respond($response);
        }

        $res = $globals->saveTabla(
            [
                'no_contrato' => $noContrato,
                'usu_act' => $session->id_usuario ?? 0,
                'fec_act' => date('Y-m-d H:i:s')
            ],
            [
                'tabla' => 'solicitud_contrato',
                'editar' => true,
                'idEditar' => ['id_solicitud_contrato' => $idSolicitud]
            ],
            [
                'id_user' => $session->id_usuario ?? 0,
                'script' => 'Principal.php/guardarNoContratoSolicitudContrato'
            ]
        );

        if (!$res->error) {
            $response->error = false;
            $response->respuesta = 'No. contrato guardado correctamente.';
        } else {
            $response->respuesta = $res->respuesta ?? $response->respuesta;
        }

        return $this->respond($response);
    }

    public function guardarNoConvenioSolicitudConvenio()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'No fue posible guardar el No. convenio.';

        if (!in_array((int) ($session->id_perfil ?? 0), [1, 7], true)) {
            $response->respuesta = 'No tiene permisos para guardar el No. convenio.';
            return $this->respond($response);
        }

        $idSolicitud = (int) ($this->request->getPost('id_solicitud_convenio') ?? 0);
        $noConvenio = trim((string) ($this->request->getPost('no_convenio') ?? ''));

        if ($idSolicitud <= 0) {
            $response->respuesta = 'Solicitud no valida.';
            return $this->respond($response);
        }

        if ($noConvenio === '') {
            $response->respuesta = 'El No. convenio es requerido.';
            return $this->respond($response);
        }

        $res = $globals->saveTabla(
            [
                'no_convenio' => $noConvenio,
                'usu_act' => $session->id_usuario ?? 0,
                'fec_act' => date('Y-m-d H:i:s')
            ],
            [
                'tabla' => 'solicitud_convenio',
                'editar' => true,
                'idEditar' => ['id_solicitud_convenio' => $idSolicitud]
            ],
            [
                'id_user' => $session->id_usuario ?? 0,
                'script' => 'Principal.php/guardarNoConvenioSolicitudConvenio'
            ]
        );

        if (!$res->error) {
            $response->error = false;
            $response->respuesta = 'No. convenio guardado correctamente.';
        } else {
            $response->respuesta = $res->respuesta ?? $response->respuesta;
        }

        return $this->respond($response);
    }

    public function guardarNoConvenioSolicitudAdquisiciones()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'No fue posible guardar el No. convenio.';

        if (!in_array((int) ($session->id_perfil ?? 0), [1, 7], true)) {
            $response->respuesta = 'No tiene permisos para guardar el No. convenio.';
            return $this->respond($response);
        }

        $idSolicitud = (int) ($this->request->getPost('id_solicitud_adquisiciones') ?? 0);
        $noConvenio = trim((string) ($this->request->getPost('no_convenio') ?? ''));

        if ($idSolicitud <= 0) {
            $response->respuesta = 'Solicitud no valida.';
            return $this->respond($response);
        }

        if ($noConvenio === '') {
            $response->respuesta = 'El No. convenio es requerido.';
            return $this->respond($response);
        }

        $res = $globals->saveTabla(
            [
                'no_convenio' => $noConvenio,
                'usu_act' => $session->id_usuario ?? 0,
                'fec_act' => date('Y-m-d H:i:s')
            ],
            [
                'tabla' => 'solicitud_adquisiciones',
                'editar' => true,
                'idEditar' => ['id_solicitud_adquisiciones' => $idSolicitud]
            ],
            [
                'id_user' => $session->id_usuario ?? 0,
                'script' => 'Principal.php/guardarNoConvenioSolicitudAdquisiciones'
            ]
        );

        if (!$res->error) {
            $response->error = false;
            $response->respuesta = 'No. convenio guardado correctamente.';
        } else {
            $response->respuesta = $res->respuesta ?? $response->respuesta;
        }

        return $this->respond($response);
    }

    public function deletePT()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $id_registro_pt = $this->request->getPost('id_registro_pt');

        $dataConfig = [
            "tabla" => "registro_pt",
            "editar" => true,
            "idEditar" => ['id_registro_pt' => $id_registro_pt]
        ];


        $response = $globals->saveTabla(['visible' => 0], $dataConfig, ["script" => "opciones.DeletePT"]);
        return $this->respond($response);
    }
    public function deletePTVe()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $id_vehiculo = $this->request->getPost('id_registro_pt');

        $dataConfig = [
            "tabla" => "pt_vehiculo",
            "editar" => true,
            "idEditar" => ['id_vehiculo' => $id_vehiculo]
        ];


        $response = $globals->saveTabla(['visible' => 0], $dataConfig, ["script" => "opciones.DeletePT"]);
        return $this->respond($response);
    }
    public function listadoEnvioGO()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        if (in_array($session->get('id_perfil'), [1, 2])) {
            $registro_go = $globals->getTabla(['tabla' => 'vw_registro_go', 'where' => ['visible' => 1, 'id_estatus' => 2]]);
        } else {
            $registro_go = $globals->getTabla(['tabla' => 'vw_registro_go', 'where' => ['visible' => 1, 'usu_reg' => $session->get('id_usuario'), 'id_estatus' => 2]]);
        }
       // die( var_dump( $registro_go ) );
        $data['registro_go'] = (!empty($registro_go->data)) ? $registro_go->data : [];
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vListadoGo';
        $this->_renderView($data);
    }
    public function listaGOjuridico()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        if (in_array($session->get('id_perfil'), [1, 2, 7])) {
            $registro_go = $globals->getTabla(['tabla' => 'vw_registro_go', 'where' => ['visible' => 1]]);
        } else {
            $registro_go = $globals->getTabla(['tabla' => 'vw_registro_go', 'where' => ['visible' => 1, 'usu_reg' => $session->get('id_usuario')]]);
        }

        $data['registro_go'] = (!empty($registro_go->data)) ? $registro_go->data : [];
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vListadoGo';
        $this->_renderView($data);
    }
    public function listadoPTjuridico()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        if (in_array($session->get('id_perfil'), [1, 2])) {
            $registro_pt = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1]]);
        } else {
            $registro_pt = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1, 'usu_reg' => $session->get('id_usuario')]]);
        }


        $data['registro_pt'] = (!empty($registro_pt->data)) ? $registro_pt->data : [];
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vregistroPT';
        $this->_renderView($data);
    }
    public function listadoEstatusPT()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        if ($session->get('id_perfil') == 1) {
            $registro_pt = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1]]);
        } 
        if ($session->get('id_perfil') == 2) {
            $registro_pt = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1, 'id_estatus' =>4]]);
        }
        if (!in_array($session->get('id_perfil'), [1,2])){
            $registro_pt = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1, 'id_estatus' =>3, 'usu_reg' => $session->get('id_usuario')]]);
        }
        //var_dump($registro_pt);
        //die();
        $data['registro_pt'] = (!empty($registro_pt->data)) ? $registro_pt->data : [];
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vregistroPT';
        $this->_renderView($data);
    }
    public function viaticoPersona($id = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        
        $folio = $globals->getTabla(['tabla' => 'formulario_pt', 'where' => ['visible' => 1, 'id_formulario_pt' => $id]]);
        $data['folio'] = $folio->data[0]->no_consecutivo;
        // Obtener datos de viaticos
        $viaticos = $globals->getTabla(['tabla' => 'viaticos_go', 'where' => ['visible' => 1, 'id_registro_go' => $id]]);

        // Cargar vista HTML
        $data['viaticos'] = (!empty($viaticos->data)) ? $viaticos->data : [];
        $html = view('secciones/vFormatoViaticosDesglose', $data);

        // Generar PDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 20,
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_bottom' => 20,
            'format' => 'Letter'
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Viaticos_Desglose.pdf', 'I');
        exit();
    }
    public function tablaArchivos($id = null, $tipo = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        if ($tipo != 'PT' && $tipo != 'GO' && $tipo != 'GRC' && $tipo != 'FIC') {
            $data['layout'] = 'plantilla/lytVacio';
            $data['contentView'] = 'secciones/vError500';
            $this->_renderView($data);
            die();
        }
        $data['viaticos']= false;
        $PT = ($tipo == 'PT') ? TRUE : FALSE;
        $GO = ($tipo == 'GO') ? TRUE : FALSE;
        $GRC = ($tipo == 'GRC') ? TRUE : FALSE;
        $FIC = ($tipo == 'FIC') ? TRUE : FALSE;
        if($PT) {
            $factura = $globals->getTabla(['tabla' => 'factura', 'where' => ['visible' => 1, 'id_registro_pt' => $id]]);
        }
        if($GO) {
            $factura = $globals->getTabla(['tabla' => 'xml_go', 'where' => ['visible' => 1, 'id_registro_go' => $id]]);
            $viaticos = $globals->getTabla(['tabla' => 'viaticos_go', 'where' => ['visible' => 1, 'id_registro_go' => $id]]);
            if(isset($viaticos->data) && !empty($viaticos->data)){
               $data['viaticos']= true;
            }
        }
        //die(var_dump($factura));
        $data['PT'] = $PT;
        $data['factura'] = (!empty($factura->data)) ? $factura->data : [];
        $data['GO'] = $GO;
        $data['GRC'] = $GRC;
        $data['FIC'] = $FIC;
        $data['id_registro'] = $id;
        $data['tipo'] = $tipo;
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vTablaArchivos';
        $this->_renderView($data);
    }
    public function tablaArchivosVehiculos($id = null, $tipo = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        if ($tipo != 'PT') {
            $data['layout'] = 'plantilla/lytVacio';
            $data['contentView'] = 'secciones/vError500';
            $this->_renderView($data);
            die();
        }
    
        $data['id_registro'] = $id;
        $data['tipo'] = $tipo;
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vTablaArchivosVehiculo';
        $this->_renderView($data);
    }

    public function familiaSecturi()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vfamiliaSecturi';
        $this->_renderView($data);
    }

    private function numeroEnLetras($numero)
    {
        //die( var_dump( $numero ) );
        $x = explode('.', number_format($numero, 2, '.', ''));
        $entero = intval($x[0]);
        $decimales = $x[1];

        $texto = $this->convertirNumero($entero);
        return ucfirst(trim($texto)) . " pesos {$decimales}/100 M.N.";
    }

    private function convertirNumero($numero)
    {
        $unidad = [
            '',
            'uno',
            'dos',
            'tres',
            'cuatro',
            'cinco',
            'seis',
            'siete',
            'ocho',
            'nueve',
            'diez',
            'once',
            'doce',
            'trece',
            'catorce',
            'quince',
            'dieciséis',
            'diecisiete',
            'dieciocho',
            'diecinueve',
            'veinte'
        ];
        $decena = ['', '', 'veinti', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
        $centena = [
            '',
            'ciento',
            'doscientos',
            'trescientos',
            'cuatrocientos',
            'quinientos',
            'seiscientos',
            'setecientos',
            'ochocientos',
            'novecientos'
        ];

        if ($numero == 0)
            return 'cero';
        if ($numero == 100)
            return 'cien';
        if ($numero >= 1000000) {
            $millones = floor($numero / 1000000);
            $resto = $numero % 1000000;
            $txt = ($millones == 1 ? 'un millón' : $this->convertirNumero($millones) . ' millones');
            if ($resto > 0)
                $txt .= ' ' . $this->convertirNumero($resto);
            return $txt;
        }
        if ($numero >= 1000) {
            $miles = floor($numero / 1000);
            $resto = $numero % 1000;
            $txt = ($miles == 1 ? 'mil' : $this->convertirNumero($miles) . ' mil');
            if ($resto > 0)
                $txt .= ' ' . $this->convertirNumero($resto);
            return $txt;
        }
        if ($numero >= 100) {
            $c = floor($numero / 100);
            $r = $numero % 100;
            return $centena[$c] . ($r > 0 ? ' ' . $this->convertirNumero($r) : '');
        }
        if ($numero <= 20) {
            return $unidad[$numero];
        }
        if ($numero < 30) {
            return $decena[floor($numero / 10)] . $unidad[$numero % 10];
        }
        $d = floor($numero / 10);
        $u = $numero % 10;
        return $decena[$d] . ($u > 0 ? ' y ' . $unidad[$u] : '');
    }
    public function getLink()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $id_registro_pt = $this->request->getPost('id_registro_pt');
        $response = $globals->getTabla([
            'tabla' => 'vw_pdf_reserva',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]
        ])->data;
        return $this->respond($response);
    }
    public function getLinkGo()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $id_registro_go = $this->request->getPost('id_registro_go');
        $response = $globals->getTabla([
            'tabla' => 'vw_pdf_reserva_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_registro_go]
        ])->data;
        return $this->respond($response);
    }
    public function ArchivoGO($id_registro_go = null, $id_archivo = null, $savePath = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $registro_go = $globals->getTabla([
            'tabla' => 'vw_registro_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_registro_go]
        ]);
        $data['reserva'] = "";
        $pdf = $globals->getTabla([
            'tabla' => 'vw_pdf_reserva_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_registro_go]
        ])->data;

        $direccion = $globals->getTabla([
            'tabla' => 'vw_direccion',
            'where' => [
                'visible' => 1,
                //'id_director' => 110
                'id_director' => $registro_go->data[0]->id_reponsable_solicitud
            ]
        ]);

        $prefijoArea = '';
        if($registro_go->data){
           $res =  $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1, 'id_area' => $registro_go->data[0]->id_direccion_responsable]]);
           $prefijoArea = $res->data[0]->prefijo;
         
        }
         $id_reponsable_solicitud = $registro_go->data[0]->id_reponsable_solicitud;
         $data['responsableGasto'] = "";
        if(isset($id_reponsable_solicitud) && !empty($id_reponsable_solicitud)){

             $res = $globals->getTabla(['tabla' => 'vw_usuario', 'where' =>["id_usuario" => $id_reponsable_solicitud ] ]);
             $data['responsableGasto'] = (isset($res->data) && !empty($res->data)) ? $res->data[0] : '';
        }

        
      
        $id_reserva_go = (isset($pdf[0]->id_reserva_go) && !empty($pdf[0]->id_reserva_go)) ? $pdf[0]->id_reserva_go : '';
        $data['es4000'] = false;
        if (!empty($id_reserva_go)) {
            $reserva = $globals->getTabla([
                'tabla' => 'vw_reserva_go',
                'where' => ['visible' => 1, 'id_reserva_go' => $id_reserva_go]
            ])->data;
            //var_dump($reserva);
            //die();
            $data['reserva'] = $reserva[0];
            $importe_str = $reserva[0]->total_importe;
            $importe_float = (float) str_replace(',', '', $importe_str); // quita coma y convierte
            $data['numero_texto'] = $this->numeroEnLetras($importe_float);

            if ($reserva[0]->partida >= '4000' && $reserva[0]->partida < '5000') {
                $data['es4000'] = true;
            }
            $presupuesto = $globals->getTabla([
             'tabla' => 'vw_presupuesto_go',
             'where' => ['visible' => 1, 'id_reserva' => $reserva[0]->id_reserva]
            ]);
                
                if( isset($presupuesto->data) && !empty($presupuesto->data)){
                    $data['presupuesto'] = $presupuesto->data;
                }
        }

        if (!empty($registro_go->data)) {
            $data['registro'] = $registro_go->data[0];
            $folio = $globals->getTabla([
                'tabla' => 'vw_direccion',
                'where' => ['visible' => 1, 'id_area' => $data['registro']->id_direccion_responsable]
            ]);

           

            $no_consecutivo = "";
            if (strlen($registro_go->data[0]->no_consecutivo) == 1) {
                $no_consecutivo = '00' . $registro_go->data[0]->no_consecutivo;
            }
            if (strlen($registro_go->data[0]->no_consecutivo) == 2) {
                $no_consecutivo = '0' . $registro_go->data[0]->no_consecutivo;
            }
            if (strlen($registro_go->data[0]->no_consecutivo) >= 3) {
                $no_consecutivo = $registro_go->data[0]->no_consecutivo;
            }
            
          //  $folio =(isset( $direccion->data) && !empty( $direccion->data))? $direccion->data[0]->folio_prefijo:'S/N/';
        
            $folio_prefijo = $prefijoArea. $no_consecutivo . '/' . date('Y'); //ESTO HAY QUE OREGUNTAR
           // die( var_dump( $folio_prefijo ) );
            //==================================
            $data['folio'] = $folio_prefijo;

        } else {
            echo '<h2>Error al encontrar registro, favor de revisar el id del registro PT</h2>';
            die();
        }

        $data['GO'] = true;
        $data['fic'] = FALSE;
        //var_dump( var_dump( $data ) );
        //die();
        $uuid = $globals->getTabla(['tabla' => 'xml_go', 'where' => ['id_registro_go' => $id_registro_go, 'visible' => 1]]);
      
        if(isset($uuid->data) && !empty($uuid->data)) {
            $data['uuid'] = ($data['fic'])?$uuid->data[0]->uuid:$uuid->data;
        }

        
       
        if ($id_archivo == 1) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(FCPATH . 'assets/documentos/Anexo1_Reporte_d_ integracion_documental_2026.xlsx');
            $sheet = $spreadsheet->getActiveSheet();

            // Insertar Logo
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(FCPATH . 'assets/logo3.png'); // Ruta al logo en assets
            $drawing->setHeight(140); 
            //$drawing->setWidth(200);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(10);
            $drawing->setWorksheet($sheet);

            // set background color
            $sheet->getStyle('A1:E6')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFFFFF');

            // Populate Excel Cells
            // Header Data
            $sheet->setCellValue('H7', date('d/m/Y', strtotime($data['registro']->fecha_tramite)));
            $folioText = (isset($data['GO']) && !empty($data['GO']) ? 'GO' : 'PT') . ' ' . $folio_prefijo;
            $sheet->setCellValue('H9', $folioText);

            // Checkboxes
            // 02_Póliza
            //die( var_dump($data['registro']) );
            $sheet->setCellValue('B15', ($data['registro']->poliza == 1) ? 'Si' : 'No'); 
            $sheet->setCellValue('B17',  'No'); 
            
            // 14_Otros
            $sheet->setCellValue('F19', 'Si');
            $sheet->setCellValue('B16', 'Si');
            $sheet->setCellValue('F14', 'Si');
            $sheet->setCellValue('F15', 'Si');
            $sheet->setCellValue('F16', 'Si');
            $sheet->setCellValue('F17', 'Si');
            

            // Footer / Payment Data
    /*         $sheet->setCellValue('B24', isset($data['registro']->dsc_proveedor) ? $data['registro']->dsc_proveedor : '');
            //die( var_dump($data['presupuesto']) );
            // Partida Presupuestal
            $arrPartida = [];
            if (isset($data['presupuesto']) && is_array($data['presupuesto'])) {
                foreach ($data['presupuesto'] as $p) {
                    $arrPartida[] = $p->dsc_partida;
                }
            } elseif (isset($data['presupuesto']->dsc_partida)) {
                $arrPartida[] = $data['presupuesto']->dsc_partida;
            }
            $sheet->setCellValue('H24', implode(', ', $arrPartida));
 */
         /*    $sheet->setCellValue('B25', isset($data['registro']->concepto_pago) ? $data['registro']->concepto_pago : '');
            
            // Contrato o convenio No.
            $noConvenio = isset($data['presupuesto'][0]->no_convenio) ? $data['presupuesto'][0]->no_convenio : '';
            $sheet->setCellValue('H26', $noConvenio);

            // Folio Fiscal (UUIDs)
             $arrUuid = [];
             $sumaTotal = 0;
            if (isset($data['uuid'])) {
                 $uuids = is_array($data['uuid']) ? $data['uuid'] : [$data['uuid']];
                foreach ($uuids as $u) {
                     $val = is_object($u) ? ((isset($u->folio) && $u->folio) ? $u->folio : $u->uuid) : $u;
                    $arrUuid[] = $val;
                    $sumaTotal += (float)(is_object($u) ? $u->total : 0);
                }
            } */
           // $sheet->setCellValue('B26', implode(', ', $arrUuid));
            
            // Importe Total
             //$fn = new \App\Libraries\Funciones();
             //$importeTexto = '$' . number_format($sumaTotal, 2) . ' ' . $fn->numeroALetras($sumaTotal);
           //  $sheet->setCellValue('B27', $importeTexto);


            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Anexo1_' . $folioText . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit();
        }
        
       

    }
    public function Archivo($id = null, $id_archivo = null, $savePath = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;

        $res = $globals->getTabla([
            'tabla' => 'formulario_pt',
            'where' => ['id_formulario_pt' => $id, 'visible' => 1]
        ]);
        $partidas = $globals->getTabla([
            'tabla' => 'manual_factura',
            'where' => ['id_registro_pt' => $id, 'visible' => 1]
        ]);
      // die(var_dump($partidas));
        $registro = $res->data[0];
       $partida = [];
       $comprobante = [];

        if (isset($partidas->data) && !empty($partidas->data)) {
            foreach ($partidas->data as $p) {
                $partida[] = $p->partida;
            }
        }

        if (isset($partidas->data) && !empty($partidas->data)) {
            foreach ($partidas->data as $p) {
                $comprobante[] = $p->no_comprobante;
            }
        }

        // Eliminar duplicados
        $partida = array_unique($partida);

        // Opcional: reindexar el arreglo
        $partida = array_values($partida);

        if($registro->nombre_proveedor_1 > 0){

            $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['id_proveedor' => $registro->nombre_proveedor_1]])->data[0];
            $registro->nombre_proveedor_1 = $proveedor->razon_social;
        }
      
      // die(var_dump($registro));
       
        if ($id_archivo == 1) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(FCPATH . 'assets/documentos/Anexo1_Reporte_d_ integracion_documental_2026.xlsx');
            $sheet = $spreadsheet->getActiveSheet();

            // Insertar Logo
                  $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(FCPATH . 'assets/logo3.png'); // Ruta al logo en assets
            $drawing->setHeight(140); 
            //$drawing->setWidth(200);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(10);
            $drawing->setWorksheet($sheet);

             // set background color
             $sheet->getStyle('A1:E6')->getFill()
             ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
             ->getStartColor()->setARGB('FFFFFFFF');
           // die(var_dump($spreadsheet));
            // Populate Excel Cells
            // Header Data  
            $sheet->setCellValue('H7', date('d/m/Y', strtotime($registro->fecha_tramite)));
           
            $sheet->setCellValue('H9', $registro->no_consecutivo);
            $sheet->getStyle('H9')->getFont()->setSize(8);
            // Checkboxes (using 'X' or 'Si' as per template logic - assuming 'Si'/'No' text based on image)
            // 02_Póliza
            //die( var_dump(  $data['registro']) );
            $sheet->setCellValue('B15', '');
            $sheet->setCellValue('B17', '');  
            
            // 14_Otros - Logic from vFormato01 implies this might be dynamic or 'Si'
            $sheet->setCellValue('F19', ''); // Defaulting to Si based on current usage or map from DB
            $sheet->setCellValue('D32', '');
            $sheet->setCellValue('D33', '');
            $sheet->setCellValue('D34', '');
            // Footer / Payment Data
            $sheet->setCellValue('B24', isset($registro->nombre_proveedor_1) ? $registro->nombre_proveedor_1 : '');
            
            // Partida Presupuestal
            //die( var_dump($data['presupuesto']) );
          
            $sheet->setCellValue('H24', (isset($partidas->data) && !empty($partidas->data)) ? implode(', ', $partida) : '');

            $sheet->setCellValue('B25', isset($registro->concepto) ? $registro->concepto : '');
            
   
            $sheet->setCellValue('H26', $registro->no_convenio);
            $sheet->setCellValue('B26', (isset($partidas->data) && !empty($partidas->data)) ? implode(', ', $comprobante) : '');
            $sheet->setCellValue('B27', $registro->importe_total_num.' ('.$registro->importe_letra.')');



            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Anexo1_' . $registro->no_consecutivo . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit();
        }



    }
    public function ArchivoVe($id_registro_pt = null, $id_archivo = null, $savePath = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data['reserva'] = "";
        $vehiculo = $globals->getTabla([
            'tabla' => 'pt_vehiculo',
            'where' => ['visible' => 1, 'id_vehiculo' => $id_registro_pt]
        ]);
        if(isset($vehiculo->data) && !empty($vehiculo->data)){
            $data['vehiculo'] = (isset($vehiculo->data) && !empty($vehiculo))?$vehiculo->data[0]:[];
            $importe_str= $vehiculo->data[0]->xml_monto;
            $importe_float = (float) str_replace(',', '', $importe_str); // quita coma y convierte
            $data['numero_texto'] = $this->numeroEnLetras($importe_float);
            $responsable = $globals->getTabla([
                'tabla' => 'vw_usuario',
                'where' => ['visible' => 1, 'id_usuario' => $vehiculo->data[0]->id_responsable ]
            ]);
            $proyecto = $globals->getTabla([
                'tabla' => 'cat_proyecto',
                'where' => ['visible' => 1, 'id_proyecto' => $vehiculo->data[0]->id_proyecto ]
            ]);
            $data['proyecto'] = isset($proyecto->data) && !empty($proyecto->data)?$proyecto->data[0]:[];
            $responsableGasto = $globals->getTabla([
                'tabla' => 'vw_direccion',
                'where' => ['visible' => 1, 'id_director' => $vehiculo->data[0]->id_responsable_gasto ]
            ]);
            $proveedor = $globals->getTabla([
                'tabla' => 'proveedor',
                'where' => ['visible' => 1, 'id_proveedor' => $vehiculo->data[0]->id_proveedor ]
            ]);
        
            $data['responsableGasto'] = isset($responsableGasto->data) && !empty($responsableGasto->data)?$responsableGasto->data[0]:[];
            $data['responsable'] = isset($responsable->data) && !empty($responsable->data)?$responsable->data[0]:[];
            $data['proveedor'] = isset($proveedor->data) && !empty($proveedor->data)?$proveedor->data[0]->razon_social:[];
           
            
        }
       // die( var_dump($data['proveedor'] ) );
      
     
      
    
            switch ($id_archivo) {
                case 1:
                    $doc = 'assets/pdf/plantillas/anexo01.pdf';
                    $formato = 'personal/vFormato01Ve.php';
                    break;
                case 4:
                    $data['layout'] = 'plantilla/lytVacio';
                    $data['contentView'] = 'secciones/vError500';
                    $this->_renderView($data);
                    die();
                    break;


            }
        
           // die( var_dump($data) );

        $html = view($formato, $data);
        $htmlSegundaHoja = view('personal/vFormato02Ve.php', $data);
        //Crear instancia de mPDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

        $pagecount = $mpdf->SetSourceFile(FCPATH . $doc);
        for ($i = 1; $i <= $pagecount; $i++) {
            $mpdf->AddPage();
            $tplId = $mpdf->ImportPage($i);
            $mpdf->UseTemplate($tplId);

            if ($i == 1) {
                $mpdf->WriteHTML($html);
            }
            if ($i == 2) {
                $mpdf->WriteHTML($htmlSegundaHoja);
            }
        }


        if ($savePath) {
            $mpdf->Output($savePath, 'F'); // F = write to file
            return $savePath;
        }
        $mpdf->Output('Formato_pt.pdf', 'I');
        exit();

    }
    public function ArchivoFIC($id_registro_pt = null, $id_archivo = null, $savePath = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data['reserva'] = "";
        $registro_pt = $globals->getTabla([
            'tabla' => 'vw_registro_pt',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]
        ]);

        $xml = $globals->getTabla([
            'tabla' => 'factura',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]
        ]);

        $direccion = $globals->getTabla([
            'tabla' => 'vw_direccion',
            'where' => [
                'visible' => 1,
                'id_director' => $registro_pt->data[0]->id_reponsable_solicitud
            ]
        ]);

        if (empty($direccion->data)) {
            $jefe = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $registro_pt->data[0]->id_reponsable_solicitud]]);
            if (!empty($jefe->data)) {
                $idJefe = $jefe->data[0]->id_jefe_inmediato;
                $direccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1, 'id_director' => $idJefe]]);
            } else {
                $area = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $registro_pt->data[0]->id_reponsable_solicitud]]);
                $direccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1, 'id_area' => $area->data[0]->id_area]]);
            }
        }

        $no_consecutivo = "";
        if (strlen($registro_pt->data[0]->no_consecutivo) == 1) {
            $no_consecutivo = '00' . $registro_pt->data[0]->no_consecutivo;
        }
        if (strlen($registro_pt->data[0]->no_consecutivo) == 2) {
            $no_consecutivo = '0' . $registro_pt->data[0]->no_consecutivo;
        }
        if (strlen($registro_pt->data[0]->no_consecutivo) >= 3) {
            $no_consecutivo = $registro_pt->data[0]->no_consecutivo;
        }
        $folio_prefijo = $direccion->data[0]->folio_prefijo . $no_consecutivo . '/' . date('Y'); //ESTO HAY QUE OREGUNTAR

        $data['direccion'] = $direccion->data[0];
        $pdf = $globals->getTabla([
            'tabla' => 'vw_pdf_reserva',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]
        ])->data;

        $instrumento = (isset($pdf[0]->instrumento) && !empty($pdf[0]->instrumento)) ? $pdf[0]->instrumento : '';
        $id_reserva = (isset($pdf[0]->id_reserva) && !empty($pdf[0]->id_reserva)) ? $pdf[0]->id_reserva : '';
        if (!empty($id_reserva)) {
            $reserva = $globals->getTabla([
                'tabla' => 'vw_reserva',
                'where' => ['visible' => 1, 'id_reserva' => $id_reserva]
            ])->data;
            $data['reserva'] = $reserva[0];
            $data['presupuesto'] = $reserva;

        
            $importe_str = $reserva[0]->total_importe;
            $importe_float = (float) str_replace(',', '', $importe_str); // quita coma y convierte
            $data['numero_texto'] = $this->numeroEnLetras($importe_float);
            $data['es4000'] = false;
            if ($reserva[0]->partida >= '4000' && $reserva[0]->partida < '5000') {
                $data['es4000'] = true;
            }


        }

        if (!empty($registro_pt->data)) {
            $data['registro'] = $registro_pt->data[0];
            $folio = $globals->getTabla([
                'tabla' => 'direccion',
                'where' => ['visible' => 1, 'id_area' => $data['registro']->id_direccion_responsable]
            ]);
            $data['fic'] = false;
            if ($registro_pt->data[0]->no_reserva == '4327278') {
                $data['folio'] = "SECTURI/DGDT/DCT/FIC-TH/" . $no_consecutivo . '/' . date('Y');
                $data['fic'] = true;
            } else if ($registro_pt->data[0]->no_reserva == '4327279') {
                $data['folio'] = "SECTURI/DGDT/DCT/FIC-TA/" . $no_consecutivo . '/' . date('Y');
                $data['fic'] = true;
            } else if ($registro_pt->data[0]->no_reserva == '4327277') {
                $data['folio'] = "SECTURI/DGDT/DCT/FIC-TA/" . $no_consecutivo . '/' . date('Y');
                $data['fic'] = true;
            } else {
                $data['folio'] = $folio_prefijo;
            }

        } else {
            echo '<h2>Error al encontrar registro, favor de revisar el id del registro PT</h2>';
            die();
        }
        $uudi = $globals->getTabla(['tabla' => 'factura', 'where' => ['id_registro_pt' => $id_registro_pt, 'visible' => 1]]);

        if (isset($uudi->data) && !empty($uudi->data)) {
            $data['uuid'] = $uudi->data;
        }
        //die( var_dump(  $uudi ) );
        if (!empty($instrumento)) {
            switch ($id_archivo) {
                case 1:
                    $doc = 'assets/pdf/plantillas/anexo01.pdf';
                    $formato = 'personal/vFormato01.php';
                    break;
                case 4:
                    if ($savePath) {
                        $source = FCPATH . $instrumento;
                        if (file_exists($source)) {
                            copy($source, $savePath);
                            return $savePath;
                        }
                        return null;
                    } else {
                        // Solo si se quiere mostrar directo en navegador
                        return redirect()->to(base_url() . $instrumento);
                    }
                    break;


            }

        } else {
            switch ($id_archivo) {
                case 1:
                    $doc = 'assets/pdf/plantillas/anexo01.pdf';
                    $formato = 'personal/vFormato01.php';
                    break;
                case 4:
                    $data['layout'] = 'plantilla/lytVacio';
                    $data['contentView'] = 'secciones/vError500';
                    $this->_renderView($data);
                    die();
                    break;


            }
        }
       
        $html = view($formato, $data);
        $htmlSegundaHoja = view('personal/vFormato02.php', $data);
        //Crear instancia de mPDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

        $pagecount = $mpdf->SetSourceFile(FCPATH . $doc);
        for ($i = 1; $i <= $pagecount; $i++) {
            $mpdf->AddPage();
            $tplId = $mpdf->ImportPage($i);
            $mpdf->UseTemplate($tplId);

            if ($i == 1) {
                $mpdf->WriteHTML($html);
            }
            if ($i == 2) {
                $mpdf->WriteHTML($htmlSegundaHoja);
            }
        }


        if ($savePath) {
            $mpdf->Output($savePath, 'F'); // F = write to file
            return $savePath;
        }
        $mpdf->Output('Formato_pt.pdf', 'I');
        exit();

    }
    public function generarZipFIC()
    {
        $response = new \stdClass();
        $id_registro_pt = $this->request->getPost('id_registro_pt');
        $data = $this->request->getPost();
        $Mglobal = new Mglobal;

        if (empty($id_registro_pt)) {
            $response->error = true;
            $response->respuesta = 'ID de registro inválido';
            return $this->respond($response);
        }

        // Consulta de PDFs asociados
        $pdf_reserva = $Mglobal->getTabla([
            'tabla' => 'vw_pdf_reserva',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]
        ]);

        $id_reserva = $Mglobal->getTabla([
            'tabla' => 'registro_pt',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]
        ])->data[0]->id_reserva;

        // Directorio temporal
        $tempDir = sys_get_temp_dir() . '/zip_temp_' . $id_registro_pt . '/';

        if (!is_dir($tempDir) && !mkdir($tempDir, 0777, true)) {
            $response->error = true;
            $response->respuesta = 'No se pudo crear directorio temporal';
            return $this->respond($response);
        }

        $archivos = [];
        $archivosTemporales = [];



        $dynamicFiles = [
            1 => '01 Anexos y formato de los LTPOFB.pdf',

        ];
        foreach ($dynamicFiles as $id => $nombre) {
            $rutaTemp = $tempDir . $nombre;
     
            $archivoGenerado = $this->ArchivoFIC($id_registro_pt, $id, $rutaTemp);
      
            if ($archivoGenerado && file_exists($archivoGenerado)) {
                $archivos[] = $archivoGenerado;
                $archivosTemporales[] = $archivoGenerado;
            }
        }


        // Archivo 07
        $rutaArchivo07 = $tempDir . '07 Formatos_diversos.pdf';
        $archivo07 = $this->ImprimirFIC($id_registro_pt, $rutaArchivo07);
     
        if ($archivo07 && file_exists($archivo07)) {
            $archivos[] = $archivo07;
            $archivosTemporales[] = $archivo07;
        }

        // Archivos desde base de datos (PDFs permanentes)
        if (!empty($pdf_reserva->data)) {
            foreach ($pdf_reserva->data as $pdf) {
                $source = FCPATH . $pdf->ruta_relativa;
                if (file_exists($source)) {
                    $archivos[] = $source;
                    // ¡NO lo añadimos a archivosTemporales!
                }
            }
        }

        // Archivos subidos (05 al 09)
        $uploadedFiles = [
            'archivo06' => '06 Oficios de Autorizaciones.pdf',
            'archivo08' => '08 Evidencia de entregable.pdf',
            'archivo09' => '09 Otros.pdf'
        ];
        foreach ($uploadedFiles as $input => $nombre) {
            $file = $this->request->getFile($input);
            if ($file && $file->isValid()) {
                $newPath = $tempDir . $nombre;
                if ($file->move($tempDir, $nombre)) {
                    $archivos[] = $newPath;
                    $archivosTemporales[] = $newPath;
                }
            }
        }

        if (empty($archivos)) {
            array_map('unlink', glob($tempDir . '*'));
            rmdir($tempDir);
            $response->error = true;
            $response->respuesta = 'No hay archivos para comprimir';
            return $this->respond($response);
        }

        // Crear ZIP
        $timestamp = date('Ymd_His');
        $zipPath = WRITEPATH . "temp_zip/Documentos_{$id_registro_pt}_{$timestamp}.zip";
        if (!is_dir(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0777, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            foreach ($archivos as $archivo) {
                $zip->addFile($archivo, basename($archivo));
            }
            $zip->close();
        }

        if (!file_exists($zipPath)) {
            $response->error = true;
            $response->respuesta = 'El archivo ZIP no se creó correctamente';
            return $this->respond($response);
        }

        // Borrar solo archivos temporales
        foreach ($archivosTemporales as $tempFile) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }

        if (is_dir($tempDir)) {
            rmdir($tempDir);
        }

        // Eliminar ZIP automáticamente al cerrar
        register_shutdown_function(function () use ($zipPath) {
            if (file_exists($zipPath)) {
                unlink($zipPath);
            }
        });

        return $this->response
            ->setHeader('Content-Type', 'application/zip')
            ->setHeader('Content-Disposition', 'attachment; filename="Documentos_' . $id_registro_pt . '.zip"')
            ->setBody(file_get_contents($zipPath));
    }
    public function generarZipV()
    {
        $response = new \stdClass();
        $id_registro_pt = $this->request->getPost('id_registro_pt');
        $data = $this->request->getPost();
        $Mglobal = new Mglobal;

        if (empty($id_registro_pt)) {
            $response->error = true;
            $response->respuesta = 'ID de registro inválido';
            return $this->respond($response);
        }

        // Consulta de PDFs asociados
        $pdf_reserva = $Mglobal->getTabla([
            'tabla' => 'vw_pdf_reserva',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]
        ]);

        $id_reserva = $Mglobal->getTabla([
            'tabla' => 'registro_pt',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]
        ])->data[0]->id_reserva;

        // Directorio temporal
        $tempDir = sys_get_temp_dir() . '/zip_temp_' . $id_registro_pt . '/';

        if (!is_dir($tempDir) && !mkdir($tempDir, 0777, true)) {
            $response->error = true;
            $response->respuesta = 'No se pudo crear directorio temporal';
            return $this->respond($response);
        }

        $archivos = [];
        $archivosTemporales = [];

        $instrumento = $Mglobal->getTabla([
            'tabla' => 'reserva',
            'where' => ['visible' => 1, 'id_reserva' => $id_reserva]
        ])->data[0]->instrumento;

        // Archivos generados dinámicamente

        if (empty($instrumento)) {
            $dynamicFiles = [
                1 => '01 Anexos y formato de los LTPOFB.pdf'
            ];
        } else {
            $dynamicFiles = [
                1 => '01 Anexos y formato de los LTPOFB.pdf',
                4 => '04 Contrato o Convenio (según corresponda).pdf',
            ];
        }

        foreach ($dynamicFiles as $id => $nombre) {
            $rutaTemp = $tempDir . $nombre;
            $archivoGenerado = $this->ArchivoVe($id_registro_pt, $id, $rutaTemp);
            if ($archivoGenerado && file_exists($archivoGenerado)) {
                $archivos[] = $archivoGenerado;
                $archivosTemporales[] = $archivoGenerado;
            }
        }


        // Archivo 07
        $rutaArchivo07 = $tempDir . '07 Formatos_diversos.pdf';
        $archivo07 = $this->ImprimirVPT($id_registro_pt, $rutaArchivo07);
        if ($archivo07 && file_exists($archivo07)) {
            $archivos[] = $archivo07;
            $archivosTemporales[] = $archivo07;
        }

        // Archivos desde base de datos (PDFs permanentes)
        if (!empty($pdf_reserva->data)) {
            foreach ($pdf_reserva->data as $pdf) {
                $source = FCPATH . $pdf->ruta_relativa;
                if (file_exists($source)) {
                    $archivos[] = $source;
                    // ¡NO lo añadimos a archivosTemporales!
                }
            }
        }

        // Archivos subidos (05 al 09)
        $uploadedFiles = [
            'archivo05' => '05 Formatos de los LRADP.pdf',
            'archivo06' => '06 Oficios de Autorizaciones.pdf',
            'archivo08' => '08 Evidencia de entregable.pdf',
            'archivo09' => '09 Otros.pdf'
        ];
        foreach ($uploadedFiles as $input => $nombre) {
            $file = $this->request->getFile($input);
            if ($file && $file->isValid()) {
                $newPath = $tempDir . $nombre;
                if ($file->move($tempDir, $nombre)) {
                    $archivos[] = $newPath;
                    $archivosTemporales[] = $newPath;
                }
            }
        }

        if (empty($archivos)) {
            array_map('unlink', glob($tempDir . '*'));
            rmdir($tempDir);
            $response->error = true;
            $response->respuesta = 'No hay archivos para comprimir';
            return $this->respond($response);
        }

        // Crear ZIP
        $timestamp = date('Ymd_His');
        $zipPath = WRITEPATH . "temp_zip/Documentos_{$id_registro_pt}_{$timestamp}.zip";
        if (!is_dir(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0777, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            foreach ($archivos as $archivo) {
                $zip->addFile($archivo, basename($archivo));
            }
            $zip->close();
        }

        if (!file_exists($zipPath)) {
            $response->error = true;
            $response->respuesta = 'El archivo ZIP no se creó correctamente';
            return $this->respond($response);
        }

        // Borrar solo archivos temporales
        foreach ($archivosTemporales as $tempFile) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }

        if (is_dir($tempDir)) {
            rmdir($tempDir);
        }

        // Eliminar ZIP automáticamente al cerrar
        register_shutdown_function(function () use ($zipPath) {
            if (file_exists($zipPath)) {
                unlink($zipPath);
            }
        });

        return $this->response
            ->setHeader('Content-Type', 'application/zip')
            ->setHeader('Content-Disposition', 'attachment; filename="Documentos_' . $id_registro_pt . '.zip"')
            ->setBody(file_get_contents($zipPath));
    }
    public function generarZip()
    {
        $response = new \stdClass();
        $id_registro_pt = $this->request->getPost('id_registro_pt');
        $data = $this->request->getPost();
        $Mglobal = new Mglobal;

        if (empty($id_registro_pt)) {
            $response->error = true;
            $response->respuesta = 'ID de registro inválido';
            return $this->respond($response);
        }

        // Consulta de PDFs asociados
        $pdf_reserva = $Mglobal->getTabla([
            'tabla' => 'vw_pdf_reserva',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]
        ]);

        $id_reserva = $Mglobal->getTabla([
            'tabla' => 'registro_pt',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]
        ])->data[0]->id_reserva;

        // Directorio temporal
        $tempDir = sys_get_temp_dir() . '/zip_temp_' . $id_registro_pt . '/';

        if (!is_dir($tempDir) && !mkdir($tempDir, 0777, true)) {
            $response->error = true;
            $response->respuesta = 'No se pudo crear directorio temporal';
            return $this->respond($response);
        }

        $archivos = [];
        $archivosTemporales = [];

        $instrumento = $Mglobal->getTabla([
            'tabla' => 'reserva',
            'where' => ['visible' => 1, 'id_reserva' => $id_reserva]
        ])->data[0]->instrumento;

        // Archivos generados dinámicamente

        if (empty($instrumento)) {
            $dynamicFiles = [
                1 => '01 Anexos y formato de los LTPOFB.pdf'
            ];
        } else {
            $dynamicFiles = [
                1 => '01 Anexos y formato de los LTPOFB.pdf',
                4 => '04 Contrato o Convenio (según corresponda).pdf',
            ];
        }

        foreach ($dynamicFiles as $id => $nombre) {
            $rutaTemp = $tempDir . $nombre;
            $archivoGenerado = $this->Archivo($id_registro_pt, $id, $rutaTemp);
            if ($archivoGenerado && file_exists($archivoGenerado)) {
                $archivos[] = $archivoGenerado;
                $archivosTemporales[] = $archivoGenerado;
            }
        }


        // Archivo 07
        $rutaArchivo07 = $tempDir . '07 Formatos_diversos.pdf';
        $archivo07 = $this->ImprimirPT($id_registro_pt, $rutaArchivo07);
        if ($archivo07 && file_exists($archivo07)) {
            $archivos[] = $archivo07;
            $archivosTemporales[] = $archivo07;
        }

        // Archivos desde base de datos (PDFs permanentes)
        if (!empty($pdf_reserva->data)) {
            foreach ($pdf_reserva->data as $pdf) {
                $source = FCPATH . $pdf->ruta_relativa;
                if (file_exists($source)) {
                    $archivos[] = $source;
                    // ¡NO lo añadimos a archivosTemporales!
                }
            }
        }

        // Archivos subidos (05 al 09)
        $uploadedFiles = [
            'archivo05' => '05 Formatos de los LRADP.pdf',
            'archivo06' => '06 Oficios de Autorizaciones.pdf',
            'archivo08' => '08 Evidencia de entregable.pdf',
            'archivo09' => '09 Otros.pdf'
        ];
        foreach ($uploadedFiles as $input => $nombre) {
            $file = $this->request->getFile($input);
            if ($file && $file->isValid()) {
                $newPath = $tempDir . $nombre;
                if ($file->move($tempDir, $nombre)) {
                    $archivos[] = $newPath;
                    $archivosTemporales[] = $newPath;
                }
            }
        }

        if (empty($archivos)) {
            array_map('unlink', glob($tempDir . '*'));
            rmdir($tempDir);
            $response->error = true;
            $response->respuesta = 'No hay archivos para comprimir';
            return $this->respond($response);
        }

        // Crear ZIP
        $timestamp = date('Ymd_His');
        $zipPath = WRITEPATH . "temp_zip/Documentos_{$id_registro_pt}_{$timestamp}.zip";
        if (!is_dir(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0777, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            foreach ($archivos as $archivo) {
                $zip->addFile($archivo, basename($archivo));
            }
            $zip->close();
        }

        if (!file_exists($zipPath)) {
            $response->error = true;
            $response->respuesta = 'El archivo ZIP no se creó correctamente';
            return $this->respond($response);
        }

        // Borrar solo archivos temporales
        foreach ($archivosTemporales as $tempFile) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }

        if (is_dir($tempDir)) {
            rmdir($tempDir);
        }

        // Eliminar ZIP automáticamente al cerrar
        register_shutdown_function(function () use ($zipPath) {
            if (file_exists($zipPath)) {
                unlink($zipPath);
            }
        });

        return $this->response
            ->setHeader('Content-Type', 'application/zip')
            ->setHeader('Content-Disposition', 'attachment; filename="Documentos_' . $id_registro_pt . '.zip"')
            ->setBody(file_get_contents($zipPath));
    }
    public function generarZipGO()
    {
        $response = new \stdClass();
        $id_registro_go = $this->request->getPost('id_registro_go');
        $Mglobal = new Mglobal;

        if (empty($id_registro_go)) {
            $response->error = true;
            $response->respuesta = 'ID de registro inválido';
            return $this->respond($response);
        }
        // Consulta de PDFs asociados
        $pdf_reserva = $Mglobal->getTabla([
            'tabla' => 'vw_pdf_reserva_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_registro_go]
        ]);

        $id_reserva = $Mglobal->getTabla([
            'tabla' => 'registro_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_registro_go]
        ])->data[0]->id_reserva_go;

        // Directorio temporal
        $tempDir = sys_get_temp_dir() . '/zip_temp_' . $id_registro_go . '/';

        if (!is_dir($tempDir) && !mkdir($tempDir, 0777, true)) {
            $response->error = true;
            $response->respuesta = 'No se pudo crear directorio temporal';
            return $this->respond($response);
        }

        $archivos = [];
        $archivosTemporales = [];


        $dynamicFiles = [
            1 => '01 Anexos y formato de los LTPOFB.pdf'
        ];


        foreach ($dynamicFiles as $id => $nombre) {
            $rutaTemp = $tempDir . $nombre;
            $archivoGenerado = $this->Archivo($id_registro_go, $id, $rutaTemp);
            if ($archivoGenerado && file_exists($archivoGenerado)) {
                $archivos[] = $archivoGenerado;
                $archivosTemporales[] = $archivoGenerado;
            }
        }


        // Archivo 07
        $rutaArchivo07 = $tempDir . '07 Formatos_diversos.pdf';
        $archivo07 = $this->ImprimirPT($id_registro_go, $rutaArchivo07);
        if ($archivo07 && file_exists($archivo07)) {
            $archivos[] = $archivo07;
            $archivosTemporales[] = $archivo07;
        }

        // Archivos desde base de datos (PDFs permanentes)
        if (!empty($pdf_reserva->data)) {
            foreach ($pdf_reserva->data as $pdf) {
                $source = FCPATH . $pdf->ruta_relativa;
                if (file_exists($source)) {
                    $archivos[] = $source;
                    // ¡NO lo añadimos a archivosTemporales!
                }
            }
        }

        // Archivos subidos (05 al 09)
        $uploadedFiles = [
            'archivo05' => '05 Formatos de los LRADP.pdf',
            'archivo09' => '09 Otros.pdf'
        ];
        foreach ($uploadedFiles as $input => $nombre) {
            $file = $this->request->getFile($input);
            if ($file && $file->isValid()) {
                $newPath = $tempDir . $nombre;
                if ($file->move($tempDir, $nombre)) {
                    $archivos[] = $newPath;
                    $archivosTemporales[] = $newPath;
                }
            }
        }

        if (empty($archivos)) {
            array_map('unlink', glob($tempDir . '*'));
            rmdir($tempDir);
            $response->error = true;
            $response->respuesta = 'No hay archivos para comprimir';
            return $this->respond($response);
        }

        // Crear ZIP
        $timestamp = date('Ymd_His');
        $zipPath = WRITEPATH . "temp_zip/Documentos_{$id_registro_go}_{$timestamp}.zip";
        if (!is_dir(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0777, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            foreach ($archivos as $archivo) {
                $zip->addFile($archivo, basename($archivo));
            }
            $zip->close();
        }

        if (!file_exists($zipPath)) {
            $response->error = true;
            $response->respuesta = 'El archivo ZIP no se creó correctamente';
            return $this->respond($response);
        }

        // Borrar solo archivos temporales
        foreach ($archivosTemporales as $tempFile) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }

        if (is_dir($tempDir)) {
            rmdir($tempDir);
        }

        // Eliminar ZIP automáticamente al cerrar
        register_shutdown_function(function () use ($zipPath) {
            if (file_exists($zipPath)) {
                unlink($zipPath);
            }
        });

        return $this->response
            ->setHeader('Content-Type', 'application/zip')
            ->setHeader('Content-Disposition', 'attachment; filename="Documentos_' . $id_registro_go . '.zip"')
            ->setBody(file_get_contents($zipPath));
    }

    public function caratulaGo($id_go)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = [];
        $id_reserva = null;

        $facturaXml = $globals->getTabla([
            'tabla' => 'xml_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_go]
        ]);
        $facturaPdfGo = $globals->getTabla([
            'tabla' => 'factura_pdf_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_go]
        ]);
        $docAmpara = (isset($facturaXml->data) ? count($facturaXml->data) : 0);
      
        $registroGo = $globals->getTabla([
            'tabla' => 'vw_registro_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_go]
        ]);
        if(isset($registroGo->data) && empty($registroGo->data)){
            echo '<center>No se encontro el registro</center>';
            die();
        }
        //die(var_dump($registroGo));
        $prefijo = $globals->getTabla([
            'tabla' => 'cat_area',
            'where' => ['id_pago' => 1, 'id_area' => $registroGo->data[0]->id_direccion_responsable]
        ]);
         if (strlen($registroGo->data[0]->no_consecutivo) == 2) {
                $zero = '0';
            } elseif (strlen($registroGo->data[0]->no_consecutivo) == 1) {
                $zero = '00';
            } else {
                $zero = '';
            }
        $prefijoCompleto = $prefijo->data[0]->prefijo.$zero.$registroGo->data[0]->no_consecutivo.'/'.date('Y');
        $idReponsableSolicitud = $registroGo->data[0]->id_reponsable_solicitud;
        $data['idReponsableSolicitud'] = $idReponsableSolicitud;
        //die( var_dump( $prefijoCompleto ) );
        if(!in_array($idReponsableSolicitud,[56,101,60])){
           $data['prefijoCompleto'] = $prefijoCompleto;
        }else{
            if($idReponsableSolicitud == 56){
                $data['prefijoCompleto'] = 'SECTURI/DS/OPER/'.$zero.$registroGo->data[0]->no_consecutivo.'/'.date('Y');
            }else if($idReponsableSolicitud == 101){
                $data['prefijoCompleto'] = 'SECTURI/DS/SPRIV/'.$zero.$registroGo->data[0]->no_consecutivo.'/'.date('Y');;
            }else if($idReponsableSolicitud == 60){
                $data['prefijoCompleto'] = 'SECTURI/DS/SPART/'.$zero.$registroGo->data[0]->no_consecutivo.'/'.date('Y');
            }
        }
      
        $data['docAmpara'] = (int)$docAmpara;
        $data['fecha_tramite'] = $registroGo->data[0]->fecha_tramite;
        $idReservaGo = $registroGo->data[0]->id_reserva_go;
        $data['nombreSecretario'] = $registroGo->data[0]->secretario;
        $data['puestoSecretario'] = $registroGo->data[0]->dsc_puesto_secretario;
        $idSubsecretario = $registroGo->data[0]->id_subsecretario;
      

        //logica para ver el responsable de la solicitud
        //vedificasmos si tiene area a cargo
        if(!in_array($idReponsableSolicitud,[56,101,60])){

        
            $areaCargo = $globals->getTabla([
                'tabla' => 'cat_area',
                'where' => ['id_pago' => 1, 'titular' => $idReponsableSolicitud]
            ]);
            if(isset($areaCargo->data) && !empty($areaCargo->data)){
                $ReponsableSolicitud = $globals->getTabla([
                    'tabla' => 'vw_usuario',
                    'where' => ['visible' => 1, 'id_usuario' => $idReponsableSolicitud]
                ]);
            }else{
                $idJefe = $globals->getTabla([
                    'tabla' => 'vw_usuario',
                    'where' => ['visible' => 1, 'id_usuario' => $idReponsableSolicitud]
                ])->data[0]->id_jefe_inmediato;
                $areaTitular = $globals->getTabla([
                    'tabla' => 'cat_area',
                    'where' => ['id_pago' => 1, 'titular' => $idJefe]
                ]);
                if(empty($areaTitular->data)){
                    $idJefe2 = $globals->getTabla([
                        'tabla' => 'vw_usuario',
                        'where' => ['visible' => 1, 'id_usuario' => $idJefe]
                    ])->data[0]->id_jefe_inmediato;
                        $ReponsableSolicitud = $globals->getTabla([
                        'tabla' => 'vw_usuario',
                        'where' => ['visible' => 1, 'id_usuario' => $idJefe2]
                    ]);
                }else{
                    $ReponsableSolicitud = $globals->getTabla([
                        'tabla' => 'vw_usuario',
                        'where' => ['visible' => 1, 'id_usuario' => $idJefe]
                    ]);
                }
            }
        }
        //die( var_dump(  ) );
        $subsecretario = $globals->getTabla([
            'tabla' => 'cat_subsecretario',
            'where' => ['visible' => 1, 'id_subsecretario' => $idSubsecretario]
        ]);
        $data['nombreResponsable'] = $subsecretario->data[0]->dsc_subsecretario;
        $data['puestoResponsable'] = $subsecretario->data[0]->puesto;
        $data['nombreReponsableSolicitud'] = isset($ReponsableSolicitud->data[0]->nombre_completo) ? $ReponsableSolicitud->data[0]->nombre_completo : '';
        $data['puestoReponsableSolicitud'] = isset($ReponsableSolicitud->data[0]->dsc_puesto) ? $ReponsableSolicitud->data[0]->dsc_puesto : '';
 //die(var_dump($data['docAmpara']));
        $presupuestoGO = $globals->getTabla([
            'tabla' => 'vw_presupuesto_go',
            'where' => ['visible' => 1, 'id_reserva' => $idReservaGo]
        ]);
        $data['presupuestoGO'] = $presupuestoGO->data;
        $data['facturaXml'] = $facturaXml->data;
        $data['facturaPdfGo'] = $facturaPdfGo->data;

        $total_importe = 0;
        // Obtener la relación de filas (periodo_factura_go) para enlazar XML con Presupuesto
        $periodoFacturaGo = $globals->getTabla([
            'tabla' => 'periodo_factura_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_go]
        ]);

        // Mapas de búsqueda
        $identificadorToPresupuesto = [];
        $identificadorToPropina = []; // Nuevo: Mapa para propina
        if (isset($periodoFacturaGo->data)) {
            foreach ($periodoFacturaGo->data as $fila) {
                $identificadorToPresupuesto[$fila->id_identificador] = $fila->id_presupuesto;
                $identificadorToPropina[$fila->id_identificador]     = $fila->propina;
            }
        }

        $presupuestoToPartida = [];
        $presupuestoToProyecto = []; // Nuevo: Mapa para Proyecto
        if (isset($data['presupuestoGO'])) {
            foreach ($data['presupuestoGO'] as $presupuesto) {
                $presupuestoToPartida[$presupuesto->id_presupuesto_go]  = $presupuesto->dsc_partida;
                $presupuestoToProyecto[$presupuesto->id_presupuesto_go] = $presupuesto->proyecto;
            }
        }

        // Construir lista plana enriquecida
        $listaFacturas = [];
        $total_importe = 0; // Inicializar
        
        foreach ($data['facturaXml'] as $factura) {
            $rawId = $factura->id_identificador;
            
            // Valores por defecto
            $partida = 'Desconocida';
            $proyecto = 'Desconocido';
            $propina = 0;
            
            if (isset($identificadorToPresupuesto[$rawId])) {
                $idPresupuesto = $identificadorToPresupuesto[$rawId];
                $propina       = $identificadorToPropina[$rawId] ?? 0; // Obtener propina correctamente por ID
                
                if (isset($presupuestoToPartida[$idPresupuesto])) {
                    $partida  = $presupuestoToPartida[$idPresupuesto];
                    $proyecto = $presupuestoToProyecto[$idPresupuesto] ?? '';
                }
            }
            
            // Calcular importe total (Total XML + Propina)
            $importeTotal = (float)str_replace([',','$'], '', $factura->total) + (float)$propina;
            $total_importe += $importeTotal; // Acumular

            $listaFacturas[] = [
                'comprobante'   => $factura->folio ?: $factura->uuid, // Usa Folio o UUID si no hay folio
                'proyecto'      => $proyecto,
                'partida'       => $partida,
                'importe'       => $importeTotal, // Formato numérico
                'contribuyente' => $factura->emisor_nombre,
                'rfc'           => $factura->emisor_rfc,
                'objeto_xml'    => $factura // Mantener objeto original por si acaso
            ];
        }

        // Asignar totales calculados
        $data['total_importe'] = $total_importe;
        $data['numero_texto'] = $this->numeroEnLetras($total_importe);
        
        // Ordenar: Primero por Partida (DESC para coincidir con el ejemplo 3790 luego 2210?), luego por Comprobante
        usort($listaFacturas, function($a, $b) {
            // Ordenar por Partida Descendente (ej. 3790 antes de 2210)
            $res = strcmp($b['partida'], $a['partida']); 
            if ($res == 0) {
                // Si la partida es igual, ordenar por Comprobante Ascendente
                return strcmp($a['comprobante'], $b['comprobante']);
            }
            return $res;
        });
        
        $data['listaOrdenada'] = $listaFacturas;

        //die( var_dump( $data['listaOrdenada'] ) );
        $html = view('secciones/vCaratulaGO.php', $data);


        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

  
        $templateFile = 'assets/pdf/plantillas/formatoGO2.pdf';

        // --- HOJA 1 ---
        $mpdf->SetSourceFile(FCPATH . $templateFile);
        $tplId = $mpdf->ImportPage(1);
        $mpdf->UseTemplate($tplId);
        $mpdf->WriteHTML($html);

        
        $mpdf->Output('Formato_pt.pdf', 'I');
        exit();

        //die(var_dump($data['listaOrdenada']));
        // var_dump( $periodo_factura );
    }

    public function oficioLiberacion($id_go)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = [];
        $id_reserva = null;

        $registroGo = $globals->getTabla([
            'tabla' => 'vw_registro_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_go]
        ]);
        if(isset($registroGo->data) && empty($registroGo->data)){
            echo '<center>No se encontro el registro</center>';
            die();
        }

        $prefijo = $globals->getTabla([
            'tabla' => 'cat_area',
            'where' => ['id_pago' => 1, 'id_area' => $registroGo->data[0]->id_direccion_responsable]
        ]);
        $concepto = $globals->getTabla([
            'tabla' => 'periodo_factura_go',
            'where' => ['id_registro_go' => $id_go]
        ])->data[0]->concepto;
           $data['concepto'] = $concepto;
         if (strlen($registroGo->data[0]->no_consecutivo) == 2) {
                $zero = '0';
            } elseif (strlen($registroGo->data[0]->no_consecutivo) == 1) {
                $zero = '00';
            } else {
                $zero = '';
            }
        $prefijoCompleto = $prefijo->data[0]->prefijo.$zero.$registroGo->data[0]->no_consecutivo.'/'.date('Y');
        $data['prefijoCompleto'] = $prefijoCompleto;
        $data['fecha_tramite'] = $registroGo->data[0]->fecha_tramite;
        $idReservaGo = $registroGo->data[0]->id_reserva_go;
        $data['nombreSecretario'] = $registroGo->data[0]->secretario;
        $data['puestoSecretario'] = $registroGo->data[0]->dsc_puesto_secretario;
        $idSubsecretario = $registroGo->data[0]->id_subsecretario;
        $idReponsableSolicitud = $registroGo->data[0]->id_reponsable_solicitud;

        //logica para ver el responsable de la solicitud
        //vedificasmos si tiene area a cargo
        $areaCargo = $globals->getTabla([
            'tabla' => 'cat_area',
            'where' => ['id_pago' => 1, 'titular' => $idReponsableSolicitud]
        ]);
        if(isset($areaCargo->data) && !empty($areaCargo->data)){
            $ReponsableSolicitud = $globals->getTabla([
                'tabla' => 'vw_usuario',
                'where' => ['visible' => 1, 'id_usuario' => $idReponsableSolicitud]
            ]);
        }else{
            $idJefe = $globals->getTabla([
                'tabla' => 'vw_usuario',
                'where' => ['visible' => 1, 'id_usuario' => $idReponsableSolicitud]
            ])->data[0]->id_jefe_inmediato;
            $areaTitular = $globals->getTabla([
                'tabla' => 'cat_area',
                'where' => ['id_pago' => 1, 'titular' => $idJefe]
            ]);
            if(empty($areaTitular->data)){
                $idJefe2 = $globals->getTabla([
                    'tabla' => 'vw_usuario',
                    'where' => ['visible' => 1, 'id_usuario' => $idJefe]
                ])->data[0]->id_jefe_inmediato;
                    $ReponsableSolicitud = $globals->getTabla([
                    'tabla' => 'vw_usuario',
                    'where' => ['visible' => 1, 'id_usuario' => $idJefe2]
                ]);
            }else{
                $ReponsableSolicitud = $globals->getTabla([
                    'tabla' => 'vw_usuario',
                    'where' => ['visible' => 1, 'id_usuario' => $idJefe]
                ]);
            }
        }
       
        $subsecretario = $globals->getTabla([
            'tabla' => 'cat_subsecretario',
            'where' => ['visible' => 1, 'id_subsecretario' => $idSubsecretario]
        ]);
        $data['nombreResponsable'] = $subsecretario->data[0]->dsc_subsecretario;
        $data['puestoResponsable'] = $subsecretario->data[0]->puesto;
        $data['nombreReponsableSolicitud'] = isset($ReponsableSolicitud->data[0]->nombre_completo) ? $ReponsableSolicitud->data[0]->nombre_completo : '';
        $data['puestoReponsableSolicitud'] = isset($ReponsableSolicitud->data[0]->dsc_puesto) ? $ReponsableSolicitud->data[0]->dsc_puesto : '';
        
        $presupuestoGO = $globals->getTabla([
            'tabla' => 'vw_presupuesto_go',
            'where' => ['visible' => 1, 'id_reserva' => $idReservaGo]
        ]);
        $data['presupuestoGO'] = $presupuestoGO->data;
  
        //Calcular total importe (sumando facturas)
        $facturaXml = $globals->getTabla([
            'tabla' => 'xml_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_go]
        ]);
        $data['facturaXml'] = $facturaXml->data; // Needed for total calculation
        
        $total_importe = 0;
        if(isset($data['facturaXml'])){
            foreach ($data['facturaXml'] as $factura) {
                $total_importe += $factura->total;
            }
        }
        $data['total_importe'] = $total_importe;
        $data['numero_texto'] = $this->numeroEnLetras($total_importe);
        $data['registro'] = $registroGo->data[0];
        $data['registro']->folio = $prefijoCompleto;
        $data['responsableGasto'] = (isset($ReponsableSolicitud->data[0])) ? $ReponsableSolicitud->data[0] : null;
        $data['GO'] = true;
        $data['fic'] = false;

        $html = view('secciones/vFormatoGO2.php', $data);

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

        $templateFile = 'assets/pdf/plantillas/formatoGO2.pdf';

        // --- HOJA 2 ---
        $mpdf->SetSourceFile(FCPATH . $templateFile);
        $tplId = $mpdf->ImportPage(2);
        $mpdf->UseTemplate($tplId);
        $mpdf->WriteHTML($html);

        $mpdf->Output('Oficio_Liberacion.pdf', 'I');
        exit();
    }



    public function ImprimirGO($id_pt = null, $hoja = null, $index = null, $savePath = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = [];

        // 1. Obtener Registro GO y Responsable
        $registro_go = $globals->getTabla(['tabla' => 'vw_registro_go', 'where' => ['visible' => 1, 'id_registro_go' => $id_pt]]);
        if (empty($registro_go->data)) {
            echo '<h2>Error al encontrar registro.</h2>'; die();
        }
        $data['registro'] = $registro_go->data[0];
        // die( var_dump( $registro_go->data[0] ) );
        $id_reponsable_solicitud = $registro_go->data[0]->id_reponsable_solicitud;
        $data['nombre_responsable'] = "";
        $data['puesto_responsable'] = "";
        $data['area_responsable']   = "";
        if(isset($id_reponsable_solicitud) && !empty($id_reponsable_solicitud)){

             $res = $globals->getTabla(['tabla' => 'vw_usuario', 'where' =>["id_usuario" => $id_reponsable_solicitud ] ]);
             $data['nombre_responsable'] = $res->data[0]->nombre_completo;
             $data['puesto_responsable'] = $res->data[0]->dsc_puesto;
             $data['area_responsable']   = $res->data[0]->dsc_area;
            //die( var_dump( $res ) );
        }

       

        // 2. Obtener Factura (XML) específica por índice
        $xml_go = $globals->getTabla(['tabla' => 'xml_go', 'where' => ['visible' => 1, 'id_registro_go' => $id_pt]]);
        
        if ($index === null || !isset($xml_go->data[$index])) {
             // Fallback: Si no hay index, tomar el primero si existe, sino error o vacío
             if(!empty($xml_go->data)) {
                 $index = 0;
             } else {
                 echo '<h2>No hay facturas asociadas para imprimir.</h2>'; die();
             }
        }
        $facturaItem = $xml_go->data[$index];
        $data['uuid'] = ($facturaItem->folio) ? $facturaItem->folio : $facturaItem->uuid;

        // 3. Obtener Datos del Periodo/Partida
        $periodo_factura_go = $globals->getTabla(['tabla' => 'periodo_factura_go', 'where' => ['visible' => 1, 'id_registro_go' => $id_pt]]);
        // Importante: Asumimos correspondencia por orden. Mejor sería por id_identificador si ambos lo tienen.
        // Revisando estructura: xml_go tiene id_identificador y periodo_factura_go tiene id_identificador.
        // Vamos a buscar el periodo que coincida con el id_identificador de la factura.
        
        $periodo = null;
        if (!empty($periodo_factura_go->data)) {
            foreach($periodo_factura_go->data as $p) {
                if ($p->id_identificador == $facturaItem->id_identificador) {
                    $periodo = $p;
                    break;
                }
            }
        }
        // Fallback por índice si no se encontró por identificador (compatibilidad)
        if (!$periodo && isset($periodo_factura_go->data[$index])) {
            $periodo = $periodo_factura_go->data[$index];
        }

        // Recuperar nombre de partida
        $nombre_partida = '';
        if ($periodo) {
             $presupuestoGO = $globals->getTabla(['tabla' => 'vw_periodo_factura_go', 'where' => ['visible' => 1, 'id_reserva' => $data['registro']->id_reserva_go]]);
             // Buscar en presupuestos el que coincida con id_presupuesto
             if (!empty($presupuestoGO->data)) {
                 foreach($presupuestoGO->data as $presup) {
                     // Nota: id_presupuesto en periodo es FK a cat_presupuesto/partida?
                     // Asumiremos que vw_periodo_factura_go tiene la info. 
                     // Simplificación: usaremos el mismo índice si es consistente, o busqueda.
                     // Dado el modelo anterior, parece que vw_periodo_factura_go trae todo, pero periodo_factura_go es la tabla raw.
                     // Intentaremos obtener el nombre de la partida directamente de cat_partida si tenemos id
                     // O mejor, el usuario dijo "cuenta_cable".
                     if(isset($presup->id_presupuesto_go) && $presup->id_presupuesto_go == $periodo->id_presupuesto) {
                          $nombre_partida = $presup->dsc_partida; // o cuenta_cable
                          break;
                     }
                 }
                 // Si no se encontró, fallback a index
                 if (empty($nombre_partida) && isset($presupuestoGO->data[$index])) {
                      $nombre_partida = $presupuestoGO->data[$index]->dsc_partida;
                 }
             }
        }
        $data['partida'] = $nombre_partida;


        // 4. Mapear datos a la vista
        if ($periodo) {
            $importe_str = $facturaItem->total; 
            $importe_float = (float) str_replace(',', '', $importe_str);
            
            $data['inicio'] = $periodo->periodo_inicio;
            $data['fin']    = $periodo->periodo_fin;
            $data['encabezado'] = $periodo->encabezado;
            $data['concepto'] = (isset($periodo->concepto)) ? $periodo->concepto : ''; // NUEVO CAMPO
            $data['comision'] = (isset($periodo->comision)) ? $periodo->comision : ''; // NUEVO CAMPO COMISION
            $data['total'] = $facturaItem->total;
            
            $monto          = $importe_float + (float)$periodo->propina;
            $data['total2']  = $monto;
            $data['monto2']  = $this->numeroEnLetras($monto);
        } else {
             $data['encabezado'] = '';
             $data['concepto'] = '';
             $data['total'] = $facturaItem->total;
             $data['monto'] = $this->numeroEnLetras((float) str_replace(',', '', $facturaItem->total));
             $data['total2'] = '';
             $data['monto2'] = '';
        }

        // 5. Renderizar PDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0, 'margin_left' => 1, 'margin_right' => 1,
            'format' => [213, 268], 'mirrorMargins' => false,
        ]);

        $html = view('personal/vFormato702GO', $data);
        $mpdf->WriteHTML($html);

        // 6. Adjuntar PDF Factura
        if (isset($facturaItem->id_identificador)) {
             $factura_pdf_go = $globals->getTabla([
                'tabla' => 'factura_pdf_go',
                'where' => [
                    'visible' => 1,
                    'id_registro_go' => $id_pt,
                    'id_identificador' => $facturaItem->id_identificador
                ]
            ]);

            if (!empty($factura_pdf_go->data)) {
                 $pdfRegistro = $factura_pdf_go->data[0];
                 $facturaPath = FCPATH . $pdfRegistro->ruta_relativa;
                 
                 if (file_exists($facturaPath)) {
                      $pageCount = $mpdf->SetSourceFile($facturaPath);
                      // Solo importamos la primera página para ponerla "debajo" si cabe
                      // Si hay más páginas, quizás deban ir en hojas nuevas, pero el requerimiento dice "misma hoja".
                      // Asumiremos que se quiere visualizar la factura principal allí.
                      
                      $tplId = $mpdf->ImportPage(1);
                      $size = $mpdf->GetTemplateSize($tplId);
                      
                      // Coordenadas: Debajo del formato (aprox Y=60mm)
                      $yPos = 65; 
                      $xPos = 10;
                      
                      // Calcular escala para ajustar al ancho disponible o alto disponible
                      // Ancho disponible: 213 - 20 (borde) = 193
                      // Alto disponible: 268 - 65 (top) - 10 (bottom) = 193
                      
                      $maxWidth = 190;
                      $maxHeight = 200;
                      
                      $width = $size['width'];
                      $height = $size['height'];
                      
                      // Escalar si excede ancho
                      if ($width > $maxWidth) {
                          $ratio = $maxWidth / $width;
                          $width = $maxWidth;
                          $height = $height * $ratio;
                      }
                      
                      // Escalar si excede alto (después de ajustar ancho)
                      if ($height > $maxHeight) {
                           $ratio = $maxHeight / $height;
                           $height = $maxHeight;
                           $width = $width * $ratio;
                      }
                      
                      // Centrar X
                      $xPos = (213 - $width) / 2;

                      $mpdf->UseTemplate($tplId, $xPos, $yPos, $width, $height);
                 }
            }
        }

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Formato702GO.pdf', 'I');
    }
    public function ImprimirTicket($id_pt = null, $hoja = null, $index = null, $savePath = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = [];

        // 1. Obtener Registro GO y Responsable
        $registro_go = $globals->getTabla(['tabla' => 'vw_registro_go', 'where' => ['visible' => 1, 'id_registro_go' => $id_pt]]);
        if (empty($registro_go->data)) {
            echo '<h2>Error al encontrar registro.</h2>'; die();
        }
        $data['registro'] = $registro_go->data[0];
        // die( var_dump( $registro_go->data[0] ) );
        $id_reponsable_solicitud = $registro_go->data[0]->id_reponsable_solicitud;
        $data['nombre_responsable'] = "";
        $data['puesto_responsable'] = "";
        $data['area_responsable']   = "";
        if(isset($id_reponsable_solicitud) && !empty($id_reponsable_solicitud)){

             $res = $globals->getTabla(['tabla' => 'vw_usuario', 'where' =>["id_usuario" => $id_reponsable_solicitud ] ]);
             $data['nombre_responsable'] = $res->data[0]->nombre_completo;
             $data['puesto_responsable'] = $res->data[0]->dsc_puesto;
             $data['area_responsable']   = $res->data[0]->dsc_area;
            //die( var_dump( $res ) );
        }

       

        // 2. Obtener Factura (XML) específica por índice
        $xml_go = $globals->getTabla(['tabla' => 'xml_go', 'where' => ['visible' => 1, 'id_registro_go' => $id_pt]]);
        
        if ($index === null || !isset($xml_go->data[$index])) {
             // Fallback: Si no hay index, tomar el primero si existe, sino error o vacío
             if(!empty($xml_go->data)) {
                 $index = 0;
             } else {
                 echo '<h2>No hay facturas asociadas para imprimir.</h2>'; die();
             }
        }
        $facturaItem = $xml_go->data[$index];
        $data['uuid'] = ($facturaItem->folio) ? $facturaItem->folio : $facturaItem->uuid;

        // 3. Obtener Datos del Periodo/Partida
        $periodo_factura_go = $globals->getTabla(['tabla' => 'periodo_factura_go', 'where' => ['visible' => 1, 'id_registro_go' => $id_pt]]);
        // Importante: Asumimos correspondencia por orden. Mejor sería por id_identificador si ambos lo tienen.
        // Revisando estructura: xml_go tiene id_identificador y periodo_factura_go tiene id_identificador.
        // Vamos a buscar el periodo que coincida con el id_identificador de la factura.
        
        $periodo = null;
        if (!empty($periodo_factura_go->data)) {
            foreach($periodo_factura_go->data as $p) {
                if ($p->id_identificador == $facturaItem->id_identificador) {
                    $periodo = $p;
                    break;
                }
            }
        }
        // Fallback por índice si no se encontró por identificador (compatibilidad)
        if (!$periodo && isset($periodo_factura_go->data[$index])) {
            $periodo = $periodo_factura_go->data[$index];
        }

        // Recuperar nombre de partida
        $nombre_partida = '';
        if ($periodo) {
             $presupuestoGO = $globals->getTabla(['tabla' => 'vw_periodo_factura_go', 'where' => ['visible' => 1, 'id_reserva' => $data['registro']->id_reserva_go]]);
             // Buscar en presupuestos el que coincida con id_presupuesto
             if (!empty($presupuestoGO->data)) {
                 foreach($presupuestoGO->data as $presup) {
                     // Nota: id_presupuesto en periodo es FK a cat_presupuesto/partida?
                     // Asumiremos que vw_periodo_factura_go tiene la info. 
                     // Simplificación: usaremos el mismo índice si es consistente, o busqueda.
                     // Dado el modelo anterior, parece que vw_periodo_factura_go trae todo, pero periodo_factura_go es la tabla raw.
                     // Intentaremos obtener el nombre de la partida directamente de cat_partida si tenemos id
                     // O mejor, el usuario dijo "cuenta_cable".
                     if(isset($presup->id_presupuesto_go) && $presup->id_presupuesto_go == $periodo->id_presupuesto) {
                          $nombre_partida = $presup->dsc_partida; // o cuenta_cable
                          break;
                     }
                 }
                 // Si no se encontró, fallback a index
                 if (empty($nombre_partida) && isset($presupuestoGO->data[$index])) {
                      $nombre_partida = $presupuestoGO->data[$index]->dsc_partida;
                 }
             }
        }
        $data['partida'] = $nombre_partida;


        // 4. Mapear datos a la vista
        if ($periodo) {
            $importe_str = $facturaItem->total; 
            $importe_float = (float) str_replace(',', '', $importe_str);
            
            $data['inicio'] = $periodo->periodo_inicio;
            $data['fin']    = $periodo->periodo_fin;
            $data['encabezado'] = $periodo->encabezado;
            $data['concepto'] = (isset($periodo->concepto)) ? $periodo->concepto : ''; // NUEVO CAMPO
            $data['comision'] = (isset($periodo->comision)) ? $periodo->comision : ''; // NUEVO CAMPO COMISION
            $data['total'] = $facturaItem->total;
            
            $monto          = $importe_float + (float)$periodo->propina;
            $data['total2']  = $monto;
            $data['monto2']  = $this->numeroEnLetras($monto);
        } else {
             $data['encabezado'] = '';
             $data['concepto'] = '';
             $data['total'] = $facturaItem->total;
             $data['monto'] = $this->numeroEnLetras((float) str_replace(',', '', $facturaItem->total));
             $data['total2'] = '';
             $data['monto2'] = '';
        }

        // 5. Renderizar PDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0, 'margin_left' => 1, 'margin_right' => 1,
            'format' => [213, 268], 'mirrorMargins' => false,
        ]);

        $html = view('personal/vFormato702GO', $data);
        $mpdf->WriteHTML($html);

      

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Formato702GO.pdf', 'I');
    }
    public function avanceActividad()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error al validar usuario";
        $globals = new Mglobal;
        $data = $this->request->getPost();

        $dataConfig = [
            "tabla" => "actividad",
            "editar" => true,
            "idEditar" => ['id_actividad' => $data['id_actividad']]
        ];
        $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Agregar.php/guardaTurno'];
        $response = $globals->saveTabla(["avance" => $data['avance']], $dataConfig, $dataBitacora);
        return $this->respond($response);


    }
    public function ListaDenuncia()
    {
        $session = \Config\Services::session();
        $data = array();
        $globals = new Mglobal;
        $data['denuncia'] = $globals->getTabla(["tabla" => "denuncia", "where" => ["visible" => 1]])->data;
        $data['usuario'] = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["visible" => 1]])->data;
        $data['scripts'] = array('principal', 'inicio');
        $data['contentView'] = 'personal/vListaDenuncia';
        $this->_renderView($data);
    }
    public function ImprimirPT($id_pt = null, $hoja = null, $index = null, $savePath = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = [];
        $id_reserva = null;

        $registro_pt = $globals->getTabla([
            'tabla' => 'vw_registro_pt',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_pt]
        ]);

        $formatos = $globals->getTabla([
            'tabla' => 'vw_pdf_reserva',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_pt]
        ]);
        $xml = $globals->getTabla([
            'tabla' => 'factura',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_pt]
        ]);
        $presupuesto = $globals->getTabla([
            'tabla' => 'vw_pagos',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_pt]
        ]);
        $periodo_factura = $globals->getTabla([
            'tabla' => 'vw_periodo_factura',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_pt]
        ]);
       
        $importe = '';
        $total = 0;
        if (isset($xml->data) && !empty($xml->data)) {
           
            $data['uuid'] = $xml->data;
            foreach ($xml->data as $f) {
                $total += (float) $f->total; 
            }
        }
       // die( var_dump($periodo_factura) );
        if (isset($presupuesto->data) && !empty($presupuesto->data)) {
            $data['presupuesto'] = $presupuesto->data;
        }
       
      
       if (isset($periodo_factura->data) && !empty($periodo_factura->data)) {
            $data['periodo_factura'] = $periodo_factura->data;
            $data['periodo_inicio'] = $periodo_factura->data[0]->periodo_inicio;
            $data['periodo_fin'] = $periodo_factura->data[0]->periodo_fin;
            $data['concepto'] = $periodo_factura->data[0]->concepto;
            $registros = count($periodo_factura->data);
        }

       $data['suma'] = $total;
       $data['suma_texto'] = $this->numeroEnLetras($total);

        // Filter Data for Specific Index if provided
        if ($index !== null) {
             if (isset($xml->data) && isset($xml->data[$index])) {
                  $data['uuid'] = [$xml->data[$index]];
                  $monto = (float)$xml->data[$index]->total;
                  $data['suma'] = $monto;
                  $data['suma_texto'] = $this->numeroEnLetras($monto);
             }
             if (isset($periodo_factura->data) && isset($periodo_factura->data[$index])) {
                 // Reset keys to 0 for view consumption
                 $data['periodo_factura'] = array_values([$periodo_factura->data[$index]]);
             }
        }

       
        $data['GO'] = false;
        $data['fic'] = false;
        $data['dividido'] = 0;
        if (!empty($registro_pt->data)) {
            $data['total']      = $registro_pt->data;
            $registro           = $registro_pt->data[0];
            $id_reserva         = $registro_pt->data[0]->id_reserva;
            $dividido           =  $registro_pt->data[0]->dividido;
            $no_consecutivo     = $registro_pt->data[0]->no_consecutivo;
            $id_proveedor_banco = $registro_pt->data[0]->id_proveedor_banco; 
            $importe = $registro_pt->data[0]->total_importe; 
            $banco = $globals->getTabla([
                'tabla' => 'proveedor_banco',
                'where' => [
                    'id_proveedor_banco' => $id_proveedor_banco
                ]
            ]);
  
            if (isset($banco->data) && !empty($banco->data)) {
                $data['no_cuenta'] = $banco->data[0]->no_cuenta;
                $data['clabe'] = $banco->data[0]->clabe;
                $data['banco'] = $banco->data[0]->banco;

            }

            $data['registro'] = $registro;

            $direccion = $globals->getTabla([
                'tabla' => 'vw_direccion',
                'where' => [
                    'visible' => 1,
                    'id_director' => $registro_pt->data[0]->id_reponsable_solicitud
                ]
            ]);
         // die( var_dump($direccion->data) );
            if (empty($direccion->data)) {
                $jefe = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $registro_pt->data[0]->id_reponsable_solicitud]]);
                if (!empty($jefe->data)) {
                    $idJefe = $jefe->data[0]->id_jefe_inmediato;
                    $direccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1, 'id_director' => $idJefe]]);
                } else {
                    $area = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $registro_pt->data[0]->id_reponsable_solicitud]]);
                    $direccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1, 'id_area' => $area->data[0]->id_area]]);
                }

            }
            
            $data['responsableGasto'] = (isset($direccion->data) && !empty($direccion->data)) ? $direccion->data[0] : '';
            $reserva = $globals->getTabla([
                'tabla' => 'vw_reserva',
                'where' => ['visible' => 1, 'id_reserva' => $id_reserva]
            ]);

            if (strlen($no_consecutivo) == 2) {
                $zero = '0';
            } elseif (strlen($no_consecutivo) == 1) {
                $zero = '00';
            } else {
                $zero = '';
            }
         /*    if (!empty($direccion->data)) {
                $folio_prefijo = $direccion->data[0]->folio_prefijo . $zero . $no_consecutivo . '/' . date('Y'); 
                $data['registro']->folio = $folio_prefijo;
            } else {
               
                $data['registro']->folio = '';
            } */
            //var_dump($registro_pt->data);
             if (!empty($registro_pt->data[0]->id_direccion_responsable)) {
                $prefijo = $globals->getTabla([
                    'tabla' => 'cat_area',
                    'where' => ['visible' => 1, 'id_area' => $registro_pt->data[0]->id_direccion_responsable ]
                ]);
           
                $folio_prefijo = (isset($prefijo->data) && !empty($prefijo->data))?$prefijo->data[0]->prefijo . $zero . $no_consecutivo . '/' . date('Y'):''; //ESTO HAY QUE OREGUNTAR
                $data['registro']->folio = $folio_prefijo;
            } else {
                $data['registro']->folio = ''; // O un valor por defecto
            }
             //die();
        } else {
            echo '<h2>Error al encontrar registro, favor de revisar el id del registro PT</h2>';
            die();
        }
          if (!empty($reserva->data)) {
                $data['reserva'] = $reserva->data;
                $data['no_convenio'] = $reserva->data[0]->no_convenio;
                $importe_str = ( $data['fic'] )?$reserva->data[0]->total_importe:$importe;
                $usu_reg = $reserva->data[0]->usu_reg;
                $importe_float = (float) str_replace(',', '', $importe_str);
                $data['numero_texto'] = $this->numeroEnLetras($importe_float);
            }
           $data['nombre_registro'] = $globals->getTabla([
                'tabla' => 'vw_usuario',
                'where' => ['id_usuario' => $usu_reg]
            ])->data[0];
       
        $subsecretario = $area = $globals->getTabla(['tabla' => 'cat_subsecretario', 'where' => ['visible' => 1, 'id_subsecretario' => $registro_pt->data[0]->id_subsecretario]]);
        $data['usu_sub'] = $subsecretario->data[0];
        // die( var_dump( $data['usu_sub']  ) );
        if($registros >= 15){
            $html = view('secciones/vFormatoPTExtra.php', $data);
            $templateFile = 'assets/pdf/plantillas/FormatoPTExtra_merged.pdf';
        }else{
            $html = view('secciones/vFormatoPT.php', $data);
            $templateFile = 'assets/pdf/plantillas/anexo07_2_2026.pdf';
        }
        
        $htmlSegundaHoja = view('secciones/vFormatoPT2.php', $data);
        $htmlTercerHoja = view('personal/vFormato702.php', $data);

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

        // Logic to select pages
        $runAll = ($hoja == null);

        // --- HOJA 1 ---
        if ($runAll || $hoja == 1) {
            $mpdf->SetSourceFile(FCPATH . $templateFile);
            $mpdf->AddPage();
            $tplId = $mpdf->ImportPage(1);
            $mpdf->UseTemplate($tplId);
            $mpdf->WriteHTML($html);
        }

        // --- HOJA 2 ---
        if ($runAll || $hoja == 2) {
            // Need to reload/ensure source if we skipped H1
            if (!$runAll) {
                 $mpdf->SetSourceFile(FCPATH . $templateFile);
            }
            $mpdf->AddPage();
            $tplId = $mpdf->ImportPage(2);
            $mpdf->UseTemplate($tplId);
            $mpdf->WriteHTML($htmlSegundaHoja);
        }

        // --- HOJA 3 (Facturas/Soporte) ---
        if ($runAll || $hoja == 3) {
             
             // Si es solo Hoja 3 y no RunAll, NO imprimimos la segunda hoja (ya filtrada arriba).
             // Pero la lógica original ejecutaba esto "if ($i == 2)".
             
             if ($dividido == 0) {
                 // Estructura original: escribía HTML3 sobre la primera factura
                 // Simular la lógica original de facturas
                 $facturas = $formatos->data;
                 if ($index !== null && isset($facturas[$index])) {
                    $facturas = [$index => $facturas[$index]];
                 }

                 if (!empty($facturas)) {
                    foreach ($facturas as $index => $factura) {
                        $facturaPath = FCPATH . $factura->ruta_relativa;
                        if (file_exists($facturaPath)) {
                            
                            // 1. Prepare SPECIFIC data for this invoice (Header)
                            // We treat it visually as 'dividido = 1' for the header content
                            $dataLoop = $data;
                            $dataLoop['dividido'] = 1; 

                            // Map UUID and Amount
                            if (isset($xml->data) && isset($xml->data[$index])) {
                                $dataLoop['uuid2'] = $xml->data[$index]->uuid;
                                $montoIndex = (float)$xml->data[$index]->total;
                                $dataLoop['total2'] = $montoIndex;
                                $dataLoop['monto2'] = $this->numeroEnLetras($montoIndex);
                            } else {
                                $dataLoop['uuid2'] = '';
                                $dataLoop['total2'] = 0;
                                $dataLoop['monto2'] = '';
                            }

                            // Map Dates / Periodo
                            if (isset($periodo_factura->data) && isset($periodo_factura->data[$index])) {
                                $dataLoop['fecha_gasto_inicio'] = $periodo_factura->data[$index]->periodo_inicio;
                                $dataLoop['fecha_gasto_fin'] = $periodo_factura->data[$index]->periodo_fin;
                                $dataLoop['partida2'] = $periodo_factura->data[$index]->partida; // Assuming partida is here
                                $dataLoop['registro']->concepto_pago = $periodo_factura->data[$index]->concepto;
                            } else {
                                // Fallback to global or leaving empty if not available specific
                                $dataLoop['fecha_gasto_inicio'] = $data['periodo_inicio'] ?? '';
                                $dataLoop['fecha_gasto_fin'] = $data['periodo_fin'] ?? '';
                                // Try to get Partida from Budget if available by index
                                if(isset($presupuesto->data) && isset($presupuesto->data[$index])) {
                                     $dataLoop['partida2'] = $presupuesto->data[$index]->partida . ' ' . $presupuesto->data[$index]->dsc_partida;
                                } else {
                                     $dataLoop['partida2'] = '';
                                }
                            }
                            
                            // Render specific header
                            $htmlSpecific = view('personal/vFormato702.php', $dataLoop);

                            $facturaPageCount = $mpdf->SetSourceFile($facturaPath);
                            for ($j = 1; $j <= $facturaPageCount; $j++) {
                                $mpdf->AddPage();
                                $tplFactura = $mpdf->ImportPage($j);
                                
                                // Write Header on the FIRST page of this invoice
                                if ($j === 1) {
                                    $mpdf->WriteHTML($htmlSpecific);
                                }
                                
                                // Escalar factura
                                $templateSize = $mpdf->GetTemplateSize($tplFactura);
                                $scaleFactor = 0.6; 
                                $width = $templateSize['width'] * $scaleFactor;
                                $height = $templateSize['height'] * $scaleFactor;
                                $mpdf->UseTemplate($tplFactura, 40, 55, $width, $height);
                            }
                        }
                    }
                }
             } elseif ($dividido == 1) {
               // Lógica de dividido = 1
                 $data['dividido'] = 1;
                 $facturas = $formatos->data;
                 if ($index !== null && isset($facturas[$index])) {
                    $facturas = [$index => $facturas[$index]];
                 }

                 if (!empty($facturas)) {
                        foreach ($facturas as $index => $facturaItem) {
                          $data['partida2']           =  $periodo_factura->data[$index]->partida;
                          $data['fecha_gasto_inicio'] =  $periodo_factura->data[$index]->periodo_inicio;
                          $data['fecha_gasto_fin']    =  $periodo_factura->data[$index]->periodo_fin;
                          $data['registro']->concepto_pago = $periodo_factura->data[$index]->concepto;
                         // die( var_dump($data['registro']->concepto_pago) );
                          $data['uuid2']              = $xml->data[$index]->uuid;
                          $data['total2'] = "";
                          $data['monto2'] = "";
                                $monto = (int)$xml->data[$index]->total ;
                                $data['total2'] = $monto;
                                $data['monto2'] = $this->numeroEnLetras($monto);

                            // 1️⃣ Agregamos una sola página con el formato 702GO
                            // Re-render view with updated data inside loop
                            $htmlTercerHojaLoop = view('personal/vFormato702.php', $data); 
                            $mpdf->AddPage();
                            $mpdf->WriteHTML($htmlTercerHojaLoop);
                            
                            // 4️⃣ Insertamos las facturas
                                $facturaPath = FCPATH . $facturaItem->ruta_relativa;
                                if (file_exists($facturaPath)) {
                                    $facturaPageCount = $mpdf->SetSourceFile($facturaPath);
                                    for ($pageNum = 1; $pageNum <= $facturaPageCount; $pageNum++) {
                                        
                                        $yPos = 10;
                                        if($pageNum == 1){
                                            $yPos = 55;
                                        }else{
                                            $mpdf->AddPage(); // Separar cada hoja
                                        }

                                        $tplFactura = $mpdf->ImportPage($pageNum);
                                        $templateSize = $mpdf->GetTemplateSize($tplFactura);
                                        $scaleFactor = 0.6;
                                        $width = $templateSize['width'] * $scaleFactor;
                                        $height = $templateSize['height'] * $scaleFactor;
                                        $xPos = ($mpdf->w - $width) / 2;
                                        //$yPos = 40; 
                                        $mpdf->UseTemplate($tplFactura, $xPos, $yPos, $width, $height);
                                    }
                                }
                        }
                    }
             }
        }

        if ($savePath) {
            $mpdf->Output($savePath, 'F'); 
            return $savePath;
        }

        $mpdf->Output('Formato_pt.pdf', 'I');
        exit();

    }
    public function ImprimirFIC($id_pt = null, $savePath = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = [];
        $id_reserva = null;
       
        $registro_pt = $globals->getTabla([
            'tabla' => 'vw_registro_pt',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_pt]
        ]);
        $formatos = $globals->getTabla([
            'tabla' => 'vw_pdf_reserva',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_pt]
        ]);

        $xml = $globals->getTabla([
            'tabla' => 'factura',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_pt]
        ]);
        

        if (isset($xml->data) && !empty($xml->data)) {
            $data['uuid'] = $xml->data;

        }
         
     // die( var_dump( $registro_pt ) );
        $data['FIC'] = false;
        $data['GO'] = false;
        if (!empty($registro_pt->data)) {
            $registro = $registro_pt->data[0];
            $id_reserva = $registro_pt->data[0]->id_reserva;
            $no_consecutivo = $registro_pt->data[0]->no_consecutivo;
            $data['registro'] = $registro;
            $presupuestoPT = $globals->getTabla([
                    'tabla' => 'vw_presupuesto',
                    'where' => ['visible' => 1, 'id_reserva' => $registro_pt->data[0]->id_reserva]
            ]);
         
            //validar si yo tengo folio 
            $direccion = $globals->getTabla([
                'tabla' => 'vw_direccion',
                'where' => [
                    'visible' => 1,
                    //'id_director' => 110
                    'id_director' => $registro_pt->data[0]->id_reponsable_solicitud
                ]
            ]);


            if (empty($direccion->data)) {
                $jefe = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $registro_pt->data[0]->id_reponsable_solicitud]]);
                if (!empty($jefe->data)) {
                    $idJefe = $jefe->data[0]->id_jefe_inmediato;
                    $direccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1, 'id_director' => $idJefe]]);
                } else {
                    $area = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $registro_pt->data[0]->id_reponsable_solicitud]]);
                    $direccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1, 'id_area' => $area->data[0]->id_area]]);
                }

            }
            //die( var_dump($registro_pt->data[0]) );
            // $folio = $direccion; //ESTO HAY QUE OREGUNTAR

            $data['responsableGasto'] = (isset($direccion->data) && !empty($direccion)) ? $direccion->data[0] : '';
            $reserva = $globals->getTabla([
                'tabla' => 'vw_reserva',
                'where' => ['visible' => 1, 'id_reserva' => $id_reserva]
            ]);
            
             
            if (!empty($reserva->data)) {
                $data['reserva'] = $reserva->data;
                $data['presupuesto'] = $reserva->data;
       
                $importe_str = $reserva->data[0]->total_importe;
                $usu_reg = $reserva->data[0]->usu_reg;
                $data['no_convenio'] = $reserva->data[0]->no_convenio;
                $importe_float = (float) str_replace(',', '', $importe_str); // quita coma y convierte
                $data['numero_texto'] = $this->numeroEnLetras($importe_float);
            }
            
            $data['nombre_registro'] = $globals->getTabla([
                'tabla' => 'vw_usuario',
                'where' => ['id_usuario' => $usu_reg]
            ])->data[0];
            if (strlen($no_consecutivo) == 2) {
                $zero = '0';
            } elseif (strlen($no_consecutivo) == 1) {
                $zero = '00';
            } else {
                $zero = '';
            }

            if (!empty($registro_pt->data)) {
              
                $data['registro'] = $registro_pt->data[0];
              //  die( var_dump(  $data['registro'] ) );
                $data['fic'] = false;
                if ($registro_pt->data[0]->no_reserva == '4327278') {
                    $data['folio'] = "SECTURI/DGDT/DCT/FIC-TH/" . $zero . $no_consecutivo . '/2025';
                    $data['fic'] = true;
                } else if ($registro_pt->data[0]->no_reserva == '4327277') {
                    $data['folio'] = "SECTURI/DGDT/DCT/FIC-TA/" . $zero . $no_consecutivo . '/2025';
                    $data['fic'] = true;
                }

            } else {
                echo '<h2>Error al encontrar registro, favor de revisar el id del registro PT</h2>';
                die();
            }
        }


        $subsecretario = $area = $globals->getTabla(['tabla' => 'cat_subsecretario', 'where' => ['visible' => 1, 'id_subsecretario' => $registro_pt->data[0]->id_subsecretario]]);
        // $usu_sub = $area = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $subsecretario->data[0]->id_usuario]]);
        $data['usu_sub'] = $subsecretario->data[0];
        //die( var_dump( $data ) );
        $html = view('secciones/vFormatoPT.php', $data);
        $htmlSegundaHoja = view('secciones/vFormatoPT2.php', $data);
        $htmlTercerHoja = view('personal/vFormato702.php', $data);

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

        // Importar PDF base (anexo07)

        $pagecount = $mpdf->SetSourceFile(FCPATH . 'assets/pdf/plantillas/anexo07_2.pdf');
         for ($i = 1; $i <= $pagecount; $i++) {
            $mpdf->AddPage();
            $tplId = $mpdf->ImportPage($i);
            $mpdf->UseTemplate($tplId);

            if ($i == 1) {
                $mpdf->WriteHTML($html);
            }

            if ($i == 2) {
                $mpdf->WriteHTML($htmlSegundaHoja);
                $facturas = $formatos->data;
             
                if (!empty($facturas)) {
                   
                   
                        foreach ($facturas as $index => $facturaItem) {
                      //   die( var_dump( $facturaItem ) );
                          $data['partida'] =  $presupuestoPT->data[$index]->dsc_partida;
                          $data['uuid'] =   $xml->data[$index]->uuid;
                            
                            $data['facturaItem'] = $facturaItem;
                          
                            $importe_str = $presupuestoPT->data[$index]->importe;
                            $importe_float = (float) str_replace(',', '', $importe_str);
                            $data['numero_texto2'] = $this->numeroEnLetras($importe_float);
                            $data['importePartida'] = $presupuestoPT->data[$index]->importe;

                            
                            // 1️⃣ Agregamos una sola página con el formato 702GO
                            $htmlTercerHoja = view('personal/vFormato702FIC.php', $data);
                            $mpdf->AddPage();
                            $mpdf->WriteHTML($htmlTercerHoja);

                            // 2️⃣ Obtenemos las facturas relacionadas
                            $factura_pdf = $globals->getTabla([
                                'tabla' => 'factura_pdf',
                                'where' => [
                                    'visible' => 1,
                                    'id_registro_pt' => $id_pt
                                ]
                            ]);
                     
                        
                            //die();
                            $facturas = isset($factura_pdf->data) && !empty($factura_pdf->data)
                                ? $factura_pdf->data
                                : [];
                            
                          
                            // 3️⃣ Posición inicial (debajo del contenido del formato)
                           $currentY = $mpdf->y + 60;

                            // 4️⃣ Insertamos las facturas una debajo de otra
                                
                                $facturaPath = FCPATH . $facturas[$index]->ruta_relativa;

                                if (file_exists($facturaPath)) {
                                    $facturaPageCount = $mpdf->SetSourceFile($facturaPath);

                                    for ($pageNum = 1; $pageNum <= $facturaPageCount; $pageNum++) {
                                        $tplFactura = $mpdf->ImportPage($pageNum);
                                        $templateSize = $mpdf->GetTemplateSize($tplFactura);

                                        $scaleFactor = 0.6;
                                        $width = $templateSize['width'] * $scaleFactor;
                                        $height = $templateSize['height'] * $scaleFactor;

                                        // 📍 Si no cabe en la hoja actual, saltamos a una nueva
                                        if ($currentY + $height > $mpdf->h - 10) {
                                            $mpdf->AddPage();
                                            $currentY = 10;
                                        }

                                        // Centrar horizontalmente
                                        $xPos = ($mpdf->w - $width) / 2;

                                        // Insertamos la página de factura
                                        $mpdf->UseTemplate($tplFactura, $xPos, $currentY, $width, $height);

                                        // Avanzamos la posición Y para la siguiente
                                        $currentY += $height + 10;
                                    }
                                }
                            
                        }
                    }



            }
        }

      /*   for ($i = 1; $i <= $pagecount; $i++) {
            $mpdf->AddPage();
            $tplId = $mpdf->ImportPage($i);
            $mpdf->UseTemplate($tplId);

            if ($i == 1) {
                $mpdf->WriteHTML($html);
            }
            if ($i == 2) {
                $mpdf->WriteHTML($htmlSegundaHoja);
                $facturas = $formatos->data;

                if (!empty($facturas)) {
                    foreach ($facturas as $index => $factura) {
                        $facturaPath = FCPATH . $factura->ruta_relativa;

                        if (file_exists($facturaPath)) {
                            $facturaPageCount = $mpdf->SetSourceFile($facturaPath);

                            for ($j = 1; $j <= $facturaPageCount; $j++) {
                                $mpdf->AddPage();
                                $tplFactura = $mpdf->ImportPage($j);

                                // Escribir HTML solo en la primera página de la primera factura
                                if ($index === 0 && $j === 1) {
                                    $mpdf->WriteHTML($htmlTercerHoja);
                                }

                                // Escalar factura
                                $templateSize = $mpdf->GetTemplateSize($tplFactura);
                                $scaleFactor = 0.6; // ajusta si es necesario
                                $width = $templateSize['width'] * $scaleFactor;
                                $height = $templateSize['height'] * $scaleFactor;

                                $mpdf->UseTemplate($tplFactura, 40, 55, $width, $height);
                            }
                        }
                    }
                }

            }
        } */

        if ($savePath) {
            $mpdf->Output($savePath, 'F'); // F = write to file
            return $savePath;
        }

        $mpdf->Output('Formato_pt.pdf', 'I');
        exit();

    }

    public function ImprimirVPT($id_pt = null, $savePath = null)
    {
      $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = [];
        $id_reserva = null;

   

        $vehiculo = $globals->getTabla([
            'tabla' => 'pt_vehiculo',
            'where' => ['visible' => 1, 'id_vehiculo' => $id_pt]
        ]);

      
        if (isset($vehiculo->data) && !empty($vehiculo->data)) {
            $data['vehiculo'] = $vehiculo->data[0];
            $importe_str = $vehiculo->data[0]->xml_monto;
            $importe_float = (float) str_replace(',', '', $importe_str); // quita coma y convierte
            $data['numero_texto'] = $this->numeroEnLetras($importe_float);
            
            $solicitud = $globals->getTabla([
                'tabla' => 'vw_usuario',
                'where' => ['visible' => 1, 'id_usuario' =>  $vehiculo->data[0]->id_responsable]
            ]);
          
           
            $proyecto = $globals->getTabla([
                'tabla' => 'cat_proyecto',
                'where' => ['visible' => 1, 'id_proyecto' =>  $vehiculo->data[0]->id_proyecto]
            ]);
            $secretario = $globals->getTabla([
                'tabla' => 'cat_secretario',
                'where' => ['visible' => 1, 'id_secretario' =>  $vehiculo->data[0]->id_secretario]
            ]);
            $resposableGasto = $globals->getTabla([
                'tabla' => 'vw_usuario',
                'where' => ['visible' => 1, 'id_usuario' =>  $vehiculo->data[0]->id_responsable_gasto]
            ]);
    
            
               $no_consecutivo = "";
            if (strlen($vehiculo->data[0]->no_consecutivo) == 1) {
                $no_consecutivo = '00' . $vehiculo->data[0]->no_consecutivo;
            }
            if (strlen($vehiculo->data[0]->no_consecutivo) == 2) {
                $no_consecutivo = '0' . $vehiculo->data[0]->no_consecutivo;
            }
            if (strlen($vehiculo->data[0]->no_consecutivo) >= 3) {
                $no_consecutivo = $vehiculo->data[0]->no_consecutivo;
            }

             $proveedor = $globals->getTabla([
                'tabla' => 'proveedor',
                'where' => ['visible' => 1, 'id_proveedor' =>  $vehiculo->data[0]->id_proveedor]
            ]);
             $proveedorBanco = $globals->getTabla([
                'tabla' => 'proveedor_banco',
                'where' => ['visible' => 1, 'id_proveedor_banco' =>  $vehiculo->data[0]->id_proveedor_banco]
            ]);

         
            $data['folio'] = $vehiculo->data[0]->folio;
            $data['proveedor'] = (isset($proveedor->data) && !empty($proveedor->data) )?$proveedor->data[0]->razon_social:'';
            $data['no_proveedor'] = (isset($proveedor->data) && !empty($proveedor->data) )?$proveedor->data[0]->no_proveedor:'';
            $data['rfc'] = (isset($proveedor->data) && !empty($proveedor->data) )?$proveedor->data[0]->rfc:'';
            $data['proveedorBanco'] = isset($proveedorBanco->data) && !empty($proveedorBanco->data)?$proveedorBanco->data[0]:'';
            $data['proyecto'] = (isset( $proyecto->data) && !empty( $proyecto->data))? $proyecto->data[0]:'';
            $data['secretario'] = (isset( $secretario->data) && !empty( $secretario->data))? $secretario->data[0]:'';
            $data['solicitud'] = (isset( $solicitud->data) && !empty( $solicitud->data))? $solicitud->data[0]:'';
            $data['resposableGasto'] = (isset( $resposableGasto->data) && !empty( $resposableGasto->data))? $resposableGasto->data[0]:'';
        }

        $html = view('secciones/vFormatoVI.php', $data);
        $htmlSegundaHoja = view('secciones/vFormatoVI2.php', $data);
        $htmlTercerHoja = view('personal/vFormatoVI702.php', $data);

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

        // Importar PDF base (anexo07)

        $pagecount = $mpdf->SetSourceFile(FCPATH . 'assets/pdf/plantillas/anexo07_2.pdf');

        for ($i = 1; $i <= $pagecount; $i++) {
            $mpdf->AddPage();
            $tplId = $mpdf->ImportPage($i);
            $mpdf->UseTemplate($tplId);

            if ($i == 1) {
                $mpdf->WriteHTML($html);
            }
            if ($i == 2) {
                $mpdf->WriteHTML($htmlSegundaHoja);
                $facturas = $vehiculo->data;

                if (!empty($facturas)) {
                   
                    foreach ($facturas as $index => $factura) {
                        $facturaPath = FCPATH . $factura->pdf;

                        if (file_exists($facturaPath)) {
                            $facturaPageCount = $mpdf->SetSourceFile($facturaPath);

                            for ($j = 1; $j <= $facturaPageCount; $j++) {
                                $mpdf->AddPage();
                                $tplFactura = $mpdf->ImportPage($j);

                                // Escribir HTML solo en la primera página de la primera factura
                                if ($index === 0 && $j === 1) {
                                    $mpdf->WriteHTML($htmlTercerHoja);
                                }

                                // Escalar factura
                                $templateSize = $mpdf->GetTemplateSize($tplFactura);
                                $scaleFactor = 0.6; // ajusta si es necesario
                                $width = $templateSize['width'] * $scaleFactor;
                                $height = $templateSize['height'] * $scaleFactor;

                                $mpdf->UseTemplate($tplFactura, 40, 55, $width, $height);
                            }
                        }
                    }
                }

            }
        }

        if ($savePath) {
            $mpdf->Output($savePath, 'F'); // F = write to file
            return $savePath;
        }

        $mpdf->Output('Formato_pt.pdf', 'I');
        exit();

    }

    public function generarFormatoPT($id_reserva = null, $edita = null, $anio = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = array();
        
        $data['id_reserva'] = $id_reserva;
        $data['editar'] = $edita;
        $data['es2025'] = ($anio == '2025')?true:false;
        $data['registro_pt'] = new stdClass();
        $data['periodo_factura'] = new stdClass();
        $data['proveedor'] = new stdClass();
        $data['proveedor_banco'] = []; // Initialized as array to match view expectations

  
        $data['no_consecutivo'] = '';

        // Default values to avoid errors in view
        $data['periodo_factura']->encabezado = '';
        $data['periodo_factura']->proyecto_clave = ''; 
        $data['periodo_factura']->partida_clave = '';
        $data['periodo_factura']->importe = '';

         $data['no_reserva']="";
         $data['no_convenio']="";
         if($data['es2025']){
            $formulario_pt = $globals->getTabla(["tabla" => "formulario_pt", "where" => ["visible" => 1, 'usu_reg' => $session->get('id_usuario')], 'tipo_formato' => 'REFRENDO']);
         }else{
            $formulario_pt = $globals->getTabla(["tabla" => "formulario_pt", "where" => ["visible" => 1, 'usu_reg' => $session->get('id_usuario')], 'tipo_formato' => 'PT']);
         }
            $no_consecutivo = count($formulario_pt->data) + 1 ;
            if (strlen($no_consecutivo) == 1) {
                $no_consecutivo = '00' . $no_consecutivo;
            }
            if (strlen($no_consecutivo) == 2) {
                $no_consecutivo = '0' . $no_consecutivo;
            }
            if (strlen($no_consecutivo) >= 3) {
                $no_consecutivo = $no_consecutivo;
            }
          $data['consecutivo'] = $no_consecutivo;

        $proveedores = $globals->getTabla(["tabla" => "proveedor", "where" => ["visible" => 1],'limit' => 10]);
        $data['proveedores'] = $proveedores->data;

        $cat_area = $globals->getTabla(["tabla" => "cat_area", "where" => ["visible" => 1, 'id_pago' => 1]]);
        $data['cat_area'] = $cat_area->data;
        if($data['es2025']){
            $cat_proyecto = $globals->getTabla(["tabla" => "cat_proyecto", "where" => ["servicios" => 1]]);
        }else{
            $cat_proyecto = $globals->getTabla(["tabla" => "cat_proyecto", "where" => ["visible" => 1]]);
        }
        $data['cat_proyecto'] = $cat_proyecto->data;

        $cat_partida = $globals->getTabla(["tabla" => "cat_partida", "where" => ["visible" => 1]]);
        $data['cat_partida'] = $cat_partida->data;

        $usuarios = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["visible" => 1]]);
        $data['usuarios'] = $usuarios->data;
        $usu = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["visible" => 1, 'id_usuario' => $session->get('id_usuario')]]);


        if($usu->data[0]->id_usuario){
            $tieneArea = $globals->getTabla(["tabla" => "cat_area", "where" => ["visible" => 1, 'titular' => $usu->data[0]->id_usuario]]);
            if(isset($tieneArea->data) && !empty($tieneArea->data)){
                $data['id_area'] = $tieneArea->data[0]->id_area;
            }else{
                $tieneArea = $globals->getTabla(["tabla" => "cat_area", "where" => ["visible" => 1, 'titular' => $usu->data[0]->id_jefe_inmediato]]);
                if(isset($tieneArea->data) && !empty($tieneArea->data)){
                    $data['id_area'] = $tieneArea->data[0]->id_area;
                }else{
                        $data['id_area'] = $cat_area->data[0]->id_area;
                }
            }
        }

        if($id_reserva){

            $registro_pt = $globals->getTabla(["tabla" => "reserva", "where" => ["id_reserva" => $id_reserva, "visible" => 1]]);
            $data['no_reserva'] = (!empty($anio) && $anio == '2025')?'':$registro_pt->data[0]->no_reserva;
            $data['no_convenio'] =  (!empty($anio) && $anio == '2025')?'':$registro_pt->data[0]->no_convenio;
            $data['id_proveedor'] = $registro_pt->data[0]->id_proveedor;
            $data['id_proveedor_banco'] = $registro_pt->data[0]->id_proveedor_banco;
            // Fetch Provider Data (Handle ID or Name)
            $provIdOrName = $data['id_proveedor'];
            if(!empty($provIdOrName)){
                $whereProv = [];
                if(is_numeric($provIdOrName)){
                    $whereProv = ["id_proveedor" => $provIdOrName];
                } else {
                    $whereProv = ["razon_social" => $provIdOrName];
                }

                 $prov = $globals->getTabla(["tabla" => "proveedor", "where" => $whereProv]);
                 if(!empty($prov->data)){
                      $data['proveedor'] = $prov->data[0];
                      $realIdProveedor = $data['proveedor']->id_proveedor;

                      // Ensure in dropdown list
                      $inList = false;
                      if (!empty($data['proveedores'])) {
                           foreach ($data['proveedores'] as $pList) {
                               if ($pList->id_proveedor == $realIdProveedor) {
                                   $inList = true;
                                   break;
                               }
                           }
                      }
                      if (!$inList) {
                          $data['proveedores'][] = $data['proveedor'];
                      }
                 }
            }
            
            // Fetch Bank Data (Linked or Default)
            if(!empty($data['id_proveedor_banco'])){
                 $banco = $globals->getTabla(["tabla" => "proveedor_banco", "where" => ["id_proveedor_banco" => $data['id_proveedor_banco']]]);
                 if(!empty($banco->data)) $data['proveedor_banco'] = $banco->data[0];
            } elseif(isset($realIdProveedor)) {
                 // Fallback to default bank
                 $banco = $globals->getTabla(["tabla" => "proveedor_banco", "where" => ["idproveedor" => $realIdProveedor, "fic" => 1]]);
                 if(!empty($banco->data)) $data['proveedor_banco'] = $banco->data[0];
            }



        }
        
        $data['scripts'] = array('principal');
        $data['contentView'] = 'secciones/vFormatoPagoTerceros';
        $this->_renderView($data);
    }
    public function FormatoMateriales()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = array();
        $data['editar'] = 0;
      
         
        $proveedores = $globals->getTabla(["tabla" => "proveedor", "where" => ["visible" => 1],'limit' => 10]);
        $data['proveedores'] = $proveedores->data;

        $cat_area = $globals->getTabla(["tabla" => "cat_area", "where" => ["visible" => 1, 'id_pago' => 1]]);
        $data['cat_area'] = $cat_area->data;
       
        $cat_proyecto = $globals->getTabla(["tabla" => "cat_proyecto", "where" => ["visible" => 1]]);
        
        $data['cat_proyecto'] = $cat_proyecto->data;

        $cat_partida = $globals->getTabla(["tabla" => "cat_partida", "where" => ["visible" => 1]]);
        $data['cat_partida'] = $cat_partida->data;

        $usuarios = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["visible" => 1]]);
        $data['usuarios'] = $usuarios->data;
        $usu = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["visible" => 1, 'id_usuario' => $session->get('id_usuario')]]);


        if($usu->data[0]->id_usuario){
            $tieneArea = $globals->getTabla(["tabla" => "cat_area", "where" => ["visible" => 1, 'titular' => $usu->data[0]->id_usuario]]);
            if(isset($tieneArea->data) && !empty($tieneArea->data)){
                $data['id_area'] = $tieneArea->data[0]->id_area;
            }else{
                $tieneArea = $globals->getTabla(["tabla" => "cat_area", "where" => ["visible" => 1, 'titular' => $usu->data[0]->id_jefe_inmediato]]);
                if(isset($tieneArea->data) && !empty($tieneArea->data)){
                    $data['id_area'] = $tieneArea->data[0]->id_area;
                }else{
                        $data['id_area'] = $cat_area->data[0]->id_area;
                }
            }
        }


        
        $data['scripts'] = array('principal');
        $data['contentView'] = 'secciones/vFormatoMateriales';
        $this->_renderView($data);
    }
    public function generarFormatoGO($id_reserva = null, $edita = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = array();
        
        $data['id_reserva'] = $id_reserva;
        $data['editar'] = $edita;

        if ($id_reserva === null) {
            $id_reserva = $this->request->getGet('id');
            $data['id_reserva'] = $id_reserva;
        }
        if ($edita === null) {
            $edita = $this->request->getGet('editar');
            $data['editar'] = $edita;
        }
        $data['registro_pt'] = new stdClass();
        $data['periodo_factura'] = new stdClass();
        $data['proveedor'] = new stdClass();
        $data['proveedor_banco'] = []; 
        $data['periodo_factura_rows'] = [];

        if ($edita && $id_reserva) {
             $registro = $globals->getTabla(["tabla" => "formulario_pt", "where" => ["id_formulario_pt" => $id_reserva]]);
             if (!empty($registro->data)) {
                 $data['registro_pt'] = $registro->data[0];
                 $items = $globals->getTabla(["tabla" => "manual_factura", "where" => ["id_registro_pt" => $id_reserva, "visible" => 1]]);
                 $data['periodo_factura_rows'] = $items->data;
                 
                 // Fetch Global Viaticos
                 $viatGlobal = $globals->getTabla(["tabla" => "viaticos_go", "where" => ["id_registro_go" => $id_reserva, "visible" => 1]]);
                 $vListGlobal = [];
                 if(!empty($viatGlobal->data)){
                     foreach($viatGlobal->data as $vg){
                         $vListGlobal[] = ['nombre' => $vg->nombre, 'monto' => $vg->importe, 'rfc' => $vg->rfc];
                     }
                 }
                 $data['registro_pt']->viaticos_json = !empty($vListGlobal) ? json_encode($vListGlobal) : '[]';
                 
                  // Fetch provider data
                 if(isset($data['registro_pt']->nombre_proveedor_1) && is_numeric($data['registro_pt']->nombre_proveedor_1) && $data['registro_pt']->nombre_proveedor_1 > 0){
                      $prov = $globals->getTabla(["tabla" => "proveedor", "where" => ["id_proveedor" => $data['registro_pt']->nombre_proveedor_1]]);
                      if(!empty($prov->data)) $data['proveedor'] = $prov->data[0];
                       $bancos = $globals->getTabla(["tabla" => "proveedor_banco", "where" => ["id_proveedor" => $data['registro_pt']->nombre_proveedor_1, "visible" => 1]]);
                       if(!empty($bancos->data)) $data['proveedor_banco'] = $bancos->data[0];
                 }
                 // If provider info is manual (text), we rely on $items which have provider details per row.
                 // But $data['proveedor'] might be needed for header/footer if used.
             }
        }

        //die( var_dump(   $data['periodo_factura_rows'] ) );
        $data['no_consecutivo'] = '';

        // Default values to avoid errors in view
        $data['periodo_factura']->encabezado = '';
        $data['periodo_factura']->proyecto_clave = ''; 
        $data['periodo_factura']->partida_clave = '';
        $data['periodo_factura']->importe = '';

         $data['no_reserva']="";
         $data['no_convenio']="";

          $formulario_pt = $globals->getTabla(["tabla" => "formulario_pt", "where" => ["visible" => 1, 'usu_reg' => $session->get('id_usuario')]]);
            $no_consecutivo = count($formulario_pt->data) + 1 ;
            if (strlen($no_consecutivo) == 1) {
                $no_consecutivo = '00' . $no_consecutivo;
            }
            if (strlen($no_consecutivo) == 2) {
                $no_consecutivo = '0' . $no_consecutivo;
            }
            if (strlen($no_consecutivo) >= 3) {
                $no_consecutivo = $no_consecutivo;
            }
          $data['consecutivo'] = $no_consecutivo;

        $proveedores = $globals->getTabla(["tabla" => "proveedor", "where" => ["visible" => 1],'limit' => 10]);
        $data['proveedores'] = $proveedores->data;

        $cat_area = $globals->getTabla(["tabla" => "cat_area", "where" => ["visible" => 1, 'id_pago' => 1]]);
        $data['cat_area'] = $cat_area->data;
        
        $cat_proyecto = $globals->getTabla(["tabla" => "cat_proyecto", "where" => ["visible" => 1]]);
        $data['cat_proyecto'] = $cat_proyecto->data;

        $cat_partida = $globals->getTabla(["tabla" => "cat_partida", "where" => ["visible" => 1]]);
        $data['cat_partida'] = $cat_partida->data;

        $usuarios = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["visible" => 1]]);
        $data['usuarios'] = $usuarios->data;
        $usu = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["visible" => 1, 'id_usuario' => $session->get('id_usuario')]]);


        if($usu->data[0]->id_usuario){
            $tieneArea = $globals->getTabla(["tabla" => "cat_area", "where" => ["visible" => 1, 'titular' => $usu->data[0]->id_usuario]]);
            if(isset($tieneArea->data) && !empty($tieneArea->data)){
                $data['id_area'] = $tieneArea->data[0]->id_area;
            }else{
                $tieneArea = $globals->getTabla(["tabla" => "cat_area", "where" => ["visible" => 1, 'titular' => $usu->data[0]->id_jefe_inmediato]]);
                if(isset($tieneArea->data) && !empty($tieneArea->data)){
                    $data['id_area'] = $tieneArea->data[0]->id_area;
                }else{
                        $data['id_area'] = $cat_area->data[0]->id_area;
                }
            }
        }
       
        //die( var_dump( $data['id_area'] ) );
   
        
        $data['scripts'] = array('principal');
        $data['contentView'] = 'secciones/vFormatoGastosOperacion';
        $this->_renderView($data);
    }

    public function obtenerBancosProveedor()
    {
        $idProveedor = $this->request->getGet('id_proveedor');
        $globals = new Mglobal;
        $response = new \stdClass();

        if (empty($idProveedor)) {
            return $this->respond(['bancos' => []]);
        }  
        $proveedorBanco = $globals->getTabla([
            'tabla' => 'proveedor_banco',
            'where' => ['visible' => 1, 'idproveedor' => $idProveedor],
        ]);
             
        // Tu modelo para obtener bancos
        $bancos = (isset($proveedorBanco->data) && !empty( $proveedorBanco->data))?$proveedorBanco->data:'';
    
        
        return $this->respond($bancos);
    }

    public function buscarProveedor()
    {
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedores';
        $termino = $this->request->getPost('termino');
        $globals = new Mglobal;

        $like = [
            'razon_social' => "%$termino%",
            'rfc' => "%$termino%",
            'no_proveedor' => "%$termino%"
        ];

        $proveedor = $globals->getTabla([
            'tabla' => 'proveedor',
            'where' => ['visible' => 1],
            'orlike' => $like,
            'limit' => 50
        ]);

        if (isset($proveedor->data) && !empty($proveedor->data)) {
            $response->error = $proveedor->error;
            $response->respuesta = $proveedor->respuesta;
            $response->data = $proveedor->data;

        }
        return $this->respond($response);


    }
  public function buscarProveedor2()
    {
       $termino = $this->request->getGet('q'); // Cambia a 'term'
        // Si no hay término, devolvemos una lista vacía para evitar errores
        if (empty($termino)) {
            return $this->respond(['results' => []]);
        }

        $globals = new Mglobal;

        // Ajustamos la búsqueda a solo el término, usando la función like de tu modelo
        // Opcional: mantienes la búsqueda en múltiples campos como lo tenías, lo cual es bueno.
        $like = [
            'razon_social' => "%$termino%",
            'rfc' => "%$termino%",
            'no_proveedor' => "%$termino%"
        ];

        $proveedor = $globals->getTabla([
            'tabla' => 'proveedor',
            'where' => ['visible' => 1],
            'orlike' => $like,
            'limit' => 20 // ¡RECOMENDADO! Un límite más bajo (10-20) mejora la UX.
        ]);
       
        // Inicializamos el array de resultados para Select2
        $resultados_select2 = [];

        // Verificamos si la consulta fue exitosa
        if (isset($proveedor->data) && !empty($proveedor->data)) {
            
            // 2. Mapear al formato que Select2 necesita: {id: valor, text: texto_a_mostrar}
            foreach ($proveedor->data as $a) {
                $resultados_select2[] = [
                    // 'id' debe ser el valor que guardas (el ID del proveedor)
                    'id' => $a->id_proveedor, 
                    // 'text' es lo que el usuario ve (la razón social)
                    'text' => $a->razon_social .' / '.$a->rfc .' / '.$a->no_proveedor
                ];
            }
        }
        
        // 3. Devolver la respuesta en la estructura final: {results: [..., ...]}
        // Si hubo un error o no se encontraron datos, 'results' será un array vacío: []
        $final_response = [
            'results' => $resultados_select2
        ];

        // Devolvemos la respuesta JSON
        // Asegúrate de que $this->respond() devuelva una respuesta JSON correcta.
        return $this->respond($final_response); 
    }
    public function Proveedor()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedor';
        $globals = new Mglobal;
        $id_proveedor = $this->request->getPost('id_proveedor');
        $fic = $this->request->getPost('fic');


        $data = [];
        if (!empty($id_proveedor)) {
            $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1, 'id_proveedor' => $id_proveedor]]);
            $banco = $globals->getTabla(['tabla' => 'proveedor_banco', 'where' => ['idproveedor' => $id_proveedor, 'visible' => 1]]);
           // var_dump($banco);
            //die();
            if (isset($banco->data[0]) && !empty($banco->data[0])) {
                if ($session->get('id_perfil') != 1) {
                    if (empty($banco->data[0]->no_cuenta) || empty($banco->data[0]->clabe)) {
                        $response->error = true;
                        $response->respuesta = 'El proveedor no tiene No. de cuenta y/o clabe, favor de solIcitar un Tiket a la área TI';
                        return $this->respond($response);
                    }
                }

            }
            $response->error = $proveedor->error;
            $response->respuesta = $proveedor->respuesta;
            $response->data['proveedor'] = (isset($proveedor->data[0]) && !empty($proveedor->data[0])) ? $proveedor->data[0] : [];
            $response->data['banco'] = (isset($banco->data[0]) && !empty($banco->data[0])) ? $banco->data : [];


        }

        return $this->respond($response);

    }
    public function editarReservaGo()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedor';
        $globals = new Mglobal;
        $id_reserva_go = $this->request->getPost('id_reserva_go');
        $data = [];
        if (!empty($id_reserva_go)) {
            $reserva = $globals->getTabla(['tabla' => 'vw_reserva_go', 'where' => ['visible' => 1, 'id_reserva_go' => $id_reserva_go]]);
            $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
            $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
            $response->error = $reserva->error;
            $response->respuesta = $reserva->respuesta;
            $reservaData = (isset($reserva->data[0]) && !empty($reserva->data[0])) ? $reserva->data[0] : [];
            if (!empty($reservaData)) {
                $reservaData->instrumento_urls = $this->mapInstrumentoUrls($reservaData->instrumento ?? null);
            }
            $response->data['reserva'] = $reservaData;
            $response->data['presupuesto'] = (isset($reserva->data[0]) && !empty($reserva->data[0])) ? $reserva->data : [];
            $response->data['proyecto'] = (isset($cat_proyecto->data[0]) && !empty($cat_proyecto->data[0])) ? $cat_proyecto->data : [];
            $response->data['partida'] = (isset($cat_partida->data[0]) && !empty($cat_partida->data[0])) ? $cat_partida->data : [];
        }

        return $this->respond($response);

    }
    public function editarReserva()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedor';
        $globals = new Mglobal;
        $id_reserva = $this->request->getPost('id_reserva');
        if($session->get('id_usuario') == 80){
            $this->enviarEmail(1);
        }
        $data = [];
        if (!empty($id_reserva)) {
            $reserva = $globals->getTabla(['tabla' => 'vw_reserva', 'where' => ['visible' => 1, 'id_reserva' => $id_reserva]]);
            $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
            $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
            $response->error = $reserva->error;
            $response->respuesta = $reserva->respuesta;
            $reservaData = (isset($reserva->data[0]) && !empty($reserva->data[0])) ? $reserva->data[0] : [];
            if (!empty($reservaData)) {
                $reservaData->instrumento_urls = $this->mapInstrumentoUrls($reservaData->instrumento ?? null);
            }
            $response->data['reserva'] = $reservaData;
            $response->data['presupuesto'] = (isset($reserva->data[0]) && !empty($reserva->data[0])) ? $reserva->data : [];
            $response->data['proyecto'] = (isset($cat_proyecto->data[0]) && !empty($cat_proyecto->data[0])) ? $cat_proyecto->data : [];
            $response->data['partida'] = (isset($cat_partida->data[0]) && !empty($cat_partida->data[0])) ? $cat_partida->data : [];
        }

        return $this->respond($response);
    }
    public function formActividad()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedor';
        $globals = new Mglobal;
        $data = $this->request->getPost();

        if (empty($data['actividad'])) {
            $response->respuesta = 'La descripción de la actividad es requerida';
            return $this->respond($response);
        }
        if (empty($data['fec_inicio'])) {
            $response->respuesta = 'La fec. inicioes requerida';
            return $this->respond($response);
        }
        if (empty($data['fec_fin'])) {
            $response->respuesta = 'La fec_fin es requerida';
            return $this->respond($response);
        }
        if (empty($data['actividad'])) {
            $response->respuesta = 'La actividad es requerida';
            return $this->respond($response);
        }

        $dataInsert = [
            "actividad" => $data['actividad'],
            "fec_inicio" => $data['fec_inicio'],
            "fec_fin" => $data['fec_fin'],
            "actividad" => $data['actividad'],
            "estado" => $data['estatus'],
            "descripcion" => $data['des_actividad'],
            "id_usuario" => $data['id_usuario'],
            "usu_reg" => $session->id_usuario,
            "fec_reg" => date('Y-m-d')
        ];
        if ($data['id_actividad'] == 0) {
            $dataInsert['usu_act'] = $session->id_usuario;
        }
        $dataConfig = [
            "tabla" => "actividad",
            "editar" => ($data['id_actividad'] == 0) ? false : true,
            "idEditar" => ['id_actividad' => (int) $data['id_actividad']]
        ];
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Principal.php/guardaActividad'];
        $res = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        if (!$res->error) {
            $response->error = false;
            $response->respuesta = $res->respuesta;
        }

        return $this->respond($response);

    }
    public function generarTramitePagoGo($id_reserva_go = null, $id_registro_go = null)
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedor';
        $globals = new Mglobal;
       
        $siExisteIdReserva = $globals->getTabla(['tabla' => 'registro_go', 'where' => ['visible' => 1, 'id_reserva_go' => $id_reserva_go]]);
        
        $btn = (!empty($siExisteIdReserva->data)) ? true : false;

        $cat_area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        if ($id_reserva_go != 0) {
            $reserva = $globals->getTabla(['tabla' => 'vw_reserva_go', 'where' => ['id_reserva' => $id_reserva_go]]);
            $presupuesto = $globals->getTabla(['tabla' => 'vw_presupuesto_go', 'where' => ['id_reserva' => $id_reserva_go]]);
        }
        if (!empty($id_registro_go)) {
            $registro_pt = $globals->getTabla(['tabla' => 'vw_registro_go', 'where' => ['visible' => 1, 'id_registro_go' => $id_registro_go]]);
        }

        $user = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->id_usuario ]]);
        $id_area           = (isset($user->data) && !empty($user->data))?$user->data[0]->id_area:0;
        $id_jefe_inmediato = (isset($user->data) && !empty($user->data))?$user->data[0]->id_jefe_inmediato:0;
      
        // BUSCAR EL ÚLTIMO CONSECUTIVO PARA ESTA ÁREA
        $area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['id_pago' => 1, 'titular' => $session->id_usuario ]]);

        if (isset($area->data) && !empty($area->data)) {
            $id_area = $area->data[0]->id_area;
        }else{
            $area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['id_pago' => 1, 'titular' => $id_jefe_inmediato ]]);
            if (isset($area->data) && !empty($area->data)) {
                $id_area = $area->data[0]->id_area;
            }
        }
        $data['id_area'] = $id_area;
        $ultimoFolio = $globals->getTabla([
            'tabla' => 'folio_go',
            'where' => ['id_direccion' => $id_area, 'visible' => 1]
        ]);

        if (isset($ultimoFolio->data) && !empty($ultimoFolio->data)) {
            $no_consecutivo = count($ultimoFolio->data);
        }else{
            $no_consecutivo = 0;
        }
        //var_dump($no_consecutivo);
        $data['no_consecutivo'] = (int)$no_consecutivo + 1;

        $secretario = $globals->getTabla(['tabla' => 'cat_secretario', 'where' => ['visible' => 1]]);
        $cat_tipo = $globals->getTabla(['tabla' => 'cat_tipo', 'where' => ['visible' => 1]]);

        $usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->get('id_usuario')]]);
        $cat_usuario = $globals->getTabla(['tabla' => 'usuario', 'where' => ['visible' => 1]]);
        $cat_director_general = $globals->getTabla(['tabla' => 'cat_director_general', 'where' => ['visible' => 1]]);
        $cat_opcion = $globals->getTabla(['tabla' => 'cat_opcion', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $cat_proyecto  = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_subsecretario = $globals->getTabla(['tabla' => 'cat_subsecretario', 'where' => ['visible' => 1]]);
        if ($id_reserva_go != 0) {
            $data['reserva'] = (!empty($reserva->data)) ? $reserva->data[0] : [];
            $data['presupuesto'] = (!empty($presupuesto->data)) ? $presupuesto->data : [];
        }
        if (!empty($id_registro_go)) {
            $data['registro_pt'] = (!empty($registro_pt->data)) ? $registro_pt->data[0] : [];
        }
       // die( var_dump( $cat_area ) );
        $data['dsc_director_general'] = (!empty($cat_director_general->data)) ? $cat_director_general->data[0]->dsc_director_general : [];
        $data['cat_area'] = (!empty($cat_area->data)) ? $cat_area->data : [];
        $data['cat_tipo'] = (!empty($cat_tipo->data)) ? $cat_tipo->data : [];
        $data['cat_opcion'] = (!empty($cat_opcion->data)) ? $cat_opcion->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['cat_subsecretario'] = (!empty($cat_subsecretario->data)) ? $cat_subsecretario->data : [];
        //$data['editar']               = (!empty($id_reserva_go) || $id_reserva_go != 0)?1:0;

        $data['secretario'] = (!empty($secretario->data)) ? $secretario->data : [];
        $data['usuario'] = (!empty($usuario->data)) ? $usuario->data[0] : [];
        $data['cat_usuario'] = (!empty($cat_usuario->data)) ? $cat_usuario->data : [];
        $data['id_reserva'] = (!empty($id_reserva_go)) ? $id_reserva_go : 0;
        $data['scripts'] = array('inicio');
        $data['edita'] = $btn;
        $data['contentView'] = 'secciones/vRegistroGo';
        $this->_renderView($data);

    }
    public function editarTramitePagoGo($id_registro_go = null, $GO = TRUE )
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedor';
        $globals = new Mglobal;
        $datosGrupal = [];
        $registro = $globals->getTabla(['tabla' => 'registro_go', 'where' => ['visible' => 1, 'id_registro_go' => $id_registro_go]]);
 
        $data['registro']   = ($registro->data)?$registro->data[0]:[];
        $director_general   = ($registro->data)?$registro->data[0]->director_general:'';
        $data['id_reserva'] = $id_reserva_go = ($registro->data)?$registro->data[0]->id_reserva_go:'';
        $directorGeneral    = $globals->getTabla(['tabla' => 'cat_director_general', 'where' => ['id_director_general' => $director_general]]);

        $data['dsc_director_general'] = ($directorGeneral->data)?$directorGeneral->data[0]->dsc_director_general:'';
       // $data['id_reserva'] =  $id_reserva_go;
        
        $cat_area           = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['id_pago' => 1]]);
        $cat_usuario        = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        $secretario         = $globals->getTabla(['tabla' => 'cat_secretario', 'where' => ['visible' => 1]]);
        $cat_subsecretario  = $globals->getTabla(['tabla' => 'cat_subsecretario', 'where' => ['visible' => 1]]);
        $cat_proyecto       = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $presupuesto        = $globals->getTabla(['tabla' => 'vw_presupuesto_go', 'where' => ['id_reserva' => $id_reserva_go]]);
        $cat_opcion         = $globals->getTabla(['tabla' => 'cat_opcion', 'where' => ['visible' => 1]]);
        $cat_partida        = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $periodo_factura_go = $globals->getTabla(['tabla' => 'periodo_factura_go', 'where' => ['id_registro_go' => $id_registro_go, 'visible' => 1]]);
        $xml_go             = $globals->getTabla(['tabla' => 'xml_go', 'where' => ['id_registro_go' => $id_registro_go, 'visible' => 1]]);
        
       
        $data['cat_partida']        = (!empty($cat_partida->data)) ? $cat_partida->data : [];
       
        $data['cat_proyecto']       = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['cat_opcion']         = (!empty($cat_opcion->data)) ? $cat_opcion->data : [];
        $data['cat_area']           = (!empty($cat_area->data)) ? $cat_area->data : [];
        $data['cat_usuario']        = (!empty($cat_usuario->data)) ? $cat_usuario->data : [];
        $data['secretario']         = (!empty($secretario->data)) ? $secretario->data : [];
        $data['cat_subsecretario']  = (!empty($cat_subsecretario->data)) ? $cat_subsecretario->data : [];
        $data['presupuesto']        = (!empty($presupuesto->data)) ? $presupuesto->data : [];
         $data['periodo_factura_go'] = (!empty($periodo_factura_go->data)) ? $periodo_factura_go->data : [];
         //var_dump( $data['presupuesto'] );
         foreach($data['presupuesto'] as $key => $p){
                 $datos = $globals->getTabla(['tabla' => 'periodo_factura_go', 'where' => ['id_presupuesto' => $p->id_presupuesto_go, 'visible' => 1]]);
               
                 $datosGrupal[$key] = $data['presupuesto'];
                 foreach($datos->data as $j => $d){
                     $datosGrupal[$key][$key]->encabezado = $d->encabezado;

                    $xml      = $globals->getTabla(['tabla' => 'xml_go', 'where' => ['id_registro_go' => $id_registro_go, 'id_identificador' => $d->id_identificador, 'visible' => 1]]);
                    $factura  = $globals->getTabla(['tabla' => 'factura_pdf_go', 'where' => ['id_registro_go' => $id_registro_go, 'id_identificador' => $d->id_identificador, 'visible' => 1]]);
                   //die( var_dump( $factura->data[0]->ruta_relativa ) );
                    $datosGrupal[$key]['datos'][$j] =  [
                         'id_periodo_factura' => $d->id_periodo_factura,
                         'id_registro_go' => $d->id_registro_go,
                         'id_presupuesto' => $d->id_presupuesto,
                         'encabezado' => $d->encabezado,
                         'periodo' => $d->periodo,
                         'importe' => $d->importe,
                         'comprobante' => $d->comprobante,
                         'propina' => $d->propina,
                         'concepto' => (isset($d->concepto)) ? $d->concepto : '',
                         'comision' => (isset($d->comision)) ? $d->comision : '',
                         'contribuyente' => $d->contribuyente,
                         'rfc' => $d->rfc,
                         'visible' => $d->visible,
                         'periodo_fin' => $d->periodo_fin,
                         'periodo_inicio' => $d->periodo_inicio,
                         'id_identificador' => $d->id_identificador,
                         'usu_reg' => $d->usu_reg,
                         'total' => (!empty($xml->data) && isset($xml->data[0]->total)) ? $xml->data[0]->total : 0,
                         'ruta_relativa' => (!empty($factura->data) && isset($factura->data[0]->ruta_relativa)) ? $factura->data[0]->ruta_relativa : ''
                    ];
                 }


               //   var_dump( $datosGrupal[$key]['datos'][$j] );
         }
      /*    foreach($periodo_factura_go as $f){

         } */

//die( var_dump( $datosGrupal ) );

 //    die();
       
       // die( var_dump( $data['periodo_factura_go']  ) );
        $data['id_registro_go']     = $id_registro_go;
        $data['datosGrupal']        = $datosGrupal;
        $data['scripts']            = array('inicio');
        $data['edita']              = 1;
        $data['contentView']        = 'secciones/vRegistroEditarGo';
        $this->_renderView($data);

    }
    public function TablaPagos($id_reserva)
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedor';
        $globals = new Mglobal;
        
        $response = $globals->getTabla(['tabla' => 'vw_pagos', 'where' => ['visible' => 1, 'id_reserva' => $id_reserva]]);
        
        $pagosID = [];
        $pagos = [];
        if(isset($response->data) && !empty($response->data)){
            $data['total_importe'] = $response->data[0]->total_importe;
            $data['id_reserva'] = $id_reserva;
            
            foreach($response->data as $p){
                // Solo agregamos el ID, permitiendo duplicados inicialmente
                $pagosID[] = $p->id_registro_pt; 
            }
            
            // Paso CLAVE: Eliminar duplicados después de llenar el array
            $pagosID = array_unique($pagosID);
            
            // Si necesitas reindexar el array (empezar las claves desde 0), puedes usar array_values:
            // $pagosID = array_values($pagosID); 
        }
        foreach($pagosID as $key => $value){
            $pago = $globals->getTabla(['tabla' => 'factura', 'where' => ['visible' => 1, 'id_registro_pt' => $value]]);

            foreach( $pago->data as $p){
                $pagos[] = [
                    'total' => $p->total,
                    'folio' => $p->folio,
                    'fecha' => $p->fecha,
                    'emisor_rfc' => $p->emisor_rfc,
                ];
            }
           
        }

       
        $data['pagos'] = $pagos;
       // die( var_dump( $data['pagos'] ) );

        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vTablaPagos';
        $this->_renderView($data);
    }

    public function continuarPago($id_registro_pt = null)
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedor';
        $globals = new Mglobal;
        $data['id_reserva'] = 0;
           $user = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->id_usuario ]]);
        $id_area           = (isset($user->data) && !empty($user->data))?$user->data[0]->id_area:0;
        $id_jefe_inmediato = (isset($user->data) && !empty($user->data))?$user->data[0]->id_jefe_inmediato:0;
      
        $responGasto = $globals->getTabla(['tabla' => 'folio_direccion', 'where' => ['id_direccion' => (int)$session->id_usuario]]);  //primero revisamos si tu no eres responsable del gasto

        if( isset($responGasto->data) && !empty($responGasto->data)){
             $no_consecutivo = count($responGasto->data);
        }

        if(empty($responGasto->data)){
             $responGasto = $globals->getTabla(['tabla' => 'folio_direccion', 'where' => ['visible' => 1, 'id_direccion', $id_jefe_inmediato ]]);
             $no_consecutivo = (isset($responGasto->data) && !empty($responGasto->data))?count($responGasto->data):'';
        }
        if(isset($no_consecutivo) && empty($no_consecutivo)){
               $responGasto = $globals->getTabla(['tabla' => 'folio_direccion', 'where' => ['visible' => 1, 'id_area ', $id_area]]);
               $no_consecutivo = (isset($responGasto->data) && !empty($responGasto->data))?count($responGasto->data):'';
        }
   

   
        $data['no_consecutivo'] = (int)$no_consecutivo + 1;
        if (!empty($id_registro_pt)) {
            $registro_pt = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]]);
        }

        $secretario = $globals->getTabla(['tabla' => 'cat_secretario', 'where' => ['visible' => 1]]);
        $cat_tipo = $globals->getTabla(['tabla' => 'cat_tipo', 'where' => ['visible' => 1]]);

        $usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->get('id_usuario')]]);
        $cat_usuario = $globals->getTabla(['tabla' => 'usuario', 'where' => ['visible' => 1]]);
        $cat_director_general = $globals->getTabla(['tabla' => 'cat_director_general', 'where' => ['visible' => 1]]);
        $cat_opcion = $globals->getTabla(['tabla' => 'cat_opcion', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
      
        if (!empty($id_registro_pt)) {
            $data['registro_pt'] = (!empty($registro_pt->data)) ? $registro_pt->data[0] : '';
            $data['id_reserva'] = (!empty($registro_pt->data)) ? $registro_pt->data[0]->id_reserva : '';
             $cat_subsecretario = $globals->getTabla(['tabla' => 'cat_subsecretario', 'where' => ['visible' => 1, 'id_subsecretario' => $data['registro_pt']->id_subsecretario]]);
             $direccion_responsable = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1, 'id_area' => $data['registro_pt']->id_direccion_responsable]]);
             $data['subsecretario'] = (!empty($cat_subsecretario->data)) ? $cat_subsecretario->data[0]->dsc_subsecretario : '';
             $data['direccion_responsable'] = (!empty($direccion_responsable->data)) ? $direccion_responsable->data[0]->dsc_area : '';
            $presupuesto = $globals->getTabla(['tabla' => 'vw_presupuesto', 'where' => ['id_reserva' => $data['registro_pt']->id_reserva]]);
        
            foreach ($presupuesto->data as $i => $p) {
                $num = count($presupuesto->data);
                $data['num'] =  ($num >= 2)?true:false;
                
                if ($p->id_partida >= 149 && $p->id_partida <= 248) {
                    $partida4000 = true;
                }
            }
       
            $idproveedor = $globals->getTabla(['tabla' => 'proveedor_banco', 'where' => ['idproveedor' => $data['registro_pt']->id_proveedor]]);
            
             $data['presupuesto'] = (!empty($presupuesto->data)) ? $presupuesto->data : [];
             $data['idproveedor'] = (!empty($idproveedor->data)) ? $idproveedor->data : '';

        }
   
       // die( var_dump(  $data ) );
        $data['dsc_director_general'] = (!empty($cat_director_general->data)) ? $cat_director_general->data[0]->dsc_director_general : [];
  
        $data['cat_tipo'] = (!empty($cat_tipo->data)) ? $cat_tipo->data : [];
        $data['cat_opcion'] = (!empty($cat_opcion->data)) ? $cat_opcion->data : [];
        
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['secretario'] = (!empty($secretario->data)) ? $secretario->data : [];
        $data['usuario'] = (!empty($usuario->data)) ? $usuario->data[0] : [];
        $data['cat_usuario'] = (!empty($cat_usuario->data)) ? $cat_usuario->data : [];

        $data['scripts'] = array('inicio');
        $data['editar'] = 1;
        $data['contentView'] = 'secciones/vContinuarPT';
        $this->_renderView($data);

    }
    public function generarTramitePago($id_reserva = null, $id_registro_pt = null)
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedor';
        $globals = new Mglobal;
        $user = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->id_usuario ]]);
        $id_area           = (isset($user->data) && !empty($user->data))?$user->data[0]->id_area:0;
        $id_jefe_inmediato = (isset($user->data) && !empty($user->data))?$user->data[0]->id_jefe_inmediato:0;
      
  /*       $responGasto = $globals->getTabla(['tabla' => 'folio_direccion', 'where' => ['id_direccion' => (int)$session->id_usuario]]);  //primero revisamos si tu no eres responsable del gasto

        if( isset($responGasto->data) && !empty($responGasto->data)){
             $no_consecutivo = count($responGasto->data);
        }
        if(empty($responGasto->data)){
             $responGasto = $globals->getTabla(['tabla' => 'folio_direccion', 'where' => ['visible' => 1, 'id_direccion', $id_jefe_inmediato ]]);
             $no_consecutivo = (isset($responGasto->data) && !empty($responGasto->data))?count($responGasto->data):'';
        }
        if(isset($no_consecutivo) && empty($no_consecutivo)){
               $responGasto = $globals->getTabla(['tabla' => 'folio_direccion', 'where' => ['visible' => 1, 'id_area ', $id_area]]);
               $no_consecutivo = (isset($responGasto->data) && !empty($responGasto->data))?count($responGasto->data):'';
        } */
        // BUSCAR EL ÚLTIMO CONSECUTIVO PARA ESTA ÁREA
        $area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1, 'titular' => $session->id_usuario ]]);

        if (isset($area->data) && !empty($area->data)) {
            $id_area = $area->data[0]->id_area;
        }else{
            $area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1, 'titular' => $id_jefe_inmediato ]]);
            if (isset($area->data) && !empty($area->data)) {
                $id_area = $area->data[0]->id_area;
            }
        }
     
        $ultimoFolio = $globals->getTabla([
            'tabla' => 'folio_direccion',
            'where' => ['id_direccion' => $id_area, 'visible' => 1]
        ]);

        if (isset($ultimoFolio->data) && !empty($ultimoFolio->data)) {
            $no_consecutivo = count($ultimoFolio->data);
        }else{
            $no_consecutivo = 0;
        }
        //var_dump($no_consecutivo);
        //$data['no_consecutivo'] = (int)$no_consecutivo + 1;
        
        $data['no_consecutivo'] = (int)$no_consecutivo + 1;
        //var_dump($data['no_consecutivo']);
        //die();
        $siExisteIdReserva = $globals->getTabla(['tabla' => 'registro_pt', 'where' => ['visible' => 1, 'id_reserva' => $id_reserva]]);
        $btn = false;
        $partida4000 = false;
        $cat_area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        if ($id_reserva != 0) {
            $reserva = $globals->getTabla(['tabla' => 'vw_reserva', 'where' => ['id_reserva' => $id_reserva]]);
          
            $presupuesto = $globals->getTabla(['tabla' => 'vw_presupuesto', 'where' => ['id_reserva' => $id_reserva]]);
            foreach ($presupuesto->data as $i => $p) {
                $num = count($presupuesto->data);
                $data['num'] =  ($num >= 2)?true:false;
                
                if ($p->id_partida >= 149 && $p->id_partida <= 248) {
                    $partida4000 = true;
                }
            }

        }
        if (!empty($id_registro_pt)) {
            $registro_pt = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]]);
        }

        $secretario = $globals->getTabla(['tabla' => 'cat_secretario', 'where' => ['visible' => 1]]);
        $cat_tipo = $globals->getTabla(['tabla' => 'cat_tipo', 'where' => ['visible' => 1]]);

        $usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->get('id_usuario')]]);
        $cat_usuario = $globals->getTabla(['tabla' => 'usuario', 'where' => ['visible' => 1]]);
        $cat_director_general = $globals->getTabla(['tabla' => 'cat_director_general', 'where' => ['visible' => 1]]);
        $cat_opcion = $globals->getTabla(['tabla' => 'cat_opcion', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_subsecretario = $globals->getTabla(['tabla' => 'cat_subsecretario', 'where' => ['visible' => 1]]);
        if ($id_reserva != 0) {

            $data['reserva'] = (!empty($reserva->data)) ? $reserva->data[0] : [];
            $data['presupuesto'] = (!empty($presupuesto->data)) ? $presupuesto->data : [];
          
        }
        if (!empty($id_registro_pt)) {
            $data['presupuesto'] = (!empty($registro_pt->data)) ? $registro_pt->data[0] : [];
        }


   // die( var_dump(  $data['presupuesto'] ) );
        $data['dsc_director_general'] = (!empty($cat_director_general->data)) ? $cat_director_general->data[0]->dsc_director_general : [];
        $data['cat_area'] = (!empty($cat_area->data)) ? $cat_area->data : [];
        $data['cat_tipo'] = (!empty($cat_tipo->data)) ? $cat_tipo->data : [];
        $data['cat_opcion'] = (!empty($cat_opcion->data)) ? $cat_opcion->data : [];
        $data['cat_subsecretario'] = (!empty($cat_subsecretario->data)) ? $cat_subsecretario->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['editar'] = (!empty($id_reserva) || $id_reserva != 0) ? 0 : 1;
        $data['secretario'] = (!empty($secretario->data)) ? $secretario->data : [];
        $data['usuario'] = (!empty($usuario->data)) ? $usuario->data[0] : [];
        $data['cat_usuario'] = (!empty($cat_usuario->data)) ? $cat_usuario->data : [];
        $data['id_reserva'] = (!empty($id_reserva)) ? $id_reserva : 0;
        $data['scripts'] = array('inicio');
        $data['edita'] = $btn;
        $data['partida4000'] = $partida4000;
          if( in_array($data['reserva']->no_proveedor, [103167, 106379, 104456, 104103])){
                   $data['contentView'] = 'secciones/vExtranjero';
                   $this->_renderView($data);
                   die();

            }
        $data['contentView'] = 'secciones/vProveedor';
        $this->_renderView($data);

    }
    public function PagoFic($id_proveedor = null)
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedor';
        $globals = new Mglobal;
        
        if ($id_proveedor != 0) {
            $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1, 'id_proveedor' => $id_proveedor]]);
            $banco = $globals->getTabla(['tabla' => 'proveedor_banco', 'where' => ['idproveedor' => $id_proveedor, 'fic' => 1]]);
            $restaurantes = $globals->getTabla(['tabla' => 'cat_restaurante_fic', 'where' => ['no_proveedor' => $proveedor->data[0]->no_proveedor]]);
            $hoteles = $globals->getTabla(['tabla' => 'cat_hoteles_fic', 'where' => ['no_proveedor' => $proveedor->data[0]->no_proveedor]]);
        }
        // var_dump( $hoteles);
        $cat_area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        $secretario = $globals->getTabla(['tabla' => 'cat_secretario', 'where' => ['visible' => 1]]);
        $cat_subsecretario = $globals->getTabla(['tabla' => 'cat_subsecretario', 'where' => ['visible' => 1]]);
        $cat_tipo = $globals->getTabla(['tabla' => 'cat_tipo', 'where' => ['visible' => 1]]);

        $usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->get('id_usuario')]]);
        $cat_usuario = $globals->getTabla(['tabla' => 'usuario', 'where' => ['visible' => 1]]);
        $cat_director_general = $globals->getTabla(['tabla' => 'cat_director_general', 'where' => ['visible' => 1]]);
        $cat_opcion = $globals->getTabla(['tabla' => 'cat_opcion', 'where' => ['visible' => 1]]);

        $consecutivo = $globals->getTabla(['tabla' => 'registro_pt', 'where' => ['visible' => 1], 'orderBy' => 'id_registro_pt DESC']);
        $conse = (isset($consecutivo->data) && !empty($consecutivo->data)) ? $consecutivo->data[0]->no_consecutivo + 1 : 1;
        $data['consecutivo'] = $conse;

        if ($id_proveedor != 0) {
            $data['proveedor'] = (!empty($proveedor->data)) ? $proveedor->data[0] : [];
            $data['banco'] = (!empty($banco->data)) ? $banco->data : [];
            $data['restaurantes'] = (!empty($restaurantes->data)) ? $restaurantes->data : [];
            $data['hoteles'] = (!empty($hoteles->data)) ? $hoteles->data : [];

        }
        // die( );

        if (!empty($id_registro_pt)) {
            $data['registro_pt'] = (!empty($registro_pt->data)) ? $registro_pt->data[0] : [];
        }
        $data['FIC'] = true;
        $data['dsc_director_general'] = (!empty($cat_director_general->data)) ? $cat_director_general->data[0]->dsc_director_general : [];
        $data['cat_area'] = (!empty($cat_area->data)) ? $cat_area->data : [];
        $data['cat_tipo'] = (!empty($cat_tipo->data)) ? $cat_tipo->data : [];
        $data['cat_opcion'] = (!empty($cat_opcion->data)) ? $cat_opcion->data : [];
        $data['editar'] = (!empty($id_proveedor) || $id_proveedor != 0) ? 0 : 1;
        $data['secretario'] = (!empty($secretario->data)) ? $secretario->data : [];
        $data['subsecretario'] = (!empty($cat_subsecretario->data)) ? $cat_subsecretario->data : [];
        $data['usuario'] = (!empty($usuario->data)) ? $usuario->data[0] : [];
        $data['cat_usuario'] = (!empty($cat_usuario->data)) ? $cat_usuario->data : [];
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vFormularioFic';
        $this->_renderView($data);

    }

    public function getProveedores()
    {

        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedores';
        $globals = new Mglobal;
        $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1]]);
        if (isset($proveedor->data) && !empty($proveedor->data)) {
            $response->error = $proveedor->error;
            $response->respuesta = $proveedor->respuesta;
            $response->data = $proveedor->data;

        }
        return $this->respond($response);
    }
    public function reporteIncidencia()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = array();
        $incidencia = $globals->getTabla([
            'tabla' => 'vw_incidenica',
            'where' => ['visible' => 1, 'id_estatus' => 3]
        ]);
        $data['incidencia'] = (isset($incidencia->data) && !empty($incidencia->data)) ? $incidencia->data : '';

        $tempQrPath = FCPATH . 'assets/images/qr_final.png';
        $folio = 'GTO - ' . date('YmdHis') . substr((string) microtime(), 1, 4);
        // Generar el QR
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data(base_url() . 'index.php/Principal/reporteIncidenciaUsuario/0/0/0/' . $folio)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(400)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->labelText('')
            ->labelFont(new NotoSans(16))
            ->labelAlignment(new LabelAlignmentCenter())
            ->build();

        $result->saveToFile($tempQrPath);
        $dataImagen = $this->encode_img_base64(FCPATH . 'assets/images/qr_final.png', 'png');
        $data['dataImagen'] = $dataImagen;
        $data['folio'] = $folio;

        $doc = 'assets/pdf/plantillas/asistencia.pdf';
        $formato = 'personal/vFormatoAsistencia.php';
        $html = view($formato, $data);
        // Crear instancia de mPDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

        // Importar el PDF base

        $pagecount = $mpdf->SetSourceFile(FCPATH . $doc);
        $tplId = $mpdf->ImportPage(1);

        // Página 1
        $mpdf->AddPage();
        $mpdf->UseTemplate($tplId);
        $mpdf->WriteHTML($html);

        // Footer en todas las páginas
        $mpdf->SetHTMLFooter('
            <div style="text-align: right; font-size: 10px;">
                Página {PAGENO} de {nbpg}
            </div>
        ');


        $mpdf->Output('Formato_pt.pdf', 'I');
        exit();
    }
    private function meses($idMes)
    {
        $meses = [
            1 => 'ENERO',
            2 => 'FEBRERO',
            3 => 'MARZO',
            4 => 'ABRIL',
            5 => 'MAYO',
            6 => 'JUNIO',
            7 => 'JULIO',
            8 => 'AGOSTO',
            9 => 'SEPTIEMBRE',
            10 => 'OCTUBRE',
            11 => 'NOVIEMBRE',
            12 => 'DICIEMBRE',
            // agrega más si los necesitas
        ];

        return $meses[$idMes] ?? '';
    }

    public function servicio()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $id               = $this->request->getGet('id_servicio');
        $data['monto']    = $this->request->getGet('monto');
        $data['folio']    = $this->request->getGet('folio');
        $data['folio_fac']= $this->request->getGet('folio_fac');
        $data['reserva']  = $this->request->getGet('reserva');
        $periodo          = $this->request->getGet('periodo');

        $data['periodo'] = $this->meses( $periodo);
        $data['numero_texto'] = $this->numeroEnLetras($data['monto']);
  
       $servicio = $globals->getTabla([
            'tabla' => 'cat_servicio',
            'where' => ['visible' => 1, 'id_servicio' => $id]
        ]);
     
        $data['servicio'] = isset($servicio->data) && !empty($servicio->data)?$servicio->data[0]:'';
  
      
       $doc = 'assets/pdf/plantillas/anexo01.pdf';
       $formato = 'personal/vFormatoCheco.php';
       
           // die( var_dump($data) );

        $html = view($formato, $data);
        $htmlSegundaHoja = view('personal/vFormatoCheco2.php', $data);
        $htmlTerceraHoja = view('personal/vFormatoCheco3.php', $data);
        //Crear instancia de mPDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

        $pagecount = $mpdf->SetSourceFile(FCPATH . $doc);
        for ($i = 1; $i <= $pagecount; $i++) {
            $mpdf->AddPage();
            $tplId = $mpdf->ImportPage($i);
            $mpdf->UseTemplate($tplId);

            if ($i == 1) {
                $mpdf->WriteHTML($html);
            }
            if ($i == 2) {
                $mpdf->WriteHTML($htmlSegundaHoja);
            }
          
                
            
        }

     $doc2 = 'assets/pdf/plantillas/anexo07_2.pdf';
    $pagecount2 = $mpdf->SetSourceFile(FCPATH . $doc2);

    // Solo procesar la primera página (i = 1)
    $mpdf->AddPage();
    $tplId = $mpdf->ImportPage(1); // Importar solo la página 1
    $mpdf->UseTemplate($tplId);
    $mpdf->WriteHTML($htmlTerceraHoja);

    $mpdf->Output('Formato_pt.pdf', 'I');
    exit();
    }

    public function validarSolicitudGrc()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error al validar la solicitud";

        // Validar permisos (Perfil 1 y 2)
        if (!in_array($session->get('id_perfil'), [1, 2])) {
             $response->respuesta = "No tiene permisos para realizar esta acción.";
             return $this->respond($response);
        }

        $id_solicitud = $this->request->getPost('id_solicitud');

        if(empty($id_solicitud)){
             $response->respuesta = "ID de solicitud incorrecto.";
             return $this->respond($response);
        }

        $formodel = new Mglobal();

        // Actualizar estatus a 2 (Validado)
        $dataConfig = [
            "tabla" => "solicitud_grc", 
            "editar" => true, 
            "idEditar" => ['id_solicitud_grc' => $id_solicitud]
        ];
        
        $dataUpdate = [
            'id_estatus' => 2, 
            'usu_act' => $session->get('id_usuario'),
            'fec_act' => date('Y-m-d H:i:s')
        ];

        $result = $formodel->saveTabla($dataUpdate, $dataConfig, ['script' => 'Principal-validarSolicitudGrc', 'id_user' => $session->get('id_usuario')]);

        if (!$result->error) {
            $response->error = false;
            $response->respuesta = "Solicitud validada correctamente";

            // Enviar Correo de notificación
            $solicitudQuery = $formodel->getTabla(['tabla' => 'solicitud_grc', 'where' => ['id_solicitud_grc' => $id_solicitud]]);
            
            if(isset($solicitudQuery->data[0])){
                $id_usu_reg = $solicitudQuery->data[0]->usu_reg;
                // Obtener correo del usuario que registró
                $usuarioQuery = $formodel->getTabla(['tabla' => 'vw_usuario', 'where' => ['id_usuario' => $id_usu_reg]]);
                
                if(isset($usuarioQuery->data[0]) && !empty($usuarioQuery->data[0]->correo)){
                    $correo = $usuarioQuery->data[0]->correo;
                    
                    $email = \Config\Services::email();
                    $email->setTo($correo);
                    $email->setSubject('Notificación Susi: Solicitud GRC Validada');
                    
                    $mensaje = '
                    <div style="font-family: Arial, sans-serif; padding: 20px;">
                        <h2 style="color: #28a745;">Solicitud Validada</h2>
                        <p>Estimado usuario,</p>
                        <p>Su solicitud GRC con folio <strong>' . $id_solicitud . '</strong> ha sido VALIDADA por el área correspondiente.</p>
                        <p>Ahora puede proceder a realizar la comprobación de gastos en el sistema.</p>
                        <p>Atentamente,<br>Sistema SUSI</p>
                    </div>';
                    
                    $email->setMessage($mensaje);
                    $email->send();
                }
            }

        } else {
            $response->respuesta = $result->respuesta;
        }

        return $this->respond($response);
    }

    public function comprobarGastos($id_solicitud = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;

        if (!$id_solicitud) {
            echo "ID no válido";
            return;
        }
        // Obtener datos de la solicitud
        $solicitudQuery = $globals->getTabla(['tabla' => 'vw_solicitud_grc', 'where' => ['id_solicitud_grc' => $id_solicitud, 'visible' => 1]]);
        
        if (empty($solicitudQuery->data)) {
            echo "Solicitud no encontrada";
            return;
        }
        // Obtener detalles
        $detallesQuery = $globals->getTabla(['tabla' => 'vw_solicitud_grc_detalle', 'where' => ['id_solicitud_grc' => $id_solicitud, 'visible' => 1]]);

        // Obtener usuarios para el select
        $usuariosQuery = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);

        $data['solicitud'] = $solicitudQuery->data[0];
        $data['detalles'] = (!empty($detallesQuery->data)) ? $detallesQuery->data : [];
        $data['usuarios'] = (!empty($usuariosQuery->data)) ? $usuariosQuery->data : [];
        
        // Variables añadidas para el "no_consecutivo" estilo Formato GO
        $cat_area = $globals->getTabla(["tabla" => "cat_area", "where" => ["id_pago" => 1, 'id_pago' => 1]]);
        $data['cat_area'] = isset($cat_area->data) ? $cat_area->data : [];
        
        $usu = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["id_pago" => 1, 'id_usuario' => $session->get('id_usuario')]]);
        $data['id_area'] = 1; // fallback
        if(isset($usu->data[0]->id_usuario)){
            $tieneArea = $globals->getTabla(["tabla" => "cat_area", "where" => ["id_pago" => 1, 'titular' => $usu->data[0]->id_usuario]]);
            if(isset($tieneArea->data) && !empty($tieneArea->data)){
                $data['id_area'] = $tieneArea->data[0]->id_area;
            }else{
                $tieneArea = $globals->getTabla(["tabla" => "cat_area", "where" => ["id_pago" => 1, 'titular' => $usu->data[0]->id_jefe_inmediato]]);
                if(isset($tieneArea->data) && !empty($tieneArea->data)){
                    $data['id_area'] = $tieneArea->data[0]->id_area;
                }else{
                    if(isset($cat_area->data[0])) {
                        $data['id_area'] = $cat_area->data[0]->id_area;
                    }
                }
            }
        }
        
        // Obtener comprobantes guardados previamente si existen
        $comprobantesGuardados = $globals->getTabla(['tabla' => 'solicitud_grc_comprobacion', 'where' => ['id_solicitud_grc' => $id_solicitud, 'visible' => 1]]);
        $data['comprobantes_guardados'] = (!empty($comprobantesGuardados->data)) ? $comprobantesGuardados->data : [];

        // Calcular o retomar consecutivo
        if (!empty($data['solicitud']->no_consecutivo)) {
            // "GRC XXX/2026" - extract the middle part or pass it down
            $data['no_consecutivo_completo'] = $data['solicitud']->no_consecutivo;
            // Let the JS handle it or we strip it. Better to pass it raw if we need.
            // Extraer solo dígitos de "GRC SSPT 005/2026" (it might be tricky). Mantenemos el cálculo como fallback.
        }

        $comprobaciones = $globals->getTabla(["tabla" => "solicitud_grc_comprobacion", "where" => ["visible" => 1, 'usu_reg' => $session->get('id_usuario')]]);
        $no_consecutivo = isset($comprobaciones->data) ? count($comprobaciones->data) + 1 : 1;
        
        // Si ya hay comprobantes guardados para ESTA sol, el número ya fue consumido. 
        // Idealmente restamos o le damos prioridad al que ya guardaron
        $no_consecutivo = str_pad($no_consecutivo, 3, "0", STR_PAD_LEFT);
        $data['no_consecutivo'] = $no_consecutivo;
        
        $data['scripts'] = ['principal', 'inicio']; // Ensure necessary scripts are loaded
        $data['contentView'] = 'personal/vComprobarGastos';

        $this->_renderView($data);
    }

    public function getFolioPorArea()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $response->error = true;

        $id_area = $this->request->getPost('id_area');

        if ($id_area) {
            $ultimoFolio = $globals->getTabla([
                'tabla' => 'folio_go',
                'where' => ['id_direccion' => $id_area, 'visible' => 1]
            ]);

            if (isset($ultimoFolio->data) && !empty($ultimoFolio->data)) {
                $no_consecutivo = count($ultimoFolio->data);
            } else {
                $no_consecutivo = 0;
            }
            
            $response->consecutivo = (int)$no_consecutivo + 1;
            $response->error = false;
        } else {
             $response->respuesta = 'ID de área no válido';
        }

        return $this->respond($response);
    }

    public function listaBorradoresGO()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        
        // Filtro por estatus 1 (Borrador)
        $where = ['visible' => 1, 'id_estatus' => 1];
        
        if (!in_array($session->get('id_perfil'), [1, 2])) {
            // Si no es admin/director, solo sus propios registros
            $where['usu_reg'] = $session->get('id_usuario');
        }

        $registro_go = $globals->getTabla([
            'tabla' => 'vw_registro_go', 
            'where' => $where
        ]);

        $data['registro_go'] = (!empty($registro_go->data)) ? $registro_go->data : [];
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'secciones/vListadoBorradorGO';
        $this->_renderView($data);
    }

    public function borradorPagoGo($id_registro_go = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        
        if(empty($id_registro_go)){
             return redirect()->to(base_url().'index.php/Principal/listaBorradoresGO');
        }

        $registro_go = $globals->getTabla(['tabla' => 'vw_registro_go', 'where' => ['visible' => 1, 'id_registro_go' => $id_registro_go]]);
        
        if(empty($registro_go->data)){
            echo "Borrador no encontrado"; 
            return;
        }
        
        $dataRegistro = $registro_go->data[0];
        $id_reserva_go = $dataRegistro->id_reserva_go;

        // Obtener datos necesarios 
        $cat_area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        $reserva = $globals->getTabla(['tabla' => 'vw_reserva_go', 'where' => ['id_reserva' => $id_reserva_go]]);
        $presupuesto = $globals->getTabla(['tabla' => 'vw_presupuesto_go', 'where' => ['id_reserva' => $id_reserva_go]]);
        
        // Periodos/Facturas
        $periodo_factura = $globals->getTabla(['tabla' => 'periodo_factura_go', 'where' => ['visible' => 1, 'id_registro_go' => $id_registro_go]]);
        $factura_pdf = $globals->getTabla(['tabla' => 'factura_pdf_go', 'where' => ['visible' => 1, 'id_registro_go' => $id_registro_go]]);
        $xml_go = $globals->getTabla(['tabla' => 'xml_go', 'where' => ['visible' => 1, 'id_registro_go' => $id_registro_go]]);

        $secretario = $globals->getTabla(['tabla' => 'cat_secretario', 'where' => ['visible' => 1]]);
        $cat_tipo = $globals->getTabla(['tabla' => 'cat_tipo', 'where' => ['visible' => 1]]);
        $cat_usuario = $globals->getTabla(['tabla' => 'usuario', 'where' => ['visible' => 1]]);
        $cat_director_general = $globals->getTabla(['tabla' => 'cat_director_general', 'where' => ['visible' => 1]]);
        $cat_opcion = $globals->getTabla(['tabla' => 'cat_opcion', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $cat_proyecto  = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_subsecretario = $globals->getTabla(['tabla' => 'cat_subsecretario', 'where' => ['visible' => 1]]);

        $data['reserva'] = (!empty($reserva->data)) ? $reserva->data[0] : [];
        $data['presupuesto'] = (!empty($presupuesto->data)) ? $presupuesto->data : [];
        $data['registro_pt'] = $dataRegistro; 
        
        // Mapear archivos por fila para JS
 

      $grupos = [];
        foreach($presupuesto->data as $key => $value){
            $grupos[] = [
                'id_presupuesto_go' => $value->id_presupuesto_go,
                'id_reserva'        => $value->id_reserva,
                'id_proyecto'       => $value->id_proyecto,
                'id_partida'        => $value->id_partida,
                'importe'           => $value->importe,
                'fec_reg'           => $value->fec_reg,
                'usu_reg'           => $value->usu_reg,
                'encabezado'        => $value->encabezado,
                'dsc_partida'       => $value->dsc_partida,
                'partida'           => $value->partida,
                'proyecto'          => $value->proyecto
  
            ];
            foreach($periodo_factura->data as $index => $v){ 
                if($v->id_identificador == $factura_pdf->data[$index]->id_identificador && 
                $v->id_identificador == $xml_go->data[$index]->id_identificador){
                $grupos[$key]['tabla'][] = [
                    'id_periodo_factura' =>$v->id_periodo_factura,
                    'id_registro_go' =>$v->id_registro_go,
                    'id_presupuesto' =>$v->id_presupuesto,
                    'periodo_fin' =>$v->periodo_fin,
                    'periodo_inicio' =>$v->periodo_inicio,
                    'id_identificador' =>$v->id_identificador,
                    'id_periodo_factura' =>$v->id_periodo_factura,
                    'ruta_absoluta' =>$factura_pdf->data[$index]->ruta_absoluta,
                    'ruta_relativa' =>$factura_pdf->data[$index]->ruta_relativa,
                    'folio' =>$xml_go->data[$index]->folio,
                    'id_xml' =>$xml_go->data[$index]->id_xml,
                    'total' =>$xml_go->data[$index]->total,
                    'concepto' => (isset($v->concepto)) ? $v->concepto: '',
                    'propina' => (isset($v->propina)) ? $v->propina: '',
                    'comision' => (isset($v->comision)) ? $v->comision: '',
                
                ];
              }   
            }   

        } 

        

        $data['grupos'] = $grupos;
        
       //var_dump($grupos);
       //die();

        $data['dsc_director_general'] = (!empty($cat_director_general->data)) ? $cat_director_general->data[0]->dsc_director_general : [];
        $data['cat_area'] = (!empty($cat_area->data)) ? $cat_area->data : [];
        $data['cat_tipo'] = (!empty($cat_tipo->data)) ? $cat_tipo->data : [];
        $data['cat_opcion'] = (!empty($cat_opcion->data)) ? $cat_opcion->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['cat_subsecretario'] = (!empty($cat_subsecretario->data)) ? $cat_subsecretario->data : [];

        $data['secretario'] = (!empty($secretario->data)) ? $secretario->data : [];
        $data['cat_usuario'] = (!empty($cat_usuario->data)) ? $cat_usuario->data : [];
        $data['id_reserva'] = $id_reserva_go;
        $data['id_area'] = $dataRegistro->id_direccion_responsable;
        
        $data['registro_guardado'] = $dataRegistro; 
        $data['periodos_guardados'] = (!empty($periodo_factura->data)) ? $periodo_factura->data : [];
        
        $data['scripts'] = array('inicio');
        $data['edita'] = true; 
        $data['contentView'] = 'secciones/vRegistroGoBorrador'; 
        $this->_renderView($data);
    }


    public function SolicitudConvenio()
    {
        $session = \Config\Services::session();
        $globals = new \App\Models\Mglobal;
        $data = array();
        
        $vw_usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        $data['direccion'] = $vw_usuario->data;
        $vw_usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        $data['usuario'] = $vw_usuario->data;

        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];

        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['catalogo_firmantes'] = $this->construirCatalogoFirmantes($data['direccion'], $data['usuario']);
        $data['firmas_seleccionadas'] = [];

        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vSolicitudConvenio';
        $this->_renderView($data);
    }

    public function enviarCRFyAPConvenio()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
         $response = new \stdClass();
        $id_solicitud_convenio = $this->request->getPost('id_solicitud_convenio');
        $solicitudes = $globals->getTabla(['tabla' => 'vw_solicitud_convenio', 'where' => ["id_solicitud_convenio"=> $id_solicitud_convenio, 'visible' => 1]]);
        if(empty($solicitudes->data)){
             $response->error = true;
             $response->respuesta = 'No se encontro Solicitud';
            return $this->respond($response);
        }

       
        $sol = $solicitudes->data[0];
        $proveedores = $globals->getTabla(["tabla" =>"proveedor", 'where' => ['rfc' => $sol->proveedor_rfc]]);
        $idProveedor = 0;
        $idPOroveedorBanco = 0;

        if(!empty($proveedores->data)){
            $idProveedor = $proveedores->data[0]->id_proveedor;
            $banco = $globals->getTabla(["tabla" =>"proveedor_banco", 'where' => ['idproveedor' => $idProveedor]]);
            if (!empty($banco->data)) {
                $idPOroveedorBanco =  $banco->data[0]->id_proveedor_banco;
            }
        }

        if (empty($idProveedor)) {
            $response->error = true;
            $response->respuesta = 'No se encontro el proveedor para enviar a CRFyAP';
            return $this->respond($response);
        }
      
        $dataInsert = [
            "id_proveedor" =>$idProveedor,
            "id_estatus" => 1,
            "id_proveedor_banco" => $idPOroveedorBanco,
            "folio" => "PT-".date("YmdHis"),
            "no_reserva" => "",
            "no_convenio" => $sol->no_convenio,
            "nuevo_fondo" => "",
            "total_importe" => $sol->monto_total,
            "comentarios_instrumento" => "",
            "instrumento" => $sol->instrumento_juridico,
            "ruta_absoluta" => "",
            "observaciones" => $sol->objeto_convenio ?? ($sol->nombre_proyecto ?? ''),
            "fec_reg" => date("Y-m-d H:i:s"),
            "usu_reg" => $session->id_usuario,
            "promo" => (in_array((int) $session->id_usuario, [17, 11, 14, 59, 38, 80], true)) ? 1 : 0,
        ];

        $result = $globals->saveTabla(
            $dataInsert,
            ["tabla" => "reserva", "editar" => false],
            ['id_user' => $session->id_usuario, 'script' => 'Principal.php/enviarCRFyAPConvenio']
        );

        if ($result->error) {
            $response->error = true;
            $response->respuesta = $result->respuesta ?? 'No se pudo enviar la solicitud a CRFyAP';
            return $this->respond($response);
        }

        $dataInsert = [
            "id_reserva" => $result->idRegistro,
            "id_proyecto" => $sol->proyecto,
            "id_partida" => $sol->partida,
            "importe" => $sol->monto_total,
            "usu_reg" => $session->id_usuario,
            "fec_reg" => date('Y-m-d H:i:s'),
        ];
        $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Principal.php/enviarCRFyAPConvenio'];
        $globals->saveTabla($dataInsert,["tabla" => "presupuesto", "editar" => false],$dataBitacora);


        $globals->saveTabla(
            ["ok" => 2, "usu_act" => $session->id_usuario, "fec_act" => date("Y-m-d H:i:s")],
            ["tabla" => "solicitud_convenio", "editar" => true, "idEditar" => ["id_solicitud_convenio" => $id_solicitud_convenio]],
            ['id_user' => $session->id_usuario, 'script' => 'Principal.php/enviarCRFyAPConvenio']
        );
        if($session->id_usuario != 1){
           $this->enviarEmail(1);
        }
        

        $response->error = false;
        $response->respuesta = 'El convenio paso a CRFyAP';
        return $this->respond($response);
        
    }

    public function ListaSolicitudConvenio()
    {
        $session = \Config\Services::session();
        $globals = new \App\Models\Mglobal;
        
        // Dependiendo del perfil mostramos todo o solo lo propio
        if (in_array($session->get('id_perfil'), [1, 7])) {
            $solicitudes = $globals->getTabla(['tabla' => 'vw_solicitud_convenio', 'where' => ['visible' => 1]]);
        } else {
            $solicitudes = $globals->getTabla(['tabla' => 'vw_solicitud_convenio', 'where' => ['visible' => 1, 'usu_reg' => $session->get('id_usuario')]]);
        }

        if(isset($solicitudes->data) && !empty($solicitudes->data)){
            foreach($solicitudes->data as &$s){
                $archivos = $globals->getTabla(['tabla' => 'solicitud_convenio_archivos', 'where' => ['id_solicitud_convenio' => $s->id_solicitud_convenio, 'visible' => 1]]);
                $s->tienen_archivos = !empty($archivos->data);
                $solicitudBase = $globals->getTabla([
                    'tabla' => 'solicitud_convenio',
                    'where' => ['id_solicitud_convenio' => $s->id_solicitud_convenio, 'visible' => 1],
                    'limit' => 1
                ]);
                if (!empty($solicitudBase->data)) {
                    $s->no_convenio = $solicitudBase->data[0]->no_convenio ?? '';
                }

                if (!empty($s->proveedor_rfc)) {
                    $proveedor = $globals->getTabla([
                        'tabla' => 'proveedor',
                        'where' => ['rfc' => $s->proveedor_rfc],
                        'limit' => 1
                    ]);

                    if (!empty($proveedor->data)) {
                        $s->crfyap_nombre_proveedor = $proveedor->data[0]->razon_social ?? ($s->proveedor_rfc ?? '');
                        $s->crfyap_no_proveedor = $proveedor->data[0]->id_proveedor ?? '';

                        $banco = $globals->getTabla([
                            'tabla' => 'proveedor_banco',
                            'where' => ['idproveedor' => $proveedor->data[0]->id_proveedor],
                            'limit' => 1
                        ]);

                        if (!empty($banco->data)) {
                            $s->crfyap_banco = $banco->data[0]->nombre_banco ?? $banco->data[0]->banco ?? $banco->data[0]->cuenta ?? 'Registrado';
                        }
                    }
                }
            }
        }

        $data['solicitudes'] = (!empty($solicitudes->data)) ? $solicitudes->data : [];
        if (!empty($data['solicitudes'])) {
            foreach ($data['solicitudes'] as &$sol) {
                $instrumentos = $sol->instrumento_juridico ?? null;

                if (empty($instrumentos) && !empty($sol->id_solicitud_convenio)) {
                    $solicitudBase = $globals->getTabla([
                        'tabla' => 'solicitud_convenio',
                        'where' => [
                            'id_solicitud_convenio' => $sol->id_solicitud_convenio,
                            'visible' => 1
                        ]
                    ]);

                    if (!empty($solicitudBase->data)) {
                        $instrumentos = $solicitudBase->data[0]->instrumento_juridico ?? null;
                        $sol->instrumento_juridico = $instrumentos;
                    }
                }

                $sol->instrumento_urls = $this->mapInstrumentoUrls($instrumentos);
            }
        }
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vListaSolicitudConvenio';
        $this->_renderView($data);
    }
    
    public function activarEnvioSolicitudContrato()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();

        $id = $this->request->getPost('id_solicitud_contrato');

        if ($id) {
            $dataConfig = [
                "tabla" => "solicitud_contrato",
                "editar" => true,
                "idEditar" => ["id_solicitud_contrato" => $id]
                
            ];
            $dataInsert = [
                "ok" => 1,
                "usu_act" => $session->id_usuario, 
                
            ];

            $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Principal.php/liberarSolicitudContrato'];

            $resultado = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

            if($resultado->error){
                $response->error = true;
                $response->respuesta = $resultado->respuesta;
                return $this->respond($response);
            }
            $response->error = false;
            $response->respuesta = 'Convenio liberado';
          
        }
        return $this->respond($response);
    }
    public function activarEnvioSolicitudConvenio()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();

        $id = $this->request->getPost('id_solicitud_convenio');

        if ($id) {
            $dataConfig = [
                "tabla" => "solicitud_convenio",
                "editar" => true,
                "idEditar" => ["id_solicitud_convenio" => $id]
                
            ];
            $dataInsert = [
                "ok" => 1,
                "usu_act" => $session->id_usuario, 
                
            ];

            $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Principal.php/liberarSolicitudContrato'];

            $resultado = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

            if($resultado->error){
                $response->error = true;
                $response->respuesta = $resultado->respuesta;
                return $this->respond($response);
            }
            $response->error = false;
            $response->respuesta = 'Convenio liberado';
          
        }
        return $this->respond($response);
    }
    
    public function liberarSolicitudContrato()
    {
         $session = \Config\Services::session();
        $globals = new \App\Models\Mglobal;
        $response = new \stdClass();

        $id = $this->request->getPost('id_solicitud_contrato');

        if ($id) {
            $resultado = $globals->getTabla([
                'tabla' => 'solicitud_contrato',
                'where' => ['id_solicitud_contrato' => $id]
            ]);
          
          if(!$resultado->error){
            $datos = $resultado->data[0];
            $proveedor = $globals->getTabla(['tabla' => 'proveedor', "where" => ['rfc' => trim($datos->proveedor_rfc) ] ]);
            if(empty($proveedor->data)){
                $response->error = true;
                $response->respuesta = 'Atención el proveedor no se encontro en la base de datos';
                return $this->response->setJSON($response);
            }
            $proveedorBanco = $globals->getTabla(['tabla' => 'proveedor_banco', "where" => ['idproveedor' => $proveedor->data[0]->id_proveedor] ]);
           
             $dataInsert = [
                "id_proveedor"       => $proveedor->data[0]->id_proveedor,
                "id_estatus"         => 1,
                "id_proveedor_banco" => $proveedorBanco->data[0]->id_proveedor_banco,
                "folio"              => "PT-".date('YmdHis'),
                "no_reserva"         => '',
                "no_convenio"        => $datos->no_contrato,
                "nuevo_fondo"        => '',
                "total_importe"      => $datos->monto_total,
                "comentarios_instrumento" => $datos->objeto_contrato,
                "instrumento"        => $datos->instrumento_juridico,
                "ruta_absoluta"      => '',
                "observaciones"      => $datos->objeto_contrato,
                "fec_reg"            => date('Y-m-d H:i:s'),
                "usu_reg"            => $session->id_usuario,
                "promo"              => (in_array($session->id_usuario, [17,11, 14, 59, 38, 80]))?1:0,
            ]; 
            $dataConfig = [
                "tabla" => "reserva",
                "editar" => false,
            ];
            $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Agregar.php/guardaReserva'];

            $result =  $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
            if(!$result->error){
              $response->error = false;
              $response->respuesta = 'El contrato paso a CRFyAP';

            }
            $dataInsert = [
                 "id_reserva"  => $result->idRegistro,
                 "id_partida"  => $datos->partida,
                 "id_proyecto" => $datos->proyecto,
                 "importe"     => $datos->monto_total,
                 "fec_reg"     => date('Y-m-d H:i:s'),
                 "usu_reg"     => $session->id_usuario,
                ];
            $dataConfig = [
                "tabla" => "presupuesto",
                "editar" => false
            ];
             $result =  $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
            if($session->id_usuario != 1){
              $this->enviarEmail(1);
            }
                
        $globals->saveTabla(
            ["ok" => 2, "usu_act" => $session->id_usuario, "fec_act" => date("Y-m-d H:i:s")],
            ["tabla" => "solicitud_contrato", "editar" => true, "idEditar" => ["id_solicitud_contrato" => $id]],
            ['id_user' => $session->id_usuario, 'script' => 'Principal.php/enviarCRFyAPConvenio']
        );

        $response->error = false;
        $response->respuesta = 'El convenio paso a CRFyAP';
        return $this->respond($response);
          
          }else{
            $response->error = true;
            $response->respuesta = 'Error al liberar la solicitud';
          } 
        }
        //APL11094
        return $this->response->setJSON($response);
    }

    public function editarSolicitudConvenio($id_solicitud = null)
    {
        $session = \Config\Services::session();
        $globals = new \App\Models\Mglobal;
        
        if (!$id_solicitud) {
             return redirect()->to(base_url('index.php/Principal/ListaSolicitudConvenio'));
        }

        $solicitud = $globals->getTabla(['tabla' => 'solicitud_convenio', 'where' => ['id_solicitud_convenio' => $id_solicitud, 'visible' => 1]]);
        if (empty($solicitud->data)) {
            return redirect()->to(base_url('index.php/Principal/ListaSolicitudConvenio'));
        }

        $pagos = $globals->getTabla(['tabla' => 'solicitud_convenio_pagos', 'where' => ['id_solicitud_convenio' => $id_solicitud, 'visible' => 1]]);

        $vw_usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        $data['usuario'] = $vw_usuario->data;
        $vw_direccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1]]);
        $data['direccion'] = $vw_direccion->data;

        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];

        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];

        $data['solicitud'] = $solicitud->data[0];
        $data['solicitud']->archivo_suficiencia_url = $this->resolveStoredFileUrl($data['solicitud']->archivo_suficiencia ?? null, 'assets/uploads/convenios');
        $data['pagos'] = (!empty($pagos->data)) ? $pagos->data : [];
        $data['catalogo_firmantes'] = $this->construirCatalogoFirmantes($data['direccion'], $data['usuario']);
        $data['firmas_seleccionadas'] = $this->obtenerFirmasSolicitud($data['solicitud']);
        
        $data['scripts'] = array('inicio');
        $data['edita'] = 1;
        $data['contentView'] = 'personal/vSolicitudConvenio';
        $this->_renderView($data);
    }

    public function guardarSolicitudConvenio()
    {
        $session = \Config\Services::session();
        $globals = new \App\Models\Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al guardar la solicitud';

        $post = $this->request->getPost();
        $id_solicitud_convenio = isset($post['id_solicitud_convenio']) ? $post['id_solicitud_convenio'] : null;

        $archivo_suficiencia = '';
        if($file = $this->request->getFile('archivo_suficiencia')) {
             if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $uploadedKey = $this->uploadFileToS3Storage($file->getTempName(), 'convenios', 'suficiencia', $newName);
                if ($uploadedKey) {
                    $archivo_suficiencia = $uploadedKey;
                } else {
                    $response->respuesta = 'No fue posible guardar el archivo de suficiencia en AWS S3.';
                    return $this->respond($response);
                }
             }
        }

        $dataInsert = [
            'responsable_proyecto' => $post['responsable_proyecto'],
            'responsable_enlace' => $post['responsable_enlace'],
            'responsable_seguimiento' => $post['responsable_seguimiento'],
            'proyecto' => $post['proyecto'],
            'partida' => $post['partida'],
            'monto_secturi' => $post['monto_secturi'],
            'monto_federal' => $post['monto_federal'],
            'monto_otra' => $post['monto_otra'],
            'monto_total' => $post['monto_total'],
            'objeto_convenio' => $post['objeto_convenio'],
            'fecha_inicio' => $post['fecha_inicio'],
            'fecha_termino' => $post['fecha_termino'],
            'proveedor_nombre' => $post['proveedor_nombre'],
            'proveedor_domicilio' => $post['proveedor_domicilio'],
            'proveedor_rfc' => $post['proveedor_rfc'],
            'proveedor_cedula' => $post['proveedor_cedula'],
            'proveedor_representante' => $post['proveedor_representante'],
            'proveedor_asistente' => $post['proveedor_asistente'] ?? null,
            'proveedor_seguimiento' => $post['proveedor_seguimiento'],
            'proveedor_correo' => $post['proveedor_correo'],
            'no_delegatorio_1' => null,
            'no_delegatorio_2' => null,
            'no_delegatorio_3' => null,
        ];

        $delegatorios = isset($post['no_delegatorio']) && is_array($post['no_delegatorio']) ? $post['no_delegatorio'] : [];
        $delegatoriosActivos = isset($post['usar_no_delegatorio']) && is_array($post['usar_no_delegatorio']) ? $post['usar_no_delegatorio'] : [];

        foreach ([1, 2, 3] as $indice) {
            $posicion = $indice - 1;
            if (isset($delegatoriosActivos[$posicion]) && trim((string) ($delegatorios[$posicion] ?? '')) !== '') {
                $dataInsert['no_delegatorio_' . $indice] = trim((string) $delegatorios[$posicion]);
            }
        }

        if (!empty($archivo_suficiencia)) {
            $dataInsert['archivo_suficiencia'] = $archivo_suficiencia;
        }

        $dataConfig = ["tabla" => "solicitud_convenio", "editar" => false];
        
        if ($id_solicitud_convenio) {
             $dataInsert['id_estatus'] = 1;
            $dataConfig = [
                "tabla" => "solicitud_convenio", 
                "editar" => true, 
                "idEditar" => ['id_solicitud_convenio' => $id_solicitud_convenio]
            ];
            $dataInsert['usu_act'] = $session->id_usuario ?? 0;
            $dataInsert['fec_act'] = date('Y-m-d H:i:s');
        } else {
             $dataInsert['usu_reg'] = $session->id_usuario ?? 0;
             $dataInsert['fec_reg'] = date('Y-m-d H:i:s');
        }

        $dataBitacora = ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/guardarSolicitudConvenio'];
        $res = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        if (!$res->error) {
            $id_solicitud = $id_solicitud_convenio ? $id_solicitud_convenio : $res->idRegistro;
            $this->guardarFirmasSolicitud(
                $globals,
                'solicitud_convenio',
                'id_solicitud_convenio',
                (int) $id_solicitud,
                $post['firmas'] ?? [],
                (int) ($session->id_usuario ?? 0),
                'Principal.php/guardarSolicitudConvenioFirmas'
            );
            
            if ($id_solicitud_convenio) {
                $globals->saveTabla(
                    ['visible' => 0], 
                    [
                        "tabla" => "solicitud_convenio_pagos", 
                        "editar" => true, 
                        "idEditar" => ['id_solicitud_convenio' => $id_solicitud_convenio]
                    ], 
                    ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/eliminarPagosAntiguos']
                );
            }

            if(isset($post['pagos']) && is_array($post['pagos'])){
                foreach($post['pagos'] as $pago){
                    $dataPago = [
                        'id_solicitud_convenio' => $id_solicitud,
                        'numero_pago' => $pago['numero'],
                        'monto' => $pago['monto'],
                        'fecha' => $pago['fecha'],
                        'entregable' => $pago['entregable'],
                        'visible' => 1
                    ];
                    $res = $globals->saveTabla($dataPago, ["tabla" => "solicitud_convenio_pagos", "editar" => false], ["id_user" => $session->id_usuario ?? 0, 'script' => 'Principal.php/guardarSolicitudConvenio']);
                }
            }
          
            $response->error = false;
            $response->respuesta = 'Solicitud guardada correctamente';
        } else {
            $response->respuesta = $res->respuesta;
        }

        return $this->respond($response);
    }

    public function eliminarSolicitudConvenio()
    {
        $session = \Config\Services::session();
        $globals = new \App\Models\Mglobal;
        $response = new \stdClass();
        $response->error = true;
        
        $id_solicitud = $this->request->getPost('id_solicitud');
        
        if($id_solicitud){
            $dataUpdate = ['visible' => 0];
            $dataBitacora = ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/eliminarSolicitudConvenio'];
            $res = $globals->saveTabla($dataUpdate, ["tabla" => "solicitud_convenio", "editar" => true, "idEditar" => ['id_solicitud_convenio' => $id_solicitud]], $dataBitacora);
            
            if(!$res->error){
                $response->error = false;
                $response->respuesta = 'Solicitud eliminada correctamente';
            } else {
                $response->respuesta = $res->respuesta;
            }
        } else {
            $response->respuesta = 'ID no válido';
        }
        
        return $this->respond($response);
    }

    public function declinarSolicitudConvenio()
    {
        $session = \Config\Services::session();
        $globals = new \App\Models\Mglobal;
        $emailService = \Config\Services::email();
        $response = new \stdClass();
        $response->error = true;

        $id = $this->request->getPost('id_solicitud');
        $motivo = $this->request->getPost('motivo');

        if (!$id) {
            $response->respuesta = "ID de solicitud no válido.";
            return $this->respond($response);
        }

        $dataConfig = [
            "tabla" => "solicitud_convenio",
            "editar" => true,
            "idEditar" => ["id_solicitud_convenio" => $id]
        ];

        $dataUpdate = [
            "id_estatus" => 2,
            "motivo" => $motivo,
            "usu_act" => $session->id_usuario ?? 0,
            "fec_act" => date('Y-m-d H:i:s')
        ];

        $res = $globals->saveTabla($dataUpdate, $dataConfig, ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/declinarSolicitudConvenio']);

        if (!$res->error) {
            $solicitudQuery = $globals->getTabla(["tabla" => "solicitud_convenio", "where" => ["id_solicitud_convenio" => $id]]);
            if(isset($solicitudQuery->data) && !empty($solicitudQuery->data)){
               $usu_reg = $solicitudQuery->data[0]->usu_reg;
               $usuarioQuery = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["id_usuario" => $usu_reg]]);
               if(isset($usuarioQuery->data) && !empty($usuarioQuery->data) && !empty($usuarioQuery->data[0]->correo)){
                   $correoDestino = $usuarioQuery->data[0]->correo;
                   $nombreUsuario = $usuarioQuery->data[0]->nombre_completo ?? 'Usuario';
                   
                   $emailService->setFrom('noreply@susi.gob.mx', 'SUSI - SECTURI');
                   $emailService->setTo($correoDestino);
                   $emailService->setSubject('Solicitud de Convenio Declinada');
                   $emailService->setMailType('html');
                   $emailService->setMessage("
                       <p>Buen día, <strong>{\$nombreUsuario}</strong>:</p>
                       <p>Se le notifica que su solicitud de elaboración de convenio con ID <strong>{\$id}</strong> ha sido <strong>declinada</strong>.</p>
                       <p><strong>Motivo:</strong> {\$motivo}</p>
                       <br>
                       <p>Saludos cordiales,</p>
                   ");
                   $emailService->send();
               }
            }

            $response->error = false;
            $response->respuesta = "Solicitud declinada correctamente.";
        } else {
            $response->respuesta = "No se pudo declinar la solicitud.";
        }

        return $this->respond($response);
    }
    
    public function subirInstrumentoJuridicoConvenio()
    {
        $session = \Config\Services::session();
        $globals = new \App\Models\Mglobal;
        $emailService = \Config\Services::email();
        $response = new \stdClass();
        $response->error = true;

        $id = $this->request->getPost('id_solicitud');
        
        if (!$id) {
            $response->respuesta = "ID no válido.";
            return $this->respond($response);
        }

        $exito = false;
        $rutasGuardadas = [];
        
        $solicitud_bd = $globals->getTabla(['tabla' => 'solicitud_convenio', 'where' => ['id_solicitud_convenio' => $id]]);
        if(isset($solicitud_bd->data) && !empty($solicitud_bd->data)) {
            $antiguo_json = $solicitud_bd->data[0]->instrumento_juridico;
            if(!empty($antiguo_json)) {
                $decoded = json_decode($antiguo_json, true);
                if(is_array($decoded)) {
                    $rutasGuardadas = $decoded;
                } else {
                    $rutasGuardadas[] = $antiguo_json;
                }
            }
        }

        if (isset($_FILES['archivos']) && is_array($_FILES['archivos']['name'])) {
            $archivosSeleccionados = array_values(array_filter($_FILES['archivos']['name'], static fn($nombre) => !empty($nombre)));
            if ((count($rutasGuardadas) + count($archivosSeleccionados)) > 4) {
                $response->respuesta = "Solo se permiten hasta 4 instrumentos juridicos.";
                return $this->respond($response);
            }

            foreach ($_FILES['archivos']['name'] as $key => $originalName) {
                if ($_FILES['archivos']['error'][$key] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['archivos']['tmp_name'][$key];
                    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                    $randHash = substr(md5(uniqid(rand(), true)), 0, 8);
                    $newName = 'Inst_Convenio_' . $id . '_' . $randHash . '.' . $ext;
                    $s3Key = $this->uploadFileToS3Storage($tmpName, 'convenios', 'instrumentos', $newName);
                    
                    if ($s3Key) {
                        $rutasGuardadas[] = $s3Key;
                        $exito = true;
                    }
                }
            }
        }

        if ($exito) {
            $instrumento_json = json_encode($rutasGuardadas);
            $dataConfig = [
                "tabla" => "solicitud_convenio",
                "editar" => true,
                "idEditar" => ["id_solicitud_convenio" => $id]
            ];

            $dataUpdate = [
                "id_estatus" => 3, 
                "instrumento_juridico" => $instrumento_json,
                "usu_act" => $session->id_usuario ?? 0,
                "fec_act" => date('Y-m-d H:i:s')
            ];

            $res = $globals->saveTabla($dataUpdate, $dataConfig, ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/subirInstrumentoJuridicoConvenio']);

            if (!$res->error) {
                $response->error = false;
                $response->respuesta = "Instrumento subido y solicitud aprobada.";
                
                $solicitudQuery = $globals->getTabla(["tabla" => "solicitud_convenio", "where" => ["id_solicitud_convenio" => $id]]);
                if(isset($solicitudQuery->data) && !empty($solicitudQuery->data)){
                   $usu_reg = $solicitudQuery->data[0]->usu_reg;
                   $usuarioQuery = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["id_usuario" => $usu_reg]]);
                   if(isset($usuarioQuery->data) && !empty($usuarioQuery->data) && !empty($usuarioQuery->data[0]->correo)){
                       $correoDestino = $usuarioQuery->data[0]->correo;
                       $nombreUsuario = $usuarioQuery->data[0]->nombre_completo ?? 'Usuario';
                       $enlaceListado = 'https://secturnet.guanajuato.gob.mx/susi/index.php/Principal/ListaSolicitudConvenio';
                       
                       $emailService->setFrom('noreply@susi.gob.mx', 'SUSI - SECTURI');
                       $emailService->setTo($correoDestino);
                       $emailService->setSubject('Solicitud de Convenio Aprobada - Instrumento Jurídico Disponible');
                       $emailService->setMailType('html');
                       $emailService->setMessage("
                           <p>Buen día, <strong>{\$nombreUsuario}</strong>:</p>
                           <p>El área Jurídica ha autorizado y adjuntado el/los instrumentos jurídicos correspondientes a su solicitud de convenio con ID <strong>{\$id}</strong>.</p>
                           <p>Puede consultar y descargar los documentos ingresando al sistema SUSI.</p>
                           <br>
                           <p>Saludos cordiales,</p>
                       ");
                       $emailService->setMessage("
                            <p>Buen día, <strong>{$nombreUsuario}</strong>:</p>
                            <p>El área Jurídica ha autorizado y adjuntado el/los instrumentos jurídicos correspondientes a su solicitud de convenio con ID <strong>{$id}</strong>.</p>
                            <p>Puede consultar y descargar los documentos ingresando al siguiente enlace:</p>
                            <p><a href='{$enlaceListado}' target='_blank'>{$enlaceListado}</a></p>
                            <br>
                            <p>Saludos cordiales,</p>
                            <p><strong>Sistema Unificado SECTURI (SUSI)</strong></p>
                        ");
                       try {
                           $emailService->send();
                       } catch (\Throwable $e) {
                           log_message('error', 'Error enviando correo de convenio aprobado: ' . $e->getMessage());
                       }
                   }
                }

            } else {
                $response->respuesta = "No se pudo actualizar el estatus.";
            }
        } else {
            $response->respuesta = "Hubo un error al mover los archivos adjuntos.";
        }

        return $this->respond($response);
    }

    public function fichaTecnica()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $fichas = $globals->getTabla(['tabla' => 'ficha_tecnica', 'where' => ['visible' => 1]]);
        $data['fichas'] = $fichas->data;
    
        $data['contentView'] = 'secciones/vFichaTecnica';
        $this->_renderView($data);

    }
    public function pdfFicha()
    {
        // setlocale(LC_TIME, 'es_ES');
        $id = $this->request->getGet('id_ficha_tecnica');
          if(!$id){
            echo "ID no válido"; return;
        }

        $session = \Config\Services::session();
        $globals = new \App\Models\Mglobal;
        
        $ficha = $globals->getTabla(['tabla' => 'ficha_tecnica', 'where' => ['id_ficha_tecnica' => $id]])->data[0];

        $data = array();
        $data['id_ficha_tecnica']   = $ficha->id_ficha_tecnica;
        $data['em_domicilio']       = $ficha->em_domicilio;
        $data['fecha_realizacion']  = $ficha->fecha_realizacion;
        $data['nombre_evento']      = $ficha->nombre_evento;
        $data['persona_solicitud']  = $ficha->persona_solicitud;
        $data['edicion']            = $ficha->edicion;
        $data['periodicidad_desc']  = $ficha->periodicidad_desc;
        $data['municipio_sede']     = $ficha->municipio_sede;
        $data['periodicidad_radio'] = $ficha->periodicidad_radio;
        $data['antecedentes']       = $ficha->antecedentes;
        $data['objetivo_general']   = $ficha->objetivo_general;
        $data['justificacion']      = $ficha->justificacion;
        $data['nivel_habilidades']  = $ficha->nivel_habilidades;
        $data['estrato']            = $ficha->estrato;
        $data['asistentes_totales'] = $ficha->asistentes_totales;
        $data['asistentes_local']   = $ficha->asistentes_local;
        $data['asistentes_regional']= $ficha->asistentes_regional;
        $data['asistentes_nacional']= $ficha->asistentes_nacional;
        $data['asistentes_internacional'] = $ficha->asistentes_internacional;
        $data['alcance']           = $ficha->alcance;
        $data['derrama_total']     = $ficha->derrama_total;
        $data['derrama_local']     = $ficha->derrama_local;
        $data['derrama_foraneo']   = $ficha->derrama_foraneo;
        $data['empleos_mujeres']   = $ficha->empleos_mujeres;
        $data['empleos_hombres']   = $ficha->empleos_hombres;
        $data['empleos_discapacidad'] = $ficha->empleos_discapacidad;
        $data['cuota_acceso']     = (isset($ficha->cuota_acceso) && !empty($ficha->cuota_acceso)) ? $ficha->cuota_acceso : 'N/A';
        $data['cuantas_cuotas']   = (isset($ficha->cuantas_cuotas) && !empty($ficha->cuantas_cuotas)) ? $ficha->cuantas_cuotas : 'N/A';
        $data['costo_total']      = (isset($ficha->costo_total) && !empty($ficha->costo_total)) ? $ficha->costo_total : 'N/A';
        $data['desglose_costo']      = $ficha->desglose_costo;
        $data['cantidades_desglose']      = $ficha->cantidades_desglose;
        $data['montos_desglose']      = $ficha->montos_desglose;
        $data['antecedentes_evento']      = $ficha->antecedentes_evento;
        $data['propuesta_valor']      = $ficha->propuesta_valor;
        $data['inclusion_mujeres']      = $ficha->inclusion_mujeres;
        $data['programa_preliminar']      = $ficha->programa_preliminar;
        $data['otras_actividades']      = $ficha->otras_actividades;
        $data['link_web']      = (isset($ficha->link_web) && !empty($ficha->link_web)) ? $ficha->link_web : 'N/A';
        $data['facebook']      = (isset($ficha->facebook) && !empty($ficha->facebook)) ? $ficha->facebook : 'N/A';
        $data['fb_seguidores']      = (isset($ficha->fb_seguidores) && !empty($ficha->fb_seguidores)) ? $ficha->fb_seguidores : 'N/A';
        $data['twitter']      = (isset($ficha->twitter) && !empty($ficha->twitter)) ? $ficha->twitter : 'N/A';
        $data['tw_seguidores']      = (isset($ficha->tw_seguidores) && !empty($ficha->tw_seguidores)) ? $ficha->tw_seguidores : 'N/A';
        $data['youtube']      = (isset($ficha->youtube) && !empty($ficha->youtube)) ? $ficha->youtube : 'N/A';
        $data['ig_seguidores']      = $ficha->ig_seguidores;
        $data['twitter']      = $ficha->twitter;
        $data['yt_seguidores']      = $ficha->yt_seguidores;
        $data['instagram']      = $ficha->instagram;
        $data['tiktok']      = $ficha->tiktok;
        $data['tk_seguidores']      = $ficha->tk_seguidores;
        $data['co_nombre']      = $ficha->co_nombre;
        $data['co_telefono']      = $ficha->co_telefono;
        $data['co_cargo']      = $ficha->co_cargo;
        $data['co_celular']      = $ficha->co_celular;
        $data['co_domicilio']      = $ficha->co_domicilio;
        $data['co_ciudad_estado']      = $ficha->co_ciudad_estado;
        $data['co_email']      = $ficha->co_email;
        $data['em_nombre']      = $ficha->em_nombre;
        $data['em_cargo']      = $ficha->em_cargo;
        $data['em_celular']      = $ficha->em_celular;
        $data['em_telefono_fijo']      = $ficha->em_telefono_fijo;
        $data['em_ciudad_estado']      = $ficha->em_ciudad_estado;
        $data['apoyo_federal']      = (isset($ficha->apoyo_federal) && !empty($ficha->apoyo_federal)) ? $ficha->apoyo_federal : 'N/A';
        $data['apoyo_municipal']      = (isset($ficha->apoyo_municipal) && !empty($ficha->apoyo_municipal)) ? $ficha->apoyo_municipal : 'N/A';
        $data['apoyo_estatal']      = (isset($ficha->apoyo_estatal) && !empty($ficha->apoyo_estatal)) ? $ficha->apoyo_estatal : 'N/A';
        $data['descripcion_apoyos']      = (isset($ficha->descripcion_apoyos) && !empty($ficha->descripcion_apoyos)) ? $ficha->descripcion_apoyos : 'N/A';
        $data['fecha_registro']      = (isset($ficha->fecha_registro) && !empty($ficha->fecha_registro)) ? $ficha->fecha_registro : 'N/A';

      // die( var_dump( $ficha ) );
      
       // $data['ficha'] = $ficha;

        // Similar to Contrato PDF View
        $html = view('personal/vFicha', $data);
        
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'format' => 'Letter'
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Ficha_Tecnica' . $id . '.pdf', 'I');
        exit();
 
    }
    public function enviarFichaTecnica()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $emailService = \Config\Services::email();
        $id = $this->request->getGet('id_ficha_tecnica');

        if (empty($id)) {
            return redirect()->to(base_url('index.php/Principal/fichaTecnica'))
                ->with('error', 'No se recibió el identificador de la ficha técnica.');
        }

        $ficha = $globals->getTabla(['tabla' => 'ficha_tecnica', 'where' => ['id_ficha_tecnica' => $id, 'visible' => 1]]);
        $result = (!empty($ficha->data)) ? $ficha->data[0] : null;

        if (!$result) {
            return redirect()->to(base_url('index.php/Principal/fichaTecnica'))
                ->with('error', 'La ficha técnica no fue encontrada.');
        }

        if (empty($result->co_email)) {
            return redirect()->to(base_url('index.php/Principal/fichaTecnica'))
                ->with('error', 'La ficha técnica no cuenta con correo en el campo co_email.');
        }

        $dataFicha = $this->normalizeUtf8Value($this->obtenerFichaTecnicaData($result));
        $pdfPath = '';

        try {
            $pdfPath = $this->generarPdfFichaTemporal($id, $dataFicha);

            $nombreDestinatario = !empty($result->co_nombre) ? $result->co_nombre : 'Usuario';
            $nombreEvento = !empty($result->nombre_evento) ? $result->nombre_evento : ('Ficha técnica ' . $id);

            $emailService->setFrom('noreply@susi.gob.mx', 'SUSI - SECTURI');
            $emailService->setTo($result->co_email);
            $emailService->setSubject('Envío de ficha técnica - ' . $nombreEvento);
            $emailService->setMailType('html');
            $emailService->setMessage('
                <p>Buen día, <strong>' . esc($nombreDestinatario) . '</strong>:</p>
                <p>Por este medio se comparte la ficha técnica correspondiente al evento <strong>' . esc($nombreEvento) . '</strong>.</p>
                <p>Se adjunta el archivo PDF para su consulta.</p>
                <br>
                <p>Saludos cordiales,</p>
                <p><strong>SUSI - SECTURI</strong></p>
            ');
            $emailService->attach($pdfPath);

            if (!$emailService->send()) {
                if ($pdfPath !== '' && file_exists($pdfPath)) {
                    unlink($pdfPath);
                }

                return redirect()->to(base_url('index.php/Principal/fichaTecnica'))
                    ->with('error', 'No se pudo enviar el correo. Verifica la configuración del servidor de correo.');
            }

            if ($pdfPath !== '' && file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            $globals->saveTabla(
                [
                    'id_estatus' => 2,
                    'usu_act' => $session->get('id_usuario'),
                    'fec_act' => date('Y-m-d H:i:s'),
                ],
                [
                    'tabla' => 'ficha_tecnica',
                    'editar' => true,
                    'idEditar' => ['id_ficha_tecnica' => $id],
                ],
                [
                    'id_user' => $session->get('id_usuario'),
                    'script' => 'Principal.php/enviarFichaTecnica',
                ]
            );

            return redirect()->to(base_url('index.php/Principal/fichaTecnica'))
                ->with('success', 'La ficha técnica se envió correctamente a ' . $result->co_email . '.');
        } catch (\Throwable $e) {
            if ($pdfPath !== '' && file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            return redirect()->to(base_url('index.php/Principal/fichaTecnica'))
                ->with('error', 'Ocurrió un problema al generar o enviar la ficha técnica: ' . $e->getMessage());
        }
    }
    
    public function subirArchivosSolicitudConvenio()
    {
        $session = \Config\Services::session();
        $id_solicitud = $this->request->getPost('id_solicitud');
        $documentos_seleccionados = $this->request->getPost('documentos');

        if (!$id_solicitud || empty($documentos_seleccionados)) {
            return redirect()->to(base_url('index.php/Principal/ListaSolicitudConvenio'));
        }

        $data['id_solicitud'] = $id_solicitud;
        $data['documentos'] = $documentos_seleccionados;
        
        $data['contentView'] = 'secciones/vSubirArchivosSolicitudConvenio';
        $this->_renderView($data);
    }

    public function guardarArchivosSolicitudConvenio()
    {
        $session = \Config\Services::session();
        $globals = new \App\Models\Mglobal;
        $response = new \stdClass();
        $response->error = true;

        $id_solicitud = $this->request->getPost('id_solicitud');
        
        if (!$id_solicitud) {
            $response->respuesta = "ID de solicitud no válido.";
            return $this->respond($response);
        }

        $count = 0;
        $errores = 0;
        
        if (isset($_FILES['archivos']) && is_array($_FILES['archivos']['name'])) {
            $guardarArchivoConvenio = function ($key, $originalName, $tmpName, $error, $indice = 0) use ($globals, $session, $id_solicitud, &$count, &$errores) {
                if (empty($originalName)) {
                    return;
                }

                if ($error !== UPLOAD_ERR_OK) {
                    $errores++;
                    return;
                }

                $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                $newName = $id_solicitud . '_' . $key . '_' . time() . '_' . $indice . '.' . $ext;
                    $s3Key = $this->uploadFileToS3Storage($tmpName, 'convenios', 'documentos', $newName);
                    
                if ($s3Key) {
                    $dataInsert = [
                        'id_solicitud_convenio' => $id_solicitud,
                        'clave_documento' => $key,
                        'nombre_archivo' => $s3Key,
                        'tipo' => $ext,
                        'usu_reg' => $session->id_usuario ?? 0,
                        'fec_reg' => date('Y-m-d H:i:s'),
                        'visible' => 1
                    ];
                    
                    $res = $globals->saveTabla($dataInsert, ["tabla" => "solicitud_convenio_archivos", "editar" => false], ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/subirArchivosSolicitudConvenio']);
                    $globals->saveTabla(['id_estatus' => 4], ["tabla" => "solicitud_convenio", "editar" => true, "idEditar" => ["id_solicitud_convenio" => $id_solicitud]], ['id_user' => $session->id_usuario ?? 0, 'script' => 'Principal.php/subirArchivosSolicitudConvenio']);
                    if (!$res->error) {
                        $count++;
                    } else {
                        $errores++;
                    }
                } else {
                    $errores++;
                }
            };

            foreach ($_FILES['archivos']['name'] as $key => $originalName) {
                if (is_array($originalName)) {
                    $limite = in_array((int) $key, [3, 11], true) ? 4 : 1;
                    foreach (array_slice($originalName, 0, $limite) as $indice => $nombreArchivo) {
                        $guardarArchivoConvenio(
                            $key,
                            $nombreArchivo,
                            $_FILES['archivos']['tmp_name'][$key][$indice] ?? '',
                            $_FILES['archivos']['error'][$key][$indice] ?? UPLOAD_ERR_NO_FILE,
                            $indice
                        );
                    }
                    continue;
                }

                $guardarArchivoConvenio(
                    $key,
                    $originalName,
                    $_FILES['archivos']['tmp_name'][$key] ?? '',
                    $_FILES['archivos']['error'][$key] ?? UPLOAD_ERR_NO_FILE
                );
            }
        }
        
        if ($count > 0) {
            $emailService = \Config\Services::email();
            $usuarioQuery = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["id_usuario" => ($session->id_usuario ?? 0)]]);
            $nombreUsuario = (isset($usuarioQuery->data) && !empty($usuarioQuery->data)) ? $usuarioQuery->data[0]->nombre_completo : 'Usuario Desconocido';
            $enlace = base_url('index.php/Principal/ListaSolicitudConvenio');
            
            $emailService->setFrom('noreply@susi.gob.mx', 'SUSI - SECTURI');
            $emailService->setTo($this->obtenerCorreosRevisionJuridica());
            //$emailService->setTo('palafox.marin@hotmail.com');
            $emailService->setSubject('Nueva Solicitud de Convenio - Archivos Adjuntados');
            $emailService->setMailType('html');
            $emailService->setMessage("
                <p>Buen día,</p>
                <p>Se le notifica que se han subido documentos para la solicitud de convenio con ID <strong>$id_solicitud</strong>.</p>
                <p>Los archivos fueron agregados por el usuario: <strong>$nombreUsuario</strong>.</p>
                <p>Puede consultar los detalles ingresando al siguiente enlace: <a href='$enlace'>$enlace</a></p>
                <br>
                <p>Saludos cordiales,</p>
                <p><strong>Sistema Unificado SECTURI (SUSI)</strong></p>
            ");
            $emailService->send();

            $response->error = false;
            $msg = "Se guardaron $count archivos correctamente.";
            if ($errores > 0) $msg .= " Hubo problemas con $errores archivos.";
            $response->respuesta = $msg;
        } else {
            $response->respuesta = "No se guardó ningún archivo. " . ($errores > 0 ? "Hubo errores al procesar." : "No se seleccionaron archivos.");
        }

        return $this->respond($response);
    }

    public function verArchivosSolicitudConvenio($id_solicitud)
    {
        $session = \Config\Services::session();
        $globals = new \App\Models\Mglobal;
        
        $archivos = $globals->getTabla([
            'tabla' => 'solicitud_convenio_archivos',
            'where' => ['id_solicitud_convenio' => $id_solicitud, 'visible' => 1]
        ]);

        if (!empty($archivos->data)) {
            foreach ($archivos->data as &$archivo) {
                $archivo->url_descarga = $this->resolveStoredFilePreviewUrl($archivo->nombre_archivo ?? null, 'assets/uploads/convenios');
            }
        }
       // die( var_dump($archivos->data));
        $data['id_solicitud'] = $id_solicitud;
        $data['archivos'] = (!empty($archivos->data)) ? $archivos->data : [];
        $data['modulo_archivos'] = 'convenio';
        $data['scripts'] = array();
        // Reciclamos la vista de archivos pasándole si es convenio o contrato
        // o creamos una view sencilla:
        $data['contentView'] = 'secciones/vVerArchivosSolicitud';
        
        $this->_renderView($data);
    }
    
    public function verSolicitudConvenioPDF($id = null)
    {
        if(!$id){
            echo "ID no válido"; return;
        }

        $session = \Config\Services::session();
        $globals = new \App\Models\Mglobal;
        
        $solicitud = $globals->getTabla(['tabla' => 'vw_solicitud_convenio', 'where' => ['id_solicitud_convenio' => $id]]);
        $solicitudBase = $globals->getTabla(['tabla' => 'solicitud_convenio', 'where' => ['id_solicitud_convenio' => $id, 'visible' => 1]]);
        $pagos = $globals->getTabla(['tabla' => 'solicitud_convenio_pagos', 'where' => ['id_solicitud_convenio' => $id, 'visible' => 1]]);
        
        if(empty($solicitud->data)){
            echo "Solicitud no encontrada"; return;
        }

        $data['solicitud'] = $this->normalizeUtf8Value($solicitud->data[0]);
        if (!empty($solicitudBase->data)) {
            foreach ((array) $solicitudBase->data[0] as $key => $value) {
                if (!isset($data['solicitud']->$key) || $data['solicitud']->$key === null || $data['solicitud']->$key === '') {
                    $data['solicitud']->$key = $this->normalizeUtf8Value($value);
                }
            }
        }
        $data['pagos'] = $this->normalizeUtf8Value((!empty($pagos->data)) ? $pagos->data : []);
        $nombreProyectoConPuesto = $this->obtenerNombreConPuesto($globals, $data['solicitud']->responsable_proyecto ?? null, true);
        $nombreSeguimientoConPuesto = $this->obtenerNombreConPuesto($globals, $data['solicitud']->responsable_seguimiento ?? null, false);
        $data['solicitud']->nombre_proyecto_puesto = $nombreProyectoConPuesto !== '' ? $nombreProyectoConPuesto : ($data['solicitud']->nombre_proyecto ?? '');
        $data['solicitud']->nombre_seguimiento_puesto = $nombreSeguimientoConPuesto !== '' ? $nombreSeguimientoConPuesto : ($data['solicitud']->nombre_seguimiento ?? '');
        $data['firmas_pdf'] = $this->obtenerFirmasSolicitudDetalle($globals, $data['solicitud']);
        foreach ($data['firmas_pdf'] as $indice => $firma) {
            $firma->no_delegatorio = $data['solicitud']->{'no_delegatorio_' . ($indice + 1)} ?? '';
        }

        $montoFields = [
            'monto_secturi',
            'monto_federal',
            'monto_otra',
            'monto_total',
        ];

        foreach ($montoFields as $field) {
            $monto = (float) str_replace([',', '$', ' '], '', (string) ($data['solicitud']->$field ?? 0));
            $textoField = $field . '_letra';
            $data['solicitud']->$textoField = $monto > 0 ? $this->numeroEnLetras($monto) : 'CERO PESOS 00/100 M.N.';
        }
        
        // Similar to Contrato PDF View
        $html = view('personal/vPdfSolicitudConvenio', $data);
        $html = $this->normalizeUtf8Value($html);
        $html = $this->cleanMpdfHtml($html);
        
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 5,
            'margin_left' => 6,
            'margin_right' => 6,
            'margin_bottom' => 5,
            'format' => 'Letter'
        ]);
        $mpdf->shrink_tables_to_fit = 1;

        $mpdf->WriteHTML($html);
        $mpdf->Output('Solicitud_Convenio_' . $id . '.pdf', 'I');
        exit();
    }

}
