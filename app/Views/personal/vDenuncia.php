
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
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Formulario</a></li>
                                        <li class="breadcrumb-item active">Denuncia</li>
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
                                    
                                   <form id="form_denuncia" >

                                       <div class="row">
                                            <!-- Dirección Responsable -->
                                            <div class="col-md-6 mb-6">
                                                <label for="nombre">Indicar su Nombre completo</label>
                                                <input id="nombre" class="form-control" name="nombre" value="<?= $session->nombre_completo ?>" >
                                             
                                            </div><!--end col-->
                                            
                                            <!-- Tipo de PT -->
                                            <div class="col-md-6 mb-6">
                                                <label for="domicilio">Domicilio personal</label>
                                                <input id="domicilio" class="form-control" name="domicilio" placeholder="Col. la joya, calle florida #12"  >
                                               
                                            </div><!--end col-->
                                         
                                        </div><!--end form-row-->
                                        <div class="row">
                                            <div class="col-md-6 mb-6">
                                                <label for="correo">Correo electrónico para recibir informes o notificaciones del seguimiento<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="correo" value="<?= $session->correo ?>"  name="correo" >
                                        
                                            </div><!--end col-->
                                            <div class="col-md-6 mb-6">
                                                <label for="telefono">Número Telefónico</label>
                                                <input type="text" class="form-control" id="telefono" placeholder="XXX-XXX-XX-XX" name="telefono" >
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="row">
                                            <div class="col-md-6 mb-6">
                                                <label for="donde_ocurrieron">¿Dónde ocurrieron los hechos?<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="donde_ocurrieron" name="donde_ocurrieron" >
                                        
                                            </div><!--end col-->
                                            <div class="col-md-6 mb-6">
                                                <label for="cuando_ocurrieron">¿Cuándo ocurrieron los hechos?<span style="color:red;">*</span></label>
                                                <input type="date" class="form-control" id="cuando_ocurrieron" name="cuando_ocurrieron" >
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                       <div class="row">
                                            <div class="col-md-6 mb-6">
                                                <label for="testigo">
                                                ¿Hubo algún testigo que presenciara los hechos?<span style="color:red;">*</span>
                                                </label>
                                                <select class="form-control" id="testigo" name="testigo">
                                                    <option value="1">SI</option>
                                                    <option value="2">NO</option>
                                                </select>
                                            </div><!--end col-->

                                            <div class="col-md-6 mb-6" id="id_usuarios">
                                                <label for="denunciando">
                                                Persona a la que se le está denunciando<span style="color:red;">*</span>
                                                </label>
                                                <select class="form-control select2" name="denunciando" id="denunciando">
                                                <?php foreach($usuario as $u): ?>
                                                    <option value="<?=$u->id_usuario ?>"><?= $u->nombre_completo ?></option>
                                                <?php endforeach; ?>
                                                <option value="0">NO APLICA</option>
                                                </select>
                                            </div><!--end col-->

                                            <!-- input alternativo oculto -->
                                            <div class="col-md-6 mb-6" id="div_input_manual" style="display:none;">
                                                <label for="denunciando_text">
                                                Escriba otra persona<span style="color:red;">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="denunciando_text" name="denunciando_text" placeholder="Ingrese nombre">
                                            </div><!--end col-->
                                        </div><!--end form-row-->

                                        <div class="row">
                                           
                                            <div class="col-md-12 mb-6">
                                                <label for="como_ocurrieron">¿Cómo ocurrieron los hechos?<span style="color:red;">*</span></label>
                                                <textarea class="form-control" id="como_ocurrieron" name="como_ocurrieron" ></textarea>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                               
                               <a class="btn btn-gradient-danger" style="color:white" onclick="window.history.back()">Atrás</a>
                      
                                <a href="javascript:void(0)" class="btn btn-gradient-primary text-white" id="btnDenuncia" onclick="ini.inicio.formDenuncia();">Guardar</a>
                         
                            </form> <!--end form-->                                          
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->
             </div><!--end row-->
         </div><!-- container -->
     </div>
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


<script src="<?= base_url()?>assets/js/feather.min.js"></script>

<script src="<?= base_url()?>plugins/tiny-editable/mindmup-editabletable.js"></script>
<script src="<?= base_url()?>plugins/tiny-editable/numeric-input-example.js"></script>
<script src="<?= base_url()?>plugins/bootable/bootstable.js"></script> 
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />

<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>

<!-- Al final de tu archivo, antes de cerrar el body -->
<script>
$(document).ready(function() {
    // Inicializar todos los elementos con clase select2
    $('.select2').select2();
    
    console.log('Select2 inicializado'); // Para debug
});
$(document).on('change', '#denunciando', function () {
  if ($(this).val() === "0") {
    // Opción "NO APLICA"
    $("#id_usuarios").hide();            // ocultar select
    $("#div_input_manual").show();       // mostrar input
  } else {
    $("#id_usuarios").show();            // mostrar select
    $("#div_input_manual").hide();       // ocultar input
  }
});

</script>