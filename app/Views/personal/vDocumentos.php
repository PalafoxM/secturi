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
                                                                    <div class="dd-handle">
                                                                       Lineamientos Centro de Atención de Visitantes para el Ejercicio Fiscal de 2025
                                                                    </div>
                                                                </li>
                                                                <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                        Lineamientos Gestión de Desarrollo Turístico Regional Sustentable para el Ejercicio Fiscal de 2025
                                                                    </div>
                                                                </li>
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                        Reglas de Operación Apoyo a Festivales Internacionales y Eventos Especiales para el Ejercicio Fiscal de 2025
                                                                    </div>
                                                                </li>  
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                        Reglas de Operación Guanajuato, ¡Sí Sabe! para el Ejercicio Fiscal de 2025
                                                                    </div>
                                                                </li>  
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                        Reglas de Operación Modelo de Excelencia Turística para el Ejercicio Fiscal de 2025
                                                                    </div>
                                                                </li>  
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                        Reglas de Operación Fondo para los Destinos Turísticos de Guanajuato para el Ejercicio Fiscal del 2025
                                                                    </div>
                                                                </li>
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                        Reglas de Operación Turismo al 100
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                       Programa Anual de Desarrollo Archivístico 2025
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                       Informe de cumplimiento del PADA 2024
                                                                    </div>
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
                                                                <li class="dd-item" data-id="1">
                                                                    <div class="dd-handle">
                                                                       Manual de Procesos y Procedimientos 2024
                                                                    </div>
                                                                </li>
                                                                <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                        Política de Igualdad Laboral y No Discriminación
                                                                    </div>
                                                                </li>
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                        Protocolo para prevenir y atender la violencia
                                                                    </div>
                                                                </li>  
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                        Lineamientos de Asistencia 2025
                                                                    </div>
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
                                                               
                                                                <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                        Autorizacion de Tratamiento de Datos
                                                                    </div>
                                                                </li>
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                   Formato Solicitud de Convenios
                                                                    </div>
                                                                </li>  
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                     Solicitud de Contratos de Adquisición
                                                                    </div>
                                                                </li>  
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Solicitud de Contratos
                                                                    </div>
                                                                </li>  
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                       Solicitud de Honorarios
                                                                    </div>
                                                                </li>
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                        Autorización de Tratamiento de Datos Personales-HONORARIOS
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Inventario
                                                                    </div>
                                                                </li> 
                                                                </ol>
                                                            <ol class="dd-list">
                                                                 <p class="text-muted mb-4">Coordinación de Planeación</p>
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                        Formato de Verificación Alineación de Información Estratégica
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                       Formato de Afectación a Metas de Proyectos de Inversión
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Instructivo FM
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Aplicabilidad y Ejemplos
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Guía de Operación Etapa Seguimiento SED
                                                                    </div>
                                                                </li> 
                                                                
                                                            </ol>
                                                            <ol class="dd-list">
                                                                 <p class="text-muted mb-4">Coodinación de Recursos Financieros</p>
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                       Disposiciones Administrativas SECTURI 2025
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Oficios de Comisión
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Encabezado Factura
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                     Evidencia Fotográfica
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                     Anexo 2.- Reporte de Integración Documental
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                     Orden de Ministración (Hoja Verde)
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Gasto a Reserva de Comprobar
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                     SOLICITUD GRC
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Pagos a Tercero
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Gasto de Operación
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Formato Conformidad Producto Recibido
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Formato de Afectación Presupuestal
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Pagos a Tercero Refrendo de Recursos
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Viáticos por Persona
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Solicitud de Operaciones Financieras
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Lineamientos Generales de Racionalidad Austeridad y Disciplina Presupuestal 2025
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Lista de Asistencia
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Clasificador por Objeto del Gasto
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Oficio de Liberación de Trámite de Pago
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Oficio de Liberación de Trámite de Pago a Tercero ALTA DE USUARIO SERVICIOS PROFESIONALES
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    XXX-GASTOS-00XX-XXX-P38X0-20XX
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Orden de Ministración de Viáticos en el Extranjero
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Oficio de Liberación de Gasto GO y GRC
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    PROCESO CANCELACION CFDI
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Datos de Facturacion
                                                                    </div>
                                                                </li> 
                                                            </ol>
                                                            <ol class="dd-list">
                                                                 <p class="text-muted mb-4">Coordinación de Recursos Materiales</p>
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                     Solicitud de Mantenimiento Vehicular
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                       Bitácora de Revisión de Niveles
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                     Investigación de Mercado
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Investigación de Mercado Servicios
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Investigación de Mercado Adquisiciones
                                                                    </div>
                                                                </li> 
                                                            </ol>
                                                            <ol class="dd-list">
                                                                 <p class="text-muted mb-4">Archivo</p>
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                     Señaletica para espacios AT
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Formato para lomo de carpeta
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                    Formatos para lomos de carpetas
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Pestaña de Expediente
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Etiqueta de caja at
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Formato inventario documental
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Instructivo llenado portada
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Portada archivística
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Formato registro de préstamo y/o consulta
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Formato listado de documentos ejemplo
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                     Registros de correspondencia
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Unidad de correspondencia 2023
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Instrumentos de Clasificación Archivistica
                                                                    </div>
                                                                </li> 
                                                            </ol>
                                                             <ol class="dd-list">
                                                                 <p class="text-muted mb-4">Coordinación de Recursos Humanos</p>
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                     Ejemplo Entregable
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                       Solicitud de Beca para Hijos
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                     Seguro Gastos Médicos Mayores
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                     Carta Compromiso CT
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Solicitud Apoyo para Estudios
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Justificante Incidencias
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                     Licencia por Paternidad
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Licencia por Lactancia
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Permiso Económico
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Requisitos Apoyo para estudios y formación básica
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Becas para Descendientes
                                                                    </div>
                                                                </li> 
                                                                 <li class="dd-item" data-id="2">
                                                                    <div class="dd-handle">
                                                                      Seguro de Gastos Médicos Mayores
                                                                    </div>
                                                                </li> 
                                                                 
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
