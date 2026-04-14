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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Administración</a></li>
                                <li class="breadcrumb-item active">Solicitud de Adquisiciones</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Solicitud de Adquisiciones</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h5 class="mt-0">SOLICITUD DE  ELABORACIÓN DE CONTRATO DE ADQUISICIÓN</h5>
                                <h4 >DIRECCIÓN GENERAL JURÍDICA</h4>
                            </div>
                            
                            <form id="form_solicitud_adquisiciones" enctype="multipart/form-data">
                                <input type="hidden" name="id_solicitud_adquisiciones" value="<?= isset($solicitud) ? $solicitud->id_solicitud_adquisiciones : '' ?>">
                                
                                <!-- SECCION 1: INFORMACIÓN DEL ÁREA SOLICITANTE -->
                                <h5 class="bg-primary text-white p-2">INFORMACIÓN DEL ÁREA SOLICITANTE</h5>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Área Solicitante::</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2" name="responsable_proyecto" required>
                                            <option value="">Seleccione una opción</option>
                                            <?php if(isset($direccion)): foreach ($direccion as $u): ?>
                                                <option value="<?= $u->id_usuario ?>" <?= (isset($solicitud) && $solicitud->responsable_proyecto == $u->id_usuario) ? 'selected' : '' ?>>
                                                    <?= $u->nombre_completo .' - '. $u->dsc_puesto ?>
                                                </option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Fecha de solicitud:</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" name="fecha_solicitud" value="<?= isset($solicitud) && $solicitud->fecha_solicitud ? date('Y-m-d', strtotime($solicitud->fecha_solicitud)) : '' ?>" required>
                                    </div>
                                </div>

                                <!-- SECCION 1.5: INFORMACIÓN DEL CONTRATO -->
                                <h5 class="bg-primary text-white p-2 mt-4">INFORMACIÓN DEL CONTRATO</h5>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Responsable del seguimiento (SECTURI):</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2" name="responsable_seguimiento" required>
                                            <option value="">Seleccione una opción</option>
                                            <?php if(isset($usuario)): foreach ($usuario as $u): ?>
                                                <option value="<?= $u->id_usuario ?>" <?= (isset($solicitud) && $solicitud->responsable_seguimiento == $u->id_usuario) ? 'selected' : '' ?>>
                                                     <?= $u->nombre_completo .' - '. $u->dsc_puesto ?>
                                                </option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Vigencia:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="vigencia" value="<?= isset($solicitud) ? (isset($solicitud->vigencia) ? $solicitud->vigencia : '') : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Objeto de Adquisición:</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="objeto_adquisicion" rows="3"><?= isset($solicitud) ? (isset($solicitud->objeto_adquisicion) ? $solicitud->objeto_adquisicion : '') : '' ?></textarea>
                                    </div>
                                </div>

                                <!-- SECCION 1.6: PROCESO DE CONTRATACIÓN -->
                                <h5 class="bg-primary text-white p-2 mt-4">PROCESO DE CONTRATACIÓN</h5>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tipo de proceso:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="tipo_proceso" value="<?= isset($solicitud) ? (isset($solicitud->tipo_proceso) ? $solicitud->tipo_proceso : '') : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">No. de invitación:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="no_invitacion" value="<?= isset($solicitud) ? (isset($solicitud->no_invitacion) ? $solicitud->no_invitacion : '') : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Fecha de invitación:</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" name="fecha_invitacion" value="<?= isset($solicitud) && isset($solicitud->fecha_invitacion) && $solicitud->fecha_invitacion ? date('Y-m-d', strtotime($solicitud->fecha_invitacion)) : '' ?>">
                                    </div>
                                </div>

                                <!-- SECCION 2: INFORMACIÓN PRESUPUESTAL -->
                                <h5 class="bg-primary text-white p-2 mt-4">INFORMACIÓN PRESUPUESTAL</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Código Programático</th>
                                                <th>Fondo</th>
                                                <th>Número de Partida</th>
                                                <th>Nombre de la Partida</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" class="form-control" name="codigo_programatico" value="<?= isset($solicitud) ? (isset($solicitud->codigo_programatico) ? $solicitud->codigo_programatico : '') : '' ?>"></td>
                                                <td><input type="text" class="form-control" name="fondo" value="<?= isset($solicitud) ? (isset($solicitud->fondo) ? $solicitud->fondo : '') : '' ?>"></td>
                                                <td>
                                                    <select class="form-control select2" name="numero_partida" required>
                                                        <option value="">Seleccione una opción</option>
                                                        <?php if(isset($cat_partida)): foreach ($cat_partida as $u): ?>
                                                            <option value="<?= $u->id_partida ?>" <?= (isset($solicitud) && $solicitud->numero_partida == $u->id_partida) ? 'selected' : '' ?>>
                                                                <?= $u->cuenta_cable ?>
                                                            </option>
                                                        <?php endforeach; endif; ?>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="nombre_partida" value="<?= isset($solicitud) ? (isset($solicitud->nombre_partida) ? $solicitud->nombre_partida : '') : '' ?>"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <h5 class="bg-primary text-white p-2 mt-4">PAGOS</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="tabla_pagos">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Monto</th>
                                                <th>Letra</th>
                                                <th style="width:50px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Dynamic rows -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-right">
                                                    <button type="button" class="btn btn-secondary btn-sm" onclick="agregarPago()">+ Agregar Pago</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- SECCION 3: DESCRIPCIÓN DEL BIEN O SERVICIO -->
                                <h5 class="bg-primary text-white p-2 mt-4">DESCRIPCIÓN DE LOS BIENES O SERVICIOS A ADQUIRIR</h5>
                                <div class="form-group">
                                    <label>Descripción detallada:</label>
                                    <textarea class="form-control" name="descripcion_bienes" rows="4" required><?= isset($solicitud) ? $solicitud->descripcion_bienes : '' ?></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Fecha sugerida de entrega/inicio:</label>
                                        <input type="date" class="form-control" name="fecha_inicio" value="<?= isset($solicitud) ? date('Y-m-d', strtotime($solicitud->fecha_inicio)) : '' ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Lugar de entrega:</label>
                                        <input type="text" class="form-control" name="lugar_entrega" value="<?= isset($solicitud) ? $solicitud->lugar_entrega : '' ?>" required>
                                    </div>
                                </div>

                                <!-- SECCION 4: INFORMACIÓN DEL PROVEEDOR -->
                                <h5 class="bg-primary text-white p-2 mt-4">INFORMACIÓN DEL PROVEEDOR</h5>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre/Razón Social:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_nombre" value="<?= isset($solicitud) ? $solicitud->proveedor_nombre : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre Comercial:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_comercial" value="<?= isset($solicitud) ? (isset($solicitud->proveedor_comercial) ? $solicitud->proveedor_comercial : '') : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Núm. de Registro de Padrón de Proveedores:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_cedula" value="<?= isset($solicitud) ? $solicitud->proveedor_cedula : '' ?>">
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
                                    <label class="col-sm-3 col-form-label">Nombre del Representante Legal:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_representante" value="<?= isset($solicitud) ? $solicitud->proveedor_representante : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Responsable de Seguimiento:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proveedor_seguimiento" value="<?= isset($solicitud) ? $solicitud->proveedor_seguimiento : '' ?>">
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

    const pagosExistentes = <?= isset($pagos) ? json_encode($pagos) : '[]' ?>;

    function agregarPago(data = null) {
        const tbody = document.querySelector('#tabla_pagos tbody');
        const count = tbody.children.length + 1;
        const row = document.createElement('tr');
        
        let numero = data ? data.numero_pago : `${count}º Pago`;
        let monto = data ? data.monto : '';
        
        row.innerHTML = `
            <td><input type="text" class="form-control" name="pagos[${count}][numero]" value="${numero}" placeholder="Ej. 1er Pago"></td>
            <td><input type="text" class="form-control pago-monto" name="pagos[${count}][monto]" value="${monto}" placeholder="$"></td>
            <td><input type="text" class="form-control pago-letra" readonly placeholder="Monto en letra"></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="mdi mdi-trash-can"></i></button>
            </td>
        `;
        tbody.appendChild(row);
        
        if (monto) {
            $(row).find('.pago-monto').trigger('input');
        }
    }

    $(document).ready(function() {
        $(document).on('input', '.pago-monto', function() {
            var valor = $(this).val();
            var inputLetras = $(this).closest('tr').find('.pago-letra');
            if (isNaN(valor) || valor.trim() === '') {
                 inputLetras.val(valor.trim() !== '' ? 'NUMERO NO LEGIBLE' : '');
            } else {
                inputLetras.val(numeroALetras(parseFloat(valor)));
            }
        });

        if (pagosExistentes && pagosExistentes.length > 0) {
            pagosExistentes.forEach(pago => agregarPago(pago));
        } else {
            agregarPago();
        }

        // Initialize Select2
        $('.select2').select2();

        $('#form_solicitud_adquisiciones').on('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            var btnSubmit = $(this).find('button[type="submit"]');
            
            btnSubmit.prop('disabled', true);
            btnSubmit.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            $.ajax({
                url: '<?= base_url("index.php/Principal/guardarSolicitudAdquisiciones") ?>',
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
                            window.location.href = '<?= base_url("index.php/Principal/ListaSolicitudAdquisiciones") ?>';
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
</script>
