<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('index.php/Principal/ListaSolicitudContrato') ?>">Listado</a></li>
                                <li class="breadcrumb-item active">Subir Archivos</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Carga de Documentación Solicitud #<?= $id_solicitud ?></h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mt-0">Adjuntar Archivos Seleccionados</h4>
                            <p class="text-muted font-13 mb-4">Por favor suba los archivos correspondientes a los documentos seleccionados.</p>

                            <form id="formSubirArchivos" enctype="multipart/form-data">
                                <input type="hidden" name="id_solicitud" value="<?= $id_solicitud ?>">
                                
                                <div class="row">
                                    <?php foreach($documentos as $key => $nombre_doc): ?>
                                    <div class="col-md-6 mb-4">
                                        <div class="card border">
                                            <div class="card-header bg-light">
                                                <h6 class="m-0"><?= $nombre_doc ?></h6>
                                            </div>
                                            <div class="card-body">
                                                <input type="file" name="archivos[<?= $key ?>]" class="form-control-file" accept=".pdf,.jpg,.png,.zip">
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12 text-right">
                                        <a href="<?= base_url('index.php/Principal/ListaSolicitudContrato') ?>" class="btn btn-secondary">Cancelar</a>
                                        <a onclick="guardarArchivosSolicitud();" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Archivos 2</a>
                                    </div>
                                </div>
                            </form>

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
<link href="<?= base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- jQuery  -->
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>

<script src="<?php echo base_url(); ?>assets/pages/jquery.analytics_customers.init.js"></script>



<script src="<?= base_url()?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url()?>assets/js/waves.js"></script>
<script src="<?= base_url()?>assets/js/feather.min.js"></script>

<script>

    function guardarArchivosSolicitud() {
        var form = $('#formSubirArchivos')[0];
        var formData = new FormData(form);

        Swal.fire({
            title: 'Guardando archivos...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '<?= base_url("index.php/Principal/guardarArchivosSolicitud") ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (!response.error) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.respuesta,
                    }).then((result) => {
                        window.location.href = '<?= base_url("index.php/Principal/ListaSolicitudContrato") ?>';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.respuesta
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al subir los archivos.'
                });
            }
        });
    }
</script>
