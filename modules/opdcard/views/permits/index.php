<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\editable\Editable;
use yii\widgets\Pjax;
use app\models\Status;
use app\models\Treatments;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\models\SearchFormx;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\opdcard\models\PermitsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'การยืมเวชระเบียนผู้ป่วยนอกและผู้ป่วยใน';
//$this->params['breadcrumbs'][] = $this->title;
// Register JS script to handle modal
$this->registerJs('
    $("#search-modal-btn").on("click", function(){
        $("#search-modal").modal("show").find(".modal-content").load($(this).attr("data-url"));
    });
');
?>

<div class="permits-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
     <div class="well">
    <p>
        <?= Html::button('<i class="fa fa-plus" aria-hidden="true"></i> เพิ่มการยืมเวชระเบียน', [
    'value' => Url::to(['permits/create']),
    'class' => 'btn btn-success',
    'id' => 'modalButton'
]) ?>

        
        <?= Html::button('<a>****กำหนดการส่งคืนเวชระเบียนภายใน 7 วัน****</a>', ['class' => 'btn btn-defualt' ]) ?>
		<?= Html::button('กรณีเกิน 7 วันจะมีการติดตามโดยแจ้งเตือนในไลน์กลุ่มสิทธิบัตร', ['class' => 'btn btn-danger']) ?>

    </p>
    <div class="well" style="border: 2px solid #a5d1d1; background-color: #B7E6C6;">

    <?php
    $form = ActiveForm::begin([
    'method' => 'post',
    'action' => ['permits/index'], // Adjust the action as needed
]);

echo '<div class="row">'; // Start a new row

echo '<div class="col-md-5">'; // Create a column for the text input
echo $form->field($searchModel, 'an')->textInput(['placeholder' => 'an']);
echo '</div>';

echo '<div class="col-md-2">'; 
echo Html::submitButton('<i class="glyphicon glyphicon-search"></i> ค้นหา', ['class' => 'btn btn-primary', 'style' => 'margin-top: 25px;']); 
echo '</div>';

echo '</div>'; // End the row

ActiveForm::end();

if (!empty($data)) {
    echo '<h3 style="color: green;">แสดงผลการค้นหา</h3>';
		//echo '<pre>';
	   //   print_r($data);
	   // echo '</pre>';

    echo GridView::widget([
    'dataProvider' => new \yii\data\ArrayDataProvider([
        'allModels' => [$data], // นี่คือข้อมูลที่ให้มา
    ]),
	'showFooter' => false, // ปิดการแสดง footer
	'options' => [
        'style' => 'color: green;', // กำหนดสีข้อความเป็นสีเขียว
    ],
    'columns' => [
        'hn',
        'an',
        'fname',
        'lname',
		'unit_name',
		'adm_dt',
		'dsc_dt',
        'บ้าน',
        'ตำบล',
        'อำเภอ',
        'จังหวัด',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{save}',
            'buttons' => [
                'save' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-floppy-disk"></span>', ['save-request', 'an' => $model['an'], 'hn' => $model['hn'], 'fname' => $model['fname'] , 'lname' => $model['lname']], [
                        'title' => 'บันทึกข้อมูล',
                       // 'data-confirm' => 'คุณต้องการบันทึกข้อมูล?',
                    ]);
                },
            ],
        ],
    ],
]);
}
?>
</div>
  
    <?php Modal::begin([
        'id' => 'modal',
        'header' => '<h4><a color-blue>CREATE PERMITS</a></h4>',
        'size'=>'modal-lg',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">ปิด</a>',
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
        ?> 
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
       // 'pjax'=>true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			
			[
                'class' => 'kartik\grid\EditableColumn',
				'attribute'=>'AN',
				'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
				'format' =>'raw',
				'label'=>'AN',
				'contentOptions' => ['style' => 'max-width:20px;'],
				'value'=> function ($model){
                    return '<font class="text-defualt">' . $model['AN'] . '</font>';
					// return '<span class="badge" style="background-color:primary">' . $model['AN'] . '</span>';
				}
			],
            // [
            //     'label'=>'เปิดแท็บใหม่',
            //     'format'=>'raw',
            //     'value'=>function($model){
            //       return Html::a('DEMO',['/site/index'],['target'=>'_blank',"data-pjax"=>"0"]);
            //     }
            //   ],
          //....
            
            /*
            [
                'class' => 'kartik\grid\EditableColumn',
                'header' => 'บน',
                'attribute' => 'bon_id',
                'value' => function($model) {
                return $model->bon->bon_price;
                },
                'pageSummary' => true,
                ],
                */
			[
                'class' => 'kartik\grid\EditableColumn',
				'attribute'=>'HN',
				'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
				'format'=>'raw',
				'contentOptions' => ['style' => 'max-width:20px;'],
				'value'=> function ($model){
					return '<font class="text-success">' . $model['HN'] . '</font>';
				},
                'pageSummary' => true,
			],
            [    
                'class' => 'kartik\grid\EditableColumn',
                        'attribute' => 'fullname',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       'format'=>'raw', 
			],
            
            [    
                        'attribute' => 'treatments.treatment_name',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       'format'=>'raw', 
			],
			[    
                        'attribute' => 'createdBy.firstname',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       'format'=>'raw', 
			],
			[    
                        'attribute' => 'createdBy.lastname',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       'format'=>'raw', 
			],
			[    
                        'attribute' => 'created_at',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       'format'=>'raw',
               
			],
                          
			[
                //'class'=>'kartik\grid\EditableColumn',
                'attribute' => 'status.status',
				'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                'format' => 'raw',
                'label' => 'สถานะ',
                'value' => function ($model) {
                    return '<span class="badge" style="background-color:' . $model->status->color . '">' . $model->status->status . '</span>';
                    
                },
            ],

           // 'id',
            //'AN',
            //'HN',
            //'fullname',
            //'treatments.treatment_name',
            // 'createdBy.firstname',
            // 'createdBy.lastname',
             //'created_at',
            // 'day_want',
             //'updater.firstname',
             //'updated_at',
            // 'status.status',

           // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end() ?>
</div>


<!-- <?php
$this->registerJsFile('@web/js/main.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?> -->
<?php
  $this->registerJs("$(function() {
   $('#modalButton').click(function(e) {
     e.preventDefault();
     $('#modal').modal('show').find('.modal-content')
     .load($(this).attr('value'));
   });
});");
?>

<?php
Modal::begin([
    'id' => 'view-modal',
    'size' => 'modal-lg',
    'header' => '<h4>View Details</h4>',
]);

echo '<div class="modal-content" id="view-modal-content"></div>';

Modal::end();

// Modal for Edit
Modal::begin([
    'id' => 'edit-modal',
    'size' => 'modal-lg',
    'header' => '<h4>Edit Details</h4>',
]);

echo '<div class="modal-content" id="edit-modal-content"></div>';

Modal::end();
Modal::begin([
    'id' => 'search-modal',
    'size' => 'modal-lg',
    'header' => '
        <div class="modal-header">
            <h4 class="modal-title">Search</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
    ',
    'footer' => '<div style="display:none;"></div>', // Hide the footer
    'closeButton' => false, // Hide the default close button
]);
echo "<div id='search-modal-content'></div>";
Modal::end();
?>

</div>

