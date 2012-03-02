<?php
$this->breadcrumbs=array(
	'Records'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Record', 'url'=>array('index')),
);
?>

<h1>Create Record</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'user'=>$user)); ?>