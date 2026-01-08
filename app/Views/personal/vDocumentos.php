 <?php $session = \Config\Services::session(); ?>
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url() ?>assets/images/favicon.ico">

        <!--calendar css-->
      	<link href="<?php echo base_url() ?>plugins/fullcalendar/packages/core/main.css" rel="stylesheet" />
      	<link href="<?php echo base_url() ?>plugins/fullcalendar/packages/daygrid/main.css" rel="stylesheet" />
      	<link href="<?php echo base_url() ?>plugins/fullcalendar/packages/bootstrap/main.css" rel="stylesheet" />
      	<link href="<?php echo base_url() ?>plugins/fullcalendar/packages/timegrid/main.css" rel="stylesheet" />
       	<link href="<?php echo base_url() ?>plugins/fullcalendar/packages/list/main.css" rel="stylesheet" />

        <!-- App css -->
        <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() ?>assets/css/jquery-ui.min.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

             <!-- Plugins css -->
        <link href="<?php echo base_url() ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
        <link href="<?php echo base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() ?>plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() ?>plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />


  

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
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">doc.</a></li>
                                        <li class="breadcrumb-item active">Normatividad</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Normateca</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="dropdown d-inline-block float-right mt-n2">
                                        <a class="nav-link dropdown-toggle arrow-none" id="drop1" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v font-18 text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="drop1" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(38px, 38px, 0px);">
                                            <a class="dropdown-item" href="#">Create Folder</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                            <a class="dropdown-item" href="#">Settings</a>
                                        </div>
                                    </div>      
                                    <h4 class="header-title mt-0 mb-3">Categorias</h4>
                                    <div class="files-nav">                                     
                                        <div class="nav flex-column nav-pills" id="files-tab" aria-orientation="vertical">
                                            <a class="nav-link active" id="files-projects-tab" data-toggle="pill" href="#files-projects" aria-selected="true">
                                                <span class="mr-3 text-warning d-inline-block">📁</span>
                                                <div class="d-inline-block align-self-center">
                                                    <h5 class="m-0">Normateca</h5>
                                                    <small>80GB/200GB En uso</small>                                                    
                                                </div>
                                            </a>
                                            <a class="nav-link" id="files-pdf-tab" data-toggle="pill" href="#files-pdf" aria-selected="false">
                                                <span class="mr-3 text-warning d-inline-block">📁</span>
                                                <div class="d-inline-block align-self-center">
                                                    <h5 class="m-0">Normatividad Interna</h5>
                                                    <small>80GB/200GB En uso</small>                                                    
                                                </div>
                                            </a>
                                            <a class="nav-link  align-items-center" id="files-documents-tab" data-toggle="pill" href="#files-documents" aria-selected="false">
                                                <span class="mr-3 text-warning d-inline-block">📁</span>
                                                <div class="d-inline-block align-self-center">
                                                    <h5 class="m-0">Formatos</h5>
                                                    <small>80GB/200GB En uso</small>                                                    
                                                </div>                                                
                                                <small class="badge badge-warning ml-auto">8</small>
                                            </a>
                                          <!--   <a class="nav-link mb-0"  href="#" data-toggle="modal" data-animation="bounce" data-target=".hide-modal">
                                                <span class="mr-3 text-warning d-inline-block">🔒</span>
                                                <div class="d-inline-block align-self-center">
                                                    <h5 class="m-0">Archivos Alta Dirección</h5>
                                                    <small>80GB/200GB En uso</small>                                                    
                                                </div>                                                                                         
                                            </a> -->
                                        </div>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->

                     
                        </div><!--end col-->

                        <div class="col-lg-9">
                            <div class="">                                    
                                <div class="tab-content" id="files-tabContent">
                                  
                                    <div class="tab-pane fade show active" id="files-projects">
                                                                                
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">

                                                        <h4 class="mt-0 header-title">Normateca</h4>
                                                        <p class="text-muted mb-4">Seccion 1</p>

                                                        <div class="custom-dd dd" id="nestable_list_1">
                                                            <ol class="dd-list">
                                                              
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Acuerdo Secretarial-004-2025.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Acuerdo Secretarial-004-2025
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                              
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Programa de Gobierno 2024-2030.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Programa de Gobierno 2024-2030
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                  <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Programa Estatal de Turismo 2025-2030_.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Programa Estatal de Turismo 2025-2030
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                               
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/PROSECTUR__2025_2030.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Programa Sectorial de Turismo Federal 2025-2030
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                 <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Plan_de_Trabajo_Anual_2025.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Plan de Trabajo Anual 2025
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                 <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Programa Anual de Mejora Continua 2025.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Programa Anual de Mejora Continua 2025
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                            
                                                           
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Actualizacion_Programa_de_Gobierno_2018-2024-db52.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Acuerdo de Creación del Observatorio Turístico del Estado de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Constitucion_Politica_de_los_Estados_Unidos_Mexicanos_-d214.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Constitución Política de los Estados Unidos Mexicanos
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Constitucion_Politica_ra_para_el_Estado_de_Guanajuato-3710.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Constitución Política ra para el Estado de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Festivales_Internacionales_y_Eventos_Especiales_para_el_Ejercicio_Fiscal_de_2022-f83b.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Festivales Internacionales y Eventos Especiales para el Ejercicio Fiscal de 2022
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_de_Archivos_Generales_del_Estado_y_los_Municipios_de_Guanajuato-909e.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Ley de Archivos Generales del Estado y los Municipios de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_de_Contrataciones_Publicas_para_el_Estado_de_Guanajuato-0195.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Ley de Contrataciones Públicas para el Estado de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_de_Hacienda_para_el_Estado_de_Guanajuato-06de.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Ley de Hacienda para el Estado de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_de_Hospedaje_a_traves_de_Plataformas_Digitales_del_Estado_de_Guanajuato-d109.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Ley de Hospedaje a través de Plataformas Digitales del Estado de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_de_Mejora_Regulatoria_para_el_Estado_de_Guanajuato-6300.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Ley de Mejora Regulatoria para el Estado de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_de_Ingresos_para_el_Estado_de_Guanajuato_para_el_Ejercicio_Fiscal_2021-20c4.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Ley de Ingresos para el Estado de Guanajuato para el Ejercicio Fiscal 2021
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_de_Proteccion_de_Datos_Personales_en_Posesion_de_Sujetos_Obligados_para_el_Estado_de_Guanajuato-6b9f.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Ley de Protección de Datos Personales en Posesión de Sujetos Obligados para el Estado de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_de_Transparencia_y_Acceso_a_la_Informacion_Publica_para_el_Estado_de_Guanajuato-bb63.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Ley de Transparencia y Acceso a la Información Pública para el Estado de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_de_Turismo_para_el_Estado_de_Guanajuato_y_sus_Municipios-1a2a.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Ley de Turismo para el Estado de Guanajuato y sus Municipios
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_del_Sistema_Estatal_Anticorrupcion_de_Guanajuato-9673.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Ley del Sistema Estatal Anticorrupción de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_del_Trabajo_de_los_Servidores_Publicos_al_Servicio_del_Estado_y_los_Municipios_-2e31.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Ley del Trabajo de los Servidores Públicos al Servicio del Estado y los Municipios
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_General_de_Turismo-cfa3.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                         Ley General de Turismo
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_Organica_del_Poder_Ejecutivo_para_el_Estado_de_Guanajuato-b646.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                        Ley Orgánica del Poder Ejecutivo para el Estado de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_del_Trabajo_de_los_Servidores_Publicos_al_Servicio_del_Estado_y_los_Municipios_-2e31.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Ley del Trabajo de los Servidores Públicos al Servicio del Estado y los Municipios
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_para_el_Fomento_de_la_Industria_Cinematografica_y_Audiovisual_del_Estado_de_Guanajuato-0c6a.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Ley para el Ejercicio y Control de los Recursos Públicos para el Estado y los Municipios del Estado de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Ley_para_el_Fomento_de_la_Industria_Cinematografica_y_Audiovisual_del_Estado_de_Guanajuato-0c6a.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Ley para el Fomento de la Industria Cinematográfica y Audiovisual del Estado de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Lineamientos_de_Servicios_Generales_de_la_Administracion_Publica_Estatal-0479.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Lineamientos de Servicios Generales de la Administración Pública Estatal
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Lineamientos_de_Tecnologias_de_la_Informacion_y_Comunicaciones_de_la_Administracion_Publica_Estatal-3f3c.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Lineamientos de Tecnologías de la Información y Comunicaciones de la Administración Pública Estatal
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Lineamientos_del_Proyecto_Centro_de_Atencion_a_Visitantes_del_Estado_para_el_Ejercicio_Fiscal_de_2022-6a6d.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Lineamientos del Proyecto Centro de Atención a Visitantes del Estado para el Ejercicio Fiscal de 2022
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Lineamientos_del_Proyecto_Gestion_del_Desarrollo_Turistico_Regional_Sustentable_para_el_Ejercicio_Fiscal_de_2022-40f2.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Lineamientos del Proyecto Gestión del Desarrollo Turístico Regional Sustentable para el Ejercicio Fiscal de 2022
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Lineamientos_Generales_de_Control_Interno_para_el_Poder_Ejecutivo_del_Estado_de_Guanajuato-0f86.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Lineamientos Generales de Control Interno para el Poder Ejecutivo del Estado de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Lineamientos_Generales_de_Control_Patrimonial_de_la_Administracion_Publica_Estatal-c171.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Lineamientos Generales de Control Patrimonial de la Administración Pública Estatal
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Lineamientos_Generales_para_el_Otorgamiento_y_Recepcion_de_Garantias_a_Favor_del_Gobierno_Estatal-8a80.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Lineamientos Generales para el Otorgamiento y Recepción de Garantías a Favor del Gobierno Estatal
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Lineamientos_para_el_Registro_Estatal_de_Turismo_para_el_Estado_de_Guanajuato-40f9.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Lineamientos para el Registro Estatal de Turismo para el Estado de Guanajuato
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Manual_para_la_elaboracion_y_formalizacion_de_contratos_y_convenios_-4e2e.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Manual para la elaboración y formalización de contratos y convenios
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Reglamento_de_la_Ley_de_Turismo_para_el_Estado_de_Guanajuato_y_sus_Municipios-501c.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Reglamento de la Ley de Turismo para el Estado de Guanajuato y sus Municipios
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Reglamento_de_la_Ley_General_de_Turismo-f621.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Reglamento de la Ley General de Turismo
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/02-Lineamientos-Inversión.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                         Lineamientos Generales para la Aplicación de Recursos en Materia de Proyectos de Inversión 2025
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="1">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/10-lineamientos-GpR.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                         Lineamientos Generales de Gestión para Resultados 2025
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                            </ol>
                                                        </div><!--nastable-list-1-->    
                                                    </div><!--end card-body-->
                                                </div><!--end card-->
                                            </div> <!-- end col -->

                                        
                                        </div> <!-- end row -->  
                                    </div><!--end tab-pane-->

                                    <div class="tab-pane fade" id="files-pdf">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">

                                                        <h4 class="mt-0 header-title">Normatividad interna</h4>
                                                        <p class="text-muted mb-4">Seccion 2</p>

                                                        <div class="custom-dd dd" id="nestable_list_1">
                                                            <ol class="dd-list">
                                                               
                                                                <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/1-Reglamento-Interior-SEDETUR-2007-404e.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Reglamento-Interior-SEDETUR-2007
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/2-Reglamento-SEDETUR-2007-Modificacion-7403.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Reglamento-SEDETUR-2007-Modificacion
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/3-Reglamento-Interior-SEDETUR-2011-Modificacion-2-c75d.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Reglamento-Interior-SEDETUR-2011-Modificacion-2
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Codigo_de_Conducta_de_la_Secretaria_de_Turismo-8f56.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Código de Conducta de la Secretaría de Turismo
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Consideraciones_de_sustentabildad_para_conferencias_talleres_y_reuniones-d8fa.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Consideraciones de sustentabildad para conferencias, talleres y reuniones
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/GUIA-DE-ACTUACION-2O17-efab.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       GUIA-DE-ACTUACION-2O17
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Informe_de_cumplimiento_del_PADA_2023-d8ff.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Informe de cumplimiento del PADA 2023
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Lineamientos_de_Asistencia_2025-be68.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Lineamientos de Asistencia 2025
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Lineamientos_Recursos_Destinados_a_Acciones_y_Proyectos_de_Inversion-b15b.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Lineamientos Recursos Destinados a Acciones y Proyectos de Inversión
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Manual_de_Induccion_Interno_SECTUR-3889.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Manual de Inducción Interno SECTUR
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Manual_de_Integracion_y_Funcionamiento_del_CISMA-66ad.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Manual de Integración y Funcionamiento del CISMA
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Manual_de_Organizacion_SECTUR-d937.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Manual de Organización SECTUR
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                             
                                                                <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Manual_de_Procesos_y_Procedimientos_2024-1086.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Manual de Procesos y Procedimientos 2024
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                                <li class="dd-item" data-id="2">
                                                                    <a target="_blank" href="<?= base_url().'assets/documentos/Manual_para_la_Elaboracion_y_Formalizacion_de_Contratos_y_Convenios-e6a1.pdf' ?>">
                                                                        <div class="dd-handle">
                                                                       Manual para la Elaboración y Formalización de Contratos y Convenios
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                             
                                                              
                                                              
                                                            </ol>
                                                        </div><!--nastable-list-1-->    
                                                    </div><!--end card-body-->
                                                </div><!--end card-->
                                            </div> <!-- end col -->

                                        
                                        </div> <!-- end row -->  
                                    </div><!--end tab-pane-->

                                    <div class="tab-pane fade" id="files-documents">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">

                                                        <h4 class="mt-0 header-title">Formatos</h4>
                                                        <p class="text-muted mb-4">Dirección General Jurídica</p>

                                                        <div class="custom-dd dd" id="nestable_list_1">
                                                            <ol class="dd-list">
                                                               <a target="_blank" href="<?= base_url()?>assets/documentos/DAJ_3_Autorizacion_tratamiento_datos_personales-338f.xlsx" >
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                            Autorizacion de Tratamiento de Datos
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-DAJ-09-2020_Formato_Solicitud_de_Convenios-40e5.xlsx" >
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                    Formato Solicitud de Convenios
                                                                        </div>
                                                                    </li>  
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-DAJ-10-2020_Solicitud_de_Contratos_De_Adquisicion-97a6.xlsx" >
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Solicitud de Contratos de Adquisición
                                                                        </div>
                                                                    </li> 
                                                                </a> 
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-DAJ-11-2020_Solicitud_de_Contratos-07eb.xlsx">
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Solicitud de Contratos
                                                                    </div>
                                                                </li>  
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-DAJ-12-2022_Solicitud_de_Honorarios-5763.xlsx">
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                       Solicitud de Honorarios
                                                                    </div>
                                                                </li>
                                                                 </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-DAJ-12-2022_Solicitud_de_Honorarios-f9f3.xlsx">
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                            Autorización de Tratamiento de Datos Personales-HONORARIOS
                                                                        </div>
                                                                    </li> 
                                                                 </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-A-07-2024 Formato_Inventario_Documental.xlsx">
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Inventario
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                </ol>
                                                            <ol class="dd-list">
                                                                 <p class="text-muted mb-4">Coordinación de Planeación</p>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-CPE-02-2023__Formato_de_Verificacion_Alineacion_de_Informacion_Estrategica-030c.xlsx">
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                            Formato de Verificación Alineación de Información Estratégica
                                                                        </div>
                                                                    </li> 
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-DPE-01-2021_Formato_de_Ajuste_de_Metas_-0977.xlsx">
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Formato de Afectación a Metas de Proyectos de Inversión
                                                                        </div>
                                                                    </li> 
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/I-CPE-03-2023__Instructivo_FM-8d90.pdf">
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Instructivo FM
                                                                    </div>
                                                                </li>
                                                                 </a> 
                                                          
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/C-13- a)_Seguimiento_SED_proyectos_Q.pdf">  
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Guía de Operación Etapa Seguimiento SED
                                                                    </div>
                                                                </li> 
                                                                </a>
                                                            </ol>
                                                            <ol class="dd-list">
                                                                 <p class="text-muted mb-4">Coodinación de Recursos Financieros</p>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/Disposiciones_Administrativas_SECTUR_2025-70b2.pdf">  
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Disposiciones Administrativas SECTURI 2025
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-RF-01-2026 Oficios de Comisión.xlsx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Oficios de Comisión
                                                                        </div>
                                                                    </li> 
                                                                 </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-RF-02-2026 Encabezado Factura.xlsx" >
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Encabezado Factura
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-RF-03-2026 Evidencia Fotográfica.xlsx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Evidencia Fotográfica
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-RF-04-2026 Anexo 2.- Reporte de Integración Documental.xlsx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Anexo 2.- Reporte de Integración Documental
                                                                        </div>
                                                                    </li> 
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-RF-05-2026 Orden de Ministración (Hoja Verde).docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Orden de Ministración (Hoja Verde)
                                                                        </div>
                                                                    </li>
                                                                </a> 
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-RF-06-2026 Gasto a Reserva de Comprobar.xlsm" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Gasto a Reserva de Comprobar
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-RF-07-2026 SOLICITUD GRC.xlsm" >  
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        SOLICITUD GRC
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-RF-08-2026 Pagos a Tercero.xlsm" >  
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Pagos a Tercero
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-RF-09-2026 Gasto de Operación.xlsx" >  
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Gasto de Operación
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-RF-10-2026 Formato Conformidad Producto Recibido.xlsx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Formato Conformidad Producto Recibido
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/Formato-de-Afectacion-Presupuestal_INTR V2026.xlsx" >  
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Formato de Afectación Presupuestal
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-RF-12-2026 Pagos a Tercero Refrendo de Recursos.xlsm" >  
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Pagos a Tercero Refrendo de Recursos
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-RF-13-2026 Viáticos por Persona.xlsm" >  
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Viáticos por Persona
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/F-RF-15-2026 Solicitud de Operaciones Financieras.xlsx" >  
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Solicitud de Operaciones Financieras
                                                                    </div>
                                                                </li>
                                                                </a>
                                                                  <a target="_blank" href="<?= base_url()?>assets/documentos/L-RF-13-2026 Lineamientos Generales de Racionalidad Austeridad y Disciplina Presupuestal 2026.pdf" >  
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Lineamientos Generales de Racionalidad Austeridad y Disciplina Presupuestal 2025
                                                                    </div>
                                                                </li>
                                                                </a> 
                                                                  <a target="_blank" href="<?= base_url()?>assets/documentos/L-RF-14-2026 Lista de Asistencia.xlsx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Lista de Asistencia
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                  <a target="_blank" href="<?= base_url()?>assets/documentos/L-RF-16-2026 Clasificador por Objeto del Gasto.pdf" >  
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Clasificador por Objeto del Gasto
                                                                        </div>
                                                                    </li>
                                                                </a> 
                                                                  <a target="_blank" href="<?= base_url()?>assets/documentos/O-RF-18-2026 Oficio de Liberación de Trámite de Pago a Tercero.docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Oficio de Liberación de Trámite de Pago
                                                                        </div>
                                                                    </li>
                                                                </a> 
                                                                  <a target="_blank" href="<?= base_url()?>assets/documentos/O-RF-17-2026 Oficio de Liberación de Trámite de Pago a Tercero ALTA DE USUARIO SERVICIOS PROFESIONALES.docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Oficio de Liberación de Trámite de Pago a Tercero ALTA DE USUARIO SERVICIOS PROFESIONALES
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                  <a target="_blank" href="<?= base_url()?>assets/documentos/O-RF-21-2026 XXX-GASTOS -00XX-XXX-38X0-20XX.docx" >  
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        XXX-GASTOS-00XX-XXX-P38X0-20XX
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                  <a target="_blank" href="<?= base_url()?>assets/documentos/F-RF-05-2025_Orden_de_Ministracion.docx" >  
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Orden de Ministración de Viáticos en el Extranjero
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                <a target="_blank" href="<?= base_url()?>assets/documentos/O-RF-19-2026 Oficio de Liberación de Gasto GO y GRC.docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Oficio de Liberación de Gasto GO y GRC
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/P-RF-20-2026 PROCESO CANCELACION CFDI.pdf" >  
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        PROCESO CANCELACION CFDI
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/M-RF-16-2026 Datos de Facturación.pdf" >  
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Datos de Facturacion
                                                                        </div>
                                                                    </li>
                                                                </a> 
                                                            </ol>
                                                            <ol class="dd-list">
                                                                 <p class="text-muted mb-4">Coordinación de Recursos Materiales</p>
                                                                  <a target="_blank" href="<?= base_url()?>assets/documentos/F-CRMG-02-2020_Solicitud_de_Mantenimiento_Vehicular-4388.xlsx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Solicitud de Mantenimiento Vehicular
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/BITACORA_DE_REVISION_DE_NIVELES-7c5f.xlsx" >  
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Bitácora de Revisión de Niveles
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-CRMSG-04-2021_INVESTIGACION_DE_MERCADO-a766.xlsx" >  
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Investigación de Mercado
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-CRMSG-04-2021__ANEXO_4_Investigacion_de_Mercado_-a8a8.xlsx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Investigación de Mercado Servicios
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-CRMSG-04-2021_Investigacion_de_Mercado__Federal_-f397.xlsx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Investigación de Mercado Adquisiciones
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                 
                                                            </ol>
                                                            <ol class="dd-list">
                                                                 <p class="text-muted mb-4">Archivo</p>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-A-01-2024 señaletica para espacios AT.pptx" >
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Señaletica para espacios AT
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                  
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-A-06-2024 Formato para lomos de carpeta.pptx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Formatos para lomos de carpetas
                                                                        </div>
                                                                </li>
                                                                 </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-A-02-2024 Pestaña de expediente.pptx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Pestaña de Expediente
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-DAJ-04-2020_Etiqueta_Caja_de_Archivo-3203.docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Etiqueta de caja at
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-A-07-2024 Formato_Inventario_Documental.xlsx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Formato inventario documental
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-A-08-2024 Instructivo_Llenado_Portada.docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Instructivo llenado portada
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-A-09-2024  Portada_archivística.docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Portada archivística
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-A-10-2024  Formato_Registro_de_préstamo_y_o_consulta.xlsx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Formato registro de préstamo y/o consulta
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-A-11-2024  Formato_ listado de documentos ejemplo.docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Formato listado de documentos ejemplo
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/R-A-01-2024 Registros de correspondencia.xlsx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Registros de correspondencia
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/U-A-01-2024 Unidad de correspondencia_2023.pdf" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Unidad de correspondencia 2023
                                                                        </div>
                                                                    </li>
                                                                 </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/IN-DAJ-07-2020_Instrumentos_de_Clasificacion_Archivistica-4407.pdf" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Instrumentos de Clasificación Archivistica
                                                                        </div>
                                                                    </li>
                                                                </a> 
                                                            </ol>
                                                             <ol class="dd-list">
                                                                 <p class="text-muted mb-4">Coordinación de Recursos Humanos</p>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-RH-01-2025_Ejemplo_Entregable-8d10.docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Ejemplo Entregable
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-RH-02-2025_Solicitud_de_Beca_para_Hijos-0597.docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Solicitud de Beca para Hijos
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/R-RH-03-2025_Seguro_de_Gastos_Medicos_Mayores-fef2.pdf" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Seguro Gastos Médicos Mayores
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-RH-04-2025_Carta_Compromiso_CT-352a.docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Carta Compromiso CT
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-RH-05-2025_Solicitud_Apoyo_para_Estudios-b422.docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Solicitud Apoyo para Estudios
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-RH-06-2025_Justificante_Incidencias-2b64.xlsx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Justificante Incidencias
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/S-RH-15-2021_LICENCIA_PATERNIDAD-841a.docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Licencia por Paternidad
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-RH-08-2025_Licencia_por_Lactancia-2fb6.docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Licencia por Lactancia
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/F-RH-09-2025_Permiso_Economico-8dce.docx" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Permiso Económico
                                                                        </div>
                                                                    </li> 
                                                                </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/R-RH-01-2025_Requisitos_Apoyo_para_estudios_y_formacion_basica-9b36.pdf" >
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Requisitos Apoyo para estudios y formación básica
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                                 <a target="_blank" href="<?= base_url()?>assets/documentos/R-RH-02-2025_Solicitud_de_Beca_para_Descendientes-cef8.pdf" > 
                                                                    <li class="dd-item" data-id="2">
                                                                        <div class="dd-handle">
                                                                        Becas para Descendientes
                                                                        </div>
                                                                    </li>
                                                                </a>
                                                               
                                                            </ol>
                                                        </div><!--nastable-list-1-->    
                                                    </div><!--end card-body-->
                                                </div><!--end card-->
                                            </div> <!-- end col -->

                                        
                                        </div> <!-- end row --> 
                                    </div><!--end tab-pen-->

                                    <div class="tab-pane fade" id="files-hide">
                                        <h4 class="mt-0 header-title mb-3">Hide</h4>
                                    </div><!--end tab-pane-->
                                </div>  <!--end tab-content-->                                                                              
                            </div><!--end card-body-->
                        </div><!--end col-->
                    </div><!--end row-->

                </div><!-- container -->

                 <!-- Modal -->

                 <div class="modal fade hide-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title mt-0" id="exampleModalLabel">Engrese Contraseña</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="p-3">
                                    <form class="form-horizontal" action="index.html">
            
                                        <div class="text-center mb-4">
                                            <div class="avatar-box thumb-xl align-self-center mr-2">
                                                <span class="avatar-title bg-light rounded-circle text-danger"><i class="fas fa-lock"></i></span>
                                            </div>
                                        </div>

                                        <div class="input-group">
                                            <input type="password" class="form-control" placeholder="Contraseña" aria-label="Password" aria-describedby="HideCard">
                                            <div class="input-group-append">
                                                <button class="btn btn-gradient-primary" type="button" id="HideCard"><i class="mdi mdi-key"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                <!--start rightbar-->

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
                                        <a class="nav-link" data-toggle="tab" href="#SettingsTab" role="tab">Settings</a>
                                    </li>
                                </ul>                                
                                
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active " id="ActivityTab" role="tabpanel">
                                        <div class="bg-light mx-n3">
                                            <img src="<?php base_url() ?>assets/images/small/img-1.gif" alt="" class="d-block mx-auto my-4" height="180">
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
                                                    <img src="<?php base_url() ?>assets/images/widgets/project3.jpg" alt="" class="thumb-lg rounded-circle">                                      
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

        




        <!-- jQuery  -->
 <script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>

 <script src='<?php echo base_url() ?>plugins/fullcalendar/packages/core/main.js'></script>
 <script src='<?php echo base_url() ?>plugins/fullcalendar/packages/daygrid/main.js'></script>
 <script src='<?php echo base_url() ?>plugins/fullcalendar/packages/timegrid/main.js'></script>
 <script src='<?php echo base_url() ?>plugins/fullcalendar/packages/interaction/main.js'></script>
 <script src='<?php echo base_url() ?>plugins/fullcalendar/packages/list/main.js'></script>



        <script src="<?php echo base_url() ?>plugins/apexcharts/apexcharts.min.js"></script> 

        <!-- Plugins js -->
        <script src="<?php echo base_url() ?>plugins/moment/moment.js"></script>
        <script src="<?php echo base_url() ?>plugins/daterangepicker/daterangepicker.js"></script>
        <script src="<?php echo base_url() ?>plugins/select2/select2.min.js"></script>
        <script src="<?php echo base_url() ?>plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <script src="<?php echo base_url() ?>plugins/timepicker/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo base_url() ?>plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="<?php echo base_url() ?>plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>

        <script src="<?php echo base_url() ?>assets/pages/jquery.forms-advanced.js"></script>
        
        <!-- App js -->
        <script src="<?php echo base_url() ?>assets/js/app.js"></script>


        <script src="<?php echo base_url() ?>assets/js/jquery-ui.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/metismenu.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/waves.js"></script>
        <script src="<?php echo base_url() ?>assets/js/feather.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/jquery.slimscroll.min.js"></script>


        <script>

        // Inicialización de timepickers
  
// Inicializar el mapa
var map = L.map('map').setView([20.956950, -101.360316], 16); // Coordenadas de Guanajuato, zoom 17

// Añadir capa de OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Añadir marcador
var marker = L.marker([20.956950, -101.360316]).addTo(map)
    .bindPopup('SECTURI')
    .openPopup();

 var polygon = L.polygon([
    [20.956965, -101.364241],
    [20.958276, -101.358666],
    [20.954891, -101.359349]
]).addTo(map); 
/* var circle = L.circle([20.956950, -101.360316], {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.5,
    radius: 1000
}).addTo(map); */
        </script>
