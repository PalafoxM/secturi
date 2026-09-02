
<?php
    $rawDecimal = static function ($value, $default = '0') {
        if ($value === null || $value === '') {
            return $default;
        }
        return (string) $value;
    };

    $formatTwoDecimals = static function ($value) {
        return number_format((float) ($value ?? 0), 2, '.', ',');
    };
?>

<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">

            <!-- ===================== tittle ===================== -->
            <div class="row mt-3">
                <div class="col-lg-12">
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
                                            <h5 class="text-muted text-uppercase font-10 font-weight-bold mt-0 mb-2">No. de Articulos</h5>
                                            <h3 class="m-0 font-weight-bold"><?= number_format($items ?? 0) ?></h3>
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
                                             <h3 class="m-0 font-weight-bold">$<?= esc($formatTwoDecimals($total_subtotal_promo ?? 0)) ?></h3>
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
                                            <h3 class="m-0 font-weight-bold">$<?= esc($formatTwoDecimals($total_dinero_promo ?? 0)) ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>     

            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-2">
                            <div class="border-bottom pb-2 mb-4">

                                <!-- Fila 1: Título -->
                                <div class="d-flex align-items-center mb-2 header-top">
                                    <h4 class="header-title mt-0 mb-0 text-dark">
                                    Inventario Promoción
                                    <strong id="nombre_material" class="ml-1">
                                        <?= $materiales ? ($materiales->convenio . " - " . ($materiales->razon_social ?? '')) : 'Convenio no encontrado' ?>
                                    </strong>
                                    </h4>
                                </div>

                                <!-- Fila 2: Toolbar -->
                                <div class="d-flex align-items-center justify-content-end flex-wrap toolbar-acciones">

                                    <a class="btn btn-secondary btn-action shadow-sm"
                                    href="<?= base_url('index.php/Inicio/ListaConvenio') ?>"
                                    title="Volver a Lista de Convenios">
                                    <i class="mdi mdi-home-outline"></i>
                                    </a>

                                    <button class="btn btn-primary btn-action shadow-sm btn-movimiento"
                                            data-id=""
                                            data-tabla="cat_inventario_promo"
                                            data-nombre=""
                                            data-stock="0"
                                            data-tipo="nuevo">
                                    <i class="mdi mdi-plus-box mr-2"></i> Nuevo producto
                                    </button>

                                    <a class="btn btn-primary btn-action shadow-sm"
                                    href="<?= base_url('index.php/Inicio/FormularioPromoPorConvenio/' . intval($id_convenio_promo ?? $id_convenio ?? 0)) ?>">
                                    <i class="mdi mdi-file-plus-outline mr-2"></i> Nuevo recibo
                                    </a>

                                    <a class="btn btn-primary btn-action shadow-sm"
                                    href="<?= base_url('index.php/Inicio/InventarioRecibosPromo/' . intval($id_convenio_promo ?? $id_convenio ?? 0)) ?>">
                                    <i class="mdi mdi-eye-outline mr-2"></i> Consultar recibos
                                    </a>

                                    <button class="btn btn-success btn-action shadow-sm"
                                            id="btnExportExcel"
                                            data-convenio="<?= intval($id_convenio_promo ?? $id_convenio ?? 0) ?>">
                                    <i class="mdi mdi-file-excel-outline mr-2"></i> Exportar Excel
                                    </button>

                                </div>
                            </div>
                            <div class="table-responsive dash-social">
                                <table id="tablaConvenios" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="thead-light">
                                        <tr class="text-center">

                                            <th class="py-3" style="width:40px;">
                                                <input type="checkbox" id="check_all">
                                                <small class="text-muted d-block">Seleccionar</small>
                                            </th>

                                            <th class="py-3">
                                                <i class="mdi mdi-tag-outline text-primary d-block mb-1"></i>
                                                <small class="text-muted d-block">Producto</small>
                                            </th>

                                            <th class="py-3">
                                                <i class="mdi mdi-image text-purple d-block mb-1"></i>
                                                <small class="text-muted d-block">Imagen</small>
                                            </th>

                                            <th class="py-3">
                                                <i class="mdi mdi-counter text-info d-block mb-1"></i>
                                                <small class="text-muted d-block">Cantidad solicitada</small>
                                            </th>

                                            <th class="py-3">
                                                <i class="mdi mdi-counter text-info d-block mb-1"></i>
                                                <small class="text-muted d-block">Stock</small>
                                            </th>

                                            <th class="py-3">
                                                <i class="mdi mdi-palette text-pink d-block mb-1"></i>
                                                <small class="text-muted d-block">Colores</small>
                                            </th>

                                            <th class="py-3">
                                                <i class="mdi mdi-format-list-bulleted text-info d-block mb-1"></i>
                                                <small class="text-muted d-block">Variantes</small>
                                            </th>

                                            <th class="py-3">
                                                <i class="mdi mdi-currency-usd text-primary d-block mb-1"></i>
                                                <small class="text-muted d-block">Precio</small>
                                            </th>

                                            <!--<th class="py-3">
                                                <i class="mdi mdi-calendar-remove text-dark d-block mb-0"></i>
                                                <small class="text-muted d-block">Fecha de ingreso</small>
                                            </th>-->

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

                                                <tr class="align-middle">

                                                    <!-- ✅ Checkbox -->
                                                    <td class="text-center align-middle">
                                                        <input type="checkbox"
                                                        class="chk-producto"
                                                        value="<?= (int)$item->id_inventario_promo ?>"
                                                        data-id="<?= (int)$item->id_inventario_promo ?>"
                                                        data-nombre="<?= esc($item->dsc_producto) ?>">
                                                    </td>

                                                    <!-- Producto -->
                                                    <td class="font-weight-medium text-dark py-3 text-center">
                                                        <?= esc($item->dsc_producto ?? '') ?>
                                                    </td>

                                                    <!-- Imagen -->
                                                    <td class="text-center">
                                                        <?php if (!empty($item->imagen)): ?>
                                                        <img src="<?= base_url($item->imagen) ?>"
                                                            class="rounded btn-ver-imagen"
                                                            style="height:40px; cursor:pointer;"
                                                            data-src="<?= base_url($item->imagen) ?>">
                                                        <?php else: ?>
                                                        <img src="<?= base_url('assets/images/no-image.png') ?>"
                                                            style="height:40px; opacity:.4;">
                                                        <?php endif; ?>
                                                    </td>

                                                    <!-- Cantidad solicitada -->
                                                    <td class="text-center">
                                                        <span class="badge badge-soft-info font-13 p-2 px-3">
                                                        <?= intval($item->cantidad_solicitada ?? $item->cantidad ?? 0) ?>
                                                        </span>
                                                    </td>

                                                    <!-- Stock real -->
                                                    <td class="text-center">
                                                        <span class="badge badge-soft-success font-13 p-2 px-3">
                                                        <?= (int)($item->stock_disponible ?? $item->total_existencia ?? $item->stock ?? 0) ?>
                                                        </span>
                                                    </td>

                                                    <!-- Colores -->
                                                    <td class="text-center">
                                                        <?php if(!empty($item->color)): ?>
                                                        <?php 
                                                            $colores = is_array($item->color) ? $item->color : json_decode((string)($item->color ?? '[]'), true);
                                                            $colores = is_array($colores) ? $colores : [];
                                                            if(is_array($colores)):
                                                            foreach($colores as $color):
                                                                $hex = is_array($color) ? ($color['hexadecimal'] ?? '') : $color;
                                                                if(!empty($hex)):
                                                        ?>
                                                            <i class="mdi mdi-circle font-18"
                                                            style="color: <?= esc($hex) ?>;"
                                                            data-toggle="tooltip"
                                                            title="<?= esc($hex) ?>"></i>
                                                        <?php 
                                                                endif;
                                                            endforeach;
                                                            endif;
                                                        ?>
                                                        <?php else: ?>
                                                        <span class="text-muted font-12">Sin colores</span>
                                                        <?php endif; ?>
                                                    </td>

                                                    <!-- Variantes -->
                                                    <td class="text-center">
                                                        <?php if(!empty($item->variantes)): ?>
                                                        <?php 
                                                            $vars = is_array($item->variantes) ? $item->variantes : json_decode((string)($item->variantes ?? '[]'), true);
                                                            $vars = is_array($vars) ? $vars : [];
                                                            if(is_array($vars)):
                                                            foreach($vars as $var):
                                                                $texto = is_array($var)
                                                                ? (($var['atributo'] ?? '') . ': ' . ($var['valor'] ?? ''))
                                                                : $var;
                                                        ?>
                                                            <span class="badge badge-soft-primary mr-1">
                                                            <?= esc(trim($texto)) ?>
                                                            </span>
                                                        <?php 
                                                            endforeach;
                                                            endif;
                                                        ?>
                                                        <?php else: ?>
                                                        <span class="text-muted font-12">Sin variantes</span>
                                                        <?php endif; ?>
                                                    </td>

                                                    <!-- Precio -->
                                                    <td class="text-center">
                                                        <?php
                                                            $col = $item->color ?? [];
                                                            $precios = [];

                                                            if (is_array($col)) {
                                                                foreach ($col as $c) {
                                                                    $precioRaw = trim((string)($c['precio'] ?? ''));
                                                                    $p = floatval($precioRaw);
                                                                    if ($p > 0) {
                                                                        $precios[] = [
                                                                            'valor' => $p,
                                                                            'texto' => $precioRaw
                                                                        ];
                                                                    }
                                                                }
                                                            }

                                                            $precioLabel = '';
                                                            if (!empty($precios)) {
                                                                usort($precios, function ($a, $b) {
                                                                    return $a['valor'] <=> $b['valor'];
                                                                });
                                                                $min = $precios[0];
                                                                $max = $precios[count($precios) - 1];
                                                                $precioLabel = ($min['valor'] == $max['valor'])
                                                                    ? ('$' . $rawDecimal($min['texto']))
                                                                    : ('$' . $rawDecimal($min['texto']) . ' - $' . $rawDecimal($max['texto']));
                                                            } else {
                                                                $precioLabel = '$' . $rawDecimal($item->precio_unitario ?? 0);
                                                            }
                                                        ?>
                                                        <div class="font-weight-bold text-dark">
                                                            <span class="text-muted font-11 d-block">Precio unitario</span>
                                                            <?= esc($precioLabel) ?>
                                                        </div>
                                                        <div class="text-muted font-12">
                                                            Subtotal: $<?= esc($rawDecimal($item->subtotal ?? 0)) ?>
                                                        </div>
                                                        <div class="text-muted font-11 mt-1">
                                                            Total redondeado:
                                                            <span class="font-weight-semibold text-primary">
                                                                $<?= esc($formatTwoDecimals($item->total ?? 0)) ?>
                                                            </span>
                                                        </div>
                                                    </td>

                                                    <!-- Acciones -->
                                                    <td class="text-center text-nowrap">

                                                        <button class="btn btn-sm btn-outline-primary btn-movimiento"
                                                                data-id="<?= (int)$item->id_inventario_promo ?>"
                                                                data-tabla="cat_inventario_promo"
                                                                data-tipo="editar"
                                                                data-nombre="<?= esc($item->dsc_producto) ?>"
                                                                data-colores='<?= esc(json_encode($item->color ?? []), 'attr') ?>'
                                                                data-variantes='<?= esc(json_encode($item->variantes ?? []), 'attr') ?>'
                                                                title="Editar producto / stock">
                                                            ✏️ Editar
                                                        </button>

                                                        <button class="btn btn-sm btn-outline-danger btn-eliminar"
                                                                data-id="<?= (int)$item->id_inventario_promo ?>"
                                                                data-tabla="cat_inventario_promo"
                                                                data-nombre="<?= esc($item->dsc_producto) ?>"
                                                                title="Eliminar">
                                                            ⛔ Elim.
                                                        </button>
                                                    </td>
                                                </tr>

                                            <?php endforeach; ?>
                                            <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-4">
                                                    No hay productos registrados
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div> <!-- end table respons   ive -->
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

                        <form id="formMovimientoInventario"
                            method="post"
                            action="<?= base_url('index.php/Inicio/guardarProducto') ?>"
                            enctype="multipart/form-data">

                            <div class="modal-body">

                                <!-- Hidden (NO OMITIR) -->
                                <input type="hidden" name="id_convenio" value="<?= $id_convenio ?>">
                                <input type="hidden" id="id_producto" name="id_producto">
                                <input type="hidden" id="tipo_movimiento" name="tipo_movimiento">
                                <input type="hidden" name="color">
                                <input type="hidden" name="variantes">
                                <input type="hidden" name="stock">
                                <input type="hidden" id="tabla_hidden" name="tabla" value="cat_inventario_promo">

                                <div class="row">

                                    <!-- LEFT COLUMN -->
                                    <div class="col-md-7">

                                        <!-- Producto -->
                                        <div class="form-group">
                                            <label class="font-weight-bold text-dark text-uppercase font-12">
                                                Producto
                                            </label>
                                            <input type="text"
                                            class="form-control"
                                            id="dsc_producto"
                                            name="dsc_producto"
                                            placeholder="Ej. Termos, playeras, plumas...">
                                        </div>

                                        <!-- Cantidad solicitada + Subtotal -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-dark text-uppercase font-12">
                                                        Cantidad solicitada
                                                    </label>
                                                    <input type="number"
                                                            class="form-control"
                                                            id="cantidad"
                                                            name="cantidad"
                                                            min="0"
                                                            readonly>
                                                    <small class="text-muted d-block mt-1">
                                                        Se calcula automáticamente según las presentaciones por color.
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-dark text-uppercase font-12">
                                                        Subtotal
                                                    </label>
                                                    <input type="number"
                                                            class="form-control"
                                                            id="subtotal"
                                                            name="subtotal"
                                                            min="0"
                                                            step="0.01"
                                                            readonly>
                                                    <small class="text-muted d-block mt-1">
                                                        (unidades * precio unitario) por renglón de color.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- COLORES / PRESENTACIONES -->
                                        <div class="form-group">
                                            <label class="font-weight-bold text-dark text-uppercase font-12">
                                                Colores (unidades, material y precio por color)
                                            </label>

                                            <div class="alert alert-info py-2 mb-2">
                                                Instrucciones: agrega el color del producto, las unidades y
                                                el precio unitario. Las variantes como talla, material, tamaño, etc, se
                                                agregan abajo.
                                            </div>

                                            <div id="colores_container"></div>

                                            <button type="button"
                                                    class="btn btn-sm btn-outline-info mt-2"
                                                    id="btn_add_color">
                                                <i class="mdi mdi-plus"></i> Agregar Color
                                            </button>

                                            <small class="text-muted d-block mt-2">
                                                El stock total se calcula con unidades por renglón.
                                            </small>
                                        </div>

                                        <!-- OTRAS VARIANTES -->
                                        <div class="form-group">
                                            <label class="font-weight-bold text-dark text-uppercase font-12">
                                                Otras Variantes (Ej: Talla, Modelo)
                                            </label>

                                            <div id="variantes_container"></div>

                                            <button type="button"
                                                    class="btn btn-sm btn-outline-secondary mt-2"
                                                    id="btn_add_variante">
                                                <i class="mdi mdi-plus"></i> Agregar Variante
                                            </button>
                                        </div>
                                    </div>

                                    <!-- RIGHT COLUMN (IMAGEN) -->
                                    <div class="col-md-5">
                                        <div class="form-group text-center">

                                            <label class="font-weight-bold text-dark text-uppercase font-12 w-100">
                                                Imagen
                                            </label>

                                            <div class="mt-2 mb-3 border rounded d-flex align-items-center justify-content-center bg-light"
                                                style="height: 180px; overflow: hidden;">
                                                <img id="preview_imagen"
                                                    src=""
                                                    class="img-fluid"
                                                    style="max-height: 100%; display: none;">
                                                <span id="placeholder_imagen"
                                                    class="text-muted small">
                                                Sin imagen seleccionada
                                                </span>
                                            </div>

                                            <div class="custom-file text-left">
                                                <input type="file"
                                                    class="custom-file-input"
                                                    id="imagen_producto"
                                                    name="imagen"
                                                    accept="image/*">
                                                <label class="custom-file-label"
                                                    for="imagen_producto"
                                                    data-browse="Elegir">
                                                Seleccionar archivo
                                                </label>
                                            </div>

                                            <small class="form-text text-muted text-left mt-1">
                                                Formatos: JPG, PNG, GIF
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer bg-light">
                                <button type="button"
                                        class="btn btn-secondary btn-sm px-4"
                                        data-dismiss="modal">
                                Cerrar
                                </button>

                                <button type="submit"
                                        class="btn btn-primary btn-sm px-4">
                                Guardar
                                </button>
                            </div>

                        </form>
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

