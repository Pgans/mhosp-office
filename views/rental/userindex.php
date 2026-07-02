<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rentals\models\RentalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'การจองพาหนะ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rental-index"><div class="box box-primary box-solid">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-file-text-o"></i> <?= Html::encode($this->title) ?>(รหัสผู้ใช้งาน : <?= Html::encode(Yii::$app->user->identity->username) ?>)</h3>
        </div>
        <div class="box-body">
            <p>
                <?= Html::a('จอง', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?=
            GridView::widget([
             
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [ 
                        'attribute' => 'status',
                        'format' => 'html',
                        'value' => function ($model) {
                            return Yii::$app->rentalStatus->getRentalStatus($model->status); //เรียกใช้ component ที่เอาไว้แสดงสถานะ
                        }
                    ],
                    'destination',
                    'description:ntext',
                    'date_start',
                    'date_end',
                            
                    [
                        'attribute' => 'user_id',
                        'value' => function($model){
                                return $model->user->firstname.' '.$model->user->lastname;
                        },
                    ],
                            
                            
                    // [
                    //     'attribute' => 'vehicle_id',
                    //     'value' => function($model){
                    //             return $model->vehicle->license;
                    //     },
                    // ],
                    [
                        'attribute' => 'area',
                        'label' => 'พื้นที่',
                        'format' => 'html',
                        'value' => Yii::$app->rentalStatus->getRentalArea($model->area),
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{view}',
                        'buttons'=>[
                                    'view' => function($url,$model,$key){
                                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',['rental/userview','id'=>$model->id]);
                                    }
                            ]
                    ],
                    
                ],
            ]);
            ?>
        </div>
    </div>