<?php
/*
###########################################################################
#Esta obra es distribuida bajo los t�rminos de la licencia GPL Versi�n 3.0#
###########################################################################
*/
class Autenticacion extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this -> load -> model('core/Autenticador');
		$this -> load -> model('core/Registro'); 
	}
	
	public function login()
	{
		///////////////////////////////////////////////////////
		
		$this -> load -> helper('url');
		$this -> load -> model('core/Registro');
		$this -> load -> model('core/Usuario');
		
		// Verificar si existe una sesi�n de usuario vigente

		$this -> load -> library('session');

		$session_username = $this -> session -> userdata('username');
		
		if ($session_username !== false)
		{
			// Terminar sesi�n activa
			
			$this -> logout(false);
		}
		
		///////////////////////////////////////////////////////

		// Revisar que existan los par�metros requeridos: username, password
		
		$username = $this -> input -> post('username', true);
		$password = $this -> input -> post('password', true);

		if ($username === false || $password === false)
		{
			// Generar un registro del evento sucedido

			$this -> Registro -> agregar('1', 'core', __CLASS__, __FUNCTION__, 
			                             'sistema', 'Par�metros incompletos.');

			// Terminar sesi�n activa
			
			$this -> logout(false);

			// Redirecciona al inicio con condici�n de error
			
			redirect('main/index/1', 'location');
			
			exit;
		}
		
		///////////////////////////////////////////////////////

		// Determinar los par�metros m�dulo/controlador/acci�n solicitados,
		// si no son especificados por el usuario externamente, se toman
		// los valores por defecto

		$modulo_solicitado = $this -> input -> post('modulo_solicitado', true);

		if ($modulo_solicitado === false)
			$modulo_solicitado = "core";

		$controlador_solicitado = $this -> input -> post('controlador_solicitado', true);

		if ($controlador_solicitado === false)
			$controlador_solicitado = "home";

		$accion_solicitado = $this -> input -> post('accion_solicitado', true);
		
		if ($accion_solicitado === false)
			$controlador_solicitado = "index";
		
		///////////////////////////////////////////////////////

		// Verificar la autenticidad del usuario
		
		$info = $this -> Usuario -> validar($username, $password);

		// Si falla la verificaci�n, redireccionar con mensaje de error
		
		if ($info === false)
		{
			// Generar un registro del evento sucedido

			$this -> Registro -> agregar('1', 'core', __CLASS__, __FUNCTION__, 
			                             'sistema', "Autenticaci�n fallida: {$username}.");

			// Terminar sesi�n activa
			
			$this -> logout(false);

			// Redirecciona al inicio con condici�n de error

			redirect('main/index/2', 'location');
			
			exit;
		}
		
		///////////////////////////////////////////////////////

		// Usuario autenticado!
		
		$usuario = $this -> Usuario -> obtenerPorNombreUsuario($username);
		
		// Se crea la sesi�n con la informaci�n del usuario autenticado
		
		$this -> session -> set_userdata('id_usuario', $usuario['id_usuario']);
		$this -> session -> set_userdata('username',   $usuario['_username']);
		$this -> session -> set_userdata('password',   $usuario['_password']);
		$this -> session -> set_userdata('nombres',    $usuario['nombres']);
		$this -> session -> set_userdata('apellidos',  $usuario['apellidos']);
		
		// Generar un registro del evento sucedido
		
		$this -> Registro -> agregar($usuario['id_usuario'], 'core', __CLASS__, __FUNCTION__, 
		                             'sistema', "Autenticaci�n exitosa: {$usuario['_username']}.");
		
		// Se redirige al cliente a la secci�n solicitada
		// Nota: la verificaci�n de permisos (basada en hooks) es la responsable
		//       de determinar si el usuario puede o no acceder a la secci�n
		//       solicitada.
		
		$destino = $modulo_solicitado . "/" . $controlador_solicitado . "/" . $accion_solicitado;

		redirect($destino, 'location');

		exit;

		///////////////////////////////////////////////////////
	}
	
	public function logout($registrar=true)
	{
		return $this -> Autenticador -> logout($registrar);
	}
}

?>
