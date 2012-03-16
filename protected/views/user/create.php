<?php
$this->breadcrumbs=array(
	Yii::t('app','page.users')=>array('index'),
	Yii::t('app','user.create'),
);

$this->menu=array(
	array('label'=>Yii::t('app','user.list'),'url'=>array('index')),
);
?>

<h1><?php echo Yii::t('app','user.create'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>