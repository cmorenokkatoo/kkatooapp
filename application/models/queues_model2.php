<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Queues2_Model extends CI_Model {
	
	public function campaign_ready()
	{
		try
		{	
			$this->db->select('user.credits, wapp.uses_special_pines, user_wapp.credits as app_credits, queues.*');
			$this->db->from('queues');
			$this->db->join('user', 'user.id = queues.user_id');
			$this->db->join('wapp', 'wapp.id = queues.id_wapp');
			$this->db->join('user_wapp', 'user_wapp.id_wapp = queues.id_wapp and user_wapp.id_user=queues.user_id', 'left');
			$this->db->where('queues.state',0);
			$this->db->where('queues.tipo_wapp !=',1);
			$this->db->order_by("queues.user_id", "desc"); 
			$result = $this->db->get()->result();
			/*echo $this->db->last_query();
			die();*/
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorCampaignReadyFecha $e){
			log_message('debug','Error al traer todas las campañas listas para encolar menor a la fecha actual');
			return FALSE;
		}
	}
	
	
	/** Obtiene los creditos de un usuario asociado a una aplicación **/
	
	function get_user_app_credits($id_user = '', $id_wapp = ''){
		try
		{
			$this->db->select('credits');
			$this->db->where('id_user', $id_user);
			$this->db->where('id_wapp', $id_wapp);
			$result = $this->db->get('user_wapp')->row();
			if($result){
				return $result->credits;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al tratar de consultar a un usuario asociado a una aplicación por difusión con pines');
			return FALSE;
		}
	}
	
	/**Obtiene los creditos de un usuario asociado a una aplicación**/
	
	function check_if_user_has_app_credits($id_user = '', $id_wapp = '', $amount=0){
		try
		{
			$this->db->select('credits');
			$this->db->where('id_user', $id_user);
			$this->db->where('id_wapp', $id_wapp);
			$this->db->where('CAST(user_wapp.credits AS DECIMAL(10,6)) >= CAST('.$amount.' AS DECIMAL(10,6))');
			$result = $this->db->get('user_wapp')->row();
			if($result){
				return TRUE;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al tratar de consultar a un usuario asociado a una aplicación por difusión con pines');
			return FALSE;
		}
	}
	
	public function campaign_ready_subscription()
	{
		try
		{	
			$this->db->select('user.credits,queues.*');
			$this->db->from('queues');
			$this->db->join('user', 'user.id = queues.user_id');
			$this->db->where('queues.state',0);
			$this->db->where('queues.tipo_wapp',1);
			$this->db->order_by("queues.user_id", "desc"); 
			$result = $this->db->get()->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorCampaignReadyFecha $e){
			log_message('debug','Error al traer todas las campañas listas para encolar menor a la fecha actual');
			return FALSE;
		}
	}
	
	public function state_queues($id, $state)
	{
		try
		{	
			$data = array(
							'state' => $state
							);
			$this->db->where('id_contact_campaign',$id);
			$this->db->update('queues', $data);
			if($this->db->affected_rows() > 0)
			{
				return TRUE;
			}
			return FALSE;
		}catch(ErrorCampaignReady $e){
			log_message('debug','Error al tratar de cambiar el estado');
			return FALSE;
		}
	}
	
	/**
	*	Cambia el estado de una cola envíada
	*/ 

	public function state_queues_ready($id = 0, $state = 0, $sec = 0, $state_r ="", $hora_r="", $minu_r="", $fecha_r="", $marcado="", $price_r =0)
	{
		try
		{	
			$data = array(
							'state' 		=> 	$state,
							'seg_real'		=> 	$sec,
							'state_real'	=>	$state_r,
							'hora_real'		=> 	$hora_r,
							'minuto_real'	=> 	$minu_r,
							'fecha_real'	=> 	$fecha_r,
							'marcado'		=> 	$marcado,
							'price_real'	=> 	$price_r
						);
			$this->db->where('id_contact_campaign',$id);
			$result = $this->db->update('queues', $data);
			return $result;
		}catch(ErrorStateQueuesReady $e){
			log_message('error','Error al tratar de cambiar el estado de una cola');
			return FALSE;
		}
	}
	
	/**
	* ACTUALIZA EL CRÉDITO DEL USUARIO
	*/
	public function update_credit_user($id='', $total='', $uses_special_pines=0, $id_app = '')
	{
		try
		{	
			$this->db->where('id_contact_campaign',$id);
			$result = $this->db->get('queues')->row();
			
			$has_credits_app = FALSE;
			if($uses_special_pines){
				$has_credits_app = $this->check_if_user_has_app_credits($result->user_id, $id_app, $total);
			}
			
			if($uses_special_pines == 1 && $has_credits_app){
				$this->db->set('updated', 'NOW()',FALSE);
				$this->db->set('credits', 'credits-'.$total,FALSE);
				$this->db->where('id_user',$result->user_id);
				$this->db->where('id_wapp',$id_app);
				$result1 = $this->db->update('user_wapp');
				
			}else{	
				$this->db->set('updated', 'NOW()',FALSE);
				$this->db->set('credits', 'credits-'.$total,FALSE);
				$this->db->where('id',$result->user_id);
				$result1 = $this->db->update('user');
			}
			return $result1;
		}catch(ErrorUpdateCreditUser $e){
			log_message('debug','Error al tratar de descontar el credito de un usuario');
			return FALSE;
		}
	}
	
	/**
	* SUMA CRÉDITO AL USUARIO GANADO EN LAS APLICACIÓNES y REALIZA EL LOG
	*/
	public function sum_register_credit_user($data)
	{
		try
		{
			
			$this->db->set('credits', 'credits+'.$data["valor"],FALSE);
			$this->db->where('id',$data["id_user_app_owner"]);
			$result1 = $this->db->update('user');
			
			$result	= $this->db->insert('user_earnings', $data);
			if($result)
			{
				return $this->db->insert_id();  
			}
			else
			{
				return FALSE;
			}
		}catch(Errorinicampigninicial $e){
			log_message('debug','Error al ingresar el registro de ganancias de un usuario => sum_register_credit_user');
			return FALSE;
		}
	}
	
	/**
	* SUMA CRÉDITO A KKATOO GANADO EN LAS APLICACIÓNES y REALIZA EL LOG
	*/
	public function sum_register_credit_kkatoo($data)
	{
		try
		{
			
			$this->db->set('credits', 'credits+'.$data["valor"],FALSE);
			$this->db->where('id',$data["id_user_app_owner"]);
			$result1 = $this->db->update('user');
			
			$result	= $this->db->insert('kkatoo_earnings', $data);
			if($result)
			{
				return $this->db->insert_id();  
			}
			else
			{
				return FALSE;
			}
		}catch(Errorinicampigninicial $e){
			log_message('debug','Error al ingresar el registro de ganancias de un usuario => sum_register_credit_user');
			return FALSE;
		}
	}
	
	/**
	* ACTUALIZA EL CRÉDITO DEL CONTACTO
	*/
	public function update_credit_contact($id_contact, $id_wapp, $total)
	{
		try
		{	
			$this->db->set('updated', 'NOW()',FALSE);
			$this->db->set('credits', 'credits-'.$total,FALSE);
			//$this->db->set('packages', 'packages-1',FALSE);
			$this->db->where('id_contact',$id_contact);
			$this->db->where('id_wapp',$id_wapp);
			$result1 = $this->db->update('contact_wapp');
			return $result1;
		}catch(ErrorUpdateCreditUser $e){
			log_message('debug','Error al traer de cambiar al descontar credito de un contacto => update_credit_contact');
			return FALSE;
		}
	}
	
	/*
	* 
	*/
	public function get_price_contact($number = 0)
	{
		try
		{
			//select * from price where '34663509353' RLIKE CONCAT('^',prefix) order by prefix desc
			$result = 	$this->db->query("select * from price where '".$number."' RLIKE CONCAT('^',prefix) order by prefix desc limit 1")->row();
			//echo $this->db->last_query();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				/*$this->load->model('payment_model');
				$dollar_today = $this->payment_model->get_current_dollar_to_cop();
				$result->valor = $result->valor * $dollar_today;*/
				return $result;
			}
		}catch(ErrorGetPrinceContact $e){
			log_message('debug','Error al tratar de saber el precio de una llamada');
			return FALSE;
		}
	}
	
	public function get_contact_user($id_campaign_contact = 0)
	{
		try
		{
			$this->db->select('pais, area, phone, id_wapp, tipo_wapp, id_contact, user_id, id_campaign, id');
			$this->db->where('state',1);
			$this->db->where('id_contact_campaign',$id_campaign_contact);
			$result	=	$this->db->get('queues')->row();
			if(!empty($result))
			{
				return $result;
			}
			return FALSE;
			
		}catch(ErrorGetContactUser $e){
			log_message('debug','Error al tratar de obtener información de un usuario');
			return FALSE;
		}
	}
	
	public function delete_session($time)
	{
		try
		{
			$this->db->where('last_activity <',$time);
			$this->db->or_where('ip_address', '97.74.64.117');
			$result	=	$this->db->delete('session');
			return $result;
			
		}catch(ErrorDeleteSession $e){
			log_message('debug','Error al tratar de obtener información de un usuario');
			return FALSE;
		}
	}
	
	public function get_value_price($id = 0)
	{
		try
		{
			$this->db->select('publisher'); 
			$this->db->where('id_contact_campaign',$id);
			$result	=	$this->db->get('queues')->row();
			if(!empty($result))
			{
				return $result;
			}
			return FALSE;
		}catch(ErrorGetPrice $e){
			return FALSE;
		}
	}
	
	/**
	* Optiene los datos de id de la aplicación y tipo de la aplicación con el id de la campana
	* @param $id_campaign id de la campana
	* @return objeto con los datos antes descritos.
	*/
	public function get_app_data_by_id_campaing($id_campaign=0){
		try
		{
			$this->db->select('wapp.id as id_wapp, wapp.tipo as tipo_wapp'); 
			$this->db->join('campaign', 'campaign.id_wapp = wapp.id');
			$this->db->where('campaign.id',$id_campaign);
			$result	=	$this->db->get('wapp')->row();
			
			if(!empty($result))
			{
				return $result;
			}
			return FALSE;
		}catch(ErrorGetPrice $e){
			return FALSE;
		}
	}
	
	/**
	* Optiene el id del usuario dueno de la aplicación
	* @param $id_app id de la aplicación
	* @return Id del usuario dueño de la aplicación
	*/
	public function get_user_owner_by_app_id($id_app=0){
		try
		{
			$this->db->select('wapp.user_id, wapp.uses_special_pines'); 
			$this->db->where('wapp.id',$id_app);
			$result	=	$this->db->get('wapp')->row();
			if(!empty($result))
			{
				return $result;
			}
			return FALSE;
		}catch(ErrorGetPrice $e){
			return FALSE;
		}
	}
}