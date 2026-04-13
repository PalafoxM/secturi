<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h4 class="page-title mb-0">Historial de Reportes por Honorarios</h4>
                            <div class="mt-2 mt-md-0">
                                <a href="<?= base_url('index.php/Inicio/FormHonorarios') ?>" class="btn btn-primary btn-sm">
                                    <i class="mdi mdi-plus-box"></i> Nuevo reporte
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle" id="tablaReporteHonorarios">
                                    <thead class="thead-light">
                                        <tr class="text-center">
                                            <th>ID</th>
                                            <th>Contrato</th>
                                            <th>Tipo</th>
                                            <th>Periodo</th>
                                            <th>Prestador</th>
                                            <th>Responsable administrativo</th>
                                            <th>Fecha de captura</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($reportes)): ?>
                                            <?php foreach ($reportes as $reporte): ?>
                                                <tr>
                                                    <td class="text-center"><?= intval($reporte->id_reporte_honorarios ?? 0) ?></td>
                                                    <td><?= esc($reporte->numero_contrato ?? '') ?></td>
                                                    <td class="text-center">
                                                        <span class="badge badge-soft-info p-2">
                                                            <?= esc(ucfirst($reporte->tipo_reporte ?? '')) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                            $inicio = !empty($reporte->fecha_inicio) ? date('d/m/Y', strtotime($reporte->fecha_inicio)) : '--';
                                                            $fin = !empty($reporte->fecha_fin) ? date('d/m/Y', strtotime($reporte->fecha_fin)) : '--';
                                                        ?>
                                                        <?= esc($inicio) ?> al <?= esc($fin) ?>
                                                    </td>
                                                    <td><?= esc($reporte->nombre_prestador ?? '') ?></td>
                                                    <td><?= esc($reporte->responsable_administrativo ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= !empty($reporte->fec_reg) ? esc(date('d/m/Y h:i A', strtotime($reporte->fec_reg))) : '--' ?>
                                                    </td>
                                                    <td class="text-center text-nowrap">
                                                        <a href="<?= base_url('index.php/Inicio/FormHonorarios/' . intval($reporte->id_reporte_honorarios ?? 0)) ?>" class="btn btn-outline-primary btn-sm">
                                                            <i class="mdi mdi-pencil"></i> Editar
                                                        </a>
                                                        <a href="<?= base_url('index.php/Inicio/pdfreporteHonorario/' . intval($reporte->id_reporte_honorarios ?? 0)) ?>" target="_blank" class="btn btn-outline-success btn-sm">
                                                            <i class="mdi mdi-file-pdf-box"></i> Consultar
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-4">
                                                    No hay reportes registrados.
                                                </td>
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

<script>
    $(function () {
        if ($.fn.DataTable) {
            $('#tablaReporteHonorarios').DataTable({
                destroy: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        }
    });
</script>
