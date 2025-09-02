<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Curps;
use App\Libraries\Fechas;
use App\Libraries\Funciones;
use App\Models\Mglobal;
use App\Models\Magregarturno;
use Config\Services;



use DateTime;



require_once FCPATH . "qr_code/autoload.php";
require_once FCPATH . "mpdf/autoload.php";


use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;


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
        $data = array_merge($this->defaultData, $data);
        echo view($data['layout'], $data);               
    }

  
    public function index()
    {
        $session = \Config\Services::session();
        $data = array();
        $catalogos = new Mglobal;
        $subordinados = $catalogos->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_jefe_inmediato' => $session->id_usuario]])->data;
        $esJefe = (!empty($subordinados))?true:false;


       $data['scripts'] = array('principal','agregar');
       $data['edita'] = 0;
       $data['esJefe'] = $esJefe ;
       $data['nombre_completo'] = $session->nombre_completo; 
       $data['contentView'] = 'formularios/vFormAgregar';                
       $this->_renderView($data);
    }
   public function procesarPediodoGo($periodo = array(), $id_registro_go= null)
    {
        $session = \Config\Services::session();
        $data = array();
        $response = new \stdClass();
        $this->globals = new Mglobal();
     
       foreach ($periodo as $p) {
            $dataConfig = [
                "tabla"  => "periodo_factura_go",
                "editar" => false 
            ];

            $dataInsert = [
                'id_registro_go' => (int)$id_registro_go,
                'encabezado'     => $p['encabezado'],  // ahora sí existe
                'periodo_inicio' => $p['periodo_inicio'],
                'periodo_fin'    => $p['periodo_fin'],
            ];

            $dataBitacora = [
                'id_user' => $session->get('id_usuario'),
                'script'  => 'Agregar.php/guardarFacturaPDF'
            ];

            $response = $this->globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
        }

        return $response;

        //return false;
    }
   public function procesarPediodo(array $periodo, $id_registro_pt= null)
    {
        $session = \Config\Services::session();
        $data = array();
        $this->globals = new Mglobal();
        $response = new \stdClass();
     foreach ($periodo as $p) {
            $dataConfig = [
                "tabla"  => "periodo_factura",
                "editar" => false 
            ];

            $dataInsert = [
                'id_registro_pt' => (int)$id_registro_pt,
                'encabezado'     => $p['encabezado'],  // ahora sí existe
                'periodo_inicio' => $p['periodo_inicio'],
                'periodo_fin'    => $p['periodo_fin'],
            ];

            $dataBitacora = [
                'id_user' => $session->get('id_usuario'),
                'script'  => 'Agregar.php/guardarFacturaPDF'
            ];

            $response = $this->globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
        }

        
       return $response;
        //return false;
    }
    public function procesarPDFgo(array $archivos, $id_registro_go= null)
    {
        $session = \Config\Services::session();
        $data = array();
        $response = new \stdClass();
        $this->globals = new Mglobal();
        foreach ($archivos as $archivo) {
            if (!$archivo->isValid()) {
                continue;
            }
            $timestamp = date('Ymd_His');
            $extension = $archivo->getClientExtension();
            $originalName = pathinfo($archivo->getName(), PATHINFO_FILENAME);
            $file = $originalName . '_' . $timestamp . '.' . $extension;

            // Ruta absoluta
            $ruta_destino = FCPATH . 'assets/pdf/';
            $archivo->move($ruta_destino, $file);

            // Rutas públicas
            $ruta_absoluta = base_url('assets/pdf/' . $file);
            $ruta_relativa = 'assets/pdf/' . $file;
            $dataConfig = [
                    "tabla"=>"factura_pdf_go",
                    "editar"=>false 
                ];
             $dataInsert = [
                        'id_registro_go'           => (int)$id_registro_go,
                        'ruta_relativa'            => $ruta_relativa,
                        'ruta_absoluta'            => $ruta_absoluta,
                        'fec_reg'                  => date('Y-m-d H:i:s'),
                        'usu_reg'                  => $session->get('id_usuario')
               
                    ];
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarFacturaPDF'];
        $response = $this->globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
        }
         return $response;
    }
   public function procesarPDF(array $archivos, $id_registro_pt= null)
    {
        $session = \Config\Services::session();
        $data = array();
            $response = new \stdClass();
        $this->globals = new Mglobal();
        foreach ($archivos as $archivo) {
            if (!$archivo->isValid()) {
                continue;
            }
            $timestamp = date('Ymd_His');
            $extension = $archivo->getClientExtension();
            $originalName = pathinfo($archivo->getName(), PATHINFO_FILENAME);
            $file = $originalName . '_' . $timestamp . '.' . $extension;

            // Ruta absoluta
            $ruta_destino = FCPATH . 'assets/pdf/';
            $archivo->move($ruta_destino, $file);

            // Rutas públicas
            $ruta_absoluta = base_url('assets/pdf/' . $file);
            $ruta_relativa = 'assets/pdf/' . $file;
            $dataConfig = [
                    "tabla"=>"factura_pdf",
                    "editar"=>false 
                ];
             $dataInsert = [
                        'id_registro_pt'           => (int)$id_registro_pt,
                        'ruta_relativa'            => $ruta_relativa,
                        'ruta_absoluta'            => $ruta_absoluta,
                        'fec_reg'                  => date('Y-m-d H:i:s'),
                        'usu_reg'                  => $session->get('id_usuario')
               
                    ];
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarFacturaPDF'];
        $response = $this->globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);

          }
          return $response;
    }
   public function procesarXML(array $archivos, $id_registro_pt= null)
    {
        $session = \Config\Services::session();
        $data = array();
        $this->globals = new Mglobal();
        foreach ($archivos as $archivo) {
            if (!$archivo->isValid()) {
                continue;
            }

            $tipo = $archivo->getMimeType(); // Detecta tipo mime

            if (in_array($tipo, ['text/xml', 'application/xml'])) {
                $contenido = file_get_contents($archivo->getTempName());

              libxml_use_internal_errors(true);
                $xml = simplexml_load_string($contenido);

                if ($xml === false) {
                    return false;
                }

                $namespaces = $xml->getNamespaces(true);
                $cfdi = $xml->children($namespaces['cfdi']);

                $attrs = $xml->attributes();
                $version = (string) $attrs['Version'];
                $fecha   = (string) $attrs['Fecha'];
                $total   = (string) $attrs['Total'];
                $moneda  = (string) $attrs['Moneda'];
                // ✅ Emisor
                $emisor = $cfdi->Emisor->attributes();
                $rfcEmisor = (string) $emisor['Rfc'];
                $nombreEmisor = (string) $emisor['Nombre'];
                // ✅ Receptor
                $receptor = $cfdi->Receptor->attributes();
                $rfcReceptor = (string) $receptor['Rfc'];
                $nombreReceptor = (string) $receptor['Nombre'];

                // ✅ UUID
                $complemento = $cfdi->Complemento->children($namespaces['tfd'] ?? []);
                $uuid = (string) $complemento->TimbreFiscalDigital['UUID'];

              $dataConfig = [
                    "tabla"=>"factura",
                    "editar"=>false 
                ];
             $dataInsert = [
                        'id_registro_pt'           => (int)$id_registro_pt,
                        'version'                  => $version,
                        'fecha'                    => date('Y-m-d H:i:s', strtotime($fecha) ),
                        'total'                    => $total,
                        'moneda'                   => $moneda,
                        'emisor_rfc'               => $rfcEmisor,
                        'emisor_nombre'            => $nombreEmisor,
                        'receptor_rfc'             => $rfcReceptor,
                        'receptor_nombre'          => $nombreReceptor,
                        'uuid'                     => $uuid,
                        'fec_reg'                  => date('Y-m-d H:i:s'),
                        'usu_reg'                  => $session->get('id_usuario')
               
                    ];
          $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardarFactura'];
          $response = $this->globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);

          }
        }

       // return false;
    }
    private function cambiarStatus($id=null)
    {
        $session = \Config\Services::session();
        $this->globals = new Mglobal();    
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaTurno'];
        $dataConfig   = [
                        "tabla"=>"reserva_go",
                        "editar"=>true,
                        "idEditar" => ['id_reserva_go' => (int)$id]
                    ];
        $response = $this->globals->saveTabla(['id_estatus' => 4],$dataConfig,$dataBitacora);


    }
    public function guardaFIC()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error|Error al guardar PT";
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        $archivos = $this->request->getFiles();
        $response->idReserva = "";
    
        if($data['no_consecutivo'] == ''){
            $response->error = true;
            $response->respuesta = "Es requerido el No. Concecutivo";
            return $this->respond($response);
        }
        if(($data['direccion_responsable'])==0){
            $response->error = true;
            $response->respuesta = "Es requerido el Dirección Responsable";
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
        if(isset($data['concepto_gasto']) && empty($data['concepto_gasto'])){
            $response->error = true;
            $response->respuesta = "Es requerido el concepto gasto";
            return $this->respond($response);
        }

        if(isset($data['no_reserva']) && empty($data['no_reserva'])){
            $response->error = true;
            $response->respuesta = "Es requerido el tipo de consumo";
            return $this->respond($response);
        }
        if(isset($data['fecha_tramite']) && empty($data['fecha_tramite'])){
          $data['fecha_tramite'] = date('Y-m-d');
        }
        
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaTurno'];
     
        foreach( $data['no_reserva'] as $k => $v){
   
        $insertReserva = [
            'id_proveedor'       => (int)$data['id_proveedor'],
            'id_estatus'         => 3,
            'id_proveedor_banco' => (int)$data['id_proveedor_banco'],
            'folio'              => 'PT - ' . date('YmdHis') . substr((string)microtime(), 1, 4),
            'no_reserva'         =>($v == 'hoteles')?4327278:($v == 'restaurantes'?4327277:4327279),
            'no_convenio'        =>$data['no_convenio'],
            'total_importe'      =>$data['total_importe'],
            'observaciones'      =>'PAGOS FIC',
            'fec_reg'            =>date('Y-m-d H:i:s'),
            'usu_reg'            =>$session->get('id_usuario')
         ];
 
         $dataConfig = [
                        "tabla"=>"reserva",
                        "editar"=>false
                    ];
        $response = $this->globals->saveTabla($insertReserva,$dataConfig,$dataBitacora);
      
            if(!$response->error){
                $id_reserva = $response->idRegistro;
                $insertPresupuesto = [
                'id_reserva'         => $id_reserva,
                'id_proyecto'        => 34,
                'id_partida'         =>($insertReserva['no_reserva'] == 4327279)?10:94,
                'importe'            =>$data['importe'][$k],
                'fec_reg'            =>date('Y-m-d H:i:s'),
                'usu_reg'            =>$session->get('id_usuario') 
        
            ];
        
            $dataConfig = [
                            "tabla"=>"presupuesto",
                            "editar"=>false
                        ];
        
            $response = $this->globals->saveTabla($insertPresupuesto,$dataConfig,$dataBitacora);
            }
            if(!$response->error){
                $id_presupuesto = $response->idRegistro;
                $insertRegistro = [
                'id_reserva'               => $id_reserva,
                'id_proveedor'             => $data['id_proveedor'],
                'id_direccion_responsable' =>99,
                'no_consecutivo'           =>$data['no_consecutivo'],
                'tipo_pt'                  =>(int)$data['tipo_pt'],
                'fecha_tramite'            =>$data['fecha_tramite'], 
                'id_reponsable_solicitud'  =>(int)$data['id_reponsable_solicitud'], 
                'director_general'         =>(int)$data['director_generar'], 
                'secretario'               =>18, 
                'fic'                      =>1, 
                'fecha_gasto_inicio'       =>$data['fecha_gasto_inicio'], 
                'fecha_gasto_fin'          =>$data['fecha_gasto_fin'],
                'formato_establecido'      =>($data['formato_establecido']=='SI')?1:2,
                'documentacion_comprobatoria'=>(int)$data['documentacion_comprobatoria'],
                'evidencia_entrega'        =>(int)$data['evidencia_entrega'],
                'otros'                    =>$data['otros'],
                'comision'                 =>$data['comision'],
                'clausula_contrato'        =>$data['clausula_contrato'],
                'contrato_convenio'        =>(int)$data['contrato_convenio'],
                'concepto_pago'            =>$data['concepto_pago'],
                'fec_reg'                  =>date('Y-m-d H:i:s'),
                'usu_reg'                  =>$session->get('id_usuario')
            ];
        
            $dataConfig = [
                            "tabla"=>"registro_pt",
                            "editar"=>false
                        ];
            
            $response = $this->globals->saveTabla($insertRegistro,$dataConfig,$dataBitacora);
            }
            if(!$response->error){
                $id_registro_pt = $response->idRegistro;
              
                //$archivo = $archivos['factura_pdf_fic'][$k];
                foreach($archivos['factura_pdf_fic'] as $archivo){
                        $timestamp = date('Ymd_His');
                $extension = $archivo->getClientExtension();
                $originalName = pathinfo($archivo->getName(), PATHINFO_FILENAME);
                $file = $originalName . '_' . $timestamp . '.' . $extension;

                // Ruta absoluta
                $ruta_destino = FCPATH . 'assets/pdf/';
                $archivo->move($ruta_destino, $file);

                // Rutas públicas
                $ruta_absoluta = base_url('assets/pdf/' . $file);
                $ruta_relativa = 'assets/pdf/' . $file;
            
                $insertFacturaPdf = [
                    'id_registro_pt' =>$id_registro_pt,
                    'ruta_absoluta'  =>$ruta_absoluta,
                    'ruta_relativa'  =>$ruta_relativa,
                    'fec_reg'        =>date('Y-m-d H:i:s'),
                    'usu_reg'        =>$session->get('id_usuario') 
        
            ];
        
            $dataConfig = [
                            "tabla"=>"factura_pdf",
                            "editar"=>false
                        ];
        
            $response = $this->globals->saveTabla($insertFacturaPdf,$dataConfig,$dataBitacora);
            $response->idReserva = $id_reserva;
                
                }
   
            
            }
          
        }
     
        return $this->respond($response);
    }
    public function guardaGO()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error|Error al guardar PT";
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
        $archivos = $this->request->getFiles();

        if($data['secretario'] == 0){
            $response->error = true;
            $response->respuesta = "Es requerido el Secretario o Director";
            return $this->respond($response);
        }
        if($data['no_consecutivo'] == ''){
            $response->error = true;
            $response->respuesta = "Es requerido el No. Concecutivo";
            return $this->respond($response);
        }
        if(($data['direccion_responsable'])==0){
            $response->error = true;
            $response->respuesta = "Es requerido el Dirección Responsable";
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
        if(isset($data['concepto_gasto']) && empty($data['concepto_gasto'])){
            $response->error = true;
            $response->respuesta = "Es requerido el concepto gasto";
            return $this->respond($response);
        }

        if(isset($data['no_reserva']) && empty($data['no_reserva'])){
            $response->error = true;
            $response->respuesta = "Es requerido el no_reserva";
            return $this->respond($response);
        }
        if(isset($data['fecha_tramite']) && empty($data['fecha_tramite'])){
          $data['fecha_tramite'] = date('Y-m-d');
        }
      
    
           $dataInsert = [
                        'id_reserva_go'            => $data['id_reserva_go'],
                        'id_direccion_responsable' => $data['direccion_responsable'],
                        'fecha_tramite'            => $data['fecha_tramite'],
                        'no_consecutivo'           => (int)$data['no_consecutivo'],
                        'id_reponsable_solicitud'  => (int)$data['id_reponsable_solicitud'],
                        'director_general'         => 1,
                        'secretario'               => (int)$data['secretario'],
                        'contrato_convenio'        => ($data['contrato_convenio'] == 'NO')?2:1,
                        'formato_establecido'      => ($data['formato_establecido']=='SI')?1:2,
                        'documentacion_comprobatoria'=>$data['documentacion_comprobatoria'],
                        'poliza'                   =>($data['poliza']=='SI')?1:2,
                        'formato_conformidad'      =>($data['formato_conformidad']=='SI')?1:2,
                        'documentacion_requerida'  =>($data['documentacion_requerida']=='SI')?1:2,
                        'evidencia_entrega'        =>(int)$data['evidencia_entrega'],
                        'concepto_gasto'           =>$data['concepto_gasto'],
                        'comision'                 =>$data['comision'],
                        'no_reserva'               =>$data['no_reserva'],
                        'lugar'                    =>$data['lugar']  
                    ];
            $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaTurno'];
        if($data['editar'] == 1){
                    $dataInsert['usu_reg'] = $session->get('id_usuario');
                    $dataInsert['fec_reg'] = date('Y-m-d');
                    $dataConfig = [
                        "tabla"=>"registro_go",
                        "editar"=>false
                    ];
        }else{   
                $dataConfig = [
                    "tabla"=>"registro_go",
                    "editar"=>true,
                    'idEditar'=>['id_registro_go' => $data['id_registro_go']]
                ];
                 $dataInsert['usu_act'] = $session->get('id_usuario');
        }
      
   
        $response = $this->globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);

        if(!$response->error){
            $id_registro_go = $response->idRegistro;
            $this->cambiarStatus($data['id_reserva_go']);
 
           $archivosPdf = [];
           $periodo = [];

            foreach ($data as $key => $p) {
                if (strpos($key, 'encabezado') === 0) {
                    $index = str_replace('encabezado', '', $key); // ej. encabezado1 → 1
                    $periodo[$index]['encabezado'] = $p;
                } 
                if (strpos($key, 'periodo_inicio') === 0) {
                    $index = str_replace('periodo_inicio', '', $key); // ej. periodo1 → 1
                    $periodo[$index]['periodo_inicio'] = $p;
                } 
                 if (strpos($key, 'periodo_fin') === 0) {
                    $index = str_replace('periodo_fin', '', $key); // ej. periodo1 → 1
                    $periodo[$index]['periodo_fin'] = $p;
                } 
            }

          
            // Recorremos todas las claves de los archivos enviados
            foreach ($archivos as $key => $fileArray) {
                if (strpos($key, 'factura_pdf_') === 0) {
                    $archivosPdf = array_merge($archivosPdf, $fileArray);
                }
            }
        
           $datosPDF =$this->procesarPDFgo($archivosPdf, $id_registro_go);
           $datosP   =$this->procesarPediodoGo($periodo, $id_registro_go);
          
            if (!$datosPDF) {
                $response->errorPDF     =  true;
                $response->respuestaPDF = "PDF inválido o no se encontró.";
            }
    
        }
        return $this->respond($response);
    }
    private function cambiarStatusPT($id=null)
    {
        $session = \Config\Services::session();
        $this->globals = new Mglobal();    
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaTurno'];
        $dataConfig   = [
                        "tabla"=>"reserva",
                        "editar"=>true,
                        "idEditar" => ['id_reserva' => (int)$id]
                    ];
        $response = $this->globals->saveTabla(['id_estatus' => 4],$dataConfig,$dataBitacora);


    }
    public function guardaPT()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = "Error|Error al guardar PT";
        $this->globals = new Mglobal();
        $data = $this->request->getPost();
       $archivos = $this->request->getFiles();
   
      
        if($data['secretario'] == 0){
            $response->error = true;
            $response->respuesta = "Es requerido el Secretario o Director";
            return $this->respond($response);
        }
        if($data['no_consecutivo'] == ''){
            $response->error = true;
            $response->respuesta = "Es requerido el No. Concecutivo";
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
        //var_dump($data);
        //die();
           
           $dataInsert = [
                        'id_reserva'               => (int)$data['id_reserva'],
                        'id_direccion_responsable' => $data['direccion_responsable'],
                        'tipo_pt'                  => $data['tipo_pt'],
                        'no_consecutivo'           => $data['no_consecutivo'],
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
                    $dataInsert['usu_reg'] = $session->get('id_usuario');
                    $dataInsert['fec_reg'] = date('Y-m-d H:i:s');
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
                 $dataInsert['usu_act'] = $session->get('id_usuario');
        }
      
   
        $response = $this->globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
      
        if(!$response->error){
            $id_registro_pt = $response->idRegistro;
            $archivosXml = [];
            $archivosPdf = [];
            $periodo = [];
            $response->idRegistro = $response->idRegistro;
             $this->cambiarStatusPT($data['id_reserva']);
            foreach ($data as $key => $p) {
                if (strpos($key, 'encabezado') === 0) {
                    $index = str_replace('encabezado', '', $key); // ej. encabezado1 → 1
                    $periodo[$index]['encabezado'] = $p;
                } 
                 if (strpos($key, 'periodo_inicio') === 0) {
                    $index = str_replace('periodo_inicio', '', $key); // ej. periodo1 → 1
                    $periodo[$index]['periodo_inicio'] = $p;
                } 
                 if (strpos($key, 'periodo_fin') === 0) {
                    $index = str_replace('periodo_fin', '', $key); // ej. periodo1 → 1
                    $periodo[$index]['periodo_fin'] = $p;
                }  
            }
          
            // Recorremos todas las claves de los archivos enviados
            foreach ($archivos as $key => $fileArray) {
                if (strpos($key, 'factura_xml_') === 0) {
                    $archivosXml = array_merge($archivosXml, $fileArray);
                } elseif (strpos($key, 'factura_pdf_') === 0) {
                    $archivosPdf = array_merge($archivosPdf, $fileArray);
                }
            }
        
       
           $datosXML = $this->procesarXML($archivosXml, $id_registro_pt);
           $datosPDF =$this->procesarPDF($archivosPdf, $id_registro_pt);
           $datosP =$this->procesarPediodo($periodo, $id_registro_pt);
          

            if (!$datosXML) {
                $response->errorXML     =  true;
                $response->respuestaXML = "XML inválido o no se encontró.";
            }
            if (!$datosPDF) {
                $response->errorPDF     =  true;
                $response->respuestaPDF = "PDF inválido o no se encontró.";
            }
    
        }
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
        if(empty($data['fec_nac']) ){
            throw new Exception("El campo fecha de nacimiento es requerido");
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
            'id_jefe_inmediato'     => (int)$data['id_jefe_inmediato'],
            'id_tipo_empleado'      => (int)$data['id_tipo_empleado'],
            'id_puesto'             => (int)$data['id_puesto'],
            'id_perfil'             => (int)$data['id_perfil'],
            'usuario'               => $data['usuario'],                
            'nombre'                => $data['nombre'],  
            'primer_apellido'       => $data['primer_apellido'],           
            'segundo_apellido'      => $data['segundo_apellido'],
            'correo'                => $data['correo'],           
            'rfc'                   => $data['rfc'],             
            'id_area'               => (int)$data['id_area'],               
            'fec_reg'               => $hoy 
        ];

        
        $fecha_nacimiento = $data['fec_nac'];
   
        // Verificar si la fecha es válida
        if (!empty($fecha_nacimiento)) {
            // Convertir a formato YYYY-MM-DD si es necesario
            $fecha_formateada = date('Y-m-d H:i:s', strtotime($fecha_nacimiento));
            
            $dataInsert['fec_nac'] = $fecha_formateada;
        } else {
            $dataInsert['fec_nac'] = null;
        }
      

        if(isset($data['contrasenia']) && !empty($data['contrasenia'])){
          $dataInsert['contrasenia'] = md5($data['contrasenia']); 
        }     
        if(isset($data['no_empleado']) && !empty($data['no_empleado'])){
          $dataInsert['no_empleado'] = $data['no_empleado']; 
        }  
        if(isset($data['nivel']) && !empty($data['nivel'])){
          $dataInsert['nivel'] = $data['nivel']; 
        }  
        if(isset($data['extencion']) && !empty($data['extencion'])){
          $dataInsert['extencion'] = $data['extencion']; 
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
    public function Directorio()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = array();
        $tabla = array('tabla' => 'vw_usuario', 'where' => ['visible' => 1], 'orderBy' => 'nombre_completo ASC');
        $usuario = $globals->getTabla($tabla);
        $data['scripts']  = array('inicio');
        $data['usuario'] = isset($usuario->data) && !empty($usuario->data) ? $usuario->data : [];
        $data['contentView'] = 'personal/vDirectorio';                             
        $this->_renderView($data);
    }
    function validarCampo($valor, $nombreCampo) {
        // $pattern = "/^([a-zA-Z 0-9]+)$/";
        $pattern = "/^([a-zA-ZáéíóúüñÁÉÍÓÚÜÑ 0-9]+)$/";
        
        if (!preg_match($pattern, $valor)) {
            throw new Exception("Error en el campo '$nombreCampo': Por favor, utilice únicamente caracteres alfanuméricos (letras y números). Gracias.");
        }
    
        return $valor;
    }
    public function Documentos()
    {  
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data['scripts'] = array('inicio');
        $data['contentView'] = 'personal/vDocumentos';                
        $this->_renderView($data);
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
            $idTipoEmpleado = $Mglobal->getTabla([
                        'tabla' => 'vw_usuario',
                        'where' => [
                            'visible' => 1,
                             'id_usuario' => $session->get('id_usuario')
                        ],
               
                    ])->data[0]->id_tipo_empleado;

            
            $data = [];
            if (isset($mes) && !empty($mes)) { // RH
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
            }else{ // POBLACION
                 $agenda = $Mglobal->getTabla([
                        'tabla' => 'asistencia',
                        'where' => [
                            'id_usuario' => $session->get('id_usuario'),
                            'visible' => 1
                             
                        ],
                    ]);
              // Obtenemos incidencias
                    $incidencia = $Mglobal->getTabla([
                        'tabla' => 'incidencia',
                        'where' => [
                            'id_usuario' => $session->get('id_usuario'),
                            'visible'    => 1
                        ]
                    ]);

                    $data['incidencia'] = [];

                    if (isset($incidencia->data) && !empty($incidencia->data)) {
                        foreach ($incidencia->data as $item) {
                            $fecha = $item->fecha;

                            // Detectar si es rango tipo "08/18/2025 - 08/22/2025"
                            if (preg_match('/\d{2}\/\d{2}\/\d{4}\s*-\s*\d{2}\/\d{2}\/\d{4}/', $fecha)) {
                                list($iniStr, $finStr) = array_map('trim', explode('-', $fecha));

                                $ini = DateTime::createFromFormat('m/d/Y', $iniStr);
                                $fin = DateTime::createFromFormat('m/d/Y', $finStr);

                                if ($ini && $fin) {
                                    // FullCalendar → end es exclusivo: sumamos 1 día
                                    $fin->modify('+1 day');

                                    $item->start = $ini->format('Y-m-d');
                                    $item->end   = $fin->format('Y-m-d');
                                    $item->tipo  = 'semana';
                                }
                            } else {
                                // Fecha simple: intentamos varios formatos
                                $d = DateTime::createFromFormat('Y-m-d', $fecha) ?:
                                    DateTime::createFromFormat('d/m/Y', $fecha) ?:
                                    DateTime::createFromFormat('m/d/Y', $fecha);

                                if ($d) {
                                    $fin = clone $d;
                                    $fin->modify('+1 day');

                                    $item->start = $d->format('Y-m-d');
                                    $item->end   = $fin->format('Y-m-d');
                                    $item->tipo  = 'dia';
                                }
                            }

                            $data['incidencia'][] = $item;
                        }
                    }

                    
            }

           //var_dump( $data['incidencia']  );
           //die();
            $cat_incidencia = $Mglobal->getTabla(['tabla' => 'cat_incidencia', 'where' => ['visible' => 1]]);
        
            $mes  = ($mes)? $mes: date('m');
            $data['anio'] = date('Y');
            $data['idTipoEmpleado'] = $idTipoEmpleado;
            $asistencia = (isset($agenda->data) && !empty($agenda->data))?$agenda->data:[];
            $data['asistencia'] = $asistencia;
            $data['cat_incidencia'] = $cat_incidencia->data;
           // $data['incidencia'] = (isset($incidencia->data) && !empty($incidencia->data))?$incidencia->data:[];
        
            $data['mes'] = $mes;
            $data['calendarStatic'] = $calendarStatic;
            $data['scripts'] = array('agregar', 'inicio');
            $data['contentView'] = 'secciones/vAsistencia';                
            $this->_renderView($data);
        }
        public function deleteAlba()
        {
        $session  = \Config\Services::session();
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error al insertar en la tabla';
        $globals  = new Mglobal;
        $id_alba  = $this->request->getPost('id_alba');
        $dataBitacora = ['id_user' =>$session->get('id_usuario'), 'script' => 'Agregar.php/guardaViatico'];
        $dataConfig = [
            "tabla"=>"lista_alba",
            "editar"=>true,
            "idEditar" => ['id_alba' => $id_alba]
        ];
        $result = $globals->saveTabla(['visible' => 0],$dataConfig,$dataBitacora);
        if(!$result->error){
            $response->error     = false;
            $response->respuesta = $result->respuesta;
        }
        return $this->respond($response);

        }
       public function albaAlta()
       {
        $session  = \Config\Services::session();
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error al insertar en la tabla';
        $globals  = new Mglobal;
        $data     = $this->request->getPost();
        $file = $this->request->getFile('foto');
        $file2 = $this->request->getFile('protocolo');
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

            $extension2 = $file2->getClientExtension();
            $originalName2 = pathinfo($file2->getName(), PATHINFO_FILENAME);
            $archivo2 = $originalName2 . '_' . $timestamp . '.' . $extension2;

            // Ruta absoluta
            $ruta_destino = FCPATH . 'assets/images/fotos/alba/';
            $file->move($ruta_destino, $archivo);
            $ruta_destino2 = FCPATH . 'assets/pdf/alba/';
            $file2->move($ruta_destino2, $archivo2);

            // Rutas públicas
            //$ruta_absoluta = base_url('aassets/images/fotos/alba/' . $archivo);
            $ruta_relativa  = 'assets/images/fotos/alba/' . $archivo;
            $ruta_relativa2 = 'assets/pdf/alba/' . $archivo2;
        }
        if(empty($data['nombre'])){
            $response->respuesta = 'El nombre es requerido';
            return $this->respond($response);
        }
        if(empty($data['primer_apellido'])){
            $response->respuesta = 'El primer_apellido es requerido';
            return $this->respond($response);
        }
        if(empty($data['fecha_nacimiento'])){
            $response->respuesta = 'El fecha_nacimiento es requerido';
            return $this->respond($response);
        }
      //  if($this->validarViativos()); return false
        $dataInsert = [
        'nombre'           =>$data['nombre'],
        'primer_apellido'  =>$data['primer_apellido'],
        'segundo_apellido' =>$data['segundo_apellido'],
        'nacionalidad'     =>$data['nacionalidad'],
        'edad'             =>$data['edad'],
        'foto'             =>$ruta_relativa,
        'protocolo'        =>$ruta_relativa2,
        'id_sexo'          =>(int)$data['id_sexo'],
        'fecha_nacimiento' =>date('Y-m-d', strtotime($data['fecha_nacimiento'])),
        'usu_reg'          =>(int)$session->get('id_usuario'),
        'fec_reg'          =>date('Y-m-d'),
        ];
     
        if($data['editar'] == 1){
             $dataConfig = [
            "tabla"=>"lista_alba",
            "editar"=>true,
            "idEditar"=>['id_alba' => $data['id_alba']]
        ];
        }else{
           $dataConfig = [
            "tabla"=>"lista_alba",
            "editar"=>false
        ];
        }
     
        $dataBitacora = ['id_user' =>$session->get('id_usuario'), 'script' => 'Agregar.php/guardaViatico'];
        $result = $globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
        if(!$result->error){
            $response->error     = false;
            $response->respuesta = $result->respuesta;
        }
        return $this->respond($response);

    }
    public function getAlba()
    {
        $session  = \Config\Services::session();
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error al traer la tabla';
        $globals  = new Mglobal;
        $id_alba     = $this->request->getPost('id_alba');
        $result = $globals->getTabla(["tabla"=>"lista_alba", 'where' => ['visible' => 1, 'id_alba' => $id_alba ]]);
        //var_dump( $result);
       // die();
        if(!$result->error){
            $response->error     = false;
            $response->respuesta = $result->respuesta;
            $response->data      = $result->data[0];
        }
        return $this->respond($response);
    }
    public function formViatico()
    {
        $session  = \Config\Services::session();
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error al insertar en la tabla';
        $globals  = new Mglobal;
        $data     = $this->request->getPost();
      //  if($this->validarViativos()); return false
        $dataInsert = [
        'ejercicio'              =>$data['ejercicio'],
        'fecha_inicio'           =>date('Y-m-d', strtotime($data['fecha_inicio'])),
        'fecha_termino'          =>date('Y-m-d', strtotime($data['fecha_termino'])),
        'denominacion_puesto'    =>$data['denominacion_puesto'],
        'denomicacion_carga'     =>$data['denomicacion_carga'],
        'clave_nivel'            =>$data['clave_nivel'],
        'id_usuario'             =>(int)$session->get('id_usuario'),
        'tipo_integrante'        =>(int)$data['tipo_integrante'],
        'tipo_viaje'             =>(int)$data['tipo_viaje'],
        'tipo_gasto'             =>(int)$data['tipo_gasto'],
        'no_personas'            =>(int)$data['no_personas'],
   /*     'importe_ejercicio'      =>$data['importe_ejercicio'],
        'fec_actualizacion'      =>$data['fec_actualizacion'],
        'pais_origen'            =>(int)$data['pais_origen'],
        'estado_origen'          =>(int)$data['estado_origen'],
        'ciudad_origen'          =>(int)$data['ciudad_origen'],
        'pais_destino'           =>(int)$data['pais_destino'],
        'estado_destino'         =>(int)$data['estado_destino'],
        'ciudad_destino'         =>(int)$data['ciudad_destino'],
        'motivo_encargo'         =>$data['motivo_encargo'],
        'fec_salida'             =>date('Y-m-d', strtotime($data['fec_salida'])),
        'fec_regreso'            =>date('Y-m-d', strtotime($data['fec_regreso'])),
        'importe_total'          =>$data['importe_total'],
        'fec_entraga_informa'    =>$data['fec_entraga_informa'],
        'hipervinculo_informe'   =>$data['hipervinculo_informe'],
        'hipervinculo_factura'   =>$data['hipervinculo_factura'],
        'hipervinculo_normativa' =>$data['hipervinculo_normativa'],
        'area_responsabe'        =>$data['area_responsabe'],
        'nota'                   =>$data['nota'], */
        ];
     
 
        $dataConfig = [
            "tabla"=>"juridico_viaticos",
            "editar"=>false
        ];
        $dataBitacora = ['id_user' =>$session->get('id_usuario'), 'script' => 'Agregar.php/guardaViatico'];
        $result = $globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);
        if(!$result->error){
            $response->error     = false;
            $response->respuesta = $result->respuesta;
        }
        return $this->respond($response);

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
    public function ReporteUsuario($fec_inicio = null, $fec_fin = null, $usuario = null)
    {
        $session     = \Config\Services::session();
        $response    = new stdClass();
        $response->error = true;
        $response->respuesta = 'No existen incidencia del usuario' ;
        $globals     = new Mglobal;
        $fechaInicio = date('Y-m-d', strtotime($fec_inicio));
        $fechaFin    = date('Y-m-d', strtotime($fec_fin));
  
        $data = array();
        $incidencia = $globals->getTabla([
            'tabla' => 'vw_incidenica',
            'where' => ['id_usuario' => $usuario, 'id_estatus' => 3],
            'whereBetween' => [['fecha_inicio', $fechaInicio, $fechaFin]]
        ]);
        
        $data['incidencia'] = (isset($incidencia->data) && !empty($incidencia->data))?$incidencia->data:'';
        $data['usuario'] = (isset($incidencia->data) && !empty($incidencia->data))?$incidencia->data[0]:'';

        $tempQrPath = FCPATH . 'assets/images/qr_final.png';
        $folio = 'GTO - ' . date('YmdHis') . substr((string)microtime(), 1, 4);
        // Generar el QR
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data(base_url().'index.php/Principal/reporteIncidenciaUsuario/' .$fechaInicio.'/'.$fechaFin.'/'.$usuario.'/'.$folio)
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
         $data['dataImagen'] =  $dataImagen;
         $data['folio'] =  $folio;

        $doc = 'assets/pdf/plantillas/asistencia.pdf';
        $formato ='personal/vFormatoAsistenciaUser.php';
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
        $tplId = $mpdf->ImportPage(1);

        // Página 1
        $mpdf->AddPage();
        $mpdf->UseTemplate($tplId);
        $mpdf->WriteHTML($html);

        // Footer en todas las páginas
        $mpdf->SetHTMLFooter('
            <div style="text-align: right; font-size: 10px;">
                Página {PAGENO} de {nbpg}
            </div>
        ');


        $mpdf->Output('Formato_pt.pdf', 'I');
        exit();

    }
  
   
    public function validarReporte()
    {
        $session     = \Config\Services::session();
        $response    = new stdClass();
        $response->error = true;
        $response->respuesta = 'EL Usuario <strong style="color:red"> no tiene incidencia(s)</strong> en esos periodos' ;
        $globals     = new Mglobal;
        $periodoInicio = $this->request->getPost('periodoInicio');
        $periodoFin = $this->request->getPost('periodoFin');
        $id_usuario = $this->request->getPost('usuario');
        $fec_ini = date('Y-m-d', strtotime($periodoInicio));
        $fec_fin = date('Y-m-d', strtotime($periodoFin));
        $tabla = [
                 'tabla' => 'incidencia', 
                 'where' => ['id_usuario' => $id_usuario],
                 'whereBetween' => [['fecha_inicio', $fec_ini, $fec_fin]]
                ];
        $incidencias = $globals->getTabla($tabla);                  
        if(!empty($incidencias->data)){
          $response->error = false;
          $response->respuesta = 'Si existen incidencia del usuario' ;
        }

     return $this->respond($response);

    }
    public function ListaAlba()
    {
        $session = \Config\Services::session();
        $data        = array();
        $globals = new Mglobal;
        $usuario = $globals->getTabla(['tabla' => 'lista_alba', 'where' => ['visible' => 1]]);
        $data['usuario'] = $usuario->data;
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vListaAlba';                
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
            'proyector'   => $data['proyecto'],
            'tipo_reunion'=> $data['tipo_reunion'],
            'id_usuario'  => $session->get('id_usuario'),
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
    public function getIncidencia()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal;
        $id_incidencia = $this->request->getPost('id_incidencia');
        if( in_array($session->get('id_perfil'). [1,2 ])){
           $inicencias = $globals->getTabla(['tabla' => 'incidencia', 'where' => ['visible' => 1]]);
        }else{
          $inicencias = $globals->getTabla(['tabla' => 'incidencia', 'where' => ['visible' => 1, 'id_usuario' => $session->get('id_usuario')]]);
        }
        if(!$inicencias->error){
            $response->error = false;
            $response->data = $inicencias->data; 

        }
        return $this->respond($response);
    }
    public function enviarCorreo( $correo1)
    {
        // Inicializar servicios y objetos
        $email = Services::email();
        $session = Services::session();
        $response = new \stdClass();
        

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
        
        $correo2 = $usuario->data[0]->correo;

        // Configurar y enviar correo
        $email->setFrom($correo2 , 'SUSI');
        //$email->setTo("palafox.marin31@gmail.com");
        $email->setTo( $correo1);
        $email->setSubject('JUSTIFICACION DE INCIDENCIA');
       $email->setMessage('
                    <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                        <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                            <div style="background-color: #004080; padding: 20px; text-align: center;">
                                <img src="' . base_url('assets/images/logo-sm.png') . '" alt="Logo" style="height: 60px;">
                            </div>
                            <div style="padding: 30px; color: #333;">
                                <h1 style="color: #004080;">¡El estatus de su incidencia cambio!</h1>
                                <p style="font-size: 16px;">Favor de <strong> Ingresar a SUSI</strong>.</p>
                                <p style="font-size: 15px;"><a href="'.base_url().'index.php/Agregar/Asistencia"><strong>Seguimiento Incidencia</strong></a></p>
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
    public function formIncidencia()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal;
        $data = $this->request->getPost();

        // Convierte la fecha a timestamp
        $fecha = $data['fecha_inicio_asistencia'];
        $diaSemana = date('N', strtotime($fecha));
        
        if(empty($data['tipo_incidencia'] ) || $data['tipo_incidencia']  == 0){
            $response->error = true;
            $response->respuesta = 'Es requerido el tipo de incidencia';
            return $this->respond($response);

        }
        // Validar que NO sea lunes (1) ni viernes (5)
        if($data['tipo_incidencia'] == 9){
            if ($diaSemana == 1 || $diaSemana == 5) {
                 $response->error = true;
                 $response->respuesta = 'La fecha no puede ser lunes ni viernes';
                 return $this->respond($response);
            }
        }
       $dataBitacora = ['id_user' => $session->id_usuario, 'script' => 'Agregar.php/edotaIncidencia'];
        $dataInsert = [
            "cat_id_incidencia"=> $data['tipo_incidencia'],
            "fecha"            => $data['fecha_inicio_asistencia'], 
            "fecha_inicio"     => $data['fecha_inicio_asistencia'], 
            "fecha_fin"        => $data['fecha_fin_asistencia'], 
            "hora_inicio"      => $data['hora_inicio_asistencia'], 
            "hora_fin"         => $data['hora_fin_asistencia'], 
            "id_estatus"       => 1, 
            "usu_act"          => $session->get('id_usuario'), 

        ];
        $dataConfig = [
             "tabla"    => "incidencia",
             "editar"   => true,
             "idEditar" => ['id_incidencia' => $data['id_incidencia'] ],
        ];
      
       $response = $globals->saveTabla($dataInsert,$dataConfig,$dataBitacora);  
       

       return $this->respond($response);

       
    }

    public function aceptarIncidencia()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal;
        $id_incidencia = $this->request->getPost('id_incidencia');
        $id_aceptar    = $this->request->getPost('id_aceptar');
        $id_usuario    = $this->request->getPost('id_usuario');
        $observaciones = $this->request->getPost('observaciones');
        $dataBitacora = ['id_user' =>  $session->get('id_usuario'), 'script' => 'Agregar.php/guardaIncidencia'];
        //optener el correo el empleado de la incidenci
        $correo = $globals->getTabla(['tabla' => 'vw_usuario', 'where'=>['id_usuario' =>$id_usuario  ]])->data[0]->correo;
      
        $dataConfig = [
                "tabla"=>"incidencia",
                "editar"=>true,
                "idEditar"=>['id_incidencia'=>$id_incidencia]
            ];
            $Insert = [
                'id_estatus'   => $id_aceptar,
                'observaciones'=> $observaciones,
                'usu_act'      => $session->get('id_usuario')                    
            ];
           $result = $globals->saveTabla($Insert,$dataConfig,$dataBitacora);
           if(!$result->error){
              $response->error = false;
              $response->respuesta = $result->respuesta;
              $res = $this->enviarCorreo($correo);
         
           }
        
        return $this->respond($response);
    }
    public function eliminarIncidencia()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal;
        $id_incidencia = $this->request->getPost('id_incidencia');
        $dataBitacora = ['id_user' =>  $session->get('id_usuario'), 'script' => 'Agregar.php/EliminarIncidencia'];
        $dataConfig = [
                "tabla"=>"incidencia",
                "editar"=>true,
                "idEditar"=>['id_incidencia'=>$id_incidencia]
            ];
            $Insert = [
                'visible'  => 0,
                'usu_act'   => $session->get('id_usuario')                    
            ];
        $result = $globals->saveTabla($Insert,$dataConfig,$dataBitacora);
           if(!$result->error){
              $response->error = false;
              $response->respuesta = $result->respuesta;
           }
        
        return $this->respond($response);
    }
    public function detalleIncidencia()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal;
        $id_incidencia = $this->request->getPost('id_incidencia');
        $inicencias = $globals->getTabla(['tabla' => 'vw_incidenica', 'where' => ['visible' => 1, 'id_incidencia' => $id_incidencia]]);
       
        if(!$inicencias->error){
            $response->error = false;
            $response->data = $inicencias->data[0]; 

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