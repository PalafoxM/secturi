<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item active">Honorarios</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Solicitudes de Honorarios</h4>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 text-right">
                    <a href="<?= base_url('index.php/Principal/SolicitudHonorarios') ?>" class="btn btn-primary">
                        Nueva Solicitud
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Prestador</th>
                                            <th>RFC</th>
                                            <th>Responsable</th>
                                            <th>Vigencia</th>
                                            <th>Monto</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($solicitudes)): ?>
                                            <?php foreach ($solicitudes as $solicitud): ?>
                                                <tr>
                                                    <td><?= $solicitud->id_solicitud_honorario ?></td>
                                                    <td><?= esc($solicitud->nombre_prestador ?? '') ?></td>
                                                    <td><?= esc($solicitud->rfc_prestador ?? '') ?></td>
                                                    <td><?= esc($solicitud->responsable_proyecto_nombre ?? '') ?></td>
                                                    <td>
                                                        <?= !empty($solicitud->vigencia_inicio) ? date('d/m/Y', strtotime($solicitud->vigencia_inicio)) : '' ?>
                                                        -
                                                        <?= !empty($solicitud->vigencia_fin) ? date('d/m/Y', strtotime($solicitud->vigencia_fin)) : '' ?>
                                                    </td>
                                                    <td>$<?= number_format((float) ($solicitud->monto_total_contrato ?? 0), 2) ?></td>
                                                    <td>
                                                        <a href="<?= base_url('index.php/Principal/pdfSolicitudHonorarios/' . $solicitud->id_solicitud_honorario) ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                                            PDF
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No hay solicitudes de honorarios registradas.</td>
                                            </tr>
                                        <?php endif; ?>
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
