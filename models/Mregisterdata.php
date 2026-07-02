<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_registerdata".
 *
 * @property string $ECLAIM_NO
 * @property string $HCODE
 * @property string $HN
 * @property string $AN
 * @property string $DATEADM
 * @property string $TIMEADM
 * @property string $DATEDSC
 * @property string $TIMEDSC
 * @property string $PERSON_TYPE
 * @property string $PID
 * @property string $IDTYPE
 * @property string $TITLES
 * @property string $FNAME
 * @property string $LNAME
 * @property string $DOB
 * @property string $SEX
 * @property string $WEIGHT
 * @property string $AGE
 * @property string $AGE_MM
 * @property string $MARRY_STATUS
 * @property string $NATIONS
 * @property string $OCCUPA
 * @property string $HMAIN
 * @property string $INSCL
 * @property string $MAININSCL
 * @property string $PROHMAIN
 * @property string $PROZONE
 * @property string $DEMAND_CLAIM_OPD
 * @property string $DEMAND_CLAIM_IPD
 * @property string $CLAIMCODE
 * @property string $PROJCODE
 * @property string $REFER_STATUS
 * @property string $HMAIN_REFER_IN
 * @property string $REFER_IN_CAUSE
 * @property string $HMAIN_REFER_OUT
 * @property string $REFER_OUT_CAUSE
 * @property string $REFERCODE
 * @property string $DISCHS
 * @property string $DISCHT
 * @property string $LOS
 * @property string $SVCTYPE
 * @property string $UUC
 * @property string $AETYPE
 * @property string $AEDATE
 * @property string $AETIME
 * @property string $STATUS
 * @property string $PARENT_ECLAIM_NO
 * @property string $DATETIMEADJ
 * @property string $HMAIN2
 * @property string $PROV1
 * @property string $PROV2
 * @property string $CLAIM_C
 * @property string $CLAIM_T
 * @property string $HC
 * @property string $PDX
 * @property string $PDX_NAME
 * @property string $PDX_DRDX
 * @property string $SUMS_HC
 * @property string $SUMS_PPCOM
 * @property string $SUMS_SERVICEITEM
 * @property string $TOTALPAID
 * @property string $TOTALEXPPAID
 * @property string $TOTALEMERPAID
 * @property string $DRG
 * @property string $RW
 * @property string $ADJRW
 * @property string $DRG_NHSO
 * @property string $RW_NHSO
 * @property string $ADJRW_NHSO
 * @property string $SOURCE
 * @property string $CHKFLAG
 * @property string $FILENAME
 * @property string $EXPORTDATE
 * @property string $CODE_ID
 * @property string $HCODE_SEND
 * @property string $DATE_SEND
 * @property string $FILENAME_SEND
 * @property string $DATA_TYPE
 * @property string $TYPE_RETURN
 * @property string $FILENAME_IN
 * @property string $TIME_STAMP
 * @property string $NOTEDATE
 * @property string $RECNO
 * @property string $RESNO
 * @property string $EPAC_ID
 * @property string $REP
 * @property string $ATTACHFILES
 * @property string $TRAN_ID
 * @property string $appeal_type
 * @property string $DEPARTMENT_CODE
 * @property string $DEPARTMENT_NAME
 * @property string $DOCNO
 * @property string $OWNRIGHTPID
 * @property string $OWNNAME
 * @property string $HASH
 * @property string $RELATE_INSCL
 * @property string $OPDSEQ
 * @property string $CONTRACT_TYPE
 * @property string $AESTATUS
 * @property string $ALERT_DATE
 * @property string $ALERT_TIME
 */
