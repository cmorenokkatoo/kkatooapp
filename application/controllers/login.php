<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * Index Page for this controller.
	 * Carga el Login de Kkatoo por defecto
	 */
	public function index()
	{
		if ($this->uri->segment(3) != '' AND is_numeric($this->uri->segment(3))){

			//Llama el modelo de usuarios para validar la existencia del usuario
			$this->load->model('user_model');
			$userInfo = $this->user_model->login_by_userid($this->uri->segment(3));

				//Inicio de sesión de usuario
			$data = array(
							'email'		=>$userInfo->email,
							'fullname'	=>$userInfo->fullname,
							'user_id'	=>$userInfo->id,
							'credits'	=>$userInfo->credits
						);
			$this->user_model->init_session($data);

			$retorno =$this->input->get('rtrn');
			if(empty($retorno))
			{
				redirect('campaign');
			}
			else
			{
				//http://kka.to/payment?prtrn=apps/pymesplus
				//'payment?prtrn=apps/'.$retorno
				redirect('landing/'.$retorno);
			}
			die();
		}
		else
		{
			if ($this->_login_in()) 
			{
				if($this->_check_special()){
					redirect('apps/'.$this->specialapp->get('uri'));
					die();
				}
				redirect('marketplace','refresh');
			}
			if($this->input->get('rtrn')!==FALSE)
			{
				$this->session->set_flashdata('retorno',$this->input->get('rtrn'));
			}
			$this->load->library( 'ci_google' );
			$data['google']	=	$this->ci_google->get_url_connect();
			$this->lang->load('login');
			$this->load->view('login',$data);
			log_message('debug', "Cargando el index del Login");
		}
		$this->output->cache(1);
	}
	 /**
	 * Funcion para cargar la vista de registro de usuario
	 */
	public function register()
	{
		if ($this->_login_in()) 
		{
			if($this->_check_special()){
				redirect('apps/'.$this->specialapp->get('uri'));
				die();
			}
			redirect('marketplace','refresh');
		}
		$this->load->library( 'ci_google' );
		$this->load->model('apps_model');
		$country		= $this->apps_model->get_country();
		$data['google']	=	$this->ci_google->get_url_connect();
		$data['country']=	$country;
		$this->lang->load('login');
		$this->load->view('register',$data);
		log_message('debug', "Cargando el formulario de registro de usuario");
	}
	/**
	 * Funcion para cargar la vista de registro de usuario
	 */
	public function forgot()
	{
		if ($this->_login_in()) 
		{
			if($this->_check_special()){
				redirect('apps/'.$this->specialapp->get('uri'));
				die();
			}
			redirect('marketplace','refresh');
		}
		$this->lang->load('login');
		$this->load->view('forgot');
		log_message('debug', "Cargando el formulario de recuperación de contraseña");
	}
	/**
	 * Funcion para cerrar la sessión del usuario
	 */
	public function logout()
	{
		$this->session->sess_destroy();
		if($this->_check_special()){
			redirect('login/login', 'refresh');
			die();
		}
		redirect('site','refresh');
		log_message('debug', "Session ha sido cerrada");
	}

	/**
	 * Funcion para realizar el registro de usuario por el metodo normal
	 */
	public function add_register()
	{
	
		if ($this->_login_in()) 
		{
			if($this->_check_special()){
				redirect('apps/'.$this->specialapp->get('uri'));
				die();
			}
			redirect('marketplace','refresh');
		}
		$this->lang->load('login');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email_r', $this->lang->line('email'), 'trim|required|max_length[64]|valid_email|xss_clean|is_unique[user.email]');
		$this->form_validation->set_rules('password', $this->lang->line('password'), 'required|min_length[5]|xss_clean|max_length[30]');
		$this->form_validation->set_rules('fullname', $this->lang->line('fullname'), 'required|max_length[64]|xss_clean');
		$this->form_validation->set_rules('phone', $this->lang->line('phone'), 'max_length[12]|xss_clean|numeric');
		$this->form_validation->set_rules('indi_pais', $this->lang->line('indi_pais'), 'xss_clean|numeric');
		if($this->specialapp->get('uses_special_pines') == 1)
		{
			$this->form_validation->set_rules('pin', $this->lang->line('pin'), 'trim|required|xss_clean');
		}
		if ($this->form_validation->run() == FALSE)
		{
			$data['error']	=	validation_errors();
			//Carga la vista de nuevo para mostrar el error
			$this->load->model('apps_model');
			$data['country'] = $this->apps_model->get_country();
			
			$this->_view_register($data);
		}
		else
		{
			if(!$this->input->post('tyc')){
	      $data['error']	=	$this->lang->line('notyc');
				//Carga la vista de nuevo para mostrar el error
				$this->load->model('apps_model');
				$data['country'] = $this->apps_model->get_country();
				
				$this->_view_register($data);
	   	}else{
				$this->load->model('user_model');
				$user 	=	$this->user_model->login_in($this->input->post('email_r'),'');
				if($user!==FALSE)
				{	
					$this->load->model('apps_model');
					$data['country'] = $this->apps_model->get_country();
					$data['error'] 	=	$this->lang->line('emailexist');
					$this->_view_register($data);
				}
				else
				{
					$valid 			= 	TRUE;
					if($this->specialapp->get('uses_special_pines') == 1)
					{
						$id_app		=	$this->specialapp->get('id');
						$this->load->model('payment_model');
						$bResult	=	$this->payment_model->check_pin_landing($id_app, $this->input->post('pin'));
						if(empty($bResult))
						{
							$this->load->model('apps_model');
							$data['country'] = $this->apps_model->get_country();
							$data['error'] 	=	$this->lang->line('nopinvalidapp');
							$this->_view_register($data);
							$valid			=	FALSE;
						}
						else
						{
												
							$id_last	=	$this->user_model->insert_new_user(
																			$this->input->post('email_r'),
																			$this->input->post('password'),
																			$this->input->post('fullname'),
																			0,
																			$this->input->post('phone'),
																			$this->input->post('indi_pais'),
																			0
																		);
							$this->user_model->insert_new_user_app($id_last, $id_app);
							$this->payment_model->update_pin_used_user($id_last, $this->input->post('pin'), $id_app);
						}
					}
					else
					{
						
						//Se crea el nuevo usuario
						
						$id_last	=	$this->user_model->insert_new_user(
																			$this->input->post('email_r'),
																			$this->input->post('password'),
																			$this->input->post('fullname'),
																			0,
																			$this->input->post('phone'),
																			$this->input->post('indi_pais'),
																			1
																		);
					}
					if($valid == TRUE)
					{
						if(!empty($id_last))
						{
							
							$newdata	=	array(
													'fullname'	=>$this->input->post('fullname'),
													'email'		=>$this->input->post('email_r'),
													'user_id'	=>$id_last,
													'credits'	=>0
													);
							//Se inicia sessión del nuevo usuario
							//$this->user_model->init_session($newdata);
							//Se confirma si el token de confirmación se generó
							$token 	=	$this->user_model->new_token();
							if(!empty($token))
							{
								//Agrega el nuevo token para la confirmación del usuario
								$id_new_token	= 	$this->user_model->insert_new_token($id_last, $token,KT_NEW_REGISTER_TOKEN);
								if(!empty($id_new_token))
								{
									//Envio de Correo de confirmación del usuario
									// Agregar Echo para ver el error del mail
									 $this->user_model->email_verify_account(
																		$this->input->post('email_r'),
																		$this->input->post('fullname'),
																		$token
																	);
									//Se envia mensaje de activación para el usuario por flashdata
									$this->session->set_flashdata('exitoso',$this->lang->line('activation'));
									log_message('debug', "Usuario creado con Exito");
									redirect('login/login','refresh');
								}	
							}
						}
						else
						{
							$this->load->model('apps_model');
							$data['country'] = $this->apps_model->get_country();
							$data['error'] 	=	$this->lang->line('errorregister');
							$this->_view_register($data);
							log_message('debug', "Error al crear un nuevo usuario");
						}	
					}
				}
			}
		}
	}
	/**
	 * Comprueba q el usuario y contraseña del usuario son validos
	 */
	public function signin()
	{
		if ($this->_login_in()) 
		{
			if($this->_check_special()){
				redirect('apps/'.$this->specialapp->get('uri'));
				die();
			}
			redirect('marketplace','refresh');
		}
		$this->lang->load('login');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|xss_clean|max_length[100]');
		$this->form_validation->set_rules('password', $this->lang->line('password'), 'required|min_length[5]|xss_clean|max_length[100]');
		if ($this->form_validation->run() == FALSE)
		{
			$data['error']	=	validation_errors();
			//Carga la vista de nuevo para mostrar el error
			$this->_view_login($data);
		}
		else
		{
			//Llama el modelo de usuarios para validar la existencia del usuario
			$this->load->model('user_model');
			$user 	=	$this->user_model->login_in($this->input->post('email'),$this->input->post('password'));
			if(empty($user))
			{
				$data['error'] 	=	$this->lang->line('nouser');
				$this->_view_login($data);
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
							redirect('payment?prtrn=apps/'.$this->specialapp->get('uri'), 'refresh');
						}else{
							redirect('apps/'.$this->specialapp->get('uri'), 'refresh');
							//redirect('payment?prtrn=apps/'.$this->specialapp->get('uri'), 'refresh');
						}
						die();
					}
					redirect('marketplace','refresh');
				}
				else
				{
					redirect($retorno);
				}
				//TODO
				//AQUI VA EL REDIRECT AL MARKETPLACE
			}
		}
	}
	/**
	* Verifica el correo existe para realizar el cambio de password
	*/
	public function add_forgot()
	{
		if ($this->_login_in()) 
		{
			if($this->_check_special()){
				redirect('apps/'.$this->specialapp->get('uri'));
				die();
			}
			redirect('marketplace','refresh');
		}
		$this->lang->load('login');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|valid_email|xss_clean|max_length[64]');
		if ($this->form_validation->run() == FALSE)
		{
			$data['error']	=	validation_errors();
			//Carga la vista de nuevo para mostrar el error
			$this->_view_forgot($data);
		}
		else
		{
			$this->load->model('user_model');
			$user 	=	$this->user_model->user_by_mail($this->input->post('email'),'');
			if($user===FALSE)
			{	
				$data['error'] 	=	$this->lang->line('emailnoexist');
				$this->_view_forgot($data);
			}
			else
			{
				$token 	=	$this->user_model->new_token();
				if(!empty($token))
				{
					//Agrega el nuevo token para la confirmación del usuario
					$id_new_token	= 	$this->user_model->insert_new_token($user->id, $token,KT_RESET_PASSWORD_TOKEN);
					//echo var_dump($id_new_token);
					if(!empty($id_new_token))
					{
						//Envio de Correo de confirmación del usuario
						$this->user_model->password_reset_user(
															$user->email,
															$user->fullname,
															$token
														);
						//Se envia mensaje de activación para el usuario por flashdata
						$this->session->set_flashdata('exitoso',$this->lang->line('resetexit'));
						log_message('debug', "Código de confirmación para reset password exitoso");
						if($this->_check_special()){
							redirect('login/login', 'refresh');
							die();
						}
						redirect('site','refresh');
					}
				}
			}
		}
	}
		/**
	* Actualiza el nuevo password de los usuarios despues de la validación por tokens
	*/
	public function add_new_password()
	{
		$this->lang->load('login');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('password', $this->lang->line('password'), 'required|min_length[5]|xss_clean|matches[confirm_password]|max_length[30]');
		$this->form_validation->set_rules('confirm_password', $this->lang->line('confirm_password'), 'required|min_length[5]|xss_clean|max_length[30]');
		if ($this->form_validation->run() == FALSE)
		{
			$data['error']		=	validation_errors();
			$data['email']  	=	urldecode($this->input->post('email'));
			$data['token']  	=	$this->input->post('token');
			$data['user_id']	=	$this->input->post('user_id');
			$data['token_url']  =	$this->input->post('token_url');
			//Carga la vista de nuevo para mostrar el error
			$this->_view_new_pass($data);
		}
		else
		{
			$this->load->model('user_model');
			$user	=	$this->user_model->user_by_mail($this->input->post('email'));
			if(empty($user))
		    {
		    	//Error enviado por Flashdata indicando que el email no se encuentra registrado en el sistema
		    	$this->session->set_flashdata('error',$this->lang->line('noregister'));
		    	redirect('site');
		    }
		    else
		    {
		    	if($user->id==$this->input->post('user_id'))
		    	{
					$result	= $this->user_model->token_by_userid($user->id,$this->input->post('token'),KT_RESET_PASSWORD_TOKEN);
			    	if(empty($result))
			    	{
			    		//Error enviado por Flashdata para decir que el usuario ya activó su cuenta
			    		$this->session->set_flashdata('error',$this->lang->line('notoken'));
			    		redirect('site');
			    	}
			    	else
			    	{
			    		if($result->token == $this->input->post('token_url'))
			    		{
			    			$update	=	$this->user_model->update_new_password($user->id,$this->input->post('password'));
			    			if(!empty($update))
			    			{
			    				//Se elimina el token de confirmación del usuario
			    				$delete	=	$this->user_model->delete_token_by_userid($user->id,$this->input->post('token'),KT_RESET_PASSWORD_TOKEN);
			    				//Se envia mensaje de activación para el usuario por flashdata
								$this->session->set_flashdata('exitoso',$this->lang->line('new_pass_ok'));
								log_message('debug', "Actualización de usuario realizada correctamente");
								redirect('login','refresh');
			    			}
			    		}
			    		else
			    		{
			    			redirect('site');
			    		}
			    	}
		    	}
		    	else
		    	{
		    		$this->session->set_flashdata('error',$this->lang->line('no_permision'));
		    		redirect('site');
		    	}
		    }
		}
	}

	/**
	* crear un nuevo password al usuario
	*/
	public function new_password()
	{
		$email 	= urldecode ($this->uri->segment(3));
		$token 	= $this->uri->segment(4);
		if($email!==FALSE AND $token!==FALSE)
		{
			$this->load->helper('email');
			if (valid_email($email))
			{
				$this->load->model('user_model');
			    $this->lang->load('login');
			  	$user	=	$this->user_model->user_by_mail($email);
			   	if(empty($user))
			    {
			    	//Error enviado por Flashdata indicando que el email no se encuentra registrado en el sistema
			    	$this->session->set_flashdata('error',$this->lang->line('noregister'));
			    	redirect('site');
			    }
			    else
			    {
			    	$id 	= $user->id;
			    	
			    	$result	= $this->user_model->token_by_userid($id,$token,KT_RESET_PASSWORD_TOKEN);
			    	if(empty($result))
			    	{
			    		//Error enviado por Flashdata para decir que el usuario ya activó su cuenta
			    		$this->session->set_flashdata('error',$this->lang->line('notoken'));
			    		redirect('site');
			    	}
			    	else
			    	{

			    		$this->_view_new_pass($result);
			    		//echo var_dump($result);
			    		//Espera para hacer el new password
			    	}
			    }
			}
			else
			{
				redirect('site');
			}
		}
		else
		{
			redirect('site');
		}
	}
	/**
	* Verifica el nuevo token del usuario recien registrado y lo confirma
	*/
	public function verify_new()
	{
	ini_set('display_errors', 'on');
		$email 	= urldecode($this->uri->segment(3));
		$token 	= $this->uri->segment(4);
		if($email!==FALSE AND $token!==FALSE)
		{
			$this->load->helper('email');
			if (valid_email($email))
			{
			    $this->load->model('user_model');
			    $this->lang->load('login');
			  	$user	=	$this->user_model->user_by_mail($email);
			   	if(empty($user))
			    {
			    	//Error enviado por Flashdata indicando que el email no se encuentra registrado en el sistema
			    	$this->session->set_flashdata('error',$this->lang->line('noregister'));
			    	redirect('site');
			    }
			    else
			    {
			    	$id 	= $user->id;
			    	$result	=	$this->user_model->token_by_userid($id,$token,KT_NEW_REGISTER_TOKEN);
			    	if(empty($result))
			    	{
			    		//Error enviado por Flashdata para decir que el usuario ya activó su cuenta
			    		$this->session->set_flashdata('error',$this->lang->line('notoken'));
			    		redirect('site');
			    	}
			    	else
			    	{
			    		//Se elimina el token de confirmación del usuario
			    		$delete	=	$this->user_model->delete_token_by_userid($id,$token,KT_NEW_REGISTER_TOKEN);
			    		if(empty($delete))
			    		{
				    		//Error enviado por Flashdata para decir que no se pudo eliminar el token
				    		$this->session->set_flashdata('error',$this->lang->line('errortoken'));
				    		redirect('site');
			    		}
			    		else
			    		{
			    			$update	=	$this->user_model->set_verified_userid($email);
			    			if(empty($update))
			    			{
			    				//Error enviado por Flashdata para decir que NO se pudo actualizar la cuenta confirmada
					    		$this->session->set_flashdata('error',$this->lang->line('errortokenupdate'));
					    		redirect('site');
			    			}
			    			else
			    			{
			    				//Se envia mensaje de que la cuenta fue confirmada para el usuario por flashdata
								$this->session->set_flashdata('exitoso',$this->lang->line('accountconfirm'));
								redirect('login/login');
			    			}
			    		}
			    	}
			    }
			}
			else
			{
			    redirect('site');
			}
		}
		else
		{
			redirect('site');
		}
	}
	public function gpauth()
	{
		if ($this->_login_in()) 
		{
			if($this->_check_special()){
				redirect('apps/'.$this->specialapp->get('uri'));
				die();
			}
			redirect('marketplace','refresh');
		}
		$this->lang->load('login');
		$this->load->library( 'ci_google' );
		$usuario = $this->ci_google->get_user();
		if(empty($usuario))
		{
			$data['error'] 	=	$this->lang->line('errorgoogle');
			$this->_view_login($data);
		}
		else
		{
			$this->load->model('user_model');
			$user 	=	$this->user_model->user_by_mail($usuario['email']);
			if($user!==FALSE)
			{
				//Si el usuario ya se encuentra en el sistema simplemente se conecta				
				$newdata	=	array(
										'fullname'	=>$user->fullname,
										'email'		=>$user->email,
										'user_id'	=>$user->id,
										'credits'	=>$user->credits
									 );
				$this->user_model->init_session($newdata);
				$retorno = $this->session->flashdata('retorno'); 
				if(empty($retorno))
				{
					if($this->_check_special()){
						redirect('apps/'.$this->specialapp->get('uri'), 'refresh');
						die();
					}
					redirect('marketplace','refresh');
				}
				else
				{
					redirect($retorno);
				}
			}
			else
			{
				$pass_unico =	uniqid();
				$id_last	=	$this->user_model->insert_new_user(
																		$usuario['email'],
																		$pass_unico,
																		$usuario['nombre'],
																		1
																	);
				if(!empty($id_last))
				{
					$newdata	=	array(
											'fullname'	=>$usuario['nombre'],
											'email'		=>$usuario['email'],
											'user_id'	=>$id_last,
											'credits'	=>0
										 );
					$this->user_model->init_session($newdata);
					//Envio de Correo de confirmación del usuario
					$prueba	=	$this->user_model->email_welcome(
														$usuario['email'],
														$usuario['nombre'],
														''
													);
					log_message('debug', "Usuario Google creado con Exito");
					$retorno = $this->session->flashdata('retorno'); 
					if(empty($retorno))
					{
						if($this->_check_special()){
							$this->session->set_flashdata('exitoso',$this->lang->line('firstkedits'));
							redirect('payment', 'refresh');
							die();
						}
						redirect('marketplace','refresh');
					}
					else
					{
						redirect($retorno);
					}
				}
				else
				{
					$data['error'] 	=	$this->lang->line('errorregister');
					$this->_view_login($data);
					log_message('debug', "Error al crear un nuevo usuario");
				}
			}
		}
	}

	/**
	 * Funcion para que el usuario se registre con Facebook
	*/
	public function fbauth()
	{
		if ($this->_login_in()) 
		{
			if($this->_check_special()){
				redirect('apps/'.$this->specialapp->get('uri'));
				die();
			}
			redirect('marketplace','refresh');
		}
		$this->session->keep_flashdata('retorno');
		$this->lang->load('login');
		// Carga la Biblioteca de Facebook
		$this->load->library( 'ci_facebook' );
		// Define el alcance de la aplicación sobre permisos del usuario
		$scope 			= 'user_about_me,email';
		// Hacia donde va Facebook una vez termine de validar
		$current_url 	= base_url() . 'login/fbauth';
		// parámetros de login
		$login_params 	= array(	'scope' => $scope,
									'redirect_uri' => $current_url,
								);
		// Url de login
		$login_url 		= $this->ci_facebook->getLoginUrl( $login_params );
		// Revisa si el usuario a denegado el acceso o ha ocurrido un error
		if ( $this->input->get( 'error', TRUE ) !== FALSE )
		{
			$data['error'] 	=	$this->lang->line('errorfacebook');
			$this->_view_login($data);
		}
		$uid 			= $this->ci_facebook->getUser();
		if ($uid)
		{
			try
			{
				$user_profile 				= $this->ci_facebook->api( '/me' );
				$new_user_data 				= array();
				$new_user_data['email'] 	= $user_profile['email'];
				if ( isset( $user_profile['first_name'] ) && ! empty( $user_profile['first_name'] ) ) 
				{
					if ( isset( $user_profile['last_name'] ) && ! empty( $user_profile['last_name'] ) ) 
					{
						$new_user_data['fullname'] = $user_profile['first_name'] . ' ' . $user_profile['last_name'];
					} 
					else 
					{
						$new_user_data['fullname'] = $user_profile['first_name'];
					}
				} 
				else 
				{
					$new_user_data['fullname'] = $user_profile['username'];
				}
				//Se inicia la creación del nuevo usuario
				$this->load->model('user_model');
				$user 	=	$this->user_model->user_by_mail($new_user_data['email']);
				if($user!==FALSE)
				{		
					//Si el usuario ya se encuentra en el sistema simplemente se conecta				
					$newdata	=	array(
											'fullname'	=>$user->fullname,
											'email'		=>$user->email,
											'user_id'	=>$user->id,
											'credits'	=>$user->credits
										 );
					$this->user_model->init_session($newdata);
					$retorno = $this->session->flashdata('retorno'); 
					if(empty($retorno))
					{
						if($this->_check_special()){
							redirect('apps/'.$this->specialapp->get('uri'), 'refresh');
							die();
						}
						redirect('marketplace','refresh');
					}
					else
					{
						redirect($retorno);
					}
				}
				else
				{
					$pass_unico =	uniqid();
					$id_last	=	$this->user_model->insert_new_user(
																			$new_user_data['email'],
																			$pass_unico,
																			$new_user_data['fullname'],
																			1
																		);
					if(!empty($id_last))
					{
						$newdata	=	array(
												'fullname'	=>$new_user_data['fullname'],
												'email'		=>$new_user_data['email'],
												'user_id'	=>$id_last,
												'credits'	=>0
											 );
						$this->user_model->init_session($newdata);
						//Envio de Correo de confirmación del usuario
						$prueba	=	$this->user_model->email_welcome(
															$new_user_data['email'],
															$new_user_data['fullname'],
															''
														);
						log_message('debug', "Usuario facebook creado con Exito");
						$retorno = $this->session->flashdata('retorno'); 
						if(empty($retorno))
						{
							if($this->_check_special()){
								$this->session->set_flashdata('exitoso',$this->lang->line('firstkedits'));
								redirect('payment', 'refresh');
								die();
							}
							redirect('marketplace','refresh');
						}
						else
						{
							redirect($retorno);
						}
					}
					else
					{
						$data['error'] 	=	$this->lang->line('errorregister');
						$this->_view_login($data);
						log_message('debug', "Error al crear un nuevo usuario");
					}
				}

			}catch(FacebookApiException $e){
				if (stripos( $e->getMessage(), 'session has expired' ) === FALSE) 
				{
    				$data['error'] 	=	$this->lang->line('expirefacebook');
					$this->_view_login($data);
				} else {
					$this->_view_login($data=array());
				}
			}
		}
		else
		{
			redirect($login_url);
		}

	}
	/**
	 * Funcion privada para verificar el Login del usuario
	*/
	private function _login_in()
	{
		return $this->session->userdata('logged_in');
	}
	 /**
	 * Funcion privada para cargar la vista de errores del Login
	 */
	private function _view_login($data)
	{
		$this->lang->load('login');
		$this->load->library( 'ci_google' );
		$data['google']	=	$this->ci_google->get_url_connect();
		$this->load->view('login',$data);
	}
	/**
	 * Funcion privada para cargar la vista de errores del registro
	 */
	private function _view_register($data)
	{
		$this->lang->load('login');
		$this->load->library( 'ci_google' );
		$data['google']	=	$this->ci_google->get_url_connect();
		$this->load->view('register',$data);
	}
	/**
	 * Funcion privada para cargar la vista de errores de recuperar contraseña
	 */
	private function _view_forgot($data)
	{
		$this->lang->load('login');
		$this->load->view('forgot',$data);
	}
	/**
	 * Funcion privada para cargar la vista de errores del nuevo password
	 */
	private function _view_new_pass($data)
	{
		$this->lang->load('login');
		$this->load->view('new_password',$data);
	}
	
	/**
	* Verifica si es una aplicación especial
	*/
	private function _check_special(){
		return $this->specialapp->get('special');
	}
}