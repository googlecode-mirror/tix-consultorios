<script type="text/javascript">
slidePaciente = null;
////////////////////////////////////////////////////////////////////////////////
function regresar()
{
	document.location = "<?php echo $urlRegresar; ?>";
}
////////////////////////////////////////////////////////////////////////////////
window.addEvent("domready", function(){
 
 	slidePaciente = new Fx.Slide('div_paciente');
	slidePaciente.hide();
 
	$('v_ampliar').addEvent('click', function(e){
			e.stop();
			slidePaciente.toggle();
	});		
	
	$('v_ocultar').addEvent('click', function(e){
			e.stop();
			slidePaciente.toggle();
	});			 
});
////////////////////////////////////////////////////////////////////////////////
</script>
<h1 class="tituloppal">Módulo consulta ambulatoria</h1>
<h2 class="subtitulo">Ver consulta</h2>
<center>
<table width="95%" class="tabla_form">
<tr><th colspan="2">Información del paciente</th></tr>
<tr><td>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
<tr>
<td colspan="2">
<table width="100%" cellpadding="2" cellspacing="2" border="0" class="tabla_interna">
<tr><td width="40%" class="campo">Apellidos:</td>
<td width="60%"><?=$tercero['primer_apellido']." ".$tercero['segundo_apellido']?></td></tr>
<tr><td class="campo">Nombres:</td><td><?=$tercero['primer_nombre']." ".$tercero['segundo_nombre']?></td></tr>
<tr><td class="campo">Documento de identidad:</td><td><?=$tercero['tipo_documento'].": ".$tercero['numero_documento']?></td></tr>
<tr><td class="campo">Fecha de nacimiento:</td><td><?=$tercero['fecha_nacimiento']?></td></tr>
<tr><td class="campo">Edad:</td><td><?=$this->lib_edad->edad($tercero['fecha_nacimiento'])?></td></tr>
<tr><td class="campo">Genero:</td><td><?=$paciente['genero']?></td></tr>
<tr><td class="campo">Entidad:</td><td>
<?php 
if(isset($entidad['razon_social']))
	echo $entidad['razon_social'];

?>
</td></tr>
</table>
</td></tr>
<tr><td colspan="2" class="linea_azul">
<span class="texto_barra">
<a href="#"  id="v_ampliar" title="Ampliar la informaci&oacute;n del paciente">
Ampliar la informaci&oacute;n del paciente
<img src="<?=base_url()?>resources/img/triangulo.png"/></a></span>
</td></tr>
<tr><td colspan="2">
<div id="div_paciente">
<table width="100%" cellpadding="2" cellspacing="2" border="0" class="tabla_interna">
<tr><td class="campo" width="40%">Pa&iacute;s:</td><td width="60%"><?=$tercero['PAI_NOMBRE']?></td></tr>
<tr><td class="campo">Departamento:</td><td><?=$tercero['depa']?></td></tr>
<tr><td class="campo">Municipio:</td><td><?=$tercero['nombre']?></td></tr>
<tr><td class="campo">Barrio / Vereda:</td><td><?=$tercero['vereda']?></td></tr>
<tr><td class="campo">Zona:</td><td><?=$tercero['zona']?></td></tr>
<tr><td class="campo">Direcci&oacute;n:</td><td><?=$tercero['direccion']?></td></tr>
<tr><td class="campo">Teléfono:</td><td><?=$tercero['telefono']?></td></tr>
<tr><td class="campo">Celular:</td><td><?=$tercero['celular']?></td></tr>
<tr><td class="campo">Fax:</td><td><?=$tercero['fax']?></td></tr>
<tr><td class="campo">Correo electrónico:</td><td><?=$tercero['email']?></td></tr>
<tr><td class="campo">Observaciones:</td><td><?=$tercero['observaciones']?></td></tr>
</table>
<p class="linea_azul">
<span class="texto_barra">
<a href="#"  id="v_ocultar" title="Ocultar la informaci&oacute;n del paciente">
Ocultar la informaci&oacute;n del paciente
<img src="<?=base_url()?>resources/img/triangulo.png"/></a></span>
</p>
</div>
</td></tr>
<tr><th colspan="2">Atenci&oacute;n del paciente</th></tr>
<tr><td class="campo">Fecha y hora de inicio:</td><td><?=$consulta['fecha_ini_consulta']?></td></tr>
<tr><td class="campo">Fecha y hora de finalización:</td><td><?=$consulta['fecha_fin_consulta']?></td></tr>
<tr><td class="campo">Medico tratante:</td>
<td><?=$medico['primer_apellido']." ".$medico['segundo_apellido']." ".$medico['primer_nombre']." ".$medico['segundo_nombre']?></td></tr>
<tr><td class="campo">Tipo medico:</td><td><?=$medico['tipo_medico']?></td></tr>
<tr><td class="campo">Especialidad:</td><td><?=$medico['especialidad']?></td></tr>
<tr><th colspan="2">Historia cl&iacute;nica</th></tr>
<tr>
<td width="35%" class="campo">Motivo de la consulta:</td><td width="65%">
<?=$consulta['motivo_consulta']?></td></tr>
<tr><td class="campo">Enfermedad actual:</td>
<td><?=$consulta['enfermedad_actual']?></td></tr>

