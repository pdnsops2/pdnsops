<?php
/**
 * User: Pascal Brewing
 * Date: 15.09.13
 * Time: 18:32
 * @package ${DIR}.MNav
 * Class MNav
 */

class MNav extends CInputWidget {
    //Types
    const MIDDLE_NAV_R = 'middleNavR';
    const MIDDLE_NAV_A = 'middleNavA';
    const MIDDLE_NAV_S = 'middleNavS';
    const MIDDLE_NAV_FREE = 'middleFree';
    //colors
    /**
     * .bGreen:hover, .bRed:hover, .bBlue:hover, .bGreyish:hover, .bBlack:hover, .bLightBlue:hover, .bGold:hover, .bSea:hover, .bBrown:hover
     */
    const BUTTON_SUCCESS = 'bGreen';
    const BUTTON_DANGER = 'bRed';
    const BUTTON_BLUE = 'bBlue';
    const BUTTON_GREYISH = 'bGreyish';
    const BUTTON_BLACK = 'bBlack';
    const BUTTON_BLUE_BLIGHT = 'bLightBlue';
    const BUTTON_GOLD = 'bGold';
    const BUTTON_SEA = 'bSea';
    const BUTTON_BROWN = 'bBrown';

    public $model;
    public $items = array();
    public $type = MNav::MIDDLE_NAV_R;
    private $viewPath;
    private $_assetsUrl;

    /**
     *
     */
    public function run(){
//        CVarDumper::dump($this->items,10,true);
        $path = Yii::getPathOfAlias('bootstrap.extensions.middlenav.views');
        $this->registerAssetCss('middlenav.css');
//        $this->prepareItems();
        $this->renderInternal($path.'/mnav.php',array('items' => $this->prepareItems(),'type' => $this->type));
    }

    private function prepareItems(){
        $option = array();
        foreach($this->items as $item){
            $label = '';
            if(isset($item['icon'])){
                $label .= BSHtml::icon($item['icon'].' glyphicon iconb');
            }


//            CVarDumper::dump($htmlOptions,10,true);

            if($this->type === MNav::MIDDLE_NAV_A || $this->type === MNav::MIDDLE_NAV_FREE && isset($item['label'])){
                $label .= "<span>{$item['label']} </span>";
            }

            $htmlOptions = array();
            $cssclass = 'tipN ';

//            if(isset($item['icon']))
//                $label .= BSHtml::icon()$item['icon'].' iconb';

            if(isset($item['color']))
                $cssclass .= $item['color'];

            $htmlOptions['class'] = $cssclass;

            if(isset($item['tooltip']))
                $htmlOptions['data-toggle'] = 'tooltip';

            if(isset($item['tooltip']['label']))
                $htmlOptions['data-original-title'] = $item['tooltip']['label'];

            if(isset($item['tooltip']['placement']))
                $htmlOptions['data-placement'] = $item['tooltip']['placement'];

            if(isset($item['badge']))
                $htmlOptions['badge'] = $item['badge'];

            if(isset($item['label']))
                $htmlOptions['title'] = $item['label'];

            $option[] = array(
                $label,
                $item['url'],

                $htmlOptions,

            );
        }
        return $option;
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
            $path            = \Yii::getPathOfAlias('bootstrap.extensions.middlenav');
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