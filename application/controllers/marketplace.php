<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketplace extends CI_Controller {
	 /**
	 * Funcion publica para cargar el index del marketplace
	 */
	public function index()
	{
		$this->deny_marketplace();
		
		$category	=	$this->uri->segment(2);
		$this->lang->load('marketplace');
		$this->load->model('marketplace_model');
		$id_category = new stdClass();
		$id_category->id = NULL;
		if($this->input->get('p')===FALSE)
		{
			$limit = 0;
		}
		else
		{
			$limit = $this->input->get('p')*8;
		}
		if($category!==FALSE)
		{
			$result 	=	$this->marketplace_model->get_uri_category($category);
			if(!empty($result))
			{
				$id_category = $result;
			}
		}		
		$apps 		=	$this->marketplace_model->get_apps($id_category->id, $limit);
		$apps_ini 	=	$this->marketplace_model->get_app_ini();
		// $apps_ini->comments = $this->marketplace_model->get_comments_app($apps_ini->id);
		// $points		=	$this->marketplace_model->get_points();
		$credits = $this->marketplace_model->get_user_credits();
		$username = $this->marketplace_model->get_user_name();
		$category 	=	$this->marketplace_model->get_category();
		$view_data 	=	array(	
								'credits'   =>  $credits,
								'username'	=> 	$username,
								'category'	=>	$category,
								'ini'		=> 	$apps_ini,	
								'apps'		=>	$apps
								);
		// echo $username;
		// die();
		$this->_view_marketplace($view_data);
	}
	/**
	 * Funcion publica para realizar la busqueda por medio del buscador del market
	 */
	public function search()
	{
		
		/*$this->load->library('form_validation');
		$this->form_validation->set_rules('q', 'BUSCAR', 'required|xss_clean|alpha_dash');
		
		$this->lang->load('marketplace');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error',$this->lang->line('errorsearch'));
			redirect('marketplace');
		}
		*/
		$this->lang->load('marketplace');
		$this->load->model('marketplace_model');
		$category 	=	$this->marketplace_model->get_category();
		if($this->input->get('p')===FALSE)
		{
			$limit = 0;
		}
		else
		{
			$limit = $this->input->get('p')*8;
		}
		$result	=	$this->marketplace_model->get_search($this->input->get('q'), $limit);
		if(empty($result))
		{
			$apps 		=	$this->marketplace_model->get_apps(NULL, $limit);
			$view_data 	=	array(
								'apps'      => $apps,
								'category'	=> $category,
								'error' 	=> $this->lang->line('nodatasearch')
								);
		}
		else
		{
			$view_data 	=	array(	
								'category'	=>	$category,
								'apps'		=>	$result
								);
		}
		$this->_view_marketplace_search($view_data);
	}
	/**
	 * Funcion privada para cargar la vista de search de Marketplace con errores
	 */
	private function _view_marketplace_search($data)
	{
		$this->lang->load('marketplace');
		$this->load->view('marketplace_search',$data);
	}
	/**
	 * Funcion privada para cargar la vista de errores del Markeplace
	 */
	private function _view_marketplace($data)
	{
		$this->lang->load('marketplace');
		$this->load->view('marketplace',$data);
	}
	/**
	 * Funcion privada para verificar el Login del usuario
	*/
	private function _login_in()
	{
		return $this->session->userdata('logged_in');
	}
	/******************************************
	FUNCIONES AJAX PARA CARGAR DATOS EN EL MARKETPLACE
	*******************************************/
	public function set_comment_app()
	{
		$this->lang->load('marketplace');
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
		$this->form_validation->set_rules('comment', 'comment', 'required|xss_clean|min_length[5]|max_length[200]');
		$this->form_validation->set_rules('app_id', 'app_id', 'required|xss_clean|numeric');
		if ($this->form_validation->run() == FALSE)
		{
			echo validation_errors();
		}
		else
		{
			$this->load->model('user_model');
			$result	=	$this->user_model->user_by_mail($this->session->userdata('email'));
			$data	=	array(
								'user_id'	=> $result->id,
								'fullname'  => $result->fullname,
								'comentario'=> $this->input->post('comment'),
								'app_id'    => $this->input->post('app_id')
							);
			$this->load->model('marketplace_model');
			$inser	=	$this->marketplace_model->insert_comment_app($data);
			if($inser)
			{
				$res 	= array(
								'cod' 	=> 1,
								'messa'	=> $this->lang->line('commentadd'),
								'fullname' => $result->fullname,
								'comentario'=> $this->input->post('comment'),
								'app_id'    => $this->input->post('app_id')
								);
				echo json_encode($res);
			}
			else
			{
				$res 	= array(
								'cod' 	=> 0,
								'messa'	=> $this->lang->line('errorcomment')
								);
				echo json_encode($res);
			}
		}
	}
	 /**
	 * Funcion publica para puntuar una applicacion de kkatoo
	 */
	public function point_app()
	{
		$this->lang->load('marketplace');
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
		$this->form_validation->set_rules('app_id', 'app_id', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('points', 'points', 'required|xss_clean|numeric');
		if ($this->form_validation->run() == FALSE)
		{
			echo validation_errors();
		}
		else
		{
			$this->load->model('marketplace_model');
			$result	=	$this->marketplace_model->get_voted_app($this->session->userdata('user_id'),$this->input->post('app_id'));
			if(empty($result))
			{
				$inse	=	$this->marketplace_model->insert_vote_app($this->session->userdata('user_id'),$this->input->post('app_id'));
				if($inse)
				{
					$cuan	=	$this->marketplace_model->update_rank_app($this->input->post('app_id'),$this->input->post('points'));
					$res 	= array(
							'cod' 	=> 1,
							'messa'	=> $this->lang->line('thanksvoted')
							);
					echo json_encode($res);
				}
				else
				{
					$res 	= array(
							'cod' 	=> 0,
							'messa'	=> $this->lang->line('errorvoted')
							);
					echo json_encode($res);
				}
			}
			else
			{
				$res 	= array(
							'cod' 	=> 0,
							'messa'	=> $this->lang->line('youvoted')
							);
				echo json_encode($res);
			}
		}
	}
	/**
	* Funcion publica para traer una aplicación opr ajax de kkatoo para mostrar en el index
	*/
	public function get_by_id_app()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|numeric');
		if ($this->form_validation->run() == FALSE)
		{
			echo validation_errors();
		}
		else
		{
			$this->load->model('marketplace_model');
			$result 	=	$this->marketplace_model->get_app_by_id($this->input->post('id'));
			echo json_encode($result);
		}
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
	* Denega el acceso al marketplace por permisos de aplicación especial.
	*/
	function deny_marketplace(){
		$this->lang->load('marketplace');
		if($this->permissions->get('deny_marketplace')){
			$this->session->set_flashdata('error',$this->lang->line('deny_marketplace'));
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
}