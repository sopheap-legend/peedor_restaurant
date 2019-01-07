<?php $this->widget('ext.modaldlg.EModalDlg'); ?>
<div id="detailItemList">

<!--<div class="table-header">
    <?php //echo $title; ?>
</div>-->

<?php $this->widget('EExcelView', array(
    'id' => $grid_id,
    'fixedHeader' => true,
    'type' => TbHtml::GRID_TYPE_BORDERED,
    'dataProvider' => $data_provider,
    'template' => "{items}\n{exportbuttons}\n",
    'columns' => $grid_columns,
));
?>
</div>

<?php   /*Yii::app()->clientScript->registerScript( 'selectItem', "
        jQuery( function($){
            $('div#listofitem').on('click','a.list-item',function(e) {
                e.preventDefault();
                $('#myModal').modal('hide');
                var remote = $('#SaleItem_item_id');
                var url=$(this).attr('href');
                $.ajax({url:url,
                        type : 'post',
                        beforeSend: function() { $('.waiting').show(); },
                        complete: function() { $('.waiting').hide(); },
                        success : function(data) {
                            $('#register_container').html(data);
                        }
                }); 
             });
         });
      ");*/
 ?>

<?php
Yii::app()->clientScript->registerScript( 'deleteItem', "
        jQuery( function($){
            $('div#detailItemList').on('click','a.delete-item',function(e) {
                e.preventDefault();
                var url=$(this).attr('href')
                var gridCart=$('#grid_cart');
                $.ajax({url:url,
                        type : 'post',
                        beforeSend: function() { $('.waiting').show(); },
                        complete: function() { $('.waiting').hide(); },
                        success : function(data) {
                            $('#register_container').html(data);
                            $('.modal').modal('hide');
                        }
                    });
                });
        });
      ");
?>
