<?php $session = \Config\Services::session(); ?>
<!-- App favicon -->
<link rel="shortcut icon" href="<?php echo base_url() ?>assets/images/favicon.ico">

<!--calendar css-->
<link href="<?php echo base_url() ?>plugins/fullcalendar/packages/core/main.css" rel="stylesheet" />
<link href="<?php echo base_url() ?>plugins/fullcalendar/packages/daygrid/main.css" rel="stylesheet" />
<link href="<?php echo base_url() ?>plugins/fullcalendar/packages/bootstrap/main.css" rel="stylesheet" />
<link href="<?php echo base_url() ?>plugins/fullcalendar/packages/timegrid/main.css" rel="stylesheet" />
<link href="<?php echo base_url() ?>plugins/fullcalendar/packages/list/main.css" rel="stylesheet" />

<!-- App css -->
<link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

<!-- Plugins css -->
<link href="<?php echo base_url() ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
<link href="<?php echo base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet"
    type="text/css" />
<link href="<?php echo base_url() ?>plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
<link href="<?php echo base_url() ?>plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css"
    rel="stylesheet" />


<style>
    /* Añade estos estilos al bloque de estilos existente */
    .fc-event-trabajando {
        background-color: #4A90E2 !important;
        /* Azul */
        border-color: #357ABD !important;
        color: #fff !important;
        /* Texto blanco */
    }

    .fc-event-asistencia {
        border-left: 4px solid #4e73df;
        background-color: #1950f5ff;
        color: #4e73df;
    }

    .fc-event-espera {
        border-left: 4px solid #4e73df;
        background-color: #f8f9fad7;
        color: #4e73df;
    }

    .fc-event-incidencia {
        border-left: 4px solidrgb(17, 72, 236);
        background-color: rgb(233, 58, 35);
        color: #f8f9fc;
    }

    .fc-event-falta {
        border-left-color: #dc3545;
        background-color: #f8d7da;
        color: #dc3545;
    }

    .fc-event-declinado {
        border-left: 4px solidrgb(17, 72, 236);
        background-color: rgba(216, 46, 23, 1);
        color: white;
    }

    .fc-event-aprobado {
        border-left: 4px solid #4caf50;
        background-color: rgba(76, 175, 80, 0.1);
        color: #388e3c;
        border: 1px solid rgba(76, 175, 80, 0.2);
    }

    .fc-event-tarde {
        border-left-color: #e74a3b;
        background-color: #f8e0df;
        color: #e74a3b;
    }

    .fc-event-temprano {
        border-left-color: #f6c23e;
        background-color: #fbf3d9;
        color: #f6c23e;
    }

    .fc-event-puntual {
        border-left-color: #1cc88a;
        background-color: #e2f1eb;
        color: #1cc88a;
    }

    .fc-event-asueto {
        border-left-color: #9B59B6;
        /* Morado vivo */
        background-color: #F3E8F9;
        /* Morado pastel */
        color: #9B59B6;
    }

    .fc-event-title {
        font-weight: bold;
        margin-bottom: 3px;
    }

    .fc-event-details {
        font-size: 0.85em;
        line-height: 1.4;
    }
</style>

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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Asistencia</a></li>
                                <li class="breadcrumb-item active">Calendario</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Calendario</h4>
                        <a href="javascript: history.go(-1)" class="btn btn-gradient-danger">Atrás</a>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <?php if ($idTipoEmpleado == 1): ?>
                                <div id='calendar'></div>
                                <div style='clear:both'></div>
                            <?php endif; ?>
                            <?php if ($idTipoEmpleado != 1): ?>
                                <h4>
                                    <div class="text-center">Estimado(a) Usuario(a), esta sección solo lo puede visualizar
                                        el<strong> personal de base <strong> </div>
                                </h4>
                                <p>Si requieres mayor información, favor de comunicarte al Administrador del Sistema
                                <p>
                                <?php endif; ?>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->
            </div><!-- End row -->

        </div><!-- container -->
    </div>
    <!-- end page content -->
</div>
<!-- end page-wrapper -->




