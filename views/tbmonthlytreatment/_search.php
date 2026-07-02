<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TbmonthlytreatmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbmonthlytreatment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'hn') ?>

    <?= $form->field($model, 'start_month') ?>

    <?= $form->field($model, 'month2') ?>

    <?= $form->field($model, 'month3') ?>

    <?php // echo $form->field($model, 'month4') ?>

    <?php // echo $form->field($model, 'month5') ?>

    <?php // echo $form->field($model, 'month6') ?>

    <?php // echo $form->field($model, 'month7') ?>

    <?php // echo $form->field($model, 'treatment_detail') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
