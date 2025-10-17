

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
                                        <li class="breadcrumb-item active">FIC</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">PAGOS DEL FESTIVAL INTERNACIONAL CERVANTINO <i class="em em-i_love_you_hand_sign"></i> </h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>
                    
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="mt-0 header-title">PROVEEDOR: <strong><?= (isset($proveedor->razon_social) && !empty($proveedor->razon_social)) ? $proveedor->razon_social : '' ?></strong></h3>
                                    <p class="text-muted mb-3" >
                                        <?= (isset($proveedor->no_proveedor) && !empty($proveedor->no_proveedor)) ? 'No. Proveedor ' . $proveedor->no_proveedor : '' ?>
                                    </p>
                                   <form id="form_fic" enctype="multipart/form-data">
                                        <input type="hidden" name="id_proveedor" id="id_proveedor" value="<?= (isset($proveedor->id_proveedor) && !empty($proveedor->id_proveedor)) ? $proveedor->id_proveedor : '' ?>" >
                                        <input type="hidden" id="proyecto" name="proyecto" value="E027QC04182501">
                                        <input type="hidden" name="editar" id="editar" value="<?= $editar ?>">
                                        <?php if (isset($registro_pt->id_registro_pt) && !empty($registro_pt->id_registro_pt)): ?>
                                        <input type="hidden" name="id_registro_pt" id="id_registro_pt" value="<?= $registro_pt->id_registro_pt ?>">
                                        <?php endif; ?>
                                       <div class="form-row">
                                            <!-- Dirección Responsable -->
                                            <div class="col-md-4 mb-3">
                                                <label for="direccion_responsable">Dirección Responsable <span class="text-danger">*</span></label>
                                                <select class="form-control" id="direccion_responsable" name="direccion_responsable" required>
                                                    <?php foreach ($cat_area as $a): ?>
                                                    <option value="<?= $a->id_area ?>" <?php echo ($a->id_area == $usuario->id_area) ? 'selected' : ''; ?>>
                                                        <?= $a->dsc_area ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Por favor ingrese la dirección responsable
                                                </div>
                                            </div><!--end col-->
                                            
                                            <!-- Tipo de PT -->
                                            <div class="col-md-4 mb-3">
                                                <label for="tipo_pt">Tipo de PT <span class="text-danger">*</span></label>
                                                <select class="form-control" id="tipo_pt" name="tipo_pt" >
                                                    <?php foreach ($cat_tipo as $p): ?>
                                                        <option value="<?= $p->id_tipo ?>" <?= (isset($registro_pt->id_tipo) && $registro_pt->id_tipo == $p->id_tipo) ? 'selected' : '' ?> ><?= $p->des_tipo ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Por favor seleccione el tipo de PT
                                                </div>
                                            </div><!--end col-->
                                            
                                            <!-- Fecha de Trámite -->
                                            <div class="col-md-4 mb-3">
                                                <label for="fecha_tramite">Fecha de Trámite <span class="text-danger">*</span></label>
                                              <input type="date" class="form-control" id="fecha_tramite" name="fecha_tramite" 
                                                value="<?= isset($registro_pt->fecha_tramite) ? date('Y-m-d', strtotime($registro_pt->fecha_tramite)) : date('Y-m-d') ?>" 
                                                required>

                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-3 mb-3">
                                                <label for="id_reponsable_solicitud">Responsable del Gasto<span style="color:red;">*</span></label>
                                                <input id="id_reponsable_solicitud" class="form-control" name="id_reponsable_solicitud" value="HUGO RAMÍREZ DUARTE" readonly>

                                            </div><!--end col-->
                                            <div class="col-md-3 mb-3">
                                                <label for="director_generar">Director/a General Administrativa <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="director_generar" value="<?= $dsc_director_general ?>" name="director_generar" readonly>
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-3 mb-3">
                                                <label for="id_subsecretario">Subsecretario(a) que autoriza</label>
                                                <input type="text" id="id_subsecretario"  name="id_subsecretario" class="form-control" value="ARMANDO EMMANUEL GASCA GARCÍA" readonly>
                                            </div><!--end col-->
                                            <div class="col-md-3 mb-3">
                                                <label for="secretario">Secretario(a) o Director(a) que autoriza</label>
                                                <input type="text" id="secretario"  name="secretario" class="form-control" value="DAVID AYALA SAUCEDO" readonly>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="id_proveedor_banco">Cuenta Bancaria del Proveedor <span style="color:red;">*</span></label>
                                                
                                                <select class="form-control" id="id_proveedor_banco" name="id_proveedor_banco" >
                                                    <?php foreach ($banco as $b): ?>
                                                    <option value="<?= $b->id_proveedor_banco ?>" ><?= $b->banco . ' / ' . $b->no_cuenta ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="fecha_gasto_inicio">Fecha de gasto inicio <span style="color:red;">*</span></label>
                                                <input type="date" class="form-control" id="fecha_gasto_inicio" name="fecha_gasto_inicio" 
                                                value="<?= isset($registro_pt->fecha_gasto_inicio) ? date('Y-m-d', strtotime($registro_pt->fecha_gasto_inicio)) : date('Y-m-d') ?>" 
                                                required>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                 <label for="fecha_gasto_fin">Fecha de gasto fin <span style="color:red;">*</span></label>
                                                <input type="date" class="form-control" id="fecha_gasto_fin" name="fecha_gasto_fin" 
                                                value="<?= isset($registro_pt->fecha_gasto_fin) ? date('Y-m-d', strtotime($registro_pt->fecha_gasto_fin)) : date('Y-m-d') ?>" 
                                                required>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-6 mb-6">
                                                <label for="formato_establecido">Formatos establecidos en los Lineamientos Generales de Racionalidad, Austeridad y Disciplina Presupuestal de la Administración Pública Estatal vigente o formatos establecidos en la regulación del trámite ingresado.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="formato_establecido" value="SI" name="formato_establecido" readonly>
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-6 mb-6">
                                                <label for="documentacion_comprobatoria">Documentación comprobatoria fiscalmente requisitada, atendiendo a lo establecido en los Lineamientos Generales de Racionalidad, Austeridad y Disciplina Presupuestal de la Administración Pública Estatal vigentes.<span style="color:red;">*</span></label>
                                                <select type="text" class="form-control" id="documentacion_comprobatoria"  name="documentacion_comprobatoria" >
                                                  <?php foreach ($cat_opcion as $o): ?>
                                                    <option value="<?= $o->id_opcion ?>" <?= (isset($registro_pt->documentacion_comprobatoria) && $registro_pt->documentacion_comprobatoria == $o->id_opcion) ? 'selected' : '' ?> ><?= $o->des_opcion ?></option>
                                                  <?php endforeach; ?>
                                               </select>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="poliza">Pólizas Contables.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="poliza" value="SI" name="poliza" readonly>
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="formato_conformidad">Formato de conformidad del producto recibido.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="formato_conformidad" name="formato_conformidad" value="SI" readonly>
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="contrato_convenio">Contrato o Convenio.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="contrato_convenio" name="contrato_convenio" value="NO APLICA" readonly>
                                          
                                               
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="documentacion_requerida">Documentación requerida para emitir el pago.<span style="color:red;">*</span></label>
                                                 <select type="text" class="form-control" id="documentacion_requerida"  name="documentacion_requerida" >
                                                 <?php foreach ($cat_opcion as $o): ?>
                                                    <option value="<?= $o->id_opcion ?>" <?= (isset($registro_pt->documentacion_requerida) && $registro_pt->documentacion_requerida == $o->id_opcion) ? 'selected' : '' ?> ><?= $o->des_opcion ?></option>
                                                  <?php endforeach; ?>
                                               </select>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="evidencia_entrega">Evidencia de entregable.<span style="color:red;">*</span></label>
                                                <select type="text" class="form-control" id="evidencia_entrega"  name="evidencia_entrega" >
                                               <?php foreach ($cat_opcion as $o): ?>
                                                    <option value="<?= $o->id_opcion ?>" <?= (isset($registro_pt->evidencia_entrega) && $registro_pt->evidencia_entrega == $o->id_opcion) ? 'selected' : '' ?> ><?= $o->des_opcion ?></option>
                                                <?php endforeach; ?>
                                               </select>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="otros">Otros</label>
                                                <input type="text" class="form-control" id="otros"  name="otros" value="ACUERDO SECRETARIAL" >
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="clausula_contrato">Claúsula del contrato.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="clausula_contrato" name="clausula_contrato" value="<?= (isset($registro_pt->clausula_contrato)) ? $registro_pt->clausula_contrato : 'TERCERA' ?>">
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="concepto_pago">Concepto del pago.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control"  autocomplete="off" id="concepto_pago" name="concepto_pago" value="<?= (isset($registro_pt->concepto_pago)) ? $registro_pt->concepto_pago : '' ?>" >
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="comision">Comisión / Reunión / Evento / Programa</label>
                                                <input type="text" class="form-control" id="comision"  name="comision" value="<?= (isset($registro_pt->comision)) ? $registro_pt->comision : 'Comisión / Reunión / Evento / Programa' ?>" >
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                              <div class="form-group" id="id_convenio">
                                                <label for="no_convenio">No. Convenio/Contrato</label>
                                                <div class="input-group">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                             V/T <i class="mdi mdi-chevron-down"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                                <a class="dropdown-item"  onclick="setConvenio('SECTURI/CONV/')">SECTURI/CONV/</a>
                                                                <a class="dropdown-item"  onclick="setConvenio('SECTURI/CTO/')">SECTURI/CTO/</a>
                                                        </div>
                                                     </div>
                                                    <input type="text" id="no_convenio" name="no_convenio" class="form-control" placeholder="025" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            AÑO <i class="mdi mdi-chevron-down"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item"  onclick="setAnio('/2025')">2025</a>
                                                            <a class="dropdown-item"  onclick="setAnio('/2024')">2024</a>
                                                            <a class="dropdown-item"  onclick="setAnio('/2023')">2023</a>
                                                        </div>
                                                    </div>
                                                </div>
                                               </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="no_consecutivo">No. Consecutivo.<span style="color:red;">*</span></label>
                                                <input type="number" class="form-control" autocomplete="off" id="no_consecutivo" name="no_consecutivo" placeholder="001"  value="<?= (isset($reserva->no_consecutivo)) ? $reserva->no_consecutivo : '' ?>" >
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                                         
                                        </div><!--end form-row-->
                    
                                  	<div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">

                                                <h4 class="mt-0 header-title">FIC</h4>
                                                <div class="table-responsive">
                                                   <table class="table table-bordered" id="makeEditable3">
                                                        <thead>
                                                            <tr>
                                                            <th>TIPO CONSUMO</th>
                                                            <th>ESTABLECIMIENTO</th>
                                                            <th>IMPORTE</th>
                                                            <th>FACTURAS</th>
                                                            <th>ACCIONES</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                            <td>
                                                                <select class="form-control catalogo" name="no_reserva[]">
                                                                <option value="restaurantes">ALIMENTOS | 3390</option>
                                                                <option value="hoteles">HOSPEDAJE | 3390</option>
                                                                <option value="restaurantes_geg">ALIMENTOS GEG | 2210</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-control catalogo-detalle" name="catalogo_detalle[]"></select>
                                                            </td>
                                                            <td>
                                                                <input type="text" autocomplete="off" class="form-control" name="importe[]" placeholder="0,000.00">
                                                            </td>
                                                            <td>
                                                             
                                                                <p class="text-muted mb-3">Factura PDF (Máx 5MB)</p>
                                                                <input id="factura_pdf_fic"  type="file" name="factura_pdf_fic[]" class="dropify" multiple accept=".pdf" />
                                                                <p class="text-muted mb-3">Factura XML (Máx 5MB)</p>
                                                                <input id="factura_xml_fic" type="file" name="factura_xml_fic[]" multiple class="dropify"  accept=".xml">
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-danger remove-row">
                                                                <i class="fas fa-trash"></i>
                                                                </button>
                                                            
                                                            </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <div class="text-right mt-2">
                                                        <!-- Contenedor mejorado para el botón -->
                                                        <a id="but_add" class="btn btn-primary text-white">
                                                            <i class="fas fa-plus"></i> Agregar Fila
                                                        </a>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-8"></div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>TOTAL:</label>
                                                                <input type="text" class="form-control font-weight-bold text-right" id="total_importe" name="total_importe" value="0.00" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end card-body-->
                                        </div>
                                    </div>
                                    <!-- end col -->
                                </div>
                               <!--  <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <p class="text-muted mb-3">Factura PDF (Máx 5MB)</p>
                                        <input id="factura_pdf_fic"  type="file" name="factura_pdf_fic[]" class="dropify" multiple accept=".pdf" />   
                                    </div>
                                    <div class="col-md-6 mb-3">
                                      
                                        <p class="text-muted mb-3">Factura XML (Máx 5MB)</p>
                                        <input id="factura_xml_fic" type="file" name="factura_xml_fic[]" multiple class="dropify"  accept=".xml">
                                    </div>
                                </div> -->
                               <a class="btn btn-gradient-danger" style="color:white" onclick="window.history.back()">Atrás</a>
                               <?php if (!$edita): ?>
                                <button class="btn btn-gradient-primary" id="btnGuardaFIC" type="submit">Guardar</button>
                               <?php endif; ?>
                            </form> <!--end form-->                                          
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->
             </div><!--end row-->
         </div><!-- container -->
     </div>
