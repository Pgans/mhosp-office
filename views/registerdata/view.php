<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Registerdata */

$this->title = $model->ECLAIM_NO;
$this->params['breadcrumbs'][] = ['label' => 'Registerdatas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="registerdata-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'ECLAIM_NO' => $model->ECLAIM_NO, 'HCODE' => $model->HCODE, 'HN' => $model->HN, 'DATEADM' => $model->DATEADM, 'TIMEADM' => $model->TIMEADM, 'PERSON_TYPE' => $model->PERSON_TYPE, 'PID' => $model->PID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'ECLAIM_NO' => $model->ECLAIM_NO, 'HCODE' => $model->HCODE, 'HN' => $model->HN, 'DATEADM' => $model->DATEADM, 'TIMEADM' => $model->TIMEADM, 'PERSON_TYPE' => $model->PERSON_TYPE, 'PID' => $model->PID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ECLAIM_NO',
            'HCODE',
            'HN',
            'AN',
            'DATEADM',
            'TIMEADM',
            'DATEDSC',
            'TIMEDSC',
            'PERSON_TYPE',
            'PID',
            'IDTYPE',
            'TITLES',
            'FNAME',
            'LNAME',
            'DOB',
            'SEX',
            'WEIGHT',
            'AGE',
            'AGE_MM',
            'MARRY_STATUS',
            'NATIONS',
            'OCCUPA',
            'HMAIN',
            'INSCL',
            'MAININSCL',
            'PROHMAIN',
            'PROZONE',
            'DEMAND_CLAIM_OPD',
            'DEMAND_CLAIM_IPD',
            'CLAIMCODE',
            'PROJCODE',
            'REFER_STATUS',
            'HMAIN_REFER_IN',
            'REFER_IN_CAUSE',
            'HMAIN_REFER_OUT',
            'REFER_OUT_CAUSE',
            'REFERCODE',
            'DISCHS',
            'DISCHT',
            'LOS',
            'SVCTYPE',
            'UUC',
            'AETYPE',
            'AEDATE',
            'AETIME',
            'STATUS',
            'PARENT_ECLAIM_NO',
            'DATETIMEADJ',
            'HMAIN2',
            'PROV1',
            'PROV2',
            'CLAIM_C',
            'CLAIM_T',
            'HC',
            'PDX',
            'PDX_NAME',
            'PDX_DRDX',
            'SUMS_HC',
            'SUMS_PPCOM',
            'SUMS_SERVICEITEM',
            'TOTALPAID',
            'TOTALEXPPAID',
            'TOTALEMERPAID',
            'DRG',
            'RW',
            'ADJRW',
            'DRG_NHSO',
            'RW_NHSO',
            'ADJRW_NHSO',
            'SOURCE',
            'CHKFLAG',
            'FILENAME',
            'EXPORTDATE',
            'CODE_ID',
            'HCODE_SEND',
            'DATE_SEND',
            'FILENAME_SEND',
            'DATA_TYPE',
            'TYPE_RETURN',
            'FILENAME_IN',
            'TIME_STAMP',
            'NOTEDATE',
            'RECNO',
            'RESNO',
            'EPAC_ID',
            'REP',
            'ATTACHFILES',
            'TRAN_ID',
            'appeal_type',
            'DEPARTMENT_CODE',
            'DEPARTMENT_NAME',
            'DOCNO',
            'OWNRIGHTPID',
            'OWNNAME',
            'HASH',
            'RELATE_INSCL',
            'OPDSEQ',
            'CONTRACT_TYPE',
            'AESTATUS',
            'ALERT_DATE',
            'ALERT_TIME',
        ],
    ]) ?>

</div>
