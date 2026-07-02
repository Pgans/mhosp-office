<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Agendaitem;
use app\models\Meetingagenda;
use yii\helpers\ArrayHelper;
//use dosamigos\ckeditor\CKEditor;
/* @var $this yii\web\View */
/* @var $model app\models\Agendasubx */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agendasubx-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-6">
    <?= $form->field($model, 'meeting_id')->dropDownList(
    ArrayHelper::map(Meetingagenda::find()->all(),'id','title'),
    ['prompt'=>'หัวข้อการประชุม']
     ) ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'agenda_id')->dropDownList(
    ArrayHelper::map(Agendaitem::find()->all(),'agenda_id','topic'),
    ['prompt'=>'กรุณาระบุวาระการประชุม']
     ) ?>
     </div>
    <?= $form->field($model, 'sub_topic')->textInput(['maxlength' => true]) ?>
	
	


    <?= $form->field($model, 'sub_description')->textarea(['rows' => 6]) ?>

     <?= $form->field($model, 'filename')->textInput(['maxlength' => true]) ?> 

     <?= $form->field($model, 'file')->fileInput() ?>

    <!-- <?= $form->field($model, 'path')->textInput(['maxlength' => true]) ?> -->


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
