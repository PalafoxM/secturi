<?php $session = \Config\Services::session(); ?>
<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Jurídico</a></li>
                                <li class="breadcrumb-item active">Listado de Contratos</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Solicitudes de Contrato</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12 text-right">
                                    <a href="<?= base_url('index.php/Principal/SolicitudContrato') ?>" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Nueva Solicitud
                                    </a>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table id="datatable-contratos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Fecha Registro</th>
                                            <th>Proyecto</th>
                                            <th>Responsable Proyecto</th>
                                            <th>Responsable Seguimiento</th>
                                            <th>Monto</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($solicitudes) && !empty($solicitudes)): ?>
                                            <?php foreach($solicitudes as $sol): ?>
                                                <tr>
                                                    <td><?= $sol->id_solicitud_contrato ?></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($sol->fec_reg)) ?></td>
                                                    <td><?= $sol->dsc_proyecto ?></td>
                                                    <td><?= $sol->nombre_proyecto ?></td>
                                                    <td><?= $sol->nombre_seguimiento ?></td>
                                                    <td><?= $sol->monto_total ?></td>
                                                    <td class="text-center">
                                                        <?php if($sol->id_estatus != 3 && $sol->id_estatus != 4 && $session->id_perfil != 7): ?>
                                                            <a href="<?= base_url('index.php/Principal/editarSolicitudContrato/' . $sol->id_solicitud_contrato) ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                                                            <button class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarSolicitud(<?= $sol->id_solicitud_contrato ?>)"><i class="fas fa-trash"></i></button>
                                                        <?php endif; ?>

                                                        <?php if($session->id_perfil != 7): ?>
                                                            <a href="<?= base_url('index.php/Principal/verSolicitudContratoPDF/' . $sol->id_solicitud_contrato) ?>" target="_blank" class="btn btn-sm btn-info" title="Ver PDF"><i class="fas fa-file-pdf"></i></a>
                                                        <?php endif; ?>

                                                        <?php if(in_array($sol->id_estatus, [3]) && $session->id_perfil != 7): ?>
                                                            <button class="btn btn-sm btn-secondary" title="Adjuntar Archivos" onclick="abrirModalArchivos(<?= $sol->id_solicitud_contrato ?>)"><i class="fas fa-paperclip"></i></button>
                                                        <?php endif; ?>

                                                        <?php if($sol->tienen_archivos): ?>
                                                                <a href="<?= base_url('index.php/Principal/verArchivosSolicitud/' . $sol->id_solicitud_contrato) ?>" class="btn btn-sm btn-success" title="Ver Archivos"><i class="fas fa-eye"></i></a>
                                                        <?php endif; ?>

                                                        <?php if($sol->id_estatus == 1 && in_array($session->id_perfil, [1,7])): ?>
                                                                <a onclick="aprobarSolicitud(<?= $sol->id_solicitud_contrato ?>);" class="btn btn-sm btn-primary" title="Aprobar"><i class="fas fa-check text-white"></i></a>
                                                        <?php endif; ?>

                                                         <?php if($sol->instrumento_juridico != ''): ?>
                                                                <a href="<?= base_url($sol->instrumento_juridico) ?>" target="_blank" class="btn btn-sm btn-success" title="Ver Instrumento Jurídico"><i class="fas fa-file-pdf"></i></a>
                                                        <?php endif; ?>

                                                        <?php if(in_array($session->id_perfil, [1,7])): ?>
                                                            <?php if($sol->id_estatus == 4): ?>
                                                                <button class="btn btn-sm btn-primary" title="Subir Instrumento Jurídico" onclick="subirInstrumentoJuridico(<?= $sol->id_solicitud_contrato ?>)"><i class="fas fa-upload"></i> Subir Instrumento</button>
                                                            <?php endif; ?>
                                                            <?php if($sol->id_estatus == 2): ?>
                                                                <button class="btn btn-sm btn-danger" title="Declinado"><i class="fas fa-times"></i>Declinado</button>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                        
                                                 </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Selección de Archivos -->
