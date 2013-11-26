<?php
$this->breadcrumbs=array(
	Yii::t('app','page.domains'),
);

$this->menu=array(
	array('label'=>Yii::t('app','domain.createDomain'),'url'=>array('create')),
	array('label'=>Yii::t('app','domain.createReverse'),'url'=>array('createReverse')),
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

<h1><?php echo Yii::t('app','page.domains'); ?></h1>

<p><?php echo Yii::t('app','page.searchOperators'); ?></p>

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
		array(
			'name'=>'name',
			'type'=>'raw',
            'value' => 'CHtml::link($data->name,Yii::app()->createUrl("domain/update", array("id"=>$data->id)))'
			),
		'type',
		'master',
		array(
			'name'=>'last_check',
			'value'=>'date("Y-m-d H:i:s", $data->last_check)'
			),
		array(
			'class'=>'CButtonColumn',
			'buttons'=>	array(
				'copy' => array(
					'url'=>'Yii::app()->createUrl("domain/copy", array("id" => $data[\'id\']))',
				),    //copy button
			),
			'template'=>'{update}{copy}{delete}',
		),
	),
)); ?>
