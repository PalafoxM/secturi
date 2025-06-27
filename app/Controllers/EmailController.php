<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Services;
use App\Models\Mglobal;

class EmailController extends BaseController
{
    public function sendEmail()
    {
        // Inicializar servicios y objetos
        $email = Services::email();
        $session = Services::session();
        $response = (object)[
            'error' => true,
            'respuesta' => "Error|Error al enviar correo"
        ];
        
        // Validar sesión y datos POST
        if(!$session->has('id_usuario')) {
            $response->respuesta = "No hay sesión activa";
            return $this->response->setJSON($response);
        }
        
        $data = $this->request->getPost();
        if(empty($data['randomTicket']) || empty($data['opcion'])) {
            $response->respuesta = "Datos incompletos";
            return $this->response->setJSON($response);
        }

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
        if(empty($usuario->data[0]->correo)) {
            $response->respuesta = "El usuario no contiene correo";
            return $this->response->setJSON($response);
        }
        
        $correo = $usuario->data[0]->correo;

        // Configurar y enviar correo
        $email->setFrom('palafox.marin31@gmail.com', 'SUSI');
        $email->setTo($correo);
        $email->setSubject('Soporte TI SECTURI');
       $email->setMessage('
                    <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                        <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                            <div style="background-color: #004080; padding: 20px; text-align: center;">
                                <img src="' . base_url('assets/images/logo-sm.png') . '" alt="Logo" style="height: 60px;">
                            </div>
                            <div style="padding: 30px; color: #333;">
                                <h1 style="color: #004080;">¡Ticket generado con éxito!</h1>
                                <p style="font-size: 16px;">Su ticket <strong>' . $data['randomTicket'] . '</strong> ha sido registrado correctamente.</p>
                                <p style="font-size: 15px;">En breve será atendido por nuestro personal.</p>
                                <p style="font-size: 15px;"><strong>Motivo:</strong> ' . htmlspecialchars($data['opcion']) . '</p>
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
}