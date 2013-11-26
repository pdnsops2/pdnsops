<?php
/**
 * IButton class file.
 *
 * @author Junior - df9 <scoob.junior@gmail.com> <junior@df9.com.br>
 * @link http://www.df9.com.br/
 * @copyright IButton class Copyright &copy; 2012 df9
 * @license IButton class is Free to use for both personal or commercial projects.
 * Visit http://www.givainc.com/labs/ibutton_jquery_plugin.htm for Giva's plugin license
 */

/**
 * IButton implements the Giva Labs jQuery iButton plugin.
 * More info at: http://www.givainc.com/labs/ibutton_jquery_plugin.htm
 *
 * IButton makes checkboxes and/or radio buttons look like Apple's "on-off" buttons
 *
 * IButton accepts CSS selectors, so it is easy to give any checkbox or radio button the effect.
 * It was also added support for model/attribute pairs, so that you can easily integrate into your Yii's forms
 * 
 * 
 * Sample 1 - turn all checkboxes in the page into iButtons:
 * 
 *	<?php $this->widget('ext.ibutton.IButton', array(
 *			'selector'=>':checkbox',
 *			'options' =>array(
 *				'duration' => 250,
 *				'change'=>'js:function(){alert("ooohay! it has been changed!");}'
 *			)
 *	));?>
 * 
 * 
 * 
 * Sample 2 - turn the model/attribute pair into an iButton:
 * 
 *	<?php $this->widget('ext.ibutton.IButton', array(
 *			'model'     => $model,
 *			'attribute' => 'is_master',
 *			'options' =>array(
 *				'labelOn'=>Yii::t('app','Yes'),
 *				'labelOff'=>Yii::t('app','No'),
 *				'change'=>'js:function($choice){
 *					$choice.is(":checked")?alert("Hey, master!"):alert("Sorry, you are not my master!");
 *				}'
 *			)
 *	));?>
 * 
 * 
 * 
 * Sample 3 - turn both all radio buttons and a model/attribute pair into iButtons:
 * 
 *	<?php $this->widget('ext.ibutton.IButton', array(
 *			'selector'  => ':radio',
 *			'model'     => $model,
 *			'attribute' => 'is_master',
 *			'options' =>array(
 *				'labelOn'=>Yii::t('app','Yes'),
 *				'labelOff'=>Yii::t('app','No'),
 *				'change'=>'js:function($choice){
 *					$choice.is(":checked")?alert("Check on"):alert("Check off");
 *				}'
 *			)
 *	));?>
 * 
 * 
 * Sample 4 - convert all checkboxes and radios in a form into iButtons using form:
 *	<?php $this->widget('ext.ibutton.IButton', array(
 *			'selector'  => 'form',
 *			'options' =>array(
 *				'labelOn'=>Yii::t('app','Yes'),
 *				'labelOff'=>Yii::t('app','No')
 *			)
 *	));?>
 * 
 * 
 * Live demo at http://www.givainc.com/labs/ibutton_example.htm
 * 
 * 
 * @author Junior - df9 <scoob.junior@gmail.com> <junior@df9.com.br>
 * @version 1.2
 */

class IButton extends CWidget{

	//the javascript id will be registered as ibutton_$id
	public $id;
	
	//in debug mode publishes normal version of js and css files, if debug is false, 
	//publishes minified versions
	public $debug = false;
	
	//which css selector should be converted to iButton
	public $selector  = null;
	
	//model/attribute pair to convert to iButton
	public $model     = null;
	public $attribute = null;
	
	//plugin options
	public $options;
	
	//controls the published javascript
	public $useJqueryMetadata = true; //The use of the Metadata plug-in allows you to define the options for your buttons within the class attribute of the form element. 
	public $useJqueryEasing   = true; //The Easing Plug-in expands the options for easing in the animation of the sliding button. 
	
