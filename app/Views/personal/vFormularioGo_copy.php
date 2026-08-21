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
						<h4 class="page-title">Formulario GO</h4>
					</div>
					<!--end page-title-box-->
				</div>
				<!--end col-->
			</div>

			<!-- end page title end breadcrumb -->
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">
							<h3 class="mt-0 header-title">TITULAR: <strong>GOBIERNO DEL ESTADO DE GUANAJUATO SFIYA SECRETARIA DE TURISMO</strong></h3>
							<p class="text-muted mb-3">
								RFC: GEG850101FQ2
							</p>
							<div class="form-row">
								<!-- Dirección Responsable -->
								<div class="col-md-4 mb-3">
									<label for="tipo_titularidad">Tipo de titularidad<span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="tipo_titularidad" name="tipo_titularidad" value="INDIVIDUAL" autocomplete="off" readonly>
								</div>
								<!--end col-->

								<div class="col-md-4 mb-3">
									<label for="no_proveedor">No. Cuenta<span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="no_proveedor" name="no_proveedor" value="51859130201" autocomplete="off" readonly>
								</div>
								<!--end col-->
								<!-- Fecha de Trámite -->
								<div class="col-md-4 mb-3">
									<label for="banco">Banco <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="banco" name="banco" value="BAN BAJIO" autocomplete="off" readonly>

								</div>
								<!--end col-->
							</div>
							<!--end form-row-->
							<div class="form-row">

								<div class="col-md-4 mb-3">
									<label for="director_generar">Clabe<span style="color:red;">*</span></label>
									<input type="text" class="form-control" id="director_generar" value="030210518591302019" name="director_generar" readonly>
								</div>
								<!--end col-->
								<div class="col-md-4 mb-3">
									<label for="proveedor">Proveedor<span style="color:red;">*</span></label>
									<input type="text" class="form-control" id="proveedor" value="D21" name="proveedor" readonly>
								</div>
								<!--end col-->
							</div>
							<!--end form-row-->
							<div class="row">
								<div class="col-12">
									<div class="card">
										<div class="card-body">

											<h4 class="mt-0 header-title">PRESUPUESTO</h4>
											<div class="table-responsive">
												<table class="table table-bordered" id="makeEditable3">
													<thead>
														<tr>
															<th>PROYECTO-META</th>
															<th>PARTIDA</th>
															<th>IMPORTE</th>
															<th>PROPINA</th>
															<th>ACCIONES</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>
																<select id="id_proyecto" class="select2 form-control" name="proyecto[]">
                                                                    <option value="">Seleccione</option>
                                                                <?php foreach($cat_proyecto as $c): ?>
                                                                    <option value="<?= $c->id_proyecto?>"><?= $c->proyecto ?></option>
                                                                <?php endforeach; ?>
                                                                </select>
															</td>
															<td>
																<select id="id_partida" class="select2 form-control" name="partida[]">
                                                                    <option value="">Seleccione</option>
                                                                    <?php foreach($cat_partida as $p): ?>
                                                                    <option value="<?= $p->id_partida?>"><?= $p->cuenta_cable ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
															</td>
															<td><input type="text" autocomplete="off" class="form-control" name="importe[]" placeholder="0,000.00"></td>
															<td><input type="text" autocomplete="off" class="form-control" name="propina[]" placeholder="0,000.00"></td>
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
													<button id="but_add" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Agregar Fila
                                                    </button>
												</div>
												<div class="row mt-3">
													<div class="col-md-8"></div>
													<div class="col-md-4">
														<div class="form-group">
															<label>TOTAL:</label>
															<input type="text" class="form-control font-weight-bold text-right" id="total_importe" value="0.00" readonly>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!--end card-body-->
									</div>
									<!--end card-->
									<button id="btn_guardarGo" class="btn btn-success">
                                        Guardar
                                    </button>

								</div>
								<!-- end col -->
							</div>
							<!-- end row -->
						</div>
						<!--end card-body-->
					</div>
					<!--end card-->
				</div>
				<!--end col-->
			</div>
			<!--end row-->
		</div>
		<!-- container -->
	</div>
</div>
<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- App css -->
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url()?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<!-- jQuery  -->

<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>


<!-- Required datatable js -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

<!-- App js -->
<script src="<?= base_url()?>assets/js/app.js"></script>
<script src="<?= base_url()?>assets/js/waves.js"></script>
<script src="<?= base_url()?>assets/js/feather.min.js"></script>


<script src="<?= base_url()?>plugins/bootable/bootstable.js"></script>
<script src="<?= base_url()?>assets/pages/jquery.tabledit.init.js"></script>
<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>


<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
<script>
	$(document).ready(function() {
	    ini.inicio.guardarReserva();
	    ini.inicio.guardarGo();
	
          $('#id_proyecto,#id_partida').select2({
                placeholder: "Selecciona una opción",
                allowClear: true,
                width: 'resolve' // Ajusta el ancho automáticamente
            });
	     $('.add-file').on('click', function(e) {
	                e.preventDefault();
	                const inputId = $(this).data('target');
	                $(inputId).click();
	            })
	    // Función debounce para retrasar la ejecución
	});
	  
	            
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
	   $('#but_add').click(function() {
	    var newRow = `<tr>
	            <td>
	                <select class="select2 form-control" name="proyecto[]">
	                    <option value="">Seleccione</option>
	                    <?php foreach($cat_proyecto as $c): ?>
	                    <option value="<?= $c->id_proyecto?>"><?= $c->proyecto ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </td>
	            <td>
	                <select class="select2 form-control" name="partida[]">
	                    <option value="">Seleccione</option>
	                    <?php foreach($cat_partida as $p): ?>
	                    <option value="<?= $p->id_partida?>"><?= $p->cuenta_cable ?></option>
	                    <?php endforeach; ?>
	                </select>
	            </td>
	            <td>
	                <input autocomplete="off" type="text" class="form-control" name="importe[]" placeholder="0,000.000">
	            </td>
				<td>
	                <input autocomplete="off" type="text" class="form-control" name="propina[]" placeholder="0,000.000">
	            </td>
	            <td>
	                <button type="button" class="btn btn-sm btn-danger remove-row">
	                    <i class="fas fa-trash"></i>
	                </button>
	            </td>
	        </tr>`;
	        
	        $('#makeEditable3 tbody').append(newRow);
	        
	        // Inicializar Select2 en la nueva fila
	        $('#makeEditable3 tbody tr:last .select2').select2();
	        
	        // Inicializar máscara para el campo de importe (opcional)
	        $('#makeEditable3 tbody tr:last input[name="importe[]"]').inputmask('numeric', {
	            radixPoint: ".",
	            groupSeparator: ",",
	            digits: 2,
	            autoGroup: true,
	            prefix: '$ ',
	            rightAlign: false
	        });
			 $('#makeEditable3 tbody tr:last input[name="propina[]"]').inputmask('numeric', {
	            radixPoint: ".",
	            groupSeparator: ",",
	            digits: 2,
	            autoGroup: true,
	            prefix: '$ ',
	            rightAlign: false
	        });
	        calcularTotal();
	    });
	    $(document).on('click', '.remove-row', function() {
	        $(this).closest('tr').remove();
	    });
	
	
</script>