<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Import */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="import-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-15">
	<?= $form->field($model, 'file')->fileInput() ?>
   <!-- <?= $form->field($model, 'file')->textInput(['maxlength' => true]) ?>-->
	</div>
   <!-- <?= $form->field($model, 'regdate')->textInput() ?>-->

    <div class="form-group">
        <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
