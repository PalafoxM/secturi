<?php
$session = \Config\Services::session();
$moduloArchivos = $modulo_archivos ?? (!empty($es_convenio) ? 'convenio' : 'contrato');

$configuracionModulo = [
    'contrato' => [
        'ruta_listado' => 'index.php/Principal/ListaSolicitudContrato',
        'titulo' => 'Archivos de Solicitud',
        'campo_id_archivo' => 'id_solicitud_contrato_archivo',
        'ruta_archivo' => 'assets/uploads/contratos/',
        'documentos' => [
            1 => 'Anexo Tecnico (Terminos de referencia)',
            '2a' => 'Investigacion de Mercado (Cotizaciones y consulta PEI)',
            '2b' => 'Analisis de Ofertas turisticas',
            '2c' => 'Argumentacion Tecnica',
            '3a' => 'Validacion de partida restringida (SF)',
            '3b' => 'Verificacion de Alineacion de Informacion Estrategica (DGIT)',
            '3c' => 'Suficiencia presupuestal (R3)',
            '3d' => 'Validacion DGTIT/CGCS u otra',
            4 => 'Justificacion',
            5 => 'Propuesta Tecnico Economica (Anexo)',
            6 => 'Aviso de privacidad integral',
            7 => 'Cedula de Registro en el Padron de Proveedores',
            8 => 'Escritura Constitutiva',
            9 => 'Documento de representacion legal (Poder)',
            10 => 'Identificacion oficial vigente',
            11 => 'Constancia de situacion fiscal',
            12 => 'Comprobante de domicilio',
            '13a' => 'Opinion de cumplimiento de obligaciones fiscales',
            '13b' => 'Manifiesto bajo protesta de cumplimiento fiscal',
            14 => 'Manifiesto de no impedimento para contratar',
            15 => 'Carta de declaracion de intereses',
            16 => 'Manifiesto de contar con infraestructura',
        ],
    ],
    'convenio' => [
        'ruta_listado' => 'index.php/Principal/ListaSolicitudConvenio',
        'titulo' => 'Archivos de Solicitud',
        'campo_id_archivo' => 'id_archivo',
        'ruta_archivo' => 'assets/uploads/convenios/',
        'documentos' => [
            1 => 'Acta de Sesion de Comite',
            2 => 'Dictamen',
            3 => 'Validaciones',
            4 => 'Propuesta de Acciones',
            5 => 'Autorizacion de tratamiento de datos personales',
            6 => 'Escritura Constitutiva y modificaciones',
            7 => 'Poder del Representante Legal y nombramientos',
            8 => 'Identificacion Oficial',
            9 => 'Constancia de Situacion Fiscal',
            10 => 'Comprobante de domicilio vigente',
            11 => 'Opinion de Cumplimiento de Obligaciones Fiscales',
            12 => 'Carta de declaracion de intereses',
            13 => 'Manifestacion de no impedimento legal',
            14 => 'Manifiesto de contar con infraestructura',
        ],
    ],
    'honorarios' => [
        'ruta_listado' => 'index.php/Principal/listadoHonorarios',
        'titulo' => 'Archivos de Solicitud',
        'campo_id_archivo' => 'id_solicitud_honorario_archivos',
        'ruta_archivo' => 'assets/uploads/honorarios/',
        'documentos' => [
            1 => 'Oficio de solicitud',
            2 => 'Formato de solicitud de Contrato',
            3 => 'Validacion Proceso Ingreso de SFIA',
            4 => 'RFC / Cedula de Identificacion Fiscal',
            5 => 'Identificacion Oficial',
            6 => 'Autorizacion de Tratamiento de Datos Personales',
            7 => 'Comprobante de Domicilio',
        ],
    ],
    'adquisiciones' => [
        'ruta_listado' => 'index.php/Principal/ListaSolicitudAdquisiciones',
        'titulo' => 'Archivos de Solicitud de Adquisiciones',
        'campo_id_archivo' => 'id_solicitud_adquisiciones_archivo',
        'ruta_archivo' => 'assets/uploads/adquisiciones/',
        'documentos' => [
            1 => 'Anexo Tecnico (Terminos de referencia)',
            2 => 'Investigacion de Mercado (Cotizaciones y consulta PEI)',
            3 => 'Validaciones',
            4 => 'Justificacion',
            5 => 'Propuesta Tecnico Economica (Anexo)',
            6 => 'Aviso de privacidad integral',
            7 => 'Cedula de Registro en el Padron de Proveedores',
            8 => 'Escritura Constitutiva',
            9 => 'Documento de representacion legal (Poder)',
            10 => 'Identificacion oficial vigente',
            11 => 'Constancia de Situacion Fiscal (RFC)',
            12 => 'Comprobante de domicilio',
            13 => 'Cumplimiento de Obligaciones Fiscales',
            14 => 'Manifiesto de no impedimento para contratar',
            15 => 'Carta de declaracion de intereses',
            16 => 'Manifiesto de contar con infraestructura',
            17 => 'Carta compromiso entrega de bienes',
        ],
    ],
];

