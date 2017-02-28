<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Landing_Model extends CI_Model {
	/*
	Funcion para obtener los paises para landing
	*/
	public function get_country()
	{
		$this->db->select('*');
		$this->db->where('enabled', 1);
		$result	= $this->db->get('country')->result();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando de eliger los paises');
	}
	
	/**
	* update aplication aproved
	*/
	function update_app_aproved($status = 0, $id_app = '', $user_id=''){
		try{
			$data = array(
							'aproved'		=> $status
							);
			$this->db->where('id', $id_app);
			$this->db->where('user_id', $user_id);
			$this->db->update('wapp',$data);
			return $this->db->get_where('wapp', array('id'=>$id_app, 'user_id'=>$user_id))->row();
		}catch(Errorgetcategory $e){
			log_message('debug','Error al tratar de traer la uri para la aplicación');
			return FALSE;
		}
	}
}