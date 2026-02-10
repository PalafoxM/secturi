

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
                                   <form id="form_proveedor_editar" enctype="multipart/form-data">
                                        <input type="hidden" name="id_proveedor" id="id_proveedor" value="<?= (isset($reserva->id_proveedor) && !empty($reserva->id_proveedor))?$reserva->id_proveedor:$registro_pt->id_proveedor?>" >
                                        <input type="hidden" name="editar" id="editar" value="<?= $editar?>">
                                        <input type="hidden" name="id_reserva" id="id_reserva" value="<?= $id_reserva?>">
                                        <input type="hidden" class="form-control" id="cuenta_bancaria" name="cuenta_bancaria" value="<?= (isset($reserva->banco_completo) && !empty($reserva->banco_completo))?$reserva->banco_completo:''?>">
                                        <?php if(isset($registro_pt->id_registro_pt) && !empty($registro_pt->id_registro_pt)): ?>
                                        <input type="hidden" name="id_registro_pt" id="id_registro_pt" value="<?= $registro_pt->id_registro_pt?>">
                                       
                                        <?php endif; ?>
                                       <div class="form-row">
                                            <!-- Dirección Responsable -->
                                             <?php if($editar != 1): ?>
                                            <div class="col-md-4 mb-3">
                                                <label for="direccion_responsable">Dirección Responsable <span class="text-danger">*</span></label>
                                                <select class="form-control select2" id="direccion_responsable" name="direccion_responsable" required>
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
    
                                            <div class="col-md-8 mb-3">
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
                                                <input type="text" class="form-control"  autocomplete="off" id="no_reserva" name="no_reserva"  value="<?= (isset($reserva->no_reserva))?$reserva->no_reserva:'' ?>" readonly>
                                               
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="no_consecutivo">No. Consecutivo.</label>
                                                <input type="text" class="form-control" readonly autocomplete="off" id="no_consecutivo" name="no_consecutivo" value="<?= (isset($registro_pt->no_consecutivo))?$registro_pt->no_consecutivo: $no_consecutivo ?>"  >
                                            </div><!--end col-->
                                     
                                            
                                         
                                        </div><!--end form-row-->
                                        <?php
                                            $partidas_mostradas = [];
                                            $total_partidas = count($datosGrupal);
                                            foreach($datosGrupal as $i => $p):
                                                // Evita duplicados por id_partida
                                            
                                                if (in_array($p->id_partida, $partidas_mostradas)) {
                                                    continue;
                                                }
                                                $partidas_mostradas[] = $p->id_partida;

                                                // Generamos un ID único para la sección de factura
                                                $section_id = "factura-section-" . $i;
                                            ?><input type="hidden" id="id_presupuesto_<?= $i ?>" name="id_presupuesto[<?= $i ?>]" value="<?= $p->id_presupuesto ;?>" >
                                                <p class="text-muted mb-4 text-center">Agregar Factura PT.</p>
                                               
                                                <hr>
                                                <div class="form-row">
                                                    <div class="col-md-2 mb-3">
                                                        <label for="partida_<?= $i ?>">Partida<span style="color:red;">*</span></label>
                                                    
                                                        <input type="hidden" name="partida[]" value="<?= $p->id_partida ?>">
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
                                                        <input type="hidden" name="proyecto[]" value="<?= $p->id_proyecto ?>">
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
                                                        <input type="text" class="form-control" autocomplete="off" id="encabezado_<?= $i ?>" name="encabezado[]" value="<?= (isset($p->dsc_partida) && !empty($p->dsc_partida)?$p->dsc_partida:'') ?>" >
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

                                                <!-- Card con Tabla Dinámica (Replicado de vRegistroGo) -->
                                                <div id="<?= $section_id ?>">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h4 class="mt-0 header-title">REFERENCIA</h4>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered" id="makeEditable<?= $i ?>">
                                                                            <thead>
                                                                                <tr>
                                                                                   
                                                                                    <th style="width: 30%">DESCRIPCIÓN</th>
                                                                                    <th style="width: 15%">VIGENCIA</th>
                                                                                    <th style="width: 20%">ARCHIVOS</th>
                                                                                    <th style="width: 15%">ACCIONES</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                 <?php if(isset($p->datos) && is_array($p->datos)): ?>
                                                                    <?php foreach($p->datos as $j => $r): ?>
                                                                    <?php $uniqueId = $r['id_identificador'] ?>
                                                                                <tr>
                                                                                  <input type="hidden" name="id_identificador_<?= $i ?>[]" value="<?= $r['id_identificador'] ?>" >
                                                                                 
                                                                                    <!-- Descripción -->
                                                                                     <td>
                                                                                         <textarea autocomplete="off" class="form-control mb-1" name="concepto_<?= $i ?>[]" placeholder="Concepto" rows="2" style="font-size: 0.85rem;"><?= (isset($r['concepto'])) ? $r['concepto'] : '' ?></textarea>
                                                                                     </td>
                                                                                     <!-- Vigencia -->
                                                                                     <td>
                                                                                         <div class="input-group input-group-sm mb-1">
                                                                                             <div class="input-group-prepend"><span class="input-group-text">Del</span></div>
                                                                                             <input autocomplete="off" type="date" class="form-control" name="periodo_inicio_<?= $i ?>[]" value="<?= (isset($r['periodo_inicio'])) ? date('Y-m-d', strtotime($r['periodo_inicio'])) : '' ?>">
                                                                                         </div>
                                                                                         <div class="input-group input-group-sm">
                                                                                             <div class="input-group-prepend"><span class="input-group-text">Al </span></div>
                                                                                             <input autocomplete="off" type="date" class="form-control" name="periodo_fin_<?= $i ?>[]" value="<?= (isset($r['periodo_fin'])) ? date('Y-m-d', strtotime($r['periodo_fin'])) : '' ?>">
                                                                                         </div>
                                                                                     </td>
                                                                                     <!-- Archivos -->
                                                                                     <td>
                                                                                         <div class="archivos-seleccionados" id="archivos_<?= $uniqueId ?>"> <!-- Usar uniqueId para evitar conflicto con i -->
                                                                                             <?php if(!empty($r['ruta_relativa'])): ?>
                                                                                                <div class="mb-1">
                                                                                                    <a href="<?= base_url() . $r['ruta_relativa'] ?>" target="_blank" class="text-danger">
                                                                                                        <i class="fas fa-file-pdf"></i> Ver PDF
                                                                                                    </a>
                                                                                                </div>
                                                                                             <?php else: ?>
                                                                                                <small class="text-muted">Sin PDF</small><br>
                                                                                             <?php endif; ?>

                                                                                             <?php if(!empty($r['total'])): ?>
                                                                                                <div class="mt-1">
                                                                                                    <span class="badge badge-soft-success">XML: $ <?= number_format($r['total'], 2) ?></span>
                                                                                                </div>
                                                                                             <?php endif; ?>
                                                                                         </div>
                                                                                     </td>
                                                                                    <!-- Acciones -->
                                                                                    <td>
                                                                                        <div class="btn-group-vertical btn-group-sm w-100">
                                                                                            <button type="button" class="btn btn-success btn-seleccionar-pdf mb-1" data-row="<?= $uniqueId ?>">
                                                                                                <i class="fas fa-file-pdf"></i> PDF
                                                                                            </button>
                                                                                            <button type="button" class="btn btn-warning btn-seleccionar-xml mb-1" data-row="<?= $uniqueId ?>">
                                                                                                <i class="mdi mdi-code-tags"></i> XML
                                                                                            </button>
                                                                                            <button type="button" class="btn btn-danger remove-row">
                                                                                                <i class="fas fa-trash"></i> Eliminar
                                                                                            </button>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                 <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <tr>
                                                                        <td colspan="10" class="text-center">No hay datos disponibles</td>
                                                                    </tr>
                                                                <?php endif; ?>
                                                                            </tbody>
                                                                        </table>
                                                                        <div class="text-right mt-2">
                                                                            <a onclick="addRow(<?= $i ?>)" class="btn btn-primary text-white">
                                                                                <i class="fas fa-plus"></i> Agregar Fila
                                                                            </a>
                                                                        </div>
                                                                        <div class="row mt-3" style="visibility: hidden"> <!-- Visibility changed to visible for PT if needed, or keep hidden -->
                                                                            <div class="col-md-8"></div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>TOTAL:</label>
                                                                                    <input type="text" name="total_importe" class="form-control font-weight-bold text-right" id="total_importe_<?= $i ?>" value="0.00" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                            <div id="hidden-file-inputs-container"></div>

                         

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
            // Definir globales explícitamente en window
            window.archivosPorFila = {};
            window.deletedRows = [];
            window.base_url = "<?= base_url() ?>";

            // --- Lógica Original de vProveedor (Mantener) con manejo de errores ---
            try {
                if (window.ini && window.ini.inicio && typeof window.ini.inicio.formPT === 'function') {
                    ini.inicio.formPT();
                } else {
                    console.warn('ini.inicio.formPT no está disponible o no es una función.');
                }
            } catch (e) {
                console.error('Error al inicializar formPT:', e);
            }

            $('.add-file').on('click', function(e) {
                e.preventDefault();
                const inputId = $(this).data('target');
                if ($(inputId).length) {
                    $(inputId).click();
                }
            });

            if ($.fn.daterangepicker) {
                $('input[name="datetimes[]"]').daterangepicker({
                    timePicker: true,
                    timePicker24Hour: true,
                    locale: {
                        format: 'YYYY-MM-DD HH:mm:ss'
                    }
                });
            }

            $(document).ready(function() {
                if ($.fn.select2) {
                    $('.select2').select2({
                        placeholder: "Selecciona un responsable",
                        allowClear: true,
                        width: '100%',
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            }
                        }
                    });
                }

                // Lógica para checkbox "No Agregar Factura"
                function actualizarVisibilidadFactura(checkbox) {
                    var targetSelector = $(checkbox).data('target');
                    var $targetSection = $(targetSelector);
                    var $encabezadoInput = $(checkbox).closest('.form-row').find('input[name="encabezado[]"]');
                    
                    if ($(checkbox).is(':checked')) {
                        $targetSection.hide();
                        $encabezadoInput.prop('readonly', true); 
                    } else {
                        $targetSection.show();
                        $encabezadoInput.prop('readonly', false);
                    }
                }
            
                $('.toggle-factura-section').each(function() {
                    actualizarVisibilidadFactura(this);
                });

                $(document).on('change', '.toggle-factura-section', function() {
                    actualizarVisibilidadFactura(this);
                });
            });

            // --- Nueva Lógica Replicada de vRegistroGo ---

            // Función para inicializar fila en el objeto de archivos
            window.inicializarFilaEnArchivos = function(rowIndex) {
                if (!window.archivosPorFila[rowIndex]) {
                    window.archivosPorFila[rowIndex] = {
                        pdf: [],
                        xml: []
                    };
                }
            };

            // Función para agregar fila
            window.addRow = function(sectionIndex) {
                const table = document.getElementById(`makeEditable${sectionIndex}`);
                if (!table) {
                    console.error(`Tabla makeEditable${sectionIndex} no encontrada`);
                    return;
                }
                const tbody = table.getElementsByTagName('tbody')[0];
                const rowCount = tbody.rows.length;
                const newRowIndex = `tabla_${sectionIndex}_${Date.now()}`;
                
                const tr = document.createElement('tr');
                tr.setAttribute('data-row-index', newRowIndex);
                
                tr.innerHTML = `

                    <td>
                    <input type="hidden" name="id_identificador_${sectionIndex}[]" value="${newRowIndex}">
                        <textarea autocomplete="off" class="form-control mb-1" name="concepto_${sectionIndex}[]" placeholder="Concepto" rows="2" style="font-size: 0.85rem;"></textarea>
                    </td>
                    <td>
                        <div class="input-group input-group-sm mb-1">
                            <div class="input-group-prepend"><span class="input-group-text">Del</span></div>
                            <input autocomplete="off" type="date" class="form-control" name="periodo_inicio_${sectionIndex}[]">
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend"><span class="input-group-text">Al </span></div>
                            <input autocomplete="off" type="date" class="form-control" name="periodo_fin_${sectionIndex}[]">
                        </div>
                    </td>
                    <td>
                        <div class="archivos-seleccionados" id="archivos_${newRowIndex}">
                            <small class="text-muted">Sin archivos</small>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group-vertical btn-group-sm w-100">
                            <button type="button" class="btn btn-success btn-seleccionar-pdf mb-1" data-row="${newRowIndex}">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                            <button type="button" class="btn btn-warning btn-seleccionar-xml mb-1" data-row="${newRowIndex}">
                                <i class="mdi mdi-code-tags"></i> XML
                            </button>
                            <button type="button" class="btn btn-danger remove-row">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </td>
                `;
                
                tbody.appendChild(tr);
                window.inicializarFilaEnArchivos(newRowIndex);
                
                // Reinicializar inputmask para la nueva fila
                if ($.fn.inputmask) {
                    $(`#makeEditable${sectionIndex} tbody tr[data-row-index="${newRowIndex}"] .propina-input`).inputmask('numeric', {
                        radixPoint: ".",
                        groupSeparator: ",",
                        digits: 2,
                        autoGroup: true,
                        prefix: '$ ',
                        rightAlign: false
                    });
                }
            };

            // Eliminar fila
            $(document).on('click', '.remove-row', function () {
                const row = $(this).closest('tr');
                const rowIndex = row.attr('data-row-index');
                const tableId = row.closest('table').attr('id');
                const sectionIndex = tableId.replace('makeEditable', '');

                if (row.closest('tbody').find('tr').length === 1) {
                    Swal.fire("Atención", "No se puede eliminar la única fila. Debe haber al menos un registro.", "warning");
                    return;
                }

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Se eliminará esta fila.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (rowIndex && !rowIndex.includes('new_')) {
                             window.deletedRows.push({
                                index: rowIndex,
                                section: sectionIndex
                            });
                        }
                        delete window.archivosPorFila[rowIndex];
                        row.remove();
                        window.calcularTotal(sectionIndex);
                        Swal.fire('Eliminado!', 'La fila ha sido eliminada.', 'success');
                    }
                })
            });

            // Seleccionar PDF
            $(document).on('click', '.btn-seleccionar-pdf', function() {
                const rowIndex = $(this).data('row');
                window.inicializarFilaEnArchivos(rowIndex);

                const input = document.createElement('input');
                input.type = 'file';
                input.accept = '.pdf';
                input.multiple = true;

                input.onchange = e => {
                    const files = Array.from(e.target.files);
                    const maxSize = 100 * 1024 * 1024;
                    const archivosValidos = files.filter(file => file.size <= maxSize);
                    
                    if (archivosValidos.length === 0) return;

                    Swal.fire({
                        title: 'Confirmar PDF',
                        html: `<p>Se agregarán ${archivosValidos.length} archivo(s).</p>`,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.inicializarFilaEnArchivos(rowIndex);
                            window.archivosPorFila[rowIndex].pdf = archivosValidos;
                            window.actualizarVistaArchivos(rowIndex);
                            Swal.fire('PDF Guardados', '', 'success');
                        }
                    });
                };
                input.click();
            });

            // Seleccionar XML
            $(document).on('click', '.btn-seleccionar-xml', function() {
                const rowIndex = $(this).data('row');
                window.inicializarFilaEnArchivos(rowIndex);

                const input = document.createElement('input');
                input.type = 'file';
                input.accept = '.xml';
                input.multiple = true;

                input.onchange = e => {
                    const files = Array.from(e.target.files);
                    const maxSize = 100 * 1024 * 1024;
                    const archivosValidos = files.filter(file => file.size <= maxSize);
                    
                    if (archivosValidos.length === 0) return;

                    Swal.fire({
                        title: 'Confirmar XML',
                        html: `<p>Se agregarán ${archivosValidos.length} archivo(s).</p>`,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.inicializarFilaEnArchivos(rowIndex);
                            window.archivosPorFila[rowIndex].xml = archivosValidos;
                            window.actualizarVistaArchivos(rowIndex);
                            Swal.fire('XML Guardados', '', 'success');
                        }
                    });
                };
                input.click();
            });

            // Actualizar vista archivos
            window.actualizarVistaArchivos = function(rowIndex) {
                const container = $(`#archivos_${rowIndex}`);
                if (!window.archivosPorFila[rowIndex]) {
                    container.html('<small class="text-muted">No hay archivos</small>');
                    return;
                }
                const archivos = window.archivosPorFila[rowIndex];
                let count = (archivos.pdf ? archivos.pdf.length : 0) + (archivos.xml ? archivos.xml.length : 0);
                
                if (count === 0) {
                    container.html('<small class="text-muted">No hay archivos</small>');
                } else {
                    container.html(`<div><small class="text-info"><strong>Total:</strong> ${count} archivo(s)</small></div>`);
                }
            };

            // Cálculo de totales
            window.calcularTotal = function(i) {
                let total = 0;
                $(`input[name="propina_${i}[]"]`).each(function() {
                    let valor = $(this).val().replace(/[$,]/g, '') || 0;
                    total += parseFloat(valor);
                });
                $(`#total_importe_${i}`).val('$ ' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
            };

            $(document).on('input', 'input[class*="propina-input"]', function() {
                const tableId = $(this).closest('table').attr('id');
                const i = tableId.replace('makeEditable', '');
                window.calcularTotal(i);
            });

            // Preparar FormData
            window.prepararFormData = function() {
                console.log("Preparando FormData...");
                console.log("Archivos en memoria:", window.archivosPorFila);
                const formData = new FormData($('#form_proveedor_editar')[0]);
                
                // Agregar archivos
                Object.keys(window.archivosPorFila).forEach(rowIndex => {
                    const archivos = window.archivosPorFila[rowIndex];
                    if (archivos && archivos.pdf) {
                        archivos.pdf.forEach((file, fileIndex) => {
                            console.log(`Adjuntando PDF para fila ${rowIndex}:`, file.name);
                            formData.append(`archivos[pdf_${rowIndex}][pdf][${fileIndex}]`, file);
                        });
                    }
                    if (archivos && archivos.xml) {
                        archivos.xml.forEach((file, fileIndex) => {
                            console.log(`Adjuntando XML para fila ${rowIndex}:`, file.name);
                            formData.append(`archivos[xml_${rowIndex}][xml][${fileIndex}]`, file);
                        });
                    }
                });
                return formData;
            };

            // Envío del formulario
            $('#form_proveedor_editar').off('submit').on('submit', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation(); // Detener otros handlers si los hay
                
                const formData = window.prepararFormData();
                formData.append('deleted_rows', JSON.stringify(window.deletedRows));

                // Validaciones básicas
                let isValid = true;
                $('input[type="date"]').each(function() {
                     // Solo validar si es visible
                     if($(this).is(':visible') && $(this).val() === ''){
                         isValid = false;
                         $(this).addClass('is-invalid');
                     } else {
                         $(this).removeClass('is-invalid');
                     }
                });

                if (!isValid) {
                    Swal.fire("Atención", "Complete las fechas requeridas.", "warning");
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "<?= base_url()?>index.php/Agregar/guardaPTEditar", 
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (response) {
                        if(!response.error){
                            Swal.fire("Correcto", response.respuesta, 'success');  
                            setTimeout(() => {
                                window.location.href = base_url + "index.php/Principal/tablaArchivos/"+response.idRegistro+'/PT';
                            }, 1500);
                        }else{
                            Swal.fire("Atención", response.respuesta, 'info');  
                        }
                    },
                    beforeSend: function (){
                        $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    },
                    complete: function (){
                        $('button[type="submit"]').prop('disabled', false).html('Guardar');
                    },
                    error: function () {
                        Swal.fire("Error", "Ocurrió un error al guardar.", "error"); 
                    }
                });
            });

            // Inicialización de filas existentes
            $(document).ready(function() {
                <?php foreach ($presupuesto as $i => $p): ?>
                const initialRowIndex<?= $i ?> = '<?= $i ?>';
                const tableId<?= $i ?> = 'makeEditable<?= $i ?>';
                
                if ($(`#` + tableId<?= $i ?>).length) {
                    $(`#` + tableId<?= $i ?> + ` tbody tr`).first().attr('data-row-index', initialRowIndex<?= $i ?>);
                    window.inicializarFilaEnArchivos(initialRowIndex<?= $i ?>);
                    
                    if ($.fn.inputmask) {
                        $(`#` + tableId<?= $i ?> + ` tbody tr .propina-input`).inputmask('numeric', {
                            radixPoint: ".",
                            groupSeparator: ",",
                            digits: 2,
                            autoGroup: true,
                            prefix: '$ ',
                            rightAlign: false
                        });
                    }
                }
                <?php endforeach; ?>
            });
        </script>
