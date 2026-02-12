
<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- ===================== tittle ===================== -->
            <div class="row mt-4">
                <div class="col-lg-9">
                    <div class="row">
                        <!-- Card 1: Total Unidades -->
                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="icon-info">
                                                <i class="mdi mdi-cube-outline bg-soft-primary rounded-circle p-2 font-20"></i>
                                            </div>
                                        </div>
                                        <div class="col pl-0">
                                            <h5 class="text-muted text-uppercase font-10 font-weight-bold mt-0 mb-2">Total Unidades</h5>
                                            <h3 class="m-0 font-weight-bold"><?= number_format($total_stock_promo ?? 0) ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Subtotal -->
                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                         <div class="col-auto">
                                            <div class="icon-info">
                                                <i class="mdi mdi-currency-usd bg-soft-success rounded-circle p-2 font-20"></i>
                                            </div>
                                        </div>
                                        <div class="col pl-0">
                                            <h5 class="text-muted text-uppercase font-10 font-weight-bold mt-0 mb-2">Total Subtotal</h5>
                                             <h3 class="m-0 font-weight-bold">$<?= number_format($total_subtotal_promo ?? 0, 2) ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Total -->
                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="icon-info">
                                                <i class="mdi mdi-cash-multiple bg-soft-warning rounded-circle p-2 font-20"></i>
                                            </div>
                                        </div>
                                        <div class="col pl-0">
                                            <h5 class="text-muted text-uppercase font-10 font-weight-bold mt-0 mb-2">Total Importe</h5>
                                            <h3 class="m-0 font-weight-bold">$<?= number_format($total_dinero_promo ?? 0, 2) ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                                
                <div class="col-lg-3">                            
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body d-flex flex-column justify-content-center text-center">
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
                    <div class="tab-content pt-2">
                        <!-- ===== Promocion ==== -->
                        <div class="row">
                            <div class="col-lg-9">
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
                                                        <th class="py-3 text-center">
                                                            <i class="mdi mdi-image text-purple d-block mb-1"></i>
                                                            <small class="text-muted d-block">Imagen</small></th>
                                                        <th class="py-3">
                                                            <i class="mdi mdi-counter text-info d-block mb-1"></i>
                                                            <small class="text-muted d-block">Cantidad</small></th>
                                                        <th class="py-3">
                                                            <i class="mdi mdi-palette text-pink d-block mb-1"></i>
                                                            <small class="text-muted d-block">Colores</small></th>
                                              
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
                                                            <?php
                                                                static $productoTmp = '';
                                                                    if (!empty($item->dsc_producto)) {
                                                                    $productoTmp = $item->dsc_producto;
                                                                }
                                                            ?>

                                                            <tr class="align-middle">
                                                                <!-- Producto -->
                                                                <td class="font-weight-medium text-dark py-3">
                                                                    <?= $item->dsc_producto ?>
                                                                </td>

                                                                <!-- Imagen -->
                                                                <td class="text-center">
                                                                    <?php if (!empty($item->imagen)): ?>
                                                                        <img src="<?= base_url($item->imagen) ?>" alt="Prod" class="rounded btn-ver-imagen" style="height: 40px; cursor: pointer;" data-src="<?= base_url($item->imagen) ?>">
                                                                    <?php else: ?>
                                                                        <span class="text-muted">-</span>
                                                                    <?php endif; ?>
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

                                                                <!-- Colores -->
                                                                <td class="text-center">
                                                                    <?php if(isset($item->colores) && !empty($item->colores)): ?>
                                                                        <?php foreach($item->colores as $color): ?>
                                                                            <i class="mdi mdi-circle font-18" 
                                                                               style="color: <?= $color->hexadecimal ?>;" 
                                                                               data-toggle="tooltip" 
                                                                               data-placement="top" 
                                                                               title="<?= 'Cantidad: ' ?>: <?= $color->cantidad ?? 0 ?>">
                                                                            </i>
                                                                        <?php endforeach; ?>
                                                                    <?php else: ?>
                                                                        <span class="text-muted font-12">Sin colores</span>
                                                                    <?php endif; ?>
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
                                                                        <button type="button" class="btn btn-outline-secondary btn-sm btn-movimiento" 
                                                                        data-id="<?= $item->id_inventario_promo ?>"
                                                                        data-nombre="<?= $productoTmp ?>"
                                                                        data-cantidad="<?= $item->cantidad ?>"
                                                                        data-stock="<?= $item->stock ?>"
                                                                        data-total_existencia="<?= $item->total_existencia ?>"
                                                                        data-imagen="<?= $item->imagen ?? '' ?>" 
                                                                        
                                                                        data-colores='<?= json_encode($item->colores ?? []) ?>'

                                                                        data-tabla="cat_inventario_promo"
                                                                        data-tipo="editar"
                                                                        title="Editar">
                                                                        <i class="mdi mdi-pencil"></i>
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
                            <div class="col-lg-3">                            
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body">
                                        <h4 class="mt-0 header-title mb-4 text-dark border-bottom pb-2">
                                            Resumen Inventario
                                        </h4>
                                        <div id="carousel_stats" 
                                            class="carousel slide" 
                                            data-ride="carousel"
                                            data-interval="3500"
                                            data-pause="hover">

                                            <ol class="carousel-indicators">
                                                <li data-target="#carousel_stats" data-slide-to="0" class="active"></li>
                                                <li data-target="#carousel_stats" data-slide-to="1"></li>
                                                <li data-target="#carousel_stats" data-slide-to="2"></li>
                                            </ol>
                                            
                                            <div class="carousel-inner">
                                                <!-- Item 1: Stock Total -->
                                                <div class="carousel-item active">
                                                    <div class="media p-3 bg-light rounded align-items-center">
                                                        <div class="avatar-md bg-soft-primary rounded-circle mr-3 d-flex align-items-center justify-content-center">
                                                            <i class="mdi mdi-cube-outline font-24"></i>
                                                        </div>
                                                        <div class="media-body">                                                           
                                                            <p class="text-muted mb-1 font-weight-medium">Stock Total</p>
                                                            <h3 class="m-0 font-weight-bold text-dark"><?= number_format($total_stock_promo ?? 0) ?></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Item 2: Total -->
                                                <div class="carousel-item">
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
                                                        </div>    
                                                        <div class="media-body">
                                                            <p class="text-muted mb-1 font-weight-medium text-uppercase font-11">Movimientos</p>
                                                            <h3 class="m-0 font-weight-bold text-dark"><?= $total_movimientos ?? 0 ?></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a class="carousel-control-prev" href="#carousel_stats" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                            </a>
                                            <a class="carousel-control-next" href="#carousel_stats" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                            </a>
                                            <div class="m-0">
                                                <div id="apex_radialbar3" class="apex-charts"></div>
                                            </div> 
                                            <div class="bg-light p-3 d-flex justify-content-between">
                                                <div>
                                                    <h2 class="mb-1 font-weight-semibold" id="contadorMovimientos">
                                                        <?= $total_movimientos ?? 0 ?>
                                                    </h2>
                                                    <p class="text-muted mb-0">Movimientos recientes</p>
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
                                        </div>    
                                        <hr class="hr-dashed mt-4">
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div><!--end col-lg-4-->
                        </div><!--end row principal-->

                        
                        <!-- ===================== MODAL STANDARD ===================== -->
                        <div class="modal fade" id="modalMovimientoInventario" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                                            
                                            <div class="row">
                                                <!-- LEFT COLUMN: Form Fields -->
                                                <div class="col-md-7">
                                                    <!-- Removed Category Select -->

                                                    <div class="form-group">
                                                        <label class="font-weight-bold text-dark text-uppercase font-12">Producto</label>
                                                        <input type="text" class="form-control" id="nombre_producto" name="nombre" required>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                             <div class="form-group">
                                                                <label class="font-weight-bold text-dark text-uppercase font-12">Cantidad</label>
                                                                <input type="number" class="form-control" id="cantidad_producto" name="cantidad" min="0" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold text-dark text-uppercase font-12" id="label_cantidad">Stock</label>
                                                                <input type="number" class="form-control" id="cantidad" name="stock" min="0" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="font-weight-bold text-dark text-uppercase font-12">Colores y Cantidades</label>
                                                        <div id="colores_container">
                                                            <!-- Dynamic rows will be added here -->
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-outline-info mt-1" id="btn_add_color">
                                                            <i class="mdi mdi-plus"></i> Agregar Color
                                                        </button>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="font-weight-bold text-dark text-uppercase font-12">Total Existencia</label>
                                                        <input type="number" class="form-control" id="total_existencia" name="total_existencia" min="0" required>
                                                    </div>
                                                </div>

                                                <!-- RIGHT COLUMN: Image Upload -->
                                                <div class="col-md-5">
                                                    <div class="form-group text-center">
                                                        <label class="font-weight-bold text-dark text-uppercase font-12 w-100">Imagen</label>
                                                        
                                                        <div class="mt-2 mb-3 border rounded d-flex align-items-center justify-content-center bg-light" style="height: 180px; overflow: hidden;">
                                                            <img id="preview_imagen" src="" alt="Vista previa" class="img-fluid" style="max-height: 100%; display: none;">
                                                            <span id="placeholder_imagen" class="text-muted small">Sin imagen seleccionada</span>
                                                        </div>

                                                        <div class="custom-file text-left">
                                                            <input type="file" class="custom-file-input" id="imagen_producto" name="imagen" accept="image/*">
                                                            <label class="custom-file-label" for="imagen_producto" data-browse="Elegir">Seleccionar Archivo</label>
                                                        </div>
                                                        <small class="form-text text-muted text-left mt-1">Formatos: JPG, PNG, GIF</small>
                                                    </div>
                                                </div>
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

