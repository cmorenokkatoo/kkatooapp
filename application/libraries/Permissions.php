<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Permissions {
	private $_permissions = array();
	
    public function set($name='', $val=''){
		if(is_array($name)){
			foreach($name as $key => $value){
				if(!empty($value)){
					$this->_permissions[$key] = $value;
				}
			}
		}else if(is_string($name)){
			if(!empty($name)){
				$this->_permissions[$name] = $val;
			}
		}
    }
	
	public function get($name=''){
		if(!empty($name)){
			if(isset($this->_permissions[$name])){
				return $this->_permissions[$name];
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	public function get_all(){
		if(!empty($this->_permissions)){
			
			return $this->_permissions;
		}else{
			return FALSE;
		}
	}
}