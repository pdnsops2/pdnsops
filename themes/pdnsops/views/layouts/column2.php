<?php $this->beginContent('//layouts/main'); ?>

<div class="sidebar1">
<div class="sidebar-header">
Operations
</div>

<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget(); ?>

<!-- end .sidebar1 --></div>
<div class="content">
	<?php echo $content; ?>
<!-- end .content --></div>

<?php $this->endContent(); ?>
