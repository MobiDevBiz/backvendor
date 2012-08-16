<?php
/**
 * BootNav class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright  Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

 Yii::import('bootstrap.widgets.BootWidget');

/**
 * Bootstrap topbar navigation widget with support for dropdown menus.
 * @since 0.9.7
 */
class BootNav extends BootWidget
{
	/**
	 * @var string the URL for the brand link.
	 */
	public $brandUrl;
	/**
	 * @var string the text for the brand link.
	 */
	public $brandText;
	/**
	 * @var array the HTML attributes for the brand link.
	 */
	public $brandOptions = array();
	/**
	 * @var array the primary menu items.
	 */
	public $primaryItems = array();
	/**
	 * @var array the secondary menu items.
	 */
	public $secondaryItems = array();
	/**
	 * @var array the HTML attributes for the primary menu.
	 */
	public $primaryOptions = array();
	/**
	 * @var array the HTML attributes for the secondary menu.
	 */
	public $secondaryOptions = array();

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		if (isset($this->htmlOptions['class']))
			$this->htmlOptions['class'] .= ' topbar';
		else
			$this->htmlOptions['class'] = 'topbar';

		if (isset($this->brandOptions['class']))
			$this->brandOptions['class'] .= ' brand';
		else
			$this->brandOptions['class'] = 'brand';

		if (isset($this->brandUrl))
			$this->brandOptions['href'] = $this->brandUrl;

		if (isset($this->primaryOptions['class']))
			$this->primaryOptions['class'] .= ' nav';
		else
			$this->primaryOptions['class'] = 'nav';

		if (isset($this->secondaryOptions['class']))
			$this->secondaryOptions['class'] .= ' secondary-nav';
		else
			$this->secondaryOptions['class'] = 'secondary-nav';

		echo CHtml::openTag('div', $this->htmlOptions);
		echo '<div class="topbar-inner"><div class="container">';
		echo CHtml::openTag('a', $this->brandOptions);
		echo $this->brandText;
		echo '</a>';

		if (!empty($this->primaryItems))
		{
			$this->controller->widget('bootstrap.widgets.BootMenu', array(
				'type'=>'',
				'items'=>$this->primaryItems,
				'htmlOptions'=>$this->primaryOptions,
			));
		}

		if (!empty($this->secondaryItems))
		{
			$this->controller->widget('bootstrap.widgets.BootMenu', array(
				'type'=>'',
				'items'=>$this->secondaryItems,
				'htmlOptions'=>$this->secondaryOptions,
			));
		}

		echo '</div></div></div>';
	}
}
