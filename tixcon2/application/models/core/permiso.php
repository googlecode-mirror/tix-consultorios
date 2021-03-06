<?php
/*
###########################################################################
#Esta obra es distribuida bajo los términos de la licencia GPL Versión 3.0#
###########################################################################
*/
class Permiso extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
	}
	
	public function obtenerTodos()
	{
		$this -> db -> order_by("modulo, descripcion");
		$result = $this -> db -> get('core_permiso');
		
		return $result -> result_array();
	}
	
	public function obtenerSegunSeccion($modulo, $controlador, $accion)
	{
		$this -> db -> where('modulo', $modulo);
		$this -> db -> where('controlador', $controlador);
		$this -> db -> where('accion', $accion);
		$result = $this -> db -> get('core_permiso');
		
		if($result == null)
			return null;
		
		return $result -> row_array();
	}
}

?>