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
                                    <img src="<?php echo base_url() ?>assets/images/widgets/calendar.svg" alt="" class="img-fluid">
                                    <h6>Agenda de hoy</h6>
                                    <ul class="list-group">
                                        <?php foreach($sala_hoy as $s): ?>
                                        <li class="list-group-item align-items-center d-flex">
                                            <div class="media">
                                                <img src="<?php echo base_url() ?>assets/images/widgets/project1.jpg" class="mr-3 thumb-sm align-self-center rounded-circle" alt="...">
                                                <div class="media-body align-self-center"> 
                                                    <h5 class="mt-0 mb-1"><?= $s->evento?></h5>
                                                    <p class="text-muted mb-0">sala : <?= $s->sala.' de '.$s->hora_inicio.' a '.$s->hora_fin ?></p>                                                                                             
                                                </div><!--end media body-->
                                            </div>
                                            <?php endforeach; ?>
                                        </li>
                                    </ul> 
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

<div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" id="modal1">
          
        </div>
    </div>
</div>
<div class="modal fade" id="calendarModal2" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
                <main>
              <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body step active">        
                                   <h2 class="mt-0 header-title">2) Detalles de la reserva</h2>
                                    <p class="text-muted mb-3">Sala seleccionada:  <span id="chosenRoom"></span></p>
                                    <form id="registroSala" > 
                                    <input type="hidden" id="sala" name="sala" >
                                    <div class="row">
                                        <div class="col-lg-6">
                                    
                                        <div class="form-group"> 
                                            <label for="fecha">Fecha</label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" id="fecha" name="fecha" readonly>
                                            </div>                                                    
                                        </div>
                                        <div class="form-group">
                                            <label for="hora_inicio">Hora inicio</label>
                                            <div class="input-group">
                                                <input type="time" id="hora_inicio" name="hora_inicio" class="form-control">
                                             
                                            </div>                                                    
                                        </div>
                                        <div class="form-group">
                                            <label for="hora_fin">Hora fin</label>
                                            <div class="input-group">
                                                <input type="time" id="hora_fin" name="hora_fin" class="form-control">
                                             
                                            </div>                                                    
                                        </div>
                                        <div class="form-group">
                                            <label for="evento">Nombre del Evento</label>
                                            <div class="input-group">
                                                <input type="text" id="evento" name="evento" class="form-control" autocomplete="off">
                                             
                                            </div>                                                    
                                        </div>                         
                                        </div>
                                        <div class="col-lg-6">
                                                 <div class="form-group">
                                            <label for="asistentes"># Asistentes</label>
                                            <div class="input-group">
                                                <input type="number" id="asistentes" name="asistentes" class="form-control">
                                             
                                            </div>                                                    
                                        </div>
                                        <div class="form-group">
                                            <label for="asistentes">Proyección</label>
                                                 <select class="form-control" id="proyecto" name="proyecto">
                                                        <option value="1">SI</option>
                                                        <option value="2">NO</option>
                                                    </select>                                      
                                        </div>
                                        <div class="form-group">
                                            <label for="asistentes">Tipo de Reunión</label>
                                                 <select class="form-control" id="tipo_reunion" name="tipo_reunion">
                                                        <option value="1">INTERNA</option>
                                                        <option value="2">EXTERNA</option>
                                                    </select>                                      
                                        </div>
                                        <div class="form-group">
                                            <label for="asistentes">Catering</label>
                                                 <select class="form-control" id="catering" name="catering">
                                                         <option value="1">INTERNA</option>
                                                        <option value="2">EXTERNA</option>
                                                    </select>                                      
                                        </div>                                  
                                        </div>
                                            <div class="wizard-actions">

                                                <button id="finishWizard" type="submit">OK ✔️</button>
                                            </div>
                                 
                                           </form>
                                    </div>                                                                      
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </main> 
        </div>
    </div>
