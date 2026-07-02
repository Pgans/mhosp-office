<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Logclosevisits */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="box box-success box-solid">
<div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'visit_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'messagecode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'response')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transaction_uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'users')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'send_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
