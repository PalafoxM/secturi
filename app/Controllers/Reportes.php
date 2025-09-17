<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Curps;
use App\Libraries\Fechas;
use App\Libraries\Funciones;
use App\Models\Mglobal;

use stdClass;
use CodeIgniter\API\ResponseTrait;
require_once FCPATH . '/mpdf/autoload.php';
require_once FCPATH . 'spout/src/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class Reportes extends BaseController {
    private $funciones;

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

        $this->funciones = new Funciones();
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
      
        $data['contentView'] = 'secciones/vReportes';                
        $this->_renderView($data);
        
    }

    public function getPrincipal()
    {
        $session = \Config\Services::session();
       
        $data = $this->request->getBody();
        $data = json_decode($data);
  
        $response = new \stdClass();
        $dataConfig = [
            'dataBase' => 'turnos2',
            'tabla' => 'turno',
            'where' => 'visible = 1',
            'order' => 'id_turno DESC',
        ];
        
        $dataConfig['limit'] = ['start' => $data->offset, 'length' => $data->limit];
        
        $where = "visible = 1 ";

        if($data->estatus && $data->estatus != 3){
            $where .= " AND id_estatus IN ('".$data->estatus."')  ";
        }

        if($data->fecha_inicio && $data->fecha_final){

            $where .= " AND (fecha_recepcion BETWEEN '" .$this->funciones->dateEuroToISO($data->fecha_inicio,"-") . "' AND '" . $this->funciones->dateEuroToISO($data->fecha_final,"-") . "')";
        }
        
        if($data->resultado_turno && $data->resultado_turno != 3){
            $where .= " AND id_resultado_turno = '".$data->resultado_turno."' ";
        }

        if ($data->search != "") {
            $where .= " AND ( id_turno = {$data->search} ";
            $where .= " OR fecha_recepcion = {$data->search} ";
            $where .= " OR solicitante_nombre = {$data->search} ";
            $where .= " OR resumen = {$data->search} ";
            $where .= " OR solicitante_razon_social = {$data->search} )"; 
        }

        $dataConfig['where'] = $where;
        $request = $this->globals->getTabla($dataConfig);

        if (isset($dataConfig['limit'])) {
            unset($dataConfig['limit']);
        }

        $dataConfig['select'] = 'count(*) AS total_registros';
        $requestTotal = $this->globals->getTabla($dataConfig);

        $response->rows = $request->data;
        $response->total = $requestTotal->data[0]->total_registros;
        $response->totalNotFiltered = $requestTotal->data[0]->total_registros;

        return $this->respond($response);

        }
 /* public function getPrincipalExcel()
    {
        $session = \Config\Services::session();
        // $data = $this->request->getBody();
        $data = $this->request->getPost();
        // $data = json_decode($data);
        $response = new \stdClass();

        $dataConfig = [
            'dataBase' => 'turnos2',
            'tabla' => 'turno',
            'where' => 'visible = 1',
            'order' => 'id_turno DESC',
        ];
        
        $where = "visible = 1 ";

        if($data['estatus'] && $data['estatus'] != 3){
            $where .= " AND id_estatus IN ('".$data['estatus']."')  ";
        }

        if($data['fecha_inicio'] && $data['fecha_final']){

            $where .= " AND (fecha_recepcion BETWEEN '" .$this->funciones->dateEuroToISO($data['fecha_inicio'],"-") . "' AND '" . $this->funciones->dateEuroToISO($data['fecha_final'],"-") . "')";
        }
        
        if($data['resultado_turno'] && $data['resultado_turno'] != 3){
            $where .= " AND id_resultado_turno = '".$data['resultado_turno']."' ";
        }


        $dataConfig['where'] = $where;
      
        $request = $this->globals->getTabla($dataConfig);
        $response = $request->data;
       
        return $this->respond($response);
        
    } */
 
    
    public function getUsuarios()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $dataDB = array();
        if ($session->id_perfil == -1) {
            $dataDB = array('tabla' => 'vw_destinatarios', 'where' => 'visible = 1 ORDER BY id_tipo_cargo ASC');
        } elseif ($session->id_perfil == 1) {
            $dataDB = array('tabla' => 'vw_destinatarios', 'where' => 'visible = 1 ORDER BY id_tipo_cargo ASC');
        } 
        $response = $principal->getTabla($dataDB);
        // var_dump($response);
        // die();
        return $this->respond($response->data);
    }
    public function getUsuario()
    {
        $session = \Config\Services::session();
        $id_destinatario = $this->request->getPost('id_destinatario');
        
        // Validar que el ID de usuario esté presente y sea válido
        if (!$id_destinatario) {
            return $this->fail('ID de usuario no proporcionado', 400);
        }

        // var_dump($id_usuario);
        // die();
        $response = $this->globals->getTabla(["tabla"=>"vw_destinatarios", "select"=>"id_destinatario, nombre_destinatario, cargo, id_tipo_cargo" ,"where"=>["id_destinatario" => $id_destinatario, "visible" => 1]])->data;
        // var_dump($response[0]);
        // die();
        return $this->respond($response[0]);
    }
    public function deleteUsuario()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();

        if (!isset($data['id_destinatario']) || empty($data['id_destinatario'])){
            $response->respuesta = "No se ha proporcionado un identificador válido";
            return $this->respond($response);
        }

        $dataConfig = [
            "tabla"=>"cat_destinatario",
            "editar"=>true,
            "idEditar"=>['id_destinatario'=>$data['id_destinatario']]
        ];
        $response = $this->globals->saveTabla(["visible"=>0],$dataConfig,["script"=>"Opciones.deleteUsuario"]);
        return $this->respond($response);
    }
    public function UpdateUsuario()
    {
        $response = new \stdClass();
        $response->error = true;
        $data = $this->request->getPost();
        // var_dump($data);
        // die();
        
        $dataInsert=[       
            'nombre_destinatario' => $data['nombre_destinatario'],
            'cargo' => $data['cargo'],
            'id_tipo_cargo' => $data['dsc_cargo'],
            
        ];
        // var_dump($dataInsert);
        // die();
        if (isset($data['editar'])){
            $dataConfig = [
                "tabla"=>"cat_destinatario",
                "editar"=>false,
                //  "idEditar"=>['id_usuario'=>$data['id_usuario']]
            ];  
        }else{
            $dataConfig = [
                "tabla"=>"cat_destinatario",
                "editar"=>true,
                 "idEditar"=>['id_destinatario'=>$data['id_destinatario']]
            ];
        }
        

        $response = $this->globals->saveTabla($dataInsert,$dataConfig,["script"=>"opciones.UpdateUsuario"]);
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
            "tabla"=>"seg_usuarios",
            "editar"=>false,
            // "idEditar"=>['id_usuario'=>$data['id_usuario']]
        ];
        $response = $this->globals->saveTabla($dataInsert,$dataConfig,["script"=>"Usuario.saveUsuario"]);
        return $this->respond($response);
    }
    
}