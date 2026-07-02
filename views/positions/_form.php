<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Positions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="positions-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'position_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> '.($model->isNewRecord ? 'บันทึก' : 'แก้ไข'), ['class' => ($model->isNewRecord ? 'btn btn-success' : 'btn btn-warning').' btn-lg btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
