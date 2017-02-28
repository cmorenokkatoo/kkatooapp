<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_Model extends CI_Model {

	/*
	Funcion para obtener la información del usuario
	*/
	public function get_user($user_id = 0)
	{
		$this->db->select('*');  
		$this->db->where('id', $user_id);
		$result	=	$this->db->get('user')->row();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando de obtener información del usuario');
	}
	/*
	Funcion para obtener la información de las campañas que se encolaron
	*/
	public function get_campaign($user_id = 0)
	{
		$this->db->select('id, name, fecha, hora, minuto');
		$this->db->where('user_id', $user_id);
		$this->db->where('state', 1);
		$result	=	$this->db->get('campaign')->result();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando de obtener información de la campaña');
	}
	/*
	Funcion para saber el estado de una campaña en la cola
	*/
	public function get_queues($user_id = 0, $id_campaign = 0)
	{
		$this->db->select('id');
		$this->db->where('user_id', $user_id);
		$this->db->where('id_campaign', $id_campaign);
		$this->db->where('state', 0);
		$result	=	$this->db->get('queues')->result();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando información de una cola');
	}
	/*
	Funcion para ver la campaña total 
	*/
	public function get_campaign_queues($user_id = 0, $id_campaign = 0)
	{
		$this->db->select('contacts_user.name, queues.pais, queues.area, queues.phone, queues.state, queues.fecha as fecha_cola, queues.fecha_real, queues.hora_real, queues.minuto_real, queues.price_real, queues.marcado', FALSE);
		$this->db->from('queues');
		$this->db->join('contacts_user', 'queues.id_contact = contacts_user.id');
		$this->db->where('queues.user_id', $user_id);
		$this->db->where('queues.id_campaign', $id_campaign);
		$result	=	$this->db->get()->result();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando información de una cola');
	}
	/*
	Funcion para obtener la ciudad
	*/
	public function get_country()
	{
		$this->db->select('*');
		$result	= $this->db->get('country')->result();
		if(empty($result)){
			return FALSE;
		}else{
			return $result;
		}
		log_message('debug','Error tratando de eliger los paises');
	}
	/*
	Funcion para obtener la ciudad
	*/
	public function get_package($min=0)
	{
		$this->db->where('valor >= ', $min, false);
		$result	= $this->db->get('package')->result();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando de eliger los paquetes de pagos');
	}
	/*
	Funcion para obtener la ciudad
	*/
	public function get_city($id_city = 0)
	{
		$this->db->where('id', $id_city);
		$result	= $this->db->get('city')->row();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando de obtener una ciudad');
	}
	
	/*
	Funcion para obtener la ciudad
	*/
	public function update_user($user_id = 0, $data = array())
	{
		$this->db->where('id', $user_id);
		$result	= $this->db->update('user', $data);
		return  $result;
	}
	
	/*
	 * Funcion para crear un id de pago	de paypal
	*/
	public function register_payment($user_id = 0, $id_paquete = 0, $payment_type='')
	{
		$id_creado 	= $this->create_pago_id();
		$data 		= array(
							'user_id'		=> $user_id,
							'id_pago'  		=> $id_creado,
							'state'			=> 0,
							'id_package'	=> $id_paquete,
							'metodo'		=> $payment_type
							);
		$this->db->set('fecha','NOW()',FALSE);
		$result 	= $this->db->insert('pay', $data);
		if($result)
		{
			return $id_creado;
		}
		return FALSE;
		log_message('debug','Error tratando de iniciar el pago de creditos de un usuario');
	}
	
	
	/*
	Funcion para obtener la información de un paquete
	*/
	public function get_package_value($id_paquete = 0)
	{
		$this->db->where('id', $id_paquete);
		$result	= $this->db->get('package')->row();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
		log_message('debug','Error tratando de obtener información de un paquete');
	}
	/*
	Funcion para obtener el id del paquete
	*/
	public function get_payment($id_payment = 0, $user_id = 0, $suscriber = FALSE)
	{
		$this->db->where('id_pago', $id_payment);
		if($suscriber){
			// $this->db->where('id_contact', $user_id);
		}else{
			// $this->db->where('user_id', $user_id);
		}
		$result	= $this->db->get('pay')->row();
		if(empty($result))
		{
			return FALSE;
		}
		else
		{
			return $result;
		}
	}
	
	
	/*
	 * Funcion para crear un id de pago	 
	*/
	public function create_pago_id()
	{
		return uniqid();
	}

	public function make_payment($id_payment = 0, $user_id = 0, $state = 0, $suscriber = FALSE)
	{
		$data = array(
               			'state' => $state
               		);
		$this->db->where('id_pago', $id_payment);
		if($suscriber){
			//$this->db->where('id_contact', $user_id);	
		}else{
			//$this->db->where('user_id', $user_id);
		}
		$result = $this->db->update('pay', $data);
		return $result;
		log_message('debug','Error al confirmar el pago del usuario');
		
	}
	
	public function update_user_credits($user_id = 0, $credits = 0)
	{
		$this->db->set('credits', 'credits+'.$credits,false);
		$this->db->where('id', $user_id);
		$result = $this->db->update('user');
		return $result;
		// return number_format($result, 1);
	}
	
	
	/************** POCHO CÓDIGO ***************/
	/*
	 * Funcion para REGISTRAR un id de pago	para el suscriptor
	 * @param $contact_id es el id del contacto
	 * @param $id_paquete es el id del paquete para relacionar el precio
	 * @para $id_app es el id de la aplicación
	 * @return $id_creado es el id de el registro de pago creado o FALSE en caso de fallar
	*/
	public function register_payment_suscriber($contact_id = 0, $id_paquete = 0, $id_app = 0, $payment_type='')
	{
		$id_creado 	= $this->create_pago_id();
		$data 		= array(
							'id_contact'	=> $contact_id,
							'id_pago'  		=> $id_creado,
							'state'			=> 0,
							'id_package'	=> $id_paquete,
							'id_app'		=> $id_app,
							'metodo'		=> $payment_type
							);
		$this->db->set('fecha','NOW()',FALSE);
		$result 	= $this->db->insert('pay', $data);
		if($result)
		{
			return $id_creado;
		}
		return FALSE;
		log_message('debug','Error tratando de iniciar el pago de creditos de un usuario');
	}
	
	/*
	 * Funcion para ACTUALIZAR creditos de un contacto asociado a una aplicación
	 * @param $id_contact es el id del contacto
	 * @param $credits creditos a agregar
	 * @return $result en caso de actualizar las filas actualizadas, de lo contrario FALSE
	*/
	public function update_contact_app_credits($id_contact = 0, $id_wapp = 0, $credits = 0, $nro_paquetes = 0)
	{
		$this->db->set('updated', 'NOW()',FALSE);
		$this->db->set('credits', 'credits+'.$credits,false);
		//$this->db->set('packages', 'packages+'.$nro_paquetes,false);
		$this->db->where('id_contact', $id_contact);
		// $this->db->where('id_wapp', $id_wapp);
		$result = $this->db->update('contact_wapp');
		return $result;
	}
	
	/*
	 * Funcion para ACTUALIZAR creditos de un usuario asociado a una aplicación
	 * @param $id_user es el id del usuario
	 * @param $credits creditos a agregar
	 * @return $result en caso de actualizar las filas actualizadas, de lo contrario FALSE
	*/
	public function update_user_app_credits($id_user = 0, $price = 0, $credits = 0)
	{
		$this->db->set('updated', 'NOW()',FALSE);
		$this->db->set('credits', 'credits+'.$price,false);
		$this->db->set('state', '1');
		//$this->db->set('packages', 'packages+'.$nro_paquetes,false);
		$this->db->where('id_user', $id_user);
		//$this->db->where('id_wapp', $id_wapp);
		$result = $this->db->update('user_wapp');
		return $result;
	}
	
	/**
	* email de pago realizado
	*/
	public function email_payment($id_pay='', $valor='', $moneda=''){
		try{
			
			$pay = $this->get_payment($id_pay, '', TRUE);
			
			if($pay->user_id == 0){
				$this->load->model('contacts_model');
				$contact = $this->contacts_model->get_contact_info($pay->id_contact);
			}else{
				$this->load->model('user_model');
				$contact = $this->user_model->get_user_by_id($pay->user_id);
			}
			
			$data 	=	array(
						'pay_name'		=> ($pay->user_id == 0)?$contact->name_payment:$contact->fullname,
						'id_pay'		=> $id_pay,
						'date'			=> $pay->fecha,
						'state'  		=> $pay->state,
						'valor'	 		=> $valor,
						'moneda'		=> $moneda
						);

			$mensaje 	=	$this->load->view('email/'.$this->config->item('language').'/paypal_payment',$data,TRUE);
	
			$this->load->library('email');
			$this->email->from(KKATOO_EMAIL_INFO, 'Kkatoo Info');
			$this->email->to(($pay->user_id == 0)?$contact->email_payment:$contact->email);
			$this->email->subject(sprintf($this->lang->line('subjectcredits'), ucfirst($pay->metodo)));
			$this->email->message($mensaje);
			$this->email->send();
			return TRUE;
		}catch(ErrorenviarWelcome $e){
			log_message('debug', "Error enviando correo de créditos por suscripción");
			return FALSE;
		}
		/*
			<h3>Gracias!.</h3>
<h4><?php echo $pay_name; ?></h4>
<p>Haz realizado una recarga de Créditos</p>
<p><strong>Código de compra:</strong> <?php echo $id_pay ?></p>
<p><strong>Fecha de compra:</strong> <?php echo $date ?></p>
<p><strong>Estado: </strong><?php echo ($state==1)? "Transaccion exitosa":"La transacción no se completó"; ?></p>
<p><strong>Valor: </strong><?php echo $valor; ?></p>
<p><strong>Moneda: </strong><?php echo $moneda; ?></p>
<?php if($state == 1): ?>
<p>La recarga de créditos ha sido exitosa, desde ahora puede disfrutar de el servicio.</p>
<?php endif; ?>
		*/
	}
	
	/**
	 * Función para enviar un correo a un usuario que realiza una compra por suscripción
	 * @param $id_contact id del contacto que realiza la compra.
	 * @param $id_pago id del pago realizado para la tabla payments
	 */
	public function email_payment_paypal_subscribe($id_pago)
	{
		try
		{
			// date_default_timezone_set('Africa/Casablanca');
			$this->load->model('apps_model');
			$this->load->model('contacts_model');
			//echo $this->session->userdata('id_contact');
			$pay 			= $this->get_payment($id_pago, $this->session->userdata('id_contact'), TRUE);
			$package_amount = $this->apps_model->get_nro_by_package($pay->id_package);
			$package_price 	= $this->apps_model->create_price_by_package_suscription($pay->id_package, $pay->id_app, $pay->id_contact);
			
			$package_price = money_format('%i', $package_price);
			
			$app 			= $this->apps_model->get_app_data_by_id($pay->id_app);
			$contact 		= $this->contacts_model->get_contact_info($pay->id_contact);
			
			$this->lang->load('pay');
			
			$data 		=	array(
									'pay_name'		=> $contact->name_payment,
									'id_pay'		=> $id_pago,
									'pay_date'		=> date("F j, Y, g:i a", strtotime($pay->fecha)),
									'app_name'  	=> $app->title,
									'package_price' => $package_price,
									'pay_state'		=> $pay->state
									);

			$mensaje 	=	$this->load->view('email/'.$this->config->item('language').'/paypal_payment_subs',$data,TRUE);

			$this->load->library('email');
			$this->email->from(KKATOO_EMAIL_INFO, 'Kkatoo Info');
			$this->email->to($contact->email_payment);
			$this->email->subject(sprintf($this->lang->line('subjectcredits'), ucfirst($pay->metodo)));
			$this->email->message($mensaje);
			$this->email->send();
			log_message('debug', "Correo enviado de créditos por suscripción");
			return TRUE;
		}catch(ErrorenviarWelcome $e){
			log_message('debug', "Error enviando correo de créditos por suscripción");
			return FALSE;
		}
	}
	
	/**
	 * Función para enviar un correo a un usuario que realiza una compra por suscripción
	 * @param $id_contact id del contacto que realiza la compra.
	 * @param $id_pago id del pago realizado para la tabla payments
	 */
	public function email_payment_validando_subscribe($id_pago)
	{
		try
		{
			// date_default_timezone_set('Africa/Casablanca');
			
			//echo $this->session->userdata('id_contact');
			$pay 			= $this->get_payment($id_pago, '', TRUE);			
			if($pay->user_id == 0){
				$this->load->model('contacts_model');
				$contact = $this->contacts_model->get_contact_info($pay->id_contact);
			}else{
				$this->load->model('user_model');
				$contact = $this->user_model->get_user_by_id($pay->user_id);
			}
				
			
			$this->lang->load('pay');
			
			$data 		=	array(
									'name'		=> ($pay->user_id == 0)?$contact->name_payment:$contact->fullname
									);

			$mensaje 	=	$this->load->view('email/'.$this->config->item('language').'/validando_compra',$data,TRUE);

			$this->load->library('email');
			$this->email->from(KKATOO_EMAIL_INFO, 'Kkatoo Info');
			if($pay->user_id == 0){
				$this->email->to($contact->email_payment);
			}else{
				$this->email->to($contact->email);
			}
			$this->email->subject(sprintf($this->lang->line('subjectcredits'), ucfirst($pay->metodo)));
			$this->email->message($mensaje);
			$this->email->send();
			return TRUE;
		}catch(ErrorenviarWelcome $e){
			log_message('debug', "Error enviando correo de créditos por suscripción");
			return FALSE;
		}
	}
	
	
	/********************* PINES *************************/
	
	/**
	* Verifica que el pin ingresado esté en la base de datos
	* @param $id_pin id del pin
	* @param $pin es el código para verificar y sumar crédito
	*/
	function check_pin_landing($pin){
		
		try
		{
			$this->db->where('BINARY pin =', "BINARY '".$pin."'", FALSE, NULL);
			$this->db->where('state', 0);
			$this->db->select('*');
			$result	= $this->db->get('pines')->row();
			//echo $this->db->last_query();
			return $result;
		}catch(Exception $e){
			log_message('debug', "Error verificar pin => check_pin_landing");
			return FALSE;
		}
	}
	
	/**
	* Actualiza el credito del usuario y actualiza el pin para quedar cómo usado
	* @param $id_contact es el id del contacto
	* @param $id_app id de la aplicación
	* @param $pin es el código para verificar y sumar crédito
	*/
	function update_pin_used($id_contact, $pin){
		try
		{
			$pin_data = $this->check_pin_landing($pin);
			$user_updated = $this->update_contact_app_credits($id_contact, $pin_data->price, 0);
			
			if(!empty($user_updated)){
				
				$this->db->set('date_used', 'NOW()',FALSE);
				$this->db->set('state', 1);
				$this->db->set('id_contact', $id_contact);
				// $this->session->set_flashdata('error', "El PIN que ingresaste ya está en uso. Te invitamos a verificar tu código.");
				if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
					$this->db->set('HTTP_X_FORWARDED_FOR', $_SERVER['HTTP_X_FORWARDED_FOR']);
				}
				$this->db->set('REMOTE_ADDR', $_SERVER['REMOTE_ADDR']);
							

				$this->db->where('BINARY pin =', "BINARY '".$pin."'", FALSE, NULL);
				$this->db->where('state', 0);
				
				$result = $this->db->update('pines');
			}
			
			if(!empty($result)):
				$this->email_payment_pin($pin_data->id);
				return TRUE;
			else:
				return FALSE;
			endif;
			
		}catch(Exception $e){
			log_message('debug', "Error actualizando pin => update_pin_used");
			return FALSE;
		}
	}
	
	/**
	* Actualiza el credito del usuario y actualiza el pin para quedar cómo usado
	* @param $id_user es el id del usuario
	* @param $id_app id de la aplicación
	* @param $pin es el código para verificar y sumar crédito
	*/
	function update_pin_used_user($id_user = '', $pin = ''){
		try
		{
			$pin_data = $this->check_pin_landing($pin);
			
			if(!empty($pin_data)){
				$user_updated = $this->update_user_app_credits($id_user, $pin_data->price, 0);
			}
			
			if(!empty($user_updated)){
				
				$this->db->set('date_used', 'NOW()',FALSE);
				$this->db->set('state', 1);
				$this->db->set('id_user', $id_user);
				if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
					$this->db->set('HTTP_X_FORWARDED_FOR', $_SERVER['HTTP_X_FORWARDED_FOR']);
				}
				$this->db->set('REMOTE_ADDR', $_SERVER['REMOTE_ADDR']);
							
				
				$this->db->where('BINARY pin =', "BINARY '".$pin."'", FALSE, NULL);
				$this->db->where('state', 0);
				
				$result = $this->db->update('pines');
			}
			
			if(!empty($result)):
				//$this->email_payment_pin($pin_data->id);
				return TRUE;
			else:
				return FALSE;
			endif;
			
		}catch(Exception $e){
			log_message('debug', "Error actualizando pin => update_pin_used");
			return FALSE;
		}
	}
	
	/**
	* Obtiene la información de un pin por id de este
	* @param $id_ping
	* @return objecto con la iformación de el Pin 
	*/
	function get_pin_info($id_pin){
		try
		{
			$result = $this->db->get_where('pines', array('id' => $id_pin))->row();
			if(!empty($result))
			{
				return $result;
			}
			return FALSE;
		}catch(Exception $e){
			log_message('debug','Error al traer la información de una pin => get_pin_info');
			return FALSE;
		}
	}
	
	/**
	 * Función para enviar un correo a un usuario que realiza una recarga con Pin
	 * @param $id_contact id del contacto que realiza la compra.
	 * @param $id_pago id del pago realizado para la tabla payments
	 */
	public function email_payment_pin($id_pin)
	{
		try
		{
			// date_default_timezone_set('Africa/Casablanca');
			$this->load->model('apps_model');
			$this->load->model('contacts_model');

			$pin 			= $this->get_pin_info($id_pin);
			$app 			= $this->apps_model->get_app_data_by_id($pin->id_wapp);
			$contact 		= $this->contacts_model->get_contact_info($pin->id_contact);
			
			$this->lang->load('pay');
			
			$data 		=	array(
									'contact_name'	=> $contact->name_payment,
									'pin_code'		=> $pin->pin,
									'pin_date'		=> date("F j, Y, g:i a", strtotime($pin->date_used)),
									'app_name'  	=> $app->title,
									'pin_value' 	=> $pin->price,
									);

			$mensaje 	=	$this->load->view('email/'.$this->config->item('language').'/pin_payment_subs',$data,TRUE);

			$this->load->library('email');
			$this->email->from(KKATOO_EMAIL_INFO, 'Kkatoo Info');
			$this->email->to($contact->email_payment);
			$this->email->subject($this->lang->line('subjectcredits'));
			$this->email->message($mensaje);
			$this->email->send();
			log_message('debug', "Correo enviado de créditos por suscripción");
			return TRUE;
		}catch(ErrorenviarWelcome $e){
			log_message('debug', "Error enviando correo de créditos por suscripción");
			return FALSE;
		}
	}
	
	function get_current_dollar_to_cop(){
		if (function_exists('curl_init') AND $ch = curl_init())
		{
			$query = 'FromCurrency=USD&ToCurrency=COP';
			
			curl_setopt($ch, CURLOPT_URL, 'http://www.webservicex.net/CurrencyConvertor.asmx/ConversionRate');
			curl_setopt($ch, CURLOPT_TIMEOUT, 15);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Kkattoo codeigniter via cURL/PHP');
			$result = curl_exec($ch);
			curl_close($ch);
			//$this->load->model('payment_model');
			return (is_numeric($result))? $result : 1900;
		}
	}
	
	/**
	* Calcula la comisión por el monto que desea pagar
	* @param $monto es el dinero que va a pagar.
	* @return valor de la comisión para sumarle al monto.
	*/
	function get_paypal_commission($monto){
		if($monto > 0.00 && $monto <= 3000.00){
			return money_format('%i', $monto * 0.054 + 0.30); // 
		}else if($monto >  3000.00 && $monto <= 10000.00){
			return money_format('%i', $monto * 0.049 + 0.30); //
		}else if($monto >  10000.00 && $monto <= 100000.00){
			return money_format('%i', $monto * 0.047 + 0.30); //
		}elseif($monto >  100000.00){
			return money_format('%i', $monto * 0.044 + 0.30); //
		}
		//return 0;
	}
	
	/**
	* Calcula la comisión por el monto que desea pagar
	* @param $monto es el dinero que va a pagar.
	* @return valor de la comisión para sumarle al monto.
	*/
	function get_pagosonline_commission($monto){
		if($monto > 0.00 && $monto <= 2600.00){
			return money_format('%i', $monto * (0.034) + 0.37); //
		}else if($monto >  3000.00 && $monto <= 10000.00){
			return money_format('%i', $monto * (0.034) + 0.37); //
		}else if($monto >  10000.00 && $monto <= 100000.00){
			return money_format('%i', $monto * (0.034) + 0.37); //
		}elseif($monto >  100000.00){
			return money_format('%i', $monto * (0.034) + 0.37); //
		}
		// return 0;
	}
	
	function update_extra_values($id_pago = '', $info = ''){
		$this->db->set('extra_values', $info);
		$this->db->where('id_pago', $id_pago);
		$this->db->update('pay'); 
	}
	
}