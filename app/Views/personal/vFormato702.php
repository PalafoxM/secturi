 
 
 <style>
    #encabezado{
       position:absolute; 
       top:5%; 
       left:8%; 
       width:84.5%; 
       background-color:black;
      font-size: 8px;
        height:18px; 
        color:white;
        text-align:center;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #responsable{
       position:absolute; 
       top:6.77%; 
       left:8%; 
       width: 19.5%; 
       border:solid 1px black;
       font-size: 7px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
        padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #concepto{
       position:absolute; 
       top:10.5%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
       font-size: 7px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #comision{
       position:absolute; 
       top:8.65%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
       background-color: #BFBDBB;
        padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;

    }
    #partida{
       position:absolute; 
       top:12.4%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
      font-size: 7px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #factura{
       position:absolute; 
       top:14.3%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #fecha{
       position:absolute; 
       top:16.2%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
       font-size: 7px;
       height:18px; 
       text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #importe{
       position:absolute; 
       top:18.1%; 
       left:8%; 
       width:19.5%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
        text-align:left;
       background-color: #BFBDBB;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }
    #nombre{
       position:absolute; 
       top:6.62%; 
       left:28.1%; 
       width: 64.15%; 
       border:solid 1px black;
      font-size: 7px;
        height:18px; 
       text-align:left;
         padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;

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
       /* background-color: red;*/
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
      top:16.1%; 
      left:28.1%; 
      width: 64.15%;
      padding-left: 4px;     /* Espacio a la izquierda */
      padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
      box-sizing: border-box;
      border:solid 1px black;
      font-size: 7px;
      height:18px; 
      text-align:left;
    }
    #importe_respuesta{
       position:absolute; 
       top:18.1%; 
       left:28.1%; 
       border:solid 1px black;
       font-size: 7px;
       height:18px; 
       text-align:left;
        width: 64.15%;
        padding-left: 4px;     /* Espacio a la izquierda */
        padding-top: 2px;      /* Opcional: si el texto toca el borde superior */
        box-sizing: border-box;
    }

 </style>
 <div id="encabezado">
    <strong>ENCABEZADO DE FACTURA</strong>
</div>
<div id="responsable">
   RESPONSABLE / CARGO / AREA:
</div>
 <div id="nombre">
   <?php if(!$fic): ?>
   <span ><?= strtoupper($responsableGasto->nombre_completo); ?> - <?= strtoupper($responsableGasto->dsc_puesto); ?> - <?= strtoupper($responsableGasto->dsc_area); ?> </span>
    <?php endif; ?>
   <?php if($fic): ?>
   <span >HUGO RAMÍREZ DUARTE - DIRECCIÓN DE COMPETITIVIDAD TURÍSTICA
 </span>
    <?php endif; ?>
</div>
<div id="comision">
   COMISION / REUNION / EVENTO:
</div>
 <div id="comision_respuesta">
   <span > &nbsp;<?= ($registro->comision); ?>  </span>
</div>
<div id="concepto">
   CONCEPTO DEL PAGO:
</div>
 <div id="concepto_respuesta">
   <span> &nbsp;<?= ($registro->concepto_pago); ?>  </span>
</div>
<div id="partida">
   PARTIDA:
</div>
<div id="partida_respuesta">
   <?php
   $total = count($reserva);
   $i = 0;
   foreach($reserva as $r):
       $i++;
   ?>
       &nbsp;<?= $r->partida ?> <?= $r->dsc_partida ?><?= ($i < $total) ? ' /' : '' ?>
   <?php endforeach; ?>
</div>

<div id="factura">
   FACTURA / RECIBO No: 
</div>
<div id="factura_respuesta">
<?= '  '.$uuid ?>
</div>
<div id="fecha">
   FECHA DEL GASTO:
</div>

 <div id="fecha_respuesta">
   <?php if(isset($registro->fecha_gasto_inicio) && !empty($registro->fecha_gasto_inicio)): ?>
   <span > DEL <?= date('d-m-Y', strtotime($registro->fecha_gasto_inicio));?> AL <?= date('d-m-Y',strtotime($registro->fecha_gasto_fin));?>  </span>
   <?php endif; ?>
</div>


<div id="importe">
   IMPORTANTE EN PESOS (MXN):
</div>
<?php
function formatearImporteSeguro($valor) {
    try {
        if (is_numeric($valor)) {
            return number_format(floatval($valor), 2);
        }
        
        $limpio = preg_replace('/[^\d\.\-]/', '', (string)$valor);
        return is_numeric($limpio) ? number_format(floatval($limpio), 2) : '0.00';
        
    } catch (Exception $e) {
        return '0.00';
    }
}

function obtenerTotalImporte($reserva) {
    if (!empty($reserva) && isset($reserva[0]->total_importe)) {
        return formatearImporteSeguro($reserva[0]->total_importe);
    }
    return '0.00';
}
?>

<div id="importe_respuesta">
<?php
if (!empty($reserva)) {
    $total = count($reserva);
    $i = 0;

    foreach ($reserva as $r) {
        $i++;
        if ($i <= $total) {
            echo '&nbsp;$' . formatearImporteSeguro($r->importe);
            if ($i < $total) {
                echo ' / ';
            } else {
                echo '';
            }
        }
    }

    // Mostrar total y texto
    echo ($total >= 2) ? '&nbsp;<br>$' . obtenerTotalImporte($reserva) : '';
    echo ' (' . mb_strtoupper($numero_texto, 'UTF-8') . ')';
}
?>
</div>

