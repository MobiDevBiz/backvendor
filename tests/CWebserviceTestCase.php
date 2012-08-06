<?php

/**
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */
class CWebserviceTestCase extends CDbTestCase
{
        public $apiControllerId = '';
    
        protected $fixtures = false;
        
        public $testBaseUrl = '';
        
        public static $apiControllerConfig = array();
        
        protected $webserviceController = null;
        
        protected function callWebservice( $apiAction = 'index' , $params = null )
        {
            if( !$this->webserviceController )
            {
                $controller = Yii::app()->createController( $this->apiControllerId );
                $this->webserviceController = $controller[0];
                $this->webserviceController->init();
                self::$apiControllerConfig = $controller[0]::$configuration;
            }
            $this->webserviceController->setRequest( CJSON::encode($params) );
            $this->webserviceController->setTestMode(true);
            Yii::app()->setController($this->webserviceController);
            Yii::app()->controller->run($apiAction);  
            $response = Response::get();     
            $this->assertArrayHasKey( self::$apiControllerConfig['statusKey'], $response  );
            return $response;
        }

}