

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
                                    <h4 class="mt-0 header-title">PROVEEDOR: <?= (isset($proveedor->razon_social) && !empty($proveedor->razon_social))?$proveedor->razon_social:$registro_pt->dsc_proveedor ?></h4>
                                    <p class="text-muted mb-3">
                                        <?= (isset($proveedor->no_proveedor) && !empty($proveedor->no_proveedor))?'No. Proveedor '.$proveedor->no_proveedor:'' ?>
                                    </p>
                                    <form  id="form_proveedor"> 
                                        <input type="hidden" name="id_proveedor" id="id_proveedor" value="<?= (isset($proveedor->id_proveedor) && !empty($proveedor->id_proveedor))?$proveedor->id_proveedor:$registro_pt->id_proveedor?>" >
                                        <input type="hidden" name="editar" id="editar" value="<?= $editar?>">
                                        <?php if(isset($registro_pt->id_registro_pt) && !empty($registro_pt->id_registro_pt)): ?>
                                        <input type="hidden" name="id_registro_pt" id="id_registro_pt" value="<?= $registro_pt->id_registro_pt?>">
                                        <?php endif; ?>
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
                                            
                                            <!-- Tipo de PT -->
                                            <div class="col-md-4 mb-3">
                                                <label for="tipo_pt">Tipo de PT <span class="text-danger">*</span></label>
                                                <select class="form-control" id="tipo_pt" name="tipo_pt" required>
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
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="cuenta_bancaria">Cuenta Bancaria del Proveedor <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="cuenta_bancaria" name="cuenta_bancaria" value="<?= (isset($banco->banco) && !empty($banco->banco))?$banco->banco.''.$banco->no_cuenta:$registro_pt->cuenta_bancaria?>">
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
                                                <input type="text" class="form-control" id="formato_conformidad" value="SI" name="formato_conformidad" readonly>
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
                                                <input type="text" class="form-control" id="otros"  name="otros" >
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
                                                <input type="text" class="form-control" autocomplete="off" id="no_reserva" name="no_reserva"  value="<?= (isset($registro_pt->no_reserva))?$registro_pt->no_reserva:'' ?>">
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                    
                                            <a class="btn btn-gradient-danger" style="color:white" onclick="window.history.back()">Atrás</a>
                                             <button class="btn btn-gradient-primary" type="submit">Guardar</button>

                                    </form> <!--end form-->                                          
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->

                   
                    </div><!--end row-->


                </div><!-- container -->
            </div>
        </div>
        <!-- end page-wrapper -->
        <!-- jQuery  -->
        <script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/jquery-ui.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/metismenu.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/waves.js"></script>
        <script src="<?php echo base_url() ?>assets/js/feather.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/jquery.slimscroll.min.js"></script>
        <script src="<?php echo base_url() ?>plugins/apexcharts/apexcharts.min.js"></script>
        
        <!-- Parsley js -->
        <script src="<?php echo base_url() ?>plugins/parsleyjs/parsley.min.js"></script>
        <script src="<?php echo base_url() ?>assets/pages/jquery.validation.init.js"></script>
        
        <!-- App js -->
        <script src="<?php echo base_url() ?>assets/js/jquery.core.js"></script>
        <script src="<?php echo base_url() ?>assets/js/app.js"></script>
        <script>
            ini.inicio.formPT();
        </script>
