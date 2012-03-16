<?php
$this->breadcrumbs=array(
	Yii::t('app','page.domains')=>array('index'),
	Yii::t('app','domain.copyDomain'),
);

$this->menu=array(
	array('label'=>'List Domain', 'url'=>array('index')),
	array('label'=>'Create Domain', 'url'=>array('create')),
	array('label'=>'Create Reverse Domain', 'url'=>array('createReverse')),
);
?>

<h1><?php echo Yii::t('app','domain.copyDomain') . ' ' .$model->name; ?></h1>

<?php echo $this->renderPartial('_formCopy', array('model'=>$model)); ?>
