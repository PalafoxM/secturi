

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
                                    <h3 class="mt-0 header-title">PROVEEDOR: <strong> GOBIERNO DEL ESTADO DE GUANAJUATO SFIYA SECRETARIA DE TURISMO</strong></h3>
                                    <p class="text-muted mb-3" >
                                      GEG850101FQ2
                                    </p>
                                   <form id="form_go" enctype="multipart/form-data">
                                        <input type="hidden" name="id_reserva_go" value="<?= $id_reserva ?>">
                                       <div class="form-row">
                                            <!-- Dirección Responsable -->
                                            <div class="col-md-4 mb-3">
                                                <label for="direccion_responsable">Dirección Responsable <span class="text-danger">*</span></label>
                                                <select class="form-control" id="direccion_responsable" name="direccion_responsable" required>
                                                    <?php foreach($cat_area as $a): ?>
                                                    <option value="<?=$a->id_area?>" <?php echo ($a->id_area == $usuario->id_area) ? 'selected' : ''; ?>>
                                                        <?=$a->dsc_area?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Por favor ingrese la dirección responsable
                                                </div>
                                            </div><!--end col-->
                                            <!-- Fecha de Trámite -->
                                            <div class="col-md-4 mb-3">
                                                <label for="fecha_tramite">Fecha de Trámite <span class="text-danger">*</span></label>
                                              <input type="date" class="form-control" id="fecha_tramite" name="fecha_tramite" 
                                                value="<?= isset($registro_pt->fecha_tramite) ? date('Y-m-d', strtotime($registro_pt->fecha_tramite)) : date('Y-m-d') ?>" 
                                                required>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="reponsable_solicitud">Responsable de la Solicitud <span style="color:red;">*</span></label>
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
                                                <label for="director_generar">Director/a General Administrativa <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="director_generar" value="<?= $dsc_director_general ?>" name="director_generar" >
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="secretario">Secretario(a) o Director(a) que autoriza</label>
                                                <select type="text" class="form-control" id="secretario" placeholder="Secretario/a" name="secretario">
                                                            <option value="0" selected >Seleccione una opcion</option>
                                                    <?php foreach($secretario as $s): ?>
                                                        <?php if(isset($registro_pt->secretario) && !empty($registro_pt->secretario)){  ?>
                                                        <option value="<?= $s->id_secretario?>" <?= ($s->id_secretario == $registro_pt->secretario)?'selected':'' ?> ><?= $s->dsc_secretario?></option>
                                                         <?php }else{ ?>
                                                              <option value="<?= $s->id_secretario?>" ><?= $s->dsc_secretario?></option>
                                                         <?php } ?>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div><!--end col-->
                                               <div class="col-md-4 mb-3">
                                                <label for="secretario">Subsecretario(a) o Director(a) General Responsable</label><span class="text-danger">*</span>
                                                <select type="text" class="form-control" id="secretario" placeholder="Secretario/a" name="secretario">
                                                            <option value="0" selected >Seleccione una opcion</option>
                                                    <?php foreach($cat_subsecretario as $s): ?>
                                                        <?php if(isset($registro_pt->id_subsecretario) && !empty($registro_pt->id_subsecretario)){  ?>
                                                        <option value="<?= $s->id_subsecretario?>" <?= ($s->id_subsecretario == $registro_pt->id_subsecretario)?'selected':'' ?> ><?= $s->dsc_subsecretario?></option>
                                                         <?php }else{ ?>
                                                              <option value="<?= $s->id_subsecretario?>" ><?= $s->dsc_subsecretario?></option>
                                                         <?php } ?>
                                                    <?php endforeach; ?>
                                                </select>
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
                                                <input type="text" class="form-control" id="formato_conformidad" value="NO" name="formato_conformidad" readonly>
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="contrato_convenio">Contrato o Convenio.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="contrato_convenio" value="NO" name="contrato_convenio" readonly>
                                               
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="documentacion_requerida">Documentación requerida para emitir el pago.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="documentacion_requerida" value="SI" name="documentacion_requerida" readonly>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="evidencia_entrega">Evidencia de entregable.<span style="color:red;">*</span></label>
                                                <select type="text" class="form-control" id="evidencia_entrega"  name="evidencia_entrega" >
                                               <?php foreach( $cat_opcion as $o ): ?>
                                                    <option value="<?=$o->id_opcion ?>" <?= ($o->id_opcion == 2)?'selected':'' ?> ><?=$o->des_opcion ?></option>
                                                <?php endforeach; ?>
                                               </select>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="concepto_gasto">Concepto del gasto<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="concepto_gasto" placeholder="Concepto del gasto" name="concepto_gasto" >
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                       
                                        
                                            <div class="col-md-4 mb-3">
                                                <label for="comision">Comisión / Reunión / Evento / Programa</label>
                                                <input type="text" class="form-control" id="comision"  name="comision" value="<?= (isset($registro_pt->comision))?$registro_pt->comision:'Comisión / Reunión / Evento / Programa' ?>" >
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="lugar">Lugar<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="lugar" placeholder="Lugar" name="lugar" >
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="no_reserva">No. de Reserva.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off" id="no_reserva" name="no_reserva"  value="<?= (isset($reserva->no_reserva))?$reserva->no_reserva:'' ?>" readonly>
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                          
                                            <div class="col-md-4 mb-3">
                                                <label for="no_consecutivo">No. Consecutivo.<span style="color:red;">*</span></label>
                                                <input type="number" class="form-control" autocomplete="off" id="no_consecutivo" name="no_consecutivo" placeholder="001"  value="<?= (isset($reserva->no_consecutivo))?$reserva->no_consecutivo:'' ?>" >
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                       
                                        </div><!--end form-row-->
                                        <br>
                                       
                                           <?php
                                                $partidas_mostradas = [];
                                                foreach($presupuesto as $i => $p):
                                                    // Evita duplicados por id_partida
                                                    if (in_array($p->id_partida, $partidas_mostradas)) {
                                                        continue;
                                                    }
                                                    $partidas_mostradas[] = $p->id_partida;
                                                ?>
                                                    <p class="text-muted mb-4 text-center">Agregar Factura GO.</p>
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
                                                            <input type="text" class="form-control" autocomplete="off" id="encabezado_<?= $i ?>" name="encabezado[]">
                                                        </div>

                                                        <!-- Periodo -->
                                                        <div class="col-md-4 mb-3">
                                                            <label for="periodo_<?= $i ?>">Periodo<span style="color:red;">*</span></label>
                                                            <div class="input-group">                                            
                                                                <input type="text" class="form-control"  id="periodo_<?= $i ?>" name="periodo[]">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text"><i class="dripicons-calendar"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="col-md-6 mb-3">
                                                            <p class="text-muted mb-3">Factura PDF (Máx 5MB)</p>
                                                            <input id="factura_pdf_input_<?= $i; ?>"  type="file" name="factura_pdf_<?= $i; ?>[]" class="dropify" multiple accept=".pdf" />   
                                                        </div>
                                                     
                                                    </div>
                                                <?php endforeach; ?>
                                            <a class="btn btn-gradient-danger" style="color:white" onclick="window.history.back()">Atrás</a>
                                              <?php if(!$edita): ?>
                                            <button class="btn btn-gradient-primary" id="btnGuardaGo" type="submit">Guardar</button>
                                            <?php endif; ?>
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
        
        <!-- App js -->
        <script src="<?= base_url()?>assets/js/app.js"></script>

    

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
            ini.inicio.formGo();
             $('.add-file').on('click', function(e) {
                e.preventDefault();
                const inputId = $(this).data('target');
                $(inputId).click();
            });

        </script>
