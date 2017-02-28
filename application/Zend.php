<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Zend
{
    /**
     * Include the Zend library once
     * we load the zend Library class
     *
     * @param   none
     * @return  none
     */
    function __construct(){
        ini_set('include_path',
        ini_get('include_path') . PATH_SEPARATOR . APPPATH . 'libraries');

    }

    /**
     * Load Zend Library
     *
     * @param   string $class
     * @return  object $class
     */
    function load($class){
        require_once (string) $class . EXT;
    }
}
/* End of Zend.php */
?>