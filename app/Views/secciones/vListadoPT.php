


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
                                <li class="breadcrumb-item active">Listado <?= (isset($GO) && !empty($GO))?'GO':'PT'; ?></li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Solicitudes <?= (isset($GO) && !empty($GO))?'GO':'PT' ?></h4>
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
                                         
                                            <span>LISTA DE PROVEEDORES</span>
                                            <div class="form-group"> 
                                                <div class="input-group">
                                                    <span class="input-group-prepend">
                                                        <button id="icono_buscar" type="button" class="btn btn-gradient-primary"><i class="fas fa-search"></i></button>
                                                        <button id="icono_spinner" style="display:none;" type="button" class="btn btn-gradient-primary"><div class="spinner-border spinner-border-sm" role="status"></div></button>
                                                    </span>
                                                    <input autocomplete="off" type="text" id="buscar_proveedor" name="buscar_proveedor" class="form-control" placeholder="Busqueda General.">
                                                   
                                                </div>                                                    
                                            </div>
                                           
                                            <table id="datatableProveedores" class="table" data-toggle="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">ID PROVEEDOR</th>
                                                        <th class="text-center">RAZON SOCIAL</th>
                                                        <th class="text-center">RFC</th>
                                                        <th class="text-center">No PROVEEDOR</th>
                                                        <th class="text-center">ACCIONES</th>
                                                    </tr>
                                                    <!--end tr-->
                                                </thead>

                                                <tbody>
                                                    <?php foreach($proveedor as $p): ?>
                                                        
                                                    <tr>
                                                        <td class="text-center"><?= $p->id_proveedor?></td>
                                                        <td class="text-center"><?= $p->razon_social?></td>
                                                        <td class="text-center"><?= $p->rfc?></td>
                                                        <td class="text-center"><?= $p->no_proveedor?></td>
                                                        <td class="text-center">
                                                             <a style="color:white" onclick="ini.inicio.reserva(<?=$p->id_proveedor?>);" data-toggle="tooltip" data-placement="left" data-original-title="Seleccionar Proveedor"
                                                               
                                                                class="btn btn-gradient-success px-4"><i
                                                                    class="mdi mdi-arrow-collapse-right font-21"></i>
                                                            </a>
                                                           <!--      <?php if($p->fic == 1):?>
                                                            <a onclick="ini.inicio.pagoFic(<?= $p->id_proveedor ?>)"  data-toggle="tooltip" data-placement="left" data-original-title="Pagos FIC"
                                                                class="btn btn-gradient-dark px-4">FIC</i>
                                                            </a>
                                                            <?php endif;?>-->
                                                            <?php if($p->fic == 1):?>
                                                            <a href="<?php echo base_url(); ?>index.php/Principal/PagoFic/<?= $p->id_proveedor ?>"  data-toggle="tooltip" data-placement="left" data-original-title="Pagos FIC"
                                                                class="btn btn-gradient-dark px-4">FIC</i>
                                                            </a>
                                                            <?php endif;?> 
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

<style>
    #modalReserva .modal-dialog {
        width: 96%;
        max-width: 1400px;
    }

    #modalReserva .modal-content {
        max-height: 95vh;
        overflow-y: auto;
    }

    #modalReserva #makeEditable2 {
        min-width: 1050px;
    }
</style>

