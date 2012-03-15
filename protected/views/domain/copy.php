<?php
$this->breadcrumbs=array(
	'Domains'=>array('index'),
	'Copy',
);

$this->menu=array(
	array('label'=>'List Domain', 'url'=>array('index')),
	array('label'=>'Create Domain', 'url'=>array('create')),
	array('label'=>'Create Reverse Domain', 'url'=>array('createReverse')),
);
?>

<h1>Copy Domain <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_formCopy', array('model'=>$model)); ?>
