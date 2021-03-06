<script type="text/javascript">
sBuscar = null;
////////////////////////////////////////////////////////////////////////////////
function regresar()
{
	document.location = "<?php echo $urlRegresar; ?>";
}
////////////////////////////////////////////////////////////////////////////////
window.addEvent("domready", function(){
 var exValidatorA = new fValidator("formulario");
 var exValidatorB = new fValidator("formularioC");
 
 sBuscar = new Fx.Slide('div_buscar');
	sBuscar.hide();
 
 $('v_ampliar').addEvent('click', function(e){
			e.stop();
			sBuscar.toggle();
	});							 
});
////////////////////////////////////////////////////////////////////////////////
function validarFormulario()
{
	return true;
}
////////////////////////////////////////////////////////////////////////////////
</script>
<h1 class="tituloppal">Administración del sistema</h1>
<h2 class="subtitulo">Administración de funcionarios</h2>
<center>
  <?php
$attributes = array('id'       => 'formularioC',
	                'name'     => 'formularioC',
					'method'   => 'post');
echo form_open('/core/administrar_funcionario/crearFuncionario',$attributes);
?>
  <table width="100%" class="tabla_form">
    <tr>
    <th colspan="2">Registro de nuevo personal</th>
  </tr>
   <tr>
  <td colspan="2" align="center">
<table class="tabla_interna" width="70%">

		<tr>
			<td class="campo" width="50%">Número documento:</td>
			<td width="50%"><?=form_input(array('name' => 'numero_documentoC',
							'id'=> 'numero_documentoC',
							'class'=>"fValidate['alphanumtilde']",
							'maxlength'   => '20',
							'size'=> '20'))?></td>
		</tr>
		<tr><td colspan="2" align="center">
<?=form_submit('boton', 'Registrar')?>
</td></tr>
</table>
<?=form_close();?>
</td></tr>
    <tr><td colspan="2" class="linea_azul">
<span class="texto_barra">
<a href="#"  id="v_ampliar" title="Buscar personal asistencial">
Buscar personal asistencial
<img src="<?=base_url()?>resources/img/triangulo.png"/></a></span>
</td></tr>
  <tr><td colspan="2" id="div_buscar">
  <?php
$attributes = array('id'       => 'formulario',
	                'name'     => 'formulario',
					'method'   => 'post',
					'onsubmit' => 'return validarFormulario()');
echo form_open('/core/administrar_funcionario/buscarFuncionario',$attributes);
?>
  <table>
  <tr>
    <th colspan="2">Criterios de búsqueda</th>
  </tr>
  <tr>
    <td class="campo">Número de documento:</td>
    <td><?=form_input(array('name' => 'numero_documento',
							'id'=> 'numero_documento',
							'maxlength'   => '20',
							'size'=> '20'))?>
                            </td>
  </tr>
   <tr>
    <td width="50%" class="campo">Primer apellido:</td>
    <td width="50%"><?=form_input(array('name' => 'primer_apellido',
					'id'=> 'primer_apellido',
					'maxlength'   => '20',
					'size'=> '20'))?>	</td>
  </tr>
   <tr>
    <td class="campo">Segundo apellido:</td>
    <td><?=form_input(array('name' => 'segundo_apellido',
					'id'=> 'segundo_apellido',
					'maxlength'   => '20',
					'size'=> '20'))?>	</td>
  </tr>
  <tr>
    <td class="campo">Primer nombre:</td>
    <td><?=form_input(array('name' => 'primer_nombre',
					'id'=> 'primer_nombre',
					'maxlength'   => '20',
					'size'=> '20'))?></td>
  </tr>
   <tr>
    <td class="campo">Segundo nombre:</td>
    <td><?=form_input(array('name' => 'segundo_nombre',
					'id'=> 'segundo_nombre',
					'maxlength'   => '20',
					'size'=> '20'))?></td>
  </tr>
   <tr>
    <td class="campo">Dependencia:</td>
    <td><select name="id_dependencia" id="id_dependencia">
      <option value="0" selected="selected">-Seleccione uno-</option>
    <?
		foreach($dependencias as $d)
		{
				echo '<option value="'.$d['id_dependencia'].'">'.$d['nombre_dependencia'].'</option>';
		}
	?>
    </select></td>
  </tr>
  <tr><td colspan="2" align="center">
<?=form_submit('boton', 'Buscar')?>
</td></tr>
</table>
</td></tr>
<tr><td colspan="2">

<table width="100%" class="tabla_interna">
<tr><th colspan="6">Listado de funcionarios</th></tr>
<tr>
<td class="campo_centro">Apellidos</td>
<td class="campo_centro">Nombres</td>
<td class="campo_centro">Dependencia</td>
<td class="campo_centro">Cargo</td>
<td class="campo_centro">Estado</td>
<td class="campo_centro">Editar</td>
</tr>
<?php
	foreach($lista as $d)
	{
?>
<tr>
<td><?=$d['primer_apellido']." ".$d['segundo_apellido']?></td>
<td><?=$d['primer_nombre']." ".$d['segundo_nombre']?></td>
<td><?=$d['nombre_dependencia']?></td>
<td><?=$d['cargo']?></td>
<td><?=$d['estado']?></td>
<td class="opcion"><a href="<?=site_url('core/administrar_funcionario/editarFuncionario/'.$d['id_funcionario'])?>">Editar</a></td>
</tr>
<?php
	}
?>
</table>

</td></tr>
<tr><td colspan="2"><?=$this->pagination->create_links()?></td></tr>
<tr><td colspan="2" align="center">
  <?
$data = array(	'name' => 'bv',
				'id' => 'bv',
				'onclick' => 'regresar()',
				'value' => 'Volver',
				'type' =>'button');
echo form_input($data);?>
</td></tr>
</table>
</center>
<?=form_close();?>
