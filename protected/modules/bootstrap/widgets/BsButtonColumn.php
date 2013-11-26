<?php
/**
 * BsButtonColumn class file.
 * @author Pascal Brewing
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package bootstrap/widgets
 */

Yii::import('bootstrap.components.BSHtml');
Yii::import('bootstrap.helpers.BSArray');
Yii::import('bootstrap.widgets.BsPager');
Yii::import('zii.widgets.grid.CButtonColumn');

/**
 * Bootstrap button column widget.
 */
class BsButtonColumn extends CButtonColumn
{
    /**
     * @var string the view button icon (defaults to BSHtml::GLYPHICON_EYE_OPEN).
     */
    public $viewButtonIcon = BSHtml::GLYPHICON_EYE_OPEN;
    /**
     * @var string the update button icon (defaults to BSHtml::GLYPHICON_PENCIL).
     */
    public $updateButtonIcon = BSHtml::GLYPHICON_PENCIL;
    /**
     * @var string the delete button icon (defaults to BSHtml::GLYPHICON_TRASH).
     */
    public $deleteButtonIcon = BSHtml::GLYPHICON_TRASH;

    /**
     * Initializes the default buttons (view, update and delete).
     */
    protected function initDefaultButtons()
    {
        parent::initDefaultButtons();

        if ($this->viewButtonIcon !== false && !isset($this->buttons['view']['icon'])) {
            $this->buttons['view']['icon'] = $this->viewButtonIcon;
        }
        if ($this->updateButtonIcon !== false && !isset($this->buttons['update']['icon'])) {
            $this->buttons['update']['icon'] = $this->updateButtonIcon;
        }
        if ($this->deleteButtonIcon !== false && !isset($this->buttons['delete']['icon'])) {
            $this->buttons['delete']['icon'] = $this->deleteButtonIcon;
        }
    }

    /**
     * Renders a link button.
     * @param string $id the ID of the button
     * @param array $button the button configuration which may contain 'label', 'url', 'imageUrl' and 'options' elements.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data object associated with the row
     */
    protected function renderButton($id, $button, $row, $data)
    {

        if (isset($button['visible']) && !$this->evaluateExpression(
                $button['visible'],
                array('row' => $row, 'data' => $data)
            )
        ) {
            return;
        }

        $url = \bootstrap\helpers\BSArray::popValue('url', $button, '#');
        if (strcmp($url, '#') !== 0) {
            $url = $this->evaluateExpression($url, array('data' => $data, 'row' => $row));
        }

        $imageUrl = \bootstrap\helpers\BSArray::popValue('imageUrl', $button, false);
        $label = \bootstrap\helpers\BSArray::popValue('label', $button, $id);
        $options = \bootstrap\helpers\BSArray::popValue('options', $button, array());

        \bootstrap\helpers\BSArray::defaultValue('data-title', $label, $options);
        \bootstrap\helpers\BSArray::defaultValue('title', $label, $options);
        \bootstrap\helpers\BSArray::defaultValue('data-toggle', 'tooltip', $options);

        if ($icon = \bootstrap\helpers\BSArray::popValue('icon', $button, false)) {
            echo CHtml::link(BSHtml::icon($icon), $url, $options);
        } else {
            if ($imageUrl && is_string($imageUrl)) {
                echo CHtml::link(CHtml::image($imageUrl, $label), $url, $options);
            } else {
                echo CHtml::link($label, $url, $options);
            }
        }
    }
}
