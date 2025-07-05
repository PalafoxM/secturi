


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
                                             <button type="button" class="btn btn-gradient-warning waves-effect waves-light">Agregar Nuevo Proveedor</button><br><br>
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
                                                             <a style="color:white" onclick="ini.inicio.reserva(<?=$p->id_proveedor?>);" title="Editar"
                                                                class="btn btn-gradient-success px-4"><i
                                                                    class="mdi mdi-border-color font-21"></i>
                                                            </a>
                                                            <a href="<?php echo base_url(); ?>index.php/Principal/Proveedor/<?= $p->id_proveedor ?>" title="Eliminar"
                                                                class="btn btn-gradient-danger px-4"><i
                                                                    class="mdi mdi-trash-can-outline font-21"></i>
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
<link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url()?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<!-- jQuery  -->
 
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>
<!-- Required datatable js -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

<!-- App js -->
<script src="<?= base_url()?>assets/js/app.js"></script>
<script src="<?= base_url()?>assets/js/waves.js"></script>
<script src="<?= base_url()?>assets/js/feather.min.js"></script>

<script src="<?= base_url()?>plugins/tiny-editable/mindmup-editabletable.js"></script>
<script src="<?= base_url()?>plugins/tiny-editable/numeric-input-example.js"></script>
<script src="<?= base_url()?>plugins/bootable/bootstable.js"></script> 
<script src="<?= base_url()?>assets/pages/jquery.tabledit.init.js"></script> 
<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
<script>
$(document).ready(function() {
    ini.inicio.guardarReserva();
    $('#datatableCategorias').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: false,
    });
    // Función debounce para retrasar la ejecución
});

</script>