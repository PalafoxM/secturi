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
                                <li class="breadcrumb-item active">Honorarios</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Solicitudes de Honorarios</h4>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 text-right">
                    <a href="<?= base_url('index.php/Principal/SolicitudHonorarios') ?>" class="btn btn-primary">
                        Nueva Solicitud
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable-honorarios" class="table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Prestador</th>
                                            <th>RFC</th>
                                            <th>Responsable</th>
                                            <th>Vigencia</th>
                                            <th>Monto</th>
                                            <th>Estatus</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($solicitudes)): ?>
                                            <?php foreach ($solicitudes as $solicitud): ?>
                                                <tr>
                                                    <td><?= $solicitud->id_solicitud_honorario ?></td>
                                                    <td><?= esc($solicitud->nombre_prestador ?? '') ?></td>
                                                    <td><?= esc($solicitud->rfc_prestador ?? '') ?></td>
                                                    <td><?= esc($solicitud->responsable_proyecto_nombre ?? '') ?></td>
                                                    <td>
                                                        <?= !empty($solicitud->vigencia_inicio) ? date('d/m/Y', strtotime($solicitud->vigencia_inicio)) : '' ?>
                                                        -
                                                        <?= !empty($solicitud->vigencia_fin) ? date('d/m/Y', strtotime($solicitud->vigencia_fin)) : '' ?>
                                                    </td>
                                                    <td>$<?= number_format((float) ($solicitud->monto_total_contrato ?? 0), 2, '.', ',') ?></td>
                                                    <td>
                                                        <?php if ((int) ($solicitud->id_estatus ?? 1) === 1): ?>
                                                            <span class="badge badge-secondary">Registrado</span>
                                                        <?php endif; ?>
                                                        <?php if ((int) ($solicitud->id_estatus ?? 0) === 2): ?>
                                                            <button class="btn btn-sm btn-outline-danger font-weight-bold shadow-sm" title="Clic para ver motivo" onclick="verMotivo('<?= htmlspecialchars($solicitud->motivo ?? '', ENT_QUOTES) ?>')">
                                                                <i class="fas fa-exclamation-triangle"></i> Declinado
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ((int) ($solicitud->id_estatus ?? 0) === 4): ?>
                                                            <button class="btn btn-sm btn-soft-warning font-weight-bold" style="cursor: default; pointer-events: none;" title="En revision por area juridica">
                                                                <i class="fas fa-circle-notch fa-spin mr-1"></i> En Espera
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ((int) ($solicitud->id_estatus ?? 0) === 3): ?>
                                                            <?php if (!empty($solicitud->instrumento_urls)): ?>
                                                                <?php foreach ($solicitud->instrumento_urls as $index => $instrumento): ?>
                                                                    <a href="<?= $instrumento['url'] ?>" target="_blank" class="btn btn-sm btn-success mb-1" title="Ver Instrumento <?= $index + 1 ?>">
                                                                        <i class="fas fa-file-pdf"></i> Inst. <?= $index + 1 ?>
                                                                    </a>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <span class="badge badge-success">Aprobado</span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($session->id_perfil != 7): ?>
                                                            <a href="<?= base_url('index.php/Principal/editarSolicitudHonorarios/' . $solicitud->id_solicitud_honorario) ?>" class="btn btn-sm btn-warning" title="Editar">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a href="<?= base_url('index.php/Principal/pdfSolicitudHonorarios/' . $solicitud->id_solicitud_honorario) ?>" target="_blank" class="btn btn-sm btn-outline-danger" title="PDF">
                                                            PDF
                                                        </a>
                                                        <?php if ($session->id_perfil != 7): ?>
                                                            <button class="btn btn-sm btn-secondary" title="Adjuntar Archivos" onclick="abrirModalArchivos(<?= $solicitud->id_solicitud_honorario ?>)">
                                                                <i class="fas fa-paperclip"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <a href="<?= base_url('index.php/Principal/verArchivosSolicitudHonorarios/' . $solicitud->id_solicitud_honorario) ?>" class="btn btn-sm btn-success" title="Ver Archivos">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <?php if ((int) ($solicitud->id_estatus ?? 0) === 4 && in_array((int) ($session->id_perfil ?? 0), [1, 7], true)): ?>
                                                            <a onclick="declinaSolicitudHonorarios(<?= $solicitud->id_solicitud_honorario ?>);" class="btn btn-sm btn-danger" title="Declinar">
                                                                <i class="fas fa-times text-white"></i>
                                                            </a>
                                                            <button class="btn btn-sm btn-primary" title="Subir Instrumento Juridico" onclick="subirInstrumentoJuridicoHonorarios(<?= $solicitud->id_solicitud_honorario ?>)">
                                                                <i class="fas fa-upload"></i> Subir Instrumento
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center">No hay solicitudes de honorarios registradas.</td>
                                            </tr>
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

