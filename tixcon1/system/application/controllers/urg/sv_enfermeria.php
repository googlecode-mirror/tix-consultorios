<?php
/*
###########################################################################
#Esta obra es distribuida bajo los términos de la licencia GPL Versión 3.0#
###########################################################################
*/
/*
 *OPUSLIBERTATI http://www.opuslibertati.org
 *Proyecto: SISTEMA DE INFORMACIÓN - GESTIÓN HOSPITALARIA
 *Nobre: sv_enfermeria
 *Tipo: controlador
 *Descripcion: Gestiona los signos vitales en enfermeria 
 *Autor: Diego Ivan Carvajal Gil <dcarvajal@opuslibertati.org>
 *Fecha de creación: 15 de noviembre de 2011
*/

class Sv_enfermeria extends Controller
{
///////////////////////////////////////////////////////////////////
	function __construct()
	{
		parent::Controller();			
		$this -> load -> model('urg/urgencias_model');
		$this -> load -> model('urg/enfermeria_model');
		$this -> load -> model('core/paciente_model');
		$this -> load -> model('core/tercero_model'); 
		$this -> load -> helper('html');	
				
	}
///////////////////////////////////////////////////////////////////


	
	/* 
* @Descripcion: genera la lista de signos vitales y su correspondiente grafica.
* @author Diego Ivan Carvajal Gil <dcarvajal@opuslibertati.org>
* @author http://www.opuslibertati.org
* @copyright	GNU GPL 3.0
* @since		20111115
* @version		20111115
*/
	
	function main($id_atencion)
	{
		//----------------------------------------------------------
		$d = array();
		$d['id_atencion'] = $id_atencion;
		$d['atencion'] = $this -> urgencias_model -> obtenerAtencion($id_atencion);
		$d['paciente'] = $this -> paciente_model -> obtenerPacienteConsulta($d['atencion']['id_paciente']);
		$d['tercero'] = $this -> paciente_model -> obtenerTercero($d['paciente']['id_tercero']);
		$d['ultima_nota'] = $this -> enfermeria_model -> obtenerUltimaNota($id_atencion);
		//Codificación de la sala de espera según el servicio
		$d['notas'] = $this -> enfermeria_model -> obtenerSvNotas($id_atencion,$d['atencion']['id_servicio']);	
	 
		
		$id_serv = $d['atencion']['id_servicio'];
		if($id_serv == 16 || $id_serv == 17 || $id_serv == 18){
		$d['urlRegresar'] 	= site_url('/urg/enfermeria_obs/main/'.$id_atencion);
		}else{
		$d['urlRegresar'] 	= site_url('/urg/enfermeria/main/'.$id_atencion);
		
	
		}
		$data_1 = array();
		$data_2 = array();
		$data_3 = array();
		$data_4 = array();
		$data_5 = array();	
		$data_6 = array();		
			
			if ($d['notas']!=0){
			foreach ($d['notas'] as $item){
			$data_1[] = $item['ten_arterial_s'];
			$data_2[] = $item['ten_arterial_d'];
			$data_3[] = $item['temperatura'];
			$data_4[] = $item['pulso'];
			$data_5[] = $item['fecha_nota'];
			$data_6[] = $item['spo2'];		
			}
			}
$this->graph->title( 'Signos Vitales', '{font-size: 20px; color: #736AFF}' );

// we add 3 sets of data:
$this->graph->set_data( $data_1 );
$this->graph->set_data( $data_2 );
$this->graph->set_data( $data_3 );
$this->graph->set_data( $data_4 );
$this->graph->set_data( $data_6 );


// we add the 3 line types and key labels
$this->graph->line_hollow( 2, 4, '0x80a033', 'P_SISTOLICA', 10 );
$this->graph->line_hollow( 2, 4, '0x80a033', 'P_DISTOLICA', 10 );

$this->graph->line_dot( 3, 5, '0xCC0000', 'TEMPERATURA', 10); 
$this->graph->line( 2, '0xCC3399', 'PULSO', 10); 
$this->graph->line_hollow( 2,5, '0x000066', 'SPO2', 10); 
   // <-- 3px thick + dots

$this->graph->set_x_labels($data_5 );
$this->graph->set_x_label_style( 10, '0x000000', 0, 2 );

$this->graph->set_y_max( 300 );
$this->graph->set_y_min( 0 );
$this->graph->y_label_steps( 10 );
$this->graph->set_y_legend( 'Opus Libertati', 12, '#736AFF' );
$this->graph->set_output_type('js');


		//-----------------------------------------------------------
		$this->load->view('core/core_inicio');
		$this -> load -> view('urg/urg_svListado', $d);
		$this->load->view('core/core_fin');
		//----------------------------------------------------------
		}
///////////////////////////////////////////////////////////////////

