<?php

/**
 * 
 * CWebserviceController is a class to be inherited by specific Webservice controllers.
 * 
 * Features:
 * - JSON API engine
 * - API interface versioning
 * - Validators inheritance
 * - AutodocAction that you can add to your webservice controller. It provides web doc based on validation rules
 * 
 * 
 * Client-Server communication format:
 * 1. Gate for all queries is your Webservice Controller. Request is encoded in JSON and put in POST body (or $__POST['request'])
 * 
 * 2. API method request JSON example: 
 * 
 * index.php?r=apiVersion/apiMethod
 * 
 * {"Some_parameter1":"Some_parameter1 Value","Some_parameter2":["Some_parameter2 Value1","Some_parameter2 Value2"]}
 * When this JSON was sent to actionIndex it finds API class by "apiVersion", finds "apiMethod" and validates all other params  by validator (has the same name as apiMethod).
 * 
 * 3. Response example for single call: 
 * Success:
 * {"status":"success","resource":{"responseParam1":"value","responseParam2":"value"}}
 * Error: 
 * {"status":"error","error":{"errorCode":1,"errorDescription":"Server core error","resource":{"error_resource_param":"value"}}}
 * 
 * 
 * 4. Response for multiple call:
 * [{"status":"success","resource":{"responseParam1":"value","responseParam2":"value"}},{"status":"error","error":{"errorCode":1,"errorDescription":"Server core error","resource":{"error_resource_param":"value"}}}]
 * 
 * 
 * -----------------------------------------------------------------------------------------------------------------
 * 
 * To create your webservice you need: 
 * 1. Copy yii-backvendor to your extensions folder
 * 
 * 2. Import all required packages for webservice in protected/config/main.php:
 *              'pathTobackvendor.extensions.*',
 *              'pathTobackvendor.web.webservice.*', 
 * 
 * 3. Create your webservice controller inside controllers folder and inherit it from CWebserviceController
 * 
 * 
 * 4. Create Api functions validators folder. This folder must be located in CWebserviceController::$configuration['apiValidatorsPath']
 *      It must be called like your Api version with "Validators" postfix. E.g. myWebserviceValidators
 *      You can redefine postfix here: CWebserviceController::$configuration['validatorsFolderName']
 * 
 * 5. Create your API function. Create a public function inside your Api class. E.g. actionMyApiFunction.
 * 
 * 6. Create validator class for this function. It must be located in your API version validators folder and called with a same name as your function (in our case myApiFunction)
 *      Your API validator must be inherited from CRequestModel class or it's child. Also in this class you can override parsing rules by parseRawRequestCallBack() method
 * 
 * 7. You can access array of parameters validated by your validator inside your API function by Request::model(). e.g. Request::model()->someParameter
 * 
 * 8. Your API function must call  Response class methods:  error() or success(). E.g.  
 *       Response::success( array('test' => $this->params['parameter'] ) );// for success;
 *       Response::error( ErrorCodes::METHOD_DEPRECATED );// for error. 
 * ErrorCodes is a class container for error codes. You can add your error codes by inheriting this class.
 * 
 * 9. To create a child version of API make a class inside controllers folder and extend it from your current verison. Also create RequestModels folder using the name conventions.
 *      If you want to redefine RequestModel class for a child function - create a validator, if not CWebserviceController will search for a validator in parent API version.
 * 
 * 
 * 10. Example how to customize CWebserviceController::$configuration and to add your custom Error codes and add new error codes to error description dictionary:
 * 
 * public function init()
 *   {
 *       $this->reconfigureWebservice();
 *       $this->reconfigureResponseErrorDictionary();
 *       return parent::init();
 *   }
 *   
 *   private function reconfigureWebservice()
 *   {
 *      self::$configuration = CMap::mergeArray(parent::$configuration, array(      
 *          ...
 *       ));
 *   }
 *   
 *  private function reconfigureResponseErrorDictionary()
 *  {
 *       Response::$errorDictionary =  CMap::mergeArray(Response::$errorDictionary, array(
 *           TestErrorCodes::SOME_CUSTOM_ERROR => 'Your custom error description',
 *       ));
 *   }
 *  
 * 
 * 11. Example of controller api method
 *   
 *   public function actionGetSomething()
 *   {
 *       Response::success( array('sameParameterIGot'=>Request::model()->parameter) );
 *   }
 *    public function actionGetMethodDepricatedError()
 *   {
 *       Response::error( ErrorCodes::METHOD_DEPRECATED );
 *   }
 * 
 * 12. Example of validator for getSomething API function
 * 
 * class getSomething extends CRequestModel
 *{
 *   public $parameter;
 *
 *   public function rules() 
 *   {
 *       return array(
 *               array( 'parameter', 'required' ),
 *           );
 *   }    
 *}
 * 
 * 13. How to add Autodocumentation for API version as an action to your Webservice Controller:
 *     public function actions()
 *   {
 *       return array(
 *           'autodoc' => 'pathToBackvendor.web.webservice.autodoc.AutodocAction',
 *       );
 *   }
 *
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */
class CWebserviceController extends CController
{
    /**
     * @var $layout is set to fasle by default
    **/
    public $layout = false;
    
