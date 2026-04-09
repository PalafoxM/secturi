<?php
$prefijo = $prefijo ?? '';
$no_consecutivo = $no_consecutivo ?? '';
?>
<div class="page-wrapper" style="background-color: #f4f6f9;">
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Título de la página con estilo moderno -->
            <div class="row mb-4 mt-3">
                <div class="col-sm-12 d-flex justify-content-between align-items-center">
                    <h4 class="page-title mb-0" style="font-weight: 700; color: #2c3e50;">
                        <i class="fas fa-file-invoice-dollar text-primary mr-2"></i>Nueva Solicitud GRC
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 m-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);" class="text-muted">SUSI</a></li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Solicitud GRC</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 text-center">
                           
                            
                           
                        </div>
                        <div class="card-body px-4 pb-4 mt-n2">
                            <form id="form_solicitud_grc" enctype="multipart/form-data">
                                <input type="hidden" name="id_solicitud" value="<?= isset($solicitud) ? $solicitud->id_solicitud_grc : '' ?>">
                                
                            
                                
                                <h6 class="card-title text-uppercase font-weight-bold text-dark mb-3 mt-2" style="font-size: 0.95rem; letter-spacing: 1px;">
                                    DATOS GENERALES DE SOLICITUD
                                </h6>

                                    <div class="row justify-content-center mb-4">
                                    <div class="col-md-auto text-center" style="padding: 7px; border-radius: 5px;">
                                        <div class="d-flex align-items-center justify-content-center folio-grc-box" style="font-weight: bold; font-size: 1.1rem; color: #000;">
                                            <span class="mr-2">GRC</span>
                                            <select id="folio" class="select2 form-control font-weight-bold p-1 mr-1 d-inline-block" style="width: auto; min-width: 120px; height: 35px; border: 1px solid #ccc; background: transparent; color: #000;">
                                                <?php foreach($cat_area as $area): ?>
                                                    <option value="<?= $area->prefijo ?>" <?= $area->prefijo == $prefijo ? 'selected' : '' ?>><?= $area->prefijo ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="mx-1">/</span>
                                            <input type="text" id="no_consecutivo_input" autocomplete="off" class="form-control font-weight-bold text-center p-1 mx-1 d-inline-block" style="width: 70px; height: 35px; border: 1px solid #ccc; background: transparent; color: #000;" value="<?= $no_consecutivo ?>" placeholder="001">
                                            <span class="mx-1">/2026</span>
                                          
                                        </div>
                                        <input type="hidden" name="no_consecutivo" id="folioCompleto">
                                    </div>
                                </div>
                                
                                <!-- Primera fila: Cheque a favor y Cantidad -->
                                <div class="row">
                                    <div class="col-md-8 mb-4">
                                        <label for="cheque_favor" class="font-weight-bold text-dark">Cheque a favor de:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-user-tie text-muted"></i></span>
                                            </div>
                                            <select name="cheque_favor" id="cheque_favor" class="form-control select2 custom-select">
                                                <?php foreach ($usuario as $row) { ?>
                                                    <option value="<?php echo $row->id_usuario; ?>" <?= (isset($solicitud) && $solicitud->cheque_favor == $row->id_usuario) ? 'selected' : '' ?>><?php echo $row->nombre_completo; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="cantidad" class="font-weight-bold text-dark">Cantidad M.N.</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-dollar-sign text-muted"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="cantidad" name="cantidad" value="<?= isset($solicitud) ? $solicitud->cantidad : '' ?>" required placeholder="0.00" style="border-left: 0; box-shadow: none;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Segunda fila: Evento y Lugar -->
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="nombre_evento" class="font-weight-bold text-dark">Nombre del evento:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="nombre_evento" name="nombre_evento" value="<?= isset($solicitud) ? $solicitud->nombre_evento : '' ?>" required placeholder="Ej. Congreso Anual..." style="border-left: 0; box-shadow: none;">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="lugar" class="font-weight-bold text-dark">Lugar del evento:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="lugar" name="lugar" value="<?= isset($solicitud) ? $solicitud->lugar : '' ?>" required placeholder="Ciudad, Estado" style="border-left: 0; box-shadow: none;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Tercera fila: Fechas y Clave Presupuestaria -->
                                <div class="row">
                                    <div class="col-md-4 mb-4">
                                        <label for="fecha_incicio" class="font-weight-bold text-dark">Fecha Inicio:</label>
                                        <input type="date" class="form-control" id="fecha_incicio" name="fecha_incicio" value="<?= isset($solicitud) ? date('Y-m-d', strtotime($solicitud->fecha_inicio)) : '' ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="fecha_fin" class="font-weight-bold text-dark">Fecha Fin:</label>
                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= isset($solicitud) ? date('Y-m-d', strtotime($solicitud->fecha_fin)) : '' ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="clave" class="font-weight-bold text-dark">Clave Presupuestaria de Nómina:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-key text-muted"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="clave" name="clave" value="<?= isset($solicitud) ? $solicitud->clave : '' ?>" required placeholder="000-0000" style="border-left: 0; box-shadow: none;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Cuarta fila: Responsable -->
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <label for="nombre_resposable" class="font-weight-bold text-dark">Responsable de la Comprobación:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-signature text-muted"></i></span>
                                            </div>
                                            <select name="nombre_resposable" id="nombre_resposable" class="form-control select2 custom-select">
                                                <?php foreach ($usuario as $row) { ?>
                                                    <option value="<?php echo $row->id_usuario; ?>" <?= (isset($solicitud) && $solicitud->nombre_responsable == $row->id_usuario) ? 'selected' : '' ?>><?php echo $row->nombre_completo; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr class="mt-2 mb-4">

                                <!-- Sección: Datos Presupuestarios -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="font-weight-bold text-dark mb-0">Datos Presupuestarios</h5>
                                        <p class="text-muted small mb-0">Agregue los conceptos y claves relacionadas</p>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm" onclick="agregarFila()">
                                        <i class="fas fa-plus mr-1"></i> Agregar Concepto
                                    </button>
                                </div>

                                <div class="table-responsive rounded shadow-sm border mb-4">
                                    <table class="table table-hover mb-0" id="tabla_detalles">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="border-top-0 text-uppercase text-muted" style="font-size: 12px; font-weight: 600;">Concepto / Fondo</th>
                                                <th class="border-top-0 text-uppercase text-muted text-center" style="font-size: 12px; font-weight: 600; width: 200px;">Importe</th>
                                                <th class="border-top-0 text-uppercase text-muted" style="font-size: 12px; font-weight: 600; width: 30%;">Clave Presupuestaria</th>
                                                <th class="border-top-0 text-center" style="width: 50px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Las filas se agregarán dinámicamente -->
                                        </tbody>
                                        <!-- tfoot eliminado porque pusimos el botón arriba -->
                                    </table>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12 text-right">
                                        <button class="btn btn-success btn-lg rounded-pill px-5 shadow" id="btnGuardarSolicitud">
                                            <i class="fas fa-save mr-2"></i>Guardar Solicitud
                                        </button>
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

<style>
/* Ajustes sutiles para inputs y cards */
.custom-select {
    height: calc(1.5em + .75rem + 2px);
}
.select2-container--default .select2-selection--single {
    border: 1px solid #ced4da;
    height: calc(1.5em + .75rem + 2px);
    border-radius: 0 0.25rem 0.25rem 0;
    border-left: 0;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: calc(1.5em + .75rem);
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: calc(1.5em + .75rem);
}
.folio-grc-box {
    flex-wrap: nowrap;
    gap: 6px;
}
.folio-grc-box #folio {
    min-width: 120px;
}
.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
}
</style>


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

