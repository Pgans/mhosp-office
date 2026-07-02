<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\jobstatus;
use app\models\departmentjob;
use kartik\widgets\Select2;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\JobcomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ระบบแจ้งซ่อมคอมพิวเตอร์';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary box-solid">
<div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">
<div class="jobcom-index">
    <p>
        <?= Html::a('สำหรับAdmin บริหารจัดการระบบแจ้งซ่อมคอมพิวเตอร์', ['create'], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

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
                [
                    'attribute' => 'jstatus.status',
                    'format' => 'raw',
                    'label' => 'สถานะ',
                    'value' => function ($model) {
                        return '<span class="badge" style="background-color:' . $model->jstatus->color . '">' . $model->jstatus->status . '</span>'; 
                       // return empty($model->department) ? null : $model->department->dep_name;  
                    },
                    
                ],
                [
                    'attribute' => 'jstatus.id',
                    'value'=> 'jstatus.status',
                    'filter' => Html::activeDropDownList($searchModel, 'jstatus_id',
                    ArrayHelper::map(jobstatus::find()->all(), 'id', 'status'),
                    ['class' => 'form-control'])
                  ],
                
                'type.type',
                // 'department.dep_name',
    
    
                ['class' => 'yii\grid\ActionColumn'],
            ],
            
        ]); ?>
    </div>