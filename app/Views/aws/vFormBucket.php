<?php
$tiposArchivo = [
    'IMG' => [
        'label' => 'Imagen',
        'accept' => '.jpg,.jpeg,.png,.gif,.webp,.bmp',
        'carpeta' => 'imagenes',
    ],
    'VIDEO' => [
        'label' => 'Video',
        'accept' => '.mp4,.mov,.avi,.mkv,.webm',
        'carpeta' => 'videos',
    ],
    'ARCHIVO' => [
        'label' => 'Archivo',
        'accept' => '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar',
        'carpeta' => 'archivos',
    ],
    'AUDIO' => [
        'label' => 'Audio',
        'accept' => '.mp3,.wav,.ogg,.m4a',
        'carpeta' => 'audios',
    ],
];
?>

<div class="page-content-tab">
    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Carga de archivos al bucket AWS S3</h5>
                            <a href="<?= base_url('index.php/Agregar/listaBucketAws') ?>" class="btn btn-light btn-sm">Ver mis archivos</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger mb-4">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <form id="formBuckets" action="<?= base_url('index.php/Agregar/guardarBucketAws') ?>" method="POST" enctype="multipart/form-data">
                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Nombre del archivo</label>
                                <input type="text" name="nombre_archivo" class="form-control" placeholder="Ej. Evidencia de evento" required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Descripcion</label>
                                <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe brevemente el archivo..." required></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Tipo de archivo</label>
                                <select name="tipo_archivo" id="tipo_archivo" class="form-control" required>
                                    <option value="">Selecciona una opcion</option>
                                    <?php foreach ($tiposArchivo as $clave => $tipo): ?>
                                        <option
                                            value="<?= esc($clave) ?>"
                                            data-accept="<?= esc($tipo['accept']) ?>"
                                            data-carpeta="<?= esc($tipo['carpeta']) ?>"
                                            data-label="<?= esc($tipo['label']) ?>"
                                        >
                                            <?= esc($clave) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted" id="infoTipoArchivo">Selecciona un tipo para habilitar la carga y definir la carpeta destino.</small>
                            </div>

                            <div class="form-group mb-4 d-none" id="contenedorArchivo">
                                <label class="form-label font-weight-bold" id="labelArchivo">Selecciona el archivo</label>
                                <input type="file" name="archivo_s3" id="archivo_s3" class="form-control" disabled>
                                <small class="form-text text-muted" id="ayudaArchivo"></small>
                            </div>

                            <div class="alert alert-light border mb-4">
                                <strong>Comportamiento:</strong> primero se valida si la carpeta existe en el bucket; si no existe, se crea y despues se guarda el archivo en esa ruta.
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary px-4 py-2">
                                    <i class="fas fa-cloud-upload-alt"></i> Subir archivo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoArchivo = document.getElementById('tipo_archivo');
    const contenedorArchivo = document.getElementById('contenedorArchivo');
    const inputArchivo = document.getElementById('archivo_s3');
    const ayudaArchivo = document.getElementById('ayudaArchivo');
    const infoTipoArchivo = document.getElementById('infoTipoArchivo');
    const labelArchivo = document.getElementById('labelArchivo');

    function actualizarCampoArchivo() {
        const opcion = tipoArchivo.options[tipoArchivo.selectedIndex];
        const accept = opcion ? opcion.getAttribute('data-accept') : '';
        const carpeta = opcion ? opcion.getAttribute('data-carpeta') : '';
        const label = opcion ? opcion.getAttribute('data-label') : '';

        inputArchivo.value = '';

        if (!tipoArchivo.value) {
            contenedorArchivo.classList.add('d-none');
            inputArchivo.disabled = true;
            inputArchivo.removeAttribute('accept');
            inputArchivo.removeAttribute('required');
            infoTipoArchivo.textContent = 'Selecciona un tipo para habilitar la carga y definir la carpeta destino.';
            ayudaArchivo.textContent = '';
            labelArchivo.textContent = 'Selecciona el archivo';
            return;
        }

        contenedorArchivo.classList.remove('d-none');
        inputArchivo.disabled = false;
        inputArchivo.required = true;
        inputArchivo.setAttribute('accept', accept);
        labelArchivo.textContent = 'Selecciona el ' + label.toLowerCase();
        infoTipoArchivo.textContent = 'Se guardara en la carpeta "' + carpeta + '" del bucket.';
        ayudaArchivo.textContent = 'Formatos permitidos: ' + accept.replaceAll('.', '').replaceAll(',', ', ');
    }

    tipoArchivo.addEventListener('change', actualizarCampoArchivo);
    actualizarCampoArchivo();
});
</script>
