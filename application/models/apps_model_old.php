<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apps_Model extends CI_Model {

	/**
	* Funcion para obtener el id de una aplicacion en base al URI
	*/
	public function get_uri_app($uri_app = NULL)
	{
		try
		{
			if($uri_app !== NULL)
			{
				$result= $this->db->get_where('wapp', array('uri' => $uri_app))->row();
			}
			
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
	/** verifica si la aplicacion es tipo, "difusion" o "suscripcion" 
		@param $uri_id es la uri de la aplicacion o el id.
		@return $tipo de aplicacion 0 = "Super aplicacion", 1 = "Suscripcion", 2 = "Difusion"
	*/
	public function get_aplication_type($uri_id)
	{
		try
		{	
			if(is_string($uri_id)){
				$result = $this->db->get_where('wapp', array('uri' => $uri_id))->row();
			}else{
				$result = $this->db->get_where('wapp', array('id' => $uri_id))->row();
			}
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result->tipo;
			}
		}catch(Errorgetcategory $e){
			log_message('debug','Error al tratar de traer la uri para la aplicación');
			return FALSE;
		}
	}
	/**
	* Funcion para listar las categorias disponibles de aplicaciones
	*/
	public function get_category()
	{
		try
		{
			$result = $this->db->get('category')->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetcategory $e){
			log_message('debug','Error al tratar de traer todas las categorias para el marketplace');
			return FALSE;
		}
	}
	/*
	* Funcion obtener los contactos ya asociados a una campaña
	*/	
	public function get_contact_campaign($campaign, $user_id, $paginate = false)
	{
		try
		{
			if($paginate):	
				$getPage = $this->input->get_post("page");
				$pag	=	(!empty($getPage))?($getPage == 1) ? 0 : (($getPage-1)*PAGINATION) : 0;
				$this->db->select('SQL_CALC_FOUND_ROWS contacts_campaign.id AS id_campaign, contacts_user.*', false);
			else:
				$this->db->select('contacts_campaign.id AS id_campaign, contacts_user.*');
			endif;
			
			
			$this->db->from('contacts_user');
			$this->db->join('contacts_campaign', 'contacts_user.id = contacts_campaign.id_contact_user');
			$this->db->where('contacts_campaign.id_campaign', $campaign);
			$this->db->where('contacts_campaign.user_id', $user_id);
			
			if($paginate) $this->db->limit(PAGINATION, $pag);
			
			$result = $this->db->get()->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetContactCampaign $e){
			log_message('debug','Error al tratar de traer los contactos que asoció a una campaña');
			return FALSE;
		}
	}
	/*
	* Funcion para iniciar una campaña para un usuario en una app 
	*/	
	public function get_campaign($user_id, $wapp)
	{
		try
		{
			$this->db->where('id_wapp', $wapp);
			$this->db->where('user_id', $user_id);
			$this->db->where('state', 0);
			$result = $this->db->get('campaign')->row();
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
	/*
	* Funcion para iniciar una campaña para un usuario en una app 
	*/
	public function ini_campaign($data)
	{
		try
		{
			$result	= $this->db->insert('campaign', $data);
			if($result)
			{
				return $this->db->insert_id();  
			}
			else
			{
				return FALSE;
			}
		}catch(Errorinicampigninicial $e){
			log_message('debug','Error al tratar de iniciar la campaña de usuario');
			return FALSE;
		}
	}
	/*
	* Funcion para agregar un batch de usuario de gmail
	*/
	public function insert_batch_contact($data)
	{
		try
		{
			 $result	= $this->db->insert_batch('contacts_user', $data);
			 return $result;
		}catch(Errorinsertbatch $e){
			log_message('debug','Error al tratar de insert batch de contactos');
			return FALSE;
		}
	}
	/*
	* Funcion para agregar un contacto a la lista de contactos de un usuario
	*/
	public function insert_contact($data)
	{
		try
		{
			$result = $this->db->insert('contacts_user', $data);
			return $this->db->insert_id();
		}catch(ErrorInsertContact $e){
			log_message('debug','Error al tratar de ingresar un contacto a la lista de contactos de un usuario');
			return FALSE;
		}
	}
	/*
	* Funcion para listar los contactos disponibles de un usuario
	*/
	public function get_contacts_wapp($user_id = 0, $wapp = 0, $ids = array())
	{
		try
		{
			if($wapp != 0)
			{
				$this->db->select('contacts_user.*, contact_fields.valor, fields.name_fields, fields.tipo');
			}
			else
			{
				$this->db->select('contacts_user.*');
			}
			
			$this->db->from('contacts_user');
			if($wapp != 0)
			{	
				$this->db->join('contact_fields', 'contacts_user.id = contact_fields.id_contact','left');
				$this->db->join('fields', 'fields.id = contact_fields.id_fields','left');
			}
			$this->db->where('contacts_user.user_id', $user_id);
			$this->db->where('contacts_user.state', 0);
			$this->db->where('contacts_user.indi_pais !=', "");
			$this->db->where('contacts_user.phone !=', "");
			if($wapp != 0)
			{	
				$this->db->where('fields.id_wapp', $wapp);
			}
			if(!empty($ids))
			{
				$this->db->where_in('contacts_user.id',$ids);
			}
			$this->db->order_by("contacts_user.name", "asc"); 
					
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
	* Funcion para listar los contactos disponibles de un usuario
	*/
	public function get_contacts_wapp_subscribe($wapp = 0, $ids = array())
	{
		try
		{
			if($wapp != 0)
			{
				$this->db->select('contacts_user.*, contact_fields.valor, fields.name_fields, fields.tipo, contact_wapp.credits, contact_wapp.packages');
			}
			else
			{
				$this->db->select('contacts_user.*');
			}
			
			$this->db->from('contacts_user');
			if($wapp != 0)
			{	
				$this->db->join('contact_fields', 'contacts_user.id = contact_fields.id_contact','left');
				$this->db->join('fields', 'fields.id = contact_fields.id_fields','left');
			}
			$this->db->join('contact_wapp', 'contacts_user.id = contact_wapp.id_contact');//,'left');
			$this->db->where('contact_wapp.id_wapp', $wapp);
			$this->db->where('contact_wapp.state', 1);
			$this->db->where('contacts_user.indi_pais !=', "");
			$this->db->where('contacts_user.phone !=', "");
			if($wapp != 0)
			{	
				$this->db->where('fields.id_wapp', $wapp);
			}
			if(!empty($ids))
			{
				$this->db->where_in('contacts_user.id',$ids);
			}
			$this->db->order_by("contacts_user.name", "asc"); 
			
			$result		= 	$this->db->get()->result();
			//echo $this->db->last_query();
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
	* Funcion para listar los contactos disponibles pero con los filtros elegidos por el usuario pero del grupo solamente
	*/
	public function get_contacts_wapp_filter_group($user_id = 0 ,$group = 0)
	{
		try
		{
			$this->db->select('contacts_user.id');
			$this->db->from('contacts_user');
			if($group != 0)
			{
				$this->db->join('group_contact_user','group_contact_user.id_contact_user = contacts_user.id');
			}
			$this->db->where('contacts_user.user_id', $user_id);
			$this->db->where('contacts_user.state', 0);
			$this->db->where('contacts_user.indi_pais !=', "");
			$this->db->where('contacts_user.phone !=', "");
			if($group != 0)
			{
				$this->db->where('group_contact_user.id_group', $group);
			}
			$this->db->order_by("contacts_user.id", "asc"); 
			//$this->db->limit(10);
			$result		= 	$this->db->get()->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorgetcontactsFilterGroup $e){
			log_message('debug','Error al tratar de listar los contactos de un usuario con filtro');
			return FALSE;
		}
	}
	
			/*
	* Funcion para listar los contactos disponibles pero con los filtros elegidos por el usuario pero del grupo solamente
	* para aplicación de subscripción
	*/
	public function get_contacts_wapp_filter_group_subscribe($wapp = 0 ,$group = 0)
	{
		try
		{
			$this->db->select('contacts_user.id');
			$this->db->from('contacts_user');
			if($group != 0)
			{
				$this->db->join('group_contact_wapp','group_contact_wapp.id_contact = contacts_user.id');
			}
			$this->db->join('contact_wapp', 'contacts_user.id = contact_wapp.id_contact');//,'left');
			$this->db->where('contact_wapp.id_wapp', $wapp);
			$this->db->where('contact_wapp.state', 1);
			$this->db->where('contacts_user.indi_pais !=', "");
			$this->db->where('contacts_user.phone !=', "");
			if($group != 0)
			{
				$this->db->where('group_contact_wapp.id_group', $group);
			}
			$this->db->order_by("contacts_user.id", "asc"); 
			//$this->db->limit(10);
			$result		= 	$this->db->get()->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorgetcontactsFilterGroup $e){
			log_message('debug','Error al tratar de listar los contactos de un usuario con filtro');
			return FALSE;
		}
	}
	
	/*
	* Funcion para listar los contactos disponibles pero con los filtros elegidos por el usuario
	*/
	public function get_contacts_wapp_filter($user_id = 0, $wapp = 0,$group = 0, $id_field = 0, $ope = "", $criterio = "")
	{
		try
		{
			$tipo = $this->get_app_type($wapp);
			$this->db->select('contacts_user.id');
			$this->db->from('contacts_user');
			if($group != 0 && $tipo != 1){
				$this->db->join('group_contact_user','group_contact_user.id_contact_user = contacts_user.id');
			}else if($group != 0 && $tipo == 1){
				$this->db->join('group_contact_wapp','group_contact_wapp.id_contact = contacts_user.id');
			}
			
			if($tipo == 1){
				$this->db->join('contact_wapp', 'contacts_user.id = contact_wapp.id_contact');//,'left');
				$this->db->where('contact_wapp.id_wapp', $wapp);
				$this->db->where('contact_wapp.state', 1);				
			}else{
				$this->db->where('contacts_user.user_id', $user_id);
				$this->db->where('contacts_user.state', 0);
			}
			
			$this->db->where('contacts_user.indi_pais !=', "");
			$this->db->where('contacts_user.phone !=', "");
			if($group != 0 && $tipo != 1){
				$this->db->where('group_contact_user.id_group', $group);
			}else if($group != 0 && $tipo == 1){
				$this->db->where('group_contact_wapp.id_group', $group);
			}
			if($id_field != 0 and $criterio!="")
			{
				if($id_field == 999999 && $tipo == 1){
					$real_ope = $ope;
					if($ope == "<>") $real_ope == '!=';
					
					$this->db->where("contact_wapp.credits ".$real_ope." '".$criterio."'",NULL,FALSE);
				}else{
					$this->db->where('contact_fields.id_fields', $id_field);
					
					$real_ope = $ope;
					if($ope == "<>") $real_ope == '!=';
					if(is_numeric($criterio)){
						$this->db->where("CONVERT(contact_fields.valor,UNSIGNED INTEGER) ".$real_ope." '".$criterio."'",NULL,FALSE);
					}else if($this->checkDate($criterio, 'Y-m-d')){
						$this->db->where("STR_TO_DATE(contact_fields.valor, '%Y-%m-%d') ".$real_ope." '".$criterio."'",NULL,FALSE);
					}else if(is_string($criterio)){
						$this->db->where("contact_fields.valor ".$real_ope." '".$criterio."'");
					}
					
				}
					
				/*switch($ope)
				{
					case "=":
							$this->db->where("((fields.tipo=1 AND CONVERT(contact_fields.valor,UNSIGNED INTEGER) = '".$criterio."') OR
												(fields.tipo=2 AND contact_fields.valor = '".$criterio."') OR
												(fields.tipo=3 AND STR_TO_DATE(contact_fields.valor, '%Y-%m-%d') = '".$criterio."'))",
											 NULL, 
											 FALSE
											);
						break;
					case ">":
							$this->db->where("((fields.tipo=1 AND CONVERT(contact_fields.valor,UNSIGNED INTEGER) > '".$criterio."') OR
												(fields.tipo=2 AND contact_fields.valor > '".$criterio."') OR
												(fields.tipo=3 AND STR_TO_DATE(contact_fields.valor, '%Y-%m-%d') > '".$criterio."'))",
											 NULL, 
											 FALSE
											);
						break;
					case "<":
							$this->db->where("((fields.tipo=1 AND CONVERT(contact_fields.valor,UNSIGNED INTEGER) < '".$criterio."') OR
												(fields.tipo=2 AND contact_fields.valor < '".$criterio."') OR
												(fields.tipo=3 AND STR_TO_DATE(contact_fields.valor, '%Y-%m-%d') < '".$criterio."'))",
											 NULL, 
											 FALSE
											);
						break;
					case "<>":
						$this->db->where("((fields.tipo=1 AND CONVERT(contact_fields.valor,UNSIGNED INTEGER) != '".$criterio."') OR
												(fields.tipo=2 AND contact_fields.valor !=  '".$criterio."') OR
												(fields.tipo=3 AND STR_TO_DATE(contact_fields.valor, '%Y-%m-%d') != '".$criterio."'))",
											 NULL, 
											 FALSE
											);
						break;
				}*/
			}
			if($id_field != 999999){
				$this->db->where('fields.id_wapp', $wapp);
				$this->db->join('contact_fields', 'contacts_user.id = contact_fields.id_contact','left');
				$this->db->join('fields', 'fields.id = contact_fields.id_fields','left');
			}
			
			$this->db->order_by("contacts_user.id", "asc"); 
			//$this->db->limit(10);
			$result		= 	$this->db->get()->result();
			//echo $this->db->last_query();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorgetcontactsFilter $e){
			log_message('debug','Error al tratar de listar los contactos de un usuario con filtro');
			return FALSE;
		}
	}
	/*
	* Funcion para listar los contactos disponibles pero que no tienen campos de la aplicación
	*/
	public function get_contacts_no_wapp($user_id = 0, $state ,$ids = array())
	{
		try
		{
			$this->db->select('*');
			if($state)
			{
				$this->db->where_not_in('id',$ids);
			}
			$this->db->where('user_id',$user_id);
			$this->db->where('state', 0);
			$this->db->where('indi_pais !=', "");
			$this->db->where('phone !=', "");
			$this->db->order_by("contacts_user.name", "asc"); 
			
			
			$result		= 	$this->db->get('contacts_user')->result();
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
	* Funcion para listar los contactos disponibles pero que no tienen campos de la aplicación
	*/
	public function get_contacts_no_wapp_subscribe($wapp = 0, $state ,$ids = array())
	{
		
		try
		{
			$this->db->select('contacts_user.*, contact_wapp.credits, contact_wapp.packages');
			$this->db->from('contacts_user');
			if($state)
			{
				$this->db->where_not_in('contacts_user.id',$ids);
			}
			$this->db->join('contact_wapp', 'contacts_user.id = contact_wapp.id_contact');//,'left');
			$this->db->where('contact_wapp.id_wapp', $wapp);
			$this->db->where('contact_wapp.state', 1);
			$this->db->where('indi_pais !=', "");
			$this->db->where('phone !=', "");
			$this->db->order_by("contacts_user.name", "asc");
			
			
			$result		= 	$this->db->get()->result();
			//echo $this->db->last_query();
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
	* Funcion para listar los contactos disponibles pero que no tienen campos de la aplicación
	*/
	public function get_contacts_where_in($user_id = 0 ,$ids = array())
	{
		
		try
		{
			$this->db->select('*');
			$this->db->where('user_id',$user_id);
			$this->db->where('state', 0);
			$this->db->where('indi_pais !=', "");
			$this->db->where('phone !=', "");
			$this->db->where_in('id',$ids);
			$this->db->order_by("contacts_user.name", "asc"); 
			
			
			
			$result		= 	$this->db->get('contacts_user')->result();
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
	* Funcion para listar los contactos disponibles pero que no tienen campos de la aplicación por suscripción
	*/
	public function get_contacts_where_in_subscribe($wapp = 0 ,$ids = array())
	{
		
		try
		{
			$this->db->select('contacts_user.*, contact_wapp.credits, contact_wapp.packages');
			$this->db->from('contacts_user');
			$this->db->join('contact_wapp', 'contacts_user.id = contact_wapp.id_contact');//,'left');
			$this->db->where('contact_wapp.id_wapp', $wapp);
			$this->db->where('contact_wapp.state', 1);
			$this->db->where('indi_pais !=', "");
			$this->db->where('phone !=', "");
			$this->db->where_in('id',$ids);
			$this->db->order_by("contacts_user.name", "asc"); 
			
			
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
	* Funcion para listar los contactos disponibles de un usuario para una determinada campañaa
	*/
	public function get_contacts_filter_campaign_wapp($user_id=0, $campaign=0,$wapp =0,$ids = array())
	{
		
		try
		{
			$this->db->select('contacts_user.*, contact_fields.valor, fields.name_fields, fields.tipo');
			$this->db->from('contacts_user');
			$this->db->join('contact_fields', 'contacts_user.id = contact_fields.id_contact','left');
			$this->db->join('fields', 'fields.id = contact_fields.id_fields','left');
			$this->db->where('contacts_user.id NOT IN (select id_contact_user from contacts_campaign
											where id_campaign = '.$campaign.' and user_id='.$user_id.')', NULL, FALSE);
			$this->db->where('contacts_user.user_id', $user_id);
			$this->db->where('contacts_user.state', 0);
			$this->db->where('contacts_user.indi_pais !=', "");
			$this->db->where('contacts_user.phone !=', "");
			$this->db->where('fields.id_wapp', $wapp);
			if(!empty($ids))
			{
				$this->db->where_in('contacts_user.id',$ids);
			}
			$this->db->order_by("contacts_user.id", "asc"); 
			
			
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
			log_message('debug','Error al tratar de listar los contactos no asociados a una campaña determinada');
			return FALSE;
		}
	}
	
	/*
	* Funcion para listar los contactos disponibles de una aplicación para una determinada campañaa
	*/
	public function get_contacts_filter_campaign_wapp_subscribe($campaign=0,$wapp =0,$ids = array())
	{

		try
		{
			$this->db->select('contacts_user.*, contact_fields.valor, fields.name_fields, fields.tipo, contact_wapp.credits, contact_wapp.packages');
			$this->db->from('contacts_user');
			$this->db->join('contact_wapp', 'contacts_user.id = contact_wapp.id_contact');//,'left');
			$this->db->where('contact_wapp.id_wapp', $wapp);
			$this->db->where('contact_wapp.state', 1);
			$this->db->join('contact_fields', 'contacts_user.id = contact_fields.id_contact','left');
			$this->db->join('fields', 'fields.id = contact_fields.id_fields','left');
			$this->db->where('contacts_user.id NOT IN (select id_contact_user from contacts_campaign
											where id_campaign = '.$campaign.')', NULL, FALSE);
			$this->db->where('contacts_user.indi_pais !=', "");
			$this->db->where('contacts_user.phone !=', "");
			$this->db->where('fields.id_wapp', $wapp);
			if(!empty($ids))
			{
				$this->db->where_in('contacts_user.id',$ids);
			}
			$this->db->order_by("contacts_user.id", "asc"); 
			
			
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
			log_message('debug','Error al tratar de listar los contactos no asociados a una campaña determinada');
			return FALSE;
		}
	}
		/*
	* Funcion para listar los contactos disponibles de un usuario para una determinada campaña pero con filtros
	*/
	
	public function get_mega_filtro_contacts($user_id = 0, $id_campaign = 0, $id_wapp = 0, $id_group = 0, $id_field = 0, $ope = "", $criterio = "", $dopage = true){
		
		if($dopage){
			$getPage = $this->input->get_post("page");
			$pag	=	(!empty($getPage))?($getPage == 1) ? 0 : (($getPage-1)*PAGINATION) : 0;
		}
		
		try{
			$tipo = $this->get_app_type($id_wapp);
			
			//REVISO QUE TIPO DE APLICACIÓN ES PARA PODER CREAR EL SELECT
			if($tipo == 1){
				$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS contacts_user.*, contact_fields.id as field, contact_wapp.credits, contact_wapp.packages', FALSE);
			}else{
				$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS contacts_user.*, contact_fields.id as field', FALSE);
			}
			
			//FROM STATEMENT 
			$this->db->from('contacts_user');
			
			//FILTRAR POR CAMPOS DINÁMICOS
			$this->db->join('contact_fields', 'contacts_user.id = contact_fields.id_contact AND contact_fields.id_wapp = '.$id_wapp, 'left');
			if($tipo == 1) 
				$this->db->join('contact_wapp', 'contacts_user.id = contact_wapp.id_contact');
				
			//LOS JOINS DE GRUPOS 
			if(!empty($id_group) && $tipo !=1)
			{
				$this->db->join('group_contact_user','group_contact_user.id_contact_user = contacts_user.id');
			}else if(!empty($id_group) && $tipo == 1){
				$this->db->join('group_contact_wapp','group_contact_wapp.id_contact = contacts_user.id');
			}
			
			//CREAMOS LOS FILTROS NATURALES PARA TRAER LOS CONTACTOS
			$this->db->where('contacts_user.indi_pais !=', "");
			$this->db->where('contacts_user.phone !=', "");
			
			//FUERA LOS QUE YA ESTÁN EN LA CAMPAÑA
			if(!empty($id_campaign)){
				$this->db->where('contacts_user.id NOT IN (select id_contact_user from contacts_campaign
											where id_campaign = '.$id_campaign.')', NULL, FALSE);
			}
			
			//SE DISTRIBUYE EL WHERE STATEMENT DEPENDIENTO DE QUE TIPO DE APLICACIÓN ES.
			if($tipo == 1){
				$this->db->where('contact_wapp.id_wapp', $id_wapp);
				$this->db->where('contact_wapp.state', 1);				
			}else{
				$this->db->where('contacts_user.user_id', $user_id);
				$this->db->where('contacts_user.state', 0);
			}
			
			//REALIZAMOS EL FILTRO POR GRUPOS DEPENDIENDO EL TIPO DE APLICACIÓN
			if(!empty($id_group) && $tipo != 1)
			{
				$this->db->where('group_contact_user.id_group', $id_group);
			}else if(!empty($id_group) && $tipo == 1){
				$this->db->where('group_contact_wapp.id_group', $id_group);
			}
			
			//REALIZAMOS FILTRO POR ID_FIELD SI EXISTE Y CRITERIO.
			
			if(!empty($id_field)){
				if(!empty($criterio)){
					
					//MIRAMOS SI EL FILTRO ES POR CREDITOS.
					if($id_field == 999999 && $tipo == 1){
						
						$real_ope = $ope;
						if($ope == "<>") $real_ope = '!=';
						$this->db->where("contact_wapp.credits ".$real_ope." '".$criterio."'",NULL,FALSE);
					
					}else{
						if($id_field == "name_contact")
						{
							$this->db->where("contacts_user.name  LIKE '%" . $criterio . "%'");
						}
						else if($id_field == "phone_contact")
						{
							$this->db->where("contacts_user.phone  LIKE '%" . $criterio . "%'");
						}
						else
						{
							//ACÁ NOS DAMOS CUENTA QUE EL FILTRO ES UN CAMPO DINÁMICO
							$this->db->where('contact_fields.id_fields', $id_field);
							
							$real_ope = $ope;
							if($ope == "<>") $real_ope == '!=';
							if(is_numeric($criterio)){
								$this->db->where("CONVERT(contact_fields.valor,UNSIGNED INTEGER) ".$real_ope." '".$criterio."'",NULL,FALSE);
							}else if($this->checkDate($criterio, 'Y-m-d')){
								$this->db->where("STR_TO_DATE(contact_fields.valor, '%Y-%m-%d') ".$real_ope." '".$criterio."'",NULL,FALSE);
							}else if(is_string($criterio)){
								$this->db->where("contact_fields.valor ".$real_ope." '".$criterio."'");
							}
						}
					}
				}
			}
			
			$this->db->group_by('contacts_user.id');
			$this->db->order_by('(field IS NOT NULL)', 'desc');
			$this->db->order_by("contacts_user.date_created", "desc");
			
			if($dopage) $this->db->limit(PAGINATION, $pag);
			
			$result		= 	$this->db->get()->result();
			/*
			echo $this->db->last_query();
			die();*/
			
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
			
		}catch(Exception $e){
			log_message('debug','Error en funcion get_mega_filtro_contacts modelo apps_model');
			return FALSE;
		}
	}
	
	/*
	Función para filtrar un array de contactos con los ya seleccionados en la campaña
	*/
	public function get_contacts_where_in_campaign($user_id = 0, $id_campaign=0, $ids=array()){
		try{
			$this->db->select('id_contact_user');
			$this->db->from('contacts_campaign');
			$this->db->where('user_id', $user_id);
			$this->db->where('id_campaign', $id_campaign);
			$this->db->where_in('id_contact_user', $ids);
			$result	= $this->db->get()->result();
			
			return (!empty($result))?$result:array();
		}catch(Exception $e){
			log_message('debug','Error en funcion get_contacts_where_in_campaign modelo apps_model');
			return FALSE;
		}
	}
		
	
	public function get_contacts_filter_campaign_wapp_filter($user_id, $campaign, $wapp, $group = 0, $id_field = 0, $ope = "", $criterio = "")
	{
		try
		{	
			$tipo = $this->get_app_type($wapp);
			
			if($tipo == 1){
				$this->db->select('contacts_user.*, contact_fields.valor, fields.name_fields, fields.tipo, contact_wapp.credits, contact_wapp.packages');
			}else{
				$this->db->select('contacts_user.*, contact_fields.valor, fields.name_fields, fields.tipo');
			}
			
			$this->db->from('contacts_user');
			if($group != 0 && $tipo !=1)
			{
				$this->db->join('group_contact_user','group_contact_user.id_contact_user = contacts_user.id');
			}else if($group!=0 && $tipo == 1){
				$this->db->join('group_contact_wapp','group_contact_wapp.id_contact = contacts_user.id');
			}
			
			$this->db->where('contacts_user.id NOT IN (select id_contact_user from contacts_campaign
											where id_campaign = '.$campaign.')', NULL, FALSE);
			if($tipo == 1){
				$this->db->join('contact_wapp', 'contacts_user.id = contact_wapp.id_contact');//,'left');
				$this->db->where('contact_wapp.id_wapp', $wapp);
				$this->db->where('contact_wapp.state', 1);				
			}else{
				$this->db->where('contacts_user.user_id', $user_id);
				$this->db->where('contacts_user.state', 0);
			}
				
			
			$this->db->where('contacts_user.indi_pais !=', "");
			$this->db->where('contacts_user.phone !=', "");
			
			
			if($group != 0 && $tipo !=1)
			{
				$this->db->where('group_contact_user.id_group', $group);
			}else if($group!=0 && $tipo == 1){
				$this->db->where('group_contact_wapp.id_group', $group);
			}
			
			if($id_field != 0 and $criterio!="")
			{
				if($id_field == 999999 && $tipo == 1){
					$real_ope = $ope;
					if($ope == "<>") $real_ope == '!=';
					
					$this->db->where("contact_wapp.credits ".$real_ope." '".$criterio."'",NULL,FALSE);
				}else{
					
					$this->db->where('contact_fields.id_fields', $id_field);
					
					$real_ope = $ope;
					if($ope == "<>") $real_ope == '!=';
					if(is_numeric($criterio)){
						$this->db->where("CONVERT(contact_fields.valor,UNSIGNED INTEGER) ".$real_ope." '".$criterio."'",NULL,FALSE);
					}else if($this->checkDate($criterio, 'Y-m-d')){
						$this->db->where("STR_TO_DATE(contact_fields.valor, '%Y-%m-%d') ".$real_ope." '".$criterio."'",NULL,FALSE);
					}else if(is_string($criterio)){
						$this->db->where("contact_fields.valor ".$real_ope." '".$criterio."'");
					}
				}
					
				/*switch($ope)
				{
					case "=":
							$this->db->where("((fields.tipo=1 AND CONVERT(contact_fields.valor,UNSIGNED INTEGER) = '".$criterio."') OR
												(fields.tipo=2 AND contact_fields.valor = '".$criterio."') OR
												(fields.tipo=3 AND STR_TO_DATE(contact_fields.valor, '%Y-%m-%d') = '".$criterio."'))",
											 NULL, 
											 FALSE
											);
						break;
					case ">":
							$this->db->where("((fields.tipo=1 AND CONVERT(contact_fields.valor,UNSIGNED INTEGER) > '".$criterio."') OR
												(fields.tipo=2 AND contact_fields.valor > '".$criterio."') OR
												(fields.tipo=3 AND STR_TO_DATE(contact_fields.valor, '%Y-%m-%d') > '".$criterio."'))",
											 NULL, 
											 FALSE
											);
						break;
					case "<":
							$this->db->where("((fields.tipo=1 AND CONVERT(contact_fields.valor,UNSIGNED INTEGER) < '".$criterio."') OR
												(fields.tipo=2 AND contact_fields.valor < '".$criterio."') OR
												(fields.tipo=3 AND STR_TO_DATE(contact_fields.valor, '%Y-%m-%d') < '".$criterio."'))",
											 NULL, 
											 FALSE
											);
						break;
					case "<>":
							$real_ope = '!=';
							$this->db->where("((fields.tipo=1 AND CONVERT(contact_fields.valor,UNSIGNED INTEGER) != '".$criterio."') OR
												(fields.tipo=2 AND contact_fields.valor !='".$criterio."') OR
												(fields.tipo=3 AND STR_TO_DATE(contact_fields.valor, '%Y-%m-%d') !='".$criterio."'))",
											 NULL, 
											 FALSE
											);
						break;
				}*/
			}
			
			if($id_field != 999999){
				$this->db->where('fields.id_wapp', $wapp);
				$this->db->join('contact_fields', 'contacts_user.id = contact_fields.id_contact','left');
				$this->db->join('fields', 'fields.id = contact_fields.id_fields','left');
			}
			
			$this->db->order_by("contacts_user.id", "asc");
			$result		= 	$this->db->get()->result();
			
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorgetcontactsFilter $e){
			log_message('debug','Error al tratar de listar los contactos no asociados a una campaña determinada pero con filtros');
			return FALSE;
		}
	}
	
	/**
	*	verifica si es una fecha.
	*/
	function checkDate($data, $format) {
		function is_date( $data ){ 
			// date_default_timezone_set('Africa/Casablanca');
			$stamp = strtotime( $data ); 
			if (!is_numeric($stamp)) 
				return FALSE; 
			$month = date( 'm', $stamp ); 
			$day   = date( 'd', $stamp ); 
			$year  = date( 'Y', $stamp ); 
			if (checkdate($month, $day, $year)) 
				return TRUE; 
			return FALSE; 
		}
	}
		/*
	* Funcion para listar los contactos disponibles de un usuario para una determinada campañaa
	*/
	public function get_contacts_filter_campaign_no_wapp($user_id = 0, $state,$campaign,$ids = array())
	{

		try
		{
			$this->db->select('*');
			if($state)
			{
				$this->db->where_not_in('id',$ids);
			}
			$this->db->where('id NOT IN (select id_contact_user from contacts_campaign
											where id_campaign = '.$campaign.' and user_id='.$user_id.')', NULL, FALSE);
			$this->db->where('user_id', $user_id);
			$this->db->where('state', 0);
			$this->db->where('indi_pais !=', "");
			$this->db->where('phone !=', "");
			$this->db->order_by("contacts_user.id", "asc"); 
			
			
			$result		= 	$this->db->get('contacts_user')->result();
			
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetcontacts $e){
			log_message('debug','Error al tratar de listar los contactos no asociados a una campaña determinada');
			return FALSE;
		}
	}
	
		/*
	* Funcion para listar los contactos disponibles de una aplicación para una determinada campañaa
	*/
	public function get_contacts_filter_campaign_no_wapp_subscribe($wapp,$state,$campaign,$ids = array())
	{
		
		try
		{
			$this->db->select('contacts_user.*, contact_wapp.credits, contact_wapp.packages');
			$this->db->from('contacts_user');
			if($state)
			{
				$this->db->where_not_in('contacts_user.id',$ids);
			}
			$this->db->where('contacts_user.id NOT IN (select id_contact_user from contacts_campaign
											where id_campaign = '.$campaign.')', NULL, FALSE);
			$this->db->join('contact_wapp', 'contacts_user.id = contact_wapp.id_contact');//,'left');
			$this->db->where('contact_wapp.id_wapp', $wapp);
			$this->db->where('contact_wapp.state', 1);
			$this->db->where('indi_pais !=', "");
			$this->db->where('phone !=', "");
			$this->db->order_by("contacts_user.id", "asc");
			
			
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
			log_message('debug','Error al tratar de listar los contactos no asociados a una campaña determinada');
			return FALSE;
		}
	}
	
	/*
	* Funcion para obtener si una campaaña tiene usuarios asociados
	*/
	public function get_campaign_contacts($campaign)
	{
		try
		{
			$this->db->where('id_campaign', $campaign);
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$result		= 	$this->db->get('contacts_campaign')->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetcontacts $e){
			log_message('debug','Error al tratar de averiguar si una campaña tenia usuarios asociados');
			return FALSE;
		}
	}
	/*
	* Funcion para obtener información de un contacto en especifico de un usuario para saber si tiene la información necesaria
	*/
	public function get_contact_by_user($contact=0)//, $user_id=0)
	{
		try
		{
			$this->db->where('id', $contact);
			//$this->db->where('user_id', $user_id);
			$this->db->where('indi_pais !=','');
			//$this->db->where('indi_area !=','');
			$this->db->where('phone 	!=','');
			$result		= 	$this->db->get('contacts_user')->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;	
			}
		}catch(ErrorGetContactByUser $e){
			log_message('debug','Error al tratar de obtener el contacto de un usuario');
			return FALSE;
		}
	}
	/*
	* Funcion para comprobar si un contacto ya fue asignado a una campaña
	*/
	public function get_contact_campaign_user($contact, $campaign)
	{
		try
		{
			$this->db->where('id_campaign', $campaign);
			$this->db->where('id_contact_user', $contact);
			$result		= 	$this->db->get('contacts_campaign')->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetCampaignUser $e){
			log_message('debug','Error al tratar de obtener si un usuario esta ya en una campaña');
			return FALSE;
		}
	}
	/*
	* Funcion para ingresar un contacto a una campaña
	*/
	public function insert_contact_campaign($data)
	{
		try
		{
			$result	= $this->db->insert('contacts_campaign', $data);
			return $result;
		}catch(Errorinsertcontactcampaign $e){
			log_message('debug','Error al tratar de ingresar un contacto a una campaña especifica');
			return FALSE;
		}
	}
		/*
	* Funcion para eliminar un contacto de una campaña
	*/
	public function delete_contact_campaign($campaign, $contact)//, $user_id)
	{
		try
		{
			$this->db->where('id_campaign', $campaign);
			$this->db->where('id_contact_user', $contact);
			//$this->db->where('user_id', $user_id);
			$result	=	$this->db->delete('contacts_campaign');
			return $result;
		}catch(ErrordeleteContactCampaign $e){
			log_message('debug','Error al tratar de eliminar un contacto de una campaña');
			return FALSE;
		}
	}
	/*
	* Funcion para traer todos los audios de una app
	*/
	public function get_audio_app_deprecated($user_id=0, $id = 0, $pos = 0)
	{
		try
		{
			
			$result = 	$this->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM 
													(SELECT DISTINCT `audio`.* 
																FROM (`audio`) JOIN `audio_app` ON `audio`.`id` = `audio_app`.`id_audio`
																WHERE `audio_app`.`app_id` = '".$id."' 
															UNION 
													SELECT `audio`.* FROM (`audio`) WHERE `audio`.`user_id` = '".$user_id."'
													) AS audio 
										 LIMIT ".$pos.",3")->result();	
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetAudioApp $e){
			log_message('debug','Error al tratar de obtener todos los audios de una app');
			return FALSE;
		}
	}
	
	/*
	* Funcion para traer todos los audios de una app
	*/
	public function get_audio_app($user_id=0, $id = 0, $pos = 0)
	{
		try
		{
			
			$result = 	$this->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM 
													(SELECT DISTINCT `audio`.* 
																FROM (`audio`) JOIN `content_wapp` ON `audio`.`id` = `content_wapp`.`id_content`
																WHERE `content_wapp`.`id_wapp` = '".$id."' 
																and `content_wapp`.`tipo` = 'audio'
															UNION 
													SELECT `audio`.* FROM (`audio`) WHERE `audio`.`user_id` = '".$user_id."'
													) AS audio 
										 LIMIT ".$pos.",3")->result();	
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetAudioApp $e){
			log_message('debug','Error al tratar de obtener todos los audios de una app');
			return FALSE;
		}

	}
	
	public function total_audio_app()
	{
		try
		{
			$result = 	$this->db->query('SELECT FOUND_ROWS() as total')->row();	
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorTotalAudio $e){
			log_message('debug','Error al tratar de obtener todos los audios de una app');
			return FALSE;
		}
	}
	/*
	* Funcion para traer todos el audio de una campaña
	*/
	public function get_audio_campaign($user_id, $campaign)
	{
		try
		{
			$this->db->select('audio.*');
			$this->db->from('audio');
			$this->db->join('campaign', 'audio.id = campaign.id_audio');
			$this->db->where('campaign.user_id', $user_id);
			$this->db->where('campaign.id', $campaign);
			$this->db->where('campaign.state', 0);
			$result		= 	$this->db->get()->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetAudioCampaign $e){
			log_message('debug','Error al tratar de obtener todos los audios de una campaña');
			return FALSE;
		}
	}
	/*
	* Funcion para traer el texto de una campaña para text_speech
	*/
	public function get_text_campaign($user_id,$campaign)
	{
		try
		{
			$this->db->where('user_id', $user_id);
			$this->db->where('id', $campaign);
			$this->db->where('text_speech !=', "");
			$result		= 	$this->db->get('campaign')->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetTextCampaign $e){
			log_message('debug','Error al tratar de obtener todos los textos de una campaña');
			return FALSE;
		}
	}
	/*
	* Funcion para agregar audio a una campaña de un usuario
	*/
	public function insert_audio_campaign($user_id  = 0, $id_campaign = 0,$audio = 0, $mensaje)
	{
		try
		{
			$data = array(
							'id_audio' 		=> $audio,
							'text_speech'	=> "",
							'id_text_speech'=> 0,
							'marcado'		=> $mensaje
							);
			$this->db->where('id', $id_campaign);
			$this->db->where('user_id', $user_id);
			$this->db->where('state', 0);
			$result	=	$this->db->update('campaign',$data);
			return $result;
		}catch(ErrorInsertaudioCampaign $e){
			log_message('debug','Error al tratar de agregar un audio a la campaña');
			return FALSE;
		}
	}
	/*
	* Funcion para agregar audio a una campaña de un usuario
	*/
	public function insert_text_speech_campaign($user_id = 0, $id_campaign = 0,$text ="", $id_text = 0, $voice = 0, $mensaje)
	{
		try
		{
			$data = array(
							'id_audio' 		=> 0,
							'id_text_speech'=> $id_text,
							'text_speech'	=> $text,
							'voice'			=> $voice,
							'marcado'		=> $mensaje
							);
			$this->db->where('id', $id_campaign);
			$this->db->where('user_id', $user_id);
			$this->db->where('state', 0);
			$result	=	$this->db->update('campaign',$data);
			return $result;
		}catch(ErrorInserttextspeech $e){
			log_message('debug','Error al tratar de agregar un audio a la campaña');
			return FALSE;
		}
	}
	/*
	* Funcion para agregar audio al banco de audios
	*/
	public function insert_audio($data)
	{
		try
		{
			$result	=	$this->db->insert('audio',$data);
			return $this->db->insert_id();
		}catch(ErrorAddAudio $e){
			log_message('debug','Error al tratar de agregar un audio al banco de audios');
			return FALSE;
		}
	}
	/*
	* Funcion para saber un usuario ya tiene audio o texto disponible para llamar
	*/
	public function get_audio_text_campaign($user_id, $id_campaign)
	{
		try
		{
			$this->db->where('id', $id_campaign);
			$this->db->where('user_id', $user_id);
			$this->db->where('state', 0);
			$this->db->where('id_audio', 0);
			$this->db->where('text_speech', "");
			$result	=	$this->db->get('campaign')->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(GetAudioTextCampaign $e){
			log_message('debug','Error al tratar de saber si un usuario ya tiene audio o texto de una campaña');
			return FALSE;	
		}
	}
	/*
	* Funcion para saber la hora y fecha de llamadas de un usuario
	*/
	public function get_date_campaign($user_id, $id_campaign)
	{
		try
		{
			$this->db->where('id', $id_campaign);
			$this->db->where('user_id', $user_id);
			$this->db->where('state', 0);
			$result	=	$this->db->get('campaign')->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(GetDateCampaign $e){
			log_message('debug','Error al tratar de traer la fecha y hora de una llamada');
			return FALSE;	
		}
	}
		/*
	* Funcion para agregar una fecha a una campaña
	*/
	public function insert_date_campaign($user_id, $campaign, $fecha, $hora, $minu, $gmt, $tiposms)
	{
		try
		{
			$data = array(
							'fecha' => $fecha,
							'hora'	=> $hora,
							'minuto'=> $minu,
							'gmt'	=> $gmt,
							'state' => 1,
							'tipo_sms' => $tiposms
							);
			$this->db->where('id', $campaign);
			$this->db->where('user_id', $user_id);
			$result	=	$this->db->update('campaign',$data);
			return $result;
		}catch(ErrorInserttextspeech $e){
			log_message('debug','Error al tratar de agregar fecha a una campaña');
			return FALSE;
		}
	}
			/*
	* Funcion para agregar una fecha a una campaña
	*/
	public function get_user_verified($user_id = 0, $verified = 0)
	{
		try
		{
			$this->db->where('id', $user_id);
			$this->db->where('verified', $verified);
			$result	=	$this->db->get('user')->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetuserverified $e){
			log_message('debug','Error al tratar de verificar si un usuario a confirmado el correo');
			return FALSE;
		}
	}
				/*
	* Funcion para saber si una campaña esta lista para despachar
	*/
	public function campaign_ready($user_id, $campaign)
	{
		try
		{
			$where = "user_id=".$user_id." AND id=".$campaign." AND (id_audio!='0' OR text_speech!='')";
			$this->db->where($where, NULL, FALSE);
			$result	=	$this->db->get('campaign')->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorcampaignready $e){
			log_message('debug','Error al verificar una campaña esta ready');
			return FALSE;
		}
	}
	/*
	* Funcion para traer toda la información de un audio
	*/
	public function get_audio($id)
	{
		try
		{
			$this->db->where('id', $id);
			$result	=	$this->db->get('audio')->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetaudio $e){
			log_message('debug','Error al traer toda la información sobre un audio');
			return FALSE;
		}
	}
	/*
	* Funcion para agregar un batch colas
	*/
	public function insert_batch_queues($data)
	{
		try
		{
			 $result	= $this->db->insert_batch('queues', $data);
			 return $result;
		}catch(Errorinsertbatchqueues $e){
			log_message('debug','Error al tratar de insert batch de queues');
			return FALSE;
		}
	}
	/*
	* Funcion para obtener todos los paises disponibles
	*/
	public function get_country()
	{
		try
		{
			$result	=	$this->db->get('country')->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetCountry $e){
			log_message('debug','Error al traer los paises');
			return FALSE;
		}
	}
	/*
	* Funcion para obtener todos los grupos de un usuario
	*/
	public function get_group($user_id)
	{
		try
		{
			$result	=	$this->db->get_where('groups',array('user_id'=>$user_id))->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetCountry $e){
			log_message('debug','Error al traer los grupos de un usuario');
			return FALSE;
		}
	}
	/*************************
		FASE II KKATOO
	**************************/
	/*
		Funcion para obtener los campos de una aplicación
	*/
	public function get_fields($wapp)
	{
		try
		{
			$this->db->where('id_wapp',$wapp);
			$this->db->order_by("id", "asc"); 
			$result	=	$this->db->get('fields')->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetFields $e){
			log_message('debug','Error al tratar de obtener los campos de una aplicación');
			return FALSE;
		}
	}
	/*
		Funcion para obtener el id de la aplicación de una campaña
	*/
	public function get_id_wapp($wapp)
	{
		try
		{
			$result	=	$this->db->get_where('campaign',array('id'=>$wapp))->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetWapp $e){
			log_message('debug','Error al tratar de obtener el id de aplicacion de una campaña');
			return FALSE;
		}
	}
	/*
	* Funcion para traer las ciudades de un pais determinado
	*/
	public function get_city($id_country)
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
	/*
	* Funcion para agregar el nombre a una campaña
	*/
	public function add_name_campaign($id, $name)
	{
		try
		{
			$data 	= 	array(
								'name' => $name
								);
			$this->db->where('id',$id);
			$result	=	$this->db->update('campaign',$data);
			return $result;
		}catch(ErrorAddNameCampaign $e){
			log_message('debug','Error al tratar de agregar un nombre a la campaña');
			return FALSE;
		}
	}
	/*
	* Funcion para traer el codigo de un pais determinado
	*/
	public function get_country_id($id)
	{
		try
		{
			$result= $this->db->get_where('country', array('id' => $id))->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetCountryId $e){
			log_message('debug','Error al tratar de traer un pais por un id');
			return FALSE;
		}
	}
	
	/*
	* Funcion para agregar un batch campos dinamicos de un usuario
	*/
	public function insert_batch_contacts_fields($data)
	{
		try
		{
			 $result	= $this->db->insert_batch('contact_fields', $data);
			 return $result;
		}catch(Errorinsertbatch $e){
			log_message('debug','Error al tratar de insert batch de contactos');
			return FALSE;
		}
	}
	
	/*
	* Funcion para agregar un batch de usuario de gmail
	*/
	public function insert_batch_contacts_campaign($data)
	{
		try
		{
			 $result	= $this->db->insert_batch('contacts_campaign', $data);
			 return $result;
		}catch(Errorinsertbatch $e){
			log_message('debug','Error al tratar de insert batch de contactos');
			return FALSE;
		}
	}
	
	public function get_voice()
	{
		try
		{
			 $result	= $this->db->get('voice')->result();
			 return $result;
		}catch(ErrorGetVoice $e){
			log_message('debug','Error al tratar de obtener las voces para las llamadas');
			return FALSE;
		}
	}
	/*
	* Funcion para obtener el wapp id
	*/
	public function get_campaign_wapp($user_id, $id_campaign)
	{
		try
		{
			$this->db->where('id', $id_campaign);
			$this->db->where('user_id', $user_id);
			$this->db->where('state', 0);
			$result	=	$this->db->get('campaign')->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(GetDateCampaign $e){
			log_message('debug','Error al tratar de traer la fecha y hora de una llamada');
			return FALSE;	
		}
	}
	
	/*
	* Funcion para agregar audio a una campaña de un usuario
	*/
	public function update_marcado_campaign($user_id  = 0, $id_campaign = 0, $marcado)
	{
		try
		{
			$data = array(
							'marcado'		=> $marcado
							);
			$this->db->where('id', $id_campaign);
			$this->db->where('user_id', $user_id);
			$this->db->where('state', 0);
			$result	=	$this->db->update('campaign',$data);
			return $result;
		}catch(ErrorUpdateMarcadoCampaign $e){
			log_message('debug','Error al tratar de actualizar marcado de campaign');
			return FALSE;
		}
	}
	
		/*
	* Funcion para optener el precio por nro de teléfono de un contacto.
	*/
	public function get_price_contact($number = 0)
	{
		try
		{
			$result = 	$this->db->query("select * from price where '".$number."' RLIKE CONCAT('^',prefix) order by prefix desc limit 1")->row();	
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				/*$this->load->model('payment_model');
				$dollar_today = $this->payment_model->get_current_dollar_to_cop();
				$result->valor = $result->valor * $dollar_today;*/
				return $result;
			}
		}catch(ErrorGetPrinceContact $e){
			log_message('debug','Error al tratar de saber el precio de una llamada');
			return FALSE;
		}
	}
	
	public function get_voice_id($id_voice = 0)
	{
		try
		{
			 $result	= $this->db->get_where('voice',array('id' => $id_voice))->row();
			 return $result;
		}catch(ErrorGetVoiceOne $e){
			log_message('debug','Error al tratar de obtener una voz para la llamada');
			return FALSE;
		}
	}
	
	/*
	* Funcion obtener los contactos ya asociados a una campaña
	*/	
	public function get_value_fields($fields = "", $id_contact = 0 ,$user_id = 0)
	{
		try
		{
			$this->db->select('contact_fields.valor, fields.tipo');
			$this->db->from('fields');
			$this->db->join('contact_fields', 'contact_fields.id_fields = fields.id');
			$this->db->where('fields.name_fields', $fields);
			$this->db->where('contact_fields.id_contact', $id_contact);
			$this->db->where('contact_fields.user_id', $user_id);
			$result = $this->db->get()->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(ErrorGetValueFields $e){
			log_message('debug','Error al tratar de obtener el value de un usuario');
			return FALSE;
		}
	}
	
	public function delete_all_campaign($user_id = 0, $id_campaign = 0)
	{
		try
		{
			$this->db->where('user_id', $user_id);
			$this->db->where('id_campaign', $id_campaign);
			$result = $this->db->delete('contacts_campaign');
			return $this->db->affected_rows();
		}catch(ErrorDeleteALlCampaign $e){
			log_message('debug','Error al tratar de eliminar un usuario');
			return FALSE;
		}
	}
	
	public function get_wapp_price($id_campaign = 0)
	{
		try
		{
			$this->db->select('wapp.price');
			$this->db->from('campaign');
			$this->db->join('wapp', 'wapp.id = campaign.id_wapp');
			$this->db->where('campaign.id', $id_campaign);
			$result = $this->db->get()->row();
			if(!empty($result))
			{
				return $result;
			}
			return FALSE;
		}catch(ErrorDeleteALlCampaign $e){
			log_message('debug','Error al tratar de obtener información de una app');
			return FALSE;
		}
	}
	
	//POCHO CODIGO
	/**
	* Trae la uri de una aplicción
	* @param $id_app es el id de la aplicación.
	* @return String con la uri de la aplicación
	*/
	public function get_app_uri($id_app){
		try
		{
			$this->db->select('wapp.uri');
			$this->db->from('wapp');
			$this->db->where('id', $id_app);
			$result = $this->db->get()->row()->uri;
			if($result){
				return $result;
			}else{
				return FALSE;
			}	
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al tratar de traer la uri de una aplicación => get_app_uri');
			return FALSE;
		}
	}
	
	/**
	* Trae el procentaje a ganarse de una aplicción
	* @param $id_app es el id de la aplicación.
	* @return Nro del porcentaje a ganarse
	*/
	public function get_app_price($id_app){
		try
		{
			$this->db->select('wapp.price');
			$this->db->from('wapp');
			$this->db->where('id', $id_app);
			$result = $this->db->get()->row()->price;
			if($result){
				return $result;
			}else{
				return FALSE;
			}	
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al tratar de traer el porcentaje a ganarse de una app => get_app_price');
			return FALSE;
		}
	}
	
	
	/**
	* Trae el procentaje a ganarse de una aplicción
	* @param $id_app es el id de la aplicación.
	* @return Nro del porcentaje a ganarse
	*/
	public function get_app_time($id_app){
		try
		{
			$this->db->select('wapp.time_subscription');
			$this->db->from('wapp');
			$this->db->where('id', $id_app);
			$result = $this->db->get()->row()->time_subscription;
			if($result){
				return $result;
			}else{
				return FALSE;
			}	
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al tratar de traer el porcentaje a ganarse de una app => get_app_time');
			return FALSE;
		}
	}
	
	/**
	* Trae los datos de paquetes permitidos para la compra en las aplicaciones de suscripción
	* @param $id_app es el id de la aplicación que tiene los paquetes asociados.
	* @return un Array de objetos con cada uno de los paquetes.
	*/
	public function load_packages_suscription_data($id_app){
		try
		{
			$this->db->select('*');
			$this->db->from('package_suscription');
			$this->db->where('id_app', $id_app);
			$result = $this->db->get()->result();
			if($result){
				return $result;
			}else{
				return FALSE;
			}	
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al tratar de traer los paquetes de una aplicación => load_packages_suscription_data');
			return FALSE;
		}
	}
	
	/**
	* Optiene el nro del paquete por id de paquete en suscripción
	* @param id_package id del paquete
	* @return Cantidad del paquete
	*/
	public function get_nro_by_package($id_package){
		try
		{
			$this->db->select('*');
			$this->db->from('package_suscription');
			$this->db->where('id', $id_package);
			$result = $this->db->get()->row()->amount;
			if($result){
				return $result;
			}else{
				return FALSE;
			}	
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al tratar de traer los paquetes de una aplicación => load_packages_suscription_data');
			return FALSE;
		}
	}
	/*Fórmula para precios en el Landing*/
	/**
	* function genera precio del paquete seleccionado
	* @param $id_package id del paquete
	* @param $id_app id de la aplicación
	* @param $id_contact id del contacto
	* @return $precio_paquete_completo
	*/ 
	public function create_price_by_package_suscription($id_package, $id_app, $id_contact){
		try
		{
			$this->load->model('contacts_model');
			if($this->check_package_app($id_package, $id_app)){
				
				$percent = $this->get_app_price($id_app);//Valor otorgado por publisher en el wizard (Valor por minuto)
				$price_call_kkatoo = $this->contacts_model->get_price_by_contact($id_contact);//Comisión del código de país según el indicativo
				$amount = $this->get_nro_by_package($id_package);//$amount = paquete de llamadas
				$time_subscription = $this->get_app_time($id_app);//Por defecto es 2 - se cambia en la BD

				if($percent != FALSE && $price_call_kkatoo != FALSE && $amount != FALSE && $time_subscription != FALSE){
					
					$price_to_suscriptor = $percent; //1900 es el cambio a Dolar actual de Kkatoo
					$price_to_pay = $price_to_suscriptor * $amount ;	
					
					// if($price_to_pay < MINIMUN){
					// 	$price_to_pay = MINIMUN;
					// }
					
					return number_format($price_to_pay, 4);	
				}else{
					return FALSE;
				}
			}else{
				return FALSE;
			}
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al tratar de crear el precio por paquete en una aplicación => create_price_by_package_suscription');
			return FALSE;
		}
		
	}
	
	/**
	* function verifica si el paquete esta asociado a una aplicación
	* @param $id_package id del paquete
	* @param $id_app id de la aplicación
	* @retun TRUE o FALSE dependiendo si esta o no asociado
	*/
	
	public function check_package_app($id_package, $id_app){
		try
		{
			$this->db->select('*');
			$this->db->from('package_suscription');
			$this->db->where('id_app', $id_app);
			$this->db->where('id', $id_package);
			$result = $this->db->get()->row();
			if($result){
				return TRUE;
			}else{
				return FALSE;
			}	
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al tratar de buscar la asociación de un paquete con una aplicación => check_package_app');
			return FALSE;
		}
	}
	/**
	* Optiene el tipo de aplicación por id
	* @param $id_app id de la aplicación
	* @return valor del tipo de aplicación 0 = super genral, 1 = suscripción o 2 = por difusión.
	*/
	function get_app_type($id_app = 0){
		try
		{
			$this->db->select('*');
			$this->db->from('wapp');
			$this->db->where('id', $id_app);
			$result = $this->db->get()->row()->tipo;
			if($result){
				return $result;
			}else{
				return FALSE;
			}	
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al tratar de traer el tipo de una aplicación => get_app_type');
			return FALSE;
		}
	}
	
	/**
	* Optiene el tipo de aplicación por id
	* @param $id_app id de la aplicación
	* @return valor del tipo de aplicación 0 = super genral, 1 = suscripción o 2 = por difusión.
	*/
	function get_app_type_if_can_use($id_app = 0){
		try
		{
			$this->db->select('*');
			$this->db->from('wapp');
			$this->db->where('id', $id_app);
			$this->db->where('aproved', 1);
			$result = $this->db->get()->row();
			if($result){
				return $result->tipo;
			}else{
				return -1;
			}	
		}catch(ErrorinsertGroup $e){
			log_message('debug','Error al tratar de traer el tipo de una aplicación => get_app_type');
			return -1;
		}
	}
	
	/**
	* Optiene los datos de id de la aplicación y tipo de la aplicación con el id de la campana
	* @param $id_campaign id de la campana
	* @return objeto con los datos antes descritos.
	*/
	public function get_app_data_by_id_campaing($id_campaign=0){
		try
		{
			$this->db->select('wapp.id as id_wapp, wapp.tipo as tipo_wapp'); 
			$this->db->join('campaign', 'campaign.id_wapp = wapp.id');
			$this->db->where('campaign.id',$id_campaign);
			$result	=	$this->db->get('wapp')->row();
			
			if(!empty($result))
			{
				return $result2;
			}
			return FALSE;
		}catch(ErrorGetPrice $e){
			return FALSE;
		}
	}
	
	/**
	*	Verifica si la aplicación es de la persona logueada
	*/
	function check_app_user($id_app='', $id_user=''){
		try
		{
			$this->db->select('*'); 
			$this->db->where('id',$id_app);
			$this->db->where('user_id',$id_user);
			$result	= $this->db->get('wapp')->row();
			if(!empty($result))
			{
				return TRUE;
			}
			return FALSE;
		}catch(ErrorGetPrice $e){
			log_message('debug','Error al tratar de verificar la aplicación => check_app_user');
			return FALSE;
		}
	}
	
	/**
	* Trae la información de la aplicación por id
	* @param $id_wapp id de la aplicación
	*/
	
	function get_app_data_by_id($id_wapp){
		try
		{
			/*$this->db->select('*'); 
			$this->db->where('id',$id_wapp);
			$result	= $this->db->get('wapp')->row();*/
			$result = $this->db->get_where('wapp', array('id' => $id_wapp))->row();
			if(!empty($result))
			{
				return $result;
			}
			return FALSE;
		}catch(ErrorGetPrice $e){
			log_message('debug','Error al traer la información de una aplicación => get_app_data_by_id');
			return FALSE;
		}
	}
	
	/**
	* Trae la información de la aplicación por id de el usuario dueño
	* @param $id_user id del usuario
	*/
	
	function get_app_data_by_user_id($id_user){
		try
		{
			/*$this->db->select('*'); 
			$this->db->where('id',$id_wapp);
			$result	= $this->db->get('wapp')->row();*/
			$result = $this->db->get_where('wapp', array('user_id' => $id_user))->result();
			if(!empty($result))
			{
				return $result;
			}
			return FALSE;
		}catch(ErrorGetPrice $e){
			log_message('debug','Error al traer la información de una aplicación => get_app_data_by_id');
			return FALSE;
		}
	}
	
	/**
	* Obtener los permisos de una aplicación por us id.
	* @param $id_wapp es el id de la aplicación
	*/
	
	function create_permisions_list($id_wapp = 0){
		try
		{
			$this->db->select('*'); 
			$permission	= $this->db->get('permission')->result();
			if(!empty($permission))
			{
				$the_permisions = array();
				foreach($permission as $per){
					$result = $this->db->get_where('permission_wapp', array('id_wapp' => $id_wapp, 'id_permission' => $per->id))->row();
					$flag = 0;
					if(!empty($result->state)) $flag = $result->state;	
					$the_permisions[$per->name] = ($flag == 1)?TRUE:FALSE;
				}
				$the_permisions['id_wapp'] = $id_wapp;
				$this->permissions->set($the_permisions);
			}
			return FALSE;
		}catch(ErrorGetPrice $e){
			log_message('debug','Error al tratar de verificar la aplicación => check_app_user');
			return FALSE;
		}
	}
	
	/**
	* Crea los datos de una aplicación en la cookie
	*/
	function create_special_app_list($id_wapp){
				$result = $this->db->get_where('wapp', array('id' => $id_wapp))->row();
				if($result){
					$vars = get_object_vars($result);
					if(is_array($vars)){
						foreach($vars as $key => $val){
							$this->specialapp->set($key, $vars[$key]);
						}
					}
				}
			
	}
	
	/**
	* Trae los datos de el usuario creador de una aplicación por el id de esta
	* @param $id_wapp id de la aplicación
	*/
	function get_user_data_by_app($id_wapp=0){
		try
		{
			$this->db->select('user.*'); 
			$this->db->from('user');
			$this->db->join('wapp', 'wapp.user_id = user.id');
			$this->db->where('wapp.id',$id_wapp);
			$result	= $this->db->get()->row();
			if(!empty($result))
			{
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al tratar de traer los datos de un usuario por id de la apliación => get_user_data_by_app');
			return FALSE;
		}
	}
	
	/**Verifica si un usuario esta asociado a una aplicación con pines**/
	
	function check_user_exits_pines($id_user = '', $id_wapp = ''){
		try
		{
			$this->db->where('id_user', $id_user);
			$this->db->where('id_wapp', $id_wapp);
			$this->db->where('state', 1);
			if($this->db->get('user_wapp')->row()){
				return TRUE;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al tratar de consultar a un usuario asociado a una aplicación por difusión con pines');
			return FALSE;
		}
	}
	
		/**Optiene los creditos de un usuario asociado a una aplicación**/
	
	function get_user_app_credits($id_user = '', $id_wapp = ''){
		try
		{
			$this->db->select('credits');
			$this->db->where('id_user', $id_user);
			$this->db->where('id_wapp', $id_wapp);
			$result = $this->db->get('user_wapp')->row();
			if($result){
				return $result->credits;
			}else{
				return 0;
			}
		}catch(Exception $e){
			log_message('debug','Error al tratar de consultar a un usuario asociado a una aplicación por difusión con pines');
			return FALSE;
		}
	}
	
	/**
	* Grabar Intro o Cierre
	* @param $data son los datos a almacenar
	* @param $id_campaign es el id de la campaña
	* @param $id_wapp es el id de la aplicación
	* @param $id_user es el id del usuario
	*/
	function save_intro_close($id_campaign='', $id_wapp='', $id_user='', $data=array()){
		try
		{
			$this->db->select('*');
			$this->db->where('id_campana', $id_campaign);
			$this->db->where('id_wapp', $id_wapp);
			$this->db->where('id_user', $id_user);
			$this->db->where('tipo_contenido', $data["tipo_contenido"]);
			$value = $this->db->get('intro_cierre')->row();
			if(!empty($value)){
				$file = $value->record_file;
				$id   = $value->id;
				$this->db->where('id', $id);
				$result	= $this->db->update('intro_cierre',$data);
				if($result){
					try{
						unlink('./public/audios/intro_cierre/'.$file);	
					}catch(Exception $e){
						
					}
					return $id;
				}else{
					return FALSE;
				}
			}else{
				$result	= $this->db->insert('intro_cierre', $data);
				if($result)
				{
					return $this->db->insert_id();  
				}else{
					return FALSE;
				}
			}
		}catch(Exception $e){
			log_message('debug','Error en la función save_intro_close');
			return FALSE;
		}
	}
	
	/**
	* Update intro campaign
	* @param $id_user es el id del usuario
	* @param $id_campaign es el id de la campaña
	* @param $id_intro_close es el id del recurso intro o close
	* @param $type es el tipo si es intro o cierre
	* @param $message es el mensaje de marcado
	*/
	function update_intro_close_campaign($id_user = '', $id_campaign='', $id_intro_close='', $type='', $messaje=''){
		try{			
			$data = array();
			$data["marcado"]=$messaje;
			if($type == "intro"){
				$data["intro"]=$id_intro_close;
			}else{
				$data["cierre"]=$id_intro_close;
			}
			$this->db->where('id', $id_campaign);
			$this->db->where('user_id', $id_user);
			$this->db->where('state', 0);
			$result	=	$this->db->update('campaign',$data);
			return $result;
		}catch(Exception $e){
			log_message('debug','Error en la función update_intro_close_campaign');
			return FALSE;
		}
	}
	
	/**
	* Optiene el intro o el cierre por el id de este
	* @param $id es el id del objeto a retornar
	*/
	function get_intro_close($id=''){
		try{			
			$this->db->where('id', $id);
			$result	= $this->db->get('intro_cierre')->row();
			return $result;
		}catch(Exception $e){
			log_message('debug','Error en la función update_intro_close_campaign');
			return FALSE;
		}
	}
	
	/**
	* Optiene las aplicaciones recientes organizadas por fecha, aprobadas o no aprobadas
	* @param $state es el estado para buscar, si aprobadas o no aprobadas, si no se envia se traen todas.
	*/
	
	function get_recent_apps($state=FALSE){
		try{
			if($state === 1 || $state === 0) $this->db->where('aproved', $state);
			$this->db->order_by('created', 'desc');
			$result = $this->db->get('wapp')->result();
			if($result){
				return $result;
			}else{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error en la función update_intro_close_campaign');
			return FALSE;
		}
	}
	
	//FOUND ROWS FROM LAS QUERY
	public function foundRows(){
		$result =	$this->db->query('SELECT FOUND_ROWS() as cuantos');
		return $result->row();
 	}
 	
 	public function getWappPrice( $id = 0)
 	{
 		$this->db->select('price');
	 	$this->db->where('id', $id);
	 	$result	=	$this->db->get('wapp');
	 	if($result->num_rows() > 0)
	 	{
		 	return $result->row();
	 	}
	 	return FALSE;
 	}	

 	/* Function: deleteApp
	 * Elimina una app identificada por su Id
	 *    
	 *
	 * Parameter:
	 *  	$appId - Int Id de la app  que se eliminará.
	 *		$user_id - Int Id del usuario propietario de la app
	 * 	Return:
	 *	  TRUE - La app se borró correctamente.
	 *    FALSE - Bool Cuando ocurrió un error.
	 *  
	 */
	public function deleteApp($appId, $user_id)
	 {
	 	$query = array("id" => $appId, "user_id" => $user_id);
		$this->db->where($query);
		$this->db->delete("wapp");
		if($this->db->affected_rows() > 0)
		{
			return TRUE;
		} 
		return FALSE;
	 }
	 					/* API */
	 // Función para insertar una campaña desde el API
	 public function insert_campaign_api($id_wapp, $user_id, $fecha, $state, $hora, $minuto, $gmt, $voice, $name, $tipo_sms, $api)
	 {
	 	$camp = array(
	 		'id_wapp' => $id_wapp,
	 		'user_id' => $user_id,
	 		'fecha'   => $fecha,
	 		'state'   => $state,
	 		'hora'    => $hora,
	 		'minuto'  => $minuto,
	 		'gmt'	  => $gmt,
	 		'voice'   => $voice,
	 		'name'    => $name,
	 		'tipo_sms'=> $tipo_sms,
	 		'api'     => $api
	 	 );
	 	$this->db->insert('campaign', $camp);
			if($this->db->affected_rows() > 0)
			{
				return $this->db->insert_id();
			}
			return FALSE;
	 }
	 
	 // Función para insertar una campaña desde el API con valores de filtro adicionales así como la opción de que se lance en horario laboral
	 public function persist_campaign_api_with_external_id($id_wapp, $user_id, $fecha, $state, $hora, $minuto, $gmt, $voice, $name, $tipo_sms, $api, $external_indicator)
	 {
		
		$camp = array(
	 		'id_wapp' => $id_wapp,
	 		'user_id' => $user_id,
	 		'fecha'   => $fecha,
	 		'state'   => $state,
	 		'hora'    => $hora,
	 		'minuto'  => $minuto,
	 		'gmt'	  => $gmt,
	 		'voice'   => $voice,
	 		'name'    => $name,
	 		'tipo_sms'=> $tipo_sms,
	 		'api'     => $api,
			'external_indicator'     => $external_indicator
	 	 );
		
		// SE VERIFICA SI EXISTE UNA CAMPA;A CON EL EXTERNAL ID, SI EXISTE SE ACTUALIZA SINO SE INSERTA
				
		$this->db->select('id');
	 	$this->db->where('external_indicator', $external_indicator);
	 	$result	=	$this->db->get('campaign');
	 	
		if($result->num_rows() > 0)
	 	{
		
		// SE ACTUALIZA
				$id_campaign= $result->row();

		
				$this->db->where('id', $id_campaign->id);
				$result	=	$this->db->update('campaign',$camp);
				
		
			return $id_campaign->id;
		}else{
	 	
		 
		// SE INSERTA 
	 	
			$this->db->insert('campaign', $camp);
			
			if($this->db->affected_rows() > 0)
			{
				return $this->db->insert_id();
			}
			
			return FALSE;
		}
	 }
	 
	 // Función para insertar una campaña desde el API
	 public function insert_contacts_api($id_wapp, $user_id, $fecha, $state, $hora, $minuto, $gmt, $voice, $name, $tipo_sms, $api)
	 {
	 	$camp = array(
	 		'id_wapp' => $id_wapp,
	 		'user_id' => $user_id,
	 		'fecha'   => $fecha,
	 		'state'   => $state,
	 		'hora'    => $hora,
	 		'minuto'  => $minuto,
	 		'gmt'	  => $gmt,
	 		'voice'   => $voice,
	 		'name'    => $name,
	 		'tipo_sms'=> $tipo_sms,
	 		'api'     => $api
	 	 );
	 	$this->db->insert('campaign', $camp);
			if($this->db->affected_rows() > 0)
			{
				return $this->db->insert_id();
			}
			return FALSE;
	 }

	 public function get_campaign_condition($id)
	 {
	 	try
		{
			$this->db->select('api'); 
			$this->db->where('id',$id);
			$result	=	$this->db->get('campaign')->row();
			if(!empty($result))
			{
				return $result;
			}
			return FALSE;
		}catch(ErrorGetPrice $e){
			return FALSE;
		}
	 }

}//



