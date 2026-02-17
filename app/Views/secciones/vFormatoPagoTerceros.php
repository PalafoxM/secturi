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
                                            <option value="<?= $area->id_area ?>"><?= $area->prefijo ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="no_consecutivo" autocomplete="off" class="form-control-plaintext" value="<?= isset($registro_pt->no_consecutivo) ? $registro_pt->no_consecutivo : $no_consecutivo ?>" placeholder="001/2026">
                                    <spam id="folio_error" class="text-danger"></spam>
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
                                    <div class="mt-2 text-left">
                                        <label class="small text-muted mb-0">PDF:</label>
                                        <input type="file" name="pdf_pt_<?= $index ?>[]" class="form-control-sm border-0 mb-1" accept=".pdf" style="width: 100%;">
                                        
                                        <label class="small text-muted mb-0">XML:</label>
                                        <input type="file" name="xml_pt_<?= $index ?>[]" class="form-control-sm border-0" accept=".xml" style="width: 100%;">
                                        
                                        <input type="hidden" name="row_index[]" value="<?= $index ?>">
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
                                            <select id="nombre_proveedor_1" name="nombre_proveedor_1" class="form-control-plaintext select2" placeholder="ORGANIZACION MUNDIAL DEL TURISMO" value="<?= isset($registro_pt->nombre_proveedor_1) ? $registro_pt->nombre_proveedor_1 : '' ?>">
                                                <option value="">Seleccione un proveedor</option>
                                                <?php foreach($proveedores as $proveedor): ?>
                                                    <option value="<?= $proveedor->razon_social ?>"><?= $proveedor->razon_social ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- No. Proveedor -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2 align-self-start">No. PROVEEDOR:</span>
                                            <input type="text" id="no_proveedor" name="no_proveedor" class="flex-grow-1" placeholder="103156" value="<?= isset($registro_pt->no_proveedor) ? $registro_pt->no_proveedor : '' ?>">
                                        </div>

                                        <!-- RFC -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">RFC:</span>
                                            <input type="text" id="rfc_proveedor" name="rfc_proveedor" class="flex-grow-1" placeholder="N0011499A" value="<?= isset($registro_pt->rfc_proveedor) ? $registro_pt->rfc_proveedor : '' ?>">
                                        </div>

                                        <!-- Nombre Proveedor (Repetido en imagen) -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2 align-self-start">NOMBRE:</span>
                                            <input type="text" id="nombre_proveedor_2" name="nombre_proveedor_2" class="flex-grow-1" placeholder="ORGANIZACION MUNDIAL DEL TURISMO" value="<?= isset($registro_pt->nombre_proveedor_2) ? $registro_pt->nombre_proveedor_2 : '' ?>">
                                        </div>

                                        <!-- No. Cuenta -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">NO. CUENTA:</span>
                                            <input type="text" id="no_cuenta" name="no_cuenta" class="flex-grow-1" placeholder="610081057237000168" value="<?= isset($registro_pt->no_cuenta) ? $registro_pt->no_cuenta : '' ?>">
                                        </div>

                                        <!-- Banco -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">BANCO:</span>
                                            <input type="text" id="banco" name="banco" class="flex-grow-1" placeholder="B&B" value="<?= isset($registro_pt->banco) ? $registro_pt->banco : '' ?>">
                                        </div>

                                        <!-- CLABE -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">CLABE:</span>
                                            <input type="text" id="clabe" name="clabe" class="flex-grow-1" placeholder="610081057237000168" value="<?= isset($registro_pt->clabe) ? $registro_pt->clabe : '' ?>">
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
                                        <input type="text" name="contrato_convenio" class="form-control-plaintext text-left w-50" value="<?= isset($registro_pt->contrato_convenio) ? $registro_pt->contrato_convenio : '' ?>">
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="label-bold me-2">No. RESERVA:</span>
                                        <input type="text" name="no_reserva_visual" class="form-control-plaintext text-left w-50" value="<?= isset($registro_pt->no_reserva) ? $registro_pt->no_reserva : '' ?>" placeholder="4798053">
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
                                    <select name="autoriza" id="autoriza">
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <?php if(in_array($usuario->id_usuario, [95, 105])): ?>
                                            <option value="<?= $usuario->nombre_completo ?>"><?= $usuario->nombre_completo ?></option>
                                            <?php endif ?>
                                        <?php endforeach; ?>
                                    </select>
                                   
                                    <input type="text" name="cargo_autoriza" class="form-control-plaintext small" value="<?= $usuario->dsc_puesto ?>">
                                    
                                </td>
                                <td class="align-bottom pb-3">
                                    <select name="responsable" id="responsable">
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <?php if(in_array($usuario->id_usuario, [95, 105])): ?>
                                            <option value="<?= $usuario->nombre_completo ?>"><?= $usuario->nombre_completo ?></option>
                                            <?php endif ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="nombre_responsable" class="form-control-plaintext font-weight-bold mb-1" value="MARCO ANTONIO MORALES GARCÍA">
                                    <input type="text" name="cargo_responsable" class="form-control-plaintext small" value="DIRECTOR/A GENERAL DE INNOVACIÓN E INTELIGENCIA TURÍSTICA">
                                </td>
                            </tr>
                             <tr>
                                <td colspan="2" style="border: none;"></td>
                                <td class="bg-grey font-weight-bold border">RESPONSABLE DEL PROYECTO</td>
                            </tr>
                             <tr>
                                <td colspan="2" style="border: none;"></td>
                                <td class="align-bottom pb-3 border" style="height: 100px;">
                                    <input type="text" name="nombre_responsable_2" class="form-control-plaintext font-weight-bold mb-1" value="MARCO ANTONIO MORALES GARCÍA">
                                    <input type="text" name="cargo_responsable_2" class="form-control-plaintext small" value="DIRECTOR/A GENERAL DE INNOVACIÓN E INTELIGENCIA TURÍSTICA">
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
    
    var globalRowIndex = <?= $totalRows ?>;

      $('#folio,#proyecto_meta,#partida,#autoriza').select2({
          placeholder: "Selecciona una opción",
          allowClear: true,
          width: 'resolve'
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

                          $('#no_proveedor').val(proveedor.no_proveedor);
                          $('#rfc_proveedor').val(proveedor.rfc);
                          $('#nombre_proveedor_2').val(proveedor.razon_social);

                          if(bancos && bancos.length > 0) {
                              // Take the first bank account found
                              var banco = bancos[0];
                              $('#no_cuenta').val(banco.no_cuenta);
                              $('#banco').val(banco.banco);
                              $('#clabe').val(banco.clabe);
                          } else {
                              $('#no_cuenta').val('');
                              $('#banco').val('');
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
              $('#banco').val('');
              $('#clabe').val('');
          }
      });

      // Concatenate Folio and Consecutivo
      function updateFolio() {
          var prefix = $('#folio option:selected').text();
          var consecutivo = $('input[name="no_consecutivo"]').val();
          if(prefix && consecutivo) {
              $('#folio_error').text(prefix + ' ' + consecutivo);
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
                optionsProyecto += `<option value="${item.id_proyecto}">${item.proyecto}</option>`;
            });

            // Build Options for Partida
            var optionsPartida = '<option value="">Seleccione...</option>';
            cat_partida.forEach(function(item) {
                optionsPartida += `<option value="${item.id_partida}">${item.cuenta_cable}</option>`;
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
                        
                        <div class="mt-2 text-left">
                            <label class="small text-muted mb-0">PDF:</label>
                            <input type="file" name="pdf_pt_${globalRowIndex}[]" class="form-control-sm border-0 mb-1" accept=".pdf" style="width: 100%;">
                            
                            <label class="small text-muted mb-0">XML:</label>
                            <input type="file" name="xml_pt_${globalRowIndex}[]" class="form-control-sm border-0" accept=".xml" style="width: 100%;">
                            
                            <input type="hidden" name="row_index[]" value="${globalRowIndex}">
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
                    output += convertirCentenas(millones) + 'MILLONES ';
                }
            }

            if (miles > 0) {
                if (miles === 1) {
                    output += 'UN MIL ';
                } else {
                    output += convertirCentenas(miles) + 'MIL ';
                }
            }

            if (unidades > 0) {
                output += convertirCentenas(unidades);
            }

            return output;
        }

        function convertirCentenas(n) {
            var output = '';
            var centenas = Math.floor(n / 100);
            var decenas = Math.floor((n % 100) / 10);
            var unidades = n % 10;
            var data = {
                unidades: ['', 'UN ', 'DOS ', 'TRES ', 'CUATRO ', 'CINCO ', 'SEIS ', 'SIETE ', 'OCHO ', 'NUEVE '],
                decenas: ['', 'DIEZ ', 'VEINTE ', 'TREINTA ', 'CUARENTA ', 'CINCUENTA ', 'SESENTA ', 'SETENTA ', 'OCHENTA ', 'NOVENTA '],
                diez: ['DIEZ ', 'ONCE ', 'DOCE ', 'TRECE ', 'CATORCE ', 'QUINCE ', 'DIECISEIS ', 'DIECISIETE ', 'DIECIOCHO ', 'DIECINUEVE '],
                centenas: ['', 'CIENTO ', 'DOSCIENTOS ', 'TRESCIENTOS ', 'CUATROCIENTOS ', 'QUINIENTOS ', 'SEISCIENTOS ', 'SETECIENTOS ', 'OCHOCIENTOS ', 'NOVECIENTOS '],
            };

            if (centenas > 0) {
                if (centenas === 1 && decenas === 0 && unidades === 0) {
                    output += 'CIEN ';
                } else {
                    output += data.centenas[centenas];
                }
            }

            if (decenas > 0) {
                if (decenas === 1) {
                    output += data.diez[unidades];
                } else if (decenas === 2 && unidades > 0) {
                    output += 'VEINTI' + data.unidades[unidades].trim(); // Veintiuno, veintidos... pero aqui simplificamos
                    // Correccion rapida para veinti:
                    // VEINTIUN, VEINTIDOS...
                    // Dejemoslo simple:
                     output += 'VEINTI' + data.unidades[unidades].replace('UN ', 'UNO ').trim() + ' ';
                } else {
                    output += data.decenas[decenas];
                    if (unidades > 0) {
                        output += 'Y ' + data.unidades[unidades];
                    }
                }
            } else if (unidades > 0) {
                // Special check for 10-19 handled above
                // If decenas was 0, just units
                if(centenas > 0 || miles > 0 || millones > 0) { // If there was something before
                     output += data.unidades[unidades];
                } else {
                    output += data.unidades[unidades];
                }
            }
            
            // Fix for VEINTI logic above which is a bit messy in quick JS
            // Let's use a simpler standard approach for 0-99
            return convertirDecenas(n % 100, (Math.floor(n/100) == 1 && (n%100)==0)); // Pass if it was exactly 100 for 'CIEN' handling check? NO, handled before.
            
            // Re-writing convertCentenas to be cleaner
        }
        
        // Better implementation for converting 0-999
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
</script>

