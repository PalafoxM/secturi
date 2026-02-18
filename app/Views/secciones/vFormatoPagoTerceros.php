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
</style>



<div class="page-wrapper">
    <?php
    // Helper to extract no_consecutivo from full folio string if editing
    // Expected format: PT [PREFIX] [NUMBER]/[YEAR]
    if (isset($registro_pt->no_consecutivo) && (!isset($no_consecutivo) || empty($no_consecutivo))) {
        // Try to match the number before the slash part
        if (preg_match('/([0-9]+)\/[0-9]{4}$/', $registro_pt->no_consecutivo, $matches)) {
            $no_consecutivo = $matches[1];
        } else {
             // Fallback: try to find the last occurring number logic if format differs?
             // For now assume the standard format described in JS.
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
                    <input type="hidden" name="id_reserva" value="<?= $id_reserva ?>">
                    <input type="hidden" name="editar" value="<?= $editar ?>">
                    <?php if($editar == 1): ?>
                    <input type="hidden" name="id_formulario_pt" value="<?= isset($registro_pt->id_formulario_pt) ? $registro_pt->id_formulario_pt : '' ?>">
                    <?php endif; ?>

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
                                    <input type="date" name="fecha_tramite" class="form-control-plaintext" value="<?= isset($registro_pt->fecha_tramite) ? date('Y-m-d', strtotime($registro_pt->fecha_tramite)) : date('Y-m-d') ?>">
                                </td>
                                <td>
                                    <select id="folio" name="folio" class="folio">
                                        <?php foreach($cat_area as $area): ?>
                                            <option value="<?= $area->id_area ?>" <?= $area->id_area == $id_area ? 'selected' : '' ?>><?= $area->prefijo ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="no_consecutivo" autocomplete="off" class="form-control-plaintext" value="<?= isset($no_consecutivo) ? $no_consecutivo : '' ?>" placeholder="001/2026">
                                
                                    <spam id="folio_error" class="text-success"> <?= isset($registro_pt->no_consecutivo) ? $registro_pt->no_consecutivo : '' ?></spam>
                              
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
                                <th style="width: 15%;">PROYECTO META</th>
                                <th style="width: 10%;">No. PARTIDA</th>
                                <th style="width: 15%;">IMPORTE</th>
                                <th style="width: 50%;">OBSERVACIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $rows = isset($periodo_factura_rows) && !empty($periodo_factura_rows) ? $periodo_factura_rows : [ (object)['encabezado' => '', 'proyecto_clave' => '', 'partida_clave' => '', 'importe' => ''] ];
                                $totalRows = count($rows);
                            ?>
                            
                            <?php foreach($rows as $index => $row): ?>
                            <tr class="item-row">
                                <!-- No. COMPROBANTE -->
                                <td style="vertical-align: top;">
                                    <input type="text" name="no_comprobante[]" class="form-control-plaintext mb-2" value="<?= isset($row->no_comprobante) ? $row->no_comprobante : '' ?>" placeholder="3220">
                                </td>

                                <!-- PROYECTO META -->
                                <td style="vertical-align: top;">
                                    <select id="proyecto_meta" name="proyecto_meta[]" class="form-control-plaintext">
                                        <?php foreach($cat_proyecto as $proyecto): ?>
                                            <option value="<?= $proyecto->proyecto ?>"><?= $proyecto->proyecto ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>

                                <!-- No. PARTIDA -->
                                <td style="vertical-align: top;">
                                    <select id="partida" name="no_partida[]" class="form-control-plaintext">
                                        <?php foreach($cat_partida as $partida): ?>
                                            <option value="<?= $partida->cuenta_cable ?>"><?= $partida->cuenta_cable ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>

                                <!-- IMPORTE -->
                                <td style="vertical-align: top;">
                                    <input type="text" name="importe[]" class="form-control-plaintext input-importe" value="<?= isset($row->importe) ? $row->importe : '' ?>" placeholder="$0.00">
                                    
                                    <!-- File Inputs -->
                                    <div class="mt-2 text-center">
                                        <!-- PDF Button -->
                                        <label for="pdf_pt_<?= $index ?>" class="file-upload-btn btn-pdf">
                                            <i class="feather icon-file-text"></i> PDF
                                        </label>
                                        <input type="file" id="pdf_pt_<?= $index ?>" name="pdf_pt_<?= $index ?>[]" class="d-none input-pdf" accept=".pdf" onchange="updateFileName(this)">
                                        <span class="file-name-display" id="name_pdf_pt_<?= $index ?>"></span>

                                        <!-- XML Button -->
                                        <label for="xml_pt_<?= $index ?>" class="file-upload-btn btn-xml">
                                            <i class="feather icon-code"></i> XML
                                        </label>
                                        <input type="file" id="xml_pt_<?= $index ?>" name="xml_pt_<?= $index ?>[]" class="d-none input-xml" accept=".xml" onchange="updateFileName(this)">
                                        <span class="file-name-display" id="name_xml_pt_<?= $index ?>"></span>
                                        
                                        <input type="hidden" name="row_index[]" value="<?= $index ?>">
                                    </div>

                                    <!-- Commission Field (Refactored) -->
                                    <div class="commission-container mt-2 text-center">
                                        <input type="hidden" name="comision[]" class="commission-value" value="<?= isset($row->comision) ? $row->comision : '' ?>">
                                        <button type="button" class="btn btn-sm <?= (isset($row->comision) && !empty($row->comision)) ? 'btn-success' : 'btn-secondary' ?> btn-commission" onclick="editComision(this)" title="Comisión / Evento">
                                            <i class="feather icon-map-pin"></i> Comisión
                                        </button>

                                        <!-- Concepto Gasto Field -->
                                        <input type="hidden" name="concepto_gasto[]" class="concepto-gasto-value" value="<?= isset($row->concepto_gasto) ? $row->concepto_gasto : '' ?>">
                                        <button type="button" class="btn btn-sm <?= (isset($row->concepto_gasto) && !empty($row->concepto_gasto)) ? 'btn-success' : 'btn-secondary' ?> btn-concepto-gasto ms-1" onclick="editConcepto(this)" title="Concepto Gasto">
                                            <i class="feather icon-list"></i> Concepto
                                        </button>

                                        <!-- Fechas Field -->
                                        <input type="hidden" name="fechas[]" class="fechas-value" value="<?= isset($row->fechas) ? $row->fechas : '' ?>">
                                        <button type="button" class="btn btn-sm <?= (isset($row->fechas) && !empty($row->fechas)) ? 'btn-success' : 'btn-secondary' ?> btn-fechas ms-1" onclick="editFechas(this)" title="Fechas">
                                            <i class="feather icon-calendar"></i> Fechas
                                        </button>
                                    </div>

                                    <?php if($index > 0): // Botón eliminar para filas extra ?>
                                        <button type="button" class="btn btn-sm btn-danger mt-1 btn-remove-row" style="padding: 0px 5px;">&times;</button>
                                    <?php endif; ?>
                                </td>

                                <!-- OBSERVACIONES / PROVIDER DATA (Solo en la primera fila con rowspan) -->
                                <?php if($index === 0): ?>
                                <td class="text-left" style="padding: 15px; vertical-align: top;" rowspan="<?= $totalRows ?>" id="tdProvider">
                                    <div class="label-bold mb-2">DATOS DEL PROVEEDOR NACIONAL</div>
                                    
                                    <div class="provider-section">
                                        <!-- Organization Name -->
                                        <div class="mb-2">
                                            <select id="nombre_proveedor_1" name="nombre_proveedor_1" class="form-control-plaintext select2" placeholder="ORGANIZACION MUNDIAL DEL TURISMO">
                                                <option value="">Seleccione un proveedor</option>
                                                <?php 
                                                    // Ensure the selected provider is always an option, even if not in the initial loop
                                                    if(isset($proveedor) && !empty($proveedor->id_proveedor)): 
                                                ?>
                                                    <option value="<?= $proveedor->id_proveedor ?>" selected>
                                                        <?= $proveedor->razon_social ?>
                                                    </option>
                                                <?php endif; ?>

                                                <?php foreach ($proveedores as $p) { 
                                                    // Skip if it's the same as the one we just added manually
                                                    if(isset($proveedor) && $proveedor->id_proveedor == $p->id_proveedor) continue; 
                                                ?>
                                                    <option value="<?= $p->id_proveedor ?>">
                                                        <?= $p->razon_social ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <!-- No. Proveedor -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2 align-self-start">No. PROVEEDOR:</span>
                                            <input type="text" id="no_proveedor" name="no_proveedor" class="flex-grow-1" placeholder="103156" value="<?= isset($registro_pt->no_proveedor) ? $registro_pt->no_proveedor : (isset($proveedor->no_proveedor) ? $proveedor->no_proveedor : '') ?>">
                                        </div>

                                        <!-- RFC -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">RFC:</span>
                                            <input type="text" id="rfc_proveedor" name="rfc_proveedor" class="flex-grow-1" placeholder="N0011499A" value="<?= isset($registro_pt->rfc_proveedor) ? $registro_pt->rfc_proveedor : (isset($proveedor->rfc) ? $proveedor->rfc : '') ?>">
                                        </div>

                                        <!-- Nombre Proveedor (Repetido en imagen) -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2 align-self-start">NOMBRE:</span>
                                            <input type="text" id="nombre_proveedor_2" name="nombre_proveedor_2" class="flex-grow-1" placeholder="ORGANIZACION MUNDIAL DEL TURISMO" value="<?= isset($registro_pt->nombre_proveedor_2) ? $registro_pt->nombre_proveedor_2 : (isset($proveedor->razon_social) ? $proveedor->razon_social : '') ?>">
                                        </div>

                                        <!-- No. Cuenta -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">NO. CUENTA:</span>
                                            <input type="text" id="no_cuenta" name="no_cuenta" class="flex-grow-1" placeholder="610081057237000168" value="<?= isset($registro_pt->no_cuenta) ? $registro_pt->no_cuenta : (isset($proveedor_banco->no_cuenta) ? $proveedor_banco->no_cuenta : (isset($proveedor_banco->cuenta) ? $proveedor_banco->cuenta : '')) ?>">
                                        </div>

                                        <!-- Banco -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">BANCO:</span>
                                            <select id="banco" name="banco" class="form-control-plaintext flex-grow-1">
                                                <option value="">Seleccione un banco</option>
                                                <?php 
                                                    $selectedBanco = isset($registro_pt->banco) ? $registro_pt->banco : (isset($proveedor_banco->banco) ? $proveedor_banco->banco : '');
                                                ?>
                                                <?php if($selectedBanco): ?>
                                                    <option value="<?= $selectedBanco ?>" selected><?= $selectedBanco ?></option>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <!-- CLABE -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">CLABE:</span>
                                            <input type="text" id="clabe" name="clabe" class="flex-grow-1" placeholder="012180001057237008" value="<?= isset($registro_pt->clabe) ? $registro_pt->clabe : (isset($proveedor_banco->clabe) ? $proveedor_banco->clabe : '') ?>">
                                        </div>

                                    </div>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                           
                             <tr>
                                 <td colspan="4" class="text-left">
                                     <button type="button" class="btn btn-info btn-sm" id="btnAddRow">+ Agregar Fila</button>
                                 </td>
                                 <td colspan="4" class="text-left">
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
                                        <input type="text" name="no_convenio" readonly class="form-control-plaintext text-left w-50" value="<?= isset($registro_pt->no_convenio) ? $registro_pt->no_convenio : $no_convenio ?>">
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="label-bold me-2">No. RESERVA:</span>
                                        <input type="text" name="no_reserva_visual" readonly class="form-control-plaintext text-left w-50" value="<?= isset($registro_pt->no_reserva) ? $registro_pt->no_reserva : $no_reserva ?>" placeholder="4798053">
                                    </div>
                                    <div class="d-flex align-items-center mt-2">
                                        <span class="label-bold me-2">CONCEPTO SOLICITUD:</span>
                                        <input type="text" name="concepto" class="form-control-plaintext text-left w-50" rows="2" placeholder="Concepto de la solicitud..." value="<?= isset($registro_pt->concepto) ? $registro_pt->concepto : '' ?>">
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
                                    <select name="nombre_autoriza" id="nombre_autoriza">
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <?php if(in_array($usuario->id_usuario, [95, 105])): ?>
                                            <option value="<?= $usuario->nombre_completo ?>" <?= isset($registro_pt->nombre_autoriza) ? ($registro_pt->nombre_autoriza == $usuario->nombre_completo ? 'selected' : '') : '' ?>><?= $usuario->nombre_completo ?></option>
                                            <?php endif ?>
                                        <?php endforeach; ?>
                                    </select>
                                   
                                    <input type="text" name="cargo_autoriza" class="form-control-plaintext small" value="<?= isset($registro_pt->cargo_autoriza) ? $registro_pt->cargo_autoriza : '' ?>">
                                    
                                </td>
                                <td class="align-bottom pb-3">
                                    <select name="nombre_responsable_1" id="nombre_responsable_1">
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <?php if(in_array($usuario->id_usuario, [152, 40, 105,18, 99, 120 ])): ?>
                                            <option value="<?= $usuario->nombre_completo ?>" <?= isset($registro_pt->nombre_responsable) ? ($registro_pt->nombre_responsable == $usuario->nombre_completo ? 'selected' : '') : '' ?>><?= $usuario->nombre_completo ?></option>
                                            <?php endif ?>
                                        <?php endforeach; ?>
                                         <option value="NO APLICA" <?= isset($registro_pt->nombre_responsable) ? ($registro_pt->nombre_responsable == 'NO APLICA' ? 'selected' : '') : '' ?>>NO APLICA</option>
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
                                        <?php foreach ($usuarios as $usuario): ?>
                                         
                                            <option value="<?= $usuario->nombre_completo ?>" <?= isset($registro_pt->nombre_responsable_2) ? ($registro_pt->nombre_responsable_2 == $usuario->nombre_completo ? 'selected' : '') : '' ?>><?= $usuario->nombre_completo ?></option>
                                        
                                        <?php endforeach; ?>
                                         <option value="NO APLICA" <?= isset($registro_pt->nombre_responsable_2) ? ($registro_pt->nombre_responsable_2 == 'NO APLICA' ? 'selected' : '') : '' ?>>NO APLICA</option>
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

    // Inject PHP data for Select2
    var cat_proyecto = <?= json_encode($cat_proyecto) ?>;
    var cat_partida = <?= json_encode($cat_partida) ?>;
    var usuarios = <?= json_encode($usuarios) ?>;
    
    var globalRowIndex = <?= $totalRows ?>;

      $('#folio,#proyecto_meta,#partida,#nombre_autoriza,#nombre_responsable_1,#nombre_responsable_2').select2({
          placeholder: "Selecciona una opción",
          allowClear: true,
          width: 'resolve'
      });

      // Function to update cargo based on selected user
      function updateCargo(selectId, inputName) {
          var selectedName = $(selectId).val();
          var cargoInput = $('input[name="' + inputName + '"]');
          
          if (selectedName && selectedName !== "NO APLICA") {
              var user = usuarios.find(function(u) {
                  return u.nombre_completo === selectedName;
              });
              
              if (user) {
                  cargoInput.val(user.dsc_puesto);
              } else {
                  // Keep existing or clear? User might type something else if Select2 allows tagging (it doesn't here mostly).
                  // If not found in list (e.g. initial load might have text not in list?), leave it.
                  // But here we are selecting FROM list.
                  cargoInput.val(''); 
              }
          } else if (selectedName === "NO APLICA") {
               cargoInput.val("NO APLICA");
          } else {
              cargoInput.val('');
          }
      }

      // Listeners for Job Title Updates
      $('#nombre_autoriza').on('change', function() {
          updateCargo('#nombre_autoriza', 'cargo_autoriza');
      });

      $('#nombre_responsable_1').on('change', function() {
          updateCargo('#nombre_responsable_1', 'cargo_responsable_1');
      });

      $('#nombre_responsable_2').on('change', function() {
          updateCargo('#nombre_responsable_2', 'cargo_responsable_2');
      });
      $('#nombre_proveedor_1').select2({
          placeholder: "Busque un proveedor...",
          allowClear: true,
          width: 'resolve',
          minimumInputLength: 3, // Start searching after 3 chars
          ajax: {
              url: '<?= base_url() ?>index.php/Principal/buscarProveedorSelect2',
              dataType: 'json',
              delay: 250,
              data: function (params) {
                  return {
                      term: params.term // search term
                  };
              },
              processResults: function (data) {
                  return {
                      results: $.map(data, function(item) {
                          return {
                              id: item.id_proveedor,
                              text: item.razon_social + ' (' + item.rfc + ')'
                          }
                      })
                  };
              },
              cache: true
          }
      });
      
      // Global variable to store current provider banks
      var currentProviderBanks = [];

      // AJAX to fetch provider details
      $('#nombre_proveedor_1').on('change', function() {
          var id_proveedor = $(this).val();
          if(id_proveedor) {
              $.ajax({
                  url: '<?= base_url() ?>index.php/Principal/getProveedor',
                  type: 'POST',
                  data: { id_proveedor: id_proveedor },
                  dataType: 'json',
                  success: function(response) {
                      if(!response.error) {
                          var proveedor = response.data.proveedor;
                          var bancos = response.data.proveedor_banco;
                          currentProviderBanks = bancos || []; // Store banks

                          $('#no_proveedor').val(proveedor.no_proveedor);
                          $('#rfc_proveedor').val(proveedor.rfc);
                          $('#nombre_proveedor_2').val(proveedor.razon_social);

                          // Populate Bank Dropdown
                          var $bancoSelect = $('#banco');
                          $bancoSelect.empty();
                          $bancoSelect.append('<option value="">Seleccione un banco</option>');

                          if(bancos && bancos.length > 0) {
                              bancos.forEach(function(banco) {
                                  $bancoSelect.append(`<option value="${banco.banco}">${banco.banco} - ${banco.no_cuenta}</option>`);
                              });
                              
                              // Automatically select the first one and trigger change
                              $bancoSelect.val(bancos[0].banco).trigger('change');
                          } else {
                              // Clear dependent fields if no banks
                              $('#no_cuenta').val('');
                              $('#clabe').val('');
                          }
                      } else {
                          console.log(response.respuesta);
                      }
                  },
                  error: function(xhr, status, error) {
                      console.error("Error fetching provider details:", error);
                  }
              });
          } else {
              // Clear fields if no provider selected
              $('#no_proveedor').val('');
              $('#rfc_proveedor').val('');
              $('#nombre_proveedor_2').val('');
              $('#no_cuenta').val('');
              $('#banco').empty().append('<option value="">Seleccione un banco</option>');;
              $('#clabe').val('');
              currentProviderBanks = [];
          }
      });

      // Handle Bank Selection Change
      $('#banco').on('change', function() {
          var selectedBancoName = $(this).val();
          if (selectedBancoName && currentProviderBanks.length > 0) {
              var selectedBank = currentProviderBanks.find(function(b) {
                  return b.banco === selectedBancoName;
              });

              if (selectedBank) {
                  $('#no_cuenta').val(selectedBank.no_cuenta);
                  $('#clabe').val(selectedBank.clabe);
              }
          } else {
               $('#no_cuenta').val('');
               $('#clabe').val('');
          }
      });



      // Concatenate Folio and Consecutivo
      function updateFolio() {
          var prefix = $('#folio option:selected').text();
          var consecutivo = $('input[name="no_consecutivo"]').val();
          if(prefix && consecutivo) {
              $('#folio_error').text('PT '+prefix + '' + consecutivo+'/2026');
              $('#folioCompleto').val('PT '+prefix + '' + consecutivo+'/2026');
          } else {
              $('#folio_error').text('');
          }
      }

      $('#folio').on('change', updateFolio);
      $('input[name="no_consecutivo"]').on('input change', updateFolio);
      
      // Initialize validation/concatenation logic
      updateFolio();

    $(document).ready(function() {

        // Importe formatting
        $(document).on('blur', '.input-importe', function() {
            let val = $(this).val().replace(/[^0-9.]/g, ''); 
            if(val) {
                $(this).val(parseFloat(val).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            }
            calcularTotal();
        });

        // Add Row
        $('#btnAddRow').click(function(){
            globalRowIndex++; // Increment index for next row

            // Build Options for Proyecto
            var optionsProyecto = '<option value="">Seleccione...</option>';
            cat_proyecto.forEach(function(item) {
                optionsProyecto += `<option value="${item.proyecto}">${item.proyecto}</option>`;
            });

            // Build Options for Partida
            var optionsPartida = '<option value="">Seleccione...</option>';
            cat_partida.forEach(function(item) {
                optionsPartida += `<option value="${item.cuenta_cable}">${item.cuenta_cable}</option>`;
            });

            var newRow = `
                <tr class="item-row">
                     <td style="vertical-align: top;">
                        <input type="text" name="no_comprobante[]" class="form-control-plaintext mb-2" placeholder="3220">
                    </td>
                    <td style="vertical-align: top;">
                        <select name="proyecto_meta[]" class="form-control-plaintext select2-dynamic" style="width: 100%;">
                            ${optionsProyecto}
                        </select>
                    </td>
                    <td style="vertical-align: top;">
                        <select name="no_partida[]" class="form-control-plaintext select2-dynamic" style="width: 100%;">
                            ${optionsPartida}
                        </select>
                    </td>
                    <td style="vertical-align: top;">
                        <input type="text" name="importe[]" class="form-control-plaintext input-importe" placeholder="$0.00">
                        
                        <div class="mt-2 text-center">
                            <!-- PDF Button -->
                            <label for="pdf_pt_${globalRowIndex}" class="file-upload-btn btn-pdf">
                                <i class="feather icon-file-text"></i> PDF
                            </label>
                            <input type="file" id="pdf_pt_${globalRowIndex}" name="pdf_pt_${globalRowIndex}[]" class="d-none input-pdf" accept=".pdf" onchange="updateFileName(this)">
                            <span class="file-name-display" id="name_pdf_pt_${globalRowIndex}"></span>

                            <!-- XML Button -->
                            <label for="xml_pt_${globalRowIndex}" class="file-upload-btn btn-xml">
                                <i class="feather icon-code"></i> XML
                            </label>
                            <input type="file" id="xml_pt_${globalRowIndex}" name="xml_pt_${globalRowIndex}[]" class="d-none input-xml" accept=".xml" onchange="updateFileName(this)">
                            <span class="file-name-display" id="name_xml_pt_${globalRowIndex}"></span>
                            
                            <input type="hidden" name="row_index[]" value="${globalRowIndex}">
                        </div>

                        <!-- Commission Field (Refactored) -->
                        <div class="commission-container mt-2 text-center">
                            <input type="hidden" name="comision[]" class="commission-value" value="">
                            <button type="button" class="btn btn-sm btn-secondary btn-commission" onclick="editComision(this)" title="Comisión / Evento">
                                <i class="feather icon-map-pin"></i> Comisión
                            </button>

                            <!-- Concepto Gasto Field -->
                            <input type="hidden" name="concepto_gasto[]" class="concepto-gasto-value" value="">
                            <button type="button" class="btn btn-sm btn-secondary btn-concepto-gasto ms-1" onclick="editConcepto(this)" title="Concepto Gasto">
                                <i class="feather icon-list"></i> Concepto
                            </button>

                            <!-- Fechas Field -->
                            <input type="hidden" name="fechas[]" class="fechas-value" value="">
                            <button type="button" class="btn btn-sm btn-secondary btn-fechas ms-1" onclick="editFechas(this)" title="Fechas">
                                <i class="feather icon-calendar"></i> Fechas
                            </button>
                        </div>

                        <button type="button" class="btn btn-sm btn-danger mt-1 btn-remove-row" style="padding: 0px 5px;">&times;</button>
                    </td>
                </tr>
            `;
            
            var $newRow = $(newRow);
            $('#pagoterceros_items_table tbody').append($newRow);
            
            // Initialize Select2 on new elements
            $newRow.find('.select2-dynamic').select2({
                placeholder: "Selecciona una opción",
                allowClear: true,
                width: 'resolve'
            });

            updateRowspan();
        });


        // Remove Row
        $(document).on('click', '.btn-remove-row', function(){
            $(this).closest('tr').remove();
            updateRowspan();
            calcularTotal();
        });

      // XML Amount Extraction
      $(document).on('change', 'input[accept=".xml"]', function(e) {
          var file = e.target.files[0];
          var $input = $(this);
          
          if (file) {
              var reader = new FileReader();
              reader.onload = function(e) {
                  try {
                      var parser = new DOMParser();
                      var xmlDoc = parser.parseFromString(e.target.result, "text/xml");
                      
                      // Try with namespace and without
                      var comprobante = xmlDoc.getElementsByTagName("cfdi:Comprobante")[0];
                      if (!comprobante) {
                          comprobante = xmlDoc.getElementsByTagName("Comprobante")[0];
                      }
                      
                      if (comprobante) {
                          var total = comprobante.getAttribute('Total');
                          if (total) {
                              // Format total
                              var floatTotal = parseFloat(total);
                              var formattedTotal = floatTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                              
                              // Find closest importe input. 
                              // Structure: td > div > input[file]. Closest td contains input[name="importe[]"]
                              var $importeInput = $input.closest('td').find('.input-importe');
                              $importeInput.val(formattedTotal);
                              
                              // Trigger calculation
                              calcularTotal();
                          }

                          // Extract Folio or UUID
                          var folio = comprobante.getAttribute('Folio');
                          if (!folio) {
                              // Try to find UUID in TimbreFiscalDigital
                              var timbre = xmlDoc.getElementsByTagName("tfd:TimbreFiscalDigital")[0];
                              if (!timbre) {
                                  timbre = xmlDoc.getElementsByTagName("TimbreFiscalDigital")[0];
                              }
                              if (timbre) {
                                  folio = timbre.getAttribute('UUID');
                              }
                          }

                          if (folio) {
                              // Find closest row and then the no_comprobante input
                              // $input is in the last td of the row. 
                              var $row = $input.closest('tr');
                              var $folioInput = $row.find('input[name="no_comprobante[]"]');
                              $folioInput.val(folio);
                          }
                      }
                  } catch (err) {
                      console.error("Error parsing XML:", err);
                  }
              };
              reader.readAsText(file);
          }
      });

        function updateRowspan(){
            var rowCount = $('#pagoterceros_items_table tbody tr.item-row').length;
            $('#tdProvider').attr('rowspan', rowCount);
        }

        function calcularTotal() {
            var total = 0;
            $('.input-importe').each(function() {
                 // Skip the total input itself if it has the class (it shouldn't, but check names)
                 if($(this).attr('name') == 'importe_total_num') return;

                 var val = $(this).val().replace(/,/g, '');
                 if (val && !isNaN(val)) {
                     total += parseFloat(val);
                 }
            });
            $('input[name="importe_total_num"]').val(total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            
            // Here you could convert to letters via AJAX if needed, or leave blank for user
            if(total > 0){
                 $('input[name="importe_letra"]').val(numeroALetras(total));
            } else {
                 $('input[name="importe_letra"]').val('');
            }
        }

        function numeroALetras(amount) {
            var data = {
                unidades: ['', 'UN ', 'DOS ', 'TRES ', 'CUATRO ', 'CINCO ', 'SEIS ', 'SIETE ', 'OCHO ', 'NUEVE '],
                decenas: ['', 'DIEZ ', 'VEINTE ', 'TREINTA ', 'CUARENTA ', 'CINCUENTA ', 'SESENTA ', 'SETENTA ', 'OCHENTA ', 'NOVENTA '],
                diez: ['DIEZ ', 'ONCE ', 'DOCE ', 'TRECE ', 'CATORCE ', 'QUINCE ', 'DIECISEIS ', 'DIECISIETE ', 'DIECIOCHO ', 'DIECINUEVE '],
                centenas: ['', 'CIENTO ', 'DOSCIENTOS ', 'TRESCIENTOS ', 'CUATROCIENTOS ', 'QUINIENTOS ', 'SEISCIENTOS ', 'SETECIENTOS ', 'OCHOCIENTOS ', 'NOVECIENTOS '],
                unidad: ['', 'UN ', 'DOS ', 'TRES ', 'CUATRO ', 'CINCO ', 'SEIS ', 'SIETE ', 'OCHO ', 'NUEVE '],
            };

            var pesos = Math.floor(amount);
            var centavos = Math.round((amount - pesos) * 100);
            var letras = '';

            if (pesos === 0) {
                letras = 'CERO ';
            } else if (pesos === 1) {
                letras = 'UN ';
            } else {
                letras = convertirGrupo(pesos);
            }

            letras += 'PESOS ';

            if (centavos < 10) {
                centavos = '0' + centavos;
            }

            letras += centavos + '/100 M.N.';

            return letras.trim();
        }

        function convertirGrupo(n) {
            var output = '';
            var millones = Math.floor(n / 1000000);
            var miles = Math.floor((n % 1000000) / 1000);
            var unidades = n % 1000;

            if (millones > 0) {
                if (millones === 1) {
                    output += 'UN MILLON ';
                } else {
                    output += convertirCentenas(millones) + ' MILLONES ';
                }
            }

            if (miles > 0) {
                if (miles === 1) {
                    output += 'MIL ';
                } else {
                    output += convertirCentenas(miles) + ' MIL ';
                }
            }

            if (unidades > 0) {
                output += convertirCentenas(unidades);
            }

            return output;
        }

        function convertirCentenas(n) {
             var output = '';
             var c = Math.floor(n / 100);
             var resto = n % 100;
             
             if (c === 1) {
                 if (resto > 0) output += 'CIENTO ';
                 else output += 'CIEN ';
             } else if (c > 1) {
                 var centenasArr = ['', 'CIENTO ', 'DOSCIENTOS ', 'TRESCIENTOS ', 'CUATROCIENTOS ', 'QUINIENTOS ', 'SEISCIENTOS ', 'SETECIENTOS ', 'OCHOCIENTOS ', 'NOVECIENTOS '];
                 output += centenasArr[c];
             }
             
             if (resto > 0) {
                 output += convertirDecenas(resto);
             }
             
             return output;
        }

        function convertirDecenas(n) {
            var output = '';
            var d = Math.floor(n / 10);
            var u = n % 10;
            var unidadesArr = ['', 'UN ', 'DOS ', 'TRES ', 'CUATRO ', 'CINCO ', 'SEIS ', 'SIETE ', 'OCHO ', 'NUEVE '];
            var decenasArr = ['', 'DIEZ ', 'VEINTE ', 'TREINTA ', 'CUARENTA ', 'CINCUENTA ', 'SESENTA ', 'SETENTA ', 'OCHENTA ', 'NOVENTA '];
            var diezArr = ['DIEZ ', 'ONCE ', 'DOCE ', 'TRECE ', 'CATORCE ', 'QUINCE ', 'DIECISÉIS ', 'DIECISIETE ', 'DIECIOCHO ', 'DIECINUEVE '];

            if (d === 0) {
                output += unidadesArr[u];
            } else if (d === 1) {
                output += diezArr[u];
            } else if (d === 2) {
                 if (u === 0) output += 'VEINTE ';
                 else output += 'VEINTI' + unidadesArr[u].trim() + ' '; // VEINTIUN? VEINTIUNO? usually VEINTIUN for amounts.
            } else {
                output += decenasArr[d];
                if (u > 0) {
                    output += 'Y ' + unidadesArr[u];
                }
            }
            return output;
        }


        // Initial Total Calculation
        calcularTotal();

        // Auto-remove invalid class on input
        $('#formPagoTerceros').on('input change', '.is-invalid', function() {
            $(this).removeClass('is-invalid');
            if($(this).attr('name') == 'importe_total_num') calcularTotal();
        });

        $('#btnGuardarPT').click(function() {
            var isValid = true;
            
            // Validate all visible inputs that are not readonly
            $('#formPagoTerceros input[type="text"], #formPagoTerceros input[type="date"]').each(function() {
                var val = $(this).val().trim();
                // Special check for money inputs
                if ($(this).hasClass('input-importe')) {
                     val = val.replace(/[^0-9.]/g, '');
                }
                
                if (!$(this).prop('readonly') && (val === '' || val === null)) {
                    console.log('Invalid field:', $(this).attr('name'));
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Specific check for total amount
            var total = $('input[name="importe_total_num"]').val().replace(/,/g, '');
            if (total == 0 || total === '') {
                console.log('Total is 0 or empty');
                isValid = false;
                $('input[name="importe_total_num"]').addClass('is-invalid'); // Highlight total even if readonly
            } else {
                $('input[name="importe_total_num"]').removeClass('is-invalid');
            }

            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Por favor, complete todos los campos requeridos.'
                });
                return;
            }

            // Disable button and show loading spinner
            var $btn = $('#btnGuardarPT');
            var originalText = $btn.html();
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');

             var formData = new FormData(document.getElementById("formPagoTerceros"));
             
             $.ajax({
                url: "<?php echo base_url(); ?>index.php/Agregar/guardaFormatoPT",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.respuesta
                        });
                        // Re-enable button on error
                        $btn.prop('disabled', false);
                        $btn.html(originalText);
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.respuesta,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                             window.location.href = "<?php echo base_url(); ?>index.php/Inicio/ListaHojaAzul";
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado.'
                    });
                     // Re-enable button on error
                     $btn.prop('disabled', false);
                     $btn.html(originalText);
                }
            });
        });
    });

    function updateFileName(input) {
        var fileName = input.files[0] ? input.files[0].name : '';
        var displayId = 'name_' + input.id;
        $('#' + displayId).text(fileName);
        $('#' + displayId).attr('title', fileName); // Add tooltip
    }

    function editComision(btn) {
        // Find the hidden input associated with this button
        var $btn = $(btn);
        var $container = $btn.closest('.commission-container');
        var $hiddenInput = $container.find('input[name="comision[]"]');
        var currentValue = $hiddenInput.val();

        Swal.fire({
            title: 'Comisión / Evento',
            input: 'text',
            inputValue: currentValue,
            inputPlaceholder: 'Escriba el lugar de comisión...',
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                // Optional: verification if needed
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var newValue = result.value;
                $hiddenInput.val(newValue);
                
                // Visual feedback & Tooltip Update
                if(newValue && newValue.trim() !== '') {
                    $btn.removeClass('btn-secondary').addClass('btn-success');
                    $btn.attr('title', newValue); // Show value on hover
                } else {
                    $btn.removeClass('btn-success').addClass('btn-secondary');
                    $btn.attr('title', 'Comisión / Evento'); // Default tooltip
                }
            }
        });
    }

    function editConcepto(btn) {
        // Find the hidden input associated with this button
        var $btn = $(btn);
        var $container = $btn.closest('.commission-container');
        var $hiddenInput = $container.find('input[name="concepto_gasto[]"]');
        var currentValue = $hiddenInput.val();

        Swal.fire({
            title: 'Concepto Gasto',
            input: 'textarea',
            inputValue: currentValue,
            inputPlaceholder: 'Escriba el concepto del gasto...',
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                // Optional: verification if needed
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var newValue = result.value;
                $hiddenInput.val(newValue);
                
                // Visual feedback & Tooltip Update
                if(newValue && newValue.trim() !== '') {
                    $btn.removeClass('btn-secondary').addClass('btn-success');
                    $btn.attr('title', newValue); // Show value on hover
                } else {
                    $btn.removeClass('btn-success').addClass('btn-secondary');
                    $btn.attr('title', 'Concepto Gasto'); // Default tooltip
                }
            }
        });
    }
    

    function editFechas(btn) {
        // Find the hidden input associated with this button
        var $btn = $(btn);
        var $container = $btn.closest('.commission-container');
        var $hiddenInput = $container.find('input[name="fechas[]"]');
        var currentValue = $hiddenInput.val();
        
        var fechaInicio = '';
        var fechaFin = '';
        
        // Try to parse existing value if it matches our format
        if (currentValue && currentValue.indexOf(' / ') !== -1) {
             var parts = currentValue.split(' / ');
             fechaInicio = parts[0];
             fechaFin = parts[1];
        } else if (currentValue) {
            // Fallback for random text: maybe put it in comments or ignore?
            // For now, let's assume it's one date or just clear it if format doesn't match
        }

        Swal.fire({
            title: 'Fechas',
            html:
                '<div class="row">' +
                '<div class="col-md-6"><label>Inicio</label><input type="date" id="swal-input-inicio" class="form-control" value="' + fechaInicio + '"></div>' +
                '<div class="col-md-6"><label>Fin</label><input type="date" id="swal-input-fin" class="form-control" value="' + fechaFin + '"></div>' +
                '</div>',
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                var inicio = document.getElementById('swal-input-inicio').value;
                var fin = document.getElementById('swal-input-fin').value;
                
                if (!inicio || !fin) {
                    Swal.showValidationMessage('Por favor seleccione ambas fechas');
                }
                return { inicio: inicio, fin: fin };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var inicio = result.value.inicio;
                var fin = result.value.fin;
                var newValue = inicio + ' / ' + fin;
                
                $hiddenInput.val(newValue);
                
                // Visual feedback & Tooltip Update
                if(newValue && newValue.trim() !== ' / ') {
                    $btn.removeClass('btn-secondary').addClass('btn-success');
                    $btn.attr('title', newValue); // Show value on hover
                } else {
                    $btn.removeClass('btn-success').addClass('btn-secondary');
                    $btn.attr('title', 'Fechas'); // Default tooltip
                }
            }
        });
    }
</script>