<div class="modal fade" id="modalReserva" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
                <main>
                   <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body step active">        
                                   <h2 class="mt-0 header-title">RESERVA</h2>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">        
                                                        <h4 class="mt-0 header-title">Datos del Proveedor</h4>
                                                           <?php if(isset($PT) && !empty($PT)): ?>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" onclick="ini.inicio.ocultarInstrumento(this);" class="custom-control-input" id="customSwitch1">
                                                            <label class="custom-control-label" for="customSwitch1">Sin Instrumento Jurídico</label>
                                                        </div>
                                                         <?php endif; ?>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="nombre_proveedor">Nombre Proveedor</label>
                                                                    <?php if(isset($PT) && !empty($PT)): ?>
                                                                      <input type="text" class="form-control" id="nombre_proveedor" name="nombre_proveedor">
                                                                    <?php endif; ?>
                                                                     <?php if(isset($GO) && !empty($GO)): ?>
                                                                      <input type="text" class="form-control" id="nombre_go" name="nombre_go" value="D-21" readonly>
                                                                    <?php endif; ?>
                                                                    <input type="hidden" id="id_proveedor" >
                                                                </div>
                                                              <div class="form-group">
                                                                    <label for="banco">Banco</label>
                                                                     <?php if(isset($PT) && !empty($PT)): ?>
                                                                   <select class="form-control select2" id="banco"></select>
                                                                    <?php endif; ?>
                                                                     <?php if(isset($GO) && !empty($GO)): ?>
                                                                      <input type="text" class="form-control select2" id="banco_go" name="banco_go" value="1109434000" readonly>
                                                                    <?php endif; ?>
                                                                    
                                                                </div>
                                                               <?php if(isset($PT) && !empty($PT)): ?>
                                                                     <div class="form-group" id="id_instrumento">
                                                                         <label for="instrumento">Istrumento Juridico</label>
                                                                         <input type="file" class="form-control" id="instrumento" name="instrumento" accept=".pdf">
                                                                     </div>
                                                                    
                                                                 <?php endif; ?>                                                                                      
                                                            </div>
                                                            <div class="col-lg-6" >
                                                                <div class="form-group">
                                                                    <label for="no_proveedor">No. Proveedor</label>
                                                                    <input type="text" class="form-control" id="no_proveedor" name="no_proveedor">
                                                                </div>
                                                                <?php if(isset($PT) && !empty($PT)): ?>
                                                                <div class="form-group" id="id_convenio">
                                                                    <label for="no_convenio">No. Convenio/Contrato</label>
                                                                    <div class="d-flex flex-wrap mb-2">
                                                                        <div class="custom-control custom-checkbox mr-4">
                                                                            <input type="checkbox" class="custom-control-input tipo-instrumento-check"
                                                                                id="tipo_instrumento_contrato" value="contrato">
                                                                            <label class="custom-control-label" for="tipo_instrumento_contrato">Contrato</label>
                                                                        </div>
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input tipo-instrumento-check"
                                                                                id="tipo_instrumento_convenio" value="convenio">
                                                                            <label class="custom-control-label" for="tipo_instrumento_convenio">Convenio</label>
                                                                        </div>
                                                                    </div>
                                                                    <select class="form-control select2" id="no_convenio" name="no_convenio" disabled>
                                                                        <option value="">Primero seleccione Contrato o Convenio</option>
                                                                        <?php foreach($solicitud_contrato as $sc): ?>
                                                                            <?php $numeroContrato = trim((string) $sc->no_contrato); ?>
                                                                            <?php if ($numeroContrato === '') continue; ?>
                                                                            <option value="<?= esc($numeroContrato, 'attr') ?>" data-tipo="contrato">
                                                                                <?= esc($numeroContrato) ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                        <?php foreach($solicitud_convenio as $sco): ?>
                                                                            <?php $numeroConvenio = trim((string) $sco->no_convenio); ?>
                                                                            <?php if ($numeroConvenio === '') continue; ?>
                                                                            <option value="<?= esc($numeroConvenio, 'attr') ?>" data-tipo="convenio">
                                                                                <?= esc($numeroConvenio) ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <?php endif; ?> 
                                                                 <div class="form-group" id="id_comentarios_instrumento">
                                                                     <label for="comentarios_instrumento">Comentarios</label>
                                                                     <textarea class="form-control" id="comentarios_instrumento" name="comentarios_instrumento" rows="3"></textarea>
                                                                 </div>
                                                            </div>  
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-body">
                                        
                                                                        <h4 class="mt-0 header-title">PRESUPUESTO</h4>
                                                                        <div class="table-responsive">
                                                                         <table class="table table-bordered" id="makeEditable2">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>PROYECTO-META</th>
                                                                                        <th>PARTIDA</th>
                                                                                        <th>FONDO</th>
                                                                                        <th>IMPORTE</th>
                                                                                        <th>ACCIONES</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
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
                                                                                        <td style="min-width: 250px;">
                                                                                            <select class="select2 form-control fondo-catalogo" name="fondo_catalogo[]">
                                                                                                <option value="">Seleccione</option>
                                                                                                <?php foreach($cat_fondo as $f): ?>
                                                                                                <?php $codigoFondo = trim((string) $f->dsc_fondo); ?>
                                                                                                <option value="<?= esc($codigoFondo, 'attr') ?>"><?= esc($codigoFondo) ?></option>
                                                                                                <?php endforeach; ?>
                                                                                            </select>
                                                                                            <div class="custom-control custom-checkbox mt-2">
                                                                                                <input type="checkbox" class="custom-control-input fondo-manual-check" id="fondo_manual_check_0">
                                                                                                <label class="custom-control-label small" for="fondo_manual_check_0">No encuentro el fondo</label>
                                                                                            </div>
                                                                                            <input type="text" class="form-control mt-2 d-none fondo-manual-input"
                                                                                                name="fondo_manual[]" maxlength="10" inputmode="numeric"
                                                                                                pattern="[0-9]{10}" placeholder="Fondo de 10 dígitos" disabled>
                                                                                        </td>
                                                                                        <td><input type="text" autocomplete="off" class="form-control" name="importe[]" placeholder="0,000.00"></td>
                                                                                        <td>
                                                                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                                                                <i class="fas fa-trash"></i>
                                                                                            </button>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <div class="text-right mt-2"> <!-- Contenedor mejorado para el botón -->
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
                                                                    </div><!--end card-body-->   
                                                                </div><!--end card-->
                                                                 <?php if(isset($PT) && !empty($PT)): ?>
                                                                  <button id="btn_guardar" class="btn btn-success">
                                                                        Guardar
                                                                  </button>
                                                                 <?php endif; ?>
                                                                  <?php if(isset($GO) && !empty($GO)): ?>
                                                                  <button id="btn_guardarGo" class="btn btn-success">
                                                                        Guardar
                                                                  </button>
                                                                 <?php endif; ?>
                                                            </div> <!-- end col -->
                                                        </div> <!-- end row -->                                                                    
                                                    </div><!--end card-body-->
                                                </div><!--end card-->
                                            </div><!--end col-->
                                        </div><!--end col-->                                                               
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
            </main> 
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

