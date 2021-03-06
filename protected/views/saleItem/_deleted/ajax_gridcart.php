<div class="grid-view" id="grid_cart">  
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
            'id'=>'sale-item-form',
            'enableAjaxValidation'=>false,
            'layout'=>TbHtml::FORM_LAYOUT_INLINE,
    )); ?>
    <?php
    if (isset($warning))
    {
        echo TbHtml::alert(TbHtml::ALERT_COLOR_WARNING, $warning);
    }
    ?>
    <table class="table table-hover table-condensed">
        <thead>
            <tr><th><?php echo Yii::t('model','model.saleitem.name'); ?></th>
                <th><?php echo Yii::t('model','model.saleitem.price'); ?></th>
                <th><?php echo Yii::t('model','model.saleitem.quantity'); ?></th>
                <th class="<?php echo Yii::app()->settings->get('sale','discount'); ?>"><?php echo Yii::t('model','model.saleitem.discount_amount'); ?></th>
                <th><?php echo Yii::t('model','model.saleitem.total'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="cart_contents">
            <?php foreach(array_reverse($items,true) as $id=>$item): ?>
                <?php /*$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
                            'id'=>'line-item-form_'. $item['item_id'],
                            'enableAjaxValidation'=>false,
                            'type'=>'inline',
                            'action' => Yii::app()->createUrl('saleitem/EditItem/',array('item_id'=>$item['item_id'])),
                            'htmlOptions'=>array('class'=>'line_item_form'),
                       ));
                        * 
                     */ 
                ?>
                <?php
                    if (substr($item['discount'],0,1)=='$')
                    {
                        $total_item=round($item['price']*$item['quantity']-substr($item['discount'],1),Yii::app()->shoppingCart->getDecimalPlace(),PHP_ROUND_HALF_DOWN);
                    }    
                    else  
                    {  
                        $total_item=round(($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100),Yii::app()->shoppingCart->getDecimalPlace(),PHP_ROUND_HALF_DOWN);
                    } 
                    $item_id=$item['item_id'];
                    $cur_item_info= Item::model()->findbyPk($item_id);
                    $qty_in_stock=$cur_item_info->quantity;
                    
                    /* to do to remove */
                    $cur_item_unit= ItemUnitQuantity::model()->findbyPk($item_id);
                    $unit_name='';
                    if ($cur_item_unit)
                    {
                        $item_unit=ItemUnit::model()->findbyPk($cur_item_unit->unit_id);
                        $unit_name=$item_unit->unit_name;
                    }
                    
                ?>
                    <tr>
                        <td> 
                            <?php echo $item['name']; ?><br/>
                            <span class="text-info"><?php echo $qty_in_stock . $unit_name .' ' . Yii::t('app','in stock') ?> </span>
                        </td>
                        <td><?php echo $form->textField($model,"[$item_id]price",array('value'=>$item['price'],'class'=>'input-small numeric input-grid','id'=>"price_$item_id",'placeholder'=>'Price','data-id'=>"$item_id",'maxlength'=>50,'onkeypress'=>'return isNumberKey(event)')); ?></td>
                        <td>
                            <?php echo $form->textField($model,"[$item_id]quantity",array('value'=>$item['quantity'],'class'=>'input-small numeric input-grid','id'=>"quantity_$item_id",'placeholder'=>'Quantity','data-id'=>"$item_id",'maxlength'=>50,'onkeypress'=>'return isNumberKey(event)')); ?>
                        </td>
                        <td class="<?php echo Yii::app()->settings->get('sale','discount'); ?>"><?php echo $form->textField($model,"[$item_id]discount",array('value'=>$item['discount'],'class'=>'input-small input-grid','id'=>"discount_$item_id",'placeholder'=>'Discount','data-id'=>"$item_id",'maxlength'=>50)); ?></td>
                        <td><?php echo $total_item; ?>
                        <td><?php echo TbHtml::linkButton('', array(
                                'color'=>TbHtml::BUTTON_COLOR_DANGER,
                                'size' => TbHtml::BUTTON_SIZE_MINI,
                                'icon' => 'glyphicon glyphicon-trash ',
                                'url' => array('DeleteItem', 'item_id' => $item_id),
                                'class' => 'delete-item',
                                'title' => Yii::t('app', 'Remove'),
                            ));
                            ?>
                        </td>    
                    </tr>
                <?php //$this->endWidget(); ?>  <!--/endformWidget-->     
            <?php endforeach; ?> <!--/endforeach-->

        </tbody>
    </table>
    <?php $this->endWidget(); ?>  <!--/endformWidget-->     

    <?php
    if (empty($items))
        echo Yii::t('app','There are no items in the cart');

    ?> 
    
    <?php if (!empty($items)) { ?>
        <div class="widget-toolbox padding-8 clearfix">
        <div class="pull-right">
            <?php echo $form->textField($model,'gdiscount',array('id'=>'gdiscount_id','class'=>'input-sm input-gdiscount','placeholder'=>'Total Discount','maxlength'=>25,'append' => '%','onkeypress' => 'return isNumberKey(event)')); ?>
        </div>
        </div>
    <?php } ?>

</div> <!--/endgridcartdiv-->     
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      