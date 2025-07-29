 
 
 <style>
    #encabezado{
       position:absolute; 
       top:5%; 
       left:8%; 
       width:85%; 
       background-color:black;
      font-size: 8px;
        height:18px; 
        color:white;
        text-align:center;
    }
    #responsable{
       position:absolute; 
       top:6.77%; 
       left:8%; 
       width:20%; 
       border:solid 1px black;
       font-size: 7px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
    }
    #concepto{
       position:absolute; 
       top:10.5%; 
       left:8%; 
       width:20%; 
       border:solid 1px black;
       font-size: 7px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
    }
    #comision{
       position:absolute; 
       top:8.65%; 
       left:8%; 
       width:20%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
       background-color: #BFBDBB;
    }
    #partida{
       position:absolute; 
       top:12.4%; 
       left:8%; 
       width:20%; 
       border:solid 1px black;
      font-size: 7px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
    }
    #factura{
       position:absolute; 
       top:14.3%; 
       left:8%; 
       width:20%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
       background-color: #BFBDBB;
    }
    #fecha{
       position:absolute; 
       top:16.2%; 
       left:8%; 
       width:20%; 
       border:solid 1px black;
       font-size: 7px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
    }
    #importe{
       position:absolute; 
       top:18.1%; 
       left:8%; 
       width:20%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
       background-color: #BFBDBB;
    }
    #nombre{
       position:absolute; 
       top:6.77%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
       text-align:left;

    }
    #comision_respuesta{
       position:absolute; 
       top:8.65%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
    }
    #concepto_respuesta{
       position:absolute; 
       top:10.5%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
    }
    #partida_respuesta{
       position:absolute; 
       top:12.4%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
    }
    #factura_respuesta{
       position:absolute; 
       top:14.3%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
    }
    #fecha_respuesta{
       position:absolute; 
       top:16.2%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
    }
    #importe_respuesta{
       position:absolute; 
       top:18.1%; 
       left:28.1%; 
       width: 64.65%; 
       border:solid 1px black;
       font-size: 7px;
       height:18px; 
       text-align:left;
    }
 </style>
 <div id="encabezado">
    <strong>ENCABEZADO DE FACTURA</strong>
</div>
<div id="responsable">
   RESPONSABLE / CARGO / AREA:
</div>
 <div id="nombre">
   <span ><?= strtoupper($nombre_registro->nombre_completo); ?> - <?= strtoupper($nombre_registro->dsc_puesto); ?> - <?= strtoupper($nombre_registro->dsc_area); ?> </span>
</div>
<div id="comision">
   COMISION / REUNION / EVENTO:
</div>
 <div id="comision_respuesta">
   <span ><?= ($registro->comision); ?>  </span>
</div>
<div id="concepto">
   CONCEPTO DEL PAGO:
</div>
 <div id="concepto_respuesta">
   <span ><?= ($registro->concepto_pago); ?>  </span>
</div>
<div id="partida">
   PARTIDA:
</div>
 <div id="partida_respuesta">
   <span > <?= ($reserva[0]->partida);?> <?= ($reserva[0]->dsc_partida);?>  </span>
</div>
<div id="factura">
   FACTURA / RECIBO No: 
</div>
<div id="factura_respuesta">

</div>
<div id="fecha">
   FECHA DEL GASTO:
</div>
 <div id="fecha_respuesta">
   <span > DEL <?= date('d-m-Y', strtotime($registro->fecha_gasto_inicio));?> AL <?= date('d-m-Y',strtotime($registro->fecha_gasto_fin));?>  </span>
</div>
<div id="importe">
   IMPORTANTE EN PESOS (MXN):
</div>
 <div id="importe_respuesta">
  $<?= ($reserva[0]->total_importe); ?> (<?= mb_strtoupper($numero_texto, 'UTF-8'); ?>)
</div>
