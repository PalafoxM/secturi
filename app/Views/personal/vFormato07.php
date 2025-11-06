
        <div  style="position:absolute; top:19.3%; left:19%; width:10%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima">21</span>
        </div>
        <div  style="position:absolute; top:19.3%; left:47.5%; width:10%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($registro->fecha_tramite)); ?></span>
        </div>

        <div  style="position:absolute; top:19.3%; left:72%; width:25%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima">PT <?= strtoupper($registro->folio);?></span>
        </div>
        <div  style="position:absolute; top:36.8%; left:67%; width:25%; background-color:white; font-size: 9px;  height:12px;">
            <span ><?= strtoupper($registro->dsc_proveedor); ?></span>
        </div>
         <div  style="position:absolute; top:38%; left:73.5%; width:20%; background-color:white; font-size: 9px;  height:12px;">
            <span ><?= strtoupper($registro->no_proveedor); ?></span>
        </div>
        <div  style="position:absolute; top:39.1%; left:64.2%; width:20%; background-color:white; font-size: 9px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->rfc); ?></span>
        </div>
        <div  style="position:absolute; top:43.5%; left:77.1%; width:20%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->dsc_proveedor); ?></span>
        </div>
        <div  style="position:absolute; top:44.6%; left:66%; width:20%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->banco); ?></span>
        </div>
         <div  style="position:absolute; top:45.7%; left:65.9%; width:20%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->no_cuenta); ?></span>
        </div>
        <?php $i = 34.4;  ?>
        <?php foreach($presupuesto as $r): ?>
         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:18.8%; width:13%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $r->proyecto ?></span>
        </div>
       
        <?php $i = $i + 1.5; ?>
        <?php endforeach; ?>
        <?php $i = 34.4;  ?>
        <?php foreach($presupuesto as $r): ?>
         <div  style="position:absolute; text-align:center; top:<?=$i?>%; left:32.8%; width:13%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= $r->partida ?></span>
        </div>
        <?php $i = $i + 1.5; ?>
        <?php endforeach; ?>
         <?php $i = 34.4;  ?>
        <?php foreach($reserva as $r): ?>
            <div style="position:absolute; text-align:center; top:<?=$i?>%; left:46.8%; width:13%; background-color:white; font-size:10px; height:12px; line-height:12px;">
                <span class="proxima"><?= '$' . number_format($r->importe, 2) ?></span>
            </div>
            <?php $i += 1.5; ?>
        <?php endforeach; ?>
        <div  style="position:absolute; text-align:center; top:52.2%; left:49.3%; width:10%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima ">$<?= ($reserva[0]->total_importe); ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:51.8%; left:61%; width:35.8%; background-color:white; font-size: 12px;  height:20px;">
            <span class="proxima "><strong><?= ($numero_texto); ?></strong></span>
        </div>
         <div  style="position:absolute; top:48.5%; left:24%; width:25%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($reserva[0]->no_convenio); ?></span>
        </div>
         <div  style="position:absolute; top:49.6%; left:14%; width:25%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($reserva[0]->no_reserva); ?></span>
        </div>
         <div  style="position:absolute;  text-align:center; top:68.75%; left:5%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->director); ?></span>
        </div>
         <div  style="position:absolute;  text-align:center; top:68.75%; left:36%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "><?= strtoupper($registro->secretario); ?></span>
        </div>
         <div  style="position:absolute;  text-align:center; top:68.75%; left:67.1%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima ">SUBSECRETARIA</span>
        </div>
         <div  style="position:absolute;  text-align:center; top:79.6%; left:67.1%; width:30%; background-color:white; font-size: 12px;  height:12px;">
            <span class="proxima "> CLAUDIA </span>
        </div>

        
        
       
   
    
