<?php
$cs = Yii::app()->clientScript;
$bootStrapPath = Yii::app()->getBaseUrl(true) . '/docs-assets/bootstrap';

/**
 * StyleSHeets
 */
$cs
    ->registerCssFile($bootStrapPath . '/css/bootstrap.css')
    ->registerCssFile($bootStrapPath . '/css/bootstrap-theme.css')
    ->registerCssFile($bootStrapPath . '/../font-awesome-4.0.3/css/font-awesome.css');

/**
 * JavaScripts
 */
$cs
    ->registerCoreScript('jquery',CClientScript::POS_END)
    ->registerCoreScript('jquery.ui',CClientScript::POS_END)
    ->registerScriptFile($bootStrapPath . '/js/bootstrap.min.js', CClientScript::POS_END)

    ->registerScript('tooltip',
        "$('[data-toggle=\"tooltip\"]').tooltip();
        $('[data-toggle=\"popover\"]').tooltip()"
        ,CClientScript::POS_READY);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?php echo $bootStrapPath . '/ico/favicon.ico' ?>">

    <title>Starter Template for Bootstrap</title>


    <!-- Custom styles for this template -->
    <link href="<?php echo $bootStrapPath . '/template.css'?>" rel="stylesheet">


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap/js/html5shiv.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div id="wrapper">

    <!-- Sidebar -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html">PDNSOPS</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <?php if (!Yii::app()->user->isGuest) {?>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <?php $this->widget('zii.widgets.CMenu',array(
                'items'=>array(
                    array('label'=>Yii::t('app','page.home'), 'url'=>array('/site/index')),
                    array('label'=>Yii::t('app','page.domains'), 'url'=>array('/domain/index'), 'visible'=>!Yii::app()->user->isGuest),
                    array('label'=>Yii::t('app','page.users'), 'url'=>array('/user/index'), 'visible'=>!Yii::app()->user->isGuest && Yii::app()->user->getState('isAdmin')),
                    array('label'=>Yii::t('app','page.logs'), 'url'=>array('/log/index'), 'visible'=>!Yii::app()->user->isGuest && Yii::app()->user->getState('isAdmin')),
                    array('label'=>Yii::t('app','page.supermasters'), 'url'=>array('/supermaster/index'), 'visible'=>!Yii::app()->user->isGuest && Yii::app()->user->getState('isAdmin')),

                ),
                'htmlOptions'=>array('class' => 'nav navbar-nav side-nav')
            ));
            }?>
            <!--
            <ul class="nav navbar-nav side-nav">
                <li class="active"><a href="index.html"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="charts.html"><i class="fa fa-bar-chart-o"></i> Charts</a></li>
                <li><a href="tables.html"><i class="fa fa-table"></i> Tables</a></li>
                <li><a href="forms.html"><i class="fa fa-edit"></i> Forms</a></li>
                <li><a href="typography.html"><i class="fa fa-font"></i> Typography</a></li>
                <li><a href="bootstrap-elements.html"><i class="fa fa-desktop"></i> Bootstrap Elements</a></li>
                <li><a href="bootstrap-grid.html"><i class="fa fa-wrench"></i> Bootstrap Grid</a></li>
                <li><a href="blank-page.html"><i class="fa fa-file"></i> Blank Page</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-square-o-down"></i> Dropdown <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Dropdown Item</a></li>
                        <li><a href="#">Another Item</a></li>
                        <li><a href="#">Third Item</a></li>
                        <li><a href="#">Last Item</a></li>
                    </ul>
                </li>
            </ul>
            -->

            <ul class="nav navbar-nav navbar-right navbar-user">
                <?php if (!Yii::app()->user->isGuest) {?>
                    <?php /*
                <li class="dropdown alerts-dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> Alerts <span class="badge">3</span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Default <span class="label label-default">Default</span></a></li>
                        <li><a href="#">Primary <span class="label label-primary">Primary</span></a></li>
                        <li><a href="#">Success <span class="label label-success">Success</span></a></li>
                        <li><a href="#">Info <span class="label label-info">Info</span></a></li>
                        <li><a href="#">Warning <span class="label label-warning">Warning</span></a></li>
                        <li><a href="#">Danger <span class="label label-danger">Danger</span></a></li>
                        <li class="divider"></li>
                        <li><a href="#">View All</a></li>
                    </ul>
                </li>
*/?>
                <li class="dropdown user-dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo Yii::app()->user->name ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><?php
                            echo BSHtml::link(
                                    '<i class="fa fa-gear"></i> '.Yii::t('app','page.settings'),
                                    array('/site/settings')
                                );
                            ?>
                        </li>
                        <li class="divider"></li>
                        <li><?php
                            echo BSHtml::link(
                                    '<i class="fa fa-power-off"></i> '.Yii::t('app','page.logout'),
                                    array('/site/logout')
                                );
                            ?></li>

                    </ul>
                </li>
                <? } else { ?>
                    <li >

                        <?php
                        echo BSHtml::menuText(
                            BSHtml::link(
                                Yii::t('app','page.login'),
                                array('/site/login'),
                                array('class' => 'navbar-link')
                            ));
                        ?>
                        ?>
                    </li>
                <?php }?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">

        <?php echo $content; ?>


    </div><!-- /#page-wrapper -->

</div><!-- /#wrapper -->
</body>