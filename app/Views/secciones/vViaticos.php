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
                                        <li class="breadcrumb-item active">Jurídico</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">9-LTAIPG26F1_IX</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>
                    
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="mt-0 header-title">LTAIPG26F1: <strong></strong></h3>
                                    <p class="text-muted mb-3" >
                                    
                                    </p>
                                   <form id="form_viatico" enctype="multipart/form-data">
                                       <div class="form-row">
                                            <!-- Dirección Responsable -->
                                            <div class="col-md-4 mb-3">
                                                <label for="ejercicio">Ejercicio <span class="text-danger">*</span></label>
                                                <select class="form-control" id="ejercicio" name="ejercicio" required>
                                                    <option value="2025">2025</option>
                                                    <option value="2024">2024</option>
                                                </select>
                                            </div><!--end col-->
                                            
                                             <div class="col-md-4 mb-3">
                                                <label for="fecha_inicio">Fec. de inicio del periodo que se informa<span class="text-danger">*</span></label>
                                              <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                            </div><!--end col-->
                                            
                                            <!-- Fecha de Trámite -->
                                            <div class="col-md-4 mb-3">
                                                <label for="fecha_termino">Fec. de término del periodo que se informa<span class="text-danger">*</span></label>
                                              <input type="date" class="form-control" id="fecha_termino" name="fecha_termino" required>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                           <div class="form-row">
                                            <div class="col-md-4 mb-6">
                                                <label for="formato_establecido">Tipo de integrante del sujeto obligado<span style="color:red;">*</span></label>
                                                  <select class="form-control" id="tipo_integrante" name="tipo_integrante" required>
                                                    <?php foreach ($cat_funcionario as $p): ?>
                                                    <option value="<?= $p->id_tipo_funcionario ?>" ><?= $p->dsc_tipo_funcionario ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div><!--end col-->
                                             <div class="col-md-4 mb-3">
                                                <label for="clave_nivel">Clave o nivel del puesto<span style="color:red;">*</span></label>
                                               <select class="form-control select2" id="clave_nivel" name="clave_nivel" required>
                                                <?php for ($i = 1; $i <= 20; $i++): ?>
                                                    <option value="<?= $i ?>"><?= $i ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            </div><!--end col-->
                                             
                                           
                                             <div class="col-md-4 mb-3">
                                                <label for="denominacion_puesto">Denominación del puesto<span style="color:red;">*</span></label>
                                                <select class="form-control select2" id="denominacion_puesto" name="denominacion_puesto">
                                                 <?php foreach ($deno_puesto as $d): ?>
                                                   <option value="<?= $d->id_denominacion ?>" ><?= $d->dsc_denominacion ?></option>
                                                  <?php endforeach; ?>
                                                </select>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="denomicacion_cargo">Denominación del cargo<span style="color:red;">*</span></label>
                                                <select class="form-control select2" id="denomicacion_cargo" name="denomicacion_cargo">
                                                 <?php foreach ($deno_cargo as $d): ?>
                                                   <option value="<?= $d->id_cargo ?>" ><?= $d->dsc_cargo ?></option>
                                                  <?php endforeach; ?>
                                                </select>
                                            </div><!--end col-->
                                         
                                             <div class="col-md-4 mb-3">
                                                <label for="area_adscripcion">Area de adscripcion<span style="color:red;">*</span></label>
                                                 <select class="form-control" id="area_adscripcion" name="area_adscripcion" required>
                                                     <?php foreach ($cat_area as $c): ?>
                                                    <option value="<?= $c->id_area_adscripcion ?>" ><?= $c->dsc_adscripcion ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div><!--end col-->
                                             <div class="col-md-4 mb-3">
                                                 <label for="nombre_completo">Nombre<span style="color:red;">*</span></label>
                                                 <select class="form-control select2" id="nombre_completo" name="nombre_completo" required>
                                                    <?php foreach ($usuarios as $p): ?>
                                                    <option value="<?= $p->id_usuario ?>" <?= ($session->get('id_usuario')) == $p->id_usuario ? 'selected' : '' ?> ><?= $p->nombre_completo ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-6">
                                                <label for="tipo_gasto">Tipo de gasto<span style="color:red;">*</span></label>
                                               <select class="form-control select2" id="tipo_gasto" name="tipo_gasto">
                                                      <?php foreach ($cat_gasto as $c): ?>
                                                    <option value="<?= $c->id_gasto ?>" ><?= $c->dsc_gasto ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="denomicacion_encargo">Denominación del encargo o comisión</label>
                                                <input type="text" class="form-control" id="denomicacion_encargo" name="denomicacion_encargo">
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-6">
                                                <label for="tipo_viaje">Tipo de Viaje<span style="color:red;">*</span></label>
                                               <select class="form-control" id="tipo_viaje" name="tipo_viaje" required>
                                                     <?php foreach ($cat_viaje as $c): ?>
                                                    <option value="<?= $c->id_viaje ?>" ><?= $c->dsc_viaje ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                     
                                        <div class="form-row"> 
                                            <div class="col-md-4 mb-3">
                                                <label for="no_personas">Número de personas encargo o comisión<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="no_personas" autocomplete="off" name="no_personas">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="importe_ejercicio">Importe ejercido por el total de acompañantes<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="importe_ejercicio" autocomplete="off" name="importe_ejercicio">
                                            </div>
                                            <!-- País -->
                                            <div class="col-md-4 mb-3">
                                                <label for="pais_origen">País origen <span style="color:red;">*</span></label>
                                                <select class="select2 form-control" id="pais_origen" name="pais_origen">
                                                <?php foreach ($cat_pais as $p): ?>
                                                    <option value="<?= $p->id_pais ?>"><?= $p->dsc_pais ?></option>
                                                <?php endforeach; ?>
                                                </select>
                                            </div>
                                           
                                        </div>
                                    <!-- En la vista -->
                                     <div class="form-row">
                                            <!-- Estado (select) -->
                                            <div class="col-md-4 mb-3" id="wrap_estado_select">
                                                <label for="estado_origen">Estado origen <span style="color:red;">*</span></label>
                                                <select class="select2 form-control" id="estado_origen" name="estado_origen_id">
                                                <!-- Lo llena JS si país = México -->
                                                </select>
                                            </div>
                                            <!-- Estado (texto libre) -->
                                            <div class="col-md-4 mb-3" id="wrap_estado_text">
                                                <label for="estado_origen_text">Estado origen <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="estado_origen_text" name="estado_origen_text" placeholder="Escribe el estado">
                                            </div>
                                            <!-- Municipio (select) -->
                                            <div class="col-md-4 mb-3" id="wrap_municipio_select">
                                                <label for="municipio_origen">Municipio origen <span style="color:red;">*</span></label>
                                                <select class="select2 form-control" id="municipio_origen" name="municipio_origen_id">
                                                <!-- Lo llena JS si estado = Guanajuato -->
                                                </select>
                                            </div>
                                            <!-- Municipio (texto libre) -->
                                            <div class="col-md-4 mb-3" id="wrap_municipio_text">
                                                <label for="municipio_origen_text">Municipio origen <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="municipio_origen_text" name="municipio_origen_text" placeholder="Escribe el municipio">
                                            </div>
                                          <!-- País Destino-->
                                            <div class="col-md-4 mb-3">
                                                <label for="pais_destino">País destino <span style="color:red;">*</span></label>
                                                <select class="select2 form-control" id="pais_destino" name="pais_destino">
                                                <?php foreach ($cat_pais as $p): ?>
                                                    <option value="<?= $p->id_pais ?>"><?= $p->dsc_pais ?></option>
                                                <?php endforeach; ?>
                                                </select>
                                            </div>
                                    </div>
                                        <div class="form-row">
                                            <!-- Estado (select) -->
                                            <div class="col-md-4 mb-3" id="destino_estado_select">
                                                <label for="estado_destino">Estado destino<span style="color:red;">*</span></label>
                                                <select class="select2 form-control" id="estado_destino" name="estado_destino_id">
                                                <!-- Lo llena JS si país = México -->
                                                </select>
                                            </div>
                                            <!-- Estado (texto libre) -->
                                            <div class="col-md-4 mb-3" id="destino_estado_text">
                                                <label for="estado_destino_text">Estado destino<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="estado_destino_text" name="estado_destino_text" placeholder="Escribe el estado">
                                            </div>
                                            <!-- Municipio (select) -->
                                            <div class="col-md-4 mb-3" id="destino_municipio_select">
                                                <label for="municipio_destino">Municipio destino<span style="color:red;">*</span></label>
                                                <select class="select2 form-control" id="municipio_destino" name="municipio_destino">
                                                <!-- Lo llena JS si estado = Guanajuato -->
                                                </select>
                                            </div>
                                            <!-- Municipio (texto libre) -->
                                            <div class="col-md-4 mb-3" id="destino_municipio_text">
                                                <label for="municipio_destino_text">Municipio/Ciudad origen <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="municipio_destino_text" name="municipio_destino_text" placeholder="Escribe el municipio">
                                            </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="motivo_encargo">Motivo del encargo o comisión<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off" id="motivo_encargo" name="motivo_encargo" >
                                            </div><!--end col-->
                                    </div>
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="fec_salida">Fecha de salida del encargo o comisión<span style="color:red;">*</span></label>
                                                <input type="date" class="form-control" autocomplete="off" id="fec_salida" name="fec_salida" >
                                            </div><!--end col-->
                                             <div class="col-md-4 mb-3">
                                                <label for="fec_regreso">Fecha de regreso del encargo o comisión<span style="color:red;">*</span></label>
                                                <input type="date" class="form-control" autocomplete="off" id="fec_regreso" name="fec_regreso">
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="importe_ejercicio_partida">Importe total erogado con motivo del encargo o comisión<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off" id="importe_ejercicio_partida" name="importe_ejercicio_partida" >
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        
                                        <div class="form-row">    
                                            <div class="col-md-4 mb-3">
                                                <label for="importe_total">Importe total de gastos no erogados derivados del encargo o comisión<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off" id="importe_total" name="importe_total" >
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="fec_entraga_informa">Fecha de entrega del informe de la comisión o encargo<span style="color:red;">*</span></label>
                                                <input type="date" class="form-control" autocomplete="off" id="fec_entraga_informa" name="fec_entraga_informa">
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="hipervinculo_informe">Hipervínculo al informe de la comisión o encargo encomendado<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off" id="hipervinculo_informe" name="hipervinculo_informe">
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="hipervinculo_factura">Hipervínculo a las facturas o comprobantes.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off" id="hipervinculo_factura" name="hipervinculo_factura" >
                                            </div><!--end col-->
                                              <div class="col-md-4 mb-3">
                                                <label for="hipervinculo_normativa">Hipervínculo a normativa que regula los gastos por concepto de viáticos y gastos de representación<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off" id="hipervinculo_normativa" name="hipervinculo_normativa">
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="area_responsabe">Área(s) responsable(s) que genera(n), posee(n), publica(n) y actualizan la información<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off" id="area_responsabe" name="area_responsabe">
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="fec_actualizacion">Fecha de actualización<span style="color:red;">*</span></label>
                                                <input type="date" class="form-control" autocomplete="off" id="fec_actualizacion" name="fec_actualizacion">
                                            </div>
                                            <div class="col-md-8 mb-3">
                                                <label for="nota">Notas<span style="color:red;">*</span></label>
                                                <textarea type="text" class="form-control" name="nota" id="nota"></textarea> 
                                            </div><!--end col-->
                                            
                                        </div><!--end form-row-->
                                            <a class="btn btn-gradient-danger" style="color:white" onclick="window.history.back()">Atrás</a>
                                            
                                             <button class="btn btn-gradient-primary" id="btnGuardarViatico" type="submit">Guardar</button>
                                            
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
        <link href="<?= base_url() ?>plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" type="text/css" />
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
         ini.inicio.formViatico();

             const inputImporte = document.getElementById('importe_ejercicio');

    inputImporte.addEventListener('blur', function () {
        let valor = inputImporte.value.replace(/[^0-9.]/g, ''); // limpia lo que no sea número o punto
        if(valor){
            let numero = parseFloat(valor);
            inputImporte.value = new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: 'MXN'
            }).format(numero);
        }
    });

  // Catálogos completos
  const CAT_PAISES     = <?= json_encode($cat_pais ?? []) ?>;
  const CAT_ESTADOS    = <?= json_encode($cat_estado ?? []) ?>;      // requiere: id_estado, dsc_estado, id_pais
  const CAT_MUNICIPIOS = <?= json_encode($cat_municipios ?? []) ?>;  // requiere: id_municipio, nombre_municipio, id_estado




