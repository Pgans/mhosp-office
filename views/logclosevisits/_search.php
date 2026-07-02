<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LogclosevisitsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="logclosevisits-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'visit_id') ?>

    <?= $form->field($model, 'pid') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'messagecode') ?>

    <?php // echo $form->field($model, 'response') ?>

    <?php // echo $form->field($model, 'transaction_uid') ?>

    <?php // echo $form->field($model, 'users') ?>

    <?php // echo $form->field($model, 'send_date') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
