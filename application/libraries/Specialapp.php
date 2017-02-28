<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Specialapp {
	private $_apps = array();
	
    public function set($name='', $val=''){
		if(is_array($name)){
			foreach($name as $key => $value){
				if(!empty($value)){
					$this->_apps[$key] = $value;
				}
			}
		}else if(is_string($name)){
			if(!empty($name)){
				$this->_apps[$name] = $val;
			}
		}
    }
	
	public function get($name=''){
		if(!empty($name)){
			if(isset($this->_apps[$name])){
				return $this->_apps[$name];
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	public function get_all(){
		if(!empty($this->_apps)){
			return $this->_apps;
		}else{
			return FALSE;
		}
	}
	
	public function create_logo($img = "logo-main-header.png"){
		$data = (object)array("brand_url" => '', "brand_title" => '', "brand_img" => '');
		if($this->get('private')){
			if($this->get('url_landing')){
				$data->brand_url = $this->get('url_landing');
			}else{
				$data->brand_url = base_url('landing/'.$this->get('uri'));
			}
			$data->brand_title = $this->get('title');
			if($this->get('logo')){
				$data->brand_img = base_url('public/'.$this->get('logo'));
			}else if($this->get('image')){
				$data->brand_img = base_url('public/'.$this->get('image'));
			}else{
				$data->brand_img = base_url('assets/img/'.$img);
			}
		}else{
			$data->brand_url = base_url(); $data->brand_img = base_url('assets/img/'.$img); $data->brand_title = "kkatoo, social dialing";
		}
		return $data;
	}
}