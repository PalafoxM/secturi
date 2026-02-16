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
                                                Folio preliminar: <?= $folio_preliminar ?>
                                            </div>

                                            <input type="hidden" name="id_inve" value="<?= isset($id_inve) ? $id_inve : '' ?>">


                                            <form id="formConvenio" method="post" enctype="multipart/form-data">

                                                <!-- ===================== DATOS ADMINISTRATIVOS ===================== -->

                                                <div class="form-row">
                                                    <div class="col-md-4 mb-3">
                                                        <label>Denominación del puesto <span style="color:red;">*</span></label>
                                                        <select class="form-control select2" name="denominacion_puesto" required>
                                                            <option value="">Seleccione...</option>
                                                            <?php foreach ($deno_puesto as $d): ?>
                                                            <option value="<?= $d->id_denominacion ?>">
                                                            <?= $d->dsc_denominacion ?>
                                                            </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label>Denominación del cargo <span style="color:red;">*</span></label>
                                                        <select class="form-control select2" name="denominacion_cargo" required>
                                                            <option value="">Seleccione...</option>
                                                                <?php foreach ($deno_cargo as $d): ?>
                                                            <option value="<?= $d->id_cargo ?>">
                                                                <?= $d->dsc_cargo ?>
                                                            </option>   
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label>Área de adscripción <span style="color:red;">*</span></label>
                                                        <select class="form-control" name="area_adscripcion" required>
                                                            <option value="">Seleccione...</option>
                                                                <?php foreach ($cat_area as $c): ?>
                                                            <option value="<?= $c->id_area_adscripcion ?>">
                                                                <?= $c->dsc_adscripcion ?>
                                                            </option>
                                                                <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- ===================== RESPONSABLE ===================== -->

                                                <div class="form-row">
                                                    <div class="col-md-6 mb-3">
                                                        <label>Nombre completo del solicitante <span style="color:red;">*</span></label>
                                                        <select class="form-control select2" name="id_usuario" required>
                                                            <?php foreach ($usuarios as $p): ?>
                                                                <option value="<?= $p->id_usuario ?>"
                                                                    <?= session()->get('id_usuario') == $p->id_usuario ? 'selected' : '' ?>>
                                                                    <?= $p->nombre_completo ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label>Fecha del evento <span style="color:red;">*</span></label>
                                                        <input type="date" class="form-control" name="fec_eve" required>
                                                    </div>
                                                </div>

                                                <!-- ===================== DATOS DEL SOLICITANTE ===================== -->

                                                <div class="form-row">
                                                    <div class="col-md-3 mb-3">
                                                        <label>Teléfono <span style="color:red;">*</span></label>
                                                        <input type="text" class="form-control" name="telefono" required>
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label>Correo <span style="color:red;">*</span></label>
                                                        <input type="email" class="form-control" name="correo" required>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="col-md-12 mb-3">
                                                        <label>Concepto <span style="color:red;">*</span></label>
                                                        <input type="text" class="form-control" name="concepto" required>
                                                    </div>
                                                </div>

                                                <!-- ===================== ARCHIVOS ===================== -->

                                                <div class="form-row">
                                                    <div class="col-md-6 mb-3">
                                                        <label>INE (Obligatorio) <span style="color:red;">*</span></label>
                                                        <input type="file" class="form-control" name="ine" accept="image/*,application/pdf" required>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label>Recibo firmado</label>
                                                        <input type="file" class="form-control" name="recibo" accept="image/*,application/pdf">
                                                    </div>
                                                </div>

                                                <!-- ===================== EVIDENCIAS ===================== -->

                                                <div class="form-row">
                                                    <div class="col-md-12 mb-2">
                                                        <label>Evidencias de entrega (máximo 8)</label>
                                                    </div>

                                                    <?php for ($i = 1; $i <= 8; $i++): ?>
                                                        <div class="col-md-3 mb-2">
                                                            <input type="file" class="form-control" name="evidencias[]" accept="image/*">
                                                        </div>
                                                    <?php endfor; ?>
                                                </div>

                                                <!-- ===================== BOTÓN ===================== -->

                                                <div class="text-right mt-4">
                                                    <button type="submit" class="btn btn-primary px-4">
                                                        Guardar Convenio
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
                    Confirmar registro
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                ¿Desea registrar el convenio?
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

<!-- Required datatable js -->
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

        if (!this.checkValidity()) {
            this.reportValidity();
            return;
        }

        $('#modalConfirmarConvenio').modal('show');
    });

    $('#btnConfirmarGuardar').on('click', function () {

        const formData = new FormData($('#formConvenio')[0]);
        const $btn = $(this);

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

                if (!res.error) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Convenio registrado',
                        text: res.respuesta
                    }).then(() => {

                        if (res.pdf_url) {
                            window.open(res.pdf_url, '_blank');
                        }

                        window.location.href = '<?= base_url("index.php/Inicio/vInventarioPromocion") ?>';
                    });

                } else {
                    console.log(res);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.detalle ?? res.respuesta
                    });
                }
            },
            complete: function () {
                $btn.prop('disabled', false).text('Sí, registrar');
                $('#modalConfirmarConvenio').modal('hide');
            }
        });

    });

});
</script>
