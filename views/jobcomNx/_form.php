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
/* @var $model app\models\Jobcom */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jobcom-form">

    <?php $form = ActiveForm::begin(); ?>
	<div class="col-md-6"
    <?= $form->field($model, 'detail')->textInput(['maxlength' => true]) ?>
	</div>
    <div class="col-md-3
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

    <div class="col-md-3">
    <?= $form->field($model, 'send_by')->textInput() ?>
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
<!--
    <?= $form->field($model, 'send_at')->textInput() ?>

    <?= $form->field($model, 'repair_by')->textInput() ?>

    <?= $form->field($model, 'repair_at')->textInput() ?>

    <?= $form->field($model, 'repair_service')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'repair_cost')->textInput() ?>

    <?= $form->field($model, 'device_id')->textInput() ?>

    <?= $form->field($model, 'jstatus_id')->textInput() ?>

    <?= $form->field($model, 'type_id')->textInput() ?>

    <?= $form->field($model, 'dep_id')->textInput() ?>
	-->

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
