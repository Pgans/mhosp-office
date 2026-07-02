<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\date\DatePicker;
use dosamigos\ckeditor\CKEditor;


/* @var $this yii\web\View */
/* @var $model app\models\Agendaitem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agendaitem-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'meeting_agenda_id')->textInput() ?>

    <!-- <?= $form->field($model, 'ref')->hiddenInput()->label(false); ?> -->

    <?= $form->field($model, 'topic')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discription')->textarea(['rows' => 6]) ?>

    <!-- <?= $form->field($model, 'create_date')->textInput() ?> -->

    <!-- <?= $form->field($model, 'view')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
