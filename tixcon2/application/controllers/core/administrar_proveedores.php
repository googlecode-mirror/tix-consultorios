<?php
/*
###########################################################################
#Esta obra es distribuida bajo los términos de la licencia GPL Versión 3.0#
###########################################################################
*/
class Administrar_proveedores extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this -> load -> helper('form');
		$this -> load -> model('core/proveedores_model');
		$this -> load -> model('core/paciente_model');
		$this -> load -> model('core/tercero_model'); 	
		$this -> load -> model('core/Registro'); 
	}
/////////////////////////////////////////////////////////////////////////////////////////////////
	function index()
	{
		//----------------------------------------------------------
		$d = array();
		//----------------------------------------------------------
		$d['urlRegresar'] 	= site_url('core/home/index');	
		//Configuración paginador
		$this->load->library('pagination');
		$config['base_url'] = site_url('core/administrar_proveedores/index');
		$config['total_rows'] = $this->proveedores_model->obtenerNumListaProveedores();
		$config['uri_segment'] = 4;
		$config['per_page'] = '25'; //Número de noticias por página
		$config['num_links'] = '2'; //Número de enlaces antes y después de la página actual
		$config['next_link'] = 'Siguiente&nbsp;&gt;';
		$config['prev_link'] = '&lt;&nbsp;Anterior';
		$config['first_link'] = '&lt;&lt;&nbsp;Primero'; //Texto del enlace que nos lleva a la página	&lt;
		$config['last_link'] = 'Último&nbsp;&gt;&gt;'; //Texto del enlace que nos lleva a la última página
		$this->pagination->initialize($config);

		$d['lista'] = $this->proveedores_model->obtenerListaProveedores($config['per_page'],
		$this->uri->segment(4)); //le pasamos el total de elementos por página y el offset
		
		$this->load->view('core/core_inicio');
		$this -> load -> view('core/proveedor_busqueda',$d);
		$this->load->view('core/core_fin');
	}
/////////////////////////////////////////////////////////////////////////////////////////////////
	function buscarProveedor()
	{
		//----------------------------------------------------------
		$d = array();
		//----------------------------------------------------------
		$d['urlRegresar'] 	= site_url('core/administrar_proveedores/index');	
		//----------------------------------------------------------
		$d['numero_documento'] 	= $this->input->post('numero_documento');
		$d['razon_social'] 	= $this->input->post('razon_social');
		//----------------------------------------------------------
		//Configuración paginador
		$this->load->library('pagination');
		$config['base_url'] = site_url('core/administrar_proveedores/index');
		$config['total_rows'] = $this->proveedores_model->obtenerNumListaProveedoresCon($d);
		$config['uri_segment'] = 4;
		$config['per_page'] = '25'; //Número de noticias por página
		$config['num_links'] = '2'; //Número de enlaces antes y después de la página actual
		$config['next_link'] = 'Siguiente&nbsp;&gt;';
		$config['prev_link'] = '&lt;&nbsp;Anterior';
		$config['first_link'] = '&lt;&lt;&nbsp;Primero'; //Texto del enlace que nos lleva a la página	&lt;
		$config['last_link'] = 'Último&nbsp;&gt;&gt;'; //Texto del enlace que nos lleva a la última página
		$this->pagination->initialize($config);

		$d['lista'] = $this->proveedores_model->obtenerListaProveedoresCon($d,$config['per_page'],
		$this->uri->segment(4)); //le pasamos el total de elementos por página y el offset

		//----------------------------------------------------------
		$this->load->view('core/core_inicio');
		$this -> load -> view('core/proveedor_busqueda',$d);
		$this->load->view('core/core_fin');
		//----------------------------------------------------------
	}
/////////////////////////////////////////////////////////////////////////////////////////////////	
	function crearProveedor()
	{
		//----------------------------------------------------------
		$d = array();
		//----------------------------------------------------------
		$d['urlRegresar'] 	= site_url('core/administrar_proveedores');
		$d['tipo_documento']	= $this -> tercero_model -> tipos_documento();
		$d['pais']				= $this -> tercero_model -> obtenerPais();
		//----------------------------------------------------------
		$this->load->view('core/core_inicio');
		$this -> load -> view('core/proveedor_crear', $d);
		$this->load->view('core/core_fin');
		//----------------------------------------------------------
	}
