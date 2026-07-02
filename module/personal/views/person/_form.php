<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Departments;
use app\models\Positions;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;


/* @var $this yii\web\View */
/* @var $model common\models\Person */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-form">

    <?php $form = ActiveForm::begin([
      'options'=> ['enctype'=>'multipart/form-data']
      ]); ?>

    <div class="col-md-3"> <?= $form->field($user, 'username')->textInput() ?></div>

    <div class="col-md-3"> <?= $form->field($user, 'password_hash')->textInput() ?></div>

    <div class="col-md-3"> <?= $form->field($user, 'email')->textInput() ?></div>

    <div class="col-md-3"> <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?></div>

    <div class="col-md-3"> <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?></div>

    <div class="col-md-3"><?= $form->field($model, 'person_img')->fileInput() ?></div>

    <div class="col-md-3">
    <?= $form->field($model, 'birthdate')->widget(DatePicker::className(),[
        'inline' => false,
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
      ]);?>
      </div>

     <div class="col-md-3">
     <?= $form->field($model, 'start_date')->widget(DatePicker::className(),[
        'inline' => false,
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
      ]);?>
      </div>
      <div class="col-md-3">
    <?= $form->field($model, 'stop_date')->widget(DatePicker::className(),[
        'inline' => false,
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'value' => '0',
        ]
      ]);?>
      </div>
     
      
     <div class="col-md-3">
     <?= $form->field($model, 'dep_id')->dropDownList(
    ArrayHelper::map(Departments::find()->all(), 'dep_id', 'dep_name'),
    ['prompt' => 'กรุณาเลือกแผนก']
    ) ?>
    </div>
    
    <div class="col-md-3">
    <?= $form->field($model, 'positions_id')->dropDownList(
    ArrayHelper::map(Positions::find()->all(), 'id', 'position_name'),
    ['prompt' => 'กรุณาเลือกตำแหน่ง']
    ) ?>
    </div>

    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>

</div>
