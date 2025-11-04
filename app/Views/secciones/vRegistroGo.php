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
                            <form id="form_go" enctype="multipart/form-data">
                                <input type="hidden" name="editar" value="1">
                                <input type="hidden" name="id_reserva_go" value="<?= $id_reserva ?>">
                                <div class="form-row">
                                    <!-- Dirección Responsable -->
                                    <div class="col-md-4 mb-3">
                                        <label for="direccion_responsable">Dirección Responsable <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="direccion_responsable"
                                            name="direccion_responsable" required>
                                            <?php foreach ($cat_area as $a): ?>
                                                <option value="<?= $a->id_area ?>" <?php echo ($a->id_area == $usuario->id_area) ? 'selected' : ''; ?>>
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
                                                <?php if (isset($registro_pt->secretario) && !empty($registro_pt->secretario)) { ?>
                                                    <option value="<?= $s->id_secretario ?>"
                                                        <?= ($s->id_secretario == $registro_pt->secretario) ? 'selected' : '' ?>>
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
                                            placeholder="Concepto del gasto" name="concepto_gasto">
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
                                            placeholder="Lugar" name="lugar">
                                    </div><!--end col-->
                                    <div class="col-md-4 mb-3">
                                        <label for="no_reserva">No. de Reserva.<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" autocomplete="off" id="no_reserva"
                                            name="no_reserva"
                                            value="<?= (isset($reserva->no_reserva)) ? $reserva->no_reserva : '' ?>"
                                            readonly>
                                        <div class="invalid-feedback">
                                            Campo no Valido
                                        </div>
                                    </div><!--end col-->
                                </div><!--end form-row-->
                                <div class="form-row">

                                    <div class="col-md-4 mb-3">
                                        <label for="no_consecutivo">No. Consecutivo.<span
                                                style="color:red;">*</span></label>
                                        <input type="number" class="form-control" autocomplete="off" id="no_consecutivo"
                                            name="no_consecutivo" placeholder="001"
                                            value="<?= (isset($reserva->no_consecutivo)) ? $reserva->no_consecutivo : '' ?>">
                                        <div class="invalid-feedback">
                                            Campo no Valido
                                        </div>
                                    </div><!--end col-->

                                </div><!--end form-row-->
                                <br>

                                <?php
                                $partidas_mostradas = [];
                                foreach ($presupuesto as $i => $p):
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
                                                <?php foreach ($cat_partida as $o): ?>
                                                    <option value="<?= $o->id_partida ?>" <?= (isset($p->id_partida) && $p->id_partida == $o->id_partida) ? 'selected' : '' ?>>
                                                        <?= $o->cuenta_cable ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="proyecto_<?= $i ?>">Proyecto<span
                                                    style="color:red;">*</span></label>
                                            <select class="form-control" id="proyecto_<?= $i ?>" name="proyecto[]" disabled>
                                                <?php foreach ($cat_proyecto as $o): ?>
                                                    <option value="<?= $o->id_proyecto ?>" <?= (isset($p->id_proyecto) && $p->id_proyecto == $o->id_proyecto) ? 'selected' : '' ?>>
                                                        <?= $o->proyecto ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Encabezado y XML -->
                                        <div class="col-md-4 mb-3">
                                            <label for="encabezado_<?= $i ?>">Encabezado<span
                                                    style="color:red;">*</span></label>
                                            <input type="text" class="form-control" autocomplete="off"
                                                id="encabezado_<?= $i ?>" name="encabezado[]"
                                                value="<?= (isset($p->encabezado) && !empty($p->encabezado)) ? $p->encabezado : '' ?>">
                                        </div>


                                    </div>

                             
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">

                                                    <h4 class="mt-0 header-title">REFERENCIA</h4>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered" id="makeEditable3">
                                                            <thead>
                                                                <tr>
                                                                    <th>IMPORTE</th>
                                                                    <th>PROPINA</th>
                                                                    <th>INICIO</th>
                                                                    <th>FIN</th>
                                                                    <th>ARCHIVOS</th>
                                                                    <th>ACCIONES</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                 
                                                                    <td><input type="text" autocomplete="off"
                                                                            class="form-control" name="importe[]"
                                                                            placeholder="Importe"></td>
                                                                    <td>
                                                                        <input autocomplete="off" type="text"
                                                                            class="form-control" name="propina[]"
                                                                            placeholder="Propina">
                                                                    </td>
                                                                    <td>
                                                                        <input autocomplete="off" type="date"
                                                                            class="form-control" name="periodo_inicio[]" >
                                                                    </td>
                                                                    <td>
                                                                           <input autocomplete="off" type="date"
                                                                            class="form-control" name="periodo_fin[]">
                                                                    </td>
                                                                     <td>
     
                                                                        <div class="archivos-seleccionados" id="archivos_<?= $i?>">
                                                                            <small class="text-muted">No hay archivos</small>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                         
                                                                        <button type="button" class="btn btn-sm btn-success btn-seleccionar-pdf" data-row="<?= $i?>">
                                                                            <i class="fas fa-file-pdf"></i> PDF
                                                                        </button>
                                                                        <button type="button" class="btn btn-sm btn-warning btn-seleccionar-xml" data-row="<?= $i?>">
                                                                            <i class="mdi mdi-code-tags"></i> XML
                                                                        </button>
                                                                        
                                                                         <button type="button" class="btn btn-sm btn-danger remove-row">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="text-right mt-2">
                                                            <!-- Contenedor mejorado para el botón -->
                                                            <a id="but_add" class="btn btn-primary text-white">
                                                                <i class="fas fa-plus"></i> Agregar Fila
                                                            </a>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-8"></div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>TOTAL:</label>
                                                                    <input type="text"
                                                                        name="total_importe"
                                                                        class="form-control font-weight-bold text-right"
                                                                        id="total_importe" value="0.00" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end card-body-->
                                            </div>


                                        </div>
                                        <!-- end col -->
                                    </div>
                                <?php endforeach; ?>
                                <div id="hidden-file-inputs-container"></div>
                                <a class="btn btn-gradient-danger" style="color:white"
                                    onclick="window.history.back()">Atrás</a>
                                <?php if (!$edita): ?>
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
   // ini.inicio.formGo();
    $('.add-file').on('click', function (e) {
        e.preventDefault();
        const inputId = $(this).data('target');
        $(inputId).click();
    });

   // Objeto global para almacenar archivos por fila
const archivosPorFila = {};
let rowCounter = 0;

$('#but_add').click(function () {
    rowCounter++;
    var newRow = `<tr data-row-index="${rowCounter}">
    
        <td>
        <input type="text" autocomplete="off" class="form-control" importe-input" name="importe[]" placeholder="Importe">
        </td>
        <td>
            <input autocomplete="off" type="text" class="form-control propina-input" name="propina[]" placeholder="Propina">
        </td>
        <td>
            <input autocomplete="off" type="date" class="form-control" name="periodo_inicio[]" placeholder="Contribuyente">
        </td>
        <td>
            <input autocomplete="off" type="date" class="form-control" name="periodo_fin[]" placeholder="RFC">
        </td>
        <td>
            <!-- Contenedor para mostrar archivos seleccionados -->
            <div class="archivos-seleccionados" id="archivos_${rowCounter}">
                <small class="text-muted">No hay archivos</small>
            </div>
        </td>
        <td>
       
            <button type="button" class="btn btn-sm btn-success btn-seleccionar-pdf" data-row="${rowCounter}">
                <i class="fas fa-file-pdf"></i> PDF
            </button>
            <button type="button" class="btn btn-sm btn-warning btn-seleccionar-xml" data-row="${rowCounter}">
                <i class="mdi mdi-code-tags"></i> XML
            </button>
            <button type="button" class="btn btn-sm btn-danger remove-row">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>`;

    $('#makeEditable3 tbody').append(newRow);

    // Inicializar almacenamiento para esta fila
    archivosPorFila[rowCounter] = {
        pdf: null,
        xml: null
    };

    // Inicializar Select2 en la nueva fila (si aplica)
    $('#makeEditable3 tbody tr:last .select2').select2();

    // Inicializar máscara para los campos numéricos
    $('#makeEditable3 tbody tr:last input[name="importe[]"]').inputmask('numeric', {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 2,
        autoGroup: true,
        prefix: '$ ',
        rightAlign: false
    });
    $('#makeEditable3 tbody tr:last input[name="propina[]"]').inputmask('numeric', {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 2,
        autoGroup: true,
        prefix: '$ ',
        rightAlign: false
    });
    
    calcularTotal();
});

// Eliminar fila con SweetAlert de confirmación
$(document).on('click', '.remove-row', function () {
    const row = $(this).closest('tr');
    const rowIndex = row.data('row-index');
    
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
            // Eliminar del objeto de archivos
            if (archivosPorFila[rowIndex]) {
                delete archivosPorFila[rowIndex];
            }
            
            row.remove();
            calcularTotal();
            
            Swal.fire(
                'Eliminado!',
                'La fila ha sido eliminada.',
                'success'
            );
        }
    });
});

