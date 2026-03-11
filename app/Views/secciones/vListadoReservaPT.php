

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
                                <li class="breadcrumb-item active">Listado PT</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Reserva PT</h4>
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
                                            <a href="<?php echo base_url().'index.php/Usuario/exportarExcel'?>" class="btn btn-primary mb-3">
                                                Descargar Excel
                                            </a>
                                            <br>
                                            <br>
                                             <?php endif; ?>
                                            <span>LISTA DE RESERVA</span>
    
                                            <table id="datatableReserva" class="table" data-toggle="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        
                                                        <th class="text-center">PROVEEDOR</th>
                                                        <th class="text-center">No. CONVENIO</th>
                                                        <?php if(isset($es_declinado) && $es_declinado): ?>
                                                            <th class="text-center">OBSERVACIONES</th>
                                                        <?php else: ?>
                                                            <th class="text-center">INSTRUMENTO</th>
                                                        <?php endif; ?>
                                                        <th class="text-center">REGISTRO</th>
                                                         <th class="text-center">ESTATUS</th>
                                                        <th class="text-center text-nowrap">ACCIONES</th>
                                                    </tr>
                                                    <!--end tr-->
                                                </thead>

                                                <tbody>
                                                    <?php foreach($reserva as $p): ?>
                                                       
                                                    <tr>
                                               
                                                        <td class="text-center"><?= $p->razon_social?></td>
                                                
                                                        <td class="text-center"><?= $p->no_convenio?></td>
                                                       
                                                        <?php if(isset($es_declinado) && $es_declinado): ?>
                                                            <td class="text-center"><?= $p->observaciones ?? '' ?></td>
                                                        <?php else: ?>
                                                            <td class="text-center">
                                                                <?php if (!empty($p->instrumento)) : ?>
                                                                    <a target="_blank" href="<?= base_url() . $p->instrumento ?>" class="btn btn-gradient-info px-4">
                                                                        <i class="dripicons-document-new font-21"></i>
                                                                    </a>
                                                                <?php endif; ?>
                                                            </td>
                                                        <?php endif; ?>

                                                          <td class="text-center"><?= (empty($p->nombre_completo))?'S/O':$p->nombre_completo?></td>
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
                                                           case 5:
                                                                $color = 'badge-soft-primary';
                                                                $texto = 'Revisión Interna';
                                                                break;
                                                            default:
                                                                $color = 'badge-soft-warning';
                                                                $texto = 'Desconocido';
                                                        }
                                                        ?>
                                                        <td class="text-center">
                                                            <?php if ($p->id_estatus == 2): ?>
                                                                <span style="cursor:pointer;" onclick="verObservacion('<?= htmlspecialchars($p->observaciones ?? '', ENT_QUOTES) ?>')" class="badge badge-md <?= $color ?> btn-ver-observacion"><?= $texto ?></span>
                                                            <?php else: ?>
                                                                <span class="badge badge-md <?= $color ?>"><?= $texto ?></span>
                                                            <?php endif; ?>
                                                        </td>
                                                        
                                                        
                                                        <td class="text-center text-nowrap">
                                                            <?php if($session->id_perfil != 2 && empty($p->id_registro_pt) ): ?>
                                                             <a style="color:white;" onclick="ini.inicio.editarReserva(<?=$p->id_reserva?>, 0);" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar"
                                                                class="btn btn-gradient-success px-4"><i
                                                                    class="mdi mdi-border-color font-21"></i>
                                                            </a>
                                                             <?php endif; ?>
                                                             <?php if(!in_array($p->id_estatus,[3,4]) && $session->id_perfil != 2): ?>
                                                            <a style="color:white;" onclick="ini.inicio.eliminarReserva(<?=$p->id_reserva?>);" data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar"
                                                                class="btn btn-gradient-danger px-4"><i
                                                                    class="mdi mdi-trash-can-outline font-21"></i>
                                                            </a>
                                        
                                                            <?php endif; ?>
                                                            <?php if(in_array($session->get('id_perfil'), [1,2])): ?>
                                                            <a style="color:white;"  onclick="ini.inicio.estatusReserva(<?=$p->id_reserva?>);" data-toggle="tooltip" data-placement="top" title="" data-original-title="Revisar reserva"
                                                                class="btn <?= (in_array($p->id_estatus,[3,4]))?'btn-gradient-info':'btn-gradient-warning'?> px-4"><i
                                                                    class="mdi mdi-lock-open font-21"></i>
                                                            </a>

                                                            <?php endif; ?>
                                                             <?php if(in_array($p->id_estatus, [3,4]) && $session->get('id_perfil') !=2): ?>
                                                             
                                                            <a href="<?= base_url().'index.php/Principal/generarFormatoPT/'.$p->id_reserva ?>" style="color:white;"  data-toggle="tooltip" data-placement="left" data-original-title="<?= (!empty($p->id_registro_pt) )?'Ver Tramite de Pago':'Generar Tramite de Pago' ?>"
                                                                class="btn <?= (!empty($p->id_registro_pt) )?'btn-gradient-info':'btn-gradient-primary' ?> px-4 uitooltip"><i
                                                                    class="mdi mdi-arrow-right-bold font-21"></i>
                                                            </a>
                                                           <!--  <a href="<?= base_url().'index.php/Principal/generarTramitePago/'.$p->id_reserva ?>" style="color:white;"  data-toggle="tooltip" data-placement="left" data-original-title="<?= (!empty($p->id_registro_pt) )?'Ver Tramite de Pago':'Generar Tramite de Pago' ?>"
                                                                class="btn <?= (!empty($p->id_registro_pt) )?'btn-gradient-info':'btn-gradient-primary' ?> px-4 uitooltip"><i
                                                                    class="mdi mdi-arrow-right-bold font-21"></i>
                                                            </a> -->
                                                            <?php endif; ?>
                                                        <!-- Validacion Interna -->
                                                             <?php if($session->get('id_usuario') == 80): ?>
                                                            <a style="color:white;"  onclick="ini.inicio.estatusReservaInterna(<?=$p->id_reserva?>);" data-toggle="tooltip" data-placement="top" title="" data-original-title="Revisar reserva"
                                                                class="btn <?= (in_array($p->id_estatus,[3,4]))?'btn-gradient-info':'btn-gradient-warning'?> px-4"><i
                                                                    class="mdi mdi-lock-open font-21"></i>
                                                            </a>

                                                             <?php endif;  ?>
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

