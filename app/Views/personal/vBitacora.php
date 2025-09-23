
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
                            <div class="card profile-card">
                                <div class="card-body p-0">
                                    <div class="media p-3  align-items-center">                                                
                                            <img src="<?= base_url().$i->foto?>" alt="user" class="rounded-circle thumb-xl">                                        
                                            <div class="media-body ml-3 align-self-center">
                                                <h5 class="pro-title"><?= $i->nombre_completo ?></h5>
                                                <p class="mb-1 text-muted"><?= $i->correo ?></p> 
                                                <p class="mb-0 text-muted"><i class="mdi mdi-checkbox-blank-circle text-success mr-1"></i><?= $i->hora ?></p>
                                            </div>
                                            <div class="action-btn">
                                                <button class="mr-1 btn btn-sm btn-soft-info"><i class="fas fa-pen"></i></button>
                                                <button class="btn btn-sm btn-soft-danger"><i class="far fa-trash-alt"></i></button>  
                                            </div>                                                                              
                                        </div>                                    
                                    </div><!--end card-body-->                 
                                </div><!--end card--> 
                            </div><!--end col-->
    

                            <?php endforeach; ?>
                            <?php endif; ?>
                    </div><!--end row--> 

                    <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                                 
                                            <table id="datatableCategorias" class="table" data-toggle="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">PERSONAL</th>
                                                        <th class="text-center">ENTRADA</th>
                                                        <th class="text-center">SALIADA</th>
                                             
                                                        <th class="text-center">ACCIONES</th>
                                                    </tr>
                                                    <!--end tr-->
                                                </thead>

                                                <tbody>
                                                    <?php foreach($usuario as $p): ?>
                                                        <tr>
                                                            <td class="text-center"><?= $p->nombre_completo ?></td>
                                                            <td class="text-center" style="cursor:pointer;"  >
                                                              <?= $p->hora_inicio ?>
                                                             </td>
                                                            <td class="text-center" style="cursor:pointer;" >
                                                                <?= $p->hora_fin ?> 
                                                             </td>                                                                                                     
                                                            <td class="text-center">
                                                                <button title="Imprimir Archivo"
                                                                    onclick="ini.inicio.editarPerfil(<?= $p->id_usuario ?>)"
                                                                    class="btn btn-gradient-warning px-4">
                                                                    <i class="dripicons-document-new font-21"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
 
