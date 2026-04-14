<!-- Top Bar End -->
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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Metrica</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Analytics</a></li>
                                <li class="breadcrumb-item active">Usuarios</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Usuario</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mt-0">Lista de Fichas Tecnicas</h4>

                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success">
                                    <?= session()->getFlashdata('success') ?>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger">
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>

                            <div class="table-responsive dash-social">
                                <table id="usuariosTable" class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">NOMBRE EVENTO</th>
                                            <th class="text-center">PERSONA SOLICITUD</th>
                                            <th class="text-center">MUNICIPIO SEDE</th>
                                            <th class="text-center">COSTO TOTAL</th>
                                            <th class="text-center">RAZON SOCIAL</th>
                                            <th class="text-center">NOMBRE EMPLEADO</th>
                                            <th class="text-center">CORREO</th>
                                            <th class="text-center">ACCIONES</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($fichas as $f): ?>
                                            <tr>
                                                <td class="text-center"><?= $f->nombre_evento ?></td>
                                                <td class="text-center"><?= $f->persona_solicitud ?></td>
                                                <td class="text-center"><?= $f->municipio_sede ?></td>
                                                <td class="text-center"><?= $f->costo_total ?></td>
                                                <td class="text-center"><?= $f->co_razon_social ?></td>
                                                <td class="text-center"><?= $f->em_nombre ?></td>
                                                <td class="text-center"><?= $f->co_email ?></td>
                                                <td class="text-center">
                                                    <a target="_blank"
                                                        href="<?= base_url() . 'index.php/Principal/pdfFicha?id_ficha_tecnica=' . $f->id_ficha_tecnica ?>"
                                                        class="btn btn-outline-info btn-sm mr-1"
                                                        title="Ver PDF">
                                                        <i class="mdi mdi-file-pdf"></i>
                                                    </a>
                                                    <a href="<?= base_url() . 'index.php/Principal/enviarFichaTecnica?id_ficha_tecnica=' . $f->id_ficha_tecnica ?>"
                                                        class="btn btn-outline-primary btn-sm"
                                                        onclick="return confirm('¿Deseas enviar esta ficha técnica al correo <?= esc($f->co_email ?? '') ?>?');"
                                                        title="Enviar ficha técnica">
                                                        <i class="mdi mdi-send"></i> Enviar
                                                    </a>
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

    <link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />

    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>
    <script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url() ?>assets/js/app.js"></script>
    <script src="<?= base_url() ?>assets/js/waves.js"></script>
    <script src="<?= base_url() ?>assets/js/feather.min.js"></script>
    <script src="<?= base_url() ?>plugins/tiny-editable/mindmup-editabletable.js"></script>
    <script src="<?= base_url() ?>plugins/tiny-editable/numeric-input-example.js"></script>
    <script src="<?= base_url() ?>plugins/bootable/bootstable.js"></script>
    <link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>

    <script>
        ini.inicio.agregarUsuario();
        $('#usuariosTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            destroy: true,
            searching: true,
        });
    </script>
