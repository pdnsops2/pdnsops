<?php
$this->breadcrumbs=array(
	'Records',
);

$this->menu=array(
	array('label'=>'Create Record', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('record-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Records</h1>

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
	if (Yii::app()->user->isAdmin)
	{
		$dataProvider = $model->search();
	}
	else
	{
		// only display records of domains we can access
		$dataProvider=new CActiveDataProvider('Record', array(
			'criteria'=>array(
				'join'=>'JOIN `domain_user` ON `domain_user`.`domain_id` = `t`.`domain_id`',
				'condition'=>'`domain_user`.`user_id` = ' . Yii::app()->user->getId(),
			),
		));	
	}
	
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'record-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'columns'=>array(
		array('name'=>'domain','value'=>'$data->domain->name'),
		'name',
		'type',
		'content',
		'ttl',
		'prio',
		array('name'=>'change_date','value'=>'date("Y-m-d H:i:s", $data->change_date)'),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
