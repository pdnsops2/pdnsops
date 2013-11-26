<?php
/**
 * BsListView class file.
 * @author Pascal Brewing
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package bootstrap/widgets
 */

Yii::import('zii.widgets.CListView');

/**
 * Bootstrap Zii list view.
 */
class BsListView extends CListView
{
    /**
     * @var string the CSS class name for the pager container. Defaults to 'pagination'.
     */
    public $pagerCssClass = 'pagination';
    /**
     * @var array the configuration for the pager.
     * Defaults to <code>array('class'=>'ext.bootstrap.widgets.BsPager')</code>.
     */
    public $pager = array('class' => 'bootstrap.widgets.BsPager');
    /**
     * @var string the URL of the CSS file used by this detail view.
     * Defaults to false, meaning that no CSS will be included.
     */
    public $cssFile = false;
    /**
     * @var string the template to be used to control the layout of various sections in the view.
     */
    public $template = "{items}\n<div class=\"row-fluid\"><div class=\"span6\">{pager}</div><div class=\"span6\">{summary}</div></div>";

    /**
     * Renders the empty message when there is no data.
     */
    public function renderEmptyText()
    {
        $emptyText = $this->emptyText === null ? Yii::t('zii', 'No results found.') : $this->emptyText;
        echo BSHtml::tag('div', array('class' => 'empty', 'span' => 12), $emptyText);
    }
}
