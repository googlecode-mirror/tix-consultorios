<script type="text/javascript">
slidePaciente = null;
////////////////////////////////////////////////////////////////////////////////
function agregar_dX()
{
	if(!validarDx())
	{
		return false;
	}
	
	var contDx = $('contDx').value;
	$('contDx').value = parseInt(contDx)+1;
	
	var var_url = '<?=site_url()?>/urg/atencion_inicial/agregar_dx';
	var ajax1 = new Request(
	{
		url: var_url,
		method: 'post',
		data:  $('formulario').toQueryString(),
		onRequest: function (){$('div_precarga').style.display = "block";},
		onSuccess: function(html){
		var html2 = $('div_lista_dx').get('html');
		$('div_lista_dx').set('html',html2, html);
		$('div_precarga').style.display = "none";},
		onComplete: function(){
			borrarForm();	
		},
		evalScripts: true,
		onFailure: function(){alert('Error ejecutando ajax!');}
	});
	ajax1.send();	
}
////////////////////////////////////////////////////////////////////////////////
function validarDx()
{
	if($('dx_hidden').value < 1){
		alert("Debe realizar la búsqueda de un diagnostico");
		return false;
	}
	return true;
}
////////////////////////////////////////////////////////////////////////////////
function borrarForm()
{	
	$('dx_hidden').value = '';
	$('dx').value = '';
	simple();
}
////////////////////////////////////////////////////////////////////////////////
function eliminarDx(id_tabla)
{	
	if(confirm('¿Desea eliminar el diagnostico seleccionado?')){
		var contDx = $('contDx').value;
		$('contDx').value = parseInt(contDx)-1;
		$(id_tabla).dispose();	
	}
}
////////////////////////////////////////////////////////////////////////////////
function validarFormulario()
{
	var contDx = $('contDx').value;
	
	if(contDx <= 0){
		alert("No se permite guardar una consulta sin diagnósticos!!");
		return false;
	}
	return true;	
}
////////////////////////////////////////////////////////////////////////////////
function regresar()
{
	if(confirm('La información no ha sido almacenada\n  ¿Esta seguro que desea continuar?'))
	{
		document.location = "<?php echo $urlRegresar; ?>";	
	}
}
////////////////////////////////////////////////////////////////////////////////
window.addEvent("domready", function(){
 var exValidatorA = new fValidator("formulario");
 
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
function avanzado()
{
	var var_url = '<?=site_url()?>/urg/atencion_inicial/dxAvanzados';
	var ajax1 = new Request(
	{
		url: var_url,
		method: 'post',
		data:  $('formulario').toQueryString(),
		onRequest: function (){$('div_precarga').style.display = "block";},
		onSuccess: function(html){$('div_dx').set('html', html);
		$('div_precarga').style.display = "none";},
		onComplete: function(){
		},
		evalScripts: true,
		onFailure: function(){alert('Error ejecutando ajax!');}
	});
	ajax1.send();		
}
////////////////////////////////////////////////////////////////////////////////
function simple()
{
	var var_url = '<?=site_url()?>/urg/atencion_inicial/dxSimple';
	var ajax1 = new Request(
	{
		url: var_url,
		method: 'post',
		data:  $('formulario').toQueryString(),
		onRequest: function (){$('div_precarga').style.display = "block";},
		onSuccess: function(html){$('div_dx').set('html', html);
		$('div_precarga').style.display = "none";},
		onComplete: function(){
		},
		evalScripts: true,
		onFailure: function(){alert('Error ejecutando ajax!');}
	});
	ajax1.send();		
}
</script>
<h1 class="tituloppal">Servicio de urgencias - Atención inicial</h1>
<h2 class="subtitulo">Atención de urgencias</h2>
<h3 class="subtitulo"><b><?=anchor('urg/atencion_inicial/desistirAtencion/'.$atencion['id_atencion'],'Desistir atenci&oacute;n')?></b></h3>
<center>
<table width="95%" class="tabla_form">
<tr><th colspan="2">Información del paciente</th></tr>
<tr><td>
<?php
$attributes = array('id'       => 'formulario',
	                'name'     => 'formulario',
					'method'   => 'post',
					'onsubmit' => 'return validarFormulario()');
echo form_open('/urg/atencion_inicial/consultaInicial_',$attributes);
echo form_hidden('fecha_ini_consulta',$fecha_ini_consulta);
echo form_hidden('id_atencion',$atencion['id_atencion']);
echo form_hidden('id_medico',$medico['id_medico']);
$edad = $this->lib_edad->edad($tercero['fecha_nacimiento']);
$annos = $this->lib_edad->annos($tercero['fecha_nacimiento']);
?>
<input type="hidden" name="contDx" value="0" id="contDx" />
<table width="100%" border="0" cellspacing="2" cellpadding="2">
<tr>
<td colspan="2">
<table width="100%" cellpadding="2" cellspacing="2" border="0" class="tabla_interna">
<tr><td width="40%" class="campo">Apellidos:</td>
<td width="60%"><?=$tercero['primer_apellido']." ".$tercero['segundo_apellido']?></td></tr>
<tr><td class="campo">Nombres:</td><td><?=$tercero['primer_nombre']." ".$tercero['segundo_nombre']?></td></tr>
<tr><td class="campo">Documento de identidad:</td><td><?=$tercero['tipo_documento'].": ".$tercero['numero_documento']?></td></tr>
<tr><td class="campo">Fecha de nacimiento:</td><td><?=$tercero['fecha_nacimiento']?></td></tr>
<tr><td class="campo">Edad:</td><td><?=$edad?></td></tr>
<tr><td class="campo">Genero:</td><td><?=$paciente['genero']?></td></tr>
<tr><td class="campo">Entidad:</td><td>
<?php 

$genero = $paciente['genero'];

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
<tr><td class="campo">Tipo usuario:</td><td>
<?
	foreach($tipo_usuario as $d)
	{
		if($paciente['id_cobertura'] == $d['id_cobertura'])
		{
			echo $d['cobertura'];
		}
	}
?>
</td></tr>
<tr><td class="campo">Tipo de afiliado:</td><td><?=$paciente['tipo_afiliado']?></td></tr>
<tr><td class="campo">Nivel o categoria:</td><td><?=$paciente['nivel_categoria']?></td></tr>
<tr><td class="campo">Desplazado:</td><td> <?=$paciente['desplazado']?></td></tr>
<tr><td class="campo">Observaciones:</td><td><?=$paciente['observaciones']?></td></tr>
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
<tr><td class="campo">Fecha y hora de atención:</td><td><?=$fecha_ini_consulta?></td></tr>
<tr><td class="campo">Medico tratante:</td>
<td><?=$medico['primer_apellido']." ".$medico['segundo_apellido']." ".$medico['primer_nombre']." ".$medico['segundo_nombre']?></td></tr>
<tr><td class="campo">Tipo medico:</td><td><?=$medico['tipo_medico']?></td></tr>
<tr><td class="campo">Especialidad:</td><td><?=$medico['especialidad']?></td></tr>
<tr><td class="campo">Paciente remitido:</td><td><?=$atencion['remitido']?></td></tr>
<?php
	$clas = "";
	if($atencion['clasificacion'] == 1){
		$clas = 'class="triage_rojo_con"';
	}else if($atencion['clasificacion'] == 2){
		$clas = 'class="triage_amarillo_con"';
	}else if($atencion['clasificacion'] == 3){
		$clas = 'class="triage_verde_con"';
	}else if($atencion['clasificacion'] == 4){
		$clas = 'class="triage_blanco_con"';
	}
	
?>
<tr><td class="campo">Clasificaci&oacute;n TRIAGE:</td><td <?=$clas?> style="padding:10px; text-align:left;"><?=$atencion['clasificacion']?></td></tr>
<tr><td class="campo">Escala Glasgow</td><td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Respuesta ocular:&nbsp;<strong><?=$triage['resp_ocular']?></strong></td>
    <td>Respuesta verbal:&nbsp;<strong><?=$triage['resp_verbal']?></strong></td>
    <td>Respuesta motora:&nbsp;<strong><?=$triage['resp_motora']?></strong></td>
    <td>Glasgow:&nbsp;<strong><?=$triage['resp_ocular']+$triage['resp_verbal']+$triage['resp_motora']?>/15</strong></td>
  </tr>
</table>

</td></tr>
<tr><th colspan="2">Historia cl&iacute;nica</th></tr>
<tr>
<td width="35%" class="campo">Motivo de la consulta:</td><td width="65%">
<?=form_textarea(array('name' => 'motivo_consulta',
								'id'=> 'motivo_consulta',
								'rows' => '3',
								'value' => $triage['motivo_consulta'],
								'class'=>"fValidate['required']",
								'cols'=> '55'))?></td></tr>
<tr><td class="campo">Enfermedad actual:</td>
<td><?=form_textarea(array('name' => 'enfermedad_actual',
								'id'=> 'enfermedad_actual',
								'rows' => '3',
								'class'=>"fValidate['required']",
								'cols'=> '55'))?></td></tr>

<tr><td class="campo">Revisi&oacute;n sistemas:</td>
<td><?=form_textarea(array('name' => 'revicion_sistemas',
							'id'=> 'revicion_sistemas',
							'rows' => '3',
							'class' => "fValidate['required']",
							'cols'=> '55'))?></td></tr>
                            <tr>
<th colspan="2">Antecedentes</th></tr>
<tr><td class="campo">Antecedentes patol&oacute;gicos:</td>
<td>
<?=form_textarea(array('name' => 'ant_patologicos',
								'id'=> 'ant_patologicos',
								'rows' => '3',
								'class'=>"fValidate['required']",
								'cols'=> '55'))?>
</td></tr>
<tr><td class="campo">Antecedentes farmacol&oacute;gicos:</td>
<td>
<?=form_textarea(array('name' => 'ant_famacologicos',
								'id'=> 'ant_famacologicos',
								'rows' => '3',
								'class'=>"fValidate['required']",
								'cols'=> '55'))?>
								</td></tr>
<tr><td class="campo">Antecedentes t&oacute;xico al&eacute;rgicos:</td>
<td>
<?=form_textarea(array('name' => 'ant_toxicoalergicos',
								'id'=> 'ant_toxicoalergicos',
								'rows' => '3',
								'class'=>"fValidate['required']",
								'cols'=> '55'))?>
</td></tr>
<tr><td class="campo">Antecedentes quir&uacute;rgicos:</td>
<td>
<?=form_textarea(array('name' => 'ant_quirurgicos',
								'id'=> 'ant_quirurgicos',
								'rows' => '3',
								'class'=>"fValidate['required']",
								'cols'=> '55'))?>
</td></tr>
<tr><td class="campo">Antecedentes familiares:</td>
<td>
<?=form_textarea(array('name' => 'ant_familiares',
								'id'=> 'ant_familiares',
								'rows' => '3',
								'class'=>"fValidate['required']",
								'cols'=> '55'))?>
</td></tr>
<tr><td class="campo">Antecedentes ginecol&oacute;gicos:</td>
<td>
<?php
	if($genero == 'Femenino' && $annos > 8){
echo form_textarea(array('name' => 'ant_ginecologicos',
								'id'=> 'ant_ginecologicos',
								'rows' => '3',
								'cols'=> '55',
								'class'=>"fValidate['required']"));
}else{
echo form_textarea(array('name' => 'ant_ginecologicos',
								'id'=> 'ant_ginecologicos',
								'rows' => '3',
								'value' => 'NO APLICA',
								'readonly' => 'readonly',
								'class'=>"fValidate['required']",
								'cols'=> '55'));
}
?>
</td></tr>
<tr><td class="campo">Otros Antecedentes:</td>
<td>

<?=form_textarea(array('name' => 'ant_otros',
								'id'=> 'ant_otros',
								'rows' => '3',
								'value' => $triage['antecedentes'],
								'class'=>"fValidate['required']",
								'cols'=> '55'))?>
</td></tr>
<tr><th colspan="2">Examen f&iacute;sico</th></tr>
<tr><td class="campo">Condiciones generales:</td>
<td><?=form_textarea(array('name' => 'condiciones_generales',
							'id'=> 'condiciones_generales',
							'rows' => '3',
							'class'=>"fValidate['required']",
							'cols'=> '55'))?></td></tr>						
<tr><td class="campo">Talla:</td>
<td><?=form_input(array('name' => 'talla',
							'id'=> 'talla',
							'onChange' => "vNum('talla','10','250')",
							'maxlength' => '5',
							'size'=> '5',
							))?>&nbsp;Centímetros</td></tr>
<tr><td class="campo">Peso:</td>
<?php
	if($annos <= 13){
?>
<td><?=form_input(array('name' => 'peso',
							'id'=> 'peso',
							'onChange' => "vNum('peso','1','160')",
							'maxlength' => '5',
							'class'=>"fValidate['required']",
							'size'=> '5',
							))?> &nbsp;Kilogramos </td>
<?php
}else{
?>
<td><?=form_input(array('name' => 'peso',
							'id'=> 'peso',
							'onChange' => "vNum('peso','1','160')",
							'maxlength' => '5',
							'size'=> '5',
							))?> &nbsp;Kilogramos </td>
<?php
}
?>							
</tr>
<tr><td colspan="2" class="linea_azul"></td></tr>   
<tr><td colspan="2">
<table width="100%" border="0" cellspacing="2" cellpadding="2" style="text-align:center" class="tabla_interna">
<tr><td class="campo_centro" colspan="5">Signos vitales TRIAGE</td></tr>
<tr>
<td width="20%" class="campo_centro">Frecuencia cardiaca</td>
<td width="20%" class="campo_centro">Frecuencia respiratoria</td>
<td width="20%" class="campo_centro">Tensi&oacute;n arterial</td>
<td width="20%" class="campo_centro">Temperatura</td>
<td width="20%" class="campo_centro">Pulsioximetr&iacute;a (SPO2)</td>
</tr>
<tr>
<td><?=$triage['frecuencia_cardiaca'];?> X min</td>
<td><?=$triage['frecuencia_respiratoria'];?>   X min</td>
<td><?=$triage['ten_arterial_s'];?>&nbsp;/&nbsp;<?=$triage['ten_arterial_d'];?></td>
<td><?=$triage['temperatura'];?> &deg;C</td>
<td><?=$triage['spo2'];?> %</td>
</table>
</td></tr>
<tr>
<td colspan="2" class="campo">
<table width="100%" border="0" cellspacing="0" cellpadding="2" style="text-align:center">
<tr><td colspan="5" class="campo_centro">
Signos vitales
</td></tr>
<tr>
<td width="20%">Frecuencia cardiaca</td>
<td width="20%">Frecuencia respiratoria</td>
<td width="20%">Tensi&oacute;n arterial</td>
<td width="20%">Temperatura</td>
<td width="20%">Pulsioximetr&iacute;a (SPO2)</td>
</tr>
<tr>
<td><?=form_input(array('name' => 'frecuencia_cardiaca',
							'id'=> 'frecuencia_cardiaca',
							'maxlength' => '3',
							'size'=> '3',
							'onChange' => "vNum('frecuencia_cardiaca','0','400')",
							'class'=>"fValidate['integer']"))?> X min</td>
<td><?=form_input(array('name' => 'frecuencia_respiratoria',
							'id'=> 'frecuencia_respiratoria',
							'onChange' => "vNum('frecuencia_respiratoria','0','100')",
							'maxlength' => '3',
							'size'=> '3',
							'class'=>"fValidate['integer']"))?>   X min</td>
<td><b>S</b>&nbsp;<?=form_input(array('name' => 'ten_arterial_s',
							'id'=> 'ten_arterial_s',
							'maxlength' => '3',
							'size'=> '3',
							'onChange' => "vNum('ten_arterial_s','0','350')",
							'class'=>"fValidate['integer']")).br()?>
                           <b>D</b>&nbsp;<?=form_input(array('name' => 'ten_arterial_d',
							'id'=> 'ten_arterial_d',
							'maxlength' => '3',
							'size'=> '3',
							'onChange' => "vNum('ten_arterial_d','0','250')",
							'class'=>"fValidate['integer']"))?></td>
<td><?=form_input(array('name' => 'temperatura',
							'id'=> 'temperatura',
							'maxlength' => '4',
							'size'=> '4',
							'onChange' => "vNum('temperatura','20','45')"))?> &deg;C</td>
<td><?=form_input(array('name' => 'spo2',
							'id'=> 'spo2',
							'maxlength' => '4',
							'size'=> '4',
							'onChange' => "vNum('spo2','0','100')"))?> %</td>
</table>
</td></tr>
<tr><td colspan="2" class="linea_azul"></td></tr>   
<tr><td class="campo">Examen cabeza:</td>
<td><?=form_textarea(array('name' => 'exa_cabeza',
						'id'=> 'exa_cabeza',
						'rows' => '3',
						'cols'=> '55',
						'class'=>"fValidate['required']"))?></td></tr>
<tr>
  <td class="campo">Examen ojos:</td>
<td><?=form_textarea(array('name' => 'exa_ojos',
						'id'=> 'exa_ojos',
						'rows' => '3',
						'cols'=> '55',
						'class'=>"fValidate['required']"))?></td></tr>
<tr>
  <td class="campo">Examen oral:</td>
<td><?=form_textarea(array('name' => 'exa_oral',
						'id'=> 'exa_oral',
						'rows' => '3',
						'cols'=> '55',
						'class'=>"fValidate['required']"))?></td></tr>
<tr>
  <td class="campo">Examen cuello:</td>
<td><?=form_textarea(array('name' => 'exa_cuello',
						'id'=> 'exa_cuello',
						'rows' => '3',
						'cols'=> '55',
						'class'=>"fValidate['required']"))?></td></tr> 
<tr>
  <td class="campo">Examen dorso:</td>
<td><?=form_textarea(array('name' => 'exa_dorso',
						'id'=> 'exa_dorso',
						'rows' => '3',
						'cols'=> '55',
						'class'=>"fValidate['required']"))?></td></tr>
<tr>
  <td class="campo">Examen torax:</td>
<td><?=form_textarea(array('name' => 'exa_torax',
						'id'=> 'exa_torax',
						'rows' => '3',
						'cols'=> '55',
						'class'=>"fValidate['required']"))?></td></tr>
<tr><td class="campo">Examen abdomen:</td>
<td><?=form_textarea(array('name' => 'exa_abdomen',
						'id'=> 'exa_abdomen',
						'rows' => '3',
						'cols'=> '55',
						'class'=>"fValidate['required']"))?></td></tr>
<tr><td class="campo">Examen genitourinario:</td>
<td><?=form_textarea(array('name' => 'exa_genito_urinario',
						'id'=> 'exa_genito_urinario',
						'rows' => '3',
						'cols'=> '55',
						'class'=>"fValidate['required']"))?></td></tr>
<tr>
  <td class="campo">Examen extremidades:</td>
<td><?=form_textarea(array('name' => 'exa_extremidades',
						'id'=> 'exa_extremidades',
						'rows' => '3',
						'cols'=> '55',
						'class'=>"fValidate['required']"))?></td></tr>
<tr>
  <td class="campo">Examen neurol&oacute;gico:</td>
<td><?=form_textarea(array('name' => 'exa_neurologico',
						'id'=> 'exa_neurologico',
						'rows' => '3',
						'cols'=> '55',
						'class'=>"fValidate['required']"))?></td></tr><tr>
<td class="campo">Examen piel:</td>
<td><?=form_textarea(array('name' => 'exa_piel',
						'id'=> 'exa_piel',
						'rows' => '3',
						'cols'=> '55',
						'class'=>"fValidate['required']"))?></td></tr><tr>
<td class="campo">Examen mental:</td>
<td><?=form_textarea(array('name' => 'exa_mental',
						'id'=> 'exa_mental',
						'rows' => '3',
						'cols'=> '55',
						'class'=>"fValidate['required']"))?></td></tr>
<tr><th colspan="2">Impresi&oacute;n diagnostica</th></tr>
<tr><td colspan="2">
<div id="div_lista_dx">
  
</div>
</td></tr>
<tr><td colspan="2" id="div_dx">
<?php
	echo $this->load->view('urg/urg_dxSimple');
?>
<tr><td colspan="2" align="center">
<?
$data = array(	'name' => 'bv',
				'onclick' => 'agregar_dX()',
				'value' => 'Agregar diagnostico',
				'type' =>'button');
echo form_input($data);
?>
</td></tr>
<tr><td colspan="2" class="linea_azul"></td></tr>
<tr><td class="campo">An&aacute;lisis:</td>
<td><?=form_textarea(array('name' => 'analisis',
							'id'=> 'analisis',
							'rows' => '3',
							'class'=>"fValidate['required']",
							'cols'=> '55'))?></td></tr>
<tr><td class="campo">Conducta:</td>
<td><?=form_textarea(array('name' => 'conducta',
							'id'=> 'conducta',
							'rows' => '3',
							'class'=>"fValidate['required']",
							'cols'=> '55'))?></td></tr>
<?php
	if($medico['id_tipo_medico'] == '1')
	{
?>
<tr><td colspan="2" id="div_verificar">
<?=$this->load->view('urg/urg_consultaConfirm')?>
</td></tr>
<?php
	}else{
		echo form_hidden('verificado','SI');
		echo form_hidden('id_medico_verifica',$medico['id_medico']);
		echo form_hidden('fecha_verificado',date('Y-m-d H:i:s'));
	}
?>
<tr><td colspan="2" class="linea_azul"></td></tr>                               
</table>
<center>
<?
$data = array(	'name' => 'bv',
				'onclick' => 'regresar()',
				'value' => 'Volver',
				'type' =>'button');
echo form_input($data);
?>
&nbsp;
<?=form_submit('boton', 'Guardar')?>
</center>
</div>
<br />
<?=form_close();?>
</td></tr></table>