<script src="<?= base_url() ?>assets/pages/jquery.forms-advanced.js"></script>

<script>
    // Serializar cat_partida para uso en JS
    const catPartida = <?php echo json_encode(isset($cat_partida) ? $cat_partida : []); ?>;
    const catProyecto = <?php echo json_encode(isset($cat_proyecto) ? $cat_proyecto : []); ?>;
    const detallesExistentes = <?php echo json_encode(isset($detalles) ? $detalles : []); ?>;

    function agregarFila(data = null) {
        const tbody = document.querySelector('#tabla_detalles tbody');
        const rowIndex = Date.now() + Math.floor(Math.random() * 1000); // Unique ID

        // Construir opciones del select
        let options = '<option value="">Seleccione una opción</option>';
        let options2 = '<option value="">Seleccione una opción</option>';
        
        catPartida.forEach(item => {
             let texto = `${item.cuenta_clabe || item.cuenta_cable || ''} - ${item.nombre_fondo || item.dsc_partida || ''}`;
             let selected = (data && data.id_partida == item.id_partida) ? 'selected' : '';
             options += `<option value="${item.id_partida}" ${selected}>${texto}</option>`;
        });
        
        catProyecto.forEach(item => {
             let selected = (data && data.id_proyecto == item.id_proyecto) ? 'selected' : '';
             options2 += `<option value="${item.id_proyecto}" ${selected}>${item.proyecto}</option>`;
        });
        
        // Importe valor
        let importeValue = '';
        if (data && data.importe) {
             // Formatear si viene de BD
             importeValue = new Intl.NumberFormat('es-MX', {
                    style: 'currency', 
                    currency: 'MXN'
                }).format(data.importe); 
        }

        const row = document.createElement('tr');
        row.className = "border-bottom";
        row.innerHTML = `
            <td class="align-middle pl-0 py-2">
                <select class="form-control select2 custom-select border-0 bg-light" name="detalles[${rowIndex}][partida]" required>
                    ${options}
                </select>
            </td>
            <td class="align-middle py-2">
                <div class="input-group input-group-sm mb-0">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-0 bg-light text-muted"><i class="fas fa-dollar-sign"></i></span>
                    </div>
                    <input type="text" class="form-control border-0 bg-light text-right" name="detalles[${rowIndex}][importe]" placeholder="0.00" value="${importeValue}" style="min-width: 120px;" required>
                </div>
            </td>
            <td class="align-middle py-2">
                <select class="form-control select2 custom-select border-0 bg-light" name="detalles[${rowIndex}][proyecto]" required>
                    ${options2}
                </select>
            </td>
            <td class="align-middle text-center pr-0 py-2" style="width: 50px;">
                <button type="button" class="btn btn-outline-danger btn-sm rounded-circle" onclick="eliminarFila(this)" title="Eliminar Concepto">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
        
        // Inicializar select2 si está disponible para los nuevos elementos
        if($.fn.select2) {
            $(row).find('.select2').select2({
                width: '100%'
            });
        }

        // Aplicar formato moneda al nuevo input de importe
        const inputImporte = row.querySelector(`input[name="detalles[${rowIndex}][importe]"]`);
        if (inputImporte) {
            aplicarFormatoMoneda(inputImporte);
        }
    }

    function eliminarFila(btn) {
        const row = btn.closest('tr');
        row.remove();
    }

    // Agregar una fila inicial
    document.addEventListener('DOMContentLoaded', function() {
        if (detallesExistentes && detallesExistentes.length > 0) {
            detallesExistentes.forEach(detalle => {
                agregarFila(detalle);
            });
        } else {
            agregarFila();
        }

        // Formato moneda para cantidad
        const inputCantidad = document.getElementById('cantidad');
        if(inputCantidad){
            aplicarFormatoMoneda(inputCantidad);
        }

        // Validación de anticipación para fechas
        let lastAlertedStart = null;

        function verificarDiasAnticipacion() {
            let dpInicio = document.getElementById('fecha_incicio');
            let dpFin = document.getElementById('fecha_fin');
            
            if (!dpInicio || !dpFin) return;
            
            let fInicioStr = dpInicio.value;
            let fFinStr = dpFin.value;
            
            // Wait until both fields have a date selected
            if (!fInicioStr || !fFinStr) return;
            
            // If we already warned about this exact start date, don't spam the user
            if (lastAlertedStart === fInicioStr) return;

            let hoy = new Date();
            hoy.setHours(0,0,0,0);
            
            let partes = fInicioStr.split('-');
            let fInput = new Date(partes[0], partes[1] - 1, partes[2]);
            fInput.setHours(0,0,0,0);
            
            let diasHabiles = 0;
            if (fInput >= hoy) {
                let tempDate = new Date(hoy.getTime());
                while (tempDate < fInput) {
                    tempDate.setDate(tempDate.getDate() + 1);
                    if (tempDate.getDay() !== 0 && tempDate.getDay() !== 6) {
                        diasHabiles++;
                    }
                }
            } else {
                diasHabiles = -1; // Force the alert for past dates
            }

            if (diasHabiles < 4) {
                lastAlertedStart = fInicioStr; // Register this start date as warned
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Aviso',
                        text: 'Artículo 50 I. Deberán solicitarse en un término de cuatro días hábiles de anticipación a celebrarse el evento o comisión correspondiente. Con excepción de aquellas comisiones y eventos urgentes, siempre y cuando estén plenamente justificados.',
                        icon: 'info',
                        confirmButtonText: 'Enterado'
                    });
                } else {
                    alert('Artículo 50 I. Deberán solicitarse en un término de cuatro días hábiles de anticipación a celebrarse el evento o comisión correspondiente. Con excepción de aquellas comisiones y eventos urgentes, siempre y cuando estén plenamente justificados.');
                }
            } else {
                lastAlertedStart = null; // Reset if they pick a valid date
            }
        }
        
        let dpInicio = document.getElementById('fecha_incicio');
        if(dpInicio) dpInicio.addEventListener('change', verificarDiasAnticipacion);
        let dpFin = document.getElementById('fecha_fin');
        if(dpFin) dpFin.addEventListener('change', verificarDiasAnticipacion);

        // Evento submit del formulario
        const form = document.getElementById('form_solicitud_grc');
        if(form){
            form.addEventListener('submit', function(e){
                e.preventDefault();

                // Validar que Cantidad M.N. sea igual a la suma de los Importes
                let cantidadStr = document.getElementById('cantidad').value.replace(/[^0-9.-]/g, '');
                let cantidadTotal = parseFloat(cantidadStr) || 0;
                
                let sumaImportes = 0;
                const importeInputs = document.querySelectorAll('input[name^="detalles"][name$="[importe]"]');
                importeInputs.forEach(input => {
                    let valStr = input.value.replace(/[^0-9.-]/g, '');
                    sumaImportes += parseFloat(valStr) || 0;
                });
                
                // Redondear a 2 decimales para evitar problemas de precisión en JS
                cantidadTotal = Math.round(cantidadTotal * 100) / 100;
                sumaImportes = Math.round(sumaImportes * 100) / 100;

                if (cantidadTotal !== sumaImportes) {
                    let mensaje = 'La Cantidad M.N. ($' + cantidadTotal.toFixed(2) + ') debe ser igual a la suma de los Importes ($' + sumaImportes.toFixed(2) + '). Revise los datos e intente nuevamente.';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Atención', mensaje, 'warning');
                    } else {
                        alert(mensaje);
                    }
                    return; // Detener guardado
                }

                if(typeof ini.inicio.guardarSolicitud === 'function'){
                    ini.inicio.guardarSolicitud();
                } else {
                    console.error('La función ini.inicio.guardarSolicitud no está definida');
                    // Fallback or alert?
                }
            });
        }
    });

    function aplicarFormatoMoneda(inputElement) {
        inputElement.addEventListener('blur', function() {
            let val = this.value.replace(/[^0-9.]/g, '');
            if(val) {
                this.value = new Intl.NumberFormat('es-MX', {
                    style: 'currency', 
                    currency: 'MXN'
                }).format(val); 
            }
        });

        inputElement.addEventListener('focus', function() {
            this.value = this.value.replace(/[^0-9.]/g, '');
        });
        
        inputElement.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9.]/g, '');
        });
    }

    // Lógica para actualizar el Folio Oculto
    function updateFolioGrc() {
        var prefix = $('#folio').val();
        var consecutivo = $('#no_consecutivo_input').val();
        var anio = '2026';
        
        if(prefix && consecutivo && anio) {
            var txtVal =  prefix + consecutivo + '/' + anio;
            $('#folioCompleto').val(txtVal);
        }
    }

    $(document).ready(function() {
        $('#folio').on('change', updateFolioGrc);
        $('#no_consecutivo_input').on('input change', updateFolioGrc);
        updateFolioGrc();
    });
</script>
