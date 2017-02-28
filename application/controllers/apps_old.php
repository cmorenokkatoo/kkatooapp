<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apps extends CI_Controller {
	
	 /**
	 * Funcion publica para cargar el index del paso 1
	 */
	public function index()
	{
		ini_set('display_errors', 'on');
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$this->lang->load('apps');
		$uri_app 	=	$this->uri->segment(2);
		
		if($uri_app !== FALSE)
		{
			$this->load->model('apps_model');
			$result 	=	$this->apps_model->get_uri_app($uri_app);
			if(empty($result))
			{
				$this->session->set_flashdata('error',$this->lang->line('appnoexist'));
				redirect('marketplace');
			}
			else
			{
				if($this->check_if_can_use($result)){
					
					if($this->uri->segment(3)===FALSE)
					{
						$this->_load_step_one($result);
					}
					elseif($this->uri->segment(3)==2)
					{
						if(!$this->_login_in())
						{
							//The Special app redirect
							$this->_return_to_special_url();
							
							$this->session->set_flashdata('error',$this->lang->line('initapp'));
							redirect('login/login');
						}
						else
						{
							$this->_load_step_two($result);	
						}
					}
					elseif($this->uri->segment(3)==3)
					{
						if(!$this->_login_in())
						{
							//The Special app redirect
							$this->_return_to_special_url();
							
							$this->session->set_flashdata('error',$this->lang->line('initapp'));
							redirect('login/login');
						}
						else
						{
							$this->_load_step_three($result);	
						}
					}
					else
					{
						$this->session->set_flashdata('error',$this->lang->line('appnofound'));
						redirect('marketplace');
					}
				}else{
					//The Special app redirect
					$this->_return_to_special_url();
					
					$this->session->set_flashdata('error',$this->lang->line('notpermitedappsubs'));
					redirect('marketplace');
				}
			}
		}
		else
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('appnofound'));
			redirect('marketplace');
		}
	}
	
	/**
	* Verifica si el usuario puede utilizar la aplicación
	*/
	function check_if_can_use($result){
		$this->load->model('apps_model');
		if(!empty($result->id)){
			if($this->_login_in()){
				$tipo = $this->apps_model->get_app_type_if_can_use($result->id);
				if($tipo==1){
					if($this->apps_model->check_app_user($result->id, $this->session->userdata('user_id'))){
						return TRUE;
					}else{
						return FALSE;
					}
				}elseif($tipo==2 || $tipo==0){
					//$this->apps_model->create_permisions_list($result->id);
					if($result->uses_special_pines){
						$user_exists = $this->apps_model->check_user_exits_pines($this->session->userdata("user_id"), $result->id);
						$app_owner = $this->apps_model->check_app_user($result->id, $this->session->userdata('user_id'));
						if(!empty($user_exists) || !empty($app_owner)){
							return TRUE;
						}else{
							$this->lang->load('pay');
							$this->session->set_flashdata('error',$this->lang->line('youshouldassociatepin'));
							redirect('payment?pin=1&prtrn=apps/'.$result->uri);
							die();
						}
					}else{
						return TRUE;
					}
				}
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}

	
	/************************************** Fuciones privadas a partir de aqui *************************************/
	
	/*
	 * Funcion privada para cargar el paso 3 de las llamadas de kkatoo
	*/
	private function _load_step_three($result)
	{
		$id_campaign		= 0;
		$duration			= 0;
		$total_credits		= 0;
		//Variables de cobro
		$utilidad			= 1;
		$publisher			= (($result->price)/100);   //formula one pasar de porcentaje a decimal
		
		$this->load->model('apps_model');
		$credits_app		= ($result->uses_special_pines==1)?$this->apps_model->get_user_app_credits($this->session->userdata('user_id'), $result->id):'0';
		//Fin Variables de ocbro
		//$total_price		= 0;
		$this->load->helper('date');
		$this->load->helper('cookie');
		if($this->_login_in())
		{
			$campaign 	= $this->apps_model->get_campaign($this->session->userdata('user_id'),$result->id);
			if(empty($campaign))
			{
				$this->session->set_flashdata('error',$this->lang->line('nocampaigncreate'));
				redirect('apps/'.$result->uri);	
			}
			else
			{
				$aux 	= $this->apps_model->get_audio_text_campaign($this->session->userdata('user_id'),$campaign->id);
				$id_campaign  		= $campaign->id;
				if(!empty($aux))
				{
					$this->session->set_flashdata('error',$this->lang->line('erroraudioexist'));
					redirect('apps/'.$result->uri.'/2');	
				}
				else
				{
					$duration		= 	$this->_duration_audio($campaign);
					$total_price	=	$this->_price_estimated($id_campaign, $duration);
					$total_price	=	$total_price; //subformula one // * $utilidad * $publisher
				}
			}
			$total_credits	= $this->_get_credit_user();
		}
		if($total_price == 0)
		{
			$this->session->set_flashdata('error',$this->lang->line('errorcall'));
			redirect('apps/'.$result->uri.'/2');	
		}
		// date_default_timezone_set('Africa/Casablanca');
		$data				= array(
										'credits'			=> 	$total_credits,
										'total_price'		=>	$total_price,
										'name_campaign'		=> 	$campaign->name,
										'id_campaign'		=> 	$id_campaign,
										'duration'			=> 	$duration,
										'app' 				=> 	$result,
										'credits_app'		=>  $credits_app
									);
		$this->session->set_flashdata('url',$result->uri);
		$this->_view_step_three($data);
	}
	/*
	 * Funcion privada para cargar el paso 2 de las llamadas de kkatoo
	*/
	private function _load_step_two($result)
	{
		$id_campaign		= 0;
		$total_credits		= 0;
		$audiosApp 			= array();
		$audiocampaign		= array();
		$textcampaign		= array();
		$fields				= array();
		$voice 				= array();
		$name_campaign		= "";		
		
		$this->load->model('apps_model');
		$credits_app		= ($result->uses_special_pines==1)?$this->apps_model->get_user_app_credits($this->session->userdata('user_id'), $result->id):'0';
		
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('login/login');
		}
		
		$campaign 	= $this->apps_model->get_campaign($this->session->userdata('user_id'),$result->id);
		if(empty($campaign))
		{
			$this->session->set_flashdata('error',$this->lang->line('nocampaigncreate'));
			redirect('apps/'.$result->uri);	
		}
		else
		{
			$id_campaign  			= $campaign->id;
			$name_campaign			= $campaign->name;
			
			if(empty($name_campaign)){
				$this->session->set_flashdata('error',$this->lang->line('no_campaign_name'));
				redirect('apps/'.$result->uri);	
				die();
			}
			
			$contacts_campaign 	= $this->apps_model->get_contact_campaign($campaign->id,$this->session->userdata('user_id'));
			if(empty($contacts_campaign))
			{
				$this->session->set_flashdata('error',$this->lang->line('nocontactscampaign'));
				redirect('apps/'.$result->uri);	
			}
			else
			{
				$audiosApp 		= $this->apps_model->get_audio_app($this->session->userdata('user_id'),$result->id);
				$pagiAudios 	= $this->apps_model->total_audio_app();
				$audiocampaign 	= $this->apps_model->get_audio_campaign($this->session->userdata('user_id'),$id_campaign);
				$textcampaign 	= $this->apps_model->get_text_campaign($this->session->userdata('user_id'),$id_campaign);
				$fields			= $this->apps_model->get_fields($result->id);
				$voice			= $this->apps_model->get_voice();
			}
		}
		$total_credits	= $this->_get_credit_user();
		$data		= array(
								'credits'			=> $total_credits,
								'name_campaign'		=> $name_campaign,
								'audios'            => $audiosApp,
								'app' 				=> $result,
								'audiocampaign'     => $audiocampaign,
								'textcampaign'     	=> $textcampaign,
								'id_campaign'		=> $id_campaign,
								'fields'			=> $fields,
								'voice'				=> $voice,
								'marcado'			=> json_decode($campaign->marcado),
								'total'				=> ceil(($pagiAudios->total)/3),
								'credits_app'		=> $credits_app
								
							);
		$this->session->set_flashdata('url',$result->uri);
		
		/**
		* Intro y Cierre
		*/
		if($campaign->intro)
			$data['intro'] = $this->apps_model->get_intro_close($campaign->intro);
		
		if($campaign->cierre)
			$data['cierre'] = $this->apps_model->get_intro_close($campaign->cierre);
		
		/** Información para la librería de contenidos **/
		// DATOS DE LA LIBRERÍA DE CONTENIDOS
		$this->load->model('wizard_model');
		$data["audios"] 	= $this->wizard_model->get_library_audios_by_app($result->id, $this->session->userdata('user_id'));
		$data["records"] 	= $this->wizard_model->get_library_records_by_app($result->id, $this->session->userdata('user_id'));
		$data["texts"] 		= $this->wizard_model->get_library_texts_by_app($result->id, $this->session->userdata('user_id'));
		$data["library"]	= $this->wizard_model->get_library_content($result->id, $this->session->userdata('user_id'));
		$data["dynamic"] 	= $this->wizard_model->get_dynamic_fields($result->id);
		
		$data['voice'] 	  	= $this->apps_model->get_voice();
		
		$this->_view_step_two($data);
	}
	/**
	 * Funcion privada para cargar el paso 1 de las llamadas de kkatoo
	*/
	private function _load_step_one($result, $datacsv = array(), $csvname = "")
	{
		
		$get = ($this->input->get_post('page'))?$this->input->get_post('page'):1;
		
		//echo $result->tipo;
		$contacts   				= array();
		$contacts_campaign	= array();
		$country						= array();
		$groups							= array();
		$campaign 					= array();
		$name_campaign			= "";
		$id_campaign				= 0;
		$total   						= 0;
		$total_credits			= 0;
		$fields							= array();
		$totalC 						= 0;
		$totalCC						= 0;			
		
		$this->load->model('apps_model');
		$credits_app		= ($result->uses_special_pines==1)?$this->apps_model->get_user_app_credits($this->session->userdata('user_id'), $result->id):'0';
		
		if($this->_login_in())
		{
			$campaign 	= $this->apps_model->get_campaign($this->session->userdata('user_id'),$result->id);
			$country		= $this->apps_model->get_country();
			$groups			= $this->apps_model->get_group($this->session->userdata('user_id'));
			$fields			= $this->apps_model->get_fields($result->id);
			
			// verifica si existe la campana
			if(empty($campaign))
			{
				// Si no existe inicializa la campana
				$datos			= array(
										 'id_wapp' => $result->id,
										 'user_id' => $this->session->userdata('user_id')
										);
				//Si un usuario esta conectado se le inicia la campaña automaticamente
				$ini_campaign 	= $this->apps_model->ini_campaign($datos);
				if(!empty($ini_campaign))
				{
					$id_campaign = $ini_campaign;
				}
				
				/*if($result->tipo == 1){
					$contacts 	= $this->_get_contacts_subscribe($result->id,$fields);
				}else{
					$contacts 	= $this->_get_contacts($result->id,$fields);
				}*/
				
			}
			else
			{
				
				$id_campaign  = $campaign->id;
				
				//$campaign_f   = $this->apps_model->get_campaign_contacts($campaign->id);
								
				/*if(empty($campaign_f))
				{
					
					if($result->tipo == 1){
						$contacts 	= $this->_get_contacts_subscribe($result->id,$fields);
					}else{
						$contacts 	= $this->_get_contacts($result->id,$fields);
					}
					
				}	
				else
				{
					
					//Busca los contactos filtrando por la campaña
					$contacts 	= $this->_get_contacts_filter_campaign(
																		$campaign->id,
																		$result->id,
																		$fields
																	 );
				}*/
				
				//Consulta los usuarios asociados a la campaña que alguna vez inició
				$contacts_campaign = $this->apps_model->get_contact_campaign($campaign->id,$this->session->userdata('user_id'), true);
				$totalCC 	= $this->apps_model->foundRows()->cuantos;
				
				if(!empty($contacts_campaign))
				{
					$total = count($contacts_campaign);
				}
				$name_campaign = $campaign->name;
			}
			
			$contacts = $this->apps_model->get_mega_filtro_contacts($this->session->userdata('user_id'), $id_campaign, $result->id);
			$totalC 	= $this->apps_model->foundRows()->cuantos;
			
			$total_credits	= $this->_get_credit_user();
		}
		
		$this->load->helper('string');
		$this->load->library( 'ci_google' );
		$data		= array(
							'credits'					=> $total_credits,
							'name_campaign'   => $name_campaign,
							'contacts_to'			=> $totalCC,
							'id_campaign'			=> $id_campaign,
							'app' 						=> $result,
							'fields' 					=> $fields,
							'csvname' 				=> $csvname,
							'country' 				=> $country,
							'groups' 					=> $groups,
							'contacts'  			=> $contacts,
							'datacsv'	  			=> $datacsv,
							'contacts_campaign'	=> $contacts_campaign,
							'link_gp'   				=> $this->ci_google->get_url_connect("contactos"),
							'credits_app'				=> $credits_app,
							'id_wapp'						=> $result->id,
							'totalC'						=> $totalC,
							'totalCC'						=> $totalCC
							);
		$this->session->set_flashdata('url',$result->uri);
		$this->_view_step_one($data);
	}
	/**
	 * Funcion privada para traer los contactos filtrados
	*/
	private function _get_contacts_filter_campaign($campaign, $wapp, $fields, $filter = 0, $group = 0, $id_field = 0, $ope = "", $criterio = "")
	{
		$this->session->keep_flashdata('url');
		$contacts = array();
		$nocontacts = 	array();
		$tipo = $this->apps_model->get_app_type($wapp);
		//Si no se va a filtrar se muestran todos los contactos que no han sido asignados a una campaña
		if($filter == 0)
		{
			if($tipo == 1){
				$contacts 	=  	$this->apps_model->get_contacts_filter_campaign_wapp_subscribe($campaign,$wapp);
			}else{
				$contacts 	=  	$this->apps_model->get_contacts_filter_campaign_wapp($this->session->userdata('user_id'),$campaign,$wapp);
			}
			if(empty($contacts))
			{	
				if($tipo == 1){
					$nocontacts 				=  	$this->apps_model->get_contacts_filter_campaign_no_wapp_subscribe($wapp,
																							FALSE,
																							$campaign
																						);
				}else{
					$nocontacts =  	$this->apps_model->get_contacts_filter_campaign_no_wapp(
																							$this->session->userdata('user_id'),
																							FALSE,
																							$campaign
																						);
				}
				
			}
			else
			{
				$ids = array();
				foreach($contacts as $c)
				{
					$ids[] = $c->id;
				}
				if($tipo == 1){
					$nocontacts =  	$this->apps_model->get_contacts_filter_campaign_no_wapp_subscribe($wapp,
																							TRUE,
																							$campaign,
																							$ids
																						);
				}else{
					$nocontacts =  	$this->apps_model->get_contacts_filter_campaign_no_wapp(
																							$this->session->userdata('user_id'),
																							TRUE,
																							$campaign,
																							$ids
																						);
				}
			}
		}
		
		// Else, parte sin suscripcion
		
		else //Se realizan los filtros respectivos y se traen los contactos con los criterios y no asignados a la campaña
		{
			$ids_filter 	=  	$this->apps_model->get_contacts_filter_campaign_wapp_filter(
																					$this->session->userdata('user_id'),
																					$campaign,
																					$wapp,
																					$group,
																					$id_field,
																					$ope,
																					$criterio
																				);
			$ids = array();
			if(!empty($ids_filter))
			{
				foreach($ids_filter as $c)
				{
					$ids[] = $c->id;
				}
				if($tipo == 1){
					$contacts 	=  	$this->apps_model->get_contacts_filter_campaign_wapp_subscribe(
																					$campaign,
																					$wapp,
																					$ids
																				);
				}else{
					$contacts 	=  	$this->apps_model->get_contacts_filter_campaign_wapp(
																					$this->session->userdata('user_id'),
																					$campaign,
																					$wapp,
																					$ids
																				);
				}
			}
		}
		$data 		= 	array();
		$aux 		= 	0;
		$i 			= 	0;
		
		if(!empty($contacts))
		{
			foreach ($contacts as $conta)
			{
				if($aux != $conta->id)
				{
					$i = count($data);
				}
				$aux = $conta->id;
				
				$data[$i]['name'] 				= $conta->name;
				$data[$i]['id']   				= $conta->id;
				$data[$i]['indi_pais']			= $conta->indi_pais;
				$data[$i]['indi_area']  		= $conta->indi_area;
				$data[$i]['phone']  			= $conta->phone;
				$data[$i]['user_id']  			= $conta->user_id;
				if(isset($conta->credits) && isset($conta->packages)){
					$data[$i]['credits']  			= $conta->credits;
					$data[$i]['packages']  			= $conta->packages;
				}
				$data[$i][$conta->name_fields] 	= $conta->valor;
			}
		}
		$i = count($data);
		if(!empty($nocontacts))
		{
			foreach ($nocontacts as $nocontact)
			{
				$data[$i]['name'] 		= $nocontact->name;
				$data[$i]['id']   		= $nocontact->id;
				$data[$i]['indi_pais']	= $nocontact->indi_pais;
				$data[$i]['indi_area']  = $nocontact->indi_area;
				$data[$i]['phone']  	= $nocontact->phone;
				$data[$i]['user_id']  	= $nocontact->user_id;
				if(isset($nocontact->credits) && isset($nocontact->packages)){
					$data[$i]['credits']  	= $nocontact->credits;
					$data[$i]['packages']  	= $nocontact->packages;
				}
				if(!empty($fields))
				{
					foreach($fields as $camp)
					{
						$data[$i][$camp->name_fields] = ""; 
					}
				}
				$i++;
			}
		}
		return $data;
	}
	
	/**
	 * Funcion privada para Get Contacts sin filtrar
	*/
	private function _get_contacts($wapp = 0 , $fields = array(), $filter = 0, $group = 0, $id_field = 0, $ope = "", $criterio = "")
	{
		$this->session->keep_flashdata('url');
		$contacts 	= 	array();
		$nocontacts = 	array();
		//Si no se va a filtrar se traen todos los datos normales
		if($filter == 0)
		{
			$contacts 	=  	$this->apps_model->get_contacts_wapp($this->session->userdata('user_id'), $wapp);
			if(empty($contacts))
			{
				$nocontacts 			=  	$this->apps_model->get_contacts_no_wapp($this->session->userdata('user_id'), FALSE);
			}
			else
			{
				$ids = array();
				foreach($contacts as $c)
				{
					$ids[] = $c->id;
				}
				$nocontacts 			=  	$this->apps_model->get_contacts_no_wapp($this->session->userdata('user_id'), TRUE, $ids);
			}
		}
		else //Si filtra se envian los datos a la función para que se encargue de hacer el filtrado de los usuarios
		{
			if($id_field)
			{
				$ids_filter 	=  	$this->apps_model->get_contacts_wapp_filter(
															$this->session->userdata('user_id'), 
															$wapp,
															$group,
															$id_field,
															$ope,
															$criterio
														);
			}
			else
			{
				$ids_filter 	=  	$this->apps_model->get_contacts_wapp_filter_group(
																						$this->session->userdata('user_id'), 
																						$group
																					);
			}
			$ids = array();
			if(!empty($ids_filter))
			{
				foreach($ids_filter as $c)
				{
					$ids[] = $c->id;
				}
				if($id_field)
				{
					$contacts 				=  	$this->apps_model->get_contacts_wapp($this->session->userdata('user_id'), $wapp,$ids);
				}
				else
				{
					$nocontacts 			=  	$this->apps_model->get_contacts_where_in($this->session->userdata('user_id'), $ids);
				}
			}
		}
		$data 		= 	array();
		$aux 		= 	0;
		$i 			= 	0;
		if(!empty($contacts))
		{
			foreach ($contacts as $conta)
			{
				if($aux != $conta->id)
				{
					$i = count($data);
				}
				$aux = $conta->id;
				
				$data[$i]['name'] 		= $conta->name;
				$data[$i]['id']   		= $conta->id;
				$data[$i]['indi_pais']	= $conta->indi_pais;
				$data[$i]['indi_area']  = $conta->indi_area;
				$data[$i]['phone']  	= $conta->phone;
				$data[$i]['user_id']  	= $conta->user_id;
				if(isset($conta->credits) && isset($conta->packages)){
					$data[$i]['credits']  	= $conta->credits;
					$data[$i]['packages']  	= $conta->packages;
				}
				$data[$i][$conta->name_fields] = $conta->valor;
			}
		}
		$i = count($data);
		if(!empty($nocontacts))
		{
			foreach ($nocontacts as $nocontact)
			{
				$data[$i]['name'] 		= $nocontact->name;
				$data[$i]['id']   		= $nocontact->id;
				$data[$i]['indi_pais']	= $nocontact->indi_pais;
				$data[$i]['indi_area']  = $nocontact->indi_area;
				$data[$i]['phone']  	= $nocontact->phone;
				$data[$i]['user_id']  	= $nocontact->user_id;
				if(isset($nocontact->credits) && isset($nocontact->packages)){
					$data[$i]['credits']  	= $nocontact->credits;
					$data[$i]['packages']  	= $nocontact->packages;
				}
				if(!empty($fields))
				{
					foreach($fields as $camp)
					{
						$data[$i][$camp->name_fields] = ""; 
					}
				}
	
				$i++;
			}
		}
		return $data;
	}
	
	/**
	 * Funcion privada para para Get Contacts sin filtrar por suscripcion
	*/
	private function _get_contacts_subscribe($wapp = 0 , $fields = array(), $filter = 0, $group = 0, $id_field = 0, $ope = "", $criterio = "")
	{
		$this->session->keep_flashdata('url');
		$contacts = array();
		$nocontacts = 	array();
		//Si no se va a filtrar se traen todos los datos normales
		if($filter == 0)
		{
			$contacts 			=	$this->apps_model->get_contacts_wapp_subscribe($wapp); 
			if(empty($contacts))
			{
				$nocontacts =  	$this->apps_model->get_contacts_no_wapp_subscribe($wapp, FALSE);
			}
			else
			{
				$ids = array();
				foreach($contacts as $c)
				{
					$ids[] = $c->id;
				}
				// Filtrado contactos ya en la campana
				$nocontacts 			=  	$this->apps_model->get_contacts_no_wapp_subscribe($wapp, TRUE, $ids);
			}
		}
		
		// PARTE SIN LA VUELTA DE SUSCRIPCIÓN EL ELSE
		
		else //Si filtra se envian los datos a la función para que se encargue de hacer el filtrado de los usuarios
		{
			if($id_field)
			{
				$ids_filter 	=  	$this->apps_model->get_contacts_wapp_filter(
															$this->session->userdata('user_id'), 
															$wapp,
															$group,
															$id_field,
															$ope,
															$criterio
														);
			}
			else
			{
				
					$ids_filter 	=  	$this->apps_model->get_contacts_wapp_filter_group_subscribe(
																							$wapp,
																							$group
																						);
			}
			$ids = array();
			if(!empty($ids_filter))
			{
				foreach($ids_filter as $c)
				{
					$ids[] = $c->id;
				}
				if($id_field)
				{
					//Los importantes para la páginación son estos.
					$contacts 				=  	$this->apps_model->get_contacts_wapp_subscribe($wapp,$ids);
				}
				else
				{
					//Los importantes para la páginación son estos.
					$nocontacts 			=  	$this->apps_model->get_contacts_where_in_subscribe($wapp, $ids);
				}
			}
		}
		$data 		= 	array();
		$aux 		= 	0;
		$i 			= 	0;
		if(!empty($contacts))
		{
			foreach ($contacts as $conta)
			{
				if($aux != $conta->id)
				{
					$i = count($data);
				}
				$aux = $conta->id;
				
				$data[$i]['name'] 		= $conta->name;
				$data[$i]['id']   		= $conta->id;
				$data[$i]['indi_pais']	= $conta->indi_pais;
				$data[$i]['indi_area']  = $conta->indi_area;
				$data[$i]['phone']  	= $conta->phone;
				$data[$i]['user_id']  	= $conta->user_id;
				if(isset($conta->credits) && isset($conta->packages)){
					$data[$i]['credits']  	= $conta->credits;
					$data[$i]['packages']  	= $conta->packages;
				}
				$data[$i][$conta->name_fields] = $conta->valor;
			}
		}
		$i = count($data);
		if(!empty($nocontacts))
		{
			foreach ($nocontacts as $nocontact)
			{
				//echo $nocontact->id;
				$data[$i]['name'] 		= $nocontact->name;
				$data[$i]['id']   		= $nocontact->id;
				$data[$i]['indi_pais']	= $nocontact->indi_pais;
				$data[$i]['indi_area']  = $nocontact->indi_area;
				$data[$i]['phone']  	= $nocontact->phone;
				$data[$i]['user_id']  	= $nocontact->user_id;
				if(isset($nocontact->credits) && isset($nocontact->packages)){
					$data[$i]['credits']  	= $nocontact->credits;
					$data[$i]['packages']  	= $nocontact->packages;
				}
				if(!empty($fields))
				{
					foreach($fields as $camp)
					{
						$data[$i][$camp->name_fields] = ""; 
					}
				}
	
				$i++;
			}
		}
		return $data;
	}
	/******************************************
	FUNCIONES AJAX DEL CONTROL APPS
	*******************************************/
	/**
	* Funcion agregar un audio grabado por el usuario
	*/
	public function add_audio_record()
	{
		$this->session->keep_flashdata('url');
		if(!$this->_login_in())
		{
			echo 'cod=0&messa=No ha iniciado session';
			exit();
		}
		$config['upload_path'] 		= './public/audios/';
		$config['allowed_types'] 	= 'wav|mp3';
		$config['encrypt_name'] 	=  TRUE;
		$config['max_size']			= '2000';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('Filedata'))
		{
			 echo "cod=0&messa=No se pudo subir el archivo";
		}
		else
		{
			$uri 	= 	$this->session->flashdata('url');
			if(empty($uri))
			{
				echo 'cod=0&messa=No esta permitido realizar esta acción';
			}
			else
			{
				$this->load->model('apps_model');
				$result = $this->apps_model->get_uri_app($uri);
				if(empty($result))
				{
					echo 'cod=0&messa=La url de la aplicación no existe';
				}
				else
				{
					$campaign 	= $this->apps_model->get_campaign($this->session->userdata('user_id'),$result->id);
					if(empty($campaign))
					{
						echo 'cod=0&messa=La campaña para este usuario no existe o no ha sido creada';
					}
					else
					{
						$upload 	= $this->upload->data();
						//Calcular tiempo Audio en MP3 
						$params = array('filename' => 'public/audios/'.$upload['file_name']);
						$this->load->library('mp3file', $params);
						$x = $this->mp3file->get_metadata();
						//Fin de eventos de MP3File
						$data       = array(
												'name' 		=> $this->input->post('nombre'),
												'path' 		=> $upload['file_name'],
												'user_id'   => $this->session->userdata('user_id'),
												'duration'	=> $x['Length'],
												'size'		=> ($x['Filesize']/1000)
											);
						$inser_au 	= $this->apps_model->insert_audio($data); 
						if(!empty($inser_au))
						{
							$mensaje				= 	array();
							$mensaje['mensaje'] 	=	(base_url('public/audios/'.$upload['file_name']));
							$mensaje['tipo']		=	0;
							$mensaje['hijos']		=	array('utils' => 0);
							$audiocampaign 	= $this->apps_model->insert_audio_campaign(
																							$this->session->userdata('user_id'),
																							$campaign->id,
																							$inser_au, 
																							json_encode($mensaje)
																						);
							if($audiocampaign)
							{
								echo 'cod=1&messa='.base_url('apps/'.$uri.'/2');
								//echo 'cod=0&messa='.$this->db->last_query();
							}
						}
					}
				}
			}
		}
	}
	/**
	* Funcion publica para listar los contactos mediante ajax
	*/
	public function get_contacts()
	{
		ini_set('display_errors', 'on');
		$this->session->keep_flashdata('url');
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
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric|max_length[12]');
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
			
			//EJECUTAMOS LA MEGA FUNCIÓN PARA LOS CONTACTOS
			$contacts = $this->apps_model->get_mega_filtro_contacts(
						$this->session->userdata('user_id'),
						$this->input->post('id_campaign'),
						$this->input->post('id_wapp'),
						$this->input->post('cbo-groups'),
						$this->input->post('cbo-field'),
						$this->input->post('cbo-operator'),
						$this->input->post('txt-criterion')
					);
			
			$return = array(
				'contacts' => $contacts,
				'totalC'	 => $this->apps_model->foundRows()->cuantos
			);
			
			echo json_encode($return);
			
		}
	}
		/**
	* Funcion publica para listar los contactos de una campaña por AJAX
	*/
	public function get_contacts_campaign()
	{
		$this->lang->load('apps');
		$this->session->keep_flashdata('url');
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
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric|max_length[12]');
		if ($this->form_validation->run() == FALSE)
		{
			echo validation_errors();
		}
		else
		{
			$this->load->model('apps_model');
			$contacts_campaign = $this->apps_model->get_contact_campaign(
																			$this->input->post('id_campaign'),
																			$this->session->userdata('user_id'),
																			true
																		 );
			$totalCC 	= $this->apps_model->foundRows()->cuantos;
			
			if(empty($contacts_campaign))
			{
				$res 	= array(
								'cod' 	=> 0,
								'messa'	=> $this->lang->line('nocontactscampaign')
								);
				echo json_encode($res);
				exit();
			}
			else
			{
				echo json_encode(array('contacts'=>$contacts_campaign, 'totalCC'=>$totalCC));
			}
		}
	}
	/**
	* Funcion publica para agregar contactos mediante ajax
	*/
	public function add_contact()
	{
		$this->lang->load('apps');
		if(!$this->_login_in())
		{
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			$url = $this->session->flashdata('url');
			redirect('login/login');
		}
		else
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', $this->lang->line('name'), 'required|xss_clean|max_length[150]');
			$this->form_validation->set_rules('indi_pais',$this->lang->line('indi_pais'), 'required|xss_clean|numeric|max_length[12]');
			$this->form_validation->set_rules('phone', $this->lang->line('phonegrid'), 'required|xss_clean|numeric|min_length[5]|max_length[12]');
			$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric|max_length[12]');
			$this->form_validation->set_rules('indi_area', $this->lang->line('indi_area'), 'xss_clean|numeric|max_length[10]');
			if ($this->form_validation->run() == FALSE)
			{
				$this->session->set_flashdata('error',validation_errors());
				$url = $this->session->flashdata('url');
				redirect('apps/'.$url);
			}
			else
			{
				$this->load->model('apps_model');
				$country	=	$this->apps_model->get_country_id($this->input->post('indi_pais'));
				$data 		=	array(
									'name' 		=> $this->input->post('name'),
									'email' 	=> $this->input->post('email'),
									'indi_pais' => $country->phonecode,
									'indi_area' => $this->input->post('indi_area'),
									'phone' 	=> $this->input->post('phone'),
									'user_id' 	=> $this->session->userdata('user_id')
								);
				$result	=	$this->apps_model->insert_contact($data);
				if(!empty($result))
				{
					$campaign	= $this->apps_model->get_id_wapp($this->input->post('id_campaign'));
					$fields		= $this->apps_model->get_fields($campaign->id_wapp);
					$item = array();
					if(!empty($fields))
					{
						foreach($fields as $fiel)
						{
							$id = (string) $fiel->name_fields;
							array_push($item,array(
											  		'id_fields' 	=> $fiel->id,
											  		'id_contact'	=> $result,
											  		'valor'			=> $this->input->post($id),
											  		'user_id'		=> $this->session->userdata('user_id'),
											  		'id_wapp'		=> $campaign->id_wapp
											  		)
										);
						}
						$batch		= $this->apps_model->insert_batch_contacts_fields($item);
					}
					else
					{
						$batch = TRUE;
					}
					if($batch)
					{
						$this->session->set_flashdata('exitoso',$this->lang->line('successcontact'));
						$url = $this->session->flashdata('url');
						redirect('apps/'.$url);
					}
				}
			}
		}
	}
	/*
	* Funcion publica para ingresar varios contactos a la campaign
	*/
	public function batch_contact_campaign()
	{
		ini_set('display_errors', 'on');
		$this->session->keep_flashdata('url');
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
		$this->form_validation->set_rules('valores', 'Seleccionados', 'required|xss_clean');
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric|max_length[12]');
		if ($this->form_validation->run() == FALSE)
		{
			$res 	= array(
							'cod' 	=> 0,
							'messa'	=> $this->lang->line('nocontactsselect')
							);
			echo json_encode($res);
			exit();
		}
		else
		{
			$this->load->model('apps_model');
			$item = array();
			
			//Nuevo Algoritmo
			$errores		= 0;
			$ok					= 0;
			$ids 				= array();
			
			
			$valores = $this->input->post('valores');
			$now_there = $this->apps_model->get_contacts_where_in_campaign($this->session->userdata('user_id'), $this->input->post('id_campaign'), $valores);
			
			$new_now_there = array();
			foreach($now_there as $nt){
				$new_now_there[] = $nt->id_contact_user;
			}
		
			$valores = array_diff($valores, $new_now_there);
			
			if(!empty($valores))
			{
				foreach($valores as $valor):
					array_push($item,array(
													'id_campaign' 		=> $this->input->post('id_campaign'),
													'id_contact_user'	=> $valor,
													'user_id'					=> $this->session->userdata('user_id')
													)
									);
					$ok++;
					$ids[] = array("id" => $valor);
				endforeach;
			}
			
			if(!empty($item))
			{
				$insert = $this->apps_model->insert_batch_contacts_campaign($item);
				
				if($insert)
				{
					$res 	= array(
							'cod' 	=> 	1,
							'total' => 	$ok,
							'ids'   =>	json_encode($ids),
							'messa'	=>	$this->lang->line('allcontactssuccess')
							);
					echo json_encode($res);
					exit();
				}else{
					$res 	= array(
								'cod' 	=> 0,
								'messa'	=> $this->lang->line('nocontactscampaign')
								);
					echo json_encode($res);
					exit();
				}
					
			}
		}
	}
	
	/*
	* Función para agregar a todos los contactos del filtro actual a la lista de seleccionados
	*/
	function add_all_contacts_to_campaing(){
		ini_set('display_errors', 'on');
		$this->session->keep_flashdata('url');
		$this->lang->load('apps');
		if(!$this->_login_in())
		{
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('login/login');
			die();
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|numeric|max_length[12]');
		$this->form_validation->set_rules('id_wapp', 'id_wapp', 'required|numeric|max_length[12]');
		$this->form_validation->set_rules('slug', 'slug', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',$this->lang->line('ocurrio_un_erro'));
			redirect('marketplace');
			die();
		}
		else
		{
			//EJECUTAMOS LA MEGA FUNCIÓN PARA LOS CONTACTOS
			$contacts = $this->apps_model->get_mega_filtro_contacts(
						$this->session->userdata('user_id'),
						$this->input->post('id_campaign'),
						$this->input->post('id_wapp'),
						$this->input->post('cbo-groups'),
						$this->input->post('cbo-field'),
						$this->input->post('cbo-operator'),
						$this->input->post('txt-criterion'),
						false
					);
					
			$item = array();		
			
			if(!empty($contacts))
			{
				foreach($contacts as $contact):
					array_push($item,array(
													'id_campaign' 		=> $this->input->post('id_campaign'),
													'id_contact_user'	=> $contact->id,
													'user_id'					=> $this->session->userdata('user_id')
													)
									);
				endforeach;
			}
			
			if(!empty($item))
			{
				$insert = $this->apps_model->insert_batch_contacts_campaign($item);
				if($insert){
					$this->session->set_flashdata('exitoso',$this->lang->line('contacts_add_success'));
					redirect('apps/'.$this->input->post('slug'));
				}else{
					$this->session->set_flashdata('error',$this->lang->line('contacts_add_error'));
					redirect('apps/'.$this->input->post('slug'));
				}
			}else{
					$this->session->set_flashdata('error',$this->lang->line('contacts_add_error'));
					redirect('apps/'.$this->input->post('slug'));
			}
		}
	}
	
	/**
	 * Funcion publica para agregar un usuario a una campaña
	*/
	public function add_contact_campaign()
	{
		$this->session->keep_flashdata('url');
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
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('');
		if ($this->form_validation->run() == FALSE)
		{
			echo validation_errors();
		}
		else
		{
			$this->load->model('apps_model');
			$result = $this->apps_model->get_contact_by_user($this->input->post('id_contact'));//,$this->session->userdata('user_id'));
			if(empty($result))
			{
				$res 	= array(
								'cod' 	=> 0,
								'messa'	=> $this->lang->line('noaddcontactcampaign')
								);
				echo json_encode($res);
			}
			else
			{
				$aux = $this->apps_model->get_contact_campaign_user($this->input->post('id_contact'),$this->input->post('id_campaign'));
				if(empty($aux))
				{
					$data	= array(
										'id_campaign' 		=> $this->input->post('id_campaign'),
										'id_contact_user' 	=> $this->input->post('id_contact'),
										'user_id' 			=> $this->session->userdata('user_id')
									);
					$result = $this->apps_model->insert_contact_campaign($data);
					if($result)
					{
						$res 	= array(
								'cod' 	=> 1,
								'messa'	=> $this->lang->line('addcontactsuccess')
								);
						echo json_encode($res);
					}
				}
				else
				{
					$res 	= array(
								'cod' 	=> 0,
								'messa'	=> $this->lang->line('contactcampaignexist')
								);
					echo json_encode($res);
				}
			}
		}
	}
	/**
	* Funcion publica para eliminar un contacto de una campaña
	*/
	public function delete_contact_campaign()
	{
		$this->session->keep_flashdata('url');
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
		$this->form_validation->set_rules('id_contact', 'id_contact', 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric|max_length[12]');
		if ($this->form_validation->run() == FALSE)
		{
			echo validation_errors();
		}
		else
		{
			$this->load->model('apps_model');
			$result = $this->apps_model->delete_contact_campaign($this->input->post('id_campaign'),$this->input->post('id_contact'));//,$this->session->userdata('user_id'));
			if($result)
			{
				$res 	= array(
							'cod' 	=> 1,
							'messa'	=> $this->lang->line('deletecontactcampaign')
							);
				echo json_encode($res);
			}
		}
	}
	
	/**
	 * Funcion publica para agregar un audio a una campaña
	*/
	public function add_content_to_main_campaign()
	{		
		
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('initapp'));
			redirect('login/login');
			
			die();
		}
		
		$this->session->keep_flashdata('url');
		$this->lang->load('apps');
		
		$url = $this->session->flashdata('url');
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('check_content', 'Contenido', 'required|xss_clean|max_length[20]');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error', $this->lang->line('select_a_content'));
			redirect('apps/'.$url.'/2');
			die();
		}
		else
		{
			
			//redirect('apps/'.$url.'/2');
			$this->load->model('apps_model');
			$this->load->model('wizard_model');
			$campaign 	= $this->apps_model->get_campaign_wapp(
																	$this->session->userdata('user_id'),
																	$this->input->post('id_campaign')
																);
			if(!empty($campaign))
			{
				$mensaje	= 	array();
				$mensaje 	= json_decode($campaign->marcado);
				$to_explode	= $this->input->post("check_content");
				$datos 		= explode('_', $to_explode);
				
				if(!is_numeric($datos[0])){
					$this->session->set_flashdata('error', $this->lang->line('select_a_content'));
					redirect('apps/'.$url.'/2');
					die();
				}
				
				if(!empty($datos[1]) && $datos[1]=="audio")
				{	
					$result = $this->wizard_model->get_library_audio_by_id(trim($datos[0]));
					if(count($mensaje) == 0)
					{
						$mensaje['mensaje'] 	=	(base_url('public/audios/'.$result->path));
						$mensaje['tipo']		=	0;
						$mensaje['intro']		=	'';
						$mensaje['cierre']		=	'';
						$mensaje['hijos']		=	array('utils' => 0);
					}
					else
					{
						$mensaje->mensaje		=	(base_url('public/audios/'.$result->path));
						$mensaje->tipo			=	0;
					}

					$result 	= $this->apps_model->insert_audio_campaign(
																		$this->session->userdata('user_id'),
																		$this->input->post('id_campaign'),
																		$datos[0], 
																		json_encode($mensaje)
																	);
					$this->session->keep_flashdata('url');
					if($result)
					{
						$this->session->set_flashdata('exitoso', $this->lang->line('audio_content_success'));
						redirect('apps/'.$url.'/2');
						die();
					}else{
						$this->session->set_flashdata('error', $this->lang->line('select_a_content'));
						redirect('apps/'.$url.'/2');
						die();
					}
				}
				elseif(!empty($datos[1]) && $datos[1]=="text")
				{
					$result = $this->wizard_model->get_library_texts_by_id(trim($datos[0]));
					if(count($mensaje) == 0)
					{
						$mensaje['mensaje'] 	=	$result->text;
						$mensaje['tipo']		=	1;
						$mensaje['intro']		=	'';
						$mensaje['cierre']		=	'';
						$mensaje['hijos']		=	array('utils' => 0);
					}
					else
					{
						$mensaje->mensaje		=	$result->text;
						$mensaje->tipo			=	1;
					}
					$result = $this->apps_model->insert_text_speech_campaign(
																				$this->session->userdata('user_id'),
																				$this->input->post('id_campaign'),
																				$result->text,
																				$result->id,
																				$result->voice,
																				json_encode($mensaje)
																			);
					$this->session->keep_flashdata('url');
					if($result)
					{
						$this->session->set_flashdata('exitoso', $this->lang->line('text_content_success'));
						redirect('apps/'.$url.'/2');
						die();
					}else{
						$this->session->set_flashdata('error', $this->lang->line('select_a_content'));
						redirect('apps/'.$url.'/2');
						die();
					}
				}else{
					$this->session->keep_flashdata('url');
					$this->session->set_flashdata('error', $this->lang->line('select_a_content'));
					redirect('apps/'.$url.'/2');
					die();
				}	
			}
		}	
	}
	
	/**
	 * Funcion publica para agregar un audio a una campaña
	*/
	public function add_audio_campaign()
	{
		$this->session->keep_flashdata('url');
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
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('audio_text', 'audio_text', 'required|xss_clean|numeric|max_length[1]');
		if ($this->form_validation->run() == FALSE)
		{
			echo validation_errors();
		}
		else
		{
			$this->load->model('apps_model');
			$campaign 	= $this->apps_model->get_campaign_wapp(
																	$this->session->userdata('user_id'),
																	$this->input->post('id_campaign')
																);
			if(!empty($campaign))
			{
				$mensaje				= 	array();
				$mensaje = json_decode($campaign->marcado);
				if($this->input->post('audio_text')==1)
				{	
					$result = $this->apps_model->get_audio($this->input->post('id_audio'));
					if(count($mensaje) == 0)
					{
						$mensaje['mensaje'] 	=	(base_url('public/audios/'.$result->path));
						$mensaje['tipo']		=	0;
						$mensaje['hijos']		=	array('utils' => 0);
					}
					else
					{
						$mensaje->mensaje		=	(base_url('public/audios/'.$result->path));
						$mensaje->tipo			=	0;
					}

					$result 	= $this->apps_model->insert_audio_campaign(
																		$this->session->userdata('user_id'),
																		$this->input->post('id_campaign'),
																		$this->input->post('id_audio'), 
																		json_encode($mensaje)
																	);
					if($result)
					{
						$res 	= array(
								'cod' 	=> 1,
								'messa'	=> $this->lang->line('addaudiosuccess')
								);
						echo json_encode($res);
					}
				}
				else
				{
					if(count($mensaje) == 0)
					{
						$mensaje['mensaje'] 	=	($this->input->post('text_speech'));
						$mensaje['tipo']		=	1;
						$mensaje['hijos']		=	array('utils' => 0);
					}
					else
					{
						$mensaje->mensaje		=	($this->input->post('text_speech'));
						$mensaje->tipo			=	1;
					}
					$result = $this->apps_model->insert_text_speech_campaign(
																				$this->session->userdata('user_id'),
																				$this->input->post('id_campaign'),
																				$this->input->post('text_speech'),
																				0, //Ojó esto es un fake para mandar algo de text speech pero la verdad si se debe mandar el id!. ojo!.
																				$this->input->post('voice'),
																				json_encode($mensaje)
																			);
					if($result)
					{
						$res 	= array(
								'cod' 	=> 1,
								'messa'	=> $this->lang->line('addtextsuccess')
								);
						echo json_encode($res);
					}
				}	
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
	/**
	 * Funcion privada para cargar la vista de paso uno
	*/
	private function _view_step_one($data)
	{
		$this->load->view('step1',$data);
	}
	/**
	 * Funcion privada para cargar la vista de paso dos
	*/
	private function _view_step_two($data)
	{
		$this->load->view('step2',$data);
	}
		/**
	 * Funcion privada para cargar la vista de paso tres
	*/
	private function _view_step_three($data)
	{
		$this->load->view('step3',$data);
	}
	/*************************
		FASE II KKATOO
	**************************/
	/*
		Funcion publica para obtener la ciudad con el id de un pais
	*/
	public function get_city()
	{
		$this->session->keep_flashdata('url');
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
		$this->form_validation->set_rules('id_pais', 'id_pais', 'required|xss_clean|numeric|max_length[12]');
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
			$result = $this->apps_model->get_city($this->input->post('id_pais'));
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
	/*
		Funcion publica para agregar el nombre de la campaña
	*/
	public function add_name_campaign()
	{
		$this->session->keep_flashdata('url');
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
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('name', 'name', 'required|xss_clean|max_length[150]');
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
			$result = $this->apps_model->add_name_campaign($this->input->post('id_campaign'),$this->input->post('name'));
			if($result)
			{
				$res 	= array(
								'cod' 	=> 1,
								'messa'	=> $this->lang->line('addnamecampaign')
								);
				echo json_encode($res);
			}else{
				echo json_encode($result);
			}
		}
		die();
	}
	/*
	//Upload de audio para el PRIMER mensaje de la campaña no para el arbol de llamada
	*/
	public function upload_audio_campaign_ini()
	{
		// date_default_timezone_set('Africa/Casablanca');
		$this->session->keep_flashdata('url');
		$this->lang->load('apps');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('login/login');
		}
		$url = $this->session->flashdata('url');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('audio_name', 'Nombre', 'required|xss_clean|max_length[99]|min_length[6]|trim');
		$this->form_validation->set_rules('id_wapp', 'Id de la aplicación', 'required|xss_clean|numeric');
		if ($this->form_validation->run() == FALSE)
		{ 
			$this->session->set_flashdata('error',validation_errors());
			redirect('apps/'.$url.'/2');
		}
		else
		{

			$config['upload_path'] 		= './public/audios/';
			$config['allowed_types'] 	= 'wav|mp3';
			$config['max_size']			= '2000';
			$config['encrypt_name']		=  TRUE;
			$this->load->library('upload', $config);
			if ( $this->upload->do_upload('Filedata'))
			{
				$upload 	= $this->upload->data();
				//Calcular tiempo Audio en MP3
				$params = array('filename' => 'public/audios/'.$upload['file_name']);
				$this->load->library('mp3file', $params);
				$x = $this->mp3file->get_metadata();
				if($x['Length'] !== NULL){
					//Fin de eventos de MP3File
					$data       = array(
											'name' 		=> $this->input->post('audio_name'),
											'path' 		=> $upload['file_name'],
											'user_id'   => $this->session->userdata('user_id'),
											'duration'	=> $x['Length'],
											'size'		=> ($x['Filesize']/1000),
											'tipo'		=> 'audio'
										);
					$this->load->model('apps_model');
					$this->load->model('wizard_model');
					$inser_au 	= 	$this->wizard_model->save_audio($data, $this->input->post('id_wapp'), 'audio');
					//$inser_au 	= $this->apps_model->insert_audio($data);
					if(!empty($inser_au))
					{
						$mensaje 				= 	array();
						$mensaje['mensaje'] 	=	(base_url('public/audios/'.$upload['file_name']));
						$mensaje['tipo']		=	0;
						$mensaje['hijos']		=	array('utils' => 0 );
						$audiocampaign 	= $this->apps_model->insert_audio_campaign(
																					$this->session->userdata('user_id'),
																					$this->input->post('id_campaign'),
																					$inser_au,
																					json_encode($mensaje)
																				 );
						if($audiocampaign)
						{
							$this->session->set_flashdata('exitoso',$this->lang->line('uploadaudiosuccess'));
						}
					}
				}else{
					$this->session->set_flashdata('error',$this->lang->line('erroruploadfile'));
				}
			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('erroruploadfile'));
			}
			redirect('apps/'.$url.'/2');
		}
	}
	
	/*
		Funcion publica para traer información de un audio
	*/
	public function get_audio()
	{
		$this->session->keep_flashdata('url');
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
		$this->form_validation->set_rules('id_audio', 'id_audio', 'required|xss_clean|numeric|max_length[12]');
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
			$result = $this->apps_model->get_audio($this->input->post('id_audio'));
			if($result)
			{
				echo json_encode($result);
			}
			else
			{
				$res 	= array(
								'cod' 	=> 0,
								'messa'	=> $this->lang->line('audionoexist')
							);
				echo json_encode($res);
				exit();
			}
		}
	}
	/*
		Funcion publica para obtener todos los audios
	*/
	public function get_audios_all()
	{
		$this->session->keep_flashdata('url');
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
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('pos', 'pos', 'required|xss_clean|numeric');
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
			$result = $this->apps_model->get_campaign_wapp($this->session->userdata('user_id'),$this->input->post('id_campaign'));
			if(!empty($result))
			{
				$pos			= $this->input->post('pos') * 3;
				$audiosApp 		= $this->apps_model->get_audio_app($this->session->userdata('user_id'),$result->id_wapp, $pos);
				if(empty($audiosApp))
				{
					$res 	= array(
							'cod' 	=> 0,
							'messa'	=> "error"
							);
					echo json_encode($res);
					exit();
				}
				else
				{
					echo json_encode($audiosApp);
				}
			}
		}
	}
	
	/**
	* Grabar intro o cierre
	*/
	function save_intro_out($type="intro"){
		$this->session->keep_flashdata('url');
		
		$this->lang->load('wizard');
		$this->load->model('wizard_model');
		$this->load->model('apps_model');
				
		$this->session->keep_flashdata('url');
		if(!$this->_login_in())
		{
			echo 'cod=0&messa=No ha iniciado session';
			die();
		}
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nombre', $this->lang->line('name'), 'required|xss_clean|max_length[99]|min_length[2]|trim');
		
		if ($this->form_validation->run() == FALSE){
			echo "cod=0&messa=".validation_errors();
			die();
		}
		
		$uri 	= 	$this->session->flashdata('url');
		
		$wapp 	= $this->apps_model->get_uri_app($uri);
		
		if(empty($wapp))
		{
			echo 'cod=0&messa=Por favor refresque la página nuevamente.';
			die();
		}
		
		$campaign 	= $this->apps_model->get_campaign($this->session->userdata('user_id'),$wapp->id);
		if(empty($campaign))
		{
			echo 'cod=0&messa=Por favor refresque la página nuevamente.';
			die();
		}

		$config['upload_path'] 		= './public/audios/intro_cierre';
		$config['allowed_types'] 	= 'wav|mp3';
		$config['encrypt_name'] 	=  TRUE;
		$config['max_size']			= '2000';
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('Filedata'))
		{
			 echo "cod=0&messa=No se pudo subir el audio, por favor refresca la página e intenta nuevamente.";
		}
		else
		{
			$audio1		=	$this->upload->data();
			$params 	= 	array('filename' => 'public/audios/intro_cierre/'.$audio1['file_name']);
			$this->load->library('mp3file', $params);
			$x = $this->mp3file->get_metadata();
			//echo json_encode($x);
			if($x['Length'] !== NULL || $x['Length'] > 10)
			{
				$path		= base_url('public/audios/intro_cierre/'.$audio1["file_name"]);
				$insert		= 	array(
										'tipo_contenido'	=> 'record_'.$type,
										'record_file' 		=> $audio1['file_name'],
										'path'   			=> $path,//$this->session->userdata('user_id'),
										'id_campana'		=> $campaign->id,
										'id_user'			=> $this->session->userdata('user_id'),
										'id_wapp'			=> $wapp->id
									);
				//print_r($insert);
				$inser_au 	= 	$this->apps_model->save_intro_close($insert["id_campana"], $insert["id_wapp"],  $insert["id_user"],  $insert);
				
				
				if($inser_au){
					
					$mensaje	= 	array();
					$mensaje 	= json_decode($campaign->marcado);
					
					if(count($mensaje) == 0)
					{
						$mensaje['mensaje'] 	=	'';
						$mensaje['tipo']		=	'';
						if($type=="intro"){
							$mensaje['intro'] 	=   $path;
						}else{
							$mensaje['intro'] 	=   '';
						}
						if($type=="cierre"){
							$mensaje['cierre'] 	=   $path;
						}else{
							$mensaje['cierre'] 	=   '';
						}
						$mensaje['hijos']		=	array('utils' => 0);
					}
					else
					{
						if($type=="intro"){
							$mensaje->intro 	= 	$path;	
						}
						if($type=="cierre"){
							$mensaje->cierre 	= 	$path;	
						}
					}
					
					$saved = $this->apps_model->update_intro_close_campaign(
							$this->session->userdata('user_id'),
							$campaign->id,
							$inser_au,
							$type,
							json_encode($mensaje)
						);
				
					if($saved){
						echo 'cod=1&messa='.base_url('apps/'.$uri.'/2');
					}else{
						try{
							unlink('./public/audios/intro_cierre/'.$audio1['file_name']);	
						}catch(Exception $e){
							
						}
						echo "cod=0&messa=No se pudo grabar el archivo, por favor refresca la página e intenta nuevamente.";
					}
				}else{
					try{
						unlink('./public/audios/intro_cierre/'.$audio1['file_name']);	
					}catch(Exception $e){
						
					}
					echo "cod=0&messa=No se pudo grabar el archivo, por favor refresca la página e intenta nuevamente.";
				}
			}else{
				try{
					unlink('./public/audios/intro_cierre/'.$audio1['file_name']);	
				}catch(Exception $e){
					
				}
				echo "cod=0&messa=La grabación no debe ser mayor a 10 segundos.";
			}	
		}
	}
	
	function save_intro(){
		$this->save_intro_out("intro");
	}
	
	function save_close(){
		$this->save_intro_out("cierre");
	}

	/***
	* Eliminar contenido del árbol
	*/
	function delete_content_from_tree_campaign(){
		if(!$this->_login_in()){
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('initapp'));
			redirect('login/login');
			
			die();
		}

		$this->session->keep_flashdata('url');
		$this->lang->load('apps');
		
		$url = $this->session->flashdata('url');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('arbol', 'arbol', 'required');

		if ($this->form_validation->run() == FALSE){
			echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cantdeleteselecteddial')));
			die();
		}
		else{
			if($this->input->post('arbol')!="inicial"){
				$this->load->model('apps_model');
				$result = $this->apps_model->get_campaign_wapp(
																$this->session->userdata('user_id'),
																$this->input->post('id_campaign')
															);
				if(!empty($result)){
					$marcado	=	json_decode($result->marcado);
					if(count($marcado) != 0){
						$padre 	= 	"";
						$hijo 	=	"";
						$arbol = explode(',', $this->input->post('arbol'));
						
						if(count($arbol) == 2)
						{
							$padre 	= 	$this->_get_number_text($arbol[0]);
							$hijo 	=		$this->_get_number_text($arbol[1]);
							unset($marcado->hijos->$padre->hijos->$hijo);
						}
						else
						{
							$padre 	= 	$this->_get_number_text($arbol[0]);
							unset($marcado->hijos->$padre);
						}

						$update = $this->apps_model->update_marcado_campaign(
																					$this->session->userdata('user_id'),
																					$this->input->post('id_campaign'),
																					json_encode($marcado)
																				);
						if($update)
						{
							echo json_encode(array('cod'=>1, 'messa'=>$this->lang->line('deletedselecteddial')));
							die();
						}else{
							echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cantdeleteselecteddial')));
							die();
						}

					}else{
						echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cantdeleteselecteddial')));
						die();
					}
				}else{
					echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cantdeleteselecteddial')));
					die();
				}
			}else{
				echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cantdeleteselecteddial')));
				die();
			}
		}
	}
	
	/**
	* Agregar contenido para el árbol
	*/
	function add_content_to_tree_campaign(){
		if(!$this->_login_in()){
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('initapp'));
			redirect('login/login');
			
			die();
		}
		
		$this->session->keep_flashdata('url');
		$this->lang->load('apps');
		
		$url = $this->session->flashdata('url');
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('check_content', 'Contenido', 'required|xss_clean|max_length[20]');
		$this->form_validation->set_rules('arbol', 'arbol', 'requiredss');
		
		if ($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('error', $this->lang->line('select_a_content_tree'));
			redirect('apps/'.$url.'/2');
			die();
		}
		else{
			if($this->input->post('arbol')=="inicial"){
				$this->add_content_to_main_campaign();
			}else{
				$to_explode	= $this->input->post("check_content");
				$datos 		= explode('_', $to_explode);
				if(!is_numeric($datos[0])){
					$this->session->set_flashdata('error', $this->lang->line('select_a_content_tree'));
					redirect('apps/'.$url.'/2');
					die();
				}
				$this->load->model('apps_model');
				$this->load->model('wizard_model');
				$result = $this->apps_model->get_campaign_wapp(
																$this->session->userdata('user_id'),
																$this->input->post('id_campaign')
															);
				if(!empty($result)){
					$marcado	=	json_decode($result->marcado);
					if(count($marcado) != 0){
						$padre 	= 	"";
						$hijo 	=	"";
						$arbol = explode(',', $this->input->post('arbol'));
						
						if(count($arbol) == 2)
						{
							$padre 	= 	$this->_get_number_text($arbol[0]);
							$hijo 	=		$this->_get_number_text($arbol[1]);
						}
						else
						{
							$padre 	= 	$this->_get_number_text($arbol[0]);
						}
						if($padre != "")
						{
							if(!empty($datos[1]) && $datos[1]=="text")
							{
								$text_speech = $this->wizard_model->get_library_texts_by_id(trim($datos[0]));
								if($hijo == "")
								{
									$marcado->hijos->$padre	=(object)	array(
																				'mensaje'	=>	$text_speech->text,
																				'tipo'		=>	1,
																				'digito'	=> 	$arbol[0],
																				'hijos'		=>	array(	
																									'utils' => 0 
																									)
																			);
								}
								else
								{
									$marcado->hijos->$padre->hijos->$hijo	=	(object)	array(
																						'mensaje'	=>	$text_speech->text,
																						'tipo'		=>	1,
																						'ayuda'		=> 	$arbol[0].'/'.$arbol[1],
																						'digito'	=> 	$arbol[1]
																					);
								}
							}
							elseif(!empty($datos[1]) && $datos[1]=="audio")
							{
								$audio = $this->wizard_model->get_library_audio_by_id(trim($datos[0]));
								$path	= base_url('public/audios/'.$audio->path);
								if($hijo == "")
								{
									$marcado->hijos->$padre		=	(object)	array(
																						'mensaje'	=>	($path),
																						'tipo'		=>	0,
																						'digito'	=> 	$arbol[0],
																						'id_audio'  =>	$audio->id_audio,
																						'duration'	=> 	$audio->duration,
																						'hijos'		=>	array(	
																												'utils' => 0 
																												)
																					);
								}
								else
								{
									$marcado->hijos->$padre->hijos->$hijo	=	(object)	array(
																						'mensaje'	=>	($path),
																						'tipo'		=>	0,
																						'ayuda'		=> 	$arbol[0].'/'.$arbol[1],
																						'digito'	=> 	$arbol[1],
																						'id_audio'  =>	$audio->id_audio,
																						'duration'	=> 	$audio->duration
																					);
								}
							}else{
								$this->session->set_flashdata('error', $this->lang->line('select_a_content_tree'));
								redirect('apps/'.$url.'/2');
								die();
							}
							$update 	= $this->apps_model->update_marcado_campaign(
																						$this->session->userdata('user_id'),
																						$this->input->post('id_campaign'),
																						json_encode($marcado)
																					);
							if($update)
							{
								$this->session->set_flashdata('exitoso', $this->lang->line('content_tree_success'));
								redirect('apps/'.$url.'/2');
								die();
							}else{
								$this->session->set_flashdata('error', $this->lang->line('select_a_content_tree'));
								redirect('apps/'.$url.'/2');
								die();
							}
						}
						else
						{
							$this->session->set_flashdata('error', $this->lang->line('select_a_content_tree'));
							redirect('apps/'.$url.'/2');
							die();
						}
					}else{
						$this->session->set_flashdata('error', $this->lang->line('select_a_content_tree'));
						redirect('apps/'.$url.'/2');
						die();
					}
				}else{
					$this->session->set_flashdata('error', $this->lang->line('select_a_content_tree'));
					redirect('apps/'.$url.'/2');
					die();
				}
			}
		}
	}
	
	/*
		Funcion publica para add audio o texto a un nodo del arbol del marcado
	*/
	public function add_tree_audio_text()
	{
		ini_set('display_errors', 'on');
		$this->session->keep_flashdata('url');
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
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('audio_text', 'audio_text', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('text_speech', 'text_speech', 'xss_clean|trim');
		$this->form_validation->set_rules('arbol', 'arbol', 'required|xss_clean');
		$this->form_validation->set_rules('id_audio', 'id_audio', 'xss_clean|numeric');
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
			$result = $this->apps_model->get_campaign_wapp(
															$this->session->userdata('user_id'),
															$this->input->post('id_campaign')
														);
			if(!empty($result))
			{
				$marcado	=	json_decode($result->marcado);
				if(count($marcado) != 0)
				{	
					$padre 	= 	"";
					$hijo 	=	"";
					$arbol = explode(',', $this->input->post('arbol'));
					if(count($arbol) == 2)
					{
						$padre 	= 	$this->_get_number_text($arbol[0]);
						$hijo 	=	$this->_get_number_text($arbol[1]);
					}
					else
					{
						$padre 	= 	$this->_get_number_text($arbol[0]);
					}
					if($padre != "")
					{
						if($this->input->post('audio_text')==0)
						{
							if($hijo == "")
							{
								$marcado->hijos->$padre	=(object)	array(
																			'mensaje'	=>	($this->input->post('text_speech')),
																			'tipo'		=>	1,
																			'digito'	=> 	$arbol[0],
																			'hijos'		=>	array(	
																								'utils' => 0 
																								)
																		);
							}
							else
							{
								$marcado->hijos->$padre->hijos->$hijo	=	(object)	array(
																					'mensaje'	=>	($this->input->post('text_speech')),
																					'tipo'		=>	1,
																					'ayuda'		=> 	$arbol[0].'/'.$arbol[1],
																					'digito'	=> 	$arbol[1]
																				);
							}
						}
						else
						{
							$audio 	= $this->apps_model->get_audio($this->input->post('id_audio'));
							$path	= base_url('public/audios/'.$audio->path);
							if($hijo == "")
							{
								$marcado->hijos->$padre		=	(object)	array(
																					'mensaje'	=>	($path),
																					'tipo'		=>	0,
																					'digito'	=> 	$arbol[0],
																					'id_audio'  =>	$audio->id,
																					'duration'	=> 	$audio->duration,
																					'hijos'		=>	array(	
																											'utils' => 0 
																											)
																				);
							}
							else
							{
								$marcado->hijos->$padre->hijos->$hijo	=	(object)	array(
																					'mensaje'	=>	($path),
																					'tipo'		=>	0,
																					'ayuda'		=> 	$arbol[0].'/'.$arbol[1],
																					'digito'	=> 	$arbol[1],
																					'id_audio'  =>	$audio->id,
																					'duration'	=> 	$audio->duration
																				);
							}
						}
						$update 	= $this->apps_model->update_marcado_campaign(
																					$this->session->userdata('user_id'),
																					$this->input->post('id_campaign'),
																					json_encode($marcado)
																				);
						if($update)
						{
							$res 	= array(
											'cod' 	=> 1,
											'messa'	=> $this->lang->line('savesuccesstree')
											);
							echo json_encode($res);	
						}
					}
					else
					{
						$res 	= array(
											'cod' 	=> 0,
											'messa'	=> $this->lang->line('errorsavetree')
											);
						echo json_encode($res);
					}
				}
				else
				{
					$res 	= array(
							'cod' 	=> 0,
							'messa'	=> $this->lang->line('nomessagemain')
							);
					echo json_encode($res);
					exit();
				}
			}
		}
	}
	/*
		Funcion publica para subir audio a un arbol
	*/
	public function add_tree_upload_audio()
	{
		// date_default_timezone_set('Africa/Casablanca');
		$this->session->keep_flashdata('url');
		$this->lang->load('apps');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('marketplace');
		}
		$url = $this->session->flashdata('url');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('arbol', 'arbol', 'required|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{ 
			$this->session->set_flashdata('error',validation_errors());
			redirect('apps/'.$url.'/2');
		}
		else
		{
			$this->load->model('apps_model');
			$result = $this->apps_model->get_campaign_wapp(
															$this->session->userdata('user_id'),
															$this->input->post('id_campaign')
														);
			if(!empty($result))
			{
				$marcado	=	json_decode($result->marcado);
				if(count($marcado) != 0)
				{	
					$padre 	= 	"";
					$hijo 	=	"";
					$arbol = explode(',', $this->input->post('arbol'));
					if(count($arbol) == 2)
					{
						$padre 	= 	$this->_get_number_text($arbol[0]);
						$hijo 	=	$this->_get_number_text($arbol[1]);
					}
					else
					{
						$padre 	= 	$this->_get_number_text($arbol[0]);
					}
					if($padre != "")
					{
						$config['upload_path'] 		= './public/audios/';
						$config['allowed_types'] 	= 'wav|mp3';
						$config['max_size']			= '2000';
						$config['encrypt_name']		=  TRUE;
						$this->load->library('upload', $config);
						if ( $this->upload->do_upload('Filedata'))
						{
							$upload 	= $this->upload->data();
							//Calcular tiempo Audio en MP3
							$params = array('filename' => 'public/audios/'.$upload['file_name']);
							$this->load->library('mp3file', $params);
							$x = $this->mp3file->get_metadata();
							//Fin de eventos de MP3File
							$data       = array(
													'name' 		=> 'Audio '.date('Y-m-d'),
													'path' 		=> $upload['file_name'],
													'user_id'   => $this->session->userdata('user_id'),
													'duration'	=> $x['Length'],
													'size'		=> ($x['Filesize']/1000)
												);
							$inser_au 	= $this->apps_model->insert_audio($data);
							if(!empty($inser_au))
							{
							
								if($hijo == "")
								{
									$marcado->hijos->$padre	=(object) array(
																			'mensaje'	=>	(base_url('public/audios/'.$upload['file_name'])),
																			'tipo'		=>	0,
																			'digito'	=> 	$arbol[0],
																			'duration'  =>  $x['Length'],
																			'id_audio'	=> 	$inser_au,
																			'hijos'		=>	array(	
																								'utils' 	=> 0 
																								)
																			);
								}
								else
								{
									$marcado->hijos->$padre->hijos->$hijo=(object) array(
																			'mensaje'	=>	(base_url('public/audios/'.$upload['file_name'])),
																			'tipo'		=>	0,
																			'ayuda'		=> 	$arbol[0].'/'.$arbol[1],
																			'digito'	=> 	$arbol[1],
																			'duration'  =>  $x['Length'],
																			'id_audio'	=> 	$inser_au
																					);
								}
								$update 	= $this->apps_model->update_marcado_campaign(
																					$this->session->userdata('user_id'),
																					$this->input->post('id_campaign'),
																					json_encode($marcado)
																				);
								if($update)
								{
									$this->session->set_flashdata('exitoso',$this->lang->line('uploadaudiosuccess'));
								}
							}
						}
						else
						{
							$this->session->set_flashdata('error', $this->upload->display_errors());
						}
					}
					else
					{
						$this->session->set_flashdata('error',$this->lang->line('errorsavetree'));
					}
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('nomessagemain'));
				}
			}
			redirect('apps/'.$url.'/2');
		}
	}
	/*
		Funcion publica obtener información sobre un nodo...
	*/
	public function get_number_tree()
	{
		ini_set('display_errors', 'on');
		$this->session->keep_flashdata('url');
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
		$this->form_validation->set_rules('id_campaign', 'id_campaign', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('arbol', 'arbol', 'required|xss_clean');
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
			if($this->input->post('arbol') != "inicial")
			{
				$this->load->model('apps_model');
				$result = $this->apps_model->get_campaign_wapp(
																$this->session->userdata('user_id'),
																$this->input->post('id_campaign')
															);
				if(!empty($result))
				{
					$marcado		=	json_decode($result->marcado);
					if(count($marcado) != 0)
					{
						$padre 		= 	"";
						$hijo 		=	"";
						$retorno	=	"";
						$arbol 		= explode(',', $this->input->post('arbol'));
						if(count($arbol) == 2)
						{
							$padre 	= 	$this->_get_number_text($arbol[0]);
							$hijo 	=	$this->_get_number_text($arbol[1]);
						}
						else
						{
							$padre 	= 	$this->_get_number_text($arbol[0]);
						}
						if($hijo == "")
						{
							if(isset($marcado->hijos->$padre))
							{
								$retorno = $marcado->hijos->$padre;
							}
						}
						else
						{
							if(isset($marcado->hijos->$padre->hijos->$hijo))
							{
								$retorno = $marcado->hijos->$padre->hijos->$hijo;
							}
							
						}
						if($retorno == "")
						{
							$res 	= array(
										'cod' 	=> 1,
										'messa'	=> ""
										);
							echo json_encode($res);
							exit();
						}
						else
						{
							if($retorno->tipo == 0){
								$this->load->model('wizard_model');
								$result = $this->wizard_model->get_library_audio_by_id($retorno->id_audio);
								if(!empty($result))
									$retorno = (object) array_merge( (array)$retorno, array( 'audio_name' => $result->name) );
							}
							$res = array(
										'cod' 	=> 1,
										'messa'	=> $retorno
										);
							echo json_encode($res);
						}
					}
					else
					{
						$res 	= array(
										'cod' 	=> 0,
										'messa'	=> $this->lang->line('nomessagemain')
										);
						echo json_encode($res);
						exit();
					}
				}
			}
			else
			{
				$res 	= array(
								'cod' 	=> 1,
								'messa'	=> $this->lang->line('nomessagemain')
								);
				echo json_encode($res);
				exit();
			}

		}
	}
	/*
	Funcion para subir un audio grabado al arbol desde el flash
	*/
	public function add_tree_record_audio()
	{
		$this->session->keep_flashdata('url');
		if(!$this->_login_in())
		{
			echo 'cod=0&messa=No ha iniciado session';
			exit();
		}
		$config['upload_path'] 		= './public/audios/';
		$config['allowed_types'] 	= '*';
		$config['encrypt_name'] 	=  TRUE;
		$config['max_size']			= '2000';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('Filedata'))
		{
			 echo "cod=0&messa=No se pudo subir el archivo";
		}
		else
		{
			$uri 	= 	$this->session->flashdata('url');
			if(empty($uri))
			{
				echo 'cod=0&messa=No esta permitido realizar esta acción';
			}
			else
			{
				$this->load->model('apps_model');
				$result 	=	$this->apps_model->get_uri_app($uri);
				if(empty($result))
				{
					echo 'cod=0&messa=La url de la aplicación no existe';
				}
				else
				{
					$campaign 	= $this->apps_model->get_campaign($this->session->userdata('user_id'),$result->id);
					if(empty($campaign))
					{
						echo 'cod=0&messa=La campaña para este usuario no existe o no ha sido creada';
					}
					else
					{
						$marcado	=	json_decode($campaign->marcado);
						if(count($marcado) != 0)
						{	
							$padre 	= 	"";
							$hijo 	=	"";
							$arbol = explode(',', $this->input->post('arbol'));
							if(count($arbol) == 2)
							{
								$padre 	= 	$this->_get_number_text($arbol[0]);
								$hijo 	=	$this->_get_number_text($arbol[1]);
							}
							else
							{
								$padre 	= 	$this->_get_number_text($arbol[0]);
							}
							if($padre != "")
							{
								$upload 	= $this->upload->data();
								//Calcular tiempo Audio en MP3 
								$params = array('filename' => 'public/audios/'.$upload['file_name']);
								$this->load->library('mp3file', $params);
								$x = $this->mp3file->get_metadata();
								//Fin de eventos de MP3File
								$data       = array(
														'name' 		=> $this->input->post('nombre'),
														'path' 		=> $upload['file_name'],
														'user_id'   => $this->session->userdata('user_id'),
														'duration'	=> $x['Length'],
														'size'		=> ($x['Filesize']/1000)
													);
								$inser_au 	= $this->apps_model->insert_audio($data); 
								if(!empty($inser_au))
								{
									if($hijo == "")
									{
										$marcado->hijos->$padre	=(object) array(
																				'mensaje'	=>	(base_url('public/audios/'.$upload['file_name'])),
																				'tipo'		=>	0,
																				'digito'	=> 	$arbol[0],
																				'duration'  =>  $x['Length'],
																				'id_audio'	=> 	$inser_au,
																				'hijos'		=>	array(	
																									'utils' 	=> 0 
																									)
																				);
									}
									else
									{
										$marcado->hijos->$padre->hijos->$hijo=(object) array(
																				'mensaje'	=>	(base_url('public/audios/'.$upload['file_name'])),
																				'tipo'		=>	0,
																				'ayuda'		=> 	$arbol[0].'/'.$arbol[1],
																				'digito'	=> 	$arbol[1],
																				'duration'  =>  $x['Length'],
																				'id_audio'	=> 	$inser_au
																						);
									}
									$update 	= $this->apps_model->update_marcado_campaign(
																								$this->session->userdata('user_id'),
																								$campaign->id,
																								json_encode($marcado)
																							);
									if($update)
									{
										echo 'cod=1&messa='.base_url('apps/'.$uri.'/2');
									}
								}
							}
							else
							{
								echo 'cod=0&messa=No se ha seleccionado opción de marcado'.$this->input->post('arbol');
							}
						}
						else
						{
							echo 'cod=0&messa=No ha creado un mensaje principal para crear un arbol de marcado';
						}
					}
				}
			}
		}
	}	
	/*
		Funcion privada para get number text..
	*/
	private function _get_number_text($opcion)
	{
		$number = "";
		switch($opcion)
		{
			case "0":
				$number = "cero";
				break;
			case "1":
				$number = "uno";
				break;
			case "2":
				$number = "dos";
				break;
			case "3";
				$number = "tres";
				break;
			case "4":
				$number = "cuatro";
				break;
			case "5":
				$number	= "cinco";
				break;
			case "6":
				$number = "seis";
				break;
			case "7":
				$number	= "siete";
				break;
			case "8":
				$number = "ocho";
				break;
			case "9":
				$number	= "nueve";
				break;
			case "*":
				$number = "ast";
				break;
			case "#";
				$number = "num";
				break;
			default:
				$number = "";
				break;
		}
		return $number;
	}
	/*
		Calcular duration de audio
	*/
	public function _duration_audio($campaign)
	{	
		$duration	= 	0;
		//Calcular duración del audio
		$marcado 	= 	json_decode($campaign->marcado);
		$audio 		= 	$this->apps_model->get_audio($campaign->id_audio);
		if($campaign->id_audio != 0)
		{
			$duration	= 	$audio->duration;
		}
		else
		{
			$total 		= strlen($campaign->text_speech);
			$duration 	+= ceil($total/10);
		}
		if(isset($marcado->hijos))
		{
			foreach($marcado->hijos as $marc)
			{	
				if(isset($marc->duration))
				{
					$duration += $marc->duration;
				}
				if(isset($marc->tipo))
				{
					if($marc->tipo == 1)
					{
						$total 			= strlen($marc->mensaje);
						$duration 		+= ceil($total/10);
					}
				}
				if(isset($marc->hijos))
				{
					foreach($marc->hijos as $hijos)
					{
						if(isset($hijos->duration))
						{
							$duration += $hijos->duration;
						}
						if(isset($hijos->tipo))
						{
							if($hijos->tipo == 1)
							{
								$total 			= strlen($hijos->mensaje);
								$duration 		+= ceil($total/10);
							}
						}
					}
				}
			}
		}
		return $duration;
	}
	
	/**
	* Funcion publica para añadir hora de llamada a una campaña y finalizarla.
	*/
	public function add_date_campaign()
	{	
		$this->session->keep_flashdata('url');
		//Variables de Cobro
		$utilidad			= 1;
		$publisher			= 1;
		//Fin variables de cobro
		$this->lang->load('apps');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('marketplace');
		}
		$uri = $this->session->flashdata('url');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('date', $this->lang->line('date'), 'required|xss_clean');
		$this->form_validation->set_rules('hour', $this->lang->line('hour'), 'required|xss_clean|numeric');
		$this->form_validation->set_rules('minu', $this->lang->line('minu'), 'required|xss_clean|numeric');
		$this->form_validation->set_rules('timezones',  $this->lang->line('gmt'), 'required|xss_clean|max_length[5]');
		$this->form_validation->set_rules('campaign',  $this->lang->line('campaign'), 'required|xss_clean');
		$this->form_validation->set_rules('checkboxBuzon',  "", 'xss_clean');
		$this->form_validation->set_rules('checkboxSMS',  "", 'xss_clean');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',validation_errors());
			redirect('apps/'.$uri.'/3');
		}
		else
		{
			$isBuzonChecked = FALSE;
				$checkboxBuzon = $this->input->post('checkboxBuzon');
				if(isset($checkboxBuzon) AND !empty($checkboxBuzon))
				{
					$isBuzonChecked = TRUE;
				}

				$isSMSChecked = FALSE;
				$checkboxSMS = $this->input->post('checkboxSMS');
				if(isset($checkboxSMS) AND !empty($checkboxSMS))
				{
					$isSMSChecked = TRUE;
				}
				//Cookie GMT
				$cookie = array(
							    'name'   => 'GMT',
							    'value'  => $this->input->post('timezones'),
							    'expire' => '0'
							);
				$this->input->set_cookie($cookie);
				//Fin Cookie GMT
				$this->load->model('apps_model');
				$verified 	= $this->apps_model->get_user_verified($this->session->userdata('user_id'));
				if(empty($verified))
				{
					$ready 	= $this->apps_model->campaign_ready($this->session->userdata('user_id'), $this->input->post('campaign'));
					if(!empty($ready))
					{
						$priceWapp		=	$this->apps_model->get_wapp_price($this->input->post('campaign'));
						if(!empty($priceWapp))
						{
							$publisher	=	(($priceWapp->price)/100);  //formula two
						}
						//VALIDAR DINERO
						$duration		= 	$this->_duration_audio($ready);
						$total_price	=	$this->_price_estimated($this->input->post('campaign'), $duration);
						$total_price	=	$total_price; //subformula two // * $utilidad * $publisher
						$verified 		= 	$this->apps_model->get_user_verified($this->session->userdata('user_id'), 1);
						$result 		=	$this->apps_model->get_uri_app($uri);
						if($result->tipo != 1){
							$app_credits = 0;
							if($result->uses_special_pines == 1){
								$app_credits = $this->apps_model->get_user_app_credits($this->session->userdata('user_id'), $result->id);
							}
							if($total_price > ($verified->credits+$app_credits))
							{
								$this->session->set_flashdata('error',$this->lang->line('nocredits'));
								redirect('apps/'.$uri.'/3');
							}
						}
						//FIN VALIDAR DINERO
						//date_default_timezone_set('Africa/Casablanca');
						$this->load->helper('date');
						$timezone 	= $this->input->post('timezones');
						$daylight_saving = FALSE;
						$now 			= time();
						$gmt 			= gmt_to_local($now, $timezone, $daylight_saving);
						$hora 		= mdate("%H", $gmt);
						$minu 		= mdate("%i", $gmt);
						$fecha 		= mdate("%Y-%m-%d", $gmt);
						//echo var_dump($hora);
						if($fecha > $this->input->post('date'))
						{
							$this->session->set_flashdata('error',$this->lang->line('datenovalid'));
							redirect('apps/'.$uri.'/3');
							//echo $this->input->post('date'); 
						}
						elseif($fecha == $this->input->post('date'))
						{
							if($hora > $this->input->post('hour'))
							{
								$this->session->set_flashdata('error',$this->lang->line('hournovalid'));
								redirect('apps/'.$uri.'/3');
							}
							elseif($hora == $this->input->post('hour') and $minu > ($this->input->post('minu')+2))
							{
								$this->session->set_flashdata('error',$this->lang->line('hournovalid'));
								redirect('apps/'.$uri.'/3');
							}
							else
							{
								//Insert final de la campaña
								$res 		= 	$this->_ready_campaign(
																		$this->input->post('campaign'),
																		$this->input->post('date'),
																		$this->input->post('hour'),
																		$this->input->post('minu'),
																		$this->input->post('timezones'),
																		$publisher,
																		$ready,
																		$isBuzonChecked,
																		$isSMSChecked
																	);
								if($res)
								{
									//Ejecutar procesos
									/*$url_e	= base_url('queues/set_queues_call');
									$c = curl_init($url_e);
									curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
									$page = curl_exec($c);
									curl_close($c);  */
									$this->session->set_flashdata('exitoso',$this->lang->line('campaignsuccess'));
									redirect('campaign/detail_campaign/'.$this->input->post('campaign'));
								}
							}
						}
						else
						{
								//Insert final de la campaña
								$res 		= 	$this->_ready_campaign(
																	$this->input->post('campaign'),
																	$this->input->post('date'),
																	$this->input->post('hour'),
																	$this->input->post('minu'),
																	$this->input->post('timezones'),
																	$publisher,
																	$ready,
																	$isBuzonChecked,
																	$isSMSChecked
																);
								if($res)
								{
									//Ejecutar procesos
									/*$url_e	= base_url('queues/set_queues_call');
									$c = curl_init($url_e);
									curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
									$page = curl_exec($c);
									curl_close($c);       */                                                                                                                     
									$this->session->set_flashdata('exitoso',$this->lang->line('campaignsuccess'));
									redirect('campaign/detail_campaign/'.$this->input->post('campaign'));
								}
						}
					}
					else
					{
						$this->session->set_flashdata('error',$this->lang->line('campaignnoready'));
						redirect('apps/'.$uri.'/3');
					}
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('noaccountverified'));
					redirect('apps/'.$uri.'/3');
				}
		}
	}
	
	/**
	* Convierte los datos que deberían ser tipo de dato fecha al formato m/d/Y
	* @param $date posible fecha que se debe converir
	* @return $rDate la fecha a retornar ya convertida, si sucede un error retorna la misma que se ingresó
	*/
	
	function convert_to_dd_mm_yyyy($date){
	
		$formats = array("d/m/Y", "m/d/Y", "Y/m/d", "Y-m-d", "d-m-Y", "m-d-Y", 'Y-d-m', "d.m.Y", "m.d.Y",  "Y.m.d", 'd-F-Y', "d/F/Y", "Y/F/d", "F/d/Y", "Y-F-d", "F-d-Y", "d.F.Y", "F.d.Y",  "Y.F.d", 'd-M-Y', "d/M/Y", "Y/M/d", "M/d/Y", "Y-M-d", "M-d-Y", "d.M.Y", "M.d.Y",  "Y.M.d", 'Y-d-M', 'Y-d-F');
		
		$rfDate = false;
		foreach ($formats as $format)
		{
			$rDate = DateTime::createFromFormat($format, $date);
			
			if ($rDate == false):
				//do nothing
			else:
				$rfDate = $rDate;
				if(strtolower($rfDate->format($format)) == strtolower($date))
					break;
				else
					$rfDate = false;
			endif;
		}
		if($rfDate){
			return $rfDate->format('m/d/Y');
		}else{
			return $date;
		}
	}
	
	/**
	* Funcion privada para dejar lista la campaña
	*/
	public function _ready_campaign($campaign,$fecha,$hour,$minu,$timezones,$publisher,$ready,$isBuzonChecked,$isSMSChecked)
	{
		//$campaign,$fecha,$hour,$minu,$timezones,$ready
		$this->load->model('apps_model');
		$command		=	"Hangup";
		$voice			=	"DEFAULT";
		$media			= 	array();
		$campana 		= 	$this->apps_model->get_campaign_wapp($this->session->userdata('user_id'),$campaign);
		$id_wapp 		=   $campana->id_wapp;
		$tipo_wapp 		= 	$this->apps_model->get_app_type($id_wapp);
		$isSMS          =    0;

		if ($isSMSChecked)
		{
			$isSMS = 1;

		}

		$ringtimeout    = 	55;
		if ($isBuzonChecked)
		{
			$ringtimeout = 25;
		}
		//Json de marcado
		$marcado 		= 	json_decode($campana->marcado);
		if($this->_verified_hijos($marcado->hijos))
		{
			$command	=	"WaitAnswer[10]";
		}
		if($campana->voice != 0 and $campana->id_audio == 0)
		{
			$arreglo_vo = 	$this->apps_model->get_voice_id($campana->voice);
			$voice		=	$arreglo_vo->name;
		}
		//Mensaje inicial
		array_push($media,array(
							"Command" 	=> $command,
							"EventData" => null,
							"Indice"	=> "i",
							"VoiceName" => $voice,
							"message"	=> ($marcado->mensaje),
							"Type"		=> $marcado->tipo
						)
			);
		if(isset($marcado->hijos))
		{
			foreach($marcado->hijos as $padre)
			{	
				$command2 	=	"Hangup";
				if(isset($padre->mensaje))
				{
					if($this->_verified_hijos($padre->hijos))
					{
						$command2	=	"WaitAnswer[10]";
					}
					array_push($media,array(
												"Command" 	=> $command2,
												"EventData" => null,
												"Indice"	=> "i/".$padre->digito,
												"VoiceName" => $voice,
												"message"	=> ($padre->mensaje),
												"Type"		=> $padre->tipo
											)
							);
					if(isset($padre->hijos))
					{
						foreach($padre->hijos as $hijos)
						{
							if(isset($hijos->mensaje))
							{
								array_push($media,array(
														"Command" 	=> "Hangup",
														"EventData" => null,
														"Indice"	=> "i/".$hijos->ayuda,
														"VoiceName" => $voice,
														"message"	=> ($hijos->mensaje),
														"Type"		=> $hijos->tipo
												)
										);
							}
						}
					}
				}
			}
		}
		//Calcular el tiempo estimado de duración del audio
		$seg_estimado	=	$this->_duration_audio($campana);
		$contacts 	= $this->apps_model->get_contact_campaign(
																$campaign,
																$this->session->userdata('user_id')
															);
		if(!empty($contacts))
		{
			$batch = array();
			/*if($media[0]['Type'] == 1)//and $media[0]['Indice']=="i")
			{
				$cadena = $media[0]['message'];
			}*/
			foreach($contacts as $data)
			{
				//Formación del JSON
				$ind = "";
				if($data->indi_area!=0)
				{
					$ind = 	$data->indi_area;
				}
				
				$tempMedia = array();
				
				$tempMedia = $media;
				
				///Reeemplazar campos dinamiconos
				foreach($tempMedia as $key => $med):
					$cadena = $tempMedia[$key]['message'];
					if($tempMedia[$key]['Type'] == 1)//and $media[0]['Indice']=="i")
					{
						$tempMedia[$key]['message'] = $cadena;
						preg_match_all('/\{(.*?)\}/s',  $tempMedia[$key]['message'], $matches);
						if(isset($matches[1]))
						{
							for($j=0; $j<count($matches[1]); $j++)
							{
								if($matches[1][$j] == "name")
								{
									$tempMedia[$key]['message'] = str_replace("{name}", $data->name, $tempMedia[$key]['message']);
								}
								else
								{
									$fields = $this->apps_model->get_value_fields(
																					$matches[1][$j], 
																					$data->id, 
																					$this->session->userdata('user_id')
																				);
									if(!empty($fields))
									{
										if($fields->tipo == 3)
											$fields->valor; //= $this->convert_to_dd_mm_yyyy($fields->valor)
										$tempMedia[$key]['message'] = str_replace("{".$matches[1][$j]."}", $fields->valor, $tempMedia[$key]['message']);
									}
								}
							}
						}
					}
					$tempMedia[$key]['message'] = ($tempMedia[$key]['message']);
				endforeach;
				//NECESARIO
				$json1 	=	array();
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/encabezado2.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b1.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b2.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/1/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/1/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/1/3",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/1/4",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/1/5",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b2.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/2/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/2/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/2/3",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/2/4",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/2/5",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/3",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b2.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/3/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/3/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/3/3",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/3/4",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/3/5",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/4",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b2.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/4/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/4/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/4/3",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/4/4",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/4/5",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/5",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b2.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/5/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/5/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/5/3",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/5/4",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/5/5",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/6",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b2.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/6/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/6/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/6/3",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/6/4",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/1/6/5",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b2.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b2.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/1/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/1/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/1/3",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/1/4",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/1/5",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b2.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/2/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/2/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/2/3",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/2/4",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/2/5",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/3",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b2.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/3/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/3/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/3/3",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/3/4",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/3/5",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/4",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b2.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/4/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/4/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/4/3",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/4/4",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/4/5",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/5",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b2.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/5/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/5/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/5/3",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/5/4",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/5/5",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"WaitAnswer[10]",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/6",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b2.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/6/1",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/6/2",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/6/3",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/6/4",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
array_push($json1, array(
							'Command'	=>	"Hangup",
							'EventData'	=>	NULL,
							'Indice'	=>	"i/2/6/5",
							'VoiceName'	=>	"DEFAULT",
							'message'	=>	"http://kka.to/public/audios/b6.mp3",
							'Type'		=>	0
							)
			);
				//FIN NECESARIO
				
				//Fin de campos dinamicos
				$json 						= array();
				$json						= new StdClass();
				$json->MediaSource 			= $tempMedia;//
				$json->id 					= $data->id_campaign;
				$phonenew					= str_replace(' ', '', $data->phone);
				$json->phone				= $data->indi_pais.$ind.$phonenew;
				$json->ringtimeout          =$ringtimeout;
				$json->isSMS 				=$isSMS;
				//Envio a la tabla Cola
				array_push($batch,array(
										'id_contact'			=> $data->id,
										'id_campaign' 			=> $ready->id,
										'id_contact_campaign' 	=> $data->id_campaign,
										'id_audio'				=> $ready->id_audio,
										'text_speech'           => $ready->text_speech,
										'fecha'					=> $fecha,
										'hora'					=> $hour,
										'minuto'				=> $minu,
										'phone'					=> $data->phone,
										'area'					=> $data->indi_area,
										'pais'					=> $data->indi_pais,
										'gmt'						=> $timezones,
										'json'					=> json_encode($json),
										'user_id'				=> $this->session->userdata('user_id'),
										'seg_estimado'	=> $seg_estimado,
										'publisher'			=> $publisher,
										'id_wapp'				=> $id_wapp,
										'tipo_wapp'			=> $tipo_wapp
							  		)
						);
			}
			$this->apps_model->insert_batch_queues($batch);

		}
		$inser 	= $this->apps_model->insert_date_campaign(
															$this->session->userdata('user_id'),
															$campaign,
															$fecha,
															$hour,
															$minu,
															$timezones,
															$isSMS
														);
		return $inser;
	}
	
	private function _verified_hijos($hijos)
	{
		$aux 	= 	FALSE;
		foreach($hijos as $hij)
		{
			if(isset($hij->mensaje))
			{
				$aux = TRUE;
				break;
			}
		}
		return $aux;
	}
	/*
	* Función para estimar el precio de la campaña, por la duración de la llamada
	*/
	private function _price_estimated($campaign = 0, $duration = 0)
	{
		set_time_limit(0);
		$contacts 		= $this->apps_model->get_contact_campaign(
																	$campaign,
																	$this->session->userdata('user_id')
																);
		$total_price 	= 0;
		if(!empty($contacts))
		{
			foreach($contacts as $con)
			{
				$number = "";
				$price  = 0;
				$number .= $con->indi_pais;
				if($con->indi_area != "")
				{
					$number .= $con->indi_area;
				}
				$number	.= $con->phone;
				$price 	= $this->apps_model->get_price_contact($number);
				if(!empty($price))
				{
					$total_price += ($price->valor) * ceil($duration/60); 
				}
			}
		}
		return $total_price;
	}
	
	
	public function delete_all_campaign()
	{
		$this->session->keep_flashdata('url');
		$this->lang->load('apps');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('marketplace');
		}
		if ($this->uri->segment(3) !== FALSE)
		{
				$this->load->model('apps_model');
				$result = $this->apps_model->delete_all_campaign($this->session->userdata('user_id'), $this->uri->segment(3));
				if($result)
				{
					$this->session->set_flashdata('exitoso',$this->lang->line('deleteallcampaign'));
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('errordeleteallcampaign'));
				}
				redirect('apps/'.$this->session->flashdata('url'));
		}
	}
	//MOstrar el numero de creditos del usuario
	private function _get_credit_user()
	{
		$this->load->model('hooks_model');
		$result	= $this->hooks_model->get_user($this->session->userdata('user_id'));
		return $result->credits;
	}
	
	//POCHO CODIGO
	public function voice_view()
	{
		$this->session->keep_flashdata('url');
		$this->lang->load('apps');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('marketplace');
		}
		$this->load->model('apps_model');
		$voice			= 	$this->apps_model->get_voice();
		$data 			=	array(
									'voice' => $voice
									);
		$this->load->view('voice_view',$data);
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
	
	/**
	* Función AJAX para pedir un audio profesional a la creadora de la aplicación
	*/
	function request_profesional_audio(){
		$this->session->keep_flashdata('url');
		$id_user = $this->input->post('id_user');
		$id_app  = $this->input->post('id_app');
		$this->load->model('apps_model');
		$this->load->model('user_model');
		$this->load->model('contacts_model');
		
		
		$country_code = $this->contacts_model->get_country_code_user($id_user);
		$user = $this->user_model->get_user_by_id($id_user);
		$creator = $this->apps_model->get_user_data_by_app($id_app);
		$this->lang->load('apps');
		
		if(!empty($creator) && !empty($user)){
			try
			{
				//$country_code = $this->();
				$data = array(
					'fullname' 	=> $user->fullname,
					'date' 		=> date("Y-m-d H:i:s"),
					'phone'		=> $user->phone,
					'country_code' => $country_code->phonecode,
					'email'		=> $user->email
				);
				
				$mensaje 	=	$this->load->view('email/'.$this->config->item('language').'/request_audio',$data,TRUE);
	
				$this->load->library('email');
				$this->email->from($user->email, $user->fullname);
				$this->email->to($creator->email);
				$this->email->subject($this->lang->line('request_audio_subject'));
				$this->email->message($mensaje);
				$this->email->send();
				//log_message('debug', "Correo enviado de peticion de audio");
				echo json_encode(array('cod'=>1, 'messa'=>$this->lang->line('request_audio_success')));
			}catch(ErrorenviarWelcome $e){
				//log_message('debug', "Error enviando correo de kréditos por suscripción");
				echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('request_audio_fail')));
			}
		}else{
			echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('request_audio_fail')));	
		}
		
	}
}