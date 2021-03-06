<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Class Campaign
*/
class Campaign extends CI_Controller {

	/** Function index
	 * 
	 * Parameter
	 * Carga el Login de Kkatoo por defecto
	 */
	public function index()
	{
		// ini_set('display_errors', 'on');
		$this->lang->load('campaign');
		if(!$this->_login_in())
		{
			//The Special app redirect
			$this->_return_to_special_url();
			
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('marketplace');
		}
		$this->load->model('campaign_model');
		$campaign 	= 	$this->campaign_model->get_campaign_info($this->session->userdata('user_id'));
		$campana	=	array();
		
		if(!empty($campaign))
		{
			foreach($campaign as $cam)
			{
				$info = $this->campaign_model->get_campaign_info($this->session->userdata('user_id'), $cam->id);
				$count_exitosas = $this->campaign_model->count_call_exitosas($this->session->userdata('user_id'), $this->uri->segment(3));
				$count_pendientes = $this->campaign_model->count_call_pendientes($this->session->userdata('user_id'), $this->uri->segment(3));
				$count_preparadas = $this->campaign_model->count_call_preparadas($this->session->userdata('user_id'), $this->uri->segment(3));
				$this->load->model('marketplace_model');
				$credits = $this->marketplace_model->get_user_credits();
				array_push($campana, array(
				                           					'credits'   =>  $credits,
											'id' 		=> $cam->id,
											'name' 		=> $cam->name,
											'fecha' 	=> $cam->fecha,
											'hora' 		=> $cam->hora,
											'count_exitosas'     =>	$count_exitosas,
											'count_pendientes' =>	$count_pendientes,
											'count_preparadas' =>	$count_preparadas,
											'gmt' 		=> $cam->gmt,
											'minuto'	=> $cam->minuto
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
	 * Funci�n campaign para traer informaci�n unica sobre una de ellas
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
				date_default_timezone_set('Africa/Casablanca');
				$this->load->helper('date');
				$result	= 	$this->campaign_model->get_campaign_queues($this->session->userdata('user_id'), $this->uri->segment(3));
				$pendi	= 	$this->campaign_model->get_campaign_pendi($this->session->userdata('user_id'), $this->uri->segment(3));
				$price	= 	$this->campaign_model->sum_campaign_real($this->session->userdata('user_id'), $this->uri->segment(3));
				$call	= 	$this->campaign_model->get_call_reali($this->session->userdata('user_id'), $this->uri->segment(3));
				$exito	= 	$this->campaign_model->get_call_exitosa($this->session->userdata('user_id'), $this->uri->segment(3));
				$marcado =	$this->campaign_model->get_call_marcados($this->session->userdata('user_id'), $this->uri->segment(3));
				$count_exitosas = $this->campaign_model->count_call_exitosas($this->session->userdata('user_id'), $this->uri->segment(3));
				$count_pendientes = $this->campaign_model->count_call_pendientes($this->session->userdata('user_id'), $this->uri->segment(3));
				$count_preparadas = $this->campaign_model->count_call_preparadas($this->session->userdata('user_id'), $this->uri->segment(3));		

				$campaign 	= 	$this->campaign_model->get_campaign($this->session->userdata('user_id'));
				$campaign_name;
				$campaign_date;
				if(!empty($campaign))
				{
					foreach($campaign as $cam)
					{
						//si el id de la campa�a en el array ($campaign) es igual al id de la campa�a en la vista, guardamos el nombre.
						if($cam->id == $this->uri->segment(3))
						{
							$campaign_name =  $cam->name;
							$campaign_date = $cam->fecha;
						}
					}
				}
				$this->load->model('marketplace_model');
				$credits = $this->marketplace_model->get_user_credits();
				$username = $this->marketplace_model->get_user_name();
				$data	=	array(
									'detalle'		=> $result,
									'credits'		=> $credits,
									'username'		=> $username,
									'total_call'	=> count($result),
									'pendi_call'	=> $pendi,
									'price_real'	=> $price,
									'call'			=> $call,
									'exito'			=> $exito,
									'marcado' 	=> 	$marcado,
									'count_exitosas' =>	$count_exitosas,
									'count_pendientes' =>	$count_pendientes,
									'count_preparadas' =>	$count_preparadas,
									'id_camp'		=> $this->uri->segment(3),
									'campaign_name' => $campaign_name,
									'campaign_date'	=> $campaign_date
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
	* Verifica si es una aplicaci�n especial
	*/
	private function _check_special(){
		return $this->specialapp->get('special');
	}
	
	/**
	* Retorna la apliaci�n si es especial a la url especial de esta
	*/
	public function _return_to_special_url(){
		if($this->_check_special()){
			$this->session->set_flashdata('error',$this->lang->line('loginplease'));
			redirect('login/login');
			die();
		}
	}

	/**
	* Funci�n para relanzar llamadas fallidas // lo hice yo XD
	*/

	public function relaunch_call()
	{
		$this->load->library('form_validation');
        $this->form_validation->set_rules('id_camp', 'Id de la campa�a', 'trim|required');

        //valido si los datos requeridos existen.
        if ($this->form_validation->run() == FALSE)
        {
            $aReturn = array(
                                'cod'   =>  0,
                                'messa' =>  'Ocurri� un error al intentar lenanzar la llamada'
                            );
            echo json_encode($aReturn);
        }
        else
        {

            $id_camp = $this->input->post('id_camp');
            $this->load->model('campaign_model');//Cargamos el modelo para esta funci�n
            $isRecalled   = $this->campaign_model->relaunch_call($id_camp); 


            if($isRecalled == FALSE)
            {  
            	$aReturn = array(
                                'cod'   =>  0,
                                'messa' =>  'Ocurri� un error al intentar relanzar la llamada'
                            );
            }
            else
            {
            	$aReturn = array(
                                'cod'   =>  1,
                                'messa' =>  'La campa�a se relanz� correctamente'
                            );
            }
            echo json_encode($aReturn);
        }

	}
	
	/**
	* Funci�n para mostrar la fecha de la campa�a en el Campaign_Detail
	**/
	public function campaign_date()
	{
		$this->load->model('campaign_model');
		$this->campaign_model->get_campaign_date($fecha);
	}
}


/* End of file welcome.php */
/* Location: ./application/controllers/login.php */