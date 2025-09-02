

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
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Formulario</a></li>
                                        <li class="breadcrumb-item active">FIC</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Denuncia por incumplimiento al Código de Ética del Poder Ejecutivo del Estado de Guanajuato y/o Código de Conducta de la SECTURI </h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>
                    
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="mt-0 header-title">DATOS DEL DENUNCIANTE<strong></strong></h3>
                                    
                                   <form id="form_fic" enctype="multipart/form-data">

                                       <div class="form-row">
                                            <!-- Dirección Responsable -->
                                            <div class="col-md-6 mb-6">
                                                <label for="direccion_responsable">Indicar su Nombre completo o "Anónimo"<span class="text-danger">*</span></label>
                                                <input id="id_reponsable_solicitud" class="form-control" name="id_reponsable_solicitud" value="HUGO RAMÍREZ DUARTE" >
                                             
                                            </div><!--end col-->
                                            
                                            <!-- Tipo de PT -->
                                            <div class="col-md-6 mb-6">
                                                <label for="tipo_pt">Domicilio con código postal<span class="text-danger">*</span></label>
                                                <input id="id_reponsable_solicitud" class="form-control" name="id_reponsable_solicitud" value="HUGO RAMÍREZ DUARTE" >
                                               
                                            </div><!--end col-->
                                         
                                        </div><!--end form-row-->
                                 
                                    
                                        <div class="form-row">
                                            <div class="col-md-6 mb-6">
                                                <label for="formato_establecido">Correo electrónico para recibir informes o notificaciones del seguimiento.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="formato_establecido" value="SI" name="formato_establecido" readonly>
                                        
                                            </div><!--end col-->
                                            <div class="col-md-6 mb-6">
                                                <label for="documentacion_comprobatoria">Número Telefónico.<span style="color:red;">*</span></label>
                                               
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                      
                                      
                             
                               
                               <a class="btn btn-gradient-danger" style="color:white" onclick="window.history.back()">Atrás</a>
                      
                                <button class="btn btn-gradient-primary" id="btnGuardaFIC" type="submit">Guardar</button>
                         
                            </form> <!--end form-->                                          
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->
             </div><!--end row-->
         </div><!-- container -->
     </div>
</div>


           <!--Form Wizard-->
<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
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

<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>

<script src="<?= base_url() ?>plugins/tiny-editable/mindmup-editabletable.js"></script>
<script src="<?= base_url() ?>plugins/tiny-editable/numeric-input-example.js"></script>
<script src="<?= base_url() ?>plugins/bootable/bootstable.js"></script>
<script src="<?= base_url() ?>assets/pages/jquery.tabledit.init.js"></script>
<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>
<script>
ini.inicio.formFIC();

	
</script>
