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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Juridico</a></li>
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
                                            <th>Responsable Proyecto</th>
                                            <th>Usuario Registro</th>
                                            <th>Monto</th>
                                            <th>Estatus</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($solicitudes)): ?>
                                            <?php foreach ($solicitudes as $sol): ?>
                                                <tr>
                                                    <td><?= $sol->id_solicitud_contrato ?></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($sol->fec_reg)) ?></td>
                                                    <td><?= $sol->nombre_proyecto ?></td>
                                                    <td><?= $sol->nombre_registra ?></td>
                                                    <td><?= $sol->monto_total; ?></td>
                                                    <td>
                                                        <?php if ((int) $sol->id_estatus === 1): ?>
                                                            <span class="badge badge-secondary">Registrado</span>
                                                        <?php endif; ?>
                                                        <?php if ((int) $sol->id_estatus === 2): ?>
                                                            <button class="btn btn-sm btn-outline-danger font-weight-bold shadow-sm" title="Clic para ver motivo" onclick="verMotivo('<?= htmlspecialchars($sol->motivo ?? '', ENT_QUOTES) ?>')">
                                                                <i class="fas fa-exclamation-triangle"></i> Declinado
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ((int) $sol->id_estatus === 4): ?>
                                                            <button class="btn btn-sm btn-soft-warning font-weight-bold" style="cursor: default; pointer-events: none;" title="En revision por area juridica">
                                                                <i class="fas fa-circle-notch fa-spin mr-1"></i> En Espera
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ((int) $sol->id_estatus === 3): ?>
                                                            <?php if (!empty($sol->instrumento_urls)): ?>
                                                                <?php foreach ($sol->instrumento_urls as $index => $instrumento): ?>
                                                                    <a href="<?= $instrumento['url'] ?>" target="_blank" class="btn btn-sm btn-success mb-1" title="Ver Instrumento <?= $index + 1 ?>"><i class="fas fa-file-pdf"></i> Inst. <?= $index + 1 ?></a>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <span class="badge badge-success">Aprobado</span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <?php if (in_array((int) ($session->id_perfil ?? 0), [1, 7], true) && !empty($sol->tienen_archivos)): ?>
                                                            <a href="<?= base_url('index.php/Principal/verArchivosSolicitud/' . $sol->id_solicitud_contrato) ?>" class="btn btn-sm btn-warning mt-1" title="Editar archivos">
                                                                <i class="fas fa-edit"></i> Editar archivos
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($session->id_perfil != 7): ?>
                                                            <a href="<?= base_url('index.php/Principal/editarSolicitudContrato/' . $sol->id_solicitud_contrato) ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                                                        <?php endif; ?>
                                                      
                                                            <a href="<?= base_url('index.php/Principal/verSolicitudContratoPDF/' . $sol->id_solicitud_contrato) ?>" target="_blank" class="btn btn-sm btn-info" title="Ver PDF"><i class="fas fa-file-pdf"></i></a>
                                                      
                                                        <?php if ($session->id_perfil != 7 && in_array((int) $sol->id_estatus, [1], true)): ?>
                                                            <button class="btn btn-sm btn-secondary" title="Adjuntar Archivos" onclick="abrirModalArchivos(<?= $sol->id_solicitud_contrato ?>)"><i class="fas fa-paperclip"></i></button>
                                                        <?php endif; ?>
                                                        <?php if (!empty($sol->tienen_archivos)): ?>
                                                            <a href="<?= base_url('index.php/Principal/verArchivosSolicitud/' . $sol->id_solicitud_contrato) ?>" class="btn btn-sm btn-success" title="Ver Archivos"><i class="fas fa-eye"></i></a>
                                                        <?php endif; ?>
                                                        <?php if ((int) $sol->id_estatus === 4 && in_array($session->id_perfil, [1, 7])): ?>
                                                            <a onclick="declinaSolicitud(<?= $sol->id_solicitud_contrato ?>);" class="btn btn-sm btn-danger" title="Declinar"><i class="fas fa-times text-white"></i></a>
                                                            <button class="btn btn-sm btn-primary" title="Subir Instrumento Juridico" onclick="subirInstrumentoJuridico(<?= $sol->id_solicitud_contrato ?>)"><i class="fas fa-upload"></i> Subir Instrumento</button>
                                                        <?php endif; ?>
                                                        <?php if (in_array((int) ($session->id_perfil ?? 0), [1, 6], true)): ?>
                                                            <button class="btn btn-sm btn-dark" title="Subir No. Contrato" onclick='subirNoContrato(<?= (int) $sol->id_solicitud_contrato ?>, <?= json_encode((string) ($sol->no_contrato ?? ''), JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                                                <i class="fas fa-file-signature"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ($session->id_perfil != 7): ?>
                                                            <button class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarSolicitud(<?= $sol->id_solicitud_contrato ?>)"><i class="fas fa-trash"></i></button>
                                                        <?php endif; ?>
                                                        <?php if ($sol->id_estatus == 3 && $session->id_usuario == 1): ?>
                                                            <a onclick="liberarSolicitud(<?= $sol->id_solicitud_contrato ?>);" class="btn btn-sm btn-pulse-purple" title="Enviar a CRFyAP"><i class="fas fa-paper-plane text-white"></i></a>
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

