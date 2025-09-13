
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
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                                        <li class="breadcrumb-item active">Inventario</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Inventario</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <?php foreach($inventario as $i): ?>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="blog-card">
                                        <img src="<?= base_url(); ?>assets/images/small/img-9.jpg" alt="" class="img-fluid"/>
                                        <h4 class="my-3">
                                            <a href="" class=""><?= $i->denominacion_activo_fijo ?></a>
                                        </h4>
                                        <p class="text-muted">Activo Fijo <?= $i->activo_fijo?></p>
                                        <hr class="hr-dashed">
                                        <div class="d-flex justify-content-between">
                                            <div class="meta-box">
                                                <div class="media">
                                                    <div class="media-body align-self-center text-truncate">
                                                        <h6 class="mt-0 mb-1 text-dark">Fabricante: <?= $i->fabricante ?></h6>
                                                        <ul class="p-0 list-inline mb-0">
                                                            <li class="list-inline-item">Fec. Cap: <?= date('d/m/Y', strtotime($i->fec_cap)); ?></li>
                                                            <li class="list-inline-item">No. Serie: <?= $i->no_serie ?> </li>
                                                        </ul>
                                                    </div><!--end media-body-->
                                                </div>                                            
                                            </div><!--end meta-box-->
                                        </div>                                        
                                    </div><!--end blog-card--> 
                                                               
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div> <!--end col-->

                            <?php endforeach; ?>
                    </div><!--end row--> 

                </div><!-- container -->

        <!-- App css -->
        <link href="<?= base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url();?>assets/css/jquery-ui.min.css" rel="stylesheet">
        <link href="<?= base_url();?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url();?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url();?>assets/css/app.min.css" rel="stylesheet" type="text/css" />



  <!-- jQuery  -->
        <script src="<?= base_url();?>assets/js/jquery.min.js"></script>
        <script src="<?= base_url();?>assets/js/jquery-ui.min.js"></script>
        <script src="<?= base_url();?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url();?>assets/js/metismenu.min.js"></script>
        <script src="<?= base_url();?>assets/js/waves.js"></script>
        <script src="<?= base_url();?>assets/js/feather.min.js"></script>
        <script src="<?= base_url();?>assets/js/jquery.slimscroll.min.js"></script>
        <script src="<?= base_url();?>plugins/apexcharts/apexcharts.min.js"></script> 
        
        <!-- App js -->
        <script src="<?= base_url();?>assets/js/app.js"></script>