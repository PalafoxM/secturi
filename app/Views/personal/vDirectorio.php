<!-- Top Bar End -->
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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Metrica</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Analytics</a></li>
                                <li class="breadcrumb-item active">Directorio</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Directorio</h4>

                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                       
                            <h4 class="header-title mt-0">Directorio Activo</h4>
                            <div class="table-responsive dash-social">
                                <table id="usuariosTable" class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">USUARIO</th>
                                            <th class="text-center">NOMBRE</th>
        
                                            <th class="text-center">PUESTO</th>
                       
                                            <th class="text-center">AREA</th>
                                            <th class="text-center">CORREO</th>
                                            <th class="text-center">EXT.</th>

                                        </tr>
                                        <!--end tr-->
                                    </thead>

                                    <tbody>
                                        <?php foreach($usuario as $u): ?>
                                        <tr>
                                            <?php if(!empty($u->ruta_foto_relativa )): ?>
                                            <td class="text-center"><img src="<?= base_url().$u->ruta_foto_relativa ?>" alt="" class="rounded-circle thumb-sm mr-1"></td>
                                            <?php endif; ?>
                                             <?php if(empty($u->ruta_foto_relativa )): ?>
                                                <td class="text-center"><img src="<?= base_url() ?>assets/images/users/user-3.jpg" alt="" class="rounded-circle thumb-sm mr-1"></td>
                                            <?php endif; ?>
                                      
                                            <td class="text-center"><?= $u->nombre_completo ?></td>
                                            <td class="text-center"><?= $u->dsc_puesto?></td>
                                            <td class="text-center"><?= $u->dsc_area?></td>
                                            <td class="text-center"><?= $u->correo?></td>
                                            <td class="text-center"><?= $u->extencion?></td>
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
    ini.inicio.agregarUsuario();
    $('#usuariosTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: true,
    });

    </script>