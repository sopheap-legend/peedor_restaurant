<style>
    body{background-color:#e4e6e9;min-height:100%;padding-bottom:0;font-family:'Arial';font-size:11px !important; font-weight: bold;};
</style>
<div class="row">
<div class="col-sm-10 col-sm-offset-1">
    <!-- #section:pages/invoice -->
    <div class="widget-box transparent">
        <?php if (Yii::app()->settings->get('receipt', 'printcompanyLogo')=='1') { ?>
            <div id="copmany_logo" class="center">
                <?php echo TbHtml::image(Yii::app()->baseUrl . '/images/logo.png','Company\'s logo',array('width'=>'110')); ?>
            </div>
        <?php } ?>
        <?php if (Yii::app()->settings->get('receipt', 'printcompanyName')=='1') { ?> 
            <div class="center">
                <?php echo TbHtml::encode(Yii::app()->settings->get('site', 'companyAddress')); ?>
            </div>
        <?php } ?>
        <?php if (Yii::app()->settings->get('receipt', 'printcompanyPhone')=='1') { ?>  
            <div class="center">
                <b class="red"><?php echo TbHtml::encode(Yii::app()->settings->get('site', 'companyPhone')); ?></b>
            </div>
        <?php } ?>
        <div id="sale_receipt" class="center"><?php echo TbHtml::encode(Yii::t('app','Sale Receipt')); ?></div>
        <?php if (Yii::app()->settings->get('receipt', 'printtransactionTime')=='1') { ?> 
            <div id="sale_time" class="center"><?php echo TbHtml::encode($transaction_time); ?></div>
        <?php } ?>
        
        <div class="widget-body">
            <div class="widget-main">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <ul class="list-unstyled spaced">
                                <li>
                                    <i class="ace-icon fa fa-caret-right blue"></i>
                                    <?php echo Yii::t('app','Customer Name') . " : ". TbHtml::encode(ucwords($customer)); ?>
                                </li>
                                <li>
                                    <i class="ace-icon fa fa-caret-right blue"></i>
                                    <?php //echo Yii::t('app','Address') . " : ". TbHtml::encode(ucwords($customer_phone)); ?>
                                    <?php echo TbHtml::encode(Yii::t('app','Invoice ID') . " : " . $sale_id); ?>
                                </li>
                                 <li>
                                    <i class="ace-icon fa fa-caret-right blue"></i>
                                     <?php echo TbHtml::encode(Yii::t('app','Seller') . " : ". $employee); ?>
                                </li>
                            </ul>
                        </div>
                    </div><!-- /.col -->
                    
                </div><!-- /.row -->

                <div class="space-6"></div>

                <div>
                    <table class="table table-striped table-bordered" id="receipt_items">
                        <thead>
                            <tr>
                                <th class="center"><?php echo Yii::t('app','#'); ?></th>
                                <th><?php echo Yii::t('app','Name'); ?></th>
                                <th><?php echo Yii::t('app','Price'); ?></th>
                                <th ><?php echo TbHtml::encode(Yii::t('app','Qty')); ?></th>
                                <th class="<?php echo Yii::app()->settings->get('sale','discount'); ?>">
                                    <?php echo TbHtml::encode(Yii::t('app','Discount')); ?>
                                </th>
                                <th class="center"><?php echo TbHtml::encode(Yii::t('app','Total')); ?></th>
                            </tr>
                        </thead>
                        <tbody id="cart_contents">
                        <?php $i=0; ?>
                        <?php foreach(array_reverse($items,true) as $id=>$item): ?>
                            <?php
                                   $i=$i+1;
                                   $discount_arr=Common::Discount($item['discount']);
                                   $discount_amount=$discount_arr[0];
                                   $discount_symbol=$discount_arr[1];
                                   if ($discount_symbol=='$') {
                                       $total_item=number_format($item['price']*$item['quantity']-$discount_amount,Yii::app()->shoppingCart->getDecimalPlace());
                                   } else {
                                       $total_item=number_format($item['price']*$item['quantity']-$item['price']*$item['quantity']*$discount_amount/100,Yii::app()->shoppingCart->getDecimalPlace());
                                   }
                            ?>
                            <tr>
                                <td class="center"><?php echo TbHtml::encode($i); ?></td>
                                <td><?php echo TbHtml::encode($item['name']); ?></td>
                                <td><?php echo TbHtml::encode(number_format($item['price'],Yii::app()->shoppingCart->getDecimalPlace())); ?></td>
                                <td><?php echo TbHtml::encode($item['quantity']); ?></td>
                                <td class="<?php echo Yii::app()->settings->get('sale','discount'); ?>"><?php echo TbHtml::encode($item['discount'] . $discount_symbol); ?></td>
                                <td class="center"><?php echo TbHtml::encode($total_item); ?>
                            </tr>
                        <?php endforeach; ?> <!--/endforeach-->

                        <?php if (Yii::app()->settings->get('sale','discount')=='hidden') { ?> 
                            <tr>
                                <td colspan="4" class="center"><?php echo TbHtml::b(Yii::t('app','Total Quantity')); ?></td>
                                <td colspan="2" class="center"><?php echo TbHtml::b(number_format($qtytotal,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
                            </tr>
                            <?php if ($gdiscount!==NULL && $gdiscount>0) { ?>
                            <tr>
                                <td colspan="4" class="center"><?php echo TbHtml::b(Yii::t('app','Sub Total')); ?></td>
                                <td colspan="2" class="center"><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($sub_total,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
                            </tr>    
                            <tr>
                                <td colspan="4" class="center"><?php echo TbHtml::b(number_format($gdiscount,0) . '% ' . Yii::t('app','Discount')); ?></td>
                                <td colspan="2" class="center"><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($sub_total/$gdiscount,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
                            </tr>  
                            <?php } ?>
                            <tr>
                                <td colspan="4" class="center"><?php echo TbHtml::b(Yii::t('app','Total')); ?></td>
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
                                    <td colspan="4" class="center"><?php echo TbHtml::b(Yii::t('app','Paid Amount')); //echo TbHtml::b(Yii::t('app',$payment['payment_type'] . ' Receive')); ?></td>
                                    <td colspan="2" class="center"><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($payment['payment_amount'],Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>  
                                </tr>
                            <?php endforeach;?>

                            <tr>
                                <td colspan="4" class="center">
                                    <?php echo TbHtml::b(Yii::t('app','Change Due')); ?>
                                </td>
                                <td colspan="2" class="center"><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($amount_change,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>  
                            </tr>    

                        <?php } else { ?>

                            <tr>
                                <td colspan="5" class="center"><?php echo TbHtml::b(Yii::t('app','Total Quantity')); ?></td>
                                <td colspan="2" class="center"><?php echo TbHtml::b(number_format($qtytotal,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
                            </tr>
                            
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
                                <td colspan="5" class="center"><?php echo TbHtml::b(Yii::t('app','Total')); ?></td>
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

                            <tr>
                                <td colspan="5" class="center">
                                    <?php echo TbHtml::b(Yii::t('app','Change Due')); ?>    
                                </td>
                                <td colspan="2" class="center"><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($amount_change,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>  
                            </tr> 

                        <?php } ?>    
                    </tbody>

                    </table>                        
                </div>

                <div class="space-6"></div>
                <div class="center">
                    <?php echo TbHtml::b(Yii::t('app',Yii::app()->settings->get('site', 'returnPolicy'))); ?>
                </div>

                <?php if (Yii::app()->settings->get('receipt', 'printSignature')==='1') { ?> 
                    <div class="space-6"></div>
                    <div class="hr hr8 hr-double hr-dotted"></div>
                    <div class="row">
                        <div class="col-sm-5 pull-right">
                            <div class="pull-right">
                                <?php echo CHtml::encode(Yii::t('app','Customers')); ?> 
                            </div>
                        </div>
                        <div class="col-sm-7 pull-left"> <?php echo CHtml::encode(Yii::t('app','Cashier')); ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>
  </div>
  <!-- /section:pages/invoice -->
</div>
</div>

<script>
function printpage()
{
    setTimeout(window.location.href='index',500);
    window.print();
    return true;
}
window.onload=printpage();
</script>