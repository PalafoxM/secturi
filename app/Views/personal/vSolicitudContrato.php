<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Jurídico</a></li>
                                <li class="breadcrumb-item active">Solicitud de Contrato</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Solicitud de Elaboración de Contrato</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h4 class="mt-0">DIRECCIÓN GENERAL JURÍDICA (DGJ-1)</h4>
                                <h5>SOLICITUD DE ELABORACIÓN DE CONTRATO</h5>
                            </div>
                            
                            <form id="form_solicitud_contrato" enctype="multipart/form-data">
                                <input type="hidden" name="id_solicitud_contrato" value="<?= isset($solicitud) ? $solicitud->id_solicitud_contrato : '' ?>">
                                
                                <!-- SECCION 1: INFORMACIÓN DEL ÁREA SOLICITANTE -->
                                <h5 class="bg-primary text-white p-2">INFORMACIÓN DEL ÁREA SOLICITANTE</h5>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre y cargo del Responsable del Proyecto:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2" name="responsable_proyecto" required>
                                            <option value="">Seleccione una opción</option>
                                            <?php foreach ($direccion as $u): ?>
                                                <option value="<?= $u->id_usuario ?>" <?= (isset($solicitud) && $solicitud->responsable_proyecto == $u->id_usuario) ? 'selected' : '' ?>>
                                                    <?= $u->nombre_completo .' - '. $u->dsc_puesto ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre y cargo del Responsable de Seguimiento:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2" name="responsable_seguimiento" required>
                                            <option value="">Seleccione una opción</option>
                                            <?php foreach ($usuario as $u): ?>
                                                <option value="<?= $u->id_usuario ?>" <?= (isset($solicitud) && $solicitud->responsable_seguimiento == $u->id_usuario) ? 'selected' : '' ?>>
                                                     <?= $u->nombre_completo .' - '. $u->dsc_puesto ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre y cargo del Enlace de Comunicaciones:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2" name="enlace_comunicaciones">
                                            <option value="">Seleccione una opción</option>
                                            <?php foreach ($usuario as $u): ?>
                                                <option value="<?= $u->id_usuario ?>" <?= (isset($solicitud) && $solicitud->enlace_comunicaciones == $u->id_usuario) ? 'selected' : '' ?>>
                                                    <?= $u->nombre_completo .' - '. $u->dsc_puesto ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- SECCION 2: INFORMACIÓN PRESUPUESTAL -->
                                <h5 class="bg-primary text-white p-2 mt-4">INFORMACIÓN PRESUPUESTAL</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Proyecto</th>
                                                <th>Partida</th>
                                                <th>Clave estandarizada</th>
                                                <th>Suficiencia Presupuestal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select class="form-control select2" name="proyecto">
                                                        <option value="">Seleccione una opción</option>
                                                        <?php foreach ($cat_proyecto as $p): ?>
                                                            <option value="<?= $p->id_proyecto ?>" <?= (isset($solicitud) && $solicitud->proyecto == $p->id_proyecto) ? 'selected' : '' ?>>
                                                                <?= $p->proyecto ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control select2" name="partida">
                                                        <option value="">Seleccione una opción</option>
                                                        <?php foreach ($cat_partida as $p): ?>
                                                            <option value="<?= $p->id_partida ?>" <?= (isset($solicitud) && $solicitud->partida == $p->id_partida) ? 'selected' : '' ?>>
                                                                <?= $p->cuenta_cable ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="clave_estandarizada" value="<?= isset($solicitud) ? $solicitud->clave_estandarizada : '' ?>"></td>
                                                <td>
                                                    <p class="small text-muted mb-0">El proyecto cuenta con la suficiencia presupuestal para la contratación de los servicios requeridos en la presente solicitud. Se anexa captura de pantalla Sistema SAP/R3</p>
                                                    <input type="file" class="form-control-file mt-2" name="archivo_suficiencia">
                                                    <?php if(isset($solicitud) && $solicitud->archivo_suficiencia): ?>
                                                        <a href="<?= base_url('assets/uploads/contratos/'.$solicitud->archivo_suficiencia) ?>" target="_blank" class="d-block mt-2">Ver archivo actual</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Monto Total del Contrato (con número y letra):</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="monto_total" name="monto_total" value="<?= isset($solicitud) ? $solicitud->monto_total : '' ?>" required>
                                        <input type="text" class="form-control mt-2" id="monto_letra" readonly placeholder="Monto en letra">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Tipo y monto de Garantía (con número y letra):</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="garantia">
                                            <option value="">Seleccione una opción</option>
                                            <option value="CHEQUE" <?= (isset($solicitud) && $solicitud->garantia == 'CHEQUE') ? 'selected' : '' ?>>CHEQUE</option>
                                            <option value="PAGARE" <?= (isset($solicitud) && $solicitud->garantia == 'PAGARE') ? 'selected' : '' ?>>PAGARE</option>
                                            <option value="FIANZA" <?= (isset($solicitud) && $solicitud->garantia == 'FIANZA') ? 'selected' : '' ?>>FIANZA</option>
                                        </select>
                                        <input type="text" class="form-control mt-2" name="monto_garantia" id="monto_garantia" value="<?= isset($solicitud) ? $solicitud->monto_total : '' ?>" readonly placeholder="112% del monto total">
                                    </div>
                                </div>

                                <!-- SECCION 3: DESCRIPCIÓN DEL SERVICIO -->
                                <h5 class="bg-primary text-white p-2 mt-4">DESCRIPCIÓN DEL SERVICIO A CONTRATAR O BIENES A ADQUIRIR</h5>
                                <div class="form-group">
                                    <label>Objeto del Contrato:</label>
                                    <textarea class="form-control" name="objeto_contrato" rows="4" required><?= isset($solicitud) ? $solicitud->objeto_contrato : '' ?></textarea>
                                </div>
                                <h6 class="mt-3">Vigencia y Pago del Contrato</h6>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Fecha de inicio:</label>
                                        <input type="date" class="form-control" name="fecha_inicio" value="<?= isset($solicitud) ? date('Y-m-d', strtotime($solicitud->fecha_inicio)) : '' ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Fecha de término:</label>
                                        <input type="date" class="form-control" name="fecha_termino" value="<?= isset($solicitud) ? date('Y-m-d', strtotime($solicitud->fecha_termino)) : '' ?>" required>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="tabla_pagos">
                                        <thead>
                                            <tr>
                                                <th>Pagos</th>
                                                <th>Monto TOTAL</th>
                                                <th>Fecha</th>
                                                <th>Entregable y contenido</th>
                                                <th style="width:50px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Dynamic rows -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-right">
                                                    <button type="button" class="btn btn-secondary btn-sm" onclick="agregarPago()">+ Agregar Pago</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- SECCION 4: INFORMACIÓN DEL PROVEEDOR -->
                                <h5 class="bg-primary text-white p-2 mt-4">INFORMACIÓN DEL PROVEEDOR</h5>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre/Razón Social:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_nombre" value="<?= isset($solicitud) ? $solicitud->proveedor_nombre : '' ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Domicilio fiscal:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_domicilio" value="<?= isset($solicitud) ? $solicitud->proveedor_domicilio : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">RFC:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_rfc" value="<?= isset($solicitud) ? $solicitud->proveedor_rfc : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Cédula de Registro (Padrón de Proveedores):</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_cedula" value="<?= isset($solicitud) ? $solicitud->proveedor_cedula : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre del Representante Legal (persona moral):</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_representante" value="<?= isset($solicitud) ? $solicitud->proveedor_representante : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Responsable de Seguimiento (correo electrónico):</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="proveedor_seguimiento" value="<?= isset($solicitud) ? $solicitud->proveedor_seguimiento : '' ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="email" class="form-control" name="proveedor_correo" value="<?= isset($solicitud) ? $solicitud->proveedor_correo : '' ?>">
                                    </div>
                                </div>

                                <!-- SECCION 5: DOCUMENTOS Y ANEXOS -->
                          

                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-success btn-lg"><i class="mdi mdi-content-save"></i> Guardar Solicitud</button>
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

       <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/jquery-ui.min.css" rel="stylesheet">
        <link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
        <!-- jQuery  -->
        <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
        <script src="<?= base_url() ?>assets/js/jquery-ui.min.js"></script>
        <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url() ?>assets/js/metismenu.min.js"></script>
        <script src="<?= base_url() ?>assets/js/waves.js"></script>
        <script src="<?= base_url() ?>assets/js/feather.min.js"></script>
        <script src="<?= base_url() ?>assets/js/jquery.slimscroll.min.js"></script>
        <script src="<?= base_url() ?>plugins/select2/select2.min.js"></script>

