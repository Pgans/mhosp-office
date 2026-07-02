<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RentalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'การจองพาหนะ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rental-index"><div class="box box-primary box-solid">
        <div class="box-header" id="grad1">
        <h3 class="box-title"><i class="fa fa-file-text-o"></i> <?= Html::encode($this->title) ?> (รหัสผู้ใช้งาน : <?= Html::encode(Yii::$app->user->identity->username) ?>)</h3>
        </div>
        <div class="box-body">
            <p>
                <?= Html::a('จอง', ['create'], ['class' => 'btn btn-success btn-lg']) ?>
            </p>
            

    <?= GridView::widget([
         'tableOptions' => [
			'class' => 'table table-striped table-hover1',
			'width'=>'100%',
			'cellspacing'=> '0'
			],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
           ['class' => 'kartik\grid\SerialColumn'],
           // 'id',
           [   
            'attribute' => 'status',
            'format' => 'html',
            'value' => function ($model) {
                return Yii::$app->rentalStatus->getRentalStatus($model->status); //เรียกใช้ component ที่เอาไว้แสดงสถานะ
            }
           ],
            'destination',
            'description:ntext',
            'passenger',
            'date_start',
            'date_end',
            'create_at',
            'update_at',
            [
                'attribute' => 'user_id',
                'value' => function($model){
                        return $model->user->firstname.' '.$model->user->lastname;
                },
            ],
            [
                'attribute' => 'vehicle_id',
                'label' =>'ทะเบียนรถ',
                'value' => function($model){
                        return $model->vehicle->license;
                },
            ],
            [
                'attribute' => 'updated_by',
                'value' => function($model){
                        return $model->updater->firstname.' '.$model->updater->lastname;
                },
            ],
           // 'driver_id',

            //['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons'=>[
                            'view' => function($url,$model,$key){
                                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',['rental/view','id'=>$model->id]);
                            }
                    ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}',
                'buttons'=>[
                            'view' => function($url,$model,$key){
                                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',['rental/update','id'=>$model->id]);
                            }
                    ]
            ],
        ],
    ]); ?>
</div>
