<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	
	/**
	 * Index Page for this controller.
	 * Carga el Login de Kkatoo por defecto
	 */
	public function index(){

		ini_set('display_errors', 'on');
		/*if(!$this->_login_in() || ($this->session->userdata("user_id")!=KKATOO_USER)){
			$this->lang->load('apps');
			$this->session->set_flashdata('error',$this->lang->line('initapp'));
			redirect('login/login');
			die();
		}*/
		$uri_app = $this->uri->segment(2);
		
		if($uri_app == 'update_user_credits'){

					$this->update_user_credits($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5));



                }else
		if(!$this->_login_in() ){
			$this->lang->load('apps');
			$this->session->set_flashdata('error',$this->lang->line('initapp'));
			
			redirect('login/login');
			die();
		}
		

		if($uri_app=='recents'){
				$this->display_recents();
		}else if($uri_app == 'users' && !$this->uri->segment(3)){
			$this->display_users();
		}else if($uri_app == 'users' && $this->uri->segment(3)){
                     $this->user();

                }else if($uri_app == 'update_user_credits'){

					$this->update_user_credits($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5));



                }
	}
	public function recents(){
		$subseccion = $this->uri->segment(3);
		if($subseccion == NULL){
			$this->display_recents();
		}else if($subseccion=='aproved'){
			$this->display_recents_aproved();
		}else if($subseccion=='noaproved'){
			$this->display_recents_no_aproved();
		}
	}
	public function user(){

		
		if(!$this->uri->segment(3) or !$this->uri->segment(4) ){
			
			$to=date('Ymd');
			$date = strtotime(date('Y-m-d H:i:s') . ' -30 days');
			$from=date('Ymd', $date);
		
		}else{
			 $from = str_replace("-","",$this->uri->segment(4));
			 $to = str_replace("-","",$this->uri->segment(5));
			
		}
		
		
		$id = $this->uri->segment(3);
		
		$this->load->model("user_model");
		$this->load->model("campaign_model");
		$data["user"] = $this->user_model->get_user_by_id($id);

		$value=$this->campaign_model->get_count_queues_by_user_from_to_state($id,$from,$to,3)[0];
		
		$data["campaign"] = array(
			"En espera" => $this->campaign_model->get_count_queues_by_user_from_to_state($id,$from,$to,1)[0],
			"Pendiente Saldo Agotado" => $this->campaign_model->get_count_queues_by_user_from_to_state($id,$from,$to,2)[0],
			"Llamada conectada" => $this->campaign_model->get_count_queues_by_user_from_to_state($id,$from,$to,3)[0],
			"Canceladas/No contesta" => $this->campaign_model->get_count_queues_by_user_from_to_state($id,$from,$to,4)[0],
			"Mensaje enviado" => $this->campaign_model->get_count_queues_by_user_from_to_state($id,$from,$to,5)[0],
			"Mensaje no conectado" => $this->campaign_model->get_count_queues_by_user_from_to_state($id,$from,$to,6)[0]
		);
		
		$data_consolidated=$this->campaign_model->get_count_queues_by_user_by_campain($id,$from,$to);
		
	
		if($data_consolidated){
			foreach($data_consolidated as $row){
				$campain_consolidated[$row->id]['name']=$row->name;
				$campain_consolidated[$row->id][$row->state]=$row->cantidad;
				
			}
			
			$data["campaign_consolidated"] = $campain_consolidated;
		}
		
		
		$this->load->view('admin/campaign', $data);
	}
	private function display_calls($user,$campaign){
		$this->load->model("campaign_model");
		$data = array();
		$data["users"] = $this->campaign_model->get_queues(0);
		$this->load->view('admin/calls', $data);
	}
	private function display_users(){
		
		$this->load->model("user_model");
		$this->load->model("queues_model");
		$data = array();
		
		$credit_logs=$this->queues_model->get_user_log_credits();

                $credit_logs_consolidated="";
		
			if($credit_logs){
				foreach($credit_logs as $row){
					$credit_logs_consolidated[$row->user_id][]=array("admin"=>$row->fullname,"credits_before"=>$row->credits_before,"credits_after"=>$row->credits_after );
					
				}
			}
		$data["admin_id"] = $this->session->userdata('user_id');
		$data["users"] = $this->user_model->get_users_by_status();
		$data["users_credtis_logs"] = $credit_logs_consolidated;
		$this->load->view('admin/users', $data);
	}
	private function display_recents(){
		$this->load->model("apps_model");
		$data = array();
		$data["apps"] = $this->apps_model->get_recent_apps();
		$this->load->view('admin/recent_app_list', $data);
	}
	private function display_recents_aproved(){
		$this->load->model("apps_model");
		$data = array();
		$data["apps"] = $this->apps_model->get_recent_apps(1);
		$this->load->view('admin/recent_app_list', $data);
	}
	private function display_recents_no_aproved(){
		$this->load->model("apps_model");
		$data = array();
		$data["apps"] = $this->apps_model->get_recent_apps(0);
		$this->load->view('admin/recent_app_list', $data);
	}

	/**
	 * Funcion privada para verificar el Login del usuario
	*/
	private function _login_in()
	{
		return $this->session->userdata('logged_in');
	}

	/**
	* cambiar el credito de un usuario
	*/
	public function update_user_credits($user_id = 0, $credits = 0, $admin_id)
	{

	
		
		$this->load->model('user_model');
		$user = $this->user_model->get_user_by_id($user_id);
		$this->db->set('credits', $credits,false);
		$this->db->where('id', $user_id);
		$result = $this->db->update('user');
		$data = array(
			"user_id" => $user->id,
			"user_id_admin" =>  $admin_id,
			"credits_before" => $user->credits,
			"credits_after" => $credits
		);
		//echo "$user_id, $credits, $admin_id, $user->id ";
		$this->db->insert('log_credits', $data);
		return $result;
		// return number_format($result, 1);
	}
}
