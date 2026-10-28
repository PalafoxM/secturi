    <style>
    .bg-black {
        background-color: #000 !important;
        color: #fff !important;
    }
    .bg-grey {
        background-color: #e0e0e0 !important;
        color: #000 !important;
        font-weight: bold;
    }
    .form-control-plaintext {
        border: 1px solid #ccc;
        padding: 4px;
        width: 100%;
        text-align: center;
    }
    .table-bordered th, .table-bordered td {
        border: 1px solid #000 !important;
        vertical-align: middle;
    }
    .input-group-text {
        background: transparent;
        border: none;
        font-weight: bold;
        padding-right: 5px;
    }
    .provider-section input {
        border: none;
        border-bottom: 1px solid #ccc;
        width: 100%;
        background: transparent;
    }
    .label-bold {
        font-weight: bold;
        font-size: 0.9em;
    }
    .is-invalid {
        border-color: red !important;
        background-color: #ffcccc !important;
    }
    
    /* Custom File Input Styles */
    .file-upload-btn {
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        color: white;
        margin-right: 5px;
        margin-bottom: 5px;
        transition: all 0.3s ease;
        text-align: center;
        width: 80px;
    }
    
    .btn-pdf {
        background-color: #dc3545; /* Red for PDF */
        border: 1px solid #dc3545;
    }
    
    .btn-pdf:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .btn-xml {
        background-color: #28a745; /* Green for XML */
        border: 1px solid #28a745;
    }

    .btn-xml:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .btn-loaded {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: white !important;
    }

    .file-name-display {
        font-size: 10px;
        color: #666;
        display: block;
        margin-top: -4px;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100px; 
    }
    
    .commission-container {
        margin-top: 8px;
        border-top: 1px dashed #ccc;
        padding-top: 5px;
    }

    .btn-agregar-fila-fic {
        border-radius: 18px;
        font-weight: 600;
        padding: 6px 16px;
    }

    .btn-quitar-fila-fic {
        font-size: 10px;
        margin-top: 8px;
        padding: 3px 9px;
    }
    
    /* Fix para visualización de textos largos en Select2 */
    .select2-container .select2-selection--single {
        height: auto !important;
        min-height: 32px;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        white-space: normal !important;
        word-wrap: break-word !important;
        padding-right: 35px !important; /* Espacio para la 'x' y la flecha '▼' */
        text-align: left; /* Mejor para textos que hacen salto de línea */
    }
