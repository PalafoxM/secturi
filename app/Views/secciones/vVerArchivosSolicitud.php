<?php
$session = \Config\Services::session();
$esConvenio = !empty($es_convenio);
$rutaListado = $esConvenio ? 'index.php/Principal/ListaSolicitudConvenio' : 'index.php/Principal/ListaSolicitudContrato';
$rutaArchivo = $esConvenio ? 'assets/uploads/convenios/' : 'assets/uploads/contratos/';
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
                        <h4 class="page-title">Archivos de Solicitud #<?= $id_solicitud ?></h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mt-0">Documentación Cargada</h4>
                            
                            <?php 
                            $nombres_docs = [
                                1 => 'Anexo Técnico (Términos de referencia)',
                                '2a' => 'Investigación de Mercado (Cotizaciones y consulta PEI)',
                                '2b' => 'Análisis de Ofertas turísticas (Excepción de Ley de Contrataciones)',
                                '2c' => 'Argumentación Técnica (Proveedor único)',
                                '3a' => 'Validación de partida restringida (SF)',
                                '3b' => 'Verificación de Alineación de Información Estratégica (DGIT)',
                                '3c' => 'Suficiencia presupuestal (R3)',
                                '3d' => 'Validación DGTIT/CGCS u otra',
                                4 => 'Justificación',
                                5 => 'Propuesta Técnico Económica (Anexo)',
                                6 => 'Aviso de privacidad integral',
                                7 => 'Cédula de Registro en el Padrón de Proveedores (Refrendo vigente)',
                                8 => 'Escritura Constitutiva/Documento que acredite la legal constitución de la persona moral',
                                9 => 'Documento que acredite la representación de la persona moral (Poder)',
                                10 => 'Identificación oficial vigente (Personas morales Representante y Personas Físicas)',
                                11 => 'Constancia de situación fiscal',
                                12 => 'Comprobante de domicilio',
                                '13a' => 'Opinión de cumplimiento de obligaciones fiscales',
                                '13b' => 'Manifiesto bajo protesta de complimiento de obligaciones fiscales',
                                14 => 'Manifiesto de no encontrare impedido para contratar',
                                15 => 'Carta de Declaración de intereses',
                                16 => 'Manifiesto de contar con infraestructura'
                            ];
                            ?>

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
                                                <tr>
                                                    <td>
                                                        <?= isset($nombres_docs[$archivo->clave_documento]) ? $nombres_docs[$archivo->clave_documento] : 'Documento ' . $archivo->clave_documento ?>
                                                    </td>
                                                    <td><?= $archivo->nombre_archivo ?></td>
                                                    <td>
                                                        <?php if ($archivo->id_estatus == 1): ?>
                                                            <span class="badge badge-warning">Pendiente</span>
                                                        <?php elseif ($archivo->id_estatus == 3): ?>
                                                            <span class="badge badge-success">Aceptado</span>
                                                        <?php elseif ($archivo->id_estatus == 4): ?>
                                                            <span class="badge badge-info">Editado</span>
                                                        <?php elseif ($archivo->id_estatus == 2): ?>
                                                            <span class="badge badge-danger">Declinado</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="<?= $archivo->url_descarga ?? base_url($rutaArchivo . $archivo->nombre_archivo) ?>" target="_blank" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <?php if($archivo->id_estatus == 2): ?>
                                                            <a onclick="editarArchivo(<?= $archivo->id_solicitud_contrato_archivo ?>)" class="btn btn-warning btn-sm">
                                                                <i class="fas fa-edit text-white"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if (in_array($archivo->id_estatus, [1, 4]) && in_array($session->get('id_perfil'), [1, 7])): ?>
                                                            <a onclick="declinarArchivo(<?= $archivo->id_solicitud_contrato_archivo ?>)" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash text-white"></i>
                                                            </a>
                                                            <a onclick="aceptarArchivo(<?= $archivo->id_solicitud_contrato_archivo ?>)" class="btn btn-success btn-sm">
                                                                <i class="fas fa-check text-white"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center">No se encontraron archivos cargados para esta solicitud.</td>
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

<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />

<!-- App css -->
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />



<!-- jQuery  -->
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>


<!-- Required datatable js -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

<script src="<?php echo base_url(); ?>assets/pages/jquery.analytics_customers.init.js"></script>


<script>
    function declinarArchivo(id_solicitud_contrato_archivo) {
        Swal.fire({
            title: '¿Está seguro de declinar este archivo?',
            text: "No podrás revertir esta acción",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, declinar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('index.php/Principal/DeclinarArchivo') ?>',
                    type: 'POST',
                    data: { id_solicitud_contrato_archivo: id_solicitud_contrato_archivo },
                    success: function(response) {
                        if (response.error == false) {
                            Swal.fire({
                                title: 'Éxito',
                                text: response.respuesta,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            })
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.respuesta,
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                            })
                        }
                    }
                });
            }
        })
    }

    function editarArchivo(id_solicitud_contrato_archivo) {
        Swal.fire({
            title: 'Subir nuevo archivo',
            text: "Seleccione el archivo PDF para reemplazar el actual",
            icon: 'info',
            input: 'file',
            inputAttributes: {
                'accept': 'application/pdf',
                'aria-label': 'Subir archivo PDF'
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, subir',
            cancelButtonText: 'Cancelar',
            preConfirm: (file) => {
                if (!file) {
                    Swal.showValidationMessage('Debe seleccionar un archivo')
                }
                return file;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let formData = new FormData();
                formData.append('id_solicitud_contrato_archivo', id_solicitud_contrato_archivo);
                formData.append('archivo', result.value);

                $.ajax({
                    url: '<?= base_url('index.php/Principal/EditarArchivo') ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        try {
                            if (typeof response === 'string') {
                                response = JSON.parse(response);
                            }
                        } catch (e) {
                            console.error("Error parsing JSON response", e);
                        }

                        if (response.error == false) {
                            Swal.fire({
                                title: 'Éxito',
                                text: response.respuesta,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            })
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.respuesta,
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                            })
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la solicitud.',
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }
        })
    }

    function aceptarArchivo(id_solicitud_contrato_archivo) {
        Swal.fire({
            title: '¿Está seguro de aceptar este archivo?',
            text: "No podrás revertir esta acción",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('index.php/Principal/AceptarArchivo') ?>',
                    type: 'POST',
                    data: { id_solicitud_contrato_archivo: id_solicitud_contrato_archivo },
                    success: function(response) {
                        if (response.error == false) {
                            Swal.fire({
                                title: 'Éxito',
                                text: response.respuesta,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            })
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.respuesta,
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    }
                });
            }
        })
    }
</script>

