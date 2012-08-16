<?php

/**
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */


class AdminLogger extends CAdminLogManager
{

    public function init()
    {
        $this->addCustomTexts();
        return parent::init();
    }

    private function addCustomTexts()
    {
        self::$texts = CMap::mergeArray(parent::$texts, array(
            
                        )
        );
    }

}

?>
