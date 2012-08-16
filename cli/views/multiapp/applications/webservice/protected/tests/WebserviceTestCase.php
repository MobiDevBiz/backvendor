<?php

/**
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */

class WebserviceTestCase extends CWebserviceTestCase
{
        public $apiControllerId = 'api';
        
        public $fixtures = false;
        
        protected function setUp()
        {
            //make your set up here ...
            parent::setUp();           
        }

}

?>
