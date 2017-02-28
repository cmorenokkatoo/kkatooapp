<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class login_edwin extends CI_Controller {

    private $content = NULL;
    private $uIdSession = NULL;
    private $usersesiongoogledrive = NULL;

    function __construct() {
        parent::__construct();
        $this->load->library( 'ci_google_edwin' );
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/controller_main
     *  - or -  
     *      http://example.com/index.php/controller_main/index
     *  - or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/controller_main/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    /* ------------------------------------------------- Edwin Sepúlveda ----------------------------------------------------- */
    public function index() {
	    var_dump($this->ci_google_edwin->get_url_connect());
    }
    
    public function actions(){
	   $usuario = $this->ci_google_edwin->get_user();
	   echo var_dump($usuario);
    }

    public function gpauth()
	{
		if ($this->_login_in()) 
		{
			if($this->_check_special()){
				redirect('apps/'.$this->specialapp->get('uri'));
				die();
			}
			redirect('marketplace','refresh');
		}
		$this->lang->load('login');
		$this->load->library( 'ci_google_edwin' );
		$usuario = $this->ci_google_edwin->get_user();
		if(empty($usuario))
		{
			$data['error'] 	=	$this->lang->line('errorgoogle');
			$this->_view_login($data);
		}
		else
		{
			$this->load->model('user_model');
			$user 	=	$this->user_model->user_by_mail($usuario['email']);
			if($user!==FALSE)
			{
				//Si el usuario ya se encuentra en el sistema simplemente se conecta				
				$newdata	=	array(
										'fullname'	=>$user->fullname,
										'email'		=>$user->email,
										'user_id'	=>$user->id,
										'credits'	=>$user->credits
									 );
				$this->user_model->init_session($newdata);
				$retorno = $this->session->flashdata('retorno'); 
				if(empty($retorno))
				{
					if($this->_check_special()){
						redirect('apps/'.$this->specialapp->get('uri'), 'refresh');
						die();
					}
					redirect('marketplace','refresh');
				}
				else
				{
					redirect($retorno);
				}
			}
			else
			{
				$pass_unico =	uniqid();
				$id_last	=	$this->user_model->insert_new_user(
																		$usuario['email'],
																		$pass_unico,
																		$usuario['nombre'],
																		1
																	);
				if(!empty($id_last))
				{
					$newdata	=	array(
											'fullname'	=>$usuario['nombre'],
											'email'		=>$usuario['email'],
											'user_id'	=>$id_last,
											'credits'	=>0
										 );
					$this->user_model->init_session($newdata);
					//Envio de Correo de confirmación del usuario
					$prueba	=	$this->user_model->email_welcome(
														$usuario['email'],
														$usuario['nombre'],
														''
													);
					log_message('debug', "Usuario Google creado con Exito");
					$retorno = $this->session->flashdata('retorno'); 
					if(empty($retorno))
					{
						if($this->_check_special()){
							$this->session->set_flashdata('exitoso',$this->lang->line('firstkedits'));
							redirect('payment', 'refresh');
							die();
						}
						redirect('marketplace','refresh');
					}
					else
					{
						redirect($retorno);
					}
				}
				else
				{
					$data['error'] 	=	$this->lang->line('errorregister');
					$this->_view_login($data);
					log_message('debug', "Error al crear un nuevo usuario");
				}
			}
		}
	}
    
}

/* End of file controller_main.php */
/* Location: ./application/controllers/controller_main.php */