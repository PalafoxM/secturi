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
                                


                                <?php foreach($grupos as $key => $grupo): ?>
                               
                                <div class="card group-container mb-4" >
                                    <div class="card-body">
                                        
                                        <!-- Header Inputs (Visual Controls) -->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label>Partida <span class="text-danger">*  </span></label>
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
                                                <label>Proyecto <span class="text-danger">*  </span></label>
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
                                                     
                                                        <th style="width: 8%;">PROPINA</th>
                                                        <th>INICIO</th>
                                                        <th>FIN</th>
                                                        <th style="width: 20%;">ARCHIVOS</th>
                                                        <th>ACCIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($grupo['tabla'] as $j => $r): ?>
                                                    <?php 
                                                        $uniqueId = $j;
                                                        $totalImporte = 0;
                                                        
                                                        $inicio = isset($r['periodo_inicio']) ? date('Y-m-d', strtotime($r['periodo_inicio'])) : '';
                                                        $fin = isset($r['periodo_fin']) ? date('Y-m-d', strtotime($r['periodo_fin'])) : '';
                                                        $propina = isset($r['propina']) ? $r['propina'] : '';
                                                    ?>
                                                    <tr data-row-index="<?= $uniqueId ?>">
                                                        <input type="hidden" name="rowIndex[]" value="<?= $uniqueId ?>">
                                                        <input type="hidden" name="id_identificador[<?= $uniqueId ?>]" value="<?= isset($r['id_identificador']) ? $r['id_identificador'] : '' ?>">
                                                        
                                                        <!-- Hidden Inputs synced with Header -->
                                                        <input type="hidden" class="row-partida" name="id_partida[<?= $uniqueId ?>]" value="<?= $grupo['id_partida'] ?>">
                                                        <input type="hidden" class="row-proyecto" name="id_presupuesto[<?= $uniqueId ?>]" value="<?= $r['id_presupuesto'] ?>">
                                                      
                                                      
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
                                                                <?php if(isset($r['ruta_relativa'])): ?>
                                                                   
                                                                        <div class="text-success"><small><strong>PDF:</strong></small></div>
                                                                        <ul class="list-unstyled mb-1">
                                                                      
                                                                            <li>
                                                                                <?php if(isset($r['ruta_relativa'])): ?>
                                                                                    <a href="<?= base_url().$r['ruta_relativa'] ?>" target="_blank"><small> FACTURA PDF</small></a>
                                                                                <?php else: ?>
                                                                                    <small><?= $r['ruta_relativa'] ?></small>
                                                                                <?php endif; ?>
                                                                            </li>
                                                                       
                                                                        </ul>
                                                                   
                                                                <?php endif; ?>

                                                                <?php if(isset($r['id_xml']) ): ?>
                                                                    
                                                                        <div class="text-info"><small><strong>XML:</strong></small></div>
                                                                        <ul class="list-unstyled mb-0">
                                                                   
                                                                           <a href="<?= base_url().'index.php/Inicio/VerXML/'.$r['id_xml'].'/go' ?>" target="_blank"> <li><small>FOLIO: <?= $r['folio'] ?> (Total: <?= isset($r['total']) ? $r['total'] : '0.00' ?>)</small></li></a>
                                                                      
                                                                        </ul>
                                                                    
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


    // --- ENVIAR ---

    // Guardar Borrador
    $('#btnGuardarBorrador').on('click', function() {
        $('#es_borrador').val('1');
        enviarFormulario("<?= base_url()?>index.php/Agregar/guardaBorradorGO", $(this));
    });

    // Guardar y Enviar
    $('#formBorrador_go').on('submit', function(e) {
        e.preventDefault();
        $('#es_borrador').val('0');
        
        if (!validarFormulario()) return;

        enviarFormulario("<?= base_url()?>index.php/Agregar/guardaGO", $('#btnGuardaGo'));
    });

    function enviarFormulario(url, btn) {
        const formData = prepararFormData();
        formData.append('deleted_rows', JSON.stringify(deletedRows));
        
        const btnText = btn.html();

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: function (){
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            },
            success: function (response) {
                if(!response.error){
                    Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                    setTimeout(() => {
                        // Redirección según sea borrador o final
                        const redirect = ($('#es_borrador').val() == '1') ? "listaBorradoresGO" : "listaReservaGO";
                        window.location.href = base_url + "index.php/Principal/" + redirect;
                    }, 1500);
                }else{
                    Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                }
            },
            complete: function (){
                btn.prop('disabled', false).html(btnText);
            },
            error: function (response) {
                // Manejo de error seguro
                let msg = "Error desconocido";
                try {
                    const res = JSON.parse(response.responseText);
                    msg = res.message || msg;
                } catch(e) {}
                Swal.fire("Error", '<p> '+ msg + '</p>');  
            }
        });
    }

    function validarFormulario() {
        // Validacion de fechas
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
            return false;
        }

        // Validacion de Archivos (Al menos 1 PDF y 1 XML por fila activa)
        let archivosValidos = true;
        $('tr[data-row-index]').each(function() {
            const rowIndex = $(this).data('row-index');
            const archivos = archivosPorFila[rowIndex];
            
            let tienePdf = false;
            let tieneXml = false;

            if (archivos) {
                if (archivos.pdf && archivos.pdf.length > 0) tienePdf = true;
                if (archivos.xml && archivos.xml.length > 0) tieneXml = true;
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
            return false;
        }

        return true;
    }

</script>