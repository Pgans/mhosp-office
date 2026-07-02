<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
?>

<div class="tbmonthlytreatment-form">

    <?php $form = ActiveForm::begin([
        'id' => 'tbmonthlytreatment-form',
    ]); ?>

    <?= $form->field($model, 'hn')->textInput(['maxlength' => true]) ?>

    <?php
    $dateFields = ['start_month', 'month2', 'month3', 'month4', 'month5', 'month6', 'month7', 'created_at'];
    foreach ($dateFields as $field) {
        echo $form->field($model, $field)->widget(DatePicker::class, [
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'options' => ['class' => 'form-control'],
        ]);
    }
    ?>

    <?= $form->field($model, 'treatment_detail')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> บันทึก', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
