

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
                                   <form id="form_proveedor" enctype="multipart/form-data">
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
                                            <div class="col-md-4 mb-3">
                                                <label for="denominacion">Denominación del puesto<span style="color:red;">*</span></label>
                                               <textarea class="form-control" id="denominacion" name="denominacion">
                                                </textarea>

                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="denomicacion_carga">Denominación del cargo<span style="color:red;">*</span></label>
                                                <textarea type="text" class="form-control" id="denomicacion_carga" name="denomicacion_carga" ></textarea>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="tipo_integrante">Denominación del encargo o comisión</label>
                                                <textarea type="text" class="form-control" id="denomicacion_carga" name="denomicacion_carga" ></textarea>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="clave_nivel">Clave o nivel del puesto<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="clave_nivel" name="clave_nivel" >
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="fecha_gasto_inicio">Area de adscripcion<span style="color:red;">*</span></label>
                                                <input type="date" class="form-control" id="fecha_gasto_inicio" name="fecha_gasto_inicio" required>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                 <label for="fecha_gasto_fin">Nombre<span style="color:red;">*</span></label>
                                                <input type="date" class="form-control" id="fecha_gasto_fin" name="fecha_gasto_fin" 
                                                required>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-6">
                                                <label for="formato_establecido">Tipo de integrante del sujeto obligado<span style="color:red;">*</span></label>
                                                  <select class="form-control" id="tipo_integrante" name="tipo_integrante" required>
                                                    <option value="1" ></option>
                                                </select>
                                            </div><!--end col-->
                                              <div class="col-md-4 mb-6">
                                                <label for="documentacion_comprobatoria">Tipo de Viaje<span style="color:red;">*</span></label>
                                               <select class="form-control" id="direccion_responsable" name="direccion_responsable" required>
                                                    <option value="1" ></option>
                                                </select>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                           <div class="col-md-4 mb-6">
                                                <label for="documentacion_comprobatoria">Tipo de gasto<span style="color:red;">*</span></label>
                                               <select class="form-control" id="direccion_responsable" name="direccion_responsable">
                                                    <option value="1" ></option>
                                                </select>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="no_personas">Número de personas acompañantes en el encargo o comisión<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="no_personas" value="SI" name="no_personas">
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="importe_ejercicio">Importe ejercido por el total de acompañantes<span style="color:red;">*</span></label>
                                              <select class="form-control" id="importe_ejercicio" name="importe_ejercicio">
                                                    <option value="1" ></option>
                                                </select>
                                               
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="no_consecutivo">Fecha de actualización<span style="color:red;">*</span></label>
                                                <input type="number" class="form-control" autocomplete="off" id="no_consecutivo" name="no_consecutivo" placeholder="001">
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="evidencia_entrega">País origen del encargo o comisión<span style="color:red;">*</span></label>
                                               <select class="form-control" id="direccion_responsable" name="direccion_responsable" required>
                                                    <option value="1" ></option>
                                                </select>
                                            </div><!--end col-->
                                             <div class="col-md-4 mb-3">
                                                <label for="evidencia_entrega">Estado origen del encargo o comisión<span style="color:red;">*</span></label>
                                               <select class="form-control" id="direccion_responsable" name="direccion_responsable" required>
                                                    <option value="1" ></option>
                                                </select>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="clausula_contrato">Ciudad origen del encargo o comisión<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="clausula_contrato" name="clausula_contrato">
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="concepto_pago">País destino del encargo o comisión<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control"  autocomplete="off" id="concepto_pago" name="concepto_pago">
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="comision">Estado destino del encargo o comisión</label>
                                                <input type="text" class="form-control" id="comision"  name="comision" >
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="no_reserva">Ciudad destino del encargo o comisión<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off" id="no_reserva" name="no_reserva">
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="no_consecutivo">Motivo del encargo o comisión<span style="color:red;">*</span></label>
                                                <input type="number" class="form-control" autocomplete="off" id="no_consecutivo" name="no_consecutivo" placeholder="001">
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="no_consecutivo">Fecha de salida del encargo o comisión<span style="color:red;">*</span></label>
                                                <input type="number" class="form-control" autocomplete="off" id="no_consecutivo" name="no_consecutivo" placeholder="001">
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="no_reserva">Fecha de regreso del encargo o comisión<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off" id="no_reserva" name="no_reserva">
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="no_consecutivo">Importe ejercido por partida por concepto<span style="color:red;">*</span></label>
                                                <input type="number" class="form-control" autocomplete="off" id="no_consecutivo" name="no_consecutivo" placeholder="001">
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="no_consecutivo">Importe total erogado con motivo del encargo o comisión<span style="color:red;">*</span></label>
                                                <input type="number" class="form-control" autocomplete="off" id="no_consecutivo" name="no_consecutivo" placeholder="001">
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="no_reserva">Fecha de entrega del informe de la comisión o encargo<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off" id="no_reserva" name="no_reserva">
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="no_consecutivo">Hipervínculo al informe de la comisión o encargo encomendado<span style="color:red;">*</span></label>
                                                <input type="number" class="form-control" autocomplete="off" id="no_consecutivo" name="no_consecutivo" placeholder="001">
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="no_consecutivo">Hipervínculo a las facturas o comprobantes.<span style="color:red;">*</span></label>
                                                <input type="number" class="form-control" autocomplete="off" id="no_consecutivo" name="no_consecutivo" placeholder="001">
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                            <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="no_reserva">Hipervínculo a normativa que regula los gastos por concepto de viáticos y gastos de representación<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off" id="no_reserva" name="no_reserva">
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="no_consecutivo">Área(s) responsable(s) que genera(n), posee(n), publica(n) y actualizan la información<span style="color:red;">*</span></label>
                                                <input type="number" class="form-control" autocomplete="off" id="no_consecutivo" name="no_consecutivo" placeholder="001">
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="no_consecutivo">Fecha de actualización<span style="color:red;">*</span></label>
                                                <input type="number" class="form-control" autocomplete="off" id="no_consecutivo" name="no_consecutivo" placeholder="001">
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-12 mb-3">
                                                <label for="no_reserva">Notas<span style="color:red;">*</span></label>
                                                <textarea type="text" class="form-control"></textarea> 
                                            </div><!--end col-->
                                            
                                        </div><!--end form-row-->
                                            <a class="btn btn-gradient-danger" style="color:white" onclick="window.history.back()">Atrás</a>
                                            
                                             <button class="btn btn-gradient-primary" id="btnGuardatPT" type="submit">Guardar</button>
                                            
                                    </form> <!--end form-->                                          
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->

                   
                    </div><!--end row-->


                </div><!-- container -->
            </div>
        </div>
           <!--Form Wizard-->
         <link rel="stylesheet" href="<?= base_url()?>plugins/jquery-steps/jquery.steps.css">

        <!-- App css -->
        <link href="<?= base_url()?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url()?>assets/css/jquery-ui.min.css" rel="stylesheet">
        <link href="<?= base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url()?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url()?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

               <!-- Plugins css -->
        <link href="<?= base_url()?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
        <link href="<?= base_url()?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url()?>plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url()?>plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
        <link href="<?= base_url()?>plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />

  
        
        <!-- jQuery  -->
        <script src="<?= base_url()?>assets/js/jquery.min.js"></script>
        <script src="<?= base_url()?>assets/js/jquery-ui.min.js"></script>
        <script src="<?= base_url()?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url()?>assets/js/metismenu.min.js"></script>
        <script src="<?= base_url()?>assets/js/waves.js"></script>
        <script src="<?= base_url()?>assets/js/feather.min.js"></script>
        <script src="<?= base_url()?>assets/js/jquery.slimscroll.min.js"></script>
        

        <script src="<?= base_url()?>plugins/jquery-steps/jquery.steps.min.js"></script>
        <script src="<?= base_url()?>assets/pages/jquery.form-wizard.init.js"></script>
        

        <!-- Plugins js -->
        <script src="<?= base_url()?>plugins/moment/moment.js"></script>
        <script src="<?= base_url()?>plugins/daterangepicker/daterangepicker.js"></script>
        <script src="<?= base_url()?>plugins/select2/select2.min.js"></script>
        <script src="<?= base_url()?>plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <script src="<?= base_url()?>plugins/timepicker/bootstrap-material-datetimepicker.js"></script>
        <script src="<?= base_url()?>plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="<?= base_url()?>plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>

        <script src="<?= base_url()?>assets/pages/jquery.forms-advanced.js"></script>
        

     
        


        <script>
            ini.inicio.formPT();
             $('.add-file').on('click', function(e) {
                e.preventDefault();
                const inputId = $(this).data('target');
                $(inputId).click();
            });

        </script>
