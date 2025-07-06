


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
                                <li class="breadcrumb-item active">Listado PT</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Solicitudes PT</h4>
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
                                                        <button type="button" class="btn btn-gradient-primary"><i class="fas fa-search"></i></button>
                                                    </span>
                                                    <input autocomplete="off" type="text" id="buscar_proveedor" name="buscar_proveedor" class="form-control" placeholder="Busqueda General.">
                                                   
                                                </div>                                                    
                                            </div>
                                           
                                            <table id="datatableCategorias" class="table" data-toggle="table">
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
                                                             <a style="color:white" onclick="ini.inicio.reserva(<?=$p->id_proveedor?>);" title="Seccionar Proveedor"
                                                               
                                                                class="btn btn-gradient-success px-4"><i
                                                                    class="mdi mdi-arrow-collapse-right font-21"></i>
                                                            </a>
                                                          <!--   <a href="<?php echo base_url(); ?>index.php/Principal/Proveedor/<?= $p->id_proveedor ?>" title="Seccionar Proveedor"
                                                               
                                                                class="btn btn-gradient-success px-4"><i
                                                                    class="mdi mdi-arrow-collapse-right font-21"></i>
                                                            </a> -->
                                                          
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

<div class="modal fade" id="modalReserva" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="nombre_proveedor">Nombre Proveedor</label>
                                                                    <input type="text" class="form-control" id="nombre_proveedor" name="nombre_proveedor">
                                                                    <input type="hidden" id="id_proveedor" >
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="instrumento">Istrumento Juridico</label>
                                                                    <input type="file" class="form-control" id="instrumento" name="instrumento" accept=".pdf">
                                                                </div>                                                                                      
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="no_proveedor">No. Proveedor</label>
                                                                    <input type="text" class="form-control" id="no_proveedor" name="no_proveedor">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="no_convenio">No. Convenio/Contrato</label>
                                                                    <div class="input-group">
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                Seleccione <i class="mdi mdi-chevron-down"></i>
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item" href="#" onclick="setConvenio('SECTURI/CONV/')">SECTURI/CONV/</a>
                                                                                <a class="dropdown-item" href="#" onclick="setConvenio('SECTURI/CTO/')">SECTURI/CTO/</a>
                                                                            </div>
                                                                        </div>
                                                                        <input type="text" id="no_convenio" name="no_convenio" class="form-control" placeholder="025">
                                                                        <div class="input-group-append">
                                                                            <button type="button" class="btn btn-gradient-primary"><?= date('Y'); ?></button>
                                                                        </div>
                                                                    </div>
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
                                                                                                <option value="<?= $p->id_partida?>"><?= $p->partida ?></option>
                                                                                                <?php endforeach; ?>
                                                                                            </select>
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
                                                                  <button id="btn_guardar" class="btn btn-success">
                                                                        Guardar
                                                                  </button>
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
    $('#datatableCategorias').DataTable({
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
                    <option value="<?= $p->id_partida?>"><?= $p->partida ?></option>
                    <?php endforeach; ?>
                </select>
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
    });
  // Inicializar Select2 cuando el modal se muestra
    $('#modalReserva').on('shown.bs.modal', function () {
        $('.select2').select2({
            placeholder: "Seleccione una opción",
            allowClear: true
        });
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



function setConvenio(valor) {
    document.getElementById('no_convenio').value = valor;
}

</script>