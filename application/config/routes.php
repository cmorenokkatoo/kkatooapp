<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';  
|
| This route indicates which controller class should be loaded if the 
| URI contains no data. In the above example, the "welcome" class 
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "site";  
$route['404_override'] = ''; 
$route['apps/(?!add_contact|voice_view|get_contacts|add_contact_campaign|delete_contact_campaign|add_audio_campaign|add_audio_record|add_date_campaign|get_contacts_campaign|get_city|add_name_campaign|batch_contact_campaign|upload_audio_campaign_ini|get_audio|add_tree_audio_text|get_audios_all|generate_marcado|get_number_tree|add_tree_upload_audio|add_tree_record_audio|delete_all_campaign|request_profesional_audio|add_content_to_main_campaign|delete_content_from_tree_campaign|add_content_to_tree_campaign|save_intro|save_close|add_all_contacts_to_campaing).*'] = "apps/index/$1"; 
$route['landing/(?!save_contact_suscription|save_contact_extra_data|add_credit_extra_data|create_price_by_package_suscription|ini_pay_suscribe|get_city|check_value_simulator|ajax_send_opservations|ajax_change_status).*'] = "landing/index/$1";
$route['wizard/(?!upload_img|save_info_app|newapp|delete_dynamic|sanitize_text|ajax_save_text_speach|ajax_delete_text_speech|upload_audio|ajax_delete_audio|ajax_update_audio_name|add_audio_record|ajax_get_audio_recorded_data|ajax_delete_batch|ajax_intro_cierre_update|ajax_add_package|ajax_remove_package|crear_aplicacion_uris).*'] = "wizard/index/$1";
$route['user/(?!fake).*'] = "user/index/$1";
// $route['conctacts/(:any)']= 'contacts/index/$1';
// $route['contacts/generate_csv/90'] = "contacts/generate_csv/90";

//$route['payment/(?!show_commission_for_kredits).*'] = "payment/index/$1";
$route['marketplace/(?!get_by_id_app|set_comment_app|search|point_app).*'] = "marketplace/index/$1"; 

//ROUTES APPMANAGER
$route['appmanager/(:any)'] = 'appmanager/index/$1';
$route['appmanager/apply_filter']							= 'appmanager/apply_filter';
$route['appmanager/add_credits']							= 'appmanager/add_credits';
$route['appmanager/remove_user_from_diffusion_pin']			= 'appmanager/remove_user_from_diffusion_pin';
$route['appmanager/add_earnings_to_my_credits']				= 'appmanager/add_earnings_to_my_credits';
$route['appmanager/init_redeem_earnings_by_transaction'] 	= 'appmanager/init_redeem_earnings_by_transaction';


//ROUTES ADMIN
$route['admin/(:any)'] = 'admin/index/$1';
$route['queues/(:any)'] = "queues/index/$1";
//$route['queues/set_queues_call'] = "queues/set_queues_call";

/* End of file routes.php */
/* Location: ./application/config/routes.php */