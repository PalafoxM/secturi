
    <!-- Page Content-->
     <?php   $session = \Config\Services::session(); ?>
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
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Mi espacio</a></li>
                                        <li class="breadcrumb-item active">MI perfil</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">MIS DATOS</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body  met-pro-bg">
                                    <div class="met-profile">
                                        <div class="row">
                                            <div class="col-lg-4 align-self-center mb-3 mb-lg-0">
                                                <div class="met-profile-main">
                                                    <div class="met-profile-main-pic">
                                                        <?php if(empty($datos->ruta_foto_relativa)): ?>
                                                            <img src="<?= base_url() ?>assets/images/users/patient-pro.png" alt="" style="width:125px; heigth: 125px;" class="rounded-circle">
                                                        <?php endif; ?>
                                                        <?php if(!empty($datos->ruta_foto_relativa)): ?>
                                                            <img src="<?= base_url().$datos->ruta_foto_relativa ?>" alt="" style="width:125px; heigth: 125px;" class="rounded-circle">
                                                        <?php endif; ?>
                                                        
                                                        <span class="fro-profile_main-pic-change" onclick="ini.inicio.abrirModalFoto();" >
                                                            <i class="fas fa-camera"></i>
                                                        </span>
                                                    </div>
                                                    <div class="met-profile_user-detail">
                                                        <h5 class="met-user-name"><?= $session->nombre_completo ?></h5>                                                        
                                                        <p class="mb-0 met-user-name-post"><?= $datos->dsc_perfil ?></p>
                                                    </div>
                                                </div>                                                
                                            </div><!--end col-->
                                            <div class="col-lg-4 ml-auto">
                                                <ul class="list-unstyled personal-detail">
                                                    <li class=""><i class="dripicons-user mr-2 text-info font-18"></i> <b> No. Empleado </b> : <?= (!empty($datos->no_empleado))?$datos->no_empleado:'' ?></li>
                                                    <li class="mt-2"><i class="dripicons-mail text-info font-18 mt-2 mr-2"></i> <b> Correo </b> : <?= (!empty($datos->correo))?$datos->correo:'' ?></li>
                                                    <li class="mt-2"><i class="dripicons-location text-info font-18 mt-2 mr-2"></i> <b>Ubicacion</b> : Silao, Gto</li>
                                                </ul>
                                                <div class="button-list btn-social-icon">                                                
                                                    <button type="button" class="btn btn-blue btn-circle">
                                                        <i class="fab fa-facebook-f"></i>
                                                    </button>
            
                                                    <button type="button" class="btn btn-secondary btn-circle ml-2">
                                                        <i class="fab fa-twitter"></i>
                                                    </button>
            
                                                    <button type="button" class="btn btn-pink btn-circle  ml-2">
                                                        <i class="fab fa-dribbble"></i>
                                                    </button>
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end row-->
                                    </div><!--end f_profile-->                                                                                
                                </div><!--end card-body-->
                                <div class="card-body">
                                    <ul class="nav nav-pills mb-0" id="pills-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="general_detail_tab" data-toggle="pill" href="#general_detail">General</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="activity_detail_tab" data-toggle="pill" href="#activity_detail">Actividad</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="portfolio_detail_tab" data-toggle="pill" href="#portfolio_detail">Portafolio</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="settings_detail_tab" data-toggle="pill" href="#settings_detail">Configuración</a>
                                        </li>
                                    </ul>        
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <div class="row">
                        <div class="col-12">
                            <div class="tab-content detail-list" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="general_detail">
                                    <div class="row">
                                        <div class="col-xl-4"> 
                                            
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class=" d-flex justify-content-between">
                                                        <img src="<?= base_url() ?>assets/images/widgets/monthly-re.png" alt="" height="75">
                                                        <div class="align-self-center">
                                                            <h2 class="mt-0 mb-2 font-weight-semibold">$955<span class="badge badge-soft-success font-11 ml-2"><i class="fas fa-arrow-up"></i> 8.6%</span></h2>
                                                            <h4 class="title-text mb-0">Monthly Revenue</h4>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between bg-light p-3 mt-3 rounded">
                                                        <div>
                                                            <h4 class="mb-1 font-weight-semibold">$10255</h4>
                                                            <p class="mb-0">Card Balance</p>
                                                        </div>
                                                        <div>
                                                            <h4 class=" mb-1 font-weight-semibold">25.12 <small>BTC</small></h4>
                                                            <p class="mb-0">Crypto Balance</p>
                                                        </div>
                                                    </div>                                    
                                                </div><!--end card-body-->
                                            </div><!--end card-->                        
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="header-title mt-0 mb-3">Compleaños del mes</h4>
                                                    <ul class="list-unsyled m-0 pl-0 transaction-history">
                                                      <?php foreach($personal as $k => $c):?>
                                                        <li class="align-items-center d-flex justify-content-between">
                                                            <div class="media">
                                                                <div class="transaction-icon">
                                                                  <?php if(!empty($c['ruta_foto_relativa'] )): ?>
                                                                    <img src="<?= base_url().$c['ruta_foto_relativa'] ?>" alt="" class="rounded-circle thumb-sm mr-1">
                                                                   <?php endif; ?>
                                                                    <?php if(empty($c['ruta_foto_relativa'] )): ?>
                                                                   <img src="<?= base_url() ?>assets/images/users/user-3.jpg" alt="" class="rounded-circle thumb-sm mr-1">
                                                                   <?php endif; ?>
                                                                </div>                                                
                                                                <div class="media-body align-self-center"> 
                                                                    <div class="transaction-data">                                                        
                                                                        <h3 class="m-0"><?= $c['nombre_completo']; ?></h3>
                                                                        <p class="text-muted mb-0">el <?= $c['dia']; ?></p>
                                                                    </div>                                                                                              
                                                                </div><!--end media body-->
                                                            </div>
                                                            <span class="text-success"><?= $c['edad']; ?> años</span>
                                                        </li>
                                                        <?php endforeach; ?>
                                                    </ul>                                       
                                                </div><!--end card-body-->
                                            </div><!--end card-->
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="dash-datepick">
                                                        <input type="hidden" id="light_datepick"/>
                                                    </div>
                                                    <div class="d-flex justify-content-between p-3 bg-light">
                                                        <div class="media">
                                                            <img src="<?= base_url() ?>assets/images/users/user-2.jpg" class="mr-3 thumb-md rounded-circle" alt="...">
                                                            <div class="media-body align-self-center">                                                           
                                                                <h5 class="mt-0 text-dark mb-1">Dias Festivos</h5>  
                                                                <p class="mb-0">Asuetos <span class="text-muted">SECTURI</span></p>                                                              
                                                            </div>
                                                        </div>
                                                        <span class="font-24 align-self-center">🎂</span>
                                                    </div>
                                                </div><!--end card-body-->                                                                                                  
                                            </div><!--end card-->
                                            
                                        </div><!--end col-->

                                        <div class="col-lg-8">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="card dash-data-card text-center">
                                                        <div class="card-body"> 
                                                            <div class="icon-info mb-3">
                                                                <i class="fas fa-ticket-alt bg-soft-warning"></i>
                                                            </div>
                                                            <h3 class="text-dark">184</h3>
                                                            <h6 class="font-14 text-dark">Tickets Nuevos</h6>                                                                                                                            
                                                        </div><!--end card-body--> 
                                                    </div><!--end card-->   
                                                </div><!-- end col-->
                                                <div class="col-lg-3">
                                                    <div class="card dash-data-card text-center">
                                                        <div class="card-body"> 
                                                            <div class="icon-info mb-3">
                                                                <i class="fab fa-codepen bg-soft-pink"></i>
                                                            </div>
                                                            <h3 class="text-dark">101</h3>
                                                            <h6 class="font-14 text-dark">Tickets En Proceso</h6>                                                                                                                            
                                                        </div><!--end card-body--> 
                                                    </div><!--end card-->   
                                                </div><!-- end col-->  
                                                <div class="col-lg-3">
                                                    <div class="card dash-data-card text-center">
                                                        <div class="card-body"> 
                                                            <div class="icon-info mb-3">
                                                                <i class="fas fa-check bg-soft-success"></i>
                                                            </div>
                                                            <h3 class="text-dark">18</h3>
                                                            <h6 class="font-14 text-dark">Tikets Hechos</h6>                                                                                                                            
                                                        </div><!--end card-body--> 
                                                    </div><!--end card-->   
                                                </div><!-- end col-->
                                                <div class="col-lg-3">
                                                    <div class="card dash-data-card text-center">
                                                        <div class="card-body"> 
                                                            <div class="icon-info mb-3">
                                                                <i class="fas fa-lock bg-soft-primary"></i>
                                                            </div>
                                                            <h3 class="text-danger">92</h3>
                                                            <h6 class="font-14 text-dark">Eliminados</h6>                                                                                                                            
                                                        </div><!--end card-body--> 
                                                    </div><!--end card-->   
                                                </div><!-- end col-->                       
                                            </div><!--end row-->
                                    
                                            
                                     
                                            <div class="card dash-info-carousel">
                                                <div class="card-body">
                                                    <div id="carousel_2" class="carousel slide" data-ride="carousel">
                                                        <div class="carousel-inner">
                                                             <?php foreach($lista_alba as $index => $l): ?>
                                                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                                                    <div class="media">
                                                                        <img src="<?= base_url().$l->foto; ?>" class="mr-3 thumb-xl align-self-center" alt="...">
                                                                        <div class="media-body align-self-center">                                                          
                                                                            <h4 class="mt-0 mb-1 title-text text-dark">
                                                                                <?= $l->nombre.' '.$l->primer_apellido.' '.$l->segundo_apellido; ?>
                                                                            </h4>
                                                                            <p class="text-muted mb-1"><?= $l->edad; ?> años</p>
                                                                            <p class="text-muted">Nacionalidad: <?= $l->nacionalidad; ?></p>
                                                                            <span class="px-2 py-1 bg-soft-pink d-inline-block">Desaparecida</span>
                                                                            <a target="_blank" href="<?= base_url().$l->protocolo; ?>" class="bg-soft-purple px-2 py-1">
                                                                                <i class="dripicons-preview"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                          
                                                        </div>
                                                        <a class="carousel-control-prev" href="#carousel_2" role="button" data-slide="prev">
                                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                        <a class="carousel-control-next" href="#carousel_2" role="button" data-slide="next">
                                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="row">
                                                 
                                                <div class="col-lg-6">
                                                    <div class="card">
                                                        <div class="card-body dash-info-carousel">
                                                            <h4 class="mt-0 header-title mb-4">Nuevas Norticias</h4>
                                                            <div id="carousel_1" class="carousel slide" data-ride="carousel">
                                                                <div class="carousel-inner">
                                                                    <div class="carousel-item">
                                                                        <div class="media">
                                                                            <img src="<?= base_url() ?>assets/images/users/user-1.jpg" class="mr-2 thumb-lg rounded-circle" alt="...">
                                                                            <div class="media-body align-self-center">                                                          
                                                                                <h4 class="mt-0 mb-1 title-text text-dark">Important Watch</h4>
                                                                                <p class="text-muted mb-0">Python Devloper</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="carousel-item">
                                                                        <div class="media">
                                                                            <img src="<?= base_url() ?>assets/images/users/user-2.jpg" class="mr-2 thumb-lg rounded-circle" alt="...">
                                                                            <div class="media-body align-self-center">                                                           
                                                                                <h4 class="mt-0 mb-1 title-text">Wireless Headphone</h4>
                                                                                <p class="text-muted mb-0">Python Devloper</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="carousel-item active">
                                                                        <div class="media">
                                                                            <img src="<?= base_url() ?>assets/images/users/user-3.jpg" class="mr-2 thumb-lg rounded-circle" alt="...">
                                                                            <div class="media-body align-self-center">                                                          
                                                                                <h4 class="mt-0 mb-1 title-text">Leather Bag</h4>
                                                                                <p class="text-muted mb-0">Python Devloper</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <a class="carousel-control-prev" href="#carousel_1" role="button" data-slide="prev">
                                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                    <span class="sr-only">Previous</span>
                                                                </a>
                                                                <a class="carousel-control-next" href="#carousel_1" role="button" data-slide="next">
                                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                    <span class="sr-only">Next</span>
                                                                </a>
                                                            </div>
                                                            <div class="row my-3">
                                                                <div class="col-sm-6">
                                                                    <p class="mb-0 text-muted font-13"><i class="mdi mdi-album mr-2 text-secondary"></i>New Leads</p>                            
                                                                </div><!-- end col-->
                                                                <div class="col-sm-6">
                                                                    <p class="mb-0 text-muted font-13"><i class="mdi mdi-album mr-2 text-warning"></i>New Leads Target</p>
                                                                </div><!-- end col-->
                                                            </div><!-- end row-->
                                                            <div class="progress bg-warning mb-3" style="height:5px;">
                                                                <div class="progress-bar bg-secondary" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>                                                            
                                                        </div><!--end card-body-->
                                                    </div><!--end card-->
                                                </div> <!--end col-->
                                                
                                                <div class="col-lg-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="p-4 bg-light text-center align-item-center">                                                                    
                                                                <h1 class="font-weight-semibold">4.8</h1> 
                                                                <h4 class="header-title">Overall Rating</h4>  
                                                                <ul class="list-inline mb-0 product-review">
                                                                    <li class="list-inline-item mr-0"><i class="mdi mdi-star text-warning font-24"></i></li>
                                                                    <li class="list-inline-item mr-0"><i class="mdi mdi-star text-warning font-24"></i></li>
                                                                    <li class="list-inline-item mr-0"><i class="mdi mdi-star text-warning font-24"></i></li>
                                                                    <li class="list-inline-item mr-0"><i class="mdi mdi-star text-warning font-24"></i></li>
                                                                    <li class="list-inline-item mr-0"><i class="mdi mdi-star-half text-warning font-24"></i></li>
                                                                    <li class="list-inline-item"><small class="text-muted">Total Review (700)</small></li>
                                                                </ul>                                     
                                                            </div> 
                                                        </div><!--end card-body-->                                                                                                  
                                                    </div><!--end card-->
                                                </div><!--end col--> 
                                            </div><!--end row-->                                          
                                        </div><!--end col-->
                                    </div><!--end row-->                                                                              
                                </div><!--end general detail-->

                                <div class="tab-pane fade" id="activity_detail">                                                
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="card">
                                                <div class="card-body"> 
                                                    <h4 class="header-title mt-0 mb-4">Latest Activity</h4>
                                                    <div class="slimscroll profile-activity-height">
                                                        <div class="activity">
                                                            <div class="activity-info">
                                                                <div class="icon-info-activity">
                                                                    <i class="mdi mdi-checkbox-marked-circle-outline bg-soft-success"></i>
                                                                </div>
                                                                <div class="activity-info-text">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <p class="text-muted mb-0 font-14 w-75"><span class="text-dark font-14">Donald</span> 
                                                                            updated the status of <a href="" class="text-dark">Refund #1234</a> to awaiting customer response
                                                                        </p>
                                                                        <span class="text-muted">10 Min ago</span>
                                                                    </div>    
                                                                </div>
                                                            </div>   
                
                                                            <div class="activity-info">
                                                                <div class="icon-info-activity">
                                                                    <i class="mdi mdi-timer-off bg-soft-pink"></i>
                                                                </div>
                                                                <div class="activity-info-text">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <p class="text-muted mb-0 font-14 w-75"><span class="text-dark font-14">Lucy Peterson</span> 
                                                                            was added to the group, group name is <a href="" class="text-dark">Overtake</a>
                                                                        </p>
                                                                        <span class="text-muted">50 Min ago</span>
                                                                    </div>    
                                                                </div>
                                                            </div>   
                
                                                            <div class="activity-info">
                                                                <div class="icon-info-activity">
                                                                    <i class="mdi mdi-alert-decagram bg-soft-purple"></i>
                                                                </div>
                                                                <div class="activity-info-text">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <p class="text-muted mb-0 font-14 w-75"><span class="text-dark font-14">Joseph Rust</span> 
                                                                            opened new showcase <a href="" class="text-dark">Mannat #112233</a> with theme market
                                                                        </p>
                                                                        <span class="text-muted">10 hours ago</span>
                                                                    </div>    
                                                                </div>
                                                            </div>   
                
                                                            <div class="activity-info">
                                                                <div class="icon-info-activity">
                                                                    <i class="mdi mdi-clipboard-alert bg-soft-warning"></i>
                                                                </div>
                                                                <div class="activity-info-text">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <p class="text-muted mb-0 font-14 w-75"><span class="text-dark font-14">Donald</span> 
                                                                            updated the status of <a href="" class="text-dark">Refund #1234</a> to awaiting customer response
                                                                        </p>
                                                                        <span class="text-muted">Yesterday</span>
                                                                    </div>    
                                                                </div>
                                                            </div>   
                                                            <div class="activity-info">
                                                                <div class="icon-info-activity">
                                                                    <i class="mdi mdi-clipboard-alert bg-soft-secondary"></i>
                                                                </div>
                                                                <div class="activity-info-text">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <p class="text-muted mb-0 font-14 w-75"><span class="text-dark font-14">Lucy Peterson</span> 
                                                                            was added to the group, group name is <a href="" class="text-dark">Overtake</a>
                                                                        </p>
                                                                        <span class="text-muted">14 Nov 2019</span>
                                                                    </div>    
                                                                </div>
                                                            </div> 
                                                            <div class="activity-info">
                                                                <div class="icon-info-activity">
                                                                    <i class="mdi mdi-clipboard-alert bg-soft-info"></i>
                                                                </div>
                                                                <div class="activity-info-text">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <p class="text-muted mb-0 font-14 w-75"><span class="text-dark font-14">Joseph Rust</span> 
                                                                            opened new showcase <a href="" class="text-dark">Mannat #112233</a> with theme market
                                                                        </p>
                                                                        <span class="text-muted">15 Nov 2019</span>
                                                                    </div>    
                                                                </div>
                                                            </div>                                                                                                                                      
                                                        </div><!--end activity-->
                                                    </div><!--crypot dash activity-->
                                                </div><!--end card-body-->
                                            </div><!--end card-->
                                        </div><!--end col-->

                                        <div class="col-lg-4">
                                            <div class="card">                                       
                                                <div class="card-body"> 
                                                    <h4 class="header-title mt-0 mb-3">Sales Category</h4>
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <img src="<?= base_url() ?>assets/images/widgets/sales-re.svg" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="col-8 align-self-center">
                                                            <p class="skill-detail">Contrary to popular belief, Lorem Ipsum is not simply random text. 
                                                                It has roots in a piece of classical Latin literature from 45 BC, 
                                                                making it over 2000 years old. Richard McClintock, a Latin professor.
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="text-right mt-3">
                                                        <span class="bg-light p-2 rounded">Last Update : 2 hours</span>
                                                    </div>
                                                    
                                                    <div class="skills mt-4">
                                                        <div class="skill-box"> 
                                                            <h4 class="skill-title">Electronic</h4> 
                                                            <div class="progress-line"> 
                                                                <span data-percent="78" style="width: 78%;">
                                                                    <span class="percent-tooltip">78%</span>
                                                                </span> 
                                                            </div>
                                                        </div>
                                                        <div class="skill-box"> 
                                                            <h4 class="skill-title">Clothes</h4> 
                                                            <div class="progress-line"> 
                                                                <span data-percent="90" style="width: 90%;">
                                                                    <span class="percent-tooltip">90%</span>
                                                                </span> 
                                                            </div>
                                                        </div>
                                                        <div class="skill-box"> 
                                                            <h4 class="skill-title">Phones</h4> 
                                                            <div class="progress-line"> 
                                                                <span data-percent="80" style="width: 80%;">
                                                                    <span class="percent-tooltip">80%</span>
                                                                </span> 
                                                            </div>
                                                        </div>
                                                        <div class="skill-box"> 
                                                            <h4 class="skill-title">Medicine</h4> 
                                                            <div class="progress-line"> 
                                                                <span data-percent="95" style="width: 95%;">
                                                                    <span class="percent-tooltip">95%</span>
                                                                </span> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  <!--end card-body-->                                     
                                            </div><!--end card-->
                                        </div><!--end col-->
                                        <div class="col-lg-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="blog-card">
                                                        <img src="<?= base_url() ?>assets/images/small/img-12.jpg" alt="" class="img-fluid">
                                                        <span class="badge badge-purple px-3 py-2 bg-soft-secondary font-weight-semibold mt-3">Food</span>   
                                                        <h4 class="my-3">
                                                            <a href="" class="">It is a long established fact that a reader will be</a>
                                                        </h4>
                                                        <p class="text-muted text-truncate">The standard chunk of Lorem Ipsum used since the for those interested.</p>
                                                        <hr class="hr-dashed">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="meta-box">
                                                                <div class="media">
                                                                    <img src="<?= base_url() ?>assets/images/users/user-1.png" alt="" class="thumb-sm rounded-circle mr-2">                                       
                                                                    <div class="media-body align-self-center text-truncate">
                                                                        <h6 class="mt-0 mb-1 text-dark">Donald Gardner</h6>
                                                                        <ul class="p-0 list-inline mb-0">
                                                                            <li class="list-inline-item">26 mars 2020</li>
                                                                            <li class="list-inline-item">by <a href="">admin</a></li>
                                                                        </ul>
                                                                    </div><!--end media-body-->
                                                                </div>                                            
                                                            </div><!--end meta-box-->
                                                            <div class="align-self-center">
                                                                <a href="#" class="text-primary">Read more <i class="fas fa-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>                                        
                                                    </div><!--end blog-card-->                                                                               
                                                </div><!--end card-body-->
                                            </div><!--end card-->
                                        </div><!--end col-->
                                    </div><!--end row-->  
                                </div><!--end education detail-->

                                <div class="tab-pane fade" id="portfolio_detail">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <ul class="col container-filter categories-filter mb-0" id="filter">
                                                            <li><a class="categories active" data-filter="*">All</a></li>
                                                            <li><a class="categories" data-filter=".branding">Branding</a></li>
                                                            <li><a class="categories" data-filter=".design">Design</a></li>
                                                            <li><a class="categories" data-filter=".photo">Photo</a></li>
                                                            <li><a class="categories" data-filter=".coffee">coffee</a></li>
                                                        </ul>
                                                    </div><!-- End portfolio  -->
                                                </div><!--end card-body-->
                                            </div><!--end card-->
                                            
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row container-grid nf-col-3  projects-wrapper">
                                                        <div class="col-lg-4 col-md-6 p-0 nf-item branding design coffee spacing">
                                                            <div class="item-box">
                                                                <a class="cbox-gallary1 mfp-image" href="<?= base_url() ?>assets/images/small/img-1.jpg" title="Consequat massa quis">
                                                                    <img class="item-container " src="<?= base_url() ?>assets/images/small/img-1.jpg" alt="7" />
                                                                    <div class="item-mask">
                                                                        <div class="item-caption">
                                                                            <h5 class="text-white">Consequat massa quis</h5>
                                                                            <p class="text-white">Branding, Design, Coffee</p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div><!--end item-box-->
                                                        </div><!--end col-->
                                        
                                                        <div class="col-lg-4 col-md-6 p-0 nf-item photo spacing">
                                                            <div class="item-box">
                                                                <a class="cbox-gallary1 mfp-image" href="<?= base_url() ?>assets/images/small/img-2.jpg" title="Vivamus elementum semper">
                                                                    <img class="item-container mfp-fade" src="<?= base_url() ?>assets/images/small/img-2.jpg" alt="2" />
                                                                    <div class="item-mask">
                                                                        <div class="item-caption">
                                                                            <h5 class="text-light">Vivamus elementum semper</h5>
                                                                            <p class="text-light">Photo</p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div><!--end item-box-->
                                                        </div><!--end col-->
                                        
                                                        <div class="col-lg-4 col-md-6 p-0 nf-item branding coffee spacing">
                                                            <div class="item-box">
                                                                <a class="cbox-gallary1 mfp-image" href="<?= base_url() ?>assets/images/small/img-3.jpg" title="Quisque rutrum">
                                                                    <img class="item-container" src="<?= base_url() ?>assets/images/small/img-3.jpg" alt="4" />
                                                                    <div class="item-mask">
                                                                        <div class="item-caption">
                                                                            <h5 class="text-light">Quisque rutrum</h5>
                                                                            <p class="text-light">Branding, Design, Coffee</p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div><!--end item-box-->
                                                        </div><!--end col-->
                                        
                                                        <div class="col-lg-4 col-md-6 p-0 nf-item branding design spacing">
                                                            <div class="item-box">
                                                                <a class="cbox-gallary1 mfp-image" href="<?= base_url() ?>assets/images/small/img-4.jpg" title="Tellus eget condimentum">
                                                                    <img class="item-container" src="<?= base_url() ?>assets/images/small/img-4.jpg" alt="5" />
                                                                    <div class="item-mask">
                                                                        <div class="item-caption">
                                                                            <h5 class="text-light">Tellus eget condimentum</h5>
                                                                            <p class="text-light">Design</p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div><!--end item-box-->
                                                        </div><!--end col-->
                                        
                                                        <div class="col-lg-4 col-md-6 p-0 nf-item branding design spacing">
                                                            <div class="item-box">
                                                                <a class="cbox-gallary1 mfp-image" href="<?= base_url() ?>assets/images/small/img-5.jpg" title="Nullam quis ant">
                                                                    <img class="item-container" src="<?= base_url() ?>assets/images/small/img-5.jpg" alt="6" />
                                                                    <div class="item-mask">
                                                                        <div class="item-caption">
                                                                            <h5 class="text-light">Nullam quis ant</h5>
                                                                            <p class="text-light">Branding, Design</p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div><!--end item-box-->
                                                        </div><!--end col-->
                                        
                                                        <div class="col-lg-4 col-md-6 p-0 nf-item photo spacing">
                                                            <div class="item-box">
                                                                <a class="cbox-gallary1 mfp-image" href="<?= base_url() ?>assets/images/small/img-6.jpg" title="Sed fringilla mauris">
                                                                    <img class="item-container" src="<?= base_url() ?>assets/images/small/img-6.jpg" alt="1" />
                                                                    <div class="item-mask">
                                                                        <div class="item-caption">
                                                                            <h5 class="text-light">Sed fringilla mauris</h5>
                                                                            <p class="text-light">Photo</p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div><!--end item-box-->
                                                        </div><!--end col-->
                                                    </div><!--end row-->
                                                </div><!--end card-body-->
                                            </div><!--end card-->
                                        </div><!--end col-->
                                        <div class="col-lg-4">
                                            <div class="card ">
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <h4><i class="fas fa-quote-left text-primary"></i></h4>
                                                    </div>                                            
                                                    <div id="carouselExampleFade2" class="carousel slide" data-ride="carousel">
                                                        <div class="carousel-inner">
                                                            <div class="carousel-item">
                                                                <div class="text-center">
                                                                    <p class="text-muted px-4">
                                                                        It is a long established fact that a reader will be distracted by 
                                                                        the readable content of a page when looking at its layout. 
                                                                        The point of using Lorem Ipsum is that it has a more-or-less 
                                                                        normal distribution of letters, as opposed to using.
                                                                    </p>
                                                                    <div class="">
                                                                        <img src="<?= base_url() ?>assets/images/users/user-10.jpg" alt="" class="rounded-circle thumb-lg mb-2">
                                                                        <p class="mb-0 text-primary"><b>- Mary K. Myers</b></p>
                                                                        <small class="text-muted">CEO Facebook</small>
                                                                    </div>                                                            
                                                                </div>
                                                            </div>
                                                            <div class="carousel-item active">
                                                                <div class="text-center">
                                                                    <p class="text-muted px-4">                                                                
                                                                        Where does it come from?
                                                                        Contrary to popular belief, Lorem Ipsum is not simply random text. 
                                                                        It has roots in a piece of classical Latin literature from 45 BC, 
                                                                        making it over 2000 years  popular belief,old.
                                                                    </p>
                                                                    <div class="">
                                                                        <img src="<?= base_url() ?>assets/images/users/user-4.jpg" alt="" class="rounded-circle  thumb-lg mb-2">
                                                                        <p class="mb-0 text-primary"><b>- Michael C. Rios</b></p>
                                                                        <small class="text-muted">CEO Facebook</small>
                                                                    </div>                                                            
                                                                </div>
                                                            </div>
                                                            <div class="carousel-item">
                                                                <div class="text-center">
                                                                    <p class="text-muted px-4">
                                                                        There are many variations of passages of Lorem Ipsum available, 
                                                                        but the majority have suffered alteration in some form, by injected humour, 
                                                                        or randomised words which don't look even slightly believable. 
                                                                        If you are going to use a passage of Lorem Ipsum. 
                                                                    </p>
                                                                    <div class="">
                                                                        <img src="<?= base_url() ?>assets/images/users/user-5.jpg" alt="" class="rounded-circle  thumb-lg mb-2">
                                                                        <p class="mb-0 text-primary"><b>- Lisa D. Pullen</b></p>
                                                                        <small class="text-muted">CEO Facebook</small>
                                                                    </div>                                                            
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end row-->
                                </div><!--end portfolio detail-->
                                
                                <div class="tab-pane fade" id="settings_detail">
                                    <div class="row">
                                        <div class="col-lg-12 col-xl-9 mx-auto">
                                            <div class="card">
                                                <div class="card-body">
                                                    
        
                                                    <div class="">
                                                        <form class="form-horizontal form-material mb-0">
                                                            <h3>Información Visible en SUSI<h3>
                                                            
                                                            <div class="form-group row">
                                                                <div class="col-md-4">
                                                                    <input type="checkbox" class="checkbox checkbox-primary" name="fec_nac" id="fec_nac" checked>
                                                                     <label for="fec_nac">Fecha de Nacimiento</label>
                                                                </div>
                                                                 <div class="col-md-4">
                                                                    <input type="checkbox" class="checkbox checkbox-primary" name="edad" id="edad" checked>
                                                                     <label for="edad">Edad</label>
                                                                </div>
                                                                 <div class="col-md-4">
                                                                    <input type="checkbox" class="checkbox checkbox-primary" name="nivel" id="nivel" checked>
                                                                     <label for="nivel">Nivel</label>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="checkbox" class="checkbox checkbox-primary" name="no_empleado" id="no_empleado" checked>
                                                                     <label for="no_empleado">Numero de Empleado</label>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="checkbox" class="checkbox checkbox-primary" name="sexo" id="sexo" checked>
                                                                     <label for="sexo">Sexo</label>
                                                                </div>
                                                                 <div class="col-md-4">
                                                                    <input type="checkbox" class="checkbox checkbox-primary" name="sexo" id="sexo" checked>
                                                                     <label for="sexo">Tipo de Contrato</label>
                                                                </div>
                                                            </div>
                                                         
                                                            <div class="form-group">
                                                                <textarea rows="5" placeholder="Message" class="form-control"></textarea>
                                                                <button class="btn btn-gradient-primary btn-sm px-4 mt-3 float-right mb-0">Guardar Configuración</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>                                            
                                            </div>
                                        </div> <!--end col-->                                          
                                    </div><!--end row-->
                                </div><!--end settings detail-->
                            </div><!--end tab-content--> 
                            
                        </div><!--end col-->
                    </div><!--end row-->

                </div><!-- container -->

                <!--  Modal content for the above example -->
                <div class="modal modal-rightbar fade" tabindex="-1" role="dialog" aria-labelledby="MetricaRightbar" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title mt-0" id="MetricaRightbar">Appearance</h5>
                                <button type="button" class="btn btn-sm btn-soft-primary btn-circle btn-square" data-dismiss="modal" aria-hidden="true"><i class="mdi mdi-close"></i></button>
                            </div>
                            <div class="modal-body">                                
                               <!-- Nav tabs -->
                               <ul class="nav nav-pills nav-justified mt-2 mb-4" role="tablist">
                                    <li class="nav-item waves-effect waves-light">
                                        <a class="nav-link active" data-toggle="tab" href="#ActivityTab" role="tab">Activity</a>
                                    </li>
                                    <li class="nav-item waves-effect waves-light">
                                        <a class="nav-link" data-toggle="tab" href="#TasksTab" role="tab">Tasks</a>
                                    </li>
                                    <li class="nav-item waves-effect waves-light">
                                        <a class="nav-link" data-toggle="tab" href="#SettingsTab" role="tab">Configuración</a>
                                    </li>
                                </ul>                                
                                
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active " id="ActivityTab" role="tabpanel">
                                        <div class="bg-light mx-n3">
                                            <img src="<?= base_url() ?>assets/images/small/img-1.gif" alt="" class="d-block mx-auto my-4" height="180">
                                        </div>
                                        <div class="slimscroll scroll-rightbar">
                                            <div class="activity">
                                                <div class="activity-info">
                                                    <div class="icon-info-activity">
                                                        <i class="mdi mdi-checkbox-marked-circle-outline bg-soft-success"></i>
                                                    </div>
                                                    <div class="activity-info-text mb-2">
                                                        <div class="mb-1">
                                                            <small class="text-muted d-block mb-1">10 Min ago</small>
                                                            <a href="#" class="m-0 w-75">Task finished</a>                                                            
                                                        </div>
                                                        <p class="text-muted mb-2 text-truncate">There are many variations of passages.</p>
                                                    </div>
                                                </div> 
    
                                                <div class="activity-info">
                                                    <div class="icon-info-activity">
                                                        <i class="mdi mdi-timer-off bg-soft-pink"></i>
                                                    </div>
                                                    <div class="activity-info-text mb-2">
                                                        <div class="mb-1">
                                                            <small class="text-muted d-block mb-1">50 Min ago</small>
                                                            <a href="#" class="m-0 w-75">Task Overdue</a>                                                            
                                                        </div>
                                                        <p class="text-muted mb-2 text-truncate">There are many variations of passages.</p>
                                                        <span class="badge badge-soft-secondary">Design</span>
                                                        <span class="badge badge-soft-secondary">HTML</span>
                                                    </div>                                                   
                                                </div>
                                                <div class="activity-info">
                                                    <div class="icon-info-activity">
                                                        <i class="mdi mdi-alert-decagram bg-soft-purple"></i>
                                                    </div>
                                                    <div class="activity-info-text mb-2">
                                                        <div class="mb-1">
                                                            <small class="text-muted d-block mb-1">10 hours ago</small>
                                                            <a href="#" class="m-0 w-75">New Task</a>                                                            
                                                        </div>
                                                        <p class="text-muted mb-2 text-truncate">There are many variations of passages.</p>
                                                    </div>        
                                                </div>   
    
                                                <div class="activity-info">
                                                    <div class="icon-info-activity">
                                                        <i class="mdi mdi-clipboard-alert bg-soft-warning"></i>
                                                    </div>
                                                    <div class="activity-info-text mb-2">
                                                        <div class="mb-1">
                                                            <small class="text-muted d-block mb-1">yesterday</small>
                                                            <a href="#" class="m-0 w-75">New Comment</a>                                                            
                                                        </div>
                                                        <p class="text-muted mb-2 text-truncate">There are many variations of passages.</p>
                                                    </div>    
                                                </div>  
                                                <div class="activity-info">
                                                    <div class="icon-info-activity">
                                                        <i class="mdi mdi-clipboard-alert bg-soft-secondary"></i>
                                                    </div>
                                                    <div class="activity-info-text mb-2">
                                                        <div class="mb-1">
                                                            <small class="text-muted d-block mb-1">01 feb 2020</small>
                                                            <a href="#" class="m-0 w-75">New Lead Meting</a>                                                            
                                                        </div>
                                                        <p class="text-muted mb-2 text-truncate">There are many variations of passages.</p>
                                                    </div>    
                                                </div>   
                                                <div class="activity-info">
                                                    <div class="icon-info-activity">
                                                        <i class="mdi mdi-checkbox-marked-circle-outline bg-soft-success"></i>
                                                    </div>
                                                    <div class="activity-info-text mb-2">
                                                        <div class="mb-1">
                                                            <small class="text-muted d-block mb-1">26 jan 2020</small>
                                                            <a href="#" class="m-0 w-75">Task finished</a>                                                            
                                                        </div>
                                                        <p class="text-muted mb-2 text-truncate">There are many variations of passages.</p>
                                                    </div>
                                                </div>                                                                                                            
                                            </div><!--end activity-->
                                        </div><!--end activity-scroll-->
                                    </div><!--end tab-pane-->
                                    <div class="tab-pane" id="TasksTab" role="tabpanel">
                                        <div class="m-0">
                                            <div id="rightbar_chart" class="apex-charts"></div>                                            
                                        </div>  
                                        <div class="text-center mt-n2 mb-2">
                                            <button type="button" class="btn btn-soft-primary">Create Project</button>
                                            <button type="button" class="btn btn-soft-primary">Create Task</button>
                                        </div>
                                        <div class="slimscroll scroll-rightbar">
                                            <div class="p-2">
                                                <div class="media mb-3">
                                                    <img src="<?= base_url() ?>assets/images/widgets/project3.jpg" alt="" class="thumb-lg rounded-circle">                                      
                                                    <div class="media-body align-self-center text-truncate ml-3">
                                                        <p class="text-success font-weight-semibold mb-0 font-14">Project</p>
                                                        <h4 class="mt-0 mb-0 font-weight-semibold text-dark font-18">Payment App</h4>                                            
                                                    </div><!--end media-body-->
                                                </div>
                                                <span><b>Deadline:</b> 02 June 2020</span>
                                                <a href="javascript: void(0);" class="d-block mt-3">
                                                    <p class="text-muted mb-0">Complete Tasks<span class="float-right">75%</span></p>
                                                    <div class="progress mt-2" style="height: 4px;">
                                                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </a>                                            
                                            </div>
                                            <hr class="hr-dashed">                                            
                                        </div>                                        
                                    </div><!--end tab-pane-->
                                    <div class="tab-pane" id="SettingsTab" role="tabpanel">
                                        <div class="p-1 bg-light mx-n3">
                                            <h6 class="px-3">Account Settings</h6>
                                        </div>
                                        <div class="p-2 text-left mt-3">
                                            <div class="custom-control custom-switch switch-primary mb-3">
                                                <input type="checkbox" class="custom-control-input" id="settings-switch1" checked="">
                                                <label class="custom-control-label" for="settings-switch1">Auto updates</label>
                                            </div>

                                            <div class="custom-control custom-switch switch-primary mb-3">
                                                <input type="checkbox" class="custom-control-input" id="settings-switch2">
                                                <label class="custom-control-label" for="settings-switch2">Location Permission</label>
                                            </div>

                                            <div class="custom-control custom-switch switch-primary mb-3">
                                                <input type="checkbox" class="custom-control-input" id="settings-switch3" checked="">
                                                <label class="custom-control-label" for="settings-switch3">Show offline Contacts</label>
                                            </div>    
                                        </div>
                                        <div class="p-1 bg-light mx-n3">
                                            <h6 class="px-3">General Settings</h6>
                                        </div>
                                        <div class="p-2 text-left mt-3">
                                            <div class="custom-control custom-switch switch-primary mb-3">
                                                <input type="checkbox" class="custom-control-input" id="settings-switch4" checked="">
                                                <label class="custom-control-label" for="settings-switch4">Show me Online</label>
                                            </div>

                                            <div class="custom-control custom-switch switch-primary mb-3">
                                                <input type="checkbox" class="custom-control-input" id="settings-switch5">
                                                <label class="custom-control-label" for="settings-switch5">Status visible to all</label>
                                            </div>

                                            <div class="custom-control custom-switch switch-primary mb-3">
                                                <input type="checkbox" class="custom-control-input" id="settings-switch6" checked="">
                                                <label class="custom-control-label" for="settings-switch6">Notifications Popup</label>
                                            </div> 
                                        </div>
                                    </div><!--end tab-pane-->
                                </div> <!--end tab-content--> 
                            </div><!--end modal-body-->
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal --> 
            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->
