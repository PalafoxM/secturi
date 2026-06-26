<?php
$usuarios = $usuarios ?? [];
$normalizarKm = static function ($valor): ?float {
    if ($valor === null || $valor === '') {
        return null;
    }

    $valor = preg_replace('/[^\d.-]/', '', (string) $valor);

    return is_numeric($valor) ? (float) $valor : null;
};
?>
<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Secturi</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Seccion</a></li>
                                <li class="breadcrumb-item active">Edenred</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado Edenred</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="font-weight-bold">EDENRED</span>
                                <div>
                                    <button type="button" class="btn btn-gradient-warning px-4 mr-2" data-toggle="modal" data-target="#modalExcelEdenred">
                                        <i class="mdi mdi-file-excel mr-2"></i>Subir Excel
                                    </button>
                                    <button type="button" class="btn btn-gradient-primary px-4" onclick="abrirModalEdenred()">
                                        <i class="mdi mdi-plus-circle-outline mr-2"></i>Agregar Registro
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="datatableEdenred" class="table table-striped table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Usuario</th>
                                            <th class="text-center">Placa</th>
                                            <th class="text-center">KM inicial</th>
                                            <th class="text-center">KM final</th>
                                            <th class="text-center">KM ultimo servicio</th>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Taller</th>
                                            <th class="text-center">Consumo</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (($edenred ?? []) as $p): ?>
                                            <tr>
                                                <td class="text-center"><?= esc($p->id_edenred ?? '') ?></td>
                                                <td><?= esc($p->nombre_completo ?? '') ?></td>
                                                <td class="text-center"><?= esc($p->placa ?? '') ?></td>
                                                <td class="text-center"><?= esc($p->km_inicial ?? '') ?></td>
                                                <td class="text-center"><?= esc($p->km_final ?? '') ?></td>
                                                <td class="text-center"><?= esc($p->km_ultimo_servicio ?? '') ?></td>
                                                <td class="text-center"><?= !empty($p->fecha) ? date('d/m/Y', strtotime($p->fecha)) : '' ?></td>
                                                <td><?= esc($p->taller ?? '') ?></td>
                                                <td class="text-center">
                                                    <?php
                                                        $consumo = '';
                                                        $consumo_real = null;
                                                        $kmFinal = $normalizarKm($p->km_final ?? null);
                                                        $kmUltimoServicio = $normalizarKm($p->km_ultimo_servicio ?? null);

                                                        if ($kmFinal !== null && $kmUltimoServicio !== null) {
                                                            $consumo_real = $kmFinal - $kmUltimoServicio;
                                                            $consumo = number_format($consumo_real, 2);
                                                        }

                                                        if ($consumo_real !== null && $consumo_real <= 5000) {
                                                            echo '<span class="badge badge-success">' . $consumo . '</span>';
                                                        } elseif ($consumo_real > 5001 && $consumo_real <= 9000) {
                                                            echo '<span class="badge badge-warning">' . $consumo . '</span>';
                                                        } elseif ($consumo_real > 9001) {
                                                            echo '<span class="badge badge-danger">' . $consumo . '</span>';
                                                        }
                                                    
                                                    
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-warning" title="Editar" onclick="editarEdenred(<?= (int) ($p->id_edenred ?? 0) ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <?php if ($consumo_real !== null && $consumo_real > 9001): ?>
                                                        <button type="button" class="btn btn-sm btn-info" title="Enviar Correo" onclick="edenredListo(<?= (int) ($p->id_edenred ?? 0) ?>)">
                                                            <i class="mdi mdi-email-outline"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarEdenred(<?= (int) ($p->id_edenred ?? 0) ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdenred" tabindex="-1" role="dialog" aria-labelledby="modalEdenredLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formEdenred">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEdenredLabel">Registro Edenred</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_edenred" id="id_edenred">
                    <input type="hidden" name="editar" id="editar_edenred" value="0">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Usuario</label>
                            <select class="form-control select2-edenred" name="id_usuario" id="id_usuario_edenred" required>
                                <option value="">Seleccione una opcion</option>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?= esc($usuario->id_usuario ?? '', 'attr') ?>">
                                        <?= esc(trim(($usuario->nombre_completo ?? '') . (!empty($usuario->dsc_puesto) ? ' - ' . $usuario->dsc_puesto : ''))) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Placa</label>
                            <input type="text" class="form-control" name="placa" id="placa_edenred" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>KM inicial</label>
                            <input type="number" step="0.01" class="form-control" name="km_inicial" id="km_inicial_edenred">
                        </div>
                        <div class="form-group col-md-6">
                            <label>KM final</label>
                            <input type="number" step="0.01" class="form-control" name="km_final" id="km_final_edenred">
                        </div>
                    </div>
                    <div class="form-row">
                      
                        <div class="form-group col-md-6">
                            <label>KM servicio</label>
                            <input type="number" step="0.01" class="form-control" name="km_servicio" id="km_servicio_edenred">
                        </div>
                        <div class="form-group col-md-6">
                            <label>KM ultimo servicio</label>
                            <input type="number" step="0.01" class="form-control" name="km_ultimo_servicio" id="km_ultimo_servicio_edenred">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Fecha</label>
                            <input type="date" class="form-control" name="fecha" id="fecha_edenred" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Taller</label>
                            <input type="text" class="form-control" name="taller" id="taller_edenred">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Estatus</label>
                            <select class="form-control" name="estatus" id="estatus_edenred">
                                <option value="0">Pendiente</option>
                                <option value="1">Hecho</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarEdenred">
                        <i class="mdi mdi-content-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalExcelEdenred" tabindex="-1" role="dialog" aria-labelledby="modalExcelEdenredLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formExcelEdenred" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExcelEdenredLabel">Subir Excel Edenred</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Archivo .xlsx</label>
                        <input type="file" class="form-control" name="archivo_edenred" id="archivo_edenred" accept=".xlsx" required>
                    </div>
                    <small class="text-muted">
                        Se procesa desde la fila 8 y se leen las columnas Nombre, Km Ant Transaccion, Km Transaccion y Placa.
                    </small>
                    <div id="resultadoExcelEdenred" class="alert alert-info mt-3 d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning" id="btnSubirExcelEdenred">
                        <i class="mdi mdi-upload"></i> Procesar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<link href="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>plugins/select2/select2.min.js"></script>
<link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />

<script>
    function limpiarFormEdenred() {
        $('#formEdenred')[0].reset();
        $('#id_edenred').val('');
        $('#editar_edenred').val(0);
        $('#id_usuario_edenred').val('').trigger('change');
        $('#estatus_edenred').val(0);
    }

    function abrirModalEdenred() {
        limpiarFormEdenred();
        $('#modalEdenredLabel').text('Agregar Registro Edenred');
        $('#modalEdenred').modal('show');
    }

    function editarEdenred(idEdenred) {
        limpiarFormEdenred();
        $.ajax({
            type: 'POST',
            url: base_url + 'index.php/Inicio/getEdenred',
            dataType: 'json',
            data: { id_edenred: idEdenred },
            success: function(response) {
                if (response.error) {
                    Swal.fire('Error', response.respuesta, 'error');
                    return;
                }

                const data = response.data || {};
                $('#modalEdenredLabel').text('Editar Registro Edenred');
                $('#id_edenred').val(data.id_edenred || idEdenred);
                $('#editar_edenred').val(1);
                $('#id_usuario_edenred').val(data.id_usuario || '').trigger('change');
                $('#placa_edenred').val(data.placa || '');
                $('#km_inicial_edenred').val(data.km_inicial || '');
                $('#km_final_edenred').val(data.km_final || '');
                $('#km_servicio_edenred').val(data.km_servicio || '');
                $('#km_ultimo_servicio_edenred').val(data.km_ultimo_servicio || '');
                $('#fecha_edenred').val(data.fecha ? String(data.fecha).substring(0, 10) : '');
                $('#taller_edenred').val(data.taller || '');
                $('#estatus_edenred').val(data.estatus || 0);
                $('#modalEdenred').modal('show');
            },
            error: function() {
                Swal.fire('Error', 'No fue posible consultar el registro.', 'error');
            }
        });
    }

    function eliminarEdenred(idEdenred) {
        Swal.fire({
            title: 'Eliminar registro',
            text: 'Esta accion eliminara el registro de Edenred.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                type: 'POST',
                url: base_url + 'index.php/Inicio/deleteEdenred',
                dataType: 'json',
                data: { id_edenred: idEdenred },
                success: function(response) {
                    if (response.error) {
                        Swal.fire('Error', response.respuesta, 'error');
                        return;
                    }
                    Swal.fire('Correcto', response.respuesta, 'success').then(() => window.location.reload());
                },
                error: function() {
                    Swal.fire('Error', 'No fue posible eliminar el registro.', 'error');
                }
            });
        });
    }

    function edenredListo(idEdenred) {
        Swal.fire({
            title: 'Enviar Correo',
            text: 'Ingrese el correo electrónico para enviar el registro',
            icon: 'info',
            input: 'email',
            inputPlaceholder: 'ejemplo@guanajuato.gob.mx',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Enviar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value) {
                    return 'Debe ingresar un correo electrónico'
                }
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    return 'Por favor ingrese un correo válido'
                }
            }
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                type: 'POST',
                url: base_url + 'index.php/Inicio/edenredListo',
                dataType: 'json',
                data: { 
                    id_edenred: idEdenred,
                    correo: result.value
                },
                success: function(response) {
                    if (response.error) {
                        Swal.fire('Error', response.respuesta, 'error');
                        return;
                    }
                    Swal.fire('Correcto', response.respuesta, 'success').then(() => window.location.reload());
                },
                error: function() {
                    Swal.fire('Error', 'No fue posible cambiar el estatus.', 'error');
                }
            });
        });
    }

    $(document).ready(function() {
        $('#datatableEdenred').DataTable({
            order: [[0, 'desc']],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            destroy: true,
            searching: true
        });

        $('.select2-edenred').select2({
            width: '100%',
            dropdownParent: $('#modalEdenred')
        });

        $('#formExcelEdenred').on('submit', function(e) {
            e.preventDefault();
            const btn = $('#btnSubirExcelEdenred');
            const resultado = $('#resultadoExcelEdenred');
            const formData = new FormData(this);

            resultado.addClass('d-none').removeClass('alert-danger alert-success').addClass('alert-info').text('');
            btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Procesando');

            $.ajax({
                type: 'POST',
                url: base_url + 'index.php/Inicio/subirExcelEdenred',
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.error) {
                        resultado.removeClass('d-none alert-info alert-success').addClass('alert-danger').text(response.respuesta);
                        return;
                    }

                    let mensaje = response.respuesta;
                    if (response.resumen && response.resumen.placas_sin_vehiculo && response.resumen.placas_sin_vehiculo.length) {
                        mensaje += ' Placas no encontradas: ' + response.resumen.placas_sin_vehiculo.join(', ');
                    }

                    resultado.removeClass('d-none alert-info alert-danger').addClass('alert-success').text(mensaje);
                    Swal.fire('Correcto', response.respuesta, 'success').then(() => window.location.reload());
                },
                error: function() {
                    resultado.removeClass('d-none alert-info alert-success').addClass('alert-danger').text('No fue posible procesar el Excel.');
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="mdi mdi-upload"></i> Procesar');
                }
            });
        });

        $('#formEdenred').on('submit', function(e) {
            e.preventDefault();
            const btn = $('#btnGuardarEdenred');
            btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Guardando');

            $.ajax({
                type: 'POST',
                url: base_url + 'index.php/Inicio/guardarEdenred',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.error) {
                        Swal.fire('Error', response.respuesta, 'error');
                        return;
                    }
                    Swal.fire('Correcto', response.respuesta, 'success').then(() => window.location.reload());
                },
                error: function() {
                    Swal.fire('Error', 'No fue posible guardar el registro.', 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="mdi mdi-content-save"></i> Guardar');
                }
            });
        });
    });
</script>
