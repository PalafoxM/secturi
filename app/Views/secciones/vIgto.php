<?php
$projects = $proyecto ?? [];
$requiredMark = '<span class="text-danger" aria-hidden="true">*</span>';

$textInput = static function (string $name, string $label, int $maxLength, bool $required = true, string $type = 'text', string $placeholder = '') use ($requiredMark): void {
    $requiredAttribute = $required ? ' required' : '';
    $mark = $required ? ' ' . $requiredMark : '';
    echo '<div class="form-group col-md-4">';
    echo '<label for="' . esc($name) . '">' . esc($label) . $mark . '</label>';
    echo '<input class="form-control" type="' . esc($type) . '" id="' . esc($name) . '" name="' . esc($name) . '" maxlength="' . $maxLength . '" placeholder="' . esc($placeholder) . '"' . $requiredAttribute . '>';
    echo '</div>';
};

$numberInput = static function (string $name, string $label, string $step = '1', string $min = '0', string $max = '', bool $required = true) use ($requiredMark): void {
    $requiredAttribute = $required ? ' required' : '';
    $maxAttribute = $max !== '' ? ' max="' . esc($max) . '"' : '';
    $valueAttribute = $required ? ' value="0"' : '';
    $mark = $required ? ' ' . $requiredMark : '';
    echo '<div class="form-group col-md-4">';
    echo '<label for="' . esc($name) . '">' . esc($label) . $mark . '</label>';
    echo '<input class="form-control" type="number" id="' . esc($name) . '" name="' . esc($name) . '"' . $valueAttribute . ' step="' . esc($step) . '" min="' . esc($min) . '"' . $maxAttribute . $requiredAttribute . '>';
    echo '</div>';
};

$textarea = static function (string $name, string $label, int $maxLength, bool $required = true, int $rows = 3) use ($requiredMark): void {
    $requiredAttribute = $required ? ' required' : '';
    $mark = $required ? ' ' . $requiredMark : '';
    echo '<div class="form-group col-12">';
    echo '<label for="' . esc($name) . '">' . esc($label) . $mark . '</label>';
    echo '<textarea class="form-control" id="' . esc($name) . '" name="' . esc($name) . '" maxlength="' . $maxLength . '" rows="' . $rows . '"' . $requiredAttribute . '></textarea>';
    echo '<small class="form-text text-muted text-right"><span class="igto-counter">0</span>/' . $maxLength . '</small>';
    echo '</div>';
};
?>

<link rel="stylesheet" href="<?= base_url('plugins/leaflet/leaflet.css') ?>">

