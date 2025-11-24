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

                           
                            <h4 class="header-title mt-0">Lista Inventarios

                            </h4>
                            <div class="table-responsive dash-social">
                                <table id="usuariosTable" class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">PROVEEDOR</th>
                                            <th class="text-center">NO. PROVEEDOR</th>
                                            <th class="text-center">RFC</th>
                                            <th class="text-center">MONTO</th>
                                            <th class="text-center">FOLIO</th>
                                            <th class="text-center">PERIODO</th>
                    
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                        <!--end tr-->
                                    </thead>

                                    <tbody>
                                        <?php foreach($cat_servicio as $i): ?>
                                        <tr>
                                           
                                            <td class="text-center"><?= $i->id_servicio ?></td>
                                            <td class="text-center"><?= $i->dsc_servicio ?></td>
                                            <td class="text-center"><?= $i->no_proveedor ?></td>
                                            <td class="text-center"><?= $i->rfc ?></td>
                                            <td class="text-center"><input type="text" class="form-control" name="monto"></td>
                                            <td class="text-center"><input type="text" class="form-control" name="folio"></td>
                                            <td class="text-center"><input type="text" class="form-control" name="periodo"></td>
                             
                                            <td class="text-center">

                                             <a href="javascript:void(0)"  onclick="ini.inicio.servicio(<?= $i->id_servicio ?>, this)">
                                                    <i class="mdi mdi-file text-success font-18"></i>
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


<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />
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
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>


<!-- Required datatable js -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

<!-- App js -->
<script src="<?= base_url()?>assets/js/app.js"></script>
<script src="<?= base_url()?>assets/js/waves.js"></script>
<script src="<?= base_url()?>assets/js/feather.min.js"></script>

<script src="<?= base_url()?>plugins/tiny-editable/mindmup-editabletable.js"></script>
<script src="<?= base_url()?>plugins/tiny-editable/numeric-input-example.js"></script>
<script src="<?= base_url()?>plugins/bootable/bootstable.js"></script> 
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />

<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>
<script>
  $('#usuariosTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: true,
    });
</script>