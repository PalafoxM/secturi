<style>
     #qr {
      
        width: 150px;
        height: 150px;
        text-align: center;
        background-image: url('<?= $dataImagen ?>');
        background-size: 100% 100%;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
</style>
<div  style="position:absolute; text-align:center; top:11%; left:70%; width:35%; height:18px;  font-size: 11px; ">
    <span class="proxima">Fecha: <?= date('d/m/Y'); ?></span>
</div>
<?php 
$top = 21;
$contador = 0;

foreach ($incidencia as $i): 
?>

<!-- Campos de cada registro -->
<div style="position:absolute;top:<?= $top ?>%; left:11.5%; width:23%; height:20px; font-size: 12px;">
    <span class="proxima"><strong>Nombre del Servidor Publico:</strong></span>
</div>
<div style="position:absolute;top:<?= $top ?>%; left:35.5%; width:23%; height:20px; font-size: 12px;">
    <i><?= $i->nombre_completo ?> </i>
</div>

<?php $top += 1.5; ?>
<div style="position:absolute;top:<?= $top ?>%; left:11.5%; width:23%; height:20px; font-size: 12px;">
    <span class="proxima"><strong>Dirección a la que pertenece:</strong></span>
</div>
<div style="position:absolute;top:<?= $top ?>%; left:35.5%; width:50%; height:20px; font-size: 12px;">
    <i><?= $i->dsc_area ?> </i>
</div>

<?php $top += 1.7; ?>
<div style="position:absolute;top:<?= $top ?>%; left:11.5%; width:23%; height:20px; font-size: 12px;">
    <span class="proxima"><strong>Tipo de Incidencia:</strong></span>
</div>
<div style="position:absolute;top:<?= $top ?>%; left:35.5%; width:50%; height:20px; font-size: 12px;">
    <i><?= $i->dsc_incidencia ?> </i>
</div>

<?php $top += 1.7; ?>
<div style="position:absolute;top:<?= $top ?>%; left:11.5%; width:23%; height:20px; font-size: 12px;">
    <span class="proxima"><strong>Incidencia:</strong></span>
</div>
<div style="position:absolute;top:<?= $top ?>%; left:35.5%; width:50%; height:20px; font-size: 12px;">
    <i><?= $i->detalles ?> </i>
</div>

<?php $top += 1.6; ?>
<div style="position:absolute;top:<?= $top ?>%; left:11.5%; width:23%; height:20px; font-size: 12px;">
    <span class="proxima"><strong>Fecha:</strong></span>
</div>
<div style="position:absolute;top:<?= $top ?>%; left:35.5%; width:23%; height:20px; font-size: 12px;">
    <i><?=  date('d/m/Y', strtotime($i->fecha)).' ' .date('H:i', strtotime($i->hora_inicio)).' - ' .date('H:i', strtotime($i->hora_fin))?> </i>
</div>

<?php $top += 1.8; ?>
<div style="position:absolute; top:<?= $top ?>%; left:11.5%; width:80%; height:1px; border-top:1px solid #000;"></div>

<?php 
$top += 4;
$contador++;

// 👉 Cada 6 registros, fuerza salto de página y reinicia el top
if ($contador % 6 == 0) {
    echo '<div style="page-break-after: always;"></div>';
    $top = 21; // Reinicia altura
}
endforeach; 
?>
    <div id="qr">
        <img src="<?= $dataImagen ?>" alt="qr" >
    </div>






