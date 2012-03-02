<?php
$this->breadcrumbs=array(
	'Records'=>array('index'),
	'Update',
);

$this->menu=array(
	array('label'=>'List Record', 'url'=>array('index')),
	array('label'=>'Create Record', 'url'=>array('create')),
);
?>

<h1>Update Record <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>