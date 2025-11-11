<!-- Top Bar End -->

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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">lista PT</a></li>
                                <li class="breadcrumb-item active">Vehículos</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Lista de Vehiculos</h4>

                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title mt-0">vehículos</h4>
                            <div class="table-responsive dash-social">
                                <table id="datatableVehiculo" class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">No CONTROL</th>
                                            <th class="text-center">MARCA</th>
                                            <th class="text-center">TIPO</th>
                                            <th class="text-center">MODELO</th>
                                            <th class="text-center">ACTIVO</th>
                                            <th class="text-center">No. TARJETA</th>
                                            <th class="text-center">DOTACION</th>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                        <!--end tr-->
                                    </thead>

                                    <tbody>
                                        <?php foreach($vehiculos as $e): ?>
                                        <tr>
                                            <td  class="text-center"><?= $e->id_vehiculo?></td>
                                            <td  class="text-center"><?= $e->no_control?></td>
                                            <td  class="text-center"><?= $e->marca?></td>
                                            <td  class="text-center"><?= $e->tipo?></td>
                                            <td  class="text-center"><?= $e->modelo?></td>
                                            <td  class="text-center"><?= $e->no_activo_fijo?></td>
                                            <td  class="text-center"><?= $e->no_tarjeta?></td>
                                            <td  class="text-center"><?= $e->dotacion?></td>

                                            <td  class="text-center" class="text-center">
                                                <a class="btn btn-outline-info btn-round" title="editar" onclick="ini.inicio.getVehiculo(<?= $e->id_vehiculo?>)" >
                                                    <i class="mdi dripicons-pencil font-18"></i></a>
                                                     <a href="<?php echo base_url().'index.php/Usuario/VehiculoTP/'.$e->id_vehiculo ?>" target="_blank">
                                                   <button type="button"  class="btn btn-outline-info btn-round">                       
                                               <i class="mdi dripicons-arrow-right text-success font-18"></i></button></a>
                                                <a class="btn btn-outline-info btn-round" href="javascript:void(0);"  onclick="ini.inicio.deletePT(<?= $e->id_vehiculo?>)" ><i
                                                        class="mdi mdi-delete-forever text-danger font-18"></i></a>
                                         
                                              
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div><!-- container -->
    </div>
<div class="modal fade" id="modalVehiculo" tabindex="-1" aria-labelledby="modalEliminarReservaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalEliminarReservaLabel">Editar</h5>
        <button type="button" onclick="ini.inicio.cerrarModalVehiculo()" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
        <div class="modal-body">
            <input type="hidden" id="id_vehiculo" name="id_vehiculo" >
           <div class="row">                            
                <div class="col-md-3">
                    <div class="mb-3 position-relative" id="">
                        <label for="no_control"
                            class="form-label campoObligatorio">No. CONTROL</label>
                        <input type="text" autocomplete="off" class="form-control"
                            id="no_control" name="no_control" placeholder="No. CONTROL">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3 position-relative" id="">
                            <label for="marca"
                                class="form-label campoObligatorio">MARCA</label>
                            <input type="text" autocomplete="off" class="form-control"
                                id="marca" name="marca"
                                placeholder="MARCA">
                    </div>
                </div>
                 <div class="col-md-3">
                        <div class="mb-3 position-relative" id="">
                            <label for="tipo"
                                class="form-label campoObligatorio">TIPO</label>
                            <input type="text" autocomplete="off" class="form-control"
                                id="tipo" name="tipo"
                                placeholder="TIPO">
                        </div>
                </div>
                <div class="col-md-3">
                        <div class="mb-3 position-relative" id="">
                            <label for="modelo" class="form-label">MODELO</label>
                            <input type="text" autocomplete="off" class="form-control"
                                id="modelo" name="modelo" placeholder="FEC. NACIMIENTO">
                        </div>
                 </div>
            </div>
           <div class="row">                            
                <div class="col-md-3">
                    <div class="mb-3 position-relative" id="">
                        <label for="activo"
                            class="form-label campoObligatorio">ACTIVO</label>
                        <input type="text" autocomplete="off" class="form-control"
                            id="activo" name="activo" placeholder="ACTIVO">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3 position-relative" id="">
                            <label for="no_tarjeta"
                                class="form-label campoObligatorio">No. TARJETA</label>
                            <input type="text" autocomplete="off" class="form-control"
                                id="no_tarjeta" name="no_tarjeta"
                                placeholder="No. TARJETA">
                    </div>
                </div>
                 <div class="col-md-3">
                        <div class="mb-3 position-relative" id="">
                            <label for="dotacion"
                                class="form-label campoObligatorio">DOTACION</label>
                            <input type="text" autocomplete="off" class="form-control"
                                id="dotacion" name="dotacion"
                                placeholder="DOTACION">
                        </div>
                </div>
                <div class="col-md-3">
                        <div class="mb-3 position-relative" id="">
                            <label for="placa" class="form-label campoObligatorio">PLACA</label>
                                <input type="text" autocomplete="off" class="form-control"
                                id="placa" name="placa"
                                placeholder="PLACA">
                        </div>
                 </div>
            </div>
           <div class="row">                            
                 <div class="col-md-6">
                        <div class="mb-3 position-relative" id="">
                            <label for="no_serie"
                                class="form-label campoObligatorio">No. SERIE</label>
                            <input type="text" autocomplete="off" class="form-control"
                                id="no_serie" name="no_serie"
                                placeholder="No. SERIE">
                        </div>
                </div>
                <div class="col-md-6">
                        <div class="mb-3 position-relative" id="">
                            <label for="id_usuario" class="form-label campoObligatorio">USUARIO</label>
                               <select class="select2 form-control custom-select" id="id_usuario" name="id_usuario" >
                                <?php foreach($usuario as $p): ?>
                                  <option value="<?= $p->id_usuario ?>"><?= $p->nombre_completo ?></option>
                                <?php endforeach; ?>
                                </select>
                        </div>
                 </div>
            </div>
        </div>
        <div class="modal-footer">
        <button type="button" onclick="ini.inicio.cerrarModalVehiculo()" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" onclick="ini.inicio.guardarVehiculo()" id="btn_vehiculo" class="btn btn-primary" >Guardar</button>
      </div>
    </div>
  </div>
</div>
   


    <link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo base_url(); ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />



    <!-- jQuery  -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>
   

    <!-- Required datatable js -->
    <script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/pages/jquery.analytics_customers.init.js"></script>
      <script src="<?php echo base_url(); ?>plugins/select2/select2.min.js"></script>
    <script>
        
$(document).ready(function() {

    $('#datatableVehiculo').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: true,
    });
});
    </script>