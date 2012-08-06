<?php

return CMap::mergeArray(
	require( dirname(__FILE__) .'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),

			'db'=>array(
                                                        'connectionString' => 'mysql:host=...;dbname=...',
                                                        'emulatePrepare' => true,
                                                        'username' => '...',
                                                        'password' => '...',
                                                        'charset' => 'utf8',
                                                        'tablePrefix' => '...',
			),
			
		),
                                'import' => array(
                                    'core.extensions.yii-backvendor.tests.CWebserviceTestCase',
                                    'application.tests.functional.*',
                                ),
	)
);

?>
