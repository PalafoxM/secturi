<div class="page-wrapper">

    <!-- Page Content-->
    <div class="page-content-tab">

        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Secturi</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">seccion</a></li>
                                <li class="breadcrumb-item active">Listado pt</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Solicitudes PT</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
     
            <!--end row-->
            <div class="row">
                <div class="col-12">
                    <div class="tab-content detail-list" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="general_detail">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                     
                                        <div class="card-body">
                                         
                                            <span>LISTA DE CONTRATOS Y CONVENIOS</span>
                                       
                                           
                                            <table id="datatableProveedores" class="table" data-toggle="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                
                                                        <th class="text-center">TIPO</th>
                                                        <th class="text-center">PROVEEDOR</th>
                                                        <th class="text-center">MONTO TOTAL</th>
                                                        <th class="text-center">NO. CONTRATO/CONVENIO</th>
                                                        <th class="text-center">NO. PROVEEDOR</th>
                                                        <th class="text-center">FECHA DE REGISTRO</th>
                                                        <th class="text-center">INSTRUMENTO FINAL</th>
                                                        <th class="text-center">ESTATUS</th>
                                                        <th class="text-center">MINISTRACION</th>
                                                    </tr>
                                                    <!--end tr-->
                                                </thead>

                                                <?php $mesesCortos = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']; ?>
                                                <tbody>
                                                    <?php foreach($contratosConveion as $p): ?>
                                                        <?php $fechaRegistro = !empty($p['fec_reg']) ? strtotime((string) $p['fec_reg']) : false; ?>
                                                        <?php $montoTotal = trim(str_replace(['$', ','], '', (string) ($p['monto_total'] ?? ''))); ?>
                                                    <tr>
                                             
                                                        <td class="text-center"><?= esc($p['tipo']) ?></td>
                                                        <td class="text-center"><?= esc($p['proveedor_seguimiento']) ?></td>
                                                        <td class="text-center">
                                                            <?= is_numeric($montoTotal) ? '$' . number_format((float) $montoTotal, 2, '.', ',') : esc($p['monto_total'] ?? '') ?>
                                                        </td>
                                                        <td class="text-center"><?= esc($p['no_contrato_convenio']) ?></td>
                                                        <td class="text-center"><?= esc($p['no_proveedor']) ?></td>
                                                        <td class="text-center">
                                                            <?= $fechaRegistro !== false
                                                                ? date('d', $fechaRegistro) . ' ' . $mesesCortos[(int) date('n', $fechaRegistro) - 1] . ' ' . date('Y', $fechaRegistro)
                                                                : 'Sin fecha' ?>
                                                        </td>
                                                        <td class="text-center">      
                                                            
                                                            <?php if ($p['ok'] != 2): ?>
                                                            <button
                                                                id="subir-instrumento-<?= (int) $p['id'] ?>-<?= esc($p['tipo'], 'attr') ?>"
                                                                type="button"
                                                                class="btn btn-primary btn-sm"
                                                                onclick="subirArchivo(<?= (int) $p['id'] ?>, '<?= esc($p['tipo'], 'attr') ?>')">
                                                                <?= empty($p['tiene_instrumento_final']) ? 'Subir' : 'Reemplazar instrumento final' ?>
                                                            </button>
                                                            <button
                                                                id="revisar-instrumento-<?= (int) $p['id'] ?>-<?= esc($p['tipo'], 'attr') ?>"
                                                                type="button"
                                                                onclick="abrirModalRevision(<?= (int) $p['id'] ?>, '<?= esc($p['tipo'], 'attr') ?>')"
                                                                class="btn btn-success btn-sm<?= empty($p['tiene_instrumento_final']) ? ' d-none' : '' ?>">
                                                                Revisar
                                                            </button>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center">      
                                                            <?php if ($p['ok'] == 1): ?>
                                                                <div class="spinner-grow-sm spinner-grow text-primary" role="status"></div>
                                                                <span class="badge badge-boxed  badge-soft-primary">Registrado</span>
                                                            <?php endif; ?>
                                                            <?php if ($p['ok'] == 2): ?>
                                                                <div class="spinner-grow-sm spinner-grow text-warning" role="status"></div>
                                                                <span class="badge badge-boxed  badge-soft-warning">En revisión</span>
                                                            <?php endif; ?>
                                                            <?php if ($p['ok'] == 3): ?>
                                                                <div class="spinner-grow-sm spinner-grow text-success" role="status"></div>
                                                                <span class="badge badge-boxed  badge-soft-success">Aprobado</span>
                                                            <?php endif; ?>
                                                            <?php if ($p['ok'] == 4): ?>
                                                                <div class="spinner-grow-sm spinner-grow text-danger" role="status"></div>
                                                                <span class="badge badge-boxed  badge-soft-danger">Rechazado</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center">  
                                                            <?php if($p['ok'] == 3): ?>
                                                              
                                                            <div class="dropdown">
                                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Pagos <i class="mdi mdi-chevron-down"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <?php foreach ($p['pagos'] as $pago): ?>
                                                                        <a target="_blank" href="<?= base_url('/index.php/Principal/generarFormatoPTJ/' . (int) $p['reserva']). '/' . (isset($pago->id_solicitud_contrato_pagos) && !empty($pago->id_solicitud_contrato_pagos) ? (int) $pago->id_solicitud_contrato_pagos : $pago->id_pago) ?>" >
                                                                        <span class="dropdown-item" title="<?= esc($pago->monto) ?>"><?= esc($pago->numero_pago) ?></span>
                                                                        </a>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>
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
        </div><!-- container -->



    </div>
    <!-- end page content -->
