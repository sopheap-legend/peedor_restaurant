<?php $box = $this->beginWidget('bootstrap.widgets.TbBox', array(
              'title' => 'Sale Register',
              'headerIcon' => 'icon-globe',
              'headerButtons' => array(
                        array(
                                'class' => 'bootstrap.widgets.TbButtonGroup',
                                'type' => 'info', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                                'buttons' => array(
                                        array('label' => 'Supspended Sales', 'url' =>Yii::app()->createUrl('SaleSuspended/Admin/'),'size'=>'small',), 
                                )
                        ),
                  )
 ));?>  
<div id="itemlookup">
<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'searchForm',
    'type'=>'search',
    //'htmlOptions'=>array('class'=>'well'),
)); ?>
    
    <?php  
    $this->widget('bootstrap.widgets.TbSelect2', array(
        'asDropDownList' => false,
        'model'=> $model, 
        'attribute'=>'item_id',
        'options' => array(
                'placeholder' => 'Type item name or scan bar code',
                'multiple'=>false,
                'width' => '50%',
                //'tokenSeparators' => array(',', ' '),
                'allowClear'=>true,
                'minimumInputLength'=>1,
                'ajax' => array(
                    'url' => Yii::app()->createUrl('Item/getItem/'), 
                    'dataType' => 'json',
                    'data' => 'js:function(term,page) {
                                return {
                                    term: term, 
                                    page_limit: 10,
                                    quietMillis: 10, //How long the user has to pause their typing before sending the next request
                                    //apikey: "e5mnmyr86jzb9dhae3ksgd73" // Please create your own key!
                                };
                            }',
                    'results' => 'js:function(data,page){
                        var remote = $(this);
                        arr=data.results;
                        var more = arr.filter(function(value) { return value !== undefined }).length;
                        //console.log(more);
                        /*
                        if (more==1)
                        {
                            var item_id=0;
                            $.each(data.results, function(key,value){
                                item_id=value.id;
                            });
                            var gridCart=$("#grid_cart");
                            var totalCart=$("#total_cart");
                            var paymentCart=$("#payment_cart");
                            var cancelCart=$("#cancel_cart");
                            $.ajax({url: "Index",
                                    dataType : "json",
                                    data : {item_id : item_id},
                                    type : "post",
                                    beforeSend: function() { $(".waiting").show(); },
                                    complete: function() { $(".waiting").hide(); },
                                    success : function(data) {
                                            if (data.status==="success")
                                            {
                                                console.log($(this).attr("id"));
                                                gridCart.html(data.div_gridcart);
                                                totalCart.html(data.div_totalcart);
                                                paymentCart.show();
                                                paymentCart.html(data.div_paymentcart); 
                                                cancelCart.html(data.div_cancelcart);
                                                remote.select2("open");
                                                remote.select2("data", null);
                                                //location.reload();
                                            }
                                            else 
                                            {
                                               console.log(data.message);
                                            }
                                      }
                                });
                            }
                          */
                        return { results: data.results };
                     }',
                ),
                'initSelection' => 'js:function (element, callback) {
                       var id=$(element).val();
                       console.log(id);
                }',
                //'htmlOptions'=>array('id'=>'search_item_id'),
        )));
    ?>
    
<?php $this->endWidget(); ?>
</div>

<?php $this->endWidget(); ?>

<?php Yii::app()->clientScript->registerScript('setFocus', '$("#SaleItem_item_id").select2("open");'); ?>

<script>
function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    return (charCode<=31 ||  charCode==46 || (charCode>=48 && charCode<=57));
}
</script>

