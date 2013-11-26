<?php
/**
 * User: Pascal Brewing
 * Date: 11.09.13
 * Time: 14:13
 * @package bootstrap.helpers
 * Class MainNavPartial
 */
namespace bootstrap\helpers;

class NavPartial extends \CModel {
    public static $view;
    public static $params;

    public function attributeNames()
    {
        // TODO: Implement attributeNames() method.
        return array(
          'view' => self::$view
        );
    }

    /**
     * @param $view
     * @return mixed
     */
    public static  function setView($view)
    {
        return self::$view = $view;
    }

    /**
     * @param $view
     * @param array $params
     * @return array
     */
    public static function getView($view,array $params = array())
    {
        self::setView($view);
        self::setParams($params);
        return array(
            'partial' => self::$view,
            'params' => self::$params
        );
    }
    /**
     * @param mixed $params
     */
    public static function setParams(array $params)
    {
        self::$params = $params;
    }

    /**
     * @return mixed
     */
    public static function getParams()
    {
        return self::$params;
    }

}