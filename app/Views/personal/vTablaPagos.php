


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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Pagina</a></li>
                                <li class="breadcrumb-item active">Listado de Pagos</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Pagos</h4>
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
    <?php
    // Función para limpiar y convertir importes
    function limpiarImporte($importe) {
        if (is_numeric($importe)) {
            return floatval($importe);
        }
        // Eliminar comas, símbolos de moneda, etc.
        $limpio = str_replace(['$', ',', ' '], '', $importe);
        return floatval($limpio);
    }
    
    // Calcular suma total de importes
    $suma_importes = 0;
    foreach($pagos as $p) {
        $suma_importes += limpiarImporte($p->importe);
    }
    
    // Calcular lo que falta
    $total_limpiado = limpiarImporte($total_importe);
    $falta = $total_limpiado - $suma_importes;
    
    // Determinar clase CSS para el "falta"
    $clase_falta = ($falta > 0) ? 'text-danger' : 'text-success';
    $texto_falta = ($falta > 0) ? 'FALTA POR PAGAR' : 'PAGO COMPLETADO';
    ?>
    
    <div class="mb-3 p-3 bg-light rounded">
        <div class="row">
            <div class="col-md-4">
                <strong>TOTAL REQUERIDO:</strong><br>
                <span class="h5">$<?= number_format($total_limpiado, 2) ?></span>
            </div>
            <div class="col-md-4">
                <strong>TOTAL PAGADO:</strong><br>
                <span class="h5 text-success">$<?= number_format($suma_importes, 2) ?></span>
            </div>
            <div class="col-md-4">
                <strong><?= $texto_falta ?>:</strong><br>
                <span class="h5 <?= $clase_falta ?>">$<?= number_format(abs($falta), 2) ?></span>
            </div>
        </div>
    </div>
    
    <button 
        class="btn btn-gradient-primary px-4 float-right mt-0 mb-3"
        onclick="history.go(-1)">
        <i class="mdi mdi-arrow-left mr-2"></i>Regresar Atrás
    </button>
    <?= $texto_falta == 'PAGO COMPLETADO'?'<a href="javascript:void(0)" onclick="ini.inicio.finalizarPago('.$id_reserva.')" ><i class="em em-100"></i></a>':''  ?>
    
    <table id="datatableCategorias" class="table" data-toggle="table">
        <thead class="thead-light">
            <tr>
                <th class="text-center">NO. RESERVA</th>
                <th class="text-center">IMPORTE</th>
                <th class="text-center">FECHA</th>
                <th class="text-center">NO. PROVEEDOR</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($pagos as $p): 
                $importe_limpio = limpiarImporte($p->importe);
            ?>
            <tr>
                <td class="text-center"><?= $p->no_reserva?></td>
                <td class="text-center">$<?= number_format($importe_limpio, 2)?></td>
                <td class="text-center"><?= date('d-m-Y', strtotime($p->fec_reg)); ?></td>
                <td class="text-center"><?= $p->no_proveedor?></td>
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
                    <!--end tab-content-->

                </div>
                <!--end col-->
            </div>
            <!--end row-->

        </div><!-- container -->



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
<script src="<?= base_url()?>assets/js/feather.min.js"></script>



<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
<script>

</script>