<?php

use yii\helpers\Html;
use kartik\grid\GridView;



/* @var $this yii\web\View */
/* @var $searchModel backend\models\JobserviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ส่งซ่อมพัสดุ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-danger box-solid">
<div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">
<div class="jobservice-index">

   <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('เพิ่มส่งซ่อมพัสดุ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       //'filterModel' => $searchModel,
       'panel' =>[
        'before'=> ''
      ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'detail',
            'dateline',
            'send_by',
            'send_at',
            'updater.firstname',
            'updater.lastname',
            'updated_at',
            'repair_service',
            'repair_cost',
            //'service.device_name',
            'jstatus.status',
            'type.type',
            'signal.name',
            'department.dep_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        
    ]); ?>
</div>