<!--  Modal content for the above example -->
<div class="modal modal-rightbar fade" id="modalJustificar" tabindex="-1" role="dialog"
    aria-labelledby="MetricaRightbar" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="MetricaRightbar">JUSTIFICAR POR</h5>
                <button type="button" class="btn btn-sm btn-soft-primary btn-circle btn-square" data-dismiss="modal"
                    aria-hidden="true"><i class="mdi mdi-close"></i></button>
            </div>
            <div class="modal-body">
                <!-- Nav tabs -->
                <ul class="nav nav-pills nav-justified mt-2 mb-4" role="tablist">
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link active" data-toggle="tab" href="#ActivityTab" role="tab">DIA</a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-toggle="tab" href="#TasksTab" role="tab">SEMANA</a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-toggle="tab" href="#SettingsTab" role="tab">MES</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active " id="ActivityTab" role="tabpanel">
                        <input type="hidden" id="fecha">
                        <h2 id="fecha_incidencia"> </h2>
                        <div class="slimscroll scroll-rightbar">
                            <div class="activity">
                                <div class="activity-info">
                                    <div class="activity-info-text mb-2">
                                        <label for="tipo_incidencia" class="form-label">Tipo de Incidencia</label>
                                        <select class="form-control select2" id="tipo_incidencia" data-toggle="select2">
                                            <option value="">Seleccione</option>
                                            <?php foreach ($cat_incidencia as $c): ?>
                                                <option value="<?= $c->id_incidencia ?>"><?= $c->dsc_incidencia ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="activity-info">
                                    <div class="activity-info-text mb-2">
                                        <label for="timepicker" class="form-label">Hora Inicio</label>
                                        <input class="form-control" id="timepicker_inicio" name="hora_inicio"
                                            placeholder="Inicio">
                                    </div>
                                </div>
                                <div class="activity-info">
                                    <div class="activity-info-text mb-2">
                                        <label for="timepicker" class="form-label">Hora Fin</label>
                                        <input class="form-control" id="timepicker_fin" name="hora_fin"
                                            placeholder="Fin">
                                    </div>
                                </div>
                                <div class="activity-info">
                                    <div class="activity-info-text mb-2">
                                        <label class="form-label">Detalles</label>
                                        <textarea class="form-control" id="detalles"></textarea>
                                    </div>
                                </div>
                                <div class="activity-info">
                                    <div class="activity-info-text mb-2">
                                        <label class="form-label">Comentarios adicionales</label>
                                        <textarea class="form-control" id="comentario"></textarea>
                                    </div>
                                </div>
                                <a style="color:white;" id="btn_incidencia" class="btn btn-gradient-success btn-lg"
                                    onclick="st.agregar.guardarIncidencia();" role="button">Guardar</a>
                            </div>
                            <!--end activity-->
                        </div>
                        <!--end activity-scroll-->
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane" id="TasksTab" role="tabpanel">

                        <h2 id="fecha_incidencia"> </h2>
                        <div class="slimscroll scroll-rightbar">
                            <div class="activity">

                                <div class="activity-info">
                                    <div class="activity-info-text mb-2">
                                        <label class="form-label">Semanas</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="datetimes" name="datetimes">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="dripicons-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity-info">
                                    <div class="activity-info-text mb-2">

                                        <select class="select2 form-control" id="tipo_incidencia_semana">
                                            <option value="">Tipo incidencia</option>
                                            <?php foreach ($cat_incidencia as $c): ?>
                                                <option value="<?= $c->id_incidencia ?>"><?= $c->dsc_incidencia ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="activity-info">
                                    <div class="activity-info-text mb-2">
                                        <label class="form-label">Detalles</label>
                                        <textarea class="form-control" id="detalles_semana"></textarea>
                                    </div>
                                </div>
                                <div class="activity-info">
                                    <div class="activity-info-text mb-2">
                                        <label class="form-label">Comentarios adicionales</label>
                                        <textarea class="form-control" id="comentario_semana"></textarea>
                                    </div>
                                </div>
                                <a style="color:white;" id="btn_semana" class="btn btn-gradient-success btn-lg"
                                    onclick="st.agregar.guardarIncidenciaS();" role="button">Guardar</a>
                            </div>
                            <!--end activity-->
                        </div>
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane" id="SettingsTab" role="tabpanel">
                        <div class="slimscroll scroll-rightbar">
                            <div class="activity">
                                <div class="activity-info">
                                    <div class="activity-info-text mb-2">
                                        <label for="tipo_incidencia" class="form-label">Tipo de Incidencia</label>
                                        <select class="select2 form-control" id="tipo_incidencia_mes">
                                            <option value="">Seleccione</option>
                                            <?php foreach ($cat_incidencia as $c): ?>
                                                <option value="<?= $c->id_incidencia ?>"><?= $c->dsc_incidencia ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="activity-info">
                                    <div class="activity-info-text mb-2">
                                        <label class="mb-3">Mes Inicio</label>
                                        <input type="text" class="form-control" placeholder="<?= date('d-m-Y'); ?>"
                                            id="mdate_inicio">
                                    </div>
                                </div>
                                <div class="activity-info">
                                    <div class="activity-info-text mb-2">
                                        <label class="mb-3">Mes Fin</label>
                                        <input type="text" class="form-control" placeholder="<?= date('d-m-Y'); ?>"
                                            id="mdate_fin">
                                    </div>
                                </div>
                                <div class="activity-info">
                                    <div class="activity-info-text mb-2">
                                        <label class="form-label">Detalles</label>
                                        <textarea class="form-control" id="detalles_mes"></textarea>
                                    </div>
                                </div>
                                <div class="activity-info">
                                    <div class="activity-info-text mb-2">
                                        <label class="form-label">Comentarios adicionales</label>
                                        <textarea class="form-control" id="comentario_mes"></textarea>
                                    </div>
                                </div>
                                <a style="color:white;" id="btn_incidencia" class="btn btn-gradient-success btn-lg"
                                    onclick="st.agregar.guardarIncidenciaM();" role="button">Guardar</a>
                            </div>
                            <!--end activity-->
                        </div>
                    </div>
                    <!--end tab-pane-->
                </div>
                <!--end tab-content-->
            </div>
            <!--end modal-body-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modalAsistencia" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header">
                <h5 class="modal-title">Editar Asistencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Cuerpo del modal SIMPLIFICADO -->
            <div class="modal-body p-3"> <!-- Reducir padding -->
                <form id="editarAsistencia">
                    <input type="hidden" id="id_incidencia" name="id_incidencia">

                    <!-- Tipo Incidencia -->
                    <div class="form-group">
                        <label for="tipo_incidencia_editar">Tipo Incidencia</label>
                        <select class="form-control select2" id="tipo_incidencia_editar" name="tipo_incidencia_editar">
                            <option value="">Seleccione</option>
                            <?php foreach ($cat_incidencia as $c): ?>
                                <option value="<?= $c->id_incidencia ?>"><?= $c->dsc_incidencia ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <!-- Columna Izquierda -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_inicio_asistencia">Fecha Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio_asistencia"
                                    name="fecha_inicio_asistencia">
                            </div>

                            <div class="form-group">
                                <label for="hora_inicio_asistencia">Hora Inicio</label>
                                <input type="time" class="form-control" id="hora_inicio_asistencia"
                                    name="hora_inicio_asistencia">
                            </div>

                            <div class="form-group">
                                <label for="detalle_asistencia">Detalles</label>
                                <textarea class="form-control" id="detalle_asistencia" name="detalle_asistencia"
                                    rows="3"></textarea>
                            </div>
                        </div>

                        <!-- Columna Derecha -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_fin_asistencia">Fecha Fin</label>
                                <input type="date" class="form-control" id="fecha_fin_asistencia"
                                    name="fecha_fin_asistencia">
                            </div>

                            <div class="form-group">
                                <label for="hora_fin_asistencia">Hora Fin</label>
                                <input type="time" class="form-control" id="hora_fin_asistencia"
                                    name="hora_fin_asistencia">
                            </div>

                            <div class="form-group">
                                <label for="comentario_asistencia">Comentario</label>
                                <textarea class="form-control" id="comentario_asistencia" name="comentario_asistencia"
                                    rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="editarAsistencia" class="btn btn-success">Guardar ✔️</button>
            </div>
        </div>
    </div>