</div>


           <!--Form Wizard-->
<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
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

<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>

<script src="<?= base_url() ?>plugins/tiny-editable/mindmup-editabletable.js"></script>
<script src="<?= base_url() ?>plugins/tiny-editable/numeric-input-example.js"></script>
<script src="<?= base_url() ?>plugins/bootable/bootstable.js"></script>
<script src="<?= base_url() ?>assets/pages/jquery.tabledit.init.js"></script>
<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>
<script>
ini.inicio.formFIC();
$(document).ready(function() {
    $('.dropdown-toggle').dropdown();
});

    var restaurantes = <?= json_encode($restaurantes); ?>;
    var hoteles      = <?= json_encode($hoteles); ?>;
    console.log(hoteles);


function setConvenio(valor) {
    document.getElementById('no_convenio').value = valor;
}
function setAnio(anio) {
    let input = document.getElementById('no_convenio');
    input.value = input.value + anio;
}
	$(document).on('input', 'input[name="importe[]"]', function() {
	    calcularTotal();
	});
		$(document).on('input', 'input[name="propina[]"]', function() {
	    calcularTotal();
	});
	function calcularTotal() {
	    let total = 0;
	    
	    $('input[name="importe[]"]').each(function() {
	        // Elimina comas y convierte a número
	        const valor = parseFloat($(this).val().replace(/,/g, '')) || 0;
	        total += valor;
	    });
		 $('input[name="propina[]"]').each(function() {
	        // Elimina comas y convierte a número
	        const valor = parseFloat($(this).val().replace(/,/g, '')) || 0;
	        total += valor;
	    });
	    
	    // Formatea el total con separadores de miles
	    $('#total_importe').val(formatNumber(total.toFixed(2)));
	}
	function formatNumber(num) {
	    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
	}
	
	 
