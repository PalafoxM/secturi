<?php $usuarios = $usuarios ?? []; ?>
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
                                <button type="button" class="btn btn-gradient-primary px-4" onclick="abrirModalEdenred()">
                                    <i class="mdi mdi-plus-circle-outline mr-2"></i>Agregar Registro
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table id="datatableEdenred" class="table table-striped table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Usuario</th>
                                            <th class="text-center">Placa</th>
                                            <th class="text-center">KM inicial</th>
                                            <th class="text-center">KM servicio</th>
                                            <th class="text-center">KM ultimo servicio</th>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Taller</th>
                                            <th class="text-center">Estatus</th>
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
                                                <td class="text-center"><?= esc($p->km_servicio ?? '') ?></td>
                                                <td class="text-center"><?= esc($p->km_ultimo_servicio ?? '') ?></td>
                                                <td class="text-center"><?= !empty($p->fecha) ? date('d/m/Y', strtotime($p->fecha)) : '' ?></td>
                                                <td><?= esc($p->taller ?? '') ?></td>
                                                <td class="text-center">
                                                    <?php if ((int) ($p->estatus ?? 0) === 1): ?>
                                                        <span class="badge badge-success">Hecho</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning">Pendiente</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-warning" title="Editar" onclick="editarEdenred(<?= (int) ($p->id_edenred ?? 0) ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <?php if ((int) ($p->estatus ?? 0) === 0): ?>
                                                        <button type="button" class="btn btn-sm btn-info" title="Marcar como hecho" onclick="edenredListo(<?= (int) ($p->id_edenred ?? 0) ?>)">
                                                            <i class="fas fa-check"></i>
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
                        <div class="form-group col-md-4">
                            <label>KM inicial</label>
                            <input type="number" step="0.01" class="form-control" name="km_inicial" id="km_inicial_edenred">
                        </div>
                        <div class="form-group col-md-4">
                            <label>KM servicio</label>
                            <input type="number" step="0.01" class="form-control" name="km_servicio" id="km_servicio_edenred">
                        </div>
                        <div class="form-group col-md-4">
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
            title: 'Marcar como hecho',
            text: 'El registro cambiara a estatus hecho.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Hecho',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                type: 'POST',
                url: base_url + 'index.php/Inicio/edenredListo',
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