<div class="modal fade" id="modalSeleccionArchivos" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Seleccion de Documentos a Subir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formSeleccionArchivos" action="<?= base_url('index.php/Principal/subirArchivosSolicitud') ?>" method="POST">
                    <input type="hidden" name="id_solicitud" id="modal_id_solicitud">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr><th style="width: 5%;">Num.</th><th>DOCUMENTO</th><th style="width: 10%; text-align: center;">SI</th></tr>
                        </thead>
                        <tbody>
                            <?php $documentos = [1 => 'Anexo Tecnico', '2a' => 'Investigacion de Mercado', '2b' => 'Analisis de Ofertas turisticas', '2c' => 'Argumentacion Tecnica', '3a' => 'Validacion de partida', '3b' => 'Alineacion Estrategica', '3c' => 'Suficiencia presupuestal', '3d' => 'Validacion complementaria', 4 => 'Justificacion', 5 => 'Propuesta Tecnico Economica', 6 => 'Aviso de privacidad integral', 7 => 'Cedula de Proveedores', 8 => 'Escritura Constitutiva', 9 => 'Poder', 10 => 'Identificacion oficial', 11 => 'Constancia fiscal', 12 => 'Comprobante de domicilio', '13a' => 'Opinion fiscal', '13b' => 'Manifiesto fiscal', 14 => 'Manifiesto de no impedimento', 15 => 'Declaracion de intereses', 16 => 'Manifiesto de infraestructura']; ?>
                            <?php foreach ($documentos as $key => $doc): ?>
                                <tr><td><?= $key ?></td><td><?= $doc ?></td><td class="text-center"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input check-si" id="si_<?= $key ?>" name="documentos[<?= $key ?>]" value="<?= $doc ?>"><label class="custom-control-label" for="si_<?= $key ?>"></label></div></td></tr>
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
<style>
@keyframes pulse-animation-purple {
  0% { box-shadow: 0 0 0 0 rgba(156, 39, 176, 0.7); }
  70% { box-shadow: 0 0 0 10px rgba(156, 39, 176, 0); }
  100% { box-shadow: 0 0 0 0 rgba(156, 39, 176, 0); }
}
.btn-pulse-purple {
  background-color: #9c27b0 !important;
  border-color: #9c27b0 !important;
  color: white !important;
  animation: pulse-animation-purple 2s infinite;
}
.btn-pulse-purple:hover {
  background-color: #7b1fa2 !important;
  border-color: #7b1fa2 !important;
  color: white !important;
}
</style>
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.slimscroll.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function(){ $('#datatable-contratos').DataTable({ language:{ url:'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' } }); });
function declinaSolicitud(id){ Swal.fire({ title:'Deseas declinar la solicitud?', text:'Ingresa el motivo de la declinacion:', icon:'warning', input:'textarea', inputPlaceholder:'Escribe el motivo aqui...', showCancelButton:true, confirmButtonColor:'#d33', cancelButtonColor:'#3085d6', confirmButtonText:'Si, declinar', cancelButtonText:'Cancelar', preConfirm:(motivo)=>{ if(!motivo || motivo.trim()===''){ Swal.showValidationMessage('El motivo es obligatorio'); return false; } return motivo; } }).then((result)=>{ if(result.isConfirmed){ $.post('<?= base_url("index.php/Principal/declinarSolicitudContrato") ?>',{ id_solicitud:id, motivo:result.value },function(response){ if(!response.error){ Swal.fire('Declinado','El registro ha sido declinado.','success').then(()=>location.reload()); } else { Swal.fire('Error','No se pudo declinar el registro.','error'); } }); } }); }
function verMotivo(motivo){ Swal.fire({ title:'Motivo de Declinacion', text: motivo || 'No se especifico un motivo.', icon:'info', confirmButtonText:'Cerrar', confirmButtonColor:'#5b73e8' }); }
function eliminarSolicitud(id){ Swal.fire({ title:'Deseas eliminar la solicitud?', text:'No podras revertir esto', icon:'warning', showCancelButton:true, confirmButtonColor:'#3085d6', cancelButtonColor:'#d33', confirmButtonText:'Si, eliminar', cancelButtonText:'Cancelar' }).then((result)=>{ if(result.isConfirmed){ $.post('<?= base_url("index.php/Principal/eliminarSolicitudContrato") ?>',{ id_solicitud:id },function(response){ if(!response.error){ Swal.fire('Eliminado','El registro ha sido eliminado.','success').then(()=>location.reload()); } else { Swal.fire('Error','No se pudo eliminar el registro.','error'); } }); } }); }
function abrirModalArchivos(id){ $('#modal_id_solicitud').val(id); $('.check-si').prop('checked', false); $('#modalSeleccionArchivos').modal('show'); }
function enviarFormularioArchivos(){ $('#formSeleccionArchivos').submit(); }
function subirInstrumentoJuridico(id){ Swal.fire({ title:'Subir Instrumentos Juridicos', input:'file', inputAttributes:{ accept:'application/pdf', multiple:'multiple', 'aria-label':'Subir Instrumentos Juridicos en PDF' }, showCancelButton:true, confirmButtonText:'Subir', cancelButtonText:'Cancelar', showLoaderOnConfirm:true, preConfirm:(files)=>{ if(!files || files.length===0){ Swal.showValidationMessage('Selecciona al menos un archivo PDF'); return false; } const formData=new FormData(); for(let i=0;i<files.length;i++){ formData.append('archivos[]', files[i]); } formData.append('id_solicitud', id); return fetch('<?= base_url("index.php/Principal/subirInstrumentoJuridico") ?>',{ method:'POST', body:formData }).then(response=>response.json()).catch(error=>{ Swal.showValidationMessage(`Error: ${error}`); }); }, allowOutsideClick:()=>!Swal.isLoading() }).then((result)=>{ if(result.isConfirmed){ if(result.value.error){ Swal.fire('Error', result.value.respuesta || 'Ocurrio un error al subir el archivo.', 'error'); } else { Swal.fire('Exito','El archivo se ha subido correctamente.','success').then(()=>location.reload()); } } }); }
function subirNoContrato(id, noContratoActual){
    Swal.fire({
        title: 'Subir No. Contrato',
        input: 'text',
        inputValue: noContratoActual || '',
        inputPlaceholder: 'Escribe el No. contrato',
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        preConfirm: (noContrato) => {
            if (!noContrato || noContrato.trim() === '') {
                Swal.showValidationMessage('El No. contrato es requerido');
                return false;
            }
            return $.post('<?= base_url("index.php/Principal/guardarNoContratoSolicitudContrato") ?>', {
                id_solicitud_contrato: id,
                no_contrato: noContrato.trim()
            }).then(response => response).catch(() => {
                Swal.showValidationMessage('No se pudo guardar el No. contrato');
            });
        }
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }
        const response = result.value || {};
        if (response.error) {
            Swal.fire('Error', response.respuesta || 'No se pudo guardar el No. contrato.', 'error');
        } else {
            Swal.fire('Exito', response.respuesta || 'No. contrato guardado correctamente.', 'success').then(() => location.reload());
        }
    });
}
function liberarSolicitud(id_solicitud){
    Swal.fire({
        title: 'Enviar a CRFyAP',
        text: '¿Estás seguro de enviar esta solicitud para que pase a revisión?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, enviar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("index.php/Principal/liberarSolicitudContrato") ?>',
                type: 'POST',
                data: {
                    id_solicitud_contrato: id_solicitud
                },
                success: function(response) {
                    if (response.error) {
                        Swal.fire({
                            title: 'Error',
                            text: response.respuesta,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        Swal.fire({
                            title: '¡Liberada!',
                            text: 'La solicitud ha sido liberada exitosamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                     
                             window.location.href = "<?php echo base_url() ?>" + "index.php/Principal/listaReservaPT";
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error de Conexión',
                        text: 'No se pudo establecer conexión con el servidor.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }
    });
}
</script>
