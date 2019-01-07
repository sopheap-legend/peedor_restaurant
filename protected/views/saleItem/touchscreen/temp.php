<table class="table table-bordered" id="receipt_items">
        <thead>
            <tr>
                <th><?php echo Yii::t('app','Name'); ?></th>
                <th class="center"><?php echo Yii::t('app','Price'); ?></th>
                <th class="center" ><?php echo TbHtml::encode(Yii::t('app','Qty')); ?></th>
                <th class="<?php echo Yii::app()->settings->get('sale','discount'); ?>">
                    <?php echo TbHtml::encode(Yii::t('app','Discount')); ?>
                </th>
                <th class="center"><?php echo TbHtml::encode(Yii::t('app','Total')); ?></th>
            </tr>
        </thead>
        <tbody id="cart_contents">
        <?php $i=0; ?>
        <?php foreach($items as $id=>$item): ?>
            <?php
                   $i=$i+1;
                   $total_item=number_format($item['total'],Yii::app()->shoppingCart->getDecimalPlace());
                   $item_id=$item['item_id'];
            ?>
            <tr>
                <!-- <td class="center"><?php //echo TbHtml::encode($i); ?></td> -->

                <?php if ($item['topping']==0) { ?>
                    <td><?php echo TbHtml::encode($item['name']); ?></td>
                <?php } else { ?>
                     <td align="right"><span class="text-info"><?php echo $item['name']; ?></span></td>
                <?php } ?>
                <td class="center"><?php echo TbHtml::encode(number_format($item['price'],Yii::app()->shoppingCart->getDecimalPlace())); ?></td>
                <td class="center"><?php echo TbHtml::encode($item['quantity']); ?></td>
                <td class="<?php echo Yii::app()->settings->get('sale','discount'); ?>"><?php echo TbHtml::encode($item['discount']); ?></td>
                <td class="center"><?php echo TbHtml::encode($total_item); ?>
            </tr>
        <?php endforeach; ?> <!--/endforeach-->

        <?php if (Yii::app()->settings->get('sale','discount')=='hidden') { ?> 
           
            <!--
            <tr>
                <td colspan="3" class="center"><?php //echo TbHtml::b(Yii::t('app','Total Quantity')); ?></td>
                <td colspan="2" class="center"><?php //echo TbHtml::b(number_format($qtytotal,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
            </tr>
            -->
            <?php //if ($gdiscount!==NULL && $gdiscount>0) { ?>
            <tr>
                <td colspan="3" class="center"><?php echo Yii::t('app','Sub Total'); ?></td>
                <td colspan="2" class="center"><?php echo Yii::app()->settings->get('site', 'currencySymbol') .number_format($sub_total,Yii::app()->shoppingCart->getDecimalPlace(), '.', ','); ?></td>
            </tr>    
            <tr>
                <td colspan="3" class="center"><?php echo $giftcard_info; ?> </td>
                <td colspan="2" class="center"><?php echo Yii::app()->settings->get('site', 'currencySymbol') . $discount_amount; ?></td>
            </tr>  
            <?php //} ?>
            <tr>
                <td colspan="3" class="center"><?php echo TbHtml::b(Yii::t('app','Total')); ?></td>
                <td colspan="2" class="center"><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($total,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
            </tr>

            <!--
            <tr>
                    <td colspan="3" style='text-align:right;'><?php //echo TbHtml::b(Yii::t('app','Total in KHR')); ?></td>
                    <td colspan="2" style='text-align:right'><?php //echo TbHtml::b(Yii::app()->numberFormatter->formatCurrency(($total_khr), 'R')); ?></td>
            </tr>
            -->

            <?php //if ($customer_debt!==NULL && $customer_debt>0) { ?>
                <!--
                <tr>
                    <td colspan="4" class="center"><?php //echo TbHtml::b(Yii::t('app','Last Debt')); ?></td>
                    <td colspan="2" class="center"><?php //echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($customer_debt,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
                </tr>
                -->
            <?php //} ?>

            <?php foreach($payments as $payment_id=>$payment): ?> 
                <?php //$splitpayment=explode(':',$payment['payment_type']) ?>
                <tr>
                    <td colspan="3" class="center"><?php echo TbHtml::b(Yii::t('app','Paid Amount')); //echo TbHtml::b(Yii::t('app',$payment['payment_type'] . ' Receive')); ?></td>
                    <td colspan="2" class="center"><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($payment['payment_amount'],Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>  
                </tr>
            <?php endforeach;?>
                
        <?php } else { ?>

            <!--    
            <tr>
                <td colspan="5" class="center"><?php //echo TbHtml::b(Yii::t('app','Total Quantity')); ?></td>
                <td colspan="2" class="center"><?php //echo TbHtml::b(number_format($qtytotal,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
            </tr>
            -->

            <?php if ($gdiscount!==NULL && $gdiscount>0) { ?>
            <tr>
                <td colspan="5" class="center"><?php echo TbHtml::b(Yii::t('app','Sub Total')); ?></td>
                <td colspan="2" class="center"><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($sub_total,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
            </tr>    
            <tr>
                <td colspan="5" class="center"><?php echo TbHtml::b(number_format($gdiscount,0) . '% ' . Yii::t('app','Discount')); ?></td>
                <td colspan="2" class="center"><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($sub_total/$gdiscount,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
            </tr>  
            <?php } ?>

            <tr>
                <td colspan="5" class="center"><?php echo TbHtml::b(Yii::t('app','Grant Total')); ?></td>
                <td colspan="2" class="center"><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($total,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
            </tr>

            <!--
            <tr>
                <td colspan="5" style='text-align:right;'><?php //echo TbHtml::b(Yii::t('app','Total in KHR')); ?></td>
                <td colspan="2" style='text-align:right'><?php //echo TbHtml::b(Yii::app()->numberFormatter->formatCurrency(($total_khr), 'R')); ?></td>
            </tr>
            -->

            <?php foreach($payments as $payment_id=>$payment): ?> 
                <?php //$splitpayment=explode(':',$payment['payment_type']) ?>
                <tr>
                    <td colspan="5" class="center"><?php echo TbHtml::b(Yii::t('app','Paid Amount')); //echo TbHtml::b(Yii::t('app',$payment['payment_type'] . ' Receive')); ?></td>
                    <td colspan="2" class="center"><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($payment['payment_amount'],Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>  
                </tr>
            <?php endforeach;?>

        <?php } ?>    
    </tbody>

    </table>  