<script type="text/javascript" src="<?=base_url()?>resources/js/lista_ajax/ajax.js"></script>
<script type="text/javascript" src="<?=base_url()?>resources/js/lista_ajax/ajax-dynamic-list.js"></script>
<table width="100%" cellspacing="0" cellpadding="0">
<tr><td class="campo" width="20%">Insumo o Dispositivo:</td><td width="80%">
<input size="60" type="text" id="insumo" name="insumo" value="" 
onkeyup="ajax_showOptions(this,'urg/insumos/listaInsumos',event)" AUTOCOMPLETE="off">
<input type="hidden" id="insumo_hidden" name="insumo_ID" value=""> 
</td></tr>
</table>
<table width="100%" cellspacing="0" cellpadding="0"> 
<tr>
<td class="campo_centro">Cantidad</td>
</tr>
<tr>
<td class="campo_centro"><?=form_input(array('name' => 'cantidad',
						'id'=> 'cantidad',
						'maxlength' => '6',
						'size'=> '6',
						'value'=> '0',
						'class'=>"fValidate['real']"))?></td>
</table>
<table width="100%" cellspacing="0" cellpadding="0">
<tr><td class="campo" width="20%">Observaciones:</td>
<td width="80%"><?=form_textarea(array('name' => 'observaciones',
							'id'=> 'observaciones',
							'rows' => '5',
							'cols'=> '45'))?></td></tr>
</table>
<center>
<?
$data = array(	'name' => 'ba',
				'onclick' => 'agregarInsumo()',
				'value' => 'Agregar',
				'type' =>'button');
echo form_input($data);
?>
</center>