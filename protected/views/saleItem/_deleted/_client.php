<!-- section:custom/widget-main -->
<?php $this->widget( 'ext.modaldlg.EModalDlg' ); ?> 
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'client-form',
        'enableAjaxValidation'=>false,
        'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
)); ?>

	<?php //echo $form->textFieldRow($model,'item_id',array('class'=>'span8','maxlength'=>100)); ?>
        
        <p>
            
        <?php  
            $this->widget('yiiwheels.widgets.select2.WhSelect2', array(
                'asDropDownList' => false,
                'model'=> $model, 
                'attribute'=>'client_id',
                'pluginOptions' => array(
                        'placeholder' => Yii::t('app','form.sale.client_hint'),
                        'multiple'=>false,
                        'width' => '65%',
                        'tokenSeparators' => array(',', ' '),
                        'allowClear'=>false,
                        //'minimumInputLength'=>2,
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('Client/getClient/'), 
                            'dataType' => 'json',
                            'data' => 'js:function(term,page) {
                                        return {
                                            term: term, 
                                            page_limit: 10,
                                            quietMillis: 10,
                                            apikey: "e5mnmyr86jzb9dhae3ksgd73" // Please create your own key!
                                        };
                                    }',
                            'results' => 'js:function(data,page){
                                return {results: data.results};
                            }',
                        ),
                )));
          ?>

          <?php echo TbHtml::linkButton(Yii::t( 'app', 'New' ),array(
                'color'=>TbHtml::BUTTON_COLOR_INFO,
                'size'=>TbHtml::BUTTON_SIZE_SMALL,
                'icon'=>'glyphicon-plus white',
                'url'=>$this->createUrl('Client/AddCustomer'), //$this->createUrl('Client/Create/',array('sale_mode'=>'Y')),
                'class'=>'update-dialog-open-link',
                'data-update-dialog-title' => Yii::t( 'app', 'form.client._form.header_create' ),
            )); ?> 
        </p>
       
        <?php if (PriceTier::model()->checkExists()<>0) { ?>
            <p>
                <?php echo $form->dropDownListControlGroup($model,'tier_id', PriceTier::model()->getPriceTier(),array('id'=>'price_tier_id',
                    'options'=>array(Yii::app()->shoppingCart->getPriceTier()=>array('selected'=>true)),
                    'class'=>'col-xs-10 col-sm-8','empty'=>'None','labelOptions'=>array('label'=>Yii::t('app','Item Tier')))); ?>

            </p>
        <?php } ?>
      
<?php $this->endWidget(); ?>

<?php 
    Yii::app()->clientScript->registerScript( 'selectCustomer', "
        jQuery( function($){
            $('#SaleItem_client_id').on('change', function(e) {
                e.preventDefault();
                var remote = $('#SaleItem_client_id');
                var customer_id=remote.val();
                var gridCart=$('#grid_cart');
                var totalCart=$('#total_cart');
                var paymentCart=$('#payment_cart');
                var cancelCart=$('#cancel_cart');
                var clientCart=$('#client_cart');
                $.ajax({url: 'SelectCustomer',
                        dataType : 'json',
                        data : {customer_id : customer_id},
                        type : 'post',
                        beforeSend: function() { $('.waiting').show(); },
                        complete: function() { $('.waiting').hide(); },
                        success : function(data) {
                                if (data.status==='success')
                                {
                                    gridCart.html(data.div_gridcart);
                                    totalCart.html(data.div_totalcart);
                                    paymentCart.html(data.div_paymentcart);
                                    cancelCart.html(data.div_cancelcart);
                                    clientCart.html(data.div_clientcart);
                                    remote.select2('data', null);
                                }
                                else 
                                {
                                   console.log(data.message);
                                   
                                }
                          }
                    });
                });
        });
      ");
 ?> 

<?php 
    Yii::app()->clientScript->registerScript( 'priceTierOption', "
        jQuery( function($){
            $('div#client_cart').on('change','#price_tier_id',function(e) {
                e.preventDefault();
                var pricetierId=$(this).val();
                var gridCart=$('#grid_cart');
                var paymentCart=$('#payment_cart');
                var cancelCart=$('#cancel_cart');
                var clientCart=$('#client_cart');
                $.ajax({url: 'SetPriceTier',
                        dataType : 'json',
                        data : {price_tier_id : pricetierId},
                        type : 'post',
                        beforeSend: function() { $('.waiting').show(); },
                        complete: function() { $('.waiting').hide(); },
                        success : function(data) {
                            if (data.status==='success')
                            {
                                gridCart.html(data.div_gridcart);
                                paymentCart.html(data.div_paymentcart);   
                            }
                            else 
                            {
                               console.log(data.message);

                            }
                        }
                    });
                });
        });
      "); 
 ?> 
