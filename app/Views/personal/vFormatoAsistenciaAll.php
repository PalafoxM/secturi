<?php
// ----- ESTILOS CSS AMIGABLES CON MPDF -----
//
// 1. SIN position: absolute
// 2. SIN porcentajes para altura (top, height)
// 3. Usamos 'padding' y 'margin' para espaciar
// 4. Usamos tablas para alinear (es lo más seguro en PDF)
//
?>
<style>
    body {
        font-family: sans-serif;
        font-size: 11px;
    }

    /* Encabezado de Folio y Fecha */
    .tabla-encabezado {
        width: 100%;
        margin-bottom: 20px; /* Espacio después del encabezado */
    }
    .tabla-encabezado td {
        width: 50%;
        font-size: 11px;
    }
    .tabla-encabezado .folio {
        text-align: left;
    }
    .tabla-encabezado .fecha {
        text-align: right;
    }

    /* Contenedor principal para cada usuario */
    .usuario-bloque {
        /* page-break-inside: avoid; */ /* Intenta no romper este bloque por la mitad (a veces funciona) */
        margin-top: 195px;
    }

    /* Div que FORZARÁ un salto de página después de cada usuario */
    .salto-pagina {
        page-break-after: always;
    }

    /* Encabezado con nombre y área del usuario */
    .usuario-header {
        background-color: #f0f0f0; /* Un fondo ligero para separar */
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }
    .usuario-header h3 {
        margin: 0;
        font-size: 14px;
    }
    .usuario-header p {
        margin: 0;
        font-size: 12px;
    }

    /* Contenedor para la lista de incidencias */
    .incidencias-lista {
        border: 1px solid #ddd;
        border-top: none; /* El borde superior ya lo pone el header */
        padding: 10px;
    }

    /* Cada incidencia individual */
    .incidencia-item {
        padding: 10px 0;
        border-bottom: 1px dotted #ccc;
        border-bottom: 1px solid #000; /* <-- LÍNEA SÓLIDA NEGRA */
    }
    /* El último item no necesita borde inferior */
    .incidencia-item:last-child {
        border-bottom: none;
    }

    /* Tabla para alinear "Etiqueta" y "Dato"
       Esto reemplaza tus divs con left: 11.5% y left: 35.5% */
    .incidencia-tabla {
        width: 100%;
        font-size: 12px;
    }
    .incidencia-tabla td {
        padding: 4px;
        vertical-align: top;
    }
    .incidencia-tabla td.etiqueta {
        width: 25%; /* Ancho de la etiqueta */
        font-weight: bold;
    }
    .incidencia-tabla td.dato {
        width: 75%; /* Ancho del dato */
        font-style: italic;
    }

    /* Contenedor para el QR al final */
    #qr-container {
        text-align: center;
        margin-top: 30px;
    }
    
    /* NOTA: Usar <img> es MUCHO MÁS SEGURO que un div con background-image.
      Asumo que $dataImagen es un Data URI (base64)
    */
    
</style>

<?php // ----- INICIO DEL HTML ----- ?>

<!-- 
  Encabezado del Documento (Folio y Fecha)
  Visible solo en la primera página porque está fuera del loop.
  Si lo necesitas en CADA página, debes usar las funciones Header() de mPDF.
-->
<table class="tabla-encabezado">
    <tr>
        <td class="folio">
            <span class="proxima">Folio: <strong style="color:red;"><?= $folio ?></strong></span>
        </td>
        <td class="fecha">
            <span class="proxima">Fecha: <?= date('d/m/Y'); ?></span>
        </td>
    </tr>
</table>


<?php 
$totalUsuarios = count($usuariosAgrupados);
$contadorUsuarios = 0;

foreach($usuariosAgrupados as $usuario): 
    $contadorUsuarios++;
?>
    
    <!-- Contenedor principal para este usuario -->
    <div class="usuario-bloque">
    
        <!-- Encabezado del usuario -->
        <div class="usuario-header">
            <h3><span class="proxima"><?= $usuario['nombre_completo'] ?></span></h3>
            <p><span class="proxima"><?= $usuario['dsc_area'] ?></span></p>
        </div>

        <!-- Lista de incidencias -->
        <div class="incidencias-lista">
            <?php 
            // Iterar sobre las incidencias
            foreach ($usuario['incidencias'] as $incidencia): 
            ?>
                <!-- Un item de incidencia -->
                <div class="incidencia-item">
                    <table class="incidencia-tabla">
                        <tr>
                            <td class="etiqueta"><span class="proxima">Tipo de Incidencia:</span></td>
                            <td class="dato"><i><?= $incidencia['dsc_incidencia'] ?></i></td>
                        </tr>
                        <tr>
                            <td class="etiqueta"><span class="proxima">Incidencia:</span></td>
                            <td class="dato"><i><?= $incidencia['detalles'] ?></i></td>
                        </tr>
                        <tr>
                            <td class="etiqueta"><span class="proxima">Fecha:</span></td>
                            <td class="dato">
                                <i>
                                <?php if($incidencia['tipo'] == 1): ?>
                                    <?= date('d/m/Y', strtotime($incidencia['fecha_inicio'])) . ' ' . date('H:i', strtotime($incidencia['hora_inicio'])) . ' - ' . date('H:i', strtotime($incidencia['hora_fin'])) ?>
                                <?php endif; ?>
                                <?php if($incidencia['tipo'] == 2): ?>
                                    <?= date('d/m/Y', strtotime($incidencia['fecha_inicio'])) . ' al ' . date('d/m/Y', strtotime($incidencia['fecha_fin'])) . ' de ' . date('H:i', strtotime($incidencia['hora_inicio'])) . ' - ' . date('H:i', strtotime($incidencia['hora_fin'])) ?>
                                <?php endif; ?>
                                </i>
                            </td>
                        </tr>
                    </table>
                    
                </div>
            <?php endforeach; // Fin del bucle de incidencias ?>
        </div> <!-- Fin .incidencias-lista -->

    </div> <!-- Fin .usuario-bloque -->
    
    <!-- QR: Se coloca al final del contenido del ÚLTIMO usuario -->
    <?php if($contadorUsuarios == $totalUsuarios): ?>
        <div id="qr-container">
            <!-- 
              ES MEJOR USAR <img>. Si $dataImagen es un base64 data URI, 
              esto funcionará perfecto y es más seguro que un background-image.
            -->
            <img src="<?= $dataImagen ?>" style="width:150px; height:150px;" alt="QR Code" />
        </div>
    <?php endif; ?>

    <!-- Salto de página (si NO es el último usuario) -->
    <?php if($contadorUsuarios < $totalUsuarios): ?>
        <div class="salto-pagina"></div>
    <?php endif; ?>

<?php endforeach; // Fin del bucle de usuarios ?>