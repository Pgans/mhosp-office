<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LotterySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lottery-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'lottery_id') ?>

    <?= $form->field($model, 'numbers') ?>

    <?= $form->field($model, 'bon_id') ?>

    <?= $form->field($model, 'below_id') ?>

    <?= $form->field($model, 'd_update') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
