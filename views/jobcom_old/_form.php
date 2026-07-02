<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\jobstatus;
use app\models\devices;
use app\models\jobtype;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Jobcom */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="box box-warning box-solid">
<div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">

 <div class="jobcom-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-5">
    <?= $form->field($model, 'detail')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-5">
<?= $form->field($model, 'dateline')->widget(DatePicker::className(),[
    'inline' => false,
    'clientOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd' 
    ]
  ]);?>
  </div>

    <!-- <?= $form->field($model, 'send_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'repair_by')->textInput() ?>

    <?= $form->field($model, 'repair_at')->textInput() ?> -->

    <!-- <div class="col-md-5">
<?= $form->field($model, 'repair_at')->widget(DatePicker::className(),[
    'inline' => false,
    'clientOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd' 
    ]
  ]);?>
  </div> -->


    <div class="col-md-6">
    <?= $form->field($model, 'repair_service')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-2">
    <?= $form->field($model, 'repair_cost')->textInput() ?>
    </div>
    <div class="col-md-5">
        <?= $form->field($model, 'jstatus_id')->dropDownList(
        ArrayHelper::map(jobstatus::find()->all(),'id','status'),
        ['prompt'=>'เลือกสถานะ']
        ) ?>
       </div>

       <div class="col-md-5">
        <?= $form->field($model, 'device_id')->dropDownList(
        ArrayHelper::map(devices::find()->all(),'device_id','device_serial'),
        ['prompt'=>'กรุณารหัสครุภัณฑ์']
        ) ?>
        </div>
    <div class = "col-md-5">
    <?= $form->field($model, 'type_id')->dropDownList(
        ArrayHelper::map(jobtype::find()->all(),'id','type'),
        ['prompt'=>'กรุณาเลือกประเภท']
        ) ?>
    </div>
    <div class = "col-md-5">
    <div class="form-group">
    <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> '.($model->isNewRecord ? 'บันทึกรายการ' : 'แก้ไข'), 
        ['class' => ($model->isNewRecord ? 'btn btn-success' : 'btn btn-info').' btn-lg ']) ?>
    </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
