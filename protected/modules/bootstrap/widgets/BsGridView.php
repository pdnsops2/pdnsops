<?php
/**
 * BsGridView class file.
 * @author Pascal Brewing
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package bootstrap/widgets
 */

Yii::import('zii.widgets.grid.CGridView');
Yii::import('bootstrap.widgets.BsDataColumn');
Yii::import('bootstrap.widgets.BsPager');

/**
 * Bootstrap Zii grid view.
 */
class BsGridView extends CGridView
{
    /**
     * @var string|array the table style.
     * Valid values are BSHtml::GRID_TYPE_STRIPED, BSHtml::GRID_TYPE_BORDERED, BSHtml::GRID_TYPE_CONDENSED and/or
     * BSHtml::GRID_TYPE_HOVER.
     */
    public $type;
    /**
     * @var string the CSS class name for the pager container. Defaults to 'pagination'.
     */
    public $pagerCssClass = 'pagination';
    /**
     * @var array the configuration for the pager.
     * Defaults to <code>array('class'=>'ext.bootstrap.widgets.TbPager')</code>.
     */
    public $pager = array('class' => 'bootstrap.widgets.BsPager');
    /**
     * @var string the URL of the CSS file used by this grid view.
     * Defaults to false, meaning that no CSS will be included.
     */
    public $cssFile = false;
    /**
     * @var string the template to be used to control the layout of various sections in the view.
     */
    public $template = "{items}\n{pager}{summary}";


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $classes = array('table');
        if (isset($this->type) && !empty($this->type)) {
            if (is_string($this->type)) {
                $this->type = explode(' ', $this->type);
            }

            foreach ($this->type as $type) {
                $classes[] = 'table-' . $type;
            }
        }
        if (!empty($classes)) {
            $classes = implode(' ', $classes);
            if (isset($this->itemsCssClass)) {
                $this->itemsCssClass .= ' ' . $classes;
            } else {
                $this->itemsCssClass = $classes;
            }
        }
    }

    /**
     * Creates column objects and initializes them.
     */
    protected function initColumns()
    {
        foreach ($this->columns as $i => $column) {
            if (is_array($column) && !isset($column['class'])) {
                $this->columns[$i]['class'] = 'bootstrap.widgets.BsDataColumn';
            }
        }
        parent::initColumns();
    }

    /**
     * Creates a column based on a shortcut column specification string.
     * @param mixed $text the column specification string
     * @return \BsDataColumn|\CDataColumn the column instance
     * @throws CException if the column format is incorrect
     */
    protected function createDataColumn($text)
    {
        if (!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new CException(Yii::t(
                'zii',
                'The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'
            ));
        }
        $column = new BsDataColumn($this);
        $column->name = $matches[1];
        if (isset($matches[3]) && $matches[3] !== '') {
            $column->type = $matches[3];
        }
        if (isset($matches[5])) {
            $column->header = $matches[5];
        }
        return $column;
    }
  
}
