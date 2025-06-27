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
      main {
      flex:1; display:flex;
      flex-direction:column; align-items:center;
      justify-content:center; padding:20px;
    }
    .step {
      width:100%; max-width:500px;
      padding:20px; border-radius:8px;
      background:linear-gradient(135deg,#e0eafc 0%,#cfdef3 100%);
      box-shadow:0 4px 12px rgba(0,0,0,0.1);
      opacity:0; transform:translateY(10px);
    }
    .step.active {
     animation:fadeIn .4s ease forwards;
    }
    @keyframes fadeIn { to { opacity:1; transform:translateY(0); } }

    /* Header gradient */
    .step h2 {
      background:linear-gradient(45deg,#007bff,#00d4ff);
      color:#fff; padding:12px; border-radius:6px;
      text-shadow:1px 1px 2px rgba(0,0,0,0.2);
      margin-bottom:16px; width:100%; box-sizing:border-box;
    }

    /* Step 1: botones más grandes */
    #step1 .wizard-actions button {
      padding: 14px 28px;
      font-size: 1.1rem;
    }

 .rooms {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 10px;
}

.room {
    cursor: pointer;
    text-align: center;
    flex: 1 1 100px;
    transition: transform 0.2s, box-shadow 0.2s; /* Agregamos box-shadow aquí */
}

/* Efecto hover (al pasar el mouse) */
.room:hover {
    transform: scale(1.05); /* Escala ligeramente */
}

/* Efecto cuando está seleccionado (clase "selected") */
.room.selected {
    transform: scale(1.1); /* Escala un poco más que el hover */
}

/* Estilo de la imagen dentro de .room */
.room img {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    transition: box-shadow 0.2s;
}

/* Efecto hover en la imagen */
.room:hover img {
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.5); /* Sombra sutil al pasar el mouse */
}

