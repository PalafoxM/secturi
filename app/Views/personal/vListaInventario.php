<?php  $session = \Config\Services::session();    ?>
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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Lista</a></li>
                                <li class="breadcrumb-item active">ALBA</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Atención Comunidad SECTURI</h4>

                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <button onclick="ini.inicio.agregarInventario()"
                                class="btn btn-gradient-danger px-4 float-right mt-0 mb-3"><i
                                    class="mdi mdi-plus-outline mr-2"></i>Agregar Inventario</button>
                           
                            <h4 class="header-title mt-0">Lista Inventarios

                            </h4>
                            <div class="table-responsive dash-social">
                                <table id="usuariosTable" class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">FOTO</th>
                                            <th class="text-center">NOMBRE</th>
                                            <th class="text-center">FECHA NAC.</th>
                                            <th class="text-center">EDAD</th>
                                            <th class="text-center">SEXO</th>
                                            <th class="text-center">NACIONALIDAD</th>

                                            <th class="text-center">ESTATUS</th>
                                            <th class="text-center">DIFUSION</th>
                                            <th class="text-center">FEC. ACTIVACION</th>
                                            <th class="text-center">FE. DESACTIVACION</th>

                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                        <!--end tr-->
                                    </thead>

                                    <tbody>
                                        <?php foreach($usuario as $u): ?>
                                        <tr>
                                            <td class="text-center">
                                                
                                            </td>
                                            <td class="text-center"><?= $u->nombre.' '.$u->primer_apellido.' '.$u->segundo_apellido ?></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"><?= $u->id_sexo==1?'HOMBRE':'MUJER' ?></td>
                                            <td class="text-center"></td>
  
                                            <td class="text-center"><?= ($u->id_sexo==1)?'<span class="badge badge-soft-danger">Activa</span>':'<span class="badge badge-soft-info">Desactivada</span>'?></td>
                                            <td class="text-center"><?= ($u->id_sexo==1)?'<span class="badge badge-soft-success">Interna</span>':'<span class="badge badge-soft-warning">Externa</span>'?></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
    
                                            <td class="text-center">
                                         <?php if(in_array($session->get('id_perfil'), [1,6])): ?>
                                                <a href="javascript:void(0);"
                                                onclick="ini.inicio.getAlba()" >
                                                <i class="mdi mdi-pencil text-success font-18"></i>
                                                </a>
                                        <?php endif; ?>
                                                 <i
                                                        class="mdi mdi-eye text-success font-18"></i>
                                        <?php if(in_array($session->get('id_perfil'), [1,6])): ?>
                                                <a href="javascript:void(0);"
                                                    onclick="ini.inicio.deleteAlba()"><i
                                                        class="mdi mdi-trash-can text-danger font-18"></i></a>
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
        </div>
    </div>

<!-- Modal -->
 <div id="modelInventarios" class="modal fade bs-example" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Inventario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detalleCurso" style="max-height: 70vh; overflow-y: auto;">
                    <form id="formAgregarUsuarioTsi" name="formAgregarUsuarioTsi">
                        <div class="row">
                            <input type="hidden" value="0" name="id_usuario" id="id_usuario">
                            <input type="hidden" value="0" name="editar" id="editar">
                            <!-- seccion izquierdo incio -->
                            <div class="col-md-12 ">
                                <div class="card">
                                    <!--init card -->
                                    <div class="card-body">

                                        <div class="row">
                                         
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="unidad"
                                                        class="form-label campoObligatorio">Unidad Responsable</label>
                                                    <select class="form-control select2"
                                                        id="unidad" name="unidad"
                                                        style="z-index:100;">
                                                        <option value="0">Seleccione</option>
                                                        <?php foreach($cat_area as $a): ?>
                                                        <option value="<?= $a->id_area ?>"><?= $a->dsc_area ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="usuario"
                                                        class="form-label campoObligatorio">Usuario</label>
                                                    <select class="form-control select2" 
                                                        id="usuario" name="usuario">
                                                        <option value="0">Seleccione</option>
                                                        <?php foreach($usuario as $a): ?>
                                                        <option value="<?= $a->id_usuario ?>"><?= $a->nombre_completo ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="plaza"
                                                        class="form-label campoObligatorio">Plaza</label>
                                                    <input type="text" autocomplete="off" class="form-control"
                                                        id="plaza" name="plaza"
                                                        placeholder="PLAZA">
                                                </div>
                                            </div>
                                             <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="fec_nac" class="form-label campoObligatorio">FECHA
                                                        NACIMIENTO</label>
                                                    <input type="date" autocomplete="off" class="form-control"
                                                        id="fec_nac" name="fec_nac" placeholder="FEC. NACIMIENTO">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                   
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" >
                                                    <label for="nivel"
                                                        class="form-label campoObligatorio">NIVEL</label>
                                                    <input type="number" autocomplete="off" class="form-control"
                                                        id="nivel" name="nivel"
                                                        placeholder="NIVEL">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" >
                                                    <label for="extencion"
                                                        class="form-label">EXTENCION</label>
                                                    <input type="number" autocomplete="off" class="form-control" id="extencion" name="extencion"
                                                        placeholder="EXT">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end card -->
                            </div>
                            <!-- seccion izquierdo fin-->
                            <!-- seccion derecha incio -->
                        </div>


                        <div class="row mb-5" id="btn_save">
                            <div class="col-md-12 text-center ">
                                <button class="btn btn-info" type="submit"><i class="mdi mdi-content-save"></i> Guardar
                                </button>
                                <button class="btn btn-warning" type="button" data-dismiss="modal"><i
                                        class="mdi mdi-content-save-off-outline" id="cancelarTurno"></i> Cancelar
                                </button>
                            </div>
                        </div>
                        <div class="row mb-5" id="btn_load" style="display:none;">
                            <div class="col-md-12 text-center ">
                                <button class="btn btn-info" type="button" disabled>
                                    <span class="spinner-grow spinner-grow-sm me-1" role="status"
                                        aria-hidden="true"></span>
                                    Guardando...
                                </button>
                            </div>
                        </div>

                    </form>



                </div>
            </div>
        </div>
    </div>

<!-- Modal -->


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
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />

<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>
