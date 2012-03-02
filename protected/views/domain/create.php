<?php
$this->breadcrumbs=array(
	'Domains'=>array('index'),
	'Create Domain',
);

$this->menu=array(
	array('label'=>'List Domain', 'url'=>array('index')),
);
?>

<h1>Create Domain</h1>

<?php echo $this->renderPartial('_formCreate', array('model'=>$model)); ?>