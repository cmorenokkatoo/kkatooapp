<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appmanager_Model extends CI_Model {
	
	/**
	* Función que carga los usuarios de Pines de una aplicación por difusión
	* @param $id_wapp es el id de la aplicación
	*/
	
	function load_difusion_users_pines($id_wapp = ''){
		try{
			$this->db->select('user.id as id_user, user.email, user.fullname, country.phonecode, user.phone, user_wapp.credits');
			$this->db->from('user_wapp');
			$this->db->join('user','user.id = user_wapp.id_user');
			$this->db->join('country','user.id_country = country.id', 'left');
			$this->db->where('user_wapp.state', 1);
			$this->db->where('user_wapp.id_wapp', $id_wapp);
			
			
			$result = $this->db->get()->result();
			
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al traer los usuarios de una aplicación por difusión y pines');
			return FALSE;
		}                                                                                                                
	}
	
	/**
	* Funcion que carga los usuarios de Pines de una aplicación por difusión con filtro
	* @param $id_wapp es el id de la aplicación
	* @param $op es el operador a utilizar
	* @param $amount es la cantidad para realizar el filtro
	*/
	
	function load_difusion_users_pines_filter($id_wapp = '', $op='=', $amount=0){
		try{
			$this->db->select('user.id as id_user, user.email, user.fullname, country.phonecode, user.phone, user_wapp.credits');
			$this->db->from('user_wapp');
			$this->db->join('user','user.id = user_wapp.id_user');
			$this->db->join('country','user.id_country = country.id', 'left');
			$this->db->where('user_wapp.state', 1);
			$this->db->where('user_wapp.id_wapp', $id_wapp);
			$this->db->where('CAST(user_wapp.credits AS DECIMAL(10,6)) '.$op.' CAST('.$amount.' AS DECIMAL(10,6))');
			
			$result = $this->db->get()->result();
			
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al traer los usuarios con filtro para difusión y pines');
			return FALSE;
		}
	}
	
	/**
	* Optiene los créditos de un usuario por id de este
	*/
	function get_user_credits($id_user = ''){
		try{
			
			$this->db->select('user.credits');
			$this->db->from('user');
			$this->db->where('user.id', $id_user);
			
			$result = $this->db->get()->row();
			
			if($result){
				return $result->credits;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error de traer la libreria audio de una aplicación => get_library_records_by_app');
			return FALSE;
		}
	}
	
	/**
	* Optiene los créditos de un usuario asociado a una aplicación por difusión
	* @param $id_user es el id del usuario
	* @param $id_wapp es el id de la aplicación
	* @return un array con los datos del usuario
	*/
	function get_user_credits_diffusion($id_user = '', $id_wapp=''){
		try{
			
			$this->db->select('user_wapp.credits');
			$this->db->from('user_wapp');
			$this->db->where('user_wapp.id_user', $id_user);
			$this->db->where('user_wapp.id_wapp', $id_wapp);
			$result = $this->db->get()->row();
			if($result){
				return $result->credits;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al traer los creditos de un usuario asociado a una aplicación por difusión => get_user_credits_diffusion');
			return FALSE;
		}
	}
	
	/**
	* Actualiza los créditos de un usuario asociado a una aplicación
	* @param $id_user es el id del usuario
	* @param $id_wapp es el id de la aplicación
	* @param $amount es la cantidad a sumar
	*/
	function add_user_diffusion_pin_credits($id_user = '', $id_wapp = '', $amount=0){
		$this->db->set('updated', 'NOW()',FALSE);
		$this->db->set('credits', 'credits+'.$amount,false);
		$this->db->where('id_user', $id_user);
		$this->db->where('id_wapp', $id_wapp);
		$this->db->where('state', 1);
		$result = $this->db->update('user_wapp');
		if($this->db->affected_rows()>0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	/**
	* Agrega créditos a un contacto de una aplicación de suscripción
	* @param $id_contact es el id del contacto
	* @param $id_wapp es el id de la aplicación
	* @param $amount es el crédito que se va a sumar
	*/
	function add_contact_suscription_credits($id_contact = '', $id_wapp = '', $amount = 0){
		$this->db->set('updated', 'NOW()',FALSE);
		$this->db->set('credits', 'credits+'.$amount,false);
		$this->db->where('id_contact', $id_contact);
		$this->db->where('id_wapp', $id_wapp);
		$this->db->where('state', 1);
		$result = $this->db->update('contact_wapp');
		if($this->db->affected_rows()>0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	
	/**
	* Resta creditos de un usuario luego de recargarlo a una persona
	* @param $id_user es el id del usuario en cuestión
	* @param $amount es la cantidad a descontar
	*/
	function discount_user_credits($id_user = '', $amount = 0){
		$this->db->set('credits', 'credits-'.$amount,false);
		$this->db->where('id', $id_user);
		$result = $this->db->update('user');
		if($this->db->affected_rows()>0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	/**
	* Suma creditos de un usuario
	* @param $id_user es el id del usuario en cuestión
	* @param $amount es la cantidad a descontar
	*/
	function add_user_credits($id_user = '', $amount = 0){
		$this->db->set('credits', 'credits+'.$amount,false);
		$this->db->where('id', $id_user);
		$result = $this->db->update('user');
		if($this->db->affected_rows()>0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	/**
	* Actualiza datos de la tabla de usuario con aplicación por difusión
	* @param $id_user es el id del usuario en cuestión
	* @param $amount es la cantidad a descontar
	*/
	function update_data_user_app_difusion($id_user = '', $id_wapp = '', $data=array()){
		try{
			$this->db->where('id_user', $id_user);
			$this->db->where('id_wapp', $id_wapp);
			$result	=	$this->db->update('user_wapp',$data);
			if($this->db->affected_rows()>0){
				return TRUE;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al tratar de actualizar datos en => update_data_user_app_difusion');
			return FALSE;
		}
	}
	
	/**
	* Funcion que carga los contactos de una aplicación por suscripción con filtro o sin filtro
	* @param $id_wapp es el id de la aplicación
	* @param $op es el operador a utilizar
	* @param $amount es la cantidad para realizar el filtro
	*/
	
	function load_suscriptors_contacts_filter($id_wapp = '', $filter = false,  $op='=', $amount=0){
		try{
			$this->db->select('contacts_user.id as id_contact, contacts_user.email, contacts_user.name, contacts_user.indi_pais as phonecode, contacts_user.phone, contact_wapp.credits');
			$this->db->from('contact_wapp');
			$this->db->join('contacts_user','contacts_user.id = contact_wapp.id_contact');
			$this->db->where('contact_wapp.state', 1);
			$this->db->where('contact_wapp.id_wapp', $id_wapp);
			if($filter){
				$this->db->where('CAST(contact_wapp.credits AS DECIMAL(10,6)) '.$op.' CAST('.$amount.' AS DECIMAL(10,6))');
			}
			
			$result = $this->db->get()->result();
			
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en esta función load_suscriptors_contacts_filter');
			return FALSE;
		}
	}
	
	/**
	* Optiene el tipo de aplicación y si es difusión con pin o no.
	* @param $id_wapp es el id de la aplicación
	* @return un objeto con los valores tipo de aplicación y uses_special_pines
	*/
	function get_app_type_and_special_pin($id_wapp = ''){
		try{
			
			$this->db->select('wapp.tipo, wapp.uses_special_pines');
			$this->db->from('wapp');
			$this->db->where('wapp.id', $id_wapp);
			$result = $this->db->get()->row();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función get_app_type_and_special_pin');
			return FALSE;
		}
	}
	
	/**
	* Optiene los usuarios que han usado la aplicación tan siquiera una sola vez
	* @param $id_wapp es el id de la aplicación
	*/
	function get_user_uses_app($id_wapp = '', $filter = false, $op = '=', $amount = ''){
		try{
			$this->db->select('user.id as id_user, user.email, user.fullname, country.phonecode, user.phone, COUNT(campaign.user_id) as nro_usos');
			$this->db->join('user', 'user.id = campaign.user_id');
			$this->db->join('country','user.id_country = country.id', 'left');
			$this->db->where('campaign.id_wapp', $id_wapp);
			$this->db->where('campaign.state', 1);
			
			if($filter) 
			$this->db->having('nro_usos '.$op.' ', $amount);
			
			$this->db->group_by('campaign.user_id');
			$this->db->order_by('nro_usos', 'desc');
			$this->db->from('campaign');
			$result = $this->db->get()->result();
			
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función get_user_uses_app');
			return FALSE;
		}
	}
	
	//ESTADISTICAS
	/**
	* Optiene las últimas campanas operadas de una aplicación por suscripción
	* @param $id_wapp es el id de la aplicación
	* @param $user_id es el id del usuario
	*/
	function get_last_campaigns_suscriptions($id_wapp = '', $user_id=''){
		try{
			
			$this->db->select('campaign.id, campaign.name, campaign.fecha,
			(SELECT COUNT(queues.state) FROM queues WHERE queues.id_campaign = campaign.id and queues.state = 0) as CUENTA0,
			(SELECT COUNT(queues.state) FROM queues WHERE queues.id_campaign = campaign.id and queues.state = 1) as CUENTA1,
			(SELECT COUNT(queues.state) FROM queues WHERE queues.id_campaign = campaign.id and queues.state = 2) as CUENTA2,
			(SELECT COUNT(queues.state) FROM queues WHERE queues.id_campaign = campaign.id and queues.state = 3) as CUENTA3,
			(SELECT COUNT(queues.state) FROM queues WHERE queues.id_campaign = campaign.id and queues.state = 4) as CUENTA4');
			$this->db->from('campaign');
			$this->db->where('campaign.id_wapp', $id_wapp);
			$this->db->where('campaign.user_id', $user_id);
			$this->db->where('campaign.state', 1);
			$this->db->limit(20, 0);
			$result = $this->db->get()->result();
			
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función get_last_campaigns_suscriptions');
			return FALSE;
		}
	}
	
	/**
	* Carga el nro de suscriptores por fecha, si se envia una fecha especifica se toma desde ese día hacia atrás 7 días.
	* @param $date fecha para cambiar los datos de la gráfica
	* @param $id_wapp es el id de la aplicación
	*/
	function suscribers_evolution($id_wapp = '', $date=''){
		try{
			$dates_array = $this->getLastNDays(7, $date);
			$to_return = array();
			
			foreach($dates_array as $dat):
				$this->db->select('COUNT(id) AS cuenta, DATE(created) AS fecha');
				$this->db->from('contact_wapp');
				$this->db->where('state', 1);
				$this->db->where('id_wapp', $id_wapp);
				$this->db->where('DATE(created)', $dat);
				$this->db->group_by('fecha');
				$result = $this->db->get()->row();
				//echo $this->db->last_query();
				if($result){
					$to_return[] = $result;
				}else{
					$to_return[] = (object)array('cuenta'=>0, 'fecha'=>$dat);
				}
			endforeach;
			//die();
			if(!empty($to_return)){
				return $to_return;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función suscribers_evolution');
			return FALSE;
		}
	}
	
	/**
	* Carga el nro de usos de una aplicación en los últimos 7 días, si se agrega como parametro una fecha especifica 
	* entonces contara los días desde esa fecha hacia atrás.
	* @param $date fecha para cambiar los datos de la gráfica
	* @param $id_wapp es el id de la aplicación
	*/
	function aplication_uses($id_wapp = '', $date=''){
		try{
			$dates_array = $this->getLastNDays(7, $date);
			$to_return = array();
			
			foreach($dates_array as $dat):
				$this->db->select('COUNT(id) AS cuenta, DATE(fecha) AS fecha');
				$this->db->from('campaign');
				$this->db->where('state', 1);
				$this->db->where('id_wapp', $id_wapp);
				$this->db->where('DATE(fecha)', $dat);
				$this->db->group_by('fecha');
				$result = $this->db->get()->row();
				//echo $this->db->last_query();
				if($result){
					$to_return[] = $result;
				}else{
					$to_return[] = (object)array('cuenta'=>0, 'fecha'=>$dat);
				}
			endforeach;
			//die();
			if(!empty($to_return)){
				return $to_return;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función aplication_uses');
			return FALSE;
		}
	}
	
	/**
	* Optiene el número de campañas realizadas para una aplicación
	( param @id_wapp es el id de la aplicación en cuestión
	*/
	function nro_crated_campaigns($id_wapp = ''){
		try{
			
			$this->db->select('COUNT(id) as cuenta');
			$this->db->from('campaign');
			$this->db->where('id_wapp', $id_wapp);
			$this->db->where('state', 1);
			$result = $this->db->get()->row();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función nro_crated_campaigns');
			return FALSE;
		}
	}
	
	/**
	* Nro de llamadas realizadas de la aplicación
	* @param $id_wapp es el id de la aplicación
	*/
	function maden_calls($id_wapp=''){
		try{
			
			$this->db->select('COUNT(id) as cuenta');
			$this->db->from('queues');
			$this->db->where('id_wapp', $id_wapp);
			$this->db->where('state', 3);
			$result = $this->db->get()->row();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función maden_calls');
			return FALSE;
		}
	}
	/**
	* Nro de llamadas realizadas de la aplicación
	* @param $id_wapp es el id de la aplicación
	*/
	function maden_sms($id_wapp=''){
		try{
			
			$this->db->select('COUNT(id) as cuenta');
			$this->db->from('queues');
			$this->db->where('id_wapp', $id_wapp);
			$this->db->where('state', 5);
			$result = $this->db->get()->row();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función maden_sms');
			return FALSE;
		}
	}
	/**
	* Es considerado un usuario registrado de una aplicación, quien la usa tan siquiera una sola vez o quien 
	* realmente se registro en la aplicación utilizando un Pin
	* @param $id_wapp es el id de la aplicación
	*/
	function user_registered_app($id_wapp = ''){
		try{
			
			$result = $this->db->query("
				SELECT COUNT(*) AS cuenta FROM(
				SELECT DISTINCT campaign.user_id
				FROM campaign
				WHERE campaign.id_wapp = $id_wapp AND campaign.state = 1
				
				UNION ALL
				
				SELECT DISTINCT user_wapp.id_user
				FROM user_wapp
				WHERE user_wapp.id_wapp = $id_wapp AND user_wapp.state = 1 AND user_wapp.id_user NOT IN (SELECT user_id FROM campaign c2 WHERE c2.id_wapp = 26)
				) AS CUENTAS
			")->row();
			
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función user_registered_app');
			return FALSE;
		}
	}
	
	/**
	* Optiene las gancias del usuario por el id de este
	* @param $id_user el id del usuario
	*/
	function get_user_earnings_by_userid($id_user = ''){
		try{
			
			$this->db->select('ROUND(SUM(valor), 2) as cuenta', FALSE);
			$this->db->from('user_earnings');
			$this->db->where('id_user_app_owner', $id_user);
			$this->db->where('redeemed', 0);
			$this->db->group_by('id_user_app_owner');
			$result = $this->db->get()->row();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función maden_calls');
			return FALSE;
		}
	}
	
	/**
	* Inicia el proceso de cargarle créditos a un publisher
	* Los estados del pago son, 0 no se a iniciado el pago, 1 en proceso de pago, 2 pagado
	* @param $data array de datos para iniciar el pago
	*/
	function ini_pago_to_user($data=array()){
		try{
			$result = $this->db->insert('pay_publisher', $data);
			if($result)
			{
				return $this->db->insert_id();
			}
			return FALSE;
		}catch(Exception $e){
			log_message('debug','Error en la función ini_pago_sum_app');
			return FALSE;
		}
	}
	
	/**
	* Les asigna un id de pago a las ganancias recibidas por aplicación
	* Los estados son 0 => Sin Redimir, 1 => En proceso y 2 => Redimidos
	*/
	function update_pago_by_app($id_wapp =  '', $id_user = '', $id_ini=''){
		try{
			$this->db->set('time_redeemed', 'NOW()',FALSE);
			$this->db->set('redeemed', 1);
			$this->db->set('id_pay_publisher', $id_ini);
			$this->db->where('id_wapp', $id_wapp);
			$this->db->where('id_pay_publisher IS NULL');
			$this->db->where('id_user_app_owner', $id_user);
			$result = $this->db->update('user_earnings');
			if($this->db->affected_rows()>0){
				return TRUE;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función update_pago_by_app');
			return FALSE;
		}
	}
	
	/**
	* Les asigna un id de pago a las gananancias recibidas por usuario
	* Los estados son 0 => Sin Redimir, 1 => En proceso y 2 => Redimidos
	*/
	function update_pago_by_user($id_user = '', $id_ini=''){
		try{
			$this->db->set('time_redeemed', 'NOW()',FALSE);
			$this->db->set('redeemed', 1);
			$this->db->set('id_pay_publisher', $id_ini);
			$this->db->where('id_pay_publisher IS NULL');
			$this->db->where('id_user_app_owner', $id_user);
			$result = $this->db->update('user_earnings');
			if($this->db->affected_rows()>0){
				return TRUE;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función update_pago_by_app');
			return FALSE;
		}
	}
	
	/**
	* Generar el pago al publisher
	*/
	function generate_payment($id_pay_publisher=''){
		$this->lang->load('appmanager');
		$pay_publisher = $this->get_pay_publisher_by_id($id_pay_publisher);
		if(!empty($pay_publisher)){
			try{
				$the_amount = $this->get_user_earnings_by_id_pay_publisher($pay_publisher->id);
				$the_amount = $the_amount->cuenta;
				$updated_pay_publisher = FALSE;
				
				$user_info  = $this->get_user_info($pay_publisher->id_user);
				
				//RA y RU son recargas una por aplicación y la otra por usuario.
				//TA y TU son transacciones, una por aplicación y la otra por usuario.
				if($this->update_user_credits_by_amount($pay_publisher->id_user, $the_amount)){
					
					$estado = '';
					
					if($pay_publisher->tipo == RA || $pay_publisher->tipo == RU){
						//Se actualiza en la tabla pay_publisher la cantidad a redimir y el estado a 2 que es pagado por ser recarga de créditos.
						$updated_pay_publisher = $this->update_amount_to_redeem($pay_publisher->id, $the_amount, 2);
						$pay_publisher->estado = 2;
					}
					if($pay_publisher->tipo == TA || $pay_publisher->tipo == TU){
						//Se actualiza en la tabla pay_publisher la cantidad a redimir y el estado a 1 ya que es una transacción y todavía falta hacerla así que queda como pendiente
						$updated_pay_publisher = $this->update_amount_to_redeem($pay_publisher->id, $the_amount, 1);
						$pay_publisher->estado = 1;
					}
					if($updated_pay_publisher){
						
						if($pay_publisher->estado==0){
							$estado = $this->lang->line('estado_0');
						}elseif($pay_publisher->estado==1){
							$estado = $this->lang->line('estado_1');
						}elseif($pay_publisher->estado==2){
							$estado = $this->lang->line('estado_2');
						}
						
						$data = array(
								'name' 				=> $user_info->fullname,
								'amount' 			=> money_format('%i', $the_amount),
								'id'				=> $pay_publisher->id,
								'tipo'				=> ($pay_publisher->tipo ==RA || $pay_publisher->tipo == RU)? $this->lang->line('type_redeem_recharge'):$this->lang->line('type_redeem_transaction'),
								'tipo_real'			=> $pay_publisher->tipo,
								'entidad'			=> $pay_publisher->entidad,
								'tipo_de_cuenta'	=> $pay_publisher->tipo_de_cuenta,
								'numero_de_cuenta'	=> $pay_publisher->nro_cuenta,
								'valor'				=> $pay_publisher->valor_redimido,
								'estado'			=> $estado,
								'date' 				=> $pay_publisher->date,	
								'email'				=> $user_info->email
							);
						
						$redeemed = '';
						if($pay_publisher->tipo == RA || $pay_publisher->tipo == RU){
							$redeemed = 2;
							//Se actualiza el log de ganancias dependiendo el estado, 2 para las recargas y 1 para las transacciones
							if($this->update_all_redeemed_earns($pay_publisher->id, $redeemed)){
								//Enviar correo
								$this->send_redeem_email($data);
								
								return TRUE;
							}
						}elseif(($pay_publisher->tipo == TA || $pay_publisher->tipo == TU) && $the_amount > 0){
							//Enviar correo
							$this->send_redeem_email($data);
							
							return TRUE;
						}
					}else{
						return FALSE;
					}
				}else{
					return FALSE;
				}
			}catch(Exception $e){
				log_message('debug','Error en la función generate_payment');
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	/**
	* Optiene la información del usuario por su id
	* @param $id_user es el id del usuario
	*/
	function get_user_info($id_user=''){
		try{
			return $this->db->get_where('user', array('id'=>$id_user))->row();
		}catch(Exception $e){
			log_message('debug','Error en la función get_user_info');
			return FALSE;
		}
	}
	
	/**
	* Envia email infromando que ha realizado una redemción de crédito
	* @param $data es un array con los datos a enviar a la vista de e-mail
	*/
	function send_redeem_email($data=array()){
		try{			
			$mensaje 	=	$this->load->view('email/'.$this->config->item('language').'/report_earns_redeemed',$data,TRUE);
			
			$this->load->library('email');
			$this->email->from(KKATOO_EMAIL_INFO, 'Kkatoo Info');
			$this->email->to($data["email"]);
			$this->email->subject($this->lang->line('subject_email_redeem'));
			$this->email->message($mensaje);
			$this->email->send();
			
			$this->subject_email_redeem_kkatoo($data);
			
			return TRUE;
			
		}catch(Exception $e){
			log_message('debug','Error en la función send_redeem_email');
			return FALSE;
		}
	}
	
	/**
	* Enviar email a Kkatoo si se va a redimir crédito por cuenta bancaria
	*/
	function subject_email_redeem_kkatoo($data=array()){
		try{			
			$mensaje 	=	$this->load->view('email/spanish/report_earns_redeemed',$data,TRUE);

			$this->load->library('email');
			$this->email->from($data["email"], $data["name"]);
			$this->email->to(KKATOO_EMAIL_INFO);
			if($data["tipo_real"]==RA || $data["tipo_real"]==RU):
				$this->email->subject($this->lang->line('subject_redeem_credits'));
			else:
				$this->email->subject($this->lang->line('subject_redeem_bank'));
			endif;
			$this->email->message($mensaje);
			$this->email->send();
			//log_message('debug', "Correo enviado de kréditos redimidos");
			return TRUE;
			
		}catch(Exception $e){
			log_message('debug','Error en la función send_redeem_email');
			return FALSE;
		}
	}
	
	/**
	* Actualiza la cantidad a redimir al usuario
	* El los estados son 0 cuando no se ha iniciado el pago, 1 cuando ya se inició el pago y 2 cuando ya se realizó el pago.
	*/
	function update_amount_to_redeem($id_pay_publisher = '', $amount = '', $state = ''){
		try{
			$this->db->set('valor_redimido', $amount);
			$this->db->set('estado', $state);
			$this->db->set('date_payment', 'NOW()',FALSE);
			$this->db->where('id', $id_pay_publisher);
			$result = $this->db->update('pay_publisher');
			if($this->db->affected_rows()>0){
				return TRUE;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función update_amount_to_redeem');
			return FALSE;
		}
	}
	
	/**
	* Actualiza las ganancias redimidas con el estado 2 si esta ya cancelado o 1 si esta en proceso
	*/
	function update_all_redeemed_earns($id_pay_publisher = '', $state=''){
		try{
			$this->db->set('redeemed', $state);
			$this->db->set('time_redeemed', 'NOW()',FALSE);
			$this->db->where('id_pay_publisher', $id_pay_publisher);
			$result = $this->db->update('user_earnings');
			if($this->db->affected_rows()>0){
				return TRUE;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función update_amount_to_redeem');
			return FALSE;
		}
	}
	
	/**
	* Actualiza el credito del usiaro
	*/
	function update_user_credits_by_amount($id_user='', $amount=''){	
		try{
			$this->db->set('credits','credits+'.$amount, FALSE);
			$this->db->where('id', $id_user);
			$result = $this->db->update('user');
			if($this->db->affected_rows()>0){
				return TRUE;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función update_user_credits_by_amount');
			return FALSE;
		}
	}
	
	/**
	* Optiene los datos de la tabla pay_publisher por id
	* @param $id es el id de los datos a optener
	*/
	function get_pay_publisher_by_id($id=''){
		try{
			$result = $this->db->get_where('pay_publisher', array('id'=>$id))->row();
			if(!empty($result)){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función get_pay_publisher_by_id');
			return FALSE;
		}
	}
	
	/**
	* Optiene las gancias del usuario por la aplicación actual
	* @param $id_wapp es el id de la aplicación
	* @param $id_user 
	*/
	function get_user_earnings_by_app($id_wapp = '', $id_user = ''){
		try{
			$this->db->select('ROUND(SUM(valor), 4) as cuenta', FALSE);
			$this->db->from('user_earnings');
			$this->db->where('id_wapp', $id_wapp); 
			$this->db->where('id_user_app_owner', $id_user);
			$this->db->where('redeemed', 0);
			$this->db->group_by('id_wapp');
			$result = $this->db->get()->row();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función get_user_earnings_by_app');
			return FALSE;
		}
	}
	
	/**
	* Optiene las gancias del usuario id_pay_publisher
	* @param $id_pay_publisher es el id del pago generado para el publisher
	*/
	function get_user_earnings_by_id_pay_publisher($id_pay_publisher=''){
		try{
			
			$this->db->select('ROUND(SUM(valor), 2) as cuenta', FALSE);
			$this->db->from('user_earnings');
			$this->db->where('id_pay_publisher', $id_pay_publisher);
			$this->db->where('redeemed', 1);
			$this->db->group_by('id_pay_publisher');
			$result = $this->db->get()->row();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función maden_calls');
			return FALSE;
		}
	}
	
	
	/**
	* Optiene los contenidos más usados para una aplicación por difusión
	* @id_wapp es el id de la aplicación
	* @id_user es el id del usuario
	*/
	function more_used_content($id_wapp = '', $id_user = FALSE){
		try{
			$this->db->select('
				text_speech.id as text_id, 
				text_speech.name as text_name, 
				text_speech.text as text_text, 
				text_speech.user_id as text_user, 
				text_speech.voice as text_voice_id, 
				text_speech.*,  
				
				content_wapp.id as content_wap_id, 
				content_wapp.fecha, 
				content_wapp.tipo as content_tipo, 
				content_wapp.id_content as content_id_content, 
				
				audio.*, 
				audio.name as audio_name, 
				audio.id as audio_id, 
				audio.tipo as audio_tipo,
				
				CASE
				  WHEN text_speech.id IS NULL THEN
						(SELECT COUNT(campaign.id_audio) FROM campaign WHERE campaign.id_audio = audio.id)
					ELSE
					  (SELECT COUNT(campaign.id_text_speech) FROM campaign WHERE campaign.id_text_speech = text_speech.id)
				END AS count_this,
				
				CASE
				  WHEN text_speech.id IS NULL THEN
						(SELECT campaign.fecha FROM campaign WHERE campaign.id_audio = audio.id ORDER BY campaign.fecha DESC limit 1)
					ELSE
					  (SELECT campaign.fecha FROM campaign WHERE campaign.id_text_speech = text_speech.id ORDER BY campaign.fecha DESC limit 1)
				END AS last_date
			', FALSE);
			
			$this->db->from('content_wapp');
			
			if($id_user){
				$this->db->join('text_speech','text_speech.id = content_wapp.id_content and content_wapp.tipo = "text" and text_speech.user_id = '.$id_user, 'LEFT');
			}else{
				$this->db->join('text_speech','text_speech.id = content_wapp.id_content and content_wapp.tipo = "text"', 'LEFT');
			}
			$this->db->join('voice','text_speech.voice = voice.id', 'LEFT');
			
			if($id_user){
				$this->db->join('audio','audio.id = content_wapp.id_content and content_wapp.tipo = "audio" and audio.user_id = '.$id_user, 'LEFT');
			}else{
				$this->db->join('audio','audio.id = content_wapp.id_content and content_wapp.tipo = "audio"', 'LEFT');
			}
			
			$this->db->where('content_wapp.id_wapp', $id_wapp);
			$where = "(text_speech.state = 1 OR audio.state = 1)";

			$this->db->where($where);
			
			$this->db->order_by("count_this", "desc");
			$this->db->order_by("content_wapp.fecha", "desc");
			$content = $this->db->get()->result();
			
			if($content){
				return $content;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función more_used_content');
			return FALSE;
		}
	}
	
	/**
	* Optine los datos de la tabla de saldos redimidos
	* @param $id_user es el id del usuario para traer los datos asociados a él.
	*/
	function get_redeemed_all($id_user=''){
		try{
			$this->lang->load('appmanager');
			$this->db->select('
					pay_publisher.*, 
					CASE
					  WHEN pay_publisher.estado = 0 THEN
							"'.$this->lang->line('estado_0').'"
					  WHEN pay_publisher.estado = 1 THEN
							"'.$this->lang->line('estado_1').'"
					  WHEN pay_publisher.estado = 2 THEN
							"'.$this->lang->line('estado_2').'"
					END AS state,
					
					CASE
					  WHEN pay_publisher.tipo = "'.RA.'" OR pay_publisher.tipo = "'.RU.'" THEN
							"'.$this->lang->line('type_redeem_recharge').'"
					 WHEN pay_publisher.tipo = "'.TA.'" OR pay_publisher.tipo = "'.TU.'" THEN
							"'.$this->lang->line('type_redeem_transaction').'"
					END AS type,
					
				', FALSE);
			$this->db->order_by('pay_publisher.date', 'DESC');
			$result = $this->db->get_where('pay_publisher', array('id_user'=>$id_user))->result();
			if(!empty($result)){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función get_redeemed_all');
			return FALSE;
		}
		
	}
	
	/**
	* Optiene las N fechas anteriores de una fecha dada con formato
	* @param $dasy el N número de días que se van a toamr hacia atrás.
	* @param $date la fecha de inicio
	* @param $format el formato para las fechas por default esta 'Y-m-d'
	*/
	function getLastNDays($days, $date='', $format = 'Y-m-d'){
		if(empty($date)){
			$date = date($format, time());
		}
		//$m = date("m"); $de= date("d"); $y= date("Y");
		$dateArray = array();
		for($i=0; $i<=$days-1; $i++){
			$dateArray[] = date($format, strtotime($date."-$i days")); 
		}
		return array_reverse($dateArray);
	}

	function publisher_ganancias(){
		
	}
	
}
?>