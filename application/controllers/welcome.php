<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{	
		header('Content-Type: text/html; charset=utf-8');

		$string = ('hola comó estós');
		
		$aux 	=	json_encode($string);
		$ph 	= 	json_decode($aux);
		
		echo var_dump($ph);
	}
	public function main()
    {

    $this->load->view('phpinfo');

    }
	
	public function prueba()
	{
		
	}
}