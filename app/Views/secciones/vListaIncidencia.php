

<?php  $session = \Config\Services::session(); ?>
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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">pag.</a></li>
                                <li class="breadcrumb-item active">Incidencia</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Incidencia</h4>
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
                                            <?php if(in_array($session->get('id_perfil'), [1,3] )): ?>
                                             <a href="<?php echo base_url().'index.php/Principal/reporteIncidencia'?>" target="_blank" class="btn btn-primary mb-3">
                                                Reporte General
                                            </a>
                                           
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="fecha_inicio">Periodo Inicio</label>
                                                        <select class="form-control" id="periodoInicio">
                                                            <?php foreach($periodo as $p): ?>
                                                                <option value="<?= $p->fecha_inicio ?>" data-id="<?= $p->id_periodo ?>">
                                                                    <?= 'PERIODO ' . $p->id_periodo . ' - ' . $p->dsc_periodo ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="fecha_inicio">Periodo Fin</label>
                                                        <select class="form-control" id="periodoFin">
                                                            <?php foreach($periodo as $p): ?>
                                                                <option value="<?= $p->fecha_fin ?>" data-id="<?= $p->id_periodo ?>">
                                                                    <?= 'PERIODO ' . $p->id_periodo . ' - ' . $p->dsc_periodo ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                       <label for="fecha_inicio">Usuario</label>
                                                        <select class="form-control select2" id="usuarioIncidencia" data-toggle="select2">
                                                            <?php foreach($usuario as $p): ?>
                                                            <option value="<?= $p->id_usuario ?>" > <?= $p->nombre_completo ?> </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                </div>
                                                  <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="fecha_fin">Enviar</label><br>
                                                        <button id="btnReporte" class="btn btn-info mb-3" onclick="ini.inicio.generarReporteIndividual();" >PDF</button>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                               
                                            <table id="datatableIncidencias" class="table" data-toggle="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">TIPO</th>
                                                        <th class="text-center">NOMBRE COMPLETO</th>
                                                        <th class="text-center">FECHA</th>
                                                        <th class="text-center">DETALLES</th>
                                                        <th class="text-center">OBSERVACIONES</th>
                                                        <th class="text-center">ESTATUS</th>
                                                        <th class="text-center">ACCIONES</th>
                                                    </tr>
                                                    <!--end tr-->
                                                </thead>

                                                <tbody>
                                                    <?php foreach($incidencia as $p): ?>
                                                    <tr>
                                                        <td class="text-center"><?= $p->dsc_incidencia?></td>
                                                        <td class="text-center"><?= $p->nombre_completo?></td>
                                                        <td class="text-center">
                                                            <?php if (isset($p->tipo) && !empty($p->tipo) && $p->tipo === 'semana'): ?>
                                                                <?= date('d-m-Y', strtotime($p->start)) ?> al <?= date('d-m-Y', strtotime('-1 day', strtotime($p->end))) ?>
                                                            <?php else: ?>
                                                                <?= date('d-m-Y', strtotime($p->fecha)) ?>
                                                            <?php endif; ?>
                                                        </td>
                                                         <td class="text-center"><?= $p->detalles?></td>
                                                         <td class="text-center"><?= $p->observaciones?></td>
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
                                                            default:
                                                                $color = 'badge-soft-secondary';
                                                                $texto = 'Desconocido';
                                                        }
                                                        ?>
                                                        <td class="text-center">
                                                            <span class="badge badge-md <?= $color ?>"><?= $texto ?></span>
                                                        </td>
                                                      
                                                      <td class="text-center">
                                                            <!-- Aprobar/aceptar -->
                                                            <a style="cursor:pointer;" onclick="saeg.principal.aceptarIncidencia(<?=$p->id_incidencia ?>, 3, <?=$p->id_usuario ?>);" class="mr-2" title="Aprobar">
                                                                <i class="fas fa-check-circle text-success font-16"></i>
                                                            </a>
                                                            
                                                            <!-- Revisar/editar -->
                                                            <a style="cursor:pointer;" onclick="saeg.principal.detalleIncidencia(<?=$p->id_incidencia ?>);" class="mr-2" title="Revisar">
                                                                <i class="fas fa-search text-info font-16"></i>
                                                            </a>
                                                            
                                                            <?php if($p->id_estatus !== 3): ?>
                                                            <a  style="cursor:pointer;" onclick="saeg.principal.aceptarIncidencia(<?=$p->id_incidencia ?>, 2,<?=$p->id_usuario ?>);" class="mr-2" title="Rechazar">
                                                                <i class="fas fa-times-circle text-warning font-16"></i>
                                                            </a>
                                                              <?php endif; ?>
                                                            <?php if(in_array($session->get('id_perfil'), [1,3]) ): ?>
                                                            <!-- Eliminar -->
                                                            <a style="cursor:pointer;" onclick="saeg.principal.eliminarIncidencia(<?=$p->id_incidencia ?>);"   class="mr-2" title="Eliminar">
                                                                <i class="fas fa-trash-alt text-danger font-16"></i>
                                                            </a>
                                                            <?php endif; ?>
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
                        <!--end general detail-->


                      

                        <!--end settings detail-->
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


