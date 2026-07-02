<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\jobstatus;
use app\models\jobservices;
use app\models\Services;
use app\models\signal;
use app\models\departmentjob;
use app\models\Jobtypeservice;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Jobservice */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="box box-success box-solid">
<div class ="box-header" id="grad01">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">
<div class="jobservice-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-5">
    <?= $form->field($model, 'detail')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-3">
<?= $form->field($model, 'dateline')->widget(DatePicker::className(),[
    'inline' => false,
    'clientOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd' 
    ]
  ]);?>
  </div>


    <!-- <div class="col-md-3">
<?= $form->field($model, 'updated_at')->widget(DatePicker::className(),[
    'inline' => false,
    'clientOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd' 
    ]
  ]);?>
  </div> -->


    <div class="col-md-5">
    <?= $form->field($model, 'repair_service')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-3">
    <?= $form->field($model, 'repair_cost')->textInput() ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'jstatus_id')->dropDownList(
        ArrayHelper::map(jobstatus::find()->all(),'id','status'),
        ['prompt'=>'เลือกสถานะ']
        ) ?>
       </div>
  
    <div class = "col-md-4">
    <?= $form->field($model, 'type_id')->dropDownList(
        ArrayHelper::map(jobtypeservice::find()->all(),'id','type'),
        ['prompt'=>'กรุณาเลือกประเภท']
        ) ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'signal_id')->dropDownList(
        ArrayHelper::map(signal::find()->all(),'signal_id','name'),
        ['prompt'=>'เลือกความเร่งด่วน']
        ) ?>
        </div>

        <div class="col-md-2">
        <?= $form->field($model, 'dep_id')->dropDownList(
        ArrayHelper::map(departmentjob::find()->all(),'dep_id','dep_name'),
        ['prompt'=>'เลือกแผนก']
        ) ?>
       </div>

    <div class = "col-md-4">
    <div class="form-group">
        <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?>
        <button class="btn btn-info" type="reset">ล้างข้อมูล</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>