/* Efecto selected en la imagen (más destacado) */
.room.selected img {
    box-shadow: 0 0 0 4px #007bff; /* Borde azul más marcado */
}

    .wizard-actions {
      margin-top:20px; text-align:right;
    }
    .wizard-actions button {
      padding:8px 16px; border:none; border-radius:4px;
      background:#007bff; color:#fff; cursor:pointer;
      margin-left:8px; animation:pulse 1.5s ease-in-out infinite;
    }
    @keyframes pulse { 0%{transform:scale(1);}50%{transform:scale(1.05);}100%{transform:scale(1);} }
    #viewReservations { animation:none; }

    #step2 label { display:block; margin-top:12px; font-weight:bold; color:#333; }
    #step2 input, #step2 select {
      width:100%; padding:10px; margin-top:6px; border:none;
      border-radius:4px; box-shadow:inset 0 2px 4px rgba(0,0,0,0.1);
      transition:box-shadow .3s;
    }
    #step2 input:focus, #step2 select:focus {
      outline:none; box-shadow:inset 0 4px 8px rgba(0,0,0,0.2);
    }

    .error { color:#c00; text-align:center; margin-top:10px; }

    /* Step 3: ancho completo para cubrir tabla */
    #step3 {
      width:100%;
      max-width:none;
    }

    #confirmationTable {
      width:100%; border-collapse:collapse; background:#fff;
      box-shadow:0 2px 4px rgba(0,0,0,0.1); border-radius:4px;
      overflow:hidden; margin-top:10px;
    }
    #confirmationTable th, #confirmationTable td {
      padding:10px; text-align:left; border-bottom:1px solid #eee;
    }
    #confirmationTable th {
      background:#007bff; color:#fff;
    }

    #calendarView { display:none; flex:1; width:100%; }
    .calendar-container { display:flex; flex:1; width:100%; }
    #calendar {
      flex:1; background:#fff; border-radius:8px; overflow:auto;
      box-shadow:0 4px 12px rgba(0,0,0,0.1); padding:20px;
    }
    .fc .fc-button {
      background:#007bff!important; color:#fff!important;
      border:none!important; border-radius:4px!important;
      animation:pulse 2s ease-in-out infinite;
      transition:transform .2s!important;
    }
    .fc .fc-button:hover:not(.fc-prev-button):not(.fc-next-button):not(.fc-today-button) {
      transform:scale(1.1)!important;
    }

    #sidebar {
      width:300px; background:#fff; border-radius:8px; margin-left:20px;
      display:flex; flex-direction:column; box-shadow:0 4px 12px rgba(0,0,0,0.1);
    }
    .sidebar-actions {
      text-align:center; padding:10px; border-bottom:1px solid #eee;
    }
    .sidebar-actions button {
      padding:6px 12px; border:none; border-radius:4px;
      background:#007bff; color:#fff; cursor:pointer;
      transition:background .2s;
    }
    .sidebar-actions button:hover { background:#0056b3; }
    #sidebar h2 {
      background:#f7f7f7; color:#333; padding:10px; text-align:center; margin:0;
    }
    #resList {
      flex:1; overflow:auto; padding:10px; list-style:none;
    }
    #resList li {
      margin-bottom:8px; font-size:14px;
    }

    @media(max-width:768px){
      main, .calendar-container { flex-direction:column; padding:0 15px 15px; }
      #sidebar { width:100%; margin-top:15px; }
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
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Sala de juntas</a></li>
                                        <li class="breadcrumb-item active">Calendario</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Calendario</h4>
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
                                    <div id='calendario'></div>
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
        <script src="<?php echo base_url() ?>assets/js/jquery-ui.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/metismenu.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/waves.js"></script>
        <script src="<?php echo base_url() ?>assets/js/feather.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/jquery.slimscroll.min.js"></script>
        <script src="<?php echo base_url() ?>plugins/apexcharts/apexcharts.min.js"></script> 

        <script src="<?php echo base_url() ?>plugins/moment/moment.js"></script>
        <script src='<?php echo base_url() ?>plugins/fullcalendar/packages/core/main.js'></script>
        <script src='<?php echo base_url() ?>plugins/fullcalendar/packages/daygrid/main.js'></script>
        <script src='<?php echo base_url() ?>plugins/fullcalendar/packages/timegrid/main.js'></script>
        <script src='<?php echo base_url() ?>plugins/fullcalendar/packages/interaction/main.js'></script>
        <script src='<?php echo base_url() ?>plugins/fullcalendar/packages/list/main.js'></script>
        <script src='<?php echo base_url() ?>assets/pages/jquery.calendar.js'></script>
        
        <!-- App js -->
        <script src="<?php echo base_url() ?>assets/js/app.js"></script>

        <script>
            st.agregar.registroSala();
       
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendario');
    
    // Datos simulados de checador (en un caso real, estos vendrían de tu base de datos)
    const registrosChecador = [
        {
            id: 1,
            empleado: 'Juan Pérez',
            fecha: '2025-06-01',
            entrada: '2025-06-01T08:15:00',
            salida: '2025-06-01T17:30:00',
            estado: 'puntual'
        },
        {
            id: 2,
            empleado: 'Juan Pérez',
            fecha: '2025-06-02',
            entrada: '2025-06-02T08:45:00',
            salida: '2025-06-02T17:15:00',
            estado: 'retardo'
        },
        {
            id: 3,
            empleado: 'Juan Pérez',
            fecha: '2025-06-03',
            entrada: '2025-06-03T08:00:00',
            salida: '2025-06-03T16:00:00',
            estado: 'salida_temprano'
        },
        {
            id: 4,
            empleado: 'María García',
            fecha: '2025-06-01',
            entrada: '2025-06-01T08:05:00',
            salida: '2025-06-01T17:45:00',
            estado: 'puntual'
        },
        {
            id: 5,
            empleado: 'María García',
            fecha: '2025-06-02',
            entrada: '2025-06-02T08:00:00',
            salida: null, // No registró salida
            estado: 'falta_salida'
        }
    ];

    // Convertimos los datos a eventos para FullCalendar
    const eventos = registrosChecador.map(registro => {
        // Determinar color según el estado
        let color;
        switch(registro.estado) {
            case 'puntual':
                color = '#28a745'; // Verde
                break;
            case 'retardo':
                color = '#ffc107'; // Amarillo
                break;
            case 'salida_temprano':
                color = '#fd7e14'; // Naranja
                break;
            case 'falta_salida':
                color = '#dc3545'; // Rojo
                break;
            default:
                color = '#6c757d'; // Gris
        }
        
        // Crear título del evento
        let title = `${registro.empleado}\nEntrada: ${registro.entrada ? registro.entrada.substr(11, 5) : 'No registrada'}`;
        if (registro.salida) {
            title += `\nSalida: ${registro.salida.substr(11, 5)}`;
        } else {
            title += `\nSalida: No registrada`;
        }
        
        return {
            id: registro.id,
            title: title,
            start: registro.entrada,
            end: registro.salida,
            color: color,
            extendedProps: {
                empleado: registro.empleado,
                estado: registro.estado
            }
        };
    });

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: ['dayGrid', 'timeGrid', 'list', 'interaction'],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
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
         defaultView: 'dayGridMonth',
        editable: false, // No permitir edición directa
        selectable: false, // No permitir selección de intervalos
        events: eventos,
        eventTimeFormat: { // Formato de hora para los eventos
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        },
        eventDidMount: function(info) {
            // Personalizar cómo se ven los eventos
            if (info.event.extendedProps.estado === 'falta_salida') {
                info.el.style.borderStyle = 'dashed';
            }
        },
        eventClick: function(info) {
            // Mostrar detalles al hacer clic en un evento
            const evento = info.event;
            const empleado = evento.extendedProps.empleado;
            const entrada = evento.start ? evento.start.toLocaleTimeString() : 'No registrada';
            const salida = evento.end ? evento.end.toLocaleTimeString() : 'No registrada';
            
            let estadoMsg = '';
            switch(evento.extendedProps.estado) {
                case 'puntual':
                    estadoMsg = 'Puntual';
                    break;
                case 'retardo':
                    estadoMsg = 'Llegó tarde';
                    break;
                case 'salida_temprano':
                    estadoMsg = 'Salió antes';
                    break;
                case 'falta_salida':
                    estadoMsg = 'Falta registro de salida';
                    break;
            }
            
            Swal.fire({
                title: `Registro de ${empleado}`,
                html: `
                    <p><strong>Entrada:</strong> ${entrada}</p>
                    <p><strong>Salida:</strong> ${salida}</p>
                    <p><strong>Estado:</strong> ${estadoMsg}</p>
                `,
                icon: 'info',
                confirmButtonText: 'Cerrar'
            });
        },
        views: {
            dayGridMonth: {
                eventDisplay: 'list-item' // Mostrar como lista en vista mensual
            },
            timeGridWeek: {
                slotMinTime: '06:00:00', // Hora mínima visible
                slotMaxTime: '20:00:00' // Hora máxima visible
            },
            timeGridDay: {
                slotMinTime: '06:00:00',
                slotMaxTime: '20:00:00'
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
