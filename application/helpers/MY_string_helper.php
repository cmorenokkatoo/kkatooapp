<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Formats phone numbers
 * If phone number is longer than 10 digits
 * The rest are considered an extension
 *
 * @param varchar $str The unformated phone number
 * @param boolean $extension TRUE/FALSE to use the extension
 * @return Formated Phone number
 */
function phone_format($phone,$extension = FALSE)
{

	$phone = preg_replace("/[^0-9]/", "", $phone);
 
	if(strlen($phone) == 7)
		return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
	elseif(strlen($phone) == 10)
		return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
	elseif(strlen($phone) == 13)
		return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})([0-9]{2})/", "($1) ($2) $3-$4-$5", $phone);
	else
		return $phone;
}

/** 
* Crea un string sano (slug) para almacenamiento en base de datos
*/
function sanitize_title_with_dashes( $title, $raw_title = '', $context = 'save' ) {
	$title = strip_tags($title);
	// Preserve escaped octets.
	$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
	// Remove percent signs that are not part of an octet.
	$title = str_replace('%', '', $title);
	// Restore octets.
	$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

	if (seems_utf8($title)) {
		if (function_exists('mb_strtolower')) {
			$title = mb_strtolower($title, 'UTF-8');
		}
		$title = utf8_uri_encode($title, 200);
	}

	$title = strtolower($title);
	$title = preg_replace('/&.+?;/', '', $title); // kill entities
	$title = str_replace('.', '-', $title);

	if ( 'save' == $context ) {
		// Convert nbsp, ndash and mdash to hyphens
		$title = str_replace( array( '%c2%a0', '%e2%80%93', '%e2%80%94' ), '-', $title );

		// Strip these characters entirely
		$title = str_replace( array(
			// iexcl and iquest
			'%c2%a1', '%c2%bf',
			// angle quotes
			'%c2%ab', '%c2%bb', '%e2%80%b9', '%e2%80%ba',
			// curly quotes
			'%e2%80%98', '%e2%80%99', '%e2%80%9c', '%e2%80%9d',
			'%e2%80%9a', '%e2%80%9b', '%e2%80%9e', '%e2%80%9f',
			// copy, reg, deg, hellip and trade
			'%c2%a9', '%c2%ae', '%c2%b0', '%e2%80%a6', '%e2%84%a2',
			// acute accents
			'%c2%b4', '%cb%8a', '%cc%81', '%cd%81',
			// grave accent, macron, caron
			'%cc%80', '%cc%84', '%cc%8c',
		), '', $title );

		// Convert times to x
		$title = str_replace( '%c3%97', 'x', $title );
	}

	$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
	$title = preg_replace('/\s+/', '-', $title);
	$title = preg_replace('|-+|', '-', $title);
	$title = trim($title, '-');

	return $title;
}

function seems_utf8($str) {
	$length = strlen($str);
	for ($i=0; $i < $length; $i++) {
		$c = ord($str[$i]);
		if ($c < 0x80) $n = 0; # 0bbbbbbb
		elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
		elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
		elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
		elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
		elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
		else return false; # Does not match any model
		for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
			if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
				return false;
		}
	}
	return true;
}

function utf8_uri_encode( $utf8_string, $length = 0 ) {
	$unicode = '';
	$values = array();
	$num_octets = 1;
	$unicode_length = 0;

	$string_length = strlen( $utf8_string );
	for ($i = 0; $i < $string_length; $i++ ) {

		$value = ord( $utf8_string[ $i ] );

		if ( $value < 128 ) {
			if ( $length && ( $unicode_length >= $length ) )
				break;
			$unicode .= chr($value);
			$unicode_length++;
		} else {
			if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;

			$values[] = $value;

			if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length )
				break;
			if ( count( $values ) == $num_octets ) {
				if ($num_octets == 3) {
					$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
					$unicode_length += 9;
				} else {
					$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
					$unicode_length += 6;
				}

				$values = array();
				$num_octets = 1;
			}
		}
	}

	return $unicode;
}
/**********************************************************************************************************************************************************/
function login_in()
	{
		$CI = get_instance();
		return $CI->session->userdata('logged_in');
	}

function check_special(){
	 $CI = get_instance();
		return $CI->specialapp->get('special');
	}
