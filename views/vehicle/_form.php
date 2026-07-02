<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Vehicle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vehicle-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'license')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <!-- <?= $form->field($model, 'driver')->textInput(['maxlength' => true]) ?> -->

    <?= $form->field($model, 'vehicle_img')->fileInput() ?>
    
    <?php if(!$model->isNewRecord){?>
    <?= Html::img('uploads/vehicles/'.$model->photo, ['class' =>'thumbnail img-responsive', 'width' => 300 ]);?> 
    <?php
    }
    ?>

    <div class="form-group">
    <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> '.($model->isNewRecord ? 'บันทึกรายการ' : 'แก้ไข'), 
        ['class' => ($model->isNewRecord ? 'btn btn-success' : 'btn btn-info').' btn-lg ']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
