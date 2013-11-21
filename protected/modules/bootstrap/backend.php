<?php

defined('APP_CONFIG_NAME') or define('APP_CONFIG_NAME', 'backend');

// web application configuration
return array(
    'basePath' => realPath(__DIR__ . '/..'),
    // path aliases
    'aliases' => array(
        // assumed the use of yiistrap and yiiwheels extensions
        'bootstrap' => 'application.modules.bootstrap',
        'menu' => 'application.modules.pb_menu',
        'chartjs' => 'bootstrap.extensions.yii-chartjs-master'
    ),

    // application behaviors
    'behaviors' => array(),
    'preload' => array('bootstrap', 'chartjs'),
    // controllers mappings
    'controllerMap' => array(),
    'import' => array(
        'bootstrap.*',
        'bootstrap.components.*',
        'bootstrap.models.*',
        'bootstrap.controllers.*',
        'bootstrap.helpers.*',
        'bootstrap.widgets.*',
        'bootstrap.extensions.*',
        'chartjs.*',
        'chartjs.widgets.*',
        'chartjs.components.*',
        'menu.*',
        'menu.models.*',
        'menu.widgets.*',
        'menu.components.*',
        'menu.controllers.*',
        'menu.extensions.*',
    ),
    // application modules
    'modules' => array(
        'bootstrap' => array(
            'class' => 'bootstrap.BootStrapModule'
        ),
        'menu' => array(
            'class' => 'menu.PbMenuModule'
        ),
    ),

    // application components
    'components' => array(
        'bsHtml' => array(
            'class' => 'bootstrap.components.BSHtml'
        ),
        'chartjs' => array(
            'class' => 'bootstrap.extensions.yii-chartjs-master.components.ChartJs'
        ),
        'menu' => array(
            'class' => 'menu.components.MenuPlugin'
        ),
        'clientScript' => array(),
        'urlManager' => array(
            // uncomment the following if you have enabled Apache's Rewrite module.
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                // default rules
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'user' => array(
            'allowAutoLogin' => true,
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        )
    ),
);