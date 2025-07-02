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

   <style>
      /* Añade estos estilos al bloque de estilos existente */
    .fc-event-asistencia {
        border-left: 4px solid #4e73df;
        background-color: #f8f9fc;
        color: #4e73df;
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
                                    <div id='calendar'></div>
                                    <div style='clear:both'></div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!-- End row -->

                </div><!-- container -->
            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->

<!-- Leaflet CSS -->
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
     

        <script>
     $(document).ready(function() {
    var calendarEl = document.getElementById('calendar');
    
    // Obtener los datos de asistencia desde PHP (asegúrate de que tu controlador los pase como JSON)

    var eventosAsistencia = <?= json_encode($asistencia ?? []) ?>;
    var mesSeleccionado = '<?= $mes ?>';
    var anio = '<?= $anio ?>';
    var calendarStatic = '<?= $calendarStatic ?>';

    // Agrega un cero al mes si es de un solo dígito
    var cero = (mesSeleccionado.length >= 2) ? '' : '0';

    // Construir la fecha en formato YYYY-MM-DD
    var fecha = anio + '-' + cero + mesSeleccionado + '-01';

    console.log(fecha);


    // Procesar los datos para FullCalendar
    var eventos = eventosAsistencia.map(function(item) {

        // Determinar clase CSS según el tiempo
        var eventClass = 'fc-event-asistencia';
        if (item.entrada >= '08:45:00') {
            eventClass = 'fc-event-tarde';
        } else {
            eventClass = 'fc-event-puntual';
        }
        
        return {
            id: item.id_asistencia,
            start: item.fecha,
            allDay: true, // Mostrar como evento de todo el día
            className: eventClass,
            extendedProps: {
                entrada: item.entrada,
                salida: item.salida,
                trabajado: item.trabajado,
                tarde: item.tarde,
                temprano: item.temprano,
                turno: item.turno
            }
        };
    });

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
        eventRender: function(info) {
            // Personalizar el contenido del evento
            var eventEl = info.el;
            eventEl.innerHTML = `
                <div class="fc-event-title">${info.event.title}</div>
                <div class="fc-event-details">
                    <div>Entrada: ${info.event.extendedProps.entrada}</div>
                    <div>Salida: ${info.event.extendedProps.salida}</div>
                    <div>Horas: ${info.event.extendedProps.trabajado}</div>
                </div>
            `;
        },
        eventClick: function(info) {
            // Mostrar detalles completos al hacer clic
            Swal.fire({
                title: 'Detalles de Asistencia',
                html: `
                    <div style="text-align: left;">
                        <p><strong>Fecha:</strong> ${info.event.start.toLocaleDateString()}</p>
                        <p><strong>Entrada:</strong> ${info.event.extendedProps.entrada}</p>
                        <p><strong>Salida:</strong> ${info.event.extendedProps.salida}</p>
                        <p><strong>Horas trabajadas:</strong> ${info.event.extendedProps.trabajado}</p>
                        <p><strong>Tiempo tarde:</strong> ${info.event.extendedProps.tarde}</p>
                        <p><strong>Salida temprano:</strong> ${info.event.extendedProps.temprano}</p>
                    </div>
                `,
                confirmButtonText: 'Cerrar',
                customClass: {
                    popup: 'swal-wide' // Clase para hacer el modal más ancho
                }
            });
        },
        dayRender: function(info) {
            // Resaltar días con registros de asistencia
            var hasEvents = eventos.some(function(event) {
                return event.start.substring(0, 10) === info.date.toISOString().substring(0, 10);
            });
            
            if (hasEvents) {
                info.el.style.backgroundColor = 'rgba(78, 115, 223, 0.05)';
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
        </script>