/////////////////////////////////////////////////////////////////////////////////////////////////
	function crearProveedor_()
	{
		//----------------------------------------------------------
		$d = array();
		//----------------------------------------------------------
		$d['numero_documento'] 	= $this->input->post('numero_documento');	
		$verTer = $this -> tercero_model ->verificaTercero($d['numero_documento']);
		if($verTer != 0){
			$dat['mensaje'] = "Ya existe un proveedor con el NIT ".$d['numero_documento']."!!";
			$dat['urlRegresar'] = site_url('core/administrar_proveedores/index');
			$this -> load -> view('core/presentacionMensaje', $dat);
			return;
		}
		//----------------------------------------------------------
		$d['primer_apellido'] 	= mb_strtoupper($this->input->post('primer_apellido'),'utf-8');
		$d['segundo_apellido'] 	= mb_strtoupper($this->input->post('segundo_apellido'),'utf-8');
		$d['primer_nombre'] 	= mb_strtoupper($this->input->post('primer_nombre'),'utf-8');
		$d['segundo_nombre'] 	= mb_strtoupper($this->input->post('segundo_nombre'),'utf-8');
		$d['id_tipo_documento'] = $this->input->post('id_tipo_documento');
		$d['fecha_nacimiento'] = $this->input->post('fecha_nacimiento');
		
		$d['razon_social'] = $this->input->post('razon_social');
		$d['pais'] 	= $this->input->post('pais');
		$d['departamento'] 	= $this->input->post('departamento');
		$d['municipio'] 	= $this->input->post('municipio');
		$d['vereda'] 	= mb_strtoupper($this->input->post('vereda'),'utf-8');
		$d['direccion'] 	= mb_strtoupper($this->input->post('direccion'),'utf-8');
		$d['zona'] 	= $this->input->post('zona');
		$d['telefono'] 	= $this->input->post('telefono');
		$d['celular'] 	= $this->input->post('celular');
		$d['fax'] 	= $this->input->post('fax');
		$d['email'] 	= $this->input->post('email');
		
		$d['representante_legal'] = $this->input->post('representante_legal');
		$d['nombre_contacto'] = $this->input->post('nombre_contacto');
		$d['estado'] 	= $this->input->post('estado');
		$d['observaciones'] 	= $this->input->post('observaciones');
		//----------------------------------------------------------
		$r = $this -> tercero_model -> crearTerceroDb($d);
		//----------------------------------------------------------
		$d['id_tercero'] 	= $r['id_tercero'];
		//----------------------------------------------------------
		$r = $this -> proveedores_model -> crearProveedorDb($d);
		//----------------------------------------------------------
		if($r['error'])
		{
			$this -> Registro -> agregar($this -> session -> userdata('id_usuario'),'core',__CLASS__,__FUNCTION__
			,'aplicacion',"Error en la creación de del proveedor ".$d['id_tercero']);
			$dat['mensaje'] = "La operación no se realio con exito.";
			$dat['urlRegresar'] = site_url('core/administrar_proveedores/index');
			$this -> load -> view('core/presentacionMensaje', $dat);
			return;
		}
		//----------------------------------------------------------
		$this -> Registro -> agregar($this -> session -> userdata('id_usuario'),'core',__CLASS__,__FUNCTION__
			,'aplicacion',"Se creo el proveedor con id ".$r['id_proveedor']);
		//----------------------------------------------------------
		$dt['mensaje']  = "Se ha creado el proveedor exitosamente!!";
		$dt['urlRegresar'] 	= site_url("core/administrar_proveedores/index");
		 
		//----------------------------------------------------------
		$this -> load -> view('core/presentacionMensaje', $dt);
		return;	
		//----------------------------------------------------------	
	}

///////////////////////////////////////////////////////////////////	
	function editarProveedor($id_proveedor)
	{
		//----------------------------------------------------------
		$d = array();
		//----------------------------------------------------------
		$d['urlRegresar'] 	= site_url('core/administrar_proveedores');
		$d['tipo_documento']	= $this -> tercero_model -> tipos_documento();
		$d['proveedor'] = $this -> proveedores_model -> obtenerProveedorConsulta($id_proveedor);
		$d['tercero'] = $this -> tercero_model -> obtenerTercero($d['proveedor']['id_tercero']);
		$d['pais'] = $this -> tercero_model -> obtenerPais();
		$d['departamento']	 	= $this -> tercero_model -> obtenerDepartamento();
		$d['municipio'] 		= $this -> tercero_model -> obtenerMunicipio($d['tercero']['departamento']);
		//----------------------------------------------------------
		$this->load->view('core/core_inicio');
		$this -> load -> view('core/proveedor_editar', $d);
		$this->load->view('core/core_fin');
		//----------------------------------------------------------
	}
