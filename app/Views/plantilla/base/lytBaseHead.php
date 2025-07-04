<?php $session     = \Config\Services::session();?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>SUSI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistema de Administración de Capacitación" name="description" />
    <meta content="SECTURI" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.ico">

    <!-- jvectormap -->
    <link href="<?php echo base_url(); ?>plugins/jvectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet">

    <!-- App css -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

    <?php if (isset($scripts)): foreach ($scripts as $js): ?>
    <script src="<?php echo base_url() . "/js/{$js}.js" ?>?filever=<?php echo time() ?>" type="text/javascript">
    </script>
    <?php endforeach;
            endif;
        ?>

</head>
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #4895ef;
            --urgent: #f72585;
            --warning: #ff9e00;
            --success: #4cc9f0;
            --success-dark: #38b6db;
            --bg: #f8f9fa;
            --text: #2b2d42;
        }
        
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        
        .chat-container {
            padding: 20px;
            height: 500px;
            overflow-y: auto;
            background: #fafafa;
        }
        
        .message {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .bot-message .avatar {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
        }
        
        .user-message {
            justify-content: flex-end;
        }
        
        .user-message .content {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border-radius: 15px 15px 0 15px;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .content {
            max-width: 70%;
            padding: 12px 15px;
            border-radius: 15px 15px 15px 0;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .option-btn {
            background: white;
            border: none;
            padding: 10px 15px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        /* Colores por categoría */
        .asistencia-btn { background: #ffddd2; color: #e29578; }
        .accesorios-btn { background: #e2f0cb; color: #83c5be; }
        .plataformas-btn { background: #dfe7fd; color: #5a7bd8; }
        .impresoras-btn { background: #ffeedd; color: #ff9f1c; }
        .otro-btn { background: #f8d7ff; color: #9b5de5; }
        
        .option-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.2);
        }
        
        .back-btn {
            background: #f1f1f1;
            border: none;
            padding: 10px 15px;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 10px 0;
            width: 100%;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: #e0e0e0;
        }
        
        .text-input-container {
            margin-top: 15px;
            display: none;
        }
        
        .text-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-family: inherit;
            resize: none;
            min-height: 80px;
            margin-bottom: 10px;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, var(--success), var(--success-dark));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
            transition: all 0.3s;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
        }
        
        .confirmation {
            background: linear-gradient(135deg, var(--success), var(--success-dark));
            color: white;
            padding: 15px;
            border-radius: 12px;
            margin-top: 15px;
            text-align: center;
            display: none;
            animation: bounceIn 0.5s;
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0.9); opacity: 0; }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .ticket-number {
            font-size: 1.3em;
            font-weight: bold;
            margin: 5px 0;
            display: inline-block;
            padding: 5px 10px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
        }
    </style>

<body>
    <script>
    var base_url = "<?php echo base_url();?>";
    var token = "<?php echo env('TOKEN_API'); ?>";
    var api = "<?php echo env('NODE_API_CURP'); ?>";
    </script>

    <!-- leftbar-tab-menu -->
    <div class="leftbar-tab-menu">
        <div class="main-icon-menu">
            <a href="<?php echo base_url(); ?>analytics/analytics-index.html"
                class="logo logo-metrica d-block text-center">
                <span>
                    <img src="<?php echo base_url(); ?>assets/images/logo-sm.png" alt="logo-small" class="logo-sm">
                </span>
            </a>
           <nav class="nav">
                     <?php if($session->id_perfil == 1): ?>       
                    <a href="#MetricaDashboard" class="nav-link" data-toggle="tooltip-custom" data-placement="right"  data-trigger="hover" title="" data-original-title="Dashboard">
                        <i data-feather="monitor" class="align-self-center menu-icon icon-dual"></i>
                    </a><!--end MetricaDashboards--> 
                    <?php endif;?>
                    <?php if(in_array($session->id_perfil, [1,2])): ?>  
                    <a href="#MetricaApps" class="nav-link" data-toggle="tooltip-custom" data-placement="right"  data-trigger="hover" title="Pagos" data-original-title="Apps">
                        <i data-feather="dollar-sign" class="align-self-center menu-icon icon-dual"></i>
                    </a><!--end MetricaApps-->
                     <?php endif;?>
                    <?php if($session->id_perfil == 1): ?>   
                    <a href="#MetricaUikit" class="nav-link" data-toggle="tooltip-custom" data-placement="right"  data-trigger="hover" title="" data-original-title="UI Kit">
                        <i data-feather="user" class="align-self-center menu-icon icon-dual"></i>
                    </a><!--end MetricaUikit-->
                    <?php endif;?>
                    <a href="#MetricaPages" class="nav-link" data-toggle="tooltip-custom" data-placement="right"  data-trigger="hover" title="" data-original-title="Pages">
                        <i data-feather="copy" class="align-self-center menu-icon icon-dual"></i>             
                    </a><!--end MetricaPages-->
                    
                    <a href="#MetricaAuthentication" class="nav-link" data-toggle="tooltip-custom" data-placement="right"  data-trigger="hover" title="" data-original-title="Authentication">
                        <i data-feather="lock" class="align-self-center menu-icon icon-dual"></i>
                    </a> <!--end MetricaAuthentication--> 
                   

            </nav><!--end nav-->
            <!--end nav-->
            <div class="pro-metrica-end">
               <a style="cursor: pointer;" class="help" data-toggle="tooltip-custom" data-placement="top" title="Subir un Tiket" onclick="ini.inicio.openSupportModal()">
                    <i data-feather="message-circle" class="align-self-center menu-icon icon-md icon-dual mb-4"></i>
                </a>
                <a href="" class="profile">
                    <img src="<?php echo base_url(); ?>assets/images/users/user-4.jpg" alt="profile-user"
                        class="rounded-circle thumb-sm">
                </a>
            </div>
        </div>
        <!--end main-icon-menu-->

        <div class="main-menu-inner">
            <!-- LOGO -->
            <div class="topbar-left">
                <a href="<?php echo base_url(); ?>index.php/Inicio" class="logo">
                    <h2>SUSI</h2>
                </a>
            </div>
            <!--end logo-->
            <div class="menu-body slimscroll">
                    <div id="MetricaDashboard" class="main-icon-menu-pane">
                        <div class="title-box">
                            <h6 class="menu-title">Dashboard</h6>       
                        </div>
                        <ul class="nav">
                            <li class="nav-item"><a class="nav-link" href="../analytics/analytics-index.html">Analytic</a></li>
                            <li class="nav-item"><a class="nav-link" href="../crypto/crypto-index.html">Crypto</a></li>
                            <li class="nav-item"><a class="nav-link" href="../crm/crm-index.html">CRM</a></li>
                            <li class="nav-item"><a class="nav-link" href="../projects/projects-index.html">Project</a></li> 
                            <li class="nav-item"><a class="nav-link" href="../ecommerce/ecommerce-index.html">Ecommerce</a></li>
                            <li class="nav-item"><a class="nav-link" href="../helpdesk/helpdesk-index.html">Helpdesk</a></li>
                            <li class="nav-item"><a class="nav-link" href="../hospital/hospital-index.html">Hospital</a></li>
                        </ul>
                    </div><!-- end Dashboards -->    

                 <div id="MetricaApps" class="main-icon-menu-pane">
                        <div class="title-box">
                            <h6 class="menu-title">Recursos</h6>
                        </div>
                        <ul class="nav metismenu">
                            <li class="nav-item">
                                <a class="nav-link" href="javascript: void(0);"><span class="w-100">Financieros</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="<?php echo base_url(); ?>index.php/Principal/listadoPT">Reserva PT</a></li>
                                    <li><a href="../apps/email-read.html">Solicitud GRC</a></li>            
                                    <li><a href="../apps/email-read.html">Solicitud GO</a></li>            
                                </ul>            
                            </li><!--end nav-item-->
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Principal/listadoEstatusPT">Listado PT</a></li>
                            <li class="nav-item"><a class="nav-link" href="../apps/contact-list.html">Listado GRC</a></li>
                            <li class="nav-item"><a class="nav-link" href="../apps/calendar.html">Listado GO</a></li>   
                            <li class="nav-item"><a class="nav-link" href="../apps/invoice.html" aria-expanded="false">Mesa RF</a></li>                        
                        </ul>
                    </div><!-- end Crypto -->
        
               
                  <div id="MetricaUikit" class="main-icon-menu-pane">
                        <div class="title-box">
                            <h6 class="menu-title">ADMIN</h6>      
                        </div>
                        <ul class="nav metismenu">                                
                            <li class="nav-item">
                                <a class="nav-link" href="#"><span class="w-100">Admin TI</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/usuarios">Lista de Usuarios</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/altaUsuario">Alta de Usuarios</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/listaPerfil">Lista de Perfiles</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/listaPuesto">Lista de Puestos</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/listaArea">Lista de Área</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/listaTiket">Listado de Tikets</a></li>
                                    <li><a href="../others/ui-dragula.html">Dragula</a></li>
                                    <li><a href="../others/ui-check-radio.html">Check & Radio</a></li>
                                    <li><a href="../others/advanced-session.html">Session Timeout</a></li>
                                    <li><a href="../others/advanced-idle-timer.html">Idle Timer</a></li>
                                </ul>            
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="#"><span class="w-100">Admin RH</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/subirAsistencia">Asistencias</a></li>
                                    <li><a href="../others/forms-advanced.html">Advance Elements</a></li>
                                    <li><a href="../others/forms-validation.html">Validation</a></li>
                                    <li><a href="../others/forms-wizard.html">Wizard</a></li>
                                    <li><a href="../others/forms-editors.html">Editors</a></li>
                                    <li><a href="../others/forms-repeater.html">Repeater</a></li>
                                    <li><a href="../others/forms-x-editable.html">X Editable</a></li>
                                    <li><a href="../others/forms-uploads.html">File Upload</a></li>
                                    <li><a href="../others/forms-img-crop.html">Image Crop</a></li>
                                </ul>            
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="#"><span class="w-100">Admin RF</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="../others/charts-apex.html">Apex</a></li>
                                    <li><a href="../others/charts-morris.html">Morris</a></li>
                                    <li><a href="../others/charts-chartist.html">Chartist</a></li>
                                    <li><a href="../others/charts-flot.html">Flot</a></li>
                                    <li><a href="../others/charts-peity.html">Peity</a></li>
                                    <li><a href="../others/charts-chartjs.html">Chartjs</a></li>
                                    <li><a href="../others/charts-sparkline.html">Sparkline</a></li>
                                    <li><a href="../others/charts-knob.html">Jquery Knob</a></li>
                                    <li><a href="../others/charts-justgage.html">JustGage</a></li>
                                </ul>            
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="#"><span class="w-100">Admin RM</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/vehiculos">Vehiculos</a></li>
                                    <li><a href="../others/tables-datatable.html">Datatables</a></li>
                                    <li><a href="../others/tables-responsive.html">Responsive</a></li>
                                    <li><a href="../others/tables-footable.html">Footable</a></li>
                                    <li><a href="../others/tables-jsgrid.html">Jsgrid</a></li>
                                    <li><a href="../others/tables-dragger.html">Dragger</a></li>
                                    <li><a href="../others/tables-editable.html">Editable</a></li>
                                </ul>            
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="#"><span class="w-100">Icons</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="../others/icons-materialdesign.html">Material Design</a></li>
                                    <li><a href="../others/icons-dripicons.html">Dripicons</a></li>
                                    <li><a href="../others/icons-fontawesome.html">Font awesome</a></li>
                                    <li><a href="../others/icons-themify.html">Themify</a></li>
                                    <li><a href="../others/icons-feather.html">Feather</a></li>
                                    <li><a href="../others/icons-typicons.html">Typicons</a></li>
                                    <li><a href="../others/icons-emoji.html">Emoji <i class="em em-ok_hand"></i></a></li>
                                </ul>            
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="#"><span class="w-100">Maps</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="../others/maps-google.html">Google Maps</a></li>
                                    <li><a href="../others/maps-leaflet.html">Leaflet Maps</a></li>
                                    <li><a href="../others/maps-vector.html">Vector Maps</a></li>       
                                </ul>            
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="#"><span class="w-100">Email Templates</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="../others/email-templates-basic.html">Basic Action Email</a></li>
                                    <li><a href="../others/email-templates-alert.html">Alert Email</a></li>
                                    <li><a href="../others/email-templates-billing.html">Billing Email</a></li>         
                                </ul>            
                            </li><!--end nav-item-->
                        </ul><!--end nav-->
                    </div><!-- end Others -->
          
                <div id="MetricaPages" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Programar Cursos</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Agregar/ReservarSala">Salas de Juntas</a>
                        </li>
                    
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Agregar/Asistencia">Asistencias</a>
                        </li>

                        
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>pages/pages-treeview.html">Constancias</a></li>

                    </ul>
                </div><!-- end Pages -->
                <div id="MetricaAuthentication" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Authentication</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>authentication/auth-login.html">Log in</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>authentication/auth-login-alt.html">Log in alt</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>authentication/auth-register.html">Register</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>authentication/auth-register-alt.html">Register-alt</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>authentication/auth-recover-pw.html">Re-Password</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>authentication/auth-recover-pw-alt.html">Re-Password-alt</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>authentication/auth-lock-screen.html">Lock Screen</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>authentication/auth-lock-screen-alt.html">Lock Screen</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>authentication/auth-404.html">Error 404</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>authentication/auth-404-alt.html">Error 404-alt</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>authentication/auth-500.html">Error 500</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>authentication/auth-500-alt.html">Error 500-alt</a></li>
                    </ul>
                </div><!-- end Authentication-->
            </div>
            <!--end menu-body-->
        </div><!-- end main-menu-inner-->
    </div>
    <!-- end leftbar-tab-menu-->

    <!-- Top Bar Start -->
    <div class="topbar">
        <!-- Navbar -->
        <nav class="navbar-custom">
            <ul class="list-unstyled topbar-nav float-right mb-0">
                <li class="hidden-sm">
                    <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown"
                        href="javascript: void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <i
                            class="fas fa-cart-arrow-down font-20 <?php echo (isset($dscCursos) && !empty($dscCursos))?'text-success':''?>"></i>
                        <i class="mdi mdi-chevron-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                     
                        <span class="dropdown-item">Sin cursos en el carrito</span>
     
                    </div>
                </li>

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti-bell noti-icon"></i>
                        <span class="badge badge-danger badge-pill noti-icon-badge">2</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-lg pt-0">

                        <h6
                            class="dropdown-item-text font-15 m-0 py-3 bg-primary text-white d-flex justify-content-between align-items-center">
                            Notifications <span class="badge badge-light badge-pill">2</span>
                        </h6>
                        <div class="slimscroll notification-list">
                            <!-- item-->
                            <a href="#" class="dropdown-item py-3">
                                <small class="float-right text-muted pl-2">2 min ago</small>
                                <div class="media">
                                    <div class="avatar-md bg-primary">
                                        <i class="la la-cart-arrow-down text-white"></i>
                                    </div>
                                    <div class="media-body align-self-center ml-2 text-truncate">
                                        <h6 class="my-0 font-weight-normal text-dark">Your order is placed</h6>
                                        <small class="text-muted mb-0">Dummy text of the printing and industry.</small>
                                    </div>
                                    <!--end media-body-->
                                </div>
                                <!--end media-->
                            </a>
                            <!--end-item-->
                            <!-- item-->
                            <a href="#" class="dropdown-item py-3">
                                <small class="float-right text-muted pl-2">10 min ago</small>
                                <div class="media">
                                    <div class="avatar-md bg-success">
                                        <i class="la la-group text-white"></i>
                                    </div>
                                    <div class="media-body align-self-center ml-2 text-truncate">
                                        <h6 class="my-0 font-weight-normal text-dark">Meeting with designers</h6>
                                        <small class="text-muted mb-0">It is a long established fact that a
                                            reader.</small>
                                    </div>
                                    <!--end media-body-->
                                </div>
                                <!--end media-->
                            </a>
                            <!--end-item-->
                            <!-- item-->
                            <a href="#" class="dropdown-item py-3">
                                <small class="float-right text-muted pl-2">40 min ago</small>
                                <div class="media">
                                    <div class="avatar-md bg-pink">
                                        <i class="la la-list-alt text-white"></i>
                                    </div>
                                    <div class="media-body align-self-center ml-2 text-truncate">
                                        <h6 class="my-0 font-weight-normal text-dark">UX 3 Task complete.</h6>
                                        <small class="text-muted mb-0">Dummy text of the printing.</small>
                                    </div>
                                    <!--end media-body-->
                                </div>
                                <!--end media-->
                            </a>
                            <!--end-item-->
                            <!-- item-->
                            <a href="#" class="dropdown-item py-3">
                                <small class="float-right text-muted pl-2">1 hr ago</small>
                                <div class="media">
                                    <div class="avatar-md bg-warning">
                                        <i class="la la-truck text-white"></i>
                                    </div>
                                    <div class="media-body align-self-center ml-2 text-truncate">
                                        <h6 class="my-0 font-weight-normal text-dark">Your order is placed</h6>
                                        <small class="text-muted mb-0">It is a long established fact that a
                                            reader.</small>
                                    </div>
                                    <!--end media-body-->
                                </div>
                                <!--end media-->
                            </a>
                            <!--end-item-->
                            <!-- item-->
                            <a href="#" class="dropdown-item py-3">
                                <small class="float-right text-muted pl-2">2 hrs ago</small>
                                <div class="media">
                                    <div class="avatar-md bg-info">
                                        <i class="la la-check-circle text-white"></i>
                                    </div>
                                    <div class="media-body align-self-center ml-2 text-truncate">
                                        <h6 class="my-0 font-weight-normal text-dark">Payment Successfull</h6>
                                        <small class="text-muted mb-0">Dummy text of the printing.</small>
                                    </div>
                                    <!--end media-body-->
                                </div>
                                <!--end media-->
                            </a>
                            <!--end-item-->
                        </div>
                        <!-- All-->
                        <a href="javascript:void(0);" class="dropdown-item text-center text-primary">
                            View all <i class="fi-arrow-right"></i>
                        </a>
                    </div>
                </li>

                <li class="dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="<?php echo base_url(); ?>assets/images/users/user-4.jpg" alt="profile-user"
                            class="rounded-circle" />
                        <span class="ml-1 nav-user-name hidden-sm"><?= $session->nombre_completo?>
                            <i class="mdi mdi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#"><i class="dripicons-user text-muted mr-2"></i> Profile</a>
                        <a class="dropdown-item" href="#"><i class="dripicons-wallet text-muted mr-2"></i> My Wallet</a>
                        <a class="dropdown-item" href="#"><i class="dripicons-gear text-muted mr-2"></i> Settings</a>
                        <a class="dropdown-item" href="#"><i class="dripicons-lock text-muted mr-2"></i> Lock screen</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo base_url()?>index.php/Login/cerrar"><i
                                class="dripicons-exit text-muted mr-2"></i> Salir</a>
                    </div>
                </li>


                <li class="mr-2">
                    <a href="#" class="nav-link" data-toggle="modal" data-animation="fade"
                        data-target=".modal-rightbar">
                        <i data-feather="align-right" class="align-self-center"></i>
                    </a>
                </li>
            </ul>
            <!--end topbar-nav-->

            <ul class="list-unstyled topbar-nav mb-0">
                <li>
                    <a href="<?php echo base_url(); ?>analytics/analytics-index.html">
                        <span class="responsive-logo">
                            <img src="<?php echo base_url(); ?>assets/images/logo-sm.png" alt="logo-small"
                                class="logo-sm align-self-center" height="34">
                        </span>
                    </a>
                </li>
                <li>
                    <button class="button-menu-mobile nav-link waves-effect waves-light">
                        <i data-feather="menu" class="align-self-center"></i>
                    </button>
                </li>
                <li class="dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="ml-1 p-2 bg-soft-classic nav-user-name hidden-sm rounded">System <i
                                class="mdi mdi-chevron-down"></i> </span>
                    </a>
                    <div class="dropdown-menu dropdown-xl dropdown-menu-left p-0">
                        <div class="row no-gutters">
                            <div class="col-12 col-lg-6">
                                <div class="text-center system-text">
                                    <h4 class="text-white">The Poworfull Dashboard</h4>
                                    <p class="text-white">See all the pages Metrica.</p>
                                    <a href="https://mannatthemes.com/metrica/" class="btn btn-sm btn-pink mt-2">See
                                        Dashboard</a>
                                </div>
                                <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <img src="<?php echo base_url(); ?>assets/images/dashboard/dash-1.png"
                                                class="d-block img-fluid" alt="...">
                                        </div>
                                        <div class="carousel-item">
                                            <img src="<?php echo base_url(); ?>assets/images/dashboard/dash-4.png"
                                                class="d-block img-fluid" alt="...">
                                        </div>
                                        <div class="carousel-item">
                                            <img src="<?php echo base_url(); ?>assets/images/dashboard/dash-2.png"
                                                class="d-block img-fluid" alt="...">
                                        </div>
                                        <div class="carousel-item">
                                            <img src="<?php echo base_url(); ?>assets/images/dashboard/dash-3.png"
                                                class="d-block img-fluid" alt="...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-12 col-lg-6">
                                <div class="divider-custom mb-0">
                                    <div class="divider-text bg-light">All Dashboard</div>
                                    </divi>
                                    <div class="p-4 text-left">
                                        <div class="row">
                                            <div class="col-6">
                                                <a class="dropdown-item mb-2"
                                                    href="<?php echo base_url(); ?>analytics/analytics-index.html">
                                                    Analytics</a>
                                                <a class="dropdown-item mb-2"
                                                    href="<?php echo base_url(); ?>crypto/crypto-index.html"> Crypto</a>
                                                <a class="dropdown-item mb-2"
                                                    href="<?php echo base_url(); ?>crm/crm-index.html"> CRM</a>
                                                <a class="dropdown-item"
                                                    href="<?php echo base_url(); ?>projects/projects-index.html">
                                                    Project</a>
                                            </div>
                                            <div class="col-6">
                                                <a class="dropdown-item mb-2"
                                                    href="<?php echo base_url(); ?>ecommerce/ecommerce-index.html">
                                                    Ecommerce</a>
                                                <a class="dropdown-item mb-2"
                                                    href="<?php echo base_url(); ?>helpdesk/helpdesk-index.html">
                                                    Helpdesk</a>
                                                <a class="dropdown-item"
                                                    href="<?php echo base_url(); ?>hospital/hospital-index.html">
                                                    Hospital</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                </li>
                <li class="hide-phone app-search">
                    <h6>
                        <?php 
                        switch($session->id_perfil){
                            case 1: echo '<i class="mdi mdi-account-card-details font-18"></i> Administrador'; break;
                            case 4: echo '<i class="mdi mdi-account-card-details font-18"></i> Gestor'; break;
                            case 6: echo '<i class="mdi mdi-account-card-details font-18"></i> Enlace'; break;
                            case 8: echo '<i class="mdi mdi-account-card-details font-18"></i> Estudiante'; break;
                            default: echo 'Sin dato';
                        }  
                        ?>
                    </h6>
                </li>
                <li class="hide-phone">
                    <?php 
                    $hoy = date("Y-m-d H:i:s");  
                    $fecha_nac = new DateTime($session->fec_nac); // Convierte la fecha de nacimiento a objeto DateTime
                    $hoy_solo_fecha = date("m-d"); // Obtiene solo mes y día actual
                    $fecha_nac_solo_fecha = $fecha_nac->format("m-d"); // Extrae mes y día de la fecha de nacimiento
                    ?>
                    <?php if ($fecha_nac_solo_fecha === $hoy_solo_fecha): ?>
                    <div id="caja" style="cursor:pointer"><a class="nav-link" onclick="lanzarConfeti()"><i class="ti-gift text-info font-22"></i></a></div>
                    <div id="pastel" style="display:none"  data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="Feliz Compleaños">
                        <a class="nav-link"><i title="Feliz Compleaños" class="mdi mdi-cake-layered text-success font-22"></i></a>
                    </div>
                    <?php endif; ?>
                </li>

            </ul>
        </nav>
        <!-- end navbar-->
    </div>
    <!--modal -->
    <!-- Modal de Soporte TI -->
<div class="modal fade" id="supportModal" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="supportModalLabel">
                    <i class="fas fa-headset mr-2"></i>Soporte TI
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="chat-container" id="chat" style="height: 60vh; overflow-y: auto;">
                    <!-- Mensaje inicial -->
                    <div class="message bot-message">
                        <div class="avatar">TI</div>
                        <div class="content">
                            <p>¡Hola! ¿En qué puedo ayudarte hoy?</p>
                            <div class="options">
                                <button class="option-btn asistencia-btn" onclick="ini.inicio.selectCategory('asistencia')">
                                    <i class="fas fa-tools"></i> ASISTENCIA
                                </button>
                                <button class="option-btn accesorios-btn" onclick="ini.inicio.selectCategory('accesorios')">
                                    <i class="fas fa-keyboard"></i> ACCESORIOS
                                </button>
                                <button class="option-btn plataformas-btn" onclick="ini.inicio.selectCategory('plataformas')">
                                    <i class="fas fa-globe"></i> PLATAFORMAS
                                </button>
                                <button class="option-btn impresoras-btn" onclick="ini.inicio.selectCategory('impresoras')">
                                    <i class="fas fa-print"></i> IMPRESORAS
                                </button>
                                <button class="option-btn otro-btn" onclick="ini.inicio.selectCategory('otro')">
                                    <i class="fas fa-question-circle"></i> OTRO
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-input-container p-3 border-top" id="textInputContainer">
                    <textarea class="form-control" id="problemDescription" placeholder="Describe tu problema en detalle..." rows="3"></textarea>
                    <button class="btn btn-primary mt-2 float-right" onclick="submitProblem()">
                        <i class="fas fa-paper-plane mr-2"></i> Enviar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
    <!--fin modal -->
    <!-- Top Bar End -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1"></script>

   <script>
        // Variables de estado
        let currentCategory = '';
        let ticketData = {};
        
        // Datos de las impresoras
        const impresoras = [
            "Despacho del C. Secretario (Canon imageRUNNER Advance C250iF)",
            "Coordinación de Comunicación Social (Samsung ProXpres)",
            "Coordinación de Recursos Financieros (Canon imageCLASS D1370)",
            "Oficina de Comunicación Social (HP LaserJet 500 color M551)",
            "Coordinación de Recursos Materiales (Canon imageCLASS D1370)",
            "Dirección de Relaciones Públicas (Samsung ProXpress SL-M3820ND)",
            "Oficina de la Dirección de Relaciones Públicas(Canon imageRUNNER 1435i)",
            "Coordinación de Recursos Humanos(Canon imageRUNNER 1435i)",
            "Dirección de Información y Análisis (HP LaserJet 500 color M551)",
            "Dirección General de Planeación (Canon imageRUNNER 1435i)",
            "DGA (Canon imageRUNNER 1435i)",
            "Coordinación de Impuesto por Servicios de Hospedaje (Canon imageRUNNER 1435i)",
            "Oficina de la Subsecretaria de Promoción Turística (Canon imageRUNNER ADVANCE 4245i)",
            "Dirección de Productos Turísticos (Canon imageRUNNER ADVANCE 4251)",
            "Dirección de Promoción y Difusión (Samsung M332x 382x 402x Series)",
            "Oficina de Dirección de Promoción y Difusión (Canon imageRUNNER ADVANCE C5035)",
            "Dirección de Proyectos Turísticos (Canon imageRUNNER ADVANCE C5035)",
            "Oficina de Dirección de Proyectos Turísticos (Samsung ProXpress SL-M3820ND)",
            "Dirección General de Planeación (Canon imageRUNNER ADVANCE C5051 COLOR)",
            "Dirección de Asuntos Jurídicos (Canon iR 3235)",
            "Planta Baja Color (Canon imageRUNNER ADVANCE C5045)",
            "Oficina de Asuntos Jurídicos (Canon iR 3235)",
            "Dirección General de Desarrollo Turístico (Canon iR 3235)",
            "Dirección de Mercadotecnia (HP LaserJet 400 M401 PCL 6)",
            "Dirección de Cultura Turística (HP LaserJet 500 color M551)"
        ];
        
        // Función para seleccionar categoría
      
        
        // Mostrar opciones para impresoras
        function showPrinterOptions() {
            const chat = document.getElementById('chat');
            
            chat.innerHTML += `
                <div class="message bot-message">
                    <div class="avatar">TI</div>
                    <div class="content">
                        <button class="back-btn" onclick="backToMainMenu()">
                            <i class="fas fa-arrow-left"></i> Volver al menú principal
                        </button>
                        <p>Selecciona el problema con la impresora:</p>
                        <div class="options">
                            <button class="option-btn impresoras-btn" onclick="selectPrinterProblem('Atasco de papel')">
                                <i class="fas fa-paper-plane"></i> Atasco de papel
                            </button>
                            <button class="option-btn impresoras-btn" onclick="selectPrinterProblem('Error en pantalla')">
                                <i class="fas fa-exclamation-circle"></i> Error en pantalla
                            </button>
                            <button class="option-btn impresoras-btn" onclick="selectPrinterProblem('Tóner residual (Liberar)')">
                                <i class="fas fa-recycle"></i> Tóner residual (Liberar)
                            </button>
                            <button class="option-btn impresoras-btn" onclick="selectPrinterProblem('Ruidos internos')">
                                <i class="fas fa-volume-mute"></i> Ruidos internos
                            </button>
                            <button class="option-btn impresoras-btn" onclick="selectPrinterProblem('Manchas en la impresión')">
                                <i class="fas fa-tint"></i> Manchas en la impresión
                            </button>
                            <button class="option-btn impresoras-btn" onclick="selectPrinterProblem('No manda correo al escanear')">
                                <i class="fas fa-envelope"></i> No manda correo al escanear
                            </button>
                            <button class="option-btn impresoras-btn" onclick="showTonerPrinters()">
                                <i class="fas fa-tint"></i> Solicitud de tóner
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Mostrar lista de impresoras para tóner
        function showTonerPrinters() {
            const chat = document.getElementById('chat');
            
            // Registrar que seleccionó solicitud de tóner
            chat.innerHTML += `
                <div class="message user-message">
                    <div class="content">
                        <p><i class="fas fa-tint"></i> Solicitud de tóner</p>
                    </div>
                    <div class="avatar">YO</div>
                </div>
            `;
            
            // Mostrar lista de impresoras
            setTimeout(() => {
                let printerOptions = '';
                impresoras.forEach(printer => {
                    printerOptions += `
                        <button class="option-btn impresoras-btn" onclick="selectPrinter('${printer}')">
                            <i class="fas fa-print"></i> ${printer}
                        </button>
                    `;
                });
                
                chat.innerHTML += `
                    <div class="message bot-message">
                        <div class="avatar">TI</div>
                        <div class="content">
                            <button class="back-btn" onclick="backToPrinterOptions()">
                                <i class="fas fa-arrow-left"></i> Volver a problemas de impresora
                            </button>
                            <p>Selecciona la impresora que necesita tóner:</p>
                            <div class="options">
                                ${printerOptions}
                            </div>
                        </div>
                    </div>
                `;
                
                chat.scrollTop = chat.scrollHeight;
            }, 500);
        }
        
        // Seleccionar impresora para tóner
        function selectPrinter(printer) {
            const chat = document.getElementById('chat');
            
            // Mostrar selección de impresora
            chat.innerHTML += `
                <div class="message user-message">
                    <div class="content">
                        <p><i class="fas fa-print"></i> ${printer}</p>
                    </div>
                    <div class="avatar">YO</div>
                </div>
            `;
            
            // Mostrar confirmación
            setTimeout(() => {
                ini.inicio.showConfirmation(printer);
            }, 500);
        }
        
        // Seleccionar problema de impresora (no tóner)
        function selectPrinterProblem(problem) {
            const chat = document.getElementById('chat');
            
            // Mostrar selección de problema
            chat.innerHTML += `
                <div class="message user-message">
                    <div class="content">
                        <p><i class="fas fa-print"></i> ${problem}</p>
                    </div>
                    <div class="avatar">YO</div>
                </div>
            `;
            
            // Mostrar confirmación
            setTimeout(() => {
                ini.inicio.showConfirmation(problem);
            }, 500);
        }
        
        // Mostrar opciones por defecto para otras categorías
        function showDefaultOptions(category) {
            const chat = document.getElementById('chat');
            let options = '';
            let title = '';
            let icon = '';
            
            switch(category) {
                case 'asistencia':
                    title = "ASISTENCIA TÉCNICA";
                    icon = "fas fa-tools";
                    options = `
                        <button class="option-btn asistencia-btn" onclick="selectOption('Lentitud en el equipo', 'fas fa-tachometer-alt')">
                            <i class="fas fa-tachometer-alt"></i> Lentitud en el equipo
                        </button>
                        <button class="option-btn asistencia-btn" onclick="selectOption('Instalación de programa', 'fas fa-download')">
                            <i class="fas fa-download"></i> Instalación de programa
                        </button>
                        <button class="option-btn asistencia-btn" onclick="selectOption('Error de Office', 'fas fa-file-word')">
                            <i class="fas fa-file-word"></i> Error de Office
                        </button>
                        <button class="option-btn asistencia-btn" onclick="selectOption('Error de licenciamiento', 'fas fa-key')">
                            <i class="fas fa-key"></i> Error de licenciamiento
                        </button>
                        <button class="option-btn asistencia-btn" onclick="selectOption('Error de Sistema Operativo', 'fas fa-desktop')">
                            <i class="fas fa-desktop"></i> Error de Sistema Operativo
                        </button>
                        <button class="option-btn asistencia-btn" onclick="selectOption('Problema de batería', 'fas fa-battery-quarter')">
                            <i class="fas fa-battery-quarter"></i> Problema de batería
                        </button>
                        <button class="option-btn asistencia-btn" onclick="selectOption('Problema de adaptador', 'fas fa-plug')">
                            <i class="fas fa-plug"></i> Problema de adaptador
                        </button>
                        <button class="option-btn asistencia-btn" onclick="selectOption('Problema de audio/video', 'fas fa-volume-up')">
                            <i class="fas fa-volume-up"></i> Problema de audio/video
                        </button>
                        <button class="option-btn asistencia-btn" onclick="selectOption('Problema de hardware', 'fas fa-microchip')">
                            <i class="fas fa-microchip"></i> Problema de hardware
                        </button>
                        <button class="option-btn asistencia-btn" onclick="selectOption('Lentitud de Internet', 'fas fa-wifi')">
                            <i class="fas fa-wifi"></i> Lentitud de Internet
                        </button>
                        <button class="option-btn asistencia-btn" onclick="selectOption('Solicitud de acceso VPN', 'fas fa-shield-alt')">
                            <i class="fas fa-shield-alt"></i> Solicitud de acceso VPN
                        </button>
                        <button class="option-btn asistencia-btn" onclick="selectOption('Error al cargar página Web', 'fas fa-exclamation-triangle')">
                            <i class="fas fa-exclamation-triangle"></i> Error al cargar página Web
                        </button>
                    `;
                    break;
                case 'accesorios':
                    title = "ACCESORIOS TI";
                    icon = "fas fa-keyboard";
                    options = `
                        <button class="option-btn accesorios-btn" onclick="selectOption('Solicitud de mouse', 'fas fa-mouse')">
                            <i class="fas fa-mouse"></i> Solicitud de mouse
                        </button>
                        <button class="option-btn accesorios-btn" onclick="selectOption('Solicitud de proyector', 'fas fa-video')">
                            <i class="fas fa-video"></i> Solicitud de proyector
                        </button>
                        <button class="option-btn accesorios-btn" onclick="selectOption('Solicitud de memoria USB', 'fas fa-usb')">
                            <i class="fas fa-usb"></i> Solicitud de memoria USB
                        </button>
                        <button class="option-btn accesorios-btn" onclick="selectOption('Solicitud cargador', 'fas fa-charging-station')">
                            <i class="fas fa-charging-station"></i> Solicitud cargador
                        </button>
                        <button class="option-btn accesorios-btn" onclick="selectOption('Solicitud de batería', 'fas fa-battery-full')">
                            <i class="fas fa-battery-full"></i> Solicitud de batería
                        </button>
                        <button class="option-btn accesorios-btn" onclick="selectOption('Solicitud de base enfriadora', 'fas fa-fan')">
                            <i class="fas fa-fan"></i> Solicitud de base enfriadora
                        </button>
                        <button class="option-btn accesorios-btn" onclick="selectOption('Solicitud de mouse pad', 'fas fa-vector-square')">
                            <i class="fas fa-vector-square"></i> Solicitud de mouse pad
                        </button>
                        <button class="option-btn accesorios-btn" onclick="selectOption('Solicitud de lector de CD', 'fas fa-compact-disc')">
                            <i class="fas fa-compact-disc"></i> Solicitud de lector de CD
                        </button>
                        <button class="option-btn accesorios-btn" onclick="selectOption('Solicitud de equipo de cómputo', 'fas fa-laptop')">
                            <i class="fas fa-laptop"></i> Solicitud de equipo de cómputo
                        </button>
                    `;
                    break;
                case 'plataformas':
                    title = "PLATAFORMAS WEB";
                    icon = "fas fa-globe";
                    options = `
                        <button class="option-btn plataformas-btn" onclick="selectOption('Cambio de formatos (Intranet)', 'fas fa-edit')">
                            <i class="fas fa-edit"></i> Cambio de formatos (Intranet)
                        </button>
                        <button class="option-btn plataformas-btn" onclick="selectOption('Sistema de asistencia (Intratur)', 'fas fa-hands-helping')">
                            <i class="fas fa-hands-helping"></i> Sistema de asistencia (Intratur)
                        </button>
                        <button class="option-btn plataformas-btn" onclick="selectOption('Sistema de correspondencia', 'fas fa-envelope')">
                            <i class="fas fa-envelope"></i> Sistema de correspondencia
                        </button>
                        <button class="option-btn plataformas-btn" onclick="selectOption('Sistema de sala de juntas', 'fas fa-users')">
                            <i class="fas fa-users"></i> Sistema de sala de juntas
                        </button>
                        <button class="option-btn plataformas-btn" onclick="selectOption('Subir banners', 'fas fa-image')">
                            <i class="fas fa-image"></i> Subir banners
                        </button>
                        <button class="option-btn plataformas-btn" onclick="selectOption('Creación de nueva sección', 'fas fa-plus-square')">
                            <i class="fas fa-plus-square"></i> Creación de nueva sección
                        </button>
                        <button class="option-btn plataformas-btn" onclick="selectOption('Sección destacados', 'fas fa-star')">
                            <i class="fas fa-star"></i> Sección destacados
                        </button>
                    `;
                    break;
            }
            
            chat.innerHTML += `
                <div class="message bot-message">
                    <div class="avatar">TI</div>
                    <div class="content">
                        <button class="back-btn" onclick="backToMainMenu()">
                            <i class="fas fa-arrow-left"></i> Volver al menú principal
                        </button>
                        <p>Selecciona una opción de ${title}:</p>
                        <div class="options">
                            ${options}
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Mostrar campo de texto para "OTRO"
        function showTextInput() {
            const chat = document.getElementById('chat');
            
            chat.innerHTML += `
                <div class="message bot-message">
                    <div class="avatar">TI</div>
                    <div class="content">
                        <button class="back-btn" onclick="backToMainMenu()">
                            <i class="fas fa-arrow-left"></i> Volver al menú principal
                        </button>
                        <p>Por favor, describe tu problema:</p>
                    </div>
                </div>
            `;
            
            document.getElementById('textInputContainer').style.display = 'block';
            chat.scrollTop = chat.scrollHeight;
        }
        
        // Seleccionar opción genérica
        function selectOption(option, icon) {
            const chat = document.getElementById('chat');
            
            // Mostrar selección del usuario
            chat.innerHTML += `
                <div class="message user-message">
                    <div class="content">
                        <p><i class="${icon}"></i> ${option}</p>
                    </div>
                    <div class="avatar">YO</div>
                </div>
            `;
            
            // Mostrar confirmación
            setTimeout(() => {
                ini.inicio.showConfirmation(option);
            }, 500);
        }
        
        // Enviar problema escrito
        function submitProblem() {
            const description = document.getElementById('problemDescription').value;
            if (description.trim() === '') {
                alert('Por favor describe tu problema');
                return;
            }
            
            const chat = document.getElementById('chat');
            
            // Mostrar descripción del usuario
            chat.innerHTML += `
                <div class="message user-message">
                    <div class="content">
                        <p><i class="fas fa-comment-alt"></i> ${description}</p>
                    </div>
                    <div class="avatar">YO</div>
                </div>
            `;
            
            document.getElementById('textInputContainer').style.display = 'none';
            document.getElementById('problemDescription').value = '';
            
            // Mostrar confirmación
            setTimeout(() => {
                ini.inicio.showConfirmation(description);
            }, 500);
        }
        
        // Mostrar confirmación de ticket
     
        
        // Volver al menú principal
        function backToMainMenu() {
            document.getElementById('textInputContainer').style.display = 'none';
            const chat = document.getElementById('chat');
            
            // Mantener solo el primer mensaje
            const initialMessage = chat.children[0].outerHTML;
            chat.innerHTML = initialMessage;
            
            chat.scrollTop = chat.scrollHeight;
        }
        
        // Volver a opciones de impresora
        function backToPrinterOptions() {
            const chat = document.getElementById('chat');
            
            // Eliminar los últimos mensajes (selección de tóner y lista de impresoras)
            while (chat.children.length > 2) {
                chat.removeChild(chat.lastChild);
            }
            
            // Mostrar opciones de impresora nuevamente
            showPrinterOptions();
        }


    function lanzarConfeti() {
        confetti({
        particleCount: 100,
        spread: 70,
        origin: { y: 0.6 },
        scalar: 1.2, // Tamaño del confeti
        shapes: ["circle", "square"], // Formas del confeti
        colors: ["#ff0000", "#ff8000", "#ffff00", "#00ff00", "#0000ff"], // Colores del confeti
    });
     $("#caja").hide();
     $("#pastel").show();
    }
    // Función para abrir el modal


    </script>