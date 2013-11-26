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
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Create',
);\n";
?>

    $this->menu=array(
        array('icon' => 'glyphicon glyphicon-home','label'=>'Manage <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
    );
    ?>
<?php echo "<?php echo BSHtml::pageHeader('Create','$this->modelClass') ?>\n"; ?>

<?php echo "<?php \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>