<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\module\huay\models\Bon;
use app\module\huay\models\Belows;
use app\module\huay\models\Sells;

/* @var $this yii\web\View */
/* @var $model app\module\huay\models\Lottery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lottery-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="panel panel-default">
      <div class="panel-body">
      <div class="col-md-2">
    <?= $form->field($model, 'numbers')->textInput(['maxlength' => true]) ?>
    </div>
    
    <div class="col-md-3">
    <?=$form->field($model, 'bon_id')->radioList(ArrayHelper::map(Bon::find()->all(),'bon_id','bon_price')) ?> 
    </div>

    <div class="col-md-3">
    <?=$form->field($model, 'below_id')->radioList(ArrayHelper::map(Belows::find()->all(),'below_id','below_price')) ?> 
    </div>  
    <div class="col-md-3">  
    <?=$form->field($model, 'sell_id')->radioList(ArrayHelper::map(Sells::find()->all(),'sell_id','sell_name')) ?> 
    </div>

      
        <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-success']) ?>
    

    <?php ActiveForm::end(); ?>

</div>

