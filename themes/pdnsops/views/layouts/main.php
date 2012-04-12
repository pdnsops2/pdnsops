<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/pdnsops/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/pdnsops/menubar3.css" />

</head>

<body>


<div class="container">
  <div class="header"><a href="#"><img src="logo01.png" alt="PDNSOps" width="180" height="80" style="background: #70d868; display:block;" /></a> 
    <!-- end .header --></div>
	<div class="mainmenu">
	<?php $this->widget('zii.widgets.CMenu',array(
		'htmlOptions'=>array(
			"class"=>"MenuBarHorizontal"),
		'items'=>array(
			array('label'=>Yii::t('app','page.home'), 'url'=>array('/site/index')),
			array('label'=>Yii::t('app','page.domains'), 'url'=>array('/domain/index'), 'visible'=>!Yii::app()->user->isGuest),
			array('label'=>Yii::t('app','page.users'), 'url'=>array('/user/index'), 'visible'=>!Yii::app()->user->isGuest && Yii::app()->user->getState('isAdmin')),
			array('label'=>Yii::t('app','page.logs'), 'url'=>array('/log/index'), 'visible'=>!Yii::app()->user->isGuest && Yii::app()->user->getState('isAdmin')),
			array('label'=>Yii::t('app','page.supermasters'), 'url'=>array('/supermaster/index'), 'visible'=>!Yii::app()->user->isGuest && Yii::app()->user->getState('isAdmin')),
			array('label'=>Yii::t('app','page.settings'), 'url'=>array('/site/settings'), 'visible'=>!Yii::app()->user->isGuest && Yii::app()->user->getState('isAdmin')),
			array('label'=>Yii::t('app','page.login'), 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
			array('label'=>Yii::t('app','page.logout'). ' ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
		),
	)); ?></div>

<?php echo $content; ?>


  <div class="footer">
		<?php echo Yii::t('app', 'page.footer'); ?>
		<?php echo date('Y'); ?> - <?php echo Yii::t('app', 'page.team'); ?><br/>
		<?php echo Yii::powered(); ?>
    <!-- end .footer --></div>
  <!-- end .container --></div>
</script>
</body>
</html>
