<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
//	function __construct(){}
		
	/**
	 * Index Page for this controller.
	 * Carga el Login de Kkatoo por defecto
	 */
	public function index(){
		ini_set('display_errors', 'on');
		$this->lang->load('apps');
		$this->load->model('apps_model');

		if(!$this->_login_in()){
			//The Special app redirect
			$this->_return_to_special_url();

			$this->session->set_flashdata('error',$this->lang->line('initapp'));
			redirect('login/login');
		}
		else{
			$uri_app 	=	$this->uri->segment(2);
			if($uri_app == "apps"){
				$this->deny_marketplace();
				$this->_load_apps();
			}
		}
	}



	public function _load_apps(){
		$this->load->model('apps_model');
		$aplicaciones = $this->apps_model->get_app_data_by_user_id($this->session->userdata('user_id'));
		$data["apps"] = $aplicaciones;
		$this->load->view("app_manager", $data);
	}

	/**
	 * Funcion privada para verificar el Login del usuario
	*/
	private function _login_in()
	{
		return $this->session->userdata('logged_in');
	}

	/**
	* Verifica si es una aplicación especial
	*/
	private function _check_special(){
		return $this->specialapp->get('special');
	}

	/**
	* Retorna la apliación si es especial a la url especial de esta
	*/
	public function _return_to_special_url(){
		if($this->_check_special()){
			redirect('login/login');
			die();
		}
	}

	/**
	* Denega el acceso al marketplace por permisos de aplicación especial.
	*/
	function deny_marketplace(){
		$this->lang->load('marketplace');
		if($this->permissions->get('deny_marketplace')){
			$this->session->set_flashdata('error',$this->lang->line('deny_marketplace'));
			if($this->specialapp->get('special')){
				if($this->specialapp->get('url_landing')){
					redirect($this->specialapp->get('url_landing'));
				}else{
					redirect('landing/'.$this->specialapp->get('uri'));
				}
			}else{
				redirect('site');
			}
			die();
		}
	}
}
