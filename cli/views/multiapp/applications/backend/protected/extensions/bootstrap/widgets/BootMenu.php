<?php
/**
 * BootMenu class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

Yii::import('zii.widgets.CMenu');

/**
 * Bootstrap menu widget with support for dropdown sub-menus.
 * @since 0.9.5
 */
class BootMenu extends CMenu
{
	/**
	 * @var string the type of menu to display.
	 * Following types are supported: '', 'tabs' and 'pills'.
	 */
	public $type = 'tabs';

	/**
	 * Initializes the menu widget.
	 */
	public function init()
	{
		if (isset($this->htmlOptions['class']))
			$this->htmlOptions['class'] .= ' '.$this->type;
		else
			$this->htmlOptions['class'] = $this->type;

		parent::init();
	}

	/**
	 * Runs the menu widget.
	 */
	public function run()
	{
		parent::run();

		$id = $this->getId();
		Yii::app()->clientScript->registerScript(__CLASS__.'#'.$id,"
			jQuery('#{$id} .dropdown-toggle').bind('click', function() {
				$(this).parent().toggleClass('open');
			});
		");
	}

    /**
     * Normalizes the items so that the 'active' state is properly identified for every menu item.
     * @param array $items the items to be normalized.
     * @param string $route the route of the current request.
     * @param boolean $active whether there is an active child menu item.
     * @return array the normalized menu items
     */
    protected function normalizeItems($items, $route, &$active)
    {
        foreach ($items as $i => $item)
        {
            if (is_array($item))
            {
                if (isset($item['visible']) && !$item['visible'])
                {
                    unset($items[$i]);
                    continue;
                }

                if (!isset($item['label']))
                    $item['label'] = '';

                if ($this->encodeLabel)
                    $items[$i]['label'] = CHtml::encode($item['label']);

                $hasActiveChild = false;

                if (isset($item['items']))
                {
                    $items[$i]['items'] = $this->normalizeItems($item['items'], $route, $hasActiveChild);

                    if (empty($items[$i]['items']) && $this->hideEmptyItems)
                        unset($items[$i]['items']);
                }

                if (!isset($item['active']))
                {
                    if ($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item, $route))
                        $active = $items[$i]['active'] = true;
                    else
                        $items[$i]['active'] = false;
                }
                else if ($item['active'])
                    $active = true;
            }
        }

        return array_values($items);
    }

    /**
     * Recursively renders the menu items.
     * @param array $items the menu items to be rendered recursively
	 * @param integer $depth the menu depth. Defaults to zero.
     */
    protected function renderMenuRecursive($items, $depth=0)
    {
        if ($depth > 1)
            return;

        $count = 0;
        $n = count($items);

        foreach ($items as $item)
        {
            if (is_array($item))
            {
                $count++;
                $options = isset($item['itemOptions']) ? $item['itemOptions'] : array();
                $class = array();

                if (isset($item['items']))
                {
                    $options['data-dropdown'] = 'dropdown';
                    $class[] = 'dropdown';
                }

                if ($depth === 0 && $item['active'] && $this->activeCssClass != '')
                    $class[] = $this->activeCssClass;

                if ($count === 1 && $this->firstItemCssClass != '')
                    $class[] = $this->firstItemCssClass;

                if ($count === $n && $this->lastItemCssClass != '')
                    $class[] = $this->lastItemCssClass;

                if ($class !== array())
                {
                    if (empty($options['class']))
                        $options['class'] = implode(' ', $class);
                    else
                        $options['class'] .= ' '.implode(' ', $class);
                }

                echo CHtml::openTag('li', $options);

                $menu = $this->renderMenuItem($item);

                if (isset($this->itemTemplate) || isset($item['template']))
                {
                    $template = isset($item['template']) ? $item['template'] : $this->itemTemplate;
                    echo strtr($template, array('{menu}' => $menu));
                }
                else
                    echo $menu;

                if (isset($item['items']) && count($item['items']))
                {
                    if (isset($item['submenuOptions']['class']))
                        $item['submenuOptions']['class'] .= ' dropdown-menu';
                    else
                        $item['submenuOptions']['class'] = 'dropdown-menu';

                    echo "\n" . CHtml::openTag('ul', isset($item['submenuOptions']) ? $item['submenuOptions'] : $this->submenuHtmlOptions) . "\n";
                    $this->renderMenuRecursive($item['items'], $depth + 1);
                    echo '</ul>'."\n";
                }

                echo '</li>'."\n";
            }
            else
                echo '<li class="divider"></li>';
        }
    }

    /**
     * Renders the content of a menu item.
     * @param array $item the menu item to be rendered
     * @return string
     */
    protected function renderMenuItem($item)
    {
        if (!isset($item['url']))
            $item['url'] = '#';

        if (isset($item['items']))
        {
            if (isset($item['linkOptions']['class']))
                $item['linkOptions']['class'] .= ' dropdown-toggle';
            else
                $item['linkOptions']['class'] = 'dropdown-toggle';
        }

        $label = $this->linkLabelWrapper === null ? $item['label'] : '<' . $this->linkLabelWrapper . '>' . $item['label'] . '</' . $this->linkLabelWrapper . '>';
        return CHtml::link($label, $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : array());
    }
}
