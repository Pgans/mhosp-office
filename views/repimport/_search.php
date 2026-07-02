<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RepimportSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="repimport-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'auto_id') ?>

    <?= $form->field($model, 'rep') ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'train_id') ?>

    <?= $form->field($model, 'hn') ?>

    <?php // echo $form->field($model, 'an') ?>

    <?php // echo $form->field($model, 'pid') ?>

    <?php // echo $form->field($model, 'fullname') ?>

    <?php // echo $form->field($model, 'main') ?>

    <?php // echo $form->field($model, 'regdate') ?>

    <?php // echo $form->field($model, 'discharge') ?>

    <?php // echo $form->field($model, 'ins') ?>

    <?php // echo $form->field($model, 'pp') ?>

    <?php // echo $form->field($model, 'errorcode') ?>

    <?php // echo $form->field($model, 'sub') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
