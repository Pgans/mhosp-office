
<?php

use kartik\grid\GridView;
?>

<?php

$gridColumns = [
	
    [
        'label' => 'วันที่',
        'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'send_date',
       'format' => 'raw',
       'value'=> function ($model){
        return '<font class="text-primary">' . $model['send_date'] . '</font>';
    },
    ],
      
    [
        'label' => 'จำนวนส่งผ่าน',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'amount',
        'format' => 'raw',
        'value'=> function ($model){
            return '<span class="btn btn-xs btn-success" style="background-color:#FF00FF">' . $model['amount'] . '</span>';
        }
    ],

//'layout' => '{items}{pager}',
    
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


