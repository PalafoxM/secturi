<?php
$esConvenio = !empty($es_convenio);
$rutaListado = $esConvenio ? 'index.php/Principal/ListaSolicitudConvenio' : 'index.php/Principal/ListaSolicitudContrato';
$rutaArchivo = $esConvenio ? 'assets/uploads/convenios/' : 'assets/uploads/contratos/';
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
                                <li class="breadcrumb-item"><a href="<?= base_url($rutaListado) ?>">Listado</a></li>
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
                            
                            <?php 
                            $nombres_docs = [
                                1 => 'Anexo Técnico (Términos de referencia)',
                                '2a' => 'Investigación de Mercado (Cotizaciones y consulta PEI)',
                                '2b' => 'Análisis de Ofertas turísticas (Excepción de Ley de Contrataciones)',
                                '2c' => 'Argumentación Técnica (Proveedor único)',
                                '3a' => 'Validación de partida restringida (SF)',
                                '3b' => 'Verificación de Alineación de Información Estratégica (DGIT)',
                                '3c' => 'Suficiencia presupuestal (R3)',
                                '3d' => 'Validación DGTIT/CGCS u otra',
                                4 => 'Justificación',
                                5 => 'Propuesta Técnico Económica (Anexo)',
                                6 => 'Aviso de privacidad integral',
                                7 => 'Cédula de Registro en el Padrón de Proveedores (Refrendo vigente)',
                                8 => 'Escritura Constitutiva/Documento que acredite la legal constitución de la persona moral',
                                9 => 'Documento que acredite la representación de la persona moral (Poder)',
                                10 => 'Identificación oficial vigente (Personas morales Representante y Personas Físicas)',
                                11 => 'Constancia de situación fiscal',
                                12 => 'Comprobante de domicilio',
                                '13a' => 'Opinión de cumplimiento de obligaciones fiscales',
                                '13b' => 'Manifiesto bajo protesta de complimiento de obligaciones fiscales',
                                14 => 'Manifiesto de no encontrare impedido para contratar',
                                15 => 'Carta de Declaración de intereses',
                                16 => 'Manifiesto de contar con infraestructura'
                            ];
                            ?>

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
                                                        <?= isset($nombres_docs[$archivo->clave_documento]) ? $nombres_docs[$archivo->clave_documento] : 'Documento ' . $archivo->clave_documento ?>
                                                    </td>
                                                    <td><?= $archivo->nombre_archivo ?></td>
                                                    <td class="text-center">
                                                        <a href="<?= base_url($rutaArchivo . $archivo->nombre_archivo) ?>" target="_blank" class="btn btn-info btn-sm">
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
                                <a href="<?= base_url($rutaListado) ?>" class="btn btn-secondary">Volver al Listado</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
