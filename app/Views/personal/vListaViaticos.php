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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Secturi</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">seccion</a></li>
                                <li class="breadcrumb-item active">Listado</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado 9-LTAIPG26F1_IX</h4><br>
                        <a href="<?= base_url().'index.php/Usuario/Descarga' ?>" class="btn btn-gradient-primary px-4 float-right mt-0 mb-3 text-white">
                            <i class="dripicons-arrow-thin-down mr-2"></i>
                            Descargar Plantilla
                        </a>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <!--end row-->
            <div class="row">
                <div class="col-12">
                    <div class="tab-content detail-list" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="general_detail">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body"> 
                                            <table id="datatableProveedores" class="table" data-toggle="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">EJERCICIO</th>
                                                        <th class="text-center">INICIO</th>
                                                        <th class="text-center">TERMINO</th>
                                                        <th class="text-center">INTEGRANTE</th>
                                                        <th class="text-center">DENOMINACION</th>
                                                        <th class="text-center">ACCIONES</th>
                                                    </tr>
                                                    <!--end tr-->
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($datos as $p): ?>
                                                    <tr>
                                                        <td class="text-center"><?= $p->ejercicio ?></td>
                                                        <td class="text-center"><?= date('d/m/Y', strtotime($p->fecha_inicio)) ?></td>
                                                        <td class="text-center"><?= date('d/m/Y', strtotime($p->fecha_termino)) ?></td>
                                                        <td class="text-center"><?= $p->dsc_tipo_funcionario ?></td>
                                                        <td class="text-center"><?= $p->dsc_denominacion ?></td>
                                                        <td class="text-center">
                                                            <div style="display: flex; justify-content: center; gap: 5px;">
                                                                <!-- 1. Botón Ver -->
                                                                <button type="button" 
                                                                    onclick="ini.inicio.consultarViatico(<?= $p->id_juridico_viatico ?>);" 
                                                                    class="btn btn-gradient-success btn-sm" 
                                                                    title="Ver Detalle" style="color:white; padding: 5px 10px;">
                                                                    <i class="mdi mdi-eye"></i>
                                                                </button>
                                                                <!-- 2. Botón Editar -->
                                                                <button type="button" 
                                                                    onclick="ini.inicio.editarViatico(<?= $p->id_juridico_viatico ?>);" 
                                                                    class="btn btn-gradient-info btn-sm" 
                                                                    title="Editar Registro" style="color:white; padding: 5px 10px;">
                                                                    <i class="mdi mdi-pencil"></i>
                                                                </button>
                                                                <!-- 3. Botón Eliminar -->
                                                                <button type="button" 
                                                                    onclick="ini.inicio.eliminarViatico(<?= $p->id_juridico_viatico ?>);" 
                                                                    class="btn btn-gradient-danger btn-sm" 
                                                                    title="Eliminar" style="color:white; padding: 5px 10px;">
                                                                    <i class="mdi mdi-delete"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- container -->
    </div>
    <!-- end page content -->
</div>

<!--Inicio Modal -->
<div class="modal fade" id="modalDetalleViatico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModal">Detalle del Registro de Viático</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <label><b>Ejercicio:</b></label>
                        <p id="det_ejercicio"></p>
                    </div>
                    <div class="col-md-8">
                        <label><b>Integrante:</b></label>
                        <p id="det_integrante"></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <label><b>Nombre Completo:</b></label>
                        <p id="det_nombre"></p>
                    </div>
                    <div class="col-md-6">
                        <label><b>Cargo:</b></label>
                        <p id="det_cargo"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label><b>Motivo del Encargo:</b></label>
                        <p id="det_motivo"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label><b>Importe Total:</b></label>
                        <p id="det_importe" class="text-success font-weight-bold"></p>
                    </div>
                    <div class="col-md-4">
                        <label><b>Fecha Salida:</b></label>
                        <p id="det_salida"></p>
                    </div>
                    <div class="col-md-4">
                        <label><b>Fecha Regreso:</b></label>
                        <p id="det_regreso"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal para Editar/Crear -->
