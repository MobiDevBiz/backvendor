<?php
/**
 * Bootstrap class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

class Bootstrap extends CApplicationComponent
{
	/**
	 * @var string the assets url for this extension.
	 */
	protected $_assetsUrl;

	/**
	 * Initializes the component.
	 */
	public function init()
	{
		Yii::setPathOfAlias('bootstrap', realpath(dirname(__FILE__).'/..'));
	}

	/**
	 * Registers the Bootstrap CSS.
	 */
	public function registerCoreCss()
	{
		Yii::app()->clientScript->registerCssFile($this->getAssetsUrl().'/css/bootstrap.min.css');
	}

	/**
	 * Registers a Bootstrap JavaScript file.
	 * @param string $fileName the file name.
     * @param integer $position the position of the JavaScript file.
	 */
	public function registerScriptFile($fileName, $position=CClientScript::POS_HEAD)
	{
		Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl().'/js/'.$fileName, $position);
	}

	/**
	* Returns the url to assets publishing the folder if necessary.
	* @return string the assets url
	*/
	protected function getAssetsUrl()
	{
		if ($this->_assetsUrl !== null)
			return $this->_assetsUrl;
		else
		{
			$assetsPath = Yii::getPathOfAlias('bootstrap.assets');

			if (YII_DEBUG)
				$assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
			else
				$assetsUrl = Yii::app()->assetManager->publish($assetsPath);

			return $this->_assetsUrl = $assetsUrl;
		}
	}
}
