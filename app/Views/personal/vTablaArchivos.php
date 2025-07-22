

<?php $session = \Config\Services::session(); ?>
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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">pago</a></li>
                                <li class="breadcrumb-item active">PAM</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Respaldo Documental</h4>
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
                
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="header-title mt-0">Respaldo Documental</h4>
                                                <p class="text-muted mb-3"> Adjunto al PAM para Pago</p>
                                                <div class="table-responsive-sm">
                                                    <table class="table mb-0">
                                                        <thead>
                                                            <tr>
                                                            <th scope="col">Nombre</th>
                                                            <th scope="col">Contenido PT</th>
                                                            <th scope="col">Contenido GO</th>
                                                            <th scope="col">Contenido GRC</th>
                                                            <th scope="col">Archivo</th>
                                                            </tr>
                                                        </thead>
                                                      <tbody>
                                                        <tr class="table-primary">
                                                            <th scope="row">01. Anexos y formato de los LTPOFB</th>
                                                            <td>Anexo 2 - Reporte de Integración Documental y Formato de conformidad del producto recibido.</td>
                                                            <td>Anexo 2 - Reporte de Integración Documental</td>
                                                            <td>Anexo 2 - Reporte de Integración Documental</td>
                                                            <td class="text-center">
                                                                <a target="_blank" href="<?= base_url().'index.php/Principal/Archivo/'.$id_registro_pt.'/1' ?>" class="text-center">
                                                                    <i class="far fa-file-pdf text-danger"></i>
                                                                    <h6 class="text-truncate">Archivo01.pdf</h6>
                                                                </a>   
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">02. Póliza</th>
                                                            <td>Financieros</td>
                                                        </tr>
                                                        <tr class="table-secondary">
                                                            <th scope="row">03. CFDI</th>
                                                            <td>Comprobante Fiscal Digital por Internet en su representación PDF</td>
                                                            <td>Comprobante Fiscal Digital por Internet en su representación PDF</td>
                                                            <td>N/A</td>
                                                            <td class="text-center">
                                                              <a target="_blank" href="<?= base_url().'index.php/Principal/Archivo/'.$id_registro_pt ?>" class="text-center">
                                                                    <i class="far fa-file-pdf text-danger"></i>
                                                                    <h6 class="text-truncate">Archivo03.pdf</h6>
                                                                </a>   
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">04. Contrato o Convenio (según corresponda)</th>
                                                            <td>Instrumento jurídico que corresponda</td>
                                                            <td>N/A</td>
                                                            <td>N/A</td>
                                                            <td class="text-center">
                                                                <a target="_blank"  href="<?= base_url().'index.php/Principal/Archivo/'.$id_registro_pt.'/4' ?>" class="text-center">
                                                                    <i class="far fa-file-pdf text-danger"></i>
                                                                    <h6 class="text-truncate">Archivo04.pdf</h6>
                                                                </a>   
                                                            </td>
                                                        </tr>
                                                        <tr class="table-success">
                                                            <th scope="row">05. Formatos de los LRADP</th>
                                                            <td>OMVE-1 (Orden de Ministración de Viáticos en el Extranjero)</td>
                                                            <td>OMVE-1 (Orden de Ministración de Viáticos Nacionales)</td>
                                                            <td>Solicitud de gastos a reserva de comprobar GRC-1 y OMVE-1 (Orden de Ministración de Viáticos en el Extranjero)</td>
                                                            <td class="text-center">
                                                               <a target="_blank" href="<?= base_url().'index.php/Principal/Archivo/'.$id_registro_pt ?>" class="text-center">
                                                                    <i class="far fa-file-pdf text-danger"></i>
                                                                    <h6 class="text-truncate">Archivo05.pdf</h6>
                                                                </a>   
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">06. Oficios de Autorizaciones</th>
                                                            <td>Autorización de Servicios Profesionales (1330, 3330, 3340 y 3390), Validación de la Coordinación General de Comunicación Social (3611, 3612, 3630, 3660 y 3690), Validación de imagen, Autorización de partidas restringidas (3710, 3760, 3810, 3820 y 3830), Autorización de pasivos, Autorización de refrendos.</td>
                                                            <td>N/A</td>
                                                            <td>Autorización de Servicios Profesionales (3310, 3330, 3340 y 3390), Autorización de partidas restringidas (3710, 3760, 3810 y 3830)</td>
                                                            <td></td>
                                                        </tr>
                                                        <tr class="table-info">
                                                            <th scope="row">07. Formatos diversos</th>
                                                            <td>Carátula de pago, Oficio(s) Delegatorio(s), Oficio de Liberación de Pago, CFDI con su respectivo encabezado de factura, Oficio de Comisión, Evidencia Fotográfica, Lista de Asistencia.</td>
                                                            <td>Carátula de pago, Oficio(s) Delegatorio(s), Oficio de Liberación de Gastos, CFDI con su respectivo encabezado de factura (incluir notas de consumo, check-in/out vouchers, tickets de peajes), Formato de Desglose de Gastos (Viáticos por persona), Oficio de Comisión, Evidencia Fotográfica, Lista de Asistencia.</td>
                                                            <td>Formato de Desglose de Gastos (Viáticos por persona presupuestado), Oficio de Comisión.</td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">08. Evidencia de entregable</th>
                                                            <td>Entregable con sello, fecha y firma de recibido</td>
                                                            <td>N/A</td>
                                                            <td>N/A</td>
                                                            <td></td>
                                                        </tr>
                                                        <tr class="table-warning">
                                                            <th scope="row">09. Otros</th>
                                                            <td>Soporte de datos bancarios, Registro en el Padrón de Proveedores (vigente), Soporte documental inherente al trámite (Garantía, CURP; en caso de ayudas y subsidios a personas físicas: listado de beneficiarios, minutas, programas, agendas, itinerarios, etc.).</td>
                                                            <td>Programas, agendas, itinerarios, recibo de verificación, etc.</td>
                                                            <td>Programas, agendas, itinerarios, etc.</td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>

                                                    </table><!--end /table-->
                                                </div><!--end /tableresponsive-->
                                            </div><!--end card-body-->
                                        </div><!--end card-->
                                    </div><!--end col-->
 
                        </div>
                    </div>
                </div>
            </div>
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



<script src="<?= base_url()?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url()?>assets/js/waves.js"></script>
<script src="<?= base_url()?>assets/js/feather.min.js"></script>
<script>
$(document).ready(function() {

    $('#datatableReserva').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: true,
    });
        // Función debounce para retrasar la ejecución
    });



</script>