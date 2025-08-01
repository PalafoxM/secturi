<style>
     #qr {
        position: absolute;
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
<div  style="position:absolute; text-align:center; top:11%; left:5%; width:35%; height:18px;  font-size: 11px; ">
    <span class="proxima">Folio: <strong style="color:red;" ><?= $folio ?></strong></span>
</div>
<div style="position:absolute;top:21%; text-align:center; left:17.5%; width:65%; background-color:white; height:20px; font-size: 12px;">
    <span class="proxima"><strong><?= $usuario->nombre_completo?></strong></span>
</div>
<div style="position:absolute;top:23%;text-align:center; left:17.5%; width:65%; background-color:white; height:20px; font-size: 12px;">
    <span class="proxima"><strong><?= $usuario->dsc_area?></strong></span>
</div>
<?php 
$top = 24;
$contador = 0;

foreach ($incidencia as $i): 
?>
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
$top += 1.5;
$contador++;

// 👉 Cada 6 registros, fuerza salto de página y reinicia el top
if ($contador % 6 == 0) {
    echo '<div style="page-break-after: always;"></div>';
    $top = 21; // Reinicia altura
}
endforeach; 
?>
<div id="qr" style="top:<?= $top ?>%; left:40%;"></div>







