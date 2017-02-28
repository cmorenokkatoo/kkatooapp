<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['mailpath']     = "/usr/sbin/sendmail";    // Sendmail path
$config['protocol']		= 'sendmail';
$config['_smtp_auth'] 	= TRUE;
$config['smtp_host'] 	= 'ssl://smtp.gmail.com';
$config['smtp_port'] 	= '465';
$config['smtp_timeout'] = '7';
$config['smtp_user'] 	= 'info@kkatoo.com'; //info@kkatoo.com
$config['smtp_pass'] 	= 'valesalazar12345'; //valesalazar12345
$config['charset'] 		= 'utf-8';
$config['newline'] 		= "\r\n";
$config['mailtype'] 	= 'html'; 
$config['validation'] 	= FALSE;

