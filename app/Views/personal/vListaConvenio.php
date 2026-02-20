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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Convenios</a></li>
                                <li class="breadcrumb-item active">Lista</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Relación de Convenios</h4>
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
                                    <button type="button" class="btn btn-primary" onclick="agregarConvenio()"><i class="fas fa-plus"></i> Nuevo Convenio</button>
                                </div>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table id="tablaConvenios" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Convenio</th>
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
                                                    <td><?= $item->id_material_promo ?></td>
                                                    <td><?= $item->convenio ?></td>
                                                    <td>$<?= $item->monto ?></td>
                                                    <td><?= $item->dsc_tiket ?></td>
                                                    <td><?= $item->razon_social ?></td>
                                                    <td><?= $item->no_proveedor ?></td>
                                                    <td>
                                                        <a href="<?= base_url('index.php/Inicio/FormularioPromo/' . $item->id_material_promo) ?>" class="btn btn-primary btn-sm"><i class="fas fa-file-alt"></i></a>
                                                        <a title="Formulario de registro"></a>
                                                        <button class="btn btn-warning btn-sm" onclick='editarConvenio(<?= json_encode($item) ?>)'><i class="fas fa-edit"></i></button>
                                                        <a title="Editar"></a>
                                                        <button class="btn btn-danger btn-sm" onclick="eliminarConvenio(<?= $item->id_material_promo ?>)"><i class="fas fa-trash"></i></button>
                                                        <a title="Eliminar"></a>
                                                        <a href="<?= base_url('index.php/Inicio/InventarioPromocion/' . $item->id_material_promo) ?>" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                                        <a title="Ver detalles"></a>
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
                             <input type="text" class="form-control" name="no_proveedor" id="no_proveedor" required>
                            
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

<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />
<!-- App css -->
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
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
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>

<script src="<?= base_url() ?>plugins/tiny-editable/mindmup-editabletable.js"></script>
<script src="<?= base_url() ?>plugins/tiny-editable/numeric-input-example.js"></script>
<script src="<?= base_url() ?>plugins/bootable/bootstable.js"></script> 
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />

<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>


<script>

    $(document).ready(function() {
        $('#tablaConvenios').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
            },
            destroy: true,
        searching: true,
        });

        $('.input-importe').on('blur', function() {
            let val = $(this).val().replace(/[^0-9.]/g, ''); 
            if(val) {
                $(this).val(parseFloat(val).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            }
        });

        // Initialize Select2 for Proveedor search
        $('#id_proveedor').select2({
            dropdownParent: $('#modalConvenio'),
            ajax: {
                url: '<?= base_url("Principal/buscarProveedor2") ?>', // Re-using existing provider search
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            placeholder: 'Buscar proveedor por Razón Social o RFC',
            minimumInputLength: 1
        });

    });

    function agregarConvenio() {
        $('#formConvenio')[0].reset();
        $('#id_material_promo').val(0);
        $('#id_proveedor').val(null).trigger('change');
        $('#modalConvenioLabel').text('Nuevo Convenio');
        $('#modalConvenio').modal('show');
    }

    function editarConvenio(item) {
        $('#formConvenio')[0].reset();
        $('#id_material_promo').val(item.id_material_promo);
        $('#convenio').val(item.convenio);
        $('#monto').val(item.monto);
        $('#no_proveedor').val(item.no_proveedor);


        $('#modalConvenioLabel').text('Editar Convenio');
        $('#modalConvenio').modal('show');
    }

    function guardaConvenio()
    { 
        let montoVal = $('#monto').val().replace(/,/g, '');

        $.ajax({
            url: '<?= base_url("index.php/Agregar/guardaConvenio") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                id_material_promo: $('#id_material_promo').val(),
                monto: montoVal,
                convenio: $('#convenio').val(),
                no_proveedor: $('#no_proveedor').val(),
            },
            success: function(response) {
                
                if (!response.error) {

                    Swal.fire({
                        title: 'Folio generado correctamente',
                        text: '¿Desea continuar al formulario?',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, continuar'
                    }).then((result) => {

                        if (result.isConfirmed) {

                            window.location.href =
                            "<?= base_url('index.php/Inicio/FormularioPromo/') ?>" 
                            + response.id_material_promo;

                        } else {
                            location.reload();
                        }

                    });

                } else {
                    Swal.fire('Error', response.respuesta, 'error');
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
                    data: { id_material_promo: id },
                    success: function(response) {
                        if (!response.error) {
                            Swal.fire('Eliminado!', response.respuesta, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.respuesta, 'error');
                        }
                    }
                });
            }
        })
    }
    function abrirModal(id) {
        document.getElementById("idMaterialPromo").value = id;
        $('#miModal').modal('show');
    }
    document.getElementById("btnConfirmarGuardar").addEventListener("click", function() {

        let id = document.getElementById("idMaterialPromo").value;

        window.location.href = "<?= base_url('index.php/Inicio/FormularioPromo/') ?>" + id;

    });
</script>
