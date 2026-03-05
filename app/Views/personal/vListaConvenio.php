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
                                                    <td>$<?= esc($item->monto) ?></td>
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
                            <input type="text" class="form-control input-importe" name="monto" id="monto" placeholder="$0.00" required>
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
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            destroy: true,
            searching: true,
            responsive: false,
            autoWidth: false
        });

        $('.input-importe').on('blur', function() {
            let val = ($(this).val() || '').replace(/[^0-9.]/g, '');
            if (val) {
                $(this).val(parseFloat(val).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            }
        });

        $('#id_proveedor').select2({
            dropdownParent: $('#modalConvenio'),
            ajax: {
                url: '<?= base_url("index.php/Inicio/buscarProveedor2") ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return { results: data.results };
                },
                cache: true
            },
            placeholder: 'Buscar proveedor por nombre, RFC o No. proveedor',
            minimumInputLength: 1,
            allowClear: true
        });

    });

    function agregarConvenio() {
        $('#formConvenio')[0].reset();
        $('#id_material_promo').val(0);

        // limpia select2
        $('#id_proveedor').empty().trigger('change');

        $('#modalConvenioLabel').text('Nuevo Convenio');
        $('#modalConvenio').modal('show');
    }

    function editarConvenio(item) {
        $('#formConvenio')[0].reset();

        $('#id_material_promo').val(item.id_material_promo);
        $('#convenio').val(item.convenio);
        $('#monto').val(item.monto);

        // Precargar select2 (porque es AJAX)
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

    function guardaConvenio() {

        const idActual = parseInt($('#id_material_promo').val(), 10) || 0;
        const esNuevo = (idActual === 0);

        // Validar proveedor (Select2)
        const idProv = $('#id_proveedor').val();
        if (!idProv) {
            if (window.Swal) Swal.fire('Falta proveedor', 'Selecciona un proveedor para poder guardar.', 'warning');
            else alert('Selecciona un proveedor para poder guardar.');
            return;
        }

        let montoVal = ($('#monto').val() || '').replace(/[^0-9.]/g, '');
        if (!montoVal) {
            if (window.Swal) Swal.fire('Error', 'El monto es requerido', 'error');
            else alert('El monto es requerido');
            return;
        }

        $('#monto').val(montoVal);

        $.ajax({
            url: '<?= base_url("index.php/Agregar/guardaConvenio") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                id_material_promo: idActual,
                monto: montoVal,
                convenio: $('#convenio').val(),
                id_proveedor: idProv
            },
            
            success: function(response) {
                if (!response.error) {

                    const idGuardado = response.id_material_promo || idActual;

                    Swal.fire({
                        title: 'Éxito',
                        text: response.respuesta || 'Registro guardado correctamente.',
                        icon: 'success',
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => {

                        // Si es nuevo, redirigir directo a inventario para alimentar artículos
                        if (esNuevo && idGuardado) {
                            window.location.href = "<?= base_url('index.php/Inicio/InventarioPromocion/') ?>" + idGuardado;
                        } else {
                            // Si fue edición, recargar lista
                            location.reload();
                        }

                    });

                } else {
                    Swal.fire('Error', response.respuesta || 'No se pudo guardar.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Ocurrió un error al guardar.', 'error');
            }
        });
    }

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
                            Swal.fire('Eliminado!', response.respuesta || 'Registro eliminado.', 'success').then(() => {
                                location.reload();
                            });
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
