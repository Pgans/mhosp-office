<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_registerdata".
 *
 * @property string $ECLAIM_NO
 * @property string $HCODE
 * @property string $HN
 * @property string|null $AN
 * @property string $DATEADM
 * @property string $TIMEADM
 * @property string|null $DATEDSC
 * @property string|null $TIMEDSC
 * @property string $PERSON_TYPE
 * @property string $PID
 * @property string|null $IDTYPE
 * @property string|null $TITLES
 * @property string|null $FNAME
 * @property string|null $LNAME
 * @property string|null $DOB
 * @property string|null $SEX
 * @property string|null $WEIGHT
 * @property string|null $AGE
 * @property string|null $AGE_MM
 * @property string|null $MARRY_STATUS
 * @property string|null $NATIONS
 * @property string|null $OCCUPA
 * @property string|null $HMAIN
 * @property string|null $INSCL
 * @property string|null $MAININSCL
 * @property string|null $PROHMAIN
 * @property string|null $PROZONE
 * @property string|null $DEMAND_CLAIM_OPD
 * @property string|null $DEMAND_CLAIM_IPD
 * @property string|null $CLAIMCODE
 * @property string|null $PROJCODE
 * @property string|null $REFER_STATUS
 * @property string|null $HMAIN_REFER_IN
 * @property string|null $REFER_IN_CAUSE
 * @property string|null $HMAIN_REFER_OUT
 * @property string|null $REFER_OUT_CAUSE
 * @property string|null $REFERCODE
 * @property string|null $DISCHS
 * @property string|null $DISCHT
 * @property string|null $LOS
 * @property string|null $SVCTYPE
 * @property string|null $UUC
 * @property string|null $AETYPE
 * @property string|null $AEDATE
 * @property string|null $AETIME
 * @property string|null $STATUS
 * @property string|null $PARENT_ECLAIM_NO
 * @property string|null $DATETIMEADJ
 * @property string|null $HMAIN2
 * @property string|null $PROV1
 * @property string|null $PROV2
 * @property string|null $CLAIM_C
 * @property string|null $CLAIM_T
 * @property string|null $HC
 * @property string|null $PDX
 * @property string|null $PDX_NAME
 * @property string|null $PDX_DRDX
 * @property string|null $SUMS_HC
 * @property string|null $SUMS_PPCOM
 * @property string|null $SUMS_SERVICEITEM
 * @property string|null $TOTALPAID
 * @property string|null $TOTALEXPPAID
 * @property string|null $TOTALEMERPAID
 * @property string|null $DRG
 * @property string|null $RW
 * @property string|null $ADJRW
 * @property string|null $DRG_NHSO
 * @property string|null $RW_NHSO
 * @property string|null $ADJRW_NHSO
 * @property string|null $SOURCE
 * @property string|null $CHKFLAG
 * @property string|null $FILENAME
 * @property string|null $EXPORTDATE
 * @property string|null $CODE_ID
 * @property string|null $HCODE_SEND
 * @property string|null $DATE_SEND
 * @property string|null $FILENAME_SEND
 * @property string|null $DATA_TYPE
 * @property string|null $TYPE_RETURN
 * @property string|null $FILENAME_IN
 * @property string|null $TIME_STAMP
 * @property string|null $NOTEDATE
 * @property string|null $RECNO
 * @property string|null $RESNO
 * @property string|null $EPAC_ID
 * @property string|null $REP
 * @property string|null $ATTACHFILES
 * @property string|null $TRAN_ID
 * @property string|null $appeal_type
 * @property string|null $DEPARTMENT_CODE
 * @property string|null $DEPARTMENT_NAME
 * @property string|null $DOCNO
 * @property string|null $OWNRIGHTPID
 * @property string|null $OWNNAME
 * @property string|null $HASH
 * @property string|null $RELATE_INSCL
 * @property string|null $OPDSEQ
 * @property string|null $CONTRACT_TYPE
 * @property string|null $AESTATUS
 * @property string|null $ALERT_DATE
 * @property string|null $ALERT_TIME
 */
