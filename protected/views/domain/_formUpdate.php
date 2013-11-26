<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'domain-form',
    'enableAjaxValidation'=>true,
    'htmlOptions' => array('class' => 'form-horizontal', 'role' => 'form')
)); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model,'type', array ('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-10">
            <?php echo $form->dropDownList($model,'type',array('MASTER'=>'MASTER','SLAVE'=>'SLAVE','NATIVE'=>'NATIVE'), array('class' => 'form-control', 'onChange' => 'javascript:showMaster($(this))')); ?>
            <script type="text/javascript">
                $(function() {
                    if ($("#Domain_type").val() == 'SLAVE') {
                        $("#master_ip").show();
                    }else {
                        $("#master_ip").hide();
                    }
                });
                function showMaster(t) {
                    if ($(t).val() == 'SLAVE') {
                        $("#master_ip").toggle();
                    } else {
                        $("#master_ip").hide();
                    }
                }
            </script>
        </div>
    </div>
    <?php
        $errorClass = $model->hasErrors('master')  ? 'has-error' : '';
?>
    <div class="form-group <?php echo $errorClass; ?>" style="display:none" id="master_ip">
        <?php echo $form->labelEx($model,'master', array ('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-10">
            <?php echo $form->textField($model,'master',array('class' => 'form-control')); ?>
            <?php echo $form->error($model,'master'); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?php echo CHtml::submitButton(Yii::t('app','page.update'), array('class' => 'btn btn-default')); ?>
        </div>
    </div>
<?php $this->endWidget();

/*
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



</div><!-- form -->
*/
?>