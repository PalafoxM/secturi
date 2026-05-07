<?php
$actividadesGuardadas = [];
if (!empty($actividades) && is_array($actividades)) {
    foreach ($actividades as $actividadItem) {
        $actividadTexto = is_object($actividadItem) ? ($actividadItem->actividad ?? '') : (is_array($actividadItem) ? ($actividadItem['actividad'] ?? '') : $actividadItem);
        $actividadTexto = trim((string) $actividadTexto);
        if ($actividadTexto !== '') {
            $actividadesGuardadas[] = $actividadTexto;
        }
    }
}
if (empty($actividadesGuardadas) && isset($solicitud)) {
    foreach ((array) $solicitud as $key => $value) {
        if (strpos($key, 'actividad_') === 0 && trim((string) $value) !== '') {
            $actividadesGuardadas[] = $value;
        }
    }
}
if (empty($actividadesGuardadas)) {
    $actividadesGuardadas = [''];
}

$catalogoFirmantesHonorarios = json_encode($catalogo_firmantes ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$firmasSeleccionadasHonorarios = json_encode(array_values($firmas_seleccionadas ?? []), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$soportes = [
    'Autorizacion SFIA' => 'autorizacion_sfia',
    'Identificacion Oficial' => 'justificacion_oficial',
    'Cedula de Registro Federal de Contribuyentes' => 'cedula_rfc',
    'Comprobante de domicilio' => 'comprobante_domicilio',
    'Autorizacion de tratamiento datos' => 'autorizacion_datos',
];
?>

<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Jur&iacute;dico</a></li>
                                <li class="breadcrumb-item active">Solicitud Honorarios</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Solicitud de Elaboraci&oacute;n de Contrato por Honorarios</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="form_solicitud_honorarios" enctype="multipart/form-data">
                                <input type="hidden" name="id_solicitud_honorario" value="<?= isset($solicitud) ? ($solicitud->id_solicitud_honorario ?? $solicitud->id_solicitud_honorarios ?? '') : '' ?>">

                                <div class="table-responsive honorarios-sheet-wrap">
                                    <table class="table table-bordered honorarios-table mb-0">
                                        <tr>
                                            <td colspan="3" class="sheet-title">
                                                <div>SOLICITUD DE ELABORACI&Oacute;N DE CONTRATO DE PRESTACI&Oacute;N DE SERVICIOS</div>
                                                <div>PERSONALES POR HONORARIOS ASIMILADOS A SALARIOS</div>
                                                <div class="sheet-subtitle">DIRECCI&Oacute;N GENERAL JUR&Iacute;DICA</div>
                                                <div class="sheet-code">DGJ-4</div>
                                            </td>
                                        </tr>

                                        <tr><td colspan="3" class="section-title">INFORMACI&Oacute;N DEL CONTRATO</td></tr>
                                        <tr>
                                            <td class="label-cell">Responsable del Proyecto</td>
                                            <td colspan="2" class="field-cell">
                                                <select class="form-control form-control-sm select2 field-input" id="responsable_proyecto" name="responsable_proyecto">
                                                    <option value="">Seleccione una opci&oacute;n</option>
                                                    <?php foreach ($usuario as $u): ?>
                                                        <option
                                                            value="<?= $u->id_usuario ?>"
                                                            data-area="<?= esc($u->dsc_area ?? '', 'attr') ?>"
                                                            <?= (isset($solicitud) && isset($solicitud->responsable_proyecto) && $solicitud->responsable_proyecto == $u->id_usuario) ? 'selected' : '' ?>
                                                        >
                                                            <?= esc($u->nombre_completo . ' - ' . ($u->dsc_puesto ?? '')) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="label-cell">&Aacute;rea</td>
                                            <td colspan="2" class="field-cell">
                                                <select class="form-control form-control-sm select2 field-input" id="area" name="area">
                                                    <option value="">Seleccione una opci&oacute;n</option>
                                                    <?php foreach (($cat_area ?? []) as $areaItem): ?>
                                                        <option value="<?= $areaItem->id_area ?>" <?= (isset($solicitud->area) && (string) $solicitud->area === (string) $areaItem->id_area) ? 'selected' : '' ?>>
                                                            <?= esc($areaItem->dsc_area ?? '') ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="label-cell">Informes a rendir</td>
                                            <td colspan="2" class="field-cell">
                                                <input type="text" class="form-control form-control-sm field-input" name="informes_rendir" value="<?= isset($solicitud->informes_rendir) ? esc($solicitud->informes_rendir) : '' ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="label-cell">Vigencia</td>
                                            <td colspan="2" class="field-cell">
                                                <div class="inline-fields">
                                                    <input type="date" class="form-control form-control-sm field-input" name="vigencia_inicio" value="<?= isset($solicitud->vigencia_inicio) ? esc($solicitud->vigencia_inicio) : '' ?>">
                                                    <span class="inline-separator">al</span>
                                                    <input type="date" class="form-control form-control-sm field-input" name="vigencia_fin" value="<?= isset($solicitud->vigencia_fin) ? esc($solicitud->vigencia_fin) : '' ?>">
                                                </div>
                                            </td>
                                        </tr>

                                        <tr><td colspan="3" class="section-title">ACTIVIDADES A REALIZAR</td></tr>
                                        <tr class="activity-header">
                                            <td class="label-cell text-center" style="width: 9%;">No.</td>
                                            <td colspan="2" class="label-cell text-center">Actividad</td>
                                        </tr>
                                        <tbody id="actividades_body">
                                        <?php foreach ($actividadesGuardadas as $index => $actividad): ?>
                                            <tr>
                                                <td class="field-cell text-center activity-index"><?= $index + 1 ?></td>
                                                <td colspan="2" class="field-cell activity-cell">
                                                    <textarea class="form-control form-control-sm field-input activity-input" name="actividades[]"><?= esc($actividad) ?></textarea>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                        <tr>
                                            <td colspan="3" class="text-right">
                                                <button type="button" class="btn btn-secondary btn-sm" id="agregar_actividad">+ Agregar actividad</button>
                                            </td>
                                        </tr>

                                        <tr><td colspan="3" class="section-title">INFORMACI&Oacute;N PRESUPUESTAL</td></tr>
                                        <tr class="activity-header">
                                            <td class="label-cell text-center">Clave presupuestal</td>
                                            <td class="label-cell text-center">N&uacute;mero y nombre de la Partida</td>
                                            <td class="label-cell text-center">Monto total del Contrato</td>
                                        </tr>
                                        <tr>
                                            <td class="field-cell">
                                                <select class="form-control form-control-sm select2 field-input" name="clave_presupuestal">
                                                    <option value="">Seleccione una opci&oacute;n</option>
                                                    <?php foreach (($cat_proyecto ?? []) as $proyecto): ?>
                                                        <option value="<?= $proyecto->id_proyecto ?>" <?= (isset($solicitud->clave_presupuestal) && (string) $solicitud->clave_presupuestal === (string) $proyecto->id_proyecto) ? 'selected' : '' ?>>
                                                            <?= esc($proyecto->proyecto ?? '') ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td class="field-cell">
                                                <select class="form-control form-control-sm select2 field-input" name="partida">
                                                    <option value="">Seleccione una opci&oacute;n</option>
                                                    <?php foreach ($cat_partida as $p): ?>
                                                        <option value="<?= $p->id_partida ?>" <?= (isset($solicitud) && isset($solicitud->partida) && $solicitud->partida == $p->id_partida) ? 'selected' : '' ?>>
                                                            <?= esc(($p->cuenta_cable ?? '') . ' - ' . ($p->partida ?? $p->nombre_fondo ?? '')) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td class="field-cell">
                                                <input type="number" step="0.01" class="form-control form-control-sm field-input" id="monto_total_contrato" name="monto_total_contrato" value="<?= isset($solicitud->monto_total_contrato) ? esc($solicitud->monto_total_contrato) : '' ?>">
                                            </td>
                                        </tr>

                                        <tr><td colspan="3" class="section-title">INFORMACI&Oacute;N DEL PRESTADOR DE SERVICIOS PERSONALES POR HONORARIOS</td></tr>
                                        <tr>
                                            <td class="label-cell">Nombre Completo Prestaci&oacute;n de Servicios</td>
                                            <td colspan="2" class="field-cell">
                                                <input type="text" class="form-control form-control-sm field-input" name="nombre_prestador" value="<?= isset($solicitud->nombre_prestador) ? esc($solicitud->nombre_prestador) : '' ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="label-cell">Prestaci&oacute;n de Servicios</td>
                                            <td colspan="2" class="field-cell">
                                                <?php
                                                    $puestoPrestadorSeleccionado = '';
                                                    foreach (['prestacion_servicios', 'puesto_prestador', 'id_puesto'] as $campoPuestoPrestador) {
                                                        if (isset($solicitud) && isset($solicitud->{$campoPuestoPrestador}) && $solicitud->{$campoPuestoPrestador} !== null && $solicitud->{$campoPuestoPrestador} !== '') {
                                                            $puestoPrestadorSeleccionado = (string) $solicitud->{$campoPuestoPrestador};
                                                            break;
                                                        }
                                                    }
                                                ?>
                                                <select class="form-control form-control-sm select2 field-input" name="prestacion_servicios">
                                                    <option value="">Seleccione una opci&oacute;n</option>
                                                    <?php foreach (($cat_puesto ?? []) as $puesto): ?>
                                                        <option value="<?= $puesto->id_puesto ?>" <?= $puestoPrestadorSeleccionado !== '' && $puestoPrestadorSeleccionado === (string) $puesto->id_puesto ? 'selected' : '' ?>>
                                                            <?= esc($puesto->dsc_puesto ?? '') ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="label-cell">RFC</td>
                                            <td colspan="2" class="field-cell">
                                                <input type="text" class="form-control form-control-sm field-input" name="rfc_prestador" value="<?= isset($solicitud->rfc_prestador) ? esc($solicitud->rfc_prestador) : '' ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="label-cell">Domicilio</td>
                                            <td colspan="2" class="field-cell">
                                                <input type="text" class="form-control form-control-sm field-input" name="domicilio_prestador" value="<?= isset($solicitud->domicilio_prestador) ? esc($solicitud->domicilio_prestador) : '' ?>">
                                            </td>
                                        </tr>

                                        <tr><td colspan="3" class="section-title">SOPORTE DOCUMENTAL</td></tr>
                                        <?php $chunked = array_chunk($soportes, 3, true); ?>
                                        <?php foreach ($chunked as $row): ?>
                                            <tr>
                                                <?php foreach ($row as $label => $name): ?>
                                                    <td class="support-cell">
                                                        <label class="support-option">
                                                            <input type="checkbox" name="<?= $name ?>" value="1" <?= (!empty($solicitud) && !empty($solicitud->$name)) ? 'checked' : '' ?>>
                                                            <span><?= esc($label) ?></span>
                                                        </label>
                                                    </td>
                                                <?php endforeach; ?>
                                                <?php for ($fill = count($row); $fill < 3; $fill++): ?>
                                                    <td class="support-cell"></td>
                                                <?php endfor; ?>
                                            </tr>
                                        <?php endforeach; ?>

                                    </table>
                                </div>

                                <h5 class="bg-primary text-white p-2 mt-4">FIRMAS</h5>
                                <div class="card border mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <p class="mb-0 text-muted">Agrega hasta 3 firmas para el formato de honorarios.</p>
                                            <button type="button" class="btn btn-primary btn-sm" id="btnAgregarFirmaHonorarios">
                                                <i class="fas fa-plus"></i> Agregar firma
                                            </button>
                                        </div>
                                        <div id="contenedor_firmas_honorarios"></div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="mdi mdi-content-save"></i> Guardar Solicitud Honorarios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .honorarios-sheet-wrap {
        width: 100%;
        background: #fff;
        overflow-x: auto;
    }

    .honorarios-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
        table-layout: fixed;
        margin-bottom: 0;
    }

    .honorarios-table td {
        border: 1px solid #000;
        padding: 4px 6px;
        vertical-align: middle;
        font-size: 12px;
        line-height: 1.1;
    }

    .sheet-title,
    .section-title {
        background: #1f4e79;
        color: #fff;
        text-align: center;
        font-weight: 700;
    }

    .sheet-title {
        padding: 14px 8px;
        font-size: 16px;
        line-height: 1.3;
    }

    .sheet-subtitle,
    .sheet-code {
        font-size: 13px;
        margin-top: 3px;
    }

    .section-title {
        font-size: 14px;
        padding: 3px 8px;
    }

    .label-cell {
        font-weight: 700;
        text-align: center;
        background: #fafafa;
    }

    .field-cell {
        background: #fff;
    }

    .field-input {
        border: 0;
        border-radius: 0;
        box-shadow: none !important;
        padding: 4px 6px;
        min-height: 30px;
        font-size: 12px;
        width: 100%;
        background: transparent;
    }

    .field-input:focus {
        border: 0;
        background: #f6fbff;
    }

    .select2-container--default .select2-selection--single {
        border: 0 !important;
        border-radius: 0 !important;
        min-height: 30px;
        background: transparent !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: 30px;
        font-size: 12px;
        padding-left: 6px;
    }

    .select2-container .select2-selection--single .select2-selection__arrow {
        height: 30px;
    }

    .inline-fields {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .inline-separator {
        font-size: 12px;
        font-weight: 700;
    }

    .activity-cell {
        padding: 0;
    }

    .activity-row-wrap {
        display: flex;
        align-items: stretch;
        gap: 8px;
        padding: 4px;
    }

    .activity-input {
        resize: none;
        height: 72px;
        flex: 1;
    }

    .remove-activity {
        align-self: center;
        white-space: nowrap;
    }

    .activity-index {
        font-weight: 700;
        background: #fafafa;
    }

    .activity-header td {
        background: #f0f0f0;
    }

    .support-cell {
        padding: 8px 6px;
        min-height: 42px;
    }

    .support-option {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 6px;
        margin: 0;
        font-size: 12px;
        text-align: left;
    }

    .validation-cell {
        position: relative;
        height: 180px;
        padding: 0;
    }

    .validation-grid {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(to right, rgba(31, 78, 121, 0.12) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(31, 78, 121, 0.12) 1px, transparent 1px);
        background-size: 16px 16px;
    }

    .signature-block {
        position: absolute;
        left: 50%;
        bottom: 12px;
        transform: translateX(-50%);
        width: 280px;
        text-align: center;
        z-index: 1;
    }

    .signature-line {
        border-top: 1px solid #000;
        margin-bottom: 4px;
    }

    .signature-name,
    .signature-role {
        font-weight: 700;
        font-size: 12px;
        line-height: 1.2;
    }

    @media (max-width: 991px) {
        .honorarios-table {
            min-width: 760px;
        }
    }
</style>

<link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery-ui.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.slimscroll.min.js"></script>
<script src="<?= base_url() ?>plugins/select2/select2.min.js"></script>

<script>
    const catalogoFirmantesHonorarios = <?= $catalogoFirmantesHonorarios ?>;
    const firmasSeleccionadasHonorarios = <?= $firmasSeleccionadasHonorarios ?>;

    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });

        function opcionesFirmantesHonorarios(valorSeleccionado = '') {
            let html = '<option value="">Seleccione un usuario</option>';
            catalogoFirmantesHonorarios.forEach(usuario => {
                const seleccionado = String(usuario.id_usuario) === String(valorSeleccionado) ? 'selected' : '';
                const puesto = usuario.dsc_puesto ? String(usuario.dsc_puesto).replace(/"/g, '&quot;') : '';
                html += `<option value="${usuario.id_usuario}" data-puesto="${puesto}" ${seleccionado}>${usuario.nombre_completo}</option>`;
            });
            return html;
        }

        function actualizarPuestoFirmaHonorarios(select) {
            const puesto = $(select).find(':selected').data('puesto') || '';
            $(select).closest('.firma-item').find('.firma-puesto').text(puesto);
        }

        function reindexarFirmasHonorarios() {
            $('#contenedor_firmas_honorarios .firma-item').each(function(index) {
                $(this).attr('data-index', index);
                $(this).find('.firma-label').text(`Firma ${index + 1}`);
                $(this).find('select').attr('name', `firmas[${index}]`);
            });
        }

        function agregarFirmaHonorarios(valorSeleccionado = '') {
            const contenedor = $('#contenedor_firmas_honorarios');
            if (contenedor.find('.firma-item').length >= 3) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Limite de firmas',
                    text: 'Solo puedes agregar hasta 3 firmas.'
                });
                return;
            }

            const index = contenedor.find('.firma-item').length;
            const html = `
                <div class="firma-item border rounded p-3 mb-3" data-index="${index}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong class="firma-label">Firma ${index + 1}</strong>
                        <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar-firma-honorarios">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <select class="form-control select2 firma-select-honorarios" name="firmas[${index}]">
                        ${opcionesFirmantesHonorarios(valorSeleccionado)}
                    </select>
                    <div class="firma-puesto text-uppercase small text-muted mt-2"></div>
                </div>
            `;

            contenedor.append(html);
            const nuevoItem = contenedor.find('.firma-item').last();
            nuevoItem.find('.firma-select-honorarios').select2({ width: '100%' });
            actualizarPuestoFirmaHonorarios(nuevoItem.find('.firma-select-honorarios'));
        }

        if (firmasSeleccionadasHonorarios.length > 0) {
            firmasSeleccionadasHonorarios.forEach(firma => agregarFirmaHonorarios(firma));
        } else {
            agregarFirmaHonorarios();
        }

        $('#btnAgregarFirmaHonorarios').on('click', function() {
            agregarFirmaHonorarios();
        });

        $(document).on('change', '.firma-select-honorarios', function() {
            actualizarPuestoFirmaHonorarios(this);
        });

        $(document).on('click', '.btn-eliminar-firma-honorarios', function() {
            const item = $(this).closest('.firma-item');
            const select = item.find('.firma-select-honorarios');
            if (select.hasClass('select2-hidden-accessible')) {
                select.select2('destroy');
            }
            item.remove();
            reindexarFirmasHonorarios();
        });

        function renumerarActividades() {
            $('#actividades_body tr').each(function(index) {
                $(this).find('.activity-index').text(index + 1);
            });
        }

        function agregarFilaActividad(valor) {
            var contenido = valor || '';
            var fila = `
                <tr>
                    <td class="field-cell text-center activity-index"></td>
                    <td colspan="2" class="field-cell activity-cell">
                        <div class="activity-row-wrap">
                            <textarea class="form-control form-control-sm field-input activity-input" name="actividades[]">${contenido}</textarea>
                            <button type="button" class="btn btn-danger btn-sm remove-activity">Quitar</button>
                        </div>
                    </td>
                </tr>
            `;
            $('#actividades_body').append(fila);
            renumerarActividades();
        }

        $('#agregar_actividad').on('click', function() {
            agregarFilaActividad('');
        });

        $(document).on('click', '.remove-activity', function() {
            if ($('#actividades_body tr').length === 1) {
                $(this).closest('tr').find('textarea').val('');
                return;
            }
            $(this).closest('tr').remove();
            renumerarActividades();
        });

        $('#actividades_body tr').each(function(index) {
            if (index > 0) {
                $(this).find('.activity-cell').wrapInner('<div class="activity-row-wrap"></div>');
                $(this).find('.activity-row-wrap').append('<button type="button" class="btn btn-danger btn-sm remove-activity">Quitar</button>');
            }
        });
        renumerarActividades();

        $('#form_solicitud_honorarios').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var btnSubmit = $(this).find('button[type="submit"]');

            btnSubmit.prop('disabled', true);
            btnSubmit.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            $.ajax({
                url: '<?= base_url("index.php/Principal/guardarSolicitudHonorarios") ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(data) {
                    if (!data.error) {
                        $('input[name="id_solicitud_honorario"]').val(data.id_solicitud_honorario || '');
                        Swal.fire({
                            icon: 'success',
                            title: 'Solicitud guardada correctamente',
                            showConfirmButton: false,
                            timer: 1200
                        }).then(() => {
                            window.location.href = data.url_listado || '<?= base_url("index.php/Principal/listadoHonorarios") ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error: ' + data.respuesta,
                            showConfirmButton: true
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrio un error al procesar la solicitud.',
                        showConfirmButton: true
                    });
                },
                complete: function() {
                    btnSubmit.prop('disabled', false);
                    btnSubmit.html('<i class="mdi mdi-content-save"></i> Guardar Solicitud Honorarios');
                }
            });
        });
    });
</script>
