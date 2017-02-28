<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appmanager extends CI_Controller {
	
	public function index($id='', $filter='', $op='%3D', $amount = 0)
	{
		//var_dump($filter);
		//$this->load->view('appmanager/appmanager');
				
		ini_set('display_errors', 'on');
		$this->lang->load('apps');
		$this->load->model('wizard_model');
		$this->load->model('apps_model');
		
		if($this->_login_in()){
			$id_app = (is_numeric($id))?$id:FALSE;
			if($id_app !== FALSE){
				$result 	=	$this->apps_model->get_app_data_by_id($id_app);
				if($result){
					if($this->check_if_can_use($result)){
						$this->_load_app_data($result, $filter, urldecode($op), $amount);
					}else{
						$this->session->set_flashdata('error',$this->lang->line('notpermitedappsubs'));
						redirect('marketplace');
					}
				}else{
					$this->session->set_flashdata('error',$this->lang->line('notpermitedappsubs'));
					redirect('marketplace');
				}
			}else{
				$wapp = $this->wizard_model->get_last_aproved_or_not_aproved_app($this->session->userdata('user_id'));
				if($wapp){
					redirect('appmanager/'.$wapp->id);	
				}else{
					
				}
			}
		}
		else{
			
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('notpermitedappsubs'));
			redirect('marketplace');
		}
	}
	
	/**
	* Cargar los datos de la aplicación para comenzar con la edición
	* @param $result son los datos básicos de la aplicación en la tabla wapp
	*/
	function _load_app_data($result, $filter='', $op='=', $amount=0){
		$this->load->model('apps_model');
		$this->load->model('wizard_model');
		$this->load->model('appmanager_model');
		
		$this->apps_model->create_permisions_list($result->id);
		//USER CREDITS
		$data['credits'] 	= $this->appmanager_model->get_user_credits($this->session->userdata('user_id'));
		
		// DATOS DE LA LIBRERÍA DE CONTENIDOS
		$data["audios"] 	= $this->wizard_model->get_library_audios_by_app($result->id, $this->session->userdata('user_id'));
		$data["records"] 	= $this->wizard_model->get_library_records_by_app($result->id, $this->session->userdata('user_id'));
		$data["texts"] 		= $this->wizard_model->get_library_texts_by_app($result->id, $this->session->userdata('user_id'));
		$data["library"]	= $this->wizard_model->get_library_content($result->id, $this->session->userdata('user_id'));
		$data['voice'] 	  	= $this->apps_model->get_voice();
		$data["dynamic"] 	= $this->wizard_model->get_dynamic_fields($result->id);
		
		//DATOS DE LA TABLA DE APLICACIÓN
		$data["app_data"] 		= $result;
		$data["load_subscribe"]	= FALSE;
		
		//DATOS DE PAGOS
		$data["get_user_earnings"] 				= $this->appmanager_model->get_user_earnings_by_app($result->id, $this->session->userdata('user_id'));
		$data["get_user_earnings_by_userid"] 	= $this->appmanager_model->get_user_earnings_by_userid($this->session->userdata('user_id'));


		
		
		//CARGAR TABLA DE PAGOS REDIMIDOS
		$data["get_redeemed_all"] = $this->appmanager_model->get_redeemed_all($this->session->userdata("user_id"));
		
		//DATOS DE SUSCRIPTORES
		if($result->tipo == 1){
			//ESTADISTICAS SUSCRIPTORES!
			//ultimas campanas operadas
			$data['last_worked_campaing_subs'] = $this->appmanager_model->get_last_campaigns_suscriptions($result->id, $this->session->userdata('user_id'));
			//Evolución de suscriptores de la aplicación
			$data["suscribers_evolution"] = $this->appmanager_model->suscribers_evolution($result->id);
			
			//cargar contactos de suscripción
			$operadores = array('>', '<', '=');
			//Cargar usuarios de pines dependiendo si hay filtro o no
			if($filter=='filter' && in_array($op, $operadores) && is_numeric($amount)){
				$data["suscribers"] = $this->appmanager_model->load_suscriptors_contacts_filter($result->id, true, $op, $amount);
				$data["load_subscribe"] = TRUE;
			}else{
				$data["suscribers"] = $this->appmanager_model->load_suscriptors_contacts_filter($result->id);
			}
			
		}elseif($result->tipo == 2){
			//ESTADISTICAS DIFUSION!
			//Uso de la aplicación
			$data["aplication_uses"] 		= $this->appmanager_model->aplication_uses($result->id);
			
			//Estadisticas botones grandes
			$data["nro_crated_campaigns"] 	= $this->appmanager_model->nro_crated_campaigns($result->id);
			$data["maden_calls"]		  	= $this->appmanager_model->maden_calls($result->id);
			$data["maden_sms"]		  	= $this->appmanager_model->maden_sms($result->id);
			// $data["user_registered_app"] 	= $this->appmanager_model->user_registered_app($result->id);
			
			//Contenido más usado
			$data['more_used_content']		= $this->appmanager_model->more_used_content($result->id, $this->session->userdata('user_id'));
			
			
			if($result->uses_special_pines == 1){
				$operadores = array('>', '<', '=');
				//Cargar usuarios de pines dependiendo si hay filtro o no
				if($filter=='filter' && in_array($op, $operadores) && is_numeric($amount)){
					$data["difusion_suscribers_pin"] = $this->appmanager_model->load_difusion_users_pines_filter($result->id, $op, $amount);
					$data["load_subscribe"] = TRUE;
				}else{
					$data["difusion_suscribers_pin"] = $this->appmanager_model->load_difusion_users_pines($result->id);
				}
			}else{
				//Cargar usuarios de la aplicación
				$operadores = array('>', '<', '=');
				//Cargar usuarios de pines dependiendo si hay filtro o no
				if($filter=='filter' && in_array($op, $operadores) && is_numeric($amount)){
					$data["uses"] = $this->appmanager_model->get_user_uses_app($result->id, true, $op, $amount);
					$data["load_subscribe"] = TRUE;
				}else{
					$data["uses"] = $this->appmanager_model->get_user_uses_app($result->id);
				}
			}
		}
		
		if($this->session->flashdata('load_screen') == 'suscribers' || $filter == 'reset') $data["load_subscribe"] = TRUE;
		$this->_view_appmanager($data);
	}

	
	/*
	Funcion privada cargar la vista de Wizard
	*/
	private function _view_appmanager($data = array())
	{
		$this->load->view('appmanager/appmanager', $data);
	}
	
	/**
	* Verifica si el usuario puede utilizar la aplicación
	*/
	function check_if_can_use($result){
		if(!empty($result->id)){
			if($this->_login_in()){
				return $this->check_if_owner($result->id);
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	
	/**
	* Funcióon para aplicar filtros a la lista de suscriptores por difusión y pin
	*/
	function apply_filter(){
		$this->load->library('form_validation');
		$this->load->model('appmanager_model');
		$this->lang->load('appmanager');
		
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('pleaselogin'));
			redirect('appmanager/'.$this->input->post('id_wapp'));
			die();
		}
		
		$this->form_validation->set_rules('cbo_operador_creditos', $this->lang->line('operador_creditos'), 'required|xss_clean|max_length[1]|trim');
		$this->form_validation->set_rules('id_wapp', 'Id aplicación', 'required|xss_clean|integer');
		$this->form_validation->set_rules('cantidad_creditos', $this->lang->line('cantidad_creditos'), 'required|xss_clean|max_length[10]|min_length[1]|numeric');
				
		if ($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('error',validation_errors());
			redirect('appmanager/'.$this->input->post('id_wapp'));
		}else{
			redirect('appmanager/'.$this->input->post('id_wapp').'/filter/'.urlencode($this->input->post('cbo_operador_creditos')).'/'.$this->input->post('cantidad_creditos'));
		}
	}
	
	/**
	* Función para agergar créditos a los suscriptos a la aplicación de difusión con pin
	*/
	function add_credits(){
		$this->load->library('form_validation');
		$this->load->model('appmanager_model');
		$this->lang->load('appmanager');
		
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('pleaselogin'));
			redirect('appmanager/'.$this->input->post('id_wapp'));
			die();
		}
		
		$this->form_validation->set_rules('input_subscriber[]', 'Suscriptores', 'required|xss_clean');
		$this->form_validation->set_rules('id_wapp', 'Id aplicación', 'required|xss_clean|integer');
		$this->form_validation->set_rules('recargar', $this->lang->line('cantidad_creditos'), 'required|xss_clean|max_length[10]|min_length[1]|numeric');
				
		if ($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('error',validation_errors());
			redirect('appmanager/'.$this->input->post('id_wapp'));
		}else{
			$id_wapp 		= $this->input->post('id_wapp');
			
			if(!$this->check_if_owner($id_wapp )){
				//The Special app redirect
				$this->_return_to_special_url();
				
				$this->session->set_flashdata('error',$this->lang->line('pleaselogin'));
				redirect('appmanager/'.$this->input->post('id_wapp'));
				die();
			}
			
			$app_type 		= $this->appmanager_model->get_app_type_and_special_pin($id_wapp);
			$users 			= $this->input->post('input_subscriber');
			$recargar 		= $this->input->post('recargar');
			$user_credits 	= $this->appmanager_model->get_user_credits($this->session->userdata('user_id'));
			//$user_credits  	= $user_credits->credits;
			if($user_credits > ($recargar*count($users))){
				foreach($users as $users){
					$success = false;
					if($app_type->tipo == 2 && $app_type->uses_special_pines == 1){
						$success = $this->appmanager_model->add_user_diffusion_pin_credits($user, $id_wapp, $recargar);
					}else if($app_type->tipo == 1){
						$success = $this->appmanager_model->add_contact_suscription_credits($users, $id_wapp, $recargar);
					}
					
					if($success){
						$this->appmanager_model->discount_user_credits($this->session->userdata('user_id'), $recargar);
					}
				}
				$this->session->set_flashdata('exitoso',$this->lang->line('recharge_done'));
				$this->session->set_flashdata('load_screen', 'suscribers');
				redirect('appmanager/'.$id_wapp);
			}else{
				$this->session->set_flashdata('error',$this->lang->line('notenoughmoney'));
				redirect('payment');	
			}
		}
	}
	
	/**
	* Retira un usuario suscrito a una aplicación que utilza pin
	*/
	function remove_user_from_diffusion_pin(){
		$this->load->library('form_validation');
		$this->load->model('appmanager_model');
		$this->lang->load('appmanager');
		
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('pleaselogin'));
			redirect('appmanager/'.$this->input->post('id_wapp'));
			die();
		}
		
		$this->form_validation->set_rules('input_subscriber[]', 'Suscriptores', 'required|xss_clean');
		$this->form_validation->set_rules('id_wapp', 'Id aplicación', 'required|xss_clean|integer');
						
		if ($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('error',validation_errors());
			redirect('appmanager/'.$this->input->post('id_wapp'));
		}else{
			$id_wapp 		= $this->input->post('id_wapp');
			
			if(!$this->check_if_owner($id_wapp )){
				//The Special app redirect
				$this->_return_to_special_url();
				
				$this->session->set_flashdata('error',$this->lang->line('pleaselogin'));
				redirect('appmanager/'.$this->input->post('id_wapp'));
				die();
			}
			
			$credits = 0;
			$users 	 = $this->input->post('input_subscriber');
			foreach($users as $user){
				$user_credit = $this->appmanager_model->get_user_credits_diffusion($user, $id_wapp);
				$data = array('state'=>0, 'credits'=>0);
				if($this->appmanager_model->update_data_user_app_difusion($user, $id_wapp, $data)){
					$credits+=$user_credit; //->credits;
				}
			}
			$this->appmanager_model->add_user_credits($this->session->userdata('user_id'), $credits);
			$this->session->set_flashdata('exitoso',$this->lang->line('user_remove_success'));
			$this->session->set_flashdata('load_screen', 'suscribers');
			redirect('appmanager/'.$id_wapp);
		}
	}
	
	/**
	* Función que permite agregar las ganancias a mis créditos, todo depende cual botón seleccione
	*/
	function add_earnings_to_my_credits(){
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('pleaselogin'));
			redirect('user/apps');
			die();
		}
		
		$id_wapp = $this->input->post('id_wapp', TRUE);
		if(!$this->check_if_owner($id_wapp )){
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('pleaselogin'));
			redirect('user/apps');
			die();
		}
		
		$is_sum_app 	= $this->input->post('sum_by_app');
		$is_sum_user 	= $this->input->post('sum_by_user');
		
		$this->load->model('appmanager_model');
		$this->lang->load('appmanager');
		if($is_sum_app){
			$app_earnings = $this->appmanager_model->get_user_earnings_by_app($id_wapp, $this->session->userdata('user_id'));
			if(!empty($app_earnings) && $app_earnings->cuenta >= MINIMUN_EARNINGS_TO_PAY){
				$data_ini = array(
								'id_user' => $this->session->userdata('user_id'),
								'tipo'	  => RA,
								'estado'  => 0	
							);
				$id_para_recarga = $this->appmanager_model->ini_pago_to_user($data_ini);
				if(!empty($id_para_recarga)){
					$ini_pay_publisher = $this->appmanager_model->update_pago_by_app($id_wapp, $this->session->userdata('user_id'), $id_para_recarga);
					if($ini_pay_publisher){
						$generate_payment = $this->appmanager_model->generate_payment($id_para_recarga);
						if($generate_payment){
							$this->session->set_flashdata('exitoso',$this->lang->line('recharged_done'));
							redirect('appmanager/'.$id_wapp);
							die();
						}else{
							$this->session->set_flashdata('error',$this->lang->line('cant_recharge_credits'));
							redirect('appmanager/'.$id_wapp);
							die();
						}
					}else{
						$this->session->set_flashdata('error',$this->lang->line('cant_recharge_credits'));
						redirect('appmanager/'.$id_wapp);
						die();
					}
				}else{
					$this->session->set_flashdata('error',$this->lang->line('cant_recharge_credits'));
					redirect('appmanager/'.$id_wapp);
					die();
				}
			}else{
				$this->session->set_flashdata('error',$this->lang->line('cant_recharge_credits'));
				redirect('appmanager/'.$id_wapp);
				die();
			}
		}
		elseif($is_sum_user){
			$user_earnings = $this->appmanager_model->get_user_earnings_by_userid($this->session->userdata('user_id'));
			if(!empty($user_earnings) && $user_earnings->cuenta >= MINIMUN_EARNINGS_TO_PAY){
				$data_ini = array(
								'id_user' => $this->session->userdata('user_id'),
								'tipo'	  => RU,
								'estado'  => 0	
							);
							
				$id_para_recarga = $this->appmanager_model->ini_pago_to_user($data_ini);
				if(!empty($id_para_recarga)){
					$ini_pay_publisher = $this->appmanager_model->update_pago_by_user($this->session->userdata('user_id'), $id_para_recarga);
					if($ini_pay_publisher){
						$generate_payment = $this->appmanager_model->generate_payment($id_para_recarga);
						if($generate_payment){
							$this->session->set_flashdata('exitoso',$this->lang->line('recharged_done'));
							redirect('appmanager/'.$id_wapp);
							die();
						}else{
							$this->session->set_flashdata('error',$this->lang->line('cant_recharge_credits'));
							redirect('appmanager/'.$id_wapp);
							die();
						}
					}else{
						$this->session->set_flashdata('error',$this->lang->line('cant_recharge_credits'));
						redirect('appmanager/'.$id_wapp);
						die();
					}
				}else{
					$this->session->set_flashdata('error',$this->lang->line('cant_recharge_credits'));
					redirect('appmanager/'.$id_wapp);
					die();
				}
			}else{
				$this->session->set_flashdata('error',$this->lang->line('cant_recharge_credits'));
				redirect('appmanager/'.$id_wapp);
				die();
			}
		}
	}
	
	function init_redeem_earnings_by_transaction(){
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('pleaselogin'));
			redirect('user/apps');
			die();
		}
		
		$id_wapp = $this->input->post('id_wapp', TRUE);
		if(!$this->check_if_owner($id_wapp )){
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('pleaselogin'));
			redirect('user/apps');
			die();
		}
		
		$this->lang->load('appmanager');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('bancos', $this->lang->line('entity'), 'required|xss_clean');
		$this->form_validation->set_rules('tipo_cuenta', $this->lang->line('account_type'), 'required|xss_clean');
		$this->form_validation->set_rules('num_cuenta', $this->lang->line('account_number'), 'required|xss_clean|min_length[5]|numeric');
		$this->form_validation->set_rules('type_redeem', $this->lang->line('redeem_amount'), 'required|xss_clean');
						
		if ($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('error',validation_errors());
			redirect('appmanager/'.$this->input->post('id_wapp'));
		}else{
			$this->load->model('appmanager_model');
			
			
			$banco 			= $this->input->post('bancos');
			$tipo_cuenta 	= $this->input->post('tipo_cuenta');
			$num_cuenta 	= $this->input->post('num_cuenta');
			$type_redeem	= $this->input->post('type_redeem');
			
			if($type_redeem=="sum_by_app"){
				
				$app_earnings = $this->appmanager_model->get_user_earnings_by_app($id_wapp, $this->session->userdata('user_id'));
				if(!empty($app_earnings) && $app_earnings->cuenta >= MINIMUN_EARNINGS_TO_PAY){
					$data_ini = array(
									'id_user' => $this->session->userdata('user_id'),
									'tipo'	  => TA,
									'estado'  => 0,
									'entidad' => $banco,
									'tipo_de_cuenta' => $tipo_cuenta,
									'nro_cuenta' => $num_cuenta
								);
					$id_para_recarga = $this->appmanager_model->ini_pago_to_user($data_ini);
					if(!empty($id_para_recarga)){
						$ini_pay_publisher = $this->appmanager_model->update_pago_by_app($id_wapp, $this->session->userdata('user_id'), $id_para_recarga);
						if($ini_pay_publisher){
							$generate_payment = $this->appmanager_model->generate_payment($id_para_recarga);
							if($generate_payment){
								$this->session->set_flashdata('exitoso',$this->lang->line('transaction_initiated'));
								redirect('appmanager/'.$id_wapp);
								die();
							}else{
								$this->session->set_flashdata('error',$this->lang->line('cant_made_transaction'));
								redirect('appmanager/'.$id_wapp);
								die();
							}
						}else{
							$this->session->set_flashdata('error',$this->lang->line('cant_made_transaction'));
							redirect('appmanager/'.$id_wapp);
							die();
						}
					}
				}else{
					$this->session->set_flashdata('error',$this->lang->line('cant_made_transaction'));
					redirect('appmanager/'.$id_wapp);
					die();
				}
				
			}elseif($type_redeem=="sum_by_user"){
				
				$user_earnings = $this->appmanager_model->get_user_earnings_by_userid($this->session->userdata('user_id'));
				if(!empty($user_earnings) && $user_earnings->cuenta >= MINIMUN_EARNINGS_TO_PAY){
					$data_ini = array(
									'id_user' => $this->session->userdata('user_id'),
									'tipo'	  => TU,
									'estado'  => 0,
									'entidad' => $banco,
									'tipo_de_cuenta' => $tipo_cuenta,
									'nro_cuenta' => $num_cuenta
								);
					$id_para_recarga = $this->appmanager_model->ini_pago_to_user($data_ini);
					if(!empty($id_para_recarga)){
						$ini_pay_publisher = $this->appmanager_model->update_pago_by_user($this->session->userdata('user_id'), $id_para_recarga);
						if($ini_pay_publisher){
							$generate_payment = $this->appmanager_model->generate_payment($id_para_recarga);
							if($generate_payment){
								$this->session->set_flashdata('exitoso',$this->lang->line('transaction_initiated'));
								redirect('appmanager/'.$id_wapp);
								die();
							}else{
								$this->session->set_flashdata('error',$this->lang->line('cant_made_transaction'));
								redirect('appmanager/'.$id_wapp);
								die();
							}
						}else{
							$this->session->set_flashdata('error',$this->lang->line('cant_made_transaction'));
							redirect('appmanager/'.$id_wapp);
							die();
						}
					}
				}else{
					$this->session->set_flashdata('error',$this->lang->line('cant_made_transaction'));
					redirect('appmanager/'.$id_wapp);
					die();
				}
				
			}else{
				$this->session->set_flashdata('error',$this->lang->line('cant_made_transaction'));
				redirect('appmanager/'.$id_wapp);
				die();
			}
		}
	}
	
	/**
	* Check if aplication is from user
	* @param $id_wapp id de la aplicación
	* @return TRUE or FALSE depending the relation.
	*/
	function check_if_owner($id_wapp=0){
		$this->load->model('apps_model');
		if($this->apps_model->check_app_user($id_wapp, $this->session->userdata('user_id'))){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	private function _login_in(){
		return $this->session->userdata('logged_in');
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
	* Verifica si es una aplicación especial
	*/
	private function _check_special(){
		return $this->specialapp->get('special');
	}

}
?>