<tr><td class="campo">Revisi&oacute;n sistemas:</td>
<td><?=$consulta['revicion_sistemas']?></td></tr>
                            <tr>
<th colspan="2">Antecedentes</th></tr>
<tr><td class="campo">Antecedentes patol&oacute;gicos:</td>
<td><?=$consulta_ant['ant_patologicos']?></td></tr>
<tr><td class="campo">Antecedentes farmacol&oacute;gicos:</td>
<td><?=$consulta_ant['ant_famacologicos']?></td></tr>
<tr><td class="campo">Antecedentes t&oacute;xico al&eacute;rgicos:</td>
<td><?=$consulta_ant['ant_toxicoalergicos']?></td></tr>
<tr><td class="campo">Antecedentes quir&uacute;rgicos:</td>
<td><?=$consulta_ant['ant_quirurgicos']?></td></tr>
<tr><td class="campo">Antecedentes familiares:</td>
<td><?=$consulta_ant['ant_familiares']?></td></tr>
<tr><td class="campo">Antecedentes ginecol&oacute;gicos:</td>
<td><?=$consulta_ant['ant_ginecologicos']?></td></tr>
<tr><td class="campo">Antecedentes otros:</td>
<td><?=$consulta_ant['ant_otros']?></td></tr>
<tr><th colspan="2">Examen f&iacute;sico</th></tr>
<tr><td class="campo">Condiciones generales:</td>
<td><?=$consulta['condiciones_generales']?></td></tr>
<tr><td class="campo">Peso:</td>
<td><?=$consulta['peso']?></td></tr>
<tr><td class="campo">Talla:</td>
<td><?=$consulta['talla']?></td></tr>

<?php
if(strlen($consulta['peso']) > 0 && strlen($consulta['talla']) > 0){
?>
<tr><td class="campo">Índice de masa corporal:</td>
<td><?=$this->lib_ope->imc($consulta['talla'],$consulta['peso'])?></td></tr>	
<?php
}
?>

