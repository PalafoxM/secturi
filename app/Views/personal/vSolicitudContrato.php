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
                                            <?php foreach ($usuario as $u): ?>
                                                <option value="<?= $u->id_usuario ?>" <?= (isset($solicitud) && $solicitud->responsable_proyecto == $u->id_usuario) ? 'selected' : '' ?>>
                                                    <?= $u->nombre_completo ?>
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
                                                    <?= $u->nombre_completo ?>
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
                                                    <?= $u->nombre_completo ?>
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
                                                    <input type="file" class="form-control-file mt-2" name="archivo_suficiencia">
                                                    <?php if(isset($solicitud) && $solicitud->archivo_suficiencia): ?>
                                                        <a href="<?= base_url('assets/uploads/contratos/'.$solicitud->archivo_suficiencia) ?>" target="_blank" class="d-block mt-2">Ver archivo actual</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Monto Total del Contrato (con número y letra):</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="monto_total" value="<?= isset($solicitud) ? $solicitud->monto_total : '' ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Tipo y monto de Garantía (con número y letra):</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="garantia" value="<?= isset($solicitud) ? $solicitud->garantia : '' ?>">
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
                                    <label class="col-sm-3 col-form-label">Responsable de Seguimiento (correo electrónico):</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" name="proveedor_correo" value="<?= isset($solicitud) ? $solicitud->proveedor_correo : '' ?>">
                                    </div>
                                </div>

                                <!-- SECCION 5: DOCUMENTOS Y ANEXOS -->
                                <h5 class="bg-primary text-white p-2 mt-4">DOCUMENTOS Y ANEXOS</h5>
                                <div class="alert alert-info text-center">
                                    <strong>SOPORTE DOCUMENTAL SE RELACIONA EN EL REVERSO</strong>
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
    const pagosExistentes = <?= isset($pagos) ? json_encode($pagos) : '[]' ?>;

    function agregarPago(data = null) {
        const tbody = document.querySelector('#tabla_pagos tbody');
        const count = tbody.children.length + 1;
        const row = document.createElement('tr');
        
        let numero = data ? data.numero_pago : `${count}º Pago`;
        let monto = data ? data.monto : '';
        let fecha = data ? data.fecha.split(' ')[0] : ''; // Ajustar si fecha incluye hora
        let entregable = data ? data.entregable : '';

        row.innerHTML = `
            <td><input type="text" class="form-control" name="pagos[${count}][numero]" value="${numero}" placeholder="Ej. 1er Pago"></td>
            <td><input type="text" class="form-control" name="pagos[${count}][monto]" value="${monto}" placeholder="$"></td>
            <td><input type="date" class="form-control" name="pagos[${count}][fecha]" value="${fecha}"></td>
            <td><input type="text" class="form-control" name="pagos[${count}][entregable]" value="${entregable}" placeholder="Descripción"></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="mdi mdi-trash-can"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    }
    
    // Add initial row
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('.select2').select2();

        if (pagosExistentes && pagosExistentes.length > 0) {
            pagosExistentes.forEach(pago => {
                agregarPago(pago);
            });
        } else {
            agregarPago();
        }
        
        const form = document.getElementById('form_solicitud_contrato');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const btnSubmit = form.querySelector('button[type="submit"]');
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

            fetch('<?= base_url("index.php/Principal/guardarSolicitudContrato") ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                   // alert('Solicitud guardada correctamente');
                    Swal.fire({
                        icon: 'success',
                        title: 'Solicitud guardada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.href = '<?= base_url("index.php/Principal/ListaSolicitudContrato") ?>';
                } else {
                    //alert('Error: ' + data.respuesta);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error: ' + data.respuesta,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = '<i class="mdi mdi-content-save"></i> Guardar Solicitud';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar la solicitud');
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="mdi mdi-content-save"></i> Guardar Solicitud';
            });
        });
    });
</script>
