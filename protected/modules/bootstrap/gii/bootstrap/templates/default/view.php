<?php
/**
 * The following variables are available in this template:
 * - $this: the BootstrapCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
<?php echo "?>\n"; ?>

<?php
echo "<?php\n";
$nameColumn = $this->guessNameColumn($this->tableSchema->columns);
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn},
);\n";
?>

$this->menu=array(
array('icon' => 'glyphicon glyphicon-home','label'=>'Manage <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
array('icon' => 'glyphicon glyphicon-plus-sign','label'=>'Create <?php echo $this->modelClass; ?>', 'url'=>array('create')),
array('icon' => 'glyphicon glyphicon-edit','label'=>'Update <?php echo $this->modelClass; ?>', 'url'=>array('update', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
array('icon' => 'glyphicon glyphicon-minus-sign','label'=>'Delete <?php echo $this->modelClass; ?>', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<?php echo "<?php echo BSHtml::pageHeader('View','$this->modelClass '.\$model->{$this->tableSchema->primaryKey}) ?>\n"; ?>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CDetailView',array(
'htmlOptions' => array(
'class' => 'table table-striped table-condensed table-hover',
),
'data'=>$model,
'attributes'=>array(
<?php
foreach ($this->tableSchema->columns as $column) {
    echo "\t\t'" . $column->name . "',\n";
}
?>
),
)); ?>