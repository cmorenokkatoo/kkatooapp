<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {
	
	var $_INICIADO  = "0";
	var $_APROBADO  = "1";
	var $_PENDIENTE = "2";
	var $_ERROR 	= "3";
	
	// Array de estados de PagosOnline
	var $_APROVED = array(1);
	
	var $_NOAPROVED = array(2,3,4,5,6,7,8,9,10,12,13,14,16,17,18,19,20,22,23,25,
		9995,9997,9998,9999);
	
	var $_WAIT = array(15,24,26,9994, 9996);

	/**
	 * Index Page for this controller.
	 * Carga el Login de Kkatoo por defecto
	 */
	public function index()
	{
		$app_name = '';
		if($this->input->get('prtrn', TRUE)){
			$app_name =  str_replace('apps/', '', $this->input->get('prtrn'));
			$this->input->set_cookie('prtrn', $this->input->get('prtrn', TRUE), 1200);
		}
		
		ini_set('display_errors', 'on');
		$this->lang->load('pay');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('marketplace');
		}
		$this->load->model('payment_model');
		$this->load->model('apps_model');
		$appData    =   $this->apps_model->get_uri_app($app_name);
		$user 		= 	$this->payment_model->get_user($this->session->userdata('user_id'));
		$country 	= 	$this->payment_model->get_country();
		$min =  ($this->specialapp->get('special'))?$this->specialapp->get('min_kredits'):0;
		$package	=	$this->payment_model->get_package($min);
		$total_credits	= $this->_get_credit_user();
		$this->load->model('marketplace_model');
		$category 	=	$this->marketplace_model->get_category();
		$data		= 	array(
								'app_data'  => $appData,
								'category'  => $category,
								'credits'	=> $total_credits,
								'user' 		=> $user,
								'country'	=> $country,
								'package'	=> $package
							);
		$this->_view_index_pay($data);
	}
	
	/**************************************
	
	FUNCIONES AJAX PARA PAYMENT
	
	**************************************/
	public function get_city()
	{
		$this->lang->load('apps');
		if(!$this->_login_in())
		{
			$res 	= array(
							'cod' 	=> 0,
							'messa'	=> $this->lang->line('loginplease')
							);
			echo json_encode($res);
			exit();
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_country', 'id_country', 'required|xss_clean|numeric');
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
			$this->load->model('apps_model');
			$result = $this->apps_model->get_city($this->input->post('id_country'));
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
	
	
	/**************************************
	
	FUNCIONES DE INICIO DE PAGO
	
	**************************************/
	
	/*
	Funcion de pago inicial
		IF state
			= 0 -> Pago iniciado
			= 1 -> Pago exitoso
			= 2 -> Pago pendiente de verificación
			= 3 -> Pago con error
	cbo_package_pp = es el valor ingresado por el usuario
	*/
	public function ini_pay()
	{ 
		ini_set('display_errors', 'on');
		$this->lang->load('pay');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('marketplace');
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txt_address_pp', $this->lang->line('address'), 'required|xss_clean|max_length[190]');
		$this->form_validation->set_rules('cbo_country_pp', $this->lang->line('country'), 'required|xss_clean|numeric');
		$this->form_validation->set_rules('cbo_city_pp', $this->lang->line('city'), 'required|xss_clean|numeric');
		$this->form_validation->set_rules('cbo_package_pp', $this->lang->line('package'), 'xss_clean|numeric');
		$this->form_validation->set_rules('txt_phone_pp', $this->lang->line('phone'), 'required|xss_clean|numeric|max_length[13]');

		//Especiales agregados despues ->		
		if($this->input->post('nit_or_id')) $this->form_validation->set_rules('nit_or_id', $this->lang->line('nit_or_id'), 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',validation_errors());
			redirect('payment');
		}
		else
		{
			//ESPECIALES
			
			$nit_or_id = ($this->input->post('nit_or_id'))?$this->input->post('nit_or_id'):'';
			
			$this->load->model('payment_model');
			$city 	=	$this->payment_model->get_city($this->input->post('cbo_city_pp'));
			$data   = 	array(
								'id_country' 	=> $this->input->post('cbo_country_pp'),
								'id_city' 		=> $this->input->post('cbo_city_pp'),
								'phone' 		=> $this->input->post('txt_phone_pp'),
								'address' 		=> $this->input->post('txt_address_pp'),
								'nit_or_id'	=> $nit_or_id
								);
			$update =	$this->payment_model->update_user(
															$this->session->userdata('user_id'),
															$data
														);
			$user 	=	$this->payment_model->get_user($this->session->userdata('user_id'));
			$package =	$this->input->post('cbo_package_pp');//$this->payment_model->get_package_value($this->input->post('cbo_package_pp'));
			
			$result = 	$this->payment_model->register_payment(
																$this->session->userdata('user_id'),
																$this->input->post('cbo_package_pp'),
																PAYPAL
															);
			$package = money_format('%i', $package);
			
			
			$iva = money_format('%i', $package * 0.16);
			$valueIva = money_format('%i', $package + $iva); 

			$commission = money_format('%i', $this->payment_model->get_paypal_commission($valueIva)); 

			$partialValue = money_format('%i', $valueIva + $commission);

			$paypalComFinal = money_format('%i', $this->payment_model->get_paypal_commission($partialValue));

			$cobrar = money_format('%i', $valueIva + $paypalComFinal);

			$ConversorUSD = 2553.15; //money_format('%i', $this->payment_model->convert_cop_to_current_dollar($cobrar));

			$cpackage = money_format('%i', $valueIva / $ConversorUSD);
			 
			
				
			if($result != FALSE)
			{
				if(!empty($package))
				{
					$view_data 	=	array(	
										'city'		=>  $city,
										'id_venta'	=>	$result,
										'user'		=> 	$user,
										'package'	=>  $cpackage
									);
						
					$this->_view_ini_pay($view_data);
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('errorpay'));
					redirect('payment');
				}

			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('errorpay'));
				redirect('payment');
			}
		}
	}
	
	/*
	 Funcion para confirmar que el pago fue realizado con exito
	*/
	public function confirm_payment()
	{
		ini_set('display_errors', 'on');
		$this->lang->load('pay');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('marketplace');
		}
		$query[] = 'cmd=_notify-validate';
