<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Registerdata */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="registerdata-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ECLAIM_NO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'HCODE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'HN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DATEADM')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TIMEADM')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DATEDSC')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TIMEDSC')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PERSON_TYPE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'IDTYPE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TITLES')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'FNAME')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'LNAME')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DOB')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SEX')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'WEIGHT')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AGE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AGE_MM')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'MARRY_STATUS')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'NATIONS')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'OCCUPA')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'HMAIN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'INSCL')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'MAININSCL')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PROHMAIN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PROZONE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DEMAND_CLAIM_OPD')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DEMAND_CLAIM_IPD')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CLAIMCODE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PROJCODE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'REFER_STATUS')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'HMAIN_REFER_IN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'REFER_IN_CAUSE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'HMAIN_REFER_OUT')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'REFER_OUT_CAUSE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'REFERCODE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DISCHS')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DISCHT')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'LOS')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SVCTYPE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'UUC')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AETYPE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AEDATE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AETIME')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'STATUS')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PARENT_ECLAIM_NO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DATETIMEADJ')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'HMAIN2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PROV1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PROV2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CLAIM_C')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CLAIM_T')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'HC')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PDX')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PDX_NAME')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PDX_DRDX')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SUMS_HC')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SUMS_PPCOM')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SUMS_SERVICEITEM')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TOTALPAID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TOTALEXPPAID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TOTALEMERPAID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DRG')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'RW')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ADJRW')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DRG_NHSO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'RW_NHSO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ADJRW_NHSO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SOURCE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CHKFLAG')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'FILENAME')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'EXPORTDATE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CODE_ID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'HCODE_SEND')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DATE_SEND')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'FILENAME_SEND')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DATA_TYPE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TYPE_RETURN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'FILENAME_IN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TIME_STAMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'NOTEDATE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'RECNO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'RESNO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'EPAC_ID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'REP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ATTACHFILES')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TRAN_ID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'appeal_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DEPARTMENT_CODE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DEPARTMENT_NAME')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'DOCNO')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'OWNRIGHTPID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'OWNNAME')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'HASH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'RELATE_INSCL')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'OPDSEQ')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CONTRACT_TYPE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'AESTATUS')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ALERT_DATE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ALERT_TIME')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
