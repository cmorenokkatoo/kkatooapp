<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	/**
	 * Index Page for this controller.
	 * Carga el Login de Kkatoo por defecto
	 */
	public function index(){
		ini_set('display_errors', 'on');		
		if(!$this->_login_in() || ($this->session->userdata("user_id")!=KKATOO_USER)){
			$this->lang->load('apps');
			$this->session->set_flashdata('error',$this->lang->line('initapp'));
			redirect('login/login');
			die();
		}
		
		$uri_app = $this->uri->segment(2);
		
		if($uri_app=='recents'){
			$subseccion = $this->uri->segment(3);
			if($subseccion == FALSE){
				$this->display_recents();
			}elseif($subseccion=='aproved'){
				$this->display_recents_aproved();
			}elseif($subseccion=='noaproved'){
				$this->display_recents_no_aproved();
			}
		}
		
		
	}
	
	private function display_recents(){
		$this->load->model("apps_model");
		$data = array();
		$data["apps"] = $this->apps_model->get_recent_apps();
		$this->load->view('admin/recent_app_list', $data); 
	}
	private function display_recents_aproved(){
		$this->load->model("apps_model");
		$data = array();
		$data["apps"] = $this->apps_model->get_recent_apps(1);
		$this->load->view('admin/recent_app_list', $data); 
	}
	private function display_recents_no_aproved(){
		$this->load->model("apps_model");
		$data = array();
		$data["apps"] = $this->apps_model->get_recent_apps(0);
		$this->load->view('admin/recent_app_list', $data); 
	}
	
	/**
	 * Funcion privada para verificar el Login del usuario
	*/
	private function _login_in()
	{
		return $this->session->userdata('logged_in');
	}
}