<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\jobstatus;
use app\models\departmentjob;
use app\models\signal;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Jobservice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jobservice-form" style="font-family: 'Sarabun', sans-serif;">

    <?php $form = ActiveForm::begin([
        'id' => 'jobservice-form',
        // แก้ไข: ปิด Ajax Validation เป็น false เพื่อไม่ให้เด้งบันทึกเองเมื่อคลิกในช่องกรอกข้อมูล
        'enableAjaxValidation' => false, 
        'enableClientValidation' => true,
    ]); ?>

    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'detail')->textInput([
                'maxlength' => true, 
                'placeholder' => 'ระบุรายละเอียดการแจ้งซ่อม...',
                'style' => 'border-radius: 10px; padding: 10px;'
            ])->label('รายละเอียดแจ้งซ่อม <span class="text-danger">*</span>') ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'dateline')->widget(DatePicker::className(), [
                'language' => 'th',
                'inline' => false,
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ],
                'options' => [
                    'class' => 'form-control',
                    'style' => 'border-radius: 10px; padding: 10px;',
                    'placeholder' => 'เลือกวันที่ต้องการ'
                ]
            ])->label('วันที่ต้องการ <span class="text-danger">*</span>'); ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'send_by')->textInput([
                'placeholder' => 'ชื่อ-นามสกุล ผู้แจ้ง',
                'style' => 'border-radius: 10px; padding: 10px;'
            ])->label('ผู้แจ้ง <span class="text-danger">*</span>') ?>
        </div>
    </div>

    <div class="row" style="margin-top: 15px;">
        <div class="col-md-4">
            <?= $form->field($model, 'jstatus_id')->dropDownList(
                ArrayHelper::map(jobstatus::find()->all(), 'id', 'status'),
                [
                    'prompt' => '-- เลือกสถานะ --',
                    'style' => 'border-radius: 10px; height: 40px;',
                    'options' => [
                        '39' => ['selected' => true], 
                    ],
                ]
            )->label('รหัสสถานะ <i class="fa fa-info-circle text-muted"></i>') ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'dep_id')->dropDownList(
                ArrayHelper::map(departmentjob::find()->all(), 'dep_id', 'dep_name'),
                [
                    'prompt' => '-- เลือกแผนก --',
                    'style' => 'border-radius: 10px; height: 40px;'
                ]
            )->label('รหัสแผนก <span class="text-danger">*</span>') ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'signal_id')->dropDownList(
                ArrayHelper::map(signal::find()->all(), 'signal_id', 'name'),
                [
                    'prompt' => '-- ระดับความเร่งด่วน --',
                    'style' => 'border-radius: 10px; height: 40px;',
                    'options' => [
                        '2' => ['selected' => true], 
                    ],
                ]
            )->label('รหัสความเร่ง') ?>
        </div>
    </div>

    <div class="form-group text-center" style="margin-top: 40px; margin-bottom: 20px;">
        <?php 
            // ตั้งค่าดีไซน์ปุ่มตามสไตล์รูปภาพที่คุณแนบมา
            $btnText = $model->isNewRecord ? 'บันทึกรายการซ่อม' : 'ยืนยันการแก้ไขข้อมูล';
            $btnColor = $model->isNewRecord ? '#28a745' : '#00c0ef'; // เขียวสำหรับจองใหม่, ฟ้าสำหรับแก้ไข
            $btnIcon = $model->isNewRecord ? 'fa fa-plus-circle' : 'fa fa-pencil-square-o';

            echo Html::submitButton('<i class="'.$btnIcon.'"></i> ' . $btnText, [
                'class' => 'btn btn-lg',
                'style' => "background-color: $btnColor; color: #fff; border-radius: 30px; padding: 12px 50px; font-weight: bold; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1);",
                'data' => [
                    // เพิ่ม Confirm เฉพาะตอนแก้ไข เพื่อกันพลาด
                    'confirm' => !$model->isNewRecord ? 'คุณตรวจสอบข้อมูลครบถ้วนและยืนยันการแก้ไขใช่หรือไม่?' : null,
                ]
            ]); 
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<style>
    /* ปรับแต่งความ Modern ให้เหมือนรูปภาพตัวอย่าง */
    .jobservice-form label {
        font-weight: 500;
        color: #555;
        margin-bottom: 8px;
        font-size: 14px;
    }
    .form-control {
        border: 1px solid #dce4ec;
        box-shadow: none;
        transition: all 0.3s;
    }
    .form-control:focus {
        border-color: #00c0ef;
        box-shadow: 0 0 8px rgba(0,192,239,0.2);
        background-color: #fff;
    }
    .has-error .form-control {
        border-color: #dd4b39 !important;
    }
    .help-block {
        font-size: 12px;
        color: #dd4b39;
    }
</style>