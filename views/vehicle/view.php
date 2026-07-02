<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Vehicle */

$this->title = $model->vehicle_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vehicles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="vehicle-view"><div class="box box-primary box-solid">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-bus"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">

            <p>
                <?= Html::a('แก้ไข', ['update', 'id' => $model->vehicle_id], ['class' => 'btn btn-warning']) ?>
                <?=
                Html::a('ลบ', ['delete', 'id' => $model->vehicle_id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'คุณแน่ใจแล้วหรือที่จะลบข้อมูลพาหนะนี้ ?',
                        'method' => 'post',
                    ],
                ])
                ?>
            </p>
            <div class"text-center">
                 <?= Html::img('uploads/vehicles/'.$model->photo, ['class' =>'thumbnail img-responsive' ])//ส่วนการแสดงรูปภาพที่เพิ่มเข้าไป?> 
            </div>
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'vehicle_id',
                    'license',
                    'description:ntext',
                   // 'driver',
                    //'photo',
                ],
            ])
            ?>

        </div>
    </div>

</div>
