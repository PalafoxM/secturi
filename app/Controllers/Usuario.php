<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\Curps;
use App\Libraries\Fechas;
use App\Libraries\Funciones;
use App\Models\Mglobal;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use stdClass;
use CodeIgniter\API\ResponseTrait;


require_once FCPATH . 'app/Libraries/PHPMailer/Exception.php';
require_once FCPATH . 'app/Libraries/PHPMailer/PHPMailer.php';
require_once FCPATH . 'app/Libraries/PHPMailer/SMTP.php';

require_once FCPATH . '/mpdf/autoload.php';
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\IOFactory;

use DateTime;
use DatePeriod;
use DateInterval;


class Usuario extends BaseController
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
        $this->globals = new Mglobal();
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
        $data['unidad'] = $this->globals->getTabla(["tabla" => "cat_clues", "select" => "id_clues, NOMBRE_UNIDAD", "where" => ["visible" => 1], 'limit' => 10]);
        $data['perfiles'] = $this->globals->getTabla(["tabla" => "seg_perfiles", "where" => ["visible" => 1]]);
        $data['cat_sexo'] = $this->globals->getTabla(["tabla" => "cat_sexo", "where" => ["visible" => 1]]);
        $data['scripts'] = array('principal', 'inicio');
        $data['edita'] = 0;
        $data['nombre_completo'] = $session->nombre_completo;
        $data['contentView'] = 'secciones/vUsuarios';
        $this->_renderView($data);

    }

    public function enviarCorreo()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $principal = new Mglobal;
        $mail = new PHPMailer(true);
        $data = array();
        $id_participante = $this->request->getPost('id_participante');
        $participante = $principal->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1, 'id_participante' => $id_participante]]);
        if (isset($participante->data) && empty($participante->data)) {
            $response->respuesta = 'Id de usuario no encontrador favor de contactar al Administrador';
            return $this->respond($response);
        }
        $usuario = $participante->data[0];
        $hoy = date("Y-m-d H:i:s");
        $dataInsert = [
            'id_sexo' => (int)$usuario->id_sexo,
            'id_nivel' => (int)$usuario->id_nivel,
            'id_dependencia' => (int)$usuario->id_dependencia,
            'id_perfil' => 8,
            'id_padre' => (int)$session->id_perfil,
            'usuario' => $usuario->curp,
            'nombre' => $usuario->nombre,
            'primer_apellido' => $usuario->primer_apellido,
            'segundo_apellido' => $usuario->segundo_apellido,
            'correo' => $usuario->correo,
            'curp' => $usuario->curp,
            'contrasenia' => md5($usuario->curp),
            'rfc' => $usuario->rfc,
            'denominacion_funcional' => $usuario->denominacion_funcional,
            'area' => $usuario->area,
            'jefe_inmediato' => $usuario->jefe_inmediato,
            'fec_nac' => date("Y-m-d", strtotime($usuario->fec_nac)),
            'fec_registro' => $hoy
        ];
        $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Agregar.php/guardaUsuario'];
        $dataConfig = [
            "tabla" => "usuario",
            "editar" => false,
            //"idEditar"=>['id_usuario'=>$data['id_usuario']]
        ];
        $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
        $dataConfig = [
            "tabla" => "participantes",
            "editar" => true,
            "idEditar" => ['id_participante' => $id_participante]
        ];
        $response = $this->globals->saveTabla(['visible' => 0], $dataConfig, $dataBitacora);
        $contrasenia = md5($usuario->curp);
        $response->error = false;
        $response->respuesta = "Correo enviado correctamente.";
        return $this->respond($response);
    /*         try {
     $mail->isSMTP(); // Usar SMTP para el envío
     $mail->SMTPDebug = 2; // Habilitar depuración (2 para mensajes de cliente y servidor)
     $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
     $mail->SMTPAuth = true; // Habilitar autenticación SMTP
     $mail->Username = 'palafox.marin31@gmail.com'; // Correo electrónico del remitente
     $mail->Password = 'vxqh wycc fsgg tzvk'; // Contraseña de aplicación o contraseña de Gmail
     $mail->SMTPSecure = 'tls'; // Usar cifrado TLS
     $mail->Port = 587; // Puerto SMTP para TLS
     // Configurar el correo electrónico
     $mail->setFrom($usuario->correo, 'Sistema de Administración de Capacitación (SAC)');
     $mail->addAddress('palafox.marin@hotmail.com'); // Correo del destinatario
     $mail->Subject = 'Credenciales de Acceso al Sistema SAC'; // Asunto del correo
     $mail->isHTML(true); // Habilitar contenido HTML en el cuerpo del correo
     // Cuerpo del correo
     $mail->Body = "
     <p>Te damos la bienvenida al <strong>Sistema de Administración de Capacitación (SAC)</strong>.</p>
     <p>A continuación, te proporcionamos tus credenciales de acceso:</p>
     <ul>
     <li><strong>Usuario:</strong> $usuario->curp</li>
     <li><strong>Contraseña:</strong> $contrasenia</li>
     </ul>
     <p>Puedes acceder al sistema a través del siguiente enlace: <a href='http://172.31.187.142/sac2/'>http://172.31.187.142/sac2/</a></p>
     <p>Si tienes alguna duda o necesitas asistencia, no dudes en contactarnos.</p>
     <p>¡Gracias por ser parte de SAC!</p>
     ";
     // Enviar el correo
     if ($mail->send()) {
     $response->error = false;
     $response->respuesta = "Correo enviado correctamente.";
     } else {
     $response->error = true;
     $response->respuesta = "Error al enviar el correo: " . $mail->ErrorInfo;
     }
     return $this->respond($response); // Devolver la respuesta
     } catch (Exception $e) {
     // Manejar excepciones
     $response->error = true;
     $response->respuesta = "Error inesperado al enviar el correo: " . $e->getMessage();
     return $this->respond($response);
     } */

    }
    public function validarReporteExcel2()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $globals = new Mglobal;
        $periodoInicio = $this->request->getPost('periodoInicio');

        $dia = date('d', strtotime($periodoInicio));

        // OBTENER EL ÚLTIMO DÍA DEL MES
        $ultimoDiaMes = date('t', strtotime($periodoInicio)); // 't' devuelve el número de días del mes

        if ($dia == '01') {
            $fec_ini = date('Y-m-01', strtotime($periodoInicio));
            $fec_fin = date('Y-m-15', strtotime($periodoInicio));
        }
        else {
            $fec_ini = date('Y-m-16', strtotime($periodoInicio));
            $fec_fin = date('Y-m-' . $ultimoDiaMes, strtotime($periodoInicio)); // Usar el último día real
        }

        $tabla = [
            'tabla' => 'vw_asistencia_incidencia',
            'where' => ['visible' => 1],
            'whereBetween' => [['fecha', $fec_ini, $fec_fin]]
        ];

        $incidencias = $globals->getTabla($tabla);
        $resul = (isset($incidencias->data) && !empty($incidencias->data)) ? $incidencias->data : [];

        if (empty($resul)) {
            $response->respuesta = "No se encontrarón datos en el<strong> periodo indicado</strong>";
        }
        else {
            $response->error = false;
            $response->respuesta = "Datos Correctos";
        }
        return $this->respond($response);
    }
    public function registrarSalida()
    {

        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $globals = new Mglobal;

        $dataConfig = [
            "tabla" => "capacitacion",
            "editar" => false
        ];
        $dataInsert = [
            'id_usuario' => $session->get('id_usuario'),
            'fec_reg' => date('Y-m-d H:i:s'),

        ];
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarCapacitacion'];
        $response = $globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
        if (!$response->error) {
            $session->set('capacitacion', 1);

        }
        return $this->respond($response);
    }
    public function validarReporteExcel()
    {

        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $globals = new Mglobal;
        $periodoInicio = $this->request->getPost('periodoInicio');
        $periodoFin = $this->request->getPost('periodoFin');
        $fec_ini = date('Y-m-d', strtotime($periodoInicio));
        $fec_fin = date('Y-m-d', strtotime($periodoFin));
        // Obtener datos de la vista
        $tabla = [
            'tabla' => 'vw_asistencia_incidencia',
            'where' => ['visible' => 1],
            'whereBetween' => [['fechas_asistencias', $fec_ini, $fec_fin]]
        ];

        $incidencias = $globals->getTabla($tabla);
        $resul = (isset($incidencias->data) && !empty($incidencias->data)) ? $incidencias->data : [];
        if (empty($resul)) {
            $response->respuesta = "No se encontrarón datos en el<strong> periodo indicado</strong>";
        }
        else {
            $response->error = false;
            $response->respuesta = "Datos Correctos";
        }
        return $this->respond($response);
    }
    public function reporteIncidenciaExcel($fechaInicio = null, $fechaFin = null)
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $globals = new Mglobal;

        $fec_ini = date('Y-m-d', strtotime($fechaInicio));
        $fec_fin = date('Y-m-d', strtotime($fechaFin));

        // Obtener datos de la vista que incluye asistencias e incidencias
        $tabla = [
            'tabla' => 'vw_asistencia_incidencia',
            'where' => ['visible' => 1],
            'whereBetween' => [['fechas_asistencias', $fec_ini, $fec_fin]],
        ];

        $datos = $globals->getTabla($tabla);
        $resul = (isset($datos->data) && !empty($datos->data)) ? $datos->data : [];
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Agrupar datos por usuario y fecha
        $datosAgrupados = []; // [usuario][fecha] = ['entrada' => ..., 'salida' => ..., 'incidencia' => ...]
        $fechasUnicas = [];

        foreach ($resul as $r) {
            $usuario = $r->nombre_completo;

            // Procesar asistencias
            if ($r->fechas_asistencias) {
                $fecha = $r->fechas_asistencias;
                $datosAgrupados[$usuario][$fecha] = [
                    'entrada' => $r->hora_inicio,
                    'salida' => $r->hora_fin,
                    'incidencia' => null // Inicialmente no hay incidencia
                ];
                $fechasUnicas[$fecha] = true;
            }

            // Procesar incidencias
            if ($r->fechas_incidencias) {
                $fecha = $r->fechas_incidencias;
                if (!isset($datosAgrupados[$usuario][$fecha])) {
                    $datosAgrupados[$usuario][$fecha] = [
                        'entrada' => '',
                        'salida' => ''
                    ];
                }
                // Agregar información de incidencia
                $datosAgrupados[$usuario][$fecha]['incidencia'] =
                    ($r->id_estatus == 1) ? 'En proceso' :
                    (($r->id_estatus == 3) ? 'Declinada' : 'Aprobada');

                $fechasUnicas[$fecha] = true;
            }
        }

        // Ordenar fechas
        $fechasOrdenadas = array_keys($fechasUnicas);
        sort($fechasOrdenadas);

        // Crear el documento
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados (fila 1: fechas, fila 2: Entrada / Salida / Incidencia)
        $sheet->setCellValue('A1', '');
        $sheet->setCellValue('A2', 'Nombre');

        $col = 'B';
        foreach ($fechasOrdenadas as $fecha) {
            $sheet->setCellValue($col . '1', date('d/m/Y', strtotime($fecha)));
            $sheet->setCellValue($col . '2', 'Entrada');
            $col++;
            $sheet->setCellValue($col . '1', '');
            $sheet->setCellValue($col . '2', 'Salida');
            $col++;
            $sheet->setCellValue($col . '1', '');
            $sheet->setCellValue($col . '2', 'Incidencia');
            $col++;
        }

        $fila = 3;
        foreach ($datosAgrupados as $usuario => $dias) {
            $sheet->setCellValue('A' . $fila, $usuario);
            $col = 'B';

            foreach ($fechasOrdenadas as $fecha) {
                $entrada = isset($dias[$fecha]['entrada']) ? $dias[$fecha]['entrada'] : '';
                $salida = isset($dias[$fecha]['salida']) ? $dias[$fecha]['salida'] : '';
                $incidencia = isset($dias[$fecha]['incidencia']) ? $dias[$fecha]['incidencia'] : '';

                // Celda entrada
                $sheet->setCellValue($col . $fila, $entrada);

                // Estilo para entrada tardía
                if ($entrada && $entrada > '09:00:00') {
                    $sheet->getStyle($col . $fila)->getFill()->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('F55166');
                }
                elseif ($entrada && $entrada > '08:45:00') {
                    $sheet->getStyle($col . $fila)->getFill()->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('C9321A');
                }
                elseif ($entrada === '') {
                    $sheet->getStyle($col . $fila)->getFill()->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFFFFF00'); // Amarillo
                }
                elseif (strcasecmp(trim($incidencia), 'Aprobada') === 0) {
                    $sheet->getStyle($col . $fila)->getFill()->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('80F293'); // Verde
                }

                $col++;

                // Celda salida
                $sheet->setCellValue($col . $fila, $salida);
                $col++;

                // Celda incidencia
                $sheet->setCellValue($col . $fila, $incidencia);

                // Estilo para celdas con incidencia
                if ($incidencia) {
                    $sheet->getStyle($col . $fila)->getFill()->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFA500'); // Naranja para incidencias
                }
                $col++;
            }
            $fila++;
        }

        // Ajustar ancho de columnas
        $sheet->getColumnDimension('A')->setWidth(30);
        $lastColumn = $sheet->getHighestColumn();
        for ($col = 'B'; $col <= $lastColumn; $col++) {
            $sheet->getColumnDimension($col)->setWidth(15);
        }
        // var_dump($spreadsheet);
        // die();
        // Descargar archivo
        $writer = new Xlsx($spreadsheet);
        $fileName = 'reporte_asistencias_incidencias_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function reporteIncidenciaExcel2($periodoInicio = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;

        if (empty($periodoInicio)) {
            $periodoInicio = date('Y-m-d');
        }

        $dia = date('d', strtotime($periodoInicio));
        $ultimoDiaMes = date('t', strtotime($periodoInicio));

        if ($dia == '01') {
            $fec_ini = date('Y-m-01', strtotime($periodoInicio));
            $fec_fin = date('Y-m-15', strtotime($periodoInicio));
        }
        else {
            $fec_ini = date('Y-m-16', strtotime($periodoInicio));
            $fec_fin = date('Y-m-' . $ultimoDiaMes, strtotime($periodoInicio));
        }

        $anio = date('Y');
        $diasFestivosGenerales = [
            $anio . '-01-01' => 'Año Nuevo',
            $anio . '-02-02' => 'Día de la Constitución',
            $anio . '-03-18' => 'Natalicio de Benito Juárez',
            $anio . '-05-01' => 'Día del Trabajo',
            $anio . '-09-16' => 'Día de la Independencia',
            $anio . '-11-17' => 'Asueto',
            $anio . '-12-12' => 'Dia de la Virgen de Guadalupe',
            '2025-12-25' => 'Navidad',
            $anio . '-01-01' => 'Asueto'
        ];

        $tabla = [
            'tabla' => 'vw_asistencia_incidencia',
            'where' => ['visible' => 1, 'id_tipo_empleado' => 1],
            'whereBetween' => [['fecha', $fec_ini, $fec_fin]],
        ];

        $datos = $globals->getTabla($tabla);
        $resul = (isset($datos->data) && !empty($datos->data)) ? $datos->data : [];

        // Fechas laborales del periodo
        $start = new \DateTime($fec_ini);
        $end = new \DateTime($fec_fin);
        $end->modify('+1 day');
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($start, $interval, $end);

        $fechasDelPeriodo = [];
        foreach ($period as $d) {
            if ($d->format('N') < 6) {
                $fechasDelPeriodo[] = $d->format('Y-m-d');
            }
        }

        // Agrupar por usuario
        $usuarios = [];
        $cumpleanosUsuarios = [];

        foreach ($resul as $r) {
            $nombre = trim($r->nombre_completo ?: 'Sin nombre');


            // Verificar si es una incidencia de tipo 2 (por semana)
            if (!empty($r->tipo) && $r->tipo == 2) {

                // Procesar todas las fechas del rango de la incidencia
                $startIncidencia = new \DateTime($r->fecha_inicio_incidencia);
                $endIncidencia = new \DateTime($r->fecha_fin_incidencia);
                $endIncidencia->modify('+1 day');
                $intervalIncidencia = new \DateInterval('P1D');
                $periodIncidencia = new \DatePeriod($startIncidencia, $intervalIncidencia, $endIncidencia);

                foreach ($periodIncidencia as $fechaIncidencia) {
                    $fechaYmd = $fechaIncidencia->format('Y-m-d');

                    // Verificar que la fecha esté dentro del periodo del reporte y sea día laboral
                    if (!in_array($fechaYmd, $fechasDelPeriodo, true))
                        continue;
                    if (date('N', strtotime($fechaYmd)) >= 6)
                        continue;

                    if (!empty($r->fec_nac) && !isset($cumpleanosUsuarios[$nombre])) {
                        $cumpleanosUsuarios[$nombre] = $r->fec_nac;
                    }

                    if (!isset($usuarios[$nombre]))
                        $usuarios[$nombre] = [];
                    if (!isset($usuarios[$nombre][$fechaYmd])) {
                        $usuarios[$nombre][$fechaYmd] = [
                            'entrada' => '',
                            'salida' => '',
                            'incidencias' => []
                        ];
                    }

                    // Agregar la incidencia para esta fecha
                    if (isset($r->id_estatus)) {
                        $usuarios[$nombre][$fechaYmd]['incidencias'][] = [
                            'estatus' => $r->id_estatus,
                            'nombre' => $r->nombre_incidencia,
                            'tipo' => $r->tipo // Guardar el tipo para referencia
                        ];
                    }
                }

            }
            else {
                // Procesamiento normal para otros registros

                $fechaYmd = !empty($r->fecha) ? date('Y-m-d', strtotime($r->fecha)) : null;
                if (!$fechaYmd || date('N', strtotime($fechaYmd)) >= 6)
                    continue;

                if (!in_array($fechaYmd, $fechasDelPeriodo, true))
                    continue;

                if (!empty($r->fec_nac) && !isset($cumpleanosUsuarios[$nombre])) {
                    $cumpleanosUsuarios[$nombre] = $r->fec_nac;
                }

                if (!isset($usuarios[$nombre]))
                    $usuarios[$nombre] = [];
                if (!isset($usuarios[$nombre][$fechaYmd])) {
                    $usuarios[$nombre][$fechaYmd] = [
                        'entrada' => '',
                        'salida' => '',
                        'incidencias' => []
                    ];
                }

                if ($r->tipo_registro === 'asistencia') {
                    if (!empty($r->hora_inicio))
                        $usuarios[$nombre][$fechaYmd]['entrada'] = $r->hora_inicio;
                    if (!empty($r->hora_fin))
                        $usuarios[$nombre][$fechaYmd]['salida'] = $r->hora_fin;
                }

                if (($r->tipo_registro === 'incidencia') && isset($r->id_estatus)) {
                    $usuarios[$nombre][$fechaYmd]['incidencias'][] = [
                        'estatus' => $r->id_estatus,
                        'cat_incidencia' => $r->cat_incidencia,
                        'hora_inicio' => $r->hora_inicio,
                        'hora_fin' => $r->hora_fin,
                        'nombre' => $r->nombre_incidencia,
                        'tipo' => $r->tipo
                    ];
                }
            }

            // Agregar días festivos
            foreach ($fechasDelPeriodo as $fechaYmd) {
                if (isset($diasFestivosGenerales[$fechaYmd])) {
                    if (!isset($usuarios[$nombre]))
                        $usuarios[$nombre] = [];
                    if (!isset($usuarios[$nombre][$fechaYmd])) {
                        $usuarios[$nombre][$fechaYmd] = [
                            'entrada' => '',
                            'salida' => '',
                            'incidencias' => []
                        ];
                    }
                    $usuarios[$nombre][$fechaYmd]['incidencias'][] = [
                        'estatus' => 3,
                        'nombre' => 'DÍA FESTIVO',
                        'tipo' => null
                    ];
                }
            }


        }

        // Resto del código para cumpleaños y generación del Excel...
        foreach ($usuarios as $nombre => $fechas) {

            if (isset($cumpleanosUsuarios[$nombre])) {
                $cumpleAnioActual = $anio . '-' . date('m-d', strtotime($cumpleanosUsuarios[$nombre]));
                if (date('N', strtotime($cumpleAnioActual)) < 6 && in_array($cumpleAnioActual, $fechasDelPeriodo)) {
                    if (!isset($usuarios[$nombre][$cumpleAnioActual])) {
                        $usuarios[$nombre][$cumpleAnioActual] = ['entrada' => '', 'salida' => '', 'incidencias' => []];
                    }
                    $usuarios[$nombre][$cumpleAnioActual]['incidencias'][] = [
                        'estatus' => 3,
                        'nombre' => 'CUMPLEAÑOS',
                        'tipo' => null
                    ];
                }
            }
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // AGREGAR LOGOTIPO
        $logoPath = FCPATH . 'assets/logo-guanajuato.png'; // Ajusta la ruta según tu estructura
        // Si no existe el logo, puedes usar una imagen por defecto o omitir esta parte

        if (file_exists($logoPath)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo Secretaria');
            $drawing->setPath($logoPath);
            $drawing->setHeight(80); // Ajusta la altura según necesites
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);

            // Ajustar altura de las primeras filas para el logo
            $sheet->getRowDimension(1)->setRowHeight(60);
            $sheet->getRowDimension(2)->setRowHeight(20);
            $sheet->getRowDimension(3)->setRowHeight(20);
        }
        else {
            // Si no hay logo, poner el nombre de la secretaría como texto
            $sheet->setCellValue('A1', 'SECRETARÍA DE [NOMBRE]');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->mergeCells('A1:C1');
            $sheet->getRowDimension(1)->setRowHeight(25);
        }

        // Título del reporte (fila 2)
        $sheet->setCellValue('A2', 'REPORTE DE ASISTENCIAS E INCIDENCIAS');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->mergeCells('A2:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($fechasDelPeriodo) * 2) . '2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Periodo del reporte (fila 3)
        $periodoTexto = 'Periodo: ' . date('d/m/Y', strtotime($fec_ini)) . ' al ' . date('d/m/Y', strtotime($fec_fin));
        $sheet->setCellValue('A3', $periodoTexto);
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->mergeCells('A3:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($fechasDelPeriodo) * 2) . '3');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // AHORA LOS ENCABEZADOS EMPIEZAN EN LA FILA 4
        $sheet->setCellValue('A4', '');
        $sheet->setCellValue('A5', 'No. Empleado'); // ← NUEVA COLUMNA
        $sheet->setCellValue('B4', '');
        $sheet->setCellValue('B5', 'Nombre'); // ← Nombre movido a columna B

        // Cabeceras (empiezan en fila 4)
        $colIndex = 3; // ← Cambiar de 2 a 3 porque ahora hay 2 columnas antes
        foreach ($fechasDelPeriodo as $fecha) {
            $colStart = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $colEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
            $fechaFormateada = date('d/m/Y', strtotime($fecha));

            // Fecha en fila 4
            $sheet->setCellValue($colStart . '4', $fechaFormateada);
            $sheet->mergeCells("{$colStart}4:{$colEnd}4");
            $sheet->getStyle($colStart . '4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Encabezados "Entrada" y "Salida" en fila 5
            $sheet->setCellValue($colStart . '5', 'Entrada');
            $sheet->setCellValue($colEnd . '5', 'Salida');

            // Estilo para encabezados
            $sheet->getStyle($colStart . '4:' . $colEnd . '5')->getFont()->setBold(true);
            $sheet->getStyle($colStart . '4:' . $colEnd . '5')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFD9D9D9');
            $sheet->getStyle($colStart . '4:' . $colEnd . '5')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $colIndex += 2;
        }

        // Cuerpo del reporte (empieza en fila 6)
        $fila = 6;
        ksort($usuarios, SORT_STRING);


        foreach ($usuarios as $nombre => $fechas) {

            $numeroEmpleado = '';
            foreach ($resul as $r) {
                if (trim($r->nombre_completo ?: 'Sin nombre') === $nombre) {
                    $numeroEmpleado = isset($r->no_empleado) ? $r->no_empleado : 'N/A';
                    break;
                }
            }

            $sheet->setCellValue('A' . $fila, $numeroEmpleado);

            // NOMBRE AHORA VA EN COLUMNA B (antes era A)
            $sheet->setCellValue('B' . $fila, $nombre);

            $colIndex = 3; // ← Cambiar de 2 a 3

            foreach ($fechasDelPeriodo as $fecha) {
                $colEntrada = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                $colSalida = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);

                // dentro del foreach ($fechasDelPeriodo as $fecha) { ... }
                $dataDia = $usuarios[$nombre][$fecha] ?? ['entrada' => '', 'salida' => '', 'incidencias' => []];
                $entrada = $dataDia['entrada'];
                $salida = $dataDia['salida'];
                $incArr = $dataDia['incidencias'];

                $valorEntrada = $entrada;
                $valorSalida = $salida;
                $validadoEntrada = false;
                $validadoSalida = false;
                $stopIncProcessing = false; // para no sobrescribir si ya procesamos una incidencia relevante
                $cat = null;
                $estatus = null;

                if (!empty($incArr)) {

                    foreach ($incArr as $inc) {

                        if ($stopIncProcessing)
                            break;

                        $cat = isset($inc['cat_incidencia']) ? (int)$inc['cat_incidencia'] : null;
                        $estatus = isset($inc['estatus']) ? (int)$inc['estatus'] : null;
                        $horaInicio = isset($inc['hora_inicio']) ? $inc['hora_inicio'] : '';
                        $horaFin = isset($inc['hora_fin']) ? $inc['hora_fin'] : '';
                        $nombreInc = isset($inc['nombre']) ? strtoupper($inc['nombre']) : '';

                        // Incidencia aprobada y NO cat 11 -> comportamiento original (marcar ambos campos con el nombre)
                        if ($estatus === 3 && !in_array($cat, [11, 1, 7])) {

                            $valorEntrada = $nombreInc;
                            $valorSalida = $nombreInc;
                            $sheet->getStyle($colEntrada . $fila)
                                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FF00B050');
                            $sheet->getStyle($colSalida . $fila)
                                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FF00B050');
                            $validadoEntrada = true;
                            $validadoSalida = true;
                            $stopIncProcessing = true;
                            break;
                        }

                        // Caso cat 11 (comisión / permiso personal): solo entrada O salida, usar horas aprobadas
                        if ($cat === 11 && $estatus === 3) {
                            // Normalizamos con DateTime (si existe)


                            // Si hay hora de inicio dentro del rango de la mañana -> marcar entrada como permiso
                            if ($horaInicio) {
                                // compara solo la parte de tiempo: 09:01:00 - 12:00:00 (ajusta si tus rangos cambian)
                                if ($horaInicio >= '08:00:00' && $horaFin <= '12:01:00') {
                                    $valorEntrada = $nombreInc;
                                    $sheet->getStyle($colEntrada . $fila)
                                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                        // agregar FF al color
                                        ->getStartColor()->setARGB('FF00B050');
                                    $validadoEntrada = true;
                                    $stopIncProcessing = true;
                                }
                            }

                            // Si hay hora fin y cae en el rango de tarde -> marcar salida como permiso
                            if (!$stopIncProcessing && $horaFin) {
                                if ($horaFin >= '12:01:00' && $horaFin <= '16:00:00') {
                                    $valorSalida = $nombreInc;
                                    $sheet->getStyle($colSalida . $fila)
                                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                        ->getStartColor()->setARGB('FF00B050');
                                    $validadoSalida = true;
                                    $stopIncProcessing = true;
                                }
                            }
                            if (!$stopIncProcessing && $horaFin) {
                                if ($horaInicio >= '09:01:00' && $horaFin <= '18:00:00') {
                                    $valorSalida = $nombreInc;
                                    $sheet->getStyle($colEntrada . $fila)
                                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                        // agregar FF al color
                                        ->getStartColor()->setARGB('FF00B050');
                                    $validadoEntrada = true;
                                    $sheet->getStyle($colSalida . $fila)
                                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                        ->getStartColor()->setARGB('FF00B050');
                                    $validadoSalida = true;
                                    $stopIncProcessing = true;
                                }
                            }

                            // Si la comisión es de todo el día (inicio y fin) podrías decidir marcar ambos campos:
                            if (!$stopIncProcessing && $horaInicio && $horaFin) {
                                // ejemplo simple: si cubre mañana y tarde -> marcar ambos
                                $valorEntrada = $nombreInc;
                                $valorSalida = $nombreInc;
                                $sheet->getStyle($colEntrada . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FF00B050');
                                $sheet->getStyle($colSalida . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FF00B050');
                                $validadoEntrada = true;
                                $validadoSalida = true;
                                $stopIncProcessing = true;
                            }

                        // si ya procesaste, salir
                        // if ($stopIncProcessing)
                        // break;
                        }

                        // NUEVA LOGICA: Si es estatus 3 (Aprobado), asegurar Salida Verde + Texto
                        if ($estatus === 3 && !$validadoSalida) {
                            $valorSalida = $nombreInc;
                            $sheet->getStyle($colSalida . $fila)
                                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FF00B050');
                            $validadoSalida = true;
                        }

                        // Otros bloques Cat 1 y Cat 7 omitidos por brevedad pero deben ser corregidos manual o en siguiente paso si necesarios
                        // Asumiremos que Cat 11 es el principal problema reportado, pero corregiremos Cat 1 y 7 en un paso mas amplio si es necesario.

                        // FIX CAT 1 y CAT 7 AQUI MISMO PARA ASEGURAR:
                        if ($cat === 1 && $estatus === 3) {
                            if ($horaInicio) {
                                if ($horaInicio >= '08:00:00' && $horaFin <= '12:01:00') {
                                    $valorEntrada = $nombreInc;
                                    $sheet->getStyle($colEntrada . $fila)
                                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                        ->getStartColor()->setARGB('FF00B050');
                                    $validadoEntrada = true;
                                    $stopIncProcessing = true;
                                }
                            }
                            if (!$stopIncProcessing && $horaFin) {
                                if ($horaInicio >= '12:01:00' && $horaFin <= '16:00:00') {
                                    $valorSalida = $nombreInc;
                                    $sheet->getStyle($colSalida . $fila)
                                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                        ->getStartColor()->setARGB('FF00B050');
                                    $validadoSalida = true;
                                    $stopIncProcessing = true;
                                }
                            }
                            if (!$stopIncProcessing && $horaInicio >= '08:00:00' && $horaFin <= '16:00:00') {
                                $valorEntrada = $valorSalida = $nombreInc;
                                $sheet->getStyle($colEntrada . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FF00B050');
                                $sheet->getStyle($colSalida . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FF00B050');
                                $validadoEntrada = true;
                                $validadoSalida = true;
                                $stopIncProcessing = true;
                            }
                            if ($stopIncProcessing)
                                break;
                        }

                        if ($cat === 7 && $estatus === 3) {
                            if ($horaInicio) {
                                if ($horaInicio >= '08:00:00' && $horaFin <= '12:01:00') {
                                    $valorEntrada = 'CONSTANCIA DE TIEMPO';
                                    $sheet->getStyle($colEntrada . $fila)
                                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                        ->getStartColor()->setARGB('FF00B050');
                                    $validadoEntrada = true;
                                    $stopIncProcessing = true;
                                }
                            }
                            if ($horaInicio >= '08:00:00' && $horaFin <= '20:00:00') {
                                $valorEntrada = 'CONSTANCIA DE TIEMPO';
                                $sheet->getStyle($colEntrada . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FF00B050');
                                $validadoEntrada = true;
                                $sheet->getStyle($colSalida . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FF00B050');
                                $validadoSalida = true;
                                $stopIncProcessing = true;
                            }
                            if (!$stopIncProcessing && $horaFin) {
                                if ($horaInicio >= '12:01:00' && $horaFin <= '16:00:00') {
                                    $valorSalida = 'CONSTANCIA DE TIEMPO';
                                    $sheet->getStyle($colSalida . $fila)
                                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                        ->getStartColor()->setARGB('FF00B050');
                                    $validadoSalida = true;
                                    $stopIncProcessing = true;
                                }
                            }
                            if (!$stopIncProcessing && $horaInicio >= '08:30:00' && $horaFin <= '16:00:00') {
                                $valorEntrada = $valorSalida = 'CONSTANCIA DE TIEMPO';
                                $sheet->getStyle($colEntrada . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FF00B050');
                                $sheet->getStyle($colSalida . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FF00B050');
                                $validadoEntrada = true;
                                $validadoSalida = true;
                                $stopIncProcessing = true;
                            }
                            if ($stopIncProcessing)
                                break;
                        }

                        // Otros estatus (rechazado/en proceso)
                        if ($estatus === 2) {
                            $valorEntrada = 'Declinado';
                            $sheet->getStyle($colEntrada . $fila)
                                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFFF0000');
                            $validadoEntrada = true; // prevent red overrides? actually if declined, maybe we want unrelated red? But kept for safety.
                            $validadoSalida = true; // Assuming declined covers it ?? Actually original code set validado=true
                            $stopIncProcessing = true;
                            break;
                        }

                        if ($estatus === 1) {
                            // En proceso
                            // (Logica original mantenida pero adaptada a flags)
                            if ($horaInicio >= '08:00:00' && $horaFin <= '12:00:00') {
                                $valorEntrada = 'Sin validar';
                                $sheet->getStyle($colEntrada . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FFFF0000');
                                // $validadoEntrada = false; // Ya es false por defecto, no necesitamos setearlo a false explicitamente si ya lo es.
                                $stopIncProcessing = true;
                                break;
                            }
                            if ($horaInicio >= '12:00:00' && $horaFin <= '16:00:00') {
                                $valorSalida = 'Sin validar';
                                $sheet->getStyle($colSalida . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FFFF0000');
                                // $validadoSalida = false;
                                $stopIncProcessing = true;
                                break;
                            }
                            if (empty($salida) && empty($entrada)) {
                                $valorEntrada = 'Sin validar';
                                $valorSalida = 'Sin validar';
                                $sheet->getStyle($colEntrada . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FFFF0000');
                                $sheet->getStyle($colSalida . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FFFF0000');
                                $stopIncProcessing = true;
                                break;
                            }
                        }
                    } // end foreach loop
                }

                // --- AHORA aplicar la validación de retardo/ sin registro solo si NO fue validado por incidencias ---

                // 1. Caso especial: faltó todo el día (y no justificado)
                if (!$validadoEntrada && !$validadoSalida && empty($entrada) && empty($salida)) {
                    $valorSalida = 'Sin registro';
                    $valorEntrada = 'Sin registro';
                    $sheet->getStyle($colSalida . $fila)
                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFFF0000');
                    $sheet->getStyle($colEntrada . $fila)
                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFFF0000'); // rojo
                }
                elseif (!$validadoSalida && (empty($salida) || !$salida)) {
                    $valorSalida = 'Sin registro';
                    $sheet->getStyle($colSalida . $fila)
                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFFF0000'); // rojo
                }
                else {
                    // VALIDACIÓN ENTRADA
                    if (!$validadoEntrada) {
                        if (!empty($entrada)) {
                            if ($entrada > '08:46:00' && $entrada < '09:00:00') {
                                $sheet->getStyle($colEntrada . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FFFFFF00'); // naranja
                            }
                            if ($entrada > '09:01:00' && $entrada < '12:00:00') {
                                $sheet->getStyle($colEntrada . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FFFF0000'); // rojo
                            }
                        }
                        else if (!$validadoEntrada && $entrada === '') {
                        // Missing entry but present exit or just general missing
                        // (Do nothing or mark red depending on prefs, staying keeping logic minimal to avoid breaking existing flows)
                        }
                    }

                    // VALIDACIÓN SALIDA
                    if (!$validadoSalida) {
                        if (!empty($salida)) {
                            if ($salida > '12:00:00' && $salida < '16:00:00') {
                                $sheet->getStyle($colSalida . $fila)
                                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FFFF0000'); // Rojo puro
                            }
                        }
                    }
                }


                // Finalmente escribimos los valores en la hoja (después de todas las validaciones)
                $sheet->setCellValue($colEntrada . $fila, $valorEntrada);
                $sheet->setCellValue($colSalida . $fila, $valorSalida);

                $colIndex += 2;

            }
            $fila++;


        }

        //die();

        // Ajustar dimensiones de columnas
        $sheet->getColumnDimension('A')->setWidth(15); // ← No. Empleado
        $sheet->getColumnDimension('B')->setWidth(40); // ← Nombre
        $totalCols = 2 + (count($fechasDelPeriodo) * 2); // ← Cambiar de 1 a 2
        for ($i = 3; $i <= $totalCols; $i++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetter)->setWidth(18);
        }

        // Agregar bordes a la tabla de datos
        $lastRow = $fila - 1;
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCols);
        $tableRange = 'A5:' . $lastCol . $lastRow;
        $sheet->getStyle($tableRange)->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'reporte_asistencias_incidencias_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$fileName}\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }



    public function getIncidencia()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;

        $id_incidencia = $this->request->getPost('id_incidencia');
        //   var_dump( $id_incidencia);
        //  die();
        $dataDB = array('tabla' => 'incidencia', 'where' => ['visible' => 1, 'id_incidencia' => $id_incidencia]);
        $response = $principal->getTabla($dataDB);
        return $this->respond($response->data[0]);
    }

    public function estatusReservaGo()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error! Error al guardar en la base de datos';
        $data = $this->request->getPost();

        $dataConfig = [
            "tabla" => "reserva_go",
            "editar" => true,
            "idEditar" => ['id_reserva_go' => (int)$data['id_reserva']]
        ];
        $dataInsert = [
            "observaciones" => (isset($data['observaciones']) && !empty($data['observaciones'])) ? $data['observaciones'] : '',
            "id_estatus" => (isset($data['motivo']) && !empty($data['motivo'])) ? (int)$data['motivo'] : '',
            "no_reserva" => (isset($data['numero_reserva']) && !empty($data['numero_reserva'])) ? (int)$data['numero_reserva'] : '',
            "usu_act" => $session->get('id_usuario'),
        ];

        $result = $principal->saveTabla($dataInsert, $dataConfig, ['id_user' => $session->get('id_usuario'), "script" => "estatus.Reserva"]);

        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;

        }
        return $this->respond($response);
    }

    public function VehiculoTP($idVehiculo, $editar = null)
    {
        $response = new \stdClass();
        $data = $this->request->getPost();
        $Mglobal = new Mglobal;
        $vehiculo = $Mglobal->getTabla(['tabla' => 'vehiculo', 'where' => ['visible' => 1, 'id_vehiculo' => $idVehiculo]]);
        $cat_area = $Mglobal->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        $cat_secretario = $Mglobal->getTabla(['tabla' => 'cat_secretario', 'where' => ['visible' => 1]]);
        $cat_subsecretario = $Mglobal->getTabla(['tabla' => 'cat_subsecretario', 'where' => ['visible' => 1]]);
        $cat_usuario = $Mglobal->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        $cat_director_general = $Mglobal->getTabla(['tabla' => 'cat_director_general', 'where' => ['visible' => 1]]);
        $cat_proyecto = $Mglobal->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1, 'servicios' => 0]]);
        // $proveedor = $Mglobal->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit' => 10]);
        $data['cat_area'] = (isset($cat_area->data) && !empty($cat_area->data)) ? $cat_area->data : [];
        $data['secretario'] = (isset($cat_secretario->data) && !empty($cat_secretario->data)) ? $cat_secretario->data : [];
        $data['cat_subsecretario'] = (isset($cat_subsecretario->data) && !empty($cat_subsecretario->data)) ? $cat_subsecretario->data : [];
        $data['cat_usuario'] = (isset($cat_usuario->data) && !empty($cat_usuario->data)) ? $cat_usuario->data : [];
        $data['dsc_director_general'] = (isset($cat_director_general->data) && !empty($cat_director_general->data)) ? $cat_director_general->data[0]->dsc_director_general : [];
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];

        if (isset($vehiculo->data) && !empty($vehiculo->data)) {
            $idUser = $vehiculo->data[0]->id_usuario;
            $data['id_proyecto'] = $vehiculo->data[0]->id_proyecto;
            $usuario = $Mglobal->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $idUser]]);
            $data['usuario'] = (isset($usuario->data) && !empty($usuario->data)) ? $usuario->data[0] : '';

        }
        $noConsecutivo = $Mglobal->getTabla(['tabla' => 'pt_vehiculo', 'where' => ['visible' => 1]]);
        $data['id_vehiculo'] = $idVehiculo;
        $data['editar'] = 0;
        $data['no_consecutivo'] = count($noConsecutivo->data) + 1;

        $data['scripts'] = array('principal', 'inicio');
        $data['contentView'] = 'secciones/vRegistroVehiculo';
        $this->_renderView($data);

    }
    public function editarVehiculoTP($idVehiculo)
    {
        $response = new \stdClass();

        $Mglobal = new Mglobal;
        $vehiculo = $Mglobal->getTabla(['tabla' => 'pt_vehiculo', 'where' => ['visible' => 1, 'id_vehiculo' => $idVehiculo]]);
        $cat_proyecto = $Mglobal->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        if ($vehiculo->data) {
            $data['id_proyecto'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->id_proyecto : '';
            $data['no_consecutivo'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->no_consecutivo : '';
            $data['id_direccion_responsable'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->id_direccion_responsable : '';
            $data['id_responsable'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->id_responsable : '';
            $data['id_secretario'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->id_secretario : '';
            $data['id_responsable_gasto'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->id_responsable_gasto : '';
            $data['comision'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->comision : '';
            $data['concepto'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->concepto : '';
            $data['fec_inicio'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->fec_inicio : '';
            $data['fec_fin'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->fec_fin : '';
            $data['convenio'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->convenio : '';
            $data['otros'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->otros : '';
            $data['proveedor'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->proveedor : '';
            $data['banco'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->banco : '';
            $data['no_proveedor'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->no_proveedor : '';
            $data['folio'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->folio : '';
            $data['no_cuenta'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->no_cuenta : '';
            $data['clabe'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->clabe : '';
            $data['rfc'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->rfc : '';
            $data['formatos'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->formatos : '';
            $data['poliza'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->poliza : '';
            $data['conformidad'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->conformidad : '';
            $data['documentacion'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->documentacion : '';
            $data['contrato_convenio'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->contrato_convenio : '';
            $data['emitir_pago'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->emitir_pago : '';
            $data['evidencia'] = (isset($vehiculo->data) && !empty($vehiculo->data)) ? $vehiculo->data[0]->evidencia : '';



        }
        //die( var_dump( $vehiculo->data ) );
        $cat_area = $Mglobal->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        $cat_secretario = $Mglobal->getTabla(['tabla' => 'cat_secretario', 'where' => ['visible' => 1]]);
        $cat_subsecretario = $Mglobal->getTabla(['tabla' => 'cat_subsecretario', 'where' => ['visible' => 1]]);
        $cat_usuario = $Mglobal->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        $cat_director_general = $Mglobal->getTabla(['tabla' => 'cat_director_general', 'where' => ['visible' => 1]]);
        $proveedor = $Mglobal->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit' => 10]);
        $data['cat_area'] = (isset($cat_area->data) && !empty($cat_area->data)) ? $cat_area->data : [];
        $data['secretario'] = (isset($cat_secretario->data) && !empty($cat_secretario->data)) ? $cat_secretario->data : [];
        $data['cat_subsecretario'] = (isset($cat_subsecretario->data) && !empty($cat_subsecretario->data)) ? $cat_subsecretario->data : [];
        $data['cat_usuario'] = (isset($cat_usuario->data) && !empty($cat_usuario->data)) ? $cat_usuario->data : [];
        $data['dsc_director_general'] = (isset($cat_director_general->data) && !empty($cat_director_general->data)) ? $cat_director_general->data[0]->dsc_director_general : [];
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];

        $data['editar'] = 1;
        $data['id_vehiculo'] = $idVehiculo;
        $data['scripts'] = array('principal', 'inicio');
        $data['contentView'] = 'secciones/vRegistroVehiculo';
        $this->_renderView($data);

    }

    public function enviarCorreoPagos($correo)
    {
        // Inicializar servicios y objetos
        $email = \Config\Services::email();

        $response = new \stdClass();



        $email->setTo($correo);
        $email->setSubject('ESTATUS DE PAGO CAMBIO');
        $email->setMessage('
                    <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                        <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                            <div style="background-color: #004080; padding: 20px; text-align: center;">
                                <img src="' . base_url('assets/images/logo-sm.png') . '" alt="Logo" style="height: 60px;">
                            </div>
                            <div style="padding: 30px; color: #333;">
                                <h1 style="color: #004080;">¡El estatus de su pago cambio!</h1>
                                <p style="font-size: 16px;">Favor de <strong> Ingresar a SUSI</strong>.</p>
                                <p style="font-size: 15px;"><a href="' . base_url() . 'index.php/Principal/listaReservaPT"><strong>Seguimiento Pago</strong></a></p>
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
        }
        else {
            $response->respuesta = 'Error al enviar: ' . $email->printDebugger();
        }

        return $this->response->setJSON($response);


    }
    public function enviarCorreoAceptacion($correo, $datos)
    {
        $email = \Config\Services::email();
        $response = new \stdClass();

        $email->setTo($correo);
        $email->setSubject('RESERVA ACEPTADA');
        $email->setMessage('
                    <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                        <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                            <div style="background-color: #004080; padding: 20px; text-align: center;">
                                <img src="' . base_url('assets/images/logo-sm.png') . '" alt="Logo" style="height: 60px;">
                            </div>
                            <div style="padding: 30px; color: #333;">
                                <h1 style="color: #004080;">¡Su reserva ha sido ACEPTADA!</h1>
                                <p style="font-size: 16px;">Detalles de la reserva:</p>
                                <ul>
                                    <li><strong>No. Reserva:</strong> ' . $datos['no_reserva'] . '</li>
                                    <li><strong>No. Convenio:</strong> ' . $datos['no_convenio'] . '</li>
                                    <li><strong>Importe:</strong> ' . $datos['total_importe'] . '</li>
                                </ul>
                                <p style="font-size: 15px;">Favor de <strong> Ingresar a SUSI</strong> para más detalles.</p>
                                <p style="font-size: 15px;"><a href="' . base_url() . 'index.php/Principal/listaReservaPT"><strong>Seguimiento Reserva</strong></a></p>
                            </div>
                            <div style="background-color: #e0e0e0; text-align: center; padding: 15px; font-size: 13px; color: #666;">
                                © ' . date('Y') . ' Sistema de Atención SUSI. Todos los derechos reservados.
                            </div>
                        </div>
                    </div>
                ');

        if ($email->send()) {
            $response->error = false;
            $response->respuesta = "Correo enviado correctamente.";
        } else {
            $response->respuesta = 'Error al enviar: ' . $email->printDebugger();
        }
        return $response;
    }

    public function estatusReserva()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error! Error al guardar en la base de datos';
        $data = $this->request->getPost();
       

        $dataConfig = [
            "tabla" => "reserva",
            "editar" => true,
            "idEditar" => ['id_reserva' => (int)$data['id_reserva']]
        ];
        $dataInsert = [
            "observaciones" => (isset($data['observaciones']) && !empty($data['observaciones'])) ? $data['observaciones'] : '',
            "id_estatus" => (isset($data['motivo']) && !empty($data['motivo'])) ? (int)$data['motivo'] : '',
            "no_reserva" => (isset($data['numero_reserva']) && !empty($data['numero_reserva'])) ? (int)$data['numero_reserva'] : '',
            "usu_act" => $session->get('id_usuario'),
        ];

        $result = $principal->saveTabla($dataInsert, $dataConfig, ['id_user' => $session->get('id_usuario'), "script" => "estatus.Reserva"]);

        // Actualizar tabla presupuesto
        if (isset($data['id_presupuesto_estatus']) && is_array($data['id_presupuesto_estatus'])) {
            foreach ($data['id_presupuesto_estatus'] as $key => $id_presupuesto) {
                if(!empty($id_presupuesto)){
                     $dataPresupuesto = [
                        'id_proyecto' => (isset($data['id_proyecto_estatus'][$key]) && !empty($data['id_proyecto_estatus'][$key])) ? $data['id_proyecto_estatus'][$key] : null,
                        'id_partida' => (isset($data['id_partida_estatus'][$key]) && !empty($data['id_partida_estatus'][$key])) ? $data['id_partida_estatus'][$key] : null,
                        'fondo' => (isset($data['fondo'][$key]) && !empty($data['fondo'][$key])) ? $data['fondo'][$key] : null,
                    ];
                    
                    $dataConfigPresupuesto = [
                        "tabla" => "presupuesto",
                        "editar" => true,
                        "idEditar" => ['id_presupuesto' => $id_presupuesto]
                    ];
                    $principal->saveTabla($dataPresupuesto, $dataConfigPresupuesto, ['id_user' => $session->get('id_usuario'), "script" => "estatus.Reserva.UpdatePresupuesto"]);
                }
            }
        }
        $usuReg = $principal->getTabla(['tabla' => 'reserva', 'where' => ['id_reserva' => $data['id_reserva'], 'visible' => 1]])->data;
        if ($usuReg) {
            $id_usuario = $usuReg[0]->usu_reg;
            $correo = $principal->getTabla(['tabla' => 'vw_usuario', 'where' => ['id_usuario' => $id_usuario, 'visible' => 1]])->data[0]->correo;
       
           if($session->get('id_usuario')!=1){
                if (isset($data['motivo']) && (int)$data['motivo'] === 3) {
                    $datosCorreo = [
                        'no_reserva' => $usuReg[0]->no_reserva,
                        'no_convenio' => $usuReg[0]->no_convenio,
                        'total_importe' => $usuReg[0]->total_importe
                    ];
                    $this->enviarCorreoAceptacion($correo, $datosCorreo);
                } else {
                    $this->enviarCorreoPagos($correo);
                }
           }
        }

        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;

        }
        return $this->respond($response);
    }
    public function deleteReservaGo()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $response = new \stdClass();
        $id_reserva = $this->request->getPost('id_reserva');

        $dataConfig = [
            "tabla" => "reserva_go",
            "editar" => true,
            "idEditar" => ['id_reserva_go' => $id_reserva]
        ];
        $result = $principal->saveTabla(['visible' => 0], $dataConfig, ["script" => "eliminar.Reserva"]);
        if (!empty($resul->data)) {
            $response->error = $result->error;
            $response->respuesta = $result->respuesta;

        }
        return $this->respond($response);
    }
    public function finalizarPago()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $response = new \stdClass();
        $id_reserva = $this->request->getPost('id_reserva');

        $dataConfig = [
            "tabla" => "reserva",
            "editar" => true,
            "idEditar" => ['id_reserva' => $id_reserva]
        ];
        $result = $principal->saveTabla(['id_estatus' => 5], $dataConfig, ["script" => "finalizo.Reserva"]);
        if (!empty($resul->data)) {
            $response->error = $result->error;
            $response->respuesta = $result->respuesta;

        }
        return $this->respond($response);
    }
    public function deleteReserva()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $response = new \stdClass();
        $id_reserva = $this->request->getPost('id_reserva');
        $dataConfig = [
            "tabla" => "reserva",
            "editar" => true,
            "idEditar" => ['id_reserva' => $id_reserva]
        ];
        $result = $principal->saveTabla(['visible' => 0], $dataConfig, ["script" => "eliminar.Reserva"]);
        if (!empty($resul->data)) {
            $response->error = $result->error;
            $response->respuesta = $result->respuesta;

        }
        return $this->respond($response);
    }
    public function descargaDirectorio()
    {
        // 1. Crear el documento
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 2. Obtener datos de la BD
        $globals = new Mglobal();
        $resul = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);

        if (!isset($resul->data) || empty($resul->data)) {
            echo "No hay datos para exportar";
            return;
        }

        // 3. Encabezados CORREGIDOS
        $encabezados = [
            'ID',
            'NOMBRE',
            'PRIMER APELLIDO',
            'SEGUNDO APELLIDO',
            'CORREO',
            'AREA',
            'EXTENCION'
        ];

        // Colocar encabezados correctamente
        $columnas = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        foreach ($encabezados as $index => $titulo) {
            $sheet->setCellValue($columnas[$index] . '1', $titulo);

            // Opcional: estilo para encabezados
            $sheet->getStyle($columnas[$index] . '1')->getFont()->setBold(true);
        }

        // 4. Llenar datos CORREGIDOS
        $fila = 2;
        foreach ($resul->data as $row) {
            $sheet->setCellValue('A' . $fila, $row->id_usuario);
            $sheet->setCellValue('B' . $fila, $row->nombre);
            $sheet->setCellValue('C' . $fila, $row->primer_apellido);
            $sheet->setCellValue('D' . $fila, $row->segundo_apellido);
            $sheet->setCellValue('E' . $fila, $row->correo);
            $sheet->setCellValue('F' . $fila, $row->dsc_area);
            $sheet->setCellValue('G' . $fila, $row->extencion);
            $fila++;
        }

        // Autoajustar columnas
        foreach ($columnas as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 5. Descargar archivo
        $writer = new Xlsx($spreadsheet);
        $fileName = 'directorio_' . date('Ymd_His') . '.xlsx';

        // Enviar headers CORRECTOS
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        $writer->save('php://output');
        exit;
    }
    public function exportarExcel()
    {
        // 1. Crear el documento
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 2. Obtener datos de la BD
        $globals = new Mglobal();
        $resul = $globals->getTabla(['tabla' => 'vw_reserva', 'where' => ['visible' => 1]]);

        if (!isset($resul->data) || empty($resul->data)) {
            echo "No hay datos para exportar";
            return;
        }

        // 3. Encabezados
        $encabezados = [
            'RESERVA',
            'Sociedad',
            'Ejercicio',
            'Clase de Documento',
            'Fecha  Contabilizacion',
            'Fecha del Documento',
            'Texto de Cabecera',
            'Refencia del Documento',
            'Importe de la Posicion',
            'Texto Posicion',
            'Partida',
            'Centro Gestor',
            'Fondo',
            'Area Funcional',
            'Cuenta Mayor',
            'Division',
            'Centro de Costo',
            'Numero de Orden',
            'Elemento PEP',
            'Acreedor',
            'Fecha de Vencimiento'
        ];
        $col = 'A';
        foreach ($encabezados as $titulo) {
            $sheet->setCellValue($col . '1', $titulo);
            $col++;
        }

        // 4. Llenar datos
        $fila = 2;
        foreach ($resul->data as $row) {
            $sheet->setCellValue('A' . $fila, $row->no_reserva);
            $sheet->setCellValue('B' . $fila, 'GEG');
            $sheet->setCellValue('C' . $fila, '2026');
            $sheet->setCellValue('D' . $fila, 'RF');
            $sheet->setCellValue('E' . $fila, date('dmY', strtotime($row->fec_reg)));
            $sheet->setCellValue('F' . $fila, date('dmY', strtotime($row->fec_reg)));
            $sheet->setCellValue('G' . $fila, $row->texto_cabecera);
            $sheet->setCellValue('H' . $fila, $row->no_convenio);
            $sheet->setCellValue('I' . $fila, $row->importe);
            $sheet->getStyle('I' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->setCellValue('J' . $fila, $row->no_convenio);
            $sheet->setCellValue('K' . $fila, $row->partida);
            $sheet->setCellValueExplicit('L' . $fila, $row->centro_gestor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('M' . $fila, ($row->nuevo_fondo)?$row->nuevo_fondo:$row->fondo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('N' . $fila, $row->area);
            $sheet->setCellValueExplicit('O' . $fila, $row->cuenta_mayor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('P' . $fila, '21');
            $sheet->setCellValueExplicit('Q' . $fila, substr($row->centro_gestor, 5), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('R' . $fila, '/');
            $sheet->setCellValue('S' . $fila, $row->elemento_pep);
            $sheet->setCellValue('T' . $fila, $row->no_proveedor);
            $sheet->setCellValue('U' . $fila, '31122026');
            $sheet->setCellValue('V' . $fila, $row->dsc_area);
            $fila++;
        }

        // 5. Descargar archivo
        $writer = new Xlsx($spreadsheet);
        $fileName = 'reserva_' . date('Ymd_His') . '.xlsx';

        // Enviar headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
    public function exportarExcelGo()
    {
        // 1. Crear el documento
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $globals = new Mglobal();
        $result = $globals->getTabla(['tabla' => 'vw_reserva_go', 'where' => ['visible' => 1]]);

        if (empty($result)) {
            echo "No hay datos para exportar";
            return;
        }

        // 3. Encabezados
        $encabezados = [
            'Suma de Importe',
            'FOLIO',
            'PARTIDA',
            'CENTRO GESTOR',
            'CTA MAYOR',
            'FONDO',
            'DIV',
            'CENTRO COSTO',
            'ORDEN',
            'ELEMENTO PEP'
        ];

        $col = 'A';
        foreach ($encabezados as $titulo) {
            $sheet->setCellValue($col . '1', $titulo);
            $col++;
        }
        // 4. Llenar datos
        //die( var_dump($result) );
        $fila = 2;
        foreach ($result->data as $row) {
            // die( var_dump($row) );
            $direccion = $globals->getTabla([
                'tabla' => 'vw_direccion',
                'where' => [
                    'visible' => 1,
                    'id_director' => $row->id_reponsable_solicitud
                ]
            ]);
            $registroGo = $globals->getTabla([
                'tabla' => 'registro_go',
                'where' => [
                    'visible' => 1,
                    'id_reserva_go' => $row->id_reserva_go
                ]
            ]);

            if (!empty($registroGo->data) && isset($registroGo)) {

            }

            if (empty($direccion->data)) {
                $usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $row->id_reponsable_solicitud]]);

                if (!empty($usuario->data)) {
                    $idJefe = $usuario->data[0]->id_jefe_inmediato;
                    $direccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1, 'id_director' => $idJefe]]);

                    if (empty($direccion->data)) {
                        $idArea = $usuario->data[0]->id_area;
                        $direccion = $globals->getTabla(['tabla' => 'vw_direccion', 'where' => ['visible' => 1, 'id_area' => $idArea]]);
                    }
                }
            }

            $prefijo = (isset($direccion->data) && !empty($direccion->data)) ? $direccion->data[0]->folio_prefijo : '';
            $no_consecutivo = str_pad($row->no_consecutivo, 3, "0", STR_PAD_LEFT);


            $folio_prefijo = $prefijo . $no_consecutivo . '/' . date('Y');
            $sheet->setCellValue('A' . $fila, floatval(str_replace(',', '', $row->importe)) + floatval(str_replace(',', '', $row->propina)));
            $sheet->setCellValue('B' . $fila, $folio_prefijo);
            $sheet->setCellValue('C' . $fila, $row->partida);
            $sheet->setCellValue('D' . $fila, $row->centro_gestor);
            $sheet->setCellValue('E' . $fila, '');
            $sheet->setCellValue('F' . $fila, $row->fondo);
            $sheet->setCellValue('G' . $fila, '21');

            // Re-mapeo de columnas tras eliminar las solicitadas
            $sheet->setCellValue('H' . $fila, '');

            $sheet->setCellValue('I' . $fila, '');
            $sheet->setCellValue('J' . $fila, $row->elemento_pep);

            $fila++;
        }

        // 5. Descargar archivo
        $writer = new Xlsx($spreadsheet);
        $fileName = 'reserva_detalle_' . date('Ymd_His') . '.xlsx';

        // Enviar headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
    public function Descarga()
    {
        // --- 1. Limpieza de Buffer ---
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        // --- 2. Carga de recursos ---
        $session = \Config\Services::session();
        $globals = new Mglobal;

        // Ruta de la plantilla
        $ruta = FCPATH . 'assets/pdf/plantillas/9-LTAIPG26F1_IX.xlsx';

        if (!file_exists($ruta)) {
            die("Error: No se encontró la plantilla en: " . $ruta);
        }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($ruta);
        $sheet = $spreadsheet->getSheetByName('Reporte de Formatos') ?? $spreadsheet->getActiveSheet();

        // Consulta a la VISTA (usará los ALIAS corregidos)
        $resul = $globals->getTabla([
            'tabla' => 'vw_juridico_viaticos',
            'where' => ['visible' => 1]
        ]);

        $fila = 8;

        if (!empty($resul->data)) {
            foreach ($resul->data as $row) {

                // --- CÁLCULOS PREVIOS ---
                // Aseguramos que sean números para evitar errores de resta
                $total_asignado = is_numeric($row->importe_total) ? $row->importe_total : 0;
                $ejercido_partida = is_numeric($row->importe_ejercicio_partida) ? $row->importe_ejercicio_partida : 0;

                // Lógica: No erogado = Total Asignado - Lo que se gastó (Ejercido)
                $no_erogado = $total_asignado - $ejercido_partida;

                // --- LLENADO DE CELDAS ---
                // A - Datos Básicos
                $sheet->setCellValue('A' . $fila, $row->ejercicio);
                $sheet->setCellValue('B' . $fila, date('d/m/Y', strtotime($row->fecha_inicio)));
                $sheet->setCellValue('C' . $fila, date('d/m/Y', strtotime($row->fecha_termino)));
                $sheet->setCellValue('D' . $fila, $row->dsc_tipo_funcionario);
                $sheet->setCellValue('E' . $fila, $row->clave_nivel);
                $sheet->setCellValue('F' . $fila, $row->dsc_denominacion);
                $sheet->setCellValue('G' . $fila, $row->dsc_cargo);
                $sheet->setCellValue('H' . $fila, $row->dsc_area);
                $sheet->setCellValue('I' . $fila, $row->nombre);
                $sheet->setCellValue('J' . $fila, $row->primer_apellido);
                $sheet->setCellValue('K' . $fila, $row->segundo_apellido);
                $sheet->setCellValue('L' . $fila, ($row->id_sexo == 2) ? 'HOMBRE' : 'MUJER');
                $sheet->setCellValue('M' . $fila, $row->dsc_gasto);
                $sheet->setCellValue('N' . $fila, 'COMPROBADO');
                $sheet->setCellValue('O' . $fila, $row->dsc_viaje);
                $sheet->setCellValue('P' . $fila, $row->no_personas);

                // Q - Importe Total (Asignado)
                $sheet->setCellValue('Q' . $fila, $total_asignado);

                // R, S, T - Origen
                $sheet->setCellValue('R' . $fila, $row->dsc_pais_origen ?? '');
                $estado_o = !empty($row->estado_origen_text) ? $row->estado_origen_text : ($row->dsc_estado_origen ?? '');
                $sheet->setCellValue('S' . $fila, $estado_o);
                $muni_o = !empty($row->municipio_origen_text) ? $row->municipio_origen_text : ($row->dsc_municipio_origen ?? '');
                $sheet->setCellValue('T' . $fila, $muni_o);

                // U, V, W - Destino
                $sheet->setCellValue('U' . $fila, $row->dsc_pais_destino ?? '');
                $estado_d = !empty($row->estado_destino_text) ? $row->estado_destino_text : ($row->dsc_estado_destino ?? '');
                $sheet->setCellValue('V' . $fila, $estado_d);
                $muni_d = !empty($row->municipio_destino_text) ? $row->municipio_destino_text : ($row->dsc_municipio_destino ?? '');
                $sheet->setCellValue('W' . $fila, $muni_d);

                // X, Y, Z
                $sheet->setCellValue('X' . $fila, $row->motivo_encargo);
                $sheet->setCellValue('Y' . $fila, $row->fec_salida);
                $sheet->setCellValue('Z' . $fila, $row->fec_regreso);

                // --- NUEVAS COLUMNAS (AA - AJ) ---

                // AA - Importe ejercido por partida
                $sheet->setCellValue('AA' . $fila, $ejercido_partida);

                // AB - Importe total erogado (La suma de lo gastado, igual a AA en este caso)
                $sheet->setCellValue('AB' . $fila, $ejercido_partida);

                // AC - Importe total gastos NO erogados (Calculado)
                $sheet->setCellValue('AC' . $fila, $no_erogado);

                // AD - Fecha entrega informe (Usa alias corregido: 'fec_entrega_informa')
                $sheet->setCellValue('AD' . $fila, $row->fec_entrega_informa ?? '');

                // AE - Hipervínculo al informe
                $sheet->setCellValue('AE' . $fila, $row->hipervinculo_informe ?? '');

                // AF - Hipervínculo a facturas
                $sheet->setCellValue('AF' . $fila, $row->hipervinculo_factura ?? '');

                // AG - Hipervínculo normativa
                $sheet->setCellValue('AG' . $fila, $row->hipervinculo_normativa ?? '');

                // AH - Área responsable
                $sheet->setCellValue('AH' . $fila, $row->area_responsable ?? '');

                // AI - Fecha actualización
                // Si la fecha viene null, ponemos la fecha actual como fallback
                $sheet->setCellValue('AI' . $fila, $row->fec_actualizacion ?? date('d/m/Y'));

                // AJ - Nota
                $sheet->setCellValue('AJ' . $fila, $row->nota ?? '');

                $fila++;
            }
        }

        // --- 3. Finalizar Descarga ---
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'reporte_viaticos_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        if (ob_get_level() > 0)
            ob_end_clean();

        $writer->save("php://output");
        exit();
    }
    public function get_viatico()
    {
        $globals = new Mglobal;
        $id = $this->request->getPost('id');

        // Obtenemos los datos directos de la vista o tabla
        $tabla = $globals->getTabla([
            "tabla" => "vw_juridico_viaticos",
            "where" => ["id_juridico_viatico" => $id]
        ]);

        // Devolvemos el primer resultado como JSON con el formato correcto de CI4
        if (!empty($tabla->data[0])) {
            return $this->response->setJSON($tabla->data[0]);
        }
        else {
            return $this->response->setJSON(['error' => 'No se encontraron datos']);
        }
    }
    public function getUsuarios()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $dataDB = array();
        if ($session->id_perfil == -1) {
            $dataDB = array('tabla' => 'vw_usuario', 'where' => ['visible' => 1]);
        }
        elseif ($session->id_perfil == 1) {
            $dataDB = array('tabla' => 'vw_usuario', 'where' => ['visible' => 1]);
        }
        $response = $principal->getTabla($dataDB);
        // var_dump($response);
        // die();
        return $this->respond($response->data);
    }
    public function guardarTiket()
    {
        $session = \Config\Services::session();
        $tiket = $this->request->getPost('randomTicket');
        $opcion = $this->request->getPost('opcion');

        $principal = new Mglobal;
        $hoy = date("Y-m-d H:i:s");
        $dataInsert = [
            'no_tiket' => $tiket,
            'descripcion' => $opcion,
            'usuario' => $session->get('id_usuario'),
            'fec_reg' => $hoy
        ];
        $dataConfig = [
            "tabla" => "tiket",
            "editar" => false
        ];
        $response = $principal->saveTabla($dataInsert, $dataConfig, ["script" => "Usuario.tiket"]);
        return $this->respond($response);
    }
    public function getUsuario()
    {
        $session = \Config\Services::session();
        $id_usuario = $this->request->getPost('id_usuario');

        // Validar que el ID de usuario esté presente y sea válido
        if (!$id_usuario) {
            return $this->fail('ID de usuario no proporcionado', 400);
        }

        // var_dump($id_usuario);
        // die();
        $response = $this->globals->getTabla(["tabla" => "vw_usuario", "where" => ["id_usuario" => $id_usuario, "visible" => 1]])->data;
        //var_dump($response[0]);
        //die();
        return $this->respond($response[0]);
    }
    public function deleteParticipante()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();

        if (!isset($data['id_participante']) || empty($data['id_participante'])) {
            $response->respuesta = "No se ha proporcionado un identificador válido";
            return $this->respond($response);
        }

        $dataConfig = [
            "tabla" => "participantes",
            "editar" => true,
            "idEditar" => ['id_participante' => $data['id_participante']]
        ];
        $response = $this->globals->saveTabla(["visible" => 0], $dataConfig, ["script" => "Usuario.deleteUsuario"]);
        return $this->respond($response);
    }
    public function deleteUsuario()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();

        if (!isset($data['id_usuario']) || empty($data['id_usuario'])) {
            $response->respuesta = "No se ha proporcionado un identificador válido";
            return $this->respond($response);
        }

        $dataConfig = [
            "tabla" => "usuario",
            "editar" => true,
            "idEditar" => ['id_usuario' => $data['id_usuario']]
        ];
        $response = $this->globals->saveTabla(["visible" => 0], $dataConfig, ["script" => "Usuario.deleteUsuario"]);
        return $this->respond($response);
    }
    public function estudianteCurso()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();

        if (!isset($data['id_estudiante_curso']) || empty($data['id_estudiante_curso'])) {
            $response->respuesta = "No se ha proporcionado un identificador válido";
            return $this->respond($response);
        }

        $dataConfig = [
            "tabla" => "estudiante_curso",
            "editar" => true,
            "idEditar" => ['id_estudiante_curso' => $data['id_estudiante_curso']]
        ];
        $response = $this->globals->saveTabla(["visible" => 0], $dataConfig, ["script" => "Usuario.deleteUsuario"]);
        return $this->respond($response);

    }
    public function deleteDetenido()
    {
        $response = new \stdClass();
        $response->error = true;
        $response->repuesta = "Error|Error al guardar en la base de datos";
        $data = $this->request->getPost();

        if (!isset($data['id_detenido']) || empty($data['id_detenido'])) {
            $response->respuesta = "No se ha proporcionado un identificador válido";
            return $this->respond($response);
        }

        $dataConfig = [
            "tabla" => "detenidos",
            "editar" => true,
            "idEditar" => ['id_detenido' => $data['id_detenido']]
        ];
        $result = $this->globals->saveTabla(["visible" => 0], $dataConfig, ["script" => "Usuario.deleteDetenido"]);
        if (!$result->error) {
            $response->error = $result->error;
            $response->respuesta = $result->respuesta;

        }
        return $this->respond($response);
    }


    public function getCursos()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $id_cat = $this->request->getPost('id_cat');
        $result = $this->globals->getTabla(["tabla" => "cursos_sac", "where" => ["visible" => 1]]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
            $response->data = $result->data;
        }

        return $this->respond($response->data);
    }
    public function getPerfil()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $id_perfil = $this->request->getPost('id_perfil');
        $result = $this->globals->getTabla(["tabla" => "perfil", "where" => ["id_perfil" => $id_perfil]]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
            $response->dsc_perfil = $result->data[0]->dsc_perfil;
        }

        return $this->respond($response);
    }
    public function getArea()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $id_area = $this->request->getPost('id_area');
        $result = $this->globals->getTabla(["tabla" => "cat_area", "where" => ["id_area" => $id_area]]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
            $response->data = $result->data[0];
        }

        return $this->respond($response);
    }
    public function getPuesto()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $id_puesto = $this->request->getPost('id_puesto');
        $result = $this->globals->getTabla(["tabla" => "cat_puesto", "where" => ["id_puesto" => $id_puesto]]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
            $response->dsc_puesto = $result->data[0]->dsc_puesto;
        }

        return $this->respond($response);
    }
    public function verDetalle()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $id_curso = $this->request->getPost('id_curso');
        $response->error = true;
        $response->data = []; // Inicializa un array en la propiedad 'data'

        $categoria = $this->globals->getTabla([
            "tabla" => "vw_categoria",
            "where" => ["id_curso" => $id_curso, 'visible' => 1]
        ]);

        $periodo = $this->globals->getTabla([
            "tabla" => "vw_periodo",
            "where" => ["id_curso" => $id_curso, 'visible' => 1]
        ]);
        $curso = $this->globals->getTabla([
            "tabla" => "cursos_sac",
            "where" => ["id_cursos_sac" => $id_curso, 'visible' => 1]
        ]);

        if (!$categoria->error) {
            $response->error = false;
            $response->respuesta = $categoria->respuesta;
            $response->data['categoria'] = $categoria->data; // Corrige la asignación del array
        }

        if (!$periodo->error) {
            $response->error = false;
            $response->respuesta = $periodo->respuesta;
            $response->data['periodo'] = $periodo->data; // Corrige la asignación del array
        }
        if (!$curso->error) {
            $response->error = false;
            $response->respuesta = $curso->respuesta;
            $response->data['curso'] = $curso->data; // Corrige la asignación del array
        }

        return $this->respond($response);
    }

    public function obtenerPerfil()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $result = $this->globals->getTabla(["tabla" => "perfil", 'where' => ['visible' => 1]]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
            $response->data = $result->data;

        }
        return $this->respond($response->data);
    }
    public function obtenerCursosSac()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $result = $this->globals->getTabla(["tabla" => "cursos_sac", 'where' => ['visible' => 1]]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
            $response->data = $result->data;

        }
        return $this->respond($response->data);
    }
    public function getCursoSac()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $id_curso = $this->request->getPost('id_curso');
        $cursos = $this->globals->getTabla(["tabla" => "cursos_sac", 'where' => ['id_cursos_sac' => $id_curso, 'visible' => 1]]);
        $categoria = $this->globals->getTabla(["tabla" => "vw_categoria", 'where' => ['id_curso' => $id_curso, 'visible' => 1]]);
        $periodo = $this->globals->getTabla(["tabla" => "vw_periodo", 'where' => ['id_curso' => $id_curso, 'visible' => 1]]);
        if (!$cursos->error) {
            $response->error = false;
            $response->respuesta = $cursos->respuesta;
            $response->data['curso'] = $cursos->data;
        }
        if (!$categoria->error) {
            $response->error = false;
            $response->respuesta = $categoria->respuesta;
            $response->data['categoria'] = $categoria->data;
        }
        if (!$periodo->error) {
            $response->error = false;
            $response->respuesta = $periodo->respuesta;
            $response->data['periodo'] = $periodo->data;
        }
        return $this->respond($response);
    }
    public function guardarArea()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;

        // Validar que los datos POST existen
        if (!$this->request->getPost()) {
            $response->respuesta = "No se recibieron datos";
            return $this->respond($response);
        }
        $data = $this->request->getPost();
        //die( var_dump( $data ) );
        // Preparar datos según el tipo de operación

        switch ($data['editar']) {
            case 1: // Editar perfil
                if (empty($data['dsc_area']) || empty($data['dsc_area'])) {
                    $response->respuesta = "El Area es requerida";
                    return $this->respond($response);
                }
                if (empty($data['dsc_corto']) || empty($data['dsc_corto'])) {
                    $response->respuesta = "Las SIGLAS son requeridas";
                    return $this->respond($response);
                }
                if (empty($data['id_usuario']) || empty($data['id_usuario'])) {
                    $response->respuesta = "El titular es requerido";
                    return $this->respond($response);
                }

                $dataInsert = [
                    'dsc_area' => trim($data['dsc_area']),
                    'dsc_corto' => $data['dsc_corto'],
                    'prefijo' => isset($data['prefijo']) ? $data['prefijo'] : '',
                    'titular' => (int)$data['id_usuario'],
                ];
                $dataConfig = [
                    "tabla" => "cat_area",
                    "editar" => true,
                    "idEditar" => ['id_area' => (int)$data['id_area']]
                ];
                break;

            case 2: // Desactivar perfil
                if (empty($data['id_area'])) {
                    $response->respuesta = "Falta el ID del perfil";
                    return $this->respond($response);
                }

                $dataInsert = [
                    'visible' => 0,
                ];
                $dataConfig = [
                    "tabla" => "cat_area",
                    "editar" => true,
                    "idEditar" => ['id_area' => (int)$data['id_area']]
                ];
                break;
            default: // Nuevo perfil


                $dataInsert = [
                    'dsc_area' => $data['comentario'],
                ];
                $dataConfig = [
                    "tabla" => "cat_area",
                    "editar" => false
                ];


        }

        // Intentar guardar
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaCategoriasPadre'];
        try {
            $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
            return $this->respond($response);
        }
        catch (\Exception $e) {
            $response->error = true;
            $response->message = "Error al guardar: " . $e->getMessage();
            return $this->respond($response, 500);
        }
    }
    public function guardarPerfil()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;

        // Validar que los datos POST existen
        if (!$this->request->getPost()) {
            $response->message = "No se recibieron datos";
            return $this->respond($response);
        }

        $data = $this->request->getPost();



        // Preparar datos según el tipo de operación
        switch ($data['editar']) {
            case 1: // Editar perfil
                if (empty($data['comentario']) || empty($data['id_perfil'])) {
                    $response->message = "Faltan datos requeridos para editar";
                    return $this->respond($response);
                }

                $dataInsert = [
                    'dsc_perfil' => trim($data['comentario']),
                ];
                $dataConfig = [
                    "tabla" => "perfil",
                    "editar" => true,
                    "idEditar" => ['id_perfil' => (int)$data['id_perfil']]
                ];
                break;

            case 2: // Desactivar perfil
                if (empty($data['id_perfil'])) {
                    $response->message = "Falta el ID del perfil";
                    return $this->respond($response);
                }

                $dataInsert = [
                    'visible' => 0,
                ];
                $dataConfig = [
                    "tabla" => "perfil",
                    "editar" => true,
                    "idEditar" => ['id_perfil' => (int)$data['id_perfil']]
                ];
                break;

            default: // Nuevo perfil
                if (empty($data['comentario'])) {
                    $response->message = "Falta la descripción del perfil";
                    return $this->respond($response);
                }

                $dataInsert = [
                    'dsc_perfil' => trim($data['comentario']),
                    'visible' => 1 // Asegurar que nuevos perfiles estén visibles
                ];
                $dataConfig = [
                    "tabla" => "perfil",
                    "editar" => false
                ];
        }

        // Intentar guardar
        try {
            $response = $this->globals->saveTabla($dataInsert, $dataConfig, ["script" => "perfil.savePerfil"]);
            return $this->respond($response);
        }
        catch (\Exception $e) {
            $response->error = true;
            $response->message = "Error al guardar: " . $e->getMessage();
            return $this->respond($response, 500);
        }
    }
    public function guardarPuesto()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;

        // Validar que los datos POST existen
        if (!$this->request->getPost()) {
            $response->respuesta = "No se recibieron datos";
            return $this->respond($response);
        }

        $data = $this->request->getPost();



        // Preparar datos según el tipo de operación
        switch ($data['editar']) {
            case 1: // Editar perfil
                if (empty($data['comentario']) || empty($data['id_puesto'])) {
                    $response->respuesta = "Faltan datos requeridos para editar";
                    return $this->respond($response);
                }

                $dataInsert = [
                    'dsc_puesto' => trim($data['comentario']),
                ];
                $dataConfig = [
                    "tabla" => "cat_puesto",
                    "editar" => true,
                    "idEditar" => ['id_puesto' => (int)$data['id_puesto']]
                ];
                break;

            case 2: // Desactivar perfil
                if (empty($data['id_puesto'])) {
                    $response->respuesta = "Falta el ID del perfil";
                    return $this->respond($response);
                }

                $dataInsert = [
                    'visible' => 0,
                ];
                $dataConfig = [
                    "tabla" => "cat_puesto",
                    "editar" => true,
                    "idEditar" => ['id_puesto' => (int)$data['id_puesto']]
                ];
                break;

            default: // Nuevo perfil
                if (empty($data['comentario'])) {
                    $response->respuesta = "Falta la descripción del puesto";
                    return $this->respond($response);
                }

                $dataInsert = [
                    'dsc_puesto' => trim($data['comentario']),
                ];
                $dataConfig = [
                    "tabla" => "cat_puesto",
                    "editar" => false
                ];
        }

        // Intentar guardar
        try {
            $response = $this->globals->saveTabla($dataInsert, $dataConfig, ["script" => "perfil.savePerfil"]);
            return $this->respond($response);
        }
        catch (\Exception $e) {
            $response->error = true;
            $response->message = "Error al guardar: " . $e->getMessage();
            return $this->respond($response, 500);
        }
    }
    public function deleteSala()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error| Error al Generar Sala de Juntas';
        $id_sala_juntas = $this->request->getPost('id_sala_juntas');

        $dataInsert = [
            'visible' => 0,
        ];
        $dataConfig = [
            "tabla" => "sala_junta",
            "editar" => true,
            "idEditar" => ['id_sala' => $id_sala_juntas]
        ];

        $response = $this->globals->saveTabla($dataInsert, $dataConfig, ["script" => "sala_junta.eliminarjunta"]);
        return $this->respond($response);
    }
    public function activarPeriodo()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();
        if ($data['id'] == 1) {
            $dataInsert = [
                'activo' => 0,
            ];
            $dataConfig = [
                "tabla" => "periodo_sac",
                "editar" => true,
                "idEditar" => ['id_periodo_sac' => $data['id_periodo']]
            ];
        }
        if ($data['id'] == 2) {
            $dataInsert = [
                'activo' => 1,
            ];
            $dataConfig = [
                "tabla" => "periodo_sac",
                "editar" => true,
                "idEditar" => ['id_periodo_sac' => $data['id_periodo']]
            ];
        }



        $response = $this->globals->saveTabla($dataInsert, $dataConfig, ["script" => "periodo_sac.eliminarPeriodo"]);
        return $this->respond($response);
    }
    public function eliminarPeriodo()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();

        $dataInsert = [
            'visible' => 0,
        ];
        $dataConfig = [
            "tabla" => "periodo_sac",
            "editar" => true,
            "idEditar" => ['id_periodo_sac' => $data['id_periodo']]
        ];


        $response = $this->globals->saveTabla($dataInsert, $dataConfig, ["script" => "periodo_sac.eliminarPeriodo"]);
        return $this->respond($response);
    }
    public function tiketListo()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();

        $dataInsert = [
            'estatus' => 1,
        ];
        $dataConfig = [
            "tabla" => "tiket",
            "editar" => true,
            "idEditar" => ['id_tiket' => $data['id_tiket']]
        ];


        $response = $this->globals->saveTabla($dataInsert, $dataConfig, ["script" => "tiket.resulto"]);
        return $this->respond($response);
    }
    public function guardarPeriodo()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();

        if ($data['editar_periodo'] == 1) {
            $periodo = $this->globals->getTabla(["tabla" => "periodo_sac", 'where' => ['visible' => 1, 'periodo' => $data['periodo']]]);
            if (isset($periodo->data) && !empty($periodo->data)) {
                $response->respuesta = 'El periodo ya existe en la base de datos';
                return $this->respond($response);
            }

            $dataInsert = [
                'dia_inicio' => $data['diaInicio'],
                'dia_fin' => $data['diaFin'],
                'periodo' => (int)$data['periodo'],
                'mes' => (int)$data['mes'],
                'usu_act' => $session->id_usuario,

            ];
            $dataConfig = [
                "tabla" => "periodo_sac",
                "editar" => true,
                "idEditar" => ['id_periodo_sac' => $data['id_periodo']]
            ];
        }
        else {
            $periodo = $this->globals->getTabla(["tabla" => "periodo_sac", 'where' => ['visible' => 1, 'periodo' => $data['periodo']]]);
            $mes = $this->globals->getTabla(["tabla" => "periodo_sac", 'where' => ['visible' => 1, 'mes' => $data['mes']]]);
            if (isset($periodo->data) && !empty($periodo->data)) {
                $response->respuesta = 'El periodo ya existe en la base de datos';
                return $this->respond($response);
            }
            if (isset($mes->data) && !empty($mes->data)) {
                $response->respuesta = 'El mes ya existe en la base de datos';
                return $this->respond($response);
            }
            $dataInsert = [
                'dia_inicio' => $data['diaInicio'],
                'dia_fin' => $data['diaFin'],
                'periodo' => (int)$data['periodo'],
                'mes' => (int)$data['mes'],
                'fec_reg' => date('Y-m-d H:i:s'),
                'usu_reg' => $session->id_usuario,

            ];
            $dataConfig = [
                "tabla" => "periodo_sac",
                "editar" => false,
                //  "idEditar"=>['id_usuario'=>$data['id_usuario']]
            ];
        }



        $response = $this->globals->saveTabla($dataInsert, $dataConfig, ["script" => "periodo_sac.savePeriodo"]);
        return $this->respond($response);
    }
    public function optenerPeriodo()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $id_periodo = $this->request->getPost('id_periodo');
        if (isset($id_periodo) && !empty($id_periodo)) {
            $result = $this->globals->getTabla(["tabla" => "vw_periodo", 'where' => ['visible' => 1, 'id_periodo_sac' => $id_periodo]]);
            if (!$result->error) {
                $response->error = false;
                $response->respuesta = $result->respuesta;
                $response->data = $result->data;

            }
            return $this->respond($response->data[0]);
        }

    }

    public function getPeriodos()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();
        $result = $this->globals->getTabla(["tabla" => "periodo_sac", 'where' => ['visible' => 1]]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
            $response->data = $result->data;

        }
        return $this->respond($response->data);
    }
    public function getSelectPeriodos()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();
        $result = $this->globals->getTabla(["tabla" => "periodo_sac", 'where' => ['visible' => 1]]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
            $response->data['periodo'] = $result->data;

        }
        $result = $this->globals->getTabla(["tabla" => "categoria_sac", 'where' => ['visible' => 1]]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
            $response->data['categoria'] = $result->data;

        }
        return $this->respond($response->data);
    }
    public function activarCursoSac()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $id_curso_sac = $this->request->getPost('id_curso_sac');
        $editar = $this->request->getPost('editar');
        if ($editar == 3) {
            $dataInsert = [
                'activo' => 0,
                'usu_act' => $session->id_usuario
            ];
            $dataConfig = [
                "tabla" => "cursos_sac",
                "editar" => true,
                "idEditar" => ["id_cursos_sac" => $id_curso_sac]
            ];
        }
        else {
            $dataInsert = [
                'activo' => 1,
                'usu_act' => $session->id_usuario
            ];
            $dataConfig = [
                "tabla" => "cursos_sac",
                "editar" => true,
                "idEditar" => ["id_cursos_sac" => $id_curso_sac]
            ];
        }
        $result = $this->globals->saveTabla($dataInsert, $dataConfig, ["script" => "categoria_sac.saveCategoria"]);
        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;

        }
        return $this->respond($response);
    }

    public function UpdateUsuario()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();
        // var_dump(isset($data['editar']));
        // die();

        $dataInsert = [
            'usuario' => $data['usuario'],
            'contrasenia' => md5($data['contrasenia']),
            'correo' => $data['correo'],
            'id_perfil' => $data['perfil'],
            'id_sexo' => $data['sexo'],
            'nombre' => $data['nombre'],
            'primer_apellido' => $data['primer_apellido'],
            'segundo_apellido' => $data['segundo_apellido'],
            'id_clues' => $data['id_clues'],
        ];
        // var_dump($dataInsert);
        // die();
        if (isset($data['editar'])) {
            $dataConfig = [
                "tabla" => "seg_usuarios",
                "editar" => false,
                //  "idEditar"=>['id_usuario'=>$data['id_usuario']]
            ];
        }
        else {
            $dataConfig = [
                "tabla" => "seg_usuarios",
                "editar" => true,
                "idEditar" => ['id_usuario' => $data['id_usuario']]
            ];
        }


        $response = $this->globals->saveTabla($dataInsert, $dataConfig, ["script" => "Usuario.saveUsuario"]);
        return $this->respond($response);
    }
    public function saveUsuario()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();
        var_dump($data['id_usuario']);
        die();
        // if (!isset($data['id_usuario']) || empty($data['id_usuario'])){
        //     $response->respuesta = "No se ha proporcionado un identificador válido";
        //     return $this->respond($response);
        // }
        // $dataInsert=[
        //     'dsc_carpeta'          => $dsc_carpeta,
        //     'id_carpeta_padre'  => $id_carpeta_raiz,
        //     'id_unidad'           => $id_unidad,
        //     'ruta'           => $ruta_raiz.'/'.$nombre_unix,
        //     'ruta_real'       => $ruta_carpeta_fisica,
        //     'fecha_registro'       => date('Y-m-d H:i:s'),
        //     'usuario_registro' => $session->id_usuario,
        //     'visible'     => 1,
        //     'nombre_carpeta'     => $nombre_unix
        // ];

        $dataConfig = [
            "tabla" => "seg_usuarios",
            "editar" => false,
            // "idEditar"=>['id_usuario'=>$data['id_usuario']]
        ];
        $response = $this->globals->saveTabla($dataInsert, $dataConfig, ["script" => "Usuario.saveUsuario"]);
        return $this->respond($response);
    }




}