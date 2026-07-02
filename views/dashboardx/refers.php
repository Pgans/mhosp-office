
<?php

use kartik\grid\GridView;
?>

<?php

$gridColumns = [
	
    [
        'label' => 'ReferDate',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'referdate',
        //'format' => ['decimal', 0]
    ],
	[
        'label' => 'HN',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'hn',
       // 'format' => ['decimal', 0]
    ],
	[
        'label' => 'AN',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'an',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'Name',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'first_name',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'Last_name',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'last_name',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'Refer_no',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'referno',
       // 'format' => ['decimal', 0]
    ],
    
	[
        'label' => 'days',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'days',
        'format' => ['decimal', 1]
    ],
    [
        'label' => 'Times',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'times',
       // 'format' => ['decimal', 0]
    ],
    [
        'label' => 'hospid',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'to_hcode',
        'format' => 'raw',
        'value'=> function ($model){
            return '<span class="badge" style="background-color:#FF9900">' . $model['to_hcode'] . '</span>';
        }
    ],
	
	[
        'label' => 'Hospname',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'to_hcode_name',
       // 'format' => ['decimal', 0]
    ],
	[
        'label' => 'refer_cause',
        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
        'attribute' => 'refer_cause',
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


