

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
                                    <h3 class="mt-0 header-title">PROVEEDOR: <strong><?= (isset($reserva->razon_social) && !empty($reserva->razon_social)) ? $reserva->razon_social : '' ?></strong></h3>
                                    <p class="text-muted mb-3" >
                                        <?= (isset($reserva->no_proveedor) && !empty($reserva->no_proveedor)) ? 'No. Proveedor ' . $reserva->no_proveedor : '' ?>
                                    </p>
                                   <form id="form_fic_editar" enctype="multipart/form-data">
                                        <input type="hidden" name="id_proveedor" id="id_proveedor" value="<?= (isset($reserva->id_proveedor) && !empty($reserva->id_proveedor)) ? $reserva->id_proveedor : '' ?>" >
                                        <input type="hidden" id="id_reserva" name="id_reserva" value="<?= $reserva->id_reserva ?>">
                                        <input type="hidden" name="editar" id="editar" value="<?= $edita ?>">
                                        <?php if (isset($datos->id_registro_pt) && !empty($datos->id_registro_pt)): ?>
                                        <input type="hidden" name="id_registro_pt" id="id_registro_pt" value="<?= $datos->id_registro_pt ?>">
                                        <?php endif; ?>
                                       <div class="form-row">
                                            <!-- Dirección Responsable -->
                                            <div class="col-md-4 mb-3">
                                                <label for="direccion_responsable">Dirección Responsable <span class="text-danger">*</span></label>
                                                <select class="form-control" id="direccion_responsable" name="direccion_responsable" required>
                                                    <?php foreach ($cat_area as $a): ?>
                                                    <option value="<?= $a->id_area ?>" <?php echo ($a->id_area == 4) ? 'selected' : ''; ?>>
                                                        <?= $a->dsc_area ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                  
                                            </div><!--end col-->
                                            
                                            <!-- Tipo de PT -->
                                            <div class="col-md-4 mb-3">
                                                <label for="tipo_pt">Tipo de PT <span class="text-danger">*</span></label>
                                                <select class="form-control" id="tipo_pt" name="tipo_pt" >
                                                    <?php foreach ($cat_tipo as $p): ?>
                                                        <option value="<?= $p->id_tipo ?>" <?= (isset($registro_pt->id_tipo) && $datos->id_tipo == $p->id_tipo) ? 'selected' : '' ?> ><?= $p->des_tipo ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                              
                                            </div><!--end col-->
                                            
                                            <!-- Fecha de Trámite -->
                                            <div class="col-md-4 mb-3">
                                                <label for="fecha_tramite">Fecha de Trámite <span class="text-danger">*</span></label>
                                              <input type="date" class="form-control" id="fecha_tramite" name="fecha_tramite" 
                                                value="<?= isset($datos->fecha_tramite) ? date('Y-m-d', strtotime($datos->fecha_tramite)) : date('Y-m-d') ?>" 
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
                                                    <option value="<?= $b->id_proveedor_banco ?>" ><?= $b->banco . ' / ' . $b->no_cuenta . ' / ' . $b->clabe ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="fecha_gasto_inicio">Fecha de gasto inicio <span style="color:red;">*</span></label>
                                                <input type="date" class="form-control" id="fecha_gasto_inicio" name="fecha_gasto_inicio" 
                                                value="<?= isset($datos->fecha_gasto_inicio) ? date('Y-m-d', strtotime($datos->fecha_gasto_inicio)) : date('Y-m-d') ?>" 
                                                required>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                 <label for="fecha_gasto_fin">Fecha de gasto fin <span style="color:red;">*</span></label>
                                                <input type="date" class="form-control" id="fecha_gasto_fin" name="fecha_gasto_fin" 
                                                value="<?= isset($datos->fecha_gasto_fin) ? date('Y-m-d', strtotime($datos->fecha_gasto_fin)) : date('Y-m-d') ?>" 
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
                                                    <option value="<?= $o->id_opcion ?>" <?= (isset($datos->documentacion_comprobatoria) && $datos->documentacion_comprobatoria == $o->id_opcion) ? 'selected' : '' ?> ><?= $o->des_opcion ?></option>
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
                                                    <option value="<?= $o->id_opcion ?>" <?= (isset($datos->documentacion_requerida) && $datos->documentacion_requerida == $o->id_opcion) ? 'selected' : '' ?> ><?= $o->des_opcion ?></option>
                                                  <?php endforeach; ?>
                                               </select>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="evidencia_entrega">Evidencia de entregable.<span style="color:red;">*</span></label>
                                                <select type="text" class="form-control" id="evidencia_entrega"  name="evidencia_entrega" >
                                               <?php foreach ($cat_opcion as $o): ?>
                                                    <option value="<?= $o->id_opcion ?>" <?= (isset($datos->evidencia_entrega) && $datos->evidencia_entrega == $o->id_opcion) ? 'selected' : '' ?> ><?= $o->des_opcion ?></option>
                                                <?php endforeach; ?>
                                               </select>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="otros">Otros</label>
                                                <input type="text" class="form-control" id="otros"  name="otros" value="<?=$datos->otros?>" >
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label for="clausula_contrato">Claúsula del contrato.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="clausula_contrato" name="clausula_contrato" value="<?= (isset($datos->clausula_contrato)) ? $datos->clausula_contrato : 'TERCERA' ?>">
                                                <div class="invalid-feedback">
                                                    Campo no Valido
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="concepto_pago">Concepto del pago.<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control"  autocomplete="off" id="concepto_pago" name="concepto_pago" value="<?= (isset($datos->concepto_pago)) ? $datos->concepto_pago : '' ?>" >
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <label for="comision">Comisión / Reunión / Evento / Programa</label>
                                                <input type="text" class="form-control" id="comision"  name="comision" value="<?= (isset($datos->comision)) ? $datos->comision : 'Comisión / Reunión / Evento / Programa' ?>" >
                                                <div class="invalid-feedback">
                                                    Please provide a valid state.
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group" id="id_convenio">
                                                    <label for="no_convenio">No. Convenio/Contrato</label>
                                                    <input type="text" class="form-control" autocomplete="off" id="no_convenio" name="no_convenio" value="<?= (isset($reserva->no_convenio)) ? $reserva->no_convenio :'' ?>" >
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="no_consecutivo">No. Consecutivo.<span style="color:red;">*</span></label>
                                                <input type="number" class="form-control" autocomplete="off" id="no_consecutivo" name="no_consecutivo"   value="<?= (isset($datos->no_consecutivo)) ? $datos->no_consecutivo :'' ?>" >
                                              
                                            </div><!--end col-->
                                         
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group" id="id_convenio">
                                                    <label for="partida">Partida</label>
                                                    <input type="text" class="form-control" autocomplete="off" id="partida" name="partida" value="<?= (isset($reserva->partida)) ? $reserva->partida :'' ?>" >
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="no_reserva">No. Reserva.<span style="color:red;">*</span></label>
                                                <input type="number" class="form-control" autocomplete="off" id="no_reserva" name="no_reserva"   value="<?= (isset($reserva->no_reserva)) ? $reserva->no_reserva :'' ?>" >
                                              
                                            </div><!--end col-->
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="partida">Total Importe</label>
                                                    <input type="text" class="form-control" autocomplete="off" id="total_importe" name="total_importe" value="<?= (isset($reserva->total_importe)) ? $reserva->total_importe :'' ?>" >
                                                </div>
                                            </div>
                                        </div><!--end form-row-->
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                  <label for="factura_pdf_fic">PDF</label>
                                                  <input id="factura_pdf_fic"  type="file" name="factura_pdf_fic[]" class="dropify" multiple accept=".pdf" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                              <div class="form-group">
                                                  <label for="factura_xml_fic">XML</label>
                                                  <input id="factura_xml_fic" type="file" name="factura_xml_fic[]" multiple class="dropify"  accept=".xml">
                                              </div><!--end col-->
                                            </div><!--end col-->
                                        </div><!--end form-row-->
                    

                               <a class="btn btn-gradient-danger" style="color:white" onclick="window.history.back()">Atrás</a>
                               <?php if ($edita == 1): ?>
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


<script src="<?= base_url() ?>plugins/bootable/bootstable.js"></script>
<script src="<?= base_url() ?>assets/pages/jquery.tabledit.init.js"></script>
<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>
<script>
ini.inicio.formEditarFIC();
$(document).ready(function() {
    $('.dropdown-toggle').dropdown();
});


	
</script>
