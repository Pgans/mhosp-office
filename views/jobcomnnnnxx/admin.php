<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Jobstatus;
use app\models\departmentjob;
use kartik\widgets\Select2;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel app\models\JobcomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'ระบบแจ้งซ่อมคอมพิวเตอร์';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary box-solid">
<div class ="box-header" id="grad11">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="well">
<div class="jobcom-index">
    <p>
        <?= Html::a('เพิ่มแจ้งซ่อมคอมพิวเตอร์', ['create'], ['class' => 'btn btn-lg btn-success']) ?>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i>เพิ่มแจ้งซ่อม', ['value' => Url::to(['jobcom/create']), 'class' => 'btn btn-success','id'=>'modalButton']); ?>
    </p>
    <?php Modal::begin([
        'id' => 'modal',
        'header' => '<h4>เพิ่มการแจ้งซ่อมคอมพิวเตอร์</h4>',
        'size'=>'modal-lg',
        'footer' => '<a href="#" class="btn btn-danger" data-dismiss="modal">ปิด</a>',
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
    ?>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'tableOptions' => [
            'class' => 'table table-striped',
                ],
                'options' => [
                    'class' => 'table-responsive',
                ],
        'dataProvider' => $dataProvider,
       'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'id', 
                    'filter' => true
                ],
                [
                    'attribute' => 'detail', 
                    'filter' => false
                ],
                
                [
                    'attribute' => 'dateline', 
                    'filter' => false
                ],
                [
                    'attribute' => 'send_by', 
                    'format'=>'raw',
                    'label'=>'ผู้แจ้ง',
                    'value'=> function ($model) {
                        return "<font class='text-success'>" . $model->send_by . '</font> ';  
                    },
                    'filter' => false
                ],
    
                [
                    'attribute' => 'send_at', 
                    'format'=>'raw',
                    'label'=>'วันที่แจ้ง',
                    'value' => function ($model)  {
                        return '<font class="text-warning">' .$model->send_at.'</font>';
                    },
                    'filter' => false
                ],
                [
                    'attribute' => 'updater.firstname', 
                    'filter' => false
                ],
    
                // [
                //     'attribute' => 'updater.lastname', 
                //     'filter' => false
                // ],
                // [
                //     'attribute' => 'dep_id',
                //     'format'=>'raw',
                //     'label' => 'แผนกที่แจ้ง',
                //     'value' => function($model) {
                //             return empty($model->department) ? null : $model->department->dep_name;
                //      },
                //     'filter' => ArrayHelper::map(departmentjob::find()->asArray()->all(), 'dep_id', 'dep_name'),
                //     'filterType' => GridView::FILTER_SELECT2,
                //     'filterWidgetOptions' => [
                //         'options' => ['prompt' => ''],
                //         'pluginOptions' => [
                //             'allowClear' => true,
                //             'width'=>'100%'
                //         ],
                //     ],
                // ],
                [
                    'attribute' => 'repair_at', 
                    'format'=>'raw',
                    'label'=>'วันที่ซ่อม',
                    'value'=>  function ($model)  {
                        return '<font class="text-info">'.$model->repair_at.'</font>';
                    },
                    'filter' => false
                ],
    
                [
                    'attribute' => 'repair_service', 
                    'filter' => false
                ],
    
                [
                    'attribute' => 'repair_cost', 
                    'filter' => false
                ],
                // [
                //     'attribute' => 'jstatus.status',

                //     'format' => 'raw',
                //     'label' => 'สถานะ',
                //     'value' => function ($model) {
                //         return '<span class="badge" style="background-color:' . $model->jstatus->color . '">' . $model->jstatus->status . '</span>'; 
                //        // return empty($model->department) ? null : $model->department->dep_name;  
                //     },
                // ],
                [
                    'attribute' => 'jstatus.id',
                    'value'=> 'jstatus.status',
                    'filter' => Html::activeDropDownList($searchModel, 'jstatus_id',
                    ArrayHelper::map(jobstatus::find()->all(), 'id', 'status'),
                    ['class' => 'form-control'])
                  ],
                //'jstatus.status',
                'type.type',
                // 'department.dep_name',
    
    
                ['class' => 'yii\grid\ActionColumn'],
            ],
            
        ]); ?>
        <?php Pjax::end()?>
        
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


