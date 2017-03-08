<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Campaign_Model extends CI_Model {

	/*
	Funcion para obtener la informaci�n de las campa�as que se encolaron
	*/
	public function get_campaign($user_id = 0)
	{

		$this->db->select('id, name, fecha, hora, minuto, gmt, tipo_sms');
		$this->db->where('user_id', $user_id);
		$this->db->where('state', 1);
		$result	=	$this->db->get('campaign')->result();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando de obtener informaci�n de la campa�a');
	}

	/*
	Funcion para saber una campa�a existe o no
	*/
	public function get_campaign_exist($user_id = 0, $id_campaign = 0)
	{

		$this->db->select('id, name, fecha, hora, minuto, gmt');
		$this->db->where('user_id', $user_id);
		$this->db->where('id', $id_campaign);
		$this->db->where('state', 1);
		$result	=	$this->db->get('campaign')->result();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando de obtener informaci�n de la campa�a');
	}/*
	Funcion para saber el estado de una campa�a en la cola
	*/
	public function get_count_queues_by_user_from_to_state($user_id = 0, $from= 0, $to = 0,$state = 0)
	{
		$this->db->select('count(id) count');
		
		/*
		Se agrega validacion para llamada con 0 segundos para que no sean tenidas encuenta en el calculo
		*/
		if(isset($state) && $state==3){
			$this->db->where('(seg_real  || minuto_real || hora_real) <>', 0 );
			
			
		}
		$this->db->where('user_id', $user_id);
		$this->db->where('fecha >=',$from);
		$this->db->where('fecha <=',$to);
		$this->db->where('state', $state);
		$result	=	$this->db->get('queues')->result();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando informaci�n de una cola');
	}
	/*
	Funcion para saber el estado de una campa�a en la cola
	*/
	public function get_count_queues_by_user_by_campain($user_id = 0, $from= 0, $to = 0,$id_campaign = null)
	{
		$this->db->select('cmp.name, count(queue.id) cantidad, queue.state, cmp.id');
		$this->db->join('campaign cmp','queue.id_campaign=cmp.id');
		$this->db->where('cmp.user_id',$user_id);
		$this->db->where('queue.fecha >=',$from);
		$this->db->where('queue.fecha <=',$to);
		$this->db->group_by('queue.state, cmp.name, cmp.id');
	
		
		$result	=	$this->db->get("queues queue")->result();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			
			return $result;
		}
		log_message('debug','Error tratando informaci�n consolidado por campana por usuario');
	}
	/*
	Funcion para saber el estado de una campa�a en la cola
	*/
	public function get_queues($user_id = 0, $id_campaign = 0, $state = 0)
	{
		$this->db->select('id');
		$this->db->where('user_id', $user_id);
		$this->db->where('id_campaign', $id_campaign);
		$this->db->where('state', $state);
		$result	=	$this->db->get('queues')->result();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando informaci�n de una cola');
	}
						/* API */
	/*
	Obtiene las colas en estado por rango de fecha || API
	*/
	public function get_colas($user_id = 0, $state = 0, $id_wapp)
	{
		$this->db->select('hora, minuto, text_speech, json, state, phone, marcado, state_real');
		$this->db->where('user_id', $user_id);
		$this->db->where('state', $state);
		$this->db->where('id_wapp', $id_wapp);
		$result	=	$this->db->get('queues')->result();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando informaci�n de una cola');
	}
	/*
	Funcion para saber el estado de una campa�a en la cola
	*/
	public function get_sum_queues($user_id = 0, $id_campaign = 0)
	{
		$this->db->select('SUM(price_real) as price');
		$this->db->where('user_id', $user_id);
		$this->db->where('id_campaign', $id_campaign);
		$this->db->group_by('id_campaign');
		$result	=	$this->db->get('queues')->row();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando informaci�n de una cola');
	}

	/*
	Funcion para ver la campa�a total
	*/
	public function get_campaign_queues($user_id = 0, $id_campaign = 0)
	{
		$this->db->select('contacts_user.name, queues.pais, queues.area, queues.phone, queues.state, queues.fecha as fecha_cola, queues.fecha_real, queues.hora_real, queues.minuto_real, queues.price_real, queues.marcado, queues.seg_real, queues.state_real', FALSE);
		$this->db->from('queues');
		$this->db->join('contacts_user', 'queues.id_contact = contacts_user.id');
		$this->db->where('queues.user_id', $user_id);
		$this->db->where('queues.id_campaign', $id_campaign);
		$this->db->order_by("queues.state", "asc");
		$result	=	$this->db->get()->result();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando informaci�n de una cola');
	}
	function get_contact_app_custom_field($user_id = 0, $id_app, $id_contact = 0){
		try
		{
			$this->db->select('fields.*, contact_fields.*');
			$this->db->from('contact_fields');
			$this->db->join('fields','fields.id_wapp = contact_fields.id_wapp','inner');
			$this->db->where('contact_fields.user_id', $user_id);
			$this->db->where('contact_fields.id_contact', $id_contact);
			$this->db->where('contact_fields.id_wapp', $id_app);
			$result	=	$this->db->get()->row();
			//print_r($this->db->last_query());

			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al traer los campos dinamicos de una aplicación y un contacto => get_contact_app_custom_field');
			return FALSE;
		}
	}


	public function get_campaign_pendi($user_id = 0, $id_campaign = 0)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('id_campaign', $id_campaign);
		$this->db->where_in('state', array(0,1));
		$result	=	$this->db->get('queues');
		return $result->num_rows();
	}

	public function sum_campaign_real($user_id = 0, $id_campaign = 0)
	{
		$this->db->select('SUM(price_real) AS price_real');
		$this->db->where('user_id', $user_id);
		$this->db->where('id_campaign', $id_campaign);
		$this->db->where_not_in('state', array(0,1));
		$result	=	$this->db->get('queues')->row();
		return $result;
	}

	public function get_call_reali($user_id = 0, $id_campaign = 0)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('id_campaign', $id_campaign);
		$this->db->where_not_in('state', array(0,1));
		$result	=	$this->db->get('queues');
		return $result->num_rows();
	}

	public function get_call_exitosa($user_id = 0, $id_campaign = 0)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('id_campaign', $id_campaign);
		$this->db->where_in('state', array(3,5));
		$result	=	$this->db->get('queues');
		return $result->num_rows();
	}

	public function get_call_marcados($user_id = 0, $id_campaign = 0)
	{
		// $marc = 1;
		$this->db->where('user_id', $user_id);
		$this->db->where('id_campaign', $id_campaign);
		$this->db->where('marcado >=', 1);
		$result	=	$this->db->get('queues');
		return $result->num_rows();
	}

	public function relaunch_call($id_campaign = 0)
	{
		$data = array('state' => 0);
		$this->db->where('id_campaign', $id_campaign);
		$this->db->where_not_in('state', array(0,1,3,5));
		$result =   $this->db->update('queues', $data);

	  if($this->db->affected_rows() > 0)
	  {
	   return TRUE;
	  }
	  return FALSE;
	}

	public function deleteCamp($arrayCamp, $user_id)
	 {
	 	$query = "DELETE FROM campaign WHERE ";

	 	//creo query para borrar datos
	 	foreach ($arrayCamp as $key => $value)
	 	{
	 		if(($key+1) < count($arrayCamp))
	 			$query .= " id=".$value." OR ";
	 		else
	 			$query .= " id=".$value." AND ";
	 	}
	 	$query .= " user_id = ".$user_id;

		$this->db->query($query);
		if($this->db->affected_rows() > 0)
		{
			return TRUE;
		}
		return FALSE;
	 }


	 // Funci�n para obtener la fecha (programada) de la campa�a

	 public function get_campaign_date()
	 {
	 	$this->db->select('fecha', $fecha);
	 	$this->db->where('user_id', $user_id);
	 	$this->db->where('id',$id);
	 	$this->db->get('campaign');
	 	return $fecha;
	 }

	 // Funci�n detalle de campa�a del api

	 public function get_detail_campaign($id_campaign)
	 {
	 	$this->db->select('id, phone, seg_real, marcado, fecha_real, hora_real, minuto_real, price_real, state_real');

	 	$this->db->where('id_campaign', $id_campaign);
	 	$result	=	$this->db->get('queues')->result();
	 	if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando de obtener informaci�n de la campa�a');
	 }
	 
	 
	 public function get_detail_calls_between_dates($user_id, $from, $to)
	 {
	 	$this->db->select('id id_call, id_campaign,id_wapp, phone, fecha_real, hora_real, minuto_real,seg_real, state_real');
		$this->db->where('fecha_real between \''.$from.'\' and \''.$to.'\'');
		$this->db->where('user_id ',$user_id);
	
	 	$result	=	$this->db->get('queues')->result();
		

	 	if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando de obtener informaci�n de la campa�a');
	 }



	 public function get_campaign_info($user_id = 0)
	 {
	 	$this->db->select('id, name, fecha, hora, minuto, gmt');
	 	$this->db->where('user_id', $user_id);
	 	$this->db->where('state', 1);
	 	$this->db->order_by('fecha','desc');
	 	$this->db->order_by('hora','desc');
	 	$this->db->order_by('minuto','desc');
	 	$result	=	$this->db->get('campaign')->result();

		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando de obtener informaci�n de la campa�a');

	 }
	 //
	 //
	 // NUEVAS FUNCIONES

	 public function count_call_exitosas($user_id = 0, $id_campaign = 0)
	 {
	 	$this->db->select('');
	 	$this->db->where('user_id', $user_id);
		$this->db->where('id_campaign', $id_campaign);
		// $this->db->where('state', array(3,5));
		$this->db->where_not_in('state', array(0,2,1,4,6));
		// $this->db->where_not_in('state',3);
		$result = $this->db->get('queues');

		return $result->num_rows();
	 }
	  public function count_call_pendientes($user_id = 0, $id_campaign = 0)
	 {
	 	$this->db->select('');
	 	$this->db->where('user_id', $user_id);
		$this->db->where('id_campaign', $id_campaign);
		// $this->db->where('state', array(3,5));
		$this->db->where_not_in('state', array(0,2,3,4,5,6));
		// $this->db->where_not_in('state',3);
		$result = $this->db->get('queues');

		return $result->num_rows();
	 }
	 public function count_call_preparadas($user_id = 0, $id_campaign = 0)
	 {
	 	$this->db->select('');
	 	$this->db->where('user_id', $user_id);
		$this->db->where('id_campaign', $id_campaign);
		// $this->db->where('state', array(3,5));
		$this->db->where_not_in('state', array(1,2,3,4,5,6));
		// $this->db->where_not_in('state',3);
		$result = $this->db->get('queues');
		return $result->num_rows();
	 }
	 public function count_call_total($user_id = 0, $id_campaign = 0)
	 {
	 	$this->db->select('');
	 	$this->db->where('user_id', $user_id);
		$this->db->where('id_campaign', $id_campaign);
		$result = $this->db->get('queues');

		return $result->num_rows();
	 }

}