function return_to_special_url(){
	$CI = get_instance();
		if(check_special()){
			$CI->session->set_flashdata('error',$CI->lang->line('loginplease'));
			return base_url('login/login');
		}
	}
function get_credit_user()
	{
		$CI = get_instance();
		$CI->load->model('hooks_model');
		$result	= $CI->hooks_model->get_user($CI->session->userdata('user_id'));
		return $result->credits;
	}
function duration_audio($campaign)
	{	$CI = get_instance();
		$CI->load->model('apps_model');
		$duration	= 	0;
		//Calcular duración del audio
		$marcado 	= 	json_decode($campaign->marcado);
		$audio 		= 	$CI->apps_model->get_audio($campaign->id_audio);
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

function price_estimated($campaign = 0, $duration = 0)
	{
		$CI = get_instance();
		$CI->load->model('apps_model');

		set_time_limit(0);
		$contacts 		= $CI->apps_model->get_contact_campaign(
																	$campaign,
																	$CI->session->userdata('user_id')
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
				$price 	= $CI->apps_model->get_price_contact($number);
				if(!empty($price))
				{
					$total_price += ($price->valor) * ceil($duration/60); 
				}
			}
		}
		return $total_price;
	}

function load_step_one($data)
	{
		$CI = get_instance();
		$get = ($CI->input->get_post('page'))?$CI->input->get_post('page'):1;
		
		//echo $result->tipo;
		$contacts   				= array();
		$contacts_campaign	= array();
		$country						= array();
		$groups							= array();
		$campaign 					= array();
		$name_campaign			= "Mi Campaña";
		$id_campaign				= 0;
		$total   						= 0;
		$total_credits			= 0;
		$fields							= array();
		$totalC 						= 0;
		$totalCC						= 0;			
		
		$CI->load->model('apps_model');
		$credits_app		= ($result->uses_special_pines==1)?$CI->apps_model->get_user_app_credits($CI->session->userdata('user_id'), $result->id):'0';
		
		if(login_in())
		{
			$campaign 	= $CI->apps_model->get_campaign($CI->session->userdata('user_id'),$result->id);
			$country		= $CI->apps_model->get_country();
			$groups			= $CI->apps_model->get_group($CI->session->userdata('user_id'));
			$fields			= $CI->apps_model->get_fields($result->id);
			
			// verifica si existe la campana
			if(empty($campaign))
			{
				// Si no existe inicializa la campana
				$datos			= array(
										 'id_wapp' => $result->id,
										 'user_id' => $CI->session->userdata('user_id')
										);
				//Si un usuario esta conectado se le inicia la campaña automaticamente
				$ini_campaign 	= $CI->apps_model->ini_campaign($datos);
				if(!empty($ini_campaign))
				{
					$id_campaign = $ini_campaign;
				}
				
				/*if($result->tipo == 1){
					$contacts 	= $CI->_get_contacts_subscribe($result->id,$fields);
				}else{
					$contacts 	= $CI->_get_contacts($result->id,$fields);
				}*/
				
			}
			else
			{
				
				$id_campaign  = $campaign->id;
				
				//$campaign_f   = $CI->apps_model->get_campaign_contacts($campaign->id);
								
				/*if(empty($campaign_f))
				{
					
					if($result->tipo == 1){
						$contacts 	= $CI->_get_contacts_subscribe($result->id,$fields);
					}else{
						$contacts 	= $CI->_get_contacts($result->id,$fields);
					}
					
				}	
				else
				{
					
					//Busca los contactos filtrando por la campaña
					$contacts 	= $CI->_get_contacts_filter_campaign(
																		$campaign->id,
																		$result->id,
																		$fields
																	 );
				}*/
				
				//Consulta los usuarios asociados a la campaña que alguna vez inició
				$contacts_campaign = $CI->apps_model->get_contact_campaign($campaign->id,$CI->session->userdata('user_id'), true);
				$totalCC 	= $CI->apps_model->foundRows()->cuantos;
				
				if(!empty($contacts_campaign))
				{
					$total = count($contacts_campaign);
				}
				$name_campaign = $campaign->name;
			}
			
			$contacts = $CI->apps_model->get_mega_filtro_contacts($CI->session->userdata('user_id'), $id_campaign, $result->id);
			$totalC 	= $CI->apps_model->foundRows()->cuantos;
			
			$total_credits	= $CI->get_credit_user();
		}
		
		$CI->load->helper('string');
		$CI->load->library( 'ci_google' );
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
							'link_gp'   				=> $CI->ci_google->get_url_connect("contactos"),
							'credits_app'				=> $credits_app,
							'id_wapp'						=> $result->id,
							'totalC'						=> $totalC,
							'totalCC'						=> $totalCC
							);
		$CI->session->set_flashdata('url',$result->uri);
		// $CI->_view_step_one($data);
	}

	function load_step_three($result)
	{
		$id_campaign		= 0;
		$duration			= 0;
		$total_credits		= 0;
		//Variables de cobro
		$utilidad			= 1;
		$publisher			= (($result->price)/100);   //formula one pasar de porcentaje a decimal
		
		$CI->load->model('apps_model');
		$credits_app		= ($result->uses_special_pines==1)?$CI->apps_model->get_user_app_credits($CI->session->userdata('user_id'), $result->id):'0';
		//Fin Variables de ocbro
		//$total_price		= 0;
		$CI->load->helper('date');
		$CI->load->helper('cookie');
		if(login_in())
		{
			$campaign 	= $CI->apps_model->get_campaign($CI->session->userdata('user_id'),$result->id);
			if(empty($campaign))
			{
				$CI->session->set_flashdata('error',$CI->lang->line('nocampaigncreate'));
				redirect('apps/'.$result->uri);	
			}
			else
			{
				$aux 	= $CI->apps_model->get_audio_text_campaign($CI->session->userdata('user_id'),$campaign->id);
				$id_campaign  		= $campaign->id;
				if(!empty($aux))
				{
					$CI->session->set_flashdata('error',$CI->lang->line('erroraudioexist'));
					redirect('apps/'.$result->uri.'/2');	
				}
				else
				{
					$duration		= 	$CI->duration_audio($campaign);
					$total_price	=	$CI->price_estimated($id_campaign, $duration);
					$total_price	=	$total_price; //subformula one // * $utilidad * $publisher
				}
			}
			$total_credits	= $CI->get_credit_user();
		}
		if($total_price == 0)
		{
			$CI->session->set_flashdata('error',$CI->lang->line('errorcall'));
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
		$CI->session->set_flashdata('url',$result->uri);
		// $CI->_view_step_three($data);
	}
	/*
	 * Funcion privada para cargar el paso 2 de las llamadas de kkatoo
	*/
	function load_step_two($result)
	{
		$CI = get_instance();
		$id_campaign		= 0;
		$total_credits		= 0;
		$audiosApp 			= array();
		$audiocampaign		= array();
		$textcampaign		= array();
		$fields				= array();
		$voice 				= array();
		$name_campaign		= "";		
		
		$CI->load->model('apps_model');
		$credits_app		= ($result->uses_special_pines==1)?$CI->apps_model->get_user_app_credits($CI->session->userdata('user_id'), $result->id):'0';
		
		if(!login_in())
		{
			//The Special app redirect
			$CI->return_to_special_url();
			
			$CI->session->set_flashdata('error',$CI->lang->line('loginplease'));
			redirect('login/login');
		}
		
		$campaign 	= $CI->apps_model->get_campaign($CI->session->userdata('user_id'),$result->id);
		if(empty($campaign))
		{
			$CI->session->set_flashdata('error',$CI->lang->line('nocampaigncreate'));
			redirect('apps/'.$result->uri);	
		}
		else
		{
			$id_campaign  			= $campaign->id;
			$name_campaign			= $campaign->name;
			
			if(empty($name_campaign)){
				$CI->session->set_flashdata('error',$CI->lang->line('no_campaign_name'));
				redirect('apps/'.$result->uri);	
				die();
			}
			
			$contacts_campaign 	= $CI->apps_model->get_contact_campaign($campaign->id,$CI->session->userdata('user_id'));
			if(empty($contacts_campaign))
			{
				$CI->session->set_flashdata('error',$CI->lang->line('nocontactscampaign'));
				redirect('apps/'.$result->uri);	
			}
			else
			{
				$audiosApp 		= $CI->apps_model->get_audio_app($CI->session->userdata('user_id'),$result->id);
				$pagiAudios 	= $CI->apps_model->total_audio_app();
				$audiocampaign 	= $CI->apps_model->get_audio_campaign($CI->session->userdata('user_id'),$id_campaign);
				$textcampaign 	= $CI->apps_model->get_text_campaign($CI->session->userdata('user_id'),$id_campaign);
				$fields			= $CI->apps_model->get_fields($result->id);
				$voice			= $CI->apps_model->get_voice();
			}
		}
		$total_credits	= $CI->get_credit_user();
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
		$CI->session->set_flashdata('url',$result->uri);
		
		/**
		* Intro y Cierre
		*/
		if($campaign->intro)
			$data['intro'] = $CI->apps_model->get_intro_close($campaign->intro);
		
		if($campaign->cierre)
			$data['cierre'] = $CI->apps_model->get_intro_close($campaign->cierre);
		
		/** Información para la librería de contenidos **/
		// DATOS DE LA LIBRERÍA DE CONTENIDOS
		$CI->load->model('wizard_model');
		$data["audios"] 	= $CI->wizard_model->get_library_audios_by_app($result->id, $CI->session->userdata('user_id'));
		$data["records"] 	= $CI->wizard_model->get_library_records_by_app($result->id, $CI->session->userdata('user_id'));
		$data["texts"] 		= $CI->wizard_model->get_library_texts_by_app($result->id, $CI->session->userdata('user_id'));
		$data["library"]	= $CI->wizard_model->get_library_content($result->id, $CI->session->userdata('user_id'));
		$data["dynamic"] 	= $CI->wizard_model->get_dynamic_fields($result->id);
		
		$data['voice'] 	  	= $CI->apps_model->get_voice();
		
		// $CI->_view_step_two($data);
	}

/**********************************************************************************************************************/

function idkkatoo()
	{
		$CI = get_instance();
		ini_set('display_errors', 'on');
		set_time_limit(0);
		$CI->lang->load('apps');
		$uri_app 	=	$CI->uri->segment(2);
		
		if($uri_app !== FALSE)
		{
			$CI->load->model('apps_model');
			$result 	=	$CI->apps_model->get_uri_app($uri_app);
				if(check_if_can_use($result)){
					
					if($CI->uri->segment(3)===FALSE)
					{
						$CI->load_step_one($result);
					}
					elseif($CI->uri->segment(3)==2)
					{
						if(!login_in())
						{
							//The Special app redirect
							$CI->return_to_special_url();
							
							$CI->session->set_flashdata('error',$CI->lang->line('initapp'));
							redirect('login/login?rtrn=/apps/kkatoo');
						}
						else
						{
							$CI->load_step_two($result);	
						}
					}
					elseif($CI->uri->segment(3)==3)
					{
						if(!$CI->login_in())
						{
							//The Special app redirect
							$CI->return_to_special_url();
							
							$CI->session->set_flashdata('error',$CI->lang->line('initapp'));
							redirect('login/login?rtrn=/apps/kkatoo');
						}
						else
						{
							$CI->load_step_three($result);	
						}
					}
					else
					{
						$CI->session->set_flashdata('error',$CI->lang->line('appnofound'));
						// redirect('marketplace');
					}
				}else{
					//The Special app redirect
					$CI->return_to_special_url();
					
					$CI->session->set_flashdata('error',$CI->lang->line('notpermitedappsubs'));
					// redirect('marketplace');
				}
			// }
		}
		else
		{
			//The Special app redirect
			$CI->return_to_special_url();
			
			$CI->session->set_flashdata('error',$CI->lang->line('appnofound'));
			// redirect('marketplace');
		}
	}

// Función para iniciar sesión con datos de usuario email y password.
// function test($email, $password, $data, $retorno){
// 	 // Get a reference to the controller object
//     $CI = get_instance();
//     // You may need to load the model if it hasn't been pre-loaded
//     $CI->load->model('user_model');
//     $CI->lang->load('login');
//     if (login_in()) 
// 	{
// 		if(check_special()){
// 			return base_url('apps/'.$CI->specialapp->get('uri'));
// 		}
// 		return base_url('apps/kkatoo'); 
// 	}	
	
//     if (!isset($email) or empty($email) or !isset($password) or  empty($password))
// 	{
// 		$mensajeError = array('code' => 0, 'msj' => "Los parámetros están incorrectos");
// 		return $mensajeError;
// 	}
// 	else
// 	{
// 		 $user 	=	$CI->user_model->login_in($email, $password);
// 		 if(empty($user))
// 		{	
// 			$mensajeError = array('code' => 0, 'msj' => $CI->lang->line('nouser'));
// 			return $mensajeError;
// 		}
// 		else
// 		{
// 			//Inicio de la sessión del usuario..
// 			$data = array(
// 							'email'		=>$user->email,
// 							'fullname'	=>$user->fullname,
// 							'user_id'	=>$user->id,
// 							'credits'	=>$user->credits
// 						);
// 			$CI->user_model->init_session($data);
// 			$retorno = $CI->session->flashdata('retorno'); 
// 			if(empty($retorno))
// 			{
// 				if(check_special()){
					
// 					if($user->first_time == 1){
// 						$CI->user_model->user_first_time_update($user->id);
// 						$CI->session->set_flashdata('exitoso',$CI->lang->line('firstkedits'));
// 						return base_url('payment?prtrn=apps/'.$CI->specialapp->get('uri'));
// 					}
// 					else{
// 						return base_url('apps/'.$CI->specialapp->get('uri'));
// 					}
					
// 				 }
// 				return $data;
// 			}
// 			else
// 			{
// 				return $data;
// 			}
// 		}
// 	}
// }


function detail_campaign($exist)
	{
		// Get a reference to the controller object
    	$CI = get_instance();
    // You may need to load the model if it hasn't been pre-loaded
    	$CI->load->model('campaign_model');
		ini_set('display_errors', 'on');
		$CI->lang->load('campaign');
		if(!login_in())
		{
			//The Special app redirect
			$CI->return_to_special_url();
			
			$CI->session->set_flashdata('error',$CI->lang->line('loginplease'));
			return base_url('marketplace');
		}
		if($CI->uri->segment(3) !== FALSE)
		{
			// $CI->load->model('campaign_model');
			$exist	= 	$CI->campaign_model->get_campaign_exist($CI->session->userdata('user_id'), $CI->uri->segment(3));
			if(!empty($exist))
			{
				// date_default_timezone_set('Africa/Casablanca');
				$CI->load->helper('date');
				$result	= 	$CI->campaign_model->get_campaign_queues($CI->session->userdata('user_id'), $CI->uri->segment(3));
				$pendi	= 	$CI->campaign_model->get_campaign_pendi($CI->session->userdata('user_id'), $CI->uri->segment(3));
				$price	= 	$CI->campaign_model->sum_campaign_real($CI->session->userdata('user_id'), $CI->uri->segment(3));
				$call	= 	$CI->campaign_model->get_call_reali($CI->session->userdata('user_id'), $CI->uri->segment(3));
				$exito	= 	$CI->campaign_model->get_call_exitosa($CI->session->userdata('user_id'), $CI->uri->segment(3));				
				$campaign 	= 	$CI->campaign_model->get_campaign($CI->session->userdata('user_id'));
				$campaign_name;
				$campaign_date;
				if(!empty($campaign))
				{
					foreach($campaign as $cam)
					{
						//si el id de la campaña en el array ($campaign) es igual al id de la campaña en la vista, guardamos el nombre.
						if($cam->id == $CI->uri->segment(3))
						{
							$campaign_name =  $cam->name;
							$campaign_date = $cam->fecha;
						}
					}
				}

				$data	=	array(
									'detalle'		=> $result,
									'total_call'	=> count($result),
									'pendi_call'	=> $pendi,
									'price_real'	=> $price,
									'call'			=> $call,
									'exito'			=> $exito,
									'id_camp'		=> $CI->uri->segment(3),
									'campaign_name' => $campaign_name,
									'campaign_date'	=> $campaign_date
									);


				// $CI->_view_detail_campaign($data); 
			}
			else
			{
				$CI->session->set_flashdata('error',$CI->lang->line('campaignno'));
				redirect('campaign');
			}
		}
		else
		{
			$CI->session->set_flashdata('error',$CI->lang->line('campaignno'));
			redirect('campaign');
		}
	}
?>