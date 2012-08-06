<?php

/**
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */

class ApiController extends CWebserviceController
{
    public function actions()
    {
        return array(
            'autodoc' => 'core.extensions.yii-backvendor.web.webservice.autodoc.AutodocAction',
        );
    }
    
    public function init()
    {
        Response::$errorDictionary =  CMap::mergeArray(Response::$errorDictionary, array(
            ApiErrorCodes::MY_CUSTOM_ERROR => 'My Custom Error Description',
            ApiErrorCodes::LOGIN_FAILED => 'Incorrect login and password Correct: chuck@norris.com qwerty ',
        ));
        return parent::init();
    }
    
    /*
     * All actions below are just examples... Please remove it before creating your own actions:
     */
    
    public function actionGetSuccess()
    {
        // A mirror funciton that returns what it got, just for example
        return Response::success( array('parameter' => Request::model()->parameter) );
    }
    
    public function actionGetError()
    {
        //renders your custom error
        return Response::error( ApiErrorCodes::MY_CUSTOM_ERROR );
    }
    
}