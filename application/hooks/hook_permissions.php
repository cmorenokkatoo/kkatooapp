<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class HK_permissions {
	
	/**
	* Obtener los permisos de una aplicación por us id.
	* @param $id_wapp es el id de la aplicación
	*/

	function initialize() {
		$ci =& get_instance();
        $ci->load->model('apps_model');
		$ci->load->helper('cookie');
		if (!isset($this->CI->session)){
			$ci->load->library('session');
		}
		if($ci->input->cookie('mycookieid')){
			$ci->apps_model->create_permisions_list($ci->input->cookie('mycookieid'));
			$ci->apps_model->create_special_app_list($ci->input->cookie('mycookieid'));	
		}
	}
}