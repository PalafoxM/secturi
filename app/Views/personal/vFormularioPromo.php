<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- ===================== tittle ===================== -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title-box">
                        <div class="float">
                            <h4 class="header-title mt-0 mb-0 text-dark border-bottom pb-6">
                                Formulario de Entrega y Seguimiento
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="tab-content detail-list" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="general_detail">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="alert alert-info text-center font-weight-bold">
                                                Folio preliminar: <?= $idConvenio ?>
                                            </div>

                                        
                                            <form id="formConvenio" method="post" enctype="multipart/form-data">
                                                <input type="hidden" id="idConvenio" name="idConvenio" value="<?= isset($idConvenio) ? $idConvenio : '' ?>">
                                                <input type="hidden" name="idArticulo" value="<?= isset($idArticulo) ? $idArticulo : '' ?>">
                                                <input type="hidden" name="idSalida" value="<?= isset($idSalida) ? $idSalida : '' ?>">

                                                <div class="form-row">

                                                    <div class="col-md-4 mb-3">
                                                        <label>Nombre completo del solicitante </label>
                                                        <input class="form-control" type="text" name="nombre_solicitante" value="<?= isset($registro) ? $registro->nombre_solicitante : '' ?>" >
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label>Teléfono </label>
                                                        <input type="text" class="form-control" name="telefono" value="<?= isset($registro) ? $registro->telefono : '' ?>" >
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label>Correo </label>
                                                        <input type="email" class="form-control" name="correo" value="<?= isset($registro) ? $registro->correo : '' ?>" >
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="col-md-4 mb-3">
                                                        <label>Puesto</label>
                                                        <input class="form-control" type="text" name="puesto" value="<?= isset($registro) ? $registro->puesto : '' ?>" >
                                                    </div>
                                                                                                
                                                    <div class="col-md-4 mb-3">
                                                        <label>Cantidad </label>
                                                        <input class="form-control" type="number" name="cantidad" value="<?= isset($registro) ? $registro->cantidad : '' ?>" >
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label>Lugar de entrega </label>
                                                        <input class="form-control" type="text" name="lugar_entrega" value="<?= isset($registro) ? $registro->lugar : '' ?>" >
                                                    </div>
                                            
                                                    <div class="col-md-4 mb-3">
                                                        <label>Fecha del evento </label>
                                                        <input type="date" class="form-control" name="fec_eve" value="<?= isset($registro) && $registro->fec_eve ? date('Y-m-d', strtotime($registro->fec_eve)) : '' ?>" >
                                                    </div>
                                                </div>

                                                <!-- PESTAÑAS PARA  DOCUMENTOS -->

                                                <div class="text-right mt-4">
                                                    <button type="submit" class="btn btn-primary px-4">
                                                        Generar Recibo
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>   

<!-- ===================== MODAL CONFIRMAR ===================== -->
<div class="modal fade" id="modalConfirmarConvenio" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    Generar recibo
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                ¿Desea registrar el folio?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btnConfirmarGuardar" class="btn btn-primary btn-sm">
                    Sí, registrar
                </button>
            </div>
        </div>
    </div>
</div>

<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<!-- App css -->
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url()?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />

<!-- jQuery  -->
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.spromoscroll.min.js"></script>

<!--  datatable js -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/responsive.bootstrap4.min.js"></script>

<!-- App js -->
<script src="<?= base_url()?>assets/js/app.js"></script>
<script src="<?= base_url()?>assets/js/waves.js"></script>
<script src="<?= base_url()?>assets/js/feather.min.js"></script>
<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>

<script>
    $(document).ready(function () {

        $('.select2').select2();

        $('#formConvenio').on('submit', function (e) {
            e.preventDefault();
            $('#modalConfirmarConvenio').modal('show');
        });

       $(document).on('click', '#btnConfirmarGuardar', function (e) {

            e.preventDefault(); // 🔥 ESTO ES CLAVE

            const formData = new FormData($('#formConvenio')[0]);
            const $btn = $(this);

            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            $.ajax({
                url: '<?= base_url("index.php/Inicio/guardarConvenio") ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,

                beforeSend: function () {
                    $btn.prop('disabled', true).text('Guardando...');
                },

                success: function (res) {
                    console.log("RESPUESTA:", res);
                },

                error: function (xhr, status, error) {
                    console.log("ERROR AJAX:", xhr.responseText);
                },

                complete: function () {
                    $btn.prop('disabled', false).text('Sí, registrar');
                    $('#modalConfirmarConvenio').modal('hide');
                }
            });
        });
    });
</script>
