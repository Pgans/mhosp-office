<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\helpers\Url;



/* @var $this yii\web\View */
/* @var $searchModel app\models\RequestHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'ขอประวัติการรักษา');
$this->params['breadcrumbs'][] = $this->title;

// Register JS script to handle modal
$this->registerJs('
    $("#search-modal-btn").on("click", function(){
        $("#search-modal").modal("show").find(".modal-content").load($(this).attr("data-url"));
    });
');

?>
<!--<div class="well" style="border: 2px solid #a5d1d1;">-->
<div class="well" style="border: 2px solid #a5d1d1; background: linear-gradient(to right, #B7E6C6, #CEF3DF); box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2); border-radius: 10px; padding: 20px;">
<h4><a>ระบบการขอประวัติการรักษาผู้ป่วยนอกและผู้ป่วยใน  โรงพยาบาลม่วงสามสิบ อุบลราชธานี   </a>  </h4>
   
   <?php

$form = ActiveForm::begin([
    'method' => 'post',
    'action' => ['requesxthistory/index'], // Adjust the action as needed
]);

echo '<div class="row">'; // Start a new row

echo '<div class="col-md-3">'; // Create a column for the CID input
echo $form->field($searchModel, 'cid')->textInput(['placeholder' => 'CID']);
echo '</div>';
/*
echo '<div class="col-md-3">'; // Create a column for the start date input
echo $form->field($searchModel, 'start_date')->input('date', ['placeholder' => 'Start Date']);
echo '</div>';

echo '<div class="col-md-3">'; // Create a column for the end date input
echo $form->field($searchModel, 'end_date')->input('date', ['placeholder' => 'End Date']);
echo '</div>';

echo '<div class="col-md-2">'; // Create a column for the year radio buttons
echo $form->field($searchModel, 'orther')->radioList([
    1 => '1 ปี',
    2 => '2 ปี',
    3 => '3 ปี'
]);
echo '</div>';

*/
echo Html::submitButton('<i class="glyphicon glyphicon-search"></i> ค้นหา', ['class' => 'btn btn-primary', 'style' => 'margin-top: 25px;']); // Add margin-top for better alignment
echo '</div>';


ActiveForm::end();


    if (!empty($data)) {
       echo '<h3 style="color: green;">แสดงผลการค้นหา</h3>';
        echo GridView::widget([
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => [$data],
            ]),
			'options' => [
    'style' => Yii::$app->user->isGuest ? 'background-color: #ffecec;' : 'background-color: #efece7; color: green;',
],

            'columns' => [
                'hn',
                'cid',
                'fname',
                'lname',
                'birthdate',
                'sex',
                'tel',
                'บ้าน',
                'ตำบล',
                'อำเภอ',
                'จังหวัด',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{save}',
                    'buttons' => [
                        'save' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-floppy-disk"></span>', ['save-request', 'cid' => $model['cid'], 'hn' => $model['hn'], 'fname' => $model['fname'], 'lname' => $model['lname']], [
                                'title' => 'บันทึกข้อมูล',
                                'data-confirm' => 'คุณต้องการบันทึกข้อมูล?',
                            ]);
                        },
                    ],
                ],
            ],
        ]);
    }
    ?>
</div>
<!-- <div class="equest-history-index">
    <div class="box box-primary box-solid">
        <div class="box-header" id="grad1">
            <h3 class="box-title"><i class="fa fa-file-text-o"></i> <?= Html::encode($this->title) ?> (รหัสผู้ใช้งาน : <?= Html::encode(Yii::$app->user->identity->username) ?>)</h3>
        </div>
        <div class="box-body"> -->

<div class="well" style="border: 2px solid #8dc5c5; border-radius: 10px; padding: 15px;">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'showPager' => false,   
        'options' => [
            'style' => Yii::$app->user->isGuest ? 'background-color: #ffecec;' : 'background-color: #efece7;',
        ],
        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'no',
                'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
              //  'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'format' => 'raw',

            ],
            // 'cid',
            [
                'attribute' => 'hn',
                'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
              //  'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'format' => 'raw',
            ],
            [
                'attribute' => 'fullname',
                'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
              //  'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'format' => 'raw',

            ],
            [
                'attribute' => 'assemble.assemble_name',
                'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
              //  'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'format' => 'raw',

            ],
            [
                'attribute' => 'created_by',
               // 'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'label' => 'ผู้บันทึก',
                'value' => function ($model) {
                    return $model->createdBy ? $model->createdBy->firstname . ' ' . $model->createdBy->lastname : '';
                },
            ],
            [
                'attribute' => 'created_at',
                'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
              //  'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'format' => 'raw',

            ],
            [
                'attribute' => 'updated_by',
               // 'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'label' => 'ผู้พิมพ์',
                'value' => function ($model) {
                    return $model->updater ? $model->updater->firstname . ' ' . $model->updater->lastname : '';
                },
            ],
            [
                'attribute' => 'updated_at',
                'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
               // 'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'format' => 'raw',

            ],
            
            [
                'attribute' => 'start_date',
                'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
              //  'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'format' => 'raw',

            ],
			[
                'attribute' => 'end_date',
                'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
              //  'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'format' => 'raw',

            ],
			[
                'attribute' => 'orther',
                'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
              //  'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'format' => 'raw',

            ],
			 [
            'label' => 'ประกันเวลา',
            'value' => function ($model) {
                if ($model->created_at && $model->created_at) {
                    $repairAt = new DateTime($model->updated_at);
                    $sendAt = new DateTime($model->created_at);
                    $interval = $repairAt->diff($sendAt);
                    return $interval->format('%d วัน %h ชั่วโมง %i นาที');
                }
                return null;
            },
        ],
            [
                //'class'=>'kartik\grid\EditableColumn',
                'attribute' => 'status.status',
               // 'headerOptions' => ['style' => 'background-color:#a4e7df'],
                'format' => 'raw',
                'label' => 'สถานะ',
                'value' => function ($model) {
                    return '<span class="badge" style="background-color:' . $model->status->color . '">' . $model->status->status . '</span>';
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>',
                            '#',
                            [
                                'title' => Yii::t('yii', 'View'),
                                'data-toggle' => 'modal',
                                'data-target' => '#view-modal',
                                'onclick' => "$('#view-modal .modal-body').load('" . Url::to(['requesxthistory/view', 'id' => $model->id]) . "')",
                            ]
                        );
                    },
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            '#',
                            [
                                'title' => Yii::t('yii', 'Update'),
                                'data-toggle' => 'modal',
                                'data-target' => '#edit-modal',
                                'onclick' => "$('#edit-modal .modal-body').load('" . Url::to(['requesxthistory/update', 'id' => $model->id]) . "')",
                            ]
                        );
                    },
                ],
            ],
        ],

    ]); ?>
</div>

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