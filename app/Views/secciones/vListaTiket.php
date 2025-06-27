


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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Secturi</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">seccion</a></li>
                                <li class="breadcrumb-item active">Listado de Tikets</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Tikets</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
     
            <!--end row-->
            <div class="row">
                <div class="col-12">
                    <div class="tab-content detail-list" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="general_detail">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">

                                        <div class="card-body">
                                            <span>TIKETS</span>
                                            <button 
                                                class="btn btn-gradient-primary px-4 float-right mt-0 mb-3"><i
                                                    class="mdi mdi-plus-circle-outline mr-2"></i>Agregar Perfil</button>
                                            <table id="datatableCategorias" class="table" data-toggle="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">ID TIKET</th>
                                                        <th class="text-center">USUARIO</th>
                                                        <th class="text-center">NÚMERO DE TIKETS</th>
                                                        <th class="text-center">DESCRIPCIÓN</th>
                                                        <th class="text-center">ESTATUS</th>
                                                        <th class="text-center">ACCIONES</th>
                                                    </tr>
                                                    <!--end tr-->
                                                </thead>

                                                <tbody>
                                                    <?php foreach($cat_tiket as $p): ?>
                                                    <tr>
                                                        <td class="text-center"><?= $p->id_tiket?></td>
                                                        <td class="text-center"><?= $p->nombre_completo?></td>
                                                        <td class="text-center"><?= $p->numero_tiket?></td>
                                                        <td class="text-center"><?= $p->dsc_tiket?></td>
                                                        <td class="text-center">
                                                            <?= ($p->estatus==0)?
                                                            '<i class="dripicons-cross text-danger font-18"></i>':
                                                            '<i class="dripicons-checkmark text-success font-18"></i>'
                                                             ?>
                                                        </td>
                                                      
                                                        <td class="text-center">
                                                              <?php if($p->estatus == 0):?>
                                                            <button title="Hecho"
                                                                onclick="ini.inicio.tiketListo(<?= $p->id_tiket?>)"
                                                                class="btn btn-gradient-info px-4">Hecho
                                                            </button>
                                                            <?php endif; ?>
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
                        <!--end general detail-->


                      

                        <!--end settings detail-->
                    </div>
                    <!--end tab-content-->

                </div>
                <!--end col-->
            </div>
            <!--end row-->

        </div><!-- container -->



    </div>
    <!-- end page content -->
</div>


<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />
<!-- App css -->
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- jQuery  -->
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>
<!-- Required datatable js -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/pages/jquery.analytics_customers.init.js"></script>

<script src="<?= base_url()?>plugins/apexcharts/apexcharts.min.js"></script>

<!-- App js -->
<script src="<?= base_url()?>assets/js/app.js"></script>


<script src="<?= base_url()?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url()?>assets/js/waves.js"></script>
<script src="<?= base_url()?>assets/js/feather.min.js"></script>



<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
<script>

$(document).ready(function() {
  $('#datatableCategorias,#datatablePeriodos,#datatableCursos').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: true,
    });
});

</script>