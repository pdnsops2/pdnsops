<?php
$this->breadcrumbs=array(
	'Supermasters'=>array('index'),
	'Update',
);

$this->menu=array(
	array('label'=>'List Supermasters', 'url'=>array('index')),
	array('label'=>'Create Supermaster', 'url'=>array('create')),
);
?>

<h1>Update Supermaster</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
