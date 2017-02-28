<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Class: llamadas.php
 * Control para el control de llamadas por url.
 *
 * Author: 
 * 
 * Locacion: controllers/llamadas.php
 */
class llamadas extends CI_Controller {
	/* Function: __construct
     * Constructor del control login carga el modelo login_model desde el principio para realizar validaciones correspondientes
     * 
     *
     * Parameter:
     *      
     *
     * Return:
     * 		
     *		
     */
	public function __construct()
	{
		parent::__construct();
	    // $this->load->model('queues_model');
				
	}
	
	
	
	 /* Function: index
     * 
     * 
     *
     * Parameter:
     *      
     *
     * Return:
     * 		
     *		
     */
	public function index()
	{
        $telefono= $this->input->get_post("tel");
        $el_texto= $this->input->get_post("texto");
        $la_hora= $this->input->get_post("hora");

        echo "esto recibo: tel = " . $telefono . " texto = " . $el_texto . "  hora = " . $la_hora;
	}
}