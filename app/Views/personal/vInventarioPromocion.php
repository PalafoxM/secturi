
<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- ===================== tittle ===================== -->
            <div class="row mt-4">
                <div class="col-lg-9">
                    <div class="row">
                        <!-- Card 1: Total Unidades -->
                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 h-100 card metric">
                                <div class="card-body">
                                    <div class="d-flex align-items-center w-100">
                                        <div class="col-auto">
                                            <div class="icon-info">
                                                <i class="mdi mdi-file-document-outline bg-soft-primary rounded-circle p-2 font-20"></i>
                                            </div>
                                        </div>
                                        <div class="col pl-0">
                                            <h5 class="text-muted text-uppercase font-10 font-weight-bold mt-0 mb-2">No. de Contrato</h5>
                                            <h3 class="m-0 font-weight-bold"><?= number_format($total_stock_promo ?? 0) ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Subtotal -->
                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 h-100 card metric">
                                <div class="card-body">
                                    <div class="d-flex align-items-center w-100">
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
                            <div class="card shadow-sm border-0 h-100 card metric">
                                <div class="card-body">
                                    <div class="d-flex align-items-center w-100">
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
                    <div class="card shadow-sm border-0 h-100 card-metric">
                        <div class="card-body d-flex flex-column justify-content-center text-center">
                            <div class="text-center">
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
            
            <div class="row">
                <div class="card-body">
                    <div class="tab-content pt-2">
                        <!-- ===== Promocion ==== -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body">
                                        <h4 class="header-title mt-0 mb-4 text-dark border-bottom pb-2">Inventario Promoción</h4>
                                        <div class="table-responsive dash-social">
                                            <table class="table table-hover sm mb-0 tabla-inventario w-100">    
                                                <thead class="thead-light">
                                                    <tr class="text-center">
                                                        <th class="py-3">
                                                            <i class="mdi mdi-tag-outline text-primary d-block mb-1"></i>
                                                            <small class="text-muted d-block">Producto</small></th>
                                                        <th class="py-3" style="width:50%;">
                                                            <i class="mdi mdi-image text-purple d-block mb-1"></i>
                                                            <small class="text-muted d-block">Imagen</small></th>
                                                        <th class="py-3">
                                                            <i class="mdi mdi-counter text-info d-block mb-1"></i>
                                                            <small class="text-muted d-block">Cantidad</small></th>
                                                        <th class="py-3">
                                                            <i class="mdi mdi-palette text-pink d-block mb-1"></i>
                                                            <small class="text-muted d-block">Colores</small></th>
                                              
                                                        <th class="py-3">
                                                            <i class="mdi mdi-currency-usd text-primary d-block mb-1"></i>
                                                            <small class="text-muted d-block">Precio</small></th>

                                                        <th class="py-3">
                                                            <i class="mdi mdi-calendar-remove text-dark d-block mb-0"></i>
                                                            <small class="text-muted d-block">Fecha contrato</small></th>

                                                        <th class="py-3">
                                                            <i class="mdi mdi-calendar-check text-success d-block mb-1"></i>
                                                            <small class="text-muted d-block">Formulario</small></th>
                                                        
                                                        <th class="py-3" style="width:15%;">
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
                                                                <td class="font-weight-medium text-dark py-3 text-center">
                                                                    <?= $item->dsc_producto ?>
                                                                </td>

                                                                <!-- Imagen -->
                                                                <td class="text-center">
                                                                    <?php if (!empty($item->imagenes)): ?>
                                                                        <?php foreach ($item->imagenes as $img): ?>
                                                                            <img src="<?= base_url($img->imagen) ?>"
                                                                            class="rounded btn-ver-imagen mr-1"
                                                                            style="height:40px; cursor:pointer;"
                                                                            data-src="<?= base_url($img->imagen) ?>">
                                                                    <?php endforeach; ?>
                                                                    <?php else: ?>
                                                                            <img src="<?= base_url('assets/images/no-image.png') ?>"
                                                                            style="height:40px; opacity:.4;">
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
                                                                        <?php foreach($item->colores as $index => $color): ?>
                                                                            
                                                                            <!-- Icono visible -->
                                                                            <i class="mdi mdi-circle font-18 color-picker-trigger"
                                                                                style="color: <?= $color->hexadecimal ?>; cursor:pointer;"
                                                                                data-index="<?= $item->id_inventario_promo ?>_<?= $index ?>"
                                                                                data-toggle="tooltip"
                                                                                data-placement="top"
                                                                                title="Cantidad: <?= $color->cantidad ?? 0 ?>">
                                                                            </i>

                                                                            <!-- Input color oculto -->
                                                                            <input type="color"
                                                                                class="color-picker-input d-none"
                                                                                id="color_<?= $item->id_inventario_promo ?>_<?= $index ?>"
                                                                                value="<?= $color->hexadecimal ?>"
                                                                                data-id="<?= $item->id_inventario_promo ?>"
                                                                                data-index="<?= $index ?>">

                                                                        <?php endforeach; ?>
                                                                    <?php else: ?>
                                                                        <span class="text-muted font-12">Sin colores</span>
                                                                    <?php endif; ?> 
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

                                                                <!-- Fec. Entrada -->
                                                                <td class="text-center">
                                                                    <span class="badge badge-soft-success font-13">
                                                                        <?= $item->fecha_entrada ?? date('d/m/Y', strtotime($item->created_at ?? 'now')) ?>
                                                                    </span>
                                                                </td>

                                                                <!-- Link a formulario -->
                                                                <td class="text-center font-weight-bold text-dark">
                                                                    <div class="d-flex justify-content-center">
                                                                        <a href="<?= base_url('vFormularioPromo?id=' . $item->id_inventario_promo) ?>"
                                                                            class="btn btn-outline-secondary btn-sm"
                                                                            title="Formulario de requisición">
                                                                            📁 Form.
                                                                        </a> <!-- lleva a formulario-->
                                                                    </div>
                                                                </td>

                                                                <!-- Acciones -->
                                                                <td class="text-center text-nowrap">
                                                                    <div class="d-flex justify-content-center">
                                                                        
                                                                        <a href="<?= base_url('vComplementosPromo?id=' . $item->id_inventario_promo) ?>"
                                                                            class="btn btn-sm btn-outline-warning btn-sm"
                                                                            title="Complementos del contrato">
                                                                            🌎 Compl. <!-- lleva a INE, oficio, evidencias -->
                                                                        </a>    

                                                                        <button class="btn btn-sm btn-outline-danger btn-eliminar btn-sm"
                                                                            data-id="<?= $item->id_inventario_promo ?>"
                                                                            data-tabla="cat_inventario_promo"
                                                                            data-nombre="<?= $item->dsc_producto ?>"
                                                                            data-stock="<?= $stock ?>"
                                                                            title="Eliminar">
                                                                            ⛔ Elim.
                                                                        </button>
                                                                    </div>    
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
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
                                                                <label class="font-weight-bold text-dark text-uppercase font-12" id="label_cantidad">Subtotal</label>
                                                                <input type="number" class="form-control" id="subtotal" name="subtotal" min="0" step="0.01" required>
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
$(window).on('load', function () {

    if ($.fn.DataTable.isDataTable('.tabla-inventario')) {
        $('.tabla-inventario').DataTable().destroy();
    }

    $('.tabla-inventario').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        },
        responsive: true,
        autoWidth: false,
        order: [[0, "asc"]],
        initComplete: function () {
            this.api().columns.adjust().draw(false);
        }
    });

    $(document).ready(function () {

        /* ==========================================================
        UTILIDADES
        ========================================================== */

        function setProductoReadonly(estado) {
            $('#nombre_producto').prop('readonly', estado);
        }

        function resetModal() {
            $('#formMovimientoInventario')[0].reset();
            $('#colores_container').empty();

            setProductoReadonly(false);
            $('#label_cantidad').text('Stock / Cantidad');
            $('#cantidad').prop('readonly', false);

            $('#preview_imagen').hide();
            $('#placeholder_imagen').show();
            $('.custom-file-label').html('Seleccionar Archivo');
        }

        function parseColores(colores) {
            if (!colores) return [];
            if (Array.isArray(colores)) return colores;

            try {
                return JSON.parse(colores);
            } catch (e) {
                return [];
            }
        }

        /* ==========================================================
            DATATABLE
        ========================================================== */

        if ($.fn.DataTable.isDataTable('.tabla-inventario')) {
            $('.tabla-inventario').DataTable().destroy();
        }

    


        /* ==========================================================
            INPUT FILE + PREVIEW
        ========================================================== */

        $(document).on('change', '.custom-file-input', function () {
            const fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label")
                .addClass("selected")
                .html(fileName);

            const input = this;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#preview_imagen').attr('src', e.target.result).show();
                    $('#placeholder_imagen').hide();
                };
                reader.readAsDataURL(input.files[0]);
            }
        });

        /* ==========================================================
            MODAL – ABRIR
        ========================================================== */

        $(document).on('click', '.btn-movimiento', function () {
            const d = $(this).data();

            resetModal();

            $('#id_producto').val(d.id || '');
            $('#tipo_movimiento').val(d.tipo || '');
            $('#nombre_producto').val(d.nombre || '');
            $('#tabla_hidden').val(d.tabla || 'cat_inventario_promo');

            const colores = parseColores(d.colores);

            if (d.tipo === 'editar') {
                $('#label_cantidad').text('Stock Actual');

                if (colores.length > 0) {
                    colores.forEach(c => {
                        addColorRow(c.hexadecimal, c.cantidad);
                    });
                }
            }

            if (d.tipo === 'nuevo') {
                $('#label_cantidad').text('Stock Inicial');
                $('#tabla_hidden').val('cat_inventario_promo');
                addColorRow();
            }

            if (d.tipo === 'salida') {
                $('#label_cantidad').text('Cantidad a retirar');
                setProductoReadonly(true);
                $('#cantidad').val('');
            }

            ('click', '.btn-ver-imagen', function () {
                const src = $(this).data('src');
                $('#imagenGrande').attr('src', src);
                $('#modalImagen').modal('show');
            });

            const titulos = {
                nuevo: 'Nuevo Producto',
                editar: 'Editar Producto',
                salida: 'Baja de Stock'
            };

            $('#modalTitulo').text(titulos[d.tipo] || 'Movimiento');
            $('#modalMovimientoInventario').modal('show');
        });

        /* ==========================================================
            FORM – SUBMIT
        ========================================================== */

        $('#formMovimientoInventario').on('submit', function (e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }

            const tipo = $('#tipo_movimiento').val();
            const url = (tipo === 'salida')
                ? '<?= base_url("index.php/Inicio/actualizarInventario") ?>'
                : '<?= base_url("index.php/Inicio/guardarProducto") ?>';

            const formData = new FormData(this);
            const $btn = $(this).find('button[type="submit"]');
            const originalText = $btn.text();

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $btn.prop('disabled', true)
                        .html('<span class="spinner-border spinner-border-sm"></span> Guardando...');
                },
                success: function (res) {
                    if (!res.error) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: res.respuesta,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.respuesta, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'No se pudo procesar la petición.', 'error');
                },
                complete: function () {
                    $btn.prop('disabled', false).text(originalText);
                }
            });
        });

        /* ==========================================================
            ELIMINAR
        ========================================================== */

        $(document).on('click', '.btn-eliminar', function () {
            const d = $(this).data();

            Swal.fire({
                icon: 'warning',
                title: '¿Eliminar producto?',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(
                        '<?= base_url("index.php/Inicio/eliminarProducto") ?>',
                        { id: d.id, tabla: d.tabla },
                        function (res) {
                            if (!res.error) {
                                Swal.fire('Eliminado', res.respuesta, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error', res.respuesta, 'error');
                            }
                        },
                        'json'
                    );
                }
            });
        });

        /* ==========================================================
            COLORES DINÁMICOS
        ========================================================== */

        let colorRowIndex = 0;

        function addColorRow(color = '#000000', cantidad = '') {
            colorRowIndex++;
            const rowId = `color_row_${colorRowIndex}`;

            $('#colores_container').append(`
                <div class="d-flex align-items-center w-100 mb-2" id="${rowId}">
                    <div class="col-6">
                        <input type="color" class="form-control form-control-sm"
                               name="colores[]" value="${color}">
                    </div>
                    <div class="col-4">
                        <input type="number" class="form-control form-control-sm input-cantidad"
                               name="cantidades[]" value="${cantidad}" min="0">
                    </div>
                    <div class="col-2">
                        <button type="button"
                                class="btn btn-sm btn-outline-danger btn-remove-color"
                                data-row="${rowId}">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>
            `);
        }

        $('#btn_add_color').on('click', function () {
            addColorRow();
        });

        $(document).on('click', '.btn-remove-color', function () {
            $('#' + $(this).data('row')).remove();
        });

    });
});
</script>