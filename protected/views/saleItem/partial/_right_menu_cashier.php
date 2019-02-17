<?php
$baseUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/css/bootstrap-table-expandable.css" />
<?php $cs->registerScriptFile($baseUrl.'/js/bootstrap-table-expandable.js',CClientScript::POS_END); ?>
<div class="col-12 col-md-12 slim-scroll" data-height="400" id="order_menu">
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            //'action' => Yii::app()->createUrl('saleItem/add3'),
            'method' => 'post',
            'id' => 'add_item_form',
        )); ?>

        <?php $this->endWidget(); ?>  <!--/endformWidget-->

        <?php
        //Show nest detail grid, now it have some challenge will recover back after deliver v1
        //Yii::app()->getComponent('yiiwheels')->registerAssetJs('bootbox.min.js');
        /*$this->widget('yiiwheels.widgets.grid.WhGridView', array(
                'id' => $grid_id,
                //'fixedHeader' => true,
                'type' => TbHtml::GRID_TYPE_BORDERED,
                'dataProvider' => $data_provider,
                'template' => "{items}\n{exportbuttons}\n",
                'columns' => $grid_columns)
        );*/
        ?>

        <table class="table table-hover table-expandable table-striped">
            <thead>
            <tr>
                <th><?php echo Yii::t('app', 'Item Code'); ?></th>
                <th><?php echo Yii::t('app', 'Item Name'); ?></th>
                <th><?php echo Yii::t('app', 'Item Qty'); ?></th>
                <th><?php echo Yii::t('app', 'Topping Qty'); ?></th>
                <!-- <th class="<?php //echo Yii::app()->settings->get('sale','discount'); ?>"><?php //echo Yii::t('model','model.saleitem.discount_amount'); ?></th> -->
                <th><?php echo Yii::t('app', 'Total'); ?></th>
                <!--<th><?php //echo Yii::t('app', 'Action'); ?></th>-->
            </tr>
            </thead>
            <tbody id="cart_contents">
            <?php foreach ($items as $id => $item): ?>
                <?php
                $total_item = number_format($item['total'], Yii::app()->orderingCart->getDecimalPlace());
                $item_id = $item['item_id'];
                //$line = $item['line'];
                //$item_parent_id = $item['item_parent_id'];
                $unit_name = '';
                //echo $item_id;
                ?>
                <tr>
                    <td>
                        <?php //if($item_parent_id==0){ ?>
                        <?php /*echo TbHtml::linkButton('', array(
                            'color' => TbHtml::BUTTON_COLOR_SUCCESS,
                            'size' => TbHtml::BUTTON_SIZE_MINI,
                            'icon' => 'glyphicon-hand-up white',
                            'url' => $this->createUrl('Item/SelectItem/',
                                array('item_parent_id' => $item_id, 'category_id' => $item['category_id'],'view' => 'cashier')),
                            'class' => 'update-dialog-open-link',
                            'data-update-dialog-title' => Yii::t('app', 'Select Topping'),
                        ));*/ ?>
                        <?php /*echo TbHtml::linkButton('', array(
                            'color' => TbHtml::BUTTON_COLOR_SUCCESS,
                            'size' => TbHtml::BUTTON_SIZE_MINI,
                            'icon' => 'glyphicon glyphicon-plus white',
                            'url' => $this->createUrl('saleItem/ShowItemsDetail/',
                                array('item_id' => $item_id, 'category_id' => $item['category_id'],'view'=>'cashier')),
                            'class' => 'update-dialog-open-link',
                            'data-update-dialog-title' => Yii::t('app', 'Items Detail'),
                        )); ?>
                        <?php } */?>
                        <?php echo $item['item_number']; ?>
                    </td>
                    <?php if ($item['topping'] == 0) { ?>
                        <td>
                           <span class="text-info">
                            <?php echo $item['name']; ?>
                           </span>
                        </td>
                    <?php } else { ?>
                        <td align="right"><span class="text-info orange"><?php echo $item['name']; ?></span></td>
                    <?php } ?>
                    <td>
                        <?php /*$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                            'method' => 'post',
                            'action' => Yii::app()->createUrl('saleItem/editItem/',
                                array('item_id' => $item['item_id'], 'item_parent_id' => $item_parent_id)),
                            'htmlOptions' => array('class' => 'line_item_form'),
                        ));*/
                        ?>

                        <?php echo $form->textField($model, "quantity", array(
                            'value' => $item['quantity'],
                            'class' => 'input-small input-grid alignRight',
                            'id' => "quantity_$item_id",
                            'placeholder' => 'Quantity',
                            'disabled' => 'disabled',
                            'data-id' => "$item_id",
                            //'data-parentid' => "$item_parent_id",
                            'maxlength' => 10
                        )); ?>

                        <?php //$this->endWidget(); ?>
                    </td>

                    <td>
                        <?php /*echo $form->textField($model, "[$item_id]price", array(
                            'value' => number_format($item['price'], Yii::app()->shoppingCart->getDecimalPlace()),
                            'disabled' => true,
                            'class' => 'input-small alignRight readonly',
                            'id' => "price_$item_id",
                            'placeholder' => 'Price',
                            'data-id' => "$item_id",
                            'maxlength' => 50,
                            'onkeypress' => 'return isNumberKey(event)'
                        ));*/ ?>
                        <?php echo $form->textField($model, "[$item_id]topping", array(
                            'value' => number_format($item['topping'], Yii::app()->shoppingCart->getDecimalPlace()),
                            'disabled' => true,
                            'class' => 'input-small alignRight readonly',
                            'id' => "topping_$item_id",
                            'placeholder' => 'Topping',
                            'data-id' => "$item_id",
                            'maxlength' => 50,
                            'onkeypress' => 'return isNumberKey(event)'
                        )); ?>
                    </td>

                    <td>
                        <?php echo $form->textField($model, "[$item_id]total", array(
                            'value' => $total_item,
                            'disabled' => true,
                            'class' => 'input-small alignRight readonly'
                        )); ?>
                    </td>
                </tr>
                <tr>
                    <div class="row">
                        <div class="col-12">
                            <td colspan=6>
                                <?php $this->renderPartial('partial/_grid_detail_order',array('view'=>'index','item_id'=>$item_id)); ?>
                            </td>
                        </div>
                    </div>
                </tr>
                <?php //$this->endWidget(); ?>  <!--/endformWidget-->
            <?php endforeach; ?> <!--/endforeach-->
            </tbody>
        </table>

        <?php if (empty($items)) {
            echo Yii::t('app', 'There are no items in the cart');
        } ?>
</div><!--/endslimscrool-->
<?php //$this->endWidget(); ?> <!--/endformWidget-->
