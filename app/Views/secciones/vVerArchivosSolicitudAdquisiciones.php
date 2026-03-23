<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('index.php/Principal/ListaSolicitudAdquisiciones') ?>">Listado</a></li>
                                <li class="breadcrumb-item active">Ver Archivos</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Archivos de Solicitud de Adquisiciones #<?= $id_solicitud ?></h4>
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
                                1 => "Anexo Técnico (Términos de referencia)",
                                2 => "Investigación de Mercado (Cotizaciones y consulta PEI)",
                                3 => "Validación de partida restringida (SF)<br>Verificación de Alineación de Información Estratégica (DGIT)<br>Suficiencia presupuestal (R3)<br>Validación DGTIT/CGCS u otra",
                                4 => "Justificación",
                                5 => "Propuesta Técnico Económica (Anexo)",
                                6 => "Aviso de privacidad integral",
                                7 => "Cédula de Registro en el Padrón de Proveedores (Refrendo vigente)",
                                8 => "Escritura Constitutiva/Documento que acredite la legal constitución de la persona moral (Modificaciones sustanciales e inscripción en el Registro Público)",
                                9 => "Documento que acredite la representación de la persona moral (Poder)",
                                10 => "Identificación oficial vigente (Personas morales Representante y Responsable de seguimiento)",
                                11 => "Constancia de Situación Fiscal (RFC)",
                                12 => "Comprobante de domicilio (Sólo cuando sea diferente al domicilio fiscal)",
                                13 => "Opinión de cumplimiento de Obligaciones Fiscales<br>Manifiesto bajo protesta de cumplimiento de Obligaciones Fiscales",
                                14 => "Manifiesto de no encontrarse impedido para Contratar",
                                15 => "Carta de Declaración de intereses",
                                16 => "Manifiesto de contar con infraestructura",
                                17 => "Carta compromiso entrega de bienes (Excepción de Garantía)"
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
                                                        <a href="<?= base_url('assets/uploads/adquisiciones/' . $archivo->nombre_archivo) ?>" target="_blank" class="btn btn-info btn-sm">
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
                                <a href="<?= base_url('index.php/Principal/ListaSolicitudAdquisiciones') ?>" class="btn btn-secondary">Volver al Listado</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
