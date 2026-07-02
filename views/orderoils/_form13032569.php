<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Spray;
use app\models\Skill;
//use frontend\models\Oils;
use app\models\Province;
use app\models\Amphur;
use app\models\District;
use app\models\Mooban;
use app\models\hospanamai;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model frontend\models\Orderoils */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orderoils-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'created_at')->textInput() ?> -->
    <div class="col-md-3">
     <?= $form->field($model, 'spray_id')->label('การพ่นเชื้อเพลิง')->radioList(array('1'=>'การพ่นหมอกควัน')); ?>
     </div>
    <div>
    <?= $form->field($model, 'oils')->checkBoxList(ArrayHelper::map(Skill::find()->all(),'id','name')) ?>
    </div>
    <div class="col-md-7">
    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-5">
    <?= $form->field($model, 'diagnosis')->textInput(['maxlength' => true]) ?>
    </div>
   
    <div class="col-sm-4 col-md-4">
    <?= $form->field($model, 'province_id')->dropdownList(
            ArrayHelper::map(Province::find()->all(),
            'PROVINCE_ID',
            'PROVINCE_NAME'),
            [
                'id'=>'ddl-province',
                'prompt'=>'เลือกจังหวัด'
        ]); ?>
    </div>
    <div class="col-sm-4 col-md-4">
    <?= $form->field($model, 'amphur_id')->widget(DepDrop::classname(), [
            'options'=>['id'=>'ddl-amphur'],
            'data'=> $amphur,
            'pluginOptions'=>[
                'depends'=>['ddl-province'],
                'placeholder'=>'เลือกอำเภอ...',
                'url'=>Url::to(['/orderoils/get-amphur'])
            ]
        ]); ?>
    </div>
    <div class="col-sm-4 col-md-4">
    <?= $form->field($model, 'district_id')->widget(DepDrop::classname(), [
        'options'=>['id'=>'ddl-district'],
        'data' =>$district, 
           'pluginOptions'=>[
               'depends'=>['ddl-province', 'ddl-amphur'],
               'placeholder'=>'เลือกตำบล...',
               'url'=>Url::to(['/orderoils/get-district'])
           ]
        ]); ?>
    </div>
    <div class="col-sm-4 col-md-4">
    <?= $form->field($model, 'mooban_id')->widget(DepDrop::classname(), [
           'data' =>$mooban,
           'pluginOptions'=>[
               'depends'=>['ddl-province', 'ddl-amphur', 'ddl-district'],
               'placeholder'=>'เลือกหมู่บ้าน...',
               'url'=>Url::to(['/orderoils/get-mooban'])
           ]
        ]); ?>
    </div>

   <div class="col-md-5">
   <?=
                        $form->field($model, 'anamai_id')->widget(Select2::className(), [
                            'data' =>  ArrayHelper::map(Hospanamai::find()->all(),'anamai_id','hospname'),
                            'options' => [
                                'placeholder' => '<--คลิก/พิมพ์เลือก-->',
                            ],
                            'pluginOptions' =>
                                [
                                'allowClear' => true
                            ],
                        ]);
                        ?> 
        <!-- <?= $form->field($model, 'anamai_id')->dropDownList(
        ArrayHelper::map(Hospanamai::find()->all(),'anamai_id','hospname'),
        ['prompt'=>'กรุณา..เลือกรพสต.ที่เบิก']
        ) ?> -->
       </div>

    <div class="form-group">
    <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> '.($model->isNewRecord ? 'บันทึกข้อมูล' : 'แก้ไข'), 
        ['class' => ($model->isNewRecord ? 'btn btn-primary' : 'btn btn-info').' btn-lg ']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
