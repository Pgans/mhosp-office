
<?php

use kartik\grid\GridView;
?>

<?php

$gridColumns = [
	
	[
        'label' => 'วันที่',
        'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 6vw; overflow: hidden;'],
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'V_DATETIME',
       'format' => 'raw',
       'value'=> function ($model){
        return '<font class="text-primary">' . $model['V_DATETIME'] . '</font>';
    },
    ],
    [
        'label' => 'VISIT_ID',
		'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 5vw; overflow: hidden;'],
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'VISIT_ID',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'HN',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'hn',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'BP_DIAS',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'BP_DIAS',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'BP_AUTO',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'bpd',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'BP_SYST',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'BP_SYST',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'BPS_AUTO',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'bps',
       // 'format' => ['decimal', 0]
    ],
	[
        'label' => 'B_TEMP',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'BODY_TEMP',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'B_AUTO',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'temperature',
       // 'format' => ['decimal', 0]
    ],
	[
        'label' => 'P_RATE',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'PULSE_RATE',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'P_AUTO',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'pulse',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'STAFF_ID',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'STAFF_ID',
        'format' => 'raw',
        'value'=> function ($model){
            return '<span class="btn btn-success">' . $model['STAFF_ID'] . '</span>';
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


