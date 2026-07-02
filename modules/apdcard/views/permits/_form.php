<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Treatments;
use app\models\Status;


/* @var $this yii\web\View */
/* @var $model frontend\models\Permits */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permits-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-3">
            <?= $form->field($model, 'AN')->textInput(['maxlength' => true]) ?>
          </div>
    <div class="col-md-3">
            <?= $form->field($model, 'HN')->textInput(['maxlength' => true]) ?>
          </div>
    <div class="col-md-3">
            <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>
          </div>
     <div class="col-md-3">
        <?= $form->field($model, 'treatments_id')->dropDownList(
        ArrayHelper::map(treatments::find()->all(),'id','treatment_name'),
        ['value' => !empty($model->status) ? $model->status : 1]
        # ['prompt'=>'กรุณาเลือกสถานะ']
        # ['prompt'=>'กรุณาเลือกเพื่อใช้']
        ) ?>
          </div>
    <!--<div class="col-md-3">
        <?= $form->field($model, 'created_by')->textInput() ?>
        </div>
    <div class="col-md-3">
    <?= $form->field($model, 'created_at')->textInput() ?>
        </div>
    <div class="col-md-3">
    <?= $form->field($model, 'updated_by')->textInput() ?>
        </div>
    <div class="col-md-3">
    <?= $form->field($model, 'updated_at')->textInput() ?>-->
    
    <div class="col-md-3">
        <?= $form->field($model, 'status_id')->dropDownList(
        ArrayHelper::map(status::find()->all(),'id','status'),
		['value' =>  $model->status_id = 2]
       # ['prompt'=>'กรุณาเลือกสถานะ']
        ) ?>
          </div> 

    <div class="form-group" align="right">
        <?= Html::submitButton($model->isNewRecord ? 'บันทึก' : 'รับคืนเวชระเบียน', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
