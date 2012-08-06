<?php

/**
 *
 * AutodocAction renders API docs for webservice controller it is attached to
 *
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */
class AutodocAction extends CAction
{
    public function run()
    {
        $controller = $this->getController();
        
        if( YII_DEBUG )
        {
            $controller->renderInternal( dirname(__FILE__).'/views/autodoc.php', array(
                    'requestFormatSingle' => $this->getRequestFormaSingle(),
                    'responseSuccess' => $this->getSuccessResponse(),
                    'responseError' => $this->getErrorResponse(),
                    'apiVersionDetails' => $this->getApiVersionDetails(),
                    'errorDictionary' => Response::$errorDictionary,
                    'assetPath' => Yii::app()->assetManager->publish( dirname(__FILE__).DIRECTORY_SEPARATOR.'assets' ),
                    'webserviceConfig' => $controller::$configuration,
                    ));
        }
        else
        {
            $controller->renderText('Access denied');
        }
    }
    
    protected function getRequestFormaSingle()
    {
        $request = array(
            'Some_parameter1' => 'Some_parameter1 Value',
            'Some_parameter2' => array( 'Some_parameter2 Value1', 'Some_parameter2 Value2'),
        );
        return " index.php?r=apiVersion/apiMethod <br><br>".CJSON::encode($request);
    }
    
    protected function getSuccessResponse()
    {
        return CJSON::encode(   Response::success(array( 'responseParam1' => 'value', 'responseParam2' => 'value' ) ) );
    }
    
    protected function getErrorResponse()
    {
        return CJSON::encode( Response::error(1, array( 'error_resource_param' => 'value' ) ) );
    }
    
    protected function getMultipleResponse()
    {
        return CJSON::encode( array(
            Response::success(array( 'responseParam1' => 'value', 'responseParam2' => 'value' ) ),
            Response::error(1, array( 'error_resource_param' => 'value' ) ) ,
        ) );
    }
   
    
    protected function getApiVersionDetails(  )
    {
        $controller = Yii::app()->controller;
        $version = $controller->getId();
        if( !is_null($version) )
        {
             $apiFunctions = $this->getApiFunctions( $version );
             if( $apiFunctions )
             {
                 $result = array();
                 foreach( $apiFunctions as $function )
                 {
                     $result[$function] = $this->getApiFunctionParameters($version, $function);
                 }
                 return $result;
             }
             else
             {
                 return null;
             }
        }
        else
        {
            return null;
        }      
    }
    
    protected function getApiFunctionParameters( $version, $function )
    {
        $controller = $this->getController();
        $result = '';
        $validation = $controller->createApiValidation($version, $function);
        $validationRules = $validation->rules();
        foreach ( $validationRules as $item )
        {
            $validString = '';
            $isParam = true;
            foreach($item as $key=>$subitem)
            {
                if(is_string($key) )
                {
                    $validString .= $key.' => ';
                }
                
                if( $isParam )
                {
                    $subitem = '<b>'.$subitem.'</b>';
                }
                $validString .= $subitem.'  ';
                $isParam = false;
            }
            $result .= $validString.'<br>';
        }
        
        
        return $result;
    }
    
    protected function getApiFunctions( )
    {
        $controller = $this->getController();
        $apiControllerName = $controller->getId();
        Yii::import( $controller::$configuration['apiValidatorsPath'].'.'.$apiControllerName.'*' );
        $allFunctions = get_class_methods($controller);
        $specialActions = $controller->specialActions();
        foreach($allFunctions as $key=>$function)
        {
            if($function == 'actions' || str_replace('action', '', $function) == $function )
            {
                unset($allFunctions[$key]);
            }
            else
            {
                $allFunctions[$key] = str_replace('action', '', $function);
                $allFunctions[$key][0] = strtolower($allFunctions[$key][0]);
                 if(in_array($allFunctions[$key], $specialActions) )
                {
                    unset($allFunctions[$key]);
                }
            }
           
        }
        return $allFunctions;
        
    }
}