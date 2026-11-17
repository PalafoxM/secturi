<?php namespace App\Controllers;
use App\Models\Mglobal;
//use App\Libraries\Validasesion;
//use App\Libraries\Globals;
use CodeIgniter\API\ResponseTrait;

class Login extends BaseController {

    use ResponseTrait;
    private const CAPTCHA_TTL = 300;
    private const LOGIN_RATE_WINDOW = 300;
    private const LOGIN_RATE_BLOCK = 300;
    private const LOGIN_MAX_USER_ATTEMPTS = 5;
    private const LOGIN_MAX_IP_ATTEMPTS = 10;

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

    private function generarCaptchaLogin(): string
    {
        if (!function_exists('imagecreatetruecolor')) {
            throw new \RuntimeException('La extensión GD es necesaria para generar el CAPTCHA.');
        }

        $caracteres = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $codigo = '';
        $longitud = strlen($caracteres);

        for ($i = 0; $i < 5; $i++) {
            $codigo .= $caracteres[random_int(0, $longitud - 1)];
        }

        $ancho = 210;
        $alto = 64;
        $imagen = imagecreatetruecolor($ancho, $alto);
        $fondo = imagecolorallocate($imagen, 238, 244, 251);
        $textoColor = imagecolorallocate($imagen, 23, 54, 95);
        imagefilledrectangle($imagen, 0, 0, $ancho, $alto, $fondo);

        for ($i = 0; $i < 9; $i++) {
            $ruido = imagecolorallocate(
                $imagen,
                random_int(105, 170),
                random_int(135, 185),
                random_int(170, 215)
            );
            imageline(
                $imagen,
                random_int(0, $ancho),
                random_int(0, $alto),
                random_int(0, $ancho),
                random_int(0, $alto),
                $ruido
            );
        }

        foreach (str_split($codigo) as $indice => $caracter) {
            $glifo = imagecreatetruecolor(18, 22);
            imagealphablending($glifo, false);
            imagesavealpha($glifo, true);
            $transparente = imagecolorallocatealpha($glifo, 0, 0, 0, 127);
            imagefilledrectangle($glifo, 0, 0, 18, 22, $transparente);
            $colorGlifo = imagecolorallocate($glifo, 23, 54, 95);
            imagestring($glifo, 5, 4, 3, $caracter, $colorGlifo);

            $glifoEscalado = imagescale($glifo, 31, 43, IMG_BICUBIC_FIXED);
            $glifoRotado = imagerotate($glifoEscalado, random_int(-15, 15), $transparente);
            imagealphablending($imagen, true);
            imagecopy(
                $imagen,
                $glifoRotado,
                20 + ($indice * 38),
                random_int(8, 15),
                0,
                0,
                imagesx($glifoRotado),
                imagesy($glifoRotado)
            );

            imagedestroy($glifo);
            imagedestroy($glifoEscalado);
            imagedestroy($glifoRotado);
        }

        for ($i = 0; $i < 55; $i++) {
            imagesetpixel($imagen, random_int(0, $ancho - 1), random_int(0, $alto - 1), $textoColor);
        }

        ob_start();
        imagepng($imagen, null, 6);
        $png = (string) ob_get_clean();
        imagedestroy($imagen);

        $session = \Config\Services::session();
        $session->set([
            'login_captcha_hash' => hash('sha256', $codigo),
            'login_captcha_expires' => time() + self::CAPTCHA_TTL,
            'login_captcha_image' => base64_encode($png),
        ]);

        return $png;
    }

    public function captcha()
    {
        $session = \Config\Services::session();
        $renovar = filter_var($this->request->getGet('refresh'), FILTER_VALIDATE_BOOLEAN);
        $imagenCodificada = (string) $session->get('login_captcha_image');
        $imagen = $imagenCodificada !== '' ? base64_decode($imagenCodificada, true) : false;
        $expira = (int) $session->get('login_captcha_expires');

        if ($renovar || $imagen === false || $expira <= time()) {
            $imagen = $this->generarCaptchaLogin();
        }

        return $this->response
            ->setHeader('Content-Type', 'image/png')
            ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->setHeader('Pragma', 'no-cache')
            ->setBody($imagen);
    }

