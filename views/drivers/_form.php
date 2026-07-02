<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Drivers */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="drivers-form">
<div class="driver-view"><div class="box box-primary box-solid">
        <div class="box-header" id="grad01">
            <h3 class="box-title"><i class="fa fa-user"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
</div>


    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'driver_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
    <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> '.($model->isNewRecord ? 'บันทึกรายการ' : 'แก้ไข'), 
        ['class' => ($model->isNewRecord ? 'btn btn-success' : 'btn btn-info').' btn-lg ']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