	/* 
* @Descripcion: Consultar un signo vital
* @author Diego Ivan Carvajal Gil <dcarvajal@opuslibertati.org>
* @author http://www.opuslibertati.org
* @copyright	GNU GPL 3.0
* @since		20111115
* @version		20111115
*/
	function consultaNota($id)
	{
		$d = array();
		$d['nota'] = $this->enfermeria_model->obtenerSvNotaConsulta($id);
		echo $this->load->view('urg/urg_notaSvConsulta',$d);
	}

///////////////////////////////////////////////////////////////////

	/* 
* @Descripcion:  Crear un registro de un signo vital.
* @author Diego Ivan Carvajal Gil <dcarvajal@opuslibertati.org>
* @author http://www.opuslibertati.org
* @copyright	GNU GPL 3.0
* @since		20111115
* @version		20111115
*/	
	function crearNota($id_atencion)
	{
		//---------------------------------------------------------------
		$d = array();
		//---------------------------------------------------------------
	
		$d['atencion'] = $this -> urgencias_model -> obtenerAtencion($id_atencion);
	
		$d['paciente'] = $this -> paciente_model -> obtenerPacienteConsulta($d['atencion']['id_paciente']);
		$d['tercero'] = $this -> paciente_model -> obtenerTercero($d['paciente']['id_tercero']);
		$d['id_medico'] = $this -> urgencias_model -> obtenerIdMedico($this->session->userdata('id_usuario'));
		$d['medico'] = $this -> urgencias_model -> obtenerMedico($d['id_medico']);
		$d['consulta'] = $this -> urgencias_model -> obtenerConsulta($id_atencion);
		
		//---------------------------------------------------------------
		
		$this -> load -> view('urg/urg_svnotaCrear',$d);
		
		//---------------------------------------------------------------
	}


	/* 
* @Descripcion: Crear una nota de enfermeria partiendo de la ultima registrada
* @author Diego Ivan Carvajal Gil <dcarvajal@opuslibertati.org>
* @author http://www.opuslibertati.org
* @copyright	GNU GPL 3.0
* @since		20111115
* @version		20111115
*/
	function crearNotaEdit($id_atencion)
	{
		//---------------------------------------------------------------
		$d = array();
		//---------------------------------------------------------------
		$d['nota'] = $this->enfermeria_model->obtenerUltNota($id_atencion);
		$d['urlRegresar'] 	= site_url('urg/notas_enfermeria/main/'.$id_atencion);
		$d['atencion'] = $this -> urgencias_model -> obtenerAtencion($id_atencion);
		$d['paciente'] = $this -> paciente_model -> obtenerPacienteConsulta($d['atencion']['id_paciente']);
		$d['tercero'] = $this -> paciente_model -> obtenerTercero($d['paciente']['id_tercero']);
		$d['id_medico'] = $this -> urgencias_model -> obtenerIdMedico($this->session->userdata('id_usuario'));
		$d['medico'] = $this -> urgencias_model -> obtenerMedico($d['id_medico']);
		$d['consulta'] = $this -> urgencias_model -> obtenerConsulta($id_atencion);
		//---------------------------------------------------------------
		$this->load->view('core/core_inicio');	
		$this -> load -> view('urg/urg_notaCrearEdit',$d);
		$this->load->view('core/core_fin');	
		//---------------------------------------------------------------
	}
///////////////////////////////////////////////////////////////////
	/* 
* @Descripcion: Guarda el registro de los signos vitales.
* @author Diego Ivan Carvajal Gil <dcarvajal@opuslibertati.org>
* @author http://www.opuslibertati.org
* @copyright	GNU GPL 3.0
* @since		20111115
* @version		20111115
*/
	function crearNota_()
	{
		//---------------------------------------------------------------
		$d = array();
		$d['pulso'] = $this->input->post('frecuencia_cardiaca');
		$d['frecuencia_respiratoria']  =$this->input->post('frecuencia_respiratoria');
		$d['ten_arterial_s']  = $this->input->post('ten_arterial_s');
		$d['ten_arterial_d']  = $this->input->post('ten_arterial_d');
		$d['temperatura']  = $this->input->post('temperatura');
		$d['spo2']  = $this->input->post('spo2');
		$d['peso']  = $this->input->post('peso');
		$d['id_medico'] = $this->input->post('id_medico');
		$d['id_servicio'] = $this->input->post('id_servicio');
		$d['id_atencion'] = $this->input->post('id_atencion');
		$atencion = $this -> urgencias_model -> obtenerAtencion($d['id_atencion']);
		
		//----------------------------------------------------------
		$d['id_nota'] = $this -> enfermeria_model -> crearSvNotaDb($d);
		//----------------------------------------------------
		$dt['mensaje']  = "Los datos de los signos vitales se han almacenado correctamente!!";
		$dt['urlRegresar'] 	= site_url("urg/sv_enfermeria/main/".$d['id_atencion']);
		$this -> load -> view('core/presentacionMensaje', $dt);
		return;	
		//----------------------------------------------------------
	}
	
