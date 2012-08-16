<?php
return CMap::mergeArray(
	require(dirname(__FILE__).'/../../../../core/config/main.php'),
	array(
                'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
                'defaultController' => 'Api',
            
            'import' => array(
                'application.components.*',
                'application.models.*',
                'application.controllers.*',
                'core.extensions.yii-backvendor.web.webservice.*',
            ),
            
            'components' => array(
                
                'urlManager'=>array(
                    'urlFormat'=>'path',
                    ),
                
                'errorHandler'=>array(
                    'errorAction'=>'api/error',
                ),
            ),
            
        )
    );
?>
