<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>SUSI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistema de Administración de Capacitación" name="description" />
    <meta content="SAC" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/huella.png">

    <link href="<?php echo base_url(); ?>plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>plugins/animate/animate.css" rel="stylesheet" type="text/css">

    <!-- App css -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

   
    <script src="<?= base_url("js/general.js") ?>"></script>

    <?php if (isset($scripts)) : foreach ($scripts as $js) : ?>
    <script src="<?php echo base_url() . "js/{$js}.js" ?>?filever=<?php echo time() ?>" type="text/javascript">
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
    /* ===== Fireflies (compiled to plain CSS) ===== */
.firefly{
  position: fixed;
  left: 50%;
  top: 50%;
  width: 0.4vw;
  height: 0.4vw;
  margin: -0.2vw 0 0 9.8vw;
  animation: ease 200s alternate infinite;
  pointer-events: none;
}
.firefly::before,
.firefly::after{
  content: '';
  position: absolute;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  transform-origin: -10vw;
}
.firefly::before{
  background: black;
  opacity: 0.4;
  animation: drift ease alternate infinite;
}
.firefly::after{
  background: white;
  opacity: 0;
  box-shadow: 0 0 0vw 0vw yellow;
  animation: drift ease alternate infinite, flash ease infinite;
}

/* base keyframes preserved from SASS */
@keyframes drift{
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
@keyframes flash{
  0%,30%,100% { opacity: 0; box-shadow: 0 0 0vw 0vw yellow; }
  5% { opacity: 1; box-shadow: 0 0 2vw 0.4vw yellow; }
}

/* ---- Firefly 1 ---- */
.firefly:nth-child(1){ animation-name: move1; }
.firefly:nth-child(1)::before{ animation-duration: 10s; }
.firefly:nth-child(1)::after{ animation-duration: 10s, 5205ms; animation-delay: 0ms, 6575ms; }
@keyframes move1{
  0.0000% { transform: translateX(-14vw) translateY(-18vh) scale(0.54); }
  3.7037% { transform: translateX(-32vw) translateY(45vh) scale(0.39); }
  7.4074% { transform: translateX(37vw) translateY(45vh) scale(0.95); }
  11.1111% { transform: translateX(-38vw) translateY(26vh) scale(0.80); }
  14.8148% { transform: translateX(-45vw) translateY(-46vh) scale(0.37); }
  18.5185% { transform: translateX(-22vw) translateY(-20vh) scale(0.90); }
  22.2222% { transform: translateX(28vw) translateY(-46vh) scale(0.97); }
  25.9259% { transform: translateX(-24vw) translateY(42vh) scale(0.95); }
  29.6296% { transform: translateX(4vw) translateY(-21vh) scale(0.83); }
  33.3333% { transform: translateX(26vw) translateY(-14vh) scale(0.26); }
  37.0370% { transform: translateX(48vw) translateY(-29vh) scale(0.80); }
  40.7407% { transform: translateX(-6vw) translateY(-14vh) scale(0.45); }
  44.4444% { transform: translateX(-22vw) translateY(48vh) scale(0.69); }
  48.1481% { transform: translateX(-36vw) translateY(-38vh) scale(0.74); }
  51.8519% { transform: translateX(-37vw) translateY(-4vh) scale(0.70); }
  55.5556% { transform: translateX(28vw) translateY(-16vh) scale(0.31); }
  59.2593% { transform: translateX(44vw) translateY(9vh) scale(0.94); }
  62.9630% { transform: translateX(-34vw) translateY(-1vh) scale(0.36); }
  66.6667% { transform: translateX(21vw) translateY(-12vh) scale(0.72); }
  70.3704% { transform: translateX(24vw) translateY(-25vh) scale(0.34); }
  74.0741% { transform: translateX(-44vw) translateY(35vh) scale(0.55); }
  77.7778% { transform: translateX(49vw) translateY(-12vh) scale(0.36); }
  81.4815% { transform: translateX(-20vw) translateY(-37vh) scale(0.74); }
  85.1852% { transform: translateX(-14vw) translateY(9vh) scale(0.72); }
  88.8889% { transform: translateX(-29vw) translateY(-2vh) scale(0.71); }
  92.5926% { transform: translateX(-23vw) translateY(36vh) scale(0.60); }
  96.2963% { transform: translateX(40vw) translateY(38vh) scale(0.35); }
  100.0000% { transform: translateX(28vw) translateY(32vh) scale(0.47); }
}


/* ---- Firefly 2 ---- */
.firefly:nth-child(2){ animation-name: move2; }
.firefly:nth-child(2)::before{ animation-duration: 12s; }
.firefly:nth-child(2)::after{ animation-duration: 12s, 6339ms; animation-delay: 0ms, 4287ms; }
@keyframes move2{
  0.0000% { transform: translateX(-1vw) translateY(-15vh) scale(0.97); }
  4.0000% { transform: translateX(-21vw) translateY(38vh) scale(0.67); }
  8.0000% { transform: translateX(49vw) translateY(50vh) scale(0.33); }
  12.0000% { transform: translateX(-20vw) translateY(-45vh) scale(0.66); }
  16.0000% { transform: translateX(2vw) translateY(-15vh) scale(0.34); }
  20.0000% { transform: translateX(-22vw) translateY(23vh) scale(0.66); }
  24.0000% { transform: translateX(-22vw) translateY(34vh) scale(0.89); }
  28.0000% { transform: translateX(1vw) translateY(33vh) scale(0.84); }
  32.0000% { transform: translateX(-31vw) translateY(-16vh) scale(0.43); }
  36.0000% { transform: translateX(-18vw) translateY(46vh) scale(0.97); }
  40.0000% { transform: translateX(19vw) translateY(-16vh) scale(1.00); }
  44.0000% { transform: translateX(5vw) translateY(25vh) scale(0.77); }
  48.0000% { transform: translateX(-3vw) translateY(-21vh) scale(0.43); }
  52.0000% { transform: translateX(16vw) translateY(14vh) scale(0.37); }
  56.0000% { transform: translateX(47vw) translateY(-43vh) scale(0.40); }
  60.0000% { transform: translateX(-30vw) translateY(31vh) scale(0.46); }
  64.0000% { transform: translateX(38vw) translateY(5vh) scale(0.34); }
  68.0000% { transform: translateX(0vw) translateY(-1vh) scale(0.85); }
  72.0000% { transform: translateX(18vw) translateY(-17vh) scale(0.96); }
  76.0000% { transform: translateX(-48vw) translateY(38vh) scale(0.40); }
  80.0000% { transform: translateX(38vw) translateY(19vh) scale(0.60); }
  84.0000% { transform: translateX(49vw) translateY(33vh) scale(0.69); }
  88.0000% { transform: translateX(-35vw) translateY(-12vh) scale(0.81); }
  92.0000% { transform: translateX(-29vw) translateY(9vh) scale(0.26); }
  96.0000% { transform: translateX(43vw) translateY(43vh) scale(0.59); }
  100.0000% { transform: translateX(15vw) translateY(48vh) scale(0.48); }
}


/* ---- Firefly 3 ---- */
.firefly:nth-child(3){ animation-name: move3; }
.firefly:nth-child(3)::before{ animation-duration: 10s; }
.firefly:nth-child(3)::after{ animation-duration: 10s, 10123ms; animation-delay: 0ms, 2945ms; }
@keyframes move3{
  0.0000% { transform: translateX(32vw) translateY(15vh) scale(0.51); }
  4.0000% { transform: translateX(-30vw) translateY(-2vh) scale(0.46); }
  8.0000% { transform: translateX(20vw) translateY(50vh) scale(0.93); }
  12.0000% { transform: translateX(-49vw) translateY(27vh) scale(0.67); }
  16.0000% { transform: translateX(13vw) translateY(-47vh) scale(0.40); }
  20.0000% { transform: translateX(-3vw) translateY(-10vh) scale(0.56); }
  24.0000% { transform: translateX(-42vw) translateY(-19vh) scale(0.98); }
  28.0000% { transform: translateX(-39vw) translateY(-39vh) scale(0.88); }
  32.0000% { transform: translateX(-41vw) translateY(48vh) scale(0.94); }
  36.0000% { transform: translateX(49vw) translateY(-33vh) scale(0.42); }
  40.0000% { transform: translateX(35vw) translateY(11vh) scale(0.96); }
  44.0000% { transform: translateX(-28vw) translateY(-16vh) scale(0.93); }
  48.0000% { transform: translateX(28vw) translateY(5vh) scale(0.53); }
  52.0000% { transform: translateX(20vw) translateY(47vh) scale(0.51); }
  56.0000% { transform: translateX(42vw) translateY(-10vh) scale(0.77); }
  60.0000% { transform: translateX(36vw) translateY(34vh) scale(0.73); }
  64.0000% { transform: translateX(7vw) translateY(17vh) scale(0.83); }
  68.0000% { transform: translateX(-34vw) translateY(-18vh) scale(0.54); }
  72.0000% { transform: translateX(-41vw) translateY(-6vh) scale(0.28); }
  76.0000% { transform: translateX(26vw) translateY(21vh) scale(0.55); }
  80.0000% { transform: translateX(26vw) translateY(-21vh) scale(0.26); }
  84.0000% { transform: translateX(-40vw) translateY(41vh) scale(0.33); }
  88.0000% { transform: translateX(-20vw) translateY(-41vh) scale(0.30); }
  92.0000% { transform: translateX(-7vw) translateY(-40vh) scale(0.91); }
  96.0000% { transform: translateX(-19vw) translateY(-14vh) scale(0.88); }
  100.0000% { transform: translateX(-22vw) translateY(20vh) scale(0.42); }
}


/* ---- Firefly 4 ---- */
.firefly:nth-child(4){ animation-name: move4; }
.firefly:nth-child(4)::before{ animation-duration: 18s; }
.firefly:nth-child(4)::after{ animation-duration: 18s, 9721ms; animation-delay: 0ms, 4373ms; }
@keyframes move4{
  0.0000% { transform: translateX(-18vw) translateY(11vh) scale(0.78); }
  3.5714% { transform: translateX(-25vw) translateY(-37vh) scale(0.38); }
  7.1429% { transform: translateX(35vw) translateY(6vh) scale(0.71); }
  10.7143% { transform: translateX(5vw) translateY(3vh) scale(0.85); }
  14.2857% { transform: translateX(44vw) translateY(-43vh) scale(0.38); }
  17.8571% { transform: translateX(-42vw) translateY(2vh) scale(0.69); }
  21.4286% { transform: translateX(-36vw) translateY(-18vh) scale(0.50); }
  25.0000% { transform: translateX(-25vw) translateY(19vh) scale(0.83); }
  28.5714% { transform: translateX(-32vw) translateY(5vh) scale(0.49); }
  32.1429% { transform: translateX(-14vw) translateY(10vh) scale(0.57); }
  35.7143% { transform: translateX(-40vw) translateY(7vh) scale(0.96); }
  39.2857% { transform: translateX(-37vw) translateY(-43vh) scale(0.95); }
  42.8571% { transform: translateX(-48vw) translateY(-38vh) scale(0.56); }
  46.4286% { transform: translateX(-28vw) translateY(3vh) scale(0.88); }
  50.0000% { transform: translateX(12vw) translateY(-22vh) scale(0.77); }
  53.5714% { transform: translateX(-42vw) translateY(-28vh) scale(0.74); }
  57.1429% { transform: translateX(-49vw) translateY(0vh) scale(0.59); }
  60.7143% { transform: translateX(9vw) translateY(-13vh) scale(0.80); }
  64.2857% { transform: translateX(40vw) translateY(44vh) scale(0.97); }
  67.8571% { transform: translateX(35vw) translateY(42vh) scale(0.88); }
  71.4286% { transform: translateX(-30vw) translateY(-25vh) scale(0.63); }
  75.0000% { transform: translateX(-22vw) translateY(-42vh) scale(1.00); }
  78.5714% { transform: translateX(45vw) translateY(20vh) scale(0.33); }
  82.1429% { transform: translateX(46vw) translateY(-9vh) scale(0.33); }
  85.7143% { transform: translateX(-43vw) translateY(25vh) scale(0.87); }
  89.2857% { transform: translateX(15vw) translateY(18vh) scale(0.46); }
  92.8571% { transform: translateX(-42vw) translateY(16vh) scale(0.36); }
  96.4286% { transform: translateX(-26vw) translateY(-41vh) scale(0.34); }
  100.0000% { transform: translateX(37vw) translateY(-19vh) scale(0.77); }
}


/* ---- Firefly 5 ---- */
.firefly:nth-child(5){ animation-name: move5; }
.firefly:nth-child(5)::before{ animation-duration: 18s; }
.firefly:nth-child(5)::after{ animation-duration: 18s, 7017ms; animation-delay: 0ms, 5243ms; }
@keyframes move5{
  0.0000% { transform: translateX(27vw) translateY(-44vh) scale(0.36); }
  5.5556% { transform: translateX(4vw) translateY(35vh) scale(1.00); }
  11.1111% { transform: translateX(23vw) translateY(17vh) scale(0.66); }
  16.6667% { transform: translateX(-16vw) translateY(-23vh) scale(0.66); }
  22.2222% { transform: translateX(-19vw) translateY(-16vh) scale(0.76); }
  27.7778% { transform: translateX(-33vw) translateY(36vh) scale(0.64); }
  33.3333% { transform: translateX(9vw) translateY(-9vh) scale(0.35); }
  38.8889% { transform: translateX(-48vw) translateY(9vh) scale(0.98); }
  44.4444% { transform: translateX(-37vw) translateY(-40vh) scale(0.94); }
  50.0000% { transform: translateX(-22vw) translateY(15vh) scale(0.59); }
  55.5556% { transform: translateX(-33vw) translateY(-5vh) scale(0.34); }
  61.1111% { transform: translateX(-18vw) translateY(-2vh) scale(0.62); }
  66.6667% { transform: translateX(-29vw) translateY(7vh) scale(0.95); }
  72.2222% { transform: translateX(41vw) translateY(-11vh) scale(0.93); }
  77.7778% { transform: translateX(-48vw) translateY(36vh) scale(0.96); }
  83.3333% { transform: translateX(-11vw) translateY(35vh) scale(0.39); }
  88.8889% { transform: translateX(-32vw) translateY(-16vh) scale(0.40); }
  94.4444% { transform: translateX(-36vw) translateY(46vh) scale(0.96); }
  100.0000% { transform: translateX(-30vw) translateY(-15vh) scale(0.62); }
}


/* ---- Firefly 6 ---- */
.firefly:nth-child(6){ animation-name: move6; }
.firefly:nth-child(6)::before{ animation-duration: 12s; }
.firefly:nth-child(6)::after{ animation-duration: 12s, 10879ms; animation-delay: 0ms, 3309ms; }
@keyframes move6{
  0.0000% { transform: translateX(-23vw) translateY(38vh) scale(0.59); }
  3.8462% { transform: translateX(15vw) translateY(13vh) scale(0.58); }
  7.6923% { transform: translateX(-43vw) translateY(-38vh) scale(0.80); }
  11.5385% { transform: translateX(-14vw) translateY(-44vh) scale(0.26); }
  15.3846% { transform: translateX(-7vw) translateY(49vh) scale(0.42); }
  19.2308% { transform: translateX(32vw) translateY(-16vh) scale(0.46); }
  23.0769% { transform: translateX(45vw) translateY(7vh) scale(0.96); }
  26.9231% { transform: translateX(41vw) translateY(5vh) scale(0.97); }
  30.7692% { transform: translateX(-48vw) translateY(-35vh) scale(0.35); }
  34.6154% { transform: translateX(39vw) translateY(-30vh) scale(0.95); }
  38.4615% { transform: translateX(-45vw) translateY(-2vh) scale(1.00); }
  42.3077% { transform: translateX(21vw) translateY(-31vh) scale(0.81); }
  46.1538% { transform: translateX(-33vw) translateY(-44vh) scale(0.65); }
  50.0000% { transform: translateX(-3vw) translateY(-44vh) scale(0.71); }
  53.8462% { transform: translateX(-23vw) translateY(38vh) scale(0.57); }
  57.6923% { transform: translateX(36vw) translateY(-36vh) scale(0.71); }
  61.5385% { transform: translateX(50vw) translateY(22vh) scale(0.78); }
  65.3846% { transform: translateX(30vw) translateY(46vh) scale(0.45); }
  69.2308% { transform: translateX(-19vw) translateY(-29vh) scale(0.48); }
  73.0769% { transform: translateX(3vw) translateY(-46vh) scale(0.48); }
  76.9231% { transform: translateX(45vw) translateY(-7vh) scale(0.78); }
  80.7692% { transform: translateX(36vw) translateY(45vh) scale(0.57); }
  84.6154% { transform: translateX(-15vw) translateY(-29vh) scale(0.39); }
  88.4615% { transform: translateX(-1vw) translateY(-45vh) scale(0.86); }
  92.3077% { transform: translateX(-21vw) translateY(-24vh) scale(0.84); }
  96.1538% { transform: translateX(-5vw) translateY(-10vh) scale(0.55); }
  100.0000% { transform: translateX(-21vw) translateY(-46vh) scale(0.50); }
}


/* ---- Firefly 7 ---- */
.firefly:nth-child(7){ animation-name: move7; }
.firefly:nth-child(7)::before{ animation-duration: 14s; }
.firefly:nth-child(7)::after{ animation-duration: 14s, 7283ms; animation-delay: 0ms, 7581ms; }
@keyframes move7{
  0.0000% { transform: translateX(-41vw) translateY(49vh) scale(0.61); }
  4.3478% { transform: translateX(-5vw) translateY(33vh) scale(0.91); }
  8.6957% { transform: translateX(2vw) translateY(37vh) scale(0.94); }
  13.0435% { transform: translateX(-7vw) translateY(-46vh) scale(0.40); }
  17.3913% { transform: translateX(-16vw) translateY(-27vh) scale(1.00); }
  21.7391% { transform: translateX(-16vw) translateY(-45vh) scale(0.39); }
  26.0870% { transform: translateX(27vw) translateY(6vh) scale(0.70); }
  30.4348% { transform: translateX(44vw) translateY(-9vh) scale(0.81); }
  34.7826% { transform: translateX(28vw) translateY(16vh) scale(0.40); }
  39.1304% { transform: translateX(0vw) translateY(24vh) scale(0.50); }
  43.4783% { transform: translateX(-17vw) translateY(-44vh) scale(0.81); }
  47.8261% { transform: translateX(-49vw) translateY(17vh) scale(0.94); }
  52.1739% { transform: translateX(38vw) translateY(43vh) scale(0.51); }
  56.5217% { transform: translateX(-3vw) translateY(6vh) scale(0.34); }
  60.8696% { transform: translateX(36vw) translateY(-7vh) scale(0.66); }
  65.2174% { transform: translateX(35vw) translateY(-34vh) scale(0.64); }
  69.5652% { transform: translateX(15vw) translateY(-10vh) scale(0.78); }
  73.9130% { transform: translateX(-8vw) translateY(2vh) scale(0.63); }
  78.2609% { transform: translateX(21vw) translateY(-33vh) scale(0.50); }
  82.6087% { transform: translateX(4vw) translateY(36vh) scale(0.74); }
  86.9565% { transform: translateX(37vw) translateY(46vh) scale(0.48); }
  91.3043% { transform: translateX(29vw) translateY(23vh) scale(0.64); }
  95.6522% { transform: translateX(2vw) translateY(21vh) scale(0.26); }
  100.0000% { transform: translateX(-11vw) translateY(-13vh) scale(0.52); }
}


/* ---- Firefly 8 ---- */
.firefly:nth-child(8){ animation-name: move8; }
.firefly:nth-child(8)::before{ animation-duration: 18s; }
.firefly:nth-child(8)::after{ animation-duration: 18s, 9970ms; animation-delay: 0ms, 5864ms; }
@keyframes move8{
  0.0000% { transform: translateX(-8vw) translateY(10vh) scale(0.82); }
  4.3478% { transform: translateX(7vw) translateY(37vh) scale(0.53); }
  8.6957% { transform: translateX(16vw) translateY(11vh) scale(0.47); }
  13.0435% { transform: translateX(35vw) translateY(-39vh) scale(0.62); }
  17.3913% { transform: translateX(16vw) translateY(35vh) scale(0.68); }
  21.7391% { transform: translateX(-38vw) translateY(47vh) scale(0.56); }
  26.0870% { transform: translateX(37vw) translateY(-10vh) scale(0.54); }
  30.4348% { transform: translateX(-24vw) translateY(-31vh) scale(0.29); }
  34.7826% { transform: translateX(-44vw) translateY(-18vh) scale(0.86); }
  39.1304% { transform: translateX(29vw) translateY(49vh) scale(0.35); }
  43.4783% { transform: translateX(9vw) translateY(4vh) scale(0.99); }
  47.8261% { transform: translateX(-25vw) translateY(42vh) scale(0.75); }
  52.1739% { transform: translateX(14vw) translateY(2vh) scale(0.57); }
  56.5217% { transform: translateX(-31vw) translateY(34vh) scale(0.26); }
  60.8696% { transform: translateX(47vw) translateY(49vh) scale(0.39); }
  65.2174% { transform: translateX(50vw) translateY(5vh) scale(0.54); }
  69.5652% { transform: translateX(-27vw) translateY(40vh) scale(0.92); }
  73.9130% { transform: translateX(10vw) translateY(-43vh) scale(0.97); }
  78.2609% { transform: translateX(-18vw) translateY(-34vh) scale(0.84); }
  82.6087% { transform: translateX(-32vw) translateY(10vh) scale(0.93); }
  86.9565% { transform: translateX(22vw) translateY(27vh) scale(0.66); }
  91.3043% { transform: translateX(47vw) translateY(7vh) scale(0.90); }
  95.6522% { transform: translateX(5vw) translateY(21vh) scale(0.83); }
  100.0000% { transform: translateX(-29vw) translateY(46vh) scale(0.86); }
}


/* ---- Firefly 9 ---- */
.firefly:nth-child(9){ animation-name: move9; }
.firefly:nth-child(9)::before{ animation-duration: 13s; }
.firefly:nth-child(9)::after{ animation-duration: 13s, 7026ms; animation-delay: 0ms, 7381ms; }
@keyframes move9{
  0.0000% { transform: translateX(32vw) translateY(-14vh) scale(0.92); }
  4.1667% { transform: translateX(13vw) translateY(31vh) scale(0.56); }
  8.3333% { transform: translateX(-14vw) translateY(7vh) scale(0.35); }
  12.5000% { transform: translateX(42vw) translateY(-13vh) scale(0.56); }
  16.6667% { transform: translateX(-15vw) translateY(-7vh) scale(0.66); }
  20.8333% { transform: translateX(20vw) translateY(-39vh) scale(0.43); }
  25.0000% { transform: translateX(-30vw) translateY(-20vh) scale(0.75); }
  29.1667% { transform: translateX(39vw) translateY(-30vh) scale(0.53); }
  33.3333% { transform: translateX(-41vw) translateY(4vh) scale(0.78); }
  37.5000% { transform: translateX(-7vw) translateY(20vh) scale(0.85); }
  41.6667% { transform: translateX(4vw) translateY(-42vh) scale(0.52); }
  45.8333% { transform: translateX(4vw) translateY(0vh) scale(1.00); }
  50.0000% { transform: translateX(40vw) translateY(-47vh) scale(0.99); }
  54.1667% { transform: translateX(-1vw) translateY(12vh) scale(0.26); }
  58.3333% { transform: translateX(-4vw) translateY(-11vh) scale(0.75); }
  62.5000% { transform: translateX(4vw) translateY(19vh) scale(0.95); }
  66.6667% { transform: translateX(28vw) translateY(-21vh) scale(0.88); }
  70.8333% { transform: translateX(-21vw) translateY(-15vh) scale(0.81); }
  75.0000% { transform: translateX(13vw) translateY(-46vh) scale(0.75); }
  79.1667% { transform: translateX(-6vw) translateY(36vh) scale(0.77); }
  83.3333% { transform: translateX(43vw) translateY(-28vh) scale(0.85); }
  87.5000% { transform: translateX(-33vw) translateY(30vh) scale(0.94); }
  91.6667% { transform: translateX(-46vw) translateY(1vh) scale(0.98); }
  95.8333% { transform: translateX(35vw) translateY(-46vh) scale(0.36); }
  100.0000% { transform: translateX(33vw) translateY(5vh) scale(0.43); }
}


/* ---- Firefly 10 ---- */
.firefly:nth-child(10){ animation-name: move10; }
.firefly:nth-child(10)::before{ animation-duration: 11s; }
.firefly:nth-child(10)::after{ animation-duration: 11s, 5412ms; animation-delay: 0ms, 2632ms; }
@keyframes move10{
  0.0000% { transform: translateX(-1vw) translateY(-8vh) scale(0.53); }
  4.1667% { transform: translateX(9vw) translateY(-8vh) scale(0.69); }
  8.3333% { transform: translateX(48vw) translateY(-1vh) scale(0.61); }
  12.5000% { transform: translateX(47vw) translateY(4vh) scale(0.58); }
  16.6667% { transform: translateX(-39vw) translateY(11vh) scale(0.28); }
  20.8333% { transform: translateX(46vw) translateY(20vh) scale(0.32); }
  25.0000% { transform: translateX(-5vw) translateY(-21vh) scale(0.34); }
  29.1667% { transform: translateX(50vw) translateY(34vh) scale(0.31); }
  33.3333% { transform: translateX(47vw) translateY(-46vh) scale(0.57); }
  37.5000% { transform: translateX(-24vw) translateY(-47vh) scale(0.45); }
  41.6667% { transform: translateX(-19vw) translateY(-33vh) scale(0.86); }
  45.8333% { transform: translateX(36vw) translateY(-35vh) scale(0.98); }
  50.0000% { transform: translateX(-22vw) translateY(10vh) scale(0.58); }
  54.1667% { transform: translateX(49vw) translateY(-2vh) scale(0.47); }
  58.3333% { transform: translateX(28vw) translateY(28vh) scale(0.40); }
  62.5000% { transform: translateX(50vw) translateY(-29vh) scale(0.65); }
  66.6667% { transform: translateX(-36vw) translateY(25vh) scale(0.29); }
  70.8333% { transform: translateX(-10vw) translateY(24vh) scale(0.74); }
  75.0000% { transform: translateX(1vw) translateY(42vh) scale(0.51); }
  79.1667% { transform: translateX(-40vw) translateY(26vh) scale(0.57); }
  83.3333% { transform: translateX(-36vw) translateY(40vh) scale(0.64); }
  87.5000% { transform: translateX(38vw) translateY(27vh) scale(0.41); }
  91.6667% { transform: translateX(23vw) translateY(-44vh) scale(0.70); }
  95.8333% { transform: translateX(19vw) translateY(5vh) scale(0.73); }
  100.0000% { transform: translateX(-41vw) translateY(15vh) scale(0.69); }
}


/* ---- Firefly 11 ---- */
.firefly:nth-child(11){ animation-name: move11; }
.firefly:nth-child(11)::before{ animation-duration: 15s; }
.firefly:nth-child(11)::after{ animation-duration: 15s, 9016ms; animation-delay: 0ms, 1365ms; }
@keyframes move11{
  0.0000% { transform: translateX(6vw) translateY(-3vh) scale(0.84); }
  5.8824% { transform: translateX(41vw) translateY(-30vh) scale(0.81); }
  11.7647% { transform: translateX(-27vw) translateY(44vh) scale(0.92); }
  17.6471% { transform: translateX(34vw) translateY(-15vh) scale(0.94); }
  23.5294% { transform: translateX(50vw) translateY(12vh) scale(0.85); }
  29.4118% { transform: translateX(6vw) translateY(44vh) scale(0.60); }
  35.2941% { transform: translateX(-8vw) translateY(-18vh) scale(0.37); }
  41.1765% { transform: translateX(-14vw) translateY(8vh) scale(0.57); }
  47.0588% { transform: translateX(47vw) translateY(10vh) scale(0.98); }
  52.9412% { transform: translateX(29vw) translateY(36vh) scale(0.74); }
  58.8235% { transform: translateX(-6vw) translateY(-46vh) scale(0.89); }
  64.7059% { transform: translateX(-8vw) translateY(-26vh) scale(0.88); }
  70.5882% { transform: translateX(-22vw) translateY(-4vh) scale(0.59); }
  76.4706% { transform: translateX(-6vw) translateY(-14vh) scale(0.61); }
  82.3529% { transform: translateX(22vw) translateY(-48vh) scale(0.92); }
  88.2353% { transform: translateX(-25vw) translateY(-39vh) scale(0.56); }
  94.1176% { transform: translateX(43vw) translateY(3vh) scale(0.88); }
  100.0000% { transform: translateX(22vw) translateY(48vh) scale(0.56); }
}


/* ---- Firefly 12 ---- */
.firefly:nth-child(12){ animation-name: move12; }
.firefly:nth-child(12)::before{ animation-duration: 16s; }
.firefly:nth-child(12)::after{ animation-duration: 16s, 10292ms; animation-delay: 0ms, 6332ms; }
@keyframes move12{
  0.0000% { transform: translateX(13vw) translateY(8vh) scale(0.28); }
  3.5714% { transform: translateX(-38vw) translateY(-12vh) scale(0.54); }
  7.1429% { transform: translateX(2vw) translateY(39vh) scale(0.57); }
  10.7143% { transform: translateX(-10vw) translateY(35vh) scale(1.00); }
  14.2857% { transform: translateX(-2vw) translateY(11vh) scale(0.96); }
  17.8571% { transform: translateX(18vw) translateY(-5vh) scale(0.80); }
  21.4286% { transform: translateX(46vw) translateY(21vh) scale(0.68); }
  25.0000% { transform: translateX(-4vw) translateY(40vh) scale(0.84); }
  28.5714% { transform: translateX(-15vw) translateY(-10vh) scale(0.58); }
  32.1429% { transform: translateX(-20vw) translateY(-34vh) scale(0.50); }
  35.7143% { transform: translateX(-9vw) translateY(-34vh) scale(0.94); }
  39.2857% { transform: translateX(48vw) translateY(39vh) scale(0.49); }
  42.8571% { transform: translateX(-25vw) translateY(-22vh) scale(0.87); }
  46.4286% { transform: translateX(-14vw) translateY(43vh) scale(0.93); }
  50.0000% { transform: translateX(27vw) translateY(-13vh) scale(0.38); }
  53.5714% { transform: translateX(-25vw) translateY(-12vh) scale(0.55); }
  57.1429% { transform: translateX(-3vw) translateY(-27vh) scale(0.64); }
  60.7143% { transform: translateX(-48vw) translateY(41vh) scale(0.94); }
  64.2857% { transform: translateX(-33vw) translateY(-14vh) scale(0.31); }
  67.8571% { transform: translateX(-43vw) translateY(21vh) scale(0.63); }
  71.4286% { transform: translateX(40vw) translateY(-33vh) scale(0.88); }
  75.0000% { transform: translateX(-36vw) translateY(-48vh) scale(0.99); }
  78.5714% { transform: translateX(-13vw) translateY(11vh) scale(0.87); }
  82.1429% { transform: translateX(7vw) translateY(-6vh) scale(0.49); }
  85.7143% { transform: translateX(-43vw) translateY(-17vh) scale(0.87); }
  89.2857% { transform: translateX(-35vw) translateY(-41vh) scale(0.77); }
  92.8571% { transform: translateX(13vw) translateY(-40vh) scale(0.99); }
  96.4286% { transform: translateX(31vw) translateY(38vh) scale(0.32); }
  100.0000% { transform: translateX(-30vw) translateY(-30vh) scale(0.98); }
}


/* ---- Firefly 13 ---- */
.firefly:nth-child(13){ animation-name: move13; }
.firefly:nth-child(13)::before{ animation-duration: 10s; }
.firefly:nth-child(13)::after{ animation-duration: 10s, 7034ms; animation-delay: 0ms, 1471ms; }
@keyframes move13{
  0.0000% { transform: translateX(22vw) translateY(48vh) scale(0.79); }
  4.7619% { transform: translateX(28vw) translateY(27vh) scale(0.54); }
  9.5238% { transform: translateX(50vw) translateY(17vh) scale(0.74); }
  14.2857% { transform: translateX(8vw) translateY(7vh) scale(0.64); }
  19.0476% { transform: translateX(26vw) translateY(5vh) scale(0.65); }
  23.8095% { transform: translateX(23vw) translateY(30vh) scale(0.33); }
  28.5714% { transform: translateX(29vw) translateY(45vh) scale(0.38); }
  33.3333% { transform: translateX(48vw) translateY(-23vh) scale(0.53); }
  38.0952% { transform: translateX(-16vw) translateY(35vh) scale(0.36); }
  42.8571% { transform: translateX(-29vw) translateY(-19vh) scale(0.48); }
  47.6190% { transform: translateX(21vw) translateY(-40vh) scale(0.46); }
  52.3810% { transform: translateX(-49vw) translateY(3vh) scale(0.83); }
  57.1429% { transform: translateX(39vw) translateY(27vh) scale(0.86); }
  61.9048% { transform: translateX(-12vw) translateY(-45vh) scale(0.55); }
  66.6667% { transform: translateX(-13vw) translateY(41vh) scale(0.62); }
  71.4286% { transform: translateX(40vw) translateY(9vh) scale(0.35); }
  76.1905% { transform: translateX(38vw) translateY(-20vh) scale(0.59); }
  80.9524% { transform: translateX(31vw) translateY(26vh) scale(0.51); }
  85.7143% { transform: translateX(5vw) translateY(-35vh) scale(0.95); }
  90.4762% { transform: translateX(-21vw) translateY(33vh) scale(0.45); }
  95.2381% { transform: translateX(-15vw) translateY(-31vh) scale(0.35); }
  100.0000% { transform: translateX(-42vw) translateY(-28vh) scale(0.65); }
}


/* ---- Firefly 14 ---- */
.firefly:nth-child(14){ animation-name: move14; }
.firefly:nth-child(14)::before{ animation-duration: 18s; }
.firefly:nth-child(14)::after{ animation-duration: 18s, 7365ms; animation-delay: 0ms, 4098ms; }
@keyframes move14{
  0.0000% { transform: translateX(-34vw) translateY(10vh) scale(0.64); }
  3.8462% { transform: translateX(40vw) translateY(2vh) scale(0.60); }
  7.6923% { transform: translateX(15vw) translateY(20vh) scale(0.89); }
  11.5385% { transform: translateX(7vw) translateY(-39vh) scale(0.31); }
  15.3846% { transform: translateX(6vw) translateY(45vh) scale(0.67); }
  19.2308% { transform: translateX(28vw) translateY(-17vh) scale(0.29); }
  23.0769% { transform: translateX(-38vw) translateY(-20vh) scale(0.99); }
  26.9231% { transform: translateX(26vw) translateY(-47vh) scale(0.60); }
  30.7692% { transform: translateX(24vw) translateY(-44vh) scale(0.48); }
  34.6154% { transform: translateX(11vw) translateY(17vh) scale(0.82); }
  38.4615% { transform: translateX(-14vw) translateY(-26vh) scale(1.00); }
  42.3077% { transform: translateX(6vw) translateY(32vh) scale(0.88); }
  46.1538% { transform: translateX(-38vw) translateY(11vh) scale(0.70); }
  50.0000% { transform: translateX(3vw) translateY(-7vh) scale(0.67); }
  53.8462% { transform: translateX(36vw) translateY(-36vh) scale(0.46); }
  57.6923% { transform: translateX(-7vw) translateY(3vh) scale(0.89); }
  61.5385% { transform: translateX(-13vw) translateY(35vh) scale(0.77); }
  65.3846% { transform: translateX(48vw) translateY(21vh) scale(0.30); }
  69.2308% { transform: translateX(9vw) translateY(-38vh) scale(0.66); }
  73.0769% { transform: translateX(-17vw) translateY(-8vh) scale(0.40); }
  76.9231% { transform: translateX(49vw) translateY(2vh) scale(0.91); }
  80.7692% { transform: translateX(-49vw) translateY(35vh) scale(0.95); }
  84.6154% { transform: translateX(10vw) translateY(3vh) scale(0.32); }
  88.4615% { transform: translateX(-25vw) translateY(17vh) scale(0.72); }
  92.3077% { transform: translateX(30vw) translateY(47vh) scale(0.89); }
  96.1538% { transform: translateX(31vw) translateY(7vh) scale(0.32); }
  100.0000% { transform: translateX(-23vw) translateY(-15vh) scale(0.96); }
}


/* ---- Firefly 15 ---- */
.firefly:nth-child(15){ animation-name: move15; }
.firefly:nth-child(15)::before{ animation-duration: 13s; }
.firefly:nth-child(15)::after{ animation-duration: 13s, 8590ms; animation-delay: 0ms, 7716ms; }
@keyframes move15{
  0.0000% { transform: translateX(40vw) translateY(13vh) scale(0.41); }
  5.2632% { transform: translateX(-46vw) translateY(31vh) scale(0.56); }
  10.5263% { transform: translateX(41vw) translateY(-29vh) scale(0.65); }
  15.7895% { transform: translateX(21vw) translateY(-48vh) scale(0.96); }
  21.0526% { transform: translateX(3vw) translateY(-38vh) scale(0.54); }
  26.3158% { transform: translateX(-35vw) translateY(10vh) scale(0.41); }
  31.5789% { transform: translateX(33vw) translateY(-30vh) scale(0.89); }
  36.8421% { transform: translateX(42vw) translateY(-12vh) scale(0.91); }
  42.1053% { transform: translateX(41vw) translateY(-15vh) scale(0.79); }
  47.3684% { transform: translateX(12vw) translateY(11vh) scale(0.57); }
  52.6316% { transform: translateX(9vw) translateY(21vh) scale(0.44); }
  57.8947% { transform: translateX(0vw) translateY(-25vh) scale(0.91); }
  63.1579% { transform: translateX(46vw) translateY(-32vh) scale(0.34); }
  68.4211% { transform: translateX(-14vw) translateY(49vh) scale(0.79); }
  73.6842% { transform: translateX(-6vw) translateY(15vh) scale(0.60); }
  78.9474% { transform: translateX(-49vw) translateY(-13vh) scale(0.64); }
  84.2105% { transform: translateX(26vw) translateY(25vh) scale(0.88); }
  89.4737% { transform: translateX(-30vw) translateY(8vh) scale(0.94); }
  94.7368% { transform: translateX(12vw) translateY(-5vh) scale(0.68); }
  100.0000% { transform: translateX(21vw) translateY(48vh) scale(0.95); }
}


/* ---- Firefly 16 ---- */
.firefly:nth-child(16){ animation-name: move16; }
.firefly:nth-child(16)::before{ animation-duration: 16s; }
.firefly:nth-child(16)::after{ animation-duration: 16s, 7637ms; animation-delay: 0ms, 7624ms; }
@keyframes move16{
  0.0000% { transform: translateX(-25vw) translateY(40vh) scale(0.56); }
  4.3478% { transform: translateX(24vw) translateY(0vh) scale(0.55); }
  8.6957% { transform: translateX(50vw) translateY(3vh) scale(0.31); }
  13.0435% { transform: translateX(-9vw) translateY(46vh) scale(0.86); }
  17.3913% { transform: translateX(41vw) translateY(-1vh) scale(0.75); }
  21.7391% { transform: translateX(35vw) translateY(34vh) scale(0.45); }
  26.0870% { transform: translateX(14vw) translateY(-45vh) scale(0.42); }
  30.4348% { transform: translateX(15vw) translateY(26vh) scale(0.68); }
  34.7826% { transform: translateX(-37vw) translateY(7vh) scale(0.38); }
  39.1304% { transform: translateX(18vw) translateY(9vh) scale(0.27); }
  43.4783% { transform: translateX(43vw) translateY(-31vh) scale(0.78); }
  47.8261% { transform: translateX(34vw) translateY(-30vh) scale(0.35); }
  52.1739% { transform: translateX(11vw) translateY(-16vh) scale(0.69); }
  56.5217% { transform: translateX(30vw) translateY(39vh) scale(0.76); }
  60.8696% { transform: translateX(34vw) translateY(-39vh) scale(0.68); }
  65.2174% { transform: translateX(37vw) translateY(19vh) scale(0.74); }
  69.5652% { transform: translateX(-9vw) translateY(31vh) scale(0.88); }
  73.9130% { transform: translateX(20vw) translateY(-45vh) scale(0.34); }
  78.2609% { transform: translateX(-19vw) translateY(31vh) scale(0.62); }
  82.6087% { transform: translateX(-20vw) translateY(46vh) scale(0.37); }
  86.9565% { transform: translateX(6vw) translateY(-37vh) scale(0.38); }
  91.3043% { transform: translateX(7vw) translateY(-28vh) scale(0.64); }
  95.6522% { transform: translateX(-46vw) translateY(-44vh) scale(0.67); }
  100.0000% { transform: translateX(-42vw) translateY(-12vh) scale(0.71); }
}


/* ---- Firefly 17 ---- */
.firefly:nth-child(17){ animation-name: move17; }
.firefly:nth-child(17)::before{ animation-duration: 15s; }
.firefly:nth-child(17)::after{ animation-duration: 15s, 6193ms; animation-delay: 0ms, 2501ms; }
@keyframes move17{
  0.0000% { transform: translateX(18vw) translateY(3vh) scale(0.98); }
  4.5455% { transform: translateX(38vw) translateY(-26vh) scale(0.47); }
  9.0909% { transform: translateX(-27vw) translateY(-39vh) scale(0.74); }
  13.6364% { transform: translateX(30vw) translateY(38vh) scale(0.56); }
  18.1818% { transform: translateX(14vw) translateY(25vh) scale(0.44); }
  22.7273% { transform: translateX(-20vw) translateY(10vh) scale(0.58); }
  27.2727% { transform: translateX(9vw) translateY(-17vh) scale(0.27); }
  31.8182% { transform: translateX(10vw) translateY(-13vh) scale(0.95); }
  36.3636% { transform: translateX(-29vw) translateY(-40vh) scale(0.82); }
  40.9091% { transform: translateX(-5vw) translateY(26vh) scale(0.64); }
  45.4545% { transform: translateX(32vw) translateY(5vh) scale(0.58); }
  50.0000% { transform: translateX(9vw) translateY(-11vh) scale(0.51); }
  54.5455% { transform: translateX(0vw) translateY(12vh) scale(0.39); }
  59.0909% { transform: translateX(-19vw) translateY(-1vh) scale(0.99); }
  63.6364% { transform: translateX(-4vw) translateY(24vh) scale(0.63); }
  68.1818% { transform: translateX(40vw) translateY(-12vh) scale(0.28); }
  72.7273% { transform: translateX(35vw) translateY(1vh) scale(0.61); }
  77.2727% { transform: translateX(-48vw) translateY(23vh) scale(0.32); }
  81.8182% { transform: translateX(28vw) translateY(46vh) scale(0.89); }
  86.3636% { transform: translateX(-13vw) translateY(50vh) scale(0.55); }
  90.9091% { transform: translateX(28vw) translateY(-4vh) scale(0.54); }
  95.4545% { transform: translateX(32vw) translateY(-25vh) scale(0.58); }
  100.0000% { transform: translateX(37vw) translateY(47vh) scale(0.43); }
}


/* ---- Firefly 18 ---- */
.firefly:nth-child(18){ animation-name: move18; }
.firefly:nth-child(18)::before{ animation-duration: 10s; }
.firefly:nth-child(18)::after{ animation-duration: 10s, 10141ms; animation-delay: 0ms, 5795ms; }
@keyframes move18{
  0.0000% { transform: translateX(-44vw) translateY(-10vh) scale(0.82); }
  3.7037% { transform: translateX(-45vw) translateY(25vh) scale(0.72); }
  7.4074% { transform: translateX(44vw) translateY(-33vh) scale(0.37); }
  11.1111% { transform: translateX(-12vw) translateY(-8vh) scale(0.79); }
  14.8148% { transform: translateX(-27vw) translateY(-24vh) scale(0.42); }
  18.5185% { transform: translateX(20vw) translateY(-3vh) scale(0.93); }
  22.2222% { transform: translateX(15vw) translateY(-15vh) scale(0.47); }
  25.9259% { transform: translateX(-17vw) translateY(12vh) scale(0.63); }
  29.6296% { transform: translateX(46vw) translateY(-6vh) scale(0.40); }
  33.3333% { transform: translateX(10vw) translateY(-40vh) scale(0.44); }
  37.0370% { transform: translateX(47vw) translateY(-21vh) scale(0.76); }
  40.7407% { transform: translateX(22vw) translateY(-3vh) scale(0.37); }
  44.4444% { transform: translateX(1vw) translateY(-48vh) scale(0.59); }
  48.1481% { transform: translateX(19vw) translateY(-34vh) scale(0.84); }
  51.8519% { transform: translateX(-2vw) translateY(37vh) scale(0.59); }
  55.5556% { transform: translateX(25vw) translateY(-1vh) scale(0.73); }
  59.2593% { transform: translateX(-36vw) translateY(37vh) scale(0.55); }
  62.9630% { transform: translateX(11vw) translateY(-46vh) scale(0.97); }
  66.6667% { transform: translateX(-8vw) translateY(29vh) scale(0.54); }
  70.3704% { transform: translateX(33vw) translateY(-41vh) scale(0.85); }
  74.0741% { transform: translateX(40vw) translateY(-11vh) scale(0.78); }
  77.7778% { transform: translateX(-35vw) translateY(-32vh) scale(0.31); }
  81.4815% { transform: translateX(-45vw) translateY(-11vh) scale(0.89); }
  85.1852% { transform: translateX(-35vw) translateY(-37vh) scale(0.56); }
  88.8889% { transform: translateX(19vw) translateY(-32vh) scale(0.75); }
  92.5926% { transform: translateX(9vw) translateY(-2vh) scale(0.95); }
  96.2963% { transform: translateX(4vw) translateY(26vh) scale(0.45); }
  100.0000% { transform: translateX(4vw) translateY(34vh) scale(0.38); }
}


/* ---- Firefly 19 ---- */
.firefly:nth-child(19){ animation-name: move19; }
.firefly:nth-child(19)::before{ animation-duration: 18s; }
.firefly:nth-child(19)::after{ animation-duration: 18s, 8344ms; animation-delay: 0ms, 8193ms; }
@keyframes move19{
  0.0000% { transform: translateX(-14vw) translateY(-45vh) scale(0.73); }
  4.1667% { transform: translateX(-22vw) translateY(7vh) scale(0.82); }
  8.3333% { transform: translateX(-19vw) translateY(-3vh) scale(0.38); }
  12.5000% { transform: translateX(38vw) translateY(-2vh) scale(0.95); }
  16.6667% { transform: translateX(33vw) translateY(-4vh) scale(0.33); }
  20.8333% { transform: translateX(1vw) translateY(-14vh) scale(0.50); }
  25.0000% { transform: translateX(-34vw) translateY(9vh) scale(0.37); }
  29.1667% { transform: translateX(35vw) translateY(-22vh) scale(0.28); }
  33.3333% { transform: translateX(-43vw) translateY(-7vh) scale(0.57); }
  37.5000% { transform: translateX(-33vw) translateY(23vh) scale(0.52); }
  41.6667% { transform: translateX(-41vw) translateY(48vh) scale(0.96); }
  45.8333% { transform: translateX(-23vw) translateY(26vh) scale(0.53); }
  50.0000% { transform: translateX(-20vw) translateY(-7vh) scale(0.44); }
  54.1667% { transform: translateX(27vw) translateY(-49vh) scale(0.61); }
  58.3333% { transform: translateX(-31vw) translateY(-33vh) scale(0.95); }
  62.5000% { transform: translateX(-17vw) translateY(-27vh) scale(0.40); }
  66.6667% { transform: translateX(35vw) translateY(-46vh) scale(0.42); }
  70.8333% { transform: translateX(-48vw) translateY(-4vh) scale(0.56); }
  75.0000% { transform: translateX(26vw) translateY(-8vh) scale(0.28); }
  79.1667% { transform: translateX(-27vw) translateY(-16vh) scale(0.32); }
  83.3333% { transform: translateX(-33vw) translateY(45vh) scale(0.79); }
  87.5000% { transform: translateX(18vw) translateY(-35vh) scale(0.34); }
  91.6667% { transform: translateX(11vw) translateY(8vh) scale(0.72); }
  95.8333% { transform: translateX(16vw) translateY(26vh) scale(0.39); }
  100.0000% { transform: translateX(8vw) translateY(15vh) scale(0.54); }
}


/* ---- Firefly 20 ---- */
.firefly:nth-child(20){ animation-name: move20; }
.firefly:nth-child(20)::before{ animation-duration: 9s; }
.firefly:nth-child(20)::after{ animation-duration: 9s, 10958ms; animation-delay: 0ms, 6917ms; }
@keyframes move20{
  0.0000% { transform: translateX(35vw) translateY(17vh) scale(0.64); }
  3.8462% { transform: translateX(9vw) translateY(33vh) scale(0.29); }
  7.6923% { transform: translateX(-42vw) translateY(12vh) scale(0.77); }
  11.5385% { transform: translateX(5vw) translateY(38vh) scale(0.39); }
  15.3846% { transform: translateX(13vw) translateY(42vh) scale(0.82); }
  19.2308% { transform: translateX(-40vw) translateY(-39vh) scale(0.67); }
  23.0769% { transform: translateX(28vw) translateY(-31vh) scale(0.34); }
  26.9231% { transform: translateX(-33vw) translateY(-14vh) scale(1.00); }
  30.7692% { transform: translateX(21vw) translateY(42vh) scale(0.67); }
  34.6154% { transform: translateX(-1vw) translateY(27vh) scale(0.93); }
  38.4615% { transform: translateX(-12vw) translateY(9vh) scale(0.90); }
  42.3077% { transform: translateX(28vw) translateY(6vh) scale(0.38); }
  46.1538% { transform: translateX(40vw) translateY(-35vh) scale(0.96); }
  50.0000% { transform: translateX(43vw) translateY(-22vh) scale(0.81); }
  53.8462% { transform: translateX(8vw) translateY(-20vh) scale(0.78); }
  57.6923% { transform: translateX(-6vw) translateY(9vh) scale(0.77); }
  61.5385% { transform: translateX(4vw) translateY(44vh) scale(0.38); }
  65.3846% { transform: translateX(-9vw) translateY(5vh) scale(0.66); }
  69.2308% { transform: translateX(36vw) translateY(-17vh) scale(0.73); }
  73.0769% { transform: translateX(-30vw) translateY(38vh) scale(0.86); }
  76.9231% { transform: translateX(-41vw) translateY(-38vh) scale(0.36); }
  80.7692% { transform: translateX(-38vw) translateY(6vh) scale(0.38); }
  84.6154% { transform: translateX(46vw) translateY(45vh) scale(0.73); }
  88.4615% { transform: translateX(-33vw) translateY(22vh) scale(0.33); }
  92.3077% { transform: translateX(26vw) translateY(22vh) scale(0.97); }
  96.1538% { transform: translateX(-7vw) translateY(36vh) scale(0.41); }
  100.0000% { transform: translateX(3vw) translateY(-4vh) scale(0.80); }
}


/* ---- Firefly 21 ---- */
.firefly:nth-child(21){ animation-name: move21; }
.firefly:nth-child(21)::before{ animation-duration: 9s; }
.firefly:nth-child(21)::after{ animation-duration: 9s, 7357ms; animation-delay: 0ms, 5419ms; }
@keyframes move21{
  0.0000% { transform: translateX(-10vw) translateY(-4vh) scale(0.39); }
  3.5714% { transform: translateX(24vw) translateY(15vh) scale(0.53); }
  7.1429% { transform: translateX(-30vw) translateY(35vh) scale(0.87); }
  10.7143% { transform: translateX(-21vw) translateY(-36vh) scale(0.70); }
  14.2857% { transform: translateX(22vw) translateY(-2vh) scale(0.40); }
  17.8571% { transform: translateX(48vw) translateY(-14vh) scale(0.99); }
  21.4286% { transform: translateX(-21vw) translateY(5vh) scale(0.97); }
  25.0000% { transform: translateX(49vw) translateY(30vh) scale(0.97); }
  28.5714% { transform: translateX(-46vw) translateY(28vh) scale(0.60); }
  32.1429% { transform: translateX(-46vw) translateY(-26vh) scale(0.60); }
  35.7143% { transform: translateX(40vw) translateY(48vh) scale(0.65); }
  39.2857% { transform: translateX(-6vw) translateY(-5vh) scale(0.26); }
  42.8571% { transform: translateX(-26vw) translateY(-31vh) scale(0.98); }
  46.4286% { transform: translateX(35vw) translateY(2vh) scale(0.34); }
  50.0000% { transform: translateX(-31vw) translateY(45vh) scale(0.29); }
  53.5714% { transform: translateX(-38vw) translateY(46vh) scale(0.93); }
  57.1429% { transform: translateX(-22vw) translateY(-1vh) scale(0.79); }
  60.7143% { transform: translateX(9vw) translateY(-6vh) scale(0.46); }
  64.2857% { transform: translateX(-2vw) translateY(-10vh) scale(0.67); }
  67.8571% { transform: translateX(50vw) translateY(23vh) scale(0.36); }
  71.4286% { transform: translateX(-43vw) translateY(-30vh) scale(0.46); }
  75.0000% { transform: translateX(47vw) translateY(30vh) scale(0.32); }
  78.5714% { transform: translateX(37vw) translateY(-39vh) scale(0.60); }
  82.1429% { transform: translateX(7vw) translateY(35vh) scale(0.80); }
  85.7143% { transform: translateX(13vw) translateY(28vh) scale(0.82); }
  89.2857% { transform: translateX(4vw) translateY(-15vh) scale(0.53); }
  92.8571% { transform: translateX(47vw) translateY(16vh) scale(0.40); }
  96.4286% { transform: translateX(-5vw) translateY(6vh) scale(0.40); }
  100.0000% { transform: translateX(-13vw) translateY(37vh) scale(0.88); }
}


/* ---- Firefly 22 ---- */
.firefly:nth-child(22){ animation-name: move22; }
.firefly:nth-child(22)::before{ animation-duration: 13s; }
.firefly:nth-child(22)::after{ animation-duration: 13s, 5373ms; animation-delay: 0ms, 2307ms; }
@keyframes move22{
  0.0000% { transform: translateX(1vw) translateY(27vh) scale(0.33); }
  4.0000% { transform: translateX(-49vw) translateY(-23vh) scale(0.64); }
  8.0000% { transform: translateX(-22vw) translateY(49vh) scale(0.43); }
  12.0000% { transform: translateX(48vw) translateY(-17vh) scale(0.63); }
  16.0000% { transform: translateX(-8vw) translateY(-34vh) scale(0.26); }
  20.0000% { transform: translateX(14vw) translateY(46vh) scale(0.81); }
  24.0000% { transform: translateX(-27vw) translateY(-33vh) scale(0.74); }
  28.0000% { transform: translateX(19vw) translateY(41vh) scale(0.55); }
  32.0000% { transform: translateX(15vw) translateY(22vh) scale(0.71); }
  36.0000% { transform: translateX(-40vw) translateY(1vh) scale(0.31); }
  40.0000% { transform: translateX(6vw) translateY(-47vh) scale(0.84); }
  44.0000% { transform: translateX(-40vw) translateY(-9vh) scale(0.99); }
  48.0000% { transform: translateX(5vw) translateY(24vh) scale(0.77); }
  52.0000% { transform: translateX(41vw) translateY(32vh) scale(0.79); }
  56.0000% { transform: translateX(-12vw) translateY(-35vh) scale(0.77); }
  60.0000% { transform: translateX(-47vw) translateY(-8vh) scale(0.47); }
  64.0000% { transform: translateX(30vw) translateY(9vh) scale(0.72); }
  68.0000% { transform: translateX(-38vw) translateY(6vh) scale(0.39); }
  72.0000% { transform: translateX(-18vw) translateY(6vh) scale(0.77); }
  76.0000% { transform: translateX(18vw) translateY(-39vh) scale(0.76); }
  80.0000% { transform: translateX(-10vw) translateY(46vh) scale(0.69); }
  84.0000% { transform: translateX(-21vw) translateY(-7vh) scale(0.47); }
  88.0000% { transform: translateX(-40vw) translateY(16vh) scale(0.40); }
  92.0000% { transform: translateX(18vw) translateY(16vh) scale(0.50); }
  96.0000% { transform: translateX(50vw) translateY(-5vh) scale(0.70); }
  100.0000% { transform: translateX(44vw) translateY(33vh) scale(0.44); }
}


/* ---- Firefly 23 ---- */
.firefly:nth-child(23){ animation-name: move23; }
.firefly:nth-child(23)::before{ animation-duration: 10s; }
.firefly:nth-child(23)::after{ animation-duration: 10s, 6200ms; animation-delay: 0ms, 2597ms; }
@keyframes move23{
  0.0000% { transform: translateX(-24vw) translateY(-27vh) scale(0.45); }
  5.0000% { transform: translateX(48vw) translateY(48vh) scale(0.35); }
  10.0000% { transform: translateX(-27vw) translateY(49vh) scale(0.89); }
  15.0000% { transform: translateX(10vw) translateY(47vh) scale(0.98); }
  20.0000% { transform: translateX(48vw) translateY(25vh) scale(0.83); }
  25.0000% { transform: translateX(38vw) translateY(23vh) scale(0.67); }
  30.0000% { transform: translateX(31vw) translateY(-9vh) scale(0.45); }
  35.0000% { transform: translateX(7vw) translateY(-41vh) scale(0.86); }
  40.0000% { transform: translateX(7vw) translateY(31vh) scale(0.64); }
  45.0000% { transform: translateX(-14vw) translateY(26vh) scale(0.33); }
  50.0000% { transform: translateX(-4vw) translateY(15vh) scale(0.35); }
  55.0000% { transform: translateX(-10vw) translateY(10vh) scale(0.83); }
  60.0000% { transform: translateX(-45vw) translateY(-42vh) scale(0.73); }
  65.0000% { transform: translateX(-13vw) translateY(-40vh) scale(0.37); }
  70.0000% { transform: translateX(29vw) translateY(27vh) scale(0.90); }
  75.0000% { transform: translateX(0vw) translateY(10vh) scale(1.00); }
  80.0000% { transform: translateX(21vw) translateY(45vh) scale(0.31); }
  85.0000% { transform: translateX(8vw) translateY(24vh) scale(0.50); }
  90.0000% { transform: translateX(-8vw) translateY(28vh) scale(0.86); }
  95.0000% { transform: translateX(15vw) translateY(-30vh) scale(0.33); }
  100.0000% { transform: translateX(8vw) translateY(-36vh) scale(0.69); }
}


/* ---- Firefly 24 ---- */
.firefly:nth-child(24){ animation-name: move24; }
.firefly:nth-child(24)::before{ animation-duration: 10s; }
.firefly:nth-child(24)::after{ animation-duration: 10s, 9134ms; animation-delay: 0ms, 5794ms; }
@keyframes move24{
  0.0000% { transform: translateX(-27vw) translateY(-44vh) scale(0.57); }
  3.5714% { transform: translateX(41vw) translateY(7vh) scale(0.82); }
  7.1429% { transform: translateX(18vw) translateY(17vh) scale(0.46); }
  10.7143% { transform: translateX(-3vw) translateY(-2vh) scale(0.62); }
  14.2857% { transform: translateX(0vw) translateY(3vh) scale(0.69); }
  17.8571% { transform: translateX(37vw) translateY(27vh) scale(0.32); }
  21.4286% { transform: translateX(31vw) translateY(33vh) scale(0.68); }
  25.0000% { transform: translateX(-41vw) translateY(-7vh) scale(0.38); }
  28.5714% { transform: translateX(22vw) translateY(37vh) scale(0.75); }
  32.1429% { transform: translateX(-13vw) translateY(-17vh) scale(0.45); }
  35.7143% { transform: translateX(-7vw) translateY(-39vh) scale(1.00); }
  39.2857% { transform: translateX(35vw) translateY(-31vh) scale(0.70); }
  42.8571% { transform: translateX(-10vw) translateY(34vh) scale(0.76); }
  46.4286% { transform: translateX(-33vw) translateY(27vh) scale(0.36); }
  50.0000% { transform: translateX(-10vw) translateY(22vh) scale(0.74); }
  53.5714% { transform: translateX(33vw) translateY(-7vh) scale(0.42); }
  57.1429% { transform: translateX(36vw) translateY(40vh) scale(0.93); }
  60.7143% { transform: translateX(-38vw) translateY(33vh) scale(0.80); }
  64.2857% { transform: translateX(16vw) translateY(-3vh) scale(0.28); }
  67.8571% { transform: translateX(-3vw) translateY(-10vh) scale(0.49); }
  71.4286% { transform: translateX(-22vw) translateY(-6vh) scale(0.88); }
  75.0000% { transform: translateX(-25vw) translateY(-21vh) scale(0.43); }
  78.5714% { transform: translateX(-30vw) translateY(-40vh) scale(0.63); }
  82.1429% { transform: translateX(-37vw) translateY(15vh) scale(0.95); }
  85.7143% { transform: translateX(45vw) translateY(18vh) scale(0.30); }
  89.2857% { transform: translateX(35vw) translateY(-6vh) scale(0.42); }
  92.8571% { transform: translateX(27vw) translateY(-1vh) scale(0.45); }
  96.4286% { transform: translateX(-29vw) translateY(-26vh) scale(0.47); }
  100.0000% { transform: translateX(43vw) translateY(7vh) scale(0.31); }
}
/* Ocultas por defecto */
.firefly { display: none; }

/* Solo visibles cuando el body tenga la clase del bosque */
body.forest-bg .firefly { display: block; }

/* (Opcional) si quieres ocultar partículas cuando sea bosque */
body.forest-bg #particles-js { display: none; }
    </style>
</head>

<body class="account-body accountbg">
<div id="particles-js"></div>
<div class="firefly"></div>
<div class="firefly"></div>
<div class="firefly"></div>
<div class="firefly"></div>
<div class="firefly"></div>
<div class="firefly"></div>
<div class="firefly"></div>
<div class="firefly"></div>
<div class="firefly"></div>
<div class="firefly"></div>
<script>
  var base_url = "<?php echo base_url(); ?>";

  const FOREST_URL = "https://i.pinimg.com/originals/44/6e/3b/446e3b79395a287ca32f7977dd83b290.jpg";

  const backgrounds = [
    base_url + "assets/images/backgrounds/gto5.webp",
    base_url + "assets/images/backgrounds/muertos-02.jpg",
    base_url + "assets/images/backgrounds/muertos-03.jpg",
    base_url + "assets/images/backgrounds/muertos-06.jpg",
    base_url + "assets/images/backgrounds/guanajuato.jpg",
    base_url + "assets/images/backgrounds/subterranea.webp",
    base_url + "assets/images/backgrounds/muertos-08.jpg",
    base_url + "assets/images/backgrounds/IMG8.jpg",
    base_url + "assets/images/backgrounds/muertos_01.jpeg",
    FOREST_URL,
  ];

  function setRandomBackground() {
    const chosen = backgrounds[Math.floor(Math.random() * backgrounds.length)];

    // pinta fondo
    document.body.style.backgroundImage = `url('${chosen}')`;
    document.body.style.backgroundSize = (window.innerWidth < 768) ? "auto 100%" : "cover";
    document.body.style.backgroundPosition = "center";
    document.body.style.backgroundRepeat = "no-repeat";

    // marca si es bosque (esto activa las luciérnagas vía CSS)
    const isForest = (chosen === FOREST_URL);
    document.body.classList.toggle('forest-bg', isForest);
  }

  window.addEventListener('load', setRandomBackground);
</script>