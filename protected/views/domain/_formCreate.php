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
		<?php echo $form->labelEx($model,'ip_address'); ?>
		<?php echo $form->textField($model,'ip_address',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'ip_address'); ?>
	</div>	

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

	<div class="row">
		<?php echo $form->labelEx($model,'add_default_ns_records'); ?>
		<?php echo $form->checkBox($model,'add_default_ns_records',array('checked'=>true)); ?>
		<?php echo $form->error($model,'add_default_ns_records'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'add_default_mx_record'); ?>
		<?php echo $form->checkBox($model,'add_default_mx_record',array('checked'=>true)); ?>
		<?php echo $form->error($model,'add_default_mx_record'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'add_default_cname_records'); ?>
		<?php echo $form->checkBox($model,'add_default_cname_records',array('checked'=>true)); ?>
		<?php echo $form->error($model,'add_default_cname_records'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app','page.create')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->