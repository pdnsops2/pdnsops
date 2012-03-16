<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'domain-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','page.requiredFields'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'copy_records'); ?>
		<?php echo $form->checkBox($model,'copy_records',array('checked'=>true)); ?>
		<?php echo $form->error($model,'copy_records'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'copy_permissions'); ?>
		<?php echo $form->checkBox($model,'copy_permissions',array('checked'=>true)); ?>
		<?php echo $form->error($model,'copy_permissions'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app','page.copy')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->