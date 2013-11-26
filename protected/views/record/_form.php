<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'record-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','page.requiredFields'); ?></p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'domain_id'); ?>
		<?php echo $model->domain->name; echo $form->hiddenField($model,'domain_id');?>
		<?php echo $form->error($model,'domain_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); echo '.' .$model->domain->name; ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type',$model->getTypes()); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content',array('rows'=>5,'cols'=>50,'maxlength'=>4096)); ?>
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
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','page.create') : Yii::t('app','page.save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->