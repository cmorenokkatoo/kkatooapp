<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * CI_Google
 *
 * Extiende la biblioteca CI_Session para el manejo de sesiones en MongoDB
 * 
 *
 * @package		Kkatoo
 * @subpackage		Libraries
 * @author		Kkatoo
 * @version		0.0.1
 */

/*
 * Importa archivos para biblioteca externa para conexión con Facebook
 * Más información: https://github.com/facebook/facebook-php-sdk
 * 
 */


class CI_Google_edwin
{
	/**
	 * String Client ID
	 *
	 * @var string
	 * @access private
	 */
	private $client_id;
	
	/**
	 * String con Client Secret
	 *
	 * @var string
	 * @access private
	 */
	private $client_secret;
	
	/**
	 * String con la URL de redirección
	 *
	 * @var string
	 * @access private
	 */
	private $redirect_uri;
	
	/**
	 * Int con el tamaño de resultados pedidos a google
	 *
	 * @var string
	 * @access private
	 */
	private $max_results = 1000;
	//private $_enable_debug = FALSE;
	
	/**
	 * Constructor
	 *
	 * Inicializa clase
	 * 
	 */
	function __construct()
	{
		// Asigna objeto CodeIgniter a variable local para ser utilizado
		// en toda la clase
		$this->_CI =& get_instance();

		// Carga configuración
		$this->_CI->load->config('google_edwin');
		
		// Asigna configuración a variables de clase
		$this->client_id 	= $this->_CI->config->item('client_id');
		$this->client_secret 	= $this->_CI->config->item('client_secret');
		$this->redirect_uri 	= $this->_CI->config->item('redirect_uri');
		$this->max_results 	= $this->_CI->config->item('max_results');
		
		//parent::__construct($config);
	}
	
	function change_to_user(){
		$this->client_id 	= $this->_CI->config->item('client_id');
		$this->client_secret 	= $this->_CI->config->item('client_secret');
		$this->redirect_uri 	= $this->_CI->config->item('redirect_uri');
		$this->max_results 	= $this->_CI->config->item('max_results');
	}
	
	function change_to_contacts(){
		$this->client_id 	= $this->_CI->config->item('client_id_contactos');
		$this->client_secret 	= $this->_CI->config->item('client_secret_contactos');
		$this->redirect_uri 	= $this->_CI->config->item('redirect_uri_contactos');
		$this->max_results 	= $this->_CI->config->item('max_results_contactos');
	}
	
	function get_url_connect($tipo = "usuario"){
		if($tipo == "contactos"){
			$this->change_to_contacts();
		}else{
			$this->change_to_user();
		}
		return 'https://accounts.google.com/o/oauth2/auth?client_id='.$this->client_id.'&redirect_uri='.$this->redirect_uri.'&scope=https://www.google.com/m8/feeds/&response_type=code';
	}
	
	function get_user(){
		$this->change_to_user();
		$this->_CI =& get_instance();
		if($this->_CI->input->get("code")){
			$auth_code = $this->_CI->input->get("code");
			$fields=array(
				'code'=>  urlencode($auth_code),
				'client_id'=>  urlencode($this->client_id),
				'client_secret'=>  urlencode($this->client_secret),
				'redirect_uri'=>  urlencode($this->redirect_uri),
				'grant_type'=>  urlencode('authorization_code')
			);
			$post = '';
			
			foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
			$post = rtrim($post,'&');
			
			$curl = curl_init();
			curl_setopt($curl,CURLOPT_URL,'https://accounts.google.com/o/oauth2/token');
			curl_setopt($curl,CURLOPT_POST,5);
			curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
			$result = curl_exec($curl);
			curl_close($curl);
			
			$response =  json_decode($result);
			$accesstoken = $response->access_token;
			
			$url = 'https://www.google.com/m8/feeds/contacts/default/full?&max-results='.$this->max_results.'&alt=json&oauth_token='.$accesstoken;
			$xmlresponse =  $this->_curl_file_get_contents($url);
			if((strlen(stristr($xmlresponse,'Authorization required'))>0) && (strlen(stristr($xmlresponse,'Error '))>0))
			{
				return false;
			}
			$temp = json_decode($xmlresponse,true);
			$email = $temp['feed']["id"]['$t'];
			$nombre = "";
			foreach($temp['feed']['entry'] as $cnt) {
				$nombre_f = $cnt['title']['$t'];
				if($nombre_f!= ""){
					if(isset($cnt['gd$email'])){
						$cnt['title']['$t'];
						$email_f = $cnt['gd$email']['0']['address'];
						if( $email_f == $email){
							$nombre = $nombre_f;
						}
					}
				}
			}
			
			return array("nombre"=>$nombre,"email"=>$email);
		}
	}
	
	
	function get_contacts(){
		$this->change_to_contacts();
		$this->_CI =& get_instance();
		if($this->_CI->input->get("code")){
			$auth_code = $this->_CI->input->get("code");
			$fields=array(
				'code'=>  urlencode($auth_code),
				'client_id'=>  urlencode($this->client_id),
				'client_secret'=>  urlencode($this->client_secret),
				'redirect_uri'=>  urlencode($this->redirect_uri),
				'grant_type'=>  urlencode('authorization_code')
			);
			$post = '';
			
			foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
			$post = rtrim($post,'&');
			
			$curl = curl_init();
			curl_setopt($curl,CURLOPT_URL,'https://accounts.google.com/o/oauth2/token');
			curl_setopt($curl,CURLOPT_POST,5);
			curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
			$result = curl_exec($curl);
			curl_close($curl);
			
			$response =  json_decode($result);
			$accesstoken = $response->access_token;
			
			$url = 'https://www.google.com/m8/feeds/contacts/default/full?&max-results='.$this->max_results.'&alt=json&oauth_token='.$accesstoken;
			$xmlresponse =  $this->_curl_file_get_contents($url);
			if((strlen(stristr($xmlresponse,'Authorization required'))>0) && (strlen(stristr($xmlresponse,'Error '))>0))
			{ 
				return false;
			}
			$temp = json_decode($xmlresponse,true);
			$contacts = array();
			foreach($temp['feed']['entry'] as $cnt) {
				if($cnt['title']['$t']!= ""){
					$nombre = "";
					$email = "";
					$tel = "";
					
					$nombre = $cnt['title']['$t'];
					
					if(isset($cnt['gd$email'])) $email =  $cnt['gd$email']['0']['address'];
					if(isset($cnt['gd$phoneNumber'])) $tel = $cnt['gd$phoneNumber'][0]['$t'];
					
					$contacts[] = array("nombre"=>$nombre, "email"=>$email, "telefono"=>$tel);
				}
			}
			
			return $contacts;
		}
	}
	
	function _curl_file_get_contents($url){
		 $curl = curl_init();
		 $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
		 curl_setopt($curl,CURLOPT_URL,$url);
		 curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);
		 curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);
		 curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
		 //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		 curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
		 curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		 curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		 
		 $contents = curl_exec($curl);
		 curl_close($curl);
		 return $contents;
	}
	

}