<div class="modal fade" id="modalSeleccionArchivos" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Selección de Documentos a Subir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formSeleccionArchivos" action="<?= base_url('index.php/Principal/subirArchivosSolicitud') ?>" method="POST">
                    <input type="hidden" name="id_solicitud" id="modal_id_solicitud">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%;">Num.</th>
                                <th>DOCUMENTO</th>
                                <th style="width: 10%; text-align: center;">SI</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $documentos = [
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
                            foreach($documentos as $key => $doc): ?>
                            <tr>
                                <td><?= $key ?></td>
                                <td><?= $doc ?></td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input check-si" id="si_<?= $key ?>" name="documentos[<?= $key ?>]" value="<?= $doc ?>">
                                        <label class="custom-control-label" for="si_<?= $key ?>"></label>
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
                <button type="button" class="btn btn-primary" onclick="enviarFormularioArchivos()">Continuar</button>
            </div>
        </div>
    </div>
</div>

<link href="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.slimscroll.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#datatable-contratos').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }
        });

        // Lógica para que los checkboxes sean mutuamente excluyentes (SI vs NA)
        $('.check-si').change(function() {
            if($(this).is(':checked')) {
                let id = $(this).attr('id').replace('si_', 'na_');
                $('#' + id).prop('checked', false);
            }
        });
        
        $('.check-na').change(function() {
            if($(this).is(':checked')) {
                let id = $(this).attr('id').replace('na_', 'si_');
                $('#' + id).prop('checked', false);
            }
        });
    });

    function aprobarSolicitud(id) {
        Swal.fire({
            title: '¿Estás seguro de aprobar la solicitud?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, Aprobar',
            cancelButtonText: 'No, Declinar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("index.php/Principal/aprobarSolicitudContrato") ?>',
                    type: 'POST',
                    data: { id_solicitud: id },
                    success: function(response) {
                        if (!response.error) {
                            Swal.fire(
                                'Aprobado!',
                                'El registro ha sido aprobado.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'No se pudo eliminar el registro.',
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Ocurrió un error al procesar la solicitud.',
                            'error'
                        );
                    }
                });
            }else{
                 $.ajax({
                    url: '<?= base_url("index.php/Principal/declinarSolicitudContrato") ?>',
                    type: 'POST',
                    data: { id_solicitud: id },
                    success: function(response) {
                        if (!response.error) {
                            Swal.fire(
                                'Declinado!',
                                'El registro ha sido declinado.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'No se pudo eliminar el registro.',
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Ocurrió un error al procesar la solicitud.',
                            'error'
                        );
                    }
                });
            }
        });
    }
    function eliminarSolicitud(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("index.php/Principal/eliminarSolicitudContrato") ?>',
                    type: 'POST',
                    data: { id_solicitud: id },
                    success: function(response) {
                        if (!response.error) {
                            Swal.fire(
                                'Eliminado!',
                                'El registro ha sido eliminado.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'No se pudo eliminar el registro.',
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Ocurrió un error al procesar la solicitud.',
                            'error'
                        );
                    }
                });
            }
        });
    }
    
    function abrirModalArchivos(id) {
        $('#modal_id_solicitud').val(id);
        // Resetear checkboxes
        $('.check-si').prop('checked', false);
        $('.check-na').prop('checked', false);
        $('#modalSeleccionArchivos').modal('show');
    }

    function enviarFormularioArchivos() {
        // Verificar que al menos un archivo o NA haya sido seleccionado (opcional)
        $('#formSeleccionArchivos').submit();
    }

    function subirInstrumentoJuridico(id) {
        Swal.fire({
            title: 'Subir Instrumento Jurídico',
            input: 'file',
            inputAttributes: {
                'accept': 'application/pdf',
                'aria-label': 'Subir Instrumento Jurídico en PDF'
            },
            showCancelButton: true,
            confirmButtonText: 'Subir',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: (file) => {
                if (!file) {
                    Swal.showValidationMessage('Por favor selecciona un archivo PDF');
                    return false;
                }
                
                const formData = new FormData();
                formData.append('archivo', file);
                formData.append('id_solicitud', id);
                
                return fetch('<?= base_url("index.php/Principal/subirInstrumentoJuridico") ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Error: ${error}`
                    )
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value.error) {
                    Swal.fire('Error', result.value.respuesta || 'Ocurrió un error al subir el archivo.', 'error');
                } else {
                    Swal.fire('¡Éxito!', 'El archivo se ha subido correctamente.', 'success').then(() => {
                        location.reload();
                    });
                }
            }
        });
    }
</script>
