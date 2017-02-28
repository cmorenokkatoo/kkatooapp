<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contacts_Model extends CI_Model {

	/*
	* Funcion para listar todos los contactos de un usuario
	*/
	public function get_contacts_all($user_id = 0, $group = 0, $q = "")
	{
		$getPage = $this->input->get("page");
		$pag	=	(!empty($getPage))?($getPage == 1) ? 0 : (($getPage-1)*PAGINATION) : 0;
		try
		{
			$this->db->select('SQL_CALC_FOUND_ROWS contacts_user.*', FALSE);
			$this->db->from('contacts_user');
			if($group != 0)
			{
				$this->db->join('group_contact_user','group_contact_user.id_contact_user = contacts_user.id');
			}
			$this->db->where('contacts_user.user_id',$user_id);
			$this->db->where('contacts_user.state', 0);
			if($group != 0)
			{
				$this->db->where('group_contact_user.id_group', $group);
			}
			if($q != "")
			{
				$this->db->like('contacts_user.name', $q, 'both');
			}
			
			$this->db->order_by("contacts_user.name", "asc"); 
			$this->db->limit(PAGINATION, $pag);
			
			$result		= 	$this->db->get()->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetcontacts $e){
			log_message('debug','Error al tratar de listar los contactos del modelo de contactos');
			return FALSE;
		}
	}
	/*
	* Funcion para agregar un grupo a un usuario
	*/
	public function insert_group_user($user_id = 0, $name ="")
	{
		try
		{
			$data = array(
						   'name' 	=> $name,
						   'user_id'=> $user_id
						);
			
			$result = $this->db->insert('groups', $data); 
			return $result;
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al tratar de agregar un grupo al usuario');
			return FALSE;
		}
	}
	/*
	* Funcion para agregar el contacto de un grupo de usuario
	*/
	public function insert_contact_group_user($id_contact = 0, $id_group = 0, $user_id = 0)
	{
		try
		{
			$data = array(
						   'id_contact_user' 	=>	$id_contact,
						   'id_group'			=> 	$id_group,
						   'user_id'			=> 	$user_id
						);
			
			$result = $this->db->insert('group_contact_user', $data); 
			return $result;
		}catch(ErrorinsertcontactGroup $e){
			log_message('debug','Error al tratar de agregar un contacto a un grupo determinado');
			return FALSE;
		}
	}
	
	/*
	* Funcion para eliminar el contacto de un usuario
	*/
	public function delete_contact($user_id = 0, $id_contact = 0)
	{
		try
		{
		  	$data = array(
			               'state' => 1
			              );
			$this->db->where('id', $id_contact);
			$this->db->where('user_id', $user_id);
			$result	=	$this->db->update('contacts_user',$data);
			return $result;
		}catch(ErrorDeleteContact $e){
			log_message('debug','Error al tratar de eliminar un contacto de un usuario');
			return FALSE;
		}
}


	/*
	* Funcion para eliminar el contacto de un usuario de un grupo determinado
	*/
	public function delete_contact_group($user_id = 0, $id_contact = 0, $id_group = 0)
	{
		try
		{
			$this->db->where('user_id', $user_id);
			$this->db->where('id_contact_user', $id_contact);
			$this->db->where('id_group', $id_group);			
			$result = $this->db->delete('group_contact_user');
			return $result;
		}catch(ErrorDeleteContact $e){
			log_message('debug','Error al tratar de eliminar un contacto de un grupo');
			return FALSE;
		}
	}

	/*
	* Funcion para eliminar el contacto de un usuario
	*/
	public function batch_delete_contact($user_id = 0, $ids = array())
	{
		try
		{
		  	$data = array(
			               'state' => 1
			              );
			$this->db->where('user_id', $user_id);
			$this->db->where_in('id',$ids);
			$result	=	$this->db->update('contacts_user',$data);
			return $result;
		}catch(ErrorDeleteContact $e){
			log_message('debug','Error al tratar de eliminar un contacto de un usuario');
			return FALSE;
		}
	}


	/*
	* Funcion para eliminar varios contactos de un usuario en batch. por cantidad.
	*/
	public function batch_delete_contact_group($user_id = 0, $id_group = 0,  $ids = array())
	{
		try
		{
			$this->db->where('id_group', $id_group);
			$this->db->where('user_id', $user_id);
			$this->db->where_in('id_contact_user',$ids);
			$result	=	$this->db->delete('group_contact_user');
			return $result;
		}catch(ErrorDeleteContact $e){
			log_message('debug','Error al tratar de eliminar varios contactos de un grupo.');
			return FALSE;
		}
	}

	/*
		Cambia el prefijo de los contactos enviados, por el prefijo enviado
	*/
	public function batch_change_prefix_pais_contact($user_id = 0, $ids = 0, $prefix = ""){
		try
		{
		  	$data = array(
			               'indi_pais' => $prefix
			              );
			$this->db->where('user_id', $user_id);
			$this->db->where_in('id',$ids);
			$result	=	$this->db->update('contacts_user',$data);
			return $result;
		}catch(ErrorDeleteContact $e){
			log_message('debug','Error al cambiar el prefijo del país de un contacto de un usuario');
			return FALSE;
		}
	}
	/*
	* Funcion para listar los contactos disponibles de un usuario
	*/
	public function get_contacts_wapp($user_id = 0, $wapp = 0, $ids = array(), $group = 0, $q="")
	{
		$getPage = $this->input->get("page");
		$pag	=	(!empty($getPage))?($getPage == 1) ? 0 : (($getPage-1)*PAGINATION) : 0;
		try
		{
			$this->db->select('SQL_CALC_FOUND_ROWS contacts_user.*, contact_fields.valor, fields.name_fields, fields.tipo', false);
			$this->db->from('contacts_user');
			if($group != 0)
			{
				$this->db->join('group_contact_user','group_contact_user.id_contact_user = contacts_user.id');
			}
			$this->db->join('contact_fields', 'contacts_user.id = contact_fields.id_contact','left');
			$this->db->join('fields', 'fields.id = contact_fields.id_fields','left');
			$this->db->where('contacts_user.user_id', $user_id);
			$this->db->where('contacts_user.state', 0);
			$this->db->where('fields.id_wapp', $wapp);
			if($group != 0)
			{
				$this->db->where('group_contact_user.id_group', $group);
			}
			if(!empty($ids))
			{
				$this->db->where_in('contacts_user.id',$ids);
			}
			if($q != "")
			{
				$this->db->like('contacts_user.name', $q, 'both');
			}
			$this->db->order_by("contacts_user.id", "asc"); 
			$this->db->limit(PAGINATION, $pag);
			
			$result		= 	$this->db->get()->result();
			
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetcontacts $e){
			log_message('debug','Error al tratar de listar los contactos de un usuario');
			return FALSE;
		}
	}
	
	/*
	* Funcion para listar los contactos disponibles pero que no tienen campos de la aplicación
	*/
	public function get_contacts_no_wapp($user_id = 0, $state = FALSE ,$ids = array(), $group = 0, $q ="")
	{
		$getPage = $this->input->get("page");
		$pag	=	(!empty($getPage))?($getPage == 1) ? 0 : (($getPage-1)*PAGINATION) : 0;
		try
		{
			$this->db->select('SQL_CALC_FOUND_ROWS contacts_user.*', false);
			$this->db->from('contacts_user');
			if($group != 0)
			{
				$this->db->join('group_contact_user','group_contact_user.id_contact_user = contacts_user.id');
			}
			if($state)
			{
				$this->db->where_not_in('contacts_user.id',$ids);
			}
			$this->db->where('contacts_user.user_id',$user_id);
			$this->db->where('contacts_user.state', 0);
			if($group != 0)
			{
				$this->db->where('group_contact_user.id_group', $group);
			}
			if($q != "")
			{
				$this->db->like('contacts_user.name', $q, 'both');
			}
			$this->db->order_by("contacts_user.id", "asc"); 
			$this->db->limit(PAGINATION, $pag);
			
			$result		= 	$this->db->get()->result();
			
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetcontacts $e){
			log_message('debug','Error al tratar de listar los contactos de un usuario que no tienen campos de la aplicación');
			return FALSE;
		}
	}
	/*
	Saber si un usuario ya tiene creado el grupo de Gmail
	*/
	public function get_group_gmail($user_id)
	{
		try
		{
			$this->db->where('user_id', $user_id);
			$this->db->where('name', 'Gmail');
			$result= $this->db->get('groups')->row();
			if(empty($result))
			{
				$result = FALSE;
			}
			return $result;
		}catch(ErrroGetGroupGmail $e){
			log_message('debug','Error al tratar al tratar si un usuario ya tiene group de gmail');
			return FALSE;
		}
	}
	/*
		//Get contactos de Gmail 
	*/
	public function get_contacts_gmail($user_id = 0)
	{
		try
		{
			$this->db->select('id');
			$this->db->where('user_id', $user_id);
			$this->db->where('gmail', 1);
			$result= $this->db->get('contacts_user')->result();
			return $result;
		}catch(ErrorGetContactsGmail $e){
			log_message('debug','Error al tratar de obtener los contactos de gmail que no se han asociado');
			return FALSE;
		}
	}
		/*
	* Funcion para volver todos los contactos de gmail a su estado normal
	*/
	public function update_user_gmail($user_id = 0, $ids = array())
	{
		try
		{	
			$data = array(
							'gmail' => 0
							);
			$this->db->where('user_id', $user_id);
			//$this->db->where_in('id', $ids);
			$result	= $this->db->update('contacts_user', $data);
			return $result;
		}catch(ErrorUpdateUserGmail $e){
			log_message('debug','Error al tratar de actualizar los usuarios de gmail');
			return FALSE;
		}
	}
	/*
	* Funcion para agregar un batch para agregar usuarios al grupo de gmail
	*/
	public function insert_batch_group($data = array())
	{
		try
		{
			 $result	= $this->db->insert_batch('group_contact_user', $data);
			 return $result;
		}catch(Errorinsertbatch $e){
			log_message('debug','Error al tratar de insert batch de de grupos en contactos');
			return FALSE;
		}
	}
	
	public function update_contact($id_contact = 0, $user_id = 0, $data = array())
	{
		try
		{
			$this->db->where('user_id', $user_id);
			$this->db->where_in('id', $id_contact);
			$result	= $this->db->update('contacts_user', $data);
			return $result;
		}catch(ErrorupdateContact $e){
			log_message('debug','Error al tratar de actualizar un contacto');
			return FALSE;
		}
	}
	
	public function delete_fields_contacts($user_id = 0, $id_contact = 0, $id_wapp = 0)
	{
		try
		{
			$this->db->where('user_id', $user_id);
			$this->db->where('id_wapp', $id_wapp);
			$this->db->where('id_contact', $id_contact);
			$result	= $this->db->delete('contact_fields');
			return $result;
		}catch(DeleteFieldsContacts $e){
			log_message('debug','Error al tratar de eliminar los campos dinamicos de un usuario');
			return FALSE;
		}
	}
	
	public function insert_contact($data)
	{
		try
		{
			$result = $this->db->insert('contacts_user', $data);
			if($result)
			{
				return $this->db->insert_id();
			}
			else
			{
				return FALSE;
			}
		}catch(ErrorInsertContact $e){
			log_message('debug','Error al tratar de agregar un nuevo usuario');
			return FALSE;
		}
	}
	
	public function get_country_code_user($user_id = 0)
	{
		try
		{
			$this->db->select('country.phonecode');
			$this->db->from('user');
			$this->db->join('country', 'country.id = user.id_country');
			$this->db->where('user.id', $user_id);
			$result	=	$this->db->get()->row();
			if(!empty($result))
			{
				return $result;
			}
			return FALSE;
		}catch(ErrorGetCountryCodeUser $e){
			log_message('debug','Error al tratar de consultar el code del pais de un usuario');
			return FALSE;
		}
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
	* Funcion para traer las ciudades de un pais determinado
	*/
	public function get_city($id_country = 0)
	{
		try
		{
			$result	=	$this->db->get_where('city',array('id_country'=>$id_country))->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetCity $e){
			log_message('debug','Error al tratar de obtener una ciudad apartir de un id de pais');
			return FALSE;
		}
	}
	
	public function get_country_contact($user_id = 0, $id_contact = 0)
	{
		try
		{
			$this->db->select('country.id, country.name, contacts_user.indi_area');
			$this->db->from('contacts_user');
			$this->db->join('country','contacts_user.indi_pais = country.phonecode','left');
			$this->db->where('contacts_user.user_id', $user_id);
			$this->db->where('contacts_user.id', $id_contact);
			$result	=	$this->db->get()->row();
			if(empty($result))
			{
				$result = FALSE;
			}
			return $result;
		}catch(ErrorGetCountry $e){
			log_message('debug','Error al tratar de obtener pais de un contacto');
			return FALSE;
		}
	}
	
	public function get_city_contact($id_country = 0, $code = 0)
	{
		try
		{
			$this->db->where('id_country', $id_country);
			$this->db->where('code', $code);
			$result	=	$this->db->get('city')->row();
			if(empty($result))
			{
				$result = FALSE;
			}
			return $result;
		}catch(ErrorGetCity $e){
			log_message('debug','Error al tratar de obtener la ciudad de un contacto');
			return FALSE;
		}
	}
	
	/**
	 * Funcion para iniciar seccion como contacto de una aplicacion.
	 * @param $contact informacion de contacto
	 * @return no retorna nada.
	*/
	public function init_session_contact($contact){
		$datasession = array(
			'id_contact' => $contact['id_contact'],
			'indi_pais'  => $contact['indi_pais'],
			'indi_area'  => $contact['indi_area'],
			'phone' => $contact['phone'],
			'id_app' => $contact['id_app'],
			'subscriber' => TRUE
		);
		$this->session->set_userdata($datasession);
	}
	
	//POCHO CODIGO
	/*
	 * Trae la informacion basica de un contacto especifico
	 * @param $id_contact es el id del contacto a retornar
	*/
	public function get_contact_info($id_contact)
	{
		try
		{	
			$result = $this->db->get_where('contacts_user', array('id' => $id_contact))->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetcategory $e){
			log_message('debug','Error al tratar de traer la uri para la aplicación');
			return FALSE;
		}
	}
	
	/*
	 * Trae la informacion basica de un contacto especifico asociado a una aplicacion
	 * @param $id_contact es el id del contacto a verificar
	 * @param $id_app es el id de la aplicaicon asociada al contacto
	*/
	public function get_contact_associated_to_app($id_contact, $id_app)
	{
		try
		{	
			$this->db->select('contacts_user.*, contact_wapp.credits, contact_wapp.packages, wapp.id as id_app, wapp.uri');
			$this->db->from('contacts_user');
			$this->db->join('contact_wapp','contacts_user.id = contact_wapp.id_contact','inner');
			$this->db->join('wapp','wapp.id = contact_wapp.id_wapp','inner');
			$this->db->where('contact_wapp.state', 1);
			$this->db->where('wapp.id', $id_app);
			$this->db->where('contacts_user.id', $id_contact);
			$result	=	$this->db->get()->row();
			//print_r($this->db->last_query());

			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetcategory $e){
			log_message('debug','Error get_contact_associated_to_app');
			return FALSE;
		}
	}
	
	/**
	* Obtiene los datos de un contacto por telefono y pais
	* @param $phone nro de telefono
	* @param $country indicativo del país
	*/
	public function get_contact_exists_by_phone_and_country($phone, $country){
		try
		{	
			$result = $this->db->get_where('contacts_user', array('phone' => $phone, 'indi_pais' => $country, 'from_subscription' => 1))->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result->id;
			}
		}catch(Errorgetcategory $e){
			log_message('debug','Error get_contact_exists_by_phone_and_country');
			return FALSE;
		}
	}
	/**
	* Asocia un contacto a una aplicacion
	* @param $id_contact es el id del contacto
	* @param $id_app es el id de la aplicacion
	* @return retorna el id de la asociacion o FALSE si no se logra.
	*/
	public function associate_contact_application($id_contact, $id_app){
		try
		{
			$data = array(
						   'id_wapp' 	=> $id_app,
						   'id_contact'=> $id_contact,
						   'credits' => 0,
						   'packages' => 0,
						   'state' => 1
						);
			
			$result = $this->db->insert('contact_wapp', $data);
			return $this->db->insert_id();
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al tratar de agregar un contacto con una aplicacion => associate_contact_application');
			return FALSE;
		}
	}
	
	/**
	* Crea todos los campos dinámicos para el contacto en vacio
	* @param $id_app id de la aplicacion
	* @param $id_contact id del contacto
	* @return Array con los campos y sus valores
	*/
	
	function get_contact_app_custom_field_and_create($id_app, $id_contact){
		try
		{
			$the_return = array();
			
			$this->db->select('fields.*');
			$this->db->from('fields');
			$this->db->where('fields.id_wapp', $id_app);
			$result	= $this->db->get()->result();
			
			if($result){
				foreach($result as $res){
					$contact_fields = $this->get_field_value($res->id,  $id_contact, $id_app);
					if(empty($contact_fields)){
						$data = array(
							   'id_fields' => $res->id,
							   'id_contact'=> $id_contact,
							   'valor' => '',
							   'user_id' => '',
							   'id_wapp' => $id_app
							);
						$result = $this->db->insert('contact_fields', $data);
						$last_id = $this->db->insert_id();
						if($last_id){
							$contact_fields = $this->get_field_value($res->id,  $id_contact, $id_app);
						}
					}
					$the_return[] = (object)array('fields'=>(object)$res, 'contact_fields' => $contact_fields); //["fields"] = 
				}
				return $the_return;
			}else{
				return FALSE;
			}
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al tratar de agregar un contacto con una aplicacion => associate_contact_application');
			return FALSE;
		}
	}
	
	/**
	* Funcion que retorna la fila de información "contact_fields" por id_field, id_contact y id_app
	* @param $id_field id del campo en "fields"
	* @param $id_contact id del contacto
	* @param $id_app id de la aplicación
	& @return fila de valores de el campo retornado.
	*/
	function get_field_value($id_field, $id_contact, $id_app){
		try
		{
			$this->db->select('contact_fields.*');
			$this->db->from('contact_fields');
			$this->db->where('id_fields', $id_field);
			$this->db->where('id_contact', $id_contact);
			$this->db->where('id_wapp', $id_app);
			$result = $this->db->get()->row();
			if($result){
				return $result;
			}else{
				return FALSE;
			}	
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al tratar de traer el valor de un campo dinamico => get_field_value');
			return FALSE;
		}
	}
	
	/**
	* Función para traer los campos dinámicos asociados a un contacto y una aplicación.
	* @param $id_app id de la aplicación
	* @param $id_contact id del contacto
	* @return Array con los campos dinamicos y sus valores.
	*/
	
	function get_contact_app_custom_field($user_id = 0, $id_app, $id_contact){
		try
		{
			$this->db->select('fields.*, contact_fields.*');
			$this->db->from('contact_fields');
			$this->db->join('fields','fields.id_wapp = contact_fields.app_id','inner');
			$this->db->where('contact_fields.user_id', $user_id);
			$this->db->where('contact_fields.id_contact', $id_contact);
			$this->db->where('contact_fields.app_id', $id_app);
			$result	=	$this->db->get()->row();
			//print_r($this->db->last_query());

			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al traer los campos dinamicos de una aplicación y un contacto => get_contact_app_custom_field');
			return FALSE;
		}
	}
	
	/**
	* Calcula el precio unitario de un contacto
	* @param $coutry indicativo del país
	* @param $city indicativo de la ciudad
	* @param $phone nro de teléfono
	* @param $id_app id de la aplicación
	*/
	function get_price_by_contact($id_contact){
		try
		{
			$this->load->model('apps_model');
			$con = $this->get_contact_info($id_contact);
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
				return $price->valor;
			}else{
				return FALSE;
			}
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al tratar de traer el precio unitario para un contacto => get_price_by_contact');
			return FALSE;
		}
	}
	
	/**
	* Actualiza los datos del contacto al momento por suscripción
	* @param $contact_id id del contacto
	* @param $data datos del contacto
	* @return Array de resultado si guarda, o FALSE si no guarda o hay un error
	*/
	function update_contact_suscription($contact_id=0, $data=array()){
		try
		{
			if($contact_id > 0){
				$this->db->where_in('id', $contact_id);
				$result	= $this->db->update('contacts_user', $data);
				return $result;
			}else{
				return FALSE;
			}
		}catch(ErrorupdateContact $e){
			log_message('debug','Error al tratar de actualizar un contacto');
			return FALSE;
		}
	}
	
	function get_contact_credit_and_packages($id_contact, $id_wapp){
		try
		{	
			$this->db->select('contact_wapp.credits, contact_wapp.packages');
			$this->db->from('contact_wapp');
			$this->db->where('contact_wapp.state', 1);
			$this->db->where('contact_wapp.id_contact', $id_contact);
			$this->db->where('contact_wapp.id_wapp', $id_wapp);
			$result	=	$this->db->get()->row();
			//print_r($this->db->last_query());

			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetcategory $e){
			log_message('debug','Error get_contact_associated_to_app');
			return FALSE;
		}
	}
	
	function get_contacts_groups($id_group = 0, $user_id = 0)
	{
		try
		{	
			$this->db->where('id_group', $id_group);
			$this->db->where('group_contact_user.user_id', $user_id);
			$this->db->join('contacts_user', 'contacts_user.id = group_contact_user.id_contact_user');
			$this->db->where('contacts_user.state', 0);
			$result	=	$this->db->get('group_contact_user')->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorgetContacts_group $e){
			log_message('debug','Error get_contact_associated_to_app');
			return FALSE;
		}
	}
	
	function delete_group($id_group = 0, $user_id = 0)
	{
		try
		{	
			$this->db->where('id_group', $id_group);
			$this->db->delete('group_contact_user');
			
			$this->db->where('id', $id_group);
			$this->db->delete('groups');
			
			return TRUE;
		}catch(ErrorDeleteGroup $e){
			log_message('debug','Error get_contact_associated_to_app');
			return FALSE;
		}
	}
	
	public function get_contacts_user_exist($id_contact	=0, $id_group = 0, $user_id = 0)
	{
		try
		{	
			$this->db->where('id_contact_user', $id_contact);
			$this->db->where('id_group', $id_group);
			$this->db->where('user_id', $user_id);
			$result	=	$this->db->get('group_contact_user')->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorgetContactsExistGroup $e){
			log_message('debug','Error get_contact_associated_to_app');
			return FALSE;
		}
	}
	
	public function insert_contact_group_new($id_contact	=0, $id_group = 0, $user_id = 0)
	{
		try
		{	
			$data 	=	array(
								'id_contact_user'	=> $id_contact,
								'id_group'			=> $id_group,
								'user_id'			=> $user_id
								);
			$result	=	$this->db->insert('group_contact_user', $data);
			return TRUE;
		}catch(ErrorInsertGroupnew $e){
			log_message('debug','Error get_contact_associated_to_app');
			return FALSE;
		}
	}
	
	public function delete_contacts_user_exist($id_contact	=0, $id_group = 0, $user_id = 0)
	{
		try
		{	
			$this->db->where('id_contact_user', $id_contact);
			$this->db->where('id_group', $id_group);
			$this->db->where('user_id', $user_id);
			$result	=	$this->db->delete('group_contact_user');
			return TRUE;
		}catch(ErrorDeleteContactsExistGroup $e){
			log_message('debug','Error get_contact_associated_to_app');
			return FALSE;
		}
	}
	
	public function update_name_group($id_group = 0, $name ="", $user_id=0)
	{
		try
		{
		  	$data = array(
			               'name' => $name
			              );
			$this->db->where('id', $id_group);
			$this->db->where('user_id', $user_id);
			$result	=	$this->db->update('groups',$data);
			return $result;
		}catch(ErrorUpdateGroup $e){
			log_message('debug','Error al tratar de eliminar un contacto de un usuario');
			return FALSE;
		}
		
	}
	
	/*
	* Función que elimina todos los contactos de un usuario.
	*/
	public function delete_all($user_id = 0)
	{
		try
		{
		  	$data = array(
			               'state' => 1
			              );
			//$this->db->where('id', $id_group);
			$this->db->where('user_id', $user_id);
			$result	=	$this->db->update('contacts_user',$data);
			return TRUE;
		}catch(ErrorUpdateGroup $e){
			log_message('debug','Error al tratar de eliminar un contacto de un usuario');
			return FALSE;
		}
	}

	/*
	* Función que elimina todos los contactos de un grupo.
	*/
	public function delete_all_group($user_id = 0, $id_group = 0)
	{
		try
		{
			$this->db->where('user_id', $user_id);
			$this->db->where('id_group', $id_group);
			$result	=	$this->db->delete('group_contact_user');
			return $result;
		}catch(ErrorUpdateGroup $e){
			log_message('debug','Error al tratar de eliminar todos los contactos de un grupo.');
			return FALSE;
		}
	}

	//FOUND ROWS FROM LAS QUERY
	public function foundRows(){
		$result =	$this->db->query('SELECT FOUND_ROWS() as cuantos');
		return $result->row();
 	}

 	/* API */
 	public function insert_id_contact_campaign($id_campaign, $id_contact_user, $user_id){
 		$id_contact_user = 344938;
 		$data = array(
 			'id_campaign' => $id_campaign,
 			'id_contact_user' => $id_contact_user,
 			'user_id' => $user_id
 			);
 		$this->db->insert('contacts_campaign', $data);
			if($this->db->affected_rows() > 0)
			{
				return $this->db->insert_id();
			}
			return FALSE;
	}

	/*
	* Funcion para obtener los datos del id_contact_campaign 
	*/	
	public function get_id_contact_campaign($id_campaign, $user_id)
	{
		try
		{
			$this->db->select('contacts_campaign.id');
			$this->db->from('contacts_campaign');
			$this->db->where('id_campaign', $id_campaign);
			$this->db->where('user_id', $user_id);
			$this->db->where('id_contact_user', 344938);
			$result = $this->db->get()->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetCampaign $e){
			log_message('debug','Error al tratar de listar información de la campaign de un usuario');
			return FALSE;
		}
	}

	public function get_id_contact()
	{
		try
		{
			$this->db->select('contacts_user.id');
			$this->db->from('contacts_user');
			$result = $this->db->get()->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetCampaign $e){
			log_message('debug','Error al tratar de listar información de la campaign de un usuario');
			return FALSE;
		}
	}

}