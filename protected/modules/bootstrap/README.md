# Yii Bootstrap 3 Module

#### Latest Stable Version 0.0.7
#####Montly Downloads [![Montly Downloads](https://poser.pugx.org/drmabuse/yii-bootstrap-3-module/d/monthly.png)](https://packagist.org/packages/drmabuse/yii-bootstrap-3-module)
#####Total Downloads [![Total Downloads](https://poser.pugx.org/drmabuse/yii-bootstrap-3-module/downloads.png)](https://packagist.org/packages/drmabuse/yii-bootstrap-3-module)
## [Demo-Page](http://bootstrap3.pascal-brewing.de/ "")
### [Class Reference](http://bootstrap3.pascal-brewing.de/doc "")
### [Demo-Page-Example Bitbucket](https://bitbucket.org/DrMabuse/yii-bootstrap-3-module-example "")
### [Changes](https://bitbucket.org/DrMabuse/yii-bootstrap-3-module-example/commits/all "")
### Code
[Bitbucket][0]

[0]: https://bitbucket.org/DrMabuse/yii-bootstrap-3-module
## Download [here](http://getbootstrap.com "bootsrap") the bootstrap package.
Append in Your Layout:
~~~
[php]
<?php
$cs = Yii::app()->clientScript;
$themePath = Yii::app()->theme->baseUrl;

/**
 * StyleSHeets
 */
$cs
    ->registerCssFile($themePath.'/assets/css/bootstrap.css')
    ->registerCssFile($themePath.'/assets/css/bootstrap-theme.css');

/**
 * JavaScripts
 */
$cs
    ->registerCoreScript('jquery',CClientScript::POS_END)
    ->registerCoreScript('jquery.ui',CClientScript::POS_END)
    ->registerScriptFile($themePath.'/assets/js/bootstrap.min.js',CClientScript::POS_END)

    ->registerScript('tooltip',
        "$('[data-toggle=\"tooltip\"]').tooltip();
        $('[data-toggle=\"popover\"]').tooltip()"
        ,CClientScript::POS_READY);

?>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script src="<?php echo Yii::app()->theme->baseUrl ?>/assets/js/html5shiv.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl ?>/assets/js/respond.min.js"></script>
<![endif]-->
~~~
### Download the Module
[Module](https://bitbucket.org/DrMabuse/yii-bootstrap-3-module "bitbucket")
Move the Package to your
~~~
[php]
/protected/modules/
~~~
Folder or install with Composer

[Packagist](https://packagist.org/packages/drmabuse/yii-bootstrap-3-module "")
~~~
[php]
"drmabuse/yii-bootstrap-3-module": "dev-master"
~~~
* Set up Git by following the instructions [here](https://help.github.com/articles/set-up-git).
  * Update the configurations in `app/config/` to suit your needs. The `common/config/main.php` is configured to use **sqllite** by default. Change your `common/config/env/dev.php` to suit your database requirements.
  * Composer is required The package includes already a `composer.phar` file.
  * Browse through the `composer.json` and remove the dependencies you don't need also update the required versions of the extensions.
  * If you have `composer` installed globally:
	 * Run `composer self-update` to make sure you have the latest version of composer.
	 * Run `composer install` to download all the dependencies.
  * If you work the `composer.phar` library within the project boilerplate.
    * Run `php composer.phar self-update` to make sure you have the latest version of composer.
    * Run `php composer.phar install` to download all the dependencies.

### Configure
configure


~~~
[php]
/config/main.php
~~~
with
~~~
[php]
 <?php
    'aliases' => array(
            'bootstrap' => 'application.modules.bootstrap',
    ),
    'modules' => array(
            'bootstrap' => array(
                'class' => 'bootstrap.BootStrapModule'
            ),
            'gii' => array(
                'generatorPaths' => array('bootstrap.gii'),
                'class' => 'system.gii.GiiModule',
                'password' => 'yaa',
                'ipFilters' => array('127.0.0.1','::1'),
	),

    ),
    'components' => array(
            'bsHtml' => array(
                'class' => 'bootstrap.components.BSHtml'
            ),
    )
    ?>
~~~
[Full Config Example](https://bitbucket.org/DrMabuse/yii-bootstrap-3-module-example/src/bf91414d4a6ff5dd2a6c34d09d309c5a122d97c4/app/config/main.php?at=master "")
