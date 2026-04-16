<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Jurídico</a></li>
                                <li class="breadcrumb-item active">Solicitud de Contrato</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Solicitud de Elaboración de Contrato</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h4 class="mt-0">DIRECCIÓN GENERAL JURÍDICA (DGJ-1)</h4>
                                <h5>SOLICITUD DE ELABORACIÓN DE CONTRATO</h5>
                            </div>
                            
                            <form id="form_solicitud_contrato" enctype="multipart/form-data">
                                <input type="hidden" name="id_solicitud_contrato" value="<?= isset($solicitud) ? $solicitud->id_solicitud_contrato : '' ?>">
                                
                                <!-- SECCION 1: INFORMACIÓN DEL ÁREA SOLICITANTE -->
                                <h5 class="bg-primary text-white p-2">INFORMACIÓN DEL ÁREA SOLICITANTE</h5>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre y cargo del Responsable del Proyecto:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2" name="responsable_proyecto" required>
                                            <option value="">Seleccione una opción</option>
                                            <?php foreach ($direccion as $u): ?>
                                                <option value="<?= $u->id_usuario ?>" <?= (isset($solicitud) && $solicitud->responsable_proyecto == $u->id_usuario) ? 'selected' : '' ?>>
                                                    <?= $u->nombre_completo .' - '. $u->dsc_puesto ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre y cargo del Responsable de Seguimiento:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2" name="responsable_seguimiento" required>
                                            <option value="">Seleccione una opción</option>
                                            <?php foreach ($usuario as $u): ?>
                                                <option value="<?= $u->id_usuario ?>" <?= (isset($solicitud) && $solicitud->responsable_seguimiento == $u->id_usuario) ? 'selected' : '' ?>>
                                                     <?= $u->nombre_completo .' - '. $u->dsc_puesto ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre y cargo del Enlace de Comunicaciones:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2" name="enlace_comunicaciones">
                                            <option value="">Seleccione una opción</option>
                                            <?php foreach ($usuario as $u): ?>
                                                <option value="<?= $u->id_usuario ?>" <?= (isset($solicitud) && $solicitud->enlace_comunicaciones == $u->id_usuario) ? 'selected' : '' ?>>
                                                    <?= $u->nombre_completo .' - '. $u->dsc_puesto ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- SECCION 2: INFORMACIÓN PRESUPUESTAL -->
                                <h5 class="bg-primary text-white p-2 mt-4">INFORMACIÓN PRESUPUESTAL</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Proyecto</th>
                                                <th>Partida</th>
                                                <th>Clave estandarizada</th>
                                                <th>Suficiencia Presupuestal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select class="form-control select2" name="proyecto">
                                                        <option value="">Seleccione una opción</option>
                                                        <?php foreach ($cat_proyecto as $p): ?>
                                                            <option value="<?= $p->id_proyecto ?>" <?= (isset($solicitud) && $solicitud->proyecto == $p->id_proyecto) ? 'selected' : '' ?>>
                                                                <?= $p->proyecto ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control select2" name="partida">
                                                        <option value="">Seleccione una opción</option>
                                                        <?php foreach ($cat_partida as $p): ?>
                                                            <option value="<?= $p->id_partida ?>" <?= (isset($solicitud) && $solicitud->partida == $p->id_partida) ? 'selected' : '' ?>>
                                                                <?= $p->cuenta_cable ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="clave_estandarizada" value="<?= isset($solicitud) ? $solicitud->clave_estandarizada : '' ?>"></td>
                                                <td>
                                                    <p class="small text-muted mb-0">El proyecto cuenta con la suficiencia presupuestal para la contratación de los servicios requeridos en la presente solicitud. Se anexa captura de pantalla Sistema SAP/R3</p>
                                                     <input type="checkbox" class="form-control mt-2 ml-2 d-flex align-items-center justify-content-center" style="width: 20px;" name="suficiencia_presupuestal" value="1" <?= (isset($solicitud) && $solicitud->suficiencia_presupuestal == 1) ? 'checked' : '' ?>> 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Monto Total del Contrato (con número y letra):</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="monto_total" name="monto_total" value="<?= isset($solicitud) ? $solicitud->monto_total : '' ?>" required>
                                        <input type="text" class="form-control mt-2" id="monto_total_texto" name="monto_total_texto" value="<?= isset($solicitud) ? esc($solicitud->monto_total_texto ?? '') : '' ?>" readonly placeholder="Monto en letra">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Tipo y monto de Garantía (con número y letra):</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="garantia">
                                            <option value="">Seleccione una opción</option>
                                            <option value="CHEQUE" <?= (isset($solicitud) && $solicitud->garantia == 'CHEQUE') ? 'selected' : '' ?>>CHEQUE</option>
                                            <option value="PAGARE" <?= (isset($solicitud) && $solicitud->garantia == 'PAGARE') ? 'selected' : '' ?>>PAGARE</option>
                                            <option value="FIANZA" <?= (isset($solicitud) && $solicitud->garantia == 'FIANZA') ? 'selected' : '' ?>>FIANZA</option>
                                            <option value="NO APLICA" <?= (isset($solicitud) && $solicitud->garantia == 'NO APLICA') ? 'selected' : '' ?>>NO APLICA</option>
                                        </select>
                                        <div class="custom-control custom-switch mt-2">
                                            <input type="checkbox" class="custom-control-input" id="custom_garantia_check">
                                            <label class="custom-control-label" for="custom_garantia_check">Ingresar otro monto o porcentaje de garantía</label>
                                        </div>
                                        <input type="text" class="form-control mt-2" name="monto_garantia" id="monto_garantia" value="<?= isset($solicitud) ? ($solicitud->monto_garantia ?? '') : '' ?>" readonly placeholder="12% del monto total">
                                        <input type="text" class="form-control mt-2" name="monto_garantia_texto" id="monto_garantia_texto" value="<?= isset($solicitud) ? esc($solicitud->monto_garantia_texto ?? '') : '' ?>" readonly placeholder="Monto de garantÃ­a en letra">
                                    </div>
                                </div>

                                <!-- SECCION 3: DESCRIPCIÓN DEL SERVICIO -->
                                <h5 class="bg-primary text-white p-2 mt-4">DESCRIPCIÓN DEL SERVICIO A CONTRATAR O BIENES A ADQUIRIR</h5>
                                <div class="form-group">
                                    <label>Objeto del Contrato:</label>
                                    <textarea class="form-control" name="objeto_contrato" rows="4" required><?= isset($solicitud) ? $solicitud->objeto_contrato : '' ?></textarea>
                                </div>
                                <h6 class="mt-3">Vigencia y Pago del Contrato</h6>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Fecha de inicio:</label>
                                        <input type="date" class="form-control" name="fecha_inicio" value="<?= isset($solicitud) ? date('Y-m-d', strtotime($solicitud->fecha_inicio)) : '' ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Fecha de término:</label>
                                        <input type="date" class="form-control" name="fecha_termino" value="<?= isset($solicitud) ? date('Y-m-d', strtotime($solicitud->fecha_termino)) : '' ?>" required>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="tabla_pagos">
                                        <thead>
                                            <tr>
                                                <th>Pagos</th>
                                                <th>Monto TOTAL</th>
                                                <th>Fecha</th>
                                                <th>Entregable y contenido</th>
                                                <th style="width:50px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Dynamic rows -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-right">
                                                    <button type="button" class="btn btn-secondary btn-sm" onclick="agregarPago()">+ Agregar Pago</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- SECCION 4: INFORMACIÓN DEL PROVEEDOR -->
                                <h5 class="bg-primary text-white p-2 mt-4">INFORMACIÓN DEL PROVEEDOR</h5>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre/Razón Social:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_nombre" value="<?= isset($solicitud) ? $solicitud->proveedor_nombre : '' ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Domicilio fiscal:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_domicilio" value="<?= isset($solicitud) ? $solicitud->proveedor_domicilio : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">RFC:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_rfc" value="<?= isset($solicitud) ? $solicitud->proveedor_rfc : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Cédula de Registro (Padrón de Proveedores):</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_cedula" value="<?= isset($solicitud) ? $solicitud->proveedor_cedula : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre del Representante Legal (persona moral):</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_representante" value="<?= isset($solicitud) ? $solicitud->proveedor_representante : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Responsable de Seguimiento:</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="proveedor_seguimiento" value="<?= isset($solicitud) ? esc($solicitud->proveedor_seguimiento ?? '') : '' ?>">
                                    </div>
                                    
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Correo de Seguimiento:</label>
                                   <div class="col-sm-4">
                                        <input type="email" class="form-control" name="proveedor_correo" value="<?= isset($solicitud) ? esc($solicitud->proveedor_correo ?? '') : '' ?>">
                                    </div>
                                    
                                </div>

                                <!-- SECCION 5: DOCUMENTOS Y ANEXOS -->
                                <?php if (!empty($edita) && !empty($solicitud->id_solicitud_contrato)): ?>
                                <h5 class="bg-primary text-white p-2 mt-4">DOCUMENTOS Y ANEXOS</h5>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-secondary" onclick="abrirModalArchivosEdicion(<?= (int) $solicitud->id_solicitud_contrato ?>)">
                                        <i class="fas fa-paperclip"></i> Editar Archivos Adjuntos
                                    </button>
                                    <?php if (!empty($archivos_soporte)): ?>
                                        <a href="<?= base_url('index.php/Principal/verArchivosSolicitud/' . $solicitud->id_solicitud_contrato) ?>" class="btn btn-success">
                                            <i class="fas fa-eye"></i> Ver Archivos Actuales
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>

                                <h5 class="bg-primary text-white p-2 mt-4">FIRMAS</h5>
                                <div class="card border mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <p class="mb-0 text-muted">Agrega hasta 3 firmas para el formato del contrato.</p>
                                            <button type="button" class="btn btn-primary btn-sm" id="btnAgregarFirmaContrato">
                                                <i class="fas fa-plus"></i> Agregar firma
                                            </button>
                                        </div>
                                        <div id="contenedor_firmas_contrato"></div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-success btn-lg"><i class="mdi mdi-content-save"></i> Guardar Solicitud</button>
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

