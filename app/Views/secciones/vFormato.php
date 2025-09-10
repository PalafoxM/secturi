<style>
#container {
    position: relative;
    width: 80%; /* Ajusta según necesites */
    margin: 0 auto; /* Esto centrará el contenedor */
    height: 100%;
}
    .campo {
        position: absolute;
        font-weight: bold;
    }

    /* Positioning for each field */
    #nombre_completo {
        top: 52em;
        left: 32em;
    }

    #fecha_nacimiento {
        top: 49em;
        left: 40em;
    }

    #curp {
        top: 54.8em;
        left: 35em;
    }

    #numero_expediente {
        top: 63.5em;
        left: 38.5em;
        color: red;
    }

    #fecha_ingreso {
        top: 58.5em;
        left: 47em;
    }

    #tel_movil {
        top: 56.7em;
        left: 38em;
    }

    #edad {
        top: 49em;
        left: 50.5em;
        width: 35px;
    }

    #puesto {
        top: 37.9em;
        left: 45.5em;
        width: 350px;
        height: 25px;
        background: white;
        text-align: right;
    }

    #no_empleado {
          top: 52em;
        left: 32em;
        width: 95px;
        font-size: 25px;
        background: red;
    }

    #qr {
        top: 40em;
        left: 250px;;
        width: 250px;
        height: 250px;
        text-align: center;
        background-image: url('<?= $dataImagen ?>');
        background-size: 100% 100%;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
</style>

<div id="container">
    <!-- Personal Information -->
    <div id="nombre_completo" class="campo"><?= $usuario->nombre_completo ?></div>
    <div id="qr" class="campo"></div>

</div>