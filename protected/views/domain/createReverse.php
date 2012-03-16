<?php
$this->breadcrumbs=array(
	'Domains'=>array('index'),
	'Create Reverse Domain',
);

$this->menu=array(
	array('label'=>'List Domain', 'url'=>array('index')),
	array('label'=>'Create Domain', 'url'=>array('create')),
	array('label'=>'Create Reverse Domain', 'url'=>array('createReverse')),
);
?>

<h1>Create Reverse Domain</h1>

<?php echo $this->renderPartial('_formCreateReverse', array('model'=>$model)); ?>