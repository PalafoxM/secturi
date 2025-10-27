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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">XML</a></li>
                                <li class="breadcrumb-item active">FACTURA</li>
                            </ol>
                        </div>
                        <h4 class="page-title">DATOS FACTURA XML</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-sm-12">
                   <div class="card">
                                                        <div class="card-body">                                    
                                                            <div class="task-box">
                                                                <div class="task-priority-icon"><i class="fas fa-circle text-danger"></i></div>
                                                                <p class="text-muted float-right">
                                                               
                                                                    <span><i class="far fa-fw fa-clock"></i> <?= date('d/m/Y', strtotime($factura->fec_reg)) ?></span>
                                                                </p>
                                                                <h5 class="mt-0"> Emisor RFC :  <?= $factura->emisor_rfc ?></h5>
                                                                <p class="text-muted mb-1">No. Certificado : <?= $factura->no_certificado ?>
                                                                </p>
                                                                <h5 class="mt-0"> Emisor Nombre :  <?= $factura->emisor_nombre ?></h5>
                                                                <p class="text-muted text-right mb-1">Folio : <?= $factura->folio ?></p>
                                                                <h5 class="mt-0"> Receptor RFC :  <?= $factura->receptor_rfc ?></h5>
                                                           
                                                                <h5 class="mt-0"> Receptor Nombre :  <?= $factura->receptor_nombre ?></h5>
                                                      
                                                                <p class="text-muted text-right mb-1">UUID : <?= $factura->uuid ?></p>
                                                              
                                                                <div class="d-flex justify-content-between">
                                                                 
                                                                    <ul class="list-inline mb-0 align-self-center">                                                                    
                                                                        <li class="list-item d-inline-block mr-2">
                                                                            <a class="" href="#">
                                                                                <i class="mdi mdi-format-list-bulleted text-success font-15"></i>
                                                                                <span class="text-muted font-weight-bold">Total : <?= $factura->total ?> </span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-item d-inline-block">
                                                                            
                                                                                <span class="text-muted font-weight-bold"><?= $factura->moneda ?> </span>
                                                                                                                                                  
                                                                        </li>
                                                                      
                                                                        <li class="list-item d-inline-block">
                                                                              <span class="text-muted font-weight-bold">version : <?= $factura->version ?> </span>                                                                           
                                                                        </li>
                                                                    </ul>
                                                                </div>                                        
                                                            </div><!--end task-box-->
                                                        </div><!--end card-body-->
                                                    </div><!--end card-->
                                                    </div><!--end card-->
                
              
            </div><!-- End row -->

        </div><!-- container -->
    </div>
    <!-- end page content -->
</div>
<!-- end page-wrapper -->







<!-- jQuery  -->
<script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/metismenu.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/waves.js"></script>
<script src="<?php echo base_url() ?>assets/js/feather.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.slimscroll.min.js"></script>


<script src="<?php echo base_url() ?>plugins/moment/moment.js"></script>
<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/core/main.js'></script>
<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/daygrid/main.js'></script>
<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/timegrid/main.js'></script>
<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/interaction/main.js'></script>
<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/list/main.js'></script>
<script src='<?php echo base_url() ?>assets/pages/jquery.calendar.js'></script>


<script>
    st.agregar.registroSala();

    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendario');
        const salas = <?= json_encode($sala_junta ?? []); ?>;
        const id_perfil = <?= $session->get('id_perfil'); ?>;
        console.log(salas);
        const eventos = salas.map(s => ({
            id: s.id_sala,
            title: s.sala,
            start: s.fecha, // correcto: '2025-06-23T10:00:00'
            end: s.fecha,
            color: s.sala === 'A' ? '#007bff' :
                s.sala === 'B' ? '#28a745' :
                    s.sala === 'AB' ? '#6f42c1' :
                        s.sala === 'TI' ? '#fd7e14' :
                            '#6c757d',
            extendedProps: {
                hora_inicio: s.hora_inicio,
                hora_fin: s.hora_fin,
                usuario: s.nombre_completo,

            }

        }));

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['dayGrid', 'timeGrid', 'list', 'interaction'],
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            locale: 'es',
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
            weekends: false,
            dateClick: function (info) {
                let dia = info.dateStr;
                const fechaHoy = new Date();
                fechaHoy.setHours(0, 0, 0, 0);
                // Parseo correcto de fecha local
                const [year, month, day] = info.dateStr.split('-');
                const fechaSeleccionada = new Date(year, month - 1, day);
                fechaSeleccionada.setHours(0, 0, 0, 0);
                const hoyTimestamp = fechaHoy.getTime();
                const seleccionadaTimestamp = fechaSeleccionada.getTime();

                console.log(seleccionadaTimestamp);
                console.log(hoyTimestamp);

                if (seleccionadaTimestamp < hoyTimestamp) {
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
                $('.room').on('click', function () {
                    const sala = $(this).data('sala');
                    st.agregar.sala(sala, dia); // Ejecuta la función al hacer clic
                });
            },
            eventRender: function (info) {
                var eventEl = info.el;
                const horaInicio = info.event.extendedProps.hora_inicio.slice(0, 5);
                const horaFin = info.event.extendedProps.hora_fin.slice(0, 5);


                eventEl.innerHTML = `
                <div class="fc-event-title"><strong>SALA ${info.event.title}</strong></div>
                <div class="fc-event-details">
                    <div>${horaInicio} A ${horaFin}</div>
                    
                </div>
            `;
            },
            dayRender: function (info) {

                const date = info.date;
                const day = date.getDay(); // 0 = domingo, 6 = sábado
                const isWeekend = (day === 0 || day === 6);

                 info.el.style.backgroundColor = 'rgba(37, 99, 235, 0.1)';  // Azul translúcido
                info.el.style.border = '1px solid rgba(37, 99, 235, 0.3)';  // Azul tenue


                if (isWeekend) {
                    info.el.style.backgroundColor = '#f0f0f0'; // gris claro para fines de semana
                }

            },
            eventClick: function (info) {
                const horaFin = info.event.extendedProps.hora_fin.slice(0, 5);
                const fecha = new Date(info.event.start).toLocaleString('es-MX', {
                    weekday: 'long', year: 'numeric', month: 'long',
                    day: 'numeric', hour: '2-digit', minute: '2-digit'
                });
                const usuario = info.event.extendedProps.usuario;

                Swal.fire({
                    title: `SALA ` + info.event.title,
                    text: `Fecha: ${fecha} a ${horaFin} registro: ${usuario} `,
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
                            data: { id_sala_juntas: eventId },
                            success: function (data) {
                                console.log(data);
                                if (!data.error) {
                                    Swal.fire("Éxito", "Se guardo correctamente.", "success")
                                } else {
                                    Swal.fire("Error", "Error al guardar comentario.", "error");
                                }
                                //  ini.inicio.obtenerCategorias(); 

                            },
                            complete: function(){
                             window.location.reload();
                            },
                            error: function () {
                                Swal.fire("Error", "Error al guardar comentario.", "error")
                            }
                        });
                    }
                });
            }

        });

        calendar.render();
    });
</script>