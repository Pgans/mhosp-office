<?php

use yii\helpers\Html;
//use app\models\Registerdata;
use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel app\models\RegisterdataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Registerdatas';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registerdata-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'before'=>'<a>รายงานข้อมูลE-Claim แยกตามREP</a>',
           // 'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ],
        'showPageSummary' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'ECLAIM_NO',
            //'HCODE',
            [
                'class'=>'kartik\grid\EditableColumn',
                'attribute' => 'REP',
             ],
           // 'REP',
            'HN',
           // 'AN',
            'DATEADM',
            //'TIMEADM',
            //'DATEDSC',
            //'TIMEDSC',
            //'PERSON_TYPE',
            'PID',
            //'IDTYPE',
            //'TITLES',
            'FNAME',
            // [
            //     'attribute' => 'FNAME' ,
            //     'value'=>function($model){
            //         return $model->FNAME.' '.$model->LNAME;
            //       }
            // ],
            //'DOB',
            //'SEX',
            //'WEIGHT',
            //'AGE',
            //'AGE_MM',
            //'MARRY_STATUS',
            //'NATIONS',
            //'OCCUPA',
            //'HMAIN',
            //'INSCL',
            [
                'attribute'=>'MAININSCL',
                'pageSummary'=> 'รวม'
            ],
            //'MAININSCL',
            //'PROHMAIN',
            //'PROZONE',
            //'DEMAND_CLAIM_OPD',
            //'DEMAND_CLAIM_IPD',
            //'CLAIMCODE',
            //'PROJCODE',
            //'REFER_STATUS',
            //'HMAIN_REFER_IN',
            //'REFER_IN_CAUSE',
            //'HMAIN_REFER_OUT',
            //'REFER_OUT_CAUSE',
            //'REFERCODE',
            //'DISCHS',
            //'DISCHT',
            //'LOS',
            //'SVCTYPE',
            //'UUC',
            //'AETYPE',
            //'AEDATE',
            //'AETIME',
            //'STATUS',
            //'PARENT_ECLAIM_NO',
            //'DATETIMEADJ',
            //'HMAIN2',
            //'PROV1',
            //'PROV2',
            //'CLAIM_C',
            //'CLAIM_T',
            //'HC',
            //'PDX',
            //'PDX_NAME',
            //'PDX_DRDX',
            //'SUMS_HC',
            //'SUMS_PPCOM',
             [
                 'class' => 'kartik\grid\EditableColumn',
                 'attribute' => 'SUMS_SERVICEITEM',
                 //'format' => ['decimal', 0] ,
                 'pageSummary' => true,
                ],
            //'SUMS_SERVICEITEM:integer',
            //'TOTALPAID',
            //'TOTALEXPPAID',
            //'TOTALEMERPAID',
            //'DRG',
            //'RW',
            //'ADJRW',
            //'DRG_NHSO',
            //'RW_NHSO',
            //'ADJRW_NHSO',
            //'SOURCE',
            //'CHKFLAG',
            //'FILENAME',
            //'EXPORTDATE',
            //'CODE_ID',
            //'HCODE_SEND',
            //'DATE_SEND',
            //'FILENAME_SEND',
            //'DATA_TYPE',
            //'TYPE_RETURN',
            //'FILENAME_IN',
            //'TIME_STAMP',
            //'NOTEDATE',
            //'RECNO',
            //'RESNO',
            //'EPAC_ID',
            //'ATTACHFILES',
            //'TRAN_ID',
            //'appeal_type',
            //'DEPARTMENT_CODE',
            //'DEPARTMENT_NAME',
            //'DOCNO',
            //'OWNRIGHTPID',
            //'OWNNAME',
            //'HASH',
            //'RELATE_INSCL',
            //'OPDSEQ',
            //'CONTRACT_TYPE',
            //'AESTATUS',
            //'ALERT_DATE',
            //'ALERT_TIME',

           // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