<?php if (!empty($edita) && !empty($solicitud->id_solicitud_contrato)): ?>
<div class="modal fade" id="modalSeleccionArchivosEdicion" tabindex="-1" role="dialog" aria-labelledby="modalLabelEdicion" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabelEdicion">Seleccion de Documentos a Reemplazar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formSeleccionArchivosEdicion" action="<?= base_url('index.php/Principal/subirArchivosSolicitud') ?>" method="POST">
                    <input type="hidden" name="id_solicitud" id="modal_id_solicitud_edicion">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr><th style="width: 5%;">Num.</th><th>DOCUMENTO</th><th style="width: 10%; text-align: center;">SI</th></tr>
                        </thead>
                        <tbody>
                            <?php $documentosEdicion = [1 => 'Anexo Tecnico', '2a' => 'Investigacion de Mercado', '2b' => 'Analisis de Ofertas turisticas', '2c' => 'Argumentacion Tecnica', '3a' => 'Validacion de partida', '3b' => 'Alineacion Estrategica', '3c' => 'Suficiencia presupuestal', '3d' => 'Validacion complementaria', 4 => 'Justificacion', 5 => 'Propuesta Tecnico Economica', 6 => 'Aviso de privacidad integral', 7 => 'Cedula de Proveedores', 8 => 'Escritura Constitutiva', 9 => 'Poder', 10 => 'Identificacion oficial', 11 => 'Constancia fiscal', 12 => 'Comprobante de domicilio', '13a' => 'Opinion fiscal', '13b' => 'Manifiesto fiscal', 14 => 'Manifiesto de no impedimento', 15 => 'Declaracion de intereses', 16 => 'Manifiesto de infraestructura']; ?>
                            <?php foreach ($documentosEdicion as $key => $doc): ?>
                                <tr><td><?= $key ?></td><td><?= $doc ?></td><td class="text-center"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input check-si-edicion" id="si_edicion_<?= $key ?>" name="documentos[<?= $key ?>]" value="<?= $doc ?>"><label class="custom-control-label" for="si_edicion_<?= $key ?>"></label></div></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="enviarFormularioArchivosEdicion()">Continuar</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

       <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/jquery-ui.min.css" rel="stylesheet">
        <link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
        <!-- jQuery  -->
        <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
        <script src="<?= base_url() ?>assets/js/jquery-ui.min.js"></script>
        <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url() ?>assets/js/metismenu.min.js"></script>
        <script src="<?= base_url() ?>assets/js/waves.js"></script>
        <script src="<?= base_url() ?>assets/js/feather.min.js"></script>
        <script src="<?= base_url() ?>assets/js/jquery.slimscroll.min.js"></script>
        <script src="<?= base_url() ?>plugins/select2/select2.min.js"></script>

