
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
                                        <div class="col-lg-4">
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
                                                
                                        <div class="col-lg-4">
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

                                        <div class="col-lg-4">
                                            <div class="card text-white bg-success">
                                                <a href="javascript:void(0)" onclick="mostrarModalCondicionInsegura();">
                                                    <div class="card-body">
                                                        <blockquote class="card-bodyquote mb-0">
                                                            <center><h4 class="text-white">Reporte de actos y condiciones inseguras.</h4></center>
                                                        </blockquote>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
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

<div id="modalCondicionInsegura" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="tituloCondicionInsegura" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloCondicionInsegura">Reporte de acto o condición insegura</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_condicion_insegura" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info">
                        El reporte puede realizarse de manera anónima. Todos los campos son obligatorios.
                    </div>

                    <h5>I. ¿Qué deseas reportar?</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="reporte_inseguro">Tipo de reporte <span class="text-danger">*</span></label>
                            <select class="form-control" id="reporte_inseguro" name="id_reporte" required>
                                <option value="">Selecciona una opción</option>
                                <option value="1">a) Acto inseguro</option>
                                <option value="2">b) Condición insegura</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="anonimo_inseguro">¿Deseas realizarlo de manera anónima? <span class="text-danger">*</span></label>
                            <select class="form-control" id="anonimo_inseguro" name="anonimo" required>
                                <option value="">Selecciona una opción</option>
                                <option value="1">Sí</option>
                                <option value="2">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="accion_insegura">Acción o condición observada <span class="text-danger">*</span></label>
                            <select class="form-control" id="accion_insegura" name="id_accion" required disabled>
                                <option value="">Primero selecciona el tipo de reporte</option>
                            </select>
                        </div>
                    </div>

                    <div class="row campos-acto-inseguro" style="display:none;">
                        <div class="col-md-12 mb-3">
                            <label for="quien_inseguro">1. ¿Quién llevó a cabo el acto inseguro? <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="quien_inseguro" name="quien" maxlength="250">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="descripcion_insegura" id="label_descripcion_insegura">Descripción detallada <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="descripcion_insegura" name="descripcion" rows="4" required></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="ubicacion_insegura" id="label_ubicacion_insegura">Ubicación exacta <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ubicacion_insegura" name="ubicacion" maxlength="500" required>
                        </div>
                    </div>

                    <div class="row campos-acto-inseguro" style="display:none;">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_hechos_inseguros">4. ¿Cuándo ocurrieron los hechos? <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="fecha_hechos_inseguros" name="fecha_hechos">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="testigos_inseguros">5. ¿Hubo más personas que presenciaran el hecho? <span class="text-danger">*</span></label>
                            <select class="form-control" id="testigos_inseguros" name="testigos">
                                <option value="">Selecciona una opción</option>
                                <option value="1">Sí</option>
                                <option value="2">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="evidencia_insegura">Añadir evidencia <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="evidencia_insegura" name="evidencia_insegura" accept=".jpg,.jpeg,.png,.pdf,.mp4,.mov" required>
                            <small class="form-text text-muted">Formatos permitidos: JPG, PNG, PDF, MP4 o MOV. Máximo 10 MB.</small>
                        </div>
                    </div>

                    <h5>II. ¿Qué propones para solucionar o mejorar la situación?</h5>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="propuesta_insegura">Propuesta <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="propuesta_insegura" name="propuesta" rows="4" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btnCondicionInsegura">Enviar reporte</button>
                </div>
            </form>
        </div>
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
const accionesInseguras = <?= json_encode($acciones_inseguras ?? [], JSON_UNESCAPED_UNICODE) ?>;

$(document).ready(function() {
    // Inicializar todos los elementos con clase select2
    $('.select2').select2();
    
    console.log('Select2 inicializado'); // Para debug

    $('#form_condicion_insegura').on('submit', function (event) {
        event.preventDefault();
        ini.inicio.formCondicionInsegura();
    });
});

$('#reporte_inseguro').on('change', function () {
    const tipo = String($(this).val());
    const $accion = $('#accion_insegura');
    const esActo = tipo === '1';

    $accion.empty().append('<option value="">Selecciona una opción</option>');
    if (accionesInseguras[tipo]) {
        Object.keys(accionesInseguras[tipo]).forEach(function (id) {
            $accion.append($('<option>', { value: id, text: accionesInseguras[tipo][id] }));
        });
        $accion.prop('disabled', false);
    } else {
        $accion.prop('disabled', true);
    }

    $('.campos-acto-inseguro').toggle(esActo);
    $('#quien_inseguro, #fecha_hechos_inseguros, #testigos_inseguros').prop('required', esActo);
    if (!esActo) {
        $('#quien_inseguro, #fecha_hechos_inseguros, #testigos_inseguros').val('');
    }

    $('#label_descripcion_insegura').html(esActo
        ? '2. ¿Qué acto inseguro realizó? <span class="text-danger">*</span>'
        : '2. Describe detalladamente la condición insegura que pudiste observar <span class="text-danger">*</span>');
    $('#label_ubicacion_insegura').html(esActo
        ? '3. ¿Dónde ocurrieron los hechos? <span class="text-danger">*</span>'
        : '1. ¿En dónde se encuentra la condición insegura? (Ubicación exacta) <span class="text-danger">*</span>');
});

function mostrarModalCondicionInsegura() {
    $('#form_condicion_insegura')[0].reset();
    $('#reporte_inseguro').trigger('change');
    $('#modalCondicionInsegura').modal('show');
}

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
