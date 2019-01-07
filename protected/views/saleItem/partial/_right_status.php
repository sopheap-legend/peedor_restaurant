<?php if (isset($table_info)) { ?>
    <span class="label label-info label-xlg">
        <?php echo '<b>' .  $table_info->name  .' - ' . Common::GroupAlias(Yii::app()->orderingCart->getGroupId()) . '</b>'; ?>
        <i class="ace-icon fa fa-clock-o"></i>
        <?= $time_go; ?>
    </span>
<?php } ?>
<?php if (Yii::app()->orderingCart->getTableId()) { ?>
    <?php if(Yii::app()->user->checkAccess('sale.edit')) { ?>
        <?php if(isset($view)){
            if($view=='cashier') {
                ?>
                <?php echo TbHtml::linkButton(Yii::t('app', 'Edit Ordered'), array(
                    'color' => TbHtml::BUTTON_COLOR_DANGER,
                    'size' => TbHtml::BUTTON_SIZE_MINI,
                    'icon' => ' ace-icon fa fa-pencil-square-o white',
                    'class' => 'btn-edit-order',
                    'url' => Yii::app()->createUrl('SaleItem/editOrdered'),
                    'title' => Yii::t('app', 'Edit Ordered'),
                )); ?>
            <?php }} ?>
    <?php } ?>
<?php }else{ ?>
    <?php if(Yii::app()->user->checkAccess('sale.edit')) { ?>
        <?php if(isset($view)){
            if($view=='cashier') {
                ?>
                <?php echo TbHtml::linkButton(Yii::t('app', 'New Order'), array(
                    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                    'size' => TbHtml::BUTTON_SIZE_MINI,
                    'icon' => ' ace-icon fa fa-pencil-square-o white',
                    'class' => 'btn-edit-order',
                    'url' => Yii::app()->createUrl('SaleItem/index'),
                    'title' => Yii::t('app', 'New Order'),
                )); ?>
            <?php }} ?>
    <?php } ?>
<?php } ?>
<?php if (isset($ordering_status)) { ?>

    <!--<span class="order-status <?php //echo $ordering_status_span; ?>">
        <i class="<?php //echo $ordering_status_icon; ?>"></i>
        <?php // $ordering_msg; ?>
    </span>-->

    <?php if ($ordering_status=='2') { ?>

        <?php echo TbHtml::linkButton(Yii::t('app', 'Confirm'), array(
            'color' => TbHtml::BUTTON_COLOR_PRIMARY,
            'size' => TbHtml::BUTTON_SIZE_MINI,
            'icon' => ' ace-icon fa fa-floppy-o white',
            'class' => 'btn-confirm-order',
            'url' => Yii::app()->createUrl('SaleItem/confirmOrder'),
            'title' => Yii::t('app', 'Confirm Order'),
        )); ?>

    <?php }//else{ ?>

    <?php //} ?>



<?php } ?>
<?php echo TbHtml::linkButton(Yii::t('app', 'Summary'), array(
    'color' => TbHtml::BUTTON_COLOR_INFO,
    'size' => TbHtml::BUTTON_SIZE_MINI,
    'icon' => ' ace-icon fa fa-eye white',
    'class' => 'btn-confirm-order',
    'url' => Yii::app()->createUrl('SaleItem/cashier'),
    'title' => Yii::t('app', 'Summary Screen'),
)); ?>

