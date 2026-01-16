

<?php $session = \Config\Services::session(); ?>
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
                                <li class="breadcrumb-item active">Listado GO</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Reserva GO</h4>
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
                                            <?php if(in_array($session->get('id_perfil'),[1,2])): ?>
                                            <a href="<?php echo base_url().'index.php/Usuario/exportarExcelGo'?>" class="btn btn-primary mb-3">
                                                Descargar Excel
                                            </a>
                                            <br>
                                            <br>
                                             <?php endif; ?>
                                            <span>LISTA DE RESERVA GO</span>
    
                                            <table id="datatableReserva" class="table" data-toggle="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        
                                                        <th class="text-center">FOLIO</th>
                                                        <th class="text-center">TOTAL IMPORTE</th>
                                                        <th class="text-center">USUARIO</th>
                                                         <th class="text-center">ESTATUS</th>
                                                        <th class="text-center">ACCIONES</th>
                                                    </tr>
                                                    <!--end tr-->
                                                </thead>

                                                <tbody>
                                                    <?php foreach($reserva as $p): ?>
                                                        <?php if($p->id_estatus !=4 ): ?>
                                                    <tr>
                                               
                                                        <td class="text-center"><?= $p->folio?></td>
                                                        <td class="text-center"><?= $p->total_importe?></td>
                                                        <td class="text-center"><?= $p->nombre_completo?></td>
                                                        <?php
                                                        switch ($p->id_estatus) {
                                                            case 1:
                                                                $color = 'badge-soft-primary';
                                                                $texto = 'En proceso';
                                                                break;
                                                            case 2:
                                                                $color = 'badge-soft-danger';
                                                                $texto = 'Declinado';
                                                                break;
                                                            case 3:
                                                                $color = 'badge-soft-success';
                                                                $texto = 'Aceptado';
                                                                break;
                                                            case 4:
                                                                $color = 'badge-soft-warning';
                                                                $texto = 'Enviado';
                                                                break;
                                                            default:
                                                                $color = 'badge-soft-warning';
                                                                $texto = 'Desconocido';
                                                        }
                                                        ?>
                                                        <td class="text-center">
                                                            <span class="badge badge-md <?= $color ?>"><?= $texto ?></span>
                                                        </td>
                                                        
                                                        <td class="text-center">
                                                            <?php if($session->id_perfil != 2 && empty($p->id_registro_go)): ?>
                                                             <a style="color:white;" onclick="ini.inicio.editarReservaGo(<?=$p->id_reserva_go?>, 0);" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar"
                                                                class="btn btn-gradient-success px-4"><i
                                                                    class="mdi mdi-border-color font-21"></i>
                                                            </a>
                                                             <?php endif; ?>
                                                             <?php if($p->id_estatus != 3 && $session->id_perfil != 2 && $p->id_estatus != 4): ?>
                                                            <a style="color:white;"  onclick="ini.inicio.eliminarReservaGo(<?=$p->id_reserva_go?>);" data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar"
                                                                class="btn btn-gradient-danger px-4"><i
                                                                    class="mdi mdi-trash-can-outline font-21"></i>
                                                            </a>
                                                           
                                                              <?php endif; ?>
                                                            <?php if(in_array($session->get('id_perfil'),[1,2])): ?>
                                                            <a style="color:white;"  onclick="ini.inicio.estatusReservaGo(<?=$p->id_reserva_go?>, <?=$p->id_estatus?> );" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= (in_array($p->id_estatus, [3,4]))?'Ver Reserva':'Validar Reserva'?>"
                                                                class="btn <?= (in_array($p->id_estatus, [3,4]))?'btn-gradient-info':'btn-gradient-warning'?> px-4"><i
                                                                    class="mdi mdi-lock-open font-21"></i>
                                                            </a>

                                                            <?php endif; ?>
                                                             <?php if(in_array($p->id_estatus, [3,4]) && $session->get('id_perfil')!=2  ): ?>
                                                            <a href="<?= base_url().'index.php/Principal/generarTramitePagoGo/'.$p->id_reserva_go ?>" style="color:white;"  data-toggle="tooltip" data-placement="left" data-original-title="<?=($p->id_estatus == 4)?'Ver Pago':'Generar Tramite de Pago'?>"
                                                                class="btn <?= (!empty($p->id_registro_go) )?'btn-gradient-info':'btn-gradient-primary' ?> px-4 uitooltip"><i
                                                                    class="mdi mdi-arrow-right-bold font-21"></i>
                                                            </a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    <?php endif; ?>
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