$moduloActivo = $configuracionModulo[$moduloArchivos] ?? $configuracionModulo['contrato'];
$rutaListado = $moduloActivo['ruta_listado'];
$campoIdArchivo = $moduloActivo['campo_id_archivo'];
$camposIdArchivo = array_values(array_unique([
    $campoIdArchivo,
    'id_archivo',
    'id_solicitud_contrato_archivo',
    'id_solicitud_convenio_archivo',
    'id_solicitud_honorario_archivos',
    'id_solicitud_honorario_archivo',
    'id_solicitud_adquisiciones_archivo',
    'id_solicitud_adquisiciones_archivos',
    'id_solicitud_adquisicion_archivo',
    'id_solicitud_adquisicion_archivos',
]));
$rutaArchivo = $moduloActivo['ruta_archivo'];
$nombresDocs = $moduloActivo['documentos'];
$tituloVista = $moduloActivo['titulo'];
?>
<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url($rutaListado) ?>">Listado</a></li>
                                <li class="breadcrumb-item active">Ver Archivos</li>
                            </ol>
                        </div>
                        <h4 class="page-title"><?= esc($tituloVista) ?> #<?= esc($id_solicitud) ?></h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title mt-0 mb-0">Documentacion Cargada</h4>
                                <?php if ($moduloArchivos === 'contrato'): ?>
                                    <div>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarArchivoSolicitud">
                                            <i class="fas fa-plus"></i> Agregar archivo
                                        </button>
                                        <a href="<?= base_url('index.php/Principal/descargarChecklistSolicitud/' . $id_solicitud) ?>" class="btn btn-success btn-sm">
                                            <i class="fas fa-download"></i> Descargar Check List
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Documento</th>
                                            <th>Archivo</th>
                                            <th>Estatus</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($archivos)): ?>
                                            <?php foreach ($archivos as $archivo): ?>
                                                <?php
                                                $idArchivo = 0;
                                                foreach ($camposIdArchivo as $campoPosibleId) {
                                                    $idArchivo = (int) ($archivo->{$campoPosibleId} ?? 0);
                                                    if ($idArchivo > 0) {
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?= esc($nombresDocs[$archivo->clave_documento] ?? ($archivo->nombre_documento ?? ('Documento ' . $archivo->clave_documento))) ?>
                                                    </td>
                                                    <td><?= esc($archivo->nombre_archivo ?? '') ?></td>
                                                    <td>
                                                        <?php if ((int) ($archivo->id_estatus) === 1): ?>
                                                            <span class="badge badge-warning">Pendiente</span>
                                                        <?php elseif ((int) ($archivo->id_estatus) === 2): ?>
                                                            <span class="badge badge-danger">Declinado</span>
                                                        <?php elseif ((int) ($archivo->id_estatus) === 3): ?>
                                                            <span class="badge badge-success">Aceptado</span>
                                                        <?php elseif ((int) ($archivo->id_estatus ?? 0) === 4): ?>
                                                            <span class="badge badge-info">Editado</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-secondary">Sin estatus</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="<?= esc($archivo->url_descarga ?? base_url($rutaArchivo . ($archivo->nombre_archivo ?? ''))) ?>" target="_blank" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <?php if ($idArchivo > 0 && (int) ($archivo->id_estatus) === 2 && (int) ($session->get('id_perfil') ?? 0) !== 7): ?>
                                                            <a onclick="editarArchivo(<?= $idArchivo ?>)" class="btn btn-warning btn-sm">
                                                                <i class="fas fa-edit text-white"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if ($idArchivo > 0 && in_array((int) ($session->get('id_perfil') ?? 0), [1, 7], true) && (!in_array((int) ($archivo->id_estatus) ?? 0, [2, 3], true))): ?>
                                                            <a onclick="declinarArchivo(<?= $idArchivo ?>)" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash text-white"></i>
                                                            </a>
                                                            <a onclick="aceptarArchivo(<?= $idArchivo ?>)" class="btn btn-success btn-sm">
                                                                <i class="fas fa-check text-white"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">No se encontraron archivos cargados para esta solicitud.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <a href="<?= base_url($rutaListado) ?>" class="btn btn-secondary">Volver al Listado</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($moduloArchivos === 'contrato'): ?>
    <div class="modal fade" id="modalAgregarArchivoSolicitud" tabindex="-1" role="dialog" aria-labelledby="modalAgregarArchivoSolicitudLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarArchivoSolicitudLabel">Seleccion de Documentos a Subir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarArchivoSolicitud" action="<?= base_url('index.php/Principal/subirArchivosSolicitud') ?>" method="POST">
                        <input type="hidden" name="id_solicitud" value="<?= esc($id_solicitud) ?>">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 10%;">Num.</th>
                                    <th>Documento</th>
                                    <th class="text-center" style="width: 12%;">Seleccionar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($nombresDocs as $key => $nombreDocumento): ?>
                                    <tr>
                                        <td><?= esc($key) ?></td>
                                        <td><?= esc($nombreDocumento) ?></td>
                                        <td class="text-center">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input documento-agregar-check" id="documento_agregar_<?= esc($key) ?>" name="documentos[<?= esc($key) ?>]" value="<?= esc($nombreDocumento) ?>">
                                                <label class="custom-control-label" for="documento_agregar_<?= esc($key) ?>"></label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="enviarAgregarArchivoSolicitud()">Continuar</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<link href="<?= base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

<script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?= base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>
<script src="<?= base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

<script>
    const moduloArchivos = '<?= esc($moduloArchivos, 'js') ?>';

    function enviarAgregarArchivoSolicitud() {
        const documentosSeleccionados = $('#formAgregarArchivoSolicitud .documento-agregar-check:checked').length;
        if (documentosSeleccionados === 0) {
            Swal.fire('Atencion', 'Seleccione al menos un documento para subir.', 'warning');
            return;
        }

        $('#formAgregarArchivoSolicitud').submit();
    }

    function declinarArchivo(idArchivo) {
        Swal.fire({
            title: 'Declinar archivo',
            text: 'No podras revertir esta accion',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, declinar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: '<?= base_url('index.php/Principal/DeclinarArchivo') ?>',
                type: 'POST',
                data: { id_archivo: idArchivo, modulo: moduloArchivos },
                success: function(response) {
                    if (response.error == false) {
                        Swal.fire('Exito', response.respuesta, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.respuesta, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrio un error al procesar la solicitud.', 'error');
                }
            });
        });
    }

    function editarArchivo(idArchivo) {
        Swal.fire({
            title: 'Subir nuevo archivo',
            text: 'Seleccione el archivo PDF para reemplazar el actual',
            icon: 'info',
            input: 'file',
            inputAttributes: {
                accept: 'application/pdf',
                'aria-label': 'Subir archivo PDF'
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, subir',
            cancelButtonText: 'Cancelar',
            preConfirm: (file) => {
                if (!file) {
                    Swal.showValidationMessage('Debe seleccionar un archivo');
                }
                return file;
            }
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            let formData = new FormData();
            formData.append('id_archivo', idArchivo);
            formData.append('modulo', moduloArchivos);
            formData.append('archivo', result.value);

            $.ajax({
                url: '<?= base_url('index.php/Principal/EditarArchivo') ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Actualizando archivo',
                        text: 'Por favor espera mientras se sube el documento.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    if (typeof response === 'string') {
                        try {
                            response = JSON.parse(response);
                        } catch (e) {}
                    }

                    if (response.error == false) {
                        Swal.fire('Exito', response.respuesta, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.respuesta, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrio un error al procesar la solicitud.', 'error');
                }
            });
        });
    }

    function aceptarArchivo(idArchivo) {
        Swal.fire({
            title: 'Aceptar archivo',
            text: 'No podras revertir esta accion',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: '<?= base_url('index.php/Principal/AceptarArchivo') ?>',
                type: 'POST',
                data: { id_archivo: idArchivo, modulo: moduloArchivos },
                success: function(response) {
                    if (response.error == false) {
                        Swal.fire('Exito', response.respuesta, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.respuesta, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrio un error al procesar la solicitud.', 'error');
                }
            });
        });
    }
</script>