//		echo var_dump($_POST);
		foreach($_POST AS $key => $val)
		{
			$query[] = $key . '=' . urlencode ($val);
		}
		
		$query 		= implode('&', $query);
		$used_curl 	= FALSE;
		if (function_exists('curl_init') AND $ch = curl_init())
		{
			curl_setopt($ch, CURLOPT_URL, 'https://www.paypal.com/cgi-bin/webscr');
			curl_setopt($ch, CURLOPT_TIMEOUT, 15);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Kkattoo codeigniter via cURL/PHP');
			$result = curl_exec($ch);
			curl_close($ch);
			$this->load->model('payment_model');
			
			$status = 	$this->input->post('payment_status');
			$id_pay = 	$this->input->post('item_number');
			$monto	=	$this->input->post('mc_gross');
			$currency = $this->input->post("mc_currency");
			
			if (strcmp ($result, "VERIFIED") == 0)
			{
				$id_pay = $this->input->post('item_number');
				
				$pay 	= $this->payment_model->get_payment($id_pay, $this->session->userdata('user_id'));
				if(!empty($pay))
				{
					$make = $this->payment_model->make_payment($id_pay, $this->session->userdata('user_id'), 1);
					if($make)
					{
						$paquete = $pay->id_package;//$this->payment_model->get_package_value($pay->id_package);

						if(!empty($paquete))
						{
								$aux = $this->payment_model->update_user_credits($this->session->userdata('user_id'),$paquete);
								$this->payment_model->email_payment($id_pay, $monto, $currency);	

							if($aux)
							{
								$this->session->set_flashdata('exitoso',$this->lang->line('paysuccess'));
								if($this->input->cookie('mycookieprtrn')){
									redirect($this->input->cookie('mycookieprtrn'));
								}else{
									redirect('payment');
								}
							}
						}//fin if(!empty($paquete))
					}
				}
			}
			else
			{
				switch($status)
				{
					//Si el pago llega con estado Completed
					case "Completed":
						$pay 	= $this->payment_model->get_payment($id_pay, $this->session->userdata('user_id'));
						if(!empty($pay))
						{
							$make = $this->payment_model->make_payment($id_pay, $this->session->userdata('user_id'), 1);
							if($make)
							{
								$paquete = $pay->id_package;
								$paquete2 = (($paquete * 1));
								$paquete3 = (($paquete * 1));
								
								if(!empty($paquete))
								{
									$paquete_valor = money_format('%i', $paquete);
									$iva = money_format('%i', $paquete * 0.16);
									$valueIva = money_format('%i', $paquete + $iva); 
									$commission = money_format('%i', $this->payment_model->get_paypal_commission($valueIva)); 
									$partialValue = money_format('%i', $valueIva + $commission);
									$paypalComFinal = money_format('%i', $this->payment_model->get_paypal_commission($partialValue));
									
									$cobrar = money_format('%i', $valueIva + $paypalComFinal); 
									$ConversorUSD = 2553.15;
			
									$cpackage = money_format('%i', $valueIva / $ConversorUSD);
									
									if(round($monto) == round($cpackage) && $currency = PAYMENT_CURRENCY)
									{
										$this->payment_model->email_payment($id_pay, $monto, $currency);
										if ($paquete >= 100 and $paquete < 201) {
											$aux = $this->payment_model->update_user_credits($this->session->userdata('user_id'),$paquete2);
										}elseif ($paquete >= 201) {
											$aux = $this->payment_model->update_user_credits($this->session->userdata('user_id'),$paquete3);
										}
										
										if($aux)
										{
											$this->session->set_flashdata('exitoso',$this->lang->line('paysuccess'));
											if($this->input->cookie('mycookieprtrn')){
												redirect($this->input->cookie('mycookieprtrn'));
											}else{
												redirect('payment');
											}
										}
									}
									else
									{
										$this->session->set_flashdata('error',$this->lang->line('errormount'));
										if($this->input->cookie('mycookieprtrn')){
											redirect($this->input->cookie('mycookieprtrn'));
										}else{
											redirect('payment');
										}
									}
								}
							}
						}
						break;
					//Se coloca el estado en 2 si el pago quedó pendiente para revisión manual
					case "Pending":
						$pay 	= $this->payment_model->get_payment($id_pay, $this->session->userdata('user_id'));
						if(!empty($pay))
						{
							$make = $this->payment_model->make_payment($id_pay, $this->session->userdata('user_id'), 2);
							if($make)
							{	
								$this->session->set_flashdata('error',$this->lang->line('paymanual'));
								$this->payment_model->email_payment_validando_subscribe($id_pay);
								
								if($this->input->cookie('mycookieprtrn')){
									redirect($this->input->cookie('mycookieprtrn'));
								}else{
									redirect('payment');
								}
							}
						}
						break;
					default:
						$pay 	= $this->payment_model->get_payment($id_pay, $this->session->userdata('user_id'));
						if(!empty($pay)){
							$make = $this->payment_model->make_payment($id_pay, $this->session->userdata('user_id'), 3);
						}
						$this->session->set_flashdata('error',$this->lang->line('errorpay'));
						if($this->input->cookie('mycookieprtrn')){
							redirect($this->input->cookie('mycookieprtrn'));
						}else{
							redirect('payment');
						}
					break;
					
				}
				//Pago no Valido
				/*$make 	= $this->payment_model->make_payment($this->input->post('item_number'), $this->session->userdata('user_id'), 2);
				if($make)
				{
					$this->session->set_flashdata('error',$this->lang->line('errorpay'));
					redirect('payment');	
				}*/
			}
		}
		else
		{
			
			$this->session->set_flashdata('error',$this->lang->line('errorpaygrave'));
			if($this->input->cookie('mycookieprtrn')){
				redirect($this->input->cookie('mycookieprtrn'));
			}else{
				redirect('payment');
			}
		}
	}

	/**
	 * Funcion privada para verificar el Login del usuario
	*/
	private function _login_in()
	{
		return $this->session->userdata('logged_in');
	}
		
	private function _view_ini_pay($data)
	{
		$this->load->view('hacerpago',$data);
	}
	
	private function _view_index_pay($data)
	{
		$this->load->view('payment_manager', $data);
	}
	private function _get_credit_user()
	{
		$this->load->model('hooks_model');
		$result	= $this->hooks_model->get_user($this->session->userdata('user_id'));
		return $result->credits;
	}
	
	/********** POCHO CÓDIGO *************/
	
	/**************************************
	
	FUNCIONES DE INICIO DE PAGO POR SUSCRIPCION
	
	**************************************/
	
	/** verica si el contacto ya se encuentra subscrito */
	private function _contact_logged(){
		return $this->session->userdata('subscriber');
	}
	
	/*
	 Funcion para confirmar que el pago fue realizado con exito
	*/
	public function confirm_payment_suscription()
	{
		ini_set('display_errors', 'on');
		$this->lang->load('pay');
		if(!$this->_contact_logged())
		{
			$this->session->set_flashdata('error',$this->lang->line('notpermissions'));
			redirect('marketplace');
		}
		$query[] = 'cmd=_notify-validate';
		//echo var_dump($_POST);
		foreach($_POST AS $key => $val)
		{
			$query[] = $key . '=' . urlencode ($val);
		}
		
		$query 		= implode('&', $query);
		$used_curl 	= FALSE;
		if (function_exists('curl_init') AND $ch = curl_init())
		{
			curl_setopt($ch, CURLOPT_URL, 'https://www.paypal.com/cgi-bin/webscr');
			curl_setopt($ch, CURLOPT_TIMEOUT, 15);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Kkattoo codeigniter via cURL/PHP');
			$result = curl_exec($ch);
			curl_close($ch);
			$this->load->model('apps_model');
			$this->load->model('payment_model');
			
			$status = 	$this->input->post('payment_status');
			$id_pay = 	$this->input->post('item_number');
			$monto	=	$this->input->post('mc_gross');
			$currency = $this->input->post('mc_currency');
			
			if (strcmp ($result, "VERIFIED") == 0)
			{
				$id_pay = $this->input->post('item_number');
				
				$pay 	= $this->payment_model->get_payment($id_pay, $this->session->userdata('id_contact'), TRUE);
				if(!empty($pay))
				{
					$make = $this->payment_model->make_payment($id_pay, $this->session->userdata('id_contact'), 1, TRUE);
					if($make)
					{
						$nro_paquetes = $this->apps_model->get_nro_by_package($pay->id_package);
						$paquete = $this->apps_model->create_price_by_package_suscription($pay->id_package, $pay->id_app, $this->session->userdata('id_contact'));
						$paquete = money_format('%i', $paquete);
						
						if(!empty($paquete))
						{
							$aux = $this->payment_model->update_contact_app_credits($this->session->userdata('id_contact'), $pay->id_app, $paquete, $nro_paquetes);
							if($aux)
							{
								$this->payment_model->email_payment_paypal_subscribe($id_pay);
								$this->session->set_flashdata('exitoso',$this->lang->line('paysuccess'));
								redirect("landing/".$this->apps_model->get_app_uri($pay->id_app).'/thanks/paypal');
							}
						}
					}
				}
			}
			else
			{
				
				$pay 	= $this->payment_model->get_payment($id_pay, $this->session->userdata('id_contact'), TRUE);
				switch($status)
				{
					//Si el pago llega con estado Completed
					case "Completed":
						if(!empty($pay))
						{
							$make = $this->payment_model->make_payment($id_pay, $this->session->userdata('id_contact'), 1, TRUE);
							if($make)
							{
								$nro_paquetes = $this->apps_model->get_nro_by_package($pay->id_package);
								
								$paquete = $this->apps_model->create_price_by_package_suscription($pay->id_package, $pay->id_app, $this->session->userdata('id_contact'));

								$paquete = money_format('%i', $paquete);
								
								$commission = money_format('%i', $this->payment_model->get_paypal_commission($paquete));
								
								$cobrar = $paquete + $commission;
								
								if(!empty($paquete))
								{
									
									if(round($monto) == round($cobrar) && $currency = PAYMENT_CURRENCY)
									{
										$aux = $this->payment_model->update_contact_app_credits($this->session->userdata('id_contact'), $pay->id_app, $paquete, $nro_paquetes);
										if($aux)
										{
											$this->payment_model->email_payment_paypal_subscribe($id_pay);
											$this->session->set_flashdata('exitoso',$this->lang->line('paysuccess'));
											redirect("landing/".$this->apps_model->get_app_uri($pay->id_app).'/thanks/paypal');
										}
									}
									else
									{
										$this->session->set_flashdata('error',$this->lang->line('errormount'));
										redirect("landing/".$this->apps_model->get_app_uri($pay->id_app).'/saved');
									}
								}
							}
						}
						break;
					//Se coloca el estado en 2 si el pago quedó pendiente para revisión manual
					case "Pending":
						//$pay 	= $this->payment_model->get_payment($id_pay, $this->session->userdata('id_contact'), TRUE);
						if(!empty($pay))
						{
							$make = $this->payment_model->make_payment($id_pay, $this->session->userdata('id_contact'), 2, TRUE);
							if($make)
							{	
								$this->payment_model->email_payment_paypal_subscribe($id_pay);
								$this->session->set_flashdata('error',$this->lang->line('paymanual'));
								redirect("landing/".$this->apps_model->get_app_uri($pay->id_app).'/thanks');
							}
						}
						break;
					default:
							if(!empty($pay)){
								$make = $this->payment_model->make_payment($id_pay, $this->session->userdata('id_contact'), 3, TRUE);
							}
							$this->session->set_flashdata('error',$this->lang->line('errormount'));
							redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/saved');
					break;
				}
			}
		}
		else
		{
			$this->session->set_flashdata('error',$this->lang->line('errorpaygrave'));
			redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/saved');
		}
	}
	
	
	/**
	* muestra comisión para agregar créditos por paypal
	*/
	function show_commission_for_kredits(){
		$this->session->keep_flashdata('url');
		$this->load->model('payment_model');
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_package', 'id_package', 'required|xss_clean|numeric|max_length[12]');
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
			$the_pay = $this->payment_model->get_package_value($this->input->post('id_package'));
			$the_pay = money_format('%i', $the_pay->valor);
			if($this->input->post('payment')=="paypal")  $commission = $this->payment_model->get_paypal_commission($the_pay);
			
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
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('login/login');
			die();
		}
	}
	
	
	public function ini_pay_pagosonline_prueba(){
		
		/**	crear datos para enviar a pagos online
		*/
		$cobrar = 0;
		$refVenta = uniqid();
		$datos_firma = array(
			'refVenta'  => $refVenta,
			'valor'		=> $cobrar
		);
		$user = (object)array('fullname'=>'Alejandrox Lopez');
		$view_data 	=	array(	
							'refVenta'	=>	$refVenta,
							'user'		=> 	$user,
							'package'	=>  $cobrar,
							'firma'		=> 	$this->firma_de_ida($datos_firma)
						);
			
		$this->_view_ini_pay_pagosonline($view_data);
	}
	
	public function pagosonline_confirm_payment_prueba(){
		$this->lang->load('pay');
		$this->load->model('payment_model');
		$datos_firma = array(
			'usuario_id'		=> $this->input->post('usuario_id', TRUE),
			'moneda'			=> $this->input->post('moneda', TRUE),
			'ref_venta' 		=> $this->input->post('ref_venta', TRUE),
			'valor'				=> $this->input->post('valor', TRUE),
			'estado_pol' 		=> $this->input->post('estado_pol', TRUE)
		);
		
		if(strcmp(trim($this->input->post('firma', TRUE)), trim($this->firma_regreso($datos_firma)))===0){
			$status 	= $this->input->post('codigo_respuesta_pol', TRUE);
			$id_pay 	= $this->input->post('ref_venta', TRUE);
			$monto		= $this->input->post('valor', TRUE);
			//$pay 		= $this->payment_model->get_payment($id_pay, $this->session->userdata('user_id'));
			//$paquete 	= $this->payment_model->get_package_value($pay->id_package);
				//$this->payment_model->verifica_ejecuta_cofirm("Pasó firma!");
			//Aprovado
			if(in_array($status, $this->_APROVED)){
				
			//No aprobado
			}else if(in_array($status, $this->_NOAPROVED)){
				
			//Hay que validar
			}else if(in_array($status, $this->_WAIT)){
					
			}
		}else{
			
			//$this->payment_model->verifica_ejecuta_cofirm("No pasó firma!");
			$this->session->set_userdata('entro_confirm', 'Ingresó a confirm, pero <strong>NO PASÓ</strong> prueba de firma');
		}
		
	}
	
	public function pagosonline_respond_prueba(){
		$this->lang->load('pay');
		$mensaje = array();
		
		$datos_firma = array(
			'usuario_id'		=> $this->input->get('usuario_id'),
			'moneda'			=> $this->input->get('moneda'),
			'ref_venta' 		=> $this->input->get('ref_venta'),
			'valor'				=> $this->input->get('valor'),
			'estado_pol' 		=> $this->input->get('estado_pol')
		);
		
		if(strcmp(trim($this->input->get('firma', TRUE)), trim($this->firma_regreso($datos_firma)))===0){
			$status = 	$this->input->get('codigo_respuesta_pol');
			$id_pay = 	$this->input->get('ref_venta');
			$monto	=	$this->input->get('valor');
			//$pay 	= 	$this->payment_model->get_payment($id_pay, $this->session->userdata('user_id'));
			
			//Aprovado
			if(in_array($status, $this->_APROVED)){			
				$mensaje = array('mensaje'=>'aprobado');
			//No aprovado
			}else if(in_array($status, $this->_NOAPROVED)){
				$mensaje = array('mensaje'=>'no aprobado');
				
			//Hay que validar
			}else if(in_array($status, $this->_WAIT)){
				$mensaje = array('mensaje'=>'en espera');
			}
			$this->load->view('pagos_online_respond', $mensaje);
		}else{
			$mensaje['mensaje']= $this->lang->line('errorpaygrave');
			$this->load->view('pagos_online_respond', $mensaje);
		}
	}
	
	
	/* PAGOS ONLINE! */
	
	public function ini_pay_pagosonline()
	{ 
		ini_set('display_errors', 'on');
		$this->lang->load('pay');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('marketplace');
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txt_address_pp', $this->lang->line('address'), 'required|xss_clean|max_length[190]');
		$this->form_validation->set_rules('cbo_country_pp', $this->lang->line('country'), 'required|xss_clean|numeric');
		$this->form_validation->set_rules('cbo_city_pp', $this->lang->line('city'), 'required|xss_clean|numeric');
		$this->form_validation->set_rules('cbo_package_pp', $this->lang->line('package'), 'xss_clean|numeric');
		$this->form_validation->set_rules('txt_phone_pp', $this->lang->line('phone'), 'required|xss_clean|numeric|max_length[13]');

		//Especiales agregados despues ->		
		if($this->input->post('nit_or_id')) $this->form_validation->set_rules('nit_or_id', $this->lang->line('nit_or_id'), 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',validation_errors());
			redirect('payment');
		}
		else
		{
			//ESPECIALES
			
			$nit_or_id = ($this->input->post('nit_or_id'))?$this->input->post('nit_or_id'):'';
			
			$this->load->model('payment_model');
			$city 	=	$this->payment_model->get_city($this->input->post('cbo_city_pp'));
			$data   = 	array(
								'id_country' 	=> $this->input->post('cbo_country_pp'),
								'id_city' 		=> $this->input->post('cbo_city_pp'),
								'phone' 		=> $this->input->post('txt_phone_pp'),
								'address' 		=> $this->input->post('txt_address_pp'),
								'nit_or_id'	=> $nit_or_id
								);
			$update 	=	$this->payment_model->update_user(
															$this->session->userdata('user_id'),
															$data
														);
			$user 		=	$this->payment_model->get_user($this->session->userdata('user_id'));
			$package	=	$this->input->post('cbo_package_pp');
			$refVenta 	= 	$this->payment_model->register_payment(
																$this->session->userdata('user_id'),
																$this->input->post('cbo_package_pp'),
																PAGOSONLINE
															);
			$package = money_format('%i', $package); // $100.000
			$iva = money_format('%i', $package * 0.16); // $16.000
			$valueIva = money_format('%i', $package + $iva); // $116.000

			$commission = money_format('%i', $this->payment_model->get_pagosonline_commission($valueIva)); // (

			$partialValue = money_format('%i', $valueIva + $commission); // 
 
			$paypalComFinal = money_format('%i', $this->payment_model->get_pagosonline_commission($partialValue)); // 

			$cobrar = money_format('%i', $valueIva + $paypalComFinal); //  -> Total Final

			// var_dump($package, $iva, $valueIva, $commission, $partialValue, $paypalComFinal, $cobrar);die();
			// $package = money_format('%i', $package);
			// $commission = money_format('%i', $this->payment_model->get_pagosonline_commission($package));
			// $cobrar = money_format('%i', $package + $commission);
			/**	crear datos para enviar a pagos online
			*/
		
			$datos_firma = array(
				'refVenta'  => $refVenta,
				'valor'		=> $valueIva
			);
			
			if($refVenta != FALSE)
			{
				if(!empty($package))
				{
					$view_data 	=	array(	
										'refVenta'	=>	$refVenta,
										'user'		=> 	$user,
										'package'	=>  $valueIva,
										'firma'		=> 	$this->firma_de_ida($datos_firma),
										'cod_user_contact'	=> $this->session->userdata('user_id')
									);
						
					$this->_view_ini_pay_pagosonline($view_data);
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('errorpay'));
					redirect('payment');
				}

			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('errorpay'));
				redirect('payment');
			}
		}
	}
	
	private function get_state_pagosonline($estado_pol, $codigo_respuesta_pol){
		// se verifica los estados de la transaccion para asi actualizar el nombre en la orden		
		$return = 0;
		
		switch($estado_pol){
			case 4:
				if($codigo_respuesta_pol == 1){
					$return = $this->_APROBADO;
				}else{
					$return = $this->_ERROR;
				}
			break;
			case 6:
				if($codigo_respuesta_pol == 4){
					$return = $this->_ERROR;
				}elseif($codigo_respuesta_pol==15){
					$return = $this->_PENDIENTE;
				}elseif($codigo_respuesta_pol==5){
					$return = $this->_ERROR;
				}else{
					$retur = $this->_ERROR;
				}
			break;
			case 7:
				$return = $this->_PENDIENTE;
			break;
			case 12:
				$return = $this->_PENDIENTE;
			break;
			default:
				$return = $this->_ERROR;
			break;
		}
		return $return;
	}
	
	public function pagosonline_confirm_payment(){
		$this->lang->load('pay');
		$this->load->model('payment_model');
		$datos_firma = array(
			'usuario_id'		=> $this->input->post('usuario_id', TRUE),
			'moneda'			=> $this->input->post('moneda', TRUE),
			'ref_venta' 		=> $this->input->post('ref_venta', TRUE),
			'valor'				=> $this->input->post('valor', TRUE),
			'estado_pol' 		=> $this->input->post('estado_pol', TRUE)
		);
		
		if(strcmp(trim($this->input->post('firma', TRUE)), trim($this->firma_regreso($datos_firma)))===0){
			$codigo_respuesta_pol 	= $this->input->post('codigo_respuesta_pol', TRUE);
			$estado_pol 			= $this->input->post('estado_pol', TRUE);
			$id_pay 				= $this->input->post('ref_venta', TRUE);
			$monto					= round(($this->input->post('valor', TRUE)/$this->input->post('tasa_cambio', TRUE)));
			$moneda    				= $this->input->post('moneda', TRUE);
			
			//En extra 1 yo envio el id de usuario que lo necesito luego
			$id_user 	= $this->input->post('extra1', TRUE);
			$pay 		= $this->payment_model->get_payment($id_pay, $id_user);
			$paquete 	= $pay->id_package;
			
			$this->payment_model->update_extra_values($id_pay, json_encode($this->input->post()));
			
			//Aprovado
			if($this->get_state_pagosonline($estado_pol, $codigo_respuesta_pol)==1){
				if(!empty($pay)){
					$make = $this->payment_model->make_payment($id_pay, $pay->user_id, 1);
					if($make){
						$paquete = $pay->id_package;
						$paquete2 = (($paquete * 1));
						$paquete3 = (($paquete * 1));

						if(!empty($paquete)){
						
							$paquete_valor = money_format('%i', $paquete);
							$iva = money_format('%i', $paquete * 0.16);
							$valueIva = money_format('%i', $paquete + $iva); 
							$commission = money_format('%i', $this->payment_model->get_paypal_commission($valueIva)); 
							$partialValue = money_format('%i', $valueIva + $commission);
							$paypalComFinal = money_format('%i', $this->payment_model->get_paypal_commission($partialValue));
							
							$cobrar = money_format('%i', $valueIva + $paypalComFinal);
							// $commission = money_format('%i', $this->payment_model->get_pagosonline_commission($paquete_valor));
							
							// $cobrar = $paquete_valor + $commission;
							
							if(round($monto) == round($valueIva) && $moneda == PAYMENT_CURRENCY){
								if ($paquete >= 100 and $paquete < 201){
									$aux = $this->payment_model->update_user_credits($pay->user_id,$paquete2);
								}elseif ($paquete >= 201) {
									$aux = $this->payment_model->update_user_credits($pay->user_id,$paquete3);
								}
								
								$this->payment_model->email_payment($id_pay, $monto, $moneda);
							}
						}
					}
				}
			//No aprovado
			}else if($this->get_state_pagosonline($estado_pol, $codigo_respuesta_pol)==3){
				if(!empty($pay)){
					$make = $this->payment_model->make_payment($id_pay, $pay->user_id, 3);
				}
			//Hay que validar
			}else if($this->get_state_pagosonline($estado_pol, $codigo_respuesta_pol)==2){

				$paquete = $pay->id_package;
				if(!empty($paquete)){

					$paquete_valor = money_format('%i', $paquete);
					$iva = money_format('%i', $paquete * 0.16);
					$valueIva = money_format('%i', $paquete + $iva); 
					$commission = money_format('%i', $this->payment_model->get_paypal_commission($valueIva)); 
					$partialValue = money_format('%i', $valueIva + $commission);
					$paypalComFinal = money_format('%i', $this->payment_model->get_paypal_commission($partialValue));
					
					$cobrar = money_format('%i', $valueIva + $paypalComFinal);

				
					// $paquete_valor 	= money_format('%i', $paquete);
					// $commission 	= money_format('%i', $this->payment_model->get_pagosonline_commission($paquete_valor));
					
					// $cobrar = $paquete_valor + $commission;
					
					if(round($monto) == round($valueIva) && $moneda == PAYMENT_CURRENCY){
						$make = $this->payment_model->make_payment($id_pay, $pay->user_id, 2);
					}
				}
				
			}
		}else{
			//print error
		}
	}
	
	public function pagosonline_respond(){
		$this->lang->load('pay');
		$this->load->model('payment_model');
		
		$datos_firma = array(
			'usuario_id'		=> $this->input->get('usuario_id'),
			'moneda'			=> $this->input->get('moneda'),
			'ref_venta' 		=> $this->input->get('ref_venta'),
			'valor'				=> $this->input->get('valor'),
			'estado_pol' 		=> $this->input->get('estado_pol')
		);
		
		if(strcmp(trim($this->input->get('firma', TRUE)), trim($this->firma_regreso($datos_firma)))===0){
			$codigo_respuesta_pol 	= 	$this->input->get('codigo_respuesta_pol', TRUE);
			$estado_pol 			= 	$this->input->get('estado_pol', TRUE);
			$id_pay 				= 	$this->input->get('ref_venta');
			$monto					=	$this->input->get('valor');
			$moneda 				= 	$this->input->get('moneda');
			$pay 					= 	$this->payment_model->get_payment($id_pay, $this->session->userdata('user_id'));
			
			//Aprovado
			if($this->get_state_pagosonline($estado_pol, $codigo_respuesta_pol)==1){
				if(!empty($pay)){
					//$make = $this->payment_model->make_payment($id_pay, $this->session->userdata('user_id'), 1);
					$paquete = $pay->id_package;
					if(!empty($paquete)){
					
						$paquete_valor = money_format('%i', $paquete);
						$iva = money_format('%i', $paquete * 0.16);
						$valueIva = money_format('%i', $paquete + $iva); 
						$commission = money_format('%i', $this->payment_model->get_paypal_commission($valueIva)); 
						$partialValue = money_format('%i', $valueIva + $commission);
						$paypalComFinal = money_format('%i', $this->payment_model->get_paypal_commission($partialValue));
						
						$cobrar = money_format('%i', $valueIva + $paypalComFinal);
						
						if(round($monto) == round($valueIva) && $moneda == PAYMENT_CURRENCY){
						
							$this->session->set_flashdata('exitoso',$this->lang->line('paysuccess'));
							$this->session->set_flashdata('paymade', 'true');
							if($this->input->cookie('mycookieprtrn')){
								redirect($this->input->cookie('mycookieprtrn').'?estado_pago='.$this->lang->line('pol_'.$codigo_respuesta_pol).'&'.http_build_query($this->input->get()));
							}else{
								redirect('payment?estado_pago='.$this->lang->line('pol_'.$codigo_respuesta_pol).'&'.http_build_query($this->input->get()));
							}
						}
					}
				}
			//No aprovado
			}else if($this->get_state_pagosonline($estado_pol, $codigo_respuesta_pol)==3){
				$message = $this->lang->line('nopay');
				
				$this->session->set_flashdata('error',$message);
				$this->session->set_flashdata('paymade', 'true');
				if($this->input->cookie('mycookieprtrn')){
					redirect($this->input->cookie('mycookieprtrn').'?estado_pago='.$this->lang->line('pol_'.$codigo_respuesta_pol).'&'.http_build_query($this->input->get()));
				}else{
					redirect('payment?estado_pago='.$this->lang->line('pol_'.$codigo_respuesta_pol).'&'.http_build_query($this->input->get()));
				}
			//Hay que validar
			}else if($this->get_state_pagosonline($estado_pol, $codigo_respuesta_pol)==2){
				$paquete = $pay->id_package;
				if(!empty($paquete)){
				
					$paquete_valor = money_format('%i', $paquete);
					$iva = money_format('%i', $paquete * 0.16);
					$valueIva = money_format('%i', $paquete + $iva); 
					$commission = money_format('%i', $this->payment_model->get_paypal_commission($valueIva)); 
					$partialValue = money_format('%i', $valueIva + $commission);
					$paypalComFinal = money_format('%i', $this->payment_model->get_paypal_commission($partialValue));
					
					$cobrar = money_format('%i', $valueIva + $paypalComFinal);
					
					if(round($monto) == round($valueIva) && $moneda == PAYMENT_CURRENCY){
						$this->payment_model->email_payment_validando_subscribe($id_pay);
						
						$message = $this->lang->line('paymanual');
						
						$this->session->set_flashdata('exitoso',$message);
						$this->session->set_flashdata('paymade', 'true');
						if($this->input->cookie('mycookieprtrn')){
							redirect($this->input->cookie('mycookieprtrn').'?estado_pago='.$this->lang->line('pol_'.$codigo_respuesta_pol).'&'.http_build_query($this->input->get()));
						}else{
							redirect('payment?estado_pago='.$this->lang->line('pol_'.$codigo_respuesta_pol).'&'.http_build_query($this->input->get()));
						}						
					}
				}
			}
		}else{
			$this->session->set_flashdata('error',$this->lang->line('errorpaygrave'));
			if($this->input->cookie('mycookieprtrn')){
				redirect($this->input->cookie('mycookieprtrn').'?estado_pago='.$this->lang->line('pol_'.$codigo_respuesta_pol).'&'.http_build_query($this->input->get()));
			}else{
				redirect('payment?estado_pago='.$this->lang->line('pol_'.$codigo_respuesta_pol).'&'.http_build_query($this->input->get()));
			}
		}
	}
	
	/**
	* Confirma el pago realizado por Pagosonline
	*/
	public function pagosonline_subscribe_confirm_payment(){
		$this->lang->load('pay');
		$this->load->model('payment_model');
		$this->load->model('apps_model');
		
		$datos_firma = array(
			'usuario_id'		=> $this->input->post('usuario_id', TRUE),
			'moneda'			=> $this->input->post('moneda', TRUE),
			'ref_venta' 		=> $this->input->post('ref_venta', TRUE),
			'valor'				=> $this->input->post('valor', TRUE),
			'estado_pol' 		=> $this->input->post('estado_pol', TRUE)
		);
		
		if(strcmp(trim($this->input->post('firma', TRUE)), trim($this->firma_regreso($datos_firma)))===0){
			$codigo_respuesta_pol 	= $this->input->post('codigo_respuesta_pol', TRUE);
			$estado_pol 			= $this->input->post('estado_pol', TRUE);
			$id_pay 				= $this->input->post('ref_venta', TRUE);
			$monto					= round(($this->input->post('valor', TRUE)/$this->input->post('tasa_cambio', TRUE)));
			$moneda 				= $this->input->post('moneda', TRUE);
			
			//En extra 1 yo envio el id de usuario que lo necesito luego
			$id_contact = $this->input->post('extra1', TRUE);
			$pay 		= $this->payment_model->get_payment($id_pay, $id_contact, TRUE);
			
			$this->payment_model->update_extra_values($id_pay, json_encode($this->input->post()));
			
			//Aprovado
			if($this->get_state_pagosonline($estado_pol, $codigo_respuesta_pol)==1){
				$make = $this->payment_model->make_payment($id_pay, $id_contact, 1, TRUE);
				if($make){
					$nro_paquetes 	= $this->apps_model->get_nro_by_package($pay->id_package);
					$paquete 		= $this->apps_model->create_price_by_package_suscription($pay->id_package, $pay->id_app, $id_contact);
					$paquete 		= money_format('%i', $paquete);
					$commission 	= money_format('%i', $this->payment_model->get_pagosonline_commission($paquete));
					
					$cobrar 		= $paquete + $commission;
					
					if(!empty($paquete)){
						if(round($monto) == round($cobrar) && $moneda == PAYMENT_CURRENCY){
							$aux = $this->payment_model->update_contact_app_credits($id_contact, $pay->id_app, $paquete, $nro_paquetes);
							$this->payment_model->email_payment_paypal_subscribe($id_pay);

					}
				}
			}
		
			//No aprovado
			}else if($this->get_state_pagosonline($estado_pol, $codigo_respuesta_pol)==3){
				if(!empty($pay)){
					$make = $this->payment_model->make_payment($id_pay, $id_contact, 3, TRUE);
				}
	
			//Hay que validar
			}else if($this->get_state_pagosonline($estado_pol, $codigo_respuesta_pol)==2){
				if(!empty($pay)){
					$nro_paquetes 	= $this->apps_model->get_nro_by_package($pay->id_package);
					$paquete 		= $this->apps_model->create_price_by_package_suscription($pay->id_package, $pay->id_app, $id_contact);
					$paquete 		= money_format('%i', $paquete);
					$commission 	= money_format('%i', $this->payment_model->get_pagosonline_commission($paquete));
					$cobrar 		= $paquete + $commission;
					if(!empty($paquete)){
						if(round($monto) == round($cobrar) && $moneda == PAYMENT_CURRENCY){
							$make = $this->payment_model->make_payment($id_pay, $id_contact, 2, TRUE);
						}
					}
				}			
			}
		}else{
			//nothing
		}
	}
	
	/**
	* Recibe los datos de una compra hecha por Pagosonline
	*/
	public function pagosonline_subscribe_respond(){
		$this->lang->load('pay');
		$this->load->model('payment_model');
		$this->load->model('apps_model');
		
		$datos_firma = array(
			'usuario_id'		=> $this->input->get('usuario_id'),
			'moneda'			=> $this->input->get('moneda'),
			'ref_venta' 		=> $this->input->get('ref_venta'),
			'valor'				=> $this->input->get('valor'),
			'estado_pol' 		=> $this->input->get('estado_pol')
		);
		
		if(strcmp(trim($this->input->get('firma', TRUE)), trim($this->firma_regreso($datos_firma)))===0){
			$codigo_respuesta_pol 	= 	$this->input->get('codigo_respuesta_pol', TRUE);
			$estado_pol 			= 	$this->input->get('estado_pol', TRUE);
			$id_pay 	= 	$this->input->get('ref_venta');
			$monto		=	$this->input->get('valor');
			$pay 		= 	$this->payment_model->get_payment($id_pay, $this->session->userdata('id_contact'), TRUE);
			$moneda     = 	$this->input->get('moneda');
			
			//Aprovado
			if($this->get_state_pagosonline($estado_pol, $codigo_respuesta_pol)==1){
				if(!empty($pay)){
					//$make = $this->payment_model->make_payment($id_pay, $this->session->userdata('id_contact'), 1, TRUE);
					$nro_paquetes 	= $this->apps_model->get_nro_by_package($pay->id_package);
					$paquete 		= $this->apps_model->create_price_by_package_suscription($pay->id_package, $pay->id_app, $this->session->userdata('id_contact'));
					$paquete 		= money_format('%i', $paquete);
					$commission 	= money_format('%i', $this->payment_model->get_pagosonline_commission($paquete));
					
					$cobrar 		= $paquete + $commission;
					$this->session->set_flashdata('paymade', 'true');
					if(!empty($paquete)){
						if(round($monto) == round($cobrar) && $moneda == PAYMENT_CURRENCY){	
							$this->session->set_flashdata('exitoso',$this->lang->line('paysuccess'));
							redirect("landing/".$this->apps_model->get_app_uri($pay->id_app).'/thanks/pagosonline?estado_pago='.$this->lang->line('pol_'.$codigo_respuesta_pol).'&'.http_build_query($this->input->get()));
						}
						else
						{
							$this->session->set_flashdata('error',$this->lang->line('errormount'));
							redirect("landing/".$this->apps_model->get_app_uri($pay->id_app).'/saved?estado_pago='.$this->lang->line('pol_'.$codigo_respuesta_pol).'&'.http_build_query($this->input->get()));
						}
					}
				}
			
			//No aprovado
			}else if($this->get_state_pagosonline($estado_pol, $codigo_respuesta_pol)==3){
				$message = $this->lang->line('nopay');
				$this->session->set_flashdata('error',$message);
				$this->session->set_flashdata('paymade', 'true');
				if(!empty($pay)){
					//$make = $this->payment_model->make_payment($id_pay, $this->session->userdata('id_contact'), 3, TRUE);
				}
				redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/saved?estado_pago='.$this->lang->line('pol_'.$codigo_respuesta_pol).'&'.http_build_query($this->input->get()));
				
			//Hay que validar
			}else if($this->get_state_pagosonline($estado_pol, $codigo_respuesta_pol)==2){
				
				if(!empty($pay)){
					//$make = $this->payment_model->make_payment($id_pay, $this->session->userdata('id_contact'), 2, TRUE);
					
					$nro_paquetes 	= $this->apps_model->get_nro_by_package($pay->id_package);
					$paquete 		= $this->apps_model->create_price_by_package_suscription($pay->id_package, $pay->id_app, $this->session->userdata('id_contact'));
					$paquete 		= money_format('%i', $paquete);
					$commission 	= money_format('%i', $this->payment_model->get_pagosonline_commission($paquete));
					$cobrar 		= $paquete + $commission;
					if(!empty($paquete)){
						if(round($monto) == round($cobrar) && $moneda == PAYMENT_CURRENCY){
							//Envio de correo diciendole que esta validando
							$this->payment_model->email_payment_validando_subscribe($id_pay);
							
							$message = $this->lang->line('paymanual');	$this->session->set_flashdata('exitoso',$message);
							$this->session->set_flashdata('paymade', 'true');
							redirect("landing/".$this->apps_model->get_app_uri($pay->id_app).'/thanks/pagosonline?estado_pago='.$this->lang->line('pol_'.$codigo_respuesta_pol).'&'.http_build_query($this->input->get()));
						}
						else
						{
							$this->session->set_flashdata('paymade', 'true');
							$this->session->set_flashdata('error',$this->lang->line('errormount'));
							redirect("landing/".$this->apps_model->get_app_uri($pay->id_app).'/saved?estado_pago='.$this->lang->line('pol_'.$codigo_respuesta_pol).'&'.http_build_query($this->input->get()));
						}
					}
				
				}				
			}
		}else{
			$this->session->set_flashdata('error',$this->lang->line('errorpaygrave'));
			redirect("landing/".$this->apps_model->get_app_uri($this->session->userdata('id_app')).'/saved');
		}
	}
	
	
	/***
	* Genera la firma de regreso dependiendo los datos que envien
	* @param $datos es un array con los datos para generar la firma
	*/
	private function firma_regreso($datos){
		$llave_encripcion 	= "PK638j348334230qnW5VU5035P";
		$usuarioId 			= $datos['accountId'];
		$moneda				= $datos['currency'];
		$refVenta 			= $datos['referenceCode'];
		$cobrar				= $datos['ammount'];
		$estado_pol 		= $datos['estado_pol'];
		// ApiKey~merchantId~referenceCode~amount~currency
		$firma				= "$llave_encripcion~$usuarioId~$refVenta~$cobrar~$moneda~$estado_pol";
		$firma_codificada	= strtoupper(md5($firma));
		
		return $firma_codificada;
	}
	
	private function firma_de_ida($datos){
		$llave_encripcion 	= "L3WEC5qsn8EGA4x8eSst3N8V0Q";
		$usuarioId 			= "616516";
		$refVenta 			= $datos['referenceCode'];
		$valor 				= $datos['ammount'];
		$moneda				= $datos['currency'];
		// ApiKey~merchantId~referenceCode~amount~currency
		$firma				= "$llave_encripcion~$usuarioId~$refVenta~$valor~$moneda";
		$firma_codificada 	= md5($firma);//md5
		
		return $firma_codificada;
	}
	
	private function _view_ini_pay_pagosonline($data)
	{
		$this->load->view('hacerpago_pagosonline',$data);
	}
	
	function strToHex($string)
	{
		$hex='';
		for ($i=0; $i < strlen($string); $i++)
		{
			$hex .= dechex(ord($string[$i]));
		}
		return $hex;
	}
	
	function hexToStr($hex)
	{
		$string='';
		for ($i=0; $i < strlen($hex)-1; $i+=2)
		{
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
		}
		return $string;
	}
	
	/**
	* Asocia pin con aplicación y recarga los créditos
	**/
	function pin_app_assocciation(){
		$this->lang->load('pay');
		if(!$this->_login_in())
		{
			//The Special app redirect
			// $this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('marketplace');
		}
		
		$this->load->model('payment_model');
		$this->load->model('user_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txt-pin-pp', 'PIN', 'trim|required|xss_clean');
		
		if ($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('error',validation_errors());
			redirect('payment?pin=1');
		}

		$this->load->model('payment_model');
		$bResult	=	$this->payment_model->check_pin_landing($this->input->post('txt-pin-pp'));
		if(empty($bResult)){
				$this->session->set_flashdata('error',$this->lang->line('wrongpin'));
				redirect('payment?pin=1');
			}
			else{
				// $this->user_model->insert_new_user_app($this->session->userdata('user_id'));
				$this->payment_model->update_pin_used_user($this->session->userdata('user_id'), $this->input->post('txt-pin-pp'));
				$this->session->set_flashdata('exitoso',$this->lang->line('successpin'));

				$userCredits = $this->user_model->get_user_credit($this->session->userdata('user_id'));
				$pinPrice = $this->user_model->get_pin_price($this->input->post('txt-pin-pp'));
				
				$totalCredits = $userCredits[0]->credits + $pinPrice[0]->price;
				$resultCredits = $this->user_model->update_credit_user($this->session->userdata('user_id'), $totalCredits);
				$datos = array('pin' => '?pin=1' 
					);

				if($resultCredits == FALSE)
					$this->session->set_flashdata('error', "El PIN que ingresaste ya está en uso. Te invitamos a verificar tu código.");
				else
					$this->session->set_flashdata('exitoso',"El saldo ha sido recargado con éxito.");
					redirect('payment?pin=1', $datos);
			}

	}

	public function get_credit_user(){
				$this->load->model('user_model');
				$userCredits = $this->user_model->get_user_credit($this->session->userdata('user_id'));
				$userwappCredits = $this->user_model->get_userwapp_credit($this->session->userdata('user_id'));
				
				$totalCredits = $userCredits[0]->credits + $userwappCredits[0]->credits;
				$resultCredits = $this->user_model->update_credit_user($this->session->userdata('user_id'), $totalCredits);
	}
	
}