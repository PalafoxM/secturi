<?php  $session = \Config\Services::session();    ?>
<div class="page-wrapper">

    <!-- Page Content-->
    <div class="page-content-tab">

        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Lista</a></li>
                                <li class="breadcrumb-item active">Inventario</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Lista de Inventario SECTURI</h4>

                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <button onclick="ini.inicio.agregarInventario()"
                                class="btn btn-gradient-danger px-4 float-right mt-0 mb-3"><i
                                    class="mdi mdi-plus-outline mr-2"></i>Agregar Inventario</button>
                           
                            <h4 class="header-title mt-0">Lista Inventarios

                            </h4>
                            <div class="table-responsive dash-social">
                                <table id="usuariosTable" class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">FOTO</th>
                                            <th class="text-center">AC. FIJO</th>
                                            <th class="text-center">DESCRIPCION</th>
                                            <th class="text-center">PREFIJO</th>
                                            <th class="text-center">No. SERIE</th>
                                            <th class="text-center">UBICACION</th>
                                            <th class="text-center">OBSERVACIONES</th>
                                            <th class="text-center">USUARIO</th>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                        <!--end tr-->
                                    </thead>

                                    <tbody>
                                        <?php foreach($inventario as $i): ?>
                                        <tr>
                                            <td class="text-center">
                                                 <img src="<?= base_url().$i->foto ?>" alt="" class="rounded-circle thumb-sm mr-1">
                                            </td>
                                            <td class="text-center"><?= $i->activo_fijo ?></td>
                                            <td class="text-center"><?= $i->denominacion_activo_fijo ?></td>
                                            <td class="text-center"><?= $i->prefijo_activo_fijo ?></td>
                                            <td class="text-center"><?= $i->no_serie?></td>
                                            <td class="text-center"><?= $i->ubicacion?></td>
                                            <td class="text-center"><?= $i->observaciones?></td>
                                            <td class="text-center"><?= $i->nombre_completo?></td>
                                            <td class="text-center">

                                                <a href="javascript:void(0);"
                                                    onclick="ini.inicio.getInventario(<?= $i->id_inventario?>)" >
                                                    <i class="mdi mdi-pencil text-success font-18"></i>
                                                </a>
                                                <a href="javascript:void(0);"
                                                    onclick="ini.inicio.deleteIn(<?= $i->id_inventario?>)"><i
                                                        class="mdi mdi-trash-can text-danger font-18"></i>
                                                </a>    

                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal -->
 <div id="modelInventarios" class="modal fade bs-example" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Inventario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detalleCurso" style="max-height: 70vh; overflow-y: auto;">
                    <form id="formInventario" >
                        <div class="row">
                            <input type="hidden" value="0" name="id_inventario" id="id_inventario">
                            <input type="hidden" value="0" name="editar" id="editar">
                            <!-- seccion izquierdo incio -->
                            <div class="col-md-12 ">
                                <div class="card">
                                    <!--init card -->
                                    <div class="card-body">

                                        <div class="row">
                                         
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="unidad"
                                                        class="form-label campoObligatorio">Activo Fijo</label>
                                                    <input class="form-control" id="activo_fijo" name="activo_fijo" placeholder="100010356">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="usuario"
                                                        class="form-label campoObligatorio">Usuario</label>
                                                    <select class="form-control select2" 
                                                        id="usuario" name="usuario">
                                                        <option value="0">Seleccione</option>
                                                        <?php foreach($usuario as $a): ?>
                                                        <option value="<?= $a->no_empleado ?>"><?= $a->nombre_completo ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="denominacion_activo_fijo"
                                                        class="form-label campoObligatorio">Denominacion activo fijo</label>
                                                    <input type="text" autocomplete="off" class="form-control"
                                                        id="denominacion_activo_fijo" name="denominacion_activo_fijo"
                                                        placeholder="DENOMINACION ACTIVO FIJO">
                                                </div>
                                            </div>
                                             <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="prefijo_activo_fijo" class="form-label campoObligatorio">Prefijo Activo Fijo</label>
                                                    <input type="text" autocomplete="off" class="form-control"
                                                        id="prefijo_activo_fijo" name="prefijo_activo_fijo" placeholder="PREFIJO ACTIVO">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                   
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" >
                                                    <label for="no_serie"
                                                        class="form-label campoObligatorio">No. Serie</label>
                                                    <input type="text" autocomplete="off" class="form-control"
                                                        id="no_serie" name="no_serie"
                                                        placeholder="NO. SERIE">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" >
                                                    <label for="fabricante"
                                                        class="form-label">Fabricante</label>
                                                    <input type="text" autocomplete="off" class="form-control" id="fabricante" name="fabricante"
                                                        placeholder="FABRICANTE">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" >
                                                    <label for="marca"
                                                        class="form-label">Marca</label>
                                                    <input type="text" autocomplete="off" class="form-control" id="marca" name="marca"
                                                        placeholder="MARCA">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" >
                                                    <label for="modelo"
                                                        class="form-label">Modelo</label>
                                                    <input type="text" autocomplete="off" class="form-control" id="modelo" name="modelo"
                                                        placeholder="MODELO">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                   
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" >
                                                    <label for="material"
                                                        class="form-label campoObligatorio">Material</label>
                                                    <input type="text" autocomplete="off" class="form-control"
                                                        id="material" name="material"
                                                        placeholder="MATERIAL">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" >
                                                    <label for="color"
                                                        class="form-label">Color</label>
                                                    <input type="text" autocomplete="off" class="form-control" id="color" name="color"
                                                        placeholder="COLOR">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" >
                                                    <label for="ubicacion"
                                                        class="form-label">Ubicacion</label>
                                                    <input type="text" autocomplete="off" class="form-control" id="ubicacion" name="ubicacion"
                                                        placeholder="UBICACION">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" >
                                                    <label for="observaciones"
                                                        class="form-label">observaciones</label>
                                                    <input type="text" autocomplete="off" class="form-control" id="observaciones" name="observaciones"
                                                        placeholder="OBSERVACIONES">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" >
                                                    <label for="estado"
                                                        class="form-label">Estado</label>
                                                    <input type="text" autocomplete="off" class="form-control" id="estado" name="estado"
                                                        placeholder="ESTADO">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" >
                                                    <label for="fec_cap"
                                                        class="form-label">Fec. cap.</label>
                                                    <input type="date" autocomplete="off" class="form-control" id="fec_cap" name="fec_cap"
                                                        placeholder="FEC. CAP.">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" >
                                                    <label for="valor"
                                                        class="form-label">valor</label>
                                                    <input type="text" autocomplete="off" class="form-control" id="valor" name="valor"
                                                        placeholder="VALOR">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" >
                                                    <label for="valor"
                                                        class="form-label">FOTO</label>
                                                    <input type="file" autocomplete="off" class="form-control" id="foto" name="foto"
                                                        accept=".jpg, .png, .jpeg">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end card -->
                            </div>
                            <!-- seccion izquierdo fin-->
                            <!-- seccion derecha incio -->
                        </div>


                        <div class="row mb-5" id="btn_save">
                            <div class="col-md-12 text-center ">
                                
                                <a  class="btn btn-warning text-white" type="button" data-dismiss="modal"><i
                                        class="mdi mdi-content-save-off-outline" id="cancelarTurno"></i> Cancelar
                                </a>
                                <a onclick="ini.inicio.formInventario();" class="btn btn-info text-white" id="btnInventario"><i class="mdi mdi-content-save"></i> Guardar
                                </a>
                            </div>
                        </div>

                    </form>



                </div>
            </div>
        </div>
    </div>

<script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>

<script src="<?= base_url(); ?>assets/js/waves.js"></script>
<script src="<?= base_url(); ?>assets/js/feather.min.js"></script>
<script src="<?= base_url(); ?>assets/js/metismenu.min.js"></script>

<script src="<?= base_url(); ?>assets/js/app.js"></script>

<script>
$(document).ready(function() {
    console.log("Sistema listo. Probando botón de Opalina...");

    // Nota: Asegúrate de que el botón en tu tabla tenga la clase o ID correcto
    $(document).on('click', '.btn-actualizar-stock', function(e) {
        e.preventDefault();
        
        const id = 13; // ID Opalina
        const cant = 1; // Cantidad a descontar

        $.ajax({
            url: '<?= base_url("index.php/Inicio/actualizarInventario") ?>',
            type: 'POST',
            data: {
                id_producto: id,
                tabla: 'cat_inventario_papel',
                tipo_movimiento: 'salida',
                cantidad: cant
            },
            dataType: 'json',
            success: function(res) {
                alert(res.respuesta);
                if (!res.error) location.reload();
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert("Error al conectar con el controlador.");
            }
        });
    });
});
</script>