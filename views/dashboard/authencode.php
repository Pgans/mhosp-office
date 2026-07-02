
<?php

use kartik\grid\GridView;
?>

<?php

$gridColumns = [
	
	[
            'attribute' => 'regdate',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'], 
            'label' => 'วันที่'
        ],
		[
            'attribute' => 'ตรวจโรค',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'], 
            'label' => 'ตรวจโรค'
        ],
        [
            'attribute' => 'กายภาพ',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'], 
            'label' => 'กายภาพ'
        ],
       	[
            'attribute' => 'ไตเทียม',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'], 
            'label' => 'ไตเทียม'
        ],
        [
            'label' => 'แผนไทย',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'attribute' => 'planthai',
           // 'format' => ['decimal', 0]
        ],
        [
            'label' => 'ans-us',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'attribute' => 'anc',
           // 'format' => ['decimal', 0]
        ],
		[
            'label' => 'ทันตกรรม',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'attribute' => 'dental',
           // 'format' => ['decimal', 0]
        ],
		[
            'label' => 'จิตเวช',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'attribute' => 'จิตเวช',
           // 'format' => ['decimal', 0]
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


