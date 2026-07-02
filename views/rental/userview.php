<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Person;
use app\models\Departments;

/* @var $this yii\web\View */
/* @var $model backend\modules\rentals\models\Rental */

$this->title = 'หมายเลขการจองที่ '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'การจองพาหนะ', 'url' => ['userindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rental-view"><div class="box box-primary box-solid">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-file-text-o"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">

            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'id',
                    [
                        'attribute' => 'status',
                        'format' => 'html',
                        'value' => Yii::$app->rentalStatus->getRentalStatus($model->status),
                    ],
                    [
                        'attribute' => 'user_id',
                        'value' => $model->user->firstname.' '.$model->user->lastname,
                    ],
                    'destination',
                    'passenger',
                    'description:ntext',
                    'date_start',
                    'date_end',
                    //'status',
                    //'user_id',
                    //'vehicle_id',
                    [
                        'attribute' => 'vehicle_id',
                        'value' => $model->vehicle->license.' คนขับรถประจำ('.$model->vehicle->driver.')' ,
                    ],
                    [
                        'attribute' => 'area',
                        'label' => 'พื้นที่',
                        'format' => 'html',
                        'value' => Yii::$app->rentalStatus->getRentalArea($model->area),
                    ],
                    [
                        'attribute' => 'dep_id',
                        'value' => $model->departments->dep_name ,
                    ],
                    [
                        'attribute' => 'user.positions_id',
                        //'value' => $model->positions->positions_name,
                    ],
                    

                    //'created_at',
                    //'updated_at',
                ],
            ])
            ?>
         
            <p>
             <?= Html::a('<class="box-title"><i class="glyphicon glyphicon-print"></i> พิมพ์เอกสาร', ['print', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
             <a  class='btn btn-primary btn-ms' href="localhost/mhosp-office/web/index.php?r=rental/index"> <i class="glyphicon glyphicon-hand-left">  </i>กลับหน้าจอง</a>
             <a  class='btn btn-warning btn-ms' href="localhost/mhosp-office/web/index.php?r=rental/calendar"> <i class="glyphicon glyphicon-hand-left">  </i>กลับหน้าปฏิทินการจอง</a>
            </p>
            </p>
        </div>
    </div>
