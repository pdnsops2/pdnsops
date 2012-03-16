<?php
$this->breadcrumbs=array(
	Yii::t('app','page.supermasters')=>array('index'),
	Yii::t('app','supermaster.update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','supermaster.list'),'url'=>array('index')),
	array('label'=>Yii::t('app','supermaster.create'),'url'=>array('create')),
);
?>

<h1><?php echo Yii::t('app','supermaster.update'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