<div class="modal fade" id="modalEditarReserva2" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel" aria-hidden="true">
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
                                                         
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" onclick="ini.inicio.ocultarInstrumento(this);" class="custom-control-input" id="customSwitch1">
                                                            <label class="custom-control-label" for="customSwitch1">Sin Instrumento Jurídico</label>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="nombre_proveedor_editar">Nombre Proveedor</label>
                                                                    <input type="text" class="form-control" id="nombre_proveedor_editar" name="nombre_proveedor_editar" readonly>
                                                                    <input type="hidden" id="id_reserva" >
                                                                </div>
                                                                <div class="form-group" id="id_instrumento">
                                                                    <span id="previews2"></span>
                                                                    <label for="instrumento_editar">Instrumento Juridico</label>
                                                                    <input type="file" class="form-control" id="instrumento_editar" name="instrumento_editar" accept=".pdf">
                                                                </div>                                                                                      
                                                            </div>
                                                            <div class="col-lg-6" >
                                                                <div class="form-group">
                                                                    <label for="no_proveedor_editar">No. Proveedor</label>
                                                                    <input type="text" class="form-control" id="no_proveedor_editar" name="no_proveedor_editar" readonly>
                                                                </div>
                                                                <div class="form-group" id="id_convenio">
                                                                    <label for="no_convenio_editar">No. Convenio/Contrato</label>
                                                                    <div class="input-group">
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                V/T <i class="mdi mdi-chevron-down"></i>
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item" href="#" onclick="setConvenio('SECTURI/CONV/')">SECTURI/CONV/</a>
                                                                                <a class="dropdown-item" href="#" onclick="setConvenio('SECTURI/CTO/')">SECTURI/CTO/</a>
                                                                            </div>
                                                                        </div>
                                                                        <input type="text" id="no_convenio_editar" name="no_convenio_editar" class="form-control" placeholder="025" autocomplete="off">
                                                                        <div class="input-group-append">
                                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                 AÑO <i class="mdi mdi-chevron-down"></i>
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item" href="#" onclick="setAnio('/2025')">2025</a>
                                                                                <a class="dropdown-item" href="#" onclick="setAnio('/2024')">2024</a>
                                                                                <a class="dropdown-item" href="#" onclick="setAnio('/2023')">2023</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group" id="id_comentarios_instrumento_editar">
                                                                    <label for="comentarios_instrumento_editar">Comentarios</label>
                                                                    <textarea class="form-control" id="comentarios_instrumento_editar" name="comentarios_instrumento_editar" rows="3"></textarea>
                                                                </div>
                                                            </div>  
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-body">
                                        
                                                                        <h4 class="mt-0 header-title">PRESUPUESTO</h4>
                                                                        <div class="table-responsive">
                                                                         <table class="table table-bordered" id="makeEditableEditar">
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
                                                                  <button id="btn_guardar_edicion" class="btn btn-success">
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
<div class="modal fade" id="modalEstatusReserva" tabindex="-1" aria-labelledby="modalEliminarReservaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalEliminarReservaLabel">Agregar Estatus Reserva</h5>
        <button type="button" onclick="ini.inicio.cerrarModalAdmin()" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
     
      <div class="modal-body">
        <form id="formEliminarReserva">
          <input type="hidden" id="id_reserva_estatus" name="id_reserva_estatus">
           <div class="row">
              <div class="col-lg-12">
                  <div class="card">
                      <div class="card-body">        
                          <div class="row">
                              <div class="col-lg-6">
                                   <div class="form-group" >
                                        <label for="varlidar_nombre_proveedor" class="form-label">PROVEEDOR</label>
                                        <input class="form-control" id="varlidar_nombre_proveedor" autocomplete="off" name="varlidar_nombre_proveedor" readonly>
                                    </div> 
                                    <div class="form-group" >
                                        <label for="validar_no_proveedor" class="form-label">No. Proveedor</label>
                                        <input class="form-control" id="validar_no_proveedor" autocomplete="off" name="validar_no_proveedor" readonly>
                                    </div>                                                                                    
                              </div>                                                             
                              <div class="col-lg-6">
                                   <div class="form-group" >
                                        <label for="validar_total_importe" class="form-label">Total Importe</label>
                                        <input class="form-control" id="validar_total_importe" autocomplete="off" name="validar_total_importe" readonly>
                                  </div>                                                                        
                                  <div class="form-group">   
                                        <label for="validar_no_convenio" class="form-label"> <div id="previews"></div></label>
                                        <input class="form-control" id="validar_no_convenio" autocomplete="off" name="validar_no_convenio" readonly>
                                  </div>                                                                                    
                              </div>                                                             
                         </div>
                          <div class="row">
                              <div class="col-lg-12">
                                   <div class="form-group" >
                                        <label for="comentarios_instrumento_estatus" class="form-label">Comentarios</label>
                                        <textarea class="form-control" id="comentarios_instrumento_estatus" name="comentarios_instrumento_estatus" rows="3"></textarea>
                                    </div>                                                                                    
                              </div>                                                             
                                                                                        
                         </div>
                          <div class="row">
                              <div class="col-lg-12">
                                <table class="table" id="ValidarMakeEditableEditar">
                                    <thead>
                                        <tr>
                                            <th>PROYECTO-META</th>
                                            <th>PARTIDA</th>
                                            <th>IMPORTE</th>
                                            <th>FONDO</th>
                
                                        </tr>
                                    </thead>
                                    <tbody> 
                                   </tbody>
                                </table>                                                                      
                              </div>                                                                                                                       
                         </div>
                         <?php if($session->get('id_usuario') != 80): ?>
                            <div class="row">
                              <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="motivo" class="form-label">Estatus</label>
                                        <select class="form-control" id="motivo" name="motivo" onchange="ini.inicio.selectMotivo();"  >
                                            <option value="1">EN PROCESO</option>
                                            <option value="2">DECLINADO</option>
                                            <option value="3">ACEPTADO</option>
                                        </select>
                                    </div>                                                                                     
                              </div>                                                             
                              <div class="col-lg-6">                                                                   
                                  <div class="form-group" id="numero" style="display:none;" >   
                                        <label for="validar_no_reserva" class="form-label">No. Reserva</label>
                                        <input type="text" class="form-control" id="validar_no_reserva" autocomplete="off" name="validar_no_reserva" >
                                  </div>                                                                                    
                              </div>                                                             
                         </div>
                            <div class="row">
                              <div class="col-lg-12">
                                    <div class="form-group" id="observacion" style="display:none;">
                                       <label for="validar_observaciones" class="form-label">Observaciones</label>
                                        <textarea class="form-control" id="validar_observaciones" ></textarea>
                                    </div>                                                                                     
                              </div>                                                             
                                                                                           
                         </div>
                         <?php endif; ?>
                  </div><!--end card-->
              </div><!--end col-->
          </div><!--end col-->    
        
         
        </form>
      </div>
      <div class="modal-footer">
          <?php if($session->get('id_usuario') != 80): ?>
          <button type="button" class="btn btn-primary" id="btnConfirmarReserva">Guardar</button>
          <?php endif; ?>
          <?php if($session->get('id_usuario') == 80): ?>
          <button type="button" class="btn btn-primary" id="btnValidarReserva">Validar Reserva</button>
          <?php endif; ?>
        <button type="button" onclick="ini.inicio.cerrarModalAdmin()" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
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
    ini.inicio.guardarReservaEdicion();
    ini.inicio.formEliminarReserva();
    $('#datatableReserva').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: true,
    });

      $('#btnValidarReserva').on('click', function () {
                const id = $('#id_reserva_estatus').val();
                $.ajax({
                    url: base_url + "index.php/Usuario/validarReserva",
                    type: "POST",
                    dataType: "json",
                    data: {
                        id_reserva: id,
                    },
                    beforeSend: function () {
                        $('#btnValidarReserva').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.error) {
                            Swal.fire("Atención", response.respuesta, "warning");

                        } else {
                            Swal.fire("Correcto", response.respuesta, "success");
                            setTimeout(() => window.location.reload(), 1500);
                        }
                    },
                    complete: function () {
                        $('#modalEstatusReserva').modal('hide');
                        $('#btnValidarReserva').prop('disabled', false).html('Guardar');

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // alert("Error al eliminar");
                        console.log("Error(s):", textStatus, errorThrown);
                    }
                });
            });
        // Función debounce para retrasar la ejecución
    });

    // 1. Escuchar cambios en inputs de importe
    $(document).on('input', 'input[name="importe[]"]', function() {
        calcularTotal();
    });

    // 2. Función para calcular el total
    function calcularTotal() {
        let total = 0;
        $('input[name="importe[]"]').each(function() {
            const valor = parseFloat($(this).val().replace(/,/g, '')) || 0;
            total += valor;
        });
        $('#total_importe_editar').val(formatNumber(total.toFixed(2)));
    }

    // 3. Función para formatear números
    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }
  function setConvenio(valor) {
        document.getElementById('no_convenio_editar').value = valor;
    }

    function setAnio(anio) {
        let input = document.getElementById('no_convenio_editar');
        input.value = input.value + anio;
    }


    function verObservacion(observacion) {
        Swal.fire({
            title: 'Motivo de Declinación',
            text: observacion,
            icon: 'info',
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#5b73e8'
        });
    }


</script>