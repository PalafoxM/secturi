<?php namespace App\Controllers;
use App\Models\Mglobal;
//use App\Libraries\Validasesion;
//use App\Libraries\Globals;
use CodeIgniter\API\ResponseTrait;

class Login extends BaseController {

    use ResponseTrait;
    private $defaultData = array(
        'title' => 'Sitema de Turnos 2.0',
        'layout' => 'plantilla/lytDefault',
        'contentView' => 'vUndefined',
        'stylecss' => '',
    );
    public function __construct()
    {
        //fechas php en espanol
        setlocale(LC_TIME, 'es_ES.utf8', 'es_MX.UTF-8', 'es_MX', 'esp_esp', 'Spanish'); // usar solo LC_TIME para evitar que los decimales los separe con coma en lugar de punto y fallen los inserts de peso y talla
        date_default_timezone_set('America/Mexico_City');  
        $session = \Config\Services::session();        
    }

    private function _renderView($data = array()) {   
        /*if(isset($data['scripts'])){
            array_push($data['scripts'], "notificaciones");
        }*/    
        $data = array_merge($this->defaultData, $data);
        echo view($data['layout'], $data);               
    }
    private function asistencia_qr($id)
    {
        
        $session = \Config\Services::session();
        $response = new \stdClass();
        // $response->error = true;
        $this->globals = new Mglobal();
        $id_usuario  = $this->globals->getTabla(['tabla' => 'usuario', 'where' => ['no_empleado' => $id , 'visible' =>1]])->data[0]->id_usuario; 
       

       $res = $this->registrarAsistencia($id_usuario);
       $data['scripts'] = array('principal');
        $data['layout'] = 'plantilla/lytVacio';
        $data['contentView'] = 'personal/vRegistroQr';                
        $this->_renderView($data); 
        die();
    }
    public function index()
    {        
        $session = \Config\Services::session();
        $data = array();
        $id= $this->request->getGet('no_empleado');
        if(isset($id) && !empty($id)){
          $this->asistencia_qr($id);
        }
        if ($session->get('logueado')==1) {
            header('Location:' . base_url() . 'index.php/Inicio');
            die();
        }
        $data['layout'] = 'plantilla/lytLogin';
        $data['contentView'] = 'secciones/vLogin';                
        $this->_renderView($data);        
    }
    public function guardarUbicacion()
    {
        $data = $this->request->getJSON(true);
       
        if (!empty($data['id_user']) && isset($data['latitud'], $data['longitud'])) {
            // Aquí podrías guardar en la base de datos o loguear
            log_message('info', 'Ubicación recibida de usuario ' . $data['id_user'] . ': ' . json_encode($data));

            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'msg' => 'Datos incompletos']);
    }
    private function registrarAsistencia($id_usuario = null)
    {
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error al validar usuario";
        $response->asistencia = true;
     
        $session = \Config\Services::session();
        $globals = new Mglobal;
        
        if ($id_usuario) {
            $hora = date("H:i:s"); 
            $fecha_hoy = date("Y-m-d"); // Solo la fecha
            
            $check = $globals->getTabla([
                'tabla' => 'asistencia',
                'where' => ['id_usuario' => $id_usuario, 'visible' => 1],
                'like' => ['fecha' => $fecha_hoy]
            ]);
            
            if (empty($check->data)) {
                $dataConfig = [
                    "tabla" => 'asistencia',
                    "editar" => false
                ];
                $dataInsert = [
                    "id_usuario" => $id_usuario,
                    "fecha" => $fecha_hoy,
                    "turno" => 'DIA(08:30-16:00)',
                    "entrada" => $hora
                ];
                $result = $globals->saveTabla($dataInsert, $dataConfig, ["script" => "asistencia.agregarAsiatencia"]);
                $response->error = $result->error;
                $response->respuesta = $result->respuesta;
                $response->asistencia = !$result->error;
            } else {
                $response->respuesta = "Ya registraste tu asistencia hoy";
                 $response->error = false;
            }
        }
        
        return $response;
    }
    private function registrarAsistencia2($id_usuario = null, $Latitud = null, $Longitud = null)
    {
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error al validar usuario";
        $response->asistencia = true;
        
        // Coordenadas del centro de la geocerca

        $centroLat = 20.956950;
        $centroLng = -101.360316;
        $radio = 1000; // metros
        
        // Validar que se recibieron coordenadas
        if (!$Latitud || !$Longitud) {
            $response->ubicacion = "No se recibieron coordenadas de ubicación";
             $response->asistencia = false;
            return $response;
        }
        
        // Calcular distancia (fórmula Haversine)
        $earthRadius = 6371000; // metros
        $latFrom = deg2rad($centroLat);
        $lonFrom = deg2rad($centroLng);
        $latTo = deg2rad($Latitud);
        $lonTo = deg2rad($Longitud);
        
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        $distancia = $angle * $earthRadius;
        

        if ($distancia > $radio) {
            $response->asistencia = false;
            $response->ubicacion = "Ubicación fuera del área permitida";
            return $response;
        }
       
       
        
        $session = \Config\Services::session();
        $globals = new Mglobal;
        
        if ($id_usuario) {
            $hora = date("H:i:s"); 
            $fecha_hoy = date("Y-m-d"); // Solo la fecha
            
            $check = $globals->getTabla([
                'tabla' => 'asistencia',
                'where' => ['id_usuario' => $id_usuario, 'visible' => 1],
                'like' => ['fecha' => $fecha_hoy]
            ]);
            
            if (empty($check->data)) {
                $dataConfig = [
                    "tabla" => 'asistencia',
                    "editar" => false
                ];
                $dataInsert = [
                    "id_usuario" => $id_usuario,
                    "fecha" => $fecha_hoy,
                    "turno" => 'DIA(08:30-16:00)',
                    "entrada" => $hora,
                    "latitud" => $Latitud,
                    "longitud" => $Longitud
                ];
                $result = $globals->saveTabla($dataInsert, $dataConfig, ["script" => "asistencia.agregarAsiatencia"]);
                $response->error = $result->error;
                $response->respuesta = $result->respuesta;
                $response->asistencia = !$result->error;
            } else {
                $response->respuesta = "Ya registraste tu asistencia hoy";
                 $response->error = false;
            }
        }
        
        return $response;
    }
    public function registrarSalida()
    {
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error al registrar salida";
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $id_asistencia = null;
        $hoy = $globals->getTabla(["tabla"=>"asistencia", 'where' => ['visible' => 1, 'id_usuario' => $session->get('id_usuario'), 'fecha' => date('Y-m-d') ]]);
        if(isset($hoy->data) && !empty($hoy->data)){
         $id_asistencia = $hoy->data[0]->id_asistencia;
           $dataConfig = [
                    "tabla" => 'asistencia',
                    "editar" => true,
                    "idEditar" => ['id_usuario' => $session->get('id_usuario'), 'id_asistencia' => $id_asistencia ]
                ];
        }else{
            $dataConfig = [
                    "tabla" => 'asistencia',
                    "editar" => true,
                    "idEditar" => ['id_usuario' => $session->get('id_usuario'),  'fecha' => date('Y-m-d') ]
                ];

        }
      
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Login.php/guardaSalida'];  
        $result = $globals->saveTabla(['salida' => date('H:i:s')], $dataConfig,  $dataBitacora );
        if(!$result->error){
          $response->error     =  $result->error;
          $response->respuesta =  $result->respuesta;
          $session->set('registro_salida', 1);
        }
        return $this->respond($response);
    }
    public function activarActividad($id_usuario)
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $session->set('qr', false);
        $response->respuesta = "Error al registrar salida";
        $globals = new Mglobal;
        $dataConfig = [
                "tabla" => 'bitacora_susi',
                "editar" => false,
                ];
      
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Login.php/guardaEntrada'];
        $dataInsert = [
                'id_usuario' =>$id_usuario,
                'fec_act' => date('Y-m-d H:i:s'),
                'hora'    =>   date('H:i:s'),
                'activo' => 1
        ];
        $result = $globals->saveTabla($dataInsert, $dataConfig,  $dataBitacora );
       
        $qr = $globals->getTabla(['tabla' => 'descarga_qr', 'where' => ['visible' => 1], 'id_usuario' => $id_usuario]);
        if(isset($qr->data) && !empty($qr->data)){
               $session->set('qr', true);
        }
    
   
    }
    public function cerrar() {
        $session = \Config\Services::session();  
        $session->destroy();
        $session->set('logueado', 0);        
        header('Location:'.base_url());
        die();
    }
    
    /**
     * Obtiene el nombre del navegador que esta usando el usuario
     * @param type $user_agent La variable del servidor $_SERVER['HTTP_USER_AGENT']
     * @return string El nombre del navegador
     */
    function get_browser_name($user_agent) {
        if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/'))
            return 'Opera';
        elseif (strpos($user_agent, 'Edge'))
            return 'Edge';
        elseif (strpos($user_agent, 'Chrome'))
            return 'Chrome';
        elseif (strpos($user_agent, 'Safari'))
            return 'Safari';
        elseif (strpos($user_agent, 'Firefox'))
            return 'Firefox';
        elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7'))
            return 'Internet Explorer';

        return $user_agent;
    }
    
    function ServerVar($Name) {
        $str = @$_SERVER[$Name];
        if (empty($str)) $str = @$_ENV[$Name];
        return $str;
    }
    
    function miDebug($msg) {
        $filename = ".debug.txt";
        if (!$handle = fopen($filename, 'a'))
                exit;
        if (is_writable($filename)) {
                $separador = "================================================================================";
                fwrite($handle, "" . $msg . "\n" . $separador . "\n\n");
        }
        fclose($handle);
    }
    
}
