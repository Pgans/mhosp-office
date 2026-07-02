<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\Departments;
use app\models\Award;

/* @var $this yii\web\View */
/* @var $model app\models\awards */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="award-form">

   <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->errorSummary($model); ?>

      <?= $form->field($model, 'ref')->hiddenInput()->label(false); ?> 

    <!-- <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>  -->
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'surname')->textarea(['rows' => 6]) ?>
    <div class="row">
      <div class="col-md-2">
        <div class="well text-center">
          <?= Html::img($model->getPhotoViewer(),['style'=>'width:100px;','class'=>'img-rounded']); ?>
        </div>
      </div>
      <div class="col-md-5">
            <?= $form->field($model, 'photo')->fileInput() ?>
      </div>
    </div>
   
    <div class="col-md-5">
     <?= $form->field($model, 'covenant')->widget(FileInput::classname(), [
    //'options' => ['accept' => 'image/*'],
    'pluginOptions' => [
        'initialPreview'=>$model->initialPreview($model->covenant,'covenant','file'),
        'initialPreviewConfig'=>$model->initialPreview($model->covenant,'covenant','config'),
        'allowedFileExtensions'=>['doc','docx','xls','xlsx','pdf'],
        'showPreview' => true,
        'showCaption' => true,
        'showRemove' => true,
        'showUpload' => false
     ]
    ]); ?>
    </div>

       
       <div class="form-group">
        <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> '.($model->isNewRecord ? 'บันทึกข้อมูล' : 'แก้ไข'), 
        ['class' => ($model->isNewRecord ? 'btn btn-primary' : 'btn btn-info').' btn-lg btn-block']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>