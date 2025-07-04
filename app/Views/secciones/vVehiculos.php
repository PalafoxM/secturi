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
                            
                                                <a class="btn btn-outline-info btn-round" title="bitacora" href="" >
                                                    <i class="mdi mdi-content-paste font-18"></i></a>
                                                   <button type="button"  class="btn btn-outline-info btn-round">                       
                                                <a href="<?php echo base_url().'index.php/Principal/ImprimirPT/'.$e->id_vehiculo ?>" target="_blank"><i
                                                        class="mdi mdi-file-document text-success font-18"></i></a></button>
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
   


    <link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />

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
    <script src="<?php echo base_url(); ?>plugins/apexcharts/apexcharts.min.js"></script>

    <!-- Required datatable js -->
    <script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/pages/jquery.analytics_customers.init.js"></script>
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