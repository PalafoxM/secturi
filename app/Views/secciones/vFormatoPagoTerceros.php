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
                    <input type="hidden" name="id_registro_pt" value="<?= isset($registro_pt->id_registro_pt) ? $registro_pt->id_registro_pt : '' ?>">
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
                                    <input type="date" name="fecha_tramite" class="form-control-plaintext" value="<?= isset($registro_pt->fecha_tramite) ? $registro_pt->fecha_tramite : date('Y-m-d') ?>">
                                </td>
                                <td>
                                    <input type="text" name="no_consecutivo" class="form-control-plaintext" value="<?= isset($registro_pt->no_consecutivo) ? $registro_pt->no_consecutivo : $no_consecutivo ?>" placeholder="PT 001/2026">
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
                                    <input type="text" name="no_comprobante[]" class="form-control-plaintext mb-2" value="<?= isset($row->encabezado) ? $row->encabezado : '' ?>" placeholder="3220">
                                    
                                    <!-- Archivos (Solo en la primera fila o por fila? Idealmente por fila pero el diseño es compacto. 
                                         Si el usuario quiere agrupar facturas, quizas solo un set de archivos global o uno por linea.
                                         La imagen tiene UNA sola celda para archivos. Asumiremos global para el trámite o adjuntar en la primera fila.) 
                                         UPDATE: Dejaremos los archivos en la primera fila para no saturar, o moveremos a otra sección.
                                         Por ahora, solo visible en la primera fila. -->
                                    <?php if($index === 0): ?>
                                    <div class="mt-2 text-left">
                                        <small>Archivos XML/PDF</small>
                                        <input type="file" name="archivos_pdf" accept=".pdf" class="form-control form-control-sm mb-1">
                                        <input type="file" name="archivos_xml" accept=".xml" class="form-control form-control-sm">
                                    </div>
                                    <?php endif; ?>
                                </td>

                                <!-- PROYECTO META -->
                                <td style="vertical-align: top;">
                                    <input type="text" name="proyecto_meta[]" class="form-control-plaintext" value="<?= isset($row->proyecto_clave) ? $row->proyecto_clave : '' ?>" placeholder="E027QC32142602">
                                </td>

                                <!-- No. PARTIDA -->
                                <td style="vertical-align: top;">
                                    <input type="text" name="no_partida[]" class="form-control-plaintext" value="<?= isset($row->partida_clave) ? $row->partida_clave : '' ?>" placeholder="3990">
                                </td>

                                <!-- IMPORTE -->
                                <td style="vertical-align: top;">
                                    <input type="text" name="importe[]" class="form-control-plaintext input-importe" value="" placeholder="$0.00">
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
                                            <input type="text" name="nombre_proveedor_1" class="form-control-plaintext" placeholder="ORGANIZACION MUNDIAL DEL TURISMO" value="<?= isset($proveedor->razon_social) ? $proveedor->razon_social : '' ?>">
                                        </div>

                                        <!-- No. Proveedor -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2 align-self-start">No. PROVEEDOR:</span>
                                            <input type="text" name="no_proveedor" class="flex-grow-1" placeholder="103156" value="<?= isset($proveedor->no_proveedor) ? $proveedor->no_proveedor : '' ?>">
                                        </div>

                                        <!-- RFC -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">RFC:</span>
                                            <input type="text" name="rfc_proveedor" class="flex-grow-1" placeholder="N0011499A" value="<?= isset($proveedor->rfc) ? $proveedor->rfc : '' ?>">
                                        </div>

                                        <!-- Nombre Proveedor (Repetido en imagen) -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2 align-self-start">NOMBRE:</span>
                                            <input type="text" name="nombre_proveedor_2" class="flex-grow-1" placeholder="ORGANIZACION MUNDIAL DEL TURISMO" value="<?= isset($proveedor->razon_social) ? $proveedor->razon_social : '' ?>">
                                        </div>

                                        <!-- No. Cuenta -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">NO. CUENTA:</span>
                                            <input type="text" name="no_cuenta" class="flex-grow-1" placeholder="610081057237000168" value="<?= isset($proveedor_banco->cuenta) ? $proveedor_banco->cuenta : '' ?>">
                                        </div>

                                        <!-- Banco -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">BANCO:</span>
                                            <input type="text" name="banco" class="flex-grow-1" placeholder="B&B" value="<?= isset($proveedor_banco->banco) ? $proveedor_banco->banco : '' ?>">
                                        </div>

                                        <!-- CLABE -->
                                        <div class="d-flex mb-1 align-items-center">
                                            <span class="label-bold me-2">CLABE:</span>
                                            <input type="text" name="clabe" class="flex-grow-1" placeholder="610081057237000168" value="<?= isset($proveedor_banco->clabe) ? $proveedor_banco->clabe : '' ?>">
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
                                     <input type="text" name="importe_letra" class="form-control-plaintext" value="" placeholder="Sesenta y dos mil seiscientos diez pesos 00/100 M.N.">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- TABLE 4: AUTORIZACIONES -->
                    <table class="table table-bordered text-center mt-4">
                        <thead>
                            <tr class="bg-black">
                                <th colspan="3" class="text-uppercase">AUTORIZACIONES</th>
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
                                    <input type="text" name="nombre_autoriza" class="form-control-plaintext font-weight-bold mb-1" value="JAVIER PACHECO CANO">
                                    <input type="text" name="cargo_autoriza" class="form-control-plaintext small" value="DIRECTOR/A GENERAL JURÍDICO">
                                </td>
                                <td class="align-bottom pb-3">
                                    <input type="text" name="nombre_responsable" class="form-control-plaintext font-weight-bold mb-1" value="MARCO ANTONIO MORALES GARCÍA">
                                    <input type="text" name="cargo_responsable" class="form-control-plaintext small" value="DIRECTOR/A GENERAL DE INNOVACIÓN E INTELIGENCIA TURÍSTICA">
                                </td>
                            </tr>
                            <!-- Second Row for Responsable del Proyecto (Repetido en imagen?) 
                                 La imagen muestra una celda doble altura o dos filas para Responsable.
                                 Parece que hay un bloque extra abajo a la derecha. Añadiremos una fila extra solo con esa celda si es necesario, 
                                 pero la imagen parece mostrar 3 columnas principales y luego una celda abajo a la derecha. 
                                 Vamos a replicar la estructura de la imagen donde 'RESPONSABLE DEL PROYECTO' tiene un bloque extra abajo.
                            -->
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
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- jQuery  -->
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>
<!-- Required datatable js -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/pages/jquery.analytics_customers.init.js"></script>

