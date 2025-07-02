<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Curps;
use App\Libraries\Fechas;
use App\Libraries\Funciones;
use App\Models\Mglobal;
use App\Models\Magregarturno;


use stdClass;
use Exception;
use CodeIgniter\API\ResponseTrait;

class Agregar extends BaseController {

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
        $session = \Config\Services::session();
        $Mglobal = new Mglobal;   
        $misCursos = $Mglobal->getTabla(['tabla' => 'vw_estudiante_curso', 'where' => ['visible' => 1, 'id_usuario' => $session->id_usuario ]]);
        $data["dscCursos"] = []; // Inicializamos como un arreglo vacío

        if (isset($misCursos->data) && !empty($misCursos->data)) {
            foreach ($misCursos->data as $c) {
                // Obtener la información del curso
                $miCurso = $Mglobal->getTabla([
                    'tabla' => 'cursos_sac', 
                    'where' => [
                        'visible' => 1, 
                        'id_cursos_sac' => $c->id_curso 
                    ]
                ]);
                if (isset($miCurso->data) && !empty($miCurso->data)) {
                    // Agregar los datos del curso al arreglo
                    $data["dscCursos"][] = [
                        'dsc_curso' => $miCurso->data[0]->dsc_curso,
                        'img'       => $miCurso->data[0]->img_ruta,
                        'id'        => $miCurso->data[0]->id_cursos_sac,
                        'periodo'   => $c->id_periodo
                    ];
                }
            }
        }   
        $data = array_merge($this->defaultData, $data);
        echo view($data['layout'], $data);               
    }

  
    public function index()
    {
        $session = \Config\Services::session();
        $data = array();
        $catalogos = new Mglobal;

 

            $data['scripts'] = array('principal','agregar');
            $data['edita'] = 0;
            $data['nombre_completo'] = $session->nombre_completo; 
            $data['contentView'] = 'formularios/vFormAgregar';                
            $this->_renderView($data);
    }
    public function guardaPT()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error|Error al guardar PT";
        $this->globals = new Mglobal();
        $data = $this->request->getPost();


        if($data['secretario'] == 0){
            $response->error = true;
            $response->respuesta = "Es requerido el Secretario o Director";
            return $this->respond($response);
        }
        if(($data['direccion_responsable'])==0){
            $response->error = true;
            $response->respuesta = "Es requerido el Dirección Responsable";
            return $this->respond($response);
        }
        if($data['tipo_pt'] == 0){
            $response->error = true;
            $response->respuesta = "Es requerido el Tipo pt";
            return $this->respond($response);
        }
        if(isset($data['cuenta_bancaria']) && empty($data['cuenta_bancaria'])){
            $response->error = true;
            $response->respuesta = "Es requerido el Cuenta Bancaria";
            return $this->respond($response);
        }
        if(isset($data['fecha_gasto_inicio']) && empty($data['fecha_gasto_inicio'])){
            $response->error = true;
            $response->respuesta = "Es requerido el fecha gasto inicio";
            return $this->respond($response);
        }
        if(isset($data['fecha_gasto_fin']) && empty($data['fecha_gasto_fin'])){
            $response->error = true;
            $response->respuesta = "Es requerido el fecha gasto fin";
            return $this->respond($response);
        }
        if(isset($data['documentacion_comprobatoria']) && empty($data['documentacion_comprobatoria'])){
            $response->error = true;
            $response->respuesta = "Es requerido el documentacion_comprobatorian";
            return $this->respond($response);
        }
        if(isset($data['poliza']) && empty($data['poliza'])){
            $response->error = true;
            $response->respuesta = "Es requerido el poliza";
            return $this->respond($response);
        }
        if(isset($data['formato_conformidad']) && empty($data['formato_conformidad'])){
            $response->error = true;
            $response->respuesta = "Es requerido el formato_conformidad";
            return $this->respond($response);
        }
        if(isset($data['concepto_pago']) && empty($data['concepto_pago'])){
            $response->error = true;
            $response->respuesta = "Es requerido el concepto_pago";
            return $this->respond($response);
        }
        if(isset($data['clausula_contrato']) && empty($data['clausula_contrato'])){
            $response->error = true;
            $response->respuesta = "Es requerido el clausula_contrato";
            return $this->respond($response);
        }
        if(isset($data['no_reserva']) && empty($data['no_reserva'])){
            $response->error = true;
            $response->respuesta = "Es requerido el no_reserva";
            return $this->respond($response);
        }
           $dataInsert = [
                        'id_direccion_responsable' => $data['direccion_responsable'],
                        'tipo_pt'                  => $data['tipo_pt'],
                        'id_proveedor'             => $data['id_proveedor'],
                        'fecha_tramite'            => $data['fecha_tramite'],
                        'id_reponsable_solicitud'  => $session->get('id_usuario'),
                        'director_general'         => 1,
                        'secretario'               => $data['secretario'],
                        'cuenta_bancaria'          => $data['cuenta_bancaria'],
                        'fecha_gasto_inicio'       => $data['fecha_gasto_inicio'],
                        'fecha_gasto_fin'          => $data['fecha_gasto_fin'],
                        'formato_establecido'      => ($data['formato_establecido']=='SI')?1:2,
                        'documentacion_comprobatoria'=>$data['documentacion_comprobatoria'],
                        'poliza'                   =>($data['poliza']=='SI')?1:2,
                        'formato_conformidad'      =>($data['formato_conformidad']=='SI')?1:2,
                        'contrato_convenio'        =>$data['contrato_convenio'],
                        'documentacion_requerida'  =>$data['documentacion_requerida'],
                        'evidencia_entrega'        =>$data['evidencia_entrega'],
                        'otros'                    =>$data['otros'],
                        'clausula_contrato'        =>$data['clausula_contrato'],
                        'concepto_pago'            =>$data['concepto_pago'],
                        'comision'                 =>$data['comision'],
                        'no_reserva'               =>$data['no_reserva']
                    ];
            $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaTurno'];
        if($data['editar'] == 0){
                    $dataConfig = [
                        "tabla"=>"registro_pt",
                        "editar"=>false
                    ];
        }else{   
                $dataConfig = [
                    "tabla"=>"registro_pt",
                    "editar"=>true,
                    'idEditar'=>['id_registro_pt' => $data['id_registro_pt']]
                ];
        }
      
   
        $response = $this->globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
        return $this->respond($response);
    }
    public function guardaUsuarioSti(){
        $session = \Config\Services::session();
        $response = new \stdClass();
        // $response->error = true;
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        
     
    
        if( $data['editar'] !=1){
            if(empty($data['contrasenia']) || empty($data['confirmar_contrasenia'])){
                throw new Exception("Los campos de contraseña son obligatorios");
            }
              
            if($data['contrasenia'] != $data['confirmar_contrasenia'] ){
                throw new Exception("Las contraseñas no son identicas");
            }
        }
      
        if(empty($data['usuario']) ){
            throw new Exception("El campo de <strong>usuario</strong> es requerido");
        }
        if($data['id_sexo'] == 0 ){
            throw new Exception("El campo sexo es requerido");
        }
     
        if($data['id_perfil'] == 0 ){
            throw new Exception("El campo perfil es requerido");
        }
     
        if($data['id_area'] == 0 ){
            throw new Exception("El campo área es requerido");
        }
        if(empty($data['correo']) ){
            throw new Exception("El campo correo es requerido");
        }
        if(empty($data['nombre']) || 
           empty($data['primer_apellido'])){
            throw new Exception("Algunos campos son requeridos");
        }
        if( $data['editar'] !=1){
            $curp  = $this->globals->getTabla(['tabla' => 'usuario', 'where' => ['rfc' => $data['rfc'], 'visible' =>1]]); 
            if( !empty($curp->data) ){
                throw new Exception("El campo de <strong>CURP</strong> ya existe en la base de datos");
            }
            $existente  = $this->globals->getTabla(['tabla' => 'usuario', 'where' => ['usuario' => $data['usuario'], 'contrasenia' => md5($data['contrasenia']),  'visible' =>1]]); 
            if( !empty($existente->data) ){
                throw new Exception("El <strong> usuario y/o contraseña</strong> ya existe en la base de datos, favor de cambiar los datos");
            }
        }
        $hoy = date("Y-m-d H:i:s"); 
        $dataInsert = [
            'id_sexo'               => (int)$data['id_sexo'],             
            'id_perfil'             => (int)$data['id_perfil'],                   
            'usuario'               => $data['usuario'],                
            'nombre'                => $data['nombre'],           
            'primer_apellido'       => $data['primer_apellido'],           
            'segundo_apellido'      => $data['segundo_apellido'],             
            'correo'                => $data['correo'],           
            'rfc'                   => $data['rfc'],                      
            'id_area'               => $data['id_area'],                        
            'fec_nac'               => $data['fec_nac'],            
            'fec_reg'               => $hoy 
        ];
        if(isset($data['contrasenia']) && !empty($data['contrasenia'])){
          $dataInsert['contrasenia'] = md5($data['contrasenia']); 
        }     
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaTurno'];
        
       
        $dataConfig = [
            "tabla"=>"usuario",
            "editar"=>($data['editar']==1)?true:false,
            "idEditar"=>['id_usuario'=>$data['id_usuario']]
        ];
   
        $response = $this->globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
        
        return $this->respond($response);
    }

    private function handleException($e)
    {
        log_message('error', "Se produjo una excepción: " . $e->getMessage());
    }
    function validarCampo($valor, $nombreCampo) {
        // $pattern = "/^([a-zA-Z 0-9]+)$/";
        $pattern = "/^([a-zA-ZáéíóúüñÁÉÍÓÚÜÑ 0-9]+)$/";
        
        if (!preg_match($pattern, $valor)) {
            throw new Exception("Error en el campo '$nombreCampo': Por favor, utilice únicamente caracteres alfanuméricos (letras y números). Gracias.");
        }
    
        return $valor;
    }
    public function cambioPassword()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $Mglobal    = new Mglobal;
        $response->error = true;
        $response->respuesta = 'Error| Error al Generar la consulta';
        $data= $this->request->getPost();
        if (!isset($data['id_usuario']) || empty($data['id_usuario'])){
            $response->respuesta = "No se ha proporcionado un identificador válido";
            return $this->respond($response);
        }
        $usuario = $Mglobal->getTabla(["tabla"=>"usuario","where"=>["id_usuario" => $data['id_usuario'], "visible" => 1]])->data[0];
        if($usuario->contrasenia == md5($data['contrasenia'])){
            $response->error     = true;
            $response->respuesta = 'La contraseña no puede ser la misma que ya esta registrada';
            return $this->respond($response);

        }
        $dataInsert = [
            'cambio_pass' =>1,
            'contrasenia' =>md5($data['contrasenia'])
        ];
        $dataConfig = [
            "tabla"=>"usuario",
            "editar"=>true,
            "idEditar"=>['id_usuario'=>$data['id_usuario']]
        ];
        $result = $Mglobal->saveTabla($dataInsert,$dataConfig,["script"=>"Usuario.deleteUsuario"]);
        if(!$result->error){
            $response->error     = $result->error;
            $response->respuesta = $result->respuesta;

        }
        return $this->respond($response);
    

    }
    public function guardaTurno(){
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
            'anio'                         => $anioActual,
            'id_asunto'                    => $data['asunto'],           
            'fecha_peticion'               => $fecha_peticion,             
            'fecha_recepcion'              => $fecha_recepcion,                           
            'solicitante_titulo'           => $data['titulo_inv'],                 
            'solicitante_nombre'           => $data['nombre_t'],                 
            'solicitante_primer_apellido'  => $data['primer_apellido'],                         
            'solicitante_segundo_apellido' => $data['segundo_apellido'],                         
            'solicitante_cargo'            => $data['cargo_inv'],             
            'solicitante_razon_social'     => $data['razon_social_inv'],                     
            'resumen'                      => $this->validarCampo($data['resumen'],"resumen"),     
            'id_estatus'                   => $data['status'],         
            'observaciones'                => $data['observaciones'],
            'id_resultado_turno'              => $data['id_resultado_turno'],   
            'resultado_turno'              => $data['resultado_turno'],             
            'firma_turno'                  => $data['firma_turno'],         
            'usuario_registro'             => $session->id_usuario,             
            'fecha_registro'               => $formattedDate, 
            // arrays
            'id_destinatario'              => isset($data['nombre_turno']) ? $data['nombre_turno'] : array(), 
            'id_destinatario_copia'        => isset($data['cpp']) ? $data['cpp'] : array(),
            'id_indicacion'                => isset($data['indicacion']) ? $data['indicacion'] : array(),
        ];
       /*  var_dump($dataInsert);
        die(); */
        $dataBitacora = ['id_user' =>  $session->id_usuario, 'script' => 'Agregar.php/guardaTurno'];
        
       
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
     
        $catalogos      = new Mglobal;
      
        foreach($data as $key){
             $insert = [
                'fullname'   => $key['fullname'],
                'categoryid' => $id_categoria,
                'startdate'  => $key['startdate'],
                'enddate'    => $key['enddate'],
                'idnumber'   => $key['idnumber']
             ];
             $result = $catalogos->createCurso($insert, 'crearCursosDesdeCSV');
        
             if(!$result->error){
                $response->error     = false;
                $response->respuesta = 'creacion de cursos exitoso';
             }else{
                $response->error     = true;
                $response->respuesta = 'Inconsistencia en el archivo, verificar ID moodle';
             }
             
        }
    return $response;
        
    }
    public function getAllCursos() {
        $session     = \Config\Services::session();
        $response    = new stdClass();
        $response->error = true ;
        $response->respuesta = 'Error| Error al generar la consulta' ;
        $Mglobal   = new Mglobal;
        $id_cursos_sac = $this->request->getPost('id_cursos_sac');
        $data = [];
        $result = $Mglobal->getTabla(['tabla' => 'cursos_sac', 'where' => ['visible' => 1, 'activo' => 1, 'id_cursos_sac' => $id_cursos_sac]]);
     
        if(!$result->error){
           $response->error     = $result->error;
           $response->respuesta = $result->respuesta;
           $response->data      = $result->data;
        }
              
        return $this->respond($response);
    }
    public function guardarCursoPrograma(){
        $session     = \Config\Services::session();
        $response    = new stdClass();
        $response->error = true ;
        $response->respuesta = 'Error| Error al generar la consulta' ;
        $Mglobal   = new Mglobal;
        $data =  $this->request->getPost();
        $hoy = date("Y-m-d H:i:s"); 
        if($data['editar'] == 0 ){
            $where =['visible' => 1, 'id_curso' => $data['id_curso_sac'], 'id_periodo' =>$data['periodo'], 'id_usuario'=>$session->id_usuario ];
            $registro    = $Mglobal->getTabla(['tabla' => 'estudiante_curso', 'where' => $where]);
        
            if(isset($registro->data) && !empty($registro->data)){
                $response->error = true;
                $response->respuesta = 'El Usuario ya tiene registrado este curso y periodo';
                return $this->respond($response);
            }
        }
     
        $dataConfig = [
            "tabla"=>"estudiante_curso",
            "editar"=>($data['editar']==1)?true:false,
             "idEditar"=>['id_estudiante_curso'=>$data['id_periodo_editar']]
        ];
        if($data['editar']==0){
            $Insert = [
                'id_curso'      => (int)$data['id_curso_sac'],                      
                'id_periodo'    => (int)$data['periodo'],                      
                'id_usuario'    => (int)$session->id_usuario,                                         
                'usu_reg'       => (int)$session->id_usuario,                      
                'fec_reg'       => $hoy   
            ];
        }else{
            $Insert = [     
                'id_periodo'    => (int)$data['periodo'],                                                              
                'usu_act'       => (int)$session->id_usuario,                       
            ];
        }
       
       $dataBitacora = ['id_user' =>  $session->id_usuario, 'script' => 'Agregar.php/guardaCurso'];
       $result = $Mglobal->saveTabla($Insert,$dataConfig,$dataBitacora);
       if(!$result->error){
        $response->error     = false;
        $response->respuesta = $result->respuesta;
       }
        
        return $this->respond($response);
    }
    public function detalleCurso($id_cursos_sac = null) {
        $session     = \Config\Services::session();
   
        $response    = new stdClass();
        $response->error = true ;
        $response->respuesta = 'Error| Error al generar la consulta' ;
        $Mglobal   = new Mglobal;
        $data = [];
        $result    = $Mglobal->getTabla(['tabla' => 'cursos_sac', 'where' => ['visible' => 1, 'activo' => 1, 'id_cursos_sac' => $id_cursos_sac]]);
        $periodo   = $Mglobal->getTabla(['tabla' => 'vw_periodo', 'where' => ['visible' => 1, 'id_curso' => $id_cursos_sac]]);
        $categoria = $Mglobal->getTabla(['tabla' => 'vw_categoria', 'where' => ['visible' => 1, 'id_curso' => $id_cursos_sac]]);
        if(isset($result->data) && empty($result->data)){
            $data['contentView'] = 'secciones/vError500';
            $data['layout'] = 'plantilla/lytLogin';
            $this->_renderView($data);
            die();
          
        }
        $data['curso']= $result->data[0];
        if(!$periodo->error){
           $data['periodo']= (isset($periodo->data) && !empty($periodo->data))?$periodo->data:[];
        }
        if(!$categoria->error){
           $data['categoria']= (isset($categoria->data) && !empty($categoria->data))?$categoria->data:[];
        }
        $data['registro'] = false;
        if($id_cursos_sac){
            $result = $Mglobal->getTabla(['tabla'=>'estudiante_curso', 'where' =>['id_curso' => $id_cursos_sac, 'id_usuario' => $session->id_usuario, 'visible' => 1,]]);
            if(isset($result->data) && !empty($result->data)){
               $data['registro'] = true;
            }
        }
        $usuRegCurso   = $Mglobal->getTabla(['tabla' => 'estudiante_curso', 'where' => ['visible' => 1, 'id_curso' => $id_cursos_sac, 'id_usuario' => $session->id_usuario]]);
        if(isset( $usuRegCurso->data) && !empty( $usuRegCurso->data)){
           $data['id_periodo_editar'] = $usuRegCurso->data[0]->id_estudiante_curso;
        }

        $data['scripts'] = array('agregar');
        $data['contentView'] = 'secciones/vDetalleProgramar';                
        $this->_renderView($data);
    }
    public function TablaPrograma() {
        $session     = \Config\Services::session();
        $response    = new stdClass();
        $Mglobal   = new Mglobal;
        $data = [];
        $data['usuario'] = [];
        $data['cursos_sac'] = []; // Inicializa el array

        $cursoSac = $Mglobal->getTabla(['tabla' => 'cursos_sac', 'where' => ['visible' => 1, 'activo' => 1]]);
        if (isset($cursoSac->data) && !empty($cursoSac->data)) {
            $sac = $cursoSac->data;
            foreach ($sac as $s) {
                $periodos = [];
                $counts = [];
                
                for ($i = 1; $i <= 9; $i++) {
                    $periodos[$i] = $Mglobal->getTabla([
                        'tabla' => 'vw_estudiante_curso',
                        'where' => ['visible' => 1, 'id_curso' => $s->id_cursos_sac, 'id_periodo' => $i]
                    ])->data;
                    
                    $counts[$i] = count($periodos[$i]);
                }
          
                $data['cursos_sac'][] = [
                    'id_cursos_sac'   => $s->id_cursos_sac,
                    'dsc_curso'       => $s->dsc_curso,
                    'periodos'        => $periodos, // Array de nombres de estudiantes
                    'contador'        => $counts
                ];
            }
        }
/*         if (isset($cursoSac->data) && !empty($cursoSac->data)) {
            $sac = $cursoSac->data;
            $contador = count($sac);
            foreach ($sac as $s) {
                $vwEstudianteCurso = $Mglobal->getTabla([
                    'tabla' => 'vw_estudiante_curso',
                    'where' => ['visible' => 1, 'id_curso' => $s->id_cursos_sac]
                ])->data;
                $periodos = [];
                die( var_dump($vwEstudianteCurso) );
                foreach ($vwEstudianteCurso as $v) {
                    $periodos[] = $v->id_periodo; // Agregar cada estudiante al array
                }
                $data['cursos_sac'][] = [
                    'id_cursos_sac'   => $s->id_cursos_sac,
                    'dsc_curso'       => $s->dsc_curso,
                    'periodos'        => $periodos, // Array de nombres de estudiantes
                    'contador'        => $contador
                ];
            }
        } */
        
       // die(var_dump($data['cursos_sac']));

        if($session->id_perfil == 1):
        $cursoDB = $Mglobal->getTabla([
            'tabla' => 'vw_estudiante_curso', 
            'where' => [
                'visible' => 1, 
            ]
        ]);
        endif;
        if($session->id_perfil != 1):
        $cursoDB = $Mglobal->getTabla([
            'tabla' => 'vw_estudiante_curso', 
            'where' => [
                'visible' => 1, 
                'id_dependencia' => $session->id_dependencia
            ]
        ]);
        endif;
        if (isset($cursoDB->data) && !empty($cursoDB->data)) {
            // Arreglo temporal para agrupar los datos por usuario
            $usuarios = [];
        
            foreach ($cursoDB->data as $c) {
                $nombreUsuario = $c->nombre_completo;
        
                // Si el usuario no existe en el arreglo, lo inicializamos
                if (!isset($usuarios[$nombreUsuario])) {
                    $usuarios[$nombreUsuario] = [
                        'nombre' => '<h6>'.$nombreUsuario.'</h6>',
                        'P1' => '',
                        'P2' => '',
                        'P3' => '',
                        'P4' => '',
                        'P5' => '',
                        'P6' => '',
                        'P7' => '',
                        'P8' => '',
                        'P9' => ''
                    ];
                }
        
                $key = 'P' . $c->id_periodo;
                if (isset($usuarios[$nombreUsuario][$key])) {
                    // Si ya hay un curso en este periodo, agregamos el nuevo curso separado por una coma
                    if (!empty($usuarios[$nombreUsuario][$key])) {
                        $usuarios[$nombreUsuario][$key] .= '<br> ';
                    }
                    $usuarios[$nombreUsuario][$key] .= '<span class="badge badge-md badge-soft-purple">'.$c->dsc_curso.'</span>';
                }
            }

            $data['usuario'] = array_values($usuarios);
        }
       
        $data['scripts'] = array('agregar');
        $data['contentView'] = 'secciones/vTablaPrograma';                
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

        public function Asistencia($mes = null, $user = null) 
        {
            $session = \Config\Services::session();
            $response = new stdClass();
            $Mglobal = new Mglobal;
            $calendarStatic = true;
      
            $data = [];
            if (isset($mes) && !empty($mes)) {
                try {
                    $meses = $this->meses($mes);
                    $agenda = $Mglobal->getTabla([
                        'tabla' => 'asistencia',
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
            }else{
                 $agenda = $Mglobal->getTabla([
                        'tabla' => 'asistencia',
                        'where' => [
                            'visible' => 1,
                             'id_usuario' => $session->get('id_usuario')
                        ],
                    ]);
            }
            $mes  = ($mes)? $mes: date('m');
            $data['anio'] = date('Y');
            $asistencia = (isset($agenda->data) && !empty($agenda->data))?$agenda->data:[];
            $data['asistencia'] = $asistencia;
            $data['mes'] = $mes;
            $data['calendarStatic'] = $calendarStatic;
            $data['scripts'] = array('agregar');
            $data['contentView'] = 'secciones/vAsistencia';                
            $this->_renderView($data);
        }
    public function ReservarSala() {
        $session     = \Config\Services::session();
        $response    = new stdClass();
        $Mglobal   = new Mglobal;
        $data = [];

        $sala_junta  = $Mglobal->getTabla(['tabla' => 'sala_junta', 'where' => ['visible' =>1]]); 
        $hoy = date("Y-m-d");
        $sala_hoy = $Mglobal->getTabla([
            'tabla' => 'sala_junta',
            'where' => [
                'visible' => 1,
                'DATE(fecha)' => $hoy // Filtra solo el día, ignorando la hora
            ]
        ]);

        $data['sala_junta'] = (isset($sala_junta->data) && !empty($sala_junta->data))?$sala_junta->data:[];
        $data['sala_hoy']   = (isset($sala_hoy->data) && !empty($sala_hoy->data))?$sala_hoy->data:[];
        $data['scripts']    = array('agregar', 'inicio');
        $data['contentView'] = 'secciones/vSala';                
        $this->_renderView($data);
    }
    public function Configuracion() {
        $session     = \Config\Services::session();
        $response    = new stdClass();
        $catalogos   = new Mglobal;
        // Obtener el evento_id encriptado desde GET y desencriptarlo
        $id_curso = $this->request->getGet('id_curso');
    
        if (!$id_curso) {
            // Manejar error de desencriptación
            echo "ID no válido o error de desencriptación.";
            return;
        }
        $datos = ['courseId' => $id_curso ];
        $categoria = "";
        $quizz = $catalogos->createCurso($datos, 'traerQuiz');
        $details = $catalogos->createCurso($datos, 'getCourseDetailsById');
        //die( var_dump( $details ) );
        if(!empty($quizz->data)){
            $data['quizz'] = $quizz->data;
        }
        if(!empty($details->data)){
            $data['details'] = $details->data;
            $insert = [
                 'categoryId' => $details->data[0]->categoryid
            ];
            $categoria = $catalogos->createCurso($insert, 'getCoursesByCategoryId');
            $data['categoria'] = $categoria->data;
            $data['fec_inicio'] = date('d-m-Y', $categoria->data[0]->startdate); 
            $data['fec_fin'] = date('d-m-Y', $categoria->data[0]->enddate); 
        }
        //var_dump( $categoria->data[0]->modules );
        $data['id_curso'] = $id_curso;

        $data['scripts'] = array('agregar');
        $data['contentView'] = 'secciones/vConfiguracion';                
        $this->_renderView($data);
    }
    public function registroSala()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error =true;
        $response->respuesta ='Error| Error al guardar Sala';
        $globals = new Mglobal;
        $data = $this->request->getPost();
    
        if(empty($data['hora_inicio'])){
            $response->respuesta ='Es requerido la hora de inicio';
            return $this->respond($response);

        }
        if(empty($data['hora_fin'])){
            $response->respuesta ='Es requerido la hora de fin';
            return $this->respond($response);

        }
        if(empty($data['asistentes'])){
            $response->respuesta ='Es requerido el numero de asistentes';
            return $this->respond($response);

        }
        if(empty($data['evento'])){
            $response->respuesta ='Es requerido el nombre del evento';
            return $this->respond($response);

        }
    
        // Convertir a timestamps para comparación
        $inicio = strtotime($data['hora_inicio']);
        $fin = strtotime($data['hora_fin']);
        
        if ((int)$fin < (int)$inicio) {
            $response->respuesta = 'La hora de fin debe ser mayor a la hora de inicio';
            return $this->respond($response);
        }
      
        $dataInsert = [
            'sala'        => $data['sala'],
            'fecha'       => $data['fecha'].' '.$data['hora_inicio'],
            'hora_inicio' => $data['hora_inicio'],
            'hora_fin'    => $data['hora_fin'],
            'evento'      => $data['evento'],
            'asistentes'  => $data['asistentes'],
            'proyector'    => $data['proyecto'],
            'tipo_reunion'=> $data['tipo_reunion'],
            'catering'    => $data['catering']
        ];
           $dataBitacora = ['id_user' =>  $session->id_usuario, 'script' => 'Agregar.php/guardaCurso'];
           $dataConfig = [
                "tabla"=>"sala_junta",
                "editar"=>false
            ];
          
           $sala = $globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
           if(!$sala->error){
            $response->error     = $sala->error;
            $response->respuesta = $sala->respuesta;
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
    public function guardaCategoria(){
        $session = \Config\Services::session();
        $response = new \stdClass();
        // $response->error = true;
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        
        $hoy = date("Y-m-d H:i:s"); 
      
        if(empty($data['nombre_curso']) ){
            throw new Exception("Es requerido el Nombre del curso");
        }
        //valida que el nombre del curso y nombre corto del curso no se repitan
        if(!empty($data['nombre_curso']) ){
            $cursoDB = $this->globals->getTabla(['tabla' => 'categoria', 'where' => ['dsc_categoria'=> $data['nombre_curso'] ,'visible' => 1]]);
            if(!empty($cursoDB->data) && isset($cursoDB->data[0]->dsc_categoria) ){
                throw new Exception("Es Nombre del curso ya existe");
            }

        }
          
        $dataBitacora = ['id_user' =>  $session->id_usuario, 'script' => 'Agregar.php/guardaCurso'];
        $dataInsert = [
            'categoryName' => $data['nombre_curso'],                      
            'courseName' => 'Curso de Prueba',
            'startDate' => '2023-01-01',
            'endDate' => '2023-12-31' 
        ];
   
        $response = $this->globals->createCurso($dataInsert, 'crearCategoria');
      
        if($response->error){
            throw new Exception("No se puedo crear la Categoria");
        }else{
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
        }
      
        return $this->respond($response);
    }
    public function formConfigurarCurso() {
        $session     = \Config\Services::session();
        $response    = new stdClass();
        $catalogos   = new Mglobal;

        // Obtener el evento_id encriptado desde GET y desencriptarlo
        $formData = $this->request->getPost();

        //validar que ya exista el curso 
        $cursoExiste        = $catalogos->getTabla(['tabla' => 'cursos_perfil', 'where' => ['id_curso'=> $formData['id_curso'] ,'visible' => 1, 'id_padre'   => $session->get('id_perfil') ]]);
        if(empty($cursoExiste->data) ){
           
            $insert = [
                'id_curso'   => (int)$formData['id_curso'],
                'id_padre'   => $session->get('id_perfil'),
                'fec_reg'    => date("Y-m-d H:i:s"),
                'usu_reg'    => $session->get('id_usuario')
            ]; $dataBitacora = ['id_user' =>  $session->id_usuario, 'script' => 'Agregar.php/updateEventos'];
   
            $dataConfig = [
                "tabla"=>"cursos_perfil",
                "editar"=>false,
               // "idEditar"=>['id_curso_moodle'=>$formData['id_curso']]
            ];
           $result = $catalogos->saveTabla($insert,$dataConfig,$dataBitacora);
            if(!$result->error){
                $response->error = $result->error;
                $response->respuesta = $result->respuesta;
            }else{
                $response->error = true;
                $response->respuesta = 'Error al actualizar las fechas';
            }

        }
       
       
        foreach ($formData['tableData'] as $key) {
            // Accede a los valores directamente sin `$i` en el índice
            if(isset($key["id_curso"]) && $key["id_curso"] > 0 ){
                $data = [
                    'id_curso'  => $key["id_curso"],
                    'timeopen'  => strtotime($key["timeopen"]),  // Convierte a Unix timestamp
                    'timeclose' => strtotime($key["timeclose"])  // Convierte a Unix timestamp
                ];
            $result       = $catalogos->createCurso($data, 'updateQuiz'); 
                if(!$result->error){
                    $response->error = $result->error;
                    $response->respuesta = $result->respuesta;
                }else{
                    $response->error = true;
                    $response->respuesta = 'Error al actualizar las fechas';
                }
               
            }
        }

        return $this->respond($response);
    }

  
}