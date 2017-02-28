<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	/**
	 * Carga el Home de Kkatoo
	 */
	public function index()
	{
		$mycookieid = $this->input->cookie('mycookieid');
		if(!empty($mycookieid)){
			$this->input->set_cookie('id', 0, 0);
			redirect('site', 'refresh');
		}

		if ($this->_login_in())
		{
			redirect('marketplace','refresh');
		}
		$this->lang->load('home');
		#$this->load->view('home');
		$this->load->view('home');

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
			redirect('apps/'.$this->specialapp->get('uri'));
			die();
		}
	}

	public function main()
    {

    $this->load->view('phpinfo');

    }
}
