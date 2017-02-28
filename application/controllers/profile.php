<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {
    
    public function index(){
        $this->load->model('payment_model');
        $user 		= 	$this->payment_model->get_user($this->session->userdata('user_id'));
        
        $data = array(
								'user' => $user
							);
        $this->_view_index_profile($data);
    }
    
    private function _view_index_profile($data)
	{
		$this->load->view('profile', $data);
	}
}