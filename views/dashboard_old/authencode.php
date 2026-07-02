
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
            'label' => 'ARI OP',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'attribute' => 'ARI OP',
           // 'format' => ['decimal', 0]
        ],
        [
            'label' => 'ARI HI',
            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
            'attribute' => 'ARI HI',
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


