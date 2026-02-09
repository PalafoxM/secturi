<?php
// Obtener la fecha
$fecha = strtotime($registro->fecha_tramite);
$dia = date('j', $fecha);      // día sin ceros iniciales (1-31)
$anio = date('Y', $fecha);     // año

// Obtener el número del mes (1-12)
$mes_numero = (int) date('n', $fecha);

// Traducir mes a español con switch
switch ($mes_numero) {
    case 1:  $mes = 'enero'; break;
    case 2:  $mes = 'febrero'; break;
    case 3:  $mes = 'marzo'; break;
    case 4:  $mes = 'abril'; break;
    case 5:  $mes = 'mayo'; break;
    case 6:  $mes = 'junio'; break;
    case 7:  $mes = 'julio'; break;
    case 8:  $mes = 'agosto'; break;
    case 9:  $mes = 'septiembre'; break;
    case 10: $mes = 'octubre'; break;
    case 11: $mes = 'noviembre'; break;
    case 12: $mes = 'diciembre'; break;
    default: $mes = 'mes desconocido';
}

$fechaFormateada = $dia . ' de ' . $mes . ' del ' . $anio;
?>
<div style="position:absolute; text-align:right; top:21.2%; left:45.5%; width:45%; height:18px; background-color:white; font-size: 16px;">
    <span class="proxima"><strong>Silao de la Victoria, Gto, <?= ucfirst($fechaFormateada); ?></strong></span>
</div>
        <div  style="position:absolute; top:24.5%; left:64.5%; width:28%; height:18px; background-color:white; font-size: 14px; ">
            <span class="proxima"><?='GO';?> <?= strtoupper($prefijoCompleto);?></span>
        </div>
        <div style="position:absolute; top:33.2%; left:9.5%; width:81%; height:72px; background-color:white; font-size:13px; text-align:justify;">
            <span class="proxima">
                Por medio de la presente, me permito solicitar su apoyo para que se realice el tramite de Gasto de Operación
                con folio <strong><?= 'GO';?> <?= strtoupper($prefijoCompleto);?></strong> por la cantidad de 
                <strong>$<?=number_format($total_importe, 2); ?> (<?= mb_strtoupper($numero_texto, 'UTF-8'); ?>)</strong>,
                por concepto de <?= (isset($registro->concepto_pago) && !empty($registro->concepto_pago))?$registro->concepto_pago:$concepto ?>.
            </span>
        </div>
       
         <div style="position:absolute; top:40%; left:9.5%; width:81%; height:80px; background-color:WHITE; font-size:13px; text-align:justify;">
            <span class="proxima">
               Manifiesto que el gasto se realizó para el cumplimiento de la comisión asignada, quedando bajo mi responsabilidad
               la veracidad de la misma en atención a lo establecido en las Disposiciones Administrativas Vigentes, 
               así mismo queda bajo mi resguardo y custodia los expedientes originales con los entregables correspondientes en 
               caso de cualquier proceso o revisión de auditoría.   
            </span>
        </div>
    
        <div style="position:absolute; top:47.5%; left:9.5%; width:81%; height:80px; background-color:WHITE; font-size:13px; text-align:justify;">
            <span class="proxima">
              Sin otro particular por el momento, aprovecho la ocasión para enviarle un coridal saludo.
            </span>
        </div>
        <div style="position:absolute; top:55%; left:9.5%; width:81%; height:67px; background-color:white; font-size:13px; text-align:center;">
            <span class="proxima">
             <strong> ATENTAMENTE </strong>
            </span>
        </div>
        <div style="position:absolute; top:62%; left:9.5%; width:81%; height:20px; background-color:white; font-size:13px; text-align:center;">
            <span class="proxima">
             <strong> _________________________________________________ </strong>
            </span>
        </div>
         <div style="position:absolute; top:64.5%; left:9.5%; width:81%; height:35px; background-color:white; font-size:13px; text-align:center;">
            <span class="proxima">
             <strong> <?= (isset($responsableGasto->nombre_completo) && !empty($responsableGasto->nombre_completo))?$responsableGasto->nombre_completo:''  ?> </strong>
            </span>
        </div>
           <div style="position:absolute; top:67%; left:9.5%; width:81%; height:60px; background-color:white; font-size:13px; text-align:center;">
            <span class="proxima">
              <strong> <?= (isset($responsableGasto->dsc_puesto) && !empty($responsableGasto->dsc_puesto))?$responsableGasto->dsc_puesto:'' ?></strong>
            </span>
        </div>
       



        
        
       
   
    
