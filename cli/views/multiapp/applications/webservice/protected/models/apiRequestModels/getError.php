<?php

/**
 * This is example request model, remove this file
 *
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */

class getError extends CRequestModel
{
    public $parameter;

    public function rules() 
    {
        return array(
                array( 'parameter', 'required' ),
            );
    }    
}