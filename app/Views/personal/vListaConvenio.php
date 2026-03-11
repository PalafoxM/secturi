<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Contratos</a></li>
                                <li class="breadcrumb-item active">Lista</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Relación de Contratos</h4>
                    </div>
                </div>
            </div>
            <!-- end page title end breadcrumb -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="button" class="btn btn-primary" onclick="agregarConvenio()">
                                        <i class="fas fa-plus"></i> Nuevo Contrato
                                    </button>
                                </div>
                            </div>

                            <br>

                            <div class="table">
                                <table id="tablaConvenios" class="table table-striped table-bordered" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Contrato</th>
                                            <th>Monto</th>
                                            <th>RFC (Ticket)</th>
                                            <th>Razón Social</th>
                                            <th>No. Proveedor</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($material_promo) && !empty($material_promo)): ?>
                                            <?php foreach ($material_promo as $item): ?>
                                                <tr>
                                                    <td><?= (int)$item->id_material_promo ?></td>
                                                    <td><?= esc($item->convenio) ?></td>
                                                    <td>$<?= number_format((float)($item->monto ?? 0), 2, '.', ',') ?></td>
                                                    <td><?= esc($item->dsc_tiket) ?></td>
                                                    <td><?= esc($item->razon_social) ?></td>
                                                    <td><?= esc($item->no_proveedor) ?></td>
                                                    <td class="text-nowrap">

                                                        <button type="button"
                                                                class="btn btn-warning btn-sm btn-editar"
                                                                data-item='<?= esc(json_encode($item), 'attr') ?>'
                                                                title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <button type="button"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="eliminarConvenio(<?= (int)$item->id_material_promo ?>)"
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>

                                                        <a href="<?= base_url('index.php/Inicio/InventarioPromocion/' . (int)$item->id_material_promo) ?>"
                                                           class="btn btn-success btn-sm btn-ver-detalles"
                                                           title="Ver inventario">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

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

<!-- Modal -->
<div class="modal fade" id="modalConvenio" tabindex="-1" role="dialog" aria-labelledby="modalConvenioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConvenioLabel">Nuevo Convenio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formConvenio">
                <div class="modal-body">
                    <input type="hidden" name="id_material_promo" id="id_material_promo" value="0">

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="convenio">Convenio <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="convenio" id="convenio" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="monto">Monto <span class="text-danger">*</span></label>
                            <input type="text"
                            class="form-control input-importe"
                            name="monto"
                            id="monto"
                            placeholder="$0.00"
                            inputmode="decimal"
                            autocomplete="off"
                            required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="no_proveedor">No. Proveedor <span class="text-danger">*</span></label>
                            <select name="id_proveedor" id="id_proveedor" style="width:100%"></select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardaConvenio()">Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>

<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />

<!-- jQuery  -->
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>

<!-- Required datatable js -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

<!-- App js -->
<script src="<?= base_url() ?>assets/js/app.js"></script>
<script src="<?= base_url() ?>assets/js/waves.js"></script>
<!-- <script src <?= base_url() ?>assets/js/feather.min.js"></script> -->

<script src="<?= base_url() ?>plugins/tiny-editable/mindmup-editabletable.js"></script>
<script src="<?= base_url() ?>plugins/tiny-editable/numeric-input-example.js"></script>
<script src="<?= base_url() ?>plugins/bootable/bootstable.js"></script>

<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>