<script src="<?= base_url()?>plugins/tiny-editable/mindmup-editabletable.js"></script>
<script src="<?= base_url()?>plugins/tiny-editable/numeric-input-example.js"></script>
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
    $('#datatableCategorias,#datatableProveedores').DataTable({
        order: [[0, 'desc']],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: false,
    });
    // Función debounce para retrasar la ejecución
});
$(document).on('input', 'input[name="importe[]"]', function() {
    calcularTotal();
});
function calcularTotal() {
    let total = 0;
    
    $('input[name="importe[]"]').each(function() {
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
let fondoRowCounter = 1;

   $('#but_add').click(function() {
    const fondoCheckId = 'fondo_manual_check_' + fondoRowCounter++;
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
            <td style="min-width: 250px;">
                <select class="select2 form-control fondo-catalogo" name="fondo_catalogo[]">
                    <option value="">Seleccione</option>
                    <?php foreach($cat_fondo as $f): ?>
                    <?php $codigoFondo = trim((string) $f->dsc_fondo); ?>
                    <option value="<?= esc($codigoFondo, 'attr') ?>"><?= esc($codigoFondo) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" class="custom-control-input fondo-manual-check" id="${fondoCheckId}">
                    <label class="custom-control-label small" for="${fondoCheckId}">No encuentro el fondo</label>
                </div>
                <input type="text" class="form-control mt-2 d-none fondo-manual-input"
                    name="fondo_manual[]" maxlength="10" inputmode="numeric"
                    pattern="[0-9]{10}" placeholder="Fondo de 10 dígitos" disabled>
            </td>
            <td>
                <input autocomplete="off" type="text" class="form-control" name="importe[]" placeholder="0,000.000">
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger remove-row">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
        
        $('#makeEditable2 tbody').append(newRow);
        
        // Inicializar Select2 en la nueva fila
        $('#makeEditable2 tbody tr:last .select2').select2();
        
        // Inicializar máscara para el campo de importe (opcional)
        $('#makeEditable2 tbody tr:last input[name="importe[]"]').inputmask('numeric', {
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
        calcularTotal();
    });

    $(document).on('change', '.fondo-manual-check', function() {
        const fila = $(this).closest('tr');
        const selectFondo = fila.find('.fondo-catalogo');
        const inputFondo = fila.find('.fondo-manual-input');
        const capturaManual = $(this).is(':checked');

        if (capturaManual) {
            selectFondo.prop('disabled', true).val(null).trigger('change');
            inputFondo.prop('disabled', false).removeClass('d-none').val('').focus();
        } else {
            inputFondo.val('').prop('disabled', true).addClass('d-none');
            selectFondo.prop('disabled', false).trigger('change');
        }
    });

    $(document).on('input', '.fondo-manual-input', function() {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
    });
  // Inicializar Select2 cuando el modal se muestra
    $('#modalReserva').on('shown.bs.modal', function () {
        const bancoSelect = $('#banco');
        if (bancoSelect.length && !bancoSelect.hasClass('select2-hidden-accessible')) {
            bancoSelect.select2({
                placeholder: 'Selecciona un banco',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 0,
                dropdownParent: $('#modalReserva')
            });
        }

        $('.select2').not('#banco').each(function() {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                $(this).select2({
                    placeholder: "Seleccione una opción",
                    allowClear: true,
                    dropdownParent: $('#modalReserva')
                });
            }
        });
    });

    const opcionesInstrumentoOriginales = $('#no_convenio option[data-tipo]').clone();

    function filtrarInstrumentos(tipo) {
        const selectInstrumento = $('#no_convenio');

        if (selectInstrumento.hasClass('select2-hidden-accessible')) {
            selectInstrumento.select2('destroy');
        }

        selectInstrumento.empty().append(
            $('<option>', {
                value: '',
                text: tipo
                    ? 'Seleccione una opción'
                    : 'Primero seleccione Contrato o Convenio'
            })
        );

        if (tipo) {
            opcionesInstrumentoOriginales.each(function() {
                if ($(this).data('tipo') === tipo) {
                    selectInstrumento.append($(this).clone());
                }
            });
        }

        selectInstrumento.val('').prop('disabled', !tipo).select2({
            placeholder: tipo ? 'Seleccione una opción' : 'Primero seleccione Contrato o Convenio',
            allowClear: true,
            dropdownParent: $('#modalReserva')
        });
    }

    $(document).on('change', '.tipo-instrumento-check', function() {
        if ($(this).is(':checked')) {
            $('.tipo-instrumento-check').not(this).prop('checked', false);
            filtrarInstrumentos($(this).val());
        } else {
            filtrarInstrumentos('');
        }
    });

    $('#modalReserva').on('hidden.bs.modal', function () {
        $('.tipo-instrumento-check').prop('checked', false);
        filtrarInstrumentos('');
    });

function debounce(func, delay) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}
$('#buscar_proveedor').on('keyup', debounce(function () {
    ini.inicio.busquedaProveedor();
}, 500));

</script>