</style>



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
                                <li class="breadcrumb-item active">PT</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Formulario FIC</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>


            <div class="container-fluid" style="background: white; padding: 20px;">
                
                <form id="formPagoTerceros" enctype="multipart/form-data">
                    <input type="hidden" name="filas_fic" id="filas_fic" value="1">
                    <!-- HEADER -->
                    <div class="row mb-4">
                        <div class="col-md-12 text-center">
                            <h3>FORMATO DE PAGO FIC</h3>
                        </div>
                    </div>

                    <!-- TABLE 1: RAMO O ENTIDAD REMITENTE -->
                    <table class="table table-bordered text-center mb-4">
                        <thead>
                            <tr class="bg-black">
                                <th colspan="3" class="text-uppercase" style="color: white;">RAMO O ENTIDAD REMITENTE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-grey">
                                <td colspan="3">
                                    <input type="text" class="form-control-plaintext bg-grey" value="21 SECRETARIA DE TURISMO E IDENTIDAD" readonly>
                                </td>
                            </tr>
                            <tr class="bg-grey">
                                <td style="width: 33%;">DIVISIÓN</td>
                                <td style="width: 33%;">FECHA TRÁMITE</td>
                                <td style="width: 33%;">FOLIO</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="division" class="form-control-plaintext" value="21">
                                </td>
                                <td>
                                    <input type="date" name="fecha_tramite" class="form-control-plaintext" value="<?= date('Y-m-d') ?>">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <select name="anio" class="form-control-plaintext mx-1" style="width: 80px;">
                                            <option value="PT SECTURI/SSIDT/DGCT/FIC/TA/">PT SECTURI/SSIDT/DGCT/FIC/TA/</option>
                                            <option value="PT SECTURI/SSIDT/DGCT/FIC/TH/">PT SECTURI/SSIDT/DGCT/FIC/TH/</option>
                                        </select>
                                        <input type="text" name="no_consecutivo" autocomplete="off" class="form-control-plaintext mx-1" style="width: 80px;" value="" placeholder="001">
                                        <?= date('Y') ?>
                                    </div>
                                    <span id="folio_error" class="text-success d-block mt-1"></span>
                              
                                    <input type="hidden" name="folioCompleto" id="folioCompleto">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- TABLE 2: DATOS PROPORCIONADOS POR LA DEPENDENCIA -->
                    <table id="pagoterceros_items_table" class="table table-bordered text-center">
                        <thead>
                            <tr class="bg-black">
                                <th colspan="5" class="text-uppercase" style="color: white;">DATOS PROPORCIONADOS POR LA DEPENDENCIA</th>
                            </tr>
                            <tr class="bg-grey">
                                <td colspan="5">REFERENCIA AL DOCUMENTO</td>
                            </tr>
                            <tr>
                                <th style="width: 10%;">No. COMPROBANTE</th>
                                <th style="width: 25%;">PROYECTO META</th>
                                <th style="width: 15%;">No. PARTIDA</th>
                                <th style="width: 15%;">IMPORTE</th>
                                <th style="width: 35%;">OBSERVACIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            
                          
                            <tr class="item-row" data-row="1">
                                <!-- No. COMPROBANTE -->
                                <td style="vertical-align: top;">
                                    <input type="text" id="comprobante" name="comprobante" class="form-control-plaintext mb-2 input-comprobante" value="" placeholder="Folio del XML" readonly>
                                </td>

                                <!-- PROYECTO META -->
                                <td style="vertical-align: top;">
                                    E027QC04182601
                                </td>

                                <!-- No. PARTIDA -->
                                <td style="vertical-align: top;">
                                   <select name="no_partida" id="no_partida" class="form-control-plaintext input-partida">
                                        <option value="">Seleccione una partida</option>
                                        <option value="3390">3390</option>
                                        <option value="2210">2210</option>
                                    </select>
                                </td>

                                <!-- IMPORTE -->
                                <td style="vertical-align: top;">
                                    <input type="text" id="importe" name="importe" class="form-control-plaintext input-importe" value="" placeholder="$0.00" readonly>
                                    
                                    <!-- File Inputs -->
                                    <div class="mt-2 text-center">
                                        <!-- PDF Button -->
                                        <label for="pdf_pt" class="file-upload-btn btn-pdf">
                                            <i class="feather icon-file-text"></i> PDF
                                        </label>
                                        <input type="file" id="pdf_pt" name="pdf_pt" class="d-none input-pdf" accept=".pdf" onchange="updateFileName(this)">
                                        <input type="hidden" name="pdf_current" value="">
                                        <span class="file-name-display" id="name_pdf_pt"></span>

                                        <!-- XML Button -->
                                        <label for="xml_fic" class="file-upload-btn btn-xml">
                                            <i class="feather icon-code"></i> XML
                                        </label>
                                        <input type="file" id="xml_fic" name="xml_fic" class="d-none input-xml" accept=".xml,text/xml,application/xml" onchange="leerXmlFIC(this)">
                                        <span class="file-name-display" id="name_xml_fic"></span>
                                    </div>

                                </td>

                                <!-- OBSERVACIONES / PROVIDER DATA (Solo en la primera fila con rowspan) -->
                           
                                <td class="text-left" style="padding: 15px; vertical-align: top;" rowspan="1" id="tdProvider">
                                    <div class="label-bold mb-2">DATOS DEL PROVEEDOR NACIONAL</div>
                                    
                                    <div class="provider-section">
                                        <!-- Organization Name -->
                                        <div class="mb-2">
                                            <select id="nombre_proveedor_1" name="nombre_proveedor_1" class="form-control-plaintext" style="width: 100%;">
                                                <option value="">Seleccione un proveedor</option>
                                            </select>
                                        </div>

                                        <!-- No. Proveedor -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2 align-self-start">No. PROVEEDOR:</span>
                                            <input type="text" id="no_proveedor" name="no_proveedor" class="flex-grow-1" placeholder="103156" value="">
                                        </div>

                                        <!-- RFC -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">RFC:</span>
                                            <input type="text" id="rfc_proveedor" name="rfc_proveedor" class="flex-grow-1" placeholder="N0011499A" value="">
                                        </div>

                                        <!-- Nombre Proveedor (Repetido en imagen) -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2 align-self-start">NOMBRE:</span>
                                            <input type="text" id="nombre_proveedor_2" name="nombre_proveedor_2" class="flex-grow-1" placeholder="ORGANIZACION MUNDIAL DEL TURISMO" value="">
                                        </div>

                                        <!-- No. Cuenta -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">NO. CUENTA:</span>
                                            <input type="text" id="no_cuenta" name="no_cuenta" class="flex-grow-1" placeholder="610081057237000168" value="">
                                        </div>

                                        <!-- Banco -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">BANCO:</span>
                                            <select id="banco" name="banco" class="form-control-plaintext flex-grow-1">
                                                <option value="">Seleccione un banco</option>
                                              
                                            </select>
                                        </div>

                                        <!-- CLABE -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">CLABE:</span>
                                            <input type="text" id="clabe" name="clabe" class="flex-grow-1" placeholder="012180001057237008" value="">
                                        </div>

                                    </div>
                                </td>
                             
                            </tr>
                            
                        </tbody>
                        <tfoot>
                           
                             <tr>
                                 <td colspan="4" class="text-left">
                                     <small class="text-muted">El No. de comprobante y el importe se obtienen del XML.</small>
                                     <button type="button" id="btnAgregarFilaFIC" class="btn btn-outline-primary btn-sm btn-agregar-fila-fic ml-3">
                                         <i class="mdi mdi-plus"></i> Agregar otra fila
                                     </button>
                                     <small class="text-muted ml-2">Máximo 3 comprobantes.</small>
                                 </td>
                                 <td colspan="1" class="text-left">
                                     <span class="label-bold me-2">CLAUSULA</span>
                                    <select class="form-control-plaintext flex-grow-1" name="clausula">
                                        <option value="TERCERA">TERCERA</option>
                                        <option value="NO APLICA">NO APLICA</option>
                                    </select>
                                 </td>
                                 
                                    
                             </tr>
                          
                           
                        </tfoot>
                    </table>


                    <!-- TABLE 3: CONTRATO / RESERVA / TOTAL -->
                    <table class="table table-bordered text-center mb-0">
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-left bg-white" style="border-bottom: none !important;">
                                    <div class="d-flex align-items-center">
                                        <span class="label-bold me-2">No. CONTRATO y/o CONVENIO:</span>
                                        <input type="text" id="no_contrato" name="no_convenio" class="form-control-plaintext text-left w-50" value="">
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="label-bold me-2">No. RESERVA:</span>
                                        <select type="text" name="no_reserva_visual"  class="form-control-plaintext text-left w-50" value="" placeholder="4798053">
                                            <option value="5413293">5413293</option>
                                            <option value="5413298">5413298</option>
                                            <option value="5413302">5413302</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="70%" class="text-right">
                                     <!-- Espacio vacío alineado a la derecha del importe superior -->
                                </td>
                                <td width="30%" class="text-center" style="border-top: 1px solid #000;">
                                    <input type="text" name="importe_total_num" class="form-control-plaintext input-importe font-weight-bold" value="" placeholder="$0.00" readonly>
                                </td>
                            </tr>
                             <tr>
                                <td colspan="4" class="text-center font-weight-bold">
                                    <input type="text" name="importe_letra" class="form-control-plaintext" value="" placeholder="Sesenta y dos mil seiscientos diez pesos 00/100 M.N." readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>

              

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancelar</button>
                            <button type="button" class="btn btn-primary" id="btnGuardarPT">Guardar</button>
                        </div>
                    </div>

                </form>
            </div>


    </div>
    <!-- end page content -->