</div>



<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<!-- jQuery  -->
<script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>

<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/core/main.js'></script>
<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/daygrid/main.js'></script>
<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/timegrid/main.js'></script>
<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/interaction/main.js'></script>
<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/list/main.js'></script>
<script src="<?php echo base_url() ?>plugins/apexcharts/apexcharts.min.js"></script>
<!-- Plugins js -->
<script src="<?php echo base_url() ?>plugins/moment/moment.js"></script>
<script src="<?php echo base_url() ?>plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url() ?>plugins/select2/select2.min.js"></script>
<script src="<?php echo base_url() ?>plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo base_url() ?>plugins/timepicker/bootstrap-material-datetimepicker.js"></script>
<script src="<?php echo base_url() ?>plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
<script src="<?php echo base_url() ?>plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>
<script src="<?php echo base_url() ?>assets/pages/jquery.forms-advanced.js"></script>
<!-- App js -->

<script src="<?php echo base_url() ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/metismenu.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/waves.js"></script>
<script src="<?php echo base_url() ?>assets/js/feather.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.slimscroll.min.js"></script>


<link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />
<!-- App css -->
<script>
    ini.inicio.formIncidencia();
    var eventosAsistencia = <?= json_encode($asistencia) ?>;
    var onlyAsistencias = <?= json_encode($onlyAsistencias) ?>;
    var diasFestivos = <?= json_encode($diasFestivos) ?>;


    function normalizarFecha(fecha) {
        if (!fecha) return '';

        // Si ya es solo fecha (YYYY-MM-DD)
        if (typeof fecha === 'string' && fecha.match(/^\d{4}-\d{2}-\d{2}$/)) {
            return fecha;
        }

        // Si tiene formato ISO (con T)
        if (typeof fecha === 'string' && fecha.includes('T')) {
            return fecha.split('T')[0];
        }

        // Si es un objeto Date
        if (fecha instanceof Date) {
            return fecha.toISOString().split('T')[0];
        }

        return '';
    }
    function obtenerFechaFormateada(info) {
        // Intentar diferentes métodos para obtener la fecha
        if (info.dateStr) {
            return info.dateStr; // Usar dateStr si está disponible
        }

        if (info.event.start) {
            // Formatear a YYYY-MM-DD
            const fecha = info.event.start;
            const year = fecha.getFullYear();
            const month = String(fecha.getMonth() + 1).padStart(2, '0');
            const day = String(fecha.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        if (info.event.extendedProps.fecha) {
            return info.event.extendedProps.fecha;
        }

        // Si no se puede obtener, usar la fecha actual
        console.warn('Se encuentra en proceso de validación');
        const hoy = new Date();
        return hoy.toISOString().split('T')[0];
    }
    function esHabil(date) {
        const d = date.getDay(); // 0=Dom, 6=Sáb
        return d >= 1 && d <= 5;
    }
    function esPasado(date) {
        const ahora = new Date();

        // Normalizamos "hoy" a medianoche
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);

        // Normalizamos la fecha a evaluar
        const d = new Date(date);
        d.setHours(0, 0, 0, 0);

        // Si es un día anterior → pasado
        if (d < hoy) return true;

        // Si es el mismo día y ya pasó la hora límite → también pasado
        if (d.getTime() === hoy.getTime() && ahora.getHours() >= 16) {
            return true;
        }

        // En cualquier otro caso no es pasado
        return false;
    }

    $(document).ready(function () {
        $('#tipo_incidencia').on('change', function () {
            st.agregar.validacionIncapacidad();
        });
        $('#tipo_incidencia_semana').on('change', function () {
            st.agregar.validacionIncapacidadS();
        });
        // Inicialización de timepickers
        $('input[name="datetimes"]').daterangepicker({
            startDate: moment(),   // Fecha actual
            endDate: moment(),     // Fecha actual (si solo quieres un día)
            "locale": {
                "applyLabel": "Aplicar",
                "cancelLabel": "Cancelar",
                "daysOfWeek": [
                    "Do",
                    "Lu",
                    "Ma",
                    "Mi",
                    "Ju",
                    "Vi",
                    "Sa"
                ],
                "monthNames": [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic"
                ],
            }
        });
        // Establecer moment a español globalmente
        moment.locale('es');

        $('#mdate_inicio').bootstrapMaterialDatePicker({

            cancelText: 'Cancelar',
            okText: 'Aceptar',

        });

        $('#mdate_fin').bootstrapMaterialDatePicker({

            cancelText: 'Cancelar',
            okText: 'Aceptar',

        });



        $('#timepicker_inicio').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            date: false,
            shortTime: true,
            switchOnClick: true
        });

        $('#timepicker_fin').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            date: false,
            shortTime: true,
            switchOnClick: true
        });

        var calendarEl = document.getElementById('calendar');

        // Obtener los datos de asistencia desde PHP (asegúrate de que tu controlador los pase como JSON)


        var mesSeleccionado = '<?= $mes ?>';
        var anio = '<?= $anio ?>';
        var calendarStatic = '<?= $calendarStatic ?>';

        // Agrega un cero al mes si es de un solo dígito
        var cero = (mesSeleccionado.length >= 2) ? '' : '0';

        // Construir la fecha en formato YYYY-MM-DD
        var fecha = anio + '-' + cero + mesSeleccionado + '-01';
        function dayBefore(yyyy_mm_dd) {
            if (!yyyy_mm_dd) return '';

            const d = new Date(yyyy_mm_dd + 'T00:00:00');
            d.setDate(d.getDate() - 1);
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${day}`;
        }
        function horaToSegundos(hora) {
            if (!hora || typeof hora !== 'string' || !hora.includes(':')) return null;
            const [hh, mm, ss] = hora.split(':').map(x => parseInt(x, 10) || 0);
            return hh * 3600 + mm * 60 + ss;
        }
        function justificar(info, fechaLabel, esSemana){
            console.log(info.event.extendedProps.observaciones);
            console.log(info.event.extendedProps.idEstatus);
              Swal.fire({
                    title: info.event.title,
                    html: `
                ${(info.event.extendedProps.idEstatus == 2) ? '<div style="text-center">' + info.event.extendedProps.observaciones + '</div>' : ''}
                <div style="text-align: left;">
                    <p><strong>${esSemana ? 'Semana' : 'Fecha'}:</strong> ${fechaLabel}</p>
                    <p><strong>${info.event.extendedProps.entrada ? 'Entrada' : 'Hora Inicio'}:</strong> ${info.event.extendedProps.entrada || info.event.extendedProps.hora_inicio}</p>
                    <p><strong>${info.event.extendedProps.salida ? 'Salida' : 'Hora Fin'}:</strong> ${info.event.extendedProps.salida || info.event.extendedProps.hora_fin}</p>
                </div>
                `,
                    showDenyButton: (info.event.extendedProps.idEstatus == 2) ? true : false,
                    showCancelButton: true,
                    confirmButtonText: '<i class="mdi mdi-plus-circle"></i> Agregar Incidencia',
                    denyButtonText: '<i class="mdi mdi-pencil"></i> Editar',
                    cancelButtonText: '<i class="mdi mdi-close"></i> Cerrar',
                    customClass: {
                        popup: 'swal-wide',
                        confirmButton: 'btn btn-success mx-1',
                        denyButton: 'btn btn-primary mx-1',
                        cancelButton: 'btn btn-secondary mx-1'
                    },
                    buttonsStyling: false,
                    showCloseButton: true,
                    reverseButtons: true,
                    focusConfirm: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // FUNCIÓN PARA OBTENER LA FECHA EN FORMATO CORRECTO

                        const dia = obtenerFechaFormateada(info);
                        st.agregar.justificarFalta(dia);


                    } else if (result.isDenied) {
                        st.agregar.editarRegistro(info.event.extendedProps.id_incidencia);
                    }
                });
        }
        // Procesar los datos para FullCalendar
        var eventos = eventosAsistencia.map(function (item) {
            let eventClass = 'fc-event-asistencia';
            let icon = ''; // Para el emoji
            let entrada = item.hora_inicio;
            const esSemana = item.tipo === 2;
            let salida = item.hora_fin;
            let esFestivo = item.esFestivo;
            let multiple = item.multiple;
            let idEstatus = item.id_estatus;;
            const hoy = '<?= date("Y-m-d") ?>'; // Formato YYYY-MM-DD
            const fechaNormalizada = normalizarFecha(item.fecha);

            if (fechaNormalizada === hoy) {
                if (entrada < '08:30:00') {
                    eventClass = 'fc-event-temprano';
                    item.nombre = 'Temprano';
                    icon = '😀';
                }
                if (entrada >= '08:30:00' && entrada <= '08:46:00') {
                    eventClass = 'fc-event-puntual';
                    item.nombre = 'Puntual';
                    icon = '😊';
                }
                if (entrada > '08:46:00') {
                    eventClass = 'fc-event-tarde';
                    item.nombre = 'Tarde';
                    icon = '😢';
                }
                if (entrada > '09:00:00') {
                    eventClass = 'fc-event-tarde';
                    item.nombre = 'Falta';
                    icon = '❌';
                }
                 if (!multiple && idEstatus == 1) {
                    eventClass = 'fc-event-espera';
                    item.nombre = 'Enviado';
                    icon = '✈️';
                }
                if (multiple && idEstatus == 1) {
                    eventClass = 'fc-event-espera';
                    item.nombre = 'Enviado';
                    icon = '✈️';
                }
            } else {

                if (entrada >= '08:30:00' && entrada <= '08:46:00' && !idEstatus) {
                    eventClass = 'fc-event-puntual';
                    item.nombre = 'Puntual';
                    icon = '🕣';
                }
                if (entrada >= '08:46:00' && entrada <= '09:59:00' && salida > '16:00:00') {
                    eventClass = 'fc-event-tarde';
                    item.nombre = 'Tarde';
                    icon = '⏳';
                }
                if (!multiple && entrada < '08:30:00' && salida > '16:00:00') {
                    eventClass = 'fc-event-temprano';
                    item.nombre = 'Temprano';
                    icon = '✅';
                }
                if (entrada >= '08:30:00' && entrada <= '08:46:00' && salida > '16:00:00') {
                    eventClass = 'fc-event-puntual';
                    item.nombre = 'Puntual';
                    icon = '🕣';
                }
                if (entrada <= '09:00:00' && salida < '16:00:00') {
                    eventClass = 'fc-event-declinado';
                    item.nombre = 'Salida antes';
                    icon = '🏃'; // Avance rápido (salida anticipada)
                }
                if (entrada > '07:00:00' && entrada < '09:00:00' && !salida || salida == null) {
                    eventClass = 'fc-event-declinado';
                    item.nombre = 'Sin Salida';
                    icon = '🚷'; // Avance rápido (salida anticipada)
                }
                if (entrada > '09:01:00') {
                    eventClass = 'fc-event-declinado';
                    item.nombre = 'Falta';
                    icon = '❌';
                } if (esFestivo) {
                    eventClass = 'fc-event-asueto';
                    item.nombre = item.title;
                    icon = '🎉';
                }
                if (!multiple && idEstatus == 1) {
                    eventClass = 'fc-event-espera';
                    item.nombre = 'Enviado';
                    icon = '✈️';
                }
                if (multiple && idEstatus == 1) {
                    eventClass = 'fc-event-espera';
                    item.nombre = 'Enviado';
                    icon = '✈️';
                }
                if (multiple && idEstatus == 2) {
                    eventClass = 'fc-event-declinado';
                    item.nombre = 'Declinado';
                    icon = '😢';
                }
                if (multiple && idEstatus == 3) {
                    eventClass = 'fc-event-aprobado';
                    item.nombre = 'Aprobado';
                    icon = '😊';
                }
                if (multiple && !idEstatus) {
                    eventClass = 'fc-event-tarde';
                    item.nombre = 'Justificar Periodo';
                    icon = '🔃';
                }
                if (!multiple && idEstatus == 1) {
                    eventClass = 'fc-event-espera';
                    item.nombre = 'Enviado';
                    icon = '✈️';
                }
                if (!multiple && idEstatus == 3) {
                    eventClass = 'fc-event-aprobado';
                    item.nombre = 'Aprobado';
                    icon = '😊';
                }
                if (!multiple && idEstatus == 2) {
                    eventClass = 'fc-event-declinado';
                    item.nombre = 'Declinado';
                    icon = '😢';
                }



            }

            const startDate = item.fecha_inicio ? item.fecha_inicio_incidencia.split(' ')[0] : null;
            const endDate = item.fecha_fin_incidencia ? item.fecha_fin_incidencia.split(' ')[0] : null;

            return {
                id: item.id_usuario,
                start: (esSemana) ? item.fecha_inicio_incidencia : item.fecha,
                end: (esSemana) ?
                    new Date(new Date(item.fecha_fin_incidencia).getTime() + 24 * 60 * 60 * 1000).toISOString() :
                    item.fecha,
                allDay: true,
                className: eventClass,
                display: esSemana ? 'background' : 'auto',
                title: `${item.nombre} ${icon}`,
                extendedProps: {
                    entrada: entrada,
                    tipo_registro: item.tipo_registro,
                    multiple: multiple,
                    esFestivo: esFestivo,
                    salida: (!salida) ? 'Sin salida' : salida,
                    trabajado: item.trabajado,
                    tarde: item.tarde,
                    temprano: item.temprano,
                    turno: item.turno,
                    tipo: 'asistencia',
                    esSemana: esSemana,
                    idEstatus: idEstatus,
                    horas_agrupadas: item.horas_agrupadas,
                    observaciones:item.observaciones,
                    id_incidencia:item.id_incidencia,
                    rango_legible: esSemana ? (startDate + ' - ' + dayBefore(endDate)) : null
                }
            };
        });



        var calendar = new FullCalendar.Calendar(calendarEl, {

            plugins: ['dayGrid', 'timeGrid', 'list', 'interaction'],
            header: {
                left: (calendarStatic) ? 'prev,next today' : '',
                center: 'title',
                right: (calendarStatic) ? 'dayGridMonth,timeGridWeek,timeGridDay,listWeek' : ''
            },
            // Configuración completa en español
            locale: 'es',
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
            weekText: 'Sm',
            allDayText: 'Todo el día',
            noEventsText: 'No hay eventos para mostrar',
            defaultDate: fecha,
            initialView: 'dayGridMonth',
            editable: false,
            selectable: false,
            events: eventos,
            // Formato de fecha en español
            titleFormat: {
                year: 'numeric',
                month: 'long'
            },
            // Configuración adicional para español
            firstDay: 1, // Lunes como primer día de la semana
            timeZone: 'local',
            eventTimeFormat: { // Formato de hora
                hour: '2-digit',
                minute: '2-digit',
                hour12: true,
                meridiem: 'short'
            },
            eventRender: function (info) {
                var eventEl = info.el;
                const esSemana = info.event.extendedProps.esSemana;
                const idEstatus = info.event.extendedProps.idEstatus;
                const multiple = info.event.extendedProps.multiple;
                if (info.event.extendedProps.esFestivo) {
                    eventEl.innerHTML = `<div class="fc-event-title">${info.event.title}</div>`;
                    return;
                }
                if (esSemana && idEstatus == 1) {
                    eventEl.innerHTML = `<div class="fc-event-title">${info.event.title}</div>`;
                    return;
                }
                if (multiple && idEstatus) {
                    eventEl.innerHTML = `<div class="fc-event-title">${info.event.title}</div>`;
                    return;
                }
                if (!multiple && idEstatus) {
                    eventEl.innerHTML = `<div class="fc-event-title">${info.event.title}</div>`;
                    return;
                }

                if (info.event.extendedProps.multiple) {
                    eventEl.innerHTML = `<div class="fc-event-title">${info.event.title}</div>
                    <div class="fc-event-details">
                            <div>${info.event.extendedProps.horas_agrupadas || '--:--'}</div>
                        </div>`;
                    return;
                }

                eventEl.innerHTML = `
                        <div class="fc-event-title">${info.event.title}</div>
                        <div class="fc-event-details">
                            <div>Entrada: ${info.event.extendedProps.entrada || '--:--'}</div>
                            <div>Salida: ${info.event.extendedProps.salida || '--:--'}</div>
                        </div>
                        `;
            },
            eventClick: function (info) {
                const esSemana = info.event.extendedProps.tipo === 2;
                const idEstatus = info.event.extendedProps.idEstatus;
                const entrada = info.event.extendedProps.entrada;
                const salida = info.event.extendedProps.salida;
                const esFestivo = info.event.extendedProps.esFestivo;
                const multiple = info.event.extendedProps.multiple;

                 const fechaLabel = esSemana
                    ? `${info.event.extendedProps.rango_legible}`
                    : info.event.start.toLocaleDateString();

                console.log(idEstatus);
                console.log(multiple);

                if (esFestivo) {
                    Swal.fire('Justificado por', info.event.title, "info");
                    return;
                }
                if (multiple && idEstatus == 1) {
                    Swal.fire('Atención', 'Se encuentra en validación', "info");
                    return;
                }
                if (multiple && idEstatus == 3) {
                    Swal.fire('Aprobado', 'La incidencia fue aprobada', "success");
                    return;
                }
                if(multiple){
                  justificar(info,fechaLabel, esSemana);
                }
                if(idEstatus == 2){
                  justificar(info,fechaLabel, esSemana);
                }

                if(salida == null || salida == 'Sin salida') {
                     justificar(info,fechaLabel, esSemana);
                }
                if(entrada > '09:00:00'){
                  justificar(info,fechaLabel, esSemana);
                }
                if(salida < '16:00:00'){
                   justificar(info,fechaLabel, esSemana);
                }
               
               

               
            },
            dateClick: function (info) {
                const fecha = info.date;
                const diaSemana = fecha.getDay();
                if (diaSemana === 0 || diaSemana === 6) {
                    Swal.fire("Error", "No se permite justificar faltas en sábado o domingo.", "error");
                    return;
                }
                let dia = info.dateStr;
                st.agregar.justificarFalta(dia);

            },
            dayRender: function (info) {
                const date = info.date;
                const fechaStr = date.toISOString().split('T')[0];
                const esRegistro = onlyAsistencias.includes(fechaStr);
                const esFestivo = fechaStr in diasFestivos;

                info.el.style.backgroundColor = 'rgba(58, 23, 75, 0.1)';
                info.el.style.border = '1px solid rgba(255, 0, 0, 0.3)';

                // ======= NUEVO: Bandera para días hábiles pasados sin registros =======
                if (esHabil(date) && esPasado(date) && !esRegistro && !esFestivo) {
                    info.el.classList.add('fc-dia-sin-chequeo');

                    // Evita duplicar badge si FullCalendar re-renderiza
                    if (!info.el.querySelector('.flag-missing')) {
                        const badge = document.createElement('div');
                        badge.className = 'flag-missing';
                        badge.innerHTML = `
                        <div class="spinner-grow text-danger"  role="status"></div>
                          <div class="fc-event-title text-danger" style="text-align: center;">Falta Sin Justificar</div>
                        `;
                        info.el.appendChild(badge);

                    }


                }
            },



        });

        calendar.render();

    });

    // Inicializar el mapa
    var map = L.map('map').setView([20.956950, -101.360316], 16); // Coordenadas de Guanajuato, zoom 17

    // Añadir capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Añadir marcador
    var marker = L.marker([20.956950, -101.360316]).addTo(map)
        .bindPopup('SECTURI')
        .openPopup();

    var polygon = L.polygon([
        [20.956965, -101.364241],
        [20.958276, -101.358666],
        [20.954891, -101.359349]
    ]).addTo(map);
    /* var circle = L.circle([20.956950, -101.360316], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: 1000
    }).addTo(map); */
</script>