<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />
<!-- App css -->
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />

<style>
  /* El nombre del contrato no debe romper el header */
  #nombre_material{
    max-width: 620px;
    display: inline-block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    vertical-align: bottom;
  }

  /* Toolbar: separación real (Bootstrap 4 no tiene gap) */
  .toolbar-acciones > *{
    margin-left: .5rem;
    margin-bottom: .5rem;
  }
  .toolbar-acciones > *:first-child{
    margin-left: 0;
  }

  /* Botones homogéneos */
  .btn-action{
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: .375rem 1rem;
    white-space: nowrap;
  }

  /* En pantallas chicas, alinear toolbar a la izquierda */
  @media (max-width: 768px){
    .toolbar-acciones{
      justify-content: flex-start !important;
    }
    #nombre_material{
      max-width: 100%;
    }
  }



  /* Tarjeta por renglón */
  #colores_container .color-item{
    padding: 10px;
    border: 1px solid rgba(0,0,0,.06);
    border-radius: 10px;
    background: #fff;
  }

  /* Uniformar alto de controles */
  #colores_container .color-item .form-control{
    height: 38px;
    line-height: 38px;
    padding-top: 0;
    padding-bottom: 0;
  }

  /* El input color es especial: quita padding y fija ancho/alto */
  #colores_container .color-item input[type="color"].form-control{
    padding: 0;
    height: 38px;
    width: 100%;
  }

  /* Alinear el botón de quitar al mismo alto */
  #colores_container .color-item .btn.remove-item{
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  /* Evita que la label empuje raro */
  #colores_container .color-item label{
    display: block;
    margin-bottom: 4px;
  }
