<?php

/**
 * Request is a static class to work with Request model instance. In your API controller you can access this model by Response::model()
 * You can set model by Request::setModel() only once in CWebserviceController.beforeAction(), do not set it in actions.
 *
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */
class Request 
{
    private static $model = null;
    private static $rawRequest = '';

    /**
     * A setter for raw request string
     * @param string $request 
     */
    public static function setRawRequest( $request = '' )
    {
        // it is posiible to set rawRequest more than one time for test mode only
        if( !empty( self::$rawRequest ) && !Yii::app()->controller->getIsTestMode() )
        {
            throw new Exception(__CLASS__.'::$rawRequest should not be set twice');
        }
        self::$rawRequest = $request;
    }
    
    /**
     * @param CRequestModel $model 
     */
    public static function setModel( CRequestModel $model )
    {
        // it is posiible to set model more than one time for test mode only
        if( !empty( self::$model ) && !Yii::app()->controller->getIsTestMode() )
        {
            throw new Exception(__CLASS__.'::$model should not be set twice');
        }
        self::$model = $model;
    }
    
    /**
     * Method to access request model instance
     * @return CRequestModel 
     */
    public static function model()
    {
        return self::$model;
    }
    
    /**
     * This method parses raw request using Request::$model callback for parsing.
     * @return boolean if validation was successfull
     * @throws Exception 
     */
    public static function validate()
    {
        if(is_null(self::$model) )
        {
            throw new Exception( 'Model was not set, it is not possible to perform validation' );
        }
        else
        {
            $rules = Request::model()->rules();
            if( !empty( $rules ) )
            {
                $params = call_user_func( Request::model()->parseRawRequestCallBack(), self::$rawRequest );
                Request::model()->attributes = $params;
                return Request::model()->validate();
            }
            else
            {
                return true;
            }
        }
    }

}