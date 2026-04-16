<?php
    $logoPath = FCPATH . 'assets/logo3.png';
    $logoBase64 = '';
    if (file_exists($logoPath)) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }
?>
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

<!-- Logo centrado en la parte superior -->
<div style="text-align: center; margin-bottom: 8px;">
    <?php if ($logoBase64): ?>
        <img src="<?= $logoBase64 ?>" alt="Logo" style="width: 220px; height: auto;">
    <?php endif; ?>
</div>

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
<div style="position:absolute;top:<?= $top ?>%; left:35.5%; <?php ($i->tipo == 2)?'width:43%':'width:23%' ?> height:20px; font-size: 12px;">
    <?php if($i->tipo == 1): ?>
    <i><?=date('d/m/Y', strtotime($i->fecha)).' ' .date('H:i', strtotime($i->hora_inicio)).' - ' .date('H:i', strtotime($i->hora_fin))?> </i>
    <?php endif; ?>
    <?php if($i->tipo == 2): ?>
    <i><?=date('d/m/Y', strtotime($i->fecha_inicio)).' al ' .date('d/m/Y', strtotime($i->fecha_fin)).' de '.date('H:i', strtotime($i->hora_inicio)).' - ' .date('H:i', strtotime($i->hora_fin))?> </i>
    <?php endif; ?>
</div>
<?php $top += 1.8; ?>
<div style="position:absolute;top:<?= $top ?>%; left:11.5%; width:23%; height:20px; font-size: 12px;">
    <span class="proxima"><strong>Persona que autoriza:</strong></span>
</div>
<div style="position:absolute;top:<?= $top ?>%; left:35.5%; width:50%; height:20px; font-size: 12px;">
    <i><?= $i->nombre_completo_autoriza ?> </i>
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







