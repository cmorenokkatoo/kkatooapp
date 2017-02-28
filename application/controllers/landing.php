<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Landing extends CI_Controller {
	var $_sub_thks = array("consignment", "paypal", "pin", "pagosonline");
	 /**
	 * Funcion publica para cargar el index del paso 1
	 */
	public function index(){
				
		$this->load->model('payment_model');
		//$this->payment_model->email_payment_paypal_subscribe('517eb0890c1b8');
		
		ini_set('display_errors', 'on');
		$this->lang->load('apps');
		$uri_app 	=	$this->uri->segment(2);
		if($uri_app !== FALSE){
		 	$this->load->model('apps_model');
			$result 	=	$this->apps_model->get_uri_app($uri_app);
			
			if(empty($result)){
				$this->session->set_flashdata('error',$this->lang->line('appnoexist'));
				redirect('marketplace');
				die();
			}
			else{
				
				if($result->aproved == 0 && $this->session->userdata('user_id') != KKATOO_USER ){
					$this->session->set_flashdata('error',$this->lang->line('appnoexist'));
					$this->input->set_cookie('id', 0, 0);
					redirect('marketplace');
					die();
				}
				
				//if( $this->session->userdata('user_id') != KKATOO_USER )
					$this->input->set_cookie('id', $result->id, '9000');
				//else
					//$this->input->set_cookie('id', 0, 0);
				
				//VERIFICA SI HAY PIN EN LA URL Y LA CREA SI LA HAY
				
				if($this->input->get('pin', TRUE)){
					$this->input->set_cookie('pin', $this->input->get('pin', TRUE), '450');
				}
				
				
				$uri = $this->uri->segment(3);
				if($uri===FALSE){
					if($result->tipo == 1){
						$this->_load_suscripcion($result);
					}else if($result->tipo == 2){
						$this->_load_difusion($result);
					}else if($result->tipo == 0){
						//$this->load_global();
					}
				}else{
					if($uri == "saved"){
						$this->_load_view_save_extra_data($result);
					}else if($uri == "thanks"){
						$where_comes 	=	$this->uri->segment(4);
						//echo $where_comes;
						if(in_array($where_comes, $this->_sub_thks)){
							$this->_load_view_thanks($result, $where_comes);
						}
					}
				}
			}
		}
	}
	
	/** 
	* Carga la aplicacion por suscripcion
	* @param $result informacion de la aplicacion
	*/
	private function _load_suscripcion($result){
		$this->load->model('contacts_model');
		$this->load->model('payment_model');
		$this->load->model('landing_model');
		
		$data_load = array();
		
		$data_load["tipo"] = 1;
		
		$data_load["app_data"] = $result;
		$data_load["country"] = $this->landing_model->get_country();
		if($this->_contact_logged()){
			$data_load["subscriber"] = TRUE;
			$contact_data = $this->contacts_model->get_contact_associated_to_app($this->session->userdata('id_contact'), $result->id);
			if($contact_data){
				$data_load["is_from_this_app"] = TRUE;
				$data_load["contact_data"] = $contact_data;
			}else{
				$data_load["contact_data"] = $this->contacts_model->get_contact_info($this->session->userdata('id_contact'));				$data_load["is_from_this_app"] = FALSE;
			}
		}else{
			$data_load["is_from_this_app"] = FALSE;
			$data_load["subscriber"] = FALSE;
		}
		
		/**
		* Datos de la aplicación para el usuario 1 de kkatoo
		*/
		if($this->session->userdata("user_id")==KKATOO_USER){
			$this->load->model("wizard_model");
			$data_load["owner"]		= $this->wizard_model->get_user_by_application($result->id);
			$data_load["audios"] 	= $this->wizard_model->get_library_audios_by_app($result->id);
			$data_load["records"] = $this->wizard_model->get_library_records_by_app($result->id);
			$data_load["texts"] 	= $this->wizard_model->get_library_texts_by_app($result->id);
			$data_load["library"]	= $this->wizard_model->get_library_content($result->id);
			$data_load["packages"]= $this->wizard_model->get_packages_app($result->id);
			$data_load["dynamic"] = $this->wizard_model->get_dynamic_fields($result->id);
			$data_load["category"]= $this->wizard_model->get_category();
		}
		
		
		$this->session->set_flashdata('url','landing/'.$result->uri);
		$this->_view_landing($data_load);
	}
	
	/** 
	* Carga la aplicacion por difusión
	* @param $result informacion de la aplicacion
	*/
	private function _load_difusion($result){
		$this->load->model('contacts_model');
		$this->load->model('landing_model');
		
		$data_load = array();
		
		$data_load["tipo"] = 2;
		
		$data_load["app_data"] = $result;
		$data_load["country"] = $this->landing_model->get_country();
		
		$data_load["is_from_this_app"] = FALSE;
		$data_load["subscriber"] = FALSE;
		
		/**
		* Datos de la aplicación para el usuario 1 de kkatoo
		*/
		if($this->session->userdata("user_id")==KKATOO_USER){
			$this->load->model("wizard_model");
			$data_load["owner"]		= $this->wizard_model->get_user_by_application($result->id);
			$data_load["audios"] 	= $this->wizard_model->get_library_audios_by_app($result->id);
			$data_load["records"] 	= $this->wizard_model->get_library_records_by_app($result->id);
			$data_load["texts"] 	= $this->wizard_model->get_library_texts_by_app($result->id);
			$data_load["library"]	= $this->wizard_model->get_library_content($result->id);
			$data_load["packages"]	= $this->wizard_model->get_packages_app($result->id);
			$data_load["dynamic"] 	= $this->wizard_model->get_dynamic_fields($result->id);
			$data_load["category"]	= $this->wizard_model->get_category();
		}
		
		$this->session->set_flashdata('url','landing/'.$result->uri);
		$this->_view_landing($data_load);
	}
	
	
	/**
	* Guarda la infromación de contacto por suscripción
	*/
	
	public function save_contact_suscription(){
		//$this->output->enable_profiler(TRUE);
		
		$this->lang->load('contacts');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('indi_pais', 	$this->lang->line('namecountry'), 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('phone', 		$this->lang->line('telephone'), 'required|xss_clean|numeric|min_length[5]|max_length[12]');
		$this->form_validation->set_rules('txt_mail_pp', 'Email', 'trim|required|max_length[64]|valid_email|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',validation_errors());
			redirect($this->session->flashdata('url'));
		}
		else
		{	
			//Se carga el modelo APPS para traer el code del pais que se llamará
			$this->load->model('apps_model');
			$this->load->model('contacts_model');
			$country	=	$this->apps_model->get_country_id($this->input->post('indi_pais'));
			
			$result = $this->contacts_model->get_contact_exists_by_phone_and_country(
							$this->input->post('phone'),
							$country->phonecode
						);
			if(empty($result)){
				$data 	=	array(
								'name' 		=> '',
								'email' 	=> '',
								'indi_pais' => $country->phonecode,
								'indi_area' => '',
								'phone' 	=> $this->input->post('phone'),
								'user_id' 	=> '',
								'from_subscription'	=> 1,
								'email'		=> $this->input->post('txt_mail_pp'),
								'email_payment'		=> $this->input->post('txt_mail_pp'),
								'country_payment' 	=> $country->id
							);
				$result	=	$this->apps_model->insert_contact($data);
			}
			if(!empty($result)){
				$update =	$this->contacts_model->update_contact_suscription(
															$result,
															array(
															'email_payment' => $this->input->post('txt_mail_pp'),
															'email' => $this->input->post('txt_mail_pp'),
															'country_payment' 	=> $country->id														)
														);
				
				$contact_app = $this->contacts_model->get_contact_associated_to_app($result, $this->input->post('app'));
				if(empty($contact_app)){
					$contact_app = $this->contacts_model->associate_contact_application($result, $this->input->post('app'));
					if(!empty($contact_app)){
						
						$contact_app = $this->contacts_model->get_contact_associated_to_app($result, $this->input->post('app'));					$this->contact_associated_init($contact_app, true);
					}
				}else{
					$this->contact_associated_init($contact_app, false);
				}
			}
		}
	}
	
	/**
	* Inicia el contacto asociado a la aplicación
	* @param $contact_app información del contacto asociado a la apalicación para la sessión
	* @param $new bandera para cargar o no el flash data si es un contacto nuevo o ya asociado
	*/
	private function contact_associated_init($contact_app, $new=false){
		$this->contacts_model->init_session_contact(
			array(
				'id_contact' => $contact_app->id,
				'indi_pais'  => $contact_app->indi_pais,
				'indi_area'  => $contact_app->indi_area,
				'phone' =>  $contact_app->phone,
				'id_app' => $contact_app->id_app,
				'uri' => $contact_app->uri,
				'subscriber' => TRUE
			)
		);
		if($new){
			$this->session->set_flashdata('exitoso',$this->lang->line('contactsuscribed'));
		}
		redirect("landing/".$contact_app->uri.'/saved');
	}
	
	/**
	* Guarda la información extra del contacto
	*/	
	public function save_contact_extra_data(){
		$this->load->model('apps_model');
		$this->load->model('contacts_model');
		
		if($this->_contact_logged()){
			$contact_app = $this->contacts_model->get_contact_associated_to_app($this->session->userdata('id_contact'), $this->input->post('app'));
			if($contact_app){
				$fields	=	$this->apps_model->get_fields($this->input->post('app'));
				//Se verifica si la aplicación tiene campos dinamicos para realizar el update	
				if(!empty($fields))
				{
					$item = array();
					foreach($fields as $fiel)
					{
						$id = (string) $fiel->name_fields;
						array_push($item,array(
												'id_fields' 	=> $fiel->id,
												'id_contact'	=> $this->session->userdata('id_contact'),
												'valor'			=> $this->input->post($id),
												'user_id'		=> '',
												'id_wapp'		=> $this->input->post('app')
												)
									);
					}
					$delete		= $this->contacts_model->delete_fields_contacts(
																					0,
																					$this->session->userdata('id_contact'),
																					$this->input->post('app')
																				);
					$batch		= $this->apps_model->insert_batch_contacts_fields($item);
				}
				
				
				//VERIFICA SI ES PAYPAL E INICIA PARTE DE PAGOS...
				
				switch($this->input->post('cbo_payment_pp')){
					case "paypal":
						$this->_ini_pay_suscribe();
					break;
					case "pagosonline":
						$this->_ini_pay_subscribe_pagosonline();
					break;
					case "pin":
						$this->_ini_pay_pin();
					break;
					case "consignacion":
						$this->_ini_pay_consignement();
					break;
				}
			}else{
				$this->lang->load('landing');
				$this->session->set_flashdata('error',$this->lang->line('notpermissions'));
				redirect("landing/".$this->apps_model->get_app_uri($this->input->post('app')));
			}
		}else{
			$this->lang->load('landing');
			$this->session->set_flashdata('error',$this->lang->line('notpermissions'));
			redirect("landing/".$this->apps_model->get_app_uri($this->input->post('app')));
		}
		
	}
	
	/**
	* Realiza la actualización de datos y carga gracias por consignación
	*/
	private function _ini_pay_consignement(){
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('txt_mail_pp', 'Email', 'trim|required|max_length[64]|valid_email|xss_clean');
		
		$this->form_validation->set_rules('txt_name_pp', $this->lang->line('fullname'), 'required|max_length[64]|xss_clean');
		if ($this->form_validation->run() == FALSE){
			
			$this->session->set_flashdata('error',validation_errors());
			redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/saved');
			
		}else{
			$update =	$this->contacts_model->update_contact_suscription(
																$this->session->userdata('id_contact'),
																array(
																'email_payment' => $this->input->post('txt_mail_pp'),
																'email' => $this->input->post('txt_mail_pp'), 
																'name_payment' => $this->input->post('txt_name_pp')
																)
															);
			
			$this->lang->load('contacts');
			$this->session->set_flashdata('exitoso',$this->lang->line('updatecontactsuccess'));
			redirect("landing/".$contact_app->uri.'/thanks/consignment/');
		}
	}
	
	/**
	* Realiza pago del pin y hace todo lo necesario ahí en la jugada
	*/
	function _ini_pay_pin(){
		if(!$this->_contact_logged())
		{
			$this->session->set_flashdata('error',$this->lang->line('notpermissions'));
			redirect('marketplace');
		}
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('txt_mail_pp', 'Email', 'trim|required|max_length[64]|valid_email|xss_clean');
		$this->form_validation->set_rules('txt_pin', 'Pin', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txt_name_pp', $this->lang->line('fullname'), 'required|max_length[64]|xss_clean');
		
		if ($this->form_validation->run() == FALSE){
			
			$this->session->set_flashdata('error',validation_errors());
			redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/saved');
			
		}else{
			
			$this->load->model('payment_model');
			$pin_data = $this->payment_model->check_pin_landing($this->session->userdata('id_app'), $this->input->post('txt_pin'));
			
			if(!empty($pin_data)){
				$update =	$this->contacts_model->update_contact_suscription(
																$this->session->userdata('id_contact'),
																array(
																'email_payment' => $this->input->post('txt_mail_pp'), 
																'email' => $this->input->post('txt_mail_pp'),
																'name_payment' => $this->input->post('txt_name_pp')
																)
															);
				
				$updated = $this->payment_model->update_pin_used($this->session->userdata('id_contact'), $this->input->post('txt_pin'), $this->session->userdata('id_app'));	
				if(!empty($updated)){
					$this->lang->load('contacts');
					$this->session->set_flashdata('exitoso',$this->lang->line('updatecontactsuccess'));
					redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/thanks/pin/');
				}else{
					$this->lang->load('pay');
					$this->session->set_flashdata('error',$this->lang->line('wrongpin'));
					redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/saved');
				}
			}else{
				$this->lang->load('pay');
				$this->session->set_flashdata('error',$this->lang->line('wrongpin'));
				redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/saved');
			}
		}
	}
	
	/*
	Funcion de pago inicial
		IF state
			= 0 -> Pago iniciado
			= 1 -> Pago exitoso
			= 2 -> Pago con error
	
	*/
	public function _ini_pay_suscribe()
	{ 
		$this->load->model('apps_model');
		$this->load->model('contacts_model');
		$this->load->model('payment_model');
		ini_set('display_errors', 'on');
		$this->lang->load('pay');
		if(!$this->_contact_logged())
		{
			$this->session->set_flashdata('error',$this->lang->line('notpermissions'));
			redirect('marketplace');
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txt_name_pp', $this->lang->line('fullname'), 'required|max_length[64]|xss_clean');
		$this->form_validation->set_rules('txt_mail_pp', 'e-Mail', 'trim|required|max_length[64]|valid_email|xss_clean');
		$this->form_validation->set_rules('txt_address_pp', $this->lang->line('address'), 'required|xss_clean|max_length[190]');
		$this->form_validation->set_rules('cbo_country_pp', $this->lang->line('country'), 'required|xss_clean|numeric');
		$this->form_validation->set_rules('cbo_city_pp', $this->lang->line('city'), 'required|xss_clean|numeric');
		$this->form_validation->set_rules('cbo_packages_pp', $this->lang->line('package'), 'required|xss_clean|numeric');
		$this->form_validation->set_rules('txt_phone_pp', $this->lang->line('phone'), 'required|xss_clean|numeric|max_length[13]');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',validation_errors());
			redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/saved');
		}
		else
		{
			$city 	=	$this->payment_model->get_city($this->input->post('cbo_city_pp'));
			$data   = 	array(
								'country_payment' 	=> $this->input->post('cbo_country_pp'),
								'city_payment' 		=> $this->input->post('cbo_city_pp'),
								'phone_payment' => $this->input->post('txt_phone_pp'),
								'address_payment' 		=> $this->input->post('txt_address_pp'),
								'name_payment' => $this->input->post('txt_name_pp'),
								'email_payment' => $this->input->post('txt_mail_pp'),
								'email' => $this->input->post('txt_mail_pp')
								);
			$update =	$this->contacts_model->update_contact_suscription(
															$this->session->userdata('id_contact'),
															$data
														);
			$user 	=	$this->contacts_model->get_contact_info($this->session->userdata('id_contact'));
			$package =	$this->apps_model->create_price_by_package_suscription($this->input->post('cbo_packages_pp'), $this->input->post('app'), $this->session->userdata('id_contact'));
			$result = 	$this->payment_model->register_payment_suscriber(
																$this->session->userdata('id_contact'),
																$this->input->post('cbo_packages_pp'),
																$this->input->post('app'),
																PAYPAL
															);
			$package = money_format('%i', $package);
			
			$commission = money_format('%i', $this->payment_model->get_paypal_commission($package));
			
			$cobrar = $package + $commission;
			
			if($result != FALSE)
			{
				if(!empty($package))
				{
					$view_data 	=	array(	
										'city'		=>  $city,
										'id_venta'	=>	$result,
										'user'		=> 	$user,
										'package'	=>  $cobrar,
										'is_suscriber' => TRUE
									);
						
					$this->_view_ini_pay($view_data);
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('errorpay'));
					redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/saved');
				}

			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('errorpay'));
				redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/saved');
			}
		}
	}
	
	/***
	* Iniciar pago para pagosonline
	*/
	
	
	public function _ini_pay_subscribe_pagosonline()
	{ 
		$this->load->model('apps_model');
		$this->load->model('contacts_model');
		$this->load->model('payment_model');
		ini_set('display_errors', 'on');
		$this->lang->load('pay');
		if(!$this->_contact_logged())
		{
			$this->session->set_flashdata('error',$this->lang->line('notpermissions'));
			redirect('marketplace');
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txt_name_pp', $this->lang->line('fullname'), 'required|max_length[64]|xss_clean');
		$this->form_validation->set_rules('txt_mail_pp', 'e-Mail', 'trim|required|max_length[64]|valid_email|xss_clean');
		$this->form_validation->set_rules('txt_address_pp', $this->lang->line('address'), 'required|xss_clean|max_length[190]');
		$this->form_validation->set_rules('cbo_country_pp', $this->lang->line('country'), 'required|xss_clean|numeric');
		$this->form_validation->set_rules('cbo_city_pp', $this->lang->line('city'), 'required|xss_clean|numeric');
		$this->form_validation->set_rules('cbo_packages_pp', $this->lang->line('package'), 'required|xss_clean|numeric');
		$this->form_validation->set_rules('txt_phone_pp', $this->lang->line('phone'), 'required|xss_clean|numeric|max_length[13]');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',validation_errors());
			redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/saved');
		}
		else
		{
			$city 	=	$this->payment_model->get_city($this->input->post('cbo_city_pp'));
			$data   = 	array(
								'country_payment' 	=> $this->input->post('cbo_country_pp'),
								'city_payment' 		=> $this->input->post('cbo_city_pp'),
								'phone_payment' => $this->input->post('txt_phone_pp'),
								'address_payment' 		=> $this->input->post('txt_address_pp'),
								'name_payment' => $this->input->post('txt_name_pp'),
								'email_payment' => $this->input->post('txt_mail_pp'),
								'email' => $this->input->post('txt_mail_pp')
								);
			$update =	$this->contacts_model->update_contact_suscription(
															$this->session->userdata('id_contact'),
															$data
														);
			$user 	=	$this->contacts_model->get_contact_info($this->session->userdata('id_contact'));
			$package =	$this->apps_model->create_price_by_package_suscription($this->input->post('cbo_packages_pp'), $this->input->post('app'), $this->session->userdata('id_contact'));
			$refVenta = 	$this->payment_model->register_payment_suscriber(
																$this->session->userdata('id_contact'),
																$this->input->post('cbo_packages_pp'),
																$this->input->post('app'),
																PAGOSONLINE
															);
			$package 	= money_format('%i', $package);
			
			$commission = money_format('%i', $this->payment_model->get_pagosonline_commission($package));
			$cobrar 	= money_format('%i', $package + $commission);
			
			/**
				crear datos para enviar a pagos online
			*/
			$datos_firma = array(
				'refVenta'  => $refVenta,
				'valor'		=> $cobrar
			);
			
			if($refVenta != FALSE)
			{
				if(!empty($package))
				{
					$view_data 	=	array(	
										'refVenta'	=>	$refVenta,
										'user'		=> 	$user,
										'package'	=>  $cobrar,
										'firma'		=> 	$this->firma_de_ida($datos_firma),
										'cod_user_contact' => $this->session->userdata('id_contact'),
										'is_suscriber' => TRUE
										
									);
						
					$this->_view_ini_pay_pagosonline($view_data);
					
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('errorpay'));
					redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/saved');
				}

			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('errorpay'));
				redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/saved');
			}
		}
	}
	
	private function _view_ini_pay($data){
		$this->load->view('hacerpago',$data);
	}
	
	private function _view_ini_pay_pagosonline($data){
		$this->load->view('hacerpago_pagosonline',$data);
	}
	
	private function firma_de_ida($datos){
		$llave_encripcion 	= (PAGOSONLINE_TESTING==1)?PAGOSONLINE_ENCRIPTION_TESTING_KEY:PAGOSONLINE_ENCRIPTION_KEY;
		$usuarioId 			= PAGOSONLINE_USER_ID;
		$moneda				= PAYMENT_CURRENCY;
		$refVenta 			= $datos['refVenta'];
		$valor 				= $datos['valor'];
		$firma				= "$llave_encripcion~$usuarioId~$refVenta~$valor~$moneda";
		$firma_codificada 	= md5($firma);
		
		return $firma_codificada;
	}
	
	
	/**
	* Carga la información de un contacto asociado a una aplicación
	* @param $result información en objecto de la aplicación
	*/	
	private function _load_view_save_extra_data($result){
		$this->load->model('apps_model');
		$this->load->model('contacts_model');
		$this->load->model('payment_model');
		$this->load->model('landing_model');
		
		$this->lang->load('landing');
		$data_load["app_data"] = $result;
		$packages_app = $this->apps_model->load_packages_suscription_data($result->id);
		$data_load["packages"] = $this->_create_package_array($packages_app);
		$data_load["city"] = array();
		$data_load["country"] = $this->_create_country_array($this->landing_model->get_country());
		if($this->_contact_logged()){
			$fields = $this->contacts_model->get_contact_app_custom_field_and_create($result->id, $this->session->userdata('id_contact'), $result->id);
			$data_load["fields"] = $fields;
			$data_load["subscriber"] = TRUE;
			$contact_data = $this->contacts_model->get_contact_associated_to_app($this->session->userdata('id_contact'), $result->id);
			if($contact_data){
				
				$data_load["is_from_this_app"] = TRUE;
				$data_load["contact_data"] = $contact_data;
				
				if(!empty($contact_data->city_payment)){
					$data_load["city"] = $this->_create_city_array($this->contacts_model->get_city($contact_data->country_payment));
				}
			}else{
				$this->session->set_flashdata('error',$this->lang->line('notpermissions'));
				redirect("landing/".$result->uri);
			}
		}else{
			$this->session->set_flashdata('error',$this->lang->line('notpermissions'));
			redirect("landing/".$result->uri);
		}
		$this->load->view('landing-save-extra-data', $data_load);
	}
	
	/**
	* Carga la vista de agradecimiento
	* @param $result los datos de la aplicación 
	*/
	function _load_view_thanks($result, $where_comes){
		$this->load->model('apps_model');
		$this->load->model('contacts_model');
		$this->load->model('payment_model');
		
		$this->lang->load('landing');
		$data_load["app_data"] = $result;
		if($this->_contact_logged()){
			$data_load["subscriber"] = TRUE;
			$contact_data = $this->contacts_model->get_contact_associated_to_app($this->session->userdata('id_contact'), $result->id);
			if($contact_data){
				$data_load["is_from_this_app"] = TRUE;
				$data_load["contact_data"] = $contact_data;
			}else{
				$this->session->set_flashdata('error',$this->lang->line('notpermissions'));
				redirect("landing/".$result->uri);
			}
		}else{
			$this->session->set_flashdata('error',$this->lang->line('notpermissions'));
			redirect("landing/".$result->uri);
		}
		$data_load["where_comes"] = $where_comes;
		$this->load->view('landing-thanks', $data_load);
	}
	
	/**
	 * Funcion privada para cargar la vista de landing
	*/
	private function _view_landing($data){
		$this->load->view('landing',$data);
	}
	
	/** verica si el contacto ya se encuentra subscrito */
	private function _contact_logged(){
		return $this->session->userdata('subscriber');
	}
	
	/**
	* Crea paises para el dropdown.
	* @param $country la lista de paises en objeto. 
	*/
	private function _create_country_array($country){
		$country_back = array();
		foreach($country as $pais):
			$country_back[$pais->id] = $pais->name;
		endforeach;
		return $country_back;
	}
	
	/**
	* Crea ciudades para el dropdown.
	* @param $city la lista de paises en objeto. 
	*/
	private function _create_city_array($city){
		$city_back = array();
		foreach($city as $cit):
			$city_back[$cit->id] = $cit->name;
		endforeach;
		return $city_back;
	}
	
	/**
	* Crea paquetes para el dropdown.
	* @param $package la lista de paquetes en objeto. 
	*/
	private function _create_package_array($package){
		$package_back = array();
		$package_back['']='Seleccione un paquete';
		if(is_array($package)){
			foreach($package as $pack):
				$package_back[$pack->id] = $pack->amount;
			endforeach;
		}
		return $package_back;
	}
	
	/**
	* Ajax para retornar el valor que debe pagar la persona por paquete seleccionado
	* @param $id_package id del paquete
	* @param $id_app id de la aplicación
	* @param $id_contact id del contacto
	*/
	public function create_price_by_package_suscription(){
		$this->session->keep_flashdata('url');
		$this->load->model('apps_model');
		$this->load->model('payment_model');
		$this->lang->load('landing');
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_package', 'id_package', 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('id_app', 'id_app', 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('id_contact', 'id_contact', 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('payment', 'payment', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$res 	= array(
							'cod' 	=> 0,
							'messa'	=> validation_errors()
							);
			echo json_encode($res);
			exit();
		}else{
			$the_pay = $this->apps_model->create_price_by_package_suscription($this->input->post('id_package'), $this->input->post('id_app'), $this->session->userdata('id_contact'));
			
			$the_pay = money_format('%i', $the_pay);
			
			if($this->input->post('payment')=="paypal")  $commission = $this->payment_model->get_paypal_commission($the_pay);
			if($this->input->post('payment')=="pagosonline")  $commission = $this->payment_model->get_pagosonline_commission($the_pay);
			
			if($the_pay != FALSE){
				echo json_encode(array(
							'cod' 	=> 1,
							'the_pay'	=> $the_pay,
							'commission' => money_format('%i', $commission)					
							));//json_encode($pay);
				exit();
			}else{
				$res 	= array(
							'cod' 	=> 0,
							'messa'	=> $this->lang->line('cantgetcredit')
							);
				echo json_encode($res);
				exit();
			}
			exit();
		}			
		die();
	}
	
	/*
		Funcion AJAX publica para traer la ciudad de un pais
	*/
	public function get_city()
	{
		$this->lang->load('landing');
		$this->session->keep_flashdata('url');
		if(!$this->_contact_logged())
		{
			$res 	= array(
							'cod' 	=> 0,
							'messa'	=> $this->lang->line('notpermissions')
							);
			echo json_encode($res);
			exit();
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_pais', 'id_pais', 'required|xss_clean|numeric');
		if ($this->form_validation->run() == FALSE)
		{
			$res 	= array(
							'cod' 	=> 0,
							'messa'	=> validation_errors()
							);
			echo json_encode($res);
			exit();
		}
		else
		{
			$this->load->model('contacts_model');
			$result = $this->contacts_model->get_city($this->input->post('id_pais'));
			if(!empty($result))
			{
				echo json_encode($result);
			}
			else
			{
				$res 	= array(
							'cod' 	=> 0,
							'messa'	=> $this->lang->line('nocity')
							);
				echo json_encode($res);
				exit();
			}
		}
	}
	
	/**
	* Genera un valor simulado de el costo de la llamada de acuerdo a la tabla de precios por teléfono, es una función AJAX
	**/
	
	function check_value_simulator(){
		$this->session->keep_flashdata('url');
		$this->lang->load('landing');
				
		$this->load->model('apps_model');
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('indi_pais', $this->lang->line('country'), 'required|xss_clean|numeric');
		$this->form_validation->set_rules('phone', $this->lang->line('phone'), 'xss_clean|numeric|max_length[13]');
		
		if ($this->form_validation->run() == FALSE)
		{
			$res 	= array(
							'cod' 	=> 0,
							'messa'	=> validation_errors()
							);
			echo json_encode($res);
			exit();
		}else{
			$country	=	$this->apps_model->get_country_id($this->input->post('indi_pais'));
			
			if($country){
				
				$number = "";
				$price  = 0;
				$number .= $country->phonecode;
				$number	.= $this->input->post('phone');
				$price 		= $this->apps_model->get_price_contact($number);
				$utilidad 	= $this->apps_model->getWappPrice($this->input->post('app'));
				if(!empty($price))
				{
					$res 	= array(
								'cod' 	=> 1,
								'messa'	=> number_format((($price->valor)*($utilidad->price/100))+($price->valor), 4),
							);
					echo json_encode($res);
					exit();
					
				}else{
					
					$res 	= array(
								'cod' 	=> 0,
								'messa'	=> $this->lang->line('cant_get_prefix_value')
								);
					echo json_encode($res);
					exit();
				
				}
			}else{
				$res 	= array(
							'cod' 	=> 0,
							'messa'	=> $this->lang->line('country_wrong')
							);
				echo json_encode($res);
				exit();
			}
		}
	}
	
	/**
	* Envia las opservaciones hechas a determinada aplicación
	*/
	function ajax_send_opservations(){
		$this->lang->load("landing");
		$this->load->model('user_model');
		
		$user = $this->user_model->get_user_by_id($this->input->post('user_id'));
		$data = array('name'=>$user->fullname, 'app_title'=>$this->input->post("app_title"), 'message'=>$this->input->post("message"));
		
		$mensaje = $this->load->view('email/'.$this->config->item('language').'/observaciones',$data,TRUE);
		
		$this->load->library('email');
		$this->email->from(KKATOO_EMAIL_INFO, 'Kkatoo Info');
		$this->email->to($user->email);
		$this->email->subject(sprintf($this->lang->line('observaciones'), ucfirst($this->input->post("app_title"))));
		$this->email->message($mensaje);
		if($this->email->send()){
			echo json_encode(array('cod'=>1));
		}else{
			echo json_encode(array('cod'=>0));
		}
		die();
	}
	
	/**
	* Change the app status
	*/
	function ajax_change_status(){
		$status  = ($this->input->post('aprobada')==1)?$this->input->post('aprobada'):0;
		$user_id = $this->input->post('user_id');
		$id_app  = $this->input->post('id_app');
		
		$this->load->model('landing_model');
		$result = $this->landing_model->update_app_aproved($status, $id_app, $user_id);
		if($result){
			$this->lang->load("landing");
			$this->load->model('user_model');
			
			$user = $this->user_model->get_user_by_id($user_id);
			$data = array('name'=>$user->fullname, 'app_title'=>$this->input->post("app_title"), 'state'=>$result->aproved, 'id'=>$id_app);
			
			$mensaje = $this->load->view('email/'.$this->config->item('language').'/aproved_not_aproved',$data,TRUE);
			
			$this->load->library('email');
			$this->email->from(KKATOO_EMAIL_INFO, 'Kkatoo Info');
			$this->email->to($user->email);
			$this->email->subject(sprintf($this->lang->line('statechange'), ucfirst($this->input->post("app_title"))));
			$this->email->message($mensaje);
			$this->email->send();
			echo json_encode(array('cod'=>1, 'messa'=>$result->aproved));
		}else{
			echo json_encode(array('cod'=>0));
		}
		die();
	}
	
}