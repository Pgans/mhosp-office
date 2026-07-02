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
use kartik\widgets\DateTimePicker;


/* @var $this yii\web\View */
/* @var $model frontend\models\Jobcom */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-success box-solid">
<div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">


    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'detail')->textInput(['maxlength' => true]) ?>

<div class="col-md-6"> 
            <div class="form-group">
                <?=
                $form->field($model, 'send_at')->widget(DateTimePicker::className(), [
                    'name' => 'send_at',
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
        </div>
    <div class="col-md-5">
    <?= $form->field($model, 'send_by')->textInput() ?>
    </div>

  <div class="col-md-5">
    <?= $form->field($model, 'jstatus_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(jobstatus::find()->all(), 'id', 'status'),
        'options' => [
            'placeholder' => '<--คลิก/เลือกสถานะ-->',
            'value' => $model->jstatus_id, // Set the default value here
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
</div>


        <div class="col-md-5">
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
	 <div class="col-md-6"> 
            <div class="form-group">
                <?=
                $form->field($model, 'repair_at')->widget(DateTimePicker::className(), [
                    'name' => 'repair_at',
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
        </div>
        <div class="col-md-5">
		<h4>ผู้ดูแลระบบ</h4>
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
	 <h4>ผู้ดูแลระบบ</h4>
    <?= $form->field($model, 'type_id')->dropDownList(
        ArrayHelper::map(jobtype::find()->all(),'id','type'),
        ['prompt'=>'กรุณาเลือกประเภท']
        ) ?>
    </div> 
	<div class="col-md-5">
	 <h4>ผู้ดูแลระบบ</h4>
    <?= $form->field($model, 'repair_service')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-warning']) ?>
        <button class="btn btn-info" type="reset">ล้างข้อมูล</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
