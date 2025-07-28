 <div  style="position:absolute; top:10%; left:28%; width:65%; background-color:white; font-size: 13px;  height:18px;">
   <span ><?= strtoupper($nombre_registro->nombre_completo); ?> - <?= strtoupper($nombre_registro->dsc_puesto); ?> - <?= strtoupper($nombre_registro->dsc_area); ?> </span>
</div>
 <div  style="position:absolute; top:13%; left:28%; width:65%; background-color:white; font-size: 12px;  height:18px;">
   <span ><?= ($registro->comision); ?>  </span>
</div>
 <div  style="position:absolute; top: 15.5%; left:28%; width:65%; background-color:white; font-size: 12px;  height:18px;">
   <span ><?= ($registro->concepto_pago); ?>  </span>
</div>
 <div  style="position:absolute; top: 18.5%; left:28%; width:65%; background-color:white; font-size: 12px;  height:18px;">
   <span > <?= ($reserva[0]->partida);?> <?= ($reserva[0]->dsc_partida);?>  </span>
</div>
 <div  style="position:absolute; top: 23.6%; left:28%; width:65%; background-color:white; font-size: 12px;  height:18px;">
   <span > DEL <?= date('d-m-Y', strtotime($registro->fecha_gasto_inicio));?> AL <?= date('d-m-Y',strtotime($registro->fecha_gasto_fin));?>  </span>
</div>
 <div  style="position:absolute; top: 26.5%; left:28%; width:65%; background-color:white; font-size: 12px;  height:18px;">
  $<?= ($reserva[0]->total_importe); ?> (<?= mb_strtoupper($numero_texto, 'UTF-8'); ?>)
</div>
