<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />


        <script type="text/javascript">
            function setupTimer(cid, time) {
                var el = document.getElementById(cid);
                var d = new Date(time);
    
                el.innerHTML = d;
 
                setInterval(function() {
                    var d = new Date(time += 1000);
                    //       alert("fkuc");
                    el.innerHTML = (d.getYear()+1900)+
                        (d.getMonth()< 9 ?"-0":"-")+(d.getMonth()+1)+
                        (d.getDate()<10?"-0":"-")+d.getDate()+
                        (d.getHours()<10?" 0":" ")+d.getHours()+
                        (d.getMinutes()<10?":0":":")+d.getMinutes()+
                        (d.getSeconds()<10?":0":":")+d.getSeconds(); 
                }, 1000);
               
            }
        </script>
        <title><?php echo CHtml::encode(Yii::app()->name.' backend- '.$this->getAction()->getId().' '.$this->modelName); ?></title>
    </head>

    <body>

        <div class="page-backend">

            <div id="header">
                <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?> - Admin interface</div>

                <div class="timer"  id="timer-server"> Server time:
                    <span id="server-time"></span>
                </div>
                <script type="text/javascript">
                    setupTimer("server-time", new Date("<?php echo date('d M Y H:i:s'); ?>").getTime());
                </script>
            </div><!-- header -->

            <div id="mainmenu">
                <?php
//                if ($this->getAction()->getID() != 'shoutman')
                    $this->widget('zii.widgets.CMenu', array(
                        'items' => $this->createMainMenu(),
                    ));
             
                ?>
            </div><!-- mainmenu -->
            <?php if (isset($this->breadcrumbs)): ?>
                <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                    'homeLink'=>false
                ));
                ?><!-- breadcrumbs -->
            <?php endif ?>

            <?php 
               $this->widget('BootAlert', array(
                    'id' => 'alert',
                    'keys' => array('error'),
                    'options' => array(
                        'displayTime' => 10000,
                        'closeTime' => 600,
                        'closeText' => '×',
                    ),
                ));
               $form=$this->beginWidget('CActiveForm', array(
	'id'=>'submit-form',
//    'action'=> array ('approveSelected'),
	'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
//            'hideErrorMessage'=>true,
    ),
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); 
            echo $content; 
            $this->endWidget();?>



        </div><!-- page -->

    </body>
</html>