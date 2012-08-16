<?php

//set path to framework here
$pathToFramwork = 'framework/yii.php';

defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));

defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($pathToFramwork);

$app=Yii::createConsoleApplication(array('basePath'=>dirname(__FILE__).'/cli'));

$app->run();
?>
