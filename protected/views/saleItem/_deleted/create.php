<!--Generated using Gimme CRUD freeware from www.HandsOnCoding.net -->
<?php
$this->breadcrumbs=array(
	'SaleItems'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SaleItems', 'url'=>array('index')),
    array('label'=>'Manage SaleItem', 'url'=>array('admin')),
);
?>

<h1>Create SaleItem</h1>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
