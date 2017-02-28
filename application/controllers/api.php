<?php 

 require_once APPPATH."libraries/REST_Controller.php"; 
class Api extends REST_Controller{
    /*
   Function: validateUser

   Valida la información del usuario

   Parameters:

      email - Correo registrado en kkatoo
      password - Contraseña usada en el registro
      uri_app - Url de la aplicación que se usará
      

   Returns:

      Información del usuario y la aplicación que se usará. Importantes: id de usuario y id de aplicación.

   

      
*/
    public function validateUser_post(){
		
        // Valido los datos del usuario
        $this->load->model('user_model');
        $email = $this->post('email');
        $password = $this->post('password');
        $validate = $this->user_model->login_in($email,$password);
        $this->load->model('apps_model');
        $uri_app = $this->post('uri_app');
        $uri = $this->apps_model->get_uri_app($uri_app);
        $user_id = $validate->id;
        $datavalidate = array('validate' => $validate, 'user_id' => $user_id);
        if ($validate == FALSE) {
            $this->response(array('Estado' => 'Email o Password incorrectos. Verifique sus datos de usuario.'));
            die();
        }
        else{
            // if ('uri_app') {
            //     # code...
            // }
            $this->response($datavalidate);
        }
        
    }
    /*
   Function: getCallInfo

   Trae el total de las llamadas realizadas por el usuario. Se puede filtrar por estado de la llamada o del SMS.

   Parameters:

    email - El correo electrónico registrado por el usuario
    password - Contraseña asignada en el registro
    uri_app - Url de la aplicación que se usa dentro de Kkatoo
    state - (2)falta crédito, (3)llamada exitosa, (4)llamada no contestada, (5)SMS entregado, (6)SMS no entregado
    id_wapp - Es el id de la aplicación que estamos usando. Se relacionan el estado y el id de la aplicación.

      

   Returns:

      Información de todos los envíos del usuario con estado, fecha, hora y teléfono.

   

      
*/
    public function getCallInfo_post()
    {
        // Valido los datos del usuario
        $this->load->model('user_model');
        $email = $this->post('email');
        $password = $this->post('password');
        $validate = $this->user_model->login_in($email,$password);
        
        if ($validate == FALSE) {
            $this->response(array('Estado' => 'Email o Password incorrectos. Verifique sus datos de usuario.'));
            die();
        }
        else{
            $this->load->model('apps_model');
            $uri_app = $this->post('uri_app');
            $uri = $this->apps_model->get_uri_app($uri_app);
            $user_id = $validate->id;
            // datos de la llamada
            $this->load->model('campaign_model');
            $user_id = $validate->id;
            $state = $this->post('state');
            $id_wapp = $this->post('id_wapp');
            // $fecha = $this->post('fecha');
            $queue_info = $this->campaign_model->get_colas($user_id, $state, $id_wapp);
            $data = array('validate' => $validate, 'queue_info' => $queue_info, 'uri' => $uri, 'user_id' => $user_id);
            $this->response($data);
        }
        
    }
    
