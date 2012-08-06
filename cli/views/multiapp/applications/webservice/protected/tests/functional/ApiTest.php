<?php

/**
 * To run this test just follow:
 * cd /path-to-your-project/applications/webservice/protected/tests
 * phpunit functional/ApiTest.php
 * 
 * you can run test for one funciton only using --filter parameter
 * phpunit --filter testGetSuccess functional/ApiTest.php
 * 
 * 
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */

class ApiTest extends WebserviceTestCase
{
    public function testGetSuccess()
    {
        $expectedValue = 'some value';
        $response = $this->callWebservice('getSuccess', array(
            'parameter' => $expectedValue,
        ));

        $resource = $response[ self::$apiControllerConfig['resourceKey'] ];
        $this->assertEquals( 'success', $response['status'] );
        $this->assertArrayHasKey( 'parameter', $resource );
        $this->assertEquals( $expectedValue, $resource['parameter'] );
    }
    
    public function testGetError()
    {
        $validationFailedResponse = $this->callWebservice('getError', array(
        ));
        $this->assertEquals( 'error', $validationFailedResponse['status'] );
        $this->assertEquals( ApiErrorCodes::VALIDATION_ERROR, $validationFailedResponse['error']['errorCode'] );
        
        
        $response = $this->callWebservice('getError', array(
            'parameter' => 'ololo',
        ));
        $this->assertEquals( 'error', $response['status'] );
        $this->assertEquals( ApiErrorCodes::MY_CUSTOM_ERROR, $response['error']['errorCode'] );
    }
     
}
?>