    /**
    *  @var $configuration Array of configurations
    **/
    public static $configuration = array(        
        
        //Path alias to versions of validation models
        'apiValidatorsPath' => 'application.models',
        
        //validators folder postfix
        'validatorsFolderPostfix' => 'RequestModels',
              
        // POST param key for JSON request
        'requestKey' => 'request',

        //key for response status
        'statusKey'=>'status',
        
        //response success status
        'statusSuccess' => 'success',
        
        //response error status
        'statusError' => 'error',
        
        //response error code key
        'errorCodeKey'=>'errorCode',
        
        //response error body key
        'errorBodyKey'=>'errorBody',
        
        //response error description key
        'errorDescriptionKey'=>'errorDescription',
        
        //error body (resource key)
        'resourceKey' => 'resource',
        );
    
    /**
     * This method defines special actions for which CWebserviceController::beforeAction() will be skipped
     * @return array 
     */
    public function specialActions()
    {
        return array(
            'error',
            );
    }
    
    private $request = '';
    private $testMode = false;
    
    
    public function beforeAction($action)
    {
        if($this->isActionAttachedByActionsMethod($action) || $this->isSpecialAction($action))
        {
            return parent::beforeAction($action);
        }
        try
        {
            $requestModel = $this->createApiValidation($this->getId(), $action->id);
            $postParamsKey = self::$configuration['requestKey'];
            if( empty($this->request) )
            {
                $this->request = (isset($_POST[$postParamsKey])) ? $_POST[$postParamsKey] : file_get_contents('php://input');
            }
            Request::setModel($requestModel);
            Request::setRawRequest($this->request);
            if( Request::validate() )
            {
                return parent::beforeAction($action);
            }
            else
            {
                Response::error( ErrorCodes::VALIDATION_ERROR, Request::model()->getErrors() );
                $this->afterAction($action);
            }
        }
        catch( Exception $e )
        {
            if( $e instanceof CHttpException)
            {
                throw $e;
            }
            $trace = YII_DEBUG ? ' Trace: '.$e->getTraceAsString() : '';
             Response::error( ErrorCodes::SERVER_ERROR, $e->getMessage().$trace );
            $this->renderAndLogResponse();
        }
    }
    
    public function afterAction( $action )
    {
        if($this->isActionAttachedByActionsMethod($action))
        {
            return parent::afterAction($action);
        }
        $this->renderAndLogResponse();
        return parent::afterAction($action);
    }
    
    protected function renderAndLogResponse()
    {
        $wrappedResponse = $this->wrapResponse( Response::get() );
        $this->renderWrappedResponse($wrappedResponse);
        WebserviceLog::$apiMethod = $this->action->id;
        WebserviceLog::$apiVersion = $this->getId();
        WebserviceLog::__( $this->request, $wrappedResponse);        
    }


    public function actionError()
    {
        Response::error( ErrorCodes::API_METHOD_NOT_FOUND);
    }
    
    public function setRequest( $request )
    {
        $this->request = $request;
    }
    
    public function setTestMode( $testMode = true )
    {
        $this->testMode = $testMode;
    }
    
    public function getIsTestMode()
    {
        return $this->testMode;
    }
    
    private function isActionAttachedByActionsMethod($action)
    {
        $actions = $this->actions();
        return isset($actions[$action->id]);
    }
    
    private function isSpecialAction($action)
    {
        $specialActions = $this->specialActions();
        return in_array($action->id, $specialActions);
    }

    /**
    * Creates an instance of validation class (used both for CWebserviceController and AutodocAction )
     * @param $apiVersion string API Verison Name
     * @param $apiMethod string API Method Name
     * @return mixed API validator class. Child of CFormModel.
    */
    public function createApiValidation( $apiVersion, $apiMethod)
    {
        $apiVersionsPath = self::$configuration['apiValidatorsPath'];
        $validatorsFolder = self::$configuration['validatorsFolderPostfix'];
        $allias = $apiVersionsPath.'.'.$apiVersion.$validatorsFolder.'.'.$apiMethod;
        $path = YiiBase::getPathOfAlias( $allias );
        $path .= '.php';
         if( file_exists( $path ) )
         {
            Yii::import($allias);
            $apiValidationName = $apiMethod;
            if(class_exists($apiValidationName) )
            {
                $apiValidation = new $apiValidationName();
                return $apiValidation;
            }
            else
            {
                throw new Exception( '"'.$apiValidationName.'" class does not defined!' );
            }
         }
         else
         {
             $parentApiControllerName = get_parent_class( $this );
             if( is_null($parentApiControllerName) || 'CWebserviceController' == $parentApiControllerName )
             {
                 if( $this->isActionAttachedByActionsMethod($this->action) )
                {
                    throw new Exception( 'Validation file "'.$apiMethod.'.php" is not defined!' );
                }
                else
                {
                    throw new CHttpException(404);
                }        
             }
             else
             {
                 $parentVersion = str_replace('Controller', '', $parentApiControllerName);
                 $parentVersion[0] = strtolower($parentVersion[0]);
                 return $this->createApiValidation($parentVersion, $apiMethod);
             }
         }
    }
    
    private function wrapResponse($response)
    {
        return  CJSON::encode($response);
    }
    
    private function renderWrappedResponse( $wrappedResponse )
    {
        if( !$this->testMode )
        {
            header('Content-type: application/json');
            $this->renderText($wrappedResponse);
        }
    }
}