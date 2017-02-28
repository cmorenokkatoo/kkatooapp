<?php  //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Logueo de Usuario
public function signin($email, $password)
	{
		if ($this->_login_in()) 
		{
			if($this->_check_special()){
				return base_url('apps/'.$this->specialapp->get('uri'));
				// die();
			}
			return base_url('marketplace'); // redirect o return marketplace: lista con las apps que tengan contenido (audios). (Responsive o lista XML)
		}
		$this->lang->load('login');
		// $this->load->library('form_validation');
		// $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|valid_email|xss_clean|max_length[64]');
		// $this->form_validation->set_rules('password', $this->lang->line('password'), 'required|min_length[5]|xss_clean|max_length[30]');
		if (!isset($email) or empty($email) or !isset($password) or  empty($password))
		{
			$mensajeError = array('code' => 0, 'msj' => "Los parámetros están incorrectos");
			return $mensajeError;
		}
		else
		{
			//Llama el modelo de usuarios para validar la existencia del usuario
			$this->load->model('user_model');
			$user 	=	$this->user_model->login_in($email, $password);
			if(empty($user))
			{	
				$mensajeError = array('code' => 0, 'msj' => $this->lang->line('nouser'));
				return $mensajeError;
			}
			else
			{
				//Inicio de la sessión del usuario..
				$data = array(
								'email'		=>$user->email,
								'fullname'	=>$user->fullname,
								'user_id'	=>$user->id,
								'credits'	=>$user->credits
							);
				$this->user_model->init_session($data);
				$retorno = $this->session->flashdata('retorno'); 
				if(empty($retorno))
				{
					if($this->_check_special()){
						
						if($user->first_time == 1){
							$this->user_model->user_first_time_update($user->id);
							$this->session->set_flashdata('exitoso',$this->lang->line('firstkedits'));
							return base_url('payment?prtrn=apps/'.$this->specialapp->get('uri'));
						}else{
							return base_url('apps/'.$this->specialapp->get('uri'));
						}
						
					}
					return base_url('marketplace');
				}
				else
				{
					return base_url($retorno);
				}
				//TODO
				//AQUI VA EL REDIRECT AL MARKETPLACE
			}
		}
	}


/**********************************************************************************************************/
	// FUNCIONES PRIVADAS

	private function _login_in()
	{
		return $this->session->userdata('logged_in');
	}

	private function _check_special(){
		return $this->specialapp->get('special');
	}

function test($email, $password){
	return 'email: '.$email.' pass: '.$password;
}
?>