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
               <li class="breadcrumb-item"><a href="javascript:void(0);">Metrica</a></li>
               <li class="breadcrumb-item"><a href="javascript:void(0);">Helpdesk</a></li>
               <li class="breadcrumb-item active">Personal a cargo</li>
            </ol>
         </div>
         <h4 class="page-title">Personal a cargo</h4>
      </div>
      <!--end page-title-box-->
   </div>
   <!--end col-->
</div>
<!-- end page title end breadcrumb -->
<div class="row">
   <?php foreach ($personal as $p): ?>
   <div class="col-lg-4">
      <div class="card">
         <div class="card-body">
            <div class="media mb-3">
               <img src="<?= base_url(); ?>assets/images/users/user-1.jpg" class="mr-3 thumb-xl align-self-center rounded-circle" alt="...">
               <div class="media-body align-self-center">
                  <h4 class="mt-0 mb-1"><?= $p->nombre_completo; ?></h4>
                  <p class="text-muted mb-0 font-12"><?= $p->dsc_puesto; ?> </p>
                  <ul class="list-inline mb-0 align-self-center">
                     <li class="list-inline-item m-0"><i class="mdi mdi-star text-warning font-18"></i></li>
                     <li class="list-inline-item m-0"><i class="mdi mdi-star text-warning font-18"></i></li>
                     <li class="list-inline-item m-0"><i class="mdi mdi-star text-warning font-18"></i></li>
                     <li class="list-inline-item m-0"><i class="mdi mdi-star text-warning font-18"></i></li>
                     <li class="list-inline-item m-0"><i class="mdi mdi-star-half text-warning font-18"></i></li>
                  </ul>
               </div>
               <!--end media body-->                                            
            </div>
            <!--end media-->           
            <h4 class="header-title"><?= $p->correo; ?></h4>
            <div class="mb-3">
               <div id="Agent_3" class="apex-charts"></div>
            </div>
            <ul class="list-inline d-flex justify-content-between mb-4">
               <li class="list-inline-item">
                  <div class="media">
                     <i data-feather="thumbs-up" class="align-self-center icon-lg icon-dual-success"></i> 
                     <div class="media-body align-self-center ml-2">
                        <p class="mb-0"> <span class="font-24 font-weight-semibold">91%</span> Avances</p>
                     </div>
                     <!--end media body-->                                            
                  </div>
                  <!--end media--> 
               </li>
               <li class="list-inline-item">
                  <div class="media">
                     <i data-feather="thumbs-down" class="align-self-center icon-lg icon-dual-danger"></i> 
                     <div class="media-body align-self-center ml-2">
                        <p class="mb-0"> <span class="font-24 font-weight-semibold">09%</span> Pendientes</p>
                     </div>
                     <!--end media body-->                                            
                  </div>
                  <!--end media--> 
               </li>
            </ul>
            <p class="text-muted  mb-4"><span class="text-dark font-weight-semibold">Entrega de activida hoy :</span> 
              
            </p>
            <div>
               <button type="button" class="btn btn-sm btn-soft-secondary">Ver actividades</button>
               <button type="button"
                  class="btn btn-sm btn-soft-primary"
                  onclick="ini.inicio.modalActividad(true, <?= $p->id_usuario ?>);">
               Agregar Actividad
               </button>
            </div>
         </div>
         <!--end card-body-->                                                                     
      </div>
      <!--end card-->                          
   </div>
   <!--end col-->
   <?php endforeach; ?>