// SweetAlert para seleccionar PDF
$(document).on('click', '.btn-seleccionar-pdf', function() {
    const rowIndex = $(this).data('row');
    const key = rowIndex;

    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.pdf';
    input.multiple = true;

    input.onchange = e => {
        const files = Array.from(e.target.files);
        
        // Validar tamaño máximo (5MB)
        const maxSize = 5 * 1024 * 1024;
        const archivosValidos = files.filter(file => file.size <= maxSize);
        const archivosInvalidos = files.filter(file => file.size > maxSize);
        
        if (archivosInvalidos.length > 0) {
            Swal.fire({
                title: 'Archivos muy grandes',
                text: `${archivosInvalidos.length} archivo(s) exceden el tamaño máximo de 5MB`,
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
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'custom-swal-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Guardar archivos
                archivosPorFila[key].pdf = archivosValidos;
                
                // Actualizar vista
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
    const key = rowIndex;

    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.xml';
    input.multiple = true;

    input.onchange = e => {
        const files = Array.from(e.target.files);
        
        // Validar tamaño máximo (5MB)
        const maxSize = 5 * 1024 * 1024;
        const archivosValidos = files.filter(file => file.size <= maxSize);
        const archivosInvalidos = files.filter(file => file.size > maxSize);
        
        if (archivosInvalidos.length > 0) {
            Swal.fire({
                title: 'Archivos muy grandes',
                text: `${archivosInvalidos.length} archivo(s) exceden el tamaño máximo de 5MB`,
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
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'custom-swal-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Guardar archivos
                archivosPorFila[key].xml = archivosValidos;
                
                // Actualizar vista
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

// Función para actualizar la vista de archivos
function actualizarVistaArchivos(rowIndex) {
    const container = $(`#archivos_${rowIndex}`);
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


// Función para preparar el FormData para el envío al backend
function prepararFormData() {

  
    const formData = new FormData();
    
    // INCLUIR TODOS LOS CAMPOS DEL FORMULARIO PRIMERO
    const form = $('#form_go')[0];
    const formElements = new FormData(form);
    
    // Copiar todos los campos del formulario
    for (let [key, value] of formElements) {
        formData.append(key, value);
    }
    

    
    $('input[name="importe[]"]').each(function(index) {
        formData.append(`importe[${index}]`, $(this).val());
    });
    
    $('input[name="propina[]"]').each(function(index) {
        formData.append(`propina[${index}]`, $(this).val());
    });
    $('input[name="periodo_inicio[]"]').each(function(index) {
        formData.append(`periodo_inicio[${index}]`, $(this).val());
    });
    $('input[name="periodo_fin[]"]').each(function(index) {
        formData.append(`periodo_fin[${index}]`, $(this).val());
    });
    

    
    
    // FINALMENTE AGREGAR ARCHIVOS
    Object.keys(archivosPorFila).forEach(rowIndex => {
        const archivos = archivosPorFila[rowIndex];
        
        if (archivos.pdf) {
            archivos.pdf.forEach((file, fileIndex) => {
                formData.append(`archivos[${rowIndex}][pdf][${fileIndex}]`, file);
            });
        }
        
        if (archivos.xml) {
            archivos.xml.forEach((file, fileIndex) => {
                formData.append(`archivos[${rowIndex}][xml][${fileIndex}]`, file);
            });
        }
    });

    return formData;
}
// Ejemplo de envío al backend
$('#form_go').on('submit', function(e) {
    e.preventDefault();

    const formData = prepararFormData();

    Swal.fire({
        title: 'Enviando archivos...',
        text: 'Por favor espere',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

     $.ajax({
                    type: "POST",
                    url: "<?= base_url()?>index.php/Agregar/guardaGO",
                    data: formData,
                    processData: false,  // Importante para FormData
                    contentType: false,  // Importante para FormData
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if(!response.error){
                            Swal.fire("Correcto", '<p> '+ response.respuesta + '</p>', 'success');  
                            setTimeout(() => {
                               // window.location.href = base_url + "index.php/Principal/listadoEstatusPT";
                                window.location.href = base_url + "index.php/Principal/tablaArchivos/"+response.idRegistro+'/GO';
                            }, 1500);
                        }else{
                            Swal.fire("Atención", '<p> '+ response.respuesta + '</p>', 'info');  
                        }
                    },
                    beforeSend: function (info){
                         $('#btnGuardaGo').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    },
                    complete: function (info){
                        $('#btnGuardaGo').prop('disabled', false).html('Guardar');
                    },
                    error: function (response,jqXHR, textStatus, errorThrown) {
                        var res= JSON.parse(response.responseText);
                        Swal.fire("Error", '<p> '+ res.message + '</p>');  
                    }
                });
});


// Inicializar la primera fila
$(document).ready(function() {
    // Inicializar la fila 0 si existe
    if ($('#makeEditable3 tbody tr').length > 0) {
        $('tr').first().attr('data-row-index', '0');
        archivosPorFila[0] = {
            pdf: null,
            xml: null
        };
    }
    
    // Agregar estilos CSS para SweetAlert
    const style = document.createElement('style');
    style.textContent = `
        .custom-swal-popup {
            font-size: 14px;
        }
        .swal2-popup .swal2-html-container {
            text-align: left !important;
        }
    `;
    document.head.appendChild(style);
});
    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });
    $(document).on('input', 'input[name="importe[]"]', function() {
	    calcularTotal();
	});
		$(document).on('input', 'input[name="propina[]"]', function() {
	    calcularTotal();
	});
	function calcularTotal() {
	    let total = 0;
	    
	    $('input[name="importe[]"]').each(function() {
	        // Elimina comas y convierte a número
	        const valor = parseFloat($(this).val().replace(/,/g, '')) || 0;
	        total += valor;
	    });
		 $('input[name="propina[]"]').each(function() {
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

</script>