</div>

<style>
    #modalRevisionReserva .modal-dialog {
        width: 96%;
        max-width: 1380px;
    }

    #modalRevisionReserva .modal-content {
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 32px);
        overflow: hidden;
        border: 0;
        border-radius: 14px;
        box-shadow: 0 24px 70px rgba(29, 45, 82, 0.24);
    }

    #modalRevisionReserva .modal-header,
    #modalRevisionReserva .modal-footer {
        flex: 0 0 auto;
        background: #fff;
    }

    #modalRevisionReserva .modal-content > form {
        display: flex;
        flex: 1 1 auto;
        flex-direction: column;
        min-height: 0;
        overflow: hidden;
    }

    #modalRevisionReserva .modal-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        padding: 26px 30px;
    }

    #modalRevisionReserva .modal-footer {
        position: relative;
        z-index: 5;
        margin: 0;
        box-shadow: 0 -8px 20px rgba(42, 62, 103, 0.08);
    }

    #modalRevisionReserva .reserva-seccion {
        padding: 22px;
        border: 1px solid #e5eaf3;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(42, 62, 103, 0.06);
    }

    #modalRevisionReserva .reserva-seccion-title {
        margin-bottom: 18px;
        color: #5269b8;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
    }

    #modalRevisionReserva .form-control[readonly] {
        color: #35456a;
        background: #f5f7fb;
    }

    #modalRevisionReserva .table th {
        white-space: nowrap;
        color: #273b67;
        background: #edf2fa;
    }

    #modalRevisionReserva .table td {
        vertical-align: middle;
    }

    #modalRevisionReserva .reserva-total {
        min-width: 230px;
        color: #20345f;
        background: #edf2fa;
        font-size: 17px;
        font-weight: 700;
        text-align: right;
    }

    @media (max-width: 767px) {
        #modalRevisionReserva .modal-body {
            padding: 18px;
        }
    }
</style>

