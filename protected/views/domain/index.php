<?php
$this->breadcrumbs=array(
	'Domains',
);

$this->menu=array(
	array('label'=>'Create Domain', 'url'=>array('create')),
	array('label'=>'Create Reverse Domain', 'url'=>array('createReverse')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('domain-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Domains</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php 
	// determine which rows to display
	$dataProvider = null;
	if ($user->admin == 1)
	{
		$dataProvider = $model->search();
	}
	else
	{
		$dataProvider = new CArrayDataProvider($user->domains, array());
	}

	  $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'domain-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'columns'=>array(
		'name',
		'type',
		'master',
		array('name'=>'last_check','value'=>'date("Y-m-d H:i:s", $data->last_check)'),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