<div class="modal fade" id="detalleIncidencia" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
                <main>
                   <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body step active">        
                                   <h2 class="mt-0 header-title">VISTA INCIDENCIA</h2>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">        
                                                        <h4 class="mt-0 header-title">Detalles</h4>
                                                         
                                                    
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="nombre">NOMBRE</label>
                                                                    <input type="text" class="form-control" id="nombre" name="nombre" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <span id="previews"></span>
                                                                    <label for="tipo_incidencia">TIPO INCIDENCIA</label>
                                                                    <input type="text" class="form-control" id="tipo_incidencia" name="tipo_incidencia" readonly>
                                                                </div>                                                                                      
                                                                <div class="form-group">
                                                                    <span id="previews"></span>
                                                                    <label for="detalles">DETALLES</label>
                                                                    <textarea type="text" class="form-control" id="detalles" name="detalles" readonly></textarea>
                                                                </div>                                                                                      
                                                            </div>
                                                            <div class="col-lg-6" >
                                                                <div class="form-group">
                                                                    <label for="hora_inicio">HORA INICIO</label>
                                                                    <input type="text" class="form-control" id="hora_inicio" name="hora_inicio" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="hora_fin">HORA FIN</label>
                                                                    <input type="text" class="form-control" id="hora_fin" name="hora_fin" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="comentario">COMETARIO</label>
                                                                    <textarea type="text" class="form-control" id="comentario" name="comentario" readonly></textarea>
                                                                </div>
                                                            </div>  
                                                        </div>  
                                                                                                               
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
                  

<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />

        <!-- App css -->
        <link href="<?= base_url()?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url()?>assets/css/jquery-ui.min.css" rel="stylesheet">
        <link href="<?= base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url()?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url()?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

               <!-- Plugins css -->
        <link href="<?= base_url()?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
        <link href="<?= base_url()?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url()?>plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url()?>plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />

               <script src="<?= base_url()?>plugins/apexcharts/apexcharts.min.js"></script> 

        <!-- jQuery  -->
        <script src="<?= base_url()?>assets/js/jquery.min.js"></script>
        <script src="<?= base_url()?>assets/js/jquery-ui.min.js"></script>
        <script src="<?= base_url()?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url()?>assets/js/metismenu.min.js"></script>
        <script src="<?= base_url()?>assets/js/waves.js"></script>
        <script src="<?= base_url()?>assets/js/feather.min.js"></script>
        <script src="<?= base_url()?>assets/js/jquery.slimscroll.min.js"></script>

        <!-- Required datatable js -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
   
        <!-- Plugins js -->
        <script src="<?= base_url()?>plugins/moment/moment.js"></script>
        <script src="<?= base_url()?>plugins/daterangepicker/daterangepicker.js"></script>
        <script src="<?= base_url()?>plugins/select2/select2.min.js"></script>
        <script src="<?= base_url()?>plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>

        <script src="<?= base_url()?>plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="<?= base_url()?>plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>

        <script src="<?= base_url()?>assets/pages/jquery.forms-advanced.js"></script>
<script>
$(document).ready(function() {
  $('#datatableIncidencias').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: true,
    });

  const inicio = document.getElementById('periodoInicio');
  const fin = document.getElementById('periodoFin');
    // Guardamos las opciones originales
    const todasLasOpciones = Array.from(fin.options);
    inicio.addEventListener('change', function() {
        // Leer el data-id del periodo seleccionado
        const idInicio = parseInt(inicio.selectedOptions[0].getAttribute('data-id'));
        // Limpiar opciones actuales
        fin.innerHTML = '';
        // Agregar solo las opciones con id >= idInicio
        todasLasOpciones.forEach(option => {
            const idOpcion = parseInt(option.getAttribute('data-id'));
            if (idOpcion >= idInicio+1) {
                fin.appendChild(option.cloneNode(true));
            }
        });
    });
});
</script>
