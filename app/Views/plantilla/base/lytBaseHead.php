<?php $session = \Config\Services::session(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>SUSI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistema Unificado SECTURI" name="description" />
    <meta content="SECTURI" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/huella.png">


    <!-- jvectormap -->
    <link href="<?php echo base_url(); ?>plugins/jvectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.min.css
" rel="stylesheet">
    <link href="<?php echo base_url(); ?>plugins/animate/animate.css" rel="stylesheet" type="text/css">

    <!-- App css -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

    <?php if (isset($scripts)):
        foreach ($scripts as $js): ?>
            <script src="<?php echo base_url() . "js/{$js}.js" ?>?filever=<?php echo time() ?>" type="text/javascript">
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
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        from {
            opacity: 0;
            transform: translateX(10px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
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
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Colores por categoría */
    .asistencia-btn {
        background: #ffddd2;
        color: #e29578;
    }

    .accesorios-btn {
        background: #e2f0cb;
        color: #83c5be;
    }

    .plataformas-btn {
        background: #dfe7fd;
        color: #5a7bd8;
    }

    .impresoras-btn {
        background: #ffeedd;
        color: #ff9f1c;
    }

    .otro-btn {
        background: #f8d7ff;
        color: #9b5de5;
    }

    .option-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
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
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
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
        0% {
            transform: scale(0.9);
            opacity: 0;
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .ticket-number {
        font-size: 1.3em;
        font-weight: bold;
        margin: 5px 0;
        display: inline-block;
        padding: 5px 10px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
    }

    /* Widget de Accesibilidad */
    .accessibility-widget {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }

    .accessibility-toggle {
        width: 50px;
        height: 50px;
        background-color: #3498db;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        border: none;
    }

    .accessibility-toggle:hover {
        background-color: #2980b9;
        transform: scale(1.1);
    }

    .accessibility-icon {
        color: white;
        font-size: 24px;
        font-weight: bold;
    }

    /* Estilos para las opciones de accesibilidad */
    body.large-text {
        font-size: 1.2em !important;
    }

    body.extra-large-text {
        font-size: 1.5em !important;
    }

    body.high-contrast {
        background-color: #000 !important;
        color: #fff !important;
    }

    body.high-contrast .card,
    body.high-contrast .modal-content,
    body.high-contrast .table {
        background-color: #000 !important;
        color: #fff !important;
        border-color: #fff !important;
    }

    body.high-contrast .btn {
        background-color: #fff !important;
        color: #000 !important;
        border-color: #fff !important;
    }

    body.grayscale {
        filter: grayscale(100%) !important;
    }

    body.readable-font {
        font-family: Arial, sans-serif !important;
        font-weight: 600 !important;
    }
</style>

<body>

    <script>
        var base_url = "<?php echo base_url(); ?>";
        var token = "<?php echo env('TOKEN_API'); ?>";
        var api = "<?php echo env('NODE_API_CURP'); ?>";
    </script>

    <!-- leftbar-tab-menu -->
    <div class="leftbar-tab-menu">
        <div class="main-icon-menu">
            <a href="<?php echo base_url(); ?>index.php/Inicio" class="logo logo-metrica d-block text-center">
                <span>
                    <img src="<?php echo base_url(); ?>assets/susi2.webp" alt="logo-small"
                        style="width:50px; heigth:50px;">
                </span>
            </a>
            <nav class="nav">
                <?php if ($session->esJefe): ?>
                    <a href="#MetricaDashboard" class="nav-link" data-toggle="tooltip-custom" data-placement="right"
                        data-trigger="hover" title="" data-original-title="Persona Superior Jerárquica">
                        <i data-feather="award" class="align-self-center menu-icon icon-dual"></i>
                    </a>
                <?php endif; ?>

                <a href="#MetricaApps" class="nav-link" data-toggle="tooltip-custom" data-placement="top"
                    data-trigger="hover" title="Pagos" data-original-title="Pagos">
                    <i data-feather="dollar-sign" class="align-self-center menu-icon icon-dual"></i>
                </a><!--end MetricaApps-->

                <?php if (in_array($session->id_perfil, [1, 3, 5])): ?>
                    <a href="#MetricaUikit" class="nav-link" data-toggle="tooltip-custom" data-placement="right"
                        data-trigger="hover" title="" data-original-title="Admin">
                        <i data-feather="user" class="align-self-center menu-icon icon-dual"></i>
                    </a><!--end MetricaUikit-->
                <?php endif; ?>
                <a href="#MetricaPages" class="nav-link" data-toggle="tooltip-custom" data-placement="top"
                    data-trigger="hover" title="Mi espacio" data-original-title="Mi espacio">
                    <i data-feather="home" class="align-self-center menu-icon icon-dual"></i>
                </a><!--end MetricaPages-->
        
                    <a href="#MetricaAuthentication" class="nav-link" data-toggle="tooltip-custom" data-placement="right"
                        data-trigger="hover" title="" data-original-title="Jurídico">
                        <i class="fas fa-balance-scale align-self-center menu-icon icon-dual"></i>
                    </a> <!--end MetricaAuthentication-->
         



            </nav><!--end nav-->
            <!--end nav-->
            <div class="pro-metrica-end">
                <a style="cursor: pointer;" class="help" data-toggle="tooltip-custom" data-placement="top"
                    title="Subir un Tiket" onclick="ini.inicio.openSupportModal()">
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
                        <h6 class="menu-title">Persona Superior Jerárquica</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Principal/incidenciaSubordinado">Incidencias</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Principal/Personal">Personal</a></li>

                    </ul>
                </div><!-- end Dashboards -->

                <div id="MetricaApps" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Recursos</h6>
                    </div>
                    <ul class="nav metismenu">
                  
                            <li class="nav-item">
                                <a class="nav-link" href="javascript: void(0);"><span class="w-100">Registro</span><span
                                        class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="<?php echo base_url(); ?>index.php/Principal/listadoPT">Reserva PT</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/Principal/listadoGo">Reserva GO</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/Principal/listadoGrc">Solicitud GRC</a></li>
                                </ul>
                            </li><!--end nav-item-->
                    
                        <li class="nav-item">
                            <a class="nav-link" href="javascript: void(0);"><span class="w-100">En Proceso</span><span
                                    class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="nav-second-level" aria-expanded="false">
                                <li><a href="<?php echo base_url(); ?>index.php/Principal/listaReservaPT">En proceso PT</a>
                                </li>
                                <li><a href="<?php echo base_url(); ?>index.php/Principal/listaReservaGO">En proceso GO</a>
                                </li>
                                <li><a href="<?php echo base_url(); ?>index.php/Inicio/ListadoSolicitudes">En proceso GRC</a></li> 
                            </ul>
                        </li><!--end nav-item-->
                        
                        <li class="nav-item">
                            <a class="nav-link" href="javascript: void(0);"><span class="w-100">Enviados</span><span
                                    class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="nav-second-level" aria-expanded="false">
                                <li><a href="<?php echo base_url(); ?>index.php/Principal/listadoEstatusPT">Envio PT</a>
                                </li>
                                <li><a href="<?php echo base_url(); ?>index.php/Principal/listadoEnvioGO">Envio GO</a>
                                </li>
                                <!--  <li><a href="../apps/email-read.html">Envio GRC</a></li>     -->
                            </ul>
                        </li><!--end nav-item-->

                        <?php if (in_array($session->id_perfil, [1, 2])): ?>
                        <li class="nav-item">
                             <a class="nav-link" href="javascript: void(0);"><span class="w-100">Concluidos</span><span
                                     class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                             <ul class="nav-second-level" aria-expanded="false">
                                 <li><a href="<?php echo base_url(); ?>index.php/Principal/concluidosAceptados">Aceptados PT</a>
                                 </li>
                                 <li><a href="<?php echo base_url(); ?>index.php/Principal/concluidosDeclinados">Declinados PT</a>
                                 </li>
                                 <li><a href="<?php echo base_url(); ?>index.php/Principal/concluidosAceptadosGO">Aceptados GO</a>
                                 </li>
                                 <li><a href="<?php echo base_url(); ?>index.php/Principal/concluidosDeclinadosGO">Declinados GO</a>
                                 </li>
                             </ul>
                         </li>
                         <?php endif; ?>                       <!--end nav-item-->
                         <li class="nav-item">
                            <a class="nav-link" href="javascript: void(0);"><span class="w-100">Sin enviar</span>
                            <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="nav-second-level" aria-expanded="false">
                              
                                <li><a href="<?php echo base_url(); ?>index.php/Principal/listaBorradoresGO">Borradores GO</a>
                                </li>
                          
                            </ul>
                        </li> 

                    <!--    <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Inicio/TipoOperacion" aria-expanded="false">Solicitud de Op. Bancarias</a></li>
                         <li class="nav-item">
                            <a class="nav-link" href="javascript: void(0);"><span class="w-100">Manual</span>
                            <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="nav-second-level" aria-expanded="false">
                              
                                <li><a href="<?php echo base_url(); ?>index.php/Principal/generarFormatoPT">Hoja Azul</a>
                                </li>
                                <li><a href="<?php echo base_url(); ?>index.php/Inicio/ListaHojaAzul">Lista Hoja Azul</a>
                                </li>
                          
                            </ul>
                        </li>  --> 
                    </ul>
                </div><!-- end Crypto -->


                <div id="MetricaUikit" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">ADMIN</h6>
                    </div>

                    <ul class="nav metismenu">
                        <?php if (in_array($session->id_perfil, [1, 3])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><span class="w-100">Admin TI</span><span class="menu-arrow"><i
                                            class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/usuarios">Lista de Usuarios</a>
                                    </li>
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/altaUsuario">Alta de Usuarios</a>
                                    </li>
                                        <li><a href="<?php echo base_url(); ?>index.php/Inicio/listaPuesto">Lista de Puestos</a>
                                        </li>
                                        <li><a href="<?php echo base_url(); ?>index.php/Inicio/listaArea">Lista de Área</a></li>
                                        <?php if ($session->id_perfil == 1): ?>
                                              <li><a href="<?php echo base_url(); ?>index.php/Inicio/listaPerfil">Lista de
                                                Perfiles</a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php/Inicio/listaTiket">Listado de Tikets</a>
                                        </li>
                                        <li><a href="<?php echo base_url(); ?>index.php/Principal/listadoProveedores">Lista de
                                                Proveedores</a></li>
                                        <li><a href="<?php echo base_url(); ?>index.php/Principal/bitacora">Bitacora</a></li>
                                    <?php endif; ?>

                                </ul>
                            </li><!--end nav-item-->
                        <?php endif; ?>
                        <?php if (in_array($session->id_perfil, [1, 3])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><span class="w-100">Admin RH</span><span class="menu-arrow"><i
                                            class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/subirAsistencia">Asistencias</a>
                                    </li>
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/listaIncidencia">Lista de
                                            Incidencia</a></li>

                                </ul>
                            </li><!--end nav-item-->
                        <?php endif; ?>
                        <?php if (in_array($session->id_perfil, [1, 5])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><span class="w-100">Admin RM</span><span class="menu-arrow"><i
                                            class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <?php if ($session->id_usuario != 91): ?>
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/vehiculos">Vehiculos</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/vehiculosPT">Vehiculos PT</a></li>
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/ListaInventario">Inventarios</a>
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/Servicios">Servicios</a>
                                    <?php endif; ?>
                                    <?php if (in_array($session->id_usuario, [ 91,121, 53, 85, 1 ])): ?>
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/InventarioProductos">Inventario Productos</a>
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/InventarioLimpieza">Inventario Limpieza</a></li>
                                    <?php endif; ?>
                                </ul>   
                            </li><!--end nav-item-->
                     
                        <?php endif; ?>
                               <li class="nav-item">
                                <a class="nav-link" href="#"><span class="w-100">Promocion Tur.</span><span class="menu-arrow"><i
                                            class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    
                                    <li><a href="<?php echo base_url(); ?>index.php/Inicio/ListaConvenio">Inventario</a></li>
                                   
                                  
                                </ul>   
                            </li><!--end nav-item-->
                    </ul><!--end nav-->
                </div><!-- end Others -->


                <div id="MetricaPages" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Home</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Agregar/ReservarSala">Salas de Juntas</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Agregar/Asistencia">Asistencias</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Agregar/Documentos">Normatividad</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Agregar/Directorio">Directorio</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Agregar/ListaAlba">Lista ALBA</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Agregar/Denuncia">Denuncia</a></li>
                    <!--     <?php if(in_array($session->id_usuario, [1,75])): ?>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Agregar/Ganadores">Ganadores</a></li>
                        <?php endif ?> -->
               <!--          <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Agregar/Inventario">Inventario</a></li> -->
                        <!--   <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>index.php/Agregar/vTiketDisenio">Tickets Diseño</a></li> -->
                     <!--    <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Agregar/aboutSusi">sobre SUSI</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Principal/Ombudsperson">Ombudsperson</a></li> -->
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Principal/ControlInterno">Control Interno</a></li>
                    </ul>
                </div><!-- end Pages -->
                <div id="MetricaAuthentication" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">GASTOS POR CONCEPTOS</h6>
                    </div>
                    <ul class="nav">
                        <?php if (in_array($session->get('id_perfil'),[1,7])): ?>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Inicio/Viaticos">viáticos/representación</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Inicio/ListaViaticos">Lista Viáticos</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Principal/listaGOjuridico">Lista Go</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>authentication/auth-recover-pw.html">Lista GRC</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Principal/ListaDenuncia">Lista Denuncia</a>
                        </li>
                        <?php endif ?>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Principal/SolicitudContrato">Solicitud Contrato</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                href="<?php echo base_url(); ?>index.php/Principal/ListaSolicitudContrato">Listado de Contratos</a>
                        </li>

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
                
        <!--             <li class="hidden-sm">

                  <?php if($session->get('capacitacion') == 0): ?>
                    <a class="nav-link waves-effect waves-light" onclick="registrarAsistencia();" data-toggle="tooltip" data-placement="left"  data-trigger="hover" data-original-title="Confirmar Asistencia"
                        href="javascript: void(0);" role="button" aria-haspopup="false" aria-expanded="false" id="btnSalida" >
                       <i class="em em-writing_hand"></i>
                    </a>
                   <?php endif; ?>
               
                </li>  -->
                <!--    <li class="hidden-sm">

                    <?php if (date('H:i:s') >= '16:00' && date('H:i:s') <= '17:00' && $session->get('registro_salida') !== 1): ?>
                    <a class="nav-link waves-effect waves-light" onclick="registrarSalida();" data-toggle="tooltip" data-placement="left"  data-trigger="hover" data-original-title="Checar Salida"
                        href="javascript: void(0);" role="button" aria-haspopup="false" aria-expanded="false" id="btnSalida" >
                        <i class="mdi dripicons-alarm font-20 text-danger"></i>
                    </a>
                    <?php endif; ?>
                    <?php if ($session->get('registro_salida') === 1): ?>
                    <a class="nav-link waves-effect waves-light" data-toggle="tooltip" data-placement="left"  data-trigger="hover" data-original-title="Salida Registrada"
                      role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="mdi dripicons-alarm font-20 text-success"></i>
                    </a>
                     <?php endif; ?>
                </li> -->

    <!--             <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti-bell noti-icon"></i>
                        <?php if ($session->get('id_tipo_empleado') == 1 && !$session->get('qr')): ?>
                            <span class="badge badge-danger badge-pill noti-icon-badge">1</span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-lg pt-0">

                        <h6
                            class="dropdown-item-text font-15 m-0 py-3 bg-primary text-white d-flex justify-content-between align-items-center">
                            <?= ($session->get('id_tipo_empleado') == 1 && !$session->get('qr')) ? 'Notificacion' : 'Sin Notificaciones' ?>
                            <span class="badge badge-light badge-pill"> <?= ($session->get('id_tipo_empleado') == 1 && !$session->get('qr')) ? '1' : '0' ?></span>
                        </h6>
                        <div class="slimscroll notification-list">
                        <?php if ($session->get('id_tipo_empleado') == 1 && !$session->get('qr')): ?>
                            <a target="_blank"
                                href="<?php echo base_url('index.php/Principal/imprimer_qr/' . $session->get('no_empleado')); ?>"
                                class="dropdown-item py-3">
                                <small class="float-right text-muted pl-2"></small>
                                <div class="media">
                                    <div class="avatar-md bg-primary">
                                        <i class="la la-qrcode text-white"></i>
                                    </div>
                                    <div class="media-body align-self-center ml-2 text-truncate">
                                        <h6 class="my-0 font-weight-normal text-dark">Código QR</h6>
                                        <small class="text-muted mb-0">Asistencia por QR</small>
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>
                        </div>

                    </div>
                </li> -->

                <li class="dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <?php if (!$session->foto): ?>
                            <img src="<?php echo base_url(); ?>assets/images/users/user-4.jpg" alt="profile-user"
                                class="rounded-circle" />
                        <?php endif; ?>
                        <?php if ($session->foto): ?>
                            <img src="<?php echo base_url() . $session->foto ?>" alt="profile-user"
                                class="rounded-circle" />
                        <?php endif; ?>
                        <span class="ml-1 nav-user-name hidden-sm"><?= $session->nombre_completo ?>
                            <i class="mdi mdi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="<?php echo base_url() ?>index.php/Inicio/Perfil"><i
                                class="dripicons-user text-muted mr-2"></i> Perfil</a>
                        <a class="dropdown-item" data-toggle="modal" data-animation="bounce" data-target=".hide-modal"
                            href="<?php echo base_url() ?>index.php/Inicio/CambiarPass"><i
                                class="dripicons-lock text-muted mr-2"></i> Cambiar Contraseña</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo base_url() ?>index.php/Login/cerrar"><i
                                class="dripicons-exit text-muted mr-2"></i> Salir</a>
                    </div>
                </li>


                <li class="mr-2">
                    <a href="#" class="nav-link" data-toggle="modal" data-animation="fade"
                        data-target=".modal-rightbar">
                        <i class="mdi mdi-human font-22"></i>
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
                <li>
                    <a href="<?php echo base_url(); ?>index.php/Principal/familiaSecturi" class="nav-link">
                        <span class="ml-1 p-2 bg-soft-classic nav-user-name hidden-sm rounded">¿Quienes Somos?</span>
                    </a>

                </li>
                <li class="hide-phone app-search">
                    <h6>
                        <?php
                        switch ($session->id_perfil) {
                            case 1:
                                echo '<i class="mdi mdi-account-card-details font-18"></i> Super Administrador';
                                break;
                            case 2:
                                echo '<i class="mdi mdi-account-card-details font-18"></i> Admin Financieros';
                                break;
                            case 4:
                                echo '<i class="mdi mdi-account-card-details font-18"></i> Personal Administrativo';
                                break;
                            case 5:
                                echo '<i class="mdi mdi-account-card-details font-18"></i> Enlace';
                                break;
                            case 6:
                                echo '<i class="mdi mdi-account-card-details font-18"></i> Admin Protocolo ALBA';
                                break;
                            case 7:
                                echo '<i class="mdi mdi-account-card-details font-18"></i> Personal Juridico';
                                break;
                            default:
                                echo 'PERSONAL SECTURI';
                        }
                        ?>
                    </h6>
                </li>
                <li class="hide-phone">
                   
                </li>

            </ul>
        </nav>
        <!-- end navbar-->
    </div>


    <div class="modal fade hide-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="exampleModalLabel">Cambiar Contraseña</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form id="formContrasenia">

                            <div class="text-center mb-4">
                                <div class="avatar-box thumb-xl align-self-center mr-2">
                                    <span class="avatar-title bg-light rounded-circle text-danger"><i
                                            class="fas fa-lock"></i></span>
                                </div>
                            </div>

                            <div class="input-group">
                                <input type="password" id="contrasenia" name="contrasenia" class="form-control"
                                    placeholder="Nueva Contraseña" aria-label="Password" aria-describedby="HideCard">
                                <div class="input-group-append">
                                    <button class="btn btn-gradient-primary" type="button" id="HideCard"><i
                                            class="mdi mdi-key"></i></button>
                                </div>
                            </div>
                            <br>
                            <div class="input-group">
                                <input type="password" id="new_contrasenia" name="new_contrasenia" class="form-control"
                                    placeholder="Ingresar nuevamente la contraseña" aria-label="Password"
                                    aria-describedby="HideCard">
                                <div class="input-group-append">
                                    <button class="btn btn-gradient-primary" type="button" id="HideCard"><i
                                            class="mdi mdi-key"></i></button>
                                </div>
                            </div>
                            <br>
                            <a onclick="ini.inicio.formContrasenia()" id="btnPass"
                                class="btn btn-primary text-white">Guardar</a>

                        </form>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!--modal -->
    <!-- Modal de Soporte TI -->
    <div class="modal fade" id="supportModal" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel"
        aria-hidden="true">
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
                                    <button class="option-btn asistencia-btn"
                                        onclick="ini.inicio.selectCategory('asistencia')">
                                        <i class="fas fa-tools"></i> ASISTENCIA
                                    </button>
                                    <button class="option-btn accesorios-btn"
                                        onclick="ini.inicio.selectCategory('accesorios')">
                                        <i class="fas fa-keyboard"></i> ACCESORIOS
                                    </button>
                                    <button class="option-btn plataformas-btn"
                                        onclick="ini.inicio.selectCategory('plataformas')">
                                        <i class="fas fa-globe"></i> PLATAFORMAS
                                    </button>
                                    <button class="option-btn impresoras-btn"
                                        onclick="ini.inicio.selectCategory('impresoras')">
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
                        <textarea class="form-control" id="problemDescription"
                            placeholder="Describe tu problema en detalle..." rows="3"></textarea>
                        <button class="btn btn-primary mt-2 float-right" onclick="submitProblem()">
                            <i class="fas fa-paper-plane mr-2"></i> Enviar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--fin modal -->

    <div class="modal modal-rightbar fade" tabindex="-1" role="dialog" aria-labelledby="MetricaRightbar"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="MetricaRightbar">Accesibilidad</h5>
                    <button type="button" class="btn btn-sm btn-soft-primary btn-circle btn-square" data-dismiss="modal"
                        aria-hidden="true">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="activity">
                            <!-- Aumentar Texto -->
                            <div class="activity-info">
                                <div class="icon-info-activity">
                                    <i class="mdi dripicons-preview bg-soft-success"></i>
                                </div>
                                <div class="activity-info-text mb-2">
                                    <div class="mb-1">
                                        <a href="javascript:void(0)" class="m-0 w-75 accessibility-option"
                                            data-action="increase-text">A++</a>
                                    </div>
                                    <p class="text-muted mb-2 text-truncate">Aumentar de Texto.</p>
                                </div>
                            </div>

                            <!-- Disminuir Texto -->
                            <div class="activity-info">
                                <div class="icon-info-activity">
                                    <i class="mdi dripicons-preview bg-soft-pink"></i>
                                </div>
                                <div class="activity-info-text mb-2">
                                    <div class="mb-1">
                                        <a href="javascript:void(0)" class="m-0 w-75 accessibility-option"
                                            data-action="decrease-text">A--</a>
                                    </div>
                                    <p class="text-muted mb-2 text-truncate">Disminuir Texto</p>
                                </div>
                            </div>

                            <!-- Escala de Grises -->
                            <div class="activity-info">
                                <div class="icon-info-activity">
                                    <i class="mdi mdi-postage-stamp bg-soft-purple"></i>
                                </div>
                                <div class="activity-info-text mb-2">
                                    <div class="mb-1">
                                        <a href="javascript:void(0)" class="m-0 w-75 accessibility-option"
                                            data-action="toggle-grayscale">Escala de Grises</a>
                                    </div>
                                    <p class="text-muted mb-2 text-truncate">Matices de gris</p>
                                </div>
                            </div>

                            <!-- Fondo Claro -->
                            <div class="activity-info">
                                <div class="icon-info-activity">
                                    <i class="mdi mdi-check-network-outline"></i>
                                </div>
                                <div class="activity-info-text mb-2">
                                    <div class="mb-1">
                                        <a href="javascript:void(0)" class="m-0 w-75 accessibility-option"
                                            data-action="normal-contrast">Fondo Claro</a>
                                    </div>
                                    <p class="text-muted mb-2 text-truncate">Contraste Positivo</p>
                                </div>
                            </div>

                            <!-- Fondo Oscuro -->
                            <div class="activity-info">
                                <div class="icon-info-activity">
                                    <i class="mdi mdi-check-network"></i>
                                </div>
                                <div class="activity-info-text mb-2">
                                    <div class="mb-1">
                                        <a href="javascript:void(0)" class="m-0 w-75 accessibility-option"
                                            data-action="high-contrast">Fondo Oscuro</a>
                                    </div>
                                    <p class="text-muted mb-2 text-truncate">Contraste Negativo</p>
                                </div>
                            </div>

                            <!-- Fuente Legible -->
                            <div class="activity-info">
                                <div class="icon-info-activity">
                                    <i class="mdi dripicons-zoom-in bg-soft-success"></i>
                                </div>
                                <div class="activity-info-text mb-2">
                                    <div class="mb-1">
                                        <a href="javascript:void(0)" class="m-0 w-75 accessibility-option"
                                            data-action="readable-font">Fuente Legible</a>
                                    </div>
                                    <p class="text-muted mb-2 text-truncate">Fuente strong</p>
                                </div>
                            </div>

                            <!-- Restablecer -->
                            <div class="activity-info">
                                <div class="icon-info-activity">
                                    <i class="mdi mdi-refresh bg-soft-warning"></i>
                                </div>
                                <div class="activity-info-text mb-2">
                                    <div class="mb-1">
                                        <a href="javascript:void(0)" class="m-0 w-75 accessibility-option"
                                            data-action="reset-all">Restablecer</a>
                                    </div>
                                    <p class="text-muted mb-2 text-truncate">Configuración original</p>
                                </div>
                            </div>
                        </div><!--end activity-->
                    </div>
                </div><!--end modal-body-->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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

            switch (category) {
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
        function registrarSalida() {
            $.ajax({
                type: "GET",
                url: base_url + "index.php/Login/registrarSalida",
                dataType: "json",
                beforeSend: function () {
                    $('#btnSalida').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                },
                success: function (response) {
                    if (response.error) {
                        Swal.fire("¡Algo Salio Mal!", 'Favor de registrar la Salida en el Checador', "error");
                        return
                    }
                    Swal.fire("¡Hora de ir a Casa!", 'Registro de Salida Guardado con Exito', "success");
                    $('#btnSalida').prop('disabled', true).html('<i class="mdi dripicons-alarm font-20 text-success"></i>');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);


                },

                error: function (jqXHR, textStatus, errorThrown) {
                    Swal.fire("Error en la conexión", textStatus, "error");
                    console.error('Error:', textStatus, errorThrown);
                }
            });

        }
        function registrarAsistencia() {
            $.ajax({
                type: "GET",
                url: base_url + "index.php/Usuario/registrarSalida",
                dataType: "json",
                beforeSend: function () {
                    $('#btnSalida').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                },
                success: function (response) {
                    if (response.error) {
                        Swal.fire("¡Algo Salio Mal!", 'Favor de registrar la Salida en el Checador', "error");
                        return
                    }
                    Swal.fire("¡Asistencia Confirmada!", 'Registro Guardado con Exito', "success");
                    $('#btnSalida').prop('disabled', true).html('<i class="em em-writing_hand text-success"></i>');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);


                },

                error: function (jqXHR, textStatus, errorThrown) {
                    Swal.fire("Error en la conexión", textStatus, "error");
                    console.error('Error:', textStatus, errorThrown);
                }
            });

        }

    </script>