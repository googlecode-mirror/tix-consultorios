<?php
$marca = md5(mt_rand());
?>
<table width="100%" cellspacing="2" cellpadding="0" class="tabla_registro" id="dx<?=$marca?>">
<tr>
<td class="campo_centro" width="15%">Eliminar</td>
<td class="campo_centro" width="85%">Diagnostico</td>
</tr>
<tr>
<td rowspan="2" align="center">
<?php
echo form_hidden('dx_ID_[]',$dx_ID);
?>
<a href="#mosAgrMed" onclick="eliminarDx('dx<?=$marca?>')">[Eliminar]</a></td>
<td><?=$dx?></td>
</tr>
</table>