    /*
   Function: makeCall

   Envía la llamada o el SMS al contacto con el mensaje indicado en la fecha y hora asignadas.

   Parameters:

    email - El correo electrónico registrado por el usuario
    password - Contraseña asignada en el registro
    mensaje - El mensaje que será enviado para la llamada o SMS. Nuestro sistema leerá el texto y lo pasará a voz.
    SMS - 1 para enviar como SMS 0 para desactivarlo
    ringtimeout - Tiempo de marcado en la llamada, a mayor valor más tiempo durará el marcado de llamada. Por defecto es de 35 segundos
    id_campaign - Si ya existe una campaña previa, podrá usar el id de la campaña para agregar llamadas o SMS. Si desea crear una nueva campaña no de ningún valor, debe tener en cuenta que si no asigna un valor, tendrá una campaña nueva por cada llamada que realice de esta forma. Si le asigna un valor, agrupará llamadas o SMS en una sola campaña.
    fecha - Fecha en la que será despachada la llamada o el sms.
    hora - Hora en la que será realizará la llamada o se enviará el SMS.
    minuto - Está relacionado a la hora en la que se realizará el envío.
    phone - Número de teléfono al que será enviada la llamada o el SMS. Si es un teléfono fijo deberá estar antecedido por el número de área (ciudad o estado).
    país - El código del país del contacto, para Colombia es 57.
    user_id - Id del usuario relacionado con el email y contraseña
    gmt - Huso horario para la llamada, para Colombia es UM5.
    id_wapp - Id de la aplicación que se está usando para realizar las llamadas.
      

   Returns:

      Id del envío (llamada o SMS).

   

      
*/
    public function makeCall_post()
    {
        // Valido los datos del usuario
        $this->load->model('user_model');
        $email = $this->post('email');
        $password = $this->post('password');
        $validate = $this->user_model->login_in($email,$password);
        $this->load->model('apps_model');
        $uri_app = $this->post('uri_app');
        $uri = $this->apps_model->get_uri_app($uri_app);
        $user_id = $validate->id;
        $this->load->model('campaign_model');
        
        if ($validate == FALSE) {
            $this->response(array('Estado' => 'Email o Password incorrectos. Verifique sus datos de usuario.'));
            die();
        }
        else{

        // Creando la cola
        $this->load->model('queues_model');
        $this->load->model('contacts_model');
        $id_campaign = $this->post('id_campaign');
        // Condicional si la campaña no existe, crea una nueva con nombre = fecha y hora
        if ($id_campaign == '') {
            $this->load->model('user_model');
            $email = $this->post('email');
            $password = $this->post('password');
            $validate = $this->user_model->login_in($email,$password);
            $this->load->model('apps_model');
            $uri_app = $this->post('uri_app');
            $uri = $this->apps_model->get_uri_app($uri_app);
            $this->load->model('apps_model');
            $id_wapp = $this->post('id_wapp');
            $user_id = $validate->id;
            $fecha = $this->post('fecha');
            $state = 1;
            $hora = $this->post('hora');
            $minuto = $this->post('minuto');
            $gmt = $this->post('gmt');
            $voice = $this->post('voice'); 
            $name = $fecha. "  " . $hora . ":" . $minuto;
            $tipo_sms = $this->post('SMS'); //Antes aqui decia tipo_sms en vez de SMS ... Asumi que era un error y lo cambie Juan Manuel
            $api = 1;
            $id_campaign = $this->apps_model->insert_campaign_api($id_wapp, $user_id, $fecha, $state, $hora, $minuto, $gmt, $voice, $name, $tipo_sms, $api);
        }     
        $fecha = $this->post('fecha');
        $hora = $this->post('hora');
        $minuto = $this->post('minuto');
        $voice = 'DEFAULT'; 
        $state = 0;
        $phone = $this->post('phone');
        $pais = $this->post('pais');
        $ringtimeout = $this->post('timeout'); //tiempo de marcado
        $isSMS = $this->post('SMS'); // para enviar SMS 1 si 0 no
        $seg_estimado = 59;
        $gmt = 'UM5';
        $id_contact = 344938; // Constante
        $id_wapp = $this->post('id_wapp');
        $id_app = $id_wapp;
        $tipo_wapp = 2; // por defecto es dos para las apps de difusión
        $message = $this->post('mensaje');
        $publisher = 0.43;//$this->apps_model->get_app_price($id_app);
        $price_real = '';
        // Inserta el contacto constante
        $id_contact_user = 344938;
        $id_contact_campaign = 0;
        $id_contact_campaign = $this->contacts_model->insert_id_contact_campaign($id_campaign, $id_contact_user, $user_id);
        // Creando el JSON a enviar
        $decode[] = array(
                'Command' => 'Hangup',
                'EventData' => 'null',
                'Indice' => 'i',
                'VoiceName' => $voice,
                'message' => $message,
                'Type' => '1'
            );
        $decodetotal = array('MediaSource' => $decode, 'id' => $id_contact_campaign,
                'phone' => $pais.$phone, 'ringtimeout' => $ringtimeout, 'isSMS' => $isSMS);
        // Fin json
        // Inserta la llamada en la tabla queues
        $insert_queue = $this->queues_model->insert_queue_api($id_campaign, $id_contact_campaign, $fecha, $hora, $minuto, $voice, $state, $phone, $pais, json_encode($decodetotal), $user_id, $seg_estimado, $gmt, $price_real, $id_contact, $publisher, $id_wapp, $tipo_wapp);   

        $sendqueue = array('validate' => $validate,'uri' => $uri,'set_queue' => $insert_queue);

        $this->response($sendqueue, 200);
        }
        
    }
	
	
	 /*
   Function: makeCallAdvanced

   Envía la llamada o el SMS al contacto con el mensaje indicado en la fecha y hora asignadas.

   Parameters:

    email - El correo electrónico registrado por el usuario
    password - Contraseña asignada en el registro
    mensaje - El mensaje que será enviado para la llamada o SMS. Nuestro sistema leerá el texto y lo pasará a voz.
    SMS - 1 para enviar como SMS 0 para desactivarlo
    ringtimeout - Tiempo de marcado en la llamada, a mayor valor más tiempo durará el marcado de llamada. Por defecto es de 35 segundos
    id_campaign - Si ya existe una campaña previa, podrá usar el id de la campaña para agregar llamadas o SMS. Si desea crear una nueva campaña no de ningún valor, debe tener en cuenta que si no asigna un valor, tendrá una campaña nueva por cada llamada que realice de esta forma. Si le asigna un valor, agrupará llamadas o SMS en una sola campaña.
    fecha - Fecha en la que será despachada la llamada o el sms.
    hora - Hora en la que será realizará la llamada o se enviará el SMS.
    minuto - Está relacionado a la hora en la que se realizará el envío.
    phone - Número de teléfono al que será enviada la llamada o el SMS. Si es un teléfono fijo deberá estar antecedido por el número de área (ciudad o estado).
    país - El código del país del contacto, para Colombia es 57.
    user_id - Id del usuario relacionado con el email y contraseña
    gmt - Huso horario para la llamada, para Colombia es UM5.
    id_wapp - Id de la aplicación que se está usando para realizar las llamadas.
		

   Returns:

      Id del envío (llamada o SMS).

   

      
*/
    public function makeCallAdvanced_post()
    {
        // Valido los datos del usuario
        $this->load->model('user_model');
        $email = $this->post('email');
        $password = $this->post('password');
        $validate = $this->user_model->login_in($email,$password);
        $this->load->model('apps_model');
        $uri_app = $this->post('uri_app');
        $uri = $this->apps_model->get_uri_app($uri_app);
        $user_id = $validate->id;
		$bussines_hour =  $this->post('bussines_hour_indicator');
        $this->load->model('campaign_model');
		$request_date =   date('Y-m-d');
		$enterprice = $this->post('enterprice');
		$position = $this->post('position');
		$enterprice_user =  $this->post('enterprice_user');
		$document = $this->post('document');
		$city = $this->post('document');
		$external_campaign_id =  $this->post('external_campaign_id');
		
        
        if ($validate == FALSE) {
            $this->response(array('Estado' => 'Email o Password incorrectos. Verifique sus datos de usuario.'));
            die();
        }
        else{

        // Creando la cola
        $this->load->model('queues_model');
        $this->load->model('contacts_model');
        $id_campaign = $this->post('id_campaign');
		
		
		
        // Condicional si la campaña no existe, crea una nueva con nombre = fecha y hora
        if ($id_campaign == '' && !empty($external_campaign_id)) {
            $this->load->model('user_model');
            $email = $this->post('email');
            $password = $this->post('password');
            $validate = $this->user_model->login_in($email,$password);
            $this->load->model('apps_model');
            $uri_app = $this->post('uri_app');
            $uri = $this->apps_model->get_uri_app($uri_app);
            $this->load->model('apps_model');
            $id_wapp = $this->post('id_wapp');
            $user_id = $validate->id;
            $fecha = $this->post('fecha');
            $state = 1;
            $hora = $this->post('hora');
            $minuto = $this->post('minuto');
            $gmt = $this->post('gmt');
            $voice = $this->post('voice'); 
            $name = $fecha. "  " . $hora . ":" . $minuto;
            $tipo_sms = $this->post('SMS'); //Antes aqui decia tipo_sms en vez de SMS ... Asumi que era un error y lo cambie Juan Manuel
            $api = 1;
			
			// SE VERIFICA SI TIENE INDICADOR DE HORARIO LABORAL Y SI LA FECHA QUE SE SOLICITA LA LLAMADA ESTA EN HORARIO NO HABIL SE PROGRAMA PARA EL SIGUIENTE DIA A LA PRIMERA HORA HABIL
		//die($bussines_hour);
			if(strtolower($bussines_hour)=='true' ){
				
				if(((intval($hora)>= 19) and intval($minuto)>30) or (intval($hora)>= 20)  ){
				
					$hora=7;
					
					$minuto=0;
					
					$date = new DateTime($fecha);

					
					$date->modify('+1 day');
					
					$fecha=$date->format('Y/m/d');
					
				
				}
				
				
			}
			
			
            $id_campaign = $this->apps_model->persist_campaign_api_with_external_id($id_wapp, $user_id, $fecha, $state, $hora, $minuto, $gmt, $voice, $name, $tipo_sms, $api, $external_campaign_id);
        }    
		
        $fecha = $this->post('fecha');
        $hora = $this->post('hora');
        $minuto = $this->post('minuto');
        $voice = 'DEFAULT'; 
        $state = 0;
        $phone = $this->post('phone');
        $pais = $this->post('pais');
        $ringtimeout = $this->post('timeout'); //tiempo de marcado
        $isSMS = $this->post('SMS'); // para enviar SMS 1 si 0 no
        $seg_estimado = 59;
        $gmt = 'UM5';
        $id_contact = 344938; // Constante
        $id_wapp = $this->post('id_wapp');
        $id_app = $id_wapp;
        $tipo_wapp = 2; // por defecto es dos para las apps de difusión
        $message = $this->post('mensaje');
        $publisher = 0.43;//$this->apps_model->get_app_price($id_app);
        $price_real = '';
        // Inserta el contacto constante
        $id_contact_user = 344938;
        $id_contact_campaign = 0;
        $id_contact_campaign = $this->contacts_model->insert_id_contact_campaign($id_campaign, $id_contact_user, $user_id);
        // Creando el JSON a enviar
        $decode[] = array(
                'Command' => 'Hangup',
                'EventData' => 'null',
                'Indice' => 'i',
                'VoiceName' => $voice,
                'message' => $message,
                'Type' => '1'
            );
        $decodetotal = array('MediaSource' => $decode, 'id' => $id_contact_campaign,
                'phone' => $pais.$phone, 'ringtimeout' => $ringtimeout, 'isSMS' => $isSMS);
        // Fin json
        // Inserta la llamada en la tabla queues advanced
							                  
        $insert_queue = $this->queues_model->insert_queue_api_advanced($id_campaign, $id_contact_campaign, $fecha, $hora, $minuto, $voice, $state, $phone, $pais, json_encode($decodetotal), $user_id, $seg_estimado, $gmt, $price_real, $id_contact, $publisher, $id_wapp, $tipo_wapp,$request_date, $enterprice, $position, $enterprice_user, $document, $city);   

        $sendqueue = array('validate' => $validate,'uri' => $uri,'set_queue' => $insert_queue);

        $this->response($sendqueue, 200);
        }
        
    }
    /*
   Function: createCampaign

   Crear una campaña vacía para agrupar llamadas y/o SMS

   Parameters:

    email - El correo electrónico registrado por el usuario
    password - Contraseña asignada en el registro
    id_wapp - Id de la aplicación que se está usando para realizar las llamadas.
    user_id - Id del usuario relacionado con el email y contraseña
    fecha - Fecha en que será enviada la campaña.
    hora - Hora programada para iniciar la campaña.
    minuto - Relacionado con la hora de la campaña.
    name - Puede nombrar la campaña o dejar vacío. Si se deja vacío la campaña tendrá como nombre la fecha y hora establecidas.   
    SMS - Determina si la campaña será de SMS (1) o no (0).   

   Returns:

      Id de la campaña vacía creada. Puede usarse para makeCall o para getCampaignDetails

   

      
*/
    public function createCampaign_post()
    {
        // $this->_set_delivered_call();
        $this->load->model('user_model');
        $email = $this->post('email');
        $password = $this->post('password');
        $validate = $this->user_model->login_in($email,$password);
        $this->load->model('apps_model');
        $uri_app = $this->post('uri_app');
        $uri = $this->apps_model->get_uri_app($uri_app);      
        if ($validate == FALSE) {
            $this->response(array('Estado' => 'Email o Password incorrectos. Verifique sus datos de usuario.'));
            die();
        }
        else{
        // Creando la campaña
        $this->load->model('apps_model');
        $id_wapp = $this->post('id_wapp');
        $user_id = $validate->id;
        $fecha = $this->post('fecha');
        $state = 1;
        $hora = $this->post('hora');
        $minuto = $this->post('minuto');
        $gmt = 'UM5';
        $voice = 0; 
        $name = $this->post('name');
        if ($name == '') {
            $name = $fecha. "  " . $hora . ":" . $minuto;
        }
        $tipo_sms = $this->post('SMS');
        $api = 1;
        $inicamp = $this->apps_model->insert_campaign_api($id_wapp, $user_id, $fecha, $state, $hora, $minuto, $gmt, $voice, $name, $tipo_sms, $api);
        $datavalidate = array('uri' => $uri, 'validate' => $validate, 'user_id' => $user_id, 'inicamp' => $inicamp);
             $this->response($datavalidate);
        }
       
    }
    /*
   Function: getCampaignDetails

   Informe de campañas realizadas

   Parameters:

    email - El correo electrónico registrado por el usuario
    password - Contraseña asignada en el registro
    user_id - Id del usuario relacionado con el email y contraseña     
    id_campaign - Id de la campaña para el informe.

   Returns:

      Detalle de la campaña específica con los datos de cada llamada o SMS

   

      
*/
    public function getCampaignDetails_post()
    {
        // Valido los datos del usuario
        $this->load->model('user_model');
        $email = $this->post('email');
        $password = $this->post('password');
        $validate = $this->user_model->login_in($email,$password);
        $this->load->model('apps_model');
        $uri_app = $this->post('uri_app');
        $uri = $this->apps_model->get_uri_app($uri_app);
        $user_id = $validate->id;
        

        if ($validate == FALSE) {
            $this->response(array('Estado' => 'Email o Password incorrectos. Verifique sus datos de usuario.'));
            die();
        }
        else{
            // Obteniendo el detalle de la campaña
        $this->load->model('campaign_model');
        $id_campaign = $this->post('id_campaign');
        $detalle = $this->campaign_model->get_detail_campaign($id_campaign);
        $this->response($detalle);
        }

        
    }
	
	
	 public function getCallsBetweenDates_post()
    {
        // Valido los datos del usuario
        $this->load->model('user_model');
        $email = $this->post('email');
        $password = $this->post('password');
        $validate = $this->user_model->login_in($email,$password);
        $this->load->model('apps_model');
        $from = $this->post('from');
		$to = $this->post('to');
    
       
		if(empty($email) or $email===0 ){
			$this->response(array('Estado' => 'No se ha ingreado el campo email por favor verifique e intente nuevamente.'));
            die();
			
		}
		
		if(empty($password) or $password===0){
			$this->response(array('Estado' => 'No se ha ingreado el campo password nuevamente.'));
			 die();
		}
		
		 $user_id = $validate->id;
        

        if ($validate == FALSE) {
            $this->response(array('Estado' => 'Email o Password incorrectos. Verifique sus datos de usuario.'));
            die();
        }
        else{
            // Obteniendo el detalle de la campaña
			$this->load->model('campaign_model');
		
			$detalle = $this->campaign_model->get_detail_calls_between_dates($user_id,$from,$to);
			$this->response($detalle);
        }

        
    }
	
/*
   Function: CreateNewUser

   Crear un nuevo usuario

   Parameters:

    email - El correo electrónico/usuario a registrar
    password - Contraseña a registrar
    Fullname - nombre personal / empresa a registrar     
    phone - teléfono del usuario móvil o fijo
    indi_pais - Indicativo del país del usuario (57) para Colombia.

   Returns:

      id del usuario creado.

   

      
*/
      public function CreateNewUser_post()
      {
        $this->load->model('user_model');
        $email = $this->post('user'); 
        $password = $this->post('password');
        $fullname = $this->post('name');
        $verified= 1; 
        $phone = $this->post('phone'); 
        $indi_pais = $this->post('indipais'); 
        $first_time = 1;
        $credits = 2000;

        $createUser = $this->user_model->insert_user_api($email, $password, $fullname, $verified, $phone, $indi_pais, $first_time, $credits);
        $this->response($createUser);
      }
/*
   Function: UpdateEmail

   Editar email de usuario

   Parameters:

    email - El correo electrónico/usuario a editar
    user_id - id del usuario

   Returns:

      email nuevo

         
*/
      public function UpdateEmail_post()
      {
        $this->load->model('user_model');
        $email = $this->post('user'); 
        $user_id = $this->post('id');

        $updateEmail = $this->user_model->update_user_email($user_id, $email);
        if($updateEmail == FALSE){
            $this->response(array('Error' => 'El email que inteta ingresar ya existe o tiene un formato incorrecto'));
            die();
        }else{
            $this->response($updateEmail);
        }
        
      }



// Upload audios ***** work in progress!!
//     public function uploadAudio_post(){
//     // Valido los datos del usuario
//     $this->load->model('user_model');
//     $email = $this->post('email');
//     $password = $this->post('password');
//     $validate = $this->user_model->login_in($email,$password);
//     $this->load->model('apps_model');
//     $uri_app = $this->post('uri_app');
//     $uri = $this->apps_model->get_uri_app($uri_app);
//     $user_id = $validate->id;
//     if( ! $this->post('submit')) {
//         $this->response(NULL, 400);
//     }
//     $this->load->library('upload');

//     if ( ! $this->upload->do_upload() ) {
//         $this->response(array('error' => strip_tags($this->upload->display_errors())), 404);
//     } else {
//         $upload = $this->upload->data();
//         $this->response($upload, 200);
//     }
// }
// 

