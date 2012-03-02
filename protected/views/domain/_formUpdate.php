<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'domain-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type',array('MASTER'=>'MASTER','SLAVE'=>'SLAVE','NATIVE'=>'NATIVE')); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'master'); ?>
		<?php echo $form->textField($model,'master',array('size'=>15,'maxlength'=>15)); ?>&nbsp;<?php echo Yii::t('domain', '(Only for Slave domains)'); ?>
		<?php echo $form->error($model,'master'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Update'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->