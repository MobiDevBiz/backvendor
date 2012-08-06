<?php

/**
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */

class AdminIdentity extends CUserIdentity
{
    private static $hashSecret = 'fw8JBwm2f';
    
    
    private $_id;
    public $isSuperadmin;
    public function authenticate()
    {
        $record=Admin::model()->findByAttributes(array('a_login'=>$this->username));
        if($record===null)
        {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        }
        else if($record->a_password!== self::hashPassword($this->password) )
        {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        }
        else
        {
            $this->_id=$record->id;
            $this->setState('_admin_title', $record->name);
            $this->setState('superadmin', ($record->super_admin==0)?false:true);
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }
    
    public  static function hashPassword( $password )
    {
        return hash_hmac('ripemd160', $password, self::$hashSecret);
    }
    
    
     
}


?>
