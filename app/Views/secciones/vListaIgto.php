<?php
$records = $registro ?? [];
$projectsById = [];
foreach (($proyecto ?? []) as $project) {
    $projectsById[(string) $project->id_cat_proyecto_q] = $project->dsc_proyecto_q;
}

$recordsForJs = [];
foreach ($records as $record) {
    $row = (array) $record;
    if (isset($row['es_refrendo'])) {
        $row['es_refrendo'] = (int) $row['es_refrendo'] === 1 ? 'Sí' : 'No';
    }
    if (isset($row['proyecto_inversion'], $projectsById[(string) $row['proyecto_inversion']])) {
        $row['proyecto_inversion'] .= ' - ' . $projectsById[(string) $row['proyecto_inversion']];
    }
    $recordsForJs[] = $row;
}
?>

<div class="page-content-tab">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="float-right">
                        <a class="btn btn-info mr-2" href="<?= base_url('index.php/Principal/Mapa') ?>">
                            <i class="fas fa-map-marked-alt mr-1"></i>Mapa
                        </a>
                        <a class="btn btn-primary" href="<?= base_url('index.php/Principal/Igto') ?>">
                            <i class="fas fa-plus mr-1"></i>Nueva obra o acción
                        </a>
                    </div>
                    <h4 class="page-title">Obras y acciones IGTO</h4>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableIgto" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ejercicio</th>
                                <th>Proyecto de inversi&oacute;n</th>
                                <th>Obra o acción</th>
                                <th>Municipio</th>
                                <th>Estatus</th>
                                <th>Avance físico</th>
                                <th>Monto modificado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($records as $index => $item): ?>
                                <tr>
                                    <td><?= esc($item->id ?? '') ?></td>
                                    <td><?= esc($item->ejercicio ?? '') ?></td>
                                    <td><?= esc($projectsById[(string) ($item->proyecto_inversion ?? '')] ?? ($item->proyecto_inversion ?? '')) ?></td>
                                    <td><?= esc($item->nombre_simplificado ?? $item->nombre_obra_accion ?? '') ?></td>
                                    <td><?= esc($item->municipio ?? '') ?></td>
                                    <td><?= esc($item->estatus_avance ?? '') ?></td>
                                    <td><?= number_format((float) ($item->avance_fisico ?? 0), 2) ?>%</td>
                                    <td>$<?= number_format((float) ($item->monto_total_modificado ?? 0), 2) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="showIgtoDetail(<?= (int) $index ?>)">
                                            <i class="fas fa-eye mr-1"></i>Ver toda la información
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

<div class="modal fade" id="modalIgtoDetail" tabindex="-1" role="dialog" aria-labelledby="modalIgtoDetailTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalIgtoDetailTitle">Detalle de obra o acción</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row" id="igtoDetailContent"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></div>
        </div>
    </div>
</div>

<link href="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>plugins/select2/select2.min.js"></script>
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />


<script>
const igtoRecords = <?= json_encode($recordsForJs, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?: '[]' ?>;
const igtoLabels = {
    id: 'ID', proyecto_inversion: 'Proyecto de inversión', icono_mapa: 'Icono del mapa',
    nombre_obra_accion: 'Nombre de la obra/acción',
    nombre_simplificado: 'Nombre simplificado', fecha_entrega: 'Fecha de entrega',
    tipo_obra_accion: 'Tipo de obra/acción', es_refrendo: 'Es refrendo',
    anio_refrendo: 'Año de refrendo', monto_pagado_devengado_estatal: 'Monto pagado/devengado estatal',
    monto_total_pagado_devengado: 'Monto total pagado/devengado',
    monto_total_pagado_devengado_sfia: 'Monto total pagado/devengado SFIA',
    usuario_inserto: 'Usuario que insertó', usuario_ultima_modificacion: 'Usuario que modificó'
};

function igtoFieldLabel(field) {
    if (igtoLabels[field]) return igtoLabels[field];
    const label = field.replaceAll('_', ' ');
    return label.charAt(0).toUpperCase() + label.slice(1);
}

function showIgtoDetail(index) {
    const record = igtoRecords[index];
    const content = document.getElementById('igtoDetailContent');
    content.innerHTML = '';
    Object.keys(record).forEach(function (field) {
        const column = document.createElement('div');
        column.className = 'col-md-6 col-lg-4 mb-3';
        const card = document.createElement('div');
        card.className = 'border rounded h-100 p-2';
        const label = document.createElement('small');
        label.className = 'd-block text-muted font-weight-bold';
        label.textContent = igtoFieldLabel(field);
        const value = document.createElement('span');
        value.className = 'text-break';
        value.textContent = record[field] === null || record[field] === '' ? '—' : record[field];
        card.appendChild(label);
        card.appendChild(value);
        column.appendChild(card);
        content.appendChild(column);
    });
    document.getElementById('modalIgtoDetailTitle').textContent = 'Detalle: ' + (record.nombre_simplificado || record.nombre_obra_accion || ('registro #' + record.id));
    $('#modalIgtoDetail').modal('show');
}

$(function () {
    if ($.fn.DataTable) {
        $('#tableIgto').DataTable({
            language: {
                emptyTable: 'No hay obras o acciones registradas', info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty: 'Mostrando 0 a 0 de 0 registros', lengthMenu: 'Mostrar _MENU_ registros',
                search: 'Buscar:', zeroRecords: 'No se encontraron resultados',
                paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
            },
            order: [[1, 'desc'], [0, 'asc']],
            pageLength: 25
        });
    }
});
</script>
