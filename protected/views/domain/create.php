<?php
$this->breadcrumbs=array(
	Yii::t('app','page.domains')=>array('index'),
	Yii::t('app','domain.createDomain'),
);

$this->menu=array(
	array('label'=>Yii::t('app','domain.listDomains'),'url'=>array('index')),
	array('label'=>Yii::t('app','domain.createDomain'),'url'=>array('create')),
	array('label'=>Yii::t('app','domain.createReverse'),'url'=>array('createReverse')),
);
?>

<h1><?php echo Yii::t('app','domain.createDomain'); ?></h1>

<?php echo $this->renderPartial('_formCreate', array('model'=>$model)); ?>