<?php
$this->breadcrumbs=array(
	Yii::t('app','page.domains')=>array('index'),
	Yii::t('app','domain.updateDomain'),
);

$this->menu=array(
	array('label'=>Yii::t('app','domain.listDomains'),'url'=>array('index')),
	array('label'=>Yii::t('app','domain.createDomain'),'url'=>array('create')),
	array('label'=>Yii::t('app','domain.createReverse'),'url'=>array('createReverse')),	
	array('label'=>Yii::t('app','domain.copyDomain'),'url'=>Yii::app()->createUrl('domain/copy', array('id'=>$model->id))),	
	array('label'=>Yii::t('app','record.create'),'url'=>Yii::app()->createUrl('record/create', array('domain'=>$model->id))),
);

/*
$dataProvider = new CActiveDataProvider('Record', array(
    'criteria' => array(
        'condition' => 'domain_id = 2'
    )
))
*/
//$dataProvider = $domain_records->search();
?>

<h1><?php echo Yii::t('app','domain.updateDomain') . ' ' . $model->name; ?></h1>

<?php echo $this->renderPartial('_formUpdate', array('model'=>$model)); ?>

<h2><?php echo yii::t('app','page.records'); ?></h2>

<?php $this->widget('application.extensions.tablesorter.Sorter', array(
	'id'=>'record-grid',
	//'dataProvider'=>new CArrayDataProvider($model->records, array()),
    'data'=> $domain_records,
	'columns'=>array(
        'name',
        'type',
        'content',
        'ttl',
        'prio',
        array('name' => 'change_date',
              'label' => 'Last updated',
              'value' => 'date("m-d-Y ", $data->change_date)')),
    'filters'=>array(
        '',
        'filter-select',
        '',
        '',
        '',
        ''
	),
)); ?>

<?php
if (Yii::app()->user->isAdmin)
{
?>
<h2><?php echo Yii::t('app','page.users'); ?></h2>

<div class="form">

	<?php 
		$domainUser=new DomainUser;
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'permission-form',
			'action'=>$this->createURL('domainUser/create', array('returnUrl'=>$this->createURL('domain/update', array('id'=>$model->id)))),
			'enableAjaxValidation'=>false,
		)); 
	?>

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
		<?php echo CHtml::submitButton(Yii::t('app','page.add')); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>new CArrayDataProvider($model->users, array()),
	'columns'=>array(
		array(
			'name'=>'username',
			'header'=>Yii::t('app','user.username'),
			),
		array(
			'name'=>'email',
			'header'=>Yii::t('app','user.email'),
			),
		'admin' => array(
            'name'=>'admin',
			'header'=>Yii::t('app','user.admin'),
            'value'=>'$data->admin == 1 ? Yii::t(\'yii\',\'yes\') : Yii::t(\'yii\',\'no\')',
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