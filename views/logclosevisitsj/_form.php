<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Logclosevisitsj */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="logclosevisitsj-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'visit_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'messagecode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'response')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transaction_uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'users')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'send_date')->textInput() ?>

    <?= $form->field($model, 'regdate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
