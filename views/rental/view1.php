<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\rentals\models\Rental */

$this->title = 'หมายเลขการจองที่ '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'การจองพาหนะ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rental-view"><div class="box box-primary box-solid">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-file-text-o"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">

            <p>
                <?= Html::a('อนุมัติ', ['accept', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                <?= Html::a('แก้ไข', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
                <?=
                Html::a('ลบ', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'คุณแน่ใจแล้วหรือที่จะลบข้อมูลนี้ ?',
                        'method' => 'post',
                    ],
                ])
                ?>
            </p>

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
                        'value' => $model->vehicle->license,
                    ],
                    'created_at',
                    'updated_at',
                ],
            ])
            ?>
           
            <p>
             <?= Html::a('<class="box-title"><i class="glyphicon glyphicon-print"></i> พิมพ์เอกสาร', ['print', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            </p>
        </div>

    </div>