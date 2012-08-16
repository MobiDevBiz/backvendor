<?php
/**
 * BootGridView class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

Yii::import('zii.widgets.grid.CGridView');
Yii::import('bootstrap.widgets.BootDataColumn');

class BootGridView extends CGridView
{
	/**
	 * @var string the CSS class name for the container table.
	 * Defaults to 'zebra-striped'.
	 */
	public $itemsCssClass = 'zebra-striped';
	/**
	 * @var string the CSS class name for the pager container.
	 * Defaults to 'pagination'.
	 */
	public $pagerCssClass = 'pagination';
	/**
	 * @var array the configuration for the pager.
	 * Defaults to <code>array('class'=>'ext.bootstrap.widgets.BootPager')</code>.
	 */
	public $pager = array('class'=>'bootstrap.widgets.BootPager');

	/**
	 * Creates column objects and initializes them.
	 */
	protected function initColumns()
	{
		foreach ($this->columns as &$column)
			if (!isset($column['class']))
				$column['class'] = 'BootDataColumn';

		parent::initColumns();
	}

	/**
     * Creates a column based on a shortcut column specification string.
     * @param string $text the column specification string
     * @return BootDataColumn the column instance
     */
    protected function createDataColumn($text)
    {
        if (!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $text, $matches))
            throw new CException(Yii::t('bootstrap','The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));

        $column = new BootDataColumn($this);
        $column->name = $matches[1];

        if (isset($matches[3]) && $matches[3] !== '')
            $column->type = $matches[3];

        if (isset($matches[5]))
            $column->header = $matches[5];

        return $column;
    }
}
