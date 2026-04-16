<?php
    $reporte = $reporte ?? null;
    $actividades = $actividades ?? [];
    if (empty($actividades)) {
        $actividades = [(object)[
            'titulo_actividad' => '',
            'desglose_actividad' => ''
        ]];
    }
?>

<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h4 class="page-title mb-0">Reporte de Actividades por Honorarios</h4>
                            <div class="mt-2 mt-md-0">
                                <a href="<?= base_url('index.php/Inicio/FormHonorarios') ?>" class="btn btn-outline-primary btn-sm mr-1">
                                    <i class="mdi mdi-plus-box"></i> Nuevo reporte
                                </a>
                                <a href="<?= base_url('index.php/Inicio/ListaReporteHonorarios') ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="mdi mdi-format-list-bulleted"></i> Historial
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
                            <div class="alert alert-info mb-4">
                                Captura la informacion del reporte y usa <strong>Exportar PDF</strong> para generar una version lista para imprimir o guardar como PDF.
                            </div>

                            <?php if (!empty($reporte->id_reporte_honorarios)): ?>
                                <div class="alert alert-light border d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="mb-2 mb-md-0">
                                        Reporte #<?= esc($reporte->id_reporte_honorarios) ?> listo para consulta.
                                    </span>
                                    <a href="<?= base_url('index.php/Inicio/pdfreporteHonorario/' . intval($reporte->id_reporte_honorarios)) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="mdi mdi-open-in-new"></i> Ver PDF actual
                                    </a>
                                </div>
                            <?php endif; ?>

                            <form id="formReporteHonorarios" method="post" action="<?= base_url('index.php/Inicio/guardarReporteHonorarios') ?>" novalidate>
                                <input type="hidden" id="id_reporte_honorarios" name="id_reporte_honorarios" value="<?= esc($reporte->id_reporte_honorarios ?? '') ?>">
                                <div class="report-shell">
                                    <div class="report-header">
                                        <div>
                                            <h3>Informe Trimestral / Final de Actividades</h3>
                                            <p>Secretaria de Turismo e Identidad</p>
                                        </div>
                                        <div class="report-tag">Formato 2026</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="responsable_administrativo">Nombre y puesto del responsable administrativo</label>
                                                <input type="text" class="form-control" id="responsable_administrativo" name="responsable_administrativo" value="<?= esc($reporte->responsable_administrativo ?? '') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="area">Area</label>
                                                <input type="text" class="form-control" id="area" name="area" value="<?= esc($reporte->area ?? '') ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="numero_contrato">No. de contrato</label>
                                                <input type="text" class="form-control" id="numero_contrato" name="numero_contrato" value="<?= esc($reporte->numero_contrato ?? '') ?>" placeholder="SECTURI/CTO/___/20__" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="tipo_reporte">Reporte</label>
                                                <select class="form-control" id="tipo_reporte" name="tipo_reporte" required>
                                                    <option value="">Seleccione una opcion</option>
                                                    <option value="trimestral" <?= (($reporte->tipo_reporte ?? '') === 'trimestral') ? 'selected' : '' ?>>Trimestral</option>
                                                    <option value="final" <?= (($reporte->tipo_reporte ?? '') === 'final') ? 'selected' : '' ?>>Final</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="fecha_firma">Fecha del documento</label>
                                                <input type="date" class="form-control" id="fecha_firma" name="fecha_firma" value="<?= esc($reporte->fecha_firma ?? '') ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fecha_inicio">Del</label>
                                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= esc($reporte->fecha_inicio ?? '') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fecha_fin">Al</label>
                                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= esc($reporte->fecha_fin ?? '') ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="section-divider">

                                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                                        <div>
                                            <h5 class="mb-1">Actividades</h5>
                                            <p class="text-muted mb-0">Cada actividad incluye un titulo y un desglose en texto libre.</p>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2 mt-md-0" id="btnAgregarActividad">
                                            <i class="mdi mdi-plus"></i> Anadir actividad
                                        </button>
                                    </div>

                                    <div id="actividadesContainer">
                                        <?php foreach ($actividades as $index => $actividad): ?>
                                            <div class="actividad-item card border mb-3">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <h6 class="mb-0 actividad-label">Actividad <?= $index + 1 ?></h6>
                                                        <button type="button" class="btn btn-outline-danger btn-sm btnEliminarActividad">Quitar</button>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Titulo de la actividad</label>
                                                        <input type="text" class="form-control actividad-titulo" name="actividad_titulo[]" value="<?= esc($actividad->titulo_actividad ?? '') ?>" required>
                                                    </div>

                                                    <div class="form-group mb-0">
                                                        <label>Desglose de la actividad</label>
                                                        <textarea class="form-control actividad-desglose" name="actividad_desglose[]" rows="6" required placeholder="Escribe un desglose. Puedes separar puntos con saltos de linea."><?= esc($actividad->desglose_actividad ?? '') ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <hr class="section-divider">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nombre_prestador">Nombre completo del prestador de servicio</label>
                                                <input type="text" class="form-control" id="nombre_prestador" name="nombre_prestador" value="<?= esc($reporte->nombre_prestador ?? '') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="puesto_prestador">Puesto del prestador de servicio</label>
                                                <input type="text" class="form-control" id="puesto_prestador" name="puesto_prestador" value="<?= esc($reporte->puesto_prestador ?? '') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nombre_responsable_area">Nombre responsable de area</label>
                                                <input type="text" class="form-control" id="nombre_responsable_area" name="nombre_responsable_area" value="<?= esc($reporte->nombre_responsable_area ?? '') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="puesto_responsable_area">Puesto responsable de area</label>
                                                <input type="text" class="form-control" id="puesto_responsable_area" name="puesto_responsable_area" value="<?= esc($reporte->puesto_responsable_area ?? '') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-0">
                                                <label for="nombre_responsable">Nombre responsable de administrar y verificar el cumplimiento del contrato</label>
                                                <input type="text" class="form-control" id="nombre_responsable" name="nombre_responsable" value="<?= esc($reporte->nombre_responsable ?? '') ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right mt-4">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="mdi mdi-file-pdf-box"></i> Exportar PDF
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="actividadTemplate">
    <div class="actividad-item card border mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 actividad-label">Actividad 1</h6>
                <button type="button" class="btn btn-outline-danger btn-sm btnEliminarActividad">Quitar</button>
            </div>

            <div class="form-group">
                <label>Titulo de la actividad</label>
                <input type="text" class="form-control actividad-titulo" name="actividad_titulo[]" required>
            </div>

            <div class="form-group mb-0">
                <label>Desglose de la actividad</label>
                <textarea class="form-control actividad-desglose" name="actividad_desglose[]" rows="6" required placeholder="Escribe un desglose. Puedes separar puntos con saltos de linea."></textarea>
            </div>
        </div>
    </div>