</style>

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
    $(document).ready(function () {

        /* ==========================================================
            DATATABLE (opcional)
        ========================================================== */
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('.tabla-inventario')) {
            $('.tabla-inventario').DataTable().clear().destroy();
        }
        if ($.fn.DataTable) {
            $('.tabla-inventario').DataTable({
            destroy: true,
            stateSave: false,
            order: [],
            ordering: false,
            pageLength: 10,
            language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
            });
        }

        /* ==========================================================
            UTILIDADES
        ========================================================== */
        function recalcularDesdeColores() {
            let stockTotal = 0;
            let subtotal = 0;

            $('#colores_container .color-item').each(function () {
                const qty = parseInt($(this).find('.color_cantidad').val(), 10) || 0;
                const prc = decimalToNumber($(this).find('.color_precio').val());

                stockTotal += Math.max(0, qty);
                subtotal += Math.max(0, qty) * Math.max(0, prc);
            });

            $('#cantidad').val(String(stockTotal));
            $('#subtotal').val(subtotal.toFixed(2));
            $('input[name="stock"]').val(String(stockTotal));

            return { stockTotal, subtotal };
        }

        function normalizeDecimal(value, maxDecimals = 10) {
            const text = (value ?? '').toString().trim().replace(',', '.');
            if (text === '') return '';

            const numeric = Number.parseFloat(text);
            if (!Number.isFinite(numeric) || numeric <= 0) return '';

            return numeric.toFixed(maxDecimals).replace(/\.?0+$/, '');
        }

        function decimalToNumber(value, maxDecimals = 10) {
            const normalized = normalizeDecimal(value, maxDecimals);
            return normalized === '' ? 0 : Number.parseFloat(normalized);
        }

        $(document).on('click', '#btn_add_color', function () {
            addColorRow('#000000', 0, '', 0);
        });

        $(document).on('input', '.color_cantidad, .color_precio', function () {
            recalcularDesdeColores();
        });

        $(document).on('click', '.remove-item', function () {
            $(this).closest('.color-item').remove();
            recalcularDesdeColores();
        });
        
        function setProductoReadonly(estado) {
            $('#dsc_producto').prop('readonly', !!estado);
        }

        function resetModal() {
            const form = $('#formMovimientoInventario')[0];
            if (form) form.reset();

            $('#colores_container').empty();
            $('#variantes_container').empty();

            setProductoReadonly(false);

            // limpiar preview imagen
            $('#preview_imagen').hide().attr('src', '');
            $('#placeholder_imagen').show();

            // ✅ limpiar input file real + label
            $('#imagen_producto').val('');
            $('.custom-file-label').removeClass('selected').html('Seleccionar archivo');

            // ✅ resetear calculados
            $('#cantidad').val('0');
            $('#subtotal').val('0.00');

            // hidden
            $('#id_producto').val('');
            $('#tipo_movimiento').val('');
            $('#tabla_hidden').val('cat_inventario_promo');

            // por si acaso
            $('input[name="color"]').val('[]');
            $('input[name="variantes"]').val('[]');
            $('input[name="stock"]').val('0');

            // compat si lo usas en submit
            // (si ya no existe el input, no pasa nada)
            $('input[name="precio_unitario"]').val('0');
        }

        function safeJsonParse(val, fallback) {
            if (!val) return fallback;
            if (Array.isArray(val)) return val;
            try {
            const parsed = JSON.parse(val);
            return (parsed === null || parsed === undefined) ? fallback : parsed;
            } catch (e) {
            return fallback;
            }
            const colores = safeJsonParse(d.colores, []);
            if (Array.isArray(colores) && colores.length > 0) {
            colores.forEach(c => {
                const hex = (typeof c === 'string') ? c : (c.hexadecimal || c.hex || '#000000');
                const qty = (typeof c === 'object' && c) ? (parseInt(c.cantidad, 10) || 0) : 0;
                const mat = (typeof c === 'object' && c) ? (c.material || c.variante || '') : '';
                const prc = (typeof c === 'object' && c) ? (parseFloat(c.precio) || 0) : 0;
                addColorRow(hex, qty, mat, prc);
            });
            } else {
            if ((d.tipo || 'nuevo') === 'nuevo') addColorRow('#000000', 0, '', 0);
            }

            recalcularDesdeColores();
        }

        /* ==========================================================
            COLORES
        ========================================================== */
        function addColorRow(color = '#000000', cantidad = 0, material = '', precio = 0) {

            // Para que no se vea "0" como valor inicial
            const qtyVal = (cantidad && parseInt(cantidad, 10) > 0) ? parseInt(cantidad, 10) : '';
            const prcVal = normalizeDecimal(precio, 10);

            let html = `
                <div class="row color-item align-items-end mb-3">

                    <div class="col-12 col-md-3">
                        <label class="font-11 text-muted mb-1">Color</label>
                        <input type="color"
                            class="form-control color_hex"
                            value="${color}">
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="font-11 text-muted mb-1">Unidades</label>
                        <input type="number"
                            class="form-control color_cantidad clear-on-focus"
                            min="0"
                            inputmode="numeric"
                            value="${qtyVal}">
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="font-11 text-muted mb-1">Precio unitario</label>
                        <input type="number"
                            class="form-control color_precio clear-on-focus"
                            min="0"
                            step="0.0000000001"
                            inputmode="decimal"
                            value="${prcVal}">
                    </div>

                    <div class="col-12 col-md-3">
                        <button type="button"
                                class="btn btn-danger btn-sm remove-item w-100"
                                title="Quitar">
                        <i class="mdi mdi-close"></i>
                        </button>
                    </div>

                </div>
            `;

            $('#colores_container').append(html);
        }

        /* ==========================================================
            VARIANTES (JSON en hidden)
        ========================================================== */
        function addVarianteRow(nombre = '', valor = '') {
            let html = `
                <div class="row mb-2 variante-item">
                <div class="col-md-5">
                    <input type="text" class="form-control variante_nombre" placeholder="Atributo (Ej: Talla)" value="${nombre}">
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control variante_valor" placeholder="Valor (Ej: M)" value="${valor}">
                </div>
                <div class="col-md-2 text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="mdi mdi-close"></i>
                    </button>
                </div>
                </div>
            `;
            $('#variantes_container').append(html);
            }

            $(document).on('click', '#btn_add_variante', function () {
            addVarianteRow();
        });

        $(document).on('focus', '.clear-on-focus', function () {
            const v = ($(this).val() || '').toString().trim();
            if (v === '0' || v === '0.00') $(this).val('');
        });

        $(document).on('blur', '.color_cantidad', function () {
            const n = parseInt($(this).val() || 0, 10);
            $(this).val(n > 0 ? String(n) : '');
        });

        $(document).on('blur', '.color_precio', function () {
            $(this).val(normalizeDecimal($(this).val(), 10));
            recalcularDesdeColores();
        });

        /* ==========================================================
            REMOVER ITEM (sirve para colores y variantes)
        ========================================================== */
        $(document).on('click', '.remove-item', function () {
            $(this).closest('.row').remove();
        });

        /* ==========================================================
            PREVIEW IMAGEN
        ========================================================== */
        $(document).on('change', '.custom-file-input', function () {
            const fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);

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
            MODAL – ABRIR (nuevo / editar / salida)
            Requiere que el botón tenga data-*
            data-id, data-tabla, data-tipo, data-nombre, data-colores, data-variantes
        ========================================================== */
       $(document).on('click', '.btn-movimiento', function () {
            const d = $(this).data();

            resetModal();

            $('#id_producto').val(d.id || '');
            $('#tipo_movimiento').val(d.tipo || 'nuevo');
            $('#dsc_producto').val(d.nombre || '');
            $('#tabla_hidden').val(d.tabla || 'cat_inventario_promo');

            // ============================
            // COLORES / PRESENTACIONES
            // soporta:
            // - string "#fff"
            // - {hexadecimal, cantidad, material, precio}
            // - {hex, cantidad, variante, precio}
            // ============================
            const colores = safeJsonParse(d.colores, []);

            if (Array.isArray(colores) && colores.length > 0) {

                colores.forEach(c => {
                const hex = (typeof c === 'string') ? c : (c.hexadecimal || c.hex || '#000000');
                const qty = (typeof c === 'object' && c) ? (parseInt(c.cantidad, 10) || 0) : 0;
                const mat = (typeof c === 'object' && c) ? (c.material || c.variante || '') : '';
                const prc = (typeof c === 'object' && c) ? (parseFloat(c.precio) || 0) : 0;

                addColorRow(hex, qty, mat, prc);
                });

            } else {
                // si es nuevo, deja al menos una fila lista
                if ((d.tipo || 'nuevo') === 'nuevo') {
                addColorRow('#000000', 0, '', 0);
                }
            }

            // ============================
            // VARIANTES (se mantienen aparte, NO se combinan)
            // ============================
            const vars = safeJsonParse(d.variantes, []);
            if (Array.isArray(vars) && vars.length > 0) {
                vars.forEach(v => {
                if (typeof v === 'string') {
                    addVarianteRow(v, '');
                } else if (v && typeof v === 'object') {
                    addVarianteRow(v.atributo || '', v.valor || '');
                }
                });
            }

            // ============================
            // MODO SALIDA
            // ============================
            if ((d.tipo || '') === 'salida') {
                setProductoReadonly(true);

                // En salida, cantidad funciona como "cantidad a retirar"
                $('#cantidad').prop('readonly', false).val('');
                $('label[for="cantidad"]').text('Cantidad a retirar');

                // Subtotal no aplica en salida (déjalo en 0)
                $('#subtotal').val('0.00');

            } else {
                // En nuevo/editar, cantidad es calculada por colores
                $('#cantidad').prop('readonly', true);
                $('label[for="cantidad"]').text('Cantidad solicitada');

                // Recalcular cantidad y subtotal desde colores al abrir
                recalcularDesdeColores();
            }

            const titulos = {
                nuevo: 'Nuevo Producto',
                editar: 'Editar Producto',
            };
            $('#modalTitulo').text(titulos[d.tipo] || 'Movimiento');

            $('#modalMovimientoInventario').modal('show');
        });

        $(document).on('input', '#dsc_producto', function () {
            this.value = (this.value || '').toUpperCase();
        });

        $(document).on('change', '#imagen_producto', function () {
            const fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName || 'Seleccionar archivo');

            const input = this;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                $('#preview_imagen').attr('src', e.target.result).show();
                $('#placeholder_imagen').hide();
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                $('#preview_imagen').hide().attr('src', '');
                $('#placeholder_imagen').show();
            }
        });

        /* ==========================================================
            MODAL IMAGEN GRANDE
        ========================================================== */
        $(document).on('click', '.btn-ver-imagen', function () {
            const src = $(this).data('src');
            $('#imagenGrande').attr('src', src);
            $('#modalImagen').modal('show');
        });

        /* ==========================================================
            CÁLCULOS AUTOMÁTICOS
        ========================================================== */
        $('#cantidad, #precio_unitario').on('input', function () {
            let cantidad = parseFloat($('#cantidad').val()) || 0;
            let precio   = parseFloat($('#precio_unitario').val()) || 0;
            $('#subtotal').val((cantidad * precio).toFixed(2));
        });

        // ==========================================================
        // FORM – SUBMIT (GUARDAR PRODUCTO)
        // - Construye JSON de color (incluye precio) y variantes
        // - Calcula stockTotal desde cantidades por color
        // - Calcula subtotal/total desde (cantidad * precio) por color
        // - Envía todo por AJAX
        // ==========================================================
        $('#formMovimientoInventario').on('submit', function (e) {
            e.preventDefault();

            const form = this;
            const url = $(form).attr('action');
            const formData = new FormData(form);

            const $btn = $(form).find('button[type="submit"]');
            const originalText = $btn.html();

            let colores = [];
            let variantes = [];

            let stockTotal = 0;
            let subtotalColores = 0;

            // Colores/presentaciones
            $('#colores_container .color-item').each(function () {
                const hex = $(this).find('.color_hex').val();
                const cantidad = parseInt($(this).find('.color_cantidad').val(), 10) || 0;
                const material = ($(this).find('.color_material').val() || '').trim();
                const precioNormalizado = normalizeDecimal($(this).find('.color_precio').val(), 10);
                const precio = decimalToNumber(precioNormalizado, 10);

                if (hex) {
                const qty = Math.max(0, cantidad);
                const prc = Math.max(0, precio);

                colores.push({ hexadecimal: hex, cantidad: qty, material: material, precio: precioNormalizado === '' ? '0' : precioNormalizado });
                stockTotal += qty;
                subtotalColores += qty * prc;
                }
            });

            // No required, pero sí validación mínima para no guardar “vacío”
            if (!colores.length && !($('#dsc_producto').val() || '').trim()) {
                Swal.fire('Aviso', 'Captura al menos el nombre del producto o agrega una presentación por color.', 'info');
                return;
            }

            // Variantes (se mantienen aparte)
            $('#variantes_container .variante-item').each(function () {
                const nombre = ($(this).find('.variante_nombre').val() || '').trim();
                const valor  = ($(this).find('.variante_valor').val() || '').trim();
                if (nombre !== '') variantes.push({ atributo: nombre, valor: valor });
            });

            // cálculos
            $('#cantidad').val(String(stockTotal));
            $('#subtotal').val(subtotalColores.toFixed(2));

            // Hidden + FormData
            $('input[name="color"]').val(JSON.stringify(colores));
            $('input[name="variantes"]').val(JSON.stringify(variantes));
            $('input[name="stock"]').val(String(stockTotal));

            formData.set('color', JSON.stringify(colores));
            formData.set('variantes', JSON.stringify(variantes));
            formData.set('stock', String(stockTotal));

            // compat con backend
            formData.set('cantidad', String(stockTotal));
            formData.set('subtotal', subtotalColores.toFixed(2));
            formData.set('total', subtotalColores.toFixed(2));
            formData.set('precio_unitario', '0');

            // tabla
            formData.set('tabla', $('#tabla_hidden').val() || 'cat_inventario_promo');

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
                if (res && res.error === false) {
                    Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: res.respuesta || 'Guardado correctamente.',
                    timer: 1500,
                    showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', (res && res.respuesta) ? res.respuesta : 'Error al guardar.', 'error');
                }
                },
                error: function (xhr) {
                console.log('AJAX ERROR:', xhr.status, xhr.responseText);
                Swal.fire('Error', 'No se pudo procesar la petición. Revisa consola (Network).', 'error');
                },
                complete: function () {
                $btn.prop('disabled', false).html(originalText);
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
                    if (res && res.error === false) {
                    Swal.fire('Eliminado', res.respuesta || 'Eliminado.', 'success')
                        .then(() => location.reload());
                    } else {
                    Swal.fire('Error', (res && res.respuesta) ? res.respuesta : 'No se pudo eliminar.', 'error');
                    }
                },
                'json'
                ).fail(function (xhr) {
                console.log('DELETE ERROR:', xhr.status, xhr.responseText);
                Swal.fire('Error', 'No se pudo procesar la petición. Revisa consola.', 'error');
                });
            }
            });
        });

        $(document).on('click', '.btn-consultar-recibo', function () {
            const idConvenio = parseInt($(this).data('convenio') || 0, 10);

            if (!idConvenio) {
                Swal.fire('Error', 'No se pudo determinar el convenio.', 'error');
                return;
            }

            $.ajax({
                url: '<?= base_url("index.php/Inicio/consultarReciboPromo") ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id_convenio_promo: idConvenio,
                    id_inventario_promo: 0 // 👈 ya no buscamos por artículo
                },
                success: function(res){
                    if (res && res.error === false) {
                        if (res.existe && res.pdf_url) {
                            window.open(res.pdf_url, '_blank');
                        } else {
                            Swal.fire('Recibo no generado', 'Aún no existe un recibo para este convenio.', 'info');
                        }
                    } else {
                        Swal.fire('Error', (res && res.respuesta) ? res.respuesta : 'No se pudo consultar el recibo.', 'error');
                    }
                },
                error: function(){
                    Swal.fire('Error', 'No se pudo procesar la petición.', 'error');
                }
            });
        });

        // Seleccionar todos
        $(document).on('change', '#check_all', function () {
            const checked = $(this).is(':checked');
            $('.chk-producto').prop('checked', checked);
        });

        // Si desmarcan uno, desmarca el master
        $(document).on('change', '.chk-producto', function () {
            const total = $('.chk-producto').length;
            const sel = $('.chk-producto:checked').length;
            $('#check_all').prop('checked', total > 0 && total === sel);
        });

        // Helper: ids seleccionados
        function getSelectedIds() {
            return $('.chk-producto:checked').map(function(){ return $(this).val(); }).get();
        }
        $(document).on('click', '#btnExportExcel', function () {
            const idConvenio = parseInt($(this).data('convenio') || 0, 10);
            if (!idConvenio) {
                Swal.fire('Error', 'No se pudo determinar el contrato.', 'error');
                return;
            }
            window.location.href = "<?= base_url('index.php/Inicio/exportarHistorialEntregasExcel/') ?>" + idConvenio;
        });
    });
</script>
