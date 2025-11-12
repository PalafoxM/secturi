

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
                                   <form id="form_proveedor" enctype="multipart/form-data">
                                        <input type="hidden" name="id_proveedor" id="id_proveedor" value="<?= (isset($reserva->id_proveedor) && !empty($reserva->id_proveedor))?$reserva->id_proveedor:$registro_pt->id_proveedor?>" >
                                        <input type="hidden" name="editar" id="editar" value="<?= $editar?>">
                                        <input type="hidden" name="id_reserva" id="id_reserva" value="<?= $id_reserva?>">
                                        <?php if(isset($registro_pt->id_registro_pt) && !empty($registro_pt->id_registro_pt)): ?>
                                        <input type="hidden" name="id_registro_pt" id="id_registro_pt" value="<?= $registro_pt->id_registro_pt?>">
                                        <?php endif; ?>
                                       <div class="form-row">
                                            <!-- Dirección Responsable -->
                                             <?php if($editar != 1): ?>
                                            <div class="col-md-4 mb-3">
                                                <label for="direccion_responsable">Dirección Responsable <span class="text-danger">*</span></label>
                                                <select class="form-control" id="direccion_responsable" name="direccion_responsable" required>
                                                    <?php foreach($cat_area as $a): ?>
                                                    <option value="<?=$a->id_area?>" <?php echo ($a->id_area == $usuario->id_area) ? 'selected' : ''; ?>>
                                                        <?=$a->dsc_area?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div><!--end col-->
                                             <?php endif; ?>
                                             <?php if($editar == 1): ?>
                                            <div class="col-md-4 mb-3">
                                                <label for="direccion_responsable">Dirección Responsable <span class="text-danger">*</span></label>
                                                <select class="form-control" id="direccion_responsable" name="direccion_responsable" required>
                                                    <?php foreach($cat_area as $a): ?>
                                                    <option value="<?=$a->id_area?>" <?php echo ($a->id_area == $registro_pt->id_direccion_responsable) ? 'selected' : ''; ?>>
                                                        <?=$a->dsc_area?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div><!--end col-->
                                             <?php endif; ?>
                                            
                                            <!-- Tipo de PT -->
                                            <div class="col-md-4 mb-3">
                                                <label for="tipo_pt">Tipo de PT <span class="text-danger">*</span></label>
                                                <select class="form-control" id="tipo_pt" name="tipo_pt" >
                                                    <?php foreach($cat_tipo as $p): ?>
                                                        <option value="<?=$p->id_tipo?>" <?=(isset($registro_pt->id_tipo) && $registro_pt->id_tipo == $p->id_tipo )?'selected':''?> ><?= $p->des_tipo ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Por favor seleccione el tipo de PT
                                                </div>
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
                                              <select name="id_reponsable_solicitud" class="form-control select2" required>
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
                                            <div class="col-md-6 mb-6">
                                                <label for="director_generar">Director/a General Administrativa <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="director_generar" value="<?= $dsc_director_general ?>" name="director_generar" >
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                           <div class="col-md-6 mb-6">
                                                <label for="secretario">Secretario(a) o Director(a) que autoriza</label>
                                                <select type="text" class="form-control" id="secretario"  name="secretario">
                                                            <option value="0" selected >Seleccione una opcion</option>
                                                    <?php foreach($secretario as $s): ?>
                                                        <?php if(isset($registro_pt->id_secretario) && !empty($registro_pt->id_secretario)){  ?>
                                                        <option value="<?= $s->id_secretario?>" <?= ($s->id_secretario == $registro_pt->id_secretario)?'selected':'' ?> ><?= $s->dsc_secretario?></option>
                                                         <?php }else{ ?>
                                                              <option value="<?= $s->id_secretario?>" ><?= $s->dsc_secretario?></option>
                                                         <?php } ?>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div><!--end col-->
                                            <div class="col-md-6 mb-6">
                                                <label for="id_subsecretario">Subsecreatrio(a) o Director(a) General Responsable</label>
                                                <select type="text" class="form-control" id="id_subsecretario" name="id_subsecretario">
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
                                            <div class="col-md-4 mb-3">
                                                <label for="cuenta_bancaria">Cuenta Bancaria del Proveedor <span style="color:red;">*</span></label>
                                                <input readonly type="text" class="form-control" id="cuenta_bancaria" name="cuenta_bancaria" value="<?= (isset($reserva->banco_completo) && !empty($reserva->banco_completo))?$reserva->banco_completo:''?>">
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
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
                                                <input type="text" class="form-control" id="formato_conformidad" value="<?=($partida4000)?'NO':'SI'?>" name="formato_conformidad" readonly>
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
                                                <input type="text" class="form-control" id="otros"  name="otros"  value="<?= (isset($registro_pt->otros))?$registro_pt->otros:''?>">
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="clausula_contrato">Claúsula del contrato.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="clausula_contrato" name="clausula_contrato" value="<?=(isset($registro_pt->clausula_contrato))?$registro_pt->clausula_contrato:'TERCERA'?>">
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="concepto_pago">Concepto del pago.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control"  autocomplete="off" id="concepto_pago" name="concepto_pago" value="<?= (isset($registro_pt->concepto_pago))?$registro_pt->concepto_pago:'' ?>" >
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="comision">Comisión / Reunión / Evento / Programa</label>
                                                <input type="text" class="form-control" id="comision"  name="comision" value="<?= (isset($registro_pt->comision))?$registro_pt->comision:'Comisión / Reunión / Evento / Programa' ?>" >
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="no_reserva">No. de Reserva.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off" id="no_reserva" name="no_reserva"  value="<?= (isset($reserva->no_reserva))?$reserva->no_reserva:'' ?>" readonly>
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="no_consecutivo">No. Consecutivo.</label>
                                                <input type="text" class="form-control" autocomplete="off" id="no_consecutivo" name="no_consecutivo" value="<?= (isset($no_consecutivo))?$no_consecutivo:''?>"  >
                                            </div><!--end col-->
                                            
                                        </div><!--end form-row-->
                                        <?php
                                            $partidas_mostradas = [];
                                            $total_partidas = count($presupuesto);
                                            foreach($presupuesto as $i => $p):
                                                // Evita duplicados por id_partida
                                            
                                                if (in_array($p->id_partida, $partidas_mostradas)) {
                                                    continue;
                                                }
                                                $partidas_mostradas[] = $p->id_partida;

                                                // Generamos un ID único para la sección de factura
                                                $section_id = "factura-section-" . $i;
                                            ?>
                                                <p class="text-muted mb-4 text-center">Agregar Factura PT.</p>
                                                <hr>
                                                <div class="form-row">
                                                    <div class="col-md-2 mb-3">
                                                        <label for="partida_<?= $i ?>">Partida<span style="color:red;">*</span></label>
                                                        <select class="form-control" id="partida_<?= $i ?>" name="partida[]" disabled>
                                                            <?php foreach($cat_partida as $o): ?>
                                                                <option value="<?= $o->id_partida ?>" <?= (isset($p->id_partida) && $p->id_partida == $o->id_partida) ? 'selected' : '' ?>>
                                                                    <?= $o->cuenta_cable ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 mb-3">
                                                        <label for="proyecto_<?= $i ?>">Proyecto<span style="color:red;">*</span></label>
                                                        <select class="form-control" id="proyecto_<?= $i ?>" name="proyecto[]" disabled>
                                                            <?php foreach($cat_proyecto as $o): ?>
                                                                <option value="<?= $o->id_proyecto ?>" <?= (isset($p->id_proyecto) && $p->id_proyecto == $o->id_proyecto) ? 'selected' : '' ?>>
                                                                    <?= $o->proyecto ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <label for="encabezado_<?= $i ?>">Encabezado<span style="color:red;">*</span></label>
                                                        <input type="text" class="form-control" autocomplete="off" id="encabezado_<?= $i ?>" name="encabezado[]" value="<?= (isset($p->encabezado) && !empty($p->encabezado)?$p->encabezado:'') ?>" >
                                                    </div>
                                                    
                                                    <!-- CHECKBOX CORREGIDO: Aparece en todos menos el primero cuando hay más de un elemento -->
                                                    <?php if(isset($num) && $num): ?>
                                                    <div class="col-md-2 mb-3">
                                                        <div class="checkbox checkbox-primary">
                                                            <input id="checkbox_<?= $i ?>" type="checkbox" name="checkbox_<?= $i ?>" 
                                                                class="toggle-factura-section" data-target="#<?= $section_id ?>">
                                                            <label for="checkbox_<?= $i ?>">
                                                                Pagar en otro periodo
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- SECCIÓN DE FACTURA UNIFICADA -->
                                                <div id="<?= $section_id ?>">
                                                    <div class="form-row">
                                                        <div class="col-md-4 mb-3">
                                                            <?php if( isset($factura_pdf[$i]) && !empty($factura_pdf[$i])): ?>
                                                                <input type="hidden" id="editarPDF_<?= $i; ?>"  name="editarPDF[]" value="<?= $factura_pdf[$i]->id_factura_pdf ?>"  >
                                                                <input type="hidden" id="editarXML_<?= $i; ?>" name="editarXML[]" value="<?= $factura[$i]->id_factura ?>"  >
                                                                <input type="hidden" id="editarPeriodo_<?= $i; ?>" name="editarPe[]" value="<?= $p->id_periodo_factura ?>"  >
                                                            <?php endif; ?>
                                                            <p class="text-muted mb-3">Factura PDF (Máx 100MB)</p> <?= (isset($factura_pdf) && !empty($factura_pdf))? '<a target="_blank" href='.base_url().$factura_pdf[$i]->ruta_relativa.'><i class="mdi dripicons-preview"></i></a>':'' ?>
                                                            <input id="factura_pdf_input_<?= $i; ?>" type="file" name="factura_pdf_<?= $i; ?>[]" class="dropify" multiple accept=".pdf" />   
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <p class="text-muted mb-3">Factura XML (Máx 100MB)</p> <?= (isset($factura) && !empty($factura))? '<a target="_blank" href='.base_url().'index.php/Inicio/VerXML/'.$factura[$i]->id_factura.'><i class="mdi dripicons-preview"></i></a>':'' ?>
                                                            <input id="factura_xml_input_<?= $i; ?>" type="file" name="factura_xml_<?= $i; ?>[]" multiple class="dropify" accept=".xml">
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <p class="text-muted mb-3">Importe</p>
                                                            <?php if($editar != 1): ?>
                                                                <input id="importe[]" type="text" autocomplete="off" name="importe[]" class="form-control" placeholder="importe" value="<?= (isset($registro_pt->importe))?$registro_pt->importe:'' ?>">
                                                            <?php else: ?>
                                                                <!-- Si estás editando, muestra el importe correspondiente -->
                                                                <?php 
                                                                $importe_valor = '';
                                                                if(isset($importe[$i])) {
                                                                    $importe_valor = $importe[$i]->importe;
                                                                }
                                                                ?>
                                                                <input id="importe[]" type="text" autocomplete="off" name="importe[]" class="form-control" placeholder="importe" value="<?= $importe_valor ?>">
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>

                                            <div class="form-row">
                                                <div class="col-md-4 mb-3"></div>
                                                <div class="col-md-4 mb-3"></div>
                                                <div class="col-md-4 mb-3">
                                                    <p class="text-muted mb-3">Total</p>
                                                    <input autocomplete="off" id="total_importe" type="text" name="total_importe" class="form-control" placeholder="0,000.00" value="<?= (isset($registro_pt->total_importe))?$registro_pt->total_importe:'' ?>" readonly>
                                                </div>
                                            </div>

                                            <a class="btn btn-gradient-danger" style="color:white" onclick="window.history.back()">Atrás</a>
                                            <?php if(!$edita): ?>  <!-- Corregí $edita por $editar para consistencia -->
                                                <button class="btn btn-gradient-primary" id="btnGuardatPT" type="submit">Guardar</button>
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
        

        <!-- Plugins js -->
        <script src="<?= base_url()?>plugins/moment/moment.js"></script>
        <script src="<?= base_url()?>plugins/daterangepicker/daterangepicker.js"></script>
        <script src="<?= base_url()?>plugins/select2/select2.min.js"></script>
        <script src="<?= base_url()?>plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <script src="<?= base_url()?>plugins/timepicker/bootstrap-material-datetimepicker.js"></script>
        <script src="<?= base_url()?>plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="<?= base_url()?>plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>

        <script>
            ini.inicio.formPT();
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
                    // Escuchar cambios en cualquier input con clase 'input-importe'
        $(document).on('input', 'input[name="importe[]"]', function() {
            calcularTotal();
        });
        function calcularTotal() {
            let total = 0;
            
            $('input[name="importe[]"]').each(function() {
                // Elimina comas y convierte a número
                const valor = parseFloat($(this).val().replace(/,/g, '')) || 0;
                total += valor;
            });
            
            // Formatea el total con separadores de miles
            $('#total_importe').val(formatNumber(total.toFixed(2)));
        }
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        }
   
        $(document).ready(function() {
        
        // Función para actualizar la visibilidad de la sección de factura
        function actualizarVisibilidadFactura(checkbox) {
            // Obtiene el selector del data-target (ej: "#factura-section-0")
            var targetSelector = $(checkbox).data('target');
            var $targetSection = $(targetSelector);
             var $encabezadoInput = $(checkbox).closest('.form-row').find('input[name="encabezado[]"]');
            // Si el checkbox está MARCADO ("No Agregar Factura")...
            if ($(checkbox).is(':checked')) {
                $targetSection.hide(); // ...oculta la sección
                $encabezadoInput.prop('readonly', true); 
            } else {
                $targetSection.show(); // ...muestra la sección
                $encabezadoInput.prop('readonly', false);
            }
        }
      
        // 1. Ejecuta la función para cada checkbox cuando la página carga
        //    (Esto oculta las secciones de los checkboxes que ya vienen marcados)
        $('.toggle-factura-section').each(function() {
            actualizarVisibilidadFactura(this);
        });

        // 2. Asigna el evento 'change' a todos los checkboxes con esa clase
        //    Usamos 'on()' para que funcione incluso si se añaden filas dinámicamente
        $(document).on('change', '.toggle-factura-section', function() {
            actualizarVisibilidadFactura(this);
        });

    });


        </script>