</template>

<style>
    .report-shell {
        max-width: 1080px;
        margin: 0 auto;
        background: linear-gradient(180deg, #ffffff 0%, #f7f9fc 100%);
        border: 1px solid #dde4ef;
        border-radius: 18px;
        padding: 28px;
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        padding: 18px 22px;
        background: linear-gradient(135deg, #0c4a6e 0%, #155e75 45%, #e7f5f7 45%, #f8fafc 100%);
        border-radius: 16px;
        color: #0f172a;
    }

    .report-header h3 {
        margin: 0 0 4px;
        color: #ffffff;
        font-weight: 700;
    }

    .report-header p {
        margin: 0;
        color: #d7f3f7;
    }

    .report-tag {
        background: #ffffff;
        color: #0c4a6e;
        border-radius: 999px;
        padding: 8px 14px;
        font-weight: 700;
        white-space: nowrap;
    }

    .section-divider {
        border-top: 1px solid #dbe4ee;
        margin: 28px 0;
    }

    .actividad-item {
        border-radius: 14px;
        overflow: hidden;
    }

    .actividad-item .card-body {
        background: #ffffff;
    }

    .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.12);
    }

    @media (max-width: 767.98px) {
        .report-shell {
            padding: 18px;
            border-radius: 14px;
        }

        .report-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formReporteHonorarios');
        const actividadesContainer = document.getElementById('actividadesContainer');
        const template = document.getElementById('actividadTemplate');
        const btnAgregarActividad = document.getElementById('btnAgregarActividad');

        if (!form || !actividadesContainer || !template) {
            return;
        }

        function addActividad(titulo = '', desglose = '') {
            const wrapper = document.createElement('div');
            wrapper.innerHTML = template.innerHTML.trim();
            const item = wrapper.firstElementChild;

            item.querySelector('.actividad-titulo').value = titulo;
            item.querySelector('.actividad-desglose').value = desglose;
            actividadesContainer.appendChild(item);
            refreshActividades();
        }

        function refreshActividades() {
            const items = actividadesContainer.querySelectorAll('.actividad-item');
            items.forEach(function (item, index) {
                const label = item.querySelector('.actividad-label');
                if (label) label.textContent = 'Actividad ' + (index + 1);
            });

            const canRemove = items.length > 1;
            actividadesContainer.querySelectorAll('.btnEliminarActividad').forEach(function (btn) {
                btn.disabled = !canRemove;
            });
        }

        function resetActividadItems() {
            actividadesContainer.innerHTML = '';
            addActividad('', '');
        }

        function resetFormState() {
            form.reset();
            document.getElementById('id_reporte_honorarios').value = '';
            form.querySelectorAll('.is-invalid').forEach(function (field) {
                field.classList.remove('is-invalid');
            });
            resetActividadItems();
        }

        function clearInvalid(field) {
            field.classList.remove('is-invalid');
        }

        function validateForm() {
            let isValid = true;
            const fechaInicio = document.getElementById('fecha_inicio').value;
            const fechaFin = document.getElementById('fecha_fin').value;

            form.querySelectorAll('[required]').forEach(function (field) {
                const value = (field.value || '').toString().trim();
                const valid = value !== '';
                field.classList.toggle('is-invalid', !valid);
                if (!valid) isValid = false;
            });

            if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
                document.getElementById('fecha_inicio').classList.add('is-invalid');
                document.getElementById('fecha_fin').classList.add('is-invalid');
                isValid = false;
            }

            return isValid;
        }

        refreshActividades();

        btnAgregarActividad.addEventListener('click', function () {
            addActividad();
        });

        actividadesContainer.addEventListener('click', function (e) {
            const btn = e.target.closest('.btnEliminarActividad');
            if (!btn) return;

            const item = btn.closest('.actividad-item');
            if (item) item.remove();
            refreshActividades();
        });

        form.addEventListener('input', function (e) {
            if (e.target.classList.contains('form-control')) {
                clearInvalid(e.target);
            }
        });

        form.addEventListener('change', function (e) {
            if (e.target.classList.contains('form-control')) {
                clearInvalid(e.target);
            }
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!validateForm()) {
                Swal.fire('Campos requeridos', 'Completa todos los campos obligatorios y revisa el rango de fechas.', 'warning');
                return;
            }

            const printWindow = window.open('', '_blank');

            if (!printWindow) {
                Swal.fire('Ventana bloqueada', 'Permite ventanas emergentes para exportar el PDF.', 'error');
                return;
            }

            printWindow.document.open();
            printWindow.document.write('<html><head><title>Generando PDF...</title></head><body style="font-family:Arial,Helvetica,sans-serif;padding:24px;">Generando PDF, por favor espera...</body></html>');
            printWindow.document.close();

            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Guardando...';

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(async function (response) {
                const rawText = await response.text();
                let data = null;

                try {
                    data = JSON.parse(rawText);
                } catch (err) {
                    console.error('Respuesta no JSON:', rawText);
                    throw new Error(rawText);
                }

                return data;
            })
            .then(function (res) {
                console.log('guardarReporteHonorarios response:', res);

                if (!res || res.error) {
                    const mensaje = (res && res.respuesta) ? res.respuesta : 'No fue posible guardar el reporte.';
                    let detalle = '';

                    if (res && res.debug) {
                        detalle = '<pre style="text-align:left;max-height:220px;overflow:auto;">' + JSON.stringify(res.debug, null, 2) + '</pre>';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: mensaje,
                        html: detalle || undefined
                    });

                    if (printWindow) printWindow.close();
                    return;
                }

                if (res.id_reporte_honorarios) {
                    document.getElementById('id_reporte_honorarios').value = res.id_reporte_honorarios;
                }

                const pdfUrl = res.pdf_url || ('<?= base_url('index.php/Inicio/pdfreporteHonorario/') ?>' + res.id_reporte_honorarios);
                printWindow.location.replace(pdfUrl);
                printWindow.focus();
                resetFormState();

                Swal.fire({
                    icon: 'success',
                    title: 'Reporte generado',
                    text: 'Se abrio una nueva ventana con opciones para imprimir o descargar el PDF.',
                    timer: 1800,
                    showConfirmButton: false
                });
            })
            .catch(function (error) {
                console.error('guardarReporteHonorarios fetch error:', error);
                const detalle = '<pre style="text-align:left;max-height:220px;overflow:auto;">' + String(error.message || error) + '</pre>';

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrio un error al guardar el reporte. Revisa la respuesta del servidor.',
                    html: detalle
                });

                if (printWindow) printWindow.close();
            })
            .finally(function () {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    });
</script>
