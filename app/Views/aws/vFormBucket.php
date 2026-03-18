<div class="page-content-tab">
    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Prueba de Subida a AWS S3 (Tabla: bucketaws)</h5>
                    </div>
                    <div class="card-body">
                        
                        <?php if(session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>

                        <?php if(session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger mb-4">
                                Error de Servidor o AWS: <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <form id="formBuckets" action="<?= base_url('index.php/Agregar/guardarBucketAws') ?>" method="POST" enctype="multipart/form-data">
                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Nombre del Archivo</label>
                                <input type="text" name="nombre_archivo" class="form-control" placeholder="Ej: Documento de prueba" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe brevemente el archivo..." required></textarea>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label class="form-label font-weight-bold">Selecciona el archivo</label>
                                <input type="file" name="archivo_s3" class="form-control-file" required>
                                <small class="form-text text-muted">Asegúrate de no enviar archivos demasiado pesados para esta prueba.</small>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary px-4 py-2">
                                    <i class="fas fa-cloud-upload-alt"></i> Subir a S3 y Guardar en BD
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
