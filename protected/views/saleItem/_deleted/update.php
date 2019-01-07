<!--Generated using Gimme CRUD freeware from www.HandsOnCoding.net -->
<?php
$this->breadcrumbs=array(
	'SaleItems'=>array('index'),
	'View'=>array('view', 'sale_id'=>$model->sale_id, 'item_id'=>$model->item_id, 'id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SaleItem', 'url'=>array('index')),
	array('label'=>'Create SaleItem', 'url'=>array('create')),
	array('label'=>'View SaleItem', 'url'=>array('view', 'sale_id'=>$model->sale_id, 'item_id'=>$model->item_id, 'id'=>$model->id)),
	array('label'=>'Manage SaleItem', 'url'=>array('admin')),
); 
?>

<h1>Update Client</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
