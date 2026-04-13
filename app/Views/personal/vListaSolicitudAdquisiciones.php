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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Administración</a></li>
                                <li class="breadcrumb-item active">Listado de Solicitudes Adquisiciones</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Solicitudes de Adquisiciones</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12 text-right">
                                    <a href="<?= base_url('index.php/Principal/SolicitudAdquisiciones') ?>" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Nueva Solicitud
                                    </a>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table id="datatable-adquisiciones" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Fecha Registro</th>
                                            <th>Estatus</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($solicitudes) && !empty($solicitudes)): ?>
                                            <?php foreach($solicitudes as $sol): ?>
                                                <tr>
                                                    <td><?= $sol->id_solicitud_adquisiciones ?></td>
                                                    <td><?= isset($sol->fec_reg) ? date('d/m/Y H:i', strtotime($sol->fec_reg)) : '' ?></td>
                                                    <td>
                                                        <?php if(isset($sol->id_estatus) && $sol->id_estatus == 2): ?>
                                                            <button class="btn btn-sm btn-outline-danger font-weight-bold shadow-sm" title="Clic para ver motivo" onclick="verMotivo('<?= htmlspecialchars($sol->motivo ?? '', ENT_QUOTES) ?>')">
                                                                <i class="fas fa-exclamation-triangle"></i> Declinado
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if(isset($sol->id_estatus) && $sol->id_estatus == 4): ?>
                                                            <button class="btn btn-sm btn-soft-warning font-weight-bold" style="cursor: default; pointer-events: none;" title="En revisión por área">
                                                                <i class="fas fa-circle-notch fa-spin mr-1"></i> En Espera
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if(isset($sol->id_estatus) && $sol->id_estatus == 3): ?>
                                                            <button class="btn btn-sm btn-success font-weight-bold" style="cursor: default; pointer-events: none;" title="Aprobado">
                                                                <i class="fas fa-check-circle mr-1"></i> Aprobado
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if(!isset($sol->id_estatus) || $sol->id_estatus == 1): ?>
                                                             <span class="badge badge-secondary">Registrado</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if($session->id_perfil != 7): ?>
                                                            <a href="<?= base_url('index.php/Principal/editarSolicitudAdquisiciones/' . $sol->id_solicitud_adquisiciones) ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                                                        <?php endif; ?>

                                                        <a href="<?= base_url('index.php/Principal/verSolicitudAdquisicionesPDF/' . $sol->id_solicitud_adquisiciones) ?>" target="_blank" class="btn btn-sm btn-info" title="Ver PDF"><i class="fas fa-file-pdf"></i></a>

                                                        <?php if($session->id_perfil != 7): ?>
                                                         <button class="btn btn-sm btn-secondary" title="Adjuntar Archivos" onclick="abrirModalArchivos(<?= $sol->id_solicitud_adquisiciones ?>)"><i class="fas fa-paperclip"></i></button>
                                                        <?php endif; ?>

                                                        <?php if(isset($sol->tienen_archivos) && $sol->tienen_archivos): ?>
                                                                <a href="<?= base_url('index.php/Principal/verArchivosSolicitudAdquisiciones/' . $sol->id_solicitud_adquisiciones) ?>" class="btn btn-sm btn-success" title="Ver Archivos"><i class="fas fa-eye"></i></a>
                                                        <?php endif; ?>
                                                        
                                                        <?php /*
                                                        <?php if($session->id_perfil != 7): ?>
                                                            <button class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarSolicitud(<?= $sol->id_solicitud_adquisiciones ?>)"><i class="fas fa-trash"></i></button>
                                                        <?php endif; ?>
                                                        */ ?>
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
                <form id="formSeleccionArchivos" action="<?= base_url('index.php/Principal/subirArchivosSolicitudAdquisiciones') ?>" method="POST">
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
                                1 => "Anexo Técnico (Términos de referencia)",
                                2 => "Investigación de Mercado (Cotizaciones y consulta PEI)",
                                3 => "Validación de partida restringida (SF)<br>Verificación de Alineación de Información Estratégica (DGIT)<br>Suficiencia presupuestal (R3)<br>Validación DGTIT/CGCS u otra",
                                4 => "Justificación",
                                5 => "Propuesta Técnico Económica (Anexo)",
                                6 => "Aviso de privacidad integral",
                                7 => "Cédula de Registro en el Padrón de Proveedores (Refrendo vigente)",
                                8 => "Escritura Constitutiva/Documento que acredite la legal constitución de la persona moral (Modificaciones sustanciales e inscripción en el Registro Público)",
                                9 => "Documento que acredite la representación de la persona moral (Poder)",
                                10 => "Identificación oficial vigente (Personas morales Representante y Responsable de seguimiento)",
                                11 => "Constancia de Situación Fiscal (RFC)",
                                12 => "Comprobante de domicilio (Sólo cuando sea diferente al domicilio fiscal)",
                                13 => "Opinión de cumplimiento de Obligaciones Fiscales<br>Manifiesto bajo protesta de cumplimiento de Obligaciones Fiscales",
                                14 => "Manifiesto de no encontrarse impedido para Contratar",
                                15 => "Carta de Declaración de intereses",
                                16 => "Manifiesto de contar con infraestructura",
                                17 => "Carta compromiso entrega de bienes (Excepción de Garantía)"
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
        $('#datatable-adquisiciones').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }
        });
    });

    function abrirModalArchivos(id) {
        $('#modal_id_solicitud').val(id);
        $('.check-si').prop('checked', false);
        $('#modalSeleccionArchivos').modal('show');
    }

    function enviarFormularioArchivos() {
        $('#formSeleccionArchivos').submit();
    }
</script>
