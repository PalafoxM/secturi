<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Curps;
use App\Libraries\Fechas;
use App\Libraries\Funciones;
use App\Models\Mglobal;

use stdClass;
use CodeIgniter\API\ResponseTrait;
require_once FCPATH . '/mpdf/autoload.php';
class Inicio extends BaseController {

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
        $misCursos = $Mglobal->getTabla(['tabla' => 'estudiante_curso', 'where' => ['visible' => 1, 'id_usuario' => $session->id_usuario ]]);
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
                        'img' => $miCurso->data[0]->img_ruta,
                        'id' => $miCurso->data[0]->id_cursos_sac,
                        'periodo'   => $c->id_periodo
                    ];
                }
            }
        }  
        //die(var_dump($data["dscCursos"]));
        $data = array_merge($this->defaultData, $data);
        echo view($data['layout'], $data); 
                      
    }

    public function index()
    {        
        $session = \Config\Services::session();
        $data        = array();
       
     
   
        $data['scripts'] = array('principal','inicio');
        $data['edita'] = 0;
        $data['nombre_completo'] = $session->nombre_completo; 
        $data['contentView'] = 'secciones/vInicio';                
        $this->_renderView($data);
        
    }
    public function Chat()
    {        
        $session = \Config\Services::session();
        $data        = array();
        $data['scripts'] = array('principal','inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vChat';                
        $this->_renderView($data);
        
    }
    public function Preinscritos()
    {        
        $session = \Config\Services::session();   
        $data = array();
        
      
        $globas              = new Mglobal;
        if($session->id_perfil == 1){
            $detenidos           = $globas->getTabla(['tabla' => 'detenidos', 'where' => ['visible' => 1]]);
            $participantes         = $globas->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1]]);
        }else{
            $detenidos           = $globas->getTabla(['tabla' => 'detenidos', 'where' => ['visible' => 1, 'id_dependencia' => $session->id_dependencia]]);
            $participantes         = $globas->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1, 'id_dependencia' => $session->id_dependencia]]);
        }

        $dataDB           = array('tabla' => 'cat_nivel', 'where' => ['visible' => 1]);
        $dependenciaDB    = array('tabla' => 'cat_dependencia', 'where' => ['visible' => 1]);
        $perfilDB         = array('tabla' => 'cat_perfil', 'where' => ['visible' => 1]);
        $cat_nivel        = $globas->getTabla($dataDB);
        $cat_dependencia  = $globas->getTabla($dependenciaDB);
        $cat_perfil       = $globas->getTabla($perfilDB);
        $cat_municipio    = $globas->getTabla(['tabla' => 'cat_municipio', 'where' => ['visible' => 1]]);

        $data['cat_nivel']       =$cat_nivel->data;
        $data['cat_dependencia'] =$cat_dependencia->data;
        $data['cat_perfil']      =$cat_perfil->data;
        $data['cat_municipio']   =$cat_municipio->data;
        $data['detenidos']   = (isset($detenidos) && !empty($detenidos))?$detenidos->data:[];
        $data['participantes'] = (isset($participantes) && !empty($participantes))?$participantes->data:[];
        $data['scripts'] = array('agregar','inicio');
        $data['contentView'] = 'secciones/vPreinscritos';                
        $this->_renderView($data);
        
    }
    public function categorias()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        if($session->id_perfil == 6){
            $data['contentView'] = 'secciones/vError500';
            $data['layout'] = 'plantilla/lytLogin';
            $this->_renderView($data);
            die();
        }
        // Obtener categorías desde la base de datos
        $dataDB = ['tabla' => 'categoria', 'where' => ['visible' => 1]];
        $cat  = $principal->getTabla(['tabla' => 'categorias_padre', 'where' => ['visible' =>1]]); 
        $response = $principal->getTabla($dataDB);
        $data['categoria']   = (isset($cat->data) && !empty($cat->data))?$cat->data:[];
        $data['scripts']     = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vCategorias';
        $this->_renderView($data);
    }
    public function altaUsuario()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        if($session->id_perfil == 8){
            header('Location:'.base_url().'index.php/');            
            die();
        }
        $usuario     = $principal->getTabla(['tabla' => 'usuario', 'where' => ['visible' =>1]]); 
        $cat_perfil  = $principal->getTabla(['tabla' => 'perfil', 'where' => ['visible' =>1]]); 
        $cat_area  = $principal->getTabla(['tabla' => 'cat_area', 'where' => ['visible' =>1]]); 
     
        $data['editar']     = 0;
        $data['cat_perfil'] = $cat_perfil->data;
        $data['cat_area']   = $cat_area->data;
        $data['usuario']    = (isset($usuario->data) && !empty($usuario->data))?$usuario->data:[];
        $data['scripts']     = ['principal', 'agregar'];
        $data['contentView'] = 'secciones/vAltaUsuario';
        $this->_renderView($data);
    }
    public function usuarios()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
    
        // Mapeo de casos para obtener usuarios según el perfil
        $usuarioQuery = [
            'tabla' => 'vw_usuario',
            'where' => ['visible' => 1]
        ];
        // Obtener usuarios
        $usuario = $principal->getTabla($usuarioQuery);
        $cat_perfil  = $principal->getTabla(['tabla' => 'perfil', 'where' => ['visible' =>1]]); 
        $cat_area  = $principal->getTabla(['tabla' => 'cat_area', 'where' => ['visible' =>1]]); 
        $data['cat_perfil'] = $cat_perfil->data;
        $data['cat_area']   = $cat_area->data;
        // Asignar datos adicionales
        $data['usuario'] = isset($usuario->data) && !empty($usuario->data) ? $usuario->data : [];
        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vUsuarios';
    
        // Renderizar la vista
        $this->_renderView($data);
    }
    public function listaPerfil()
    {
        $session = \Config\Services::session();
        $principal            = new Mglobal;
        $cat_perfil           = $principal->getTabla(['tabla' => 'perfil', 'where'=>['visible' => 1]]);
        $data['cat_perfil']   = (isset($cat_perfil->data) && !empty($cat_perfil->data))?$cat_perfil->data:[];
        $data['scripts']     = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vlistaPerfiles';
        $this->_renderView($data);   
    }
  public function subirAsistencia()
    {
        $session     = \Config\Services::session();
        $principal   = new Mglobal;
        $cat_usuario = $principal->getTabla([
            'tabla' => 'vw_asistencia',
            'where' => ['visible' => 1]
        ]);

        // Enviar datos a la vista
        $data['mes']           = date('m');
        $data['cat_usuario']   = $cat_usuario->data ?? [];  
        $data['scripts']       = ['principal', 'inicio'];
        $data['contentView']   = 'secciones/vSubirAsistencia';
        $this->_renderView($data);
    }
  public function vehiculos()
    {
        $session     = \Config\Services::session();
        $principal   = new Mglobal;
        $vehiculos = $principal->getTabla([
            'tabla' => 'vehiculo',
            'where' => ['visible' => 1]
        ]);
        $data['vehiculos']   = $vehiculos->data ?? [];  
        $data['scripts']       = ['principal', 'inicio'];
        $data['contentView']   = 'secciones/vVehiculos';
        $this->_renderView($data);
    }
