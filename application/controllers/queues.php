<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Queues extends CI_Controller {

	 function __construct() {
        parent::__construct();
    }

	/**
	 * Index Page for this controller.
	 * Carga el Login de Kkatoo por defecto
	 */
	public function index()
	{
		date_default_timezone_set('Africa/Casablanca');
		$this->load->helper('date');
		$time = time();
		$this->set_queues_call();
		//echo now();
	}

	private function _cleanup()
	{
		date_default_timezone_set('Africa/Casablanca');
		$this->load->helper('date');
		$this->load->model('queues_model');
		$time		=	now() -	1800;
		$result 	=	$this->queues_model->delete_session($time);
	}

	/*
		Estados de las llamadas
		if
			state = 0 -->lista para llamar
			state = 1 -->llamada encolada
			state = 2 -->no se realiz� la llamada por falta de money
			state = 3 -->llamada realizada con exito
			state = 4 -->llamada colgada/no contestada
			state = 5 --> SMS con �xito
			state = 6 --> SMS no enviado
	*/
	public function set_queues_call()
	{

		$this->_set_delivered_call();
		$this->_set_queues_call_subscribe();
		$this->_set_bounced_call();
		$this->_cleanup();
		$utilidad 	= 	1;
		$publisher	=	1;
		ini_set('display_errors', 'on');
		ini_set('memory_limit', '-1');
		//set_time_limit(0);

		//ini_set('memory_limit','2G');
	   	// $memory = (int)ini_get("memory_limit");
	    //echo ' -> '.$memory;   die();
		$this->load->helper('date');

		$this->load->model('queues_model');
		//echo 'llamando odelo...';
		$result 	=	$this->queues_model->campaign_ready();
		if(!empty($result))
		{
			$aux = 0;
			$credito = 0;
			$app_credito = 0;
			foreach($result as $data)
			{
				date_default_timezone_set('Africa/Casablanca');
				$now 		= time();
				$gmt 		=gmt_to_local($now, $data->gmt, FALSE);
				$hora 		= mdate("%H", $gmt);
				$minu 		= mdate("%i", $gmt);
				$fecha 		= mdate("%Y-%m-%d", $gmt);
				if($data->fecha < $fecha)
				{
					if($aux!=$data->user_id)
					{
						if($data->uses_special_pines==1){
							if(!empty($data->app_credits)){
								$app_credito = $data->app_credits;
							}
						}

						$credito = $data->credits;
						$aux 	 = $data->user_id;
					}
					//Calcular minutos
					if (!empty($data->pais)) {
						$number .= $data->pais;
					}
					else{
						$number = $data->pais;
					}

					if($data->area != 0)
					{
						$number .=  $data->area;
					}
					$number 	.=  $data->phone;
					$resultado 	=	$this->queues_model->get_price_contact($number);
					//Fin calcular minutos
					if(!empty($resultado))
					{
						$minuto_estimado 	= (($data->seg_estimado/60)*($resultado->valor)*($data->publisher))+ ($resultado->valor);
					}
					else
					{
						$minuto_estimado	= 0;
					}
					if($data->uses_special_pines == 1){
						if($minuto_estimado <= $app_credito and $minuto_estimado != 0)
						{
							$app_credito = $app_credito - $minuto_estimado;
							//Encolar la llamada
							$resultadoState = $this->queues_model->state_queues($data->id_contact_campaign,1);
							if($resultadoState)
							{
								$this->_encolar($data->json);
							}
						}
						else
						{
							if($minuto_estimado <= $credito and $minuto_estimado != 0)
							{
								$credito = $credito - $minuto_estimado;
								//Encolar la llamada
								$resultadoState	=	$this->queues_model->state_queues($data->id_contact_campaign,1);
								if($resultadoState)
								{
									$this->_encolar($data->json);
								}
							}
							else
							{
								$this->queues_model->state_queues($data->id_contact_campaign,2);
							}
						}
					}else{
						if($minuto_estimado <= $credito and $minuto_estimado != 0)
						{
							$credito = $credito - $minuto_estimado;
							//Encolar la llamada

							$resultadoState	= $this->queues_model->state_queues($data->id_contact_campaign,1);
							if($resultadoState)
							{
								$this->_encolar($data->json);
							}
						}
						else
						{
							$this->queues_model->state_queues($data->id_contact_campaign,2);
						}
					}
				}
				elseif($data->fecha == $fecha)
				{
					if($data->hora < $hora)
					{
						if($aux!=$data->user_id)
						{
							if($data->uses_special_pines==1){
								if(!empty($data->app_credits)){
									$app_credito = $data->app_credits;
								}
							}

							$credito = $data->credits;
							$aux 	 = $data->user_id;
						}
						//Calcular minutos
						$number = $data->pais;
						if($data->area != 0)
						{
							$number .=  $data->area;
						}
						$number 	.=  $data->phone;
						$resultado 	=	$this->queues_model->get_price_contact($number);
						//Fin calcular minutos
						if(!empty($resultado))
						{
							$minuto_estimado 	= (($data->seg_estimado/60)*($resultado->valor)*($data->publisher))+ ($resultado->valor);
						}
						else
						{
							$minuto_estimado	= 0;
						}
						if($data->uses_special_pines == 1){
							if($minuto_estimado <= $app_credito and $minuto_estimado != 0)
							{
								$app_credito = $app_credito - $minuto_estimado;
								//Encolar la llamada

								$resultadoState	=	$this->queues_model->state_queues($data->id_contact_campaign,1);
								if($resultadoState)
								{
									$this->_encolar($data->json);
								}
							}
							else
							{
								if($minuto_estimado <= $credito and $minuto_estimado != 0)
								{
									$credito = $credito - $minuto_estimado;
									//Encolar la llamada

									$resultadoState	= $this->queues_model->state_queues($data->id_contact_campaign,1);
									if($resultadoState)
									{
										$this->_encolar($data->json);
									}
								}
								else
								{
									$this->queues_model->state_queues($data->id_contact_campaign,2);
								}
							}
						}else{
							if($minuto_estimado <= $credito and $minuto_estimado != 0)
							{
								$credito = $credito - $minuto_estimado;
								//Encolar la llamada

								$resultadoState	= $this->queues_model->state_queues($data->id_contact_campaign,1);
								if($resultadoState)
								{
									$this->_encolar($data->json);
								}
							}
							else
							{
								$this->queues_model->state_queues($data->id_contact_campaign,2);
							}
						}

					}
					elseif($data->hora == $hora and $data->minuto <= $minu)
					{
						if($aux!=$data->user_id)
						{
							if($data->uses_special_pines==1){
								if(!empty($data->app_credits)){
									$app_credito = $data->app_credits;
								}
							}

							$credito = $data->credits;
							$aux 	 = $data->user_id;
						}
						//Calcular minutos
						$number = $data->pais;
						if($data->area != 0)
						{
							$number .=  $data->area;
						}
						$number 	.=  $data->phone;
						$resultado 	=	$this->queues_model->get_price_contact($number);
						//Fin calcular minutos
						if(!empty($resultado))
						{
							$minuto_estimado 	= (($data->seg_estimado/60)*($resultado->valor)*($data->publisher))+ ($resultado->valor);
						}
						else
						{
							$minuto_estimado	= 0;
						}
						if($data->uses_special_pines == 1){
							if($minuto_estimado <= $app_credito and $minuto_estimado != 0)
							{
								$app_credito = $app_credito - $minuto_estimado;
								//Encolar la llamada

								$resultadoState	=	$this->queues_model->state_queues($data->id_contact_campaign,1);
								if($resultadoState)
								{
									$this->_encolar($data->json);
								}
							}
							else
							{
								if($minuto_estimado <= $credito and $minuto_estimado != 0)
								{
									$credito = $credito - $minuto_estimado;
									//Encolar la llamada
									//$this->_encolar($data->json);
									$resultadoState	=	$this->queues_model->state_queues($data->id_contact_campaign,1);
									if($resultadoState)
									{
										$this->_encolar($data->json);
									}
								}
								else
								{
									$this->queues_model->state_queues($data->id_contact_campaign,2);
								}
							}
						}else{
							if($minuto_estimado <= $credito and $minuto_estimado != 0)
							{
								$credito = $credito - $minuto_estimado;
								//Encolar la llamada

								$resultadoState	=	$this->queues_model->state_queues($data->id_contact_campaign,1);
								if($resultadoState)
								{
									$this->_encolar($data->json);
								}
							}
							else
							{
								$this->queues_model->state_queues($data->id_contact_campaign,2);
							}
						}
					}
				}
			}
		}
	echo date("r");
	}

	private function _set_delivered_call()
	{
		$utilidad 	= 	1;
		$publisher	=	1;

		// connect
		$connection = new AMQPConnection();

	$connection->connect();

		// open Channel
		$channel = new AMQPChannel($connection);

		// declare exchange
		$exchange = new AMQPExchange($channel);
		$exchange->setName(DELIVEREDX);
		$exchange->setType(DIRECT);
		//$exchange->declare();

		// create Queue
		$queue 	= new AMQPQueue($channel);

		$queue->setName(DELIVERED);
		$this->load->model('queues_model');

		while ($envelope = $queue->get(AMQP_AUTOACK))
		{
			$total = 0;
		    $data = json_decode($envelope->getBody());

		    //Obtener numero de telefono
		    $phone 	=	$this->queues_model->get_contact_user($data->id);
		    $number = 	0;
		    // echo var_dump($phone).' Numero de telefono<br/>';
		    if(!empty($phone))
		    {
			    if($phone->area == 0 or $phone->area="")
			    {
				    $number	=	$phone->pais.$phone->phone;
			    }
			    else
			    {
				    $number	=	$phone->pais.$phone->phone;
				}
				//Datos de la aplicaci�n
				$user_data_app = $this->queues_model->get_user_owner_by_app_id($phone->id_wapp);

				//Id del usuario due�o de la aplicaci�n
				$user_owner_app 	= (!empty($user_data_app))?$user_data_app->user_id:FALSE;

				//Validaci�n si utiliza pines especiales para descontar a estos
				$uses_special_pines = (!empty($user_data_app))?$user_data_app->uses_special_pines:0;

				// precio de la tabla price para el contacto es un objeto y la propiedad es valor
				$price_contact = $this->queues_model->get_price_contact($number);
				// porcentaje que se quiere ganar el publisher es un objeto y la propiedad es publisher
				$percent_publisher = $this->queues_model->get_value_price($data->id);
				// Calcular n�mero de mensajes de texto enviados en una campa�a

				// Calculo total del precio de la llamada
				$isSms = $this->queues_model->get_sms_state($phone->id_campaign);
				if ($isSms->tipo_sms == 0){
						 $minuto;
					if($data->data > 1 AND $data->data < 60)
						$minuto = 60;
					elseif($data->data > 61 AND $data->data < 120 )
						$minuto = 120;
					elseif($data->data < 180 AND $data->data > 121)
					 	$minuto = 180;
					elseif($data->data < 240 AND $data->data > 181)
					 	$minuto = 240;
					 elseif($data->data < 300 AND $data->data > 241)
					 	$minuto = 300;
					 elseif($data->data < 360 AND $data->data > 301)
					 	$minuto = 360;
					 elseif($data->data < 420 AND $data->data > 361)
					 	$minuto = 420;
					elseif ($data->data == 0)
					 	$minuto = 0;

							if ((($minuto/60)*($price_contact->valor)) == 0)
								$total = 0;
							else{
							$total = (($minuto/60)*($price_contact->valor));
								}

					$total = number_format($total, 4);
					$kkatoo_earnings = 0;
					if ($total == 0) {
						$publisher_earnings = 0;
					}else{
						$publisher_earnings = 0;
						$publisher_earnings = number_format($publisher_earnings, 4);
					}
				}
				else{
					$total = $price_contact->valorsms;
					$total = number_format($total, 4);
					$kkatoo_earnings = 0;
					if ($total == 0) {
						$publisher_earnings = 0;
					}else{
						$publisher_earnings = 0;
						$publisher_earnings = number_format($publisher_earnings, 4);
					}
				}

			    if($phone->tipo_wapp == 1){
					$result = $this->queues_model->update_credit_contact($phone->id_contact, $phone->id_wapp, $total);
				}else{
					$result = $this->queues_model->update_credit_user($data->id, $total, $uses_special_pines, $phone->id_wapp);
				}

				if($result){
					$data_credits = array(
						'id_queue' 			=> $phone->id, //Id de la cola
						'id_user_app_owner' => $user_owner_app, //Id del usuario due�o de la aplicaci�n
						'id_wapp' 			=> $phone->id_wapp, //Id de la aplicaci�n
						'valor' 			=> $publisher_earnings //Valor ganado
					);
					$result2 = 	$this->queues_model->sum_register_credit_user($data_credits);

					$data_credits = array(
						'id_queue' 			=> $phone->id, //Id de la cola
						'id_user_app_owner' => KKATOO_USER, // Id del usuario de kkatoo
						'id_wapp' 			=> $phone->id_wapp, //Id de la aplicaci�n que le esta dando la money
						'valor' 			=> $kkatoo_earnings //Valor ganado
					);
					$result2 = 	$this->queues_model->sum_register_credit_kkatoo($data_credits);
				}

				$isSms = $this->queues_model->get_sms_state($phone->id_campaign);
				if ($isSms->tipo_sms == 1) {
					$result1 	=	$this->queues_model->state_queues_ready(
			    															$data->id,
			    															5,
			    															$data->data,
			    															$data->CallState,
			    															$data->Hora,
			    															$data->Mins,
			    															$data->Fecha,
			    															$data->Interaction,
			    															$total
			    														);


				}
				else{
					$result1 	=	$this->queues_model->state_queues_ready(
			    															$data->id,
			    															3,
			    															$data->data,
			    															$data->CallState,
			    															$data->Hora,
			    															$data->Mins,
			    															$data->Fecha,
			    															$data->Interaction,
			    															$total
			    														);
				}
		    }
			else
			{
				$nuevoprueba = array(
										'json' => $data->id
										);
				$this->db->insert('prueba', $nuevoprueba);
			}
		}
	}

	private function _set_bounced_call()
	{
		// connect
		$connection = new AMQPConnection();
		$connection->connect();

		// open Channel
		$channel = new AMQPChannel($connection);

		// declare exchange
		$exchange = new AMQPExchange($channel);
		$exchange->setName(BOUNCEDX);
		$exchange->setType(DIRECT);
		//$exchange->declare();

		// create Queue
		$queue 	= new AMQPQueue($channel);
		$queue->setName(BOUNCED);
		$this->load->model('queues_model');
		while ($envelope = $queue->get(AMQP_AUTOACK))
		{
			$total = 0;
		    $data = json_decode($envelope->getBody());
		    $isSms = $this->queues_model->get_sms_state($phone->id_campaign);
		    if ($isSms->tipo_sms == 1) {
		    	$result1 = $this->queues_model->state_queues($data->id, 6);
		    }else{
		    $result 	=	$this->queues_model->state_queues($data->id, 4);
		    }
		}
	}
private function _encolar($message)
	{


		$data = array(
						'json'	=>	$message
						);
		$this->db->insert('prueba', $data);

		$connection = new AMQPConnection();
		$connection->connect();

		$channel = new AMQPChannel($connection);

		$exchange = new AMQPExchange($channel);
		$exchange->setName(ACTIVEX);
		$exchange->setType(DIRECT);

		$queue = new AMQPQueue($channel);
		$queue->setName(ACTIVE);
		$queue->setFlags(AMQP_PASSIVE|AMQP_DURABLE);

		// $message = $exchange->publish($message, ACTIVE);
		if ($queue->declareQueue() < 20000) {

			$message = $exchange->publish($message, ACTIVE);

		}elseif ($queue->declareQueue() >= 20000 and $queue->declareQueue() < 35001) {
			$exchange = new AMQPExchange($channel);
			$exchange->setName(ACTIVEX2);
			$exchange->setType(DIRECT);

			$queue = new AMQPQueue($channel);
			$queue->setName(ACTIVE2);
			$queue->setFlags(AMQP_PASSIVE|AMQP_DURABLE);

			if ($queue->declareQueue() >= 35001 and $queue->declareQueue() < 68001) {
				$exchange = new AMQPExchange($channel);
				$exchange->setName(ACTIVEX3);
				$exchange->setType(DIRECT);

				$queue = new AMQPQueue($channel);
				$queue->setName(ACTIVE3);
				$queue->setFlags(AMQP_PASSIVE|AMQP_DURABLE);

				if ($queue->declareQueue() >= 68001 and $queue->declareQueue() < 99999) {
					$exchange = new AMQPExchange($channel);
					$exchange->setName(ACTIVEX4);
					$exchange->setType(DIRECT);

					$queue = new AMQPQueue($channel);
					$queue->setName(ACTIVE4);
					$queue->setFlags(AMQP_PASSIVE|AMQP_DURABLE);

					if ($queue->declareQueue() >= 99999) {
						$exchange = new AMQPExchange($channel);
						$exchange->setName(ACTIVEX5);
						$exchange->setType(DIRECT);

						$queue = new AMQPQueue($channel);
						$queue->setName(ACTIVE5);
						$queue->setFlags(AMQP_PASSIVE|AMQP_DURABLE);

						$message = $exchange->publish($message, ACTIVE5);

					}
					else{
						$message = $exchange->publish($message, ACTIVE4);

					}

				}
				else{
					$message = $exchange->publish($message, ACTIVE3);
				}

			}else{
				$message = $exchange->publish($message, ACTIVE2);
			}
		}




}

// private function _encolar($message)
// 	{

// 		$data = array(
// 						'json'	=>	$message
// 						);
// 		$this->db->insert('prueba', $data);
// 		// connect
// 		$connection = new AMQPConnection();
// 		$connection->connect();

// 		// open Channel
// 		$channel = new AMQPChannel($connection);

// 		// declare exchange
// 		$exchange = new AMQPExchange($channel);
// 		$exchange->setName(ACTIVEX);
// 		$exchange->setType(DIRECT);
// 		//$exchange->declare();

// 		// create Queue
// 		$queue = new AMQPQueue($channel);
// 		$queue->setName(ACTIVE);
// 		$queue->setFlags(AMQP_PASSIVE|AMQP_DURABLE);
// 		//$queue->declare();
// 		// publish message
// 		$message = $exchange->publish($message, ACTIVE);
// }


	// POCHO CODIGO

	/**
	* inicia cola para aplicaciones por suscripci�n
	*/
	public function _set_queues_call_subscribe()
	{
		$utilidad 	= 	1;
		$publisher	=	1;
		ini_set('display_errors', 'on');
		$this->load->helper('date');
		$this->load->model('contacts_model');
		$this->load->model('queues_model');
		$result 	=	$this->queues_model->campaign_ready_subscription();
		if(!empty($result))
		{
			$aux = 0;
			$credito = 0;
			foreach($result as $data)
			{
				date_default_timezone_set('Africa/Casablanca');
				$now 		= time();
				$gmt 			= gmt_to_local($now, $data->gmt, FALSE); //$now;
				$hora 		= mdate("%H", $gmt);
				$minu 		= mdate("%i", $gmt);
				$fecha 		= mdate("%Y-%m-%d", $gmt);
				if($data->fecha < $fecha)
				{
					$contact_data = $this->contacts_model->get_contact_credit_and_packages($data->id_contact, $data->id_wapp);
					$credito = $contact_data->credits;
					//Calcular minutos
					$number = $data->pais;
					if($data->area != 0)
					{
						$number .=  $data->area;
					}
					$number 	.=  $data->phone;
					$resultado 	=	$this->queues_model->get_price_contact($number);
					//Fin calcular minutos
					if(!empty($resultado))
					{
						$minuto_estimado 	= (ceil($data->seg_estimado/60))*($resultado->valor)*($data->publisher);
					}
					else
					{
						$minuto_estimado	= 0;
					}
					if($minuto_estimado <= $credito and $minuto_estimado != 0)
					{
						$credito = $credito - $minuto_estimado;
						//Encolar la llamada

						$resultadoState	=	$this->queues_model->state_queues($data->id_contact_campaign,1);
						if($resultadoState)
						{
							$this->_encolar($data->json);
						}
					}
					else
					{
						$this->queues_model->state_queues($data->id_contact_campaign,2);
					}
				}
				elseif($data->fecha == $fecha)
				{
					if($data->hora < $hora)
					{
						$contact_data = $this->contacts_model->get_contact_credit_and_packages($data->id_contact, $data->id_wapp);
						$credito = $contact_data->credits;
						//Calcular minutos
						$number = $data->pais;
						if($data->area != 0)
						{
							$number .=  $data->area;
						}
						$number 	.=  $data->phone;
						$resultado 	=	$this->queues_model->get_price_contact($number);
						//Fin calcular minutos
						if(!empty($resultado))
						{
							$minuto_estimado 	= (ceil($data->seg_estimado/60))*($resultado->valor)*($data->publisher);
						}
						else
						{
							$minuto_estimado	= 0;
						}
						if($minuto_estimado <= $credito and $minuto_estimado != 0)
						{
							$credito = $credito - $minuto_estimado;
							//Encolar la llamada

							$resultadoState	=	$this->queues_model->state_queues($data->id_contact_campaign,1);
							if($resultadoState)
							{
								$this->_encolar($data->json);
							}
						}
						else
						{
							$this->queues_model->state_queues($data->id_contact_campaign,2);
						}
					}
					elseif($data->hora == $hora and $data->minuto <= $minu)
					{
						$contact_data = $this->contacts_model->get_contact_credit_and_packages($data->id_contact, $data->id_wapp);
						$credito = $contact_data->credits;
						//Calcular minutos
						$number = $data->pais;
						if($data->area != 0)
						{
							$number .=  $data->area;
						}
						$number 	.=  $data->phone;
						$resultado 	=	$this->queues_model->get_price_contact($number);

						//Fin calcular minutos
						if(!empty($resultado))
						{
							$minuto_estimado 	= (ceil($data->seg_estimado/60))*($resultado->valor)*($data->publisher);
						}
						else
						{
							$minuto_estimado	= 0;
						}
						if($minuto_estimado <= $credito and $minuto_estimado != 0)
						{
							$credito = $credito - $minuto_estimado;
							//Encolar la llamada

							$resultadoState	=	$this->queues_model->state_queues($data->id_contact_campaign,1);
							if($resultadoState)
							{
								$this->_encolar($data->json);
							}
						}
						else
						{
							$this->queues_model->state_queues($data->id_contact_campaign,2);
						}
					}
				}
			}
		}
	}
}
