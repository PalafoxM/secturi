<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Curps;
use App\Libraries\Fechas;
use App\Libraries\Funciones;
use App\Models\Mglobal;
use DateTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use stdClass;
use CodeIgniter\API\ResponseTrait;
require_once FCPATH . '/mpdf/autoload.php';

class Inicio extends BaseController
{

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
        if ($session->get('logueado') != 1) {
            header('Location:' . base_url() . 'index.php/Login/cerrar?inactividad=1');
            die();
        }
    }

    private function _renderView($data = array())
    {
        $session = \Config\Services::session();
        $Mglobal = new Mglobal;
        //die(var_dump($data["dscCursos"]));
        $data = array_merge($this->defaultData, $data);
        echo view($data['layout'], $data);

    }

    private function resolveFacturaPdfPath(?string $storedPath, string $prefix = 'factura_pdf_')
    {
        if (empty($storedPath)) {
            return null;
        }

        if (strpos($storedPath, 'FACTURAS/') === 0) {
            $s3 = new \App\Libraries\S3Service();
            return $s3->downloadToTempFile($storedPath, $prefix);
        }

        $fullPath = FCPATH . ltrim($storedPath, '/\\');
        return file_exists($fullPath) ? $fullPath : null;
    }

    private function getTablaData(Mglobal $globals, array $config): array
    {
        $result = $globals->getTabla($config);
        return (isset($result->data) && is_array($result->data)) ? $result->data : [];
    }

    private function getTimestampFromRow($row): ?int
    {
        $fields = [
            'created_at',
            'updated_at',
            'fecha_registro',
            'fec_registro',
            'fecha_creacion',
            'fec_creacion',
            'fecha',
            'fec_alta',
        ];

        foreach ($fields as $field) {
            if (!empty($row->$field)) {
                $timestamp = strtotime((string) $row->$field);
                if ($timestamp !== false) {
                    return $timestamp;
                }
            }
        }

        return null;
    }

    private function buildStatusSummary(array $rows, array $statusMap): array
    {
        $summary = [];

        foreach ($rows as $row) {
            $statusId = $row->id_estatus ?? $row->estatus ?? null;
            if ($statusId === null || $statusId === '') {
                $label = 'Sin estatus';
            } else {
                $label = $statusMap[(string) $statusId] ?? ('Estatus ' . $statusId);
            }

            if (!isset($summary[$label])) {
                $summary[$label] = 0;
            }

            $summary[$label]++;
        }

        return $summary;
    }

    private function buildAdminDashboardData(Mglobal $globals): array
    {
        $statusRows = $this->getTablaData($globals, [
            'tabla' => 'cat_estatus',
            'where' => ['visible' => 1],
        ]);

        $statusMap = [];
        foreach ($statusRows as $statusRow) {
            $statusId = $statusRow->id_estatus ?? $statusRow->id_cat_estatus ?? null;
            $statusLabel = $statusRow->dsc_estatus ?? $statusRow->estatus ?? $statusRow->nombre ?? null;
            if ($statusId !== null && $statusLabel !== null) {
                $statusMap[(string) $statusId] = $statusLabel;
            }
        }

        $moduleConfigs = [
            'Pagos PT' => [
                'tabla' => 'registro_pt',
                'id_field' => 'id_registro_pt',
                'title_fields' => ['folio', 'concepto', 'descripcion'],
            ],
            'Pagos GO' => [
                'tabla' => 'registro_go',
                'id_field' => 'id_registro_go',
                'title_fields' => ['folio', 'concepto', 'descripcion'],
            ],
            'Fichas Técnicas' => [
                'tabla' => 'ficha_tecnica',
                'id_field' => 'id_ficha_tecnica',
                'title_fields' => ['folio', 'nombre_evento', 'asunto'],
            ],
            'Solicitudes GRC' => [
                'tabla' => 'solicitud_grc',
                'id_field' => 'id_solicitud_grc',
                'title_fields' => ['folio', 'asunto', 'descripcion'],
            ],
            'Convenios' => [
                'tabla' => 'solicitud_convenio',
                'id_field' => 'id_solicitud_convenio',
                'title_fields' => ['folio', 'asunto', 'descripcion'],
            ],
            'Contratos' => [
                'tabla' => 'solicitud_contrato',
                'id_field' => 'id_solicitud_contrato',
                'title_fields' => ['folio', 'asunto', 'descripcion'],
            ],
        ];

        $monthlyRows = [];
        $dashboardModules = [];
        $recentItems = [];
        $currentYear = (int) date('Y');

        foreach ($moduleConfigs as $label => $config) {
            $rows = $this->getTablaData($globals, [
                'tabla' => $config['tabla'],
                'where' => ['visible' => 1],
            ]);

            $dashboardModules[] = [
                'module' => $label,
                'total' => count($rows),
                'status' => $this->buildStatusSummary($rows, $statusMap),
            ];

            $monthlyRows[$label] = array_fill(0, 12, 0);

            foreach ($rows as $row) {
                $timestamp = $this->getTimestampFromRow($row);
                if ($timestamp !== null && (int) date('Y', $timestamp) === $currentYear) {
                    $monthIndex = (int) date('n', $timestamp) - 1;
                    $monthlyRows[$label][$monthIndex]++;
                }

                $title = null;
                foreach ($config['title_fields'] as $field) {
                    if (!empty($row->$field)) {
                        $title = (string) $row->$field;
                        break;
                    }
                }

                if ($title === null) {
                    $idField = $config['id_field'];
                    $title = 'Registro #' . ($row->$idField ?? 's/n');
                }

                $statusId = $row->id_estatus ?? $row->estatus ?? null;
                $statusLabel = ($statusId !== null && $statusId !== '')
                    ? ($statusMap[(string) $statusId] ?? ('Estatus ' . $statusId))
                    : 'Sin estatus';

                $recentItems[] = [
                    'module' => $label,
                    'title' => $title,
                    'status' => $statusLabel,
                    'timestamp' => $timestamp ?? 0,
                    'date' => $timestamp ? date('d/m/Y H:i', $timestamp) : 'Sin fecha',
                ];
            }
        }

        $ticketRows = $this->getTablaData($globals, [
            'tabla' => 'tiket',
            'where' => ['visible' => 1],
        ]);

        $ticketMap = [
            '0' => 'Nuevos',
            '1' => 'Concluidos',
            '2' => 'En proceso',
        ];
        $dashboardTickets = ['Nuevos' => 0, 'En proceso' => 0, 'Concluidos' => 0];
        foreach ($ticketRows as $ticket) {
            $key = (string) ($ticket->estatus ?? $ticket->id_estatus ?? '');
            $label = $ticketMap[$key] ?? 'Sin clasificar';
            if (!isset($dashboardTickets[$label])) {
                $dashboardTickets[$label] = 0;
            }
            $dashboardTickets[$label]++;

            $timestamp = $this->getTimestampFromRow($ticket);
            $recentItems[] = [
                'module' => 'Tickets',
                'title' => !empty($ticket->asunto) ? (string) $ticket->asunto : 'Ticket #' . ($ticket->id_tiket ?? 's/n'),
                'status' => $label,
                'timestamp' => $timestamp ?? 0,
                'date' => $timestamp ? date('d/m/Y H:i', $timestamp) : 'Sin fecha',
            ];
        }

        usort($recentItems, static function ($left, $right) {
            return ($right['timestamp'] ?? 0) <=> ($left['timestamp'] ?? 0);
        });
        $recentItems = array_slice($recentItems, 0, 8);
        foreach ($recentItems as &$item) {
            unset($item['timestamp']);
        }
        unset($item);

        $usuarios = $this->getTablaData($globals, [
            'tabla' => 'usuario',
            'where' => ['visible' => 1],
        ]);

        $activeUsers = 0;
        $inactiveUsers = 0;
        foreach ($usuarios as $usuario) {
            $isActive = true;
            if (isset($usuario->activo)) {
                $isActive = (int) $usuario->activo === 1;
            } elseif (isset($usuario->estatus)) {
                $isActive = in_array((int) $usuario->estatus, [1, 2], true);
            } elseif (isset($usuario->id_estatus)) {
                $isActive = in_array((int) $usuario->id_estatus, [1, 2], true);
            }

            if ($isActive) {
                $activeUsers++;
            } else {
                $inactiveUsers++;
            }
        }

        $dashboardCards = [
            [
                'title' => 'Usuarios visibles',
                'value' => count($usuarios),
                'subtitle' => 'Base activa del sistema',
                'icon' => 'users',
                'color' => 'primary',
            ],
            [
                'title' => 'Módulos monitoreados',
                'value' => count($moduleConfigs),
                'subtitle' => 'Operación conectada al dashboard',
                'icon' => 'grid',
                'color' => 'success',
            ],
            [
                'title' => 'Tickets abiertos',
                'value' => ($dashboardTickets['Nuevos'] ?? 0) + ($dashboardTickets['En proceso'] ?? 0),
                'subtitle' => 'Seguimiento pendiente',
                'icon' => 'inbox',
                'color' => 'warning',
            ],
            [
                'title' => 'Movimientos recientes',
                'value' => count($recentItems),
                'subtitle' => 'Últimos registros detectados',
                'icon' => 'activity',
                'color' => 'info',
            ],
        ];

        return [
            'dashboardCards' => $dashboardCards,
            'dashboardModules' => $dashboardModules,
            'dashboardTickets' => $dashboardTickets,
            'dashboardUsers' => [
                'total' => count($usuarios),
                'active' => $activeUsers,
                'inactive' => $inactiveUsers,
            ],
            'dashboardRecentItems' => $recentItems,
            'dashboardMonthLabels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            'dashboardMonthlyRows' => $monthlyRows,
        ];
    }

    public function index()
    {
        $session = \Config\Services::session();
        $data = array();
        $globas = new Mglobal;
        $vista = null;
        $votoHombre = false;
        $votoMujer = false;
        $data['eventos'] = "";
        $votoH = $globas->getTabla(['tabla' => 'vw_honestidad', 'where' => ['visible' => 1, 'usu_reg' => $session->id_usuario, 'id_sexo' => 1]]);
        $votoM = $globas->getTabla(['tabla' => 'vw_honestidad', 'where' => ['visible' => 1, 'usu_reg' => $session->id_usuario, 'id_sexo' => 2]]);
        if (isset($votoH->data) && !empty($votoH->data)) {
            $votoHombre = true;
        }
        if (isset($votoM->data) && !empty($votoM->data)) {
            $votoMujer = true;
        }
        if (in_array($session->get('id_perfil'), [1, 2, 3])) {
            $vista = 'secciones/vInicio';
            $data = array_merge($data, $this->buildAdminDashboardData($globas));
        }
        else {
            $vista = 'personal/vInicio';
            $data['datos'] = $globas->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->id_usuario]])->data[0];
            $data['lista_alba'] = $globas->getTabla(['tabla' => 'alba', 'where' => ['visible' => 1]])->data;

        }
        $mes_actual = date('m');
        $cumple = $globas->getTabla([
            'tabla' => 'vw_usuario',
            'where' => ['visible' => 1],
            'whereMonth' => [['fec_nac', $mes_actual]]
        ])->data;
        $personal = [];
        if (isset($cumple) && !empty($cumple)) {
            foreach ($cumple as $c) {
                $fecha_nacimiento = new DateTime($c->fec_nac);
                $hoy = new DateTime();
                $edad = $hoy->diff($fecha_nacimiento)->y;
                $personal[] = [
                    'nombre_completo' => $c->nombre,
                    'id_edad' => $c->id_edad,
                    'id_nivel' => $c->id_nivel,
                    'id_sexo' => $c->sexo,
                    'id_fec_nac' => $c->id_fec_nac,
                    'id_usuario' => $c->id_usuario,
                    'dsc_area' => $c->dsc_area,
                    'edad' => $edad,
                    'ruta_foto_relativa' => $c->ruta_foto_relativa,
                    'dia' => date('d', strtotime($c->fec_nac))
                ];

            }
        }
        $actividad = $globas->getTabla(['tabla' => 'vw_actividad', 'where' => ['visible' => 1, "id_usuario" => $session->id_usuario]])->data;
        $configuracion = $globas->getTabla(['tabla' => 'vw_configuracion', 'where' => ['visible' => 1, "id_usuario" => $session->id_usuario]])->data;

        $tiket = $globas->getTabla(['tabla' => 'vw_tiket', 'where' => ['visible' => 1, "id_usuario" => $session->id_usuario]])->data;
        // die( var_dump( $tiket) );
        $eventos = $globas->getTabla(['tabla' => 'eventos', 'where' => ['visible' => 1], 'whereMonth' => [['fecha', $mes_actual]]])->data;
        $tiketNuevo = [];
        $tiketProceso = [];
        $tiketConcluido = [];

        foreach ($tiket as $t) {
            if ($t->estatus == 0) {
                $tiketNuevo[] = $t->estatus;
            }
            if ($t->estatus == 1) {
                $tiketConcluido[] = $t->estatus;
            }
            if ($t->estatus == 2) {
                $tiketProceso[] = $t->estatus;
            }

        }
        foreach ($eventos as $e) {

            if (date('d-m') == date('d-m', strtotime($e->fecha))) {
                $data['eventos'] = $e;
            }

        }
        $data['votoHombre'] = $votoHombre;
        $data['votoMujer'] = $votoMujer;

        $data['tiketNuevo'] = count($tiketNuevo);
        $data['tiketConcluido'] = count($tiketConcluido);
        $data['tiketProceso'] = count($tiketProceso);
        $data['actividad'] = (isset($actividad) && !empty($actividad)) ? $actividad : [];
        $data['configuracion'] = (isset($configuracion[0]) && !empty($configuracion)) ? $configuracion[0] : '';
        $data['personal'] = $personal;
        $data['tarjetas'] = $this->getTablaData($globas, [
            'tabla' => 'tarjetas',
            'where' => ['visible' => 1],
            'order' => 'id_tarjeta DESC',
        ]);
        $data['scripts'] = array('principal', 'inicio');
        $data['edita'] = 0;
        $data['nombre_completo'] = $session->nombre_completo;
        $data['contentView'] = $vista;
        $this->_renderView($data);

    }


    public function Perfil()
    {
        $session = \Config\Services::session();
        $data = array();
        $globas = new Mglobal;
        $votoHombre = false;
        $votoMujer = false;
        $vista = 'personal/vInicio';
        $votoH = $globas->getTabla(['tabla' => 'vw_honestidad', 'where' => ['visible' => 1, 'usu_reg' => $session->id_usuario, 'id_sexo' => 1]]);
        $votoM = $globas->getTabla(['tabla' => 'vw_honestidad', 'where' => ['visible' => 1, 'usu_reg' => $session->id_usuario, 'id_sexo' => 2]]);
        if (isset($votoH->data) && !empty($votoH->data)) {
            $votoHombre = true;
        }
        if (isset($votoM->data) && !empty($votoM->data)) {
            $votoMujer = true;
        }
        $mes_actual = date('m');


        $cumple = $globas->getTabla([
            'tabla' => 'vw_usuario',
            'where' => ['visible' => 1],
            'whereMonth' => [['fec_nac', $mes_actual]]
        ])->data;
        $personal = [];
        if (isset($cumple) && !empty($cumple)) {
            foreach ($cumple as $c) {
                $fecha_nacimiento = new DateTime($c->fec_nac);
                $hoy = new DateTime();
                $edad = $hoy->diff($fecha_nacimiento)->y;
                $personal[] = [
                    'nombre_completo' => $c->nombre,
                    'id_edad' => $c->id_edad,
                    'id_nivel' => $c->id_nivel,
                    'id_sexo' => $c->sexo,
                    'id_fec_nac' => $c->id_fec_nac,
                    'id_usuario' => $c->id_usuario,
                    'dsc_area' => $c->dsc_area,
                    'edad' => $edad,
                    'ruta_foto_relativa' => $c->ruta_foto_relativa,
                    'dia' => date('d', strtotime($c->fec_nac))
                ];

            }
        }
        $data['eventos'] = "";
        $fechaInicio = date("Y-m-d");
        $fechaFin = date("Y-m-d");
        $actividad = $globas->getTabla(['tabla' => 'vw_actividad', 'where' => ['visible' => 1, "id_usuario" => $session->id_usuario]])->data;
        $configuracion = $globas->getTabla(['tabla' => 'vw_configuracion', 'where' => ['visible' => 1, "id_usuario" => $session->id_usuario]])->data;

        $tiket = $globas->getTabla(['tabla' => 'vw_tiket', 'where' => ['visible' => 1, "id_usuario" => $session->id_usuario]])->data;
        $eventos = $globas->getTabla(['tabla' => 'eventos', 'where' => ['visible' => 1], 'whereMonth' => [['fecha', $mes_actual]]])->data;
        $tiketNuevo = [];
        $tiketProceso = [];
        $tiketConcluido = [];

        foreach ($eventos as $e) {

            if (date('d-m') == date('d-m', strtotime($e->fecha))) {
                $data['eventos'] = $e;
            }

        }


        foreach ($tiket as $t) {
            if ($t->estatus == 0) {
                $tiketNuevo[] = $t->estatus;
            }
            if ($t->estatus == 1) {
                $tiketConcluido[] = $t->estatus;
            }
            if ($t->estatus == 2) {
                $tiketProceso[] = $t->estatus;
            }

        }
        $data['votoHombre'] = $votoHombre;
        $data['votoMujer'] = $votoMujer;

        $data['tiketNuevo'] = count($tiketNuevo);
        $data['tiketConcluido'] = count($tiketConcluido);
        $data['tiketProceso'] = count($tiketProceso);
        $data['actividad'] = (isset($actividad) && !empty($actividad)) ? $actividad : [];
        $data['configuracion'] = (isset($configuracion[0]) && !empty($configuracion)) ? $configuracion[0] : '';
        $data['personal'] = $personal;
        $data['datos'] = $globas->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->id_usuario]])->data[0];
        $data['lista_alba'] = $globas->getTabla(['tabla' => 'alba', 'where' => ['visible' => 1]])->data;
        $data['tarjetas'] = $this->getTablaData($globas, [
            'tabla' => 'tarjetas',
            'where' => ['visible' => 1],
            'order' => 'id_tarjeta DESC',
        ]);
        $data['scripts'] = array('principal', 'inicio');
        $data['edita'] = 0;
        $data['nombre_completo'] = $session->nombre_completo;
        $data['contentView'] = $vista;
        //die(json_encode($data));
        $this->_renderView($data);
    }
    public function ListaViaticos()
    {
        $session = \Config\Services::session();
        $globas = new Mglobal;
        $vista = 'personal/vInicio';
        $data['datos'] = $globas->getTabla(['tabla' => 'vw_juridico_viaticos', 'where' => ['visible' => 1]])->data;
        $data['cat_funcionario'] = $globas->getTabla(['tabla' => 'cat_tipo_funcionario', 'where' => ['visible' => 1]])->data;
        $data['cat_area'] = $globas->getTabla(['tabla' => 'cat_area_adscripcion', 'where' => ['visible' => 1]])->data;
        $data['cat_gasto'] = $globas->getTabla(['tabla' => 'cat_gasto', 'where' => ['visible' => 1]])->data;
        $data['cat_viaje'] = $globas->getTabla(['tabla' => 'cat_viaje', 'where' => ['visible' => 1]])->data;
        $data['cat_pais'] = $globas->getTabla(['tabla' => 'cat_pais', 'where' => ['visible' => 1]])->data;
        $data['cat_estado'] = $globas->getTabla(['tabla' => 'cat_estado', 'where' => ['visible' => 1]])->data;
        $data['cat_municipios'] = $globas->getTabla(['tabla' => 'cat_municipios', 'where' => ['visible' => 1]])->data;
        $data['deno_puesto'] = $globas->getTabla(['tabla' => 'deno_puesto', 'where' => ['visible' => 1]])->data;
        $data['deno_cargo'] = $globas->getTabla(['tabla' => 'deno_cargo', 'where' => ['visible' => 1]])->data;
        $data['usuarios'] = $globas->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]])->data;
        $data['scripts'] = array('principal', 'inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vListaViaticos';
        $this->_renderView($data);
    }
    public function getDetalleViaticoJSON()
    {
        $globals = new Mglobal;
        $id = $this->request->getPost('id');

        // Obtenemos los datos directos de la vista o tabla
        $tabla = $globals->getTabla([
            "tabla" => "vw_juridico_viaticos",
            "where" => ["id_juridico_viatico" => $id]
        ]);

        // Devolvemos el primer resultado como JSON con el formato correcto de CI4
        if (!empty($tabla->data[0])) {
            return $this->response->setJSON($tabla->data[0]);
        }
        else {
            return $this->response->setJSON(['error' => 'No se encontraron datos']);
        }
    }
    // ==========================================
    // VISTAS DE INVENTARIO
    // ==========================================

    public function InventarioProductos()
    {
        $globas = new Mglobal;

        $data['cat_inventario_papel'] = $globas->getTabla(['tabla' => 'cat_inventario_papel', 'where' => ['visible' => 1]])->data;
        $data['cat_inventario_art_papel'] = $globas->getTabla(['tabla' => 'cat_inventario_art_papel', 'where' => ['visible' => 1]])->data;
        $data['cat_inventario_art_ofi'] = $globas->getTabla(['tabla' => 'cat_inventario_art_ofi', 'where' => ['visible' => 1]])->data;

        // Totales
        $data['total_stock_papel'] = array_sum(array_column($data['cat_inventario_papel'] ?? [], 'stock'));
        $data['total_stock_art_papel'] = array_sum(array_column($data['cat_inventario_art_papel'] ?? [], 'stock'));
        $data['total_stock_art_ofi'] = array_sum(array_column($data['cat_inventario_art_ofi'] ?? [], 'stock'));

        $data['scripts'] = array('principal', 'inicio');
        $data['contentView'] = 'personal/vInventarioProductos';
        $this->_renderView($data);
    }

    public function InventarioLimpieza()
    {
        $globas = new Mglobal;

        $data['cat_inventario_limpieza'] = $globas->getTabla([
            'tabla' => 'cat_inventario_limpieza',
            'where' => ['visible' => 1]
        ])->data;

        $data['total_stock_lim'] = 0;
        if (!empty($data['cat_inventario_limpieza'])) {
            foreach ($data['cat_inventario_limpieza'] as $item) {
                $data['total_stock_lim'] += (int)$item->stock;
            }
        }

        $data['scripts'] = array('principal', 'inicio');
        $data['contentView'] = 'personal/vInventarioLimpieza';
        $this->_renderView($data);
    }
    public function guardarTarjeta()
    {
        $session = \Config\Services::session();
        $globas = new Mglobal;
        $response = new \stdClass();

        $titulo = trim((string) $this->request->getPost('titulo'));
        $descripcion = trim((string) $this->request->getPost('descripcion'));
        $link = trim((string) $this->request->getPost('link'));
        $archivo = $this->request->getFile('archivo');
    
        if ($titulo === '') {
            $response->error = true;
            $response->respuesta = 'El campo título es obligatorio.';
            return $this->respond($response);
        }

        if ($descripcion === '') {
            $response->error = true;
            $response->respuesta = 'El campo descripción es obligatorio.';
            return $this->respond($response);
        }

        if ($link !== '' && !filter_var($link, FILTER_VALIDATE_URL)) {
             return $this->response->setJSON(['error' => true, 'respuesta' => 'El link debe ser una URL válida.']);
        }

        if (!$archivo || !$archivo->isValid()) {
             return $this->response->setJSON(['error' => true, 'respuesta' => 'Debes subir un archivo de imagen o PDF.']);
        }

        $extension = strtolower((string) $archivo->getExtension());
        $permitidos = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf'];
        if (!in_array($extension, $permitidos, true)) {
             return $this->response->setJSON(['error' => true, 'respuesta' => 'Solo se permiten archivos JPG, PNG, WEBP, GIF o PDF.']);
        }

        $uploadPath = FCPATH . 'assets/uploads/tarjetas/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = 'tarjeta_' . date('YmdHis') . '_' . uniqid() . '.' . $extension;
        if (!$archivo->move($uploadPath, $newName)) {
             return $this->response->setJSON(['error' => true, 'respuesta' => 'No fue posible guardar el archivo de la tarjeta.']);
        }

        $relativePath = 'assets/uploads/tarjetas/' . $newName;

        
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaTurno'];
        $dataConfig = [
            "tabla" => "tarjetas",
            "editar" => 'false',
            
        ];
        $dataInsert = [
            'titulo' => $titulo,
            'archivo' => $relativePath,
            'tipo_archivo' => $extension,
            'descripcion' => $descripcion,
            'link' => $link !== '' ? $link : null,
            'usu_reg' => (int) $session->get('id_usuario'),
        ];

        $response = $globas->saveTabla($dataInsert, $dataConfig, $dataBitacora);
       // die(var_dump($response));
        if($response->error){
             $response->respuesta = 'No fue posible guardar la tarjeta.';
             return $this->respond($response);
        }
    
        return $this->respond(['error' => false, 'respuesta' => 'Tarjeta guardada correctamente.']);
    }

    public function eliminarTarjeta($idTarjeta = null)
    {
        $session = \Config\Services::session();
        $globas = new Mglobal;

        $idTarjeta = (int) $idTarjeta;
        if ($idTarjeta <= 0) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'No se recibió una tarjeta válida.',
            ]);
        }

        $tarjetas = $this->getTablaData($globas, [
            'tabla' => 'tarjetas',
            'where' => ['visible' => 1, 'id_tarjeta' => $idTarjeta],
        ]);

        if (empty($tarjetas)) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'La tarjeta no existe o ya fue eliminada.',
            ]);
        }

        $tarjeta = $tarjetas[0];
        if (!empty($tarjeta->archivo)) {
            $archivoPath = FCPATH . ltrim((string) $tarjeta->archivo, '/\\');
            if (is_file($archivoPath)) {
                @unlink($archivoPath);
            }
        }

        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Inicio.php/eliminarTarjeta'];
        $dataConfig = [
            'tabla' => 'tarjetas',
            'editar' => 'true',
            'idEditar' => ['id_tarjeta' => $idTarjeta],
        ];

        $response = $globas->saveTabla(['visible' => 0], $dataConfig, $dataBitacora);
        if (!empty($response->error)) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'No fue posible eliminar la tarjeta.',
            ]);
        }

        return $this->response->setJSON([
            'error' => false,
            'respuesta' => 'Tarjeta eliminada correctamente.',
        ]);
    }
    public function ListaConvenio()
    {
        $globas = new Mglobal;

        $result = $globas->getTabla([
            'tabla' => 'vw_material_promo',
            'where' => ['visible' => 1]
        ]);

        $data['material_promo'] = $result->data ?? [];

        $data['scripts'] = array('principal', 'inicio');
        $data['contentView'] = 'personal/vListaConvenio';
        $this->_renderView($data);
    }
    public function FormHonorarios($id = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $dataBase = 'susi';
        $data = [];
        $data['scripts'] = array('principal', 'inicio');
        $data['contentView'] = 'personal/vFormReporteHonorarios';
        $data['pdf_referencia'] = base_url('assets/Reporteactividades26.pdf');
        $data['reporte'] = null;
        $data['actividades'] = [];
        $data['id_usuario_session'] = $session->get('id_usuario');

        if (!empty($id)) {
            $reporte = $globals->getTabla([
                'dataBase' => $dataBase,
                'tabla' => 'reporte_honorarios',
                'where' => [
                    'id_reporte_honorarios' => intval($id),
                    'visible' => 1
                ]
            ]);

            if (!empty($reporte->data[0])) {
                $data['reporte'] = $reporte->data[0];

                $actividades = $globals->getTabla([
                    'dataBase' => $dataBase,
                    'tabla' => 'reporte_honorarios_actividad',
                    'where' => [
                        'id_reporte_honorarios' => intval($id),
                        'visible' => 1
                    ],
                    'order' => ['orden' => 'ASC']
                ]);

                $data['actividades'] = $actividades->data ?? [];
            }
        }

        $this->_renderView($data);
    }
    public function ListaReporteHonorarios()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $dataBase = 'susi';

        $where = ['visible' => 1];
        if (!in_array((int)$session->get('id_perfil'), [1, 2, 3], true)) {
            $where['usu_reg'] = (int)$session->get('id_usuario');
        }

        $reportes = $globals->getTabla([
            'dataBase' => $dataBase,
            'tabla' => 'reporte_honorarios',
            'where' => $where,
            'order' => ['id_reporte_honorarios' => 'DESC']
        ]);

        $data = [];
        $data['scripts'] = array('principal', 'inicio');
        $data['contentView'] = 'personal/vListaReporteHonorarios';
        $data['reportes'] = $reportes->data ?? [];

        $this->_renderView($data);
    }
    public function guardarReporteHonorarios()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $dataBase = 'susi';
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'No fue posible guardar el reporte.';

        $post = $this->request->getPost();
        $idReporte = intval($post['id_reporte_honorarios'] ?? 0);

        $responsable = trim((string)($post['responsable_administrativo'] ?? ''));
        $area = trim((string)($post['area'] ?? ''));
        $numeroContrato = trim((string)($post['numero_contrato'] ?? ''));
        $tipoReporte = trim((string)($post['tipo_reporte'] ?? ''));
        $fechaInicio = trim((string)($post['fecha_inicio'] ?? ''));
        $fechaFin = trim((string)($post['fecha_fin'] ?? ''));
        $fechaFirma = trim((string)($post['fecha_firma'] ?? ''));
        $nombrePrestador = trim((string)($post['nombre_prestador'] ?? ''));
        $puestoPrestador = trim((string)($post['puesto_prestador'] ?? ''));
        $nombreResponsable = trim((string)($post['nombre_responsable'] ?? ($post['nombre_responsable_area'] ?? '')));
        $puestoResponsable = trim((string)($post['puesto_responsable'] ?? ($post['puesto_responsable_area'] ?? '')));
        $titulos = $post['actividad_titulo'] ?? [];
        $desgloses = $post['actividad_desglose'] ?? [];

        if (
            $responsable === '' || $area === '' || $numeroContrato === '' || $tipoReporte === '' ||
            $fechaInicio === '' || $fechaFin === '' || $fechaFirma === '' ||
            $nombrePrestador === '' || $puestoPrestador === '' || $nombreResponsable === '' || $puestoResponsable === ''
        ) {
            $response->respuesta = 'Todos los campos del reporte son obligatorios.';
            return $this->response->setJSON($response);
        }

        if (!in_array($tipoReporte, ['trimestral', 'final'], true)) {
            $response->respuesta = 'El tipo de reporte es invalido.';
            return $this->response->setJSON($response);
        }

        if ($fechaInicio > $fechaFin) {
            $response->respuesta = 'La fecha inicial no puede ser mayor a la fecha final.';
            return $this->response->setJSON($response);
        }

        $actividadesLimpias = [];
        if (!is_array($titulos)) $titulos = [];
        if (!is_array($desgloses)) $desgloses = [];

        $maxActividades = max(count($titulos), count($desgloses));
        for ($i = 0; $i < $maxActividades; $i++) {
            $titulo = trim((string)($titulos[$i] ?? ''));
            $desglose = trim((string)($desgloses[$i] ?? ''));

            if ($titulo === '' && $desglose === '') {
                continue;
            }

            if ($titulo === '' || $desglose === '') {
                $response->respuesta = 'Cada actividad debe incluir titulo y desglose.';
                return $this->response->setJSON($response);
            }

            $actividadesLimpias[] = [
                'titulo_actividad' => $titulo,
                'desglose_actividad' => $desglose,
                'orden' => count($actividadesLimpias) + 1
            ];
        }

        if (empty($actividadesLimpias)) {
            $response->respuesta = 'Debes capturar al menos una actividad.';
            return $this->response->setJSON($response);
        }

        $dataSave = [
            'responsable_administrativo' => $responsable,
            'area' => $area,
            'numero_contrato' => $numeroContrato,
            'tipo_reporte' => $tipoReporte,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'fecha_firma' => $fechaFirma,
            'nombre_prestador' => $nombrePrestador,
            'puesto_prestador' => $puestoPrestador,
            'nombre_responsable' => $nombreResponsable,
            'puesto_responsable' => $puestoResponsable,
            'nombre_responsable_area' => $nombreResponsable,
            'puesto_responsable_area' => $puestoResponsable,
            'pdf_referencia' => 'assets/Reporteactividades26.pdf',
            'visible' => 1
        ];

        $dataConfig = [
            'dataBase' => $dataBase,
            'tabla' => 'reporte_honorarios',
            'editar' => false
        ];

        if ($idReporte > 0) {
            $dataConfig = [
                'dataBase' => $dataBase,
                'tabla' => 'reporte_honorarios',
                'editar' => true,
                'idEditar' => ['id_reporte_honorarios' => $idReporte]
            ];
            $dataSave['usu_act'] = $session->id_usuario ?? 0;
            $dataSave['fec_act'] = date('Y-m-d H:i:s');
        } else {
            $dataSave['usu_reg'] = $session->id_usuario ?? 0;
            $dataSave['fec_reg'] = date('Y-m-d H:i:s');
        }

        $result = $globals->saveTabla(
            $dataSave,
            $dataConfig,
            ['id_user' => $session->id_usuario ?? 0, 'script' => 'Inicio.guardarReporteHonorarios']
        );

        if (!isset($result->error) || $result->error) {
            $response->respuesta = $result->respuesta ?? 'Error al guardar la cabecera del reporte.';
            $response->debug = $result ?? null;
            return $this->response->setJSON($response);
        }

        $idGuardado = $idReporte > 0 ? $idReporte : intval($result->idRegistro ?? 0);
        if ($idGuardado <= 0) {
            $response->respuesta = 'No fue posible identificar el reporte guardado.';
            return $this->response->setJSON($response);
        }

        if ($idReporte > 0) {
            $globals->saveTabla(
                [
                    'visible' => 0,
                    'usu_act' => $session->id_usuario ?? 0,
                    'fec_act' => date('Y-m-d H:i:s')
                ],
                [
                    'dataBase' => $dataBase,
                    'tabla' => 'reporte_honorarios_actividad',
                    'editar' => true,
                    'idEditar' => ['id_reporte_honorarios' => $idGuardado]
                ],
                ['id_user' => $session->id_usuario ?? 0, 'script' => 'Inicio.guardarReporteHonorarios.actividades_reset']
            );
        }

        foreach ($actividadesLimpias as $actividad) {
            $detalle = [
                'id_reporte_honorarios' => $idGuardado,
                'titulo_actividad' => $actividad['titulo_actividad'],
                'desglose_actividad' => $actividad['desglose_actividad'],
                'orden' => $actividad['orden'],
                'visible' => 1,
                'usu_reg' => $session->id_usuario ?? 0,
                'fec_reg' => date('Y-m-d H:i:s')
            ];

            $saveActividad = $globals->saveTabla(
                $detalle,
                ['dataBase' => $dataBase, 'tabla' => 'reporte_honorarios_actividad', 'editar' => false],
                ['id_user' => $session->id_usuario ?? 0, 'script' => 'Inicio.guardarReporteHonorarios.actividad']
            );

            if (!isset($saveActividad->error) || $saveActividad->error) {
                $response->respuesta = $saveActividad->respuesta ?? 'Error al guardar actividades del reporte.';
                $response->debug = $saveActividad ?? null;
                return $this->response->setJSON($response);
            }
        }

        $response->error = false;
        $response->respuesta = 'Reporte guardado correctamente.';
        $response->id_reporte_honorarios = $idGuardado;
        $response->pdf_url = base_url('index.php/Inicio/pdfreporteHonorario/' . $idGuardado);

        return $this->response->setJSON($response);
    }
    public function getReporteHonorariosJSON($id = null)
    {
        $globals = new Mglobal;
        $dataBase = 'susi';
        $id = intval($id ?: $this->request->getGet('id') ?: $this->request->getPost('id'));

        if ($id <= 0) {
            return $this->response->setJSON(['error' => true, 'respuesta' => 'ID invalido']);
        }

        $reporte = $globals->getTabla([
            'dataBase' => $dataBase,
            'tabla' => 'reporte_honorarios',
            'where' => [
                'id_reporte_honorarios' => $id,
                'visible' => 1
            ]
        ]);

        if (empty($reporte->data[0])) {
            return $this->response->setJSON(['error' => true, 'respuesta' => 'Reporte no encontrado']);
        }

        $actividades = $globals->getTabla([
            'dataBase' => $dataBase,
            'tabla' => 'reporte_honorarios_actividad',
            'where' => [
                'id_reporte_honorarios' => $id,
                'visible' => 1
            ],
            'order' => ['orden' => 'ASC']
        ]);

        return $this->response->setJSON([
            'error' => false,
            'data' => $reporte->data[0],
            'actividades' => $actividades->data ?? []
        ]);
    }
    public function pdfreporteHonorario($id = null)
    {
        $globals = new Mglobal;
        $dataBase = 'susi';
        $id = intval($id ?: $this->request->getGet('id'));

        if ($id <= 0) {
            exit('ID de reporte invalido.');
        }

        $reporte = $globals->getTabla([
            'dataBase' => $dataBase,
            'tabla' => 'reporte_honorarios',
            'where' => [
                'id_reporte_honorarios' => $id,
                'visible' => 1
            ]
        ]);

        if (empty($reporte->data[0])) {
            exit('Reporte no encontrado.');
        }

        $actividades = $globals->getTabla([
            'dataBase' => $dataBase,
            'tabla' => 'reporte_honorarios_actividad',
            'where' => [
                'id_reporte_honorarios' => $id,
                'visible' => 1
            ],
            'order' => ['orden' => 'ASC']
        ]);

        $data = [];
        $data['reporte'] = $reporte->data[0];
        $data['actividades'] = $actividades->data ?? [];
        $data['pdf_referencia'] = !empty($data['reporte']->pdf_referencia)
            ? base_url($data['reporte']->pdf_referencia)
            : base_url('assets/Reporteactividades26.pdf');
        $templateFile = ROOTPATH . 'public/assets/Reporteactividades26.pdf';

        $html = view('pdfs/vPdfReporteHonorarios', $data);

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 42,
            'margin_left' => 18,
            'margin_right' => 18,
            'margin_bottom' => 24,
            'format' => 'Letter',
            'tempDir' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mpdf'
        ]);

        $mpdf->showImageErrors = true;

        if (is_file($templateFile)) {
            $mpdf->SetDocTemplate($templateFile, true);
        } else {
            throw new \Exception('No existe la plantilla del reporte en: ' . $templateFile);
        }

        $mpdf->SetHTMLFooter('
            <div style="width:100%; margin:0 auto; padding:0; text-align:center;">
                <div style="display:block; width:100%; text-align:center; font-size:9.2pt; line-height:1.3; margin:0 auto;">
                    Parque Guanajuato Bicentenario. Carretera de Cuota Silao-Guanajuato Km. 3.8 Silao, Gto. C.P. 36270<br>
                    Tel. (472) 103 9900<br>
                    guanajuato.mx
                </div>
            </div>
        ');

        $mpdf->WriteHTML($html);
        $mpdf->Output('Reporte_Honorarios_' . $id . '.pdf', 'I');
        exit;
    }
    public function FormularioPromo($id = null, $idFila = null, $idSalida = null)
    {
        $session = \Config\Services::session();
        $globals = new Mglobal();

        $session->set('formPromo_id_material_promo', $id);
        $session->set('formPromo_idArticulo', $idFila);
        $session->set('formPromo_idSalida', $idSalida);

        $data = [];
        $data['scripts'] = array('principal', 'inicio');
        $data['contentView'] = 'personal/vFormularioPromo';

        $data['id_material_promo'] = $id;
        $data['idArticulo'] = $idFila;
        $data['id_salida_inventario'] = $idSalida;

        // ✅ MODO MULTI: si NO hay idFila, cargamos productos del contrato
        if (empty($idFila)) {
            $productos = $globals->getTabla([
                'tabla' => 'cat_inventario_promo',
                'where' => [
                    'visible' => 1,
                    'id_convenio_promo' => intval($id)
                ]
            ])->data ?? [];

            $data['productos'] = $productos; // si viene vacío, la vista puede mostrar mensaje
        } else {
            $data['productos'] = []; // legacy
        }

        $this->_renderView($data);
    }
    private function generarFolioPorConvenio($idConvenio)
    {
        $globas = new Mglobal();

        $anio = date('Y');
        $idConvenio = intval($idConvenio);

        $res = $globas->getTabla([
            'tabla' => 'salida_inventario',
            'where' => [
                'visible' => 1,
                'id_convenio' => $idConvenio
            ]
        ]);

        $pref = "PROMO-{$idConvenio}-{$anio}-";
        $max = 0;

        if (!empty($res->data)) {
            foreach ($res->data as $row) {
                $folio = trim((string)($row->folio ?? ''));
                if ($folio !== '' && strpos($folio, $pref) === 0) {
                    $partes = explode('-', $folio);
                    $n = intval(end($partes));
                    if ($n > $max) $max = $n;
                }
            }
        }

        $consecutivo = $max + 1;
        return $pref . str_pad((string)$consecutivo, 4, '0', STR_PAD_LEFT);
    }
    public function guardarConvenio()
    {
        $globas  = new Mglobal();
        $session = \Config\Services::session();

        $dataBase = 'susi'; // requerido por saveTabla()
        $tablaCab = 'salida_inventario';
        $tablaDet = 'salida_inventario_detalle';

        // ============================
        // ID contrato (compat)
        // ============================
        $idConvenio = $this->request->getPost('id_material_promo');
        if ($idConvenio === null || $idConvenio === '') {
            $idConvenio = $this->request->getPost('idConvenio');
        }
        $idConvenio = intval($idConvenio);

        // ============================
        // IDs legacy
        // ============================
        $idArticulo = $this->request->getPost('idArticulo'); // puede venir vacío en flujo nuevo
        $idSalida   = $this->request->getPost('idSalida');
        $idSalida = ($idSalida === null || $idSalida === '') ? 0 : intval($idSalida);

        // ============================
        // Campos cabecera
        // ============================
        $fecEveRaw = trim((string)$this->request->getPost('fec_eve'));
        $fecEve = ($fecEveRaw !== '') ? ($fecEveRaw . ' 00:00:00') : null;

        $conceptoRaw = trim((string)$this->request->getPost('concepto'));
        $concepto = ($conceptoRaw !== '') ? $conceptoRaw : null;

        $lugar   = $this->request->getPost('lugar_entrega');
        $puesto  = $this->request->getPost('puesto');
        $nombre  = $this->request->getPost('nombre_solicitante');
        $tel     = $this->request->getPost('telefono');
        $correo  = $this->request->getPost('correo');

        if ($idConvenio <= 0) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'Falta id_material_promo (contrato).'
            ]);
        }

        // ============================
        // Detectar si viene flujo nuevo (items JSON)
        // ============================
        $itemsJson = (string)$this->request->getPost('items');
        $items = json_decode($itemsJson, true);
        $usaNuevo = (is_array($items) && !empty($items));

        // ============================
        // Si NO viene items, usamos flujo viejo (1 artículo)
        // ============================
        if (!$usaNuevo) {
            $idArticulo = ($idArticulo === null || $idArticulo === '') ? 0 : intval($idArticulo);
            if ($idArticulo <= 0) {
                return $this->response->setJSON([
                    'error' => true,
                    'respuesta' => 'Faltan IDs: id_material_promo / idArticulo'
                ]);
            }

            $qtyLegacy = intval($this->request->getPost('cantidad'));
            if ($qtyLegacy <= 0) {
                return $this->response->setJSON([
                    'error' => true,
                    'respuesta' => 'La cantidad es requerida.'
                ]);
            }

            // Convertimos el flujo viejo a items para unificar lógica abajo
            $items = [[
                'id_inventario_promo' => $idArticulo,
                'cantidad_entregada'  => $qtyLegacy
            ]];
        }

        // ============================
        // Validar items + stock antes de guardar
        // ============================
        $prods = $globas->getTabla([
            'tabla' => 'cat_inventario_promo',
            'where' => [
                'visible' => 1,
                'id_convenio_promo' => $idConvenio
            ]
        ]);

        $mapStock = [];
        if (empty($prods->error) && !empty($prods->data)) {
            foreach ($prods->data as $p) {
                $mapStock[intval($p->id_inventario_promo ?? 0)] = intval($p->stock ?? 0);
            }
        }

        foreach ($items as $it) {
            $idInv = intval($it['id_inventario_promo'] ?? 0);
            $qty   = intval($it['cantidad_entregada'] ?? 0);

            if ($idInv <= 0 || $qty <= 0) {
                return $this->response->setJSON([
                    'error' => true,
                    'respuesta' => 'Items inválidos: revisa productos y cantidades.'
                ]);
            }

            if (!array_key_exists($idInv, $mapStock)) {
                return $this->response->setJSON([
                    'error' => true,
                    'respuesta' => "Producto {$idInv} no pertenece a este contrato o no existe."
                ]);
            }

            if ($qty > $mapStock[$idInv]) {
                return $this->response->setJSON([
                    'error' => true,
                    'respuesta' => "Stock insuficiente para producto {$idInv}. Disponible: {$mapStock[$idInv]}."
                ]);
            }
        }

        // ============================
        // EDITAR cabecera (solo si idSalida viene)
        // (solo cabecera, no tocar stock ni detalle)
        // ============================
        if ($idSalida > 0) {
            $dataUpdate = [
                'id_convenio'        => $idConvenio,
                'lugar'              => $lugar,
                'puesto'             => $puesto,
                'nombre_solicitante' => $nombre,
                'telefono'           => $tel,
                'correo'             => $correo,
                'fec_eve'            => $fecEve,
                'concepto'           => $concepto,
                'usu_act'            => $session->get('id_usuario'),
                'fec_act'            => date('Y-m-d H:i:s')
            ];

            $resultUpd = $globas->saveTabla(
                $dataUpdate,
                [
                    'dataBase' => $dataBase,
                    'tabla'    => $tablaCab,
                    'editar'   => true,
                    'idEditar' => ['id_salida_inventario' => $idSalida]
                ],
                ['script' => 'Inicio.php/guardarConvenio_UPD']
            );

            if ($resultUpd && isset($resultUpd->error) && $resultUpd->error === false) {
                $pdf_url = base_url("index.php/Inicio/generarPDFConvenio/" . $idSalida);

                return $this->response->setJSON([
                    'error' => false,
                    'respuesta' => 'Recibo actualizado correctamente',
                    'pdf_url' => $pdf_url,
                    'id_material_promo' => $idConvenio,
                    'id_salida_inventario' => $idSalida
                ]);
            }

            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'No se pudo actualizar el recibo',
                'debug' => $resultUpd
            ]);
        }

        // ============================
        // INSERTAR cabecera (nuevo recibo)
        // Folio consecutivo por contrato
        // ============================
        $folio = $this->generarFolioPorConvenio($idConvenio);

        // ✅ Compat legacy: llenar id_articulo y cantidad en cabecera
        $primerId = intval($items[0]['id_inventario_promo'] ?? 0);

        $totalEntrega = 0;
        foreach ($items as $it) {
            $totalEntrega += intval($it['cantidad_entregada'] ?? 0);
        }

        $dataInsertCab = [
            'id_convenio'        => $idConvenio,
            'id_articulo'        => $primerId,
            'cantidad'           => $totalEntrega,
            'lugar'              => $lugar,
            'puesto'             => $puesto,
            'nombre_solicitante' => $nombre,
            'telefono'           => $tel,
            'correo'             => $correo,
            'fec_eve'            => $fecEve,
            'concepto'           => $concepto,
            'folio'              => $folio,
            'usu_reg'            => $session->get('id_usuario'),
            'fec_reg'            => date('Y-m-d H:i:s'),
            'visible'            => 1
        ];

        $resultCab = $globas->saveTabla(
            $dataInsertCab,
            [
                'dataBase' => $dataBase,
                'tabla'    => $tablaCab,
                'editar'   => false
            ],
            ['script' => 'Inicio.php/guardarConvenio_INS']
        );

        if (!$resultCab || !isset($resultCab->error) || $resultCab->error !== false) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'No se pudo crear el recibo (cabecera).',
                'debug' => $resultCab
            ]);
        }

        $idRecibo = intval($resultCab->idRegistro ?? $resultCab->insertId ?? 0);
        if ($idRecibo <= 0) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'Recibo creado pero no se pudo obtener el ID.',
                'debug' => $resultCab
            ]);
        }

        // ============================
        // Insertar detalle + restar stock
        // ============================
        foreach ($items as $it) {
            $idInv = intval($it['id_inventario_promo']);
            $qty   = intval($it['cantidad_entregada']);

            $stockAntes   = $mapStock[$idInv];
            $stockDespues = $stockAntes - $qty;

            // detalle
            $dataDet = [
                'id_salida_inventario' => $idRecibo,
                'id_inventario_promo'  => $idInv,
                'cantidad_entregada'   => $qty,
                'stock_antes'          => $stockAntes,
                'stock_despues'        => $stockDespues,
                'visible'              => 1,
                'fec_reg'              => date('Y-m-d H:i:s'),
                'usu_reg'              => $session->get('id_usuario')
            ];

            $resultDet = $globas->saveTabla(
                $dataDet,
                [
                    'dataBase' => $dataBase,
                    'tabla'    => $tablaDet,
                    'editar'   => false
                ],
                ['script' => 'Inicio.php/guardarConvenio_DET']
            );

            if (!$resultDet || !isset($resultDet->error) || $resultDet->error !== false) {
                return $this->response->setJSON([
                    'error' => true,
                    'respuesta' => "Recibo {$idRecibo} creado, pero falló detalle del producto {$idInv}.",
                    'debug' => $resultDet
                ]);
            }

            // restar stock
            $resultStock = $globas->saveTabla(
                ['stock' => $stockDespues],
                [
                    'dataBase' => $dataBase,
                    'tabla'    => 'cat_inventario_promo',
                    'editar'   => true,
                    'idEditar' => ['id_inventario_promo' => $idInv]
                ],
                ['script' => 'Inicio.php/guardarConvenio_STOCK']
            );

            if (!$resultStock || !isset($resultStock->error) || $resultStock->error !== false) {
                return $this->response->setJSON([
                    'error' => true,
                    'respuesta' => "Recibo {$idRecibo} creado, pero falló la actualización de stock del producto {$idInv}.",
                    'debug' => $resultStock
                ]);
            }

            $mapStock[$idInv] = $stockDespues;
        }

        $pdf_url = base_url("index.php/Inicio/generarPDFConvenio/" . $idRecibo);

        return $this->response->setJSON([
            'error' => false,
            'respuesta' => 'Recibo generado correctamente',
            'pdf_url' => $pdf_url,
            'id_material_promo' => $idConvenio,
            'id_salida_inventario' => $idRecibo,
            'folio' => $folio
        ]);
    }
    public function buscarProveedor2()
    {
        $term = $this->request->getGet('q');

        $this->globals = new Mglobal();

        $res = $this->globals->getTabla([
            "tabla" => "proveedor",
            "orlike"  => [
                "razon_social" => $term,
                "no_proveedor" => $term,
                "rfc" => $term,
            ],
            "limit" => 10
        ]);

        $results = [];

        if (!empty($res->data)) {
            foreach ($res->data as $row) {
                $results[] = [
                    "id"   => $row->id_proveedor, // 🔥 ESTO ES LO QUE SE ENVÍA
                    "text" => $row->razon_social . " - " . $row->no_proveedor
                ];
            }
        }

        return $this->response->setJSON([
            "results" => $results
        ]);
    }
    public function generarPDFConvenio($id)
    {
        $globas = new Mglobal();

        $id = intval($id);

        // ============================
        // 1) Cabecera (salida_inventario)
        // ============================
        $registro = $globas->getTabla([
            'tabla' => 'salida_inventario',
            'where' => [
                'id_salida_inventario' => $id,
                'visible' => 1
            ],
            'limit' => 1
        ])->data[0] ?? null;

        if (!$registro) {
            echo "Registro no encontrado.";
            return;
        }

        $idConvenio = intval($registro->id_convenio ?? 0);

        // ============================
        // 2) Detalle del folio (salida_inventario_detalle)
        // ============================
        $detalles = $globas->getTabla([
            'tabla' => 'salida_inventario_detalle',
            'where' => [
                'visible' => 1,
                'id_salida_inventario' => $id
            ]
        ])->data ?? [];

        if (empty($detalles)) {
            // PDF sin renglones, pero con cabecera
            $detalles = [];
        }

        // ============================
        // Datos del contrato (para mostrar en PDF)
        // ============================
        $infoContrato = $globas->getTabla([
            'tabla' => 'vw_material_promo',
            'where' => [
                'visible' => 1,
                'id_material_promo' => $idConvenio
            ],
            'limit' => 1
        ])->data ?? [];

        $materiales = $infoContrato[0] ?? null;

        // ============================
        // 3) Mapear nombres de productos del contrato (1 llamada)
        // ============================
        $productosConvenio = $globas->getTabla([
            'tabla' => 'cat_inventario_promo',
            'where' => [
                'visible' => 1,
                'id_convenio_promo' => $idConvenio
            ]
        ])->data ?? [];

        $mapProd = []; // id_inventario_promo => dsc_producto
        foreach ($productosConvenio as $p) {
            $mapProd[intval($p->id_inventario_promo ?? 0)] = (string)($p->dsc_producto ?? '');
        }

        // ============================
        // 4) Construir productos para PDF (solo lo entregado en ESTE folio)
        // ============================
        $productosPDF = [];
        $totalEntregado = 0;

        foreach ($detalles as $d) {
            $idInv = intval($d->id_inventario_promo ?? 0);
            $qty   = intval($d->cantidad_entregada ?? 0);

            $totalEntregado += $qty;

            $productosPDF[] = (object)[
                'id_inventario_promo' => $idInv,
                'dsc_producto'        => $mapProd[$idInv] ?? ('ID#' . $idInv),
                'cantidad_entregada'  => $qty
            ];
        }

        $nombreArticuloCompat = !empty($productosPDF)
            ? (string)($productosPDF[0]->dsc_producto ?? 'Desconocido')
            : 'Desconocido';

        // ============================
        // 5) Datos para la vista PDF
        // ============================
        $datos = [
            'folio' => $registro->folio,
            'concepto' => $registro->concepto,
            'materiales' => $materiales,

            // compat (si aún lo imprimes en algún lado)
            'cantidad' => $totalEntregado,

            'nombre_solicitante' => $registro->nombre_solicitante,
            'puesto' => $registro->puesto,
            'telefono' => $registro->telefono,
            'correo' => $registro->correo,
            'fec_eve' => $registro->fec_eve ? date('d/m/Y', strtotime($registro->fec_eve)) : '',
            'lugar' => $registro->lugar,

            // ✅ SOLO lo del folio
            'productos' => $productosPDF,
            'total_entregado' => $totalEntregado,
            
            // compat
            'nombre_articulo' => $nombreArticuloCompat
            
        ];


        // ============================
        // 6) mPDF + membrete
        // ============================
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10
        ]);

        $mpdf->showImageErrors = true;

        $path = ROOTPATH . 'public/assets/images/membrete.pdf';
        if (!file_exists($path)) {
            throw new \Exception("No existe el membrete en: " . $path);
        }

        $mpdf->SetDocTemplate($path, true);

        $html = view('personal/vpdfReciboPromo', $datos);
        $mpdf->WriteHTML($html);

        $nombreArchivo = 'Recibo_' . ($datos['folio'] ?? $id) . '.pdf';
        $mpdf->Output($nombreArchivo, \Mpdf\Output\Destination::INLINE);
        exit;
    }
    public function FormularioPromoPorConvenio($idConvenio = null)
    {
        if (empty($idConvenio)) {
            return redirect()->back();
        }

        $globals = new Mglobal();

        $producto = $globals->getTabla([
            'tabla' => 'cat_inventario_promo',
            'where' => [
                'visible' => 1,
                'id_convenio_promo' => intval($idConvenio)
            ]
        ])->data ?? [];

        if (empty($producto)) {
            return redirect()->to(base_url('index.php/Inicio/InventarioPromocion/' . intval($idConvenio)));
        }

        // ✅ Activar modo MULTI (sin idFila)
        return redirect()->to(base_url('index.php/Inicio/FormularioPromo/' . intval($idConvenio)));
    }
    public function consultarReciboPromo()
    {
        $globals = new Mglobal;

        $idConvenio = (int)$this->request->getPost('id_convenio_promo');
        $idArticulo = (int)$this->request->getPost('id_inventario_promo'); // puede venir 0

        if (!$idConvenio) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'Datos incompletos: falta convenio.'
            ]);
        }

        // WHERE base: siempre por convenio + visible
        $where = [
            'id_convenio' => $idConvenio,
            'visible'     => 1
        ];

        // Si viene artículo válido (>0), lo aplicamos. Si no, se busca "general" por convenio.
        if ($idArticulo > 0) {
            $where['id_articulo'] = $idArticulo;
        }

        $rows = $globals->getTabla([
            'tabla' => 'salida_inventario',
            'where' => $where,
            'orderBy' => 'id_salida_inventario DESC'
        ])->data ?? [];

        if (empty($rows)) {
            return $this->response->setJSON([
                'error' => false,
                'existe' => false,
                'respuesta' => 'Recibo no generado.'
            ]);
        }

        $registro = $rows[0];
        $idSalida = $registro->id_salida_inventario ?? null;

        if (!$idSalida) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'No se pudo determinar el ID del recibo.'
            ]);
        }

        return $this->response->setJSON([
            'error' => false,
            'existe' => true,
            'pdf_url' => base_url('index.php/Inicio/generarPDFConvenio/' . $idSalida)
        ]);
    }
    public function ListaSalidasPromo($idArticulo = null)
    {
        $globas = new Mglobal;
        $session = \Config\Services::session();

         // Datos Articulo
         $articulo = $globas->getTabla(['tabla' => 'cat_inventario_promo', 'where' => ['id_inventario_promo' => $idArticulo]])->data[0] ?? null;
         $data['articulo'] = $articulo;
         $data['idArticulo'] = $idArticulo;
        
         // Depende de la logica, tal vez necesitemos el id_convenio del articulo para links
         // $data['idConvenio'] = ... (buscarlo o pasarlo?)
         // Lo sacamos del articulo si tiene relacion o del historial. 
         // En vInventarioPromocion se usaba $materiales->convenio. 
         // Pero aqui solo tenemos idArticulo. 
         
         // Lista Salidas
         $salidas = $globas->getTabla([
             'tabla' => 'salida_inventario', 
             'where' => ['id_articulo' => $idArticulo, 'visible' => 1]
         ])->data;
         $data['salidas'] = $salidas;

        $data['scripts'] = array('principal', 'inicio');
        $data['contentView'] = 'personal/vListaSalidasPromo';
        $this->_renderView($data);
    }
    public function eliminarSalida()
    {
        $globas = new Mglobal;
        $session = \Config\Services::session();
        $id = $this->request->getPost('id');

        $dataUpdate = [
            'visible' => 0,
            'usu_act' => $session->get('id_usuario'),
            'fec_act' => date('Y-m-d H:i:s')
        ];
        
        $dataConfig = [
            'tabla' => 'salida_inventario',
            'editar' => true,
            'idEditar' => ['id_salida_inventario' => $id]
        ];
        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Inicio.php/eliminarSalida'];

        $result = $globas->saveTabla($dataUpdate, $dataConfig, $dataBitacora);
        return $this->response->setJSON($result);
    }
    public function InventarioPromocion($id = null)
    {
        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $globals = new Mglobal;

        $idConvenio = (int)$id;

        $info = $globals->getTabla([
        'tabla' => 'vw_material_promo',
        'where' => [
            'visible' => 1,
            'id_material_promo' => (int)$id
        ]
        ])->data ?? [];

        $data['materiales'] = $info[0] ?? null;

        // Productos del convenio
        $productos = $globals->getTabla([
            'tabla' => 'cat_inventario_promo',
            'where' => [
                'visible' => 1,
                'id_convenio_promo' => (int)$id
            ]
        ])->data ?? [];

        // Datos del convenio (padre)
        $convenio = $globals->getTabla([
            'tabla' => 'vw_material_promo',
            'where' => [
                'visible' => 1,
                'id_material_promo' => (int)$id
            ]
        ])->data ?? [];

        $data['cat_inventario_promo'] = $productos;

        // IDs homologados (nuevo + compatibilidad)
        $data['id_convenio_promo']  = (int)$id;
        $data['id_convenio']        = (int)$id; // legacy
        $data['id_material_promo']  = (int)$id; // legacy

        foreach ($productos as $key => $item) {

            // ===== Variantes (JSON) =====
            $varsJson = is_array($item) ? ($item['variantes'] ?? '') : ($item->variantes ?? '');
            $varsArr = json_decode((string)$varsJson, true);
            if (is_array($item)) {
                $item['variantes'] = is_array($varsArr) ? $varsArr : [];
            } else {
                $item->variantes = is_array($varsArr) ? $varsArr : [];
            }

            // ===== Colores (JSON) =====
            $colJson = is_array($item) ? ($item['color'] ?? '') : ($item->color ?? '');
            $colArr = json_decode((string)$colJson, true);
            if (is_array($item)) {
                $item['color'] = is_array($colArr) ? $colArr : [];
            } else {
                $item->color = is_array($colArr) ? $colArr : [];
            }

            // ===== Cálculos =====
            $cantidad = (int)(is_array($item) ? ($item['cantidad'] ?? 0) : ($item->cantidad ?? 0));
            $stock    = (int)(is_array($item) ? ($item['stock'] ?? 0) : ($item->stock ?? 0));

            if (is_array($item)) {
                $item['cantidad_solicitada'] = $cantidad;
                $item['stock_disponible']    = $stock;
            } else {
                $item->cantidad_solicitada = $cantidad;
                $item->stock_disponible    = $stock;
            }

            $idInv = is_array($item) ? ($item['id_inventario_promo'] ?? null) : ($item->id_inventario_promo ?? null);

            $sum = 0;

            if ($idInv) {
                $det = $globals->getTabla([
                    'tabla' => 'salida_inventario_detalle',
                    'where' => [
                        'visible' => 1,
                        'id_inventario_promo' => intval($idInv)
                    ]
                ])->data ?? [];

                foreach ($det as $d) {
                    $sum += intval($d->cantidad_entregada ?? 0);
                }
            }

            // Guardar en el item para la vista
            if (is_array($item)) {
                $item['cantidad_solicitada'] = $sum;
            } else {
                $item->cantidad_solicitada = $sum;
            }

            $productos[$key] = $item;
        }

        // =============================
        // MÉTRICAS
        // =============================

        $data['total_stock_promo'] = 0;
        $data['total_subtotal_promo'] = 0;
        $data['total_dinero_promo'] = 0;

        foreach ($productos as $item) {

            // stock disponible (según tu estructura actual)
            $data['total_stock_promo'] += intval($item->stock ?? $item->total_existencia ?? 0);

            // ✅ ahora el subtotal/total vienen guardados (calculados por color)
            $sub = floatval($item->subtotal ?? 0);
            $tot = floatval($item->total ?? $sub);

            $data['total_subtotal_promo'] += $sub;
            $data['total_dinero_promo']   += $tot;
        }

        $data['id_convenio'] = (int)$id;
        $data['total_movimientos'] = 0;
        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'personal/vInventarioPromocion';

        $this->_renderView($data);
    }
    public function InventarioRecibosPromo($id = null)
    {
        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $globals = new Mglobal();

        $idConvenio = intval($id);

        // Info del contrato (header)
        $info = $globals->getTabla([
            'tabla' => 'vw_material_promo',
            'where' => [
                'visible' => 1,
                'id_material_promo' => $idConvenio
            ]
        ])->data ?? [];

        $data['materiales'] = $info[0] ?? null;

        // Recibos por contrato
        $recibos = $globals->getTabla([
            'tabla' => 'salida_inventario',
            'where' => [
                'visible' => 1,
                'id_convenio' => $idConvenio
            ],
            'order' => ['id_salida_inventario' => 'DESC']
        ])->data ?? [];

        $data['recibos'] = $recibos;

        // IDs homologados
        $data['id_convenio_promo'] = $idConvenio;
        $data['id_convenio'] = $idConvenio;

        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'personal/vInventarioRecibosPromo';

        $this->_renderView($data);
    }
    public function subirDocumentoRecibo()
    {
        $globals  = new Mglobal();
        $session  = \Config\Services::session();

        $dataBase = 'susi';

        $idRecibo = intval($this->request->getPost('id_salida_inventario'));
        $tipo     = trim((string)$this->request->getPost('tipo')); // oficio|evidencia|ine

        if ($idRecibo <= 0) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'ID de recibo inválido.'
            ]);
        }

        if (!in_array($tipo, ['oficio', 'evidencia', 'ine'], true)) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'Tipo de documento inválido.'
            ]);
        }

        // Traer recibo para conocer contrato/folio y validar existe
        $rec = $globals->getTabla([
            'tabla' => 'salida_inventario',
            'where' => [
                'visible' => 1,
                'id_salida_inventario' => $idRecibo
            ]
        ])->data[0] ?? null;

        if (!$rec) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'Recibo no encontrado.'
            ]);
        }

        $idConvenio = intval($rec->id_convenio ?? 0);
        $folio = (string)($rec->folio ?? ('RECIBO_' . $idRecibo));

        // Archivo
        $file = $this->request->getFile('archivo');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'No se recibió archivo válido.'
            ]);
        }

        // Validar tamaño <= 300 MB
        $maxBytes = 300 * 1024 * 1024;
        if (($file->getSize() ?? 0) > $maxBytes) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'El archivo excede 300 MB.'
            ]);
        }

        // Extensiones permitidas (ajústalo si quieres)
        $ext = strtolower((string)$file->getExtension());
        $permitidas = ['pdf','jpg','jpeg','png','webp'];
        if (!in_array($ext, $permitidas, true)) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'Formato no permitido. Usa PDF o imagen (JPG/PNG/WEBP).'
            ]);
        }

        // Carpeta destino: public/assets/docs_recibos/{idConvenio}/{folio}/
        $safeFolio = preg_replace('/[^A-Za-z0-9\-\_]/', '_', $folio);
        $uploadPathRel = 'assets/docs_recibos/' . $idConvenio . '/' . $safeFolio . '/';
        $uploadPathAbs = FCPATH . $uploadPathRel;

        if (!is_dir($uploadPathAbs)) {
            mkdir($uploadPathAbs, 0777, true);
        }

        $newName = $tipo . '_' . date('Ymd_His') . '_' . $file->getRandomName();
        if (!$file->move($uploadPathAbs, $newName)) {
            return $this->response->setJSON([
                'error' => true,
                'respuesta' => 'No se pudo guardar el archivo.'
            ]);
        }

        $rutaRel = $uploadPathRel . $newName;

        // Columna a actualizar en salida_inventario
        $colMap = [
            'oficio'    => 'archivo_oficio',
            'evidencia' => 'archivo_evidencia',
            'ine'       => 'archivo_ine'
        ];
        $col = $colMap[$tipo];

        $result = $globals->saveTabla(
            [$col => $rutaRel],
            [
                'dataBase' => $dataBase,
                'tabla'    => 'salida_inventario',
                'editar'   => true,
                'idEditar' => ['id_salida_inventario' => $idRecibo]
            ],
            ['script' => 'Inicio.php/subirDocumentoRecibo_' . strtoupper($tipo)]
        );

        if ($result && isset($result->error) && $result->error === false) {
            return $this->response->setJSON([
                'error' => false,
                'respuesta' => 'Archivo cargado correctamente.',
                'tipo' => $tipo,
                'ruta' => base_url($rutaRel),
                'ruta_rel' => $rutaRel
            ]);
        }

        return $this->response->setJSON([
            'error' => true,
            'respuesta' => $result->respuesta ?? 'No se pudo actualizar el recibo.',
            'debug' => $result
        ]);
    }

    // ==========================================
    // PROCESOS (AJAX)
    // ==========================================
    public function actualizarInventario()
    {
        $response = new \stdClass();
        $globals = new Mglobal;
        $response->error = true;

        $id_producto     = (int)$this->request->getPost('id_producto');
        $tabla           = (string)$this->request->getPost('tabla');
        $tipo_movimiento = (string)$this->request->getPost('tipo_movimiento');

        // Para movimientos tradicionales (entrada/salida) puede venir 'cantidad'
        // Para recalcular stock por colores, puede venir 'stock' (lo mandas desde JS)
        $cantidad = $this->request->getPost('cantidad');
        $stockPost = $this->request->getPost('stock');

        // Colores (arrays)
        $coloresPost    = $this->request->getPost('colores');      // ['#ffffff', ...]
        $cantidadesPost = $this->request->getPost('cantidades');   // ['2', '5', ...]

        if ($id_producto <= 0 || $tabla === '') {
            $response->respuesta = "Datos incompletos.";
            return $this->respond($response);
        }

        // Mapeo de IDs según la tabla
        switch ($tabla) {
            case 'cat_inventario_papel':
                $idGenerico = 'id_inventario_papel';
                break;
            case 'cat_inventario_art_papel':
                $idGenerico = 'id_inventario_art_papel';
                break;
            case 'cat_inventario_art_ofi':
                $idGenerico = 'id_inventario_art_ofi';
                break;
            case 'cat_inventario_limpieza':
                $idGenerico = 'id_inventario_lim';
                break;
            case 'cat_inventario_promo':
                $idGenerico = 'id_inventario_promo';
                break;
            default:
                $idGenerico = '';
                break;
        }

        if ($idGenerico === '') {
            $response->respuesta = "Tabla no reconocida.";
            return $this->respond($response);
        }

        // Obtener producto
        $producto = $globals->getTabla([
            'tabla' => $tabla,
            'where' => [$idGenerico => $id_producto]
        ]);

        if (empty($producto->data)) {
            $response->respuesta = "Producto no encontrado.";
            return $this->respond($response);
        }

        $registro = $producto->data[0];
        $stockActual = (int)(is_array($registro) ? ($registro['stock'] ?? 0) : ($registro->stock ?? 0));

        // =========================================================
        // 1) LIMPIAR/ACTUALIZAR COLORES (SI LLEGAN)
        // =========================================================
        $dataUpdate = [];

        $coloresJsonArr = [];
        $stockDesdeColores = 0;

        if (is_array($coloresPost) && is_array($cantidadesPost)) {

            foreach ($coloresPost as $i => $hex) {
                $hex = trim((string)$hex);
                if ($hex === '') continue;

                $qty = (int)($cantidadesPost[$i] ?? 0);
                if ($qty < 0) $qty = 0;

                $coloresJsonArr[] = [
                    'hexadecimal' => $hex,
                    'cantidad'    => $qty
                ];

                $stockDesdeColores += $qty;
            }

            $colorJson = json_encode($coloresJsonArr, JSON_UNESCAPED_UNICODE);
            if ($colorJson === false) $colorJson = '[]';

            // Guardamos el JSON de colores
            $dataUpdate['color'] = $colorJson;

            // Si tu regla es: "stock = suma de cantidades por color", entonces:
            // (esto normalmente aplica en editar/nuevo, NO en salida)
            if ($tipo_movimiento !== 'salida') {
                $dataUpdate['stock'] = $stockDesdeColores;
                $dataUpdate['total_existencia'] = $stockDesdeColores;
            }
        }

        // =========================================================
        // 2) MOVIMIENTO CLÁSICO DE STOCK (entrada/salida)
        // =========================================================
        // Si llega cantidad explícita, úsala, si no, usa stockPost
        $cantidadMov = null;
        if ($cantidad !== null && $cantidad !== '') {
            $cantidadMov = (int)$cantidad;
        } elseif ($stockPost !== null && $stockPost !== '') {
            $cantidadMov = (int)$stockPost;
        }

        // Si NO llegó nada y tampoco hay update por colores, no hacemos nada
        if ($cantidadMov === null && empty($dataUpdate)) {
            $response->respuesta = "No se recibió ningún cambio.";
            return $this->respond($response);
        }

        // Si hay movimiento salida/entrada, ajusta stockActual.
        // Ojo: si estás editando por colores, ya pudimos setear stock directamente arriba.
        if ($cantidadMov !== null && $tipo_movimiento !== '') {

            if ($cantidadMov <= 0) {
                $response->respuesta = "Cantidad inválida.";
                return $this->respond($response);
            }

            // Si ya se recalculó stock por colores, parte desde ese valor.
            $baseStock = array_key_exists('stock', $dataUpdate) ? (int)$dataUpdate['stock'] : $stockActual;

            $nuevoStock = ($tipo_movimiento === 'salida')
                ? ($baseStock - $cantidadMov)
                : ($baseStock + $cantidadMov);

            if ($nuevoStock < 0) {
                $response->respuesta = "No hay suficiente stock.";
                return $this->respond($response);
            }

            $dataUpdate['stock'] = $nuevoStock;
            $dataUpdate['total_existencia'] = $nuevoStock;

            if ($tipo_movimiento === 'salida') {
                $dataUpdate['fecha_salida'] = date('Y-m-d H:i:s'); // asegúrate que exista esa columna
            }
            if ($tipo_movimiento === 'entrada') {
                $dataUpdate['fecha_entrada'] = date('Y-m-d H:i:s');
            }
        }

        // =========================================================
        // GUARDAR
        // =========================================================
        $result = $globals->saveTabla($dataUpdate, [
            'tabla'   => $tabla,
            'editar'  => true,
            'idEditar'=> [$idGenerico => $id_producto]
        ], ['script' => 'Inicio.actualizarInventario']);

        if ($result) {
            $response->error = false;
            $response->respuesta = "Actualizado correctamente.";
        } else {
            $response->respuesta = "No se pudo actualizar.";
        }

        return $this->respond($response);
    }
    public function guardarProducto()
    {
        $response = new \stdClass();
        $globals  = new Mglobal;

        $response->error = true;

        $tabla    = 'cat_inventario_promo';
        $dataBase = 'susi'; // requerido por saveTabla()

        $tipo_movimiento = trim((string)$this->request->getPost('tipo_movimiento'));
        if ($tipo_movimiento === '') $tipo_movimiento = 'nuevo';

        $id_producto = intval($this->request->getPost('id_producto'));
        $id_convenio = intval($this->request->getPost('id_convenio'));

        $dsc_producto    = trim((string)$this->request->getPost('dsc_producto'));
        $cantidad        = intval($this->request->getPost('cantidad'));          // solicitado (nuevo/editar) o retiro (salida)
        $precio_unitario = floatval($this->request->getPost('precio_unitario'));
        if ($precio_unitario < 0) $precio_unitario = 0; // ya no es obligatorio

        $color     = (string)$this->request->getPost('color');       // JSON string
        $variantes = (string)$this->request->getPost('variantes');   // JSON string
        $stock     = intval($this->request->getPost('stock'));       // calculado en JS (nuevo/editar)

        // =============================
        // Validaciones base
        // =============================
        if ($id_convenio <= 0) {
            $response->respuesta = "Convenio/Requisición inválido.";
            return $this->respond($response);
        }

        // Validar padre existe
        $padre = $globals->getTabla([
            'tabla' => 'vw_material_promo',
            'where' => [
                'id_material_promo' => $id_convenio,
                'visible' => 1
            ]
        ]);

        if (empty($padre->data)) {
            $response->respuesta = "No existe la requisición/convenio seleccionado.";
            return $this->respond($response);
        }

        // =============================
        // SALIDA (baja de stock)
        // =============================
        if ($tipo_movimiento === 'salida') {

            if ($id_producto <= 0) {
                $response->respuesta = "Producto inválido para salida.";
                return $this->respond($response);
            }

            $retiro = max(0, $cantidad);
            if ($retiro <= 0) {
                $response->respuesta = "La cantidad de salida debe ser mayor a 0.";
                return $this->respond($response);
            }

            // Traer producto actual para leer stock
            $prod = $globals->getTabla([
                'tabla' => $tabla,
                'where' => [
                    'visible' => 1,
                    'id_inventario_promo' => $id_producto,
                    'id_convenio_promo'   => $id_convenio
                ]
            ]);

            if (empty($prod->data)) {
                $response->respuesta = "No se encontró el producto.";
                return $this->respond($response);
            }

            $row = $prod->data[0];
            $stockActual = intval($row->stock ?? 0);

            if ($retiro > $stockActual) {
                $response->respuesta = "Stock insuficiente. Disponible: {$stockActual}.";
                return $this->respond($response);
            }

            $nuevoStock = $stockActual - $retiro;

            $dataUpdate = [
                'stock' => $nuevoStock
            ];

            $dataConfig = [
                'dataBase' => $dataBase,
                'tabla'    => $tabla,
                'editar'   => true,
                'idEditar' => ['id_inventario_promo' => $id_producto]
            ];

            $result = $globals->saveTabla(
                $dataUpdate,
                $dataConfig,
                ['script' => 'Inicio.guardarProducto_SALIDA']
            );

            if ($result && isset($result->error) && $result->error === false) {
                $response->error = false;
                $response->respuesta = "Salida aplicada. Nuevo stock: {$nuevoStock}.";
                $response->stock = $nuevoStock;
            } else {
                $response->respuesta = $result->respuesta ?? "Error al aplicar salida.";
                $response->debug = $result;
            }

            return $this->respond($response);
        }

        // =============================
        // NUEVO / EDITAR (producto)
        // =============================
        if ($dsc_producto === '') {
            $response->respuesta = "El nombre del producto es obligatorio.";
            return $this->respond($response);
        }

        if ($tipo_movimiento === 'editar' && $id_producto <= 0) {
            $response->respuesta = "Producto inválido para editar.";
            return $this->respond($response);
        }

        // Normalizar JSON
        if ($color === '' || $color === 'null') $color = '[]';
        $colorArr = json_decode($color, true);
        if (!is_array($colorArr)) {
            $colorArr = [];
            $color = '[]';
        }

        if ($variantes === '' || $variantes === 'null') $variantes = '[]';
        $varArr = json_decode($variantes, true);
        if (!is_array($varArr)) {
            $varArr = [];
            $variantes = '[]';
        }

        // Recalcular stock desde colores (backend manda)
        $stockCalc = 0;
        foreach ($colorArr as $c) {
            $stockCalc += intval($c['cantidad'] ?? 0);
        }
        $stock = ($stockCalc > 0) ? $stockCalc : max(0, $stock);

        // Normalizar
        $cantidad = max(0, $cantidad);
        $precio_unitario = max(0, $precio_unitario);

        // ✅ Subtotal/Total: si hay precio por color, se calcula por color;
        // si no, cae al cálculo viejo
        $subtotal = 0;
        $colArr = $colorArr; // ya lo tienes decodificado arriba

        if (is_array($colArr) && !empty($colArr)) {

            $tienePrecioPorColor = false;
            foreach ($colArr as $c) {
                if (isset($c['precio']) && $c['precio'] !== '' && floatval($c['precio']) > 0) {
                    $tienePrecioPorColor = true;
                    break;
                }
            }

            if ($tienePrecioPorColor) {
                foreach ($colArr as $c) {
                    $qty = intval($c['cantidad'] ?? 0);
                    $prc = floatval($c['precio'] ?? $precio_unitario); // fallback al base
                    $subtotal += max(0, $qty) * max(0, $prc);
                }
            } else {
                // sin precios por color → cálculo viejo
                $subtotal = $cantidad * $precio_unitario;
            }

        } else {
            // sin colores → cálculo viejo
            $subtotal = $cantidad * $precio_unitario;
        }

        $total = $subtotal;

        $dataSave = [
            'id_convenio_promo' => $id_convenio,
            'dsc_producto'      => $dsc_producto,
            'cantidad'          => $cantidad, // solicitado
            'stock'             => $stock,    // disponible
            'precio_unitario'   => $precio_unitario,
            'subtotal'          => $subtotal,
            'total'             => $total,
            'color'             => $color,
            'variantes'         => $variantes,
            'visible'           => 1
        ];

        if ($tipo_movimiento === 'nuevo') {
            $dataSave['fecha_entrada'] = date('Y-m-d H:i:s');
        }

        // Imagen solo si viene
        $file = $this->request->getFile('imagen');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName    = $file->getRandomName();
            $uploadPath = 'assets/img_productos/';

            if (!is_dir(FCPATH . $uploadPath)) {
                mkdir(FCPATH . $uploadPath, 0777, true);
            }

            if ($file->move(FCPATH . $uploadPath, $newName)) {
                $dataSave['imagen'] = $uploadPath . $newName;
            }
        }

        $dataConfig = [
            'dataBase' => $dataBase,
            'tabla'    => $tabla,
            'editar'   => ($tipo_movimiento === 'editar')
        ];

        if ($tipo_movimiento === 'editar') {
            $dataConfig['idEditar'] = ['id_inventario_promo' => $id_producto];
        }

        $result = $globals->saveTabla(
            $dataSave,
            $dataConfig,
            ['script' => 'Inicio.guardarProducto_' . strtoupper($tipo_movimiento)]
        );

        if ($result && isset($result->error) && $result->error === false) {
            $response->error = false;
            $response->respuesta = "Producto guardado correctamente.";
            $response->id_inventario_promo = intval($result->idRegistro ?? $result->insertId ?? 0);
        } else {
            $response->respuesta = $result->respuesta ?? "Error al guardar el producto.";
            $response->debug = $result;
        }

        return $this->respond($response);
    }
    public function eliminarProducto()
    {
        $response = new \stdClass();
        $globals = new Mglobal;
        $response->error = true;

        $id_producto = $this->request->getPost('id');
        $tabla = $this->request->getPost('tabla');

        switch ($tabla) {
            case 'cat_inventario_papel':
                $idGenerico = 'id_inventario_papel';
                break;
            case 'cat_inventario_art_papel':
                $idGenerico = 'id_inventario_art_papel';
                break;
            case 'cat_inventario_art_ofi':
                $idGenerico = 'id_inventario_art_ofi';
                break;
            case 'cat_inventario_limpieza':
                $idGenerico = 'id_inventario_lim';
                break;
            case 'cat_inventario_promo':
                $idGenerico = 'id_inventario_promo';
                break;
            default:
                $idGenerico = '';
                break;
        }

        if (!$idGenerico) {
            $response->respuesta = "Error de configuración de tabla.";
            return $this->respond($response);
        }

        $result = $globals->saveTabla(['visible' => 0], [
            'tabla' => $tabla,
            'editar' => true,
            'idEditar' => [$idGenerico => $id_producto]
        ], ['script' => 'Inicio.eliminarProducto']);

        if ($result) {
            $response->error = false;
            $response->respuesta = "Eliminado correctamente.";
        }

        return $this->respond($response);
    }

    public function exportarHistorialEntregasExcel($idConvenio = null)
    {
        $globals = new Mglobal();

        $idConvenio = intval($idConvenio);
        if ($idConvenio <= 0) {
            return redirect()->back();
        }

        // ============================================================
        // 1) Cabeceras (salida_inventario)
        // ============================================================
        $cab = $globals->getTabla([
            'tabla' => 'salida_inventario',
            'where' => [
                'visible' => 1,
                'id_convenio' => $idConvenio
            ]
            
        ]);

        if (!empty($cab->error)) {
            return redirect()->back()->with('error', $cab->respuesta ?? 'Error al consultar recibos');
        }

        $cabeceras = $cab->data ?? [];

        // Index cabeceras por id para lookup rápido
        $mapCab = [];
        $idsSalida = [];
        foreach ($cabeceras as $c) {
            $idSalida = intval($c->id_salida_inventario ?? 0);
            if ($idSalida > 0) {
                $mapCab[$idSalida] = $c;
                $idsSalida[] = $idSalida;
            }
        }

        // ============================================================
        // 2) Productos del contrato (1 sola llamada) para mapa id->nombre
        // ============================================================
        $pro = $globals->getTabla([
            'tabla' => 'cat_inventario_promo',
            'where' => [
                'visible' => 1,
                'id_convenio_promo' => $idConvenio
            ]
        ]);

        $mapProd = []; // id_inventario_promo => dsc_producto
        if (empty($pro->error) && !empty($pro->data)) {
            foreach ($pro->data as $p) {
                $mapProd[intval($p->id_inventario_promo ?? 0)] = (string)($p->dsc_producto ?? '');
            }
        }

        // ============================================================
        // 3) Detalles (N llamadas, porque no hay where_in)
        // ============================================================
        $rows = [];

        // Ordenar idsSalida DESC para que el Excel salga del más nuevo al más viejo
        rsort($idsSalida);

        foreach ($idsSalida as $idSalida) {
            $det = $globals->getTabla([
                'tabla' => 'salida_inventario_detalle',
                'where' => [
                    'visible' => 1,
                    'id_salida_inventario' => $idSalida
                ]
            ]);

            if (!empty($det->error) || empty($det->data)) {
                continue;
            }

            $cabRow = $mapCab[$idSalida] ?? null;

            $folio = $cabRow ? (string)($cabRow->folio ?? '') : '';
            $fecRegRaw = $cabRow ? ($cabRow->fec_reg ?? null) : null;

            $fecha = '';
            if (!empty($fecRegRaw)) {
                try {
                    $dt = new \DateTime($fecRegRaw);
                    $fecha = $dt->format('d/m/Y H:i');
                } catch (\Exception $e) {
                    $fecha = (string)$fecRegRaw;
                }
            }

            $solicitante = $cabRow ? (string)($cabRow->nombre_solicitante ?? '') : '';
            $concepto    = $cabRow ? (string)($cabRow->concepto ?? '') : '';

            foreach ($det->data as $d) {
                $idProd = intval($d->id_inventario_promo ?? 0);
                $producto = $mapProd[$idProd] ?? ('ID#' . $idProd);

                $cantEntregada = intval($d->cantidad_entregada ?? 0);
                $stockDespuesRaw = $d->stock_despues ?? null;
                $stockDespues = ($stockDespuesRaw === null || $stockDespuesRaw === '') ? '' : intval($stockDespuesRaw);

                $rows[] = [
                    'folio' => $folio,
                    'fecha' => $fecha,
                    'solicitante' => $solicitante,
                    'producto' => $producto,
                    'cantidad_entregada' => $cantEntregada,
                    'stock_despues' => $stockDespues,
                    'concepto' => $concepto
                ];
            }
        }

        // ============================================================
        // 4) XLSX
        // ============================================================
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Historial');

        $headers = [
            'Folio',
            'Fecha',
            'Solicitante',
            'Producto',
            'Cantidad entregada',
            'Stock después',
            'Concepto'
        ];

        $col = 1;
        foreach ($headers as $h) {
            $sheet->setCellValueByColumnAndRow($col, 1, $h);
            $col++;
        }
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        $r = 2;
        foreach ($rows as $row) {
            $sheet->setCellValue('A'.$r, $row['folio']);
            $sheet->setCellValue('B'.$r, $row['fecha']);
            $sheet->setCellValue('C'.$r, $row['solicitante']);
            $sheet->setCellValue('D'.$r, $row['producto']);
            $sheet->setCellValue('E'.$r, $row['cantidad_entregada']);
            $sheet->setCellValue('F'.$r, $row['stock_despues']);
            $sheet->setCellValue('G'.$r, $row['concepto']);
            $r++;
        }

        foreach (range('A','G') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $filename = 'Historial_Entregas_Contrato_' . $idConvenio . '_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="'.$filename.'"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($this->writeSpreadsheetToString($writer));
    }

    private function writeSpreadsheetToString(\PhpOffice\PhpSpreadsheet\Writer\Xlsx $writer)
    {
        ob_start();
        $writer->save('php://output');
        return ob_get_clean();
    }

    public function ListadoSolicitudes()
    {
        $session = \Config\Services::session();
        $globas = new Mglobal;
        $vista = 'personal/vInicio';
        if(in_array($session->get('id_perfil'), [1,2])){
            $data['datos'] = $globas->getTabla(['tabla' => 'vw_solicitud_grc', 'where' => ['visible' => 1]])->data;
        }else{
            $data['datos'] = $globas->getTabla(['tabla' => 'vw_solicitud_grc', 'where' => ['visible' => 1, 'usu_reg' => $session->get('id_usuario')]])->data;
        }
        $data['scripts'] = array('principal', 'inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'personal/vListaSolicitudes';
        $this->_renderView($data);

    }
    public function Viaticos()
    {
        $session = \Config\Services::session();
        $data = array();
        $globas = new Mglobal;
        if (!in_array($session->get('id_perfil'), [1, 7])) {
            $data['contentView'] = 'secciones/vError500';
            $data['layout'] = 'plantilla/lytVacio';
            $this->_renderView($data);
            die();
        }

        $data['cat_funcionario'] = $globas->getTabla(['tabla' => 'cat_tipo_funcionario', 'where' => ['visible' => 1]])->data;
        $data['cat_gasto'] = $globas->getTabla(['tabla' => 'cat_gasto', 'where' => ['visible' => 1]])->data;
        $data['cat_viaje'] = $globas->getTabla(['tabla' => 'cat_viaje', 'where' => ['visible' => 1]])->data;
        $data['cat_pais'] = $globas->getTabla(['tabla' => 'cat_pais', 'where' => ['visible' => 1]])->data;
        $data['cat_estado'] = $globas->getTabla(['tabla' => 'cat_estado', 'where' => ['visible' => 1]])->data;
        $data['cat_municipios'] = $globas->getTabla(['tabla' => 'cat_municipios', 'where' => ['visible' => 1]])->data;
        $data['deno_puesto'] = $globas->getTabla(['tabla' => 'deno_puesto', 'where' => ['visible' => 1]])->data;
        $data['deno_cargo'] = $globas->getTabla(['tabla' => 'deno_cargo', 'where' => ['visible' => 1]])->data;
        $data['cat_area'] = $globas->getTabla(['tabla' => 'cat_area_adscripcion', 'where' => ['visible' => 1]])->data;
        // die( var_dump( $data['deno_puesto'] ) );
        $data['usuarios'] = $globas->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]])->data;
        $data['scripts'] = array('inicio', 'principal');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vViaticos';
        $this->_renderView($data);

    }
    public function ListaInventario()
    {
        $session = \Config\Services::session();
        $data = array();
        $globas = new Mglobal;
        $data['inventario'] = $globas->getTabla(['tabla' => 'vw_inventario', 'where' => ['visible' => 1]])->data;
        $data['cat_perfil'] = $globas->getTabla(['tabla' => 'perfil', 'where' => ['visible' => 1]])->data;
        $data['cat_area'] = $globas->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]])->data;
        $data['usuario'] = $globas->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]])->data;

        $data['scripts'] = array('principal', 'inicio');
        $data['contentView'] = 'personal/vListaInventario';
        $this->_renderView($data);

    }
    public function Servicios()
    {
        $session = \Config\Services::session();
        $data = array();
        $globas = new Mglobal;
        $data['cat_servicio'] = $globas->getTabla(['tabla' => 'cat_servicio', 'where' => ['visible' => 1]])->data;
        $data['cat_mes'] = $globas->getTabla(['tabla' => 'cat_mes', 'where' => ['visible' => 1]])->data;

        // --- NEW LOGIC FOR MATERIALES FORM ---
        $data['id_reserva'] = null;
        $data['editar'] = 0;
        $data['es2025'] = false;
        $data['registro_pt'] = new stdClass();
        $data['periodo_factura'] = new stdClass();
        $data['proveedor'] = new stdClass();
        $data['proveedor_banco'] = []; 

        $data['no_consecutivo'] = '';

        $data['periodo_factura']->encabezado = '';
        $data['periodo_factura']->proyecto_clave = ''; 
        $data['periodo_factura']->partida_clave = '';
        $data['periodo_factura']->importe = '';

        $data['no_reserva']="";
        $data['no_convenio']="";

        $formulario_pt = $globas->getTabla(["tabla" => "formulario_pt", "where" => ["visible" => 1, 'usu_reg' => $session->get('id_usuario')], 'tipo_formato' => 'MATERIALES']);
        $no_consecutivo = count(is_array($formulario_pt->data)?$formulario_pt->data:[]) + 1 ;
        if (strlen($no_consecutivo) == 1) {
            $no_consecutivo = '00' . $no_consecutivo;
        }
        if (strlen($no_consecutivo) == 2) {
            $no_consecutivo = '0' . $no_consecutivo;
        }
        
        $data['consecutivo'] = $no_consecutivo;

        $proveedores = $globas->getTabla(["tabla" => "proveedor", "where" => ["visible" => 1],'limit' => 10]);
        $data['proveedores'] = $proveedores->data;

        $cat_area = $globas->getTabla(["tabla" => "cat_area", "where" => ["visible" => 1, 'id_pago' => 1]]);
        $data['cat_area'] = $cat_area->data;
        
        $cat_proyecto = $globas->getTabla(["tabla" => "cat_proyecto", "where" => ["visible" => 1]]);
        $data['cat_proyecto'] = $cat_proyecto->data;

        $cat_partida = $globas->getTabla(["tabla" => "cat_partida", "where" => ["visible" => 1]]);
        $data['cat_partida'] = $cat_partida->data;

        $usuarios = $globas->getTabla(["tabla" => "vw_usuario", "where" => ["visible" => 1]]);
        $data['usuarios'] = $usuarios->data;
        $usu = $globas->getTabla(["tabla" => "vw_usuario", "where" => ["visible" => 1, 'id_usuario' => $session->get('id_usuario')]]);

        if(isset($usu->data[0]->id_usuario)){
            $tieneArea = $globas->getTabla(["tabla" => "cat_area", "where" => ["visible" => 1, 'titular' => $usu->data[0]->id_usuario]]);
            if(isset($tieneArea->data) && !empty($tieneArea->data)){
                $data['id_area'] = $tieneArea->data[0]->id_area;
            }else{
                $tieneArea = $globas->getTabla(["tabla" => "cat_area", "where" => ["visible" => 1, 'titular' => $usu->data[0]->id_jefe_inmediato]]);
                if(isset($tieneArea->data) && !empty($tieneArea->data)){
                    $data['id_area'] = $tieneArea->data[0]->id_area;
                }else{
                        $data['id_area'] = isset($cat_area->data[0]->id_area) ? $cat_area->data[0]->id_area : 0;
                }
            }
        }
        $data['anio_actual'] = date('Y');

        $data['scripts'] = array('principal', 'inicio');
        $data['contentView'] = 'personal/vServicios';
        $this->_renderView($data);

    }
    public function Chat()
    {
        $session = \Config\Services::session();
        $data = array();
        $data['scripts'] = array('principal', 'inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vChat';
        $this->_renderView($data);

    }
    public function Preinscritos()
    {
        $session = \Config\Services::session();
        $data = array();


        $globas = new Mglobal;
        if ($session->id_perfil == 1) {
            $detenidos = $globas->getTabla(['tabla' => 'detenidos', 'where' => ['visible' => 1]]);
            $participantes = $globas->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1]]);
        }
        else {
            $detenidos = $globas->getTabla(['tabla' => 'detenidos', 'where' => ['visible' => 1, 'id_dependencia' => $session->id_dependencia]]);
            $participantes = $globas->getTabla(['tabla' => 'participantes', 'where' => ['visible' => 1, 'id_dependencia' => $session->id_dependencia]]);
        }

        $dataDB = array('tabla' => 'cat_nivel', 'where' => ['visible' => 1]);
        $dependenciaDB = array('tabla' => 'cat_dependencia', 'where' => ['visible' => 1]);
        $perfilDB = array('tabla' => 'cat_perfil', 'where' => ['visible' => 1]);
        $cat_nivel = $globas->getTabla($dataDB);
        $cat_dependencia = $globas->getTabla($dependenciaDB);
        $cat_perfil = $globas->getTabla($perfilDB);
        $cat_municipio = $globas->getTabla(['tabla' => 'cat_municipio', 'where' => ['visible' => 1]]);

        $data['cat_nivel'] = $cat_nivel->data;
        $data['cat_dependencia'] = $cat_dependencia->data;
        $data['cat_perfil'] = $cat_perfil->data;
        $data['cat_municipio'] = $cat_municipio->data;
        $data['detenidos'] = (isset($detenidos) && !empty($detenidos)) ? $detenidos->data : [];
        $data['participantes'] = (isset($participantes) && !empty($participantes)) ? $participantes->data : [];
        $data['scripts'] = array('agregar', 'inicio');
        $data['contentView'] = 'secciones/vPreinscritos';
        $this->_renderView($data);

    }
    public function listaIncidencia()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;

        if (!in_array($session->get('id_perfil'), [1, 3])) {
            $data['contentView'] = 'secciones/vError500';
            $data['layout'] = 'plantilla/lytVacio';
            $this->_renderView($data);
            die();
        }
        // Obtener categorías desde la base de datos
        $Periodo = ['tabla' => 'cat_periodo', 'where' => ['visible' => 1]];
        $dataDB = ['tabla' => 'vw_incidenica', 'where' => ['visible' => 1]];
        $dataInci = ['tabla' => 'cat_incidencia', 'where' => ['visible' => 1]];
        $usuario = [
            'tabla' => 'vw_incidenica',
            'select' => ['id_usuario', 'nombre_completo'],
            'where' => ['visible' => 1],
            'groupBy' => ['id_usuario']
        ];


        $response = $principal->getTabla($dataDB);
        $incidencia = $principal->getTabla($dataInci);
        $periodo = $principal->getTabla($Periodo);
        $usuario = $principal->getTabla($usuario);

        $data['incidencia'] = (isset($response->data) && !empty($response->data)) ? $response->data : [];
        $data['cat_incidencia'] = (isset($incidencia->data) && !empty($incidencia->data)) ? $incidencia->data : [];
        $data['periodo'] = (isset($periodo->data) && !empty($periodo->data)) ? $periodo->data : [];
        $data['usuario'] = (isset($usuario->data) && !empty($usuario->data)) ? $usuario->data : [];
       // die( var_dump($data['incidencia']) );
        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vListaIncidencia';
        $this->_renderView($data);
    }
    public function altaUsuario()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        if ($session->id_perfil == 8) {
            header('Location:' . base_url() . 'index.php/');
            die();
        }
        $usuario = $principal->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        $cat_perfil = $principal->getTabla(['tabla' => 'perfil', 'where' => ['visible' => 1]]);
        $cat_area = $principal->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        $cat_puesto = $principal->getTabla(['tabla' => 'cat_puesto', 'where' => ['visible' => 1]]);
        $tipo_empleado = $principal->getTabla(['tabla' => 'cat_tipo_empleado ', 'where' => ['visible' => 1]]);


        $data['editar'] = 0;
        $data['cat_perfil'] = $cat_perfil->data;
        $data['cat_area'] = $cat_area->data;
        $data['cat_puesto'] = $cat_puesto->data;
        $data['tipo_empleado'] = $tipo_empleado->data;
        $data['usuario'] = (isset($usuario->data) && !empty($usuario->data)) ? $usuario->data : [];
        $data['scripts'] = ['principal', 'agregar'];
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
        $cat_perfil = $principal->getTabla(['tabla' => 'perfil', 'where' => ['visible' => 1]]);
        $cat_area = $principal->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        $cat_puesto = $principal->getTabla(['tabla' => 'cat_puesto', 'where' => ['visible' => 1]]);
        $tipo_empleado = $principal->getTabla(['tabla' => 'cat_tipo_empleado', 'where' => ['visible' => 1]]);

        $data['cat_perfil'] = $cat_perfil->data;
        $data['cat_area'] = $cat_area->data;
        $data['cat_puesto'] = $cat_puesto->data;
        $data['tipo_empleado'] = $tipo_empleado->data;
        // Asignar datos adicionales
        $data['usuario'] = isset($usuario->data) && !empty($usuario->data) ? $usuario->data : [];
        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vUsuarios';

        // Renderizar la vista
        $this->_renderView($data);
    }
    public function posada()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;

        // Mapeo de casos para obtener usuarios según el perfil
        $usuarioQuery = [
            'tabla' => 'posada',
            'where' => ['visible' => 1]
        ];
        // Obtener usuarios
        $usuario = $principal->getTabla($usuarioQuery);

        // Asignar datos adicionales
        $data['usuario'] = isset($usuario->data) && !empty($usuario->data) ? $usuario->data : [];
        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vPosada';

        // Renderizar la vista
        $this->_renderView($data);
    }
    public function listaPerfil()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $cat_perfil = $principal->getTabla(['tabla' => 'perfil', 'where' => ['visible' => 1]]);
        $data['cat_perfil'] = (isset($cat_perfil->data) && !empty($cat_perfil->data)) ? $cat_perfil->data : [];
        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vlistaPerfiles';
        $this->_renderView($data);
    }
    public function subirAsistencia()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $cat_usuario = $principal->getTabla([
            'tabla' => 'vw_asistencia',
            'where' => ['visible' => 1, 'id_tipo_empleado' => 1]
        ]);
        //die( var_dump( $cat_usuario   )  );
        $Periodo = ['tabla' => 'cat_periodo', 'where' => ['visible' => 1]];
        $periodo = $principal->getTabla($Periodo);
        $data['periodo'] = (isset($periodo->data) && !empty($periodo->data)) ? $periodo->data : [];
        // Enviar datos a la vista
        $data['mes'] = date('m');
        $data['cat_usuario'] = $cat_usuario->data ?? [];

        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vSubirAsistencia';
        $this->_renderView($data);
    }
    public function vehiculos()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $vehiculos = $principal->getTabla([
            'tabla' => 'vehiculo',
            'where' => ['visible' => 1]
        ]);
        $usuario = $principal->getTabla([
            'tabla' => 'vw_usuario',
            'where' => ['visible' => 1]
        ]);
        $data['vehiculos'] = $vehiculos->data ?? [];
        $data['usuario'] = $usuario->data ?? [];
        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vVehiculos';
        $this->_renderView($data);
    }
    public function vehiculosPT()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $vehiculos = $principal->getTabla([
            'tabla' => 'pt_vehiculo',
            'where' => ['visible' => 1]
        ]);
        $data['vehiculos'] = $vehiculos->data ?? [];
        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vVehiculosLista';
        $this->_renderView($data);
    }

    // ==========================================
    // TIPO DE OPERACIÓN CRUD
    // ==========================================

    public function TipoOperacion()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;

        // Load Catalog for Deposito/Traspaso
        // Assuming cat_deposito exists. If not, user might need to create it.
        $cat_deposito = $globals->getTabla(['tabla' => 'vw_deposito', 'where' => ['visible' => 1]]);

        // Load existing operations
        // Assuming table 'operaciones' exists.
        if (in_array($session->get('id_perfil'), [1, 2])) {
            $operaciones = $globals->getTabla(['tabla' => 'operaciones', 'where' => ['visible' => 1]]);
        }
        else {
            $operaciones = $globals->getTabla(['tabla' => 'operaciones', 'where' => ['visible' => 1, 'usu_reg' => $session->get('id_usuario')]]);
        }

        // Load Users for Solicitante name
        $usuarios = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);

        $data['cat_deposito'] = $cat_deposito->data ?? [];
        $data['operaciones'] = $operaciones->data ?? [];
        $data['usuarios'] = $usuarios->data ?? [];

        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vTipoOperacion';
        $this->_renderView($data);
    }

    public function guardarTipoOperacion()
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $globals = new Mglobal;
        $response->error = true;

        $id_operacion = $this->request->getPost('id_operacion');
        $id_tipo_operacion = $this->request->getPost('id_tipo_operacion'); // 1=Deposito, 2=Traspaso, 3=Consulta Corte

        // Base Data
        $dataBase = [
            'id_tipo_operacion' => $id_tipo_operacion,
            'visible' => 1,
            'usu_reg' => $session->id_usuario ?? 0,
            'fec_reg' => date('Y-m-d H:i:s')
        ];

        // Specific fields based on type
        if ($id_tipo_operacion == 1) { // Deposito
            $dataSave = $dataBase;
            $dataSave['id_deposito'] = $this->request->getPost('id_deposito');
            $dataSave['importe'] = $this->request->getPost('importe2');

            // File Upload
            $file = $this->request->getFile('comprobante');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $uploadPath = FCPATH . 'assets/uploads/comprobantes';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);
                $dataSave['comprobante'] = 'assets/uploads/comprobantes/' . $newName;
            }

            $dataConfig = [
                'tabla' => 'operaciones',
                'editar' => !empty($id_operacion),
                'idEditar' => ['id_operacion' => $id_operacion]
            ];
            $result = $globals->saveTabla($dataSave, $dataConfig, ['script' => 'Inicio.guardarTipoOperacion']);

        }
        elseif ($id_tipo_operacion == 2) { // Traspaso
            // Recibimos arrays para destinos e importes
            $cuentas_dest = $this->request->getPost('cuenta_destino');
            $importes = $this->request->getPost('importe');
            $origen = $this->request->getPost('cuenta_traspaso');
            $justificacionBase = $this->request->getPost('justificaciones');

            if (!is_array($cuentas_dest)) {
                $cuentas_dest = [$cuentas_dest];
            }
            if (!is_array($importes)) {
                $importes = [$importes];
            }

            $errors = 0;
            foreach ($cuentas_dest as $idx => $destino) {
                if (empty($destino))
                    continue;
                $monto = isset($importes[$idx]) ? $importes[$idx] : 0;

                $dataSave = $dataBase;
                $dataSave['id_deposito'] = $origen; // La cuenta origen
                $dataSave['importe'] = $monto;
                // Guardamos el destino en justificación para referencia
                $dataSave['justificaciones'] = "Destino ID: " . $destino . ". " . $justificacionBase;

                // Si es edición, se asume que solo viene 1 registro (el array tiene tamaño 1)
                // y usamos $id_operacion. Si es nuevo, $id_operacion es vacio.
                $esEdicion = !empty($id_operacion);

                $dataConfig = [
                    'tabla' => 'operaciones',
                    'editar' => $esEdicion,
                    'idEditar' => ['id_operacion' => $id_operacion]
                ];

                $res = $globals->saveTabla($dataSave, $dataConfig, ['script' => 'Inicio.guardarTraspaso']);
                if ($res->error)
                    $errors++;

                // Si es edición, rompemos el ciclo tras el primero para no crear duplicados por error
                if ($esEdicion)
                    break;
            }

            $result = new \stdClass();
            $result->error = ($errors > 0);

        }
        elseif ($id_tipo_operacion == 3) { // Consulta Corte
            $dataSave = $dataBase;
            $dataSave['estado_cuenta'] = $this->request->getPost('estado_cuenta');
            $dataSave['periodo'] = $this->request->getPost('periodo');

            $dataConfig = [
                'tabla' => 'operaciones',
                'editar' => !empty($id_operacion),
                'idEditar' => ['id_operacion' => $id_operacion]
            ];
            $result = $globals->saveTabla($dataSave, $dataConfig, ['script' => 'Inicio.guardarTipoOperacion']);
        }

        if (!$result->error) {
            $response->error = false;
            $response->respuesta = "Operación guardada correctamente.";

            // Envio de Correo Notificación
            try {
                $email = \Config\Services::email();
                $destinatario = 'negonzalez@guanajuato.gob.mx';
                $email->setFrom('a.palafoxm@guanajuato.gob.mx', 'SUSI - Sistema Unificado SECTURI');
                $email->setTo($destinatario);
                $email->setSubject('Nueva Solicitud de Operación - SUSI');

                $nombreCompleto = $session->nombre_completo ?? 'Usuario Desconocido';
                $mensaje = "<h3>Nueva Solicitud de Operación</h3>";
                $mensaje .= "<p>El usuario <strong>{$nombreCompleto}</strong> ha subido una nueva solicitud de operación.</p>";
                $mensaje .= "<p>Por favor revise el sistema para más detalles.</p>";
                $mensaje .= "<a href='https://secturnet.guanajuato.gob.mx/susi/index.php/Inicio/TipoOperacion'>Por favor revise el sistema para más detalles.</a>";

                $email->setMessage($mensaje);
                if (!$email->send()) {
                    log_message('error', 'Error enviando correo: ' . $email->printDebugger(['headers']));
                }
            }
            catch (\Exception $e) {
                log_message('error', 'Error enviando correo notificación operación: ' . $e->getMessage());
            }
        }
        else {
            $response->respuesta = "Error al guardar la operación.";
        }



        return $this->respond($response);
    }
    public function guardarSeguimiento()
    {
        $response = new \stdClass();
        $globals = new Mglobal;
        $email = \Config\Services::email();
        $session = \Config\Services::session();
        $response->error = true;

        $id_operacion = $this->request->getPost('id_operacion');
        $seguimiento = $this->request->getPost('seguimiento');

        if (empty($id_operacion)) {
            $response->respuesta = "ID de operación no válido.";
            return $this->respond($response);
        }

        $dataSave = [
            'seguimiento' => $seguimiento
        ];

        // Manejo de archivo
        $file = $this->request->getFile('archivo');
        $filePath = '';
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = FCPATH . 'assets/uploads/seguimiento';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $filePath = 'assets/uploads/seguimiento/' . $newName;
            $dataSave['seguimiento_formato'] = $filePath;
        }

        $dataConfig = [
            'tabla' => 'operaciones',
            'editar' => true,
            'idEditar' => ['id_operacion' => $id_operacion]
        ];
        $correo = '';
        $usuRegistra = $globals->getTabla(['tabla' => 'operaciones', 'where' => ['id_operacion' => $id_operacion]])->data;
        if (isset($usuRegistra[0]->usu_reg) && !empty($usuRegistra[0]->usu_reg)) {
            $correo = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['id_usuario' => $usuRegistra[0]->usu_reg]])->data[0]->correo;
        }

        $result = $globals->saveTabla($dataSave, $dataConfig, ['script' => 'Inicio.guardarSeguimiento']);

        if (!$result->error) {
            $response->error = false;
            $response->respuesta = "Seguimiento guardado.";

            // Envio de Correo
            try {
                $destinatario = $correo ?? 'susi@guanajuato.gob.mx'; // Fallback
                $email->setFrom('a.palafoxm@guanajuato.gob.mx', 'SUSI - Sistema Unificado SECTURI');
                $email->setTo($destinatario);
                $email->setSubject('Actualización de Seguimiento - Operación #' . $id_operacion);

                $mensaje = "<h3>Se ha actualizado el seguimiento de la operación #{$id_operacion}</h3>";
                $mensaje .= "<p><strong>Seguimiento:</strong> {$seguimiento}</p>";
                $mensaje .= "<p><strong>Usuario:</strong> " . ($session->nombre_completo ?? 'Sistema') . "</p>";

                if ($filePath) {
                    $mensaje .= "<p>Se ha adjuntado un archivo de soporte.</p>";
                    $email->attach(FCPATH . $filePath);
                }

                $email->setMessage($mensaje);
                $email->send();
            }
            catch (\Exception $e) {
                log_message('error', 'Error enviando correo seguimiento: ' . $e->getMessage());
            }

        }
        else {
            $response->respuesta = "Error al guardar seguimiento.";
        }

        return $this->respond($response);
    }

    public function eliminarTipoOperacion()
    {
        $response = new \stdClass();
        $globals = new Mglobal;
        $response->error = true;

        $id_operacion = $this->request->getPost('id_operacion');

        $result = $globals->saveTabla(['visible' => 0], [
            'tabla' => 'operaciones',
            'editar' => true,
            'idEditar' => ['id_operacion' => $id_operacion]
        ], ['script' => 'Inicio.eliminarTipoOperacion']);

        if ($result) {
            $response->error = false;
            $response->respuesta = "Eliminado correctamente.";
        }
        else {
            $response->respuesta = "Error al eliminar.";
        }

        return $this->respond($response);
    }

    public function getTipoOperacion()
    {
        $globals = new Mglobal;
        $id = $this->request->getPost('id_operacion');

        $data = $globals->getTabla(['tabla' => 'operaciones', 'where' => ['id_operacion' => $id]]);

        if (!empty($data->data[0])) {
            return $this->response->setJSON($data->data[0]);
        }
        else {
            return $this->response->setJSON(['error' => 'No encontrado']);
        }
    }    /*   public function subirAsistencia()
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
        $principal = new Mglobal;

        $cat_puesto = $principal->getTabla(['tabla' => 'cat_puesto', 'where' => ['visible' => 1]]);
        $data['cat_puesto'] = (isset($cat_puesto->data) && !empty($cat_puesto->data)) ? $cat_puesto->data : [];
        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vlistaPuesto';
        $this->_renderView($data);
    }
    public function verXML($idFactura = null, $tipo = null)
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        if ($tipo == 'go') {
            $factura = $principal->getTabla(['tabla' => 'xml_go', 'where' => ['visible' => 1, 'id_xml' => $idFactura]]);
        }
        else {
            $factura = $principal->getTabla(['tabla' => 'factura_pdf', 'where' => ['visible' => 1, 'id_factura_pdf' => $idFactura]]);
        }
        $data['factura'] = (isset($factura->data) && !empty($factura->data)) ? $factura->data[0] : [];
        //  die( var_dump( $data['factura'] ) );
        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vFactura';
        $this->_renderView($data);
    }
    public function EditarPT($id_registro_pt)
    {
        $session = \Config\Services::session();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Error al traer los proveedor';
        $globals = new Mglobal;
        $siExisteIdReserva = $globals->getTabla(['tabla' => 'registro_pt', 'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]]);
        $btn = false;
        $partida4000 = false;
        $cat_area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        $id_reserva = (isset($siExisteIdReserva->data) && !empty($siExisteIdReserva->data)) ? $siExisteIdReserva->data[0]->id_reserva : '';
        $id_reponsable_solicitud = (isset($siExisteIdReserva->data) && !empty($siExisteIdReserva->data)) ? $siExisteIdReserva->data[0]->id_reponsable_solicitud : '';
        if ($id_reserva) {
            $reserva = $globals->getTabla(['tabla' => 'vw_reserva', 'where' => ['id_reserva' => $id_reserva]]);
            $consecutivo = $globals->getTabla(['tabla' => 'folio_direccion', 'where' => ['visible' => 1, 'id_direccion' => $id_reponsable_solicitud]]);
            //die( var_dump( $consecutivo ) );           
            $conse = (isset($consecutivo->data) && !empty($consecutivo->data)) ? $consecutivo->data[0]->no_consecutivo : '';
            $data['consecutivo'] = (int)$conse + 1;
            $presupuesto = $globals->getTabla(['tabla' => 'vw_pagos', 'where' => ['id_registro_pt' => $id_registro_pt]]);
            foreach ($presupuesto->data as $i => $p) {
                if ($p->id_partida >= 149 && $p->id_partida <= 248) {
                    $partida4000 = true;
                }
            }

        }

        if (!empty($id_registro_pt)) {

            $registro_pt = $globals->getTabla(['tabla' => 'vw_registro_pt', 'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]]);
            $importe = $globals->getTabla(['tabla' => 'periodo_factura', 'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]]);
            $factura_pdf = $globals->getTabla(['tabla' => 'factura_pdf', 'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]]);
            $factura = $globals->getTabla(['tabla' => 'factura', 'where' => ['visible' => 1, 'id_registro_pt' => $id_registro_pt]]);
            if (isset($importe->data) && !empty($importe->data)) {
                $data['importe'] = $importe->data;
            }
            if (isset($factura_pdf->data) && !empty($factura_pdf->data)) {
                $data['factura_pdf'] = $factura_pdf->data;
            }

            if (isset($factura->data) && !empty($factura->data)) {
                $data['factura'] = $factura->data;
            }

        }

        $secretario = $globals->getTabla(['tabla' => 'cat_secretario', 'where' => ['visible' => 1]]);
        $cat_tipo = $globals->getTabla(['tabla' => 'cat_tipo', 'where' => ['visible' => 1]]);

        $usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->get('id_usuario')]]);
        $cat_usuario = $globals->getTabla(['tabla' => 'usuario', 'where' => ['visible' => 1]]);
        $cat_director_general = $globals->getTabla(['tabla' => 'cat_director_general', 'where' => ['visible' => 1]]);
        $cat_opcion = $globals->getTabla(['tabla' => 'cat_opcion', 'where' => ['visible' => 1]]);
        $cat_partida = $globals->getTabla(['tabla' => 'cat_partida', 'where' => ['visible' => 1]]);
        $cat_proyecto = $globals->getTabla(['tabla' => 'cat_proyecto', 'where' => ['visible' => 1]]);
        $cat_subsecretario = $globals->getTabla(['tabla' => 'cat_subsecretario', 'where' => ['visible' => 1]]);
        if ($id_reserva != 0) {
            $data['reserva'] = (!empty($reserva->data)) ? $reserva->data[0] : [];
            $data['presupuesto'] = (!empty($presupuesto->data)) ? $presupuesto->data : [];
        }
        if (!empty($id_registro_pt)) {
            $data['registro_pt'] = (!empty($registro_pt->data)) ? $registro_pt->data[0] : [];
        }
        $datosGrupal = [];
        if (!empty($data['presupuesto'])) {
            foreach ($data['presupuesto'] as $key => $p) {
                // Clone the object to avoid modifying the original reference if it matters, 
                // but since we are building a new array $datosGrupal, we can just work with $p or a copy.
                // Let's use a new object to be safe and clean.
                $item = clone $p;

                $datos = $globals->getTabla(['tabla' => 'periodo_factura', 'where' => ['id_presupuesto' => $p->id_presupuesto, 'visible' => 1]]);

                $item->datos = [];

                if (isset($datos->data) && !empty($datos->data)) {
                    // Use the header from the first invoice if available
                    $item->encabezado = $datos->data[0]->encabezado ?? '';

                    foreach ($datos->data as $j => $d) {
                       // var_dump( $d->id_identificador);
                        $xml = $globals->getTabla(['tabla' => 'factura', 'where' => ['id_registro_pt' => $id_registro_pt, 'id_identificador' => $d->id_identificador, 'visible' => 1]]);
                        $factura = $globals->getTabla(['tabla' => 'factura_pdf', 'where' => ['id_registro_pt' => $id_registro_pt, 'id_identificador' => $d->id_identificador, 'visible' => 1]]);

                        $invoiceData = [
                            'id_periodo_factura' => $d->id_periodo_factura,
                            'id_registro_pt' => $d->id_registro_pt,
                            'id_presupuesto' => $d->id_presupuesto,
                            'encabezado' => $d->encabezado,
                            'importe' => $d->importe,
                            'concepto' => (isset($d->concepto)) ? $d->concepto : '',
                            'visible' => $d->visible,
                            'periodo_fin' => $d->periodo_fin,
                            'periodo_inicio' => $d->periodo_inicio,
                            'id_identificador' => $d->id_identificador,
                            'usu_reg' => $d->usu_reg,
                            'total' => (!empty($xml->data) && isset($xml->data[0]->total)) ? $xml->data[0]->total : 0,
                            'ruta_relativa' => (!empty($factura->data) && isset($factura->data[0]->ruta_relativa)) ? $factura->data[0]->ruta_relativa : ''
                        ];
                        $item->datos[] = $invoiceData;
                    }
                }
                $datosGrupal[] = $item;
            }
        }
       // die(   );
        $data['datosGrupal'] = $datosGrupal;
        $data['dsc_director_general'] = (!empty($cat_director_general->data)) ? $cat_director_general->data[0]->dsc_director_general : [];
        $data['cat_area'] = (!empty($cat_area->data)) ? $cat_area->data : [];
        $data['cat_tipo'] = (!empty($cat_tipo->data)) ? $cat_tipo->data : [];
        $data['cat_opcion'] = (!empty($cat_opcion->data)) ? $cat_opcion->data : [];
        $data['cat_subsecretario'] = (!empty($cat_subsecretario->data)) ? $cat_subsecretario->data : [];
        $data['cat_partida'] = (!empty($cat_partida->data)) ? $cat_partida->data : [];
        $data['cat_proyecto'] = (!empty($cat_proyecto->data)) ? $cat_proyecto->data : [];
        $data['editar'] = 1;
        $data['secretario'] = (!empty($secretario->data)) ? $secretario->data : [];
        $data['usuario'] = (!empty($usuario->data)) ? $usuario->data[0] : [];
        $data['cat_usuario'] = (!empty($cat_usuario->data)) ? $cat_usuario->data : [];
        $data['id_reserva'] = (!empty($id_reserva)) ? $id_reserva : 0;
        $data['scripts'] = array('inicio');
        $data['edita'] = $btn;
        $data['partida4000'] = $partida4000;
        $data['contentView'] = 'secciones/vRegistroEditarPT';
        // die( var_dump( $data['importe'])  );
        $this->_renderView($data);
    }
    public function EditarFIC($id_registro_pt = null)
    {
        $globals = new Mglobal;
        $session = \Config\Services::session();
        $idRegistroPT = $id_registro_pt;
        $registro_pt = $globals->getTabla(['tabla' => 'registro_pt', 'where' => ['visible' => 1, 'id_registro_pt' => $idRegistroPT]]);
        if (isset($registro_pt) && !empty($registro_pt)) {
            $data['datos'] = $registro_pt->data[0];
            $id_proveedor = $registro_pt->data[0]->id_proveedor;
            $id_reserva = $registro_pt->data[0]->id_reserva;
            $banco = $globals->getTabla(['tabla' => 'proveedor_banco', 'where' => ['visible' => 1, 'idproveedor' => $id_proveedor, 'fic' => 1]]);
            $reserva = $globals->getTabla(['tabla' => 'vw_reserva', 'where' => ['visible' => 1, 'id_reserva' => $id_reserva]]);

            $data['banco'] = (isset($banco) && !empty($banco)) ? $banco->data : '';
            $data['edita'] = 1;
            if (isset($reserva->data) && !empty($reserva->data)) {
                $data['reserva'] = $reserva->data[0];

            }
        }

        $cat_area = $globals->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        $secretario = $globals->getTabla(['tabla' => 'cat_secretario', 'where' => ['visible' => 1]]);
        $cat_subsecretario = $globals->getTabla(['tabla' => 'cat_subsecretario', 'where' => ['visible' => 1]]);
        $cat_tipo = $globals->getTabla(['tabla' => 'cat_tipo', 'where' => ['visible' => 1]]);

        $usuario = $globals->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1, 'id_usuario' => $session->get('id_usuario')]]);
        $cat_usuario = $globals->getTabla(['tabla' => 'usuario', 'where' => ['visible' => 1]]);
        $cat_director_general = $globals->getTabla(['tabla' => 'cat_director_general', 'where' => ['visible' => 1]]);
        $cat_opcion = $globals->getTabla(['tabla' => 'cat_opcion', 'where' => ['visible' => 1]]);


        $data['FIC'] = true;
        $data['dsc_director_general'] = (!empty($cat_director_general->data)) ? $cat_director_general->data[0]->dsc_director_general : [];
        $data['cat_area'] = (!empty($cat_area->data)) ? $cat_area->data : [];
        $data['cat_tipo'] = (!empty($cat_tipo->data)) ? $cat_tipo->data : [];
        $data['cat_opcion'] = (!empty($cat_opcion->data)) ? $cat_opcion->data : [];
        $data['secretario'] = (!empty($secretario->data)) ? $secretario->data : [];
        $data['subsecretario'] = (!empty($cat_subsecretario->data)) ? $cat_subsecretario->data : [];
        $data['usuario'] = (!empty($usuario->data)) ? $usuario->data[0] : [];
        $data['cat_usuario'] = (!empty($cat_usuario->data)) ? $cat_usuario->data : [];
        $data['scripts'] = array('inicio');
        //die( var_dump( $data['datos'] ) );
        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'personal/vFormularioEditarFic';
        $this->_renderView($data);


    }
    public function listaTiket()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        if ($session->get('id_perfil') == 1) {
            $tabla = array('tabla' => 'vw_tiket', 'where' => ['visible' => 1], 'orderBy' => 'id_tiket DESC');
        }
        else {
            $tabla = array('tabla' => 'vw_tiket', 'where' => ['id_usuario' => $session->get('id_usuario'), 'visible' => 1], 'orderBy' => 'id_tiket DESC');
        }

        $cat_tiket = $principal->getTabla($tabla);

        $data['cat_tiket'] = (isset($cat_tiket->data) && !empty($cat_tiket->data)) ? $cat_tiket->data : [];
        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vListaTiket';
        $this->_renderView($data);
    }
    public function listaArea()
    {
        $session = \Config\Services::session();
        $principal = new Mglobal;
        $cat_area = $principal->getTabla(['tabla' => 'cat_area', 'where' => ['visible' => 1]]);
        $usuario = $principal->getTabla(['tabla' => 'vw_usuario', 'where' => ['visible' => 1]]);
        $data['cat_area'] = (isset($cat_area->data) && !empty($cat_area->data)) ? $cat_area->data : [];
        $data['usuario'] = (isset($usuario->data) && !empty($usuario->data)) ? $usuario->data : [];
        $data['scripts'] = ['principal', 'inicio'];
        $data['contentView'] = 'secciones/vlistaArea';
        $this->_renderView($data);
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
                    'opened' => false, // Abrir el nodo si es necesario
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
                    $tree[] = & $categoryMap[$category->id];
                }
                else {
                    $categoryMap[$category->parent]->children[] = & $categoryMap[$category->id];
                }
            }
        }
        // insertamos las categorias padre 
        if ($session->get('id_perfil') == 1) {
            if (!empty($result->data)) {
                foreach ($result->data as $category) {
                    if ($category->parent == 0) { // Filtrar solo categorías padre
                        $dataInsert = [
                            'id_categoria' => (int)$category->id,
                            'dsc_categoria' => $category->name
                        ];

                        $dataBitacora = ['id_user' => $session->get('id_usuario'), 'script' => 'Agregar.php/guardaCategoriasPadre'];


                        $dataConfig = [
                            "tabla" => "categorias_padre",
                            "editar" => false
                        ];
                        $cat = $principal->getTabla(['tabla' => 'categorias_padre', 'where' => ['id_categoria' => (int)$category->id, 'visible' => 1]]);
                        if (isset($cat->data) && empty($cat->data)) {
                            $principal->saveTabla($dataInsert, $dataConfig, $dataBitacora);
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
            }
            else {

                $cursos[] = 'CURSO 2025'; // Valor por defecto si no hay activas
            }

            $tree = $this->filterTree($tree, $cursos); // Filtrar con múltiples categorías
        }


        // Formatear el árbol para jsTree
        $formattedTree = $this->formatForJsTree($tree);

        return $this->respond($formattedTree);
    }
    private function filterTree($tree, $cursos)
    {
        $filteredTree = [];

        foreach ($tree as $node) {
            // Verificar si el nodo actual está en el array de categorías permitidas
            if (in_array($node->name, $cursos)) {
                $filteredTree[] = $node;
            }
            elseif (!empty($node->children)) {
                // Filtrar los hijos si los hay
                $node->children = $this->filterTree($node->children, $cursos);
                if (!empty($node->children)) {
                    $filteredTree[] = $node;
                }
            }
        }

        return $filteredTree;
    }

    public function pdfTurno()
    {
        // $session = \Config\Services::session();
        setlocale(LC_TIME, 'es_ES');
        $catalogos = new Mglobal;
        $dataPage = [];
        $mpdf = new \Mpdf\Mpdf();
        $id_turno = $this->request->getGet('id_turno');
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
        $dataDB = array('select' => $select, 'tabla' => 'turno', 'where' => 'id_turno= "' . $id_turno . '" AND visible = 1');
        $response = $catalogos->getTabla($dataDB);

        $dataPage['id_turno'] = $response->data[0]->id_turno;
        $dataPage['anio'] = $response->data[0]->anio;
        $titulo = (empty($response->data[0]->solicitante_titulo)) ? '' : $response->data[0]->solicitante_titulo . ".";
        $dataPage['nombre_completo'] = $response->data[0]->solicitante_nombre . " " . $response->data[0]->solicitante_primer_apellido . " " . $response->data[0]->solicitante_segundo_apellido;
        $dataPage['cargo'] = $response->data[0]->solicitante_cargo;
        $dataPage['razon_social'] = $response->data[0]->solicitante_razon_social;
        $dataPage['resumen'] = $response->data[0]->resumen;

        $dataPage['fecha_recepcion'] = strftime('%d/%b/%y', strtotime($response->data[0]->fecha_recepcion));
        ;

        $dataDB = array('select' => 'dsc_asunto', 'tabla' => 'cat_asuntos', 'where' => 'id_asunto= "' . $response->data[0]->id_asunto . '" AND visible = 1');
        $responseAsunto = $catalogos->getTabla($dataDB);
        $dataPage['asunto'] = $responseAsunto->data[0]->dsc_asunto;

        $dataDB = array('select' => 'usuario', 'tabla' => 'seg_usuarios', 'where' => 'id_usuario= "' . $response->data[0]->usuario_registro . '" AND visible = 1');
        $responseUsuario = $catalogos->getTabla($dataDB);
        $dataPage['usuario_registro'] = $responseUsuario->data[0]->usuario;
        // turnado a: 
        $dataDB = array('select' => 'nombre_destinatario,cargo', 'tabla' => 'cat_destinatario', 'where' => 'id_destinatario IN (SELECT id_destinatario FROM `turno_destinatario` WHERE id_turno ="' . $id_turno . '")');
        $responseIndicacion = $catalogos->getTabla($dataDB);
        $dataPage['turnado'] = $responseIndicacion->data;
        // con indicaciones
        $dataDB = array('select' => 'dsc_indicacion', 'tabla' => 'cat_indicaciones', 'where' => 'id_indicacion IN (SELECT id_indicacion FROM `turno_indicacion` WHERE id_turno ="' . $id_turno . '")');
        $responseIndicacion = $catalogos->getTabla($dataDB);
        $dataPage['indicaciones'] = $responseIndicacion->data;
        //  var_dump($responseCopia->data);
        //   die();
        // con copia
        $dataDB = array('select' => 'nombre_destinatario', 'tabla' => 'cat_destinatario', 'where' => 'id_destinatario IN (SELECT id_destinatario FROM `turno_con_copia` WHERE id_turno = "' . $id_turno . '")');
        $responseCopia = $catalogos->getTabla($dataDB);
        $dataPage['destinatarioCopia'] = $responseCopia->data;
        //  var_dump($responseCopia->data);
        //   die();

        $dataImagen = $this->encode_img_base64(FCPATH . 'assets/images/formato.png', 'png');
        $mpdfConfig = [
            'fontDir' => FCPATH . 'assets/fonts/custom/',
            'fontdata' => [
                'proxima-nova' => [
                    'R' => 'proxima-nova.ttf',
                ],
            ],
        ];

        $mpdf = new \Mpdf\Mpdf($mpdfConfig);

        $html = view("pdfs/vpdfTurno.php", ["dataPage" => $dataPage, "dataImagen" => $dataImagen]);
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



    public function pdfLiberacionPago()
    {
        {
        $globals = new Mglobal;
        $id = $this->request->getGet('id');
        $data = [];

        if ($id) {
            $registro = $globals->getTabla(["tabla" => "formulario_pt", "where" => ["id_formulario_pt" => $id]]);
       
            if (!empty($registro->data)) {
                 if($registro->data[0]->nombre_proveedor_1 > 0){
                        $proveedor = $globals->getTabla(["tabla" => "proveedor", "where" => ["id_proveedor" => $registro->data[0]->nombre_proveedor_1]]);
                        $registro->data[0]->nombre_proveedor_1 = $proveedor->data[0]->razon_social;
                        $data['proveedor'] = $proveedor->data[0];

                     
                    }
               // $proveedor = $globals->getTabla(["tabla" => "proveedor", "where" => ["id_proveedor" => $registro->data[0]->nombre_proveedor_1]]);
                //$registro->data[0]->nombre_proveedor_1 = $proveedor->data[0]->razon_social;
                $data['registro_pt'] = $registro->data[0];
                $items = $globals->getTabla(["tabla" => "manual_factura", "where" => ["id_registro_pt" => $id , "visible" => 1]]);
                   foreach($items->data as $item){
                            $partida = $globals->getTabla([
                                "tabla" => "cat_partida",
                                "where" => ["cuenta_cable" => $item->partida, 'visible' => 1]
                            ]);
                            $proyecto = $globals->getTabla([
                                "tabla" => "cat_proyecto",
                                "where" => ["proyecto" => $item->proyecto, 'visible' => 1]
                            ]);
                            $item->dsc_partida = (isset($partida->data[0])) ? $partida->data[0]->nombre_fondo : '';
                            $item->dsc_proyecto = (isset($proyecto->data[0])) ? $proyecto->data[0]->dsc_proyecto : '';
                        }
                $data['periodo_factura_rows'] = $items->data;
                $data['edit'] = 1; // For view logic if reused
            }
        }

        // For mPDF, passing local absolute path is better and avoids base64 issues
        $data['logo'] = FCPATH . 'assets/logo-guanajuato.png';
        $data['norma'] = FCPATH . 'assets/Norma.png';
        //die( var_dump($data['logo']) ); // Debug if needed
       // die( var_dump($data) );
        $html = view('pdfs/vPdfLiberacionPago', $data);

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'format' => 'Letter',
            'tempDir' => sys_get_temp_dir().DIRECTORY_SEPARATOR.'mpdf'
        ]);
        
        $mpdf->WriteHTML($html);
        $mpdf->Output('LiberacionPago_' . $id . '.pdf', 'I');
        exit;
    }
    }
    public function ListaHojaAzul()
    {
        $session = \Config\Services::session();
        $globas = new Mglobal;
        if(in_array($session->get('id_perfil'), [1,2])){
            $dataDB = array('tabla' => 'formulario_pt', 'where' => ['visible' => 1, 'tipo_formato' => 'PT']);
        }else{
              if(in_array($session->get('id_usuario'), [14,80,17, 59, 11, 38])){
                $dataDB = array('tabla' => 'formulario_pt', 'where' => ['visible' => 1, "promo"=> 1]);
            }else{
                $dataDB = array('tabla' => 'formulario_pt', 'where' => ['visible' => 1, 'usu_reg' => $session->get('id_usuario'), 'tipo_formato' => 'PT']);
                //$reserva = $globals->getTabla(['tabla' => 'vw_lista_reserva', 'where' => ['usu_reg' => $session->get('id_usuario'), 'visible' => 1, "id_estatus"=> 3]]);
            }
            
        }
       
       
        $response = $globas->getTabla($dataDB);
         //die( var_dump( $response ) );
          foreach($response->data as $key => $value){
            $usuario = $globas->getTabla(["tabla" => "vw_usuario", "where" => ["id_usuario" => $value->usu_reg], 'limit' => 1]);
            $response->data[$key]->nombre_usuario = $usuario->data[0]->nombre_completo;
            }  
        $data['dataHojaAzul'] = $response->data;
        
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vListaHojaAzul';
        $this->_renderView($data);
    }
    public function ListaHojaAzulMateriales()
    {
        $session = \Config\Services::session();
        $globas = new Mglobal;
        if(in_array($session->get('id_perfil'), [1,2])){
            $dataDB = array('tabla' => 'formulario_pt', 'where' => ['visible' => 1, 'tipo_formato' => 'MATERIALES']);
        }else{
            $dataDB = array('tabla' => 'formulario_pt', 'where' => ['visible' => 1, 'usu_reg' => $session->get('id_usuario'), 'tipo_formato' => 'MATERIALES']);
        }
       
       // die( var_dump( $dataDB ) );
        $response = $globas->getTabla($dataDB);
          foreach($response->data as $key => $value){
            $usuario = $globas->getTabla(["tabla" => "vw_usuario", "where" => ["id_usuario" => $value->usu_reg], 'limit' => 1]);
            $response->data[$key]->nombre_usuario = $usuario->data[0]->nombre_completo;
            }  
        $data['dataHojaAzul'] = $response->data;
        
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vListaHojaAzul';
        $this->_renderView($data);
    }
    public function ListaHojaAzulRefrendo($anio = null )
    {
        $session = \Config\Services::session();
        $globas = new Mglobal;
        if(in_array($session->get('id_perfil'), [1,2])){
            $dataDB = array('tabla' => 'formulario_pt', 'where' => ['visible' => 1, 'tipo_formato' => 'REFRENDO']);
        }else{
            $dataDB = array('tabla' => 'formulario_pt', 'where' => ['visible' => 1, 'usu_reg' => $session->get('id_usuario'), 'tipo_formato' => 'REFRENDO']);
        }
       
        $response = $globas->getTabla($dataDB);
          foreach($response->data as $key => $value){
            $usuario = $globas->getTabla(["tabla" => "vw_usuario", "where" => ["id_usuario" => $value->usu_reg], 'limit' => 1]);
            $response->data[$key]->nombre_usuario = $usuario->data[0]->nombre_completo;
            }  
        $data['dataHojaAzul'] = $response->data;
        $data['anio'] = $anio;
        
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['contentView'] = 'secciones/vListaHojaAzul';
        $this->_renderView($data);
    }

    public function generarFormatoPT()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $data = array();
        $data['es2025'] = false;
        
        $id = $this->request->getGet('id');
        $editar = $this->request->getGet('editar');
        $seguirPagando = $this->request->getGet('seguir_pagando');
        $anio = $this->request->getGet('anio');
        
        $data['editar'] = ($editar == 1) ? 1 : 0;
        $data['seguir_pagando'] = ($seguirPagando == 1) ? 1 : 0;
        $data['es2025'] = ($anio == 2025) ? true : false;
        $data['id_reserva'] = 0; // Default or fetch if needed
        $data['no_consecutivo'] = ''; // Logic to generate new consecutive if needed, or leave blank

         $proveedores = $globals->getTabla(["tabla" => "proveedor", "where" => ["visible" => 1],'limit' => 10]);
        $data['proveedores'] = $proveedores->data;

        if (($data['editar'] == 1 || $data['seguir_pagando'] == 1) && $id) {
            // Fetch main record
            $registroNumero = $globals->getTabla(["tabla" => "formulario_pt", "where" => ["id_formulario_pt" => $id, 'usu_reg' => $session->get('id_usuario')]]);
            // We don't overwrite no_consecutivo here with count() because it corrupts the read value from the db.
           
            $registro = $globals->getTabla(["tabla" => "formulario_pt", "where" => ["id_formulario_pt" => $id]]);
            if (!empty($registro->data)) {
                $data['registro_pt'] = $registro->data[0];
            //die(var_dump($data['registro_pt']));
                // Fetch items
                $items = $globals->getTabla(["tabla" => "manual_factura", "where" => ["id_registro_pt" => $id, "visible" => 1]]);
                $data['periodo_factura_rows'] = $items->data;
                
                  $cat_area = $globals->getTabla(["tabla" => "cat_area", "where" => ["visible" => 1, 'id_pago' => 1]]);
                $data['cat_area'] = $cat_area->data;
                
                $cat_proyecto = $globals->getTabla(["tabla" => "cat_proyecto", "where" => ["servicios" => 1]]);
                $data['cat_proyecto'] = $cat_proyecto->data;

                $cat_partida = $globals->getTabla(["tabla" => "cat_partida", "where" => ["visible" => 1]]);
                $data['cat_partida'] = $cat_partida->data;

                $usuarios = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["visible" => 1]]);
                $data['usuarios'] = $usuarios->data;
                $data['id_area'] = $usuarios->data[0]->id_area;

 
                
                // Check if we have provider info stored or linked.
                if (isset($data['registro_pt']->nombre_proveedor_1) && is_numeric($data['registro_pt']->nombre_proveedor_1)) {
                    $prov = $globals->getTabla(["tabla" => "proveedor", "where" => ["id_proveedor" => $data['registro_pt']->nombre_proveedor_1]]);
                    if (!empty($prov->data)) {
                        $data['proveedor'] = $prov->data[0];
                        
                         // Fetch banks for this provider
                        $bancos = $globals->getTabla(["tabla" => "proveedor_banco", "where" => ["id_proveedor" => $data['registro_pt']->nombre_proveedor_1, "visible" => 1]]);
                        $data['proveedor_banco'] = (!empty($bancos->data)) ? $bancos->data[0] : null; // Pass first bank or null, and maybe list for JS?
                    }
                }
            }
        } 

        //die( var_dump($data['cat_partida']) );

        $data['scripts'] = array('principal', 'inicio');
        $data['edita'] = $data['editar'];
        $data['contentView'] = 'secciones/vFormatoPagoTerceros';
        $this->_renderView($data);
    }

    public function eliminarHojaAzul()
    {
        $response = new \stdClass();
        $globals = new Mglobal;
        $session = \Config\Services::session();
        $id = $this->request->getPost('id_registro_pt');
        
        if ($id) {
            $result = $globals->saveTabla(
                ['visible' => 0], 
                ['tabla' => 'formulario_pt', 'editar' => true, 'idEditar' => ['id_formulario_pt' => $id]],
                ['id_user' => $session->id_usuario, 'script' => 'Agregar.php/guardaSala'] // Also fix id_registro_pt -> id_formulario_pt based on user edits
            );
            
            if (!$result->error) {
                $response->error = false;
                $response->respuesta = "Registro eliminado correctamente.";
            } else {
                $response->error = true;
                $response->respuesta = "Error al eliminar el registro.";
            }
        } else {
            $response->error = true;
            $response->respuesta = "ID no válido.";
        }
        
        return $this->response->setJSON($response);
    }

    public function pdfPagoTerceros()
    {
        $globals = new Mglobal;
        $id = $this->request->getGet('id');
        $data = [];

        if ($id) {
            $registro = $globals->getTabla(["tabla" => "formulario_pt", "where" => ["id_formulario_pt" => $id]]);
          //  die(var_dump($registro));
            if (!empty($registro->data)) {
              if($registro->data[0]->nombre_proveedor_1 > 0){
                $proveedor = $globals->getTabla(["tabla" => "proveedor", "where" => ["id_proveedor" => $registro->data[0]->nombre_proveedor_1]]);
                $registro->data[0]->nombre_proveedor_1 = $proveedor->data[0]->razon_social;
                $data['proveedor'] = $proveedor->data[0];
             }
             
              
              
               // $proveedor = $globals->getTabla(["tabla" => "proveedor", "where" => ["id_proveedor" => $registro->data[0]->nombre_proveedor_1]]);
               // $registro->data[0]->nombre_proveedor_1 = $proveedor->data[0]->razon_social;
              //  $data['proveedor'] = $proveedor->data[0];
                $data['registro_pt'] = $registro->data[0];
                $items = $globals->getTabla(["tabla" => "manual_factura", "where" => ["id_registro_pt" => $id , "visible" => 1]]);
                $data['periodo_factura_rows'] = $items->data;
                $data['edit'] = 1; // For view logic if reused
            }
        }

        // For mPDF, passing local absolute path is better and avoids base64 issues
        $data['logo'] = FCPATH . 'assets/logo-guanajuato.png';
        //die( var_dump($data['logo']) ); // Debug if needed
       // die(var_dump($data));
        $html = view('pdfs/vPdfFormatoPT', $data);

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'format' => 'Letter',
            'tempDir' => sys_get_temp_dir().DIRECTORY_SEPARATOR.'mpdf'
        ]);
        
        $mpdf->WriteHTML($html);
        $mpdf->Output('FormatPagoTerceros_' . $id . '.pdf', 'I');
        exit;
    }



    public function pdfEncabezadoFactura()
    {
        $globals = new Mglobal;
        $id = $this->request->getGet('id');
        $data = [];

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'format' => 'Letter',
            'tempDir' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mpdf_merge_new'
        ]);

        if ($id) {
            $registro = $globals->getTabla(["tabla" => "formulario_pt", "where" => ["id_formulario_pt" => $id]]);
            if (!empty($registro->data)) {
                $data['registro_pt'] = $registro->data[0];
                $items = $globals->getTabla(["tabla" => "manual_factura", "where" => ["id_registro_pt" => $id, "visible" => 1]]);
                $itemCount = count($items->data);
                $currentIndex = 0;

                $usuario = $globals->getTabla([
                    "tabla" => "vw_usuario",
                    "where" => ["nombre_completo" => $registro->data[0]->nombre_responsable_2]
                ]);
                $registro->data[0]->nombre_responsable = (isset($usuario->data[0])) ? $usuario->data[0]->nombre_completo.'-'.$usuario->data[0]->dsc_puesto.'-'.$usuario->data[0]->dsc_area : '';
                     
                foreach($items->data as $key => $item){
                    $currentIndex++;
                    $item->importe_letra = $this->numeroALetras($item->importe);
                    
                    if ($currentIndex > 1) {
                         $mpdf->AddPage();
                    }

                    $partida = $globals->getTabla([
                        "tabla" => "cat_partida",
                        "where" => ["cuenta_cable" => $item->partida, 'visible' => 1]
                    ]);
                    $item->dsc_partida = (isset($partida->data[0])) ? $partida->data[0]->nombre_fondo : '';
                    
                    // 1. Write Header
                    $data['row'] = $item; 
                    $html = view('pdfs/vPdfEncabezadoFactura', $data);
                    $mpdf->WriteHTML($html);

                    // 2. Append PDF INVOICE below header
                    if (!empty($item->pdf)) {
                        $fullPath = $this->resolveFacturaPdfPath($item->pdf, 'factura_pt_');
                        if ($fullPath && file_exists($fullPath)) {
                            
                            // GS Conversion: Down-convert to PDF 1.4 to assure FPDI compatibility
                            $gsTempFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mpdf_merge_new' . DIRECTORY_SEPARATOR . 'gs_' . uniqid() . '.pdf';
                            
                            // Validar sistema operativo para elegir el ejecutable Ghostscript
                            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                                $gsPath = '"C:\Program Files (x86)\gs\gs10.06.0\bin\gswin32c.exe"';
                            } else {
                                $gsPath = 'gs'; // Ruta universal del comando en Linux
                            }
                            
                            // Command to convert PDF down to 1.4
                            $cmd = $gsPath . ' -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile="' . $gsTempFile . '" "' . $fullPath . '"';
                            shell_exec($cmd);

                            // If conversion succeeded, use it; otherwise fallback to original
                            $importFile = file_exists($gsTempFile) && filesize($gsTempFile) > 0 ? $gsTempFile : $fullPath;

                            try {
                                $pagecount = $mpdf->SetSourceFile($importFile);
                                
                                // Import Page 1 and place below header
                                if ($pagecount >= 1) {
                                    $tplId = $mpdf->ImportPage(1);
                                    
                                    // Calculate dynamic position
                                    $y_start = $mpdf->y + 5; // 5mm margin below header
                                    $page_height = 279; // Letter height in mm (approx)
                                    $bottom_margin = 10;
                                    $max_height = $page_height - $y_start - $bottom_margin;

                                    // Usage: UseTemplate($tplId, x, y, w, h)
                                    // Use full width (approx 200mm) and calculated max height to avoid overflow
                                    $mpdf->UseTemplate($tplId, 5, $y_start, 205, $max_height);
                                }

                                // Append remaining pages as new pages
                                for ($i = 2; $i <= $pagecount; $i++) {
                                    $mpdf->AddPage(); 
                                    $tplId = $mpdf->ImportPage($i);
                                    // Use full page for subsequent pages
                                    $mpdf->UseTemplate($tplId); 
                                }
                            } catch (\Throwable $e) {
                                // If PDF is corrupted or has CrossReferenceException
                                $currentY = $mpdf->y + 10;
                                $mpdf->SetXY(10, $currentY);
                                $mpdf->SetFont('Arial', 'B', 12);
                                $mpdf->SetTextColor(255, 0, 0);
                                $mpdf->WriteCell(190, 10, "ERROR: No se pudo cargar el archivo PDF adjunto.", 0, 1, 'C');
                                $mpdf->SetFont('Arial', '', 10);
                                $mpdf->SetTextColor(0, 0, 0);
                                $mpdf->WriteCell(190, 10, "Archivo: " . basename($item->pdf), 0, 1, 'C');
                                $mpdf->WriteCell(190, 10, "Detalle: El archivo parece estar dañado o tiene un formato no válido.", 0, 1, 'C');
                                $mpdf->WriteCell(190, 10, "Error técnico: " . $e->getMessage(), 0, 1, 'C');
                            }
                            
                            @unlink($gsTempFile); // Cleanup ghostscript file
                            if (strpos($item->pdf, 'FACTURAS/') === 0) {
                                @unlink($fullPath);
                            }
                        }
                    }
                }
            }
        }

        $mpdf->Output('EncabezadoFactura_' . $id . '.pdf', 'I');
        exit;
    }

      public function pdfEncabezadoTiketGO()
    {
        $globals = new Mglobal;
        $id = $this->request->getGet('id');
        $data = [];

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'format' => 'Letter',
            'tempDir' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mpdf'
        ]);

        if ($id) {
            $registro = $globals->getTabla(["tabla" => "formulario_pt", "where" => ["id_formulario_pt" => $id]]);
            if (!empty($registro->data)) {
                $data['registro_pt'] = $registro->data[0];
                $items = $globals->getTabla(["tabla" => "manual_factura", "where" => ["id_registro_pt" => $id, "visible" => 1]]);
                
                $itemCount = count($items->data);
                $currentIndex = 0;

                foreach($items->data as $item){
                    $currentIndex++;
                    $item->importe_letra = $this->numeroALetras($item->importe);
                    
                    if ($currentIndex > 1) {
                         $mpdf->AddPage();
                    }

                    $partida = $globals->getTabla([
                        "tabla" => "cat_partida",
                        "where" => ["cuenta_cable" => $item->partida, 'visible' => 1]
                    ]);
                    $usuario = $globals->getTabla([
                        "tabla" => "vw_usuario",
                        "where" => ["nombre_completo" => $item->responsable]
                    ]);
                    //die(json_encode($usuario));
                    $item->dsc_partida = (isset($partida->data[0])) ? $partida->data[0]->nombre_fondo : '';
                    $item->nombre_responsable = (isset($usuario->data[0])) ? $usuario->data[0]->nombre_completo.'-'.$usuario->data[0]->dsc_puesto.'-'.$usuario->data[0]->dsc_area : '';
                  //  die(var_dump($item->nombre_responsable));
                    // 1. Write Header
                    $data['row'] = $item; 
                    $html = view('pdfs/vPdfEncabezadoFacturaGO', $data);
                    $mpdf->WriteHTML($html);

                }
            }
        }

        $mpdf->Output('EncabezadoFacturaGO_' . $id . '.pdf', 'I');
        exit;
    }

    public function pdfEncabezadoFacturaGO()
    {
        $globals = new Mglobal;
        $id = $this->request->getGet('id');
        $data = [];

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'format' => 'Letter',
            'tempDir' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mpdf'
        ]);

        if ($id) {
            $registro = $globals->getTabla(["tabla" => "formulario_pt", "where" => ["id_formulario_pt" => $id]]);
            if (!empty($registro->data)) {
                $data['registro_pt'] = $registro->data[0];
                $items = $globals->getTabla(["tabla" => "manual_factura", "where" => ["id_registro_pt" => $id, "visible" => 1]]);
                
                $itemCount = count($items->data);
                $currentIndex = 0;

                foreach($items->data as $item){
                    $currentIndex++;
                    $item->importe_letra = $this->numeroALetras($item->importe);
                    
                    if ($currentIndex > 1) {
                         $mpdf->AddPage();
                    }

                    $partida = $globals->getTabla([
                        "tabla" => "cat_partida",
                        "where" => ["cuenta_cable" => $item->partida, 'visible' => 1]
                    ]);
                    $usuario = $globals->getTabla([
                        "tabla" => "vw_usuario",
                        "where" => ["nombre_completo" => $item->responsable]
                    ]);
                    //die(json_encode($usuario));
                    $item->dsc_partida = (isset($partida->data[0])) ? $partida->data[0]->nombre_fondo : '';
                    $item->nombre_responsable = (isset($usuario->data[0])) ? $usuario->data[0]->nombre_completo.'-'.$usuario->data[0]->dsc_puesto.'-'.$usuario->data[0]->dsc_area : '';
                  //  die(var_dump($item->nombre_responsable));
                    // 1. Write Header
                    $data['row'] = $item; 
                    $html = view('pdfs/vPdfEncabezadoFacturaGO', $data);
                    $mpdf->WriteHTML($html);

                    // 2. Append PDF INVOICE below header
                    if (!empty($item->pdf)) {
                        $fullPath = $this->resolveFacturaPdfPath($item->pdf, 'factura_go_');
                        if ($fullPath && file_exists($fullPath)) {
                            
                            // GS Conversion: Down-convert to PDF 1.4 to assure FPDI compatibility
                            $gsTempFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mpdf_merge_new' . DIRECTORY_SEPARATOR . 'gs_' . uniqid() . '.pdf';
                            
                            // Validar sistema operativo para elegir el ejecutable Ghostscript
                            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                                $gsPath = '"C:\Program Files (x86)\gs\gs10.06.0\bin\gswin32c.exe"';
                            } else {
                                $gsPath = 'gs'; // Ruta universal del comando en Linux
                            }
                            
                            // Command to convert PDF down to 1.4
                            $cmd = $gsPath . ' -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile="' . $gsTempFile . '" "' . $fullPath . '"';
                            shell_exec($cmd);

                            // If conversion succeeded, use it; otherwise fallback to original
                            $importFile = file_exists($gsTempFile) && filesize($gsTempFile) > 0 ? $gsTempFile : $fullPath;

                            try {
                                $pagecount = $mpdf->SetSourceFile($importFile);
                                
                                // Import Page 1 and place below header
                                if ($pagecount >= 1) {
                                    $tplId = $mpdf->ImportPage(1);
                                    
                                    // Calculate dynamic position
                                    $y_start = $mpdf->y + 5; // 5mm margin below header
                                    $page_height = 279; 
                                    $page_width = 216;
                                    $bottom_margin = 10;
                                    $left_margin = 5;
                                    $max_width = 205;
                                    $max_height = $page_height - $y_start - $bottom_margin;
                                    
                                    // Get original dimensions to scale proportionally
                                    $size = $mpdf->getTemplateSize($tplId);
                                    $orig_w = $size['width'];
                                    $orig_h = $size['height'];
                                    
                                    $ratio_w = $max_width / $orig_w;
                                    $ratio_h = $max_height / $orig_h;
                                    $ratio = min($ratio_w, $ratio_h);
                                    
                                    $new_w = $orig_w * $ratio;
                                    $new_h = $orig_h * $ratio;
                                    
                                    // Center horizontally
                                    $x_pos = $left_margin + (($max_width - $new_w) / 2);
                                    
                                    $mpdf->UseTemplate($tplId, $x_pos, $y_start, $new_w, $new_h);
                                }

                                // Append remaining pages as new pages
                                for ($i = 2; $i <= $pagecount; $i++) {
                                    $mpdf->AddPage(); 
                                    $tplId = $mpdf->ImportPage($i);
                                    // Use full page for subsequent pages
                                    $mpdf->UseTemplate($tplId); 
                                }
                            } catch (\Throwable $e) {
                                // If PDF is corrupted or has CrossReferenceException
                                $currentY = $mpdf->y + 10;
                                $mpdf->SetXY(10, $currentY);
                                $mpdf->SetFont('Arial', 'B', 12);
                                $mpdf->SetTextColor(255, 0, 0);
                                $mpdf->WriteCell(190, 10, "ERROR: No se pudo cargar el archivo PDF adjunto.", 0, 1, 'C');
                                $mpdf->SetFont('Arial', '', 10);
                                $mpdf->SetTextColor(0, 0, 0);
                                $mpdf->WriteCell(190, 10, "Archivo: " . basename($item->pdf), 0, 1, 'C');
                                $mpdf->WriteCell(190, 10, "Detalle: El archivo parece estar dañado o tiene un formato no válido.", 0, 1, 'C');
                                $mpdf->WriteCell(190, 10, "Error técnico: " . $e->getMessage(), 0, 1, 'C');
                            }
                            
                            @unlink($gsTempFile); // Cleanup ghostscript file
                            if (strpos($item->pdf, 'FACTURAS/') === 0) {
                                @unlink($fullPath);
                            }
                        }
                    }
                }
            }
        }

        $mpdf->Output('EncabezadoFacturaGO_' . $id . '.pdf', 'I');
        exit;
    }

    public function pdfEncabezadoFacturaTicket()
    {
        $globals = new Mglobal;
        $id = $this->request->getGet('id');
        $data = [];

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'format' => 'Letter',
            'tempDir' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mpdf'
        ]);

        if ($id) {
            $registro = $globals->getTabla(["tabla" => "formulario_pt", "where" => ["id_formulario_pt" => $id]]);
            if (!empty($registro->data)) {
                $data['registro_pt'] = $registro->data[0];
                $items = $globals->getTabla(["tabla" => "manual_factura", "where" => ["id_registro_pt" => $id, "visible" => 1]]);
                //die( var_dump( $items->data ) );
                $itemCount = count($items->data);
                $currentIndex = 0;

                   $usuario = $globals->getTabla([
                            "tabla" => "vw_usuario",
                            "where" => ["nombre_completo" => $registro->data[0]->nombre_responsable_2]
                        ]);
                        $registro->data[0]->nombre_responsable = (isset($usuario->data[0])) ? $usuario->data[0]->nombre_completo.'-'.$usuario->data[0]->dsc_puesto.'-'.$usuario->data[0]->dsc_area : '';
                     

                foreach($items->data as $key => $item){
                    $currentIndex++;
                    $item->importe_letra = $this->numeroALetras($item->importe);
                    
                    if ($currentIndex > 1) {
                         $mpdf->AddPage();
                    }

                 
                        $partida = $globals->getTabla([
                            "tabla" => "cat_partida",
                            "where" => ["cuenta_cable" => $item->partida, 'visible' => 1]
                        ]);
                        $item->dsc_partida = (isset($partida->data[0])) ? $partida->data[0]->nombre_fondo : '';
                     
                    
                    
                    // 1. Write Header
                    //die(var_dump($item));
                    $data['row'] = $item; 
                    $html = view('pdfs/vPdfEncabezadoFactura', $data);
                    $mpdf->WriteHTML($html);

                    // 2. Append PDF INVOICE below header
             
                }
            }
        }

        $mpdf->Output('EncabezadoFactura_' . $id . '.pdf', 'I');
        exit;
    }

    private function numeroALetras($amount)
    {
        // Simple PHP implementation of number to words (Mexican Pesos) or reuse existing if available in library
        // Since I don't have a library handy, I'll implementing a basic one or look for one.
        // Actually, let's use a simplified version for now or check if there is a helper.
        // I will implement a basic one here to ensure it works.
        
        // Remove commas before casting to float
        if (is_string($amount)) {
            $amount = str_replace(',', '', $amount);
        }
        
        $amount = (float)$amount;
        $pesos = floor($amount);
        $centavos = round(($amount - $pesos) * 100);
        
        $formatter = new \NumberFormatter("es", \NumberFormatter::SPELLOUT);
        $letras = strtoupper($formatter->format($pesos));
        
        $letras .= " PESOS " . str_pad($centavos, 2, '0', STR_PAD_LEFT) . "/100 M.N.";
        
        return $letras;
    }


    public function listaGastosOperacion()
    {
        $session = \Config\Services::session();
        $globas = new Mglobal;
        if(in_array($session->get('id_perfil'), [1,2])){
            $dataDB = array('tabla' => 'formulario_pt', 'where' => ['visible' => 1, 'tipo_formato' => 'GO']);
        }else{
            $dataDB = array('tabla' => 'formulario_pt', 'where' => ['visible' => 1, 'usu_reg' => $session->get('id_usuario'), 'tipo_formato' => 'GO']);
        }
        $response = $globas->getTabla($dataDB);
        
        // Check for Viaticos
        if (!empty($response->data)) {
            foreach ($response->data as &$row) {
                 $check = $globas->getTabla([
                     "tabla" => "viaticos_go", 
                     "where" => ["id_registro_go" => $row->id_formulario_pt, "visible" => 1],
                     "limit" => 1
                 ]);
                 $usuario = $globas->getTabla([
                     "tabla" => "vw_usuario", 
                     "where" => ["id_usuario" => $row->usu_reg],
                     "limit" => 1
                 ]);
                 $row->tiene_viaticos = !empty($check->data);
                 $row->nombre_responsable = !empty($usuario->data[0]) ? $usuario->data[0]->nombre_completo : '';
            }
        }
       // die(var_dump($response->data));
        $data['dataHojaAzul'] = $response->data; // Reuse var name for ease in view or change
        
        $data['scripts'] = array('inicio');
        $data['edita'] = 0;
        $data['title'] = 'Lista de Gastos de Operación';
        $data['contentView'] = 'secciones/vListaGastosOperacion';
        $this->_renderView($data);
    }
    public function pdfGastosOperacion()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $id = $this->request->getGet('id');
        $data = [];

        // For mPDF, passing local absolute path is better and avoids base64 issues
        $data['logo'] = FCPATH . 'assets/logo-guanajuato.png';

        if ($id) {
            $registro = $globals->getTabla(["tabla" => "formulario_pt", "where" => ["id_formulario_pt" => $id]]);
            if (!empty($registro->data)) {
                $data['registro_pt'] = $registro->data[0];
                
                // Fetch items
                $items = $globals->getTabla(["tabla" => "manual_factura", "where" => ["id_registro_pt" => $id, "visible" => 1]]);
                $data['periodo_factura_rows'] = $items->data;
                $rows = count( $items->data);
                $data['rows'] = $rows;

                // Data for View Headers (Areas, default banks, etc)
                 if(isset($data['registro_pt']->nombre_proveedor_1) && is_numeric($data['registro_pt']->nombre_proveedor_1) && $data['registro_pt']->nombre_proveedor_1 > 0){
                      $prov = $globals->getTabla(["tabla" => "proveedor", "where" => ["id_proveedor" => $data['registro_pt']->nombre_proveedor_1]]);
                      if(!empty($prov->data)) $data['proveedor'] = $prov->data[0];
                       
                       // Bank
                       $bancos = $globals->getTabla(["tabla" => "proveedor_banco", "where" => ["id_proveedor" => $data['registro_pt']->nombre_proveedor_1, "visible" => 1]]);
                       if(!empty($bancos->data)) $data['proveedor_banco'] = $bancos->data[0];
                 }
                
                // Fetch Area for Prefix if needed 
                 $cat_area = $globals->getTabla(["tabla" => "cat_area", "where" => ["visible" => 1, 'id_pago' => 1]]);
                $data['cat_area'] = $cat_area->data;
            }
        }
      //  die(var_dump($data));
        $html = view('pdfs/vPdfGastosOperacion', $data);

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_bottom' => 5,
            'format' => 'Letter',
            'tempDir' => sys_get_temp_dir().DIRECTORY_SEPARATOR.'mpdf'
        ]);
        
        $mpdf->WriteHTML($html);
        $mpdf->Output('FormatGO_' . $id . '.pdf', 'I');
        exit;
    }
    public function Edenred()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $edenred = $globals->getTabla(["tabla" => "vw_edenred", "where" => ["visible" => 1]]);
        $usuarios = $globals->getTabla(["tabla" => "vw_usuario", "where" => ["visible" => 1]]);

        $data['edenred'] = $edenred->data ?? [];
        $data['usuarios'] = $usuarios->data ?? [];
        $data['scripts'] = array('inicio');
        $data['title'] = 'Lista de Gastos de Operación';
        $data['contentView'] = 'secciones/vListaEdenred';
        $this->_renderView($data);

    }
    public function guardarEdenred()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error al guardar el registro';
        $post = $this->request->getPost();

        $idEdenred = (int) ($post['id_edenred'] ?? 0);
        $esEdicion = !empty($post['editar']) && (int) $post['editar'] === 1 && $idEdenred > 0;

        if (empty($post['id_usuario'])) {
            $response->respuesta = 'El usuario es requerido';
            return $this->respond($response);
        }
        if (empty($post['placa'])) {
            $response->respuesta = 'La placa es requerida';
            return $this->respond($response);
        }
        if (empty($post['fecha'])) {
            $response->respuesta = 'La fecha es requerida';
            return $this->respond($response);
        }

        $dataInsert = [
            'id_usuario' => (int) $post['id_usuario'],
            'placa' => trim((string) ($post['placa'] ?? '')),
            'km_inicial' => $post['km_inicial'] ?? null,
            'km_servicio' => $post['km_servicio'] ?? null,
            'km_ultimo_servicio' => $post['km_ultimo_servicio'] ?? null,
            'fecha' => date('Y-m-d', strtotime($post['fecha'])),
            'taller' => trim((string) ($post['taller'] ?? '')),
            'estatus' => (int) ($post['estatus'] ?? 0),
        ];

        if ($esEdicion) {
            $dataConfig = [
                'tabla' => 'edenred',
                'editar' => true,
                'idEditar' => ['id_edenred' => $idEdenred]
            ];
        } else {
            $dataInsert['visible'] = 1;
            $dataConfig = [
                'tabla' => 'edenred',
                'editar' => false
            ];
        }

        $result = $globals->saveTabla($dataInsert, $dataConfig, [
            'id_user' => $session->get('id_usuario'),
            'script' => 'Inicio.php/guardarEdenred'
        ]);

        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
        } else {
            $response->respuesta = $result->respuesta ?? $response->respuesta;
        }

        return $this->respond($response);
    }

    public function getEdenred()
    {
        $globals = new Mglobal;
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error al consultar el registro';
        $idEdenred = (int) $this->request->getPost('id_edenred');

        $result = $globals->getTabla([
            'tabla' => 'edenred',
            'where' => ['visible' => 1, 'id_edenred' => $idEdenred]
        ]);

        if (!$result->error && !empty($result->data)) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
            $response->data = $result->data[0];
        } else {
            $response->respuesta = 'No se encontro el registro';
        }

        return $this->respond($response);
    }

    public function deleteEdenred()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error al eliminar el registro';
        $idEdenred = (int) $this->request->getPost('id_edenred');

        $result = $globals->saveTabla([
            'visible' => 0
        ], [
            'tabla' => 'edenred',
            'editar' => true,
            'idEditar' => ['id_edenred' => $idEdenred]
        ], [
            'id_user' => $session->get('id_usuario'),
            'script' => 'Inicio.php/deleteEdenred'
        ]);

        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
        } else {
            $response->respuesta = $result->respuesta ?? $response->respuesta;
        }

        return $this->respond($response);
    }

    public function edenredListo()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $response = new stdClass();
        $response->error = true;
        $response->respuesta = 'Error al cambiar el estatus';
        $idEdenred = (int) $this->request->getPost('id_edenred');

        $result = $globals->saveTabla([
            'estatus' => 1
        ], [
            'tabla' => 'edenred',
            'editar' => true,
            'idEditar' => ['id_edenred' => $idEdenred]
        ], [
            'id_user' => $session->get('id_usuario'),
            'script' => 'Inicio.php/edenredListo'
        ]);

        if (!$result->error) {
            $response->error = false;
            $response->respuesta = $result->respuesta;
        } else {
            $response->respuesta = $result->respuesta ?? $response->respuesta;
        }

        return $this->respond($response);
    }

    public function pdfOficioLiberacion()
    {
        $session = \Config\Services::session();
        $globals = new Mglobal;
        $id = $this->request->getGet('id');
        $data = [];

        // Logo
        $data['logo'] = FCPATH . 'assets/logo-guanajuato.png';

        // Meses for date formatting
        $data['meses'] = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        if ($id) {
            $registro = $globals->getTabla(["tabla" => "formulario_pt", "where" => ["id_formulario_pt" => $id]]);
            if (!empty($registro->data)) {
                $data['registro_pt'] = $registro->data[0];
                
                // Helper to convert number to letters (reuse existing logic or simple one)
              
            }
        }
        //die(var_dump($data));
        $html = view('pdfs/vPdfOficioLiberacion', $data);

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 20,
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_bottom' => 20,
            'format' => 'Letter',
            'tempDir' => sys_get_temp_dir().DIRECTORY_SEPARATOR.'mpdf'
        ]);
        
        $mpdf->WriteHTML($html);
        $mpdf->Output('OficioLiberacion_' . $id . '.pdf', 'I');
        exit;
    }
}
