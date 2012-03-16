<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'domain-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','page.requiredFields'); ?></p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type',array('MASTER'=>'MASTER','SLAVE'=>'SLAVE','NATIVE'=>'NATIVE')); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'master'); ?>
		<?php echo $form->textField($model,'master',array('size'=>15,'maxlength'=>15)); ?>&nbsp;<?php echo Yii::t('app','domain.onlyForSlave'); ?>
		<?php echo $form->error($model,'master'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app','page.update')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->