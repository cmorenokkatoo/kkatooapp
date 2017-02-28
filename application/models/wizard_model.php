<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wizard_Model extends CI_Model {

	/*
	Funcion para obtener la lista de categorias existentes
	*/
	public function get_category()
	{
		try
		{
			$result	=	$this->db->get('category')->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetCategory $e){
			log_message('debug','Error tratando de obtener informaci�n del usuario');
			return FALSE;
		}
	}

	public function get_uri($uri = "", $id_wapp='')
	{
		try
		{
			if(is_numeric($id_wapp)){
				$this->db->where('id != ', $id_wapp);
			}
			$this->db->where('uri', $uri);
			$result	=	$this->db->get('wapp')->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetUri $e){
			log_message('debug','Error tratando de obtener informaci�n del usuario');
			return FALSE;
		}
	}

	/**
	* Optiene aplicaci�n por nombre
	*/

	public function get_title($title = "", $id_wapp=0)
	{
		try
		{
			$this->db->where('title', $title);
			$this->db->where('id != ', $id_wapp);
			$result	=	$this->db->get('wapp')->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetUri $e){
			log_message('debug','Error tratando de obtener informaci�n del usuario');
			return FALSE;
		}
	}

	public function insert_wapp($data = array())
	{
		try
		{
			$result = $this->db->insert('wapp', $data);
			if($result)
			{
				return $this->db->insert_id();
			}
			return FALSE;

		}catch(ErrorInsertWapp $e){
			log_message('debug','Error tratando de insertar informaci�n del usuario');
			return FALSE;
		}
	}



	public function insert_audio_wapp($data = array())
	{
		try
		{
			$result	= 	$this->db->insert('audio_app', $data);
			return $result;
		}catch(ErrorInsertAudioWapp $e){
			log_message('debug','Error tratando de insertar audio a una aplicaci�n');
			return FALSE;
		}
	}

	public function insert_batch_fields($data = array())
	{
		try
		{
			$result	= 	$this->db->insert_batch('fields', $data);
			return $result;
		}catch(ErrorInsertBatchFields $e){
			log_message('debug','Error tratando de insertar audio a una aplicaci�n');
			return FALSE;
		}
	}

	// POCHO C�DIGOS
	/*
	* Inicia una aplicaci�n que se va a crear
	* retorna el id para cargarla.
	*/
	public function initialize_app($id_user, $tipo){
		try{
			$data = array(
				'user_id' => $id_user,
				'title' => date("F j, Y, g:i a"),
				'image' => '',
				'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce diam magna, ultricies in fringilla in, pretium aliquam nisi. Maecenas in tellus nisl, vitae malesuada leo. Donec eu sem eu magna pretium consectetur eget in tortor.',
				'private' => 0,
				'aproved' => 0,
				'tipo'	  => $tipo
			);

			//APLICACI�N GLOBAL
			if($tipo===0){
				$data['upload_audio'] 			= 1;
				$data['text_speech'] 				= 1;
				$data['record_audio'] 			= 1;
				$data['use_audio'] 					= 1;
				$data['aditional_options'] 	= 1;
			}

			//SUSCRIPCI�N
			if($tipo===1){
				$data['upload_audio'] 			= 1;
				$data['text_speech'] 				= 1;
				$data['record_audio'] 			= 1;
				$data['use_audio'] 					= 1;
				$data['aditional_options'] 	= 1;
			}

			//DIFUSI�N
			if($tipo===2){
				$data['upload_audio'] 			= 0;
				$data['text_speech'] 				= 0;
				$data['record_audio'] 			= 0;
				$data['use_audio'] 					= 1;
				$data['aditional_options'] 	= 1;
			}

			if($tipo===0) $data['tipo'] = 2;

			$result	= 	$this->db->insert('wapp', $data);
			if($this->db->affected_rows()>0){
				$wapp_ID = $this->db->insert_id();

				$permissions = array();

				$permissions[] = array('id_permission'=>1, 'id_wapp'=>$wapp_ID, 'state'=>0); //Restringir facebook
				$permissions[] = array('id_permission'=>2, 'id_wapp'=>$wapp_ID, 'state'=>0); //Restringir gmail
				$permissions[] = array('id_permission'=>3, 'id_wapp'=>$wapp_ID, 'state'=>0); //Pedir pin o id en kreditos
				$permissions[] = array('id_permission'=>4, 'id_wapp'=>$wapp_ID, 'state'=>0); //Denegar el market place
				$permissions[] = array('id_permission'=>5, 'id_wapp'=>$wapp_ID, 'state'=>0); //Denegar el wizard
				$permissions[] = array('id_permission'=>6, 'id_wapp'=>$wapp_ID, 'state'=>0); //Denegar el blog
				$permissions[] = array('id_permission'=>7, 'id_wapp'=>$wapp_ID, 'state'=>0); //Denegar el footer
				$permissions[] = array('id_permission'=>8, 'id_wapp'=>$wapp_ID, 'state'=>0); //Requerir audio
				$permissions[] = array('id_permission'=>9, 'id_wapp'=>$wapp_ID, 'state'=>0); //Aplicaci�n de pin

				$this->db->insert_batch('permission_wapp', $permissions);

				return $wapp_ID;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error tratando de iniciar una aplicaci�n => initialize_app');
			return FALSE;
		}
	}

	/**
	* Optiene los audios asociados a una aplicaci�n en la librer�a de contenido
	* @param $id_app el id de la aplicaci�n
	* @param $id_user es el id del usuario
	*/
	function get_library_audios_by_app($id_app='', $id_user=FALSE){

		try{
			$this->db->select('audio.*, audio.id as id_audio, audio.tipo as tipo_audio, content_wapp.*, content_wapp.id_content as id_content');
			$this->db->from('content_wapp');
			$this->db->join('audio','audio.id = content_wapp.id_content');
			$this->db->where('content_wapp.tipo', "audio");
			$this->db->where('audio.tipo', "audio");
			$this->db->where('audio.state', 1);
			if($id_user) $this->db->where('audio.user_id', $id_user);
			$this->db->where('content_wapp.id_wapp', $id_app);
			$result = $this->db->get()->result();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error de traer la libreria audio de una aplicaci�n => get_library_audios_by_app');
			return FALSE;
		}

	}

	/**
	* Optiene las grabaciones asociadas a una aplicaci�n en la librer�a de contenido
	* @param $id_app el id de la aplicaci�n
	*/
	function get_library_records_by_app($id_app='', $id_user = FALSE){
		try{
			$this->db->select('audio.*, audio.id as id_audio, audio.tipo as tipo_audio, content_wapp.*, content_wapp.id_content as id_content');
			$this->db->from('content_wapp');
			$this->db->join('audio','audio.id = content_wapp.id_content');
			$this->db->where('content_wapp.tipo', "audio");
			$this->db->where('audio.tipo', "record");
			$this->db->where('audio.state', 1);
			if($id_user) $this->db->where('audio.user_id', $id_user);
			$this->db->where('content_wapp.id_wapp', $id_app);
			$result = $this->db->get()->result();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error de traer la libreria audio de una aplicaci�n => get_library_records_by_app');
			return FALSE;
		}
	}

	/**
	* Optiene los texto speechs asociados a una aplicaci�n en la librer�a de contenido
	* @param $id_app el id de la aplicaci�n
	*/
	function get_library_texts_by_app($id_app='', $id_user = FALSE){
		try{
			$this->db->select('text_speech.*, text_speech.id as id_text, content_wapp.*, content_wapp.id_content as id_content, voice.name as voice_name, voice.idioma, voice.id as voice_id');
			$this->db->from('content_wapp');
			$this->db->join('text_speech','text_speech.id = content_wapp.id_content');
			$this->db->join('voice','text_speech.voice = voice.id');
			$this->db->where('text_speech.state', 1);
			if($id_user) $this->db->where('text_speech.user_id', $id_user);
			$this->db->where('content_wapp.tipo', "text");
			$this->db->where('content_wapp.id_wapp', $id_app);
			$result = $this->db->get()->result();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error de traer la libreria audio de una aplicaci�n => get_library_records_by_app');
			return FALSE;
		}
	}

	/**
	* Optener el contenido de la aplicaci�n
	*/

	function get_library_content($id_app='', $id_user = FALSE){
		try{
			$this->db->select('text_speech.id as text_id, text_speech.`name` as text_name, text_speech.text as text_text, text_speech.user_id as text_user, text_speech.voice as text_voice_id, text_speech.*,  voice.id as voice_id,
			voice.`name` as voice_name, voice.*,
			content_wapp.id as content_wap_id, content_wapp.fecha, content_wapp.tipo as content_tipo, content_wapp.id_content as content_id_content,
			audio.*, audio.`name` as audio_name, audio.id as audio_id, audio.tipo as audio_tipo');
			$this->db->from('content_wapp');
			// Flag = 1 es el contenido que es original de la app y est� disponible para todos los usuarios.
			if($id_user){
				$this->db->join('text_speech','text_speech.id = content_wapp.id_content and content_wapp.tipo = "text" and (text_speech.user_id ='.$id_user.' or text_speech.flag = 1)'  , 'LEFT');
			}else{
				$this->db->join('text_speech','text_speech.id = content_wapp.id_content and content_wapp.tipo = "text"', 'LEFT');
			}
			$this->db->join('voice','text_speech.voice = voice.id', 'LEFT');

			if($id_user){
				$this->db->join('audio','audio.id = content_wapp.id_content and content_wapp.tipo = "audio" and (audio.user_id ='.$id_user.' or audio.flag = 1)', 'LEFT');

			}else{
				$this->db->join('audio','audio.id = content_wapp.id_content and content_wapp.tipo = "audio"', 'LEFT');
			}
			$this->db->where('content_wapp.id_wapp', $id_app);
			$where = "(text_speech.state = 1 OR audio.state = 1)";
			$this->db->where($where);

			$this->db->order_by("content_wapp.fecha", "desc");
			$result = $this->db->get()->result();

			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error de traer la libreria audio de una aplicaci�n => get_library_records_by_app');
			return FALSE;
		}
	}

	/**
	* Optiene los campos d�namicos de una aplicaci�n
	* @param $id_app es el id de la aplicaci�n
	*/
	function get_dynamic_fields($id_app){
		try{
			$this->db->select('fields.*');
			$this->db->from('fields');
			$this->db->where('fields.id_wapp', $id_app);
			$result = $this->db->get()->result();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error de traer la libreria audio de una aplicaci�n => get_library_records_by_app');
			return FALSE;
		}
	}

	/**
	* Actualiza las imagenes subidas por id de aplicaci�n
	* @param $id_app el id de la aplicaci�n
	* @param $url_img es la ruta de la imagen
	* @param $type el t�po de imagen que se esta subiendo.
	*/
	function return_imagen($id_app='',$type=''){
		try
		{
			switch($type){
				case "img_para_market":
					$this->db->select('image');
				break;
				case "img_fondo":
					$this->db->select('fondo_html');
				break;
				case "img_logotipo":
					$this->db->select('logo');
				break;
				case "img_secundaria":
					$this->db->select('secondary_img_html');
				break;
			}

			$this->db->from('wapp');
			$this->db->where('id', $id_app);
			$result = $this->db->get()->row();
			//echo $this->db->last_query();
			if($result){
				switch($type){
					case "img_para_market":
						return $result->image;
					break;
					case "img_fondo":
						return $result->fondo_html;
					break;
					case "img_logotipo":
						return $result->logo;
					break;
					case "img_secundaria":
						return $result->secondary_img_html;
					break;
				}
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al traer la imagen de una aplicaci�n');
			return FALSE;
		}
	}

	/**
	* Actualiza la informaci�n de una aplicaci�n ya inicializada
	* @param $id_app id de la aplicaci�n
	* @param $data datos a actualizar
	*/
	function update_app_data($id_app='', $data=''){
		try
		{
			$this->db->where('id', $id_app);
			//$this->db->where('css_route', '');
			$result	=	$this->db->update('wapp',$data);
			return $result;
		}catch(Exception $e){
			log_message('debug','Error al actualizar la info de una aplicaci�n');
			return FALSE;
		}
	}

	/**
	* Actualiza los permisos de aplicaciones privada o publicas
	* @param $private verifica si es privada 1 o publica 0.
	* @param $id_wapp es el id de la aplicaci�n.
	*/
	function update_permission_app_by_private($private='', $id_wapp=0){
		try
		{
			if($private == 1 || $private == 0){

				//Permiso 4 Abrir el market place
				if($this->check_if_permission_exist($id_wapp, 4)!==FALSE){
					$this->update_permision($id_wapp, 4, $private);
				}else{
					$this->insert_permission($id_wapp, 4, $private);
				}

				//Permiso 5 Ocultar el wizard
				if($this->check_if_permission_exist($id_wapp, 5)!==FALSE){
					$this->update_permision($id_wapp, 5, $private);
				}else{
					$this->insert_permission($id_wapp, 5, $private);
				}

				//Permiso 6 Ocultar el blog
				if($this->check_if_permission_exist($id_wapp, 6)!==FALSE){
					$this->update_permision($id_wapp, 6, $private);
				}else{
					$this->insert_permission($id_wapp, 6, $private);
				}

				//Permiso 7 Ocultar el footer
				if($this->check_if_permission_exist($id_wapp, 7)!==FALSE){
					$this->update_permision($id_wapp, 7, $private);
				}else{
					$this->insert_permission($id_wapp, 7, $private);
				}
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al actualizar la info de permisos de una aplicaci�n');
			return FALSE;
		}
	}

	/**
	* Inserta un permiso para una aplicaci�n
	* @param $id_wapp id de la palicaci�n
	* @param $id_permission id del permiso
	* @param $state valor del permiso
	*/
	function insert_permission($id_wapp, $id_permission, $state){
		try
		{
			$this->db->insert('permission_wapp', array('id_wapp'=>$id_wapp, 'id_permission'=>$id_permission, 'state'=>$state));
			return $this->db->affected_rows() > 0;
		}catch(Exception $e){
			log_message('debug','Error al insertar la info de un permiso de una aplicaci�n');
			return FALSE;
		}
	}

	/**
	* Verifica si un permiso expecifico existe y retorna el permiso si existe
	* @param $id_wapp id de la palicaci�n
	* @param $id_permission id del permiso
	*/
	function check_if_permission_exist($id_wapp='', $id_permission=''){
		try
		{
			$this->db->select('*');
			$this->db->from('permission_wapp');
			$this->db->where('id_wapp', $id_wapp);
			$this->db->where('id_permission', $id_permission);
			$result = $this->db->get();
			if($result->num_rows() > 0){
				return $result->row()->state;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al actualizar la info de un permiso de una aplicaci�n');
			return FALSE;
		}
	}

	/**
	* Actualiza un permiso de una apliaci�n
	* @param $id_wapp id de la palicaci�n
	* @param $id_permission id del permiso
	* @param $state valor de actualizaci�n del permiso
	*/
	function update_permision($id_wapp='', $id_permission='', $state=''){
		try
		{
			$this->db->where('id_wapp', $id_wapp);
			$this->db->where('id_permission', $id_permission);
			$result	=	$this->db->update('permission_wapp',array('state'=>$state));
			return $result;
		}catch(Exception $e){
			log_message('debug','Error al actualizar la info de un permiso de una aplicaci�n');
			return FALSE;
		}
	}

	/**
	* Actualiza las imagenes subidas por id de aplicaci�n
	* @param $id_app el id de la aplicaci�n
	* @param $url_img es la ruta de la imagen
	* @param $type el t�po de imagen que se esta subiendo.
	*/
	function update_app_img($id_app='', $url_img='', $type=''){
		try
		{
			switch($type){
				case "img_para_market":
					$data = array('image'=>$url_img);
				break;
				case "img_fondo":
					$data = array('fondo_html'=>$url_img);
				break;
				case "img_logotipo":
					$data = array('logo'=>$url_img);
				break;
				case "img_secundaria":
					$data = array('secondary_img_html'=>$url_img);
				break;
			}
			$this->db->where('id', $id_app);
			$result = $this->db->update('wapp',$data);
			return $result;
		}catch(Exception $e){
			log_message('debug','Error al tratar de agregar una imagen a la aplicaci�n');
			return FALSE;
		}
	}

	/**
	* Optiene la �ltima aplicaci�n no aprovada del usuario actualmente conectado
	*/
	function get_last_not_aproved_app($id_user){
		try{
			$this->db->select('*');
			$this->db->from('wapp');
			$this->db->where('user_id', $id_user);
			$this->db->where('aproved', 0);
			$this->db->order_by("id", "desc");
			$result = $this->db->get()->row();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al tratar de traer la �ltima aplicaci�n no aprovada por usuario');
			return FALSE;
		}
	}

	/**
	* Optiene la �ltima aplicaci�n no aprovada del usuario actualmente conectado
	*/
	function get_last_aproved_or_not_aproved_app($id_user){
		try{
			$this->db->select('*');
			$this->db->from('wapp');
			$this->db->where('user_id', $id_user);
			$this->db->order_by("id", "desc");
			$result = $this->db->get()->row();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al tratar de traer la �ltima aplicaci�n no aprovada por usuario');
			return FALSE;
		}
	}

	/**
	* Check if field already exits
	*/
	function check_if_field_exits($id_field='', $id_app=''){
		try{
			$this->db->select('*');
			$this->db->from('fields');
			$this->db->where('id', $id_field);
			$this->db->where('id_wapp', $id_app);
			$result = $this->db->get()->row();
			//echo $this->db->last_query();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al tratar de traer los datos de un campo din�mico');
			return FALSE;
		}
	}

	/**
	* Actualiza en batch
	*/
	public function update_batch_fields($data = array())
	{
		try
		{
			foreach($data as $d)
			{
				$data1 = array(
								'name_fields' 		=> $d['name_fields'],
								'id_wapp' 			=> $d['id_wapp'],
								'name' 				=> $d['name'],
								'default' 			=> $d['default']
							);
				$this->db->where('id', $d['id']);
				$result	= 	$this->db->update('fields', $data1);
				//echo $this->db->last_query();
			}
			return $result;
		}catch(ErrorInsertBatchFields $e){
			log_message('debug','Error tratando actualizar campos din�micos');
			return FALSE;
		}
	}

	/**
	* Elimina el campo din�mico de una aplicaci�n y todos los datos asociados a este
	*/
	function delete_dynamic_field($id_dynamic='', $id_wapp =''){
	try
		{
			$this->db->where('id_wapp', $id_wapp);
			$this->db->where('id_fields', $id_dynamic);
			if($this->db->delete('contact_fields')){
				$this->db->where('id_wapp', $id_wapp);
				$this->db->where('id', $id_dynamic);
				if($this->db->delete('fields')){
					return TRUE;
				}else{
					return FALSE;
				}
			}else{
				return FALSE;
			}
		}catch(ErrorInsertBatchFields $e){
			log_message('debug','Error tratando de eliminar campos din�micos');
			return FALSE;
		}
	}

	/**
	* Funcion para guardar un texto speech en la base de datos
	* @param $data array de datos para almacenar
	*/
	function save_text_speech($data=array(), $id_wapp=''){
		try
		{
			$result = $this->db->insert('text_speech', $data);
			$id 	= $this->db->insert_id();
			if($id)
			{
				$result = $this->db->insert('content_wapp', array(
							'id_content' 	=> $id,
							'id_wapp'		=> $id_wapp,
							'tipo'			=> 'text'
						));
				if($this->db->insert_id()){
					$data = array('text_speech.flag'=>'0');
						$this->db->where('text_speech.id_app', $id_wapp);
						$this->db->update('text_speech join wapp on wapp.user_id = text_speech.user_id ', $data);
					return $id;
				}
			}
			return FALSE;
		}catch(ErrorInsertWapp $e){
			log_message('debug','Error tratando de insertar informaci�n del texto speach');
			return FALSE;
		}
	}

	/**
	* Funcion para actualizar un texto speech en la base de datos
	* @param $data array de datos para almacenar
	* @param $id_content es el id del texto speech que se va a actualizar
	*/
	function update_text_speach($data=array(), $id_content='', $user_id=''){
		try
		{
			$this->db->where('id', $id_content);
			$this->db->where('user_id', $user_id);
			$result	= $this->db->update('text_speech',$data);
			if($result){
				return TRUE;
			}else{
				return FALSE;
			}

		}catch(ErrorInsertWapp $e){
			log_message('debug','Error tratando de actualizar informaci�n del texto speach');
			return FALSE;
		}
	}

	/**
	* Funcion para eliminar un texto speech
	* @param $id_content es el id del textospeech
	* @param $user_id es el id del usuario actualmente logueado
	*/
	function delete_text_speach($id_content='', $user_id=''){
		try
		{

			$data = array(
			               'state' => 0
			              );
			$this->db->where('user_id', $user_id);
			$this->db->where('id',$id_content);
			$result	=	$this->db->update('text_speech',$data);
			return $result;

			/*$this->db->where('id_content', $id_content);
			$this->db->where('tipo', 'text');
			if($this->db->delete('content_wapp')){
				$this->db->where('user_id', $user_id);
				$this->db->where('id', $id_content);
				if($this->db->delete('text_speech')){
					return TRUE;
				}else{
					return FALSE;
				}
			}else{
				return FALSE;
			}*/
		}catch(ErrorInsertBatchFields $e){
			log_message('debug','Error tratando de eliminar campos din�micos');
			return FALSE;
		}
	}

	/**
	* Optiene los texto speechs asociados a una aplicaci�n en la librer�a de contenido
	* @param $text_id el id del texto speech
	*/
	function get_library_texts_by_id($text_id=''){
		try{
			$this->db->select('text_speech.*, voice.name as voice_name, voice.idioma, voice.id as voice_id');
			$this->db->from('text_speech');
			$this->db->join('voice','text_speech.voice = voice.id', 'LEFT');
			$this->db->where('text_speech.id', $text_id);
			$result = $this->db->get()->row();
			//echo $this->db->last_query();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error de traer la libreria audio de una aplicaci�n => get_library_records_by_app');
			return FALSE;
		}
	}

	/**
	* Funcion para eliminar un audio
	* @param $id_content es el id del audio
	* @param $user_id es el id del usuario actualmente logueado
	*/
	function delete_audio($id_content='', $user_id=''){
		try
		{
			$data = array(
			               'state' => 0
			              );
			$this->db->where('user_id', $user_id);
			$this->db->where('id',$id_content);
			$result	=	$this->db->update('audio',$data);
			return $result;

			/*$this->db->where('id_content', $id_content);
			$this->db->where('tipo', 'audio');
			if($this->db->delete('content_wapp')){
				$this->db->where('user_id', $user_id);
				$this->db->where('id', $id_content);
				if($this->db->delete('audio')){
					return TRUE;
				}else{
					return FALSE;
				}
			}else{
				return FALSE;
			}*/
		}catch(ErrorInsertBatchFields $e){
			log_message('debug','Error tratando de eliminar campos din�micos');
			return FALSE;
		}
	}

	/**
	* Update audio name
	*/

	function update_audio_name($data = array(), $id_content = '', $user_id = ''){
		try
		{
			$this->db->where('id', $id_content);
			$this->db->where('user_id', $user_id);
			$result	= $this->db->update('audio',$data);
			if(!empty($result)){
				return TRUE;
			}else{
				return FALSE;
			}

		}catch(ErrorInsertWapp $e){
			log_message('debug','Error tratando de actualizar el nombre del audio');
			return FALSE;
		}
	}

	/**
	* Guarda la infromaci�n de un audio que se sube.
	*/
	function save_audio($data=array(), $id_wapp='', $tipo = ''){
		try
		{
			$result = $this->db->insert('audio', $data);
			$id 	= $this->db->insert_id();
			if(!empty($id))
			{
				$result = $this->db->insert('content_wapp', array(
							'id_content' 	=> $id,
							'id_wapp'		=> $id_wapp,
							'tipo'			=> $tipo
						));
				$id2 = $this->db->insert_id();
				if(!empty($id2)){
						$data = array('audio.flag'=>'0');
						$this->db->where('audio.id_app', $id_wapp);
						$this->db->update('audio join wapp on wapp.user_id = audio.user_id ', $data);
					return $id;
				}
			}
			return FALSE;
		}catch(ErrorInsertWapp $e){
			log_message('debug','Error tratando de insertar informaci�n del audio');
			return FALSE;
		}
	}

	/**
	* Optiene los audios asociados a una aplicaci�n en la librer�a de contenido
	* @param $text_id es el id del audio
	*/
	function get_library_audio_by_id($audio_id=''){
		try{
			$this->db->select('audio.*, audio.id as id_audio, audio.tipo as tipo_audio, content_wapp.*, content_wapp.id_content as id_content');
			$this->db->from('content_wapp');
			$this->db->join('audio','audio.id = content_wapp.id_content');
			$this->db->where('audio.id', $audio_id);
			$result = $this->db->get()->row();
			//echo $this->db->last_query();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error de traer la libreria audio de una aplicaci�n => get_library_records_by_app');
			return FALSE;
		}
	}

	/**
	* Delete text speech library batch
	*/
	public function batch_delete_text_speach($user_id = 0, $ids = array())
	{
		try
		{
		  	$data = array(
			               'state' => 0
			              );
			$this->db->where('user_id', $user_id);
			$this->db->where_in('id',$ids);
			$result	= $this->db->update('text_speech',$data);
			return $result;
		}catch(Exception $e){
			log_message('debug','Error al tratar de eliminar un texto_speech en batch');
			return FALSE;
		}
	}

	/**
	* Delete audio batch
	*/
	public function batch_delete_audio($user_id = 0, $ids = array())
	{
		try
		{
		  	$data = array(
			               'state' => 0
			              );
			$this->db->where('user_id', $user_id);
			$this->db->where_in('id',$ids);
			$result	= $this->db->update('audio',$data);
			return $result;
		}catch(Exception $e){
			log_message('debug','Error al tratar de eliminar un audio en batch');
			return FALSE;
		}
	}

	/**
	* Funci�n para actualizar los datos de grabar intro o cierre
	*/
	function intro_cierre_update($resp=0, $id_wapp=0, $action='', $user_id=''){
		try
		{
			if($action=='intro'){
				$data = array('intro' => $resp, 'cierre'=>($resp==1)?0:1);
			}

			if($action=='cierre'){
				$data = array('cierre' => $resp, 'intro'=>($resp==1)?0:1);
			}

			if($action=='none'){
				$data = array('cierre' => 0, 'intro'=>0);
			}

			$this->db->where('user_id', $user_id);
			$this->db->where('id',$id_wapp);

			$result	= $this->db->update('wapp',$data);

			if($this->db->affected_rows() > 0){
				return TRUE;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al tratar de actualizar el intro o cierre de una aplicaci�n');
			return FALSE;
		}
	}

	/**
	* Agrega el paquete a una aplicaci�n
	* @param $data son los datos a agregar
	* @param $id_wapp es el id de la aplicaci�n
	*/
	function add_package_application($data=array()){
		try
		{
			$result = $this->db->insert('package_suscription', $data);
			if($result)
			{
				return $this->db->insert_id();
			}
			return FALSE;

		}catch(Exception $e){
			log_message('debug','Error tratando de agregar un paquete');
			return FALSE;
		}
	}

	/**
	* Obtiene un paquete por aplicaci�n y valor
	* @param $id_wapp son los datos a agregar
	* @param $package_value es el id de la aplicaci�n
	*/
	function get_package_by_app_and_value($id_wapp='', $amount = ''){
		try
		{
			$this->db->where('id_app', $id_wapp);
			$this->db->where('amount', $amount);

			$result = $this->db->get('package_suscription')->row();
			if($result)
			{
				return TRUE;
			}else{
				return FALSE;
			}

		}catch(Exception $e){
			log_message('debug','Error tratando de obtener un paquete');
			return FALSE;
		}
	}

	/**
	* Funci�n para remover un paquete en una aplicaci��n
	*/

	function remove_packages($id_wapp='', $id_package=''){

		try
		{
			$this->db->where('id', $id_package);
			$this->db->where('id_app', $id_wapp);

			if($this->db->delete('package_suscription')){
				return TRUE;
			}else{
				return FALSE;
			}

		}catch(Exception $e){
			log_message('debug','Error tratando de remover un paquete');
			return FALSE;
		}
	}

	/**
	* Funci�n para obtener los paquetes de una aplicaci�n
	*/
	function get_packages_app($id_wapp = ''){
		try{
			$this->db->select('*');
			$this->db->from('package_suscription');
			$this->db->where('id_app', $id_wapp);
			$this->db->order_by('amount', 'ASC');

			$result = $this->db->get()->result();

			if($result){
				return $result;
			}else{
				return FALSE;
			}

		}catch(Exception $e){
			log_message('debug','Error al traer los paquetes de una aplicaci�n => get_packages_app');
			return FALSE;
		}
	}

	/**
	* Obtener aplicaciones que no tienen uris
	*/

	function get_applications_without_uris(){
		$this->db->select('id, title');
		$this->db->from('wapp');
		$this->db->where("uri = ''");
		$result = $this->db->get()->result();
		return $result;
	}

	/**
	* Actualizamos la uri de las aplicaciones por id
	*/

	function update_uri($id_app='', $uri=''){
		$this->db->where('id', $id_app);
		$result	=	$this->db->update('wapp',array('uri'=>$uri));
		return $result;
	}

	/**
	* Retorna el usuario por aplicaci�n
	*/
	function get_user_by_application($id_wapp=''){
		$this->db->select('user.*');
		$this->db->from('user');
		$this->db->join('wapp', 'wapp.user_id = user.id');
		$this->db->where('wapp.id', $id_wapp);
		return $this->db->get()->row();
	}

	/**
	*Enviar email para aprobar
	*/
	function send_email_aprove($name='', $uri=''){
		$this->load->model('user_model');
		$user = $this->user_model->get_user_by_id(KKATOO_USER);
		$data = array('name'=>$name, 'uri'=>$uri);

		$mensaje = $this->load->view('email/spanish/application_to_check',$data,TRUE);

		$this->load->library('email');
		$this->email->from(KKATOO_EMAIL_INFO, 'Kkatoo Info');
		$this->email->to($user->email);
		$this->email->subject(sprintf('La aplicaci�n %s se actualiz�.', ucfirst($name)));
		$this->email->message($mensaje);
		$this->email->send();
	}
}
