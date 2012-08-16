<?php
/**
 * BootDataColumn class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

Yii::import('zii.widgets.grid.CDataColumn');

/**
 * Thanks to Simo Jokela <rBoost@gmail.com> for writing the original version of this class.
 */
class BootDataColumn extends CDataColumn
{
	/**
	 * @var string the header color for sortable columns.
	 * Valid values are: 'blue', 'green', 'red', 'yellow', 'orange' and 'purple'.
	 * @since 0.9.6
	 */
	public $color;

	/**
	 * Initializes the column.
	 */
	public function init()
	{
		if ($this->grid->enableSorting && $this->sortable && $this->name !== null)
		{
			$class = array();
			$class[] = 'header';

			if ($this->color !== null)
				$class[] = $this->color;

			$class = implode(' ', $class);

			if (isset($this->headerHtmlOptions['class']))
				$this->headerHtmlOptions['class'] .= $class;
			else
				$this->headerHtmlOptions['class'] = $class;
		}

		parent::init();
	}

	/**
	 * Renders the header cell.
	 */
	public function renderHeaderCell()
	{
		if ($this->grid->enableSorting && $this->sortable && $this->name !== null)
		{
			$direction = $this->grid->dataProvider->sort->getDirection($this->name);

			if ($direction !== null)
			{
				$sortCssClass = $direction ? 'headerSortDown' : 'headerSortUp';
				$this->headerHtmlOptions['class'] .= ' '.$sortCssClass;
			}
		}

		parent::renderHeaderCell();
	}
}