    private function validarCaptchaLogin(string $respuesta): bool
    {
        $session = \Config\Services::session();
        $hashEsperado = (string) $session->get('login_captcha_hash');
        $expira = (int) $session->get('login_captcha_expires');

        // Cada reto sólo puede utilizarse una vez, incluso si la respuesta es incorrecta.
        $session->remove(['login_captcha_hash', 'login_captcha_expires', 'login_captcha_image']);

        $respuesta = strtoupper(trim($respuesta));
        return $hashEsperado !== ''
            && $expira > time()
            && preg_match('/^[A-Z0-9]{5}$/', $respuesta) === 1
            && hash_equals($hashEsperado, hash('sha256', $respuesta));
    }

    private function clavesLimiteLogin(string $usuario): array
    {
        $ip = (string) $this->request->getIPAddress();
        $usuarioNormalizado = strtolower(trim($usuario));

        return [
            ['tipo' => 'ip', 'base' => 'login_ip_' . hash('sha256', $ip), 'limite' => self::LOGIN_MAX_IP_ATTEMPTS],
            ['tipo' => 'usuario', 'base' => 'login_user_' . hash('sha256', $usuarioNormalizado), 'limite' => self::LOGIN_MAX_USER_ATTEMPTS],
        ];
    }

    private function segundosBloqueoLogin(string $usuario): int
    {
        $cache = \Config\Services::cache();
        $bloqueadoHasta = 0;

        foreach ($this->clavesLimiteLogin($usuario) as $configuracion) {
            $bloqueadoHasta = max(
                $bloqueadoHasta,
                (int) ($cache->get($configuracion['base'] . '_blocked') ?: 0)
            );
        }

        return max(0, $bloqueadoHasta - time());
    }

    private function registrarIntentoFallidoLogin(string $usuario, bool $incluirUsuario = true): int
    {
        $cache = \Config\Services::cache();
        $bloqueo = 0;

        foreach ($this->clavesLimiteLogin($usuario) as $configuracion) {
            if (!$incluirUsuario && $configuracion['tipo'] === 'usuario') {
                continue;
            }

            $contadorKey = $configuracion['base'] . '_count';
            $bloqueoKey = $configuracion['base'] . '_blocked';
            $contadorActual = $cache->get($contadorKey);

            if ($contadorActual === null) {
                $intentos = 1;
                $cache->save($contadorKey, $intentos, self::LOGIN_RATE_WINDOW);
            } else {
                $incrementado = $cache->increment($contadorKey, 1);
                $intentos = $incrementado === false
                    ? ((int) $contadorActual + 1)
                    : (int) $incrementado;
                if ($incrementado === false) {
                    $cache->save($contadorKey, $intentos, self::LOGIN_RATE_WINDOW);
                }
            }

            if ($intentos >= (int) $configuracion['limite']) {
                $bloqueadoHasta = time() + self::LOGIN_RATE_BLOCK;
                $cache->save($bloqueoKey, $bloqueadoHasta, self::LOGIN_RATE_BLOCK);
                $cache->delete($contadorKey);
                $bloqueo = max($bloqueo, self::LOGIN_RATE_BLOCK);
            }
        }

        return $bloqueo;
    }

