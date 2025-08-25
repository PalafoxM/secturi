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
        <link href="<?php echo base_url() ?>plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() ?>plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />


   <style>
      /* Añade estos estilos al bloque de estilos existente */
    .fc-event-asistencia {
        border-left: 4px solid #4e73df;
        background-color: #f8f9fc;
        color: #4e73df;
    }
    .fc-event-incidencia {
        border-left: 4px solidrgb(17, 72, 236);
        background-color:rgb(233, 58, 35);
        color: #f8f9fc;
    }
    .fc-event-falta {
        border-left-color: #dc3545;
        background-color: #f8d7da;
        color: #dc3545;
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
                                           
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->                      
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                     <?php if($idTipoEmpleado == 1): ?>
                                    <div id='calendar'></div>
                                    <div style='clear:both'></div>
                                       <?php endif; ?>
                                       <?php if($idTipoEmpleado != 1): ?>
                                          <h4><div class="text-center">Estimado(a) Usuario(a), esta sección solo lo puede visualizar el<strong> personal de base <strong> </div> </h4>
                                          <p>Si requieres mayor información, favor de comunicarte al Administrador del Sistema<p>
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
        <div class="modal modal-rightbar fade" id="modalJustificar" tabindex="-1" role="dialog" aria-labelledby="MetricaRightbar"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="MetricaRightbar">JUSTIFICAR POR</h5>
                        <button type="button" class="btn btn-sm btn-soft-primary btn-circle btn-square"
                            data-dismiss="modal" aria-hidden="true"><i class="mdi mdi-close"></i></button>
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
                                <input type="hidden" id="fecha"  >
                               <h2 id="fecha_incidencia"> </h2>
                                <div class="slimscroll scroll-rightbar">
                                    <div class="activity">
                                        <div class="activity-info">
                                            <div class="activity-info-text mb-2">
                                                <label for="tipo_incidencia" class="form-label">Tipo de Incidencia</label>
                                                <select class="select2 form-control" id="tipo_incidencia">
                                                     <option value="">Seleccione</option>
                                                     <?php foreach($cat_incidencia as $c): ?>
                                                     <option value="<?= $c->id_incidencia?>"><?= $c->dsc_incidencia ?></option>
                                                     <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="activity-info">
                                            <div class="activity-info-text mb-2">
                                                 <label for="timepicker" class="form-label">Hora Inicio</label>
                                                 <input class="form-control" id="timepicker_inicio" name="hora_inicio" placeholder="Inicio">
                                             </div>
                                        </div>
                                        <div class="activity-info">
                                            <div class="activity-info-text mb-2">
                                                 <label for="timepicker" class="form-label">Hora Fin</label>
                                                 <input class="form-control" id="timepicker_fin" name="hora_fin" placeholder="Fin">
                                             </div>
                                        </div>
                                         <div class="activity-info">
                                            <div class="activity-info-text mb-2">
                                                 <label class="form-label">Detalles</label>
                                                 <textarea class="form-control" id="detalles" ></textarea>
                                             </div>
                                        </div>
                                         <div class="activity-info">
                                            <div class="activity-info-text mb-2">
                                                 <label class="form-label">Comentarios adicionales</label>
                                                 <textarea class="form-control" id="comentario" ></textarea>
                                             </div>
                                        </div>
                                      <a style="color:white;" id="btn_incidencia" class="btn btn-gradient-success btn-lg" onclick="st.agregar.guardarIncidencia();" role="button">Guardar</a>
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
                                                <label for="tipo_incidencia" class="form-label">Tipo de Incidencia</label>
                                                <select class="select2 form-control" id="tipo_incidencia"  >
                                                     <option value="">Seleccione</option>
                                                     <?php foreach($cat_incidencia as $c): ?>
                                                     <option value="<?= $c->id_incidencia?>"><?= $c->dsc_incidencia ?></option>
                                                     <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="activity-info">
                                            <div class="activity-info-text mb-2">
                                               <label class="my-3">Semanas</label>
                                                <div class="input-group">                                            
                                                    <input type="text" class="form-control" name="datetimes">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="dripicons-calendar"></i></span>
                                                    </div>
                                                </div>
                                             </div>
                                        </div>
                                         <div class="activity-info">
                                            <div class="activity-info-text mb-2">
                                                 <label class="form-label">Detalles</label>
                                                 <textarea class="form-control" id="detalles" ></textarea>
                                             </div>
                                        </div>
                                         <div class="activity-info">
                                            <div class="activity-info-text mb-2">
                                                 <label class="form-label">Comentarios adicionales</label>
                                                 <textarea class="form-control" id="comentario" ></textarea>
                                             </div>
                                        </div>
                                      <a style="color:white;" id="btn_incidencia" class="btn btn-gradient-success btn-lg" onclick="st.agregar.guardarIncidencia();" role="button">Guardar</a>
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
                                                <select class="select2 form-control" id="tipo_incidencia">
                                                     <option value="">Seleccione</option>
                                                     <?php foreach($cat_incidencia as $c): ?>
                                                     <option value="<?= $c->id_incidencia?>"><?= $c->dsc_incidencia ?></option>
                                                     <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="activity-info">
                                            <div class="activity-info-text mb-2">
                                             <label class="mb-3">Mes Inicio</label>
                                               <input type="text" class="form-control" placeholder="2017-06-04" id="mdate">
                                             </div>
                                        </div>
                                        <div class="activity-info">
                                            <div class="activity-info-text mb-2">
                                             <label class="mb-3">Mes Fin</label>
                                               <input type="text" class="form-control" placeholder="2017-06-04" id="mdate">
                                             </div>
                                        </div>
                                         <div class="activity-info">
                                            <div class="activity-info-text mb-2">
                                                 <label class="form-label">Detalles</label>
                                                 <textarea class="form-control" id="detalles" ></textarea>
                                             </div>
                                        </div>
                                         <div class="activity-info">
                                            <div class="activity-info-text mb-2">
                                                 <label class="form-label">Comentarios adicionales</label>
                                                 <textarea class="form-control" id="comentario" ></textarea>
                                             </div>
                                        </div>
                                      <a style="color:white;" id="btn_incidencia" class="btn btn-gradient-success btn-lg" onclick="st.agregar.guardarIncidencia();" role="button">Guardar</a>
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

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

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
<script src="<?php echo base_url() ?>assets/js/app.js"></script>
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
     $(document).ready(function() {
        $('#tipo_incidencia').on('change', function() {
        st.agregar.validacionIncapacidad();
    });
        // Inicialización de timepickers
    $('input[name="datetimes"]').daterangepicker({
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

    var eventosAsistencia = <?= json_encode($asistencia ?? []) ?>;
    var incidencia = <?= json_encode($incidencia ?? []) ?>;
    var mesSeleccionado = '<?= $mes ?>';
    var anio = '<?= $anio ?>';
    var calendarStatic = '<?= $calendarStatic ?>';

    // Agrega un cero al mes si es de un solo dígito
    var cero = (mesSeleccionado.length >= 2) ? '' : '0';

    // Construir la fecha en formato YYYY-MM-DD
    var fecha = anio + '-' + cero + mesSeleccionado + '-01';

    // Procesar los datos para FullCalendar
    var eventos = eventosAsistencia.map(function(item) {
        let eventClass = 'fc-event-asistencia';
        let icon = ''; // Para el emoji

        if (!item.entrada || item.entrada === '') {
            eventClass = 'fc-event-falta';
            item.nombre = 'Falta';
            icon = '❌';
        } else if (item.entrada < '08:30:00') {
            eventClass = 'fc-event-temprano';
            item.nombre = 'Temprano';
            icon = '✅';
        } else if (item.entrada >= '08:30:00' && item.entrada <= '08:45:00') {
            eventClass = 'fc-event-puntual';
            item.nombre = 'Puntual';
            icon = '🕣';
        } else if (item.entrada > '08:45:00') {
            eventClass = 'fc-event-tarde';
            item.nombre = 'Tarde';
            icon = '⚠️';
        }

        return {
            id: item.id_asistencia,
            start: item.fecha,
            allDay: true,
            className: eventClass,
            title: `${item.nombre} ${icon}`,
            extendedProps: {
                entrada: item.entrada,
                salida: item.salida,
                trabajado: item.trabajado,
                tarde: item.tarde,
                temprano: item.temprano,
                turno: item.turno,
                tipo: 'asistencia'
            }
        };
    });


    // Procesar las incidencias como eventos adicionales
    var eventosIncidencia = incidencia.map(function(item) {
        console.log(item);
        return {
            id: 'incidencia-' + item.id_incidencia,
            start: item.fecha,
            allDay: true,
            className: (item.id_estatus===3)?'fc-event-puntual':'fc-event-incidencia', // Clase CSS para estilizar
            title: 'Enviado',
            extendedProps: {
                tipo: 'incidencia',
                hora_inicio: item.hora_inicio || 'En validación',
                hora_fin: item.hora_fin,
                comentarios: item.comentarios,
                id_estatus: item.id_estatus
            }
        };
    });

    var todosLosEventos = eventos.concat(eventosIncidencia);

    var calendar = new FullCalendar.Calendar(calendarEl, {
   
       plugins: ['dayGrid', 'timeGrid', 'list', 'interaction'],
        header: {
            left: (calendarStatic)?'prev,next today':'',
            center: 'title',
            right: (calendarStatic)?'dayGridMonth,timeGridWeek,timeGridDay,listWeek':''
        },
        // Configuración completa en español
        locale: 'es',
        buttonText: {
            today:    'Hoy',
            month:    'Mes',
            week:     'Semana',
            day:      'Día',
            list:     'Lista'
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
        events: todosLosEventos,
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
      eventRender: function(info) {
            var eventEl = info.el;
            console.log(info.event.title);

            if (info.event.title === 'Enviado') {
                if (info.event.extendedProps.id_estatus === 3) {
                    eventEl.innerHTML = `
                        <div class="fc-event-title">Aprovado</div>
                        <div class="fc-event-temprano">
                            <div>Hora Inicio: ${info.event.extendedProps.hora_inicio}</div>
                        </div>
                    `;
                } else {
                    eventEl.innerHTML = `
                        <div class="fc-event-title">${info.event.title}</div>
                        <div class="fc-event-details">
                            <div>Hora Inicio: ${info.event.extendedProps.hora_inicio}</div>
                            <div>Hora fin: ${info.event.extendedProps.hora_fin}</div>
                        </div>
                    `;
                }
            } else {
                eventEl.innerHTML = `
                    <div class="fc-event-title">${info.event.title}</div>
                    <div class="fc-event-details">
                        <div>Entrada: ${info.event.extendedProps.entrada || '--:--'}</div>
                        <div>Salida: ${info.event.extendedProps.salida || '--:--'}</div>
                    </div>
                `;
            }
        },
        eventClick: function(info) {
            // Mostrar detalles completos al hacer clic
            var eventEl = info.el;
            
            Swal.fire({
                title: info.event.title,
                html: `
                    <div style="text-align: left;">
                        <p><strong>Fecha:</strong> ${info.event.start.toLocaleDateString()}</p>
                        <p><strong> ${info.event.extendedProps.entrada?'Entrada':'Hora Inicio'}:</strong> ${info.event.extendedProps.entrada || info.event.extendedProps.hora_inicio}</p>
                        <p><strong>${info.event.extendedProps.entrada?'Salida':'Hora Fin'}:</strong> ${info.event.extendedProps.salida || info.event.extendedProps.hora_fin}</p>
                    </div>
                `,
                confirmButtonText: 'Cerrar',
                customClass: {
                    popup: 'swal-wide' // Clase para hacer el modal más ancho
                }
            });
        },
       dateClick: function(info) {
            const fecha = info.date; // tipo Date
            const diaSemana = fecha.getDay(); // 0 (Domingo) a 6 (Sábado)
            if (diaSemana === 0 || diaSemana === 6) {
                 Swal.fire("Error", "No se permite justificar faltas en sábado o domingo. " , "error");
                return; // Detiene la ejecución
            }
            // Si es un día hábil, continúa
            let dia = info.dateStr;
            st.agregar.justificarFalta(dia);
        },
       dayRender: function(info) {
            
            const date = info.date;
            const day = date.getDay(); // 0 = domingo, 6 = sábado
            const isWeekend = (day === 0 || day === 6);
            const dateStr = date.toISOString().substring(0, 10);

            const eventoDelDia = eventos.find(e => e.start.substring(0, 10) === dateStr);
            console.log(eventoDelDia);
            const esFalta = eventoDelDia && eventoDelDia.className.includes('fc-event-falta');

            // Verificar si hay eventos para este día
            var hasEvents = eventos.some(function(event) {
                return event.start.substring(0, 10) === dateStr;
            });
         

            if (isWeekend && !esFalta) {
                info.el.style.backgroundColor = '#f0f0f0'; // gris claro para fines de semana
            } else if (hasEvents) {
                info.el.style.backgroundColor = 'rgba(78, 115, 223, 0.05)'; // estilo para días con eventos
            } else {
                info.el.style.backgroundColor = 'rgba(255, 0, 0, 0.1)'; // rojo claro para días vacíos
                info.el.style.border = '1px solid rgba(255, 0, 0, 0.3)'; // opcional: borde rojo
            }
            
        }

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
