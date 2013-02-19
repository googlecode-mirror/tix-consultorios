<?php
/*
###########################################################################
#Esta obra es distribuida bajo los términos de la licencia GPL Versión 3.0#
###########################################################################
*/
class Home extends Controller
{
	function __construct()
	{
		parent::Controller();
	}
	
	public function index()
	{
		$this -> load -> view('core/core_inicio');
		$this -> load -> model('core/home_model');
		$d = array();
		$d['nove'] = $this->home_model->obtenerNovedades();
		$this -> load -> view("core/home",$d);
		$this -> load -> view("core/core_fin");
	}
	
	public function volverError()
	{
		$this -> load -> view("core/mensajeError");	
	}
}

?>
