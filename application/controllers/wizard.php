<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wizard extends CI_Controller {
	var $_nombre_aplicacion = "nombre_app";
	var $_imagen_fondo = "fondo";
	var $_logotipo = "logo";
	var $_slogan = "slogan";
	var $_descripcion = "descripcion";
	var $_video = "video";
	var $_imagen_market = "market";
	var $_imagen_secundaria = "secundaria";
	var $_categoria = "categorias";
	var $_privacidad = "privacidad";
	var $_dominio_propio = "dominio";
	var $_dynamic_name = "dynamic_name";
	var $_dynamic_type  = "dynamic_type";
	var $_dynamic_default = "dynamic_default";
	var $_dynamic_id	= "dynamic_id";
	var $_save_intr = "";
	var $_save_close = "";
	var $_price_percent = "percent";
	var $_id_app = "id_wapp";

	/**
	 * Index Page for this controller.
	 * Carga el Login de Kkatoo por defecto
	 */
	public function index()
	{
		$this->deny_wizard();

		//POCHO CÓDIGO
		ini_set('display_errors', 'on');
		$this->lang->load('apps');
		$this->load->model('wizard_model');
		$this->load->model('apps_model');
		if($this->_login_in()){
			$id_app =	$this->uri->segment(2);
			if($id_app !== FALSE){
				$result 	=	$this->apps_model->get_app_data_by_id($id_app);
				if($result){
					if($this->check_if_can_use($result)){
						$this->_load_app_data($result);
					}else{
						$this->session->set_flashdata('error',$this->lang->line('notpermitedappsubs'));
						redirect('marketplace');
					}
				}else{
					$this->session->set_flashdata('error',$this->lang->line('notpermitedappsubs'));
					redirect('marketplace');
				}
			}else{
				$wapp = $this->wizard_model->get_last_not_aproved_app($this->session->userdata('user_id'));
				if($wapp){
					redirect('wizard/'.$wapp->id);
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
	* Inicializa una aplicación nueva
	*/
	function newapp($tipo=false){
		$this->lang->load('apps');
		$this->load->model('wizard_model');

		if($tipo == "subs"){
			$tipo = 1;
		}else if($tipo == "dif"){
			$tipo = 2;
		}else if($tipo == "global" && in_array($this->session->userdata('user_id'), explode(',', IDS_USER_GLOBAL_APP))){
			$tipo = 0;
		}else{
			//The Special app redirect
			$this->_return_to_special_url();

			$this->session->set_flashdata('error',$this->lang->line('notpermitedappsubs'));
			redirect('marketplace');

			die();
		}
		$id_wapp = $this->wizard_model->initialize_app($this->session->userdata('user_id'), $tipo);
		redirect('wizard/'.$id_wapp);
	}

	/**
	* Crear uris aplicaciónes
	*/
	function crear_aplicacion_uris(){
		$this->load->model('wizard_model');
		$apps = $this->wizard_model->get_applications_without_uris();
		foreach($apps as $app){
			$uri 			=	 	$this->_quitarAcentos($app->title);
			$uri 			=   sanitize_title_with_dashes($uri);
			$result 	=  	$this->wizard_model->get_uri($uri, $app->id);
			if(empty($result)){
				$this->wizard_model->update_uri($app->id, $uri);
			}
		}
	}

	/**
	* Cargar los datos de la aplicación para comenzar con la edición
	* @param $result son los datos básicos de la aplicación en la tabla wapp
	*/
	function _load_app_data($result){
		$this->load->model('apps_model');

		$category 			= $this->wizard_model->get_category();
		$data["audios"] 	= $this->wizard_model->get_library_audios_by_app($result->id, $this->session->userdata('user_id'));
		$data["records"] 	= $this->wizard_model->get_library_records_by_app($result->id, $this->session->userdata('user_id'));
		$data["texts"] 		= $this->wizard_model->get_library_texts_by_app($result->id, $this->session->userdata('user_id'));
		$data["library"]	= $this->wizard_model->get_library_content($result->id, $this->session->userdata('user_id'));
		$data["packages"]	= $this->wizard_model->get_packages_app($result->id);

		$data["dynamic"] 	= $this->wizard_model->get_dynamic_fields($result->id);


		$data["app_data"] 	= $result;
		$data["category"] 	= $category;

		$data['voice'] 	  	= $this->apps_model->get_voice();

		$this->_view_wizard($data);
	}

	/**
	* funcion para guardar la información del formulario de creación de aplicación
	*/

	function save_info_app(){
		$this->load->library('form_validation');
		$this->load->model('wizard_model');
		$this->lang->load('wizard');

		$this->form_validation->set_rules($this->_nombre_aplicacion, $this->lang->line('_nombre_aplicacion'), 'required|xss_clean|max_length[99]|min_length[6]|trim');
		$this->form_validation->set_rules($this->_slogan, $this->lang->line('_slogan'), 'xss_clean|max_length[200]|min_length[6]|trim');
		$this->form_validation->set_rules($this->_descripcion, $this->lang->line('_descripcion'), 'xss_clean|max_length[500]|min_length[1]');
		$this->form_validation->set_rules($this->_video, $this->lang->line('_video'), 'xss_clean|max_length[200]|min_length[5]');
		$this->form_validation->set_rules($this->_categoria, $this->lang->line('_categoria'), 'required|xss_clean');
		$this->form_validation->set_rules($this->_privacidad, $this->lang->line('_privacidad'), 'required|xss_clean');
		$this->form_validation->set_rules($this->_dominio_propio, $this->lang->line('_dominio_propio'), 'xss_clean');
		$this->form_validation->set_rules($this->_price_percent, $this->lang->line('_price_percent'), 'required|xss_clean|numeric');

		$this->form_validation->set_rules($this->_id_app, $this->lang->line('_id_app'), 'required|xss_clean|numeric');

		if ($this->form_validation->run() == FALSE){
			/*$this->session->set_flashdata('error',validation_errors());
			redirect('wizard');*/

			$category 	= 	$this->wizard_model->get_category();
			$data		=	array(
									'category' 	=> $category,
									'error'		=> validation_errors()
									);
			$this->session->set_flashdata('error',validation_errors());
			redirect('wizard/'.$this->input->post($this->_id_app));
		}
		else{
			$id_wapp = $this->input->post($this->_id_app);
			if($this->check_if_owner($id_wapp)){

				/**
				* Verifica si dejó la fecha como un nombre.
				*/
				$date = DateTime::createFromFormat('F j, Y, g:i a', $this->input->post($this->_nombre_aplicacion));
				if ($date == true):
				$this->session->set_flashdata('error',$this->lang->line('wrong_name'));
					redirect('wizard/'.$id_wapp);
					die();
				endif;

				$uri 			= 	$this->_quitarAcentos($this->input->post($this->_nombre_aplicacion));
				$uri 			=   sanitize_title_with_dashes($uri);
				$result 	=  	$this->wizard_model->get_uri($uri, $id_wapp);
				//$result 	= 	$this->wizard_model->get_title($this->input->post($this->_nombre_aplicacion), $id_wapp);
				if($result)
				{
					$this->session->set_flashdata('error',$this->lang->line('appexits'));
					redirect('wizard/'.$id_wapp);
					die();
					//$uri = $this->get_uri_plus($result->uri);
					//$result = $this->wizard_model->get_uri($uri, $id_wapp);
				}else{
					$data	=	array(
										'title' 						=>	$this->input->post($this->_nombre_aplicacion),
										'description' 			=>	$this->input->post($this->_descripcion),
										'category' 					=>	$this->input->post($this->_categoria),
										'uri' 							=> 	$uri,
										'price'                         =>  $this->input->post($this->_price_percent),
										'titulo_html' 			=>	$this->input->post($this->_nombre_aplicacion),
										'slogan_html' 			=>	$this->input->post($this->_slogan),
										'description_html'	=>	$this->input->post($this->_descripcion),
										'video_html'				=> 	$this->input->post($this->_video),
										'url_landing'				=> 	$this->input->post($this->_dominio_propio),
										'private'						=> 	$this->input->post($this->_privacidad),
										'special'						=>  $this->input->post($this->_privacidad)
									);
					$updated	=	$this->wizard_model->update_app_data($id_wapp, $data);
					if(!empty($updated) && ($data['private']==1 || $data['private']==0)){
						$this->wizard_model->update_permission_app_by_private($data['private'], $id_wapp);
					}
					if(!empty($updated))
					{
						if($this->input->post($this->_dynamic_name))
						{
							if($this->input->post($this->_dynamic_type))
							{
								$name	=	$this->input->post($this->_dynamic_name);
								$type	=	$this->input->post($this->_dynamic_type);
								$default =  $this->input->post($this->_dynamic_default);
								$dinamic_id = $this->input->post($this->_dynamic_id);
								if(count($name) < 10 and count($name) == count($type))
								{
									$fields = array();
									$update = array();
									for($i = 0; $i < count($name); $i++)
									{
										if($name[$i] != "" and isset($name[$i]))
										{
											$the_default = "";
											if($type[$i] == 4 || $type[$i] == 5){

												if(!empty($default[$i])){
													$real_def = array();
													$the_default2 = explode("\n", $default[$i]);
													foreach($the_default2 as $def2):
														$def2 = trim($def2);
														if(!empty($def2)) array_push($real_def, $def2);
													endforeach;
													$the_default = json_encode($real_def);
												}
											}

											$name_fields = $this->sanitize_text($name[$i]);
											if(!empty($dinamic_id[$i])){
												$field_one = $this->wizard_model->check_if_field_exits($dinamic_id[$i], $id_wapp);
												if($field_one){
													array_push($update,
														array(
																			'id'					=> $dinamic_id[$i],
																			'name_fields' 			=> $name_fields,
																			'id_wapp'				=> $id_wapp,
																			'name'					=> $name[$i],
																			'default'				=> (!empty($the_default)) ? $the_default : ""
																		)
													);
												}
											}else{
												array_push($fields, array(
																			'name_fields' 	=> $name_fields,
																			'tipo'		 	=> $type[$i],
																			'id_wapp'		=> $id_wapp,
																			'name'			=> $name[$i],
																			'default'		=> (!empty($the_default)) ? $the_default : ""
																		)
															);
											}
										}
									}
									if(!empty($fields))
									{
										$batch 	= 	$this->wizard_model->insert_batch_fields($fields);
									}
									if(!empty($update))
									{
										//echo var_dump($update);
										$batch 	= 	$this->wizard_model->update_batch_fields($update);
									}
								}
							}
						}
						$this->session->set_flashdata('release','TRUE');
						$this->session->set_flashdata('exitoso',$this->lang->line('updated_data'));

						//Sent email of applicación.
						$this->wizard_model->send_email_aprove($this->input->post($this->_nombre_aplicacion), $uri);
						redirect('wizard/'.$id_wapp);
					}
				}
			}else{
				$this->session->set_flashdata('error',$this->lang->line('appnotfoundtosave'));
				redirect('user/apps');
				//$wapp = $this->wizard_model->get_last_not_aproved_app($this->session->userdata('user_id'));
			}
		}
	}

	/**
	* FUNCION AJAX! Elimina un campo dinámico
	*/
	function delete_dynamic(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('app_id', 'app_id', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('dinamic_id', 'dinamic_id', 'required|xss_clean|numeric');

		$this->load->model('wizard_model');
		$this->lang->load('wizard');

		if ($this->form_validation->run() == FALSE){
			echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cant_delete_dynamic')));
		}
		else{
			if($this->check_if_owner($this->input->post('app_id'))){
				$success = $this->wizard_model->delete_dynamic_field($this->input->post('dinamic_id'), $this->input->post('app_id'));
				if($success){
					echo json_encode(array('cod'=>1, 'messa'=>$this->lang->line('dynamic_deleted_cool')));
				}else{
					echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cant_delete_dynamic')));
				}
			}else{
				echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cant_delete_dynamic')));
			}
		}
	}


	/**
	* Plus optiene la uri duplicada y le agrega un nro al final
	*/
	function get_uri_plus($uri){
		$match = preg_match('*\d+$*', $uri, $matches);
		$nro = 0;
		if($match){
			$nro = intval($matches[0]) + 1;
			return preg_replace('*\d+$*', $nro, $uri);
		}else{
			return $uri.'1';
		}

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

	/**
	* función para subir las imagenes y asociarlas a la aplicación
	*/
	function upload_img(){
		$this->lang->load('wizard');
		$this->load->model('wizard_model');
		$id_app = $this->input->post('wapp');
		$type = $this->input->post('type');
		if(!empty($id_app)&&!empty($type)){
			$config['upload_path'] 		= './public/img/apps/';
			$config['allowed_types'] 	= 'gif|jpg|png';
			$config['encrypt_name'] 	=  FALSE;
			$config['max_size']			= '2000';
			$config['max_width'] 		= '1024';
			$config['max_height'] 		= '768';
			$config['min_width'] 		= '998';
			$config['min_height'] 		= '648';
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload($type)){
				return false;
			}else{
				$imagen		=	$this->upload->data();

				$current_img = $this->wizard_model->return_imagen($id_app, $type);
				$this->load->helper('file');
				try{
					if(!empty($current_img) && (trim('img/apps/'.$imagen['file_name']) != trim($current_img))) {
						unlink('./public/'.$current_img);
					}
				}catch(Exception $e){

				}

				$return["files"][] = array(
					'name' => $imagen['file_name'],
					'size' => $imagen['file_size'],
					'url' => base_url('public/img/apps/'.$imagen['file_name']),
					'thumbnail_url' => base_url('public/img/apps/'.$imagen['file_name']),
					'delete_url' => '',
					'delete_type' => '',
				);

				$this->wizard_model->update_app_img($id_app, 'img/apps/'.$imagen['file_name'], $type);

				echo json_encode($return);
			}
		}else{
			return false;
		}
	}

	/**
	* función ajax para subir audio
	*/

	function upload_audio(){
		$this->session->keep_flashdata('url');

		$this->lang->load('wizard');
		$this->load->model('wizard_model');
		$this->load->model('apps_model');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('nombre', $this->lang->line('name'), 'required|xss_clean|max_length[99]|min_length[6]|trim');
		$this->form_validation->set_rules('id_wapp', $this->lang->line('_id_app'), 'required|xss_clean|numeric');

		if ($this->form_validation->run() == FALSE){
					echo json_encode(array(
									'cod' 	=> 0,
									'messa'		=> validation_errors()
									));
		}
		else{
			$this->load->library('upload', $this->_set_upload_options());
			//Subir audio uno
			if($this->upload->do_upload('upload_audio'))
			{
				$audio1		=	$this->upload->data();
				$params 	= 	array('filename' => './public/audios/'.$audio1['file_name']);
				$this->load->library('mp3file', $params);
				$x = $this->mp3file->get_metadata();

				if(!isset($x['Length'])) $x['Length']=round($x['Filesize']/1024,2);
				if($x['Length'] !== NULL)
				{
					$insert		= 	array(
															'name' 		=> $this->input->post('nombre'),
															'path' 		=> $audio1['file_name'],
															'user_id'   => $this->session->userdata('user_id'),
															'id_app'	=> $this->input->post('id_wapp'),
															'duration'	=> $x['Length'],
															'size'		=> ($x['Filesize']/1024),
															'tipo'		=> 'audio'
														);

					$inser_au 	= 	$this->wizard_model->save_audio($insert, $this->input->post('id_wapp'), 'audio');

					if($inser_au){
						$audio_data = $this->wizard_model->get_library_audio_by_id($inser_au);
						echo json_encode(array(
							'cod'			=> 1,
							'audio_data'	=> $audio_data,
							'name' 			=> $audio1['file_name'],
							'size' 			=> $audio1['file_size'],
							'url' 			=> base_url('public/audios/'.$audio1['file_name']),
							'thumbnail_url' => base_url('public/audios/'.$audio1['file_name']),
							'delete_url' 	=> '',
							'delete_type' 	=> ''
						));
					}else{
						try{
							unlink('./public/audios/'.$audio1['file_name']);
						}catch(Exception $e){

						}
						echo json_encode(array(
									'cod' 	=> 0,
									'messa' => $this->lang->line('cant_upload_audio')
									));
					}
				}
			}else{
				echo json_encode(array(
									'cod' 	=> 0,
									'messa' => $this->lang->line('cant_upload_audio'),
									'upload_messa' => $this->upload->display_errors()
									));
			}
		}
	}

	/**
	* Funcion agregar un audio grabado por el usuario
	*/
	public function add_audio_record()
	{
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
		$this->form_validation->set_rules('nombre', $this->lang->line('name'), 'xss_clean|max_length[99]|amin_length[2]|trim');
		$this->form_validation->set_rules('id_wapp', $this->lang->line('_id_app'), 'xss_clean|numeric');

		if ($this->form_validation->run() == FALSE){
			echo "cod=0&messa=".validation_errors();
			die();
		}

		$id_wapp =	$this->input->post('id_wapp');
		if($id_wapp == FALSE){
			echo "cod=0&messa=No se puede subir el audio, por favor refresque la página y vuelva a intentarlo";
			die();
		}
		/*
		if(!$this->check_if_owner($id_wapp)){
			echo "cod=0&messa=No se puede subir el audio, por favor refresque la página y vuelva a intentarlo";
			die();
		}
		*/
		$config['upload_path'] 		=  './public/audios/';
		$config['allowed_types'] 	= 'wav|mp3';
		$config['encrypt_name'] 	=  true;
		$config['max_size']			= '5000';


		if(isset($_POST['upload_type']) && $_POST['upload_type']=="html5"){
			$audio64=explode("audio/wav;base64,",$_POST['Filedata']);

			$audio64=$audio64[1];

			$decoded=base64_decode($audio64);
				if ( ! file_put_contents($config['upload_path'].$_POST['filename'],$decoded))
				{
					echo $this->upload->display_errors();
					 echo "cod=0&messa=Hay un error al intentar subir el audio";//.$this->lang->line('cant_upload_audio');
					die();
				}

				$audio1		=	array('file_name'=>$_POST['filename']);
		}
		//SI ES EL GRABADO DE HTML5
		ELSE{
			//SI ES EL GRABADOR SWF

				$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('Filedata'))
				{
					 echo "cod=0&messa=Error, no cumple con los requisitos"; //.$this->lang->line('cant_upload_audio
					 die();
				}

				$audio1		=	$this->upload->data();

		}


		//DIE($decoded);



		//else
		//{


			$params 	= 	array('filename' => './public/audios/'.$audio1['file_name']);
			chmod('./public/audios/'.$audio1['file_name'], 0777);
			$this->load->library('mp3file', $params);
			$x = $this->mp3file->get_metadata();
			if(!isset($x['Length'])) $x['Length']=round($x['Filesize']/1024,2);
			//echo json_encode($x);
			if($x['Length'] !== NULL)
			{
				$insert		= 	array(
														'name' 		=> $this->input->post('nombre'),
														'path' 		=> $audio1['file_name'],
														'user_id'   => $this->session->userdata('user_id'),
														'duration'	=> $x['Length'],
														'size'		=> ($x['Filesize']/1024),
														'tipo'		=> 'record'
													);

				$inser_au 	= 	$this->wizard_model->save_audio($insert, $id_wapp, 'audio');

				if($inser_au){
					$audio_data = $this->wizard_model->get_library_audio_by_id($inser_au);

					//echo 'cod=1&messa='.$inser_au;


					echo http_build_query(array(
						'cod'			=> 1,
						'messa'			=> $inser_au
					));


				}else{
					try{
						unlink('./public/audios/'.$audio1['file_name']);
					}catch(Exception $e){

					}
					echo "cod=0&messa=".$this->lang->line('cant_upload_audio');
				}
			}
		//}
	}

	/**
	* Función ajax para obtener los datos de un audio grabado.
	*/
	function ajax_get_audio_recorded_data(){
		$this->session->keep_flashdata('url');

		$this->lang->load('wizard');
		$this->load->model('wizard_model');

		$audio_data = $this->wizard_model->get_library_audio_by_id($this->input->post('id_content'));

		if($audio_data){
			echo json_encode(array(
				'cod'			=> 1,
				'audio_data'	=> $audio_data,
				'name' 			=> $audio_data->path,
				'size' 			=> $audio_data->size,
				'url' 			=> base_url('public/audios/'.$audio_data->path),
				'thumbnail_url' => base_url('public/audios/'.$audio_data->path),
				'delete_url' 	=> '',
				'delete_type' 	=> ''
			));
		}else{
			echo json_encode(array(
							'cod' 	=> 0,
							'messa' => $this->lang->line('cant_upload_audio')
							));
		}

	}

	/**
	* Funcion ajax para eliminar campos de la librería en batch
	*/
	function ajax_delete_batch(){
		ini_set('display_errors', 'on');

		$this->session->keep_flashdata('url');

		$this->lang->load('wizard');
		if(!$this->_login_in())
		{
			echo json_encode(array(
							'cod' 	=> 0,
							'messa' => $this->lang->line('loginplease')
							));
			die();
		}

		$valores = $this->input->post('check_content');
		if(is_array($valores) and !empty($valores)){
			$ids_text 	= array();
			$ids_audio  = array();
			for($i=0;$i<count($valores);$i++)
			{
				$exp = explode('_', $valores[$i]);
				if(trim($exp[1]) == "text")  $ids_text[]	= trim($exp[0]);
				if(trim($exp[1]) == "audio") $ids_audio[]	= trim($exp[0]);
			}

			$this->load->model('wizard_model');
			$result_text_speech = false;
			$result_audio = false;

			if(!empty($ids_text)){
				$result_text_speech = $this->wizard_model->batch_delete_text_speach(
																	$this->session->userdata('user_id'),
																	$ids_text
																);
			}

			if(!empty($ids_audio)){
			$result_audio = $this->wizard_model->batch_delete_audio(
																	$this->session->userdata('user_id'),
																	$ids_audio
																);
			}

			$return = array();

			if($result_text_speech)
			{
				$return['text_speech'] = $ids_text;
				//$this->session->set_flashdata('exitoso',$this->lang->line('deletebatchsuccess'));
			}
			if($result_audio)
			{
				$return ['audio'] = $ids_audio;
				//$this->session->set_flashdata('error',$this->lang->line('errorbatchdelete'));
			}

			if(!empty($return)){
				echo json_encode(array('cod'=>1, 'messa'=>$return));
			}else{
				echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('error_content_delete')));
			}

		}else{
			echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('error_content_delete')));
		}

	}

	/**
	* Funcion Ajax para guardar un texto speach
	*/

	function ajax_save_text_speach(){
		$this->session->keep_flashdata('url');

		$this->load->library('form_validation');
		$this->load->model('wizard_model');
		$this->lang->load('wizard');

		$this->form_validation->set_rules('txt_msg_to_speech', $this->lang->line("messagena"), 'required|xss_clean|max_length[1000]|min_length[6]|trim');
		$this->form_validation->set_rules('cbo-vozmini', $this->lang->line('voicena'), 'xss_clean|numeric');
		$this->form_validation->set_rules($this->_id_app, $this->lang->line('_id_app'), 'required|xss_clean|numeric');

		if(!$this->_login_in())
		{
			echo json_encode(array(
							'cod' 	=> 0,
							'messa' => $this->lang->line('loginplease')
							));
			die();
		}

		if ($this->form_validation->run() == FALSE){
			/*$this->session->set_flashdata('error',validation_errors());
			redirect('wizard');*/

			//$category 	= 	$this->wizard_model->get_category();
			echo json_encode(array(
									'cod' 		=> 0,
									'messa'		=> validation_errors()
									));
			die();
		}else{
			$data = array(
				'name'		=> implode(' ', array_slice(explode(' ', $this->input->post('txt_msg_to_speech')), 0, 3)).'...',
				'text'		=> $this->input->post('txt_msg_to_speech'),
				'voice'		=> $this->input->post('cbo-vozmini'),
				'user_id'	=> $this->session->userdata('user_id'),
				'id_app'	=> $this->input->post($this->_id_app)
			);
			$posible_id = $this->input->post('id_content_text');
			if(!empty($posible_id)){
				$result = $this->wizard_model->update_text_speach($data, $posible_id, $this->session->userdata('user_id'));
				if($result){
					$result = $posible_id;
				}else{
					echo json_encode(array(
									'cod' 		=> 0,
									'messa'		=> $this->lang->line('error_text_speach_update')
									));
					die();
				}
			}else{
				$result = $this->wizard_model->save_text_speech($data, $this->input->post($this->_id_app));
			}

			if(!empty($result)){
				//$data['id'] = $result;
				$data_return = $this->wizard_model->get_library_texts_by_id($result);
				echo json_encode(array(
						'cod' => 1,
						'messa'	=> $data_return
				));
			}else{
				echo json_encode(array(
									'cod' 		=> 0,
									'messa'		=> $this->lang->line('error_text_speach_save')
									));
			}
		}
	}

	/**
	* Función para actualizar si graban intro o cierre en la aplicación
	*/

	function ajax_intro_cierre_update(){
		$this->session->keep_flashdata('url');

		$this->load->library('form_validation');
		$this->load->model('wizard_model');
		$this->lang->load('wizard');

		$this->form_validation->set_rules('resp', 'resp', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('id_wapp', 'id_wapp', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('action', 'action', 'required|xss_clean');

		if(!$this->_login_in())
		{
			echo json_encode(array(
							'cod' 	=> 0,
							'messa' => $this->lang->line('loginplease')
							));
			die();
		}

		if ($this->form_validation->run() == FALSE){
			echo json_encode(array(
									'cod' 		=> 0,
									'messa'		=> $this->lang->line('cant_update_the_select')
									));
			die();
		}else{
			$result = $this->wizard_model->intro_cierre_update($this->input->post('resp'), $this->input->post('id_wapp'), $this->input->post('action'), $this->session->userdata('user_id'));
			if($result){
				echo json_encode(array('cod'=>1, 'messa'=>$this->input->post('resp')));
			}else{
				echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cant_update_the_select')));
			}
		}
	}

	/**
	* Funcion ajax para eliminar un texto speech
	*/
	function ajax_delete_text_speech(){
		$this->session->keep_flashdata('url');

		$this->load->library('form_validation');
		$this->load->model('wizard_model');
		$this->lang->load('wizard');

		$this->form_validation->set_rules('id_content', '', 'required|xss_clean|numeric');

		if ($this->form_validation->run() == FALSE){
			echo json_encode(array(
									'cod' 		=> 0,
									'messa'		=> $this->lang->line('error_text_speach_delete')
									));
			die();
		}else{
			$result = $this->wizard_model->delete_text_speach($this->input->post('id_content'), $this->session->userdata('user_id'));
			if($result){
				echo json_encode(array(
									'cod' 		=> 1,
									'messa'		=> $this->input->post('id_content')
									));
				die();
			}else{
				echo json_encode(array(
										'cod' 		=> 0,
										'messa'		=> $this->lang->line('error_text_speach_delete')
										));
				die();
			}
		}
	}

	/**
	* Funcion ajax para eliminar un audio
	*/
	function ajax_delete_audio(){
		$this->session->keep_flashdata('url');

		$this->load->library('form_validation');
		$this->load->model('wizard_model');
		$this->lang->load('wizard');

		$this->form_validation->set_rules('id_content', '', 'required|xss_clean|numeric');

		if ($this->form_validation->run() == FALSE){
			echo json_encode(array(
									'cod' 		=> 0,
									'messa'		=> $this->lang->line('error_audio_delete')
									));
			die();
		}else{
			$result = $this->wizard_model->delete_audio($this->input->post('id_content'), $this->session->userdata('user_id'));
			if($result){
				echo json_encode(array(
									'cod' 		=> 1,
									'messa'		=> $this->input->post('id_content')
									));
				die();
			}else{
				echo json_encode(array(
										'cod' 		=> 0,
										'messa'		=> $this->lang->line('error_audio_delete')
										));
				die();
			}
		}
	}

	/**
	* Función para actualizar el nombre de un audio en ajax
	*/
	function ajax_update_audio_name(){
		$this->session->keep_flashdata('url');

		$this->load->library('form_validation');
		$this->load->model('wizard_model');
		$this->lang->load('wizard');

		$this->form_validation->set_rules('id_content', '', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('nombre', $this->lang->line('name'), 'required|xss_clean|max_length[99]|min_length[2]|trim');

		if ($this->form_validation->run() == FALSE){
			echo json_encode(array(
									'cod' 		=> 0,
									'messa'		=> $this->lang->line('error_audio_update')
									));
			die();
		}else{
			$data = array('name'=>$this->input->post('nombre'));
			$result = $this->wizard_model->update_audio_name($data, $this->input->post('id_content'), $this->session->userdata('user_id'));

			if($result){
				echo json_encode(array(
					'cod' => 1,
					'messa' => 'Oh ready!'
				));
			}else{
				echo json_encode(array(
					'cod' => 0,
					'messa'		=> $this->lang->line('error_audio_update')
				));
			}
		}


	}


	/**
	 * Funcion privada para verificar el Login del usuario
	*/

	public function add_wapp()
	{

		ini_set('display_errors', 'on');
		$this->lang->load('wizard');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();

			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('marketplace');
		}

		$special_title = "Kkatoo";
		if($this->_check_special()){
			$special_title = $this->specialapp->get('title');
		}

		$this->load->model('wizard_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', sprintf($this->lang->line('title'), $special_title), 'required|xss_clean|max_length[99]|min_length[6]|trim');
		$this->form_validation->set_rules('description', $this->lang->line('description'), 'required|xss_clean|max_length[500]|min_length[70]');
		$this->form_validation->set_rules('id_category', $this->lang->line('category'), 'required|xss_clean|numeric|min_length[1]');
		$this->form_validation->set_rules('paypal', $this->lang->line('paypal'), 'required|xss_clean|valid_email');
		$this->form_validation->set_rules('additional_percent', 'Porcentaje', 'required|xss_clean|numeric');
		if ($this->form_validation->run() == FALSE)
		{
			/*$this->session->set_flashdata('error',validation_errors());
			redirect('wizard');*/

			$category 	= 	$this->wizard_model->get_category();
			$data		=	array(
									'category' 	=> $category,
									'error'		=> validation_errors()
									);
			$this->_view_wizard($data);
		}
		else
		{
			$config['upload_path'] 		= './public/img/apps/';
			$config['allowed_types'] 	= 'gif|jpg|png';
			$config['encrypt_name'] 	=  TRUE;
			$config['max_size']			= '2000';
			$config['max_width'] 		= '1024';
			$config['max_height'] 		= '768';
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('imagenapp'))
			{
				$this->session->set_flashdata('error',$this->upload->display_errors());
				redirect('wizard');
			}
			else
			{
				$uri 		=  str_replace(' ', '-', $this->input->post('title'));
				$result 	= 	$this->wizard_model->get_uri($uri);
				if(!empty($result))
				{
					$this->session->set_flashdata('error',$this->lang->line('titleexist'));
					redirect('wizard');
				}
				else
				{
					$imagen		=	$this->upload->data();
					$uri		=	$this->_quitarAcentos($uri);
					$data		=	array(
											'image'			=>	'img/apps/'.$imagen['file_name'],
											'title' 		=>	$this->input->post('title'),
											'description' 	=>	$this->input->post('description'),
											'category' 		=>	$this->input->post('id_category'),
											'upload_audio' 	=>	$this->input->post('upload_audio'),
											'text_speech' 	=>	$this->input->post('text_speech'),
											'record_audio' 	=>	$this->input->post('record_audio'),
											'use_audio' 	=>	$this->input->post('use_audio'),
											'uri'			=>	$uri,
											'user_id'		=> 	$this->session->userdata('user_id'),
											'price'			=> 	$this->input->post('additional_percent')
										);
					$id_wapp	=	$this->wizard_model->insert_wapp($data);
					if(!empty($id_wapp))
					{
						$this->load->model('apps_model');
						$this->upload->initialize($this->_set_upload_options());
						//Subir audio uno
						if($this->upload->do_upload('add-dynamic-audio-1'))
						{	$audio1		=	$this->upload->data();
							$params 	= 	array('filename' => 'public/audios/'.$audio1['file_name']);
							$this->load->library('mp3file', $params);
					        $x = $this->mp3file->get_metadata();
					        if($x['Length'] !== NULL)
					        {
						     	$insert					       	= 	array(
																		'name' 		=> 'Nombre de Prueba',
																		'path' 		=> $audio1['file_name'],
																		'user_id'   => $this->session->userdata('user_id'),
																		'duration'	=> $x['Length'],
																		'size'		=> ($x['Filesize']/1000)
																	);
						        $inser_au 						= 	$this->apps_model->insert_audio($insert);
						        $aux							=	array(
						        											'id_audio'	=>	$inser_au,
						        											'app_id'	=> 	$id_wapp
						        										);
						        $audio_app 						= 	$this->wizard_model->insert_audio_wapp($aux);
					        }
					    }
					    //Subir audio Dos
					    if($this->upload->do_upload('add-dynamic-audio-2'))
						{	$audio2		=	$this->upload->data();
							$params 	= 	array('filename' => 'public/audios/'.$audio2['file_name']);
							$this->load->library('mp3file', $params);
					        $x = $this->mp3file->get_metadata();
					        if($x['Length'] !== NULL)
					        {
						     	$insert					       	= 	array(
																		'name' 		=> 'Nombre de Prueba',
																		'path' 		=> $audio2['file_name'],
																		'user_id'   => $this->session->userdata('user_id'),
																		'duration'	=> $x['Length'],
																		'size'		=> ($x['Filesize']/1000)
																	);
						        $inser_au 						= 	$this->apps_model->insert_audio($insert);
						        $aux							=	array(
						        											'id_audio'	=>	$inser_au,
						        											'app_id'	=> 	$id_wapp
						        										);
						        $audio_app 						= 	$this->wizard_model->insert_audio_wapp($aux);
					        }
					    }
					    //Subir audio 3
					    if($this->upload->do_upload('add-dynamic-audio-3'))
						{	$audio3		=	$this->upload->data();
							$params 	= 	array('filename' => 'public/audios/'.$audio3['file_name']);
							$this->load->library('mp3file', $params);
					        $x = $this->mp3file->get_metadata();
					        if($x['Length'] !== NULL)
					        {
						     	$insert					       	= 	array(
																		'name' 		=> 'Nombre de Prueba',
																		'path' 		=> $audio3['file_name'],
																		'user_id'   => $this->session->userdata('user_id'),
																		'duration'	=> $x['Length'],
																		'size'		=> ($x['Filesize']/1000)
																	);
						        $inser_au 						= 	$this->apps_model->insert_audio($insert);
						        $aux							=	array(
						        											'id_audio'	=>	$inser_au,
						        											'app_id'	=> 	$id_wapp
						        										);
						        $audio_app 						= 	$this->wizard_model->insert_audio_wapp($aux);
					        }
					    }
					    //Subir audio 4
					    if($this->upload->do_upload('add-dynamic-audio-4'))
						{	$audio4		=	$this->upload->data();
							$params 	= 	array('filename' => 'public/audios/'.$audio4['file_name']);
							$this->load->library('mp3file', $params);
					        $x = $this->mp3file->get_metadata();
					        if($x['Length'] !== NULL)
					        {
						     	$insert					       	= 	array(
																		'name' 		=> 'Nombre de Prueba',
																		'path' 		=> $audio4['file_name'],
																		'user_id'   => $this->session->userdata('user_id'),
																		'duration'	=> $x['Length'],
																		'size'		=> ($x['Filesize']/1000)
																	);
						        $inser_au 						= 	$this->apps_model->insert_audio($insert);
						        $aux							=	array(
						        											'id_audio'	=>	$inser_au,
						        											'app_id'	=> 	$id_wapp
						        										);
						        $audio_app 						= 	$this->wizard_model->insert_audio_wapp($aux);
					        }
					    }
					    //Subir audio 5
					    if($this->upload->do_upload('add-dynamic-audio-5'))
						{	$audio5		=	$this->upload->data();
							$params 	= 	array('filename' => 'public/audios/'.$audio5['file_name']);
							$this->load->library('mp3file', $params);
					        $x = $this->mp3file->get_metadata();
					        if($x['Length'] !== NULL)
					        {
						     	$insert					       	= 	array(
																		'name' 		=> 'Nombre de Prueba',
																		'path' 		=> $audio5['file_name'],
																		'user_id'   => $this->session->userdata('user_id'),
																		'duration'	=> $x['Length'],
																		'size'		=> ($x['Filesize']/1000)
																	);
						        $inser_au 						= 	$this->apps_model->insert_audio($insert);
						        $aux							=	array(
						        											'id_audio'	=>	$inser_au,
						        											'app_id'	=> 	$id_wapp
						        										);
						        $audio_app 						= 	$this->wizard_model->insert_audio_wapp($aux);
					        }
					    }
					   	if($this->input->post('add-dynamic-name'))
						{
							if($this->input->post('add-dynamic-type'))
							{
								$name	=	$this->input->post('add-dynamic-name');
								$type	=	$this->input->post('add-dynamic-type');
								if(count($name) <11 and count($name) == count($type))
								{
									$fields = array();
									for($i = 0; $i < count($name); $i++)
									{
										if($name[$i] != "" and isset($name[$i]))
										{
											array_push($fields, array(
																		'name_fields' 	=> str_replace(' ','',$name[$i]),
																		'tipo'		 	=> $type[$i],
																		'id_wapp'		=> $id_wapp,
																		'name'			=> $name[$i]
																	)
														);
										}
									}
									if(!empty($fields))
									{
										$batch 	= 	$this->wizard_model->insert_batch_fields($fields);
									}
								}
							}
						}
					}
					$this->session->set_flashdata('exitoso',$this->lang->line('successapp'));
					redirect('marketplace');
				}
			}
		}
	}

	private function _set_upload_options()
	{
		//  upload an image options
	    $config = array();
	    $config['upload_path'] = './public/audios/';
		$config['allowed_types'] 	= 'wav|mp3';
		$config['encrypt_name'] 	=  TRUE;
		$config['max_size']			= '5000';
	    return $config;
	}

	private function _quitarAcentos($text)
	{
		$text = htmlentities($text, ENT_QUOTES, 'UTF-8');
		$text = strtolower($text);
		$patron = array (
			// Espacios, puntos y comas por guion
			'/[\., ]+/' => '-',

			// Vocales
			'/&agrave;/' => 'a',
			'/&egrave;/' => 'e',
			'/&igrave;/' => 'i',
			'/&ograve;/' => 'o',
			'/&ugrave;/' => 'u',

			'/&aacute;/' => 'a',
			'/&eacute;/' => 'e',
			'/&iacute;/' => 'i',
			'/&oacute;/' => 'o',
			'/&uacute;/' => 'u',

			'/&acirc;/' => 'a',
			'/&ecirc;/' => 'e',
			'/&icirc;/' => 'i',
			'/&ocirc;/' => 'o',
			'/&ucirc;/' => 'u',

			'/&atilde;/' => 'a',
			'/&etilde;/' => 'e',
			'/&itilde;/' => 'i',
			'/&otilde;/' => 'o',
			'/&utilde;/' => 'u',

			'/&auml;/' => 'a',
			'/&euml;/' => 'e',
			'/&iuml;/' => 'i',
			'/&ouml;/' => 'o',
			'/&uuml;/' => 'u',

			'/&auml;/' => 'a',
			'/&euml;/' => 'e',
			'/&iuml;/' => 'i',
			'/&ouml;/' => 'o',
			'/&uuml;/' => 'u',

			// Otras letras y caracteres especiales
			'/&aring;/' => 'a',
			'/&ntilde;/' => 'n',

			// Agregar aqui mas caracteres si es necesario

		);

		$text = preg_replace(array_keys($patron),array_values($patron),$text);
		return $text;
	}

	/*
	Funcion privada cargar la vista de Wizard
	*/
	private function _view_wizard($data = array())
	{
		$this->load->view('wizard', $data);
	}

	private function _login_in()
	{
		return $this->session->userdata('logged_in');
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
			redirect('login/login');
			die();
		}
	}

	/**
	* Verifica si tiene permisos para entrar al wizard
	*/
	function deny_wizard(){
		if($this->permissions->get('deny_wizard')){
			$this->session->set_flashdata('error',$this->lang->line('nopermissionwizard'));
			if($this->specialapp->get('special')){
				if($this->specialapp->get('url_landing')){
					redirect($this->specialapp->get('url_landing'));
				}else{
					redirect('landing/'.$this->specialapp->get('uri'));
				}
			}else{
				redirect('site');
			}
			die();
		}
	}

	/**
	* Organizar nombre de campo dinámico también se utiliza en AJAX
	*/

	function sanitize_text($text=FALSE){
		$name_fields = (!empty($text))?$text:$this->input->post("text");

		$name_fields = $this->_quitarAcentos($name_fields);
		$name_fields = sanitize_title_with_dashes($name_fields);

		if((!empty($text))){
			return $name_fields;
		}else{
			if(is_string($name_fields)){
				echo json_encode(array('cod'=>1, 'messa'=>$name_fields));
			}else{
				echo json_encode(array('cod'=>0, 'messa'=>$name_fields));
			}

		}
	}

	/**
	* Función AJAX para agregar un paquete a una aplicación
	*/

	function ajax_add_package(){
		$this->lang->load('wizard');
		$this->load->model('wizard_model');

		$this->session->keep_flashdata('url');
		if(!$this->_login_in())
		{
			echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('loginplease')));
			die();
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('nro_package', 'nro_package', 'required|xss_clean|is_natural_no_zero');
		$this->form_validation->set_rules('id_wapp', $this->lang->line('_id_app'), 'required|xss_clean|numeric');

		if ($this->form_validation->run() == FALSE){
			echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cant_create_package')));
			die();
		}

		$id_wapp =	$this->input->post('id_wapp');

		if(!$this->check_if_owner($id_wapp)){
			echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cant_create_package')));
			die();
		}

		if($this->wizard_model->get_package_by_app_and_value($id_wapp, $this->input->post('nro_package'))){
			echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('package_exists')));
			die();
		}

		$data = array('id_app'=>$id_wapp, 'amount'=>$this->input->post('nro_package'));
		$result = $this->wizard_model->add_package_application($data);

		if(!empty($result)){
			echo json_encode(array('cod'=>1, 'messa'=>$result));
		}else{
			echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cant_create_package')));
		}
		die();
	}


	/**
	* Función AJAX para remover un paquete de una aplicación
	*/
	function ajax_remove_package(){
		$this->lang->load('wizard');
		$this->load->model('wizard_model');

		$this->session->keep_flashdata('url');
		if(!$this->_login_in())
		{
			echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('loginplease')));
			die();
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_package', 'id_package', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('id_wapp', $this->lang->line('_id_app'), 'required|xss_clean|numeric');

		if ($this->form_validation->run() == FALSE){
			echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cant_remove_package')));
			die();
		}

		$id_wapp =	$this->input->post('id_wapp');

		if(!$this->check_if_owner($id_wapp)){
			echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cant_remove_package')));
			die();
		}

		//$data = array('id_app'=>$id_wapp, 'amount'=>$this->input->post('nro_package'));
		$result = $this->wizard_model->remove_packages($id_wapp,$this->input->post('id_package'));

		if(!empty($result)){
			echo json_encode(array('cod'=>1, 'messa'=>$result));
		}else{
			echo json_encode(array('cod'=>0, 'messa'=>$this->lang->line('cant_remove_package')));
		}
		die();
	}

}
