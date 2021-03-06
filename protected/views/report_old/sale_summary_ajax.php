<?php $this->widget('EExcelView',array(
        'id'=>'sale-summary-grid',
        'fixedHeader' => true,
        'responsiveTable' => true,
        'type'=>TbHtml::GRID_TYPE_BORDERED,
	'dataProvider'=>$report->saleSummary(),
        //'filter'=>$filtersForm,
        'summaryText' =>'<p class="text-info" align="left">' . Yii::t('app','Sales Summary') . Yii::t('app','From') . ':  ' . $from_date . '  ' . Yii::t('app','To') . ':  ' . $to_date . '</p>', 
	'template'=>"{summary}\n{items}\n{exportbuttons}\n{pager}",
        'columns'=>array(
		array('name'=>'no_of_invoice',
                      'header'=>Yii::t('app','No. of Invoices'),
                      'value'=>'$data["no_of_invoice"]',
                      'headerHtmlOptions'=>array('style' => 'text-align: right;'),
                      'htmlOptions'=>array('style' => 'text-align: right;'),
                ),
                array('name'=>'quantity',
                      'header'=>Yii::t('app','Quantity Sold'),
                      'value' =>'number_format($data["quantity"],Yii::app()->shoppingCart->getDecimalPlace(), ".", ",")',
                      'htmlOptions'=>array('style' => 'text-align: right;'),
                      'headerHtmlOptions'=>array('style' => 'text-align: right;'),
                ),
		array('name'=>'amount',
                      'header'=>Yii::t('app','Amount Sold'),
                      'value' =>'number_format($data["amount"],Yii::app()->shoppingCart->getDecimalPlace(), ".", ",")',
                      'htmlOptions'=>array('style' => 'text-align: right;'),
                      'headerHtmlOptions'=>array('style' => 'text-align: right;'),
                ),
	),
)); ?>