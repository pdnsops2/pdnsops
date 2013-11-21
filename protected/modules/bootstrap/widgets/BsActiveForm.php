<?php
/**
 * BsActiveForm class file.
 * @author Pascal Brewing
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package bootstrap/widgets
 */

Yii::import('bootstrap.components.BSHtml');
Yii::import('bootstrap.behaviors.BsWidget');

/**
 * Bootstrap active form widget.
 */
class BsActiveForm extends CActiveForm
{
    /**
     * @var string the form layout.
     */
    public $layout;
    /**
     * @var string the help type. Valid values are BSHtml::HELP_INLINE and BSHtml::HELP_BLOCK.
     */
    public $helpType = BSHtml::HELP_TYPE_BLOCK;
    /**
     * @var string the CSS class name for error messages.
     */
    public $errorMessageCssClass = 'has-error';
    /**
     * @var string the CSS class name for success messages.
     */
    public $successMessageCssClass = 'has-success';

    /**
     * @var boolean whether to hide inline errors. Defaults to false.
     */
    public $hideInlineErrors = false;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        $this->attachBehavior('BsWidget', new BsWidget());
        $this->copyId();
        if ($this->stateful) {
            echo BSHtml::statefulFormBs($this->layout, $this->action, $this->method, $this->htmlOptions);
        } else {
            echo BSHtml::beginFormBs($this->layout, $this->action, $this->method, $this->htmlOptions);
        }
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
    public function error(
        $model,
        $attribute,
        $htmlOptions = array(),
        $enableAjaxValidation = true,
        $enableClientValidation = true
    ) {
        if (!$this->enableAjaxValidation) {
            $enableAjaxValidation = false;
        }
        if (!$this->enableClientValidation) {
            $enableClientValidation = false;
        }
        if (!$enableAjaxValidation && !$enableClientValidation) {
            return BSHtml::error($model, $attribute, $htmlOptions);
        }
        $id = CHtml::activeId($model, $attribute);
        $inputID = \bootstrap\helpers\BSArray::getValue('inputID', $htmlOptions, $id);
        unset($htmlOptions['inputID']);
        \bootstrap\helpers\BSArray::defaultValue('id', $inputID . '_em_', $htmlOptions);
        $option = array(
            'id' => $id,
            'inputID' => $inputID,
            'errorID' => $htmlOptions['id'],
            'model' => get_class($model),
            'name' => $attribute,
            'enableAjaxValidation' => $enableAjaxValidation,
            'inputContainer' => 'div.form-group', // Bootstrap requires this,
            'errorCssClass' => $this->errorMessageCssClass,
            'successCssClass' => $this->successMessageCssClass
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
        foreach ($optionNames as $name) {
            if (isset($htmlOptions[$name])) {
                $option[$name] = \bootstrap\helpers\BSArray::popValue($name, $htmlOptions);
            }
        }

        if ($model instanceof CActiveRecord && !$model->isNewRecord) {
            $option['status'] = 1;
        }
        if ($enableClientValidation) {
            $validators = \bootstrap\helpers\BSArray::getValue('clientValidation', $htmlOptions, array());
            $attributeName = $attribute;
            if (($pos = strrpos($attribute, ']')) !== false && $pos !== strlen($attribute) - 1) // e.g. [a]name
            {
                $attributeName = substr($attribute, $pos + 1);
            }
            foreach ($model->getValidators($attributeName) as $validator) {
                if ($validator->enableClientValidation) {
                    if (($js = $validator->clientValidateAttribute($model, $attributeName)) != '') {
                        $validators[] = $js;
                    }
                }
            }
            if ($validators !== array()) {
                $option['clientValidation'] = "js:function(value, messages, attribute) {\n" . implode(
                        "\n",
                        $validators
                    ) . "\n}";
            }
        }
        $html = BSHtml::error($model, $attribute, $htmlOptions);
        if ($html === '') {
            $htmlOptions['type'] = $this->helpType;
            BSHtml::addCssStyle('display:none', $htmlOptions);
            $html = BSHtml::help('', $htmlOptions);
        }
        $this->attributes[$inputID] = $option;
        return $html;
    }

    /**
     * Displays a summary of validation errors for one or several models.
     * @param mixed $models the models whose input errors are to be displayed.
     * @param string $header a piece of HTML code that appears in front of the errors
     * @param string $footer a piece of HTML code that appears at the end of the errors
     * @param array $htmlOptions additional HTML attributes to be rendered in the container div tag.
     * @return string the error summary. Empty if no errors are found.
     */
    public function errorSummary($models, $header = null, $footer = null, $htmlOptions = array())
    {
        if (!$this->enableAjaxValidation && !$this->enableClientValidation) {
            return BSHtml::errorSummary($models, $header, $footer, $htmlOptions);
        }
        \bootstrap\helpers\BSArray::defaultValue('id', $this->id . '_es_', $htmlOptions);
        $html = BSHtml::errorSummary($models, $header, $footer, $htmlOptions);
        if ($html === '') {
            if ($header === null) {
                $header = '<p>' . Yii::t('yii', 'Please fix the following input errors:') . '</p>';
            }
            BSHtml::addCssClass(BSHtml::$errorSummaryCss, $htmlOptions);
            BSHtml::addCssStyle('display:none', $htmlOptions);
            $html = CHtml::tag('div', $htmlOptions, $header . '<ul><li>dummy</li></ul>' . $footer);
        }
        $this->summaryID = $htmlOptions['id'];
        return $html;
    }

    /**
     * Renders a text field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     * @see BSHtml::activeTextField
     */
    public function textField($model, $attribute, $htmlOptions = array())
    {
        
        return BSHtml::activeTextField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a password field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     * @see BSHtml::activePasswordField
     */
    public function passwordField($model, $attribute, $htmlOptions = array())
    {
        
        return BSHtml::activePasswordField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a url field for a model attribute.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field
     * @see BSHtml::activeUrlField
     */
    public function urlField($model, $attribute, $htmlOptions = array())
    {
        
        return BSHtml::activeUrlField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders an email field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     * @see BSHtml::activeEmailField
     */
    public function emailField($model, $attribute, $htmlOptions = array())
    {
        
        return BSHtml::activeEmailField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders an telephone field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     * @see BSHtml::activeEmailField
     */
    public function telField($model, $attribute, $htmlOptions = array())
    {

        return BSHtml::activeTelField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a number field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     * @see BSHtml::activeNumberField
     */
    public function numberField($model, $attribute, $htmlOptions = array())
    {
        
        return BSHtml::activeNumberField($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a range field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     * @see BSHtml::activeRangeField
     */
    public function rangeField($model, $attribute, $htmlOptions = array())
    {
        
        return BSHtml::activeRangeField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a date field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     */
    public function dateField($model, $attribute, $htmlOptions = array())
    {
        
        return BSHtml::activeDateField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a text area for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated text area.
     * @see BSHtml::activeTextArea
     */
    public function textArea($model, $attribute, $htmlOptions = array())
    {
        
        return BSHtml::activeTextArea($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a file field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes
     * @return string the generated input field.
     * @see BSHtml::activeFileField
     */
    public function fileField($model, $attribute, $htmlOptions = array())
    {
        
        return BSHtml::activeFileField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a radio button for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated radio button.
     * @see BSHtml::activeRadioButton
     */
    public function radioButton($model, $attribute, $htmlOptions = array())
    {
        return BSHtml::activeRadioButton($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a checkbox for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated check box.
     * @see BSHtml::activeCheckBox
     */
    public function checkBox($model, $attribute, $htmlOptions = array())
    {
        return BSHtml::activeCheckBox($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a dropdown list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $data data for generating the list options (value=>display).
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated drop down list.
     * @see BSHtml::activeDropDownList
     */
    public function dropDownList($model, $attribute, $data, $htmlOptions = array())
    {
        return BSHtml::activeDropDownList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Renders a list box for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $data data for generating the list options (value=>display).
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated list box.
     * @see BSHtml::activeListBox
     */
    public function listBox($model, $attribute, $data, $htmlOptions = array())
    {
        return BSHtml::activeListBox($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Renders a radio button list for a model attribute
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $data data for generating the list options (value=>display)
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated radio button list.
     * @see BSHtml::activeRadioButtonList
     */
    public function radioButtonList($model, $attribute, $data, $htmlOptions = array())
    {
        return BSHtml::activeRadioButtonList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Renders an inline radio button list for a model attribute
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $data data for generating the list options (value=>display)
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated radio button list.
     * @see BSHtml::activeInlineRadioButtonList
     */
    public function inlineRadioButtonList($model, $attribute, $data, $htmlOptions = array())
    {
        return BSHtml::activeInlineRadioButtonList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Renders a checkbox list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $data data for generating the list options (value=>display)
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated checkbox list.
     * @see BSHtml::activeCheckBoxList
     */
    public function checkBoxList($model, $attribute, $data, $htmlOptions = array())
    {
        return BSHtml::activeCheckBoxList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Renders an inline checkbox list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $data data for generating the list options (value=>display)
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated checkbox list.
     * @see BSHtml::activeInlineCheckBoxList
     */
    public function inlineCheckBoxList($model, $attribute, $data, $htmlOptions = array())
    {
        return BSHtml::activeInlineCheckBoxList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Renders an uneditable field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated field.
     * @see BSHtml::activeUneditableField
     */
    public function uneditableField($model, $attribute, $htmlOptions = array())
    {
        return BSHtml::activeUneditableField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a search query field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input.
     * @see BSHtml::activeSearchField
     */
    public function searchQuery($model, $attribute, $htmlOptions = array())
    {
        return BSHtml::activeSearchQueryField($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a text field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeTextFieldControlGroup
     */
    public function textFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeTextFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a password field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activePasswordFieldControlGroup
     */
    public function passwordFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activePasswordFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with an url field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeUrlFieldControlGroup
     */
    public function urlFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeUrlFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with an email field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeEmailFieldControlGroup
     */
    public function emailFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeEmailFieldControlGroup($model, $attribute, $htmlOptions);
    }

   /**
     * Generates a control group with an telephone field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeTelFieldControlGroup
     */
    public function telFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeTelFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a number field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeNumberFieldControlGroup
     */
    public function numberFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeNumberFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a range field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeRangeFieldControlGroup
     */
    public function rangeFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeRangeFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a date field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeDateFieldControlGroup
     */
    public function dateFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeDateFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a text area for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeTextAreaControlGroup
     */
    public function textAreaControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeTextAreaControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a check box for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeCheckBoxControlGroup
     */
    public function checkBoxControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeCheckBoxControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a radio button for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeRadioButtonControlGroup
     */
    public function radioButtonControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeRadioButtonControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a drop down list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeDropDownListControlGroup
     */
    public function dropDownListControlGroup($model, $attribute, $data, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeDropDownListControlGroup($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a control group with a list box for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeListBoxControlGroup
     */
    public function listBoxControlGroup($model, $attribute, $data, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeListBoxControlGroup($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a control group with a file field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeFileFieldControlGroup
     */
    public function fileFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeFileFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a radio button list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $data data for generating the list options (value=>display).
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeRadioButtonListControlGroup
     */
    public function radioButtonListControlGroup($model, $attribute, $data, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeRadioButtonListControlGroup($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a control group with an inline radio button list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $data data for generating the list options (value=>display).
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeInlineCheckBoxListControlGroup
     */
    public function inlineRadioButtonListControlGroup($model, $attribute, $data, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeInlineRadioButtonListControlGroup($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a control group with a check box list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $data data for generating the list options (value=>display).
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeCheckBoxListControlGroup
     */
    public function checkBoxListControlGroup($model, $attribute, $data, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeCheckBoxListControlGroup($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a control group with an inline check box list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $data data for generating the list options (value=>display).
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeInlineCheckBoxListControlGroup
     */
    public function inlineCheckBoxListControlGroup($model, $attribute, $data, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeInlineCheckBoxListControlGroup($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a control group with an uneditable field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeUneditableFieldControlGroup
     */
    public function uneditableFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeUneditableFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a search field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see BSHtml::activeSearchFieldControlGroup
     */
    public function searchQueryControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return BSHtml::activeSearchQueryControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Processes the options for a input row.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions the options.
     * @return array the processed options.
     */
    protected function processRowOptions($model, $attribute, $options)
    {
        $errorOptions = \bootstrap\helpers\BSArray::popValue('errorOptions', $options, array());
        $errorOptions['type'] = $this->helpType;
        $error = $this->error($model, $attribute, $errorOptions);

        // kind of a hack for ajax forms but this works for now.
        if (!empty($error) && strpos($error, 'display:none') === false) {
            $options['color'] = BSHtml::INPUT_COLOR_ERROR;
        }
        if (!$this->hideInlineErrors) {
            $options['error'] = $error;
        }
        $helpOptions = \bootstrap\helpers\BSArray::popValue('helpOptions', $options, array());
        $helpOptions['type'] = $this->helpType;
        $labelOptions = \bootstrap\helpers\BSArray::popValue('labelOptions',$options,array());

        $options['helpOptions'] = $helpOptions;
        $options['labelOptions'] = self::setLabelOptionsByLayout($this->layout,$labelOptions);
        $options['formLayout'] = $this->layout;
        return $options;
    }

    /**
     * set the label CssClass by Layout
     * @param string $layout
     * @param array $labelOptions
     * @return array new label options
     */
    static private function setLabelOptionsByLayout($layout,$labelOptions=array()){

        if(empty($layout)){
            BSHtml::addCssClass('control-label',$labelOptions);
            return $labelOptions;
        }
        if($layout === BSHtml::FORM_LAYOUT_INLINE){
            BSHtml::addCssClass('control-label',$labelOptions);
            BSHtml::addCssClass('sr-only',$labelOptions);
            return $labelOptions;
        }
        $labelClass = \bootstrap\helpers\BSArray::popValue('class',$labelOptions,BSHtml::FORM_LAYOUT_HORIZONTAL_LABEL_CLASS);
        BSHtml::addCssClass('control-label',$labelOptions);
        BSHtml::addCssClass($labelClass,$labelOptions);
        return $labelOptions;

    }

}
