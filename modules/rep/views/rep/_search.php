<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\rep\models\RepSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rep-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'REP') ?>

    <?= $form->field($model, 'SEQ') ?>

    <?= $form->field($model, 'TRAN_ID') ?>

    <?= $form->field($model, 'HN') ?>

    <?php // echo $form->field($model, 'AN') ?>

    <?php // echo $form->field($model, 'PID') ?>

    <?php // echo $form->field($model, 'FULLNAME') ?>

    <?php // echo $form->field($model, 'INSCL') ?>

    <?php // echo $form->field($model, 'REGDATE') ?>

    <?php // echo $form->field($model, 'COMPEN') ?>

    <?php // echo $form->field($model, 'INST') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
