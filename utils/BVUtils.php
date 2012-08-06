<?php

/**
 * Description of BVUtils
 *
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */
class BVUtils 
{
    public static function formatMessage( $template, array $args )
    {
        foreach( $args as $search => $replace )
        {
            $template = str_replace($search, $replace, $template);
        }
        return $template;
    }
        
    public static function cropString($string, $maxLength, $end = '...')
    {
        if (strlen($string)<=$maxLength)
            return $string;
        $cropLength = $maxLength-strlen($end);
        if ($cropLength<1) 
            return $string;
        $substring = substr($string, 0, $cropLength);
        return $substring.$end;
    }
}