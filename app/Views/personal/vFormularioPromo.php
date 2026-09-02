<?php
$session = \Config\Services::session();

// Recupera desde sesión (si ya hiciste el set en el controller)
$idConvenio = $idConvenio ?? $session->get('formPromo_id_material_promo');
$idFila     = $idFila     ?? $session->get('formPromo_idArticulo');
$idSalida   = $idSalida   ?? $session->get('formPromo_idSalida');

// Alias homogéneos para que tu HTML no dependa del nombre
$id_material_promo   = $id_material_promo   ?? $idConvenio;
$idArticulo          = $idArticulo          ?? $idFila;
$id_salida_inventario= $id_salida_inventario?? $idSalida;
?>

<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- ===================== tittle ===================== -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title-box">
                        <div class="float">
                            <h4 class="header-title mt-0 mb-0 text-dark border-bottom pb-6">
                                Formulario de Distribución y Seguimiento
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
                                                Contrato: <?= esc($idConvenio) ?> —
                                                Folio: <span class="text-dark">Se asignará al generar</span>
                                            </div>

                                            <form id="formConvenio" method="post" enctype="multipart/form-data">
                                                
                                                <input type="hidden" name="id_material_promo" id="id_material_promo"
                                                    value="<?= esc($id_material_promo ?? $id_convenio_promo ?? $id_convenio ?? '') ?>">

                                                <input type="hidden" name="idArticulo" id="idArticulo"
                                                    value="<?= esc($idArticulo ?? $id_inventario_promo ?? '') ?>">

                                                <input type="hidden" name="idSalida" id="idSalida"
                                                    value="<?= esc($id_salida_inventario ?? $idSalida ?? '') ?>">
                                                <input type="hidden" name="items" id="items" value="">

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
                                                        <label>Concepto</label>
                                                        <input class="form-control" type="text" name="concepto"
                                                        value="<?= isset($registro) ? esc($registro->concepto) : '' ?>"
                                                        placeholder="Ej. Acción por México">
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label>Lugar de Distribución </label>
                                                        <input class="form-control" type="text" name="lugar_entrega" value="<?= isset($registro) ? $registro->lugar : '' ?>" >
                                                    </div>
                                            
                                                    <div class="col-md-4 mb-3">
                                                        <label>Fecha del evento </label>
                                                        <input type="date" class="form-control" name="fec_eve" value="<?= isset($registro) && $registro->fec_eve ? date('Y-m-d', strtotime($registro->fec_eve)) : '' ?>" >
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label>Fecha del recibo </label>
                                                        <input type="date" class="form-control" name="fec_recibo" value="<?= isset($registro) && !empty($registro->fec_recibo) ? date('Y-m-d', strtotime($registro->fec_recibo)) : date('Y-m-d') ?>" >
                                                    </div>
                                                </div>

                                                <!-- PESTAÑAS PARA  DOCUMENTOS -->

                                                <?php if (!empty($productos)): ?>
                                                    <hr>
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h5 class="mb-0">Productos del contrato</h5>
                                                        <small class="text-muted">Selecciona y captura cantidad distribuir</small>
                                                    </div>

                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered" id="tablaProductos">
                                                        <thead>
                                                            <tr class="text-center">
                                                            <th style="width:50px;"><input type="checkbox" id="check_all"></th>
                                                            <th>Producto</th>
                                                            <th style="width:160px;">Stock disponible</th>
                                                            <th style="width:180px;">Cantidad a distribuir</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach($productos as $p): ?>
                                                            <?php
                                                                $idInv = intval($p->id_inventario_promo ?? 0);
                                                                $stock = intval($p->stock ?? 0);
                                                            ?>
                                                            <tr>
                                                                <td class="text-center">
                                                                <input type="checkbox" class="chk-prod" value="<?= $idInv ?>">
                                                                </td>
                                                                <td><?= esc($p->dsc_producto ?? '') ?></td>
                                                                <td class="text-center"><span class="badge badge-soft-success p-2"><?= $stock ?></span></td>
                                                                <td class="text-center">
                                                                <input type="number" class="form-control qty-entrega"
                                                                        data-id="<?= $idInv ?>" min="0" placeholder="0">
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                        </table>
                                                    </div>
                                                    <?php endif; ?>
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

        // Interceptar submit del form (muestra modal de confirmación)
        $(document).on('submit', '#formConvenio', function (e) {
            e.preventDefault();
            $('#modalConfirmarConvenio').modal('show');
        });

        // Confirmar y guardar (AJAX)
        $(document).on('click', '#btnConfirmarGuardar', function (e) {
            e.preventDefault();

            // ✅ Abrir popup en el gesto del click para evitar bloqueo
            let pdfWindow = window.open('', '_blank');

            const $form = $('#formConvenio');
            if (!$form.length) {
            if (pdfWindow) pdfWindow.close();
            return;
            }

            // ✅ FormData (debe existir antes de usarlo)
            const formData = new FormData($form[0]);

            // =======================
            // MODO MULTI (si existe tabla de productos)
            // =======================
            if ($('.chk-prod').length > 0) {
            const items = [];
            let primerId = 0;
            let total = 0;

            $('.chk-prod:checked').each(function(){
                const idInv = parseInt($(this).val() || 0, 10);
                const qty = parseInt($('.qty-entrega[data-id="'+idInv+'"]').val() || 0, 10);

                if (idInv > 0 && qty > 0) {
                items.push({ id_inventario_promo: idInv, cantidad_entregada: qty });
                if (!primerId) primerId = idInv;
                total += qty;
                }
            });

            if (!items.length) {
                Swal.fire('Error', 'Selecciona al menos un producto y captura cantidad mayor a cero.', 'error');
                if (pdfWindow) pdfWindow.close();
                return;
            }

            // compat: idArticulo requerido
            $('#idArticulo').val(String(primerId));

            // guardar items en hidden + en formData
            $('#items').val(JSON.stringify(items));
            formData.set('items', JSON.stringify(items));

            // compat: cantidad total (tu backend legacy la usa en cabecera)
            formData.set('cantidad', String(total));
            }

            // ✅ Validación de IDs requeridos
            let idConvenio = ($('#id_material_promo').val() || '').trim();
            let idArticulo = ($('#idArticulo').val() || '').trim();

            console.log('id_material_promo:', idConvenio);
            console.log('idArticulo:', idArticulo);

            if (!idConvenio || idConvenio === '0' || !idArticulo || idArticulo === '0') {
            Swal.fire('Error', 'Faltan IDs: id_material_promo / idArticulo', 'error');
            if (pdfWindow) pdfWindow.close();
            return;
            }

            // (Opcional) Debug de payload
            // for (let pair of formData.entries()) console.log(pair[0] + ': ' + pair[1]);

            const $btn = $(this);
            const originalText = $btn.text();

            console.log('idSalida:', ($('#idSalida').val() || '').trim());

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
                    console.log('RESPUESTA AJAX:', res);

                    if (res && res.error === false) {

                    // ✅ Enviar el popup al PDF
                    if (res.pdf_url) {
                        if (pdfWindow) pdfWindow.location.href = res.pdf_url;
                    } else {
                        // si no hay PDF, no dejes pestaña en blanco
                        if (pdfWindow) pdfWindow.close();
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Listo',
                        text: res.respuesta || 'Registrado correctamente',
                        timer: 900,
                        showConfirmButton: false
                    });

                    // ✅ Redirigir a inventario del convenio
                    const idMat = (res.id_material_promo || idConvenio).toString();
                    setTimeout(function () {
                        window.location.href = '<?= base_url("index.php/Inicio/InventarioPromocion/") ?>' + idMat;
                    }, 900);

                    } else {
                    const msg = (res && res.respuesta) ? res.respuesta : 'No se pudo guardar';
                    Swal.fire('Error', msg, 'error');
                    if (pdfWindow) pdfWindow.close();
                    }
                },

                error: function (xhr) {
                    console.log('ERROR AJAX:', xhr.status, xhr.responseText);
                    Swal.fire('Error', 'Error AJAX. Revisa consola/network.', 'error');
                    if (pdfWindow) pdfWindow.close();
                },

                complete: function () {
                    $btn.prop('disabled', false).text(originalText);
                    $('#modalConfirmarConvenio').modal('hide');
                }
            });
        });

        // Master: seleccionar todos
        $(document).off('change', '#check_all').on('change', '#check_all', function () {
            const checked = $(this).is(':checked');
            $('.chk-prod').prop('checked', checked);
        });

        // Si desmarcan uno, desmarca el master; si todos marcados, marca master
        $(document).off('change', '.chk-prod').on('change', '.chk-prod', function () {
            const total = $('.chk-prod').length;
            const sel = $('.chk-prod:checked').length;
            $('#check_all').prop('checked', total > 0 && total === sel);
        });

        $(document).on('input', '.qty-entrega', function(){
            const idInv = $(this).data('id');
            const qty = parseInt($(this).val() || 0, 10);
            const $chk = $('.chk-prod[value="'+idInv+'"]');
            if (qty > 0) $chk.prop('checked', true).trigger('change');
        });
    });
</script>
