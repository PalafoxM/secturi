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


use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
 

class Principal extends BaseController {

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
        if($session->get('logueado')!= 1){
            header('Location:'.base_url().'index.php/Login/cerrar?inactividad=1');            
            die();
        }
    }

    private function _renderView($data = array()) {     
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
        $session  = \Config\Services::session();
        $globals  = new Mglobal;
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
                if (empty($row['fecha'])) continue;

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
                if(isset($proveedor->data) && !empty($proveedor->data)){
                      $registro = [
                            'id_usuario'  => $proveedor->data[0]->id_usuario,
                            'fecha'       => DateTime::createFromFormat('d/m/Y', $row['fecha'])->format('Y-m-d'),
                            'turno'       => $row['turno'] ?? null,
                            'entrada'     => $row['entrada'] ?? null,
                            'salida'      => $row['salida'] ?? null,
                            'trabajado'   => $row['trabajado'] ?? null,
                            'tarde'       => $tarde,
                            'temprano'    => $temprano,
                        ];
                     $dataConfig = [
                        "tabla"=>"asistencia",
                        "editar"=>false
                    ];
                    $response = $globals->saveTabla($registro,$dataConfig,["script"=>"Usuario.tiket"]);
                    $response->error = false;
                    $response->respuesta = 'Carga se guardo correctamente';
                    
                }

            }

        }


        return $this->respond($response);
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
  
    public function imprimer_qr($id_usuario)
    {
        // Ruta del QR
        $tempQrPath = FCPATH . 'assets/images/qr_final.png';

        // Generar el QR
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data(base_url().'index.php/Login?id_user=' . $id_usuario)
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
         $dataImagen = $this->encode_img_base64(FCPATH .'assets/images/qr_final.png', 'png');
         $data = array();
         $data['dataImagen'] =  $dataImagen;
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
               
                $curpDB = $this->globals->getTabla(['tabla' => 'participantes', 'where' => [
                    'visible' => 1,
                    'id_dependencia' => $session->get('id_dependencia'),
                    'curp' => $d['curp']
                ]]);
                $correoDB = $this->globals->getTabla(['tabla' => 'participantes', 'where' => [
                    'visible' => 1,
                    'id_dependencia' => $session->get('id_dependencia'),
                    'correo' => $d['correo']
                ]]);
    
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
                    'nombre'             => $c['nombre'],
                    'primer_apellido'    => $c['primer_apellido'],
                    'segundo_apellido'   => $c['segundo_apellido'],
                    'curp'               => $c['curp'],
                    'correo'             => $c['correo'],
                   // 'fec_nac'            => date("Y-m-d H:i:s", strtotime($c['fec_nac'])),
                    'centro_gestor'      => $c['centro_gestor'],
                    'jefe_inmediato'     => $c['jefe_inmediato'],
                    'area'               => $c['area'],
                    'rfc'                => substr($c['curp'], 0, 10), 
                    'observaciones'      => $c['observaciones'],
                    //'id_sexo'            => ($c['sexo'] == 'HOMBRE') ? 1 : 2,
                    'id_municipio'       => 15,
                    'id_dependencia'     => (int)$session->get('id_dependencia'),
                    'id_dep_padre'       => (int)$session->get('id_padre'),
                    'id_nivel'           => (int)$c['nivel'],
                    'fec_reg'            => date("Y-m-d H:i:s"),
                    'usu_reg'            => $session->get('id_usuario')
                ];
                $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarDetenido'];
                $dataConfig = ["tabla" => "detenidos", "editar" => false];
                $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
            }
        }
    
        if (!empty($dataClean)) {
            foreach ($dataClean as $c) {
                $dataInsert = [
                    'nombre'             => $c['nombre'],
                    'primer_apellido'    => $c['primer_apellido'],
                    'segundo_apellido'   => $c['segundo_apellido'],
                    'curp'               => $c['curp'],
                    'correo'             => $c['correo'],
                    'fec_nac'            => $c['fecha_nacimiento'],
                    'centro_gestor'      => $c['centro_gestor'],
                    'jefe_inmediato'     => $c['jefe_inmediato'],
                    'area'               => $c['area'],
                    'rfc'                => substr($c['curp'], 0, 10),
                    'edad'               => $c['edad'],
                    'id_sexo'            => ($c['sexo'] == 'H') ? 1 : 2,
                    'id_municipio'       => 15,
                    'id_dependencia'     => (int)$session->get('id_dependencia'),
                    'id_dep_padre'       => (int)$session->get('id_padre'),
                    'id_nivel'           => (int)$c['nivel'],
                    'fec_reg'            => date("Y-m-d H:i:s"),
                    'usu_reg'            => $session->get('id_usuario')
                ];
                $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarParticipantes'];
                $dataConfig = ["tabla" => "participantes", "editar" => false];
                $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
            }
        }
    }
    function validarCURP($curp) {
        // Lista de códigos de entidades válidos en México
        $response = new \stdClass();
        $response->error = true;
        $entidadesValidas = [
            'AS', 'BC', 'BS', 'CC', 'CL', 'CM', 'CS', 'CH', 'DF', 'DG', 'GT', 
            'GR', 'HG', 'JC', 'MC', 'MN', 'MS', 'NT', 'NL', 'OC', 'PL', 'QT', 
            'QR', 'SP', 'SL', 'SR', 'TC', 'TL', 'TS', 'VZ', 'YN', 'ZS'
        ];
        
        // Validación de longitud de 18 caracteres y el formato general
        if (strlen($curp) !== 18 ) {
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
            return $response;; // CURP no válida por consonantes internas incorrectas
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
    public function guardarParticipantes()
    {  
        $session = \Config\Services::session();
        $response = new \stdClass();
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        
    
        // Validación de campos requeridos
        $result = $this->validarCamposRequeridos($data);
         if($result->error){
             $response->error = true;
             $response->respuesta =  $result->respuesta;
             return $this->respond($response);
         }  
        
     

        // Configuración de bitácora
        $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Agregar.php/guardaParticipante'];

        // Verificación de unicidad para CURP y correo
  
        if($data['editar'] == 1 && $data['id_detenido'] != 0 || $data['id_participante'] ==0){
            if (!$this->verificarUnicidad('curp', $data['curp']) || !$this->verificarUnicidad('correo', $data['correo'])) {
                $response->error = true;
                $response->respuesta = !$this->verificarUnicidad('curp', $data['curp']) ? 'La CURP ya existe en la base de datos' : 'El correo ya existe en la base de datos';
                return $this->respond($response);
            }
        }
        
        $hoy = date("Y-m-d H:i:s"); 
        $dataInsert = [
            'curp'                  => $data['curp'],           
            'curp_viejo'            => $data['curp_viejo'],           
            'nombre'                => $data['nombre'],           
            'primer_apellido'       => $data['primer_apellido'],           
            'segundo_apellido'      => $data['segundo_apellido'],           
            'fec_nac'               => date("Y-m-d", strtotime($data['fec_nac'])),   
            'rfc'                   => $data['rfc'],   
            'correo'                => $data['correo'],   
            'id_sexo'               => (int)$data['id_sexo'],   
            'id_nivel'              => (int)$data['id_nivel'],   
            'id_dependencia'        => (int)$session->get('id_dependencia'),   
            'funcion'               => $data['funcion'],   
            'denominacion_funcional'=> $data['denominacion_funcional'],   
            'area'                  => $data['area'],   
            'jefe_inmediato'        => $data['jefe_inmediato'],   
            'id_municipio'          => (int)$data['id_municipio'],   
            'centro_gestor'         => $data['centro_gestor'],   
            'correo_enlace'        => $data['correo_enlace'],   
            'id_dep_padre'          => $session->get('id_dependencia'),
            'usu_reg'               => $session->get('id_usuario'),
            'fec_reg'               => $hoy   
        ];
     

     
       //agregar nuevo
        if($data['editar'] == 0 && $data['id_participante'] == 0){
            $dataConfig = [
                "tabla" => "participantes",
                "editar" => false,
               // "idEditar" => ['id_participante' => $data['id_participante']]
            ];
        }
        //editar participante
        if($data['editar'] == 1 && $data['id_participante'] != 0){
            $dataConfig = [
                "tabla" => "participantes",
                "editar" => true,
                "idEditar" => ['id_participante' => $data['id_participante']]
            ];
        }
        //editar detenido
        if($data['editar'] == 1 && $data['id_detenido'] != 0){
            $dataConfig = [
                "tabla" => "participantes",
                "editar" => false,
               // "idEditar" => ['id_participante' => $data['id_participante']]
            ];
        }

       
        $response = $this->globals->saveTabla($dataInsert, $dataConfig, $dataBitacora);
      
        // Si es una edición, marcar al participante en detenidos como inactivo
        if ($data['editar'] == 1 && $data['id_detenido'] != 0) {
            $dataConfigDetenidos = ["tabla" => "detenidos", "editar" => true, "idEditar" => ['id_detenido' => $data['id_detenido']]];
            $dataDetenidos = ['visible' => 0];
            $result = $this->globals->saveTabla($dataDetenidos, $dataConfigDetenidos, $dataBitacora);
            $response->error = $result->error;
            $response->respuesta = $result->respuesta;
        }
        // ahora insertamos en la de participante 
      

        return $this->respond($response);
    }
    private function verificarUnicidad($campo, $valor)
    {
        $session = \Config\Services::session();
        $registro = $this->globals->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1, $campo => $valor, 'id_dependencia' => $session->get('id_dependencia'), ]]);
        return empty($registro->data);
    }
    private function validarCamposRequeridos($data)
    {
       
        $response = new \stdClass();
        $response->error = false;
        $response->respuesta = 'campos requeridos';
        if(empty($data['curp'])){
            $response->error = true;
            $response->respuesta = 'El campo curp es requerido';
        }
        if(empty($data['correo'])){
            $response->error = true;
            $response->respuesta = 'El campo correo es requerido';
        }
        if(empty($data['fec_nac'])){
            $response->error = true;
            $response->respuesta = 'El campo fecha de nacimiento es requerido';
        }
        if(empty($data['primer_apellido'])){
            $response->error = true;
            $response->respuesta = 'El campo primer apellido es requerido';
        }
        if(empty($data['id_municipio'])){
            $response->error = true;
            $response->respuesta = 'El campo municipio es requerido';
        }
        if(empty($data['correo_enlace'])){
            $response->error = true;
            $response->respuesta = 'El campo correo del enlace es requerido';
        }
        if($data['id_nivel'] == 'Seleccione'){
            $response->error = true;
            $response->respuesta = 'El campo nivel es requerido';
        }
        if(empty($data['id_sexo'])){
            $response->error = true;
            $response->respuesta = 'El campo sexo es requerido';
        }
        return $response;
    }
    public function crearEvento()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new \stdClass();
        $vw = $Mglobal->getTabla(['tabla' => 'vw_estudiante_curso','where' => ['visible' => 1, 'id_dependencia' => $session->id_dependencia]
        ])->data;
        if($session->id_perfil === 1){
            $dataConfig = ["tabla"=>"estudiante_curso", "editar"=>true,"idEditar"=>['id_dependencia'=>$session->id_dependencia]
            ];
        }
        if($session->get('id_perfil') === 4){
            $cursoPadre  = $globals->getTabla(['tabla' => 'cursos_perfil', 'where' => ['visible' => 1, 'id_padre' => 4]]);
        }
        $dataBitacora = ['id_user' =>  $session->id_usuario, 'script' => 'Agregar.php/guardaCurso'];
        $dataConfig = [
            "tabla"=>"categoria",
            "editar"=>false,
            // "idEditar"=>['id_usuario'=>$data['id_usuario']]
        ];
        $Insert = [
            'dsc_categoria'  => $response->data[0]->name,                      
            'id_moodle_categoria'      => $response->data[0]->id,                      
            'fec_reg'        => $hoy   
        ];
       $response = $this->globals->saveTabla($Insert,$dataConfig,$dataBitacora);
        die();

    }
    public function Matricular()
    {  
       
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $cursos = [];
        if($session->get('id_perfil') === 1){
            $cursoPadre  = $globals->getTabla(['tabla' => 'cursos_perfil', 'where' => ['visible' => 1]]);
        }
        if($session->get('id_perfil') === 4){
            $cursoPadre  = $globals->getTabla(['tabla' => 'cursos_perfil', 'where' => ['visible' => 1, 'id_padre' => 4]]);
        }
        if($session->get('id_perfil') >= 5){
            $cursoPadre  = $globals->getTabla(['tabla' => 'cursos_perfil', 'where' => ['visible' => 1, 'id_padre' => $session->get('id_padre')]]);
        }


        if(isset($cursoPadre->data) && !empty($cursoPadre->data)){
            $id_cursos= $cursoPadre->data;
            foreach ($id_cursos as $key) {
                $data = ['courseId' => $key->id_curso];
                $details = $globals->createCurso($data, 'getCourseDetailsById');
            
                if (isset($details->data) && !empty($details->data)) {
                    $cursos[] = [
                        'id' => $details->data[0]->id,
                        'shortname' => $details->data[0]->shortname,
                        'fullname' => $details->data[0]->fullname,
                        'startdate' => date('d-m-Y', $details->data[0]->startdate),
                        'enddate' => date('d-m-Y', $details->data[0]->enddate),
                    ];
                }
            }
        }

        if ($session->get('id_perfil') >= 5) {
            $participantes = $globals->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1, 'id_dependencia' => $session->get('id_dependencia')]]);
        } 
        if ($session->get('id_perfil') == 4 || $session->get('id_perfil') == 4){
            $participantes = $globals->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1, 'id_dep_padre'  => $session->get('id_perfil')]]);
        }
        if ($session->get('id_perfil') == 1){
            $participantes = $globals->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1]]);
        }

      
    
            // Add full name to each filtered $detenidos record
            foreach ($participantes->data as $d) {
                $d->nombre_completo = $d->nombre . ' ' . $d->primer_apellido . ' ' . $d->segundo_apellido;
            }
        
        //die( var_dump( $participantes ) );
     
        $data['cursos'] = $cursos;
        $data['participantes'] = (!empty($participantes->data))?$participantes->data:'';
        $data['scripts'] = array('principal');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vMatricular';                
        $this->_renderView($data);
        
    }
    public function guardarReservaEditar()
    {  
       
        $session = \Config\Services::session();
        $email =  \Config\Services::email();
        $globals      = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al Guardar los datos';
        $data =  $this->request->getPost();
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


        if(isset($data['no_convenio']) && empty($data['no_convenio'])){
            $response->error = true;
            $response->respuesta = "El campo No. Convenio es requerido";
             return $this->respond($response);
        }
        $hoy = date("Y-m-d H:i:s"); 
    
        $dataInsert = [
            "total_importe" => $data['total_importe'],
            "no_convenio"   =>$data['no_convenio'],
            "id_estatus"   => 1,
            "usu_act"       => $session->get('id_usuario')
        ];

       if (!empty($ruta_relativa)) {
            $dataInsert['instrumento']    = $ruta_relativa;
            $dataInsert['ruta_absoluta']  = $ruta_absoluta;
        }
        
         $dataBitacora = ['id_user' =>  $session->get('id_usuario'), 'script' => 'Agregar.php/EditarReserva'];
         $dataConfig = [
            "tabla"=>"reserva",
            "editar"=>true,
           "idEditar"=>['id_reserva'=>$data['id_reserva']]
        ];
         $response = $globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
      
         if(!$response->error){
               $i = 0;
                foreach($data['id_presupuesto'] as $d){
                    $dataInsert = [
                            "id_proyecto"    => $data['proyecto'][$i],
                            "id_partida"     => $data['partida'][$i],
                            "importe"        => $data['importe'][$i], 
                            "usu_act"        => $session->get('id_usuario')
                            
                        ];
                         $dataConfig = [
                            "tabla"=>"presupuesto",
                            "editar"=>true,
                            "idEditar"=>['id_presupuesto'=>$d]
                        ];
     
                        $res = $globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
                        if(!$res->error){
                         $response->error = $res->error;
                         $response->respuesta = $res->respuesta;
                     
                        }
                            $i++;
                }
            
         }
/*         $email->setTo([
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
    public function guardarReserva()
    {  
       
        $session = \Config\Services::session();
        $email =  \Config\Services::email();
        $globals      = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al Guardar los datos';
        $data =  $this->request->getPost();
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


        if(isset($data['no_convenio']) && empty($data['no_convenio'])){
            $response->error = true;
            $response->respuesta = "El campo No. Convenio es requerido";
             return $this->respond($response);
        }
        $hoy = date("Y-m-d H:i:s"); 
    
        $dataInsert = [
            "id_proveedor"      => (int)$data['id_proveedor'],
            "total_importe"     => $data['total_importe'],
            "id_proveedor_banco"=> (int)$data['banco'],
            "fec_reg"           => $hoy, 
            "usu_reg"           => $session->get('id_usuario')
             
        ];
       if (!empty($ruta_relativa)) {
            $dataInsert['instrumento']    = $ruta_relativa;
            $dataInsert['ruta_absoluta']  = $ruta_absoluta;
            $dataInsert['no_convenio']    = $data['no_convenio'];
        }
        
         $dataBitacora = ['id_user' =>  $session->get('id_usuario'), 'script' => 'Agregar.php/guardaReserva'];
         $dataConfig = [
            "tabla"=>"reserva",
            "editar"=>false,
            // "idEditar"=>['id_usuario'=>$data['id_usuario']]
        ];
         $response = $globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
       
         if(!$response->error){
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
                    "tabla"=>"presupuesto",
                    "editar"=>false,
                    // "idEditar"=>['id_usuario'=>$data['id_usuario']]
                ];
                foreach($datosCombinados as $d){
                    $dataInsert = [
                            "id_reserva"     => $id_reserva,
                            "id_proyecto"    => $d['proyecto'],
                            "id_partida"     => $d['partida'],
                            "importe"        => $d['importe'], 
                            "fec_reg"        => $hoy, 
                            "usu_reg"        => $session->get('id_usuario')
                            
                        ];
     
                        $res = $globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
                        if(!$res->error){
                         $response->error = $res->error;
                         $response->respuesta = $res->respuesta;
                     
                        }
                }
         }
    //$this->enviarEmail();

    return $this->respond($response);
    }
  
    private function enviarEmail()
    {
        $session = \Config\Services::session();
        $email =  \Config\Services::email();
        $globals      = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al enviar el correo';

          $email->setTo([
            'alopez@guanajuato.gob.mx',
            'negonzalez@guanajuato.gob.mx',
            'dhernandezq@guanajuato.gob.mx'
          ]); 

       // $email->setTo('palafox.marin31@gmail.com'); // destinatario principal
        // $email->setCC(['palafox.marin@hotmail.com', 'palafox.marin31@gmail.com']); // copia visible
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
    private function envioCorreoJefeInmediato()
    {
        $session = \Config\Services::session();
        $email =  \Config\Services::email();
        $globals      = new Mglobal;
        $response = new \stdClass();
        $response->error = true;

        $id_jefe_inmediato = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->get('id_usuario')]])->data[0]->id_jefe_inmediato;
        $correoJefe = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $id_jefe_inmediato]])->data[0]->correo;

       $email->setTo($correoJefe);

       $email->setSubject('Solicitud de Autorización de Incidencia');

        $email->setMessage('
            <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    <!-- Encabezado con logotipo -->
                    <div style="background-color: #004080; padding: 20px; text-align: center;">
                        <img src="' . base_url('assets/images/logo.png') . '" alt="Logo" style="height: 60px;">
                    </div>
                    <!-- Contenido principal -->
                    <div style="padding: 30px; color: #333;">
                        <h2 style="color: #004080;">Solicitud de autorización de incidencia</h2>
                        <p style="font-size: 16px;">El usuario <strong>' . $session->get('nombre_completo') . '</strong> ha registrado una incidencia en el sistema <strong>SUSI</strong>.</p>
                        <p style="font-size: 16px;">Le solicitamos amablemente su <strong>revisión y autorización</strong> para proceder con el trámite correspondiente.</p>
                        <p style="font-size: 15px;">Puede consultar y validar esta incidencia ingresando al sistema o siguiendo su flujo de aprobación.</p>
                        <p style="font-size: 15px; color: #888;">Este mensaje fue generado automáticamente por el sistema SUSI. No es necesario responder a este correo.</p>
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
    }
    public function guardarIncidencia()
    {  
        $session = \Config\Services::session();
        $globals      = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $data =  $this->request->getPost();
        $dataInsert = [
            "hora_inicio"        => $data['hora_inicio'],
            "hora_fin"           => $data['hora_fin'],
            "cat_id_incidencia"  => (int)$data['tipo_incidencia'],
            "fecha"              => $data['fecha'],
            "comentario"         => $data['comentario'],
            "detalles"           => $data['detalles'],
            "id_usuario"         => $session->get('id_usuario'),
            "fec_reg"            => date('Y-m-d H:i:s'),
        ]; 
           $dataConfig = [
                "tabla"     => "incidencia",
                 "editar"   => false,
                ];
        $result = $globals->saveTabla($dataInsert, $dataConfig, ["script" => "guardar.incidencia"]);  
        if(!$result->error){
           $response->error= false; 
           $response->respuesta= $result->respuesta; 
        }
        $this->envioCorreoJefeInmediato(); 
        return $this->respond($response);
    }
    public function actualizarBanco()
    {  
        $session = \Config\Services::session();
        $globals      = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $data =  $this->request->getPost();
        $dataInsert = [
            "banco"     =>  $data['banco'],
            "no_cuenta" =>  $data['no_cuenta'],
            "clabe"     =>  $data['clabe'],
        ]; 
           $dataConfig = [
                "tabla"     => "proveedor_banco",
                 "editar"   => true,
                 "idEditar" => ["id_proveedor_banco" => $data['id_proveedor_banco']]
                ];
        $result = $globals->saveTabla($dataInsert, $dataConfig, ["script" => "proveedor_banco.editarBanco"]); 
        if(!$result->error){
            $response->error = false;
            $response->respuesta = "Datos guardados correctamente";
        }         
        return $this->respond($response);
    }
    public function eliminarBanco()
    {  
        $session = \Config\Services::session();
        $globals      = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $id_proveedor_banco =  $this->request->getPost('id_proveedor_banco');
     
           $dataConfig = [
                "tabla"     => "proveedor_banco",
                 "editar"   => true,
                 "idEditar" => ["id_proveedor_banco" => $id_proveedor_banco]
                ];
        $result = $globals->saveTabla(['visible'=> 0], $dataConfig, ["script" => "proveedor_banco.eliminarBanco"]); 
        if(!$result->error){
            $response->error = false;
            $response->respuesta = "Datos guardados correctamente";
        }         
        return $this->respond($response);
    }
    public function eliminarProveedor()
    {  
        $session = \Config\Services::session();
        $globals      = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $id_proveedor =  $this->request->getPost('id_proveedor');
        $dataConfig = [
                 "tabla"     => 'proveedor',
                 "editar"   => true,
                 "idEditar" => ['id_proveedor'=> $id_proveedor]
                ];
    
        $result = $globals->saveTabla(['visible'=>0], $dataConfig, ["script" => "proveedo.eliminarProveedor"]); 
        if(!$result->error){
           $response->error = false;
           $response->respuesta = $result->respuesta;
        }
        return $this->respond($response);
    }
    public function agregarProveedor()
    {  
        $session = \Config\Services::session();
        $globals      = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $data =  $this->request->getPost();
     
        $dataConfig = [
                "tabla"   => 'proveedor',
                 "editar" => false
                ];
        $dataInsert = [
                "id_tipo_proveedor" => 1,
                "razon_social"      => $data['razon_social'],
                "no_proveedor"      => $data['no_proveedor'],
                "rfc"               => $data['rfc'],
                ];
        $result = $globals->saveTabla($dataInsert, $dataConfig, ["script" => "proveedo.agregarProveedo"]); 
        if(!$result->error){
           $response->error = false;
           $response->respuesta = $result->respuesta;
        }
        return $this->respond($response);
    }
    public function agregarBanco()
    {  
        $session = \Config\Services::session();
        $globals      = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $data =  $this->request->getPost();
        $dataConfig = [
                "tabla"   => 'proveedor_banco',
                 "editar" => false
                ];
        $dataInsert = [
                "idproveedor" => $data['id_proveedor'],
                "banco"       => $data['banco'],
                "no_cuenta"   => $data['no_cuenta'],
                "clabe"       => $data['clabe'],
                ];
        $result = $globals->saveTabla($dataInsert, $dataConfig, ["script" => "proveedor_banco.agregarBanco"]); 
        if(!$result->error){
           $response->error = false;
           $response->respuesta = $result->respuesta;
        }
        return $this->respond($response);
    }
 
    public function getProveedor()
    {  
        $session = \Config\Services::session();
        $globals      = new Mglobal;
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error al optener los datos';
        $id_proveedor =  $this->request->getPost('id_proveedor');
        $proveedor    = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1, 'id_proveedor' => $id_proveedor]]);
        $proveedor_banco    = $globals->getTabla(['tabla' => 'proveedor_banco', 'where' => ['visible' => 1, 'idproveedor' => $id_proveedor]]);
        if(!$proveedor->error){
          $response->error = false;
          $response->respuesta = $proveedor->respuesta;
          $response->data['proveedor'] = (isset($proveedor->data) && !empty($proveedor->data))?$proveedor->data[0]:[];
          $response->data['proveedor_banco'] = (isset($proveedor_banco->data) && !empty($proveedor_banco->data))?$proveedor_banco->data:[];
        }              
         return $this->respond($response);
    }
    public function listadoProveedores()
    {  
        $session = \Config\Services::session();
        $globals      = new Mglobal;
        $proveedor    = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit'=>50]);
        $data['proveedor']    = (!empty($proveedor->data))?$proveedor->data:[];
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vListadoProveedor';                
        $this->_renderView($data); 
    }
    public function incidenciaSubordinado()
    {
        $session = \Config\Services::session();
        $globals      = new Mglobal;
        $incidencia    = $globals->getTabla(['tabla' => 'vw_incidenica', 'where' => ['visible' => 1, 'id_jefe_inmediato' => $session->get('id_usuario')]]);

        $data['incidencia']    = (!empty($incidencia->data))?$incidencia->data:[];
        $data['scripts'] = array('inicio', 'principal');
        $data['contentView'] = 'secciones/vListaIncidencia';                
        $this->_renderView($data); 
    }
    public function listaReservaPT()
    {  
       
        $session = \Config\Services::session();
        $globals      = new Mglobal;
        if(in_array($session->get('id_perfil'), [1,2])){
            $reserva    = $globals->getTabla(['tabla' => 'vw_lista_reserva', 'where' => ['visible' => 1]]);
        }else{
            $reserva    = $globals->getTabla(['tabla' => 'vw_lista_reserva', 'where' => ['visible' => 1, 'usu_reg' => $session->get('id_usuario')]]);
        }
      
        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_partida  = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $proveedor    = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit'=>100]);
        $data['reserva']    = (!empty($reserva->data))?$reserva->data:[];
        $data['scripts'] = array('inicio');
        $data['cat_proyecto'] = (!empty($cat_proyecto->data))?$cat_proyecto->data:[];
        $data['cat_partida']  = (!empty($cat_partida->data))?$cat_partida->data:[];
        $data['contentView'] = 'secciones/vListadoReservaPT';                
        $this->_renderView($data);
        
    }
    public function listadoPT()
    {  
       
        $session = \Config\Services::session();
        $globals      = new Mglobal;
        $cat_perfil   = $globals->getTabla(['tabla' => 'perfil', 'where' => ['visible' => 1]]);
        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_partida  = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $proveedor    = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1], 'limit'=>100]);

        $data['cat_perfil']   = (!empty($cat_perfil->data))?$cat_perfil->data:[];
        $data['proveedor']    = (!empty($proveedor->data))?$proveedor->data:[];
        $data['cat_proyecto'] = (!empty($cat_proyecto->data))?$cat_proyecto->data:[];
        $data['cat_partida']  = (!empty($cat_partida->data))?$cat_partida->data:[];
     
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vListadoPT';                
        $this->_renderView($data);
        
    }
    public function deletePT()
    {  
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $id_registro_pt     = $this->request->getPost('id_registro_pt');
   
            $dataConfig = [
                "tabla"=>"registro_pt",
                "editar"=>true,
                "idEditar"=>['id_registro_pt'=>$id_registro_pt]
            ];
    
        
        $response = $globals->saveTabla(['visible' => 0],$dataConfig,["script"=>"opciones.DeletePT"]);
        return $this->respond($response);
    }
    public function listadoEstatusPT()
    {  
        $session = \Config\Services::session();
        $globals = new Mglobal;
        if(in_array($session->get('id_perfil'), [1,2])){
         $registro_pt = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1]]);
        }else{
            $registro_pt = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1, 'usu_reg' => $session->get('id_usuario')]]);
        }
      

        $data['registro_pt'] = (!empty($registro_pt->data))?$registro_pt->data:[];
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vregistroPT';                
        $this->_renderView($data);
    }
    public function tablaArchivos($id = null)
    {  
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data['id_registro_pt'] = $id;
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vTablaArchivos';                
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
            '', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve',
            'diez', 'once', 'doce', 'trece', 'catorce', 'quince',
            'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve', 'veinte'
        ];
        $decena = ['', '', 'veinti', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
        $centena = ['', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos',
            'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];

        if ($numero == 0) return 'cero';
        if ($numero == 100) return 'cien';
        if ($numero >= 1000000) {
            $millones = floor($numero / 1000000);
            $resto = $numero % 1000000;
            $txt = ($millones == 1 ? 'un millón' : $this->convertirNumero($millones) . ' millones');
            if ($resto > 0) $txt .= ' ' . $this->convertirNumero($resto);
            return $txt;
        }
        if ($numero >= 1000) {
            $miles = floor($numero / 1000);
            $resto = $numero % 1000;
            $txt = ($miles == 1 ? 'mil' :  $this->convertirNumero($miles) . ' mil');
            if ($resto > 0) $txt .= ' ' .  $this->convertirNumero($resto);
            return $txt;
        }
        if ($numero >= 100) {
            $c = floor($numero / 100);
            $r = $numero % 100;
            return $centena[$c] . ($r > 0 ? ' ' .  $this->convertirNumero($r) : '');
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
    public function Archivo($id_registro_pt =  null, $id_archivo = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $registro_pt = $globals->getTabla([
            'tabla' => 'vw_registro_pt',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]
        ]);
       
        if (!empty($registro_pt->data)) {
            $data['registro'] = $registro_pt->data[0];
            $folio = $globals->getTabla([
                'tabla' => 'direccion',
                'where' => ['visible' => 1, 'id_area' => $data['registro']->id_direccion_responsable]
            ]);
            $data['folio'] = $folio->data[0]->folio_prefijo;
        } else {
            echo '<h2>Error al encontrar registro, favor de revisar el id del registro PT</h2>';
            die();
        }
       switch($id_archivo){
            case 1:
                $doc = 'assets/pdf/plantillas/anexo02.pdf';
                $formato = 'personal/vFormato01.php';
                break;
            case 4:
                $doc = 'assets/pdf/plantillas/anexo04.pdf';
                 $formato ='personal/vFormato04.php';
                break;
        }
        $html = view( $formato, $data);
        // Crear instancia de mPDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

        // Importar el PDF base
      
        $pagecount = $mpdf->SetSourceFile(FCPATH . $doc );
        $mpdf->AddPage();
        $tplId = $mpdf->ImportPage(1);
        $mpdf->UseTemplate($tplId);
        $mpdf->WriteHTML($html);

        $mpdf->Output('Formato_pt.pdf', 'I');
        exit();
    }

    public function ImprimirPT($id_pt = null)
    {  
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = [];
        $id_reserva= null;

      $registro_pt = $globals->getTabla([
            'tabla' => 'vw_registro_pt',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_pt]
        ]);

        if (!empty($registro_pt->data)) {
            $registro = $registro_pt->data[0];
            $id_reserva = $registro_pt->data[0]->id_reserva;
            $data['registro'] = $registro;

            $folio = $globals->getTabla([
                'tabla' => 'direccion',
                'where' => ['visible' => 1, 'id_area' => $registro->id_direccion_responsable]
            ]);
            $reserva = $globals->getTabla([
                'tabla' => 'vw_reserva',
                'where' => ['visible' => 1, 'id_reserva' => $id_reserva]
            ]);
            if(!empty($reserva->data)){
             $data['reserva'] = $reserva->data;
             $importe_str = $reserva->data[0]->total_importe;
             $importe_float = (float) str_replace(',', '', $importe_str); // quita coma y convierte
             $data['numero_texto'] = $this->numeroEnLetras($importe_float);
            }
   
            if (!empty($folio->data)) {
            $zero = (strlen($folio->data[0]->ultimo_folio_pt) >= 2)?'0':'00';
                $data['registro']->folio = $folio->data[0]->folio_prefijo.$zero.$folio->data[0]->ultimo_folio_pt.'/'.$folio->data[0]->periodo_pt;
            } else {
                $data['registro']->folio = ''; // O un valor por defecto
            }

        } else {
            echo '<h2>Error al encontrar registro, favor de revisar el id del registro PT</h2>';
            die();
        }

       
        $html = view('secciones/vFormatoPT.php', $data);
        $htmlSegundaHoja = view('secciones/vFormatoPT2.php', $data);

        // Crear instancia de mPDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

        // Importar el PDF base
        $pagecount = $mpdf->SetSourceFile(FCPATH . 'assets/pdf/formatoPT.pdf');

      for ($i = 1; $i <= $pagecount; $i++) {
            $mpdf->AddPage();
            $tplId = $mpdf->ImportPage($i);
            $mpdf->UseTemplate($tplId);

            if ($i == 1) {
                // Solo escribe HTML en la primera página
                $mpdf->WriteHTML($html);
            }
            if ($i == 2) {
                // Solo escribe HTML en la primera página
                $mpdf->WriteHTML($htmlSegundaHoja);
            }
        }


        $mpdf->Output('Formato_pt.pdf', 'I');
        exit();
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

       if(isset($proveedor->data) && !empty($proveedor->data)){
            $response->error     = $proveedor->error;
            $response->respuesta = $proveedor->respuesta;
            $response->data      =  $proveedor->data;

        }
        return $this->respond($response);
       

    }
        public function Proveedor()
    {  
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedor';
        $globals = new Mglobal;
        $id_proveedor =  $this->request->getPost('id_proveedor');
      
        $data = [];
       if(!empty($id_proveedor)){
            $proveedor           = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1, 'id_proveedor' =>$id_proveedor ]]);
            $banco               = $globals->getTabla(['tabla' => 'proveedor_banco', 'where' => ['idproveedor' => $id_proveedor ]]);
            if(isset( $banco->data[0]) && !empty( $banco->data[0])){
                if($session->get('id_perfil') != 1){
                    if(empty($banco->data[0]->no_cuenta) || empty($banco->data[0]->clabe)){
                        $response->error = true;
                        $response->respuesta = 'El proveedor no tiene No. de cuenta y/o clabe, favor de solIcitar un Tiket a la área TI';
                        return $this->respond($response);
                    }
                }
                
            }
            $response->error     = $proveedor->error;
            $response->respuesta = $proveedor->respuesta;
            $response->data['proveedor'] = (isset($proveedor->data[0]) && !empty($proveedor->data[0]))?$proveedor->data[0]:[];
            $response->data['banco'] = (isset( $banco->data[0]) && !empty( $banco->data[0]))?$banco->data:[];
            
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
        $id_reserva =  $this->request->getPost('id_reserva');
        $data = [];
       if(!empty($id_reserva)){
            $reserva             = $globals->getTabla(['tabla' => 'vw_reserva', 'where' => ['visible' => 1, 'id_reserva' =>$id_reserva ]]);
            $cat_proyecto        = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1 ]]);
            $cat_partida        = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1 ]]);
            $response->error     = $reserva->error;
            $response->respuesta = $reserva->respuesta;
            $response->data['reserva'] = (isset($reserva->data[0]) && !empty($reserva->data[0]))?$reserva->data[0]:[];
            $response->data['presupuesto'] = (isset($reserva->data[0]) && !empty($reserva->data[0]))?$reserva->data:[];
            $response->data['proyecto'] = (isset($cat_proyecto->data[0]) && !empty($cat_proyecto->data[0]))?$cat_proyecto->data:[];
            $response->data['partida'] = (isset($cat_partida->data[0]) && !empty($cat_partida->data[0]))?$cat_partida->data:[];
        }
      
         return $this->respond($response);
        
    }

    public function generarTramitePago($id_reserva = null, $id_registro_pt =  null)
    {  
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedor';
        $globals = new Mglobal;
        $siExisteIdReserva  = $globals->getTabla(['tabla' => 'registro_pt', 'where' => ['visible' => 1 , 'id_reserva' => $id_reserva ]]);
        $btn = (!empty($siExisteIdReserva->data))?true:false;
        $cat_area  = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1 ]]);
        if($id_reserva != 0){
            $reserva   = $globals->getTabla(['tabla' => 'vw_reserva', 'where' => ['id_reserva' => $id_reserva ]]);
            $presupuesto   = $globals->getTabla(['tabla' => 'presupuesto', 'where' => ['id_reserva' => $id_reserva ]]);
        }
        if(!empty($id_registro_pt)){
            $registro_pt   = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1, 'id_registro_pt' =>$id_registro_pt ]]);
        }

        
        $secretario = $globals->getTabla(['tabla' => 'cat_secretario', 'where' => ['visible' => 1 ]]);
        $cat_tipo = $globals->getTabla(['tabla' => 'cat_tipo', 'where' => ['visible' => 1 ]]);
 
        $usuario                = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' =>$session->get('id_usuario') ]]);
        $cat_usuario            = $globals->getTabla(['tabla' => 'usuario', 'where' => ['visible' => 1 ]]);
        $cat_director_general   = $globals->getTabla(['tabla' => 'cat_director_general', 'where' => ['visible' => 1 ]]);
        $cat_opcion             = $globals->getTabla(['tabla' => 'cat_opcion', 'where' => ['visible' => 1 ]]);
        $cat_partida             = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1 ]]);
        if($id_reserva != 0){
          $data['reserva']      = (!empty($reserva->data))?$reserva->data[0]:[];
          $data['presupuesto']  = (!empty($presupuesto->data))?$presupuesto->data:[];
        }
        if(!empty($id_registro_pt)){
           $data['registro_pt']  = (!empty($registro_pt->data))?$registro_pt->data[0]:[];
        }
    
        $data['dsc_director_general'] = (!empty($cat_director_general->data))?$cat_director_general->data[0]->dsc_director_general:[];
        $data['cat_area']             = (!empty($cat_area->data))?$cat_area->data:[];
        $data['cat_tipo']             = (!empty($cat_tipo->data))?$cat_tipo->data:[];
        $data['cat_opcion']           = (!empty($cat_opcion->data))?$cat_opcion->data:[];
        $data['cat_partida']          = (!empty($cat_partida->data))?$cat_partida->data:[];
        $data['editar']               = (!empty($id_reserva) || $id_reserva != 0)?0:1;
        $data['secretario']           = (!empty($secretario->data))?$secretario->data:[];
        $data['usuario']              = (!empty($usuario->data))?$usuario->data[0]:[];
        $data['cat_usuario']          = (!empty($cat_usuario->data))?$cat_usuario->data:[];
        $data['id_reserva']          = (!empty($id_reserva))?$id_reserva:0;
        $data['scripts']              = array('inicio');
        $data['edita']                = $btn;
        $data['contentView']          = 'secciones/vProveedor';                
        $this->_renderView($data);
        
    }  
/*     public function generarTramitePago($id_proveedor = null, $id_registro_pt =  null)
    {  
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedor';
        $globals = new Mglobal;
        $cat_area  = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1 ]]);
        if($id_proveedor != 0){
            $proveedor   = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1, 'id_proveedor' =>$id_proveedor ]]);
            $banco       = $globals->getTabla(['tabla' => 'vw_reserva', 'where' => ['id_proveedor' => $id_proveedor ]]);
        }
        if(!empty($id_registro_pt)){
            $registro_pt   = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1, 'id_registro_pt' =>$id_registro_pt ]]);
        }
        
        $secretario = $globals->getTabla(['tabla' => 'cat_secretario', 'where' => ['visible' => 1 ]]);
        $cat_tipo = $globals->getTabla(['tabla' => 'cat_tipo', 'where' => ['visible' => 1 ]]);
 
        $usuario              = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' =>$session->get('id_usuario') ]]);
        $cat_usuario          = $globals->getTabla(['tabla' => 'usuario', 'where' => ['visible' => 1 ]]);
        $cat_director_general = $globals->getTabla(['tabla' => 'cat_director_general', 'where' => ['visible' => 1 ]]);
        $cat_opcion           = $globals->getTabla(['tabla' => 'cat_opcion', 'where' => ['visible' => 1 ]]);
        if($id_proveedor != 0){
          $data['proveedor']   = (!empty($proveedor->data))?$proveedor->data[0]:[];
          $data['banco']       = (!empty($banco->data))?$banco->data[0]:[];
         
        }
        if(!empty($id_registro_pt)){
           $data['registro_pt']   = (!empty($registro_pt->data))?$registro_pt->data[0]:[];
        }
        $data['dsc_director_general'] = (!empty($cat_director_general->data))?$cat_director_general->data[0]->dsc_director_general:[];
        $data['cat_area']    = (!empty($cat_area->data))?$cat_area->data:[];
        $data['cat_tipo']    = (!empty($cat_tipo->data))?$cat_tipo->data:[];
        $data['cat_opcion']  = (!empty($cat_opcion->data))?$cat_opcion->data:[];
        $data['editar']      = (!empty($id_proveedor) || $id_proveedor != 0)?0:1;
        $data['secretario']  = (!empty($secretario->data))?$secretario->data:[];
        $data['usuario']     = (!empty($usuario->data))?$usuario->data[0]:[];
        $data['cat_usuario']     = (!empty($cat_usuario->data))?$cat_usuario->data:[];
        $data['scripts']     = array('inicio');
        $data['edita']       = 0;
        $data['contentView'] = 'secciones/vProveedor';                
        $this->_renderView($data);
        
    }  
 */
    public function getProveedores()
    {  
       
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedores';
        $globals = new Mglobal;
        $proveedor = $globals->getTabla(['tabla' => 'proveedor', 'where' => ['visible' => 1]]);
        if(isset($proveedor->data) && !empty($proveedor->data)){
            $response->error     = $proveedor->error;
            $response->respuesta = $proveedor->respuesta;
            $response->data      =  $proveedor->data;

        }
        return $this->respond($response);
    }
    public function reporteIncidencia()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $registro_pt = $globals->getTabla([
            'tabla' => 'vw_registro_pt',
            'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]
        ]);
       
        if (!empty($registro_pt->data)) {
            $data['registro'] = $registro_pt->data[0];
            $folio = $globals->getTabla([
                'tabla' => 'direccion',
                'where' => ['visible' => 1, 'id_area' => $data['registro']->id_direccion_responsable]
            ]);
            $data['folio'] = $folio->data[0]->folio_prefijo;
        } else {
            echo '<h2>Error al encontrar registro, favor de revisar el id del registro PT</h2>';
            die();
        }
       switch($id_archivo){
            case 1:
                $doc = 'assets/pdf/plantillas/anexo02.pdf';
                $formato = 'personal/vFormato01.php';
                break;
            case 4:
                $doc = 'assets/pdf/plantillas/anexo04.pdf';
                 $formato ='personal/vFormato04.php';
                break;
        }
        $html = view( $formato, $data);
        // Crear instancia de mPDF
        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 0,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => [213, 268],
            'mirrorMargins' => false,
        ]);

        // Importar el PDF base
      
        $pagecount = $mpdf->SetSourceFile(FCPATH . $doc );
        $mpdf->AddPage();
        $tplId = $mpdf->ImportPage(1);
        $mpdf->UseTemplate($tplId);
        $mpdf->WriteHTML($html);

        $mpdf->Output('Formato_pt.pdf', 'I');
        exit();
    }
  
}