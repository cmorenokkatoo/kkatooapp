<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * CI_Facebook
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

require_once(APPPATH . 'third_party/facebook/base_facebook.php');
require_once(APPPATH . 'third_party/facebook/facebook.php');

class CI_Facebook extends Facebook
{
	/**
	 * String con URL del API
	 *
	 * @var string
	 * @access private
	 */
	private $_api_url;
	
	/**
	 * String con Key del API
	 *
	 * @var string
	 * @access private
	 */
	private $_api_key;
	
	/**
	 * String con Secret Key del API
	 *
	 * @var string
	 * @access private
	 */
	private $_api_secret;
	
	//private $_errors = array();
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
		$this->_CI->load->config('facebook');
		
		// Asigna configuración a variables de clase
		$this->_api_url 	= $this->_CI->config->item('facebook_api_url');
		$this->_api_key 	= $this->_CI->config->item('facebook_app_id');
		$this->_api_secret 	= $this->_CI->config->item('facebook_api_secret');
		
		// Configuración para biblioteca externa
		$config = array(
	  		'appId'  => $this->_api_key,
			'secret' => $this->_api_secret
		);
		
		parent::__construct($config);
	}
	

}