<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model {

	/**
	 * Funcion para verificar si un usuario esta registrado en KKatoo
	 */
	public function login_in($email,$password)
	{
		if(!empty($password)){
			$result = $this->db->get_where('user', array('email' => $email, 'verified' => 1))->row();
		}else{
			$result = $this->db->get_where('user', array('email' => $email))->row();
		}
		//$result = $this->mdb->getfirst($this->mdb->{$this->mdb->mongo_friendly}->user,array('email'=>$email));
		if(!empty($result))
		{
			$this->load->helper('security');
			$str = do_hash($password); // SHA1
			$str = do_hash($str, 'md5'); // MD5 
			if($str == $result->password)
			{
				return $result;
			}
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Funcion para iniciar la session del usuario
	 */
	public function init_session($user)
	{
		$datasession = array(
						'fullname'  => $user['fullname'],
						'email'     => $user['email'],
						'user_id'   => $user['user_id'],
						'credits'   => $user['credits'],
						'logged_in' => TRUE
					);
		$this->session->set_userdata($datasession);
	}
	/**
	 * Funcion para agregar un nuevo usuario en KKatoo
	 */
	public function insert_new_user($email = "", $password = "",$fullname ="",$verified=0 , $phone ="", $indi_pais = 0, $first_time = 0)
	{
		try{
			$this->load->helper('security');
			$pass = do_hash($password); // SHA1
			$pass = do_hash($pass, 'md5'); // MD5
			$data 	= array(
								'email'		=>$email,
								'fullname'	=>$fullname,
								'password'	=>$pass,
								'verified'	=>$verified,
								'phone'		=>$phone,
								'id_country'=>$indi_pais,
								'first_time' => $first_time
								// 'is_owner' => $is_owner
							);
			$this->db->set('created', 'NOW()',false);
			$this->db->insert('user', $data); 
			//$result	=	$this->mdb->insert($this->mdb->{$this->mdb->mongo_friendly}->user,$data);
			return  $this->db->insert_id();
		}catch (Erroralingresarnuevo $e){
			return FALSE;
		}
	}
	
	/**
	* Actualiza el valor de ingreso por primera vez de un usuario
	*/
	function user_first_time_update($id){
		try
		{
			$data = array('first_time'=>0);
            $this->db->set('updated', 'NOW()', false);
			$this->db->where('id', $id);
			$result = $this->db->update('user', $data);
			if(!empty($result))
			{
				return $result;
			}
			else
			{
				return FALSE;
			}
		}catch(ErrorverifiedUserId $e){
			log_message('debug','Error al tratar de verificar la cuenta como confirmada del usuario');
			return FALSE;
		}
	}
	
	/**
	 * Funcion para enviar un correo de bienvenida al nuevo usuario
	 */
	public function email_welcome($email,$fullname,$token)
	{
		try
		{
			$data 		=	array(
									'name' => $fullname,
									'email'=> $email,
									'token'=> $token
									);

			$mensaje 	=	$this->load->view('email/'.$this->config->item('language').'/welcome_user',$data,TRUE);

			$this->load->library('email');
			$this->email->from(KKATOO_EMAIL_INFO, 'Kkatoo Info');
			$this->email->to($email);
			$this->email->subject($this->lang->line('subjectwelcome'));
			$this->email->message($mensaje);
			$this->email->send();
			log_message('debug', "Correo de bienvenida enviado con exito");
			return TRUE;
		}catch(ErrorenviarWelcome $e){
			log_message('debug', "Error al enviar correo de bienvenida");
			return FALSE;
		}
	}
	
	/**
	 * Funcion para enviar un correo de verificación de cuenta al nuevo usuario
	 * Function to send a verification email to the new user account
	 */
	public function email_verify_account($email,$fullname,$token)
	{
		try
		{
			$data 		=	array(
									'name' => $fullname,
									'email'=> $email,
									'token'=> $token
									);

			$mensaje 	=	$this->load->view('email/'.$this->config->item('language').'/verify_account',$data,TRUE);
			$this->load->library('email');
			$this->email->from(KKATOO_EMAIL_INFO, 'Kkatoo Info');
			$this->email->to($email);
			$this->email->subject($this->lang->line('subjectwelcome'));
			// 
			$this->email->message($mensaje);
			$this->email->send();
			//echo $this->email->print_debugger();
			//die();
			//print_r(error_get_last());
			log_message('debug', "Correo enviado de verificación de cuenta enviado con exito");
			return TRUE;
			// return $this->email->print_debugger();
		}catch(ErrorenviarWelcome $e){
			log_message('debug', "Error al enviar correo de verificación de cuenta");
			return FALSE;
			// return $this->email->print_debugger();
		}
	}
	/**
	 * Funcion para enviar un correo de reset password al usuario
	*/
	public function password_reset_user($email,$fullname,$token)
	{
		try
		{
			$data 		=	array(
									'name' => $fullname,
									'email'=> $email,
									'token'=> $token
									);

			$mensaje 	=	$this->load->view('email/'.$this->config->item('language').'/reset_password_user',$data,TRUE);

			$this->load->library('email');
			$this->email->from(KKATOO_EMAIL_INFO, 'Kkatoo Info');
			$this->email->to($email);
			$this->email->subject($this->lang->line('subjectwelcome'));
			$this->email->message($mensaje);
			$this->email->send();
			log_message('debug', "Correo enviado de bienvenida enviado con exito");
			return TRUE;
		}catch(ErrorenviarWelcome $e){
			log_message('debug', "Error al enviar correo de bienvenida");
			return FALSE;
		}

	}
	/**
	 * Funcion para generar un nuevo token para confirmar el correo de usuario
	*/
	public function new_token()
	{
		try
		{
			$this->load->helper('security');
			$id_unico	=	uniqid();
			$str = do_hash($id_unico); // SHA1
			$str = do_hash($str, 'md5'); // MD5 
			return $str;

		}catch (Errorenvionewtoken $e){
			log_message('debug', "Error al generar el nuevo token para un usuario nuevo");
			return FALSE;
		}
	}
	/**
	 * Funcion para agregar un nuevo token al usuario
	*/
	public function insert_new_token($id,$token,$tipo)
	{
		try
		{
			$this->db->where('type', $tipo);
			$this->db->where('user_id', $id);
			$this->db->delete('token'); 		
			
			$data 	= array(
							'user_id'	=>$id,
							'token'  	=>$token,
							'type'		=>$tipo
							);
			$result = $this->db->insert('token', $data);
			return $result;
		}catch(Errorinsertnewtoken $e){
			log_message('debug','Error al tratar de agregar un nuevo token en la collecion');
			return FALSE;
		}
	}
	/**
	 * Funcion consultar a un usuario por Email
	*/
	public function user_by_mail($email)
	{
		try
		{
			$result = $this->db->get_where('user', array('email' => $email))->row();
			if(!empty($result))
			{
				return $result;
			}
			else
			{
				return FALSE;
			}
		}catch(Errorconsultarusariopormail $e){
			log_message('debug','Error al tratar de consultar a un usuario por email');
			return FALSE;
		}
	}
	/**
		* Funcion consultar a un token por ID, Toke, Tipo
	*/
	public function token_by_userid($id_user,$token,$tipo)
	{
		try
		{
			$result = $this->db->get_where('token', array('user_id' => $id_user, 'token'	=> $token, 'type'	=>	$tipo))->row();
			if(!empty($result))
			{
				return $result;
			}
			else
			{
				return FALSE;
			}
		}catch(Errorconsultartokenbyuserid $e){
			log_message('debug','Error al tratar de consultar token por UserId');
			return FALSE;
		}
	}
	/**
	* Funcion para eliminar un token en base a un userID
	*/
	public function delete_token_by_userid($id_user,$token,$tipo)
	{
		try
		{
			$this->db->where('type', $tipo);
			$this->db->where('user_id', $id_user);
			$this->db->where('token',	$token);
			$result = $this->db->delete('token'); 	
			if(!empty($result))
			{
				return $result;
			}
			else
			{
				return FALSE;
			}
		}catch(Errorconsultartokenbyuserid $e){
			log_message('debug','Error al tratar de eliminar un token por userid');
			return FALSE;
		}
	}
	/**
	* Funcion para verificar el usuario como confirmado una vez haya dado click al enlace enviado
	*/
	public function set_verified_userid($email)
	{
		try
		{
			$data = array(
               'verified' => 1
            );
            $this->db->set('updated', 'NOW()',false);
			$this->db->where('email', $email);
			$result = $this->db->update('user', $data);
			if(!empty($result))
			{
				return $result;
			}
			else
			{
				return FALSE;
			}
		}catch(ErrorverifiedUserId $e){
			log_message('debug','Error al tratar de verificar la cuenta como confirmada del usuario');
			return FALSE;
		}
	}
	/**
	* Funcion para actualizar el nuevo password
	*/
	public function update_new_password($userid, $password)
	{
		try
		{
			$this->load->helper('security');
			$pass = do_hash($password); // SHA1
			$pass = do_hash($pass, 'md5'); // MD5 
			$data 	= array(
							'password' => $pass
							);
			$this->db->where('id', $userid);
			$result = $this->db->update('user', $data);
			if(!empty($result))
			{
				return $result;
			}
			else
			{
				return FALSE;
			} 
		}catch(Updatenewpassword $e){
			log_message('debug','Error al actualizar el nuevo password del usuario');
			return FALSE;
		}
	}
	
	/**
	* Optiene los datos del usuario por id
	* @param $id_user es el id del  usuario
	*/
	function get_user_by_id($id_user=0){
		try
		{
			$result = $this->db->get_where('user', array('id' => $id_user))->row();
			if(!empty($result))
			{
				return $result;
			}
			else
			{
				return FALSE;
			}
		}catch(Exception $e){
			log_message('debug','Error al tratar de consultar a un usuario por id => get_user_by_id');
			return FALSE;
		}
	}
	
	/**
	* Ingresa un nuevo usuario asociado a una aplicación de difusión que maneja pines
	*/
	function insert_new_user_app($id_user=''){
		try
		{
			$this->db->where('id_user', $id_user);
			if($this->db->get('user_wapp')->row()){
				return FALSE;
			}else{
				$this->db->insert('user_wapp', array('id_user'=>$id_user, 'credits'=>0));
				$inserted = $this->db->insert_id();
				if($inserted){
					return $inserted;
				}else{
					return FALSE;
				}
			}
		}catch(Exception $e){
			log_message('debug','Error al tratar de consultar a un usuario por id => get_user_by_id');
			return FALSE;
		}
	}

	function get_pin_price($pin = ''){
			$this->db->select('price');
			  $this->db->where('pin', $pin);
			  $result = $this->db->get('pines');
			  if($result->num_rows() > 0)
			  {
			   return $result->result();
			  } 
			  return FALSE;  
		}
// obtiene los créditos del usuario
	function get_user_credit($user_id = ''){
		$this->db->select('credits');
		$this->db->where('id', $user_id);
		$result = $this->db->get('user');
		if($result->num_rows() > 0)
		{
			return $result->result();
		}	
		return FALSE;		
	}

	function get_userwapp_credit($user_id = ''){
		$this->db->select('credits');
		$this->db->where('id_user', $user_id);
		$result = $this->db->get('user_wapp');
		if($result->num_rows() > 0)
		{
			return $result->result();
		}	
		return FALSE;		
	}

	function update_credit_user($user_id = '', $credits = 0){
		$data = array("credits" => $credits);
		$this->db->where('id', $user_id);
		$this->db->update('user', $data); 

		if($this->db->affected_rows() > 0)
		{
			return TRUE;
		}	
		return FALSE;	
	}

	public function insert_user_api($email, $password, $fullname, $verified, $phone, $indi_pais, $first_time, $credits)
	{
		try{
			$this->load->helper('security');
			$pass = do_hash($password); // SHA1
			$pass = do_hash($pass, 'md5'); // MD5
			$data 	= array(
								'email'		=>$email,
								'fullname'	=>$fullname,
								'password'	=>$pass,
								'verified'	=>$verified,
								'phone'		=>$phone,
								'id_country'=>$indi_pais,
								'first_time' => $first_time,
								'credits' => $credits
								// 'is_owner' => $is_owner
							);
			$this->db->set('created', 'NOW()',false);
			$this->db->insert('user', $data); 
			//$result	=	$this->mdb->insert($this->mdb->{$this->mdb->mongo_friendly}->user,$data);
			return  $this->db->insert_id();
		}catch (Erroralingresarnuevo $e){
			return FALSE;
		}
	}

	public function update_user_email($user_id, $email)
	{
		$data = array("email" => $email);
		$this->db->where('id', $user_id);
		$this->db->update('user', $data); 

		if($this->db->affected_rows() > 0)
		{
			return $data;
		}	
		return FALSE;
	}
public function login_by_userid($user_id)
	{
		if(!empty($user_id)){
			$result = $this->db->get_where('user', array('id' => $user_id, 'verified' => 1))->row();
		}else{
			return FALSE;
		}
		
		if(!empty($result))
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}


}