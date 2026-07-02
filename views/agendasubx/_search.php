<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AgendasubxSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agendasubx-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'sub_id') ?>

    <?= $form->field($model, 'meeting_id') ?>

    <?= $form->field($model, 'agenda_id') ?>

    <?= $form->field($model, 'sub_topic') ?>

    <?= $form->field($model, 'sub_description') ?>

    <?php // echo $form->field($model, 'department') ?>

    <?php // echo $form->field($model, 'filename') ?>

    <?php // echo $form->field($model, 'path') ?>

    <?php // echo $form->field($model, 'create_date') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