<div class="modal fade" id="modalFoto" tabindex="-1" aria-labelledby="modalEliminarReservaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalEliminarReservaLabel">Agregar Imagen</h5>
        <button type="button" onclick="ini.inicio.cerrarModalFoto()" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
        <div class="modal-body">
         <label for="foto">Agregar Nueva Foto en JPG o PNG</label>
         <input type="file" class="form-control" id="foto" name="foto" accept=".png">
        </div>
      <div class="modal-footer">
        <button type="button" onclick="ini.inicio.cerrarModalFoto()" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" onclick="guardarFoto();" class="btn btn-primary" >Guardar</button>
      </div>
    </div>
  </div>
</div>

    <link href="<?= base_url() ?>plugins/dropify/css/dropify.min.css" rel="stylesheet">
        <link href="<?= base_url() ?>plugins/filter/magnific-popup.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>plugins/lightpick/lightpick.css" rel="stylesheet" />

        <!-- App css -->
        <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/jquery-ui.min.css" rel="stylesheet">
        <link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

        <!-- jQuery  -->
        <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
        <script src="<?= base_url() ?>assets/js/jquery-ui.min.js"></script>
        <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url() ?>assets/js/metismenu.min.js"></script>
        <script src="<?= base_url() ?>assets/js/waves.js"></script>
        <script src="<?= base_url() ?>assets/js/feather.min.js"></script>
        <script src="<?= base_url() ?>assets/js/jquery.slimscroll.min.js"></script>
        <script src="<?= base_url() ?>plugins/apexcharts/apexcharts.min.js"></script> 

        <script src="<?= base_url() ?>plugins/dropify/js/dropify.min.js"></script>
        <script src="<?= base_url() ?>plugins/moment/moment.js"></script>
        <script src="<?= base_url() ?>plugins/filter/isotope.pkgd.min.js"></script>
        <script src="<?= base_url() ?>plugins/filter/masonry.pkgd.min.js"></script>
        <script src="<?= base_url() ?>plugins/filter/jquery.magnific-popup.min.js"></script>
        <script src="<?= base_url() ?>plugins/chartjs/chart.min.js"></script>
        <script src="<?= base_url() ?>plugins/chartjs/roundedBar.min.js"></script>
        <script src="<?= base_url() ?>plugins/lightpick/lightpick.js"></script>
        <script src="<?= base_url() ?>assets/pages/jquery.profile.init.js"></script>
        
        <!-- App js -->
        <script src="<?= base_url() ?>assets/js/app.js"></script>

<script>
function guardarFoto() {
    // Usar JavaScript puro para evitar problemas con jQuery
    const fotoInput = document.getElementById('foto');
      console.log(fotoInput );
    if (!fotoInput) {
        Swal.fire("Error", "No se encontró el campo de imagen", "error");
        return;
    }
  
    if (!fotoInput.files || fotoInput.files.length === 0) {
        Swal.fire("Atención", "Es requerida la imagen", "info");
        return;
    }
    
    let foto = fotoInput.files[0];
    let formData = new FormData();
    formData.append('foto', foto);
    
    $.ajax({
        url: base_url + "index.php/Principal/guardarFoto",
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if(!response.error){
                Swal.fire("Correcto", response.respuesta, "success");
                setTimeout(() => {
                    window.location.reload(); 
                }, 1000);
            } else {
                Swal.fire("Error", "Favor de llamar al Administrador", "error");
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
            Swal.fire("Error", "Favor de llamar al Administrador", "error");
        }
    });
}
</script>


