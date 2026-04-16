<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('index.php/Principal/listadoHonorarios') ?>">Listado</a></li>
                                <li class="breadcrumb-item active">Ver Archivos</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Archivos de Solicitud #<?= $id_solicitud ?></h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mt-0">Documentación Cargada</h4>

                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Documento</th>
                                            <th>Archivo</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($archivos)): ?>
                                            <?php foreach ($archivos as $archivo): ?>
                                                <tr>
                                                    <td>
                                                        <?= isset($documentos_honorarios[$archivo->clave_documento]) ? $documentos_honorarios[$archivo->clave_documento] : ($archivo->nombre_documento ?? ('Documento ' . $archivo->clave_documento)) ?>
                                                    </td>
                                                    <td><?= $archivo->nombre_archivo ?></td>
                                                    <td class="text-center">
                                                        <a href="<?= $archivo->url_descarga ?? '#' ?>" target="_blank" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i> Ver
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center">No se encontraron archivos cargados para esta solicitud.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <a href="<?= base_url('index.php/Principal/listadoHonorarios') ?>" class="btn btn-secondary">Volver al Listado</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
