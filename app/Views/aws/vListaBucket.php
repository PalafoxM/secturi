<?php
$filtros = $filtros ?? ['busqueda' => '', 'tipo_archivo' => ''];
$archivos = $archivos ?? [];
?>

<div class="page-content-tab">
    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col-md-10 offset-md-1">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Mis archivos en AWS S3</h5>
                        <a href="<?= base_url('index.php/Agregar/formBucketAws') ?>" class="btn btn-light btn-sm">Subir nuevo archivo</a>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="<?= base_url('index.php/Agregar/listaBucketAws') ?>" class="mb-4">
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label class="form-label font-weight-bold">Buscar por nombre o descripcion</label>
                                    <input
                                        type="text"
                                        name="busqueda"
                                        class="form-control"
                                        value="<?= esc($filtros['busqueda'] ?? '') ?>"
                                        placeholder="Ej. evidencia, contrato, video..."
                                    >
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label font-weight-bold">Tipo de archivo</label>
                                    <select name="tipo_archivo" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="IMG" <?= ($filtros['tipo_archivo'] ?? '') === 'IMG' ? 'selected' : '' ?>>IMG</option>
                                        <option value="VIDEO" <?= ($filtros['tipo_archivo'] ?? '') === 'VIDEO' ? 'selected' : '' ?>>VIDEO</option>
                                        <option value="ARCHIVO" <?= ($filtros['tipo_archivo'] ?? '') === 'ARCHIVO' ? 'selected' : '' ?>>ARCHIVO</option>
                                        <option value="AUDIO" <?= ($filtros['tipo_archivo'] ?? '') === 'AUDIO' ? 'selected' : '' ?>>AUDIO</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Buscar</button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>Tipo</th>
                                        <th>Ruta S3</th>
                                        <th class="text-center">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($archivos)): ?>
                                        <?php foreach ($archivos as $archivo): ?>
                                            <tr>
                                                <td><?= esc($archivo->nombre_archivo ?? '') ?></td>
                                                <td><?= esc($archivo->descripcion ?? '') ?></td>
                                                <td><?= esc($archivo->tipo_archivo ?? 'OTRO') ?></td>
                                                <td><small><?= esc($archivo->ruta_s3 ?? '') ?></small></td>
                                                <td class="text-center">
                                                    <?php if (!empty($archivo->url_descarga)): ?>
                                                        <a href="<?= esc($archivo->url_descarga) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Abrir</a>
                                                    <?php else: ?>
                                                        <span class="text-muted">Sin enlace</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No se encontraron archivos con esos filtros.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <small class="text-muted">Se muestran hasta 10 archivos del usuario actual.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
