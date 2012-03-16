<?php
$this->breadcrumbs=array(
	Yii::t('app','page.domains')=>array('domain/index'),
	$model->domain->name=>Yii::app()->createUrl("domain/update", array('id'=>$model->domain->id)),	
	Yii::t('app','record.update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','domain.listDomains'),'url'=>array('index')),
	array('label'=>Yii::t('app','domain.createDomain'),'url'=>array('create')),
	array('label'=>Yii::t('app','domain.createReverse'),'url'=>array('createReverse')),	
	array('label'=>Yii::t('app','record.create'),'url'=>Yii::app()->createUrl('record/create', array('domain'=>$model->domain->id))),
);
?>

<h1><?php echo Yii::t('app','record.update') . ' ' . $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>