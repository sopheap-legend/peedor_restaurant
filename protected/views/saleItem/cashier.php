<style>
    .table-btn {
        margin-left: 10px;
        margin-bottom: 12px;
    }
</style>

<?php $this->widget('ext.modaldlg.EModalDlg'); ?>

<div id="register_container">

    <?php $this->renderPartial('partial/_left_panel',
        array(
            'model' => $model,
            'tables' => $tables,
            'zones' => $zones,
            'table_id' => $table_id,
            'zone_id' => $zone_id,
            'view'=>'cashier'
        )); ?>

    <?php $this->renderPartial('partial/_right_panel_cashier', array(
        'model' => $model,
        //'grid_columns' => $grid_columns,
        //'data_provider' => $data_provider,
        //'grid_id' => $grid_id,
        'table_info' => $table_info,
        //'items' => $items,
        'itemsCashier' => $itemsCashier,
        'sub_total' => $sub_total,
        'amount_due' => $amount_due,
        'count_payment' => $count_payment,
        'payments' => $payments,
        'count_item' => $count_item,
        'location_id' => $location_id,
        'time_go' => $time_go,
        'print_categories' => $print_categories,
        'ordering_status' => $ordering_status,
        'ordering_msg' => $ordering_msg,
        'ordering_status_span' => $ordering_status_span,
        'ordering_status_icon' =>  $ordering_status_icon,
        'giftcard_info' => $giftcard_info,
        'giftcard_id' => $giftcard_id
    )); ?>

    <?php $this->renderPartial('partial/_js',array('view'=>'cashier')); ?>

</div>



