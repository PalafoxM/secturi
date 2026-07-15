<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item active">Reportes de seguridad</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Actos y condiciones inseguras</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Listado de reportes</h4>
                            <p class="text-muted mb-4">Consulta la información completa y la evidencia registrada en cada reporte.</p>

                            <div class="table-responsive">
                                <table id="tablaReportesInseguros" class="table table-striped table-bordered w-100">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Folio</th>
                                            <th>Fecha de registro</th>
                                            <th>Tipo</th>
                                            <th>Acción o condición</th>
                                            <th>Reportante</th>
                                            <th>Ubicación</th>
                                            <th>Evidencia</th>
                                            <th class="text-center">Detalles</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (($reportes ?? []) as $reporte): ?>
                                            <?php
                                            $fechaRegistro = !empty($reporte['fecha_registro'])
                                                ? date('d/m/Y H:i', strtotime($reporte['fecha_registro']))
                                                : 'Sin fecha';
                                            $detalleJson = htmlspecialchars(
                                                json_encode($reporte, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            );
                                            ?>
                                            <tr>
                                                <td><?= esc($reporte['id']) ?></td>
                                                <td data-order="<?= esc($reporte['fecha_registro']) ?>"><?= esc($fechaRegistro) ?></td>
                                                <td>
                                                    <span class="badge <?= (int) $reporte['id_reporte'] === 1 ? 'badge-warning' : 'badge-danger' ?>">
                                                        <?= esc($reporte['tipo_reporte']) ?>
                                                    </span>
                                                </td>
                                                <td><?= esc($reporte['accion']) ?></td>
                                                <td>
                                                    <?= esc($reporte['reportante']) ?>
                                                    <?php if ($reporte['anonimo'] === 'SI'): ?>
                                                        <span class="badge badge-secondary">Anónimo</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= esc($reporte['ubicacion'] ?: 'No disponible') ?></td>
                                                <td class="text-center">
                                                    <?php if (!empty($reporte['evidencia_url'])): ?>
                                                        <a class="btn btn-sm btn-outline-primary" href="<?= esc($reporte['evidencia_url']) ?>" target="_blank" rel="noopener noreferrer">
                                                            <i class="mdi mdi-paperclip"></i> Ver archivo
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">Sin archivo</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-info btn-detalle-inseguro" data-detalle="<?= $detalleJson ?>">
                                                        <i class="mdi mdi-eye"></i> Ver
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if (empty($reportes)): ?>
                                <div class="alert alert-info mt-3 mb-0">No existen reportes registrados.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalDetalleInseguro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="tituloDetalleInseguro" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloDetalleInseguro">Detalle del reporte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3"><strong>Folio</strong><div id="detalle_folio"></div></div>
                    <div class="col-md-4 mb-3"><strong>Fecha de registro</strong><div id="detalle_fecha_registro"></div></div>
                    <div class="col-md-4 mb-3"><strong>Reporte anónimo</strong><div id="detalle_anonimo"></div></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><strong>Tipo de reporte</strong><div id="detalle_tipo"></div></div>
                    <div class="col-md-6 mb-3"><strong>Reportante</strong><div id="detalle_reportante"></div></div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3"><strong>ID de acción</strong><div id="detalle_id_accion"></div></div>
                    <div class="col-md-8 mb-3"><strong>Acción o condición</strong><div id="detalle_accion"></div></div>
                </div>
                <div class="row detalle-solo-acto">
                    <div class="col-md-12 mb-3"><strong>¿Quién llevó a cabo el acto?</strong><div id="detalle_quien"></div></div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3"><strong>Descripción</strong><div id="detalle_descripcion" class="detalle-texto"></div></div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3"><strong>Ubicación</strong><div id="detalle_ubicacion" class="detalle-texto"></div></div>
                </div>
                <div class="row detalle-solo-acto">
                    <div class="col-md-6 mb-3"><strong>Fecha de los hechos</strong><div id="detalle_fecha_hechos"></div></div>
                    <div class="col-md-6 mb-3"><strong>¿Hubo testigos?</strong><div id="detalle_testigos"></div></div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3"><strong>Propuesta de solución o mejora</strong><div id="detalle_propuesta" class="detalle-texto"></div></div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <strong>Evidencia</strong><br>
                        <a id="detalle_evidencia" class="btn btn-sm btn-outline-primary mt-1" href="#" target="_blank" rel="noopener noreferrer">
                            <i class="mdi mdi-paperclip"></i> Abrir evidencia
                        </a>
                        <span id="detalle_sin_evidencia" class="text-muted">Sin evidencia disponible</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<link href="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>

<style>
    .detalle-texto {
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }
</style>

<script>
$(document).ready(function () {
    if ($.fn.DataTable) {
        $('#tablaReportesInseguros').DataTable({
            order: [[0, 'desc']],
            pageLength: 25,
            language: {
                search: 'Buscar:',
                lengthMenu: 'Mostrar _MENU_ registros',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ reportes',
                infoEmpty: 'No hay reportes disponibles',
                zeroRecords: 'No se encontraron coincidencias',
                paginate: { previous: 'Anterior', next: 'Siguiente' }
            }
        });
    }

    $(document).on('click', '.btn-detalle-inseguro', function () {
        let detalle;
        try {
            detalle = JSON.parse($(this).attr('data-detalle'));
        } catch (error) {
            Swal.fire('Error', 'No fue posible leer el detalle del reporte.', 'error');
            return;
        }

        const texto = function (valor) {
            return valor === null || valor === undefined || String(valor).trim() === '' ? 'No aplica' : String(valor);
        };
        const esActo = Number(detalle.id_reporte) === 1;

        $('#detalle_folio').text(texto(detalle.id));
        $('#detalle_fecha_registro').text(texto(detalle.fecha_registro));
        $('#detalle_anonimo').text(texto(detalle.anonimo));
        $('#detalle_tipo').text(texto(detalle.tipo_reporte));
        $('#detalle_reportante').text(texto(detalle.reportante));
        $('#detalle_id_accion').text(texto(detalle.id_accion));
        $('#detalle_accion').text(texto(detalle.accion));
        $('#detalle_quien').text(texto(detalle.quien));
        $('#detalle_descripcion').text(texto(detalle.descripcion));
        $('#detalle_ubicacion').text(texto(detalle.ubicacion));
        $('#detalle_fecha_hechos').text(texto(detalle.fecha_hechos));
        $('#detalle_testigos').text(texto(detalle.hubo_testigos));
        $('#detalle_propuesta').text(texto(detalle.propuesta));
        $('.detalle-solo-acto').toggle(esActo);

        if (detalle.evidencia_url) {
            $('#detalle_evidencia').attr('href', detalle.evidencia_url).show();
            $('#detalle_sin_evidencia').hide();
        } else {
            $('#detalle_evidencia').attr('href', '#').hide();
            $('#detalle_sin_evidencia').show();
        }

        $('#modalDetalleInseguro').modal('show');
    });
});
</script>
