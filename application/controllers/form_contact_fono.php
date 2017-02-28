<?php
 if (!defined('BASEPATH')){ exit('Acceso no autorizado');}
 
class form_contact_fono extends CI_Controller{
 
public function __construct(){
 parent::__construct();
 $this->load->library(array('form_validation'));
 $this->load->helper(array('url','form'));
 $this->load->library('email');
 $this->load->library('parser');
}
 
//carga formulario de contacto
public function index(){
 // venimos de un envio del formulario con errores
 if($this->input->post()){
   // los recuperamos para mostrarlos en el formulario
   $array_datos_contacto = array(
     'asunto'=>$this->input->post('asunto'),
     'email'=>$this->input->post('email'),
     'mensaje'=>$this->input->post('mensaje'),
     'publicidad'=>$this->input->post('publicidad')
   );
 }
 else{
   //inicializamos los campos vacios
   $array_datos_contacto = array(
     'asunto'=>'',
     'email'=>'',
     'mensaje'=>'',
     'publicidad'=>''
   );
 }
 //cargamos la vista del formulario con los campos
 $this->load->view('landing',$array_datos_contacto);
}
 
//valida los datos y envia por email
public function enviar(){
 //validamos los parametros con la libreria de codeigniter
 $this->form_validation->set_rules('asunto', '<i>&quot;Asunto&quot;</i>', 'required|trim|max_length[200]|xss_clean');
 $this->form_validation->set_rules('email', '<i>&quot;Email&quot;</i>', 'required|trim|valid_email|max_length[200]|xss_clean');
 $this->form_validation->set_rules('mensaje', '<i>&quot;Mensaje&quot;</i>', 'required|trim|max_length[200]|xss_clean');
 $this->form_validation->set_rules('publicidad', '<i>&quot;¿Desea suscribirse a nuestra newsletter?&quot;</i>', 'required|trim|max_length[2]|xss_clean');
 if(!$this->form_validation->run()){
   //error en la validacion. volvemos a mostrar el formulario
   $this->index();
 }
 else{
   // parametros correctos. enviamos el mail
   $this->email->clear();
   $config['mailtype'] = "html";
   $this->email->initialize($config);
   $this->email->set_newline("\r\n");
 
   //remitente
   $this->email->from('contacto@miempresa.com', 'Miempresa');
 
   //destinatarios
   $array_emails = array(
     'cfmoreno@kkatoo.com'
   );
   $this->email->to($array_emails);
 
   //datos del formulario
   $array_datos_contacto = array(
     'asunto'=>$this->input->post('asunto'),
     'email'=>$this->input->post('email'),
     'mensaje'=>$this->input->post('mensaje'),
     'publicidad'=>$this->input->post('publicidad')
   );
 
   //parseamos los datos del formulario con la vista del email
   $mail_body = $this->parser->parse('email_view', $array_datos_contacto, true);
 
   //resto de datos para el envio
   $this->email->subject('Formulario de contacto');
   $this->email->message($mail_body);
 
 if ($this->email->send()) {
   //correcto. saltamos a otra url para evitar envios múltiples
   redirect(base_url().'form_contact_fono/gracias','refresh');
 } else {
   //error
   echo 'Se ha producido un error interno.';
 }
}
}
 
&nbsp;
 
//valida los datos y envia por email
 public function gracias(){
 $this->load->view('gracias_view');
 }
 
}
 
?>