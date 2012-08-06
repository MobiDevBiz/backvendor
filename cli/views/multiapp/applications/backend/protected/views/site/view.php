
<h1>View <?php echo $this->modelAlias; ?> <?php echo $this->getModelTitle($model); ?></h1>
<?php
// if (isset($_GET['Merchant']))PrintAndDie::_($_GET);
echo CHtml::decode($this->widget('zii.widgets.CDetailView', array(
    'cssFile' => Yii::app()->getBaseUrl().'/css/detailview/styles.css',
    'data' => $model,
    'attributes' => $this->getDetailViewAttributes(),
), true));
?>
<br>
<br>
<?php
foreach ($subs as $key => $value)
{
    echo '<h2>'.  BVUtils::formatMessage(SiteController::$configuration['allChildrenText'], array(
       '{children}' =>$value['createMenu']['aliasPlural'],
        '{parent}'=> $this->modelAlias ,
    ))  . ' </h2>';
    if ($value['createMenu']['editable'])
    {
        $this->widget('zii.widgets.CMenu', array('items' => array(
                array('label' => 'Add new ' . $value['createMenu']['alias'], 'url' => array(
                        'create',
                        'entity' => $value['createMenu']['entity'],
                        'parentEntity' => $this->entityId,
                        'parentKey' => $value['createMenu']['foreignKey'],
                        'parentValue' => $value['createMenu']['foreignValue'],
                )),
                )));
    }

    $value['grid']['baseScriptUrl'] = Yii::app()->getBaseUrl().'/css/gridview';
    $value['grid']['pager'] = array('class' => 'CLinkPager', 'cssFile' => Yii::app()->getBaseUrl().'/css/gridview/pager.css');
    $this->widget('zii.widgets.grid.CGridView', $value['grid']);
}
?>
<br>
<!--custom page element - "reactivate gift" button-->

<?php
if ($this->entityId == 'gift' and $this->model->is_received == 1)
{
    echo CHtml::submitButton('Reactivate gift', array('submit' => $this->createUrl('reactivateGift', array($this->model->tableSchema->primaryKey => $this->getModelPk($this->model)))));
}
?>