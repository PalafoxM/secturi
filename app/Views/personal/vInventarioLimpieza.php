
<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

         

            <!-- ===================== TABS DE INVENTARIO ===================== -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="row mb-3">
                                <div class="col-md-9">
                                    <div class="alert alert-secondary d-flex justify-content-around align-items-center mb-0">
                                        <span class="font-14"><i class="mdi mdi-paperclip mr-1"></i> Total Limpieza: <?= $total_stock_lim ?? 0 ?></strong></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-primary btn-block btn-movimiento"
                                            data-id=""
                                            data-tabla=""
                                            data-nombre=""
                                            data-stock=""
                                            data-tipo="nuevo">
                                        <i class="mdi mdi-plus-box mr-1"></i> Nuevo Producto
                                    </button>
                                </div>
                            </div>

                            <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active font-weight-semibold" data-toggle="tab" href="#oficina" role="tab">
                                        <i class="mdi mdi-paperclip mr-1"></i> Artículos de Limpieza
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content pt-4">

                                <!-- ===== Limpieza ==== -->
                                <div class="tab-pane fade show active" id="limpieza" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-striped dt-responsive nowrap tabla-inventario" style="width:100%">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th class="text-center">Stock Actual</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaLimpieza">
                                                <?php if(isset($cat_inventario_limpieza) && !empty($cat_inventario_limpieza)): ?>
                                                    <?php foreach($cat_inventario_limpieza as $item): ?>
                                                    <?php 
                                                        $stock = $item->stock;
                                                        $badgeClass = ($stock < 5) ? 'badge-soft-danger' : 'badge-soft-success';
                                                    ?>
                                                    <tr>
                                                        <td class="align-middle font-weight-medium">
                                                            <?= $item->nombre ?? $item->descripcion ?? 'Producto ' . $item->id_inventario_lim ?>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <span class="badge <?= $badgeClass ?> font-12 p-2" id="stock_lim-<?= $item->id_inventario_lim ?>">
                                                                <?= $item->stock ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <button class="btn btn-xs btn-outline-primary btn-movimiento"
                                                                    data-id="<?= $item->id_inventario_lim ?>"
                                                                    data-tabla="cat_inventario_limpieza"
                                                                    data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                    data-stock="<?= $item->stock ?>"
                                                                    data-tipo="editar"
                                                                    title="Editar Producto">
                                                                <i class="mdi mdi-pencil"></i> Editar
                                                            </button>
                                                            <button class="btn btn-xs btn-outline-warning btn-movimiento ml-2"
                                                                    data-id="<?= $item->id_inventario_lim ?>"
                                                                    data-tabla="cat_inventario_limpieza"
                                                                    data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                    data-tipo="salida"
                                                                    title="Registrar Salida">
                                                                <i class="mdi mdi-minus"></i> Baja
                                                            </button>
                                                            <button class="btn btn-xs btn-outline-danger btn-eliminar ml-2"
                                                                data-id="<?= $item->id_inventario_lim ?>"
                                                                data-tabla="cat_inventario_limpieza"
                                                                data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                title="Eliminar Producto">
                                                                <i class="mdi mdi-trash-can"></i> Eliminar
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
                                <input type="hidden" id="tipo_movimiento" name="tipo_movimiento">
                                <!-- Hidden field for table name when editing or fixed -->
                                <input type="hidden" id="tabla_hidden" name="tabla"> 

                                <div class="form-group" id="div_tabla_select" style="display:none;">
                                    <label class="font-weight-bold">Categoría</label>
                                    <select class="form-control" id="tabla_select">
                                        <option value="cat_inventario_limpieza">Artículos de Limpieza</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Producto</label>
                                    <input type="text" class="form-control" id="nombre_producto" name="nombre" required>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold" id="label_cantidad">Stock</label>
                                    <input type="number" class="form-control" id="cantidad" name="stock" min="0" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
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
    // 1. Inicializar DataTable
    if ($.fn.DataTable.isDataTable('.tabla-inventario')) {
        $('.tabla-inventario').DataTable().destroy();
    }
    $('.tabla-inventario').DataTable({
        language: { url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json" },
        responsive: true,
        order: [[ 0, "asc" ]]
    });

    // 2. Abrir Modal y Configurar Datos
    $(document).on('click', '.btn-movimiento', function() {
        var d = $(this).data();
        
        $('#id_producto').val(d.id);
        $('#tipo_movimiento').val(d.tipo);
        $('#nombre_producto').val(d.nombre);
        $('#tabla_hidden').val(d.tabla);
        
        // Reset de campos
        $('#nombre_producto').prop('readonly', false);
        $('#div_tabla_select').hide();
        $('#cantidad').val(d.stock || '');

        var titulo = 'Movimiento';
        if(d.tipo == 'nuevo'){
            titulo = 'Nuevo Producto';
            $('#div_tabla_select').show();
            $('#label_cantidad').text('Stock Inicial');
        } else if(d.tipo == 'editar'){
            titulo = 'Editar Producto';
            $('#label_cantidad').text('Stock Actual');
        } else if(d.tipo == 'salida'){
            titulo = 'Baja de Stock';
            $('#nombre_producto').prop('readonly', true);
            $('#label_cantidad').text('Cantidad a retirar');
            $('#cantidad').val('');
        }
        
        $('#modalTitulo').text(titulo);
        $('#modalMovimientoInventario').modal('show');
    });

    // 3. ENVIAR FORMULARIO (Aquí estaba el fallo)
    $('#formMovimientoInventario').on('submit', function(e) {
        e.preventDefault(); // ESTO DETIENE EL ENVÍO POR URL
        console.log("Intentando enviar formulario...");

        var tipo = $('#tipo_movimiento').val();
        var urlInfo = (tipo == 'salida') ? 
            '<?= base_url("index.php/Inicio/actualizarInventario") ?>' : 
            '<?= base_url("index.php/Inicio/guardarProducto") ?>';

        if(tipo == 'nuevo') $('#tabla_hidden').val($('#tabla_select').val());

        $.ajax({
            url: urlInfo,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (!res.error) {
                    Swal.fire("¡Éxito!", res.respuesta, "success").then(() => location.reload());
                } else {
                    Swal.fire("Error", res.respuesta, "error");
                }
            },
            error: function() {
                Swal.fire("Error", "No se pudo procesar la petición en el servidor.", "error");
            }
        });
    });

    // 4. Eliminar
    $(document).on('click', '.btn-eliminar', function() {
        var d = $(this).data();
        Swal.fire({
            title: '¿Eliminar ' + d.nombre + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, borrar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url("index.php/Inicio/eliminarProducto") ?>', {id: d.id, tabla: d.tabla}, function(res) {
                    location.reload();
                }, 'json');
            }
        });
    });
}); // <--- Solo un cierre de ready
</script>
