<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Campaign_Model extends CI_Model {

	/*
	Funcion para obtener la información de las campañas que se encolaron
	*/
	public function get_campaign($user_id = 0)
	{
		
		$this->db->select('id, name, fecha, hora, minuto, gmt');
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
		log_message('debug','Error tratando de obtener información de la campaña');
	}
	
	/*
	Funcion para saber una campaña existe o no
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
		log_message('debug','Error tratando de obtener información de la campaña');
	}
	/*
	Funcion para saber el estado de una campaña en la cola
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
		log_message('debug','Error tratando información de una cola');
	}
	/*
	Funcion para saber el estado de una campaña en la cola
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
		log_message('debug','Error tratando información de una cola');
	}
	/*
	Funcion para ver la campaña total 
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
		log_message('debug','Error tratando información de una cola');
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
		$this->db->select_sum('price_real');
		$this->db->where('user_id', $user_id);
		$this->db->where('id_campaign', $id_campaign);
		$this->db->where_not_in('state', array(0,1));
		$result	=	$this->db->get('queues')->row();
		//echo $this->db->last_query();
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
		$this->db->where('state', 3);
		$result	=	$this->db->get('queues');
		return $result->num_rows();
	}

	public function relaunch_call($id_campaign = 0)
	{
		$data = array('state' => 0);
		$this->db->where('id_campaign', $id_campaign);
		$this->db->where('state', 4);
		$result =   $this->db->update('queues', $data); 

	  if($this->db->affected_rows() > 0)
	  {
	   return TRUE;
	  } 
	  return FALSE;
	}
}