<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\jobstatus;
use app\models\devices;
use app\models\jobtype;
use app\models\DepartmentJob;
use dosamigos\datepicker\DatePicker;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model frontend\models\Jobcom */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jobcom-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'detail')->textInput(['maxlength' => true]) ?>

<div class="col-md-5">
<?= $form->field($model, 'dateline')->widget(DatePicker::className(),[
    'inline' => false,
    'clientOptions' => [
        'defaultDate' => date('Y-m-d'),
        'autoclose' => true,
        'todayHighlight' => true,
        'format' => 'yyyy-mm-dd',
    ]
  ]);?>
  </div>

    <div class="col-md-5">
    <?= $form->field($model, 'send_by')->textInput() ?>
    </div>

   <div class="col-md-3">
   <?=
                        $form->field($model, 'jstatus_id')->widget(Select2::className(), [
                            'data' => ArrayHelper::map(jobstatus::find()->all(),'id','status'),
                            'options' => [
                                'placeholder' => '<--คลิก/เลือกสถานะ-->',
                            ],
                            'pluginOptions' =>
                                [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
       </div>

        <div class="col-md-3">
                        <?=
                        $form->field($model, 'dep_id')->widget(Select2::className(), [
                            'data' =>  ArrayHelper::map(departmentjob::find()->all(),'dep_id','dep_name'),
                            'options' => [
                                'placeholder' => '<--คลิก/เลือกแผนก-->',
                            ],
                            'pluginOptions' =>
                                [
                                'allowClear' => true
                            ],
                        ]);
                        ?> 
                    </div>

        <div class="col-md-3">
                        <?=
                        $form->field($model, 'device_id')->widget(Select2::className(), [
                            'data' =>  ArrayHelper::map(devices::find()->all(),'device_id','device_serial'),
                            'options' => [
                                'placeholder' => '<--คลิก/เลือกอุปกรณ์-->',
                            ],
                            'pluginOptions' =>
                                [
                                'allowClear' => true
                            ],
                        ]);
                        ?> 
                    </div>

       
     <div class="col-md-5">
    <?= $form->field($model, 'type_id')->dropDownList(
        ArrayHelper::map(jobtype::find()->all(),'id','type'),
        ['prompt'=>'กรุณาเลือกประเภท']
        ) ?>
    </div> 

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-warning']) ?>
        <button class="btn btn-info" type="reset">ล้างข้อมูล</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
