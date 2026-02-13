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
                            <h3 class="mt-0 header-title">PROVEEDOR: <strong> GOBIERNO DEL ESTADO DE GUANAJUATO SFIYA
                                    SECRETARIA DE TURISMO</strong></h3>
                            <p class="text-muted mb-3">
                                GEG850101FQ2
                            </p>
                            <form id="form_go_editar" enctype="multipart/form-data">
                                <input type="hidden" name="editar" value="1">
                                <input type="hidden" name="id_reserva_go" value="<?= $id_reserva ?>">
                                <input type="hidden" name="id_registro_go" value="<?= $id_registro_go ?>">
                                <div class="form-row">
                                    <!-- Dirección Responsable -->
                                    <div class="col-md-4 mb-3">
                                        <label for="direccion_responsable">Dirección Responsable <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2" id="direccion_responsable"
                                            name="direccion_responsable" required>
                                            <?php foreach ($cat_area as $a): ?>
                                                <option value="<?= $a->id_area ?>" <?php echo ($a->id_area == $registro->id_direccion_responsable) ? 'selected' : ''; ?>>
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
                                            value="<?= isset($registro->fecha_tramite) ? date('Y-m-d', strtotime($registro->fecha_tramite)) : date('Y-m-d') ?>"
                                            required>
                                    </div><!--end col-->
                                    <div class="col-md-4 mb-3">
                                        <label for="reponsable_solicitud">Responsable de la Solicitud <span
                                                style="color:red;">*</span></label>
                                        <select name="id_reponsable_solicitud" class="form-control select2" required>
                                            <?php foreach ($cat_usuario as $u): ?>
                                                <option value="<?= $u->id_usuario ?>" <?php echo ($u->id_usuario == $registro->id_reponsable_solicitud) ? 'selected' : ''; ?>>
                                                    <?= $u->nombre_completo ?>
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
                                             
                                                    <option value="<?= $s->id_secretario ?>" <?=($s->id_secretario == $registro->secretario )?'selected':''?> ><?= $s->dsc_secretario ?></option>
                                        
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
                                              
                                                    <option value="<?= $s->id_subsecretario ?>" <?= ($s->id_subsecretario == $registro->id_subsecretario)?'selected':'' ?> ><?= $s->dsc_subsecretario ?></option>
                                               
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
                                        <label for="lugar">Lugar.<span
                                                style="color:red;">*</span></label>
                                        <input type="text" class="form-control" id="lugar"
                                            name="lugar" value="<?= $registro->lugar ?>">
                                        
                                    </div><!--end col-->

                                </div><!--end form-row-->
                                <div class="form-row">


                                    <!-- Comisión Eliminada de Aquí -->
                                    <div class="col-md-12 mb-3">
                                        <label for="no_consecutivo">No. Consecutivo.<span
                                                style="color:red;">*</span></label>
                                        <input type="number" class="form-control" autocomplete="off" id="no_consecutivo"
                                            name="no_consecutivo" placeholder="001"
                                            value="<?= (isset($registro->no_consecutivo)) ? $registro->no_consecutivo : '' ?>">
                                        <div class="invalid-feedback">
                                            Campo no Valido
                                        </div>
                                    </div><!--end col-->

                                </div><!--end form-row-->
                                <br>

                              <?php
                                    $partidas_mostradas = [];
                                    foreach ($datosGrupal as $i => $p):
                                        // Evita duplicados por id_partida
                                        if (in_array($p[$i]->id_partida, $partidas_mostradas)) {
                                            continue;
                                        }
                                        $partidas_mostradas[] = $p[$i]->id_partida;
                                        ?>
                                        <input type="hidden" id="id_presupuesto_<?= $i ?>" name="id_presupuesto[<?= $i ?>]" value="<?= $p[$i]->id_presupuesto_go ;?>" >
                                        <p class="text-muted mb-4 text-center">Agregar Factura GO.</p>
                                        <hr>
                                        <div class="form-row"> <!-- presupuesto -->
                                            <!-- Partida y Factura PDF -->
                                            <div class="col-md-4 mb-3">
                                                <label for="partida_<?= $i ?>">Partida<span style="color:red;">*</span></label>
                                                <select class="form-control" id="partida_<?= $i ?>" name="partida[]" disabled>
                                                    <?php foreach ($cat_partida as $o): ?>
                                                        <option value="<?= $o->id_partida ?>" <?= (isset($p[$i]->id_partida) && $p[$i]->id_partida == $o->id_partida) ? 'selected' : '' ?>>
                                                            <?= $o->cuenta_cable ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="proyecto_<?= $i ?>">Proyecto<span style="color:red;">*</span></label>
                                                <select class="form-control" id="proyecto_<?= $i ?>" name="proyecto[]" disabled>
                                                    <?php foreach ($cat_proyecto as $o): ?>
                                                        <option value="<?= $o->id_proyecto ?>" <?= (isset($p[$i]->id_proyecto) && $p[$i]->id_proyecto == $o->id_proyecto) ? 'selected' : '' ?>>
                                                            <?= $o->proyecto ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- Encabezado y XML -->
                                            <div class="col-md-4 mb-3">
                                                <label for="encabezado_<?= $i ?>">Encabezado<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" autocomplete="off"
                                                    id="encabezado_<?= $i ?>" name="encabezado[<?= $i ?>]"
                                                    value="<?= (isset($p[$i]->encabezado) && !empty($p[$i]->encabezado)) ? $p[$i]->encabezado : '' ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="mt-0 header-title">REFERENCIA</h4>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered" id="makeEditable_<?= $i ?>">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 12%">PROPINA</th>
                                                                        <th style="width: 30%">DESCRIPCIÓN</th>
                                                                        <th style="width: 15%">VIGENCIA</th>
                                                                        <th style="width: 20%">ARCHIVOS</th>
                                                                        <th style="width: 15%">ACCIONES</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if(isset($p['datos']) && is_array($p['datos'])): ?>
                                                                    <?php foreach($p['datos'] as $j => $r): ?>
                                                                    <?php $uniqueId = $r['id_identificador'] ?>
                                                                    <tr data-row-index="<?= $uniqueId ?>">
                                                                        <input type="hidden" name="id_identificador_<?= $i ?>[]" value="<?= $r['id_identificador'] ?>" >
                                                                        <!-- Propina -->
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">$</span>
                                                                                </div>
                                                                                <input autocomplete="off" type="text" class="form-control propina-input" 
                                                                                    name="propina_<?= $i ?>[]" placeholder="0.00" 
                                                                                    value="<?= $r['propina']; ?>" >
                                                                            </div>
                                                                        </td>
                                                                        <!-- Descripción (Concepto + Comisión) -->
                                                                        <td>
                                                                            <textarea autocomplete="off" class="form-control mb-1" 
                                                                                name="concepto_<?= $i ?>[]" placeholder="Concepto" 
                                                                                rows="2" style="font-size: 0.85rem;"><?= (isset($r['concepto'])) ? $r['concepto'] : '' ?></textarea>
                                                                            
                                                                            <textarea autocomplete="off" class="form-control" 
                                                                                name="comision_<?= $i ?>[]" placeholder="Comisión / Evento" 
                                                                                rows="2" style="font-size: 0.85rem; background-color: #f8f9fa;"><?= (isset($r['comision'])) ? $r['comision'] : '' ?></textarea>
                                                                        </td>
                                                                        <!-- Vigencia -->
                                                                        <td>
                                                                            <div class="input-group input-group-sm mb-1">
                                                                                <div class="input-group-prepend"><span class="input-group-text">Del</span></div>
                                                                                <input autocomplete="off" type="date" class="form-control" 
                                                                                    name="periodo_inicio_<?= $i ?>[]" 
                                                                                    value="<?= date('Y-m-d', strtotime($r['periodo_inicio'])) ?>" >
                                                                            </div>
                                                                            <div class="input-group input-group-sm">
                                                                                <div class="input-group-prepend"><span class="input-group-text">Al </span></div>
                                                                                <input autocomplete="off" type="date" class="form-control" 
                                                                                    name="periodo_fin_<?= $i ?>[]"  
                                                                                    value="<?= date('Y-m-d', strtotime($r['periodo_fin']))  ?>">
                                                                            </div>
                                                                        </td>
                                                                        <!-- Archivos -->
                                                                        <td>
                                                                            <div class="archivos-seleccionados" id="archivos_<?= $uniqueId ?>">
                                                                                <?php if(!empty($r['ruta_relativa'])): ?>
                                                                                <a href="<?= base_url() . $r['ruta_relativa'] ?>" target="_blank">
                                                                                    <i class="fas fa-file-pdf"></i> PDF
                                                                                </a>
                                                                                <?php else: ?>
                                                                                <small class="text-muted">No hay archivos</small>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </td>
                                                                        <!-- Acciones -->
                                                                        <td>
                                                                            <div class="btn-group-vertical btn-group-sm w-100">
                                                                                <button type="button" class="btn btn-success btn-seleccionar-pdf mb-1" 
                                                                                    data-row="<?= $uniqueId ?>">
                                                                                    <i class="fas fa-file-pdf"></i> PDF
                                                                                </button>
                                                                                <button type="button" class="btn btn-warning btn-seleccionar-xml mb-1" 
                                                                                    data-row="<?= $uniqueId ?>">
                                                                                    <i class="mdi mdi-code-tags"></i> XML
                                                                                </button>
                                                                                <button type="button" class="btn btn-danger remove-row" 
                                                                                    data-row="<?= $uniqueId ?>">
                                                                                    <i class="fas fa-trash"></i> Eliminar
                                                                                </button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <div id="hidden-file-inputs-container"></div>
                                <a class="btn btn-gradient-danger" style="color:white"
                                    onclick="window.history.back()">Atrás</a>
                           
                                    <button class="btn btn-gradient-primary" id="btnEditarGo" type="submit">Guardar</button>
                     
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
// Objeto global para almacenar archivos por fila
const archivosPorFila = {};

