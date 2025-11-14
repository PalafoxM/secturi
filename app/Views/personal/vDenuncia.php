
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
                                <h4 class="page-title">RECEPCIÓN DE DENUNCIAS</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>
                    
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="mt-0 header-title">TIPO DE DENUNCIA<strong></strong></h3>
                                    
                                     <div class="row">
                                        <div class="col-lg-6">
                                            <div class="card text-white bg-primary">
                                                <a href="javascript:void(0)" onclick="mostrarForm1();">
                                                <div class="card-body">
                                                    <blockquote class="card-bodyquote mb-0">
                                                         <center><h4 class="text-white">Por incumplimiento al Código de Ética y/o Código de Conducta.</h4> </center>
                                                       
                                                    </blockquote>
                                                </div><!--end card-body-->
                                                </a>
                                            </div><!--end card-->
                                        </div><!--end col-->
                                                
                                        <div class="col-lg-6">
                                            <div class="card text-white bg-warning">
                                                <a href="javascript:void(0)" onclick="mostrarForm2();">
                                                <div class="card-body">
                                                    <blockquote class="card-bodyquote mb-0">
                                                        <center> <h4 class="text-white">Por violencia laboral, acoso u hostigamiento sexual.</h4> </center>
                                                       
                                                    </blockquote>
                                                </div><!--end card-body-->
                                                </a>
                                            </div><!--end card-->
                                        </div><!--end col-->
                                    </div><!--end row-->                                      
                        </div><!--end card-body-->
                    </div><!--end card-->
                    <div class="row" id="formulario_denuncia" style="display:none;" >
                         <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                     <h4 class="mt-0 header-title" id="incumplimiento">incumplimiento</h4>
                                      <h4 class="mt-0 header-title" id="violencia">violencia</h4>
                                      <form id="form_denuncia" >

                                       <div class="row">
                                            <!-- Dirección Responsable -->
                                            <div class="col-md-6 mb-6">
                                                <label for="nombre">Nombre</label>
                                                <input id="nombre" class="form-control" name="nombre" value="<?= $session->nombre_completo ?>" >
                                             
                                            </div><!--end col-->
                                            
                                            <div class="col-md-6 mb-6">
                                               <label for="correo">Correo electrónico para recibir informes o notificaciones del seguimiento</label>
                                                <input type="text" class="form-control" id="correo" value="<?= $session->correo ?>"  name="correo" >
                                        
                                            </div><!--end col-->
                                         
                                        </div><!--end form-row-->
                                        <div class="row">
                                            <div class="col-md-6 mb-6">
                                                <label for="correo">¿Deseas que tu denuncia sea anónima?</label><span style="color:red;">*</span>
                                                <select class="form-control">
                                                    <option>NO</option>
                                                    <option>SI</option>
                                                </select>
                                        
                                            </div><!--end col-->
                                            <div class="col-md-6 mb-6">
                                                
                                            </div><!--end col-->
                                           
                                        </div><!--end form-row-->
                                    
                                          <h3 class="mt-0 header-title">NARRACIÓN DE LOS HECHOS<strong></strong></h3>
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
                                                <?php foreach ($usuario as $u): ?>
                                                    <option value="<?= $u->id_usuario ?>"><?= $u->nombre_completo ?></option>
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
                                            <div class="col-md-6 mb-6">
                                                <label for="como_ocurrieron">¿Cómo ocurrieron los hechos?<span style="color:red;">*</span></label>
                                                <textarea class="form-control" id="como_ocurrieron" name="como_ocurrieron" ></textarea>
                                            </div><!--end col-->
                                            <div class="col-md-6 mb-6">
                                                <label for="como_ocurrieron">Evidencia en caso de contar con ella</label>
                                                <input type="file" class="form-control" id="como_ocurrieron" name="como_ocurrieron" ></textinputarea>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <br>
                                        <center>
                                        <h6 class="mt-0">Todas las denuncias serán tratadas con confidencialidad, respeto y seriedad.<br>
                              Tu participación contribuye a fortalecer una cultura de ética, integridad y confianza en la Secretaría de Turismo e Identidad.</h6>  </center>
                               
                               <a class="btn btn-gradient-danger" style="color:white" onclick="window.history.back()">Atrás</a>
                      
                                <a href="javascript:void(0)" class="btn btn-gradient-primary text-white" id="btnDenuncia" onclick="ini.inicio.formDenuncia();">Enviar</a>
                         
                            </form>
                         </div>
                        </div>
                      </div>
                    </div>
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


<script src="<?= base_url() ?>assets/js/feather.min.js"></script>

<script src="<?= base_url() ?>plugins/tiny-editable/mindmup-editabletable.js"></script>
<script src="<?= base_url() ?>plugins/tiny-editable/numeric-input-example.js"></script>
<script src="<?= base_url() ?>plugins/bootable/bootstable.js"></script> 
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
function mostrarForm1() {
    $('#formulario_denuncia').fadeIn(400); // 400 ms = 0.4 segundos
    $('#incumplimiento').fadeIn();
    $('#violencia').hide();
}
function mostrarForm2() {
    $('#formulario_denuncia').fadeIn(400); // 400 ms = 0.4 segundos
     $('#incumplimiento').hide();
    $('#violencia').fadeIn();
}

</script>