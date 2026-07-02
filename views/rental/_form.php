<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Drivers;
use app\models\Vehicle;
use app\models\Departments;
use app\models\Person;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;



/* @var $this yii\web\View */
/* @var $model app\models\Rental */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rental-form">
<div class="box box-primary box-solid" >
    <div class="box-header" id="grad1">
        <div class="box-title"> ระบบการจองรถ<small> MuangSamSib Hospital</small></div>
    </div>
    <div class="box-body">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-8"><?= $form->field($model, 'destination')->textInput(['maxlength' => true]) ?></div>

   <!-- <div class="col-md-4"><?= $form->field($model, 'passenger')->textInput() ?></div> -->
   <div class="col-md-4"> <?= $form->field($model, 'passenger')
                ->dropDownList(['1' => '1 คน',
                                '2' => '2 คน',
                                '3' => '3 คน',
                                '4' => '4 คน',
                                '5' => '5 คน',
                                '6' => '6 คน',
                                '7' => '7 คน',
                                '8' => '8 คน',
                                '9' => '9 คน',
                                '10' => '10 คน',
                                '11' => '11 คน',
                                '12' => '12 คน',
                                '13' => '13 คน'
                    ])
    ?></div>

   <div class="col-md-12"><?= $form->field($model, 'description')->textarea(['rows' => 6]) ?></div>

   <div class="col-md-3"> 
    <div class="form-group">
    <?=
    $form->field($model, 'date_start')->widget(DateTimePicker::className(), [
        'name' => 'date_start',
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
</div></div>

   <div class="col-md-3"> <div class="form-group">
                <?=
                $form->field($model, 'date_end')->widget(DateTimePicker::className(), [
                    'name' => 'date_end',
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
            </div></div>

   <!-- <div class="col-md-3"><?= $form->field($model, 'create_at')->textInput() ?></div> -->

   <!-- <div class="col-md-3"><?= $form->field($model, 'update_at')->textInput() ?></div> -->
   
<div class="col-md-3"><?= $form->field($model, 'dep_id')->dropDownList(
    ArrayHelper::map(Departments::find()->all(), 'dep_id', 'dep_name'),
    ['prompt' => 'แผนก']
    ) ?></div>
 <div class="col-md-4"> <?= $form->field($model, 'status')
                ->dropDownList([0 => 'รออนุมัติ',
                                1 => 'อนุมัติ',
                                2 => 'ไม่อนุมัติ',
								3 => 'ยกเลิก'
                                
                    ])
    ?></div> 

   <!-- <div class="col-md-3"><?= $form->field($model, 'user_id')->textInput() ?></div> -->

   <div class="col-md-3"><?= $form->field($model, 'vehicle_id')->dropDownList(
    ArrayHelper::map(Vehicle::find()->all(), 'vehicle_id', 'license'),
    ['prompt' => 'ทะเบียนรถ กรณีจองว่างได้ค่ะ']
    ) ?></div>

   <div class="col-md-3">
   <?= $form->field($model, 'driver_id')->dropDownList(
    ArrayHelper::map(Drivers::find()->all(), 'driver_id', 'driver_name'),
    ['prompt' => 'พนักงานขับรถ กรณีจองว่างได้ครับ']
    ) ?>
   </div>
   <div class="col-md-3">
   <?php $model->area = $model->isNewRecord ? 'I' : $model->area ?>
    <?= $form->field($model, 'area')->radioList(array('I'=>'ในจังหวัด', 'O'=>'นอกจังหวัด')) ?>

     </div>
    <div class="form-group">
    <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> '.($model->isNewRecord ? 'บันทึกการจอง' : 'แก้ไข'), 
        ['class' => ($model->isNewRecord ? 'btn btn-success' : 'btn btn-info').' btn-lg ']) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
