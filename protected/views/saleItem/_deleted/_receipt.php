<style>
  .hidden {display:none}
</style>
<div lass="col-xs-12 col-sm-9 widget-container-col>
    <?php
    if (isset($error_message))
    {   
        echo '<h2 style="text-align: center;">'.$error_message.'</h1>';  
    } 
    else {
    ?>
        <div id="receipt_header" style="width:200px; margin:0 auto;">
            <?php if (Yii::app()->settings->get('receipt', 'printcompanyLogo')=='1') { ?>
                <div id="copmany_logo" align="center"><?php echo TbHtml::image(Yii::app()->baseUrl . '/images/logo.png','Company\'s logo',array('width'=>'110')); ?></div>
            <?php } ?>
            <?php if (Yii::app()->settings->get('receipt', 'printcompanyName')=='1') { ?>    
                <div id="company_name" align="center"><?php echo TbHtml::b(Yii::app()->settings->get('site', 'companyName')); ?></b></div>
            <?php } ?>
            <?php if (Yii::app()->settings->get('receipt', 'printcompanyAddress')=='1') { ?>    
                <div id="company_address" align="center"><?php echo TbHtml::b(Yii::app()->settings->get('site', 'companyAddress')); ?></div>
            <?php } ?>
            <?php if (Yii::app()->settings->get('receipt', 'printcompanyPhone')=='1') { ?>    
                <div id="company_phone" align="center"><b><?php echo TbHtml::b(Yii::app()->settings->get('site', 'companyPhone')); ?></b></div>
            <?php } ?>
                <div id="sale_receipt" align="center"><?php echo TbHtml::b('Sales Receipt'); ?></div>
            <?php if (Yii::app()->settings->get('receipt', 'printtransactionTime')=='1') { ?> 
                <div id="sale_time" align="center"><?php echo TbHtml::b($transaction_time); ?></div>
            <?php } ?>
        </div>    
        <div id="receipt_general_info">
            <?php if(isset($customer))
            {
            ?>  
            <div id="customer"><?php echo CHtml::encode(Yii::t('app','Customer Name') . " : ". TbHtml::b(ucwords($customer))); ?></div>
            <?php
            }
            ?>
            <div id="sale_id"><?php echo TbHtml::b(Yii::t('app','Invoice ID') . " : " . $sale_id); ?></div>
            <div id="employee"><?php echo TbHtml::b(Yii::t('app','Seller') . " : ". $employee); ?></div>
        </div>
        <div class="grid-view">  
            <table class="table" id="receipt_items">
                <thead>
                    <tr><th><?php echo Yii::t('app','Name'); ?></th>
                        <th><?php echo Yii::t('app','Price'); ?></th>
                        <th style='text-align:center;'><?php echo TbHtml::b(Yii::t('app','Quantity')); ?></th>
                        <th style='text-align:center;' class="<?php echo Yii::app()->settings->get('system','discount'); ?>"><?php echo TbHtml::b(Yii::t('app','Discount')); ?></th>
                        <th style='text-align:right;'><?php echo TbHtml::b(Yii::t('app','Total')); ?></th>
                    </tr>
                </thead>
                <tbody id="cart_contents">
                    <?php foreach(array_reverse($items,true) as $id=>$item): ?>
                     <?php
                            if (substr($item['discount'],0,1)=='$')
                            {
                                $total_item=number_format($item['price']*$item['quantity']-substr($item['discount'],1),Yii::app()->shoppingCart->getDecimalPlace());
                                $discount_symbol='$';
                            }    
                            else  
                            {  
                                $total_item=number_format($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100,Yii::app()->shoppingCart->getDecimalPlace());
                                 $discount_symbol='%';
                            } 
                      ?>
                            <tr>
                                <td><?php echo TbHtml::b($item['name']); ?></td>
                                <td><?php echo TbHtml::b(number_format($item['price'],Yii::app()->shoppingCart->getDecimalPlace())); ?></td>
                                <td style='text-align:center;'><?php echo TbHtml::b($item['quantity']); ?></td>
                                <td style='text-align:center;' class="<?php echo Yii::app()->settings->get('system','discount'); ?>"><?php echo TbHtml::b($item['discount'] . $discount_symbol); ?></td>
                                <td style='text-align:right;'><?php echo TbHtml::b($total_item); ?>
                            </tr>
                    <?php endforeach; ?> <!--/endforeach-->

                    <?php if (Yii::app()->settings->get('system','discount')=='hidden') { ?> 
                    
						<tr>
                            <td colspan="3" style='text-align:right;'><?php echo TbHtml::b(Yii::t('app','Total Quantity')); ?></td>
                            <td colspan="2" style='text-align:right'><?php echo TbHtml::b(number_format($qtytotal,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
                        </tr>
					
                        <tr>
                            <td colspan="3" style='text-align:right;'><?php echo TbHtml::b(Yii::t('app','Total')); ?></td>
                            <td colspan="2" style='text-align:right'><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($total,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
                        </tr>
                        
                        <!--
                        <tr>
                                <td colspan="3" style='text-align:right;'><?php //echo TbHtml::b(Yii::t('app','Total in KHR')); ?></td>
                                <td colspan="2" style='text-align:right'><?php //echo TbHtml::b(Yii::app()->numberFormatter->formatCurrency(($total_khr), 'R')); ?></td>
                        </tr>
                        -->

                        <?php foreach($payments as $payment_id=>$payment): ?> 
                            <?php //$splitpayment=explode(':',$payment['payment_type']) ?>
                            <tr>
                                <td colspan="3" style='text-align:right;'><?php echo TbHtml::b(Yii::t('app','Paid Amount')); //echo TbHtml::b(Yii::t('app',$payment['payment_type'] . ' Receive')); ?></td>
                                <td colspan="2" style='text-align:right;'><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($payment['payment_amount'],Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>  
                            </tr>
                        <?php endforeach;?>

                        <tr>
                            <td colspan="3" style='text-align:right;'><?php echo TbHtml::b(Yii::t('app','Change Due')); ?></td>
                            <td colspan="2" style='text-align:right;'><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($amount_change,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>  
                        </tr>    
                    
                    <?php } else { ?>
					
                        <tr>
                            <td colspan="4" style='text-align:right;'><?php echo TbHtml::b(Yii::t('app','Total Quantity')); ?></td>
                            <td colspan="2" style='text-align:right'><?php echo TbHtml::b(number_format($qtytotal,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
                        </tr>
                        
                        <tr>
                            <td colspan="4" style='text-align:right;'><?php echo TbHtml::b(Yii::t('app','Total')); ?></td>
                            <td colspan="2" style='text-align:right'><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($total,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>
                        </tr>
                        
                        <!--
                        <tr>
                            <td colspan="3" style='text-align:right;'><?php //echo TbHtml::b(Yii::t('app','Total in KHR')); ?></td>
                            <td colspan="2" style='text-align:right'><?php //echo TbHtml::b(Yii::app()->numberFormatter->formatCurrency(($total_khr), 'R')); ?></td>
                        </tr>
                        -->

                        <?php foreach($payments as $payment_id=>$payment): ?> 
                            <?php //$splitpayment=explode(':',$payment['payment_type']) ?>
                            <tr>
                                <td colspan="4" style='text-align:right;'><?php echo TbHtml::b(Yii::t('app','Paid Amount')); //echo TbHtml::b(Yii::t('app',$payment['payment_type'] . ' Receive')); ?></td>
                                <td colspan="2" style='text-align:right;'><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($payment['payment_amount'],Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>  
                            </tr>
                        <?php endforeach;?>

                        <tr>
                            <td colspan="4" style='text-align:right;'><?php echo TbHtml::b(Yii::t('app','Change Due')); ?></td>
                            <td colspan="2" style='text-align:right;'><?php echo TbHtml::b(Yii::app()->settings->get('site', 'currencySymbol') .number_format($amount_change,Yii::app()->shoppingCart->getDecimalPlace(), '.', ',')); ?></td>  
                        </tr> 
                     
                    <?php } ?>    
                    

                </tbody>

            </table>
        </div>
   
        <div id="return_policy" style="width:200px; margin:0 auto;">
            <div id="return_policy" align="center"><?php echo TbHtml::b(Yii::t('app',Yii::app()->settings->get('site', 'returnPolicy'))); ?></div>
        </div> 
   
        <?php if (Yii::app()->settings->get('receipt', 'printSignature')==='1') { ?> 
        <div class="clearfix">
            <div class="pull-right">
                <div class="hr hr6"></div>
                <?php echo CHtml::encode(Yii::t('app','Customers')); ?>    
            </div>
            <div class="pull-left">
                <div class="hr hr6"></div> 
                <?php echo CHtml::encode(Yii::t('app','Cashier')); ?>
            </div>
        </div>
        <?php } ?>

        <div id="mybutton">
            <?php /* echo TbHtml::linkButton(Yii::t( 'app', 'Print' ),array(
                'color'=>TbHtml::BUTTON_OLOR_SUCCESS,
                'size'=>TbHtml::BUTTON_SIZE_SMALL,
                'icon'=>'print white',
                //'url'=>Yii::app()->createUrl('SaleItem/Index'),
                'onclick'=>'{printpage();}',
            )); */?> 
            <?php /*echo TbHtml::linkButton(Yii::t( 'app', 'Edit' ),array(
                'color'=>TbHtml::BUTTON_COLOR_SUCCESS,
                'size'=>TbHtml::BUTTON_SIZE_SMALL,
                'icon'=>'pencil white',
                'url'=>$this->createUrl('SaleItem/EditSale',array('sale_id'=>$sale_id)),
            )); */?> 
        </div>
    <?php } ?>
    
</div>

<script>
function printpage()
{
    setTimeout(window.location.href='index',500);
    window.print();
    return true;
}
//window.onload=printpage();
</script>
