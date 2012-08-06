<?php

/**
 * This is example request model, remove this file
 *
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */

class getSuccess extends CRequestModel
{
    public $parameter;

    public function rules() 
    {
        return array(
                array( 'parameter', 'required' ),
            );
    }
    
    //You can redefine common parsing rule by setting other callback. This callback must have one parameter - raw incoded string
    public function parseRawRequestCallBack() 
    {
        return 'CJSON::decode';
    }
}