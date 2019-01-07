<div class="wide form">
    <?php $form = $this->beginWidget('\TbActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'layout' => TbHtml::FORM_LAYOUT_SEARCH,
    )); ?>

    <span class="input-icon">
        <?php echo CHtml::activeTelField($model,'zone_name', array('class' => 'col-xs-12 col-sm-12','placeholder' => Yii::t('app','Search'))); ?>
        <i class="ace-icon fa fa-search nav-search-icon"></i>
    </span>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->