<div class="modal fade" id="modalRevisionReserva" tabindex="-1" role="dialog" aria-labelledby="modalRevisionReservaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4 class="modal-title mb-1" id="modalRevisionReservaLabel">Reserva PT</h4>
                    <small class="text-muted">Los datos jurídicos y presupuestales se cargan desde la solicitud autorizada.</small>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formReservaSolicitudPT">
                <input type="hidden" id="reserva_solicitud_id" name="id_solicitud">
                <input type="hidden" id="reserva_solicitud_tipo" name="tipo">

                <div class="modal-body">
                    <div id="reserva_modal_loading" class="text-center py-5">
                        <span class="spinner-border text-primary" role="status" aria-hidden="true"></span>
                        <p class="mt-3 mb-0">Cargando información de la solicitud...</p>
                    </div>

                    <div id="reserva_modal_content" class="d-none">
                        <section class="reserva-seccion mb-4">
                            <h5 class="reserva-seccion-title">Datos del proveedor</h5>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="reserva_nombre_proveedor">Nombre del proveedor</label>
                                        <input type="text" class="form-control" id="reserva_nombre_proveedor" readonly>
                                    </div>
                                    <div class="form-group mb-lg-0">
                                        <label for="reserva_banco">Banco <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="reserva_banco" name="id_proveedor_banco" required>
                                            <option value="">Seleccione una opción</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="reserva_no_proveedor">No. proveedor</label>
                                        <input type="text" class="form-control" id="reserva_no_proveedor" readonly>
                                    </div>
                                    <div class="form-group mb-lg-0">
                                        <label for="reserva_numero_instrumento" id="reserva_numero_label">Contrato / Convenio</label>
                                        <input type="text" class="form-control" id="reserva_numero_instrumento" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-6">
                                    <label>Instrumento jurídico final</label>
                                    <div>
                                        <a id="reserva_instrumento_link" class="btn btn-outline-danger" href="#" target="_blank" rel="noopener noreferrer">
                                            <i class="fas fa-file-pdf mr-1"></i> Ver instrumento PDF
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="comentarios_instrumento">Comentarios</label>
                                    <textarea class="form-control" id="comentarios_instrumento" name="comentarios_instrumento"
                                        rows="3" maxlength="5000" placeholder="Escribe los comentarios de la reserva"></textarea>
                                </div>
                            </div>
                        </section>

                        <section class="reserva-seccion">
                            <h5 class="reserva-seccion-title">Presupuesto</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="tablaPresupuestoReserva">
                                    <thead>
                                        <tr>
                                            <th>Proyecto-meta</th>
                                            <th>Partida</th>
                                            <th style="min-width: 230px;">Fondo <span class="text-danger">*</span></th>
                                            <th style="min-width: 160px;">Importe</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end align-items-center mt-3">
                                <label class="mr-3 mb-0 font-weight-bold">TOTAL:</label>
                                <input type="text" id="reserva_total" class="form-control reserva-total" readonly>
                            </div>
                        </section>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnGuardarReservaSolicitud" class="btn btn-success" disabled>
                        <i class="fas fa-save mr-1"></i> Guardar reserva
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<link href="<?= base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url(); ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>plugins/select2/select2.min.js"></script>
<script>
    function escaparHtml(valor) {
        return String(valor ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatearMoneda(valor) {
        return new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN',
            minimumFractionDigits: 2
        }).format(Number(valor) || 0);
    }

    function limpiarModalRevision() {
        if ($.fn.select2) {
            $('#reserva_banco.select2-hidden-accessible').select2('destroy');
            $('#tablaPresupuestoReserva .reserva-fondo-select.select2-hidden-accessible').select2('destroy');
        }

        $('#formReservaSolicitudPT')[0].reset();
        $('#tablaPresupuestoReserva tbody').empty();
        $('#reserva_banco').html('<option value="">Seleccione una opción</option>');
        $('#reserva_modal_loading').removeClass('d-none');
        $('#reserva_modal_content').addClass('d-none');
        $('#btnGuardarReservaSolicitud').prop('disabled', true);
    }

    function inicializarSelectoresReserva() {
        if (!$.fn.select2) {
            return;
        }

        $('#reserva_banco').select2({
            width: '100%',
            placeholder: 'Seleccione una opción',
            dropdownParent: $('#modalRevisionReserva')
        });
        $('#tablaPresupuestoReserva .reserva-fondo-select').select2({
            width: '100%',
            placeholder: 'Seleccione un fondo',
            dropdownParent: $('#modalRevisionReserva')
        });
    }

    function abrirModalRevision(id, tipo) {
        limpiarModalRevision();
        $('#modalRevisionReserva').modal('show');

        $.ajax({
            url: '<?= base_url("index.php/Principal/obtenerDatosReservaSolicitudPT") ?>',
            type: 'POST',
            dataType: 'json',
            data: { id_solicitud: id, tipo: tipo },
            success: function(response) {
                if (response.error || !response.data) {
                    $('#modalRevisionReserva').modal('hide');
                    Swal.fire('Error', response.respuesta || 'No fue posible cargar la solicitud.', 'error');
                    return;
                }

                const data = response.data;
                $('#reserva_solicitud_id').val(data.id_solicitud);
                $('#reserva_solicitud_tipo').val(data.tipo);
                $('#reserva_nombre_proveedor').val(data.proveedor.nombre || '');
                $('#reserva_no_proveedor').val(data.proveedor.no_proveedor || '');
                $('#reserva_numero_label').text(data.tipo === 'contrato' ? 'No. contrato' : 'No. convenio');
                $('#reserva_numero_instrumento').val(data.numero_instrumento || '');
                $('#reserva_instrumento_link').attr('href', data.instrumento_url || '#');

                const $banco = $('#reserva_banco');
                (data.bancos || []).forEach(function(item) {
                    const detalles = [item.banco, item.no_cuenta, item.clabe].filter(Boolean).join(' · ');
                    $banco.append(new Option(detalles, item.id));
                });

                const fondos = data.fondos || [];
                let total = 0;
                const $tbody = $('#tablaPresupuestoReserva tbody');
                (data.presupuestos || []).forEach(function(presupuesto, indice) {
                    total += Number(presupuesto.importe) || 0;
                    const opcionesFondo = ['<option value="">Seleccione una opción</option>']
                        .concat(fondos.map(function(fondo) {
                            return '<option value="' + escaparHtml(fondo) + '">' + escaparHtml(fondo) + '</option>';
                        }))
                        .join('');

                    $tbody.append(
                        '<tr data-indice="' + indice + '">' +
                            '<td><input type="text" class="form-control" value="' + escaparHtml(presupuesto.proyecto) + '" readonly></td>' +
                            '<td><input type="text" class="form-control" value="' + escaparHtml(presupuesto.partida) + '" readonly></td>' +
                            '<td>' +
                                '<select class="form-control reserva-fondo-select" required>' + opcionesFondo + '</select>' +
                                '<div class="custom-control custom-checkbox mt-2">' +
                                    '<input type="checkbox" class="custom-control-input fondo-manual-check" id="fondo_manual_revision_' + indice + '">' +
                                    '<label class="custom-control-label small" for="fondo_manual_revision_' + indice + '">No encuentro el fondo</label>' +
                                '</div>' +
                                '<input type="text" class="form-control mt-2 d-none fondo-manual-input" maxlength="10" ' +
                                    'inputmode="numeric" pattern="[0-9]{10}" placeholder="Fondo de 10 dígitos" disabled>' +
                            '</td>' +
                            '<td><input type="text" class="form-control text-right" value="' + escaparHtml(formatearMoneda(presupuesto.importe)) + '" readonly></td>' +
                        '</tr>'
                    );
                });

                $('#reserva_total').val(formatearMoneda(total));
                $('#reserva_modal_loading').addClass('d-none');
                $('#reserva_modal_content').removeClass('d-none');
                inicializarSelectoresReserva();

                const puedeGuardar = (data.bancos || []).length > 0
                    && (data.presupuestos || []).length > 0;
                $('#btnGuardarReservaSolicitud').prop('disabled', !puedeGuardar);

                if (!(data.bancos || []).length) {
                    Swal.fire('Atención', 'El proveedor no tiene cuentas bancarias registradas.', 'warning');
                } else if (!fondos.length) {
                    Swal.fire('Atención', 'No existen fondos en el catálogo. Activa “No encuentro el fondo” para capturarlo manualmente.', 'info');
                }
            },
            error: function() {
                $('#modalRevisionReserva').modal('hide');
                Swal.fire('Error', 'No fue posible consultar la información de la solicitud.', 'error');
            }
        });
    }

    function subirArchivo(id, tipo) {
        const urlSubida = '<?= base_url("index.php/Principal/subirInstrumentoJuridicoFinalPT") ?>';
        const limiteBytes = 500 * 1024 * 1024;

        Swal.fire({
            title: 'Subir instrumento jurídico final',
            html: '<p class="mb-2">Selecciona un único archivo PDF menor a 500 MB.</p>',
            input: 'file',
            inputAttributes: {
                accept: '.pdf,application/pdf',
                'aria-label': 'Selecciona el instrumento jurídico final en PDF'
            },
            showCancelButton: true,
            confirmButtonText: 'Subir',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: (seleccion) => {
                const archivo = seleccion instanceof FileList ? seleccion.item(0) : seleccion;

                if (!archivo) {
                    Swal.showValidationMessage('Selecciona el archivo PDF.');
                    return false;
                }

                const extension = archivo.name.split('.').pop().toLowerCase();
                if (extension !== 'pdf') {
                    Swal.showValidationMessage('Solo se permiten archivos en formato PDF.');
                    return false;
                }

                if (archivo.size <= 0 || archivo.size >= limiteBytes) {
                    Swal.showValidationMessage('El PDF debe pesar menos de 500 MB.');
                    return false;
                }

                const formData = new FormData();
                formData.append('id_solicitud', id);
                formData.append('tipo', tipo);
                formData.append('archivo', archivo, archivo.name);

                return fetch(urlSubida, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(async (response) => {
                        const data = await response.json().catch(() => null);
                        if (!response.ok || !data || data.error) {
                            Swal.showValidationMessage(
                                data && data.respuesta
                                    ? data.respuesta
                                    : 'No se pudo guardar el instrumento jurídico final.'
                            );
                            return false;
                        }
                        return data;
                    })
                    .catch(() => {
                        Swal.showValidationMessage('No se pudo completar la carga del archivo.');
                        return false;
                    });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((resultado) => {
            if (!resultado.isConfirmed) {
                return;
            }
            if (!resultado.value || resultado.value.error) {
                return;
            }

            $('#revisar-instrumento-' + id + '-' + tipo).removeClass('d-none');
            $('#subir-instrumento-' + id + '-' + tipo).text('Reemplazar instrumento final');
            Swal.fire('Listo', resultado.value.respuesta, 'success');
        });
    }

    $(function () {
        $('#tablaPresupuestoReserva').on('change', '.fondo-manual-check', function() {
            const $fila = $(this).closest('tr');
            const $selector = $fila.find('.reserva-fondo-select');
            const $capturaManual = $fila.find('.fondo-manual-input');
            const esManual = $(this).is(':checked');

            if (esManual) {
                if ($.fn.select2 && $selector.hasClass('select2-hidden-accessible')) {
                    $selector.select2('destroy');
                }
                $selector.val('').prop('disabled', true).prop('required', false).addClass('d-none');
                $capturaManual.prop('disabled', false).prop('required', true).removeClass('d-none').focus();
                return;
            }

            $capturaManual.val('').prop('disabled', true).prop('required', false).addClass('d-none');
            $selector.prop('disabled', false).prop('required', true).removeClass('d-none');
            if ($.fn.select2) {
                $selector.select2({
                    width: '100%',
                    placeholder: 'Seleccione un fondo',
                    dropdownParent: $('#modalRevisionReserva')
                });
            }
        });

        $('#tablaPresupuestoReserva').on('input', '.fondo-manual-input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });

        $('#formReservaSolicitudPT').on('submit', function(event) {
            event.preventDefault();

            const idSolicitud = $('#reserva_solicitud_id').val();
            const tipo = $('#reserva_solicitud_tipo').val();
            const idBanco = $('#reserva_banco').val();
            const comentariosInstrumento = $.trim($('#comentarios_instrumento').val() || '');
            const fondos = $('#tablaPresupuestoReserva tbody tr').map(function() {
                const $fila = $(this);
                return $fila.find('.fondo-manual-check').is(':checked')
                    ? $.trim($fila.find('.fondo-manual-input').val() || '')
                    : $.trim($fila.find('.reserva-fondo-select').val() || '');
            }).get();

            if (!idBanco) {
                Swal.fire('Atención', 'Selecciona el banco del proveedor.', 'warning');
                return;
            }
            if (!fondos.length || fondos.some(function(fondo) { return !/^\d{10}$/.test(fondo); })) {
                Swal.fire('Atención', 'Selecciona un fondo válido para cada presupuesto.', 'warning');
                return;
            }

            const $boton = $('#btnGuardarReservaSolicitud');
            const textoOriginal = $boton.html();
            $boton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...');

            $.ajax({
                url: '<?= base_url("index.php/Principal/guardarReservaDesdeSolicitudPT") ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id_solicitud: idSolicitud,
                    tipo: tipo,
                    id_proveedor_banco: idBanco,
                    fondos: fondos,
                    comentarios_instrumento: comentariosInstrumento
                },
                success: function(response) {
                    if (response.error) {
                        Swal.fire('Error', response.respuesta || 'No fue posible guardar la reserva.', 'error');
                        return;
                    }

                    $('#modalRevisionReserva').modal('hide');
                    Swal.fire('Correcto', response.respuesta, 'success').then(function() {
                      
                        window.location.reload();
                    });
                },
                error: function() {
                    Swal.fire('Error', 'No fue posible guardar la reserva.', 'error');
                },
                complete: function() {
                    $boton.prop('disabled', false).html(textoOriginal);
                }
            });
        });

        $('#datatableProveedores').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: 'Buscar:',
                lengthMenu: 'Mostrar _MENU_ registros',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty: 'Mostrando 0 a 0 de 0 registros',
                infoFiltered: '(filtrado de _MAX_ registros totales)',
                zeroRecords: 'No se encontraron registros',
                emptyTable: 'No hay registros disponibles',
                paginate: {
                    first: 'Primero',
                    last: 'Último',
                    next: 'Siguiente',
                    previous: 'Anterior'
                }
            }
        });
    });
</script>
