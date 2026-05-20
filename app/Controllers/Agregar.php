<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Curps;
use App\Libraries\Fechas;
use App\Libraries\Funciones;
use App\Models\Mglobal;
use App\Models\Magregarturno;
use Config\Services;



use DateTime;
use DatePeriod;
use DateInterval;



require_once FCPATH . "qr_code/autoload.php";
require_once FCPATH . "mpdf/autoload.php";


use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;


use stdClass;
use Exception;
use CodeIgniter\API\ResponseTrait;

class Agregar extends BaseController
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
        $session = \Config\Services::session();
        $Mglobal = new Mglobal;
        $data = array_merge($this->defaultData, $data);
        echo view($data['layout'], $data);
    }

    private function uploadManualFacturaFileToS3(string $tmpName, string $extension, $idRegistroPt, $rowIndex, string $tipoArchivo, string $prefijo = 'PT')
    {
        $s3 = new \App\Libraries\S3Service();
        $folder = strtoupper($tipoArchivo) === 'XML' ? 'FACTURAS/XML' : 'FACTURAS/PDF';
        $safeExtension = strtolower($extension);
        $fileName = sprintf(
            '%s_%s_ROW_%s_%s.%s',
            strtoupper($prefijo),
            $idRegistroPt,
            $rowIndex,
            uniqid(),
            $safeExtension
        );

        $s3Key = $folder . '/' . $fileName;
        $uploaded = $s3->uploadFile($tmpName, $s3Key);

        return $uploaded ? $s3Key : null;
    }

    private function resolveManualFacturaFilePath(?string $storedPath, string $prefix = 'factura_')
    {
        if (empty($storedPath)) {
            return null;
        }

        if (strpos($storedPath, 'FACTURAS/') === 0) {
            $s3 = new \App\Libraries\S3Service();
            return $s3->downloadToTempFile($storedPath, $prefix);
        }

        $fullPath = FCPATH . ltrim($storedPath, '/\\');
        return file_exists($fullPath) ? $fullPath : null;
    }

    private function extractManualFacturaXmlTotals(?string $xmlContent): array
    {
        $result = [
            'isr' => 0.00,
            'impuesto_local' => 0.00,
            'xml_subtotal' => 0.00,
        ];

        if (empty($xmlContent)) {
            return $result;
        }

        try {
            $xmlContent = str_replace(['cfdi:', 'implocal:', 'tfd:'], '', $xmlContent);
            $xmlObj = @simplexml_load_string($xmlContent);

            if (!$xmlObj) {
                return $result;
            }

            if (isset($xmlObj['SubTotal'])) {
                $result['xml_subtotal'] = (float) $xmlObj['SubTotal'];
            }

            if (isset($xmlObj->Impuestos) && isset($xmlObj->Impuestos['TotalImpuestosRetenidos'])) {
                $result['isr'] = (float) $xmlObj->Impuestos['TotalImpuestosRetenidos'];
            }

            if (isset($xmlObj->Complemento) && isset($xmlObj->Complemento->ImpuestosLocales) && isset($xmlObj->Complemento->ImpuestosLocales['TotaldeRetenciones'])) {
                $result['impuesto_local'] = (float) $xmlObj->Complemento->ImpuestosLocales['TotaldeRetenciones'];
            }
        } catch (\Throwable $e) {
        }

        return $result;
    }


    public function index()
    {
        $session = \Config\Services::session();
        $data = array();
        $catalogos = new Mglobal;
        $subordinados = $catalogos->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_jefe_inmediato' => $session->id_usuario]])->data;
        $esJefe = (!empty($subordinados)) ? true : false;


        $data['scripts'] = array('principal', 'agregar');
        $data['edita'] = 0;
        $data['esJefe'] = $esJefe;
        $data['nombre_completo'] = $session->nombre_completo;
        $data['contentView'] = 'formularios/vFormAgregar';
        $this->_renderView($data);
    }
    public function procesarPediodoGo($periodo = array(), $id_registro_go = null)
    {
        $session = \Config\Services::session();
        $data = array();
        $response = new \stdClass();
        $this->globals = new Mglobal();



        foreach ($periodo as $p) {


            $esAnidado = (is_array($p['encabezado']) && is_array($p['encabezado']));
            $esNormal = (is_string($p['encabezado']) && is_string($p['encabezado']));

            if ($esAnidado) {
                // Estructura anidada: múltiples registros
                foreach ($p['encabezado'] as $index => $encabezado) {
                    // Iterar sobre cada conjunto de datos

                    $dataConfig = [
                        "tabla" => "periodo_factura_go",
                        "editar" => false
                    ];

                    $dataInsert = [
                        'id_registro_go' => (int) $id_registro_go,
                        'encabezado' => $p['encabezado'][$index] ?? $p['encabezado'][0] ?? null,
                        'importe' => (int) $p['importe'][$index] ?? null,
                        'periodo_inicio' => date('Y-m-d', strtotime($p['periodo_inicio'][$index])) ?? null,
                        'periodo_fin' => date('Y-m-d', strtotime($p['periodo_fin'][$index])) ?? null,
                        'propina' => (int) $p['propina'][$index] ?? null,

                    ];

                    $dataBitacora = [
                        'id_user' => $session->get('id_usuario'),
                        'script' => 'Agregar.php/guardarFacturaPDF'
                    ];

                    $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);


                }
            } else if ($esNormal) {
                // Estructura normal: un solo registro
                // Iterar sobre cada conjunto de datos
                $count = count($p['encabezado']);

                for ($i = 0; $i < $count; $i++) {
                    $dataConfig = [
                        "tabla" => "periodo_factura_go",
                        "editar" => false
                    ];

                    $dataInsert = [
                        'id_registro_go' => (int) $id_registro_go,
                        'encabezado' => $p['encabezado'][$i] ?? $p['encabezado'][0] ?? null,
                        'importe' => (int) $p['importe'][$i] ?? null,
                        'periodo_inicio' => date('Y-m-d', strtotime($p['periodo_inicio'][$i])) ?? null,
                        'periodo_fin' => date('Y-m-d', strtotime($p['periodo_fin'][$i])) ?? null,
                        'propina' => (int) $p['propina'][$i] ?? null,

                    ];

                    $dataBitacora = [
                        'id_user' => $session->get('id_usuario'),
                        'script' => 'Agregar.php/guardarFacturaPDF'
                    ];

                    $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

                }
            }
        }


        return $response;
    }

    public function periodoIndividual($encabezado, $partida, $proyecto, $editarPe, $periodo_inicio, $periodo_fin)
    {
        $session = \Config\Services::session();
        $this->globals = new Mglobal();

        $dataConfig = [
            "tabla" => "periodo_factura",
            "editar" => true,
            "idEditar" => ['id_periodo_factura' => $editarPe]
        ];

        $dataInsert = [
            'encabezado' => $encabezado,  // ahora sí existe
            'id_partida' => $partida,
            'id_proyecto' => $proyecto,
            'periodo_inicio' => date('Y-m-d', strtotime($periodo_inicio)),
            'periodo_fin' => date('Y-m-d', strtotime($periodo_fin))
        ];

        $dataBitacora = [
            'id_user' => $session->get('id_usuario'),
            'script' => 'Agregar.php/guardarPeriodo'
        ];

        $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
        // die( var_dump(  $response ) );
        return $response;
    }
    public function procesarPediodoEditar(array $periodo, $id_registro_pt = null)
    {
        $response = new \stdClass();
        $this->globals = new Mglobal();

        foreach ($periodo as $pe) {
            // DETECTAR TIPO DE ESTRUCTURA
            $esAnidado = (is_array($pe['editarPe']));
            $esNormal = (is_string($pe['editarPe']));


            if ($esAnidado) {
                // Estructura anidada: múltiples registros
                foreach ($periodo as $index => $p) {

                    if (isset($p['editarPe'][$index])) {
                        $response = $this->periodoIndividual(
                            $p['encabezado'][$index],
                            $p['partida'][$index],
                            $p['proyecto'][$index],
                            // $p['importe'][$index],
                            $p['editarPe'][$index],
                            $p['periodo_inicio'][$index],
                            $p['periodo_fin'][$index]
                        );
                    }
                }

            } else if ($esNormal) {
                // Estructura normal: un solo registro
                $response = $this->periodoIndividual(
                    $pe['encabezado'],
                    // $p['importe'],
                    $pe['partida'],
                    $pe['proyecto'],
                    $pe['editarPe'],
                    $pe['periodo_inicio'],
                    $pe['periodo_fin']
                );
            }
        }

        ;
        return $response;
    }
    public function procesarPediodo(array $periodo, $id_registro_pt = null)
    {
        $session = \Config\Services::session();
        // Asegúrate de que $this->globals está inicializado
        // $this->globals = new Mglobal(); 
        $responses = [];

        // Paso 1: Obtener el array real de datos
        // La estructura mostrada siempre contiene los datos en el índice 0 del array principal.
        if (empty($periodo) || !isset($periodo[0])) {
            return $responses; // No hay datos que procesar
        }

        $datos_por_campo = $periodo[0];

        // Paso 2: Determinar cuántos registros hay que procesar
        // El número de registros es la longitud de cualquiera de los arrays de campos, por ejemplo, 'partida'.
        if (!isset($datos_por_campo['partida']) || !is_array($datos_por_campo['partida'])) {
            // Manejar error o estructura inesperada
            return $responses;
        }

        // Contamos el número de registros (filas de datos) a partir de la longitud del array 'partida'
        $num_registros = count($datos_por_campo['partida']);

        // Paso 3: Iterar sobre cada registro (0, 1, 2, ...)
        for ($i = 0; $i < $num_registros; $i++) {

            // Llamar a la función de procesamiento individual con los datos para el índice $i
            $this->procesarRegistroIndividual(
                $id_registro_pt,
                $datos_por_campo['encabezado'][$i] ?? null, // Usar ?? null por si acaso, aunque no debería ser necesario
                $datos_por_campo['partida'][$i] ?? null,
                $datos_por_campo['proyecto'][$i] ?? null,
                $datos_por_campo['periodo_inicio'][$i] ?? null,
                $datos_por_campo['periodo_fin'][$i] ?? null,
                $responses
            );
        }

        return $responses;
    }

    private function procesarRegistroIndividual($id_registro_pt, $encabezado, $partida, $proyecto, $periodo_inicio, $periodo_fin, &$responses)
    {
        $session = \Config\Services::session();

        if (!empty(trim($encabezado)) && !empty(trim($encabezado))) {

            // Limpiar importe
            //  $importe_limpio = floatval(str_replace(['$', ',', ' '], '', $importe));

            $dataInsert = [
                'id_registro_pt' => (int) $id_registro_pt,
                'encabezado' => trim($encabezado),
                // 'importe' => $importe_limpio,
                'id_partida' => (int) $partida,
                'id_proyecto' => (int) $proyecto,
                'periodo_inicio' => $periodo_inicio,
                'periodo_fin' => $periodo_fin,
                'fec_reg' => date('Y-m-d H:i:s'),
            ];
            //die( var_dump( $dataInsert ) );
            $dataConfig = [
                "tabla" => "periodo_factura",
                "editar" => false
            ];

            $dataBitacora = [
                'id_user' => $session->get('id_usuario'),
                'script' => 'Agregar.php/guardarPeriodo'
            ];

            $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

            $responses[] = $response;
        }
        // Si no cumple la condición, simplemente no hace nada (no inserta)
    }

    public function procesarPDFeditar(array $archivos, array $idPdf, $id_registro_pt = null)
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $this->globals = new Mglobal();

        // Extraer los IDs de edición (vienen en el primer elemento del array)




        $archivosProcesados = 0;

        foreach ($archivos as $index => $archivo) {
            // Validar archivo

            if (!$archivo->isValid() || $archivo->getError() == 4 || $archivo->getSize() == 0) {
                continue;
            }


            $timestamp = date('Ymd_His');
            $extension = $archivo->getClientExtension();
            $file = '03_CFDI_' . $index . '_' . $timestamp . '.' . $extension;

            // Ruta absoluta
            $ruta_destino = FCPATH . 'assets/pdf/';

            // Crear directorio si no existe
            if (!is_dir($ruta_destino)) {
                mkdir($ruta_destino, 0755, true);
            }

            $archivo->move($ruta_destino, $file);

            // Rutas públicas
            $ruta_absoluta = base_url('assets/pdf/' . $file);
            $ruta_relativa = 'assets/pdf/' . $file;

            foreach ($idPdf as $key) {
                $dataConfig = [
                    "tabla" => "factura_pdf",
                    "editar" => true,
                    "idEditar" => ['id_factura_pdf' => $key]
                ];
                $dataInsert = [
                    'ruta_relativa' => $ruta_relativa,
                    'ruta_absoluta' => $ruta_absoluta,
                    'usu_act' => $session->get('id_usuario')
                ];
                $dataBitacora = [
                    'id_user' => $session->get('id_usuario'),
                    'script' => 'Agregar.php/editarFacturaPDF'
                ];

                $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

            }
            // Add local file path to response
            if (!isset($response->savedFiles)) {
                $response->savedFiles = [];
            }
            $response->savedFiles[] = $ruta_destino . $file;

            $archivosProcesados++;
        }

        // Configurar respuesta
        if ($archivosProcesados > 0) {
            $response->success = true;
            $response->message = "Se procesaron {$archivosProcesados} archivos correctamente";
        } else {
            $response->success = false;
            $response->message = "No se procesaron archivos válidos";
        }

        return $response;
    }
    public function procesarPDF(array $archivos, $id_registro_pt = null)
    {
        $session = \Config\Services::session();
        $data = array();
        $response = new \stdClass();
        $this->globals = new Mglobal();
        $i = 1;


        foreach ($archivos as $archivo) {
            if (!$archivo || !$archivo->isValid()) {
                $errorMsg = $archivo->getErrorString() ?: 'Archivo inválido';
                $response->errores[] = "Archivo {$i}: {$errorMsg}";
                $i++;
                continue;
            }

            $random = bin2hex(random_bytes(4)); // 8 caracteres hexadecimales
            $timestamp = date('Ymd_His_') . $random;
            $extension = $archivo->getClientExtension();
            $file = '03_CFDI_' . $i . '_' . $timestamp . '.' . $extension;

            // Ruta absoluta
            $ruta_destino = FCPATH . 'assets/pdf/';
            $archivo->move($ruta_destino, $file);

            // Rutas públicas
            $ruta_absoluta = base_url('assets/pdf/' . $file);
            $ruta_relativa = 'assets/pdf/' . $file;
            $dataConfig = [
                "tabla" => "factura_pdf",
                "editar" => false
            ];
            $dataInsert = [
                'id_registro_pt' => (int) $id_registro_pt,
                'ruta_relativa' => $ruta_relativa,
                'ruta_absoluta' => $ruta_absoluta,
                'fec_reg' => date('Y-m-d H:i:s'),
                'usu_reg' => $session->get('id_usuario')

            ];
            $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarFacturaPDF'];
            $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
            
            // Add local file path to response
            if (!isset($response->savedFiles)) {
                $response->savedFiles = [];
            }
            $response->savedFiles[] = $ruta_destino . $file;

            $i++;
        }

        return $response;
    }
    public function procesarXMLeditar(array $archivos, array $idXml, $id_registro_pt = null)
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $this->globals = new Mglobal();

        $responses = []; // Inicializar array de respuestas

        foreach ($archivos as $key => $p) {
            $archivo = $p;

            // Verificar que sea un XML válido
            if (
                $archivo->getError() === 0 &&
                in_array($archivo->getClientMimeType(), ['text/xml', 'application/xml'])
            ) {

                $contenido = file_get_contents($archivo->getTempName());
                libxml_use_internal_errors(true);
                $xml = simplexml_load_string($contenido);

                if ($xml === false) {
                    $responses[] = ['error' => 'Archivo XML inválido'];
                    continue;
                }

                // Procesar el XML
                $namespaces = $xml->getNamespaces(true);
                $cfdi = $xml->children($namespaces['cfdi']);

                $attrs = $xml->attributes();
                $version = (string) $attrs['Version'];
                $fecha = (string) $attrs['Fecha'];
                $total = (string) $attrs['Total'];
                $moneda = (string) $attrs['Moneda'];
                $Serie = (string) $attrs['Serie'];
                $Folio = (string) $attrs['Folio'];
                $FormaPago = (string) $attrs['FormaPago'];
                $CondicionesDePago = (string) $attrs['CondicionesDePago'];
                $SubTotal = (float) $attrs['SubTotal'];
                $Descuento = isset($attrs['Descuento']) ? (float) $attrs['Descuento'] : 0;
                $TipoCambio = isset($attrs['TipoCambio']) ? (float) $attrs['TipoCambio'] : 1;

                $Certificado = (string) $attrs['Certificado'];
                $NoCertificado = (string) $attrs['NoCertificado'];

                // ✅ Emisor
                $emisor = $cfdi->Emisor->attributes();
                $rfcEmisor = (string) $emisor['Rfc'];
                $nombreEmisor = (string) $emisor['Nombre'];

                // ✅ Receptor
                $receptor = $cfdi->Receptor->attributes();
                $rfcReceptor = (string) $receptor['Rfc'];
                $nombreReceptor = (string) $receptor['Nombre'];

                // ✅ UUID - CÓDIGO CORREGIDO
                $uuid = '';
                $NoCertificadoSAT = '';

                // Verificar si existe el complemento
                if (isset($cfdi->Complemento)) {
                    // Obtener el namespace correcto para el timbre fiscal
                    $tfdNamespace = isset($namespaces['tfd']) ? $namespaces['tfd'] : 'http://www.sat.gob.mx/TimbreFiscalDigital';

                    $complemento = $cfdi->Complemento->children($tfdNamespace);

                    // Verificar si existe el TimbreFiscalDigital
                    if (isset($complemento->TimbreFiscalDigital)) {
                        $tfdAttributes = $complemento->TimbreFiscalDigital->attributes();
                        $uuid = (string) $tfdAttributes['UUID'];
                        $NoCertificadoSAT = (string) $tfdAttributes['NoCertificadoSAT'];
                    }
                }

                if (isset($idXml[$key])) {
                    $idFacturaEditar = $idXml[$key]; // <-- ESTE es el ID correcto para este archivo

                    $dataConfig = [
                        "tabla" => "factura",
                        "editar" => true,
                        "idEditar" => ['id_factura' => $idFacturaEditar]
                    ];

                    $dataInsert = [
                        'version' => $version,
                        'fecha' => date('Y-m-d H:i:s', strtotime($fecha)),
                        'total' => $total,
                        'moneda' => $moneda,
                        'folio' => $Folio,
                        'no_certificado' => $NoCertificadoSAT ?: $NoCertificado,
                        'emisor_rfc' => $rfcEmisor,
                        'emisor_nombre' => $nombreEmisor,
                        'receptor_rfc' => $rfcReceptor,
                        'receptor_nombre' => $nombreReceptor,
                        'uuid' => $uuid,
                        'fec_reg' => date('Y-m-d H:i:s'),
                        'usu_reg' => $session->get('id_usuario')
                    ];

                    $dataBitacora = [
                        'id_user' => $session->get('id_usuario'),
                        'script' => 'Agregar.php/editarFacturaFIC'
                    ];

                    $result = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

                    $responses[] = $result;

                } else {
                    $responses[] = ['error' => 'No existe ID para este archivo'];
                }

            } else {
                $responses[] = ['error' => 'Archivo no válido o error en la subida'];
            }


        }

        return $responses;
    }



    public function procesarXML(array $archivos, $id_registro_pt = null)
    {
        $session = \Config\Services::session();
        $data = array();
        $this->globals = new Mglobal();

        foreach ($archivos as $archivo) {
            if (!$archivo->isValid()) {
                continue;
            }

            $tipo = $archivo->getMimeType();

            if (in_array($tipo, ['text/xml', 'application/xml'])) {
                $contenido = file_get_contents($archivo->getTempName());

                libxml_use_internal_errors(true);
                $xml = simplexml_load_string($contenido);

                if ($xml === false) {
                    return false;
                }

                $namespaces = $xml->getNamespaces(true);
                $cfdi = $xml->children($namespaces['cfdi']);

                $attrs = $xml->attributes();
                $version = (string) $attrs['Version'];
                $fecha = (string) $attrs['Fecha'];
                $total = (string) $attrs['Total'];
                $moneda = (string) $attrs['Moneda'];
                $Serie = (string) $attrs['Serie'];
                $Folio = (string) $attrs['Folio'];
                $FormaPago = (string) $attrs['FormaPago'];
                $CondicionesDePago = (string) $attrs['CondicionesDePago'];
                $SubTotal = (float) $attrs['SubTotal'];
                $Descuento = isset($attrs['Descuento']) ? (float) $attrs['Descuento'] : 0;
                $TipoCambio = isset($attrs['TipoCambio']) ? (float) $attrs['TipoCambio'] : 1;

                $Certificado = (string) $attrs['Certificado'];
                $NoCertificado = (string) $attrs['NoCertificado'];

                // ✅ Emisor
                $emisor = $cfdi->Emisor->attributes();
                $rfcEmisor = (string) $emisor['Rfc'];
                $nombreEmisor = (string) $emisor['Nombre'];

                // ✅ Receptor
                $receptor = $cfdi->Receptor->attributes();
                $rfcReceptor = (string) $receptor['Rfc'];
                $nombreReceptor = (string) $receptor['Nombre'];

                // ✅ UUID - CÓDIGO CORREGIDO
                $uuid = '';
                $NoCertificado = '';

                // Verificar si existe el complemento
                if (isset($cfdi->Complemento)) {
                    // Obtener el namespace correcto para el timbre fiscal
                    $tfdNamespace = isset($namespaces['tfd']) ? $namespaces['tfd'] : 'http://www.sat.gob.mx/TimbreFiscalDigital';

                    $complemento = $cfdi->Complemento->children($tfdNamespace);

                    // Verificar si existe el TimbreFiscalDigital
                    if (isset($complemento->TimbreFiscalDigital)) {
                        $tfdAttributes = $complemento->TimbreFiscalDigital->attributes();
                        $uuid = (string) $tfdAttributes['UUID'];
                        $NoCertificado = (string) $tfdAttributes['NoCertificadoSAT'];
                    }
                }



                $dataConfig = [
                    "tabla" => "factura",
                    "editar" => false
                ];
                $dataInsert = [
                    'id_registro_pt' => (int) $id_registro_pt,
                    'version' => $version,
                    'fecha' => date('Y-m-d H:i:s', strtotime($fecha)),
                    'total' => $total,
                    'moneda' => $moneda,
                    'folio' => $Folio,
                    'no_certificado' => $NoCertificado, // Usar el del timbre, no del comprobante
                    'emisor_rfc' => $rfcEmisor,
                    'emisor_nombre' => $nombreEmisor,
                    'receptor_rfc' => $rfcReceptor,
                    'receptor_nombre' => $nombreReceptor,
                    'uuid' => $uuid,
                    'fec_reg' => date('Y-m-d H:i:s'),
                    'usu_reg' => $session->get('id_usuario')
                ];

                $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarFactura'];
                $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

            }
        }
        return $response;
    }


    private function cambiarStatus($id = null)
    {
        $session = \Config\Services::session();
        $this->globals = new Mglobal();
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaTurno'];
        $dataConfig = [
            "tabla" => "reserva_go",
            "editar" => true,
            "idEditar" => ['id_reserva_go' => (int) $id]
        ];
        $response = $this->globals->saveTabla(['id_estatus' => 4], $dataConfig, $dataBitacora);


    }
    public function deleteDenuncia()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal();
        $id_denuncia = $this->request->getPost('id_denuncia');
        $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Agregar.php/eliminarDenuncia'];
        $dataConfig = [
            "tabla" => "denuncia",
            "editar" => true,
            "idEditar" => ['id_denuncia' => $id_denuncia]
        ];
        $response = $globals->saveTabla(['visible' => 0], $dataConfig, $dataBitacora);
        return $this->respond($response);
    }
    public function deleteInventario()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal();
        $id_inventario = $this->request->getPost('id_inventario');
        $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Agregar.php/eliminarIncentario'];
        $dataConfig = [
            "tabla" => "inventario",
            "editar" => true,
            "idEditar" => ['id_inventario' => $id_inventario]
        ];
        $response = $globals->saveTabla(['visible' => 0], $dataConfig, $dataBitacora);
        return $this->respond($response);
    }
    public function getDenuncia()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal();
        $id_denuncia = $this->request->getPost('id_denuncia');
        $response = $globals->getTabla(['tabla' => 'denuncia', 'where' => ['id_denuncia' => $id_denuncia]]);
        return $this->respond($response->data[0]);
    }
    public function Denuncia()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal();
        $data['usuario'] = $globals->getTabla([
            'tabla' => 'vw_usuario',
            'where' => ['visible' => 1]
        ])->data;
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vDenuncia';
        $this->_renderView($data);

    }
    public function aboutSusi()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vSusi';
        $this->_renderView($data);
    }
    public function Inventario()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $this->globals = new Mglobal();
        $dataDB = array('tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->id_usuario]);
        $response = $this->globals->getTabla($dataDB)->data;
        if (!empty($response)) {
            $no_empleado = $response[0]->no_empleado;
            $dataDB = array('tabla' => 'inventario', 'where' => ['visible' => 1, 'no_empleado' => $no_empleado]);
            $inventario = $this->globals->getTabla($dataDB)->data;
            $data['inventario'] = $inventario;

        }

        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vInventario';
        $this->_renderView($data);

    }
    public function vTiketDisenio()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vTiketDisenio';
        $this->_renderView($data);
    }
    public function guardaEditarFIC()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error|Error al guardar PT";
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        $archivos = $this->request->getFiles();
        $response->idReserva = "";

        if ($data['no_consecutivo'] == '') {
            $response->error = true;
            $response->respuesta = "Es requerido el No. Concecutivo";
            return $this->respond($response);
        }
        if (($data['direccion_responsable']) == 0) {
            $response->error = true;
            $response->respuesta = "Es requerido el Dirección Responsable";
            return $this->respond($response);
        }
        if (isset($data['documentacion_comprobatoria']) && empty($data['documentacion_comprobatoria'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el documentacion_comprobatorian";
            return $this->respond($response);
        }


        if (isset($data['concepto_gasto']) && empty($data['concepto_gasto'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el concepto gasto";
            return $this->respond($response);
        }

        if (isset($data['no_reserva']) && empty($data['no_reserva'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el tipo de consumo";
            return $this->respond($response);
        }
        if (isset($data['id_proveedor_banco']) && empty($data['id_proveedor_banco'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el banco del proveedor";
            return $this->respond($response);
        }
        if (isset($data['fecha_tramite']) && empty($data['fecha_tramite'])) {
            $data['fecha_tramite'] = date('Y-m-d');
        }

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/editarFic'];


        $insertReserva = [
            'id_proveedor' => (int) $data['id_proveedor'],
            'id_estatus' => 3,
            'id_proveedor_banco' => (int) $data['id_proveedor_banco'],
            'folio' => 'PT - ' . date('YmdHis') . substr((string) microtime(), 1, 4),
            'no_reserva' => $data['no_reserva'],
            'no_convenio' => $data['no_convenio'],
            'total_importe' => $data['total_importe'],
            'observaciones' => 'PAGOS FIC',
            'usu_act' => $session->get('id_usuario')
        ];

        $dataConfig = [
            "tabla" => "reserva",
            "editar" => true,
            "idEditar" => ['id_reserva' => $data['id_reserva']]
        ];

        $response = $this->globals->saveTabla($insertReserva, $dataConfig, $dataBitacora);

        if (!$response->error) {
            $id_reserva = $response->idRegistro;
            $insertPresupuesto = [
                'id_reserva' => $id_reserva,
                'id_proyecto' => 34,
                'id_partida' => $data['no_reserva'] == 4327279 ? 10 : 94,
                'importe' => $data['total_importe'],
                'usu_act' => $session->get('id_usuario')

            ];

            $dataConfig = [
                "tabla" => "presupuesto",
                "editar" => true,
                'idEditar' => ['id_presupuesto' => $id_reserva]

            ];

            $response = $this->globals->saveTabla($insertPresupuesto, $dataConfig, $dataBitacora);

        }
        if (!$response->error) {
            $id_presupuesto = $response->idRegistro;
            $insertRegistro = [
                'id_reserva' => $id_reserva,
                'id_proveedor' => $data['id_proveedor'],
                'id_direccion_responsable' => 99,
                'id_subsecretario' => 2,
                'no_consecutivo' => $data['no_consecutivo'],
                'tipo_pt' => (int) $data['tipo_pt'],
                'fecha_tramite' => $data['fecha_tramite'],
                'id_reponsable_solicitud' => 99,
                'director_general' => (int) $data['director_generar'],
                'secretario' => 18,
                'fic' => 1,
                'fecha_gasto_inicio' => $data['fecha_gasto_inicio'],
                'fecha_gasto_fin' => $data['fecha_gasto_fin'],
                'formato_establecido' => ($data['formato_establecido'] == 'SI') ? 1 : 2,
                'documentacion_comprobatoria' => (int) $data['documentacion_comprobatoria'],
                'evidencia_entrega' => (int) $data['evidencia_entrega'],
                'otros' => $data['otros'],
                'comision' => $data['comision'],
                'clausula_contrato' => $data['clausula_contrato'],
                'contrato_convenio' => 2,
                'concepto_pago' => $data['concepto_pago'],
                'usu_act' => $session->get('id_usuario')
            ];

            $dataConfig = [
                "tabla" => "registro_pt",
                "editar" => true,
                "idEditar" => ['id_registro_pt' => $data['id_registro_pt']]
            ];

            $response = $this->globals->saveTabla($insertRegistro, $dataConfig, $dataBitacora);

        }
        if (!$response->error) {

            $id_registro_pt = $response->idRegistro;
            $archivosXml = [];
            $archivosPdf = [];
            $response->idReserva = $id_registro_pt;

            // foreach sobre los archivos recibidos
            foreach ($archivos as $key => $fileEntry) {
                // normalizar clave a minúsculas por si acaso
                $k = strtolower($key);

                // Si es un array de UploadedFile (varios archivos), iteramos
                if (is_array($fileEntry)) {
                    foreach ($fileEntry as $singleFile) {
                        if (!$singleFile)
                            continue;
                        // comprobar que sea un objeto con getSize (UploadedFileInterface)
                        $size = method_exists($singleFile, 'getSize') ? $singleFile->getSize() : null;

                        if (strpos($k, 'factura_xml_fic') === 0 && $size > 0) {
                            $archivosXml[] = $singleFile;
                        } elseif (strpos($k, 'factura_pdf_fic') === 0 && $size > 0) {
                            $archivosPdf[] = $singleFile;
                        }
                    }
                } else {
                    // caso archivo único
                    $singleFile = $fileEntry;
                    if (!$singleFile)
                        continue;
                    $size = method_exists($singleFile, 'getSize') ? $singleFile->getSize() : null;

                    if (strpos($k, 'factura_xml_fic') === 0 && $size > 0) {
                        $archivosXml[] = $singleFile;
                    } elseif (strpos($k, 'factura_pdf_fic') === 0 && $size > 0) {
                        $archivosPdf[] = $singleFile;
                    }
                }
            }


            if (!empty($archivosXml)) {
                $datosXML = $this->procesarXMLeditar($archivosXml, $id_registro_pt);
            } else {
                $response->errorXML = true;
                $response->respuestaXML = "No se encontraron archivos XML para procesar.";
            }

            if (!empty($archivosPdf)) {
                $datosPDF = $this->procesarPDFeditar($archivosPdf, $id_registro_pt);

            } else {
                $response->errorPDF = true;
                $response->respuestaPDF = "No se encontraron archivos PDF para procesar.";
            }

        }



        return $this->respond($response);
    }
    public function guardaFIC()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error|Error al guardar PT";
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        $archivos = $this->request->getFiles();
        $response->idReserva = "";



        if ($data['no_consecutivo'] == '') {
            $response->error = true;
            $response->respuesta = "Es requerido el No. Concecutivo";
            return $this->respond($response);
        }
        if (($data['direccion_responsable']) == 0) {
            $response->error = true;
            $response->respuesta = "Es requerido el Dirección Responsable";
            return $this->respond($response);
        }
        if (isset($data['documentacion_comprobatoria']) && empty($data['documentacion_comprobatoria'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el documentacion_comprobatorian";
            return $this->respond($response);
        }
        if (isset($data['poliza']) && empty($data['poliza'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el poliza";
            return $this->respond($response);
        }
        if (isset($data['formato_conformidad']) && empty($data['formato_conformidad'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el formato_conformidad";
            return $this->respond($response);
        }
        if (isset($data['concepto_gasto']) && empty($data['concepto_gasto'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el concepto gasto";
            return $this->respond($response);
        }

        if (isset($data['no_reserva']) && empty($data['no_reserva'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el tipo de consumo";
            return $this->respond($response);
        }
        if (isset($data['id_proveedor_banco']) && empty($data['id_proveedor_banco'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el banco del proveedor";
            return $this->respond($response);
        }
        if (isset($data['fecha_tramite']) && empty($data['fecha_tramite'])) {
            $data['fecha_tramite'] = date('Y-m-d');
        }
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaTurno'];

        // Pre-procesar archivos UNA SOLA VEZ fuera del loop
        $archivosXml = [];
        $archivosPdf = [];
        $finalAttachments = [];

        foreach ($archivos as $key => $fileArray) {
            if (strpos($key, 'factura_xml_fic') === 0) {
                $archivosXml = array_merge($archivosXml, is_array($fileArray) ? $fileArray : [$fileArray]);
            } elseif (strpos($key, 'factura_pdf_fic') === 0) {
                $archivosPdf = array_merge($archivosPdf, is_array($fileArray) ? $fileArray : [$fileArray]);
            }
        }



        $iteracionResponse = new \stdClass();
        $iteracionResponse->error = false;
        $iteracionResponse->idRegistro = null;

        // 1. Insertar Reserva
        foreach ($data['no_reserva'] as $k => $v) {
            $noReserva = (($v == 'hoteles')) ? 4327278 : 4327277;
        }

        $insertReserva = [
            'id_proveedor' => (int) $data['id_proveedor'],
            'id_estatus' => 3,
            'id_proveedor_banco' => (int) $data['id_proveedor_banco'],
            'folio' => 'PT - ' . date('YmdHis') . substr((string) microtime(), 1, 4),
            'no_reserva' => $noReserva,
            'no_convenio' => $data['no_convenio'],
            'total_importe' => $data['total_importe'],
            'observaciones' => 'PAGOS FIC',
            'fec_reg' => date('Y-m-d H:i:s'),
            'usu_reg' => $session->get('id_usuario')
        ];

        $dataConfig = ["tabla" => "reserva", "editar" => false];
        $reservaResult = $this->globals->saveTabla($insertReserva, $dataConfig, $dataBitacora);

        if ($reservaResult->error) {
            $iteracionResponse->error = true;
            $iteracionResponse->respuesta = "Error al guardar reserva: " . $reservaResult->respuesta;

        }

        $id_reserva = $reservaResult->idRegistro;

        // 3. Insertar Registro PT
        $insertRegistro = [
            'id_reserva' => $id_reserva,
            'id_proveedor' => $data['id_proveedor'],
            'id_direccion_responsable' => 99,
            'id_subsecretario' => 2,
            'no_consecutivo' => $data['no_consecutivo'],
            'tipo_pt' => (int) $data['tipo_pt'],
            'fecha_tramite' => $data['fecha_tramite'],
            'id_reponsable_solicitud' => 99,
            'director_general' => (int) $data['director_generar'],
            'secretario' => 18,
            'fic' => 1,
            'fecha_gasto_inicio' => $data['fecha_gasto_inicio'],
            'fecha_gasto_fin' => $data['fecha_gasto_fin'],
            'formato_establecido' => ($data['formato_establecido'] == 'SI') ? 1 : 2,
            'documentacion_comprobatoria' => (int) $data['documentacion_comprobatoria'],
            'evidencia_entrega' => (int) $data['evidencia_entrega'],
            'otros' => $data['otros'],
            'comision' => $data['comision'],
            'clausula_contrato' => $data['clausula_contrato'],
            'contrato_convenio' => 2,
            'concepto_pago' => $data['concepto_pago'],
            'fec_reg' => date('Y-m-d H:i:s'),
            'usu_reg' => $session->get('id_usuario')
        ];

        $dataConfig = ["tabla" => "registro_pt", "editar" => false];
        $registroResult = $this->globals->saveTabla($insertRegistro, $dataConfig, $dataBitacora);

        if ($registroResult->error) {
            $iteracionResponse->error = true;
            $iteracionResponse->respuesta = "Error al guardar registro PT: " . $registroResult->respuesta;

        }

        $id_registro_pt = $registroResult->idRegistro;
        foreach ($data['no_reserva'] as $k => $v) {

            // 2. Insertar Presupuesto
            $insertPresupuesto = [
                'id_reserva' => $id_reserva,
                'id_proyecto' => 34,
                'id_partida' => (($v == 'restaurantes_geg')) ? 10 : 94,
                'importe' => $data['importe'][$k],
                'fec_reg' => date('Y-m-d H:i:s'),
                'usu_reg' => $session->get('id_usuario')
            ];

            $dataConfig = ["tabla" => "presupuesto", "editar" => false];
            $presupuestoResult = $this->globals->saveTabla($insertPresupuesto, $dataConfig, $dataBitacora);

            if ($presupuestoResult->error) {
                $iteracionResponse->error = true;
                $iteracionResponse->respuesta = "Error al guardar presupuesto: " . $presupuestoResult->respuesta;

            }
            $id_presupuesto = $presupuestoResult->idRegistro;

        }
        // 4. Procesar archivos SOLO para el primer registro exitoso

        $this->cambiarStatusPT($id_reserva);

        // Procesar XML
        if (!empty($archivosXml)) {
            $datosXML = $this->procesarXML($archivosXml, $id_registro_pt);
            if ($datosXML && $datosXML->error) {
                $iteracionResponse->errorXML = true;
                $iteracionResponse->respuestaXML = "Error en XML: " . ($datosXML->respuesta ?? 'Desconocido');
            } else {
                 // Guardar XML físicos para adjuntar al correo
                 foreach ($archivosXml as $xml) {
                     if (!$xml->isValid()) continue;
                     
                     $contenido = file_get_contents($xml->getTempName());
                     $xmlName = 'Factura_fic_xml_' . $id_registro_pt . '_' . date('Ymd_His') . '_' . uniqid() . '.xml';
                     $ruta_xml_destino = FCPATH . 'assets/pdf/' . $xmlName;
                     
                     if (file_put_contents($ruta_xml_destino, $contenido) !== false) {
                         $finalAttachments[] = $ruta_xml_destino;
                     }
                 }
            }
        }

        // Procesar PDF
        if (!empty($archivosPdf)) {
            foreach ($archivosPdf as $archivo) {
                if (!$archivo || !$archivo->isValid()) {
                    continue;
                }

                $microtime = microtime(true);
                $timestamp = date('Ymd_His', $microtime) . sprintf('%03d', ($microtime - floor($microtime)) * 1000);
                $extension = $archivo->getClientExtension();
                $file = '03_CFDI_' . $timestamp . '.' . $extension;

                $ruta_destino = FCPATH . 'assets/pdf/';
                if ($archivo->move($ruta_destino, $file)) {
                    $ruta_absoluta = base_url('assets/pdf/' . $file);
                    $ruta_relativa = 'assets/pdf/' . $file;
                    $finalAttachments[] = $ruta_destino . $file;

                    $dataConfig = ["tabla" => "factura_pdf", "editar" => false];
                    $dataInsert = [
                        'id_registro_pt' => $id_registro_pt,
                        'ruta_relativa' => $ruta_relativa,
                        'ruta_absoluta' => $ruta_absoluta,
                        'fec_reg' => date('Y-m-d H:i:s'),
                        'usu_reg' => $session->get('id_usuario')
                    ];

                    $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
                }
            }
        }

        $response->idReserva = $id_registro_pt;



        $iteracionResponse->idRegistro = $id_registro_pt;



        // Determinar respuesta final

        $response->error = false;
        $response->respuesta = "registros guardados correctamente";

        // Enviar correos si hay adjuntos
         if (!empty($finalAttachments)) {
            $mailer = new \App\Libraries\Mailer();
            
            $mensajeHTML = '
            <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    <div style="background-color: #004080; padding: 20px; text-align: center;">
                        <h2 style="color: #ffffff; margin: 0;">Facturas Generadas</h2>
                    </div>
                    <div style="padding: 30px; color: #333;">
                        <p style="font-size: 16px;">Estimado usuario,</p>
                        <p style="font-size: 16px;">Se adjuntan a este correo los archivos <strong>XML</strong> y <strong>PDF</strong> correspondientes a las facturas de <strong>Gastos FIC (PT)</strong> generadas en el sistema SUSI.</p>
                        <p style="font-size: 14px; color: #666;">Por favor, conserve estos comprobantes para su control administrativo.</p>
                        
                        <div style="margin-top: 25px; padding: 15px; background-color: #e3f2fd; border-left: 5px solid #2196f3; border-radius: 4px;">
                            <p style="margin: 0; font-size: 14px; color: #0d47a1;"><strong>Nota:</strong> Este es un mensaje automático, favor de no responder a esta dirección.</p>
                        </div>
                    </div>
                    <div style="background-color: #e0e0e0; text-align: center; padding: 15px; font-size: 12px; color: #666;">
                        © ' . date('Y') . ' Sistema de Atención SUSI. Todos los derechos reservados.
                    </div>
                </div>
            </div>';

            $mailer->send(
                $mensajeHTML, 
                $session->get('id_usuario'), 
                ['dasedetur@guanajuato.gob.mx'], 
                2, 
                false, 
                $finalAttachments, 
                "Facturas FIC Generadas - SUSI"
            );
        } 

        //return $this->respond($response);
    }
    public function guardaGO()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error|Error al guardar PT";
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        $archivos_post = $this->request->getFiles(); 

       // die( var_dump($data) );
     
        $archivos_por_tabla = [];
        if (isset($archivos_post['archivos'])) {

            foreach ($archivos_post['archivos'] as $key => $file_data) {
                // $key es 'pdf_0', 'xml_table_1_row_...', etc.

                $parts = explode('_', $key);
                $type = $parts[0]; // 'pdf' o 'xml'

                // El resto es el rowIndex que usó el JS
                $rowIndex = substr($key, strlen($type) + 1); // '0', '1', 'table_1_row_...'

                // Determinamos a qué tabla '$i' pertenece
                $tabla_id = null;
                if (strpos($rowIndex, 'table_') === 0) {
                    // Es una fila nueva, ej: 'table_1_row_...'
                    $parts_row = explode('_', $rowIndex);
                    $tabla_id = $parts_row[1]; // Extrae el '1'
                } elseif (preg_match('/^\d+$/', $rowIndex)) {
                    // Es una fila inicial, ej: '0' o '1' (solo números)
                    $tabla_id = $rowIndex;
                } else {
                    // Es un rowIndex complejo de fila inicial, ej: 'initial_0_...'
                    // Intentamos extraer el ID de la tabla
                    if (preg_match('/^initial_(\d+)_/', $rowIndex, $matches)) {
                        $tabla_id = $matches[1]; // Extrae el '0'
                    }
                }

                if ($tabla_id !== null) {
                    // Inicializar array de tabla si no existe
                    if (!isset($archivos_por_tabla[$tabla_id])) {
                        $archivos_por_tabla[$tabla_id] = [];
                    }

                    // CORRECCIÓN IMPORTANTE: Extraemos el array de archivos del interior
                    if ($type == 'pdf' && isset($file_data['pdf'])) {
                        $archivos_por_tabla[$tabla_id][$rowIndex]['pdf'] = $file_data['pdf'];
                    } elseif ($type == 'xml' && isset($file_data['xml'])) {
                        $archivos_por_tabla[$tabla_id][$rowIndex]['xml'] = $file_data['xml'];
                    }
                }
            }
        }

        // 2. Iterar por las tablas usando los datos de $data (getPost)
        $tablas_procesadas = [];
        $finalAttachments = [];
        if (isset($data['encabezado'])) { // Usamos 'encabezado' como guía

            foreach ($data['encabezado'] as $i => $encabezado_texto) {

                $tablas_procesadas[$i] = [
                    'encabezado' => $encabezado_texto,
                    'partida' => $data['partida'][$i] ?? $data['id_partida'][$i] ?? null, // Capturamos la partida (fallback id_partida)
                    'proyecto' => $data['proyecto'][$i] ?? null, // Capturamos el proyecto de la tabla
                    'id_presupuesto' => $data['id_presupuesto'][$i] ?? null, // Capturamos el proyecto de la tabla
                    'filas' => []
                ];

                // Si no hay 'periodo_inicio' (estándar) ni 'nombre_viatico' (viáticos) para esta tabla, saltamos
                if (!isset($data['periodo_inicio_' . $i]) && !isset($data['nombre_viatico_' . $i])) {
                    continue;
                }

                // Obtenemos las claves de los archivos para esta tabla, EN ORDEN.
                $file_keys_para_tabla_i = [];
                if (isset($archivos_por_tabla[$i])) {
                    $file_keys_para_tabla_i = array_keys($archivos_por_tabla[$i]);
                }

                // Obtenemos las claves de los archivos para esta tabla, EN ORDEN.
                $file_keys_para_tabla_i = [];
                if (isset($archivos_por_tabla[$i])) {
                    $file_keys_para_tabla_i = array_keys($archivos_por_tabla[$i]);
                }

                // 1. PROCESAR FILAS ESTÁNDAR (usando periodo_inicio como guía)
                $rows_data = [];
                $format = 'wizard'; // Default

                if (isset($data['periodo_inicio_' . $i])) {
                    $rows_data = $data['periodo_inicio_' . $i];
                } elseif (isset($data['periodo_inicio'][$i])) {
                    $rows_data = [$data['periodo_inicio'][$i]]; // Treat as single row
                    $format = 'borrador';
                }

                if (!empty($rows_data)) {
                    foreach ($rows_data as $j => $val) {
                         // Robustez: Si el valor guía está vacío (fila vacía), ignoramos
                         if (empty($val)) continue;

                         // EXTRAER VALORES SEGÚN FORMATO
                         if ($format == 'borrador') {
                            $propina = $data['propina'][$i] ?? null;
                            $concepto = $data['concepto'][$i] ?? null;
                            $comision = $data['comision'][$i] ?? null;
                            $periodo_inicio = $data['periodo_inicio'][$i] ?? null;
                            $periodo_fin = $data['periodo_fin'][$i] ?? null;
                            $id_presupuesto_go = $data['id_presupuesto_go'][$i] ?? 0;
                            $id_reserva = $data['id_reserva'][$i] ?? 0;
                            
                            $rowIndex_de_archivos = $i; // En borrador, $i es el rowId
                             
                         } else {
                            $propina = $data['propina_' . $i][$j] ?? null;
                            $concepto = $data['concepto_' . $i][$j] ?? null;
                            $comision = $data['comision_' . $i][$j] ?? null;
                            $periodo_inicio = $data['periodo_inicio_' . $i][$j] ?? null;
                            $periodo_fin = $data['periodo_fin_' . $i][$j] ?? null;
                            // Wizard no envía estos IDs por ahora (son registros nuevos desde presupuesto)
                            $id_presupuesto_go = 0; 
                            $id_reserva = 0; // Debería venir de alguna parte si es necesario

                            // Usamos $j para obtener la clave de archivo correspondiente por orden
                            $file_keys_para_tabla_i = [];
                            if (isset($archivos_por_tabla[$i])) {
                                $file_keys_para_tabla_i = array_keys($archivos_por_tabla[$i]);
                            }
                            $rowIndex_de_archivos = $file_keys_para_tabla_i[$j] ?? null;
                         }

                        $archivos_de_la_fila = null;
                        if ($rowIndex_de_archivos !== null) {
                             // Buscar en archivos_por_tabla. 
                             // En formato borrador, $i es 'new_...' o '123'. 
                             // prepararArchivos agrupa por indices numéricos (tablas) o strings (filas)? 
                             // Si isArray format, prepararArchivos lo maneja?
                             // Asumamos que funciona si $archivos_por_tabla está bien construido.
                             if ($format == 'borrador') {
                                 // En borrador, no hay tabla indexada. Todo está en $archivos_por_tabla directamente?
                                 // NO, prepararArchivos itera $_FILES['archivos'][...].
                                 // Si borrador envía archivos[pdf_ROWID], prepararArchivos usará ROWID como key.
                                 // Entonces $archivos_por_tabla tiene keys como 'pdf_ROWID' o 'xml_ROWID'. 
                                 
                                 // REVISAR LOGICA DE ARCHIVOS LUEGO SI FALLA
                                  if (isset($archivos_por_tabla[$rowIndex_de_archivos])) {
                                    $archivos_de_la_fila = $archivos_por_tabla[$rowIndex_de_archivos];
                                  } else {
                                      // Buscar keys con prefix si es necesario...
                                      // Por ahora, intento directo.
                                      // Nota: prepararArchivos usa explode('_', $key)[1] para tabla index...
                                      // Si key es pdf_new_123...
                                  }
                             } else {
                                if (isset($archivos_por_tabla[$i][$rowIndex_de_archivos])) {
                                    $archivos_de_la_fila = $archivos_por_tabla[$i][$rowIndex_de_archivos];
                                }
                             }
                        }
                        
                        // PARCHE PARA ARCHIVOS EN BORRADOR (SI NO SE ENCUENTRAN POR LOGICA ANTERIOR)
                        if ($format == 'borrador' && empty($archivos_de_la_fila)) {
                             // Intentar recuperar directamente del global procesado si existe estructura plana
                             // (Depende de prepararArchivos)
                        }


                        $fila_completa = [
                            // Standar fields
                            'propina' => $propina,
                            'concepto' => $concepto,
                            'comision' => $comision, 
                            'periodo_inicio' => $periodo_inicio,
                            'periodo_fin' => $periodo_fin,
                            'id_presupuesto_go' => $id_presupuesto_go,
                            'id_reserva' => $id_reserva,
                            
                            // Viaticos fields (NULL for standard rows)
                            'contribuyente' => null,
                            'rfc'           => null,
                            'importe'       => null,
                            'is_viaticos'   => false,

                            'archivos' => $archivos_de_la_fila,
                            'js_rowIndex' => $rowIndex_de_archivos
                        ];

                        $tablas_procesadas[$i]['filas'][] = $fila_completa;
                    }
                }

                // 2. PROCESAR FILAS DE VIATICOS (usando nombre_viatico como guía)
                if (isset($data['nombre_viatico_' . $i])) {
                    foreach ($data['nombre_viatico_' . $i] as $j => $val) {
                        // Robustez: Si el nombre está vacío, ignoramos
                        if (empty($val)) continue;

                        // Viaticos NO tienen archivos asociados en este flujo (o se manejan diferente)
                        // Si tuvieran, necesitarían su propia lógica de asignación
                        
                         $fila_completa = [
                            // Standar fields (NULL or irrelevant for viaticos)
                            'propina' => null,
                            'periodo_inicio' => null, // Validación ignorará esto si is_viaticos es true
                            'periodo_fin' => null,
                            
                            // Viaticos fields
                            'contribuyente' => $data['nombre_viatico_' . $i][$j] ?? null,
                            'rfc'           => $data['rfc_viatico_' . $i][$j] ?? null,
                            'importe'       => $data['importe_' . $i][$j] ?? null,
                            'is_viaticos'   => true,

                            'archivos' => null, 
                            'js_rowIndex' => null
                        ];

                        $tablas_procesadas[$i]['filas'][] = $fila_completa;
                    }
                }
            }
        }

        // die();
        if ($data['secretario'] == 0) {
            $response->error = true;
            $response->respuesta = "Es requerido el Secretario o Director";
            return $this->respond($response);
        }
        if ($data['id_subsecretario'] == 0) {
            $response->error = true;
            $response->respuesta = "Es requerido el Subsecretario";
            return $this->respond($response);
        }
        if ($data['no_consecutivo'] == '') {
            $response->error = true;
            $response->respuesta = "Es requerido el No. Concecutivo";
            return $this->respond($response);
        }
        if (($data['direccion_responsable']) == 0) {
            $response->error = true;
            $response->respuesta = "Es requerido el Dirección Responsable";
            return $this->respond($response);
        }
        if (isset($data['documentacion_comprobatoria']) && empty($data['documentacion_comprobatoria'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el documentacion_comprobatorian";
            return $this->respond($response);
        }
        if (isset($data['poliza']) && empty($data['poliza'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el poliza";
            return $this->respond($response);
        }
        if (isset($data['lugar']) && empty($data['lugar'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el lugar";
            return $this->respond($response);
        }
        if (isset($data['formato_conformidad']) && empty($data['formato_conformidad'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el formato_conformidad";
            return $this->respond($response);
        }
        if (isset($data['concepto_gasto']) && empty($data['concepto_gasto'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el concepto gasto";
            return $this->respond($response);
        }

        // VALIDAR FECHAS Y ARCHIVOS EN FILAS
        // VALIDAR FECHAS Y ARCHIVOS EN FILAS
        foreach ($tablas_procesadas as $tabla) {
            foreach ($tabla['filas'] as $fila) {
                // Si es viaticos, validamos campos específicos y saltamos lo demás
                if (!empty($fila['is_viaticos'])) {
                    if (empty($fila['contribuyente']) || empty($fila['importe'])) {
                         $response->error = true;
                         $response->respuesta = "Nombre y Total son requeridos en Desglose de Gastos.";
                         return $this->respond($response);
                    }
                    continue; 
                }

                if (empty($fila['periodo_inicio']) || empty($fila['periodo_fin'])) {
                    $response->error = true;
                    $response->respuesta = "Es necesario capturar las fechas de inicio y fin en todas las filas.";
                    return $this->respond($response);
                }

                if (empty($fila['concepto']) || empty($fila['comision'])) {
                    $response->error = true;
                    $response->respuesta = "Es necesario capturar Concepto y Comisión en todas las filas.";
                    return $this->respond($response);
                }

                // Validación de archivos (PDF y XML)
                //die( var_dump($fila['archivos']) );
                if (empty($fila['archivos']) || empty($fila['archivos']['pdf']) || empty($fila['archivos']['xml'])) {
                    $response->error = true;
                    $response->respuesta = "Es necesario adjuntar al menos un PDF y un XML en todas las filas.";
                    return $this->respond($response);
                }
            }
        }


        if (isset($data['fecha_tramite']) && empty($data['fecha_tramite'])) {
            $data['fecha_tramite'] = date('Y-m-d');
        }
   
        
       $no_consecutivo = $this->registrarFolioGo($data['no_consecutivo'], $data['id_reponsable_solicitud'], $data['direccion_responsable']);

       // Definir estatus: 1 = En captura/Borrador, 2 = Enviado
       $estatus = ($data['es_borrador'] == 1) ? 1 : 2;

        $dataInsert = [
            'id_reserva_go' => $data['id_reserva_go'],
            'id_estatus' => $estatus,
            'id_direccion_responsable' => $data['direccion_responsable'],
            'fecha_tramite' => $data['fecha_tramite'],
            'no_consecutivo' => $no_consecutivo,
            'id_reponsable_solicitud' => (int) $data['id_reponsable_solicitud'],
            'director_general' => 1,
            'secretario' => (int) $data['secretario'],
            'id_subsecretario' => (int) $data['id_subsecretario'],
            'contrato_convenio' => ($data['contrato_convenio'] == 'NO') ? 2 : 1,
            'formato_establecido' => ($data['formato_establecido'] == 'SI') ? 1 : 2,
            'documentacion_comprobatoria' => $data['documentacion_comprobatoria'],
            'poliza' => ($data['poliza'] == 'SI') ? 1 : 2,
            'formato_conformidad' => ($data['formato_conformidad'] == 'SI') ? 1 : 2,
            'documentacion_requerida' => ($data['documentacion_requerida'] == 'SI') ? 1 : 2,
            'evidencia_entrega' => (int) $data['evidencia_entrega'],

            'total_importe' => (isset($data['total_importe']) && !empty($data['total_importe'])) ? $data['total_importe'] : 0, // Asegúrate de que este total sea correcto
            // 'comision' => $data['comision'],
            //'no_reserva' => $data['no_reserva'],
            'lugar' => $data['lugar'],
            'usu_reg' => $session->get('id_usuario'),
        ];
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaTurno'];

        $dataConfig = [
            "tabla" => "registro_go",
            "editar" => false

        ];

        $responsePrincipal = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);


        if (!$responsePrincipal->error) {
            $id_registro_go = $responsePrincipal->idRegistro;
            
            // Lógica de Estatus: Si es borrador no cambiamos estatus o lo dejamos en 1
            $es_borrador = $data['es_borrador'] ?? 0;
            if ($es_borrador != 1) {
                 $this->cambiarStatus($data['id_reserva_go']);
            }
            
            foreach ($tablas_procesadas as $i => $tabla) {


                foreach ($tabla['filas'] as $j => $fila) {

                    // OBTENEMOS EL IDENTIFICADOR ÚNICO DE LA FILA
                    $identificador_fila_unica = $fila['js_rowIndex'];

                    // 1. GUARDAR DATOS DE LA FILA (Importe, Propina, etc.)
                   
                    if (!empty($fila['is_viaticos'])) {
                        // === GUARDAR EN VIATICOS_GO ===
                        $datos_viatico = [
                            'id_registro_go' => $id_registro_go,
                            'id_presupuesto' => $tabla['id_presupuesto'],
                            'nombre'         => $fila['contribuyente'],
                            'rfc'            => $fila['rfc'],
                            'importe'        => (!empty($fila['importe'])) ? str_replace(['$', ','], '', $fila['importe']) : 0,
                            'id_identificador' => $identificador_fila_unica,
                            'usu_reg'        => $session->get('id_usuario'),
                            'fec_reg'        => date('Y-m-d H:i:s')
                        ];

                        $dataConfig = [
                            "tabla" => "viaticos_go",
                            "editar" => false
                        ];

                        $dataBitacora = [
                            'id_user' => $session->get('id_usuario'),
                            'script' => 'Agregar.php/guardarViatico'
                        ];

                        $responseViatico = $this->globals->saveTabla($datos_viatico, $dataConfig, $dataBitacora);

                    } else {
                        // === LOGICA PARA NUEVAS FILAS DE BORRADOR (PRESUPUESTO_GO) ===
                        // Si viene de Borrador y es nueva fila (sin ID, con Reserva)
                        if (empty($fila['id_presupuesto_go']) && !empty($fila['id_reserva'])) {
                             $datos_presupuesto = [
                                'id_reserva' => $fila['id_reserva'],
                                'id_proyecto' => $tabla['id_presupuesto'],
                                'id_partida' => $tabla['partida'],
                                'importe' => (!empty($fila['propina'])) ? str_replace(['$', ','], '', $fila['propina']) : 0,
                                'encabezado' => $tabla['encabezado'],
                                'usu_reg' => $session->get('id_usuario'),
                                'fec_reg' => date('Y-m-d H:i:s'),
                                // dsc_partida? partida? Se asume que ID partida es suficiente o trigger llena dsc
                            ];

                            $dataConfigPres = ["tabla" => "presupuesto_go", "editar" => false];
                            $dataBitacoraPres = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarPresupuestoGo'];
                            
                            $respPres = $this->globals->saveTabla($datos_presupuesto, $dataConfigPres, $dataBitacoraPres);
                            
                            if (!$respPres->error) {
                                $identificador_fila_unica = $respPres->idRegistro;
                            }
                        } elseif (!empty($fila['id_presupuesto_go'])) {
                             // Si ya existe ID (Update en Borrador), usarlo como identificador
                             $identificador_fila_unica = $fila['id_presupuesto_go'];
                        }
                        
                        // Si falla la lógica anterior, $identificador_fila_unica sigue siendo js_rowIndex (temporal)
                        // Esto causaría probelmas en factura. Para filas nuevas DEBE ser el ID real.


                        // === GUARDAR EN PERIODO_FACTURA_GO (Estándar) ===
                        $datos_periodo = [
                            'id_registro_go' => $id_registro_go, // Se vincula al registro principal
                            'encabezado' => $tabla['encabezado'], // Dato de la tabla
                            'id_presupuesto' => $tabla['id_presupuesto'], // Dato de la tabla
                            'propina' => (!empty($fila['propina'])) ? str_replace(['$', ','], '', $fila['propina']) : 0, // Limpiamos la propina
                            'periodo_inicio' => $fila['periodo_inicio'],
                            'periodo_fin' => $fila['periodo_fin'],
                            'concepto' => (isset($fila['concepto']) && !empty($fila['concepto'])) ? $fila['concepto'] : '',
                            'comision' => $fila['comision'],
                            'id_identificador' => $identificador_fila_unica, // EL ENLACE CLAVE (Ahora es ID Real para nuevos/updates)
                            'usu_reg' => $session->get('id_usuario'),
                            'fec_reg' => date('Y-m-d H:i:s')
                        ];

                        $dataConfig = [
                            "tabla" => "periodo_factura_go",
                            "editar" => false
                        ];

                        $dataBitacora = [
                            'id_user' => $session->get('id_usuario'),
                            'script' => 'Agregar.php/guardarFacturaPeriodo'
                        ];

                        $responseFila = $this->globals->saveTabla($datos_periodo, $dataConfig, $dataBitacora);
                    }
                    


                    $archivos_pdf_fila = [];
                    $archivos_xml_fila = [];

                    // Validar y recolectar PDFs de la fila
                    if (!empty($fila['archivos']['pdf'])) {
                        foreach ($fila['archivos']['pdf'] as $pdf) {
                            if ($pdf->isValid() && !$pdf->hasMoved()) {
                                $archivos_pdf_fila[] = $pdf;
                            }
                        }
                    }

                    // Validar y recolectar XMLs de la fila
                    if (!empty($fila['archivos']['xml'])) {
                        foreach ($fila['archivos']['xml'] as $xml) {
                            if ($xml->isValid() && !$xml->hasMoved()) {
                                $archivos_xml_fila[] = $xml;
                            }
                        }
                    }



                    // 3. GUARDAR PDFS (CORREGIDO)
                    if (!empty($archivos_pdf_fila)) {

                        // Iteramos sobre los archivos PDF de ESTA fila
                        foreach ($archivos_pdf_fila as $archivo_pdf) { // Cambié $archivo por $archivo_pdf

                            if (!$archivo_pdf->isValid()) {
                                continue;
                            }

                            $timestamp = date('Ymd_His');
                            $extension = $archivo_pdf->getClientExtension();

                            // CORRECCIÓN: Usamos el identificador de fila para el nombre
                            $file = 'Factura_go_' . $identificador_fila_unica . '_' . $timestamp . '_' . uniqid() . '.' . $extension;

                            $ruta_destino = FCPATH . 'assets/pdf/';
                            $archivo_pdf->move($ruta_destino, $file);

                            // Agregar a adjuntos
                            $finalAttachments[] = $ruta_destino . $file;

                            $ruta_absoluta = base_url('assets/pdf/' . $file);
                            $ruta_relativa = 'assets/pdf/' . $file;

                            $dataConfigPdf = [
                                "tabla" => "factura_pdf_go",
                                "editar" => false
                            ];

                            $dataInsertPdf = [
                                'id_registro_go' => (int) $id_registro_go, // Enlace al registro maestro
                                'id_identificador' => $identificador_fila_unica, // <-- CORREGIDO: EL ENLACE DE FILA
                                'ruta_relativa' => $ruta_relativa,
                                'ruta_absoluta' => $ruta_absoluta,
                                'fec_reg' => date('Y-m-d H:i:s'),
                                'usu_reg' => $session->get('id_usuario')
                            ];

                            $dataBitacoraPdf = [
                                'id_user' => $session->get('id_usuario'),
                                'script' => 'Agregar.php/guardarFacturaPDF'
                            ];

                            // Guardamos la info del PDF
                            $this->globals->saveTabla($dataInsertPdf, $dataConfigPdf, $dataBitacoraPdf);
                        }
                    }

                    // 4. GUARDAR XMLS (CORREGIDO)
                    if (!empty($archivos_xml_fila)) {

                        // Iteramos sobre los archivos XML de ESTA fila
                        foreach ($archivos_xml_fila as $archivo_xml) { // Cambié $key => $archivo
                            if (!$archivo_xml->isValid()) {
                                continue;
                            }

                            $tipo = $archivo_xml->getMimeType();

                            if (in_array($tipo, ['text/xml', 'application/xml'])) {
                                $contenido = file_get_contents($archivo_xml->getTempName());

                                // ... (Tu código de parseo de XML va aquí, es correcto) ...
                                libxml_use_internal_errors(true);
                                $xml = simplexml_load_string($contenido);
                                if ($xml === false) {
                                    continue;
                                } // Saltar si el XML está mal

                                $namespaces = $xml->getNamespaces(true);
                                $cfdi = $xml->children($namespaces['cfdi']);

                                // ... (extracción de $version, $fecha, $total, $rfcEmisor, $uuid, etc.)

                                $attrs = $xml->attributes();
                                $version = (string) $attrs['Version'];
                                $fecha = (string) $attrs['Fecha'];
                                $total = (string) $attrs['Total'];
                                $moneda = (string) $attrs['Moneda'];
                                $Folio = (string) $attrs['Folio'];

                                $emisor = $cfdi->Emisor->attributes();
                                $rfcEmisor = (string) $emisor['Rfc'];
                                $nombreEmisor = (string) $emisor['Nombre'];

                                $receptor = $cfdi->Receptor->attributes();
                                $rfcReceptor = (string) $receptor['Rfc'];
                                $nombreReceptor = (string) $receptor['Nombre'];

                                $uuid = '';
                                $NoCertificadoSAT = ''; // Renombrado para claridad
                                if (isset($cfdi->Complemento)) {
                                    $tfdNamespace = isset($namespaces['tfd']) ? $namespaces['tfd'] : 'http://www.sat.gob.mx/TimbreFiscalDigital';
                                    if (isset($cfdi->Complemento->children($tfdNamespace)->TimbreFiscalDigital)) {
                                        $tfdAttributes = $cfdi->Complemento->children($tfdNamespace)->TimbreFiscalDigital->attributes();
                                        $uuid = (string) $tfdAttributes['UUID'];
                                        $NoCertificadoSAT = (string) $tfdAttributes['NoCertificadoSAT'];
                                    }
                                }
                                // ... Fin del parseo ...

                                // Guardar XML físico para adjuntar
                                $xmlName = 'Factura_go_xml_' . $identificador_fila_unica . '_' . date('Ymd_His') . '_' . uniqid() . '.xml';
                                $ruta_xml_destino = FCPATH . 'assets/pdf/' . $xmlName;
                                file_put_contents($ruta_xml_destino, $contenido);
                                $finalAttachments[] = $ruta_xml_destino;

                                $dataConfigXml = [
                                    "tabla" => "xml_go",
                                    "editar" => false
                                ];
                                $dataInsertXml = [
                                    'id_registro_go' => (int) $id_registro_go, // Enlace al registro maestro
                                    'version' => $version,
                                    'fecha' => date('Y-m-d H:i:s', strtotime($fecha)),
                                    'total' => $total,
                                    'moneda' => $moneda,
                                    'id_identificador' => $identificador_fila_unica, // <-- CORREGIDO: EL ENLACE DE FILA
                                    'folio' => $Folio,
                                    'no_certificado' => $NoCertificadoSAT, // Usar el del timbre
                                    'emisor_rfc' => $rfcEmisor,
                                    'emisor_nombre' => $nombreEmisor,
                                    'receptor_rfc' => $rfcReceptor,
                                    'receptor_nombre' => $nombreReceptor,
                                    'uuid' => $uuid,
                                    'fec_reg' => date('Y-m-d H:i:s'),
                                    'usu_reg' => $session->get('id_usuario')
                                ];

                                $dataBitacoraXml = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarFacturaGO'];

                                // Guardamos la info del XML
                                $responseXML = $this->globals->saveTabla($dataInsertXml, $dataConfigXml, $dataBitacoraXml);

                                // Importante: Asignar la respuesta final solo si no hay error
                                if (!$responseXML->error) {
                                    $response->error = false;
                                    $response->respuesta = 'Archivos XML y PDF guardados correctamente';
                                }
                            }
                        }
                    }
                }
            }


           $folio = $this->globals->getTabla(['tabla'=>'folio_go', 'where'=>['id_folio_go' => $no_consecutivo]]);
           $folioCompleto = "";
           
           if(isset($folio->data) && !empty($folio->data)){
               $idDireccion = $folio->data[0]->id_direccion;
               $no_consecutivo_val = $folio->data[0]->no_consecutivo;
               $direccionObj = $this->globals->getTabla(["tabla"=>"direccion", "where"=>["id_director"=>$idDireccion]]);
               
               $direccionStr = "";
               if(isset($direccionObj->data) && !empty($direccionObj->data)){
                   // Assuming 'clave' is the column for the prefix (e.g., DA, DJ). 
                   // If 'clave' is not the correct column, check 'dsc_direccion' or 'siglas'.
                   $direccionStr = isset($direccionObj->data[0]->folio_prefijo) ? $direccionObj->data[0]->folio_prefijo : (isset($direccionObj->data[0]->folio_prefijo) ? $direccionObj->data[0]->dscfolio_prefijo : '');
               }
               
               $folioCompleto = $direccionStr . str_pad($no_consecutivo_val, 3, "0", STR_PAD_LEFT).'/'.date('Y');
           }
           

           
            // === FIN NUEVO CÓDIGO DE PROCESAMIENTO ===
            if($session->get('id_usuario') != 1){
                
                // Enviar correos si hay adjuntos
                if(isset($data['es_borrador']) && $data['es_borrador'] != 1){
                    
                
                    if (!empty($finalAttachments)) {
                        $mailer = new \App\Libraries\Mailer();
                        
                        $mensajeHTML = '
                        <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                            <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                                <div style="background-color: #004080; padding: 20px; text-align: center;">
                                    <h2 style="color: #ffffff; margin: 0;">Facturas Generadas</h2>
                                </div>
                                <div style="padding: 30px; color: #333;">
                                    <p style="font-size: 16px;">Estimado usuario,</p>
                                    <p style="font-size: 16px;">Se adjuntan a este correo los archivos <strong>XML</strong> y <strong>PDF</strong> correspondientes a las facturas de <strong>Gastos Operativos (GO)</strong> generadas en el sistema SUSI.</p>
                                    <p style="font-size: 16px;"><strong>Folio: ' . $folioCompleto . '</strong></p>
                                    <p style="font-size: 14px; color: #666;">Por favor, conserve estos comprobantes para su control administrativo.</p>
                                    
                                    <div style="margin-top: 25px; padding: 15px; background-color: #e3f2fd; border-left: 5px solid #2196f3; border-radius: 4px;">
                                        <p style="margin: 0; font-size: 14px; color: #0d47a1;"><strong>Nota:</strong> Este es un mensaje automático, favor de no responder a esta dirección.</p>
                                    </div>
                                </div>
                                <div style="background-color: #e0e0e0; text-align: center; padding: 15px; font-size: 12px; color: #666;">
                                    © ' . date('Y') . ' Sistema de Atención SUSI. Todos los derechos reservados.
                                </div>
                            </div>
                        </div>';

                        $mailer->send(
                            $mensajeHTML, 
                            $session->get('id_usuario'), 
                        ['dasedetur@guanajuato.gob.mx'], 
                        //  ['palafox.marin31@gmail.com'],
                            2, // Tipo 2 para plantilla custom (HTML completo)
                            false, 
                            $finalAttachments, 
                            "Facturas G.O. Generadas - SUSI - Folio: " . $folioCompleto
                        );
                    }  

                }  

            }
        }

        return $this->respond($responsePrincipal);
    }

   public function guardaBorradorGO()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error|Error al guardar Borrador GO";
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        $archivos_post = $this->request->getFiles();
        
        // 1. MAPEAR ARCHIVOS POR rowIndex (Corrección de mapeo);
       // die( var_dump($data) );
        $archivos = [];

        if (isset($archivos_post['archivos'])) {
            foreach ($archivos_post['archivos'] as $key => $file_data) {
                // Extraer tipo (pdf/xml) y rowIndex
                if (strpos($key, 'pdf_') === 0) {
                    $rowIndex = substr($key, 4); // Remover 'pdf_'
                    if (isset($file_data['pdf'])) {
                        // Inicializar array para este rowIndex si no existe
                        if (!isset($archivos[$rowIndex])) {
                            $archivos[$rowIndex] = ['pdf' => [], 'xml' => []];
                        }
                        $archivos[$rowIndex]['pdf'] = $file_data['pdf'];
                    }
                } elseif (strpos($key, 'xml_') === 0) {
                    $rowIndex = substr($key, 4); // Remover 'xml_'
                    if (isset($file_data['xml'])) {
                        // Inicializar array para este rowIndex si no existe
                        if (!isset($archivos[$rowIndex])) {
                            $archivos[$rowIndex] = ['pdf' => [], 'xml' => []];
                        }
                        $archivos[$rowIndex]['xml'] = $file_data['xml'];
                    }
                }
            }
        }


        $id_registro_go = isset($data['id_registro_go']) && !empty($data['id_registro_go']) ? $data['id_registro_go'] : null;

         $dataInsert = [
            'id_reserva_go' => $data['id_reserva_go'],
            'id_estatus' => (isset($data['es_borrador']) && $data['es_borrador'] == 0) ? 2: 1, // Siempre borrador
            'id_direccion_responsable' => $data['direccion_responsable'],
            'fecha_tramite' => $data['fecha_tramite'],
            'no_consecutivo' => $data['no_consecutivo'],
            'id_reponsable_solicitud' => (int) $data['id_reponsable_solicitud'],
            'director_general' => 1,
            'secretario' => (int) $data['secretario'],
            'id_subsecretario' => (int) $data['id_subsecretario'],
            'contrato_convenio' => ($data['contrato_convenio'] == 'NO') ? 2 : 1,
            'formato_establecido' => ($data['formato_establecido'] == 'SI') ? 1 : 2,
            'documentacion_comprobatoria' => $data['documentacion_comprobatoria'],
            'poliza' => ($data['poliza'] == 'SI') ? 1 : 2,
            'formato_conformidad' => ($data['formato_conformidad'] == 'SI') ? 1 : 2,
            'documentacion_requerida' => ($data['documentacion_requerida'] == 'SI') ? 1 : 2,
            'evidencia_entrega' => (int) $data['evidencia_entrega'],
          //  'concepto_gasto' => $data['concepto_gasto'],
            'fecha_tramite' => (isset($data['fecha_tramite']) && !empty($data['fecha_tramite']))?$data['fecha_tramite']:date('Y-m-d'),
            //'total_importe' => $data['total_importe'], 
           // 'comision' => $data['comision'],
            'lugar' => $data['lugar'],
            'usu_reg' => $session->get('id_usuario'),
        ];
        
        // CORRECCIÓN: Agregar ID al array de datos para asegurar UPDATE
        if(!empty($id_registro_go)) {
            $dataInsert['id_registro_go'] = $id_registro_go;
        }
        
        $dataConfig = [
            "tabla" => "registro_go",
            "editar" => true,
            "idEditar" => ['id_registro_go' => $id_registro_go]
        ];


        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaBorradorGO'];
        $responsePrincipal = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
       
      $rowIndex = is_array($data['rowIndex']) ? $data['rowIndex'] : [];
     
        $grupal = [];
        $pdf = [];
        $xml = [];
        foreach ($rowIndex as $key => $rowKey) {
            // Verificar si existe id_identificador para este rowKey
            // O si el valor de id_identificador es 0 (para filtrar)
            if (isset($data['id_identificador'][$rowKey]) && $data['id_identificador'][$rowKey] === '0') {
                continue; // Saltar elementos con id_identificador = '0'
            }

            if (strpos($rowKey, 'new_') === 0) {
                 $grupal[] = [
                    'rowIndex' => $rowKey,
                    'id_partida' => $data['id_partida'][$rowKey] ?? null,
                    'id_presupuesto' => $data['id_presupuesto'][$rowKey] ?? null,
                    'propina' => $data['propina'][$rowKey] ?? null,
                    'periodo_inicio' => $data['periodo_inicio'][$rowKey] ?? null,
                    'periodo_fin' => $data['periodo_fin'][$rowKey] ?? null,
                    'importe' => $data['importe'][$rowKey] ?? null,
                    'encabezado' => $data['encabezado'][$rowKey] ?? null,
                    'concepto' => $data['concepto'][$rowKey] ?? null,
                    'comision' => $data['comision'][$rowKey] ?? null,
                ];
                     
                $pdf[] = $archivos[$rowKey]['pdf'] ?? null;
                $xml[] = $archivos[$rowKey]['xml'] ?? null;
                
            }
            
      
        }
    
    
        foreach ($grupal as $key => $value) {
             $datos_periodo = [
                            'id_registro_go' => $id_registro_go, 
                            'encabezado' => $value['encabezado'], 
                            'id_identificador' => $value['rowIndex'], // Usamos el ID temporal 'new_...'
                            'id_presupuesto' => $value['id_presupuesto'] ?? null, 
                            'propina' => (!empty($value['propina'])) ? str_replace(['$', ','], '', $value['propina']) : 0, 
                            'periodo_inicio' => isset($value['periodo_inicio']) ? $value['periodo_inicio'] : date('Y-m-d'),
                            'periodo_fin' => isset($value['periodo_fin']) ? $value['periodo_fin'] : date('Y-m-d'),
                            'usu_reg' => $session->get('id_usuario'),
                            'fec_reg' => date('Y-m-d H:i:s'),
                            'concepto' => $value['concepto'] ?? null,
                            'comision' => $value['comision'] ?? null,
                        ];
                          $response = $this->globals->saveTabla(
                            $datos_periodo,
                            ["tabla" => "periodo_factura_go", "editar" => false], 
                             ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/insertPeriodo']
                        );
                
                if (isset($pdf[$key])) {
                    foreach ($pdf[$key] as $index => $pdfFile) { // Renombrado variable interna para evitar colisión
                        if ($pdfFile->isValid() && !$pdfFile->hasMoved()) {
                            $timestamp = date('Ymd_His');
                            $extension = $pdfFile->getClientExtension();
                            // Usamos $id_fila_real para asegurar unicidad y referencia real
                            $file = 'Factura_go_' . $value['rowIndex'] . '_' . $timestamp . '_' . uniqid() . '.' . $extension;
                            $ruta_destino = FCPATH . 'assets/pdf/';
                            $pdfFile->move($ruta_destino, $file);
                            
                            $dataInsert = [
                                'id_registro_go' => (int) $id_registro_go, 
                                'id_identificador' => $value['rowIndex'], 
                                'ruta_relativa' => 'assets/pdf/' . $file,
                                'ruta_absoluta' => base_url('assets/pdf/' . $file),
                                'fec_reg' => date('Y-m-d H:i:s'),
                                'usu_reg' => $session->get('id_usuario')
                            ];
                            $response = $this->globals->saveTabla($dataInsert, ["tabla" => "factura_pdf_go", "editar" => false], []);
                        }
                    }
                }

                if (isset($xml[$key])) {
                    foreach($xml[$key] as $i => $xml_file){
                     if ($xml_file->isValid() && !$xml_file->hasMoved()) {
                     $contenido = file_get_contents($xml_file->getTempName());

                                // ... (Tu código de parseo de XML va aquí, es correcto) ...
                                libxml_use_internal_errors(true);
                                $xmlObj = simplexml_load_string($contenido);
                                if ($xmlObj === false) {
                                    continue;
                                } // Saltar si el XML está mal

                                $namespaces = $xmlObj->getNamespaces(true);
                                $cfdi = $xmlObj->children($namespaces['cfdi']);

                                // ... (extracción de $version, $fecha, $total, $rfcEmisor, $uuid, etc.)

                                $attrs = $xmlObj->attributes();
                                $version = (string) $attrs['Version'];
                                $fecha = (string) $attrs['Fecha'];
                                $total = (string) $attrs['Total'];
                                $moneda = (string) $attrs['Moneda'];
                                $Folio = (string) $attrs['Folio'];

                                $emisor = $cfdi->Emisor->attributes();
                                $rfcEmisor = (string) $emisor['Rfc'];
                                $nombreEmisor = (string) $emisor['Nombre'];

                                $receptor = $cfdi->Receptor->attributes();
                                $rfcReceptor = (string) $receptor['Rfc'];
                                $nombreReceptor = (string) $receptor['Nombre'];

                                $uuid = '';
                                $NoCertificadoSAT = ''; // Renombrado para claridad
                                if (isset($cfdi->Complemento)) {
                                    $tfdNamespace = isset($namespaces['tfd']) ? $namespaces['tfd'] : 'http://www.sat.gob.mx/TimbreFiscalDigital';
                                    if (isset($cfdi->Complemento->children($tfdNamespace)->TimbreFiscalDigital)) {
                                        $tfdAttributes = $cfdi->Complemento->children($tfdNamespace)->TimbreFiscalDigital->attributes();
                                        $uuid = (string) $tfdAttributes['UUID'];
                                        $NoCertificadoSAT = (string) $tfdAttributes['NoCertificadoSAT'];
                                    }
                                }
                                // ... Fin del parseo ...

                                // Guardar XML físico para adjuntar
                                $xmlName = 'Factura_go_xml_' . $value['rowIndex'] . '_' . date('Ymd_His') . '_' . uniqid() . '.xml';
                                $ruta_xml_destino = FCPATH . 'assets/pdf/' . $xmlName;
                                file_put_contents($ruta_xml_destino, $contenido);
                                $finalAttachments[] = $ruta_xml_destino;

                                $dataConfigXml = [
                                    "tabla" => "xml_go",
                                    "editar" => false
                                ];
                                $dataInsertXml = [
                                    'id_registro_go' => (int) $id_registro_go, // Enlace al registro maestro
                                    'version' => $version,
                                    'fecha' => date('Y-m-d H:i:s', strtotime($fecha)),
                                    'total' => $total,
                                    'moneda' => $moneda,
                                    'id_identificador' => $value['rowIndex'], // <-- CORREGIDO: EL ENLACE DE FILA
                                    'folio' => $Folio,
                                    'no_certificado' => $NoCertificadoSAT, // Usar el del timbre
                                    'emisor_rfc' => $rfcEmisor,
                                    'emisor_nombre' => $nombreEmisor,
                                    'receptor_rfc' => $rfcReceptor,
                                    'receptor_nombre' => $nombreReceptor,
                                    'uuid' => $uuid,
                                    'fec_reg' => date('Y-m-d H:i:s'),
                                    'usu_reg' => $session->get('id_usuario')
                                ];

                                $dataBitacoraXml = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarFacturaGO'];

                                // Guardamos la info del XML
                                $responseXML = $this->globals->saveTabla($dataInsertXml, $dataConfigXml, $dataBitacoraXml);
                           
                    }

                }
            }
        
            
        } // Fin foreach grupal

        return $this->respond($responsePrincipal);
    }
    
    private function procesarArchivosFila($id_registro_go, $id_fila_real, $rowIndexOriginal, $archivos_por_rowIndex, $session) {
        // Buscamos archivos mapeados al rowIndex original (que puede ser string 'table_1...')
        $archivos = isset($archivos_por_rowIndex[$rowIndexOriginal]) ? $archivos_por_rowIndex[$rowIndexOriginal] : null;
        
        if (!$archivos) return;

        // PDF
        if (isset($archivos['pdf']) && is_array($archivos['pdf'])) {
            foreach ($archivos['pdf'] as $pdf) {
                if ($pdf->isValid() && !$pdf->hasMoved()) {
                    $timestamp = date('Ymd_His');
                    $extension = $pdf->getClientExtension();
                    // Usamos $id_fila_real para asegurar unicidad y referencia real
                    $file = 'Factura_go_' . $id_fila_real . '_' . $timestamp . '_' . uniqid() . '.' . $extension;
                    $ruta_destino = FCPATH . 'assets/pdf/';
                    $pdf->move($ruta_destino, $file);
                    
                    $dataInsert = [
                        'id_registro_go' => (int) $id_registro_go, 
                        'id_identificador' => $id_fila_real, 
                        'ruta_relativa' => 'assets/pdf/' . $file,
                        'ruta_absoluta' => base_url('assets/pdf/' . $file),
                        'fec_reg' => date('Y-m-d H:i:s'),
                        'usu_reg' => $session->get('id_usuario')
                    ];
                    $this->globals->saveTabla($dataInsert, ["tabla" => "factura_pdf_go", "editar" => false], []);
                }
            }
        }

        // XML
        if (isset($archivos['xml']) && is_array($archivos['xml'])) {
            foreach ($archivos['xml'] as $xml_file) {
                 if ($xml_file->isValid() && !$xml_file->hasMoved()) {
                     // Parseo XML simplificado
                    $contenido = file_get_contents($xml_file->getTempName());
                    // ... Logica de parseo minima o completa ...
                    // Por brevedad, asumimos guardado básico, pero idealmente parsear para sacar UUID, etc.
                    // Copiamos logica de parseo si es vital, o guardamos el archivo fisico y registro basico.
                    
                    // Guardado físico
                    $xmlName = 'Factura_go_xml_' . $id_fila_real . '_' . date('Ymd_His') . '_' . uniqid() . '.xml';
                    file_put_contents(FCPATH . 'assets/pdf/' . $xmlName, $contenido);
                    
                    $dataInsertXml = [
                        'id_registro_go' => (int) $id_registro_go, 
                        'id_identificador' => $id_fila_real, 
                        'folio' => 'XML_DRAFT', // Placeholder si no parseamos
                        'uuid' => 'DRAFT_' . uniqid(),
                        'fec_reg' => date('Y-m-d H:i:s'),
                        'usu_reg' => $session->get('id_usuario')
                    ];
                     $this->globals->saveTabla($dataInsertXml, ["tabla" => "xml_go", "editar" => false], []);
                 }
            }
        }
    }
    public function editarGO()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error|Error al guardar GO";
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        $archivos_post = $this->request->getFiles();
      
        // Verificar que tenemos id_registro_go para edición
        if (empty($data['id_registro_go'])) {
            $response->respuesta = "Error|No se recibió el ID de registro GO para edición";
            return $this->respond($response);
        }

        $id_registro_go = $data['id_registro_go'];

        // Validaciones de campos obligatorios
        if ($data['secretario'] == 0) {
            $response->respuesta = "Es requerido el Secretario o Director";
            return $this->respond($response);
        }
        if ($data['id_subsecretario'] == 0) {
            $response->respuesta = "Es requerido el Subsecretario";
            return $this->respond($response);
        }
        if (($data['direccion_responsable']) == 0) {
            $response->respuesta = "Es requerido el Dirección Responsable";
            return $this->respond($response);
        }
        if (isset($data['documentacion_comprobatoria']) && empty($data['documentacion_comprobatoria'])) {
            $response->respuesta = "Es requerido el documentacion_comprobatorian";
            return $this->respond($response);
        }
        if (isset($data['poliza']) && empty($data['poliza'])) {
            $response->respuesta = "Es requerido el poliza";
            return $this->respond($response);
        }
        if (isset($data['formato_conformidad']) && empty($data['formato_conformidad'])) {
            $response->respuesta = "Es requerido el formato_conformidad";
            return $this->respond($response);
        }
        // if (isset($data['concepto_gasto']) && empty($data['concepto_gasto'])) {
        //     $response->respuesta = "Es requerido el concepto gasto";
        //     return $this->respond($response);
        // }

        if (isset($data['fecha_tramite']) && empty($data['fecha_tramite'])) {
            $data['fecha_tramite'] = date('Y-m-d');
        }

        // 1. Actualizar el registro principal
        $estatus = (isset($data['es_borrador']) && $data['es_borrador'] == 1) ? 1 : 2;
        
        // PROCESAR ELIMINACIÓN DE FILAS
        if (isset($data['deleted_rows']) && !empty($data['deleted_rows'])) {
            $deleted_rows = json_decode($data['deleted_rows'], true);
            if (is_array($deleted_rows) && !empty($deleted_rows)) {
                foreach ($deleted_rows as $id_identificador) {
                    // Marcar como no visible en periodo_factura_go
                     $this->globals->saveTabla(
                        ['visible' => 0],
                        ['tabla' => 'periodo_factura_go', 'editar' => true, 'idEditar' => ['id_identificador' => $id_identificador]],
                        ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/editarGO/deleteRow']
                    );
                     // Opcional: Marcar facturas PDF y XML también
                     $this->globals->saveTabla(
                        ['visible' => 0],
                        ['tabla' => 'factura_pdf_go', 'editar' => true, 'idEditar' => ['id_identificador' => $id_identificador]],
                        ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/editarGO/deleteRowPDF']
                    );
                     $this->globals->saveTabla(
                        ['visible' => 0],
                        ['tabla' => 'xml_go', 'editar' => true, 'idEditar' => ['id_identificador' => $id_identificador]],
                        ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/editarGO/deleteRowXML']
                    );
                }
            }
        }

        $dataInsert = [
            //'id_reserva_go' => $data['id_reserva_go'],
            'id_estatus' => $estatus,
            'id_direccion_responsable' => $data['direccion_responsable'],
            'fecha_tramite' => $data['fecha_tramite'],
            'no_consecutivo' => (int) $data['no_consecutivo'],
            'id_reponsable_solicitud' => (int) $data['id_reponsable_solicitud'],
            'director_general' => 1,
            'secretario' => (int) $data['secretario'],
            'id_subsecretario' => (int) $data['id_subsecretario'],
            'contrato_convenio' => ($data['contrato_convenio'] == 'NO') ? 2 : 1,
            'formato_establecido' => ($data['formato_establecido'] == 'SI') ? 1 : 2,
            'documentacion_comprobatoria' => $data['documentacion_comprobatoria'],
            'poliza' => ($data['poliza'] == 'SI') ? 1 : 2,
            'formato_conformidad' => ($data['formato_conformidad'] == 'SI') ? 1 : 2,
            'documentacion_requerida' => ($data['documentacion_requerida'] == 'SI') ? 1 : 2,
            'evidencia_entrega' => (int) $data['evidencia_entrega'],
            // 'concepto_gasto' => $data['concepto_gasto'],
            // 'comision' => $data['comision'],

            'lugar' => $data['lugar'],
            'usu_act' => $session->get('id_usuario'),
        ];

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/editarGO'];
        $dataConfig = [
            "tabla" => "registro_go",
            "editar" => true,
            'idEditar' => ['id_registro_go' => $data['id_registro_go']]
        ];

        $responsePrincipal = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        if ($responsePrincipal->error) {
            return $this->respond($responsePrincipal);
        }

        // 2. Organizar archivos por fila
        $archivos_por_fila = [];

        // Procesar archivos subidos
        foreach ($archivos_post as $clave => $grupo_archivos) {
            // Buscar archivos PDF (formato: pdf_{rowIndex})
            if (strpos($clave, 'pdf_') === 0) {
                $rowIndex = substr($clave, 4); // Remover "pdf_"

                if (!isset($archivos_por_fila[$rowIndex])) {
                    $archivos_por_fila[$rowIndex] = ['pdf' => [], 'xml' => []];
                }

                // Manejar múltiples archivos PDF
                if (is_array($grupo_archivos)) {
                    foreach ($grupo_archivos as $archivo) {
                        if ($archivo->isValid() && $archivo->getError() === UPLOAD_ERR_OK) {
                            $archivos_por_fila[$rowIndex]['pdf'][] = $archivo;
                        }
                    }
                } elseif ($grupo_archivos->isValid() && $grupo_archivos->getError() === UPLOAD_ERR_OK) {
                    $archivos_por_fila[$rowIndex]['pdf'][] = $grupo_archivos;
                }
            }

            // Buscar archivos XML (formato: xml_{rowIndex})
            if (strpos($clave, 'xml_') === 0) {
                $rowIndex = substr($clave, 4); // Remover "xml_"

                if (!isset($archivos_por_fila[$rowIndex])) {
                    $archivos_por_fila[$rowIndex] = ['pdf' => [], 'xml' => []];
                }

                // Manejar múltiples archivos XML
                if (is_array($grupo_archivos)) {
                    foreach ($grupo_archivos as $archivo) {
                        if ($archivo->isValid() && $archivo->getError() === UPLOAD_ERR_OK) {
                            $archivos_por_fila[$rowIndex]['xml'][] = $archivo;
                        }
                    }
                } elseif ($grupo_archivos->isValid() && $grupo_archivos->getError() === UPLOAD_ERR_OK) {
                    $archivos_por_fila[$rowIndex]['xml'][] = $grupo_archivos;
                }
            }
        }

        // 3. Procesar cada tabla (conjunto de partidas)
        $tablas_procesadas = [];

        if (isset($data['encabezado'])) {
            foreach ($data['encabezado'] as $i => $encabezado_texto) {
                $tablas_procesadas[$i] = [
                    'encabezado' => $encabezado_texto,
                    'id_presupuesto' => $data['id_presupuesto'][$i] ?? null,
                    'filas' => []
                ];

                // Si no hay datos para esta tabla, continuar
                if (!isset($data['periodo_inicio_' . $i])) {
                    continue;
                }

                // Procesar cada fila de la tabla
                foreach ($data['periodo_inicio_' . $i] as $j => $periodo_inicio) {
                    // Obtener el id_identificador de la fila (si existe)
                    $id_identificador = $data['id_identificador_' . $i][$j] ?? null;

                    // Determinar el rowIndex para buscar archivos
                    $rowIndex = $id_identificador;
                    if (empty($rowIndex)) {
                        // Para filas nuevas, usar un patrón diferente
                        $rowIndex = 'nuevo_' . $i . '_' . $j;
                    }

                    // Obtener archivos para esta fila
                    $archivos_fila = $archivos_por_fila[$rowIndex] ?? ['pdf' => [], 'xml' => []];

                    // Construir objeto de fila
                    $fila_completa = [
                        'id_identificador' => $id_identificador,
                        'propina' => $data['propina_' . $i][$j] ?? null,
                        'concepto' => $data['concepto_' . $i][$j] ?? null, // Capturamos concepto
                        'comision' => $data['comision_' . $i][$j] ?? null, // Capturamos comision
                        'periodo_inicio' => $data['periodo_inicio_' . $i][$j] ?? null,
                        'periodo_fin' => $data['periodo_fin_' . $i][$j] ?? null,
                        'archivos' => $archivos_fila,
                        'rowIndex' => $rowIndex,
                        'tabla_index' => $i,
                        'fila_index' => $j
                    ];

                    $tablas_procesadas[$i]['filas'][] = $fila_completa;
                }
            }
        }

        // 4. Procesar cada tabla y sus filas
        foreach ($tablas_procesadas as $i => $tabla) {
            foreach ($tabla['filas'] as $j => $fila) {

                $id_identificador = $fila['id_identificador'];

                // Preparar datos para periodo_factura_go
                $datos_periodo = [
                    //'id_registro_go' => $id_registro_go,
                    'encabezado' => $tabla['encabezado'],
                  //  'id_presupuesto' => $tabla['id_presupuesto'],
                    'propina' => (!empty($fila['propina'])) ? str_replace(['$', ','], '', $fila['propina']) : 0,
                    'concepto' => $fila['concepto'], // Guardamos concepto
                    'comision' => $fila['comision'], // Guardamos comision
                    'periodo_inicio' => $fila['periodo_inicio'],
                    'periodo_fin' => $fila['periodo_fin'],
                    'usu_reg' => $session->get('id_usuario')
                ];

                // Determinar si es edición o inserción
                if (isset($id_identificador) && $id_identificador >= 0) {
                    // EDICIÓN: Buscar registro existente

                    $existePeriodo = $this->globals->getTabla([
                        'tabla' => 'periodo_factura_go',
                        'where' => [
                            'id_registro_go' => $id_registro_go,
                            'id_identificador' => $id_identificador
                        ]
                    ]);

                    if (!$existePeriodo->error && !empty($existePeriodo->data)) {
                        // Actualizar registro existente
                        $dataConfig = [
                            "tabla" => "periodo_factura_go",
                            "editar" => true,
                            "idEditar" => ['id_periodo_factura' => $existePeriodo->data[0]->id_periodo_factura]
                        ];

                        $dataBitacora = [
                            'id_user' => $session->get('id_usuario'),
                            'script' => 'Agregar.php/editarGO'
                        ];

                        $responsePeriodo = $this->globals->saveTabla($datos_periodo, $dataConfig, $dataBitacora);

                        if ($responsePeriodo->error) {
                            $response->error = true;
                            $response->respuesta .= "|Error al actualizar periodo para fila $i-$j";
                        }
                    }
                }


                //====================================================================================
                //====================================================================================
                //====================================================================================

                foreach ($archivos_post as $clave => $a) {

                    if (strpos($clave, 'pdf_') === 0) {
                        $identificador = substr($clave, 4); // Remover "xml_"
                        $existePeriodo = $this->globals->getTabla([
                            'tabla' => 'factura_pdf_go',
                            'where' => [
                                'id_registro_go' => $id_registro_go,
                                'id_identificador' => $identificador
                            ]
                        ]);
                        $ID = (isset($existePeriodo->data) && !empty($existePeriodo->data)) ? $existePeriodo->data[0]->id_factura_pdf_go : '';
                        if ($ID) {
                            foreach ($a as $archivo_pdf) {

                                if ($archivo_pdf->isValid() && $archivo_pdf->getError() === UPLOAD_ERR_OK) {
                                    $timestamp = date('Ymd_His');
                                    $extension = $archivo_pdf->getClientExtension();
                                    $nombre_archivo = 'Factura_go_' . $id_identificador . '_' . $timestamp . '_' . uniqid() . '.' . $extension;

                                    $ruta_destino = FCPATH . 'assets/pdf/';

                                    // Crear directorio si no existe
                                    if (!is_dir($ruta_destino)) {
                                        mkdir($ruta_destino, 0777, true);
                                    }

                                    if ($archivo_pdf->move($ruta_destino, $nombre_archivo)) {
                                        $ruta_relativa = 'assets/pdf/' . $nombre_archivo;
                                        $ruta_absoluta = base_url($ruta_relativa);



                                        $datos_pdf = [
                                            'id_registro_go' => $id_registro_go,
                                            //  'id_identificador' => $id_identificador,
                                            'ruta_relativa' => $ruta_relativa,
                                            'ruta_absoluta' => $ruta_absoluta,
                                            'fec_reg' => date('Y-m-d H:i:s'),
                                            'usu_reg' => $session->get('id_usuario')
                                        ];


                                        // Actualizar PDF existente (último registro)
                                        $dataConfigPdf = [
                                            "tabla" => "factura_pdf_go",
                                            "editar" => true,
                                            "idEditar" => ['id_factura_pdf_go' => $ID]
                                        ];
                                    }

                                    $dataBitacoraPdf = [
                                        'id_user' => $session->get('id_usuario'),
                                        'script' => 'Agregar.php/editarGO'
                                    ];

                                    $respuestaPDF = $this->globals->saveTabla($datos_pdf, $dataConfigPdf, $dataBitacoraPdf);

                                    if ($respuestaPDF->error) {
                                        $response->respuesta .= "|Error al guardar PDF para fila";
                                    }
                                }
                            }
                        }
                    }
                    if (strpos($clave, 'xml_') === 0) {
                        $identificador = substr($clave, 4); // Remover "xml_"
                        $existePeriodo = $this->globals->getTabla([
                            'tabla' => 'xml_go',
                            'where' => [
                                'id_registro_go' => $id_registro_go,
                                'id_identificador' => $identificador
                            ]
                        ]);

                        $ID = (isset($existePeriodo->data) && !empty($existePeriodo->data)) ? $existePeriodo->data[0]->id_xml : '';

                        if ($ID) {
                            foreach ($a as $archivo_xml) {

                                $tipo = $archivo_xml->getMimeType();

                                if (in_array($tipo, ['text/xml', 'application/xml'])) {
                                    $contenido = file_get_contents($archivo_xml->getTempName());
                                    libxml_use_internal_errors(true);
                                    $xml = simplexml_load_string($contenido);

                                    if ($xml !== false) {
                                        $namespaces = $xml->getNamespaces(true);
                                        $cfdi = $xml->children($namespaces['cfdi']);

                                        $attrs = $xml->attributes();
                                        $version = (string) $attrs['Version'];
                                        $fecha = (string) $attrs['Fecha'];
                                        $total = (string) $attrs['Total'];
                                        $moneda = (string) $attrs['Moneda'];
                                        $Folio = (string) $attrs['Folio'];

                                        $emisor = $cfdi->Emisor->attributes();
                                        $rfcEmisor = (string) $emisor['Rfc'];
                                        $nombreEmisor = (string) $emisor['Nombre'];

                                        $receptor = $cfdi->Receptor->attributes();
                                        $rfcReceptor = (string) $receptor['Rfc'];
                                        $nombreReceptor = (string) $receptor['Nombre'];

                                        $uuid = '';
                                        $NoCertificadoSAT = '';

                                        if (isset($cfdi->Complemento)) {
                                            $tfdNamespace = isset($namespaces['tfd']) ? $namespaces['tfd'] : 'http://www.sat.gob.mx/TimbreFiscalDigital';
                                            if (isset($cfdi->Complemento->children($tfdNamespace)->TimbreFiscalDigital)) {
                                                $tfdAttributes = $cfdi->Complemento->children($tfdNamespace)->TimbreFiscalDigital->attributes();
                                                $uuid = (string) $tfdAttributes['UUID'];
                                                $NoCertificadoSAT = (string) $tfdAttributes['NoCertificadoSAT'];
                                            }
                                        }


                                        $datos_xml = [
                                            'id_registro_go' => $id_registro_go,
                                            // 'id_identificador' => $id_identificador,
                                            'version' => $version,
                                            'fecha' => date('Y-m-d H:i:s', strtotime($fecha)),
                                            'total' => $total,
                                            'moneda' => $moneda,
                                            'folio' => $Folio,
                                            'no_certificado' => $NoCertificadoSAT,
                                            'emisor_rfc' => $rfcEmisor,
                                            'emisor_nombre' => $nombreEmisor,
                                            'receptor_rfc' => $rfcReceptor,
                                            'receptor_nombre' => $nombreReceptor,
                                            'uuid' => $uuid,
                                            'fec_reg' => date('Y-m-d H:i:s'),
                                            'usu_reg' => $session->get('id_usuario')
                                        ];


                                        // Actualizar XML existente (último registro)
                                        $dataConfigXml = [
                                            "tabla" => "xml_go",
                                            "editar" => true,
                                            "idEditar" => ['id_xml' => $ID]
                                        ];


                                        $dataBitacoraXml = [
                                            'id_user' => $session->get('id_usuario'),
                                            'script' => 'Agregar.php/editarGO'
                                        ];

                                        $respuestaXML = $this->globals->saveTabla($datos_xml, $dataConfigXml, $dataBitacoraXml);
                                        // var_dump($respuestaXML);
                                        if ($respuestaXML->error) {
                                            $response->respuesta .= "|Error al guardar XML para fila";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }


            }





        }
        // die();

        // 7. Responder con éxito
        $response->error = false;
        $response->respuesta = "GO editado correctamente";
        $response->idRegistro = $id_registro_go;

        return $this->respond($response);
    }
    private function cambiarStatusPT($id = null)
    {
        $session = \Config\Services::session();
        $this->globals = new Mglobal();
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/cambiarStatusPT'];
        $dataConfig = [
            "tabla" => "reserva",
            "editar" => true,
            "idEditar" => ['id_reserva' => (int) $id]
        ];
        $response = $this->globals->saveTabla(['id_estatus' => 4], $dataConfig, $dataBitacora);


    }
    public function enviarCorreoDenuncia()
    {
        // Inicializar servicios y objetos
        $email = Services::email();
        $session = Services::session();
        $response = new \stdClass();

        // Configurar y enviar correo
        $email->setFrom('a.palafoxm@guanajuato.gob.mx', 'SUSI');
        //$email->setTo("dasedetur@guanajuato.gob.mx");
        $email->setTo([
            'tmares@guanajuato.gob.mx',
            'luis.perez@guanajuato.gob.mx'
        ]);
        $email->setSubject('REGISTRO DE DENUNCIA');
        $email->setMessage('
                    <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                        <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                            <div style="background-color: #004080; padding: 20px; text-align: center;">
                                <img src="' . base_url('assets/images/logo-sm.png') . '" alt="Logo" style="height: 60px;">
                            </div>
                            <div style="padding: 30px; color: #333;">
                                <h1 style="color: #004080;">¡Se ha registrado una Denuncia por Incumplimiento al codigo de Ética!</h1>
                                <p style="font-size: 16px;">Favor de <strong> Ingresar a SUSI</strong>.</p>
                                <p style="font-size: 15px;"><a href="' . base_url() . 'index.php/Principal/ListaDenuncia"><strong>Seguimiento Denuncia</strong></a></p>
                            </div>
                            <div style="background-color: #e0e0e0; text-align: center; padding: 15px; font-size: 13px; color: #666;">
                                © ' . date('Y') . ' Sistema de Atención SUSI. Todos los derechos reservados.
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

        return $this->response->setJSON($response);


    }
    public function formDenuncia()
    {
        $session = \Config\Services::session();
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        $dataInsert = [
            "nombre" => $data['nombre'],
            "domicilio" => $data['domicilio'],
            "correo" => $data['correo'],
            "telefono" => $data['telefono'],
            "donde_ocurrieron" => $data['donde_ocurrieron'],
            "cuando_ocurrieron" => $data['cuando_ocurrieron'],
            "testigo" => (int) $data['testigo'],
            "denunciando" => $data['denunciando'],
            "denunciando_text" => $data['denunciando_text'],
            "como_ocurrieron" => $data['como_ocurrieron'],
            "usu_reg" => $session->id_usuario,
            "fec_reg" => date('Y-m-d'),
        ];
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaDenuncia'];
        $dataConfig = [
            "tabla" => "denuncia",
            "editar" => false
            //"idEditar" => ['id_reserva' => (int)$id]
        ];
        $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
        if (!$response->error) {
            $this->enviarCorreoDenuncia();

        }
        return $this->respond($response);
    }
    public function formInventario()
    {
        $session = \Config\Services::session();
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        $file = $this->request->getFile('foto');

        if (isset($file) && !empty($file)) {
            $timestamp = date('Ymd_His');
            $extension = $file->getClientExtension();

            $archivo = $session->usuario . '_' . $timestamp . '.' . $extension;

            $ruta_destino = FCPATH . 'assets/images/fotos/';
            $file->move($ruta_destino, $archivo);

            $ruta_relativa = 'assets/images/fotos/' . $archivo;

        }

        $dataInsert = [
            "activo_fijo" => $data['activo_fijo'],
            "no_empleado" => $data['usuario'],
            "denominacion_activo_fijo" => $data['denominacion_activo_fijo'],
            "prefijo_activo_fijo" => $data['prefijo_activo_fijo'],
            "no_serie" => $data['no_serie'],
            "fabricante" => $data['fabricante'],
            "marca" => $data['marca'],
            "modelo" => $data['modelo'],
            "material" => $data['material'],
            "color" => $data['color'],
            "foto" => (isset($ruta_relativa) && !empty($ruta_relativa)) ? $ruta_relativa : '',
            "ubicacion" => $data['ubicacion'],
            "observaciones" => $data['observaciones'],
            "estado" => $data['estado'],
            "valor" => $data['valor'],
            "usu_reg" => $session->id_usuario,
            "fec_reg" => date('Y-m-d'),
        ];
        if (isset($data['fec_cap']) && !empty($data['fec_cap'])) {
            $dataInsert['fec_cap'] = date('Y-m-d', strtotime($data['fec_cap']));
        }
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaDenuncia'];
        $dataConfig = [
            "tabla" => "inventario",
            "editar" => ($data['editar'] == 0) ? false : true,
            "idEditar" => ['id_inventario' => (int) $data['id_inventario']]
        ];
        $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        return $this->respond($response);
    }
    public function getInventario()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $id_inventario = $this->request->getPost('id_inventario');
        $dataDB = array('tabla' => 'inventario', 'where' => ['visible' => 1, 'id_inventario' => $id_inventario]);
        $response = $globals->getTabla($dataDB);
        return $this->respond($response->data[0]);

    }
    private function registrarFolioGo($noConsecutivo, $responsableGasto, $direccionResponsable)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $idRegistro = 0;
        
        $user = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->id_usuario]]);

        //vemos si el usuario tiene id_area
        if (isset($user->data) && !empty($user->data)) {
           // $id_area = $user->data[0]->id_area;


            $dataInsert = [
                'no_consecutivo' => $noConsecutivo,
                'id_area' => $responsableGasto,
                'id_direccion' => $direccionResponsable,
                'fec_reg' => date('Y-m-d H:i:s'),
                'usu_reg' => $session->id_usuario,
            ];
            $dataConfig = [
                "tabla" => "folio_go",
                "editar" => false
            ];

            $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaFolio'];
            $resultado = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

            if (!$resultado->error) {
                // $idRegistro = $resultado->idRegistro; 
                // Si necesitas el ID del registro, úsalo. Si necesitas el consecutivo, retorna $nuevoConsecutivo
            }
             
             return $noConsecutivo;
        }

        return 0; // O manejar error si no tiene área
    }
    private function registrarFolio($noConsecutivo, $responsableGasto, $direccionResponsable)
    {
     

        $session = \Config\Services::session();
        $globals = new Mglobal;
        $idRegistro = 0;
        
        $user = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->id_usuario]]);

        //vemos si el usuario tiene id_area
        if (isset($user->data) && !empty($user->data)) {
           // $id_area = $user->data[0]->id_area;


            $dataInsert = [
                'no_consecutivo' => $noConsecutivo,
                'id_area' => $responsableGasto,
                'id_direccion' => $direccionResponsable,
                'fec_reg' => date('Y-m-d H:i:s'),
                'usu_reg' => $session->id_usuario,
            ];
            $dataConfig = [
                "tabla" => "folio_direccion",
                "editar" => false
            ];

            $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaFolio'];
            $resultado = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

            if (!$resultado->error) {
                // $idRegistro = $resultado->idRegistro; 
                // Si necesitas el ID del registro, úsalo. Si necesitas el consecutivo, retorna $nuevoConsecutivo
            }
             
             return $noConsecutivo;
        }

        return 0; // O manejar error si no tiene área

    }
    public function guardaPT()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error|Error al guardar PT";
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        $archivos_post = $this->request->getFiles();
        $finalAttachments = [];

        if ($data['secretario'] == 0) {
            $response->error = true;
            $response->respuesta = "Es requerido el Secretario o Director";
            return $this->respond($response);
        }

        if ($data['id_subsecretario'] == 0) {
            $response->error = true;
            $response->respuesta = "Es requerido el Secretario o Director";
            return $this->respond($response);
        }

        if (($data['direccion_responsable']) == 0) {
            $response->error = true;
            $response->respuesta = "Es requerido el Dirección Responsable";
            return $this->respond($response);
        }
        if (($data['id_reponsable_solicitud']) == 0) {
            $response->error = true;
            $response->respuesta = "Es requerido el Responsable de la Solicitud";
            return $this->respond($response);
        }
        if ($data['tipo_pt'] == 0) {
            $response->error = true;
            $response->respuesta = "Es requerido el Tipo pt";
            return $this->respond($response);
        }
        if (isset($data['cuenta_bancaria']) && empty($data['cuenta_bancaria'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el Cuenta Bancaria";
            return $this->respond($response);
        }
        if (isset($data['fecha_gasto_inicio']) && empty($data['fecha_gasto_inicio'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el fecha gasto inicio";
            return $this->respond($response);
        }
        if (isset($data['fecha_gasto_fin']) && empty($data['fecha_gasto_fin'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el fecha gasto fin";
            return $this->respond($response);
        }
        if (isset($data['documentacion_comprobatoria']) && empty($data['documentacion_comprobatoria'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el documentacion_comprobatorian";
            return $this->respond($response);
        }
        if (isset($data['poliza']) && empty($data['poliza'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el poliza";
            return $this->respond($response);
        }
        if (isset($data['formato_conformidad']) && empty($data['formato_conformidad'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el formato_conformidad";
            return $this->respond($response);
        }
        if (isset($data['concepto_pago']) && empty($data['concepto_pago'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el concepto_pago";
            return $this->respond($response);
        }
        if (isset($data['clausula_contrato']) && empty($data['clausula_contrato'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el clausula_contrato";
            return $this->respond($response);
        }
        if (isset($data['no_reserva']) && empty($data['no_reserva'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el no_reserva";
            return $this->respond($response);
        }

        if (isset($data['no_consecutivo']) && empty($data['no_consecutivo'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el no_consecutivo";
            return $this->respond($response);
        }

         if ($data['editar'] != 1) {
            $no_consecutivo = $this->registrarFolio($data['no_consecutivo'], $data['id_reponsable_solicitud'], $data['direccion_responsable']);
        }

          $dataInsert = [
            'id_reserva' => (int) $data['id_reserva'],
            'id_direccion_responsable' => $data['direccion_responsable'],
            'tipo_pt' => $data['tipo_pt'],
            'no_consecutivo' => $data['editar'] == 0 ? $no_consecutivo : $data['no_consecutivo'],
            'id_proveedor' => $data['id_proveedor'],
            'fecha_tramite' => $data['fecha_tramite'],
            'id_reponsable_solicitud' => (int) $data['id_reponsable_solicitud'],
            'director_general' => 1,
            'secretario' => $data['secretario'],
            'id_subsecretario' => $data['id_subsecretario'],
            'cuenta_bancaria' => $data['cuenta_bancaria'],
            'formato_establecido' => ($data['formato_establecido'] == 'SI') ? 1 : 2,
            'documentacion_comprobatoria' => $data['documentacion_comprobatoria'],
            'poliza' => ($data['poliza'] == 'SI') ? 1 : 2,
            'formato_conformidad' => ($data['formato_conformidad'] == 'SI') ? 1 : 2,
            'contrato_convenio' => $data['contrato_convenio'],
            'documentacion_requerida' => $data['documentacion_requerida'],
            'evidencia_entrega' => $data['evidencia_entrega'],
            'otros' => $data['otros'],
            'clausula_contrato' => $data['clausula_contrato'],
            'concepto_pago' =>'Sin Concepto',
            'comision' => $data['comision'],
            'dividido' => 0,
            'no_reserva' => $data['no_reserva']
        ];

          $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaPT'];
        
            $dataInsert['usu_reg'] = $session->get('id_usuario');
            $dataInsert['fec_reg'] = date('Y-m-d H:i:s');
            $dataConfig = [
                "tabla" => "registro_pt",
                "editar" => false
            ];
   
        
        $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
      
       
        $id_registro_pt = $response->idRegistro;
            $archivos_por_fila = [];

                foreach ($archivos_post['archivos'] as $key => $file_data) {
                    $parts = explode('_', $key);
                    
                    if (count($parts) >= 2) {
                        $type = $parts[0]; // 'pdf' o 'xml'
                        
                        // Reconstruir identificador completo
                        if (count($parts) === 2) {
                            // Formato simple: pdf_0
                            $row_id = $parts[1]; // '0'
                        } elseif ($parts[1] === 'tabla') {
                            // Formato tabla: pdf_tabla_0_1770688871443
                            $row_id = $parts[1] . '_' . $parts[2] . '_' . $parts[3];
                        } else {
                            // Otro formato, usar toda la clave después del tipo
                            $row_id = substr($key, strlen($type) + 1);
                        }
                        
                        if (!isset($archivos_por_fila[$row_id])) {
                            $archivos_por_fila[$row_id] = [];
                        }
                        
                        if (isset($file_data[$type][0])) {
                            $archivos_por_fila[$row_id][$type] = $file_data[$type][0];
                        }
                    }
                }

            $insertar_presupuesto = [];

            foreach ($data['id_presupuesto'] as $table_key => $value) {
                $table_data = [];
                $num_filas = count($data['concepto_' . $table_key] ?? []);
                
                for ($i = 0; $i < $num_filas; $i++) {
                    $fila_data = [
                        //'propina' => $data['propina_' . $table_key][$i] ?? '',
                        'concepto' => $data['concepto_' . $table_key][$i] ?? '',
                        //'comision' => $data['comision_' . $table_key][$i] ?? '',
                        'periodo_inicio' => $data['periodo_inicio_' . $table_key][$i] ?? '',
                        'periodo_fin' => $data['periodo_fin_' . $table_key][$i] ?? '',
                        'proyecto' => $data['proyecto'][$table_key] ?? '', 
                        'partida' => $data['partida'][$table_key] ?? '', 
                        'id_identificador' => $data['id_identificador_' . $table_key][$i] ?? '',
                        'id_presupuesto' => $value ?? '',
                        'archivos' => []
                    ];
                    
                    // Buscar archivos
                    $file_key = $fila_data['id_identificador'];
                    
                    if (isset($archivos_por_fila[$file_key])) {
                        $fila_data['archivos'] = $archivos_por_fila[$file_key];
                    }
                    
                    $table_data[] = $fila_data;
                }
                
                $insertar_presupuesto[$table_key] = $table_data;
            }



             foreach($insertar_presupuesto as $key => $tabla){
                foreach($tabla as $fila){

                    // 1. Insertar en periodo_factura
                    $dataPeriodo = [
                        'id_registro_pt' => $id_registro_pt,
                        'periodo_inicio' => $fila['periodo_inicio'],
                        'periodo_fin' => $fila['periodo_fin'],
                        'concepto' => $fila['concepto'] ?? '',
                        'id_proyecto' => $fila['proyecto'] ?? '',
                        'id_partida' => $fila['partida'] ?? '',
                        'id_identificador' => $fila['id_identificador'] ?? '', 
                        'id_presupuesto' => $fila['id_presupuesto'] ?? '', 
                        'usu_reg' => $session->id_usuario,
                        'fec_reg' => date('Y-m-d H:i:s'),
                        'visible' => 1
                    ];

                    $dataConfigPeriodo = ["tabla" => "periodo_factura", "editar" => false];
                    $periodoResult = $this->globals->saveTabla($dataPeriodo, $dataConfigPeriodo, ["script" => "Agregar.php/guardaPT/periodo"]);
                        //  var_dump($periodoResult); 
                    $id_periodo_factura = $periodoResult->idRegistro ?? 0;

                     foreach ($fila['archivos'] as $key => $archivo) {
                        if(!$archivo->isValid()) continue;

                        $tipo = $archivo->getMimeType();
                        $timestamp = date('Ymd_His');
                        $extension = $archivo->getClientExtension();
                        $originalName = pathinfo($archivo->getName(), PATHINFO_FILENAME);
                        $fileName = '03_CFDI_' . $id_registro_pt . '_' . $timestamp . '_' . uniqid() . '.' . $extension;
                        // Ruta absoluta
                        $ruta_destino = FCPATH . 'assets/pdf/';
                        if (!is_dir($ruta_destino)) {
                            mkdir($ruta_destino, 0755, true);
                        }
                        $archivo->move($ruta_destino, $fileName);
                        $finalAttachments[] = $ruta_destino . $fileName;
                        $ruta_relativa = 'assets/pdf/' . $fileName;
                        $ruta_absoluta = base_url($ruta_relativa);

                        if (in_array($tipo, ['text/pdf', 'application/pdf'])) {
                            $dataInsert = [
                                'id_registro_pt' =>  $id_registro_pt,
                                'id_identificador' => $fila['id_identificador'],
                               // 'nombre_archivo' => $originalName,
                                'ruta_absoluta' => $ruta_absoluta,
                                'ruta_relativa' => $ruta_relativa,
                                'fec_reg' => date('Y-m-d H:i:s'),
                                'usu_reg' => $session->id_usuario,
                                'visible' => 1
                            ];
                            $dataConfig = [
                                "tabla" => "factura_pdf",
                                "editar" => false
                            ];
                            $resPDF = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora); 
                             
                        } elseif (in_array($tipo, ['text/xml', 'application/xml'])) {
                           
                            $contenido = file_get_contents($ruta_destino . $fileName);
                            
                                // ... (Tu código de parseo de XML va aquí, es correcto) ...
                                libxml_use_internal_errors(true);
                                $xml = simplexml_load_string($contenido);
                                if ($xml === false) {
                                    continue;
                                } // Saltar si el XML está mal

                                $namespaces = $xml->getNamespaces(true);
                                $cfdi = $xml->children($namespaces['cfdi']);

                                // ... (extracción de $version, $fecha, $total, $rfcEmisor, $uuid, etc.)

                                $attrs = $xml->attributes();
                                $version = (string) $attrs['Version'];
                                $fecha = (string) $attrs['Fecha'];
                                $total = (string) $attrs['Total'];
                                $moneda = (string) $attrs['Moneda'];
                                $Folio = (string) $attrs['Folio'];

                                $emisor = $cfdi->Emisor->attributes();
                                $rfcEmisor = (string) $emisor['Rfc'];
                                $nombreEmisor = (string) $emisor['Nombre'];

                                $receptor = $cfdi->Receptor->attributes();
                                $rfcReceptor = (string) $receptor['Rfc'];
                                $nombreReceptor = (string) $receptor['Nombre'];

                                $uuid = '';
                                $NoCertificadoSAT = ''; // Renombrado para claridad
                                if (isset($cfdi->Complemento)) {
                                    $tfdNamespace = isset($namespaces['tfd']) ? $namespaces['tfd'] : 'http://www.sat.gob.mx/TimbreFiscalDigital';
                                    if (isset($cfdi->Complemento->children($tfdNamespace)->TimbreFiscalDigital)) {
                                        $tfdAttributes = $cfdi->Complemento->children($tfdNamespace)->TimbreFiscalDigital->attributes();
                                        $uuid = (string) $tfdAttributes['UUID'];
                                        $NoCertificadoSAT = (string) $tfdAttributes['NoCertificadoSAT'];
                                    }
                                }
                                // ... Fin del parseo ...

                                $dataConfigXml = [
                                    "tabla" => "factura",
                                    "editar" => false
                                ];
                                $dataInsertXml = [
                                    'id_registro_pt' => (int) $id_registro_pt, // Enlace al registro maestro
                                    'version' => $version,
                                    'fecha' => date('Y-m-d H:i:s', strtotime($fecha)),
                                    'total' => $total,
                                    'moneda' => $moneda,
                                    'id_identificador' => $fila['id_identificador'], // <-- CORREGIDO: EL ENLACE DE FILA
                                    'folio' => $Folio,
                                    'no_certificado' => $NoCertificadoSAT, // Usar el del timbre
                                    'emisor_rfc' => $rfcEmisor,
                                    'emisor_nombre' => $nombreEmisor,
                                    'receptor_rfc' => $rfcReceptor,
                                    'receptor_nombre' => $nombreReceptor,
                                    'uuid' => $uuid,
                                    'fec_reg' => date('Y-m-d H:i:s'),
                                    'usu_reg' => $session->get('id_usuario')
                                ];

                                $dataBitacoraXml = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarFacturaGO'];

                                // Guardamos la info del XML
                                $responseXML = $this->globals->saveTabla($dataInsertXml, $dataConfigXml, $dataBitacoraXml);
                                // var_dump($responseXML);
                                // Importante: Asignar la respuesta final solo si no hay error
                                if (!$responseXML->error) {
                                    $response->error = false;
                                    $response->respuesta = 'Archivos XML y PDF guardados correctamente';
                                }
                            //$this->globals->saveTabla($dataFactura, $dataConfig, $dataBitacora); 
                        
                        }
                   }
            }
        }

    
      if($session->get('id_usuario') != 1){
        // --- ENVIAR CORREO ---
            if (!empty($finalAttachments)) {
                $idDireccion = $data['direccion_responsable'];
                $direccionObj = $this->globals->getTabla(["tabla"=>"direccion", "where"=>["id_director"=>$idDireccion]]);
                $direccionStr = "";

                if(isset($direccionObj->data) && !empty($direccionObj->data)){
                    $direccionStr = isset($direccionObj->data[0]->folio_prefijo) ? $direccionObj->data[0]->folio_prefijo : (isset($direccionObj->data[0]->dscfolio_prefijo) ? $direccionObj->data[0]->dscfolio_prefijo : '');
                }
                
                $folioCompleto = $direccionStr . str_pad($no_consecutivo, 3, "0", STR_PAD_LEFT).'/'.date('Y');
                
                // Obtener correo del usuario para BCC
                $userObj = $this->globals->getTabla(["tabla"=>"vw_usuario", "where"=>["id_usuario"=>$session->id_usuario]]);
                $userEmail = (isset($userObj->data) && !empty($userObj->data)) ? $userObj->data[0]->correo : false;

                $mailer = new \App\Libraries\Mailer();
                
                $mensajeHTML = '
                <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                    <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                        <div style="background-color: #004080; padding: 20px; text-align: center;">
                            <h2 style="color: #ffffff; margin: 0;">Facturas PT Generadas</h2>
                        </div>
                        <div style="padding: 30px; color: #333;">
                            <p style="font-size: 16px;">Estimado usuario, </p>
                            <p style="font-size: 16px;">El personal administrativo '.$session->nombre_completo.' ha generado las facturas de <strong>Pago a Terceros (PT)</strong> en el sistema SUSI.</p>
                            <p style="font-size: 16px;"><strong>Folio: ' . $folioCompleto . '</strong></p>
                            <p style="font-size: 14px; color: #666;">Por favor, conserve estos comprobantes para su control administrativo.</p>
                            
                            <div style="margin-top: 25px; padding: 15px; background-color: #e3f2fd; border-left: 5px solid #2196f3; border-radius: 4px;">
                                <p style="margin: 0; font-size: 14px; color: #0d47a1;"><strong>Nota:</strong> Este es un mensaje automático, favor de no responder a esta dirección.</p>
                            </div>
                        </div>
                        <div style="background-color: #e0e0e0; text-align: center; padding: 15px; font-size: 12px; color: #666;">
                            © ' . date('Y') . ' Sistema de Atención SUSI. Todos los derechos reservados.
                        </div>
                    </div>
                </div>';

                $mailer->send(
                    $mensajeHTML, 
                    $session->get('id_usuario'), 
                ['dasedetur@guanajuato.gob.mx'], 
                2,
                    false, 
                    $finalAttachments, 
                    "Facturas PT Generadas - SUSI - Folio: " . $folioCompleto,
                    false, // header
                    false, // footer
                    false, // name
                    $userEmail // bcc
                );
            } 
      } 

          
       
        return $this->respond($response);


    
    }

    public function guardaPTEditar()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error|Error al guardar PT";
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        $archivos_post = $this->request->getFiles();

        // 1. Validaciones Básicas
         if ($data['secretario'] == 0) {
            $response->error = true;
            $response->respuesta = "Es requerido el Secretario o Director";
            return $this->respond($response);
        }

        // 2. Actualizar registro_pt
        // Asumimos que id_reserva es el ID del registro_pt que estamos editando
        $id_registro_pt = $data['id_registro_pt']; 
        
        $dataUpdatePT = [
            'id_direccion_responsable' => $data['direccion_responsable'],
            'tipo_pt' => $data['tipo_pt'],
            'no_consecutivo' => $data['no_consecutivo'],
            'id_proveedor' => $data['id_proveedor'],
            'fecha_tramite' => $data['fecha_tramite'],
            'id_reponsable_solicitud' => (int) $data['id_reponsable_solicitud'],
            'secretario' => $data['secretario'],
            'id_subsecretario' => $data['id_subsecretario'],
            'cuenta_bancaria' => $data['cuenta_bancaria'],
            'documentacion_comprobatoria' => $data['documentacion_comprobatoria'],
            'poliza' => ($data['poliza'] == 'SI') ? 1 : 2,
            'formato_conformidad' => ($data['formato_conformidad'] == 'SI') ? 1 : 2,
            'contrato_convenio' => $data['contrato_convenio'],
            'documentacion_requerida' => $data['documentacion_requerida'],
            'evidencia_entrega' => $data['evidencia_entrega'],
            'otros' => $data['otros'],
            'clausula_contrato' => $data['clausula_contrato'],
            'comision' => $data['comision'],
            'no_reserva' => $data['no_reserva'],
            'usu_act' => $session->get('id_usuario'),
            'fec_act' => date('Y-m-d H:i:s')
        ];

        
        $dataConfigPT = [
            "tabla" => "registro_pt",
            "editar" => true,
            "idEditar" => ['id_registro_pt' => $id_registro_pt] 
        ];
        
        $this->globals->saveTabla($dataUpdatePT, $dataConfigPT, ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaPTEditar']);


        // 3. Procesar Archivos (Parsing de claves complejas)
        $archivos_por_fila = [];
        if(isset($archivos_post['archivos'])){
            foreach ($archivos_post['archivos'] as $key => $file_data) {
                $parts = explode('_', $key);
                if (count($parts) >= 2) {
                    $type = $parts[0]; // pdf o xml
                    
                    // Lógica para extraer ID de fila igual que en guardaPT
                    if (count($parts) === 2) { 
                        $row_id = $parts[1]; 
                    } elseif ($parts[1] === 'tabla') { 
                        $row_id = $parts[1] . '_' . $parts[2] . '_' . $parts[3]; 
                    } else { 
                        $row_id = substr($key, strlen($type) + 1); 
                    }
                    
                    if (!isset($archivos_por_fila[$row_id])) { $archivos_por_fila[$row_id] = []; }
                    if (isset($file_data[$type][0])) { $archivos_por_fila[$row_id][$type] = $file_data[$type][0]; }
                }
            }
        }

        // 4. Procesar Filas (Presupuestos / Facturas)
        if(isset($data['id_presupuesto'])){
             foreach ($data['id_presupuesto'] as $table_key => $value) {
                // Iterar sobre los arrays de datos de cada tabla (presupuesto)
                $num_filas = count($data['concepto_' . $table_key] ?? []);
                
                for ($i = 0; $i < $num_filas; $i++) {
                    // ID Identificador para vincular archivos y buscar registro existente
                    $id_identificador = $data['id_identificador_' . $table_key][$i] ?? '';
                    
                    // Datos básicos de la fila
                    $fila_data = [
                        'id_registro_pt' => $id_registro_pt,
                        'periodo_inicio' => $data['periodo_inicio_' . $table_key][$i] ?? '',
                        'periodo_fin' => $data['periodo_fin_' . $table_key][$i] ?? '',
                        'concepto' => $data['concepto_' . $table_key][$i] ?? '',
                        //'id_proyecto' => $data['proyecto'][$table_key] ?? '', 
                        //'id_partida' => $data['partida'][$table_key] ?? '', 
                        'id_identificador' => $id_identificador
                       
                    ];
                  
                    // Determinar si existe la fila (UPDATE) o es nueva (INSERT)
                    // Usamos getTabla para buscar por id_identificador Y id_registro_pt para seguridad
                    $existe = $this->globals->getTabla([
                        'tabla' => 'periodo_factura',
                        'where' => ['id_identificador' => $id_identificador, 'id_registro_pt' => $id_registro_pt]
                    ]);
                    
                        // UPDATE
                         $id_periodo_factura_real = $existe->data[0]->id_periodo_factura;
                         
                         $dataConfigPeriodo = [
                            "tabla" => "periodo_factura", 
                            "editar" => true,
                            "idEditar" => ['id_periodo_factura' => $id_periodo_factura_real]
                        ];
                  
                    
                    $res = $this->globals->saveTabla($fila_data, $dataConfigPeriodo, ["script" => "Agregar.php/guardaPTEditar/periodo"]);
                 
                   

                    // 5. Procesar Archivos de la Fila
                    // Los archivos nuevos se insertan. No borramos los anteriores automáticamente.
                    if (isset($archivos_por_fila[$id_identificador])) {
                         foreach ($archivos_por_fila[$id_identificador] as $type => $archivo) {
                            if(!$archivo->isValid()) continue;

                            $timestamp = date('Ymd_His');
                            $extension = $archivo->getClientExtension();
                            // Generar nombre archivo
                            $fileName = '03_CFDI_' . $id_registro_pt . '_' . $timestamp . '_' . uniqid() . '.' . $extension;
                            
                            $ruta_destino = FCPATH . 'assets/pdf/';
                            if (!is_dir($ruta_destino)) { mkdir($ruta_destino, 0755, true); }
                            
                            $archivo->move($ruta_destino, $fileName);
                            $ruta_relativa = 'assets/pdf/' . $fileName;
                            $ruta_absoluta = base_url($ruta_relativa);

                            if ($type === 'pdf') {
                                // Guardar PDF
                                $dataInsertPDF = [
                                    'id_registro_pt' =>  $id_registro_pt,
                                    'id_identificador' => $id_identificador,
                                    'ruta_absoluta' => $ruta_absoluta,
                                    'ruta_relativa' => $ruta_relativa,
                                    
                                ];
                            $existePDF = $this->globals->getTabla([
                                'tabla' => 'factura_pdf',
                                'where' => ['id_identificador' => $id_identificador, 'id_registro_pt' => $id_registro_pt]
                            ]);
                            
                            $dataConfigPdf = ["tabla" => "factura_pdf", "editar" => false];

                            if (isset($existePDF->data) && !empty($existePDF->data)) { // UPDATE
                                $id_factura_pdf_real = $existePDF->data[0]->id_factura_pdf;
                                $dataConfigPdf = [
                                    "tabla" => "factura_pdf", 
                                    "editar" => true, 
                                    "idEditar" => ['id_factura_pdf' => $id_factura_pdf_real]
                                ];

                                // Mantener fecha registro original si se desea, o actualizar
                                unset($dataInsertPDF['usu_reg']);
                                unset($dataInsertPDF['fec_reg']);
                             

                            } 
                            // ELSE (Insertar) se maneja por defecto con dataConfigPdf edit=false

                            $this->globals->saveTabla($dataInsertPDF, $dataConfigPdf, []);
                            } elseif ($type === 'xml') {
                                // Procesar XML
                                $contenido = file_get_contents($ruta_destino . $fileName);
                                libxml_use_internal_errors(true);
                                $xml = simplexml_load_string($contenido);
                                
                                if ($xml !== false) {
                                    $namespaces = $xml->getNamespaces(true);
                                    $cfdi = $xml->children($namespaces['cfdi']);
                                    $attrs = $xml->attributes();
                                    $tfdNamespace = isset($namespaces['tfd']) ? $namespaces['tfd'] : 'http://www.sat.gob.mx/TimbreFiscalDigital';
                                    
                                    $uuid = '';
                                    if (isset($cfdi->Complemento) && isset($cfdi->Complemento->children($tfdNamespace)->TimbreFiscalDigital)) {
                                        $uuid = (string) $cfdi->Complemento->children($tfdNamespace)->TimbreFiscalDigital->attributes()['UUID'];
                                    }
                                    
                                    // Datos XML
                                    $fol = isset($attrs['Folio']) ? (string)$attrs['Folio'] : '';

                                     // Emisor y Receptor para verificar
                                    $emisor = $cfdi->Emisor->attributes();
                                    $rfcEmisor = (string) $emisor['Rfc'];
                                    $nombreEmisor = (string) $emisor['Nombre'];

                                    $receptor = $cfdi->Receptor->attributes();
                                    $rfcReceptor = (string) $receptor['Rfc'];
                                    $nombreReceptor = (string) $receptor['Nombre'];
                                    
                                    $dataInsertXML = [
                                        'id_registro_pt' => $id_registro_pt,
                                        'version' => (string) $attrs['Version'],
                                        'fecha' => date('Y-m-d H:i:s', strtotime((string) $attrs['Fecha'])),
                                        'total' => (string) $attrs['Total'],
                                        'moneda' => (string) $attrs['Moneda'],
                                        'id_identificador' => $id_identificador,
                                        'folio' => $fol,
                                        'uuid' => $uuid,
                                        'emisor_rfc' => $rfcEmisor,
                                        'emisor_nombre' => $nombreEmisor,
                                        'receptor_rfc' => $rfcReceptor,
                                        'receptor_nombre' => $nombreReceptor,
                                     
                                    ];
                                    $existeXML = $this->globals->getTabla([
                                        'tabla' => 'factura',
                                        'where' => ['id_identificador' => $id_identificador, 'id_registro_pt' => $id_registro_pt]
                                    ]);

                                    $dataConfigXml = ["tabla" => "factura", "editar" => false];
                                    
                                    if (isset($existeXML->data) && !empty($existeXML->data)) {
                                         // Update existing XML record
                                         $dataConfigXml = [
                                            "tabla" => "factura", 
                                            "editar" => true, 
                                            "idEditar" => ['id_factura' => $existeXML->data[0]->id_factura]
                                        ];
                                     
                                    } 
                                    
                                    $this->globals->saveTabla($dataInsertXML, $dataConfigXml, []);
                                }
                            }
                         }
                    } // Fin procesamiento archivos de fila
                } // Fin for filas
             } // Fin foreach tablas
        }
       // die();
        $response->error = false;
        $response->respuesta = "Éxito|Se ha actualizado correctamente.";
        $response->idRegistro = $id_registro_pt;
        return $this->respond($response);
    }
    public function guardaVe()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error|Error al guardar PT";
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        $archivos = $this->request->getFiles();

        // Variables para almacenar datos del XML
        $total = 0;
        $uuid = '';
        $Folio = '';
        $rfcEmisor = '';
        $nombreReceptor = '';
        $retencionesFederales = 0;
        $retencionesLocales = 0;
        $ruta_relativa = '';

        // Validaciones...
        if (empty($data['id_proveedor'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el proveedor";
            return $this->respond($response);
        }
        if (isset($data['id_proveedor_banco']) && empty($data['id_proveedor_banco'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el Cuenta Bancaria";
            return $this->respond($response);
        }
        if (isset($data['proyecto']) && empty($data['proyecto'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el proyecto";
            return $this->respond($response);
        }

        if (($data['direccion_responsable']) == 0) {
            $response->error = true;
            $response->respuesta = "Es requerido el Dirección Responsable";
            return $this->respond($response);
        }
        if ($data['id_secretario'] == 0) {
            $response->error = true;
            $response->respuesta = "Es requerido el id_secretario ";
            return $this->respond($response);
        }

        if (($data['id_reponsable_solicitud']) == 0) {
            $response->error = true;
            $response->respuesta = "Es requerido el Responsable de la Solicitud";
            return $this->respond($response);
        }

        if (isset($data['fecha_inicio']) && empty($data['fecha_inicio'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el fecha gasto inicio";
            return $this->respond($response);
        }
        if (isset($data['fecha_fin']) && empty($data['fecha_fin'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el fecha gasto fin";
            return $this->respond($response);
        }

        if (isset($data['concepto_pago']) && empty($data['concepto_pago'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el concepto_pago";
            return $this->respond($response);
        }
        if (isset($data['id_responsable_gasto']) && empty($data['id_responsable_gasto'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el id_responsable_gasto";
            return $this->respond($response);
        }
        if (isset($data['convenio']) && empty($data['convenio'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el convenio";
            return $this->respond($response);
        }
        if ($data['editar'] == 0) {
            if (empty($archivos)) {
                $response->error = true;
                $response->respuesta = "Es requerido los archivos XML and PDF";
                return $this->respond($response);
            }
        }

        // Procesamiento de archivos
        if (is_array($archivos) && !empty($archivos)) {
            foreach ($archivos as $key => $archivo) {
                $tipo = $archivo->getMimeType();

                if (in_array($tipo, ['text/pdf', 'application/pdf'])) {
                    $timestamp = date('Ymd_His');
                    $extension = $archivo->getClientExtension();
                    $originalName = pathinfo($archivo->getName(), PATHINFO_FILENAME);
                    $file = '03_CFDI_' . $key . '_' . $timestamp . '.' . $extension;
                    // Ruta absoluta
                    $ruta_destino = FCPATH . 'assets/pdf/';
                    $archivo->move($ruta_destino, $file);
                    $ruta_relativa = 'assets/pdf/' . $file;
                }

                if (in_array($tipo, ['text/xml', 'application/xml'])) {
                    $contenido = file_get_contents($archivo->getTempName());

                    libxml_use_internal_errors(true);
                    $xml = simplexml_load_string($contenido);

                    if ($xml === false) {
                        $response->error = true;
                        $response->respuesta = "Error al procesar el archivo XML";
                        return $this->respond($response);
                    }

                    $namespaces = $xml->getNamespaces(true);
                    $cfdi = $xml->children($namespaces['cfdi']);

                    $attrs = $xml->attributes();
                    $version = (string) $attrs['Version'];
                    $fecha = (string) $attrs['Fecha'];
                    $total = (string) $attrs['Total'];
                    $moneda = (string) $attrs['Moneda'];
                    $Serie = (string) $attrs['Serie'];
                    $Folio = (string) $attrs['Folio'];
                    $FormaPago = (string) $attrs['FormaPago'];
                    $CondicionesDePago = (string) $attrs['CondicionesDePago'];
                    $SubTotal = (float) $attrs['SubTotal'];
                    $Descuento = isset($attrs['Descuento']) ? (float) $attrs['Descuento'] : 0;
                    $TipoCambio = isset($attrs['TipoCambio']) ? (float) $attrs['TipoCambio'] : 1;

                    $Certificado = (string) $attrs['Certificado'];
                    $NoCertificado = (string) $attrs['NoCertificado'];

                    // ✅ Emisor
                    $emisor = $cfdi->Emisor->attributes();
                    $rfcEmisor = (string) $emisor['Rfc'];
                    $nombreEmisor = (string) $emisor['Nombre'];

                    // ✅ Receptor
                    $receptor = $cfdi->Receptor->attributes();
                    $rfcReceptor = (string) $receptor['Rfc'];
                    $nombreReceptor = (string) $receptor['Nombre'];

                    // ✅ UUID
                    $uuid = '';
                    $NoCertificadoSAT = '';

                    // Verificar si existe el complemento
                    if (isset($cfdi->Complemento)) {
                        // Obtener el namespace correcto para el timbre fiscal
                        $tfdNamespace = isset($namespaces['tfd']) ? $namespaces['tfd'] : 'http://www.sat.gob.mx/TimbreFiscalDigital';

                        $complemento = $cfdi->Complemento->children($tfdNamespace);

                        // Verificar si existe el TimbreFiscalDigital
                        if (isset($complemento->TimbreFiscalDigital)) {
                            $tfdAttributes = $complemento->TimbreFiscalDigital->attributes();
                            $uuid = (string) $tfdAttributes['UUID'];
                            $NoCertificadoSAT = (string) $tfdAttributes['NoCertificadoSAT'];
                        }
                    }

                    // ✅ OBTENER RETENCIONES FEDERALES
                    $retencionesFederales = 0;
                    if (isset($cfdi->Impuestos)) {
                        $impuestosAttrs = $cfdi->Impuestos->attributes();
                        $retencionesFederales = (float) ($impuestosAttrs['TotalImpuestosRetenidos'] ?? 0);
                    }

                    // ✅ OBTENER RETENCIONES LOCALES
                    $retencionesLocales = 0;
                    if (isset($namespaces['implocal']) && isset($cfdi->Complemento)) {
                        $implocal = $cfdi->Complemento->children($namespaces['implocal']);

                        if (isset($implocal->ImpuestosLocales)) {
                            $impLocalesAttrs = $implocal->ImpuestosLocales->attributes();
                            $retencionesLocales = (float) ($impLocalesAttrs['TotaldeRetenciones'] ?? 0);
                        }
                    }

                    // ✅ OBTENER IVA (Traslados)
                    $iva = 0;
                    if (isset($cfdi->Impuestos)) {
                        $impuestosAttrs = $cfdi->Impuestos->attributes();
                        $iva = (float) ($impuestosAttrs['TotalImpuestosTrasladados'] ?? 0);
                    }

                    // ✅ OBTENER SUBTOTAL
                    $subtotal = (float) ($attrs['SubTotal'] ?? 0);

                    // ✅ OBTENER DESCUENTO
                    $descuento = isset($attrs['Descuento']) ? (float) $attrs['Descuento'] : 0;
                }
            }
        }

        // Preparar datos para insertar
        $dataInsert = [
            'id_direccion_responsable' => $data['direccion_responsable'],
            'id_proveedor' => $data['id_proveedor'],
            'fecha_tramite' => $data['fecha_tramite'],
            'id_responsable' => (int) $data['id_reponsable_solicitud'],
            'id_director' => 1,
            'id_secretario' => $data['id_secretario'],
            'id_responsable_gasto' => $data['id_responsable_gasto'],
            'id_proveedor_banco' => $data['id_proveedor_banco'],
            'fec_inicio' => $data['fecha_inicio'],
            'fec_fin' => $data['fecha_fin'],
            'concepto' => $data['concepto_gasto'],
            'comision' => $data['comision'],
            'id_proyecto' => $data['proyecto'],
            'no_consecutivo' => $data['no_consecutivo'],
            'convenio' => $data['convenio'],
            'otros' => $data['otros'],
            'folio' => $data['folio'],
            'formatos' => $data['formatos'],
            'documentacion' => $data['documentacion'],
            'poliza' => $data['poliza'],
            'conformidad' => $data['conformidad'],
            'contrato_convenio' => $data['contrato_convenio'],
            'emitir_pago' => $data['emitir_pago'],
            'evidencia' => $data['evidencia'],
        ];

        // Agregar datos del XML si existen
        if (isset($ruta_relativa) && !empty($ruta_relativa)) {
            $dataInsert['pdf'] = $ruta_relativa;
            $dataInsert['xml_monto'] = $total;
            $dataInsert['xml_uuid'] = (!empty($Folio)) ? $Folio : $uuid;
            $dataInsert['xml_rfc'] = $rfcEmisor;
            $dataInsert['xml_razon_social'] = $nombreReceptor;
            $dataInsert['xml_subtotal'] = $subtotal ?? 0;
            //$dataInsert['xml_iva']          = $iva ?? 0;
            //  $dataInsert['xml_descuento']    = $descuento ?? 0;
            $dataInsert['xml_retenciones_federales'] = $retencionesFederales;
            $dataInsert['xml_retenciones_locales'] = $retencionesLocales;
            //$dataInsert['xml_total_retenciones']     = $retencionesFederales + $retencionesLocales;
            //$dataInsert['xml_moneda']       = $moneda ?? 'MXN';
            //$dataInsert['xml_fecha']        = $fecha ?? date('Y-m-d H:i:s');
        }

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaPTVehiculos'];

        if ($data['editar'] == 0) {
            $dataInsert['usu_reg'] = $session->get('id_usuario');
            $dataInsert['fec_reg'] = date('Y-m-d H:i:s');
            $dataConfig = [
                "tabla" => "pt_vehiculo",
                "editar" => false
            ];
        } else {
            $dataConfig = [
                "tabla" => "pt_vehiculo",
                "editar" => true,
                'idEditar' => ['id_vehiculo' => $data['id_vehiculo']]
            ];
            $dataInsert['usu_act'] = $session->get('id_usuario');
        }

        $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        return $this->respond($response);
    }
    public function guardaPT2()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error|Error al guardar PT";
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        $archivos = $this->request->getFiles();



        if (isset($data['cuenta_bancaria']) && empty($data['cuenta_bancaria'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el Cuenta Bancaria";
            return $this->respond($response);
        }
        if (isset($data['fecha_gasto_inicio']) && empty($data['fecha_gasto_inicio'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el fecha gasto inicio";
            return $this->respond($response);
        }
        if (isset($data['fecha_gasto_fin']) && empty($data['fecha_gasto_fin'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el fecha gasto fin";
            return $this->respond($response);
        }
        if (isset($data['documentacion_comprobatoria']) && empty($data['documentacion_comprobatoria'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el documentacion_comprobatorian";
            return $this->respond($response);
        }


        $registro_pt = $this->globals->getTabla([
            'tabla' => 'registro_pt',
            'where' => ['visible' => 1, 'id_registro_pt' => $data['id_registro_pt']]
        ]);

        if (isset($registro_pt->data) && !empty($registro_pt->data)) {
            $datos = $registro_pt->data[0];
        }

        $this->registrarFolio($data['no_consecutivo'], $datos->id_reponsable_solicitud);
        //die( var_dump( $noConsecutivo ) );

        $dataInsert = [
            'id_reserva' => (int) $datos->id_reserva,
            'id_direccion_responsable' => $datos->id_direccion_responsable,
            'tipo_pt' => $datos->tipo_pt,
            'no_consecutivo' => $data['no_consecutivo'],
            'id_proveedor' => $datos->id_proveedor,
            'fecha_tramite' => date('Y-m-d', strtotime($data['fecha_tramite'])),
            'id_reponsable_solicitud' => (int) $datos->id_reponsable_solicitud,
            'director_general' => $datos->director_general,
            'secretario' => $datos->secretario,
            'id_subsecretario' => $datos->id_subsecretario,
            'cuenta_bancaria' => $data['cuenta_bancaria'],
            // 'total_importe' => $data['total_importe'],
            // 'fecha_gasto_inicio' => date('Y-m-d', strtotime($data['fecha_gasto_inicio'])),
            //'fecha_gasto_fin' => date('Y-m-d', strtotime($data['fecha_gasto_fin'])),
            'formato_establecido' => $datos->formato_establecido,
            'documentacion_comprobatoria' => $datos->documentacion_comprobatoria,
            'poliza' => $datos->poliza,
            'formato_conformidad' => $datos->formato_conformidad,
            'contrato_convenio' => $datos->contrato_convenio,
            'documentacion_requerida' => $datos->documentacion_requerida,
            'evidencia_entrega' => $datos->evidencia_entrega,
            'otros' => $data['otros'],
            'clausula_contrato' => $datos->clausula_contrato,
            'concepto_pago' => $data['concepto_pago'],
            'comision' => $data['comision'],
            'no_reserva' => $datos->no_reserva,
            'usu_reg' => $session->get('id_usuario'),
            'fec_reg' => date('Y-m-d H:i:s'),
        ];
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaReserva'];
        $dataConfig = [
            "tabla" => "registro_pt",
            "editar" => false
        ];

        $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        if (!$response->error) {
            $id_registro_pt = $response->idRegistro;
            $archivosXml = [];
            $archivosPdf = [];
            $periodo = [];
            $response->idRegistro = $response->idRegistro;


            foreach ($data as $key => $p) {
                if (strpos($key, 'encabezado') === 0) {
                    $index = str_replace('encabezado', '', $key);
                    $index = $index === '' ? 0 : $index;
                    if (!isset($periodo[$index])) {
                        $periodo[$index] = [];
                    }
                    $periodo[$index]['encabezado'] = $p;
                }

                if (strpos($key, 'partida') === 0) {
                    $index = str_replace('partida', '', $key);
                    $index = $index === '' ? 0 : $index;
                    if (!isset($periodo[$index])) {
                        $periodo[$index] = [];
                    }
                    $periodo[$index]['partida'] = $p;
                }
                if (strpos($key, 'proyecto') === 0) {
                    $index = str_replace('proyecto', '', $key);
                    $index = $index === '' ? 0 : $index;
                    if (!isset($periodo[$index])) {
                        $periodo[$index] = [];
                    }
                    $periodo[$index]['proyecto'] = $p;
                }
                if (strpos($key, 'fecha_gasto_inicio') === 0) {
                    $index = str_replace('fecha_gasto_inicio', '', $key);
                    $index = $index === '' ? 0 : $index;
                    if (!isset($periodo[$index])) {
                        $periodo[$index] = [];
                    }
                    $periodo[$index]['periodo_inicio'] = $p;
                }
                if (strpos($key, 'fecha_gasto_fin') === 0) {
                    $index = str_replace('fecha_gasto_fin', '', $key);
                    $index = $index === '' ? 0 : $index;
                    if (!isset($periodo[$index])) {
                        $periodo[$index] = [];
                    }
                    $periodo[$index]['periodo_fin'] = $p;
                }
            }

            // Recorremos todas las claves de los archivos enviados
            foreach ($archivos as $key => $fileArray) {
                if (strpos($key, 'factura_xml_') === 0) {
                    $archivosXml = array_merge($archivosXml, $fileArray);
                } elseif (strpos($key, 'factura_pdf_') === 0) {
                    $archivosPdf = array_merge($archivosPdf, $fileArray);
                }
            }



            $datosXML = $this->procesarXML($archivosXml, $id_registro_pt);
            $datosPDF = $this->procesarPDF($archivosPdf, $id_registro_pt);
            $datosP = $this->procesarPediodo($periodo, $id_registro_pt);


            if (!$datosXML) {
                $response->errorXML = true;
                $response->respuestaXML = "XML inválido o no se encontró.";
            }
            if (!$datosPDF) {
                $response->errorPDF = true;
                $response->respuestaPDF = "PDF inválido o no se encontró.";
            }

        }
        return $this->respond($response);
    }
    public function guardaUsuarioSti()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        // $response->error = true;
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        $file = $this->request->getFile('foto');


        if ($data['editar'] != 1) {

            if (empty($data['contrasenia']) || empty($data['confirmar_contrasenia'])) {
                throw new Exception("Los campos de contraseña son obligatorios");
            }

            if ($data['contrasenia'] != $data['confirmar_contrasenia']) {
                throw new Exception("Las contraseñas no son identicas");
            }
        }

        if (empty($data['usuario'])) {
            throw new Exception("El campo de <strong>usuario</strong> es requerido");
        }
        if (empty($data['id_jefe_inmediato'])) {
            throw new Exception("El campo de <strong>Jefe inmediato es requerido</strong> es requerido");
        }
        if ($data['id_sexo'] == 0) {
            throw new Exception("El campo sexo es requerido");
        }

        if ($data['id_perfil'] == 0) {
            throw new Exception("El campo perfil es requerido");
        }

        if ($data['id_area'] == 0) {
            throw new Exception("El campo área es requerido");
        }
        if (empty($data['correo'])) {
            throw new Exception("El campo correo es requerido");
        }
        if (empty($data['fec_nac'])) {
            throw new Exception("El campo fecha de nacimiento es requerido");
        }
        if (
            empty($data['nombre']) ||
            empty($data['primer_apellido'])
        ) {
            throw new Exception("Algunos campos son requeridos");
        }
        if ($data['editar'] != 1) {
            $curp = $this->globals->getTabla(['tabla' => 'usuario', 'where' => ['rfc' => $data['rfc'], 'visible' => 1]]);
            if (!empty($curp->data)) {
                throw new Exception("El campo de <strong>CURP</strong> ya existe en la base de datos");
            }
            $existente = $this->globals->getTabla(['tabla' => 'usuario', 'where' => ['usuario' => $data['usuario'], 'visible' => 1]]);
            if (!empty($existente->data)) {
                throw new Exception("El <strong> usuario</strong> ya existe en la base de datos, favor de cambiar los datos");
            }
        }
        if (isset($file) && !empty($file) && $file->getSize() > 0) {
            $timestamp = date('Ymd_His');
            $extension = $file->getClientExtension();
            $originalName = pathinfo($file->getName(), PATHINFO_FILENAME);
            $archivo = $data['usuario'] . '_' . $timestamp . '.' . $extension;
            $ruta_destino = FCPATH . 'assets/images/fotos/';
            $file->move($ruta_destino, $archivo);
            $ruta_relativa = 'assets/images/fotos/' . $archivo;

        }



        $hoy = date("Y-m-d H:i:s");


        $dataInsert = [
            'id_sexo' => (int) $data['id_sexo'],
            'id_jefe_inmediato' => (int) $data['id_jefe_inmediato'],
            'id_tipo_empleado' => (int) $data['id_tipo_empleado'],
            'id_puesto' => (int) $data['id_puesto'],
            'id_perfil' => (int) $data['id_perfil'],
            'usuario' => $data['usuario'],
            'nombre' => $data['nombre'],
            'primer_apellido' => $data['primer_apellido'],
            'segundo_apellido' => $data['segundo_apellido'],
            'correo' => $data['correo'],
            'rfc' => $data['rfc'],
            'id_area' => (int) $data['id_area'],
            'fec_reg' => $hoy
        ];
        if (isset($ruta_relativa) && !empty($ruta_relativa) && $file->getSize() > 0) {
            $dataInsert['ruta_foto_relativa'] = $ruta_relativa;
        }


        $fecha_nacimiento = $data['fec_nac'];

        // Verificar si la fecha es válida
        if (!empty($fecha_nacimiento)) {
            // Convertir a formato YYYY-MM-DD si es necesario
            $fecha_formateada = date('Y-m-d H:i:s', strtotime($fecha_nacimiento));

            $dataInsert['fec_nac'] = $fecha_formateada;
        } else {
            $dataInsert['fec_nac'] = null;
        }


        if (isset($data['contrasenia']) && !empty($data['contrasenia'])) {
            $dataInsert['contrasenia'] = $this->hashContrasenia($data['contrasenia']);
        }
        if (isset($data['no_empleado']) && !empty($data['no_empleado'])) {
            $dataInsert['no_empleado'] = $data['no_empleado'];
        }
        if (isset($data['nivel']) && !empty($data['nivel'])) {
            $dataInsert['nivel'] = $data['nivel'];
        }
        if (isset($data['extencion']) && !empty($data['extencion'])) {
            $dataInsert['extencion'] = $data['extencion'];
        }

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaTurno'];


        $dataConfig = [
            "tabla" => "usuario",
            "editar" => ($data['editar'] == 1) ? true : false,
            "idEditar" => ['id_usuario' => $data['id_usuario']]
        ];

        $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        return $this->respond($response);
    }

    private function handleException($e)
    {
        log_message('error', "Se produjo una excepción: " . $e->getMessage());
    }
    public function Directorio()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = array();
        $tabla = array('tabla' => 'vw_usuario', 'where' => ['visible' => 1], 'orderBy' => 'nombre_completo ASC');
        $usuario = $globals->getTabla($tabla);
        $data['scripts'] = array('inicio');
        $data['usuario'] = isset($usuario->data) && !empty($usuario->data) ? $usuario->data : [];
        $data['contentView'] = 'personal/vDirectorio';
        $this->_renderView($data);
    }
    public function Ganadores()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = array();
        $tabla = array('tabla' => 'vw_honestidad', 'where' => ['visible' => 1]);

        $usuario = $globals->getTabla($tabla);
        $data['scripts'] = array('inicio');
        $data['usuario'] = isset($usuario->data) && !empty($usuario->data) ? $usuario->data : [];
        $data['contentView'] = 'personal/vGanadores';
        $this->_renderView($data);
    }
    function validarCampo($valor, $nombreCampo)
    {
        // $pattern = "/^([a-zA-Z 0-9]+)$/";
        $pattern = "/^([a-zA-ZáéíóúüñÁÉÍÓÚÜÑ 0-9]+)$/";

        if (!preg_match($pattern, $valor)) {
            throw new Exception("Error en el campo '$nombreCampo': Por favor, utilice únicamente caracteres alfanuméricos (letras y números). Gracias.");
        }

        return $valor;
    }
    public function Documentos()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vDocumentos';
        $this->_renderView($data);
    }
    public function cambioPassword()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $Mglobal = new Mglobal;
        $response->error = true;
        $response->respuesta = 'Error| Error al Generar la consulta';
        $data = $this->request->getPost();
        if (!isset($data['id_usuario']) || empty($data['id_usuario'])) {
            $response->respuesta = "No se ha proporcionado un identificador válido";
            return $this->respond($response);
        }
        $usuario = $Mglobal->getTabla(["tabla" => "usuario", "where" => ["id_usuario" => $data['id_usuario'], "visible" => 1]])->data[0];
        if ($this->validarContrasenia($data['contrasenia'], $usuario->contrasenia)) {
            $response->error = true;
            $response->respuesta = 'La contraseña no puede ser la misma que ya esta registrada';
            return $this->respond($response);

        }
        $dataInsert = [
            'cambio_pass' => 1,
            'contrasenia' => $this->hashContrasenia($data['contrasenia'])
        ];
        $dataConfig = [
            "tabla" => "usuario",
            "editar" => true,
            "idEditar" => ['id_usuario' => $data['id_usuario']]
        ];
        $result = $Mglobal->saveTabla($dataInsert, $dataConfig, ["script" => "Usuario.deleteUsuario"]);
        if (!$result->error) {
            $response->error = $result->error;
            $response->respuesta = $result->respuesta;

        }
        return $this->respond($response);


    }
    public function guardaTurno()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        // $response->error = true;
        $agregar = new Magregarturno();
        $data = $this->request->getPost();

        $currentDateTime = new \DateTime();
        $formattedDate = $currentDateTime->format('Y-m-d H:i:s');
        $fecha_peticion = $currentDateTime::createFromFormat('d/m/Y', $data['fecha_peticion'])->format('Y-m-d');
        $fecha_recepcion = $currentDateTime::createFromFormat('d/m/Y', $data['fecha_recepcion'])->format('Y-m-d');

        $anioActual = date("Y");
        $dataInsert = [
            'anio' => $anioActual,
            'id_asunto' => $data['asunto'],
            'fecha_peticion' => $fecha_peticion,
            'fecha_recepcion' => $fecha_recepcion,
            'solicitante_titulo' => $data['titulo_inv'],
            'solicitante_nombre' => $data['nombre_t'],
            'solicitante_primer_apellido' => $data['primer_apellido'],
            'solicitante_segundo_apellido' => $data['segundo_apellido'],
            'solicitante_cargo' => $data['cargo_inv'],
            'solicitante_razon_social' => $data['razon_social_inv'],
            'resumen' => $this->validarCampo($data['resumen'], "resumen"),
            'id_estatus' => $data['status'],
            'observaciones' => $data['observaciones'],
            'id_resultado_turno' => $data['id_resultado_turno'],
            'resultado_turno' => $data['resultado_turno'],
            'firma_turno' => $data['firma_turno'],
            'usuario_registro' => $session->id_usuario,
            'fecha_registro' => $formattedDate,
            'id_destinatario' => isset($data['nombre_turno']) ? $data['nombre_turno'] : array(),
            'id_destinatario_copia' => isset($data['cpp']) ? $data['cpp'] : array(),
            'id_indicacion' => isset($data['indicacion']) ? $data['indicacion'] : array(),
        ];
        /*  var_dump($dataInsert);
         die(); */
        $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Agregar.php/guardaTurno'];


        try {
            $respuesta = $agregar->guardarTurnoNuevo($dataInsert, $dataBitacora);
            $response->respuesta = $respuesta;
            return $this->respond($response);
        } catch (\Exception $e) {
            $this->handleException($e);

            $response->error = $e->getMessage();
            return $this->respond($response);
        }
    }
    
    public function uploadCSV()
    {
        $response = new \stdClass();

        // Verificar si el archivo se recibió correctamente
        if ($file = $this->request->getFile('fileinput')) {
            if ($file->getClientMimeType() !== 'text/csv' && strtolower($file->getExtension()) !== 'csv') {
                $response->error = true;
                $response->respuesta = 'El archivo debe ser de formato CSV.';
                return $this->respond($response);
            }
            $id_categoria = $this->request->getPost('id_categoria');

            if ($file->isValid() && !$file->hasMoved()) {
                // Asignar un nombre aleatorio y mover el archivo a la carpeta de uploads

                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads', $newName);
                $filePath = WRITEPATH . 'uploads/' . $newName;

                // Procesar el archivo CSV y enviar los datos a Node.js
                $processResponse = $this->processCSVAndSend($filePath, $id_categoria);
                // Eliminar el archivo CSV después de procesarlo
                // Configurar la respuesta en función del resultado de `processCSVAndSend`
                if ($processResponse->error) {
                    $response->error = true;
                    $response->respuesta = 'Error al procesar el CSV';

                } else {
                    $response->error = false;
                    $response->respuesta = 'Archivo procesado correctamente';
                    //$response->data = $processResponse->data;
                }
            } else {
                $response->error = true;
                $response->respuesta = 'Error en la subida del archivo.';
            }
        } else {
            $response->error = true;
            $response->message = 'Archivo no recibido.';
        }
        return $this->respond($response);
        //return $this->response->setJSON($response);
    }
    public function processCSVAndSend($filePath, $id_categoria)
    {
        $response = new \stdClass();
        $data = [];

        if (($handle = fopen($filePath, "r")) !== false) {
            $header = fgetcsv($handle, 1000, ","); // Lee la primera fila como encabezado

            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                $encodedRow = array_map('utf8_encode', $row); // Codifica los valores a UTF-8
                $courseData = array_combine($header, $encodedRow); // Combina encabezado y valores

                // Convertir las fechas al formato `yyyy-mm-dd`
                // $courseData['startdate'] = date('Y-m-d', strtotime(str_replace('/', '-', $courseData['startdate'])));
                // $courseData['enddate'] = date('Y-m-d', strtotime(str_replace('/', '-', $courseData['enddate'])));

                $data[] = $courseData;
            }
            fclose($handle);
        }
        // Enviar los datos a Node.js
        return $this->sendDataToNode($data, $id_categoria);
    }
    public function sendDataToNode($data, $id_categoria)
    {
        $client = \Config\Services::curlrequest();
        $session = \Config\Services::session();
        $response = new \stdClass();

        $catalogos = new Mglobal;

        foreach ($data as $key) {
            $insert = [
                'fullname' => $key['fullname'],
                'categoryid' => $id_categoria,
                'startdate' => $key['startdate'],
                'enddate' => $key['enddate'],
                'idnumber' => $key['idnumber']
            ];
            $result = $catalogos->createCurso($insert, 'crearCursosDesdeCSV');

            if (!$result->error) {
                $response->error = false;
                $response->respuesta = 'creacion de cursos exitoso';
            } else {
                $response->error = true;
                $response->respuesta = 'Inconsistencia en el archivo, verificar ID moodle';
            }

        }
        return $response;

    }
    public function getAllCursos()
    {
        $session = \Config\Services::session();
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error| Error al generar la consulta';
        $Mglobal = new Mglobal;
        $id_cursos_sac = $this->request->getPost('id_cursos_sac');
        $data = [];
        $result = $Mglobal->getTabla(['tabla' => 'cursos_sac', 'where' => ['visible' => 1, 'activo' => 1, 'id_cursos_sac' => $id_cursos_sac]]);

        if (!$result->error) {
            $response->error = $result->error;
            $response->respuesta = $result->respuesta;
            $response->data = $result->data;
        }

        return $this->respond($response);
    }

    public function detalleCurso($id_cursos_sac = null)
    {
        $session = \Config\Services::session();

        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error| Error al generar la consulta';
        $Mglobal = new Mglobal;
        $data = [];
        $result = $Mglobal->getTabla(['tabla' => 'cursos_sac', 'where' => ['visible' => 1, 'activo' => 1, 'id_cursos_sac' => $id_cursos_sac]]);
        $periodo = $Mglobal->getTabla(['tabla' => 'vw_periodo', 'where' => ['visible' => 1, 'id_curso' => $id_cursos_sac]]);
        $categoria = $Mglobal->getTabla(['tabla' => 'vw_categoria', 'where' => ['visible' => 1, 'id_curso' => $id_cursos_sac]]);
        if (isset($result->data) && empty($result->data)) {
            $data['contentView'] = 'secciones/vError500';
            $data['layout'] = 'plantilla/lytLogin';
            $this->_renderView($data);
            die();

        }
        $data['curso'] = $result->data[0];
        if (!$periodo->error) {
            $data['periodo'] = (isset($periodo->data) && !empty($periodo->data)) ? $periodo->data : [];
        }
        if (!$categoria->error) {
            $data['categoria'] = (isset($categoria->data) && !empty($categoria->data)) ? $categoria->data : [];
        }
        $data['registro'] = false;
        if ($id_cursos_sac) {
            $result = $Mglobal->getTabla(['tabla' => 'estudiante_curso', 'where' => ['id_curso' => $id_cursos_sac, 'id_usuario' => $session->id_usuario, 'visible' => 1,]]);
            if (isset($result->data) && !empty($result->data)) {
                $data['registro'] = true;
            }
        }
        $usuRegCurso = $Mglobal->getTabla(['tabla' => 'estudiante_curso', 'where' => ['visible' => 1, 'id_curso' => $id_cursos_sac, 'id_usuario' => $session->id_usuario]]);
        if (isset($usuRegCurso->data) && !empty($usuRegCurso->data)) {
            $data['id_periodo_editar'] = $usuRegCurso->data[0]->id_estudiante_curso;
        }

        $data['scripts'] = array('agregar');
        $data['contentView'] = 'secciones/vDetalleProgramar';
        $this->_renderView($data);
    }

    private function meses($mes, $anio = null)
    {
        $anio = $anio ?? date('Y');
        if ($mes < 1 || $mes > 12) {
            throw new InvalidArgumentException("El mes debe estar entre 1 y 12");
        }
        $mesFormateado = str_pad($mes, 2, '0', STR_PAD_LEFT);
        $data['mes_inicio'] = "$anio-$mesFormateado-01";
        $data['mes_fin'] = date('Y-m-t', strtotime($data['mes_inicio']));

        return $data;
    }

    private function getFaltasRangoQuincena(DateTime $inicio, DateTime $fin)
    {
        $Mglobal = new Mglobal;
        $session = \Config\Services::session();

        // Convertir DateTime a string en formato YYYY-MM-DD
        $inicioStr = $inicio->format('Y-m-d');
        $finStr = $fin->format('Y-m-d');

        // Obtener todos los registros de asistencia en el rango
        $agenda = $Mglobal->getTabla([
            'tabla' => 'vw_asistencia_incidencia',
            'where' => [
                'id_usuario' => $session->id_usuario,
                'visible' => 1,
            ],
            'whereBetween' => [['fechas_asistencias', $inicioStr, $finStr]]
        ]);

        $asistencias = (!empty($agenda->data)) ? $agenda->data : [];
        $faltas = [];

        // --- CICLO ÚNICO Y EFICIENTE ---
        // Recorremos solo los registros que trajo la base de datos, una sola vez.
        foreach ($asistencias as $row) {

            // Opcional: Si la tabla puede tener registros en fines de semana y quieres ignorarlos.
            $dayOfWeek = (int) date('N', strtotime($row->fechas_asistencias)); // 1 (lunes) a 7 (domingo)
            if ($dayOfWeek > 5) {
                continue; // Si es sábado o domingo, salta al siguiente registro
            }
            if ($row->id_estatus == 3) {
                $faltas[] = (object) [
                    'id_usuario' => $session->id_usuario,
                    'fecha' => $row->fechas_asistencias,
                    'observaciones' => 'Aprobado',
                ];
            }
            if ($dayOfWeek == 1) {
                if (empty($row->hora_inicio) && empty($row->hora_fin)) {
                    $faltas[] = (object) [
                        'id_usuario' => $session->id_usuario,
                        'fecha' => $row->fechas_asistencias,
                        'observaciones' => 'Falta (sin registro)',
                    ];
                }

            }
            if ($dayOfWeek == 2) {
                if (empty($row->hora_inicio) && empty($row->hora_fin)) {
                    $faltas[] = (object) [
                        'id_usuario' => $session->id_usuario,
                        'fecha' => $row->fechas_asistencias,
                        'observaciones' => 'Falta (sin registro)',
                    ];
                }

            }

            if ($row->id_estatus == 1) {
                $faltas[] = (object) [
                    'id_usuario' => $session->id_usuario,
                    'fecha' => $row->fechas_asistencias,
                    'observaciones' => 'En validación',
                ];

                //  return $faltas;
            }

            // Chequeo de llegada tarde
            if ($row->hora_inicio > '09:00:00') {
                $faltas[] = (object) [
                    'id_usuario' => $session->id_usuario,
                    'fecha' => $row->fechas_asistencias,
                    'observaciones' => 'Llegada Tarde',
                ];
            }

            // Chequeo de salida temprana
            if ($row->hora_fin < '16:00:00' && !empty($row->hora_fin)) { // Asegurarse de que no esté vacío
                $faltas[] = (object) [
                    'id_usuario' => $session->id_usuario,
                    'fecha' => $row->fechas_asistencias,
                    'observaciones' => 'Salida Fuera de Tiempo',
                ];
            }

            // Chequeo de salida no registrada (CORREGIDO: usando == en lugar de =)
            if (empty($row->hora_fin)) { // Es una forma más limpia de chequear si está vacío
                $faltas[] = (object) [
                    'id_usuario' => $session->id_usuario,
                    'fecha' => $row->fechas_asistencias,
                    'observaciones' => 'Registro de Salida No Registrado',
                ];
            }
            if ($row->id_estatus == 3) { // Es una forma más limpia de chequear si está vacío
                $faltas[] = (object) [
                    'id_usuario' => $session->id_usuario,
                    'fecha' => $row->fechas_asistencias,
                    'observaciones' => 'Enviado',
                ];
            }

        }

        // Aquí puedes continuar si necesitas encontrar los días de ausencia total.

        return $faltas;
    }

    private function marcarMultiples(&$asistencia)
    {
        $agrupadosPorFecha = [];

        foreach ($asistencia as $registro) {
            $fecha = date('Y-m-d', strtotime($registro->fecha));
            if (!isset($agrupadosPorFecha[$fecha])) {
                $agrupadosPorFecha[$fecha] = [];
            }
            $agrupadosPorFecha[$fecha][] = $registro;
        }

        $resultado = [];

        foreach ($agrupadosPorFecha as $fecha => $registros) {
            usort($registros, function ($a, $b) {
                return strtotime($a->hora_inicio) - strtotime($b->hora_inicio);
            });

            if (count($registros) === 1) {
                $registros[0]->multiple = false;
                $registros[0]->horas_agrupadas = $registros[0]->hora_inicio . ' - ' . $registros[0]->hora_fin;
                $resultado[] = $registros[0];
                continue;
            }

            // CREAR REGISTRO CONSOLIDADO COMBINANDO INFORMACIÓN
            $registroConsolidado = new stdClass();

            // Combinar propiedades inteligentemente (priorizar valores no nulos)
            foreach ($registros as $registro) {
                foreach ($registro as $propiedad => $valor) {
                    // Si la propiedad no existe o es nula en el consolidado, asignar valor
                    if (
                        !property_exists($registroConsolidado, $propiedad) ||
                        $registroConsolidado->$propiedad === null
                    ) {
                        $registroConsolidado->$propiedad = $valor;
                    }

                    // Casos especiales para propiedades específicas
                    if ($propiedad === 'tipo_registro' && $valor !== 'asistencia') {
                        // Priorizar 'incidencia' sobre 'asistencia'
                        $registroConsolidado->tipo_registro = $valor;
                    }

                    if ($propiedad === 'id_estatus' && $valor !== null) {
                        // Priorizar estatus no nulos
                        $registroConsolidado->id_estatus = $valor;
                    }
                }
            }

            // Configurar horas consolidadas
            $primerRegistro = $registros[0];
            $ultimoRegistro = end($registros);

            $registroConsolidado->multiple = true;
            $registroConsolidado->hora_inicio = $primerRegistro->hora_inicio;
            $registroConsolidado->hora_fin = $ultimoRegistro->hora_fin;

            // Crear cadena con todas las horas
            $horas = [];
            foreach ($registros as $reg) {
                $horas[] = $reg->hora_inicio . ' - ' . $reg->hora_fin;
            }
            $registroConsolidado->horas_agrupadas = implode(' | ', $horas);
            $registroConsolidado->registros_individuales = $registros;

            // Marcar visibilidad apropiada
            $registroConsolidado->visible = 1;
            if ($registroConsolidado->tipo_registro === 'incidencia') {
                $registroConsolidado->visible_incidencia = 1;
                $registroConsolidado->visible_asistencia = null;
            } else {
                $registroConsolidado->visible_asistencia = 1;
                $registroConsolidado->visible_incidencia = null;
            }

            $resultado[] = $registroConsolidado;
        }

        return $resultado;
    }


    public function Asistencia($mes = null, $user = null)
    {
        $session = \Config\Services::session();
        $response = new stdClass();
        $Mglobal = new Mglobal;
        $calendarStatic = true;
        $idTipoEmpleado = $Mglobal->getTabla([
            'tabla' => 'vw_usuario',
            'where' => [
                'visible' => 1,
                'id_usuario' => $session->get('id_usuario')
            ],

        ])->data[0]->id_tipo_empleado;


        $data = [];
        if (isset($mes) && !empty($mes)) { // RH
            try {
                $meses = $this->meses($mes);
                $agenda = $Mglobal->getTabla([
                    'tabla' => 'vw_asistencia_incidencia',
                    'where' => [
                        'visible' => 1,
                        'id_usuario' => $user
                    ],
                    'whereBetween' => [
                        ['fecha', $meses['mes_inicio'], $meses['mes_fin']]
                    ]
                ]);
            } catch (InvalidArgumentException $e) {
                // Manejar error de mes inválido
                log_message('error', $e->getMessage());
            }
            $calendarStatic = false;
        } else { // POBLACION
            $agenda = $Mglobal->getTabla([
                'tabla' => 'vw_asistencia_incidencia',
                'where' => [
                    'id_usuario' => $session->get('id_usuario'),
                    'visible' => 1

                ],
            ]);
            // Obtenemos incidencias
        }

        $data['onlyAsistencias'] = [];
        $onlyAsistencias = $Mglobal->getTabla([
            'tabla' => 'asistencia',
            'where' => ['visible' => 1, 'id_usuario' => $session->id_usuario]
        ]);

        if (isset($onlyAsistencias->data) && !empty($onlyAsistencias->data)) {
            foreach ($onlyAsistencias->data as $f) {
                $data['onlyAsistencias'][] = date('Y-m-d', strtotime($f->fecha));
            }
        }



        $inicio = new DateTime('2025-09-01');
        $fin = new DateTime('2025-09-16'); // inclusive

        $otras = [];
        $periodo = new DatePeriod($inicio, new DateInterval('P1D'), (clone $fin)->modify('+1 day'));
        foreach ($periodo as $d) {
            $otras[] = $d->format('Y-m-d');
        }
        //Traer incidencia para quitar el spinner
        $incidenciaUser = $Mglobal->getTabla(['tabla' => 'incidencia', 'where' => ['visible' => 1, 'id_usuario' => $session->id_usuario, 'id_estatus' => 3]]);
        if (isset($incidenciaUser->data) && !empty($incidenciaUser->data)) {
            foreach ($incidenciaUser->data as $f) {
                $otras[] = $f->fecha;
            }
        }

        $data['onlyAsistencias'] = array_values(array_unique(array_merge($data['onlyAsistencias'], $otras)));
        sort($data['onlyAsistencias']); // ascendente

        $incidenciaGeneral = $Mglobal->getTabla([
            'tabla' => 'cat_incidencia',
            'where' => ['visible' => 1, 'sexo' => 3]
        ])->data;

        $incidenciaPorSexo = $Mglobal->getTabla([
            'tabla' => 'cat_incidencia',
            'where' => ['visible' => 1, 'sexo' => $session->id_sexo]
        ])->data;

        // Usar array temporal para evitar duplicados
        $tempArray = [];
        foreach ($incidenciaGeneral as $item) {
            $tempArray[$item->id_incidencia] = $item;
        }
        foreach ($incidenciaPorSexo as $item) {
            $tempArray[$item->id_incidencia] = $item;
        }
        //var_dump($data['onlyAsistencias']);
        //die();

        // Convertir a array indexado numéricamente
        $data['cat_incidencia'] = array_values($tempArray);

        // Ordenar por nombre
        usort($data['cat_incidencia'], function ($a, $b) {
            return strcmp($a->dsc_incidencia, $b->dsc_incidencia);
        });

        // die(var_dump($data['cat_incidencia']));
        $mes = ($mes) ? $mes : date('m');
        $data['anio'] = date('Y');
        $data['idTipoEmpleado'] = $idTipoEmpleado;
        $finMes = new DateTime('last day of this month');
        $hoy = new DateTime('today');
        $dia = (int) $hoy->format('d'); // número del día

        if ($dia >= 16) {
            // quincena 2: arranca día 16
            $inicio = new DateTime($hoy->format('Y-m-16'));
            $fin = $hoy;
        } else {
            // quincena 1: arranca día 1
            $inicio = new DateTime($hoy->format('Y-m-01'));
            $fin = new DateTime($hoy->format('Y-m-15'));
        }
        $data['inicio'] = $inicio->format('d/m/Y');
        $data['fin'] = $fin->format('d/m/Y');


        $asistencia = (isset($agenda->data) && !empty($agenda->data)) ? $agenda->data : [];

        $registrosAgrupados = $this->marcarMultiples($asistencia);
        //die(  var_dump( $registrosAgrupados ) );
        // Definir los días festivos
        $cumple = date('m-d', strtotime($session->fec_nac));
        $anio = date('Y');
        $diasFestivos = [
            $anio . '-01-01' => 'Año Nuevo',
            $anio . '-02-02' => 'Conmemoración del 5 de febrero',
            $anio . '-03-16' => 'Conmemoración del Natalicio de Benito Juárez',
            $anio . '-05-01' => 'Día del Trabajo',
            $anio . '-09-16' => 'Asueto',
            $anio . '-03-30' => 'Semana Santa',
            $anio . '-03-31' => 'Semana Santa',
            $anio . '-04-01' => 'Semana Santa',
            $anio . '-04-02' => 'Semana Santa',
            $anio . '-04-03' => 'Semana Santa',
            $anio . '-07-25' => 'Fiesta del Santo Patrón de la región "Santiago Apóstol"',
            '2025-11-17' => 'Asueto',
           '2025-12-12' => 'Día de la Virgen de Guadalupe',
            $anio . '-11-02' => 'Asueto',
            $anio . '-12-25' => 'Navidad',
            '2025-' . $cumple => 'Mi cumpleaños',
            $anio . '-' . $cumple => 'Mi cumpleaños',
            '2025-12-25' => 'Asueto',
            $anio . '-12-25' => 'Asueto',
            $anio . '-01-01' => 'Asueto',
        ];

        if( $session->get('id_usuario') == 164){
            $diasFestivos = array_merge($diasFestivos, [
               
                '2026-05-04' => 'Nuevo Ingreso',
                '2026-05-05' => 'Nuevo Ingreso',
                '2026-05-06' => 'Nuevo Ingreso',
                '2026-05-07' => 'Nuevo Ingreso',
                '2026-05-08' => 'Nuevo Ingreso',
            ]);
        }

        $data['diasFestivos'] = $diasFestivos;
        // Agregar días festivos al resultado
        foreach ($diasFestivos as $fecha => $titulo) {
            $registrosAgrupados[] = [
                'title' => $titulo,
                'fecha' => $fecha,
                'backgroundColor' => '#3388ff',
                'esFestivo' => true
            ];
        }

        //die( var_dump( $registrosAgrupados ) );
        //$faltas = $this->getFaltasRangoQuincena($inicio, $fin);
        // $data['faltas'] = $faltas;
        $data['asistencia'] = $registrosAgrupados;
        //$data['cat_incidencia'] = $cat_incidencia->data;
        // $data['incidencia'] = (isset($incidencia->data) && !empty($incidencia->data))?$incidencia->data:[];
        //die();
        $data['mes'] = $mes;
        $data['calendarStatic'] = $calendarStatic;
        $data['scripts'] = array('agregar', 'inicio');
        $data['contentView'] = 'secciones/vAsistencia';
        $this->_renderView($data);
    }
    public function deleteAlba()
    {
        $session = \Config\Services::session();
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error al insertar en la tabla';
        $globals = new Mglobal;
        $id_alba = $this->request->getPost('id_alba');
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaViatico'];
        $dataConfig = [
            "tabla" => "lista_alba",
            "editar" => true,
            "idEditar" => ['id_alba' => $id_alba]
        ];
        $result = $globals->saveTabla(['visible' => 0], $dataConfig, $dataBitacora);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
        }
        return $this->respond($response);

    }
    public function albaAlta()
    {
        $session = \Config\Services::session();
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error al insertar en la tabla';
        $globals = new Mglobal;
        $data = $this->request->getPost();
        $file = $this->request->getFile('foto');
        $file2 = $this->request->getFile('protocolo');
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

            $extension2 = $file2->getClientExtension();
            $originalName2 = pathinfo($file2->getName(), PATHINFO_FILENAME);
            $archivo2 = $originalName2 . '_' . $timestamp . '.' . $extension2;

            // Ruta absoluta
            $ruta_destino = FCPATH . 'assets/images/fotos/alba/';
            $file->move($ruta_destino, $archivo);
            $ruta_destino2 = FCPATH . 'assets/pdf/alba/';
            $file2->move($ruta_destino2, $archivo2);

            // Rutas públicas
            //$ruta_absoluta = base_url('aassets/images/fotos/alba/' . $archivo);
            $ruta_relativa = 'assets/images/fotos/alba/' . $archivo;
            $ruta_relativa2 = 'assets/pdf/alba/' . $archivo2;
        }
        if (empty($data['nombre'])) {
            $response->respuesta = 'El nombre es requerido';
            return $this->respond($response);
        }
        if (empty($data['primer_apellido'])) {

            $response->respuesta = 'El primer_apellido es requerido';
            return $this->respond($response);
        }
        if (empty($data['fecha_nacimiento'])) {
            $response->respuesta = 'El fecha_nacimiento es requerido';
            return $this->respond($response);
        }
        if (empty($data['municipio'])) {
            $response->respuesta = 'El municipio es requerido';
            return $this->respond($response);
        }
        if (empty($data['nacionalidad'])) {
            $response->respuesta = 'El nacionalidad es requerido';
            return $this->respond($response);
        }
        if (empty($data['fec_activacion'])) {
            $response->respuesta = 'El fec activacion es requerido';
            return $this->respond($response);
        }
        if (empty($data['fec_desactivacion'])) {
            $response->respuesta = 'El fec desactivacion es requerido';
            return $this->respond($response);
        }
        //  if($this->validarViativos()); return false
        $dataInsert = [
            'nombre' => $data['nombre'],
            'primer_apellido' => $data['primer_apellido'],
            'segundo_apellido' => $data['segundo_apellido'],
            'nacionalidad' => $data['nacionalidad'],
            'id_municipio' => (int) $data['municipio'],
            'id_estatus' => (int) $data['id_estatus'],
            'id_difusion' => (int) $data['id_difusion'],
            'fec_desactivacion' => date('Y-m-d', strtotime($data['fec_desactivacion'])),
            'fec_activacion' => date('Y-m-d', strtotime($data['fec_activacion'])),
            'edad' => $data['edad'],
            'id_sexo' => (int) $data['id_sexo'],
            'fecha_nacimiento' => date('Y-m-d', strtotime($data['fecha_nacimiento'])),
            'usu_reg' => (int) $session->get('id_usuario'),
            'fec_reg' => date('Y-m-d'),
        ];

        // Agrega campos de archivos solo si existen
        if (isset($ruta_relativa) && !empty($ruta_relativa)) {
            $dataInsert['foto'] = $ruta_relativa;      // <- operador correcto
        }
        if (isset($ruta_relativa2) && !empty($ruta_relativa2)) {
            $dataInsert['protocolo'] = $ruta_relativa2; // <- operador correcto
        }
        if ($data['editar'] == 1) {
            $dataConfig = [
                "tabla" => "lista_alba",
                "editar" => true,
                "idEditar" => ['id_alba' => $data['id_alba']]
            ];
        } else {
            $dataConfig = [
                "tabla" => "lista_alba",
                "editar" => false
            ];
        }

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaViatico'];
        $result = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
        }
        return $this->respond($response);

    }
    public function getAlba()
    {
        $session = \Config\Services::session();
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error al traer la tabla';
        $globals = new Mglobal;
        $id_alba = $this->request->getPost('id_alba');
        $result = $globals->getTabla(["tabla" => "lista_alba", 'where' => ['visible' => 1, 'id_alba' => $id_alba]]);
        //var_dump( $result);
        // die();
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
            $response->data = $result->data[0];
        }
        return $this->respond($response);
    }
    public function formConfiguracion()
    {
        $session = \Config\Services::session();
        $response = new stdClass();
        $response->error = true;
        $globals = new Mglobal;
        $response->respuesta = 'Error al traer la tabla';
        $data = $this->request->getPost();

        $dataInsert = [
            "fec_nac" => (isset($data['fec_nac']) && !empty($data['fec_nac'])) ? 1 : 0,
            "edad" => (isset($data['edad']) && !empty($data['edad'])) ? 1 : 0,
            "nivel" => (isset($data['nivel']) && !empty($data['nivel'])) ? 1 : 0,
            "no_empleado" => (isset($data['no_empleado']) && !empty($data['no_empleado'])) ? 1 : 0,
            "sexo" => (isset($data['sexo']) && !empty($data['sexo'])) ? 1 : 0,
            "contrato" => (isset($data['contrato']) && !empty($data['contrato'])) ? 1 : 0,
            "id_usuario" => $session->id_usuario,
        ];

        $result = $globals->getTabla(["tabla" => "configuracion", 'where' => ['visible' => 1, 'id_usuario' => $session->id_usuario]]);
        if (!empty($result->data)) {
            $dataConfig = [
                "tabla" => "configuracion",
                "editar" => true,
                "idEditar" => ['id_usuario' => $session->id_usuario]
            ];
        } else {
            $dataConfig = [
                "tabla" => "configuracion",
                "editar" => false,
                //"idEditar" => ['id_usuario' => $session->id_usuario]
            ];
        }

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaConfiguracion'];

        $response = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
        return $this->respond($response);


    }
    public function formViatico()
    {
        $session = \Config\Services::session();
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error al procesar la solicitud';

        $globals = new Mglobal;
        $data = $this->request->getPost();

        // 1. Detectar si es EDICIÓN o INSERCIÓN
        $id = isset($data['id_juridico_viatico']) && !empty($data['id_juridico_viatico']) 
          ? $data['id_juridico_viatico'] 
          : null;

        // 2. Limpiar montos
        $limpiarMonto = function($monto) {
            return str_replace(['$', ',', ' '], '', (string)$monto);
        };

        // 3. Preparar datos base (Respetando typos exactos de la tabla)
        $dataSave = [
            'ejercicio'                => $data['ejercicio'] ?? null,
            'fecha_inicio'             => isset($data['fecha_inicio']) && !empty($data['fecha_inicio']) ? date('Y-m-d', strtotime($data['fecha_inicio'])) : null,
            'fecha_termino'            => isset($data['fecha_termino']) && !empty($data['fecha_termino']) ? date('Y-m-d', strtotime($data['fecha_termino'])) : null,
            'tipo_integrante'          => (int) ($data['tipo_integrante'] ?? 0),
            'clave_nivel'              => (int) ($data['clave_nivel'] ?? 0),
            'denominacion_puesto'      => (int) ($data['denominacion_puesto'] ?? 0),
            'denomicacion_cargo'       => (int) ($data['denomicacion_cargo'] ?? 0),
            'area_adscripcion'         => (int) ($data['area_adscripcion'] ?? 0),
            'nombre_completo'          => (int) ($data['nombre_completo'] ?? 0),
            'tipo_gasto'               => (int) ($data['tipo_gasto'] ?? 0),
            'tipo_viaje'               => (int) ($data['tipo_viaje'] ?? 0),
            'no_personas'              => (int) ($data['no_personas'] ?? 0),
            'importe_ejercicio'        => $limpiarMonto($data['importe_ejercicio'] ?? '0'),
            'pais_origen'              => (int) ($data['pais_origen'] ?? 0),
            'estado_origen_id'         => (int) ($data['estado_origen_id'] ?? 0),
            'estado_origen_text'       => $data['estado_origen_text'] ?? '',
            'municipio_origen_id'      => (int) ($data['municipio_origen_id'] ?? 0),
            'municipio_origen_text'    => $data['municipio_origen_text'] ?? '',
            'pais_destino'             => (int) ($data['pais_destino'] ?? 0),
            'estado_destino_id'        => (int) ($data['estado_destino_id'] ?? 0),
            'estado_destino_text'      => $data['estado_destino_text'] ?? '',
            'denomicacion_encargo'     => $data['denominacion_encargo'] ?? '',
            'municipio_destino_text'   => $data['municipio_destino_text'] ?? '',
            'municipio_destino_id'     => (int) ($data['municipio_destino'] ?? 0), 
            'motivo_encargo'           => $data['motivo_encargo'] ?? '',
            'fec_salida'               => $data['fec_salida'] ?? null,
            'fec_regreso'              => $data['fec_regreso'] ?? null,
            'importe_ejercicio_partida'=> $limpiarMonto($data['importe_ejercicio_partida'] ?? '0'),
            'importe_total'            => $limpiarMonto($data['importe_total'] ?? '0'),
            'fec_entraga_informa'      => $data['fec_entrega_informe'] ?? null,
            'hipervinculo_informe'     => $data['hipervinculo_informe'] ?? '',
            'hipervinculo_factura'     => $data['hipervinculo_factura'] ?? '',
            'hipervinculo_normativa'   => $data['hipervinculo_normativa'] ?? '',
            'area_responsabe'          => $data['area_responsable'] ?? '',
            'fec_actualizacion'        => $data['fec_actualizacion'] ?? null,
            'nota'                     => $data['nota'] ?? '',
        ];

        // 4. Configuración Auditoría y Modo (Insert vs Update)
        if ($id) {
            $dataSave['usu_act'] = $session->get('id_usuario');
            $dataSave['fec_act'] = date('Y-m-d H:i:s');

            $dataConfig = [
                "tabla"    => "juridico_viaticos",
                "editar"   => true,
                "idEditar" => ["id_juridico_viatico" => $id]
            ];
        } else {
            $dataSave['usu_reg'] = $session->get('id_usuario');
            $dataSave['fec_reg'] = date('Y-m-d');
            $dataSave['visible'] = 1;

            $dataConfig = [
                "tabla"  => "juridico_viaticos",
                "editar" => false
            ];
        }

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/formViatico'];
        $result = $globals->saveTabla($dataSave, $dataConfig, $dataBitacora);

        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $id ? 'Registro actualizado correctamente' : 'Registro insertado correctamente';
        } else {
            $response->respuesta = $result->respuesta;
        }
        return $this->respond($response);
    }
    public function eliminar()
    {
        $session = \Config\Services::session();
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error al eliminar';

        $globals = new Mglobal;
        $data = $this->request->getPost();
        $session = \Config\Services::session();
        $id = $this->request->getPost('id_juridico_viatico');

        if (!$id) {
            $response->respuesta = "No se recibió el ID del registro.";
            return $this->respond($response);
        }   

        $dataUpdate = [
            'visible' => 0,
            'usu_act' => $session->id_usuario,
            'fec_act' => date('Y-m-d H:i:s')
        ];

        $dataConfig = [
            "tabla"  => "juridico_viaticos",
            "editar" => true,
            "idEditar"  => ["id_juridico_viatico" => $id]
        ];

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/eliminar'];

        $result = $globals->saveTabla($dataUpdate, $dataConfig, $dataBitacora);

        if (!$result->error) {
           $response->error = false;
            $response->respuesta = 'Registro eliminado correctamente.';
        } else {
            $response->respuesta = $result->respuesta;
        }

        return $this->respond($response);
    }
    public function ReservarSala()
    {
        $session = \Config\Services::session();
        $response = new stdClass();
        $Mglobal = new Mglobal;
        $data = [];

        $sala_junta = $Mglobal->getTabla(['tabla' => 'vw_sala_junta', 'where' => ['visible' => 1]]);

        $hoy = date("Y-m-d");
        $sala_hoy = $Mglobal->getTabla([
            'tabla' => 'vw_sala_junta',
            'where' => [
                'visible' => 1,
                'DATE(fecha)' => $hoy // Filtra solo el día, ignorando la hora
            ]
        ]);

        $data['sala_junta'] = (isset($sala_junta->data) && !empty($sala_junta->data)) ? $sala_junta->data : [];
        $data['sala_hoy'] = (isset($sala_hoy->data) && !empty($sala_hoy->data)) ? $sala_hoy->data : [];
        $data['scripts'] = array('agregar', 'inicio');
        $data['contentView'] = 'secciones/vSala';
        $this->_renderView($data);
    }
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
    public function ReporteUsuario($fec_inicio = null, $fec_fin = null, $usuario = null, $id_incidencia = null)
    {
        $session = \Config\Services::session();
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'No existen incidencia del usuario';
        $globals = new Mglobal;
        $fechaInicio = date('Y-m-d', strtotime($fec_inicio));
        $fechaFin = date('Y-m-d', strtotime($fec_fin));

        $data = array();
        $incidenciaUsuario = null;

        if ($usuario == 'todos') {
            $incidenciaUsuario = $globals->getTabla([
                'tabla' => 'vw_incidenica',
                'where' => ['id_estatus' => 3],
                'whereBetween' => [['fecha_inicio', $fechaInicio, $fechaFin]]
            ]);
        } else {
            $incidenciaUsuario = $globals->getTabla([
                'tabla' => 'vw_incidenica',
                'where' => ['id_usuario' => $usuario, 'id_estatus' => 3],
                'whereBetween' => [['fecha_inicio', $fechaInicio, $fechaFin]]
            ]);
            
            if (empty($incidenciaUsuario->data)) {
                $incidenciaUsuario = $globals->getTabla([
                    'tabla' => 'vw_incidenica',
                    'where' => ['id_usuario' => $usuario, 'id_estatus' => 3],
                    'whereBetween' => [['fecha_fin', $fechaInicio, $fechaFin]]
                ]);
            }
        }

        $incidenciaData = [];
        if (isset($incidenciaUsuario->data) && !empty($incidenciaUsuario->data)) {
            if ($id_incidencia != 'todos') {
                foreach ($incidenciaUsuario->data as $i) {
                    if ($i->cat_id_incidencia == $id_incidencia) {
                        $incidenciaData[] = $i;
                    }
                }
            } else {
                $incidenciaData = $incidenciaUsuario->data;
            }
        }

        $data['incidencia'] = $incidenciaData;
        if ($usuario == 'todos') {
            $data['usuario'] = !empty($incidenciaData) ? $incidenciaData : '';
            $usuariosAgrupados = [];

            foreach ($data['incidencia'] as $key => $i) {
                $nombreUsuario = $i->nombre_completo;

                if (!isset($usuariosAgrupados[$nombreUsuario])) {
                    $usuariosAgrupados[$nombreUsuario] = [
                        'nombre_completo' => $nombreUsuario,
                        'dsc_area' => $i->dsc_area,
                        'incidencias' => []
                    ];
                }

                $usuariosAgrupados[$nombreUsuario]['incidencias'][] = [
                    'dsc_incidencia' => $i->dsc_incidencia,
                    'fecha_inicio' => $i->fecha_inicio,
                    'hora_inicio' => $i->hora_inicio,
                    'fecha_fin' => $i->fecha_fin,
                    'hora_fin' => $i->hora_fin,
                    'detalles' => $i->detalles,
                    'tipo' => $i->tipo,
                    'nombre_completo_autoriza' => $i->nombre_completo_autoriza,
                ];
            }

            // Convertir a array indexado si lo prefieres
            $usuariosAgrupados = array_values($usuariosAgrupados);
            $data['usuariosAgrupados'] = $usuariosAgrupados;

        } else {
            if (!empty($incidenciaData)) {
                $data['usuario'] = $incidenciaData[0];
            } else {
                // Obtener datos del usuario directamente de la base de datos si no hay incidencias
                $dbUsuario = $globals->getTabla(['tabla' => 'usuario', 'where' => ['id_usuario' => $usuario]]);
                if (!empty($dbUsuario->data)) {
                    $u = $dbUsuario->data[0];
                    $nombreCompleto = trim(($u->primer_apellido ?? '') . ' ' . ($u->segundo_apellido ?? '') . ' ' . ($u->nombre ?? ''));
                    $data['usuario'] = (object)[
                        'nombre_completo' => $nombreCompleto,
                        'dsc_area' => 'Sin incidencias en el periodo'
                    ];
                } else {
                    $data['usuario'] = (object)[
                        'nombre_completo' => 'Usuario no encontrado',
                        'dsc_area' => ''
                    ];
                }
            }
        }


        $tempQrPath = FCPATH . 'assets/images/qr_final.png';
        $folio = 'GTO - ' . date('YmdHis') . substr((string) microtime(), 1, 4);
        // Generar el QR
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data(base_url() . 'index.php/Principal/reporteIncidenciaUsuario/' . $fechaInicio . '/' . $fechaFin . '/' . $usuario . '/' . $folio)
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

        $vista = ($usuario == 'todos') ? 'personal/vFormatoAsistenciaAll.php' : 'personal/vFormatoAsistenciaUser.php';

        $doc = 'assets/pdf/plantillas/asistencia.pdf';
        $formato = $vista;
        $html = view($formato, $data);
        // Crear instancia de mPDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

          $html = view($formato, $data);
        
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'format' => 'Letter'
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Formato_Asistencia_' . $id_incidencia . '.pdf', 'I');
        exit();

    }


    public function validarReporte()
    {
        $session = \Config\Services::session();
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'EL Usuario <strong style="color:red"> no tiene incidencia(s)</strong> en esos periodos';
        $globals = new Mglobal;
        $periodoInicio = $this->request->getPost('periodoInicio');
        $periodoFin = $this->request->getPost('periodoFin');
        $id_usuario = $this->request->getPost('usuario');
        $id_incidencia = $this->request->getPost('incidencia');
        $fec_ini = date('Y-m-d', strtotime($periodoInicio));
        $fec_fin = date('Y-m-d', strtotime($periodoFin));
        if ($id_usuario == 'todos') {
            $response->error = false;
            $response->respuesta = 'Si existen incidencia del usuario';
            return $this->respond($response);
        }
    
        $tabla = [
            'tabla' => 'incidencia',
            'where' => ['id_usuario' => $id_usuario],
            'whereBetween' => [['fecha_inicio', $fec_ini, $fec_fin]]
        ];
        $incidencias = $globals->getTabla($tabla);
        
        if (empty($incidencias->data)) {
            $tabla = [
                'tabla' => 'incidencia',
                'where' => ['id_usuario' => $id_usuario],
                'whereBetween' => [['fecha_fin', $fec_ini, $fec_fin]]
            ];
            $incidencias = $globals->getTabla($tabla);
        }

        $tiene_incidencias = false;
        if (isset($incidencias->data) && !empty($incidencias->data)) {
            if ($id_incidencia != 'todos') {
                foreach ($incidencias->data as $i) {
                    if ($i->cat_id_incidencia == $id_incidencia) {
                        $tiene_incidencias = true;
                        break;
                    }
                }
            } else {
                $tiene_incidencias = true;
            }
        }

        if ($tiene_incidencias) {
            $response->error = false;
            $response->respuesta = 'Si existen incidencia del usuario';
        }

        return $this->respond($response);

    }
    public function ListaAlba()
    {
        $session = \Config\Services::session();
        $data = array();
        $globals = new Mglobal;
        $usuario = $globals->getTabla(['tabla' => 'lista_alba', 'where' => ['visible' => 1]]);
        $cat_municipios = $globals->getTabla(['tabla' => 'cat_municipios', 'where' => ['id_estado' => 11, 'visible' => 1]]);
        $data['usuario'] = $usuario->data;
        $data['cat_municipios'] = $cat_municipios->data;
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vListaAlba';
        $this->_renderView($data);
    }
    public function getActividad()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error| Error al consultar la actividad';
        $globals = new Mglobal;
        $id_actividad = $this->request->getPost('id_actividad');
        $resul = $globals->getTabla(['tabla' => 'vw_actividad', 'where' => ['visible' => 1, 'id_actividad' => $id_actividad]]);
        if (!$resul->error) {
            $response->error = false;
            $response->respuesta = $resul->respuesta;
            $response->data = $resul->data[0];

        }
        return $this->respond($response);
    }
    public function registroSala()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error| Error al guardar Sala';
        $globals = new Mglobal;
        $data = $this->request->getPost();

        if (empty($data['hora_inicio'])) {
            $response->respuesta = 'Es requerido la hora de inicio';
            return $this->respond($response);

        }
        if (empty($data['hora_fin'])) {
            $response->respuesta = 'Es requerido la hora de fin';
            return $this->respond($response);

        }
        if (empty($data['asistentes'])) {
            $response->respuesta = 'Es requerido el numero de asistentes';
            return $this->respond($response);

        }
        if (empty($data['evento'])) {
            $response->respuesta = 'Es requerido el nombre del evento';
            return $this->respond($response);

        }

        // Convertir a timestamps para comparación
        // Normalizar las horas del FRONT
        $inicioDT = new DateTime($data['hora_inicio'] . ':00');
        $finDT = new DateTime($data['hora_fin'] . ':00');

        // Validar que fin > inicio
        if ($finDT <= $inicioDT) {
            $response->respuesta = 'La hora de fin debe ser mayor a la hora de inicio';
            return $this->respond($response);
        }

        $fecha = $data['fecha'];
        $like = ['fecha' => "%$fecha%"];
        $dataDB = array('tabla' => 'sala_junta', 'where' => ['visible' => 1, 'sala' => $data['sala']], 'orlike' => $like, );
        $response = $globals->getTabla($dataDB);

        if (isset($response->data) && !empty($response->data)) {

            foreach ($response->data as $f) {

                // Normalizar horas del registro existente de BD
                $inicioExist = new DateTime($f->hora_inicio);
                $finExist = new DateTime($f->hora_fin);

                // Validar traslape correcto
                if ($inicioDT < $finExist && $finDT > $inicioExist) {

                    $response->error = true;
                    $response->respuesta =
                        "La Sala {$data['sala']} ya está reservada de {$f->hora_inicio} a {$f->hora_fin}";
                    return $this->respond($response);
                }
            }
        }


        $dataInsert = [
            'sala' => $data['sala'],
            'fecha' => $data['fecha'] . ' ' . $data['hora_inicio'],
            'hora_inicio' => $data['hora_inicio'],
            'hora_fin' => $data['hora_fin'],
            'evento' => $data['evento'],
            'asistentes' => $data['asistentes'],
            'proyector' => $data['proyecto'],
            'tipo_reunion' => $data['tipo_reunion'],
            'id_usuario' => $session->get('id_usuario'),
            'catering' => $data['catering']
        ];
        $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Agregar.php/guardaSala'];
        $dataConfig = [
            "tabla" => "sala_junta",
            "editar" => false
        ];

        $sala = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
        if (!$sala->error) {
            $response->error = $sala->error;
            $response->respuesta = $sala->respuesta;
        }

        return $this->respond($response);


    }
    public function getIncidencia()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal;
        $id_incidencia = $this->request->getPost('id_incidencia');
        if (in_array($session->get('id_perfil') . [1, 2])) {
            $inicencias = $globals->getTabla(['tabla' => 'incidencia', 'where' => ['visible' => 1]]);
        } else {
            $inicencias = $globals->getTabla(['tabla' => 'incidencia', 'where' => ['visible' => 1, 'id_usuario' => $session->get('id_usuario')]]);
        }
        if (!$inicencias->error) {
            $response->error = false;
            $response->data = $inicencias->data;

        }
        return $this->respond($response);
    }
    public function enviarCorreo($correo1)
    {
        // Inicializar servicios y objetos
        $email = Services::email();
        $session = Services::session();
        $response = new \stdClass();


        // Obtener datos del usuario
        $global = new Mglobal();
        $usuario = $global->getTabla([
            'tabla' => 'vw_usuario',
            'where' => [
                'visible' => 1,
                'id_usuario' => $session->id_usuario
            ]
        ]);

        // Validar correo del usuario
        if (empty($usuario->data[0]->correo)) {
            $response->respuesta = "El usuario no contiene correo";
            return $this->response->setJSON($response);
        }

        $correo2 = $usuario->data[0]->correo;

        // Configurar y enviar correo
        $email->setFrom($correo2, 'SUSI');
        //$email->setTo("dasedetur@guanajuato.gob.mx");
        $email->setTo($correo1);
        $email->setSubject('JUSTIFICACION DE INCIDENCIA');
        $email->setMessage('
                    <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                        <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                            <div style="background-color: #004080; padding: 20px; text-align: center;">
                                <img src="' . base_url('assets/images/logo-sm.png') . '" alt="Logo" style="height: 60px;">
                            </div>
                            <div style="padding: 30px; color: #333;">
                                <h1 style="color: #004080;">¡El estatus de su incidencia cambió!</h1>
                                <p style="font-size: 16px;">Favor de <strong> Ingresar a SUSI</strong>.</p>
                                <p style="font-size: 15px;"><a href="' . base_url() . 'index.php/Agregar/Asistencia"><strong>Seguimiento Incidencia</strong></a></p>
                            </div>
                            <div style="background-color: #e0e0e0; text-align: center; padding: 15px; font-size: 13px; color: #666;">
                                © ' . date('Y') . ' Sistema de Atención SUSI. Todos los derechos reservados.
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

        return $this->response->setJSON($response);


    }
    public function formIncidencia()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal;
        $data = $this->request->getPost();

        // Convierte la fecha a timestamp
        $fecha = $data['fecha_inicio_asistencia'];
        $diaSemana = date('N', strtotime($fecha));

        if (empty($data['tipo_incidencia_editar']) || $data['tipo_incidencia_editar'] == 0) {
            $response->error = true;
            $response->respuesta = 'Es requerido el tipo de incidencia';
            return $this->respond($response);

        }
        // Validar que NO sea lunes (1) ni viernes (5)
        if ($data['tipo_incidencia_editar'] == 9) {
            if ($diaSemana == 1 || $diaSemana == 5) {
                $response->error = true;
                $response->respuesta = 'La fecha no puede ser lunes ni viernes';
                return $this->respond($response);
            }
        }
        $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Agregar.php/edotaIncidencia'];
        $dataInsert = [
            "cat_id_incidencia" => $data['tipo_incidencia_editar'],
            "fecha" => $data['fecha_inicio_asistencia'],
            "fecha_inicio" => $data['fecha_inicio_asistencia'],
            "fecha_fin" => $data['fecha_fin_asistencia'],
            "hora_inicio" => $data['hora_inicio_asistencia'],
            "hora_fin" => $data['hora_fin_asistencia'],
            "comentario" => $data['comentario_asistencia'],
            "detalles" => $data['detalle_asistencia'],
            "id_estatus" => 1,
            "usu_act" => $session->get('id_usuario'),

        ];
        $dataConfig = [
            "tabla" => "incidencia",
            "editar" => true,
            "idEditar" => ['id_incidencia' => $data['id_incidencia']],
        ];

        $response = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);


        return $this->respond($response);


    }

    public function aceptarIncidencia()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal;
        $id_incidencia = $this->request->getPost('id_incidencia');
        $id_aceptar = $this->request->getPost('id_aceptar');
        $id_usuario = $this->request->getPost('id_usuario');
        $observaciones = $this->request->getPost('observaciones');
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaIncidencia'];
        //optener el correo el empleado de la incidenci
        $datos = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['id_usuario' => $id_usuario]])->data[0];
        if ($datos) {
            $correo = $datos->correo;
            $no_empleado = $datos->no_empleado;
        }
        $dataConfig = [
            "tabla" => "incidencia",
            "editar" => true,
            "idEditar" => ['id_incidencia' => $id_incidencia]
        ];
        $Insert = [
            'id_estatus' => $id_aceptar,
            'observaciones' => $observaciones,
            'usu_act' => $session->get('id_usuario')
        ];

        $result = $globals->saveTabla($Insert, $dataConfig, $dataBitacora);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
            $res = $this->enviarCorreo($correo);

        }
        return $this->respond($response);
        /*            //traer fecha de la incidencia para eliminar el spinner
           $fecha = $globals->getTabla(['tabla' => 'incidencia', 'where' => ['id_incidencia' => $id_incidencia, 'visible' =>1]])->data[0]->fecha;
           $dataConfig = [
               "tabla" => "asistencia",
               "editar" => false
           ];
           $Insert = [
               'id_usuario' => $id_usuario,
               'fecha' => $fecha,
               'tipo_registro' => ($id_aceptar==3)?'Justificacion':'Declinado',
               'entrada' => '08:30:00',
               'salida' => '16:00:00',
               'no_empleado' => $no_empleado
           ];

           $response = $globals->saveTabla($Insert, $dataConfig, $dataBitacora); */

    }
    public function eliminarIncidencia()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal;
        $id_incidencia = $this->request->getPost('id_incidencia');
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/EliminarIncidencia'];
        $dataConfig = [
            "tabla" => "incidencia",
            "editar" => true,
            "idEditar" => ['id_incidencia' => $id_incidencia]
        ];
        $Insert = [
            'visible' => 0,
            'usu_act' => $session->get('id_usuario')
        ];
        $result = $globals->saveTabla($Insert, $dataConfig, $dataBitacora);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
        }

        return $this->respond($response);
    }
    public function detalleIncidencia()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal;
        $id_incidencia = $this->request->getPost('id_incidencia');
        $inicencias = $globals->getTabla(['tabla' => 'vw_incidenica', 'where' => ['visible' => 1, 'id_incidencia' => $id_incidencia]]);

        if (!$inicencias->error) {
            $response->error = false;
            $response->data = $inicencias->data[0];

        }
        return $this->respond($response);
    }

    public function getCoursesByCategoryId($id_categoria)
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $catalogos = new Mglobal;

        $eventos = '';
        $data = [
            'categoryId' => $id_categoria
        ];

        $categoria = $catalogos->createCurso($data, 'getCoursesByCategoryId');

        if (!empty($categoria->data)) {
            // Recorre los cursos y convierte las fechas a un formato legible
            foreach ($categoria->data as &$curso) {
                if (isset($curso->startdate)) {
                    $curso->startdate_legible = date('d-m-Y', $curso->startdate);
                }
                if (isset($curso->enddate)) {
                    $curso->enddate_legible = date('d-m-Y', $curso->enddate);
                }
            }
            $eventos = $categoria->data;
        }

        return $this->respond($eventos);
    }
    public function guardaCategoria()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        // $response->error = true;
        $this->globals = new Mglobal();
        $data = $this->request->getPost();

        $hoy = date("Y-m-d H:i:s");

        if (empty($data['nombre_curso'])) {
            throw new Exception("Es requerido el Nombre del curso");
        }
        //valida que el nombre del curso y nombre corto del curso no se repitan
        if (!empty($data['nombre_curso'])) {
            $cursoDB = $this->globals->getTabla(['tabla' => 'categoria', 'where' => ['dsc_categoria' => $data['nombre_curso'], 'visible' => 1]]);
            if (!empty($cursoDB->data) && isset($cursoDB->data[0]->dsc_categoria)) {
                throw new Exception("Es Nombre del curso ya existe");
            }

        }

        $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Agregar.php/guardaCurso'];
        $dataInsert = [
            'categoryName' => $data['nombre_curso'],
            'courseName' => 'Curso de Prueba',
            'startDate' => '2023-01-01',
            'endDate' => '2023-12-31'
        ];

        $response = $this->globals->createCurso($dataInsert, 'crearCategoria');

        if ($response->error) {
            throw new Exception("No se puedo crear la Categoria");
        } else {
            $dataConfig = [
                "tabla" => "categoria",
                "editar" => false,
                // "idEditar"=>['id_usuario'=>$data['id_usuario']]
            ];
            $Insert = [
                'dsc_categoria' => $response->data[0]->name,
                'id_moodle_categoria' => $response->data[0]->id,
                'fec_reg' => $hoy
            ];
            $response = $this->globals->saveTabla($Insert, $dataConfig, $dataBitacora);
        }

        return $this->respond($response);
    }
    public function formConfigurarCurso()
    {
        $session = \Config\Services::session();
        $response = new stdClass();
        $catalogos = new Mglobal;

        // Obtener el evento_id encriptado desde GET y desencriptarlo
        $formData = $this->request->getPost();

        //validar que ya exista el curso 
        $cursoExiste = $catalogos->getTabla(['tabla' => 'cursos_perfil', 'where' => ['id_curso' => $formData['id_curso'], 'visible' => 1, 'id_padre' => $session->get('id_perfil')]]);
        if (empty($cursoExiste->data)) {

            $insert = [
                'id_curso' => (int) $formData['id_curso'],
                'id_padre' => $session->get('id_perfil'),
                'fec_reg' => date("Y-m-d H:i:s"),
                'usu_reg' => $session->get('id_usuario')
            ];
            $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Agregar.php/updateEventos'];

            $dataConfig = [
                "tabla" => "cursos_perfil",
                "editar" => false,
                // "idEditar"=>['id_curso_moodle'=>$formData['id_curso']]
            ];
            $result = $catalogos->saveTabla($insert, $dataConfig, $dataBitacora);
            if (!$result->error) {
                $response->error = $result->error;
                $response->respuesta = $result->respuesta;
            } else {
                $response->error = true;
                $response->respuesta = 'Error al actualizar las fechas';
            }

        }


        foreach ($formData['tableData'] as $key) {
            // Accede a los valores directamente sin `$i` en el índice
            if (isset($key["id_curso"]) && $key["id_curso"] > 0) {
                $data = [
                    'id_curso' => $key["id_curso"],
                    'timeopen' => strtotime($key["timeopen"]),  // Convierte a Unix timestamp
                    'timeclose' => strtotime($key["timeclose"])  // Convierte a Unix timestamp
                ];
                $result = $catalogos->createCurso($data, 'updateQuiz');
                if (!$result->error) {
                    $response->error = $result->error;
                    $response->respuesta = $result->respuesta;
                } else {
                    $response->error = true;
                    $response->respuesta = 'Error al actualizar las fechas';
                }

            }
        }

        return $this->respond($response);
    }



    public function guardarSolicitud()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error al guardar Solicitud";

        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        
        // Validaciones
        $camposRequeridos = [
            'cheque_favor' => 'Cheque a favor',
            'cantidad' => 'Cantidad',
            'nombre_evento' => 'Nombre del evento',
            'lugar' => 'Lugar',
            'fecha_incicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'clave' => 'Clave',
            'nombre_resposable' => 'Nombre Responsable'
        ];

        foreach($camposRequeridos as $campo => $nombre) {
             if (empty($data[$campo])) {
                $response->respuesta = "Es requerido el campo: " . $nombre;
                return $this->respond($response);
            }
        }
        
        // Limpiar cantidad
        $cantidad_limpia = floatval(str_replace(['$', ',', ' '], '', $data['cantidad']));

        // Datos principales
        $dataInsert = [
           'cheque_favor' => $data['cheque_favor'],
           'cantidad' => $cantidad_limpia,
           'nombre_evento' => $data['nombre_evento'],
           'lugar' => $data['lugar'],
           'fecha_inicio' => $data['fecha_incicio'],
           'fecha_fin' => $data['fecha_fin'],
           'clave' => $data['clave'],
           'nombre_responsable' => $data['nombre_resposable'],
           'no_consecutivo' => $data['no_consecutivo'] ?? '',
           'fec_reg' => date('Y-m-d H:i:s'),
           'usu_reg' => $session->get('id_usuario')
        ];

        $id_solicitud = isset($data['id_solicitud']) && !empty($data['id_solicitud']) ? $data['id_solicitud'] : 0;

        // Configuración para Guardar/Editar
        $dataConfig = [
            "tabla" => "solicitud_grc", 
            "editar" => ($id_solicitud > 0)
        ];
        
        if ($id_solicitud > 0) {
            $dataConfig["idEditar"] = ['id_solicitud_grc' => $id_solicitud];
        }

        $dataBitacora = [
            'id_user' => $session->get('id_usuario'),
            'script' => 'Agregar.php/guardarSolicitud'
        ];

        $result = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);

        if (!$result->error) {
            // Si es nuevo registro, obtener el ID insertado
            if ($id_solicitud == 0) {
                 $id_solicitud = isset($result->idRegistro) ? $result->idRegistro : 0;
            } else {
                 // Si es edición, deshabilitar detalles anteriores para insertar los nuevos (clean slate approach)
                 // Primero obtenemos los detalles actuales
                 $detallesActuales = $this->globals->getTabla(['tabla' => 'solicitud_grc_detalle', 'where' => ['id_solicitud_grc' => $id_solicitud, 'visible' => 1]]);
                 if (!empty($detallesActuales->data)) {
                     foreach($detallesActuales->data as $det) {
                         // Deshabilitamos cada detalle
                         $this->globals->saveTabla(['visible' => 0], ["tabla" => "solicitud_grc_detalle", "editar" => true, "idEditar" => ['id_solicitud_grc_detalle' => $det->id_solicitud_grc_detalle]], $dataBitacora);
                     }
                 }
            }
            
            // Guardar detalles (Nuevos o Reemplazo)
            if (isset($data['detalles']) && is_array($data['detalles'])) {
                foreach ($data['detalles'] as $detalle) {
                    if(!empty($detalle['partida']) && !empty($detalle['importe'])) {
                         $importe_limpio = floatval(str_replace(['$', ',', ' '], '', $detalle['importe']));
                         
                         $detalleInsert = [
                             'id_solicitud_grc' => $id_solicitud, // FK
                             'id_partida' => $detalle['partida'],
                             'importe' => $importe_limpio,
                             'id_proyecto' => $detalle['proyecto'] ?? null,
                             'fec_reg' => date('Y-m-d H:i:s'),
                             'usu_reg' => $session->get('id_usuario'),
                             'visible' => 1
                         ];
                         
                         $this->globals->saveTabla($detalleInsert, ["tabla" => "solicitud_grc_detalle", "editar" => false], $dataBitacora);
                    }
                }
            }
            
            $response->error = false;
            $response->respuesta = ($id_solicitud > 0 && isset($data['id_solicitud'])) ? "Solicitud actualizada correctamente" : "Solicitud guardada correctamente";
            // Check if it was an update but $id_solicitud came from result (create case) vs form (update case)
             if (isset($data['id_solicitud']) && !empty($data['id_solicitud'])) {
                  $response->respuesta = "Solicitud actualizada correctamente";
             } else {
                  $response->respuesta = "Solicitud guardada correctamente";
             }

        } else {
             $response->respuesta = $result->respuesta;
        }

        return $this->respond($response);
    }
    public function eliminarSolicitud()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error al eliminar Solicitud";

        $this->globals = new Mglobal();
        $id_solicitud = $this->request->getPost('id_solicitud');
        
        if ($id_solicitud) {
            $dataConfig = [
                "tabla" => "solicitud_grc", 
                "editar" => true,
                "idEditar" => ['id_solicitud_grc' => $id_solicitud]
            ];
            $dataBitacora = [
                'id_user' => $session->get('id_usuario'),
                'script' => 'Agregar.php/eliminarSolicitud'
            ];
            
            // Soft delete (visible = 0)
            $result = $this->globals->saveTabla(['visible' => 0], $dataConfig, $dataBitacora);
            
            $response->error = $result->error;
            $response->respuesta = $result->error ? "Error al eliminar" : "Solicitud eliminada correctamente";
        }

        return $this->respond($response);
    }


    public function guardarComprobacion()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error al guardar comprobación";

        $this->globals = new Mglobal();
        $data = $this->request->getPost();

        $id_solicitud = isset($data['id_solicitud_grc']) ? $data['id_solicitud_grc'] : 0;

        if ($id_solicitud == 0) {
            $response->respuesta = "Solicitud no válida";
            return $this->respond($response);
        }

        if (!isset($data['comprobacion']) || empty($data['comprobacion'])) {
            $response->respuesta = "No hay comprobantes para guardar";
            return $this->respond($response);
        }
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarComprobacion'];
        // Deshabilitar anteriores (limpieza para evitar duplicados si se edita)
        // NOTA: Esto asume que la tabla 'solicitud_grc_comprobacion' existe.
        $detallesActuales = $this->globals->getTabla(['tabla' => 'solicitud_grc_comprobacion', 'where' => ['id_solicitud_grc' => $id_solicitud, 'visible' => 1]]);
        
        if (isset($detallesActuales->data)) {
            foreach($detallesActuales->data as $d){
                 $this->globals->saveTabla(['visible' => 0], ['tabla' => 'solicitud_grc_comprobacion', 'editar' => true, 'idEditar' => ['id_solicitud_grc_comprobacion' => $d->id_solicitud_grc_comprobacion]], $dataBitacora);
            }
        }

        $error = false;
        foreach ($data['comprobacion'] as $row) {
            $insert = [
                'id_solicitud_grc' => $id_solicitud,
                'nombre_emisor' => $row['nombre_emisor'],
                'rfc' => $row['rfc'],
                'importe' => floatval(str_replace(['$', ',', ' '], '', $row['importe'])),
                'fec_reg' => date('Y-m-d H:i:s'),
                'usu_reg' => $session->get('id_usuario')
            ];
           
            $res = $this->globals->saveTabla($insert, ['tabla' => 'solicitud_grc_comprobacion', "editar" => false], $dataBitacora);
            //die( var_dump( $res ) );
            if ($res->error) {
                $error = true;
            }
        }

        if (!$error) {
            // Actualizar estatus de solicitud a 3 (Comprobado) y registrar quien actualizó
            $dataUpdate = [
                'id_estatus' => 3,
                'no_consecutivo' => $data['folioCompleto'],
                'usu_act' => $session->get('id_usuario'),
                'fec_act' => date('Y-m-d H:i:s')
            ]; 
            $this->globals->saveTabla($dataUpdate, ['tabla' => 'solicitud_grc', 'editar' => true, 'idEditar' => ['id_solicitud_grc' => $id_solicitud]], $dataBitacora);

            $response->error = false;
            $response->respuesta = "Comprobación guardada correctamente";
        } else {
            $response->respuesta = "Ocurrió un error al guardar algunos comprobantes. Verifique la tabla solicitud_grc_comprobacion.";
        }

        return $this->respond($response);
    }

    public function guardaFormatoMateriales()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
       // $archivos_post = $this->request->getFiles();
        
        // 1. Validaciones Básicas
        if (isset($data['no_consecutivo']) && empty($data['no_consecutivo'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el no_consecutivo";
            return $this->respond($response);
        }
           
        $dataInsert = [
            'no_consecutivo' => $data['folioCompleto'],
            'nombre_proveedor_1' => $data['nombre_proveedor_1'],
            'rfc_proveedor' => $data['rfc_proveedor'],
            'nombre_proveedor_2' => $data['nombre_proveedor_2'],
            'no_cuenta' => $data['no_cuenta'],
            'banco' => $data['banco'],
            'clabe' => $data['clabe'],
            'clausula' => $data['clausula'],
            'no_proveedor' => $data['no_proveedor'],
            'fecha_tramite' => $data['fecha_tramite'],
            'nombre_responsable_2' => $data['nombre_responsable_2'], 
            'nombre_responsable' => $data['nombre_responsable_1'], 
            'cargo_responsable_2' => $data['cargo_responsable_2'], 
            'cargo_responsable' => $data['cargo_responsable_1'], 
            'cargo_director_general' => $data['cargo_director_general'], 
            'nombre_director_general' => $data['nombre_director_general'],
            'importe_total_num' => $data['importe_total_num'],
            'nombre_autoriza' => $data['nombre_autoriza'],
            'cargo_autoriza' => $data['cargo_autoriza'],
            'no_reserva' => $data['no_reserva_visual'] ?? 0,
            'no_convenio' => $data['no_convenio'] ?? '',
            'importe_letra' => $data['importe_letra'] ?? '',
            'concepto' => $data['concepto'] ?? ''
        ];

        $dataInsert['tipo_formato'] = isset($data['tipo_formato']) && !empty($data['tipo_formato']) ? $data['tipo_formato'] : 'PT';

        if(isset($data['es2025']) && $data['es2025']){
            $dataInsert['tipo_formato'] = 'REFRENDO';
        }
       
        $id_registro_pt = null;
        if($data['editar'] == 1 && isset($data['id_formulario_pt'])){
            $id_registro_pt = $data['id_formulario_pt'];
            $dataConfig = ["tabla" => "formulario_pt", "editar" => true, "idEditar" => ['id_formulario_pt' => $id_registro_pt]];
            $dataInsert['usu_act'] = $session->get('id_usuario');
            $dataInsert['fec_act'] = date('Y-m-d H:i:s');
            $responseMain = $this->globals->saveTabla($dataInsert, $dataConfig, ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaFormatoPT_Upd']);
        } else {
            $dataConfig = ["tabla" => "formulario_pt", "editar" => false];
            $dataInsert['usu_reg'] = $session->get('id_usuario');
            $dataInsert['fec_reg'] = date('Y-m-d H:i:s');
            $responseMain = $this->globals->saveTabla($dataInsert, $dataConfig, ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaFormatoPT_Ins']);
            $id_registro_pt = $responseMain->idRegistro;
        }
       
         if($data['editar'] == 1 && isset($data['id_formulario_pt'])){
          //  $filasActuales = $this->globals->getTabla(['tabla' => 'manual_factura', 'where' => ['id_registro_pt' => $id_registro_pt, 'visible' => 1]]);

    
                   $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaFormatoPT_Upd'];
                  $res =  $this->globals->saveTabla(
                        ['visible' => 0, 'usu_act' => $session->get('id_usuario'), 'fec_act' => date('Y-m-d H:i:s')], 
                        ["tabla" => "manual_factura", "editar" => true, "idEditar" => ['id_registro_pt' => $data['id_formulario_pt']]], 
                        $dataBitacora
                    );
                
                //var_dump($res);
            
        }

        // Arrays de inputs
        if(isset($data['no_comprobante']) && is_array($data['no_comprobante'])){
            for($i=0; $i < count($data['no_comprobante']); $i++){
                 // Skip empty rows if necessary, or just save empty strings as user entered 
                
                $pdfPath = null;
                $xmlPath = null;
                
                // Handle File Uploads (PDF/XML)
                if(isset($data['row_index'][$i])){
                    $rIdx = $data['row_index'][$i];
                    
                    // FALLBACK a los archivos actuales si estamos editando
                    $currentPdfName = "pdf_current_" . $rIdx;
                    $currentXmlName = "xml_current_" . $rIdx;
                    
                    if (isset($data[$currentPdfName]) && is_array($data[$currentPdfName]) && !empty($data[$currentPdfName][0])) {
                        $pdfPath = $data[$currentPdfName][0];
                    }
                    if (isset($data[$currentXmlName]) && is_array($data[$currentXmlName]) && !empty($data[$currentXmlName][0])) {
                        $xmlPath = $data[$currentXmlName][0];
                    }

                    // 1. Process PDF
                    $inputNamePdf = 'pdf_pt_' . $rIdx;
                    if(isset($_FILES[$inputNamePdf])){
                        $files = $_FILES[$inputNamePdf];
                        // With multiple, it is $_FILES['inputName']['name'][0]... but we expect single file per type per row usually, 
                        // but input name is array [] so it comes as array. We take the first one or loop.
                        // Given input name="pdf_pt_X[]", $_FILES['pdf_pt_X']['name'][0] is the file.
                        
                        $countFiles = count($files['name']);
                        for($f=0; $f < $countFiles; $f++){
                            if($files['error'][$f] == 0){
                                $tmpName = $files['tmp_name'][$f];
                                $name = $files['name'][$f];
                                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                
                                if($ext == 'pdf'){
                                    $uploadedPdfPath = $this->uploadManualFacturaFileToS3($tmpName, $ext, $id_registro_pt, $rIdx, 'PDF', 'PT');
                                    if($uploadedPdfPath){
                                        $pdfPath = $uploadedPdfPath;
                                    }
                                }
                            }
                        }
                    }

                    // 2. Process XML
                    $inputNameXml = 'xml_pt_' . $rIdx;
                    if(isset($_FILES[$inputNameXml])){
                        $files = $_FILES[$inputNameXml];
                         $countFiles = count($files['name']);
                        for($f=0; $f < $countFiles; $f++){
                            if($files['error'][$f] == 0){
                                $tmpName = $files['tmp_name'][$f];
                                $name = $files['name'][$f];
                                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                
                                if($ext == 'xml'){
                                    $uploadedXmlPath = $this->uploadManualFacturaFileToS3($tmpName, $ext, $id_registro_pt, $rIdx, 'XML', 'PT');
                                    if($uploadedXmlPath){
                                        $xmlPath = $uploadedXmlPath;
                                    }
                                }
                            }
                        }
                    }
                }

                $dataFila = [
                      'id_registro_pt' => $id_registro_pt,
                      //'id_presupuesto' => 0, 
                      'no_comprobante' => $data['no_comprobante'][$i], 
                      'importe' => $data['importe'][$i],
                      'proyecto' =>  $data['proyecto_meta'][$i],
                      'partida' => $data['no_partida'][$i],
                      'comision' => isset($data['comision'][$i]) ? trim($data['comision'][$i]) : '',
                      'concepto_gasto' => isset($data['concepto_gasto'][$i]) ? trim($data['concepto_gasto'][$i]) : '',
                      'fechas' => isset($data['fechas'][$i]) ? trim($data['fechas'][$i]) : '',
                      'usu_reg' => $session->get('id_usuario'),
                      'fec_reg' => date('Y-m-d H:i:s')
                ];
                
                if($pdfPath) $dataFila['pdf'] = $pdfPath;
                if($xmlPath) $dataFila['xml'] = $xmlPath;
              $response =  $this->globals->saveTabla($dataFila, ["tabla" => "manual_factura", "editar" => false], []);
            }
        }

       if($response->error){
           $response->error = true;
           $response->respuesta = "Error|".$response->respuesta;
           return $this->respond($response);
       }

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaConvenio'];

      //  die(); APL110943

        $response->error = false;
        $response->respuesta = "Éxito|La información se guardó correctamente.";



        return $this->respond($response);
    }


    public function guardaFormatoPT()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
       // $archivos_post = $this->request->getFiles();
        
        // 1. Validaciones Básicas
        if (isset($data['no_consecutivo']) && empty($data['no_consecutivo'])) {
            $response->error = true;
            $response->respuesta = "Es requerido el no_consecutivo";
            return $this->respond($response);
        }
           
   


      //validar que no se repita el no_consecutivo
      if($data['editar'] != 1 ){
        $existe = $this->globals->getTabla(['tabla' => 'formulario_pt', 'where' => ['no_consecutivo' => $data['folioCompleto'], 'visible' => 1]]);
            if(!$existe->error && !empty($existe->data)){
                $usuario = $this->globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['id_usuario' => $existe->data[0]->usu_reg]])->data[0]->nombre_completo;
                $response->error = true;
                $response->respuesta = "El No. Consecutivo ya existe, creado por: ".$usuario;
                return $this->respond($response);
            }
      }
   

        
       
        $dataInsert = [
            'no_consecutivo' => $data['folioCompleto'],
            'nombre_proveedor_1' => $data['nombre_proveedor_1'],
            'rfc_proveedor' => $data['rfc_proveedor'],
            'nombre_proveedor_2' => $data['nombre_proveedor_2'],
            'no_cuenta' => $data['no_cuenta'],
            'banco' => $data['banco'],
            'clabe' => $data['clabe'],
            'clausula' => $data['clausula'],
            'no_proveedor' => $data['no_proveedor'],
            'fecha_tramite' => $data['fecha_tramite'],
            'nombre_responsable_2' => $data['nombre_responsable_2'], 
            'nombre_responsable' => $data['nombre_responsable_1'], 
            'cargo_responsable_2' => $data['cargo_responsable_2'], 
            'cargo_responsable' => $data['cargo_responsable_1'], 
            'cargo_director_general' => $data['cargo_director_general'], 
            'nombre_director_general' => $data['nombre_director_general'],
            'importe_total_num' => $data['importe_total_num'],
            'nombre_autoriza' => $data['nombre_autoriza'],
            'cargo_autoriza' => $data['cargo_autoriza'],
            'no_reserva' => $data['no_reserva_visual'] ?? 0,
            'no_convenio' => $data['no_convenio'] ?? '',
            'importe_letra' => $data['importe_letra'] ?? '',
            'concepto' => $data['concepto'] ?? ''
        ];

        $dataInsert['tipo_formato'] = isset($data['tipo_formato']) && !empty($data['tipo_formato']) ? $data['tipo_formato'] : 'PT';

        if(isset($data['es2025']) && $data['es2025']){
            $dataInsert['tipo_formato'] = 'REFRENDO';
        }
        if(isset($data['es2025']) && !$data['es2025'] && in_array($session->get('id_usuario'), [14,80,17, 59, 11, 38])){
            $dataInsert['promo'] = 1;
        }
       
        $id_registro_pt = null;
        if($data['editar'] == 1 && isset($data['id_formulario_pt'])){
            $id_registro_pt = $data['id_formulario_pt'];
            $dataConfig = ["tabla" => "formulario_pt", "editar" => true, "idEditar" => ['id_formulario_pt' => $id_registro_pt]];
            $dataInsert['usu_act'] = $session->get('id_usuario');
            $dataInsert['fec_act'] = date('Y-m-d H:i:s');
            $responseMain = $this->globals->saveTabla($dataInsert, $dataConfig, ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaFormatoPT_Upd']);
        } else {
            $dataConfig = ["tabla" => "formulario_pt", "editar" => false];
            $dataInsert['usu_reg'] = $session->get('id_usuario');
            $dataInsert['fec_reg'] = date('Y-m-d H:i:s');
            $responseMain = $this->globals->saveTabla($dataInsert, $dataConfig, ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaFormatoPT_Ins']);
            $id_registro_pt = $responseMain->idRegistro;
        }
       
         if($data['editar'] == 1 && isset($data['id_formulario_pt'])){
          //  $filasActuales = $this->globals->getTabla(['tabla' => 'manual_factura', 'where' => ['id_registro_pt' => $id_registro_pt, 'visible' => 1]]);

    
                   $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaFormatoPT_Upd'];
                  $res =  $this->globals->saveTabla(
                        ['visible' => 0, 'usu_act' => $session->get('id_usuario'), 'fec_act' => date('Y-m-d H:i:s')], 
                        ["tabla" => "manual_factura", "editar" => true, "idEditar" => ['id_registro_pt' => $data['id_formulario_pt']]], 
                        $dataBitacora
                    );
                
                //var_dump($res);
            
        }

        // Arrays de inputs
        if(isset($data['no_comprobante']) && is_array($data['no_comprobante'])){
            for($i=0; $i < count($data['no_comprobante']); $i++){
                 // Skip empty rows if necessary, or just save empty strings as user entered 
                
                $pdfPath = null;
                $xmlPath = null;
                
                // Handle File Uploads (PDF/XML)
                if(isset($data['row_index'][$i])){
                    $rIdx = $data['row_index'][$i];
                    
                    // FALLBACK a los archivos actuales si estamos editando
                    $currentPdfName = "pdf_current_" . $rIdx;
                    $currentXmlName = "xml_current_" . $rIdx;
                    
                    if (isset($data[$currentPdfName]) && is_array($data[$currentPdfName]) && !empty($data[$currentPdfName][0])) {
                        $pdfPath = $data[$currentPdfName][0];
                    }
                    if (isset($data[$currentXmlName]) && is_array($data[$currentXmlName]) && !empty($data[$currentXmlName][0])) {
                        $xmlPath = $data[$currentXmlName][0];
                    }

                    // 1. Process PDF
                    $inputNamePdf = 'pdf_pt_' . $rIdx;
                    if(isset($_FILES[$inputNamePdf])){
                        $files = $_FILES[$inputNamePdf];
                        // With multiple, it is $_FILES['inputName']['name'][0]... but we expect single file per type per row usually, 
                        // but input name is array [] so it comes as array. We take the first one or loop.
                        // Given input name="pdf_pt_X[]", $_FILES['pdf_pt_X']['name'][0] is the file.
                        
                        $countFiles = count($files['name']);
                        for($f=0; $f < $countFiles; $f++){
                            if($files['error'][$f] == 0){
                                $tmpName = $files['tmp_name'][$f];
                                $name = $files['name'][$f];
                                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                
                                if($ext == 'pdf'){
                                    $uploadedPdfPath = $this->uploadManualFacturaFileToS3($tmpName, $ext, $id_registro_pt, $rIdx, 'PDF', 'PT');
                                    if($uploadedPdfPath){
                                        $pdfPath = $uploadedPdfPath;
                                    }
                                }
                            }
                        }
                    }

                    // 2. Process XML
                    $inputNameXml = 'xml_pt_' . $rIdx;
                    if(isset($_FILES[$inputNameXml])){
                        $files = $_FILES[$inputNameXml];
                         $countFiles = count($files['name']);
                        for($f=0; $f < $countFiles; $f++){
                            if($files['error'][$f] == 0){
                                $tmpName = $files['tmp_name'][$f];
                                $name = $files['name'][$f];
                                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                
                                if($ext == 'xml'){
                                    $uploadedXmlPath = $this->uploadManualFacturaFileToS3($tmpName, $ext, $id_registro_pt, $rIdx, 'XML', 'PT');
                                    if($uploadedXmlPath){
                                        $xmlPath = $uploadedXmlPath;
                                    }
                                }
                            }
                        }
                    }
                }

                $dataFila = [
                      'id_registro_pt' => $id_registro_pt,
                      //'id_presupuesto' => 0, 
                      'no_comprobante' => $data['no_comprobante'][$i], 
                      'importe' => $data['importe'][$i],
                      'proyecto' =>  $data['proyecto_meta'][$i],
                      'partida' => $data['no_partida'][$i],
                      'comision' => isset($data['comision'][$i]) ? trim($data['comision'][$i]) : '',
                      'concepto_gasto' => isset($data['concepto_gasto'][$i]) ? trim($data['concepto_gasto'][$i]) : '',
                      'fechas' => isset($data['fechas'][$i]) ? trim($data['fechas'][$i]) : '',
                      'usu_reg' => $session->get('id_usuario'),
                      'fec_reg' => date('Y-m-d H:i:s')
                ];
                
                if($pdfPath) $dataFila['pdf'] = $pdfPath;
                if($xmlPath) $dataFila['xml'] = $xmlPath;
              $response =  $this->globals->saveTabla($dataFila, ["tabla" => "manual_factura", "editar" => false], []);
            }
        }

       if($response->error){
           $response->error = true;
           $response->respuesta = "Error|".$response->respuesta;
           return $this->respond($response);
       }

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaConvenio'];
       //cambiar estatus a 4
       if($data['editar'] != 1){
            $res = $this->globals->saveTabla(['id_estatus' => 4], ["tabla" => "reserva", "editar" => true, 'idEditar' => ['id_reserva' => $data['id_reserva']]], $dataBitacora);
       }

      //  die(); APL110943

        $response->error = false;
        $response->respuesta = "Éxito|La información se guardó correctamente.";

        $esEdicion = (string) ($data['editar'] ?? '') === '1';
        $esRefrendo2025 = filter_var($data['es2025'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $tipoFormato = strtoupper((string) ($data['tipo_formato'] ?? 'PT'));
        $debeEnviarCorreoFacturasPt = !$esEdicion
            && !$esRefrendo2025
            && (int) $session->get('id_usuario') !== 1
            && $tipoFormato === 'PT';

        if ($debeEnviarCorreoFacturasPt) {
            $tempFilesToDelete = [];

            try {
                $email = \Config\Services::email();
                $nombreUsuario = $session->get('nombre_completo');
                $filesToAttach = [];
                $activeRows = $this->globals->getTabla(['tabla' => 'manual_factura', 'where' => ['id_registro_pt' => $id_registro_pt, 'visible' => 1]]);

                if (isset($activeRows->data)) {
                    foreach ($activeRows->data as $row) {
                        if (!empty($row->pdf)) {
                            $resolvedPdfPath = $this->resolveManualFacturaFilePath($row->pdf, 'mail_pdf_');
                            if ($resolvedPdfPath) {
                                $filesToAttach[] = $resolvedPdfPath;
                                if (strpos($row->pdf, 'FACTURAS/') === 0) {
                                    $tempFilesToDelete[] = $resolvedPdfPath;
                                }
                            }
                        }

                        if (!empty($row->xml)) {
                            $resolvedXmlPath = $this->resolveManualFacturaFilePath($row->xml, 'mail_xml_');
                            if ($resolvedXmlPath) {
                                $filesToAttach[] = $resolvedXmlPath;
                                if (strpos($row->xml, 'FACTURAS/') === 0) {
                                    $tempFilesToDelete[] = $resolvedXmlPath;
                                }
                            }
                        }
                    }
                }

                if (!empty($filesToAttach)) {
                    $email->setFrom('a.palafox@guanajuato.gob.mx', $nombreUsuario);
                    $email->setTo('dasedetur@guanajuato.gob.mx');
                    $email->setCC($session->get('correo'));

                    $subject = 'Registro Formato ' . $dataInsert['no_consecutivo'];
                    $body = 'Estimado usuario,<br><br>';
                    $body .= 'Se ha realizado el registro del <strong>Formato PT</strong> con consecutivo <strong>' . $dataInsert['no_consecutivo'] . '</strong>.<br>';
                    $body .= 'Registrado por: <strong>' . $nombreUsuario . '</strong>.<br><br>';
                    $body .= 'Se adjuntan los archivos correspondientes (PDF/XML).<br>';
                    $body .= '<br>Saludos cordiales.';

                    $email->setSubject($subject);
                    $email->setMessage($body);

                    foreach ($filesToAttach as $filePath) {
                        $email->attach($filePath);
                    }

                    $email->send();
                }
            } catch (\Throwable $e) {
                log_message('error', 'Error al enviar correo de facturas PT: ' . $e->getMessage());
            } finally {
                foreach ($tempFilesToDelete as $tempFile) {
                    @unlink($tempFile);
                }
            }
        }

        return $this->respond($response);
    }

    public function guardaConvenio()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $this->globals = new Mglobal();
        $data = $this->request->getPost();

        // ✅ PK real
        $idActual = intval($data['id_material_promo'] ?? $data['id_convenio_promo'] ?? 0);
        $esNuevo  = ($idActual === 0);

        // =========================
        // VALIDACIONES BÁSICAS
        // =========================
        $convenio = trim((string)($data['convenio'] ?? ''));
        if ($convenio === '') {
            $response->error = true;
            $response->respuesta = "Error|El convenio es requerido";
            return $this->respond($response);
        }

        $monto = trim((string)($data['monto'] ?? ''));
        if ($monto === '') {
            $response->error = true;
            $response->respuesta = "Error|El monto es requerido";
            return $this->respond($response);
        }

        // =========================
        // VALIDACIÓN PROVEEDOR (HÍBRIDO)
        // =========================
        $idProveedor = $data['id_proveedor'] ?? null;
        $noProveedor = trim((string)($data['no_proveedor'] ?? ''));

        if (empty($idProveedor) && $noProveedor === '') {
            $response->error = true;
            $response->respuesta = "Error|Debe seleccionar o ingresar un proveedor";
            return $this->respond($response);
        }

        // =========================
        // BUSCAR PROVEEDOR
        // =========================
        if (!empty($idProveedor)) {
            $ExisteProveedor = $this->globals->getTabla([
                "tabla" => "proveedor",
                "where" => ["id_proveedor" => $idProveedor],
                "limit" => 1
            ]);
        } else {
            $ExisteProveedor = $this->globals->getTabla([
                "tabla" => "proveedor",
                "where" => ["no_proveedor" => $noProveedor],
                "limit" => 1
            ]);
        }

        if (empty($ExisteProveedor->data)) {
            $response->error = true;
            $response->respuesta = "Error|El proveedor no existe";
            return $this->respond($response);
        }

        // =========================
        // VALIDAR SI EL CONVENIO YA EXISTE (EXCLUYENDO EL MISMO ID)
        // =========================
        $ExisteConvenio = $this->globals->getTabla([
            "tabla" => "cat_convenio_promo",
            "where" => [
                "visible"  => 1,
                "convenio" => $convenio
            ]
        ]);

        if (!empty($ExisteConvenio->data)) {
            foreach ($ExisteConvenio->data as $row) {
                $otroId = intval($row->id_convenio_promo ?? 0);

                // Nuevo: cualquier match duplica
                // Editar: duplica solo si es otro registro
                if ($esNuevo || $otroId !== $idActual) {
                    $response->error = true;
                    $response->respuesta = "Error|El convenio ya existe";
                    return $this->respond($response);
                }
            }
        }

        // =========================
        // INSERTAR / EDITAR
        // =========================
        $dataSave = [
            'convenio'     => $convenio,
            'monto'        => $monto,
            'id_proveedor' => $ExisteProveedor->data[0]->id_proveedor,
            'visible'      => 1
        ];

        if ($esNuevo) {
            $dataSave['usu_reg'] = $session->get('id_usuario');
            $dataSave['fec_reg'] = date('Y-m-d H:i:s');

            $dataConfig = [
                "dataBase" => "susi",
                "tabla"    => "cat_convenio_promo",
                "editar"   => false
            ];

            $script = 'Agregar.php/guardaConvenio_Ins';
        } else {
            $dataSave['usu_act'] = $session->get('id_usuario');
            $dataSave['fec_act'] = date('Y-m-d H:i:s');

            $dataConfig = [
                "dataBase" => "susi",
                "tabla"    => "cat_convenio_promo",
                "editar"   => true,
                // ✅ PK correcto:
                "idEditar" => ["id_convenio_promo" => $idActual]
            ];

            $script = 'Agregar.php/guardaConvenio_Upd';
        }

        $res = $this->globals->saveTabla(
            $dataSave,
            $dataConfig,
            [
                'id_user' => $session->get('id_usuario'),
                'script'  => $script
            ]
        );

        // =========================
        // RESPUESTA FINAL
        // =========================
        if ($res && isset($res->error) && $res->error === false) {
            $nuevoId = $esNuevo ? intval($res->idRegistro ?? $res->insertId ?? 0) : $idActual;

            $response->error = false;
            $response->respuesta = "Éxito|Guardado correctamente";

            // ✅ devolver ambos por compat con vistas antiguas
            $response->id_convenio_promo = $nuevoId;
            $response->id_material_promo = $nuevoId; // compat
        } else {
            $response->error = true;
            $response->respuesta = "Error|Error al guardar: " . ($res->respuesta ?? 'desconocido');
            $response->debug = $res;
        }

        return $this->respond($response);
    }

    public function eliminarConvenio()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $this->globals = new Mglobal();
        $id = $this->request->getPost('id_material_promo');

        if ($id) {
            $dataUpdate = [
                'visible' => 0,
                'usu_act' => $session->get('id_usuario'),
                'fec_act' => date('Y-m-d H:i:s')
            ];
            
            $dataConfig = ["tabla" => "cat_convenio_promo", "editar" => true, "idEditar" => ['id_convenio_promo' => $id]];
            
            $res = $this->globals->saveTabla($dataUpdate, $dataConfig, ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/eliminarConvenio']);

            if (!$res->error) {
                $response->error = false;
                $response->respuesta = "Registro eliminado correctamente";
            } else {
                $response->error = true;
                $response->respuesta = "Error al eliminar";
            }
        } else {
            $response->error = true;
            $response->respuesta = "ID inválido";
        }

        return $this->respond($response);
    }
    public function guardaFormatoGO()
    {
        $session = \Config\Services::session();
        $this->globals = new Mglobal;
        $data = $this->request->getPost();
        
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "";
        
        // Validation (Basic)
        if(!isset($data['row_index'])){
             $response->respuesta = "Error|Debe agregar al menos un registro.";
             return $this->respond($response);
        }

        $folioFinal = isset($data['folioCompleto']) ? $data['folioCompleto'] : (isset($data['no_consecutivo']) ? $data['no_consecutivo'] : '');

        // Main Record (Reuse formulario_pt for now as structure is similar)
        // If user wants separate table later, we change this.
        $dataInsert = [
            'no_consecutivo' => $folioFinal,
            // Fallback for main provider if needed by not null constraint?
            'nombre_proveedor_1' => isset($data['proveedor_id'][0]) ? $data['proveedor_id'][0] : (isset($data['nombre_proveedor_1']) ? $data['nombre_proveedor_1'] : 0), 
            'no_cuenta' => isset($data['no_cuenta']) ? $data['no_cuenta'] : '', 
            'banco' => isset($data['banco']) ? $data['banco'] : '',
            'clabe' => isset($data['clabe']) ? $data['clabe'] : '',
            'clausula' => isset($data['clausula']) ? $data['clausula'] : '',
            'no_proveedor' => isset($data['no_proveedor']) ? $data['no_proveedor'] : '',
            'fecha_tramite' => isset($data['fecha_tramite']) ? $data['fecha_tramite'] : date('Y-m-d'),
            
            // Signatures
            'nombre_responsable_2' => $data['nombre_responsable_2'], 
            'nombre_responsable' => $data['nombre_responsable_1'], 
            'cargo_responsable_2' => $data['cargo_responsable_2'], 
            'cargo_responsable' => $data['cargo_responsable_1'], 
            'cargo_director_general' => isset($data['cargo_director_general']) ? $data['cargo_director_general'] : '', 
            'nombre_director_general' => isset($data['nombre_director_general']) ? $data['nombre_director_general'] : '',
            'nombre_autoriza' => $data['nombre_autoriza'],
            'cargo_autoriza' => $data['cargo_autoriza'],
            
            'importe_total_num' => $data['importe_total_num'],
            'no_reserva' => isset($data['no_reserva_visual']) ? $data['no_reserva_visual'] : 0,
            'no_convenio' => isset($data['no_convenio']) ? $data['no_convenio'] : '',
            'importe_letra' => isset($data['importe_letra']) ? $data['importe_letra'] : '',
            'concepto' => isset($data['concepto']) ? $data['concepto'] : '',
            'relacion' => isset($data['relacion_documentos']) ? $data['relacion_documentos'] : '',
            
            'tipo_formato' => 'GO' // Discriminator if using same table?
        ];
       
        $id_registro_pt = null;
        if(isset($data['editar']) && $data['editar'] == 1 && isset($data['id_formulario_pt'])){
            $id_registro_pt = $data['id_formulario_pt'];
            $dataConfig = ["tabla" => "formulario_pt", "editar" => true, "idEditar" => ['id_formulario_pt' => $id_registro_pt]];
            $dataInsert['usu_act'] = $session->get('id_usuario');
            $dataInsert['fec_act'] = date('Y-m-d H:i:s');
            $responseMain = $this->globals->saveTabla($dataInsert, $dataConfig, ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaFormatoGO_Upd']);
        } else {
            $dataConfig = ["tabla" => "formulario_pt", "editar" => false];
            $dataInsert['usu_reg'] = $session->get('id_usuario');
            $dataInsert['fec_reg'] = date('Y-m-d H:i:s');
            $responseMain = $this->globals->saveTabla($dataInsert, $dataConfig, ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaFormatoGO_Ins']);
            if(isset($responseMain->idRegistro)) $id_registro_pt = $responseMain->idRegistro;
        }
       
        if(isset($responseMain->error) && $responseMain->error){
            $response->error = true;
            $response->respuesta = "Error Principal|".$responseMain->respuesta;
            return $this->respond($response);
        }

        // Items
        if(isset($data['editar']) && $data['editar'] == 1 && isset($data['id_formulario_pt'])){
             // Soft delete old items
             $this->globals->saveTabla(
                ['visible' => 0, 'usu_act' => $session->get('id_usuario'), 'fec_act' => date('Y-m-d H:i:s')], 
                ["tabla" => "manual_factura", "editar" => true, "idEditar" => ['id_registro_pt' => $data['id_formulario_pt']]], 
                ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaFormatoGO_Upd']
            );
            
             // Soft delete old viaticos
             $this->globals->saveTabla(
                ['visible' => 0, 'usu_act' => $session->get('id_usuario'), 'fec_act' => date('Y-m-d H:i:s')], 
                ["tabla" => "viaticos_go", "editar" => true, "idEditar" => ['id_registro_go' => $data['id_formulario_pt']]], 
                ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaFormatoGO_Upd_Viat']
            );
        }

        if(isset($data['no_comprobante']) && is_array($data['no_comprobante'])){
            for($i=0; $i < count($data['no_comprobante']); $i++){
                
                $pdfPath = null;
                $xmlPath = null;
                $xmlContentForExtraction = null;
                
                if(isset($data['row_index'][$i])){
                    $rIdx = $data['row_index'][$i];
                    
                    // Default to existing file if present
                    if(isset($data['pdf_current_' . $rIdx][0]) && !empty($data['pdf_current_' . $rIdx][0])){
                        $pdfPath = $data['pdf_current_' . $rIdx][0];
                    }
                     if(isset($data['xml_current_' . $rIdx][0]) && !empty($data['xml_current_' . $rIdx][0])){
                        $xmlPath = $data['xml_current_' . $rIdx][0];
                    }

                    // PDF
                    $inputNamePdf = 'pdf_pt_' . $rIdx;
                    if(isset($_FILES[$inputNamePdf])){
                        $files = $_FILES[$inputNamePdf];
                        // Handle potential array structure if multiple files, but here expecting one per input name
                        // Check if name is array
                        if(is_array($files['name'])){ 
                             $countFiles = count($files['name']);
                             for($f=0; $f < $countFiles; $f++){
                                if($files['error'][$f] == 0 && strtolower(pathinfo($files['name'][$f], PATHINFO_EXTENSION)) == 'pdf'){
                                    $tmpName = $files['tmp_name'][$f];
                                    $ext = pathinfo($files['name'][$f], PATHINFO_EXTENSION);
                                    $uploadedPdfPath = $this->uploadManualFacturaFileToS3($tmpName, $ext, $id_registro_pt, $rIdx, 'PDF', 'GO');
                                    if($uploadedPdfPath){
                                        $pdfPath = $uploadedPdfPath;
                                    }
                                }
                             }
                        } 
                    }

                    // XML
                    $inputNameXml = 'xml_pt_' . $rIdx;
                    if(isset($_FILES[$inputNameXml])){
                        $files = $_FILES[$inputNameXml];
                         if(is_array($files['name'])){ 
                            $countFiles = count($files['name']);
                            for($f=0; $f < $countFiles; $f++){
                                if($files['error'][$f] == 0 && strtolower(pathinfo($files['name'][$f], PATHINFO_EXTENSION)) == 'xml'){
                                    $tmpName = $files['tmp_name'][$f];
                                    $ext = pathinfo($files['name'][$f], PATHINFO_EXTENSION);
                                    $xmlContentForExtraction = @file_get_contents($tmpName) ?: null;
                                    $uploadedXmlPath = $this->uploadManualFacturaFileToS3($tmpName, $ext, $id_registro_pt, $rIdx, 'XML', 'GO');
                                    if($uploadedXmlPath){
                                        $xmlPath = $uploadedXmlPath;
                                    }
                                }
                            }
                         }
                    }
                }

                $subTotalFormulario = isset($data['sub_total'][$i]) ? (float) str_replace(',', '', $data['sub_total'][$i]) : 0;

                $dataFila = [
                      'id_registro_pt' => $id_registro_pt,
                      'no_comprobante' => $data['no_comprobante'][$i], 
                      'importe' => $data['importe'][$i],
                      'proyecto' =>  $data['proyecto_meta'][$i],
                      'partida' => $data['no_partida'][$i],
                      'comision' => isset($data['comision'][$i]) ? trim($data['comision'][$i]) : '',
                      'concepto_gasto' => isset($data['concepto_gasto'][$i]) ? trim($data['concepto_gasto'][$i]) : '',
                      'responsable' => isset($data['responsable_gasto'][$i]) ? trim($data['responsable_gasto'][$i]) : '',
                      'fechas' => isset($data['fechas'][$i]) ? trim($data['fechas'][$i]) : '',
                      'propinas' => isset($data['propinas'][$i]) ? trim((float)$data['propinas'][$i]) : '',
                      
                      // Provider Info (For GO)
                      'rfc' => isset($data['proveedor_rfc'][$i]) ? $data['proveedor_rfc'][$i] : '', 
                      'proveedor' => isset($data['proveedor_nombre'][$i]) ? $data['proveedor_nombre'][$i] : '',
                    //  'id_proveedor' => (isset($data['proveedor_id'][$i]) && is_numeric($data['proveedor_id'][$i])) ? $data['proveedor_id'][$i] : 0,

                      'usu_reg' => $session->get('id_usuario'),
                      'fec_reg' => date('Y-m-d H:i:s')
                ];
                
                if($pdfPath) $dataFila['pdf'] = $pdfPath;
                if($xmlPath) $dataFila['xml'] = $xmlPath;

                // Extraccion de ISR y Retenciones Locales desde XML
                $isr_xml = 0.00;
                $il_xml  = 0.00;
                $subtotal_xml = 0.00;
                if (empty($xmlContentForExtraction) && !empty($xmlPath)) {
                    $resolvedXmlPath = $this->resolveManualFacturaFilePath($xmlPath, 'factura_xml_');
                    if ($resolvedXmlPath) {
                        $xmlContentForExtraction = @file_get_contents($resolvedXmlPath) ?: null;
                        if (strpos($xmlPath, 'FACTURAS/') === 0) {
                            @unlink($resolvedXmlPath);
                        }
                    }
                }

                $xmlTotals = $this->extractManualFacturaXmlTotals($xmlContentForExtraction);
                $isr_xml = $xmlTotals['isr'];
                $il_xml = $xmlTotals['impuesto_local'];
                $subtotal_xml = $xmlTotals['xml_subtotal'];

                $dataFila['isr'] = $isr_xml;
                $dataFila['impuesto_local'] = $il_xml;
                $dataFila['xml_subtotal'] = $subtotal_xml;

                if ($isr_xml > 0 || $il_xml > 0) {
                    $dataFila['sub_total'] = $subTotalFormulario > 0 ? $subTotalFormulario : $subtotal_xml;
                }
                
                $resItem = $this->globals->saveTabla($dataFila, ["tabla" => "manual_factura", "editar" => false], []);
            }
        } // End of manual_factura loop

        // --- SAVE GLOBAL VIATICOS ---
        if(isset($data['viaticos_global_json']) && !empty($data['viaticos_global_json'])) {
            $viaticosJson = $data['viaticos_global_json'];
            
            // Handle if it's sent as an array with one element (depending on form encoding) or just a string
            if (is_array($viaticosJson)) {
                $viaticosJson = $viaticosJson[0]; 
            }
            
            $viaticosArr = json_decode($viaticosJson, true);
           
            if(is_array($viaticosArr) && count($viaticosArr) > 0){
   
                foreach($viaticosArr as $viat){
                    $datos_viatico = [
                        'id_registro_go' => $id_registro_pt, // Main Header ID
                        'id_presupuesto' => 0, // Manual form doesn't track budget ID strictly
                        'nombre'         => $viat['nombre'],
                        'rfc'            => isset($viat['rfc']) ? $viat['rfc'] : '', 
                        'importe'        => $viat['monto'],
                        'id_identificador' => 0, // No specific item, global
                        'usu_reg'        => $session->get('id_usuario'),
                        'fec_reg'        => date('Y-m-d H:i:s')
                    ];

               $this->globals->saveTabla($datos_viatico, ["tabla" => "viaticos_go", "editar" => false], ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaFormatoGO']);
                }
            }
        }
        
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaFormatoGO'];
        // Update status of reserva if needed
        if(isset($data['id_reserva'])){
             $this->globals->saveTabla(['id_estatus' => 4], ["tabla" => "reserva", "editar" => true, 'idEditar' => ['id_reserva' => $data['id_reserva']]], $dataBitacora);
        }

        $response->error = false;
        $response->respuesta = "Éxito|La información de Gastos de Operación se guardó correctamente.";

        // --- EMAIL SENDING LOGIC ---
      //  var_dump($data);
        if($session->get('id_usuario') != 1){
            if($data['editar'] == 0 || $data['editar'] == ''){
                try {
                    $email = \Config\Services::email();
                    $globals = new Mglobal; 
                    
                    // 1. Get User Name
                    $nombreUsuario = $session->get('nombre_completo');
                    if(!$nombreUsuario){
                        // Fallback: Query if not in session
                        $u = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['id_usuario' => $session->get('id_usuario')]]);
                        if(isset($u->data[0])) $nombreUsuario = $u->data[0]->nombre_completo;
                        else $nombreUsuario = "Usuario System";
                    }
                    
                    // 2. Get Files to Attach
                    $filesToAttach = [];
                    $tempFilesToDelete = [];
                    // Query active rows for this record
                    $activeRows = $globals->getTabla(['tabla' => 'manual_factura', 'where' => ['id_registro_pt' => $id_registro_pt, 'visible' => 1]]);
                    
                    if(isset($activeRows->data)){
                        foreach($activeRows->data as $row){
                            if(!empty($row->pdf)){
                                $resolvedPdfPath = $this->resolveManualFacturaFilePath($row->pdf, 'mail_pdf_');
                                if($resolvedPdfPath){
                                    $filesToAttach[] = $resolvedPdfPath;
                                    if(strpos($row->pdf, 'FACTURAS/') === 0){
                                        $tempFilesToDelete[] = $resolvedPdfPath;
                                    }
                                }
                            }
                            if(!empty($row->xml)){
                                $resolvedXmlPath = $this->resolveManualFacturaFilePath($row->xml, 'mail_xml_');
                                if($resolvedXmlPath){
                                    $filesToAttach[] = $resolvedXmlPath;
                                    if(strpos($row->xml, 'FACTURAS/') === 0){
                                        $tempFilesToDelete[] = $resolvedXmlPath;
                                    }
                                }
                            }
                        }
                    }

                    // 3. Configure Email
                    $email->setFrom('a.palafox@guanajuato.gob.mx', $nombreUsuario); 
                    $email->setTo('dasedetur@guanajuato.gob.mx');
                    //$email->setTo('palafox.marin31@hotmail.com');
                    $email->setCC($session->get('correo')); 
                    
                    $subject = 'Registro Formato GO ' . $folioFinal;
                    $body = 'Estimado usuario,<br><br>';
                    $body .= 'Se ha realizado el registro del <strong>Formato GO (Gastos de Operación)</strong> con consecutivo <strong>' . $folioFinal . '</strong>.<br>';
                    $body .= 'Registrado por: <strong>' . $nombreUsuario . '</strong>.<br><br>';
                    $body .= 'Se adjuntan los archivos correspondientes (PDF/XML).<br>';
                    $body .= '<br>Saludos cordiales.';

                    $email->setSubject($subject);
                    $email->setMessage($body);

                    // 4. Attach Files
                    foreach($filesToAttach as $filePath){
                        $email->attach($filePath);
                    }

                    // 5. Send
                    if($email->send()){
                        // Success logging or modify response if needed
                    } else {
                    }

                    foreach($tempFilesToDelete as $tempFile){
                        @unlink($tempFile);
                    }

                } catch (\Exception $e) {
                    // Do not fail the main save operation if email fails
                }
            }
        }



        //die("llega");
        return $this->respond($response);
    }

    /**
     * Ejemplo de apartado para realizar guardado y consumo del BUCKET S3
     */
    public function pruebaS3()
    {
        $s3 = new \App\Libraries\S3Service();

        // 1. Ejemplo de Guardado
        $archivo = $this->request->getFile('archivo_s3');
        if ($archivo && $archivo->isValid() && !$archivo->hasMoved()) {
            $nuevoNombre = $archivo->getRandomName();
            $rutaTemporal = $archivo->getTempName();

            // Guardar en bucket (ej. en carpeta 'documentos/')
            $urlPublica = $s3->uploadFile($rutaTemporal, 'documentos/' . $nuevoNombre);

            if ($urlPublica) {
                // 2. Ejemplo de Consumo
                // Obtener URL temporal firmada para visualizar o descargar
                $urlConsumo = $s3->getPresignedUrl('documentos/' . $nuevoNombre, '+1 hour');

                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Archivo guardado correctamente en S3',
                    'url_publica' => $urlPublica,                 // URL directa si el bucket fuera público
                    'url_temporal_consumo' => $urlConsumo         // URL segura de consumo
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'Error al subir archivo a AWS S3. Revisa logs de CodeIgniter.'
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'No se recibió un archivo válido ("archivo_s3") o hay error en la petición.'
        ]);
    }

    /**
     * Muestra el formulario para prueba de AWS S3
     */
    public function formBucketAws()
    {
        $session = \Config\Services::session();
        $data = array();
        $data['contentView'] = 'aws/vFormBucket';
        $this->_renderView($data);
    }

    public function listaBucketAws()
    {
        $session = \Config\Services::session();
        $Mglobal = new \App\Models\Mglobal();
        $s3 = new \App\Libraries\S3Service();

        $busqueda = trim((string) $this->request->getGet('busqueda'));
        $tipoArchivo = strtoupper(trim((string) $this->request->getGet('tipo_archivo')));

        $consulta = [
            'tabla' => 'bucketaws',
            'where' => [
                'visible' => 1
            ],
            'orderBy' => 'id_bucketaws DESC',
            'limit' => 10
        ];

        if ($busqueda !== '') {
            $consulta['orlike'] = [
                'nombre_archivo' => $busqueda,
                'descripcion' => $busqueda
            ];
        }

        $respuesta = $Mglobal->getTabla($consulta);
        $archivos = $respuesta->data ?? [];

        foreach ($archivos as $archivo) {
            $archivo->tipo_archivo = $this->obtenerTipoBucketAws($archivo->ruta_s3 ?? '');
            $archivo->url_descarga = !empty($archivo->ruta_s3) ? $s3->getPresignedUrl($archivo->ruta_s3, '+20 minutes') : null;
        }

        if ($tipoArchivo !== '') {
            $archivos = array_values(array_filter($archivos, function ($archivo) use ($tipoArchivo) {
                return ($archivo->tipo_archivo ?? '') === $tipoArchivo;
            }));
        }

        $data = [];
        $data['contentView'] = 'aws/vListaBucket';
        $data['archivos'] = $archivos;
        $data['filtros'] = [
            'busqueda' => $busqueda,
            'tipo_archivo' => $tipoArchivo
        ];
        $this->_renderView($data);
    }

    private function obtenerTipoBucketAws(string $rutaS3): string
    {
        if (strpos($rutaS3, 'media/imagenes/') === 0) {
            return 'IMG';
        }

        if (strpos($rutaS3, 'media/videos/') === 0) {
            return 'VIDEO';
        }

        if (strpos($rutaS3, 'media/audios/') === 0) {
            return 'AUDIO';
        }

        if (strpos($rutaS3, 'media/archivos/') === 0) {
            return 'ARCHIVO';
        }

        return 'OTRO';
    }

    /**
     * Procesa la subida y guardado de datos en la tabla `bucketaws`
     */
    public function guardarBucketAws()
    {
        $session = \Config\Services::session();
        $s3 = new \App\Libraries\S3Service();
        $Mglobal = new \App\Models\Mglobal();

        $post = $this->request->getPost();
        $archivo = $this->request->getFile('archivo_s3');

        $tiposArchivo = [
            'IMG' => [
                'carpeta' => 'imagenes',
                'extensiones' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'],
            ],
            'VIDEO' => [
                'carpeta' => 'videos',
                'extensiones' => ['mp4', 'mov', 'avi', 'mkv', 'webm'],
            ],
            'ARCHIVO' => [
                'carpeta' => 'archivos',
                'extensiones' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar'],
            ],
            'AUDIO' => [
                'carpeta' => 'audios',
                'extensiones' => ['mp3', 'wav', 'ogg', 'm4a'],
            ],
        ];

        $nombreArchivo = trim($post['nombre_archivo'] ?? '');
        $descripcion = trim($post['descripcion'] ?? '');
        $tipoSeleccionado = strtoupper(trim($post['tipo_archivo'] ?? ''));

        if ($nombreArchivo === '' || $descripcion === '' || $tipoSeleccionado === '') {
            $session->setFlashdata('error', 'Debes capturar el nombre del archivo, la descripcion y el tipo.');
            return redirect()->to(base_url('index.php/Agregar/formBucketAws'));
        }

        if (!isset($tiposArchivo[$tipoSeleccionado])) {
            $session->setFlashdata('error', 'El tipo de archivo seleccionado no es valido.');
            return redirect()->to(base_url('index.php/Agregar/formBucketAws'));
        }

        if (!$archivo || !$archivo->isValid() || $archivo->hasMoved()) {
            $session->setFlashdata('error', 'No se detecto un archivo valido para subir.');
            return redirect()->to(base_url('index.php/Agregar/formBucketAws'));
        }

        $extension = strtolower((string) $archivo->getClientExtension());
        $configuracionTipo = $tiposArchivo[$tipoSeleccionado];

        if (!in_array($extension, $configuracionTipo['extensiones'], true)) {
            $session->setFlashdata(
                'error',
                'El archivo seleccionado no coincide con el tipo indicado. Extensiones permitidas: ' . implode(', ', $configuracionTipo['extensiones'])
            );
            return redirect()->to(base_url('index.php/Agregar/formBucketAws'));
        }

        $carpetaS3 = 'media/' . $configuracionTipo['carpeta'];
        $nuevoNombre = $archivo->getRandomName();
        $rutaTemporal = $archivo->getTempName();
        $rutaS3 = $carpetaS3 . '/' . $nuevoNombre;
        $urlPublica = $s3->uploadFile($rutaTemporal, $rutaS3);

        if ($urlPublica) {
            $dataInsert = [
                'nombre_archivo' => $nombreArchivo,
                'descripcion'    => $descripcion,
                'usu_reg'        => $session->get('id_usuario'),
                'ruta_s3'        => $rutaS3
            ];

            $dataConfig = [
                'tabla' => 'bucketaws',
                'editar' => false
            ];

            $dataBitacora = [
                'id_user' => $session->get('id_usuario'),
                'script' => 'Agregar.php/guardarBucketAws'
            ];

            $Mglobal->saveTabla($dataInsert, $dataConfig, $dataBitacora);

            $session->setFlashdata('success', 'Archivo subido correctamente a la carpeta "' . $configuracionTipo['carpeta'] . '" en S3 y registrado en bucketaws.');
        } else {
            $session->setFlashdata('error', 'Error al subir el archivo al bucket de AWS.');
        }

        return redirect()->to(base_url('index.php/Agregar/formBucketAws'));
    }
}
