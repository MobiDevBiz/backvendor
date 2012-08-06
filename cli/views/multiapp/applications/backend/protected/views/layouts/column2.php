<?php $this->beginContent('/layouts/main'); ?>
<div class="container">
	<div class="span-19">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-5 last">
		<div id="sidebar">
		<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Operations',
			));
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->menu,
				'htmlOptions'=>array('class'=>'operations'),
			));
			$this->endWidget();
                        if ($this->getAction()->getId()=='view')
                        {
                            $onPageSizeChange ='';
                            foreach ($this->subs as $key => $value)
                            {
                                $onPageSizeChange.="$.fn.yiiGridView.update('" . $value['grid']['id']. "',{ data:{pageSize: $(this).val() }});";
                            }
                        
                        echo '<b>Change tables size:</b><br> Display ' .
                        CHtml::dropDownList('pageSize', $this->pageSize, SiteController::$configuration['pageSizesDropDownList'], array('onchange' => $onPageSizeChange)) . ' rows per page.';
                       }
		?>
		</div><!-- sidebar -->
	</div>
</div>
<?php $this->endContent(); ?>