<?php $session = \Config\Services::session(); ?>
<div class="page-content-tab">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="float-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Inicio</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Dashboard Operativo</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <?php foreach (($dashboardCards ?? []) as $card): ?>
                <div class="col-md-6 col-xl-3">
                    <div class="card report-card">
                        <div class="card-body">
                            <div class="row d-flex justify-content-center">
                                <div class="col-8">
                                    <p class="text-dark font-weight-semibold font-14 mb-1"><?= esc($card['title']) ?></p>
                                    <h3 class="my-2"><?= esc($card['value']) ?></h3>
                                    <p class="mb-0 text-muted"><?= esc($card['subtitle']) ?></p>
                                </div>
                                <div class="col-4 align-self-center">
                                    <div class="report-main-icon bg-light-alt">
                                        <i data-feather="<?= esc($card['icon']) ?>" class="align-self-center icon-dual-<?= esc($card['color']) ?> icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="header-title mt-0 mb-0">Resumen de módulos</h4>
                            <span class="badge badge-soft-primary">Datos reales del sistema</span>
                        </div>
                        <div class="table-responsive browser_users">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Módulo</th>
                                        <th>Total</th>
                                        <th>Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (($dashboardModules ?? []) as $module): ?>
                                        <tr>
                                            <td class="font-weight-semibold"><?= esc($module['module']) ?></td>
                                            <td><?= esc($module['total']) ?></td>
                                            <td>
                                                <?php if (!empty($module['status'])): ?>
                                                    <?php foreach ($module['status'] as $statusLabel => $statusTotal): ?>
                                                        <span class="badge badge-soft-secondary mr-1 mb-1"><?= esc($statusLabel) ?>: <?= esc($statusTotal) ?></span>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin estatus configurados</span>
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

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-0 mb-3">Tickets por estatus</h4>
                        <?php $ticketTotal = array_sum($dashboardTickets ?? []); ?>
                        <?php if (!empty($dashboardTickets)): ?>
                            <?php foreach ($dashboardTickets as $label => $value): ?>
                                <?php $percent = $ticketTotal > 0 ? round(($value / $ticketTotal) * 100) : 0; ?>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-dark"><?= esc($label) ?></span>
                                        <span class="text-muted"><?= esc($value) ?> (<?= esc($percent) ?>%)</span>
                                    </div>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?= $percent ?>%" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0">No hay tickets registrados.</p>
                        <?php endif; ?>

                        <hr>

                        <h4 class="header-title mt-0 mb-3">Usuarios</h4>
                        <div class="row text-center">
                            <div class="col-4">
                                <h3 class="mb-0"><?= esc($dashboardUsers['total'] ?? 0) ?></h3>
                                <small class="text-muted">Visibles</small>
                            </div>
                            <div class="col-4">
                                <h3 class="mb-0 text-success"><?= esc($dashboardUsers['active'] ?? 0) ?></h3>
                                <small class="text-muted">Activos</small>
                            </div>
                            <div class="col-4">
                                <h3 class="mb-0 text-warning"><?= esc($dashboardUsers['inactive'] ?? 0) ?></h3>
                                <small class="text-muted">Inactivos</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-0 mb-3">Actividad reciente</h4>
                        <div class="table-responsive browser_users">
                            <table class="table table-striped mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Módulo</th>
                                        <th>Registro</th>
                                        <th>Estatus</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($dashboardRecentItems)): ?>
                                        <?php foreach ($dashboardRecentItems as $item): ?>
                                            <tr>
                                                <td><?= esc($item['module']) ?></td>
                                                <td><?= esc($item['title']) ?></td>
                                                <td><span class="badge badge-soft-secondary"><?= esc($item['status']) ?></span></td>
                                                <td><?= esc($item['date']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No se encontraron movimientos recientes.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-0 mb-3">Registros por mes (<?= date('Y') ?>)</h4>
                        <div class="table-responsive browser_users">
                            <table class="table table-sm mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Módulo</th>
                                        <?php foreach (($dashboardMonthLabels ?? []) as $monthLabel): ?>
                                            <th><?= esc($monthLabel) ?></th>
                                        <?php endforeach; ?>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (($dashboardMonthlyRows ?? []) as $label => $values): ?>
                                        <tr>
                                            <td class="font-weight-semibold"><?= esc($label) ?></td>
                                            <?php foreach ($values as $value): ?>
                                                <td><?= esc($value) ?></td>
                                            <?php endforeach; ?>
                                            <td class="font-weight-bold"><?= esc(array_sum($values)) ?></td>
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

