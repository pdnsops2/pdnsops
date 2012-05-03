<?php

/**
 * SettingsForm class.
 * SettingsForm is the data structure for keeping
 * settings form data. It is used by the 'settings' action of 'SiteController'.
 */
class SettingsForm extends CFormModel
{
	public $domainMasterIP;
	
	public $defaultTTL;
	
	public $soaRetry;
	
	public $soaRefresh;
	
	public $soaExpire;

	public $ns1;
	
	public $ns2;

	public $ns3;

	public $ns4;

	public $ns5;

	public $ns6;

	public $ns7;

	public $ns8;
	
	public $theme;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('defaultTTL,soaRetry,soaRefresh,soaExpire,theme', 'required'),
			array('defaultTTL,soaRetry,soaRefresh,soaExpire', 'numerical', 'integerOnly'=>true),
			array('domainMasterIP,ns1,ns2,ns3,ns4,ns5,ns6,ns7,ns8', 'length', 'max'=>15),
			array('domainMasterIP,ns1,ns2,ns3,ns4,ns5,ns6,ns7,ns8', 'ext.validators.FIpValidator', 'version'=>'ipv4'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'domainMasterIP'=>Yii::t('app','setting.domainMasterIP'),
			'defaultTTL'=>Yii::t('app','setting.defaultTTL'),
			'soaRetry'=>Yii::t('app','setting.soaRetry'),
			'soaRefresh'=>Yii::t('app','setting.soaRefresh'),
			'soaExpire'=>Yii::t('app','setting.soaExpire'),
			'ns1'=>Yii::t('app','setting.ns1'),
			'ns2'=>Yii::t('app','setting.ns2'),
			'ns3'=>Yii::t('app','setting.ns3'),
			'ns4'=>Yii::t('app','setting.ns4'),
			'ns5'=>Yii::t('app','setting.ns5'),
			'ns6'=>Yii::t('app','setting.ns6'),
			'ns7'=>Yii::t('app','setting.ns7'),
			'ns8'=>Yii::t('app','setting.ns8'),
			'theme'=>Yii::t('app','setting.theme'),
		);
	}
	
	public function load()
	{
		$settings = Setting::model()->findAll();
		foreach ($settings as $setting)
		{
			$this->{$setting->key} = $setting->value;
		}
	}
	
	public function save()
	{
		$reflect = new ReflectionClass($this);
		$props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

		foreach ($props as $prop)
		{
			$setting = Setting::model()->findByPk($prop->name);
			$setting->value = $this->{$prop->name};
			$setting->save(false);
		}		
	}
	
	public function getThemes() 
	{
		$result = array();
		$themeDir = getcwd() . '/themes/';

		if ($handle = opendir($themeDir)) 
		{
			while (false !== ($entry = readdir($handle))) 
			{
				if ($entry == '.' || $entry == '..')
					continue;

				if (is_dir($themeDir . $entry))
					$result[$entry] = $entry;
			}
			
			closedir($handle);
		}
	
		return $result;
	}
}