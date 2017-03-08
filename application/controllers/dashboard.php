<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function index(){

		$view_data = "Hola mundo";

		$this->_view_dashboard($view_data);
	}


	private function _view_dashboard($data){
		$this->load->view('dashboard',$data);
	}


}