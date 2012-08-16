<?php

Yii::setPathOfAlias('core', dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');
Yii::setPathOfAlias('images', dirname(__FILE__) . DIRECTORY_SEPARATOR.'../../applications/images' );

return array(
    'name' => 'My System Name',
    'import' => array(
        'core.models.*',
        'core.components.*',
         'core.components.ImageRouter.*',
        'core.extensions.yii-backvendor.base.*',
        'core.extensions.yii-backvendor.extensions.*',
        'core.extensions.yii-backvendor.utils.*',
    ),
    'preload' => array(
        'log',
    ),
    'components' => array(
        'db' => require( dirname(__FILE__) . '/db.php' ),
        'imageRouter' => require( dirname(__FILE__) . '/image_router.php' ),
        
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'warning, error',
                ),
            ),
        ),
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'parololo667'
        ),
    ),
);
?>
