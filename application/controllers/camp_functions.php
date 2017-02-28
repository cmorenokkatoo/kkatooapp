<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Camp_Functions extends CI_Controller {


	function __construct()
    {
        parent::__construct();
       $this->load->model('campaign_model');
    }

   
	/* Function: ajaxDeleteCamp
     * Función para borrar una lista de campañas especificiadas por sus ids
     *
     * Parameter:
     *     id_campaign - Array json con los Ids de las campañas a borrar.
     *
     * Return:
     *      1 - String - la camp se eliminó correctamente.
     *      0 - String - Ocurrió un error al tratar de eliminarla camp
     *     
     */

    public function ajaxDeleteCamp()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_campaign', 'Id de la campaña a eliminar', 'trim|required');

        //valido si los datos requeridos existen.
        if ($this->form_validation->run() == FALSE)
        {
            $aReturn = array(
                                'cod'   =>  0,
                                'messa' =>  'Ocurrió un error al intentar borra la campaña'
                            );
            echo json_encode($aReturn);
        }
        else
        {

            //Convertimos el json en un array php
            $arrayCampaigns  = json_decode($this->input->post('id_campaign'), true);

            //Enviamos el array de ids al modelo, junto con el id del usuario en sesión.
            $isDeleted   = $this->campaign_model->deleteCamp($arrayCampaigns, $this->session->userdata('user_id'));

            //Si ocurrió un error al borrar.
            if($isDeleted == FALSE)
            {  
            	$aReturn = array(
                                'cod'   =>  0,
                                'messa' =>  'Ocurrió un error al intentar borra la campaña'
                            );
            }
            else
            {
                //Si se borraron las campañas exitosamente
            	$aReturn = array(
                                'cod'   =>  1,
                                'messa' =>  'La campaña se eliminó correctamente'
                            );
            }
            echo json_encode($aReturn);
        }
    }

}