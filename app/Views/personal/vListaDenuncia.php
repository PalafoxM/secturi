


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
                        <h4 class="page-title">Listado de Denuncia</h4>
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
                                            <span>DENUNCIA</span>
                                            
                                            <table id="datatableCategorias" class="table" data-toggle="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">USUARIO</th>
                                                        <th class="text-center">CORREO</th>
                                                        <th class="text-center">TELEFONO</th>
                                                        <th class="text-center">COMO</th>
                                                        <th class="text-center">TESTIGOS</th>
                                                        <th class="text-center">ACCIONES</th>
                                                    </tr>
                                                    <!--end tr-->
                                                </thead>

                                                <tbody>
                                                    <?php foreach ($denuncia as $p): ?>
                                                    <tr>
                                                        <td class="text-center"><?= $p->nombre ?></td>
                                                        <td class="text-center"><?= $p->correo ?></td>
                                                        <td class="text-center"><?= $p->telefono ?></td>
                                                        <td class="text-center"><?= $p->como_ocurrieron ?></td>
                                                        <td class="text-center">
                                                            <?=
                                                            ($p->testigo == 1)
                                                                ? 'SI'
                                                                : 'NO'
                                                            ?>
                                                        </td>
                                                      
                                                         <td class="text-center">
                                                                <a href="javascript:void(0)" 
                                                                onclick="ini.inicio.getDenuncia(<?= $p->id_denuncia ?>)"
                                                                    data-animation="bounce" ><i
                                                                        class="mdi mdi-eye text-info font-18"></i></a>

                                
                                                                <a href="javascript:void(0);"
                                                                    onclick="ini.inicio.deleteDe(<?= $p->id_denuncia ?>)"><i
                                                                        class="mdi mdi-trash-can text-danger font-18"></i></a>
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
</div>
<div id="modalDenuncia" class="modal fade bs-example" tabindex="-1" aria-labelledby="modalActividadLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header">
            <h5 id="modalActividadLabel" class="modal-title">Detalles de la Denuncia</h5>
            <button type="button" class="btn-close" onclick="ini.inicio.modalDenuncia(false)" aria-label="Cerrar"></button>
         </div>
         <div class="modal-body">
            <form id="form_actividad" >
           
                                       <div class="row">
                                            <!-- Dirección Responsable -->
                                            <div class="col-md-6 mb-6">
                                                <label for="nombre">Indicar su Nombre completo o "Anónimo"</label>
                                                <input id="nombre" class="form-control" name="nombre" >
                                             
                                            </div><!--end col-->
                                            
                                            <!-- Tipo de PT -->
                                            <div class="col-md-6 mb-6">
                                                <label for="domicilio">Domicilio con código postal<span class="text-danger">*</span></label>
                                                <input id="domicilio" class="form-control" name="domicilio" placeholder="Col. la joya, calle florida #12"  >
                                               
                                            </div><!--end col-->
                                         
                                        </div><!--end form-row-->
                                        <div class="row">
                                            <div class="col-md-6 mb-6">
                                                <label for="correo">Correo electrónico para recibir informes o notificaciones del seguimiento.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="correo"  name="correo" >
                                        
                                            </div><!--end col-->
                                            <div class="col-md-6 mb-6">
                                                <label for="telefono">Número Telefónico.<span style="color:red;">*</span></label>
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
                                           
                                            <div class="col-md-12 mb-6">
                                                <label for="como_ocurrieron">¿Cómo ocurrieron los hechos?<span style="color:red;">*</span></label>
                                                <textarea class="form-control" id="como_ocurrieron" name="como_ocurrieron" ></textarea>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
         <div class="modal-footer">
            <a class="btn btn-danger text-white" onclick="ini.inicio.modalDenuncia(false)">Cerrar</a>
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
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- jQuery  -->
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>
<!-- Required datatable js -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/pages/jquery.analytics_customers.init.js"></script>

<script src="<?= base_url() ?>plugins/apexcharts/apexcharts.min.js"></script>

<!-- App js -->
<script src="<?= base_url() ?>assets/js/app.js"></script>


<script src="<?= base_url() ?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>



<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
<script>

$(document).ready(function() {
  $('#datatableCategorias').DataTable({
       order: [[0, 'desc']] ,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: true,
    });
});


</script>