/*   public function subirAsistencia()
    {
        $session     = \Config\Services::session();
        $principal   = new Mglobal;
        $cat_usuario = $principal->getTabla([
            'tabla' => 'vw_usuario',
            'where' => ['visible' => 1]
        ]);

        if (isset($cat_usuario->data) && !empty($cat_usuario->data)) {
              $anio = $anio ?? date('Y');

            foreach ($cat_usuario->data as &$usuario) {
                $id_usuario = $usuario->id_usuario;
                $asistenciasPorMes = [];

                for ($mes = 1; $mes <= 12; $mes++) {
                    $fecha_inicio = date("Y-m-01", strtotime("$anio-$mes-01"));
                    $fecha_fin    = date("Y-m-t", strtotime($fecha_inicio));

                    $tabla = [
                        'tabla' => 'asistencia',
                        'where' => [
                            'visible' => 1,
                            'id_usuario' => $id_usuario
                        ],
                        'whereBetween' => [
                            ['fecha', $fecha_inicio, $fecha_fin]
                        ]
                    ];

                    $asistencia = $principal->getTabla($tabla);

                    if (isset($asistencia->data) && !empty($asistencia->data)) {
                        $diasTrabajados = count($asistencia->data);
                        $asistenciasPorMes[$mes] = [
                            'dias' => $diasTrabajados,
                            'cumplio' => ($diasTrabajados >= 20) ? 1 : 0
                        ];
                    } else {
                        $asistenciasPorMes[$mes] = [
                            'dias' => 0,
                            'cumplio' => 0
                        ];
                    }
                }

                // Agrega los datos de asistencia al objeto de usuario
                $usuario->asistencias = $asistenciasPorMes;
            }
        }

        // Enviar datos a la vista
        $data['cat_usuario']   = $cat_usuario->data ?? [];  
        $data['scripts']       = ['principal', 'inicio'];
        $data['contentView']   = 'secciones/vSubirAsistencia';
        $this->_renderView($data);
    } */

    public function listaPuesto()
    {
        $session = \Config\Services::session();
        $principal            = new Mglobal;
      
        $cat_puesto           = $principal->getTabla(['tabla' => 'cat_puesto', 'where'=>['visible' => 1]]); 
        $data['cat_puesto']   = (isset($cat_puesto->data) && !empty($cat_puesto->data))?$cat_puesto->data:[];
        $data['scripts']     = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vlistaPuesto';
        $this->_renderView($data);   
    }
    public function listaTiket()
    {
        $session = \Config\Services::session();
        $principal            = new Mglobal;
        if($session->get('id_perfil')==1){
        $cat_tiket  = $principal->getTabla(['tabla' => 'vw_tiket', 'where'=>['visible' => 1]]); 
        }else{
        $cat_tiket  = $principal->getTabla(['tabla' => 'vw_tiket', 'where'=>['usuario'=> $session->get('id_usuario'), 'visible' => 1]]); 
        }


        $data['cat_tiket']   = (isset($cat_tiket->data) && !empty($cat_tiket->data))?$cat_tiket->data:[];
        $data['scripts']     = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vListaTiket';
        $this->_renderView($data);   
    }
    public function listaArea()
    {
        $session = \Config\Services::session();
        $principal            = new Mglobal;
        $cat_area             = $principal->getTabla(['tabla' => 'cat_area', 'where'=>['visible' => 1]]); 
        $data['cat_area']     = (isset($cat_area->data) && !empty($cat_area->data))?$cat_area->data:[];
        $data['scripts']     = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vlistaArea';
        $this->_renderView($data);   
    }
    public function activarCategoria()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $response = new \stdClass();
        if($session->id_usuario != 1) {
            $response->error =true;
            $response->respuesta ='Perfil AG';
            return $this->respond($response);
        }
        $response->error =true;
        $response->respuesta ='Error al guardar';
        $id_categoria = $this->request->getPost('id_categoria');
        $id = $this->request->getPost('id');
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaCategoriasPadre'];
        $dataConfig = [
            "tabla"=>"categorias_padre",
            "editar"=>true,
            "idEditar"=>['id_categoria'  => (int)$id_categoria]
        ]; 
        if($id == 1){
            $insert = ['activo' => 1];
        }else{
            $insert = ['activo' => 0];
        }
       
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaCategoriasPadre'];
        $dataConfig = ["tabla"=>"categorias_padre", "editar"=>true, "idEditar"=>['id_categoria'=> (int)$id_categoria]];
        $result    = $principal->saveTabla($insert ,$dataConfig, $dataBitacora );
           if(!$result->error){
               $response->error= $result->error;
               $response->respuesta= $result->respuesta;
           }
        
        return $this->respond($response);
    }
    // Función para formatear el árbol para jsTree
    private function formatForJsTree($tree)
    {
        // Obtener los IDs de las categorías activas
        $principal = new Mglobal;
        $activos = [];
        $activo = $principal->getTabla(['tabla' => 'categorias_padre', 'where' => ['visible' => 1, 'activo' => 1]]);
        
        if (isset($activo->data) && !empty($activo->data)) {
            foreach ($activo->data as $d) {
                $activos[] = $d->id_categoria;
            }
        }
    
        // Formatear el árbol para jsTree
        $formattedTree = [];
        foreach ($tree as $node) {
            // Verificar si el nodo debe estar deshabilitado
            $disabled = in_array($node->id, $activos);
    
            // Crear el nodo formateado
            $formattedNode = [
                'id' => $node->id,
                'text' => $node->name,
                'state' => [
                    'disabled' => false, // Deshabilitar el nodo si está en $activos
                    'opened' =>  false,    // Abrir el nodo si es necesario
                    'selected' => $disabled, // Seleccionar el nodo si es necesario
                ],
                'children' => !empty($node->children) ? $this->formatForJsTree($node->children) : [],
            ];
    
            // Agregar el nodo formateado al árbol
            $formattedTree[] = $formattedNode;
        }
    
        return $formattedTree;
    }

    // Función para generar el HTML del árbol
    private function generateTreeHtml($tree)
    {
        $html = '<ul>';
        foreach ($tree as $node) {
            $html .= '<li data-jstree=\'{"icon":"fa fa-folder text-warning font-18"}\'>' . $node['text'];
            if (!empty($node['children'])) {
                $html .= $this->generateTreeHtml($node['children']);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
    

    public function getPrincipal()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
       
        $dataDB = array('tabla' => 'turno', 'where' => 'visible = 1 ORDER BY fecha_registro DESC');  
        $response = $principal->getTabla($dataDB); 
      
         return $this->respond($response->data);
    }
    public function getCurso()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $dataDB = array('tabla' => 'categoria', 'where' => ['visible ' => 1]);
        $response = $principal->getTabla($dataDB);

        // Obtener categorías y construir el árbol de categorías
        $result = $principal->getCategories('getCategories');
        $categoryMap = [];
        $tree = [];

        if (!empty($result->data)) {
            foreach ($result->data as $category) {
                $category->children = [];
                $categoryMap[$category->id] = $category;
            }
            foreach ($result->data as $category) {
                if ($category->parent == 0 || !isset($categoryMap[$category->parent])) {
                    $tree[] = &$categoryMap[$category->id];
                } else {
                    $categoryMap[$category->parent]->children[] = &$categoryMap[$category->id];
                }
            }
        }
       // insertamos las categorias padre 
       if($session->get('id_perfil') == 1){
        if (!empty($result->data)) {
            foreach ($result->data as $category) {
                if ($category->parent == 0) { // Filtrar solo categorías padre
                    $dataInsert = [
                        'id_categoria'  => (int)$category->id,
                        'dsc_categoria' => $category->name
                    ];
                    
                    $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaCategoriasPadre'];
                    
                   
                    $dataConfig = [
                        "tabla"=>"categorias_padre",
                        "editar"=>false
                    ];
                    $cat  = $principal->getTabla(['tabla' => 'categorias_padre', 'where' => ['id_categoria' => (int)$category->id, 'visible' =>1]]); 
                    if(isset($cat->data) && empty($cat->data)){
                        $principal->saveTabla($dataInsert,$dataConfig,$dataBitacora);
                    }
                    

                }
            }
        }
       }
       

        // Filtrar categorías si el perfil es diferente de 1
        if ($session->get('id_perfil') != 1) {
            $cursos = []; // Array para múltiples categorías
            $activo = $principal->getTabla(['tabla' => 'categorias_padre', 'where' => ['visible' => 1, 'activo' => 1]]);
            
            // Si hay categorías activas, se agregan al array
            if (isset($activo->data) && !empty($activo->data)) {
                foreach ($activo->data as $categoria) {
                    $cursos[] = $categoria->dsc_categoria; // Agregar cada categoría activa
                }
            } else {

                $cursos[] = 'CURSO 2025'; // Valor por defecto si no hay activas
            }
            
            $tree = $this->filterTree($tree, $cursos); // Filtrar con múltiples categorías
        }
        
        
        // Formatear el árbol para jsTree
        $formattedTree = $this->formatForJsTree($tree);
       
        return $this->respond($formattedTree);
    }
    private function filterTree($tree, $cursos) {
        $filteredTree = [];
        
        foreach ($tree as $node) {
            // Verificar si el nodo actual está en el array de categorías permitidas
            if (in_array($node->name, $cursos)) {
                $filteredTree[] = $node;
            } elseif (!empty($node->children)) {
                // Filtrar los hijos si los hay
                $node->children = $this->filterTree($node->children, $cursos);
                if (!empty($node->children)) {
                    $filteredTree[] = $node;
                }
            }
        }
        
        return $filteredTree;
    }

    public function pdfTurno(){
        // $session = \Config\Services::session();
        setlocale(LC_TIME, 'es_ES');
        $catalogos = new Mglobal;
        $dataPage = [];
        $mpdf = new \Mpdf\Mpdf();
        $id_turno= $this->request->getGet('id_turno');
        // Agregar contenido al PDF
        // $dataPage['id_turno'] = $id_turno;
        $select = '
        id_turno,
        anio, 
        id_asunto,
        fecha_recepcion,
        solicitante_titulo, 
        solicitante_nombre,
        solicitante_primer_apellido,
        solicitante_segundo_apellido, 
        solicitante_cargo,
        solicitante_razon_social,
        resumen,
        usuario_registro,
        firma_turno,
        ';
        $dataDB = array('select' => $select,'tabla' => 'turno', 'where' => 'id_turno= "'.$id_turno.'" AND visible = 1');
        $response = $catalogos->getTabla($dataDB);

        $dataPage['id_turno'] =     $response->data[0]->id_turno;
        $dataPage['anio'] =         $response->data[0]->anio;
        $titulo = (empty($response->data[0]->solicitante_titulo)) ? '' : $response->data[0]->solicitante_titulo.".";
        $dataPage['nombre_completo'] = $response->data[0]->solicitante_nombre." ".$response->data[0]->solicitante_primer_apellido." ".$response->data[0]->solicitante_segundo_apellido;
        $dataPage['cargo'] = $response->data[0]->solicitante_cargo;
        $dataPage['razon_social'] = $response->data[0]->solicitante_razon_social; 
        $dataPage['resumen'] =$response->data[0]->resumen; 
       
        $dataPage['fecha_recepcion'] =  strftime('%d/%b/%y', strtotime($response->data[0]->fecha_recepcion));;
        
        $dataDB = array('select' => 'dsc_asunto', 'tabla' => 'cat_asuntos', 'where' => 'id_asunto= "'.$response->data[0]->id_asunto.'" AND visible = 1');
        $responseAsunto = $catalogos->getTabla($dataDB);
        $dataPage['asunto'] = $responseAsunto->data[0]->dsc_asunto;

        $dataDB = array('select' => 'usuario','tabla' => 'seg_usuarios', 'where' => 'id_usuario= "'.$response->data[0]->usuario_registro.'" AND visible = 1');
        $responseUsuario = $catalogos->getTabla($dataDB);
        $dataPage['usuario_registro'] = $responseUsuario->data[0]->usuario;
        // turnado a: 
        $dataDB = array('select' => 'nombre_destinatario,cargo','tabla' => 'cat_destinatario', 'where' => 'id_destinatario IN (SELECT id_destinatario FROM `turno_destinatario` WHERE id_turno ="'.$id_turno.'")');
        $responseIndicacion = $catalogos->getTabla($dataDB);
        $dataPage['turnado'] =$responseIndicacion->data;
        // con indicaciones
        $dataDB = array('select' => 'dsc_indicacion','tabla' => 'cat_indicaciones', 'where' => 'id_indicacion IN (SELECT id_indicacion FROM `turno_indicacion` WHERE id_turno ="'.$id_turno.'")');
        $responseIndicacion = $catalogos->getTabla($dataDB);
        $dataPage['indicaciones'] =$responseIndicacion->data;
        //  var_dump($responseCopia->data);
        //   die();
        // con copia
        $dataDB = array('select' => 'nombre_destinatario','tabla' => 'cat_destinatario', 'where' => 'id_destinatario IN (SELECT id_destinatario FROM `turno_con_copia` WHERE id_turno = "'.$id_turno.'")');
        $responseCopia = $catalogos->getTabla($dataDB);
        $dataPage['destinatarioCopia'] =$responseCopia->data;
        //  var_dump($responseCopia->data);
        //   die();

        $dataImagen = $this->encode_img_base64(FCPATH .'assets/images/formato.png', 'png');
        $mpdfConfig = [
            'fontDir' => FCPATH . 'assets/fonts/custom/',
            'fontdata' => [
                'proxima-nova' => [
                    'R' => 'proxima-nova.ttf',
                ],
            ],
        ];
        
        $mpdf = new \Mpdf\Mpdf($mpdfConfig);
        
        $html = view("pdfs/vpdfTurno.php", ["dataPage" => $dataPage,"dataImagen" =>$dataImagen] );
        $mpdf->WriteHTML($html);

        // Generar el PDF
        $mpdf->Output('output.pdf', 'I'); // Descargar el PDF directamente
        exit;
    }
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

    
}