    public function payment_manager_post()
    {
        // Valido los datos del usuario
        $this->load->model('user_model');
        $email = $this->post('email');
        $password = $this->post('password');
        $validate = $this->user_model->login_in($email,$password);
        // $user_id = $this->session->userdata($data);

        $this->load->model('apps_model'); //
        $uri_app = $this->post('uri_app');
        $user_id = $validate->id; 
        $ArrayValidador = array('User Id' => $user_id, 'url' => 'http://kka.to/login/login/'.$user_id.'?rtrn='.$uri_app);


        if ($validate == FALSE) {
            $this->response(array('Estado' => 'Email o Password incorrectos. Verifique sus datos de usuario.'));
        }
        else{
            $this->response($ArrayValidador);    
        }    
    }


// 
    public function uploadAudio_post()
    {
        $path = $this->post('path');


        
            $config['upload_path']      = './public/audios/';
            $config['allowed_types']    = 'mp3|wav|wna';
            $config['max_size']         = '2000';
            $config['encrypt_name']     =  TRUE;
            $this->load->library('upload', $config);
    }
































/*********************************************************************************************************************
                                             DE USO INTERNO
*********************************************************************************************************************/
   public function getIdContactCampaign_post()
    {
        $this->load->model('contacts_model');
        $id_campaign= $this->post('id_campaign');
        $validate = $this->user_model->login_in($email,$password);
        $getid = $this->contacts_model->get_id_contact_campaign($id_campaign, $user_id);
        $this->response($getid);
    }

    public function getpublisherearnings_post()
    {
        // Valido los datos del usuario
        $this->load->model('user_model');
        $email = $this->post('email');
        $password = $this->post('password');
        $validate = $this->user_model->login_in($email,$password);
        $user_id = $validate->id;
        // Ganancias del publisher según id de la app
        $this->load->model('apps_model');
        $id_wapp = $this->post('id');
        $getprice = $this->apps_model->get_wapp_price_api($id_wapp);
        $resultfunction = array('validate' => $validate,'getprice' => $getprice,'user_id' => $user_id);

        $this->response($resultfunction);
    }
}