// Función para inicializar una fila en archivosPorFila
function inicializarFilaEnArchivos(rowIndex) {
    if (!archivosPorFila[rowIndex]) {
        archivosPorFila[rowIndex] = {
            pdf: null,
            xml: null
        };
    }
}

// Eliminar fila
$(document).on('click', '.remove-row', function () {
    const rowId = $(this).data('row');
    const row = $(this).closest('tr');
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción eliminará también los archivos asociados",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            if (archivosPorFila[rowId]) {
                delete archivosPorFila[rowId];
            }
            
            row.remove();
            
            Swal.fire('Eliminado!', 'La fila ha sido eliminada.', 'success');
        }
    });
});

// SweetAlert para seleccionar PDF
$(document).on('click', '.btn-seleccionar-pdf', function() {
    const rowIndex = $(this).data('row');
    inicializarFilaEnArchivos(rowIndex);
    
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.pdf';
    input.multiple = true;

    input.onchange = e => {
        const files = Array.from(e.target.files);
        const maxSize = 100 * 1024 * 1024;
        const archivosValidos = files.filter(file => file.size <= maxSize);
        const archivosInvalidos = files.filter(file => file.size > maxSize);
        
        if (archivosInvalidos.length > 0) {
            Swal.fire({
                title: 'Archivos muy grandes',
                text: `${archivosInvalidos.length} archivo(s) exceden el tamaño máximo de 100MB`,
                icon: 'error'
            });
        }
        
        if (archivosValidos.length === 0) return;

        Swal.fire({
            title: 'Archivos PDF Seleccionados',
            html: `
                <div class="text-left">
                    <p><strong>${archivosValidos.length} archivo(s) PDF:</strong></p>
                    <ul class="text-left" style="max-height: 200px; overflow-y: auto;">
                        ${archivosValidos.map(file => 
                            `<li>${file.name} (${(file.size/1024/1024).toFixed(2)} MB)</li>`
                        ).join('')}
                    </ul>
                </div>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Confirmar PDF',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                inicializarFilaEnArchivos(rowIndex);
                archivosPorFila[rowIndex].pdf = archivosValidos;
                actualizarVistaArchivos(rowIndex);
                
                Swal.fire({
                    title: 'PDF Guardados!',
                    text: `${archivosValidos.length} archivo(s) PDF asociados a esta fila`,
                    icon: 'success',
                    timer: 2000
                });
            }
        });
    };
    input.click();
});

// SweetAlert para seleccionar XML
$(document).on('click', '.btn-seleccionar-xml', function() {
    const rowIndex = $(this).data('row');
    inicializarFilaEnArchivos(rowIndex);

    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.xml';
    input.multiple = true;

    input.onchange = e => {
        const files = Array.from(e.target.files);
        const maxSize = 100 * 1024 * 1024;
        const archivosValidos = files.filter(file => file.size <= maxSize);
        const archivosInvalidos = files.filter(file => file.size > maxSize);
        
        if (archivosInvalidos.length > 0) {
            Swal.fire({
                title: 'Archivos muy grandes',
                text: `${archivosInvalidos.length} archivo(s) exceden el tamaño máximo de 100MB`,
                icon: 'error'
            });
        }
        
        if (archivosValidos.length === 0) return;

        Swal.fire({
            title: 'Archivos XML Seleccionados',
            html: `
                <div class="text-left">
                    <p><strong>${archivosValidos.length} archivo(s) XML:</strong></p>
                    <ul class="text-left" style="max-height: 200px; overflow-y: auto;">
                        ${archivosValidos.map(file => 
                            `<li>${file.name} (${(file.size/1024/1024).toFixed(2)} MB)</li>`
                        ).join('')}
                    </ul>
                </div>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Confirmar XML',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                inicializarFilaEnArchivos(rowIndex);
                archivosPorFila[rowIndex].xml = archivosValidos;
                actualizarVistaArchivos(rowIndex);
                
                Swal.fire({
                    title: 'XML Guardados!',
                    text: `${archivosValidos.length} archivo(s) XML asociados a esta fila`,
                    icon: 'success',
                    timer: 2000
                });
            }
        });
    };
    input.click();
});

