<?php
/**
 * BootActiveForm class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

class BootActiveForm extends CActiveForm
{
	/**
	 * @var string the error message type. Valid types are 'inline' and 'block'.
	 */
	public $errorMessageType = 'inline';
	/**
	 * @var boolean whether this is a stacked form.
	 */
	public $stacked = false;

	/**
	 * Initializes the widget.
	 * This renders the form open tag.
	 */
	public function init()
	{
		$cssClass = $this->stacked ? 'form-stacked' : '';

		if (!isset($this->htmlOptions['class']))
			$this->htmlOptions['class'] = $cssClass;
		else
			$this->htmlOptions['class'] .= ' '.$cssClass;

		if ($this->errorMessageType === 'inline')
			$this->errorMessageCssClass = 'help-inline';
		else
			$this->errorMessageCssClass = 'help-block';

		parent::init();
	}

	/**
	 * Creates an input row of a specific type.
	 * @param string $type the input type
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $data the data for list inputs
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function inputRow($type, $model, $attribute, $data = null, $htmlOptions = array())
	{
		ob_start();
		Yii::app()->controller->widget('ext.bootstrap.widgets.BootInput',array(
			'type'=>$type,
			'form'=>$this,
			'model'=>$model,
			'attribute'=>$attribute,
			'data'=>$data,
			'htmlOptions'=>$htmlOptions,
		));
		return ob_get_clean();
	}

	/**
	 * Renders a checkbox input row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function checkBoxRow($model, $attribute, $htmlOptions = array())
	{
		return $this->inputRow('checkbox', $model, $attribute, null, $htmlOptions);
	}

	/**
	 * Renders a checkbox list input row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $data the list data
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function checkBoxListRow($model, $attribute, $data = array(), $htmlOptions = array())
	{
		return $this->inputRow('checkboxlist', $model, $attribute, $data, $htmlOptions);
	}

	/**
	 * Renders a drop-down list input row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $data the list data
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function dropDownListRow($model, $attribute, $data = array(), $htmlOptions = array())
	{
		return $this->inputRow('dropdownlist', $model, $attribute, $data, $htmlOptions);
	}

	/**
	 * Renders a file field input row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function fileFieldRow($model, $attribute, $htmlOptions = array())
	{
		return $this->inputRow('filefield', $model, $attribute, null, $htmlOptions);
	}

	/**
	 * Renders a password field input row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function passwordFieldRow($model, $attribute, $htmlOptions = array())
	{
		return $this->inputRow('password', $model, $attribute, null, $htmlOptions);
	}

	/**
	 * Renders a radio button input row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function radioButtonRow($model, $attribute, $htmlOptions = array())
	{
		return $this->inputRow('radiobutton', $model, $attribute, null, $htmlOptions);
	}

	/**
	 * Renders a radio button list input row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $data the list data
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function radioButtonListRow($model, $attribute, $data = array(), $htmlOptions = array())
	{
		return $this->inputRow('radiobuttonlist', $model, $attribute, $data, $htmlOptions);
	}

	/**
	 * Renders a text field input row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function textFieldRow($model, $attribute, $htmlOptions = array())
	{
		return $this->inputRow('textfield', $model, $attribute, null, $htmlOptions);
	}

	/**
	 * Renders a text area input row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function textAreaRow($model, $attribute, $htmlOptions = array())
	{
		return $this->inputRow('textarea', $model, $attribute, null, $htmlOptions);
	}

	/**
	 * Renders a captcha row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 * @since 0.9.3
	 */
	public function captchaRow($model, $attribute, $htmlOptions = array())
	{
		return $this->inputRow('captcha', $model, $attribute, null, $htmlOptions);
	}

	/**
	 * Renders an uneditable text field row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 * @since 0.9.5
	 */
	public function uneditableRow($model, $attribute, $htmlOptions = array())
	{
		return $this->inputRow('uneditable', $model, $attribute, null, $htmlOptions);
	}

	/**
	 * Renders a checkbox list for a model attribute.
	 * This method is a wrapper of {@link CHtml::activeCheckBoxList}.
	 * Please check {@link CHtml::activeCheckBoxList} for detailed information
	 * about the parameters for this method.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $data value-label pairs used to generate the check box list.
	 * @param array $htmlOptions additional HTML options.
	 * @return string the generated check box list
	 * @since 0.9.5
	 */
	public function checkBoxList($model, $attribute, $data, $htmlOptions = array())
	{
		return $this->inputsList('checkbox', $model, $attribute, $data, $htmlOptions);
	}

	/**
	 * Renders a radio button list for a model attribute.
	 * This method is a wrapper of {@link CHtml::activeRadioButtonList}.
	 * Please check {@link CHtml::activeRadioButtonList} for detailed information
	 * about the parameters for this method.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $data value-label pairs used to generate the radio button list.
	 * @param array $htmlOptions additional HTML options.
	 * @return string the generated radio button list
	 * @since 0.9.5
	 */
	public function radioButtonList($model, $attribute, $data, $htmlOptions = array())
	{
		return $this->inputsList('radio', $model, $attribute, $data, $htmlOptions);
	}

	/**
	 * Renders an input list.
	 * @param string $type the input type. Valid types are 'checkbox' and 'radio'.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $data value-label pairs used to generate the radio button list.
	 * @param array $htmlOptions additional HTML options.
	 * @return string the generated input list.
	 * @since 0.9.5
	 */
	protected function inputsList($type, $model, $attribute, $data, $htmlOptions = array())
	{
		CHtml::resolveNameID($model, $attribute, $htmlOptions);
		$selection = CHtml::resolveValue($model, $attribute);

		if ($model->hasErrors($attribute))
		{
			if(isset($htmlOptions['class']))
				$htmlOptions['class'] .= ' '.CHtml::$errorCss;
			else
				$htmlOptions['class'] = CHtml::$errorCss;
		}

		$name = $htmlOptions['name'];
		unset($htmlOptions['name']);

		if (array_key_exists('uncheckValue', $htmlOptions))
		{
			$uncheck = $htmlOptions['uncheckValue'];
			unset($htmlOptions['uncheckValue']);
		}
		else
			$uncheck = '';

		$hiddenOptions = isset($htmlOptions['id']) ? array('id' => CHtml::ID_PREFIX.$htmlOptions['id']) : array('id' => false);
		$hidden = $uncheck !== null ? CHtml::hiddenField($name, $uncheck, $hiddenOptions) : '';

		unset($htmlOptions['template'], $htmlOptions['separator'], $htmlOptions['labelOptions']);

		$items = array();
		$baseID = CHtml::getIdByName($name);
		$id = 0;
		$method = $type === 'checkbox' ? 'checkBox' : 'radioButton';

		foreach($data as $value => $label)
		{
			$checked =! strcmp($value, $selection);
			$htmlOptions['value'] = $value;
			$htmlOptions['id'] = $baseID.'_'.$id++;
			$option = CHtml::$method($name, $checked, $htmlOptions);
			$items[] = '<label>'.$option.'<span>'.$label.'</span></label>';
		}

		return $hidden.'<ul class="inputs-list"><li>'.implode('</li><li>',$items).'</li></ul>';
	}

	/**
	 * Displays a summary of validation errors for one or several models.
	 * This method is very similar to {@link CHtml::errorSummary} except that it also works
	 * when AJAX validation is performed.
	 * @param mixed $models the models whose input errors are to be displayed. This can be either
	 * a single model or an array of models.
	 * @param string $header a piece of HTML code that appears in front of the errors
	 * @param string $footer a piece of HTML code that appears at the end of the errors
	 * @param array $htmlOptions additional HTML attributes to be rendered in the container div tag.
	 * @return string the error summary. Empty if no errors are found.
	 * @see CHtml::errorSummary
	 */
	public function errorSummary($models, $header = null, $footer = null, $htmlOptions = array())
	{
		if (!isset($htmlOptions['class']))
			$htmlOptions['class'] = 'alert-message block-message error'; // Bootstrap error class as default

		return parent::errorSummary($models, $header, $footer, $htmlOptions);
	}

	/**
	 * Displays the first validation error for a model attribute.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute name
	 * @param array $htmlOptions additional HTML attributes to be rendered in the container div tag.
	 * @param boolean $enableAjaxValidation whether to enable AJAX validation for the specified attribute.
	 * @param boolean $enableClientValidation whether to enable client-side validation for the specified attribute.
	 * @return string the validation result (error display or success message).
	 */
	public function error($model, $attribute, $htmlOptions = array(), $enableAjaxValidation = true, $enableClientValidation = true)
	{
		if (!$this->enableAjaxValidation)
			$enableAjaxValidation = false;

		if (!$this->enableClientValidation)
			$enableClientValidation = false;

		if (!isset($htmlOptions['class']))
			$htmlOptions['class'] = $this->errorMessageCssClass;

		if (!$enableAjaxValidation && !$enableClientValidation)
			return $this->errorSpan($model, $attribute, $htmlOptions);

		$id = CHtml::activeId($model,$attribute);
		$inputID = isset($htmlOptions['inputID']) ? $htmlOptions['inputID'] : $id;
		unset($htmlOptions['inputID']);
		if (!isset($htmlOptions['id']))
			$htmlOptions['id'] = $inputID.'_em_';

		$option = array(
			'id'=>$id,
			'inputID'=>$inputID,
			'errorID'=>$htmlOptions['id'],
			'model'=>get_class($model),
			'name'=>CHtml::resolveName($model, $attribute),
			'enableAjaxValidation'=>$enableAjaxValidation,
			'inputContainer'=>'div.clearfix', // Bootstrap requires this
		);

		$optionNames = array(
			'validationDelay',
			'validateOnChange',
			'validateOnType',
			'hideErrorMessage',
			'inputContainer',
			'errorCssClass',
			'successCssClass',
			'validatingCssClass',
			'beforeValidateAttribute',
			'afterValidateAttribute',
		);

		foreach ($optionNames as $name)
		{
			if (isset($htmlOptions[$name]))
			{
				$option[$name] = $htmlOptions[$name];
				unset($htmlOptions[$name]);
			}
		}

		if ($model instanceof CActiveRecord && !$model->isNewRecord)
			$option['status'] = 1;

		if ($enableClientValidation)
		{
			$validators = isset($htmlOptions['clientValidation']) ? array($htmlOptions['clientValidation']) : array();
			foreach ($model->getValidators($attribute) as $validator)
			{
				if ($enableClientValidation && $validator->enableClientValidation)
				{
					if (($js = $validator->clientValidateAttribute($model,$attribute)) != '')
						$validators[] = $js;
				}
			}

			if ($validators !== array())
				$option['clientValidation']="js:function(value, messages, attribute) {\n".implode("\n",$validators)."\n}";
		}

		$html = $this->errorSpan($model, $attribute, $htmlOptions);

		if ($html === '')
		{
			if (isset($htmlOptions['style']))
				$htmlOptions['style'] = rtrim($htmlOptions['style'], ';').';display:none';
			else
				$htmlOptions['style'] = 'display:none';

			$html = CHtml::tag('span', $htmlOptions, '');
		}

		$this->attributes[$inputID] = $option;
		return $html;
	}

	/**
	 * Displays the first validation error for a model attribute.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute name
	 * @param array $htmlOptions additional HTML attributes to be rendered in the container div tag.
	 * This parameter has been available since version 1.0.7.
	 * @return string the error display. Empty if no errors are found.
	 * @see CModel::getErrors
	 * @see errorMessageCss
	 */
	public static function errorSpan($model, $attribute, $htmlOptions = array())
	{
		CHtml::resolveName($model, $attribute);
		$error = $model->getError($attribute);

		if ($error !== null)
		{
			if (!isset($htmlOptions['class']))
				$htmlOptions['class'] = 'help-inline';

			return CHtml::tag('span', $htmlOptions, $error); // Bootstrap errors must be spans
		}
		else
			return '';
	}
}
