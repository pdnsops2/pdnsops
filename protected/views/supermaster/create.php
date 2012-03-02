<?php
$this->breadcrumbs=array(
	'Supermasters'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Supermasters', 'url'=>array('index')),
);
?>

<h1>Create Supermaster</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>