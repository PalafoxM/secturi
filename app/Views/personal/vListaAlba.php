<!-- Top Bar End -->
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
                            <?php if(in_array($session->get('id_perfil'), [1,6])): ?>
                            <button onclick="ini.inicio.agregarAlba()"
                                class="btn btn-gradient-danger px-4 float-right mt-0 mb-3"><i
                                    class="mdi mdi-account-plus-outline mr-2"></i>Agregar</button>
                             <?php endif; ?>
                            <h4 class="header-title mt-0">solicita de su valioso apoyo para dar informacion de la siguiente lista

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
                                            <?php if(in_array($session->get('id_perfil'), [1,6])): ?>
                                            <th class="text-center">ESTATUS</th>
                                            <th class="text-center">DIFUSION</th>
                                            <th class="text-center">FEC. ACTIVACION</th>
                                            <th class="text-center">FE. DESACTIVACION</th>
                                            <?php endif; ?>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                        <!--end tr-->
                                    </thead>

                                    <tbody>
                                        <?php foreach($usuario as $u): ?>
                                        <tr>
                                            <td class="text-center">
                                                <a class="user-avatar mr-2" href="#">
                                                    <img src="<?= base_url().$u->foto ?>" alt="user" class="thumb-xl rounded">
                                                </a>
                                            </td>
                                            <td class="text-center"><?= $u->nombre.' '.$u->primer_apellido.' '.$u->segundo_apellido ?></td>
                                            <td class="text-center"><?= date('d-m-Y', strtotime($u->fecha_nacimiento)) ?></td>
                                            <td class="text-center"><?= $u->edad?></td>
                                            <td class="text-center"><?= $u->id_sexo==1?'HOMBRE':'MUJER' ?></td>
                                            <td class="text-center"><?= $u->nacionalidad?></td>
                                            <?php if(in_array($session->get('id_perfil'), [1,6])): ?>
                                            <td class="text-center"><?= ($u->id_estatus==1)?'<span class="badge badge-soft-danger">Activa</span>':'<span class="badge badge-soft-info">Desactivada</span>'?></td>
                                            <td class="text-center"><?= ($u->id_difusion==1)?'<span class="badge badge-soft-success">Interna</span>':'<span class="badge badge-soft-warning">Externa</span>'?></td>
                                            <td class="text-center"><?= date('d-m-Y', strtotime($u->fec_activacion))?></td>
                                            <td class="text-center"><?= date('d-m-Y', strtotime($u->fec_desactivacion))?></td>
                                               <?php endif; ?>
                                            <td class="text-center">
                                         <?php if(in_array($session->get('id_perfil'), [1,6])): ?>
                                                <a href="javascript:void(0);"
                                                onclick="ini.inicio.getAlba(<?= $u->id_alba ?>)" >
                                                <i class="mdi mdi-pencil text-success font-18"></i>
                                                </a>
                                        <?php endif; ?>
                                                 <a href="<?php echo base_url().$u->protocolo ?>" target="_blank"
                                                    data-animation="bounce" ><i
                                                        class="mdi mdi-eye text-success font-18"></i></a>
                                        <?php if(in_array($session->get('id_perfil'), [1,6])): ?>
                                                <a href="javascript:void(0);"
                                                    onclick="ini.inicio.deleteAlba(<?=$u->id_alba?>)"><i
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

    <div id="modalAlba" class="modal fade bs-example" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar ALBA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detalleCurso" style="max-height: 70vh; overflow-y: auto;">
                    <form id="formAgregarAlba" name="formAgregarUsuarioTsi">
                        <div class="row">
                            <input type="hidden" value="0" name="id_alba" id="id_alba">
                            <input type="hidden" value="0" name="editar" id="editar">
                            <!-- seccion izquierdo incio -->
                            <div class="col-md-12 ">
                                <div class="card">
                                    <!--init card -->
                                    <div class="card-body">

                                        <div class="row">
                                         
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="nombre"
                                                        class="form-label campoObligatorio">NOMBRE</label>
                                                    <input type="text" autocomplete="off" class="form-control"
                                                        id="nombre" name="nombre" placeholder="NOMBRE">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="primer_apellido"
                                                        class="form-label campoObligatorio">PRIMER
                                                        APELLIDO</label>
                                                    <input type="text" autocomplete="off" class="form-control"
                                                        id="primer_apellido" name="primer_apellido"
                                                        placeholder="PRIMER APELLIDO">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="segundo_apellido"
                                                        class="form-label campoObligatorio">SEGUNDO
                                                        APELLIDO</label>
                                                    <input type="text" autocomplete="off" class="form-control"
                                                        id="segundo_apellido" name="segundo_apellido"
                                                        placeholder="SEGUNDO APELLIDO">
                                                </div>
                                            </div>
                                             <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="fecha_nacimiento" class="form-label campoObligatorio">FECHA
                                                        NACIMIENTO</label>
                                                    <input type="date" autocomplete="off" class="form-control"
                                                        id="fecha_nacimiento" name="fecha_nacimiento" placeholder="FEC. NACIMIENTO">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                           
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="nacionalidad" class="form-label campoObligatorio">NACIONALIDAD</label>
                                                    <select class="form-control" name="nacionalidad" id="nacionalidad">
                                                        <option value="MEXICANA">MEXICANA </option>
                                                        <option value="EXTRAJERA">EXTRAJERA</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="edad"
                                                        class="form-label campoObligatorio">EDAD</label>
                                                    <input type="text" autocomplete="off" class="form-control"
                                                        id="edad" name="edad" placeholder="EDAD">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="id_sexo" class="form-label">SEXO</label>
                                                    <select class="form-control" id="id_sexo" name="id_sexo"
                                                        data-placeholder="seleccione" style="z-index:100;">
                                                        <option value="0">seleccione</option>
                                                        <option value="1">HOMBRE</option>
                                                        <option value="2">MUJER</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="municipio" class="form-label">MUNICIPIO</label>
                                                    <select class="form-control select2" id="municipio" name="municipio">
                                                       <?php foreach($cat_municipios as $m): ?>
                                                           <option value="<?=$m->id_municipio?>" ><?=$m->nombre_municipio?></option>
                                                       <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="id_estatus" class="form-label campoObligatorio">ESTATUS</label>
                                                    <select class="form-control" name="id_estatus" id="id_estatus">
                                                        <option value="1">ACTIVA </option>
                                                        <option value="2">DESACTIVA</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="id_difusion" class="form-label">DIFUSION</label>
                                                    <select class="form-control" id="id_difusion" name="id_difusion"
                                                        data-placeholder="seleccione" style="z-index:100;">
                                                        <option value="0">seleccione</option>
                                                        <option value="1">MASIVA</option>
                                                        <option value="2">INTERNA</option>
                                                    </select>
                                                </div>
                                            </div>
                                           <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="fec_activacion"
                                                        class="form-label campoObligatorio">FEC. ACTIVACION</label>
                                                    <input type="date" autocomplete="off" class="form-control"
                                                        id="fec_activacion" name="fec_activacion" >
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3 position-relative" id="">
                                                    <label for="fec_desactivacion"
                                                        class="form-label campoObligatorio">FEC. DESACTIVACION</label>
                                                    <input type="date" autocomplete="off" class="form-control"
                                                        id="fec_desactivacion" name="fec_desactivacion" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-md-6">
                                                <div class="mb-3 position-relative">
                                                    <label for="foto" class="form-label">FOTO</label>
                                                    <input type="file" class="form-control" id="foto" name="foto" accept=".png">
                                                    <img id="previewFoto" src="" class="mt-2 img-thumbnail" style="max-width: 150px; display:none;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative">
                                                    <label for="protocolo" class="form-label">PROTOCOLO</label>
                                                    <input type="file" class="form-control" id="protocolo" name="protocolo">
                                                    <img id="previewProtocolo" src="" class="mt-2 img-thumbnail" style="max-width: 150px; display:none;">
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
                                <button class="btn btn-info" id="btnAlba" type="submit"><i class="mdi mdi-content-save"></i> Guardar
                                </button>
                                <button class="btn btn-warning" type="button" data-dismiss="modal"><i
                                        class="mdi mdi-content-save-off-outline" id="cancelarTurno"></i> Cancelar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



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
<script>
    ini.inicio.altaAlba();
</script>