<script>
    $(document).ready(function() {

        // Editar
        $(document).off('click', '.btn-editar').on('click', '.btn-editar', function (e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            try {
            const raw = $(this).attr('data-item');
            console.log('data-item editar =>', raw);
            const item = JSON.parse(raw);
            editarConvenio(item);
            } catch (err) {
            console.error('Error al parsear data-item:', err, $(this).attr('data-item'));
            }
        });

        $('#tablaConvenios').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' },
            destroy: true,
            searching: true,
            responsive: false,
            autoWidth: false
        });

        // Select2 proveedor
        $('#id_proveedor').select2({
            dropdownParent: $('#modalConvenio'),
            ajax: {
            url: '<?= base_url("index.php/Inicio/buscarProveedor2") ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) { return { q: params.term }; },
            processResults: function(data) { return { results: data.results }; },
            cache: true
            },
            placeholder: 'Buscar proveedor por nombre, RFC o No. proveedor',
            minimumInputLength: 1,
            allowClear: true
        });

    });

    // =========================
    // Formato moneda
    // =========================
    function toNumberString(val) {
        val = (val || '').toString().replace(/[^0-9.]/g, '');
        const parts = val.split('.');
        if (parts.length > 2) val = parts.shift() + '.' + parts.join('');
        if (val === '.') val = '0';
        return val;
    }

    function formatCurrency(val) {
        const clean = toNumberString(val);
        const num = parseFloat(clean);
        if (isNaN(num)) return '';
        return '$' + num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function guardaConvenio() {

        const idActual = parseInt($('#id_material_promo').val(), 10) || 0;
        const esNuevo = (idActual === 0);

        // Validar proveedor (Select2)
        const idProv = $('#id_proveedor').val();
        if (!idProv) {
            Swal.fire('Falta proveedor', 'Selecciona un proveedor para poder guardar.', 'warning');
            return;
        }

        let montoVal = toNumberString($('#monto').val());
        if (!montoVal || parseFloat(montoVal) <= 0) {
            Swal.fire('Error', 'El monto es requerido', 'error');
            return;
        }

        const convenioVal = ($('#convenio').val() || '').trim();
        if (!convenioVal) {
            Swal.fire('Error', 'El convenio/contrato es requerido', 'error');
            return;
        }

        // Enviar limpio
        $('#monto').val(montoVal);
        const montoPretty = formatCurrency(montoVal);
        $('#monto').val(montoPretty);

        $.ajax({
            url: '<?= base_url("index.php/Agregar/guardaConvenio") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
            id_material_promo: idActual,     // compat (tu backend ya lo acepta)
            // si quieres, también puedes mandar id_convenio_promo: idActual
            monto: montoVal,
            convenio: convenioVal,
            id_proveedor: idProv
            },
            success: function (response) {
            if (response && response.error === false) {

                const idGuardado =
                response.id_convenio_promo ||
                response.id_material_promo ||
                idActual;

                Swal.fire({
                title: 'Éxito',
                text: response.respuesta || 'Registro guardado correctamente.',
                icon: 'success',
                timer: 1200,
                showConfirmButton: false
                }).then(() => {
                if (esNuevo && idGuardado) {
                    window.location.href = "<?= base_url('index.php/Inicio/InventarioPromocion/') ?>" + idGuardado;
                } else {
                    location.reload();
                }
                });

            } else {
                Swal.fire('Error', (response && response.respuesta) ? response.respuesta : 'No se pudo guardar.', 'error');
            }
            },
            error: function (xhr) {
            console.log('AJAX ERROR:', xhr.status, xhr.responseText);
            Swal.fire('Error', 'Ocurrió un error al guardar. Revisa consola/network.', 'error');
            }
        });
    }

    // Formatear al perder foco
    $(document).on('blur', '.input-importe', function () {
        const formatted = formatCurrency($(this).val());
        $(this).val(formatted !== '' ? formatted : '');
    });

    // Al enfocar, quita formato
    $(document).on('focus', '.input-importe', function () {
        $(this).val(toNumberString($(this).val()));
    });

    // =========================
    // Modal nuevo/editar
    // =========================
    function agregarConvenio() {
        $('#formConvenio')[0].reset();
        $('#id_material_promo').val(0);
        $('#id_proveedor').empty().trigger('change');
        $('#modalConvenioLabel').text('Nuevo Convenio');
        $('#modalConvenio').modal('show');
    }

    function editarConvenio(item) {
        $('#formConvenio')[0].reset();

        // ✅ OJO: tu PK real es id_convenio_promo, pero tu front usa id_material_promo como compat
        $('#id_material_promo').val(item.id_convenio_promo || item.id_material_promo || 0);

        $('#convenio').val(item.convenio);
        $('#monto').val(formatCurrency(item.monto));

        const idProv = item.id_proveedor || null;
        const textProv = (item.razon_social ? item.razon_social : '') + ' - ' + (item.no_proveedor ? item.no_proveedor : '');

        $('#id_proveedor').empty().trigger('change');

        if (idProv) {
            const option = new Option(textProv.trim(), idProv, true, true);
            $('#id_proveedor').append(option).trigger('change');
        }

        $('#modalConvenioLabel').text('Editar Convenio');
        $('#modalConvenio').modal('show');
    }

    // =========================
    // Eliminar
    // =========================
    function eliminarConvenio(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("index.php/Agregar/eliminarConvenio") ?>',
                type: 'POST',
                dataType: 'json',
                data: { id_material_promo: id },
                success: function(response) {
                if (!response.error) {
                    Swal.fire('Eliminado!', response.respuesta || 'Registro eliminado.', 'success')
                    .then(() => location.reload());
                } else {
                    Swal.fire('Error', response.respuesta || 'No se pudo eliminar.', 'error');
                }
                },
                error: function() {
                Swal.fire('Error', 'Ocurrió un error al eliminar.', 'error');
                }
            });
            }
        });
    }
</script>