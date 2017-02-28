<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['mailpath']     = "/usr/sbin/sendmail";    // Sendmail path
$config['protocol']		= 'smtp';
$config['_smtp_auth'] 	= true;
$config['smtp_host'] 	= 'server.amigaslive.net';
$config['smtp_port'] 	= '25';
$config['smtp_timeout'] = '7';
$config['smtp_user'] 	= 'info'; //info@kkatoo.com
$config['smtp_pass'] 	= 'kkatoo2016'; //valesalazar12345
//$config['smtp_user'] 	= 'development@domoti-sas.com'; //info@kkatoo.com
//$config['smtp_pass'] 	= 'domotidomoti'; //valesalazar12345
$config['charset'] 		= 'utf-8';
$config['newline'] 		= "\r\n";
$config['mailtype'] 	= 'html';
$config['validation'] 	= FALSE;

$config['protocol']    = 'smtp';
$config['smtp_host']    = 'ssl://smtp.gmail.com';
$config['smtp_port']    = '465';
$config['smtp_timeout'] = '7';
$config['smtp_user']    = 'info@kkatoo.com';
$config['smtp_pass']    = 'valesalazar12345';
$config['charset']    = 'utf-8';
$config['newline']    = "\r\n";
$config['mailtype'] = 'html'; // or html
$config['validation'] = TRUE; // bool whether to validate email or not      

$config['_smtp_auth'] 	= TRUE;
