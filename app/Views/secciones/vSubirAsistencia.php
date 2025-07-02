


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
                                <li class="breadcrumb-item active">Asistencia</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Asistencias</h4>
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
                                            <span>PERFILES</span>
                                            <button onclick="ini.inicio.cargaCsv()"
                                                class="btn btn-gradient-primary px-4 float-right mt-0 mb-3"><i
                                                    class="mdi mdi-plus-circle-outline mr-2"></i>Subir Archivo</button>
                                                 
                                            <table id="datatableCategorias" class="table" data-toggle="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center">PERSONAL</th>
                                                        <th class="text-center">ENE</th>
                                                        <th class="text-center">FEB</th>
                                                        <th class="text-center">MAR</th>
                                                        <th class="text-center">ABR</th>
                                                        <th class="text-center">MAY</th>
                                                        <th class="text-center">JUN</th>
                                                        <th class="text-center">JUL</th>
                                                        <th class="text-center">AGO</th>
                                                        <th class="text-center">SEP</th>
                                                        <th class="text-center">OCT</th>
                                                        <th class="text-center">NOV</th>
                                                        <th class="text-center">DIC</th>
                                                        <th class="text-center">ACCIONES</th>
                                                    </tr>
                                                    <!--end tr-->
                                                </thead>

                                                <tbody>
                                                    <?php foreach($cat_usuario as $p): ?>
                                                        <tr>
                                                            <td class="text-center"><?= $p->nombre_completo ?></td>
                                                            <td class="text-center" style="cursor:pointer;" title="número de asistencias <?= $p->ene ?>" >
                                                                <a href="<?= base_url().'index.php/Agregar/Asistencia/1/'.$p->id_usuario?>" ><i class="mdi mdi-calendar <?= ($p->ene <= 5)?'text-danger':'text-success' ?> font-18" ></i>
                                                             </td>
                                                            <td class="text-center" style="cursor:pointer;" title="número de asistencias <?= $p->feb ?>" >
                                                                <a href="<?= base_url().'index.php/Agregar/Asistencia/2/'.$p->id_usuario?>" ><i class="mdi mdi-calendar  <?= ($p->feb <= 5)?'text-danger':'text-success' ?> font-18" ></i>
                                                             </td>
                                                            <td class="text-center" style="cursor:pointer;" title="número de asistencias <?= $p->mar ?>" >
                                                                <a href="<?= base_url().'index.php/Agregar/Asistencia/3/'.$p->id_usuario?>" ><i class="mdi mdi-calendar <?= ($p->mar <= 5)?'text-danger':'text-success' ?> font-18" ></i>
                                                             </td>
                                                            <td class="text-center" style="cursor:pointer;" title="número de asistencias <?= $p->abr ?>" >
                                                                <a href="<?= base_url().'index.php/Agregar/Asistencia/4/'.$p->id_usuario?>" ><i class="mdi mdi-calendar <?= ($p->abr <= 5)?'text-danger':'text-success' ?> font-18" ></i>
                                                             </td>
                                                            <td class="text-center" style="cursor:pointer;" title="número de asistencias <?= $p->may ?>">
                                                                <a href="<?= base_url().'index.php/Agregar/Asistencia/5/'.$p->id_usuario?>" ><i class="mdi mdi-calendar  <?= ($p->may <= 5)?'text-danger':'text-success' ?> font-18" ></i>
                                                             </td>
                                                            <td class="text-center" style="cursor:pointer;" title="número de asistencias <?= $p->jun ?>" >
                                                                <a href="<?= base_url().'index.php/Agregar/Asistencia/6/'.$p->id_usuario?>" ><i class="mdi mdi-calendar  <?= ($p->jun <= 5)?'text-danger':'text-success' ?> font-18" ></i>
                                                             </td>
                                                            <td class="text-center" style="cursor:pointer;" title="número de asistencias <?= $p->jul ?>">
                                                                <a href="<?= base_url().'index.php/Agregar/Asistencia/7/'.$p->id_usuario?>" ><i class="mdi mdi-calendar  <?= ($p->jul <= 5)?'text-danger':'text-success' ?> font-18" ></i>
                                                             </td>
                                                            <td class="text-center" style="cursor:pointer;" title="número de asistencias <?= $p->ago ?>" >
                                                                <a href="<?= base_url().'index.php/Agregar/Asistencia/8/'.$p->id_usuario?>" ><i class="mdi mdi-calendar  <?= ($p->ago <= 5)?'text-danger':'text-success' ?> font-18" ></i>
                                                             </td>
                                                            <td class="text-center" style="cursor:pointer;" title="número de asistencias <?= $p->sep ?>">
                                                                <a href="<?= base_url().'index.php/Agregar/Asistencia/9/'.$p->id_usuario?>" ><i class="mdi mdi-calendar  <?= ($p->sep <= 5)?'text-danger':'text-success' ?> font-18" ></i>
                                                             </td>
                                                            <td class="text-center" style="cursor:pointer;" title="número de asistencias <?= $p->oct ?>">
                                                                <a href="<?= base_url().'index.php/Agregar/Asistencia/10/'.$p->id_usuario?>" ><i class="mdi mdi-calendar  <?= ($p->oct <= 5)?'text-danger':'text-success' ?> font-18" ></i>
                                                             </td>
                                                            <td class="text-center" style="cursor:pointer;" title="número de asistencias <?= $p->nov ?>">
                                                                <a href="<?= base_url().'index.php/Agregar/Asistencia/11/'.$p->id_usuario?>" ><i class="mdi mdi-calendar  <?= ($p->nov <= 5)?'text-danger':'text-success' ?> font-18" ></i>
                                                             </td>
                                                            <td class="text-center" style="cursor:pointer;" title="número de asistencias <?= $p->dic ?>" >
                                                                <a href="<?= base_url().'index.php/Agregar/Asistencia/12/'.$p->id_usuario?>" ><i class="mdi mdi-calendar  <?= ($p->dic <= 5)?'text-danger':'text-success' ?> font-18" ></i>
                                                             </td>

                                                       

                                                            <td class="text-center">
                                                                <button title="editar"
                                                                    onclick="ini.inicio.editarPerfil(<?= $p->id_usuario ?>)"
                                                                    class="btn btn-gradient-warning px-4">
                                                                    <i class="dripicons-pencil font-21"></i>
                                                                </button>

                                                                <button title="eliminar"
                                                                    onclick="ini.inicio.eliminarPerfil(<?= $p->id_usuario ?>)"
                                                                    class="btn btn-gradient-danger px-4">
                                                                    <i class="dripicons-trash font-21"></i>
                                                                </button>
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


  <!-- modal -->
    <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Subir Archivo</h4>
                    <button type="button" class="btn-close" onclick="ini.inicio.cerrarModal()" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadCSVParticipantes" name="uploadCSVParticipantes" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="fileParticipantes">Seleccionar Archivo CSV:</label>
                            <input type="file" name="fileParticipantes" id="fileParticipantes" accept=".csv" required
                                class="form-control">
                        </div>


                    </form>
                </div>
                <div class="modal-footer" id="btn_csv">
                    <button type="button" class="btn btn-light" onclick="ini.inicio.cerrarModal()" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="ini.inicio.subirCsv()">Procesar
                        csv</button>
                </div>
                <div class="modal-footer" id="load_csv" style="display:none">
                    <button class="btn btn-primary mt-3" >
                        <div class="spinner-grow" role="status">
                            <span class="visually-hidden">.</span>
                        </div>
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

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

    $('#datatableCategorias,#datatablePeriodos,#datatableCursos').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Ruta al archivo de localización
        },
        destroy: true,
        searching: true,
    });
});


</script>