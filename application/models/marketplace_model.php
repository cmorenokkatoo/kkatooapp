<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketplace_model extends CI_Model {

	/**
	* Funcion para traer todas las aplicaciones por categoria y demas
	*/
	public function get_apps($id_category = NULL, $limit=0)
	{
		try
		{
			if($id_category===NULL)
			{
				$this->db->select('wapp.*, COUNT(comment_app.app_id) AS total_comentario');
				$this->db->from('wapp');
				$this->db->join('comment_app', 'wapp.id = comment_app.app_id', 'left');
				$this->db->where('wapp.aproved', 1);
				$this->db->where('wapp.private', 0);
				$this->db->group_by("wapp.id"); 
				$this->db->order_by("wapp.created", "desc");
				//$this->db->limit(8,$limit);
				$result = $this->db->get()->result();
			}
			else
			{
				$this->db->select('wapp.*, COUNT(comment_app.app_id) AS total_comentario');
				$this->db->from('wapp');
				$this->db->join('comment_app', 'wapp.id = comment_app.app_id', 'left');
				$this->db->where('wapp.category',$id_category);
				$this->db->where('wapp.aproved', 1);
				$this->db->where('wapp.private', 0);
				$this->db->group_by("wapp.id");
				$this->db->order_by("wapp.created", "desc"); 
				$this->db->limit(8,$limit);
				$result= $this->db->get()->result();
			}
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetapps $e){
			log_message('debug','Error al tratar de traer todas las aplicaciones disponibles');
			return FALSE;
		}
	}
	/**
	* Funcion para traer los comentarios de una aplicación
	*/
	public function get_comments_app($id)
	{
		try
		{
			$result= $this->db->get_where('comment_app', array('app_id' => $id))->result();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetappini $e){
			log_message('debug','Error al tratar de traer una aplicación inicial para el marketplace');
			return FALSE;
		}
	}
	/**
	* Funcion para traer la aplicación inicial del market
	*/
	public function get_app_ini()
	{
		try
		{
			$result= $this->db->get_where('wapp', array('inicial' => 1), 1)->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetappini $e){
			log_message('debug','Error al tratar de traer una aplicación inicial para el marketplace');
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
	/**
	* Funcion para traer la url de una categoria
	*/
	public function get_uri_category($category = NULL)
	{
		try
		{
			if($category !== NULL)
			{
				$result= $this->db->get_where('category', array('uri' => $category))->row();
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
			log_message('debug','Error al tratar de traer todas las categorias para el marketplace');
			return FALSE;
		}
	}
	/**
	* Funcion para agregar que un usuario ya realizó el voto por una aplicación
	*/
	public function insert_vote_app($user_id, $app_id)
	{
		try
		{
			$data	= array(
								'app_id'	=>$app_id,
								'user_id'	=>$user_id
							);
			$result = $this->db->insert('voted_app', $data);
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorinsertapp $e){
			log_message('debug','Error al tratar de ingresar el voto de un usuario');
			return FALSE;
		}
	}
	/**
	* Funcion para aumentar los puntos de ranking de las aplicaciones
	*/
	public function update_rank_app($app_id,$points)
	{
		try
		{
	        $this->db->set('cuantos', 'cuantos+1',FALSE);
	        $this->db->set('points', 'points+'.$points,FALSE); 
			$this->db->where('id', $app_id);
			$result = $this->db->update('wapp');
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorupdaterank $e){
			log_message('debug','Error al tratar de ingresar el voto de un usuario en la app');
			return FALSE;
		}
	}
	public function get_points($app_id,$points)
	{
		try{
		$this->db->select('points', $points);
		$this->db->from('wapp');
		$this->db->where('id', $app_id);
		$result = $this->db->get('wapp');
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorupdaterank $e){
			log_message('debug','Error al tratar de ingresar el voto de un usuario en la app');
			return FALSE;
		}
	}
	/**
	* Funcion para realizar una busqueda booleana entre los titulos y descripcion de la aplicación
	*/

	
	public function get_search($q,$limit=0)
	{
		try
		{
			$this->db->select('wapp.*, COUNT(comment_app.app_id) AS total_comentario');
			$this->db->from('wapp');
			$this->db->join('comment_app', 'wapp.id = comment_app.app_id', 'left');
			$this->db->where("MATCH(wapp.title, wapp.description)AGAINST('".$q."' IN BOOLEAN MODE)", NULL, FALSE);
			$this->db->group_by("wapp.id"); 
			$this->db->limit(10,$limit);
			$result = $this->db->get()->result();
			
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorupdaterank $e){
			log_message('debug','Error al tratar de consultar una aplicacion por el metodo search');
			return FALSE;
		}
	}
	
	/******************************************
	FUNCIONES AJAX PARA CARGAR DATOS EN EL MARKETPLACE
	*******************************************/
	public function insert_comment_app($data)
	{
		try
		{
			$this->db->set('fecha', 'NOW()',false);
			$result = $this->db->insert('comment_app', $data);
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetappini $e){
			log_message('debug','Error al tratar de agregar un comentario a una aplicacion');
			return FALSE;
		}
	}
	/**
	* Funcion para traer una aplicación en base a un ID
	*/
	public function get_app_by_id($id)
	{
		try
		{
			$result= $this->db->get_where('wapp', array('id' => $id), 1)->row();
			$result->comments = $this->get_comments_app($result->id);
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorgetappini $e){
			log_message('debug','Error al tratar de traer una aplicación inicial para el marketplace');
			return FALSE;
		}
	}
	/**
	* Funcion publica saber si un usuario ha realizado un voto por una aplicacion
	*/
	public function get_voted_app($user_id, $app_id)
	{
		try
		{
			$result= $this->db->get_where('voted_app', array('user_id' => $user_id,'app_id'=>$app_id))->row();
			if(empty($result))
			{
				return FALSE;
			}
			else
			{
				return $result;
			}
		}catch(Errorvotedapp $e){
			log_message('debug','Error al tratar de saber si un usuario ha realizado un voto por una aplicacion');
			return FALSE;
		}
	}

	public function get_user_credits($user_id=0)
	{
		$user_id = $this->session->userdata('user_id');
		if (empty($user_id)) {
			echo "";
		}else{
			$query = $this->db->query("SELECT credits FROM user where id = ". $user_id . "");
			$row = $query->row();

			return $row->credits;
		}
		
	}
	public function get_user_name($user_id=0)
	{
		$user_id = $this->session->userdata('user_id');
		if (empty($user_id)) {
			echo "";
		}else{
			$query = $this->db->query("SELECT fullname FROM user where id = ". $user_id . "");
			$row = $query->row();

			return $row->fullname;
		}
		
	}
}