<div class="modal fade" id="modalSeleccionArchivosHonorarios" tabindex="-1" role="dialog" aria-labelledby="modalLabelHonorarios" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabelHonorarios">Seleccion de Documentos a Subir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formSeleccionArchivosHonorarios" action="<?= base_url('index.php/Principal/subirArchivosSolicitudHonorarios') ?>" method="POST">
                    <input type="hidden" name="id_solicitud" id="modal_id_solicitud_honorarios">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr><th style="width: 5%;">Num.</th><th>DOCUMENTO</th><th style="width: 10%; text-align: center;">SI</th></tr>
                        </thead>
                        <tbody>
                            <?php $documentos = [1 => 'Oficio de solicitud', 2 => 'Formato de solicitud de Contrato', 3 => 'Validacion Proceso Ingreso de SFIA', 4 => 'RFC / Cedula de Identificacion Fiscal', 5 => 'Identificacion Oficial', 6 => 'Autorizacion de Tratamiento de Datos Personales en Posesion de Sujetos Obligados', 7 => 'Comprobante de Domicilio']; ?>
                            <?php foreach ($documentos as $key => $doc): ?>
                                <tr>
                                    <td><?= $key ?></td>
                                    <td><?= $doc ?></td>
                                    <td class="text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input check-si-honorarios" id="si_honorarios_<?= $key ?>" name="documentos[<?= $key ?>]" value="<?= $doc ?>">
                                            <label class="custom-control-label" for="si_honorarios_<?= $key ?>"></label>
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
                <button type="button" class="btn btn-primary" onclick="enviarFormularioArchivosHonorarios()">Continuar</button>
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
$(document).ready(function(){
    $('#datatable-honorarios').DataTable({
        language:{ url:'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' }
    });
});
function abrirModalArchivos(id){
    $('#modal_id_solicitud_honorarios').val(id);
    $('.check-si-honorarios').prop('checked', false);
    $('#modalSeleccionArchivosHonorarios').modal('show');
}
function enviarFormularioArchivosHonorarios(){
    $('#formSeleccionArchivosHonorarios').submit();
}
function verMotivo(motivo){
    Swal.fire({ title:'Motivo de Declinacion', text: motivo || 'No se especifico un motivo.', icon:'info', confirmButtonText:'Cerrar', confirmButtonColor:'#5b73e8' });
}
function declinaSolicitudHonorarios(id){
    Swal.fire({
        title:'Deseas declinar la solicitud?',
        text:'Ingresa el motivo de la declinacion:',
        icon:'warning',
        input:'textarea',
        inputPlaceholder:'Escribe el motivo aqui...',
        showCancelButton:true,
        confirmButtonColor:'#d33',
        cancelButtonColor:'#3085d6',
        confirmButtonText:'Si, declinar',
        cancelButtonText:'Cancelar',
        preConfirm:(motivo)=>{
            if(!motivo || motivo.trim()===''){
                Swal.showValidationMessage('El motivo es obligatorio');
                return false;
            }
            return motivo;
        }
    }).then((result)=>{
        if(result.isConfirmed){
            $.post('<?= base_url("index.php/Principal/declinarSolicitudHonorarios") ?>',{ id_solicitud:id, motivo:result.value },function(response){
                if(!response.error){
                    Swal.fire('Declinado','El registro ha sido declinado.','success').then(()=>location.reload());
                } else {
                    Swal.fire('Error','No se pudo declinar el registro.','error');
                }
            });
        }
    });
}
function subirInstrumentoJuridicoHonorarios(id){
    Swal.fire({
        title:'Subir Instrumentos Juridicos',
        input:'file',
        inputAttributes:{ accept:'application/pdf', multiple:'multiple', 'aria-label':'Subir Instrumentos Juridicos en PDF' },
        showCancelButton:true,
        confirmButtonText:'Subir',
        cancelButtonText:'Cancelar',
        showLoaderOnConfirm:true,
        preConfirm:(files)=>{
            if(!files || files.length===0){
                Swal.showValidationMessage('Selecciona al menos un archivo PDF');
                return false;
            }
            const formData=new FormData();
            for(let i=0;i<files.length;i++){
                formData.append('archivos[]', files[i]);
            }
            formData.append('id_solicitud', id);
            return fetch('<?= base_url("index.php/Principal/subirInstrumentoJuridicoHonorarios") ?>',{
                method:'POST',
                body:formData
            }).then(response=>response.json()).catch(error=>{
                Swal.showValidationMessage(`Error: ${error}`);
            });
        },
        allowOutsideClick:()=>!Swal.isLoading()
    }).then((result)=>{
        if(result.isConfirmed){
            if(result.value.error){
                Swal.fire('Error', result.value.respuesta || 'Ocurrio un error al subir el archivo.', 'error');
            } else {
                Swal.fire('Exito','El archivo se ha subido correctamente.','success').then(()=>location.reload());
            }
        }
    });
}
</script>
