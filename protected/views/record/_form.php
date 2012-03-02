<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'record-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'domain_id'); ?>
		<?php 
		if ($model->isNewRecord)
		{	
			if ($user->admin == 1)
			{
				$models = Domain::model()->findAll(array('order' => 'name ASC'));
			}
			else
			{
				$models = Domain::model()->findAllBySql('SELECT * FROM `domains` JOIN `domain_user` ON `domains`.`id` = `domain_user`.`domain_id` WHERE `domain_user`.`user_id` = ' . $user->id . ' ORDER BY `domains`.`name`');		
			}
			
			echo $form->dropDownList($model,'domain_id', CHtml::listData($models, 'id', 'name'));
		}
		else
		{
			echo $model->domain->name;
		}
		?>
		<?php echo $form->error($model,'domain_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->textField($model,'type',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textField($model,'content',array('size'=>60,'maxlength'=>4096)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ttl'); ?>
		<?php echo $form->textField($model,'ttl'); ?>
		<?php echo $form->error($model,'ttl'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'prio'); ?>
		<?php echo $form->textField($model,'prio'); ?>
		<?php echo $form->error($model,'prio'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->