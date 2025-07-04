<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>SUSI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistema de Administración de Capacitación" name="description" />
    <meta content="SAC" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.ico">

    <!-- App css -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

   
    <script src="<?= base_url("/js/general.js") ?>"></script>

    <?php if (isset($scripts)) : foreach ($scripts as $js) : ?>
    <script src="<?php echo base_url() . "/js/{$js}.js" ?>?filever=<?php echo time() ?>" type="text/javascript">
    </script>
    <?php endforeach;
    endif; ?>

    <style>
    /* Asegúrate de que el cuerpo de la página cubra toda la pantalla */
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        background-size: cover;
        background-position: center;
        transition: background-image 1s ease-in-out;
    }

    #particles-js {
        position: absolute;
        width: 100%;
        height: 100%;
    }
    </style>
</head>

<body class="account-body accountbg">
    <script>
    var base_url = "<?php echo base_url(); ?>";

    // Lista de imágenes de fondo
    const backgrounds = [
        "<?php echo base_url(); ?>assets/images/backgrounds/IMG4.jpg",
        "<?php echo base_url(); ?>assets/images/backgrounds/IMG10.jpg",
        "<?php echo base_url(); ?>assets/images/backgrounds/IMG18.jpg",
        "<?php echo base_url(); ?>assets/images/backgrounds/guanajuato.jpg",
        "<?php echo base_url(); ?>assets/images/backgrounds/subterranea.webp",
        "<?php echo base_url(); ?>assets/images/backgrounds/leon.jpg",
        "<?php echo base_url(); ?>assets/images/backgrounds/uni.jpg",

    ];

  function setRandomBackground() {
    const randomIndex = Math.floor(Math.random() * backgrounds.length);
    document.body.style.backgroundImage = `url('${backgrounds[randomIndex]}')`;
    document.body.style.backgroundSize = "cover";
    document.body.style.backgroundPosition = "center";
    document.body.style.backgroundRepeat = "no-repeat";
}

// Versión optimizada para móviles
if(window.innerWidth < 768) {
    document.body.style.backgroundSize = "auto 100%";
}

window.onload = setRandomBackground;
    </script>