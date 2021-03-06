<?php echo $this->load->helper("open_flash_chart"); ?>
<script type="text/javascript">
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
function resetDiv()
{
	$('con_nota').set('html','');
	$('sv_nota').set('html','');
}
////////////////////////////////////////////////////////////////////////////////

function consultaNota(id)
{
	var var_url = '<?=site_url()?>/urg/sv_enfermeria/consultaNota/'+id;
	var ajax1 = new Request(
	{
		url: var_url,
		onRequest: function (){$('div_precarga').style.display = "block";},
		onSuccess: function(html){$('con_nota').set('html', html);
		$('div_precarga').style.display = "none";},
		onComplete: function(){
		},
		evalScripts: true,
		onFailure: function(){alert('Error ejecutando ajax!');}
	});
	ajax1.send();		
}
////////////////////////////////////////////////////////////////////////////////
function registroSignos(id_nota)
{
	var var_url = '<?=site_url()?>/urg/sv_enfermeria/crearNota/'+id_nota;
	var ajax1 = new Request(
	{
		url: var_url,
		onRequest: function (){$('div_precarga').style.display = "block";},
		onSuccess: function(html){$('sv_nota').set('html', html);
		$('div_precarga').style.display = "none";},
		onComplete: function(){
		},
		evalScripts: true,
		onFailure: function(){alert('Error ejecutando ajax!');}
	});
	ajax1.send();		
}
////////////////////////////////////////////////////////////////////////////////

function regresar()
{
	document.location = "<?php echo $urlRegresar; ?>";
}
////////////////////////////////////////////////////////////////////////////////
</script>



<h1 class="tituloppal">Servicio de urgencias - Signos vitales</h1>
<h2 class="subtitulo">Signos vitales enfermer&iacute;a</h2>
<center>
<table width="100%" border="0" cellspacing="2" cellpadding="2" class="tabla_form">
<tr><th colspan="2">Datos del paciente</th></tr>
<tr>
<td>
<table width="100%" border="0" cellspacing="2" cellpadding="2" class="tabla_interna">
<tr><td class="campo">Apellidos:</td>
<td><?=$tercero['primer_apellido']." ".$tercero['segundo_apellido']?></td><td class="campo">Nombres:</td><td><?=$tercero['primer_nombre']." ".$tercero['segundo_nombre']?></td></tr>
<tr><td class="campo">Documento de identidad:</td><td><?=$tercero['tipo_documento'].": ".$tercero['numero_documento']?></td><td class="campo">Genero:</td><td><?=$paciente['genero']?></td></tr>
<tr><td class="campo">Fecha de nacimiento:</td><td><?=$tercero['fecha_nacimiento']?></td><td class="campo">Edad:</td><td><?=$this->lib_edad->edad($tercero['fecha_nacimiento'])?></td></tr>
<tr></tr>
</table>
</td>
</tr>

 
  

<tr><td colspan="2">&nbsp;</td></tr>
<tr><th colspan="2">Signos Vitales </th></tr>
<tr>
 <td>
 <div id="sv_nota">
 
</div>
</td></tr>




<?php

	if($notas == 0)
	{
		echo "<tr> <td>";
		echo "<center><strong>No se ha registrado ningun signo vital</strong></center>";	
		echo "<tr/> </td>";
	}else{
?></td></tr>
 <tr>
  <td>
  
 <div style="float:left ; border: 1px solid #999999; margin:0px 10px">
 <? 
 
echo $this->load->helper("open_flash_chart");
open_flash_chart_object( 350, 200, site_url('urg/sv_enfermeria/presion_arterial/'.$atencion['id_atencion']), true,base_url('')."" );

 ?>

</div>

 <div style="float:left ; border: 1px solid #999999;">
 <? 
open_flash_chart_object( 350, 200, site_url('urg/sv_enfermeria/temperatura/'.$atencion['id_atencion']), true,base_url('')."" );
 ?>

</div>

<div style="float:left ; border: 1px solid #999999; margin:10px 10px">
 <? 
 
echo $this->load->helper("open_flash_chart");
open_flash_chart_object( 350, 200, site_url('urg/sv_enfermeria/frecuencia_cardiaca/'.$atencion['id_atencion']),true,base_url('')."" );

 ?>

</div>

 <div style="float:left ; border: 1px solid #999999;margin-top:10px">
 <? 
open_flash_chart_object( 350, 200, site_url('urg/sv_enfermeria/frecuencia_respiratoria/'.$atencion['id_atencion']), true,base_url('')."" );
 ?>

</div>

	</td>
  </tr>

<?php
if($monitor != 0){
	?>
<tr>
  <td colspan="2">
<table width="100%" border="0" cellspacing="2" cellpadding="2" class="tabla_interna">

<tr>
    <th colspan="2" class="campo_centro">Monitoria</th>
    
  </tr>
<tr>
    <td class="campo_centro">Fecha</td>
    <td class="campo_centro">Operación</td>
  </tr>
<?php
	foreach($monitor as $d)
	{
?>
  <tr>
<td><?=$d['fecha_turno'];?></td>

<td class="opcion"><a href="<?=site_url()?>/urg/sv_enfermeria/consultaMonitoria/<?=$d['id_atencion']?>/<?=$d['fecha_turno']?>" class="emailbutton" rel="lightbox[external 850 480]" title="<?=$d['fecha_turno']?>"><strong>Monitoria</strong></a></td>

</tr>

<?php
	}
		echo "</table>";
			echo "</td></tr>";	
}
?>


<tr>
  <td>
<div id="con_nota">

</div>
	</td>
  </tr>
  <td colspan="2">
<table width="100%" border="0" cellspacing="2" cellpadding="2" class="tabla_interna">
<tr>
    <th colspan="3" class="campo_centro">Seguimiento</th>
    
  </tr>
<tr>
    <td class="campo_centro">Fecha y hora</td>
    <td class="campo_centro">Realiza la nota</td>
    <td class="campo_centro">Operación</td>
  </tr>
<?php
	foreach($notas as $d)
	{
?>
  <tr>
<td><?=$d['fecha_nota'];?></td>
<td><?=$d['primer_apellido']." ".$d['segundo_apellido']." ".$d['primer_nombre']." ".$d['segundo_nombre']?></td>
<td class="opcion"><a href="#con_nota" onclick="consultaNota('<?=$d['id']?>')"><strong>Consultar</strong></a></td>

</tr>

<?php
	}
		echo "</table>";
			echo "</td></tr>";
	}
?>

<tr><td class="linea_azul"></td></tr>      




<tr><td align="center">
<?
$data = array(	'name' => 'bv',
				'onclick' => 'regresar()',
				'value' => 'Volver',
				'type' =>'button');
echo form_input($data);
?>
</td></tr>
</table>

