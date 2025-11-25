 <div  style="position:absolute; text-align:center; top:14%; left:75%; width:10%; height:10px; background-color:white; font-size: 8px; ">
          <?= date('d/m/Y');?> 
    </div>
        <div  style="position:absolute; text-align:center; top:16.2%; left:70%; width:22%; height:10px; background-color:white; font-size: 8px; ">
          <?= 'PT SECTURI/DGA/CRMSG/'.$folio.'/'.date('Y'); ?> 
        </div>
        
        <div  style="position:absolute; text-align:center; top:18.4%; left:70.5%; width:22%; height:10px; background-color:white; font-size: 8px; ">
            3140
        </div>
        <div  style="position:absolute; text-align:center; top:16.2%; left:30%; width:22%; height:10px; background-color:white; font-size: 8px; ">
         <?=  $servicio->dsc_servicio; ?>
        </div>
        <div style="position:absolute; text-align:center; top:21.2%; left:30%; width:22%; height:10px; background-color:white; font-size: 8px; ">
          <?=  $servicio->dsc_servicio .' '. $periodo .' / '. date('Y')?> 
        </div>
        <div style="position:absolute; top:27.6%; left:22%; width:22%; height:10px; background-color:white; font-size: 8px; ">
           RODRIGO GONZALEZ GUERRERO
        </div>
        <div style="position:absolute; top:29.8%; left:22%; width:22%; height:10px; background-color:white; font-size: 8px; ">
               DIRECTOR GENERAL ADMINISTRATIVO
        </div>
        <div  style="position:absolute; text-align:justify; top:31.6%; left:7%; width:85.5%; height:30px; background-color:white; font-size: 10px; ">
       Solicito que se realice trámite de pago del comprobante fiscal con folio <strong><?= $folio_fac ?></strong>, derivado del contrato ó convenio número <strong>N/A </strong> por la cantidad
        de <strong>$<?= number_format($monto, 2) .' ('.$numero_texto.')' ?></strong>, por el servicio  <strong><?=  $servicio->dsc_servicio .' '. $periodo .' / '. date('Y')?></strong> 
      prestando por el proveedor <strong><?=  $servicio->dsc_servicio; ?></strong>. Se cuenta con suficiencia presupuestal en la partida correspondiente.
        </div>
      <?php $top = 43.3; ?>
      <?php $top1 = 47; ?>
      <?php $top2 = 49.5; ?>
      <?php $top3 = 52.2; ?>
      <?php for($i = 1; $i <= 3; $i++): ?>
      <?php  if($i != 3): ?>
      <div style="position:absolute; top:<?=$top?>%; left:50%; width:2%; height:12px; background-color:white; font-size: 10px;">
       SI
      </div>
      <?php endif; ?>
      <div style="position:absolute; top:<?=$top1?>%; left:21.5%; width:30%; height:12px; background-color:white; font-size: 10px;">
         ELIZABETH CRISTINA MONDRAGON MARTINEZ
      </div>

      <div style="position:absolute; top:<?=$top2?>%; left:21.5%; width:30%; height:12px; background-color:white; font-size: 10px;">
         COORDINADORA DE RECURSOS MATERIALES Y SERVICIOS GENERALES
      </div>

      <div style="position:absolute; top:<?=$top3?>%; left:21.5%; width:30%; height:12px; background-color:white; font-size: 10px;">
         DIRECCION GENERAL ADMINISTRATIVA
      </div>

      <?php $top += 12.4; ?>
      <?php $top1 += 13; ?>
      <?php $top2 += 13; ?>
      <?php $top3 += 13; ?>
      <?php endfor; ?>

      <div style="position:absolute; top:94.65%; left:21.5%; width:30%; height:10px; background-color:white; font-size: 10px;">
         ELIZABETH CRISTINA MONDRAGON MARTINEZ
      </div>

      <div style="position:absolute; top:97%; left:21.5%; width:30%; height:8px; background-color:white; font-size: 10px;">
         COORDINADORA DE RECURSOS MATERIALES Y SERVICIOS GENERALES
      </div>



        