<div class="modal fade" id="modal_form_viatico" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document"> <!-- modal-xl para que quepa bien -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestión de Viático</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="form_viatico" enctype="multipart/form-data">
                    <input type="hidden" id="id_juridico_viatico" name="id_juridico_viatico" value="">
                    <div class="form-row">
                        <!-- Dirección Responsable -->
                        <div class="col-md-4 mb-3">
                           <label for="ejercicio">Ejercicio <span class="text-danger">*</span></label>
                            <select class="form-control" id="ejercicio" name="ejercicio" required>
                                <option value="2025">2026</option>
                                <option value="2024">2025</option>
                                <option value="2024">2024</option>
                            </select>
                        </div><!--end col-->
                        <div class="col-md-4 mb-3">
                            <label for="fecha_inicio">Fec. de inicio del periodo que se informa<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                        </div><!--end col-->
                        <!-- Fecha de Trámite -->
                        <div class="col-md-4 mb-3">
                            <label for="fecha_termino">Fec. de término del periodo que se informa<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha_termino" name="fecha_termino" required>
                        </div><!--end col-->
                    </div><!--end form-row-->
                    <div class="form-row">
                        <div class="col-md-4 mb-6">
                            <label for="formato_establecido">Tipo de integrante del sujeto obligado<span style="color:red;">*</span></label>
                            <select class="form-control" id="tipo_integrante" name="tipo_integrante" required>
                                <?php foreach ($cat_funcionario as $p): ?>
                                <option value="<?= $p->id_tipo_funcionario ?>" ><?= $p->dsc_tipo_funcionario ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div><!--end col-->
                        <div class="col-md-4 mb-3">
                            <label for="clave_nivel">Clave o nivel del puesto<span style="color:red;">*</span></label>
                            <select class="form-control select2" id="clave_nivel" name="clave_nivel" required>
                                <?php for ($i = 1; $i <= 20; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div><!--end col-->
                        <div class="col-md-4 mb-3">
                            <label for="denominacion_puesto">Denominación del puesto<span style="color:red;">*</span></label>
                            <select class="form-control select2" id="denominacion_puesto" name="denominacion_puesto" required>
                                <?php foreach ($deno_puesto as $d): ?>
                                <option value="<?= $d->id_denominacion ?>" ><?= $d->dsc_denominacion ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div><!--end col-->
                    </div><!--end form-row-->
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="denomicacion_cargo">Denominación del cargo<span style="color:red;">*</span></label>
                            <select class="form-control select2" id="denomicacion_cargo" name="denomicacion_cargo" required>
                                <?php foreach ($deno_cargo as $d): ?>
                                <option value="<?= $d->id_cargo ?>" ><?= $d->dsc_cargo ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div><!--end col-->
                        <div class="col-md-4 mb-3">
                            <label for="area_adscripcion">Area de adscripcion<span style="color:red;">*</span></label>
                            <select class="form-control" id="area_adscripcion" name="area_adscripcion" required>
                                <?php foreach ($cat_area as $c): ?>
                                <option value="<?= $c->id_area_adscripcion ?>" ><?= $c->dsc_adscripcion ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div><!--end col-->
                        <div class="col-md-4 mb-3">
                            <label for="nombre_completo">Nombre<span style="color:red;">*</span></label>
                            <select class="form-control select2" id="nombre_completo" name="nombre_completo" required>
                                <?php foreach ($usuarios as $p): ?>
                                <option value="<?= $p->id_usuario ?>" <?= (session()->get('id_usuario')) == $p->id_usuario ? 'selected' : '' ?>><?= $p->nombre_completo ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div><!--end col-->
                    </div><!--end form-row-->
                    <div class="form-row">
                        <div class="col-md-4 mb-6">
                            <label for="tipo_gasto">Tipo de gasto<span style="color:red;">*</span></label>
                            <select class="form-control select2" id="tipo_gasto" name="tipo_gasto">
                                <?php foreach ($cat_gasto as $c): ?>
                                <option value="<?= $c->id_gasto ?>" ><?= $c->dsc_gasto ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div><!--end col-->
                        <div class="col-md-4 mb-3">
                            <label for="denominacion_encargo">Denominación del encargo o comisión<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="denominacion_encargo" name="denominacion_encargo" required>
                        </div><!--end col-->
                        <div class="col-md-4 mb-6">
                            <label for="tipo_viaje">Tipo de Viaje<span style="color:red;">*</span></label>
                            <select class="form-control" id="tipo_viaje" name="tipo_viaje" required>
                                <?php foreach ($cat_viaje as $c): ?>
                                <option value="<?= $c->id_viaje ?>" ><?= $c->dsc_viaje ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div><!--end col-->
                    </div><!--end form-row-->
                    <div class="form-row"> 
                        <div class="col-md-4 mb-3">
                            <label for="no_personas">Número de personas encargo o comisión<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="no_personas" autocomplete="off" name="no_personas">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="importe_ejercicio">Importe ejercido por el total de acompañantes<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="importe_ejercicio" autocomplete="off" name="importe_ejercicio">
                        </div>
                        <!-- País -->
                        <div class="col-md-4 mb-3">
                            <label for="pais_origen">País origen <span style="color:red;">*</span></label>
                            <select class="select2 form-control" id="pais_origen" name="pais_origen">
                                <?php foreach ($cat_pais as $p): ?>
                                <option value="<?= $p->id_pais ?>"><?= $p->dsc_pais ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- En la vista -->
                    <div class="form-row">
                        <!-- Estado (select) -->
                        <div class="col-md-4 mb-3" id="wrap_estado_select">
                            <label for="estado_origen">Estado origen <span style="color:red;">*</span></label>
                            <select class="select2 form-control" id="estado_origen" name="estado_origen_id">
                                <!-- Lo llena JS si país = México -->
                            </select>
                        </div>
                        <!-- Estado (texto libre) -->
                        <div class="col-md-4 mb-3" id="wrap_estado_text">
                            <label for="estado_origen_text">Estado origen <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="estado_origen_text" name="estado_origen_text" placeholder="Escribe el estado">
                        </div>
                        <!-- Municipio (select) -->
                        <div class="col-md-4 mb-3" id="wrap_municipio_select">
                            <label for="municipio_origen">Municipio origen <span style="color:red;">*</span></label>
                            <select class="select2 form-control" id="municipio_origen" name="municipio_origen_id">
                                <!-- Lo llena JS si estado = Guanajuato -->
                            </select>
                        </div>
                        <!-- Municipio (texto libre) -->
                        <div class="col-md-4 mb-3" id="wrap_municipio_text">
                            <label for="municipio_origen_text">Municipio origen <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="municipio_origen_text" name="municipio_origen_text" placeholder="Escribe el municipio">
                        </div>
                        <!-- País Destino-->
                        <div class="col-md-4 mb-3">
                            <label for="pais_destino">País destino <span style="color:red;">*</span></label>
                            <select name="pais_destino" id="pais_destino" class="form-control" required>
                                <?php foreach ($cat_pais as $p): ?>
                                <option value="<?= $p->id_pais ?>"><?= $p->dsc_pais ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <!-- Estado (select) -->
                        <div class="col-md-4 mb-3" id="destino_estado_select">
                            <label for="estado_destino">Estado destino<span style="color:red;">*</span></label>
                            <select class="select2 form-control" id="estado_destino" name="estado_destino_id">
                                <!-- Lo llena JS si país = México -->
                            </select>
                        </div>
                        <!-- Estado (texto libre) -->
                        <div class="col-md-4 mb-3" id="destino_estado_text">
                            <label for="estado_destino_text">Estado destino<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="estado_destino_text" name="estado_destino_text" placeholder="Escribe el estado">
                        </div>
                        <!-- Municipio (select) -->
                        <div class="col-md-4 mb-3" id="destino_municipio_select">
                            <label for="municipio_destino">Municipio destino<span style="color:red;">*</span></label>
                            <select class="select2 form-control" id="municipio_destino" name="municipio_destino">
                                <!-- Lo llena JS si estado = Guanajuato -->
                            </select>
                        </div>
                        <!-- Municipio (texto libre) -->
                        <div class="col-md-4 mb-3" id="destino_municipio_text">
                            <label for="municipio_destino_text">Municipio/Ciudad origen <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="municipio_destino_text" name="municipio_destino_text" placeholder="Escribe el municipio">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="motivo_encargo">Motivo del encargo o comisión<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" autocomplete="off" id="motivo_encargo" name="motivo_encargo" >
                        </div><!--end col-->
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="fec_salida">Fecha de salida del encargo o comisión<span style="color:red;">*</span></label>
                            <input type="date" class="form-control" autocomplete="off" id="fec_salida" name="fec_salida" >
                        </div><!--end col-->
                        <div class="col-md-4 mb-3">
                            <label for="fec_regreso">Fecha de regreso del encargo o comisión<span style="color:red;">*</span></label>
                            <input type="date" class="form-control" autocomplete="off" id="fec_regreso" name="fec_regreso">
                        </div><!--end col-->
                        <div class="col-md-4 mb-3">
                            <label for="importe_ejercicio_partida">Importe total erogado con motivo del encargo o comisión<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" autocomplete="off" id="importe_ejercicio_partida" name="importe_ejercicio_partida" >
                        </div><!--end col-->
                    </div><!--end form-row-->
                                        
                    <div class="form-row">    
                        <div class="col-md-4 mb-3">
                            <label for="importe_total">Importe total de gastos no erogados derivados del encargo o comisión<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" autocomplete="off" id="importe_total" name="importe_total" >
                        </div><!--end col-->
                        <div class="col-md-4 mb-3">
                            <label for="fec_entrega_informe">Fecha de entrega del informe de la comisión o encargo<span style="color:red;">*</span></label>
                            <input type="date" class="form-control" autocomplete="off" id="fec_entrega_informe" name="fec_entrega_informe">
                        </div><!--end col-->
                        <div class="col-md-4 mb-3">
                            <label for="hipervinculo_informe">Hipervínculo al informe de la comisión o encargo encomendado<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" autocomplete="off" id="hipervinculo_informe" name="hipervinculo_informe">
                        </div><!--end col-->
                    </div><!--end form-row-->
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="hipervinculo_factura">Hipervínculo a las facturas o comprobantes.<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" autocomplete="off" id="hipervinculo_factura" name="hipervinculo_factura" >
                        </div><!--end col-->
                        <div class="col-md-4 mb-3">
                            <label for="hipervinculo_normativa">Hipervínculo a normativa que regula los gastos por concepto de viáticos y gastos de representación<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" autocomplete="off" id="hipervinculo_normativa" name="hipervinculo_normativa">
                        </div><!--end col-->
                        <div class="col-md-4 mb-3">
                            <label for="area_responsable">Área(s) responsable(s) que genera(n), posee(n), publica(n) y actualizan la información<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" autocomplete="off" id="area_responsable" name="area_responsable">
                        </div><!--end col-->
                    </div><!--end form-row-->
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="fec_actualizacion">Fecha de actualización<span style="color:red;">*</span></label>
                            <input type="date" class="form-control" autocomplete="off" id="fec_actualizacion" name="fec_actualizacion">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="nota">Notas<span style="color:red;">*</span></label>
                            <textarea type="text" class="form-control" name="nota" id="nota"></textarea> 
                        </div><!--end col-->
                                            
                    </div><!--end form-row-->
                    <a class="btn btn-gradient-danger" style="color:white" onclick="window.history.back()">Atrás</a>
                    <button class="btn btn-gradient-primary" id="btnGuardarViatico" type="submit">Guardar</button>
                </form> <!--end form-->
            </div>
        </div>
    </div>
</div>
<!--FIN MODAL -->
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
<script src="<?= base_url() ?>assets/pages/jquery.tabledit.init.js"></script> 
<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>


<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        ini.inicio.guardarReserva();
        ini.inicio.guardarGo();
        $('#datatableCategorias,#datatableProveedores').DataTable({
            order: [[0, 'desc']],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
            },
            destroy: true,
            searching: true,
        });
        // Función debounce para retrasar la ejecución
    });
</script>