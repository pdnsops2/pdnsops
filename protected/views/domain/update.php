<?php
$this->breadcrumbs=array(
	'Domains'=>array('index'),
	'Update',
);

$this->menu=array(
	array('label'=>'List Domains', 'url'=>array('index')),
	array('label'=>'Create Domain', 'url'=>array('create')),
	array('label'=>'Create Reverse Domain', 'url'=>array('createReverse')),
	array('label'=>'Create Record', 'url'=>Yii::app()->createUrl('record/create', array('domain'=>$model->id))),
);
?>

<h1>Update Domain <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_formUpdate', array('model'=>$model)); ?>

<h2>Records</h2>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'record-grid',
	'dataProvider'=>new CArrayDataProvider($model->records, array()),
	'columns'=>array(
		'name',
		'type',
		'content',
		'ttl',
		'prio',
		array('name'=>'change_date','value'=>'date("Y-m-d H:i:s", $data->change_date)'),
		array(
			'class' => 'CButtonColumn',
	        'buttons' => array(
				'update' => array(
					'url' => 'Yii::app()->createUrl("record/update", array("id" => $data[\'id\']))',
				),    // update button
				'delete' => array(
					'url' => 'Yii::app()->createUrl("record/delete", array("id" => $data[\'id\']))',
				),    // delete button
			),
			'template'=>'{update}{delete}',
		),
	),
)); ?>

<?php
if (Yii::app()->user->isAdmin)
{
?>
<h2>Users</h2>

<div class="form">

<?php 
	$domainUser=new DomainUser;
	$form=$this->beginWidget('CActiveForm', array(
	'id'=>'permission-form',
	'action'=>$this->createURL('domainUser/create', array('returnUrl'=>$this->createURL('domain/update', array('id'=>$model->id)))),
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($domainUser); ?>
	
	<?php echo $form->hiddenField($domainUser, 'domain_id', array('value'=>$model->id));?>
	
	<div class="row">
		<?php echo $form->labelEx($domainUser,'user_id'); ?>
		<?php 
		$models = User::model()->findAllBySql('SELECT * FROM `user` WHERE `user`.`id` NOT IN (SELECT `user_id` FROM `domain_user` WHERE `domain_id` = ' . $model->id . ') ORDER BY `username`');
		echo $form->dropDownList($domainUser,'user_id', CHtml::listData($models, 'id', 'username')); 
		?>
		<?php echo $form->error($domainUser,'user_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Add'); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>new CArrayDataProvider($model->users, array()),
	'columns'=>array(
		'id',
		'username',
		'email',
		'admin' => array(
            'name'=>'admin',
            'value'=>'$data->admin == 1 ? "yes" : "no"',
        ),
		array(
			'class' => 'CButtonColumn',
	        'buttons' => array(
				'update' => array(
					'url' => 'Yii::app()->createUrl("user/update", array("id" => $data[\'id\']))',
				),    // update button
				// TODO: after completion, this button should reload the add-user form
				'delete' => array(
					'url' => 'Yii::app()->createUrl("domainUser/delete", array("domain_id" => ' . $model->id . ', "user_id" => $data[\'id\']))',
				),    // delete button 
			),
			'template'=>'{update}{delete}',
		),
	),
));
}
?>