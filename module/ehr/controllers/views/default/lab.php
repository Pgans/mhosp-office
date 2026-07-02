
<?php

use kartik\grid\GridView;
?>

<?php

$gridColumns = [
	[
            'attribute' => 'HOSPCODE',
            'label' => รหัสรพ.'
        ],
       [
            'attribute' => 'DATE_SERV',
            'label' => 'วันตรวจ'
        ],
	[
            'attribute' => 'TIME_SERV',
            'label' => 'เวลาตรวจ'
        ],
            [
            'attribute' => 'LAB_NAME',
            'label' => 'ชื่อรายการ'
        ],
            [
            'attribute' => 'LAB_RESULT',
            'label' => 'ผลการตรวจ'
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