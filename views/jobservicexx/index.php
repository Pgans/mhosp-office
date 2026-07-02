<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\jobstatus;
use app\models\DepartmentJob;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\JobserviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ส่งซ่อมพัสดุ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-danger box-solid" >
<div class ="box-header" id="grad1">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">
<div class="jobcom-index">
<div class="jobservice-index">

    <p>
        <?= Html::a('แจ้งส่งซ่อมพัสดุ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
      // 'filterModel' => $searchModel,
     
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            
             [
                 'attribute' => 'id', 
                 'filter' => false
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
                'filter' => false
            ],

            [
                'attribute' => 'send_at', 
                'filter' => false
            ],

            // [
            //     'attribute' => 'updater.firstname', 
            //     'filter' => false
            // ],

            // [
            //     'attribute' => 'updater.lastname', 
            //     'filter' => false
            // ],
            // [
            //     'attribute' => 'updated_at', 
            //     'filter' => false
            // ],

            // [
            //     'attribute' => 'repair_service', 
            //     'filter' => false
            // ],

            // [
            //     'attribute' => 'repair_cost', 
            //     'filter' => false
            // ],
            [
                'attribute' => 'jstatus.id',
                 'format' => 'raw',
               'value' => function ($model) {
                return '<span class="badge" style="background-color:' . $model->jstatus->color . '">' . $model->jstatus->status . '</span>';
                
               },
               
            ],
            //     'filter' => Html::activeDropDownList($searchModel, 'jstatus_id',
            //     ArrayHelper::map(jobstatus::find()->all(), 'id', 'status'),
            //     ['class' => 'form-control'])
            //   ],
            //'jstatus.status',
            //'type.type',
            //'signal.name',
            [
                'attribute' => 'dep_id',
                'label' => 'แผนกที่แจ้ง',
                'value' => function($model) {
                        return empty($model->department) ? null : $model->department->dep_name;
                 },
                'filter' => ArrayHelper::map(Departmentjob::find()->asArray()->all(), 'dep_id', 'dep_name'),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['prompt' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width'=>'100%'
                    ],
                ],
            ],
            //'department.dep_name',


            //['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons'=>[
                            'view' => function($url,$model,$key){
                                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',['jobservice/view','id'=>$model->id]);
                            }
                    ]
            ],
        ],
        
    ]); ?>
</div>
