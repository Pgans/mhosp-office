
<?php

use kartik\grid\GridView;
?>

<?php

$gridColumns = [
	
	[
        'label' => 'วันที่',
        'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'admit_date',
       'format' => 'raw',
       'value'=> function ($model){
        return '<font class="text-primary">' . $model['admit_date'] . '</font>';
    },
    ],
    [
        'label' => 'LR',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'LR',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'Ward1',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'Ward1',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'Ward2',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'Ward2',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'Ward3',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'Ward3',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'Ward4',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'Ward4',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'HomeI',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'HI',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'รวม',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'TOTAL',
        'format' => 'raw',
        'value'=> function ($model){
            return '<span class="badge" style="background-color:#FF9900">' . $model['TOTAL'] . '</span>';
        }
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


