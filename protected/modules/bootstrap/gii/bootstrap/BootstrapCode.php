<?php
/**
 * BootstrapCode class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package bootstrap.gii
 */

Yii::import('gii.generators.crud.CrudCode');

class BootstrapCode extends CrudCode
{
    public function generateControlGroup($modelClass, $column)
    {
        if ($column->type === 'boolean') {
            return "BSHtml::activeCheckBoxControlGroup(\$model,'{$column->name}')";
        } else {
            if (stripos($column->dbType, 'text') !== false) {
                return "BSHtml::activeTextAreaControlGroup(\$model,'{$column->name}',array('rows'=>6))";
            } else {
                if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                    $inputField = 'activePasswordControlGroup';
                } else {
                    $inputField = 'activeTextFieldControlGroup';
                }

                if ($column->type !== 'string' || $column->size === null) {
                    return "BSHtml::{$inputField}(\$model,'{$column->name}')";
                } else {
                    if (($size = $maxLength = $column->size) > 60) {
                        $size = 60;
                    }
                    return "BSHtml::{$inputField}(\$model,'{$column->name}',array('size'=>$size,'maxlength'=>$maxLength))";
                }
            }
        }
    }

    public function generateActiveControlGroup($modelClass, $column)
    {
        if ($column->type === 'boolean') {
            return "\$form->checkBoxControlGroup(\$model,'{$column->name}')";
        } else {
            if (stripos($column->dbType, 'text') !== false) {
                return "\$form->textAreaControlGroup(\$model,'{$column->name}',array('rows'=>6))";
            } else {
                if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                    $inputField = 'passwordFieldControlGroup';
                } else {
                    $inputField = 'textFieldControlGroup';
                }

                if ($column->type !== 'string' || $column->size === null) {
                    return "\$form->{$inputField}(\$model,'{$column->name}')";
                } else {
                    return "\$form->{$inputField}(\$model,'{$column->name}',array('maxlength'=>$column->size))";
                }
            }
        }
    }
}