</div>

<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />
<!-- App css -->
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<!-- jQuery  -->
 
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>


<!-- Required datatable js -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

<!-- App js -->
<script src="<?= base_url() ?>assets/js/app.js"></script>
<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>

<script src="<?= base_url() ?>plugins/tiny-editable/mindmup-editabletable.js"></script>
<script src="<?= base_url() ?>plugins/tiny-editable/numeric-input-example.js"></script>
<script src="<?= base_url() ?>plugins/bootable/bootstable.js"></script> 
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />

<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>



<script>
function mostrarMensajeFIC(icono, titulo, mensaje) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: icono, title: titulo, text: mensaje });
        return;
    }
    alert(mensaje);
}

function updateFileName(input) {
    var nombre = input.files && input.files[0] ? input.files[0].name : '';
    $('#name_' + input.id).text(nombre).attr('title', nombre);
    $('label[for="' + input.id + '"]').toggleClass('btn-loaded', nombre !== '');
}

function sufijoFilaFIC(numeroFila) {
    return numeroFila === 1 ? '' : '_' + numeroFila;
}

function convertirDecenasFIC(numero) {
    var unidades = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
    var especiales = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISÉIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
    var decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
    var decena = Math.floor(numero / 10);
    var unidad = numero % 10;

    if (decena === 0) {
        return unidades[unidad];
    }
    if (decena === 1) {
        return especiales[unidad];
    }
    if (decena === 2 && unidad > 0) {
        return 'VEINTI' + unidades[unidad];
    }
    return decenas[decena] + (unidad > 0 ? ' Y ' + unidades[unidad] : '');
}

