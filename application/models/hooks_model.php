<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hooks_Model extends CI_Model {

	public function get_user($userid = 0)
	{
		try
		{
			$this->db->select('credits');
			$this->db->where('id', $userid);
			$result = $this->db->get('user')->row();
			if(!empty($result))
			{
				return $result;
			}
			else
			{
				return FALSE;
			} 
		}catch(Updatenewpassword $e){
			log_message('debug','Error al actualizar el nuevo password del usuario');
			return FALSE;
		}
	}
}