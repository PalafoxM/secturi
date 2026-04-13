<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item active">Comprobación de Gastos</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Formato de Desglose de Viaticos - Solicitud #<?= isset($solicitud) ? $solicitud->id_solicitud_grc : '' ?></h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Datos de la Solicitud (Solo Lectura) -->
                            <h4 class="mt-0 header-title">Datos de la Solicitud</h4>
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label>Evento:</label>
                                    <input type="text" class="form-control" value="<?= isset($solicitud) ? $solicitud->nombre_evento : '' ?>" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Lugar:</label>
                                    <input type="text" class="form-control" value="<?= isset($solicitud) ? $solicitud->lugar : '' ?>" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Total Solicitado:</label>
                                    <input type="text" class="form-control" value="<?= isset($solicitud) ? number_format($solicitud->cantidad, 2) : '' ?>" readonly>
                                </div>
                            </div>
                            
                            <hr>
                            <h5 class="mt-0 header-title">Detalles Solicitados</h5>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Partida/Fondo</th>
                                            <th>Proyecto</th>
                                            <th class="text-right">Importe</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($detalles) && !empty($detalles)): ?>
                                            <?php foreach ($detalles as $det): ?>
                                            <tr>
                                                <td><?= $det->cuenta_cable .' - '. $det->nombre_fondo ?></td>
                                                <td><?= $det->proyecto ?></td>
                                                <td class="text-right">$<?= number_format($det->importe, 2) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="3" class="text-center">No hay detalles</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <hr>
                            <!-- Sección de Comprobación -->
                            <h4 class="mt-0 header-title">Registro de Comprobantes</h4>
                            <form id="form_comprobacion_gastos">
                                <input type="hidden" name="id_solicitud_grc" value="<?= isset($solicitud) ? $solicitud->id_solicitud_grc : '' ?>">
                                
                                <div class="form-row mb-3">
                                    <div class="col-md-6 d-flex align-items-center">
                                        <label class="font-weight-bold mr-2 mb-0">No. Consecutivo:</label>
                                        <div class="d-flex align-items-center border rounded p-1 folio-box" style="background: #f8f9fa;">
                                            <span class="mr-1 small font-weight-bold">GRC</span>
                                            <select name="folio" id="folio" class="select2 form-control form-control-sm mr-1" style="width: auto; min-width: 120px; max-width: 250px;">
                                                <?php if(isset($cat_area) && is_array($cat_area)): ?>
                                                    <?php foreach($cat_area as $area): ?>
                                                        <option value="<?=  $area->prefijo ?>" <?= (isset($id_area) && $area->id_area == $id_area) ? 'selected' : '' ?>><?= $area->prefijo ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <input type="text" name="no_consecutivo" id="no_consecutivo" class="form-control form-control-sm text-center font-weight-bold ml-1" style="width: 60px;" value="<?= isset($no_consecutivo) ? $no_consecutivo : '' ?>" placeholder="001">
                                            <span class="ml-1 small font-weight-bold">/<?= date('Y') ?></span>
                                            <input type="hidden" name="folioCompleto" id="folioCompleto">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="tabla_comprobacion">
                                        <thead>
                                            <tr>
                                                <th>Nombre (Emisor)</th>
                                                <th>RFC</th>
                                                <th>Importe</th>
                                                <th style="width: 50px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Filas dinámicas -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-right">
                                                    <button type="button" class="btn btn-secondary btn-sm" onclick="agregarFilaComprobacion()">
                                                        <i class="fas fa-plus"></i> Agregar Comprobante
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-right font-weight-bold">TOTAL COMPROBADO:</td>
                                                <td class="text-right font-weight-bold" id="total_comprobado">$0.00</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12 text-right">
                                        <a href="<?= base_url('index.php/Inicio/ListadoSolicitudes') ?>" class="btn btn-secondary">Cancelar</a>
                                        <button class="btn btn-primary" id="btnGuardarComprobacion" type="submit">Guardar Comprobación</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
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
<style>
    .folio-box {
        flex-wrap: nowrap;
        gap: 6px;
    }

    #folio {
        min-width: 120px;
    }
</style>



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

<script src="<?= base_url() ?>assets/pages/jquery.forms-advanced.js"></script>

