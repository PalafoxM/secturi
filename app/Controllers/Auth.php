<?php

namespace App\Controllers;

use CodeIgniter\Controller;
//use CodeIgniter\API\ResponseTrait;
use League\OAuth2\Client\Provider\Google;
use App\Models\Mglobal;
use stdClass;


class Auth extends Controller
{
    protected $googleProvider;
   // use ResponseTrait; 
    

    public function __construct()
    {
        $this->googleProvider = new Google([
            'clientId'     => env('GOOGLE_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_CLIENT_SECRET'),
            'redirectUri'  => base_url('index.php/Auth/callback'),
        ]);
    }

    public function login()
    {
        $authUrl = $this->googleProvider->getAuthorizationUrl();
        session()->set('oauth2state', $this->googleProvider->getState());
        return redirect()->to($authUrl);
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

    public function callback()
    {
          $session = session();

        // ✅ CORRECCIÓN: Verifica que el estado COINCIDA
        $state = $this->request->getGet('state');
        $storedState = $session->get('oauth2state');

        if (!$state || $state !== $storedState) {
            log_message('error', 'Google Auth Error: State mismatch. Received state: ' . ($state ?: 'null') . ', Stored state: ' . ($storedState ?: 'null'));
            $session->remove('oauth2state');
            return redirect()->to('/Login')
                            ->with('error', 'Estado de seguridad inválido. Inténtalo de nuevo.');
        }

        try {
            // Obtener token
            $token = $this->googleProvider->getAccessToken('authorization_code', [
                'code' => $this->request->getGet('code')
            ]);

            // Obtener datos del usuario de Google
            $ownerDetails = $this->googleProvider->getResourceOwner($token);
            $email = $ownerDetails->getEmail();

            if (!$email) {
                log_message('error', 'Google Auth Error: No email obtained from token.');
                return redirect()->to('/login')
                                ->with('error', 'No se pudo obtener tu correo de Google. ¿Permitiste el acceso?');
            }

            $catalogos = new Mglobal();

            // Buscar usuario por correo
            $dataDB = [
                'tabla' => 'usuario',
                'where' => [
                    "correo" => $email,
                    "visible" => 1
                ]
            ];

            $result = $catalogos->getTabla($dataDB);

            if (isset($result->data) && !empty($result->data)) {
                $user = $result->data[0];

                // Iniciar sesión (igual que en validar_usuario)
                $session->set('logueado', 1);
                $session->set('id_usuario', $user->id_usuario);
                $session->set('id_sexo', $user->id_sexo);
                $session->set('usuario', $user->usuario ?? $email);
                $session->set('nombre_completo', trim($user->nombre . ' ' . $user->primer_apellido . ' ' . $user->segundo_apellido));
                $session->set('id_perfil', $user->id_perfil);
                $session->set('fec_nac', $user->fec_nac);
                $session->set('correo', $user->correo);
                $session->set('foto', $user->ruta_foto_relativa);
                $session->set('id_tipo_empleado', $user->id_tipo_empleado);
                $session->set('no_empleado', $user->no_empleado);

                // Activar actividad
                $this->activarActividad($user->id_usuario);

                // Verificar si es jefe
                $subordinados = $catalogos->getTabla([
                    'tabla' => 'vw_usuario',
                    'where' => ['visible' => 1, 'id_jefe_inmediato' => $user->id_usuario]
                ])->data;
                $esJefe = !empty($subordinados);
                $session->set('esJefe', $esJefe);

                // Redirigir al inicio
                return redirect()->to('/Inicio');

            } else {
                // Usuario no registrado → mensaje claro
                log_message('error', 'Google Auth Error: Email not registered -> ' . $email);
                return redirect()->to('/login')
                                ->with('error', "Tu cuenta de Google (<strong>" . esc($email) . "</strong>) no está registrada en el sistema.");
            }

        } catch (\Exception $e) {
            log_message('error', 'OAuth Google error: ' . $e->getMessage());
            
            return redirect()->to('/login')
                           ->with('error', 'Error al iniciar sesión con Google. Por favor, de contactar al administrador TI.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}