<?php
/**
 *
 * Response is a class helping to format your response
 * Usage in API classes: 
 * 
 * If you want your Webservice Controller to render JSON like 
 * {"status":"success","resource":{"myKey":"myValue"}}
 * Use
 *  Response::success( array('myKey' => 'myValue' ) );
 * 
 * If you want to return an error with description set in Response::$errorDictionary like
 * {"status":"error","error":{"errorCode":1,"errorDescription":"Server core error","resource":{"error_resource_param":"value"}}}
 * Use
 * Response::error( ErrorCodes::SERVER_ERROR, array("error_resource_param" => "value" );
 *
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 * 
 */
class Response
{
    private static $response = null;
    
    /**
     *
     * @var array of Error codes => descriptions. Can be merged by custom errors in your Webservice Controller 
     */
    public static $errorDictionary = array(
        ErrorCodes::SERVER_ERROR => 'Server core error',
        ErrorCodes::VALIDATION_ERROR => 'Validation failed',
        ErrorCodes::METHOD_DEPRECATED => 'This method is deprecated in current API version',
        ErrorCodes::EMPTY_RESPONSE => 'This API function returned NULL, please tell web developer to fix it',
        ErrorCodes::API_METHOD_NOT_FOUND => '404 - API Method not found',
    );
    
    /**
     * Static function for wrapping success responses
     * Configurations that can influence on it:
     *  $controller::$configuration['statusKey'] = 'status' by default
     * $controller::$configuration['statusSuccess'] = 'success' by default
     * $controller::$configuration['resourceKey'] = 'resource' by default
     * @param array $successResponse (null by default) Can be added to specify Body (resource) of response.
     * @return array  prepeared to be JSON encoded
     */
    public static function success($successResponse = null)
    {
        $controller = Yii::app()->controller;
        $result = array($controller::$configuration['statusKey'] => $controller::$configuration['statusSuccess']);
        $result += array($controller::$configuration['resourceKey'] => $successResponse);
        self::$response = $result;
        return self::get();
    }

    /**
     *  $controller::$configuration['statusKey'] = 'status' by default
     * $controller::$configuration['statusError'] = 'error' by default
     * $controller::$configuration['resourceKey'] = 'resource' by default
     * $controller::$configuration['errorDescriptionKey'] = 'errorDescription' by default
     * @param string $errorCode is a key of Response::$errorDictionary array.
     * @param array $errorresource null by default. Can be added to specify error body (resource) of response
     * @return array  prepeared to be JSON encoded 
     */
    public static function error($errorCode, $errorresource=null)
    {
        $controller = Yii::app()->controller;
        
        $errorDescription = self::errorDescription($errorCode);

        self::$response = array(
            $controller::$configuration['statusKey'] => $controller::$configuration['statusError'],
            $controller::$configuration['statusError'] => array(
                $controller::$configuration['errorCodeKey'] => $errorCode,
                $controller::$configuration['errorDescriptionKey'] => $errorDescription,
                $controller::$configuration['resourceKey'] => $errorresource
            )
        );
        return self::get();
    }
    
    /**
     * Method that returns current response array.
     * @return array 
     */
    public static function get()
    {
        if( empty(self::$response) )
        {
            Response::error( ErrorCodes::EMPTY_RESPONSE );
        }
        return self::$response;
    }

    protected static function errorDescription($errorCode)
    {
        if( isset(self::$errorDictionary[$errorCode]) )
        {
            return self::$errorDictionary[$errorCode];
        }
        else
        {
            return 'Error unknown to error dictionary. Please kick your developers to make them define it';
        }
    }

    

}

?>
