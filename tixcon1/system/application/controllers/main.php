<?php
/*
###########################################################################
#Esta obra es distribuida bajo los términos de la licencia GPL Versión 3.0#
###########################################################################
*/
class Main extends Controller
{
	function Main()
	{
		parent::Controller();
	}
	
	function index()
	{
		$this -> load -> view('core/core_inicio_main');
		$this -> load -> view('core/pagina_inicio');
		$this -> load -> view('core/core_fin');
	}
}
