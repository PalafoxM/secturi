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
                                        <span class="font-14"><i class="mdi mdi-paperclip mr-1"></i> Total Oficina: <strong><?= $total_stock_art_ofi ?? 0 ?></strong></span>
                                        <span class="font-14"><i class="mdi mdi-pen mr-1"></i> Total PapelerÍ­a: <strong><?= $total_stock_art_papel ?? 0 ?></strong></span>
                                        <span class="font-14"><i class="mdi mdi-file-outline mr-1"></i> Total Papel: <strong><?= $total_stock_papel ?? 0 ?></strong></span>
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
                                        <i class="mdi mdi-paperclip mr-1"></i> ArtÍ­culos de Oficina
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-semibold" data-toggle="tab" href="#papeleria" role="tab">
                                        <i class="mdi mdi-pen mr-1"></i> Artí­culos PapelerÍ­a
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
                                                            <button class="btn btn-xs btn-outline-primary btn-movimiento"
                                                                    data-id="<?= $item->id_inventario_art_ofi ?>"
                                                                    data-tabla="cat_inventario_art_ofi"
                                                                    data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                    data-stock="<?= $item->stock ?>"
                                                                    data-tipo="editar"
                                                                    title="Editar Producto">
                                                                <i class="mdi mdi-pencil"></i> Editar
                                                            </button>
                                                            <button class="btn btn-xs btn-outline-warning btn-movimiento ml-2"
                                                                    data-id="<?= $item->id_inventario_art_ofi ?>"
                                                                    data-tabla="cat_inventario_art_ofi"
                                                                    data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                    data-stock="<?= $item->stock ?>"
                                                                    data-tipo="salida"
                                                                    title="Registrar Salida">
                                                                <i class="mdi mdi-minus"></i> Baja
                                                            </button>
                                                            <button class="btn btn-xs btn-outline-danger btn-eliminar ml-2"
                                                                data-id="<?= $item->id_inventario_art_ofi ?>"
                                                                data-tabla="cat_inventario_art_ofi"
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
                                                            <button class="btn btn-xs btn-outline-primary btn-movimiento"
                                                                    data-id="<?= $item->id_inventario_art_papel ?>"
                                                                    data-tabla="cat_inventario_art_papel"
                                                                    data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                    data-stock="<?= $item->stock ?>"
                                                                    data-tipo="editar"
                                                                    title="Editar Producto">
                                                                <i class="mdi mdi-pencil"></i> Editar
                                                            </button>
                                                            <button class="btn btn-xs btn-outline-warning btn-movimiento ml-2"
                                                                    data-id="<?= $item->id_inventario_art_papel ?>"
                                                                    data-tabla="cat_inventario_art_papel"
                                                                    data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                    data-stock="<?= $item->stock ?>"
                                                                    data-tipo="salida"
                                                                    title="Registrar Salida">
                                                                <i class="mdi mdi-minus"></i> Baja
                                                            </button>
                                                            <button class="btn btn-xs btn-outline-danger btn-eliminar ml-2"
                                                                data-id="<?= $item->id_inventario_art_papel ?>"
                                                                data-tabla="cat_inventario_art_papel"
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
                                                            <button class="btn btn-xs btn-outline-primary btn-movimiento"
                                                                    data-id="<?= $item->id_inventario_papel ?>"
                                                                    data-tabla="cat_inventario_papel"
                                                                    data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                    data-stock="<?= $item->stock ?>"
                                                                    data-tipo="editar"
                                                                    title="Editar Producto">
                                                                <i class="mdi mdi-pencil"></i> Editar
                                                            </button>
                                                            <button class="btn btn-xs btn-outline-warning btn-movimiento ml-2"
                                                                    data-id="<?= $item->id_inventario_papel ?>"
                                                                    data-tabla="cat_inventario_papel"
                                                                    data-nombre="<?= $item->nombre ?? $item->descripcion ?? '' ?>"
                                                                    data-stock="<?= $item->stock ?>"
                                                                    data-tipo="salida"
                                                                    title="Registrar Salida">
                                                                <i class="mdi mdi-minus"></i> Baja
                                                            </button>
                                                            <button class="btn btn-xs btn-outline-danger btn-eliminar ml-2"
                                                                data-id="<?= $item->id_inventario_papel ?>"
                                                                data-tabla="cat_inventario_papel"
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
                                    <label class="font-weight-bold">CategorÍ­a</label>
                                    <select class="form-control" id="tabla_select">
                                        <option value="cat_inventario_art_ofi">ArtÍ­culos de Oficina</option>
                                        <option value="cat_inventario_art_papel">ArtÍ­culos PapelerÍ­a</option>
                                        <option value="cat_inventario_papel">Papel</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Producto</label>
                                    <input type="text" class="form-control" id="nombre_producto" name="nombre" required>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold" id="label_cantidad">Stock</label>
                                    <input type="number" class="form-control" id="cantidad" name="stock" min="0" required placeholder="0">
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

<link href="<?= base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url(); ?>plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<!-- App css -->
<link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url()?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />

<!-- jQuery  -->
<script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?= base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>