function convertirCentenasFIC(numero) {
    var centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];
    if (numero === 100) {
        return 'CIEN';
    }

    var centena = Math.floor(numero / 100);
    var resto = numero % 100;
    return [centenas[centena], resto > 0 ? convertirDecenasFIC(resto) : ''].filter(Boolean).join(' ');
}

function convertirEnteroFIC(numero) {
    if (numero === 0) {
        return 'CERO';
    }

    var partes = [];
    var millones = Math.floor(numero / 1000000);
    var miles = Math.floor((numero % 1000000) / 1000);
    var unidades = numero % 1000;

    if (millones > 0) {
        partes.push(millones === 1 ? 'UN MILLÓN' : convertirCentenasFIC(millones) + ' MILLONES');
    }
    if (miles > 0) {
        partes.push(miles === 1 ? 'MIL' : convertirCentenasFIC(miles) + ' MIL');
    }
    if (unidades > 0) {
        partes.push(convertirCentenasFIC(unidades));
    }

    return partes.join(' ');
}

function numeroALetrasFIC(importe) {
    var importeRedondeado = Math.round((Number(importe) + Number.EPSILON) * 100) / 100;
    var pesos = Math.floor(importeRedondeado);
    var centavos = Math.round((importeRedondeado - pesos) * 100);

    if (centavos === 100) {
        pesos++;
        centavos = 0;
    }

    return convertirEnteroFIC(pesos)
        + (pesos === 1 ? ' PESO ' : ' PESOS ')
        + String(centavos).padStart(2, '0')
        + '/100 M.N.';
}

function recalcularTotalFIC() {
    var total = 0;
    $('.item-row .input-importe').each(function () {
        var importe = Number(String($(this).val() || '').replace(/,/g, ''));
        if (Number.isFinite(importe)) {
            total += importe;
        }
    });

    $('input[name="importe_total_num"]').val(total.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));
    $('input[name="importe_letra"]').val(total > 0 ? numeroALetrasFIC(total) : '');
}

function leerXmlFIC(input) {
    updateFileName(input);
    var numeroFila = Number(input.getAttribute('data-row') || 1);
    var sufijo = sufijoFilaFIC(numeroFila);
    $('#comprobante' + sufijo + ', #importe' + sufijo).val('');
    recalcularTotalFIC();

    if (!input.files || !input.files[0]) {
        return;
    }

    var archivo = input.files[0];
    if (!/\.xml$/i.test(archivo.name)) {
        input.value = '';
        updateFileName(input);
        mostrarMensajeFIC('warning', 'Archivo incorrecto', 'Seleccione un archivo XML.');
        return;
    }

    var lector = new FileReader();
    lector.onload = function (evento) {
        var documento = new DOMParser().parseFromString(evento.target.result, 'application/xml');
        if (documento.getElementsByTagName('parsererror').length) {
            input.value = '';
            updateFileName(input);
            mostrarMensajeFIC('error', 'XML inválido', 'No fue posible interpretar el archivo seleccionado.');
            return;
        }

        var comprobanteXml = documento.documentElement;
        var folio = comprobanteXml.getAttribute('Folio') || comprobanteXml.getAttribute('folio') || '';
        var totalTexto = comprobanteXml.getAttribute('Total') || comprobanteXml.getAttribute('total') || '';
        var importe = Number(String(totalTexto).replace(/,/g, ''));
        var uuid = '';
        var timbres = documento.getElementsByTagNameNS('*', 'TimbreFiscalDigital');
        if (timbres.length) {
            uuid = timbres[0].getAttribute('UUID') || '';
        }

        var numeroComprobante = folio || uuid;
        if (!numeroComprobante || !Number.isFinite(importe) || importe <= 0) {
            input.value = '';
            updateFileName(input);
            mostrarMensajeFIC('error', 'XML incompleto', 'El XML debe contener Folio o UUID y un importe total válido.');
            return;
        }

        var importeFormateado = importe.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        $('#comprobante' + sufijo).val(numeroComprobante);
        $('#importe' + sufijo).val(importeFormateado);
        recalcularTotalFIC();
    };
    lector.onerror = function () {
        mostrarMensajeFIC('error', 'Error', 'No fue posible leer el archivo XML.');
    };
    lector.readAsText(archivo);
}