// Función para actualizar vista de archivos
function actualizarVistaArchivos(rowIndex) {
    const container = $(`#archivos_${rowIndex}`);
    
    if (!archivosPorFila[rowIndex]) {
        container.html('<small class="text-muted">No hay archivos</small>');
        return;
    }
    
    const archivos = archivosPorFila[rowIndex];
    let html = '';
    let count = 0;

    if (archivos.pdf && archivos.pdf.length > 0) {
        html += `<div><small class="text-success"><strong>PDF:</strong> ${archivos.pdf.length} archivo(s)</small></div>`;
        count += archivos.pdf.length;
    }

    if (archivos.xml && archivos.xml.length > 0) {
        html += `<div><small class="text-warning"><strong>XML:</strong> ${archivos.xml.length} archivo(s)</small></div>`;
        count += archivos.xml.length;
    }

    if (count === 0) {
        html = '<small class="text-muted">No hay archivos</small>';
    } else {
        html += `<div><small class="text-info"><strong>Total:</strong> ${count} archivo(s)</small></div>`;
    }

    container.html(html);
}

// Preparar FormData
function prepararFormData() {
    const formData = new FormData();
    const form = $('#form_go_editar')[0];
    const formElements = new FormData(form);
    
    // Agregar todos los campos del formulario
    for (let [key, value] of formElements) {
        formData.append(key, value);
    }
    
    // Agregar archivos
    Object.keys(archivosPorFila).forEach(rowIndex => {
        const archivos = archivosPorFila[rowIndex];
        
        if (archivos && archivos.pdf) {
            archivos.pdf.forEach((file, fileIndex) => {
                formData.append(`pdf_${rowIndex}[${fileIndex}]`, file);
            });
        }
        
        if (archivos && archivos.xml) {
            archivos.xml.forEach((file, fileIndex) => {
                formData.append(`xml_${rowIndex}[${fileIndex}]`, file);
            });
        }
    });

    return formData;
}

