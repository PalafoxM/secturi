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
                                <li class="breadcrumb-item active">Solicitud de Pago de Honorarios</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Solicitud de Pago de Honorarios</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h4 class="mt-0">DIRECCIÓN GENERAL JURÍDICA (DGJ-1)</h4>
                                <h5>SOLICITUD DE PAGO DE HONORARIOS</h5>
                            </div>
                            
                            <form id="form_solicitud_honorarios" enctype="multipart/form-data">
                                <input type="hidden" name="id_solicitud_honorarios" value="<?= isset($solicitud) ? $solicitud->id_solicitud_honorarios : '' ?>">
                                
                                <!-- SECCION I: LUGAR, FECHA Y TIPO DE SOLICITUD -->
                                <h5 class="bg-primary text-white p-2">I. LUGAR, FECHA Y TIPO DE SOLICITUD</h5>
                                
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Lugar de expedición:</label>
                                        <input type="text" class="form-control" name="lugar_expedicion" value="<?= isset($solicitud) ? $solicitud->lugar_expedicion : 'Silao, Gto.' ?>" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label>Día:</label>
                                        <input type="text" class="form-control text-center" name="dia" value="<?= date('d') ?>" readonly>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label>Mes:</label>
                                        <input type="text" class="form-control text-center" name="mes" value="<?= date('m') ?>" readonly>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label>Año:</label>
                                        <input type="text" class="form-control text-center" name="anio" value="<?= date('Y') ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Tipo de solicitud:</label>
                                        <select class="form-control" name="tipo_solicitud" required>
                                            <option value="">Seleccione el tipo</option>
                                            <option value="Honorario asimilado" <?= (isset($solicitud) && $solicitud->tipo_solicitud == 'Honorario asimilado') ? 'selected' : '' ?>>Honorario asimilado</option>
                                            <option value="Honorario puro" <?= (isset($solicitud) && $solicitud->tipo_solicitud == 'Honorario puro') ? 'selected' : '' ?>>Honorario puro</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Núm de contrato:</label>
                                        <input type="text" class="form-control" name="num_contrato" value="<?= isset($solicitud) ? $solicitud->num_contrato : '' ?>" required>
                                    </div>
                                </div>

                                <!-- SECCION II: DATOS DEL PRESTADOR DE SERVICIOS -->
                                <h5 class="bg-primary text-white p-2 mt-4">II. DATOS DEL PRESTADOR DE SERVICIOS</h5>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nombre:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="nombre_prestador" value="<?= isset($solicitud) ? $solicitud->nombre_prestador : '' ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">RFC:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="rfc_prestador" value="<?= isset($solicitud) ? $solicitud->rfc_prestador : '' ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Periodo de pago:</label>
                                    <div class="col-sm-1 text-right">Del:</div>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" name="periodo_del" value="<?= isset($solicitud) ? $solicitud->periodo_del : '' ?>" required>
                                    </div>
                                    <div class="col-sm-1 text-right">Al:</div>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" name="periodo_al" value="<?= isset($solicitud) ? $solicitud->periodo_al : '' ?>" required>
                                    </div>
                                </div>

                                <!-- SECCION III: IMPORTE -->
                                <h5 class="bg-primary text-white p-2 mt-4">III. IMPORTE</h5>
                                <div class="bg-light p-3 border rounded">
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-8 text-right font-weight-bold">Importe Neto :  $</div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control text-right font-weight-bold" id="importe_neto_display" readonly value="0.00">
                                        </div>
                                    </div>

                                    <table class="table table-bordered mb-0 bg-white">
                                        <tbody>
                                            <tr>
                                                <td class="align-middle font-weight-bold" style="width: 30%;">HONORARIO BRUTO</td>
                                                <td><input type="number" step="0.01" class="form-control text-right importe-calc" id="honorario_bruto" name="honorario_bruto" value="<?= isset($solicitud) ? $solicitud->honorario_bruto : '' ?>" required></td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle text-danger font-weight-bold">MENOS (-)</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle">RETENCIÓN ISR</td>
                                                <td><input type="number" step="0.01" class="form-control text-right importe-calc" id="retencion_isr" name="retencion_isr" value="<?= isset($solicitud) ? $solicitud->retencion_isr : '' ?>"></td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle">RETENCIÓN IVA</td>
                                                <td><input type="number" step="0.01" class="form-control text-right importe-calc" id="retencion_iva" name="retencion_iva" value="<?= isset($solicitud) ? $solicitud->retencion_iva : '' ?>"></td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle">OTROS</td>
                                                <td><input type="number" step="0.01" class="form-control text-right importe-calc" id="otros" name="otros" value="<?= isset($solicitud) ? $solicitud->otros : '' ?>"></td>
                                            </tr>
                                            <tr class="bg-light">
                                                <td class="align-middle font-weight-bold text-primary">IGUAL (=)</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle font-weight-bold">IMPORTE NETO</td>
                                                <td><input type="text" class="form-control text-right font-weight-bold" id="importe_neto" name="importe_neto" value="<?= isset($solicitud) ? $solicitud->importe_neto : '' ?>" readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    <div class="mt-3">
                                        <input type="text" class="form-control text-center text-uppercase font-weight-bold" id="importe_neto_letra" name="importe_neto_letra" readonly placeholder="CANTIDAD EN LETRAS">
                                    </div>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-success btn-lg"><i class="mdi mdi-content-save"></i> Guardar Solicitud Honorarios</button>
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
        if (amount == 0) return "(CERO PESOS 00/100 M.N.)";
        var pesos = Math.floor(amount);
        var centavos = Math.round((amount - pesos) * 100);
        var letras = "";

        if (pesos == 0) letras = "CERO";
        else if (pesos == 1) letras = "UN";
        else letras = convertirGrupo(pesos);

        return ("(" + letras + " PESOS " + (centavos < 10 ? "0" : "") + centavos + "/100 M.N.)").toUpperCase();
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

    function calcularImporteNeto() {
        var bruto = parseFloat($('#honorario_bruto').val()) || 0;
        var isr = parseFloat($('#retencion_isr').val()) || 0;
        var iva = parseFloat($('#retencion_iva').val()) || 0;
        var otros = parseFloat($('#otros').val()) || 0;
        
        var neto = bruto - isr - iva - otros;
        
        $('#importe_neto').val(neto.toFixed(2));
        $('#importe_neto_display').val(neto.toFixed(2));
        $('#importe_neto_letra').val(numeroALetras(neto));
    }

    $(document).ready(function() {
        // Initial Calculation
        calcularImporteNeto();
        
        $('.importe-calc').on('input', function() {
            calcularImporteNeto();
        });

        $('#form_solicitud_honorarios').on('submit', function(e) {
            e.preventDefault();
            // Implement saving logic
            Swal.fire({
                icon: 'info',
                title: 'No implementado',
                text: 'La funcionalidad de guardar esta solicitud aún no se ha implementado en el backend.',
                showConfirmButton: true
            });
        });
    });
</script>
