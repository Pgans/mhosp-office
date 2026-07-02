<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Treatments;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use app\models\Jstatus;

/* @var $this yii\web\View */
/* @var $model frontend\models\Permits */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permits-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-6">
            <?= $form->field($model, 'AN')->textInput(['maxlength' => true]) ?>
          </div>
    <div class="col-md-6">
            <?= $form->field($model, 'HN')->textInput(['maxlength' => true]) ?>
          </div>
    <div class="col-md-6">
            <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>
        </div>
    <div class="col-md-6">
            <?= $form->field($model,'day_want')->widget(DatePicker::className(),[
        'inline' => false,
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
      ]);?>
      </div> 
     <div class="col-md-6">
        <?= $form->field($model, 'treatments_id')->dropDownList(
        ArrayHelper::map(treatments::find()->all(),'id','treatment_name'),
        ['value' => !empty($model->status) ? $model->status : 1]
        # ['prompt'=>'กรุณาเลือกเพื่อใช้']
        ) ?>
          </div>
    <!--<?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'status_id')->textInput() ?>-->
    <div class="col-md-3">
        <?= $form->field($model, 'status_id')->dropDownList(
        ArrayHelper::map(jstatus::find()->all(),'id','status'),
        ['value' => !empty($model->status) ? $model->status : 1]
       # ['prompt'=>'กรุณาเลือกสถานะ']
        ) ?>
          </div> 

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'ตกลง' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-warning' : 'btn btn-primary']) ?>
        <button class="btn btn-info" type="reset">ล้างข้อมูล</button>
    </div>
  
    
    <?php ActiveForm::end(); ?>

</div>
