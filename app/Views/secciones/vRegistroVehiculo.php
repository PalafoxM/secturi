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
                                <li class="breadcrumb-item active">GO</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Formulario GO</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>

            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                   
                            <form id="form_vehiculo" enctype="multipart/form-data">
                                <input type="hidden" name="editar" value="0">
                                <input type="hidden" name="id_proyecto" value="<?= $id_proyecto?>">
                                <div class="form-row">
                                    <!-- Dirección Responsable -->
                                    <div class="col-md-6 mb-6">
                                        <label for="id_proveedor">Proveedor<span class="text-danger">*</span></label>
                                        <select class="form-control select2-ajax" id="id_proveedor" name="id_proveedor" required>
                                            </select>
                                    </div>
                                    <div class="col-md-6 mb-6">
                                        <label for="id_proveedor_banco">Proveedor Banco<span class="text-danger">*</span></label>
                                        <select class="form-control select2-ajax" id="id_proveedor_banco" name="id_proveedor_banco" required>
                                            </select>
                                    </div>
                                </div><!--end form-row-->
                                <div class="form-row">
                                    <!-- Dirección Responsable -->
                                    <div class="col-md-4 mb-3">
                                        <label for="direccion_responsable">Dirección Responsable <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="direccion_responsable"
                                            name="direccion_responsable" required>
                                            <?php foreach ($cat_area as $a): ?>
                                                <option value="<?= $a->id_area ?>" <?php echo ($a->id_area == $usuario->id_area) ? 'selected' : ''; ?>>
                                                    <?= $a->dsc_area ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor ingrese la dirección responsable
                                        </div>
                                    </div><!--end col-->
                                    <!-- Fecha de Trámite -->
                                    <div class="col-md-4 mb-3">
                                        <label for="fecha_tramite">Fecha de Trámite <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="fecha_tramite" name="fecha_tramite"
                                            value="<?= isset($registro_pt->fecha_tramite) ? date('Y-m-d', strtotime($registro_pt->fecha_tramite)) : date('Y-m-d') ?>"
                                            required>
                                    </div><!--end col-->
                                    <div class="col-md-4 mb-3">
                                        <label for="reponsable_solicitud">Responsable de la Solicitud <span
                                                style="color:red;">*</span></label>
                                        <select name="id_reponsable_solicitud" class="form-control" required>
                                            <?php foreach ($cat_usuario as $u): ?>
                                                <?php
                                                // Determina el valor que debe quedar seleccionado
                                                $selected = '';
                                                if (isset($registro_pt->id_reponsable_solicitud) && $registro_pt->id_reponsable_solicitud == $u->id_usuario) {
                                                    $selected = 'selected';
                                                } elseif (!isset($registro_pt->id_reponsable_solicitud) && isset($usuario) && $usuario->id_usuario == $u->id_usuario) {
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                <option value="<?= $u->id_usuario ?>" <?= $selected ?>>
                                                    <?= $u->nombre . ' ' . $u->primer_apellido . ' ' . $u->segundo_apellido ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!--end col-->
                                </div><!--end form-row-->
                                <div class="form-row">

                                    <div class="col-md-4 mb-3">
                                        <label for="director_generar">Director/a General Administrativa <span
                                                style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="director_generar"
                                            value="<?= $dsc_director_general ?>" name="director_generar">
                                        <div class="invalid-feedback">
                                            Please provide a valid state.
                                        </div>
                                    </div><!--end col-->
                                    <div class="col-md-4 mb-3">
                                        <label for="secretario">Secretario(a) o Director(a) que autoriza</label>
                                        <select type="text" class="form-control" id="secretario"
                                            placeholder="Secretario/a" name="secretario">
                                            <option value="0" selected>Seleccione una opcion</option>
                                            <?php foreach ($secretario as $s): ?>
                                                <?php if (isset($registro_pt->secretario) && !empty($registro_pt->secretario)) { ?>
                                                    <option value="<?= $s->id_secretario ?>"
                                                        <?= ($s->id_secretario == $registro_pt->secretario) ? 'selected' : '' ?>>
                                                        <?= $s->dsc_secretario ?></option>
                                                <?php } else { ?>
                                                    <option value="<?= $s->id_secretario ?>"><?= $s->dsc_secretario ?></option>
                                                <?php } ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!--end col-->
                                    <div class="col-md-4 mb-3">
                                        <label for="secretario">Subsecretario(a) o Director(a) General
                                            Responsable</label><span class="text-danger">*</span>
                                        <select type="text" class="form-control" id="id_subsecretario"
                                            placeholder="Secretario/a" name="id_subsecretario">
                                            <option value="0" selected>Seleccione una opcion</option>
                                            <?php foreach ($cat_subsecretario as $s): ?>
                                                <?php if (isset($registro_pt->id_subsecretario) && !empty($registro_pt->id_subsecretario)) { ?>
                                                    <option value="<?= $s->id_subsecretario ?>"
                                                        <?= ($s->id_subsecretario == $registro_pt->id_subsecretario) ? 'selected' : '' ?>><?= $s->dsc_subsecretario ?></option>
                                                <?php } else { ?>
                                                    <option value="<?= $s->id_subsecretario ?>"><?= $s->dsc_subsecretario ?>
                                                    </option>
                                                <?php } ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!--end col-->
                                </div><!--end form-row-->

                        
                                <div class="form-row">
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="comision">Comisión / Reunión / Evento / Programa</label>
                                        <input type="text" class="form-control" id="comision" name="comision"
                                            value="<?= (isset($registro_pt->comision)) ? $registro_pt->comision : 'Comisión / Reunión / Evento / Programa' ?>">
                                        <div class="invalid-feedback">
                                            Please provide a valid state.
                                        </div>
                                    </div><!--end col-->
                              
                                    <div class="col-md-6 mb-3">
                                        <label for="concepto_gasto">Concepto del gasto<span
                                                style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="concepto_gasto" autocomplete="off"
                                            placeholder="Concepto del gasto" name="concepto_gasto">
                                    </div><!--end col-->
                                </div><!--end form-row-->
                                   <div class="form-row">

                                 
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha_inicio">Fecha Inicio.<span
                                                style="color:red;">*</span></label>
                                           <input id="fecha_inicio"  type="date" name="fecha_inicio" class="form-control" multiple accept=".pdf" />
                                
                                    </div><!--end col-->
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha_fin">Facha Fin.<span
                                                style="color:red;">*</span></label>
                                       <input id="fecha_fin" type="date" name="fecha_fin" multiple class="form-control"  accept=".xml">
                                    </div><!--end col-->

                                                                
                                </div><!--end form-row-->
          
                                <div class="form-row">

                                 
                                    <div class="col-md-6 mb-3">
                                        <label for="no_consecutivo">Factura PDF.<span
                                                style="color:red;">*</span></label>
                                           <input id="factura_pdf"  type="file" name="factura_pdf" class="dropify" multiple accept=".pdf" />
                                
                                    </div><!--end col-->
                                    <div class="col-md-6 mb-3">
                                        <label for="factura_xml">Factura XML.<span
                                                style="color:red;">*</span></label>
                                       <input id="factura_xml" type="file" name="factura_xml" multiple class="dropify"  accept=".xml">
                                    </div><!--end col-->

                                                                
                                </div><!--end form-row-->
                             
                                <br>

                               
                                <div id="hidden-file-inputs-container"></div>
                              
                             
                                    <button class="btn btn-gradient-primary" id="btnGuardaVi" type="submit">Guardar</button>
                           
                            </form> <!--end form-->
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!-- container -->
    </div>
</div>
<!--Form Wizard-->
<link rel="stylesheet" href="<?= base_url() ?>plugins/jquery-steps/jquery.steps.css">

<!-- App css -->
<link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

<!-- Plugins css -->
<link href="<?= base_url() ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
<link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet"
    type="text/css" />
<link href="<?= base_url() ?>plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
<link href="<?= base_url() ?>plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />



<!-- jQuery  -->
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery-ui.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.slimscroll.min.js"></script>


<script src="<?= base_url() ?>plugins/jquery-steps/jquery.steps.min.js"></script>
<script src="<?= base_url() ?>assets/pages/jquery.form-wizard.init.js"></script>



<!-- Plugins js -->
<script src="<?= base_url() ?>plugins/moment/moment.js"></script>
<script src="<?= base_url() ?>plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?= base_url() ?>plugins/select2/select2.min.js"></script>
<script src="<?= base_url() ?>plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="<?= base_url() ?>plugins/timepicker/bootstrap-material-datetimepicker.js"></script>
<script src="<?= base_url() ?>plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
<script src="<?= base_url() ?>plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>

<script src="<?= base_url() ?>assets/pages/jquery.forms-advanced.js"></script>


<script>


$(document).ready(function() {
    $('#id_proveedor').select2({
        placeholder: 'Escribe para buscar un proveedor...',
            minimumInputLength: 3,
            allowClear: true,
            ajax: {
                url: base_url + "index.php/Principal/buscarProveedor2",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    console.log('Término buscado:', params.term); // Debug
                    return {
                        q: params.term // Asegúrate que esto se envía
                    };
                },
                processResults: function (data) {
                    console.log('Respuesta recibida:', data); // Debug
                    return data;
                },
                cache: true
            }
        });
    });

    $('#id_proveedor').on('change', function() {
        var idProveedor = $(this).val();
        
        if (idProveedor) {
            cargarBancosProveedor(idProveedor);
        } else {
            // Limpiar el select de bancos si no hay proveedor seleccionado
            $('#id_proveedor_banco').empty().append('<option value="">Seleccione un banco</option>');
        }
    });

      function cargarBancosProveedor(idProveedor) {
        $.ajax({
            url: base_url + "index.php/Principal/obtenerBancosProveedor",
            type: 'GET',
            dataType: 'json',
            data: {
                id_proveedor: idProveedor
            },
            beforeSend: function() {
                // Mostrar loading
                $('#id_proveedor_banco').empty().append('<option value="">Cargando bancos...</option>');
            },
            success: function(response) {
                console.log(response  );
                $('#id_proveedor_banco').empty();
                
                if (response && response.length > 0) {
                    // Agregar opción por defecto
                    $('#id_proveedor_banco').append('<option value="">Seleccione un banco</option>');
                    
                    $.each(response, function(index, banco) {    
                        $('#id_proveedor_banco').append(
                            $('<option>', {
                                value: banco.id_proveedor_banco,
                                text: banco.banco + ' - ' + banco.no_cuenta + ' - ' + banco.clabe
                            })
                        );
                    });
                } else {
                    $('#id_proveedor_banco').append('<option value="">No hay bancos registrados</option>');
                }
            },
            error: function() {
                $('#id_proveedor_banco').empty().append('<option value="">Error al cargar bancos</option>');
            }
        });
     }

$('#form_vehiculo').on('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    $.ajax({
        type: "POST",
        url: "<?= base_url()?>index.php/Agregar/guardaVe",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            console.log(response);
            if(!response.error){
                Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                setTimeout(() => {
                    window.location.href = base_url + "index.php/Principal/tablaArchivosVehiculos/"+response.idRegistro+'/PT';
                }, 1500);
            }else{
                Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
            }
        },
        beforeSend: function (info){
            $('#btnGuardaGo').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
        },
        complete: function (info){
            $('#btnGuardaGo').prop('disabled', false).html('Guardar');
        },
        error: function (response,jqXHR, textStatus, errorThrown) {
            var res= JSON.parse(response.responseText);
            Swal.fire("Error", '<p> '+ res.message + '</p>');  
        }
    });
});
        

</script>