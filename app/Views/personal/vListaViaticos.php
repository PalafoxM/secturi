


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
                                <li class="breadcrumb-item active">Listado</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado 9-LTAIPG26F1_IX</h4>
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
                                            <span>LISTA 9-LTAIPG26F1_IX</span>
                                            <table id="datatableProveedores" class="table" data-toggle="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">EJERCICIO</th>
                                                        <th class="text-center">INICIO</th>
                                                        <th class="text-center">TERMINO</th>
                                                        <th class="text-center">INTEGRANTE</th>
                                                        <th class="text-center">DENOMINACION</th>
                                                        <th class="text-center">ACCIONES</th>
                                                    </tr>
                                                    <!--end tr-->
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($datos as $p): ?>
                                                    <tr>
                                                        <td class="text-center"><?= $p->ejercicio ?></td>
                                                        <td class="text-center"><?= date('d/m/Y', strtotime($p->fecha_inicio)) ?></td>
                                                        <td class="text-center"><?= date('d/m/Y', strtotime($p->fecha_termino)) ?></td>
                                                        <td class="text-center"><?= $p->dsc_tipo_funcionario ?></td>
                                                        <td class="text-center"><?= $p->dsc_denominacion ?></td>
                                                        <td class="text-center">
                                                             <a style="color:white" onclick="ini.inicio.reserva(<?= $p->id_juridico_viatico ?>);" title="Seccionar Proveedor"
                                                               
                                                                class="btn btn-gradient-success px-4"><i
                                                                    class="mdi mdi-arrow-collapse-right font-21"></i>
                                                            </a>
                                                          <!--   <a href="<?php echo base_url(); ?>index.php/Principal/Proveedor/<?= $p->id_juridico_viatico ?>" title="Seccionar Proveedor"
                                                               
                                                                class="btn btn-gradient-success px-4"><i
                                                                    class="mdi mdi-arrow-collapse-right font-21"></i>
                                                            </a> -->
                                                          
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

<!--Inicio Modal -->

                                                    <!--FIN MODAL -->
<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />
<!-- App css -->
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<!-- jQuery  -->
 
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>


<!-- Required datatable js -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

<!-- App js -->
<script src="<?= base_url() ?>assets/js/app.js"></script>
<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>

<script src="<?= base_url() ?>plugins/tiny-editable/mindmup-editabletable.js"></script>
<script src="<?= base_url() ?>plugins/tiny-editable/numeric-input-example.js"></script>
<script src="<?= base_url() ?>plugins/bootable/bootstable.js"></script> 
<script src="<?= base_url() ?>assets/pages/jquery.tabledit.init.js"></script> 
<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>


<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
<script>
$(document).ready(function() {
    ini.inicio.guardarReserva();
    ini.inicio.guardarGo();
    $('#datatableCategorias,#datatableProveedores').DataTable({
        order: [[0, 'desc']],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: true,
    });
    // Función debounce para retrasar la ejecución
});
</script>