<script>
    // Pasar usuarios a JS
    const catalogoUsuarios = <?= json_encode(isset($usuarios) ? $usuarios : []) ?>;
    const comprobantesGuardados = <?= json_encode(isset($comprobantes_guardados) ? $comprobantes_guardados : []) ?>;

    function agregarFilaComprobacion(data = null) {
        const tbody = document.querySelector('#tabla_comprobacion tbody');
        const rowIndex = Date.now() + Math.floor(Math.random() * 1000);

        // Construir opciones de usuario
        let optionsUsuarios = '<option value="">Seleccione un usuario</option>';
        catalogoUsuarios.forEach(u => {
            const nombreCompleto = u.nombre_completo || u.nombre || '';
            let isSelected = (data && (data.nombre_emisor === nombreCompleto || data.nombre_emisor === u.nombre)) ? 'selected' : '';
            optionsUsuarios += `<option value="${nombreCompleto}" data-rfc="${u.rfc || ''}" ${isSelected}>${nombreCompleto}</option>`;
        });


        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select class="form-control select2 user-select" name="comprobacion[${rowIndex}][nombre_emisor]" required onchange="actualizarRFC(this)">
                    ${optionsUsuarios}
                </select>
            </td>
            <td>
                <input type="text" class="form-control rfc-input" name="comprobacion[${rowIndex}][rfc]" required placeholder="RFC" style="text-transform: uppercase;" value="${data ? data.rfc : ''}" readonly>
            </td>
            <td>
                <input type="text" class="form-control input-importe" name="comprobacion[${rowIndex}][importe]" required placeholder="0.00" value="${data ? data.importe : ''}" onblur="calcularTotalComprobado()">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFilaComprobacion(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);

        // Inicializar select2
        if($.fn.select2) {
            $(row).find('.select2').select2({
                width: '100%'
            });
        }

        // Formato moneda
        const inputImporte = row.querySelector('.input-importe');
        if (inputImporte) {
            if (data && data.importe) {
                inputImporte.value = new Intl.NumberFormat('es-MX', {style: 'currency', currency: 'MXN'}).format(data.importe);
            }
            aplicarFormatoMoneda(inputImporte);
        }
    }

    function actualizarRFC(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const rfc = selectedOption.getAttribute('data-rfc');
        const row = selectElement.closest('tr');
        const rfcInput = row.querySelector('.rfc-input');
        if(rfcInput) {
            rfcInput.value = rfc ? rfc : '';
        }
    }

    function eliminarFilaComprobacion(btn) {
        btn.closest('tr').remove();
        calcularTotalComprobado();
    }

    function aplicarFormatoMoneda(input) {
        input.addEventListener('blur', function() {
            let val = this.value.replace(/[^0-9.]/g, '');
            if(val) {
                this.value = new Intl.NumberFormat('es-MX', {style: 'currency', currency: 'MXN'}).format(val);
            }
            calcularTotalComprobado();
        });
        input.addEventListener('focus', function() {
            this.value = this.value.replace(/[^0-9.]/g, '');
        });
    }

    function calcularTotalComprobado() {
        let total = 0;
        document.querySelectorAll('.input-importe').forEach(input => {
            let val = input.value.replace(/[^0-9.]/g, '');
            total += parseFloat(val) || 0;
        });
        document.getElementById('total_comprobado').innerText = new Intl.NumberFormat('es-MX', {style: 'currency', currency: 'MXN'}).format(total);
    }

    function updateFolioCompleto() {
        let area_prefix = document.getElementById('folio').options[document.getElementById('folio').selectedIndex].text;
        let cons = document.getElementById('no_consecutivo').value;
        let year = new Date().getFullYear();
        let completo = area_prefix + ' ' + cons + '/' + year;
        document.getElementById('folioCompleto').value = completo;
    }

    // Inicializar con una o más filas
    document.addEventListener('DOMContentLoaded', function() {
        if (comprobantesGuardados && comprobantesGuardados.length > 0) {
            comprobantesGuardados.forEach(c => agregarFilaComprobacion(c));
            setTimeout(calcularTotalComprobado, 200); // Wait for inputs to inject
        } else {
            agregarFilaComprobacion();
        }

        // Inicializar Folio Completo
        updateFolioCompleto();
        document.getElementById('no_consecutivo').addEventListener('input', updateFolioCompleto);
        
        // El select de folio usaba Select2, si cambia, debemos capturarlo
        $('#folio').on('change', function() {
            updateFolioCompleto();
        });

        const form = document.getElementById('form_comprobacion_gastos');
        if(form){
            form.addEventListener('submit', function(e){
                e.preventDefault();
                updateFolioCompleto(); // Ensure it's updated before submit
                if(typeof ini.inicio.guardarComprobacion === 'function'){
                    ini.inicio.guardarComprobacion();
                } else {
                    console.error('La función ini.inicio.guardarComprobacion no está definida');
                    alert('Error: Función de guardado no disponible.');
                }
            });
        }
    });
</script>