</div>


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
        const salas = <?= json_encode($sala_junta ?? []); ?>;
        const id_perfil = <?= $session->get('id_perfil'); ?>;
        console.log(salas);
        const eventos = salas.map(s => ({
                id: s.id_sala_junta,
                title: s.evento,
                start: s.fecha, // correcto: '2025-06-23T10:00:00'
                end: s.fecha,
                color: s.sala === 'A' ? '#007bff' :
                    s.sala === 'B' ? '#28a745' :
                    s.sala === 'AB' ? '#6f42c1' :
                    s.sala === 'TI' ? '#fd7e14' :
                    '#6c757d' // color por defecto
            }));

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['dayGrid', 'timeGrid', 'list', 'interaction'],
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            buttonText: { // Traduce los textos de los botones
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
            editable: true, // Permite arrastrar y editar eventos
            selectable: true, // Permite seleccionar intervalos
            events: eventos,
        dateClick: function(info) {
                let dia = info.dateStr; 
                const fechaHoy = new Date();
                const fechaSeleccionada = new Date(info.dateStr); 
                fechaHoy.setHours(0, 0, 0, 0);
                fechaSeleccionada.setHours(0, 0, 0, 0);

                // Convertir a timestamps (opcional)
                const hoyTimestamp = fechaHoy.getTime();
                const seleccionadaTimestamp = fechaSeleccionada.getTime();
       
                if (parseInt(seleccionadaTimestamp) < parseInt(hoyTimestamp)) {
                    Swal.fire("Error", "No puedes agendar en una fecha pasada", "error");
                    return;
                }
                st.agregar.calendarModal();
                let html = `<main>
                    <div id="step1" class="step active">
                        <h2>1) Selecciona la sala</h2>
                        <div class="rooms">
                            <div class="room" data-sala="A"><img src="https://sectur.guanajuato.gob.mx/wp-content/uploads/2025/06/SALAA.png"><div>Sala A</div></div>
                            <div class="room" data-sala="B"><img src="https://sectur.guanajuato.gob.mx/wp-content/uploads/2025/06/SALAB.png"><div>Sala B</div></div>
                            <div class="room" data-sala="AB"><img src="https://sectur.guanajuato.gob.mx/wp-content/uploads/2025/06/SALAAB.png"><div>Combinada</div></div>
                            <div class="room" data-sala="TI"><img src="https://sectur.guanajuato.gob.mx/wp-content/uploads/2025/06/SALATI.png"><div>Sala TI</div></div>
                        </div>
                        <div id="step1Error" class="error"></div>
                    </div>
                </main>`;
                
                $('#modal1').html(html);

                // Asignar eventos después de renderizar
                $('.room').on('click', function() {
                    const sala = $(this).data('sala');
                    st.agregar.sala(sala, dia); // Ejecuta la función al hacer clic
                });
            },
        eventClick: function(info) {
   
                const fecha = new Date(info.event.start).toLocaleString('es-MX', {
                    weekday: 'long', year: 'numeric', month: 'long',
                    day: 'numeric', hour: '2-digit', minute: '2-digit'
                });

                Swal.fire({
                    title: info.event.title,
                    text: `Fecha: ${fecha}`,
                    icon: "warning",
                    showCancelButton: true,
                    showConfirmButton: (id_perfil == 1),
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Eliminar"
                }).then((result) => {
                    if (result.isConfirmed) {
                         const eventId = info.event.id;
                                  console.log(eventId);
                            $.ajax({
                                type: "POST",
                                url: base_url + "index.php/Usuario/deleteSala",
                                dataType: "json",
                                data:{id_sala_juntas:eventId},
                                success: function(data) {
                                    console.log(data);
                                    if (!data.error) {
                                        Swal.fire("Éxito", "Se guardo correctamente.", "success")
                                    } else {
                                        Swal.fire("Error", "Error al guardar comentario.", "error");
                                    }
                                //  ini.inicio.obtenerCategorias(); 
                                ini.inicio.getPeriodos(); 
                                },
                                error: function() {
                                    Swal.fire("Error", "Error al guardar comentario.", "error")
                                }
                            });
                        Swal.fire({
                            title: "¡Eliminado!",
                            text: "El evento ha sido eliminado.",
                            icon: "success"
                        });
                    }
                });
            }

        });

    calendar.render();
});
        </script>
