<?php
/*
###########################################################################
#Esta obra es distribuida bajo los términos de la licencia GPL Versión 3.0#
###########################################################################
*/
/*
 *TIX - http://www.tix.com.co
 *Proyecto: TIX CONSULTORIOS
 *Nobre: Coam_model
 *Tipo: modelo
 *Descripcion: Acceso a datos modulo de consulta ambulatoria
 *Autor: Carlos Andrés Jaramillo Patiño <cajaramillo@tix.com.co>
 *Fecha de creación: 06 de abril de 2012
 *Última fecha modificación: 14 de enero de 2013
*/
class Coam_model extends CI_Model 
{
////////////////////////////////////////////////////////////
function __construct()
{        
	parent::__construct();
	$this->load->database();
}
////////////////////////////////////////////////////////////
/*
* Crea la atencion del paciente mediante su admision
*
* @author Carlos Andrés Jaramillo Patiño <cjaramillo@opuslibertati.org>
* @author http://www.opuslibertati.org
* @copyright    GNU GPL 3.0
* @since		20120301
* @version		20120301
* @return		array[object]
*/
function crearAdmisionDb($d)
{
	$insert = array(
	'id_paciente' => $d['id_paciente'],
	'fecha_ingreso' => date('Y-m-d H:i:s'),
	'fecha_egreso' => '0000-00-00 00:00:00',
	'id_cita' => $d['id_cita'],
	'id_consultorio' => $d['id_consultorio'],
	'id_entidad' => $d['id_entidad'],
	'consulta' => 'NO',
	'admision' => 'NO',
	'id_estado' => '1',
	'activo'		=> 'SI',
	'fecha_modificacion' =>	date('Y-m-d H:i:s'),
	'id_usuario_admision' => $this->session->userdata('id_usuario'));
	$this->db->insert('coam_atencion',$insert);
	//----------------------------------------------------
	$id_atencion = $this->db->insert_id();
	//----------------------------------------------------
	$update = array('estado' => 'confirmada');
	$this->db->where('id_cita',$d['id_cita']);
	$this->db->update('coam_agenda_citas',$update);
	
	return $id_atencion;
}
////////////////////////////////////////////////////////////
function obtenerOrigenesAtencion()
{
	$result = $this->db->get('core_origen_atencion');
	return $result->result_array();
}
////////////////////////////////////////////////////////////
function obtenerConsultorios()
{
	$this->db->order_by('consultorio','asc');
	$result = $this->db->get('coam_consultorios');
	return $result->result_array();
}
///////////////////////////////////////////////////////////
function obtPacConsultorio($id_consultorio)
{
	$this->db->select('coam_atencion.id_atencion,
	coam_atencion.fecha_ingreso,
	coam_atencion.id_medico_consulta,
	coam_atencion.consulta,
	coam_atencion.id_consultorio,
	core_paciente.genero,
	core_tercero.primer_apellido,
	core_tercero.segundo_apellido,
	core_tercero.primer_nombre,
	core_tercero.segundo_nombre,
	core_tercero.numero_documento,
	core_tipo_documentos.tipo_documento,
	coam_estados_atencion.estado,
	core_tercero.fecha_nacimiento,
	coam_atencion.id_estado');
	$this->db->from('coam_atencion');
	$this->db->join('core_paciente','coam_atencion.id_paciente = core_paciente.id_paciente');
	$this->db->join('core_tercero','core_paciente.id_tercero = core_tercero.id_tercero');
	$this->db->join('core_tipo_documentos','core_tercero.id_tipo_documento = core_tipo_documentos.id_tipo_documento');
	$this->db->join('coam_estados_atencion','coam_atencion.id_estado = coam_estados_atencion.id_estado');
	$this->db->where('coam_atencion.id_consultorio',$id_consultorio);
	$this->db->where('coam_atencion.activo','SI');
	$this->db->order_by('fecha_ingreso','desc');   	
	$result = $this->db->get();
	return $result->result_array();
}
///////////////////////////////////////////////////////////
function obtenerAtencion($id_atencion)
{
	$this->db->select('coam_estados_atencion.estado,
	coam_atencion.id_atencion,
	coam_atencion.id_consultorio,
	coam_atencion.id_paciente,
	coam_atencion.fecha_ingreso,
	coam_atencion.fecha_egreso,
	coam_consultorios.consultorio,
	coam_atencion.consulta,
	coam_atencion.id_entidad,
	coam_atencion.id_medico_consulta,
	coam_atencion.id_entidad_pago,
	coam_atencion.admision,
	coam_atencion.observaciones_adm,
	coam_atencion.id_estado,
	coam_atencion.id_origen,
	coam_atencion.id_contrato,
	coam_atencion.id_causa_externa,
	coam_atencion.activo,
	core_causa_externa.causa_externa');
	$this->db->from('coam_atencion');
	$this->db->join('coam_estados_atencion','coam_atencion.id_estado = coam_estados_atencion.id_estado');
	$this->db->join('core_origen_atencion','coam_atencion.id_origen = core_origen_atencion.id_origen','LEFT');
  $this->db->join('core_causa_externa','coam_atencion.id_causa_externa = core_causa_externa.id_causa_externa','LEFT');
  $this->db->join('coam_consultorios','coam_atencion.id_consultorio = coam_consultorios.id_consultorio','LEFT');
	$this->db->where('id_atencion',$id_atencion);
	$result = $this->db->get();
	return $result->row_array();
}
///////////////////////////////////////////////////////////
function obtenerListaCausaExterna()
{
	$result = $this->db->get('core_causa_externa');
	return $res = $result -> result_array();
}
///////////////////////////////////////////////////////////
function consulta_ambulatoriaDb($d)
{
	$dat = array();
	$dat['error'] =	$error = false;
	$insert = array(
	'motivo_consulta' => $d['motivo_consulta'],
	'enfermedad_actual' => $d['enfermedad_actual'],
	'revicion_sistemas' => $d['revicion_sistemas'],
	'condiciones_generales' => $d['condiciones_generales'],
	'talla' => $d['talla'],
	'peso' => $d['peso'],
	'frecuencia_cardiaca' => $d['frecuencia_cardiaca'],
	'frecuencia_respiratoria' => $d['frecuencia_respiratoria'],
	'ten_arterial_s' => $d['ten_arterial_s'],
	'ten_arterial_d' => $d['ten_arterial_d'],
	'temperatura' => $d['temperatura'],
	'spo2' => $d['spo2'],
	'analisis' => $d['analisis'],
	'conducta' => $d['conducta'],
	'fecha_ini_consulta' => $d['fecha_ini_consulta'],
	'fecha_fin_consulta' => date('Y-m-d H:i:s'),
	'id_medico' => $d['id_medico'],
	'id_atencion' => $d['id_atencion'],
	'id_usuario' => $this->session->userdata('id_usuario'));
	$r = $this->db->insert('coam_nota_inicial',$insert);
	if($r != 1){
	$error = true;
	return $dat['error'] = $error;}
	$id_consulta = $this->db->insert_id();
	//----------------------------------------------------
	$insertAnt = array(
	'ant_patologicos' => $d['ant_patologicos'],
	'ant_famacologicos' => $d['ant_famacologicos'],
	'ant_toxicoalergicos' => $d['ant_toxicoalergicos'],
	'ant_quirurgicos' => $d['ant_quirurgicos'],
	'ant_familiares' => $d['ant_familiares'],
	'ant_ginecologicos' => $d['ant_ginecologicos'],
	'ant_otros' => $d['ant_otros'],
	'id_consulta' => $id_consulta);
	$this->db->insert('coam_nota_inicial_ant',$insertAnt);
	//----------------------------------------------------
	$insertExa = array(
	'exa_cabeza' => $d['exa_cabeza'],
	'exa_ojos' => $d['exa_ojos'],
	'exa_oral' => $d['exa_oral'],
	'exa_cuello' => $d['exa_cuello'],
	'exa_dorso' => $d['exa_dorso'],
	'exa_torax' => $d['exa_torax'],
	'exa_abdomen' => $d['exa_abdomen'],
	'exa_genito_urinario' => $d['exa_genito_urinario'],
	'exa_extremidades' => $d['exa_extremidades'],
	'exa_neurologico' => $d['exa_neurologico'],
	'exa_piel' => $d['exa_piel'],
	'exa_mental' => $d['exa_mental'],
	'id_consulta' => $id_consulta);
	$r = $this->db->insert('coam_nota_inicial_exa',$insertExa);
	//----------------------------------------------------
	if(count($d['dx']) > 0 && strlen($d['dx'][0]) > 0)
		{
			for($i=0;$i<count($d['dx']);$i++)
			{
				$insert = array(
					'id_diag' 		=> $d['dx'][$i],
					'orden_dx' 		=> $i,
					'tipo_dx' 		=> $d['tipo_dx'][$i],
					'id_consulta' 	=> $id_consulta );
				$this->db->insert('coam_nota_inicial_diag', $insert); 
			}
		}
	//----------------------------------------------------	
	$update = array('consulta' => 'SI',
	'id_medico_consulta' => $d['id_medico'],
	'id_estado' => '3',
	'id_causa_externa' => $d['id_causa_externa'],
	'id_finalidad' => $d['id_finalidad']);
	$this->db->where('id_atencion',$d['id_atencion']);
	$this->db->update('coam_atencion ',$update);
	//----------------------------------------------------
	return $dat;
}
////////////////////////////////////////////////////////////
function obtenerNotaInicial($id_atencion)
{
	$this->db->where('id_atencion',$id_atencion);
	$result = $this->db ->get('coam_nota_inicial');
	return $result->row_array();
}
////////////////////////////////////////////////////////////
function obtenerNotaInicial_ant($id_consulta)
{
	$this -> db -> where('id_consulta',$id_consulta);
	$result = $this -> db ->get('coam_nota_inicial_ant');
	return $result -> row_array();
}
////////////////////////////////////////////////////////////	
function obtenerNotaInicial_exa($id_consulta)
{
	$this -> db -> where('id_consulta',$id_consulta);
	$result = $this -> db ->get('coam_nota_inicial_exa');
	return $result -> row_array();
}
////////////////////////////////////////////////////////////
function obtenerDxConsulta($id_consulta)
{
	$this->db->select('coam_nota_inicial_diag.id_diag,
	coam_nota_inicial_diag.tipo_dx,
	coam_nota_inicial_diag.orden_dx,
	core_diag_item.diagnostico,
	coam_nota_inicial_diag.id_consulta');
	$this->db->from('coam_nota_inicial_diag');
	$this->db->join('core_diag_item','coam_nota_inicial_diag.id_diag = core_diag_item.id_diag');
	$this->db->where('id_consulta',$id_consulta);
	$result = $this->db->get();
	return  $result->result_array();
}
///////////////////////////////////////////////////////////
function cambiar_estado($id_atencion,$id_estado)
{
	$this->db->where('id_atencion',$id_atencion);
	$this->db->update('coam_atencion',array('id_estado' => $id_estado));	
}
///////////////////////////////////////////////////////////
function obtenerOrdenes($id_atencion)
{
	$this->db->select('core_tipo_medico.descripcion As tipo,
		core_especialidad.descripcion As esp,
		core_tercero.primer_apellido,
		core_tercero.segundo_apellido,
		core_tercero.primer_nombre,
		core_tercero.segundo_nombre,
		coam_ordenamiento.id_orden,
		coam_ordenamiento.fecha_creacion,
		coam_ordenamiento.id_atencion,
		coam_ordenamiento.id_medico');
	$this->db->from('coam_ordenamiento');
	$this->db->join('core_medico','coam_ordenamiento.id_medico = core_medico.id_medico');
	$this->db->join('core_tipo_medico','core_medico.id_tipo_medico = core_tipo_medico.id_tipo_medico');
	$this->db->join('core_especialidad','core_medico.id_especialidad = core_especialidad.id_especialidad');
	$this->db->join('core_tercero','core_medico.id_tercero = core_tercero.id_tercero');
	$this->db->where('coam_ordenamiento.id_atencion',$id_atencion);
	$this->db->order_by('coam_ordenamiento.fecha_creacion','DESC');
	$result = $this->db->get();
	$num = $result->num_rows();
	if($num == 0){
		return $num;
	}else{
		return $result->result_array();
	}
}
///////////////////////////////////////////////////////////
function crearOrdenDb($d)
{
	$dat = array();
	$dat['error'] =	$error = false;
	$insert = array(
	'fecha_ini_ord' => $d['fecha_ini_ord'],
	'fecha_creacion' => date('Y-m-d H:i:s'),
	'fecha_modificacion' => date('Y-m-d H:i:s'),
	'id_usuario' => $this -> session -> userdata('id_usuario'),
	'id_medico' => $d['id_medico'],
	'id_atencion' => $d['id_atencion']);
	$r = $this->db->insert('coam_ordenamiento',$insert);
	$dat['id_orden'] = $this->db->insert_id();
	if($r != 1){
		$error = true;
		return $dat['error'] = $error;}
	//----------------------------------------------------------
	if(count($d['atc']) > 0 && strlen($d['atc'][0]) > 0)
	{
		for($i=0;$i<count($d['atc']);$i++)
		{
			$insert = array(
				'atc' 		=> $d['atc'][$i],
				'dosis' 	=> $d['dosis'][$i],
				'id_unidad' => $d['id_unidad'][$i],
				'frecuencia'=> $d['frecuencia'][$i],
				'id_frecuencia'=> $d['id_frecuencia'][$i],
				'id_via'=> $d['id_via'][$i],
				'pos'	=> $d['pos'][$i],
				'observacionesMed'=> $d['observacionesMed'][$i],
				'id_orden' 		=> $dat['id_orden'] );
			$this->db->insert('coam_orde_medicamentos', $insert);
		}
	}
	//----------------------------------------------------------
	if(count($d['cups']) > 0 && strlen($d['cups'][0]) > 0)
	{
		for($i=0;$i<count($d['cups']);$i++)
		{ 
			$insert = array(
				'cups' 		=> $d['cups'][$i],
				'observacionesCups' => $d['observacionesCups'][$i],
				'cantidadCups' => $d['cantidadCups'][$i],
				'id_orden' 		=> $dat['id_orden'] );
			$this->db->insert('coam_orde_cups', $insert); 
		}
	}
	//----------------------------------------------------------
	return $dat;
}
///////////////////////////////////////////////////////////
function obtenerOrden($id_orden)
{
	$this->db->where('id_orden',$id_orden);
	$result = $this->db->get('coam_ordenamiento');
	return $result->row_array();
}
///////////////////////////////////////////////////////////
function obtenerCupsOrden($id_orden)
{
	$this->db->where('id_orden',$id_orden);
	$result = $this->db->get('coam_orde_cups');
	return $result->result_array();	
}
///////////////////////////////////////////////////////////
function obtenerMediOrden($id_orden)
{
	$this->db->where('id_orden',$id_orden);
	$result = $this->db->get('coam_orde_medicamentos');
	return $result->result_array();	
}
///////////////////////////////////////////////////////////
function remisionDb($d)
{
	$insert = array(
		'id_atencion' => $d['id_atencion'],
		'id_medico' => $d['id_medico'],
		'id_especialidad' => $d['id_especialidad'],
		'motivo_remision' => $d['motivo_remision'],
		'observacion' => $d['observacion'],
		'fecha_remision' => date('Y-m-d H:i:s')
	);
	$this->db->insert('coam_remision',$insert);
	return $this->db->insert_id();
}
///////////////////////////////////////////////////////////
function obtenerRemision($id_remision)
{
	$this->db->select('*');
	$this->db->from('coam_remision');
	$this->db->join('core_especialidad','coam_remision.id_especialidad = core_especialidad.id_especialidad');
	$this->db->where('id_remision',$id_remision);
	$res = $this->db->get();
	return $res->row_array();
}
///////////////////////////////////////////////////////////
function obtenerRemisiones($id_atencion)
{
		$this->db->select('*');
	$this->db->from('coam_remision');
	$this->db->join('core_especialidad','coam_remision.id_especialidad = core_especialidad.id_especialidad');
	$this->db->order_by('fecha_remision','DESC');
	$this->db->where('id_atencion',$id_atencion);
	$result = $this->db->get();
	$num = $result->num_rows();
	if($num == 0){
		return $num;
	}else{
		return $result->result_array();
	}	
}
///////////////////////////////////////////////////////////
function incapacidadDb($d)
{
	$insert = array(
		'id_atencion' => $d['id_atencion'],
		'id_medico' => $d['id_medico'],
		'fecha_inicio' => $d['fecha_inicio'],
		'duracion' => $d['duracion'],
		'observacion' => $d['observacion'],
		'fecha_incapacidad' => date('Y-m-d H:i:s')
	);
	$this->db->insert('coam_incapacidad',$insert);
	return $this->db->insert_id();
}
///////////////////////////////////////////////////////////
function obtenerIncapacidad($id_incapacidad)
{
	$this->db->where('id_incapacidad',$id_incapacidad);
	$res = $this->db->get('coam_incapacidad');
	return $res->row_array();
}
///////////////////////////////////////////////////////////
function obtenerIncapacidades($id_atencion)
{
	$this->db->where('id_atencion',$id_atencion);
	$this->db->order_by('fecha_incapacidad','DESC');
	$result = $this->db->get('coam_incapacidad');
	$num = $result->num_rows();
	if($num == 0){
		return $num;
	}else{
		return $result->result_array();
	}	
}
///////////////////////////////////////////////////////////
function finalizar_atencionDb($id_atencion)
{
	$update = array(
		'fecha_egreso' => date('Y-m-d H:i:s'),
		'id_estado' => '5',
		'activo' => 'NO',
		'fecha_modificacion' => date('Y-m-d H:i:s'));
	$this->db->where('id_atencion',$id_atencion);
	$this->db->update('coam_atencion',$update);	
}
///////////////////////////////////////////////////////////
function listadoTotalAtenciones()
{
	$this->db->select('coam_atencion.id_atencion,
	coam_atencion.fecha_ingreso,
	coam_atencion.id_medico_consulta,
	coam_atencion.consulta,
	coam_atencion.id_consultorio,
	core_paciente.genero,
	core_tercero.primer_apellido,
	core_tercero.segundo_apellido,
	core_tercero.primer_nombre,
	core_tercero.segundo_nombre,
	core_tercero.numero_documento,
	core_tipo_documentos.tipo_documento,
	coam_estados_atencion.estado,
	core_tercero.fecha_nacimiento,
	coam_atencion.id_estado,
	coam_consultorios.consultorio');
	$this->db->from('coam_atencion');
	$this->db->join('core_paciente','coam_atencion.id_paciente = core_paciente.id_paciente');
	$this->db->join('core_tercero','core_paciente.id_tercero = core_tercero.id_tercero');
	$this->db->join('core_tipo_documentos','core_tercero.id_tipo_documento = core_tipo_documentos.id_tipo_documento');
	$this->db->join('coam_estados_atencion','coam_atencion.id_estado = coam_estados_atencion.id_estado');
	$this->db->join('coam_consultorios','coam_atencion.id_consultorio = coam_consultorios.id_consultorio');
	$this->db->where('coam_atencion.activo','SI');
	$this->db->order_by('fecha_ingreso','desc');   	
	$result = $this->db->get();
	$num = $result->num_rows();
	if($num == 0){
		return $num;
	}else{
		return $result->result_array();
	}	
}
///////////////////////////////////////////////////////////
function editar_admisionDb($d)
{
	$update = array(
	'id_consultorio' => $d['id_consultorio'],
	'id_entidad' => $d['id_entidad'],
	'fecha_modificacion' =>	date('Y-m-d H:i:s'));
	$this->db->where('id_atencion',$d['id_atencion']);
	$this->db->update('coam_atencion',$update);
	//----------------------------------------------------
}
///////////////////////////////////////////////////////////
function retiroVoluntario($d)
{
	$insert = array(
		'id_atencion'	=> $d['id_atencion'], 
		'id_medico'		=> $d['id_medico'],
		'fecha_retiro'	=> date('Y-m-d H:i:s'),
		'id_usuario'	=> $this -> session -> userdata('id_usuario')
	);
	$this->db->insert('coam_retiro_voluntario',$insert);
	
	$update = array(
		'fecha_modificacion'=> date('Y-m-d H:i:s'),
		'id_estado'			=> '4',
		'activo'			=> 'NO',
		'fecha_egreso'		=> date('Y-m-d H:i:s')
		);
	$this->db->where('id_atencion',$d['id_atencion']);
	$this->db->update('coam_atencion',$update);
		
}
///////////////////////////////////////////////////////////
function no_respondeDB($id_atencion)
{	
	$update = array(
		'fecha_modificacion'=> date('Y-m-d H:i:s'),
		'id_estado'			=> '6',
		'activo'			=> 'NO',
		'fecha_egreso'		=> date('Y-m-d H:i:s')
		);
	$this->db->where('id_atencion',$id_atencion);
	$this->db->update('coam_atencion',$update);
		
}
///////////////////////////////////////////////////////////function obtenerListaCausaExterna()
function obtenerListaFinalidad()
{
	$result = $this->db->get('core_finalidad_consulta');
	return $res = $result -> result_array();
}

///////////////////////////////////////////////////////////
function obtenerConsultorio($id_consultorio)
{
	$this->db->where('id_consultorio',$id_consultorio);
	$res = $this->db->get('coam_consultorios');
	return $res->row_array();	
}
///////////////////////////////////////////////////////////
function agregar_dispoDB($d)
{
	$insert = array(
		'anno' => $d['anno'],
		'mes' => $d['mes'],
		'dia' => $d['dia'],
		'id_medico' => $d['id_medico'],
		'id_consultorio' => $d['id_consultorio'],
		'hora_inicio' => $d['hora_inicio'],
		'min_inicio' => $d['min_inicio'],
		'hora_fin' => $d['hora_fin'],
		'min_fin' => $d['min_fin'],
		'tiempo_consulta' => $d['tiempo_consulta'],
		'fecha_creacion' => date('Y-m-d H:i:s'),
		'id_usuario' => $this->session->userdata('id_usuario'));
	$this->db->insert('coam_agenda_dispoconsul',$insert);	
}
///////////////////////////////////////////////////////////
function validar_disponibilidadDB($d)
{
	$this->db->where('anno',$d['anno']);
	$this->db->where('mes',$d['mes']);
	$this->db->where('dia',$d['dia']);
	$this->db->where('id_consultorio',$d['id_consultorio']);
	$result = $this->db->get('coam_agenda_dispoconsul');
	$num = $result->num_rows();
	if($num == 0){
		return 'v';
	}else{
		if($num == 1){
			$res = $result->row_array();
			return $this->validar_disponibilidad_detalle($d,$res);	
		}else{
			$res = $result->result_array();
			$cont_f = 0;
			foreach($res as $data){
				$valor = $this->validar_disponibilidad_detalle($d,$data);
				if($valor == 'f'){
					$cont_f++;
				}
			}
			if($cont_f > 0){
				return 'f';
			}else{
				return 'v';
			}	
		}
	}
}
///////////////////////////////////////////////////////////
function validar_disponibilidad_detalle($d,$res)
{
	if($res['hora_inicio'] > $d['hora_inicio']){
		if($res['hora_inicio'] > $d['hora_fin']){
			return 'v';	
		}else if($res['hora_inicio'] == $d['hora_fin']){
			if($res['min_inicio'] >= $d['min_fin']){
				return 'v';		
			}else{
				return 'f';	
			}
		}else{
			return 'f';
		}
	}else if($res['hora_inicio'] == $d['hora_inicio']){
		if($res['hora_fin'] <= $d['hora_inicio']){
			
			if($res['min_fin'] <= $d['min_inicio']){
				return 'v';
			}else{
				return 'f';	
			}
		}else{
			return 'f';
		}		
	}else if($res['hora_inicio'] < $d['hora_inicio']){
		if($res['hora_fin'] <= $d['hora_inicio']){
			if($res['min_fin'] <= $d['min_inicio']){
				return 'v';
			}else{
				return 'f';
			}
		}else{
			return 'f';
		}
	}
}
///////////////////////////////////////////////////////////
function obtener_agenda_dia($dia,$mes,$anno,$id_consultorio)
{
	$this->db->select("CONCAT(core_tercero.primer_nombre,' ',
		core_tercero.segundo_nombre, ' ',
		core_tercero.primer_apellido, ' ',
		core_tercero.segundo_apellido) AS medico,
		core_especialidad.descripcion,
		coam_agenda_dispoconsul.id,
		coam_agenda_dispoconsul.hora_inicio,
		coam_agenda_dispoconsul.min_inicio,
		coam_agenda_dispoconsul.hora_fin,
		coam_agenda_dispoconsul.min_fin,
		coam_agenda_dispoconsul.tiempo_consulta",false);
	$this->db->from('coam_agenda_dispoconsul');
	$this->db->JOIN('core_medico','coam_agenda_dispoconsul.id_medico = core_medico.id_medico');
	$this->db->JOIN('core_tercero','core_medico.id_tercero = core_tercero.id_tercero');
	$this->db->JOIN('core_especialidad','core_medico.id_especialidad = core_especialidad.id_especialidad');
	$this->db->where('anno',$anno);
	$this->db->where('mes',$mes);
	$this->db->where('dia',$dia);
	$this->db->where('id_consultorio',$id_consultorio);
	$this->db->order_by('hora_inicio','ASC');
	$this->db->order_by('min_inicio','ASC');
	$result = $this->db->get();
	return $result->result_array();	
}
///////////////////////////////////////////////////////////
function verificar_obtener_agenda_dia($dia,$mes,$anno,$id_consultorio)
{
	$this->db->where('anno',$anno);
	$this->db->where('mes',$mes);
	$this->db->where('dia',$dia);
	$this->db->where('id_consultorio',$id_consultorio);
	$result = $this->db->get('coam_agenda_dispoconsul');
	$num = $result->num_rows();
	return $num;	
}
///////////////////////////////////////////////////////////
function eliminar_dispo($id)
{
	$this->db->where('id',$id);	
	 $this->db->delete('coam_agenda_dispoconsul');		
}
///////////////////////////////////////////////////////////
function obtenerAgendasFecha($dia,$mes,$anno)
{
	$this->db->select("CONCAT(core_tercero.primer_apellido, ' ',
		core_tercero.segundo_apellido) AS medico,
		core_especialidad.descripcion,
		coam_agenda_dispoconsul.id,
		coam_agenda_dispoconsul.id_medico,
		coam_agenda_dispoconsul.hora_inicio,
		coam_agenda_dispoconsul.min_inicio,
		coam_agenda_dispoconsul.hora_fin,
		coam_agenda_dispoconsul.min_fin,
		coam_agenda_dispoconsul.tiempo_consulta",false);
	$this->db->from('coam_agenda_dispoconsul');
	$this->db->JOIN('core_medico','coam_agenda_dispoconsul.id_medico = core_medico.id_medico');
	$this->db->JOIN('core_tercero','core_medico.id_tercero = core_tercero.id_tercero');
	$this->db->JOIN('core_especialidad','core_medico.id_especialidad = core_especialidad.id_especialidad');
	$this->db->where('anno',$anno);
	$this->db->where('mes',$mes);
	$this->db->where('dia',$dia);
	$this->db->order_by('hora_inicio','ASC');
	$this->db->order_by('min_inicio','ASC');
	$result = $this->db->get();
	$num = $result->num_rows();
	if($num == 0){
		return $num;
	}else{
		return $result->result_array();	
	}
}
///////////////////////////////////////////////////////////
function obtenerDisponibilidadDia($id)
{
	$this->db->select("CONCAT(core_tercero.primer_nombre,' ',
		core_tercero.segundo_nombre, ' ',
		core_tercero.primer_apellido, ' ',
		core_tercero.segundo_apellido) AS medico,
		core_especialidad.descripcion,
		coam_agenda_dispoconsul.id,
		coam_agenda_dispoconsul.id_medico,
		coam_agenda_dispoconsul.dia,
		coam_agenda_dispoconsul.mes,
		coam_agenda_dispoconsul.anno,
		coam_agenda_dispoconsul.id_consultorio,
		coam_agenda_dispoconsul.hora_inicio,
		coam_agenda_dispoconsul.min_inicio,
		coam_agenda_dispoconsul.hora_fin,
		coam_agenda_dispoconsul.min_fin,
		coam_agenda_dispoconsul.tiempo_consulta",false);
	$this->db->from('coam_agenda_dispoconsul');
	$this->db->JOIN('core_medico','coam_agenda_dispoconsul.id_medico = core_medico.id_medico');
	$this->db->JOIN('core_tercero','core_medico.id_tercero = core_tercero.id_tercero');
	$this->db->JOIN('core_especialidad','core_medico.id_especialidad = core_especialidad.id_especialidad');
	$this->db->where('id',$id);
	$result = $this->db->get();
	return $result->row_array();	
}
///////////////////////////////////////////////////////////
function agregarCitaDb($d)
{
	$insert = array(
		'id_agenda' =>	$d['id_agenda'],
		'hora' => $d['hora'],
		'id_tipo_documento' => $d['id_tipo_documento'],
		'primer_nombre' => $d['primer_nombre'],
		'segundo_nombre' => $d['segundo_nombre'],
		'primer_apellido' => $d['primer_apellido'],
		'segundo_apellido' => $d['segundo_apellido'],
		'numero_documento' => $d['numero_documento'],
		'id_entidad' => $d['id_entidad'],
		'estado' => 'asignada',
		'fecha_creacion' => date('Y-m-d H:i:s'),
		'id_usuario' => $this->session->userdata('id_usuario'),
	);
	$this->db->insert('coam_agenda_citas',$insert);
}
///////////////////////////////////////////////////////////
function obtener_cita_hora($id_agenda,$hora)
{
	$this->db->where('id_agenda',$id_agenda);
	$this->db->where('hora',$hora);
	$result = $this->db->get('coam_agenda_citas');	
	$num = $result->num_rows();
	if($num == 0){
		return $num;
	}else{
		return $result->row_array();	
	}
}
///////////////////////////////////////////////////////////
function obtener_estado_agenda($id_agenda)
{
	$this->db->where('id_agenda',$id_agenda);
	$result = $this->db->get('coam_agenda_citas');	
	$num = $result->num_rows();
	return $num;	
}
///////////////////////////////////////////////////////////
function obtenerCitasFecha($d)
{
	$this->db->SELECT(" 
		coam_consultorios.consultorio,
		concat(coam_agenda_citas.primer_nombre, ' ', coam_agenda_citas.segundo_nombre, ' ', coam_agenda_citas.primer_apellido, ' ', coam_agenda_citas.segundo_apellido) AS paciente,
		coam_agenda_citas.numero_documento,
		core_tipo_documentos.tipo_documento,
		coam_agenda_citas.estado,
		coam_agenda_citas.hora,
		coam_agenda_citas.id_cita,
		core_especialidad.descripcion,
		concat(core_tercero.primer_nombre, ' ', core_tercero.segundo_nombre, ' ', core_tercero.primer_apellido, ' ', core_tercero.segundo_apellido) AS medico,
		coam_agenda_dispoconsul.hora_inicio,
		coam_agenda_dispoconsul.min_inicio,
		coam_agenda_dispoconsul.hora_fin,
		coam_agenda_dispoconsul.min_fin,
		coam_agenda_dispoconsul.mes,
		coam_agenda_dispoconsul.dia,
		coam_agenda_dispoconsul.anno",false);
	$this->db->FROM("coam_agenda_citas");
	$this->db->JOIN("coam_agenda_dispoconsul","coam_agenda_citas.id_agenda = coam_agenda_dispoconsul.id");
	$this->db->JOIN("coam_consultorios","coam_agenda_dispoconsul.id_consultorio = coam_consultorios.id_consultorio");
	$this->db->JOIN("core_tipo_documentos","coam_agenda_citas.id_tipo_documento = core_tipo_documentos.id_tipo_documento");
	$this->db->JOIN("core_medico","coam_agenda_dispoconsul.id_medico = core_medico.id_medico");
	$this->db->JOIN("core_especialidad","core_medico.id_especialidad = core_especialidad.id_especialidad");
	$this->db->JOIN("core_tercero","core_medico.id_tercero = core_tercero.id_tercero");
	$this->db->where('coam_agenda_dispoconsul.mes',$d['mes']);
	$this->db->where('coam_agenda_dispoconsul.dia',$d['dia']);
	$this->db->where('coam_agenda_dispoconsul.anno',$d['anno']);
	$this->db->order_by('coam_agenda_citas.hora','ASC');
	$result = $this->db->get();
	$num = $result->num_rows();
	if($num == 0){
		return $num;
	}else{
		return $result->result_array();	
	}
}
///////////////////////////////////////////////////////////
function obtener_cita($id_cita)
{
	$this->db->where('id_cita',$id_cita);
	$this->db->from('coam_agenda_citas');
	$this->db->join('coam_agenda_dispoconsul','coam_agenda_citas.id_agenda = coam_agenda_dispoconsul.id');
	$result = $this->db->get();
	return $result->row_array();
}
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
}