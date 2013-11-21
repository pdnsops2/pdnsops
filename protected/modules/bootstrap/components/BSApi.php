<?php
/**
 * User: Pascal Brewing
 * Date: 11.09.13
 * Time: 10:59
 * @package bootstrap.components
 * Class BSApi
 */

class BSApi extends CComponent {
    // Bootstrap plugins
    const PLUGIN_AFFIX = 'affix';
    const PLUGIN_ALERT = 'alert';
    const PLUGIN_BUTTON = 'button';
    const PLUGIN_CAROUSEL = 'carousel';
    const PLUGIN_COLLAPSE = 'collapse';
    const PLUGIN_DROPDOWN = 'dropdown';
    const PLUGIN_MODAL = 'modal';
    const PLUGIN_POPOVER = 'popover';
    const PLUGIN_SCROLLSPY = 'scrollspy';
    const PLUGIN_TAB = 'tab';
    const PLUGIN_TOOLTIP = 'tooltip';
    const PLUGIN_TRANSITION = 'transition';
    const PLUGIN_TYPEAHEAD = 'typeahead';
    /**
     * @var array $assetsJs of javascript library names to be registered when initializing the library.
     */
    public $assetsJs = array();
    /**
     * @var array $assetsCss of style library names to be registered when initializing the library.
     */
    public $assetsCss = array();
    /**
     * @var string holds the published assets
     */
    protected $_assetsUrl;

    /**
     * Widget's initialization
     * @throws \CException
     */
    public function init()
    {
        /* ensure all widgets - plugins are accessible to the library */
        \Yii::import('bootstrap.widgets.*');

        /* register css assets */
        foreach ($this->assetsCss as $css) {
            $this->registerAssetCss($css);
        }
        /* register js assets */
        foreach ($this->assetsJs as $js) {
            $this->registerAssetJs($js);
        }
    }


    /**
     * Returns the assets URL.
     * Assets folder has few orphan and very useful utility libraries.
     * @return string
     */
    public function getAssetsUrl()
    {
        if (isset($this->_assetsUrl)) {
            return $this->_assetsUrl;
        } else {
            $forceCopyAssets = true;
            $path            = \Yii::getPathOfAlias('bootstrap');
            $assetsUrl       = \Yii::app()->assetManager->publish(
                $path . DIRECTORY_SEPARATOR . 'assets',
                false,
                -1,
                $forceCopyAssets
            );

            return $this->_assetsUrl = $assetsUrl;
        }
    }

    /**
     * Register a specific js file in the asset's js folder
     * @param string $jsFile
     * @param int $position the position of the JavaScript code.
     * @see CClientScript::registerScriptFile
     * @return $this
     */
    public function registerAssetJs($jsFile, $position = CClientScript::POS_END)
    {
        \Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl() . "/js/{$jsFile}", $position);
        return $this;
    }

    /**
     * Registers a specific css in the asset's css folder
     * @param string $cssFile the css file name to register
     * @param string $media the media that the CSS file should be applied to. If empty, it means all media types.
     * @return $this
     */
    public function registerAssetCss($cssFile, $media = '')
    {
        \Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl() . "/css/{$cssFile}", $media);
        return $this;
    }
} 