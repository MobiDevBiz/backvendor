<?php

/**
 * CRequestModel is a parent class for particular Request models
 *
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */
class CRequestModel extends CFormModel
{  
    
    /**
     * Defines callback for parsing raw request string, CJSON::decode by default
     * @return string 
     */
    public function parseRawRequestCallBack()
    {
        return 'CJSON::decode';
    }
}