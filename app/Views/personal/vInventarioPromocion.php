
<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- ===================== tittle ===================== -->
            <div class="row mt-4">
                <div class="col-lg-9 col-md-8 mb-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="icon-info">
                                        <i data-feather="smile" class="align-self-center icon-lg icon-dual-warning"></i>
                                    </div>
                                </div> 
                                <div class="col">
                                    <div class="ml-2 font-13">
                                        <p class="mb-1 text-muted">Material Promocional:</p>
                                        <?= count($inventario ?? []) ?>
                                    </div>
                                    <div class="progress mt-2" style="height:4px;">
                                        <div class="progress-bar bg-pink" role="progressbar" style="width: 22%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                                
                <div class="col-lg-3 col-md-4 mb-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body d-flex flex-column justify-content-center text-center">
                            <div class="icon-info mb-3">
                                <i data-feather="plus-square" class="icon-lg icon-dual-primary"></i>
                            </div>
                            <div class="col-sm-8 col-8 align-self-center text-right">
                                <div class="ml-2">
                                    <button class="btn btn-primary px-4 btn-movimiento shadow-sm"
                                        data-id=""
                                        data-tabla=""
                                        data-nombre=""
                                        data-stock=""
                                        data-tipo="nuevo">
                                        <i class="mdi mdi-plus-box mr-2"></i> Nuevo producto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>     
            
            <div class="row">
                <div class="card-body">
                    
                
                    <div class="tab-content pt-4">

                        <!-- ===== Promocion ==== -->
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body">
                                        <h4 class="header-title mt-0 mb-4 text-dark border-bottom pb-2">Inventario Promoción</h4>
                                        <div class="table-responsive">
                                            <table class="table table-hover sm mb-0 tabla-inventario w-100">    
                                                <thead class="thead-light">
                                                    <tr class="text-center">
                                                        <th class="py-3" style="width:50%;">
                                                            <i class="mdi mdi-tag-outline text-primary d-block mb-1"></i>
                                                            <small class="text-muted d-block">Producto</small></th>
                                                        <th class="py-3">
                                                            <i class="mdi mdi-counter text-info d-block mb-1"></i>
                                                            <small class="text-muted d-block">Cantidad</small></th>
                                                        <th class="py-3">
                                                            <i class="mdi mdi-palette-outline text-warning d-block mb-1"></i>
                                                            <small class="text-muted d-block">Especificaciones</small></th>
                                                        <th class="py-3">
                                                            <i class="mdi mdi-warehouse text-success d-block mb-1"></i>
                                                            <small class="text-muted d-block">Stock</small></th>
                                                        <th class="py-3">
                                                            <i class="mdi mdi-database-outline text-dark d-block mb-0"></i>
                                                            <small class="text-muted d-block">Tot. Exist.</small></th>
                                                        <th class="py-3">
                                                            <i class="mdi mdi-currency-usd text-primary d-block mb-1"></i>
                                                            <small class="text-muted d-block">Precio</small></th>
                                                        <th  class="py-3" style="width:15%;">
                                                            <i class="mdi mdi-cog-outline text-secondary d-block mb-0"></i>
                                                            <small class="text-muted d-block">Acciones</small>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaPromo">
                                                    <?php if(isset($cat_inventario_promo) && !empty($cat_inventario_promo)): ?>
                                                        <?php foreach($cat_inventario_promo as $item): ?>
                                                            <?php 
                                                                $stock = (int) ($item->stock ?? 0); 
                                                                $badgeClass = ($stock < 5) ? 'badge-soft-danger' : 'badge-soft-success'; 
                                                            ?>
                                                            <tr class="align-middle">
                                                                <!-- Producto -->
                                                                <td class="font-weight-medium text-dark py-3">
                                                                    <?= $item->dsc_producto ?>
                                                                </td>

                                                                <!-- Cantidad (badge) -->
                                                                <?php
                                                                    $stock = (int) $item->stock;
                                                                    $badgeClass = ($stock < 5) ? 'badge-soft-danger' : 'badge-soft-success';
                                                                ?>
                                                                <td class="text-center">
                                                                    <span class="badge badge-soft-info font-13 p-2 px-3">
                                                                        <?= $item->cantidad ?>
                                                                    </span>
                                                                </td>

                                                                <!-- Especificaciones -->
                                                                <td class="text-center text-muted">
                                                                    <?= $item->color ?>
                                                                </td>

                                                                <!-- Stock -->
                                                                <td class="text-center">
                                                                    <span class="badge <?= $badgeClass ?> font-13">
                                                                        <?= $stock ?>
                                                                    </span>
                                                                </td>

                                                                <!-- Total Existencia -->
                                                                <td class="text-center font-weight-bold text-dark">
                                                                    <?= $item->total_existencia ?>
                                                                </td>

                                                                <!-- Precio -->
                                                                <td class="text-center">
                                                                    <div class="font-weight-bold text-dark">
                                                                        $<?= number_format((float)($item->precio_unitario ?? 0), 2) ?>
                                                                    </div>

                                                                    <div class="text-muted font-12">
                                                                        Subtotal:
                                                                        $<?= number_format((float)($item->subtotal ?? 0), 2) ?>
                                                                    </div>

                                                                    <div class="text-muted font-11 mt-1">
                                                                        Total: <span class="font-weight-semibold text-primary">$<?= number_format((float)($item->total ?? 0), 2) ?></span>
                                                                    </div>
                                                                </td>  

                                                                <!-- Acciones -->
                                                                <td class="text-center text-nowrap">
                                                                    <div class="d-flex justify-content-center">
                                                                        <button class="btn btn-sm btn-outline-primary btn-movimiento border-0 p-2"
                                                                            data-id="<?= $item->id_inventario_promo ?>"
                                                                            data-tabla="cat_inventario_promo"
                                                                            data-nombre="<?= $item->dsc_producto ?>"
                                                                            data-stock="<?= $stock ?>"
                                                                            data-tipo="editar"
                                                                            title="Editar">
                                                                            ✏️ Editar
                                                                        </button>

                                                                        <button class="btn btn-sm btn-outline-warning btn-movimiento border-0 mx-1 p-2"
                                                                            data-id="<?= $item->id_inventario_promo ?>"
                                                                            data-tabla="cat_inventario_promo"
                                                                            data-nombre="<?= $item->dsc_producto ?>"
                                                                            data-stock="<?= $stock ?>"
                                                                            data-tipo="salida"
                                                                            title="Baja">
                                                                            ➖ Baja
                                                                        </button>

                                                                        <button class="btn btn-sm btn-outline-danger btn-eliminar border-0 p-2"
                                                                            data-id="<?= $item->id_inventario_promo ?>"
                                                                            data-tabla="cat_inventario_promo"
                                                                            data-nombre="<?= $item->dsc_producto ?>"
                                                                            data-stock="<?= $stock ?>"
                                                                            title="Eliminar">
                                                                            🗑 Eliminar
                                                                        </button>
                                                                    </div>    
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="7" class="text-center text-muted py-4">
                                                                No hay productos registrados
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div> <!-- end table responsive -->
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div><!--end col-lg-8-->

                            <!--Carrusel-->
                            <div class="col-lg-4">                            
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body">
                                        <h4 class="mt-0 header-title mb-4 text-dark border-bottom pb-2">Resumen Inventario</h4>
                                        <div id="carousel_stats" class="carousel slide" data-ride="carousel">
                                            <div class="carousel-inner">
                                                <!-- Item 1: Stock Total -->
                                                <div class="carousel-item active">
                                                    <div class="media p-3 bg-light rounded align-items-center">
                                                        <div class="avatar-md bg-soft-primary rounded-circle mr-3 d-flex align-items-center justify-content-center">
                                                            <i class="mdi mdi-cube-outline font-24"></i>
                                                        </div>
                                                        <div class="media-body">                                                           
                                                            <p class="text-muted mb-1 font-weight-medium">Stock Total</p>
                                                            <h3 class="m-0 font-weight-bold text-dark"><?= number_format($total_valor ?? 0) ?></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Item 2: Total -->
                                                <div class="carousel-item active">
                                                    <div class="media p-3 bg-light rounded align-items-center">
                                                        <div class="avatar-md bg-soft-success rounded-circle mr-3 d-flex align-items-center justify-content-center">
                                                            <i class="mdi mdi-currency-mxn font-24"></i>
                                                        </div>    
                                                        <div class="media-body">                                                           
                                                            <h4 class="text-muted mb-1 font-weight-medium">Total Inventario</h4>
                                                            <h3 class="m-0 font-weight-bold text-dark">$<?= number_format($total_valor ?? 0, 2) ?></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Item 3: Movimientos -->
                                                <div class="carousel-item">
                                                    <div class="media p-3 bg-light rounded align-items-center">
                                                        <div class="avatar-md bg-soft-warning rounded-circle mr-3 d-flex align-items-center justify-content-center">
                                                            <i class="mdi mdi-history font-24"></i>
                                                            <img src="../assets/images/users/user-3.jpg" class="mr-2 thumb-lg rounded-circle" alt="...">
                                                            <div class="media-body">
                                                                <p class="text-muted mb-1 font-weight-medium text-uppercase font-11">Movimientos</p>
                                                                <h3 class="m-0 font-weight-bold text-dark"><?= $total_movimientos ?? 0 ?></h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a class="carousel-control-prev" href="#carousel_2" role="button" data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Anterior</span>
                                                </a>
                                                <a class="carousel-control-next" href="#carousel_2" role="button" data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Siguiente</span>
                                                </a>
                                                </div>
                                                <div class="m-0">
                                                    <div id="apex_radialbar3" class="apex-charts"></div>
                                                </div> 
                                                <div class="bg-light p-3 d-flex justify-content-between">
                                                    <div>
                                                        <h2 class="mb-1 font-weight-semibold">222</h2>
                                                        <p class="text-muted mb-0">Entradas recientes</p>
                                                    </div>
                                                </div>
                                                <div class="media mt-5 mb-3 align-items-center">
                                                    <div class="avatar-md bg-soft-primary rounded shadow-none mr-3 d-flex align-items-center justify-content-center h-100">
                                                    <i class="mdi mdi-file-document-outline font-26 text-primary"></i>
                                                </div>                                     
                                                <div class="media-body text-truncate">
                                                    <p class="mb-0 text-muted font-weight-medium">Acceso rápido</p>
                                                    <a href="<?= base_url('index.php/Inicio/inventarioDetalle') ?>" class="font-18 font-weight-bold text-primary">
                                                        Descripciones <i class="mdi mdi-arrow-right font-16 ml-1"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        <hr class="hr-dashed mt-4">
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div><!--end col-lg-4-->
                        </div><!--end row principal-->

                        
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
                                                <label class="font-weight-bold  text-dark text-uppercase font-12">Categoría</label>
                                                <select class="form-control" id="tabla_select">
                                                    <option value="cat_inventario_promo">Artículos de Promoción</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="font-weight-bold text-dark text-uppercase font-12">Producto</label>
                                                <input type="text" class="form-control" id="nombre_producto" name="nombre" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="font-weight-bold text-dark text-uppercase font-12">Color / Especificación</label>
                                                <input type="text" class="form-control" id="color_producto" name="color">
                                            </div>

                                            <div class="form-group">
                                                <label class="font-weight-bold text-dark text-uppercase font-12" id="label_cantidad">Stock / Cantidad</label>
                                                <input type="number" class="form-control" id="cantidad" name="stock" min="0" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="font-weight-bold text-dark text-uppercase font-12">Imagen (URL)</label>
                                                <input type="text" class="form-control" id="imagen_producto" name="imagen">
                                            </div>
                                        </div>

                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary btn-sm px-3" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary btn-sm px-4">Guardar</button>
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
        $('#color_producto').val(d.color || '');
        $('#imagen_producto').val(d.imagen || '');

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

    // 3. ENVIAR FORMULARIO 
    $('#formMovimientoInventario').on('submit', function(e) {
        e.preventDefault(); 
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

    // 4. Eliminar producto
    $(document).on('click', '.btn-eliminar', function() {
        var d = $(this).data();
        Swal.fire({
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
            icon: 'warning',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url("index.php/Inicio/eliminarProducto") ?>', {id: d.id, tabla: d.tabla}, function(res) {
                    if(!res.error) {
                        Swal.fire("¡Eliminado!", res.respuesta, "success").then(() => location.reload());
                    } else {
                        Swal.fire("Error", res.respuesta, "error");
                    }
                }, 'json');
            }
        });
    });
}); // <--- Solo un cierre de ready
</script>