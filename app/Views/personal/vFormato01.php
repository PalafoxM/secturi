 <div  style="position:absolute; top:18%; left:80%; width:10%; height:18px; background-color:white; font-size: 12px; ">
            <span class="proxima"><?= date('d/m/Y', strtotime($registro->fecha_tramite)); ?></span>
        </div>

        <div  style="position:absolute; text-align:center; top:21.5%; left:72%; width:25%; height:18px; background-color:white; font-size: 11px; ">
            <span class="proxima"><?= (isset($GO) && !empty($GO))?'GO':'PT' ?> <?= strtoupper($folio);?></span>
        </div>
        
        <div  style="position:absolute; text-align:center; top:47.6%; left:84.5%; width:7%; height:18px; background-color:white; font-size: 10px; ">
            <span class="proxima"><?= ($registro->evidencia_entrega == 1)?'SI':'NO'; ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:33.5%; left:84.5%; width:7%; height:18px; background-color:white; font-size: 10px; ">
            <span class="proxima"><?= ($registro->documentacion_comprobatoria == 1)?'SI':'NO'; ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:42.3%; left:84.5%; width:7%; height:18px; background-color:white; font-size: 10px; ">
            <span class="proxima"><?= (isset($registro->contrato_convenio) && !empty($registro->contrato_convenio) && $registro->contrato_convenio == 1)?'SI':'NO'; ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:29.5%; left:84.5%; width:7%; height:18px; background-color:white; font-size: 10px;">
            <span class="proxima"><?= ($registro->formato_establecido == 1)?'SI':'NO'; ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:37%; left:84.5%; width:7%; height:18px; background-color:white; font-size: 10px; ">
            <span class="proxima"><?= ($registro->poliza == 1)?'SI':'NO'; ?></span>
        </div>
        <div  style="position:absolute; text-align:center; top:50.5%; left:25%; width:70%; height:18px; background-color:white; font-size: 10px; ">
            <span class="proxima"><?= strtoupper($registro->otros); ?></span>
        </div>


        