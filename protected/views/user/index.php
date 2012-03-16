<?php
$this->breadcrumbs=array(
	Yii::t('app','page.users'),
);

$this->menu=array(
	array('label'=>Yii::t('app','user.create'),'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('app','page.users'); ?></h1>

<p><?php echo Yii::t('app','page.searchOperators'); ?></p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'username',
		'email',
		'admin' => array(
            'name'=>'admin',
            'value'=>'$data->admin == 1 ? Yii::t(\'yii\',\'yes\') : Yii::t(\'yii\',\'no\')',
        ),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
