<h1>Manage <?php echo $this->modelAliasPlural; ?></h1>
<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>
<?php
//echo Yii::app()->imageRouter->thumb['maxWidth'];
$dataProvider->setPagination(array('pageSize' => $this->pageSize,));
$grid = $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'grid',
    'baseScriptUrl' => Yii::app()->getBaseUrl().'/css/gridview',
    'selectableRows'=>  $this->selectable,
    'pager' => array(
        'class' => 'CLinkPager', 'cssFile' => Yii::app()->getBaseUrl().'/css/gridview/pager.css', 
    ),
    'summaryText' => 'Displaying {start}-{end} from {count} row(s).<br> Display ' .
    CHtml::dropDownList('pageSize', $this->pageSize, SiteController::$configuration['pageSizesDropDownList'], array('onchange' => "$.fn.yiiGridView.update('grid',{ data:{pageSize: $(this).val() }})")) . ' rows per page.',
    'dataProvider' => $dataProvider,
    'filter' => $model,
    'columns' => $this->getGridViewAttributes(),
        )); 
?>
