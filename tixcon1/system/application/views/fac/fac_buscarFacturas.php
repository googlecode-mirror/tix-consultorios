<script type="text/javascript">
////////////////////////////////////////////////////////////////////////////////
function regresar()
{
	document.location = "<?php echo $urlRegresar; ?>";
}
////////////////////////////////////////////////////////////////////////////////
window.addEvent("domready", function(){	
 var exValidatorA = new fValidator("formulario");		 
});
////////////////////////////////////////////////////////////////////////////////
</script>
<?php
$attributes = array('id'       => 'formulario',
	                'name'     => 'formulario',
					'method'   => 'post');
	echo form_open('/fac/consultar_facturas/buscarPaciente',$attributes);
?>
<h1 class="tituloppal">Historial de Facturación</h1>

<center>
<table width="70%" class="tabla_form">
<tr>
  <th colspan="2">Buscar por paciente</th></tr>
<tr><td width="50%" class="campo">Número de documento del paciente:</td>
<td width="50%"><?=form_input(array('name' => 'numero_documento',
							'id'=> 'numero_documento',
							'maxlength'   => '20',
							'size'=> '20',
							'class'=>"fValidate['nit']"))?>
</td></tr>
<tr><td colspan="2" align="center"><?
$data = array(	'name' => 'bv',
				'id' => 'bv',
				'onclick' => 'regresar()',
				'value' => 'Volver',
				'type' =>'button');
echo form_input($data);
?>
&nbsp;
<?=form_submit('boton', 'Buscar')?></td></tr>
</table>
</center>
<?=form_close();?>