</div>
<!--end row-->
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <p class="badge badge-soft-pink font-11 p-1 mb-1 float-lg-right float-md-none">Actividades Asignadas hace 15 min.</p>
            <h4 class="header-title mt-0 mb-3">Actividades</h4>
            <div class="table-responsive browser_users">
               <table class="table mb-0">
                  <thead class="thead-light">
                     <tr>
                        <th class="border-top-0">Personal</th>
                        <th class="border-top-0">Actividad</th>
                        <th class="border-top-0">Inicio</th>
                        <th class="border-top-0">Termino</th>
                        <th class="border-top-0">Avance en %</th>
                        <th class="border-top-0">Acciones</th>
                     </tr>
                     <!--end tr-->
                  </thead>
                  <tbody>
                    <?php foreach($actividad as $a): ?>
                     <tr>
                        <td>
                           <div class="media">
                              <img src="<?= base_url(); ?>assets/images/users/user-1.png" alt="" class="thumb-sm rounded-circle mr-2">                                       
                              <div class="media-body align-self-center text-truncate">
                                 <h6 class="mt-0 mb-1 text-dark"><?= $a->nombre_completo ?></h6>
                                 <p class="text-muted mb-0">Creado: <?= date('d/m/Y', strtotime($a->fec_reg)) ?></p>
                              </div>
                              <!--end media-body-->
                           </div>
                        </td>
                        <td><?= $a->actividad ?></td>
                        <td><?= date('d/m/Y', strtotime($a->fec_inicio)) ?></td>
                        <td><?= date('d/m/Y', strtotime($a->fec_fin)) ?></td>
                        <td><?= $a->avance ?>%</td>
                        <td>                                                                                                       
                           <a href="javascript:void(0)" onclick="ini.inicio.modalActividadEditar(<?= $a->id_actividad ?>)" class="mr-2"><i class="fas fa-edit text-info font-16"></i></a>
                           <a href="javascript:void(0)" onclick="ini.inicio.deleteActividad(<?= $a->id_actividad ?>);"><i class="fas fa-trash-alt text-danger font-16"></i></a>
                        </td>
                     </tr>
                      <?php endforeach; ?>
                  </tbody>
               </table>
               <!--end table-->                                               
            </div>
            <!--end /div-->
         </div>
         <!--end card-body-->
      </div>
      <!--end card-->
   </div>
   <!--end col-->
</div>
<!--end row-->
</div><!-- container -->
<div id="modalActividad" class="modal fade bs-example" tabindex="-1" aria-labelledby="modalActividadLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header">
            <h5 id="modalActividadLabel" class="modal-title">Nueva actividad</h5>
            <button type="button" class="btn-close" onclick="ini.inicio.modalActividad(false, 0)" aria-label="Cerrar"></button>
         </div>
         <div class="modal-body">
            <form id="form_actividad" >
            <input type="hidden" id="id_usuario" name="id_usuario">
            <input type="hidden" id="id_actividad" name="id_actividad" value="0">
            <div class="row">
               <div class="col-md-2">
                  <div class="mb-3 position-relative" id="">
                     <label for="fec_inicio"
                        class="form-label">FEC. INICIO</label>
                     <input type="date" autocomplete="off" class="form-control"
                        id="fec_inicio" name="fec_inicio">
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="mb-3 position-relative" id="">
                     <label for="fec_fin"
                        class="form-label ">FEC. FIN</label>
                     <input type="date" autocomplete="off" class="form-control"
                        id="fec_fin" name="fec_fin"
                        >
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3 position-relative" id="">
                     <label for="actividad"
                        class="form-label ">ACTIVIDAD</label>
                     <input type="text" autocomplete="off" class="form-control"
                        id="actividad" name="actividad"
                        placeholder="Agregar Actividad..">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3 position-relative" id="">
                     <label for="estatus"
                        class="form-label ">ESTATUS</label>
                     <select autocomplete="off" class="form-control"
                        id="estatus" name="estatus">
                        <option value="1">ALTA</option>
                        <option value="2">MEDIA</option>
                        <option value="3">BAJA</option>
                     </select>
                  </div>
               </div>
            </div>
            <div class="col-md-12">
               <div class="mb-3 position-relative" >
                  <label for="des_actividad"
                     class="form-label">DESCRIPCION</label>
                  <textarea autocomplete="off" class="form-control"
                     id="des_actividad" name="des_actividad"></textarea>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <a class="btn btn-danger text-white" onclick="ini.inicio.modalActividad(false, 0)">Cerrar</a>
            <button id="btnActividad" class="btn btn-primary">Guardar</button>
         </div>
    </form>
      </div>
   </div>
</div>


 <!-- App css -->
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />


                        <!-- jQuery  -->
        <script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/jquery-ui.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/metismenu.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/waves.js"></script>
        <script src="<?= base_url(); ?>assets/js/feather.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>
      
        <script src="<?= base_url(); ?>assets/pages/jquery.agents.init.js"></script> 
<script>
    ini.inicio.formActividad();
</script>
        