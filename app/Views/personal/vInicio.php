<!-- Page Content-->
<?php $session = \Config\Services::session(); ?>
<style>
    .met-profile-main-pic {
        /* tamaño del marco */
        width: 125px;
        /* ajusta a lo que necesites */
        aspect-ratio: 1 / 1;
        /* círculo perfecto; si no tienes soporte, usa height:125px */
        border-radius: 50%;
        overflow: hidden;
        /* recorta lo que sobresalga */
        position: relative;
        /* (opcional) “margen” visual tipo marco */
        padding: 4px;
        background: #fff;
        box-shadow: 0 0 0 2px #e5e7eb;
        /* borde suave */
    }

    .met-profile-main-pic .avatar {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        /* rellena sin deformar, recortando */
        object-position: center;
        /* centrada */
        border-radius: 50%;
    }

    /* Botón de cámara */
    .fro-profile_main-pic-change {
        position: absolute;
        right: 6px;
        bottom: 6px;
        background: rgba(0, 0, 0, .6);
        color: #fff;
        border-radius: 9999px;
        padding: 6px;
        line-height: 1;
        cursor: pointer;
        font-size: 12px;
    }

    /* Avatar genérico */
    .avatar {
        border-radius: 50%;
        overflow: hidden;
        display: inline-block;
        position: relative;
    }

    /* Tamaño pequeño (ajusta a tu gusto) */
    .avatar-sm {
        width: 40px;
        height: 40px;
    }

    /* Imagen rellenando el círculo sin deformarse */
    .avatar img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        object-position: center;
    }

    /* Imagen grande del carousel */
    .thumb-xl {
        width: 128px;
        /* ajusta a tu gusto (96/128/160) */
        height: 128px;
        border-radius: 12px;
        /* usa 50% si la quieres circular */
        object-fit: cover;
        /* rellena sin deformar, recorta exceso */
        object-position: center;
        flex-shrink: 0;
        /* evita que la imagen se aplaste en el .media */
    }

    /* (Opcional) si notas saltos de layout entre slides, fija una altura mínima */
    .carousel-item .media {
        min-height: 140px;
        /* un poco más que la imagen para textos cortos */
    }

    .float-color-icon {
        animation: floatAndColor 6s infinite ease-in-out;
    }

    @keyframes floatAndColor {

        /* Fase 1 - Dorado */
        0% {
            transform: translateY(0px);
            color: #ffc107;
            /* text-warning */
        }

        10% {
            transform: translateY(-8px);
            color: #ffc107;
        }

        /* Fase 2 - Azul */
        20% {
            transform: translateY(0px);
            color: #007bff;
            /* text-primary */
        }

        30% {
            transform: translateY(-8px);
            color: #007bff;
        }

        /* Fase 3 - Verde */
        40% {
            transform: translateY(0px);
            color: #28a745;
            /* text-success */
        }

        50% {
            transform: translateY(-8px);
            color: #28a745;
        }

        /* Fase 4 - Rojo/Naranja */
        60% {
            transform: translateY(0px);
            color: #fd7e14;
            /* text-orange */
        }

        70% {
            transform: translateY(-8px);
            color: #fd7e14;
        }

        /* Fase 5 - Púrpura */
        80% {
            transform: translateY(0px);
            color: #6f42c1;
            /* text-purple */
        }

        90% {
            transform: translateY(-8px);
            color: #6f42c1;
        }

        /* Regreso al inicio */
        100% {
            transform: translateY(0px);
            color: #ffc107;
        }
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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Mi espacio</a></li>
                                <li class="breadcrumb-item active">MI perfil</li>
                            </ol>
                        </div>
                        <h4 class="page-title">MIS DATOS</h4>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>


            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body  met-pro-bg">
                            <div class="met-profile">
                                <div class="row">
                                    <div class="col-lg-4 align-self-center mb-3 mb-lg-0">
                                        <div class="met-profile-main">
                                            <div class="met-profile-main-pic">
                                                <img src="<?= empty($datos->ruta_foto_relativa)
                                                    ? base_url() . 'assets/images/users/patient-pro.png'
                                                    : base_url() . $datos->ruta_foto_relativa ?>" alt="Foto de perfil"
                                                    class="avatar">

                                                <span class="fro-profile_main-pic-change"
                                                    onclick="ini.inicio.abrirModalFoto();">
                                                    <i class="fas fa-camera"></i>
                                                </span>
                                            </div>
                                            <div class="met-profile_user-detail">
                                                <h5 class="met-user-name"><?= $session->nombre_completo ?></h5>
                                                <p class="mb-0 met-user-name-post"><?= $datos->dsc_perfil ?></p>
                                            </div>

                                        </div>
                                    </div><!--end col-->
                                    <div class="col-lg-4 ml-auto">
                                        <ul class="list-unstyled personal-detail">
                                            <li class=""><i class="dripicons-user mr-2 text-info font-18"></i> <b> No. Personal </b> :
                                                <?= (!empty($datos->no_empleado)) ? $datos->no_empleado : '' ?>
                                            </li>
                                            <li class="mt-2"><i class="dripicons-mail text-info font-18 mt-2 mr-2"></i>
                                                <b> Correo </b> : <?= (!empty($datos->correo)) ? $datos->correo : '' ?>
                                            </li>
                                            <li class="mt-2"><i
                                                    class="dripicons-location text-info font-18 mt-2 mr-2"></i>
                                                <b>Ubicacion</b> : Silao, Gto
                                            </li>
                                        </ul>
                                        <div class="button-list btn-social-icon">
                                            <button type="button" class="btn btn-blue btn-circle">
                                                <i class="fab fa-facebook-f"></i>
                                            </button>

                                            <button type="button" class="btn btn-secondary btn-circle ml-2">
                                                <i class="fab fa-twitter"></i>
                                            </button>

                                            <button type="button" class="btn btn-pink btn-circle  ml-2">
                                                <i class="fab fa-dribbble"></i>
                                            </button>
                                        </div>
                                    </div><!--end col-->
                                </div><!--end row-->
                            </div><!--end f_profile-->
                        </div><!--end card-body-->
                        <div class="card-body">
                            <ul class="nav nav-pills mb-0" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="general_detail_tab" data-toggle="pill"
                                        href="#general_detail">General</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="activity_detail_tab" data-toggle="pill"
                                        href="#activity_detail">Actividad</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" id="settings_detail_tab" data-toggle="pill"
                                        href="#settings_detail">Configuración</a>
                                </li>
                            </ul>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->
            </div><!--end row-->
            <div class="row">
                <div class="col-12">
                    <div class="tab-content detail-list" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="general_detail">
                            <div class="row">
                                <div class="col-xl-4">


                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="header-title mt-0 mb-3">Compleaños del mes</h4>
                                            <ul class="list-unsyled m-0 pl-0 transaction-history">
                                                <?php foreach ($personal as $k => $c): ?>
                                                    <?php
                                                    $foto = !empty($c['ruta_foto_relativa'])
                                                        ? base_url() . $c['ruta_foto_relativa']
                                                        : base_url() . 'assets/images/users/user-3.jpg';
                                                    ?>
                                                    <?php if ($c['id_fec_nac'] != "0"): ?>
                                                        <li class="align-items-center d-flex justify-content-between">

                                                            <div class="media">

                                                                <div class="transaction-icon avatar avatar-sm mr-2">
                                                                    <a href="javascript:void(0)"
                                                                        onclick="ini.inicio.verDetallesCumple(<?= $c['id_usuario'] ?>)">
                                                                        <img src="<?= $foto ?>"
                                                                            alt="Foto de <?= htmlspecialchars($c['nombre_completo'] ?? 'usuario', ENT_QUOTES, 'UTF-8') ?>"
                                                                            loading="lazy" width="40" height="40"
                                                                            onerror="this.onerror=null;this.src='<?= base_url() ?>assets/images/users/user-3.jpg';">
                                                                    </a>
                                                                </div>

                                                                <div class="media-body align-self-center">
                                                                    <div class="transaction-data">
                                                                        <h3 class="m-0"><?= $c['nombre_completo']; ?></h3>
                                                                        <p class="text-muted mb-0"><?= $c['dsc_area']; ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php if ($c['id_edad'] != '0'): ?>
                                                                <span class="text-success"><?= $c['dia']; ?></span>
                                                            <?php endif; ?>

                                                        </li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div><!--end card-body-->
                                    </div><!--end card-->


                                </div><!--end col-->

                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="card dash-info-carousel">
                                                <div class="card-body">
                                                    <h4 class="mt-0 header-title mb-4">Protocolo ALBA Guanajuato</h4>
                                                    <div id="carousel_2" class="carousel slide" data-ride="carousel">
                                                        <div class="carousel-inner">
                                                            <?php foreach ($lista_alba as $index => $l): ?>
                                                                <?php
                                                                $nombreCompleto = trim(($l->nombre ?? '') . ' ' . ($l->primer_apellido ?? '') . ' ' . ($l->segundo_apellido ?? ''));
                                                                $foto = !empty($l->foto)
                                                                    ? base_url() . $l->foto
                                                                    : base_url() . 'assets/images/placeholder-xxl.jpg'; // tu placeholder
                                                                ?>
                                                                <div
                                                                    class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                                                    <div class="media">
                                                                        <img src="<?= $foto ?>"
                                                                            alt="Foto de <?= htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8') ?>"
                                                                            class="thumb-xl mr-3 align-self-center"
                                                                            width="128" height="128"
                                                                            loading="<?= $index === 0 ? 'eager' : 'lazy' ?>"
                                                                            onerror="this.onerror=null;this.src='<?= base_url() ?>assets/images/placeholder-xxl.jpg';">
                                                                        <div class="media-body align-self-center">
                                                                            <h4 class="mt-0 mb-1 title-text text-dark">
                                                                                <?= $nombreCompleto ?>
                                                                            </h4>
                                                                            <p class="text-muted mb-1"><?= $l->edad; ?> años
                                                                            </p>
                                                                            <p class="text-muted">Nacionalidad:
                                                                                <?= $l->nacionalidad; ?>
                                                                            </p>
                                                                            <span
                                                                                class="px-2 py-1 bg-soft-pink d-inline-block">Desaparecida</span>
                                                                            <a target="_blank" rel="noopener"
                                                                                href="<?= base_url() . $l->protocolo; ?>"
                                                                                class="bg-soft-purple px-2 py-1">
                                                                                <i class="dripicons-preview"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            <?php endforeach; ?>

                                                        </div>
                                                        <a class="carousel-control-prev" href="#carousel_2"
                                                            role="button" data-slide="prev">
                                                            <span class="carousel-control-prev-icon"
                                                                aria-hidden="true"></span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                        <a class="carousel-control-next" href="#carousel_2"
                                                            role="button" data-slide="next">
                                                            <span class="carousel-control-next-icon"
                                                                aria-hidden="true"></span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-body">
                                                    <a href="<?= base_url()?>assets/pdf/plantillas/Igualdad Laboral_y_No_Discriminación.pdf" target="_blank">
                                                    <p class="mb-0 header-title">
                                                        <i class="mdi mdi-charity text-primary"></i>
                                                        Política de Igualdad Laboral y No Discriminación
                                                    </p>
                                                    </a>
                                                    <a href="<?= base_url()?>assets/pdf/plantillas/Protocolo_para_prevenir.pdf" target="_blank" >
                                                    <p class="mb-0 header-title mt-2">
                                                        <i class="mdi mdi-shield-home-outline me-2 text-danger"></i>
                                                        Protocolo para prevenir y atender la violencia
                                                    </p>
                                                    </a>
                                                </div><!--end card-body-->
                                            </div>


                                        </div><!-- end col-->
                                        <?php if (!$votoHombre || !$votoMujer): ?>
                                            <div class="col-lg-4">
                                                <div class="card client-card">
                                                    <div class="card-body text-center">
                                                        <!-- Ícono con animación de flotación y cambio de colores -->
                                                        <div class="icon-info mb-3">
                                                            <i class="fas fa-award float-color-icon"
                                                                style="font-size: 3rem;">
                                                            </i>
                                                        </div>
                                                        <h5 class="client-name">Postular a la persona</h5>
                                                        <span class="text-muted mr-3">
                                                            RECONOCIMIENTO 2025 SECTURI
                                                        </span>
                                                        <p class="text-muted text-center mt-3">LA PERSONA SERVIDORA PÚBLICA
                                                            HONESTA</p>
                                                        <?php if (!$votoHombre): ?>
                                                            <a href="<?= base_url() . 'index.php/Principal/Postulacion/1' ?>"
                                                                class="btn btn-sm btn-soft-secondary">Hombre</a>
                                                        <?php endif; ?>
                                                        <?php if (!$votoMujer): ?>
                                                            <a href="<?= base_url() . 'index.php/Principal/Postulacion/2' ?>"
                                                                class="btn btn-sm btn-soft-primary">Mujer</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($votoHombre && $votoMujer): ?>
                                            <div class="col-lg-4">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="ribbon4 rib4-warning">
                                                            <span
                                                                class="ribbon4-band ribbon4-band-warning text-white text-center">Figura</span>
                                                        </div><!--end ribbon-->
                                                        <img src="<?= base_url() ?>/assets/images/fotos/foto_3.png" alt=""
                                                            class="d-block mx-auto my-4" height="170">
                                                        <div class="d-flex justify-content-between align-items-center my-4">
                                                            <div>
                                                                <p class="text-muted mb-2">Ombudsperson</p>

                                                            </div>
                                                            <div>

                                                                <ul
                                                                    class="list-inline mb-0 product-review align-self-center">
                                                                    <li class="list-inline-item"><i
                                                                            class="la la-star text-warning font-16"></i>
                                                                    </li>
                                                                    <li class="list-inline-item"><i
                                                                            class="la la-star text-warning font-16 ml-n2"></i>
                                                                    </li>
                                                                    <li class="list-inline-item"><i
                                                                            class="la la-star text-warning font-16 ml-n2"></i>
                                                                    </li>
                                                                    <li class="list-inline-item"><i
                                                                            class="la la-star text-warning font-16 ml-n2"></i>
                                                                    </li>
                                                                    <li class="list-inline-item"><i
                                                                            class="la la-star text-warning font-16 ml-n2"></i>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <a href="<?= base_url()?>index.php/Principal/Ombudsperson" class="btn btn-soft-primary btn-block">Javier Pacheco
                                                            Cano</a>
                                                    </div><!--end card-body-->
                                                </div><!--end card-->
                                            </div>
                                        <?php endif; ?>
                                    </div><!--end row-->


                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="card">
                                                <div class="card-body dash-info-carousel">
                                                    <h4 class="mt-0  mb-4">Código de Conducta</h4>
                                                    <div id="carousel_1" class="carousel slide" data-ride="carousel">
                                                        <div class="carousel-inner">
                                                            <div class="carousel-item">
                                                                <a href="<?= base_url() . 'assets/pdf/plantillas/principio.pdf' ?>"
                                                                    target="_black">
                                                                    <div class="media">
                                                                        <div class="icon-info mb-3">
                                                                            <!-- Constitución, leyes, normas -->
                                                                            <i
                                                                                class="fas fa-balance-scale bg-soft-primary"></i>
                                                                        </div>
                                                                        <div class="media-body align-self-center">
                                                                            <h4 class="mt-0 mb-1 title-text text-dark">
                                                                                Principios</h4>
                                                                            <p class="text-muted mb-0">Ver más</p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>

                                                            <div class="carousel-item">
                                                                <a href="<?= base_url() . 'assets/pdf/plantillas/reglas.pdf' ?>"
                                                                    target="_black">
                                                                    <div class="media">
                                                                        <div class="icon-info mb-3">
                                                                            <!-- Reglas, integridad, lineamientos -->
                                                                            <i class="fas fa-gavel bg-soft-success"></i>
                                                                        </div>
                                                                        <div class="media-body align-self-center">
                                                                            <h4 class="mt-0 mb-1">Reglas de
                                                                                Integridad</h4>
                                                                            <p class="text-muted mb-0">Saber más</p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>

                                                            <div class="carousel-item active">
                                                                <a href="<?= base_url() . 'assets/pdf/plantillas/valores2.pdf' ?>"
                                                                    target="_black">
                                                                    <div class="media">
                                                                        <div class="icon-info mb-3">
                                                                            <!-- Valores, ética, principios -->
                                                                            <i
                                                                                class="fas fa-hand-holding-heart bg-soft-warning"></i>
                                                                        </div>
                                                                        <div class="media-body align-self-center">
                                                                            <h4 class="mt-0 mb-1 title-text">Valores
                                                                            </h4>
                                                                            <p class="text-muted mb-0">Conoce los
                                                                                Valores</p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <a class="carousel-control-prev" href="#carousel_1"
                                                            role="button" data-slide="prev">
                                                            <span class="carousel-control-prev-icon"
                                                                aria-hidden="true"></span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                        <a class="carousel-control-next" href="#carousel_1"
                                                            role="button" data-slide="next">
                                                            <span class="carousel-control-next-icon"
                                                                aria-hidden="true"></span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </div>
                                                </div><!--end card-body-->
                                            </div><!--end card-->

                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="mt-0  mb-4">Código de Ética</h4>
                                                    <div class="blog-card">
                                                        <div class="ratio ratio-16x9"> <!-- BS5 -->
                                                            <iframe
                                                                src="<?= base_url() ?>assets/pdf/plantillas/Codigo_de_Conducta_SECTUR3.pdf"
                                                                title="Código de Conducta" style="border:0"
                                                                loading="lazy" width="100%">
                                                            </iframe>
                                                        </div>
                                                        <p class="text-muted text-truncate">PERIODICO OFICIAL DEL
                                                            GOBIERNO DEL
                                                            ESTADO DE GUANAJUATO.</p>
                                                        <hr class="hr-dashed">
                                                        <div class="d-flex justify-content-between">

                                                            <div class="align-self-center">
                                                                <a href="<?= base_url() ?>assets/pdf/plantillas/Codigo_de_Conducta_SECTUR3.pdf"
                                                                    target="_blank" class="text-primary">Leer más <i
                                                                        class="fas fa-long-arrow-alt-right"></i></a>
                                                            </div>
                                                        </div>
                                                    </div><!--end blog-card-->
                                                </div><!--end card-body-->
                                            </div><!--end card-->

                                        </div> <!--end col-->


                                        <div class="col-lg-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="blog-card">
                                                        <img src="<?= base_url() ?>assets/images/oct.png" alt=""
                                                            class="img-fluid" />
                                                    </div><!--end blog-card-->

                                                </div><!--end card-body-->
                                            </div><!--end card-->
                                        </div><!--end col-->
                                    </div><!--end row-->
                                </div><!--end col-->
                            </div><!--end row-->
                        </div><!--end general detail-->

                        <div class="tab-pane fade" id="activity_detail">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="header-title mt-0 mb-4">Actividades Asignadas</h4>
                                            <div class="slimscroll profile-activity-height">
                                                <div class="activity">

                                                    <?php if (isset($actividad) && !empty($actividad)): ?>
                                                        <?php foreach ($actividad as $a): ?>
                                                            <div class="activity-info">
                                                                <div class="icon-info-activity">
                                                                    <i class="mdi mdi-clipboard-alert bg-soft-warning"></i>
                                                                </div>
                                                                <div class="activity-info-text">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center">
                                                                        <p class="text-muted mb-0 font-14 w-75"><span
                                                                                class="text-dark font-14"><?= $a->actividad ?></span>
                                                                            <?= $a->descripcion ?>
                                                                        </p>
                                                                        <span
                                                                            class="text-muted"><?= date('d/m/Y', strtotime($a->fec_reg)) ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>

                                                </div><!--end activity-->
                                            </div><!--crypot dash activity-->
                                        </div><!--end card-body-->
                                    </div><!--end card-->
                                </div><!--end col-->

                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="header-title mt-0 mb-3">Avance Actividad</h4>
                                            <div class="row">

                                                <div class="col-12 align-self-center">
                                                    <p class="skill-detail">
                                                        Favor de ajustar el porcentaje de las actividad(es)
                                                    </p>
                                                </div>

                                            </div>

                                            <?php if (isset($actividad) && !empty($actividad)): ?>
                                                <?php foreach ($actividad as $a): ?>
                                                    <div class="skills mt-4">
                                                        <div class="skill-box">
                                                            <h4 class="skill-title"><?= $a->actividad ?></h4>
                                                            <div class="p-3">
                                                                <input type="text" class="range_actividad"
                                                                    value="<?= ($a->avance) ?>"
                                                                    data-id="<?= (int) $a->id_actividad ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div> <!--end card-body-->
                                    </div><!--end card-->
                                </div><!--end col-->
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="blog-card">
                                                <div class="ratio ratio-16x9"> <!-- BS5 -->
                                                    <iframe
                                                        src="<?= base_url() ?>assets/pdf/plantillas/Codigo_de_Conducta_SECTUR3.pdf"
                                                        title="Código de Conducta" style="border:0" loading="lazy"
                                                        width="100%">
                                                    </iframe>
                                                </div>

                                                <h4 class="my-3">
                                                    <a href="" class="">Código de Ética</a>
                                                </h4>
                                                <p class="text-muted text-truncate">PERIODICO OFICIAL DEL GOBIERNO DEL
                                                    ESTADO DE GUANAJUATO.</p>
                                                <hr class="hr-dashed">
                                                <div class="d-flex justify-content-between">

                                                    <div class="align-self-center">
                                                        <a href="<?= base_url() ?>assets/pdf/plantillas/Codigo_de_Conducta_SECTUR3.pdf"
                                                            target="_blank" class="text-primary">Leer más <i
                                                                class="fas fa-long-arrow-alt-right"></i></a>
                                                    </div>
                                                </div>
                                            </div><!--end blog-card-->
                                        </div><!--end card-body-->
                                    </div><!--end card-->
                                </div><!--end col-->
                            </div><!--end row-->
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card dash-data-card text-center">
                                        <div class="card-body">
                                            <div class="icon-info mb-3">
                                                <i class="fas fa-ticket-alt bg-soft-warning"></i>
                                            </div>
                                            <h3 class="text-dark"><?= $tiketNuevo ?></h3>
                                            <h6 class="font-14 text-dark">Nuevos Tickets</h6>
                                        </div><!--end card-body-->
                                    </div><!--end card-->
                                </div><!-- end col-->
                                <div class="col-lg-4">
                                    <div class="card dash-data-card text-center">
                                        <div class="card-body">
                                            <div class="icon-info mb-3">
                                                <i class="fab fa-codepen bg-soft-pink"></i>
                                            </div>
                                            <h3 class="text-dark"><?= $tiketProceso ?></h3>
                                            <h6 class="font-14 text-dark">Tickets En Proceso</h6>
                                        </div><!--end card-body-->
                                    </div><!--end card-->
                                </div><!-- end col-->
                                <div class="col-lg-4">
                                    <div class="card dash-data-card text-center">
                                        <div class="card-body">
                                            <div class="icon-info mb-3">
                                                <i class="fas fa-check bg-soft-success"></i>
                                            </div>
                                            <h3 class="text-dark"><?= $tiketConcluido ?></h3>
                                            <h6 class="font-14 text-dark">Tikets Concluidos</h6>
                                        </div><!--end card-body-->
                                    </div><!--end card-->
                                </div><!-- end col-->

                            </div><!--end row-->
                        </div><!--end education detail-->


                        <div class="tab-pane fade" id="settings_detail">
                            <div class="row">
                                <div class="col-lg-12 col-xl-9 mx-auto">
                                    <div class="card">
                                        <div class="card-body">


                                            <div class="">
                                                <form id="formConfiguracion" class="form-horizontal form-material mb-0">
                                                    <h3>Información Visible en SUSI<h3>

                                                            <div class="form-group row">
                                                                <div class="col-md-4">
                                                                    <input type="checkbox"
                                                                        class="checkbox checkbox-primary" name="fec_nac"
                                                                        id="fec_nac" <?= (isset($configuracion) && !empty($configuracion) && $configuracion->fec_nac == 0) ? '' : 'checked' ?>>
                                                                    <label for="fec_nac">Fecha de Nacimiento</label>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="checkbox"
                                                                        class="checkbox checkbox-primary" name="edad"
                                                                        id="edad" <?= (isset($configuracion) && !empty($configuracion) && $configuracion->edad == 0) ? '' : 'checked' ?>>
                                                                    <label for="edad">Edad</label>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="checkbox"
                                                                        class="checkbox checkbox-primary" name="nivel"
                                                                        id="nivel" <?= (isset($configuracion) && !empty($configuracion) && $configuracion->id_nivel == 0) ? '' : 'checked' ?>>
                                                                    <label for="nivel">Nivel</label>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="checkbox"
                                                                        class="checkbox checkbox-primary" name="id_foto"
                                                                        id="id_foto" checked>
                                                                    <label for="no_empleado">Foto</label>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="checkbox"
                                                                        class="checkbox checkbox-primary" name="sexo"
                                                                        id="sexo" <?= (isset($configuracion) && !empty($configuracion) && $configuracion->sexo == 0) ? '' : 'checked' ?>>
                                                                    <label for="sexo">Sexo</label>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="checkbox"
                                                                        class="checkbox checkbox-primary"
                                                                        name="contrato" id="contrato" checked>
                                                                    <label for="contrato">Tipo de Contrato</label>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <button type="submit" id="btnConfig"
                                                                    class="btn btn-gradient-primary btn-sm px-4 mt-3 float-right mb-0">Guardar
                                                                    Configuración</button>
                                                            </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!--end col-->
                            </div><!--end row-->
                        </div><!--end settings detail-->
                    </div><!--end tab-content-->

                </div><!--end col-->
            </div><!--end row-->

        </div><!-- container -->


    </div>
    <!-- end page content -->
</div>
<!-- end page-wrapper -->
<div class="modal fade" id="modalFoto" tabindex="-1" aria-labelledby="modalEliminarReservaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalEliminarReservaLabel">Agregar Imagen</h5>
                <button type="button" onclick="ini.inicio.cerrarModalFoto()" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="met-profile-main-pic">
                    <?php
                    $foto = !empty($datos->ruta_foto_relativa)
                        ? base_url() . $datos->ruta_foto_relativa
                        : base_url() . 'assets/images/users/patient-pro.png';
                    $alt = htmlspecialchars($datos->nombre ?? 'Foto de perfil', ENT_QUOTES, 'UTF-8');
                    ?>
                    <img src="<?= $foto ?>" alt="<?= $alt ?>" class="avatar" width="125" height="125" loading="lazy"
                        onerror="this.onerror=null;this.src='<?= base_url() ?>assets/images/users/patient-pro.png';" />
                </div>
                <label for="foto">Agregar Nueva Foto en JPG o PNG</label>
                <input type="file" class="form-control" id="foto" name="foto" accept=".jpg, .jpeg, .png">
            </div>
            <div class="modal-footer">
                <button type="button" onclick="ini.inicio.cerrarModalFoto()" class="btn btn-danger"
                    data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnFotos" onclick="guardarFoto();" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="verDetallesCumple" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="">CUMPLEAÑERO DEL MES</h5>
                <button type="button" onclick="ini.inicio.closeCumple()" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="met-profile-main-pic2 text-center">


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="ini.inicio.closeCumple()" class="btn btn-danger"
                    data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<link href="<?= base_url() ?>plugins/dropify/css/dropify.min.css" rel="stylesheet">
<link href="<?= base_url() ?>plugins/filter/magnific-popup.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>plugins/lightpick/lightpick.css" rel="stylesheet" />

<!-- App css -->
<link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

<!-- ION Slider -->
<link href="<?= base_url() ?>plugins/ion-rangeslider/ion.rangeSlider.css" rel="stylesheet" type="text/css" />


<!-- jQuery  -->
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery-ui.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.slimscroll.min.js"></script>


<script src="<?= base_url() ?>plugins/dropify/js/dropify.min.js"></script>
<script src="<?= base_url() ?>plugins/moment/moment.js"></script>
<script src="<?= base_url() ?>plugins/filter/isotope.pkgd.min.js"></script>
<script src="<?= base_url() ?>plugins/filter/masonry.pkgd.min.js"></script>
<script src="<?= base_url() ?>plugins/filter/jquery.magnific-popup.min.js"></script>
<script src="<?= base_url() ?>plugins/chartjs/chart.min.js"></script>
<script src="<?= base_url() ?>plugins/chartjs/roundedBar.min.js"></script>
<script src="<?= base_url() ?>plugins/lightpick/lightpick.js"></script>
<script src="<?= base_url() ?>assets/pages/jquery.profile.init.js"></script>

<script src="<?= base_url() ?>plugins/hopscotch/hopscotch.js"></script>
<script src="<?= base_url() ?>assets/pages/jquery.tour.init.js"></script>

<!-- Range slider js -->
<script src="<?= base_url() ?>plugins/ion-rangeslider/ion.rangeSlider.min.js"></script>
<script src="<?= base_url() ?>assets/pages/jquery.rangeslider.init.js"></script>

<script src="<?= base_url(); ?>assets/pages/jquery.analytics_dashboard.init.js"></script>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1"></script>
<script>
    ini.inicio.formConfiguracion();

    $(".range_actividad").each(function () {
        let $input = $(this);
        let idActividad = $input.data("id");

        // Variable para controlar el debounce
        let timeoutId = null;

        $input.ionRangeSlider({
            min: 0,
            max: 100,
            step: 1,
            grid: true,
            onFinish: function (data) {
                // Clear cualquier timeout existente
                clearTimeout(timeoutId);

                // Establecer nuevo timeout (300ms de delay)
                timeoutId = setTimeout(function () {
                    ini.inicio.avanceActividad(idActividad, data.from);
                }, 300);
            }
        });
    });

    function guardarFoto() {
        // Usar JavaScript puro para evitar problemas con jQuery
        const fotoInput = document.getElementById('foto');
        console.log(fotoInput);
        if (!fotoInput) {
            Swal.fire("Error", "No se encontró el campo de imagen", "error");
            return;
        }

        if (!fotoInput.files || fotoInput.files.length === 0) {
            Swal.fire("Atención", "Es requerida la imagen", "info");
            return;
        }

        let foto = fotoInput.files[0];
        let formData = new FormData();
        formData.append('foto', foto);

        $.ajax({
            url: base_url + "index.php/Principal/guardarFoto",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#btnFotos').prop('disabled', true).html('Guardando...');
            },
            success: function (response) {
                if (!response.error) {
                    Swal.fire("Correcto", response.respuesta, "success");
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Swal.fire("Error", "Favor de llamar al Administrador", "error");
                }
            },
            complete: function () {
                $('#btnFotos').prop('disabled', false).html('Guardar');
            },
            error: function (xhr, status, error) {
                console.log(error);
                Swal.fire("Error", "Favor de llamar al Administrador", "error");
            }
        });
    }
</script>