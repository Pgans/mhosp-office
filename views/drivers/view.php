<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Drivers */

$this->title = $model->driver_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Drivers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="drivers-view">
<div class="driver-edit"><div class="box box-primary box-solid">
        <div class="box-header" id="grad01">
            <h3 class="box-title"><i class="fa fa-user"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->driver_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->driver_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'driver_id',
            'driver_name',
        ],
    ]) ?>

</div>
