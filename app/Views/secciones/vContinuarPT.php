

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
                                        <li class="breadcrumb-item active">PT</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Formulario PT</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>
                    
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="mt-0 header-title">PROVEEDOR: <strong><?= (isset($reserva->razon_social) && !empty($reserva->razon_social))?$reserva->razon_social:$registro_pt->dsc_proveedor ?></strong></h3>
                                    <p class="text-muted mb-3" >
                                        <?= (isset($proveedor->no_proveedor) && !empty($proveedor->no_proveedor))?'No. Proveedor '.$proveedor->no_proveedor:'' ?>
                                    </p>
                                   <form id="form_next_pt" enctype="multipart/form-data">
                                        <input type="hidden" name="id_proveedor" id="id_proveedor" value="<?= (isset($reserva->id_proveedor) && !empty($reserva->id_proveedor))?$reserva->id_proveedor:$registro_pt->id_proveedor?>" >
                                        <input type="hidden" name="editar" id="editar" value="<?= $editar?>">
                                        <input type="hidden" name="id_reserva" id="id_reserva" value="<?= $id_reserva?>">
                                        <?php if(isset($registro_pt->id_registro_pt) && !empty($registro_pt->id_registro_pt)): ?>
                                        <input type="hidden" name="id_registro_pt" id="id_registro_pt" value="<?= $registro_pt->id_registro_pt?>">
                                        <?php endif; ?>
                                       <div class="form-row">
                                            <!-- Dirección Responsable -->
                                            <div class="col-md-4 mb-3">
                                                <label for="direccion_responsable">Dirección Responsable <span class="text-danger">*</span></label>
                                                <input type="text" id="direccion_responsable" name="direccion_responsable" class="form-control" value="<?= $direccion_responsable ?>" readonly>
                                              
                                            </div><!--end col-->
                                            
                                            <!-- Tipo de PT -->
                                            <div class="col-md-4 mb-3">
                                                <label for="tipo_pt">Tipo de PT <span class="text-danger">*</span></label>
                                                  <input type="text" id="tipo_pt" name="tipo_pt" class="form-control" value="<?= $registro_pt->dsc_tipo ?>" readonly>
                                            </div><!--end col-->
                                            
                                            <!-- Fecha de Trámite -->
                                            <div class="col-md-4 mb-3">
                                                <label for="fecha_tramite">Fecha de Trámite <span class="text-danger">*</span></label>
                                              <input type="date" class="form-control" id="fecha_tramite" name="fecha_tramite" 
                                                value="<?= isset($registro_pt->fecha_tramite) ? date('Y-m-d', strtotime($registro_pt->fecha_tramite)) : date('Y-m-d') ?>" 
                                                required>

                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-6 mb-6">
                                                <label for="reponsable_solicitud">Responsable del Gasto<span style="color:red;">*</span></label>
                                                 <input type="text" id="reponsable_solicitud" name="reponsable_solicitud" class="form-control" value="<?= $registro_pt->responsable ?>" readonly>

                                            </div><!--end col-->
                                            <div class="col-md-6 mb-6">
                                                <label for="director_generar">Director/a General Administrativa <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="director_generar" value="<?= $dsc_director_general ?>" name="director_generar" readonly>
                                           
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                           <div class="col-md-6 mb-6">
                                                <label for="secretario">Secretario(a) o Director(a) que autoriza</label>
                                               <input type="text" class="form-control" id="secretario" value="<?= $registro_pt->secretario ?>" name="secretario" readonly>
                                            </div><!--end col-->
                                            <div class="col-md-6 mb-6">
                                                <label for="id_subsecretario">Subsecreatrio(a) o Director(a) General Responsable</label>
                                                 <input type="text" class="form-control" value="<?= $subsecretario?>" readonly>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="cuenta_bancaria">Cuenta Bancaria del Proveedor <span style="color:red;">*</span></label>
                                                 <select type="text" class="form-control" id="cuenta_bancaria"  name="cuenta_bancaria" >
                                                  <?php foreach( $idproveedor as $o ): ?>
                                                    <option value="<?=$o->id_proveedor_banco ?>" <?= (isset($registro_pt->id_proveedor_banco) && $registro_pt->id_proveedor_banco == $o->id_proveedor_banco)?'selected':'' ?> ><?=$o->banco.'/'.$o->no_cuenta.'/'.$o->clabe ?></option>
                                                  <?php endforeach; ?>
                                               </select>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="fecha_gasto_inicio">Fecha de gasto inicio <span style="color:red;">*</span></label>
                                                <input type="date" class="form-control" id="fecha_gasto_inicio" name="fecha_gasto_inicio" 
                                                value="<?= isset($registro_pt->fecha_gasto_inicio) ? date('Y-m-d', strtotime($registro_pt->fecha_gasto_inicio)) : date('Y-m-d') ?>" 
                                                required>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                 <label for="fecha_gasto_fin">Fecha de gasto fin <span style="color:red;">*</span></label>
                                                <input type="date" class="form-control" id="fecha_gasto_fin" name="fecha_gasto_fin" 
                                                value="<?= isset($registro_pt->fecha_gasto_fin) ? date('Y-m-d', strtotime($registro_pt->fecha_gasto_fin)) : date('Y-m-d') ?>" 
                                                required>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-6 mb-6">
                                                <label for="formato_establecido">Formatos establecidos en los Lineamientos Generales de Racionalidad, Austeridad y Disciplina Presupuestal de la Administración Pública Estatal vigente o formatos establecidos en la regulación del trámite ingresado.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="formato_establecido" value="SI" name="formato_establecido" readonly>
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-6 mb-6">
                                                <label for="documentacion_comprobatoria">Documentación comprobatoria fiscalmente requisitada, atendiendo a lo establecido en los Lineamientos Generales de Racionalidad, Austeridad y Disciplina Presupuestal de la Administración Pública Estatal vigentes.<span style="color:red;">*</span></label>
                                                <select type="text" class="form-control" id="documentacion_comprobatoria"  name="documentacion_comprobatoria" >
                                                  <?php foreach( $cat_opcion as $o ): ?>
                                                    <option value="<?=$o->id_opcion ?>" <?= (isset($registro_pt->documentacion_comprobatoria) && $registro_pt->documentacion_comprobatoria == $o->id_opcion)?'selected':'' ?> ><?=$o->des_opcion ?></option>
                                                  <?php endforeach; ?>
                                               </select>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="poliza">Pólizas Contables.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="poliza" value="SI" name="poliza" readonly>
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="formato_conformidad">Formato de conformidad del producto recibido.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="formato_conformidad" value="<?=($registro_pt->formato_conformidad == 1)?'SI':'NO'?>" name="formato_conformidad" readonly>
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="contrato_convenio">Contrato o Convenio.<span style="color:red;">*</span></label>
                                                <select type="text" class="form-control" id="contrato_convenio"  name="contrato_convenio" >
                                                  <?php foreach( $cat_opcion as $o ): ?>
                                                    <option value="<?=$o->id_opcion ?>" <?= (isset($registro_pt->contrato_convenio) && $registro_pt->contrato_convenio == $o->id_opcion)?'selected':'' ?> ><?=$o->des_opcion ?></option>
                                                  <?php endforeach; ?>
                                               </select>
                                               
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="documentacion_requerida">Documentación requerida para emitir el pago.<span style="color:red;">*</span></label>
                                                 <select type="text" class="form-control" id="documentacion_requerida"  name="documentacion_requerida" >
                                                 <?php foreach( $cat_opcion as $o ): ?>
                                                    <option value="<?=$o->id_opcion ?>" <?= (isset($registro_pt->documentacion_requerida) && $registro_pt->documentacion_requerida == $o->id_opcion)?'selected':'' ?> ><?=$o->des_opcion ?></option>
                                                  <?php endforeach; ?>
                                               </select>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="evidencia_entrega">Evidencia de entregable.<span style="color:red;">*</span></label>
                                                <select type="text" class="form-control" id="evidencia_entrega"  name="evidencia_entrega" >
                                               <?php foreach( $cat_opcion as $o ): ?>
                                                    <option value="<?=$o->id_opcion ?>" <?= (isset($registro_pt->evidencia_entrega) && $registro_pt->evidencia_entrega == $o->id_opcion)?'selected':'' ?> ><?=$o->des_opcion ?></option>
                                                <?php endforeach; ?>
                                               </select>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="otros">Otros</label>
                                                <input type="text" class="form-control" id="otros"  name="otros" value="<?= $registro_pt->otros ?>" readonly>
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="clausula_contrato">Claúsula del contrato.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" readonly id="clausula_contrato" name="clausula_contrato" value="<?=(isset($registro_pt->clausula_contrato))?$registro_pt->clausula_contrato:'TERCERA'?>">
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="concepto_pago">Concepto del pago.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" readonly  autocomplete="off" id="concepto_pago" name="concepto_pago" value="<?= (isset($registro_pt->concepto_pago))?$registro_pt->concepto_pago:'' ?>" >
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="comision">Comisión / Reunión / Evento / Programa</label>
                                                <input type="text" class="form-control" readonly id="comision"  name="comision" value="<?= (isset($registro_pt->comision))?$registro_pt->comision:'Comisión / Reunión / Evento / Programa' ?>" >
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="no_reserva">No. de Reserva.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off" id="no_reserva" name="no_reserva"  value="<?= (isset($registro_pt->no_reserva))?$registro_pt->no_reserva:'' ?>" readonly>
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                              
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        
                                       
                                           <?php
                                                $partidas_mostradas = [];
                                                foreach($presupuesto as $i => $p):
                                                    // Evita duplicados por id_partida
                                                    if (in_array($p->id_partida, $partidas_mostradas)) {
                                                        continue;
                                                    }
                                                    $partidas_mostradas[] = $p->id_partida;
                                                ?>
                                                    <p class="text-muted mb-4 text-center">Agregar Factura PT.</p>
                                                    <hr>
                                                    <div class="form-row"> <!-- presupuesto -->
                                                        <!-- Partida y Factura PDF -->
                                                        <div class="col-md-4 mb-3">
                                                            <label for="partida_<?= $i ?>">Partida<span style="color:red;">*</span></label>
                                                            <select class="form-control" id="partida_<?= $i ?>" name="partida[]" disabled>
                                                                <?php foreach($cat_partida as $o): ?>
                                                                    <option value="<?= $o->id_partida ?>" <?= (isset($p->id_partida) && $p->id_partida == $o->id_partida) ? 'selected' : '' ?>>
                                                                        <?= $o->cuenta_cable ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <!-- Encabezado y XML -->
                                                        <div class="col-md-4 mb-3">
                                                            <label for="encabezado_<?= $i ?>">Encabezado<span style="color:red;">*</span></label>
                                                            <input type="text" class="form-control" readonly autocomplete="off" id="encabezado_<?= $i ?>" name="encabezado[]" value="<?= (isset($p->encabezado) && !empty($p->encabezado)?$p->encabezado:'') ?>" >
                                                        </div>

                                                        <!-- Periodo -->
                                                        <div class="col-md-2 mb-3">
                                                            <label for="periodo_inicio<?= $i ?>">Inicio<span style="color:red;">*</span></label>
                                                            <div class="input-group">                                            
                                                                <input type="date" class="form-control"  id="periodo_inicio<?= $i ?>" name="periodo_inicio[]">
                                                                
                                                            </div>
                                                        </div>
                                                         <div class="col-md-2 mb-3">
                                                            <label for="periodo_fin<?= $i ?>">Termino<span style="color:red;">*</span></label>
                                                            <div class="input-group">                                            
                                                                <input type="date" class="form-control"  id="periodo_fin<?= $i ?>" name="periodo_fin[]">
                                                                
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="col-md-4 mb-3">
                                                            <p class="text-muted mb-3">Factura PDF (Máx 100MB)</p>
                                                            <input id="factura_pdf_input_<?= $i; ?>"  type="file" name="factura_pdf_<?= $i; ?>[]" class="dropify" multiple accept=".pdf" />   
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                          
                                                            <p class="text-muted mb-3">Factura XML (Máx 100MB)</p>
                                                            <input id="factura_xml_input_<?= $i; ?>" type="file" name="factura_xml_<?= $i; ?>[]" multiple class="dropify"  accept=".xml">
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                          
                                                            <p class="text-muted mb-3">Importe</p>
                                                    <input class="form-control" type="text" id="importe" name="importe" placeholder="0,000.00" onblur="formatearSimple(this)">
                                                        </div>
                                                    </div>
                                                    
                                                <?php endforeach; ?>
                                                    
                                        

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
        <script src="<?= base_url()?>plugins/apexcharts/apexcharts.min.js"></script> 

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

        <script>
            ini.inicio.nextFormPT();
             $('.add-file').on('click', function(e) {
                e.preventDefault();
                const inputId = $(this).data('target');
                $(inputId).click();
            });
            $('input[name="datetimes[]"]').daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss'
                }
            });
            $(document).ready(function() {
    $('.select2').select2({
                placeholder: "Selecciona un responsable",
                allowClear: true,
                width: '100%', // Para que ocupe todo el ancho
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    }
                }
            });
        });
     function formatearSimple(input) {
            // Limpiar y convertir
            let valor = input.value.replace(/[^\d.]/g, '');
            let numero = parseFloat(valor);
            
            if (!isNaN(numero)) {
                // Formatear con separadores de miles y 2 decimales
                input.value = numero.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            } else {
                input.value = '0.00';
            }
        }

        </script>