$(function () {
    var $proveedor = $('#nombre_proveedor_1');
    var $banco = $('#banco');
    var filasActuales = 1;

    function crearFilaFIC(numeroFila) {
        var sufijo = sufijoFilaFIC(numeroFila);
        return ''
            + '<tr class="item-row" data-row="' + numeroFila + '">'
            + '  <td style="vertical-align: top;">'
            + '    <input type="text" id="comprobante' + sufijo + '" name="comprobante' + sufijo + '" class="form-control-plaintext mb-2 input-comprobante" placeholder="Folio del XML" readonly>'
            + '    <button type="button" class="btn btn-outline-danger btn-sm btn-quitar-fila-fic" data-row="' + numeroFila + '"><i class="mdi mdi-close"></i> Quitar fila</button>'
            + '  </td>'
            + '  <td style="vertical-align: top;">E027QC04182601</td>'
            + '  <td style="vertical-align: top;">'
            + '    <select name="no_partida' + sufijo + '" id="no_partida' + sufijo + '" class="form-control-plaintext input-partida">'
            + '      <option value="">Seleccione una partida</option>'
            + '      <option value="3390">3390</option>'
            + '      <option value="2210">2210</option>'
            + '    </select>'
            + '  </td>'
            + '  <td style="vertical-align: top;">'
            + '    <input type="text" id="importe' + sufijo + '" name="importe' + sufijo + '" class="form-control-plaintext input-importe" placeholder="$0.00" readonly>'
            + '    <div class="mt-2 text-center">'
            + '      <label for="pdf_pt' + sufijo + '" class="file-upload-btn btn-pdf"><i class="feather icon-file-text"></i> PDF</label>'
            + '      <input type="file" id="pdf_pt' + sufijo + '" name="pdf_pt' + sufijo + '" class="d-none input-pdf" accept=".pdf,application/pdf" onchange="updateFileName(this)">'
            + '      <span class="file-name-display" id="name_pdf_pt' + sufijo + '"></span>'
            + '      <label for="xml_fic' + sufijo + '" class="file-upload-btn btn-xml"><i class="feather icon-code"></i> XML</label>'
            + '      <input type="file" id="xml_fic' + sufijo + '" name="xml_fic' + sufijo + '" data-row="' + numeroFila + '" class="d-none input-xml" accept=".xml,text/xml,application/xml" onchange="leerXmlFIC(this)">'
            + '      <span class="file-name-display" id="name_xml_fic' + sufijo + '"></span>'
            + '    </div>'
            + '  </td>'
            + '</tr>';
    }

    function actualizarFilasFIC() {
        $('#filas_fic').val(filasActuales);
        $('#tdProvider').attr('rowspan', filasActuales);
        $('#btnAgregarFilaFIC').prop('disabled', filasActuales >= 3);
        $('.btn-quitar-fila-fic').toggleClass('d-none', false);
    }

    $('#btnAgregarFilaFIC').on('click', function () {
        if (filasActuales >= 3) {
            return;
        }
        filasActuales++;
        $('#pagoterceros_items_table tbody').append(crearFilaFIC(filasActuales));
        actualizarFilasFIC();
    });

    $('#pagoterceros_items_table').on('click', '.btn-quitar-fila-fic', function () {
        var numeroFila = Number($(this).attr('data-row'));
        if (numeroFila !== filasActuales || filasActuales <= 1) {
            return;
        }
        $(this).closest('tr').remove();
        filasActuales--;
        actualizarFilasFIC();
        recalcularTotalFIC();
    });

    function actualizarFolio() {
        var prefijo = $('select[name="anio"]').val() || '';
        var consecutivo = $.trim($('input[name="no_consecutivo"]').val() || '');
        var folio = consecutivo ? prefijo + consecutivo + '/<?= date('Y') ?>' : '';
        $('#folio_error').text(folio);
        $('#folioCompleto').val(folio);
    }

    $('select[name="anio"], input[name="no_consecutivo"]').on('change input', actualizarFolio);
    actualizarFolio();

    function limpiarEstablecimiento() {
        $('#no_proveedor, #rfc_proveedor, #nombre_proveedor_2, #no_contrato, #no_cuenta, #clabe').val('');
        $banco.empty().append(new Option('Seleccione un banco', ''));
    }

    $proveedor.select2({
        width: '100%',
        placeholder: 'Buscar establecimiento por nombre, RFC o No. proveedor',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '<?= base_url('index.php/Inicio/buscarEstablecimientoFIC') ?>',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { term: params.term || '' };
            },
            processResults: function (data) {
                return { results: data.results || [] };
            },
            cache: true
        }
    });

    $proveedor.on('select2:select', function (event) {
        var establecimiento = event.params.data || {};

        $('#no_proveedor').val(establecimiento.no_proveedor || '');
        $('#rfc_proveedor').val(establecimiento.rfc || '');
        $('#nombre_proveedor_2').val(establecimiento.razon_social || '');
        $('#no_contrato').val(establecimiento.no_contrato || '');
        $('#no_cuenta').val(establecimiento.no_cuenta || '');
        $('#clabe').val(establecimiento.clabe || '');

        $banco.empty().append(new Option('Seleccione un banco', ''));
        if (establecimiento.banco) {
            $banco.append(new Option(establecimiento.banco, establecimiento.banco, true, true));
        }
    });

    $proveedor.on('select2:clear', limpiarEstablecimiento);

    $('#btnGuardarPT').on('click', function () {
        actualizarFolio();

        var filasCompletas = true;
        for (var numeroFila = 1; numeroFila <= filasActuales; numeroFila++) {
            var sufijo = sufijoFilaFIC(numeroFila);
            if (!$('#pdf_pt' + sufijo).val() || !$('#xml_fic' + sufijo).val()
                || !$('#comprobante' + sufijo).val() || !$('#no_partida' + sufijo).val()) {
                filasCompletas = false;
                break;
            }
        }

        if (!$proveedor.val() || !$('#folioCompleto').val() || !filasCompletas
            || !$('select[name="no_reserva_visual"]').val()) {
            mostrarMensajeFIC('warning', 'Datos incompletos', 'Seleccione establecimiento, partida, reserva, consecutivo y los archivos PDF y XML.');
            return;
        }

        var $boton = $(this);
        var textoOriginal = $boton.html();
        $boton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Guardando...');

        $.ajax({
            url: '<?= base_url('index.php/Inicio/guardarPagoFIC') ?>',
            type: 'POST',
            data: new FormData(document.getElementById('formPagoTerceros')),
            processData: false,
            contentType: false,
            dataType: 'json'
        }).done(function (response) {
            if (response.error) {
                mostrarMensajeFIC('error', 'No fue posible guardar', response.respuesta || 'Revise la información capturada.');
                return;
            }

            if (response.data) {
                $('input[name="importe_total_num"]').val(
                    Number(response.data.importe || 0).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })
                );
                $('input[name="importe_letra"]').val(response.data.importe_letra || '');
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Guardado',
                    text: response.respuesta,
                    confirmButtonText: 'Ver lista'
                }).then(function () {
                    window.location.href = '<?= base_url('index.php/Inicio/ListaPagosFIC') ?>';
                });
            } else {
                alert(response.respuesta);
                window.location.href = '<?= base_url('index.php/Inicio/ListaPagosFIC') ?>';
            }
        }).fail(function () {
            mostrarMensajeFIC('error', 'Error', 'Ocurrió un error al guardar el pago FIC.');
        }).always(function () {
            $boton.prop('disabled', false).html(textoOriginal);
        });
    });
});
</script>

