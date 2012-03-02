<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php 
		if ($model->isNewRecord)
		{	
			echo $form->textField($model,'username',array('size'=>60,'maxlength'=>128));
		}
		else
		{
			echo $model->username;
		}
		?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
	
	<div class="row">
        <?php echo $form->labelEx($model,'admin'); ?>
		<?php echo $form->checkBox($model,'admin'); ?>
        <?php echo $form->error($model,'admin'); ?>
    </div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'newPassword'); ?>
		<?php echo $form->passwordField($model,'newPassword',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'newPassword'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'newPasswordRepeat'); ?>
		<?php echo $form->passwordField($model,'newPasswordRepeat',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'newPasswordRepeat'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->