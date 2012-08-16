<?php
$yii='../../framework/yii.php';
$config='./protected/config/main.php';

 defined('YII_DEBUG') or define('YII_DEBUG',false);

require_once($yii);
Yii::createWebApplication($config)->run();

?>
   