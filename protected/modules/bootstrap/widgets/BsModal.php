<?php
/**
 * TbModal class file.
 * @author Antonio Ramirez <ramirez.cobos@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package bootstrap/widgets
 */

Yii::import('bootstrap.behaviors.BsWidget');

/**
 * Bootstrap modal widget.
 */
class BsModal extends CWidget
{
    /**
     * @var array the HTML options for the view container tag.
     */
    public $htmlOptions = array();

    /**
     * @var array  The additional HTML attributes of the button that will show the modal. If empty array, only
     * the markup of the modal will be rendered on the page, so users can easily call the modal manually with their own
     * scripts. The following special attributes are available:
     * <ul>
     *    <li>label: string, the label of the button</li>
     * </ul>
     *
     * For available options of the button trigger, see http://twitter.github.com/bootstrap/javascript.html#modals.
     */
    public $buttonOptions = array();

    /**
     * @var boolean indicates whether the modal should use transitions. Defaults to 'true'.
     */
    public $fade = true;

    /**
     * @var bool $keyboard, closes the modal when escape key is pressed.
     */
    public $keyboard = true;

    /**
     * @var bool $show, shows the modal when initialized.
     */
    public $show = false;

    /**
     * @var mixed includes a modal-backdrop element. Alternatively, specify `static` for a backdrop which doesn't close
     * the modal on click.
     */
    public $backdrop = true;

    /**
     * @var mixed the remote url. If a remote url is provided, content will be loaded via jQuery's load method and
     * injected into the .modal-body of the modal.
     */
    public $remote;

    /**
     * @var string a javascript function that will be invoked immediately when the `show` instance method is called.
     */
    public $onShow;

    /**
     * @var string a javascript function that will be invoked when the modal has been made visible to the user
     *     (will wait for css transitions to complete).
     */
    public $onShown;

    /**
     * @var string a javascript function that will be invoked immediately when the hide instance method has been called.
     */
    public $onHide;

    /**
     * @var string a javascript function that will be invoked when the modal has finished being hidden from the user
     *     (will wait for css transitions to complete).
     */
    public $onHidden;

    /**
     * @var string[] the Javascript event handlers.
     */
    protected $events = array();

    /**
     * @var array $options the plugin options.
     */
    protected $options = array();

    /**
     * @var string
     */
    public $closeText = BSHtml::CLOSE_TEXT;

    /**
     * @var string header content
     */
    public $header;

    /**
     * @var string body of modal
     */
    public $content;

    /**
     * @var string footer content
     */
    public $footer;

    /**
     * Widget's initialization method
     */
    public function init()
    {
        $this->attachBehavior('BsWidget', new BsWidget());

        \bootstrap\helpers\BSArray::defaultValue('id', $this->getId(), $this->htmlOptions);
        \bootstrap\helpers\BSArray::defaultValue('role', 'dialog', $this->htmlOptions);
        \bootstrap\helpers\BSArray::defaultValue('tabindex', '-1', $this->htmlOptions);

        BSHtml::addCssClass('modal hide', $this->htmlOptions);
        if ($this->fade) {
            BSHtml::addCssClass('fade', $this->htmlOptions);
        }

        if (is_array($this->footer)) {
            $this->footer = implode('&nbsp;', $this->footer);
        }

        $this->initOptions();
        $this->initEvents();
    }

    /**
     * Initialize events if any
     */
    public function initEvents()
    {
        foreach (array('onShow', 'onShown', 'onHide', 'onHidden') as $event) {
            if ($this->$event !== null) {
                $modalEvent = strtolower(substr($event, 2));
                if ($this->$event instanceof CJavaScriptExpression) {
                    $this->events[$modalEvent] = $this->$event;
                } else {
                    $this->events[$modalEvent] = new CJavaScriptExpression($this->$event);
                }
            }
        }
    }

    /**
     * Initialize plugin options.
     * ***Important***: The display of the button overrides the initialization of the modal bootstrap widget.
     */
    public function initOptions()
    {
        if ($remote = \bootstrap\helpers\BSArray::popValue('remote', $this->options)) {
            $this->options['remote'] = CHtml::normalizeUrl($remote);
        }

        \bootstrap\helpers\BSArray::defaultValue('backdrop', $this->backdrop, $this->options);
        \bootstrap\helpers\BSArray::defaultValue('keyboard', $this->keyboard, $this->options);
        \bootstrap\helpers\BSArray::defaultValue('show', $this->show, $this->options);
    }

    /**
     * Widget's run method
     */
    public function run()
    {
        $this->renderModal();
        $this->renderButton();
        $this->registerClientScript();
    }

    /**
     * Renders the button
     */
    public function renderButton()
    {
        if (!empty($this->buttonOptions) && is_array($this->buttonOptions)) {
            \bootstrap\helpers\BSArray::defaultValue('data-toggle', 'modal', $this->buttonOptions);

            if ($this->remote !== null) {
                $this->buttonOptions['data-remote'] = CHtml::normalizeUrl($this->remote);
            }

            $selector = '#' . $this->htmlOptions['id'];
            $label = \bootstrap\helpers\BSArray::popValue('label', $this->buttonOptions, 'button');
            $attr = isset($this->buttonOptions['data-remote']) ? 'data-target' : 'href';
            \bootstrap\helpers\BSArray::defaultValue($attr, $selector, $this->buttonOptions);
            echo BSHtml::button($label, $this->buttonOptions);
        }
    }

    /**
     * Renders the modal markup
     */
    public function renderModal()
    {
        echo BSHtml::openTag('div', $this->htmlOptions) . PHP_EOL;

        $this->renderModalHeader();
        $this->renderModalBody();
        $this->renderModalFooter();

        echo '</div>' . PHP_EOL;
    }

    /**
     * Renders the header HTML markup of the modal
     */
    public function renderModalHeader()
    {
        echo '<div class="modal-header">' . PHP_EOL;
        if ($this->closeText) {
            echo BSHtml::closeButton($this->closeText, array('data-dismiss' => 'modal'));
        }
        echo BSHtml::tag('h3', array(), $this->header);
        echo '</div>' . PHP_EOL;
    }

    /**
     * Renders the HTML markup for the body of the modal
     */
    public function renderModalBody()
    {
        echo '<div class="modal-body">' . PHP_EOL;
        echo $this->content;
        echo '</div>' . PHP_EOL;
    }

    /**
     * Renders the HTML markup for the footer of the modal
     */
    public function renderModalFooter()
    {

        echo '<div class="modal-footer">' . PHP_EOL;
        echo $this->footer;
        echo '</div>' . PHP_EOL;
    }

    /**
     * Registers necessary client scripts.
     */
    public function registerClientScript()
    {
        $selector = '#' . $this->htmlOptions['id'];

        // do we render a button? If so, bootstrap will handle its behavior through its
        // mark-up, otherwise, register the plugin.
        if (empty($this->buttonOptions)) {
            $this->registerPlugin(BSApi::PLUGIN_MODAL, $selector, $this->options);
        }

        $this->registerEvents($selector, $this->events);
    }

}