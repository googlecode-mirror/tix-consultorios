<?php
$n = count($lista);
if($n>0)
{
?>
<table style="width:100%" class="tabla_interna">
<tr>
<td class="campo_centro"># Orden</td>
<td class="campo_centro">Tipo Documento</td>
<td class="campo_centro">Nombre</td>
<td class="campo_centro">Fecha </td>
<td class="campo_centro">Procedimiento</td>
<td class="campo_centro">Etiqueta</td>
<td class="campo_centro">Accion</td>
</tr>
<?php
  $i = 1;
  
  foreach($lista as $d)
  {
	   print_r($d);
?>
<tr>
<td align="center"><strong><?=$d['id_ordenes']?></strong></td>
<td align="center"><?=$d['tipo_documento']." ".$d['numero_documento']?></td>
<td><?=$d['primer_nombre']." ".$d['primer_apellido']." ".$d['segundo_apellido']?></td>
<td><?=$d['fecha_envio_lab']?></td>
<td><?=$d['desc_subcategoria']?></td>
<td><? $data = array('name'=>'imp',
                'onclick' => "Abrir_ventana('".site_url('lab/laboratorio/etiqueta/'.$d['id_ordenes'].'/'.$d['tipo_documento'].$d['numero_documento'].'/'.$d['primer_nombre'].$d['primer_apellido'].'')."')",
                'value' => 'Imprimir',
                'type' =>'button');
echo form_input($data);?></td>


<?php

    if ($d['accion']=="recepcionar")
    { ?>
	<td class="opcion"><a href="<?=site_url()?>/lab/laboratorio/recepcionarOrden/<?=$d['id_ordenes']?>"><strong>Recepcionar</strong></a> </td>
	<?php	
	}if ($d['accion']=="tomar")
    { ?>
	<td class="opcion"><a href="<?=site_url()?>/lab/laboratorio/recepcionarOrden/<?=$d['id_ordenes']?>"><strong>Tomar</strong></a> </td>
 <?php
	} if ($d['accion']=="registrar") { ?>
	<td class="opcion"><a href="<?=site_url()?>/lab/laboratorio/registrarOrden/<?=$d['id_ordenes']?>"><strong>Registrar</strong></a> </td>
<?php }

?>
 


</td>

<?php
$i++;
  }

}else{
?>
<tr><td colspan="4" class="campo_centro">No se encontraron registros</td></tr>
</table>
<?php
}
?>