	//default plugin options
	private $defaultOptions = array(
		'duration'          => 200,      // the speed of the animation 
		'easing'            => 'swing',  // the easing animation to use 
		'labelOn'           => 'ON',     // the text to show when toggled on 
		'labelOff'          => 'OFF',    // the text to show when toggled off 
		'resizeHandle'      => 'auto',   // determines if handle should be resized 
		'resizeContainer'   => 'auto',   // determines if container should be resized 
		'enableDrag'        => true,     // determines if we allow dragging 
		'enableFx'          => true,     // determines if we show animation 
		'allowRadioUncheck' => false,    // determine if a radio button should be able to be unchecked 
		'clickOffset'       => 120,      // if millseconds between a mousedown & mouseup event this value, then considered a mouse click
 
		// define the class statements 
		'className'         => "", // an additional class name to attach to the main container 
		'classContainer'    => "ibutton-container" ,
		'classDisabled'     => "ibutton-disabled" ,
		'classFocus'        => "ibutton-focus" ,
		'classLabelOn'      => "ibutton-label-on" ,
		'classLabelOff'     => "ibutton-label-off" ,
		'classHandle'	    => "ibutton-handle" ,
		'classHandleMiddle' => "ibutton-handle-middle" ,
		'classHandleRight'  => "ibutton-handle-right" ,
		'classHandleActive' => "ibutton-active-handle" ,
		'classPaddingLeft'  => "ibutton-padding-left" ,
		'classPaddingRight' => "ibutton-padding-right" ,
 
		// event handlers 
		'init'    => null,  // callback that occurs when a iButton is initialized 
		'change'  => null,  // callback that occurs when the button state is changed 
		'click'	  => null,  // callback that occurs when the button is clicked 
		'disable' => null,  // callback that occurs when the button is disabled/enabled 
		'destroy' => null,  // callback that occurs when the button is destroyed
	);
	
	//code that will be generated
	private $jsCode;
	
	/*
	 * Performs some tasks before running the plugin:
	 * - merge user options and default options
	 * - published needed javascript and css files
	 * - create the javascript code and stores into $this->jsCode
	 */
	public function init(){
		$this->makeOptions();
		$this->publishAssets();		
		$this->makeJsCode();
	}
	
	/*
	 * Starts the plugin
	 */
	public function run(){
		Yii::app()->clientScript->registerScript(
			'ibutton_'.$this->id, 
			$this->jsCode, 
			CClientScript::POS_END
		);
	}

	/*
	 * Merge user options with default options
	 */
	private function makeOptions(){
		isset($this->options) && $this->options = CMap::mergeArray($this->defaultOptions, $this->options);
	}
	
	
	/*
	 * Creates the javascript code needed
	 */
	private function makeJsCode(){
		//if the user specifies a selector and a model->attribute pair, make both iButtons
		$selectors = $this->selector ? $this->selector : null;
		if($this->model && $this->attribute):
			$selectors .= $selectors ? ', #' . CHtml::activeId($this->model, $this->attribute) : '#'.CHtml::activeId($this->model, $this->attribute);
		endif;
		
		//create the js code
		$this->jsCode = "
			$(document).ready(function(){
				var opt = eval(".CJavaScript::encode($this->options).");
				$('".$selectors."').iButton(opt);
			});";
	}
	
	/*
	 * Publishes the resources needed
	 */
	private function publishAssets(){
		$cs = Yii::app()->clientScript;
		$assetsPath = Yii::app()->assetManager->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'resources');
		
		$cs->registerCssFile($assetsPath . '/css/' . ($this->debug ? 'jquery.ibutton.css' : 'jquery.ibutton.min.css'));
		
		//register the Yii's built in Metadata jQuery plugin
		if($this->useJqueryMetadata)
			$cs->registerCoreScript('metadata');
		
		//register the easing plugin
		if($this->useJqueryEasing)
			$cs->registerScriptFile($assetsPath . '/lib/' . 'jquery.easing.1.3.js');
		
		$cs->registerScriptFile($assetsPath . '/lib/' . ($this->debug ? 'jquery.ibutton.js' : 'jquery.ibutton.min.js') , CClientScript::POS_END);
	}
}

?>
