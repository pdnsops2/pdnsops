<?php
$this->pageTitle=Yii::app()->name . ' - ' . Yii::t('app','page.settings');
$this->breadcrumbs=array(
	Yii::t('app','page.settings'),
);
?>

<h1><?php echo Yii::t('app','page.settings'); ?></h1>

<?php if(Yii::app()->user->hasFlash('settings')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('settings'); ?>
</div>

<?php else: ?>

<p><?php echo Yii::t('app','setting.change'); ?></p>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contact-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note"><?php echo Yii::t('app','page.requiredFields'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'theme'); ?>
		<?php echo $form->dropDownList($model,'theme',$model->getThemes()); ?>
		<?php echo $form->error($model,'theme'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'domainMasterIP'); ?>
		<?php echo $form->textField($model,'domainMasterIP'); ?>
		<?php echo $form->error($model,'domainMasterIP'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'defaultTTL'); ?>
		<?php echo $form->textField($model,'defaultTTL'); ?>
		<?php echo $form->error($model,'defaultTTL'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'soaRetry'); ?>
		<?php echo $form->textField($model,'soaRetry'); ?>
		<?php echo $form->error($model,'soaRetry'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'soaRefresh'); ?>
		<?php echo $form->textField($model,'soaRefresh'); ?>
		<?php echo $form->error($model,'soaRefresh'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'soaExpire'); ?>
		<?php echo $form->textField($model,'soaExpire'); ?>
		<?php echo $form->error($model,'soaExpire'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ns1'); ?>
		<?php echo $form->textField($model,'ns1'); ?>
		<?php echo $form->error($model,'ns1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ns2'); ?>
		<?php echo $form->textField($model,'ns2'); ?>
		<?php echo $form->error($model,'ns2'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'ns3'); ?>
		<?php echo $form->textField($model,'ns3'); ?>
		<?php echo $form->error($model,'ns3'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'ns4'); ?>
		<?php echo $form->textField($model,'ns4'); ?>
		<?php echo $form->error($model,'ns4'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'ns5'); ?>
		<?php echo $form->textField($model,'ns5'); ?>
		<?php echo $form->error($model,'ns5'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'ns6'); ?>
		<?php echo $form->textField($model,'ns6'); ?>
		<?php echo $form->error($model,'ns6'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'ns7'); ?>
		<?php echo $form->textField($model,'ns7'); ?>
		<?php echo $form->error($model,'ns7'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'ns8'); ?>
		<?php echo $form->textField($model,'ns8'); ?>
		<?php echo $form->error($model,'ns8'); ?>
	</div>
	
	
	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app','page.update')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>