<!-- Modal para ver imagen grande -->
<div class="modal fade" id="modalImagen" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-3">
                 <button type="button" class="close mb-2" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <img id="imagenGrande" src="" class="img-fluid rounded" alt="Imagen Producto">
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

    function setProductoReadonly(estado) {
    $('#nombre_producto').prop('readonly', estado);
    }

    // 1. Inicializar DataTable
    if ($.fn.DataTable.isDataTable('.tabla-inventario')) {
        $('.tabla-inventario').DataTable().destroy();
    }
    $('.tabla-inventario').DataTable({
        language: { url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json" },
        responsive: true,
        order: [[ 0, "asc" ]]
    });

    // Custom file input label change
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        
        // Image preview logic
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_imagen').attr('src', e.target.result).show();
                $('#placeholder_imagen').hide();
            }
            reader.readAsDataURL(input.files[0]);
        } else {
             $('#preview_imagen').attr('src', '').hide();
             $('#placeholder_imagen').show();
        }
    });

    // RESET TOTAL DEL MODAL
    setProductoReadonly(false);
        $('#label_cantidad').text('Stock / Cantidad');
        $('#cantidad').prop('readonly', false);

    // 2. Abrir Modal y Configurar Datos
    $(document).on('click', '.btn-movimiento', function() {
        var d = $(this).data();
        
        $('#id_producto').val(d.id);
        $('#tipo_movimiento').val(d.tipo);
        $('#nombre_producto').val(d.nombre);
        $('#tabla_hidden').val(d.tabla);
        
        // Reset de campos
        $('#nombre_producto').prop('readonly', false);
        // Removed: $('#div_tabla_select').hide(); 
        $('#cantidad').val(d.stock || '');
        
        // Asignar los nuevos campos
        $('#cantidad_producto').val(d.cantidad || '');
        $('#total_existencia').val(d.total_existencia || '');

        // Limpiar contenedor de colores
        $('#colores_container').empty();
        
        // Populate colors if editing and has dynamic colors
        var dynamicColores = d.colores; // Should be object/array from json_encode

        if (d.tipo == 'editar') {
            var colorsAdded = 0;

            if (dynamicColores && dynamicColores.length > 0) {
                // New dynamic logic
                dynamicColores.forEach(function(c) {
                    // c.hexadecimal and c.cantidad
                    addColorRow(c.hexadecimal, c.cantidad);
                    colorsAdded++;
                });
            } else {
                // Fallback / Legacy Logic (if data-colores is empty but has old attributes)
                // List of supported colors in DB
                var dbColors = ['Negro', 'Blanco', 'Azul', 'Verde', 'Amarillo', 'Rojo', 'Gris', 'Naranja'];
                
                dbColors.forEach(function(color) {
                    // Construct attribute name e.g., d.negro_cantidad
                    var key = color.toLowerCase() + '_cantidad';
                    var qty = d[key]; 
                    
                    // Also check the boolean flag e.g., d.negro
                    var flag = d[color.toLowerCase()];

                    if ((qty && qty > 0) || (flag && flag == 1)) {
                        addColorRow(color, qty || 0); // This will use the legacy name mapping
                        colorsAdded++;
                    }
                });
            }

            // If no colors found (legacy or empty), maybe add one empty row or none
            if (colorsAdded === 0) {
                 // Optional: addColorRow(); 
            }
        } else {
            // New product: add one empty row
            addColorRow();
        }
        
        // Limpiar el input file y preview por defecto
        $('#imagen_producto').val('');
        $('.custom-file-label').html('Seleccionar Archivo');
        
        if (d.imagen && d.imagen.trim() !== '') {
            $('#preview_imagen').attr('src', '<?= base_url() ?>' + d.imagen).show();
            $('#placeholder_imagen').hide();
        } else {
            $('#preview_imagen').attr('src', '').hide();
            $('#placeholder_imagen').show();
        }


        var titulo = 'Movimiento';
        if(d.tipo == 'nuevo'){
            titulo = 'Nuevo Producto';
            // Removed: $('#div_tabla_select').show();
            $('#tabla_hidden').val('cat_inventario_promo'); // Force default table
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
        
        // Validación básica HTML5
        if (!this.checkValidity()) {
            this.reportValidity();
            return;
        }

        console.log("Intentando enviar formulario...");

        var tipo = $('#tipo_movimiento').val();
        var urlInfo = (tipo == 'salida') ? 
            '<?= base_url("index.php/Inicio/actualizarInventario") ?>' : 
            '<?= base_url("index.php/Inicio/guardarProducto") ?>';

        var formData = new FormData(this);
        var $btnSubmit = $(this).find('button[type="submit"]');
        var btnText = $btnSubmit.text();

        $.ajax({
            url: urlInfo,
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false, // Importante para envío de archivos
            contentType: false, // Importante para envío de archivos
            beforeSend: function() {
                $btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...');
            },
            success: function(res) {
                if (!res.error) {
                    Swal.fire({
                        title: "¡Éxito!",
                        text: res.respuesta,
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        $('#modalMovimientoInventario').modal('hide');
                        // Recargar tabla sin recargar página
                        // Reload the current page content in background and replace table body
                        $.get(location.href, function(data) {
                            var newBody = $(data).find('#tablaPromo').html();
                            var $table = $('.tabla-inventario').DataTable();
                            $table.destroy();
                            $('#tablaPromo').html(newBody);
                            
                            // Reinitialize DataTable
                            $('.tabla-inventario').DataTable({
                                language: { url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json" },
                                responsive: true,
                                order: [[ 0, "asc" ]]
                            });
                             // Update metrics carousel also if possible (optional but good)
                             var newCarousel = $(data).find('#carousel_stats').html();
                             $('#carousel_stats').html(newCarousel);
                        });
                    });
                } else {
                    Swal.fire("Error", res.respuesta, "error");
                }
            },
            error: function() {
                Swal.fire("Error", "No se pudo procesar la petición en el servidor.", "error");
            },
            complete: function() {
                $btnSubmit.prop('disabled', false).text(btnText);
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
            cancelButtonText: 'Cancelar',
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

    // --- Dynamic Color Logic ---
    function addColorRow(color = '', cantidad = '') {
        var rowId = Date.now();
        
        // Mapa de colores legacy a Hex
        var colorMap = {
            'Negro': '#000000',
            'Blanco': '#ffffff',
            'Azul': '#0000ff',
            'Verde': '#008000',
            'Amarillo': '#ffff00',
            'Rojo': '#ff0000',
            'Gris': '#808080',
            'Naranja': '#ffa500'
        };

        // Si el color viene como nombre (legacy), convertir a hex. Si no, usar tal cual (o default negro)
        var colorValue = colorMap[color] || color || '#000000';

        var html = `
            <div class="row align-items-center mb-2 color-row" id="row_${rowId}">
                <div class="col-6">
                    <div class="input-group">
                        <input type="color" class="form-control form-control-sm form-control-color" 
                               name="colores[]" 
                               value="${colorValue}" 
                               title="Elige un color">
                    </div>
                </div>
                <div class="col-4">
                    <input type="number" class="form-control form-control-sm input-cantidad" name="cantidades[]" placeholder="Cant." value="${cantidad}" min="0" required>
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-color" data-row="row_${rowId}">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </div>
            </div>
        `;
        $('#colores_container').append(html);
    }

    $('#btn_add_color').click(function() {
        addColorRow();
    });

    $(document).on('click', '.btn-remove-color', function() {
        var rowId = $(this).data('row');
        $('#' + rowId).remove();
        updateTotalCantidad();
    });

    $(document).on('input', '.input-cantidad', function() {
        updateTotalCantidad();
    });

    function updateTotalCantidad() {
        var total = 0;
        $('.input-cantidad').each(function() {
            var val = parseInt($(this).val()) || 0;
            total += val;
        });
        // Optional: Update total stock field if needed, but currently logic might be separate
        // $('#cantidad').val(total); 
    }

    // 5. Ver imagen grande
    $(document).on('click', '.btn-ver-imagen', function() {
        var src = $(this).data('src');
        $('#imagenGrande').attr('src', src);
        $('#modalImagen').modal('show');
    });
}); // <--- Solo un cierre de ready
</script>