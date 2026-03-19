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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Administración</a></li>
                                <li class="breadcrumb-item active">Solicitud de Adquisiciones</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Solicitud de Adquisiciones</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h4 class="mt-0">DIRECCIÓN GENERAL DE ADMINISTRACIÓN</h4>
                                <h5>SOLICITUD DE ADQUISICIONES</h5>
                            </div>
                            
                            <form id="form_solicitud_adquisiciones" enctype="multipart/form-data">
                                <input type="hidden" name="id_solicitud_adquisiciones" value="<?= isset($solicitud) ? $solicitud->id_solicitud_adquisiciones : '' ?>">
                                
                                <!-- SECCION 1: INFORMACIÓN DEL ÁREA SOLICITANTE -->
                                <h5 class="bg-primary text-white p-2">INFORMACIÓN DEL ÁREA SOLICITANTE</h5>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre y cargo del Responsable del Proyecto:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2" name="responsable_proyecto" required>
                                            <option value="">Seleccione una opción</option>
                                            <?php if(isset($direccion)): foreach ($direccion as $u): ?>
                                                <option value="<?= $u->id_usuario ?>" <?= (isset($solicitud) && $solicitud->responsable_proyecto == $u->id_usuario) ? 'selected' : '' ?>>
                                                    <?= $u->nombre_completo .' - '. $u->dsc_puesto ?>
                                                </option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre y cargo del Responsable de Seguimiento:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2" name="responsable_seguimiento" required>
                                            <option value="">Seleccione una opción</option>
                                            <?php if(isset($usuario)): foreach ($usuario as $u): ?>
                                                <option value="<?= $u->id_usuario ?>" <?= (isset($solicitud) && $solicitud->responsable_seguimiento == $u->id_usuario) ? 'selected' : '' ?>>
                                                     <?= $u->nombre_completo .' - '. $u->dsc_puesto ?>
                                                </option>
                                            <?php endforeach; endif; ?>
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
                                                        <?php if(isset($cat_proyecto)): foreach ($cat_proyecto as $p): ?>
                                                            <option value="<?= $p->id_proyecto ?>" <?= (isset($solicitud) && $solicitud->proyecto == $p->id_proyecto) ? 'selected' : '' ?>>
                                                                <?= $p->proyecto ?>
                                                            </option>
                                                        <?php endforeach; endif; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control select2" name="partida">
                                                        <option value="">Seleccione una opción</option>
                                                        <?php if(isset($cat_partida)): foreach ($cat_partida as $p): ?>
                                                            <option value="<?= $p->id_partida ?>" <?= (isset($solicitud) && $solicitud->partida == $p->id_partida) ? 'selected' : '' ?>>
                                                                <?= $p->cuenta_cable ?>
                                                            </option>
                                                        <?php endforeach; endif; ?>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="clave_estandarizada" value="<?= isset($solicitud) ? $solicitud->clave_estandarizada : '' ?>"></td>
                                                <td>
                                                    <p class="small text-muted mb-0">El proyecto cuenta con la suficiencia presupuestal. Se anexa captura.</p>
                                                    <input type="file" class="form-control-file mt-2" name="archivo_suficiencia">
                                                    <?php if(isset($solicitud) && $solicitud->archivo_suficiencia): ?>
                                                        <a href="<?= base_url('assets/uploads/adquisiciones/'.$solicitud->archivo_suficiencia) ?>" target="_blank" class="d-block mt-2">Ver archivo actual</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Monto Total Estimado (con número y letra):</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control monto-input" id="monto_total" name="monto_total" value="<?= isset($solicitud) ? $solicitud->monto_total : '' ?>" required>
                                        <input type="text" class="form-control mt-2 monto-letra" id="monto_letra" readonly placeholder="Monto en letra">
                                    </div>
                                </div>

                                <!-- SECCION 3: DESCRIPCIÓN DEL BIEN O SERVICIO -->
                                <h5 class="bg-primary text-white p-2 mt-4">DESCRIPCIÓN DE LOS BIENES O SERVICIOS A ADQUIRIR</h5>
                                <div class="form-group">
                                    <label>Descripción detallada:</label>
                                    <textarea class="form-control" name="descripcion_bienes" rows="4" required><?= isset($solicitud) ? $solicitud->descripcion_bienes : '' ?></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Fecha sugerida de entrega/inicio:</label>
                                        <input type="date" class="form-control" name="fecha_inicio" value="<?= isset($solicitud) ? date('Y-m-d', strtotime($solicitud->fecha_inicio)) : '' ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Lugar de entrega:</label>
                                        <input type="text" class="form-control" name="lugar_entrega" value="<?= isset($solicitud) ? $solicitud->lugar_entrega : '' ?>" required>
                                    </div>
                                </div>

                                <!-- SECCION 4: INFORMACIÓN DEL PROVEEDOR (SI APLICA) -->
                                <h5 class="bg-primary text-white p-2 mt-4">INFORMACIÓN DEL PROVEEDOR SUGERIDO (SI APLICA)</h5>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre/Razón Social:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_nombre" value="<?= isset($solicitud) ? $solicitud->proveedor_nombre : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">RFC:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_rfc" value="<?= isset($solicitud) ? $solicitud->proveedor_rfc : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Correo Electrónico:</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" name="proveedor_correo" value="<?= isset($solicitud) ? $solicitud->proveedor_correo : '' ?>">
                                    </div>
                                </div>

                                <!-- SECCION 5: DOCUMENTOS Y ANEXOS -->
                                <h5 class="bg-primary text-white p-2 mt-4 text-center">DOCUMENTOS Y ANEXOS</h5>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm mt-3">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center" style="width: 5%;">Num.</th>
                                                <th>DOCUMENTO</th>
                                                <th class="text-center" style="width: 5%;">SI</th>
                                                <th class="text-center" style="width: 5%;">N/A</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $documentos = [
                                                "Anexo Técnico (Términos de referencia)",
                                                "Investigación de Mercado (Cotizaciones y consulta PEI)",
                                                "Validación de partida restringida (SF)<br>Verificación de Alineación de Información Estratégica (DGIT)<br>Suficiencia presupuestal (R3)<br>Validación DGTIT/CGCS u otra",
                                                "Justificación",
                                                "Propuesta Técnico Económica (Anexo)",
                                                "Aviso de privacidad integral",
                                                "Cédula de Registro en el Padrón de Proveedores (Refrendo vigente)",
                                                "Escritura Constitutiva/Documento que acredite la legal constitución de la persona moral (Modificaciones sustanciales e inscripción en el Registro Público)",
                                                "Documento que acredite la representación de la persona moral (Poder)",
                                                "Identificación oficial vigente (Personas morales Representante y Responsable de seguimiento)",
                                                "Constancia de Situación Fiscal (RFC)",
                                                "Comprobante de domicilio (Sólo cuando sea diferente al domicilio fiscal)",
                                                "Opinión de cumplimiento de Obligaciones Fiscales<br>Manifiesto bajo protesta de cumplimiento de Obligaciones Fiscales",
                                                "Manifiesto de no encontrarse impedido para Contratar",
                                                "Carta de Declaración de intereses",
                                                "Manifiesto de contar con infraestructura",
                                                "Carta compromiso entrega de bienes (Excepción de Garantía)"
                                            ];
                                            $i = 1;
                                            foreach($documentos as $doc): ?>
                                            <tr>
                                                <td class="text-center font-weight-bold align-middle"><?= $i ?></td>
                                                <td class="align-middle"><?= $doc ?></td>
                                                <td class="text-center align-middle">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="doc_<?= $i ?>_si" name="documento_<?= $i ?>" value="SI" class="custom-control-input">
                                                        <label class="custom-control-label" for="doc_<?= $i ?>_si"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="doc_<?= $i ?>_na" name="documento_<?= $i ?>" value="NA" class="custom-control-input" checked>
                                                        <label class="custom-control-label" for="doc_<?= $i ?>_na"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $i++; endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

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
        $('.monto-input').on('input', function() {
            var inputTotal = $(this);
            var inputLetras = inputTotal.next('.monto-letra');
            var valor = inputTotal.val();
            // Validación de número
            if (isNaN(valor) || valor.trim() === '') {
                 if(valor.trim() !== '') {
                      inputLetras.val('NUMERO NO LEGIBLE');
                 } else {
                      inputLetras.val('');
                 }
            } else {
                inputLetras.val(numeroALetras(parseFloat(valor)));
            }
        });
        
        // Trigger inicial si ya hay valor
        $('.monto-input').each(function() {
            if($(this).val()) {
                $(this).trigger('input');
            }
        });

        // Initialize Select2
        $('.select2').select2();

        $('#form_solicitud_adquisiciones').on('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            var btnSubmit = $(this).find('button[type="submit"]');
            
            btnSubmit.prop('disabled', true);
            btnSubmit.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            $.ajax({
                url: '<?= base_url("index.php/Principal/guardarSolicitudAdquisiciones") ?>',
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
                            window.location.href = '<?= base_url("index.php/Principal/ListaSolicitudAdquisiciones") ?>';
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
