<?php
/**
 * BootMediaGrid class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

Yii::import('bootstrap.widgets.BootListView');

class BootMediaGrid extends BootListView
{
	/**
	 * @var string the tag name for the view container. Defaults to 'div'.
	 */
	public $tagName = 'div';
	/**
	 * @var array the images to display in the media grid.
	 */
	public $images = array();

	/**
	 * Renders the data items for the view.
	 * Each item is corresponding to a single data model instance.
	 * Child classes should override this method to provide the actual item rendering logic.
	 */
	public function renderItems()
	{
		$data = $this->dataProvider->getData();
		
		if (!empty($data))
		{
			echo CHtml::openTag('ul', array('class'=>'media-grid'));
			$owner = $this->getOwner();
			$render = $owner instanceof CController ? 'renderPartial' : 'render';
			foreach($data as $i=>$item)
			{
				$data = $this->viewData;
				$data['index'] = $i;
				$data['data'] = $item;
				$data['widget'] = $this;
				echo CHtml::openTag('li');
				$owner->$render($this->itemView,$data);
				echo '</li>';
			}

			echo '</ul>';
		}
		else
			$this->renderEmptyText();
	}
}
