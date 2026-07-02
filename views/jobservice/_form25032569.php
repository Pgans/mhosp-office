<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\jobstatus;
use app\models\Services;
use app\models\signal;
use app\models\departmentjob;
use app\models\Jobtypeservice;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Jobservice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jobservice-form">

    <?php $form = ActiveForm::begin([
        'id' => 'your-form',
        'enableAjaxValidation' => true,
    ]); ?>

   <div class="col-md-5">
    <?= $form->field($model, 'detail')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-3">
<?= $form->field($model, 'dateline')->widget(DatePicker::className(), [
	 'language' => 'th',
    'inline' => false,
    'clientOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd' // รูปแบบปี-เดือน-วัน
   
    ]
]);?>

  </div>
	<div class="col-md-3">
    <?= $form->field($model, 'send_by')->textInput() ?>
    </div>

   

     <div class="col-md-3">
        <?= $form->field($model, 'jstatus_id')->dropDownList(
        ArrayHelper::map(jobstatus::find()->all(),'id','status'),
        [
		'prompt' => 'เลือกสถานะ',
            'options' => [
                '39' => ['selected' => true], 
            ],
			]
        ) ?>
       </div>

    
     <div class="col-md-2">
        <?= $form->field($model, 'dep_id')->dropDownList(
        ArrayHelper::map(departmentjob::find()->all(),'dep_id','dep_name'),
        ['prompt'=>'เลือกแผนก']
        ) ?>
       </div>

    <div class="col-md-2">
        <?= $form->field($model, 'signal_id')->dropDownList(
        ArrayHelper::map(signal::find()->all(),'signal_id','name'),
        [
		'prompt' => 'ความเร่งด่วน',
            'options' => [
                '2' => ['selected' => true], 
            ],
			]
        ) ?>
        </div>

    

    <div class="form-group">
        <div class="form-group">
    <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> '.($model->isNewRecord ? 'บันทึกรายการ' : 'แก้ไข'), 
        ['class' => ($model->isNewRecord ? 'btn btn-success' : 'btn btn-info').' btn-lg ']) ?>
    </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
