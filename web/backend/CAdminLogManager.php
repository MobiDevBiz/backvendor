<?php

/**
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */

class CAdminLogManager extends CComponent
{

    public $adminName;
    public $action;
    protected  static $texts = array(
        'login' => '{adminName} logged in',
        'logout' => '{adminName} logged out',
        'create' => '{adminName} added new {entity} {entityTitle} (#{entityID})',
        'update' => '{adminName} updated {entity} {entityTitle} (#{entityID})',
        'delete' => '{adminName} deleted {entity} {entityTitle} (#{entityID})',
        'nuke' => '{adminName} erased {modelName} table',
        'undelete'=>'{adminName} restored {entity} {entityTitle} (#{entityID})',
    );

    public function init()
    {
        
    }

    private function InitializeLog()
    {
        $this->adminName = Yii::app()->user->_admin_title . ' (' . Yii::app()->user->name . ')';
        $log = new AdminActivityLog();
        $log->admin_name = $this->adminName;
        $log->date = date('Y-m-d H:i:s');
        $log->action = $this->action;
        $log->message = $this->getText();
        $log->ip = $_SERVER['REMOTE_ADDR'];
        return $log;
    }

    private function getText()
    {
        return self::$texts[$this->action];
    }

    private function getUpdateAdditional($oldAttributes)
    {
        $newAttributes = Yii::app()->controller->model->getAttributes();
        $result = 'Changed attributes';
        foreach ($oldAttributes as $attribute => $oldValue)
        {
            if ($newAttributes[$attribute] != $oldValue)
            {
                $result.=(' "' . $attribute . '" from "' . $oldValue . '" to "' . $newAttributes[$attribute] . '",');
            }
        }
        return $result;
    }

    private function getAttributesAdditional()
    {
        $newAttributes = Yii::app()->controller->model->getAttributes();
        $result = 'Attributes: ';
        foreach ($newAttributes as $attribute => $value)
        {
                $result.=(' "' . $attribute . '" = "' . $value . '",');
        }
        return $result;
    }
    

    public function logLogIn()
    {
        $this->action = 'login';
        $log = $this->InitializeLog();
        $log->message = BVUtils::formatMessage($log->message, array(
                    '{adminName}' => $this->adminName,
                ));
        $log->save();
    }

    public function logLogOut()
    {
        $this->action = 'logout';
        $log = $this->InitializeLog();
        $log->message = BVUtils::formatMessage($log->message, array(
                    '{adminName}' => $this->adminName,
                ));
        $log->save();
    }

    public function logCreate()
    {
        $this->action = 'create';
        $log = $this->InitializeLog();
        $log->entity = Yii::app()->controller->entityId;
        $log->message = BVUtils::formatMessage($log->message, array(
                    '{adminName}' => $this->adminName,
                    '{entity}' => Yii::app()->controller->entityId,
                    '{entityID}' => Yii::app()->controller->primaryKey,
                    '{entityTitle}' => Yii::app()->controller->model->{Yii::app()->controller->getTitleAttribute()},
                ));
                    $log->additional=  $this->getAttributesAdditional();
        $log->save();
    }

    public function logUpdate($oldAttributes)
    {
        $this->action = 'update';
        $log = $this->InitializeLog();
        $log->entity = Yii::app()->controller->entityId;
        $log->message = BVUtils::formatMessage($log->message, array(
                    '{adminName}' => $this->adminName,
                    '{entity}' => Yii::app()->controller->entityId,
                    '{entityID}' => Yii::app()->controller->primaryKey,
                    '{entityTitle}' => Yii::app()->controller->model->{Yii::app()->controller->getTitleAttribute()},
                ));
        $log->additional = $this->getUpdateAdditional($oldAttributes);
        $log->save();
    }

    public function logDelete()
    {
        $this->action = 'delete';
        $log = $this->InitializeLog();
        $log->entity = Yii::app()->controller->entityId;
        $log->message = BVUtils::formatMessage($log->message, array(
                    '{adminName}' => $this->adminName,
                    '{entity}' => Yii::app()->controller->entityId,
                    '{entityID}' => Yii::app()->controller->primaryKey,
                    '{entityTitle}' => Yii::app()->controller->model->{Yii::app()->controller->getTitleAttribute()},
                ));
                       $log->additional=  $this->getAttributesAdditional();
        $log->save();
    }

    public function logUndelete()
    {
        $this->action = 'undelete';
        $log = $this->InitializeLog();
        $log->entity = Yii::app()->controller->entityId;
        $log->message = BVUtils::formatMessage($log->message, array(
                    '{adminName}' => $this->adminName,
                    '{entity}' => Yii::app()->controller->entityId,
                    '{entityID}' => Yii::app()->controller->primaryKey,
                    '{entityTitle}' => Yii::app()->controller->model->{Yii::app()->controller->getTitleAttribute()},
                ));
                    $log->additional=  $this->getAttributesAdditional();
        $log->save();
    }
    
    public function logNuke()
    {
        $this->action = 'nuke';
        $log = $this->InitializeLog();
        $log->entity = Yii::app()->controller->entityId;
        $log->message = BVUtils::formatMessage($log->message, array(
                    '{adminName}' => $this->adminName,
                    '{modelName}' => Yii::app()->controller->modelName,
                ));
        $log->save();
    }

   

}

?>
