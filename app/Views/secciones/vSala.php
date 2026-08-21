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
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .step {
        width: 100%;
        max-width: 500px;
        padding: 20px;
        border-radius: 8px;
        background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        opacity: 0;
        transform: translateY(10px);
    }

    .step.active {
        animation: fadeIn .4s ease forwards;
    }

    @keyframes fadeIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Header gradient */
    .step h2 {
        background: linear-gradient(45deg, #007bff, #00d4ff);
        color: #fff;
        padding: 12px;
        border-radius: 6px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        margin-bottom: 16px;
        width: 100%;
        box-sizing: border-box;
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
        transition: transform 0.2s, box-shadow 0.2s;
        /* Agregamos box-shadow aquí */
    }

    /* Efecto hover (al pasar el mouse) */
    .room:hover {
        transform: scale(1.05);
        /* Escala ligeramente */
    }

    /* Efecto cuando está seleccionado (clase "selected") */
    .room.selected {
        transform: scale(1.1);
        /* Escala un poco más que el hover */
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
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.5);
        /* Sombra sutil al pasar el mouse */
    }

    /* Efecto selected en la imagen (más destacado) */
    .room.selected img {
        box-shadow: 0 0 0 4px #007bff;
        /* Borde azul más marcado */
    }

    .wizard-actions {
        margin-top: 20px;
        text-align: right;
    }

    .wizard-actions button {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        background: #007bff;
        color: #fff;
        cursor: pointer;
        margin-left: 8px;
        animation: pulse 1.5s ease-in-out infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    #viewReservations {
        animation: none;
    }

    #step2 label {
        display: block;
        margin-top: 12px;
        font-weight: bold;
        color: #333;
    }

    #step2 input,
    #step2 select {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
        border: none;
        border-radius: 4px;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: box-shadow .3s;
    }

    #step2 input:focus,
    #step2 select:focus {
        outline: none;
        box-shadow: inset 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .error {
        color: #c00;
        text-align: center;
        margin-top: 10px;
    }

    /* Step 3: ancho completo para cubrir tabla */
    #step3 {
        width: 100%;
        max-width: none;
    }

    #confirmationTable {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        overflow: hidden;
        margin-top: 10px;
    }

    #confirmationTable th,
    #confirmationTable td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    #confirmationTable th {
        background: #007bff;
        color: #fff;
    }

    #calendarView {
        display: none;
        flex: 1;
        width: 100%;
    }

    .calendar-container {
        display: flex;
        flex: 1;
        width: 100%;
    }

    #calendar {
        flex: 1;
        background: #fff;
        border-radius: 8px;
        overflow: auto;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .fc .fc-button {
        background: #007bff !important;
        color: #fff !important;
        border: none !important;
        border-radius: 4px !important;
        animation: pulse 2s ease-in-out infinite;
        transition: transform .2s !important;
    }

    .fc .fc-button:hover:not(.fc-prev-button):not(.fc-next-button):not(.fc-today-button) {
        transform: scale(1.1) !important;
    }

    #sidebar {
        width: 300px;
        background: #fff;
        border-radius: 8px;
        margin-left: 20px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .sidebar-actions {
        text-align: center;
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .sidebar-actions button {
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        background: #007bff;
        color: #fff;
        cursor: pointer;
        transition: background .2s;
    }

    .sidebar-actions button:hover {
        background: #0056b3;
    }

    #sidebar h2 {
        background: #f7f7f7;
        color: #333;
        padding: 10px;
        text-align: center;
        margin: 0;
    }

    #resList {
        flex: 1;
        overflow: auto;
        padding: 10px;
        list-style: none;
    }

    #resList li {
        margin-bottom: 8px;
        font-size: 14px;
    }

    @media(max-width:768px) {

        main,
        .calendar-container {
            flex-direction: column;
            padding: 0 15px 15px;
        }

        #sidebar {
            width: 100%;
            margin-top: 15px;
        }
    }

    /* Renovación visual de Reservar Sala */
    .sala-page {
        --sala-navy: #102a56;
        --sala-blue: #3568df;
        --sala-sky: #eaf1ff;
        --sala-ink: #182a4d;
        --sala-muted: #7180a1;
        --sala-line: #e4eaf4;
        background: #f3f6fb;
        padding-bottom: 40px;
    }

    .sala-page .container-fluid {
        max-width: 1600px;
        padding: 24px 28px 0;
    }

    .sala-hero {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        min-height: 190px;
        margin-bottom: 24px;
        padding: 34px 38px;
        color: #fff;
        border-radius: 22px;
        background: linear-gradient(125deg, #102a56 0%, #2457bd 58%, #4a7be8 100%);
        box-shadow: 0 18px 42px rgba(36, 87, 189, .22);
    }

    .sala-hero::before,
    .sala-hero::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
    }

    .sala-hero::before {
        width: 280px;
        height: 280px;
        right: -72px;
        top: -152px;
    }

    .sala-hero::after {
        width: 170px;
        height: 170px;
        right: 180px;
        bottom: -120px;
    }

    .sala-hero__copy,
    .sala-hero__stats {
        position: relative;
        z-index: 1;
    }

    .sala-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        color: #cbdcff;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
    }

    .sala-eyebrow::before {
        content: '';
        width: 26px;
        height: 2px;
        border-radius: 5px;
        background: #8db1ff;
    }

    .sala-hero h1 {
        margin: 0 0 10px;
        color: #fff;
        font-size: clamp(28px, 3vw, 42px);
        font-weight: 650;
        letter-spacing: -.035em;
    }

    .sala-hero p {
        max-width: 650px;
        margin: 0;
        color: rgba(255, 255, 255, .8);
        font-size: 14px;
    }

    .sala-hero__stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(125px, 1fr));
        gap: 12px;
        min-width: 290px;
    }

    .sala-stat {
        padding: 18px 20px;
        border: 1px solid rgba(255, 255, 255, .18);
        border-radius: 16px;
        background: rgba(255, 255, 255, .12);
        backdrop-filter: blur(8px);
    }

    .sala-stat span {
        display: block;
        margin-bottom: 5px;
        color: #dce7ff;
        font-size: 11px;
        font-weight: 600;
    }

    .sala-stat strong {
        display: block;
        color: #fff;
        font-size: 27px;
        line-height: 1;
    }

    .sala-panel {
        height: 100%;
        margin-bottom: 24px;
        overflow: hidden;
        border: 1px solid var(--sala-line);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 10px 28px rgba(27, 48, 89, .07);
    }

    .sala-panel__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 22px 24px;
        border-bottom: 1px solid var(--sala-line);
    }

    .sala-panel__title {
        margin: 0;
        color: var(--sala-ink);
        font-size: 17px;
        font-weight: 650;
    }

    .sala-panel__subtitle {
        margin: 4px 0 0;
        color: var(--sala-muted);
        font-size: 12px;
    }

    .sala-date-pill {
        flex: 0 0 auto;
        padding: 7px 11px;
        color: #285dc9;
        border-radius: 30px;
        background: var(--sala-sky);
        font-size: 11px;
        font-weight: 700;
    }

    .sala-agenda {
        max-height: 670px;
        padding: 10px 18px 18px;
        overflow-y: auto;
    }

    .sala-agenda__item {
        display: grid;
        grid-template-columns: 54px minmax(0, 1fr);
        gap: 13px;
        align-items: center;
        padding: 15px 4px;
        border-bottom: 1px solid #edf1f7;
    }

    .sala-agenda__item:last-child { border-bottom: 0; }

    .sala-agenda__image {
        width: 54px;
        height: 54px;
        object-fit: cover;
        border: 3px solid #fff;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(30, 55, 104, .14);
        transition: transform .2s ease;
    }

    .sala-agenda__image:hover { transform: translateY(-2px); }

    .sala-agenda__name {
        overflow: hidden;
        margin: 0 0 5px;
        color: var(--sala-ink);
        font-size: 13px;
        font-weight: 650;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .sala-agenda__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        color: var(--sala-muted);
        font-size: 11px;
    }

    .sala-room-badge {
        padding: 3px 8px;
        color: #285dc9;
        border-radius: 20px;
        background: var(--sala-sky);
        font-weight: 700;
    }

    .sala-empty {
        padding: 55px 24px;
        text-align: center;
    }

    .sala-empty__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 58px;
        height: 58px;
        margin-bottom: 14px;
        color: var(--sala-blue);
        border-radius: 18px;
        background: var(--sala-sky);
        font-size: 25px;
    }

    .sala-empty strong { display: block; color: var(--sala-ink); }
    .sala-empty span { display: block; margin-top: 5px; color: var(--sala-muted); font-size: 12px; }

    .sala-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 0 24px 18px;
    }

    .sala-legend span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #667493;
        font-size: 11px;
    }

    .sala-legend i {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .sala-calendar-wrap { padding: 6px 22px 24px; }
    .sala-page #calendario { min-height: 610px; }

    .sala-page .fc-toolbar h2 {
        color: var(--sala-ink);
        font-size: 19px;
        font-weight: 650;
        text-transform: capitalize;
    }

    .sala-page .fc-button,
    .sala-page .fc .fc-button {
        min-height: 35px;
        padding: 7px 12px !important;
        color: #50617f !important;
        border: 1px solid #dce4f1 !important;
        border-radius: 9px !important;
        background: #fff !important;
        box-shadow: none !important;
        animation: none !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        text-transform: capitalize !important;
    }

    .sala-page .fc-button:hover,
    .sala-page .fc-button-active {
        color: #fff !important;
        border-color: var(--sala-blue) !important;
        background: var(--sala-blue) !important;
        transform: none !important;
    }

    .sala-page .fc-day-header {
        padding: 11px 4px;
        color: #63718e;
        background: #f7f9fc;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .sala-page .fc-event {
        padding: 4px 6px;
        border: 0;
        border-radius: 7px;
        box-shadow: 0 3px 8px rgba(27, 48, 89, .12);
    }

    .sala-page .fc-event-title { font-size: 10px; }
    .sala-page .fc-event-details { font-size: 9px; opacity: .9; }

    #calendarModal .modal-content,
    #calendarModal2 .modal-content,
    #verSala .modal-content {
        overflow: hidden;
        border: 0;
        border-radius: 20px;
        box-shadow: 0 28px 70px rgba(12, 35, 74, .25);
    }

    #calendarModal main,
    #calendarModal2 main { padding: 0; background: #f7f9fc; }

    #calendarModal2 .card {
        margin: 0;
        border: 0;
        border-radius: 0;
        box-shadow: none;
    }

    #calendarModal .step,
    #calendarModal2 .step {
        max-width: none;
        padding: 30px;
        border-radius: 0;
        background: #fff;
        box-shadow: none;
    }

    #calendarModal .step h2,
    #calendarModal2 .step h2 {
        padding: 0;
        color: var(--sala-ink);
        background: none;
        text-shadow: none;
        font-size: 21px;
    }

    #calendarModal .rooms {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-top: 22px;
    }

    #calendarModal .room {
        position: relative;
        display: grid;
        grid-template-columns: 82px 1fr;
        gap: 15px;
        align-items: center;
        padding: 13px;
        text-align: left;
        border: 1px solid var(--sala-line);
        border-radius: 15px;
        background: #fff;
    }

    #calendarModal .room:hover {
        border-color: #8aabef;
        background: #f7faff;
        box-shadow: 0 9px 22px rgba(36, 87, 189, .1);
        transform: translateY(-2px);
    }

    #calendarModal .room img {
        width: 82px;
        height: 68px;
        object-fit: cover;
        border-radius: 11px;
    }

    #calendarModal .room strong { display: block; color: var(--sala-ink); font-size: 14px; }
    #calendarModal .room small { color: var(--sala-muted); font-size: 11px; }

    #calendarModal2 .form-control {
        height: 43px;
        border: 1px solid #dfe6f1;
        border-radius: 10px;
        background: #fbfcfe;
        box-shadow: none;
    }

    #calendarModal2 label {
        margin-bottom: 7px;
        color: #4d5c78;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    #calendarModal2 .wizard-actions { width: 100%; margin-top: 10px; }
    #calendarModal2 #finishWizard {
        min-width: 150px;
        padding: 11px 22px;
        border-radius: 10px;
        background: linear-gradient(135deg, #2457bd, #4277e8);
        box-shadow: 0 8px 20px rgba(36, 87, 189, .22);
        animation: none;
        font-weight: 650;
    }

    @media (max-width: 991.98px) {
        .sala-page .container-fluid { padding: 18px 16px 0; }
        .sala-hero { align-items: flex-start; flex-direction: column; padding: 28px; }
        .sala-hero__stats { width: 100%; min-width: 0; }
        .sala-agenda { max-height: 420px; }
    }

    @media (max-width: 767.98px) {
        .sala-hero { border-radius: 16px; }
        .sala-panel__header { align-items: flex-start; flex-direction: column; }
        .sala-calendar-wrap { padding: 4px 12px 18px; overflow-x: auto; }
        .sala-page #calendario { min-width: 720px; }
        #calendarModal .rooms { grid-template-columns: 1fr; }
        .sala-page .fc-toolbar { align-items: flex-start; flex-direction: column; gap: 10px; }
    }
