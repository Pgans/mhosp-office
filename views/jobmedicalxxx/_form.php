<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\jobstatus;
use app\models\devices;
use app\models\jobtype;
use dosamigos\datepicker\DatePicker;
use app\models\DepartmentJob;

/* @var $this yii\web\View */
/* @var $model app\models\Jobcom */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-success box-solid">
<div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">

 <div class="jobcom-form">

    <?php $form = ActiveForm::begin([
        'id' => 'your-form',
        'enableAjaxValidation' => true,
    ]); ?>
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
	<div class="col-md-3">
   <?= $form->field($model, 'send_by')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="col-md-2">
        <?= $form->field($model, 'dep_id')->dropDownList(
        ArrayHelper::map(departmentjob::find()->all(),'dep_id','dep_name'),
        ['prompt'=>'เลือกแผนก']
        ) ?>
       </div>
	   
	 
   <!-- <?= $form->field($model, 'send_at')->textInput() ?>-->

    <!--<?= $form->field($model, 'repair_by')->textInput() ?>-->

    <!--<?= $form->field($model, 'repair_at')->textInput() ?>-->
	<!--<div class="col-md-3">
    <?= $form->field($model, 'repair_service')->textInput(['maxlength' => true]) ?>
	</div>-->
	
	<!--<div class="col-md-2">
    <?= $form->field($model, 'repair_cost')->textInput() ?>
    </div>-->
   <!-- <?= $form->field($model, 'device_id')->textInput() ?>-->

    <div class="col-md-4">
        <?= $form->field($model, 'jstatus_id')->dropDownList(
        ArrayHelper::map(jobstatus::find()->all(),'id','status'),
        [
		'prompt' => 'เลือกสถานะ',
            'options' => [
                '39' => ['selected' => true], // Set jstatus_id to 1 by default
            ],
		]
        ) ?>
       </div>
  <!--
    <div class = "col-md-5">
    <?= $form->field($model, 'type_id')->dropDownList(
        ArrayHelper::map(jobtype::find()->all(),'id','type'),
        ['prompt'=>'กรุณาเลือกประเภท']
        ) ?>
    </div>-->

   
     <div class = "col-md-5">
    <div class="form-group">
    <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> '.($model->isNewRecord ? 'บันทึกรายการ' : 'แก้ไข'), 
        ['class' => ($model->isNewRecord ? 'btn btn-success' : 'btn btn-info').' btn-lg ']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs("
    $('form#your-form').on('beforeSubmit', function(e) {
        var form = $(this);
        $.post(
            form.attr('action'),
            form.serialize()
        )
        .done(function(response) {
            if(response.success) {
                $('#createModal').modal('hide');
                $.pjax.reload({container: '#your-pjax-container'});
            } else {
                $('#createContent').html(response.content);
            }
        })
        .fail(function() {
            console.log('server error');
        });
        return false;
    });
");
?>
