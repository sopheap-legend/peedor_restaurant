<div class="col-xs-12 col-sm-8 widget-container-col">
    <div class="message expire_date" style="display:none">
        <div class="alert in alert-block fade alert-success">Transaction Failed !</div>
    </div>

        <?php
        $this->renderPartial('_search', array(
            'model' => $model,
        ));
        ?>
    
        <!-- #section:grid.cart.layout -->
        <div class="grid-view" id="grid_cart">  
            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'sale-item-form',
                'enableAjaxValidation' => false,
                'layout' => TbHtml::FORM_LAYOUT_INLINE,
            ));
            ?>
            <?php
            if (isset($warning)) {
                echo TbHtml::alert(TbHtml::ALERT_COLOR_WARNING, $warning);
            }
            ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr><th><?php echo Yii::t('model', 'model.saleitem.name'); ?></th>
                        <th><?php echo Yii::t('model', 'model.saleitem.price'); ?></th>
                        <th><?php echo Yii::t('model', 'model.saleitem.quantity'); ?></th>
                        <th class="<?php echo Yii::app()->settings->get('sale', 'discount'); ?>"><?php echo Yii::t('model', 'model.saleitem.discount_amount'); ?></th>
                        <th><?php echo Yii::t('model', 'model.saleitem.total'); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="cart_contents">
                    <?php foreach (array_reverse($items, true) as $id => $item): ?>
                        <?php /* $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
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
                        
                        if (substr($item['discount'], 0, 1) == '$') {
                            $total_item = round($item['price'] * $item['quantity'] - substr($item['discount'], 1), Yii::app()->shoppingCart->getDecimalPlace(), PHP_ROUND_HALF_DOWN);
                        } else {
                            $total_item = round(($item['price'] * $item['quantity'] - $item['price'] * $item['quantity'] * $item['discount'] / 100), Yii::app()->shoppingCart->getDecimalPlace(), PHP_ROUND_HALF_DOWN);
                        }
                        $item_id = $item['item_id'];
                        $cur_item_info = Item::model()->findbyPk($item_id);
                        $qty_in_stock = $cur_item_info->quantity;
                        
                        /* to do to remove */
                        $cur_item_unit = ItemUnitQuantity::model()->findbyPk($item_id);
                        $unit_name = '';
                        if ($cur_item_unit) {
                            $item_unit = ItemUnit::model()->findbyPk($cur_item_unit->unit_id);
                            $unit_name = $item_unit->unit_name;
                        }
                        ?>
                        <tr>
                            <td> 
                                <?php echo $item['name']; ?><br/>
                                <span class="text-info"><?php echo $qty_in_stock . ' ' . $unit_name . ' ' . Yii::t('app', 'in stock') ?> </span>
                            </td>
                            <td><?php echo $form->textField($model, "[$item_id]price", array('value' => $item['price'], 'class' => 'input-small numeric input-grid', 'id' => "price_$item_id", 'placeholder' => 'Price', 'data-id' => "$item_id", 'maxlength' => 50, 'onkeypress' => 'return isNumberKey(event)')); ?></td>
                            <td>
                                <?php echo $form->textField($model, "[$item_id]quantity", array('value' => $item['quantity'], 'class' => 'input-small numeric input-grid', 'id' => "quantity_$item_id", 'placeholder' => 'Quantity', 'data-id' => "$item_id", 'maxlength' => 50, 'onkeypress' => 'return isNumberKey(event)')); ?>
                            </td>
                            <td class="<?php echo Yii::app()->settings->get('sale', 'discount'); ?>"><?php //echo $form->dropDownList($model, 'discount',array('%', '$'),array('class'=>'input-mini'));  ?>
                                <?php echo $form->textField($model, "[$item_id]discount", array('value' => $item['discount'], 'class' => 'input-small input-grid', 'id' => "discount_$item_id", 'placeholder' => 'Discount', 'data-id' => "$item_id", 'maxlength' => 50)); ?></td>
                            <td><?php echo $total_item; ?>
                            <td><?php
                                echo TbHtml::linkButton('', array(
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
            

            <?php
            if (empty($items)) {
                echo Yii::t('app', 'There are no items in the cart');
            }
            ?> 
            
            <?php if (!empty($items)) { ?>
              <div class="widget-toolbox padding-8 clearfix">
              <div class="pull-right" id="gdiscount_content">
                  <?php echo $form->textField($model,'gdiscount',array('id'=>'gdiscount_id','class'=>'input-small input-gdiscount','placeholder'=>'Total Discount','maxlength'=>25,'append' => '%','onkeypress' => 'return isNumberKey(event)')); ?>
              </div>
              </div>
             <?php } ?>
        
        <?php $this->endWidget(); ?>  <!--/endformWidget-->         

        </div> <!-- #section:grid.cart.layout -->
        
      
</div> <!--/span9-->

<div class="col-xs-12 col-sm-4 widget-container-col">
    <!-- #section:canel-cart.layout -->
    <div class="row">
        <div id="cancel_cart">
            <?php if ($count_item <> 0) { ?> 
                <div align="right">
                    <?php
                    echo TbHtml::linkButton(Yii::t('app', 'Draft'), array(
                        'color' => TbHtml::BUTTON_COLOR_WARNING,
                        'size' => TbHtml::BUTTON_SIZE_SMALL,
                        'icon' => 'glyphicon-pause white',
                        'url' => Yii::app()->createUrl('SaleItem/SuspendSale/'),
                        'class' => 'suspend-sale',
                        'title' => Yii::t('app', 'Draft'),
                    ));
                    ?>

                    <?php
                    echo TbHtml::linkButton(Yii::t('app', 'form.button.cancelsale'), array(
                        'color' => TbHtml::BUTTON_COLOR_DANGER,
                        'size' => TbHtml::BUTTON_SIZE_SMALL,
                        'icon' => '	glyphicon-remove white',
                        'url' => Yii::app()->createUrl('SaleItem/CancelSale/'),
                        'class' => 'cancel-sale',
                        'title' => Yii::t('app', 'Cancel'),
                    ));
                    ?>     
                </div>
            <?php } ?>
        </div>    
    </div> <!-- #section:canel-cart.layout -->
    
    <!-- #section:client.layout -->
    <div class="row">
        <div class="sidebar-nav" id="client_cart">
            <?php
            if (isset($customer)) {
                $this->widget('yiiwheels.widgets.box.WhBox', array(
                    'title' => Yii::t('app', 'form.sale.client_title'),
                    'headerIcon' => 'ace-icon fa fa-users',
                    'content' => $this->renderPartial('_client_selected', array('model' => $model, 'customer' => $customer, 'customer_mobile_no' => $customer_mobile_no), true),
                ));
            } else {
                $this->widget('yiiwheels.widgets.box.WhBox', array(
                    'title' => Yii::t('app', 'form.sale.client_title'),
                    'headerIcon' => 'ace-icon fa fa-users',
                    'content' => $this->renderPartial('_client', array('model' => $model), true)
                ));
            }
            ?>
        </div>  
    </div> <!-- #section:client.layout -->
   
    <!-- #section:payment-cart.layout -->
    <div class="row">
        <div class="sidebar-nav" id="payment_cart">
            <?php if ($count_item <> 0) { ?>    
                
                    <?php
                    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                        'id' => 'payment-form',
                        'enableAjaxValidation' => false,
                        'layout' => TbHtml::FORM_LAYOUT_INLINE,
                    ));
                    ?>
                        <table class="table table-bordered table-condensed">
                            <tbody>
                                <tr>
                                    <td><?php echo Yii::t('app', 'form.sale.payment_lbl_itemcart'); ?> :</td>
                                    <td><?php echo $count_item; ?></td>
                                </tr>
                                <?php if ($gdiscount!==NULL && $gdiscount>0) { ?>
                                <tr>
                                    <td><?php echo Yii::t('app', 'form.sale.payment_lbl_subtotal'); ?> :</td>
                                    <td><span class="badge badge-info bigger-120"><?php echo Yii::app()->settings->get('site', 'currencySymbol') . number_format($sub_total, Yii::app()->shoppingCart->getDecimalPlace(), '.', ','); ?></span></td>
                                </tr>
                                <tr>
                                    <td><?php echo $gdiscount . '% ' . Yii::t('app', 'Discount'); ?> :</td>
                                    <td><span class="badge badge-info bigger-120"><?php echo Yii::app()->settings->get('site', 'currencySymbol') . number_format($sub_total/$gdiscount, Yii::app()->shoppingCart->getDecimalPlace(), '.', ','); ?></span></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td><?php echo Yii::t('app', 'form.sale.payment_lbl_total'); ?> :</td>
                                    <td><span class="badge badge-info bigger-120"><?php echo Yii::app()->settings->get('site', 'currencySymbol') . number_format($total, Yii::app()->shoppingCart->getDecimalPlace(), '.', ','); ?></span></td>
                                </tr>
                                <tr>
                                    <td><?php echo Yii::t('app', 'form.sale.payment_lbl_amountdue'); ?>:</td>
                                    <td><span class="badge badge-important bigger-120"><?php echo Yii::app()->settings->get('site', 'currencySymbol') . number_format($total, Yii::app()->shoppingCart->getDecimalPlace(), '.', ','); ?><span></td>
                                </tr>
                                <tr>
                                    <td><?php echo Yii::t('app', 'Payment Type'); ?>:</td>
                                    <td><?php echo $form->dropDownList($model, 'payment_type', InvoiceItem::itemAlias('payment_type'), array('id' => 'payment_type_id')); ?> </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style='text-align:right'><?php echo $form->textFieldControlGroup($model, 'payment_amount', array('class' => 'form-control', 'value' => Yii::app()->numberFormatter->format('0.00', $amount_due), 'style' => 'text-align: right', 'maxlength' => 10, 'id' => 'payment_amount_id', 'data-url' => Yii::app()->createUrl('SaleItem/AddPayment/'),)); ?> </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style='text-align:right'><?php
                                        echo TbHtml::linkButton(Yii::t('app', 'form.sale.payment_btn_addpayment'), array(
                                            'color' => TbHtml::BUTTON_COLOR_INFO,
                                            'size' => TbHtml::BUTTON_SIZE_MINI,
                                            'icon' => 'glyphicon-plus white',
                                            'url' => Yii::app()->createUrl('SaleItem/AddPayment/'),
                                            'class' => 'add-payment',
                                            'title' => Yii::t('app', 'Add Payment'),
                                        ));
                                        ?>   
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                    <?php // Only show this part if there is at least one payment entered.
                    if ($count_payment > 0) {
                    ?>
                        <table class="table table-striped table-condensed">
                            <thead class="thin-border-bottom">
                                <tr><th>Type</th><td>Amount</th><th></tr>
                            </thead>
                            <tbody id="payment_content">
                                <?php foreach ($payments as $id => $payment): ?>
                                <tr>
                                    <td><?php echo $payment['payment_type']; ?></td>
                                    <td><?php echo Yii::app()->settings->get('site', 'currencySymbol') . number_format($payment['payment_amount'],Yii::app()->shoppingCart->getDecimalPlace()); ?></td>
                                    <!--
                                    <td><a class='delete' title='Cancel Payment' rel='tooltip' href='#'><i class='icon-remove'></i></a></td>
                                    -->
                                    <td>
                                        <?php
                                        echo TbHtml::linkButton('', array(
                                            //'color'=>TbHtml::BUTTON_COLOR_INFO,
                                            'size' => TbHtml::BUTTON_SIZE_MINI,
                                            'icon' => 'glyphicon-remove',
                                            'url' => Yii::app()->createUrl('SaleItem/DeletePayment', array('payment_id' => $payment['payment_type'])),
                                            'class' => 'delete-payment',
                                            'title' => Yii::t('app', 'Delete Payment'),
                                        ));
                                        ?>     
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                        <?php } ?>

                        <?php if ($count_payment > 0) { ?>
                            <td colspan="3" style='text-align:right'>
                            <?php
                            echo TbHtml::linkButton(Yii::t('app', 'form.sale.payment_btn_completesale'), array(
                                'color' => TbHtml::BUTTON_COLOR_SUCCESS,
                                //'size'=>TbHtml::BUTTON_SIZE_SMALL,
                                'icon' => 'glyphicon glyphicon-off white',
                                'url' => Yii::app()->createUrl('SaleItem/CompleteSale/'),
                                'class' => 'complete-sale',
                                'title' => Yii::t('app', 'Complete Sale'),
                            ));
                            ?>        
                            </td>    
                        <?php } ?> 
                        <!--
                        <div id="comment_content" align="right">
                        <?php //echo $form->textArea($model,'comment',array('rows'=>1, 'cols'=>20,'class'=>'input-small','maxlength'=>250,'id'=>'comment_id'));  ?>
                        </div>
                        -->
                        </tbody>
                    </table>
                    
                    <?php $this->endWidget(); ?> 
            </div> <!-- /section:custom/widget-main -->    
        <?php } ?>    
        </div> <!-- payment cart -->
    </div> <!-- #section:payment-cart.layout -->

</div> <!--/span3-->

<!--    
</div>
</div>
-->

<div class="waiting"><!-- Place at bottom of page --></div>
