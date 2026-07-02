<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Requesxthistory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="requesxthistory-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-4">
        <?= $form->field($model, 'no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-4">
    <?= $form->field($model, 'cid')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-4">
    <?= $form->field($model, 'hn')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-4">
    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-4"> <?= $form->field($model, 'assemble')
                ->dropDownList(['1' => 'การรักษาพยาบาล',
                                '2' => 'เป็นหลักฐานทางกฏหมาย',
                                '3' => 'อื่นๆ',
                                
                    ])
    ?></div>
  
    <!-- <div class="col-md-4">
    <?= $form->field($model, 'assemble')->textInput(['maxlength' => true]) ?>
    </div> -->
    <!-- <div class="col-md-4">
    <?= $form->field($model, 'created_by')->textInput() ?>
    </div> -->
    <!-- <div class="col-md-4">
    <?= $form->field($model, 'created_at')->textInput() ?>
    </div> -->
    <!-- <div class="col-md-4">
    <?= $form->field($model, 'updated_by')->textInput() ?>
    </div>
    <div class="col-md-4">
    <?= $form->field($model, 'updated_at')->textInput() ?>
    </div> -->
    <div class="col-md-4"> <?= $form->field($model, 'status_id')
                ->dropDownList(['1' => 'ขอประวัติ',
                                '2' => 'ส่งมอบเรียบร้อย',
                                '3' => 'ยกเลิก',
                                
                    ])
    ?></div>
  
  <div class="col-md-4">
    <?= $form->field($model, 'day_want')->widget(DatePicker::className(),[
    'inline' => false,
    'clientOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd' 
    ]
  ]);?>
	</div>
	 <div class="col-md-4">
        <?= $form->field($model, 'start_date')->widget(DatePicker::className(), [
            'inline' => false,
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd' 
            ]
        ]); ?>
    </div>
    
    <div class="col-md-4">
        <?= $form->field($model, 'end_date')->widget(DatePicker::className(), [
            'inline' => false,
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd' 
            ]
        ]); ?>
    </div>
    
  <div class="col-md-4">
    <?= $form->field($model, 'orther')->radioList([
        '1 ปี' => '1 ปี',
        '2 ปี' => '2 ปี',
        '3 ปี' => '3 ปี',
    ]) ?>
</div>


    <div class="form-group">
        <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> '.($model->isNewRecord ? 'บันทึกการจอง' : 'แก้ไข'), 
        ['class' => ($model->isNewRecord ? 'btn btn-success' : 'btn btn-info').' btn-lg ']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>