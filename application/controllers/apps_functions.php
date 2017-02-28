<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apps_Functions extends CI_Controller {


	function __construct()
    {
        parent::__construct();
       $this->load->model('apps_model', 'appsmodel');
    }

   
	/* Function: ajaxDeleteApp
     * Función para borrar un aplicación por su id
     *
     * Parameter:
     *     appId - Int Id de la aplicación.
     *
     * Return:
     *      1 - String - la app se eliminó correctamente.
     *      0 - String - Ocurrió un error al tratar de eliminarla app
     *     
     */

    public function ajaxDeleteApp()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('appId', 'Id de la app a eliminar', 'trim|required');

        //valido si los datos requeridos existen.
        if ($this->form_validation->run() == FALSE)
        {
            $aReturn = array(
                                'cod'   =>  0,
                                'messa' =>  'Ocurrió un error al intentar borra la aplicación'
                            );
            echo json_encode($aReturn);
        }
        else
        {

            $appId = $this->input->post('appId');
            $isDeleted   = $this->appsmodel->deleteApp($appId, $this->session->userdata('user_id'));


            if($isDeleted == FALSE)
            {  
            	$aReturn = array(
                                'cod'   =>  0,
                                'messa' =>  'Ocurrió un error al intentar borra la aplicación'
                            );
            }
            else
            {
            	$aReturn = array(
                                'cod'   =>  1,
                                'messa' =>  'La aplicación se eliminó correctamente'
                            );
            }
            echo json_encode($aReturn);
        }
    }

}