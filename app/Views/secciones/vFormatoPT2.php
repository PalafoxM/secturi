        <?php
        setlocale(LC_TIME, 'es_ES.UTF-8'); // Para Linux/macOS
        // setlocale(LC_TIME, 'spanish'); // Para Windows

        $fecha = strtotime($registro->fecha_tramite);
        $fechaFormateada = strftime('%e de %B del %Y', $fecha);
        ?>
        <div  style="position:absolute; text-align:right;  top:21.2%; left:45.5%; width:45%; height:18px; background-color:white; font-size: 16px; ">
            <span class="proxima"><strong>Silao de la Victoria, Gto, <?= ucfirst($fechaFormateada); ?></strong></span>
        </div>
        <div  style="position:absolute; top:24.5%; left:64.5%; width:28%; height:18px; background-color:white; font-size: 14px; ">
            <span class="proxima">PT <?= strtoupper($registro->folio);?></span>
        </div>
        <div style="position:absolute; top:33.2%; left:9.5%; width:81%; height:72px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
                Por medio de la presente, me permito solicitar su apoyo para que se realice el tramite de Pago a Tercero
                con folio <strong>PT <?= strtoupper($registro->folio);?></strong> por la cantidad de 
                <strong>$<?= ($reserva[0]->total_importe); ?> (<?= mb_strtoupper($numero_texto, 'UTF-8'); ?>)</strong>,
                de comprobante(s) fiscale(s) No. <strong>5191</strong> por concepto de <?= $registro->concepto_pago ?> 
                al proveedor <?= $registro->dsc_proveedor ?>.
            </span>
        </div>
        <div style="position:absolute; top:42.5%; left:9.5%; width:81%; height:42px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
                Lo anterior con cargo al proyecto(s) <strong><?= strtoupper($reserva[0]->proyecto);?></strong> a las partida(s) presepuestal(es) 
                <strong>"<?= ($reserva[0]->partida);?> <?= ($reserva[0]->dsc_partida);?>".</strong>
            </span>
        </div>



        
        
       
   
    