<div class="modal fade" id="reservaGo" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">EDITAR RESERVA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <main>
                   <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body step active">        
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">        
                                                        <h4 class="mt-0 header-title">Edición Datos</h4>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="form-group">
                                                                    <label for="titular">Titular</label>
                                                                    <input type="text" class="form-control" id="titular" value="GOBIERNO DEL ESTADO DE GUANAJUATO SFIYA SECRETARIA DE TURISMO" name="titular" readonly>
                                                                    <input type="hidden" id="id_reserva_go" >
                                                                </div>                                                                                 
                                                            </div> 
                                                        </div> 
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="tipo_titularidad">Tipo de titularidad</label>
                                                                    <input type="text" class="form-control" id="tipo_titularidad" value="INDIVIDUAL" name="tipo_titularidad" readonly>
                                                                    <input type="hidden" id="id_reserva_go" >
                                                                </div>                                                                                 
                                                            </div>
                                                            <div class="col-lg-4" >
                                                                <div class="form-group">
                                                                    <label for="no_cuenta">No. Cuenta</label>
                                                                    <input type="text" class="form-control" id="no_cuenta" value="51859130201" name="no_cuenta" readonly>
                                                                </div>
                                                            </div>  
                                                             <div class="col-lg-4" >
                                                                <div class="form-group">
                                                                    <label for="banco">Banco</label>
                                                                    <input type="text" class="form-control" id="banco" value="BAN BAJIO - 030210518591302019" name="banco" readonly>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-body">
                                        
                                                                        <h4 class="mt-0 header-title">PRESUPUESTO</h4>
                                                                        <div class="table-responsive">
                                                                         <table class="table table-bordered" id="editarReservaGo">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>PROYECTO-META</th>
                                                                                        <th>PARTIDA</th>
                                                                                        <th>IMPORTE</th>
                                                                                  
                                                                                        <th>ACCIONES</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                  
                                                                                </tbody>
                                                                            </table>
                                                                            
                                                                            <div class="row mt-3">
                                                                                <div class="col-md-8"></div>
                                                                                <div class="col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label>TOTAL:</label>
                                                                                        <input type="text" class="form-control font-weight-bold text-right" id="total_importe_editar" value="0.00" readonly>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>   
                                                                    </div><!--end card-body-->   
                                                                </div><!--end card-->
                                                                  <button id="btnReservaGo" class="btn btn-success">
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

<!-- Modal -->
<div class="modal fade" id="modalReservaGo" tabindex="-1" aria-labelledby="modalEliminarReservaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalEliminarReservaLabel">Agregar Estatus Reserva</h5>
        <button type="button" onclick="ini.inicio.cerrarModalGo()" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
     
      <div class="modal-body">
        <form id="formEliminarReserva">
          <input type="hidden" id="id_reserva_estatus_go" name="id_reserva_estatus_go">
           <div class="row">
              <div class="col-lg-12">
                  <div class="card">
                      <div class="card-body">        
                 
                          <div class="row">
                              <div class="col-lg-12">
                                <table class="table" id="tablaReservaGo">
                                    <thead>
                                        <tr>
                                            <th>PROYECTO-META</th>
                                            <th>PARTIDA</th>
                                            <th>IMPORTE</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                   </tbody>
                                </table>                                                                      
                              </div>                                                                                                                       
                         </div>
                            <div class="row">
                              <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="motivo_go" class="form-label">Estatus</label>
                                        <select class="form-control" id="motivo_go" name="motivo_go" onchange="ini.inicio.selectMotivoGo();" required>
                                            <option value="1">EN PROCESO</option>
                                            <option value="2">DECLINADO</option>
                                            <option value="3">ACEPTADO</option>
                                        </select>
                                    </div>                                                                                     
                              </div>                                                             
                              <div class="col-lg-6">                                                                   
                                  <div class="form-group" id="numero_go" style="display:none;" >   
                                        <label for="validar_no_reserva_go" class="form-label">No. Reserva</label>
                                        <input type="text" class="form-control" id="validar_no_reserva_go" autocomplete="off" name="validar_no_reserva_go" >
                                  </div>                                                                                    
                              </div>                                                             
                         </div>
                            <div class="row">
                              <div class="col-lg-12">
                                    <div class="form-group" id="observacion_go" style="display:none;">
                                       <label for="observaciones_go" class="form-label">Observaciones</label>
                                        <textarea class="form-control" id="observaciones_go" name="observaciones_go"></textarea>
                                    </div>                                                                                     
                              </div>                                                             
                                                                                           
                         </div>
                  </div><!--end card-->
              </div><!--end col-->
          </div><!--end col-->    
        
         
        </form>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnConfirmarReservaGo">Guardar</button>
        <button type="button" onclick="ini.inicio.cerrarModalGo()" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
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
<script>
$(document).ready(function() {
    ini.inicio.guardarReservaEdicionGo();
    ini.inicio.formEliminarReservaGo();
    $('#datatableReserva').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: true,
    });
        // Función debounce para retrasar la ejecución
    });

    // 1. Escuchar cambios en inputs de importe
    $(document).on('input', 'input[name="importe_go[]"]', function() {
        calcularTotal();
    });
     $(document).on('input', 'input[name="propina_go[]"]', function() {
        calcularTotal();
    });

    // 2. Función para calcular el total
    function calcularTotal() {
        let total = 0;
        $('input[name="importe_go[]"]').each(function() {
            const valor = parseFloat($(this).val().replace(/,/g, '')) || 0;
            total += valor;
        });
        $('input[name="propina_go[]"]').each(function() {
            const valor = parseFloat($(this).val().replace(/,/g, '')) || 0;
            total += valor;
        });
        $('#total_importe_editar').val(formatNumber(total.toFixed(2)));
    }

    // 3. Función para formatear números
    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }
</script>