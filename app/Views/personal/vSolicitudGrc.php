<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item active">Solicitud GRC</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Solicitud GRC</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body" style="padding: 2rem 3rem;">
                            <form id="form_solicitud_grc" enctype="multipart/form-data">
                                <input type="hidden" name="id_solicitud" value="<?= isset($solicitud) ? $solicitud->id_solicitud_grc : '' ?>">
                                
                                <div class="mb-4">
                                    <p style="font-size: 16px; margin-bottom: 5px;">Solicito a usted, la autorización para que se expida cheque a favor de:</p>
                                    <div class="text-center" style="border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 20px;">
                                        <select name="cheque_favor" id="cheque_favor" class="form-control select2 font-weight-bold" style="width: 50%; display: inline-block; font-size: 16px;">
                                            <?php foreach ($usuario as $row) { ?>
                                                <option value="<?php echo $row->id_usuario; ?>" <?= (isset($solicitud) && $solicitud->cheque_favor == $row->id_usuario) ? 'selected' : '' ?>><?php echo $row->nombre_completo; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row align-items-center mb-3">
                                    <label for="cantidad" class="col-sm-2 col-form-label" style="font-size: 16px;">Por la cantidad de: </label>
                                    <div class="col-sm-10" style="border-bottom: 1px solid #000;">
                                        <input type="text" class="form-control border-0 shadow-none px-0" id="cantidad" name="cantidad" value="<?= isset($solicitud) ? number_format($solicitud->cantidad, 2) : '' ?>" required style="font-size: 16px; background-color: transparent;">
                                    </div>
                                </div>

                                <div class="form-group row align-items-center mb-3">
                                    <label for="nombre_evento" class="col-sm-2 col-form-label" style="font-size: 16px;">Nombre del evento: </label>
                                    <div class="col-sm-10" style="border-bottom: 1px solid #000;">
                                        <input type="text" class="form-control border-0 shadow-none px-0" id="nombre_evento" name="nombre_evento" value="<?= isset($solicitud) ? $solicitud->nombre_evento : '' ?>" required style="font-size: 16px; background-color: transparent;">
                                    </div>
                                </div>

                                <div class="form-group row align-items-center mb-3">
                                    <label for="lugar" class="col-sm-1 col-form-label" style="font-size: 16px;">Lugar: </label>
                                    <div class="col-sm-11" style="border-bottom: 1px solid #000;">
                                        <input type="text" class="form-control border-0 shadow-none px-0" id="lugar" name="lugar" value="<?= isset($solicitud) ? $solicitud->lugar : '' ?>" required style="font-size: 16px; background-color: transparent;">
                                    </div>
                                </div>

                                <div class="form-group row align-items-center mb-4">
                                    <label class="col-auto col-form-label" style="font-size: 16px;">Duración: Del </label>
                                    <div class="col-auto" style="border-bottom: 1px solid #000; padding-left: 0; padding-right: 0;">
                                        <input type="date" class="form-control border-0 shadow-none px-2" id="fecha_incicio" name="fecha_incicio" value="<?= isset($solicitud) ? date('Y-m-d', strtotime($solicitud->fecha_inicio)) : '' ?>" required style="font-size: 16px; background-color: transparent; width: auto; display: inline-block;">
                                    </div>
                                    <label class="col-auto col-form-label" style="font-size: 16px;"> al </label>
                                    <div class="col-auto" style="border-bottom: 1px solid #000; padding-left: 0; padding-right: 0;">
                                        <input type="date" class="form-control border-0 shadow-none px-2" id="fecha_fin" name="fecha_fin" value="<?= isset($solicitud) ? date('Y-m-d', strtotime($solicitud->fecha_fin)) : '' ?>" required style="font-size: 16px; background-color: transparent; width: auto; display: inline-block;">
                                    </div>
                                </div>

                                <div class="form-group row align-items-center mb-3 mt-4">
                                    <label for="clave" class="col-sm-6 col-form-label" style="font-size: 16px;">Clave Presupuestaria donde se efectúa el pago de nómina: </label>
                                    <div class="col-sm-6" style="border-bottom: 1px solid #000;">
                                        <input type="text" class="form-control border-0 shadow-none px-0" id="clave" name="clave" value="<?= isset($solicitud) ? $solicitud->clave : '' ?>" required style="font-size: 16px; background-color: transparent;">
                                    </div>
                                </div>

                                <div class="form-group row align-items-center mb-5">
                                    <label for="nombre_resposable" class="col-sm-5 col-form-label" style="font-size: 16px;">Nombre del Responsable de la Comprobación: </label>
                                    <div class="col-sm-7" style="border-bottom: 1px solid #000;">
                                        <select name="nombre_resposable" id="nombre_resposable" class="form-control select2 border-0 shadow-none px-0" style="font-size: 16px; background-color: transparent;">
                                            <?php foreach ($usuario as $row) { ?>
                                                <option value="<?php echo $row->id_usuario; ?>" <?= (isset($solicitud) && $solicitud->nombre_responsable == $row->id_usuario) ? 'selected' : '' ?>><?php echo $row->nombre_completo; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="text-center mt-5 mb-5">
                                    <div style="width: 400px; margin: 0 auto; border-top: 1px solid #000; padding-top: 5px;">
                                        <span style="font-size: 14px;">Firma de la persona Responsable de la Comprobación</span>
                                    </div>
                                </div>

                                <div class="text-center mb-4">
                                    <h5 class="font-weight-bold" style="font-size: 14px;">DATOS PRESUPUESTARIOS</h5>
                                    <h6 class="font-weight-bold" style="font-size: 14px;">PRESUPUESTO</h6>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-borderless" id="tabla_detalles">
                                        <thead style="border-bottom: 1px solid #dee2e6;">
                                            <tr style="font-size: 12px; color: #666; text-transform: uppercase;">
                                                <th class="pl-0">Concepto</th>
                                                <th class="text-center">Importe</th>
                                                <th>Clave presupuestaria</th>
                                                <th style="width: 80px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Las filas se agregarán dinámicamente -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-right pb-0 pr-0 mt-2">
                                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="agregarFila()">
                                                        <i class="fas fa-plus"></i> Agregar Fila
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-12 text-right">
                                        <button class="btn btn-primary" id="btnGuardarSolicitud" >Guardar Solicitud</button>
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
        row.innerHTML = `
            <td class="pl-0" style="padding-top: 5px; padding-bottom: 5px;">
                <select class="form-control select2" name="detalles[${rowIndex}][partida]" required>
                    ${options}
                </select>
            </td>
            <td style="padding-top: 5px; padding-bottom: 5px;">
                <input type="text" class="form-control text-right" name="detalles[${rowIndex}][importe]" placeholder="0.00" value="${importeValue}" required>
            </td>
            <td style="padding-top: 5px; padding-bottom: 5px;">
                <select class="form-control select2" name="detalles[${rowIndex}][proyecto]" required>
                    ${options2}
                </select>
            </td>
            <td class="text-center pr-0" style="padding-top: 5px; padding-bottom: 5px;">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarFila(this)">
                    <i class="fas fa-trash"></i>
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

        // Evento submit del formulario
        const form = document.getElementById('form_solicitud_grc');
        if(form){
            form.addEventListener('submit', function(e){
                e.preventDefault();
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
</script>
