<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\rep\models\Rep */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rep-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'REP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SEQ')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TRAN_ID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'HN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'FULLNAME')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'INSCL')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'REGDATE')->textInput() ?>

    <?= $form->field($model, 'COMPEN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'INST')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
