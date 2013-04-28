<?php
$n = count($lista);
if($n>0)
{
?>
<table style="width:100%" class="tabla_interna">
<tr>
<td class="campo_centro">No.</td>
<td class="campo_centro">Fecha y hora solicitud</td>
<td class="campo_centro">Paciente</td>
<td class="campo_centro">Servicio</td>
<td class="campo_centro">Cama</td>
<td class="campo_centro">Operación</td>
</tr>
<?php
  $i = 1;
  
  foreach($lista as $d)
  {
	  
	$d['ordenMedi'] = $this -> hospi_model -> obtenerMediOrdenInsu($d['id_orden']);
   if(count($d['ordenMedi']) != 0){
?>
<tr>
<td align="center"><strong><?=$i?></strong></td>
<td><?=$d['fecha_creacion']?></td>
<td><?=$d['primer_apellido']." ".$d['segundo_apellido']." ".$d['primer_nombre']." ".$d['segundo_nombre']?></td>
<td><?=$d['nombre_servicio']?></td>
<td style="text-align:center""><?=$d['numero_cama']?></td>
<td class="opcion"><a href="<?=site_url()?>/far/far_hospi/consultarOrden/<?=$d['id_orden']?>"><strong>Consultar</strong></a></td>

<?php
$i++;
	}
  }

}else{
?>
<tr><td colspan="4" class="campo_centro">No se encontraron registros</td></tr>
</table>
<?php
}

?>