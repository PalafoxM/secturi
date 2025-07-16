


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
                                <li class="breadcrumb-item active">Listado de Tikets</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Tikets</h4>
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
                                            <span>TIKETS</span>
                                            <button 
                                                class="btn btn-gradient-primary px-4 float-right mt-0 mb-3"><i
                                                    class="mdi mdi-plus-circle-outline mr-2"></i>Agregar Perfil</button>
                                            <table id="datatableCategorias" class="table" data-toggle="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">ID</th>
                                                        <th class="text-center">TIPO</th>
                                                        <th class="text-center">NOMBRE COMPLETO</th>
                                                        <th class="text-center">FECHA</th>
                                                        <th class="text-center">ESTATUS</th>
                                                        <th class="text-center">ACCIONES</th>
                                                    </tr>
                                                    <!--end tr-->
                                                </thead>

                                                <tbody>
                                                    <?php foreach($incidencia as $p): ?>
                                                    <tr>
                                                        <td class="text-center"><?= $p->id_incidencia?></td>
                                                        <td class="text-center"><?= $p->dsc_incidencia?></td>
                                                        <td class="text-center"><?= $p->nombre_completo?></td>
                                                        <td class="text-center"><?= date('d-m-Y', strtotime($p->fecha))?></td>
                                                        <td class="text-center">
                                                             <?php
                                                             switch($p->cat_id_incidencia){
                                                                 case 1:
                                                                    echo '<span class="badge badge-soft-primary">pendiente</span>';
                                                                    break;
                                                                 case 2:
                                                                    echo '<span class="badge badge-soft-success">aprobado</span>';
                                                                    break;
                                                                 case 3:
                                                                    echo '<span class="badge badge-soft-danger">rechazado</span>';
                                                                    break;
                                                             }
                                                             ?>
                                                        </td>
                                                      
                                                      <td class="text-center">
                                                            <!-- Aprobar/aceptar -->
                                                            <a href="#" class="mr-2" title="Aprobar">
                                                                <i class="fas fa-check-circle text-success font-16"></i>
                                                            </a>
                                                            
                                                            <!-- Revisar/editar -->
                                                            <a style="cursor:pointer;" onclick="saeg.principal.detalleIncidencia(<?=$p->id_incidencia ?>);" class="mr-2" title="Revisar">
                                                                <i class="fas fa-search text-info font-16"></i>
                                                            </a>
                                                            
                                                            <!-- Rechazar -->
                                                            <a href="#" class="mr-2" title="Rechazar">
                                                                <i class="fas fa-times-circle text-warning font-16"></i>
                                                            </a>
                                                            
                                                            <!-- Eliminar -->
                                                            <a href="#" class="mr-2" title="Eliminar">
                                                                <i class="fas fa-trash-alt text-danger font-16"></i>
                                                            </a>
                                                            
                                                            <!-- Editar (opcional) -->
                                                            <a href="#" class="mr-2" title="Editar">
                                                                <i class="fas fa-edit text-primary font-16"></i>
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
                        <!--end general detail-->


                      

                        <!--end settings detail-->
                    </div>
                    <!--end tab-content-->

                </div>
                <!--end col-->
            </div>
            <!--end row-->

        </div><!-- container -->



    </div>
    <!-- end page content -->
</div>


<div class="modal fade" id="detalleIncidencia" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
                <main>
                   <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body step active">        
                                   <h2 class="mt-0 header-title">EDITAR RESERVA</h2>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">        
                                                        <h4 class="mt-0 header-title">Detalles</h4>
                                                         
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox"  class="custom-control-input" id="customSwitch1">
                                                            <label class="custom-control-label" for="customSwitch1">Datos</label>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="nombre">NOMBRE</label>
                                                                    <input type="text" class="form-control" id="nombre" name="nombre" readonly>
                                                                    <input type="hidden" id="id_reserva" >
                                                                </div>
                                                                <div class="form-group" id="id_instrumento">
                                                                    <span id="previews"></span>
                                                                    <label for="tipo_incidencia">TIPO INCIDENCIA</label>
                                                                    <input type="text" class="form-control" id="tipo_incidencia" name="tipo_incidencia" readonly>
                                                                </div>                                                                                      
                                                                <div class="form-group" id="id_instrumento">
                                                                    <span id="previews"></span>
                                                                    <label for="detalles">DETALLES</label>
                                                                    <textarea type="text" class="form-control" id="detalles" name="detalles" readonly></textarea>
                                                                </div>                                                                                      
                                                            </div>
                                                            <div class="col-lg-6" >
                                                                <div class="form-group">
                                                                    <label for="hora_inicio">HORA INICIO</label>
                                                                    <input type="text" class="form-control" id="hora_inicio" name="hora_inicio" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="hora_fin">HORA FIN</label>
                                                                    <input type="text" class="form-control" id="hora_fin" name="hora_fin" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="comentario">COMETARIO</label>
                                                                    <textarea type="text" class="form-control" id="comentario" name="comentario" readonly></textarea>
                                                                </div>
                                                            </div>  
                                                        </div>  
                                                                                                               
                                                    </div><!--end card-body-->
                                                </div><!--end card-->
                                            </div><!--end col-->
                                        </div><!--end col-->                                                               
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
            </main> 
        </div>
    </div>
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
  $('#datatableCategorias,#datatablePeriodos,#datatableCursos').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: true,
    });
});

</script>