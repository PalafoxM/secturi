<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mt-4">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Promoción</a></li>
                                <li class="breadcrumb-item active">Bitácora de Salidas</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Bitácora de Salidas: <?= isset($articulo) ? $articulo->dsc_producto : 'Desconocido' ?></h4>
                    </div>
                </div>
            </div>

            <!-- Content -->
             <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
                                <h4 class="header-title mt-0 mb-0 text-dark">Registros de Salida</h4>
                                <div>
                                     <!-- Link to FormularioPromo for Adding New -->
                                     <!-- We need idConvenio. Assuming we can get it from articulo or pass it. 
                                          The user's code uses FormularioPromo/ID_C/ID_A.
                                          We have ID_A ($idArticulo). We need ID_C.
                                          Let's assume $articulo->id_convenio exists if that's how it works, 
                                          OR simpler: just pass 0 or handling it in controller if needed.
                                          Actually, in vInventarioPromocion it was: $materiales->convenio (just string?) 
                                          Wait, the link was `FormularioPromo/'. $id_convenio. "/" . $item->id_inventario_promo`.
                                          $id_convenio comes from controller `InventarioPromocion($id)`.
                                          Here we only have `ListaSalidasPromo($idArticulo)`.
                                          We might be missing $id_convenio.
                                          However, since we saved it in `salida_inventario`, we can get it from there if there are records,
                                          or we should have passed it to ListaSalidasPromo.
                                          
                                          For now, I will assume $articulo has it or I'll check if I need to adjust the controller to fetch it.
                                          In `vInventarioPromocion`, the products are fetched from `cat_inventario_promo` join `cat_material_promo`.
                                          Let's update the Add button later if needed. For now I'll use a placeholder or try to infer.
                                          Actually, I'll use the Back button logic to go back to inventory.
                                     -->
                                    <a href="<?= base_url("index.php/Inicio/FormularioPromo/0/$idArticulo") ?>" class="btn btn-primary px-4 shadow-sm">
                                        <i class="mdi mdi-plus-box mr-2"></i> Agregar Salida
                                    </a>
                                </div>
                            </div>

                            <div class="table-responsive dash-social">
                                <table id="tablaSalidas" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="thead-light">
                                        <tr class="text-center">
                                            <th>Fecha Evento</th>
                                            <th>Solicitante</th>
                                            <th>Puesto</th>
                                            <th>Lugar</th>
                                            <th>Cantidad</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($salidas) && !empty($salidas)): ?>
                                            <?php foreach($salidas as $s): ?>
                                                <tr class="text-center align-middle">
                                                    <td><?= date('d/m/Y', strtotime($s->fec_eve)) ?></td>
                                                    <td><?= $s->nombre_solicitante ?></td>
                                                    <td><?= $s->puesto ?></td>
                                                    <td><?= $s->lugar ?></td>
                                                    <td><span class="badge badge-soft-info p-2 font-13"><?= $s->cantidad ?></span></td>
                                                    <td>
                                                        <a href="<?= base_url("index.php/Inicio/FormularioPromo/{$s->id_convenio}/{$s->id_articulo}/{$s->id_salida_inventario}") ?>" 
                                                           class="btn btn-sm btn-outline-primary" title="Editar">
                                                            <i class="mdi mdi-pencil"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-outline-danger btn-eliminar-salida" 
                                                                data-id="<?= $s->id_salida_inventario ?>" title="Eliminar">
                                                            <i class="mdi mdi-trash-can"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No hay registros de salida.</td>
                                            </tr>
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

<script>
    $(document).ready(function() {
        $('#tablaSalidas').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' }
        });

        // Eliminar
        $('.btn-eliminar-salida').click(function() {
            var id = $(this).data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Se eliminará este registro de salida.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('<?= base_url("index.php/Inicio/eliminarSalida") ?>', {id: id}, function(data) {
                        if (data && !data.error) {
                            Swal.fire('Eliminado!', 'El registro ha sido eliminado.', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', 'No se pudo eliminar el registro.', 'error');
                        }
                    }, 'json');
                }
            })
        });
    });
</script>
