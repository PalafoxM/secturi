<style>
    .bg-black {
        background-color: #000 !important;
        color: #fff !important;
    }
    .bg-white {
        background-color: #fff !important;
        color: #000 !important;
    }
    .bg-grey {
        background-color: #e0e0e0 !important;
        color: #000 !important;
        font-weight: bold;
    }
    .form-control-plaintext {
        border: 1px solid #ccc; /* Keep border for inputs to be visible */
        padding: 4px;
        width: 100%;
        text-align: center;
        background: transparent;
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
    .label-bold {
        font-weight: bold;
        font-size: 0.9em;
    }
    
    /* Custom File Input Styles */
    .file-upload-btn {
        display: inline-block;
        padding: 4px 8px;
        cursor: pointer;
        border-radius: 4px;
        font-size: 11px;
        font-weight: bold;
        color: white;
        margin-right: 2px;
        margin-bottom: 2px;
        transition: all 0.3s ease;
        text-align: center;
        width: 60px;
    }
    
    .btn-pdf { background-color: #dc3545; border: 1px solid #dc3545; }
    .btn-pdf:hover { background-color: #c82333; }
    
    .btn-xml { background-color: #28a745; border: 1px solid #28a745; }
    .btn-xml:hover { background-color: #218838; }

    .file-name-display {
        font-size: 9px;
        color: #666;
        display: block;
        max-width: 80px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 0 auto;
    }
    
    .commission-container {
        margin-top: 5px;
        border-top: 1px dashed #ccc;
        padding-top: 2px;
    }

    /* Logo Styling */
    .header-logo {
        max-width: 250px; /* Adjust as needed */
        height: auto;
    }
    
    .header-text-right {
        text-align: right;
        font-size: 0.8rem;
    }
    
    .header-bank-info {
        font-size: 0.85rem;
        text-align: left;
    }
    .header-bank-info .row {
        margin-bottom: 2px;
    }
    .header-bank-info label {
        font-weight: bold;
        margin-bottom: 0;
    }
    .header-bank-info span, .header-bank-info input {
        font-weight: normal;
    }
    
    /* Tighten table padding */
    .table-sm td, .table-sm th {
        padding: 0.3rem;
    }

    /* Custom Icon Buttons */
    .btn-icon-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px; /* Slightly larger */
        height: 32px;
        padding: 0;
        border-radius: 4px;
        color: white !important;
        border: none;
        transition: all 0.2s;
        margin-right: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .btn-icon-custom i {
        font-size: 16px; /* Icon font size */
        margin: 0;
        color: white !important;
    }
    .btn-icon-custom:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 5px rgba(0,0,0,0.3);
    }
    .btn-comision { 
        background-color: #17a2b8 !important; /* Info Blue */
        border-color: #17a2b8 !important;
    } 
    .btn-comision:hover { background-color: #138496 !important; }

    .btn-concepto { 
        background-color: #ffc107 !important; /* Warning Orange */
        border-color: #ffc107 !important;
        color: white !important; /* Ensure white text */
    } 
    .btn-concepto:hover { background-color: #e0a800 !important; }

    .btn-fechas { 
        background-color: #6f42c1 !important; /* Purple */
        border-color: #6f42c1 !important;
    } 
    .btn-fechas:hover { background-color: #5a32a3 !important; }

    .btn-propinas { 
        background-color: #28a745 !important; /* Green */
        border-color: #28a745 !important;
    } 
    .btn-propinas:hover { background-color: #218838 !important; }
    
    .file-name-display {
        margin-bottom: 3px;
        font-size: 10px;
    }
</style>

<div class="page-wrapper">
    <?php
    // Format Consecutivo
    if (isset($registro_pt->no_consecutivo) && (!isset($no_consecutivo) || empty($no_consecutivo))) {
         if (preg_match('/([0-9]+)\/[0-9]{4}$/', $registro_pt->no_consecutivo, $matches)) {
            $no_consecutivo = $matches[1];
        }
    }
    // Set default bank values if new
    $default_bank_account = "5185913";
    $default_bank_branch = "MARFIL";
    $default_bank_clabe = "030 21051859130201 9";
    $default_bank_name = "BANCO DEL BAJÍO";
    ?>

    <!-- Page Content-->
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Formulario</a></li>
                                <li class="breadcrumb-item active">GO</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Formulario GO</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid" style="background: white; padding: 20px;">
                <form id="formPagoTerceros" enctype="multipart/form-data">
                    <input type="hidden" name="id_reserva" value="<?= $id_reserva ?>">
                    <input type="hidden" name="editar" value="<?= $editar ?>">
                    <?php if($editar == 1): ?>
                    <input type="hidden" name="id_formulario_pt" value="<?= isset($registro_pt->id_formulario_pt) ? $registro_pt->id_formulario_pt : '' ?>">
                    <?php endif; ?>

                    <!-- HEADER LAYOUT -->
                    <div class="row mb-2 align-items-end">
                        <div class="col-md-6">
                            <!-- Logo Placeholder -->
                            
                        </div>
                        <div class="col-md-6 header-text-right">
                            <div class="font-weight-bold">GOBIERNO DEL ESTADO DE GUANAJUATO</div>
                            <div class="font-weight-bold">FORMATO DE GASTO DE OPERACIÓN</div>
                            <div class="mt-2">FORMATO GO - 1 25</div> <!-- Fixed layout text -->
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                         <div class="col-md-12 text-center">
                            Relación de <span id="count_docs" class="font-weight-bold border-bottom px-2"> <?= isset($registro_pt->relacion) ? $registro_pt->relacion : '1' ?> </span> documentos que amparan un importe de <span id="header_total" class="font-weight-bold border-bottom px-2">$0.00</span>
                            <input type="hidden" name="relacion_documentos" id="relacion_documentos" value="<?= isset($registro_pt->relacion) ? $registro_pt->relacion : '1' ?>">
                        </div>
                    </div>

                    <!-- TOP SECTION: BANK INFO (Left) & RAMO INFO (Right) -->
                    <div class="row mb-0">
                        <!-- BANK INFO LEFT -->
                        <div class="col-md-6 header-bank-info">
                            <div class="mb-2">Favor de depositar a nombre de:</div>
                            <div class="font-weight-bold mb-3 pl-3">GOBIERNO DEL ESTADO DE GUANAJUATO</div>
                            
                            <div class="row">
                                <div class="col-sm-2"><label>CUENTA:</label></div>
                                <div class="col-sm-4"><input type="text" class="form-control-plaintext text-left p-0" value="<?= $default_bank_account ?>"></div>
                                <div class="col-sm-2"><label>SUCURSAL:</label></div>
                                <div class="col-sm-4"><input type="text" class="form-control-plaintext text-left p-0" value="<?= $default_bank_branch ?>"></div>
                            </div>
                            
                             <div class="row">
                                <div class="col-sm-2"><label>CLABE:</label></div>
                                <div class="col-sm-10"><input type="text" class="form-control-plaintext text-left p-0" value="<?= $default_bank_clabe ?>"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-2"><label>BANCO:</label></div>
                                <div class="col-sm-10"><input type="text" class="form-control-plaintext text-left p-0" value="<?= $default_bank_name ?>"></div>
                            </div>
                            
                            <div class="border-bottom border-dark mt-2 w-100"></div>
                        </div>

                        <!-- RAMO INFO RIGHT -->
                        <div class="col-md-6 pl-0">
                            <table class="table table-bordered text-center table-sm mb-0">
                                <thead>
                                    <tr class="bg-black">
                                        <th colspan="3" class="text-uppercase" style="color: white; font-size: 0.8rem;">RAMO O ENTIDAD REMITENTE</th>
                                    </tr>
                                    <tr class="bg-black">
                                         <th colspan="3" class="text-uppercase" style="color: white; font-size: 1rem;">21 SECRETARIA DE TURISMO E IDENTIDAD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-grey">
                                        <td style="width: 20%; font-size: 0.8rem;">DIVISIÓN</td>
                                        <td style="width: 30%; font-size: 0.8rem;">FECHA</td>
                                        <td style="width: 50%; font-size: 0.8rem;">FOLIO</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="division" class="form-control-plaintext" value="21" readonly>
                                        </td>
                                        <td>
                                            <input type="date" name="fecha_tramite" class="form-control-plaintext p-0" value="<?= isset($registro_pt->fecha_tramite) ? date('Y-m-d', strtotime($registro_pt->fecha_tramite)) : date('Y-m-d') ?>">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="mr-1 small font-weight-bold">GO</span>
                                                <select name="folio" id="folio" class="form-control-plaintext p-0 mr-1 select2" style="width: auto; max-width: 250px; font-size: 0.8rem;">
                                                    <?php foreach($cat_area as $area): ?>
                                                        <option value="<?= $area->id_area ?>" <?= $area->id_area == $id_area ? 'selected' : '' ?>><?= $area->prefijo ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <input type="text" name="no_consecutivo" autocomplete="off" class="form-control-plaintext p-0 font-weight-bold" style="width: 50px; text-align: center;" value="<?= isset($no_consecutivo) ? $no_consecutivo : '' ?>" placeholder="001">
                                                <span class="ml-1 small font-weight-bold">/2026</span>
                                            </div>
                                             <!-- Hidden Folio Completo Input -->
                                            <input type="hidden" name="folioCompleto" id="folioCompleto">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- DATA TABLE -->
                    <table id="pagoterceros_items_table" class="table table-bordered text-center table-sm">
                        <thead>
                            <tr class="bg-grey" style="font-size: 0.8rem;">
                                <th colspan="6">DATOS PROPORCIONADOS POR LA DEPENDENCIA</th>
                            </tr>
                            <tr class="bg-white" style="font-size: 0.8rem;">
                                <th colspan="4" class="border-bottom-0">REFERENCIA AL DOCUMENTO</th>
                                <th colspan="2" class="border-bottom-0">OBSERVACIONES</th>
                            </tr>
                            <tr style="font-size: 0.75rem;">
                                <th style="width: 10%;">COMPROBANTE</th>
                                <th style="width: 15%;">PROYECTO META</th>
                                <th style="width: 10%;">PARTIDA No.</th>
                                <th style="width: 15%;">IMPORTE</th>
                                <th style="width: 30%;">DATOS DEL CONTRIBUYENTE</th>
                                <th style="width: 20%;">RFC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $rows = isset($periodo_factura_rows) && !empty($periodo_factura_rows) ? $periodo_factura_rows : [ (object)['encabezado' => '', 'proyecto_clave' => '', 'partida_clave' => '', 'importe' => ''] ];
                            ?>
                            
                            <?php foreach($rows as $index => $row): ?>
                            <tr class="item-row">
                                <!-- No. COMPROBANTE -->
                                <td>
                                    <input type="text" name="no_comprobante[]" class="form-control-plaintext" value="<?= isset($row->no_comprobante) ? $row->no_comprobante : '' ?>" placeholder="Folio">
                                </td>

                                <!-- PROYECTO META -->
                                <td>
                                    <select name="proyecto_meta[]" class="form-control-plaintext select2-dynamic">
                                        <?php foreach($cat_proyecto as $proyecto): ?>
                                            <option value="<?= $proyecto->proyecto ?>" <?= (isset($row->proyecto) && $row->proyecto == $proyecto->proyecto) ? 'selected' : '' ?>><?= $proyecto->proyecto ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>

                                <!-- No. PARTIDA -->
                                <td>
                                    <select name="no_partida[]" class="form-control-plaintext select2-dynamic">
                                        <?php foreach($cat_partida as $partida): ?>
                                            <option value="<?= $partida->cuenta_cable ?>" <?= (isset($row->partida) && $row->partida == $partida->cuenta_cable) ? 'selected' : '' ?>><?= $partida->cuenta_cable ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    
                                    <!-- Viaticos Button (Moved here) -->
                                    <div class="mt-1 text-center">
                                        <input type="hidden" name="viaticos_json[]" value="<?= isset($row->viaticos_json) ? htmlspecialchars($row->viaticos_json) : '' ?>">
                                        <button type="button" class="btn btn-icon-custom btn-viaticos d-none" onclick="openViaticosModal(this)" title="Desglose Viáticos" style="background-color: #17a2b8 !important; color: white;">
                                            <i class="fas fa-users"></i>
                                        </button>
                                    </div>
                                </td>

                                <!-- IMPORTE -->
                                <td>
                                    <input type="text" name="importe[]" class="form-control-plaintext input-importe font-weight-bold" value="<?= isset($row->importe) ? $row->importe : '' ?>" placeholder="$0.00">
                                    
                                    <!-- Buttons (PDF/XML/Extras) -->
                                    <div class="mt-1 d-flex justify-content-center flex-wrap">
                                        <label for="pdf_pt_<?= $index ?>" class="file-upload-btn btn-pdf" title="Subir PDF">
                                            PDF
                                        </label>
                                        <input type="file" id="pdf_pt_<?= $index ?>" name="pdf_pt_<?= $index ?>[]" class="d-none input-pdf" accept=".pdf" onchange="updateFileName(this)">
                                        <input type="hidden" name="pdf_current_<?= $index ?>[]" value="<?= isset($row->pdf) ? $row->pdf : '' ?>">
                                        
                                        <label for="xml_pt_<?= $index ?>" class="file-upload-btn btn-xml" title="Subir XML">
                                            XML
                                        </label>
                                        <input type="file" id="xml_pt_<?= $index ?>" name="xml_pt_<?= $index ?>[]" class="d-none input-xml" accept=".xml" onchange="updateFileName(this)">
                                        <input type="hidden" name="xml_current_<?= $index ?>[]" value="<?= isset($row->xml) ? $row->xml : '' ?>">
                                        
                                        <input type="hidden" name="row_index[]" value="<?= $index ?>">
                                    </div>
                                    <div class="mt-0">
                                         <span class="file-name-display" id="name_pdf_pt_<?= $index ?>"></span>
                                         <span class="file-name-display" id="name_xml_pt_<?= $index ?>"></span>
                                    </div>
                                    
                                    <!-- Extra Buttons Container (Hidden by default or smaller) -->
                                    <div class="commission-container d-flex justify-content-center">
                                        <input type="hidden" name="comision[]" value="<?= isset($row->comision) ? $row->comision : '' ?>">
                                        <button type="button" class="btn btn-icon-custom btn-comision" onclick="editComision(this)" data-toggle="tooltip" data-placement="top" title="Comisión">
                                            <i class="fas fa-briefcase"></i>
                                        </button>

                                        <input type="hidden" name="concepto_gasto[]" value="<?= isset($row->concepto_gasto) ? $row->concepto_gasto : '' ?>">
                                        <button type="button" class="btn btn-icon-custom btn-concepto" onclick="editConcepto(this)" data-toggle="tooltip" data-placement="top" title="Concepto">
                                            <i class="fas fa-file-alt"></i>
                                        </button>

                                        <input type="hidden" name="fechas[]" value="<?= isset($row->fechas) ? $row->fechas : '' ?>">
                                        <button type="button" class="btn btn-icon-custom btn-fechas" onclick="editFechas(this)" data-toggle="tooltip" data-placement="top" title="Fechas">
                                            <i class="fas fa-calendar-alt"></i>
                                        </button>
                                        
                                        <input type="hidden" name="propinas[]" value="<?= isset($row->propinas) ? $row->propinas : '' ?>">
                                        <button type="button" class="btn btn-icon-custom btn-propinas" onclick="editPropinas(this)" data-toggle="tooltip" data-placement="top" title="Propinas">
                                            <i class="fas fa-coins"></i>
                                        </button>
                                         <?php if($index > 0): ?>
                                            <button type="button" class="btn btn-sm btn-danger py-0 px-1 ml-1 btn-remove-row">&times;</button>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- DATOS DEL CONTRIBUYENTE (Provider Select) -->
                                <td>
                                     
                                     <!-- Hidden input to store Name for display/save if needed -->
                                     <input type="text" name="proveedor_nombre[]" class="form-control-plaintext mt-1 small font-weight-bold provider-name-display" value="<?= isset($row->proveedor) ? $row->proveedor : '' ?>" placeholder="BEBIDAS PURIFICADAS" readonly>
                                     <input type="hidden" name="proveedor_id[]" class="provider-id-hidden">
                                </td>

                                <!-- RFC -->
                                <td>
                                    <input type="text" name="proveedor_rfc[]" class="form-control-plaintext" placeholder="RFC" value="<?= isset($row->rfc) ? $row->rfc : '' ?>" readonly>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                         <tfoot>
                             <tr>
                                 <td colspan="6" class="text-left">
                                     <button type="button" class="btn btn-info btn-sm" id="btnAddRow">+ Agregar Fila</button>
                                 </td>
                             </tr>
                             <tr>
                                <td colspan="6" class="text-left bg-light">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold">Concepto general:</label>
                                        <textarea name="concepto" class="form-control" rows="2" placeholder="Describa el concepto general del gasto..."><?= isset($registro_pt->concepto) ? $registro_pt->concepto : '' ?></textarea>
                                    </div>
                                </td>
                             </tr>
                        </tfoot>
                    </table>

                    <!-- TOTALS ROW -->
                    <div class="row mb-0 border border-top-0 border-dark mx-0">
                         <div class="col-8 text-right font-weight-bold p-2 border-right border-dark">
                             IMPORTE TOTAL
                         </div>
                         <div class="col-4 p-2 font-weight-bold text-center">
                             <input type="text" name="importe_total_num" class="form-control-plaintext font-weight-bold" value="" placeholder="$0.00" readonly>
                         </div>
                    </div>
                    <div class="row mb-4 border border-top-0 border-dark mx-0">
                        <div class="col-12 text-center p-2 font-weight-bold bg-light">
                             <input type="text" name="importe_letra" class="form-control-plaintext" value="" placeholder="(IMPORTE CON LETRA)" readonly>
                        </div>
                    </div>

                    <!-- SIGNATURES -->
                    <table class="table table-bordered text-center mt-4">
                        <tbody>
                            <!-- Signature Titles Row -->
                            <tr style="font-size: 0.8rem;">
                                <td width="33%" class="align-top border-bottom-0">DIRECTOR GENERAL ADMINISTRATIVO</td>
                                <td width="33%" class="align-top border-bottom-0">AUTORIZA</td>
                                <td width="33%" class="align-top border-bottom-0">RESPONSABLE DEL PROYECTO</td>
                            </tr>
                             <!-- Signature Space -->
                            <tr style="height: 100px;">
                                <td class="align-bottom pb-1 border-top-0">
                                   <input type="text" name="nombre_director_general" class="form-control-plaintext font-weight-bold small" value="L.R.I. RODRIGO GONZALEZ GUERRERO" readonly>
                                </td>
                                <td class="align-bottom pb-1 border-top-0">
                                    <select name="nombre_autoriza" id="nombre_autoriza" class="form-control-plaintext select2 mb-2">
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <?php if(in_array($usuario->id_usuario, [95, 105])): ?>
                                            <option value="<?= $usuario->nombre_completo ?>" <?= isset($registro_pt->nombre_autoriza) && $registro_pt->nombre_autoriza == $usuario->nombre_completo ? 'selected' : '' ?>><?= $usuario->nombre_completo ?></option>
                                            <?php endif ?>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td class="align-bottom pb-1 border-top-0">
                                    <select name="nombre_responsable_1" id="nombre_responsable_1" class="form-control-plaintext select2 mb-2">
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <?php if(in_array($usuario->id_usuario, [152, 40, 105,18, 99, 120])): ?>
                                            <option value="<?= $usuario->nombre_completo ?>" <?= isset($registro_pt->nombre_responsable) && $registro_pt->nombre_responsable == $usuario->nombre_completo ? 'selected' : '' ?>><?= $usuario->nombre_completo ?></option>
                                            <?php endif ?>
                                        <?php endforeach; ?>
                                         <option value="NO APLICA">NO APLICA</option>
                                    </select>
                                </td>
                            </tr>
                            
                            <!-- Signature Footer Row -->
                            <tr class="bg-white" style="font-size: 0.8rem;">
                                <td>DIRECTOR GENERAL ADMINISTRATIVO</td>
                                <td>
                                    <input type="text" name="cargo_autoriza" class="form-control-plaintext small text-center font-weight-bold" value="<?= isset($registro_pt->cargo_autoriza) ? $registro_pt->cargo_autoriza : 'DIRECTOR/A GENERAL JURÍDICO' ?>" readonly>
                                </td>
                                <td>
                                    <input type="text" name="cargo_responsable_1" class="form-control-plaintext small text-center font-weight-bold" value="<?= isset($registro_pt->cargo_responsable_1) ? $registro_pt->cargo_responsable_1 : 'RESPONSABLE DEL PROYECTO' ?>" readonly>
                                </td>
                            </tr>
                            
                             <!-- Extra row for Elizabeth Cristina Mondragón Martínez -->
                             <tr style="height: 100px;">
                                <td class="border-top-0 border-bottom-0"></td> <!-- Empty -->
                                <td class="border-top-0 border-bottom-0"></td> <!-- Empty -->
                                <td class="align-bottom pb-1 border-top-0">
                                   <select name="nombre_responsable_2" id="nombre_responsable_2" class="form-control-plaintext select2 mb-2">
                                        <option value="">Seleccione...</option>
                                        <?php 
                                        $default_resp_2 = 'AGUSTIN PALAFOX MARIN';
                                        $selected_resp_2 = isset($registro_pt->nombre_responsable_2) ? $registro_pt->nombre_responsable_2 : $default_resp_2;
                                        ?>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <option value="<?= $usuario->nombre_completo ?>" <?= $selected_resp_2 == $usuario->nombre_completo ? 'selected' : '' ?>><?= $usuario->nombre_completo ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr class="bg-white" style="font-size: 0.8rem;">
                                <td class="border-top-0"></td> <!-- Empty -->
                                <td class="border-top-0"></td> <!-- Empty -->
                                <td class="border-top-0">
                                    <input type="text" name="cargo_responsable_2" class="form-control-plaintext small text-center font-weight-bold" value="<?= isset($registro_pt->cargo_responsable_2) ? $registro_pt->cargo_responsable_2 : 'COORDINADOR/A DE RECURSOS MATERIALES Y SERVICIOS GENERALES' ?>" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancelar</button>
                            <button type="button" class="btn btn-primary" id="btnGuardarPT" onclick="saveForm();">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODALS -->

<!-- Modal Comisión -->
<div class="modal fade" id="modalComision" tabindex="-1" role="dialog" aria-labelledby="modalComisionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalComisionLabel">Editar Comisión</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <textarea id="txtComision" class="form-control" rows="4" placeholder="Ingrese la comisión..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="saveComision()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Concepto -->
<div class="modal fade" id="modalConcepto" tabindex="-1" role="dialog" aria-labelledby="modalConceptoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConceptoLabel">Editar Concepto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <textarea id="txtConcepto" class="form-control" rows="4" placeholder="Ingrese el concepto..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="saveConcepto()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Fechas -->
<div class="modal fade" id="modalFechas" tabindex="-1" role="dialog" aria-labelledby="modalFechasLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFechasLabel">Editar Fechas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Fecha Inicio</label>
                    <input type="date" id="dateInicio" class="form-control">
                </div>
                <div class="form-group">
                    <label>Fecha Fin</label>
                    <input type="date" id="dateFin" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="saveFechas()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPTS (Re-using existing CSS links but ensuring scripts block matches) -->
<!-- ... (Keep CSS links from previous file) ... -->

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

<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

<script src="<?= base_url() ?>assets/js/app.js"></script>
<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>
<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>

<!-- Modal Viaticos Global -->
<div class="modal fade" id="modalViaticos" tabindex="-1" role="dialog" aria-labelledby="modalViaticosLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalViaticosLabel">Desglose de Viáticos (Partida 3750 / 3760)</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Agregue las personas y sus montos. Al guardar, el <strong>Importe Total</strong> de la fila se actualizará automáticamente y el detalle se agregará al campo <strong>Descripción</strong>.
                </div>
                <input type="hidden" id="current_viaticos_row_index">
                <table class="table table-bordered table-sm" id="tblViaticos">
                    <thead class="thead-light">
                        <tr>
                            <th>Nombre de la Persona</th>
                            <th width="150">Monto</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be added here -->
                    </tbody>
                </table>
                <button type="button" class="btn btn-sm btn-primary" onclick="addViaticoRow()">
                    <i class="fas fa-plus"></i> Agregar Persona
                </button>
            </div>
            <div class="modal-footer">
                <div class="mr-auto font-weight-bold">
                    Total Viáticos: <span id="totalViaticosDisplay">$0.00</span>
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="saveViaticos()">Guardar y Aplicar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Data Injection
    var cat_proyecto = <?= json_encode($cat_proyecto) ?>;
    var cat_partida = <?= json_encode($cat_partida) ?>;
    var usuarios = <?= json_encode($usuarios) ?>;
    var globalRowIndex = <?= count($rows) ?>;

    $(document).ready(function() {
        
        // Initialize Base Select2
        // $('#folio').select2(); // Hidden now
        $('.select2').select2({ width: '100%' });
        
        // Initialize Row Select2s
        initRowSelect2($('body'));

        // Check initial visibility for Viaticos buttons
        $('select[name="no_partida[]"]').each(function() {
            var text = $(this).find('option:selected').text();
            if(text.includes('3750') || text.includes('3760')) {
                $(this).closest('tr').find('.btn-viaticos').removeClass('d-none');
            }
        });

        // Initialize Validation/Totals
        calcularTotal();
        updateFolioDisplay();

        // -----------------------------------------------------
        // Events
        // -----------------------------------------------------
        
        // Add Row
        $('#btnAddRow').click(function(){
            globalRowIndex++;
            var newRow = createRowHtml(globalRowIndex);
            $('#pagoterceros_items_table tbody').append(newRow);
            
            // Init Select2 on new row
            var $newRow = $('#pagoterceros_items_table tbody tr:last');
            initRowSelect2($newRow);
            
            updateCountDocs();
        });

        // Remove Row
        $(document).on('click', '.btn-remove-row', function(){
            $(this).closest('tr').remove();
            calcularTotal();
            updateCountDocs();
        });

        // Format Importe on Blur
        $(document).on('blur', '.input-importe', function() {
            let val = $(this).val().replace(/[^0-9.]/g, ''); 
            if(val) {
                $(this).val(parseFloat(val).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            }
            calcularTotal();
        });
        
        // Folio Logic
        // On input of consecutive or change of select, update the display text
         $('input[name="no_consecutivo"]').on('input', function() {
            updateFolioDisplay();
         });
         $('#folio').on('change', function() {
             updateFolioDisplay();
         });
         
        // XML Upload 
         $(document).on('change', 'input[accept=".xml"]', function(e) {
            handleXmlUpload(this);
         });
         
        // Initialize Puestos Events
        $('#nombre_autoriza').on('select2:select change', function() {
            updateCargo(this, 'cargo_autoriza');
        });
        $('#nombre_responsable_1').on('select2:select change', function() {
            updateCargo(this, 'cargo_responsable_1');
        });
        $('#nombre_responsable_2').on('select2:select change', function() {
            updateCargo(this, 'cargo_responsable_2');
        });

    });
    
    // Global variable to track which button/row triggered the modal
    var currentEditingInput = null;

    function editComision(btn) {
        // Find hidden input relative to button
        var hiddenInput = $(btn).siblings('input[name="comision[]"]');
        currentEditingInput = hiddenInput;
        
        // Fill modal
        $('#txtComision').val(hiddenInput.val());
        $('#modalComision').modal('show');
    }

    function saveComision() {
        if(currentEditingInput) {
            currentEditingInput.val($('#txtComision').val());
            $('#modalComision').modal('hide');
        }
    }

    function editConcepto(btn) {
        var hiddenInput = $(btn).siblings('input[name="concepto_gasto[]"]');
        currentEditingInput = hiddenInput;
        
        $('#txtConcepto').val(hiddenInput.val());
        $('#modalConcepto').modal('show');
    }

    function saveConcepto() {
         if(currentEditingInput) {
            currentEditingInput.val($('#txtConcepto').val());
            $('#modalConcepto').modal('hide');
        }
    }

    function editFechas(btn) {
        var hiddenInput = $(btn).siblings('input[name="fechas[]"]');
        currentEditingInput = hiddenInput;
        
        // Parse existing value if any. Format assumed: "Del YYYY-MM-DD al YYYY-MM-DD" or just text
        var val = hiddenInput.val();
        $('#dateInicio').val('');
        $('#dateFin').val('');
        
        if(val) {
            // Try simple regex to extract dates
            // Match pattern like "YYYY-MM-DD"
            var dates = val.match(/\d{4}-\d{2}-\d{2}/g);
            if(dates && dates.length >= 1) {
                $('#dateInicio').val(dates[0]);
            }
            if(dates && dates.length >= 2) {
                $('#dateFin').val(dates[1]);
            }
        }
        
        $('#modalFechas').modal('show');
    }

    function saveFechas() {
        if(currentEditingInput) {
            var i = $('#dateInicio').val();
            var f = $('#dateFin').val();
            
            // Construct string
            var str = "";
            if(i && f) {
                str = "Del " + i + " al " + f;
            } else if(i) {
                 str = "Del " + i;
            } else if(f) {
                str = "Al " + f;
            }
            
            currentEditingInput.val(str);
            $('#modalFechas').modal('hide');
        }
    }

    // -------------------------------------------------------------------------
    // Functions
    // -------------------------------------------------------------------------
    
    function updateCargo(select, inputName) {
          var selectedName = $(select).val();
          var cargoInput = $('input[name="' + inputName + '"]');
          
          if (selectedName && selectedName !== "NO APLICA") {
              var user = usuarios.find(function(u) {
                  return u.nombre_completo === selectedName;
              });
              
              if (user) {
                  cargoInput.val(user.dsc_puesto || '');
              } else {
                   cargoInput.val(''); 
              }
          } else if (selectedName === "NO APLICA") {
               cargoInput.val("NO APLICA");
          } else {
              cargoInput.val('');
          }
    }

    function initRowSelect2($container) {
        $container.find('.select2-dynamic').select2({
            placeholder: "Seleccione...",
            allowClear: true,
            width: '100%'
        });

        // Partida Change Event for Viaticos
        $container.find('select[name="no_partida[]"]').on('select2:select', function(e) {
            var data = e.params.data;
            var $row = $(this).closest('tr');
            var $btnViaticos = $row.find('.btn-viaticos');
          
            var text = data.text; 
            if(text.includes('3750') || text.includes('3760')) {
                $btnViaticos.removeClass('d-none');
                console.log( 'entro a ala clase' );
            } else {
                $btnViaticos.addClass('d-none');
                // Optional: Clear viaticos data if changed?
            }
        }).on('select2:unselect', function(e){
             $(this).closest('tr').find('.btn-viaticos').addClass('d-none');
        });

        // Initialize Provider Search Select2
        $container.find('.select2-proveedor-dynamic').select2({
            placeholder: "Buscar...",
            allowClear: true,
            width: '100%',
            minimumInputLength: 3,
            ajax: {
                url: '<?= base_url() ?>index.php/Principal/buscarProveedorSelect2',
                dataType: 'json',
                delay: 250,
                data: function (params) { return { term: params.term }; },
                processResults: function (data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id_proveedor,
                                text: item.razon_social, // Just name in dropdown
                                rfc: item.rfc, // Store RFC
                                razon_social: item.razon_social
                            }
                        })
                    };
                },
                cache: true
            }
        }).on('select2:select', function (e) {
            var data = e.params.data;
            var $row = $(this).closest('tr');
            
            // Populate Fields
            $row.find('input[name="proveedor_rfc[]"]').val(data.rfc);
            $row.find('input[name="proveedor_nombre[]"]').val(data.razon_social);
            $row.find('input[name="proveedor_id[]"]').val(data.id);
            
            // We use the hidden input 'proveedor_id[]' to store ID likely needed for save
            // Note: Use one of them as the 'main' provider for legacy support if needed?
            // For now, visual mainly.
             if($row.index() === 0) {
                 // Maybe sync with a main hidden field if backend requires one global provider?
                 // Let's assume backend iterates rows or we update backend.
             }
        }).on('select2:clear', function(e){
             var $row = $(this).closest('tr');
             $row.find('input[name="proveedor_rfc[]"]').val('');
             $row.find('input[name="proveedor_nombre[]"]').val('');
             $row.find('input[name="proveedor_id[]"]').val('');
        });
    }

    function createRowHtml(index) {
        // Options Generation
        var optsProj = '<option value="">Seleccione...</option>';
        cat_proyecto.forEach(p => optsProj += `<option value="${p.proyecto}">${p.proyecto}</option>`);
        
        var optsPart = '<option value="">Seleccione...</option>';
        cat_partida.forEach(p => optsPart += `<option value="${p.cuenta_cable}">${p.cuenta_cable}</option>`);

        return `
            <tr class="item-row">
                <td><input type="text" name="no_comprobante[]" class="form-control-plaintext" placeholder="Folio"></td>
                <td><select name="proyecto_meta[]" class="form-control-plaintext select2-dynamic">${optsProj}</select></td>
                <td><select name="no_partida[]" class="form-control-plaintext select2-dynamic">${optsPart}</select></td>
                <td>
                    <input type="text" name="importe[]" class="form-control-plaintext input-importe font-weight-bold" placeholder="$0.00">
                     <div class="mt-1 d-flex justify-content-center flex-wrap">
                        <label for="pdf_pt_${index}" class="file-upload-btn btn-pdf" title="Subir PDF">PDF</label>
                        <input type="file" id="pdf_pt_${index}" name="pdf_pt_${index}[]" class="d-none input-pdf" accept=".pdf" onchange="updateFileName(this)">
                        
                        <label for="xml_pt_${index}" class="file-upload-btn btn-xml" title="Subir XML">XML</label>
                        <input type="file" id="xml_pt_${index}" name="xml_pt_${index}[]" class="d-none input-xml" accept=".xml" onchange="updateFileName(this)">
                        <input type="hidden" name="row_index[]" value="${index}">
                    </div>
                    <div class="mt-0">
                         <span class="file-name-display" id="name_pdf_pt_${index}"></span>
                         <span class="file-name-display" id="name_xml_pt_${index}"></span>
                    </div>
                    
                     <div class="commission-container d-flex justify-content-center">
                        <input type="hidden" name="comision[]" value="">
                        <button type="button" class="btn btn-icon-custom btn-comision" onclick="editComision(this)" title="Comisión">
                            <i class="fas fa-briefcase"></i>
                        </button>

                        <input type="hidden" name="concepto_gasto[]" value="">
                        <button type="button" class="btn btn-icon-custom btn-concepto" onclick="editConcepto(this)" title="Concepto">
                            <i class="fas fa-file-alt"></i>
                        </button>

                        <input type="hidden" name="fechas[]" value="">
                        <button type="button" class="btn btn-icon-custom btn-fechas" onclick="editFechas(this)" title="Fechas">
                            <i class="fas fa-calendar-alt"></i>
                        </button>

                        <button type="button" class="btn btn-sm btn-danger py-0 px-1 ml-1 btn-remove-row">&times;</button>
                    </div>
                </td>
                <td>
                   
                    <input type="text" name="proveedor_nombre[]" class="form-control-plaintext mt-1 small font-weight-bold provider-name-display" readonly>
                    <input type="hidden" name="proveedor_id[]" class="provider-id-hidden">
                </td>
                <td><input type="text" name="proveedor_rfc[]" class="form-control-plaintext" placeholder="RFC" readonly></td>
            </tr>
        `;
    }

    function updateFolioDisplay() {
        var num = $('input[name="no_consecutivo"]').val();
        var prefijo = $('#folio option:selected').text();
        // If prefijo is just the text inside option, use it. Usually SECTURI/DGA...
        // Format: GO [The Select Text] [Num] / 2026
        // Note: Check if prefijo ends with /
        if(!prefijo.endsWith('/')) prefijo += '/';
        
        var full = "GO " + prefijo + (num ? num : "001") + "/2026";
        $('#folio_error').text(full); // If used elsewhere
        $('#folioCompleto').val(full);
    }
    
    function updateCountDocs() {
        var count = $('.item-row').length;
        $('#count_docs').text(count);
        $('#relacion_documentos').val(count);
    }

    function calculateTotalOnly() {
        var total = 0;
        $('.input-importe').each(function() {
            var val = $(this).val().replace(/[^0-9.]/g, '');
            if(val) total += parseFloat(val);
        });
        
        // Add Propinas
        $('input[name="propinas[]"]').each(function(){
            var val = $(this).val();
            if(val && !isNaN(val)) total += parseFloat(val);
        });

        return total;
    }

    function calcularTotal() {
        var total = calculateTotalOnly();
        var formatted = total.toLocaleString('en-US', {style: 'currency', currency: 'USD'});
        $('input[name="importe_total_num"]').val(formatted);
        $('#header_total').text(formatted);
        
        // Number to Letters
        if(total > 0) {
             $('input[name="importe_letra"]').val(numeroALetras(total, { plural: 'PESOS 00/100 M.N.', singular: 'PESO 00/100 M.N.', centPlural: 'CENTAVOS', centSingular: 'CENTAVO' }));
        } else {
             $('input[name="importe_letra"]').val('CERO PESOS 00/100 M.N.');
        }
    }

    // Number to Letters implementation (Simplified)
    var numeroALetras = (function() {
        // Code borrowed/adapted from common JS libraries for this purpose
        function Unidades(num){
            switch(num)
            {
                case 1: return 'UN';
                case 2: return 'DOS';
                case 3: return 'TRES';
                case 4: return 'CUATRO';
                case 5: return 'CINCO';
                case 6: return 'SEIS';
                case 7: return 'SIETE';
                case 8: return 'OCHO';
                case 9: return 'NUEVE';
            }
            return '';
        }
        function Decenas(num){
            let decena = Math.floor(num/10);
            let unidad = num - (decena * 10);
            switch(decena)
            {
                case 1:
                    switch(unidad)
                    {
                        case 0: return 'DIEZ';
                        case 1: return 'ONCE';
                        case 2: return 'DOCE';
                        case 3: return 'TRECE';
                        case 4: return 'CATORCE';
                        case 5: return 'QUINCE';
                        default: return 'DIECI' + Unidades(unidad);
                    }
                case 2:
                    switch(unidad)
                    {
                        case 0: return 'VEINTE';
                        default: return 'VEINTI' + Unidades(unidad);
                    }
                case 3: return DecenasY('TREINTA', unidad);
                case 4: return DecenasY('CUARENTA', unidad);
                case 5: return DecenasY('CINCUENTA', unidad);
                case 6: return DecenasY('SESENTA', unidad);
                case 7: return DecenasY('SETENTA', unidad);
                case 8: return DecenasY('OCHENTA', unidad);
                case 9: return DecenasY('NOVENTA', unidad);
                case 0: return Unidades(unidad);
            }
        }
        function DecenasY(strSin, numUnidades) {
            if (numUnidades > 0)
                return strSin + ' Y ' + Unidades(numUnidades)
            return strSin;
        }
        function Centenas(num) {
            let centenas = Math.floor(num / 100);
            let decenas = num - (centenas * 100);
            switch(centenas)
            {
                case 1:
                    if (decenas > 0) return 'CIENTO ' + Decenas(decenas);
                    return 'CIEN';
                case 2: return 'DOSCIENTOS ' + Decenas(decenas);
                case 3: return 'TRESCIENTOS ' + Decenas(decenas);
                case 4: return 'CUATROCIENTOS ' + Decenas(decenas);
                case 5: return 'QUINIENTOS ' + Decenas(decenas);
                case 6: return 'SEISCIENTOS ' + Decenas(decenas);
                case 7: return 'SETECIENTOS ' + Decenas(decenas);
                case 8: return 'OCHOCIENTOS ' + Decenas(decenas);
                case 9: return 'NOVECIENTOS ' + Decenas(decenas);
            }
            return Decenas(decenas);
        }
        function Seccion(num, divisor, strSingular, strPlural) {
            let cientos = Math.floor(num / divisor)
            let resto = num - (cientos * divisor)
            let letras = '';
            if (cientos > 0)
                if (cientos > 1)
                    letras = Centenas(cientos) + ' ' + strPlural;
                else
                    letras = strSingular;
            if (resto > 0)
                letras += '';
            return letras;
        }
        function Miles(num) {
            let divisor = 1000;
            let cientos = Math.floor(num / divisor)
            let resto = num - (cientos * divisor)
            let strMiles = Seccion(num, divisor, 'UN MIL', 'MIL');
            let strCentenas = Centenas(resto);
            if(strMiles == '') return strCentenas;
            return strMiles + ' ' + strCentenas;
        }
        function Millones(num) {
            let divisor = 1000000;
            let cientos = Math.floor(num / divisor)
            let resto = num - (cientos * divisor)
            let strMillones = Seccion(num, divisor, 'UN MILLON DE', 'MILLONES DE');
            let strMiles = Miles(resto);
            if(strMillones == '') return strMiles;
            return strMillones + ' ' + strMiles;
        }
        return function(num, currency) {
            currency = currency || {};
            let data = {
                numero: num,
                enteros: Math.floor(num),
                centavos: (((Math.round(num * 100)) - (Math.floor(num) * 100))),
                letrasCentavos: '',
                letrasMonedaPlural: currency.plural || 'PESOS',
                letrasMonedaSingular: currency.singular || 'PESO', 
                letrasCentavosPlural: currency.centPlural || 'CENTAVOS',
                letrasCentavosSingular: currency.centSingular || 'CENTAVO'
            };

            if (data.centavos > 0) {
                 data.letrasCentavos = (data.centavos < 10 ? '0' + data.centavos : data.centavos) + '/100 M.N.';
            } else {
                 data.letrasCentavos = '00/100 M.N.';
            }

            if(data.enteros == 0) return 'CERO ' + data.letrasMonedaPlural + ' ' + data.letrasCentavos;
            if (data.enteros == 1) return Millones(data.enteros) + ' ' + data.letrasMonedaSingular + ' ' + data.letrasCentavos;
            else return Millones(data.enteros) + ' ' + data.letrasMonedaPlural + ' ' + data.letrasCentavos;
        };
    })();

    window.updateFileName = function(input) {
        var fileName = input.files[0] ? input.files[0].name : '';
        $('#' + 'name_' + input.id).text(fileName);
    }
    
    function handleXmlUpload(input) {
         var file = input.files[0];
         if (!file) return;

         var $row = $(input).closest('tr');
         var $importe = $row.find('input[name="importe[]"]');
         var $comprobante = $row.find('input[name="no_comprobante[]"]');
         var $rfc = $row.find('input[name="proveedor_rfc[]"]');
         var $provName = $row.find('input[name="proveedor_nombre[]"]');

         var reader = new FileReader();
         reader.onload = function(e) {
             var parser = new DOMParser();
             var xmlDoc = parser.parseFromString(e.target.result, "text/xml");
             
             // Get Total
             var comp = xmlDoc.getElementsByTagName("cfdi:Comprobante")[0] || xmlDoc.getElementsByTagName("Comprobante")[0];
             if(comp) {
                 var total = comp.getAttribute("Total");
                 if(total) $importe.val(parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 2}));
                 
                 var folio = comp.getAttribute("Folio");
                 if(folio) $comprobante.val(folio);
                 else {
                     // Try UUID
                     var tfd = xmlDoc.getElementsByTagName("tfd:TimbreFiscalDigital")[0] || xmlDoc.getElementsByTagName("TimbreFiscalDigital")[0];
                     if(tfd && tfd.getAttribute("UUID")) $comprobante.val(tfd.getAttribute("UUID"));
                 }
             }
             
             // Get Emisor RFC / Name (Optional bonus feature)
             var emisor = xmlDoc.getElementsByTagName("cfdi:Emisor")[0] || xmlDoc.getElementsByTagName("Emisor")[0];
             if(emisor) {
                 var rfc = emisor.getAttribute("Rfc");
                 var nombre = emisor.getAttribute("Nombre");
                 
                 if(rfc) $rfc.val(rfc);
                 if(nombre) $provName.val(nombre); 
             }
             
             calcularTotal();
         };
         reader.readAsText(file);
    }

    function saveForm() {
        // Validation
        let isValid = true;
        let missingFields = 0;

        // Reset previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.border-danger').removeClass('border-danger');
        $('.select2-selection').removeClass('border border-danger');

        // --- HEADER VALIDATION ---
        
        // Folio Select
        let $folio = $('select[name="folio"]');
        if($folio.val() === '' || $folio.val() === null) {
             $folio.next('.select2-container').find('.select2-selection').addClass('border border-danger');
             isValid = false;
             missingFields++;
        }
        
        // Consecutivo Input
        let $consecutivo = $('input[name="no_consecutivo"]');
        if($consecutivo.val().trim() === '') {
            $consecutivo.addClass('is-invalid border border-danger');
            isValid = false;
            missingFields++;
        }
        
        // Fecha Tramite
        let $fecha = $('input[name="fecha_tramite"]');
        if($fecha.val().trim() === '') {
            $fecha.addClass('is-invalid border border-danger');
            isValid = false;
            missingFields++;
        }

        // --- FOOTER VALIDATION ---
        
        // Concepto General
        let $concepto = $('textarea[name="concepto"]');
        if($concepto.length > 0 && $concepto.val().trim() === '') {
            $concepto.addClass('is-invalid border border-danger');
            isValid = false;
            missingFields++;
        }
        
        // Signatures
        let $autoriza = $('select[name="nombre_autoriza"]');
        if($autoriza.val() === '' || $autoriza.val() === null) {
             $autoriza.next('.select2-container').find('.select2-selection').addClass('border border-danger');
             isValid = false;
             missingFields++;
        }
        
        let $resp1 = $('select[name="nombre_responsable_1"]');
         if($resp1.val() === '' || $resp1.val() === null) {
             $resp1.next('.select2-container').find('.select2-selection').addClass('border border-danger');
             isValid = false;
             missingFields++;
        }
        
        let $resp2 = $('select[name="nombre_responsable_2"]');
        if($resp2.length > 0 && ($resp2.val() === '' || $resp2.val() === null)) {
             $resp2.next('.select2-container').find('.select2-selection').addClass('border border-danger');
             isValid = false;
             missingFields++;
        }

        // --- ROWS VALIDATION ---
        $('#tblDetalle tbody tr').each(function() {
            let $row = $(this);
            
            // Validate Comprobante
            let $comprobante = $row.find('input[name="no_comprobante[]"]');
            if($comprobante.val().trim() === '') {
                $comprobante.addClass('is-invalid border border-danger');
                isValid = false;
                missingFields++;
            }

            // Validate Proyecto
            let $proyecto = $row.find('select[name="proyecto_meta[]"]');
            if($proyecto.val() === '' || $proyecto.val() === null) {
                $proyecto.next('.select2-container').find('.select2-selection').addClass('border border-danger');
                isValid = false;
                 missingFields++;
            }

            // Validate Partida
            let $partida = $row.find('select[name="no_partida[]"]');
             if($partida.val() === '' || $partida.val() === null) {
                 $partida.next('.select2-container').find('.select2-selection').addClass('border border-danger');
                isValid = false;
                 missingFields++;
            }

            // Validate Importe
            let $importe = $row.find('input[name="importe[]"]');
             if($importe.val().trim() === '') {
                $importe.addClass('is-invalid border border-danger');
                isValid = false;
                 missingFields++;
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos Incompletos',
                text: 'Por favor, llena todos los campos obligatorios marcados en rojo.',
            });
            return; // Stop submission
        }

       // e.preventDefault();
        var formData = new FormData($('#formPagoTerceros')[0]);
        
        // Append Main Provider ID from first row as fallback if backend requires 'nombre_proveedor_1'
        var firstProvId = $('input[name="proveedor_id[]"]').first().val();
        if(firstProvId) formData.append('nombre_proveedor_1', firstProvId);

        $.ajax({
            url: '<?= base_url() ?>index.php/Agregar/guardaFormatoGO', 
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response){
                if(response.respuesta.split('|')[0] == 'Éxito'){
                    Swal.fire({ title: 'Éxito', text: response.respuesta.split('|')[1], icon: 'success' }).then(() => {
                        window.location.href = "<?= base_url('index.php/Inicio/listaGastosOperacion') ?>";
                    });
                } else {
                     Swal.fire("Error", response.respuesta.split('|')[1], "error");
                }
            },
            error: function() {
                 Swal.fire("Error", "Ocurrió un error al guardar.", "error");
            }
        });
    }

    // Clear validation error on input
    $(document).on('input change', '.is-invalid, .select2-hidden-accessible', function() {
        $(this).removeClass('is-invalid border border-danger');
        if($(this).hasClass('select2-hidden-accessible')) {
             $(this).next('.select2-container').find('.select2-selection').removeClass('border border-danger');
        }
    });

    function editConcepto(btn) { 
        var $hidden = $(btn).closest('.commission-container').find('input[name="concepto_gasto[]"]');
        Swal.fire({
            title: 'Concepto Gasto',
            input: 'textarea',
            inputValue: $hidden.val(),
            showCancelButton: true,
             confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.isConfirmed) $hidden.val(result.value);
        });
    }
    
    function editComision(btn) { 
        var $hidden = $(btn).closest('.commission-container').find('input[name="comision[]"]');
         Swal.fire({
            title: 'Comisión / Evento',
            input: 'textarea',
             inputValue: $hidden.val(),
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.isConfirmed) $hidden.val(result.value);
        });
    }

    function editFechas(btn) {
        var $hidden = $(btn).closest('.commission-container').find('input[name="fechas[]"]');
        var currentVal = $hidden.val() || '';
        var parts = currentVal.split(' / ');
        var start = parts[0] || '';
        var end = parts[1] || '';

         Swal.fire({
            title: 'Rango de Fechas',
            html: `
                <div class="form-group text-left">
                    <label>Inicio</label>
                    <input type="date" id="swal-start" class="form-control" value="${start}">
                </div>
                 <div class="form-group text-left">
                    <label>Fin</label>
                    <input type="date" id="swal-end" class="form-control" value="${end}">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                return document.getElementById('swal-start').value + ' / ' + document.getElementById('swal-end').value;
            }
        }).then((result) => {
            if(result.isConfirmed) $hidden.val(result.value);
        });
    }

    function editPropinas(btn) { 
        var $hidden = $(btn).closest('.commission-container').find('input[name="propinas[]"]');
        Swal.fire({
            title: 'Propinas',
            input: 'number',
            inputValue: $hidden.val(),
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            inputAttributes: {
                min: 0,
                step: 0.01
            }
        }).then((result) => {
            if(result.isConfirmed) {
                $hidden.val(result.value);
                calcularTotal(); // Update Total
            }
        });
    }

    // --- VIATICOS MODAL LOGIC ---

    function openViaticosModal(btn) {
        var $row = $(btn).closest('tr');
        // If it's a new row, it has arowIndex. If loaded, it might use index.
        // We need a reliable way to identify the row to push data back.
        // Let's use the row's DOM element index or add a unique ID if needed.
        // The buttons have onclick="openViaticosModal(this)"
        
        // Store current row reference
        $('#current_viaticos_row_index').val($row.index());
        
        // Load existing data
        var jsonStr = $row.find('input[name="viaticos_json[]"]').val();
        var data = [];
        try {
            data = jsonStr ? JSON.parse(jsonStr) : [];
        } catch(e) { console.error("Error parsing viaticos JSON", e); }
        
        // Clear and Repopulate Table
        var $tbody = $('#tblViaticos tbody');
        $tbody.empty();
        
        if(data.length > 0) {
            data.forEach(function(item) {
                addViaticoRow(item.nombre, item.monto);
            });
        } else {
            // Add one empty row by default
            addViaticoRow();
        }
        
        calculateViaticosTotal();
        $('#modalViaticos').modal('show');
    }

    function addViaticoRow(nombre = '', monto = '') {
        var tr = `
            <tr>
                <td><input type="text" class="form-control form-control-sm viatico-nombre" placeholder="Nombre completo" value="${nombre}"></td>
                <td>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="number" class="form-control viatico-monto" placeholder="0.00" value="${monto}" step="0.01" oninput="calculateViaticosTotal()">
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger px-2" onclick="removeViaticoRow(this)">&times;</button>
                </td>
            </tr>
        `;
        $('#tblViaticos tbody').append(tr);
    }

    function removeViaticoRow(btn) {
        $(btn).closest('tr').remove();
        calculateViaticosTotal();
    }

    function calculateViaticosTotal() {
        var total = 0;
        $('.viatico-monto').each(function() {
            var val = parseFloat($(this).val()) || 0;
            total += val;
        });
        $('#totalViaticosDisplay').text(total.toLocaleString('en-US', {style: 'currency', currency: 'USD'}));
        return total;
    }

    function saveViaticos() {
        var rowIndex = $('#current_viaticos_row_index').val();

        
        if(!window.currentRowElement) return;
        
        var $row = $(window.currentRowElement);
        var viaticosData = [];
        var total = 0;
        var descriptionParts = [];
        
        $('#tblViaticos tbody tr').each(function() {
            var nombre = $(this).find('.viatico-nombre').val().trim();
            var monto = parseFloat($(this).find('.viatico-monto').val()) || 0;
            
            if(nombre && monto > 0) {
                viaticosData.push({nombre: nombre, monto: monto});
                total += monto;
                descriptionParts.push(`${nombre} ($${monto.toFixed(2)})`);
            }
        });
        
        // 1. Save JSON
        $row.find('input[name="viaticos_json[]"]').val(JSON.stringify(viaticosData));
        

        var descText = "VIÁTICOS: " + descriptionParts.join(', ');
        $row.find('input[name="concepto_gasto[]"]').val(descText);
        $row.find('button.btn-concepto').addClass('btn-info').removeClass('btn-warning'); // Visual cue (optional)
        
        // 4. Update Commission? Maybe set to "REUNION DE TRABAJO" or similar default?
        // $row.find('input[name="comision[]"]').val("VIATICOS");
        
        // Update Totals
        calcularTotal();
        
        $('#modalViaticos').modal('hide');
    }
    
    // Override openViaticosModal to set global element
    var originalOpen = openViaticosModal;
    openViaticosModal = function(btn) {
        window.currentRowElement = $(btn).closest('tr');
        // Call logic
        // Duplicate logic here or call helper?
        // Let's just put logic here directly since I defined openViaticosModal above but need to fix it.
        
        var $row = $(window.currentRowElement);
        var jsonStr = $row.find('input[name="viaticos_json[]"]').val();
        var data = [];
        try {
            data = jsonStr ? JSON.parse(jsonStr) : [];
        } catch(e) { console.error("JSON Error", e); }
        
        var $tbody = $('#tblViaticos tbody');
        $tbody.empty();
        
        if(data.length > 0) {
            data.forEach(function(item) {
                addViaticoRow(item.nombre, item.monto);
            });
        } else {
            addViaticoRow();
        }
        
        calculateViaticosTotal();
        $('#modalViaticos').modal('show');
    }


</script>
