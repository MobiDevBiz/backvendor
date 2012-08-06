<?php
/**
 * BootInputBlock class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
 
class BootInput extends CInputWidget
{
	/**
	 * @var BootActiveForm the associated form widget.
	 */
	public $form;
	/**
	 * @var string the input label text.
	 */
	public $label;
	/**
	 * @var string the input type.
	 * Following types are supported: checkbox, checkboxlist, dropdownlist, filefield, password,
	 * radiobutton, radiobuttonlist, textarea, textfield, captcha and uneditable.
	 */
	public $type;
	/**
	 * @var array the data for list inputs.
	 */
	public $data = array();

	/**
	 * Initializes the widget.
	 * This method is called by {@link CBaseController::createWidget}
	 * and {@link CBaseController::beginWidget} after the widget's
	 * properties have been initialized.
	 */
	public function init()
	{
		if ($this->form === null)
			throw new CException('Failed to initialize widget! Form is not set.');

		if ($this->model === null)
			throw new CException('Failed to initialize widget! Model is not set.');

		if ($this->type === null)
			throw new CException('Failed to initialize widget! Input type is not set.');
	}

	/**
	 * Executes the widget.
	 * This method is called by {@link CBaseController::endWidget}.
	 */
	public function run()
	{
		$errorCss = $this->model->hasErrors($this->attribute) ? ' '.CHtml::$errorCss : '';
		echo CHtml::openTag('div', array('class'=>'clearfix'.$errorCss));

		switch ($this->type)
		{
			case 'checkbox':
				$this->checkBox();
				break;

			case 'checkboxlist':
				$this->checkBoxList();
				break;

			case 'dropdownlist':
				$this->dropDownList();
				break;

			case 'filefield':
				$this->fileField();
				break;

			case 'password':
				$this->passwordField();
				break;

			case 'radiobutton':
				$this->radioButton();
				break;

			case 'radiobuttonlist':
				$this->radioButtonList();
				break;

			case 'textarea':
				$this->textArea();
				break;

			case 'textfield':
				$this->textField();
				break;

			case 'captcha':
				$this->captcha();
				break;

			case 'uneditable':
				$this->uneditableField();
				break;

			default:
				throw new CException('Failed to run widget! Input type is invalid.');
		}

		echo '</div>';
	}

	protected function checkBox()
	{
		echo '<div class="input"><div class="inputs-list">';
		echo '<label for="'.CHtml::getIdByName(CHtml::resolveName($this->model, $this->attribute)).'">';
		echo $this->form->checkBox($this->model, $this->attribute, $this->htmlOptions).' ';
		echo '<span>'.$this->model->getAttributeLabel($this->attribute).'</span>';
		echo $this->getError().$this->getHint();
		echo '</label></div></div>';
	}

	protected function checkBoxList()
	{
		echo $this->getLabel().'<div class="input">';
		echo $this->form->checkBoxList($this->model, $this->attribute, $this->data, $this->htmlOptions);
		echo $this->getError().$this->getHint();
		echo '</div>';
	}

	protected function dropDownList()
	{
		echo $this->getLabel().'<div class="input">';
		echo $this->form->dropDownList($this->model, $this->attribute, $this->data, $this->htmlOptions);
		echo $this->getError().$this->getHint();
		echo '</div>';
	}

	protected function fileField()
	{
		echo $this->getLabel().'<div class="input">';
		echo $this->form->fileField($this->model, $this->attribute, $this->htmlOptions);
		echo $this->getError().$this->getHint();
		echo '</div>';
	}

	protected function passwordField()
	{
		echo $this->getLabel().'<div class="input">';
		echo $this->form->passwordField($this->model, $this->attribute, $this->htmlOptions);
		echo $this->getError().$this->getHint();
		echo '</div>';
	}

	protected function radioButton()
	{
		echo '<div class="input"><div class="inputs-list">';
		echo '<label for="'.CHtml::getIdByName(CHtml::resolveName($this->model, $this->attribute)).'">';
		echo $this->form->radioButton($this->model, $this->attribute, $this->htmlOptions).' ';
		echo '<span>'.$this->model->getAttributeLabel($this->attribute).'</span>';
		echo $this->getError().$this->getHint();
		echo '</label></div></div>';
	}

	protected function radioButtonList()
	{
		echo $this->getLabel().'<div class="input">';
		echo $this->form->radioButtonList($this->model, $this->attribute, $this->data, $this->htmlOptions);
		echo $this->getError().$this->getHint();
		echo '</div>';
	}

	protected function textArea()
	{
		echo $this->getLabel().'<div class="input">';
		echo $this->form->textArea($this->model, $this->attribute, $this->htmlOptions);
		echo $this->getError().$this->getHint();
		echo '</div>';
	}

	protected function textField()
	{
		echo $this->getLabel().'<div class="input">';
		echo $this->form->textField($this->model, $this->attribute, $this->htmlOptions);
		echo $this->getError().$this->getHint();
		echo '</div>';
	}

	protected function captcha()
	{
		echo $this->getLabel().'<div class="input"><div class="captcha">';
		echo '<div class="widget">'.$this->widget('CCaptcha', array('showRefreshButton'=>false), true).'</div>';
		echo $this->form->textField($this->model, $this->attribute, $this->htmlOptions);
		echo $this->getError().$this->getHint();
		echo '</div></div>';
	}

	protected function uneditableField()
	{
		echo $this->getLabel().'<div class="input">';
		echo '<span class="uneditable-input">'.$this->model->{$this->attribute}.'</span>';
		echo $this->getError().$this->getHint();
		echo '</div>';
	}

	/**
	 * Returns the label for this block.
	 * @return string the label
	 */
	protected function getLabel()
	{
		if ($this->label !== false && !in_array($this->type, array('checkbox', 'radio')) && $this->hasModel())
			return $this->form->labelEx($this->model, $this->attribute);
		else if ($this->label !== null)
			return $this->label;
		else
			return '';
	}

	/**
	 * Returns the hint text for this block.
	 * @return string the hint text
	 */
	protected function getHint()
	{
		if (isset($this->htmlOptions['hint']))
		{
			$hint = $this->htmlOptions['hint'];
			unset($this->htmlOptions['hint']);
			return '<span class="help-block">'.$hint.'</span>';
		}
		else
			return '';
	}

	/**
	 * Returns the error text for this block.
	 * @return string the error text
	 */
	protected function getError()
	{
		return $this->form->error($this->model, $this->attribute);
	}
}