</style>

<div class="page-wrapper">
    <div class="page-content-tab sala-page">
        <div class="container-fluid">
            <section class="sala-hero" aria-labelledby="salaPageTitle">
                <div class="sala-hero__copy">
                    <span class="sala-eyebrow">Gestión de espacios</span>
                    <h1 id="salaPageTitle">Reserva tu sala</h1>
                    <p>Consulta la disponibilidad y agenda un espacio para tu próxima reunión de forma rápida y sencilla.</p>
                </div>
                <div class="sala-hero__stats" aria-label="Resumen de salas">
                    <div class="sala-stat">
                        <span>Reservas de hoy</span>
                        <strong><?= count($sala_hoy) ?></strong>
                    </div>
                    <div class="sala-stat">
                        <span>Espacios disponibles</span>
                        <strong>4</strong>
                    </div>
                </div>
            </section>

            <div class="row align-items-stretch">
                <div class="col-xl-4 col-lg-5">
                    <section class="sala-panel" aria-labelledby="agendaHoyTitle">
                        <header class="sala-panel__header">
                            <div>
                                <h2 class="sala-panel__title" id="agendaHoyTitle">Agenda de hoy</h2>
                                <p class="sala-panel__subtitle">Reuniones programadas para este día</p>
                            </div>
                            <span class="sala-date-pill"><?= date('d/m/Y') ?></span>
                        </header>

                        <div class="sala-agenda">
                            <?php if (empty($sala_hoy)): ?>
                                <div class="sala-empty">
                                    <span class="sala-empty__icon"><i class="far fa-calendar-check"></i></span>
                                    <strong>No hay reservas para hoy</strong>
                                    <span>Selecciona una fecha en el calendario para comenzar.</span>
                                </div>
                            <?php else: ?>
                                <?php
                                $imagenesSala = [
                                    'A' => ['archivo' => 'salaA.jpg', 'modal' => 1],
                                    'B' => ['archivo' => 'salaB.jpg', 'modal' => 2],
                                    'AB' => ['archivo' => 'salaAB.jpg', 'modal' => 3],
                                    'TI' => ['archivo' => 'salaTI.jpg', 'modal' => 4],
                                ];
                                ?>
                                <?php foreach ($sala_hoy as $s): ?>
                                    <?php $datosSala = $imagenesSala[$s->sala] ?? $imagenesSala['A']; ?>
                                    <article class="sala-agenda__item">
                                        <a href="javascript:void(0)" onclick="st.agregar.modalSala(<?= $datosSala['modal'] ?>);"
                                            aria-label="Ver sala <?= esc($s->sala) ?>">
                                            <img src="<?= base_url('assets/images/fotos/alba/salas/' . $datosSala['archivo']) ?>"
                                                class="sala-agenda__image" alt="Sala <?= esc($s->sala) ?>">
                                        </a>
                                        <div>
                                            <h3 class="sala-agenda__name"><?= esc($s->evento) ?></h3>
                                            <div class="sala-agenda__meta">
                                                <span class="sala-room-badge">Sala <?= esc($s->sala) ?></span>
                                                <span><i class="far fa-clock mr-1"></i><?= esc(substr($s->hora_inicio, 0, 5)) ?>–<?= esc(substr($s->hora_fin, 0, 5)) ?></span>
                                            </div>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>

                <div class="col-xl-8 col-lg-7">
                    <section class="sala-panel" aria-labelledby="calendarioTitle">
                        <header class="sala-panel__header">
                            <div>
                                <h2 class="sala-panel__title" id="calendarioTitle">Calendario de disponibilidad</h2>
                                <p class="sala-panel__subtitle">Haz clic en un día disponible para crear una reserva</p>
                            </div>
                            <span class="sala-date-pill"><i class="far fa-calendar-alt mr-1"></i> Lunes a viernes</span>
                        </header>
                        <div class="sala-legend" aria-label="Identificación de salas">
                            <span><i style="background:#007bff"></i>Sala A</span>
                            <span><i style="background:#28a745"></i>Sala B</span>
                            <span><i style="background:#6f42c1"></i>Combinada</span>
                            <span><i style="background:#fd7e14"></i>Sala TI</span>
                        </div>
                        <div class="sala-calendar-wrap">
                            <div id="calendario"></div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="verSala" tabindex="-1" aria-labelledby="verSalaTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verSalaTitle">Conoce nuestras salas</h5>
                <button type="button" onclick="st.agregar.cerrarSala()" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Aquí se reemplazará el contenido -->
                <div class="met-profile-main-pic3 text-center"></div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="st.agregar.cerrarSala()" class="btn btn-danger"
                    data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" id="modal1">

        </div>
    </div>