    private function limpiarIntentosLogin(string $usuario): void
    {
        $cache = \Config\Services::cache();
        foreach ($this->clavesLimiteLogin($usuario) as $configuracion) {
            $cache->delete($configuracion['base'] . '_count');
            $cache->delete($configuracion['base'] . '_blocked');
        }
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

    public function validar_usuario()
    {
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Usuario o contraseña incorrectos.';

        if (strtolower($this->request->getMethod()) !== 'post') {
            return $this->respond($response);
        }

        $usuario = trim((string) $this->request->getPost('usuario'));
        $contrasenia = (string) $this->request->getPost('contrasenia');
        $captcha = (string) $this->request->getPost('captcha');

        if ($usuario === '' || $contrasenia === '' || trim($captcha) === '') {
            $response->respuesta = 'Es necesario ingresar usuario, contraseña y CAPTCHA.';
            $response->renovar_captcha = true;
            return $this->respond($response);
        }

        $session = \Config\Services::session();
        $segundosBloqueo = $this->segundosBloqueoLogin($usuario);
        if ($segundosBloqueo > 0) {
            $response->respuesta = 'Demasiados intentos. Intenta nuevamente en ' . $segundosBloqueo . ' segundos.';
            $response->renovar_captcha = true;
            $response->espera_segundos = $segundosBloqueo;
            return $this->respond($response, 429);
        }

        if (!$this->validarCaptchaLogin($captcha)) {
            $bloqueo = $this->registrarIntentoFallidoLogin($usuario, false);
            $response->respuesta = $bloqueo > 0
                ? 'Demasiados intentos. Intenta nuevamente en ' . $bloqueo . ' segundos.'
                : 'El código CAPTCHA es incorrecto o expiró.';
            $response->renovar_captcha = true;
            $response->espera_segundos = $bloqueo;
            return $this->respond($response, $bloqueo > 0 ? 429 : 400);
        }

        $catalogos = new Mglobal;
        $result = $catalogos->getTabla([
            'tabla' => 'usuario',
            'where' => ['usuario' => $usuario, 'visible' => 1],
            'limit' => 1,
        ]);

        $user = (!empty($result->data)) ? $result->data[0] : null;

        if (!$user || !$this->validarContrasenia($contrasenia, $user->contrasenia ?? null)) {
            $bloqueo = $this->registrarIntentoFallidoLogin($usuario);
            $response->respuesta = $bloqueo > 0
                ? 'Demasiados intentos. Intenta nuevamente en ' . $bloqueo . ' segundos.'
                : 'Usuario, contraseña o CAPTCHA incorrectos.';
            $response->renovar_captcha = true;
            $response->espera_segundos = $bloqueo;
            return $this->respond($response, $bloqueo > 0 ? 429 : 401);
        }

        if ($this->requiereRehashContrasenia($user->contrasenia ?? null)) {
            $catalogos->saveTabla(
                ['contrasenia' => $this->hashContrasenia($contrasenia)],
                [
                    'tabla' => 'usuario',
                    'editar' => true,
                    'idEditar' => ['id_usuario' => $user->id_usuario],
                ],
                ['id_user' => $user->id_usuario, 'script' => 'Login.php/validar_usuario']
            );
        }

        $session->regenerate(true);
        $this->limpiarIntentosLogin($usuario);
        $session->remove([
            'intentos_login_tradicional',
            'bloqueo_login_tradicional',
            'login_captcha_hash',
            'login_captcha_expires',
            'login_captcha_image',
        ]);
        $session->set('logueado', 1);
        $session->set('id_usuario', $user->id_usuario);
        $session->set('id_sexo', $user->id_sexo ?? null);
        $session->set('usuario', $user->usuario);
        $session->set('nombre_completo', trim(
            ($user->nombre ?? '') . ' ' .
            ($user->primer_apellido ?? '') . ' ' .
            ($user->segundo_apellido ?? '')
        ));
        $session->set('id_perfil', $user->id_perfil ?? null);
        $session->set('fec_nac', $user->fec_nac ?? null);
        $session->set('correo', $user->correo ?? null);
        $session->set('foto', $user->ruta_foto_relativa ?? null);
        $session->set('id_tipo_empleado', $user->id_tipo_empleado ?? null);
        $session->set('no_empleado', $user->no_empleado ?? null);

        $this->activarActividad($user->id_usuario);

        $subordinados = $catalogos->getTabla([
            'tabla' => 'vw_usuario',
            'where' => ['visible' => 1, 'id_jefe_inmediato' => $user->id_usuario],
        ]);
        $session->set('esJefe', !empty($subordinados->data));

        $response->error = false;
        $response->respuesta = 'Acceso correcto.';

        return $this->respond($response);
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