<script>
    const catalogoFirmantesContrato = <?= json_encode($catalogo_firmantes ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    const firmasSeleccionadasContrato = <?= json_encode(array_values($firmas_seleccionadas ?? []), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

    function normalizarMonto(valor) {
        if (valor === null || valor === undefined) {
            return 0;
        }

        var texto = String(valor).replace(/[$,\s]/g, '');
        var numero = parseFloat(texto);
        return isNaN(numero) ? 0 : numero;
    }

    function numeroALetras(amount) {
        if (amount == 0) return "CERO PESOS 00/100 M.N.";
        var pesos = Math.floor(amount);
        var centavos = Math.round((amount - pesos) * 100);
        var letras = "";

        if (pesos == 0) letras = "CERO";
        else if (pesos == 1) letras = "UN";
        else letras = convertirGrupo(pesos);

        return (letras + " PESOS " + (centavos < 10 ? "0" : "") + centavos + "/100 M.N.").toUpperCase();
    }

    function convertirGrupo(n) {
        var output = "";
        if (n == 100) output = "CIEN";
        else if (n > 100 && n < 1000) output = centenas(n);
        else if (n >= 1000 && n < 1000000) {
            var miles = Math.floor(n / 1000);
            var resto = n % 1000;
            output = (miles == 1 ? "UN" : convertirGrupo(miles)) + " MIL" + (resto > 0 ? " " + convertirGrupo(resto) : "");
        } else if (n >= 1000000) {
            var millones = Math.floor(n / 1000000);
            var resto = n % 1000000;
            output = (millones == 1 ? "UN MILLON" : convertirGrupo(millones) + " MILLONES") + (resto > 0 ? " " + convertirGrupo(resto) : "");
        } else {
            output = centenas(n);
        }
        return output;
    }

    function centenas(n) {
        var centenas = Math.floor(n / 100);
        var decenas = n % 100;
        var output = "";
        
        switch (centenas) {
            case 1: output = (decenas > 0 ? "CIENTO" : "CIEN"); break;
            case 2: output = "DOSCIENTOS"; break;
            case 3: output = "TRESCIENTOS"; break;
            case 4: output = "CUATROCIENTOS"; break;
            case 5: output = "QUINIENTOS"; break;
            case 6: output = "SEISCIENTOS"; break;
            case 7: output = "SETECIENTOS"; break;
            case 8: output = "OCHOCIENTOS"; break;
            case 9: output = "NOVECIENTOS"; break;
        }
        
        if (decenas > 0) output += (output ? " " : "") + dec(decenas);
        return output;
    }

    function dec(n) {
        if (n < 10) return unidades(n);
        var output = "";
        if (n >= 10 && n <= 29) {
            switch (n) {
                case 10: output = "DIEZ"; break;
                case 11: output = "ONCE"; break;
                case 12: output = "DOCE"; break;
                case 13: output = "TRECE"; break;
                case 14: output = "CATORCE"; break;
                case 15: output = "QUINCE"; break;
                case 16: output = "DIECISEIS"; break;
                case 17: output = "DIECISIETE"; break;
                case 18: output = "DIECIOCHO"; break;
                case 19: output = "DIECINUEVE"; break;
                case 20: output = "VEINTE"; break;
                case 21: output = "VEINTIUNO"; break;
                case 22: output = "VEINTIDOS"; break;
                case 23: output = "VEINTITRES"; break;
                case 24: output = "VEINTICUATRO"; break;
                case 25: output = "VEINTICINCO"; break;
                case 26: output = "VEINTISEIS"; break;
                case 27: output = "VEINTISIETE"; break;
                case 28: output = "VEINTIOCHO"; break;
                case 29: output = "VEINTINUEVE"; break;
            }
        } else {
             var d = Math.floor(n / 10);
             var u = n % 10;
             switch(d) {
                 case 3: output = "TREINTA"; break;
                 case 4: output = "CUARENTA"; break;
                 case 5: output = "CINCUENTA"; break;
                 case 6: output = "SESENTA"; break;
                 case 7: output = "SETENTA"; break;
                 case 8: output = "OCHENTA"; break;
                 case 9: output = "NOVENTA"; break;
             }
             if (u > 0) output += " Y " + unidades(u);
        }
        return output;
    }

    function unidades(n) {
        switch(n) {
            case 1: return "UN";
            case 2: return "DOS";
            case 3: return "TRES";
            case 4: return "CUATRO";
            case 5: return "CINCO";
            case 6: return "SEIS";
            case 7: return "SIETE";
            case 8: return "OCHO";
            case 9: return "NUEVE";
        }
        return "";
    }

    function opcionesFirmantesContrato(valorSeleccionado = '') {
        let html = '<option value="">Seleccione un usuario</option>';
        catalogoFirmantesContrato.forEach(usuario => {
            const seleccionado = String(usuario.id_usuario) === String(valorSeleccionado) ? 'selected' : '';
            const puesto = usuario.dsc_puesto ? String(usuario.dsc_puesto).replace(/"/g, '&quot;') : '';
            html += `<option value="${usuario.id_usuario}" data-puesto="${puesto}" ${seleccionado}>${usuario.nombre_completo}</option>`;
        });
        return html;
    }

    function actualizarPuestoFirmaContrato(select) {
        const puesto = $(select).find(':selected').data('puesto') || '';
        $(select).closest('.firma-item').find('.firma-puesto').text(puesto);
    }

    function reindexarFirmasContrato() {
        $('#contenedor_firmas_contrato .firma-item').each(function(index) {
            $(this).attr('data-index', index);
            $(this).find('.firma-label').text(`Firma ${index + 1}`);
            $(this).find('select').attr('name', `firmas[${index}]`);
        });
    }

    function agregarFirmaContrato(valorSeleccionado = '') {
        const contenedor = $('#contenedor_firmas_contrato');
        if (contenedor.find('.firma-item').length >= 3) {
            Swal.fire({
                icon: 'warning',
                title: 'Límite de firmas',
                text: 'Solo puedes agregar hasta 3 firmas.'
            });
            return;
        }

        const index = contenedor.find('.firma-item').length;
        const html = `
            <div class="firma-item border rounded p-3 mb-3" data-index="${index}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="firma-label">Firma ${index + 1}</strong>
                    <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar-firma">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <select class="form-control select2 firma-select" name="firmas[${index}]">
                    ${opcionesFirmantesContrato(valorSeleccionado)}
                </select>
                <div class="firma-puesto text-uppercase small text-muted mt-2"></div>
            </div>
        `;

        contenedor.append(html);
        const nuevoItem = contenedor.find('.firma-item').last();
        nuevoItem.find('.firma-select').select2({
            width: '100%'
        });
        actualizarPuestoFirmaContrato(nuevoItem.find('.firma-select'));
    }

    $(document).ready(function() {
        $('#monto_total').on('input', function() {
            var valor = $(this).val();
            var montoNormalizado = normalizarMonto(valor);
            // Validación de número
            if (valor.trim() === '' || montoNormalizado <= 0) {
                 if(valor.trim() !== '') {
                      $('#monto_total_texto').val('NUMERO NO LEGIBLE');
                 } else {
                       $('#monto_total_texto').val('');
                 }
            } else {
                $('#monto_total_texto').val(numeroALetras(montoNormalizado));
                
                if (!$('#custom_garantia_check').is(':checked')) {
                    // Calcular monto total + 12%
                    var monto = montoNormalizado;
                    var garantia = monto * 0.12;
                    var totalMonto = garantia;
                    $('#monto_garantia').val(totalMonto.toFixed(2));
                    $('#monto_garantia_texto').val(numeroALetras(totalMonto));
                }
            }
        });
        
        $('#custom_garantia_check').on('change', function() {
            if ($(this).is(':checked')) {
                $('#monto_garantia').removeAttr('readonly');
                $('#monto_garantia').trigger('input');
            } else {
                $('#monto_garantia').attr('readonly', true);
                $('#monto_total').trigger('input'); // Recalcular monto original
            }
        });

        $('#monto_garantia').on('input', function() {
            var valorGarantia = $(this).val();
            if (isNaN(valorGarantia) || valorGarantia.trim() === '') {
                if (valorGarantia.trim() !== '') {
                    $('#monto_garantia_texto').val('NUMERO NO LEGIBLE');
                } else {
                    $('#monto_garantia_texto').val('');
                }
            } else {
                $('#monto_garantia_texto').val(numeroALetras(parseFloat(valorGarantia)));
            }
        });
        
        // Trigger inicial si ya hay valor
        if($('#monto_total').val()) {
            // Verificar si hay monto de garantia original
            var valOriginalGarantia = "<?= isset($solicitud) ? ($solicitud->monto_garantia ?? '') : '' ?>";
            $('#monto_total').trigger('input');
            
            // Si el original que viene de BD es diferente al calculado o no vacio, activar checkbox
            if (valOriginalGarantia && valOriginalGarantia !== $('#monto_garantia').val()) {
                $('#custom_garantia_check').prop('checked', true).trigger('change');
                $('#monto_garantia').val(valOriginalGarantia);
            }
            $('#monto_garantia').trigger('input');
        }
    });

    const pagosExistentes = <?= isset($pagos) ? json_encode($pagos) : '[]' ?>;

    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    function agregarPago(data = null) {
        const tbody = document.querySelector('#tabla_pagos tbody');
        const count = tbody.children.length + 1;
        const row = document.createElement('tr');
        
        let numero = data ? data.numero_pago : `${count}º Pago`;
        let monto = data ? data.monto : '';
        let entregable = data ? data.entregable : '';
        
        // Determinar mes seleccionado
        let mesSeleccionado = '';
        if (data && data.fecha) {
            // Si es formato fecha YYYY-MM-DD
            if (data.fecha.match(/^\d{4}-\d{2}-\d{2}/)) {
                let parts = data.fecha.split('-'); // [YYYY, MM, DD]
                if(parts.length >= 2) {
                    let mesIndex = parseInt(parts[1]) - 1;
                    if(mesIndex >= 0 && mesIndex < 12) {
                        mesSeleccionado = meses[mesIndex];
                    }
                }
            } else {
                // Si ya es texto
                mesSeleccionado = data.fecha;
            }
        }

        let options = '<option value="">Seleccione mes</option>';
        meses.forEach(mes => {
            let selected = (mes === mesSeleccionado) ? 'selected' : '';
            options += `<option value="${mes}" ${selected}>${mes}</option>`;
        });

        row.innerHTML = `
            <td><input type="text" class="form-control" name="pagos[${count}][numero]" value="${numero}" placeholder="Ej. 1er Pago"></td>
            <td><input type="text" class="form-control" name="pagos[${count}][monto]" value="${monto}" placeholder="$"></td>
            <td>
                <select class="form-control" name="pagos[${count}][fecha]">
                    ${options}
                </select>
            </td>
            <td><input type="text" class="form-control" name="pagos[${count}][entregable]" value="${entregable}" placeholder="Descripción"></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="mdi mdi-trash-can"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    }
    
    // Validate payment sum
    function validarMontoPagos(mostrarError = false) {
        var montoTotal = normalizarMonto($('#monto_total').val());
        var sumaPagos = 0;

        $('#tabla_pagos tbody input[name*="[monto]"]').each(function() {
            sumaPagos += normalizarMonto($(this).val());
        });

        if (sumaPagos > montoTotal) {
            if (mostrarError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El monto total de los pagos (' + sumaPagos.toFixed(2) + ') no debe ser mayor al Monto Total del Contrato (' + montoTotal.toFixed(2) + ').',
                    showConfirmButton: true
                });
            } else {
                 // Toast or subtle indicator
                 Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'La suma de pagos excede el total',
                    showConfirmButton: false,
                    timer: 3500
                });
            }
            return false;
        }
        return true;
    }

    // Add initial row
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('.select2').select2();

        if (firmasSeleccionadasContrato.length > 0) {
            firmasSeleccionadasContrato.forEach(firma => agregarFirmaContrato(firma));
        } else {
            agregarFirmaContrato();
        }

        $('#btnAgregarFirmaContrato').on('click', function() {
            agregarFirmaContrato();
        });

        $(document).on('change', '.firma-select', function() {
            actualizarPuestoFirmaContrato(this);
        });

        $(document).on('click', '.btn-eliminar-firma', function() {
            const item = $(this).closest('.firma-item');
            const select = item.find('.firma-select');
            if (select.hasClass('select2-hidden-accessible')) {
                select.select2('destroy');
            }
            item.remove();
            reindexarFirmasContrato();
        });

        // Real-time validation
        $(document).on('input', '#monto_total, #tabla_pagos input[name*="[monto]"]', function() {
            validarMontoPagos(false);
        });

        if (pagosExistentes && pagosExistentes.length > 0) {
            pagosExistentes.forEach(pago => {
                agregarPago(pago);
            });
        } else {
            agregarPago();
        }
        
        $('#form_solicitud_contrato').on('submit', function(e) {
            e.preventDefault();

            if (!validarMontoPagos(true)) {
                return;
            }
            
            var formData = new FormData(this);
            var btnSubmit = $(this).find('button[type="submit"]');
            
            btnSubmit.prop('disabled', true);
            btnSubmit.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            $.ajax({
                url: '<?= base_url("index.php/Principal/guardarSolicitudContrato") ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(data) {
                    if (!data.error) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Solicitud guardada correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '<?= base_url("index.php/Principal/ListaSolicitudContrato") ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error: ' + data.respuesta,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        btnSubmit.prop('disabled', false);
                        btnSubmit.html('<i class="mdi mdi-content-save"></i> Guardar Solicitud');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Ocurrió un error al procesar la solicitud');
                    btnSubmit.prop('disabled', false);
                    btnSubmit.html('<i class="mdi mdi-content-save"></i> Guardar Solicitud');
                }
            });
        });
    });

    function abrirModalArchivosEdicion(id) {
        $('#modal_id_solicitud_edicion').val(id);
        $('.check-si-edicion').prop('checked', false);
        $('#modalSeleccionArchivosEdicion').modal('show');
    }

    function enviarFormularioArchivosEdicion() {
        $('#formSeleccionArchivosEdicion').submit();
    }
</script>
