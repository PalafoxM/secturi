<?php
$records = $registro ?? [];
$markers = [];
$omitted = 0;

foreach ($records as $record) {
    $latitude = isset($record->latitud) ? (float) $record->latitud : null;
    $longitude = isset($record->longitud) ? (float) $record->longitud : null;

    if ($latitude === null || $longitude === null ||
        $latitude < -90 || $latitude > 90 ||
        $longitude < -180 || $longitude > 180 ||
        ($latitude === 0.0 && $longitude === 0.0)) {
        $omitted++;
        continue;
    }

    $markers[] = [
        'folio' => (string) ($record->folio_obra_accion ?? ''),
        'nombre' => (string) ($record->nombre_obra_accion ?? 'Obra o acción sin nombre'),
        'latitud' => $latitude,
        'longitud' => $longitude,
    ];
}
?>
<link href="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>plugins/select2/select2.min.js"></script>
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="<?= base_url('plugins/leaflet/leaflet.css') ?>">

<style>
    #mapaObrasTurismo {
        height: calc(100vh - 250px);
        min-height: 520px;
        border: 1px solid #dce2eb;
        border-radius: .4rem;
        z-index: 1;
    }
    .mapa-summary .media { min-width: 145px; }
    .mapa-summary .icon-info { width: 38px; height: 38px; line-height: 38px; text-align: center; border-radius: 50%; }
    .mapa-empty {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 500;
        width: min(420px, 85%);
        text-align: center;
        background: rgba(255, 255, 255, .96);
        padding: 1.5rem;
        border-radius: .5rem;
        box-shadow: 0 5px 20px rgba(31, 45, 61, .15);
    }
    .mapa-container { position: relative; }
    .leaflet-popup-content .obra-popup-name { font-weight: 600; color: #263b5e; line-height: 1.35; }
    @media (max-width: 767.98px) {
        #mapaObrasTurismo { height: 65vh; min-height: 420px; }
        .page-title-box .float-right { float: none !important; margin-bottom: 1rem; }
    }
</style>

<div class="page-content-tab">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="float-right">
                        <a class="btn btn-outline-secondary" href="<?= base_url('index.php/Principal/ListaIgto') ?>">
                            <i class="fas fa-arrow-left mr-1"></i>Volver al listado
                        </a>
                    </div>
                    <h4 class="page-title">Mapa de obras y acciones</h4>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between mapa-summary mb-3">
                    <div>
                        <h4 class="mt-0 mb-1">Ubicación de obras turísticas</h4>
                        <p class="text-muted mb-0">Seleccione un marcador para consultar el nombre de la obra o acción.</p>
                    </div>
                    <div class="d-flex flex-wrap mt-3 mt-md-0">
                        <div class="media align-items-center mr-4">
                            <div class="icon-info bg-soft-primary text-primary mr-2"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="media-body"><small class="text-muted d-block">En el mapa</small><strong><?= count($markers) ?></strong></div>
                        </div>
                        <?php if ($omitted > 0): ?>
                            <div class="media align-items-center">
                                <div class="icon-info bg-soft-warning text-warning mr-2"><i class="fas fa-exclamation-triangle"></i></div>
                                <div class="media-body"><small class="text-muted d-block">Sin coordenadas válidas</small><strong><?= $omitted ?></strong></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mapa-container">
                    <div id="mapaObrasTurismo" aria-label="Mapa de obras y acciones turísticas"></div>
                    <?php if (empty($markers)): ?>
                        <div class="mapa-empty">
                            <i class="fas fa-map-marker-alt fa-2x text-muted mb-2"></i>
                            <h5>No hay ubicaciones disponibles</h5>
                            <p class="text-muted mb-0">Las obras registradas no tienen coordenadas válidas para mostrarse.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('plugins/leaflet/leaflet.js') ?>"></script>
<script>
(function () {
    'use strict';

    const obras = <?= json_encode($markers, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?: '[]' ?>;
    const map = L.map('mapaObrasTurismo', { scrollWheelZoom: true }).setView([20.8756, -100.9867], 8);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>'
    }).addTo(map);

    const bounds = [];
    obras.forEach(function (obra) {
        const point = [obra.latitud, obra.longitud];
        const marker = L.marker(point, {
            title: obra.nombre,
            alt: 'Ubicación de ' + obra.nombre
        }).addTo(map);

        const popup = document.createElement('div');
        popup.className = 'obra-popup-name';
        popup.textContent = obra.nombre;
        marker.bindPopup(popup, { maxWidth: 340 });
        bounds.push(point);
    });

    if (bounds.length === 1) {
        map.setView(bounds[0], 16);
    } else if (bounds.length > 1) {
        map.fitBounds(bounds, { padding: [35, 35], maxZoom: 16 });
    }

    L.control.scale({ imperial: false, position: 'bottomleft' }).addTo(map);
    window.setTimeout(function () { map.invalidateSize(); }, 250);
})();
</script>