<?php 
    Yii::app()->clientScript->registerScript( 'searchItem', "
        jQuery( function($){
            $('#SaleItem_item_id').on('change', function(e) {
                e.preventDefault();
                var remote = $('#SaleItem_item_id');
                var item_id=remote.val();
                var gridCart=$('#grid_cart');
                var totalCart=$('#total_cart');
                var paymentCart=$('#payment_cart');
                var cancelCart=$('#cancel_cart');
                $.ajax({url: 'Index',
                        dataType : 'json',
                        data : {item_id : item_id},
                        type : 'post',
                        beforeSend: function() { $('.waiting').show(); },
                        complete: function() { $('.waiting').hide(); },
                        success : function(data) {
                                if (data.status==='success')
                                {
                                    gridCart.html(data.div_gridcart);
                                    totalCart.html(data.div_totalcart);
                                    paymentCart.show();
                                    paymentCart.html(data.div_paymentcart); 
                                    cancelCart.html(data.div_cancelcart);
                                    remote.select2('data', null);
                                    remote.select2('open');
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
    Yii::app()->clientScript->registerScript( 'removeCustomer', "
        jQuery( function($){
            $('#client_cart').on('click','a.detach-customer', function(e) {
                e.preventDefault();
                var clientCart=$('#client_cart');
                $.ajax({url: 'RemoveCustomer',
                        dataType : 'json',
                        type : 'post',
                        beforeSend: function() { $('.waiting').show(); },
                        complete: function() { $('.waiting').hide(); },
                        success : function(data) {
                                if (data.status==='success')
                                {
                                    clientCart.html(data.div_clientcart);
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
    Yii::app()->clientScript->registerScript( 'deleteItem', "
        jQuery( function($){
            $('div#grid_cart').on('click','a.delete-item',function(e) {
                e.preventDefault();
                var url=$(this).attr('href');
                var gridCart=$('#grid_cart');
                var totalCart=$('#total_cart');
                var paymentCart=$('#payment_cart');
                var cancelCart=$('#cancel_cart');
                $.ajax({url:url,
                        dataType : 'json',
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
                                    if (data.items==0)
                                    {
                                         $('div#payment_cart').hide();
                                    }
                                    $('#SaleItem_item_id').select2('open');
                                }
                                else 
                                {
                                  alert('something worng');
                                  return false;
                                }
                          }
                    });
                });
        });
      ");
 ?>  

<?php 
    Yii::app()->clientScript->registerScript( 'editItem', "
        jQuery( function($){
            $('div#grid_cart').on('change','input',function(e) {
                e.preventDefault();
                var frmCtlVal=$(this).val();
                var item_id=$(this).data('id');
                var quantity=$('#quantity_'+ item_id).val();
                var price=$('#price_'+ item_id).val();
                var discount=$('#discount_'+ item_id).val();
                var gridCart=$('#grid_cart');
                var totalCart=$('#total_cart');
                var paymentCart=$('#payment_cart');
                var message=$('.message');
                var alert=$('.alert');
                $.ajax({
                        url: 'EditItem',
                        dataType : 'json',
                        data : {item_id : item_id, quantity : quantity, discount : discount, price : price},
                        type : 'post',
                        beforeSend: function() { $('.waiting').show(); },
                        complete: function() { $('.waiting').hide(); },
                        success : function(data) {
                                if (data.status==='success')
                                {
                                     message.hide();
                                     gridCart.html(data.div_gridcart);
                                     totalCart.html(data.div_totalcart);
                                     paymentCart.html(data.div_paymentcart);
                                     $('#SaleItem_item_id').select2('open');
                                }    
                                else
                                {
                                    alert('someting wrong');
                                     return false;
                                }
                       }
                 });
                
                /*
                $('#line-item-form_'+ item_id).ajaxSubmit({
                        //url: 'EditItem',
                        dataType : 'json',
                        //data : {item_id : item_id, quantity : quantity, discount : discount, price : price},
                        type : 'post',
                        success : function(data) {
                                if (data.status==='success')
                                {
                                     alert('updating content..');
                                     message.hide();
                                     gridCart.html(data.div_gridcart);
                                     totalCart.html(data.div_totalcart);
                                     paymentCart.html(data.div_paymentcart);
                                     $('#SaleItem_item_id').select2('open');
                                }    
                                else
                                {
                                    alert('someting wrong');
                                     return false;
                                }
                       }
                });
                */
            });
        });
      "); 
 ?> 

<?php 
    Yii::app()->clientScript->registerScript( 'addPayment', "
        jQuery( function($){
            $('#payment_cart').on('click','a.add-payment',function(e) {
                e.preventDefault();
                var url=$(this).attr('href');
                var gridCart=$('#grid_cart');
                var totalCart=$('#total_cart');
                var paymentCart=$('#payment_cart');
                var message=$('.message');
                var payment_id=$('#payment_type_id').val();
                var payment_amount=$('#payment_amount_id').val();
                $.ajax({url:url,
                        dataType : 'json',
                        type : 'post',
                        beforeSend: function() { $('.waiting').show(); },
                        complete: function() { $('.waiting').hide(); },
                        data : {payment_id : payment_id, payment_amount : payment_amount},
                        success : function(data) {
                                if (data.status==='success')
                                {
                                    message.hide();
                                    gridCart.html(data.div_gridcart);
                                    totalCart.html(data.div_totalcart);
                                    paymentCart.html(data.div_paymentcart);
                                }
                                else 
                                {
                                   console.log(data.div);
                                }
                          }
                    });
                });
        });
      ");
 ?>  

<?php 
    Yii::app()->clientScript->registerScript( 'deletePayment', "
        jQuery( function($){
            $('#payment_cart').on('click','a.delete-payment',function(e) {
                e.preventDefault();
                var url=$(this).attr('href');
                var gridCart=$('#grid_cart');
                var totalCart=$('#total_cart');
                var paymentCart=$('#payment_cart');
                var message=$('.message');
                $.ajax({url:url,
                        dataType : 'json',
                        type : 'post',
                        beforeSend: function() { $('.waiting').show(); },
                        complete: function() { $('.waiting').hide(); },
                        success : function(data) {
                                if (data.status==='success')
                                {
                                    message.hide();
                                    gridCart.html(data.div_gridcart);
                                    totalCart.html(data.div_totalcart);
                                    paymentCart.html(data.div_paymentcart);
                                }
                                else 
                                {
                                   console.log(data.div);
                                }
                          }
                    });
                });
        });
      ");
 ?>  

<?php 
    Yii::app()->clientScript->registerScript( 'cancelSale', "
        jQuery( function($){
            $('#cancel_cart').on('click','a.cancel-sale',function(e) {
                e.preventDefault();
                if (!confirm('Are you sure you want to clear this sale? All items will cleared.'))
                {
                  return false;
                }
                var url=$(this).attr('href');
                var gridCart=$('#grid_cart');
                var totalCart=$('#total_cart');
                var paymentCart=$('#payment_cart');
                var cancelCart=$('#cancel_cart');
                var message=$('.message');
                var clientCart=$('#client_cart');
                $.ajax({url:url,
                        dataType : 'json',
                        type : 'post',
                        beforeSend: function() { $('.waiting').show(); },
                        complete: function() { $('.waiting').hide(); },
                        success : function(data) {
                                if (data.status==='success')
                                {
                                    message.hide();
                                    gridCart.html(data.div_gridcart);
                                    totalCart.html(data.div_totalcart);
                                    paymentCart.html(data.div_paymentcart);
                                    cancelCart.html(data.div_cancelcart);
                                    clientCart.html(data.div_clientcart);
                                }
                                else 
                                {
                                   console.log(data.div);
                                }
                          }
                    });
                });
        });
      ");
 ?>  <?php 
    Yii::app()->clientScript->registerScript( 'setComment', "
        jQuery( function($){
            $('div#comment_content').on('change','#comment_id',function(e) {
                e.preventDefault();
                var comment=$(this).val();
                $.ajax({
                        url: 'SetComment',
                        dataType : 'json',
                        data : {comment : comment},
                        type : 'post',
                        beforeSend: function() { $('.waiting').show(); },
                        complete: function() { $('.waiting').hide(); },
                        success : function(data) {
                                if (data.status==='success')
                                {
                                    console.log('comment saved');
                                    
                                }    
                                else
                                {
                                    alert('someting wrong');
                                    return false;
                                }
                       }
                 });
            });
        });
      "); 
 ?> 

<?php 
    Yii::app()->clientScript->registerScript( 'completeSale', "
        jQuery( function($){
            $('#payment_cart').on('click','a.complete-sale',function(e) {
                e.preventDefault();
                if (!confirm('Are you sure you want to submit this sale? This cannot be undone.'))
                {
                  return false;
                }
                var url=$(this).attr('href');
                var message=$('.message');
                $.ajax({url:url,
                        dataType : 'json',
                        type : 'post',
                        beforeSend: function() { $('.waiting').show(); },
                        complete: function() { $('.waiting').hide(); },
                        success : function(data) {
                                if (data.status==='success')
                                {
                                    window.location.href=data.div_receipt;
                                }
                                else if (data.status ==='failed')
                                {
                                    message.slideToggle();
                                    message.html(data.message);
                                    message.show();
                                    return false;
                                }
                          }
                    });
                });
        });
      ");
 ?>  


<?php 
    Yii::app()->clientScript->registerScript( 'SuspendedSale', "
        jQuery( function($){
            $('#cancel_cart').on('click','a.suspend-sale',function(e) {
                e.preventDefault();
                if (!confirm('Are you sure you want to suspend this sale?'))
                {
                  return false;
                }
                var url=$(this).attr('href');
                var message=$('.message');
                var remote = $('#SaleItem_item_id');
                var gridCart=$('#grid_cart');
                var totalCart=$('#total_cart');
                var paymentCart=$('#payment_cart');
                var cancelCart=$('#cancel_cart');
                var clientCart=$('#client_cart');
                $.ajax({url:url,
                        dataType : 'json',
                        type : 'post',
                        beforeSend: function() { $('.waiting').show(); },
                        complete: function() { $('.waiting').hide(); },
                        success : function(data) {
                                if (data.status==='success')
                                {
                                    message.hide();
                                    gridCart.html(data.div_gridcart);
                                    totalCart.html(data.div_totalcart);
                                    paymentCart.html(data.div_paymentcart);
                                    cancelCart.html(data.div_cancelcart);
                                    clientCart.html(data.div_clientcart);
                                    remote.select2('data', null);
                                    remote.select2('open');
                                }
                                else if (data.status ==='failed')
                                {
                                    message.slideToggle();
                                    message.html(data.message);
                                    message.show();
                                    return false;
                                }
                          }
                    });
                });
        });
      ");
 ?>  

<?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.validate.min.js', CClientScript::POS_END); ?>