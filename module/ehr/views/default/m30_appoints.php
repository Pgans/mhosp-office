
<?php

use kartik\grid\GridView;
?>

<?php

$gridColumns = [
	
       	[
            'attribute' => 'date_serv',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'], 
            'label' => 'วันตรวจ'
        ],
		[
            'attribute' => 'ap_date',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'], 
            'label' => 'นัดครั้งต่อไป'
        ],
		[
            'attribute' => 'ap_memo',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'], 
            'label' => 'ข้อมูลการนัด'
        ],
        [
            'attribute' => 'staff_name',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'], 
            'label' => 'แพทย์ที่นัด'
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