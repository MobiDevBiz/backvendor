<?php

// change the following paths if necessary
$yiit='../../../../framework/yiit.php';
$config='../config/test.php';

require_once($yiit);

Yii::createWebApplication($config);
require_once(dirname(__FILE__).'/WebserviceTestCase.php');
?>
