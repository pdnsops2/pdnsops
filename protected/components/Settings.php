<?php

// TODO: use memcache
class Settings extends CApplicationComponent
{
	const MAX_NAMESERVERS = 8;

	private static $cache = null;

	public function __get($name)
	{
		if (Settings::$cache === null)
		{
			Settings::initCache();
		}
	
		return Settings::$cache[$name];
	}
	
	public function __set($name, $value)
	{
		// TODO: implement
	}
	
	private static function initCache()
	{
		Settings::$cache = array();
	
		$settings=Setting::model()->findAll();
		foreach ($settings as $setting)
		{
			Settings::$cache[$setting->key] = $setting->value;
		}
	}
}