class Registerdata extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'm_registerdata';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ECLAIM_NO', 'HCODE', 'HN', 'DATEADM', 'TIMEADM', 'PERSON_TYPE', 'PID'], 'required'],
            [['ECLAIM_NO', 'PARENT_ECLAIM_NO'], 'string', 'max' => 11],
            [['HCODE', 'FNAME', 'LNAME', 'OCCUPA', 'HMAIN', 'INSCL', 'MAININSCL', 'PROHMAIN', 'PROZONE', 'CLAIMCODE', 'PROJCODE', 'HMAIN_REFER_IN', 'HMAIN_REFER_OUT', 'DISCHS', 'DISCHT', 'HMAIN2', 'PROV1', 'PROV2', 'PDX_NAME', 'PDX_DRDX', 'FILENAME', 'EXPORTDATE', 'CODE_ID', 'HCODE_SEND', 'FILENAME_SEND', 'FILENAME_IN', 'NOTEDATE', 'DEPARTMENT_NAME', 'OWNNAME'], 'string', 'max' => 255],
            [['HN', 'AN', 'RECNO', 'RESNO', 'OPDSEQ'], 'string', 'max' => 15],
            [['DATEADM', 'DATEDSC', 'AEDATE', 'DATETIMEADJ', 'EPAC_ID', 'REP'], 'string', 'max' => 10],
            [['TIMEADM', 'TIMEDSC', 'AETIME', 'ALERT_TIME'], 'string', 'max' => 4],
            [['PERSON_TYPE', 'TITLES', 'DOB', 'WEIGHT', 'AGE', 'AGE_MM', 'MARRY_STATUS', 'DEMAND_CLAIM_OPD', 'DEMAND_CLAIM_IPD', 'REFER_IN_CAUSE', 'REFER_OUT_CAUSE', 'REFERCODE', 'LOS', 'SVCTYPE', 'UUC', 'AETYPE', 'HC', 'PDX', 'SUMS_HC', 'SUMS_PPCOM', 'TOTALPAID', 'TOTALEXPPAID', 'TOTALEMERPAID', 'DRG', 'RW', 'ADJRW', 'DRG_NHSO', 'RW_NHSO', 'ADJRW_NHSO', 'SOURCE', 'CHKFLAG', 'DATE_SEND', 'DATA_TYPE', 'TYPE_RETURN', 'TIME_STAMP'], 'string', 'max' => 50],
            [['PID', 'OWNRIGHTPID'], 'string', 'max' => 13],
            [['IDTYPE', 'SEX', 'REFER_STATUS', 'STATUS', 'RELATE_INSCL', 'CONTRACT_TYPE', 'AESTATUS'], 'string', 'max' => 1],
            [['NATIONS', 'CLAIM_C', 'CLAIM_T'], 'string', 'max' => 100],
            [['ATTACHFILES', 'appeal_type'], 'string', 'max' => 2],
            [['TRAN_ID'], 'string', 'max' => 20],
            [['DEPARTMENT_CODE'], 'string', 'max' => 6],
            [['DOCNO'], 'string', 'max' => 30],
            [['HASH'], 'string', 'max' => 24],
            [['ALERT_DATE'], 'string', 'max' => 8],
            [['SUMS_SERVICEITEM'], 'integer'],
            [['ECLAIM_NO'], 'unique'],
            [['ECLAIM_NO', 'HCODE', 'HN', 'DATEADM', 'TIMEADM', 'PERSON_TYPE', 'PID'], 'unique', 'targetAttribute' => ['ECLAIM_NO', 'HCODE', 'HN', 'DATEADM', 'TIMEADM', 'PERSON_TYPE', 'PID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ECLAIM_NO' => 'Eclaim No',
            'HCODE' => 'Hcode',
            'HN' => 'Hn',
            'AN' => 'An',
            'DATEADM' => 'Dateadm',
            'TIMEADM' => 'Timeadm',
            'DATEDSC' => 'Datedsc',
            'TIMEDSC' => 'Timedsc',
            'PERSON_TYPE' => 'Person Type',
            'PID' => 'Pid',
            'IDTYPE' => 'Idtype',
            'TITLES' => 'Titles',
            'FNAME' => 'Fname',
            'LNAME' => 'Lname',
            'DOB' => 'Dob',
            'SEX' => 'Sex',
            'WEIGHT' => 'Weight',
            'AGE' => 'Age',
            'AGE_MM' => 'Age Mm',
            'MARRY_STATUS' => 'Marry Status',
            'NATIONS' => 'Nations',
            'OCCUPA' => 'Occupa',
            'HMAIN' => 'Hmain',
            'INSCL' => 'Inscl',
            'MAININSCL' => 'Maininscl',
            'PROHMAIN' => 'Prohmain',
            'PROZONE' => 'Prozone',
            'DEMAND_CLAIM_OPD' => 'Demand Claim Opd',
            'DEMAND_CLAIM_IPD' => 'Demand Claim Ipd',
            'CLAIMCODE' => 'Claimcode',
            'PROJCODE' => 'Projcode',
            'REFER_STATUS' => 'Refer Status',
            'HMAIN_REFER_IN' => 'Hmain Refer In',
            'REFER_IN_CAUSE' => 'Refer In Cause',
            'HMAIN_REFER_OUT' => 'Hmain Refer Out',
            'REFER_OUT_CAUSE' => 'Refer Out Cause',
            'REFERCODE' => 'Refercode',
            'DISCHS' => 'Dischs',
            'DISCHT' => 'Discht',
            'LOS' => 'Los',
            'SVCTYPE' => 'Svctype',
            'UUC' => 'Uuc',
            'AETYPE' => 'Aetype',
            'AEDATE' => 'Aedate',
            'AETIME' => 'Aetime',
            'STATUS' => 'Status',
            'PARENT_ECLAIM_NO' => 'Parent Eclaim No',
            'DATETIMEADJ' => 'Datetimeadj',
            'HMAIN2' => 'Hmain2',
            'PROV1' => 'Prov1',
            'PROV2' => 'Prov2',
            'CLAIM_C' => 'Claim C',
            'CLAIM_T' => 'Claim T',
            'HC' => 'Hc',
            'PDX' => 'Pdx',
            'PDX_NAME' => 'Pdx Name',
            'PDX_DRDX' => 'Pdx Drdx',
            'SUMS_HC' => 'Sums Hc',
            'SUMS_PPCOM' => 'Sums Ppcom',
            'SUMS_SERVICEITEM' => 'Sums Serviceitem',
            'TOTALPAID' => 'Totalpaid',
            'TOTALEXPPAID' => 'Totalexppaid',
            'TOTALEMERPAID' => 'Totalemerpaid',
            'DRG' => 'Drg',
            'RW' => 'Rw',
            'ADJRW' => 'Adjrw',
            'DRG_NHSO' => 'Drg Nhso',
            'RW_NHSO' => 'Rw Nhso',
            'ADJRW_NHSO' => 'Adjrw Nhso',
            'SOURCE' => 'Source',
            'CHKFLAG' => 'Chkflag',
            'FILENAME' => 'Filename',
            'EXPORTDATE' => 'Exportdate',
            'CODE_ID' => 'Code ID',
            'HCODE_SEND' => 'Hcode Send',
            'DATE_SEND' => 'Date Send',
            'FILENAME_SEND' => 'Filename Send',
            'DATA_TYPE' => 'Data Type',
            'TYPE_RETURN' => 'Type Return',
            'FILENAME_IN' => 'Filename In',
            'TIME_STAMP' => 'Time Stamp',
            'NOTEDATE' => 'Notedate',
            'RECNO' => 'Recno',
            'RESNO' => 'Resno',
            'EPAC_ID' => 'Epac ID',
            'REP' => 'Rep',
            'ATTACHFILES' => 'Attachfiles',
            'TRAN_ID' => 'Tran ID',
            'appeal_type' => 'Appeal Type',
            'DEPARTMENT_CODE' => 'Department Code',
            'DEPARTMENT_NAME' => 'Department Name',
            'DOCNO' => 'Docno',
            'OWNRIGHTPID' => 'Ownrightpid',
            'OWNNAME' => 'Ownname',
            'HASH' => 'Hash',
            'RELATE_INSCL' => 'Relate Inscl',
            'OPDSEQ' => 'Opdseq',
            'CONTRACT_TYPE' => 'Contract Type',
            'AESTATUS' => 'Aestatus',
            'ALERT_DATE' => 'Alert Date',
            'ALERT_TIME' => 'Alert Time',
        ];
    }
}
