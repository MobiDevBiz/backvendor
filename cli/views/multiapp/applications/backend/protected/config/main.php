<?php
return CMap::mergeArray(
	require(dirname(__FILE__).'/../../../../core/config/main.php'),
	array(
            'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',

            'import' => array(
                'application.components.*',
                'application.models.*',
                'application.extensions.*',
                'application.extensions.bootstrap.widgets.*',
                 'core.extensions.yii-backvendor.web.backend.*',
            ),
            
            'preload' => array(
                'bootstrap',
                ),

            'components' => array(
                
                'user' => array(
                    'loginUrl' => array('site/login'),
                    'StateKeyPrefix' => '_backend',
                ),
                
              
                
                'logger' => array(
                    'class'=> 'application.components.AdminLogger'
                ),
                
                'user' => array(
                    'allowAutoLogin' => true,
                ),
                
                'bootstrap' => array(
                    'class' => 'application.extensions.bootstrap.components.Bootstrap',
                ),
        ),
    )
);
?>