<tr><td colspan="2" class="linea_azul"></td></tr>   
<tr><td colspan="2">
<table width="100%" border="0" cellspacing="2" cellpadding="2" style="text-align:center" class="tabla_interna">
<tr><td class="campo_centro" colspan="5">Signos vitales</td></tr>
<tr>
<td width="20%" class="campo_centro">Frecuencia cardiaca</td>
<td width="20%" class="campo_centro">Frecuencia respiratoria</td>
<td width="20%" class="campo_centro">Tensi&oacute;n arterial</td>
<td width="20%" class="campo_centro">Temperatura</td>
<td width="20%" class="campo_centro">Pulsioximetr&iacute;a (SPO2)</td>
</tr>
<tr>
<td><?=$consulta['frecuencia_cardiaca'];?> X min</td>
<td><?=$consulta['frecuencia_respiratoria'];?>   X min</td>
<td><?=$consulta['ten_arterial_s'];?>&nbsp;/&nbsp;<?=$consulta['ten_arterial_d'];?></td>
<td><?=$consulta['temperatura'];?> &deg;C</td>
<td><?=$consulta['spo2'];?> %</td>
</table>
</td></tr>
<tr><td colspan="2" class="linea_azul"></td></tr>   
<tr><td class="campo">Examen cabeza:</td>
<td><?=$consulta_exa['exa_cabeza'];?></td></tr>
<tr>
  <td class="campo">Examen ojos:</td>
<td><?=$consulta_exa['exa_ojos'];?></td></tr>
<tr>
  <td class="campo">Examen oral:</td>
<td><?=$consulta_exa['exa_oral'];?></td></tr>
<tr>
  <td class="campo">Examen cuello:</td>
<td><?=$consulta_exa['exa_cuello'];?></td></tr> 
<tr>
  <td class="campo">Examen dorso:</td>
<td><?=$consulta_exa['exa_dorso'];?></td></tr>
<tr>
  <td class="campo">Examen torax:</td>
<td><?=$consulta_exa['exa_torax'];?></td></tr>
<tr><td class="campo">Examen abdomen:</td>
<td><?=$consulta_exa['exa_abdomen'];?></td></tr>
<tr><td class="campo">Examen genitourinario:</td>
<td><?=$consulta_exa['exa_genito_urinario'];?></td></tr>
<tr>
  <td class="campo">Examen extremidades:</td>
<td><?=$consulta_exa['exa_extremidades'];?></td></tr>
<tr>
  <td class="campo">Examen neurol&oacute;gico:</td>
<td><?=$consulta_exa['exa_neurologico'];?></td></tr><tr>
<td class="campo">Examen piel:</td>
<td><?=$consulta_exa['exa_piel'];?></td></tr><tr>
<td class="campo">Examen mental:</td>
<td><?=$consulta_exa['exa_mental'];?></td></tr>
<tr><th colspan="2">Impresi&oacute;n diagnostica</th></tr>
<?php
$i = 1;
if(count($dx) > 0)
{
foreach($dx as $d)
{
?>
<tr><td class="campo">Diagnostico <?=$i?>:</td><td>
<?php
$i++;
switch ($d['tipo_dx']) {
    case 1:
        $tipodx = "Impresión diagnóstica";
        break;
    case 2:
        $tipodx = "Confirmado nuevo";
        break;
    case 3:
        $tipodx = "Confirmado repetido";
        break;
}
	echo '<strong>', $d['id_diag'], '</strong>', $d['diagnostico'], ' - ' ,$tipodx;
?>
</td></tr>
<?php
}
}else{
?>
<tr><td class="campo_centro" colspan="2">No hay diagnosticos asociados a la consulta inicial</td><td>
<?php
}
?>
<tr><td class="campo">Causa externa:</td>
<td><?=$atencion['causa_externa'];?></td></tr>
<tr><td class="campo">An&aacute;lisis:</td>
<td><?=$consulta['analisis'];?></td></tr>
<tr><td class="campo">Conducta:</td>
<td><?=$consulta['conducta'];?></td></tr>
<tr><td colspan="2" class="linea_azul"></td></tr>                               
</table>
<center>
<?
$data = array(	'name' => 'bv',
				'onclick' => 'regresar()',
				'value' => 'Volver',
				'type' =>'button');
echo form_input($data).nbs();
$data = array(	'name' => 'imp',
				'onclick' => "Abrir_ventana('".site_url('impresion/coam/consultaInicial/'.$atencion['id_atencion'])."')",
				'value' => 'Imprimir',
				'type' =>'button');
echo form_input($data);
?>
</center>
</div>
<br />
<?=form_close();?>
</td></tr></table>