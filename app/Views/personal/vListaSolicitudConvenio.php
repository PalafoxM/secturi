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
                                            <th>Responsable Registro</th>
                                            <th>Monto Total</th>
                                            <th>Estatus</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($solicitudes)): ?>
                                            <?php foreach ($solicitudes as $sol): ?>
                                              
                                                <?php if($sol->ok != 2): ?>
                                                <tr>
                                                    <td><?= $sol->id_solicitud_convenio ?></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($sol->fec_reg)) ?></td>
                                                    <td><?= $sol->nombre_proyecto ?></td>
                                                    <td><?= $sol->nombre_registra ?></td>
                                                    <td>$<?= number_format((float) str_replace([',', '$', ' '], '', (string) ($sol->monto_total ?? 0)), 2, '.', ','); ?></td>
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
                                                                    <span class="d-inline-block mb-1">
                                                                        <a href="<?= $instrumento['url'] ?>" target="_blank" class="btn btn-sm btn-success" title="Ver Instrumento <?= $index + 1 ?>">
                                                                            <i class="fas fa-file-pdf"></i> Inst. <?= $index + 1 ?>
                                                                        </a>
                                                                        <?php if (in_array((int) ($session->id_perfil ?? 0), [1, 7], true)): ?>
                                                                            <button type="button" class="btn btn-sm btn-outline-warning" title="Editar Inst. <?= $index + 1 ?>" onclick="editarInstrumentoConvenio(<?= (int) $sol->id_solicitud_convenio ?>, <?= (int) $index ?>)">
                                                                                <i class="fas fa-pen"></i>
                                                                            </button>
                                                                        <?php endif; ?>
                                                                    </span>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <span class="badge badge-success">Aprobado</span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <?php if((int) $sol->id_estatus === 3 && in_array($session->id_perfil, [1, 7], true)): ?>
                                                            <input onclick="envioCRFyAP(this)" id="sol_<?= (int) $sol->id_solicitud_convenio ?>" type="checkbox" name="seleccionados[]" class="ms-3" style="zoom:1;" value="<?= (int) $sol->id_solicitud_convenio ?>" <?= ($sol->ok === 1)? 'checked' : '' ?>>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($session->id_perfil != 7): ?>
                                                            <a href="<?= base_url('index.php/Principal/editarSolicitudConvenio/' . $sol->id_solicitud_convenio) ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                                                        <?php endif; ?>
                                                        <a href="<?= base_url('index.php/Principal/verSolicitudConvenioPDF/' . $sol->id_solicitud_convenio) ?>" target="_blank" class="btn btn-sm btn-info" title="Ver PDF"><i class="fas fa-file-pdf"></i></a>
                                                        <?php if ($session->id_perfil != 7 && $sol->id_estatus ==1): ?>
                                                            <button class="btn btn-sm btn-secondary" title="Adjuntar Archivos" onclick="abrirModalArchivos(<?= $sol->id_solicitud_convenio ?>)"><i class="fas fa-paperclip"></i></button>
                                                        <?php endif; ?>
                                                        <?php if (!empty($sol->tienen_archivos) && $sol->id_estatus != 1): ?>
                                                            <a href="<?= base_url('index.php/Principal/verArchivosSolicitudConvenio/' . $sol->id_solicitud_convenio) ?>" class="btn btn-sm btn-success" title="Ver Archivos"><i class="fas fa-eye"></i></a>
                                                        <?php endif; ?>
                                                        <?php if (in_array($session->id_perfil, [1, 7])): ?>
                                                            <a onclick="declinaSolicitud(<?= $sol->id_solicitud_convenio ?>);" class="btn btn-sm btn-danger" title="Declinar"><i class="fas fa-times text-white"></i></a>
                                                            <button class="btn btn-sm btn-primary" title="Subir Instrumento Juridico" onclick="subirInstrumentoJuridico(<?= $sol->id_solicitud_convenio ?>)"><i class="fas fa-upload"></i> Subir Instrumento</button>
                                                        <?php endif; ?>
                                                        <?php if (in_array((int) ($session->id_perfil ?? 0), [1, 7], true)): ?>
                                                            <button class="btn btn-sm <?=($sol->no_convenio)?'btn-pink':'btn-dark'?>" title="Subir No. Convenio" onclick='subirNoConvenio(<?= (int) $sol->id_solicitud_convenio ?>, <?= json_encode((string) ($sol->no_convenio ?? ''), JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                                                <i class="fas fa-file-signature"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if (!empty($sol->no_convenio)): ?>
                                                            <button class="btn btn-sm btn-outline-info" title="Ver No. Convenio" onclick='verNoDocumento("No. Convenio", <?= json_encode((string) $sol->no_convenio, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                                                <i class="fas fa-hashtag"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ($session->id_perfil != 7): ?>
                                                            <button class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarSolicitud(<?= $sol->id_solicitud_convenio ?>)"><i class="fas fa-trash"></i></button>
                                                        <?php endif; ?>
                                                        <?php if($sol->ok === 1): ?>
                                                            <button class="btn btn-sm btn-pulse-purple" title="Enviar a CRFyAP" onclick="enviarCRFyAP(<?= $sol->id_solicitud_convenio ?>)"><i class="fas fa-paper-plane text-white"></i></button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                              
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formSeleccionArchivos" action="<?= base_url('index.php/Principal/subirArchivosSolicitudConvenio') ?>" method="POST">
                    <input type="hidden" name="id_solicitud" id="modal_id_solicitud">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr><th style="width: 5%;">Num.</th><th>DOCUMENTO</th><th style="width: 10%; text-align: center;">SI</th></tr>
                        </thead>
                        <tbody>
                            <?php $documentos = [1 => 'Acta de Sesion de Comite', 2 => 'Dictamen', 3 => 'Validaciones', 4 => 'Propuesta de Acciones', 5 => 'Autorizacion de Tratamiento de Datos Personales', 6 => 'Escritura Constitutiva y modificaciones', 7 => 'Poder del Representante Legal y nombramientos', 8 => 'Identificacion Oficial', 9 => 'Constancia de Situacion Fiscal', 10 => 'Comprobante de domicilio vigente', 11 => 'Opinion de Cumplimiento de Obligaciones Fiscales', 12 => 'Carta de declaracion de intereses', 13 => 'Manifestacion de no impedimento legal', 14 => 'Manifiesto de contar con infraestructura', 15 => 'Informe de Justificacion de proveedor unico', 16 => 'Padron de proveedores', 17 => 'Suficiencia presupuestal']; ?>
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
const crfyapConvenios = <?= json_encode(array_reduce($solicitudes ?? [], function ($carry, $sol) {
    $instrumentos = $sol->instrumento_urls ?? [];
    $carry[(int) $sol->id_solicitud_convenio] = [
        'id' => (int) $sol->id_solicitud_convenio,
        'nombre_proveedor' => $sol->crfyap_nombre_proveedor ?? $sol->proveedor_nombre ?? $sol->nombre_proveedor ?? $sol->proveedor_rfc ?? 'N/D',
        'no_proveedor' => $sol->crfyap_no_proveedor ?? $sol->id_proveedor ?? $sol->no_proveedor ?? 'N/D',
        'banco' => $sol->crfyap_banco ?? $sol->banco ?? $sol->nombre_banco ?? 'N/D',
        'no_convenio' => $sol->no_convenio ?? '',
        'comentarios' => $sol->objeto_convenio ?? $sol->nombre_proyecto ?? '',
        'instrumentos' => is_array($instrumentos) ? count($instrumentos) : 0,
        'presupuesto' => [[
            'proyecto' => $sol->dsc_proyecto ?? 'N/D',
            'partida' => $sol->cuenta_cable ?? $sol->cuenta_cable ?? 'N/D',
            'importe' => number_format((float) ($sol->monto_total ?? 0), 2, '.', ','),
        ]],
    ];
    return $carry;
}, []), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

$(document).ready(function(){ $('#datatable-convenios').DataTable({ language:{ url:'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' } }); });
function declinaSolicitud(id){ Swal.fire({ title:'Deseas declinar la solicitud?', text:'Ingresa el motivo de la declinacion:', icon:'warning', input:'textarea', inputPlaceholder:'Escribe el motivo aqui...', showCancelButton:true, confirmButtonColor:'#d33', cancelButtonColor:'#3085d6', confirmButtonText:'Si, declinar', cancelButtonText:'Cancelar', preConfirm:(motivo)=>{ if(!motivo || motivo.trim()===''){ Swal.showValidationMessage('El motivo es obligatorio'); return false; } return motivo; } }).then((result)=>{ if(result.isConfirmed){ $.post('<?= base_url("index.php/Principal/declinarSolicitudConvenio") ?>',{ id_solicitud:id, motivo:result.value },function(response){ if(!response.error){ Swal.fire('Declinado','El registro ha sido declinado.','success').then(()=>location.reload()); } else { Swal.fire('Error','No se pudo declinar el registro.','error'); } }); } }); }
function verMotivo(motivo){ Swal.fire({ title:'Motivo de Declinacion', text: motivo || 'No se especifico un motivo.', icon:'info', confirmButtonText:'Cerrar', confirmButtonColor:'#5b73e8' }); }
function verNoDocumento(titulo, valor){ Swal.fire({ title: titulo, text: valor, icon: 'info', confirmButtonText: 'Cerrar', confirmButtonColor: '#5b73e8' }); }
function eliminarSolicitud(id){ Swal.fire({ title:'Deseas eliminar la solicitud?', text:'No podras revertir esto', icon:'warning', showCancelButton:true, confirmButtonColor:'#3085d6', cancelButtonColor:'#d33', confirmButtonText:'Si, eliminar', cancelButtonText:'Cancelar' }).then((result)=>{ if(result.isConfirmed){ $.post('<?= base_url("index.php/Principal/eliminarSolicitudConvenio") ?>',{ id_solicitud:id },function(response){ if(!response.error){ Swal.fire('Eliminado','El registro ha sido eliminado.','success').then(()=>location.reload()); } else { Swal.fire('Error','No se pudo eliminar el registro.','error'); } }); } }); }
function abrirModalArchivos(id){ $('#modal_id_solicitud').val(id); $('.check-si').prop('checked', false); $('#modalSeleccionArchivos').modal('show'); }
function enviarFormularioArchivos(){ $('#formSeleccionArchivos').submit(); }
function subirInstrumentoJuridico(id){ Swal.fire({ title:'Subir Instrumentos Juridicos', input:'file', inputAttributes:{ accept:'application/pdf', multiple:'multiple', 'aria-label':'Subir Instrumentos Juridicos en PDF' }, showCancelButton:true, confirmButtonText:'Subir', cancelButtonText:'Cancelar', showLoaderOnConfirm:true, preConfirm:(files)=>{ if(!files || files.length===0){ Swal.showValidationMessage('Selecciona al menos un archivo PDF'); return false; } if(files.length>4){ Swal.showValidationMessage('Solo puedes subir hasta 4 archivos PDF'); return false; } const formData=new FormData(); for(let i=0;i<files.length;i++){ formData.append('archivos[]', files[i]); } formData.append('id_solicitud', id); return fetch('<?= base_url("index.php/Principal/subirInstrumentoJuridicoConvenio") ?>',{ method:'POST', body:formData }).then(response=>response.json()).catch(error=>{ Swal.showValidationMessage(`Error: ${error}`); }); }, allowOutsideClick:()=>!Swal.isLoading() }).then((result)=>{ if(result.isConfirmed){ if(result.value.error){ Swal.fire('Error', result.value.respuesta || 'Ocurrio un error al subir el archivo.', 'error'); } else { Swal.fire('Exito','El archivo se ha subido correctamente.','success').then(()=>location.reload()); } } }); }
function editarInstrumentoConvenio(id, indice){
    Swal.fire({
        title: 'Editar Instrumento',
        text: 'Seleccione hasta 4 PDFs. Se reemplazaran desde el instrumento elegido.',
        input: 'file',
        inputAttributes: {
            accept: 'application/pdf',
            multiple: 'multiple',
            'aria-label': 'Subir instrumentos juridicos en PDF'
        },
        showCancelButton: true,
        confirmButtonText: 'Reemplazar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: (files) => {
            if (!files || files.length === 0) {
                Swal.showValidationMessage('Selecciona al menos un archivo PDF');
                return false;
            }
            if (files.length > 4) {
                Swal.showValidationMessage('Solo puedes subir hasta 4 archivos PDF');
                return false;
            }
            const formData = new FormData();
            formData.append('id_solicitud', id);
            formData.append('indice', indice);
            for (let i = 0; i < files.length; i++) {
                formData.append('archivos[]', files[i]);
            }
            return fetch('<?= base_url("index.php/Principal/reemplazarInstrumentoConvenio") ?>', {
                method: 'POST',
                body: formData
            }).then(response => response.json()).catch(error => {
                Swal.showValidationMessage(`Error: ${error}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }
        if (result.value && result.value.error) {
            Swal.fire('Error', result.value.respuesta || 'No se pudo reemplazar el instrumento.', 'error');
        } else {
            Swal.fire('Exito', (result.value && result.value.respuesta) || 'Instrumento reemplazado correctamente.', 'success').then(() => location.reload());
        }
    });
}
function subirNoConvenio(id, noConvenioActual){
    Swal.fire({
        title: 'Subir No. Convenio',
        input: 'text',
        inputValue: noConvenioActual || '',
        inputPlaceholder: 'Escribe el No. convenio',
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        preConfirm: (noConvenio) => {
            if (!noConvenio || noConvenio.trim() === '') {
                Swal.showValidationMessage('El No. convenio es requerido');
                return false;
            }
            return $.post('<?= base_url("index.php/Principal/guardarNoConvenioSolicitudConvenio") ?>', {
                id_solicitud_convenio: id,
                no_convenio: noConvenio.trim()
            }).then(response => response).catch(() => {
                Swal.showValidationMessage('No se pudo guardar el No. convenio');
            });
        }
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }
        const response = result.value || {};
        if (response.error) {
            Swal.fire('Error', response.respuesta || 'No se pudo guardar el No. convenio.', 'error');
        } else {
            Swal.fire('Exito', response.respuesta || 'No. convenio guardado correctamente.', 'success').then(() => location.reload());
        }
    });
}

function envioCRFyAP(e){
    if(e.checked){
        Swal.fire({
            title: 'Envio de Solicitud',
            text: '¿Se activara el envio de la solicitud de convenio a CRFyAP?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url("index.php/Principal/activarEnvioSolicitudConvenio") ?>', {
                    id_solicitud_convenio: e.value
                }).then(response => response).catch(() => {
                    Swal.showValidationMessage('No se pudo guardar el No. convenio');
                });
                const response = result.value || {};
                if (response.error) {
                    Swal.fire('Error', response.respuesta || 'No se pudo guardo la activacion.', 'error');
                } else {
                    Swal.fire('Exito', response.respuesta || 'Activacion guardada correctamente.', 'success').then(() => location.reload() );
                }
                
            }else{
                $(e).prop('checked', false);
            }
        })
        
    }
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, function(character) {
        return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[character];
    });
}

function construirPreviewCRFyAP(datos) {
    const presupuesto = Array.isArray(datos.presupuesto) ? datos.presupuesto : [];
    const filasPresupuesto = presupuesto.map(row => `
        <tr>
            <td>${escapeHtml(row.proyecto || 'N/D')}</td>
            <td>${escapeHtml(row.partida || 'N/D')}</td>
            <td style="text-align:right;">$${escapeHtml(row.importe || '0.00')}</td>
        </tr>
    `).join('');

    return `
        <div style="text-align:left;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px 22px; margin-bottom:16px;">
                <div>
                    <div style="font-size:12px; color:#5b6aa0; margin-bottom:5px;">Nombre Proveedor</div>
                    <div style="border:1px solid #e1e6f0; border-radius:4px; padding:9px 12px; min-height:22px;">${escapeHtml(datos.nombre_proveedor)}</div>
                </div>
                <div>
                    <div style="font-size:12px; color:#5b6aa0; margin-bottom:5px;">No. Proveedor</div>
                    <div style="border:1px solid #e1e6f0; border-radius:4px; padding:9px 12px; min-height:22px;">${escapeHtml(datos.no_proveedor)}</div>
                </div>
                <div>
                    <div style="font-size:12px; color:#5b6aa0; margin-bottom:5px;">Banco</div>
                    <div style="border:1px solid #e1e6f0; border-radius:4px; padding:9px 12px; min-height:22px;">${escapeHtml(datos.banco)}</div>
                </div>
                <div>
                    <div style="font-size:12px; color:#5b6aa0; margin-bottom:5px;">No. Convenio/Contrato</div>
                    <div style="border:1px solid #e1e6f0; border-radius:4px; padding:9px 12px; min-height:22px;">${escapeHtml(datos.no_convenio || 'Sin capturar')}</div>
                </div>
                <div>
                    <div style="font-size:12px; color:#5b6aa0; margin-bottom:5px;">Instrumento Juridico</div>
                    <div style="border:1px solid #e1e6f0; border-radius:4px; padding:9px 12px; min-height:22px;">${escapeHtml(datos.instrumentos)} archivo(s)</div>
                </div>
                <div>
                    <div style="font-size:12px; color:#5b6aa0; margin-bottom:5px;">Comentarios</div>
                    <div style="border:1px solid #e1e6f0; border-radius:4px; padding:9px 12px; min-height:48px;">${escapeHtml(datos.comentarios || 'Sin comentarios')}</div>
                </div>
            </div>
            <div style="font-weight:700; color:#6b7fc0; margin:8px 0 10px;">PRESUPUESTO</div>
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <thead>
                    <tr style="background:#eef2f8; color:#30446f;">
                        <th style="padding:10px; text-align:left;">PROYECTO-META</th>
                        <th style="padding:10px; text-align:left;">PARTIDA</th>
                        <th style="padding:10px; text-align:right;">IMPORTE</th>
                    </tr>
                </thead>
                <tbody>
                    ${filasPresupuesto || '<tr><td colspan="3" style="padding:10px; text-align:center;">Sin presupuesto</td></tr>'}
                </tbody>
            </table>
        </div>
    `;
}

function enviarCRFyAP(id){
    const datos = crfyapConvenios[id] || { presupuesto: [] };
    Swal.fire({
        title: 'Previsualizacion de envio a CRFyAP',
        html: construirPreviewCRFyAP(datos),
        width: 760,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Enviar a CRFyAP',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const formData = new FormData();
            formData.append('id_solicitud_convenio', id);
            return fetch('<?= base_url("index.php/Principal/enviarCRFyAPConvenio") ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .catch(() => {
                Swal.showValidationMessage('No se pudo guardar el envio.');
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }

        const response = result.value || {};
        if (response.error) {
            Swal.fire('Error', response.respuesta || 'No se pudo guardar el envio.', 'error');
        } else {
            Swal.fire('Exito', response.respuesta || 'El envio se realizo correctamente.', 'success').then(() => window.location.href = '<?= base_url("index.php/Principal/listaReservaPT") ?>');
        }
    });
}
</script>