// Envío del formulario
$('#form_go_editar').on('submit', function(e) {
    e.preventDefault();
    const formData = prepararFormData();

    $.ajax({
        type: "POST",
        url: "<?= base_url()?>index.php/Agregar/editarGO",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            console.log(response);
            if(!response.error){
                Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                setTimeout(() => {
                    window.location.href = base_url + "index.php/Principal/tablaArchivos/"+response.idRegistro+'/GO';
                }, 1500);
            }else{
                Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
            }
        },
        beforeSend: function (info){
            $('#btnEditarGo').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
        },
        complete: function (info){
            $('#btnEditarGo').prop('disabled', false).html('Guardar');
        },
        error: function (response,jqXHR, textStatus, errorThrown) {
            var res= JSON.parse(response.responseText);
            Swal.fire("Error", '<p> '+ res.message + '</p>');  
        }
    });
});

// Inicialización
$(document).ready(function() {
    // Inicializar filas existentes
    $('table tbody tr').each(function() {
        const rowIndex = $(this).data('row-index');
        if (rowIndex) {
            inicializarFilaEnArchivos(rowIndex);
        }
    });
    
    // Aplicar inputmask a todos los campos de importe y propina
    $('.importe-input, .propina-input').each(function() {
        $(this).inputmask('numeric', {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 2,
            autoGroup: true,
            prefix: '$ ',
            rightAlign: false
        });
    });
    
    console.log('Archivos por fila inicializados:', archivosPorFila);
});
</script>