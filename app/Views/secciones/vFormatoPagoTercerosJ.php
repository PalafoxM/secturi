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
    <?php
    $anio_actual = (isset($es2025) && $es2025) ? '2025' : '2026';
    $es_refrendo = isset($es_refrendo)
        ? (bool) $es_refrendo
        : (isset($registro_pt->tipo_formato) && strtoupper((string) $registro_pt->tipo_formato) === 'REFRENDO');
    $prefix_found = '';
    $seguir_pagando = isset($seguir_pagando) && $seguir_pagando == 1;
    // Helper to extract no_consecutivo and prefix from full folio string if editing
    // Expected format: PT [PREFIX] [NUMBER]/[YEAR]
    if (isset($registro_pt->no_consecutivo) && (!isset($no_consecutivo) || empty($no_consecutivo))) {
        // Try to match the prefix, number and year
        if (preg_match('/(?:PT|GO|REFRENDO\s*GO|REFRENDO\s*PT)\s+(.*?)\s*([0-9]{3,})\/([0-9]{4})$/i', trim($registro_pt->no_consecutivo), $matches)) {
            $prefix_found = trim($matches[1]);
            // Si el prefijo capturado tiene un '/' al final por el greedy match o no,
            // (ej. "SECTURI/DGA/001" el (.*?) con slash puede haber dejado un slash extra o quitado).
            // Limpiamos.
            if (substr($prefix_found, -1) === '/') {
                $prefix_found = substr($prefix_found, 0, -1);
            }
            if (!$seguir_pagando) {
                $no_consecutivo = $matches[2];
            }
            $anio_actual = $matches[3];
        } else if (preg_match('/([0-9]{3,})\/([0-9]{4})$/', trim($registro_pt->no_consecutivo), $matches)) {
            if (!$seguir_pagando) {
                $no_consecutivo = $matches[1];
            }
            $anio_actual = $matches[2];
        } else if (preg_match('/([0-9]{3,})/', trim($registro_pt->no_consecutivo), $matches)) {
            if (!$seguir_pagando) {
                $no_consecutivo = $matches[1];
            }
        } else {
             // Fallback: try to find the last occurring number logic if format differs?
             // For now assume the standard format described in JS.
             if (!$seguir_pagando) {
                 $no_consecutivo = $registro_pt->no_consecutivo;
             }
        }
    } else if (isset($consecutivo) && (!isset($no_consecutivo) || empty($no_consecutivo))) {
        $no_consecutivo = $consecutivo;
    }

    // Attempt to recover id_area from the parsed prefix if editing an existing record
    if (!empty($prefix_found) && isset($cat_area) && is_array($cat_area)) {
        foreach ($cat_area as $area) {
            $clean_area = trim($area->prefijo);
            if (substr($clean_area, -1) === '/') {
                $clean_area = substr($clean_area, 0, -1);
            }
            if (strtoupper($clean_area) === strtoupper($prefix_found)) {
                $id_area = $area->id_area;
                break;
            }
        }
    }
    ?>

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
                        <h4 class="page-title">Formulario PT</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>


            <div class="container-fluid" style="background: white; padding: 20px;">
                
                <form id="formPagoTerceros" enctype="multipart/form-data">

                    <!-- HEADER -->
                    <div class="row mb-4">
                        <div class="col-md-12 text-center">
                            <h3>FORMATO DE PAGO A TERCEROS</h3>
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
                                    <input type="date" name="fecha_tramite" class="form-control-plaintext" value="<?= isset($reserva->fec_reg) ? date('Y-m-d', strtotime($reserva->fec_reg)) : date('d-m-Y') ?>">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <select id="folio" name="folio" class="folio">
                                           <?php foreach($cat_area as $area): ?>
                                                <option value="<?= $area->id_area ?>" <?= (isset($id_area) && $id_area == $area->id_area) ? 'selected' : '' ?>><?= $area->prefijo ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="text" name="no_consecutivo" autocomplete="off" class="form-control-plaintext mx-1" style="width: 80px;" value="<?= isset($no_consecutivo) ? $no_consecutivo : '' ?>" placeholder="001">
                                        <span style="font-weight:bold; font-size: 1.2em;">/</span>
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
                            <?php foreach($presupuesto as $index => $row): ?>
                            <tr class="item-row">
                                <!-- No. COMPROBANTE -->
                                <td style="vertical-align: top;">
                                    <input type="text" name="no_comprobante[]" class="form-control-plaintext mb-2" value="" placeholder="3220">
                                </td>

                                <!-- PROYECTO META -->
                                <td style="vertical-align: top;">
                                    <input type="text" name="proyecto_meta[]" class="form-control-plaintext" value="<?= isset($row->proyecto) ? $row->proyecto : '' ?>">
                                </td>

                                <!-- No. PARTIDA -->
                                <td style="vertical-align: top;">
                                      <input type="text" name="no_partida[]" class="form-control-plaintext" value="<?= isset($row->dsc_partida) ? $row->dsc_partida : '' ?>">
                                </td>

                                <!-- IMPORTE -->
                                <td style="vertical-align: top;">
                                    <input type="text" name="importe[]" class="form-control-plaintext input-importe" value="<?= isset($row->importe) ? number_format($row->importe, 2, '.', ',') : '' ?>" placeholder="$0.00">
                                    
                                    <!-- File Inputs -->
                                    <div class="mt-2 text-center">
                                        <!-- PDF Button -->
                                        <label for="pdf_pt_<?= $index ?>" class="file-upload-btn btn-pdf <?= !empty($row->pdf) ? 'btn-loaded' : '' ?>">
                                            <i class="feather icon-file-text"></i> PDF
                                        </label>
                                        <input type="file" id="pdf_pt_<?= $index ?>" name="pdf_pt_<?= $index ?>[]" class="d-none input-pdf" accept=".pdf" onchange="updateFileName(this)">
                                        <input type="hidden" name="pdf_current_<?= $index ?>[]" value="<?= isset($row->pdf) ? $row->pdf : '' ?>">
                                        <span class="file-name-display" id="name_pdf_pt_<?= $index ?>"></span>

                                        <!-- XML Button -->
                                        <label for="xml_pt_<?= $index ?>" class="file-upload-btn btn-xml <?= !empty($row->xml) ? 'btn-loaded' : '' ?>">
                                            <i class="feather icon-code"></i> XML
                                        </label>
                                        <input type="file" id="xml_pt_<?= $index ?>" name="xml_pt_<?= $index ?>[]" class="d-none input-xml" accept=".xml" onchange="updateFileName(this)">
                                        <input type="hidden" name="xml_current_<?= $index ?>[]" value="<?= isset($row->xml) ? $row->xml : '' ?>">
                                        <span class="file-name-display" id="name_xml_pt_<?= $index ?>"></span>
                                        
                                        <input type="hidden" name="row_index[]" value="<?= $index ?>">
                                    </div>

                                    <!-- Commission Field (Refactored) -->
                                    <div class="commission-container mt-2 text-center">
                                        <input type="hidden" name="comision[]" class="commission-value" value="<?= isset($row->comision) ? $row->comision : '' ?>">
                                        <button type="button" class="btn btn-sm <?= (isset($row->comision) && !empty($row->comision)) ? 'btn-loaded' : 'btn-secondary' ?> btn-commission" onclick="editComision(this)" title="Comisión / Evento">
                                            <i class="feather icon-map-pin"></i> Comisión
                                        </button>

                                        <!-- Concepto Gasto Field -->
                                        <input type="hidden" name="concepto_gasto[]" class="concepto-gasto-value" value="<?= isset($row->concepto_gasto) ? $row->concepto_gasto : '' ?>">
                                        <button type="button" class="btn btn-sm <?= (isset($row->concepto_gasto) && !empty($row->concepto_gasto)) ? 'btn-loaded' : 'btn-secondary' ?> btn-concepto-gasto ms-1" onclick="editConcepto(this)" title="Concepto Gasto">
                                            <i class="feather icon-list"></i> Concepto
                                        </button>

                                        <!-- Fechas Field -->
                                        <input type="hidden" name="fechas[]" class="fechas-value" value="<?= isset($row->fechas) ? $row->fechas : '' ?>">
                                        <button type="button" class="btn btn-sm <?= (isset($row->fechas) && !empty($row->fechas)) ? 'btn-loaded' : 'btn-secondary' ?> btn-fechas ms-1" onclick="editFechas(this)" title="Fechas">
                                            <i class="feather icon-calendar"></i> Fechas
                                        </button>
                                    </div>

                                    <?php if($index > 0): // Botón eliminar para filas extra ?>
                                        <button type="button" class="btn btn-sm btn-danger mt-1 btn-remove-row" style="padding: 0px 5px;">&times;</button>
                                    <?php endif; ?>
                                </td>

                                <!-- OBSERVACIONES / PROVIDER DATA (Solo en la primera fila con rowspan) -->
            
                                <td class="text-left" style="padding: 15px; vertical-align: top;" rowspan="" id="tdProvider">
                                    <div class="label-bold mb-2">DATOS DEL PROVEEDOR NACIONAL</div>
                                    
                                    <div class="provider-section">
                                        <!-- No. Proveedor -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2 align-self-start">No. PROVEEDOR:</span>
                                            <input type="text" id="no_proveedor" name="no_proveedor" class="flex-grow-1" placeholder="103156" value="<?= isset($reserva->no_proveedor) ? $reserva->no_proveedor : (isset($proveedor->no_proveedor) ? $proveedor->no_proveedor : '') ?>">
                                        </div>

                                        <!-- RFC -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">RFC:</span>
                                            <input type="text" id="rfc_proveedor" name="rfc_proveedor" class="flex-grow-1" placeholder="N0011499A" value="<?= isset($reserva->rfc) ? $reserva->rfc :  '' ?>">
                                        </div>

                                        <!-- Nombre Proveedor (Repetido en imagen) -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2 align-self-start">NOMBRE:</span>
                                            <input type="text" id="nombre_proveedor_2" name="nombre_proveedor_2" class="flex-grow-1" placeholder="ORGANIZACION MUNDIAL DEL TURISMO" value="<?= isset($reserva->razon_social) ? $reserva->razon_social :'' ?>">
                                        </div>

                                        <!-- No. Cuenta -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">NO. CUENTA:</span>
                                            <input type="text" id="no_cuenta" name="no_cuenta" class="flex-grow-1" placeholder="610081057237000168" value="<?= isset($reserva->no_cuenta) ? $reserva->no_cuenta : '' ?>">
                                        </div>

                                        <!-- Banco -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">BANCO:</span>
                                            <input type="text" id="banco" name="banco" class="flex-grow-1" placeholder="BANCO SANTANDER (MEXICO), S.A." value="<?= isset($reserva->banco) ? $reserva->banco : '' ?>">
                                        </div>

                                        <!-- CLABE -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">CLABE:</span>
                                            <input type="text" id="clabe" name="clabe" class="flex-grow-1" placeholder="012180001057237008" value="<?= isset($reserva->clabe) ? $reserva->clabe : '' ?>">
                                        </div>

                                    </div>
                                </td>
                          
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                           
                             <tr>
                                 <td colspan="4" class="text-left">
                                     
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
                                        <span class="label-bold me-2">No. CONTRATO y/o CONVENIO: &nbsp;</span>
                                        <input type="text" name="no_convenio"  class="form-control-plaintext text-left w-50" value="<?= isset($reserva->no_convenio) ? $reserva->no_convenio : '' ?>" placeholder="CONVENIO 2023-001">
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="label-bold me-2">No. RESERVA: &nbsp;</span>
                                        <input type="text" name="no_reserva_visual"  class="form-control-plaintext text-left w-50" value="<?= isset($reserva->no_reserva) ? $reserva->no_reserva : '' ?>" placeholder="4798053">
                                    </div>
                                    <div class="d-flex align-items-center mt-2">
                                        <span class="label-bold me-2">CONCEPTO SOLICITUD:&nbsp; </span>
                                        <textarea type="text" name="concepto" class="form-control-plaintext text-left w-100" rows="2" placeholder="Concepto de la solicitud..." value=""><?= isset($objeto) ? $objeto : '' ?></textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="70%" class="text-right">
                                     <!-- Espacio vacío alineado a la derecha del importe superior -->
                                </td>
                                <td width="30%" class="text-center" style="border-top: 1px solid #000;">
                                    <input type="text" name="importe_total_num" class="form-control-plaintext input-importe font-weight-bold" value="$<?= isset($monto) ? $monto : '' ?>" placeholder="$0.00" readonly>
                                </td>
                            </tr>
                             <tr>
                                <td colspan="4" class="text-center font-weight-bold">
                                    <input type="text" name="importe_letra" class="form-control-plaintext" value="<?= isset($monto_texto) ? $monto_texto : '' ?>" placeholder="Sesenta y dos mil seiscientos diez pesos 00/100 M.N." readonly>
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
                                    <select name="nombre_autoriza" id="nombre_autoriza">
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($usuarios as $user): ?>
                                            <option value="<?= $user->nombre_completo ?>" > 
                                                <?= $user->nombre_completo ?>
                                            </option>
                                        <?php endforeach; ?>
                                      
                                    </select>
                                   
                                    <input type="text" name="cargo_autoriza" class="form-control-plaintext small" value="<?= isset($registro_pt->cargo_autoriza) ? $registro_pt->cargo_autoriza : '' ?>">
                                    
                                </td>
                                <td class="align-bottom pb-3">
                                    <select name="nombre_responsable_1" id="nombre_responsable_1">
                                          <option value="">Seleccione una opción</option>
                                        <?php foreach ($usuarios as $user): ?>
                                            <option value="<?= $user->nombre_completo ?>" > 
                                                <?= $user->nombre_completo ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <input type="text" name="cargo_responsable_1" class="form-control-plaintext small" value="<?= isset($registro_pt->cargo_responsable) ? $registro_pt->cargo_responsable : '' ?>">
                                </td>
                            </tr>
                             <tr>
                                <td colspan="2" style="border: none;"></td>
                                <td class="bg-grey font-weight-bold border">RESPONSABLE DEL PROYECTO</td>
                            </tr>
                             <tr>
                                <td colspan="2" style="border: none;"></td>
                                <td class="align-bottom pb-3 border" style="height: 100px;">
                                     <select name="nombre_responsable_2" id="nombre_responsable_2">
                                        <option value="">Seleccione una opción</option>
                                         <?php foreach ($usuarios as $user): ?>
                                            <option value="<?= $user->nombre_completo ?>" > 
                                                <?= $user->nombre_completo ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                  
                                    <input type="text" name="cargo_responsable_2" class="form-control-plaintext small" value="<?= isset($registro_pt->cargo_responsable_2) ? $registro_pt->cargo_responsable_2 : '' ?>">
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
    $(function() {
        $('#folio').select2({
            placeholder: 'Selecciona una opción',
            width: 'resolve'
        });
        $('#nombre_autoriza, #nombre_responsable_1, #nombre_responsable_2').select2({
            placeholder: 'Selecciona una opción',
            width: 'resolve'
        });
    });
</script>
