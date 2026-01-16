<?php
namespace App\Controllers;
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

    public function index()
    {

        $session = \Config\Services::session();
        $data = array();
        $data['scripts'] = array('principal');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vVacio';
        $this->_renderView($data);

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
            "id_estatus" => 1,
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
        $extension = $foto->getClientExtension();
        //$originalName = pathinfo($foto->getName(), PATHINFO_FILENAME);
       $exten = date('Ymd_His'); // "20241210_143025"
       $archivo = $session->usuario.'_'.$exten.'.' . $extension;

        $ruta_destino = FCPATH . 'assets/images/fotos/';

        $foto->move($ruta_destino, $archivo);
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
            $response->error = $res->error;
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
            "total_importe" => $data['total_importe'],
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
            if (count($data['proyecto']) === count($data['partida']) && count($data['partida']) === count($data['importe'])) {
                foreach ($data['proyecto'] as $index => $proyecto) {
                    // Solo agregar si todos los valores existen
                    if (!empty($data['proyecto']) && !empty($data['partida'][$index]) && !empty($data['importe'][$index])) {
                        $datosCombinados[] = [
                            'proyecto' => $proyecto,
                            'partida' => $data['partida'][$index],
                            'importe' => str_replace(',', '', $data['importe'][$index]),
                            'propina' => str_replace(',', '', $data['propina'][$index]) // Elimina comas del formato numérico
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
                    "importe" => $d['importe'],
                    "propina" => $d['propina'],
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
        $res = $this->enviarEmail(1);
      

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


        if (isset($data['no_convenio']) && empty($data['no_convenio'])) {
            $response->error = true;
            $response->respuesta = "El campo No. Convenio es requerido";
            return $this->respond($response);
        }
        $hoy = date("Y-m-d H:i:s");
        $folio = 'PT-' . date('YmdHis'); // Ejemplo: FOL-20250725133045

        $dataInsert = [
            "id_proveedor" => (int) $data['id_proveedor'],
            "total_importe" => $data['total_importe'],
            "id_proveedor_banco" => (int) $data['banco'],
            "fec_reg" => $hoy,
            "usu_reg" => $session->get('id_usuario'),
            "folio" => $folio
        ];
        if (!empty($ruta_relativa)) {
            $dataInsert['instrumento'] = $ruta_relativa;
            $dataInsert['ruta_absoluta'] = $ruta_absoluta;
            $dataInsert['no_convenio'] = $data['no_convenio'];
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
        $this->enviarEmail(0);
       

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

   //     $email->setTo('palafox.marin31@gmail.com'); // destinatario principal
        // $email->setCC(['palafox.marin@hotmail.com', 'palafox.marin31@gmail.com']); // copia visible
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
            "contrasenia" => md5($data['contrasenia']),
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

       /*$email->setTo([
            'agascag@guanajuato.gob.mx',
            'ccampos@guanajuato.gob.mx',
            'sandag@guanajuato.gob.mx',
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
        ]);*/
 
                $email->setTo([
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
                    'e.salazarmo@guanajuato.gob.mx',
                    'jacostap@guanajuato.gob.mx',
                    'jrojas@guanajuato.gob.mx',
                    'miguel.salazarc@guanajuato.gob.mx',
                    'mrcarballo@guanajuato.gob.mx',
                    'murrutiac@guanajuato.gob.mx',
                    'negonzalez@guanajuato.gob.mx',
                    'nlandin@guanajuato.gob.mx',
                    'orosas@guanajuato.gob.mx',
                    'tmares@guanajuato.gob.mx',
                    'pcortesvi@guanajuato.gob.mx',
                    'ilianacord@guanajuato.gob.mx',
                    'luis.perez@guanajuato.gob.mx',
                    'rgonzalezva@guanajuato.gob.mx',
                    'mascencio@guanajuato.gob.mx',
                    'jmazavala@guanajuato.gob.mx',
                    'lebalderas@guanajuato.gob.mx',
                    'rantonio@guanajuato.gob.mx',
                    'alvarezp@guanajuato.gob.mx',
                    'jrodriguezgo@guanajuato.gob.mx',
                ]);  
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
                            En caso de que aún no hayas realizado las <strong>justificaciones correspondientes a la quincena 01/2026</strong>, 
                            la cual comprende el periodo del <strong>01 al 15 de enero de 2026</strong>, 
                            tienes hasta el día <strong>viernes 23 de enero hasta las 16:00 hrs</strong> para realizarlas.
                        </p>

                        <div class="highlight-box">
                            <p style="font-size: 15px; line-height: 1.6; margin: 0;">
                                Para cualquier duda o aclaración, favor de comunicarse a la 
                                <strong>Coordinación de Recursos Humanos</strong> o 
                                <strong>Coordinación de Tecnologías de la Información</strong>.
                            </p>
                        </div>

                        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px;">
                            Le invitamos a revisar y validar sus incidencias correspondientes en el sistema SUSI.
                        </p>

                        <div style="text-align: center; margin: 30px 0;">
                            <a href="https://secturnet.guanajuato.gob.mx/susi/index.php/Principal/incidenciaSubordinado" class="btn" style="color: white; text-decoration: none;">
                                📋 Revisar Incidencias del Personal
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
            $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva', 'where' => ['visible' => 1]]);
        } else {
            $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva', 'where' => ['usu_reg' => $session->get('id_usuario'), 'visible' => 1 ]]);
        }
        //die( var_dump($reserva ) );
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
    public function listaReservaGO()
    {

        $session = \Config\Services::session();
        $globals = new Mglobal;
        if (in_array($session->get('id_perfil'), [1, 2])) {
            $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva_go', 'where' => ['visible' => 1]]);
        } else {
            $reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva_go', 'where' => ['usu_reg' => $session->get('id_usuario'), 'visible' => 1]]);
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
        $cat_usuario = $globals->getTabla(['tabla' => 'cat_usuario', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $cat_area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
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
        $data['cat_usuario'] = (!empty($cat_usuario->data)) ? $cat_usuario->data : [];
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
        $usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);

        $data['cat_perfil'] = (!empty($cat_perfil->data)) ? $cat_perfil->data : [];
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['usuario'] = (!empty($usuario->data)) ? $usuario->data : [];
        
        // Datos para edición
        $data['solicitud'] = $solicitud->data[0];
        $data['detalles'] = (!empty($detalles->data)) ? $detalles->data : [];
        $data['editar'] = true;

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
        // Convertir cantidad a letras
        $data['cantidad_letra'] = $this->numeroEnLetras($data['solicitud']->cantidad);

        // Fecha creación texto
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = strtotime($data['solicitud']->fec_reg);
        $data['fecha_texto'] = "Silao, Gto., a " . date('d', $fecha) . " de " . $meses[date('n', $fecha)-1] . " de " . date('Y', $fecha);

        $html = view('personal/vFormatoGRC', $data);
        $doc = 'assets/documentos/SOLICITUD_GRC.pdf';
        
        // Crear PDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_bottom' => 0,
            'format' => 'Letter'
        ]);

        if (file_exists(FCPATH . $doc)) {
            $mpdf->SetSourceFile(FCPATH . $doc);
            $tplId = $mpdf->ImportPage(1);
            $mpdf->UseTemplate($tplId);
        }

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

        $data['solicitud'] = $solicitud->data[0];
        $data['comprobaciones'] = (!empty($comprobaciones->data)) ? $comprobaciones->data : [];
        
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

    public function SolicitudContrato()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = array();
        
        // Cargar catalogos si es necesario, similar a otras vistas
        // Por ahora solo cargamos la vista básica
        $data['scripts'] = array('inicio'); // Asumiendo scripts estandar
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vSolicitudContrato';
        $this->_renderView($data);
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

        $data['solicitud'] = $solicitud->data[0];
        $data['pagos'] = (!empty($pagos->data)) ? $pagos->data : [];
        
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
        
        // Manejo de archivo
        $archivo_suficiencia = '';
        if($file = $this->request->getFile('archivo_suficiencia')) {
             if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'assets/uploads/contratos', $newName);
                $archivo_suficiencia = $newName;
             }
        }

        // Datos principales
        $dataInsert = [
            'usu_reg' => $session->id_usuario,
            'fec_reg' => date('Y-m-d H:i:s'),
            'responsable_proyecto' => $post['responsable_proyecto'],
            'responsable_seguimiento' => $post['responsable_seguimiento'],
            'enlace_comunicaciones' => $post['enlace_comunicaciones'],
            'proyecto' => $post['proyecto'],
            'partida' => $post['partida'],
            'clave_estandarizada' => $post['clave_estandarizada'],
            'archivo_suficiencia' => $archivo_suficiencia,
            'monto_total' => $post['monto_total'],
            'garantia' => $post['garantia'],
            'objeto_contrato' => $post['objeto_contrato'],
            'fecha_inicio' => $post['fecha_inicio'],
            'fecha_termino' => $post['fecha_termino'],
            'proveedor_nombre' => $post['proveedor_nombre'],
            'proveedor_domicilio' => $post['proveedor_domicilio'],
            'proveedor_rfc' => $post['proveedor_rfc'],
            'proveedor_cedula' => $post['proveedor_cedula'],
            'proveedor_representante' => $post['proveedor_representante'],
            'proveedor_correo' => $post['proveedor_correo'],
            'visible' => 1
        ];

        // Guardar Encabezado
        // NOTA: Asumimos tabla solicitud_contrato. Si no existe, fallará y el usuario reportará.
        $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Agregar.php/guardaSolicitudContrato'];
        $res = $globals->saveTabla($dataInsert, ["tabla" => "solicitud_contrato", "editar" => false], $dataBitacora);

        if (!$res->error) {
            $id_solicitud = $res->id;
            
            // Guardar Pagos
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
                    $globals->saveTabla($dataPago, ["tabla" => "solicitud_contrato_pagos", "id" => "id_pago"], ["tabla" => "bitacora", "id" => "id_bitacora"]);
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
            $res = $globals->saveTabla($dataUpdate, ["tabla" => "solicitud_contrato", "id" => "id_solicitud_contrato", "valor_id" => $id_solicitud], $dataBitacora);
            
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
         // Aquí se integraría la librería de Email similar a enviarEmail()
         
         if($id_solicitud){
             $response->error = false;
             $response->respuesta = 'Correo enviado (Simulado)';
         } else {
             $response->respuesta = 'ID no válido';
         }
         
         return $this->respond($response);
    }
    
    public function verSolicitudContratoPDF($id = null)
    {
        if(!$id){
            echo "ID no válido"; return;
        }

        $session = \Config\Services::session();
        $globals = new Mglobal;
        
        // Cargar datos
        $solicitud = $globals->getTabla(['tabla' => 'solicitud_contrato', 'where' => ['id_solicitud_contrato' => $id]]);
        $pagos = $globals->getTabla(['tabla' => 'solicitud_contrato_pagos', 'where' => ['id_solicitud_contrato' => $id, 'visible' => 1]]);
        
        if(empty($solicitud->data)){
            echo "Solicitud no encontrada"; return;
        }

        $data['solicitud'] = $solicitud->data[0];
        $data['pagos'] = (!empty($pagos->data)) ? $pagos->data : [];
        
        // Reutilizamos la vista de formulario pero en modo lectura o creamos una vista optimizada para impresión
        // Por ahora usaré una vista simple para PDF
        $html = view('personal/vPdfSolicitudContrato', $data);
        
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

    public function ListaSolicitudContrato()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = array();

        $solicitudes = $globals->getTabla(["tabla" => "solicitud_contrato", "where" => ["visible" => 1]]);
        
        $data['solicitudes'] = (!empty($solicitudes->data)) ? $solicitudes->data : [];
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vListaSolicitudContrato';
        $this->_renderView($data);
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
        if (in_array($session->get('id_perfil'), [1, 2])) {
            $registro_pt = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1]]);
        } else {
            $registro_pt = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1, 'id_estatus' =>4, 'usu_reg' => $session->get('id_usuario')]]);
        }
        //var_dump($registro_pt);
        //die();
        $data['registro_pt'] = (!empty($registro_pt->data)) ? $registro_pt->data : [];
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vregistroPT';
        $this->_renderView($data);
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
        $PT = ($tipo == 'PT') ? TRUE : FALSE;
        $GO = ($tipo == 'GO') ? TRUE : FALSE;
        $GRC = ($tipo == 'GRC') ? TRUE : FALSE;
        $FIC = ($tipo == 'FIC') ? TRUE : FALSE;
        if($PT) {
            $factura = $globals->getTabla(['tabla' => 'factura', 'where' => ['visible' => 1, 'id_registro_pt' => $id]]);
        }
        if($GO) {
            $factura = $globals->getTabla(['tabla' => 'xml_go', 'where' => ['visible' => 1, 'id_registro_go' => $id]]);
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

     

        if (empty($direccion->data)) {
            $jefe = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $registro_go->data[0]->id_reponsable_solicitud]]);
            if (!empty($jefe->data)) {
                $idJefe = $jefe->data[0]->id_jefe_inmediato;
                $direccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1, 'id_director' => $idJefe]]);
            } else {
                $area = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $registro_go->data[0]->id_reponsable_solicitud]]);
                $direccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1, 'id_area' => $area->data[0]->id_area]]);
            }
        }

        $data['responsableGasto'] = (isset($direccion->data) && !empty($direccion)) ? $direccion->data[0] : '';
        //var_dump($data['responsableGasto']  );
        //die();
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

            //==================================
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
            if (strlen($registro_go->data[0]->no_consecutivo) == 1) {
                $no_consecutivo = '00' . $registro_go->data[0]->no_consecutivo;
            }
            if (strlen($registro_go->data[0]->no_consecutivo) == 2) {
                $no_consecutivo = '0' . $registro_go->data[0]->no_consecutivo;
            }
            if (strlen($registro_go->data[0]->no_consecutivo) >= 3) {
                $no_consecutivo = $registro_go->data[0]->no_consecutivo;
            }
            
            $folio =(isset( $direccion->data) && !empty( $direccion->data))? $direccion->data[0]->folio_prefijo:'S/N/';
        
            $folio_prefijo = $folio . $no_consecutivo . '/' . date('Y'); //ESTO HAY QUE OREGUNTAR
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

            // Populate Excel Cells
            // Header Data
            $sheet->setCellValue('H7', date('d/m/Y', strtotime($data['registro']->fecha_tramite)));
            $folioText = (isset($data['GO']) && !empty($data['GO']) ? 'GO' : 'PT') . ' ' . $folio_prefijo;
            $sheet->setCellValue('H9', $folioText);

            // Checkboxes
            // 02_Póliza
            //die( var_dump($data['registro']) );
            $sheet->setCellValue('B15', ($data['registro']->poliza == 1) ? 'Si' : 'No'); 
            $sheet->setCellValue('B17',  'Si'); 
            
            // 14_Otros
            $sheet->setCellValue('F19', 'Si');
            $sheet->setCellValue('D32', 'Si');

            $sheet->setCellValue('D33', 'Si');
            $sheet->setCellValue('D34', 'Si');

            // Footer / Payment Data
            $sheet->setCellValue('B24', isset($data['registro']->dsc_proveedor) ? $data['registro']->dsc_proveedor : '');
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

            $sheet->setCellValue('B25', isset($data['registro']->concepto_pago) ? $data['registro']->concepto_pago : '');
            
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
            }
            $sheet->setCellValue('B26', implode(', ', $arrUuid));
            
            // Importe Total
             $fn = new \App\Libraries\Funciones();
             $importeTexto = '$' . number_format($sumaTotal, 2) . ' ' . $fn->numeroALetras($sumaTotal);
             $sheet->setCellValue('B27', $importeTexto);


            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Anexo1_' . $folioText . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit();
        }
        
       
        switch ($id_archivo) {
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
        //echo "<pre>";
        //print_r( $data['reserva']  );
        //echo "</pre>";
        //die();

        $html = view($formato, $data);
        // Crear instancia de mPDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

        //die( var_dump($doc) );
        $pagecount = $mpdf->SetSourceFile(FCPATH . $doc);


            $mpdf->AddPage();
            $tplId = $mpdf->ImportPage(1);
            $mpdf->UseTemplate($tplId);
           $mpdf->WriteHTML($html);
          
          
        


        if ($savePath) {
            $mpdf->Output($savePath, 'F'); // F = write to file
            return $savePath;
        }
        $mpdf->Output('Formato_pt.pdf', 'I');
        exit();

    }
    public function Archivo($id_registro_pt = null, $id_archivo = null, $savePath = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data['reserva'] = "";
        $registro_pt = $globals->getTabla([
            'tabla' => 'vw_registro_pt',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]
        ]);

        //die( var_dump($registro_pt) );
        $presupuesto = $globals->getTabla([
            'tabla' => 'vw_reserva',
            'where' => ['visible' => 1, 'id_reserva' => $registro_pt->data[0]->id_reserva]
        ]);
        //die( var_dump($presupuesto) );
        $direccion = $globals->getTabla([
            'tabla' => 'vw_direccion',
            'where' => [
                'visible' => 1,
                'id_director' => $registro_pt->data[0]->id_reponsable_solicitud
            ]
        ]);
        
        if( isset($presupuesto->data) && !empty($presupuesto->data)){
             $data['presupuesto'] = $presupuesto->data;
        }
      
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
        
         $folio =(isset( $direccion->data) && !empty( $direccion->data))? $direccion->data[0]->folio_prefijo:'S/N/';
      
        $folio_prefijo = $folio . $no_consecutivo . '/' . date('Y'); //ESTO HAY QUE OREGUNTAR

        $data['direccion'] = (isset( $direccion->data[0]) && !empty( $direccion->data[0]))? $direccion->data[0]:[];
        $pdf = $globals->getTabla([
            'tabla' => 'vw_pdf_reserva',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]
        ])->data;

        $instrumento = (isset($pdf[0]->instrumento) && !empty($pdf[0]->instrumento)) ? $pdf[0]->instrumento : '';
        $id_reserva = (isset($pdf[0]->id_reserva) && !empty($pdf[0]->id_reserva)) ? $pdf[0]->id_reserva : '';
       
         $importe = "";
        if (!empty($registro_pt->data)) {
            $data['registro'] = $registro_pt->data[0];
            $data['responsable'] = $registro_pt->data[0]->responsable;
            $data['dsc_puesto'] = $registro_pt->data[0]->dsc_puesto;
            $importe = $registro_pt->data[0]->total_importe;
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
         if (!empty($id_reserva)) {
            $reserva = $globals->getTabla([
                'tabla' => 'vw_reserva',
                'where' => ['visible' => 1, 'id_reserva' => $id_reserva]
            ])->data;
            $data['reserva'] = $reserva[0];
           
            $importe_str = ($data['fic'])?$reserva[0]->total_importe:$importe;
            $importe_float = (float) str_replace(',', '', $importe_str); // quita coma y convierte
            $data['numero_texto'] = $this->numeroEnLetras($importe_float);
            $data['es4000'] = false;
            if ($reserva[0]->partida >= '4000' && $reserva[0]->partida < '5000') {
                $data['es4000'] = true;
            }


        }
  
        $uuid = $globals->getTabla(['tabla' => 'factura', 'where' => ['id_registro_pt' => $id_registro_pt, 'visible' => 1]]);
       //die(var_dump($uuid));
        if(isset($uuid->data) && !empty($uuid->data)) {
            $data['uuid'] = ($data['fic'])?$uuid->data[0]->uuid:$uuid->data;
        }
       
        if ($id_archivo == 1) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(FCPATH . 'assets/documentos/Anexo1_Reporte_d_ integracion_documental_2026.xlsx');
            $sheet = $spreadsheet->getActiveSheet();
           // die(var_dump($spreadsheet));
            // Populate Excel Cells
            // Header Data
            $sheet->setCellValue('H7', date('d/m/Y', strtotime($data['registro']->fecha_tramite)));
            $folioText = (isset($data['GO']) && !empty($data['GO']) ? 'GO' : 'PT') . ' ' . $folio_prefijo;
            if ($data['fic']) {
                 $folioText = 'PT ' . $data['folio'];
            }
            $sheet->setCellValue('H9', $folioText);

            // Checkboxes (using 'X' or 'Si' as per template logic - assuming 'Si'/'No' text based on image)
            // 02_Póliza
            //die( var_dump(  $data['registro']) );
            $sheet->setCellValue('B15', ($data['registro']->poliza == 1) ? 'Si' : 'No');
            $sheet->setCellValue('B17', ($data['registro']->contrato_convenio == 1) ? 'Si' : 'No');  
            
            // 14_Otros - Logic from vFormato01 implies this might be dynamic or 'Si'
            $sheet->setCellValue('F19', 'Si'); // Defaulting to Si based on current usage or map from DB
            $sheet->setCellValue('D32', 'Si');
            $sheet->setCellValue('D33', 'Si');
            $sheet->setCellValue('D34', 'Si');
            // Footer / Payment Data
            $sheet->setCellValue('B24', isset($data['registro']->dsc_proveedor) ? $data['registro']->dsc_proveedor : '');
            
            // Partida Presupuestal
            //die( var_dump($data['presupuesto']) );
            $arrPartida = [];
            if (isset($data['presupuesto']) && is_array($data['presupuesto'])) {
                foreach ($data['presupuesto'] as $p) {
                    $arrPartida[] = $p->partida;
                }
            } elseif (isset($data['presupuesto']->partida)) {
                $arrPartida[] = $data['presupuesto']->partida;
            }
            $sheet->setCellValue('H24', implode(', ', $arrPartida));

            $sheet->setCellValue('B25', isset($data['registro']->concepto_pago) ? $data['registro']->concepto_pago : '');
            
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
            }
            $sheet->setCellValue('B26', implode(', ', $arrUuid));
            
            // Importe Total
             $fn = new \App\Libraries\Funciones();
             $importeTexto = '$' . number_format($sumaTotal, 2) . ' ' . $fn->numeroALetras($sumaTotal);
             $sheet->setCellValue('B27', $importeTexto);


            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Anexo1_' . $folioText . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit();
            die();
        }

        if (!empty($instrumento)) {
            switch ($id_archivo) {
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


    public function ImprimirGO($id_pt = null, $hoja = null, $index = null, $savePath = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = [];
        $id_reserva = null;

        $registro_go = $globals->getTabla([
            'tabla' => 'vw_registro_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_pt]
        ]);
        $formatos = $globals->getTabla([
            'tabla' => 'vw_pdf_reserva_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_pt]
        ]);
        $direccion = $globals->getTabla([
            'tabla' => 'vw_direccion',
            'where' => [
                'visible' => 1,
                'id_director' => $registro_go->data[0]->id_reponsable_solicitud
            ]
        ]);


        $periodo_factura = $globals->getTabla([
            'tabla' => 'vw_formato_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_pt]
        ]);
        $documentos = $globals->getTabla([
            'tabla' => 'periodo_factura_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_pt]
        ]);
        
        $importe = '';
         //var_dump( $periodo_factura );
        if(isset($periodo_factura->data) && !empty($periodo_factura->data)){

            $presupuestoGO = $globals->getTabla([
                    'tabla' => 'vw_periodo_factura_go',
                    'where' => ['visible' => 1, 'id_reserva' => $periodo_factura->data[0]->id_reserva_go]
            ]);
          
            if(isset($presupuestoGO) && !empty($presupuestoGO)){

               
              $data['presupuestoGO'] = $presupuestoGO->data;

            }
            
          
          
            $itemFactura  =  $periodo_factura->data;
            $data['documentos'] = count($documentos->data);
        }
          
        //==============================
         $xml = $globals->getTabla([
            'tabla' => 'xml_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_pt]
        ]);

        if (isset($xml->data) && !empty($xml->data)) {
            $data['uuid'] = $xml->data;
        }
        //==============================
        //==============================
         $importe = $globals->getTabla([
            'tabla' => 'periodo_factura_go',
            'where' => ['visible' => 1, 'id_registro_go' => $id_pt]
        ]);
       // var_dump( $xml->data );
        if (isset($importe->data) && !empty($importe->data)) {
            $data['importe'] = $importe->data;
              $totalGo = 0;
              foreach($xml->data as $key => $value){
                  $data['importe'][$key]->total = $value->total;
                  $totalGo += $value->total + (int)$importe->data[$key]->propina;
                
                }


        }
        //var_dump( $data['importe'] );
        //die();
        //==============================
        


        if (empty($direccion->data)) {
            $jefe = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $registro_go->data[0]->id_reponsable_solicitud]]);
            if (!empty($jefe->data)) {
                $idJefe = $jefe->data[0]->id_jefe_inmediato;
                $direccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1, 'id_director' => $idJefe]]);
            } else {
                $area = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $registro_go->data[0]->id_reponsable_solicitud]]);
                $direccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1, 'id_area' => $area->data[0]->id_area]]);
            }

        }
        $data['responsableGasto'] = ($direccion->data) ? $direccion->data[0] : '';
      // var_dump($registro_go->data[0]);
       //die();
        $subsecretario = $area = $globals->getTabla(['tabla' => 'cat_subsecretario', 'where' => ['visible' => 1, 'id_subsecretario' => $registro_go->data[0]->id_subsecretario]]);
        // $usu_sub = $area = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $subsecretario->data[0]->id_usuario]]);
        $data['usu_sub'] = $subsecretario->data[0];

        if (!empty($registro_go->data)) {
            $registro              = $registro_go->data[0];
            $id_reserva_go         = $registro_go->data[0]->id_reserva_go;
            $no_consecutivo        = $registro_go->data[0]->no_consecutivo;
            $data['registro']      = $registro;
            $data['registro']->total_importe = $totalGo;

           // die( var_dump( $data['registro']  ) );
            $importe_str          =  $registro->total_importe;    
            $importe_limpio = str_replace(['$', ' ', ','], '', $importe_str); 
            $importe_float = (float) $importe_limpio;// quita coma y convierte
            $data['numero_texto'] = $this->numeroEnLetras($importe_float);
            $data['no_cuenta']     = $registro->no_cuenta;
            $data['banco']         = $registro->banco;;
            $data['dsc_proveedor'] = $registro->dsc_proveedor;
            $data['clabe'] = '';
            
            $presupuesto = $globals->getTabla([
                'tabla' => 'vw_formato_go',
                'where' => ['visible' => 1, 'id_reserva_go' => $id_reserva_go]
            ]);

            if (isset($presupuesto->data) && !empty($presupuesto->data)) {
                // Eliminar duplicados completos
                $serialized = array_map('serialize', $presupuesto->data);
                $uniqueSerialized = array_unique($serialized);
                $data['presupuesto'] = array_map('unserialize', $uniqueSerialized);
            }
        
           //die( var_dump(  $data['presupuesto'] ) );
            $reserva = $globals->getTabla([
                'tabla' => 'vw_reserva_go',
                'where' => ['visible' => 1, 'id_reserva_go' => $id_reserva_go]
            ]);
            if (!empty($reserva->data)) {
                $data['reserva'] = $reserva->data;
                $usu_reg = $reserva->data[0]->usu_reg;
              /*   $importe_str = $reserva->data[0]->total_importe;
                
                $importe_float = (float) str_replace(',', '', $importe_str); // quita coma y convierte
                $data['numero_texto'] = $this->numeroEnLetras($importe_float); */
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
            if (!empty($direccion->data)) {
                $folio_prefijo = $direccion->data[0]->folio_prefijo . $zero . $no_consecutivo . '/' . date('Y'); //ESTO HAY QUE OREGUNTAR
                $data['registro']->folio = $folio_prefijo;
            } else {
                $data['registro']->folio = ''; // O un valor por defecto
            }

        } else {
            echo '<h2>Error al encontrar registro, favor de revisar el id del registro PT</h2>';
            die();
        }
        $data['GO'] = TRUE;
        $data['fic'] = false;
  
    //    $html = view('secciones/vFormatoPT.php', $data);
        $html = view('secciones/vFormatoGO.php', $data);
        $htmlSegundaHoja = view('secciones/vFormatoGO2.php', $data);
   //     $htmlTercerHoja = view('personal/vFormato702GO.php', $data);

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

        $runAll = ($hoja == null);
        $templateFile = 'assets/pdf/plantillas/formatoGO2.pdf';

        if ($runAll || $hoja == 1 || $hoja == 2) {
             $mpdf->SetSourceFile(FCPATH . $templateFile);
        }

        // --- HOJA 1 ---
        if ($runAll || $hoja == 1) {
            $mpdf->AddPage();
            $tplId = $mpdf->ImportPage(1);
            $mpdf->UseTemplate($tplId);
            $mpdf->WriteHTML($html);
        }

        // --- HOJA 2 ---
        if ($runAll || $hoja == 2) {
             // Reload source if specific page requested to be safe (though handled above)
             if (!$runAll && $hoja == 2) { // Only set source if specifically requesting page 2 and not all
                 $mpdf->SetSourceFile(FCPATH . $templateFile);
             }
             $mpdf->AddPage();
             $tplId = $mpdf->ImportPage(2);
             $mpdf->UseTemplate($tplId);
             $mpdf->WriteHTML($htmlSegundaHoja);
        }
        
        // --- HOJA 3 (Facturas) ---
        if ($runAll || $hoja == 3) {
            $xml_go = $globals->getTabla([
                    'tabla' => 'xml_go',
                    'where' => ['visible' => 1, 'id_registro_go' => $id_pt]
            ])->data;

            if ($index !== null && isset($xml_go[$index])) {
                $xml_go = [$index => $xml_go[$index]];
            }

            if (!empty($xml_go)) {
   
                    foreach ($xml_go as $index => $facturaItem) {
                    // die( var_dump( $presupuestoGO ) );
                         // $data['partida'] =  $presupuestoGO->data[$index]->dsc_partida;
                          $importe_str     =  $facturaItem->total;
                          $data['total']     =  $facturaItem->total;
                          $importe_float = (float) str_replace(',', '', $importe_str);
                          $data['monto'] = $this->numeroEnLetras($importe_float);
                          $data['partida'] =  $presupuestoGO->data[$index]->dsc_partida;
                          $data['uuid'] =     ($facturaItem->folio)?$facturaItem->folio:$facturaItem->uuid;
                            
                            $data['facturaItem'] = $facturaItem;
                            $periodo_factura_go = $globals->getTabla([
                                'tabla' => 'periodo_factura_go',
                                'where' => [
                                    'visible' => 1,
                                    'id_registro_go' => $id_pt,
                                ]
                            ]);
                           
                              $periodo = isset($periodo_factura_go->data) && !empty($periodo_factura_go->data)
                                ? $periodo_factura_go->data
                                : [];
                           // var_dump( $periodo );
                           
                            foreach($periodo  as $key => $p){
                           // Reemplazar el foreach interno por:
                                if (isset($periodo[$index])) {
                                    $p = $periodo[$index];
                                    
                                    $importe_str = $facturaItem->total; // Usar el item actual
                                    $importe_float = (float) str_replace(',', '', $importe_str);
                                    $data['numero_texto2'] = $this->numeroEnLetras($importe_float);
                                    $data['importePartida'] = $facturaItem->total;
                                    $data['inicio'] = $p->periodo_inicio;
                                    $data['fin']    = $p->periodo_fin;
                                    $monto          = (int)$facturaItem->total + (int)$p->propina;
                                    $data['total2']  = $monto;
                                    $data['monto2']  = $this->numeroEnLetras($monto);
                                } else {
                                    // Valores por defecto si no hay período
                                    $data['numero_texto2'] = '';
                                    $data['importePartida'] = '';
                                    $data['inicio'] = '';
                                    $data['fin']    = '';
                                    $data['total2']  = '';
                                    $data['monto2']  = '';
                                };

                                
                            }
                          
                           
                            $htmlTercerHoja = view('personal/vFormato702GO.php', $data);
                            // 1️⃣ Agregamos una sola página con el formato 702GO
                          
                            $mpdf->AddPage();
                            $mpdf->WriteHTML($htmlTercerHoja);

                            // 2️⃣ Obtenemos las facturas relacionadas
                            $factura_pdf_go = $globals->getTabla([
                                'tabla' => 'factura_pdf_go',
                                'where' => [
                                    'visible' => 1,
                                    'id_registro_go' => $id_pt,
                                    'id_identificador' => $facturaItem->id_identificador
                                ]
                            ]);
                     

                            $facturas = isset($factura_pdf_go->data) && !empty($factura_pdf_go->data)
                                ? $factura_pdf_go->data
                                : [];
                            
                          
                            // 3️⃣ Posición inicial (debajo del contenido del formato)
                           $currentY = $mpdf->y + 60;

                            // 4️⃣ Insertamos las facturas una debajo de otra
                            foreach ($facturas as $factura) {
                                $facturaPath = FCPATH . $factura->ruta_relativa;

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


        if ($savePath) {
            $mpdf->Output($savePath, 'F'); // F = write to file
            return $savePath;
        }

        $mpdf->Output('Formato_pt.pdf', 'I');
        exit();

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
        
        if (isset($presupuesto->data) && !empty($presupuesto->data)) {
            $data['presupuesto'] = $presupuesto->data;
        }
       
      
       if (isset($periodo_factura->data) && !empty($periodo_factura->data)) {
            $data['periodo_factura'] = $periodo_factura->data;
            $data['periodo_inicio'] = $periodo_factura->data[0]->periodo_inicio;
            $data['periodo_fin'] = $periodo_factura->data[0]->periodo_fin;
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
            if (!empty($direccion->data)) {
                $folio_prefijo = $direccion->data[0]->folio_prefijo . $zero . $no_consecutivo . '/' . date('Y'); 
                $data['registro']->folio = $folio_prefijo;
            } else {
               
                $data['registro']->folio = '';
            }

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
                                        $mpdf->AddPage(); // Separar cada hoja
                                        $tplFactura = $mpdf->ImportPage($pageNum);
                                        $templateSize = $mpdf->GetTemplateSize($tplFactura);
                                        $scaleFactor = 0.6;
                                        $width = $templateSize['width'] * $scaleFactor;
                                        $height = $templateSize['height'] * $scaleFactor;
                                        $xPos = ($mpdf->w - $width) / 2;
                                        $yPos = 40; 
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
            $response->data['reserva'] = (isset($reserva->data[0]) && !empty($reserva->data[0])) ? $reserva->data[0] : [];
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
        $data = [];
        if (!empty($id_reserva)) {
            $reserva = $globals->getTabla(['tabla' => 'vw_reserva', 'where' => ['visible' => 1, 'id_reserva' => $id_reserva]]);
            $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
            $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
            $response->error = $reserva->error;
            $response->respuesta = $reserva->respuesta;
            $response->data['reserva'] = (isset($reserva->data[0]) && !empty($reserva->data[0])) ? $reserva->data[0] : [];
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
      
        $responGasto = $globals->getTabla(['tabla' => 'folio_go', 'where' => ['id_direccion' => (int)$session->id_usuario]]);  //primero revisamos si tu no eres responsable del gasto

        if( isset($responGasto->data) && !empty($responGasto->data)){
             $no_consecutivo = count($responGasto->data);
        }
        if(empty($responGasto->data)){
             $responGasto = $globals->getTabla(['tabla' => 'folio_go', 'where' => ['visible' => 1, 'id_direccion', $id_jefe_inmediato ]]);
             $no_consecutivo = (isset($responGasto->data) && !empty($responGasto->data))?count($responGasto->data):'';
        }
        if(isset($no_consecutivo) && empty($no_consecutivo)){
               $responGasto = $globals->getTabla(['tabla' => 'folio_go', 'where' => ['visible' => 1, 'id_area ', $id_area]]);
               $no_consecutivo = (isset($responGasto->data) && !empty($responGasto->data))?count($responGasto->data):'';
        }
   

        
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
        
        $cat_area           = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
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
           //      var_dump(  $datos );
                 $datosGrupal[$key] = $data['presupuesto'];
                 foreach($datos->data as $j => $d){
             //      var_dump(  $d );
                    $xml      = $globals->getTabla(['tabla' => 'xml_go', 'where' => ['id_registro_go' => $id_registro_go, 'id_identificador' => $d->id_identificador, 'visible' => 1]]);
                    $factura  = $globals->getTabla(['tabla' => 'factura_pdf_go', 'where' => ['id_registro_go' => $id_registro_go, 'id_identificador' => $d->id_identificador, 'visible' => 1]]);
                    //die( var_dump( $xml ) );
                    $datosGrupal[$key]['datos'][$j] =  [
                         'id_periodo_factura' => $d->id_periodo_factura,
                         'id_registro_go' => $d->id_registro_go,
                         'id_presupuesto' => $d->id_presupuesto,
                         'encabezado' => $d->encabezado,
                         'periodo' => $d->periodo,
                         'importe' => $d->importe,
                         'comprobante' => $d->comprobante,
                         'propina' => $d->propina,
                         'contribuyente' => $d->contribuyente,
                         'rfc' => $d->rfc,
                         'visible' => $d->visible,
                         'periodo_fin' => $d->periodo_fin,
                         'periodo_inicio' => $d->periodo_inicio,
                         'id_identificador' => $d->id_identificador,
                         'usu_reg' => $d->usu_reg,
                         'total' => $xml->data[0]->total,
                         'ruta_relativa' => $factura->data[0]->ruta_relativa,
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
            $data['registro_pt'] = (!empty($registro_pt->data)) ? $registro_pt->data[0] : [];
        }


        // die( var_dump(  $data['registro_pt'] ) );
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
        $data['scripts'] = ['principal', 'inicio']; // Ensure necessary scripts are loaded
        $data['contentView'] = 'personal/vComprobarGastos';

        $this->_renderView($data);
    }

}