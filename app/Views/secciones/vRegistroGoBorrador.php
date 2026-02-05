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
                        <h4 class="page-title">Borrador GO</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>

            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="mt-0 header-title">PROVEEDOR: <strong> GOBIERNO DEL ESTADO DE GUANAJUATO SFIYA
                                    SECRETARIA DE TURISMO</strong></h3>
                            <p class="text-muted mb-3">
                                GEG850101FQ2
                            </p>
                            <form id="formBorrador_go" enctype="multipart/form-data">
                                <input type="hidden" name="editar" value="1">
                                <input type="hidden" name="id_reserva_go" value="<?= $id_reserva ?>">
                                <input type="hidden" name="id_registro_go" value="<?= isset($registro_guardado->id_registro_go) ? $registro_guardado->id_registro_go : '' ?>">
                                <input type="hidden" name="es_borrador" id="es_borrador" value="0">
                                <input type="hidden" name="deleted_rows" id="deleted_rows" value="">
                                <div class="form-row">
                                    <!-- Dirección Responsable -->
                                    <div class="col-md-4 mb-3">
                                        <label for="direccion_responsable">Dirección Responsable <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2" id="direccion_responsable"
                                            name="direccion_responsable" required>
                                            <?php foreach ($cat_area as $a): ?>
                                                <option value="<?= $a->id_area ?>" <?php echo ($a->id_area == $id_area) ? 'selected' : ''; ?>>
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
                                        <select name="id_reponsable_solicitud" class="form-control select2" required>
                                            <?php 
                                            $opcionNoAplica = null;
                                            foreach ($cat_usuario as $u): 
                                                $nombreCompleto = $u->nombre . ' ' . $u->primer_apellido . ' ' . $u->segundo_apellido;
                                                
                                                // Si es NO APLICA, lo guardamos para el final
                                                if (stripos($nombreCompleto, 'NO APLICA') !== false) {
                                                    $opcionNoAplica = $u;
                                                    continue;
                                                }

                                                // Determina el valor que debe quedar seleccionado
                                                $selected = '';
                                                if (isset($registro_pt->id_reponsable_solicitud) && $registro_pt->id_reponsable_solicitud == $u->id_usuario) {
                                                    $selected = 'selected';
                                                } elseif (!isset($registro_pt->id_reponsable_solicitud) && isset($usuario) && $usuario->id_usuario == $u->id_usuario) {
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                <option value="<?= $u->id_usuario ?>" <?= $selected ?>>
                                                    <?= $nombreCompleto ?>
                                                </option>
                                            <?php endforeach; ?>

                                            <?php 
                                            // Renderizamos NO APLICA al final si se encontró
                                            if ($opcionNoAplica): 
                                                $u = $opcionNoAplica;
                                                $nombreCompleto = $u->nombre . ' ' . $u->primer_apellido . ' ' . $u->segundo_apellido;
                                                
                                                $selected = '';
                                                if (isset($registro_pt->id_reponsable_solicitud) && $registro_pt->id_reponsable_solicitud == $u->id_usuario) {
                                                    $selected = 'selected';
                                                } elseif (!isset($registro_pt->id_reponsable_solicitud) && isset($usuario) && $usuario->id_usuario == $u->id_usuario) {
                                                    $selected = 'selected';
                                                }
                                            ?>
                                                <option value="<?= $u->id_usuario ?>" <?= $selected ?>>
                                                    <?= $nombreCompleto ?>
                                                </option>
                                            <?php endif; ?>
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
                                        <select type="text" class="form-control select2" id="secretario"
                                            placeholder="Secretario/a" name="secretario">
                                            <option value="0" selected>Seleccione una opcion</option>
                                            <?php foreach ($secretario as $s): ?>
                                                <?php if (isset($registro_pt->id_secretario) && !empty($registro_pt->id_secretario)) { ?>
                                                    <option value="<?= $s->id_secretario ?>"
                                                        <?= ($s->id_secretario == $registro_pt->id_secretario) ? 'selected' : '' ?>>
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
                                    <div class="col-md-6 mb-6">
                                        <label for="formato_establecido">Formatos establecidos en los Lineamientos
                                            Generales de Racionalidad, Austeridad y Disciplina Presupuestal de la
                                            Administración Pública Estatal vigente o formatos establecidos en la
                                            regulación del trámite ingresado.<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="formato_establecido" value="SI"
                                            name="formato_establecido" readonly>
                                        <div class="invalid-feedback">
                                            Campo no Valido
                                        </div>
                                    </div><!--end col-->
                                    <div class="col-md-6 mb-6">
                                        <label for="documentacion_comprobatoria">Documentación comprobatoria fiscalmente
                                            requisitada, atendiendo a lo establecido en los Lineamientos Generales de
                                            Racionalidad, Austeridad y Disciplina Presupuestal de la Administración
                                            Pública Estatal vigentes.<span style="color:red;">*</span></label>
                                        <select type="text" class="form-control" id="documentacion_comprobatoria"
                                            name="documentacion_comprobatoria">
                                            <?php foreach ($cat_opcion as $o): ?>
                                                <option value="<?= $o->id_opcion ?>"
                                                    <?= (isset($registro_pt->documentacion_comprobatoria) && $registro_pt->documentacion_comprobatoria == $o->id_opcion) ? 'selected' : '' ?>><?= $o->des_opcion ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!--end col-->
                                </div><!--end form-row-->
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label for="poliza">Pólizas Contables.<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="poliza" value="SI" name="poliza"
                                            readonly>
                                        <div class="invalid-feedback">
                                            Campo no Valido
                                        </div>
                                    </div><!--end col-->
                                    <div class="col-md-4 mb-3">
                                        <label for="formato_conformidad">Formato de conformidad del producto
                                            recibido.<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="formato_conformidad" value="SI"
                                            name="formato_conformidad" readonly>
                                        <div class="invalid-feedback">
                                            Please provide a valid state.
                                        </div>
                                    </div><!--end col-->
                                    <div class="col-md-4 mb-3">
                                        <label for="contrato_convenio">Contrato o Convenio.<span
                                                style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="contrato_convenio" value="NO"
                                            name="contrato_convenio" readonly>

                                    </div><!--end col-->
                                </div><!--end form-row-->
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label for="documentacion_requerida">Documentación requerida para emitir el
                                            pago.<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="documentacion_requerida" value="SI"
                                            name="documentacion_requerida" readonly>
                                    </div><!--end col-->
                                    <div class="col-md-4 mb-3">
                                        <label for="evidencia_entrega">Evidencia de entregable.<span
                                                style="color:red;">*</span></label>
                                        <select type="text" class="form-control" id="evidencia_entrega"
                                            name="evidencia_entrega">
                                            <?php foreach ($cat_opcion as $o): ?>
                                                <option value="<?= $o->id_opcion ?>" <?= ($o->id_opcion == 2) ? 'selected' : '' ?>>
                                                    <?= $o->des_opcion ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!--end col-->
                                    <div class="col-md-4 mb-3">
                                        <label for="concepto_gasto">Concepto del gasto<span
                                                style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="concepto_gasto" autocomplete="off"
                                            placeholder="Concepto del gasto" name="concepto_gasto" value="<?= (isset($registro_pt->concepto_gasto)) ? $registro_pt->concepto_gasto : '' ?>">
                                    </div><!--end col-->
                                </div><!--end form-row-->
                                <div class="form-row">


                                    <div class="col-md-4 mb-3">
                                        <label for="comision">Comisión / Reunión / Evento / Programa</label>
                                        <input type="text" class="form-control" id="comision" name="comision"
                                            value="<?= (isset($registro_pt->comision)) ? $registro_pt->comision : 'Comisión / Reunión / Evento / Programa' ?>">
                                        <div class="invalid-feedback">
                                            Please provide a valid state.
                                        </div>
                                    </div><!--end col-->
                                    <div class="col-md-4 mb-3">
                                        <label for="lugar">Lugar<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="lugar" autocomplete="off"
                                            placeholder="Lugar" name="lugar" value="<?= (isset($registro_pt->lugar)) ? $registro_pt->lugar : '' ?>">
                                    </div><!--end col-->

                                </div><!--end form-row-->
                                <div class="form-row">

                                    <div class="col-md-4 mb-3">
                                        <label for="no_consecutivo">No. Consecutivo.<span
                                                style="color:red;">*</span></label>
                                        <input type="number" class="form-control" autocomplete="off" id="no_consecutivo"
                                            name="no_consecutivo" placeholder="001"
                                            value="<?= (isset($registro_pt->no_consecutivo)) ? $registro_pt->no_consecutivo : '' ?>" readonly>
                                        <div class="invalid-feedback">
                                            Campo no Valido
                                        </div>
                                    </div><!--end col-->

                                </div><!--end form-row-->
                                <br>
                                
                                <?php
                                $grupos = [];
                                if (!empty($archivosPorFila)) {
                                    foreach ($archivosPorFila as $id => $fila) {
                                        $pId = $fila['id_partida'] ?? '';
                                        $prId = $fila['id_proyecto'] ?? '';
                                        $psId = $fila['id_presupuesto'] ?? '';
                                        $enc = $fila['encabezado'] ?? '';
                                        $key = $pId . '-' . $prId . '-' . $enc; 
                                        
                                        if (!isset($grupos[$key])) {
                                             $grupos[$key] = [
                                                 'datos' => [],
                                                 'id_partida' => $pId,
                                                 'id_proyecto' => $prId,
                                                 'id_presupuesto' => $psId,
                                                 'encabezado' => $enc
                                             ];
                                        }
                                        $grupos[$key]['datos'][$id] = $fila;
                                    }
                                } else {
                                    // Grupo por defecto vacio
                                    $grupos['default'] = [
                                        'datos' => [],
                                        'id_partida' => '',
                                        'id_presupuesto' => '',
                                        'encabezado' => 'Borrador'
                                    ];
                                }
                                $groupIndex = 0;
                                ?>

                                <?php foreach($grupos as $key => $grupo): ?>
                                <?php $groupIndex++; ?>
                                <div class="card group-container mb-4" data-group-id="<?= $groupIndex ?>">
                                    <div class="card-body">
                                        
                                        <!-- Header Inputs (Visual Controls) -->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label>Partida <span class="text-danger">* <?=  $grupo['id_partida'] ?> </span></label>
                                                <select class="form-control header-partida">
                                                    <option value="">Seleccione</option>
                                                    <?php foreach ($cat_partida as $partida): ?>
                                                        <option value="<?= $partida->id_partida ?>" <?= ($grupo['id_partida'] == $partida->id_partida) ? 'selected' : '' ?>>
                                                            <?= $partida->cuenta_cable ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Proyecto <span class="text-danger">* <?= $grupo['id_proyecto'] ?> </span></label>
                                                <select class="form-control header-proyecto">
                                                    <option value="">Seleccione</option>
                                                    <?php foreach ($cat_proyecto as $proyecto): ?>
                                                        <option value="<?= $proyecto->id_proyecto ?>" <?= ($grupo['id_proyecto'] == $proyecto->id_proyecto) ? 'selected' : '' ?>>
                                                            <?= $proyecto->proyecto ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Encabezado <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control header-encabezado" value="<?= $grupo['encabezado'] ?>" placeholder="Encabezado">
                                            </div>
                                        </div>

                                        <h4 class="mt-0 header-title">REFERENCIA</h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-rows">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10%;">IMPORTE</th>
                                                        <th style="width: 8%;">PROPINA</th>
                                                        <th>INICIO</th>
                                                        <th>FIN</th>
                                                        <th style="width: 20%;">ARCHIVOS</th>
                                                        <th>ACCIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($grupo['datos'] as $j => $r): ?>
                                                    <?php 
                                                        $uniqueId = $j;
                                                        $totalImporte = 0;
                                                        if(isset($r['xml']) && is_array($r['xml'])) {
                                                            foreach($r['xml'] as $xml) {
                                                                $totalImporte += isset($xml['total']) ? (float)$xml['total'] : 0;
                                                            }
                                                        }
                                                        $inicio = isset($r['periodo_inicio']) ? date('Y-m-d', strtotime($r['periodo_inicio'])) : '';
                                                        $fin = isset($r['periodo_fin']) ? date('Y-m-d', strtotime($r['periodo_fin'])) : '';
                                                        $propina = isset($r['propina']) ? $r['propina'] : '';
                                                    ?>
                                                    <tr data-row-index="<?= $uniqueId ?>">
                                                        <input type="hidden" name="rowIndex[]" value="<?= $uniqueId ?>">
                                                        <input type="hidden" name="id_identificador[<?= $uniqueId ?>]" value="<?= isset($r['id_identificador']) ? $r['id_identificador'] : '' ?>">
                                                        
                                                        <!-- Hidden Inputs synced with Header -->
                                                        <input type="hidden" class="row-partida" name="id_partida[<?= $uniqueId ?>]" value="<?= $grupo['id_partida'] ?>">
                                                        <input type="hidden" class="row-proyecto" name="id_presupuesto[<?= $uniqueId ?>]" value="<?= $grupo['id_presupuesto'] ?>">
                                                        <input type="hidden" class="row-encabezado" name="encabezado[<?= $uniqueId ?>]" value="<?= $grupo['encabezado'] ?>">
                                                        
                                                        <td>
                                                            <input type="text" autocomplete="off" class="form-control importe-input" 
                                                                name="importe[<?= $uniqueId ?>]" placeholder="Importe" 
                                                                value="<?= number_format($totalImporte, 2, '.', '') ?>" readonly>
                                                        </td>
                                                        <td>
                                                            <input autocomplete="off" type="text" class="form-control propina-input" 
                                                                name="propina[<?= $uniqueId ?>]" placeholder="Propina" 
                                                                value="<?= $propina ?>" >
                                                        </td>
                                                        <td>
                                                            <input autocomplete="off" type="date" class="form-control" 
                                                                name="periodo_inicio[<?= $uniqueId ?>]" 
                                                                value="<?= $inicio ?>" >
                                                        </td>
                                                        <td>
                                                            <input autocomplete="off" type="date" class="form-control" 
                                                                name="periodo_fin[<?= $uniqueId ?>]"  
                                                                value="<?= $fin ?>">
                                                        </td>
                                                        <td>
                                                            <div class="archivos-seleccionados" id="archivos_<?= $uniqueId ?>">
                                                                <?php if(isset($r['pdf']) && is_array($r['pdf'])): ?>
                                                                    <?php if(count($r['pdf']) > 0): ?>
                                                                        <div class="text-success"><small><strong>PDF:</strong></small></div>
                                                                        <ul class="list-unstyled mb-1">
                                                                        <?php foreach($r['pdf'] as $pdf): ?>
                                                                            <li>
                                                                                <?php if(isset($pdf['ruta'])): ?>
                                                                                    <a href="<?= base_url().$pdf['ruta'] ?>" target="_blank"><small><?= $pdf['nombre'] ?></small></a>
                                                                                <?php else: ?>
                                                                                    <small><?= $pdf['nombre'] ?></small>
                                                                                <?php endif; ?>
                                                                            </li>
                                                                        <?php endforeach; ?>
                                                                        </ul>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>

                                                                <?php if(isset($r['xml']) && is_array($r['xml'])): ?>
                                                                    <?php if(count($r['xml']) > 0): ?>
                                                                        <div class="text-info"><small><strong>XML:</strong></small></div>
                                                                        <ul class="list-unstyled mb-0">
                                                                        <?php foreach($r['xml'] as $xml): ?>
                                                                           <a href="<?= base_url().'index.php/Inicio/VerXML/'.$xml['id'].'/go' ?>" target="_blank"> <li><small><?= $xml['nombre'] ?> (Total: <?= isset($xml['total']) ? $xml['total'] : '0.00' ?>)</small></li></a>
                                                                        <?php endforeach; ?>
                                                                        </ul>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                                
                                                                <?php if(empty($r['pdf']) && empty($r['xml'])): ?>
                                                                    <small class="text-muted">No hay archivos</small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="mt-1">
                                                                <button type="button" class="btn btn-sm btn-success btn-seleccionar-pdf" data-row="<?= $uniqueId ?>">
                                                                    <i class="fas fa-file-pdf"></i> PDF
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-warning btn-seleccionar-xml" data-row="<?= $uniqueId ?>">
                                                                    <i class="mdi mdi-code-tags"></i> XML
                                                                </button>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-danger remove-row" 
                                                                data-row="<?= $uniqueId ?>">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-primary btn-sm mt-2 btnAgregarFila">
                                                <i class="fas fa-plus"></i> Agregar Fila
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                
                                <button type="button" class="btn btn-info btn-sm mb-4" id="btnAgregarGrupo">
                                    <i class="fas fa-plus-circle"></i> Agregar Nueva Sección
                                </button>
 
                                <div id="hidden-file-inputs-container"></div>
                                <a class="btn btn-gradient-danger" style="color:white"
                                    onclick="window.history.back()">Atrás</a>
                              
                                    <button class="btn btn-gradient-secondary" id="btnGuardarBorrador" type="button" style="margin-right: 10px;">Guardar sin enviar</button>
                                    <button class="btn btn-gradient-primary" id="btnGuardaGo" type="submit">Guardar y Enviar</button>
                               
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
    // Inicializar variables globales desde PHP
    var archivosPorFila = <?= json_encode($archivosPorFila) ?>;
    if (typeof archivosPorFila !== 'object' || archivosPorFila === null) {
        archivosPorFila = {};
    }
    
    // Catalogos para JS
    var catPartida = <?= json_encode($cat_partida) ?>;
    var catProyecto = <?= json_encode($cat_proyecto) ?>;



// Preparar FormData corregido
function prepararFormData() {
    const formData = new FormData();
    const form = $('#formBorrador_go')[0];
    const formElements = new FormData(form);
    
    for (let [key, value] of formElements) {
        formData.append(key, value);
    }
    
    // Capturar inputs de los modales de viaticos MANUALMENTE

    

    
    // Agregar archivos - SOLO FILAS QUE EXISTEN
    Object.keys(archivosPorFila).forEach(rowIndex => {
        const archivos = archivosPorFila[rowIndex];
        
        if (archivos && archivos.pdf) {
            archivos.pdf.forEach((file, fileIndex) => {
                if(file instanceof File) {
                    formData.append(`archivos[pdf_${rowIndex}][pdf][${fileIndex}]`, file);
                }
            });
        }
        
        if (archivos && archivos.xml) {
            archivos.xml.forEach((file, fileIndex) => {
                 if(file instanceof File) {
                    formData.append(`archivos[xml_${rowIndex}][xml][${fileIndex}]`, file);
                 }
            });
        }
    });

    return formData;
}

// Envío del formulario
// Variable global para filas eliminadas
let deletedRows = [];

// ENVIAR BORRADOR (Sin validación estricta de archivos)
$('#btnGuardarBorrador').on('click', function() {
    $('#es_borrador').val('1');
    
    // 1. Preparar FormData
    const formData = prepararFormData();
    formData.append('deleted_rows', JSON.stringify(deletedRows));

    // 2. Enviar AJAX a endpoint de Borrador
    $.ajax({
        type: "POST",
        url: "<?= base_url()?>index.php/Agregar/guardaBorradorGO",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            if(!response.error){
                Swal.fire("Borrador Guardado", '<p> '+ response.respuesta + '</p>', 'success');  
                setTimeout(() => {
                     window.location.href = base_url + "index.php/Principal/listaBorradoresGO";
                }, 1500);
            }else{
                Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
            }
        },
        beforeSend: function (){
            $('#btnGuardarBorrador').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
        },
        complete: function (){
            $('#btnGuardarBorrador').prop('disabled', false).html('Guardar sin enviar');
        },
        error: function (response) {
            var res = JSON.parse(response.responseText);
            Swal.fire("Error", '<p> '+ res.message + '</p>');  
        }
    });
});


// ENVIAR FINAL (Con validación estricta)
$('#formBorrador_go').on('submit', function(e) {
    e.preventDefault();
    
    $('#es_borrador').val('0'); // Asegurar que no es borrador

    // 1. Preparar FormData
    const formData = prepararFormData();
    formData.append('deleted_rows', JSON.stringify(deletedRows));

    // 2. Validacion de fechas requeridas
    let fechasValidas = true;
    $('input[type="date"]').each(function() {
        if ($(this).val() === '') {
            fechasValidas = false;
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    if (!fechasValidas) {
        Swal.fire("Atención", "Por favor, complete todas las fechas requeridas.", "warning");
        return;
    }

    // 3. Validacion de Archivos (ESTRICTA)
    let archivosValidos = true;
    $('tr[data-row-index]').each(function() {
        if ($(this).attr('data-viaticos') === 'true') return; 

        const rowIndex = $(this).data('row-index');
        const archivos = archivosPorFila[rowIndex];
        
        let tienePdf = false;
        let tieneXml = false;

        if (archivos) {
            if (archivos.pdf && Array.isArray(archivos.pdf) && archivos.pdf.length > 0) tienePdf = true;
            if (archivos.xml && Array.isArray(archivos.xml) && archivos.xml.length > 0) tieneXml = true;
        }

        if (!tienePdf || !tieneXml) {
            archivosValidos = false;
            $(this).find('.archivos-seleccionados').addClass('border border-danger');
        } else {
            $(this).find('.archivos-seleccionados').removeClass('border border-danger');
        }
    });

    if (!archivosValidos) {
        Swal.fire("Atención", "Es requerido adjuntar al menos un PDF y un XML por cada fila.", "warning");
        return;
    }

    // 4. Enviar AJAX a endpoint Original
    $.ajax({
        type: "POST",
        url: "<?= base_url()?>index.php/Agregar/guardaGO",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            if(!response.error){
                Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                setTimeout(() => {
                    window.location.href = base_url + "index.php/Principal/listaReservaGO";
                }, 1500);
            }else{
                Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
            }
        },
        beforeSend: function (){
            $('#btnGuardaGo').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
        },
        complete: function (){
            $('#btnGuardaGo').prop('disabled', false).html('Guardar y Enviar');
        },
        error: function (response) {
            var res= JSON.parse(response.responseText);
            Swal.fire("Error", '<p> '+ res.message + '</p>');  
        }
    });

    });


// Función para agregar nueva fila (en contexto de grupo)
$(document).on('click', '.btnAgregarFila', function() {
    const groupContainer = $(this).closest('.group-container');
    const tableBody = groupContainer.find('table tbody');
    
    // Obtener valores del header del grupo
    const idPartida = groupContainer.find('.header-partida').val();
    const idProyecto = groupContainer.find('.header-proyecto').val();
    const encabezado = groupContainer.find('.header-encabezado').val();
    
    // Generar ID único no numérico
    const newId = 'new_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
    
    // Inicializar almacenamiento de archivos
    archivosPorFila[newId] = { pdf: [], xml: [] };
    
    const nuevaFila = `
        <tr data-row-index="${newId}">
            <input type="hidden" name="rowIndex[]" value="${newId}">
            
            <input type="hidden" class="row-partida" name="id_partida[${newId}]" value="${idPartida}">
            <input type="hidden" class="row-proyecto" name="id_presupuesto[${newId}]" value="${idProyecto}">
            <input type="hidden" class="row-encabezado" name="encabezado[${newId}]" value="${encabezado}">
            
            <td>
                <input type="text" autocomplete="off" class="form-control importe-input" 
                    name="importe[${newId}]" placeholder="Importe" 
                    value="0.00" readonly>
            </td>
            <td>
                <input autocomplete="off" type="text" class="form-control propina-input" 
                    name="propina[${newId}]" placeholder="Propina" 
                    value="0" >
            </td>
            <td>
                <input autocomplete="off" type="date" class="form-control" 
                    name="periodo_inicio[${newId}]" 
                    value="" >
            </td>
            <td>
                <input autocomplete="off" type="date" class="form-control" 
                    name="periodo_fin[${newId}]"  
                    value="">
            </td>
            <td>
                <div class="archivos-seleccionados" id="archivos_${newId}">
                    <small class="text-muted">No hay archivos</small>
                </div>
            </td>
            <td>
                <div class="mt-1">
                    <button type="button" class="btn btn-sm btn-success btn-seleccionar-pdf" data-row="${newId}">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <button type="button" class="btn btn-sm btn-warning btn-seleccionar-xml" data-row="${newId}">
                        <i class="mdi mdi-code-tags"></i> XML
                    </button>
                </div>
                <button type="button" class="btn btn-sm btn-danger remove-row" 
                    data-row="${newId}">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
    
    tableBody.append(nuevaFila);
});

// Sincronizar cambios del Header con los inputs ocultos de las filas
$(document).on('change keyup', '.header-partida', function() {
    const val = $(this).val();
    const groupContainer = $(this).closest('.group-container');
    groupContainer.find('.row-partida').val(val);
});
$(document).on('change keyup', '.header-proyecto', function() {
    const val = $(this).val();
    const groupContainer = $(this).closest('.group-container');
    groupContainer.find('.row-proyecto').val(val);
});
$(document).on('change keyup', '.header-encabezado', function() {
    const val = $(this).val();
    const groupContainer = $(this).closest('.group-container');
    groupContainer.find('.row-encabezado').val(val);
});

// Agregar Nuevo Grupo
$('#btnAgregarGrupo').on('click', function() {
    // Clonar el primer grupo para usarlo como template (limpiando valores)
    // Nota: Esto requiere que exista al menos un grupo. Si no, habría que construir el template en JS.
    // Como backend garantiza al menos 'default', podemos intentar clonar.
    
    const primerGrupo = $('.group-container').first();
    if(primerGrupo.length > 0) {
        const nuevoGrupo = primerGrupo.clone();
        
        // Limpiar inputs del header
        nuevoGrupo.find('.header-partida').val('');
        nuevoGrupo.find('.header-proyecto').val('');
        nuevoGrupo.find('.header-encabezado').val('Borrador');
        
        // Limpiar tabla (dejar vacía)
        nuevoGrupo.find('tbody').empty();
        
        // Actualizar data-group-id (opcional, solo para unicidad si se usa)
        const id = Date.now();
        nuevoGrupo.attr('data-group-id', id);
        
        // Insertar antes del boton
        $(this).before(nuevoGrupo);
    }
});

// Delegación de eventos para botones de filas dinámicas (Eliminar)
$(document).on('click', '.remove-row', function() {
    const rowId = $(this).data('row');
    const tr = $(this).closest('tr');
    
    // Si no es una fila nueva (es decir, ya existía en DB), agregamos a deletedRows
    if (!isNaN(rowId) && !String(rowId).startsWith('new_')) {
        deletedRows.push(rowId);
    } else {
        // Es una fila nueva que no se ha guardado aun, solo la borramos del DOM y de la variable JS
        delete archivosPorFila[rowId];
    }
    
    tr.remove();
    $('#deleted_rows').val(JSON.stringify(deletedRows));
});

// Delegación de eventos para selección de archivos (PDF/XML) en filas dinámicas
$(document).on('click', '.btn-seleccionar-pdf, .btn-seleccionar-xml', function() {
    const rowId = $(this).data('row');
    const isPdf = $(this).hasClass('btn-seleccionar-pdf');
    const type = isPdf ? 'pdf' : 'xml';
    const accept = isPdf ? '.pdf' : '.xml';
    
    // Crear input file temporal
    const fileInput = $('<input type="file" multiple accept="' + accept + '" style="display:none;">');
    $('body').append(fileInput);
    
    fileInput.trigger('click');
    
    fileInput.on('change', function() {
        const files = this.files;
        if (files.length > 0) {
            // Agregar archivos al array global
            if (!archivosPorFila[rowId]) archivosPorFila[rowId] = { pdf: [], xml: [] };
            if (!archivosPorFila[rowId][type]) archivosPorFila[rowId][type] = [];
            
            Array.from(files).forEach(file => {
                archivosPorFila[rowId][type].push(file);
            });
            
            // Renderizar vista previa
            renderizarArchivosFila(rowId);
        }
        fileInput.remove();
    });
});

// Función helper para renderizar los archivos de una fila
function renderizarArchivosFila(rowId) {
    const container = $(`#archivos_${rowId}`);
    const archivos = archivosPorFila[rowId];
    let html = '';
    
    if (archivos && archivos.pdf && archivos.pdf.length > 0) {
        html += '<div class="text-success"><small><strong>PDF:</strong></small></div><ul class="list-unstyled mb-1">';
        archivos.pdf.forEach(file => {
            const name = (file instanceof File) ? file.name : file.nombre;
            html += `<li><small>${name}</small></li>`;
        });
        html += '</ul>';
    }
    
    if (archivos && archivos.xml && archivos.xml.length > 0) {
        html += '<div class="text-info"><small><strong>XML:</strong></small></div><ul class="list-unstyled mb-0">';
        archivos.xml.forEach(file => {
             const name = (file instanceof File) ? file.name : file.nombre;
             const total = (file instanceof File) ? '' : (file.total ? ` (Total: ${file.total})` : '');
             html += `<li><small>${name}${total}</small></li>`;
        });
        html += '</ul>';
    }
    
    if (html === '') {
        html = '<small class="text-muted">No hay archivos</small>';
    }
    
    container.html(html);
}




</script>