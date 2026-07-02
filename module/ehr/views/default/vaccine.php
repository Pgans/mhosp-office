
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
            'attribute' => 'PID',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
           // 'label' => 'เลขHN',
        ],
		[
            'attribute' => 'SEQ',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
            //'label' => 'เลขHN',
        ],
		[
            'attribute' => 'VACCINETYPE',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
            'label' => 'รหัสวัคซีน',
        ],
		/*[
            'attribute' => 'VACCINEPLACE',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
            'label' => 'xxx',
        ],*/
		[
            'attribute' => 'name_english',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
            'label' => 'ชื่ออังกฤษ',
        ],
		[
            'attribute' => 'name_thai',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
            'label' => 'ชื่อไทย',
        ],
		[
            'attribute' => 'category',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
            'label' => 'ประเภท',
        ],
		[
            'attribute' => 'Diag',
            'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
            'label' => 'โรครักษา',
        ],
       /*
		[
        'attribute' => 'VACCINEPLACE',
        'format'=>'raw',
        'label' => 'รายการ',
        'headerOptions'=>[ 'style'=>'background-color:#FEF5E7'],
        'value' => function ($model, $key, $index, $widget) {
                return "<font  color='2E86C1'>" . $model['VACCINEPLACE'] . "</font>"; 
        },
        'pageSummary' => 'รวมทั้งหมด',
    ],
		*/
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