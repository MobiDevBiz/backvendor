<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="eng" >
<head>

    <link rel="stylesheet" type="text/css" href="<?php echo $assetPath ?>/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $assetPath ?>/autodoc.css" />
</head>

<body>
    
    <h1><?php  echo CHtml::link('Autodocumentation', array('autodoc')); ?></h1>
    
    <?php
        if( is_array( $apiVersionDetails ) )
        {
            echo '<table class="doctable">
                <tr>  <td><b>API Function Name</b></td> <td><b>Parameters</b></td> </tr>
            ';
            foreach( $apiVersionDetails as $functionName=>$parameters )
            {
                echo '<tr>';
                echo '<td> <b>'.$functionName.' </b></td>';
                echo '<td>'.$parameters.'</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
            
    ?>
    
    <h2> Request Format </h2>
    
    <h3> Single Call </h3>
    
    <?php  echo $requestFormatSingle; ?>
    
    <h3> Multiple Call </h3>
    
    In progress... 
    
    
    
    <h2> Response Format </h2>
    
    <h3> Success </h3>
    
    <?php  echo $responseSuccess; ?>
    
    <h3>Error</h3>
    
    <?php  echo $responseError; ?>
    
    <h3>Multiple Call Response</h3>
    
    In progress..
    
    <h2>Webservice Settings</h2>
    
    <table class="doctable">
        
        <?php  
            foreach( $webserviceConfig as $key => $value)
            {
                if( $value === false )
                {
                    $value = 'no';
                }
                elseif( $value === true )
                {
                    $value = 'yes';
                }
                echo '<tr>';
                echo '<td> <b>'.$key.' </b></td>';
                echo '<td>'.$value.'</td>';
                echo '</tr>';
            }
            echo CHtml::tag('table', $webserviceConfig) 
        ?>
    </table>
    
     <h2>Error Dictionary</h2>
    
    <table class="doctable">
        
        <?php  
            foreach( $errorDictionary as $key => $value)
            {
                if( $value === false )
                {
                    $value = 'no';
                }
                elseif( $value === true )
                {
                    $value = 'yes';
                }
                echo '<tr>';
                echo '<td> <b>'.$key.' </b></td>';
                echo '<td>'.$value.'</td>';
                echo '</tr>';
            }
            echo CHtml::tag('table', $webserviceConfig) 
        ?>
    </table>
    

</body>
