
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
                                <h4 class="page-title">Bitacora de hoy</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <?php if(isset($bitacora) && !empty($bitacora)): ?>
                        <?php foreach($bitacora as $i): ?>
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="blog-card">
                                            <?php if(isset($i->foto) && !empty($i->foto)): ?>
                                                <img src="<?= base_url().$i->foto?>" alt="" class="img-fluid"/>
                                            <?php endif; ?>
                                            <?php if(empty($i->foto)): ?>
                                            <img src="<?= base_url(); ?>assets/images/small/img-9.jpg" alt="" class="img-fluid"/>
                                            <?php endif; ?>
                                            <hr class="hr-dashed">
                                            <div class="d-flex justify-content-between">
                                                <div class="meta-box">
                                                    <div class="media">
                                                        <div class="media-body align-self-center text-truncate">
                                                            <h6 class="mt-0 mb-1 text-dark"> Nombre: <?= $i->nombre_completo ?> Hora: <?= $i->hora ?></h6>
                                                            <ul class="p-0 list-inline mb-0">
                                            
                                                                <li class="list-inline-item">Correo: <?= $i->correo ?> </li>
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
                            <?php endif; ?>
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
 