<script>
    function numeroALetras(amount) {
        if (amount == 0) return "CERO PESOS 00/100 M.N.";
        var pesos = Math.floor(amount);
        var centavos = Math.round((amount - pesos) * 100);
        var letras = "";

        if (pesos == 0) letras = "CERO";
        else if (pesos == 1) letras = "UN";
        else letras = convertirGrupo(pesos);

        return (letras + " PESOS " + (centavos < 10 ? "0" : "") + centavos + "/100 M.N.").toUpperCase();
    }

    function convertirGrupo(n) {
        var output = "";
        if (n == 100) output = "CIEN";
        else if (n > 100 && n < 1000) output = centenas(n);
        else if (n >= 1000 && n < 1000000) {
            var miles = Math.floor(n / 1000);
            var resto = n % 1000;
            output = (miles == 1 ? "UN" : convertirGrupo(miles)) + " MIL" + (resto > 0 ? " " + convertirGrupo(resto) : "");
        } else if (n >= 1000000) {
            var millones = Math.floor(n / 1000000);
            var resto = n % 1000000;
            output = (millones == 1 ? "UN MILLON" : convertirGrupo(millones) + " MILLONES") + (resto > 0 ? " " + convertirGrupo(resto) : "");
        } else {
            output = centenas(n);
        }
        return output;
    }

    function centenas(n) {
        var centenas = Math.floor(n / 100);
        var decenas = n % 100;
        var output = "";
        
        switch (centenas) {
            case 1: output = (decenas > 0 ? "CIENTO" : "CIEN"); break;
            case 2: output = "DOSCIENTOS"; break;
            case 3: output = "TRESCIENTOS"; break;
            case 4: output = "CUATROCIENTOS"; break;
            case 5: output = "QUINIENTOS"; break;
            case 6: output = "SEISCIENTOS"; break;
            case 7: output = "SETECIENTOS"; break;
            case 8: output = "OCHOCIENTOS"; break;
            case 9: output = "NOVECIENTOS"; break;
        }
        
        if (decenas > 0) output += (output ? " " : "") + dec(decenas);
        return output;
    }

    function dec(n) {
        if (n < 10) return unidades(n);
        var output = "";
        if (n >= 10 && n <= 29) {
            switch (n) {
                case 10: output = "DIEZ"; break;
                case 11: output = "ONCE"; break;
                case 12: output = "DOCE"; break;
                case 13: output = "TRECE"; break;
                case 14: output = "CATORCE"; break;
                case 15: output = "QUINCE"; break;
                case 16: output = "DIECISEIS"; break;
                case 17: output = "DIECISIETE"; break;
                case 18: output = "DIECIOCHO"; break;
                case 19: output = "DIECINUEVE"; break;
                case 20: output = "VEINTE"; break;
                case 21: output = "VEINTIUNO"; break;
                case 22: output = "VEINTIDOS"; break;
                case 23: output = "VEINTITRES"; break;
                case 24: output = "VEINTICUATRO"; break;
                case 25: output = "VEINTICINCO"; break;
                case 26: output = "VEINTISEIS"; break;
                case 27: output = "VEINTISIETE"; break;
                case 28: output = "VEINTIOCHO"; break;
                case 29: output = "VEINTINUEVE"; break;
            }
        } else {
             var d = Math.floor(n / 10);
             var u = n % 10;
             switch(d) {
                 case 3: output = "TREINTA"; break;
                 case 4: output = "CUARENTA"; break;
                 case 5: output = "CINCUENTA"; break;
                 case 6: output = "SESENTA"; break;
                 case 7: output = "SETENTA"; break;
                 case 8: output = "OCHENTA"; break;
                 case 9: output = "NOVENTA"; break;
             }
             if (u > 0) output += " Y " + unidades(u);
        }
        return output;
    }

    function unidades(n) {
        switch(n) {
            case 1: return "UN";
            case 2: return "DOS";
            case 3: return "TRES";
            case 4: return "CUATRO";
            case 5: return "CINCO";
            case 6: return "SEIS";
            case 7: return "SIETE";
            case 8: return "OCHO";
            case 9: return "NUEVE";
        }
        return "";
    }

    $(document).ready(function() {
        $('#monto_total').on('input', function() {
            var valor = $(this).val();
            // Validación de número
            if (isNaN(valor) || valor.trim() === '') {
                 if(valor.trim() !== '') {
                      $('#monto_letra').val('NUMERO NO LEGIBLE');
                 } else {
                      $('#monto_letra').val('');
                 }
            } else {
                $('#monto_letra').val(numeroALetras(parseFloat(valor)));
                
                // Calcular 12%
                var monto = parseFloat(valor);
                var garantia = monto * 0.12;
                var totalMonto = garantia + monto;
                // Formatear a 2 decimales
                $('#monto_garantia').val(totalMonto.toFixed(2));
            }
        });
        
        // Trigger inicial si ya hay valor
        if($('#monto_total').val()) {
            $('#monto_total').trigger('input');
        }
    });

    const pagosExistentes = <?= isset($pagos) ? json_encode($pagos) : '[]' ?>;

    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    function agregarPago(data = null) {
        const tbody = document.querySelector('#tabla_pagos tbody');
        const count = tbody.children.length + 1;
        const row = document.createElement('tr');
        
        let numero = data ? data.numero_pago : `${count}º Pago`;
        let monto = data ? data.monto : '';
        let entregable = data ? data.entregable : '';
        
        // Determinar mes seleccionado
        let mesSeleccionado = '';
        if (data && data.fecha) {
            // Si es formato fecha YYYY-MM-DD
            if (data.fecha.match(/^\d{4}-\d{2}-\d{2}/)) {
                let parts = data.fecha.split('-'); // [YYYY, MM, DD]
                if(parts.length >= 2) {
                    let mesIndex = parseInt(parts[1]) - 1;
                    if(mesIndex >= 0 && mesIndex < 12) {
                        mesSeleccionado = meses[mesIndex];
                    }
                }
            } else {
                // Si ya es texto
                mesSeleccionado = data.fecha;
            }
        }

        let options = '<option value="">Seleccione mes</option>';
        meses.forEach(mes => {
            let selected = (mes === mesSeleccionado) ? 'selected' : '';
            options += `<option value="${mes}" ${selected}>${mes}</option>`;
        });

        row.innerHTML = `
            <td><input type="text" class="form-control" name="pagos[${count}][numero]" value="${numero}" placeholder="Ej. 1er Pago"></td>
            <td><input type="text" class="form-control" name="pagos[${count}][monto]" value="${monto}" placeholder="$"></td>
            <td>
                <select class="form-control" name="pagos[${count}][fecha]">
                    ${options}
                </select>
            </td>
            <td><input type="text" class="form-control" name="pagos[${count}][entregable]" value="${entregable}" placeholder="Descripción"></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="mdi mdi-trash-can"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    }
    
    // Validate payment sum
    function validarMontoPagos(mostrarError = false) {
        var montoTotal = parseFloat($('#monto_total').val()) || 0;
        var sumaPagos = 0;

        $('#tabla_pagos tbody input[name*="[monto]"]').each(function() {
            sumaPagos += parseFloat($(this).val()) || 0;
        });

        if (sumaPagos > montoTotal) {
            if (mostrarError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El monto total de los pagos (' + sumaPagos.toFixed(2) + ') no debe ser mayor al Monto Total del Contrato (' + montoTotal.toFixed(2) + ').',
                    showConfirmButton: true
                });
            } else {
                 // Toast or subtle indicator
                 Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'La suma de pagos excede el total',
                    showConfirmButton: false,
                    timer: 3500
                });
            }
            return false;
        }
        return true;
    }

    // Add initial row
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('.select2').select2();

        // Real-time validation
        $(document).on('input', '#monto_total, #tabla_pagos input[name*="[monto]"]', function() {
            validarMontoPagos(false);
        });

        if (pagosExistentes && pagosExistentes.length > 0) {
            pagosExistentes.forEach(pago => {
                agregarPago(pago);
            });
        } else {
            agregarPago();
        }
        
        $('#form_solicitud_contrato').on('submit', function(e) {
            e.preventDefault();

            if (!validarMontoPagos(true)) {
                return;
            }
            
            var formData = new FormData(this);
            var btnSubmit = $(this).find('button[type="submit"]');
            
            btnSubmit.prop('disabled', true);
            btnSubmit.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            $.ajax({
                url: '<?= base_url("index.php/Principal/guardarSolicitudContrato") ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(data) {
                    if (!data.error) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Solicitud guardada correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '<?= base_url("index.php/Principal/ListaSolicitudContrato") ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error: ' + data.respuesta,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        btnSubmit.prop('disabled', false);
                        btnSubmit.html('<i class="mdi mdi-content-save"></i> Guardar Solicitud');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Ocurrió un error al procesar la solicitud');
                    btnSubmit.prop('disabled', false);
                    btnSubmit.html('<i class="mdi mdi-content-save"></i> Guardar Solicitud');
                }
            });
        });
    });
</script>
