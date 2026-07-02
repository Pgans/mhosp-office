<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RegisterdataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="registerdata-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ECLAIM_NO') ?>

    <?= $form->field($model, 'HCODE') ?>

    <?= $form->field($model, 'HN') ?>

    <?= $form->field($model, 'AN') ?>

    <?= $form->field($model, 'DATEADM') ?>

    <?php // echo $form->field($model, 'TIMEADM') ?>

    <?php // echo $form->field($model, 'DATEDSC') ?>

    <?php // echo $form->field($model, 'TIMEDSC') ?>

    <?php // echo $form->field($model, 'PERSON_TYPE') ?>

    <?php // echo $form->field($model, 'PID') ?>

    <?php // echo $form->field($model, 'IDTYPE') ?>

    <?php // echo $form->field($model, 'TITLES') ?>

    <?php // echo $form->field($model, 'FNAME') ?>

    <?php // echo $form->field($model, 'LNAME') ?>

    <?php // echo $form->field($model, 'DOB') ?>

    <?php // echo $form->field($model, 'SEX') ?>

    <?php // echo $form->field($model, 'WEIGHT') ?>

    <?php // echo $form->field($model, 'AGE') ?>

    <?php // echo $form->field($model, 'AGE_MM') ?>

    <?php // echo $form->field($model, 'MARRY_STATUS') ?>

    <?php // echo $form->field($model, 'NATIONS') ?>

    <?php // echo $form->field($model, 'OCCUPA') ?>

    <?php // echo $form->field($model, 'HMAIN') ?>

    <?php // echo $form->field($model, 'INSCL') ?>

    <?php // echo $form->field($model, 'MAININSCL') ?>

    <?php // echo $form->field($model, 'PROHMAIN') ?>

    <?php // echo $form->field($model, 'PROZONE') ?>

    <?php // echo $form->field($model, 'DEMAND_CLAIM_OPD') ?>

    <?php // echo $form->field($model, 'DEMAND_CLAIM_IPD') ?>

    <?php // echo $form->field($model, 'CLAIMCODE') ?>

    <?php // echo $form->field($model, 'PROJCODE') ?>

    <?php // echo $form->field($model, 'REFER_STATUS') ?>

    <?php // echo $form->field($model, 'HMAIN_REFER_IN') ?>

    <?php // echo $form->field($model, 'REFER_IN_CAUSE') ?>

    <?php // echo $form->field($model, 'HMAIN_REFER_OUT') ?>

    <?php // echo $form->field($model, 'REFER_OUT_CAUSE') ?>

    <?php // echo $form->field($model, 'REFERCODE') ?>

    <?php // echo $form->field($model, 'DISCHS') ?>

    <?php // echo $form->field($model, 'DISCHT') ?>

    <?php // echo $form->field($model, 'LOS') ?>

    <?php // echo $form->field($model, 'SVCTYPE') ?>

    <?php // echo $form->field($model, 'UUC') ?>

    <?php // echo $form->field($model, 'AETYPE') ?>

    <?php // echo $form->field($model, 'AEDATE') ?>

    <?php // echo $form->field($model, 'AETIME') ?>

    <?php // echo $form->field($model, 'STATUS') ?>

    <?php // echo $form->field($model, 'PARENT_ECLAIM_NO') ?>

    <?php // echo $form->field($model, 'DATETIMEADJ') ?>

    <?php // echo $form->field($model, 'HMAIN2') ?>

    <?php // echo $form->field($model, 'PROV1') ?>

    <?php // echo $form->field($model, 'PROV2') ?>

    <?php // echo $form->field($model, 'CLAIM_C') ?>

    <?php // echo $form->field($model, 'CLAIM_T') ?>

    <?php // echo $form->field($model, 'HC') ?>

    <?php // echo $form->field($model, 'PDX') ?>

    <?php // echo $form->field($model, 'PDX_NAME') ?>

    <?php // echo $form->field($model, 'PDX_DRDX') ?>

    <?php // echo $form->field($model, 'SUMS_HC') ?>

    <?php // echo $form->field($model, 'SUMS_PPCOM') ?>

    <?php // echo $form->field($model, 'SUMS_SERVICEITEM') ?>

    <?php // echo $form->field($model, 'TOTALPAID') ?>

    <?php // echo $form->field($model, 'TOTALEXPPAID') ?>

    <?php // echo $form->field($model, 'TOTALEMERPAID') ?>

    <?php // echo $form->field($model, 'DRG') ?>

    <?php // echo $form->field($model, 'RW') ?>

    <?php // echo $form->field($model, 'ADJRW') ?>

    <?php // echo $form->field($model, 'DRG_NHSO') ?>

    <?php // echo $form->field($model, 'RW_NHSO') ?>

    <?php // echo $form->field($model, 'ADJRW_NHSO') ?>

    <?php // echo $form->field($model, 'SOURCE') ?>

    <?php // echo $form->field($model, 'CHKFLAG') ?>

    <?php // echo $form->field($model, 'FILENAME') ?>

    <?php // echo $form->field($model, 'EXPORTDATE') ?>

    <?php // echo $form->field($model, 'CODE_ID') ?>

    <?php // echo $form->field($model, 'HCODE_SEND') ?>

    <?php // echo $form->field($model, 'DATE_SEND') ?>

    <?php // echo $form->field($model, 'FILENAME_SEND') ?>

    <?php // echo $form->field($model, 'DATA_TYPE') ?>

    <?php // echo $form->field($model, 'TYPE_RETURN') ?>

    <?php // echo $form->field($model, 'FILENAME_IN') ?>

    <?php // echo $form->field($model, 'TIME_STAMP') ?>

    <?php // echo $form->field($model, 'NOTEDATE') ?>

    <?php // echo $form->field($model, 'RECNO') ?>

    <?php // echo $form->field($model, 'RESNO') ?>

    <?php // echo $form->field($model, 'EPAC_ID') ?>

    <?php // echo $form->field($model, 'REP') ?>

    <?php // echo $form->field($model, 'ATTACHFILES') ?>

    <?php // echo $form->field($model, 'TRAN_ID') ?>

    <?php // echo $form->field($model, 'appeal_type') ?>

    <?php // echo $form->field($model, 'DEPARTMENT_CODE') ?>

    <?php // echo $form->field($model, 'DEPARTMENT_NAME') ?>

    <?php // echo $form->field($model, 'DOCNO') ?>

    <?php // echo $form->field($model, 'OWNRIGHTPID') ?>

    <?php // echo $form->field($model, 'OWNNAME') ?>

    <?php // echo $form->field($model, 'HASH') ?>

    <?php // echo $form->field($model, 'RELATE_INSCL') ?>

    <?php // echo $form->field($model, 'OPDSEQ') ?>

    <?php // echo $form->field($model, 'CONTRACT_TYPE') ?>

    <?php // echo $form->field($model, 'AESTATUS') ?>

    <?php // echo $form->field($model, 'ALERT_DATE') ?>

    <?php // echo $form->field($model, 'ALERT_TIME') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