class Mregisterdata extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_registerdata';
    }

    /**
     * @inheritdoc
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
            [['PERSON_TYPE', 'TITLES', 'DOB', 'WEIGHT', 'AGE', 'AGE_MM', 'MARRY_STATUS', 'DEMAND_CLAIM_OPD', 'DEMAND_CLAIM_IPD', 'REFER_IN_CAUSE', 'REFER_OUT_CAUSE', 'REFERCODE', 'LOS', 'SVCTYPE', 'UUC', 'AETYPE', 'HC', 'PDX', 'SUMS_HC', 'SUMS_PPCOM', 'SUMS_SERVICEITEM', 'TOTALPAID', 'TOTALEXPPAID', 'TOTALEMERPAID', 'DRG', 'RW', 'ADJRW', 'DRG_NHSO', 'RW_NHSO', 'ADJRW_NHSO', 'SOURCE', 'CHKFLAG', 'DATE_SEND', 'DATA_TYPE', 'TYPE_RETURN', 'TIME_STAMP'], 'string', 'max' => 50],
            [['PID', 'OWNRIGHTPID'], 'string', 'max' => 13],
            [['IDTYPE', 'SEX', 'REFER_STATUS', 'STATUS', 'RELATE_INSCL', 'CONTRACT_TYPE', 'AESTATUS'], 'string', 'max' => 1],
            [['NATIONS', 'CLAIM_C', 'CLAIM_T'], 'string', 'max' => 100],
            [['ATTACHFILES', 'appeal_type'], 'string', 'max' => 2],
            [['TRAN_ID'], 'string', 'max' => 20],
            [['DEPARTMENT_CODE'], 'string', 'max' => 6],
            [['DOCNO'], 'string', 'max' => 30],
            [['HASH'], 'string', 'max' => 24],
            [['ALERT_DATE'], 'string', 'max' => 8],
            [['ECLAIM_NO'], 'unique'],
            [['ECLAIM_NO', 'HCODE', 'HN', 'DATEADM', 'TIMEADM', 'PERSON_TYPE', 'PID'], 'unique', 'targetAttribute' => ['ECLAIM_NO', 'HCODE', 'HN', 'DATEADM', 'TIMEADM', 'PERSON_TYPE', 'PID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ECLAIM_NO' => 'Eclaim  No',
            'HCODE' => 'Hcode',
            'HN' => 'Hn',
            'AN' => 'An',
            'DATEADM' => 'Dateadm',
            'TIMEADM' => 'Timeadm',
            'DATEDSC' => 'Datedsc',
            'TIMEDSC' => 'Timedsc',
            'PERSON_TYPE' => 'Person  Type',
            'PID' => 'Pid',
            'IDTYPE' => 'Idtype',
            'TITLES' => 'Titles',
            'FNAME' => 'Fname',
            'LNAME' => 'Lname',
            'DOB' => 'Dob',
            'SEX' => 'Sex',
            'WEIGHT' => 'Weight',
            'AGE' => 'Age',
            'AGE_MM' => 'Age  Mm',
            'MARRY_STATUS' => 'Marry  Status',
            'NATIONS' => 'Nations',
            'OCCUPA' => 'Occupa',
            'HMAIN' => 'Hmain',
            'INSCL' => 'Inscl',
            'MAININSCL' => 'Maininscl',
            'PROHMAIN' => 'Prohmain',
            'PROZONE' => 'Prozone',
            'DEMAND_CLAIM_OPD' => 'Demand  Claim  Opd',
            'DEMAND_CLAIM_IPD' => 'Demand  Claim  Ipd',
            'CLAIMCODE' => 'Claimcode',
            'PROJCODE' => 'Projcode',
            'REFER_STATUS' => 'Refer  Status',
            'HMAIN_REFER_IN' => 'Hmain  Refer  In',
            'REFER_IN_CAUSE' => 'Refer  In  Cause',
            'HMAIN_REFER_OUT' => 'Hmain  Refer  Out',
            'REFER_OUT_CAUSE' => 'Refer  Out  Cause',
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
            'PARENT_ECLAIM_NO' => 'Parent  Eclaim  No',
            'DATETIMEADJ' => 'Datetimeadj',
            'HMAIN2' => 'Hmain2',
            'PROV1' => 'Prov1',
            'PROV2' => 'Prov2',
            'CLAIM_C' => 'Claim  C',
            'CLAIM_T' => 'Claim  T',
            'HC' => 'Hc',
            'PDX' => 'Pdx',
            'PDX_NAME' => 'Pdx  Name',
            'PDX_DRDX' => 'Pdx  Drdx',
            'SUMS_HC' => 'Sums  Hc',
            'SUMS_PPCOM' => 'Sums  Ppcom',
            'SUMS_SERVICEITEM' => 'Sums  Serviceitem',
            'TOTALPAID' => 'Totalpaid',
            'TOTALEXPPAID' => 'Totalexppaid',
            'TOTALEMERPAID' => 'Totalemerpaid',
            'DRG' => 'Drg',
            'RW' => 'Rw',
            'ADJRW' => 'Adjrw',
            'DRG_NHSO' => 'Drg  Nhso',
            'RW_NHSO' => 'Rw  Nhso',
            'ADJRW_NHSO' => 'Adjrw  Nhso',
            'SOURCE' => 'Source',
            'CHKFLAG' => 'Chkflag',
            'FILENAME' => 'Filename',
            'EXPORTDATE' => 'Exportdate',
            'CODE_ID' => 'Code  ID',
            'HCODE_SEND' => 'Hcode  Send',
            'DATE_SEND' => 'Date  Send',
            'FILENAME_SEND' => 'Filename  Send',
            'DATA_TYPE' => 'Data  Type',
            'TYPE_RETURN' => 'Type  Return',
            'FILENAME_IN' => 'Filename  In',
            'TIME_STAMP' => 'Time  Stamp',
            'NOTEDATE' => 'Notedate',
            'RECNO' => 'Recno',
            'RESNO' => 'Resno',
            'EPAC_ID' => 'Epac  ID',
            'REP' => 'Rep',
            'ATTACHFILES' => 'Attachfiles',
            'TRAN_ID' => 'Tran  ID',
            'appeal_type' => 'Appeal Type',
            'DEPARTMENT_CODE' => 'Department  Code',
            'DEPARTMENT_NAME' => 'Department  Name',
            'DOCNO' => 'Docno',
            'OWNRIGHTPID' => 'Ownrightpid',
            'OWNNAME' => 'Ownname',
            'HASH' => 'Hash',
            'RELATE_INSCL' => 'Relate  Inscl',
            'OPDSEQ' => 'Opdseq',
            'CONTRACT_TYPE' => 'Contract  Type',
            'AESTATUS' => 'Aestatus',
            'ALERT_DATE' => 'Alert  Date',
            'ALERT_TIME' => 'Alert  Time',
        ];
    }
}
