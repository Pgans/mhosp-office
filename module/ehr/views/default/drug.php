
<?php

use kartik\grid\GridView;
?>

<?php

$gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],
        [
        'attribute' => 'DNAME',
        'format'=>'raw',
        'label' => 'รายการ',
        'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
        'value' => function ($model, $key, $index, $widget) {
                return "<font  color='2E86C1'>" . $model['DNAME'] . "</font>"; 
        },
        'pageSummary' => 'รวมทั้งหมด',
    ],
	[
        'attribute' => 'AMOUNT',
        'label' => 'จำนวน',
        'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
        'format' => ['decimal', 0],
        'hAlign' => 'right',
        'pageSummary' => true,
        'pageSummaryOptions' => ['id' => 'total_sum'],
    ],
	[
    'attribute'=>'USAGE_LINE1',
    'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
	'label'=>'การใช้ยา'
	],
	[
    'attribute'=>'USAGE_LINE2',
    'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
	'label'=>'วิธีการใช้ยา'
	],
        
     /*   [
        'attribute' => 'DRUGPRICE',
        'label' => 'ราคา/หน่วย',
        'format' => ['decimal', 2],
        'hAlign' => 'right',
        'pageSummary' => true,
        'pageSummaryOptions' => ['id' => 'total_sum'],
    ],
        [
        'attribute' => 'tprice',
        'label' => 'ราคารวม',
        'format' => ['decimal', 2],
        'hAlign' => 'right',
        'pageSummary' => true,
        'pageSummaryOptions' => ['id' => 'total_sum'],
    ],*/
];

echo GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'autoXlFormat' => true,
    'export' => [
        'fontAwesome' => true,
        'showConfirmAlert' => false,
        'target' => GridView::TARGET_BLANK
    ],
    'columns' => $gridColumns,
    'resizableColumns' => true,
     'showPageSummary' => true,
        //'resizeStorageKey' => Yii::$app->user->id . '-' . date("m"),
]);
?>