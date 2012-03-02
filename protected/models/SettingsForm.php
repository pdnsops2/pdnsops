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

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('defaultTTL,soaRetry,soaRefresh,soaExpire', 'required'),
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
			'domainMasterIP'=>'Domain Master IP',
			'defaultTTL'=>'Default TTL',
			'soaRetry'=>'SOA Retry',
			'soaRefresh'=>'SOA Refresh',
			'soaExpire'=>'SOA Expire',
			'ns1'=>'Primary Nameserver',
			'ns2'=>'Secondary Nameserver',
			'ns3'=>'Tertiary Nameserver',
			'ns4'=>'Quaternary Nameserver',
			'ns5'=>'Quinary Nameserver',
			'ns6'=>'Senary Nameserver',
			'ns7'=>'Septenary Nameserver',
			'ns8'=>'Octonary Nameserver',
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
}