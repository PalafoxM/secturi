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
                    <input type="hidden" name="id_reserva" value=">">
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
                                        2026
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
                           
                            
                          
                            <tr class="item-row">
                                <!-- No. COMPROBANTE -->
                                <td style="vertical-align: top;">
                                    <input type="text" name="no_comprobante[]" class="form-control-plaintext mb-2" value="" placeholder="3220">
                                </td>

                                <!-- PROYECTO META -->
                                <td style="vertical-align: top;">
                                    E027QC04182601
                                </td>

                                <!-- No. PARTIDA -->
                                <td style="vertical-align: top;">
                                   2210
                                </td>

                                <!-- IMPORTE -->
                                <td style="vertical-align: top;">
                                    <input type="text" name="importe" class="form-control-plaintext input-importe" value="0.00" placeholder="$0.00">
                                    
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
                                        <label for="xml_pt" class="file-upload-btn btn-xm">
                                            <i class="feather icon-code"></i> XML
                                        </label>
                                        <input type="file" id="xml_pt" name="xml_pt" class="d-none input-xml" accept=".xml" onchange="updateFileName(this)">
                                        <input type="hidden" name="xml_current" value="">
                                        <span class="file-name-display" id="name_xml_pt"></span>
                                        
                                        <input type="hidden" name="row_index[]" value="">
                                    </div>

                                </td>

                                <!-- OBSERVACIONES / PROVIDER DATA (Solo en la primera fila con rowspan) -->
                           
                                <td class="text-left" style="padding: 15px; vertical-align: top;" rowspan="" id="tdProvider">
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
                                     <button type="button" class="btn btn-info btn-sm" id="btnAddRow">+ Agregar Fila</button>
                                 </td>
                                 <td colspan="1" class="text-left">
                                     <span class="label-bold me-2">CLAUSULA</span>
                                    <select class="form-control-plaintext flex-grow-1" name="clausula">
                                        <option value="PRIMERA">PRIMERA</option>
                                        <option value="SEGUNDA">SEGUNDA</option>
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
                                        <input type="text" name="no_reserva_visual"  class="form-control-plaintext text-left w-50" value="" placeholder="4798053">
                                    </div>
                                    <div class="d-flex align-items-center mt-2">
                                        <span class="label-bold me-2">CONCEPTO SOLICITUD:</span>
                                        <input type="text" name="concepto" class="form-control-plaintext text-left w-50" rows="2" placeholder="Concepto de la solicitud..." value="">
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

                    <!-- TABLE 4: AUTORIZACIONES -->
                    <table class="table table-bordered text-center mt-4">
                        <thead>
                            <tr class="bg-black">
                                <th colspan="3" class="text-uppercase" style="color: white;">AUTORIZACIONES</th>
                            </tr>
                            <tr class="bg-grey">
                                <th width="33%">DIRECTOR/A GENERAL ADMINISTRATIVO/A</th>
                                <th width="33%">AUTORIZA</th>
                                <th width="33%">RESPONSABLE DEL PROYECTO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="height: 100px;">
                                <td class="align-bottom pb-3">
                                    <input type="text" name="nombre_director_general" class="form-control-plaintext font-weight-bold mb-1" value="RODRIGO GONZALEZ GUERRERO">
                                    <input type="text" name="cargo_director_general" class="form-control-plaintext small" value="DIRECTOR/A GENERAL ADMINISTRATIVO/A">
                                </td>
                                <td class="align-bottom pb-3">

                                   
                                    <input type="text" name="cargo_autoriza" class="form-control-plaintext small" value="">
                                    
                                </td>
                                <td class="align-bottom pb-3">
                                   

                                    <input type="text" name="cargo_responsable_1" class="form-control-plaintext small" value="">
                                </td>
                            </tr>
                             <tr>
                                <td colspan="2" style="border: none;"></td>
                                <td class="bg-grey font-weight-bold border">RESPONSABLE DEL PROYECTO</td>
                            </tr>
                             <tr>
                                <td colspan="2" style="border: none;"></td>
                                <td class="align-bottom pb-3 border" style="height: 100px;">
                                  
                                  
                                    <input type="text" name="cargo_responsable_2" class="form-control-plaintext small" value="">
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
$(function () {
    var proveedorBancos = [];
    var $proveedor = $('#nombre_proveedor_1');
    var $banco = $('#banco');

    function limpiarBanco() {
        proveedorBancos = [];
        $banco.empty().append(new Option('Seleccione un banco', ''));
        $('#no_cuenta, #clabe').val('');
    }

    function limpiarProveedor() {
        $('#no_proveedor, #rfc_proveedor, #nombre_proveedor_2, #no_contrato').val('');
        limpiarBanco();
    }

    function seleccionarBanco(indice) {
        var banco = proveedorBancos[indice];
        $('#no_cuenta').val(banco ? (banco.no_cuenta || banco.cuenta || '') : '');
        $('#clabe').val(banco ? (banco.clabe || '') : '');
    }

    $proveedor.select2({
        width: '100%',
        placeholder: 'Buscar por nombre, RFC o No. proveedor',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '<?= base_url('index.php/Principal/buscarProveedorSelect2') ?>',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { term: params.term || '' };
            },
            processResults: function (data) {
                return {
                    results: $.map(data || [], function (item) {
                        return {
                            id: item.id_proveedor,
                            text: [item.razon_social, item.rfc, item.no_proveedor]
                                .filter(Boolean)
                                .join(' / ')
                        };
                    })
                };
            },
            cache: true
        }
    });

    $proveedor.on('select2:select', function (event) {
        var idProveedor = event.params.data.id;

        limpiarProveedor();

        $.ajax({
            url: '<?= base_url('index.php/Principal/getProveedor') ?>',
            type: 'POST',
            dataType: 'json',
            data: { id_proveedor: idProveedor }
        }).done(function (response) {
            if (response.error || !response.data || !response.data.proveedor) {
                limpiarProveedor();
                return;
            }

            var proveedor = response.data.proveedor;
            proveedorBancos = response.data.proveedor_banco || [];

            $('#no_proveedor').val(proveedor.no_proveedor || '');
            $('#rfc_proveedor').val(proveedor.rfc || '');
            $('#nombre_proveedor_2').val(proveedor.razon_social || '');
            $('#no_contrato').val(response.data.no_contrato || proveedor.no_contrato || proveedor.no_convenio || '');

            $banco.empty().append(new Option('Seleccione un banco', ''));
            $.each(proveedorBancos, function (indice, cuenta) {
                var descripcion = cuenta.banco || 'Banco sin nombre';
                if (cuenta.no_cuenta || cuenta.cuenta) {
                    descripcion += ' - ' + (cuenta.no_cuenta || cuenta.cuenta);
                }

                var opcion = new Option(descripcion, cuenta.banco || '', indice === 0, indice === 0);
                $(opcion).attr('data-indice', indice);
                $banco.append(opcion);
            });

            if (proveedorBancos.length) {
                seleccionarBanco(0);
            } else {
                $('#no_cuenta, #clabe').val('');
            }
        }).fail(function () {
            limpiarProveedor();
        });
    });

    $proveedor.on('select2:clear', limpiarProveedor);

    $banco.on('change', function () {
        var indice = $(this).find('option:selected').attr('data-indice');
        seleccionarBanco(indice === undefined ? -1 : Number(indice));
    });
});
</script>

