<!--Generated using Gimme CRUD freeware from www.HandsOnCoding.net -->
<?php
$this->breadcrumbs=array(
	'SaleItems'=>array('index'),
	'View',
);

$this->menu=array(
	array('label'=>'List SaleItem', 'url'=>array('index')),
	array('label'=>'Create SaleItem', 'url'=>array('create')),
	array('label'=>'Update SaleItem', 'url'=>array('update', 'sale_id'=>$model->sale_id, 'item_id'=>$model->item_id, 'id'=>$model->id)),
	array('label'=>'Delete SaleItem', 'url'=>'delete', 
	      'linkOptions'=>array('submit'=>array('delete',
	                                           'sale_id'=>$model->sale_id, 'item_id'=>$model->item_id, 'id'=>$model->id),
									'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SaleItem', 'url'=>array('admin')),
);
?>

<h1>View SaleItem</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'sale_id',
		'item_id',
		'description',
		'line',
		'quantity',
		'cost_price',
		'unit_price',
		'price',
		'discount_amount',
		'discount_percent',
	),
)); ?>
