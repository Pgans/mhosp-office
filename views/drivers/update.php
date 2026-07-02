<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Drivers */

$this->title = Yii::t('app', 'แก้ไขข้อมูลพนักงานขับรถ: {name}', [
    'name' => $model->driver_id,
]);
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Drivers'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->driver_id, 'url' => ['view', 'id' => $model->driver_id]];
// $this->params['breadcrumbs'][] = Yii::t('app', 'Update');
 ?>

<div class="driver-edit"><div class="box box-primary box-solid">
        <div class="box-header" id="grad01">
            <h3 class="box-title"><i class="fa fa-user"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
