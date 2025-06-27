


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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">seccion</a></li>
                                <li class="breadcrumb-item active">Listado PT</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Solicitudes PT</h4>
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
                                <div class="col-12">
                                    <div class="card">
                                     
                                        <div class="card-body">
                                         
                                            <span>LISTA DE PROVEEDORES</span>
                                            <div class="form-group"> 
                                                <div class="input-group">
                                                    <span class="input-group-prepend">
                                                        <button type="button" class="btn btn-gradient-primary"><i class="fas fa-search"></i></button>
                                                    </span>
                                                    <input autocomplete="off" type="text" id="buscar_proveedor" name="buscar_proveedor" class="form-control" placeholder="Busqueda General.">
                                                   
                                                </div>                                                    
                                            </div>
                                           
                                            <table id="datatableCategorias" class="table" data-toggle="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">ID PROVEEDOR</th>
                                                        <th class="text-center">RAZON SOCIAL</th>
                                                        <th class="text-center">RFC</th>
                                                        <th class="text-center">No PROVEEDOR</th>
                                                        <th class="text-center">ACCIONES</th>
                                                    </tr>
                                                    <!--end tr-->
                                                </thead>

                                                <tbody>
                                                    <?php foreach($proveedor as $p): ?>
                                                    <tr>
                                                        <td class="text-center"><?= $p->id_proveedor?></td>
                                                        <td class="text-center"><?= $p->razon_social?></td>
                                                        <td class="text-center"><?= $p->rfc?></td>
                                                        <td class="text-center"><?= $p->no_proveedor?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo base_url(); ?>index.php/Principal/Proveedor/<?= $p->id_proveedor ?>" title="Seccionar Proveedor"
                                                               
                                                                class="btn btn-gradient-success px-4"><i
                                                                    class="mdi mdi-arrow-collapse-right font-21"></i>
                                                            </a>
                                                          
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

<script src="<?= base_url()?>plugins/apexcharts/apexcharts.min.js"></script>

<!-- App js -->
<script src="<?= base_url()?>assets/js/app.js"></script>


<script src="<?= base_url()?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url()?>assets/js/waves.js"></script>
<script src="<?= base_url()?>assets/js/feather.min.js"></script>



<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
<script>
$(document).ready(function() {

    $('#datatableCategorias').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: false,
    });
    // Función debounce para retrasar la ejecución
});
function debounce(func, delay) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}
$('#buscar_proveedor').on('keyup', debounce(function () {
    ini.inicio.busquedaProveedor();
}, 500));





</script>