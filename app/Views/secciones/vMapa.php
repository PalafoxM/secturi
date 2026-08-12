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

    $iconValue = trim((string) ($record->icono_mapa ?? ''));
    $iconId = ctype_digit($iconValue) ? $iconValue : '33';
    $iconUrl = '';
    if ($iconValue !== '' && !ctype_digit($iconValue)) {
        $iconUrl = preg_match('~^(?:https?:)?//|^data:~i', $iconValue)
            ? $iconValue
            : base_url(ltrim(str_replace('\\', '/', $iconValue), '/'));
    }

    $markers[] = [
        'id' => (int) ($record->id ?? 0),
        'proyecto' => (string) ($record->proyecto_inversion ?? ''),
        'nombre' => (string) ($record->nombre_obra_accion ?? $record->nombre_simplificado ?? 'Obra o acción sin nombre'),
        'nombre_simplificado' => (string) ($record->nombre_simplificado ?? ''),
        'categoria' => (string) ($record->categoria ?? ''),
        'subcategoria' => (string) ($record->subcategoria ?? ''),
        'municipio' => (string) ($record->municipio ?? ''),
        'localidad' => (string) ($record->localidad ?? ''),
        'estatus' => (string) ($record->estatus_avance ?? ''),
        'avance_fisico' => (float) ($record->avance_fisico ?? 0),
        'icono_id' => $iconId,
        'icono_url' => $iconUrl,
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
    .leaflet-popup-content .obra-popup-detail { margin-top: .35rem; color: #5b6b82; line-height: 1.4; }
    .obra-marker-wrapper { background: transparent; border: 0; }
    .obra-marker {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #fff;
        border-radius: 50% 50% 50% 0;
        color: #fff;
        font-size: 16px;
        box-shadow: 0 3px 9px rgba(25, 40, 65, .38);
        transform: rotate(-45deg);
    }
    .obra-marker i { transform: rotate(45deg); }
    .obra-map-legend {
        max-width: 230px;
        padding: 9px 11px;
        background: rgba(255, 255, 255, .96);
        border-radius: .35rem;
        box-shadow: 0 1px 7px rgba(0, 0, 0, .25);
        color: #33415c;
        line-height: 1.25;
    }
    .obra-map-legend strong { display: block; margin-bottom: 6px; }
    .obra-map-legend-item { display: flex; align-items: center; margin-top: 5px; }
    .obra-map-legend-symbol {
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 24px;
        margin-right: 7px;
        border: 2px solid #fff;
        border-radius: 50%;
        color: #fff;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .25);
    }
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

    const iconCatalog = {
        '3':  { icon: 'fas fa-bullhorn', color: '#e83e8c', label: 'Promoción y comercialización' },
        '6':  { icon: 'fas fa-building', color: '#5969ff', label: 'Edificios y paradores' },
        '8':  { icon: 'fas fa-calendar-alt', color: '#f59f00', label: 'Festivales, cultura y eventos' },
        '24': { icon: 'fas fa-lightbulb', color: '#17a2b8', label: 'Inteligencia y productos turísticos' },
        '31': { icon: 'fas fa-hand-holding-usd', color: '#28a745', label: 'Incentivos a prestadores' },
        '33': { icon: 'fas fa-map-marker-alt', color: '#6c757d', label: 'Obra o acción general' },
        '48': { icon: 'fas fa-leaf', color: '#20c997', label: 'Sustentabilidad turística' },
        '58': { icon: 'fas fa-film', color: '#6f42c1', label: 'Industria cinematográfica' }
    };

    function getIconDefinition(iconId) {
        return iconCatalog[String(iconId)] || iconCatalog['33'];
    }

    function createMarkerIcon(iconId) {
        const definition = getIconDefinition(iconId);
        return L.divIcon({
            className: 'obra-marker-wrapper',
            html: '<div class="obra-marker" style="background-color:' + definition.color + '"><i class="' + definition.icon + '"></i></div>',
            iconSize: [38, 38],
            iconAnchor: [19, 38],
            popupAnchor: [0, -38]
        });
    }

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>'
    }).addTo(map);

    const bounds = [];
    obras.forEach(function (obra) {
        const point = [obra.latitud, obra.longitud];
        const markerOptions = {
            title: obra.nombre,
            alt: 'Ubicación de ' + obra.nombre
        };

        if (obra.icono_url) {
            markerOptions.icon = L.icon({
                iconUrl: obra.icono_url,
                iconSize: [38, 38],
                iconAnchor: [19, 38],
                popupAnchor: [0, -36],
                className: 'obra-map-icon'
            });
        } else {
            markerOptions.icon = createMarkerIcon(obra.icono_id);
        }

        const marker = L.marker(point, markerOptions).addTo(map);

        const popup = document.createElement('div');
        const name = document.createElement('div');
        name.className = 'obra-popup-name';
        name.textContent = obra.nombre;
        popup.appendChild(name);

        const details = [
            'Tipo: ' + getIconDefinition(obra.icono_id).label,
            obra.proyecto ? 'Proyecto: ' + obra.proyecto : '',
            obra.municipio ? 'Municipio: ' + obra.municipio : '',
            obra.localidad ? 'Localidad: ' + obra.localidad : '',
            obra.estatus ? 'Estatus: ' + obra.estatus : '',
            'Avance físico: ' + Number(obra.avance_fisico || 0).toFixed(2) + '%'
        ].filter(Boolean);
        if (details.length) {
            const detail = document.createElement('div');
            detail.className = 'obra-popup-detail';
            detail.textContent = details.join(' · ');
            popup.appendChild(detail);
        }
        marker.bindPopup(popup, { maxWidth: 340 });
        bounds.push(point);
    });

    if (bounds.length === 1) {
        map.setView(bounds[0], 16);
    } else if (bounds.length > 1) {
        map.fitBounds(bounds, { padding: [35, 35], maxZoom: 16 });
    }

    const usedIconIds = [...new Set(obras.map(function (obra) {
        return String(obra.icono_id || '33');
    }))];
    if (usedIconIds.length) {
        const legend = L.control({ position: 'bottomright' });
        legend.onAdd = function () {
            const container = L.DomUtil.create('div', 'obra-map-legend');
            const title = document.createElement('strong');
            title.textContent = 'Tipos de obra o acción';
            container.appendChild(title);

            usedIconIds.forEach(function (iconId) {
                const definition = getIconDefinition(iconId);
                const row = document.createElement('div');
                row.className = 'obra-map-legend-item';
                row.innerHTML = '<span class="obra-map-legend-symbol" style="background-color:' + definition.color + '"><i class="' + definition.icon + '"></i></span><span></span>';
                row.lastElementChild.textContent = definition.label;
                container.appendChild(row);
            });

            L.DomEvent.disableClickPropagation(container);
            return container;
        };
        legend.addTo(map);
    }

    L.control.scale({ imperial: false, position: 'bottomleft' }).addTo(map);
    window.setTimeout(function () { map.invalidateSize(); }, 250);
})();
</script>