$(function() {
  $('#pais_origen, #estado_origen, #municipio_origen').select2({ width: '100%' });

  function llenarSelect($sel, items, valueKey, textKey, placeholder) {
    $sel.empty();
    if (placeholder) $sel.append(new Option(placeholder, '', false, false));
    items.forEach(it => $sel.append(new Option(it[textKey], it[valueKey], false, false)));
    $sel.trigger('change.select2');
  }



  // País → decide si usamos selects o textos
  $('#pais_origen').on('change', function() {
    const idPais = $(this).val();

    if (String(idPais) === String(142)) {
        $('#wrap_estado_select').show();
        $('#wrap_estado_text').hide();

      llenarSelect($('#estado_origen'), CAT_ESTADOS, 'id_estado', 'dsc_estado', 'Seleccione estado');

      $('#municipio_origen').empty();
    } else {
      // País distinto a México: estado y municipio en texto libre
      $('#wrap_municipio_text').show();
      $('#wrap_estado_text').show();
      $('#wrap_estado_select').hide();
      $('#wrap_municipio_select').hide();
 
    }
  });
  $('#pais_destino').on('change', function() {
    const idPaisDestino = $(this).val();
    
    if (String(idPaisDestino) === String(142)) {
        $('#destino_estado_select').show();
        $('#destino_estado_text').hide();

      llenarSelect($('#estado_destino'), CAT_ESTADOS, 'id_estado', 'dsc_estado', 'Seleccione estado');

      $('#municipio_destino').empty();
    } else {
      // País distinto a México: estado y municipio en texto libre
      $('#destino_municipio_text').show();
      $('#destino_estado_text').show();
      $('#destino_estado_select').hide();
      $('#destino_municipio_select').hide();
 
    }
  });

  // Estado → decide municipio select o texto
  $('#estado_origen').on('change', function() {
    const idEstado = $(this).val();
     $('#wrap_municipio_text').hide();
      $('#wrap_municipio_select').show();
    if (String(idEstado) === String(11)) {


      llenarSelect($('#municipio_origen'), CAT_MUNICIPIOS, 'id_municipio', 'nombre_municipio', 'Seleccione municipio');
    } else {
      $('#wrap_municipio_text').show();
      $('#wrap_municipio_select').hide();
    }
  });
  // Estado → decide municipio select o texto
  $('#estado_destino').on('change', function() {
    const idEstado = $(this).val();
     $('#destino_municipio_text').hide();
      $('#destino_municipio_select').show();
    if (String(idEstado) === String(11)) {


      llenarSelect($('#municipio_destino'), CAT_MUNICIPIOS, 'id_municipio', 'nombre_municipio', 'Seleccione municipio');
    } else {
      $('#destino_municipio_text').show();
      $('#destino_municipio_select').hide();
    }
  });

  // Inicialización: seleccionar primer país y disparar lógica
  const primerPais = $('#pais_origen option:first').val();
  if (primerPais) {
    $('#pais_origen').val(primerPais).trigger('change');
  }
  const segundoPais = $('#pais_destino option:first').val();
  if (segundoPais) {
    $('#pais_destino').val(segundoPais).trigger('change');
  }
});


        </script>