</div>
<div class="modal fade" id="calendarModal2" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <main>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body step active">
                                <h2 class="mt-0 header-title">2) Detalles de la reserva</h2>
                                <p class="text-muted mb-3">Sala seleccionada: <span id="chosenRoom"></span></p>
                                <form id="registroSala">
                                    <input type="hidden" id="sala" name="sala">
                                    <div class="row">
                                        <div class="col-lg-6">

                                            <div class="form-group">
                                                <label for="fecha">Fecha</label>
                                                <div class="input-group">
                                                    <input type="date" class="form-control" id="fecha" name="fecha"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="hora_inicio">Hora inicio</label>
                                                <div class="input-group">
                                                    <input type="time" id="hora_inicio" name="hora_inicio"
                                                        class="form-control">

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="hora_fin">Hora fin</label>
                                                <div class="input-group">
                                                    <input type="time" id="hora_fin" name="hora_fin"
                                                        class="form-control">

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="evento">Nombre del Evento</label>
                                                <div class="input-group">
                                                    <input type="text" id="evento" name="evento" class="form-control"
                                                        autocomplete="off">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="asistentes"># Asistentes</label>
                                                <div class="input-group">
                                                    <input type="number" id="asistentes" name="asistentes"
                                                        class="form-control">

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



<!-- Las dependencias base se cargan desde la plantilla principal. -->
<script src="<?php echo base_url() ?>plugins/moment/moment.js"></script>
<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/core/main.js'></script>
<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/daygrid/main.js'></script>
<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/timegrid/main.js'></script>
<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/interaction/main.js'></script>
<script src='<?php echo base_url() ?>plugins/fullcalendar/packages/list/main.js'></script>
<script src='<?php echo base_url() ?>assets/pages/jquery.calendar.js'></script>