<script src="<?= base_url()?>plugins/apexcharts/apexcharts.min.js"></script>

<!-- App js -->
<script src="<?= base_url()?>assets/js/app.js"></script>


<script src="<?= base_url()?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url()?>assets/js/waves.js"></script>
```
<script src="<?= base_url()?>assets/js/feather.min.js"></script>



<script>

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
            // Create new row
            // Note: We don't include the Provider cell, just the first 4 columns.
            var newRow = `
                <tr class="item-row">
                     <td style="vertical-align: top;">
                        <input type="text" name="no_comprobante[]" class="form-control-plaintext mb-2" placeholder="3220">
                    </td>
                    <td style="vertical-align: top;">
                        <input type="text" name="proyecto_meta[]" class="form-control-plaintext" placeholder="E027QC32142602">
                    </td>
                    <td style="vertical-align: top;">
                        <input type="text" name="no_partida[]" class="form-control-plaintext" placeholder="3990">
                    </td>
                    <td style="vertical-align: top;">
                        <input type="text" name="importe[]" class="form-control-plaintext input-importe" placeholder="$0.00">
                        <button type="button" class="btn btn-sm btn-danger mt-1 btn-remove-row" style="padding: 0px 5px;">&times;</button>
                    </td>
                </tr>
            `;
            
            // Insert before the LAST row? No, append to tbody. BUT the last row might have the Provider cell rowspan set.
            // Actually, we just append to tbody. The "Provider" cell is in the *first* tr.
            // We just need to update its rowspan.
            
            $('#pagoterceros_items_table tbody').append(newRow);
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
        }

        // Initial Total Calculation
        calcularTotal();

        $('#btnGuardarPT').click(function() {
             var formData = new FormData(document.getElementById("formPagoTerceros"));
             
             $.ajax({
                url: "<?php echo base_url(); ?>Agregar/guardaFormatoPT",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    var obj = JSON.parse(data);
                    if (obj.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: obj.respuesta
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: obj.respuesta,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            window.location.reload(); 
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.respuesta
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado.'
                    });
                }
            });
        });
    });
</script>

