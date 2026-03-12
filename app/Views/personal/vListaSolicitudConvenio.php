<?php $session = \Config\Services::session(); ?>
<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Jurídico</a></li>
                                <li class="breadcrumb-item active">Listado de Convenios</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Solicitudes de Convenio</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12 text-right">
                                    <a href="<?= base_url('index.php/Principal/SolicitudConvenio') ?>" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Nueva Solicitud
                                    </a>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table id="datatable-convenios" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Fecha Registro</th>
                                            <th>Responsable Proyecto</th>
                                            <th>Responsable Seguimiento</th>
                                            <th>Monto Total</th>
                                            <th>Estatus</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($solicitudes) && !empty($solicitudes)): ?>
                                            <?php foreach($solicitudes as $sol): ?>
                                                <tr>
                                                    <td><?= $sol->id_solicitud_convenio ?></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($sol->fec_reg)) ?></td>
                                                    <td><?= $sol->nombre_proyecto ?></td>
                                                    <td><?= $sol->nombre_seguimiento ?></td>
                                                    <td><?= $sol->monto_total ?></td>
                                                    <td>
                                                        <?php if($sol->id_estatus == 2): ?>
                                                            <button class="btn btn-sm btn-outline-danger font-weight-bold shadow-sm" title="Clic para ver motivo" onclick="verMotivo('<?= htmlspecialchars($sol->motivo ?? '', ENT_QUOTES) ?>')">
                                                                <i class="fas fa-exclamation-triangle"></i> Declinado
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if($sol->id_estatus == 4): ?>
                                                            <button class="btn btn-sm btn-soft-warning font-weight-bold" style="cursor: default; pointer-events: none;" title="En revisión por área Jurídica">
                                                                <i class="fas fa-circle-notch fa-spin mr-1"></i> En Espera
                                                            </button>
                                                        <?php endif; ?>

                                                           <?php if($sol->id_estatus == 3): ?>
                                                             <?php
                                                                $btn_instrumentos = '';
                                                                $decoded = json_decode($sol->instrumento_juridico, true);
                                                                if(is_array($decoded)) {
                                                                    foreach($decoded as $index => $ruta) {
                                                                        $btn_instrumentos .= '<a href="'.base_url($ruta).'" target="_blank" class="btn btn-sm btn-success mb-1" title="Ver Instrumento '.($index+1).'"><i class="fas fa-file-pdf"></i> Inst. '.($index+1).'</a> ';
                                                                    }
                                                                } else {
                                                                     // Fallback para antiguos strings guardados directamente
                                                                     $btn_instrumentos = '<a href="'.base_url($sol->instrumento_juridico).'" target="_blank" class="btn btn-sm btn-success mb-1" title="Ver Instrumento Jurídico"><i class="fas fa-file-pdf"></i> Instrumento</a> ';
                                                                }
                                                                echo $btn_instrumentos;
                                                             ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if($session->id_perfil != 7): ?>
                                                            <a href="<?= base_url('index.php/Principal/editarSolicitudConvenio/' . $sol->id_solicitud_convenio) ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                                                        <?php endif; ?>
                                                            <a href="<?= base_url('index.php/Principal/verSolicitudConvenioPDF/' . $sol->id_solicitud_convenio) ?>" target="_blank" class="btn btn-sm btn-info" title="Ver PDF"><i class="fas fa-file-pdf"></i></a>
                                                        <?php if($session->id_perfil != 7): ?>
                                                         <button class="btn btn-sm btn-secondary" title="Adjuntar Archivos" onclick="abrirModalArchivos(<?= $sol->id_solicitud_convenio ?>)"><i class="fas fa-paperclip"></i></button>
                                                        <?php endif; ?>
                                                        <?php if(isset($sol->tienen_archivos) && $sol->tienen_archivos): ?>
                                                                <a href="<?= base_url('index.php/Principal/verArchivosSolicitudConvenio/' . $sol->id_solicitud_convenio) ?>" class="btn btn-sm btn-success" title="Ver Archivos"><i class="fas fa-eye"></i></a>
                                                        <?php endif; ?>

                                                        <?php if($sol->id_estatus == 4 && in_array($session->id_perfil, [1,7])): ?>
                                                                <a onclick="declinaSolicitud(<?= $sol->id_solicitud_convenio ?>);" class="btn btn-sm btn-danger" title="Declinar"><i class="fas fa-times text-white"></i></a>
                                                        <?php endif; ?>

                                                        <?php if(in_array($session->id_perfil, [1,7])): ?>
                                                            <?php if($sol->id_estatus == 4): ?>
                                                                <button class="btn btn-sm btn-primary" title="Subir Instrumento Jurídico" onclick="subirInstrumentoJuridico(<?= $sol->id_solicitud_convenio ?>)"><i class="fas fa-upload"></i> Subir Instrumento</button>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                       
                                                        <?php if($session->id_perfil != 7): ?>
                                                            <button class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarSolicitud(<?= $sol->id_solicitud_convenio ?>)"><i class="fas fa-trash"></i></button>
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
                <form id="formSeleccionArchivos" action="<?= base_url('index.php/Principal/subirArchivosSolicitudConvenio') ?>" method="POST">
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
                                1 => 'Acta de Sesión de Comité',
                                2 => 'Dictamen',
                                3 => 'Validaciones',
                                4 => 'Propuesta de Acciones',
                                5 => 'Autorización de Tratamiento de Datos Personales en Posesión de Sujetos Obligados',
                                6 => 'Escritura Constitutiva y sus modificaciones si es el caso, inscritas en el Registro Público que corresponda',
                                7 => 'Poder del Representante Legal; tratándose de municipios (Acta en la que se autoriza al Presidente a firmar Contratos y Convenios; Acta o nombramiento en la que se designe al Secretario del Ayuntamiento y Nombramiento del Responsable de Seguimiento)',
                                8 => 'Identificación Oficial (Representante Legal)',
                                9 => 'Constancia de Situación Fiscal (R.F.C.); en el caso de persona extranjera remitir el documento análogo en su país de origen.',
                                10 => 'Comprobante de domicilio vigente, en caso de que el domicilio convencional sea distinto al domicilio fiscal.',
                                11 => 'Opinión de Cumplimiento de Obligaciones Fiscales en sentido positivo-SAT ($300,000.00) / Manifestación de Cumplimiento de Obligaciones Fiscales-Formato (menos de $300,000.00)',
                                12 => 'Carta de declaración de intereses; (no será exigible en los convenios celebrados con Municipios).',
                                13 => 'Manifestación de que no se encuentra impedido legalmente, bajo ningún supuesto, que pueda afectar las estipulaciones establecidas en el presente instrumento jurídico.',
                                14 => 'Manifiesto de contar con infraestructura (formato E); (no será exigible en los convenios celebrados con Municipios).'
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
        $('#datatable-convenios').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }
        });
    });

    function declinaSolicitud(id) {
        Swal.fire({
            title: '¿Estás seguro de declinar la solicitud?',
            text: "Ingresa el motivo de la declinación:",
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Escribe el motivo aquí...',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, declinar',
            cancelButtonText: 'Cancelar',
            preConfirm: (motivo) => {
                if (!motivo || motivo.trim() === '') {
                    Swal.showValidationMessage('El motivo es obligatorio');
                    return false;
                }
                return motivo;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const motivo = result.value;
                $.ajax({
                    url: '<?= base_url("index.php/Principal/declinarSolicitudConvenio") ?>',
                    type: 'POST',
                    data: { id_solicitud: id, motivo: motivo },
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
                            Swal.fire('Error!', 'No se pudo declinar el registro.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Ocurrió un error al procesar la solicitud.', 'error');
                    }
                });
            }
        });
    }
    
    function verMotivo(motivo) {
        Swal.fire({
            title: 'Motivo de Declinación',
            text: motivo || 'No se especificó un motivo.',
            icon: 'info',
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#5b73e8'
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
                    url: '<?= base_url("index.php/Principal/eliminarSolicitudConvenio") ?>',
                    type: 'POST',
                    data: { id_solicitud: id },
                    success: function(response) {
                        if (!response.error) {
                            Swal.fire('Eliminado!', 'El registro ha sido eliminado.', 'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', 'No se pudo eliminar el registro.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Ocurrió un error al procesar la solicitud.', 'error');
                    }
                });
            }
        });
    }
    
    function abrirModalArchivos(id) {
        $('#modal_id_solicitud').val(id);
        $('.check-si').prop('checked', false);
        $('#modalSeleccionArchivos').modal('show');
    }

    function enviarFormularioArchivos() {
        $('#formSeleccionArchivos').submit();
    }

    function subirInstrumentoJuridico(id) {
        Swal.fire({
            title: 'Subir Instrumentos Jurídicos',
            input: 'file',
            inputAttributes: {
                'accept': 'application/pdf',
                'multiple': 'multiple',
                'aria-label': 'Subir Instrumentos Jurídicos en PDF'
            },
            showCancelButton: true,
            confirmButtonText: 'Subir',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: (files) => {
                if (!files || files.length === 0) {
                    Swal.showValidationMessage('Por favor selecciona al menos un archivo PDF');
                    return false;
                }
                
                const formData = new FormData();
                for (let i = 0; i < files.length; i++) {
                    formData.append('archivos[]', files[i]);
                }
                formData.append('id_solicitud', id);
                
                return fetch('<?= base_url("index.php/Principal/subirInstrumentoJuridicoConvenio") ?>', {
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
                    Swal.showValidationMessage(`Error: ${error}`);
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
