<?php
/**
 *  WebserviceLog is a helper class used to log requests/responses
 *  Yii::log is used
 *
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */
class WebserviceLog
{
    public static $apiMethod = 'undefined';
    public static $apiVersion = 'undefined';
    
    /**
     * Logs current Request-Response using Yii::log
     * @param string $request
     * @param string $response
     * @param string $apiMethod 
     */
    public static function __( $request, $response, $apiMethod = null )
    {
        $controller = Yii::app()->controller;
        $response = Response::get();
        $status = $response[ $controller::$configuration['statusKey'] ];
        if( $status == $controller::$configuration['statusError'] )
        {
            $level = 'error';
        }
        else
        {
            $level = 'info';
        }
        
        if( !$apiMethod )
        {
            $apiMethod = self::$apiMethod ;
        }
        
        $http = new CHttpRequest();
        $ip = $http->getUserHostAddress();
        
        Yii::log('Request: '.$request.' ' .
                'Response: '.$response.' ' .
                'API Method: '.$apiMethod.' '.
                'API Version: '.self::$apiVersion.'  '.
                'IP: '.$ip.'  '.
                'Status: '.$status.'  ', 
                $level, 
                'webervice'
                );
    }
}

?>
