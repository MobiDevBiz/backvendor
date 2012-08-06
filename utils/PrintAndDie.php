<?php

/**
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */
class PrintAndDie
{
    public static function _($expression, $die=true)
    {
        print_r($expression);
        if ($die)
        {
            die();
        }
        return null;
    }
}
?>
