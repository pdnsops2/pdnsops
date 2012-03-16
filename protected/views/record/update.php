<?php
$this->breadcrumbs=array(
	'Domains'=>array('domain/index'),
	$model->domain->name=>Yii::app()->createUrl("domain/update", array('id'=>$model->domain->id)),	
	'Update Record',
);

$this->menu=array(
	array('label'=>'List Domains', 'url'=>array('domain/index')),
	array('label'=>'Create Domain', 'url'=>array('domain/create')),
	array('label'=>'Create Reverse Domain', 'url'=>array('domain/createReverse')),
	array('label'=>'Create Record', 'url'=>Yii::app()->createUrl('record/create', array('domain'=>$model->domain->id))),
);
?>

<h1>Update Record <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>