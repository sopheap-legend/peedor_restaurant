
<?php $items=Yii::app()->orderingCart->getCartCashierGrid($item_id); ?>
<?php //print_r($items); ?>
<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th>Item Code</th>
        <th>Item Name</th>
        <th>Price</th>
        <th>Quantity</th>
    </tr>
    </thead>
    <tbody id="cart_contents_detail">
        <?php foreach ($items as $id => $item): ?>
            <tr>
                <td>
                    <?php echo $item['item_number']; ?>
                </td>
                <td>
                    <?php echo $item['name']; ?>
                </td>
                <td>
                    <?php echo $item['price']; ?>
                </td>
                <td>
                    <?php echo $item['quantity']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
