
<?php

use kartik\grid\GridView;
?>

<?php

$gridColumns = [
	
	[
        'label' => 'วันที่',
        'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'refer_date',
       'format' => 'raw',
       'value'=> function ($model){
        return '<font class="text-primary">' . $model['refer_date'] . '</font>';
    },
    ],
    [
        'label' => 'สรรสิทธิประสงค์(10669)',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'สรรสิทธิประสงค์(10669)',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => '50 พรรษา(21984)',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => '50-พรรษา(21984)',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'พระศรีมหาโพธิ์(12269)',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'พระศรีมหาโพธิ์(12269)',
       // 'format' => ['decimal', 0]
    ],
    
    [
        'label' => 'รวม',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'รวม',
        'format' => 'raw',
        'value'=> function ($model){
            return '<span class="btn btn-warning">' . $model['รวม'] . '</span>';
        }
    ],
    //'layout'=>'{items}{pager}',
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


