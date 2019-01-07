<style>
 .label {
    border-radius: 0.25em;
    color: #FFFFFF;
    display: inline;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    padding: 0.2em 0.6em 0.3em;
    text-align: center;
    vertical-align: baseline;
    white-space: nowrap;
}   
</style>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'client-selected-form',
        'enableAjaxValidation'=>false,
        'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
)); ?>
 
         <?php //echo TbHtml::labelTb(ucwords($customer) . ' - ' . $customer_mobile_no, array('color' => TbHtml::LABEL_COLOR_INFO)); ?>

        <?php //echo TbHtml::badge(TbHtml::lead(ucwords($customer) . ' - ' . $customer_mobile_no),array('color' => TbHtml::BADGE_COLOR_INFO)); ?>

        <?php //echo TbHtml::badge(TbHtml::lead(ucwords($customer)),array('color' => TbHtml::BADGE_COLOR_INFO)); ?>
        <!-- <h1 style="font-size:30px;background-color:#3a87ad;color:white;border-radius: 10px"> -->
        <h1><span class="label label-primary"><?php echo ucwords($customer); ?></span>
            <?php echo TbHtml::linkButton(Yii::t( 'app', '' ),array(
                'color'=>TbHtml::BUTTON_COLOR_WARNING,
                'size'=>TbHtml::BUTTON_SIZE_MINI,
                'icon'=>'glyphicon-remove white',
                'class'=>'btn btn-app detach-customer',
            )); ?>
        </h1>
    
        <?php if (PriceTier::model()->checkExists()<>0) { ?>
            <p>
                <?php echo $form->dropDownListControlGroup($model,'tier_id', PriceTier::model()->getPriceTier(),array('id'=>'price_tier_id',
                    'options'=>array(Yii::app()->shoppingCart->getPriceTier()=>array('selected'=>true)),
                    'class'=>'col-xs-10 col-sm-8','empty'=>'None','labelOptions'=>array('label'=>Yii::t('app','Item Tier')))); ?>

            </p>
        <?php } ?>
        
<?php $this->endWidget(); ?>
