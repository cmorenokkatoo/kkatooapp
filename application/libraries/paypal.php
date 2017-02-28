<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Paypal {
	
	public $CI;
	
	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	public function paypal_make_payment($invoicenumber, $price, $productname,$discount=0)
	{

			$order_id = str_replace("INV-", '', $invoicenumber);
			
			
			$this->CI->load->library('paypal_class');
			$buyNow =  $this->CI->paypal_class;
			//$this->CI->paypal_class->testing();
			$buyNow->addVar('business',PAYPAL_EMAIL_ADDRESS);	/* Payment Email */
			$buyNow->addVar('cmd','_xclick');
			$buyNow->addVar('cmd','_cart');
			$buyNow->addVar('upload','1');
			

			
			$buyNow->addVar('item_name_1',$productname);
			$buyNow->addVar('amount_1',$price);
			$buyNow->addVar('quantity_1','1');

			$buyNow->addVar('return',urlencode($this->CI->config->site_url()) .'quote%2Fprosesorder%2F'.$order_id );
			

			$buyNow->addVar('discount_amount_1',$discount);
			$buyNow->addVar('invoice',$invoicenumber);
			$buyNow->addVar('tax_1',(round(round((($price-$discount)* 0.1),2),2)));			
			$buyNow->addVar('currency_code','AUD');		
			$buyNow->addVar('rm','2');			
			
			/* Paypal IPN URL - MUST BE URL ENCODED */
			$buyNow->addVar('notify_url',urlencode($this->CI->config->site_url()) .'payment%2Fipn_validate');	
			Header("Location:".$buyNow->getLink());
			echo '<a href="'.$buyNow->getLink().'">Click Here if you are not redirected to paypal</a>';
	}
	public function generatepaypallink($invoicenumber, $price, $productname,$discount=0)
	{
	$order_id = str_replace("INV-", '', $invoicenumber);
				
			$this->CI->load->library('paypal_class');
			$buyNow =  $this->CI->paypal_class;
			//$this->CI->paypal_class->testing();
			$buyNow->addVar('business',PAYPAL_EMAIL_ADDRESS);	/* Payment Email */
			$buyNow->addVar('cmd','_xclick');
			$buyNow->addVar('cmd','_cart');
			$buyNow->addVar('upload','1');
			

			
			$buyNow->addVar('item_name_1',$productname);
			$buyNow->addVar('amount_1',$price);
			$buyNow->addVar('quantity_1','1');

			$buyNow->addVar('return',urlencode($this->CI->config->site_url()) .'quote%2Fprosesorder%2F'.$order_id );
			

			$buyNow->addVar('discount_amount_1',$discount);
			$buyNow->addVar('invoice',$invoicenumber);
			$buyNow->addVar('tax_1',(round(round((($price-$discount)* 0.1),2),2)));			
			$buyNow->addVar('currency_code','AUD');		
			$buyNow->addVar('rm','2');			
			
			/* Paypal IPN URL - MUST BE URL ENCODED */
			$buyNow->addVar('notify_url',urlencode($this->CI->config->site_url()) .'payment%2Fipn_validate');
			
			return $buyNow->getLink();

	}
	


}
?>