<style>
    .igto-section { border: 1px solid #e6eaf0; border-radius: .45rem; margin-bottom: 1.25rem; overflow: hidden; }
    .igto-section-title { background: #f5f7fa; border-bottom: 1px solid #e6eaf0; padding: .85rem 1rem; }
    .igto-section-title h5 { margin: 0; color: #263b5e; font-size: 1rem; }
    .igto-section-body { padding: 1rem; }
    #igtoMap { height: 480px; min-height: 350px; border-radius: .35rem; border: 1px solid #d8dee8; z-index: 1; }
    .igto-map-help { background: #eef6ff; border-left: 4px solid #1761fd; padding: .75rem; border-radius: .25rem; }
    .igto-total { background-color: #eefaf4 !important; font-weight: 600; }
    .igto-actions { position: sticky; bottom: 0; z-index: 900; background: rgba(255,255,255,.96); border-top: 1px solid #e6eaf0; padding: .8rem 1rem; box-shadow: 0 -3px 12px rgba(31,45,61,.08); }
    .igto-required-note { font-size: .82rem; }
    @media (max-width: 767.98px) { #igtoMap { height: 360px; } .igto-actions { position: static; } }
</style>

<div class="page-content-tab">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="float-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('index.php/Inicio') ?>">Inicio</a></li>
                            <li class="breadcrumb-item">IGTO</li>
                            <li class="breadcrumb-item active">Nueva obra o acción</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Registro de obras y acciones</h4>
                </div>
            </div>
        </div>

        <form id="formIgto" autocomplete="off">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mt-0 mb-1">Información de la obra o acción</h4>
                            <p class="text-muted mb-0">Capture los datos solicitados y seleccione la ubicación exacta en el mapa.</p>
                        </div>
                        <span class="igto-required-note text-muted"><span class="text-danger">*</span> Campos obligatorios</span>
                    </div>

                    <section class="igto-section">
                        <div class="igto-section-title"><h5><i class="fas fa-file-alt mr-2"></i>Identificación</h5></div>
                        <div class="igto-section-body">
                            <div class="form-row">
                                <?php $textInput('folio_obra_accion', 'Folio de obra/acción', 25, true, 'text', 'Ej. OBRA-2026-001'); ?>
                                <?php $numberInput('ejercicio', 'Ejercicio', '1', '2000', '2100'); ?>
                                <div class="form-group col-md-4">
                                    <label for="proyecto_de_inversion">Proyecto de inversión <?= $requiredMark ?></label>
                                    <select class="form-control" id="proyecto_de_inversion" name="proyecto_de_inversion" required>
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($projects as $item): ?>
                                            <option value="<?= esc($item->id_cat_proyecto_q) ?>">
                                                <?= esc( $item->dsc_proyecto_q) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php $textInput('dependencia', 'Dependencia', 10); ?>
                                <?php $textInput('nombre_simplificado', 'Nombre simplificado', 255); ?>
                                <?php $textInput('categoria', 'Categoría', 30); ?>
                                <?php $textInput('subcategoria', 'Subcategoría', 80); ?>
                                <div class="form-group col-md-4">
                                    <label for="fecha_entrega">Fecha de entrega <?= $requiredMark ?></label>
                                    <input class="form-control" type="date" id="fecha_entrega" name="fecha_entrega" required>
                                </div>
                                <?php $textarea('nombre_obra_accion', 'Nombre de la obra o acción', 500); ?>
                                <?php $textarea('meta_sed', 'Meta SED', 500); ?>
                            </div>
                        </div>
                    </section>

                    <section class="igto-section">
                        <div class="igto-section-title"><h5><i class="fas fa-users mr-2"></i>Empleos</h5></div>
                        <div class="igto-section-body">
                            <div class="form-row">
                                <?php
                                foreach ([
                                    'empleos_permanentes_mujeres' => 'Permanentes — mujeres',
                                    'empleos_eventuales_mujeres' => 'Eventuales — mujeres',
                                    'empleos_protegidos_mujeres' => 'Protegidos — mujeres',
                                    'empleos_permanentes_hombres' => 'Permanentes — hombres',
                                    'empleos_eventuales_hombres' => 'Eventuales — hombres',
                                    'empleos_protegidos_hombres' => 'Protegidos — hombres',
                                ] as $name => $label) {
                                    $numberInput($name, $label);
                                }
                                ?>
                            </div>
                        </div>
                    </section>

                    <section class="igto-section">
                        <div class="igto-section-title"><h5><i class="fas fa-map-marker-alt mr-2"></i>Ubicación geográfica</h5></div>
                        <div class="igto-section-body">
                            <div class="igto-map-help mb-3">
                                <strong>Coloque el marcador:</strong> haga clic sobre el mapa o arrastre el marcador. El sistema completará las coordenadas y los datos de domicilio disponibles; podrá corregirlos manualmente.
                            </div>
                            <div class="row">
                                <div class="col-lg-7 mb-3 mb-lg-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small id="igtoMapStatus" class="text-muted" role="status">Seleccione un punto en el mapa.</small>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnCurrentLocation">
                                            <i class="fas fa-crosshairs mr-1"></i>Mi ubicación
                                        </button>
                                    </div>
                                    <div id="igtoMap" aria-label="Mapa para seleccionar la ubicación de la obra"></div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-row">
                                        <?php $numberInput('latitud', 'Latitud', '0.0000001', '-90', '90'); ?>
                                        <?php $numberInput('longitud', 'Longitud', '0.0000001', '-180', '180'); ?>
                                        <?php $textInput('estado', 'Estado', 10); ?>
                                        <?php $textInput('municipio', 'Municipio', 50); ?>
                                        <?php $textInput('localidad', 'Localidad', 80); ?>
                                        <?php $textInput('tipo_asentamiento', 'Tipo de asentamiento', 10); ?>
                                        <?php $textInput('nombre_asentamiento', 'Nombre del asentamiento', 30, false); ?>
                                        <?php $textInput('tipo_vialidad', 'Tipo de vialidad', 10, false); ?>
                                        <?php $textInput('nombre_vialidad', 'Nombre de vialidad', 20, false); ?>
                                        <?php $numberInput('numero_exterior', 'Número exterior', '1', '0', '', false); ?>
                                        <?php $numberInput('numero_exterior_2', 'Número exterior 2', '1', '0', '', false); ?>
                                        <?php $textInput('codigo_postal', 'Código postal', 10, false); ?>
                                        <?php $textInput('zona_impulso', 'Zona impulso', 30, false); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="igto-section">
                        <div class="igto-section-title"><h5><i class="fas fa-coins mr-2"></i>Montos modificados</h5></div>
                        <div class="igto-section-body">
                            <div class="form-row">
                                <?php
                                foreach ([
                                    'monto_modificado_estatal' => 'Estatal',
                                    'monto_modificado_deuda' => 'Deuda',
                                    'monto_modificado_recurso_propio' => 'Recurso propio',
                                    'monto_modificado_federal' => 'Federal',
                                    'monto_modificado_municipal' => 'Municipal',
                                    'monto_modificado_otro' => 'Otro',
                                    'monto_modificado_beneficiario' => 'Beneficiario',
                                ] as $name => $label) {
                                    $numberInput($name, 'Monto ' . $label, '0.01');
                                }
                                ?>
                                <div class="form-group col-md-4">
                                    <label for="monto_total_modificado">Total modificado</label>
                                    <input class="form-control igto-total" type="number" id="monto_total_modificado" value="0.00" step="0.01" readonly>
                                </div>
                                <?php $numberInput('monto_total_modificado_sfia', 'Total modificado SFIA', '0.01'); ?>
                            </div>
                        </div>
                    </section>

                    <section class="igto-section">
                        <div class="igto-section-title"><h5><i class="fas fa-hand-holding-usd mr-2"></i>Montos pagados más devengados</h5></div>
                        <div class="igto-section-body">
                            <div class="form-row">
                                <?php
                                foreach ([
                                    'monto_pagado_mas_devengado_estatal' => 'Estatal',
                                    'monto_pagado_mas_devengado_deuda' => 'Deuda',
                                    'monto_pagado_mas_devengado_recurso_propios' => 'Recursos propios',
                                    'monto_pagado_mas_devengado_federal' => 'Federal',
                                    'monto_pagado_mas_devengado_municipal' => 'Municipal',
                                    'monto_pagado_mas_devengado_otro' => 'Otro',
                                    'monto_pagado_mas_devengado_beneficiario' => 'Beneficiario',
                                ] as $name => $label) {
                                    $numberInput($name, 'Pagado/devengado ' . $label, '0.01');
                                }
                                ?>
                                <div class="form-group col-md-4">
                                    <label for="monto_total_pagado_mas_devengado">Total pagado más devengado</label>
                                    <input class="form-control igto-total" type="number" id="monto_total_pagado_mas_devengado" value="0.00" step="0.01" readonly>
                                </div>
                                <?php $numberInput('monto_total_pagado_mas_devengado_sfia', 'Total pagado más devengado SFIA', '0.01'); ?>
                            </div>
                        </div>
                    </section>

                    <section class="igto-section">
                        <div class="igto-section-title"><h5><i class="fas fa-tasks mr-2"></i>Clasificación, estatus y beneficiarios</h5></div>
                        <div class="igto-section-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="es_refrendo">¿Es refrendo? <?= $requiredMark ?></label>
                                    <select class="form-control" id="es_refrendo" name="es_refrendo" required>
                                        <option value="0">No</option><option value="1">Sí</option>
                                    </select>
                                </div>
                                <?php $numberInput('ano_de_refrendo', 'Año de refrendo', '1', '0', '2100'); ?>
                                <?php $textInput('tipo_obraaccion', 'Tipo de obra/acción', 10); ?>
                                <?php $textInput('tipo', 'Tipo', 20); ?>
                                <?php $textInput('estatus', 'Estatus', 20); ?>
                                <?php $textInput('estatus_avance', 'Estatus de avance', 30); ?>
                                <?php $textInput('observaciones_estatus_avance', 'Observación de estatus', 10, false); ?>
                                <?php $textInput('cct', 'CCT', 20, false); ?>
                                <?php $numberInput('informe_de_gobierno', 'Informe de gobierno'); ?>
                                <?php $textInput('situacion', 'Situación', 20); ?>
                                <?php $numberInput('beneficiarios_mujeres', 'Beneficiarias mujeres'); ?>
                                <?php $numberInput('beneficiarios_hombres', 'Beneficiarios hombres'); ?>
                                <div class="form-group col-md-4">
                                    <label for="beneficiarios_totales">Beneficiarios totales <?= $requiredMark ?></label>
                                    <input class="form-control igto-total" type="number" id="beneficiarios_totales" name="beneficiarios_totales" value="0" min="0" step="1" required readonly>
                                </div>
                                <?php $textarea('observaciones_generales', 'Observaciones generales', 255, false); ?>
                            </div>
                        </div>
                    </section>

                    <section class="igto-section">
                        <div class="igto-section-title"><h5><i class="fas fa-chart-line mr-2"></i>Avances</h5></div>
                        <div class="igto-section-body">
                            <div class="form-row">
                                <?php $numberInput('avance_financiero', 'Avance financiero (%)', '0.01', '0', '100'); ?>
                                <?php $numberInput('avance_fisico', 'Avance físico (%)', '0.01', '0', '100'); ?>
                                <?php $numberInput('avance_financiero_sfia', 'Avance financiero SFIA', '0.01'); ?>
                                <?php $textarea('observaciones_avance_fisico', 'Observaciones del avance físico', 255, false); ?>
                            </div>
                        </div>
                    </section>

                    <section class="igto-section">
                        <div class="igto-section-title"><h5><i class="fas fa-project-diagram mr-2"></i>Alineación estratégica y control</h5></div>
                        <div class="igto-section-body">
                            <div class="form-row">
                                <?php $textInput('folio_relacionado', 'Folio relacionado', 25, false); ?>
                                <?php $textInput('eje_estrategico', 'Eje estratégico', 30); ?>
                                <?php $textInput('metas', 'Metas', 10, false); ?>
                                <?php $textInput('alineacion_programa_gobierno_y_sectorial', 'Alineación programa de gobierno/sectorial', 10, false); ?>
                                <?php $textInput('alineacion_agenda_transversal_y_programa_especial', 'Alineación agenda transversal/programa especial', 10, false); ?>
                                <?php $textInput('enfoque', 'Enfoque', 10, false); ?>
                                <?php $numberInput('cifras_estimadas', 'Cifras estimadas'); ?>
                                <div class="form-group col-md-4">
                                    <label for="activo">Activo <?= $requiredMark ?></label>
                                    <select class="form-control" id="activo" name="activo" required>
                                        <option value="SI">Sí</option><option value="NO">No</option>
                                    </select>
                                </div>
                                <?php $textarea('alineacion_otros_instrumentos', 'Alineación con otros instrumentos', 500, false); ?>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="igto-actions text-right">
                    <a class="btn btn-outline-secondary mr-2" href="<?= base_url('index.php/Principal/ListaIgto') ?>">
                        <i class="fas fa-list mr-1"></i>Ir a la lista
                    </a>
                    <button type="submit" class="btn btn-primary" id="btnGuardarIgto">
                        <i class="fas fa-save mr-1"></i>Guardar obra o acción
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<link href="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>plugins/select2/select2.min.js"></script>
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />


<script src="<?= base_url('plugins/leaflet/leaflet.js') ?>"></script>
<script>
(function () {
    'use strict';

    const modifiedFields = [
        'monto_modificado_estatal', 'monto_modificado_deuda',
        'monto_modificado_recurso_propio', 'monto_modificado_federal',
        'monto_modificado_municipal', 'monto_modificado_otro',
        'monto_modificado_beneficiario'
    ];
    const accruedFields = [
        'monto_pagado_mas_devengado_estatal', 'monto_pagado_mas_devengado_deuda',
        'monto_pagado_mas_devengado_recurso_propios', 'monto_pagado_mas_devengado_federal',
        'monto_pagado_mas_devengado_municipal', 'monto_pagado_mas_devengado_otro',
        'monto_pagado_mas_devengado_beneficiario'
    ];

    function calculateTotal(fields, destination) {
        const total = fields.reduce(function (sum, field) {
            return sum + (parseFloat(document.getElementById(field).value) || 0);
        }, 0);
        document.getElementById(destination).value = total.toFixed(2);
    }

    modifiedFields.forEach(function (field) {
        document.getElementById(field).addEventListener('input', function () {
            calculateTotal(modifiedFields, 'monto_total_modificado');
        });
    });
    accruedFields.forEach(function (field) {
        document.getElementById(field).addEventListener('input', function () {
            calculateTotal(accruedFields, 'monto_total_pagado_mas_devengado');
        });
    });
    ['beneficiarios_mujeres', 'beneficiarios_hombres'].forEach(function (field) {
        document.getElementById(field).addEventListener('input', function () {
            const women = parseInt(document.getElementById('beneficiarios_mujeres').value, 10) || 0;
            const men = parseInt(document.getElementById('beneficiarios_hombres').value, 10) || 0;
            document.getElementById('beneficiarios_totales').value = women + men;
        });
    });

    document.querySelectorAll('#formIgto textarea[maxlength]').forEach(function (element) {
        const counter = element.parentElement.querySelector('.igto-counter');
        element.addEventListener('input', function () { counter.textContent = element.value.length; });
    });

    const refrendo = document.getElementById('es_refrendo');
    const refrendoYear = document.getElementById('ano_de_refrendo');
    function toggleRefrendoYear() {
        const enabled = refrendo.value === '1';
        refrendoYear.readOnly = !enabled;
        if (!enabled) refrendoYear.value = '0';
        else if (parseInt(refrendoYear.value, 10) === 0) refrendoYear.value = new Date().getFullYear();
    }
    refrendo.addEventListener('change', toggleRefrendoYear);
    toggleRefrendoYear();

    const status = document.getElementById('igtoMapStatus');
    document.getElementById('latitud').value = '';
    document.getElementById('longitud').value = '';
    const map = L.map('igtoMap').setView([20.8756, -100.9867], 8);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>'
    }).addTo(map);

    let marker = null;
    let reverseTimer = null;
    let reverseController = null;

    function setValue(id, value) {
        if (value === undefined || value === null || value === '') return;
        const input = document.getElementById(id);
        if (!input) return;
        const maxLength = parseInt(input.getAttribute('maxlength'), 10);
        input.value = maxLength ? String(value).substring(0, maxLength) : value;
    }

    function reverseGeocode(lat, lng) {
        window.clearTimeout(reverseTimer);
        status.className = 'text-info';
        status.textContent = 'Consultando los datos de la ubicación…';
        reverseTimer = window.setTimeout(function () {
            if (reverseController) reverseController.abort();
            reverseController = new AbortController();
            const endpoint = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&addressdetails=1&accept-language=es&lat=' +
                encodeURIComponent(lat) + '&lon=' + encodeURIComponent(lng);
            fetch(endpoint, { signal: reverseController.signal, headers: { 'Accept': 'application/json' } })
                .then(function (response) {
                    if (!response.ok) throw new Error('No fue posible consultar la dirección.');
                    return response.json();
                })
                .then(function (result) {
                    const address = result.address || {};
                    const municipality = address.municipality || address.city || address.town || address.county;
                    const locality = address.city || address.town || address.village || address.hamlet || address.suburb || municipality;
                    const settlement = address.neighbourhood || address.suburb || address.quarter || address.residential;
                    setValue('estado', address.state);
                    setValue('municipio', municipality);
                    setValue('localidad', locality);
                    setValue('tipo_asentamiento', settlement ? 'Colonia' : 'Localidad');
                    setValue('nombre_asentamiento', settlement);
                    setValue('tipo_vialidad', address.road ? 'Calle' : 'Vialidad');
                    setValue('nombre_vialidad', address.road || address.pedestrian || address.path);
                    if (address.house_number && /^\d+$/.test(address.house_number)) setValue('numero_exterior', address.house_number);
                    setValue('codigo_postal', address.postcode);
                    status.className = 'text-success';
                    status.textContent = 'Ubicación precargada. Verifique y complete los datos antes de guardar.';
                })
                .catch(function (error) {
                    if (error.name === 'AbortError') return;
                    status.className = 'text-warning';
                    status.textContent = 'Se guardaron las coordenadas; capture o revise el domicilio manualmente.';
                });
        }, 1100);
    }

    function placeMarker(lat, lng, centerMap) {
        document.getElementById('latitud').value = Number(lat).toFixed(7);
        document.getElementById('longitud').value = Number(lng).toFixed(7);
        if (!marker) {
            marker = L.marker([lat, lng], { draggable: true, title: 'Ubicación de la obra' }).addTo(map);
            marker.on('dragend', function () {
                const point = marker.getLatLng();
                placeMarker(point.lat, point.lng, false);
            });
        } else {
            marker.setLatLng([lat, lng]);
        }
        if (centerMap) map.setView([lat, lng], Math.max(map.getZoom(), 16));
        marker.bindPopup('Ubicación seleccionada').openPopup();
        reverseGeocode(Number(lat).toFixed(7), Number(lng).toFixed(7));
    }

    map.on('click', function (event) { placeMarker(event.latlng.lat, event.latlng.lng, false); });
    document.getElementById('btnCurrentLocation').addEventListener('click', function () {
        if (!navigator.geolocation) {
            status.className = 'text-warning';
            status.textContent = 'El navegador no permite obtener la ubicación.';
            return;
        }
        status.className = 'text-info';
        status.textContent = 'Obteniendo su ubicación…';
        navigator.geolocation.getCurrentPosition(function (position) {
            placeMarker(position.coords.latitude, position.coords.longitude, true);
        }, function () {
            status.className = 'text-warning';
            status.textContent = 'No fue posible obtener la ubicación. Seleccione el punto en el mapa.';
        }, { enableHighAccuracy: true, timeout: 10000 });
    });

    ['latitud', 'longitud'].forEach(function (field) {
        document.getElementById(field).addEventListener('change', function () {
            const lat = parseFloat(document.getElementById('latitud').value);
            const lng = parseFloat(document.getElementById('longitud').value);
            if (Number.isFinite(lat) && Number.isFinite(lng)) placeMarker(lat, lng, true);
        });
    });

    document.getElementById('formIgto').addEventListener('submit', function (event) {
        event.preventDefault();
        const form = event.currentTarget;
        if (!form.checkValidity()) {
            form.reportValidity();
            const invalid = form.querySelector(':invalid');
            if (invalid) invalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        const button = document.getElementById('btnGuardarIgto');
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm mr-1"></span>Guardando…';
        $.ajax({
            url: '<?= base_url('index.php/Principal/guardarIgto') ?>',
            method: 'POST',
            data: $(form).serialize(),
            dataType: 'json'
        }).done(function (response) {
            if (response && response.error === false) {
                Swal.fire('Registro guardado', response.respuesta, 'success').then(function () {
                    window.location.href = '<?= base_url('index.php/Principal/ListaIgto') ?>';
                });
            } else {
                Swal.fire('No fue posible guardar', (response && response.respuesta) || 'Revise la información capturada.', 'error');
            }
        }).fail(function (xhr) {
            const response = xhr.responseJSON || {};
            Swal.fire('Error', response.respuesta || 'Ocurrió un error al guardar la obra o acción.', 'error');
        }).always(function () {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-save mr-1"></i>Guardar obra o acción';
        });
    });

    window.setTimeout(function () { map.invalidateSize(); }, 250);
})();
</script>