///////////////////////////////////////////////////////////////////	
	function editarProveedor_()
	{
		//----------------------------------------------------------
		$d = array();
		//----------------------------------------------------------
		$d['id_tercero'] 	= $this->input->post('id_tercero');
		$d['primer_apellido'] 	= mb_strtoupper($this->input->post('primer_apellido'),'utf-8');
		$d['segundo_apellido'] 	= mb_strtoupper($this->input->post('segundo_apellido'),'utf-8');
		$d['primer_nombre'] 	= mb_strtoupper($this->input->post('primer_nombre'),'utf-8');
		$d['segundo_nombre'] 	= mb_strtoupper($this->input->post('segundo_nombre'),'utf-8');
		$d['razon_social'] 	= $this->input->post('razon_social');
		$d['fecha_nacimiento'] 	= $this->input->post('fecha_nacimiento');
		$d['id_tipo_documento'] 	= $this->input->post('id_tipo_documento');
		$d['numero_documento'] 	= $this->input->post('numero_documento');
		$d['pais'] 	= $this->input->post('pais');
		$d['departamento'] 	= $this->input->post('departamento');
		$d['municipio'] 	= $this->input->post('municipio');
		$d['vereda'] 	= mb_strtoupper($this->input->post('vereda'),'utf-8');
		$d['direccion'] 	= mb_strtoupper($this->input->post('direccion'),'utf-8');
		$d['zona'] 	= $this->input->post('zona');
		$d['telefono'] 	= $this->input->post('telefono');
		$d['celular'] 	= $this->input->post('celular');
		$d['fax'] 	= $this->input->post('fax');
		$d['email'] 	= $this->input->post('email');
		$d['observaciones'] 	= mb_strtoupper($this->input->post('observaciones'),'utf-8');	
		//----------------------------------------------------------
		$r = $this -> tercero_model -> editarTerceroDb($d);
		//----------------------------------------------------------
		$d['representante_legal'] = $this->input->post('representante_legal');
		$d['nombre_contacto'] = $this->input->post('nombre_contacto');
		$d['estado'] = $this->input->post('estado');
		$d['observaciones'] = $this->input->post('observaciones');
		$d['id_proveedor'] = $this->input->post('id_proveedor');	
		//----------------------------------------------------------
		$r = $this -> proveedores_model -> editarProveedorDb($d);
		//----------------------------------------------------------
		if($r['error'])
		{
			$this -> Registro -> agregar($this -> session -> userdata('id_usuario'),'core',__CLASS__,__FUNCTION__
			,'aplicacion',"Error en la modificación el proveedor con el ID ".$d['id_proveedor']);
			$dat['mensaje'] = "La operación no se realio con exito.";
			$dat['urlRegresar'] = site_url('core/administrar_proveedores/index');
			$this -> load -> view('core/presentacionMensaje', $dat);
			return;
		}
		//----------------------------------------------------------
		$this -> Registro -> agregar($this -> session -> userdata('id_usuario'),'core',__CLASS__,__FUNCTION__
			,'aplicacion',"Se modifico el proveedor con id ".$d['id_proveedor']);
		//----------------------------------------------------------
		$dt['mensaje']  = "Se ha modificado el proveedor exitosamente!!";
		$dt['urlRegresar'] 	= site_url("core/administrar_proveedores/index");
		 
		//----------------------------------------------------------
		$this -> load -> view('core/presentacionMensaje', $dt);
		return;	
	}
	
/////////////////////////////////////////////////////////////////////////////////////////////////
	function obtenerDepartamento()
	{
		$pais 	= $this->input->post('pais');
		$cadena = '';
   		if($pais == '52')
		{
			$departamento 		= $this -> tercero_model -> obtenerDepartamento();
			$cadena .='<select name="departamento" id="departamento" onchange="obtenerMunicipio()">';
			$cadena .='<option value="0" selected="selected">-Seleccione uno-</option>';
			foreach($departamento as $d)
			{
				$cadena .= '<option value="'.$d['id_departamento'].'">'.$d['nombre'].'</option>';
			}
			$cadena .='</select>';
		}else{
			$cadena .='<select name="departamento" id="departamento">';
			$cadena .= '<option value="999">-No aplica-</option>';
			$cadena .='</select>';
			$cadena .='<script>noAplicaMunicipio();</script>';
		}echo $cadena;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////
	function obtenerMunicipio()
	{
		$departamento 	= $this->input->post('departamento');
		$cadena = '';
		$departamento 		= $this -> tercero_model -> obtenerMunicipio($departamento);
		$cadena .='<select name="municipio" id="municipio">';
		$cadena .='<option value="0" selected="selected">-Seleccione uno-</option>';
		foreach($departamento as $d)
		{
			$cadena .= '<option value="'.$d['id_municipio'].'">'.$d['nombre'].'</option>';
		}
		$cadena .='</select>';
	
		echo $cadena;
	}	
/////////////////////////////////////////////////////////////////////////////////////////////////
	function noAplicaMunicipio()
	{
		$cadena = '';
		$cadena .='<select name="municipio" id="municipio">';
		$cadena .= '<option value="99999">-No aplica-</option>';
		$cadena .='</select>';
		echo $cadena;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////
}