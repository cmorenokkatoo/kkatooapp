<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| Tipos de variables para el Token
|--------------------------------------------------------------------------
*/
define('KT_NEW_REGISTER_TOKEN',1);
define('KT_RESET_PASSWORD_TOKEN',2);
/* End of file constants.php */
/* Location: ./application/config/constants.php */


/**
* CONSTANTES DE KKATOO
*/
/* Id de usuario de kkatoo */
define('KKATOO_USER', 570);
define('KKATOO_TESTER', 570);
define('IDS_USER_GLOBAL_APP', '570');

/* porcentajes de ganancias */
define('KKATOO_PERCENT', 0.4);
define('KKATOO_USER_PERCENT', 0.6);
// define('KKATOO_COMISION', 120);

/** Emails de kkatoo **/
define('KKATOO_EMAIL_INFO', 'info@kkatoo.com');

/** Constante Kkatoo root */
define('KKATOO_ROOT', '.mensajesdevoz.co');

/** Constante Kkatoo root */
define('BOUNCEDX', 'bouncedx');
define('BOUNCED', 'bounced');
define('DELIVEREDX', 'deliveredx');
define('DELIVERED', 'delivered');
define('ACTIVEX', 'activex');
define('ACTIVE', 'active');
define('ACTIVEX2', 'activex2');
define('ACTIVE2', 'active2');
define('ACTIVEX3', 'activex3');
define('ACTIVE3', 'active3');
define('ACTIVEX4', 'activex4');
define('ACTIVE4', 'active4');
define('ACTIVEX6', 'activex6');
define('ACTIVE6', 'active6');
define('DIRECT', 'direct');

/** Minimo a pagar por una persona */
define('MINIMUN', 20000);
define('MINIMUN_EARNINGS_TO_PAY', 20); /* mínimo de ganancias para canjear


/**Variables de pagos*/
define('PAYMENT_CURRENCY', 'COP');
define('PAYPAL', 'paypal');
define('PAGOSONLINE', 'pagosonline');
define('PAGOSONLINE_ENCRIPTION_KEY', '');
define('PAGOSONLINE_ENCRIPTION_TESTING_KEY', '');
define('PAGOSONLINE_USER_ID', '');
define('PAGOSONLINE_TESTING', 0);
define('PAGOSONLINE_URL', 'https://gateway.payulatam.com/ppp-web-gateway/');

/** TIPOS DE PAGOS AL USUARIO **/
define('RA', 'RECHARGE_BY_APP');
define('RU', 'RECHARGE_BY_USER');
define('TA', 'TRANSACTION_BY_APP');
define('TU', 'TRANSACTION_BY_USER');


/***PAGINATION***/
define('PAGINATION', 25);