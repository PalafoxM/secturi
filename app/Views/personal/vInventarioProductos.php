
<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- ===================== HEADER ===================== -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title mt-0">Inventario</h4>

                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="media my-2">
                                        <img src="<?= base_url('assets/images/widgets/logo2.png') ?>"
                                             class="thumb-md-2 rounded-circle" alt="stock">
                                        <div class="media-body ml-3">
                                            <h4 class="mt-0 mb-1 font-weight-semibold text-dark font-24">
                                                1,248
                                            </h4>
                                            <p class="text-muted text-uppercase mb-0 font-12">
                                                Stock total activo
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ===================== GRÁFICA ===================== -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div style="height:300px;">
                                        <canvas id="graficaInventario"></canvas>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== TABS DE INVENTARIO ===================== -->
            <!-- ===================== TABS DE INVENTARIO ===================== -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active font-weight-semibold" data-toggle="tab" href="#oficina" role="tab">
                                        <i class="mdi mdi-paperclip mr-1"></i> Artículos de Oficina
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-semibold" data-toggle="tab" href="#papeleria" role="tab">
                                        <i class="mdi mdi-pen mr-1"></i> Artículos Papelería
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-semibold" data-toggle="tab" href="#papel" role="tab">
                                        <i class="mdi mdi-file-outline mr-1"></i> Papel
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content pt-4">

                                <!-- ===== OFICINA ===== -->
                                <div class="tab-pane fade show active" id="oficina" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-striped dt-responsive nowrap tabla-inventario" style="width:100%">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th class="text-center">Stock Actual</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaOficina">
                                                <?php if(isset($cat_inventario_art_ofi) && !empty($cat_inventario_art_ofi)): ?>
                                                    <?php foreach($cat_inventario_art_ofi as $item): ?>
                                                    <?php 
                                                        $stock = $item->stock;
                                                        $badgeClass = ($stock < 5) ? 'badge-soft-danger' : 'badge-soft-success';
                                                    ?>
                                                    <tr>
                                                        <td class="align-middle font-weight-medium">
                                                            <?= $item->nombre ?? $item->descripcion ?? 'Producto ' . $item->id_inventario_art_ofi ?>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <span class="badge <?= $badgeClass ?> font-12 p-2" id="stock-art_ofi-<?= $item->id_inventario_art_ofi ?>">
                                                                <?= $item->stock ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <button class="btn btn-xs btn-outline-success btn-movimiento"
                                                                    data-id="<?= $item->id_inventario_art_ofi ?>"
                                                                    data-tabla="cat_inventario_art_ofi"
                                                                    data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                    data-tipo="entrada"
                                                                    title="Registrar Entrada">
                                                                <i class="mdi mdi-plus"></i> Alta
                                                            </button>
                                                            <button class="btn btn-xs btn-outline-warning btn-movimiento ml-2"
                                                                    data-id="<?= $item->id_inventario_art_ofi ?>"
                                                                    data-tabla="cat_inventario_art_ofi"
                                                                    data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                    data-tipo="salida"
                                                                    title="Registrar Salida">
                                                                <i class="mdi mdi-minus"></i> Baja
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- ===== PAPELERÍA ===== -->
                                <div class="tab-pane fade" id="papeleria" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-striped dt-responsive nowrap tabla-inventario" style="width:100%">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th class="text-center">Stock Actual</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaPapeleria">
                                                <?php if(isset($cat_inventario_art_papel) && !empty($cat_inventario_art_papel)): ?>
                                                    <?php foreach($cat_inventario_art_papel as $item): ?>
                                                    <?php 
                                                        $stock = $item->stock;
                                                        $badgeClass = ($stock < 5) ? 'badge-soft-danger' : 'badge-soft-success';
                                                    ?>
                                                    <tr>
                                                        <td class="align-middle font-weight-medium">
                                                            <?= $item->nombre ?? $item->descripcion ?? 'Producto ' . $item->id_inventario_art_papel ?>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <span class="badge <?= $badgeClass ?> font-12 p-2" id="stock-art_papel-<?= $item->id_inventario_art_papel ?>">
                                                                <?= $item->stock ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <button class="btn btn-xs btn-outline-success btn-movimiento"
                                                                    data-id="<?= $item->id_inventario_art_papel ?>"
                                                                    data-tabla="cat_inventario_art_papel"
                                                                    data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                    data-tipo="entrada"
                                                                    title="Registrar Entrada">
                                                                <i class="mdi mdi-plus"></i> Alta
                                                            </button>
                                                            <button class="btn btn-xs btn-outline-warning btn-movimiento ml-2"
                                                                    data-id="<?= $item->id_inventario_art_papel ?>"
                                                                    data-tabla="cat_inventario_art_papel"
                                                                    data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                    data-tipo="salida"
                                                                    title="Registrar Salida">
                                                                <i class="mdi mdi-minus"></i> Baja
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- ===== PAPEL ===== -->
                                <div class="tab-pane fade" id="papel" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-striped dt-responsive nowrap tabla-inventario" style="width:100%">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th class="text-center">Stock Actual</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaPapel">
                                                <?php if(isset($cat_inventario_papel) && !empty($cat_inventario_papel)): ?>
                                                    <?php foreach($cat_inventario_papel as $item): ?>
                                                    <?php 
                                                        $stock = $item->stock;
                                                        $badgeClass = ($stock < 5) ? 'badge-soft-danger' : 'badge-soft-success';
                                                    ?>
                                                    <tr>
                                                        <td class="align-middle font-weight-medium">
                                                            <?= $item->nombre ?? $item->descripcion ?? 'Producto ' . $item->id_inventario_papel ?>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <span class="badge <?= $badgeClass ?> font-12 p-2" id="stock-papel-<?= $item->id_inventario_papel ?>">
                                                                <?= $item->stock ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <button class="btn btn-xs btn-outline-success btn-movimiento"
                                                                    data-id="<?= $item->id_inventario_papel ?>"
                                                                    data-tabla="cat_inventario_papel"
                                                                    data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                    data-tipo="entrada"
                                                                    title="Registrar Entrada">
                                                                <i class="mdi mdi-plus"></i> Alta
                                                            </button>
                                                            <button class="btn btn-xs btn-outline-warning btn-movimiento ml-2"
                                                                    data-id="<?= $item->id_inventario_papel ?>"
                                                                    data-tabla="cat_inventario_papel"
                                                                    data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                    data-tipo="salida"
                                                                    title="Registrar Salida">
                                                                <i class="mdi mdi-minus"></i> Baja
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div> <!-- end tab-content -->
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col-12 -->
            </div>

            <!-- ===================== MODAL RIGHTBAR ===================== -->
            <div class="modal fade modal-rightbar" id="modalMovimientoInventario" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm modal-dialog-right" role="document">
                    <div class="modal-content">

                        <div class="modal-header bg-light">
                            <h5 class="modal-title font-16" id="modalTitulo">
                                <i class="mdi mdi-database-plus mr-2"></i> Movimiento
                            </h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>

                        <form id="formMovimientoInventario">
                            <div class="modal-body">
                                <input type="hidden" id="id_producto" name="id_producto">
                                <input type="hidden" id="tabla" name="tabla">
                                <input type="hidden" id="tipo_movimiento" name="tipo_movimiento">

                                <div class="form-group">
                                    <label class="font-weight-bold">Producto</label>
                                    <input type="text" class="form-control" id="nombre_producto" readonly>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Cantidad</label>
                                    <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required placeholder="0">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                        data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary btn-sm">Guardar Movimiento</button>
                            </div>
                        </form>

                    </div>
                </div>
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
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>

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
$(document).ready(function() {
    
    // Initialize DataTables


    // Handle "Alta" and "Baja" button clicks
    $(document).on('click', '.btn-movimiento', function() {
        var id = $(this).data('id');
        var tabla = $(this).data('tabla');
        var nombre = $(this).data('nombre');
        var tipo = $(this).data('tipo');
        
        $('#id_producto').val(id);
        $('#tabla').val(tabla);
        $('#nombre_producto').val(nombre);
        $('#tipo_movimiento').val(tipo);
        $('#cantidad').val('');

        var titulo = '';
        var icono = '';
        if(tipo == 'entrada'){
            titulo = 'Alta de Stock';
            icono = 'mdi-plus-circle text-success';
        } else {
            titulo = 'Baja de Stock';
            icono = 'mdi-minus-circle text-warning';
        }
        
        $('#modalTitulo').html('<i class="mdi '+icono+' mr-2"></i> ' + titulo);
        
        $('#modalMovimientoInventario').modal('show');
    });

    // Handle form submit
    $('#formMovimientoInventario').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: '<?= base_url() ?>index.php/Inicio/actualizarInventario',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (!response.error) {
                    alert(response.respuesta);
                    
                    // Update the UI
                    var id = $('#id_producto').val();
                    var tabla = $('#tabla').val();
                    var nuevoStock = response.nuevo_stock;
                    
                    // Determine which cell to update based on table
                    var prefix = '';
                    if (tabla == 'cat_inventario_art_ofi') prefix = 'art_ofi';
                    else if (tabla == 'cat_inventario_art_papel') prefix = 'art_papel';
                    else if (tabla == 'cat_inventario_papel') prefix = 'papel';
                    
                    var badge = $('#stock-' + prefix + '-' + id);
                    badge.text(nuevoStock);
                    
                    // Update badge color logic (optional dynamic class change)
                    if (nuevoStock < 5) {
                        badge.removeClass('badge-soft-success').addClass('badge-soft-danger');
                    } else {
                        badge.removeClass('badge-soft-danger').addClass('badge-soft-success');
                    }
                    
                    $('#modalMovimientoInventario').modal('hide');
                } else {
                    alert('Error: ' + response.respuesta);
                }
            },
            error: function() {
                alert('Error de conexión con el servidor.');
            }
        });
    });
});
</script>
