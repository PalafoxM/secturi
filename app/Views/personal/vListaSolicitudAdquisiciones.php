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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Administracion</a></li>
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
                                            <th>Responsable Proyecto</th>
                                            <th>Usuario Registro</th>
                                            <th>Estatus</th>
                                            <th>Instrumento Juridico</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($solicitudes)): ?>
                                            <?php foreach ($solicitudes as $sol): ?>
                                                <tr>
                                                    <td><?= $sol->id_solicitud_adquisiciones ?></td>
                                                    <td><?= !empty($sol->fec_reg) ? date('d/m/Y H:i', strtotime($sol->fec_reg)) : '' ?></td>
                                                    <td><?= esc($sol->nombre_proyecto ?? '') ?></td>
                                                    <td><?= esc($sol->nombre_registra ?? '') ?></td>
                                                    <td>
                                                        <?php if ((int) ($sol->id_estatus ?? 1) === 1): ?>
                                                            <span class="badge badge-secondary">Registrado</span>
                                                        <?php endif; ?>
                                                        <?php if ((int) ($sol->id_estatus ?? 0) === 2): ?>
                                                            <button class="btn btn-sm btn-outline-danger font-weight-bold shadow-sm" title="Clic para ver motivo" onclick="verMotivo('<?= htmlspecialchars($sol->motivo ?? '', ENT_QUOTES) ?>')">
                                                                <i class="fas fa-exclamation-triangle"></i> Declinado
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ((int) ($sol->id_estatus ?? 0) === 4): ?>
                                                            <button class="btn btn-sm btn-soft-warning font-weight-bold" style="cursor: default; pointer-events: none;" title="En revision por area juridica">
                                                                <i class="fas fa-circle-notch fa-spin mr-1"></i> En Espera
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ((int) ($sol->id_estatus ?? 0) === 3): ?>
                                                            <span class="badge badge-success">Aprobado</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if (!empty($sol->instrumento_urls)): ?>
                                                            <?php foreach ($sol->instrumento_urls as $index => $instrumento): ?>
                                                                <span class="d-inline-block mb-1">
                                                                    <a href="<?= $instrumento['url'] ?>" target="_blank" class="btn btn-sm btn-success" title="Ver Instrumento <?= $index + 1 ?>">
                                                                        <i class="fas fa-file-pdf"></i> Inst. <?= $index + 1 ?>
                                                                    </a>
                                                                    <?php if (in_array((int) ($session->id_perfil ?? 0), [1, 7], true)): ?>
                                                                        <button type="button" class="btn btn-sm btn-outline-warning" title="Editar Inst. <?= $index + 1 ?>" onclick="editarInstrumentoAdquisiciones(<?= (int) $sol->id_solicitud_adquisiciones ?>, <?= (int) $index ?>)">
                                                                            <i class="fas fa-pen"></i>
                                                                        </button>
                                                                    <?php endif; ?>
                                                                </span>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">Sin instrumento</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($session->id_perfil != 7): ?>
                                                            <a href="<?= base_url('index.php/Principal/editarSolicitudAdquisiciones/' . $sol->id_solicitud_adquisiciones) ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                                                        <?php endif; ?>
                                                        <a href="<?= base_url('index.php/Principal/verSolicitudAdquisicionesPDF/' . $sol->id_solicitud_adquisiciones) ?>" target="_blank" class="btn btn-sm btn-info" title="Ver PDF"><i class="fas fa-file-pdf"></i></a>
                                                        <?php if (!in_array($session->get('id_perfil'), [1, 7], true) && $sol->id_estatus == 1 ): ?>
                                                            <button class="btn btn-sm btn-secondary" title="Adjuntar Archivos" onclick="abrirModalArchivos(<?= $sol->id_solicitud_adquisiciones ?>)"><i class="fas fa-paperclip"></i></button>
                                                        <?php endif; ?>
                                                        <?php if (!empty($sol->tienen_archivos)): ?>
                                                            <a href="<?= base_url('index.php/Principal/verArchivosSolicitudAdquisiciones/' . $sol->id_solicitud_adquisiciones) ?>" class="btn btn-sm btn-success" title="Ver Archivos"><i class="fas fa-eye"></i></a>
                                                        <?php endif; ?>
                                                        <?php if (in_array((int) ($session->id_perfil ?? 0), [1, 7], true)): ?>
                                                            <a onclick="declinaSolicitud(<?= $sol->id_solicitud_adquisiciones ?>);" class="btn btn-sm btn-danger" title="Declinar"><i class="fas fa-times text-white"></i></a>
                                                            <button class="btn btn-sm btn-primary" title="Subir Instrumento Juridico" onclick="subirInstrumentoJuridico(<?= $sol->id_solicitud_adquisiciones ?>)"><i class="fas fa-upload"></i> Subir Instrumento</button>
                                                        <?php endif; ?>
                                                        <?php if (in_array((int) ($session->id_perfil ?? 0), [1, 7], true)): ?>
                                                            <button class="btn btn-sm <?= !empty($sol->no_convenio) ? 'btn-pink' : 'btn-dark' ?>" title="Subir No. Convenio" onclick='subirNoConvenioAdquisiciones(<?= (int) $sol->id_solicitud_adquisiciones ?>, <?= json_encode((string) ($sol->no_convenio ?? ''), JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                                                <i class="fas fa-file-signature"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if (!empty($sol->no_convenio)): ?>
                                                            <button class="btn btn-sm btn-outline-info" title="Ver No. Convenio" onclick='verNoDocumento("No. Convenio", <?= json_encode((string) $sol->no_convenio, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                                                <i class="fas fa-hashtag"></i>
                                                            </button>
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
                <form id="formSeleccionArchivos" action="<?= base_url('index.php/Principal/subirArchivosSolicitudAdquisiciones') ?>" method="POST">
                    <input type="hidden" name="id_solicitud" id="modal_id_solicitud">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr><th style="width: 5%;">Num.</th><th>DOCUMENTO</th><th style="width: 10%; text-align: center;">SI</th></tr>
                        </thead>
                        <tbody>
                            <?php $documentos = [1 => 'Anexo Tecnico (Terminos de referencia)', 2 => 'Investigacion de Mercado (Cotizaciones y consulta PEI)', 3 => 'Validacion de partida / Alineacion / R3 / otra', 4 => 'Justificacion', 5 => 'Propuesta Tecnico Economica', 6 => 'Aviso de privacidad integral', 7 => 'Cedula de Registro en el Padron de Proveedores', 8 => 'Escritura Constitutiva', 9 => 'Documento que acredita la representacion', 10 => 'Identificacion oficial vigente', 11 => 'Constancia de Situacion Fiscal', 12 => 'Comprobante de domicilio', 13 => 'Opinion de cumplimiento / Manifiesto fiscal', 14 => 'Manifiesto de no impedimento', 15 => 'Carta de Declaracion de intereses', 16 => 'Manifiesto de infraestructura', 17 => 'Carta compromiso entrega de bienes']; ?>
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
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.slimscroll.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function(){ $('#datatable-adquisiciones').DataTable({ language:{ url:'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' } }); });
function abrirModalArchivos(id){ $('#modal_id_solicitud').val(id); $('.check-si').prop('checked', false); $('#modalSeleccionArchivos').modal('show'); }
function enviarFormularioArchivos(){ $('#formSeleccionArchivos').submit(); }
function verMotivo(motivo){ Swal.fire({ title:'Motivo de Declinacion', text: motivo || 'No se especifico un motivo.', icon:'info', confirmButtonText:'Cerrar', confirmButtonColor:'#5b73e8' }); }
function verNoDocumento(titulo, valor){ Swal.fire({ title: titulo, text: valor, icon: 'info', confirmButtonText: 'Cerrar', confirmButtonColor: '#5b73e8' }); }
function declinaSolicitud(id){ Swal.fire({ title:'Deseas declinar la solicitud?', text:'Ingresa el motivo de la declinacion:', icon:'warning', input:'textarea', inputPlaceholder:'Escribe el motivo aqui...', showCancelButton:true, confirmButtonColor:'#d33', cancelButtonColor:'#3085d6', confirmButtonText:'Si, declinar', cancelButtonText:'Cancelar', preConfirm:(motivo)=>{ if(!motivo || motivo.trim()===''){ Swal.showValidationMessage('El motivo es obligatorio'); return false; } return motivo; } }).then((result)=>{ if(result.isConfirmed){ $.post('<?= base_url("index.php/Principal/declinarSolicitudAdquisiciones") ?>',{ id_solicitud:id, motivo:result.value },function(response){ if(!response.error){ Swal.fire('Declinado','El registro ha sido declinado.','success').then(()=>location.reload()); } else { Swal.fire('Error','No se pudo declinar el registro.','error'); } }); } }); }
function subirInstrumentoJuridico(id){ Swal.fire({ title:'Subir Instrumentos Juridicos', input:'file', inputAttributes:{ accept:'application/pdf', multiple:'multiple', 'aria-label':'Subir Instrumentos Juridicos en PDF' }, showCancelButton:true, confirmButtonText:'Subir', cancelButtonText:'Cancelar', showLoaderOnConfirm:true, preConfirm:(files)=>{ if(!files || files.length===0){ Swal.showValidationMessage('Selecciona al menos un archivo PDF'); return false; } const formData=new FormData(); for(let i=0;i<files.length;i++){ formData.append('archivos[]', files[i]); } formData.append('id_solicitud', id); return fetch('<?= base_url("index.php/Principal/subirInstrumentoJuridicoAdquisiciones") ?>',{ method:'POST', body:formData }).then(response=>response.json()).catch(error=>{ Swal.showValidationMessage(`Error: ${error}`); }); }, allowOutsideClick:()=>!Swal.isLoading() }).then((result)=>{ if(result.isConfirmed){ if(result.value.error){ Swal.fire('Error', result.value.respuesta || 'Ocurrio un error al subir el archivo.', 'error'); } else { Swal.fire('Exito','El archivo se ha subido correctamente.','success').then(()=>location.reload()); } } }); }
function editarInstrumentoAdquisiciones(id, indice){
    Swal.fire({
        title: 'Editar Instrumento',
        text: 'Seleccione hasta 4 PDFs. Se reemplazaran desde el instrumento elegido.',
        input: 'file',
        inputAttributes: { accept: 'application/pdf', multiple: 'multiple', 'aria-label': 'Subir instrumentos juridicos en PDF' },
        showCancelButton: true,
        confirmButtonText: 'Reemplazar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: (files) => {
            if (!files || files.length === 0) { Swal.showValidationMessage('Selecciona al menos un archivo PDF'); return false; }
            if (files.length > 4) { Swal.showValidationMessage('Solo puedes subir hasta 4 archivos PDF'); return false; }
            const formData = new FormData();
            formData.append('id_solicitud', id);
            formData.append('indice', indice);
            for (let i = 0; i < files.length; i++) { formData.append('archivos[]', files[i]); }
            return fetch('<?= base_url("index.php/Principal/reemplazarInstrumentoAdquisiciones") ?>', { method: 'POST', body: formData })
                .then(response => response.json())
                .catch(error => { Swal.showValidationMessage(`Error: ${error}`); });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (!result.isConfirmed) { return; }
        if (result.value && result.value.error) {
            Swal.fire('Error', result.value.respuesta || 'No se pudo reemplazar el instrumento.', 'error');
        } else {
            Swal.fire('Exito', (result.value && result.value.respuesta) || 'Instrumento reemplazado correctamente.', 'success').then(() => location.reload());
        }
    });
}
function subirNoConvenioAdquisiciones(id, noConvenioActual){
    Swal.fire({
        title: 'Subir No. Convenio',
        input: 'text',
        inputValue: noConvenioActual || '',
        inputPlaceholder: 'Escribe el No. convenio',
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        preConfirm: (noConvenio) => {
            if (!noConvenio || noConvenio.trim() === '') { Swal.showValidationMessage('El No. convenio es requerido'); return false; }
            return $.post('<?= base_url("index.php/Principal/guardarNoConvenioSolicitudAdquisiciones") ?>', {
                id_solicitud_adquisiciones: id,
                no_convenio: noConvenio.trim()
            }).then(response => response).catch(() => {
                Swal.showValidationMessage('No se pudo guardar el No. convenio');
            });
        }
    }).then((result) => {
        if (!result.isConfirmed) { return; }
        const response = result.value || {};
        if (response.error) {
            Swal.fire('Error', response.respuesta || 'No se pudo guardar el No. convenio.', 'error');
        } else {
            Swal.fire('Exito', response.respuesta || 'No. convenio guardado correctamente.', 'success').then(() => location.reload());
        }
    });
}
</script>
