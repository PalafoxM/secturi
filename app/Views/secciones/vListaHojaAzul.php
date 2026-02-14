<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Listas</a></li>
                                <li class="breadcrumb-item active">Lista Hoja Azul</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Lista Hoja Azul</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="toolbar">
                                     <a href="<?= base_url() ?>index.php/Inicio/generarFormatoPT" class="btn btn-primary"><i class="mdi mdi-plus"></i> Nuevo</a>
                                </div>
                                <table id="datatableUsuario" class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">FEC. TRAMITE</th>
                                            <th class="text-center">FOLIO</th>
                                            <th class="text-center">NO. RESERVA</th>
                                            <th class="text-center">PROVEEDOR</th>
                                            <th class="text-center">IMPORTE</th>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                        <!--end tr-->
                                    </thead>

                                    <tbody>
                                        <?php foreach($dataHojaAzul as $e): ?>
                                        <tr>
                                            <td  class="text-center"><?= date('d/m/Y', strtotime($e->fecha_tramite)) ?></td>
                                            <td  class="text-center"><?= $e->no_consecutivo?></td>
                                            <td  class="text-center"><?= $e->no_reserva?></td>
                                            <td  class="text-center"><?= $e->nombre_proveedor_1?></td>
                                            <td  class="text-center"><?= $e->importe_total_num?></td>
                                           
                   
                                            <td  class="text-center" class="text-center">
                                          
                                              
                                                <a class="btn btn-outline-info btn-round" href="<?php echo base_url().'index.php/Inicio/Viaticos/'.$e->id_formulario_pt ?>"  ><i
                                                        class="mdi mdi-check text-success font-18"></i></a> 
                                               
                                                <a class="btn btn-outline-info btn-round" href="<?php echo base_url().'index.php/Inicio/EditarFIC/'.$e->id_formulario_pt?>"  ><i
                                                        class="mdi dripicons-pencil text-warning font-18"></i></a> 
                                             
                                                <a title="Continuar Pago" class="btn btn-outline-info btn-round" href="<?php echo base_url().'index.php/Principal/continuarPago/'.$e->id_formulario_pt ?>"  ><i
                                                        class="mdi dripicons-media-next text-danger font-18"></i></a> 
                                               
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

        </div><!-- container -->
    </div>
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

<script src="<?= base_url()?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url()?>assets/js/waves.js"></script>
<script src="<?= base_url()?>assets/js/feather.min.js"></script>
