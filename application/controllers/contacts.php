<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contacts extends CI_Controller {
	/**
	 * Función para manejar todos los contactos de un usuario
	 * 
	 */
	public function contact_manager()
	{
		ini_set('display_errors', 'on');
		
		$this->lang->load('contacts');
		if(!$this->_login_in()){
			
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}
		//Inicalizar variables desde CERO
		$fields		= array();
		$contacts	= array();
		$total 		= 0;
		$id_groups	= 0;
		$id_wapp    = 0;
		$app		= FALSE;
		//Carga del modelo de Apps donde existen algunas funciones utiles
		$this->load->model('apps_model');
		//Libreria de Google para realizar las importaciones
		$this->load->library( 'ci_google' );
		//Helper de manejo de string 
		$this->load->helper('string');
		//Carga de datos iniciales para la vista de manejador de contactos
		$country	= $this->apps_model->get_country();
		$groups		= $this->apps_model->get_group($this->session->userdata('user_id'));
		//Se toma el valor de la variable que viene por get del grupo
		$uri_app 	=	$this->uri->segment(3);
		
		$get = ($this->uri->segments[count($this->uri->segments)-1]=="pages")?end($this->uri->segments):false;
		$_GET["page"] = (is_numeric($get))?$get:false;
		
		if($uri_app !== FALSE)
		{
			$id_groups = $uri_app;
		}
		if($this->session->flashdata('url'))
		{
			$this->session->keep_flashdata('url');
			//Obtiene toda la información de la aplicación
			$result 	=	$this->apps_model->get_uri_app($this->session->flashdata('url'));
			if(empty($result))
			{
				$this->session->set_flashdata('error',$this->lang->line('appnoexist'));
				redirect('marketplace');
			}
			else
			{
				$id_wapp 	= $result->id;
				//Se obtienen los campos dinamicos de la aplicación la cual fue referida
				$fields		= $this->apps_model->get_fields($id_wapp);
				//Si viene con el id desde alguna Aplicación para poder cargar los campos dinamicos	
				if($this->input->get('q'))
				{
					$contacts 	= $this->_get_contacts_app($id_wapp,$fields,$id_groups,$this->input->get('q'));
					$total 		 	= $this->contacts_model->foundRows()->cuantos;
				}
				else
				{
					$contacts 	= $this->_get_contacts_app($id_wapp,$fields,$id_groups);
					$total 		 	= $this->contacts_model->foundRows()->cuantos;
				}
				$app		= TRUE;
			}
		}
		else
		{
			if($this->input->get('q'))
			{
				$contacts 	= $this->_get_contacts($id_groups, $this->input->get('q'));
				$total 		 	= $this->contacts_model->foundRows()->cuantos;
			}
			else
			{
				//Si no viene de ninguna aplicación si no que ingreso de manera directa
				$contacts 	= $this->_get_contacts($id_groups);
				$total 		 	= $this->contacts_model->foundRows()->cuantos;
			}
		}
		$this->load->model('marketplace_model');
		$username = $this->marketplace_model->get_user_name();
		$total_credits		= $this->_get_credit_user();
		//Datos que se enviaran a la vista para cargarlos
		$data	= array(
							'credits'		=> $total_credits,
							'username'		=> $username,
							'url'			=> $this->session->flashdata('url'),
							'id_wapp'		=> $id_wapp,
							'ref_app' 		=> $app,
							'country' 		=> $country,
							'contacts' 		=> $contacts,
							'groups' 			=> $groups,
							'fields' 			=> $fields,
							'link_gp'   	=> $this->ci_google->get_url_connect("contactos"),
							'total'				=> $total
						);
		//Carga de la vista de Contactos
		$this->_view_contact_manager($data);
	}
	
	public function add_contact()
	{
		$this->lang->load('contacts');
		$this->session->keep_flashdata('url');
		if(!$this->_login_in())
		{
			
			//The Special app redirect
			$this->_return_to_special_url();
			
			//die();
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}
		$uri = "";
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 		$this->lang->line('namecontact'), 'required|xss_clean|max_length[150]');
		$this->form_validation->set_rules('indi_pais', 	$this->lang->line('namecountry'), 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('phone', 		$this->lang->line('telephone'), 'required|xss_clean|numeric|min_length[5]|max_length[12]');
		$this->form_validation->set_rules('indi_area', 	$this->lang->line('indi_area'), 'xss_clean|numeric|max_length[10]');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',validation_errors());
			redirect('contacts/contact_manager');
		}
		else
		{
			//Se carga el modelo APPS para traer el code del pais que se llamará
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
				if($this->input->post('id_wapp'))
				{
					$fields	=	$this->apps_model->get_fields($this->input->post('id_wapp'));
					//Se verifica si la aplicación tiene campos dinamicos para realizar el insert	
					if(!empty($fields))
					{
						$item = array();
						foreach($fields as $fiel)
						{
							$id = (string) $fiel->name_fields;
							array_push($item,array(
											  		'id_fields' 	=> $fiel->id,
											  		'id_contact'	=> $result,
											  		'valor'			=> $this->input->post($id),
											  		'user_id'		=> $this->session->userdata('user_id'),
											  		'id_wapp'		=> $this->input->post('id_wapp')
											  		)
										);
						}
						$batch		= $this->apps_model->insert_batch_contacts_fields($item);
					}
				}
				//Si llega el ID del grupo el usuario queda inmediatamente asociado al grupo de donde se inició el agregar.
				if($this->input->post('id_group'))
				{
					$this->load->model('contacts_model');
					$contacts_group	= $this->contacts_model->insert_contact_group_user(
																						$result,
																						$this->input->post('id_group'),
																						$this->session->userdata('user_id')
																					);
					$uri = $this->input->post('id_group');
				}
				$this->session->set_flashdata('exitoso',$this->lang->line('successcontact'));
				$url = $this->session->flashdata('url');
				redirect('contacts/contact_manager/'.$uri);
			}
		}
	}
	
	/**
	 * Función publica para agregar un grupo a un usuario
	 * 
	*/
	public function add_group()
	{
		$this->lang->load('contacts');
		$this->session->keep_flashdata('url');
		if(!$this->_login_in())
		{
			
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}
		$uri = "";
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', $this->lang->line('namegroup'), 'required|xss_clean|max_length[40]');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',validation_errors());
			redirect('contacts/contact_manager');
		}
		else
		{
			$this->load->model('contacts_model');
			$result	= $this->contacts_model->insert_group_user(
																$this->session->userdata('user_id'), 
																$this->input->post('name')
															 );
			if($this->input->post('id_group'))
			{
				$uri = $this->input->post('id_group');
			}
			if($result)
			{
				$this->session->set_flashdata('exitoso',$this->lang->line('successgroup'));
			}
			else
			{
				$this->session->set_flashdata('exitoso',$this->lang->line('erroraddgroup'));
			}
			redirect('contacts/contact_manager/'.$uri);
		}
	}
	
	/**
	 * Funcion publica para importar los contactos de Gmail a la base de datos
	*/
	public function import_user_gmail()
	{
		$this->lang->load('contacts');
		$this->session->keep_flashdata('url');
		$phonecode = "";
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}
		$this->load->library( 'ci_google' );
		$usuarios = $this->ci_google->get_contacts();
		$item = array();
		$this->load->model('contacts_model');
		$country 		=	$this->contacts_model->get_country_code_user($this->session->userdata('user_id'));
		if(!empty($country))
		{
			$phonecode = $country->phonecode;
		}
		foreach($usuarios as $user)
		{
			 array_push($item,array(
							  		'name' 		=> $user['nombre'],
							  		'phone'		=> str_replace('-','',$user['telefono']),
							  		'email'		=> $user['email'],
							  		'user_id'	=> $this->session->userdata('user_id'),
							  		'gmail'		=> 1,
							  		'indi_pais'	=> $phonecode
							  		)
						);
		}
		$this->load->model('apps_model');
		
		$result 	=	$this->apps_model->insert_batch_contact($item);
		$gmail 		=	$this->contacts_model->get_contacts_gmail($this->session->userdata('user_id'));
		if(!empty($gmail))
		{
			$ids 			= 	array();
			$group 			= 	$this->contacts_model->get_group_gmail($this->session->userdata('user_id'));
			if(empty($group))
			{
				$insert 		=	$this->contacts_model->insert_group_user($this->session->userdata('user_id'), "Gmail");
				$id_group 		= 	$this->db->insert_id();
			}
			else
			{
				$id_group 		= 	$group->id;
			}
			foreach($gmail as $g)
			{
				array_push($ids,array(
								  		'id_contact_user' 	=> $g->id,
								  		'id_group' 			=> $id_group,
								  		'user_id' 			=> $this->session->userdata('user_id')
								  		)
							);
			}
			$insert_batch 	=	$this->contacts_model->insert_batch_group($ids);
		}
		$update		 	=	$this->contacts_model->update_user_gmail($this->session->userdata('user_id'));
		if($result)
		{
			$this->session->set_flashdata('exitoso',$this->lang->line('contactsimportsuccess'));
			redirect('contacts/contact_manager');	
		}
	}
	
	public function edit_contact_user()
	{
		$this->session->keep_flashdata('url');
		$this->lang->load('contacts');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}
		$uri = "";
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_contact', 'id_contact', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('name',	$this->lang->line('namecontact'), 'required|xss_clean|max_length[150]');
		$this->form_validation->set_rules('indi_pais', 	$this->lang->line('namecountry'), 'required|xss_clean|numeric|max_length[12]');
		$this->form_validation->set_rules('phone', 		$this->lang->line('telephone'), 'required|xss_clean|numeric|min_length[5]|max_length[12]');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',validation_errors());
			redirect('contacts/contact_manager');
		}
		else
		{
			//Se carga el modelo APPS para traer el code del pais que se llamará
			$this->load->model('contacts_model');
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
			$result	=	$this->contacts_model->update_contact(
																$this->input->post('id_contact'),
																$this->session->userdata('user_id'),
																$data
															 );
			if($result)
			{
				if($this->input->post('id_wapp'))
				{
					
					$fields	=	$this->apps_model->get_fields($this->input->post('id_wapp'));
					//Se verifica si la aplicación tiene campos dinamicos para realizar el update	
					if(!empty($fields))
					{
						$item = array();
						foreach($fields as $fiel)
						{
							$id = (string) $fiel->name_fields;
							array_push($item,array(
											  		'id_fields' 	=> $fiel->id,
											  		'id_contact'	=> $this->input->post('id_contact'),
											  		'valor'			=> $this->input->post($id),
											  		'user_id'		=> $this->session->userdata('user_id'),
											  		'id_wapp'		=> $this->input->post('id_wapp')
											  		)
										);
						}
						$delete		= $this->contacts_model->delete_fields_contacts(
																						$this->session->userdata('user_id'),
																						$this->input->post('id_contact'),
																						$this->input->post('id_wapp')
																					);
						$batch		= $this->apps_model->insert_batch_contacts_fields($item);
					}
				}
				if($this->input->post('id_group'))
				{
					$uri = $this->input->post('id_group');
				}
				$this->session->set_flashdata('exitoso',$this->lang->line('updatecontactsuccess'));
				$url = $this->session->flashdata('url');
				redirect('contacts/contact_manager/'.$uri);
			}
		}
	}
	
	/*
	* Funcion publica para eliminar un contacto de un usuario especifico
	*/
	public function delete_contact()
	{
		$this->session->keep_flashdata('url');
		$this->lang->load('contacts');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}
		$uri = "";
		if ($this->uri->segment(3) === FALSE)
		{
			$this->session->set_flashdata('error',$this->lang->line('urlnovaliddelete'));
			redirect('contacts/contact_manager/'.$uri);
		}
		else
		{
			$this->load->model('contacts_model');
						
			if($this->uri->segment(4))
			{
				//Eliminamos el contacto del grupo
				$result = $this->contacts_model->delete_contact_group(
																$this->session->userdata('user_id'),
																$this->uri->segment(3),
																$this->uri->segment(4)
															);
				$uri = $this->uri->segment(4);
			}else{
				//Eliminamos el contacto de totalmente
				$result = $this->contacts_model->delete_contact(
																$this->session->userdata('user_id'),
																$this->uri->segment(3)
															);
			}
			if($result)
			{
				$this->session->set_flashdata('exitoso',$this->lang->line('deletecontactsuccess'));
			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('errordeletecontact'));
			}
			redirect('contacts/contact_manager/'.$uri);
		}
	}
	
	/*
	* Funcion publica para eliminar un contacto de un usuario especifico
	*/
	public function batch_delete_contact()
	{
		$this->session->keep_flashdata('url');
		$this->lang->load('contacts');
		if(!$this->_login_in())
		{
			//The Special app redirect
			//$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}
		$uri = "";
		$this->load->library('form_validation');
		$this->form_validation->set_rules('valores', 'idsdelete', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',$this->lang->line('nodeletebatchselected'));
			redirect('contacts/contact_manager');
		}
		else
		{
			$valores = $this->input->post('valores');
			if(is_array($valores) and !empty($valores))
			{
				$ids = array();
				for($i=0;$i<count($valores);$i++)
				{
					$ids[]	= $valores[$i];
				}
				$this->load->model('contacts_model');
				if($this->input->post('id_group'))
				{
					$uri = $this->input->post('id_group');
					$result = $this->contacts_model->batch_delete_contact_group(
																		$this->session->userdata('user_id'),
																		$this->input->post('id_group'),
																		$ids
																	);
				}else{
					$result = $this->contacts_model->batch_delete_contact(
																		$this->session->userdata('user_id'),
																		$ids
																	);
				}
				if($result)
				{
					$this->session->set_flashdata('exitoso',$this->lang->line('deletebatchsuccess'));
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('errorbatchdelete'));
				}
			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('errorbatchdelete'));
			}
			redirect('contacts/contact_manager/'.$uri);
		}
	}
	/*
		Funcion para agregar un codigo de pais a los contactos
	*/
	public function batch_add_pais_to_contacto(){
		//print_r($this->input->post());
		$this->session->keep_flashdata('url');
		$this->lang->load('contacts');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}
		$uri = "";
		$this->load->library('form_validation');
		$this->form_validation->set_rules('valores', 'idsdelete', 'required|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',$this->lang->line('nocontacttochangeprefix'));
			redirect('contacts/contact_manager');
		}
		else
		{
			if($this->input->post('id_group'))
			{
				$uri = $this->input->post('id_group');
			}
			$valores = $this->input->post('valores');
			if(is_array($valores) and !empty($valores))
			{
				$ids = array();
				for($i=0;$i<count($valores);$i++)
				{
					$ids[]	= $valores[$i];
				}
				$this->load->model('contacts_model');
				$result = $this->contacts_model->batch_change_prefix_pais_contact(
																		$this->session->userdata('user_id'),
																		$ids,
																		$this->input->post('pais_contact')
																	);
				if($result)
				{
					$this->session->set_flashdata('exitoso',$this->lang->line('changeprefixbatchsuccess'));
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('changeprefixbatcherror'));
				}
			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('changeprefixbatcherror'));
			}
			redirect('contacts/contact_manager/'.$uri);
		}
	}
	
	/*
		Funcion privada para traer los contactos que tienen campos dinamicos
	*/
	private function _get_contacts_app($wapp = 0 , $fields = array(), $group = 0, $q = "")
	{
		$contacts = array();
		$nocontacts = array();
		$this->load->model('contacts_model');
		//Si no se va a filtrar se traen todos los datos normales
		$contacts 	=  	$this->contacts_model->get_contacts_wapp($this->session->userdata('user_id'), $wapp,array(),$group,$q);
		if(empty($contacts))
		{
			$nocontacts =  	$this->contacts_model->get_contacts_no_wapp($this->session->userdata('user_id'), FALSE, array(),$group,$q);
		}
		else
		{
			$ids = array();
			foreach($contacts as $c)
			{
				$ids[] = $c->id;
			}
			$nocontacts =  	$this->contacts_model->get_contacts_no_wapp($this->session->userdata('user_id'), TRUE, $ids, $group,$q);
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
	
	/*
		Funcion privada para traer los contactos que no tienen campos dinamicos
	*/
	private function _get_contacts($group = 0, $q = "")
	{
		//Carga del modelo de contacts para traer los contactos SIN campos dinamicos
		$this->load->model('contacts_model');
		$data 		= array();
		$contacts	= $this->contacts_model->get_contacts_all($this->session->userdata('user_id'), $group, $q);
		$i = 0;
		if(!empty($contacts))
		{
			foreach ($contacts as $conta)
			{
					$data[$i]['name'] 		= $conta->name;
					$data[$i]['id']   		= $conta->id;
					$data[$i]['indi_pais']	= $conta->indi_pais;
					$data[$i]['indi_area']  = $conta->indi_area;
					$data[$i]['phone']  	= $conta->phone;
					$data[$i]['user_id']  	= $conta->user_id;
					$i++;
			}
		}
		return $data;
	}
	/**
	* Funcion publica para generar el csv al usuario.
	*/
	public function generate_csv()
	{
		$this->lang->load('contacts');
		$this->session->keep_flashdata('url');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=contactos.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		$csv	= "nombre,indipais,telefono";
		if($this->uri->segment(3)!==FALSE)
		{
			$this->load->model('apps_model');
			$fields	=	$this->apps_model->get_fields($this->uri->segment(3));

			if(!empty($fields))
			{
				foreach($fields as $fiel)
				{
					$csv .= ",".utf8_decode($fiel->name_fields); 
					
				}
			}


		}
		else
		{
			$csv .= "\n";
		}
		echo $csv;
	}
	
	/**
	* Funcion publica para importar un CSV de contactos.
	*/
	public function import_csv_contact()
	{
		date_default_timezone_set('Africa/Casablanca');
		ini_set('memory_limit', '-1');

		$this->lang->load('contacts');
		$this->session->keep_flashdata('url');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}
		$uri = "";
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name_file', 'name_file', 'required|max_length[190]');
		$this->form_validation->set_rules('telefono', 'telefono', 'numeric');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',$this->lang->line('errorcsvadd'));
			redirect('contacts/contact_manager/');
		}
		else
		{
			$this->load->library('csvreader');
			$filePath 	= './public/csv/'.$this->input->post('name_file');
			$data		= $this->csvreader->parse_file($filePath);
			if(!empty($data))
			{
				$this->load->model('contacts_model');
				$this->load->model('apps_model');
				$fields		=	array();
				if($this->input->post('id_wapp'))
				{
					$fields	=	$this->apps_model->get_fields($this->input->post('id_wapp'));
				}
				//echo var_dump($data);
				foreach($data as $user)
				{
					$result = FALSE;
					if(!empty($user['nombre']) and !empty($user['telefono']) and !empty($user['indipais']))
					{
						$item = array(
									  		'name' 		=> utf8_encode($user['nombre']),
									  		'indi_pais'	=> trim($user['indipais']),
									  		'indi_area'	=> '',//trim($user['indiarea']),
									  		'phone'		=> trim($user['telefono']),
									  		'user_id'	=> $this->session->userdata('user_id'),
									  		'gmail'		=> 1
								);
						$result 	=	$this->contacts_model->insert_contact($item);
					}
					if(!empty($result) and $this->input->post('id_wapp'))
					{
						
						//Se verifica si la aplicación tiene campos dinamicos para realizar el insert	
						if(!empty($fields))
						{
							$item = array();
							foreach($fields as $fiel)
							{
								$field_value = "";
								if(!empty($user[$fiel->name_fields])) $field_value = $user[$fiel->name_fields];
								array_push($item,array(
												  		'id_fields' 	=> $fiel->id,
												  		'id_contact'	=> $result,
												  		'valor'			=> utf8_encode(trim($field_value)),
												  		'user_id'		=> $this->session->userdata('user_id'),
												  		'id_wapp'		=> $this->input->post('id_wapp')
												  		)
											);
							}
							$batch		= $this->apps_model->insert_batch_contacts_fields($item);
						}
					}
				}
				if($this->input->post('id_group'))
				{
					$gmail 		=	$this->contacts_model->get_contacts_gmail($this->session->userdata('user_id'));
					if(!empty($gmail))
					{
						$ids 			= 	array();
						foreach($gmail as $g)
						{
							array_push($ids,array(
											  		'id_contact_user' 	=> $g->id,
											  		'id_group' 			=> $this->input->post('id_group'),
											  		'user_id' 			=> $this->session->userdata('user_id')
											  		)
										);
						}
						$insert_batch 	=	$this->contacts_model->insert_batch_group($ids);
					}
					$uri = $this->input->post('id_group');
				}
				else
				{
					$insert 		=	$this->contacts_model->insert_group_user(
																					$this->session->userdata('user_id'), 
																					date('l jS \of F Y h:i:s A')
																				);
					$id_group 		= 	$this->db->insert_id();
					$gmail 		=	$this->contacts_model->get_contacts_gmail($this->session->userdata('user_id'));
					if(!empty($gmail))
					{
						$ids 			= 	array();
						foreach($gmail as $g)
						{
							array_push($ids,array(
											  		'id_contact_user' 	=> $g->id,
											  		'id_group' 			=> $id_group,
											  		'user_id' 			=> $this->session->userdata('user_id')
											  		)
										);
						}
						$insert_batch 	=	$this->contacts_model->insert_batch_group($ids);
					}
					$uri			=	$id_group;
				}
				$update		 	=	$this->contacts_model->update_user_gmail($this->session->userdata('user_id'));
			}
			if($result)
			{
				$this->session->set_flashdata('exitoso',$this->lang->line('addcsvsuccess'));
				redirect('contacts/contact_manager/'.$uri);
			}
		}
	}
	
	/**
	 * Funcion publica para guardar contactos de un archivo CSV
	*/
	public function add_csv_contact()
	{
	ini_set('display_errors', 'on');
	ini_set('memory_limit', '-1');

		$this->session->keep_flashdata('url');
		$this->lang->load('contacts');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}
		$config['upload_path'] 		= './public/csv/';
		$config['allowed_types'] 	= 'csv';
		$config['encrypt_name'] 	=  TRUE;
		$config['max_size']			= '8096';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('contacts_archive'))
		{
			$this->session->set_flashdata('error',$this->upload->display_errors());
			redirect('contacts/contact_manager/');
		}
		else
		{
			$this->load->library('csvreader');
			$upload = 	$this->upload->data();
			$data   = 	array();
			$data	= 	$this->csvreader->parse_file($upload['full_path']);
			$this->load->model('apps_model');
			$fields	= 	array();
			if($this->input->post('id_wapp'))
			{
				$fields		= $this->apps_model->get_fields($this->input->post('id_wapp'));
			}
			$this->load->model('marketplace_model');
			$username = $this->marketplace_model->get_user_name();
			$credits		= $this->_get_credit_user();
			$name	=	$upload['file_name'];
			$datos	=	array(
								'name_file' => 	$name,
								'username'  =>  $username,
								'credits'   =>	$credits,
								'id_wapp'   => 	$this->input->post('id_wapp'),
								'id_group'  => 	$this->input->post('id_group'),
								'fields'	=> 	$fields,
								'data'		=> 	$data
							);
			$this->_view_csv_preview($datos);
		}
	}
	
	public function copy_user_group()
	{
		ini_set('display_errors', 'on');
		$this->session->keep_flashdata('url');
		$this->lang->load('contacts');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}
		$uri = "";
		$this->load->library('form_validation');
		$this->form_validation->set_rules('valores', 'idsdelete', 'required|xss_clean');
		$this->form_validation->set_rules('grupos_cambiar', 'Id Grupo', 'required|xss_clean|numeric');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',$this->lang->line('errorcopyusergroup'));
			redirect('contacts/contact_manager');
		}
		else
		{
			if($this->input->post('grupos_cambiar'))
			{
				$uri = $this->input->post('grupos_cambiar');
			}
			$valores = $this->input->post('valores');
			if(is_array($valores) and !empty($valores))
			{
				$ids = array();
				$this->load->model('contacts_model');
				for($i=0;$i<count($valores);$i++)
				{
					$result = $this->contacts_model->get_contacts_user_exist(
																				$valores[$i],
																				$this->input->post('grupos_cambiar'),
																				$this->session->userdata('user_id')
																			);
					if(empty($result))
					{
						$insert = $this->contacts_model->insert_contact_group_new(
																					$valores[$i],
																					$this->input->post('grupos_cambiar'),
																					$this->session->userdata('user_id')
																				);
					}													
				}
				$this->session->set_flashdata('exitoso',$this->lang->line('successcopygroup'));
			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('errorcopyusergroup'));
			}
			redirect('contacts/contact_manager/'.$uri);
		}
	}
	
	public function move_user_group()
	{
		ini_set('display_errors', 'on');
		$this->session->keep_flashdata('url');
		$this->lang->load('contacts');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}	
		$uri = "";
		$this->load->library('form_validation');
		$this->form_validation->set_rules('valores', 'idsdelete', 'required|xss_clean');
		$this->form_validation->set_rules('grupos_cambiar', 'Id Grupo', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('id_group', 'Id Grupo', 'required|xss_clean|numeric');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',$this->lang->line('errormoveusergroup'));
			redirect('contacts/contact_manager');
		}
		else
		{
			if($this->input->post('grupos_cambiar'))
			{
				$uri = $this->input->post('grupos_cambiar');
			}
			if($this->input->post('grupos_cambiar') != $this->input->post('id_group'))
			{
				$valores = $this->input->post('valores');
				if(is_array($valores) and !empty($valores))
				{
					$ids = array();
					$this->load->model('contacts_model');
					for($i=0;$i<count($valores);$i++)
					{
						$result = $this->contacts_model->get_contacts_user_exist(
																					$valores[$i],
																					$this->input->post('grupos_cambiar'),
																					$this->session->userdata('user_id')
																				);
						if(empty($result))
						{
							$delete = $this->contacts_model->delete_contacts_user_exist(
																						$valores[$i],
																						$this->input->post('id_group'),
																						$this->session->userdata('user_id')
																					);
							$insert = $this->contacts_model->insert_contact_group_new(
																						$valores[$i],
																						$this->input->post('grupos_cambiar'),
																						$this->session->userdata('user_id')
																					);
						}													
					}
					$this->session->set_flashdata('exitoso',$this->lang->line('successmovegroup'));
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('errormoveusergroup'));
				}
			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('errormoveusergroup'));
			}
			redirect('contacts/contact_manager/'.$uri);
		}
	}
	
	public function delete_contact_group()
	{
		ini_set('display_errors', 'on');
		$this->session->keep_flashdata('url');
		$this->lang->load('contacts');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}
		if ($this->uri->segment(3) !== FALSE)
		{
			$id_group	= 	$this->uri->segment(3);
			$this->load->model('contacts_model');
			$result = $this->contacts_model->get_contacts_groups(	$id_group,	$this->session->userdata('user_id'));
			if(empty($result))
			{
				$this->contacts_model->delete_group(	$id_group,	$this->session->userdata('user_id'));
				$this->session->set_flashdata('exitoso',$this->lang->line('successdeletegroup'));
				redirect('contacts/contact_manager');
			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('errordeletegroup'));
				redirect('contacts/contact_manager/'.$id_group);
			}
		}
	}
	
	/*
		Funcion privada para cargar la vista de Contacta Manager
	*/
	private function _view_contact_manager($data)
	{
		$this->load->view('contact_manager',$data);
	}
	/*
		Funcion privada para cargar la vista de Preview de CSV a importar
	*/
	private function _view_csv_preview($data)
	{
		$this->load->view('csv_preview',$data);
	}
	/**
	 * Funcion privada para verificar el Login del usuario
	*/
	private function _login_in()
	{
		return $this->session->userdata('logged_in');
	}
	/***********************************************
	***********************************************
	***********************************************
	***********************************************
	***********************************************
		FUNCIONES PARA REALIZAR PETICIONES POR AJAX
	***********************************************
	***********************************************
	***********************************************
	***********************************************
	**************************/
	
	/*
		Funcion AJAX publica para traer la ciudad de un pais
	*/
	public function get_city()
	{
		$this->lang->load('contacts');
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
	
	public function get_country_contacts()
	{
		$this->lang->load('contacts');
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
		$this->form_validation->set_rules('id_contact', 'id_contact', 'required|xss_clean|numeric');
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
			$item   = array();
			$result = $this->contacts_model->get_country_contact($this->session->userdata('user_id'),$this->input->post('id_contact'));
			if(!empty($result))
			{
				$city			=	$this->contacts_model->get_city_contact($result->id, $result->indi_area);
				$item['country']= 	array('name' =>$result->name,'id'=>$result->id);
				if(!empty($city))
				{
					$item['city']	= 	array('name' =>$city->name,'id'=>$city->code);
				}
				else
				{
					$item['city']	= 	array('name' =>"",'id'=>0);
				}
				echo json_encode($item);
			}
			else
			{
				$res 	= array(
							'cod' 	=> 0,
							'messa'	=> $this->lang->line('nocountrycontact')
							);
				echo json_encode($res);
				exit();
			}
		}
	}
	public function update_name_group()
	{
		$this->lang->load('contacts');
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

		$this->load->model('contacts_model');
		$result = $this->contacts_model->update_name_group($this->input->post('id'),$this->input->post('value'),$this->session->userdata('user_id'));
		echo $this->input->post('value');
	}
	
	public function delete_all()
	{
		$this->lang->load('contacts');
		$this->session->keep_flashdata('url');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('site');
		}

		$this->load->model('contacts_model');
		$id_group = "";
		$result   = FALSE;
		if($this->uri->segment(3) !== FALSE)
		{
			$id_group	= $this->uri->segment(3);
			if(is_numeric($id_group))
			{
				$result = $this->contacts_model->delete_all_group($this->session->userdata('user_id'), $id_group);
			}
		}else{
			$result = $this->contacts_model->delete_all($this->session->userdata('user_id'));
		}
		if($result)
		{
			$this->session->set_flashdata('exitoso',$this->lang->line('deletebatchsuccess'));
		}
		else
		{
			$this->session->set_flashdata('error',$this->lang->line('errordeletecontact'));
		}
		redirect('contacts/contact_manager/'.$id_group);	
		
		
		/*if ($this->uri->segment(3) !== FALSE)
		{
			$id_group	= 	$this->uri->segment(3);
			if(is_numeric($id_group))
			{
				$this->load->model('contacts_model');
				$result = $this->contacts_model->delete_all($this->session->userdata('user_id'));
				if($result)
				{
					$this->session->set_flashdata('exitoso',$this->lang->line('successdeletegroup'));
					redirect('contacts/contact_manager');
				}
				
				if(!empty($result))
				{
					$this->contacts_model->delete_group(	$id_group,	$this->session->userdata('user_id'));
					$this->session->set_flashdata('exitoso',$this->lang->line('successdeletegroup'));
					redirect('contacts/contact_manager');
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('errordeletegroup'));
					redirect('contacts/contact_manager/'.$id_group);
				}
			}
		}*/

	}
	private function _get_credit_user()
	{
		$this->load->model('hooks_model');
		$result	= $this->hooks_model->get_user($this->session->userdata('user_id'));
		return $result->credits;
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
	
}