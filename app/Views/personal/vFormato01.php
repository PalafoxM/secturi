        <div  style="position:absolute; text-align:center; top:22.2%; left:78%; width:10%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($registro->fecha_tramite)); ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:22.2%; left:35%; width:20%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima">SECRETARIA DE TURISMO E IDENTIDAD </span>
        </div>

        <div  style="position:absolute; text-align:center; top:26.5%; left:73%; width:19.2%; height:18px; background-color:white; font-size: 9px; ">
            <span class="proxima"><?= (isset($GO) && !empty($GO))?'GO':'PT' ?> <?= strtoupper($folio);?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:26.5%; left:35%; width:19.2%; height:18px; background-color:white; font-size: 9px; ">
            <span class="proxima">TRÁMITES DE PAGO ESTATALES</span>
        </div>
        
        <div  style="position:absolute; text-align:center; top:58.2%; left:81%; width:7%; height:18px; background-color:white; font-size: 10px; ">
            <span class="proxima"><?= ($registro->evidencia_entrega == 1)?'SI':'NO'; ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:40.5%; left:81%; width:7%; height:18px; background-color:white; font-size: 10px; ">
            <span class="proxima"><?= ($registro->documentacion_comprobatoria == 1)?'SI':'NO'; ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:51.8%; left:81%; width:7%; height:18px; background-color:white; font-size: 10px; ">
            <?php if(!$fic): ?>
            <span class="proxima"><?= (isset($registro->contrato_convenio) && !empty($registro->contrato_convenio) && $registro->contrato_convenio == 1)?'SI':'NO'; ?></span>
            <?php endif; ?>

            <?php if($fic): ?>
            <span class="proxima">N/A</span>
            <?php endif; ?>
        </div>
        <div  style="position:absolute; text-align:center; top:34.5%; left:81%; width:7%; height:18px; background-color:white; font-size: 10px;">
            <span class="proxima"><?= ($registro->formato_establecido == 1)?'SI':'NO'; ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:45.3%; left:81%; width:7%; height:18px; background-color:white; font-size: 10px; ">
             <?php if(!$fic): ?>
            <span class="proxima"><?= ($registro->poliza == 1)?'SI':'NO'; ?></span>
            <?php endif; ?>
        </div>
         <div  style="position:absolute; text-align:center; top:48.6%; left:81%; width:7%; height:18px; background-color:white; font-size: 10px; ">
            <span class="proxima">SI</span>
        </div>
         <div  style="position:absolute; text-align:center; top:55.2%; left:81%; width:7%; height:18px; background-color:white; font-size: 10px; ">
            <span class="proxima">SI</span>
        </div>
        <div  style="position:absolute; text-align:center; top:62.5%; left:25%; width:65%; height:18px; background-color:white; font-size: 10px; ">
            <span class="proxima"><?= strtoupper($registro->otros); ?></span>
        </div>


        