<script>
    var finishWizardButton = document.getElementById('finishWizard');
    if (finishWizardButton) {
        finishWizardButton.innerHTML = 'Confirmar reserva <i class="fas fa-arrow-right ml-2"></i>';
    }

    st.agregar.registroSala();

    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendario');
        const salas = <?= json_encode($sala_junta ?? []); ?>;
        const id_perfil = <?= $session->get('id_perfil'); ?>;
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

                if (seleccionadaTimestamp < hoyTimestamp) {
                    Swal.fire("Error", "No puedes agendar en una fecha pasada", "error");
                    return;
                }

                st.agregar.calendarModal();
                let html = `<main>
                    <div id="step1" class="step active">
                        <span class="sala-eyebrow" style="color:#3568df">Nueva reserva</span>
                        <h2>Selecciona la sala</h2>
                        <p class="text-muted">Elige el espacio que mejor se adapte a tu reunión.</p>
                        <div class="rooms">
                            <div class="room" data-sala="A"><img src="<?= base_url('assets/images/fotos/alba/salas/salaA.jpg') ?>" alt="Sala A"><div><strong>Sala A</strong><small>Espacio para reuniones</small></div></div>
                            <div class="room" data-sala="B"><img src="<?= base_url('assets/images/fotos/alba/salas/salaB.jpg') ?>" alt="Sala B"><div><strong>Sala B</strong><small>Espacio para reuniones</small></div></div>
                            <div class="room" data-sala="AB"><img src="<?= base_url('assets/images/fotos/alba/salas/salaAB.jpg') ?>" alt="Sala combinada"><div><strong>Sala combinada</strong><small>Mayor capacidad</small></div></div>
                            <div class="room" data-sala="TI"><img src="<?= base_url('assets/images/fotos/alba/salas/salaTI.jpg') ?>" alt="Sala TI"><div><strong>Sala TI</strong><small>Espacio con tecnología</small></div></div>
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
                        $.ajax({
                            type: "POST",
                            url: base_url + "index.php/Usuario/deleteSala",
                            dataType: "json",
                            data: { id_sala_juntas: eventId },
                            success: function (data) {
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
