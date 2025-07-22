
<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use League\OAuth2\Client\Provider\Google;

class Auth extends Controller
{
    protected $googleProvider;

    public function __construct()
    {
        $this->googleProvider = new Google([
            'clientId'     => env('GOOGLE_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_CLIENT_SECRET'),
            'redirectUri'  => 'http://localhost/secturi/index.php/Auth/callback',
        ]);
    }

    public function login()
    {
        $authUrl = $this->googleProvider->getAuthorizationUrl();
        session()->set('oauth2state', $this->googleProvider->getState());
        return redirect()->to($authUrl);
    }

    public function callback()
    {
        $session = session();

        // Verifica el estado
        if ($this->request->getGet('state') !== $session->get('oauth2state')) {
            $session->remove('oauth2state');
            exit('Estado inválido');
        }

        // Obtener el token
        $token = $this->googleProvider->getAccessToken('authorization_code', [
            'code' => $this->request->getGet('code')
        ]);

        // Obtener datos del usuario
        try {
            $ownerDetails = $this->googleProvider->getResourceOwner($token);

            $userData = [
                'name'  => $ownerDetails->getName(),
                'email' => $ownerDetails->getEmail(),
                'avatar'=> $ownerDetails->getAvatar(),
            ];

            // Aquí puedes guardar/validar el usuario en tu base de datos
            $session->set('user', $userData);
            var_dump($userData);
            die();
            //return redirect()->to('/dashboard'); // o la ruta que quieras
        } catch (Exception $e) {
            exit('Error al obtener el usuario: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}