function llenarOpciones($select, lista, valueKey, textKey) {
  $select.empty();
  if (!Array.isArray(lista)) return;
  lista.forEach(function (item) {
    $select.append(new Option(item[textKey], item[valueKey]));
  });
}

// Mapa de catálogos → { lista, valueKey, textKey }
const CATALOGOS = {
  'restaurantes':     { lista: () => restaurantes, valueKey: 'id_restaurante_fic', textKey: 'dsc_restaurante' },
  'hoteles':          { lista: () => hoteles,      valueKey: 'id_hotel_fic',       textKey: 'dsc_hotel' },
  'restaurantes_geg': { lista: () => restaurantes, valueKey: 'id_restaurante_fic', textKey: 'dsc_restaurante' } // ejemplo
};

// --- Delegación: cuando cambie el select de catálogo en cualquier fila ---
$(document).on('change', '.catalogo', function () {
  const $row = $(this).closest('tr');
  const $detalle = $row.find('.catalogo-detalle');
  const tipo = $(this).val();

  const cfg = CATALOGOS[tipo];
  if (!cfg) { $detalle.empty(); return; }

  llenarOpciones($detalle, cfg.lista(), cfg.valueKey, cfg.textKey);
});

// --- Agregar fila ---
$('#but_add').on('click', function () {
  const newRow = `
    <tr>
      <td>
        <select class="form-control catalogo" name="no_reserva[]">
          <option value="restaurantes">ALIMENTOS | 3390</option>
          <option value="hoteles">HOSPEDAJE | 3390</option>
          <option value="restaurantes_geg">ALIMENTOS GEG | 2210</option>
        </select>
      </td>
      <td>
        <select class="form-control catalogo-detalle" name="catalogo_detalle[]"></select>
      </td>
      <td>
        <input type="text" autocomplete="off" class="form-control" name="importe[]" placeholder="0,000.00">
      </td>
      <td>
            <p class="text-muted mb-3">Factura PDF (Máx 5MB)</p>
            <input id="factura_pdf_fic"  type="file" name="factura_pdf_fic[]" class="dropify" multiple accept=".pdf" />
            <p class="text-muted mb-3">Factura XML (Máx 5MB)</p>
            <input id="factura_xml_fic" type="file" name="factura_xml_fic[]" multiple class="dropify"  accept=".xml">
      </td>
      <td>
        <button type="button" class="btn btn-sm btn-danger remove-row">
          <i class="fas fa-trash"></i>
        </button>
      </td>
    </tr>`;
  $('#makeEditable3 tbody').append(newRow);


	        // Inicializar máscara para el campo de importe (opcional)
	        $('#makeEditable3 tbody tr:last input[name="importe[]"]').inputmask('numeric', {
	            radixPoint: ".",
	            groupSeparator: ",",
	            digits: 2,
	            autoGroup: true,
	            prefix: '$ ',
	            rightAlign: false
	        });
		
	        calcularTotal();
});

// --- Eliminar fila ---
$(document).on('click', '.remove-row', function () {
  $(this).closest('tr').remove();
});

// Inicializar la primera fila
$('.catalogo').first().trigger('change');
	
</script>