<!-- Required datatable js -->
<script src="<?= base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>plugins/datatables/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>plugins/datatables/responsive.bootstrap4.min.js"></script>

<!-- App js -->
<script src="<?= base_url()?>assets/js/app.js"></script>
<script src="<?= base_url()?>assets/js/waves.js"></script>
<script src="<?= base_url()?>assets/js/feather.min.js"></script>
<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>

<script>
    $(document).ready(function() {
    
        // Persistencia de Pestañas
        var activeTab = localStorage.getItem('inventoryActiveTab');
        if (activeTab) {
            setTimeout(function() {
                $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
            }, 300);
        }

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            localStorage.setItem('inventoryActiveTab', $(e.target).attr("href"));
        });

        // Initialize DataTables
        $('.tabla-inventario').DataTable({
            language: {
                "decimal": "",
                "emptyTable": "No hay informaciÍ³n",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ productos",
                "infoEmpty": "Mostrando 0 a 0 de 0 productos",
                "infoFiltered": "(Filtrado de _MAX_ total productos)",
                "lengthMenu": "Mostrar _MENU_ productos",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:", // <--- Texto del buscador
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Íšltimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            responsive: true,
            order: [[ 0, "asc" ]] // Ordenar alfabÍ©ticamente por nombre
        });

        // Handle "Alta" (New), "Editar", and "Baja" button clicks
        $(document).on('click', '.btn-movimiento', function() {
            var id = $(this).data('id');
            var tabla = $(this).data('tabla');
            var nombre = $(this).data('nombre');
            var tipo = $(this).data('tipo');
            // 'stock' data attribute might miss if we didn't add it to 'baja' button, but it's fine
            var stock = $(this).data('stock'); 
        
            $('#id_producto').val(id);
            $('#tipo_movimiento').val(tipo);
            $('#nombre_producto').val(nombre);
            $('#cantidad').val(stock); // Default to current stock

            var titulo = '';
            var icono = '';
        
            // Reset valid elements
            $('#nombre_producto').prop('readonly', false);
            $('#div_tabla_select').hide();
            $('#tabla_hidden').val(tabla);

            if(tipo == 'nuevo'){
                titulo = 'Nuevo Producto';
                icono = 'mdi-plus-box text-primary';
                $('#id_producto').val('');
                $('#nombre_producto').val('');
                $('#cantidad').val('0');
                $('#div_tabla_select').show();
                $('#label_cantidad').text('Stock Inicial');
            } else if(tipo == 'editar'){
                 titulo = 'Editar Producto';
                icono = 'mdi-pencil text-primary';
                $('#label_cantidad').text('Stock Actual');
            } else if(tipo == 'salida'){
                 titulo = 'Baja de Stock';
                icono = 'mdi-minus-circle text-warning';
                $('#nombre_producto').prop('readonly', true);
                $('#label_cantidad').text('Cantidad a retirar');
                $('#cantidad').val(''); // Clear for input
            }
        
            $('#modalTitulo').html('<i class="mdi '+icono+' mr-2"></i> ' + titulo);
        
            $('#modalMovimientoInventario').modal('show');
        });

        $(document).on('click', '.btn-eliminar', function() {
            var id = $(this).data('id');
            var tabla = $(this).data('tabla');
            var nombre = $(this).data('nombre');

            // Usamos SweetAlert para confirmar antes de borrar
            Swal.fire({
                title: 'Â¿EstÍ¡s seguro?',
                text: "Se eliminarÍ¡ el producto: " + nombre,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'SÍ­, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario dice que SÍ, hacemos la peticiÍ³n AJAX
                    $.ajax({
                        url: '<?= base_url() ?>index.php/Inicio/eliminarProducto',
                        type: 'POST',
                        data: { id: id, tabla: tabla },
                        dataType: 'json',
                        success: function(response) {
                            if (!response.error) {
                                Swal.fire("Eliminado", response.respuesta, "success");
                                // Recargamos la pÍ¡gina para ver los cambios
                                setTimeout(function(){ location.reload(); }, 1500);
                            } else {
                                Swal.fire("Error", response.respuesta, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Error", "Error de conexiÍ³n con el servidor.", "error");
                        }
                    });
                }
            });
        });
        // Handle form submit
        $('#formMovimientoInventario').submit(function(e) {
            e.preventDefault();
        
            var tipo = $('#tipo_movimiento').val();
            var urlInfo = '';
        
           // If 'nuevo', get table from select
            if(tipo == 'nuevo'){
                $('#tabla_hidden').val($('#tabla_select').val());
            }

            var formData = $(this).serialize();

            if(tipo == 'salida'){
                 urlInfo = '<?= base_url() ?>index.php/Inicio/actualizarInventario'; 
            } else {
                 urlInfo = '<?= base_url() ?>index.php/Inicio/guardarProducto';
            }

            $.ajax({
                url: urlInfo,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (!response.error) {
                        Swal.fire("Correcto", response.respuesta, "success");
                        location.reload(); // Reload to show new data/totals
                    } else {
                        Swal.fire("Error", response.respuesta, "error");
                    }
                },
                error: function() {
                    Swal.fire("Error", "Error de conexiÍ³n con el servidor.", "error");
                }
            });
        }); 
    });
</script>
