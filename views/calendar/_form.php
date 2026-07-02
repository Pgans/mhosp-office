<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;


/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="col-md-4">
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
	</div>
	
<div class="col-md-4">
    <?=
    $form->field($model, 'start')->widget(DateTimePicker::className(), [
        'name' => 'start',
        'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
        //'type' => DateTimePicker::TYPE_INLINE,
        'layout' => '{picker}{input}{remove}',
        //'value' => '23-Feb-1982 10:10',
        'pluginOptions' => [
            'language' => 'th',
            'todayHighlight' => true,
            'autoclose' => true,
            'format' => 'yyyy-m-d hh:ii'
        ]
    ]);
    ?>
	</div>
<div class="col-md-4">
     <?=
    $form->field($model, 'end')->widget(DateTimePicker::className(), [
        'name' => 'end',
        'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
        //'type' => DateTimePicker::TYPE_INLINE,
        'layout' => '{picker}{input}{remove}',
        //'value' => '23-Feb-1982 10:10',
        'pluginOptions' => [
            'language' => 'th',
            'todayHighlight' => true,
            'autoclose' => true,
            'format' => 'yyyy-m-d hh:ii'
        ]
    ]);
    ?>
	</div>
<div class="col-md-6">
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
	</div>
<!--	
<div class="col-md-6">
    <?= $form->field($model, 'created_at')->textInput() ?>
	</div>
	
<div class="col-md-6">
    <?= $form->field($model, 'updated_at')->textInput() ?>
</div>
-->
    <div class="form-group">
        <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> '.($model->isNewRecord ? 'บันทึกการนัด' : 'แก้ไข'), 
        ['class' => ($model->isNewRecord ? 'btn btn-success' : 'btn btn-info').' btn-lg ']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
