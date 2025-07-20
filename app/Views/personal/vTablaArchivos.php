

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
                                                                <th scope="row">01 Anexos y formato de los LTPOFB</th>
                                                                <td class="">Anexo 2 - Reporte de Integración Documental y Formato de conformidad del producto recibido.</td>
                                                                <td class="">Otto</td>
                                                                <td class="">@mdo</td>
                                                            </tr>
                                                            <tr class="">
                                                            <th scope="row">2</th>
                                                                <td>Jacob</td>
                                                                <td>Thornton</td>
                                                                <td>@fat</td>
                                                            </tr>
                                                            <tr class="table-secondary">
                                                                <th scope="row">3</th>
                                                                <td class="">Larry</td>
                                                                <td class="">the Bird</td>
                                                                <td class="">@twitter</td>
                                                            </tr>
                                                            <tr class="">
                                                                <th scope="row">4</th>
                                                                <td>Mark</td>
                                                                <td>Otto</td>
                                                                <td>@mdo</td>
                                                            </tr>
                                                            <tr class="table-success">
                                                            <th scope="row">5</th>
                                                                <td>Jacob</td>
                                                                <td>Thornton</td>
                                                                <td>@fat</td>
                                                            </tr>
                                                            <tr class="">
                                                                <th scope="row">6</th>
                                                                <td>Larry</td>
                                                                <td>the Bird</td>
                                                                <td>@twitter</td>
                                                            </tr>
                                                            <tr class="table-info">
                                                            <th scope="row">7</th>
                                                                <td>Jacob</td>
                                                                <td>Thornton</td>
                                                                <td>@fat</td>
                                                            </tr>
                                                            <tr class="">
                                                                <th scope="row">8</th>
                                                                <td>Larry</td>
                                                                <td>the Bird</td>
                                                                <td>@twitter</td>
                                                            </tr>
                                                            <tr class="table-warning">
                                                            <th scope="row">9</th>
                                                                <td>Jacob</td>
                                                                <td>Thornton</td>
                                                                <td>@fat</td>
                                                            </tr>
                                                            <tr class="">
                                                                <th scope="row">10</th>
                                                                <td>Larry</td>
                                                                <td>the Bird</td>
                                                                <td>@twitter</td>
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