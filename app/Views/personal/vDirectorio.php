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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Personal</a></li>
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
                                            <th >NOMBRE</th>
                                            <th>PUESTO</th>
                                            <th>AREA</th>
                                            <th>CORREO</th>
                                            <th>EXT.</th>

                                        </tr>
                                        <!--end tr-->
                                    </thead>

                                    <tbody>
                                        <?php foreach($usuario as $u): ?>
                                        <tr>
                                            <?php if(!empty($u->ruta_foto_relativa )): ?>
                                            <td class="text-center"><a href="javascript:void(0);" onclick="ini.inicio.verDetalles(<?= $u->id_usuario?>)" ><img src="<?= base_url().$u->ruta_foto_relativa ?>" alt="" class="rounded-circle thumb-sm mr-1"></a></td>
                                            <?php endif; ?>
                                             <?php if(empty($u->ruta_foto_relativa )): ?>
                                            <td class="text-center"><a href="javascript:void(0);" onclick="ini.inicio.verDetalles(<?= $u->id_usuario?>)" ><img src="<?= base_url() ?>assets/images/users/user-3.jpg" alt="" class="rounded-circle thumb-sm mr-1"></a></td>
                                            <?php endif; ?>
                                      
                                            <td><?= $u->nombre_completo ?></td>
                                            <td><?= $u->dsc_puesto?></td>
                                            <td><?= $u->dsc_area?></td>
                                            <td><?= $u->correo?></td>
                                            <td><?= $u->extencion?></td>
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
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="">Ver detalles</h5>
        <button type="button" onclick="ini.inicio.cerrarUsuario()" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
        <div class="modal-body">
           <div class="met-profile-main-pic text-center">
               
               
            </div>
        </div>
      <div class="modal-footer">
        <button type="button" onclick="ini.inicio.cerrarUsuario()" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
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