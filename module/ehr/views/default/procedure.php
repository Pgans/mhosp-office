
<?php

use kartik\grid\GridView;
?>

<?php

$gridColumns = [
	
	[
            'attribute' => 'HOSPCODE',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
            'label' => 'สถาน'
        ],
       	[
            'attribute' => 'DATE_SERV',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
            'label' => 'เวลาตรวจ',
        ],
        [
            'attribute' => 'PROCEDCODE',
            'format'=>'raw',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
            'value' => function ($model, $key, $index, $widget) {
                return "<font  color='2E86C1'>" . $model['PROCEDCODE'] . "</font>"; 
        },
            'label' => 'รหัสหัตการ'
        ],
        [
            'attribute' => 'NAME',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
            'label' => 'ชื่อหัตถการ'
        ],
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
        //'resizeStorageKey' => Yii::$app->user->id . '-' . date("m"),
]);
?>