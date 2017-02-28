<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Class Campaign
*
*
*
*
*/

class Campaign extends CI_Controller {

	/** Function index
	 * 
	 * Parameter
	 * Carga el Login de Kkatoo por defecto
	 */
	public function index()
	{
		ini_set('display_errors', 'on');
		$this->lang->load('campaign');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('marketplace');
		}
		$this->load->model('campaign_model');
		$campaign 	= 	$this->campaign_model->get_campaign($this->session->userdata('user_id'));
		$campana	=	array();
		if(!empty($campaign))
		{
			foreach($campaign as $cam)
			{
				$estado	=	"";	
				$queues = 	$this->campaign_model->get_queues($this->session->userdata('user_id'), $cam->id);
				$price	= 	$this->campaign_model->get_sum_queues($this->session->userdata('user_id'), $cam->id);
				$result	= 	$this->campaign_model->get_campaign_queues($this->session->userdata('user_id'), $cam->id);
				$call	= 	$this->campaign_model->get_call_reali($this->session->userdata('user_id'), $cam->id);
				if(empty($queues))
				{
					$queues1 = 	$this->campaign_model->get_queues($this->session->userdata('user_id'), $cam->id, 1);
					if(empty($queues1))
					{
						$estado	=	$this->lang->line('finish');
					}
					else
					{
						$estado	=	$this->lang->line('cola');
					}
				}
				else
				{
					$estado	=	$this->lang->line('active');
				}
				//echo $estado.' estado';
				$price = 0;
				if(!empty($price->price)) $price = $price->price;
				array_push($campana, array(
											'id' 		=> $cam->id,
											'name' 		=> $cam->name,
											'fecha' 	=> $cam->fecha,
											'hora' 		=> $cam->hora,
											'gmt' 		=> $cam->gmt,
											'total_call'=> count($result),
											'call'		=> $call,
											'minuto'	=> $cam->minuto,
											'estado'	=> $estado,
											'price'		=> $price
											)
							);
			}
		}
		$data		= 	array(
							'campaign'	=> $campana,
							);
		$this->_view_campaign_manage($data);
	}
	/**
	 * Función campaign para traer información unica sobre una de ellas
	 *
	 */
	public function detail_campaign()
	{
		ini_set('display_errors', 'on');
		$this->lang->load('campaign');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('marketplace');
		}
		if($this->uri->segment(3) !== FALSE)
		{
			$this->load->model('campaign_model');
			$exist	= 	$this->campaign_model->get_campaign_exist($this->session->userdata('user_id'), $this->uri->segment(3));
			if(!empty($exist))
			{
				// date_default_timezone_set('Africa/Casablanca');
				$this->load->helper('date');
				$result	= 	$this->campaign_model->get_campaign_queues($this->session->userdata('user_id'), $this->uri->segment(3));
				$pendi	= 	$this->campaign_model->get_campaign_pendi($this->session->userdata('user_id'), $this->uri->segment(3));
				$price	= 	$this->campaign_model->sum_campaign_real($this->session->userdata('user_id'), $this->uri->segment(3));
				$call	= 	$this->campaign_model->get_call_reali($this->session->userdata('user_id'), $this->uri->segment(3));
				$exito	= 	$this->campaign_model->get_call_exitosa($this->session->userdata('user_id'), $this->uri->segment(3));				
				$campaign 	= 	$this->campaign_model->get_campaign($this->session->userdata('user_id'));
				$campaign_name;

				if(!empty($campaign))
				{
					foreach($campaign as $cam)
					{
						//si el id de la campaña en el array ($campaign) es igual al id de la campaña en la vista, guardamos el nombre.
						if($cam->id == $this->uri->segment(3))
						{
							$campaign_name =  $cam->name;
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
									'id_camp'		=> $this->uri->segment(3),
									'campaign_name' => $campaign_name
									);


				$this->_view_detail_campaign($data); 
			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('campaignno'));
				redirect('campaign');
			}
		}
		else
		{
			$this->session->set_flashdata('error',$this->lang->line('campaignno'));
			redirect('campaign');
		}
	}
	

	/**
	 * Funcion privada para verificar el Login del usuario
	*/
	private function _login_in()
	{
		return $this->session->userdata('logged_in');
	}
	
	private function _view_campaign_manage($data)
	{
		$this->load->view('campaign_manager',$data);
	}
	private function _view_detail_campaign($data)
	{
		$this->load->view('campaign_detail',$data);
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
	* Función para relanzar llamadas fallidas // lo hice yo XD
	*/

	public function relaunch_call()
	{
		$this->load->library('form_validation');
        $this->form_validation->set_rules('id_camp', 'Id de la campaña', 'trim|required');

        //valido si los datos requeridos existen.
        if ($this->form_validation->run() == FALSE)
        {
            $aReturn = array(
                                'cod'   =>  0,
                                'messa' =>  'Ocurrió un error al intentar lenanzar la llamada'
                            );
            echo json_encode($aReturn);
        }
        else
        {

            $id_camp = $this->input->post('id_camp');
            $this->load->model('campaign_model');//Cargamos el modelo para esta función
            $isRecalled   = $this->campaign_model->relaunch_call($id_camp); 


            if($isRecalled == FALSE)
            {  
            	$aReturn = array(
                                'cod'   =>  0,
                                'messa' =>  'Ocurrió un error al intentar relanzar la llamada'
                            );
            }
            else
            {
            	$aReturn = array(
                                'cod'   =>  1,
                                'messa' =>  'La campaña se relanzó correctamente'
                            );
            }
            echo json_encode($aReturn);
        }

	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/login.php */