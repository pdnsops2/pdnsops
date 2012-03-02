<?php
$this->breadcrumbs=array(
	'Domains'=>array('index'),
	'Create Reverse Domain',
);

$this->menu=array(
	array('label'=>'List Domain', 'url'=>array('index')),
);
?>

<h1>Create Reverse Domain</h1>

<?php echo $this->renderPartial('_formCreateReverse', array('model'=>$model)); ?>