		/* 
* @Descripcion: Permite consultar los signos vitales.
* @author Diego Ivan Carvajal Gil <dcarvajal@opuslibertati.org>
* @author http://www.opuslibertati.org
* @copyright	GNU GPL 3.0
* @since		20111115
* @version		20111115
*/
	
function consultarSv($id_atencion)
	{
		//----------------------------------------------------------
		$d = array();
		$d['id_atencion'] = $id_atencion;
		$d['atencion'] = $this -> urgencias_model -> obtenerAtencion($id_atencion);
		$d['paciente'] = $this -> paciente_model -> obtenerPacienteConsulta($d['atencion']['id_paciente']);
		$d['tercero'] = $this -> paciente_model -> obtenerTercero($d['paciente']['id_tercero']);
		$d['ultima_nota'] = $this -> enfermeria_model -> obtenerUltimaNota($id_atencion);
		//Codificación de la sala de espera según el servicio
		$d['notas'] = $this -> enfermeria_model -> obtenerSvNotas($id_atencion,$d['atencion']['id_servicio']);	
	 
		
		$id_serv = $d['atencion']['id_servicio'];
		
		$d['urlRegresar'] 	= site_url('/urg/observacion/main/'.$id_atencion);
		
		
		$data_1 = array();
		$data_2 = array();
		$data_3 = array();
		$data_4 = array();
		$data_5 = array();	
		$data_6 = array();		
			
			if ($d['notas']!=0){
			foreach ($d['notas'] as $item){
			$data_1[] = $item['ten_arterial_s'];
			$data_2[] = $item['ten_arterial_d'];
			$data_3[] = $item['temperatura'];
			$data_4[] = $item['pulso'];
			$data_5[] = $item['fecha_nota'];
			$data_6[] = $item['spo2'];		
			}
			}
$this->graph->title( 'Signos Vitales', '{font-size: 20px; color: #736AFF}' );

// we add 3 sets of data:
$this->graph->set_data( $data_1 );
$this->graph->set_data( $data_2 );
$this->graph->set_data( $data_3 );
$this->graph->set_data( $data_4 );
$this->graph->set_data( $data_6 );


// we add the 3 line types and key labels
$this->graph->line_hollow( 2, 4, '0x80a033', 'P_SISTOLICA', 10 );
$this->graph->line_hollow( 2, 4, '0x80a033', 'P_DISTOLICA', 10 );

$this->graph->line_dot( 3, 5, '0xCC0000', 'TEMPERATURA', 10); 
$this->graph->line( 2, '0xCC3399', 'PULSO', 10); 
$this->graph->line_hollow( 2,5, '0x000066', 'SPO2', 10); 
   // <-- 3px thick + dots

$this->graph->set_x_labels($data_5 );
$this->graph->set_x_label_style( 10, '0x000000', 0, 2 );

$this->graph->set_y_max( 300 );
$this->graph->set_y_min( 0 );
$this->graph->y_label_steps( 10 );
$this->graph->set_y_legend( 'Opus Libertati', 12, '#736AFF' );
$this->graph->set_output_type('js');


		//-----------------------------------------------------------
		$this->load->view('core/core_inicio');
		$this -> load -> view('urg/urg_svListadoM', $d);
		$this->load->view('core/core_fin');
		//----------------------------------------------------------
		}	
///////////////////////////////////////////////////////////////////
		/* 
* @Descripcion: Permite consultar los signos vitales para los efectos.
* @author Diego Ivan Carvajal Gil <dcarvajal@opuslibertati.org>
* @author http://www.opuslibertati.org
* @copyright	GNU GPL 3.0
* @since		20111115
* @version		20111115
*/
	
function consultarSvEfecto($id_atencion)
	{
		//----------------------------------------------------------
		$d = array();
		$d['id_atencion'] = $id_atencion;
		$d['atencion'] = $this -> urgencias_model -> obtenerAtencion($id_atencion);
		$d['paciente'] = $this -> paciente_model -> obtenerPacienteConsulta($d['atencion']['id_paciente']);
		$d['tercero'] = $this -> paciente_model -> obtenerTercero($d['paciente']['id_tercero']);
		$d['ultima_nota'] = $this -> enfermeria_model -> obtenerUltimaNota($id_atencion);
		//Codificación de la sala de espera según el servicio
		$d['notas'] = $this -> enfermeria_model -> obtenerSvNotas($id_atencion,$d['atencion']['id_servicio']);	
	 
		
		$id_serv = $d['atencion']['id_servicio'];
		
		$d['urlRegresar'] 	= site_url('/urg/observacion/main/'.$id_atencion);
		
		
		$data_1 = array();
		$data_2 = array();
		$data_3 = array();
		$data_4 = array();
		$data_5 = array();	
		$data_6 = array();		
			
			if ($d['notas']!=0){
			foreach ($d['notas'] as $item){
			$data_1[] = $item['ten_arterial_s'];
			$data_2[] = $item['ten_arterial_d'];
			$data_3[] = $item['temperatura'];
			$data_4[] = $item['pulso'];
			$data_5[] = $item['fecha_nota'];
			$data_6[] = $item['spo2'];		
			}
			}
$this->graph->title( 'Signos Vitales', '{font-size: 20px; color: #736AFF}' );

// we add 3 sets of data:
$this->graph->set_data( $data_1 );
$this->graph->set_data( $data_2 );
$this->graph->set_data( $data_3 );
$this->graph->set_data( $data_4 );
$this->graph->set_data( $data_6 );


// we add the 3 line types and key labels
$this->graph->line_hollow( 2, 4, '0x80a033', 'P_SISTOLICA', 10 );
$this->graph->line_hollow( 2, 4, '0x80a033', 'P_DISTOLICA', 10 );

$this->graph->line_dot( 3, 5, '0xCC0000', 'TEMPERATURA', 10); 
$this->graph->line( 2, '0xCC3399', 'PULSO', 10); 
$this->graph->line_hollow( 2,5, '0x000066', 'SPO2', 10); 
   // <-- 3px thick + dots

$this->graph->set_x_labels($data_5 );
$this->graph->set_x_label_style( 10, '0x000000', 0, 2 );

$this->graph->set_y_max( 300 );
$this->graph->set_y_min( 0 );
$this->graph->y_label_steps( 10 );
$this->graph->set_y_legend( 'Opus Libertati', 12, '#736AFF' );
$this->graph->set_output_type('js');


		//-----------------------------------------------------------
		$this->load->view('core/efecto_inicio');
		$this -> load -> view('urg/urg_svListadoEfecto', $d);
		$this->load->view('core/core_fin');
		//----------------------------------------------------------
		}	
///////////////////////////////////////////////////////////////////
}
?>
